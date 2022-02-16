<?php
    /**
     * Plugin Name: Nuzuka Lead Generation System
     * Description: Nuzuka Lead Generation System for generating and operating leads. Combines sitemanager for analyzing lead conversion from visits. 
     * Author: Nuzuka Technology Team
     * Author URI: https://nuzuka.com
     * Version: 0.1
     * Plugin URI: https://nuzuka.com
     */
    
    $oc = "__nzk_org_code__";
    $pc = "__nzk_prof_code__";

    function activate_nuzuka_plugin() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/NuzukaLeadGenerationActivator.php';
        error_log("Activating plugin");
        NuzukaLeadGenerationActivator::activate(get_site_url());
    }
    
    function deactivate_nuzuka_plugin() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/NuzukaLeadGenerationDeactivator.php';
        NuzukaLeadGenerationDeactivator::deactivate(get_site_url());
    }
    
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
    
    function nuzuka_json_basic_auth_error( $error ) {
    	// Passthrough other errors
    	if ( ! empty( $error ) ) {
    		return $error;
    	}
    	global $wp_json_basic_auth_error;
    	return $wp_json_basic_auth_error;
    }
    
    function nuzuka_footer_append() {
        global $wp_query, $oc, $pc;
        $pgid = $wp_query->get_queried_object_id();
        if($pgid){
            $surl = get_site_url();
            $rdata = (object) array();
            $rdata->oc = $oc;
            $rdata->pc = $pc;
            $rdata->surl = $surl;
            $rdata->pglink = $pgid;
            $ch = curl_init("https://api.pearnode.com/nuzuka/site/plugin/widget_html_page.php");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($rdata));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            $out = curl_exec($ch);
            curl_close($ch);
            echo $out;
        }
    }
  
    function nuzuka_widget_shortcode_handler($atts){
        global $oc, $pc;
        $a = shortcode_atts( array('id' => 'none'), $atts);
        $out = "<hr/> id is - ".$a->id."</hr>";
        if($a->id != "none"){
            $rdata = (object) array();
            $rdata->oc = $oc;
            $rdata->pc = $pc;
            $rdata->wid = $a->id;
            $ch = curl_init("https://api.pearnode.com/nuzuka/site/plugin/widget_html_id.php");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($rdata));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            $cout = curl_exec($ch);
            curl_close($ch);
            $out .= $cout;
        }
        echo $out;
    }
    
    register_activation_hook( __FILE__, 'activate_nuzuka_plugin' );
    register_deactivation_hook( __FILE__, 'deactivate_nuzuka_plugin' );
    add_filter( 'rest_authentication_errors', 'nuzuka_json_basic_auth_error' );
    add_filter('determine_current_user', 'nuzuka_json_basic_auth_handler', 20);
    add_action('wp_footer', 'nuzuka_footer_append');
    add_shortcode('nzkwidget', 'nuzuka_widget_shortcode_handler' );
?>