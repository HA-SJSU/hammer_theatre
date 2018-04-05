<?php
/**
 * Register and enqueue a stylesheets and scripts in the Frontend.
 *
 * @see WP Enqueue Scripts Docs (https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/)
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Enqueue a CSS in the WordPress Admin.
 */
function ple_admin_style() {
        wp_register_style( 
          'ple-admin-style', // ID for Enqueuing
          PAGE_LOADING_EFFECTS_URL. 'assets/css/admin/style.css', // define( 'PAGE_LOADING_EFFECTS_URL', plugin_dir_url( __FILE__ ) )
          false, // shows at header styles
          '1.0.0' // version
        );
        wp_enqueue_style( 'ple-admin-style' ); // Enqueuing this CSS file
}
add_action( 'admin_enqueue_scripts', 'ple_admin_style' );

/**
 * Enqueue a JS in the WordPress Admin.
 */
function ple_admin_script($hook_suffix) {
    wp_register_script( 
      'ple-admin-script', // ID for Enqueuing
      PAGE_LOADING_EFFECTS_URL. 'assets/js/admin/script.js', // define( 'PAGE_LOADING_EFFECTS_URL', plugin_dir_url( __FILE__ ) )
      array('wp-color-picker', 'jquery'), // jQuery Dependency
      '1.0.0', 
      true ); // shows at the footer scripts
      // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'ple-admin-script' ); // Enqueuing this CSS file
}
add_action( 'admin_enqueue_scripts', 'ple_admin_script' );


