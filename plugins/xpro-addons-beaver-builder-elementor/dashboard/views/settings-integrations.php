<?php
$user_data = Xpro_Beaver_Dashboard_Utils::instance()->get_option( 'xpro_beaver_user_data' );
//$pro_active  = ( in_array( 'xpro-elementor-addons-pro/xpro-elementor-addons-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) );
?>

<div class="xpro-bb-tab-content" id="bb-integrations">
	<div class="xpro-row">
		<div class="xpro-col-lg-9">
			<div class="xpro-bb-tab-content-wrapper xpro-bb-dashboard-tab-modules-content">
				<div class="xpro-bb-dashboard-tab-content-inner">
					<div class="xpro-bb-dashboard-intro">
						<div class="xpro-bb-dashboard-intro-content">
							<h2 class="xpro-bb-dashboard-title">user <span class="xpro-pink">data</span></h2>
							<button class="xpro-bb-dashboard-btn xpro-dashboard-save-button">
								<i class="dashicons dashicons-update"></i>
								<?php echo esc_html__( 'Save Changes', 'xpro-bb-addons' ); ?>
							</button>
						</div>

					</div>
					<div class="xpro-row">
						<div class="xpro-col-lg-6">
							<div class="xpro-bb-dashboard-text-form-wrapper xpro-bb-content-type-free">
								<h4 class="xpro-bb-dashboard-title xpro-bb-dashboard-widget-count-title">Contact Form</h4>
								<p class="xpro-bb-dashboard-txt">Set your default email address to receive emails from the Simple Contact Form.</p>
								<div class="xpro-dashboard-text-form-input">
									<label class="xpro-bb-dashboard-control-label" for="xpro-dashboard-modules-text-support@wpxpro.com">
										Contact Form
										<span class="xpro-bb-dashboard-control-label-switch" data-active="ON" data-inactive="OFF"></span>
									</label>
									<input type="text" class="xpro-bb-dashboard-widget-control-input" placeholder="support@wpxpro.com" name="xpro_beaver_user_data[contact_form][mail]" id="xpro-dashboard-modules-text-support@wpxpro.com" autocomplete="off" value="<?php echo ( isset( $user_data['contact_form']['mail'] ) ) ? $user_data['contact_form']['mail'] : ''; ?>">
								</div>
							</div>
						</div>
