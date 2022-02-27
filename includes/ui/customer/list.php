<!doctype html>
<html lang="en">

	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
		<meta name="viewport" content="width=device-width" />
		
		<title>List Consumers</title>
		<link rel="stylesheet" href="https://static-158c3.kxcdn.com/tools/fontawsome/6.0.0/css/all.min.css">
	    <link href="https://static-158c3.kxcdn.com/tools/nprogress/nprogress.css" rel="stylesheet">
		<link href="https://static-158c3.kxcdn.com/tools/easyautocomplete/1.3.5/easy-autocomplete.min.css" rel="stylesheet" />
		<link href="https://static-158c3.kxcdn.com/tools/easyautocomplete/1.3.5/easy-autocomplete.themes.min.css" rel="stylesheet" />
		<link href="https://static-158c3.kxcdn.com/tools/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" />
		<link href="https://static-158c3.kxcdn.com/pearnode/css/bs_theme.css" rel="stylesheet" />
		<link href="https://static-158c3.kxcdn.com/pearnode/css/screen_resolution.css" rel="stylesheet" />
		
		<script src="https://static-158c3.kxcdn.com/tools/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/tools/moment/moment.min.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/tools/mustache/mustache.min.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/tools/nprogress/nprogress.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/tools/easyautocomplete/1.3.5/jquery.easy-autocomplete.min.js" type="text/javascript" ></script>
	    <script src="https://static-158c3.kxcdn.com/tools/sweetalert/2.8.15.3/sweetalert2.all.min.js"></script>
		<script src="https://static-158c3.kxcdn.com/tools/popper/1.15.0/popper.min.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/tools/bootstrap/4.3.1/js/bootstrap.min.js"></script>
		<script src="https://static-158c3.kxcdn.com/tools/currency-formatter/currency-formatter-2.0.0.min.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/tools/js-sha1/0.6.0/sha1.min.js" type="text/javascript"></script>
    	<script src="https://static-158c3.kxcdn.com/js/common/util.js" type="text/javascript"></script>
		<script src="https://static-158c3.kxcdn.com/js/common/generic_init.js" type="text/javascript"></script>
		
		<style type="text/css">
			.bscrollcontainer { 
				overflow-y: auto; 
				min-height: 10vh; 
				max-height: 80vh;
			}
			.bscrollcontainer thead th{ 
				position: sticky; 
				top: 0; 
			}
		</style>

		<script id="ctmpl" type="text/html">
			<table id="item_tbl" class="table table-sm table-hover table-bordered" style="width:100%;">
				<thead>
					<tr>
						<th style="text-align:center;">
							<input type="checkbox" onclick="toggleSelection();" checked />
						</th>
						<th>Name</th>
						<th style="text-align:center;">Submissions</th>
						<th>Email</th>
						<th style="text-align:center;">Phone</th>
						<th style="text-align:center;">Tags</th>
					</tr>
				</thead>
				<tbody style="width:100%" id="items_container">
					{{#records}}
						<tr id='item_row_{{id}}' class='item_row' code="{{id}}">
							<td width="5%" id='item_sel_{{id}}' style="text-align:center;">
								<input type="checkbox" class='item_sel' iid="{{id}}" checked='checked'/>
							</td>
							<td width="15%" id='item_name_{{id}}' class='item_name'>
								<span style="margin-left:5px;font-weight:bold;">{{dname}}</span>
							</td>
							<td width="15%" id='item_submissions_{{id}}' class='item_submissions' style="text-align:center;">
								<span class="badge badge-success" style="font-size:13px;">L-{{lcnt}}</span>
								<span class="badge badge-success" style="font-size:13px;margin-left:5px;">E-{{ecnt}}</span>
							</td>
							<td width="10%" id='item_email_{{id}}' class='item_email'><span style="margin-left:5px;">{{email}}</span></td>
							<td width="10%" id='item_phone_{{id}}' class='item_unit' style="text-align:center;">{{phone}}</td>
							<td width="25%" id='item_tags_{{id}}' class='item_tags' style="text-align:center;">{{tags}}</td>
						 </tr>
					{{/records}} 				
				</tbody>
			</table>
		</script>
		
		<script>
    		var oc = '<?php echo $org->code; ?>';
    		var pc = '<?php echo $profile->code; ?>';
    		var uid = '<?php echo $user->id; ?>';
    		var uck = '<?php echo $user->ck; ?>';

    		var ctmpl = document.getElementById('ctmpl').innerHTML;
	    	var clisturl = 'https://api.pearnode.com/nuzuka/consumer/list.php';
	    	var tlisturl = 'https://api.pearnode.com/nuzuka/consumer/list_tags.php';
			var mturl = "https://api.pearnode.com/nuzuka/consumer/mtag.php";
	    	var consumersObj = {};
	    	var tagArr = new Array();
    		var listParams = {};
			var csize = 1000;
			var cctr = 1;
			var delay = 250;
			var chunkedArr = new Array();
			var filteredTags = [];
			var renderCtr = 0;
			var all_selected = true;
			var enableActions = false;
			var tagObj = {};
			
	    	$(document).ready(function() {
				initView();
			});
	
	    	function initView(){
				initJquery();
			    $('#one_search').bind("keyup change", function(e){
					var _input = $(this).val();
					$(".item_row:contains('" + _input + "')").show();
			        $(".item_row:not(:contains('" + _input + "'))").hide();
				});
			    $('#item_search').bind("keyup change", function(e){
					var _input = $(this).val();
					$(".citem:contains('" + _input + "')").show();
			        $(".citem:not(:contains('" + _input + "'))").hide();
				});
    			loadConsumers();
			}		
			
	    	function loadConsumers(){
	    		consumersObj = {};
	    		cctr = 0;
		    	listParams.oc = oc;
		    	listParams.pc = pc;
		    	listParams.uid = uid;
	    		NProgress.start();
			    $.post(clisturl, JSON.stringify(listParams), function(data) {
	    			var consumers = $.parseJSON(data);
	    			$.each(consumers, function(index, consumer){
	    				cctr ++;
	    				consumer.id = parseInt(consumer.id);
	    				consumer.dname = toTitleCase(consumer.name);
	    				consumersObj[consumer.id] = consumer;
	    			});
		    		displayData(consumers);
				});
	    	}
	    	
			function displayData(items){
				$('#consumer_cnt').text(items.length + " customers");
				if(items.length != 0){
					$('#no_consumer_container').hide();
					$('#consumer_container').html(Mustache.render(ctmpl, {'records' : items}));
					$('[data-toggle="popover"]').popover();
					$('#consumer_container').fadeIn(500);
				}else {
					$('#consumer_container').hide();
					$('#no_consumer_container').fadeIn(500);
				}
				NProgress.done();
			}
			
			function toggleSelection(){
				if(all_selected){
					all_selected = false;
					$('.item_sel').prop('checked', false);
				}else {
					all_selected = true;
					$('.item_sel').prop('checked', true);
				}
			}
			
			function showAll(){
				$('.item_row').fadeIn(500);
			}

			function launchAppFunction(){
        		var url = "https://app.nuzuka.com/wp_launch.html?oc=" + oc + "&pc=" + pc + "&uck=" + uck + "&fn=ListConsumers";
        		window.open(url, "nuzuka_app");
        		return false;
    		}
		</script>
	</head>

	<body style="overflow-x:hidden;">
		<div class="navbar navbar-expand-lg w-100">
			<ul class="navbar-nav mr-auto">
		        <li class="nav-item">
			      	<a class="btn btn-outline-secondary" href="#" onclick="return loadConsumers();" style="margin-left: 5px;">
			      		<img src="https://static-158c3.kxcdn.com/images/refresh.png" style="max-width:1.2vw"/><b style="margin-left: 5px;">Refresh</b>
			      	</a>
		        </li>
		        <li class="nav-item">
			      	<input type="text" class="form-control" id="one_search" size="20" placeholder="Filter..." style="margin-left: 5px;">
		        </li>
		         <li class="nav-item p-1" style="margin-left: 5px;">
					<span class="badge badge-secondary straight-badge" id="consumer_cnt" style="font-size: 13px;"></span>
		        </li>
			</ul>
			<ul>
				<li class="nav-item">
			      	<button class="btn btn-primary" data-toggle="popover" data-trigger="hover" data-placement="top" 
							data-content="Manage in App" onclick="return launchAppFunction();">
							Manage
					</button>
		        </li>
			</ul>
	    </div>
		
		<div class="row w-100 m-0 p-2">
			<div class="row w-100 m-0 scrollcontainer" id="consumer_container" style="display: none;"></div>
		</div>
		
		<div class="row w-100 m-0 justify-content-center" style="display: none;height: 30vh;" id="no_consumer_container">
			<div class="alert alert-warning w-75 text-center my-auto">
				<p>
					Customers are the visitors who have actually placed an enquiry.<br />
					<b>Sorry! no customers found. Probably no leads / enquiries have been placed yet.</b>
				</p>
			</div>
		</div>
    </body>
</html>
