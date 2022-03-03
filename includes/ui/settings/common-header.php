<html>
		<?php 
		  function load_style_dependencies(){
    		    wp_enqueue_style('nuzuka-font-awsome', plugins_url('inclues/assets/css/fontawsome-6.0.0-all-min.css', __FILE__));
    		    wp_enqueue_style('nuzuka-nprogress', plugins_url('inclues/assets/css/nprogress.css', __FILE__));
    		    wp_enqueue_style('nuzuka-bootstrap-4.3.1', plugins_url('inclues/assets/css/bootstrap-4.3.1-min.css', __FILE__));
    		    wp_enqueue_style('nuzuka-bootstrap-theme', plugins_url('inclues/assets/css/bs_theme.css', __FILE__));
    		    wp_enqueue_style('nuzuka-screen-resolution', plugins_url('inclues/assets/css/screen_resolution.css', __FILE__));
    		    wp_enqueue_style('nuzuka-select2', plugins_url('inclues/assets/css/select2-4.1.0-rc.min.css', __FILE__));
    		    wp_enqueue_style('nuzuka-select2-bootstrap', plugins_url('inclues/assets/css/select2-bootstrap4.min.css', __FILE__));
    		    wp_enqueue_style('nuzuka-datepicker', plugins_url('inclues/assets/css/datepicker-min.css', __FILE__));
    		    wp_enqueue_style('nuzuka-daterangepicker', plugins_url('inclues/assets/css/daterangepicker-3.0.3.css', __FILE__));
    		    wp_enqueue_style('nuzuka-google-font-kanit', plugins_url('inclues/assets/css/google-font-kanit.css', __FILE__));
		  }
    	  add_action('wp_enqueue_scripts', "load_style_dependencies");
		?>
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
	
		<section class="p-1">
        		<div class="row w-100 m-0 justify-content-center">
        			<div class="container">
        				<div class="row p-0 w-100 m-0 justify-content-center">
        					<div class="container-fluid">
		
