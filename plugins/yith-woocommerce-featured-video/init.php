<?php
/**
 * Plugin Name: YITH WooCommerce Featured Video
 * Plugin URI: https://yithemes.com/themes/plugins/yith-woocommerce-featured-audio-video-content/
 * Description: <code><strong>YITH WooCommerce Featured Video</strong></code> allows you to set a video or audio instead of the featured image on the single product page. <a href ="https://yithemes.com">Get more plugins for your e-commerce shop on <strong>YITH</strong></a>
 * Version: 1.33.0
 * Author: YITH
 * Author URI: https://yithemes.com/
 * Text Domain: yith-woocommerce-featured-video
 * Domain Path: /languages/
 * WC requires at least: 8.4
 * WC tested up to: 8.6
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH WooCommerce Featured Audio Video Content
 * @version 1.33.0
 */

/*
Copyright 2013  Your Inspiration Themes  (email : plugins@yithemes.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

/**
 * Show error if WooCommerce isn't active
 *
 * @since 1.0.0
 */
function yith_ywcfav_install_woocommerce_admin_notice() {
	?>
		<div class="error">
			<p><?php esc_html_e( 'YITH WooCommerce Featured Video is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-featured-video' ); ?></p>
		</div>
	<?php
}

/**
 * Show error if the premium is active
 *
 * @since 1.0.0
 */
function yith_ywcfav_install_free_admin_notice() {
	?>
		<div class="error">
			<p><?php esc_html_e( 'You can\'t activate the free version of YITH WooCommerce Featured Video while you are using the premium one.', 'yith-woocommerce-featured-video' ); ?></p>
		</div>
	<?php
}

if ( ! defined( 'YWCFAV_VERSION' ) ) {
	define( 'YWCFAV_VERSION', '1.33.0' );
}

if ( ! defined( 'YWCFAV_FREE_INIT' ) ) {
	define( 'YWCFAV_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YWCFAV_FILE' ) ) {
	define( 'YWCFAV_FILE', __FILE__ );
}

if ( ! defined( 'YWCFAV_DIR' ) ) {
	define( 'YWCFAV_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'YWCFAV_URL' ) ) {
	define( 'YWCFAV_URL', plugins_url( '/', __FILE__ ) );
}

if ( ! defined( 'YWCFAV_ASSETS_URL' ) ) {
	define( 'YWCFAV_ASSETS_URL', YWCFAV_URL . 'assets/' );
}

if ( ! defined( 'YWCFAV_TEMPLATE_PATH' ) ) {
	define( 'YWCFAV_TEMPLATE_PATH', YWCFAV_DIR . 'templates/' );
}

if ( ! defined( 'YWCFAV_INC' ) ) {
	define( 'YWCFAV_INC', YWCFAV_DIR . 'includes/' );
}

if ( ! defined( 'YWCFAV_SLUG' ) ) {
	define( 'YWCFAV_SLUG', 'yith-woocommerce-featured-video' );
}


if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

/* Plugin Framework Version Check */
if ( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YWCFAV_DIR . 'plugin-fw/init.php' ) ) {
	require_once YWCFAV_DIR . 'plugin-fw/init.php';
}

yit_maybe_plugin_fw_loader( YWCFAV_DIR );

if ( ! function_exists( 'YITH_Featured_Audio_Video_Init' ) ) {

	/**
	 * Unique access to instance of YITH_WC_Audio_Video class
	 *
	 * @since 1.1.4
	 */
	function YITH_Featured_Audio_Video_Init() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
		load_plugin_textdomain( 'yith-woocommerce-featured-video', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		require_once YWCFAV_INC . 'functions.yith-wc-featured-audio-video.php';
		require_once YWCFAV_INC . 'classes/class.ywcfav-manager.php';
		require_once YWCFAV_INC . 'classes/class.ywcfav-admin.php';
		require_once YWCFAV_INC . 'classes/class.ywcfav-frontend.php';
		require_once YWCFAV_INC . 'classes/class.ywcfav-zoom-magnifier.php';
		require_once YWCFAV_INC . 'classes/class.yith-woocommerce-audio-video-content.php';

		global $YITH_Featured_Audio_Video; // phpcs:ignore WordPress.NamingConventions.ValidVariableName
		$YITH_Featured_Audio_Video = YITH_Featured_Video(); // phpcs:ignore WordPress.NamingConventions.ValidVariableName
	}
}

add_action( 'yith_wc_featured_audio_video_init', 'YITH_Featured_Audio_Video_Init' );

if ( ! function_exists( 'yith_featured_audio_video_install' ) ) {
	/**
	 * Install featured audio video content
	 *
	 * @since 1.1.4
	 */
	function yith_featured_audio_video_install() {

		if ( ! function_exists( 'WC' ) ) {
			add_action( 'admin_notices', 'yith_ywcfav_install_woocommerce_admin_notice' );
		} elseif ( defined( 'YWCFAV_PREMIUM' ) ) {
			add_action( 'admin_notices', 'yith_ywcfav_install_free_admin_notice' );
			deactivate_plugins( plugin_basename( __FILE__ ) );
		} else {
			do_action( 'yith_wc_featured_audio_video_init' );
		}

	}
}

add_action( 'plugins_loaded', 'yith_featured_audio_video_install', 11 );
