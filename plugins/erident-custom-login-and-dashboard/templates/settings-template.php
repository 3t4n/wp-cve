<?php
/**
 * Settings page template.
 *
 * @package Custom_Login_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings = get_option( 'plugin_erident_settings', [] );
	?>

	<div class="wrap heatbox-wrap cldashboard-settings-page">

		<div class="heatbox-header heatbox-has-tab-nav heatbox-margin-bottom">

			<div class="heatbox-container heatbox-container-center">

				<div class="logo-container">

					<div>
						<span class="title">
							<?php _e( 'Custom Login & Dashboard', 'erident-custom-login-and-dashboard' ); ?>
							<span class="version"><?php echo esc_html( CUSTOM_LOGIN_DASHBOARD_PLUGIN_VERSION ); ?></span>
						</span>
						<p class="subtitle"><?php _e( 'The #1 rated Plugin to customize the WordPress Login Screen.', 'erident-custom-login-and-dashboard' ); ?></p>
					</div>

					<div>
						<img src="<?php echo esc_url( CUSTOM_LOGIN_DASHBOARD_PLUGIN_URL ); ?>/assets/images/erident-logo.png">
					</div>

				</div>

				<nav>
					<ul class="heatbox-tab-nav">
						<li class="heatbox-tab-nav-item cldashboard-login-screen-panel">
							<a href="#login-screen"><?php _e( 'Login Screen', 'erident-custom-login-and-dashboard' ); ?></a>
						</li>
						<li class="heatbox-tab-nav-item cldashboard-dashboard-settings-panel">
							<a href="#dashboard-settings"><?php _e( 'Dashboard Settings', 'erident-custom-login-and-dashboard' ); ?></a>
						</li>
						<li class="heatbox-tab-nav-item cldashboard-tools-panel">
							<a href="#tools"><?php _e( 'Tools', 'erident-custom-login-and-dashboard' ); ?></a>
						</li>
					</ul>
				</nav>

			</div>

		</div>

		<div class="heatbox-container heatbox-container-center heatbox-column-container heatbox-form-container">

			<div class="heatbox-main">

				<h1 style="display: none;"></h1>

				<div>
					<form method="post" action="options.php" class="cldashboard-settings-form">
						<div class="heatbox-admin-panel cldashboard-dashboard-settings-panel">
							<?php
							$dashboard_settings_box = require __DIR__ . '/setting-boxes/dashboard-settings.php';
							$dashboard_settings_box( $settings );

							$misc_settings_box = require __DIR__ . '/setting-boxes/misc-settings.php';
							$misc_settings_box( $settings );
							?>
						</div>

						<div class="heatbox-admin-panel cldashboard-login-screen-panel">
							<?php
							$login_logo_settings_box = require_once __DIR__ . '/setting-boxes/login-logo-settings.php';
							$login_logo_settings_box( $settings );

							$login_bg_settings_box = require_once __DIR__ . '/setting-boxes/login-bg-settings.php';
							$login_bg_settings_box( $settings );
							?>

							<div class="heatbox-group">
								<?php
								$login_form_layout_settings_box = require_once __DIR__ . '/setting-boxes/login-form-layout-settings.php';
								$login_form_layout_settings_box( $settings );

								$login_form_bg_settings_box = require_once __DIR__ . '/setting-boxes/login-form-bg-settings.php';
								$login_form_bg_settings_box( $settings );

								$login_form_label_settings_box = require_once __DIR__ . '/setting-boxes/login-form-label-settings.php';
								$login_form_label_settings_box( $settings );

								$login_form_input_settings_box = require_once __DIR__ . '/setting-boxes/login-form-input-settings.php';
								$login_form_input_settings_box( $settings );

								$login_form_button_settings_box = require_once __DIR__ . '/setting-boxes/login-form-button-settings.php';
								$login_form_button_settings_box( $settings );

								$login_form_link_settings_box = require_once __DIR__ . '/setting-boxes/login-form-link-settings.php';
								$login_form_link_settings_box( $settings );
								?>
							</div>

							<?php
							$login_footer_link_settings_box = require_once __DIR__ . '/setting-boxes/login-footer-link-settings.php';
							$login_footer_link_settings_box( $settings );
							?>
						</div>

						<div class="cldashboard-form-footer">
							<div class="cldashboard-submit-area">
								<button type="submit" class="button button-primary button-larger cldashboard-submit-button">
									<?php esc_html_e( 'Save All Settings', 'erident-custom-login-and-dashboard' ); ?>
								</button>
								<span class="cldashboard-notice cldashboard-submit-notice"></span>
							</div>
							<div class="cldashboard-reset-area">
								<span class="cldashboard-notice cldashboard-reset-notice"></span>
								<button type="button" class="button button-larger cldashboard-reset-button">
									<?php esc_html_e( 'Reset Settings', 'erident-custom-login-and-dashboard' ); ?>
								</button>
								<button type="button" class="button button-larger cldashboard-load-defaults-button">
									<?php esc_html_e( 'Load Default Settings', 'erident-custom-login-and-dashboard' ); ?>
								</button>
							</div>
						</div>
					</form>

					<div class="heatbox-admin-panel cldashboard-tools-panel">
						<div class="cldashboard-tools-container">

							<?php
							require_once __DIR__ . '/setting-boxes/export-settings.php';
							require_once __DIR__ . '/setting-boxes/import-settings.php';
							?>

						</div>
					</div>
				</div>

			</div>

			<div class="heatbox-sidebar">

				<?php
				require __DIR__ . '/setting-boxes/recommended.php';
				require __DIR__ . '/setting-boxes/review.php';
				?>

			</div>

			<div class="heatbox-divider"></div>

		</div>

		<div class="heatbox-container heatbox-container-wide heatbox-container-center featured-products">

			<h2><?php _e( 'Check out our other free WordPress products!', 'erident-custom-login-and-dashboard' ); ?></h2>

			<ul class="products">
				<li class="heatbox">
					<a href="https://wordpress.org/plugins/ultimate-dashboard/" target="_blank">
						<img src="<?php echo esc_url( CUSTOM_LOGIN_DASHBOARD_PLUGIN_URL ); ?>/assets/images/ultimate-dashboard.jpg">
					</a>
					<div class="heatbox-content">
						<h3><?php _e( 'Ultimate Dashboard', 'erident-custom-login-and-dashboard' ); ?></h3>
						<p class="subheadline"><?php _e( 'Fully customize your WordPress Dashboard.', 'erident-custom-login-and-dashboard' ); ?></p>
						<p><?php _e( 'Ultimate Dashboard is the #1 plugin to create a Custom WordPress Dashboard for you and your clients. It also comes with Multisite Support which makes it the perfect plugin for your WaaS network.', 'erident-custom-login-and-dashboard' ); ?></p>
						<a href="https://wordpress.org/plugins/ultimate-dashboard/" target="_blank" class="button"><?php _e( 'View Features', 'erident-custom-login-and-dashboard' ); ?></a>
					</div>
				</li>
				<li class="heatbox">
					<a href="https://wordpress.org/themes/page-builder-framework/" target="_blank">
						<img src="<?php echo esc_url( CUSTOM_LOGIN_DASHBOARD_PLUGIN_URL ); ?>/assets/images/page-builder-framework.jpg">
					</a>
					<div class="heatbox-content">
						<h3><?php _e( 'Page Builder Framework', 'erident-custom-login-and-dashboard' ); ?></h3>
						<p class="subheadline"><?php _e( 'The only Theme you\'ll ever need.', 'erident-custom-login-and-dashboard' ); ?></p>
						<p class="description"><?php _e( 'With its minimalistic design the Page Builder Framework theme is the perfect foundation for your next project. Build blazing fast websites with a theme that is easy to use, lightweight & highly customizable.', 'erident-custom-login-and-dashboard' ); ?></p>
						<a href="https://wordpress.org/themes/page-builder-framework/" target="_blank" class="button"><?php _e( 'View Features', 'erident-custom-login-and-dashboard' ); ?></a>
					</div>
				</li>
				<li class="heatbox">
					<a href="https://wordpress.org/plugins/better-admin-bar/" target="_blank">
						<img src="<?php echo esc_url( CUSTOM_LOGIN_DASHBOARD_PLUGIN_URL ); ?>/assets/images/swift-control.jpg">
					</a>
					<div class="heatbox-content">
						<h3><?php _e( 'Better Admin Bar', 'erident-custom-login-and-dashboard' ); ?></h3>
						<p class="subheadline"><?php _e( 'Replace the boring WordPress Admin Bar with this!', 'erident-custom-login-and-dashboard' ); ?></p>
						<p><?php _e( 'Better Admin Bar is the plugin that make your clients love WordPress. It drastically improves the user experience when working with WordPress and allows you to replace the boring WordPress admin bar with your own navigation panel.', 'erident-custom-login-and-dashboard' ); ?></p>
						<a href="https://wordpress.org/plugins/better-admin-bar/" target="_blank" class="button"><?php _e( 'View Features', 'erident-custom-login-and-dashboard' ); ?></a>
					</div>
				</li>
			</ul>

			<p class="credit"><?php _e( 'Made with ❤ in Aschaffenburg, Germany', 'erident-custom-login-and-dashboard' ); ?></p>

		</div>

	</div>

	<?php
};
