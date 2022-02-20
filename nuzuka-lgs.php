<?php
    /**
     * Plugin Name: Nuzuka Lead Generation System
     * Description: Nuzuka Lead Generation System for generating and operating leads. Combines sitemanager for analyzing lead conversion from visits.
     * Author: Nuzuka Technology Team
     * Author URI: https://nuzuka.com
     * Version: 0.1
     * Plugin URI: https://nuzuka.com
     */
    
    $org = (object) array();
    $profile = (object) array();
    $user = (object) array();
    
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
    
    function nuzuka_render_configuration() {
        global $org, $profile, $user;
        ?>
	    <link rel="stylesheet" href="https://static-158c3.kxcdn.com/tools/bootstrap/4.3.1/css/bootstrap.min.css"/>
		<body>
    		<hr />
    		<div class="row w-100 m-0 p-2">
    		  <a href="https://nuzuka.com">
    			<img src="http://nuzuka.com/wp-content/uploads/2021/10/Nuzuka-Logo.png" width="128px;">
    		  </a>
    		</div>
    		<hr />
    		<section class="mt-4">
        		<div class="row w-100 m-0 justify-content-center">
        			<div class="container">
        				<div class="row p-0 w-100 m-0 justify-content-center">
        					<div class="container-fluid">
		<?php 
		if(isset($profile->code)){
        ?>
														
		<?php                     
        }else {
        ?>
					<form action='<?php echo get_admin_url(); ?>admin-post.php' method='post'>
    					<div class="card-header bg-light w-100" style="font-weight: bold;">
    						Enter Business Registration details. 
    						<div class="float-right">Not Registered yet ? 
    							<a class="link link-primary my-auto" href="https://app.nuzuka.com/wp_register.html" target="_nzkwpregister">Register here</a>
    						</div>
    					</div>
    					<div class="card-body w-100">
    						<div class="form-group">
    						    <label for="bizid">Business Identification</label>
    						    <input type="text" class="form-control" id="bizid" name="bizid" required="required"/>
    					    	<small id="bizidhelp" class="form-text text-muted">This is your unique business identification number.</small>
    						</div>
    						<div class="form-group">
    					    	<label for="bizsecret">Business Secret</label>
    					    	<input type="text" class="form-control" id="bizsecret" name="bizsecret" required="required"/>
    					    	<small id="bizsecrethelp" class="form-text text-muted">This is your unique business secret code.</small>
    						</div>
    						<div class="form-group">
    					    	<label for="adminsecret">Administrator Secret</label>
    					    	<input type="text" class="form-control" id="adminsecret" name="adminsecret" required="required"/>
    					    	<small id="adminsecrethelp" class="form-text text-muted">This is your unique administrator secret code.</small>
    						</div>
    						<input type='hidden' name='action' value='nuzuka_registration_form' />
    					</div>
    					<div class="card-footer w-100">
    						<button class="btn btn-primary w-100" type="submit">
    							Start the journey !!!
    						</button>
    					</div>
					</form>
		<?php
        }
        ?>
        					</div>
        				</div>
        			</div>
        		</div>
    		</section>
		</body>
		<?php 
    }
    
    function handle_submit_nuzuka_registration_form(){
        $regdata = (object) $_POST;
        $odata = (object) array();
        $odata->oc = $regdata->bizid;
        $odata->pc = $regdata->bizsecret;
        $odata->uck = $regdata->adminsecret;
       
        $ch = curl_init("https://api.pearnode.com/nuzuka/site/plugin/bizdetails.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($odata));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        $cout = curl_exec($ch);
        error_log("Response [$cout]");
        curl_close($ch);
        
        exit( wp_redirect("options-general.php?page=nuzuka-plugin-configuration") );
    }
    
    function nuzuka_do_admin_init(){
		add_menu_page(
			'Nuzuka Configuration', 
			'Nuzuka', // menu link text
			'manage_options', // capability to access the page
			'nuzuka-plugin-configuration', 
			'nuzuka_render_configuration', // callback function /w content
			'dashicons-superhero', // menu icon
			5 // priority
		);
    }

    add_filter('rest_authentication_errors', 'nuzuka_json_basic_auth_error');
    add_filter('determine_current_user', 'nuzuka_json_basic_auth_handler', 20);
    add_filter('the_content', 'nuzuka_parse_content', 25);
    add_action('wp_footer', 'nuzuka_footer_append');
    add_action('admin_menu', 'nuzuka_do_admin_init');
    add_action('admin_post_nuzuka_registration_form', 'handle_submit_nuzuka_registration_form'); // If the user is logged in
    