<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

if (!function_exists('upvf_pro_gdrive_callback')) 
{
	function upvf_pro_gdrive_callback()
	{
		if (!current_user_can('manage_options'))
		{
			wp_die( __('You do not have sufficient permissions to access this page.', 'user-private-files') );
		}

				?>
				<div class="wrap">
					<div id="upvf_pro_gdrive_settings">
						<form method='POST' action="">
							<div id="upfp_gdrive_sec">
								<div class="upfp_setting-container">
									<h2 class="heading">Google Drive Settings - (Premium Feature)</h2>
									<div class="upfp_inner-container">
										<div class="upfp_col-1">
											<label>Client ID</label>
										</div>
										<div class="upfp_col-2">
											<input type="text" class="upfp_input" disabled readonly="readonly">
										</div>								
									</div>
								
									<div class="upfp_inner-container">
										<div class="upfp_col-1">
											<label>Client Secret</label>
										</div>
										<div class="upfp_col-2">
											<input type="text" class="upfp_input" disabled readonly="readonly">
										</div>								
									</div>
								</div>														
							</div>
							
							<div class="upfp_admin_save">
								<input type="submit" class="button-primary" value="Save"/>
							</div>
							
						</form>
					</div>
				</div>
				<?php
		
	}
}
?>