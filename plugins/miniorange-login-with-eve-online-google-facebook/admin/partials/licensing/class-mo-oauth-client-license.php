<?php
/**
 * Licensing UI
 *
 * @package    licensing
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Adding required files
 */
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-mo-oauth-client-license-pricing-breakdown.php';


/**
 * Handle the UI for displaying licensing plans
 */
class MO_OAuth_Client_License {
	/**
	 * CSS required for display of different licensing plans
	 */
	public static function emit_css() {
		?>
		<style>
			.moc-lp-buy-btn {
				border-radius: 5rem;
				letter-spacing: .1rem;
				font-weight: bold;
				padding: 1rem;
				color:  #fff;
			}
			.moc-lp-buy-btn:hover {
				opacity: 1;
			}
			.widget-header-text{
				color: #fff;
				padding: 10px;
				display: inline-block;
				white-space: nowrap;
				overflow: hidden;
				flex-grow: 1;
				text-overflow: ellipsis;
				text-align: center;
			}
			.widget-header-close-icon{
				margin-right: 10px;
				color: white;
			}
			.widget-header{
				border-radius: 5px 5px 0 0;
				height: 40px;
				max-height: 40px;
				min-height: 40px;
				display: flex;
				align-items: center;
				background-color: #6b778c;
				position: relative;
			}
			.sticky {
				position: fixed;
				top: 30px;
				width: 100%;
			}
			.underscore {
				border-bottom:3px solid red;
			}
		</style>
		<?php
	}

