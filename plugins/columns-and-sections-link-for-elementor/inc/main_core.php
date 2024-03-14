<?php

namespace ECSL;

use Elementor;
use ECSL\Classes\Helper;
use const ECSL_PATH;

class Plugin {

	public static $instance;

	public $module_manager;

	public static $helper       = null;
	private static $show_notice = true;

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		$this->autoload_classes_register();
		self::$helper = new Helper();

		add_action( 'wp_enqueue_scripts', [ $this, 'ecsl_load_scripts' ] );

		$this->module_manager = new Managers\Module_Manager();


	}


	public function ecsl_load_scripts() {
		wp_enqueue_style( 'ecsl-main-style', ECSL_URL . 'assets/css/main-style' . ECSL_SCRIPT_SUFFIX . '.css', [], ECSL_VERSION );

		wp_enqueue_script(
			'ecsl-main-script',
			ECSL_URL . 'assets/js/main-scripts' . ECSL_SCRIPT_SUFFIX . '.js',
			[
				'jquery',
			],
			ECSL_VERSION,
			true
		);

		//abilita url in front
		if ( is_plugin_active( 'elementor/elementor.php' ) ) {
			wp_localize_script(
				'ecsl-main-script',
				'eae',
				[
					'ajaxurl'     => admin_url( 'admin-ajax.php' ),
					'current_url' => base64_encode( self::$helper->get_current_url_non_paged() ),
					'breakpoints' => Elementor\Core\Responsive\Responsive::get_breakpoints(),
				]
			);
		}
	}


/* autoload classes */
	private function autoload_classes_register() {
		spl_autoload_register( [ __CLASS__, 'autoload_classes' ] );
	}

	public function autoload_classes( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		if ( ! class_exists( $class ) ) {

			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class
				)
			);

			$filename = ECSL_PATH . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include $filename;
			}
		}
	}

}

Plugin::get_instance();
