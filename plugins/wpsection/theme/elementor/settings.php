<?php
/**
 * wpsection Elementor Extention
 */

final class wpsection_Elementor_Extension {

	const VERSION = '1.0.0';

	const MINIMUM_ELEMENTOR_VERSION = '2.8.2';

	const MINIMUM_PHP_VERSION = '7.0';

	private static $_instance = null;

	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	public function __construct() {

		add_action( 'plugins_loaded', [ $this, 'init' ] );

	}


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
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_widget_categories' ) );
		add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'elementor_assets'] );
	}


	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'wpsection' ),
			'<strong>' . esc_html__( 'wpsection Elementor Extension', 'wpsection' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'wpsection' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}


	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'wpsection' ),
			'<strong>' . esc_html__( 'wpsection Elementor Extension', 'wpsection' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'wpsection' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'wpsection' ),
			'<strong>' . esc_html__( 'wpsection Elementor Extension', 'wpsection' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'wpsection' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

public function add_elementor_widget_categories( $elements_manager ) {
    
    $elements_manager->add_category(
        'wpsection_category',
        [
            'title' => __(  WPSECTION_PLUGIN_NAME, 'wpsection' ),
            'icon'  => 'fa fa-plug',
        ]
    );
    $elements_manager->add_category(
        'wpsection_shop',
        [
            'title' => __(  'WPS WooCommerce', 'wpsection' ),
            'icon'  => 'fa fa-plug',
        ]
    );

    $elements_manager->add_category(
        'theme_category',
        [
            'title' => __( WPSECTION_THEME_NAME, 'wpsection' ),
            'icon'  => 'fa fa-plug',
        ]
    );
}

/*
	public function elementor_assets() {
		global $plugin_root;
		wp_enqueue_script( "wpsection-elementor-js", $plugin_root . 'assets/public/js/wpsection-elementor.js', array( 'jquery' ), time(), true );
	}
*/	

public function elementor_assets() {
    add_action('elementor/editor/after_enqueue_styles', array($this, 'enqueue_elementor_script'));
}

public function enqueue_elementor_script() {
    wp_enqueue_script("wpsection-elementor-js", plugin_dir_url(__FILE__) . 'assets/public/js/wpsection-elementor.js', array('jquery'), time(), true);
}
	
	
	
public function init_widgets() {

	//Default Theme Widget
	$widget_directory = get_template_directory() . '/includes/thirdparty/plugins/elementor_widget/';
	$widget_files1 = glob($widget_directory . '*.php');

    foreach ( $widget_files1 as $widget_file ) {
        include_once $widget_file;
    }

	//Default Theme WooCommerce Widget
	$widget_directory3 = get_template_directory() . '/includes/thirdparty/plugins/elementor_widget/woocomerce/';
	$widget_files3 = glob($widget_directory3 . '*.php');
    foreach ( $widget_files3 as $widget_file3 ) {
        include_once $widget_file3;
    }

	
	//Default WPSECTION  Widget
	 $widget_files2 = glob( __DIR__ . '/widget/*.php' );

    foreach ( $widget_files2 as $widget_file2 ) {
        include_once $widget_file2;
    }
	

			// Default WPSECTION Widget
		$widget_files4 = glob( plugin_dir_path( __FILE__ ) . '/woocomerce/*.php' );

		foreach ( $widget_files4 as $widget_file4 ) {
		    include_once $widget_file4;
		}
	
}
	
	
}

wpsection_Elementor_Extension::instance();





