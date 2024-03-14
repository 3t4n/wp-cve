<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://miniorange.com/
 * @since      1.1.1
 *
 * @package    Media_Restriction
 * @subpackage Media_Restriction/admin/partials
 */

/**
 * Adding required packages.
 */
require 'media-restriction-addon .php';
/**
 * Stating page UI intiate.
 *
 * @return mixed
 */
function mo_media_restrict_page_ui() {
	?>
	<div style="overflow:hidden">
		<div class="row" style="margin-left:-20px">

				<?php
				$currenttab = '';
				if ( isset( $_GET['tab'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
					$currenttab = sanitize_text_field( wp_unslash( $_GET['tab'] ) ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
				}
				?>
	<div class="tab">
	<div class="logo-details mo_media_restriction_logo_details">
	<img src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . '../images/miniorange_logo.png'; ?>" width="10%">&nbsp;&nbsp;&nbsp;
	<span class="logo_name mo_media_restriction_logo_name">miniOrange</span>
	</div>
	<a href="admin.php?page=mo_media_restrict&tab=configure_file_restriction"><button class="tablinks 
	<?php
	if ( '' === $currenttab || 'configure_file_restriction' === $currenttab ) {
		echo 'active';}
	?>
	" id="defaultOpen"><i class="fa fa-file-text"></i>&nbsp;&nbsp;&nbsp;<span class="links_name">File & Folder Restriction</span></button></a>
	<a href="admin.php?page=mo_media_restrict&tab=redirect_option"><button class="tablinks 
	<?php
	if ( 'redirect_option' === $currenttab ) {
		echo 'active';}
	?>
		" id="defaultOpen2"><i class="fa fa-external-link"></i>&nbsp;&nbsp;&nbsp;Select Server & Redirect &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;option</button></a>
	<a href="admin.php?page=mo_media_restrict&tab=private_directory"><button class="tablinks 
	<?php
	if ( 'private_directory' === $currenttab ) {
		echo 'active';}
	?>
	"><i class="fa fa-folder-open"></i>&nbsp;&nbsp;&nbsp;Private Directory</button></a>
	<a href="admin.php?page=mo_media_restrict&tab=configure_role_base_restriction"><button class="tablinks 
	<?php
	if ( 'configure_role_base_restriction' === $currenttab ) {
		echo 'active';}
	?>
		"><i class="fa fa-cogs"></i>&nbsp;&nbsp;&nbsp;Role/User base Restriction</button></a> 
	<a href="admin.php?page=mo_media_restrict&tab=ip_restriction"><button class="tablinks 
	<?php
	if ( 'ip_restriction' === $currenttab ) {
		echo 'active';}
	?>
	"><i class="fa fa-database"></i>&nbsp;&nbsp;&nbsp;IP restriction</button></a>

	<a href="admin.php?page=mo_media_restrict&tab=add_on"><button class="tablinks 
	<?php
	if ( 'add_on' === $currenttab ) {
		echo 'active';}
	?>
	"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;&nbsp;Add-on</button></a>
	<a href="admin.php?page=mo_media_restrict&tab=account_setup"><button class="tablinks 
	<?php
	if ( 'account_setup' === $currenttab ) {
		echo 'active';}
	?>
	"><i class="fa fa-user-o"></i>&nbsp;&nbsp;&nbsp;Account</button></a>
	<a href="admin.php?page=mo_media_restrict&tab=requestfordemo"><button class="tablinks 
	<?php
	if ( 'requestfordemo' === $currenttab ) {
		echo 'active';}
	?>
		"><i class="fa-sharp fa-solid fa-cloud"></i>&nbsp;&nbsp;&nbsp;Trials Available</button></a>
</div>
	<?php if ( ! ( isset( $_REQUEST['tab'] ) && 'licensingtab' === sanitize_text_field( wp_unslash( $_REQUEST['tab'] ) ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission. ?> 
<div class="row mo_oauth_notice_after" style="margin-left: 20.8%;margin-top:1.5%;display: flex;">
		<div class="mo_media_restriction_box box" style="width: calc(100% / 3 - 70px);"><a href="admin.php?page=mo_media_restrict&tab=licensingtab"><div class="mo_media_restriction_sub_box">
		<div class="right-side">
			<div class="box-topic">Licensing plans</div>
		</div>
		&nbsp;&nbsp;<img src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . '../images/pricing-icon.png'; ?>" width="12%">
		</div></a></div>		
		<div class="mo_media_restriction_box box"><a href="https://plugins.miniorange.com/protect-wordpress-media-files#featuredocumentation" target="_blank"><div class="mo_media_restriction_sub_box">
		<div class="right-side">
			<div class="box-topic">Features Documentation</div>
		</div>
		&nbsp;&nbsp;<img src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . '../images/addon-icon.png'; ?>" width="10%">
		</div></a></div>
		<div class="mo_media_restriction_box box" ><a href="https://wordpress.org/support/plugin/prevent-file-access/" target="_blank"><div class="mo_media_restriction_sub_box">
		<div class="right-side">
			<div class="box-topic">Ask questions on forum</div>
		</div>
		&nbsp;&nbsp;<img src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . '../images/addon-icon.png'; ?>" width="10%">
		</div></a></div>
	</div>
				<?php
	}
	if ( '' === $currenttab || 'configure_file_restriction' === $currenttab ) {
		mo_media_restrict_file_restriction();
	} elseif ( 'configure_role_base_restriction' === $currenttab ) {
		mo_media_role_base_restriction();
	} elseif ( 'private_directory' === $currenttab ) {
		mo_media_restrict_private_directory();
	} elseif ( 'redirect_option' === $currenttab ) {
		mo_media_redirect_option_tab();
	} elseif ( 'ip_restriction' === $currenttab ) {
		mo_media_ip_restriction_tab();
	} elseif ( 'licensingtab' === $currenttab ) {
		mo_media_restrict_licensing_tab();
	} elseif ( 'account_setup' === $currenttab ) {
		mo_media_account_setup_tab();
	} elseif ( 'requestfordemo' === $currenttab ) {
		mo_media_restrict_demo_folder();
	} elseif ( 'add_on' === $currenttab ) {
		mo_media_restrict_addon_list();
	}
	?>
		</div>
		<?php if ( ! ( isset( $_REQUEST['tab'] ) && 'licensingtab' === sanitize_text_field( wp_unslash( $_REQUEST['tab'] ) ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission. ?>
			<div class="col-md-3" style="padding:0px;">
				<div class="rightbar-sec" style="margin: 1em 2.5em 1em 0em">
				<div class="premium-block mo_media_restriction_premium_block" style="text-align: center; color: white;">
					<p style="font-weight: bold;font-size: 18px;letter-spacing: 1px;margin-top: 25px;margin-bottom: 18px;">Unlock More<br>Security Features</p>
					<p><span><b>✓</b></span>&nbsp;&nbsp;Role-based restriction</p>
					<p><span><b>✓</b></span>&nbsp;&nbsp;File &amp; folder restriction</p>
					<p class="">Starting at <span class="" style="font-weight: bold;font-size: 25px;">$249*</span></p>
					<p class=""><a href="admin.php?page=mo_media_restrict&tab=licensingtab"><button type="button" style="width:auto;padding: 5px 10px;border-radius:4px;background: #ffffff;border: navajowhite;color: #7C7C7C;" class="">Go Enterprise Now</button></a></p>
				</div>
			<br>
	<div class="mo_media_restriction_security_card">
		<div class="inner-block"> 
			<img src="https://i.postimg.cc/rpdyXn4c/1519864451822-1-removebg-preview.png" height="65px" width="65px" style=" width: 30px;height: 30px;">
			<p class="mo_media_restriction_reverse_proxy_card" >Reverse Proxy</p>
			<p style="color: #838383;">miniOrange</p>
			<hr style="margin-top: 10px; margin-bottom: 15px;">
		<p class="" style="line-height: 22px;color: #515151;">Reverse Proxy improves site performance and protects your websites against web vulnerabilities, which provides advanced security solutions such as IP restriction, Media Restriction, URL Rewriting, Rate Limiting and many more</p>
		<i class="fa fa-star str-rating-saml"></i>
		<i class="fa fa-star str-rating-saml"></i>
		<i class="fa fa-star str-rating-saml"></i>
		<i class="fa fa-star str-rating-saml"></i>
		<i class="fa fa-star-half-o str-rating-saml"></i>
		<br><br>
		<p class=""><a href="https://wordpress.org/plugins/reverse-proxy/" target="_blank"><button type="button"class="mo_media_restriction-button">Go Premium Now</button></a></p>
		</div>
	</div>
<?php } ?>
	<div class="mo_media_restriction_support-icon" style="display: block;">
			<div class="mo_media_restriction_help-container" id="help-container" style="display: block;">
				<span class="mo_media_restriction_span1">
					<div class="mo_media_restriction_need">
					<span class="mo_media_restriction_span2"></span>
						<div id="mo_media_restriction-support-msg">Need Help? We are right here!</div><span><button type="button" style="cursor: pointer;float:right;color: #d62727;" class="dashicons dashicons-dismiss mo-oauth-notice-dismiss" id="mo_media_restriction-support-message-hide">
				</button></span>
					</div>
				</span>
				<div id="service-btn">
				<div class="mo_media_restriction-service-icon">
					<img src="<?php echo esc_url( plugins_url( 'images/mail.png', __DIR__ ) ); ?>" class="mo_media_restriction-service-img" alt="support">
				</div>
			</div>
			</div>
		</div>

	<div class="mo_media_restriction-support-form-container" style="display: none;">
			<div class="mo_media_restriction-widget-header">
				<b>Contact miniOrange Support</b>
				<div class="mo_media_restriction-widget-header-close-icon">
				<button type="button" style="cursor: pointer;float:right;" class="dashicons dashicons-dismiss mo-oauth-notice-dismiss" id="mo_media_restriction-support-form-hide">
				</button>
				</div>
			</div>
			<br>
			<div class="support-form top-label" style="display: block;padding:10px 15px;">
			<p class="mo_media_restriction_contact_us_p"><b>Just send us a query so we can help you.</b></p>
					<form action="" method="POST">
						<?php wp_nonce_field( 'mo_media_restriction_contact_us_form', 'mo_media_restriction_contact_us_field' ); ?>
						<input type="hidden" name="option" value="mo_media_restriction_contact_us">
						<div class="form-group">
							<input type="email" placeholder="Enter email here" class="form-control" name="mo_media_restriction_contact_us_email" id="mo_media_restriction_contact_us_email" required>
						</div>
						<div class="form-group">
							<input type="tel" id="mo_media_restriction_contact_us_phone" pattern="[\+]\d{11,14}|[\+]\d{1,4}[\s]\d{9,10}" placeholder="Enter phone here" class="form-control" name="mo_media_restriction_contact_us_phone">
						</div>
						<div class="form-group">
							<textarea class="form-control" onkeypress="mo_media_restriction_contact_us_valid_query(this)" onkeyup="mo_media_restriction_contact_us_valid_query(this)" onblur="mo_media_restriction_contact_us_valid_query(this)" name="mo_media_restriction_contact_us_query" placeholder="Enter query here" rows="3" id="mo_media_restriction_contact_us_query" required></textarea>
						</div>
						<input type="submit" class="btn btn-primary mo_media_restriction_button_css" id="mo_media_restriction-submit-support" style="width: 120px;height: 35px;text-align: center;padding: 5px;" value="Submit">
					</form>
					<p class="mo_media_restriction_contact_us_p"></br><b>If you want custom features in the plugin, just drop an email at <u><a href="mailto:info@xecurify.com">info@xecurify.com</a></u></b></p>
			</div>
		</div>
	<?php if ( ! ( isset( $_REQUEST['tab'] ) && 'licensingtab' === sanitize_text_field( wp_unslash( $_REQUEST['tab'] ) ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission. ?>
	</div>	
</row>
</div>
	<?php } ?>
		</div>
	</div>
	<?php
}
/**
 * Licensing tab UI.
 *
 * @return mixed
 */
function mo_media_restrict_licensing_tab() {
	?>
	<!-- HTML Code start -->
	<div class="col-md-12">
		<div id="account-setup" class="tabcontent mo_media_restriction_tabcontent">
		<div class="dashboard-sec mo_media_restriction_container" style="margin-left: 23%;">
		<div class="mo_media_restriction_card">
		<h4><img src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . '../images/pricing-icon.png'; ?>" width="33px"><span>&nbsp;&nbsp;&nbsp;Choose From The Below Plans To Upgrade </span></h4>
		<hr class="mo_media_restriction_hr">
		<section class='content'>
		<div class="container">
			<div class="clearfix">
				<div class="row pricing-wrapper comparison-table clearfix style-3">
					<div class="col-md-4 pricing-col list-feature">
						<div class="pricing-card">
							<div class="pricing-header">
								<h5>Choose Your Plan</h5>
								<p>Compare Package Feature</p>
							</div>
							<div class="pricing-feature">
								<li class="heading">
									<p><b>File Restriction</b></p>
								</li>
								<li>
									<p>1. Number of Extensions</p>
								</li>
								<li class="heading">
									<p><b>Redirect Option</b></p>
								</li>
								<li>
									<p>1. Display Custom Page</p>
								</li>
								<li>
									<p>2. WordPress login</p>
								</li>
								<li>
									<p>3. SSO (SAML or OAuth) login</p>
								</li>
								<li class="heading">
									<p><b>Folder Restriction</b></p>
								</li>
								<li>
									<p>1. WordPress Upload Folder</p>
								</li>
								<li>
									<p>2. WordPress Custom Folder</p>
								</li>
								<li>
									<p>3. User Based Folder Restriction</p>
								</li>
								<li>
									<p>4. Roles Based Folder Restriction</p>
								</li>
								<li class="heading">
									<p><b>Protected Folder</b></p>
								</li>
								<li>
									<p>1. Number of file uploads</p>
								</li>
								<li class="heading">
									<p><b>Supported server</b></p>
								</li>
								<li>
									<p>1. Apache</p>
								</li>
								<li>
									<p>2. NGINX</p>
								</li>
								<li class="heading">
									<p><b>Security level Base</b></p>
								</li>
								<li>
									<p>1. Cookie</p>
								</li>
								<li>
									<p>2. Session</p>
								</li>
								<li class="heading">
									<p><b>Media Management</b></p>
								</li>
								<li>
									<p>1. Controlled access</p>
								</li>
								<li>
									<p>2. Categorize folders & subfolder</p>
								</li>
								<li>
									<p>3. Logs ( Upload, Download, Delete)</p>
								</li>
								<li class="heading">
									<p><b>Membership Media Restriction</b></p>
								</li>
								<li>
									<p>1. Paid Memberships Pro</p>
								</li>
								<li>
									<p>2. ARMember Membership</p>
								</li>
								<li>
									<p>3. WordPress Membership</p>
								</li>
								<li>
									<p>4. WooCommerce Subscription</p>
								</li>
							</div>
						</div>
					</div>
					<!-- Premium Plan  -->
					<div class="col-md-2.6 pricing-col person">
						<div class="pricing-card">
							<div class="pricing-header">
								<h5>Premium</h5>
								<div class="price-box">
									<div class="price">149
										<div class="currency">$</div>
										<!-- <div class="plan">/ Year</div> -->
									</div>
								</div>
							</div>
							<div class="pricing-feature pricing-center">
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<span>UNLIMITED</span>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<span>UNLIMITED</span>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
							</div>
							<div class="pricing-footer">
								<a onclick="upgradeform('wp_media_restriction_plan')" class="btn btn-act rounded btn-line">
									<span>Upgrade now</span>
									<i class="fa fa-arrow-right"></i>
								</a>
							</div>
						</div>
					</div>
					<!-- Enterprise Plan -->
					<div class="col-md-2.6 pricing-col">
						<div class="pricing-card">
							<div class="pricing-header">
								<h5>Enterprise</h5>
								<div class="price-box">
									<div class="price">249
										<div class="currency">$</div>
										<!-- <div class="plan">/ Year</div> -->
									</div>
								</div>
							</div>
							<div class="pricing-feature pricing-center">
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<span>UNLIMITED</span>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<span>UNLIMITED</span>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-times unavailable"></i>
									</p>
								</li>
							</div>
							<div class="pricing-footer">
								<a onclick="upgradeform('wp_media_restriction_enterprise_plan')" class="btn btn-act rounded btn-line">
									<span>Upgrade now</span>
									<i class="fa fa-arrow-right"></i>
								</a>
							</div>
						</div>
					</div>
					<!-- All Inclusive Plan -->
					<div class="col-md-2.6 pricing-col person">
						<div class="pricing-card">
							<div class="pricing-header">
								<h5>ALL-INCLUSIVE</h5>
								<a class="ribbon">
									<i class="fa fa-star"></i>
									<span>Recommended</span>
								</a>
								<div class="price-box">
									<div class="price">449
										<div class="currency">$</div>
										<!-- <div class="plan">/ Year</div> -->
									</div>
								</div>
							</div>
							<div class="pricing-feature pricing-center">
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<span>UNLIMITED</span>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<span>UNLIMITED</span>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li class="heading">
									<p>
										<i>&nbsp;&nbsp;&nbsp;</i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-check available"></i>
									</p>
								</li>
							</div>
							<div class="pricing-footer">
								<a onclick="upgradeform('wp_media_restriction_all_inclusive_plan')" class="btn btn-act rounded btn-line">
									<span>Upgrade now</span>
									<i class="fa fa-arrow-right"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="moc-licensing-notice">
							<span style="color: red;">*</span>Cost applicable for one instance only. Licenses are perpetual and the Support Plan includes 12 months of maintenance (support and version updates). You can renew maintenance after 12 months at 50% of the current license cost.
							<p><span style="color: red;">*</span><strong>MultiSite Network Support</strong>
								There is an additional cost for the number of subsites in Multisite Network.</p>
							<h4>10 Days Return Policy</h4>
							<p>At miniOrange, we want to ensure you are 100% happy with your purchase. If the premium plugin you purchased is not working as advertised and you've attempted to resolve any issues with our support team, which couldn't get resolved. We will refund the whole amount within 10 days of the purchase. Please email us at <a href="mailto:info@xecurify.com" target="_blank">info@xecurify.com</a> for any queries regarding the return policy.</p>
							<p><b>This functionality operates at the server level, thus if the Apache server rules doesn't work. Please contact info@xecurify.com with your concerns.</b></p>
							<p><b>the WP Engine, Siteground and other servers like this runs on a nginx server, which requires the use of nginx configuration rules. Please email us at info@xecurify.com or oauthsupport@xecurify.com if you face any issues.</b></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
</div></div>
	<a id="mobacktoaccountsetup" style="display:none;"
	href="<?php echo ! empty( $_SERVER['REQUEST_URI'] ) ? esc_attr( add_query_arg( array( 'tab' => 'account_setup' ), sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ) : ''; ?>">
	Back</a>
	<input type="hidden" value="<?php echo 'account-setup' === get_option( 'mo_media_restriction_new_user' ) ? 1 : 0; ?>" id="mo_customer_registered">
	<script>
	function upgradeform(planType) {
		if (planType === "") {
		location.href = "https://wordpress.org/plugins/prevent-file-access/";
		return;
		} else {
		if (jQuery('#mo_customer_registered').val() == 1) {
			const url = `https://portal.miniorange.com/initializepayment?requestOrigin=${planType}`;
			window.open(url, "_blank");
		} else {
			location.href = jQuery('#mobacktoaccountsetup').attr('href');
		}
		}
	}
	</script>
	<?php
}
/**
 * Set up tab UI function.
 *
 * @return mixed
 */
function mo_media_account_setup_tab() {
	if ( false === get_option( 'mo_media_restriction_new_user' ) || 'register' === get_option( 'mo_media_restriction_new_user' ) ) {
		?>
		<row class=" row mo_media_restriction_row">
		<div class="col-md-9"> 
		<div id="account-setup" class="tabcontent mo_media_restriction_tabcontent">
		<div class="dashboard-sec mo_media_restriction_container">
		<div class="mo_media_restriction_card">
		<h4><img src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . '../images/addon-icon.png'; ?>" width="5%"><span>&nbsp;&nbsp;&nbsp;Register with miniOrange &nbsp;<small class="optional-btn mo_media_restriction_optional-btn" style="font-size: x-small;">[OPTIONAL]</small></span></h4>
		<hr class="mo_media_restriction_hr">
			<h6>Why should I register? </h6>
			<p class="mo_media_restriction_contact_us_p mo_media_restriction_notice_background">You should register so that in case you need help, we can help you with step by step instructions.<b> You will also need a miniOrange account to upgrade to the premium version of the plugins.</b> We do not store any information except the email that you will use to register with us.</p>
			<br>
			<form action="" method="POST">
				<?php wp_nonce_field( 'mo_media_restriction_register_customer_form', 'mo_media_restriction_register_customer_field' ); ?>
				<input type="hidden" name="option" value="mo_media_restriction_register_customer">

				<div class="row" style="margin-top:20px">
					<div class="col-md-4">
						<h6 class="mo_media_restriction_label_heading"><b>Email:</b></h6>
					</div>
					<div class="col-md-4">
						<input type="email" placeholder="person@example.com" value="<?php echo esc_attr( get_option( 'mo_media_restriction_admin_email' ) ); ?>" name="mo_media_restriction_admin_email" class="form-control" required>
					</div>
				</div>

				<div class="row" style="margin-top:20px">
					<div class="col-md-4">
						<h6 class="mo_media_restriction_label_heading"><b>Password:</b></h6>
					</div>
					<div class="col-md-4">
						<input type="password" name="mo_media_restriction_password" minlength="8" placeholder="Choose your password (Min. length 8)" class="form-control" required>
					</div>
				</div>

				<div class="row" style="margin-top:20px">
					<div class="col-md-4">
						<h6 class="mo_media_restriction_label_heading"><b>Confirm Password:</b></h6>
					</div>
					<div class="col-md-4">
						<input type="password" name="mo_media_restriction_confirm_password" minlength="8" placeholder="Confirm your password" class="form-control" required>
					</div>
				</div>

				<div class="row" style="margin-top:20px">
					<div class="col-md-4"></div>
					<div class="col-md-3">
						<input type="submit" class="btn btn-primary btn-large mo_media_restriction_button_css" style="width:120px;padding:5px;height:35px;margin-right:40px;" value="Register">
					</div>
					<div class="col-md-4">
						<input type="button" class="btn btn-primary btn-large mo_media_restriction_button_css" style="height:35px;padding: 5px 15px;" id="mo_media_restriction_goto_login" value="Already have an account?">
					</div>
				</div>
			</form>
		</div>
	</div></div>
		<form action="" id="mo_media_restriction_goto_login_form" method="POST">
			<?php wp_nonce_field( 'mo_media_restriction_change_to_login', 'mo_media_restriction_change_to_login_field' ); ?>
			<input type="hidden" name="option" value="mo_media_restriction_change_to_login">
		</form>
		<script>
			jQuery('#mo_media_restriction_goto_login').click(function() {
				jQuery('#mo_media_restriction_goto_login_form').submit();
			});
		</script>
		<?php
	} elseif ( 'login' === get_option( 'mo_media_restriction_new_user' ) ) {
		?>
		<row class=" row mo_media_restriction_row">
		<div class="col-md-9"> 
		<div id="account-setup" class="tabcontent mo_media_restriction_tabcontent">
		<div class="dashboard-sec mo_media_restriction_container" >
		<div class="mo_media_restriction_card">
		<h4><img src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . '../images/addon-icon.png'; ?>" width="5%"><span>&nbsp;&nbsp;&nbsp;Login with miniOrange &nbsp;<small class="optional-btn mo_media_restriction_optional-btn" style="font-size: x-small;">[OPTIONAL]</small></span></h4>
		<hr class="mo_media_restriction_hr">
			<p class="mo_media_restriction_contact_us_p mo_media_restriction_notice_background">It seems you already have an account with miniOrange. Please enter your miniOrange email and password.</p>
	</br><p><a target="_blank" href="https://login.xecurify.com/moas/idp/resetpassword" rel="noopener">*Click here if you forgot your password?</a></p>
			<form action="" method="POST">
				<?php wp_nonce_field( 'mo_media_restriction_login_customer_form', 'mo_media_restriction_login_customer_field' ); ?>
				<input type="hidden" name="option" value="mo_media_restriction_login_customer">

				<div class="row" style="margin-top:20px">
					<div class="col-md-3">
						<h6 class="mo_media_restriction_label_heading"><b>Email:</b></h6>
					</div>
					<div class="col-md-4">
						<input type="email" placeholder="person@example.com" value="<?php echo esc_attr( get_option( 'mo_media_restriction_admin_email' ) ); ?>" name="mo_media_restriction_admin_email" class="form-control" required>
					</div>
				</div>

				<div class="row" style="margin-top:20px">
					<div class="col-md-3">
						<h6 class="mo_media_restriction_label_heading"><b>Password:</b></h6>
					</div>
					<div class="col-md-4">
						<input type="password" name="mo_media_restriction_password" minlength="8" placeholder="Choose your password (Min. length 8)" class="form-control" required>
					</div>
				</div>

				<div class="row" style="margin-top:20px">
					<div class="col-md-3"></div>
					<div class="col-md-3">
						<input type="submit" class="btn btn-primary btn-large mo_media_restriction_button_css" style="width:120px;padding: 5px;height:35px;" value="Login">
					</div>
					<div class="col-md-3">
						<input type="button" class="btn btn-primary btn-large mo_media_restriction_button_css" id="mo_media_restriction_goto_register" style="width:120px;padding: 5px;height:35px;" value="Sign Up">
					</div>
				</div>
			</form>
		</div>
	</div></div>
		<form action="" id="mo_media_restriction_goto_register_form" method="POST">
			<?php wp_nonce_field( 'mo_media_restriction_change_to_register', 'mo_media_restriction_change_to_register_field' ); ?>
			<input type="hidden" name="option" value="mo_media_restriction_change_to_register">
		</form>
		<script>
			jQuery('#mo_media_restriction_goto_register').click(function() {
				jQuery('#mo_media_restriction_goto_register_form').submit();
			});
		</script>
		<?php
	} elseif ( 'account-setup' === get_option( 'mo_media_restriction_new_user' ) ) {
		?>
		<row class=" row mo_media_restriction_row">
		<div class="col-md-9"> 
		<div id="role-restriction" class="tabcontent mo_media_restriction_tabcontent">
		<div class="dashboard-sec mo_media_restriction_container" >	
		<div class="mo_media_restriction_card">
			<h4 style="margin-bottom:20px"><img src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . '../images/miniorange_logo.png'; ?>" width="30px">&nbsp;&nbsp;&nbsp;<span>Thank you for registering with miniOrange.</span></h4>
			<hr>
			<div class="row">
				<table class="table table-striped table-bordered">
					<tbody>
						<tr>
							<td><b>miniOrange Account Email</b></td>
							<td><?php echo esc_html( get_option( 'mo_media_restriction_admin_email' ) ); ?></td>
						</tr>
						<tr>
							<td><b>Customer ID</b></td>
							<td><?php echo esc_html( get_option( 'mo_media_restriction_admin_customer_key' ) ); ?></td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="row" style="margin-top:20px">
				<div class="col-md-2">
					<form action="" method="POST">
						<?php wp_nonce_field( 'mo_media_restriction_change_to_register', 'mo_media_restriction_change_to_register_field' ); ?>
						<input type="hidden" name="option" value="mo_media_restriction_change_to_register">
						<input type="submit" class="btn btn-primary btn-large mo_media_restriction_button_css" style="height:35px" value="Change email address">
					</form>
				</div>
			</div>
	</div></div>
		</div>
		<?php
	}
}
/**
 * Redirect options UI function.
 *
 * @return mixed
 */
function mo_media_redirect_option_tab() {
	$mo_initiate_class   = new Media_Restriction_Admin( 'prevent-file-access', MO_MEDIA_RESTRICTION_PLUGIN_NAME_VERSION );
	$mo_demo_nginx_rules = $mo_initiate_class->mo_media_restrict_write_nginx_rules();
	?>
	<row class=" row mo_media_restriction_row">
	<div class="col-md-9"> 
	<div id="file-folder" class="tabcontent mo_media_restriction_tabcontent">
	<div class="dashboard-sec mo_media_restriction_container" >
		<div class="mo_media_restriction_card">
		<h4><img src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . '../images/addon-icon.png'; ?>" width="5%"><span>&nbsp;&nbsp;&nbsp;Redirect Option</span></h4>
		<hr class="mo_media_restriction_hr">
		<p style="color: #400d0d;font-size: 12px;" class="mo_media_restriction_notice_background">&nbsp;&nbsp;&nbsp;This feature allows you to redirect the restricted user to the desired page when they access the restricted media.</p>
		<?php
			$restrict_option = 'display-custom-page';
		?>
				<form action="" method="POST" id="mo_media_restriction_media_restriction_enable_form">
			<?php wp_nonce_field( 'mo_media_restriction_media_restriction_enable_form', 'mo_media_restriction_enable_field' ); ?>
			<input type="hidden" name="option" value="mo_media_restriction_enable">
			<input type="hidden" id="mo_media_restriction_show_rules" name="mo_media_restriction_show_rules" value="0">
		<div class="row" style="margin-top:20px">
					<div class="col-md-5">
						<h6 class="mo_media_restriction_label_heading"><b>Choose Redirect Option:</b></h6>
					</div>

					<div class="col-md-6">
						<input type="radio" class="mo_media_restriction_redirect_radio" name="mo_mr_restrict_option" value="display-custom-page" <?php checked( 'display-custom-page' === $restrict_option ); ?>> <span class="mo_media_restriction_redirect_radio_text">Display Custom Page</span> &nbsp;&nbsp;</br>
						<input type="radio" disabled class="mo_media_restriction_redirect_radio" name="mo_mr_restrict_option" value="redirect-to-wordpress-login"> <span class="mo_media_restriction_redirect_radio_text">Redirect to WordPress login <small style="color:red;"><b>&nbsp;&nbsp;<a class="premium-btn mo_media_restriction_premium-btn" href="admin.php?page=mo_media_restrict&amp;tab=licensingtab">PREMIUM</a></b></small></span>&nbsp;&nbsp;</br>
						<input type="radio" disabled class="mo_media_restriction_redirect_radio" name="mo_mr_restrict_option" value="redirect-to-idp-login"> <span class="mo_media_restriction_redirect_radio_text">Redirect to SSO Login <small style="color:red;"><b>&nbsp;&nbsp;<a class="premium-btn mo_media_restriction_premium-btn" href="admin.php?page=mo_media_restrict&amp;tab=licensingtab">ENTERPRISE</a></b></small> </span>
					</div>
				</div>

				<?php
				$page_list   = get_pages();
				$redirect_to = get_option( 'mo_mr_redirect_to' );
				if ( empty( $redirect_to ) ) {
					$redirect_to = '403-forbidden-page';
				}
				?>
				</br>

				<div class="row" style="margin-top:20px">
					<div class="col-md-5">
						<h6 class="mo_media_restriction_label_heading"><b>Redirect to:</b></h6>
					</div>

					<div class="col-md-5">
						<select id="display-custom-page-select" class="form-control mo_media_restriction_select" name="mo_media_redirect_to_display_page">
							<option value="403-forbidden-page" 
							<?php
							if ( '403-forbidden-page' === $redirect_to ) {
								echo 'selected';}
							?>
							>403 Forbidden Page</option>
							<?php
							if ( count( $page_list ) > 0 ) {
								foreach ( $page_list as $page ) {
									echo '<option value="' . esc_attr( $page->post_name ) . '"';
									if ( $page->post_name === $redirect_to ) {
										echo 'selected';
									}
									echo ' >' . esc_html( $page->post_title ) . '</option>';
								}
							}
							?>
						</select>
						<select readonly style="display:none" id="redirect-to-idp-login-select" class="form-control mo_media_restriction_select">
							<option>SAML SSO login</option>
							<option>OAuth SSO login</option>
						</select>
						<select readonly style="display:none" id="redirect-to-wordpress-login-select" class="form-control mo_media_restriction_select">
							<option>WordPress login</option>
						</select>
					</div>
				</div>

						</br></br>

			<h4><img src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . '../images/addon-icon.png'; ?>" width="5%"><span>&nbsp;&nbsp;&nbsp;Advanced Option</span></h4>
			<hr class="mo_media_restriction_hr">
				<p style="color: #400d0d;font-size: 12px;" class="mo_media_restriction_notice_background">&nbsp;&nbsp;&nbsp;Please select the Server on which your website is hosted.</p>		
			<div class="row" style="margin-top:20px">
				<div class="col-md-5">
					<h6 class="mo_media_restriction_label_heading"><b>Choose server: </b><a href="https://plugins.miniorange.com/protect-wordpress-media-files#serverselection" target="_blank" ><i class="fa fa-info-circle" style="color:black"></i></a></h6>
				</div>
				<div class="col-md-5">
					<?php
						$choose_server = get_option( 'mo_media_restriction_choose_server', 'apache' );
					?>
					<input type="radio" 
					<?php
					if ( 'apache' === $choose_server ) {
						?>
						checked <?php } ?> name="choose_server" value="apache"> <span class="mo_media_restriction_redirect_radio_text">Apache</span> &nbsp;&nbsp;</br>
					<input type="radio" 
					<?php
					if ( 'godaddy' === $choose_server ) {
						?>
						checked <?php } ?> name="choose_server" value="godaddy"> <span class="mo_media_restriction_redirect_radio_text">GoDaddy Managed Hosting Server</span> &nbsp;&nbsp;</br>
					<input type="radio" 
					<?php
					if ( 'nginx' === $choose_server ) {
						?>
						checked <?php } ?> name="choose_server" value="nginx"> <span class="mo_media_restriction_redirect_radio_text">NGINX  [DEMO RULES AVAILABLE]&nbsp;&nbsp;</span>&nbsp;&nbsp;
				</div>
				<div class="col-md-2">
				</div>
			</div>
			<div class="row" style="margin-top:20px">
				<div class="col-md-5">
					<h6 class="mo_media_restriction_label_heading"><b>Security Level Base: </b><a href="https://plugins.miniorange.com/protect-wordpress-media-files#securitylevelbase" target="_blank" ><i class="fa fa-info-circle" style="color:black"></i></a></h6>
					<p></p>
				</div>
				<div class="col-md-5">
					<input type="radio" name="security_level" checked><span class="mo_media_restriction_redirect_radio_text">Cookie </span>&nbsp;&nbsp;
					<input type="radio" name="security_level" disabled> <span class="mo_media_restriction_redirect_radio_text">Session  <small style="color:red;"><b>&nbsp;&nbsp;<a class="premium-btn mo_media_restriction_premium-btn" href="admin.php?page=mo_media_restrict&amp;tab=licensingtab">ENTERPRISE</a></b></small></span>&nbsp;&nbsp;
				</div>
				<div class="col-md-2">
				</div>
			</div>
			</form>
			<div class="row" style="margin-top:50px">
				<div class="col-md-12">
					<input type="submit" onclick="mo_media_restriction_rules_confirmation()" class="btn btn-primary btn-large mo_media_restriction_button_css" style="width:150px;height:auto" value="Save Settings">
					<div class="mo_media_restriction_note_box"><p><b>Note : </b>Make sure your Permalinks Structure is not set to Plain.<br>To change Permalinks:<b> Go to Admin Dashboard -> Settings -> Permalinks -> Permalink Structure</b></p></div>
				</div>
			</div>
			<div id="confirmation-popup" class="mo_media_restriction_overlay" style="display:none">
				<div class="mo_media_restriction_popup" style="width:30%;">
					<a class="close" href="">&times;</a>
					<br>
					<br>
					<div class="content text-center">
						<h4 class="text-center"><b>The plugin will update your .htaccess file to make it work. In case if you find any difficulty drop a query on <a href="mailto:info@xecurify.com">info@xecurify.com</a></b></h4>
						<div class="mo_media_restriction_note_box"><p><b>Note : </b>Make sure your Permalinks Structure is not set to Plain.<br>To change Permalinks:<b> Go to Admin Dashboard -> Settings -> Permalinks -> Permalink Structure</b></p></div>
						<br>
						<button class="btn btn-primary btn-large mo_media_restriction_button_css" onclick="mo_media_restriction_rules_alert_box(true,'mo_media_restriction_media_restriction_enable_form')" style="width:250px;height:50px">Okay, I understand</button>
					</div>
				</div>
			</div>
		</div>
		<div id="mo_nginx_demo_rule" class="mo_media_restriction_overlay" style="display:none">
				<div class="mo_media_restriction_popup" style="width:60%;">
					<a class="close" href="">&times;</a>
					<br>
					<br>
					<div class="content text-center">
					<p style="color: #f51818;font-size: 15px;" class="mo_media_restriction_notice_background"><strong>Full extension of NGNIX server rules will be available in premium or Higher versions of plugin.</strong></p>
						<h6 class="text-center"><b>These are demo rules to restrict the Prevent files on website running on nginx server. Please upload these in rules nginx.config files. In case if you find any difficulty drop a query on <a href="mailto:info@xecurify.com">info@xecurify.com</a></b></h6>
						<br>
						<div style="width:70%;border: 1px solid black;margin:auto;padding:1%;color:red;">
						<strong>
							<?php
							echo $mo_demo_nginx_rules; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Ignoring escaping for NGINX server rules because server rules contials special character.
							?>
							</strong>
						</div>
						<br>
						<h4><b>NOTE:</b> You might need to communicate with your hosting provider support to uploads these rules.</h4>
					</div>
				</div>
		</div>
		</div>
		</div>
	<?php
}
/**
 * Demo trial UI function.
 *
 * @return mixed
 */
function mo_media_restrict_demo_folder() {
	$democss = 'width: 350px; height:30px;padding:5px;font-size:14px;';
	?>
	<row class=" row mo_media_restriction_row">
		<div class="col-md-9"> 
		<div id="account-setup" class="tabcontent mo_media_restriction_tabcontent">
		<div class="dashboard-sec mo_media_restriction_container" >
		<div class="mo_media_restriction_card">
		<h4><img src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . '../images/addon-icon.png'; ?>" width="5%"><span>&nbsp;&nbsp;&nbsp;Request For Demo </span></h4>
		<hr class="mo_media_restriction_hr">
		<div class="row">
			<div class="col-md-12">
				<p style="background: #93939317;padding: 10px 10px 10px 10px;border-radius: 10px">Want to try out the paid features before purchasing the license? Just let us know which plan you\'re interested in and we will setup a demo for you.</b></i></p>
			</div>
		</div>
		<hr>
			<form method="post" action="">
			<input type="hidden" name="option" value="mo_media_r_demo_request_form" />
			<?php wp_nonce_field( 'mo_media_r_demo_request_form', 'mo_media_r_demo_request_field' ); ?>
			<table class="mo_demo_table_layout" cellpadding="4" cellspacing="4">
				<tr>
					<td style="width:35%"><h6 class="mo_media_restriction_label_heading"><b>Email Id<p style="display:inline;color:red;">*</p>:</b></h6></td>
					<td><input required type="email" style="<?php echo esc_attr( $democss ); ?>" name="mo_auto_create_demosite_email" placeholder="We will use this email to setup the demo for you" value="<?php echo esc_attr( get_option( 'mo_media_restriction_admin_email' ) ); ?>" /></td>
				</tr>
				<tr>
					<td><h6 class="mo_media_restriction_label_heading"><strong><?php esc_html_e( 'Request a demo for', 'prevent-file-access' ); ?> <p style="display:inline;color:red;">*</p>: </strong></h6></td>
					<td>
					<select class="mo_oauth_request_demo_inputs" required style="<?php echo esc_attr( $democss ); ?>;padding: 2px 24px;" name="mo_auto_create_demosite_demo_plan" id="mo_oauth_client_demo_plan_id">
									<option disabled value="" selected>--------------------- Select ---------------------</option>
							<option value="miniorange-prevent-file-access-premium">WP Prevent Files/Folders Access Premium Plugin</option>
							<option value="miniorange-prevent-file-access-enterprise">WP Prevent Files/Folders Access Enterprise Plugin</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><h6 class="mo_media_restriction_label_heading"><strong><?php esc_html_e( 'Usecase', 'prevent-file-access' ); ?><p style="display:inline;color:red;">*</p> : </strong></h6></td>
					<td>
					<textarea type="text" minlength="15" name="mo_auto_create_demosite_usecase" style="resize: vertical; width:350px; height:100px; font-size:14px; line-height:1.5;" rows="4" placeholder="<?php esc_html_e( 'Example. I want to protect my files and folder from public based on whether they are logged in or not, based on their roles, and their membership.', 'prevent-file-access' ); ?>" required value=""></textarea>
					</td>
					</tr> 	
				<tr>
					<td></td>
					<td>
						<input type="submit" name="submit" style="height:35px;padding: 5px 15px;" value="<?php esc_html_e( 'Submit Demo Request', 'prevent-file-access' ); ?>" class="btn btn-primary btn-large mo_media_restriction_button_css" />
					</td>
				</tr>
			</table>
		</form>
	</div>
</div></div>
	<?php
}
/**
 * File restriction tab UI.
 *
 * @return mixed
 */
function mo_media_restrict_file_restriction() {
	$mo_initiate_class   = new Media_Restriction_Admin( 'prevent-file-access', MO_MEDIA_RESTRICTION_PLUGIN_NAME_VERSION );
	$mo_demo_nginx_rules = $mo_initiate_class->mo_media_restrict_write_nginx_rules();
	?>
<row class=" row mo_media_restriction_row">
<div class="col-md-9"> 
<div id="file-folder" class="tabcontent mo_media_restriction_tabcontent">
<div class="dashboard-sec mo_media_restriction_container" >
	<div class="mo_media_restriction_card">
	<h4><img src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . '../images/addon-icon.png'; ?>" width="5%"><span>&nbsp;&nbsp;&nbsp;File & Folder Restriction</span></h4>
	<hr class="mo_media_restriction_hr">
	<div class="row">
	<div class="col-md-12">
	<p style="color: #666666;font-size: 12px;" class="mo_media_restriction_notice_background">This functionality operates at the server level, thus if the Apache server rules don't work or your server is a nginx server, which requires the use of nginx configuration rules, or if you face any issues, please email us at info@xecurify.com or oauthsupport@xecurify.com. We would recommend that you please ensure your PHP server and rules will work on your server before purchasing it, or else contact us and we will help you to set up the plugin according to your requirements on your site.</b></i></p>
	</div>
	</div>
	<br>
	<div class="row">
	<div class="col-md-5">
		<h6 class="mo_media_restriction_label_heading"><b>Enable Media Restriction: </b></h6>
	</div>
	<div class="col-md-4">
	<label class="mo_media_restriction_switch">
					<form action="" method="POST" id="mo_enable_media_restriction_form">
						<?php wp_nonce_field( 'mo_media_restriction_enable_form', 'mo_media_restriction_enable_field' ); ?>
						<input value="1" name="mo_enable_media_restriction" type="checkbox" id="mo_enable_media_restriction" <?php checked( (int) get_option( 'mo_enable_media_restriction' ) === 1 ); ?>>
						<span class="mo_media_restriction_slider round"></span>
						<input type="hidden" name="option" value="mo_enable_media_restriction">
					</form>
				</label>
	</div>
	</div>
	<?php
	if ( get_option( 'mo_enable_media_restriction' ) ) {
		$mo_media_restriction_file_types = get_option( 'mo_media_restriction_file_types' ) ? str_replace( '|', ',', get_option( 'mo_media_restriction_file_types' ) ) : 'png,jpg,gif,pdf,doc';
		$restrict_option                 = 'display-custom-page';
		?>
		<form action="" id="mo_media_restriction_file_configuration_form" method="POST">
		<?php wp_nonce_field( 'mo_media_restriction_file_configuration_form', 'mo_media_restriction_file_configuration_field' ); ?>
		<input type="hidden" name="option" value="mo_media_restriction_file_types">
		<input type="hidden" id="mo_media_restriction_show_rules" name="mo_media_restriction_show_rules" value="0">
		<div class="row" style="margin-top:20px">
		<div class="col-md-3">
			<h6 class="mo_media_restriction_label_heading"><b>File types to restrict:</b></h6>
			<br>	
		</div>
		<div class="col-md-5" style="padding:0px">
			</tags><input type="text" name="mo_media_restriction_file_types" value="<?php echo esc_attr( $mo_media_restriction_file_types ); ?>" placeholder="Write file extension hit enter">
			<script>
							var input1 = document.querySelector('input[name=mo_media_restriction_file_types]'),
								// init Tagify script on the above inputs
								tagify1 = new Tagify(input1, {
									maxTags: 5,
									enforceWhitelist: true,
									whitelist: ["pdf", "png", "jpg", "doc", "gif"],
									blacklist: [] // In string format "hello","temp"
								});
						</script>
		</div>
		<div class="col-md-4">
			<p style="line-height: 22px;font-size:12px">We do support only five extenstions in our free version which are: <span class="file-type mo_media_restriction_file-type">png</span> , <span class="file-type mo_media_restriction_file-type">jpg</span> , <span class="file-type mo_media_restriction_file-type">gif</span> , <span class="file-type mo_media_restriction_file-type">pdf</span> , <span class="file-type mo_media_restriction_file-type">doc</span></p>
		</div>
		</div>
	</form>

	<div class="row" style="margin-top:50px">
				<div class="col-md-12">
					<input type="submit" onclick="mo_media_restriction_rules_confirmation()" class="btn btn-primary btn-large mo_media_restriction_button_css" style="width:150px;height:auto" value="Save Settings">
					<div class="mo_media_restriction_note_box"><p><b>Note : </b>Make sure your Permalinks Structure is not set to Plain.<br>To change Permalinks:<b> Go to Admin Dashboard -> Settings -> Permalinks -> Permalink Structure</b></p></div>
				</div>
			</div>

			<div id="confirmation-popup" class="mo_media_restriction_overlay" style="display:none">
				<div class="mo_media_restriction_popup" style="width:30%;">
					<a class="close" href="">&times;</a>
					<br>
					<br>
					<div class="content text-center">
						<h4 class="text-center"><b>The plugin will update your .htaccess file to make it work. In case if you find any difficulty drop a query on <a href="mailto:info@xecurify.com">info@xecurify.com</a></b></h4>
						<div class="mo_media_restriction_note_box"><p><b>Note : </b>Make sure your Permalinks Structure is not set to Plain.<br>To change Permalinks:<b> Go to Admin Dashboard -> Settings -> Permalinks -> Permalink Structure</b></p></div>
						<br>
						<button class="btn btn-primary btn-large mo_media_restriction_button_css" onclick="mo_media_restriction_rules_alert_box(true,'mo_media_restriction_file_configuration_form')" style="width:250px;height:50px">Okay, I understand</button>
					</div>
				</div>
			</div>
		</div>
		<div id="mo_nginx_demo_rule" class="mo_media_restriction_overlay" style="display:none">
				<div class="mo_media_restriction_popup" style="width:60%;">
					<a class="close" href="">&times;</a>
					<br>
					<br>
					<div class="content text-center">
						<h6 class="text-center"><b>These are demo rules to restrict the files inside protected folders on a website running on an nginx server. Please upload these in the rules.nginx.config file. In case if you find any difficulty drop a query on <a href="mailto:info@xecurify.com">info@xecurify.com</a></b></h6>
						<br>
						<div style="width:70%;border: 1px solid black;margin:auto;padding:1%;color:red;">
						<strong>
						<?php
						echo $mo_demo_nginx_rules; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Ignoring escaping for NGINX server rules because server rules contials special character.
						?>
							</strong>
						</div>
						<br>
						<h4><b>NOTE:</b> You might need to communicate with your hosting provider support to uploads these rules.</h4>
					</div>
				</div>
		<?php } ?>
	</div>
	<?php
	/**
	 * Fuction for subdirectory.
	 *
	 * @param  mixed $path directory path.
	 * @return bool
	 */
	function mo_media_restrict_directory_has_subdirectory( $path ) {
		$subdir_list = scandir( $path );
		foreach ( $subdir_list as $list ) {
			if ( '.' !== $list || '..' !== $list ) {
				$check_dir = $path . '/' . $list;
				if ( is_dir( $check_dir ) ) {
					return true;
				}
			}
		}
		return false;
	}
	/**
	 * Print Directory
	 *
	 * @param mixed $path directory path.
	 * @param mixed $parentfolder parent folder.
	 * @return mixed
	 */
	function mo_media_restrict_directory_print( $path, $parentfolder ) {
		$subdir_list = scandir( $path );
		foreach ( $subdir_list as $list ) {
			if ( '.' !== $list && '..' !== $list && 'protectedfiles' !== $list ) {
				$check_dir = $path . '/' . $list;
				if ( is_dir( $check_dir ) ) {
					echo '<li>';
					if ( mo_media_restrict_directory_has_subdirectory( $check_dir ) ) {
						echo '<i id="mo-media-restriction-' . esc_attr( $parentfolder . '-' . $list ) . '-i" onclick="mo_media_restrict_display_folder(\'mo-media-restriction-' . esc_attr( $parentfolder . '-' . $list ) . '\')" class="mo_media_restriction_plus_icon">+</i>';
					}
					echo '<input disabled type="checkbox" id="mo-media-restriction-' . esc_attr( $parentfolder . '-' . $list ) . '"';
					if ( get_option( 'mo_media_restriction_folder_list' ) !== false ) {
						$selected_dir = str_replace( '/', '-', get_option( 'mo_media_restriction_folder_list' ) );
						if ( in_array( $parentfolder . '-' . $list, $selected_dir, true ) ) {
							echo 'checked';
						}
					}
					echo '>
				<label for="mo-media-restriction-' . esc_attr( $parentfolder . '-' . $list ) . '">' . esc_html( $list ) . '</label>';
					if ( mo_media_restrict_directory_has_subdirectory( $check_dir ) ) {
						echo '<ul class="mo_media_restriction_pure_tree">';
						$parentfolder = $parentfolder . '-' . $list;
						mo_media_restrict_directory_print( $check_dir, $parentfolder );
						echo '</ul>';
						$all_folders = explode( '-', $parentfolder );
						unset( $all_folders[ count( $all_folders ) - 1 ] );
						$parentfolder = implode( '-', $all_folders );
					}
					echo '</li>';
				}
			}
		}
	}
	?>
	<div class="mo_media_restriction_card">
		<h4 style="margin-bottom:30px">Folder Restriction <a href="https://plugins.miniorange.com/protect-wordpress-media-files#uploadfolderrestrictions" target="_blank" ><i class="fa fa-info-circle" style="color:black"></i></a></h4>
		<p>
			<?php
			$upload_dir = wp_upload_dir();
			?>
		</p>
		<p style="color: #400d0d;font-size: 12px;" class="mo_media_restriction_notice_background">This feature allows you to restrict access to the complete uploads folder or any subfolder in the uploads folder. You can also select multiple folders inside the Uploads folder and they all will be restricted from public access.</p>
	<form method="post" id="mo_media_restriction_folder_configuration_form" action="">
	<div class="row">
		<div class="col-md-6">
		<h6 class="mo_media_restriction_label_heading"><b>WP Upload Folder to restrict:</b> <small style="color:red;font-size:12px"><b><a class="premium-btn mo_media_restriction_premium-btn" href="admin.php?page=mo_media_restrict&amp;tab=licensingtab">PREMIUM</a></b></small> </h6>
		</div>
		<div class="col-md-4">
		<?php
		if ( $upload_dir && isset( $upload_dir['basedir'] ) && false === $upload_dir['error'] ) {
			?>
						<ul class="mo_media_restriction_pure_tree main-tree">
							<li>
								<i id="mo-media-restriction-uploads-i" onclick="mo_media_restrict_display_folder('mo-media-restriction-uploads')" class="mo_media_restriction_plus_icon">+</i>
								<input class="" disabled type="checkbox" id="mo-media-restriction-uploads">
								<label for="mo-media-restriction-uploads">uploads</label>
					<?php
					if ( mo_media_restrict_directory_has_subdirectory( $upload_dir['basedir'] ) ) {
						echo '<ul class="mo_media_restriction_pure_tree">';
						mo_media_restrict_directory_print( $upload_dir['basedir'], 'uploads' );
						echo '</ul>';
					}
					?>
							</li>
						</ul>
					<?php
		} else {
			echo '<b style="color:red">' . esc_html( $upload_dir['error'] ) . '</b>';
		}
		?>
	</div>
	</div>
	<div class="row">
		<div class="col-md-6">
		<h6 class="mo_media_restriction_label_heading"><b>WP Custom Folder to restrict:</b> <small style="color:red;font-size:12px"><b><a class="premium-btn mo_media_restriction_premium-btn" href="admin.php?page=mo_media_restrict&amp;tab=licensingtab">ENTERPRISE</a></b></small> </h6>
		</div>
		<div class="col-md-4">
		<input type="text" class="form-control" placeholder="Enter folder name here" disabled="">
		</div>
	</div>
	</form>
	<div class="row" style="margin-top:50px">
	<div class="col-md-12">
		<input type="submit" disabled="" class="btn btn-large mo_media_restriction-button mo_media_ip_btn mo_noHover" style="width: 150px; padding: 6px 12px;" value="Save Settings">
	</div>
	</div>
</div>
</div></div>

	<?php
}
/**
 * Role base restriction tab.
 *
 * @return mixed
 */
function mo_media_role_base_restriction() {
	?>

<row class=" row mo_media_restriction_row">
<div class="col-md-9"> 
<div id="role-restriction" class="tabcontent mo_media_restriction_tabcontent">
<div class="dashboard-sec mo_media_restriction_container" >
	<div class="mo_media_restriction_card">
		<h4 style="margin-bottom:30px">User base Folder Restriction <small style="color:red;font-size:12px"><b><a class="premium-btn mo_media_restriction_premium-btn" href="admin.php?page=mo_media_restrict&amp;tab=licensingtab">ENTERPRISE</a></b></small></h4>
		<div class="row">
		<div class="col-md-6">
			<h6 class="mo_media_restriction_label_heading"><b>Enable user base restriction:</b></h6>
		</div>
		<div class="col-md-4">
			<label class="mo_media_restriction_switch">
			<input value="1" name="mo_enable_user_base_restriction" disabled type="checkbox" id="mo_enable_user_base_restriction">
			<span class="mo_media_restriction_slider round" style="cursor:not-allowed;"></span>
			</label>
		</div>
		</div>
	</div>

	<div class="mo_media_restriction_card">
		<h4 style="margin-bottom:30px">Role base Folder Restriction <small style="color:red;font-size:12px"><b><a class="premium-btn mo_media_restriction_premium-btn" href="admin.php?page=mo_media_restrict&amp;tab=licensingtab">ENTERPRISE</a></b></small></h4>
		<p style="color: #400d0d;font-size: 12px;" class="mo_media_restriction_notice_background">You can use this feature to restrict access to folders based on WordPress roles. You need to assign a folder name to the role that can access it, then only the user with that role will be able to access that particular folder.</p>
		<div class="row">
		<div class="col-md-6">
			<h6 class="mo_media_restriction_label_heading"><b>Enable role base restriction:</b></h6>
		</div>
		<div class="col-md-4">
			<label class="mo_media_restriction_switch">
			<input value="1" name="mo_enable_role_base_restriction" type="checkbox" id="mo_enable_role_base_restriction" onclick="show_default_roles()" checked>
			<span class="mo_media_restriction_slider round"></span>
			</label>
		</div>
		</div>
		<?php
		$all_roles = wp_roles()->roles;
		?>
			</br>
			<div id="mo_media_role_based_restriction_check" style="display:block;">
				<div class="row">
					<div class="col-md-6">
						<h6 class="mo_media_restriction_label_heading"><b>Roles</b></h6>
					</div>
					<div class="col-md-4">
						<h6 class="mo_media_restriction_label_heading"><b>Folder name</b></h6>
					</div>
				</div>
				<br>
				<?php
				$role_folder_list = get_option( 'mo_role_base_restriction_folder_list' );
				foreach ( $all_roles as $key => $role ) {
					?>
						<div class="row" style="opacity:0.5;">
							<div class="col-md-6">
								<input name='role[]' type="hidden" value="<?php echo esc_attr( $key ); ?>">
								<p><b>
								<?php
								echo esc_attr( $role['name'] ) . '</b>';
								if ( 'administrator' === $key ) {
									echo '(The administrator can access any folder in WordPress instance but if you want to restrict a folder such that only administrator can access it and no other user then enter it here)';
								}
								?>
								</p>
							</div>
							<div class="col-md-4 form-group">
								<input class="form-control" name="role_folder[]" type="text" placeholder="Enter folder name here" value="
								<?php
								if ( isset( $role_folder_list[ $key ] ) ) {
									echo esc_attr( $role_folder_list[ $key ] );}
								?>
								" disabled>
							</div>
						</div>
					<?php
				}
				?>
		</div>
		</div>
	</div>
	</div>
	<script>	
		function show_default_roles(){
			let roleToggle= document.getElementById('mo_enable_role_base_restriction');
			let displayRoles = document.getElementById('mo_media_role_based_restriction_check');
							if(roleToggle.checked){
								displayRoles.style.display='block';
							} else {
								displayRoles.style.display='none';
							}
						}

		</script>
	<?php
}
/**
 * Page Restriction tab.
 *
 * @return mixed
 */
function mo_media_restrict_page_restriction() {
	?>
	<div class="mo_oauth_premium_option_text"><span style="color:red;">*</span>This is a add-on feature.
		<a href="https://wordpress.org/plugins/page-and-post-restriction/" target="_blank" rel="noopener">Click Here</a> to see our full list of add-on feature.</div>
	<div class="mo_media_restriction_card" style="background-color: rgba(168, 168, 168, 0.7);opacity: 0.5;">
		<h4 style="margin-bottom:30px">Page/Post Restriction</h4>
		<p>
			<?php
			$upload_dir = wp_upload_dir();
			?>
		</p>

		<div style="padding-right:10px; font-size: small;">
			<h3 style="font-size: 20px;">Give Access to Pages based on Roles</h3>
			<p>
				<b>Note </b>: Enter role(s) of a user that you want to give access to for a page. Other roles will be restricted (By default all pages/posts are accessible to all users irrespective of their roles).
			</p>
			<p>
				<b>Note </b>: Before clicking on "Save Configuration", please check all the boxes of the pages/posts for which you want to save the changes.
			</p>

			<input type="hidden" id="_wpnonce" name="_wpnonce" value="ccc4b3f2bc"><input type="hidden" name="_wp_http_referer" value="/hookswp/wp-admin/admin.php?page=page_restriction">
			<input type="hidden" name="option" value="papr_restrict_pages">
			<span style="color: blue;">Select All</span> &nbsp;
			<span style="color: blue;">Deselect All</span>



			<br><br>&nbsp;&nbsp;&nbsp;<input disabled type="checkbox" class="checkBoxClass1" name="mo_page_0" value="true"><b>Home Page</b><i>&nbsp;&nbsp;<span style="color: blue;">[Visit Page]</span></i>
			<input class="mo_roles_suggest ui-autocomplete-input" disabled type="text" name="mo_role_values_0" id="0" value="" placeholder="Enter (;) separated Roles" style="width: 300px;" autocomplete="off"><br><br>&nbsp;&nbsp;&nbsp;<input disabled type="checkbox" class="checkBoxClass1" name="2" value="true" > <b>Sample Page</b>&nbsp;<i><span style="color: blue;">[Visit Page]</span></i>&nbsp; <input class="mo_roles_suggest ui-autocomplete-input" disabled type="text" name="mo_role_values_2" id="2" value="" placeholder="Enter (;) separated Roles" style="width: 300px;" autocomplete="off"><br><br><br><input type="submit" disabled class="button button-primary button-larges" value="Save Configuration" 11="">
			<br>
			<h3 style="font-size: 20px;">Give Access to Posts based on Roles</h3>
			<p>
				<b>Note </b>: Enter a role(s) of a user that you want to give access to for a post (By default all posts are accessible to all users irrespective of their roles).
			</p>

			<span style="color: blue;">Select All</span> &nbsp;
			<span style="color: blue;">Deselect All</span>
			<table>

				<tbody>
					<tr style="margin-bottom:3%;">
						<td><input type="checkbox" disabled class="checkBoxClass2" name="mo_post_1" value="true"> <b>Hello world!</b><i>&nbsp;&nbsp;<span style="color: blue;">[Visit Post]</span> </i> </td>
						<td>
							<input class="mo_roles_suggest ui-autocomplete-input" disabled type="text" name="mo_role_values_1" value="" placeholder="Enter (;) separated Roles" style="width: 300px;" autocomplete="off"><br><br>
						</td>
					</tr>
				</tbody>
			</table><br>
			<input type="submit" disabled class="button button-primary button-larges" value="Save Configuration">
			<br>

			<h3 style="font-size: 20px;">Give Access to Category of Posts based on Roles
			</h3>
			<p><b>Note </b>: Enter a role(s) of a user that you want to give access to for a Category post (By default all posts are accessible to all users irrespective of their roles). </p>
			<span style="color: blue;">Select All</span> &nbsp;
			<span style="color: blue;">Deselect All</span>
			<table>


				<tbody>
					<tr style="margin-bottom:3%;">
						<td><input type="checkbox"  class="checkBoxClass2" name="mo_category_1" value="true" disabled>
							<b>Uncategorized</b><i>&nbsp;&nbsp;<span style="color: blue;">[Visit Category]</span></i></td>
						<td><input class="mo_roles_suggest ui-autocomplete-input" style="width:175%" type="text" name="mo_role_values_1" disabled value="" placeholder="Enter (;) separated Roles" autocomplete="off"><br><br></td>
					</tr>
				</tbody>
			</table>
			<br>
			<input type="submit" disabled class="button button-primary button-larges" value="Save Configuration">
			<br><br>
		</div>

		<div style="padding-right:10px; font-size: small;">
			<h3 style="font-size: 20px;">Select pages you want to give access to Logged in Users only</h3>
			<p>
				<b>Note </b>: Selet the page(s) that you want to restrict access, for a user not Logged In (By default all pages/posts are accessible to all users).
			</p>
				<span class="selectAll"><input type="checkbox" disabled name="papr_select_all_pages"  id="selectall4"> Select All Pages
				</span><br>
				<span class="mo_pr_help_desc"><b>NOTE: </b> If this option is enabled, all the newly added pages will be checked by default.</span>

				<br><br>&nbsp;&nbsp;&nbsp;<input disabled type="checkbox" class="checkBoxClass3" name="mo_redirect_0" value="true"> <b>Home Page&nbsp;</b><i><span style="color: blue;">[visit page]</span> </i> <br><br> &nbsp;&nbsp;&nbsp;<input type="checkbox" disabled class="checkBoxClass3" id="2" name="2" value="true"><b>Sample Page</b><i>&nbsp;<span style="color: blue;">[visit page]</span></i><br><br><br><input type="submit" disabled class="button button-primary button-larges" value="Save Configuration">
			<br>

			<h3 style="font-size: 20px;">Select posts you want only Logged in Users to access</h3>
			<p><b>Note </b>: Select the post(s) that you want to restrict access, for a User not Logged In (By default all pages/posts are accessible to all users). </p>


				<span class="selectAll"><input disabled type="checkbox"   id="selectall4" name="papr_select_all_posts"> Select All Posts</span><br><span class="mo_pr_help_desc"><b>NOTE: </b> If this option is enabled, all the newly added posts will be checked by default.</span><br><br>
				<table>

					<tbody>
						<tr style="margin-bottom:3%;">
							<td><input type="checkbox" disabled class="checkBoxClass4" name="mo_redirect_post_1" value="true"> <b>Hello world!</b>&nbsp;<i><span style="color: blue;">[visit post]</span></i><br><br></td>
						</tr>
					</tbody>
				</table><br>
				<input type="submit" disabled class="button button-primary button-larges" value="Save Configuration">
		</div>
	</div>
	<?php
}
/**
 * Private directory tab
 *
 * @return mixed
 */
function mo_media_restrict_private_directory() {
	?>
	<row class=" row mo_media_restriction_row">
	<div class="col-md-9"> 
	<div id="role-restriction" class="tabcontent mo_media_restriction_tabcontent">
	<div class="dashboard-sec mo_media_restriction_container" style="width: 72%;margin: 1em 0.5em 1em 28%;box-shadow: 0 5px 10px rgb(0 0 0 / 21%);padding: 23px;border-radius: 5px;">
		<div class="mo_media_restriction_card">
		<h4 ><img src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . '../images/addon-icon.png'; ?>" width="5%"><span>&nbsp;&nbsp;&nbsp;Upload files in protected folder <a href="https://plugins.miniorange.com/protect-wordpress-media-files#protectedfolder" target="_blank" ><i class="fa fa-info-circle" style="color:black"></i></a></span></h4>
		<hr class="mo_media_restriction_hr">
		<p style="color: #400d0d;font-size: 12px;" class="mo_media_restriction_notice_background">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The Private Directory feature enables you to store files within a directory that is restricted from public access.<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We do support only five extenstions in our free version which are: <span class="file-type mo_media_restriction_file-type">png</span> , <span class="file-type mo_media_restriction_file-type">jpg</span> , <span class="file-type mo_media_restriction_file-type">gif</span> , <span class="file-type mo_media_restriction_file-type">pdf</span> , <span class="file-type mo_media_restriction_file-type">doc</span></p>

		<div class="row">
			<div style="width:95%;text-align:center;padding:20px;border: 4px dashed #b4b9be;margin-left:auto;margin-right:auto;margin-bottom:10px">
				<form method="post" enctype="multipart/form-data">
					<?php wp_nonce_field( 'mo_media_restriction_file_upload_form', 'mo_media_restriction_file_upload_field' ); ?>
					<input type="file" name="fileToUpload" style="display: inline-block; position: relative; z-index: 1;width: 40%;margin-right: 90px;" required class="mo_media_restriction_upload_file">
					<input type="submit" class="mo_media_restriction-button" style="width: 100px;" value="Upload">
					<input type="hidden" name="option" value="mo_media_restriction_file_upload">
				</form>
			</div>
		</div>

		<div class="row" style="margin:20px">
			<form method="post" id="mo_media_restriction_delete_file" action="">
				<?php wp_nonce_field( 'mo_media_restriction_delete_file_form', 'mo_media_restriction_delete_file_field' ); ?>
				<input type="hidden" name="option" value="mo_media_restriction_delete_file">
				<input type="hidden" id="mo_media_restrict_filename" name="mo_media_restrict_filename" value="none">
			</form>
		</div>
			<?php
			$upload_dir = wp_upload_dir();
			if ( $upload_dir && isset( $upload_dir['basedir'] ) ) {
				$base_upload_dir   = $upload_dir['basedir'];
				$protectedfiles    = $base_upload_dir . DIRECTORY_SEPARATOR . 'protectedfiles';
				$protectedfilesurl = $upload_dir['baseurl'] . '/protectedfiles';
				if ( false !== $upload_dir['error'] ) {
					echo "<p style='color:red'>" . esc_html( $upload_dir['error'] ) . '</p>';
				} else {
					if ( ! file_exists( $protectedfiles ) && ! is_dir( $protectedfiles ) ) {
						wp_mkdir_p( $protectedfiles, 0775, true );
					}
					$diriterator = new DirectoryIterator( $protectedfiles );
					echo "<table class='mo_media_restriction_table' id='mo_media_restriction_table' style='width:100%'><thead><tr class='mo_media_restriction_tr'><th class='mo_media_restriction_th' style='width: 35%;'>File Name</th><th class='mo_media_restriction_th' style='width: 50%;'>URL</th><th class='mo_media_restriction_th' style='width:15%'>Action</th></tr></thead><tbody>";
					$count = 0;
					foreach ( $diriterator as $fileinfo ) {
						if ( ! $fileinfo->isDot() ) {
							echo "<tr class='mo_media_restriction_tr'><td class='mo_media_restriction_td' style='width: 35%;'>" . esc_html( $fileinfo->getFilename() ) . "</td><td class='mo_media_restriction_td' style='width: 50%;'>" . esc_html( $protectedfilesurl . '/' . $fileinfo->getFilename() ) . "</td><td class='mo_media_restriction_td' style='width: 15%;'><button class='btn btn-danger mo_media_restriction-button' onclick=\"mo_media_restrict_delete_file('" . esc_attr( $fileinfo->getFilename() ) . "')\">Delete</button></td></tr>";
							if ( ++$count > 100 ) {
								break;
							}
						}
					}
					echo "</tbody></table></br>
					<script>
					 jQuery(document).ready(function() {
					        jQuery('#mo_media_restriction_table').DataTable({
					            'order': [[ 1, 'desc' ]]
					        });
							jQuery('#mo_media_restriction_table td').css('white-space','initial');
					    } );
					</script>";
					echo '<style>
					table {
						table-layout:fixed;
					  }
					  table td {
						word-wrap: break-word;
						max-width: 250px;
					  }
					  #mo_media_restriction_table td {
						white-space:inherit;
					  }
					  </style>';
				}
			}
			?>
		</div>
		</div></div>
	<?php
}
/**
 * IP restriction tab.
 *
 * @return mixed
 */
function mo_media_ip_restriction_tab() {
	?>
<row class=" row mo_media_restriction_row">
<div class="col-md-9"> 
<div id="ip-restrcition" class="tabcontent mo_media_restriction_tabcontent">
<div class="dashboard-sec mo_media_restriction_container" >
	<div class="mo_media_restriction_card">
		<h4><img src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . '../images/addon-icon.png'; ?>" width="5%"><span>&nbsp;&nbsp;&nbsp;Configuration</span></h4>
	<hr class="mo_media_restriction_hr">
	<p style="color: #666666;background: #93939317;font-size: 12px;padding: 20px;border-radius: 10px;">This feature will allow you to restrict the website based on the user's IP address.</b><br>
	You can either allow specified IPs to access your website or you can restrict your website to some specific IPs.</b></i></p>

<div class="main-container" style="margin-left: 5em;">
		<div class="row">
		<div class="col-md-8">
			<h6 class="mo_media_restriction_label_heading"><b>Enable IP Based Restriction: <small style="color:red;font-size:12px"><a class="premium-btn mo_media_restriction_premium-btn" href="admin.php?page=mo_media_restrict&amp;tab=licensingtab">ENTERPRISE</a></b></small></h4>
		</div>
		<div class="col-md-4">
			<label class="mo_media_restriction_switch">
			<form action="" method="POST" id="mo_enable_ip_restriction_form">
				<?php wp_nonce_field( 'mo_enable_ip_restriction_form', 'mo_enable_ip_restriction_field' ); ?>
				<input type="hidden" name="option" value="mo_enable_ip_restriction">
				<input value="1" name="mo_enable_role_base_restriction" type="checkbox" disabled id="mo_enable_role_base_restriction" onclick="show_default_roles()">
				<span class="mo_ip_restriction_slider round" style="cursor:not-allowed"></span>
				</form>
				</label>
			</div>
		</div>
</br>
		<?php
		if ( 1 ) {
			?>
		<div class="row">
			<div class="col-md-5">
			<input disabled type="radio" name="mo_enable_ip_radio"  <?php checked( 1 ); ?>> 
		<span class="mo_media_restriction_label_heading">Allow</span> &nbsp;&nbsp;&nbsp;&nbsp;
		<input disabled="" type="radio" name="mo_enable_ip_radio"> 
		<span class="mo_media_restriction_label_heading">Restrict</span> 
			</div>
		</br>
			<div class="col-md-6">
			<input disabled="" type="text" name="mo_allowed_ip" id="mo_allowed_ip" placeholder="eg : 192.168.168.168" style="width:66%;height:35px;float:left;">&nbsp;&nbsp;&nbsp;&nbsp;
			<!-- <br><br>
			<p>Enter the IP address that you wish to Allow/Disallow</p> -->
			<button disabled="" id="" type="submit" class="btn mo_media_ip_btn mo_noHover btn-large" value="ADD" aria-disabled="false">Add</button><br>
			</div>
		</div>
		</br>
		</div>
				<div class="row" style="margin-top:20px">
					<div class="col-md-4"   >
						<table class='mo_media_restriction_table' style="width:325%;">
							<thead>
								<tr class='mo_media_restriction_tr'>
									<th class='mo_ip_restriction_th' style="width:10%">Sno.</th>
									<th class='mo_ip_restriction_th' style="width:65%">IP</th>
									<th class='mo_ip_restriction_th' style="width:25%">Action</th>
								</tr>
								<tr>
									<td class="mo_ip_restriction_td" style="width:10%"></td>
									<td class="mo_ip_restriction_td" style="width:55%;text-align: center;"><h6 class="mo_media_restriction_label_heading">No item found<h6></td>
									<td class="mo_ip_restriction_td" style="text-align:center; width:35%;"></td>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
		</div>
			<?php
		}
}
/**
 * Fuction to call Addon tab.
 *
 * @return mixed
 */
function mo_media_restrict_addon_list() {
	mo_media_restriction_addon();
}
?>
