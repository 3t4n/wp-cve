<?php
/*
* load login functionality
*/

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

class UPFP_LOGIN_FUNC
{
	function __construct()
	{
		$this->load_init();
	}
	
	function load_init(){
		//add_submenu_page('upvf-pro', 'Login', 'Login', 'manage_options', 'upvf-pro-login', array($this, 'upvf_pro_login_menu'), 3);
	}
	
	function upvf_pro_login_menu(){
		if (!current_user_can('manage_options')){
			wp_die( __('You do not have sufficient permissions to access this page.', 'user-private-files') );
		}
		
		?>
		
		<div class="wrap" id="upfp_login_option_page">
			<form method="post" action="">
				<div class="upfp_setting-container">
					<h2 class="heading">Login Options - (PRO Feature)</h2>
					<div class="upfp_inner-container">
						<div class="upfp_col-1">
							<label>Login Message</label>
						</div>
						<div class="upfp_col-2">
							<input type="text" class="upfp_input" disabled readonly="readonly" placeholder="Leave blank for no message">
						</div>								
					</div>
						
					<div class="upfp_inner-container">
						<div class="upfp_col-1">
							<label>Username / Email Label</label>
						</div>
						<div class="upfp_col-2">
							<input type="text" class="upfp_input" disabled readonly="readonly" placeholder="Leave blank for default label">
						</div>								
					</div>

					<div class="upfp_inner-container">
						<div class="upfp_col-1">
							<label>Password Label</label>
						</div>
						<div class="upfp_col-2">
							<input type="text" class="upfp_input" disabled readonly="readonly" placeholder="Leave blank for default label">
						</div>								
					</div>
				
					<div class="upfp_inner-container">
						<div class="upfp_col-1">
							<label>Display Remember Me</label>
						</div>

						<div class="upfp_col-2">
							<div id="upfp_setting-toggle" class="upfp_toggle_setting">
								<div class="upfp_toggle-check">
									<input type="checkbox" disabled readonly="readonly">
									<div class="upfp_round"></div>
								</div>
							</div>
						</div>
					</div>
				
					<div class="upfp_inner-container">
						<div class="upfp_col-1">
							<label>Login Button Label</label>
						</div>
						<div class="upfp_col-2">
							<input type="text" class="upfp_input" disabled readonly="readonly" placeholder="Leave blank for default label">
						</div>								
					</div>

					<div class="upfp_inner-container">
						<div class="upfp_col-1">
							<label>Display Lost Password</label>
						</div>

						<div class="upfp_col-2">
							<div id="upfp_setting-toggle" class="upfp_toggle_setting">
								<div>
									<input type="checkbox" disabled readonly="readonly">
									<div class="upfp_round"></div>
								</div>
							</div>
						</div>
					</div>

					<div class="upfp_inner-container">
						<div class="upfp_col-1">
							<label>Lost Password Label</label>
						</div>
						<div class="upfp_col-2">
							<input type="text" class="upfp_input" disabled readonly="readonly" placeholder="Leave blank for default label">
						</div>								
					</div>

				</div>

				<div class="upfp_admin_save">
					<input type="submit" class="button-primary" value="Save"/>
				</div>
				
			</form>
		</div>
		<?php
	
	}
	
}?>