<?php

class QiAddonsForElementor_FrameworkRoot {
	private static $instance;
	private $shortcodes;
	private $image_sizes;

	private function __construct() {
		do_action( 'qi_addons_for_elementor_action_framework_before_framework_root_init' );

		add_action( 'after_setup_theme', array( $this, 'load_shortcode_files' ), 5 );
		add_action( 'after_setup_theme', array( $this, 'load_media_files' ), 5 );
		add_action( 'after_setup_theme', array( $this, 'load_admin_pages_files' ), 5 );
		add_action( 'after_setup_theme', array( $this, 'load_dashboard_widgets_files' ), 5 );
		add_action( 'after_setup_theme', array( $this, 'load_admin_notice_files' ), 5 );

		do_action( 'qi_addons_for_elementor_action_framework_after_framework_root_init' );
	}

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function load_shortcode_files() {
		require_once QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc/shortcodes/include.php';

		$this->shortcodes = new QiAddonsForElementor_Framework_Shortcodes();
	}

	public function load_admin_pages_files() {
		require_once QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc/admin-pages/include.php';
	}

	public function load_dashboard_widgets_files() {
		require_once QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc/dashboard-widgets/include.php';
	}

	public function load_admin_notice_files() {
		require_once QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc/admin-notice/include.php';
	}

	public function load_media_files() {
		require_once QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc/media/include.php';

		$this->image_sizes = new QiAddonsForElementor_Framework_Image_Sizes();
	}

	function get_shortcodes() {
		return $this->shortcodes;
	}

	function add_shortcode( QiAddonsForElementor_Framework_Shortcode $shortcode ) {
		if ( $shortcode ) {
			$this->get_shortcodes()->add_shortcode( $shortcode );
		}

		return $shortcode;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_get_framework_root' ) ) {
	/**
	 * Main instance of Framework Root.
	 *
	 * Returns the main instance of QiAddonsForElementor_FrameworkRoot to prevent the need to use globals.
	 *
	 * @since  1.0
	 * @return QiAddonsForElementor_FrameworkRoot
	 */
	function qi_addons_for_elementor_framework_get_framework_root() {
		return QiAddonsForElementor_FrameworkRoot::get_instance();
	}
}
