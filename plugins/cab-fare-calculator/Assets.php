<?php

/**
 * Assets handler Class.
 */
class TBLight_Assets {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		 add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Get the list of scripts
	 *
	 * @return array
	 */
	public function get_scripts() {
		 $elsettings = BookingHelper::config();

		return array(
			'tblight-chosen-script'           => array(
				'src'     => TBLIGHT_ASSETS . '/js/chosen.jquery.min.js',
				'version' => '1.8.7',
			),
			'tblight-moment-script'           => array(
				'src'     => TBLIGHT_ASSETS . '/js/moment-with-locales.min.js',
				'version' => '2.29.3',
			),
			'tblight-bootstrap-popper-script' => array(
				'src'     => TBLIGHT_ASSETS . '/js/popper.min.js',
				'version' => '2.10.2',
			),
			'tblight-bootstrap-script'        => array(
				'src'     => TBLIGHT_ASSETS . '/js/bootstrap.min.js',
				'version' => '5.1.3',
			),
			'tblight-datetimepicker-script'   => array(
				'src'     => TBLIGHT_ASSETS . '/js/bootstrap-datetimepicker.min.js',
				'version' => '4.17.49',
			),
			'tblight-google-maps-script'      => array(
				'src'     => 'https://maps.googleapis.com/maps/api/js?v=3&language=en&libraries=geometry,places&key=' . trim( $elsettings->api_key ),
				'version' => 1,
			),
			'tblight-core-script'             => array(
				'src'     => TBLIGHT_ASSETS . '/js/tbfcore.min.js',
				'version' => filemtime( TBLIGHT_ASSETS_PATH . '/js/tbfcore.min.js' ),
			),
			'tblight-googlegeo-script'        => array(
				'src'     => TBLIGHT_ASSETS . '/js/googleGeo.min.js',
				'version' => filemtime( TBLIGHT_ASSETS_PATH . '/js/googleGeo.min.js' ),
			),
			'tblight-main-script'             => array(
				'src'     => TBLIGHT_ASSETS . '/js/main.min.js',
				'version' => filemtime( TBLIGHT_ASSETS_PATH . '/js/main.min.js' ),
			),
		);
	}

	/**
	 * Get the list of styles
	 *
	 * @return array
	 */
	public function get_styles() {
		return array(
			'tblight-bootstrap-style'                => array(
				'src'     => TBLIGHT_ASSETS . '/css/bootstrap.min.css',
				'version' => '5.1.3',
			),
			'tblight-bootstrap-datetimepicker-style' => array(
				'src'     => TBLIGHT_ASSETS . '/css/bootstrap-datetimepicker.min.css',
				'version' => '4.17.49',
			),
			'tblight-main-style'                     => array(
				'src'     => TBLIGHT_ASSETS . '/css/main.min.css',
				'version' => filemtime( TBLIGHT_ASSETS_PATH . '/css/main.min.css' ),
			),
			'tblight-media-style'                    => array(
				'src'     => TBLIGHT_ASSETS . '/css/media.min.css',
				'version' => filemtime( TBLIGHT_ASSETS_PATH . '/css/media.min.css' ),
			),
			'tblight-chosen-style'                   => array(
				'src'     => TBLIGHT_ASSETS . '/css/chosen.min.css',
				'version' => '1.8.7',
			),
			'tblight-fontawesome-style'              => array(
				'src'     => TBLIGHT_ASSETS . '/css/custom-font-awesome.min.css',
				'version' => '6.1.1',
			),
		);
	}

	/**
	 * Register scripts and styles
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		$scripts = $this->get_scripts();
		$styles  = $this->get_styles();

		foreach ( $scripts as $handle => $script ) {
			$deps = isset( $script['deps'] ) ? $script['deps'] : false;
			wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
		}

		foreach ( $styles as $handle => $style ) {
			$deps = isset( $style['deps'] ) ? $style['deps'] : false;
			wp_register_style( $handle, $style['src'], array(), $style['version'] );
		}

		wp_localize_script(
			'tblight-core-script',
			'tblightAjax',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'tblight-ajax-nonce' ),
			)
		);
	}
}
