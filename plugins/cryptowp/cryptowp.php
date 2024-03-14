<?php
/**
 * Plugin Name: Crypto Price Widgets - CryptoWP
 * Plugin URI: https://cryptowp.com/
 * Description: The best way to show cryptocurrency coin prices and data on your site. Show Bitcoin, Ethereum, and other coin prices with easy to use widget and shortcode and organize coin data through the stunning Crypto Dashboard.
 * Version: 1.3.3
 * Author: Alex, Kolakube
 * Author URI: https://kolakube.com/
 * Author email: alex@kolakube.com
 * License: GPL-2.0+
 * Requires at least: 3.5
 * Tested up to: 6.0
 * Text Domain: cryptowp
 * Domain Path: /languages
 *
 * This plugin is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see http://www.gnu.org/licenses/.
 */

// Define constants

define( 'CRYPTOWP_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'CRYPTOWP_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'CRYPTOWP_VERSION', '1.3.3' );
if ( ! defined( 'CRYPTOWP_AUTOREFRESH' ) )
	define( 'CRYPTOWP_AUTOREFRESH', ( MINUTE_IN_SECONDS * 15 ) );

/**
 * The CryptoWP class fires the plugin's activation methods
 * and includes all files and resources needed to run the plugin.
 * Think of this class as the control center, and call to access
 * methods from anywhere in the WP environment.
 *
 * @since 1.0
 */

final class CryptoWP {

	/**
	 * Start all required processes and setup initial data on
	 * plugin activation.
	 *
	 * @since 1.0
	 */

	public function __construct() {
		include_once( CRYPTOWP_DIR . 'functions/strings.php' );
		include_once( CRYPTOWP_DIR . 'functions/template-functions.php' );
		if ( is_admin() )
			include_once( CRYPTOWP_DIR . 'admin/admin.php' );
		include_once( CRYPTOWP_DIR . 'admin/processes.php' );
		include_once( CRYPTOWP_DIR . 'admin/widget.php' );
		include_once( CRYPTOWP_DIR . 'functions/shortcode.php' );
		register_activation_hook( __FILE__, array( $this, 'setup' ) );
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'widgets_init', array( $this, 'widgets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Load textdomain, register styles, and register Blocks.
	 *
	 * @since 1.0
	 */

	public function init() {
		load_plugin_textdomain( 'cryptowp' );
		wp_register_style( 'cryptowp', CRYPTOWP_URL . 'assets/css/cryptowp.css', array(), cryptowp_ver( 'assets/css/cryptowp.css' ) );
		$this->updater();
	}

	/**
	 * Once CryptoCompare API generates list of top 100, ensure
	 * we save those to DB on activation. Until then, simply set the
	 * autorefresh transient until next data refresh.
	 *
	 * @since 1.0
	 */

	public function setup() {
		set_transient( 'cryptowp_autorefresh', true, CRYPTOWP_AUTOREFRESH );
	}

	/**
	 * Enqueue frontend assets.
	 *
	 * @since 1.0
	 */

	public function enqueue() {
		wp_enqueue_style( 'cryptowp' );
	}

	/**
	 * Register widgets.
	 *
	 * @since 1.0
	 */

	public function widgets() {
		register_widget( 'cryptowp_widget' );
	}

	/**
	 * Run Updater for version 1.3.
	 *
	 * @since 1.3
	 */

	public function updater() {
		$coins = array();
		$option = get_option( 'cryptowp' );
		if ( ! empty( $option ) && empty( $option['version'] ) || $option['version'] < '1.3' ) {
			if ( ! empty( $option['coins'] ) ) {
				foreach ( $option['coins'] as $count => $coin )
					$coins[$coin['symbol']] = $coin;
				unset( $option['coins'] );
				$option['coins'] = $coins;
			}
			$option['version'] = CRYPTOWP_VERSION;
		}
		update_option( 'cryptowp', $option );
	}

}

new CryptoWP;