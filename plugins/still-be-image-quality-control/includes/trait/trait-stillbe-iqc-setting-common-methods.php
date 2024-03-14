<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




trait StillBE_IQC_Setting_Common_Methods {


	// Check Month, date, hour, minute
	static public function chk_num_type( $value, $type = null ) {

		// Is Numeric?
		if( ! is_numeric( $value ) ) {
			return false;
		}

		// Cast the Value
		$_value = absint( $value );

		// Range
		switch( $type ) {
			case 'jpeg':
			case 'webp':
				$min = 1;
				$max = 100;
			break;
			case 'png':
				$min = 1;
				$max = 9;
			break;
			default:
				// Impossible Value
				$min = 2;
				$max = 1;
		}

		// In Range?
		if( $min > $value || $max < $value ) {
			return false;
		}

		// Return the Casted Value
		return $_value;

	}


	// Sanitize Setting Values
	public function sanitize_setting( $input ) {

		// Sanitized data is passed through
		if( empty( $input['not-sanitized'] ) ) {
			return $input;
		}

		// Save Data
		$save = array();

		// Quality Levels
		$qualities = empty( $input['quality'] ) ? array() : $input['quality'];
		$save['quality'] = array();
		foreach( $qualities as $size => $q ) {
			if( 'original' === $size ) {
				continue;
			}
			foreach( $q as $type => $v ) {
				$_tmp = $this->chk_num_type( $v, $type );
				if( false === $_tmp ) {
					continue;
				}
				$q_name = $size. '_'. $type;
				$save['quality'][ $q_name ] = $_tmp;
			}
		}

		// Max Size
		$big_threashold = apply_filters( 'big_image_size_threshold', 2560 );
		$big_threashold = $big_threashold ? min( 16383, absint( $threshold ) ) : $big_threashold;   // Max Size of WebP is 16383px

		// Original Image WebP Quality
		$_defaults = _stillbe_get_quality_level_array();
		$save['original-webp'] = array(
			// Default Values
			array(
				'lossy'    => $_defaults['original_webp'],
				'lossless' => 9,
			),
		);
		$original_webp = empty( $qualities['original']['webp'] ) ? array() : $qualities['original']['webp'];
		foreach( $original_webp as $i => $_threshold ) {
			$_lossy = $this->chk_num_type(
			            ( empty( $_threshold['lossy'] ) ? $_defaults['original_webp'] : $_threshold['lossy'] ),
			            'webp'
			          ) ?: $_defaults['original_webp'];
			$_lossless = $this->chk_num_type(
			               ( empty( $_threshold['lossless'] ) ? 9 : $_threshold['lossless'] ),
			               'png'
			             ) ?: 9;
			$_width  = empty( $_threshold['width']  ) ? 0 : absint( $_threshold['width']  );
			$_height = empty( $_threshold['height'] ) ? 0 : absint( $_threshold['height'] );
			if( 1 > $i ) {
				$save['original-webp'][0] = array(
					'lossy'    => $_lossy,
					'lossless' => $_lossless,
				);
			} else {
				// When bogger than the maximum size
				if( $big_threashold && ( $big_threashold < $_width || $big_threashold < $_height ) ) {
					continue;
				}
				// When both width & height are 0
				if( 1 > $_width && 1 > $_height ) {
					continue;
				}
				$save['original-webp'][] = array(
					'width'    => $_width,
					'height'   => $_height,
					'lossy'    => $_lossy,
					'lossless' => $_lossless,
				);
			}
		}
		// Sort in ASC
		$_buff = array_shift( $save['original-webp'] );
		usort( $save['original-webp'], function( $a, $b ) {
			if( $a['width'] !== $b['width'] ) {
				return $a['width'] - $b['width'];
			}
			if( $a['height'] !== $b['height'] ) {
				return $a['height'] - $b['height'];
			}
			return 0;
		} );
		array_unshift( $save['original-webp'], $_buff );

		// Current Added Image Sizes
		$added_sizes = empty( $this->current['image-size'] ) ? array() : $this->current['image-size'];
		$added_sizes = array_column( $added_sizes, 'name' );

		// Add Image Sizes
		$resistered_sizes = $this->get_all_sizes();
		foreach( $resistered_sizes as $_name => $_size ) {
			if( in_array( $_name, $added_sizes ) ) {
				unset( $resistered_sizes[ $_name ] );
			}
		}
		$add_sizes          = empty( $input['image-size'] ) ? array() : $input['image-size'];
		$save['image-size'] = array();
		foreach( $add_sizes as $add_size) {
			$name = (string) $add_size['name'];
			if( empty( $name ) || preg_match( '/\W/', $name ) ) {
				// Illegal name
				continue;
			}
			if( isset( $resistered_sizes[ $name ] ) || isset( $save['image-size'][ $name ] ) ) {
				// Duplicate name
				continue;
			}
			$width  = empty( $add_size['width']  ) ? 0 : absint( $add_size['width'] );
			$height = empty( $add_size['height'] ) ? 0 : absint( $add_size['height'] );
			$crop   = ! empty( $add_size['crop'] );
			if( 1 > $width && 1 > $height ) {
				continue;
			}
			$save['image-size'][ $name ] = compact( 'name', 'width', 'height', 'crop' );
			foreach( array( 'jpeg', 'png', 'webp' ) as $type ) {
				if( empty( $add_size[ $type ] ) ) {
					continue;
				}
				$_tmp = $this->chk_num_type( $add_size[ $type ], $type );
				if( false === $_tmp ) {
					continue;
				}
				$q_name = $name. '_'. $type;
				$save['quality'][ $q_name ] = $_tmp;
			}
		}

		// Do NOT Empty the Default Value
		if( empty( $save['quality']['default_jpeg'] ) ) {
			$save['quality']['default_jpeg'] = $this->_default['default_jpeg'];
		}
		if( empty( $save['quality']['default_png'] ) ) {
			$save['quality']['default_png']  = $this->_default['default_png'];
		}
		if( empty( $save['quality']['default_webp'] ) ) {
			$save['quality']['default_webp'] = $this->_default['default_webp'];
		}
		// Deleted @since 1.0.0
		// Added alternative property "original-webp"
	//	if( empty( $save['quality']['original_webp'] ) ) {
	//		$save['quality']['original_webp'] = $this->_default['original_webp'];
	//	}

		// Toggle Options
		$toggles = empty( $input['toggle'] ) ? array() : $input['toggle'];
		$save['toggle'] = array();
		foreach( $toggles as $option => $bool ) {
			$save['toggle'][ $option ] = ( 'true' === $bool );
		}

		// Big Image Threshold
		$threshold = isset( $input['big-threshold'] ) ? $input['big-threshold'] : null;
		if( is_numeric( $threshold ) ) {
			// Max Size of WebP is 16383px
			$save['big-threshold'] = min( 16383, absint( $threshold ) );
		} else {
			$save['big-threshold'] = null;
		}

		// Auto Regenerate using WP-Cron
		$auto_regen = isset( $input['auto-regen-wpcron'] ) ? $input['auto-regen-wpcron']       : array();
		$_number    = isset( $auto_regen['number']       ) ? absint( $auto_regen['number']   ) : 0;
		$_interval  = isset( $auto_regen['interval']     ) ? absint( $auto_regen['interval'] ) : 60;
		$save['auto-regen-wpcron'] = array(
			'number'   => $_number,
			'interval' => $_interval,
		);

		// Set a WP-Cron Schedule
		if( empty( $this->current['auto-regen-wpcron'] ) || empty( $this->current['auto-regen-wpcron']['number'] ) ) {
			wp_schedule_single_event(
				absint( @time() ) + $_interval,
				'stillbe_image_quality_control_arg_wpcron_run',
				array( time() )
			);
		}

		// If WebP is Enabled, Put ".htaccess" in the uploads Directory
		_stillbe_iqc_htaccess_webp( ! empty( $save['toggle']['enable-webp'] ) );

		// for Debug
	//	exit(json_encode($save));

		// Save!!
		return $save;

	}


