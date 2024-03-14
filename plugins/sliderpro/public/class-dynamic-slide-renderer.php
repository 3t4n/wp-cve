<?php
/**
 * Base class for dynamic slide renderers.
 * 
 * @since 4.0.0
 */
class BQW_SP_Dynamic_Slide_Renderer extends BQW_SP_Slide_Renderer {

	/**
	 * The settings data of the slide.
	 *
	 * @since 4.0.0
	 * 
	 * @var array
	 */
	protected $settings = null;

	/**
	 * The default settings data.
	 *
	 * @since 4.0.0
	 * 
	 * @var array
	 */
	protected $default_settings = null;

	/**
	 * The registered dynamic tags.
	 *
	 * An associative array that contains the name of the
	 * tag and the function that will render the tag.
	 *
	 * @since 4.0.0
	 * 
	 * @var array
	 */
	protected $registered_tags = null;

	/**
	 * Initialize the renderer.
	 *
	 * @since 4.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->default_settings = BQW_SliderPro_Settings::getSlideSettings();
	}

	/**
	 * Set the data of the slide.
	 *
	 * @since 4.0.0
	 * 
	 * @param array $data        The data of the slide.
	 * @param int   $slider_id   The id of the slider.
	 * @param int   $slide_index The index of the slide.
	 * @param bool  $extra_data  Extra settings data for the slider.
	 */
	public function set_data( $data, $slider_id, $slide_index, $extra_data ) {
		parent::set_data( $data, $slider_id, $slide_index, $extra_data );

		$this->settings = $this->data['settings'];
	}

	/**
	 * Return the HTML markup of the slide.
	 *
	 * @since 4.0.0
	 *
	 * @return string The HTML output.
	 */
	public function render() {
		return parent::render();
	}

	/**
	 * Return all the tags used in the slide.
	 *
	 * Get the tags by matching all the '[sp_' occurances and
	 * parse the tags to extract the name of the tag and the argument.
	 *
	 * @since  1.0.0
	 * 
	 * @return array The array of used tags.
	 */
	protected function get_slide_tags() {
		$tags = array();

		preg_match_all( '/\[sp_(.*?)\]/', $this->html_output, $matches, PREG_SET_ORDER );

		foreach ( $matches as $match ) {
			$tag = $match[0];

			$delimiter_position = strpos( $match[1], '.' );
			$tag_arg = $delimiter_position !== false ? substr( $match[1], $delimiter_position + 1 ) : false;
			$tag_name = $tag_arg !== false ? substr( $match[1], 0, $delimiter_position ) : $match[1];

			$tags[] = array(
				'full' => $tag,
				'name' => $tag_name,
				'arg' => $tag_arg
			);
		}

		return $tags;
	}

	/**
	 * Applies the correct renderer method based on the name of the tag.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_name The name of the tag.
	 * @param  string $tag_arg  The argument of the tag.
	 * @param  string $data     The current post, or gallery image, or flickr photo.
	 * @return object           The renderer method associated with the tag name.
	 */
	protected function render_tag( $tag_name, $tag_arg, $data ) {
		foreach ( $this->registered_tags as $name => $method ) {
			if ( $name === $tag_name ) {
				return call_user_func( $method, $tag_arg, $data );
			}
		}
	}

	/**
	 * Return the value of the specified setting.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $setting_name The setting name.
	 * @return mixed                The setting value.
	 */
	protected function get_setting_value( $setting_name ) {
		return isset( $this->settings[ $setting_name ] ) ? $this->settings[ $setting_name ] : $this->default_settings[ $setting_name ]['default_value'];
	}
}