<?php
if(!class_exists('Rm_Mailer_Hubspot_CRM')){
	class Rm_Mailer_Hubspot_CRM{
	
		private $slug;
		private $setting;

		function __construct(){

			require_once('api/hubspot/class.lists.php');
			require_once('api/hubspot/class.contacts.php');

			add_action( 'wp_ajax_rm_get_hubspot_data', array($this,'get_hubspot_data' ));
			add_action( 'wp_ajax_rm_update_hubspot_authentication', array($this,'update_hubspot_authentication' ));
			add_action( 'wp_ajax_rm_disconnect_hubspot', array($this,'disconnect_hubspot' ));
			add_action( 'rainmaker_post_lead', array($this,'hubspot_add_subscriber' ),10,2);
			add_filter( 'rainmaker_mailers', array( $this, 'init' ) );
			
			$this->setting  = array(
				'name' => 'HubSpot',
				'parameters' => array( 'api_key' ),
				'where_to_find_url' => 'http://help.hubspot.com/articles/KCS_Article/Integrations/How-do-I-get-my-HubSpot-API-key'
			);
			$this->slug = 'hubspot';
		}
		
		function init($mailers){
			$mailers[$this->slug] = $this->setting;
			return $mailers;
		}

		function get_hubspot_data(){

			$isKeyChanged = false;
			$connected = false;
			ob_start();
			$hubspot_api = get_option('hubspot_api');
			if( $hubspot_api != '' ) {
				$listsObj = new Rm_HubSpot_Lists($hubspot_api);

				$lists = $listsObj->get_static_lists(null);
				if( isset( $lists->status ) ){
					if( $lists->status == 'error' ) {
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
			
			<div class="rm-form-row" <?php echo $formstyle; ?>>
	            <input type="text" autocomplete="off" id="hubspot_api_key" class="auth-text-input" name="form_data[hubspot-api-key]" value="<?php echo esc_attr( $hubspot_api ); ?>" placeholder="<?php _e( "HubSpot API Key", "icegram-rainmaker" ); ?>"/>
	        </div>

            <div class="rm-form-row hubspot-list">
	        <?php
	            $hs_lists = array();
	            if($hubspot_api != ''  && !$isKeyChanged) 
					$hs_lists = rmGetHSLists($hubspot_api);
				else
					$hs_lists = '';

				if( $hs_lists != '' ) $hs_lists = get_option('hubspot_lists');
				if( $hs_lists != '' ){
					$connected = true;
				?>
				<span class="help_tip admin_field_icon" data-tip="<?php _e("Select list where  you want to sync leads.", "icegram-rainmaker" ) ?>"></span>

				<select id="hubspot-list" visibleclass="rm-list-select" name="form_data[hubspot_lists]">
				<?php
					$selected_list = (!empty($rm_form_settings['hubspot_lists'])) ? $rm_form_settings['hubspot_lists'] : '';

					foreach($hs_lists as $id => $name) {
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

            	<?php if( $hubspot_api == "" ) { ?>
	            	<button id="auth-hubspot" class="button button-secondary auth-button" disabled><?php _e( "Authenticate Hubspot", "icegram-rainmaker" ); ?></button>
	            <?php } else {
	            		if( $isKeyChanged ) {
	            ?>
	            	<div id="update-<?php echo $this->slug; ?>" class="update-mailer" data-mailerslug="<?php echo $this->setting['name']; ?>" data-mailer="<?php echo $this->slug; ?>"><span><?php _e( "Your credentials seems to be changed.</br>Use different '" . $this->setting['name'] . " credentials?", "icegram-rainmaker" ); ?></span></div>
	            <?php
	            		} else {
	            ?>
	            	<div id="disconnect-hubspot" class="disconnect-mailer button button-secondary"  data-mailerslug="Hubspot" data-mailer="hubspot"><span><?php _e( "Use different 'Hubspot' account?", "icegram-rainmaker" ); ?></span></div>
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
				
		function hubspot_add_subscriber($lead, $form_settings){

			if(empty($form_settings['rm_enable_list']) || empty($form_settings['rm_list_provider']) || $form_settings['rm_list_provider'] !== 'hubspot'){
				return;
        	}

			$email = $lead['email'];
			$this->api_key = get_option('hubspot_api');
			$name = isset( $lead['name'] ) ? $lead['name'] : '';		
			$list = isset( $form_settings['hubspot_lists']) ? $form_settings['hubspot_lists'] : '';

			
			//	Check Email in MX records
			$status = 'success';
	
			$contacts = new Rm_HubSpot_Contacts($this->api_key);
		    //Create Contact
		    $params =  array('email' => $email, 'firstname' => $name );

		    try {

			    $createdContact = $contacts->create_contact($params);

			    if(isset($createdContact->{'status'}) && $createdContact->{'status'} == 'error'){
			    	$contactProfile = $createdContact->identityProfile;
			    	$contactID = $contactProfile->vid;
			    	$contacts->update_contact($contactID,$params);				    	
			    } else {
			    	$contactID = $createdContact->{'vid'};
			    }   

			    $lists = new Rm_HubSpot_Lists($this->api_key);
			   	$contacts_to_add = array($contactID);
			   	$lists->add_contacts_to_list($contacts_to_add,$list);

			} catch (Exception $e) {

				error_log("Something went wrong. Please try again.","icegram-rainmaker");
				die();
			}
						
		}

		function update_hubspot_authentication(){
			
			$post = $_POST;

			// $data = array();
			$HAPIKey = $post['api_key'];

			$listsObj = new Rm_HubSpot_Lists($HAPIKey);
			$lists = $listsObj->get_static_lists(null);
			if( isset( $lists->status ) ){
				if( $lists->status == 'error' ) {
					print_r(json_encode(array(
						'status' => "error",
						'message' => __( "Failed to authenticate. Please check API Key", "icegram-rainmaker" )
					)));
					die();
				}
			}
			
			if( is_array( $lists->lists ) && !empty( $lists->lists ) ) {
				$html = apply_filters("hubspot_list_found",$lists->lists,$HAPIKey);
			}else{

        		$html = '';
			}
			ob_start();
			?>
			<div class="rm-form-row">
				<div id='disconnect-hubspot' class='disconnect-mailer button button-secondary' data-mailerslug='Hubspot' data-mailer='hubspot'>
					<span>
						<?php echo _e( "Use different 'Hubspot' account?", "icegram-rainmaker" ); ?>
					</span>
				</div>
			</div>
			<?php 

			$html .= ob_get_clean();

			update_option('hubspot_api',$HAPIKey);
			//update_option('hubspot_lists',$hs_lists);	

			print_r(json_encode(array(
				'status' => "success",
				'message' => $html
			)));
			
			die();
		}
		
		
		function disconnect_hubspot(){
			
			delete_option( 'hubspot_api' );
			delete_option( 'hubspot_lists' );
			
			print_r(json_encode(array(
                'message' => "disconnected",
			)));
			die();
		}
	}
	new Rm_Mailer_Hubspot_CRM;

	if( !function_exists( 'rmGetHSLists' ) ){
		function rmGetHSLists( $api_key) {

			$listsObj = new Rm_HubSpot_Lists($api_key);

			$lists = $listsObj->get_static_lists(null);
			if( isset( $lists->status ) ){
				if( $lists->status == 'error' ) {
					return array();
				}
			} else {
				$hs_lists = array();
				foreach($lists->lists as $offset => $list) {
					$hs_lists[$list->listId] = $list->name;
				}
				return $hs_lists;
			}
			
		}
	}
	
}