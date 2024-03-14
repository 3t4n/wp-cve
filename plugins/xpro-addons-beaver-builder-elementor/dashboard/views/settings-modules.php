<?php

$modules_all    = Xpro_Beaver_Modules_List::instance()->get_list();
$modules_active = Xpro_Beaver_Dashboard_Utils::instance()->get_option( 'xpro_beaver_modules_list', array_keys( $modules_all ) );
$modules_active = ( ! isset( $modules_active[0] ) ? array_keys( $modules_active ) : $modules_active );

?>

<div class="xpro-bb-tab-content" id="bb-modules">
	<div class="xpro-row">
		<div class="xpro-col-lg-9">
			<div class="xpro-bb-tab-content-wrapper xpro-bb-dashboard-tab-modules-content">
				<div class="xpro-bb-dashboard-tab-content-inner">
					<div class="xpro-bb-dashboard-intro">
						<div class="xpro-bb-dashboard-intro-content">
							<h2 class="xpro-bb-dashboard-title">widgets</h2>
							<div class="xpro-bb-dashboard-input-switch">
								<input checked type="checkbox" value="widgets-all" class="xpro-bb-dashboard-widget-control-input" name="xpro_bb_dashboard_widget_control_input" id="xpro-bb-dashboard-widget-control-input">
								<label class="xpro-bb-dashboard-control-label" for="xpro-bb-dashboard-widget-control-input">
									<?php echo esc_html__( 'Disable All', 'xpro-bb-addons' ); ?>
									<span class="xpro-bb-dashboard-control-label-switch" data-active="ON" data-inactive="OFF"></span>
									<?php echo esc_html__( 'Enable All', 'xpro-bb-addons' ); ?>
								</label>
							</div>

							<button class="xpro-bb-dashboard-btn xpro-dashboard-save-button">
								<i class="dashicons dashicons-update"></i>
								<?php echo esc_html__( 'Save Changes', 'xpro-bb-addons' ); ?>
							</button>
						</div>

					</div>
					<div class="xpro-row">
						<?php foreach ( $modules_all as $module => $module_config ) : ?>
							<div class="xpro-col-lg-4">
								<div class="xpro-bb-dashboard-widget-item xpro-bb-dashboard-input-switch xpro-bb-content-type-<?php echo esc_attr( $module_config['package'] ); ?>">
									<input type="checkbox" <?php echo esc_attr( ( ( in_array( $module, $modules_active, true ) ) ? 'checked=checked' : '' ) ); ?> value="<?php echo esc_attr( $module ); ?>" class="xpro-bb-dashboard-widget-control-input" name="xpro_beaver_modules_list[]" id="xpro-bb-dashboard-modules-switch-<?php echo esc_attr( $module_config['slug'] ); ?>"<?php echo $module_config['package'] === 'pro-disabled' ? ' disabled' : ''; ?>>
									<label class="xpro-bb-dashboard-control-label" for="xpro-bb-dashboard-modules-switch-<?php echo esc_attr( $module_config['slug'] ); ?>">
										<?php echo esc_attr( $module_config['title'] ); ?>
										<span class="xpro-bb-dashboard-control-label-switch" data-active="ON" data-inactive="OFF"></span>
									</label>
								</div>
							</div>
						<?php endforeach; ?>
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
					<div class="xpro-bb-dashboard-modules-wrapper">
						<h4 class="xpro-bb-dashboard-title xpro-bb-dashboard-widget-count-title">Premium <span class="xpro-pink">Modules</span></h4>
						<div class="xpro-bb-dashboard-modules-carousel owl-carousel owl-carousel2">
							<div class="xpro-bb-dashboard-modules-item">
								<i class="xi xi-lottie"></i>
								<h4 class="xpro-bb-dashboard-title xpro-bb-dashboard-widget-count-title"><span class="xpro-pink">Lottie</span></h4>
								<p class="xpro-bb-dashboard-txt">Enhance your site’s engagement & user experience by adding ‘wow’ effects.</p>
								<a href="https://beaver.wpxpro.com/modules/lottie/" class="xpro-bb-dashboard-btn xpro-bb-btn-document xpro-btn-underline" target="_blank">View All</a>
							</div>
							<div class="xpro-bb-dashboard-modules-item">
								<i class="xi xi-image-slider"></i>
								<h4 class="xpro-bb-dashboard-title xpro-bb-dashboard-widget-count-title"><span class="xpro-pink">Flip Box</span></h4>
								<p class="xpro-bb-dashboard-txt">An amazing addon that offers dual side content with animated touch.</p>
								<a href="https://beaver.wpxpro.com/modules/flip-box/" class="xpro-bb-dashboard-btn xpro-bb-btn-document xpro-btn-underline" target="_blank">View All</a>
							</div>
							<div class="xpro-bb-dashboard-modules-item">
								<i class="xi xi-model-popup"></i>
								<h4 class="xpro-bb-dashboard-title xpro-bb-dashboard-widget-count-title"><span class="xpro-pink">Modal Popup</span></h4>
								<p class="xpro-bb-dashboard-txt">Design intuitive modal popups & display them on click of image, button, text, etc.
								</p>
								<a href="https://beaver.wpxpro.com/modules/modal-popup/" class="xpro-bb-dashboard-btn xpro-bb-btn-document xpro-btn-underline" target="_blank">View All</a>
							</div>
							<div class="xpro-bb-dashboard-modules-item">
								<i class="xi xi-model-popup"></i>
								<h4 class="xpro-bb-dashboard-title xpro-bb-dashboard-widget-count-title"><span class="xpro-pink">News Ticker</span></h4>
								<p class="xpro-bb-dashboard-txt">Create beautiful custom tickers to display trending news, special offers & more.
								</p>
								<a href="https://beaver.wpxpro.com/modules/news-ticker/" class="xpro-bb-dashboard-btn xpro-bb-btn-document xpro-btn-underline" target="_blank">View All</a>
							</div>
							<div class="xpro-bb-dashboard-modules-item">
								<i class="xi xi-model-popup"></i>
								<h4 class="xpro-bb-dashboard-title xpro-bb-dashboard-widget-count-title"><span class="xpro-pink">Image Masking</span></h4>
								<p class="xpro-bb-dashboard-txt">Add out-of-the-box & attractive masking to your images with 55+ options.
								</p>
								<a href="https://beaver.wpxpro.com/modules/image-masking/" class="xpro-bb-dashboard-btn xpro-bb-btn-document xpro-btn-underline" target="_blank">View All</a>
							</div>
						</div>
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
