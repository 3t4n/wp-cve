<?php
/**
 * Renders the slider.
 * 
 * @since 4.0.0
 */
class BQW_SP_Slider_Renderer {

	/**
	 * Data of the slider.
	 *
	 * @since 4.0.0
	 * 
	 * @var array
	 */
	protected $data = null;

	/**
	 * ID of the slider.
	 *
	 * @since 4.0.0
	 * 
	 * @var int
	 */
	protected $id = null;

	/**
	 * Name of the slider.
	 *
	 * @since 4.2.0
	 * 
	 * @var string
	 */
	protected $name = '';

	/**
	 * ID attribute (to be used in HTML) of the slider.
	 *
	 * @since 4.2.0
	 * 
	 * @var string
	 */
	protected $idAttribute = '';

	/**
	 * Settings of the slider.
	 *
	 * @since 4.0.0
	 * 
	 * @var array
	 */
	protected $settings = null;

	/**
	 * Default slider settings data.
	 *
	 * @since 4.0.0
	 * 
	 * @var array
	 */
	protected $default_settings = null;

	/**
	 * HTML markup of the slider.
	 *
	 * @since 4.0.0
	 * 
	 * @var string
	 */
	protected $html_output = '';

	/**
	 * List of id's for the CSS files that need to be loaded for the slider.
	 *
	 * @since 4.0.0
	 * 
	 * @var array
	 */
	protected $css_dependencies = array();

	/**
	 * List of id's for the JS files that need to be loaded for the slider.
	 *
	 * @since 4.0.0
	 * 
	 * @var array
	 */
	protected $js_dependencies = array();

	/**
	 * Initialize the slider renderer by retrieving the id and settings from the passed data.
	 * 
	 * @since 4.0.0
	 *
	 * @param array $data The data of the slider.
	 */
	public function __construct( $data ) {
		$this->data = $data;
		$this->id = $this->data['id'];
		$this->name = $this->data['name'];
		$this->settings = $this->data['settings'];
		$this->default_settings = BQW_SliderPro_Settings::getSettings();

		$this->idAttribute = isset( $this->settings['use_name_as_id'] ) && $this->settings['use_name_as_id'] === true ? str_replace( ' ', '-', strtolower( $this->name ) ) : 'slider-pro-' . $this->id;
	}

	/**
	 * Return the slider's HTML markup.
	 *
	 * @since 4.0.0
	 * 
	 * @return string The HTML markup of the slider.
	 */
	public function render() {
		$slides_html = '';

		if ( $this->has_slides() ) {
			$slides_html = $this->create_slides();
		}

		if ( empty( $slides_html  ) ) {
			$this->html_output = '<div class="sp-no-slides">The slider with the ID of ' . $this->id . ' is empty.</div>';

			$this->html_output = apply_filters( 'sliderpro_markup', $this->html_output, $this->id );
			return $this->html_output;
		}

		$classes = 'slider-pro sp-no-js';
		$classes .= isset( $this->settings['custom_class'] ) && $this->settings['custom_class'] !== '' ? ' ' . $this->settings['custom_class'] : '';
		$classes = apply_filters( 'sliderpro_classes' , $classes, $this->id );

		$width = isset( $this->settings['width'] ) ? $this->settings['width'] : $this->default_settings['width']['default_value'];
		$height = isset( $this->settings['height'] ) ? $this->settings['height'] : $this->default_settings['height']['default_value'];

		if ( is_numeric( $width ) ) {
			$width .= 'px';
		}

		if ( is_numeric( $height ) ) {
			$height .= 'px';
		}

		$this->html_output .= "\r\n" . '<div id="' . esc_attr( $this->idAttribute ) . '" class="' . esc_attr( $classes ) . '" style="width: ' . $width . '; height: ' . $height . ';">';
		$this->html_output .= "\r\n" . '	<div class="sp-slides">';
		$this->html_output .= "\r\n" . '		' . $slides_html;
		$this->html_output .= "\r\n" . '	</div>';
		$this->html_output .= "\r\n" . '</div>';
		
		$this->html_output = apply_filters( 'sliderpro_markup', $this->html_output, $this->id );

		return $this->html_output;
	}

