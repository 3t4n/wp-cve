<?php
/**
 * Slider Pro public class.
 * 
 * @since 4.0.0
 */
class BQW_SliderPro {

	/**
	 * Current version of the Slider Pro plugin.
	 * 
	 * @since 4.0.0
	 * 
	 * @var string
	 */
	const VERSION = '4.8.8';

	/**
	 * Plugin slug.
	 * 
	 * @since 4.0.0
	 * 
	 * @var string
	 */
	protected $plugin_slug = 'sliderpro';

	/**
	 * Current class instance.
	 * 
	 * @since 4.0.0
	 * 
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Scripts to load.
	 *
	 * Script id's are added to this array when the slider is rendered,
	 * and then the list of scripts is enqueued when the wp_footer action is called.
	 * 
	 * @since 4.0.0
	 * 
	 * @var array
	 */
	protected $scripts_to_load = array();

	/**
	 * JavaScript output.
	 *
	 * The JavaScript output of all sliders loaded on the page is stored
	 * in this variable, and when wp_footer is called, the result is printed.
	 * 
	 * @since 4.0.0
	 * 
	 * @var string
	 */
	protected $js_output = '';

	/**
	 * Indicates if stylesheets need to be loaded.
	 * 
	 * @since 4.0.0
	 * 
	 * @var bool
	 */
	protected $styles_loaded = false;

	/**
	 * Indicates if stylesheets were checked.
	 * 
	 * @since 4.0.0
	 * 
	 * @var bool
	 */
	protected $styles_checked = false;

	/**
	 * Initialize the Slider Pro plugin.
	 *
	 * @since 4.0.0
	 */
	private function __construct() {
		// load the translation
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// register the public CSS and JS files
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );

		// when the actions are called enqueue the necessary CSS and JS files
		add_action( 'wp_enqueue_scripts', array( $this, 'load_styles' ) );
		add_action( 'wp_footer', array( $this, 'load_scripts' ) );

		add_action( 'wp_print_footer_scripts', array( $this, 'print_inline_scripts' ) );

