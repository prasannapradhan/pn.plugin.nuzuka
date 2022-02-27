<!doctype html>
<html lang="en">

	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
		<meta name="viewport" content="width=device-width" />
		
		<title>Widget Listing</title>
		
		<link rel="stylesheet" href="https://static-158c3.kxcdn.com/tools/fontawsome/6.0.0/css/all.min.css">
	    <link href="https://static-158c3.kxcdn.com/tools/nprogress/nprogress.css" rel="stylesheet">
		<link href="https://static-158c3.kxcdn.com/tools/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" />
	    <link href="https://static-158c3.kxcdn.com/tools/datepicker/datepicker.min.css" rel="stylesheet">
	    <link href="https://static-158c3.kxcdn.com/tools/daterangepicker/3.0.3/daterangepicker.css" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@600&family=Roboto:wght@400&display=swap" rel="stylesheet">
	    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
		<link href="https://static-158c3.kxcdn.com/pearnode/css/bs_theme.css" rel="stylesheet" />
		<link href="https://static-158c3.kxcdn.com/pearnode/css/screen_resolution.css" rel="stylesheet" />
			
	    <script src="https://static-158c3.kxcdn.com/tools/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/tools/popper/1.15.0/popper.min.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/tools/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	   	<script src="https://static-158c3.kxcdn.com/tools/rsvp/4.7.0/rsvp-min.js" type="text/javascript"></script>
	    <script src="https://static-158c3.kxcdn.com/tools/sha-256/default/sha-256.min.js" type="text/javascript"></script>
	    <script src="https://static-158c3.kxcdn.com/tools/js-xlsx/shim.min.js" type="text/javascript"></script>
	    <script src="https://static-158c3.kxcdn.com/tools/js-xlsx/xlsx.full.min.js" type="text/javascript"></script>
	    <script src="https://static-158c3.kxcdn.com/tools/filesaver/filesaver-1.3.6.min.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/tools/moment/moment.min.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/tools/mustache/mustache.min.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/tools/nprogress/nprogress.js" type="text/javascript"></script>
	    <script src="https://static-158c3.kxcdn.com/tools/sweetalert/2.9.17.1/swal.min.js" type="text/javascript" ></script>
		<script src="https://static-158c3.kxcdn.com/tools/datepicker/datepicker.min.js" type="text/javascript" ></script>
		<script src="https://static-158c3.kxcdn.com/tools/daterangepicker/3.0.3/daterangepicker.min.js" type="text/javascript" ></script>
		<script src="https://static-158c3.kxcdn.com/tools/currency-formatter/currency-formatter-2.0.0.min.js" type="text/javascript" ></script>
		
		<script src="https://static-158c3.kxcdn.com/js/common/util.js" type="text/javascript"></script>
	   	<script src="https://static-158c3.kxcdn.com/js/common/generic_init.js" type="text/javascript"></script>
	   	<script src="https://static-158c3.kxcdn.com/js/common/date_controls_generic.js" type="text/javascript"></script>
			
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
			        <td class='text-center' width='10%'><a href="#" onclick="return loadGenerator('{{id}}', '{{type}}')"><b>{{id}}</b></a></td>
					<td class='text-center' width='12%'><b>{{type}}</b></td>
                    <td class='text-center' width='27%'>{{item_name}}</td>
			        <td class='text-center' width='8%'>{{category}}</td>
			        <td class='text-center' width='15%'>{{position}}</td>
                    <td class='text-center' width='12%'>{{created}}</td>
				    <td class='text-center' width='15%'>
						<button class="btn btn btn-outline-primary" onclick="return generateSnippet('{{id}}')" 
							data-toggle="popover" data-trigger="hover" data-placement="left" data-content="Code Snippet" >
							<img src="https://static-158c3.kxcdn.com/images/code.png" style="max-width:1vw"/>	
						</button>
						<button class="btn btn btn-outline-primary" onclick="return loadGenerator('{{id}}', '{{type}}')" 
							data-toggle="popover" data-trigger="hover" data-placement="left" data-content="Edit Widget" >
							<img src="https://static-158c3.kxcdn.com/images/edit.png" style="max-width:1vw"/>	
						</button>						
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
		    var tmplt = '';
			var total_lead = 0;
			var enqArr = [];
			var widgets = [];
			var wtmplt = document.getElementById('widget_listing').innerHTML;
			var widgetMap = {};
			var oc = '<?php echo $org->code; ?>';
			var pc = '<?php echo $profile->code; ?>';
						
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
			
			function generateSnippet(id){
				$('#modal_msg').text('');
				var widget = widgetMap[id];
				NProgress.start();
				var wconfig = $.parseJSON(widget.config);
				wconfig[wconfig.position] = true;
				if(widget.type == "lead-widget"){
					$.get('/view/template/_widget.mustache', {}, function(tmpl){
						var html = Mustache.render(tmpl, wconfig);
						html = html.replace(/[\t\r\n]+/g,"");
						$('#code_section').val(html);
						$('#widget_id').text(widget.id);
						$('#widget_snippet_modal').modal('show');
						NProgress.done();
					});
				} else if(widget.type == "enquiry-widget"){
					$.get('/view/template/_widget_form.mustache', {}, function(tmpl){
						widget.autoopen = true;
						var html = Mustache.render(tmpl, wconfig);
						html = html.replace(/[\t\r\n]+/g,"");
						$('#code_section').val(html);
						$('#widget_id').text(widget.id);
						$('#widget_snippet_modal').modal('show');
						NProgress.done();
					});
				}else if(widget.type == "lead-button"){
					$.get('/view/template/_lead_button.mustache', {}, function(tmpl){
						widget.autoopen = false;
						var html = Mustache.render(tmpl, wconfig);
						html = html.replace(/[\t\r\n]+/g,"");
						$('#code_section').val(html);
						$('#widget_id').text(widget.id);
						$('#widget_snippet_modal').modal('show');
						NProgress.done();
					});
				}else if(widget.type == "lead-link"){
					$.get('/view/template/_lead_link.mustache', {}, function(tmpl){
						widget.autoopen = false;
						var html = Mustache.render(tmpl, wconfig);
						html = html.replace(/[\t\r\n]+/g,"");
						$('#code_section').val(html);
						$('#widget_id').text(widget.id);
						$('#widget_snippet_modal').modal('show');
						NProgress.done();
					});
				}else if(widget.category == "enquiry-button"){
					$.get('/view/template/_widget_form.mustache', {}, function(tmpl){
						widget.autoopen = false;
						var html = Mustache.render(tmpl, wconfig);
						html = html.replace(/[\t\r\n]+/g,"");
						$('#code_section').val(html);
						$('#widget_id').text(widget.id);
						$('#widget_snippet_modal').modal('show');
						NProgress.done();
					});
				}else if(widget.category == "enquiry-link"){
					$.get('/view/template/_form_link.mustache', {}, function(tmpl){
						widget.autoopen = false;
						var html = Mustache.render(tmpl, wconfig);
						html = html.replace(/[\t\r\n]+/g,"");
						$('#code_section').val(html);
						$('#widget_id').text(widget.id);
						$('#widget_snippet_modal').modal('show');
						NProgress.done();
					});
				}else {
					showMessage('Error generating', 'Unknown widget type. Please regenerate the widget', 'error');
				}
				return false;
			}
			
			function copyCode(){
				var content = $('#code_section').val();
				if (navigator.clipboard) { // default: modern asynchronous API
				    navigator.clipboard.writeText(content);
				} else if (window.clipboardData && window.clipboardData.setData) {     // for IE11
				    window.clipboardData.setData('Text', content);
				}
				$('#modal_msg').text("copied!");
				return false;
			}
			
			function openCreate(){
				console.log("Load Generator without parameter");
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
			
			function loadGenerator(wid, typ){
				console.log("Load generator with widget type parameter");
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
				<li class="nav-item p-1">
					<button id="create_button" class="btn btn-sm btn-outline-primary" onclick="return parent.showWidgetGenerator();">Add new</button>
				</li>
			</ul>
		</nav>
		<div class="wrapper p-2">
			<div id="records_container" class="scrollcontainer bg-white"></div>
			<div class="row w-100 justify-content-center" style="margin-left: 0px;display: none;" id="no_items_container">
	  			<div class="alert alert-info w-100">No widgets found. Generate one <a href="#" onclick="openCreate();" class="link-primary"><b>here</b></a></div>
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