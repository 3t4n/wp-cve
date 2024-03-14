<?php
/*
 * Title: Sellingcommander Connector http://sellingcommander.com/
*/
if ( !function_exists('add_action') ) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

$extension_root = plugin_dir_url(__DIR__);
$site_name      = get_bloginfo( 'name' );

?>
<div class="sellingcommander-context">
	<h1 class="sellingcommander-title">Selling Commander Connector <span id="sellingcommander_connector_version"></span>
	<br/>
	<a target="_blank" href="https://sellingcommander.com">https://sellingcommander.com</a>
	<br>
	<span>Selling Commander: <a target="_blank" target="_blank" href="http://holest.com"><?php echo esc_attr__("Holest website","sellingcommander"); ?></a> |</span> 
	<span><a target="_blank" href="https://sellingcommander.com/terms-of-service.html"><?php echo esc_attr__("Terms of Service","sellingcommander"); ?></a> |</span>
	<span><a target="_blank" href="https://sellingcommander.com/privacy-policy.html"><?php echo esc_attr__("Privacy Policy","sellingcommander"); ?></a></span>
	</h1>

	<div class='sellingcommander-promo'>
			
		<div>
			<a class="sellingcommander-button sc-icon cmd-just-manage cmd-just-manage-products"><?php echo esc_attr__("Just manage products..."); ?></a>
			<p class='note'>(* <?php echo esc_attr__("with generic account."); ?> )</p>
		</div>
				
		<div>
			<h4><?php echo esc_attr__("Manage, Export, Import products","sellingcommander"); ?></h4>
			<img id="product_promo" src="<?php echo esc_attr($extension_root . "/assets/manage_products.png") ?>" /> 
		</div>
		<div style='display:none;'>
			<h4><?php echo esc_attr__("Manage, Export, Import orders","sellingcommander"); ?></h4>
			<img id="order_promo" src="<?php echo esc_attr($extension_root . "/assets/manage_orders.png") ?>" /> 
		</div>
	</div>
	
	<div class="sellingcommander-recommend">
		<p><?php echo esc_attr__("It's recomended that each backend user create its own S.C. account","sellingcommander"); ?></p>
	</div>
	
	<div class="sellingcommander-error"></div>
	
	<div class="sellingcommander-pending">
		<?php echo esc_attr__("Please wait...","sellingcommander"); ?>
	</div>
	
	<div class="sellingcommander-account">
		<?php echo esc_attr__("Currently loged-in with 'Selling Commander' as:","sellingcommander"); ?> <span></span>
		<button class="sellingcommander-button secondary-button" id="cmdLoginChange" style="width: auto;padding: 6px 12px;margin-left:25px;"><?php echo esc_attr__("Change login","sellingcommander"); ?></button>
	</div>
	
	<div class="sellingcommander-local-account">
		<?php echo esc_attr__("On-site user 'Selling Commander' connected account:","sellingcommander"); ?> <span></span>
		<button class="btn btn-xs btn-primary" id="cmdSCSwapAcount"><?php echo esc_attr__("swap to:","sellingcommander"); ?> <span></span></button>
	</div>
	
	<div class="sellingcommander-proceed">
		<div class='sellingcommander-proceed-form'>
			<input type="hidden" name="scaction" value="proceed" />
			<table>
				<tr>
					<td colspan="2" class="sellingcommander-info-bg">
						<p class="note create-note">
							<?php echo esc_attr__("Upon proceeding this site will be connected to the 'Selling Commander'. Your exiting account","sellingcommander") . " '<span class='sellingcommander_proceed_account'></span>' " . __("will be the owner of the created connection.","sellingcommander"); ?>
						</p>
						
						<p class="note owner-note">
							<?php echo esc_attr__("This site's 'Selling Commander' connection is owned by:","sellingcommander") . " '<span class='sellingcommander_owner'></span>'." ; ?>  
						</p>
						
						<p class="note uconnect-note">
							<?php echo esc_attr__("Upon proceeding you will be added to the 'Selling Commander' managment group for this site under S.C. site role:","sellingcommander") . " '<span class='sellingcommander_role'></span>'."; ?>
						</p>
					</td>
				</tr>
				<tr class="cmd-bar">
					<td colspan="2">
						<button class="sellingcommander-button button-100 sc-icon" id="cmdProceedAs"><?php echo esc_attr__("Proceed as","sellingcommander"); ?>: <b>[<span id="ProceedAs"></span>]</b></button>
					</td>
				</tr>
				
			</table>
		</div>
		<p class="optionally-or"><?php echo esc_attr__("OR...","sellingcommander"); ?></p>
	</div>

	<div class="sellingcommander-register">
		<div class='form-title'>
			<strong><?php echo esc_attr__("Selling Commander Registration","sellingcommander"); ?></strong>
		</div>
		<div class='sellingcommander-register-form'>
			<form>
				<input type="hidden" name="scaction" value="register" />
				<table>
					<tr>
						<td colspan="2" class="sellingcommander-info-bg">
							<p class="note create-note">
								<?php echo esc_attr__("Upon registration this site will be connected to the 'Selling Commander'. Your account will be the owner of the created connection.","sellingcommander"); ?>
							</p>
							
							<p class="note owner-note">
								<?php echo esc_attr__("This site's 'Selling Commander' connection is owned by:","sellingcommander") . " '<span class='sellingcommander_owner'></span>'." ; ?>  
							</p>
							
							<p class="note uconnect-note">
								<?php echo esc_attr__("Upon registration you will be added to the 'Selling Commander' managment group for this site under S.C. site role:","sellingcommander") . " '<span class='sellingcommander_role'></span>'."; ?>
							</p>
						</td>
					</tr>
					
					<tr><td><?php echo esc_attr__("Name your business (optionally)","sellingcommander"); ?></td>
					<td>
						<input  autocomplete="off"  type='text' name='entity_name'  placeholder='<?php echo esc_attr__("your business name","sellingcommander"); ?>' />
					</td>
					</tr>
					<tr><td><?php echo esc_attr__("Name this shop (optionally)","sellingcommander"); ?></td>
					<td>
						<input  autocomplete="off"  type='text' name='shop_title' value="<?php echo esc_attr( $site_name ); ?>"  placeholder='<?php echo esc_attr__("this shop name","sellingcommander"); ?>' />
					</td>
					</tr>
					<tr>
					<td colspan="2" class="sellingcommander-terms-conditions">
						<?php echo esc_attr__("I accept the","sellingcommander");?> 
						<a class="try-popup" target='_blank' href='https://sellingcommander.com/terms-of-service.html'><?php echo esc_attr__("Terms of Service","sellingcommander");?></a>
						<?php echo esc_attr__("and the","sellingcommander");?>	
						<a class="try-popup" target='_blank' href='https://sellingcommander.com/privacy-policy.html'><?php echo esc_attr__("Privacy Policy","sellingcommander");?></a>
						<?php echo esc_attr__("of the 'Selling Commander' platform","sellingcommander");?><input type="checkbox" name="sctermsandcond" value="1" />
					</td>	
					</tr>
					<tr>
						<td class="note create-note" colspan="2">
							<?php echo esc_attr__("Standard registration","sellingcommander"); ?>
						</td>
					</tr>
					<tr><td><?php echo esc_attr__("SC account e-mail","sellingcommander"); ?> *</td>
					<td>
						<input autocomplete="off"  type='email' name='email'  placeholder='<?php echo esc_attr__("email","sellingcommander"); ?>' require/>
					</td>
					</tr>
					<tr><td><?php echo esc_attr__("SC account password","sellingcommander"); ?> *</td>
					<td>
						<input autocomplete="off"  type='password' name='password'  placeholder='<?php echo esc_attr__("enter password","sellingcommander"); ?>' require/>
					</td>
					</tr>
					<tr><td><?php echo esc_attr__("SC account repeat password","sellingcommander"); ?> *</td>
					<td>
						<input autocomplete="off"  type='password' name='rpassword'  placeholder='<?php echo esc_attr__("repeat password","sellingcommander"); ?>' require/>
					</td>
					</tr>
					<tr class="cmd-bar">
						<td rowspan="2">
							<button class="sellingcommander-button secondary-button" id="cmdHaveAccount"><?php echo esc_attr__("Already have an account? Sign in","sellingcommander"); ?></button>
						</td>
						<td>
							<button class="sellingcommander-button sc-icon" id="cmdSCRegister"><?php echo esc_attr__("Register (standard way)","sellingcommander"); ?></button>
						</td>
					</tr>
					
					<tr class="cmd-bar">
						
						<td>
							<div>
								
								<p class="optionally-or"><?php echo esc_attr__("OR...","sellingcommander"); ?></p>
								
								<button class="sellingcommander-button google-icon" id="cmdSCGoogleRegister"><?php echo esc_attr__("Register with Google...","sellingcommander"); ?></button>
								<button class="sellingcommander-button facebook-icon" id="cmdSCFacebookRegister"><?php echo esc_attr__("Register with Facebook...","sellingcommander"); ?></button>
								
								<p class="note maxwidth40"><?php echo esc_attr__("On Google/Facebook registration you will be registered with account whose e-mail is one returned by Google/Facebook. Call to a Google/Facebook services will be made only with pruphose of the account authentication. No other data than e-mail from Google/Facebook will be used or stored.","sellingcommander"); ?></p>
								
							</div>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>

	<div class="sellingcommander-login">
		<div class='form-title'>
			<strong><?php echo esc_attr__("Login to the 'Selling Commander'","sellingcommander"); ?></strong>
		</div>
		<div class='sellingcommander-login-form'>
			<form>
				<input type="hidden" name="scaction" value="login" />
				<table>
						<tr>
							<td colspan="2" class="sellingcommander-info-bg">
								<p class="note create-note">
									<?php echo esc_attr__("Upon login this site will be connected to the 'Selling Commander'. Your account will be the owner of the created connection.","sellingcommander"); ?>
								</p>
								
								<p class="note owner-note">
									<?php echo esc_attr__("This site's 'Selling Commander' connection is owned by:","sellingcommander") . " '<span class='sellingcommander_owner'></span>'." ; ?>  
								</p>
								
								<p class="note uconnect-note">
									<?php echo esc_attr__("Upon login you will be added to the 'Selling Commander' managment group for this site under S.C. site role:","sellingcommander") . " '<span class='sellingcommander_role'></span>'."; ?>
								</p>
								
							</td>
						</tr>
						<tr><td><?php echo esc_attr__("SC account e-mail","sellingcommander"); ?> *</td>
						<td>
							<input autocomplete="off"  type='email' name='email'  placeholder='<?php echo esc_attr__("email","sellingcommander"); ?>' require/>
						</td>
						</tr>
						<tr><td><?php echo esc_attr__("SC account password","sellingcommander"); ?> *</td>
						<td>
							<input autocomplete="off"  type='password' name='password'  placeholder='<?php echo esc_attr__("enter password","sellingcommander"); ?>' require/>
						</td>
						</tr>
						<tr class="cmd-bar">
							<td rowspan="2">
								<button class="sellingcommander-button secondary-button sc-icon" id="cmdCreateAccount"><?php echo esc_attr__("Don't have an account? Register...","sellingcommander"); ?></button>
							</td>
							<td >
							    <a class="sellingcommander-button" target="_blank" href="https://sellingcommander.com/#forgot" id="cmdForgotPassword"><?php echo esc_attr__("Forgot password?","sellingcommander"); ?></a>
								<button class="sellingcommander-button sc-icon" id="cmdSCLogin"><?php echo esc_attr__("S.C. Login","sellingcommander"); ?></button>
							</td>
						</tr>
						<tr class="cmd-bar">
							<td>
								<p class="optionally-or"><?php echo esc_attr__("OR...","sellingcommander"); ?></p>
								<button class="sellingcommander-button google-icon" id="cmdSCGoogleLogin"><?php echo esc_attr__("Login with Google","sellingcommander"); ?></button>
								<button class="sellingcommander-button facebook-icon" id="cmdSCFacebookLogin"><?php echo esc_attr__("Login with Facebook","sellingcommander"); ?></button>
								
								<p class="note maxwidth40"><?php echo esc_attr__("On Google/Facebook login you will be loged-in with account whose e-mail is one returned by Google/Facebook. Call to a Google/Facebook services will be made only with pruphose of the account authentication. No other data than e-mail from Google/Facebook will be used or stored.","sellingcommander"); ?></p>
							</td>
						</tr>
						
				</table>
			</form>
		</div>
	</div>

	<div class="sellingcommander-onsitedahboard">
		<div class='form-title'>
			<strong><?php echo esc_attr__("Sites/Channels","sellingcommander"); ?></strong>
		</div>
		<div class="sellingcommander-channellist">

		</div>
	</div>
	
	<div class="sellingcommander-confirm-alias sellingcommander-info-bg">
		<p><?php echo esc_attr__("Is this site same as","sellingcommander") ?>: <span id="SCAliasedSite"></span>?</p>
		<div>
			<button class="sellingcommander-button" aliasval="new" ><?php echo esc_attr__("No, this is an new site","sellingcommander"); ?></button>
			<br/><button class="sellingcommander-button" aliasval="new" ><?php echo esc_attr__("This is an production copy","sellingcommander"); ?></button>
			<br/><button class="sellingcommander-button" aliasval="new" ><?php echo esc_attr__("This is an test copy","sellingcommander"); ?></button>
			<br/><button class="sellingcommander-button" aliasval="yestakeurl" ><?php echo esc_attr__("Yes - but, this will be the primary url from now on","sellingcommander"); ?></button>
			<br/><button class="sellingcommander-button" aliasval="yes" ><?php echo esc_attr__("Yes - I'm now just using a auxiliary domain name","sellingcommander"); ?></button>
		</div>
	</div>
	
	<div class="sellingcommander-confirmident">
		<div class='form-title'>
			<strong><?php echo esc_attr__("Verify your email with 6-digit code","sellingcommander"); ?></strong>
		</div>
		<div class='sellingcommander-confirmident-form'>
			<form>
				<table>
						<tr>
							<td class="note" colspan="2">
								<?php echo esc_attr__("We have sent and email to e-mail address you specified with the verification code. Please check your inbox for this message and code. Enter the code here to proceed. If you don't find this message make sure you also check for SPAM and TRASH emails.","sellingcommander"); ?>
							</td>
						</tr>
						<tr><td><?php echo esc_attr__("E-mail confirmation code","sellingcommander"); ?> *</td>
						<td>
							<input autocomplete="off"  type='text' name='email_confirmation' placeholder='<?php echo esc_attr__("enter 6 digit code","sellingcommander"); ?>' require/>
							<button class="sellingcommander-button secondary-button" id="cmdResendCode" style="width: auto;padding: 6px 12px;"><?php echo esc_attr__("Re-send code","sellingcommander"); ?></button>
						</td>
						</tr>
						<tr class="cmd-bar">
							<td colspan="2">
								<button class="sellingcommander-button  secondary-button" id="cmdConfirmIdentCancel"><?php echo esc_attr__("Cancel","sellingcommander"); ?></business>
								<button class="sellingcommander-button" id="cmdConfirmIdent"><?php echo esc_attr__("Proceed with registration","sellingcommander"); ?></business>
							</td>
						</tr>
				</table>
			</form>
		</div>
	</div>
	
	<p class="problem password_problem">
		<?php echo esc_attr__("SC account password: At least one number, one lowercase and one uppercase letter, at least six characters","sellingcommander"); ?>
	</p>
	<p class="problem repassword_problem">
		<?php echo esc_attr__("SC account repeat password: Passwords do not match","sellingcommander"); ?>
	</p>
	<p class="problem email_problem">
		<?php echo esc_attr__("SC account e-mail: Please enter valid email","sellingcommander"); ?>
	</p>
	<p class="problem register_accept">
		<?php echo esc_attr__("SC terms & conditions: You must accept the Selling Comander's Terms of Service and Privacy Policy!","sellingcommander"); ?>
	</p>
	<p class="problem register_error">
		<?php echo esc_attr__("ERROR: General register operation failure","sellingcommander"); ?>
	</p>
	<p class="problem login_problem">
		<?php echo esc_attr__("Username doesn't exist or wrong password","sellingcommander"); ?>
	</p>
	
	<div class="sellingcommander-local-dashboard">
	
	</div>
	
	<div class="sellingcommander-promo-zone"></div>
	<div class="sellingcommander-support">
		<?php echo esc_attr__("SUPPORT","sellingcommander"); ?>: <a href="mailto:support@holest.com">support@holest.com</a> | <a href="http://holest.com/forum/index.html" >holest.com form</a> | Tel/Viber: +381 63 7490 130</a>
	</div>
	
	
	
</div>