	/**
	 * Check if the slider has slides.
	 *
	 * @since  1.0.0
	 * 
	 * @return boolean Whether or not the slider has slides.
	 */
	protected function has_slides() {
		if ( isset( $this->data['slides'] ) && ! empty( $this->data['slides'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Create the slider's slides and get their HTML markup.
	 *
	 * @since  1.0.0
	 * 
	 * @return string The HTML markup of the slides.
	 */
	protected function create_slides() {
		$slides_output = '';
		$slides = $this->data['slides'];
		$slide_counter = 0;

		foreach ( $slides as $slide ) {
			$slides_output .= $this->create_slide( $slide, $slide_counter );
			$slide_counter++;
		}

		return $slides_output;
	}

	/**
	 * Create a slide.
	 * 
	 * @since 4.0.0
	 *
	 * @param  array  $data          The data of the slide.
	 * @param  int    $slide_counter The index of the slide.
	 * @return string                The HTML markup of the slide.
	 */
	protected function create_slide( $data, $slide_counter ) {
		$lazy_loading = isset( $this->settings['lazy_loading'] ) ? $this->settings['lazy_loading'] : $this->default_settings['lazy_loading']['default_value'];
		$lightbox = isset( $this->settings['lightbox'] ) ? $this->settings['lightbox'] : $this->default_settings['lightbox']['default_value'];
		$hide_image_title = isset( $this->settings['hide_image_title'] ) ? $this->settings['hide_image_title'] : $this->default_settings['hide_image_title']['default_value'];
		$link_target = isset( $this->settings['link_target'] ) ? $this->settings['link_target'] : $this->default_settings['link_target']['default_value'];
		$auto_thumbnail_images = isset( $this->settings['auto_thumbnail_images'] ) ? $this->settings['auto_thumbnail_images'] : $this->default_settings['auto_thumbnail_images']['default_value'];
		$thumbnail_image_size = isset( $this->settings['thumbnail_image_size'] ) ? $this->settings['thumbnail_image_size'] : $this->default_settings['thumbnail_image_size']['default_value'];

		$extra_data = new stdClass();
		$extra_data->lazy_loading = $lazy_loading;
		$extra_data->lightbox = $lightbox;
		$extra_data->hide_image_title = $hide_image_title;
		$extra_data->link_target = $link_target;
		$extra_data->auto_thumbnail_images = $auto_thumbnail_images;
		$extra_data->thumbnail_image_size = $thumbnail_image_size;

		$slide = BQW_SP_Slide_Renderer_Factory::create_slide( $data );
		$slide->set_data( $data, $this->id, $slide_counter, $extra_data );
		
		return $slide->render();
	}

	/**
	 * Return the inline JavaScript code of the slider and identify all CSS and JS
	 * files that need to be loaded for the current slider.
	 *
	 * @since 4.0.0
	 * 
	 * @return string The inline JavaScript code of the slider.
	 */
	public function render_js() {
		$js_output = '';
		$settings_js = '';

		foreach ( $this->default_settings as $name => $setting ) {
			if ( ! isset( $setting['js_name'] ) ) {
				continue;
			}

			$setting_default_value = $setting['default_value'];
			$setting_value = isset( $this->settings[ $name ] ) ? $this->settings[ $name ] : $setting_default_value;

			if ( $setting_value != $setting_default_value ) {
				if ( $settings_js !== '' ) {
					$settings_js .= ',';
				}

				if ( is_bool( $setting_value ) ) {
					$setting_value = $setting_value === true ? 'true' : 'false';
				} else if ( is_numeric( $setting_value ) ) {
					$setting_value = floatval( $setting_value );
				} else {
					$setting_value = json_encode( $setting_value );
				}

				$settings_js .= "\r\n" . '			' . $setting['js_name'] . ': ' . $setting_value;
			}
		}

		if ( isset ( $this->settings['breakpoints'] ) ) {
			$breakpoints_js = "";

			foreach ( $this->settings['breakpoints'] as $breakpoint ) {
				if ( $breakpoint['breakpoint_width'] === '' ) {
					continue;
				}

				if ( $breakpoints_js !== '' ) {
					$breakpoints_js .= ',';
				}

				$breakpoints_js .= "\r\n" . '				' . $breakpoint['breakpoint_width'] . ': {';

				unset( $breakpoint['breakpoint_width'] );

				if ( ! empty( $breakpoint ) ) {
					$breakpoint_setting_js = '';

					foreach ( $breakpoint as $name => $value ) {
						if ( $breakpoint_setting_js !== '' ) {
							$breakpoint_setting_js .= ',';
						}

						if ( is_bool( $value ) ) {
							$value = $value === true ? 'true' : 'false';
						} else if ( is_numeric( $value ) ) {
							$value = floatval( $value );
						} else {
							$value = json_encode( $value );
						}

						$breakpoint_setting_js .= "\r\n" . '					' . $this->default_settings[ $name ]['js_name'] . ': ' . $value;
					}

					$breakpoints_js .= $breakpoint_setting_js;
				}

				$breakpoints_js .= "\r\n" . '				}';
			}

			if ( $settings_js !== '' ) {
				$settings_js .= ',';
			}

			$settings_js .= "\r\n" . '			breakpoints: {' . $breakpoints_js . "\r\n" . '			}';
		}

		$this->add_js_dependency( 'plugin' );

		$js_output .= "\r\n" . '		$( "#' . $this->idAttribute . '" ).sliderPro({' .
											$settings_js .
						"\r\n" . '		});' . "\r\n";

		if ( isset ( $this->settings['lightbox'] ) && $this->settings['lightbox'] === true ) {
			$this->add_js_dependency( 'lightbox' );
			$this->add_css_dependency( 'lightbox' );

			$lightbox_options = array();
			$lightbox_options = apply_filters( 'sliderpro_lightbox_options', $lightbox_options, $this->id );
			$lightbox_options_string = '';

			if ( is_null( $lightbox_options ) === false && empty( $lightbox_options ) === false ) {
				foreach ( $lightbox_options as $key => $value) {
					$lightbox_option_value = $value;

					if ( is_bool( $lightbox_option_value ) ) {
						$lightbox_option_value = $lightbox_option_value === true ? 'true' : 'false';
					} else if ( is_numeric( $lightbox_option_value ) ) {
						$lightbox_option_value = floatval( $lightbox_option_value );
					} else {
						$lightbox_option_value = json_encode( $lightbox_option_value );
					}

					$lightbox_options_string .= ', ' . $key . ': ' . $lightbox_option_value;
				}
			}

			$js_output .= "\r\n" . '		$( "#' . $this->idAttribute . ' .sp-image" ).parent( "a" ).on( "click", function( event ) {' .
							"\r\n" . '			event.preventDefault();' .
							"\r\n" . '			if ( $( "#' . $this->idAttribute . '" ).hasClass( "sp-swiping" ) === false ) {' .
							"\r\n" . '				var sliderInstance = $( "#' . $this->idAttribute . '" ).data( "sliderPro" ),' .
							"\r\n" . '					isAutoplay = sliderInstance.settings.autoplay;' .
							"\r\n" .
							"\r\n" . '				$.fancybox.open( $( "#' . $this->idAttribute . ' .sp-image" ).parent( "a" ), {' .
							"\r\n" . '					index: $( this ).parents( ".sp-slide" ).index(),' .
							"\r\n" . '					afterShow: function() {' .
							"\r\n" . '						if ( isAutoplay === true ) {' .
							"\r\n" . '							sliderInstance.settings.autoplay = false;' .
							"\r\n" . '							sliderInstance.stopAutoplay();' .
							"\r\n" . '						}' .
							"\r\n" . '					},' .
							"\r\n" . '					afterClose: function() {' .
							"\r\n" . '						if ( isAutoplay === true ) {' .
							"\r\n" . '							sliderInstance.settings.autoplay = true;' .
							"\r\n" . '							sliderInstance.startAutoplay();' .
							"\r\n" . '						}' .
							"\r\n" . '					}' .
							"\r\n" . '					' . $lightbox_options_string . 
							"\r\n" . '				});' .
							"\r\n" . '			}' .
							"\r\n" . '		});' . "\r\n";
		}

		return $js_output;
	}

	/**
	 * Add the id of a CSS file that needs to be loaded for the current slider.
	 *
	 * @since 4.0.0
	 * 
	 * @param string $id The id of the file.
	 */
	protected function add_css_dependency( $id ) {
		$this->css_dependencies[] = $id;
	}

	/**
	 * Add the id of a JS file that needs to be loaded for the current slider.
	 *
	 * @since 4.0.0
	 * 
	 * @param string $id The id of the file.
	 */
	protected function add_js_dependency( $id ) {
		$this->js_dependencies[] = $id;
	}

	/**
	 * Return the list of id's for CSS files that need to be loaded for the current slider.
	 *
	 * @since 4.0.0
	 * 
	 * @return array The list of id's for CSS files.
	 */
	public function get_css_dependencies() {
		return $this->css_dependencies;
	}

	/**
	 * Return the list of id's for JS files that need to be loaded for the current slider.
	 *
	 * @since 4.0.0
	 * 
	 * @return array The list of id's for JS files.
	 */
	public function get_js_dependencies() {
		return $this->js_dependencies;
	}
}