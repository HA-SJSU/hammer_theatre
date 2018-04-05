<?php
if(!class_exists('Rm_Mailer_CampaignMonitor')){
	class Rm_Mailer_CampaignMonitor{
		private $slug;
		private $setting;
		function __construct(){

			require_once('api/campaignMonitor_api/csrest_general.php');
			require_once('api/campaignMonitor_api/csrest_clients.php');
			require_once('api/campaignMonitor_api/csrest_subscribers.php');
			add_action( 'wp_ajax_rm_get_campaignmonitor_data', array($this,'get_campaignmonitor_data' ));
			add_action( 'wp_ajax_rm_update_campaignmonitor_authentication', array($this,'update_campaignmonitor_authentication' ));
			add_action( 'wp_ajax_rm_disconnect_campaignmonitor', array($this,'disconnect_campaignmonitor' ));
			add_action( 'rainmaker_post_lead', array( &$this, 'campaignmonitor_add_subscriber' ), 10, 2); 
			add_filter( 'rainmaker_mailers', array( $this, 'init' ) );
			$this->setting  = array(
				'name' => 'Campaign Monitor',
				'parameters' => array( 'client_id', 'api_key' ),
				'where_to_find_url' => 'https://www.campaignmonitor.com/api/getting-started/?&_ga=1.18810747.338212664.1439118258#clientid'
			);
			$this->slug = 'campaignmonitor';
		}
		
		function init($mailers){
			$mailers[$this->slug] = $this->setting;
			return $mailers;
		}

		function get_campaignmonitor_data(){

			$connected = false;
			$isKeyChanged = false;
			ob_start();

			$cm_api = get_option($this->slug.'_api');
			$cm_client_id = get_option($this->slug.'_client_id');

			if( $cm_api != '' ) {
				
				$auth = array('api_key' => $cm_api);
				$wrap = new Rm_REST_General($auth);
				$res = $wrap->get_clients();
				if(!$res->was_successful()){
					$isKeyChanged = true;
					$formstyle = '';
				} else {
					$formstyle = 'style="display:none;"';
				}
			} else {
            	$formstyle = '';
            }
            $rm_form_settings = get_post_meta($_REQUEST['form_id'], 'rm_form_settings', true);
            ?>

			<div class="rm-form-row" <?php echo $formstyle; ?>>
	            <input type="text" autocomplete="off" class="auth-text-input" id="<?php echo $this->slug; ?>_client_id" name="form_data[campaignmonitor-client-id]" value="<?php echo esc_attr( $cm_client_id ); ?>" placeholder="<?php _e( "Client ID", "icegram-rainmaker" ); ?>"/>
	        </div>

            <div class="rm-form-row" <?php echo $formstyle; ?>>
				<input type="text" autocomplete="off" class="auth-text-input" id="campaignmonitor_api_key" name="form_data[campaignmonitor-auth-key]" placeholder="<?php _e( "Campaign Monitor API Key", "icegram-rainmaker" ); ?>" value="<?php echo esc_attr( $cm_api ); ?>"/>
			</div>

            <div class="rm-form-row campaignmonitor-list">
            <?php

            $cm_lists = array();

            if( $cm_api != '' && !$isKeyChanged ){
				$cm_lists = rmGetCMLists($cm_api,$cm_client_id);
            }
			
			if( !empty($cm_lists)){
				$connected = true;
			?>
				<span class="help_tip admin_field_icon" data-tip="<?php _e("Select list where  you want to sync leads.", "icegram-rainmaker" ) ?>"></span>
				
				<select id="campaignmonitor-list" class="rm-list-select" name="form_data[campaignmonitor_lists]">
			<?php
			$selected_list = (!empty($rm_form_settings['campaignmonitor_lists'])) ? $rm_form_settings['campaignmonitor_lists'] : '';

				foreach($cm_lists as $id => $name) {
				?>
					<option value="<?php echo $id; ?>" <?php selected( $selected_list , $id ,true ) ?>><?php echo $name; ?></option>
				<?php
				}
				?>
				</select>
				<?php
			}
            ?>
            </div>

            <div class="rm-form-row">
	            <?php if( $cm_api == "" ) { ?>
	            	<button id="auth-campaignmonitor" class="button button-secondary auth-button" disabled><?php _e( "Authenticate Campaign Monitor", "icegram-rainmaker" ); ?></button><span class="spinner" style="float: none;"></span>
	            <?php } else {
	            		if( $isKeyChanged ) {
	            ?>
	            	<div id="update-campaignmonitor" class="update-mailer" data-mailerslug="Campaign Monitor" data-mailer="campaignmonitor"><span><?php _e( "Your credentials seems to be changed.</br>Use different 'Campaign Monitor' credentials?", "icegram-rainmaker" ); ?></span></div><span class="spinner" style="float: none;"></span>
	            <?php
	            		} else {
	            ?>
	            	<div id="disconnect-campaignmonitor" class="disconnect-mailer button button-secondary" data-mailerslug="Campaign Monitor" data-mailer="campaignmonitor"><span><?php _e( "Use different 'Campaign Monitor' account?", "icegram-rainmaker" ); ?></span></div><span class="spinner" style="float: none;"></span>
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

		function campaignmonitor_add_subscriber($lead, $form_settings){

			if(empty($form_settings['rm_enable_list']) || empty($form_settings['rm_list_provider']) || $form_settings['rm_list_provider'] !== 'campaignmonitor'){
				return;
        	}

			$api_key = get_option('campaignmonitor_api');
			if(empty($api_key)){
				error_log("API Key is not provided", 0);
				return;
			}

			$status = 'success';
			$this->api_key = $api_key;

			$email = $lead['email'];
			$name = isset( $lead['name'] ) ? $lead['name'] : '';
			$list = isset( $form_settings['campaignmonitor_lists']) ? $form_settings['campaignmonitor_lists'] : '';
			
			$auth = array('api_key' => $this->api_key);
			$wrap = new Rm_REST_Subscribers($list, $auth);

			$result = $wrap->add(array(
			    'EmailAddress' => $email,
			    'Name' => $name,
			    'Resubscribe' => true
			));

			if(!$result->was_successful()) {
				error_log("Something went wrong. Please try again", 0);
				return;
			}
			else {
				error_log($result->response);
			}
		}

		function update_campaignmonitor_authentication(){
			$post = $_POST;

			$data = array();
			$api_key = $post['authentication_token'];
			$client_id = $_POST['clientID'];

			$this->api_key = $api_key;

			$auth = array('api_key' => $this->api_key);
			$wrap = new Rm_REST_General($auth);
			$result = $wrap->get_clients();

			if(!$result->was_successful()) {
				print_r(json_encode(array(
					'status' => "error",
					'message' => __( "Unable to authenticate. Please check client ID and API key.", "icegram-rainmaker" )
				)));
				die();
			}

			$wrap = new Rm_REST_Clients($client_id, $auth);

			$lists = $wrap->get_lists();

			$lists = $lists->response;

			$cm_lists = array();
			$html = $query = '';
			
			$html .= '<select id="campaignmonitor-list" class="rm-list-select" name="form_data[campaignmonitor_lists]">';
			foreach($lists as $offset => $list) {
				$html .= '<option value="'.$list->ListID.'">'.$list->Name.'</option>';
				$query .= $list->ListID.'|'.$list->Name.',';
				$cm_lists[$list->ListID] = $list->Name;
			}
			$html .= '</select>';
			$html .= '<span class="help_tip admin_field_icon" data-tip="'. __('Select list where  you want to sync leads.', 'icegram-rainmaker' ) .'"></span>';
			$html .= '<input type="hidden" id="mailer-all-lists" value="'.$query.'"/>';
			$html .= '<input type="hidden" id="mailer-list-action" value="update_campaignmonitor_list"/>';
			$html .= '<input type="hidden" id="mailer-list-api" value="'.$this->api_key.'"/>';

			ob_start();
			?>
			<div class="rm-form-row">
				<div id="disconnect-campaignmonitor" class="disconnect-mailer button button-secondary" data-mailerslug="Campaign Monitor" data-mailer="campaignmonitor">
					<span>
						<?php _e( "Use different 'Campaign Monitor' account?", "icegram-rainmaker" ); ?>
					</span>
				</div>
				<span class="spinner" style="float: none;"></span>
			</div>
			<?php
			$html .= ob_get_clean();

			update_option('campaignmonitor_client_id',$client_id);
			update_option('campaignmonitor_api',$api_key);
			update_option('campaignmonitor_lists',$cm_lists);

			print_r(json_encode(array(
				'status' => "success",
				'message' => $html
			)));

			die();
		}

		function disconnect_campaignmonitor(){

			delete_option( 'campaignmonitor_api' );
			delete_option( 'campaignmonitor_client_id' );
			delete_option( 'campaignmonitor_lists' );

			print_r(json_encode(array(
                'message' => "disconnected",
			)));
			die();
		}
	}
	new Rm_Mailer_CampaignMonitor;

	if( !function_exists( 'rmGetCMLists' ) ){
		function rmGetCMLists( $api_key,$client_id ) {

			$auth = array('api_key' => $api_key);
			$wrap = new Rm_REST_General($auth);
			$result = $wrap->get_clients();

			if(!$result->was_successful()) {
				print_r(json_encode(array(
					'status' => "error",
					'message' => __( "Unable to authenticate.", "icegram-rainmaker" )
				)));
				die();
			}
			$wrap = new Rm_REST_Clients($client_id, $auth);
			$lists = $wrap->get_lists();
			$lists = $lists->response;

			$cm_lists = array();
			foreach($lists as $offset => $list) {
				$cm_lists[$list->ListID] = $list->Name;
			}
			return $cm_lists;
		}
	}
}