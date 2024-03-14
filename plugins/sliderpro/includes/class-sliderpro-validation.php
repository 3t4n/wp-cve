<?php
/**
 * Contains the validation functions for the slider settings, slides, layers etc.
 * 
 * @since 4.7.0
 */
class BQW_SliderPro_Validation {

	/**
	 * Validate the slider's data.
	 *
	 * @since 4.7.0
	 * 
	 * @param array  $data Posted slider data.
	 * @return array       Validated slider data.
	 */
	public static function validate_slider_data( $data ) {
		$slider = array(
			'id' => intval( $data['id'] ),
			'name' => sanitize_text_field( $data['name'] ),
			'panels_state' => self::validate_slider_panels_state( $data['panels_state'] ),
			'settings' => self::validate_slider_settings( $data['settings'] ),
			'slides' => self::validate_slider_slides( $data['slides'] )
		);

		return $slider;
	}

	/**
	 * Validate the slider's panels state.
	 *
	 * @since 4.7.0
	 * 
	 * @param array  $data Posted slider panels state.
	 * @return array       Validated slider panels state.
	 */
	public static function validate_slider_panels_state( $data ) {
		$slider_panels_state = array();
		$default_panels_state = BQW_SliderPro_Settings::getPanelsState();

		foreach ( $data as $panel_name => $panel_state) {
			$slider_panels_state[ $panel_name ] = ( $panel_state === 'closed' || $panel_state === '' ) ? $panel_state : 'closed';
		}

		return $slider_panels_state;
	}

	/**
	 * Validate the slider's settings.
	 *
	 * @since 4.7.0
	 * 
	 * @param array  $data Posted slider settings.
	 * @return array       Validated slider settings.
	 */
	public static function validate_slider_settings( $data ) {
		$slider_settings = array();
		$default_slider_settings = BQW_SliderPro_Settings::getSettings();
		
		foreach ( $default_slider_settings as $name => $value ) {
			if ( isset( $data[ $name ] ) ) {
				$setting_value = $data[ $name ];
				$type = $default_slider_settings[ $name ][ 'type' ];

				if ( $type === 'boolean' ) {
					$slider_settings[ $name ] = is_bool( $setting_value ) ? $setting_value : $default_slider_settings[ $name ]['default_value'];
				} else if ( $type === 'number' ) {
					$slider_settings[ $name ] = floatval( $setting_value );
				} else if ( $type === 'mixed' || $type === 'text' ) {
					$slider_settings[ $name ] = sanitize_text_field( $setting_value );
				} else if ( $type === 'select' ) {
					if ( $name === 'thumbnail_image_size' ) {
						$slider_settings[ $name ] = sanitize_text_field( $setting_value );
					} else {
						$slider_settings[ $name ] = array_key_exists( $setting_value, $default_slider_settings[ $name ]['available_values'] ) ? $setting_value : $default_slider_settings[ $name ]['default_value'];
					}
				}
			}
		}

		if ( isset( $data['breakpoints'] ) ) {
			$slider_settings['breakpoints'] = self::validate_slider_breakpoint_settings( $data['breakpoints'] );
		}
		
		return $slider_settings;
	}

	/**
	 * Validate the slider's breakpoint settings.
	 *
	 * @since 4.7.0
	 * 
	 * @param array  $data Posted breakpoint settings.
	 * @return array       Validated breakpoint settings.
	 */
	public static function validate_slider_breakpoint_settings( $breakpoints_data ) {
		$default_slider_settings = BQW_SliderPro_Settings::getSettings();
		$default_breakpoint_settings = BQW_SliderPro_Settings::getBreakpointSettings();
		$breakpoints = array();

		foreach ( $breakpoints_data as $breakpoint_data ) {
			$breakpoint = array(
				'breakpoint_width' => floatval( $breakpoint_data['breakpoint_width'] )
			);

			foreach ( $breakpoint_data as $name => $value ) {
				if ( in_array( $name, $default_breakpoint_settings ) ) {
					$type = $default_slider_settings[ $name ][ 'type' ];

					if ( $type === 'boolean' ) {
						$breakpoint[ $name ] = is_bool( $value ) ? $value : $default_slider_settings[ $name ]['default_value'];
					} else if ( $type === 'number' ) {
						$breakpoint[ $name ] = floatval( $value );
					} else if ( $type === 'mixed' ) {
						$breakpoint[ $name ] = sanitize_text_field( $value );
					} else if ( $type === 'select' ) {
						$breakpoint[ $name ] = array_key_exists( $value, $default_slider_settings[ $name ]['available_values'] ) ? $value : $default_slider_settings[ $name ]['default_value'];
					}
				}
			}

			array_push( $breakpoints, $breakpoint );
		}

		return $breakpoints;
	}

