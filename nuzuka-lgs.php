<?php
    /**
     * Plugin Name: Nuzuka Enquiry Capture
     * Description: Nuzuka Enquiry Capture is a replacement of contact form to track your site vistors and capture every customer enquiry in a Sales perspective.
     * Author: Nuzuka Technology Team
     * Author URI: https://pearnode.com
     * Version: 0.1
     * Plugin URI: https://nuzuka.com
     */
    
    $org = (object) array();
    $profile = (object) array();
    $user = (object) array();
    
    $plugin_dir = plugin_dir_path( __FILE__ );
    $cred_file = $plugin_dir."credentials.json";
    
    function nuzuka_json_basic_auth_handler( $user ) {
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
            return null;
        }
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
    
    function nuzuka_plugin_settings() {
        global $org, $profile, $user, $cred_file;
        $out = "";
        if(file_exists($cred_file)){
            $out = file_get_contents($cred_file);
        }
        if($out != ""){
            $cred = json_decode($out);
            $org = $cred->org;
            $profile = $cred->profile;
            $user = $cred->user;
        }
        include( plugin_dir_path( __FILE__ ) . 'includes/ui/settings/common-header.php');
		if(isset($profile->code)){
		     $surl = get_site_url();
		     
		     $rdata = (object) array();
		     $rdata->oc = $org->code;
		     $rdata->pc = $profile->code;
		     $rdata->surl = $surl;
		     $ch = curl_init("https://api.pearnode.com/nuzuka/site/plugin/activate.php");
		     curl_setopt($ch, CURLOPT_POST, 1);
		     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($rdata));
		     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		     curl_setopt($ch, CURLOPT_FAILONERROR, true);
		     $cout = curl_exec($ch);
		     curl_close($ch);
		     
		     $site = json_decode($cout);
        ?>
    		<div class="card-header w-100">
    			<div class="row w-100 m-0">
    				<div class="col-6">
    					<span style="font-size: 1.1rem;">Hello, <b><?php echo $user->full_name; ?> </b></span>	
    				</div>
    				<div class="col-6 d-flex justify-content-end">
    					<b><?php echo $org->name;?></b>
    				</div>
    			</div>
    		</div>
	    	<script>
    	    	var oc = '<?php echo $org->code; ?>';
    	    	var pc = '<?php echo $profile->code; ?>';
    	    	var uck = '<?php echo $user->ck; ?>';
    	    	var sid = '<?php echo $site->id; ?>';
    	    	var sname = '<?php echo $site->site_name; ?>';

        		function launchApp(){
            		var url = "https://app.nuzuka.com/wp_launch.html?oc=" + oc + "&pc=" + pc + "&uck=" + uck;
            		window.open(url, "nuzuka_app");
        		}
        	</script>
		    <?php 
    		    if(!isset($site->config)){
    		        $site->config = (object) array();
    		    }
    		    $sconfig = $site->config;
    		    if(isset($sconfig->scanned) && ($sconfig->scanned)){
    		        include( plugin_dir_path( __FILE__ ) . 'includes/ui/settings/launch-app.php');
		        }else {
		            include( plugin_dir_path( __FILE__ ) . 'includes/ui/settings/site-unscanned.php');
		        }
        }else {
            include( plugin_dir_path( __FILE__ ) . 'includes/ui/settings/attach-token.php');
        }
		include( plugin_dir_path( __FILE__ ) . 'includes/ui/settings/common-footer.php');
    }
    
    function nuzuka_plugin_site() {
        global $org, $profile, $user, $cred_file;
        $out = "";
        if(file_exists($cred_file)){
            $out = file_get_contents($cred_file);
        }
        if($out != ""){
            $cred = json_decode($out);
            $org = $cred->org;
            $profile = $cred->profile;
            $user = $cred->user;
        }else {
            $foo = menu_page_url("nuzuka-plugin-settings");
            exit( wp_redirect($foo));
        }
    }
    
    function handle_submit_nuzuka_registration_form(){
        global $org, $profile, $user, $cred_file;
        
        $regdata = (object) $_POST;
        $odata = (object) array();
        $token = $regdata->authtoken;
        
        $ddata = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1]))));
        $odata->oc = $ddata->oc;
        $odata->pc = $ddata->pc;
        $odata->uck = $ddata->uck;
       
        $ch = curl_init("https://api.pearnode.com/nuzuka/site/plugin/bizdetails.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($odata));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        $cout = curl_exec($ch);
        curl_close($ch);

        file_put_contents($cred_file, $cout);
        exit( wp_redirect("options-general.php?page=nuzuka-plugin-settings") );
    }
    
    function nuzuka_do_admin_init(){
		add_menu_page('Nuzuka', 'Nuzuka', 'manage_options', 'nuzuka-plugin-settings', 'nuzuka_plugin_settings', 'dashicons-superhero', 5);
		add_submenu_page('nuzuka-plugin-settings', 'Nuzuka Settings', 'Settings', 'manage_options', 'nuzuka-plugin-settings', 'nuzuka_plugin_settings');
		add_submenu_page('nuzuka-plugin-settings', 'Nuzuka Site', 'Site', 'manage_options', 'nuzuka-plugin-site', 'nuzuka_plugin_site');
    }

    add_filter('rest_authentication_errors', 'nuzuka_json_basic_auth_error');
    add_filter('determine_current_user', 'nuzuka_json_basic_auth_handler', 20);
    add_filter('the_content', 'nuzuka_parse_content', 25);
    
    add_action('wp_footer', 'nuzuka_footer_append');
    add_action('admin_menu', 'nuzuka_do_admin_init');
    add_action('admin_post_nuzuka_registration_form', 'handle_submit_nuzuka_registration_form'); // If the user is logged in
    
    