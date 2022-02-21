<?php
    /**
     * Plugin Name: Nuzuka Enquiry Capture
     * Description: Nuzuka Enquiry Capture is a replacement of contact form to track your site vistors and capture every customer enquiry in a Sales perspective.
     * Author: Nuzuka Technology Team
     * Author URI: https://nuzuka.com
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
    
    function nuzuka_render_configuration() {
        global $org, $profile, $user, $cred_file;
        $out = @file_get_contents($cred_file);
        if($out != ""){
            $cred = json_decode($out);
            $org = $cred->org;
            $profile = $cred->profile;
            $user = $cred->user;
        }
        ?>
	    <link rel="stylesheet" href="https://static-158c3.kxcdn.com/tools/bootstrap/4.3.1/css/bootstrap.min.css"/>
	    <script src="https://static-158c3.kxcdn.com/tools/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/tools/bootstrap/4.3.1/js/bootstrap.min.js"></script>
		<body>
			<div class="modal fade" id="scan_modal" tabindex="-1" role="dialog" aria-hidden="true">
    		  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    		    <div class="modal-content">
    		      <div class="modal-header">
    		        <h5 class="modal-title">Scanning <span id="modal_site_name" class="badge badge-info" style="font-size: 14px;"></span></h5>
    		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    		          <span aria-hidden="true">&times;</span>
    		        </button>
    		      </div>
    		      <div class="modal-body">
    		      		<div class="row w-100 p-2 mb-2 mt-2 border-1 shadow-sm">
    		      			<div class="col-6">
    		      				<span>Scanning <b>Pages</b></span>
    		      			</div>
    		      			<div class="col-6 d-flex justify-content-center">
    		      				<div id="page_scan_result"><b>Waiting..</b></div>
    		      			</div>
    		      		</div>
    		      		<div class="row w-100 p-2 mb-2 mt-2 border-1 shadow-sm">
    		      			<div class="col-6">
    		      				<span>Scanning <b>Posts</b></span>
    		      			</div>
    		      			<div class="col-6 d-flex justify-content-center">
    		      				<div id="post_scan_result"><b>Waiting..</b></div>
    		      			</div>
    		      		</div>
    		      </div>
    		      <div class="modal-footer">
    		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    		      </div>
    		    </div>
    		  </div>
    		</div>
		
    		<div class="row w-100 m-0 p-2">
    		  <a href="https://nuzuka.com">
    			<img src="http://nuzuka.com/wp-content/uploads/2021/10/Nuzuka-Logo.png" width="128px;">
    		  </a>
    		</div>
    		<hr />
    		<section class="mt-1">
        		<div class="row w-100 m-0 justify-content-center">
        			<div class="container">
        				<div class="row p-0 w-100 m-0 justify-content-center">
        					<div class="container-fluid">
		<?php 
		if(isset($profile->code)){
		    $surl = get_site_url();
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
        <?php 		    
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
		    $sconfig = $site->config;
		    if(isset($sconfig->scanned) && ($sconfig->scanned)){
		        ?>
    				<div class="card-body row justify-content-center" style="min-height: 30vh;">
						<div class="row w-100 m-0 justify-content-center">
							<h4 class="my-auto">Congratulations !! Your site is now integrated with Nuzuka app</h4>
						</div>
						<div class="row w-100 m-0 justify-content-center">
							<button class="btn btn-primary w-25 my-auto" onclick="return launchApp();">Launch App</button>
						</div>
			   		</div>
		   		<?php                     
		    }else {
		        ?>
                   	<script>
                 	    function scanSite(){
                			$('#modal_site_name').text(sname);
                			$('#scan_modal').modal('show');
                	    	var pdata = {'oc': oc,'pc': pc, 'sid' : sid};
                			var postUrl = "https://api.pearnode.com/nuzuka/site/scan/page_open.php"; 
                			$('#page_scan_result').html('<img src="https://static-158c3.kxcdn.com/images//ajax/loader-snake-blue.gif" style="width: 1.5vw;"/>');
                		    $.post(postUrl, JSON.stringify(pdata), function(data) {
                		    	var robj = $.parseJSON(data);
                		    	var pgstatus = robj.status;
                		    	if(pgstatus.status == "success"){
                			    	$('#page_scan_result').html(robj.fetch_ctr + " found, " + robj.add_ctr + " added, " + robj.update_ctr + " updated");
                			    	postUrl = "https://api.pearnode.com/nuzuka/site/scan/post_open.php"; 
                					$('#post_scan_result').html('<img src="https://static-158c3.kxcdn.com/images//ajax/loader-snake-blue.gif" style="width: 1.5vw;"/>');
                				    $.post(postUrl, JSON.stringify(pdata), function(data) {
                				    	var robj = $.parseJSON(data);
                				    	var psstatus = robj.status;
                				    	if(psstatus.status == "success"){
                					    	$('#post_scan_result').html(robj.fetch_ctr + " found, " + robj.add_ctr + " added, " + robj.update_ctr + " updated");
                					    	var postUrl = "https://api.pearnode.com/nuzuka/site/scan/update.php"; 
                					    	 $.post(postUrl, JSON.stringify(pdata), function(data) {
                     					    	$('#scan_container').hide();
                    					    	$('#launch_container').fadeIn(200);
                					    	 });
                				    	}else {
                				    		$('#post_scan_result').html("Error in scanning : <b style='color:red;'>" + pgstatus.code + "</b>");
                				    	}
                				    });
                		    	}else {
                		    		$('#page_scan_result').html("Error in scanning : <b style='color:red;'>" + pgstatus.code + "</b>");
                		    	}
                		    });
                		    return false;
                		}
            	    </script>
					<div class="card-body row justify-content-center" style="min-height: 30vh;"  id="scan_container">
						<div class="row w-100 m-0 justify-content-center">
							<h4 class="my-auto">Your site is now ready for Integration</h4>
						</div>
						<div class="row w-100 m-0 justify-content-center">
							<button class="btn btn-primary w-25 my-auto" onclick="return scanSite();">Scan and integrate now</button>
						</div>
			   		</div>
			   		<div class="card-body row justify-content-center" style="min-height: 30vh;display:none;"  id="launch_container">
						<div class="row w-100 m-0 justify-content-center">
							<h4 class="my-auto">Congratulations !! Your site is now integrated with Nuzuka app</h4>
						</div>
						<div class="row w-100 m-0 justify-content-center">
							<button class="btn btn-primary w-25 my-auto" onclick="return launchApp();">Launch App</button>
						</div>
			   		</div>
		   		<?php    
		    }
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
        global $org, $profile, $user, $cred_file;
        
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
        curl_close($ch);

        file_put_contents($cred_file, $cout);
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
    