<?php
/**
 * Renders the slider inside a lightbox.
 * 
 * @since 4.5.0
 */
class BQW_SP_Lightbox_Slider {

	/**
	 * Current class instance.
	 * 
	 * @since 4.5.0
	 * 
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Indicates if the necessary scripts were loaded.
	 * 
	 * @since 4.5.0
	 * 
	 * @var bool
	 */
	private $scripts_loaded = false;

	/**
	 * Current instance of the public Slider Pro class.
	 * 
	 * @since 4.5.0
	 * 
	 * @var object
	 */
	private $sliderpro = null;

	/**
	 * Initialize the functionality.
	 *
	 * @since 4.5.0
	 */
	public function __construct() {
		$this->sliderpro = BQW_SliderPro::get_instance();

		if ( get_option( 'sliderpro_lightbox_sliders' ) === false ) {
			add_option( 'sliderpro_lightbox_sliders', array() );
		}

		add_action( 'wp_ajax_sliderpro_load_lightbox_slider', array( $this, 'ajax_load_lightbox_slider' ) );
		add_action( 'wp_ajax_nopriv_sliderpro_load_lightbox_slider', array( $this, 'ajax_load_lightbox_slider' ) );

		add_shortcode( 'sliderpro_lightbox', array( $this, 'sliderpro_lightbox_shortcode' ) );
	}

	/**
	 * Return the current class instance.
	 *
	 * @since 4.5.0
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
	 * Load the necessary scripts.
	 *
	 * @since 4.5.0
	 */
	protected function load_scripts() {

		// get the plugin slug
		$plugin_slug = $this->sliderpro->get_plugin_slug();

		// load style
		wp_enqueue_style( $plugin_slug . '-lightbox-slider-style' );

		// load scripts
		$this->sliderpro->add_script_to_load( $plugin_slug . '-plugin-script' );
		$this->sliderpro->add_script_to_load( $plugin_slug . '-lightbox-slider-script' );

		wp_localize_script( $plugin_slug . '-lightbox-slider-script', 'sp_js_vars', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		));
	}

	/**
	 * AJAX call for loading the slider.
	 *
	 * Get the slider, based on the indicated 'id', from the list
	 * of stored sliders, then pass the attributes and content of
	 * the slider, which were specified in the shortcode, to the
	 * function that parses the main 'sliderpro' shortcode.
	 *
	 * Return the resulted HTML and JavaScript for the slider.
	 *
	 * @since 4.5.0
	 */
	public function ajax_load_lightbox_slider() {
		$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : -1;

		$lightbox_sliders = get_option( 'sliderpro_lightbox_sliders' );
		$slider = $lightbox_sliders[ $id ];

		$slider_html = $this->sliderpro->sliderpro_shortcode( $slider['atts'], $slider['content'] );
		$slider_js = $this->sliderpro->get_inline_scripts();

		echo $slider_html . $slider_js;

		die();
	}

	/**
	 * Parse the lightbox slider shortcode and store each instance in a list.
	 *
	 * @since 4.5.0
	 */
	public function sliderpro_lightbox_shortcode( $atts, $content = null ) {

		// load the necessary scripts if they were not loaded yet
		if ( $this->scripts_loaded === false ) {
			$this->scripts_loaded = true;
			$this->load_scripts();
		}

		// get the id specified in the shortcode
		$id = isset( $atts['id'] ) ? $atts['id'] : -1;

		// store the values for later use
		$lightbox_sliders = get_option( 'sliderpro_lightbox_sliders' );

		// if the slider is set to not be cached, remove it from the array of stored sliders if it's there
		if ( isset( $lightbox_sliders[ $id ] ) && isset( $atts['allow_cache'] ) && $atts['allow_cache'] === 'false' ) {
			unset( $lightbox_sliders[ $id ] );
		}

		// if the slider is not found in the array, add it
		if ( ! isset( $lightbox_sliders[ $id ] ) ) {
			$lightbox_sliders[ $id ] = array(
				'atts' => $atts,
				'content' => $content
			);

			update_option( 'sliderpro_lightbox_sliders', $lightbox_sliders );
		}

		return false;
	}
}