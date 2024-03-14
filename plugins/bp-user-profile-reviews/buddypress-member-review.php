<?php
/**
 * Plugin Name: Wbcom Designs - BuddyPress Member Reviews
 * Plugin URI: https://wbcomdesigns.com/downloads/buddypress-user-profile-reviews/
 * Description: The BuddyPress Member Reviews plugin enhances the Buddypress community by empowering registered users to post reviews on other members' profiles. This feature is exclusive to registered members, preventing users from reviewing their profiles to ensure unbiased feedback.
 * Version: 3.1.0
 * Author: Wbcom Designs
 * Author URI: https://wbcomdesigns.com
 * License: GPLv2+
 * Text Domain: bp-member-reviews
 * Domain Path: /languages
 *
 * @package BuddyPress_Member_Reviews
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Constants used in the plugin.
 */
define( 'BUPR_PLUGIN_VERSION', '3.1.0' );
define( 'BUPR_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'BUPR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once __DIR__ . '/vendor/autoload.php';
HardG\BuddyPress120URLPolyfills\Loader::init();

if ( ! function_exists( 'bupr_load_textdomain' ) ) {
	add_action( 'init', 'bupr_load_textdomain' );
	/**
	 * Load plugin textdomain.
	 *
	 * @author   Wbcom Designs
	 * @since    1.0.0
	 */
	function bupr_load_textdomain() {
		$domain = 'bp-member-reviews';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain, 'languages/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}
}



if ( ! function_exists( 'bupr_plugins_files' ) ) {

	add_action( 'plugins_loaded', 'bupr_plugins_files' );

	/**
	 * Include requir files
	 *
	 * @author   Wbcom Designs
	 * @since    1.0.0
	 */
	function bupr_plugins_files() {
		if ( class_exists( 'BuddyPress' ) ) {
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bupr_admin_page_link' );
			/**
			* Include needed files on init
			*/
			$include_files = array(
				'includes/class-buprglobals.php',
				'admin/wbcom/wbcom-admin-settings.php',
				'includes/bupr-scripts.php',
				'admin/bupr-admin.php',
				'admin/class-bupr-admin-feedback.php',
				'includes/bupr-filters.php',
				'includes/bupr-shortcodes.php',
				'includes/widgets/display-review.php',
				'includes/widgets/member-rating.php',
				'includes/bupr-ajax.php',
				'includes/bupr-notification.php',
				'includes/bupr-genral-functions.php',
			);

			foreach ( $include_files as $include_file ) {
				include $include_file;
			}
		}
	}
}

/**
 * Settings link for this plugin.
 *
 * @param array $links The plugin setting links array.
 * 
 * @return array
 * @author   Wbcom Designs
 * @since    1.0.0
 */
function bupr_admin_page_link( $links ) {
	$page_link = array(
		'<a href="' . admin_url( 'admin.php?page=bp-member-review-settings' ) . '">' . esc_html__( 'Settings', 'bp-member-reviews' ) . '</a>',
	);
	return array_merge( $links, $page_link );
}

/**
 *  Check if buddypress activate.
 */
function bupr_requires_buddypress() {
	if ( ! class_exists( 'Buddypress' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		// deactivate_plugins('buddypress-polls/buddypress-polls.php');
		add_action( 'admin_notices', 'bupr_required_plugin_admin_notice' );
		$activate = filter_input( INPUT_GET, 'activate' );
		unset( $activate );
	}
}

add_action( 'admin_init', 'bupr_requires_buddypress' );
/**
 * Throw an Alert to tell the Admin why it didn't activate.
 *
 * @author wbcomdesigns
 * @since  1.0.0
 */
function bupr_required_plugin_admin_notice() {
	$bpquotes_plugin = esc_html__( 'BuddyPress Member Reviews', 'bp-member-reviews' );
	$bp_plugin       = esc_html__( 'BuddyPress', 'bp-member-reviews' );
	echo '<div class="error"><p>';
	/* translators: %s: */
	echo sprintf( esc_html__( '%1$s is ineffective now as it requires %2$s to be installed and active.', 'bp-member-reviews' ), '<strong>' . esc_html( $bpquotes_plugin ) . '</strong>', '<strong>' . esc_html( $bp_plugin ) . '</strong>' );
	echo '</p></div>';
	if ( null !== filter_input( INPUT_GET, 'activate' ) ) {
		$activate = filter_input( INPUT_GET, 'activate' );
		unset( $activate );
	}
}


/**
 * redirect to plugin settings page after activated
 */

add_action( 'activated_plugin', 'bupr_activation_redirect_settings' );
function bupr_activation_redirect_settings( $plugin ) {

	if ( $plugin == plugin_basename( __FILE__ ) && class_exists( 'Buddypress' ) ) {
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action']  == 'activate' && isset( $_REQUEST['plugin'] ) && $_REQUEST['plugin'] == $plugin) { //phpcs:ignore
			wp_redirect( admin_url( 'admin.php?page=bp-member-review-settings' ) );
			exit;
		}
	}
}

/*
 * Site url translate using WPML
 *
 */
function bupr_site_url( $url ) {
	if ( ! is_admin() && strpos( $url, 'wp-admin' ) == false ) {
		return untrailingslashit( apply_filters( 'wpml_home_url', $url ) );
	} else {
		return $url;
	}
}
