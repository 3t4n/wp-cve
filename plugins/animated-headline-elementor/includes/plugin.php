<?php

namespace Elementor_Animated_Headline_Addon;

use Animated_Headline_Elementor_Widget;
use Elementor\Widgets_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Plugin class.
 *
 * The main class that initiates and runs the addon.
 *
 * @since 1.0.0
 */
final class Elementor_Animated_Headline {

	/**
	 * Addon Version
	 *
	 * @since 1.0.0
	 * @var string The addon version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @var string Minimum Elementor version required to run the addon.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.7.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 * @var string Minimum PHP version required to run the addon.
	 */
	const MINIMUM_PHP_VERSION = '7.3';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 * @var Elementor_Animated_Headline The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Elementor_Animated_Headline An instance of the class.
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * Perform some compatibility checks to make sure basic requirements are meet.
	 * If all compatibility checks pass, initialize the functionality.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		if ( $this->is_compatible() ) {
			add_action( 'elementor/init', [ $this, 'init' ] );
		}

	}

	/**
	 * Compatibility Checks
	 *
	 * Checks whether the site meets the addon requirement.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function is_compatible() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );

			return false;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );

			return false;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );

			return false;
		}

		return true;

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'animated-headline-elementor' ),
			'<strong>' . esc_html__( 'Animated Headline', 'animated-headline-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'animated-headline-elementor' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'animated-headline-elementor' ),
			'<strong>' . esc_html__( 'Animated Headline', 'animated-headline-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'animated-headline-elementor' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'animated-headline-elementor' ),
			'<strong>' . esc_html__( 'Animated Headline', 'animated-headline-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'animated-headline-elementor' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Initialize
	 *
	 * Load the addons functionality only after Elementor is initialized.
	 *
	 * Fired by `elementor/init` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {

		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		add_action( 'elementor/elements/categories_registered', [$this, 'custom_animated_headline_widget_categories' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'animated_heading_style' ] );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'animated_heading_script' ] );
		// Register widget scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'animated_heading_widget_scripts' ] );

	}

	//Define editor script
	public function animated_heading_widget_scripts() {
		wp_register_script( 'animated-headline-elementor', plugins_url( '../assets/js/editor/animated-headline.js', __FILE__ ), [ 'jquery' ], false, true );
	}


	//CSS
	public function animated_heading_style() {
		wp_enqueue_style( 'animated-headline-font', '//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700' );
		wp_enqueue_style( 'animated-headline-reset-css', plugin_dir_url( __FILE__ ) . '../assets/css/reset.css' );
		wp_enqueue_style( 'animated-headline-style-css', plugin_dir_url( __FILE__ ) . '../assets/css/style.css' );
	}

	//JS
	public function animated_heading_script() {
		wp_enqueue_script( 'animated-headline-main-js', plugin_dir_url( __FILE__ ) . '../assets/js/main.js', array( 'jquery' ), '1.0.0', true );
	}


	/**
	 * Register Widgets
	 *
	 * Load widgets files and register new Elementor widgets.
	 *
	 * Fired by `elementor/widgets/register` action hook.
	 *
	 * @param Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {

		require_once( __DIR__ . '/widgets/animated-headline-widget.php' );

		$widgets_manager->register( new Animated_Headline_Elementor_Widget() );

	}
	/**
	 * Register Category
	 */

	function custom_animated_headline_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'animated_headline-category',
			[
				'title' => esc_html__( 'Animated Headline', 'animated-headline-elementor' ),
				'icon' => 'fa fa-plug',
			]
		);
	}


}