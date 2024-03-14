<?php
/**
 * Handles the server side functionality of the Slider Pro Gutenberg block.
 * 
 * @since 4.8.3
 */
class BQW_SliderPro_Block {

	/**
	 * Current class instance.
	 * 
	 * @since 4.8.3
	 * 
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Add initialization logic for the block.
	 *
	 * @since 4.8.3
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Return the current class instance.
	 *
	 * @since 4.8.3
	 * 
	 * @return object The instance of the current class.
	 */
	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register the block using the block.json file.
	 * Register a route that will allow the fetching of some slider data (name and id).
	 *
	 * @since 4.8.3
	 */
	public function init() {
		if ( ! function_exists( 'register_block_type' ) || ! function_exists( 'register_rest_route' ) ) {
			return;
		}

		register_block_type( __DIR__ . '/build' );

		add_action( 'rest_api_init', function() {
			register_rest_route( 'sliderpro/v1', '/sliders', array(
				'method' => 'GET',
				'callback' => array( $this, 'get_sliders' ),
				'permission_callback' => '__return_true'
			));
		} );

		wp_localize_script( 'bqworks-sliderpro-editor-script', 'sp_gutenberg_js_vars', array(
			'admin_url' => admin_url( 'admin.php' )
		));
	}

	/**
	 * Endpoint for the 'sliderpro/v1/sliders' route that returns
	 * the id and name of the sliders.
	 *
	 * @since 4.8.3
	 */
	public function get_sliders( $request ) {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$response = array();

		$sliders = $wpdb->get_results( "SELECT * FROM " . $prefix . "slider_pro_sliders ORDER BY id" );

		foreach ( $sliders as $slider ) {
			$slider_id = $slider->id;
			$slider_name = stripslashes( $slider->name );
			
			$response[ $slider_id ] = $slider_name;
		}
		
		return rest_ensure_response( $response );
	}
}