<!--						<div class="xpro-col-lg-4">-->
<!--							<div class="xpro-bb-dashboard-text-form-wrapper xpro-bb-content-type-pro --><?php //echo esc_attr( Xpro_Beaver_Dashboard_Utils::instance()->is_widget_active_class( 'google-maps', $pro_active ) ); ?><!--">-->
<!--								<h4 class="xpro-bb-dashboard-title xpro-bb-dashboard-widget-count-title">Google Maps</h4>-->
<!--								<p class="xpro-bb-dashboard-txt">Visit <a href="https://developers.google.com/" target="_blank">developers.google.com</a>, generate your API key, and insert it here.</p>-->
<!--								<div class="xpro-dashboard-text-form-input">-->
<!--									<label class="xpro-bb-dashboard-control-label" for="xpro-dashboard-modules-text-">-->
<!--										Google Map API-->
<!--										<span class="xpro-bb-dashboard-control-label-switch" data-active="ON" data-inactive="OFF"></span>-->
<!--									</label>-->
<!--									<input type="text" class="xpro-bb-dashboard-widget-control-input" placeholder="Enter Your Google Map API Here" name="xpro_beaver_user_data[google_map][api]" id="xpro-dashboard-modules-text-" autocomplete="off">-->
<!--								</div>-->
<!--							</div>-->
<!--						</div>-->
<!--						<div class="xpro-col-lg-4">-->
<!--							<div class="xpro-bb-dashboard-text-form-wrapper xpro-bb-content-type-pro label-google-map">-->
<!--								<h4 class="xpro-bb-dashboard-title xpro-bb-dashboard-widget-count-title">Street Map</h4>-->
<!--								<p class="xpro-bb-dashboard-txt">Go to <a href="https://www.mapbox.com/" target="_blank">mapbox.com</a> and generate the API key to insert it here.</p>-->
<!--								<div class="xpro-dashboard-text-form-input">-->
<!--									<label class="xpro-bb-dashboard-control-label" for="xpro-dashboard-modules-text-">-->
<!--										Street Map API-->
<!--										<span class="xpro-bb-dashboard-control-label-switch" data-active="ON" data-inactive="OFF"></span>-->
<!--									</label>-->
<!--									<input type="text" class="xpro-bb-dashboard-widget-control-input" placeholder="Enter Your Street Map API Here" name="xpro_beaver_user_data[street_map][api]" id="xpro-dashboard-modules-text-" autocomplete="off">-->
<!--								</div>-->
<!--							</div>-->
<!--						</div>-->
						<div class="xpro-col-lg-6">
							<div class="xpro-bb-dashboard-text-form-wrapper xpro-bb-content-type-free">
								<h4 class="xpro-bb-dashboard-title xpro-bb-dashboard-widget-count-title">reCaptcha Access</h4>
								<p class="xpro-bb-dashboard-txt">
									Go to your Google reCAPTCHA > Account > Generate Keys (reCAPTCHA V2 > Invisible), Copy and Paste them here.
								</p>
								<div class="xpro-dashboard-text-form-input">
									<label class="xpro-bb-dashboard-control-label" for="xpro-dashboard-modules-site-key">
										Site Key
										<span class="xpro-bb-dashboard-control-label-switch" data-active="ON" data-inactive="OFF"></span>
									</label>
									<input type="text" class="xpro-bb-dashboard-widget-control-input" placeholder="Enter Your Site Key Here" name="xpro_beaver_user_data[recaptcha][site_key]" id="xpro-dashboard-modules-site-key" autocomplete="off" value="<?php echo ( isset( $user_data['recaptcha']['site_key'] ) ) ? $user_data['recaptcha']['site_key'] : ''; ?>">
								</div>
								<div class="xpro-dashboard-text-form-input">
									<label class="xpro-bb-dashboard-control-label" for="xpro-dashboard-modules-secret-key">
										Secret Key
										<span class="xpro-bb-dashboard-control-label-switch" data-active="ON" data-inactive="OFF"></span>
									</label>
									<input type="text" class="xpro-bb-dashboard-widget-control-input" placeholder="Enter Your Secret Key Here" name="xpro_beaver_user_data[recaptcha][secret_key]" id="xpro-dashboard-modules-secret-key" autocomplete="off" value="<?php echo ( isset( $user_data['recaptcha']['secret_key'] ) ) ? $user_data['recaptcha']['secret_key'] : ''; ?>">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="xpro-col-lg-3">
			<div class="xpro-bb-dashboard-sidebar-wrapper">
				<div class="xpro-bb-dashboard-sidebar xpro-sidebar">
					<div class="xpro-bb-dashboard-widget-count-wrapper">
						<h2 class="xpro-bb-dashboard-title xpro-bb-dashboard-widget-count-title">Perfect <span class="xpro-pink">Toolkit</span> for Beaver Builder</h2>
						<p class="xpro-bb-dashboard-txt">
							Step up your design game with premium plugins & templates your competitors wish they had.
						</p>
						<ul class="xpro-bb-dashboard-widget-count-list">
							<li>
								<span class="xpro-bb-dashboard-widget-count">300+</span>
								<span class="xpro-bb-dashboard-widget-count-text">Templates</span>
							</li>
							<li>
								<span class="xpro-bb-dashboard-widget-count">50+</span>
								<span class="xpro-bb-dashboard-widget-count-text">Modules</span>
							</li>

							<li>
								<span class="xpro-bb-dashboard-widget-count">100+</span>
								<span class="xpro-bb-dashboard-widget-count-text">Full Themes</span>
							</li>
							<li>
								<span class="xpro-bb-dashboard-widget-count">500+</span>
								<span class="xpro-bb-dashboard-widget-count-text">Sections</span>
							</li>
						</ul>
					</div>
				</div>
				<div class="xpro-bb-dashboard-sidebar xpro-sidebar">
					<div class="xpro-bb-dashboard-widget-count-wrapper xpro-bb-dashboard-widget-support">
						<img src="https://bbdemos.wpxpro.com/lottie/pride-loader.gif" alt="">
						<h2 class="xpro-bb-dashboard-title xpro-bb-dashboard-widget-count-title">support <span class="xpro-pink">US</span></h2>
						<p class="xpro-bb-dashboard-txt">
							Xpro Addons makes web design and development process super easy and exceptionally fast.
						</p>
						<a href="https://beaver.wpxpro.com/contact-us/" class="xpro-bb-dashboard-btn xpro-bb-btn-document" target="_blank">Get Started</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
