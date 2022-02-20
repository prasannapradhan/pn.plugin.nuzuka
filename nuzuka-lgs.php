<?php
    /**
     * Plugin Name: Nuzuka Lead Generation System
     * Description: Nuzuka Lead Generation System for generating and operating leads. Combines sitemanager for analyzing lead conversion from visits. 
     * Author: Nuzuka Technology Team
     * Author URI: https://nuzuka.com
     * Version: 0.1
     * Plugin URI: https://nuzuka.com
     */
    
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
        global $wp_query;
        $pgid = $wp_query->get_queried_object_id();
        if($pgid){
            $surl = get_site_url();
            $rdata = (object) array();
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
  
    function nuzuka_parse_content($content){
        $sc = 'nzkwidget';
        $matches = array();
        preg_match_all("/\[$sc(.+?)?\]/i", $content, $matches);
        if(sizeof($matches) > 0){
            $mbox = $matches[0];
            foreach($mbox as $m){
                $mx = str_replace("&#8221;", "\"", $m);
                $mx = str_replace("&#8243;", "\"", $mx);
                $mx = str_replace("&#8216;", "\"", $mx);
                $mx = str_replace("&#8217;", "\"", $mx);
                $widarr = array();
                if (preg_match('/"([^"]+)"/', $mx, $widarr)) {
                    $wid = $widarr[1];
                    if($wid != "none"){
                        $rdata = (object) array();
                        $rdata->wid = $wid;
                        $ch = curl_init("https://api.pearnode.com/nuzuka/site/plugin/widget_html_id.php");
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($rdata));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                        curl_setopt($ch, CURLOPT_FAILONERROR, true);
                        $cout = curl_exec($ch);
                        curl_close($ch);
                        $content = str_replace($m, $cout, $content);
                    }
                }
            }
        }
        return $content;
    }
    
    
    register_activation_hook( __FILE__, 'activate_nuzuka_plugin');
    register_deactivation_hook( __FILE__, 'deactivate_nuzuka_plugin');
    add_filter('rest_authentication_errors', 'nuzuka_json_basic_auth_error');
    add_filter('determine_current_user', 'nuzuka_json_basic_auth_handler', 20);
    add_filter('the_content', 'nuzuka_parse_content', 25);
    add_action('wp_footer', 'nuzuka_footer_append');