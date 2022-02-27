<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
		<meta name="viewport" content="width=device-width" />
		
		<title>Dashboard</title>
		<link rel="stylesheet" href="https://static-158c3.kxcdn.com/tools/fontawsome/6.0.0/css/all.min.css">
		<link href="https://static-158c3.kxcdn.com/tools/nprogress/nprogress.css" rel="stylesheet">
		<link href="https://static-158c3.kxcdn.com/tools/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" />
		<link href="https://static-158c3.kxcdn.com/pearnode/css/bs_theme.css" rel="stylesheet" />
		<link href="https://static-158c3.kxcdn.com/pearnode/css/screen_resolution.css" rel="stylesheet" />
		
		<script src="https://static-158c3.kxcdn.com/tools/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/tools/moment/moment.min.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/tools/mustache/mustache.min.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/tools/nprogress/nprogress.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/tools/sweetalert/2.9.17.1/swal.min.js" type="text/javascript" ></script>
		<script src="https://static-158c3.kxcdn.com/tools/popper/1.15.0/popper.min.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/tools/bootstrap/4.3.1/js/bootstrap.min.js"></script>
		<script src="https://static-158c3.kxcdn.com/tools/currency-formatter/currency-formatter-2.0.0.min.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/tools/js-sha1/0.6.0/sha1.min.js" type="text/javascript"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js" type="text/javascript" ></script>
    	<script src="https://code.jquery.com/color/jquery.color-2.2.0.js"></script>
    	<script src="https://static-158c3.kxcdn.com/js/common/util.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/js/common/generic_init.js" type="text/javascript"></script>
		
		<script>
			var oc = '<?php echo $org->code; ?>';
			var pc = '<?php echo $profile->code; ?>';
		</script>
	</head>
	
	<body>
	
        <form action='<?php echo get_admin_url(); ?>admin-post.php' method='post' id="nuzuka_woocommerce_config_form">
        	<div class="card-header bg-light w-100 mt-2" style="font-weight: bold;">
        		Configure Woocommerce Pages 
        	</div>
        	<div class="card-body w-100 p-1 mt-2">
        		<div class="row w-100 m-0 mb-2 mt-1">
        			<div class="col-5 p-0">
        				<img src="https://static-158c3.kxcdn.com/images/nuzuka/extra/woocommerce.png" 
        					class="shadow shadow-sm rounded w-100" 
        					style="height: 40vh;border-radius: 16px; "/>
        			</div>
        			<div class="col-7">
        				<ul class="w-100 ml-3" style="font-size: 15px !important;list-style-type: square;">
                          <li class="p-1"><b>Nuzuka works seamlessly with Woocommerce to generate enquiries on products</b></li>
                          <li class="p-1 mt-2" style="text-decoration:underline;">Steps to configure Woocommerce with Nuzuka</li>
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
                        </ul>
        			</div>
        		</div>
        		<div class="form-group mt-2">
        		    <label for="woocommerce_consumer_key"><b>Woocommerce Consumer Key</b></label>
        		    <textarea rows="1" class="form-control" id="woocommerce_consumer_key" name="woocommerce_consumer_key" required="required">
        		    	<?php echo $wc_consumer_key;?>
        		    </textarea>
        	    	<small id="wcckeyhelp" class="form-text text-muted">Enter the woocommerce Consumer Key you generated here</small>
        		</div>
        		<div class="form-group mt-1">
        		    <label for="woocommerce_consumer_secret"><b>Woocommerce Consumer Secret</b></label>
        		    <textarea rows="1" class="form-control" id="woocommerce_consumer_secret" name="woocommerce_consumer_secret" required="required">
        		    	<?php echo $wc_consumer_secret;?>
        		    </textarea>
        	    	<small id="wccsechelp" class="form-text text-muted">Enter the woocommerce Consumer Secret you generated here</small>
        		</div>
        		<input type='hidden' name='action' value='nuzuka_woocommerce_config_form' />
        		<input type='hidden' name='sid' value='<?php echo $site->id;?>' />
        	</div>
        	<div class="card-footer w-100">
        		<button class="btn btn-primary w-100" type="submit">
        			<b>Start Integration</b>
        		</button>
        	</div>
        </form>

	</body>
</html>