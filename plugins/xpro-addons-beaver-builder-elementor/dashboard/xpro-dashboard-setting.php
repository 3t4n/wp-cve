<?php
if ( ! function_exists( 'xpro_dashboard_welcome' ) ) {
	function xpro_dashboard_welcome() {
		 $sections = array(
			 'dashboard'    => array(
				 'title'     => esc_html__( 'Dashboard', 'xpro-bb-addons' ),
				 'sub-title' => esc_html__( 'General info', 'xpro-bb-addons' ),
			 ),
			 'modules'      => array(
				 'title'     => esc_html__( 'Modules', 'xpro-bb-addons' ),
				 'sub-title' => esc_html__( 'List of Modules', 'xpro-bb-addons' ),
			 ),
			 'features'     => array(
				 'title'     => esc_html__( 'Xpro Features', 'xpro-bb-addons' ),
				 'sub-title' => esc_html__( 'Xpro Features', 'xpro-bb-addons' ),
			 ),
			 'integrations' => array(
				 'title'     => esc_html__( 'Integration', 'xpro-bb-addons' ),
				 'sub-title' => esc_html__( 'Xpro Integration For BB', 'xpro-bb-addons' ),
			 ),
			 'pro'          => array(
				 'title'     => esc_html__( 'Go Pro', 'xpro-bb-addons' ),
				 'sub-title' => esc_html__( 'Xpro Pro Modules', 'xpro-bb-addons' ),
			 ),
		 );

		 $sections = apply_filters( 'xpro-addons/admin/sections/list', $sections );

			?>
		<div class="xpro-bb-dashboard-wrapper">

			<!-- Header -->
			<div class="xpro-bb-header-wrapper">
				<a href="<?php echo esc_url( get_site_url() . '/wp-admin/admin.php?page=xpro_dashboard_welcome' ); ?>" class="xpro-bb-header-logo">
					<img src="<?php echo esc_url( XPRO_ADDONS_FOR_BB_URL . 'assets/images/Logo.png' ); ?>" alt="image">
				</a>
				<div class="xpro-bb-header-btn">
					<a href="https://beaver.wpxpro.com/docs/" target="_blank" class="xpro-bb-dashboard-btn xpro-bb-btn-document">Documentation</a>
					<a href="https://beaver.wpxpro.com/contact-us/" target="_blank" class="xpro-bb-dashboard-btn xpro-bb-btn-support">support</a>
				</div>
			</div>

			<form method="POST" id="xpro-dashboard-settings-form" action="<?php echo esc_url( admin_url() . 'admin-ajax.php?action=xpro_beaver_addons_admin_action&nonce=' . wp_create_nonce( 'xpro-dashboard-nonce' ) ); ?>">

				<!-- Nav -->
				<div class="xpro-row">
					<div class="xpro-col-lg-9">
						<div class="xpro-bb-tabs-wrapper">
							<ul class="xpro-bb-tabs">
								<!-- sections nav begins -->
								<?php
								$count = 0;
								foreach ( $sections as $section_key => $section ) :
									reset( $sections );
									?>
									<li <?php echo ( $section_key === key( $sections ) ) ? 'class="active"' : ''; ?>>
										<!-- class="xpro-dashboard-tab-link" -->
										<a href="#bb-<?php echo strtolower( $section_key ); ?>" class="xpro-bb-tabs-link">
											<?php echo esc_html( $section['title'] ); ?>
										</a>
									</li>
									<?php
									$count++;
endforeach;
								?>
								<!-- sections nav ends -->
							</ul>
						</div>
					</div>
					<div class="xpro-col-lg-3">
						<div class="xpro-bb-dashboard-sidebar-wrapper">
							<div class="xpro-bb-dashboard-sidebar">
								<div class="xpro-bb-social-tabs">
									<span>Our Community</span>
									<div class="xpro-bb-social-tabs-icon">
										<a href="https://www.facebook.com/xprobeaver" target="_blank"><i class="xi xi-facebook-feed"></i></a>
										<a href="https://www.instagram.com/xprobeaver/" target="_blank"><i class="xi xi-instagram-feed"></i></a>
										<a href="https://twitter.com/xprobeaver" target="_blank"><i class="xi xi-twitter-feed"></i></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php
				foreach ( $sections as $section_key => $section ) :
					reset( $sections );
					include XPRO_ADDONS_FOR_BB_DIR . 'dashboard/views/settings-' . $section_key . '.php';
				endforeach;
				?>

			</form>

			<div class="xpro-dashboard-popup-wrapper">
				<div class="xpro-dashboard-popup-content">
					<button type="button" class="xpro-dashboard-popup-close-btn">
						<i class="xi xi-cross"></i>
					</button>
					<i class="xi xi-link-2"></i>
					<?php if ( did_action( 'xpro_beaver_addons_pro_loaded' ) ) : ?>
						<h2><?php echo esc_html__( 'Go Premium', 'xpro-bb-addons' ); ?></h2>
						<p><?php esc_html_e( 'Activate', 'xpro-bb-addons' ); ?>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=xpro_addons_for_bb_settings' ) ); ?>">
								<?php esc_html_e( 'pro version', 'xpro-bb-addons' ); ?>
							</a>
							<?php esc_html_e( 'to unlock our premium features!', 'xpro-bb-addons' ); ?>
						</p>
					<?php else : ?>
						<h2><?php echo esc_html__( 'Go Premium', 'xpro-bb-addons' ); ?></h2>
						<p><?php esc_html_e( 'Purchase', 'xpro-bb-addons' ); ?>
							<a target="_blank" href="https://beaver.wpxpro.com/bundle-pricing/">
								<?php esc_html_e( 'pro version', 'xpro-bb-addons' ); ?>
							</a>
							<?php esc_html_e( 'to unlock our premium features!', 'xpro-bb-addons' ); ?>
						</p>
					<?php endif; ?>
				</div>
			</div>

		</div>
		<?php
	}
}
