<?php
if(!class_exists('Rm_Mailer_MailPoet')){
	class Rm_Mailer_MailPoet{

		private $slug;
		private $setting;
		
		function __construct(){
			add_action('wp_ajax_rm_get_mailpoet_data', array($this,'get_mailpoet_data'));
	        add_action('rainmaker_post_lead', array( &$this, 'mailpoet_add_subscriber'), 10, 2); 
			add_filter('rainmaker_mailers', array( $this,'init'));
			
			$this->setting  = array(
				'name' => 'MailPoet',
			);
			$this->slug = 'mailpoet';
		}

		function init($mailers){
			$mailers[$this->slug] = $this->setting;
			return $mailers;
		}

		function get_mailpoet_data(){

			ob_start();
			
			//this will return an array of results with the name and list_id of each mailing list
			$model_list = WYSIJA::get('list','model');
			

			$mailpoet_lists = $model_list->get(array('name','list_id'),array('is_enabled'=>1));

			$rm_form_settings = get_post_meta($_REQUEST['form_id'], 'rm_form_settings', true);
			?>

			<div class="rm-form-row mailpoet-list">
	            <?php
				$selected_list = (!empty($rm_form_settings['mailpoet-list'])) ? $rm_form_settings['mailpoet-list'] : '';

				if( !empty( $mailpoet_lists ) ){
					//$connected = true;
					$html = '<span class="help_tip admin_field_icon" data-tip="'. __('Select list where  you want to sync leads', 'icegram-rainmaker' ) .'"></span>';
					$html .= '<select id="mailpoet-list" class="rm-list-select" name="form_data[mailpoet-list]">';
					foreach($mailpoet_lists as $list) {
						$html .= '<option value="'.$list['list_id'].'" '.selected( $selected_list , $list['list_id'] ,false ).'>'.$list['name'].'</option>';
					}
					$html .= '</select>';
					echo $html;
				}
	            ?>
            </div>
			
			<?php
			
				$content = ob_get_clean();
	            $result['data'] = $content;
	            echo json_encode($result);
	            die();


		}
		/*
		* Add subscriber to Email Subscriber
		* @Since 1.0
		*/
		function mailpoet_add_subscriber($lead, $form_settings){

			if(empty($form_settings['rm_enable_list']) || empty($form_settings['rm_list_provider']) || $form_settings['rm_list_provider'] !== 'mailpoet'){
				return;
        	}
			
			$name  = isset( $lead['name'] ) ? $lead['name'] : '';
			$email = $lead['email'];
			$list  = isset( $form_settings['mailpoet-list']) ? $form_settings['mailpoet-list'] : '';
			
		
		    //in this array firstname and lastname are optional
		    $user_data = array(
		        'email' => $email,
		        'firstname' => $name);

		    $data_subscriber = array(
		      'user' => $user_data,
		      'user_list' => array('list_ids' => array($form_settings['mailpoet-list']))
		    );
		 
		    $helper_user = WYSIJA::get('user','helper');
		    $helper_user->addSubscriber($data_subscriber);
		}
	}
	new Rm_Mailer_MailPoet;

}