	/**
	 * Display Licensing Page
	 */
	public static function show_licensing_page() {

		self::emit_css();
		?>
		<div id="navbar" style="padding-left: 22%;padding-top: 1%"  >
			<b><a href="#licensing_plans" id="plans-section" class="navbar-links">Plans</a></b>
			<b><a href="#upgrade-steps" id="upgrade-section" class="navbar-links">Upgrade Steps</a></b>
			<b><a href="#payment-method" id="payment-section" class="navbar-links">Payment Methods</a></b>
		</div>
	<script>    
		window.onscroll = function() {moOAuthStickyNavbar()};
		var navbar = document.getElementById("navbar");
		var sticky = navbar.offsetTop;

		function moOAuthStickyNavbar() {
			if (window.pageYOffset >= sticky) {
				navbar.classList.add("sticky")
			} else {
				navbar.classList.remove("sticky");
			}
		}

		var selectArray = JSON.parse('<?php echo wp_json_encode( new MO_OAuth_Client_License_Pricing_Breakdown() ); ?>');

		function createSelectOptions(elemId) {
				var selectPricingArray = selectArray[elemId];
				var selectElem = ' <div class="cd-price" id="flex-container"><div class="mo-oauth-flex-value"><span class="cd-currency">$</span><span class="cd-value" id="standardID">' + selectArray[elemId]["1"] + '</span></div><div class="mo-oauth-flex-policy"><sup><a href="#moc_licensing_policy" style="text-decoration: none;color:#7C8594;">*</a></sup></div></div>' + '</header> <!-- .cd-pricing-header --></a>' + '<h3 class="instanceClass" >No. of instances:';
				var selectElem = selectElem + ' <select class="selectInstancesClass" required="true" onchange="changePricing(this)" id="' + elemId + '">';
				jQuery.each(selectPricingArray, function (instances, price) {
					selectElem = selectElem + '<option value="' + instances + '" data-value="' + instances + '">' + instances + ' </option>';
				})
				selectElem = selectElem + "</select>";
				return document.write(selectElem);
			}

			function createSelectWithSubsitesOptions(elemId) {
				var selectPricingArray = selectArray[elemId];
				var selectSubsitePricingArray = selectArray['subsite_intances'];
				var selectElem = ' <div class="cd-price" id="flex-container"><div class="mo-oauth-flex-value"><span class="cd-currency">$</span><span class="cd-value" id="standardID">' + parseInt(parseInt(selectArray[elemId]["1"])+90) + '</span></div><div class="mo-oauth-flex-policy"><sup><a href="#moc_licensing_policy" style="text-decoration: none;color:#7C8594;">*</a></sup></div></div>' + '</header> <!-- .cd-pricing-header --></a>' + '<footer class="cd-pricing-footer"><div style="display: inline-block;float: left;"><h4 class="instanceClass" style="margin-bottom:2px;">No. of instances:';
				var selectElem = selectElem + ' <select class="selectInstancesClass" required="true" onchange="changePricing(this)" id="' + elemId + '">';
				jQuery.each(selectPricingArray, function (instances, price) {
					selectElem = selectElem + '<option value="' + instances + '" data-value="' + instances + '">' + instances + ' </option>';
				})
				selectElem = selectElem + "</select></h3>";
				selectElem = selectElem + '<h3 class="instanceClass" stlye="padding-top:2px;" >No. of subsites:&nbsp&nbsp';
				selectElem = selectElem + '<select class="selectInstancesClass" required="true" onchange="changePricing(this)" id="' + elemId + '" name="' + elemId + '-subsite">';
				jQuery.each(selectSubsitePricingArray, function (instances, price) {
					selectElem = selectElem + '<option value="' + instances + '" data-value="' + instances + '">' + instances + ' </option>';
				})
				selectElem = selectElem + "</select></h3></div>";
				return document.write(selectElem);
			}

			function changePricing($this) {
				var selectId = jQuery($this).attr("id");
				var selectSubsiteValue = jQuery("select[name=" + selectId + "-subsite]").val();
				var e = document.getElementById(selectId);
				var strUser = e.options[e.selectedIndex].value;
				var strUserInstances = strUser != "UNLIMITED" ? strUser : 500;
				selectArrayElement = [];
				selectSubsiteArrayElement = selectArray.subsite_intances[selectSubsiteValue];
				if (selectId == "pricing_standard") selectArrayElement = selectArray.pricing_standard[strUser];
				if (selectId == "pricing_premium") selectArrayElement = selectArray.pricing_premium[strUser];
				if (selectId == "pricing_enterprise") selectArrayElement = selectArray.pricing_enterprise[strUser];
				if (selectId == "pricing_all_inclusive") selectArrayElement = selectArray.pricing_all_inclusive[strUser];
				if (selectId == "mul_pricing_premium") selectArrayElement = parseInt(selectArray.mul_pricing_premium[strUser].replace(",", "")) + parseInt(parseInt(selectSubsiteArrayElement) * parseInt(strUserInstances));
				if (selectId == "mul_pricing_enterprise") selectArrayElement = parseInt(selectArray.mul_pricing_enterprise[strUser].replace(",", "")) + parseInt(parseInt(selectSubsiteArrayElement) * parseInt(strUserInstances));
				if (selectId == "mul_pricing_all_inclusive") selectArrayElement = parseInt(selectArray.mul_pricing_all_inclusive[strUser].replace(",", "")) + parseInt(parseInt(selectSubsiteArrayElement) * parseInt(strUserInstances));
				jQuery("#" + selectId).parents("div.individual-container").find(".cd-value").text(selectArrayElement);
			}

	</script>
	<!--  -->
		<!-- Important JSForms -->
		<input type="hidden" value="<?php echo esc_attr( mooauth_is_customer_registered() ); ?>" id="mo_customer_registered">
		<form style="display:none;" id="viewlicensekeys"
			action="<?php echo esc_attr( get_option( 'host_name' ) ) . '/moas/login'; ?>"
			target="_blank" method="post">
			<?php wp_nonce_field( 'mo_oauth_viewlicensekeys_form_nonce', 'mo_oauth_viewlicensekeys_form_field' ); ?>
			<input type="email" name="username" value="<?php echo esc_attr( get_option( 'mo_oauth_admin_email' ) ); ?>"/>
			<input type="text" name="redirectUrl"
				value="<?php echo esc_attr( get_option( 'host_name' ) ) . '/moas/viewlicensekeys'; ?>"/>
		</form>
		<!-- End Important JSForms -->
		<!-- Licensing Table -->
		<br>

		<div style="text-align: center;" id="licensing_plans" onmouseenter="onMouseEnter('plans-section', '3px solid #093553')" onmouseleave="onMouseEnter('plans-section', 'none')">
			<h1 class="mo_oauth_h1" style="display:block;">Choose From The Below Plans To Upgrade</h1>
		</div>
		<div class="mo-oauth-notice">
			<strong class="mo_strong">Note:</strong> License is linked to the domain of the WordPress instance, so if you have dev-staging-prod type of environment then you will require 3 licenses of the plugin (with discounts applicable on pre-production environments). To know more about different features, add-ons and all pricing plans you can visit our miniOrange OAuth SSO plugin's product page <a href="https://plugins.miniorange.com/wordpress-sso" target="_blank"><i>here</i></a>.
		</div> 
		<div class="cd-pricing-switcher" onmouseenter="onMouseEnter('plans-section')" onmouseleave="onMouseEnter('plans-section', 1)">
			<p class="fieldset">
				<input type="radio" name="sitetype" value="singlesite" id="singlesite" checked>
				<label for="singlesite"><?php esc_html_e( 'Single Site', 'miniorange-login-with-eve-online-google-facebook' ); ?></label>
				<input type="radio" name="sitetype" value="multisite" id="multisite">
				<label for="multisite"><?php esc_html_e( 'Multisite Network', 'miniorange-login-with-eve-online-google-facebook' ); ?></label>
				<span class="cd-switch"></span>
			</p>
		</div>
		<div class="mo-oauth-licensing-container" style="height: 100%;margin-bottom: 5%" onmouseenter="onMouseEnter('plans-section','3px solid #093553')" onmouseleave="onMouseEnter('plans-section', 'none')">
		<div class="mo-oauth-licensing-header">
			<div class="container-fluid">
				<div class="row">
					<div class="col-6 moct-align-right">
						&nbsp;
					</div>
					<div class="col-6 moct-align-right">
						&nbsp;
					</div>
				</div>
				<div id="single-site-section">
				<div class="row justify-content-center mx-15">
					<div class="col-3 moct-align-center individual-container">
						<div class="moc-licensing-plan card-body">
							<div class="moc-licensing-plan-header">
								<div class="moc-licensing-plan-price"><strong class="mo_strong">STANDARD</strong></div>
								<hr>
								<div class="moc-licensing-plan-name">Unlimited user creation<br>+<br>Basic Attribute Mapping<br><br></div>
								<?php echo esc_html( MO_OAUTH_CLIENT_DISCOUNT_URL ); ?>
								<script>
								createSelectOptions('pricing_standard');
								</script>
							</div>
							<button class="btn btn-block mo-oauth-btn-block btn-info text-uppercase moc-lp-buy-btn"  onclick="upgradeform('wp_oauth_client_standard_plan')" ><?php esc_html_e( 'Buy now', 'miniorange-login-with-eve-online-google-facebook' ); ?></button>
							<div class="moc-licensing-plan-feature-list">
								<ul>
									<li>&#9989;&emsp;1 OAuth / OpenID Connect provider <br>Support</li>
									<li>&#9989;&emsp;Auto Create Users (Unlimited Users)</li>
									<li>&#9989;&emsp;Account Linking</li>
									<li>&#9989;&emsp;Basic Attribute Mapping<br></li>
									<li>&#9989;&emsp;Login Widget, Shortcode and Login Link</li>
									<li>&#9989;&emsp;Authorization Code Grant <br><br><br></li>
									<li>&#9989;&emsp;Login Button Customization</li>
									<li>&#9989;&emsp;Custom Redirect URL after login and logout</li>
									<li>&#9989;&emsp;Basic Role Mapping</li>
									<li>&#10060;&emsp;<span class="text-muted">JWT Support</span></li>
									<li>&#10060;&emsp;<span class="text-muted">Protect complete site</span></li>
									<li>&#10060;&emsp;<span class="text-muted">Domain specific registration</span></li>
									<li>&#10060;&emsp;<span class="text-muted">Hide & Disable WP Login</span></li>
									<li>&#10060;&emsp;<span class="text-muted">Front-Channel & Back-Channel Single Logout (SLO) Support</span></li>
									<!-- <li>&#10060;&emsp;<span class="text-muted">Multi-site Support</span></li>                                     -->
									<li>&#10060;&emsp;<span class="text-muted">WP hooks to read token, login event and extend plugin functionality</span></li>
									<li>&#10060;&emsp;<span class="text-muted">End User Login Reports / Analytics</span></li>
									<li>&#10060;&emsp;<span class="text-muted">Add-Ons Support</span></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-3 moct-align-center individual-container">
						<div class="moc-licensing-plan card-body">
							<div class="moc-licensing-plan-header">
								<div class="moc-licensing-plan-price"><strong class="mo_strong">PREMIUM</strong></div>
								<hr>
								<div class="moc-licensing-plan-name">Protect site with SSO login<br>+<br>Email Domains restriction<br><br></div>
								<?php echo esc_html( MO_OAUTH_CLIENT_DISCOUNT_URL ); ?>
								<script>
								createSelectOptions('pricing_premium');
								</script>
							</div>
							<button class="btn btn-block mo-oauth-btn-block-black btn-info text-uppercase moc-lp-buy-btn" onclick="upgradeform('wp_oauth_client_premium_plan')" ><?php esc_html_e( 'Buy now', 'miniorange-login-with-eve-online-google-facebook' ); ?></button>
							<div class="moc-licensing-plan-feature-list">
								<ul>
									<li>&#9989;&emsp;1 OAuth / OpenID Connect provider <br>Support</li>
									<li>&#9989;&emsp;Auto Create Users (Unlimited Users)</li>
									<li>&#9989;&emsp;Account Linking</li>
									<li>&#9989;&emsp;Advanced + Custom Attribute Mapping</li>
									<li>&#9989;&emsp;Login Widget, Shortcode and Login Link</li>
									<li>&#9989;&emsp;Authorization Code Grant, Password Grant, Implicit Grant, Refresh token Grant<br><br></li>
									<li>&#9989;&emsp;Login Button Customization</li>
									<li>&#9989;&emsp;Custom Redirect URL after login and logout</li>
									<li>&#9989;&emsp;Advanced Role + Group Mapping</li>
									<li>&#9989;&emsp;JWT Support</li>
									<li>&#9989;&emsp;Protect complete site</li>
									<li>&#9989;&emsp;Domain specific registration</li>
									<!-- <li>&#9989;&emsp;Multi-site Support*</li> -->
									<li>&#10060;&emsp;<span class="text-muted">Hide & Disable WP Login</span></li>
									<li>&#10060;&emsp;<span class="text-muted">Front-Channel & Back-Channel Single Logout (SLO) Support</span></li>
									<li>&#10060;&emsp;<span class="text-muted">WP hooks to read token, login event and extend plugin functionality</span></li>
									<li>&#10060;&emsp;<span class="text-muted">End User Login Reports / Analytics</span></li>
									<li>&#10060;&emsp;<span class="text-muted">Add-Ons Support</span></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-3 moct-align-center individual-container">
						<div class="moc-licensing-plan card-body ">
							<div class="moc-licensing-plan-header">
								<div class="moc-licensing-plan-price"><strong class="mo_strong">ENTERPRISE</strong></div>
								<hr>
								<div class="moc-licensing-plan-name">Multiple providers support<br>+<br>Developer Hooks<br><br></div>
								<?php echo esc_html( MO_OAUTH_CLIENT_DISCOUNT_URL ); ?>
								<script>
								createSelectOptions('pricing_enterprise');
								</script>
							</div>
							<button class="btn btn-block mo-oauth-btn-block btn-info text-uppercase moc-lp-buy-btn" onclick="upgradeform('wp_oauth_client_enterprise_plan')"><?php esc_html_e( 'Buy now', 'miniorange-login-with-eve-online-google-facebook' ); ?></button>
							<div class="moc-licensing-plan-feature-list">
								<ul>
									<li>&#9989;&emsp;Unlimited OAuth / OpenID Connect <br>provider Support</li>
									<li>&#9989;&emsp;Auto Create Users (Unlimited Users)</li>
									<li>&#9989;&emsp;Account Linking</li>
									<li>&#9989;&emsp;Advanced + Custom Attribute Mapping</li>
									<li>&#9989;&emsp;Login Widget, Shortcode and Login Link</li>
									<li>&#9989;&emsp;Authorization Code Grant, Password Grant, Client Credentials Grant, Implicit Grant, Refresh token Grant</li>
									<li>&#9989;&emsp;Login Button Customization</li>
									<li>&#9989;&emsp;Custom Redirect URL after login and logout</li>
									<li>&#9989;&emsp;Advanced Role + Group Mapping</li>
									<li>&#9989;&emsp;JWT Support</li>
									<li>&#9989;&emsp;Protect complete site</li>
									<li>&#9989;&emsp;Domain specific registration</li>
									<li>&#9989;&emsp;Hide & Disable WP Login</li>
									<!-- <li>&#9989;&emsp;Multi-site Support*</li> -->
									<li>&#9989;&emsp;Front-Channel & Back-Channel Single Logout (SLO) Support</li>
									<li>&#9989;&emsp;WP hooks to read token, login event and extend plugin functionality</li>
									<li>&#9989;&emsp;End User Login Reports / Analytics</li>
									<li>&#10060;&emsp;<span class="text-muted">Add-Ons Support</span></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-3 moct-align-center individual-container">
						<div class="moc-licensing-plan card-body">
							<div class="moc-licensing-plan-header">
								<div class="moc-licensing-plan-price"><strong class="mo_strong">ALL-INCLUSIVE</strong></div>
								<hr>
								<div class="moc-licensing-plan-name">All Enterprise Features<br>+<br>Add-Ons*
								<div class="mo-oauth-licencing-tooltip"> <a href="admin.php?page=mo_oauth_settings&tab=addons"><img class="mo-oauth-tooltip-licence-img" src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) . '/img/mo_oauth_info-icon.png' ); ?>" alt=""/></a>
									<span class="mo-oauth-licencing-tooltiptext">
										<ul style="padding: 0px !important;">
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/wordpress-user-provisioning">1. SCIM User Provisioning</a></a></li>
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/wordpress-page-restriction">2. Page and Post Restriction</a></li>
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/wordpress-buddypress-integrator">3. Buddypress Integrator</a></li>
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/idp-login-form-plugin-for-sso">4. Login Form Add-On</a></li>                                         
										<li>5. Discord to WordPress Role Mapping Add-on</li>
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/wordpress-learndash-integrator">6. LearnDash Integration Add-On</a></li>
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/wordpress-media-restriction">7. Media Restriction Add-On</a></li>
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/wordpress-attribute-based-redirection-restriction">8. Attribute based Redirection</a></li>                                         
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/sso-session-management">9. SSO Session Management</a></li>
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/wordpress-attribute-based-redirection-restriction">10. Membership Level Based Login Redirection</a></li>
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/wordpress-sso-login-audit">11. SSO Login Audit</a></li>
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/wordpress-memberpress-integrator">12. MemberPress Integration</a></li>
										</ul>
									</span><br/><br>
								</div><br></div>
								<?php echo esc_html( MO_OAUTH_CLIENT_DISCOUNT_URL ); ?>
								<script>
								createSelectOptions('pricing_all_inclusive');
								</script>
							</div>
							<button class="btn btn-block mo-oauth-btn-block-black btn-info text-uppercase moc-lp-buy-btn" onclick="upgradeform('wp_oauth_client_all_inclusive_single_site_plan')" ><?php esc_html_e( 'Buy now', 'miniorange-login-with-eve-online-google-facebook' ); ?></button>
							<div class="moc-licensing-plan-feature-list">
								<ul>
									<li>&#9989;&emsp;All Enterprise Plan Features</li>
									<li>&#9989;&emsp;Add-Ons Support are as below:</li>
										<ul style="list-style-position: inside";>
											<li type="square">Real Time User Provisioning from IDP to WordPress - SCIM</li>
											<li type="square">Page Restriction</li>
											<li type="square">BuddyPress Attribute Mapping</li>
											<li type="square">Login Form Add-On</li>
											<li type="square">Discord Role Mapping</li>
											<li type="square">LearnDash Attribute Integration Add-On</li>
											<li type="square">Media Restriction Add-On (Premium Plan)</li>
											<li type="square">Attribute based Redirection</li>
											<li type="square">SSO Session Management</li>
											<li type="square">Membership Level Based Login Redirection</li>
											<li type="square">SSO Login Audit</li>
											<li type="square">Regex Role Mapping Add-on</li>
											<li><span></span></li>
											<li><span></span>&nbsp;</li>
											<li><span></span></li>
											<li><span></span>&nbsp;&nbsp;&nbsp;<br></li>
											<br>
											<br>
										</ul>
									<!-- </li> -->
								</ul>
							</div>
						</div>
						<br>
					</div>
				</div>
			</div>
			<div id="multisite-network-section" style="display: none;">
				<div class="row justify-content-center mx-15">
					<div class="col-3 moct-align-center individual-container">
						<div class="moc-licensing-plan card-body">
							<div class="moc-licensing-plan-header">
								<div class="moc-licensing-plan-price"><strong class="mo_strong">PREMIUM</strong></div>
								<hr>
								<div class="moc-licensing-plan-name">Protect site with SSO login<br>+<br>Email Domains restriction<br><br></div>
								<?php echo esc_html( MO_OAUTH_CLIENT_DISCOUNT_URL ); ?>
								<script>
								createSelectWithSubsitesOptions('mul_pricing_premium');
								</script>
							</div>
							<button class="btn btn-block mo-oauth-btn-block btn-info text-uppercase moc-lp-buy-btn" onclick="upgradeform('wp_oauth_client_multisite_premium_plan')">Buy now</button>
							<div class="moc-licensing-plan-feature-list">
								<ul>
									<li>&#9989;&emsp;1 OAuth / OpenID Connect provider <br>Support</li>
									<li>&#9989;&emsp;Auto Create Users (Unlimited Users)</li>
									<li>&#9989;&emsp;Account Linking</li>
									<li>&#9989;&emsp;Advanced + Custom Attribute Mapping</li>
									<li>&#9989;&emsp;Login Widget, Shortcode and Login Link</li>
									<li>&#9989;&emsp;Authorization Code Grant, Password Grant, Implicit Grant, Refresh token Grant<br>&nbsp;<br></li>
									<li>&#9989;&emsp;Login Button Customization</li>
									<li>&#9989;&emsp;Custom Redirect URL after login and logout</li>
									<li>&#9989;&emsp;Advanced Role + Group Mapping</li>
									<li>&#9989;&emsp;JWT Support</li>
									<li>&#9989;&emsp;Protect complete site</li>
									<li>&#9989;&emsp;Domain specific registration</li>
									<li>&#9989;&emsp;Multi-site Support*</li>
									<li>&#10060;&emsp;<span class="text-muted">Hide & Disable WP Login</span></li>
									<li>&#10060;&emsp;<span class="text-muted">Front-Channel & Back-Channel Single Logout (SLO) Support</span></li>
									<li>&#10060;&emsp;<span class="text-muted">WP hooks to read token, login event and extend plugin functionality</span></li>
									<li>&#10060;&emsp;<span class="text-muted">End User Login Reports / Analytics</span></li>
									<li>&#10060;&emsp;<span class="text-muted">Add-Ons Support</span></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-3 moct-align-center individual-container">
						<div class="moc-licensing-plan card-body">
							<div class="moc-licensing-plan-header">
								<div class="moc-licensing-plan-price"><strong class="mo_strong">ENTERPRISE</strong></div>
								<hr>
								<div class="moc-licensing-plan-name">Multiple providers support<br>+<br>Developer Hooks<br><br></div>
								<?php echo esc_html( MO_OAUTH_CLIENT_DISCOUNT_URL ); ?>
								<script>
								createSelectWithSubsitesOptions('mul_pricing_enterprise');
								</script>
							</div>
							<button class="btn btn-block mo-oauth-btn-block-black btn-info text-uppercase moc-lp-buy-btn" onclick="upgradeform('wp_oauth_client_multisite_enterprise_plan')" >Buy now</button>
							<div class="moc-licensing-plan-feature-list">
								<ul>
									<li>&#9989;&emsp;Unlimited OAuth / OpenID Connect <br>provider Support</li>
									<li>&#9989;&emsp;Auto Create Users (Unlimited Users)</li>
									<li>&#9989;&emsp;Account Linking</li>
									<li>&#9989;&emsp;Advanced + Custom Attribute Mapping</li>
									<li>&#9989;&emsp;Login Widget, Shortcode and Login Link</li>
									<li>&#9989;&emsp;Authorization Code Grant, Password Grant, Client Credentials Grant, Implicit Grant, Refresh token Grant</li>
									<li>&#9989;&emsp;Login Button Customization</li>
									<li>&#9989;&emsp;Custom Redirect URL after login and logout</li>
									<li>&#9989;&emsp;Advanced Role + Group Mapping</li>
									<li>&#9989;&emsp;JWT Support</li>
									<li>&#9989;&emsp;Protect complete site</li>
									<li>&#9989;&emsp;Domain specific registration</li>
									<li>&#9989;&emsp;Multi-site Support*</li>
									<li>&#9989;&emsp;Hide & Disable WP Login</li>
									<li>&#9989;&emsp;Front-Channel & Back-Channel Single Logout (SLO) Support</li>
									<li>&#9989;&emsp;WP hooks to read token, login event and extend plugin functionality</li>
									<li>&#9989;&emsp;End User Login Reports / Analytics</li>
									<li>&#10060;&emsp;<span class="text-muted">Add-Ons Support</span></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-3 moct-align-center individual-container">
						<div class="moc-licensing-plan card-body">
							<div class="moc-licensing-plan-header">
								<div class="moc-licensing-plan-price"><strong class="mo_strong">ALL-INCLUSIVE</strong></div>
								<hr>
								<div class="moc-licensing-plan-name">All Enterprise Features<br>+<br>Add-Ons*
								<div class="mo-oauth-licencing-tooltip"> <a href="admin.php?page=mo_oauth_settings&tab=addons"><img class="mo-oauth-tooltip-licence-img" src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) . '/img/mo_oauth_info-icon.png' ); ?>" alt=""/></a>
									<span class="mo-oauth-licencing-tooltiptext">
										<ul style="padding: 0px !important;">
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/wordpress-user-provisioning">1. SCIM User Provisioning</a></a></li>
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/wordpress-page-restriction">2. Page and Post Restriction</a></li>
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/wordpress-buddypress-integrator">3. Buddypress Integrator</a></li>
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/idp-login-form-plugin-for-sso">4. Login Form Add-On</a></li>                                         
										<li>5. Discord to WordPress Role Mapping Add-on</li>
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/wordpress-learndash-integrator">6. LearnDash Integration Add-On</a></li>
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/wordpress-media-restriction">7. Media Restriction Add-On</a></li>
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/wordpress-attribute-based-redirection-restriction">8. Attribute based Redirection</a></li>                                         
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/sso-session-management">9. SSO Session Management</a></li>
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/wordpress-attribute-based-redirection-restriction">10. Membership Level Based Login Redirection</a></li>
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/wordpress-sso-login-audit">11. SSO Login Audit</a></li>
										<li><a class="mo-oauth-licencing-no-style-link" target="_blank" href="https://plugins.miniorange.com/wordpress-memberpress-integrator">12. MemberPress Integration</a></li>
										</ul>
									</span><br/><br>
								</div><br></div>
								<?php echo esc_html( MO_OAUTH_CLIENT_DISCOUNT_URL ); ?>
								<script>
								createSelectWithSubsitesOptions('mul_pricing_all_inclusive');
								</script>
							</div>
							<button class="btn btn-block mo-oauth-btn-block btn-info text-uppercase moc-lp-buy-btn" onclick="upgradeform('wp_oauth_client_all_inclusive_multisite_plan')">Buy now</button>
							<div class="moc-licensing-plan-feature-list ">
								<ul>
									<li>&#9989;&emsp;All Enterprise Plan Features</li>
									<li>&#9989;&emsp;Add-Ons Support are as below:</li>
										<ul style="list-style-position: inside";>
											<li type="square">Real Time User Provisioning from IDP to WordPress - SCIM</li>
											<li type="square">Page Restriction</li>
											<li type="square">BuddyPress Attribute Mapping</li>
											<li type="square">Login Form Add-On</li>
											<li type="square">Discord Role Mapping</li>
											<li type="square">LearnDash Attribute Integration Add-On</li>
											<li type="square">Media Restriction Add-On (Premium Plan)</li>
											<li type="square">Attribute based Redirection</li>
											<li type="square">SSO Session Management</li>
											<li type="square">Membership Level Based Login Redirection</li>
											<li type="square">SSO Login Audit</li>
											<li type="square">Regex Role Mapping Add-on</li>
											<li><span></span></li>
											<li><span></span>&nbsp;</li>
											<li><span></span>&nbsp;</li>

											<li><span></span>&nbsp;&nbsp;&nbsp;<br></li>
											<br>
											<br>
											<br>
										</ul>
									<!-- </li> -->
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			</div> 
		</div>
	</div>
			<div class="moc-licensing-notice" style="height: 400px; padding-top: 10px;" id="upgrade-steps">
			<div class="PricingCard-toggle oauth-plan-title mul-dir-heading "  onmouseenter="onMouseEnter('upgrade-section', '3px solid #093553')" onmouseleave="onMouseEnter('upgrade-section', 'none')" style="padding-top: 1px;">
						<h2 class="mo-oauth-h2">HOW TO UPGRADE TO PREMIUM</h2>
						<!-- <hr style="background-color:#17a2b8; width: 20%;height: 3px;border-width: 3px;"> -->
					</div> 
			<section class="section-steps"  id="section-steps" onmouseenter="onMouseEnter('upgrade-section', '3px solid #093553')" onmouseleave="onMouseEnter('upgrade-section', 'none')">
					<div class="row">
							<div class="col span-1-of-2 steps-box">
								<div class="works-step">
									<div><b>1</b></div>
									<p>
										Click on <b><i>Buy Now</i></b> button for required premium plan and you will be redirected to miniOrange login console.
									</p>
								</div>
								<div class="works-step">
									<div><b>2</b></div>
									<p>
										Enter your miniOrange account credentials. You can create one for free <i><b><a href="admin.php?page=mo_oauth_settings&tab=account">here</a></b></i> if you don't have. Once you have successfuly logged in, you will be redirected towards the payment page. 
									</p>
								</div>
								<div class="works-step">
									<div><b>3</b></div>
									<p>
										Enter your card details and proceed for payment. On successful payment completion, the premium plugin will be available to download. 
									</p>
								</div>
								</div>
								<div class="col span-1-of-2 steps-box">
								<div class="works-step">
									<div><b>4</b></div>
									<p>
										You can download the premium plugin from the <b><i>Releases and Downloads</i></b> section on the miniOrange console.
									</p>
								</div>
								<div class="works-step">
									<div><b>5</b></div>
									<p>
										From the WordPress admin dashboard, deactivate the free plugin currently installed.
									</p>
								</div>
								<div class="works-step">
									<br>
									<div><b>6</b></div>
									<p style="padding-top:10px;">
										Now install the downloaded premium plugin and activate it.
										After activating the premium plugin, login using the account which you have used for the purchase of premium license.<br> <br>
									</p>
								</div>
							</div>
						</div> 
						</section>
						</div> 

						<div class="moc-licensing-notice" style="height: 10%px; padding-top: 10px;" >

							<div class="PricingCard-toggle ">
				<h2 class="mo-oauth-h2"> INSTANCE - SUBSITES DEFINITION</h2>
			</div>
			<!-- <hr style="background-color:#17a2b8; width: 20%;height: 3px;border-width: 3px;"> -->
						<br>
						<div class="instance-subsites">
				<div class="row">
					<div class="col span-1-of-2 instance-box">
						<h3 class="myH3">What is an instance?</h3><br>
						<br><p style="font-size: 1em;">A WordPress instance refers to a single installation of a WordPress site. It refers to each individual website where the plugin is active. In the case of a single site WordPress, each website will be counted as a single instance.
						<br>
						<br> For example, You have 3 sites hosted like one each for development, staging, and production. This will be counted as 3 instances.</p>
					</div>
					<div class="col span-1-of-2 subsite-box">
						<h3 class="myH4">What is a multisite network?</h3><br>
						<br><p style="font-size: 1em;">A multisite network means managing multiple sites within the same WordPress installation and has the same database.
						<br>
						<br>For example, You have 1 WordPress instance/site with 3 subsites in it then it will be counted as 1 instance with 3 subsites.
						<br> You have 1 WordPress instance/site with 3 subsites and another WordPress instance/site with 2 subsites then it will be counted as 2 instances with 3 subsites.</p>
					</div>
				</div>
			</div>
		</div>
						<div class="moc-licensing-notice" id="payment-method" style="height: 10%;padding-top: 10px;min-height: 400px;" onmouseenter="onMouseEnter('payment-section', '3px solid #093553')" onmouseleave="onMouseEnter('payment-section', 'none')">
							<h2 class="mo-oauth-h2">ACCEPTED PAYMENT METHODS</h2>
						<section class="payment-methods">
						<br>
						<div class="row">
							<div class="col span-1-of-3">
								<div class="plan-box">
									<div>
										<i style="font-size:30px;" class="fa fa-cc-amex" aria-hidden="true"></i>
										<i style="font-size:30px;" class="fa fa-cc-visa" aria-hidden="true"></i>
										<i style="font-size:30px;" class="fa fa-cc-mastercard" aria-hidden="true"></i>
									</div>
									<div>
										If the payment is made through Credit Card/International Debit Card, the license will be created automatically once the payment is completed.
									</div>
								</div>
							</div>
							<div class="col span-1-of-3">
								<div class="plan-box">
									<div>
										<i style="font-size:30px;" class="fa fa-university" aria-hidden="true"><span style="font-size: 20px;font-weight:500;">&nbsp;&nbsp;Bank Transfer</span></i>
									</div>
									<div>
										If you want to use bank transfer for the payment then contact us at <b><i><span>oauthsupport@xecurify.com</span></i></b>  so that we can provide you the bank details.
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<p style="margin-top:20px;font-size:16px;">
								<span style="font-weight:500;"> Note :</span> Once you have paid through PayPal/Net Banking, please inform us so that we can confirm and update your license.
							</p>
						</div>
					</section>
				</div>
					<!-- Licensing Plans End -->
					<div class="moc-licensing-notice" style="min-height:450px;  margin-bottom: 3%;">
						<h2 id="moc_licensing_policy" class="mo-oauth-h2">LICENSING POLICY</h2>
						<br>
						<p style="font-size: 1em;"><span style="color: red;">*</span>The WordPress SSO plugin licenses are subscription-based, and each license includes 12 months of maintenance, which covers version updates.<br></p>

						<p style="font-size: 1em;"><span style="color: red;">*</span>We provide deep discounts on bulk license purchases and pre-production environment licenses. As the no. of licenses increases, the discount percentage also increases. Contact us at <i><a href="mailto:oauthsupport@xecurify.com">oauthsupport@xecurify.com</a></i> for more information.</p>

						<p style="font-size: 1em;"><span style="color: red;">*</span><strong class="mo_strong">MultiSite Network Support : </strong>
							There is an additional cost for the number of subsites in Multisite Network. The Multisite licenses are based on the <b>total number of subsites</b> in your WordPress Network.
							<br>
							<br>
							<strong class="mo_strong">Note</strong> : miniOrange does not store or transfer any data which is coming from the OAuth Provider to the WordPress. All the data remains within your premises/server. We do not provide the developer license for our paid plugins and the source code is protected. It is strictly prohibited to make any changes in the code without having written permission from miniOrange. There are hooks provided in the plugin which can be used by the developers to extend the plugin's functionality.
							<br>
							<br>
						At miniOrange, we want to ensure you are 100% happy with your purchase. For more details on our plugin licensing terms and refund policy, you can check out our<i><a href="https://plugins.miniorange.com/end-user-license-agreement" target="_blank"> End User License Agreement.</a></i> Please email us at <i><a href="mailto:info@xecurify.com" target="_blank">info@xecurify.com</a></i> for any queries regarding the return policy.</p>
					</div>
				</div>
			</div>
		</div>

		<div class="support-icon">

		<div class="service-btn" id="service-btn">
			<div class="service-icon">
				<img src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . 'img/mail.png'; ?>" class="service-img" alt="support"style="width: 50px;height: 50px; margin-top: 5px;">
			</div>
		</div>
	</div>

	<div class="support-form-container">
		<span class="container-rel"></span>
		<div class="widget-header" >
		<div class="widget-header-text"><b>Contact miniOrange Support</b></div>
		<div class="widget-header-close-icon">
			<button type="button" class="dashicons dashicons-dismiss mo-oauth-notice-dismiss" id="mo_oauth_help_close_form"></button>
		</div>
	</div>
	<div class="loading-inner" style="overflow:hidden;">
		<div class="loading-icon">
			<div class="loading-icon-inner">
				<span class="icon-box">
					<img class="icon-image" src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . 'img/success.png'; ?>" alt="success">
				</span>
				<p class="loading-icon-text">
					<p>Thanks for your inquiry.<br><br>If you dont hear from us within 24 hours, please feel free to send a follow up email to <a href="mailto:<?php echo 'oauthsupport@xecurify.com'; ?>"><?php echo 'oauthsupport@xecurify.com'; ?></a></p>
				</p>
			</div>
		</div>
	</div>
	<div class="loading-inner-2" style="overflow:hidden;">
		<div class="loading-icon-2">
			<div class="loading-icon-inner-2">
			<br>
			<span class="icon-box-2">
				<img class="icon-image-2" src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ) . 'img/error.png'; ?>" alt="error" >
			</span>
			<p class="loading-icon-text-2">
				<p>Unable to connect to Internet.<br>Please try again.</p>
			</p>
			</div>
		</div>
	</div>
	<div class="loading-inner-3" style="overflow:hidden;">
		<div class="loading-icon-3">
			<p class="loading-icon-text-3">
				<p style="font-size:18px;">Please Wait...</p>
			</p>
			<div class="loader"></div>
		</div>
	</div>
	<form role="form" action="" id="support-form" method="post" class="support-form top-label">
		<?php wp_nonce_field( 'mo_oauth_support_form', 'mo_oauth_support_form_field' ); ?>
			<div class="field-group">
				<label class="field-group-label" for="email">
					<span class="label-name">Your Contact E-mail</span>
				</label>
				<input type="email" class="field-label-text" style="background-color: #f1f1f1;" name="email" id="person_email" dir="auto"  required="true"  title="Enter a valid email address." placeholder="Enter valid email">
			</div>
			<div class="field-group">
				<label class="field-group-label">
					<span class="label-name">What are you looking for</span>
				</label >
				<select class="what_you_looking_for" style="background-color: #f1f1f1; max-width:26.5rem;">
						<option class="Select-placeholder" value="" disabled>Select Category</option>
						<option value="Plugin Pricing">I want to discuss about Plugin Pricing</option>
						<option value="Schedule a Demo">I want to schedule a Demo</option>
						<option value="Custom Requirement">I have custom requirement</option>
						<option value="Others">My reason is not listed here </option>
				</select>
			</div>
			<div class="field-group">
				<label class="field-group-label" for="description">
					<span class="label-name">How can we help you?</span>
				</label>
				<textarea rows="5" id="person_query" name="description" dir="auto" required="true" class="field-label-textarea" placeholder="You will get reply via email"></textarea>
			</div>
			<div class="submit_button">
				<button id="" type="submit" class="button1 button_new_color button__appearance-primary submit-button" value="Submit" aria-disabled="false">Submit</button>
			</div>
		</form>
	</div>
	<script>
		jQuery("#mo_oauth_help_close_form").click(function(){
			jQuery(".support-form-container").css('display','none');
		});
	</script>
	<script>
		jQuery(".help-container").click(function(){
			jQuery(".support-form-container").css('display','block');
			//jQuery(".help-container").css('display','none');
		});

		jQuery(".service-img").click(function(){
			jQuery('input[type="text"], textarea').val('');
			jQuery('select').val('');
			jQuery(".support-form-container").css('display','block');
			jQuery(".support-form").css('display','block');
			jQuery(".loading-inner").css('display','none');
			jQuery(".loading-inner-2").css('display','none');
			jQuery(".loading-inner-3").css('display','none');
			//jQuery(".help-container").css('display','none');
		});
	</script>

	<script>
	jQuery('.support-form').submit(function(e){

		e.preventDefault();
		var email = jQuery('#person_email').val();
		var query = jQuery('#person_query').val();
		var look= jQuery('.what_you_looking_for').val();
		var fname = "<?php echo esc_attr( ( wp_get_current_user()->user_firstname ) ); ?>";
		var lname = "<?php echo esc_attr( ( wp_get_current_user()->user_lastname ) ); ?>";
		if(look == '' || look == null){
			look = 'empty';
		}
		query1= '<b>['+look+']</b> <br><b>WP OAuth Client SSO Plugin Licensing Question: </b>'+query+' <br> ';
		if(email == "" || query == "" || query1 == ""){

			jQuery('#login-error').show();
			jQuery('#errorAlert').show();

		}
		else{
			jQuery('input[type="text"], textarea').val('');
			jQuery('select').val('Select Category');
			jQuery(".support-form").css('display','none');
			jQuery(".loading-inner-3").css('display','block');
			var json = new Object();

			json = {
				"email" : email,
				"query" : query1,
				"ccEmail" : "oauthsupport@xecurify.com",
				"company" : "<?php echo ! empty( $_SERVER ['SERVER_NAME'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_SERVER ['SERVER_NAME'] ) ) ) : ''; ?>", 
				"firstName" : fname,
				"lastName" : lname,
			}
			var jsonString = JSON.stringify(json);
			jQuery.ajax({
				url: "https://login.xecurify.com/moas/rest/customer/contact-us",
				type : "POST",
				data : jsonString,
				crossDomain: true,
				dataType : "text",
				contentType : "application/json; charset=utf-8",
				success: function (data, textStatus, xhr) { successFunction();},
				error: function (jqXHR, textStatus, errorThrown) { errorFunction(); }
			});
		}
	});

	function successFunction(){
		jQuery(".loading-inner-3").css('display','none');
		jQuery(".loading-inner").css('display','block');
	}

	function errorFunction(){
		jQuery(".loading-inner-3").css('display','none');
		jQuery(".loading-inner-2").css('display','block');
	}
	</script>
		<!-- End Licensing Table -->
		<a  id="mobacktoaccountsetup" style="display:none;" href="<?php echo ! empty( $_SERVER['REQUEST_URI'] ) ? esc_attr( add_query_arg( array( 'tab' => 'account' ), sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ) : ''; ?>">Back</a>
		<!-- JSForms Controllers -->
		<script>
			jQuery("input[name=sitetype]:radio").change(function() {

				if (this.value == 'multisite') {
					jQuery('#single-site-section').css('display','none');
					jQuery('#multisite-network-section').css('display','block');

				}
				else {
					jQuery('#single-site-section').css('display','block');
					jQuery('#multisite-network-section').css('display','none');

				}
			});
			function upgradeform(planType) {
				if(planType === "") {
					location.href = "https://wordpress.org/plugins/miniorange-login-with-eve-online-google-facebook/";
					return;
				} else {
					const url = `https://portal.miniorange.com/initializepayment?requestOrigin=${planType}`;
					window.open(url, "_blank");
				}

			}
			function getlicensekeys() {
				// if(jQuery('#mo_customer_registered').val()==1)
				jQuery('#viewlicensekeys').submit();
			}
			function onMouseEnter(divid, css){
				document.getElementById(divid).style.borderBottom = css;
			}
		</script>
		<!-- End JSForms Controllers -->
		<?php
	}
}
