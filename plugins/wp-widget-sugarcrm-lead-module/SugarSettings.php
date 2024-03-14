<?php
if ( isset($_SERVER['SCRIPT_FILENAME']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit(esc_html__('Please don\'t access this file directly.', 'WP2SL'));
}

$htaccessProtected 		= get_option('OEPL_is_SugarCRM_htaccess_Protected');
$htaccessUsername 		= get_option('OEPL_SugarCRM_htaccess_Username');
$htaccessPassword 		= get_option('OEPL_SugarCRM_htaccess_Password');

$successMsg 			= get_option('OEPL_SugarCRMSuccessMessage');
$failureMsg 			= get_option('OEPL_SugarCRMFailureMessage');
$ReqFieldsMessage 		= get_option('OEPL_SugarCRMReqFieldsMessage');
$InvalidCaptchaMessage 	= get_option('OEPL_SugarCRMInvalidCaptchaMessage');

$IPaddrStatus 			= get_option('OEPL_auto_IP_addr_status');
$EmailNotification 		= get_option('OEPL_Email_Notification');
$EmailNotificationRx 	= get_option('OEPL_Email_Notification_Receiver');
$redirectStatus 		= get_option("OEPL_User_Redirect_Status");
$redirectTo 			= get_option("OEPL_User_Redirect_To");
$captchaSettings 		= get_option('OEPL_Captcha_status');
$sel_captcha 			= get_option('OEPL_Select_Captcha');

$Email_checked 			= ($EmailNotification === 'Y') ? 'checked="checked"' : '';
$IP_checked 			= ($IPaddrStatus === 'Y') ? 'checked="checked"' : '';
$redirectCbx 			= ($redirectStatus === 'Y') ? 'checked="checked"' : '';
$htacessCbx 			= ($htaccessProtected === 'Y') ? 'checked="checked"' : '';

wp_unslash($_GET);
if (isset($_GET['view']) && !$_GET['view'] or !isset($_GET['view']) or empty($_GET['view'])) {
	$_GET['view'] = 'sugarSettings';
}

?>
<div class="wrap">
	<table align="left" cellpadding="1" width="100%" cellspacing="1" border="0">
		<tr>
			<td valign="top">
				<table align="left" cellpadding="1" width="100%" cellspacing="1" border="0">
					<tr height="30">
						<td>
							<div class="wp2sl_h2_div">
								<h2><?php esc_html_e("WordPress to Sugar / SuiteCRM Lead", "WP2SL"); ?></h2>
							</div>
							<div class="wp2sl_pro_link_div">
								<a href="<?php echo esc_url(admin_url('admin.php?page=SugarSetting&view=pro')); ?>" class="OEPL_get_pro"><?php esc_html_e("Get Pro version", "WP2SL"); ?></a>
							</div>
						</td>
					</tr>

					<tr>
						<td valign="top">
							<div class="OEPL_tab_settings">
								<ul class="tabs">
									<li class="<?php echo ($_GET['view'] === 'sugarSettings' ? 'active' : ''); ?>">
										<a href="<?php echo esc_url(admin_url('admin.php?page=SugarSetting&view=sugarSettings')); ?>"><?php esc_html_e("Sugar / SuiteCRM Settings", "WP2SL"); ?></a>
									</li>
									<li class="<?php echo ($_GET['view'] === 'generalSettings' ? 'active' : ''); ?>">
										<a href="<?php echo esc_url(admin_url('admin.php?page=SugarSetting&view=generalSettings')); ?>"><?php esc_html_e("General Settings", "WP2SL"); ?></a>
									</li>
									<li class="<?php echo ($_GET['view'] === 'messageSettings' ? 'active' : ''); ?>">
										<a href="<?php echo esc_url(admin_url('admin.php?page=SugarSetting&view=messageSettings')); ?>"><?php esc_html_e("Message Settings", "WP2SL"); ?></a>
									</li>
									<li class="<?php echo ($_GET['view'] === 'CustomCSS' ? 'active' : ''); ?>">
										<a href="<?php echo esc_url(admin_url('admin.php?page=SugarSetting&view=CustomCSS')); ?>"><?php esc_html_e("Custom CSS", "WP2SL"); ?></a>
									</li>
									<li class="<?php echo (isset($_GET['view']) && $_GET['view'] === 'ShortCode' ? 'active' : ''); ?>">
										<a href="<?php echo esc_url(admin_url('admin.php?page=SugarSetting&view=ShortCode')); ?>"><?php esc_html_e("Short Code", "WP2SL"); ?></a>
									</li>
									<li class="<?php echo ($_GET['view'] === 'pro' ? 'active' : ''); ?>">
										<a href="<?php echo esc_url(admin_url('admin.php?page=SugarSetting&view=pro')); ?>"><?php esc_html_e("Pro Features", "WP2SL"); ?></a>
									</li>
									<li class="<?php echo (isset($_GET['view']) && $_GET['view'] === 'CRMPlugins' ? 'active' : ''); ?>">
										<a href="<?php echo esc_url(admin_url('admin.php?page=SugarSetting&view=CRMPlugins')); ?>"><?php esc_html_e("Sugar Plugins", "WP2SL"); ?></a>
									</li>
									<li class="<?php echo (isset($_GET['view']) && $_GET['view'] === 'SuitePlugins' ? 'active' : ''); ?>">
										<a href="<?php echo esc_url(admin_url('admin.php?page=SugarSetting&view=SuitePlugins')); ?>"><?php esc_html_e("SuiteCRM Plugins", "WP2SL"); ?></a>
									</li>
								</ul>
								<div class="content">
									<form name="OEPl_sugarSettings" id="OEPl_sugarSettings" method="post">
										<input type="hidden" id="oepl_nonce" value="<?php echo wp_create_nonce('upload_thumb') ?>" name="oepl_nonce" />
										<div class="OEPL_ErrMsg">
											<?php esc_html_e("This is Error Message", "WP2SL"); ?>
										</div>
										<div class="OEPL_SuccessMsg">
										<?php esc_html_e("This is success message", "WP2SL"); ?>
										</div>

										<table class="form-table wp2sl_form_tabl">
											<?php if (isset($_GET['view']) && $_GET['view'] === 'sugarSettings') {
												/**************************************
												 * PLUGIN SUGARCRM API SETTINGS
												 **************************************/
											?>
												<div class="title">
													<?php esc_html_e("Sugar / SuiteCRM REST API Settings", "WP2SL"); ?>
												</div>
												<tr>
													<td><strong><?php esc_html_e("Sugar / SuiteCRM URL ", "WP2SL"); ?>:</strong><br />
														<input name="OEPL_SUGARCRM_URL" type="text" id="OEPL_SUGARCRM_URL" value="<?php echo get_option('OEPL_SUGARCRM_URL'); ?>" size="53" required />
														<p class="description"><?php esc_html_e("Your Sugar / SuiteCRM REST API URL will be <Domain>/service/v4_1/rest.php.", "WP2SL"); ?><br /><?php esc_html_e("i.e. http://mycrm.com/service/v4_1/rest.php", "WP2SL"); ?> <b><?php esc_html_e("or", "WP2SL"); ?></b> <?php esc_html_e("http://demo.mycrm.com/service/v4_1/rest.php", "WP2SL"); ?></p>
													</td>
												</tr>
												<tr>
													<td><strong><?php esc_html_e("SugarCRM Admin User", "WP2SL"); ?> :</strong><br />
														<input name="OEPL_SUGARCRM_ADMIN_USER" autocomplete="off" type="text" id="OEPL_SUGARCRM_ADMIN_USER" value="<?php echo get_option('OEPL_SUGARCRM_ADMIN_USER'); ?>" size="25" required />
													</td>
												</tr>
												<tr>
													<td><strong><?php esc_html_e("SugarCRM Admin Password", "WP2SL"); ?> : </strong><br />
														<input name="OEPL_SUGARCRM_ADMIN_PASS" autocomplete="off" type="text" id="OEPL_SUGARCRM_ADMIN_PASS" required size="25" />
													</td>
												</tr>
												<tr>
													<td colspan="2"><strong><?php esc_html_e("Is your SugarCRM .htaccess protected ?", "WP2SL"); ?></strong>&nbsp;&nbsp;
														<input type="checkbox" name="OEPL_is_htacess_protected" id="OEPL_is_htacess_protected" <?php echo $htacessCbx; ?> />
													</td>
												</tr>
												<tr class="OEPL_htaccess_tr">
													<td><strong><?php esc_html_e(".htaccess UserName", "WP2SL"); ?> : </strong><br />
														<input type="text" name="Oepl_Htaccess_Admin_User" id="Oepl_Htaccess_Admin_User" value="<?php echo $htaccessUsername; ?>" />
													</td>
												</tr>
												<tr class="OEPL_htaccess_tr">
													<td><strong><?php esc_html_e(".htaccess Password", "WP2SL"); ?> : </strong><br />
														<input type="text" name="Oepl_Htaccess_Admin_Pass" id="Oepl_Htaccess_Admin_Pass" />
													</td>
												</tr>
												<tr>
													<td class="OEPL_reload_this"><input type="button" name="testConn" id="testConn" value="Test Connection" class="button button-large" />
														&nbsp;&nbsp;
														<input type="submit" value="<?php _e('Save Changes') ?>" class="button button-primary button-large OEPLsaveConfig" />
														&nbsp;&nbsp;
														<?php if (get_option('OEPL_SUGARCRM_ADMIN_PASS') && get_option('OEPL_SUGARCRM_ADMIN_PASS') != '') {
														?>
															<input type="button" value="<?php _e('Synchronize Lead Fields') ?>" id="LeadFldSync" class="button button-primary button-large" />
														<?php } ?>
													</td>
												</tr>
											<?php } ?>
											<?php if ($_GET['view'] === 'generalSettings') {
												/**************************************
												 * PLUGIN GENERAL SETTINGS
												 **************************************/
											?>
												<div class="title">
													<?php esc_html_e("Plugin Genaral Settings", "WP2SL"); ?>
												</div>
												<tr>
													<th><?php esc_html_e("CAPTCHA status :", "WP2SL"); ?></th>
													<td>
														<select class="captchaSettings" name="captchaSettings">
															<option <?php echo ($captchaSettings === 'Y' ? 'selected="selected"' : '') ?> value="Y"><?php esc_html_e("Active", "WP2SL"); ?></option>
															<option <?php echo ($captchaSettings === 'N' || $captchaSettings === FALSE ? 'selected="selected"' : '') ?> value="N"><?php esc_html_e("Inactive", "WP2SL");?></option>
														</select>
														<p class="description">
															<?php esc_html_e("You can Enable or Disable CAPTCHA on your Lead-Forms anytime you want.", "WP2SL"); ?>
														</p>
													</td>
												</tr>

												<tr>
													<th><?php esc_html_e("Select CAPTCHA :", "WP2SL"); ?></th>
													<td>
														<select class="oepl_sel_captcha" name="oepl_sel_captcha">
															<option <?php echo ($sel_captcha === 'numeric' || $sel_captcha === FALSE ? 'selected="selected"' : '') ?> value="numeric"><?php esc_html_e("Numeric CAPTCHA", "WP2SL"); ?></option>
															<option <?php echo ($sel_captcha === 'google' ? 'selected="selected"' : '') ?> value="google"><?php esc_html_e("Google CAPTCHA", "WP2SL"); ?></option>
														</select>
														<p class="description">
															<?php esc_html_e("You can Select Which CAPTCHA you want to use on your Lead-Forms anytime you want.", "WP2SL"); ?>
														</p>
													</td>
												</tr>
												<?php
												$oepl_recaptcha_site_key 	= get_option('OEPL_RECAPTCHA_SITE_KEY');
												$oepl_recaptcha_secret_key 	= get_option('OEPL_RECAPTCHA_SECRET_KEY');
												?>

												<tr class="reCAPTCHA_tr">
													<td  class="reCAPTCHA_td" colspan="2"><strong><?php esc_html_e("Goolge reCAPTCHA settings", "WP2SL"); ?></strong>
														<p class="description"><?php esc_html_e("To use reCAPTCHA, you need to", "WP2SL"); ?> <a href="<?php echo esc_url('https://www.google.com/recaptcha/admin#list'); ?>" target="_blank"><?php esc_html_e("sign up for API keys", "WP2SL"); ?></a> <?php esc_html_e("for your site.", "WP2SL"); ?> </p>
													</td>
												</tr>
												<tr class="reCAPTCHA_tr">
													<th><strong><?php esc_html_e("reCAPTCHA Site key (Public key) :", "WP2SL"); ?> </strong></th>
													<td>
														<input type="text" class="oepl_recaptcha_site_key" value="<?php echo $oepl_recaptcha_site_key; ?>" name="oepl_recaptcha_site_key" id="oepl_recaptcha_site_key" />
													</td>
												</tr>
												<tr class="reCAPTCHA_tr">
													<th><strong><?php esc_html_e("reCAPTCHA Secret key (Private key) :", "WP2SL"); ?> </strong></th>
													<td>
														<input type="text" class="oepl_recaptcha_secret_key" value="<?php echo $oepl_recaptcha_secret_key; ?>" name="oepl_recaptcha_secret_key" id="oepl_recaptcha_secret_key" />
													</td>
												</tr>


												<tr>
													<th align="right"><?php esc_html_e("Capture Remote IP :","WP2SL"); ?></th>
													<td><input type="checkbox" name="IPaddrStatus" value="1" class="IPaddrStatus" <?php echo $IP_checked; ?> />
														<p class="description"><?php esc_html_e("If check box is checked then plug-in will pass user's Remote IP Address to your SugarCRM Lead module.", "WP2SL"); ?> <br />
														<div class="OEPL_highlight_this"><strong><?php esc_html_e("To use this feature – you must create a custom field in your SugarCRM Lead module with field-name 'lead_remote_ip'.", "WP2SL"); ?></div>
														</p>
													</td>
												</tr>

												<tr>
													<th align="right"><?php esc_html_e("Get Email Notification :", "WP2SL"); ?></th>
													<td><input type="checkbox" name="EmailNotification" id="EmailNotification" <?php echo $Email_checked; ?> />
														<p class="description"><?php esc_html_e("Receive email notification whenever a new lead is generated.", "WP2SL"); ?></p>
													</td>
												</tr>

												<tr class="EmailToTR">
													<th align="right"><?php esc_html_e("Send Email to :", "WP2SL"); ?></th>
													<td>
														<input type="text" name="EmailReceiver" size="50" id="EmailReceiver" value="<?php echo $EmailNotificationRx; ?>" />
														<p class="description">
														<?php esc_html_e("Provide Email address to which notification will be sent. Multiple recipients may be specified using a comma", "WP2SL"); ?> ( , ) <?php esc_html_e("separated string.", "WP2SL"); ?>
														</p>
													</td>
												</tr>

												<tr>
													<th align="right"><?php esc_html_e("Redirect after submit :", "WP2SL"); ?></th>
													<td><input type="checkbox" name="OEPL_redirect_user" id="OEPL_redirect_user" <?php echo $redirectCbx; ?> />
														<p class="description"><?php esc_html_e("Redirect user to any page you want after lead is successfully generated.", "WP2SL"); ?></p>
													</td>
												</tr>

												<tr class="OEPL_redirect_tr">
													<th align="right"><?php esc_html_e("Redirect to :", "WP2SL"); ?></th>
													<td>
														<input type="text" name="OEPL_redirect_user_to" id="OEPL_redirect_user_to" value="<?php echo $redirectTo; ?>" size="50" />
														<p class="description">
														<?php esc_html_e("Please enter URL including http://...", "WP2SL"); ?>
															<br />
															<strong><?php esc_html_e("example :", "WP2SL"); ?></strong> <?php echo esc_url("http://www.DomainName.com/"); ?>
														</p>
													</td>
												</tr>

												<tr height="8"></tr>
												<tr>
													<th></th>
													<td><?php submit_button('Save Settings', 'primary', 'OEPL_save_general_settings', false); ?></td>
												</tr>
											<?php } ?>

											<?php if ($_GET['view'] === 'messageSettings') {
												/**************************************
												 * PLUGIN MESSAGE SETTINGS
												 **************************************/
											?>
												<div class="title">
													</span> <?php esc_html_e("Plugin Message Settings", "WP2SL"); ?>
												</div>
												<tr>
													<th><?php esc_html_e("Success Message :", "WP2SL"); ?></th>
													<td><textarea class="SuccessMessage" cols="40" rows="2" name="SuccessMessage"><?php echo $successMsg; ?></textarea>
														<p class="description"><?php esc_html_e("Message to be displayed when lead is successfully generated.", "WP2SL"); ?></p>
													</td>
												</tr>
												<tr>
													<th><?php esc_html_e("Failure Message :", "WP2SL"); ?></th>
													<td><textarea cols="40" rows="2" class="FailureMessage" name="FailureMessage"><?php echo $failureMsg; ?></textarea>
														<p class="description"><?php esc_html_e("Message to be displayed when plugin cannot submit lead to SugarCRM.", "WP2SL"); ?></p>
													</td>
												</tr>
												<tr>
													<th><?php esc_html_e("Required Fields Message :", "WP2SL"); ?></th>
													<td><textarea cols="40" rows="2" class="ReqFieldsMessage" name="ReqFieldsMessage"><?php echo $ReqFieldsMessage; ?></textarea>
														<p class="description"><?php esc_html_e("Message to be displayed when user is too lazy to fill in the required fields.", "WP2SL"); ?></p>
													</td>
												</tr>
												<tr>
													<th><?php esc_html_e("Invalid Captcha Message :", "WP2SL"); ?></th>
													<td><textarea cols="40" rows="2" class="InvalidCaptchaMessage" name="InvalidCaptchaMessage"><?php echo $InvalidCaptchaMessage; ?></textarea>
														<p class="description"><?php esc_html_e("Message to be displayed when user is so week at math that he cannot do simple addtion of two digits.", "WP2SL"); ?> </p>
													</td>
												</tr>
												<tr>
													<td></td>
													<td><?php submit_button('Save Messages', 'primary', 'OEPL_message_save', false); ?></td>
												</tr>
											<?php } ?>

											<?php if ($_GET['view'] === 'CustomCSS') {
												/**************************************
												 * PLUGIN CUSTOM CSS SETTINGS
												 **************************************/
											?>
												<div class="title">
													<?php esc_html_e("Custom CSS", "WP2SL"); ?>
												</div>
												<tr>
													<td class="OEPL_custom_css_td"><textarea rows="15" name="OEPL_custom_css" id="OEPL_custom_css"><?php echo get_option("OEPL_Form_Custom_CSS"); ?></textarea>
														<p class="description"><?php esc_html_e("You can write down your custom css here. Be sure to wrap your styles in", "WP2SL"); ?> "&lt;style&gt; &lt;/style&gt;" <br />
														<?php esc_html_e("Click here for basic", "WP2SL"); ?> <a class="OEPL_form_structure_a"  onclick="jQuery('#OEPL_form_structure').toggle()"><?php esc_html_e("Form Structure", "WP2SL"); ?></a></p>

														<div id="OEPL_form_structure">
															<xmp style="cursor:copy;border: 1px solid #009688;padding: 10px;box-shadow: 0 0 5px #009688 inset !important;">
<p>// <?php esc_html_e("Form Message will be displayed here", "WP2SL"); ?>
<div class='LeadFormMsg'></div>
</p>

<form id="OEPL_Widget_Form">
	<p>
		<label><strong><?php esc_html_e("LABEL HERE", "WP2SL"); ?> <span class="span_cls">*</span> :</strong></label><br>
	</p>

	<p class="WidgetLeadFormCaptcha">
		<label>What is &nbsp;<strong>8 + 9</strong> ?</label><br>
		<input type="text" id="captcha">
	</p>

	<p>
		<input type="submit" id="WidgetFormSubmit" value="Submit" name="submit">
	</p>
</form>
															</xmp>
														</div>
													</td>
												</tr>

												<tr>
													<td class="OEPL_css_save_td"><?php submit_button('Save Custom CSS', 'primary', 'OEPL_css_save', false); ?></td>
												</tr>
											<?php } ?>

											<?php if ($_GET['view'] === 'pro') {
												/**************************************
												 * PLUGIN CUSTOM CSS SETTINGS
												 **************************************/
											?>
												<div class="title">
													<?php esc_html_e("Wordpress to Sugar and SuiteCRM form builder PRO", "WP2SL"); ?>
												</div>
												<p>
												<?php esc_html_e("Thank you for using our Plugin. It's always nice to have more on your plate. Our PRO edition will help you to", "WP2SL"); ?> <strong><?php esc_html_e("generate unlimited web-forms", "WP2SL"); ?></strong> <?php esc_html_e("in few simple steps via user friendly drag & drop designed based form-builder.", "WP2SL"); ?>
												</p>
												<p><?php esc_html_e("If you didn't know then let me tell you that our plugin is also compitible with", "WP2SL"); ?> <strong><?php esc_html_e("SuiteCRM.", "WP2SL"); ?></strong></p>
												<h4 class="OEPL_highlight_this OEPL-red"><?php esc_html_e("Pro Version Features :", "WP2SL"); ?></h4>
												<ul class="pro_version_ul">
													<li>
													<?php esc_html_e("User-friendly Drag & Drop form builder.", "WP2SL"); ?>
													</li>
													<li>
													<?php esc_html_e("Option to use Custom CAPTCHA method or google reCAPTCHA on your Lead-Forms.", "WP2SL") ;?>
													</li>
													<li>
													<?php esc_html_e("Create multiple forms and use Shortcode generated anywhere on your WordPress site.", "WP2SL"); ?>
													</li>
													<li>
													<?php esc_html_e("Unlimited Forms with different set of fields.", "WP2SL"); ?>
													</li>
													<li>
													<?php esc_html_e("Additional email notification option for each form.", "WP2SL"); ?>
													</li>
													<li>
													<?php esc_html_e("Generate Short-Code to set form anywhere in your WordPress website.", "WP2SL"); ?>
													</li>
													<li>
													<?php esc_html_e("Email compose feature to send auto reply to visitors for individual forms. You can use Lead-Form fields in WYSIWYG editor to compose email template.", "WP2SL"); ?>
													</li>
													<li>
													<?php esc_html_e("Get premium support.", "WP2SL"); ?>
													</li>
												</ul>
												<hr />
												<p><a class="OEPL-link" href="<?php echo esc_url('https://www.youtube.com/watch?feature=player_embedded&v=Ue8XFqC6bnM'); ?>" target="_blank"><?php esc_html_e("Click Here", "WP2SL"); ?></a> <?php esc_html_e("for detailed video walk through of our", "WP2SL"); ?> <strong><?php esc_html_e("'Wordpress to SugarCRM form builder PRO'.", "WP2SL"); ?></strong></p>
												<p><a class="OEPL-link" href="<?php echo esc_url('https://offshoreevolution.com/sugarcrm-customization'); ?>" target="_blank"><?php esc_html_e("Click Here", "WP2SL"); ?></a> <?php esc_html_e("to submit your inquiry to get PRO plugin and more detail about it.", "WP2SL"); ?></p>
											<?php } ?>

											<?php if ($_GET['view'] === 'ShortCode') {
												/**************************************
												 * PLUGIN CUSTOM CSS SETTINGS
												 **************************************/
											?>
												<div class="title">
													<?php esc_html_e("Short Code", "WP2SL"); ?>
												</div>
												<h3><?php esc_html_e("[OEPL_CRM_Lead_Form]", "WP2SL"); ?></h3>
											<?php } ?>

											<?php if ($_GET['view'] === 'CRMPlugins') { ?>
												<div class="title">
													</span> <?php esc_html_e("Our Sugar Plugins", "WP2SL"); ?>
												</div>

												<span class="fa-1x">
													<a href="<?php echo esc_url('https://offshoreevolution.com/scan-identical-leads'); ?>" target="_blank"><?php esc_html_e("Scan Identical Leads", "WP2SL");?></a>
													<span class="blink3oDays">
													<?php esc_html_e("Free 30 day trial", "WP2SL"); ?>
													</span>
												</span>
												<div class="PluginDesc">
												<?php esc_html_e("Have newly created leads? Processing imported leads from one or more sources? Quickly identify if there is already an existing lead by setting up your predefined parameters. Any possible duplicates will be highlighted and noted when viewing a given lead.", "WP2SL"); ?>
												</div>
												<br />
												<br />
												<span class="fa-1x"><a href="<?php echo esc_url('https://offshoreevolution.com/proximity-search-for-sugarcrm-suitecrm'); ?>" target="_blank"><?php esc_html_e("Proximity Search", "WP2SL"); ?></a> <span class="blink3oDays"><?php esc_html_e("Free 30 day trial", "WP2SL"); ?></span></span>
												<div class="PluginDesc">
												<?php esc_html_e("Proximity searches are becoming more essential to businesses every day. Sales and marketing teams use them to organize field visits and campaigns. The real estate industry use them to offer better alternative locations to their customers. Hotel, travel and tourism businesses use them to offer various accommodation options and places to visit. How will you use it?", "WP2SL"); ?>
												</div>

												<br />
											<?php } ?>

											<?php if ($_GET['view'] === 'SuitePlugins') { ?>
												<div class="title">
													<?php esc_html_e("Our SuiteCRM Plugins", "WP2SL"); ?>
												</div>

												<span class="fa-1x"><a href="<?php echo esc_url('https://offshoreevolution.com/scan-identical-leads'); ?>" target="_blank" ><?php esc_html_e("Scan Identical Leads", "WP2SL"); ?></a> <span class="blink3oDays"><?php esc_html_e("Free 30 day trial", "WP2SL"); ?></span></span>
												<div class="PluginDesc">
												<?php esc_html_e("Have newly created leads? Processing imported leads from one or more sources? Quickly identify if there is already an existing lead by setting up your predefined parameters. Any possible duplicates will be highlighted and noted when viewing a given lead.", "WP2SL"); ?>
												</div>
												<br /><br />
												<span class="fa-1x"><a href="<?php echo esc_url('https://offshoreevolution.com/proximity-search-for-sugarcrm-suitecrm'); ?>" target="_blank"><?php esc_html_e("Proximity Search", "WP2SL"); ?></a> <span class="blink3oDays"><?php esc_html_e("Free 30 day trial", "WP2SL"); ?></span></span>
												<div class="PluginDesc">
												<?php esc_html_e("Proximity searches are becoming more essential to businesses every day. Sales and marketing teams use them to organize field visits and campaigns. The real estate industry use them to offer better alternative locations to their customers. Hotel, travel and tourism businesses use them to offer various accommodation options and places to visit. How will you use it?", "WP2SL"); ?>
												</div>

												<br />
											<?php } ?>

										</table>
									</form>
								</div>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>