<?php
/**
 * Plugin Name: Advanced Image Comparison for Elementor
 * Description: Advanced Image Comparison for elementor wordpress plugin
 * Plugin URI:  https://wpcreativeidea.com/
 * Version:     2.0.2
 * Author:      Md.Ruhel Khan
 * Author URI:  https://wpcreativeidea.com/home
 * Text Domain: advanced-image-comparison-for-elementor
 */

define('AIC_DIR_FILE', __FILE__);
define('AIC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('AIC_LITE', 'advancedImageComparisonLite');
define('AIC_PLUGIN_VERSION', '2.0.2');

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Advanced Image Comparison Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 2.0.2
 */
final class AdvancedImageComparison 
{

	/**
	 * Plugin Version
	 *
	 * @since 2.0.2
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '2.0.2';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 2.0.2
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.6.7';

	/**
	 * Minimum PHP Version
	 *
	 * @since 2.0.2
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Instance
	 *
	 * @since 2.0.2
	 *
	 * @access private
	 * @static
	 *
	 * @var AdvancedImageComparison 
	 * The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 2.0.2
	 *
	 * @access public
	 * @static
	 *
	 * @return AdvancedImageComparison 
	 * An instance of the class.
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
	 * @since 2.0.2
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ] );

	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 2.0.2
	 *
	 * @access public
	 */
	public function i18n() {

		load_plugin_textdomain( 'advanced-image-comparison-for-elementor' );
	}

	/**
	 * On Plugins Loaded
	 *
	 * Checks if Elementor has loaded, and performs some compatibility checks.
	 * If All checks pass, inits the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 2.0.2
	 *
	 * @access public
	 */
	public function on_plugins_loaded() {

		if ( $this->is_compatible() ) {
			add_action( 'elementor/init', [ $this, 'init' ] );
		}
	
		add_action( 'admin_notices', [$this, 'aic_admin_Notice'] );
	}
	
	public function aic_admin_Notice() {
		//get the current screen
		$screen = get_current_screen();
		//Checks if settings updated 
		if ( $screen->id == 'dashboard' ||  $screen->id == 'plugins' ) {
			?>
				<div class="notice notice-success is-dismissible">
					<p>
						<?php _e('Congratulations! you have installed "Advanced Image Comparison" for elementor plugin, Please rating this plugin.', 'advanced-image-comparison-for-elementor'); ?>
						<em><a href="https://wordpress.org/support/plugin/advanced-image-comparison-for-elementor/reviews/#new-post" target="_blank">Rating</a></em>
					</p>
				</div>
			<?php
		}
	}


	/**
	 * Compatibility Checks
	 *
	 * Checks if the installed version of Elementor meets the plugin's minimum requirement.
	 * Checks if the installed PHP version meets the plugin's minimum requirement.
	 *
	 * @since 2.0.2
	 *
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
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 2.0.2
	 *
	 * @access public
	 */
	public function init() {
	
		$this->loadTextDomain();

		// Add Plugin actions
		add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
		
		add_action('elementor/frontend/after_enqueue_styles', function() {
			wp_enqueue_style( 'aic-image-comparison', plugin_dir_url( __FILE__ ). 'assets/css/aic_image_comparison.css', array(), AIC_PLUGIN_VERSION);
			wp_enqueue_style( 'aic-twentytwenty', plugin_dir_url( __FILE__ ). 'assets/css/twentytwenty.css', array(), AIC_PLUGIN_VERSION);
		});

		// after_enqueue_scripts
		add_action('elementor/frontend/after_enqueue_scripts', function() {
			wp_enqueue_script( 'aic-move-js', plugin_dir_url( __FILE__ ). 'assets/js/jquery.event.move.js', array('jquery'), AIC_PLUGIN_VERSION);
			wp_enqueue_script( 'aic-twentytwenty-js', plugin_dir_url( __FILE__ ). 'assets/js/jquery.twentytwenty.js', array('jquery'), AIC_PLUGIN_VERSION);
			wp_enqueue_script( 'aic-custom-js', plugin_dir_url( __FILE__ ). 'assets/js/custom.js', array('jquery'), AIC_PLUGIN_VERSION);
		});
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 2.0.2
	 *
	 * @access public
	 */
	public function init_widgets($widgets_manager) {
		
		// Include Widget files
		require_once( __DIR__ . '/widgets/AICImageComparisonWidget.php' );

		// Register widget
		$widgets_manager->register( new AIC\Classes\Widgets\AICImageComparisonWidget() );
	}

	public function loadTextDomain()
    {
        load_plugin_textdomain('advanced-image-comparison-for-elementor', false, basename(dirname(__FILE__)) . '/languages');
	}
	
	
	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 2.0.2
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'advanced-image-comparison-for-elementor' ),
			'<strong>' . esc_html__( 'Advanced Image Comparison', 'advanced-image-comparison-for-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'advanced-image-comparison-for-elementor' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 2.0.2
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'advanced-image-comparison-for-elementor' ),
			'<strong>' . esc_html__( 'Advanced Image Comparison', 'advanced-image-comparison-for-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'advanced-image-comparison-for-elementor' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 2.0.2
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'advanced-image-comparison-for-elementor' ),
			'<strong>' . esc_html__( 'Advanced Image Comparison', 'advanced-image-comparison-for-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'advanced-image-comparison-for-elementor' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

}

AdvancedImageComparison::instance();