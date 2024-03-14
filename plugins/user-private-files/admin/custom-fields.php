<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

if (!function_exists('upvf_pro_custom_fields_callback')) 
{
	function upvf_pro_custom_fields_callback()
	{
		if (!current_user_can('manage_options'))
		{
			wp_die( __('You do not have sufficient permissions to access this page.', 'user-private-files') );
		}

				?>
				
				<div class="wrap" id="upf_cf_option_page">
					<form method="post" action="">
						<!-- General Settings -->
						<div class="upfp_setting-container">
							
							<div class="upfp_thumb_heading">
								<h2 class="heading">General (Premium Feature)</h2>
							</div>
							
							<div class="upfp_inner-container">
								<div class="upf_cf_admin_col upf_cf_admin_col-1">
									<strong>Display form when file is uploaded</strong> 
									<br> 
									<span>(This will remove the bulk upload functionality)</span>
								</div>
								<div class="upf_cf_admin_col">
									<input type="checkbox">
								</div>
							</div>

							<!-- Toggle default columns -->
							<div class="upfp_inner-container">
								<div class="upf_cf_admin_col upf_cf_admin_col-1">
                                    <?php 
										$def_columns = array(
											'modify_date' => 'Modify Date',
											'type'        => 'Type',
											'size'        => 'Size',
											'author'      => 'Author'
										);
										$options = '';
										foreach($def_columns as $key => $val){
											$options .= '<option value="'.$key.'">'.$val.'</option>';
										}
									?>
									<label id="upfp_toggle_cols" for="upfp_hide_def_cols"><strong>Select default columns to hide</strong></label>
								</div>
                                <div class="upfp_col-2">
									<select class="chosen-select" multiple><?php echo $options; ?></select>
								</div>
							</div>
							
						</div>
						
						<!-- Custom Fields -->
						<div class="upfp_setting-container">
							
							<div class="upfp_thumb_heading">
								<h2 class="heading">Custom Fields (Premium Feature)</h2>
								<button class="upfp_btn">Add New</button>
							</div>
							
							<div class="upfp_inner-container">
								<div class="upf_cf_admin_col upf_cf_admin_col-1"><strong>Field Name</strong></div>
								<div class="upf_cf_admin_col"><strong>Type</strong></div>
								<div class="upf_cf_admin_col"><strong>Required</strong></div>
								<div class="upf_cf_admin_col"><strong>Display in List View</strong></div>
								<div class="upf_cf_admin_col"><strong>Display in Sidebar</strong></div>
							</div>
							
						</div>
						
						<!-- Save -->
						<p class="submit">
							<input type="submit" class="button-primary" value="Save">
						</p>
					
					</form>
					
				</div>
				
				<?php
		
	}
}
?>