<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
	<meta name="viewport" content="width=device-width" />
	<title>Submissions</title>

	<link rel="stylesheet" href="https://static-158c3.kxcdn.com/tools/fontawsome/6.0.0/css/all.min.css">
    <link href="https://static-158c3.kxcdn.com/tools/nprogress/nprogress.css" rel="stylesheet">
    <link href="https://static-158c3.kxcdn.com/tools/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://static-158c3.kxcdn.com/tools/datepicker/datepicker.min.css" rel="stylesheet">
    <link href="https://static-158c3.kxcdn.com/tools/daterangepicker/3.0.3/daterangepicker.css" rel="stylesheet">
 
	<link href="https://static-158c3.kxcdn.com/pearnode/css/bs_theme.css" rel="stylesheet" />
	<link href="https://static-158c3.kxcdn.com/pearnode/css/screen_resolution.css" rel="stylesheet" />
		
    <script src="https://static-158c3.kxcdn.com/tools/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
    <script src="https://static-158c3.kxcdn.com/tools/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script src="https://static-158c3.kxcdn.com/tools/popper/1.15.0/popper.min.js" type="text/javascript"></script>
    <script src="https://static-158c3.kxcdn.com/tools/rsvp/4.7.0/rsvp-min.js" type="text/javascript"></script>
    <script src="https://static-158c3.kxcdn.com/tools/sha-256/default/sha-256.min.js" type="text/javascript"></script>
    <script src="https://static-158c3.kxcdn.com/tools/js-xlsx/shim.min.js" type="text/javascript"></script>
    <script src="https://static-158c3.kxcdn.com/tools/js-xlsx/xlsx.full.min.js" type="text/javascript"></script>
    <script src="https://static-158c3.kxcdn.com/tools/filesaver/filesaver-1.3.6.min.js" type="text/javascript"></script>
    <script src="https://static-158c3.kxcdn.com/tools/moment/moment.min.js" type="text/javascript"></script>
    <script src="https://static-158c3.kxcdn.com/tools/mustache/mustache.min.js" type="text/javascript"></script>
    <script src="https://static-158c3.kxcdn.com/tools/sweetalert/2.9.17.1/swal.min.js" type="text/javascript" ></script>
    <script src="https://static-158c3.kxcdn.com/tools/nprogress/nprogress.js" type="text/javascript"></script>
    <script src="https://static-158c3.kxcdn.com/tools/datepicker/datepicker.min.js" type="text/javascript"></script>
	<script src="https://static-158c3.kxcdn.com/tools/daterangepicker/3.0.3/daterangepicker.min.js" type="text/javascript" ></script>
	<script src="https://static-158c3.kxcdn.com/tools/currency-formatter/currency-formatter-2.0.0.min.js" type="text/javascript"></script>
	<script src="https://static-158c3.kxcdn.com/tools/js-sha1/0.6.0/sha1.min.js" type="text/javascript"></script>
	
   	<script src="https://static-158c3.kxcdn.com/js/common/util.js" type="text/javascript"></script>
   	<script src="https://static-158c3.kxcdn.com/js/common/generic_init.js" type="text/javascript"></script>
   	<script src="https://static-158c3.kxcdn.com/js/common/date_controls_generic.js" type="text/javascript"></script>
   	<script src="https://static-158c3.kxcdn.com/js/common/inventory-model.js" type="text/javascript"></script>
	
	<script type="text/html" id="tmpl">
        <table class='table table-sm table-bordered compact display nowrap' id='records_tbl'>
		<thead>
			 <tr>
				<th class='text-center'>Number</th>
				<th class='text-center'>Submitter</th>
				<th class='text-center'>Email</th>
				<th class='text-center'>Mobile</th>
				<th class='text-center'>Created On</th>
			 </tr>
		</thead>
        <tbody>
			{{#records}}
				 {{#summary}}
					<tr class='item_row'>
						<td colspan="7">
							<div class="row w-100 m-0 bg-light">
								<img src="{{img_url}}" style="width:3vw;height:6vh;border-radius:3px;" id="item_img_{{number}}" class="m-1"/>
								<span class="p-2" style="font-size:16px;"><b>{{item_name}}</b></span>
							</div>
						</td>
					</tr>
				 {{/summary}}
				 {{#regular}}
				 <tr class='item_row'>
				   <td width='25%'>
						<div class="row w-100 m-0 p-2">
							<span style="font-weight:bold;margin-right:5px;">{{number}}</span>
							<span id="ucnt_{{id}}" class="my-auto badge badge-secondary" style="font-size:11px;"
								data-toggle="tooltip" data-placement="left" title="Lead conversation count">{{uctr}}</span>
							{{#info.summary.new_customer}}
								<span class="my-auto badge badge-light" style="font-size:14px;"
									data-toggle="tooltip" data-placement="left" title="New customer for you">
									<i class="fa fa-star"></i>
								</span>
							{{/info.summary.new_customer}}
							{{#info.summary.return_customer}}
								<span class="my-auto badge badge-light" style="font-size:14px;"
									data-toggle="tooltip" data-placement="left" title="The customer is a return customer">
									<i class="fa fa-arrows-left-right"></i>
								</span>
							{{/info.summary.return_customer}}
							{{#info.summary.re_purchase}}
								<span class="my-auto badge badge-light" style="font-size:14px;"
									data-toggle="tooltip" data-placement="left" title="Customer has purchased earlier">
									<i class="fa-regular fa-cart-plus"></i>
								</span>
							{{/info.summary.re_purchase}}
							{{#info.summary.no_purchase}}
								<span class="my-auto badge badge-light" style="font-size:14px;"
									data-toggle="tooltip" data-placement="left" title="Customer has not done any purchases yet">
									<i class="fa-regular fa-cart-shopping"></i>
								</span>
							{{/info.summary.no_purchase}}
							<span class="my-auto badge badge-light" style="font-size:14px;"
								data-toggle="tooltip" data-placement="left" title="You had conversations before">
								<i class="fa-regular fa-comment-dots"></i>
								<span style="font-size:13px;margin-left:3px;" >{{info.summary.previous_conversations}}</span>
							</span>
							<span class="my-auto badge badge-info" style="font-size:11px;">{{per}}%</span>
						</div>
				   </td>
				   <td class='text-center' width='15%'>{{user_name}}</td>
				   <td class='text-center' width='10%'>{{user_email}}</td>
				   <td class='text-center' width='10%'>{{user_mobile}}</td>
				   <td class='text-center' width='10%'>{{created_at}}</td>
				 <tr>
				 {{/regular}}
			{{/records}}
        </tbody>
        </table>
    </script>

	<style type="text/css">
		 .scrollcontainer {
		    overflow-y: auto;
		    min-height: 10vh;
		    max-height: 85vh
		}
		.scrollcontainer thead th {
		    position: sticky;
		    top: 0;
		    background-color: #fff;
		    background: #fff;
		    height: 3vh;
		}
	</style>	

	<script type="text/javascript">
		var wftmpls = [];
		var oitmpl = "";
		var total_value = 0.0;
		var total_qty = 0.0;
		var processedRecords = new Array();
		var submissionMap = {};
		var submissionNumberMap = {};
		var itemsubmissionMap = {};
		var stats = {};
		var collapsedGroups = {};
		var oc = '<?php echo $org->code; ?>';
		var pc = '<?php echo $profile->code; ?>';
		var org = {};
		var profile = {};
		
		$(document).ready(function() {
			var purl = "https://api.pearnode.com/extn/org/self/details.php";
			var pdata = {'oc': oc,'pc': pc};
			$.post(purl, JSON.stringify(pdata), function(data) {
				var robj = $.parseJSON(data);
				org = robj.org;
				profile = robj.profile;
				dateFilterInit(dateChangeCallback);
			});
			initActions();
		});

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

		function loadSubmissionData(){
			NProgress.start();
			var purl = "https://api.pearnode.com/nuzuka/submission/list.php";
			var pdata = {'oc': oc,'pc': pc};
			pdata = alterParams(pdata);
			$.post(purl, JSON.stringify(pdata), function(data) {
				renderSubmissionListing($.parseJSON(data));
				NProgress.done();
			});
			return false;
		}
		
		function dateChangeCallback(changeDate){
			loadSubmissionData();
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

		function renderSubmissionListing(records){
			$('#records_container').empty();
			processRecords(records);
			var ikeys = Object.keys(itemsubmissionMap);
			if(ikeys.length > 0){
				$.each(ikeys, function(idx, key){
					var larr = itemsubmissionMap[key];
					renderItemsDisplay(larr);
				});
			}else {
				renderItemsDisplay([]);
			}
		}
		
		function processRecords(records){
			processedRecords = new Array();
			submissionMap = {};
			submissionNumberMap = {};
			itemsubmissionMap = {};
			total_qty = 0.0;
			$.each(records, function(key, record) {
				var item_name = record.item.name;
				if(typeof itemsubmissionMap[item_name] == "undefined"){
					itemsubmissionMap[item_name] = [{'item_name' : item_name, 'img_url' : record.item.img_url, 'summary' : true}];
				}
				var iarr = itemsubmissionMap[item_name];
				record.per = calculateConversionProbablity(record);
				iarr.push(record);
				record.created_at = moment(record.created_at).fromNow();
				submissionMap[record.id] = record;
				submissionNumberMap[record.number] = record;
				record.regular = true;
				processedRecords.push(record);
			});
			return processedRecords;
		}

		function calculateConversionProbablity(lead){
			var base = 40;
			var calculated = base;
			var info = lead.info;
			var oa = org.address;
			if(typeof info.channel != "undefined"){
				var ic = info.channel;
				if((lead.user_mobile != "") && (lead.user_mobile != "1234567890")){
					calculated += 10;
				}
				if(ic !== null){
					if(ic.country_code){
						if(ic.country_code == oa.country_code){
							calculated += 10;
						}
					}
					if(ic.state_name){
						if(fuzzy_match(ic.state_name.toLowerCase(), oa.state.toLowerCase())){
							calculated += 10;
						}
					}
					if(ic.city){
						if(fuzzy_match(ic.city.toLowerCase(), oa.city.toLowerCase())){
							calculated += 10;
						}
					}
				}
				var lsubs = info.submissions.lead;
				var esubs = info.submissions.enquiry;
				var totsubs = lsubs.total + esubs.total;
				if(totsubs > 0){
					calculated += 10;
				}
				if(lsubs.converted.cnt > 0 || esubs.converted.cnt > 0){
					calculated += 10;
				}
			}
			return calculated;
		}
		
		function fuzzy_match(str,pattern){
			if((str == "") || (pattern == "")){
				return false;
			}
		    pattern = pattern.split("").reduce(function(a,b){ return a+".*"+b; });
		    return (new RegExp(pattern)).test(str);
		}
		
		function renderItemsDisplay(submissionRecords){
		  if (submissionRecords.length > 0) {
			    var seq = 0
				$.each(submissionRecords, function(idx, lead){
					lead.seq = seq;
					seq++;
				});
				var tmpl = document.getElementById('tmpl').innerHTML;
			    $('#records_container').append(Mustache.render(tmpl,{'records': submissionRecords}));
			    $('[data-toggle="popover"]').popover();
		  }else{
			  var html = "<div class='row w-100 m-0 justify-content-center'>" 
			  	+ " <div class='alert alert-info w-100 p-2'>Sorry !! No enquiries found for the dates specified</div></div>";
			  $('#records_container').html(html);
		  }
		  NProgress.done();
		}
	</script>
</head>
<body>
	<nav class="navbar navbar-expand-lg w-100">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item">
				<a class="btn btn-outline-secondary" href="#" onclick="return loadSubmissionData();" style="margin-left: 5px;">
					<img src="https://static-158c3.kxcdn.com/images/refresh.png" style="max-width:1.2vw"/><b style="margin-left: 5px;">Refresh</b>
				</a>
			</li>
		</ul>
		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
				<input type="text" class="form-control" id="search" placeholder="Filter...">
			</li>
			<li class="nav-item">
				<div class="btn-group" role="group" style="margin-left: 5px;">
					<button class="btn btn-outline-secondary" onclick="return moveNextDate(dateChangeCallback);"><b>&nbsp;&#8658;&nbsp;</b></button>
					<button id="datechooser" class="btn btn-outline-secondary">Date</button>
					<button id="dateRangeChooser" class="btn btn-outline-secondary">Range</button>
					<button class="btn btn-outline-secondary" onclick="return movePreviousDate(dateChangeCallback);"><b>&nbsp;&#8656;&nbsp;</b></button>
				</div>
			</li>
			<li class="nav-item">
				<div class="btn-group" role="group" style="margin-left: 5px;"> 
					<span class="badge badge-secondary straight-badge" id="chosen-date" style="padding: 0.75rem !important;"></span>
					<span class="badge badge-secondary straight-badge" id="total_qty"></span>
				</div>
			</li>
			<li class="nav-item">
				<button class="btn btn-primary" style="margin-left: 5px;">Manage</button>
			</li>
		</ul>
	</nav>
	<div class="container-fluid p-0">
		<div id="records_container" class="scrollcontainer" style="background-color: #fff;overflow-x:hidden;"></div>
	</div>
</body>
</html>