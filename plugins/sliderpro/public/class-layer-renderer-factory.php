<?php
/**
 * Factory for layer renderers.
 *
 * Implements the appropriate functionality for each layer, depending on the layer's type.
 * 
 * @since 4.0.0
 */
class BQW_SP_Layer_Renderer_Factory {

	/**
	 * List of layer types and the associated layer renderer class name.
	 *
	 * @since 4.0.0
	 * 
	 * @var array
	 */
	protected static $registered_types = array(
		'paragraph' => 'BQW_SP_Paragraph_Layer_Renderer',
		'heading' => 'BQW_SP_Heading_Layer_Renderer',
		'image' => 'BQW_SP_Image_Layer_Renderer',
		'div' => 'BQW_SP_Div_Layer_Renderer',
		'video' => 'BQW_SP_Video_Layer_Renderer'
	);

	/**
	 * Default layer type.
	 *
	 * @since 4.0.0
	 *
	 * @var string
	 */
	protected static $default_type = null;

	/**
	 * Return an instance of the renderer class based on the type of the layer.
	 *
	 * @since 4.0.0
	 * 
	 * @param  array  $data The data of the layer.
	 * @return object       An instance of the appropriate renderer class.
	 */
	public static function create_layer( $data ) {
		if ( is_null( self::$default_type ) ) {
			$default_settings = BQW_SliderPro_Settings::getLayerSettings();
			self::$default_type = $default_settings['type']['default_value'];
		}

		$type = isset( $data['type'] ) ? $data['type'] : self::$default_type;

		foreach( self::$registered_types as $registered_type_name => $registered_type_class ) {
			if ( $type === $registered_type_name ) {
				return new $registered_type_class();
			}
		}
	}
}