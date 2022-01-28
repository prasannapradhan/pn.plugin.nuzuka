<?php
/**
 * Plugin Name: Nuzuka Lead Generation System
 * Description: Nuzuka Lead Generation System for generating leads and working on them
 * Author: Nuzuka Technology Team
 * Author URI: https://nuzuka.com
 * Version: 0.1
 * Plugin URI: https://github.com/prasannapradhan/nuzuka-lgs
 */

define( 'PLUGIN_NAME_VERSION', '1.0.0' );

function nuzuka_json_basic_auth_handler( $user ) {
	global $wp_json_basic_auth_error;

	$wp_json_basic_auth_error = null;

	// Don't authenticate twice
	if ( ! empty( $user ) ) {
		return $user;
	}

	// Check that we're trying to authenticate
	if ( !isset( $_SERVER['PHP_AUTH_USER'] ) ) {
		return $user;
	}

	$username = $_SERVER['PHP_AUTH_USER'];
	$password = $_SERVER['PHP_AUTH_PW'];

	remove_filter( 'determine_current_user', 'nuzuka_json_basic_auth_handler', 20 );

	$user = wp_authenticate( $username, $password );

	add_filter( 'determine_current_user', 'nuzuka_json_basic_auth_handler', 20 );

	if ( is_wp_error( $user ) ) {
		$wp_json_basic_auth_error = $user;
		return null;
	}

	$wp_json_basic_auth_error = true;

	return $user->ID;
}
add_filter('determine_current_user', 'nuzuka_json_basic_auth_handler', 20);

function nuzuka_json_basic_auth_error( $error ) {
	// Passthrough other errors
	if ( ! empty( $error ) ) {
		return $error;
	}

	global $wp_json_basic_auth_error;

	return $wp_json_basic_auth_error;
}
add_filter( 'rest_authentication_errors', 'nuzuka_json_basic_auth_error' );

function activate_nuzuka_plugin() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/NuzukaLeadGenerationActivator.php';
    NuzukaLeadGenerationActivator::activate(get_site_url());
}

function deactivate_nuzuka_plugin() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/NuzukaLeadGenerationDeactivator.php';
    NuzukaLeadGenerationDeactivator::deactivate(get_site_url());
}

register_activation_hook( __FILE__, 'activate_nuzuka_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_nuzuka_plugin' );
