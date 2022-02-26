<form action='<?php echo get_admin_url(); ?>admin-post.php' method='post'>
	<div class="card-header bg-light w-100" style="font-weight: bold;">
		Enter Business Registration details. 
		<div class="float-right">Not Registered yet ? 
			<a class="link link-primary my-auto" href="https://app.nuzuka.com/wp_register.html" target="_nzkwpregister">Register here</a>
		</div>
	</div>
	<div class="card-body w-100">
		<div class="form-group">
		    <label for="authtoken">Auth token</label>
		    <textarea rows="4" class="form-control" id="authtoken" name="authtoken" required="required"></textarea>
	    	<small id="authtokenhelp" class="form-text text-muted">Enter the Authorization token here</small>
		</div>
		<input type='hidden' name='action' value='nuzuka_registration_form' />
	</div>
	<div class="card-footer w-100">
		<button class="btn btn-primary w-100" type="submit">
			Start the journey !!!
		</button>
	</div>
</form>
