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
		
		<script type="text/html" id="lst_tmpl">
			{{#elements}}
				<div class="form-check form-check-inline">
  					<input class="form-check-input lmetricitem mt-1" type="checkbox" value="{{.}}" checked="checked" onclick="return reRenderLeadStats();">
  					<label class="form-check-label">{{.}}</label>
				</div>
			{{/elements}}
		</script>
		
		<script type="text/html" id="est_tmpl">
			{{#elements}}
				<div class="form-check form-check-inline">
  					<input class="form-check-input emetricitem mt-1" type="checkbox" value="{{.}}" checked="checked" onclick="return reRenderEnquiryStats();">
  					<label class="form-check-label">{{.}}</label>
				</div>
			{{/elements}}
		</script>
		
		<script>
    		var oc = '<?php echo $org->code; ?>';
    		var pc = '<?php echo $profile->code; ?>';
    		var uid = '<?php echo $user->id; ?>';
    		var uck = '<?php echo $user->ck; ?>';

    		var schartStore = {};
    		var lstatuselems = ['submitted', 'processing', 'disqualified', 'converted'];
    		var dlstatuselems = ['submitted', 'processing', 'disqualified', 'converted'];
    		var estatuselems = ['submitted', 'processing', 'completed', 'converted'];
    		var destatuselems = ['submitted', 'processing', 'completed', 'converted'];
			var lstmpl = document.getElementById('lst_tmpl').innerHTML;
			var estmpl = document.getElementById('est_tmpl').innerHTML;
			var lead = {};
			var enquiry = {};
			
			$(document).ready(function() {
				loadView();
			    $('#lmetrics_selector').html(Mustache.render(lstmpl, {'elements' : lstatuselems}));
			    $('#emetrics_selector').html(Mustache.render(estmpl, {'elements' : estatuselems}));
			});

			function loadView(){
				NProgress.start();
				var pdata = {'oc': oc,'pc': pc};
				var postUrl = "https://api.pearnode.com/nuzuka/dashboard/live.php"; 
			    $.post(postUrl, JSON.stringify(pdata), function(data) {
					var robj = $.parseJSON(data);
					var active = robj.active;
					$('#hits_live').text(active.hit.cnt);
					$('#clicks_live').text(active.click.cnt);
					$('#logins_live').text(active.login.cnt);
					$('#submission_live').text(active.submission.cnt); 
				});
				var postUrl = "https://api.pearnode.com/nuzuka/dashboard/lead.php"; 
			    $.post(postUrl, JSON.stringify(pdata), function(data) {
					var robj = $.parseJSON(data);
					lead = robj.fortnight.lead;
					displayFortnightLeadDistribution(lead);
					NProgress.done();
				});
			    var postUrl = "https://api.pearnode.com/nuzuka/dashboard/item.php"; 
			    $.post(postUrl, JSON.stringify(pdata), function(data) {
					var robj = $.parseJSON(data);
					var hitmatrix = robj.itemmatrix.hit;
					displayItemDistribution(robj.itemmatrix);
				});
			    var postUrl = "https://api.pearnode.com/nuzuka/dashboard/enquiry.php"; 
			    $.post(postUrl, JSON.stringify(pdata), function(data) {
					var robj = $.parseJSON(data);
					enquiry = robj.fortnight.enquiry;
					displayFortnightEnquiryDistribution(enquiry);
				});
			    var postUrl = "https://api.pearnode.com/nuzuka/dashboard/conversations.php"; 
			    $.post(postUrl, JSON.stringify(pdata), function(data) {
					var robj = $.parseJSON(data);
					var conversations = robj.conversations;
					displayConversationDistribution(conversations);
				});
				var postUrl = "https://api.pearnode.com/nuzuka/dashboard/reach.php"; 
			    $.post(postUrl, JSON.stringify(pdata), function(data) {
					var robj = $.parseJSON(data);
					displayReachStateDistribution(robj.reach.top10.state);
					displayReachCityDistribution(robj.reach.top10.city);
				});
			    var postUrl = "https://api.pearnode.com/nuzuka/dashboard/access.php"; 
			    $.post(postUrl, JSON.stringify(pdata), function(data) {
					var robj = $.parseJSON(data);
					displayAccessOSDistribution(robj.access.os);
					displayAccessBrowserDistribution(robj.access.browser);
				});
				var lastUpdate = new Date().getTime();
				var mom = moment.unix(lastUpdate / 1000);
				$('#update_time').text("Last updated " + mom.fromNow());
			}

			function reloadView(){
				loadView();
			}
			
			function displayFortnightLeadDistribution(fortnight){
				var sname = "lfortnight";
				var chart = schartStore[sname];
				if(typeof chart != "undefined"){
					chart.destroy()
				}
				var daykeys = Object.keys(fortnight);
	    		var data = {};
	    		var datasets = [];
				var statsdsmap = {};
				var totdsmap = {};
				var lineds = {'label' : 'total', 'data' : [], 'type' : 'line', 'backgroundColor' : '#3E45F4', 'order' : 0, 'borderColor': '#070A52', 'lineTension': 0.3};
	    		$.each(dlstatuselems, function(idx, statuskey){
	    			$.each(daykeys, function(idx, daykey){
	    				var dayobj = fortnight[daykey];
	    				if(typeof statsdsmap[statuskey] == "undefined"){
							statsdsmap[statuskey] = {'label' : statuskey, 'data' : [], 'backgroundColor' : textToColor(statuskey), 'order' : 1};
						}
	    				var stkobj = statsdsmap[statuskey];
						var statuscnt = parseInt(dayobj[statuskey]);
						stkobj.data.push(statuscnt);
		    		});
	    		});
	    		$.each(dlstatuselems, function(idx, statuskey){
	    			$.each(daykeys, function(idx, daykey){
	    				var dayobj = fortnight[daykey];
						if(typeof totdsmap[daykey] == "undefined"){
	    					totdsmap[daykey] = {'data' : []};
						}
						var statuscnt = parseInt(dayobj[statuskey]);
						var tobj = totdsmap[daykey];
						tobj.data.push(statuscnt);
						tobj.cnt = 0;
						$.each(tobj.data, function(idx, val){
							tobj.cnt += val;
						});
		    		});
	    		});
	    		$.each(daykeys, function(idx, daykey){
	    			var tobj = totdsmap[daykey];
	    			lineds.data.push(tobj.cnt);
	    		});
	    		var stackdsarr = Object.values(statsdsmap);
	    		$.each(stackdsarr, function(idx, stackds){
	    			datasets.push(stackds);
	    		})
	    		datasets.push(lineds);
	    		data.datasets = datasets;
	    		data.labels = daykeys;
	    		var chartData = {
					type: 'bar',
					data: data,
					options: {
						maintainAspectRatio : false,
						responsive: true,
						scales: { x: { stacked: true,}, y: {stacked: true}},
						responsiveAnimationDuration: 1000,
						legend: {position: 'top'},
						title: {display: true, text: "Leads summary for the fortnight"},
						animation: {animateScale: true, animateRotate: true}
					}
		    	};
	    		var elem = document.getElementById(sname + '_chart');
	    		chart = new Chart(elem, chartData);
	    		schartStore[sname] = chart;
	    	}

			function displayFortnightEnquiryDistribution(fortnight){
				var sname = "efortnight";
				var chart = schartStore[sname];
				if(typeof chart != "undefined"){
					chart.destroy()
				}
				var daykeys = Object.keys(fortnight);
	    		var data = {};
	    		var datasets = [];
				var statsdsmap = {};
				var totdsmap = {};
				var lineds = {'label' : 'total', 'data' : [], 'type' : 'line', 'backgroundColor' : '#3E45F4', 'order' : 0, 'borderColor': '#070A52', 'lineTension': 0.3};
	    		$.each(destatuselems, function(idx, statuskey){
	    			$.each(daykeys, function(idx, daykey){
	    				var dayobj = fortnight[daykey];
	    				if(typeof statsdsmap[statuskey] == "undefined"){
							statsdsmap[statuskey] = {'label' : statuskey, 'data' : [], 'backgroundColor' : textToColor(statuskey), 'order' : 1};
						}
	    				var stkobj = statsdsmap[statuskey];
						var statuscnt = parseInt(dayobj[statuskey]);
						stkobj.data.push(statuscnt);
		    		});
	    		});
	    		$.each(estatuselems, function(idx, statuskey){
	    			$.each(daykeys, function(idx, daykey){
	    				var dayobj = fortnight[daykey];
						if(typeof totdsmap[daykey] == "undefined"){
	    					totdsmap[daykey] = {'data' : []};
						}
						var statuscnt = parseInt(dayobj[statuskey]);
						var tobj = totdsmap[daykey];
						tobj.data.push(statuscnt);
						tobj.cnt = 0;
						$.each(tobj.data, function(idx, val){
							tobj.cnt += val;
						});
		    		});
	    		});
	    		$.each(daykeys, function(idx, daykey){
	    			var tobj = totdsmap[daykey];
	    			lineds.data.push(tobj.cnt);
	    		});
	    		var stackdsarr = Object.values(statsdsmap);
	    		$.each(stackdsarr, function(idx, stackds){
	    			datasets.push(stackds);
	    		})
	    		datasets.push(lineds);
	    		data.datasets = datasets;
	    		data.labels = daykeys;
	    		var chartData = {
					type: 'bar',
					data: data,
					options: {
						maintainAspectRatio : false,
						responsive: true,
						scales: { x: { stacked: true,}, y: {stacked: true}},
						responsiveAnimationDuration: 1000,
						legend: {position: 'top'},
						title: {display: true, text: "Enquiry summary for the fortnight"},
						animation: {animateScale: true, animateRotate: true}
					}
		    	};
	    		var elem = document.getElementById(sname + '_chart');
	    		chart = new Chart(elem, chartData);
	    		schartStore[sname] = chart;
	    	}

			function displayConversationDistribution(conversations){
				var sname = "conversations";
				var chart = schartStore[sname];
				if(typeof chart != "undefined"){
					chart.destroy()
				}
				var daykeys = Object.keys(conversations);
	    		var data = {};
	    		var datasets = [];
				var convmap = {};
				var totdsmap = {};
				var lineds = {'label' : 'count', 'data' : [], 'type' : 'line', 'backgroundColor' : '#3E45F4', 'order' : 0, 'borderColor': '#070A52', 'lineTension': 0.4};
    			$.each(daykeys, function(idx, daykey){
    				var dayobj = conversations[daykey];
					if(typeof totdsmap[daykey] == "undefined"){
    					totdsmap[daykey] = {'data' : []};
					}
					var tobj = totdsmap[daykey];
					tobj.cnt = dayobj[daykey];
	    		});
	    		$.each(daykeys, function(idx, daykey){
	    			var tobj = totdsmap[daykey];
	    			lineds.data.push(tobj.cnt);
	    		});
	    		datasets.push(lineds);
	    		data.datasets = datasets;
	    		data.labels = daykeys;
	    		var chartData = {
					type: 'bar',
					data: data,
					options: {
						maintainAspectRatio : false,
						responsive: true,
						responsiveAnimationDuration: 1000,
						legend: {position: 'top'},
						title: {display: true, text: "Leads summary for the fortnight"},
						animation: {animateScale: true, animateRotate: true}
					}
		    	};
	    		var elem = document.getElementById(sname + '_chart');
	    		chart = new Chart(elem, chartData);
	    		schartStore[sname] = chart;
	    	}
			
			function displayReachStateDistribution(reach){
				var sname = "reachstate";
				var chart = schartStore[sname];
				if(typeof chart != "undefined"){
					chart.destroy()
				}
	    		var skeys = Object.keys(reach);
	    		var svals = [];
	    		var COLORS = [];
	    		$.each(skeys, function(idx, key){
	    			COLORS.push(textToColor(key));
	    			svals.push(reach[key]);
	    		})
	    		var chartData = {
					type: 'doughnut',
					data: {
						datasets: [{data: svals, backgroundColor: COLORS}],
						labels: skeys
					},
					options: {
						maintainAspectRatio : false,
						responsive: true,
						responsiveAnimationDuration: 1000,
						legend: {display: false},
						title: {display: true, text: "Top state state reach"},
						animation: {animateScale: true, animateRotate: true}
					}
	    		};
	    		var elem = document.getElementById(sname + '_chart');
	    		chart = new Chart(elem, chartData);
	    		schartStore[sname] = chart;
	    	}
			
			function displayReachCityDistribution(reach){
				var sname = "reachcity";
				var chart = schartStore[sname];
				if(typeof chart != "undefined"){
					chart.destroy()
				}
	    		var skeys = Object.keys(reach);
	    		var svals = [];
	    		var COLORS = [];
	    		$.each(skeys, function(idx, key){
	    			COLORS.push(textToColor(key));
	    			svals.push(reach[key]);
	    		})
	    		var chartData = {
					type: 'doughnut',
					data: {
						datasets: [{data: svals, backgroundColor: COLORS}],
						labels: skeys
					},
					options: {
						maintainAspectRatio : false,
						responsive: true,
						responsiveAnimationDuration: 1000,
						legend: {display: false},
						title: {display: true, text: "Top state state reach"},
						animation: {animateScale: true, animateRotate: true}
					}
	    		};
	    		var elem = document.getElementById(sname + '_chart');
	    		chart = new Chart(elem, chartData);
	    		schartStore[sname] = chart;
	    	}
			
			function displayAccessOSDistribution(os){
				var sname = "accessos";
				var chart = schartStore[sname];
				if(typeof chart != "undefined"){
					chart.destroy()
				}
	    		var skeys = Object.keys(os);
	    		var svals = [];
	    		var COLORS = [];
	    		$.each(skeys, function(idx, key){
	    			COLORS.push(textToColor(key));
	    			svals.push(os[key]);
	    		})
	    		var chartData = {
					type: 'doughnut',
					data: {
						datasets: [{data: svals, backgroundColor: COLORS}],
						labels: skeys
					},
					options: {
						maintainAspectRatio : false,
						responsive: true,
						responsiveAnimationDuration: 1000,
						legend: {display: false},
						title: {display: true, text: "Top state state reach"},
						animation: {animateScale: true, animateRotate: true}
					}
	    		};
	    		var elem = document.getElementById(sname + '_chart');
	    		chart = new Chart(elem, chartData);
	    		schartStore[sname] = chart;
	    	}
			
			function displayAccessBrowserDistribution(browser){
				var sname = "accessbrowser";
				var chart = schartStore[sname];
				if(typeof chart != "undefined"){
					chart.destroy()
				}
	    		var skeys = Object.keys(browser);
	    		var svals = [];
	    		var COLORS = [];
	    		$.each(skeys, function(idx, key){
	    			COLORS.push(textToColor(key));
	    			svals.push(browser[key]);
	    		})
	    		var chartData = {
					type: 'doughnut',
					data: {
						datasets: [{data: svals, backgroundColor: COLORS}],
						labels: skeys
					},
					options: {
						maintainAspectRatio : false,
						responsive: true,
						responsiveAnimationDuration: 1000,
						legend: {display: false},
						title: {display: true, text: "Top state state reach"},
						animation: {animateScale: true, animateRotate: true}
					}
	    		};
	    		var elem = document.getElementById(sname + '_chart');
	    		chart = new Chart(elem, chartData);
	    		schartStore[sname] = chart;
	    	}
			
			function displayItemDistribution(matrix){
				var hitmatrix = matrix.hit;
				var submitmatrix = matrix.submit;
				var sname = "itemmatrix";
				var chart = schartStore[sname];
				if(typeof chart != "undefined"){
					chart.destroy()
				}
	    		var hitkeys = Object.keys(hitmatrix);
	    		if(hitkeys.length > 2){
		    		var difflen = hitkeys.length - 5;
		    		var diffh = 5 * difflen;
		    		var apph = 25 + diffh;
		    		$('#itemmatrix_container').css('height', apph + 'vh');
	    		}else {
	    			$('#itemmatrix_container').css('height', '25vh');
	    		}
	    		var hitvals = [];
	    		var submitvals = [];
    			var hitds = {'label' : 'Hits', 'data' : [], 'labels' : [], 'backgroundColor' : [], 'order' : 1};
    			var submitds = {'label' : 'Submits', 'data' : [], 'labels' : [], 'backgroundColor' : [], 'order' : 1};
	    		var shortkeys = [];
    			$.each(hitkeys, function(idx, key){
	    			var shortkey = key;
	    			if(key.length > 50){
	    				shortkey = key.substring(0,48) + "...";
	    			}
	    			shortkeys.push(shortkey);
	    			hitds.labels.push(key);
	    			submitds.labels.push(key);
	    			hitds.backgroundColor.push(textToColor(shortkey));
	    			submitds.backgroundColor.push(textToColor(shortkey + " submits"));
	    			hitds.data.push(hitmatrix[key]);
	    			if(typeof submitmatrix[key] == "undefined"){
	    				submitds.data.push(0);
	    			}else {
	    				submitds.data.push(submitmatrix[key]);
	    			}
	    		})
	    		var datasets = [];
	    		datasets.push(hitds);
	    		datasets.push(submitds);
	    		var chartData = {
					type: 'bar',
					data: {
						datasets: datasets,
						labels: shortkeys
					},
					options: {
						maintainAspectRatio : false,
						indexAxis: 'y',
						responsive: true,
						responsiveAnimationDuration: 1000,
						plugins:{legend: {display: false}},
						title: {display: true, text: "Top state state reach"},
						animation: {animateScale: true, animateRotate: true}
					}
	    		};
	    		var elem = document.getElementById(sname + '_chart');
	    		chart = new Chart(elem, chartData);
	    		schartStore[sname] = chart;
	    	}
			
			function reRenderLeadStats(){
				dlstatuselems = [];
				$('.lmetricitem').each(function(){
					if($(this).is(':checked')){
						dlstatuselems.push($(this).val());
					}
				});
				displayFortnightLeadDistribution(lead);
			}
			
			function reRenderEnquiryStats(){
				destatuselems = [];
				$('.emetricitem').each(function(){
					if($(this).is(':checked')){
						destatuselems.push($(this).val());
					}
				});
				displayFortnightEnquiryDistribution(enquiry);
			}
			
			function textToColor(str) {
	    		  var hash = 0;
	    		  for (var i = 0; i < str.length; i++) {
	    		    hash = str.charCodeAt(i) + ((hash << 5) - hash);
	    		  }
	    		  var colour = '#';
	    		  for (var i = 0; i < 3; i++) {
	    		    var value = (hash >> (i * 8)) & 0xFF;
	    		    colour += ('FF' + value.toString(16)).substr(-2);
	    		  }
	    		  return colour;
	    	}
			
			function hashCode(str) {
			  return str.split('').reduce((prevHash, currVal) =>
			    (((prevHash << 5) - prevHash) + currVal.charCodeAt(0))|0, 0);
			}
		</script>
	</head>

	<body style="overflow-x:hidden;" class="p-1">
	   <div class="container">
    	   <div class="row w-100 m-0 mt-1 mb-1">
    			<div class="card-header bg-light w-100 pl-0 pr-0" style="min-height: 7vh;background-color: #F7F9EF !important;">
    				<div class="row w-100 m-0">
    					<div class="col-5 pl-0">
    						<a class="btn btn-outline-secondary" href="#" onclick="return reloadView();">
    							<img src="https://static-158c3.kxcdn.com/images/refresh.png" style="max-width:1.2vw"/><b style="margin-left: 5px;">Refresh</b>
    						</a>
    						<b style="margin-left: 5px;">Recent Activity / 1 hour</b>	
    					</div>
    					<div class="col-7 d-flex justify-content-end">
    						<div class="col-auto">
    							<span class="badge badge-secondary p-2" id="update_time" style="font-size:14px;"></span>
    						</div>
    					</div>
    				</div>
    			</div>
    	   		<div class="row w-100 m-0 p-0 mt-1">
    				<div class="col-3 pl-0" >
    					<div class="row m-0 p-0 h-100 rounded" style="background-color: #F5C19D;">
    						<span id="hits_live" class="my-auto p-2" style="font-size: 34px;font-weight: bold;">0</span>
    						<span class="my-auto" style="font-size: 18px;font-weight: bold;margin-left: 10px;color: #858692;">Hits</span>
    					</div>
    				</div>
    				<div class="col-3" >
    					<div class="row m-0 p-0 h-100 rounded" style="background-color: #F7F157;">
    						<span id="clicks_live" class="my-auto p-2" style="font-size: 34px;font-weight: bold;">0</span>
    						<span class="my-auto" style="font-size: 18px;font-weight: bold;margin-left: 10px;color: #858692;">Clicks</span>
    					</div>
    				</div>
    				<div class="col-3" >
    					<div class="row m-0 p-0 h-100 rounded" style="background-color: #86F9E5;">
    						<span id="logins_live" class="my-auto p-2" style="font-size: 34px;font-weight: bold;">0</span>
    						<span class="my-auto" style="font-size: 18px;font-weight: bold;margin-left: 10px;color: #858692;">Logins</span>
    					</div>
    				</div>
    				<div class="col-3 pr-0" >
    					<div class="row m-0 p-0 h-100 rounded" style="background-color: #EFB8F4;">
    						<span id="submission_live" class="my-auto p-2" style="font-size: 34px;font-weight: bold;">0</span>
    						<span class="my-auto" style="font-size: 18px;font-weight: bold;margin-left: 10px;color: #858692;">Submissions</span>
    					</div>
    				</div>
    	   		</div>
    	   </div>
    	   <div class="row w-100 m-0 mt-2" style="overflow-x:hidden;">
    			<div class="card-header bg-light w-100"  style="min-height: 7vh;background-color: #F7F9EF !important;">
    				<div class="row w-100 m-0">
    					<div class="col-12">
    						<b>Overall Service Affinity matrix showing item <span class="badge badge-secondary" style="font-size:14px;">HIT</span> 
    							matrix and item <span class="badge badge-secondary" style="font-size:14px;">SUBMIT </span> matrix</b>	
    					</div>
    				</div>
    			</div>
    	   		<div class="row w-100 m-0">
    				<div class="col-12 p-0" id="itemmatrix_container">
    					<canvas id="itemmatrix_chart" style="width:100%;height:100%;"></canvas>
    				</div>	
    	   		</div>
    	   </div>
    	   <div class="row w-100 m-0 mt-2" style="overflow-x:hidden;">
    			<div class="card-header bg-light w-100"  style="min-height: 7vh;background-color: #F7F9EF !important;">
    				<div class="row w-100 m-0">
    					<div class="col-6">
    						<b>Reach Top 10 Metrics by Region and City</b>	
    					</div>
    					<div class="col-6"></div>
    				</div>
    			</div>
    	   		<div class="row w-100 m-0">
    				<div class="col-6 card" style="height: 50vh !important;">
    					<canvas id="reachstate_chart" style="width:100%;height:100%;"></canvas>
    				</div>	
    				<div class="col-6 card" style="height: 50vh !important;">
    					<canvas id="reachcity_chart" style="width:100%;height:100%;"></canvas>
    				</div>	
    	   		</div>
    	   </div>
    	   <div class="row w-100 m-0 mt-2" style="overflow-x:hidden;">
    			<div class="card-header bg-light w-100"  style="min-height: 7vh;background-color: #F7F9EF !important;">
    				<div class="row w-100 m-0">
    					<div class="col-6">
    						<b>Access Metrics by Operating system and Browser</b>	
    					</div>
    					<div class="col-6"></div>
    				</div>
    			</div>
    	   		<div class="row w-100 m-0">
    				<div class="col-6 card" style="height: 50vh !important;">
    					<canvas id="accessos_chart" style="width:100%;height:100%;"></canvas>
    				</div>	
    				<div class="col-6 card" style="height: 50vh !important;">
    					<canvas id="accessbrowser_chart" style="width:100%;height:100%;"></canvas>
    				</div>	
    	   		</div>
    	   </div>
    	   <div class="row w-100 m-0 mt-2" style="overflow-x:hidden;">
    			<div class="card-header bg-light w-100"  style="min-height: 7vh;background-color: #F7F9EF !important;">
    				<div class="row w-100 m-0">
    					<div class="col-3">
    						<b>Lead Metrics 15 days</b>	
    					</div>
    					<div class="col-9">
    						<div class="form-inline" id="lmetrics_selector"></div>
    					</div>
    				</div>
    			</div>
    	   		<div class="row w-100 m-0">
    				<div class="col-12 card" style="height: 40vh !important;">
    					<canvas id="lfortnight_chart" style="width:100%;height:100%;"></canvas>
    				</div>	
    	   		</div>
    	   </div>
    	   <div class="row w-100 m-0 mt-2" style="overflow-x:hidden;">
    			<div class="card-header bg-light w-100"  style="min-height: 7vh;background-color: #F7F9EF !important;">
    				<div class="row w-100 m-0">
    					<div class="col-3">
    						<b>Enquiry Metrics 15 days</b>	
    					</div>
    					<div class="col-9">
    						<div class="form-inline" id="emetrics_selector"></div>
    					</div>
    				</div>
    			</div>
    	   		<div class="row w-100 m-0">
    				<div class="col-12 card" style="height: 40vh !important;">
    					<canvas id="efortnight_chart" style="width:100%;height:100%;"></canvas>
    				</div>	
    	   		</div>
    	   </div>
    	   <div class="row w-100 m-0 mt-2" style="overflow-x:hidden;">
    			<div class="card-header bg-light w-100"  style="min-height: 7vh;background-color: #F7F9EF !important;">
    				<div class="row w-100 m-0">
    					<div class="col-6">
    						<b>Conversation metrics 15 days</b>	
    					</div>
    					<div class="col-6">
    					</div>
    				</div>
    			</div>
    	   		<div class="row w-100 m-0">
    				<div class="col-12 card" style="height: 40vh !important;">
    					<canvas id="conversations_chart" style="width:100%;height:100%;"></canvas>
    				</div>	
    	   		</div>
    	   </div>
	   </div>
    </body>

</html>
