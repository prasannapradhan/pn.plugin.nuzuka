<!doctype html>
<html lang="en">
	<?php wp_head(); ?>
	<script>
    	var oc = '<?php echo $org->code; ?>';
    	var pc = '<?php echo $profile->code; ?>';
    	var uid = '<?php echo $user->id; ?>';
    	var uck = '<?php echo $user->ck; ?>';
    	var sid = '<?php echo $site->id; ?>';
    	var sname = '<?php echo $site->site_name; ?>';
    	
	 	function scanSite(){
			var wcckey = $('#woocommerce_consumer_key').val().trim();
			var wcsec = $('#woocommerce_consumer_secret').val().trim();
			if(wcckey == ""){
				$('#woocommerce_consumer_key').addClass('is-invalid');
				return false;
			}else{
				$('#woocommerce_consumer_key').removeClass('is-invalid');
			}
			if(wcsec == ""){
				$('#woocommerce_consumer_secret').addClass('is-invalid');
				return false;
			}else{
				$('#woocommerce_consumer_secret').removeClass('is-invalid');
			}
			var pdata = {'oc': oc,'pc': pc, 'sid' : sid, 'woocommerce_consumer_key': wcckey, 'woocommerce_consumer_secret': wcsec};
	    	var postUrl = "https://api.pearnode.com/nuzuka/site/plugin/woocommerce.php";
	    	$.post(postUrl, JSON.stringify(pdata), function(data) {
				$('#modal_site_name').text(sname);
				$('#scan_modal').modal('show');
				var postUrl = "https://api.pearnode.com/nuzuka/site/scan/page_open.php"; 
				$('#page_scan_result').html('<img src="<?php echo plugins_url('includes/assets/', dirname(__FILE__)); ?>images//ajax/loader-snake-blue.gif" style="width: 1.5vw;"/>');
			    $.post(postUrl, JSON.stringify(pdata), function(data) {
			    	var robj = $.parseJSON(data);
			    	var pgstatus = robj.status;
			    	if(pgstatus.status == "success"){
				    	$('#page_scan_result').html(robj.fetch_ctr + " found, " + robj.add_ctr + " added, " + robj.update_ctr + " updated");
				    	postUrl = "https://api.pearnode.com/nuzuka/site/scan/post_open.php"; 
						$('#post_scan_result').html('<img src="<?php echo plugins_url('includes/assets/', dirname(__FILE__)); ?>images//ajax/loader-snake-blue.gif" style="width: 1.5vw;"/>');
					    $.post(postUrl, JSON.stringify(pdata), function(data) {
					    	var robj = $.parseJSON(data);
					    	var psstatus = robj.status;
					    	if(psstatus.status == "success"){
						    	$('#post_scan_result').html(robj.fetch_ctr + " found, " + robj.add_ctr + " added, " + robj.update_ctr + " updated");
						    	var postUrl = "https://api.pearnode.com/nuzuka/site/scan/woocommerce_update.php"; 
						    	 $.post(postUrl, JSON.stringify(pdata), function(data) {
			 					    submitNavigationForm('nuzuka-plugin-page-site');
						    	 });
					    	}else {
					    		$('#post_scan_result').html("Error in scanning : <b style='color:red;'>" + pgstatus.code + "</b>");
					    	}
					    });
			    	}else {
			    		$('#page_scan_result').html("Error in scanning : <b style='color:red;'>" + pgstatus.code + "</b>");
			    	}
			    });
	    	});
		    return false;
		}
		
		function submitNavigationForm(navslug){
			var form = document.getElementById('navigation_form');
			var hiddenField = document.createElement('input');
		    hiddenField.type = 'hidden';
		    hiddenField.name = 'navslug';
		    hiddenField.value = navslug;
		    form.appendChild(hiddenField);
		    form.submit();
		    return false;
		}
	</script>
	
	<body style="overflow-x:hidden;">
		<div class="row w-100 m-0 p-2">
        	<div class="card-header bg-light w-100 mt-2" style="font-weight: bold;">
        		Integrate Woocommerce with Nuzuka to create enquiries on Products
        	</div>
        	<div class="card-body w-100 p-1 mt-1">
        		<div class="row w-100 m-0 mb-2 mt-1">
        			<div class="col-5 p-0">
        				<img src="<?php echo plugins_url('includes/assets/', dirname(__FILE__)); ?>images/woocommerce.png" 
        					class="shadow shadow-sm rounded w-100" 
        					style="height: 40vh;border-radius: 16px; "/>
        			</div>
        			<div class="col-7">
        				<ul class="w-100 ml-4" style="font-size: 15px !important;list-style-type: square;">
                          <li class="p-1" style="text-decoration:underline;">Steps to configure Woocommerce with Nuzuka</li>
                          <li>
                              <ol>
                                  <li class="p-1">Goto Menu > Woocommerce > Settings > Advanced > REST API</li>
                                  <li class="p-1">Click on the Add key button</li>
                                  <li class="p-1">Enter description as "Nuzuka Woocommerce"</li>
                                  <li class="p-1">Change Permission to "Read / Write"</li>
                                  <li class="p-1">Click on "Generate API Key" button</li>
                                  <li class="p-1">Copy the "Consumer Key" and "Consumer Secret" paste it below</li>
                              </ol>
                          </li>
                          <li class="p-1">Click on the "Start Integration" button</li>
                        </ul>
        			</div>
        		</div>
        		<div class="form-group mt-1">
        		    <label for="woocommerce_consumer_key"><b>Woocommerce Consumer Key</b></label>
        		    <input type="text" class="form-control" id="woocommerce_consumer_key" name="woocommerce_consumer_key" 
        		    	required="required"  value="<?php echo $wc_consumer_key;?>"/>
        	    	<small id="wcckeyhelp" class="form-text text-muted">Enter the woocommerce Consumer Key you generated here</small>
        		</div>
        		<div class="form-group mt-1">
        		    <label for="woocommerce_consumer_secret"><b>Woocommerce Consumer Secret</b></label>
        		    <input type="text" class="form-control" id="woocommerce_consumer_secret" name="woocommerce_consumer_secret" 
        		    	required="required" value="<?php echo $wc_consumer_secret;?>">
        	    	<small id="wccsechelp" class="form-text text-muted">Enter the woocommerce Consumer Secret you generated here</small>
        		</div>
        	</div>
        	<div class="card-footer w-100">
        		<button class="btn btn-primary w-100" onclick="return scanSite();">
        			<b>Start Integration</b>
        		</button>
        	</div>
		</div>
		
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
		
	</body>
</html>