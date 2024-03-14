<?php

use XproElementorAddons\Inc\Xpro_Elementor_Widget_List;
use XproElementorAddons\Libs\Xpro_Elementor_Dashboard;
use XproElementorAddonsPro\Libs\Xpro_Elementor_License;

$sections = array(
	'dashboard' => array(
		'title' => esc_html__( 'Dashboard', 'xpro-elementor-addons' ),
		'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25.045 25.003"><path data-name="Path 3" d="M24.286 19.115c-.459-.018-.92-.005-1.38-.006h-.875a5.157 5.157 0 0 0-10.208 0H1.032c-.678 0-1.034.255-1.036.739s.358.743 1.032.743h10.821a5.133 5.133 0 0 0 1.779 3.195 5.116 5.116 0 0 0 6.462.133 5.171 5.171 0 0 0 1.941-3.328h.389c.621 0 1.243.012 1.864-.007a.735.735 0 1 0 0-1.469m-7.352 4.4a3.669 3.669 0 1 1 3.688-3.707 3.681 3.681 0 0 1-3.688 3.707"/><path data-name="Path 4" d="M1.02 5.884H3a5.15 5.15 0 0 0 10.2 0h10.852c.644 0 1-.261 1-.737s-.346-.727-1-.727H13.23C12.35 1.535 10.738.097 8.31.005a5 5 0 0 0-3.035.851A5.17 5.17 0 0 0 3.011 4.42h-1.99c-.662 0-1 .243-1.013.716s.345.748 1.02.748M8.16 1.473a3.678 3.678 0 1 1-3.736 3.634A3.686 3.686 0 0 1 8.16 1.473"/></svg>',
	),
	'widgets'   => array(
		'title' => esc_html__( 'Widgets', 'xpro-elementor-addons' ),
		'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25.01 25.003"><path d="M13.26 1.939c.189-.209.336-.385.5-.547a4.374 4.374 0 0 1 6.318 0q1.77 1.761 3.532 3.531a4.282 4.282 0 0 1 0 6.339c-.159.16-.332.306-.539.5.244.08.409.119.561.186a2.091 2.091 0 0 1 1.363 1.977c.025 2.5.029 5.006 0 7.509a3.639 3.639 0 0 1-3.681 3.564c-3.008.013-6.016 0-9.025 0h-8.4A3.687 3.687 0 0 1 .012 21.13V3.873A3.692 3.692 0 0 1 3.891.001q3.307-.005 6.614 0c1.625 0 2.174.373 2.765 1.939m3.441 12.734a2.926 2.926 0 0 0 2.22-.822c1.266-1.251 2.531-2.5 3.774-3.777a2.772 2.772 0 0 0 0-3.964q-1.866-1.909-3.776-3.775a2.842 2.842 0 0 0-4.057.02q-1.876 1.851-3.726 3.727a2.78 2.78 0 0 0 0 4.014c1.231 1.253 2.479 2.49 3.725 3.729a2.715 2.715 0 0 0 1.841.847M1.483 13.249v.382c0 2.492.033 4.984-.013 7.474a2.31 2.31 0 0 0 2.426 2.434c2.491-.03 4.983-.008 7.475-.008h.37V13.25Zm11.766 10.263c.1.01.152.02.208.02 2.607 0 5.214.012 7.821 0a2.2 2.2 0 0 0 2.254-2.245c.014-.574 0-1.148 0-1.723v-5.375c0-.681-.263-.949-.928-.954a4.545 4.545 0 0 0-.688.008.91.91 0 0 0-.475.2c-.526.5-1.017 1.03-1.545 1.524a4.183 4.183 0 0 1-3.634 1.16 4.379 4.379 0 0 1-2.583-1.4c-.125-.132-.267-.249-.434-.4Zm-2.63-11.759.07-.079c-.224-.208-.461-.4-.669-.625a4.264 4.264 0 0 1 .049-5.969 10.836 10.836 0 0 1 1.029-1.016 1.7 1.7 0 0 0 .684-1.633c-.061-.709-.249-.95-.962-.951H3.86a2.19 2.19 0 0 0-2.375 2.383v7.546c0 .11.012.221.019.342Z"/></svg>',
	),
	'modules'   => array(
		'title' => esc_html__( 'Extensions', 'xpro-elementor-addons' ),
		'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22.069 25.078"><path d="M6.848 5.964a4.381 4.381 0 0 1 1.809-5.2 4.081 4.081 0 0 1 4.768.008 4.356 4.356 0 0 1 1.789 5.192h4.487a2.208 2.208 0 0 1 2.364 2.372v12.891a3.683 3.683 0 0 1-3.865 3.85H2.383a2.2 2.2 0 0 1-2.372-2.359c0-.965.034-1.932-.01-2.895A1.529 1.529 0 0 1 2.069 18.3a2.924 2.924 0 1 0 1.437-5.654 3.385 3.385 0 0 0-1.422.084A1.5 1.5 0 0 1 .01 11.19c0-.977-.005-1.953 0-2.93a2.174 2.174 0 0 1 2.29-2.3c1.378-.008 2.757 0 4.135 0h.412M1.476 19.698v2.929c0 .721.247.98.964.981h15.784a2.2 2.2 0 0 0 2.359-2.342q.006-6.463 0-12.926c0-.667-.25-.913-.918-.913h-4.273a1.509 1.509 0 0 1-1.576-2.08 3.434 3.434 0 0 0 .087-1.422 2.921 2.921 0 1 0-5.667 1.38 1.566 1.566 0 0 1-1.624 2.138c-1.433-.064-2.871-.018-4.307-.014a.742.742 0 0 0-.829.832v3.09a4.335 4.335 0 0 1 5.034 1.571 4.433 4.433 0 0 1 .135 4.959c-1.078 1.669-2.8 2.295-5.167 1.82"/></svg>',
	),
	'userdata'  => array(
		'title' => esc_html__( 'User Data', 'xpro-elementor-addons' ),
		'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25.054 23"><path data-name="Path 5" d="M24.821 13.646a4.393 4.393 0 0 0-1.271-2.143 4.365 4.365 0 0 0 1.437-3.025 36.671 36.671 0 0 0-.007-4.551A3.932 3.932 0 0 0 21.772.059a14.42 14.42 0 0 0-2.1-.042H3.819C1.678.024.1 1.824.027 4.392-.008 5.62.016 6.85.027 8.079a5.022 5.022 0 0 0 .088.819 4.6 4.6 0 0 0 1.383 2.6l-.138.158a4.549 4.549 0 0 0-1.322 3.159c-.04 1.338-.061 2.682.009 4.017.129 2.427 1.651 4.115 3.674 4.155 1.011.02 2.022 0 3.033 0h14.44c2.269 0 3.829-1.889 3.833-4.612 0-1.1.02-2.21-.009-3.314a7.009 7.009 0 0 0-.2-1.419M1.5 4.533c.011-1.62.926-2.749 2.272-2.751q8.754-.015 17.508 0c1.323 0 2.254 1.128 2.276 2.716q.024 1.7 0 3.4c-.02 1.613-.96 2.725-2.3 2.727q-4.377.007-8.754 0H3.886c-1.48 0-2.384-1.083-2.388-2.859 0-1.077-.006-2.155 0-3.232M23.56 18.451c-.013 1.663-.947 2.779-2.325 2.781q-4.36.005-8.72 0H3.899c-1.5 0-2.395-1.081-2.4-2.889 0-1.063-.006-2.127 0-3.19.01-1.643.928-2.762 2.3-2.764q8.72-.011 17.439 0c1.38 0 2.31 1.13 2.322 2.789q.012 1.637 0 3.273"/><path data-name="Path 6" d="M5.277 6.857h8.639a.734.734 0 0 0 .807-.674c.04-.464-.313-.787-.871-.787h-8.54a1.623 1.623 0 0 0-.241.009.729.729 0 0 0-.547 1.069.761.761 0 0 0 .749.389"/><path data-name="Path 8" d="M13.887 16.153H5.278a1.239 1.239 0 0 0-.24.014.733.733 0 0 0-.474 1.126.81.81 0 0 0 .751.326h8.609a.736.736 0 0 0 .806-.712.749.749 0 0 0-.843-.749"/></svg>',
	),
);

