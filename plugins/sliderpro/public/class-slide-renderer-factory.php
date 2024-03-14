<?php
/**
 * Factory for slide renderers.
 *
 * Implements the appropriate functionality for each slide, depending on the slide's type.
 *
 * @since  1.0.0
 */
class BQW_SP_Slide_Renderer_Factory {

	/**
	 * Return an instance of the renderer class based on the type of the slide.
	 *
	 * @since 4.0.0
	 * 
	 * @param  array  $data The data of the slide.
	 * @return object       An instance of the appropriate renderer class.
	 */
	public static function create_slide( $data ) {
		$default_settings = BQW_SliderPro_Settings::getSlideSettings();
		$registered_types = $default_settings['content_type']['available_values'];
		$type = isset( $data['settings']['content_type'] ) ? $data['settings']['content_type'] : $default_settings['content_type']['default_value'];

		foreach( $registered_types as $registered_type_name => $registered_type ) {
			if ( $type === $registered_type_name ) {
				$registered_type_class = $registered_type['renderer_class'];
				return new $registered_type_class();
			}
		}
	}
}