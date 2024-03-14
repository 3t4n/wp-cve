<?php
/**
 * Better Admin Bar page template.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

// Get the widgets.
$active_widgets    = swift_control_get_active_widgets();
$available_widgets = swift_control_get_available_widgets();
$locked_widgets    = swift_control_get_locked_widgets();

// Get the settings.
$default_settings = swift_control_get_default_widget_settings();
$locked_settings  = swift_control_get_locked_widget_settings();

// Get saved settings.
$saved_widget_settings = swift_control_get_saved_widget_settings();

// Pass saved settings & default settings to $GLOBALS so we can re-use it later.
$GLOBALS['swift_control_default_settings'] = $default_settings;
$GLOBALS['swift_control_widget_settings']  = $saved_widget_settings;
?>

<div class="wrap heatbox-wrap swift-control-settings">

	<div class="heatbox-header heatbox-has-tab-nav heatbox-margin-bottom">

		<div class="heatbox-container heatbox-container-center">

			<div class="logo-container">

				<div>
					<span class="title">
						<?php _e( 'Better Admin Bar', 'better-admin-bar' ); ?>
						<span class="version"><?php echo esc_html( SWIFT_CONTROL_PLUGIN_VERSION ); ?></span>
					</span>
					<p class="subtitle"><?php _e( 'The WordPress Admin Bar reimagined.', 'better-admin-bar' ); ?></p>
				</div>

				<div>
					<img src="<?php echo esc_url( SWIFT_CONTROL_PLUGIN_URL ); ?>/assets/images/logo.png">
				</div>

			</div>

			<nav>
				<ul class="heatbox-tab-nav">
					<li class="heatbox-tab-nav-item swift-control-settings-panel">
						<a href="#settings"><?php _e( 'Quick Access Panel', 'better-admin-bar' ); ?></a>
					</li>
					<li class="heatbox-tab-nav-item swift-control-admin-bar-panel">
						<a href="#admin-bar"><?php _e( 'Admin Bar Settings', 'better-admin-bar' ); ?></a>
					</li>
					<li class="heatbox-tab-nav-item swift-control-tools-panel">
						<a href="#tools"><?php _e( 'Tools', 'better-admin-bar' ); ?></a>
					</li>
					<li class="swift-control-preview">
						<label for="swift_control_preview_toggle" class="toggle-switch">
							<input
								type="checkbox"
								name="swift_control_preview_toggle"
								id="swift_control_preview_toggle"
								value="1"
							/>
							<div class="switch-track">
								<div class="switch-thumb"></div>
							</div>
						</label>
						<span class="preview-text"><?php _e( 'Preview', 'better-admin-bar' ); ?></span>
					</li>
				</ul>
			</nav>

		</div>

	</div>

	<div class="heatbox-container heatbox-container-center heatbox-form-container">

		<h1 style="display: none;"></h1>

		<form method="post" action="options.php" class="swift-control-settings-form general-settings-area">

			<div class="saved-status-bar"><?php _e( 'Your settings have been saved.', 'better-admin-bar' ); ?></div>

			<div class="heatbox-admin-panel swift-control-settings-panel">

				<div class="heatbox-column-container">
					<div class="heatbox-main">
						<?php require_once __DIR__ . '/setting-boxes/active-widgets.php'; ?>

						<!-- On mobile screen / when the layout is stacked, the heatboxes from the "heatbox-sidebar" will be moved here -->
						<div class="stacked-heatbox-placeholder"></div>

						<?php
						require_once __DIR__ . '/setting-boxes/display-settings.php';
						require_once __DIR__ . '/setting-boxes/color-settings.php';
						require_once __DIR__ . '/setting-boxes/misc-settings.php';
						?>
					</div>

					<div class="heatbox-sidebar">
						<?php
						require_once __DIR__ . '/setting-boxes/available-widgets.php';
						require_once __DIR__ . '/setting-boxes/pro-widgets.php';
						?>
					</div>
				</div>

			</div>

			<div class="heatbox-admin-panel swift-control-admin-bar-panel">
				<?php
				require_once __DIR__ . '/setting-boxes/admin-bar-settings.php';
				?>
			</div>

			<p class="submit">
				<button type="button" name="submit" id="submit" class="button button-primary button-larger save-general-settings" value="Save Changes">
					<?php _e( 'Save Changes', 'better-admin-bar' ); ?>
				</button>
			</p>

		</form>

		<div class="heatbox-admin-panel swift-control-tools-panel">
			<div class="swift-control-tools-container">
				<?php
				require_once __DIR__ . '/setting-boxes/export-widgets.php';
				require_once __DIR__ . '/setting-boxes/import-widgets.php';
				?>
			</div>
		</div>

		<div class="heatbox-divider"></div>

		<div class="heatbox-container heatbox-container-wide heatbox-container-center featured-products">

			<h2><?php _e( 'Check out our other free WordPress products!', 'better-admin-bar' ); ?></h2>

			<ul class="products">
				<li class="heatbox">
					<a href="https://wordpress.org/plugins/ultimate-dashboard/" target="_blank">
						<img src="<?php echo esc_url( SWIFT_CONTROL_PLUGIN_URL ); ?>/assets/images/ultimate-dashboard.jpg">
					</a>
					<div class="heatbox-content">
						<h3><?php _e( 'Ultimate Dashboard', 'better-admin-bar' ); ?></h3>
						<p class="subheadline"><?php _e( 'Fully customize your WordPress Dashboard.', 'better-admin-bar' ); ?></p>
						<p><?php _e( 'Ultimate Dashboard is the #1 plugin to create a Custom WordPress Dashboard for you and your clients. It also comes with Multisite Support which makes it the perfect plugin for your WaaS network.', 'better-admin-bar' ); ?></p>
						<a href="https://wordpress.org/plugins/ultimate-dashboard/" target="_blank" class="button"><?php _e( 'View Features', 'better-admin-bar' ); ?></a>
					</div>
				</li>
				<li class="heatbox">
					<a href="https://wordpress.org/themes/page-builder-framework/" target="_blank">
						<img src="<?php echo esc_url( SWIFT_CONTROL_PLUGIN_URL ); ?>/assets/images/page-builder-framework.jpg">
					</a>
					<div class="heatbox-content">
						<h3><?php _e( 'Page Builder Framework', 'better-admin-bar' ); ?></h3>
						<p class="subheadline"><?php _e( 'The only Theme you\'ll ever need.', 'better-admin-bar' ); ?></p>
						<p class="description"><?php _e( 'With its minimalistic design the Page Builder Framework theme is the perfect foundation for your next project. Build blazing fast websites with a theme that is easy to use, lightweight & highly customizable.', 'better-admin-bar' ); ?></p>
						<a href="https://wordpress.org/themes/page-builder-framework/" target="_blank" class="button"><?php _e( 'View Features', 'better-admin-bar' ); ?></a>
					</div>
				</li>
				<li class="heatbox">
					<a href="https://wordpress.org/plugins/responsive-youtube-vimeo-popup/" target="_blank">
						<img src="<?php echo esc_url( SWIFT_CONTROL_PLUGIN_URL ); ?>/assets/images/wp-video-popup.jpg">
					</a>
					<div class="heatbox-content">
						<h3><?php _e( 'WP Video Popup', 'better-admin-bar' ); ?></h3>
						<p class="subheadline"><?php _e( 'The #1 Video Popup Plugin for WordPress.', 'better-admin-bar' ); ?></p>
						<p><?php _e( 'Add beautiful responsive YouTube & Vimeo video lightbox popups to any post, page or custom post type of website without sacrificing performance.', 'better-admin-bar' ); ?></p>
						<a href="https://wordpress.org/plugins/responsive-youtube-vimeo-popup/" target="_blank" class="button"><?php _e( 'View Features', 'better-admin-bar' ); ?></a>
					</div>
				</li>
			</ul>

			<p class="credit"><?php _e( 'Made with ❤ in Aschaffenburg, Germany', 'better-admin-bar' ); ?></p>

		</div>

	</div>

</div>