	/**
	 * Validate the slider's slides data.
	 *
	 * @since 4.7.0
	 * 
	 * @param array  $data Posted slider slides data.
	 * @return array       Validated slider slides.
	 */
	public static function validate_slider_slides( $slides_data ) {
		$slides = array();
		global $allowedposttags;
		
		foreach ( $slides_data as $slide_data ) {
			$slide = array();

			foreach ( $slide_data as $name => $value ) {
				if ( in_array( $name, array( 'position', 'main_image_id', 'main_image_width', 'main_image_height' ) ) ) {
					$slide[ $name ] = intval( $value );
				} else if ( $name === 'settings' ) {
					$slide['settings'] = self::validate_slide_settings( $value );
				} else if ( $name === 'layers' ) {
					$slide['layers'] = self::validate_slide_layers( $value );
				} else {

					// for other slide fields, like html, caption, image sources etc.
					$allowed_html = array_merge(
						$allowedposttags,
						array(
							'iframe' => array(
								'src' => true,
								'width' => true,
								'height' => true,
								'allow' => true,
								'allowfullscreen' => true,
								'class' => true,
								'id' => true
							),
							'source' => array(
								'src' => true,
								'type' => true
							)
						)
					);

					$allowed_html = apply_filters( 'sliderpro_allowed_html', $allowed_html );

					$slide[ $name ] = wp_kses( $value, $allowed_html );
				}
			}

			array_push( $slides, $slide );
		}

		return $slides;
	}

	/**
	 * Validate the slide settings.
	 *
	 * @since 4.7.0
	 * 
	 * @param array  $data Posted slide settings.
	 * @return array       Validated slide settings.
	 */
	public static function validate_slide_settings( $slide_settings_data ) {
		$slide_settings = array();
		$default_slide_settings = BQW_SliderPro_Settings::getSlideSettings();

		if ( ! empty( $slide_settings_data ) ) {
			$slide_settings['content_type'] = array_key_exists( $slide_settings_data['content_type'], $default_slide_settings['content_type']['available_values'] ) ? $slide_settings_data['content_type'] : $default_slide_settings['content_type']['default_value'];
			
			foreach ( $slide_settings_data as $slide_setting_name => $slide_setting_value ) {
				if ( isset( $default_slide_settings[ $slide_setting_name ] ) ) {
					$type = $default_slide_settings[ $slide_setting_name ]['type'];

					if ( $type === 'number' ) {
						$slide_settings[ $slide_setting_name ] = floatval( $slide_setting_value );
					} else if ( $type === 'text' ) {
						$slide_settings[ $slide_setting_name ] = sanitize_text_field( $slide_setting_value );
					} else if ( $type === 'select' ) {
						$slide_settings[ $slide_setting_name ] = array_key_exists( $slide_setting_value, $default_slide_settings[ $slide_setting_name ]['available_values'] ) ? $slide_setting_value : $default_slide_settings[ $slide_setting_name ]['default_value'];
					} else if ( $type === 'multiselect' ) {
						$slide_settings[ $slide_setting_name ] = array();

						foreach ( $slide_setting_value as $option ) {
							array_push( $slide_settings[ $slide_setting_name ], wp_kses_post( $option ) );
						}
					}
				}
			}
		}

		return $slide_settings;
	}

	/**
	 * Validate the slide layers.
	 *
	 * @since 4.7.0
	 * 
	 * @param array  $data Posted slide layers.
	 * @return array       Validated slide layers.
	 */
	public static function validate_slide_layers( $layers_data ) {
		$layers = array();
		global $allowedposttags;

		foreach ( $layers_data as $layer_data ) {
			$layer = array();

			foreach ( $layer_data as $name => $value ) {
				if ( in_array( $name, array( 'id', 'slider_id', 'slide_id', 'position' ) ) ) {
					$layer[ $name ] = intval( $value );
				} else if ( $name === 'settings' ) {
					$layer['settings'] = self::validate_layer_settings( $value );
				} else {

					// for other layer fields, like name, text, image source etc.
					$allowed_html = array_merge(
						$allowedposttags,
						array(
							'iframe' => array(
								'src' => true,
								'width' => true,
								'height' => true,
								'allow' => true,
								'allowfullscreen' => true,
								'class' => true,
								'id' => true
							),
							'source' => array(
								'src' => true,
								'type' => true
							)
						)
					);

					$allowed_html = apply_filters( 'sliderpro_allowed_html', $allowed_html );

					$layer[ $name ] = wp_kses( $value, $allowed_html );
				}
			}

			array_push( $layers, $layer );
		}

		return $layers;
	}

	/**
	 * Validate the layer settings.
	 *
	 * @since 4.7.0
	 * 
	 * @param array  $data Posted layer settings.
	 * @return array       Validated slide layers.
	 */
	public static function validate_layer_settings( $layer_settings_data ) {
		$layer_settings = array();
		$default_layer_settings = BQW_SliderPro_Settings::getLayerSettings();

		foreach ( $layer_settings_data as $layer_setting_name => $layer_setting_value ) {
			if ( isset( $default_layer_settings[ $layer_setting_name ] ) ) {
				$type = $default_layer_settings[ $layer_setting_name ]['type'];

				if ( $type === 'number' ) {
					$layer_settings[ $layer_setting_name ] = floatval( $layer_setting_value );
				} else if ( $type === 'text' || $type === 'mixed' ) {
					$layer_settings[ $layer_setting_name ] = sanitize_text_field( $layer_setting_value );
				} else if ( $type === 'select' ) {
					$layer_settings[ $layer_setting_name ] = array_key_exists( $layer_setting_value, $default_layer_settings[ $layer_setting_name ]['available_values'] ) ? $layer_setting_value : $default_layer_settings[ $layer_setting_name ]['default_value'];
				} else if ( $type === 'multiselect' ) {
					$layer_settings[ $layer_setting_name ] = array();

					foreach ( $layer_setting_value as $option ) {
						array_push( $layer_settings[ $layer_setting_name ], wp_kses_post( $option ) );
					}
				}
			}
		}

		return $layer_settings;
	}
}