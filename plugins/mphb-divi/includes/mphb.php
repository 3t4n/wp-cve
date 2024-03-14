<?php

class MPHB_Divi extends DiviExtension {

	public $name = 'mphb-divi';
	public $gettext_domain;
	public $version;

	private static $instance = null;

	/**
	 * @return MPHB_Divi|null
	 */
	public static function getInstance () {

		if ( ! isset ( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * MPHB_Divi constructor.
	 *
	 * @param string $name
	 * @param array  $args
	 */
	public function __construct( $name = 'mphb-divi', $args = array() ) {

		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir );

		$this->addActions();
		$this->loadIncludes();

		$pluginData		      = get_plugin_data( plugin_dir_path( dirname( __FILE__ ) ) . 'mphb-divi.php' );
		$this->version        = isset( $pluginData[ 'Version' ] )    ? $pluginData[ 'Version' ]    : '';
		$this->gettext_domain = isset( $pluginData[ 'TextDomain' ] ) ? $pluginData[ 'TextDomain' ] : '';

		parent::__construct( $name, $args );
	}

	/**
	 * Add actions
	 */
	public function addActions () {

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );
		add_action( 'customize_preview_init', array( $this, 'enqueueCustomizerScripts' ) );
		add_action( 'et_fb_enqueue_assets', function () {
			wp_enqueue_script( 'mphb-flexslider' );
			wp_enqueue_style( 'mphb-flexslider-css' );
		} );
	}

	/**
	 * Enqueue scripts & styles
	 */
	public function enqueueScripts () {

		wp_enqueue_style( 'mphb-divi-style', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/style.css', array(), $this->version );
	}

	/**
	 * Enqueue customizer scripts
	 */
	public function enqueueCustomizerScripts () {

		wp_enqueue_script( 'mphb-divi-customize-preview', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/customize-preview.js', array( 'jquery', 'customize-preview' ), $this->version );
	}

	/**
	 * Load plugin includes
	 */
	private function loadIncludes () {

		include plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions.php';
	}

	public function hook_et_builder_modules_loaded() {

		if ( file_exists( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/loader.php' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/loader.php';
		}
	}
}

MPHB_Divi::getInstance();