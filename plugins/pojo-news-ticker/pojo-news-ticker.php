<?php
/*
Plugin Name: Pojo News Ticker
Plugin URI: http://pojo.me/
Description: This plugin allows you to add a News Ticker widget to your WordPress site, of which works with Pojo Framework.
Author: Pojo Team
Author URI: http://pojo.me/
Version: 1.0.3
Text Domain: pojo-news-ticker
Domain Path: /languages/
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'POJO_NEWS_TICKER__FILE__', __FILE__ );
define( 'POJO_NEWS_TICKER_BASE', plugin_basename( POJO_NEWS_TICKER__FILE__ ) );
define( 'POJO_NEWS_TICKER_URL', plugins_url( '/', POJO_NEWS_TICKER__FILE__ ) );
define( 'POJO_NEWS_TICKER_ASSETS_PATH', plugin_dir_path( POJO_NEWS_TICKER__FILE__ ) . 'assets/' );
define( 'POJO_NEWS_TICKER_ASSETS_URL', POJO_NEWS_TICKER_URL . 'assets/' );

final class Pojo_News_Ticker {

	/**
	 * @var Pojo_News_Ticker The one true Pojo_News_Ticker
	 * @since 1.0.0
	 */
	private static $_instance = null;

	public function load_textdomain() {
		load_plugin_textdomain( 'pojo-news-ticker', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'pojo-news-ticker' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'pojo-news-ticker' ), '1.0.0' );
	}

	/**
	 * @return Pojo_News_Ticker
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new Pojo_News_Ticker();

		return self::$_instance;
	}

	public function admin_notices() {
		echo '<div class="error"><p>' . sprintf( __( '<a href="%s" target="_blank">Pojo Framework</a> is not active. Please activate any theme by Pojo before you are using "Pojo News Ticker" plugin.', 'pojo-news-ticker' ), 'http://pojo.me/' ) . '</p></div>';
	}

	public function print_update_error() {
		echo '<div class="error"><p>' . sprintf( __( 'Your <a href="%s" target="_blank">Pojo Framework</a> isn\'t updated, please upgrade.', 'pojo-news-ticker' ), 'http://pojo.me/' ) . '</p></div>';
	}

	public function bootstrap() {
		// This plugin for Pojo Themes..
		if ( ! class_exists( 'Pojo_Core' ) ) {
			add_action( 'admin_notices', array( &$this, 'admin_notices' ) );
			return;
		}

		if ( version_compare( '1.2.0', Pojo_Core::instance()->get_version(), '>' ) ) {
			add_action( 'admin_notices', array( &$this, 'print_update_error' ) );
			return;
		}

		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ), 200 );
	}

	public function enqueue_scripts() {
		wp_register_script( 'pojo-news-ticker', POJO_NEWS_TICKER_ASSETS_URL . 'js/app.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'pojo-news-ticker' );
	}

	public function register_widget() {
		if ( ! class_exists( 'Pojo_Widget_Base' ) )
			return;
		
		include( 'widgets/class-pojo-widget-news-ticker.php' );
		register_widget( 'Pojo_Widget_News_Ticker' );
	}

	public function register_widget_builder( $widgets ) {
		$widgets[] = 'Pojo_Widget_News_Ticker';
		return $widgets;
	}
	
	private function __construct() {
		add_action( 'init', array( &$this, 'bootstrap' ), 100 );
		add_action( 'plugins_loaded', array( &$this, 'load_textdomain' ) );

		add_action( 'pojo_widgets_registered', array( &$this, 'register_widget' ) );
		add_action( 'pojo_builder_widgets', array( &$this, 'register_widget_builder' ) );
	}

}

Pojo_News_Ticker::instance();
// EOF