		// register the shortcodes
		add_shortcode( 'sliderpro', array( $this, 'sliderpro_shortcode' ) );
		add_shortcode( 'sliderpro_slide', array( $this, 'sliderpro_slide_shortcode' ) );
		add_shortcode( 'sliderpro_slide_element', array( $this, 'sliderpro_slide_element_shortcode' ) );
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since 4.0.0
	 * 
	 * @return string The plugin slug.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return the current class instance.
	 *
	 * @since 4.0.0
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
	 * Return the current class instance.
	 *
	 * @since 4.0.0
	 */
	public function load_plugin_textdomain() {
		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, 'sliderpro/languages/' );
	}

	/**
	 * Register the public CSS files.
	 *
	 * @since 4.0.0
	 */
	public function register_styles() {
		if ( get_option( 'sliderpro_load_unminified_scripts' ) == true ) {
			wp_register_style( $this->plugin_slug . '-plugin-style', plugins_url( 'public/assets/css/slider-pro.css', dirname( __FILE__ ) ), array(), self::VERSION );
			wp_register_style( $this->plugin_slug . '-lightbox-style', plugins_url( 'public/assets/libs/fancybox/jquery.fancybox.css', dirname( __FILE__ ) ), array(), self::VERSION );
		} else {
			wp_register_style( $this->plugin_slug . '-plugin-style', plugins_url( 'public/assets/css/slider-pro.min.css', dirname( __FILE__ ) ), array(), self::VERSION );
			wp_register_style( $this->plugin_slug . '-lightbox-style', plugins_url( 'public/assets/libs/fancybox/jquery.fancybox.min.css', dirname( __FILE__ ) ), array(), self::VERSION );
		}
		
		wp_register_style( $this->plugin_slug . '-lightbox-slider-style', plugins_url( 'public/assets/css/lightbox-slider.css', dirname( __FILE__ ) ), array(), self::VERSION );
	}

	/**
	 * Register the public JS files.
	 *
	 * @since 4.0.0
	 */
	public function register_scripts() {
		if ( get_option( 'sliderpro_load_unminified_scripts' ) == true ) {
			wp_register_script( $this->plugin_slug . '-plugin-script', plugins_url( 'public/assets/js/jquery.sliderPro.js', dirname( __FILE__ ) ), array( 'jquery' ), self::VERSION );
			wp_register_script( $this->plugin_slug . '-lightbox-slider-script', plugins_url( 'public/assets/js/lightbox-slider.js', dirname( __FILE__ ) ), array(), self::VERSION );
			wp_register_script( $this->plugin_slug . '-lightbox-script', plugins_url( 'public/assets/libs/fancybox/jquery.fancybox.js', dirname( __FILE__ ) ), array(), self::VERSION );
		} else {
			wp_register_script( $this->plugin_slug . '-plugin-script', plugins_url( 'public/assets/js/jquery.sliderPro.min.js', dirname( __FILE__ ) ), array( 'jquery' ), self::VERSION );
			wp_register_script( $this->plugin_slug . '-lightbox-slider-script', plugins_url( 'public/assets/js/lightbox-slider.min.js', dirname( __FILE__ ) ), array(), self::VERSION );
			wp_register_script( $this->plugin_slug . '-lightbox-script', plugins_url( 'public/assets/libs/fancybox/jquery.fancybox.min.js', dirname( __FILE__ ) ), array(), self::VERSION );
		}
	}

	/**
	 * Add script id's to the list of scripts to be loaded when wp_footer is called.
	 *
	 * @since 4.0.0
	 * 
	 * @param string $handle An id of the script to load.
	 */
	public function add_script_to_load( $handle ) {
		if ( in_array( $handle, $this->scripts_to_load ) === false ) {
			$this->scripts_to_load[] = $handle;
		}
	}

	/**
	 * Load the slider data from the database.
	 *
	 * @since 4.0.0
	 * 
	 * @param  int        $id The id of the slider.
	 * @return array|bool     An array containing the slider data, or false, if the specified id doesn't exist in the database.
	 */
	public function load_slider( $id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'slider_pro_sliders';

		$result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d ORDER BY id", $id ), ARRAY_A );

		if ( ! is_null( $result ) ) {
			return $result;
		} else {
			return false;	
		}
	}

	/**
	 * Load each slide's data from the database.
	 *
	 * @since 4.0.0
	 * 
	 * @param  int        $id The id of the slider.
	 * @return array|bool     An array containing each slide's data, or false, if the specified id doesn't exist in the database.
	 */
	public function load_slides( $id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'slider_pro_slides';
		$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE slider_id = %d ORDER BY position", $id ), ARRAY_A );

		if ( ! is_null( $result ) ) {
			return $result;
		} else {
			return false;	
		}
	}

	/**
	 * Load each layer's data from the database.
	 *
	 * @since 4.0.0
	 * 
	 * @param  int        $id The id of the slider.
	 * @return array|bool     An array containing each layer's data, or false, if the specified id doesn't exist in the database.
	 */
	public function load_layers( $id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'slider_pro_layers';
		$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE slide_id = %d", $id ), ARRAY_A );

		if ( ! is_null( $result ) ) {
			return $result;
		} else {
			return false;	
		}
	}

	/**
	 * Return the data of the slider.
	 *
	 * It loads the slider, slides and layers data from the database and formats it into
	 * and a single array.
	 *
	 * @since 4.0.0
	 * 
	 * @param  int   $id The id of the slider.
	 * @return array     An array containing all the slider's database data.
	 */
	public function get_slider( $id ) {
		$slider = array();
		$slider_raw = $this->load_slider( $id );

		if ( $slider_raw === false ) {
			return false;
		}

		$slider['id'] = $slider_raw['id'];
		$slider['name'] = $slider_raw['name'];
		$slider['settings'] = json_decode( stripslashes( $slider_raw['settings'] ), true );
		$slider['panels_state'] = json_decode( stripslashes( $slider_raw['panels_state'] ), true );
		
		$slides_raw = $this->load_slides( $id );

		if ( $slides_raw !== false ) {
			$slider['slides'] = array();

			foreach ( $slides_raw as $slide_raw ) {
				$slide = $slide_raw;
				$slide['settings'] = json_decode( stripslashes( $slide_raw['settings'] ), true );
				$layers_raw = $this->load_layers( $slide_raw['id'] );

				if ( $layers_raw !== false ) {
					$slide['layers'] = array();

					foreach ( $layers_raw as $layer_raw ) {
						$layer = $layer_raw;
						$layer['settings'] = json_decode( stripslashes( $layer_raw['settings'] ), true );

						array_push( $slide['layers'], $layer );
					}

					usort( $slide['layers'], array( $this, 'sort_layers_by_position' ) );
				}

				array_push( $slider['slides'], $slide );
			}
		}

		return BQW_SliderPro_Validation::validate_slider_data( $slider );
	}

	/**
	 * Utility function for sorting a slide's layers
	 *
	 * @since 4.0.0
	 * 
	 * @param  array $layer1 A layer array.
	 * @param  array $layer2 Another layer array.
	 * @return int           The difference between the layers' position.
	 */
	public function sort_layers_by_position( $layer1, $layer2 ) {
		return $layer1['position'] - $layer2['position'];
	}

	/**
	 * Return the HTML markup of the slider.
	 *
	 * Also, retrieves the JavaScript output of the slider and CSS and JavaScript files that need to be loaded.
	 *
	 * @since 4.0.0
	 * 
	 * @param  array   $slider_data An array containing the slider's data.
	 * @param  boolean $allow_cache    Indicates whether or not the output will be cached.
	 * @return string                  The HTML code that needs to be printed for the slider.
	 */
	public function output_slider( $slider_data, $allow_cache = true ) {
		$slider_data = apply_filters( 'sliderpro_data', $slider_data, $slider_data['id'] );

		$slider = new BQW_SP_Slider_Renderer( $slider_data );

		$html_output = $slider->render();

		if ( strpos( $html_output, 'sp-no-slides' ) !== false ) {
			return $html_output;
		}

		$js_output = $slider->render_js();
		$this->js_output .= $js_output;
		$css_dependencies = $slider->get_css_dependencies();
		$js_dependencies = $slider->get_js_dependencies();

		foreach ( $css_dependencies as $css_dependency ) {
			wp_enqueue_style( $this->plugin_slug . '-' . $css_dependency . '-style' );
		}

		foreach ( $js_dependencies as $js_dependency ) {
			$this->add_script_to_load( $this->plugin_slug . '-' . $js_dependency . '-script' );
		}

		if ( $allow_cache === true ) {
			$slider_cache = array(
				'html_output' => $html_output,
				'js_output' => $js_output,
				'css_dependencies' => $css_dependencies,
				'js_dependencies' => $js_dependencies
			);

			$plugin_settings = BQW_SliderPro_Settings::getPluginSettings();
			$cache_time = 60 * 60 * floatval( get_option( 'sliderpro_cache_expiry_interval', $plugin_settings['cache_expiry_interval']['default_value'] ) );
			
			set_transient( 'sliderpro_cache_' . $slider_data['id'], $slider_cache, $cache_time );
		}

		return $html_output;
	}

	/**
	 * Load the necessary CSS files or inline code.
	 *
	 * It checks to see if the plugin is set to load the CSS in all pages or only on the homepage,
	 * then it checks to see if the slider shortcode exists in the currently viewed post(s), then it checks
	 * if the slider is added in a widget.
	 *
	 * If either of these checks return a positive result, the slider's CSS file will be loaded.
	 *
	 * Then, it also loads the custom CSS, either from a file or from the database and print it as inline CSS.
	 * 
	 * @since 4.0.0
	 */
	public function load_styles() {
		if ( is_admin() ) {
			return;
		}

		$this->styles_checked = true;

		global $posts;
		
		if ( $this->styles_loaded === false && ( $load_stylesheets = get_option( 'sliderpro_load_stylesheets' ) ) !== false ) {
			if ( ( $load_stylesheets === 'all' ) || ( $load_stylesheets === 'homepage' && ( is_home() || is_front_page() ) ) ) {
				$this->styles_loaded = true;
			}
		}

		if ( $this->styles_loaded === false && isset( $posts ) && ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				if ( strpos( $post->post_content, '[sliderpro' ) !== false ) {
					$this->styles_loaded = true;
				}
			}
		}

		if ( $this->styles_loaded === false && is_active_widget( false, false, 'bqw-sliderpro-widget' ) ) {
			$this->styles_loaded = true;
		}

		if ( $this->styles_loaded === true ) {
			wp_enqueue_style( $this->plugin_slug . '-plugin-style' );

			if ( get_option( 'sliderpro_is_custom_css') == true ) {
				wp_add_inline_style( $this->plugin_slug . '-plugin-style', stripslashes( get_option( 'sliderpro_custom_css' ) ) );
			}

			do_action( 'sliderpro_enqueue_styles', $this->plugin_slug . '-plugin-style' );
		}
	}

	/**
	 * Load the scripts added to the list of scripts that need to be loaded, enqueues them,
	 * and then it prints the inline JavaScrip code that instantiates all the sliders on the page.
	 * 
	 * @since 4.0.0
	 */
	public function load_scripts() {
		$load_js_in_all_pages = get_option( 'sliderpro_load_js_in_all_pages', false );

		if ( empty( $this->scripts_to_load ) && $load_js_in_all_pages != true ) {
			return;
		} else if ( empty( $this->scripts_to_load ) && $load_js_in_all_pages == true ) {
			$this->add_script_to_load( $this->plugin_slug . '-plugin-script' );
		}

		foreach ( $this->scripts_to_load as $key => $value ) {
			if ( is_numeric( $key ) ) {
				wp_enqueue_script( $value );
			} else if ( is_string( $key ) ) {
				wp_enqueue_script( $key, $value );
			}
		}

		do_action( 'sliderpro_enqueue_scripts' );
	}

	/**
	 * Return the inline JavaScript code for all sliders on the page.
	 *
	 * @since 4.0.0
	 * 
	 * @return string The inline JavaScript.
	 */
	public function get_inline_scripts() {
		if ( $this->js_output === '' ) {
			return;
		}
		
		$inline_js = "\r\n" . '<script type="text/javascript">' .
					"\r\n" . 'if (window.jQuery && window.jQuery.fn.sliderPro) {' .
					"\r\n" . '	initSliderPro();' .
					"\r\n" . '} else {' .
					"\r\n" . '	const initSliderProTimer = setInterval(() => {' .
					"\r\n" . '		if (window.jQuery && window.jQuery.fn.sliderPro) {' .
					"\r\n" . '			initSliderPro();' .
					"\r\n" . '			clearInterval(initSliderProTimer);' .
					"\r\n" . '		}' .
					"\r\n" . '	}, 100);' .
					"\r\n" . '}' . "\r\n" .

					"\r\n" . 'function initSliderPro() {' .
					"\r\n" . '	jQuery( document ).ready(function( $ ) {' .
					$this->js_output;

		if ( get_option( 'sliderpro_is_custom_js' ) ) {
			$custom_js = "\r\n" . '	' . stripslashes( get_option( 'sliderpro_custom_js' ) );

			$inline_js .= $custom_js;
		}

		$inline_js .= "\r\n" . '	});' .
					"\r\n" . '}' .
					"\r\n" . '</script>' . "\r\n\r\n";

		$inline_js = apply_filters( 'sliderpro_javascript', $inline_js );

		return $inline_js;
	}

	/**
	 * Print the inline JavaScript code.
	 *
	 * @since 4.0.0
	 */
	public function print_inline_scripts() {
		echo $this->get_inline_scripts();
	}

	/**
	 * Parse the slider shortcode and print the slider.
	 * 
	 * @since 4.0.0
	 * 
	 * @param  array  $atts    The attributes specified in the shortcode.
	 * @param  string $content The content added inside the shortcode.
	 * @return string          The slider's HTML.
	 */
	public function sliderpro_shortcode( $atts, $content = null ) {
		
		// if the CSS file(s) were not enqueued, display a warning message
		$styles_warning = '<div class="sp-styles-warning" style="width: 450px; background-color: #FFF; color: #F00; border: 1px solid #F00; padding: 10px; font-size: 14px;">
			<span style="font-weight: bold;">Warning: The stylesheets were not loaded!</span> 
			You will need to change the <i>Load stylesheets</i> setting from <i>Automatically</i> to <i>On homepage</i> or <i>On all pages</i>. 
			You can set that <a style="text-decoration: underline; color: #F00;" href="' . admin_url( 'admin.php?page=sliderpro-settings' ) . '">here</a>.
			</div>';

		// If styles were not checked, check them now.
		// This check is necessary because with some themes the shortcodes are parsed before the
		// 'wp_enqueue_script' action is called.
		if ( $this->styles_checked === false ) {
			$this->load_styles();
		}

		// don't show the warning if the styles are loaded
		if ( $this->styles_loaded === true || is_admin() === true ) {
			$styles_warning = '';
		}

		// get the id specified in the shortcode
		$id = isset( $atts['id'] ) ? intval( $atts['id'] ) : -1;

		// check whether cache is allowed
		$allow_cache = ( isset( $atts['allow_cache'] ) && $atts['allow_cache'] === 'false' ) ? false : true;
		$cache_transient_name = 'sliderpro_cache_' . $id;

		// load the slider from the cache
		if ( ( $slider_cache = get_transient( $cache_transient_name ) ) !== false && $allow_cache !== false ) {
			$css_dependencies = $slider_cache['css_dependencies'];
			$js_dependencies = $slider_cache['js_dependencies'];

			foreach ( $css_dependencies as $css_dependency ) {
				wp_enqueue_style( $this->plugin_slug . '-' . $css_dependency . '-style' );
			}

			foreach ( $js_dependencies as $js_dependency ) {
				$this->add_script_to_load( $this->plugin_slug . '-' . $js_dependency . '-script' );
			}

			$this->js_output .= $slider_cache['js_output'];
			
			return $styles_warning . $slider_cache['html_output'];
		}

		// parse the inner content of the shortcode
		$content = do_shortcode( $content );

		// get the slider's database data
		$slider = $this->get_slider( $id );

		// if the specified id doesn't return a result, either the slider doesn't exist, or, if
		// there is inner content added to the shortcode, try to create the slider from scratch
		if ( $slider === false ) {
			if ( empty( $content ) ) {
				return 'A slider with the ID of ' . $id . ' doesn\'t exist.';
			}

			$slider = array( 'settings' => array() );

			$slider['name'] = isset( $atts['name'] ) ? $atts['name'] : '';
		}

		// add the id of the slider to the array of slider data
		if ( ! isset( $slider['id'] ) ) {
			$slider['id'] = $id;
		}

		// override the slider's settings with those specified in the shortcode
		foreach ( $atts as $key => $value ) {
			if ( $key === 'breakpoints' ) {
				$value = json_decode( stripslashes( $value ), true );
			} else if ( $value === 'true' ) {
				$value = true;
			} else if ( $value === 'false' ) {
				$value = false;
			}

			$slider['settings'][ $key ] = $value;
		}

		// analyze the shortcode's content, if any
		if ( ! empty( $content ) ) {

			// create an array that will hold additional extra
			$slides_extra = array();
			
			// counter for the slides for which an index was not specified and will be added at the end of the other slides
			$end_counter = 1;

			// get all the added slides
			$slides_shortcode = do_shortcode( $content );
			$slides_shortcode = str_replace( '<br />', '', $slides_shortcode );		
			$slides_shortcode = explode( '%sp_sep%', $slides_shortcode );
			
			// loop through all the slides added within the shortcode 
			// and add the slide to the slides_extra array
			foreach ( $slides_shortcode as $slide_shortcode ) {
				$slide_shortcode = json_decode( stripslashes( trim( $slide_shortcode ) ), true );

				if ( ! empty( $slide_shortcode ) ) {
					$index = $slide_shortcode['settings']['index'];
					
					if ( ! is_numeric( $index ) ) {
						$index .= '_' . $end_counter;
						$end_counter++;
					}
					
					$slides_extra[ $index ] = $slide_shortcode;
				}
			}
			
			// loop through the slides added in the database and override the content with that
			// specified through shortcodes
			if ( isset( $slider['slides'] ) ) {
				foreach ( $slider['slides'] as $index => &$slide ) {
					if ( isset( $slides_extra[ $index ] ) ) {
						$slide_extra = $slides_extra[ $index ];

						foreach ( $slide_extra as $key => $value ) {
							if ( $key === 'settings' || $key === 'layers' ) {
								$slide[ $key ] = array_merge( $slide[ $key ], $slide_extra[ $key ] );
							} else {
								$slide[ $key ] = $value;
							}
						}
						
						unset( $slides_extra[ $index ] );
					}
				}
			}

			// add the remaining slides, added through shortcodes, to the end of the slider
			if ( ! empty( $slides_extra ) ) {
				if ( ! isset( $slider['slides'] ) ) {
					$slider['slides'] = array();
				}

				foreach ( $slides_extra as $slide_end ) {
					array_push( $slider['slides'], $slide_end );
				}
			}
		}
		
		return $styles_warning . $this->output_slider( $slider );
	}

	/**
	 * Parse the slider slide shortcode and return the data.
	 * 
	 * @since 4.0.0
	 * 
	 * @param  array  $atts    The attributes specified in the shortcode.
	 * @param  string $content The content added inside the shortcode.
	 * @return string          JSON-encoded data for the slide.
	 */
	public function sliderpro_slide_shortcode( $atts, $content = null ) {
		// initialize the settings
		$slide = array( 'settings' => array( 'index' => 'end' ) );

		// parse the attributes passed
		if ( ! empty( $atts ) ) {
			foreach ( $atts as $key => $value ) {
				if ( $key === 'posts_post_types' || $key === 'posts_taxonomies' ) {
					$value = explode( ',', $value );
				}

				$slide['settings'][ $key ] = $value;
			}
		}

		$slide_content = do_shortcode( $content );	
		$slide_content = str_replace( '<br />', '', $slide_content );	
		$slide_content_elements = explode( '%sp_sep%', $slide_content );

		// get the content of the slide
		foreach ( $slide_content_elements as $element ) {
			$element = json_decode( stripslashes( trim( $element ) ), true );

			if ( ! empty( $element ) ) {
				foreach ( $element as $key => $value ) {

					// check if the element is a layer or a different type
					if ( $key === 'layer' ) {
						$layer = array( 'text' => $value );

						if ( isset( $element['layer_settings'] ) ) {
							foreach ( $element['layer_settings'] as $layer_setting => $layer_setting_value ) {
								$layer[ $layer_setting ] = $layer_setting_value;
							}
						}

						if ( ! isset( $slide['layers'] ) ) {
							$slide['layers'] = array();
						}

						array_push( $slide['layers'], $layer );
					} else if ( $key !== 'layer_settings' ) {
						$slide[ $key ] = $value;
					}
				}
			}
		}

		return json_encode( $slide ) . '%sp_sep%';
	}

	/**
	 * Parse the slider slide element shortcode and return the data.
	 * 
	 * @since 4.0.0
	 * 
	 * @param  array  $atts    The attributes specified in the shortcode.
	 * @param  string $content The content added inside the shortcode.
	 * @return string          JSON-encoded data for the slide element.
	 */
	public function sliderpro_slide_element_shortcode( $atts, $content = null ) {
		$content = do_shortcode( $content );

		$attributes = array( 'layer_settings' => array() );

		foreach ( $atts as $key => $value ) {
			if ( $key === 'name' ) {
				$attributes[ $atts['name'] ] = $content;
			} else if ( isset( $atts['name'] ) && $atts['name'] === 'layer' ) {
				if ( $value === 'true' ) {
					$value = true;
				} else if ( $value === 'false' ) {
					$value = false;
				} else if ( $key === 'preset_styles' ) {
					$value = explode( ',', $value );
				}

				$attributes['layer_settings'][ $key ] = $value;
			}
		}

		return json_encode( $attributes ) . '%sp_sep%';
	}
}