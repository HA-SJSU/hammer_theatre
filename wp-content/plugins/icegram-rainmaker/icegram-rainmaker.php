<?php
/*
 * Plugin Name: Rainmaker - The Best Readymade WP Forms Plugin
 * Plugin URI: http://www.icegram.com/addons/icegram-rainmaker/
 * Description: The complete solution to create simple forms and collect leads
 * Version: 0.29
 * Author: icegram
 * Author URI: http://www.icegram.com/
 *
 * Copyright (c) 2016 Icegram
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * Text Domain: icegram-rainmaker
 * Domain Path: /lang/
*/

function initialize_icegram_rainmaker() {
    // i18n / l10n - load translations
    load_plugin_textdomain( 'icegram-rainmaker', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' ); 
    include_once 'classes/class-icegram-rainmaker.php'; 
    $rm = new Rainmaker();
}

function install_icegram_rainmaker(){
	    // Redirect to welcome screen 
        delete_option( '_icegram_rm_activation_redirect' );      
        add_option( '_icegram_rm_activation_redirect', 'pending' );
}

add_action( 'plugins_loaded', 'initialize_icegram_rainmaker' );
register_activation_hook( __FILE__,  'install_icegram_rainmaker');