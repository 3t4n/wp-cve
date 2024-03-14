<?php

namespace SirvElementorWidget;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Plugin {

	private static $_instance = null;

	const VERSION = '1.1.0';
	const MINIMUM_ELEMENTOR_VERSION = '3.3.0';
	const MINIMUM_PHP_VERSION = '5.6.0';

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	public function __construct(){
		if ($this->is_compatible()) {
			add_action('elementor/init', [$this, 'init']);
		}
	}

	public function is_compatible(){
		if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
			add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
			return false;
		}

		// Check for required PHP version
		if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
			add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
			return false;
		}

		return true;
	}


	public function init(){
		//register elementor's components
		add_action('elementor/widgets/register', [$this, 'register_widgets']);
		add_action('elementor/controls/register', [$this, 'register_controls']);

		//register elementor's assets
		add_action('elementor/frontend/after_enqueue_styles', [$this, 'widget_frontend_styles']);
		add_action('elementor/editor/after_enqueue_styles', [$this, 'widget_backend_styles']);
		add_action('elementor/preview/enqueue_scripts', [$this, 'widget_backend_scripts']);
	}


	public function register_widgets( $widgets_manager ){
		require_once(__DIR__ . '/SirvWidget.php');

		$widgets_manager->register(new Widgets\SirvWidget());
	}


	public function register_controls( $controls_manager ){
		require_once(__DIR__ . '/SirvControl.php');

		$controls_manager->register(new Controls\SirvControl());
	}


	public function widget_frontend_styles() {
		wp_register_style( 'sirv-gallery', SIRV_PLUGIN_SUBDIR_URL_PATH . 'css/wp-sirv-gallery.css' );
		wp_enqueue_style('sirv-gallery');
	}


	public function widget_backend_styles(){
		wp_register_style('sirv-elementor-block-css', plugins_url('/assets/css/sirv-elementor-block.css', __FILE__));
		wp_enqueue_style('sirv-elementor-block-css');
	}


	public function widget_backend_scripts() {
		$sirv_js_path = \getValue::getOption('SIRV_JS_FILE');

		wp_register_script('sirv-js', $sirv_js_path, array(), false, true);
		wp_enqueue_script('sirv-js');

		wp_register_script('sirv-inject-js', plugins_url('/assets/js/sirv-inject.js', __FILE__), array('jquery', 'sirv-js'), false, true);
		wp_enqueue_script('sirv-inject-js');
		wp_enqueue_script( 'sirv-gallery-viewer', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/wp-sirv-mv-gallery.js', array('jquery', 'sirv-js'), false);

	}


	public function admin_notice_minimum_elementor_version()
	{

		if (isset($_GET['activate'])) unset($_GET['activate']);

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'Sirv gallery widget'),
			'<strong>' . esc_html__('Sirv gallery elementor\'s widget', 'sirv') . '</strong>',
			'<strong>' . esc_html__('Elementor', 'sirv') . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
	}


	public function admin_notice_minimum_php_version()
	{

		if (isset($_GET['activate'])) unset($_GET['activate']);

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-addon'),
			'<strong>' . esc_html__('Sirv gallery elementor\'s widget', 'sirv') . '</strong>',
			'<strong>' . esc_html__('PHP', 'sirv') . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
	}
}

