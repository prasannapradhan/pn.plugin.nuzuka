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
		<button class="btn btn-primary w-25 my-auto" onclick="return scanSite();">Integrate now</button>
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