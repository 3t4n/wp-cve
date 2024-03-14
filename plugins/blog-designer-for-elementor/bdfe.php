<?php
/**
 * Plugin Name:       Blog Designer For Elementor
 * Plugin URI:        https://theimran.com/blog-designer-for-elementor-page-builder
 * Description:       Blog Designer Has been built for Elementor Page Builder. Blog Designer can be use with any theme from stores. you can design each and every part of the layout.
 * Version:           1.0.8
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Theimran WordPress Shop
 * Author URI:        https://theimran.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       bdfe
 * Domain Path:       /languages
 */

if (!defined('BDFE_TEXT_DOMAIN')) {
	define('BDFE_TEXT_DOMAIN', 'bdfe');
}
if (!defined('BDFE_PLUGIN_URL')) {
	define('BDFE_PLUGIN_URL', plugin_dir_url( __file__ ));
}
if (!defined('BDFE_PLUGIN_PATH')) {
	define('BDFE_PLUGIN_PATH', plugin_dir_path( __file__ ));
}
$getplugindata = get_file_data(__FILE__, array('Version' => 'Version'), false);
$bdfe_version = $getplugindata['Version'];
if (!defined('bdfe_VERSION')) {
	define('bdfe_VERSION', $bdfe_version);
}

require BDFE_PLUGIN_PATH . 'class-gamajo-template-loader.php';
require BDFE_PLUGIN_PATH . 'bdfe-template-loader.php';
require BDFE_PLUGIN_PATH . 'inc/admin/admin.php';
require BDFE_PLUGIN_PATH . 'widgets/about-me.php';

final class BlogDesignerForElementor {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.7';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var BlogDesignerForElementor The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return BlogDesignerForElementor An instance of the class.
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
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );

	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n() {

		load_plugin_textdomain( BDFE_TEXT_DOMAIN );

	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		// Add Plugin actions
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
		// add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );
		add_action( 'wp_enqueue_scripts', [$this, 'bdfe_enqueue_scripts'], 10 );

	}

	public function bdfe_enqueue_scripts(){
		wp_enqueue_style( 'fontawesome', BDFE_PLUGIN_URL . 'assets/css/fontawesome/fontawesome.css' );
		wp_enqueue_style( 'bdfe-style', BDFE_PLUGIN_URL . 'assets/css/style.css' );
		wp_enqueue_script( 'masonry');
		wp_enqueue_script( 'owl-carousel', BDFE_PLUGIN_URL . 'assets/js/owl-carousel.min.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'imagesloaded.pkgd', BDFE_PLUGIN_URL . 'assets/js/imagesloaded.pkgd.min.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'bdfe-main', BDFE_PLUGIN_URL . 'assets/js/main.js', array( 'jquery' ), null, true );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', BDFE_TEXT_DOMAIN ),
			'<strong>' . esc_html__( 'Blog Maker For Elementor', BDFE_TEXT_DOMAIN ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', BDFE_TEXT_DOMAIN ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', BDFE_TEXT_DOMAIN ),
			'<strong>' . esc_html__( 'Elementor Test Extension', BDFE_TEXT_DOMAIN ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', BDFE_TEXT_DOMAIN ) . '</strong>',
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
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', BDFE_TEXT_DOMAIN ),
			'<strong>' . esc_html__( 'Blog Maker For Elementor', BDFE_TEXT_DOMAIN ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', BDFE_TEXT_DOMAIN ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_widgets() {
		// Include Widget files
		require_once( __DIR__ . '/widgets/post-layouts.php' );
		require_once( __DIR__ . '/widgets/hero-slider.php' );

		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \bdfe_Posts_Layouts() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \bdfe_Hero_Slider() );

	}

}

BlogDesignerForElementor::instance();
/**
 * excerpt limit
 */
function bdfe_get_excerpt( $limit, $source = null ) {
	if ( $source == 'content' ? ( $excerpt = get_the_content() ) : ( $excerpt = get_the_excerpt() ) ) {
	}
	$excerpt = preg_replace( ' (\[.*?\])', '', $excerpt );
	$excerpt = strip_shortcodes( $excerpt );
	$excerpt = strip_tags( $excerpt );
	$excerpt = substr( $excerpt, 0, $limit );
	$excerpt = substr( $excerpt, 0, strripos( $excerpt, ' ' ) );
	$excerpt = trim( preg_replace( '/\s+/', ' ', $excerpt ) );
	return $excerpt;
}

/**
 * posted by
 */
if ( ! function_exists( 'bdfe_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function bdfe_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', BDFE_TEXT_DOMAIN ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);
		echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.
	}
endif;

add_action( 'init', 'bdfe_add_image_size' );
function bdfe_add_image_size(){
	add_image_size( 'bdfe-large-thumb', 600, 800 );
	add_image_size( 'bdfe-hero-slider-thumb', 1920, 649 );
}

// bdfe posted on function

if ( ! function_exists( 'bdfe_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function bdfe_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
		}
		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);
		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'posted on %s', 'post date', 'bdfe' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);
		echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.
	}
endif;

if ( ! function_exists( 'bdfe_comment_popuplink' ) ) {
	function bdfe_comment_popuplink() {
		comments_popup_link( esc_html__( 'No Comment', 'bdfe' ), esc_html__( '1 Comment', 'bdfe' ), esc_html__( '% Comments', 'bdfe' ) );
	}
}

function bdfe_add_elementor_widget_categories( $elements_manager ) {

	$elements_manager->add_category(
		'blogmaker',
		[
			'title' => __( 'Blog Designer', BDFE_TEXT_DOMAIN ),
			'icon' => 'fa fa-th',
		]
	);


}
add_action( 'elementor/elements/categories_registered', 'bdfe_add_elementor_widget_categories' );

// add_filter( 'wp_lazy_loading_enabled', '__return_false' );