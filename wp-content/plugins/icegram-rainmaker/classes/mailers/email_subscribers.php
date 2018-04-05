<?php
if(!class_exists('Rm_Mailer_Email_Subscribers')){
	class Rm_Mailer_Email_Subscribers{
		private $slug;
		private $setting;
		function __construct(){
	        add_action( 'rainmaker_post_lead', array( &$this, 'email_subscribers_add_subscriber' ), 10, 2); 
			add_filter( 'rainmaker_mailers', array( $this,'init' ) );
			add_action( 'wp_ajax_rm_get_email_subscribers_data', array($this,'get_email_subscribers_data' ));
			$this->setting  = array(
				'name' => 'Email Subscribers',
			);
			$this->slug = 'email_subscribers';
		}

		//Init function
		function init($mailers){
			$mailers[$this->slug] = $this->setting;
			return $mailers;
		}

		/*
		* @Since 1.0
		*/
		function get_email_subscribers_data(){

			ob_start();
			
			//this will return an array of results with the name and list_id of each mailing list
			$es_lists = es_cls_dbquery::es_view_subscriber_group();
			$rm_form_settings = get_post_meta($_REQUEST['form_id'], 'rm_form_settings', true);
			?>

			<div class="rm-form-row es-list">
	            <?php
				$selected_list = (!empty($rm_form_settings['es-list'])) ? $rm_form_settings['es-list'] : '';

				if( !empty( $es_lists ) ){
					//$connected = true;
					$html = '<span class="help_tip admin_field_icon" data-tip="'. __('Select list where  you want to sync leads', 'icegram-rainmaker' ) .'"></span>';
					$html .= '<select id="es-list" class="rm-list-select" name="form_data[es-list]">';
					foreach($es_lists as $key => $list) {
						$html .= '<option value="'.$list['es_email_group'].'" '.selected( $selected_list , $list['es_email_group'] ,false ).'>'.$list['es_email_group'].'</option>';
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
		function email_subscribers_add_subscriber($lead, $form_settings){
				
			if(empty($form_settings['rm_enable_list']) || empty($form_settings['rm_list_provider']) || $form_settings['rm_list_provider'] !== 'email_subscribers'){
				return;
        	}
        	$ig_es_optintype = get_option('ig_es_optintype');
        	if(empty($ig_es_optintype)){
				$es_settings = es_cls_settings::es_setting_select();
				$ig_es_optintype = $es_settings['es_c_optinoption'];
        	}
			$contact['es_email_name'] = (!empty($lead['name'])) ? $lead['name'] : '';
			$contact['es_email_status'] = (!empty($ig_es_optintype) && $ig_es_optintype == 'Double Opt In' ) ? 'Unconfirmed' : 'Single Opt In';
			$contact['es_email_group'] = (!empty($form_settings['es-list'])) ? $form_settings['es-list'] : 'Public';
			$contact['es_email_mail'] = $lead['email'];
			es_cls_dbquery::es_view_subscriber_ins($contact, $action = "insert");
			return true;
		}
	}
	new Rm_Mailer_Email_Subscribers;

}