	// Get Resistered All Sizes
	public function get_all_sizes() {

		if( ! empty( $this->sizes ) ) {
			return $this->sizes;
		}

		$sizes = wp_get_registered_image_subsizes();
		uasort( $sizes, function( $a, $b ) {
			if( $a['width'] !== $b['width'] ) {
				return $a['width'] - $b['width'];
			}
			if( $a['height'] !== $b['height'] ) {
				return $a['height'] - $b['height'];
			}
			return (int) $a['crop'] - (int) $b['crop'];
		} );
		$this->sizes = $sizes;

		return $this->sizes;

	}


	// Set Translation Array for Javascript
	public function set_js_translate( $ids = null ) {

		if( empty( $ids ) ) {
			return $this->js_translate;
		}

		if( is_string( $ids ) ) {
			$ids = array( $ids );
		} elseif( is_array( $ids ) ) {
			$ids = array_map( 'strval', $ids );
		} else {
			return $this->js_translate;
		}

		foreach( $ids as $id ) {
			if( empty( $id ) ) {
				continue;
			}
			$this->js_translate[ $id ] = esc_html__( $id, 'still-be-image-quality-control' );
		}

		return $this->js_translate;

	}


	// Check the Extension Plugin Version
	public function supported_extension_plugin_ver() {

		if( ! defined( 'STILLBE_IQ_EXT_PLUGIN_VER' ) ||
		    version_compare( STILLBE_IQ_EXT_PLUGIN_VER, STILLBE_IQ_REQUIRED_EXT_PLUGIN_VER, '>=' ) ) {
			return true;
		}

		// Unsupported Version
		return false;

	}


}