$sections = apply_filters( 'xpro_elementor_addons_admin_sections_list', $sections );

?>

<div class="xpro-dashboard">
	<form method="POST" id="xpro-dashboard-settings-form" action="<?php echo esc_url( site_url() . '/wp-admin/admin-ajax.php?action=xpro_elementor_addons_admin_action&nonce=' . wp_create_nonce( 'xpro-dashboard-nonce' ) ); ?>">

		<!-- Xpro Dasboard Header -->
		<div class="xpro-dashboard-header">
			<div class="xpro-row">
				<div class="xpro-col-6">
					<div class="xpro-dashboard-header-info">
						<a href="<?php echo esc_url( 'https://elementor.wpxpro.com/' ); ?>" target="_blank" class="xpro-dashboard-header-logo">
							<img src="<?php echo esc_url( XPRO_ELEMENTOR_ADDONS_ASSETS . '/admin/images/xpro-logo-dark.png' ); ?>" alt="xpro-logo">
						</a>
					</div>
				</div>
				<div class="xpro-col-6">
					<div class="xpro-dashboard-header-panel">
						<span class="xpro-elementor-addon-version">
							<?php echo esc_html( 'Version: ' . XPRO_ELEMENTOR_ADDONS_VERSION ); ?>
						</span>
						<span class="xpro-elementor-addon-package">
							<?php echo esc_html( 'License: ' . ucwords( xpro_elementor_get_package_type() ) ); ?>
						</span>
					</div>
				</div>
			</div>
		</div>

		<div class="xpro-dashboard-body">
			<div class="xpro-row">

				<div class="xpro-col-lg-9">
					<!--Nav Tabs-->
					<div class="xpro-dashboard-tabs-wrapper">
						<ul class="xpro-dashboard-tabs">
							<!-- sections nav begins -->
							<?php
							$count = 0;
							foreach ( $sections as $section_key => $section ) :
								?>
								<li <?php echo ( key( $sections ) === $section_key ) ? 'class="active"' : ''; ?>>
									<a href="#xpro-<?php echo esc_attr( strtolower( $section_key ) ); ?>" class="xpro-dashboard-tab-link-<?php echo esc_attr( $section_key ); ?>">
										<?php
										$icon_allowed['svg']  = array(
											'xmlns'       => array(),
											'fill'        => array(),
											'viewbox'     => array(),
											'role'        => array(),
											'aria-hidden' => array(),
											'focusable'   => array(),
										);
										$icon_allowed['path'] = array(
											'd'    => array(),
											'fill' => array(),
										);
										echo wp_kses( $section['icon'], $icon_allowed );
										?>
										<?php echo esc_html( $section['title'] ); ?>
									</a>
								</li>
								<?php
								$count ++;
							endforeach;
							?>
							<!-- sections nav ends -->
						</ul>
					</div>

					<!--Nav Tabs Content-->
					<div class="xpro-dashboard-content-wrapper">
						<?php
						foreach ( $sections as $section_key => $section ) :
							include XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'libs/dashboard/views/settings-' . $section_key . '.php';
						endforeach;
						?>
					</div>

					<!--Help & Support-->
					<div class="xpro-dashboard-support-wrapper">
						<div class="xpro-dashboard-support-content">
							<h4 class="xpro-dashboard-label">
								<?php echo esc_html__( 'Need Help?', 'xpro-elementor-addons' ); ?>
							</h4>
							<h3><?php echo esc_html__( 'Expert Support', 'xpro-elementor-addons' ); ?></h3>
							<p>
								<?php echo esc_html__( 'Feel like consulting our support team? Contact our 24/7 user support and we will happily assist you with any.', 'xpro-elementor-addons' ); ?>
							</p>
							<a target="_blank" href="<?php echo esc_url( 'https://elementor.wpxpro.com/contact-us/' ); ?>"><?php echo esc_html__( 'Contact Us', 'xpro-elementor-addons' ); ?></a>
						</div>
						<div class="xpro-dashboard-support-image">
							<img src="<?php echo esc_url( XPRO_ELEMENTOR_ADDONS_ASSETS . '/admin/images/support-image-1.png' ); ?>" alt="support">
						</div>
					</div>
				</div>

				<div class="xpro-col-lg-3">
					<div class="xpro-dashboard-sidebar">
						<div class="xpro-dashboard-social-wrapper">
							<h4 class="xpro-dashboard-label"><?php echo esc_html__( 'Our Social', 'xpro-elementor-addons' ); ?></h4>
							<div class="xpro-dashboard-social-list">
								<a href="https://www.facebook.com/xproelementor/" target="_blank"><i class="dashicons dashicons-facebook-alt" aria-hidden="true"></i></a>
								<a href="https://www.instagram.com/xproelementor/" target="_blank"><i class="dashicons dashicons-instagram" aria-hidden="true"></i></a>
								<a href="https://twitter.com/xproelementor" target="_blank"><i class="dashicons dashicons-twitter" aria-hidden="true"></i></a>
							</div>
						</div>
						<div class="xpro-dashboard-widget-count-wrapper">
							<?php
							$widgets_all  = Xpro_Elementor_Widget_List::instance()->get_list();
							$widget_count = array();
							foreach ( $widgets_all as $i => $w ) {
								if ( 'pro-disabled' !== $w['package'] ) {
									$widget_count[ $w['slug'] ] = $w;
								}
							}

							if ( ! Xpro_Elementor_Dashboard::instance()->utils->get_option( 'xpro_elementor_widget_list' ) ) {
								$widgets_active = get_option( 'xpro_elementor_widget_list', array_keys( $widget_count ) );
							} else {
								$widgets_active = Xpro_Elementor_Widget_List::instance()->get_list( false, '', 'active' );
							}

							?>
							<h4 class="xpro-dashboard-label">
								<?php echo esc_html__( 'Overview', 'xpro-elementor-addons' ); ?>
							</h4>
							<ul class="xpro-dashboard-widget-count-list">
								<li>
									<span class="xpro-dashboard-widget-count">
										<?php echo esc_html( count( $widgets_all ) ); ?>
									</span>
									<span class="xpro-dashboard-widget-count-text">
										<?php echo esc_html__( 'Widgets', 'xpro-elementor-addons' ); ?></span>
								</li>
								<li>
									<span class="xpro-dashboard-widget-count">
										<?php echo esc_html( count( $widgets_active ) ); ?>
									</span>
									<span class="xpro-dashboard-widget-count-text">
										<?php echo esc_html__( 'Active', 'xpro-elementor-addons' ); ?>
									</span>
								</li>
								<li>
									<span class="xpro-dashboard-widget-count">
										<?php echo esc_html( ( count( $widgets_all ) - count( $widgets_active ) ) ); ?>
									</span>
									<span class="xpro-dashboard-widget-count-text">
										<?php echo esc_html__( 'Inactive', 'xpro-elementor-addons' ); ?>
									</span>
								</li>
							</ul>
							<div class="xpro-dashboard-widget-count-btn-wrapper">
								<a target="_blank" href="<?php echo esc_url( 'https://elementor.wpxpro.com/widgets' ); ?>" class="xpro-dashboard-widget-count-btn">
									<?php echo esc_html__( 'Explore Widgets', 'xpro-elementor-addons' ); ?>
								</a>
							</div>
						</div>
						<div class="xpro-dashboard-featured-wrapper">
							<h4 class="xpro-dashboard-label">
								<?php echo esc_html__( 'Featured Widgets', 'xpro-elementor-addons' ); ?>
							</h4>
							<?php
							$featured = array(
								'multi-layer-slider' => array(
									'title'       => esc_html__( 'Multi Layer Slider', 'xpro-elementor-addons' ),
									'icon'        => 'xi xi-multi-layer-slider',
									'description' => esc_html__( 'Powerful slider to create eye-popping slides for your websites.', 'xpro-elementor-addons' ),
									'url'         => 'https://elementor.wpxpro.com/multi-layer-slider/',
								),
								'advanced-portfolio' => array(
									'title'       => esc_html__( 'Advanced Portfolio', 'xpro-elementor-addons' ),
									'icon'        => 'xi xi-advance-portfolio',
									'description' => esc_html__( 'Design amazing portfolios using our premium layouts & hover effects.', 'xpro-elementor-addons' ),
									'url'         => 'https://elementor.wpxpro.com/advanced-portfolio/',
								),
								'advanced-gallery'   => array(
									'title'       => esc_html__( 'Advanced Gallery', 'xpro-elementor-addons' ),
									'icon'        => 'xi xi-advance-gallery',
									'description' => esc_html__( 'The most advanced gallery widget to create beautiful layouts.', 'xpro-elementor-addons' ),
									'url'         => 'https://elementor.wpxpro.com/advanced-gallery/',
								),
							);
							?>
							<div class="xpro-dashboard-featured-carousel xpro-owl-theme owl-carousel">
								<?php
								$f_count = 1;
								foreach ( $featured as $i => $item ) {
									?>
									<div data-item="<?php echo esc_attr( $f_count ); ?>" class="xpro-dashboard-featured-item">
										<i class="<?php echo esc_attr( $item['icon'] ); ?>" aria-hidden="true"></i>
										<h3><?php echo esc_html( $item['title'] ); ?></h3>
										<p><?php echo esc_html( $item['description'] ); ?></p>
										<a target="_blank" href="<?php echo esc_url( $item['url'] ); ?>">
											<?php echo esc_html__( 'Discove More', 'xpro-elementor-addons' ); ?>
										</a>
									</div>
									<?php
									$f_count ++;
								}
								?>
							</div>
						</div>
						<div class="xpro-dashboard-documentation-wrapper">
							<h4 class="xpro-dashboard-label">
								<?php echo esc_html__( 'Documentation', 'xpro-elementor-addons' ); ?>
							</h4>
							<p>
								<?php echo esc_html__( 'We have prepared comprehensive documentation for you to get the most of our powerful Elementor Widgets. Let’s understand how our widgets work.', 'xpro-elementor-addons' ); ?>
							</p>
							<a target="_blank" href="<?php echo esc_url( 'https://elementor.wpxpro.com/docs' ); ?>"><?php echo esc_html__( 'View Documentation', 'xpro-elementor-addons' ); ?></a>
						</div>
					</div
				</div>

			</div>
		</div>

	</form>
	<div class="xpro-dashboard-popup-wrapper">
		<div class="xpro-dashboard-popup-content">
			<button type="button" class="xpro-dashboard-popup-close-btn">
				<i class="xi xi-cross"></i>
			</button>
			<i class="xi xi-link-2"></i>
			<?php if ( did_action( 'xpro_elementor_addons_pro_loaded' ) ) : ?>
				<h2><?php echo esc_html__( 'Go Premium', 'xpro-elementor-addons' ); ?></h2>
				<p><?php esc_html_e( 'Activate', 'xpro-elementor-addons' ); ?>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=xpro-elementor-addons-license' ) ); ?>">
						<?php esc_html_e( 'pro version', 'xpro-elementor-addons' ); ?>
					</a>
					<?php esc_html_e( 'to unlock our premium features!', 'xpro-elementor-addons' ); ?>
				</p>
			<?php else : ?>
				<h2><?php echo esc_html__( 'Go Premium', 'xpro-elementor-addons' ); ?></h2>
				<p><?php esc_html_e( 'Purchase', 'xpro-elementor-addons' ); ?>
					<a target="_blank" href="https://elementor.wpxpro.com/buy">
						<?php esc_html_e( 'pro version', 'xpro-elementor-addons' ); ?>
					</a>
					<?php esc_html_e( 'to unlock our premium features!', 'xpro-elementor-addons' ); ?>
				</p>
			<?php endif; ?>
		</div>
	</div>
</div>
