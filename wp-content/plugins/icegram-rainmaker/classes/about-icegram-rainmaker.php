<?php
/*
 * About Rainmaker
 */

if ( !defined( 'ABSPATH' ) ) exit;

// Actions for support
add_action( 'admin_footer', 'rm_support_ticket_content' );

function rm_support_ticket_content() {
    global $current_user, $pagenow, $typenow, $rainmaker;
    $headers = '';
    if ( $pagenow != 'edit.php' ) return;
    if ( $typenow != 'rainmaker_form') return;
    if ( !( $current_user instanceof WP_User ) || !current_user_can( 'manage_options' )) return;

    if( isset( $_POST['submit_query'] ) && $_POST['submit_query'] == "Send" && !empty($_POST['client_email'])){
        check_admin_referer( 'rm-submit-query' );
        $additional_info = ( isset( $_POST['additional_information'] ) && !empty( $_POST['additional_information'] ) ) ? sanitize_text_field( $_POST['additional_information'] ) : '';
        $additional_info = str_replace( '###', '<br />', $additional_info );
        $additional_info = str_replace( array( '[', ']' ), '', $additional_info );

        $from = 'From: ';
        $from .= ( isset( $_POST['client_name'] ) && !empty( $_POST['client_name'] ) ) ? sanitize_text_field( $_POST['client_name'] ) : '';
        $from .= ' <' . sanitize_email( $_POST['client_email'] ) . '>' . "\r\n";
        $headers .= $from;
        $headers .= str_replace('From: ', 'Reply-To: ', $from);
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $message = $additional_info . '<br /><br />'.nl2br(sanitize_text_field($_POST['message'])) ;
        wp_mail( 'hello@icegram.com', sanitize_text_field($_POST['subject']), $message, $headers ); 
        header('Location: ' . $_SERVER['HTTP_REFERER'] );
    }
    ?>
    <div id="rm_post_query_form" style="display: none;">
        <?php

            if ( !wp_script_is('jquery') ) {
                wp_enqueue_script('jquery');
                wp_enqueue_style('jquery');
            }

            $first_name = get_user_meta($current_user->ID, 'first_name', true);
            $last_name = get_user_meta($current_user->ID, 'last_name', true);
            $name = $first_name . ' ' . $last_name;
            $customer_name = ( !empty( $name ) ) ? $name : $current_user->data->display_name;
            $customer_email = $current_user->data->user_email;

        ?>
        <form id="rm_form_post_query" method="POST" action="" enctype="multipart/form-data">
            <script type="text/javascript">
                jQuery(function(){
                    jQuery('input#rm_submit_query').click(function(e){
                        var error = false;

                        var client_name = jQuery('input#client_name').val();
                        if ( client_name == '' ) {
                            jQuery('input#client_name').css('border-color', 'red');
                            error = true;
                        } else {
                            jQuery('input#client_name').css('border-color', '');
                        }

                        var client_email = jQuery('input#client_email').val();
                        if ( client_email == '' ) {
                            jQuery('input#client_email').css('border-color', 'red');
                            error = true;
                        } else {
                            jQuery('input#client_email').css('border-color', '');
                        }

                        var subject = jQuery('table#rm_post_query_table input#subject').val();
                        if ( subject == '' ) {
                            jQuery('input#subject').css('border-color', 'red');
                            error = true;
                        } else {
                            jQuery('input#subject').css('border-color', '');
                        }

                        var message = jQuery('table#rm_post_query_table textarea#message').val();
                        if ( message == '' ) {
                            jQuery('textarea#message').css('border-color', 'red');
                            error = true;
                        } else {
                            jQuery('textarea#message').css('border-color', '');
                        }

                        if ( error == true ) {
                            jQuery('label#error_message').text('* All fields are compulsory.');
                            e.preventDefault();
                        } else {
                            jQuery('label#error_message').text('');
                        }
                    });

                    jQuery(".rm-contact-us a.thickbox").click( function(){
                        setTimeout(function() {
                            jQuery('#TB_ajaxWindowTitle').text('Send your query');
                        }, 0 );
                    });

                    jQuery('div#TB_ajaxWindowTitle').each(function(){
                       var window_title = jQuery(this).text(); 
                       if ( window_title.indexOf('Send your query') != -1 ) {
                           jQuery(this).remove();
                       }
                    });

                    jQuery('input,textarea').keyup(function(){
                        var value = jQuery(this).val();
                        if ( value.length > 0 ) {
                            jQuery(this).css('border-color', '');
                            jQuery('label#error_message').text('');
                        }
                    });
                    jQuery('.update-nag, .ig_st_notice').hide();

                });
            </script>
            <table id="rm_post_query_table">
                <tr>
                    <td><label for="client_name"><?php _e('Name', 'icegram-rainmaker'); ?>*</label></td>
                    <td><input type="text" class="regular-text rm_text_field" id="client_name" name="client_name" value="<?php echo $customer_name; ?>" /></td>
                </tr>
                <tr>
                    <td><label for="client_email"><?php _e('E-mail', 'icegram-rainmaker'); ?>*</label></td>
                    <td><input type="email" class="regular-text rm_text_field" id="client_email" name="client_email" value="<?php echo $customer_email; ?>" /></td>
                </tr>
                <tr>
                    <td><label for="subject"><?php _e('Subject', 'icegram-rainmaker'); ?>*</label></td>
                    <td><input type="text" class="regular-text rm_text_field" id="subject" name="subject" value="<?php echo ( !empty( $subject ) ) ? $subject : ''; ?>" /></td>
                </tr>
                <tr>
                    <td style="vertical-align: top; padding-top: 12px;"><label for="message"><?php _e('Message', 'icegram-rainmaker'); ?>*</label></td>
                    <td><textarea id="message" name="message" rows="10" cols="60"><?php echo ( !empty( $message ) ) ? $message : ''; ?></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td><label id="error_message" style="color: red;"></label></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" class="button" id="rm_submit_query" name="submit_query" value="Send" /></td>
                </tr>
            </table>
            <?php wp_nonce_field( 'rm-submit-query'); ?>
        </form>
    </div>
    <?php
}

    if ( !wp_script_is( 'thickbox' ) ) {
        if ( !function_exists( 'add_thickbox' ) ) {
            require_once ABSPATH . 'wp-includes/general-template.php';
        }
        add_thickbox();
    } 
    ?>
        <div class="wrap about-wrap icegram-rainmaker">   
            <div class="rm_about-header">
            <h1><?php echo __( 'Welcome to Rainmaker!' , 'icegram-rainmaker'); ?></h1>
            <div class="about-text rainmaker-about-text"><?php echo __( 'Thanks for installing and we hope you will enjoy using Rainmaker.' , 'icegram-rainmaker'); ?>
                <br>
                <?php _e( " Your sample form is ready!", "icegram-rainmaker" )?>
                <?php 
                   $sample_id = get_option('rm_sample_data_imported');
                        $view_rm_form = admin_url( 'edit.php?post_type=rainmaker_form' );
                    ?>
                    <p class="rm-actions">
                        <a href="<?php echo $view_rm_form; ?>"> <strong><?php _e( 'Edit & Use this form.', 'icegram-rainmaker' ); ?></strong></a>
                    </p>
            </div>
            <div class="rm-logo">
            <?php printf(__( "Version: %s", "icegram-rainmaker"), $this->version ); ?>
            </div>
            <div class="rm-support">
                    <?php _e( 'Questions? Need Help?', "icegram-rainmaker" ); ?>
                <div id="rm-contact-us" class="rm-contact-us"><a class="thickbox"  href="<?php echo admin_url() . "#TB_inline?inlineId=rm_post_query_form&post_type=rainmaker_form" ?>"><?php _e("Contact Us", "icegram-rainmaker"); ?></a></div>
            </div>
                <?php do_action('rm_about_changelog'); ?>
            </div>   
            <!-- <hr> -->
        </div>
        <?php

// Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit; 
    }
    ?>
    <div class="about_wrap">
        <br/>
        <div id="tab-description" class="plugin-description section ">
            <h1><?php _e('Description', 'icegram-rainmaker' ); ?></h1>
            <p class="rm_summary"><?php _e('Rainmaker provides you readymade form templates, styles that can be inserted anywhere on your WordPress website.', 'icegram-rainmaker' ); ?> </p>
            <h1><?php _e('Feature Overview', 'icegram-rainmaker' ); ?></h1>
                <ul class="rm_summary">
                    <li><?php _e('Readymade subscription forms', 'icegram-rainmaker' ); ?></li>
                    <li><?php _e('Ability to add any form with custom HTML', 'icegram-rainmaker' ); ?></li>
                    <li><?php _e('Elegant designs', 'icegram-rainmaker' ); ?></li>
                    <li><?php _e('No coding required (No HTML, CSS, JavaScript required)', 'icegram-rainmaker' ); ?></li>
                    <li><?php _e('Extremely simple and user-friendly', 'icegram-rainmaker' ); ?></li>
                    <li><?php _e('Easy Embedding anywhere on WordPress site(blog post, page, sidebar,etc.)', 'icegram-rainmaker' ); ?></li>
                    <li><?php _e('Go live instantly', 'icegram-rainmaker' ); ?></li>
                    <li><?php _e('Automatically saves data; no extra plugins/configurations needed', 'icegram-rainmaker' ); ?></li>
                    <li><?php _e('Complete data security', 'icegram-rainmaker' ); ?></li>
                    <li><?php _e('Easy MailChimp Integration; other services coming soon', 'icegram-rainmaker' ); ?></li>
                    <li><?php _e('Integration with other 400+ apps via IFTTT or Zapier', 'icegram-rainmaker' ); ?></li>
                </ul>
            <h1><?php _e('Best Marketing Solution', 'icegram-rainmaker' ); ?></h1>
            <p class="rm_summary"><?php _e('Full Compatibility with <strong><a href="https://wordpress.org/plugins/icegram/">Icegram</a></strong><br>
            Combine Rainmaker with Icegram and make it the best marketing tool.', 'icegram-rainmaker' ); ?></p>
        </div>
            <h1><?php echo __( 'Frequently Asked Questions', 'icegram-rainmaker' ); ?></h1>
    </div>

    <div class="wrap about-wrap">
        <ol class="rm_faq_list">
            <li class="rm_faq">
                <?php echo sprintf(__( '%s' ), '<a href="https://www.icegram.com/documentation/how-to-create-a-form-in-rainmaker/" target="_blank">' . __( 'How to Create a Form in Rainmaker?', 'icegram-rainmaker' ) . '</a>'); ?>
            </li>
            <li class="rm_faq">
                <?php echo sprintf(__( '%s' ), '<a href="https://www.icegram.com/documentation/how-to-deploy-a-rainmaker-form-on-your-website/" target="_blank">' . __( 'How to deploy a Rainmaker form on your website?', 'icegram-rainmaker' ) . '</a>' ); ?>
            </li>
            <li class="rm_faq">
                <?php echo sprintf(__( '%s' ), '<a href="https://www.icegram.com/documentation/how-to-integrate-your-data-to-a-3rd-party-webhook-from-rainmaker/" target="_blank">' . __( 'How to Integrate the Form to a 3rd Party Webhook using Rainmaker?', 'icegram-rainmaker' ) . '</a>' ); ?>
            </li>
            <li class="rm_faq">
                <?php echo sprintf(__( '%s' ), '<a href="https://www.icegram.com/documentation/how-to-enable-captcha-in-your-rainmaker-form/" target="_blank">' . __( 'How to enable captcha in your Rainmaker form?', 'icegram-rainmaker' ) . '</a>' ); ?>
            </li>
            <li class="rm_faq">
                <?php echo sprintf(__( '%s' ), '<a href="https://www.icegram.com/documentation/how-to-insert-rainmaker-form-into-icegrams-message/" target="_blank">' . __( 'How to insert Rainmaker form into Icegram’s Message?', 'icegram-rainmaker' ) . '</a>' ); ?>
            </li>
        </ol>
    </div>

