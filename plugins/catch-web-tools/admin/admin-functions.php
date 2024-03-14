<?php
/**
 * @package Admin
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue admin scripts and styles
 */
function catchwebtools_admin_enqueue_scripts( $hook_suffix ) {
	$allowed_admin_hook_suffix = catchwebtools_admin_hook_suffix();

	if ( in_array( $hook_suffix, $allowed_admin_hook_suffix ) ) {
		wp_enqueue_media();

		wp_enqueue_script( 'catchwebtools-plugin-options', CATCHWEBTOOLS_URL . 'admin/js/admin.js', array( 'jquery', 'wp-color-picker' ), '2013-10-05' );

		wp_enqueue_script( 'catch-ids-match-height', plugin_dir_url( __FILE__ ) . 'js/jquery.matchHeight.min.js', array( 'jquery' ), '1.0', false );

		//CSS Styles
		//Only add genericons CSS in Social Icons Page
		if ( 'catch-web-tools_page_catch-web-tools-social-icons' == $hook_suffix ) {
			wp_enqueue_style( 'cwt-genericons', CATCHWEBTOOLS_URL . 'css/genericons.css', false, '3.4.1' );
		}

		wp_enqueue_style( 'catchwebtools-plugin-css', CATCHWEBTOOLS_URL . 'admin/css/admin.css', array( 'wp-color-picker', 'thickbox' ), '2013-10-05' );

		wp_enqueue_style( 'catchwebtools-plugin-dashboard-css', CATCHWEBTOOLS_URL . 'admin/css/admin-dashboard.css', false, '2013-10-05' );

		/**
		 * Admin Social Links
		 * use facebook and twitter scripts only on dashboard
		 */
		if ( 'toplevel_page_catch-web-tools' == $hook_suffix ) {
			?>
			<!-- Start Social scripts -->
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=276203972392824";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			<!-- End Social scripts -->
			<?php
		}
	}

	//Catch Updater Scripts and Style
	//Only add Catch Updater Scripts and Style to theme-install page and plugin-install page
	if ( 'theme-install.php' == $hook_suffix || 'plugin-install.php' == $hook_suffix ) {
		wp_enqueue_script( 'catch-updater-admin-js', CATCHWEBTOOLS_URL . 'admin/js/catch-updater-admin.js' );

		wp_enqueue_style( 'catch-updater-admin-css', CATCHWEBTOOLS_URL . 'admin/css/catch-updater-admin.css' );
	}

	//Catch Updater Scripts and Style
	//Only add Catch Updater Scripts and Style to theme-install page and plugin-install page
	if ( 'catch-web-tools_page_catch-web-tools-catch-ids' == $hook_suffix ) {
		wp_enqueue_script( 'catch-ids-js', CATCHWEBTOOLS_URL . 'admin/js/catch-ids.js' );
		wp_enqueue_style( 'catch-ids-css', CATCHWEBTOOLS_URL . 'admin/css/catch-ids.css' );
	}
}
add_action( 'admin_enqueue_scripts', 'catchwebtools_admin_enqueue_scripts' );

require_once( CATCHWEBTOOLS_PATH . 'admin/inc/core.php' );

require_once( CATCHWEBTOOLS_PATH . 'admin/inc/catch-ids.php' );

require_once( CATCHWEBTOOLS_PATH . 'admin/inc/social-icons.php' );

require_once( CATCHWEBTOOLS_PATH . 'to-top/to-top.php' );

function cwt_updater() {
	global $wp_version;
	// Disable Catch Updater module by default since version 5.5 and later
	if ( version_compare( $wp_version, '5.5', '<' ) ) {
		// Get Catch Updater Status
		$catchwebtools_catch_updater = catchwebtools_get_options( 'catchwebtools_catch_updater' );

		if ( $catchwebtools_catch_updater['status'] ) {
			if ( is_admin() ) {
				require_once( CATCHWEBTOOLS_PATH . 'admin/catch-updater/inc/catch-updater-modify-installer.php' );
				require_once( CATCHWEBTOOLS_PATH . 'admin/catch-updater/inc/catch-updater-plugin-modify-installer.php' );
			} else {
				require_once( CATCHWEBTOOLS_PATH . 'admin/catch-updater/inc/catch-updater-show-maintenance-message.php' );
			}
		}
	}
}

add_action( 'admin_init', 'cwt_updater' );


//Get Catch Big Image Size Threshold Status
$catchwebtools_big_image_size_threshold = catchwebtools_get_options( 'catchwebtools_big_image_size_threshold' );

if ( $catchwebtools_big_image_size_threshold['status'] ) {
	add_filter( 'big_image_size_threshold', '__return_false' );
}

//Get SEO and OG status
$catchwebtools_seo = catchwebtools_get_options( 'catchwebtools_seo' );
$catchwebtools_og  = catchwebtools_get_options( 'catchwebtools_opengraph' );

/* Include metabox only if SEO or OG modules are activated.
 * Otherwise it produced an error due to WP nonce
 */
if ( ( $catchwebtools_seo['status'] || $catchwebtools_og['status'] ) && is_admin() ) {
	require_once( CATCHWEBTOOLS_PATH . 'admin/inc/metabox.php' );
}

function catchwebtools_custom_css_migrate() {
	$ver = get_theme_mod( 'cwt_custom_css_version', false );

	// Return if update has already been run
	if ( version_compare( $ver, '4.7' ) >= 0 ) {
		return;
	}

	if ( function_exists( 'wp_update_custom_css_post' ) ) {
		// Migrate any existing theme CSS to the core option added in WordPress 4.7.

		/**
		 * Get Theme Options Values
		 */
		$custom_css = catchwebtools_get_options( 'catchwebtools_custom_css' );

		if ( '' != $custom_css && ! is_array( $custom_css ) ) {
			$core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
			$return   = wp_update_custom_css_post( $core_css . $custom_css );
			if ( ! is_wp_error( $return ) ) {
				// Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
				unset( $custom_css );
				delete_transient( 'catchwebtools_custom_css' );
				delete_option( 'catchwebtools_custom_css' );

				// Update to match custom_css_version so that script is not executed continously
				set_theme_mod( 'cwt_custom_css_version', '4.7' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'catchwebtools_custom_css_migrate' );

// Display customizer options of Heaader Footer script only if WebMaster module is active.
function catchwebtools_is_active_webmaster_module( $control ) {
	$enabled = $control->manager->get_setting( 'catchwebtools_webmaster[status]' )->value();
	if ( 1 == $enabled ) {
		return true;
	} else {
		return false;
	}
}
