<?php
/*
 * Plugin Name: Child Themify
 * Description: Create child themes at the click of a button
 * Version: 2.0.0
 * Plugin URI: https://github.com/johnpbloch/child-themify
 * Author: John P. Bloch
 * License: GPL-2.0+
 * Text Domain: child-themify
 * Network: true
 */

define( 'CTF_PATH', __FILE__ );
define( 'CTF_URL', plugin_dir_url( CTF_PATH ) );
define( 'CTF_VERSION', '2.0.0' );

function ctf_plugins_loaded() {
	$inc = plugin_dir_path( CTF_PATH ) . '/includes/';
	require_once $inc . 'util.php';
	require_once $inc . 'api.php';
	child_themify_api_init();
	if ( is_admin() ) {
		require_once $inc . 'admin.php';
		child_themify_admin_init();
	}
}

add_action( 'plugins_loaded', 'ctf_plugins_loaded' );
