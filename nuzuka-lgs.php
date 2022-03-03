<?php
    /**
     * Plugin Name: Nuzuka Enquiry Capture
     * Description: Nuzuka Enquiry Capture is a super replacement of contact forms.
     * Author: Nuzuka Technology Team
     * Author URI: https://pearnode.com
     * Version: 1.0.0
     * Plugin URI: https://nuzuka.com
     */
    
    $org = (object) array();
    $profile = (object) array();
    $user = (object) array();
    
    $plugin_dir = plugin_dir_path( __FILE__ );
    $cred_file = $plugin_dir."credentials.json";
    
    function nuzuka_json_basic_auth_handler( $user ) {
        if (!empty($user)) {
            return $user;
        }
        if(!isset($_SERVER['PHP_AUTH_USER'])) {
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
        if (!empty($error)) {
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
            $rdata = array('surl' => $surl, 'pglink' => $pgid);
            $args = array('body' => $rdata, 'timeout' => '5', 'redirection' => '5', 'httpversion' => '1.0', 'blocking' => true, 'headers' => array(), 'cookies' => array());
            $out = wp_remote_post('https://api.pearnode.com/nuzuka/site/plugin/widget_html_page.php', $args);
            if(is_array($out)){
                $page = (object) $out;
                echo json_encode($page);
            }
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
                        $rdata = array('wid' => $wid);
                        $args = array('body' => $rdata, 'timeout' => '5', 'redirection' => '5', 'httpversion' => '1.0', 'blocking' => true, 'headers' => array(), 'cookies' => array());
                        $html = wp_remote_post('https://api.pearnode.com/nuzuka/site/plugin/widget_html_id.php', $args);
                        if($html != ""){
                            $content = str_replace($m, $html, $content);
                        }
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
        error_log("Loading dependencies");
        add_action('wp_enqueue_scripts', "load_style_dependencies");
        add_action('wp_enqueue_scripts', "load_script_dependencies");
        include( plugin_dir_path( __FILE__ ) . 'includes/ui/settings/common-header.php');
		if(isset($profile->code)){
		     $surl = get_site_url();
		     $rdata = array('oc' => $org->code, 'pc' => $profile->code, 'surl' => $surl);
		     $args = array('body' => $rdata, 'timeout' => '5', 'redirection' => '5', 'httpversion' => '1.0', 'blocking' => true, 'headers' => array(), 'cookies' => array());
		     $out = wp_remote_post('https://api.pearnode.com/nuzuka/site/plugin/activate.php', $args);
		     if(is_array($out)){
		         $site = (object) $out;
		     }
        ?>
    		<div class="card-header w-100">
    			<div class="row w-100 m-0">
    				<div class="col-12 pl-1">
    					<span style="font-size: 1.1rem;">Hello, <b><?php echo $user->full_name; ?> </b></span>	
    					<span style="margin-left:5px;">from <b><?php echo $org->name;?></b></span>
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
		            include( plugin_dir_path( __FILE__ ) . 'includes/ui/settings/unscanned.php');
		        }
        }else {
            include( plugin_dir_path( __FILE__ ) . 'includes/ui/settings/attach-token.php');
        }
		include( plugin_dir_path( __FILE__ ) . 'includes/ui/settings/common-footer.php');
    }
    
    function handle_submit_nuzuka_registration_form(){
        global $org, $profile, $user, $cred_file;
        if(isset($_POST['authtoken'])){
            $token = $_POST['authtoken'];
            $ddata = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1]))));
            $rdata = array('oc' => $ddata->oc, 'pc' => $ddata->pc, 'uck' => $ddata->uck);
            $args = array('body' => $rdata, 'timeout' => '5', 'redirection' => '5', 'httpversion' => '1.0', 'blocking' => true, 'headers' => array(), 'cookies' => array());
            $out = wp_remote_post('https://api.pearnode.com/nuzuka/site/plugin/bizdetails.php', $args);
            if(is_array($out)){
                $details = (object) $out;
                $dout = json_encode($details);
                file_put_contents($cred_file, $dout);
                exit(wp_redirect("options-general.php?page=nuzuka-plugin-settings") );
            }
        }
    }
    
    function handle_submit_nuzuka_navigation_form(){
        $regdata = (object) $_POST;
        $slug = $regdata->navslug;
        exit(wp_redirect("admin.php?page=$slug"));
    }
    
    function nuzuka_plugin_page_site() {
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
            
            $rdata = array('oc' => $org->code, 'pc' => $profile->code, 'surl' => get_site_url());
            $args = array('body' => $rdata, 'timeout' => '5', 'redirection' => '5', 'httpversion' => '1.0', 'blocking' => true, 'headers' => array(), 'cookies' => array());
            $out = wp_remote_post('https://api.pearnode.com/nuzuka/site/details_url.php', $args);
            if(is_array($out)){
                $site = (object) $out;
                include( plugin_dir_path( __FILE__ ) . 'includes/ui/site/pages.php');
            }
        }else {
            $foo = menu_page_url("nuzuka-plugin-settings");
            exit(wp_redirect($foo));
        }
    }

    function nuzuka_plugin_page_visitors() {
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
            
            $rdata = array('oc' => $org->code, 'pc' => $profile->code, 'surl' => get_site_url());
            $args = array('body' => $rdata, 'timeout' => '5', 'redirection' => '5', 'httpversion' => '1.0', 'blocking' => true, 'headers' => array(), 'cookies' => array());
            $out = wp_remote_post('https://api.pearnode.com/nuzuka/site/details_url.php', $args);
            if(is_array($out)){
                $site = (object) $out;
                include( plugin_dir_path( __FILE__ ) . 'includes/ui/site/visitors.php');
            }
        }else {
            $foo = menu_page_url("nuzuka-plugin-settings");
            exit( wp_redirect($foo));
        }
    }
    
    function nuzuka_plugin_page_inventory() {
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
            
            include( plugin_dir_path( __FILE__ ) . 'includes/ui/inventory/items.php');
        }else {
            $foo = menu_page_url("nuzuka-plugin-settings");
            exit( wp_redirect($foo));
        }
    }
    
    function nuzuka_plugin_page_enquiries() {
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
            
            include( plugin_dir_path( __FILE__ ) . 'includes/ui/submissions/list.php');
        }else {
            $foo = menu_page_url("nuzuka-plugin-settings");
            exit( wp_redirect($foo));
        }
    }
    
    function nuzuka_plugin_page_widgets() {
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
            include( plugin_dir_path( __FILE__ ) . 'includes/ui/widget/widgets.php');
        }else {
            $foo = menu_page_url("nuzuka-plugin-settings");
            exit( wp_redirect($foo));
        }
    }
    
    function nuzuka_plugin_page_customers() {
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
            include( plugin_dir_path( __FILE__ ) . 'includes/ui/customer/list.php');
        }else {
            $foo = menu_page_url("nuzuka-plugin-settings");
            exit( wp_redirect($foo));
        }
    }
    
    function nuzuka_plugin_page_dashboard() {
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
            
            $rdata = array('oc' => $org->code, 'pc' => $profile->code, 'surl' => get_site_url());
            $args = array('body' => $rdata, 'timeout' => '5', 'redirection' => '5', 'httpversion' => '1.0', 'blocking' => true, 'headers' => array(), 'cookies' => array());            
            $out = wp_remote_post('https://api.pearnode.com/nuzuka/site/details_url.php', $args);
            if(is_array($out)){
                $site = (object) $out;
                include( plugin_dir_path( __FILE__ ) . 'includes/ui/dashboard/dashboard.php');
            }
        }else {
            $foo = menu_page_url("nuzuka-plugin-settings");
            exit( wp_redirect($foo));
        }
    }
    
    function nuzuka_plugin_page_woocommerce() {
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
            
            $rdata = array('oc' => $org->code, 'pc' => $profile->code, 'surl' => get_site_url());
            $args = array('body' => $rdata, 'timeout' => '5', 'redirection' => '5', 'httpversion' => '1.0', 'blocking' => true, 'headers' => array(), 'cookies' => array());
            $out = wp_remote_post('https://api.pearnode.com/nuzuka/site/details_url.php', $args);
            if(is_array($out)){
                $site = (object) $out;
                $wc_consumer_key = "";
                $wc_consumer_secret = "";
                if(isset($site->config)){
                    $sconfig = $site->config;
                    if(isset($sconfig->commerce)){
                        if($sconfig->commerce == "woocommerce"){
                            if(isset($sconfig->woocommerce)){
                                $wc_consumer_key = $sconfig->woocommerce->woocommerce_consumer_key;
                                $wc_consumer_secret = $sconfig->woocommerce->woocommerce_consumer_secret;
                            }
                        }
                    }
                }
                include( plugin_dir_path( __FILE__ ) . 'includes/ui/woocommerce/woocommerce.php');
            }
        }else {
            $foo = menu_page_url("nuzuka-plugin-settings");
            exit( wp_redirect($foo));
        }
    }
    
    function nuzuka_do_admin_init(){
		add_menu_page('Nuzuka', 'Nuzuka Beta', 'manage_options', 'nuzuka-plugin-settings', 'nuzuka_plugin_settings', 'dashicons-superhero', 5);
		add_submenu_page('nuzuka-plugin-settings', 'Nuzuka Settings', 'Settings', 'manage_options', 'nuzuka-plugin-settings', 'nuzuka_plugin_settings');
		add_submenu_page('nuzuka-plugin-settings', 'Nuzuka Plugin Dashboard', 'Dashboard', 'manage_options', 'nuzuka-plugin-page-dashboard', 'nuzuka_plugin_page_dashboard');
		add_submenu_page('nuzuka-plugin-settings', 'Nuzuka Plugin Enquiries', 'Enquiries', 'manage_options', 'nuzuka-plugin-page-enquiries', 'nuzuka_plugin_page_enquiries');
		add_submenu_page('nuzuka-plugin-settings', 'Nuzuka Plugin Site Pages', 'Pages', 'manage_options', 'nuzuka-plugin-page-site', 'nuzuka_plugin_page_site');
		add_submenu_page('nuzuka-plugin-settings', 'Nuzuka Plugin Visitors', 'Visitors', 'manage_options', 'nuzuka-plugin-page-visitors', 'nuzuka_plugin_page_visitors');
		add_submenu_page('nuzuka-plugin-settings', 'Nuzuka Plugin Customers', 'Customers', 'manage_options', 'nuzuka-plugin-page-customers', 'nuzuka_plugin_page_customers');
		add_submenu_page('nuzuka-plugin-settings', 'Nuzuka Plugin Widgets', 'Widgets', 'manage_options', 'nuzuka-plugin-page-widgets', 'nuzuka_plugin_page_widgets');
		add_submenu_page('nuzuka-plugin-settings', 'Nuzuka Plugin Inventory', 'Catalog', 'manage_options', 'nuzuka-plugin-page-inventory', 'nuzuka_plugin_page_inventory');
		add_submenu_page('nuzuka-plugin-settings', 'Nuzuka Plugin Woocommerce', 'Woocommerce', 'manage_options', 'nuzuka-plugin-page-woocommerce', 'nuzuka_plugin_page_woocommerce');
    }
 
    function load_style_dependencies(){
        wp_enqueue_style('nuzuka-font-awsome', plugins_url('includes/assets/css/fontawsome-6.0.0-all-min.css', __FILE__));
        wp_enqueue_style('nuzuka-nprogress', plugins_url('includes/assets/css/nprogress.css', __FILE__));
        wp_enqueue_style('nuzuka-bootstrap-4.3.1', plugins_url('includes/assets/css/bootstrap-4.3.1-min.css', __FILE__));
        wp_enqueue_style('nuzuka-bootstrap-theme', plugins_url('includes/assets/css/bs_theme.css', __FILE__));
        wp_enqueue_style('nuzuka-screen-resolution', plugins_url('includes/assets/css/screen_resolution.css', __FILE__));
        wp_enqueue_style('nuzuka-select2', plugins_url('includes/assets/css/select2-4.1.0-rc.min.css', __FILE__));
        wp_enqueue_style('nuzuka-select2-bootstrap', plugins_url('includes/assets/css/select2-bootstrap4.min.css', __FILE__));
        wp_enqueue_style('nuzuka-datepicker', plugins_url('includes/assets/css/datepicker-min.css', __FILE__));
        wp_enqueue_style('nuzuka-daterangepicker', plugins_url('includes/assets/css/daterangepicker-3.0.3.css', __FILE__));
        wp_enqueue_style('nuzuka-google-font-kanit', plugins_url('includes/assets/css/google-font-kanit.css', __FILE__));
    }
    
    function load_script_dependencies(){
        error_log("Loading script dependencies");
        wp_enqueue_script('nuzuka-jquery', plugins_url('includes/assets/js/jquery-1.12.4.min.js', __FILE__));
        wp_enqueue_script('nuzuka-bootstrap', plugins_url('includes/assets/js/bootstrap-4.3.1.min.js', __FILE__));
        wp_enqueue_script('nuzuka-popper', plugins_url('includes/assets/js/popper-1.15.0.min.js', __FILE__));
        wp_enqueue_script('nuzuka-popper', plugins_url('includes/assets/js/swal-2.9.17.1.min.js', __FILE__));
        wp_enqueue_script('nuzuka-popper', plugins_url('includes/assets/js/nprogress.js', __FILE__));
        wp_enqueue_script('nuzuka-popper', plugins_url('includes/assets/js/mustache.min.js', __FILE__));
        wp_enqueue_script('nuzuka-popper', plugins_url('includes/assets/js/moment.min.js', __FILE__));
        wp_enqueue_script('nuzuka-popper', plugins_url('includes/assets/js/datepicker.min.js', __FILE__));
        wp_enqueue_script('nuzuka-popper', plugins_url('includes/assets/js/daterangepicker-3.0.3.min.js', __FILE__));
        wp_enqueue_script('nuzuka-popper', plugins_url('includes/assets/js/chartjs-3.7.0.min.js', __FILE__));
        wp_enqueue_script('nuzuka-popper', plugins_url('includes/assets/js/jquery-color-2.0.0.js', __FILE__));
        wp_enqueue_script('nuzuka-popper', plugins_url('includes/assets/js/currency-formatter-2.0.0.min.js', __FILE__));
        wp_enqueue_script('nuzuka-popper', plugins_url('includes/assets/js/pearnode-commons-api-registry.js', __FILE__));
        wp_enqueue_script('nuzuka-popper', plugins_url('includes/assets/js/pearnode-commons-init.js', __FILE__));
        wp_enqueue_script('nuzuka-popper', plugins_url('includes/assets/js/pearnode-commons-inventory-model.js', __FILE__));
        wp_enqueue_script('nuzuka-popper', plugins_url('includes/assets/js/pearnode-commons-inventory-functions.js', __FILE__));
        wp_enqueue_script('nuzuka-popper', plugins_url('includes/assets/js/pearnode-commons-util.js', __FILE__));
        wp_enqueue_script('nuzuka-popper', plugins_url('includes/assets/js/pearnode-commons-cb-inventory-functions.js', __FILE__));
    }
    
    add_filter('rest_authentication_errors', 'nuzuka_json_basic_auth_error');
    add_filter('determine_current_user', 'nuzuka_json_basic_auth_handler', 20);
    add_filter('the_content', 'nuzuka_parse_content', 25);
    
    add_action('wp_footer', 'nuzuka_footer_append');
    add_action('admin_menu', 'nuzuka_do_admin_init');
    add_action('admin_post_nuzuka_registration_form', 'handle_submit_nuzuka_registration_form');
    add_action('admin_post_nuzuka_navigation_form', 'handle_submit_nuzuka_navigation_form');
    
    add_action('in_admin_header', function () {
        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
    }, 1000);
     