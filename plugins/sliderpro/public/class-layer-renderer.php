<?php
/**
 * Base class for layer renderers.
 *
 * @since  1.0.0
 */
class BQW_SP_Layer_Renderer {

	/**
	 * Data of the layer.
	 *
	 * @since 4.0.0
	 * 
	 * @var array
	 */
	protected $data = null;

	/**
	 * Settings of the layer.
	 *
	 * @since 4.0.0
	 * 
	 * @var array
	 */
	protected $settings = null;

	/**
	 * Default layer settings.
	 *
	 * @since 4.0.0
	 * 
	 * @var array
	 */
	protected $default_settings = null;

	/**
	 * ID of the slider to which the layer belongs.
	 *
	 * @since 4.0.0
	 * 
	 * @var int
	 */
	protected $slider_id = null;

	/**
	 * index of the slide to which the layer belongs.
	 *
	 * @since 4.0.0
	 * 
	 * @var int
	 */
	protected $slide_index = null;

	/**
	 * Indicates whether the slide's images will be lazy loaded.
	 *
	 * @since 4.0.0
	 * 
	 * @var bool
	 */
	protected $lazy_loading = null;

	/**
	 * Initialize the layer renderer.
	 * 
	 * @since 4.0.0
	 */
	public function __construct() {
		$this->default_settings = BQW_SliderPro_Settings::getLayerSettings();
	}

	/**
	 * No implementation yet.
	 * 
	 * @since 4.0.0
	 */
	public function render() {
		
	}

	/**
	 * Set the data of the layer.
	 *
	 * @since 4.0.0
	 * 
	 * @param array $data         The data of the layer.
	 * @param int   $slider_id    The id of the slider.
	 * @param int   $slide_index  The index of the slide.
	 * @param bool  $lazy_loading Indicates whether the images will be lazy loaded
	 */
	public function set_data( $data, $slider_id, $slide_index, $lazy_loading ) {
		$this->data = $data;
		$this->slider_id = $slider_id;
		$this->slide_index = $slide_index;
		$this->lazy_loading = $lazy_loading;
		$this->settings = isset( $this->data['settings'] ) ? $this->data['settings'] : [];
	}

	/**
	 * Return the classes of the layer.
	 *
	 * Gets the class associated with the display method,
	 * then the preset classes and then the custom class.
	 *
	 * @since  1.0.0
	 * 
	 * @return string The classes.
	 */
	protected function get_classes() {
		$classes = 'sp-layer';

		if ( isset( $this->settings['display'] ) ) {
			$classes .= ' sp-' . $this->settings['display'];
			unset( $this->settings['display'] );
		}

		$styles = isset( $this->settings['preset_styles'] ) ? $this->settings['preset_styles'] : $this->default_settings['preset_styles']['default_value'];

		foreach ( $styles as $style ) {
			$classes .= ' ' . $style;
		}

		unset( $this->settings['preset_styles'] );

		if ( isset( $this->settings['custom_class'] ) && $this->settings['custom_class'] !== '' ) {
			$classes .= ' ' . $this->settings['custom_class'];
		}

		unset( $this->settings['custom_class'] );

		$classes = apply_filters( 'sliderpro_layer_classes', $classes, $this->slider_id, $this->slide_index );

		return $classes;
	}

	/**
	 * Return the attributes of the layer.
	 *
	 * Gets the attribute data and creates a string of attributes
	 * by suffixing each attribute name with 'data-'.
	 *
	 * @since  1.0.0
	 * 
	 * @return string The attributes.
	 */
	protected function get_attributes() {
		$attributes = '';

		foreach ( $this->settings as $name => $value ) {
			if ( $this->default_settings[ $name ]['default_value'] != $value ) {
				$name = str_replace('_', '-', $name);

				$attributes .= ' data-' . $name . '="' . esc_attr( $value ) . '"';
			}
		}

		return $attributes;
	}
}