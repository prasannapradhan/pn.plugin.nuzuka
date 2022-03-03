<!doctype html>
<html lang="en">
	<head>
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
		  function load_script_dependencies(){
		      wp_enqueue_script('nuzuka-jquery', plugins_url('inclues/assets/js/jquery-1.12.4.min.js', __FILE__));
		      wp_enqueue_script('nuzuka-bootstrap', plugins_url('inclues/assets/js/bootstrap-4.3.1.min.js', __FILE__));
		      wp_enqueue_script('nuzuka-popper', plugins_url('inclues/assets/js/popper-1.15.0.min.js', __FILE__));
		      wp_enqueue_script('nuzuka-popper', plugins_url('inclues/assets/js/swal-2.9.17.1.min.js', __FILE__));
		      wp_enqueue_script('nuzuka-popper', plugins_url('inclues/assets/js/nprogress.js', __FILE__));
		      wp_enqueue_script('nuzuka-popper', plugins_url('inclues/assets/js/mustache.min.js', __FILE__));
		      wp_enqueue_script('nuzuka-popper', plugins_url('inclues/assets/js/moment.min.js', __FILE__));
		      wp_enqueue_script('nuzuka-popper', plugins_url('inclues/assets/js/datepicker.min.js', __FILE__));
		      wp_enqueue_script('nuzuka-popper', plugins_url('inclues/assets/js/daterangepicker-3.0.3.min.js', __FILE__));
		      wp_enqueue_script('nuzuka-popper', plugins_url('inclues/assets/js/chartjs-3.7.0.min.js', __FILE__));
		      wp_enqueue_script('nuzuka-popper', plugins_url('inclues/assets/js/jquery-color-2.0.0.js', __FILE__));
		      wp_enqueue_script('nuzuka-popper', plugins_url('inclues/assets/js/currency-formatter-2.0.0.min.js', __FILE__));
		      wp_enqueue_script('nuzuka-popper', plugins_url('inclues/assets/js/pearnode-commons-api-registry.js', __FILE__));
		      wp_enqueue_script('nuzuka-popper', plugins_url('inclues/assets/js/pearnode-commons-init.js', __FILE__));
		      wp_enqueue_script('nuzuka-popper', plugins_url('inclues/assets/js/pearnode-commons-inventory-model.js', __FILE__));
		      wp_enqueue_script('nuzuka-popper', plugins_url('inclues/assets/js/pearnode-commons-inventory-functions.js', __FILE__));
		      wp_enqueue_script('nuzuka-popper', plugins_url('inclues/assets/js/pearnode-commons-util.js', __FILE__));
		      wp_enqueue_script('nuzuka-popper', plugins_url('inclues/assets/js/pearnode-commons-cb-inventory-functions.js', __FILE__));
		  }
    	  add_action('wp_enqueue_scripts', "load_style_dependencies");
    	  add_action('wp_enqueue_scripts', "load_script_dependencies");
		?>
			
		<script id='widget_listing' type="text/html">
		 <table class='table table-sm table-bordered compact display nowrap table-hover' id='records_tbl'>
		   <thead>
		     <tr>
                <th class='text-center'>#</th>
				<th class='text-center'>Type</th>
                <th class='text-center'>Item</th>
		        <th class='text-center'>Category</th>
		        <th class='text-center'>Position</th>
                <th class='text-center'>Created</th>
				<th class='text-center'>Action</th>
		     </tr>
		   </thead>
		   <tbody>
		    {{#records}}
			     <tr class='item_row'>
			        <td class='text-center' width='10%'><b>{{id}}</b></td>
					<td class='text-center' width='12%'><b>{{type}}</b></td>
                    <td class='text-center' width='27%'>{{item_name}}</td>
			        <td class='text-center' width='8%'>{{category}}</td>
			        <td class='text-center' width='15%'>{{position}}</td>
                    <td class='text-center' width='12%'>{{created}}</td>
				    <td class='text-center' width='15%'>
						<button class="btn btn btn-outline-danger" onclick="return remove('{{id}}')" 
							data-toggle="popover" data-trigger="hover" data-placement="left" data-content="Dashboard" >
							<img src="https://static-158c3.kxcdn.com/images/bin.png" style="max-width:1vw"/>	
						</button>
				    </td>
			     <tr>
		     {{/records}}
		   </tbody>
		 </table>
		</script>	
	    
		<style type="text/css">
			 .wrapper{
		        height: 85vh !important;
		        max-height: 100vh !important;
		        min-height: 85vh!important;
			 }
			 .scrollcontainer{
			   max-height: 85vh !important;
			 }
		</style>	
		
		<script type="text/javascript">
    		var oc = '<?php echo $org->code; ?>';
    		var pc = '<?php echo $profile->code; ?>';
    		var uid = '<?php echo $user->id; ?>';
    		var uck = '<?php echo $user->ck; ?>';

    		var tmplt = '';
			var total_lead = 0;
			var enqArr = [];
			var widgets = [];
			var wtmplt = document.getElementById('widget_listing').innerHTML;
			var widgetMap = {};
						
			$(document).ready(function() {
				loadWidgets();
				initActions();
			});
			
			function loadWidgets() {
			   NProgress.start();
			   var purl = "https://api.pearnode.com/nuzuka/widget/list.php";
			   var pdata = {'oc': oc,'pc': pc};
			   $.post(purl, JSON.stringify(pdata), function(resp) {
				    widgets = $.parseJSON(resp);
				    if(widgets.length > 0){
					    var html = "";
				    	$.each(widgets, function(idx, widget) {
				    		if(widget.item_name == ""){
				    			widget.item_name = "No linked item";
				    		}
				    		widget.created = moment(widget.created_at).fromNow();
				    		widget.oc = oc;
				    		widget.pc = pc;
				    		widgetMap[widget.id] = widget;
						})
						$('#widget_count').text(widgets.length + " widgets");
						html = Mustache.render(wtmplt, {'records':widgets});
				    	$('#records_container').html(html);
				    }else {
				    	$('#records_container').hide();
				    	$('#no_items_container').fadeIn(300);
				    }
				    NProgress.done();
			   })
			}
			
			function initActions(){
				$("#search").on("keyup", function() {
				    var value = $(this).val().toLowerCase();
				    $("table tr").each(function(index) {
				        if (index != 0) {
				            $row = $(this);
				            $row.find('td').each(function() {
				                var id = $(this).text().toLowerCase();
				                if (id.indexOf(value) == -1) {
				                    $row.hide();
				                }else {
				                    $row.show();
				                    return false;
				                }
				            });  
				        }
				    });
				});
			}
			
			function remove(wid){
			   Swal.fire({
					  title: 'Do you really want to remove the widget ?',
					  icon: 'question',
					  showCancelButton: true,
					  confirmButtonText: 'Yes',
			   }).then((result) => {
				  if (typeof result.dismiss == "undefined") {
					   NProgress.start();
					   var purl = "https://api.pearnode.com/nuzuka/widget/remove.php";
					   var pdata = {'oc': oc,'pc': pc, 'wid' : wid};
					   $.post(purl, JSON.stringify(pdata), function(resp) {
						    NProgress.done();
						   loadWidgets();
					   })
				  } 
			   })
			}

			function launchAppFunction(){
	    		var url = "https://app.nuzuka.com/wp_launch.html?oc=" + oc + "&pc=" + pc + "&uck=" + uck + "&fn=Widgets";
	    		window.open(url, "nuzuka_app");
	    		return false;
			}
		</script>
	</head>

	<body style="overflow-x:hidden;height: 100vh;">
		<nav class="navbar navbar-expand-lg w-100">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<input type="text" class="form-control" id="search" placeholder="Filter...">
				</li>
				<li class="nav-item p-1">
					<span class="badge badge-secondary straight-badge" id="widget_count"></span>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item p-1">
					<button class="btn btn-primary" onclick="return launchAppFunction();">Manage</button>
				</li>
			</ul>
		</nav>
		<div class="wrapper pl-2 pr-2">
			<div id="records_container" class="scrollcontainer bg-white"></div>
			<div class="row w-100 justify-content-center" style="margin-left: 0px;display: none;" id="no_items_container">
	  			<div class="alert alert-info w-100">No widgets found. Generate one <a href="#" onclick="launchAppFunction();" class="link-primary"><b>here</b></a></div>
	  		</div>
		</div>
		
		<div class="modal" tabindex="-1" role="dialog" id="widget_snippet_modal">
		  <div class="modal-dialog modal-xl" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="widget_id"></h5>
		        <span id="modal_msg" class="modal-title badge badge-info"></span>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        	<div class="row w-100 mb-2 justify-content-center mt-2">
			 		  <div class="col-10" style="margin-left: 0px;">
						<textarea class="form-control" id="code_section" disabled="disabled" rows="15" onclick="this.select();">Wiget embed code will be generated here</textarea>
						<button class="btn btn-outline-primary w-100" onclick="return copyCode();">Copy to clipboard</button>
					  </div>
					  <div class="row w-100" style="margin-left: 0px; border: none;display: none;" id="content_section">
							<iframe class="nuzuka_widget_frame" allow="geolocation" src="" style="border: none;" width="100%"></iframe>
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