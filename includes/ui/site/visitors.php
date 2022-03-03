<!doctype html>
<html lang="en">
	<head>
		<?php wp_head(); ?>
		<script type="text/html" id="tmpl">
			{{#records}}
				{{#summary}}
				<tr class='item_row'>
					<td colspan="5">
						{{#pic}}
							<div class="row w-100 m-0 bg-light">
								<img src="{{picture}}" style="width:width:3vw;height:6vh;border-radius:3px;" class="m-1"/>
								<span class="p-2" style="font-size:16px;"><b>{{vname}}</b></span>
							</div>
						{{/pic}}
						{{^pic}}
							<div class="row w-100 m-0 bg-light">
								<span class="p-2" style="font-size:16px;"><b>{{vname}}</b></span>
							</div>
						{{/pic}}
					</td>
				</tr>
				{{/summary}}
				{{#regular}}		
				<tr id='item_row_{{id}}' class='item_row' code="{{id}}">
					<td width="5%"><span style="margin-left:5px;">{{seq}}</span></td>
					<td width="20%"><span style="margin-left:5px;">{{email}}</span></td>
					<td width="15%" style="text-align:center;"><span class="p-2" style="font-size:14px;">{{site_name}}</span></td>
					<td width="30%" style="text-align:center;">{{page_url}}</td>
					<td width="15%" style="text-align:center;">
						{{created_at}}
					</td>
				 </tr>
				 {{/regular}}
			{{/records}} 
        </script>

		<style type="text/css">
			.bscrollcontainer { 
				overflow-y: auto; 
				min-height: 10vh; 
				max-height: 70vh;
			}
			.bscrollcontainer thead th{ 
				position: sticky; 
				top: 0; 
			}
		</style>

		<script>
    		var oc = '<?php echo $org->code; ?>';
    		var pc = '<?php echo $profile->code; ?>';
    		var sid = '<?php echo $site->id; ?>';
    		var uck = '<?php echo $user->ck; ?>';
    		
			var vitmpl = "";
	    	var visitorsObj = {};
    		var visitorNameMap = {};
	    	var rdata = {};
    		
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
			    vitmpl = document.getElementById('tmpl').innerHTML;
	    		dateFilterInit(dateChangeCallback);
			}		
			

	    	function alterParams(rdata){
	    		if(singleDate){
	    			if((typeof selDate !== 'undefined') && (selDate != "")){
	    				rdata.dt = selDate;
	    				try {
	    					delete rdata.startDate;
	    					delete rdata.endDate;
	    				} catch (e) {
	    				}
	    			}	
	    		}else {
	    			if((typeof sdt !== 'undefined') && (sdt != "")){
	    				rdata.startDate = sdt;
	    				try {
	    					delete rdata.dt;
	    				} catch (e) {
	    				}
	    			}
	    			if((typeof edt !== 'undefined') && (edt != "")){
	    				rdata.endDate = edt;
	    				try {
	    					delete rdata.dt;
	    				} catch (e) {
	    				}
	    			}
	    		}
	    		if((typeof ndt !== 'undefined') && (ndt != "")){
	    			delete rdata.dt;
	    			delete rdata.startDate;
	    			delete rdata.endDate;
	    		}
	    		return rdata;
	    	}

	    	function loadVisitors(){
	    		NProgress.start();
	    		var purl = "https://api.pearnode.com/nuzuka/visitor/list.php";
	    		var pdata = {'oc': oc,'pc': pc};
	    		pdata = alterParams(pdata);
	    		$.post(purl, JSON.stringify(pdata), function(data) {
	    			renderVisitorListing($.parseJSON(data));
	    			NProgress.done();
	    		});
	    		return false;
	    	}
	    	
	    	function dateChangeCallback(changeDate){
    			loadVisitors();
			}
	    	
	    	function renderVisitorListing(visitors){
	    		visitorNameMap = {};
	    		visitorsObj = {};
	    		NProgress.start();
    			$.each(visitors, function(index, visitor){
    				visitor.id = parseInt(visitor.id);
					visitor.created_at = moment(visitor.con).fromNow();
					if(typeof visitorNameMap[visitor.name] == "undefined"){
						if(visitor.picture != ""){
							visitor.pic = true;
							visitorNameMap[visitor.name] = [{'vname' : toTitleCase(visitor.name), 'picture' : visitor.picture, 'summary' : true, 'pic' : true}];
						}else{
							visitorNameMap[visitor.name] = [{'vname' : toTitleCase(visitor.name), 'picture' : visitor.picture, 'summary' : true}];
						}
					}
					var iarr = visitorNameMap[visitor.name];
					iarr.push(visitor);
					visitor.regular = true;
    				visitorsObj[visitor.id] = visitor;
    			});
    			displayNameData();
	    	}
	    	
			function displayNameData(){
				var keys = Object.keys(visitorNameMap);
				$('#items_container').empty();
				if(keys.length != 0){
					$('#no_visitor_container').hide();
					$('#visitor_container').fadeIn(500);
					$.each(keys, function(idx, key){
						var seq = 0
						var items = visitorNameMap[key];
						$.each(items, function(idx, item){
							item.seq = seq;
							seq++;
						});
						var mdata = {};
						mdata['records'] = items;
						$('#items_container').append(Mustache.render(vitmpl, mdata));
					});
				}else {
					$('#visitor_container').hide();
					$('#no_visitor_container').fadeIn(400);
				}
				NProgress.done();
			}
			
			function showAll(){
				$('.item_row').fadeIn(500);
			}
		</script>
	</head>

	<body style="overflow-x:hidden;" class="p-2">
		<nav class=" navbar navbar-expand-lg">
			<!-- Left navbar links -->
			<ul class="navbar-nav mr-auto">
		        <li class="nav-item">
			      	<a class="btn btn-sm btn-outline-secondary" href="#" onclick="return loadVisitors();" style="margin-left: 5px;">
			      		<img src="<?php echo plugins_url('includes/assets/', dirname(__FILE__)); ?>images/refresh.png" style="max-width:1.4vw"/><b style="margin-left: 5px;">Refresh</b>
			      	</a>
		        </li>
			</ul>
			<ul class="navbar-nav ml-auto">
		        <li class="nav-item" style="margin-left: 10px;">
					<span id="new_enq_text" class="my-auto" style="display: none;"></span>
				</li>
		        <li class="nav-item" style="margin-left: 10px;">
					<input type="text" class="form-control" id="one_search" placeholder="Filter...">
				</li>
		        <li class="nav-item" style="margin-left: 10px;">
					<div class="btn-group" role="group">
						<button class="btn btn-outline-secondary" onclick="return moveNextDate(dateChangeCallback);"><b>&nbsp;&#8658;&nbsp;</b></button>
						<button id="datechooser" class="btn btn-outline-secondary">Date</button>
						<button id="dateRangeChooser" class="btn btn-outline-secondary">Range</button>
						<button class="btn btn-outline-secondary" onclick="return movePreviousDate(dateChangeCallback);"><b>&nbsp;&#8656;&nbsp;</b></button>
					</div>
				</li>
		        <li class="nav-item" style="margin-left: 10px;">
					<div class="btn-group" role="group"> 
						<span class="badge badge-secondary straight-badge" id="chosen-date" style="padding: 0.75rem !important;"></span>
						<span class="badge badge-secondary straight-badge" id="total_qty"></span>
					</div>
				<li>
			</ul>
		</nav>		
		
		<div class="w-100 m-0 row pl-2 pr-2">
			<div class="row w-100 scrollcontainer m-0" style="display: none;" id="visitor_container">
				<table id="item_tbl" class="table table-sm table-hover table-bordered" style="width:100%;">
					<thead>
						<tr>
							<th>#</th>
							<th>Email</th>
							<th style="text-align:center;">Site</th>
							<th style="text-align:center;">Page</th>
							<th style="text-align:center;">Time</th>
						</tr>
					</thead>
					<tbody style="width:100%" id="items_container">	</tbody>
				</table>
			</div>
		</div>
		
		<div class="row w-100 m-0 justify-content-center" style="display: none;height: 30vh;" id="no_visitor_container">
			<div class="alert alert-warning w-75 text-center my-auto">
				<p>
					Visitors are prospective leads who logged in into the widget. They may or may not place an enquiry.<br />
					<b>Sorry! no visitors found for the date range specified</b>
				</p>
			</div>
		</div>
		
    </body>
</html>
