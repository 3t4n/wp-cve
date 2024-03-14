<?php
/**
 * Firebase Authentication Licensing plans page
 *
 * @package firebase-authentication
 */

/**
 * Displays the licensing plans in admin area
 */
class Mo_Firebase_Authentication_Admin_Licensing_Plans {

	/**
	 * Function to display licensing plans
	 *
	 * @return void
	 */
	public static function mo_firebase_authentication_licensing_plans() {
		?>
			<?php
			$mo_account_setup_page_url = get_admin_url() . 'admin.php?page=mo_firebase_authentication&tab=account';
			?>

			<!-- Important JSForms -->
			<a id="mobacktoaccountsetup" href="<?php echo esc_url( $mo_account_setup_page_url ); ?>"></a>
			<input type="hidden" value="<?php echo esc_attr( mo_firebase_authentication_is_customer_registered() ); ?>" id="mo_customer_registered">

			<form style="display:none;" id="viewlicensekeys" action="<?php echo esc_url( get_option( 'mo_fb_host_name' ) . '/moas/login' ); ?>" target="_blank" method="post">
				<input type="email" name="username" value="<?php echo esc_attr( get_option( 'mo_firebase_authentication_admin_email' ) ); ?>"/>
				<input type="text" name="redirectUrl" value="<?php echo esc_url( get_option( 'mo_fb_host_name' ) . '/moas/viewlicensekeys' ); ?>"/>
			</form>
			<!-- End Important JSForms -->
			<br/>
			<h2 style = 'text-align :center'>Our Pricing Plans</h2>
			<br/>
			<div style="    width: 86%;margin: 0 auto;">
				<div class="row">
					<div class="col-4 moct-align-center">
						<div class="moc-licensing-plan card-body" style="background-color: #ffffff;">
							<div class="moc-licensing-plan-header">
								<div class="moc-licensing-plan-name">Premium</div>
							</div><br>
							<div class="moc-licensing-plan-price"><sup>$</sup>149<sup>*</sup></div>
							<h3><a href="https://plugins.miniorange.com/firebase-woocommerce-integration" target="_blank" style="text-decoration: none;">[WooCommerce Integration]</a></h3>
							<!-- <a class="btn btn-block btn-info text-uppercase moc-lp-buy-btn" href="mailto:info@xecurify.com" target="_blank">Contact Us</a> -->
							<button class="btn btn-block  text-uppercase moc-lp-buy-btn" onclick="upgradeform('wp_oauth_firebase_authentication_premium_plan')">Buy Now</button>
							<br>
							<div class="moc-licensing-plan-feature-list">
								<ul>
									<li>Allow login with Firebase and WordPress <a href='https://plugins.miniorange.com/firebase-premium-and-enterprise-plugin-features#step1' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li>Sync Firebase UID to WordPress <a href='https://plugins.miniorange.com/firebase-premium-and-enterprise-plugin-features#step2' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li>Basic Role Mapping (Set Default Role) <a href='https://plugins.miniorange.com/firebase-premium-and-enterprise-plugin-features#step2' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li>Auto register users in Firebase as well as WordPress <a href='https://plugins.miniorange.com/firebase-premium-and-enterprise-plugin-features#step3' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li>Login & Registration Form Integration (WooCommerce, BuddyPress) <a href='https://plugins.miniorange.com/firebase-premium-and-enterprise-plugin-features#step4' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li>Custom redirect URL after Login and Logout <a href='https://plugins.miniorange.com/firebase-premium-and-enterprise-plugin-features#step5' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li>WP function to retrieve  Firebase User JWT  <a href='https://developers.miniorange.com/docs/wordpress-firebase/firebase-id-token' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li>​Hide the change password field on WordPress/WooCommerce account page.​ <a href='https://developers.miniorange.com/docs/wordpress-firebase/hide-password-field-firebase' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>	
									<li><br/></li>
								</ul>
							</div>
						</div>
					</div>
					<!-- <div class="col-1 moct-align-center"></div> -->
					<div class="col-4 moct-align-center">
						<div class="moc-licensing-plan card-body" style="background-color: #ffffff;">
							<div class="moc-licensing-plan-header">
								<div class="moc-licensing-plan-name">Enterprise</div>
							</div><br>
							<div class="moc-licensing-plan-price"><sup>$</sup>249<sup>*</sup></div>
							<h3><a href="https://plugins.miniorange.com/firebase-social-login-integration-for-wordpress" target="_blank" style="text-decoration: none;">[Firebase Social Login]</a></h3>
							<!-- <a class="btn btn-block btn-purple text-uppercase moc-lp-buy-btn" href="mailto:info@xecurify.com" target="_blank">Contact Us</a> -->
							<button class="btn btn-block buy_button text-uppercase moc-lp-buy-btn" onclick="upgradeform('wp_oauth_firebase_authentication_enterprise_plan')" style="cursor:inherit">Buy Now</button>

							<br>
							<div class="moc-licensing-plan-feature-list">
								<ul>
									<li style = font-size:15px> <b>All premium plan features </b></li>
									<li>WP hooks to read Firebase token, login event and extend plugin functionality <a href='https://plugins.miniorange.com/firebase-premium-and-enterprise-plugin-features#step6' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li>Shortcodes to add Firebase Login and Registration Form <a href='https://plugins.miniorange.com/firebase-premium-and-enterprise-plugin-features#step8' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li>Firebase Authentication methods <br>Google, Facebook, Github, Twitter, Microsoft, Yahoo, Apple <a href='https://plugins.miniorange.com/firebase-premium-and-enterprise-plugin-features#step9' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li>Shortcode to add Firebase Social Login buttons <a href='https://plugins.miniorange.com/firebase-premium-and-enterprise-plugin-features#step10' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li>WP function to retrieve Firebase User JWT, and integrate your Custom/Third party Login and Registration forms. <a href='https://developers.miniorange.com/docs/wordpress-firebase/firebase-id-token' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li>*Phone Authentication (Passwordless login using Firebase OTP verification) <a href='https://plugins.miniorange.com/firebase-premium-and-enterprise-plugin-features#step11' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									</ul>
							</div>
						</div>
					</div>
					<!-- <div class="col-1 moct-align-center"></div> -->
					<div class="col-4 moct-align-center">
						<div class="moc-licensing-plan card-body" style="background-color: #ffffff;">
							<div class="moc-licensing-plan-header">
								<div class="moc-licensing-plan-name">All-Inclusive</div>
							</div><br>
							<div class="moc-licensing-plan-price"><sup>$</sup>449<sup>*</sup></div>
							<h3><a href="https://plugins.miniorange.com/woocommerce-cloud-firestore-integration" target="_blank" style="text-decoration: none;">[Cloud Firestore Integration]</a></h3>
							<a class="btn btn-block text-uppercase moc-lp-buy-btn" href="mailto:oauthsupport@xecurify.com" target="_blank">Contact Us</a>
							<br>
							<div class="moc-licensing-plan-feature-list">
								<ul>
									<li style = font-size:15px> <b>All enterprise plan features </b></li>
									<li>Display Firestore Collection Data <a href='https://plugins.miniorange.com/woocommerce-cloud-firestore-integration#stepe' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li>Shortcodes to Dynamic Display Firestore Data <a href='https://plugins.miniorange.com/woocommerce-cloud-firestore-integration#stepf' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li>Sync WordPress User Advanced profile to Firestore <a href='https://plugins.miniorange.com/woocommerce-cloud-firestore-integration#stepg' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li>Sync WooCommerce prodcut Data to Firestore Collection <a href='https://plugins.miniorange.com/woocommerce-cloud-firestore-integration#stepb' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li>Sync WooCommerce order to Firestore Collection <a href='https://plugins.miniorange.com/woocommerce-cloud-firestore-integration#stepc' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li>Sync WooCommerce subscription prodcut Data to Firestore Collection <a href='https://plugins.miniorange.com/woocommerce-cloud-firestore-integration#step3' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li>Sync WooCommerce subscription order Data to Firestore Collection <a href='https://plugins.miniorange.com/woocommerce-cloud-firestore-integration#step4' target="_blank"> <i class="fa fa-external-link mo_external_link"></i></a></li>
									<li></li>
								</ul>
							</div>
						</div>
					</div>
					<br/><br/>
					<div style="margin: 0 20px;"><b style="color: red">*</b><strong>Cost applicable for one instance only. Licenses are perpetual and the Support Plan includes 12 months of maintenance (support and version updates). You can renew maintenance after 12 months at 50% of the current license cost.</strong></div>
					<div style="margin: 20px;"><p>miniOrange does not store or transfer any data which is coming from Firebase to the WordPress. All the data remains within your premises / server. We do not provide the developer license for our paid plugins and the source code is protected. It is strictly prohibited to make any changes in the code without having written permission from miniOrange. There are hooks provided in the plugin which can be used by the developers to extend the plugin's functionality.</p></div>
					<div style="margin: 0 20px;"><p>At miniOrange, we want to ensure you are 100% happy with your purchase. If the premium plugin you purchased is not working as advertised and you've attempted to resolve any issues with our support team, which couldn't get resolved. Please check our <a href="https://plugins.miniorange.com/end-user-license-agreement" target="_blank">End User License Agreement</a> for more details. Please email us at <a href="mailto:oauthsupport@xecurify.com" target="_blank">oauthsupport@xecurify.com</a> for any queries regarding the return policy.</p></div>

				</div>
			</div><br/>
			<!-- End Licensing Table -->
			<!-- JSForms Controllers -->
			<script>
				function upgradeform(planType) {
						if(planType === "") {
							location.href = "https://wordpress.org/plugins/firebase-authentication/";
							return;
						} else {
							jQuery('#requestOrigin').val(planType);
							if(jQuery('#mo_customer_registered').val()==1){
								const url = `https://portal.miniorange.com/initializepayment?requestOrigin=${planType}`;            
								window.open(url, "_blank"); 
							}
							else{
								location.href = jQuery('#mobacktoaccountsetup').attr('href');
							}
						}

					}
			</script>
			<?php
	}
}
