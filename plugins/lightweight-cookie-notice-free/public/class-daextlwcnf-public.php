<?php

/*
 * This class should be used to work with the public side of wordpress.
 */

class Daextlwcnf_Public {

	protected static $instance = null;
	private $shared = null;

	private function __construct() {

		//Assign an instance of the plugin shared class
		$this->shared = Daextlwcnf_Shared::get_instance();

		//Load public css
		add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));

		//Load public js
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	}

	/*
	 * Creates an instance of this class.
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	/**
	 * Enqueue style.
	 */
	public function enqueue_styles() {

		//Adds the Google Fonts if they are defined in the "Google Font URL" option.
		if ( strlen( trim( get_option( $this->shared->get( 'slug' ) . "_google_font_url" ) ) ) > 0 ) {
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-google-font',
				esc_url( get_option( $this->shared->get( 'slug' ) . '_google_font_url' ) ), false );
		}

	}

	/**
	 * Enqueue scripts.
	 */
	public function enqueue_scripts() {

		if ( intval( get_option( $this->shared->get( 'slug' ) . '_assets_mode' ), 10 ) === 0 ) {

			//Development
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-daextlwcnf-polyfills',
				$this->shared->get( 'url' ) . 'public/assets/js/daextlwcnf-polyfills.js', array(),
				$this->shared->get( 'ver' ), true );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-daextlwcnf-utility',
				$this->shared->get( 'url' ) . 'public/assets/js/daextlwcnf-utility.js', array(),
				$this->shared->get( 'ver' ), true );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-daextlwcnf-revisit-cookie-consent',
				$this->shared->get( 'url' ) . 'public/assets/js/daextlwcnf-revisit-cookie-consent.js', array(),
				$this->shared->get( 'ver' ), true );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-daextlwcnf-cookie-settings',
				$this->shared->get( 'url' ) . 'public/assets/js/daextlwcnf-cookie-settings.js', array(),
				$this->shared->get( 'ver' ), true );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-daextlwcnf-cookie-notice',
				$this->shared->get( 'url' ) . 'public/assets/js/daextlwcnf-cookie-notice.js', array(),
				$this->shared->get( 'ver' ), true );

			$partial_script_handle = 'daextlwcnf-cookie-notice';

		} else {

			//Production
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-general',
				$this->shared->get( 'url' ) . 'public/assets/js/production/general.js',
				array(), $this->shared->get( 'ver' ), true );

			$partial_script_handle = 'general';

		}

		//Generate the array that will be passed to wp_localize_script()
		$php_data = array(
			'nonce'               => wp_create_nonce( "daextlwcnf" ),
			'ajaxUrl'             => admin_url( 'admin-ajax.php' ),
		);

		//Make PHP data available to the JavaScript part in the DAEXTLWCNF_PHPDATA object
		wp_localize_script( $this->shared->get( 'slug' ) . '-' . $partial_script_handle, 'DAEXTLWCNF_PHPDATA',
			$php_data );

		$initialization_script = $this->shared->generate_initialization_script();
		if($initialization_script !== false){
			wp_add_inline_script( $this->shared->get( 'slug' ) . '-' . $partial_script_handle, $initialization_script, 'after' );
		}

	}

}