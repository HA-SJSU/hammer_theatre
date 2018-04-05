<?php
if(!class_exists('Rm_Mailer_Mailchimp')){
	class Rm_Mailer_Mailchimp{
		private $slug;
		private $setting;
		function __construct(){
			add_action( 'wp_ajax_rm_get_mailchimp_data', array($this,'get_mailchimp_data' ));
			add_action( 'wp_ajax_rm_update_mailchimp_authentication', array($this,'update_mailchimp_authentication' ));
			add_action( 'wp_ajax_rm_disconnect_mailchimp', array($this,'disconnect_mailchimp' ));
	        add_action( 'rainmaker_post_lead', array( &$this, 'mailchimp_add_subscriber' ), 10, 2); 
			add_filter( 'rainmaker_mailers', array( $this, 'init' ) );
			$this->setting  = array(
				'name' => 'MailChimp',
				'parameters' => array( 'api_key' ),
				'where_to_find_url' => 'http://kb.mailchimp.com/accounts/management/about-api-keys/#Find-or-Generate-Your-API-Key'
			);
			$this->slug = 'mailchimp';
		}

		//Init function
		function init($mailers){
			$mailers[$this->slug] = $this->setting;
			return $mailers;
		}

		/*
		* @Since 1.0
		*/
		function get_mailchimp_data(){
			$isKeyChanged = false;

			$connected = false;
			ob_start();
			$mc_api = get_option('mailchimp_api');
			$mc_double_optin = get_option('mailchimp_double_optin');

            if( $mc_api != '' ) {
            	$dash_position = strpos( $mc_api, '-' );

				if( $dash_position !== false ) {
					$api_url = 'https://' . substr( $mc_api, $dash_position + 1 ) . '.api.mailchimp.com/2.0/';
				} else {
					return false;
				}
				$method = 'lists/list';
				$data['apikey'] = $mc_api;
				$url = $api_url . $method . '.json';

				$response = wp_remote_post( $url, array(
					'body' => $data,
					'timeout' => 15,
					'headers' => array('Accept-Encoding' => ''),
					'sslverify' => false
					)
				);
				$body = wp_remote_retrieve_body( $response );

				$request = json_decode( $body );

				if( isset( $request->status ) ) {
					if( $request->status == 'error' && $request->code == 104  ) {
						$formstyle = '';
						$isKeyChanged = true;
					}
				} else {
					$formstyle = 'style="display:none;"';
				}

            } else {
            	$formstyle = '';
            }
			$rm_form_settings = get_post_meta($_REQUEST['form_id'], 'rm_form_settings', true);
            ?>
			<div class="rm-form-row" <?php echo $formstyle; ?> >
            	<input placeholder="<?php _e( "API Key", 'icegram-rainmaker'  ); ?>" type="text" autocomplete="off" id="mailchimp_api_key" class="auth-text-input" name="form_data[mailchimp-auth-key]" value="<?php echo esc_attr( $mc_api ); ?>"/>
	        </div>

            <div class="rm-form-row mailchimp-list">
	            <?php
				$mc_lists = rmGetMCLists($mc_api);
				$selected_list = (!empty($rm_form_settings['mailchimp-list'])) ? $rm_form_settings['mailchimp-list'] : '';

				if( !empty( $mc_lists ) ){
					$connected = true;
					$html = '<span class="help_tip admin_field_icon" data-tip="'. __('Select list where  you want to sync leads', 'icegram-rainmaker' ) .'"></span>';
					$html .= '<select id="mailchimp-list" class="rm-list-select" name="form_data[mailchimp-list]">';
					foreach($mc_lists as $id => $name) {
						$html .= '<option value="'.$id.'" '.selected( $selected_list , $id ,false ).'>'.$name.'</option>';
					}
					$html .= '</select>';
					echo $html;
				}
	            ?>
            </div>
            <div class="rm-form-row mailchimp-double-optin">
                   <?php
	                if(!empty($mc_lists)){
	                ?> 
	                	<label>
	                		<input type="checkbox" name="form_data[mailchimp-double-optin]" value="yes" <?php ( !empty( $rm_form_settings['mailchimp-double-optin']) ) ? checked( $rm_form_settings['mailchimp-double-optin'], 'yes' ) : ''; ?> /> <?php _e( 'Enable double opt-in?', 'icegram-rainmaker' );?>
						</label>
	                <?php 
	                }
                ?>
            </div>

            <div class="rm-form-row">
            	<?php if( $mc_api == "" ) { ?>
	            	<button id="auth-mailchimp" class="button button-secondary auth-button" disabled><?php _e( "Verify", 'icegram-rainmaker'  ); ?></button><br/><a href="<?php echo $this->setting['where_to_find_url']?>" target="_blank"><?php _e( "Find / Generate your MailChimp API key" ,"icegram-rainmaker") ?></a>
	            <?php } else {
	            		if( $isKeyChanged ) {
	            ?>
	            	<div id="update-mailchimp" class="update-mailer" data-mailerslug="MailChimp" data-mailer="mailchimp"><span><?php _e( "Your credentials seems to be changed.</br>Use different 'MailChimp' credentials?", 'icegram-rainmaker'  ); ?></span></div>
	            <?php
	            		} else {
	            ?>
	            	<div id="disconnect-mailchimp" class="disconnect-mailer button button-secondary" data-mailerslug="Mailchimp" data-mailer="mailchimp"><span><?php _e( "Use different 'MailChimp' account?", 'icegram-rainmaker'  ); ?></span></div>
	            <?php
	            		}
	            ?>
	            <?php } ?>
	        </div>

            <?php
            $content = ob_get_clean();

            $result['data'] = $content;
            $result['helplink'] = $this->setting['where_to_find_url'];
            $result['isconnected'] = $connected;
            echo json_encode($result);
            die();

		}

		/*
		* Add subscriber to mailchimp
		* @Since 1.0
		*/

		function mailchimp_add_subscriber($lead, $form_settings){
			
        	if(empty($form_settings['rm_enable_list']) || empty($form_settings['rm_list_provider']) || $form_settings['rm_list_provider'] !== 'mailchimp'){
				return;
        	}

			$api_key = get_option( 'mailchimp_api' );
			if(empty($api_key)){
				error_log("API Key is not provided", 0);
				return;
			}
			$status = 'success';
			$this->api_key = $api_key;
			$dash_position = strpos( $api_key, '-' );

			if( $dash_position !== false ) {
				$this->api_url = 'https://' . substr( $api_key, $dash_position + 1 ) . '.api.mailchimp.com/2.0/';
			}
			$method = 'lists/subscribe';
			$data = array();
			$data['apikey'] = $this->api_key;
			$data['id'] = $form_settings[$form_settings['rm_list_provider'].'-list'];
			//chk if this is working
			$data['double_optin'] = (isset($form_settings['mailchimp-double-optin'])) ? true : false;
			
			$data['email'] = array(
				'email' => $lead['email']
			);

			if( isset( $lead['name'] ) ){
				$data['merge_vars'] = array(
					'FNAME' => $lead['name']
				);
			}
			
			$api_url = $this->api_url . $method . '.json';
			$response = wp_remote_post( $api_url, array(
				'body' => $data,
				'timeout' => 15,
				'headers' => array('Accept-Encoding' => ''),
				'sslverify' => false
				)
			);
			
			// die($response);
			// test for wp errors
			if( is_wp_error( $response ) ) {
				error_log("Something went wrong. Please try again", 0);
				return;
			}

			return true;
		}

		
		/*
		* Authentication
		* @Since 1.0
		*/
		function update_mailchimp_authentication(){
			$post = $_POST;
			$data = array();
			$api_key = $post['authentication_token'];
			$this->api_url = '';
			
			if( $api_key == "" ){
				print_r(json_encode(array(
					'status' => "error",
					'message' => __( "Please provide valid API Key for your mailchimp account.", 'icegram-rainmaker'  )
				)));
				die();
			}

			$this->api_key = $api_key;
			$dash_position = strpos( $api_key, '-' );

			if( $dash_position !== false ) {
				$this->api_url = 'https://' . substr( $api_key, $dash_position + 1 ) . '.api.mailchimp.com/2.0/';
			}
			$method = 'lists/list';
			$data['apikey'] = $this->api_key;
			$url = $this->api_url . $method . '.json';

			$response = wp_remote_post( $url, array(
				'body' => $data,
				'timeout' => 15,
				'headers' => array('Accept-Encoding' => ''),
				'sslverify' => false
				)
			);

			// test for wp errors
			if( is_wp_error( $response ) ) {

				print_r(json_encode(array(
					'status' => "error",
					'message' => "HTTP Error: " . $response->get_error_message()
				)));
				die();
			}

			$body = wp_remote_retrieve_body( $response );
			$request = json_decode( $body );
			$lists = (array)$request->data;
			$mc_lists = array();
			$html = $query = '';
            $html .= '<input placeholder="API Key"type="text" autocomplete="off" id="mailchimp_api_key" class="auth-text-input" name="form_data[mailchimp-auth-key]" value="" style="display:none"/>';
			$html .= '<div class="rm-field-row mailchimp-list">';
			$html .= '<select id="mailchimp-list" class="rm-list-select" name="form_data[mailchimp-list]">';
			foreach($lists as $offset => $list) {
				$html .= '<option value="'.$list->id.'">'.$list->name.'</option>';
				$query .= $list->id.'|'.$list->name.',';
				$mc_lists[$list->id] = $list->name;
			}
			$html .= '</select>';
			$html .= '<span class="help_tip admin_field_icon" data-tip="'. __('Select list where  you want to sync leads', 'icegram-rainmaker' ) .'"></span>';
			$html .= '<br/><label><input type="checkbox" name="form_data[mailchimp-double-optin]" value="yes"/>'.__( 'Enable double opt-in?', 'icegram-rainmaker' ).'</label></div>';
			$html .= '<input type="hidden" id="mailer-all-lists" value="'.$query.'"/>';
			$html .= '<input type="hidden" id="mailer-list-action" value="update_mailchimp_list"/>';
			$html .= '<input type="hidden" id="mailer-list-api" value="'.$this->api_key.'"/>';
			ob_start();
			?>
			<div class="rm-form-row">
				<div id="disconnect-mailchimp" class="disconnect-mailer button button-secondary" data-mailerslug="Mailchimp" data-mailer="mailchimp">
					<span>
						<?php _e( "Use different 'MailChimp' account?", 'icegram-rainmaker'  ); ?>
					</span>    
				</div>
			</div>
			<?php
			$html .= ob_get_clean();

			update_option('mailchimp_api',$api_key);
			update_option('mailchimp_lists',$mc_lists);
			//update_option('mailchimp_double_optin',$post['mailchimp_double_optin']);

			print_r(json_encode(array(
				'status' => "success",
				'message' => $html
			)));

			die();
		}

		/*
		* Disconnect mailchimp
		* @Since 1.0
		*/
		function disconnect_mailchimp(){
			delete_option( 'mailchimp_api' );
			delete_option( 'mailchimp_lists' );
			// delete_option( 'mailchimp_double_optin' );
			print_r(json_encode(array(
                'message' => "disconnected",
			)));
			die();
		}
	}
	new Rm_Mailer_Mailchimp;

	if( !function_exists( 'rmGetMCLists' ) ){
		/*
		* Get lists from mailchimp
		* @Since 1.0
		*/
		function rmGetMCLists( $api_key ) {
			$api_key = $api_key;
			$data = array();
			$dash_position = strpos( $api_key, '-' );

			if( $dash_position !== false ) {
				$api_url = 'https://' . substr( $api_key, $dash_position + 1 ) . '.api.mailchimp.com/2.0/';
			} else {
				return false;
			}
			$method = 'lists/list';
			$data['apikey'] = $api_key;
			$url = $api_url . $method . '.json';

			$response = wp_remote_post( $url, array(
				'body' => $data,
				'timeout' => 15,
				'headers' => array('Accept-Encoding' => ''),
				'sslverify' => false
				)
			);

			// test for wp errors
			if( is_wp_error( $response ) ) {
				return false;
				exit;
			}

			$body = wp_remote_retrieve_body( $response );
			$request = json_decode( $body );
			if( isset( $request->status ) ) {
				if( $request->status == 'error' && $request->code == 104 ){
					return array();
				}
			} else {
				$lists = (array)$request->data;
				$mc_lists = array();
				foreach($lists as $offset => $list) {
					$mc_lists[$list->id] = $list->name;
				}
				return $mc_lists;
			}

		}
	}

}