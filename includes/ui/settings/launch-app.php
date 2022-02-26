<div class="card-body row justify-content-center" style="min-height: 30vh;">
	<div class="row w-100 m-0 justify-content-center">
		<div class="col-6">
			<div class="row w-100 m-0 justify-content-left">
    			<h2 class="my-auto">Congratulations !!</h2>
    			<div class="row w-100 m-0 justify-content-left mt-2">
    				<span>Your site is now integrated</span>
    			</div>
            	<div class="row w-100 m-0 justify-content-left mt-3">
            		<div class="row w-100 m-0 mt-2">
            			<span>Not every business function be handled at a wordpress site. We recommend you use our app for using all the features.</span>
            		</div>
            		<div class="row w-100 m-0 mt-3">
	            		<button class="btn btn-primary w-75" onclick="return launchApp();">Launch App</button>
            		</div>
            	</div>
			</div>
		</div>
		
		<div class="col-6">
			<div class="row w-100 m-0 bg-light p-2 border-1 rounded"><b>Plugin Navigation Links</b></div>
			<div class="row w-100 m-0 mt-2">
				<form action='<?php echo get_admin_url(); ?>admin-post.php' method='post' id="navigation_form">
    				<script>
    					function submitNavigationForm(navslug){
    						var form = document.getElementById('navigation_form');
    						var hiddenField = document.createElement('input');
    					    hiddenField.type = 'hidden';
    					    hiddenField.name = 'navslug';
    					    hiddenField.value = navslug;
    					    form.appendChild(hiddenField);
    					    form.submit();
    					    return false;
    					}
    				</script>
    				<ul class="w-100 ml-3" style="font-size: 15px !important;list-style-type: square;">
                      <li class="p-1">You can find all your site pages in 
                      	<a href="#" onclick="return submitNavigationForm('nuzuka-plugin-page-site')"><b>Pages</b></a> page.
                      </li>
                      <li class="p-1">The operational dashboard can be found in 
                      	<a href="#" onclick="return submitNavigationForm('nuzuka-plugin-page-dashboard')"><b>Dashboard</b></a> page.
                      </li>
                      <li class="p-1">Site visitors can be found in <a href="#"><b>Visitors</b></a> page.</li>
                      <li class="p-1">Advanced features like Widget Creation, Inventory Declaration, Enquiry Operations, Customer management, Multisite Monitoriing 
                      		and much more are found in the <a href="#" onclick="return launchApp();"><b>Nuzuka App</b></a>
                      </li>
                      <li class="p-1">To raise a support issue <a href="https://support.pearnode.com" 
                      	target="pearnode_support"><b>Support</b></a> or mail us <b>support@pearnode.com</b></li>
                    </ul>
                    <input type='hidden' name='action' value='nuzuka_navigation_form' />
                </form>
			</div>
		</div>
	</div>
</div>
