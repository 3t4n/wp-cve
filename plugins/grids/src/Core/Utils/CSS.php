<?php

namespace Grids\Core\Utils;

/* Check that we're running this file from the plugin. */
if ( ! defined( 'GRIDS' ) ) die( 'Forbidden' );

/**
 * Grids CSS class.
 *
 * @since 1.0.0
 */
class CSS {

	/**
	 * Get the parent breakpoint name.
	 *
	 * @since 1.3.2
	 * @param string $breakpoint The breakpoint name.
	 * @return string
	 */
	public static function get_parent_breakpoint( $breakpoint ) {
		$breakpoints_config = \Grids\Core::instance()->get_config( 'breakpoints' );
		$breakpoints_indexes = array_keys( $breakpoints_config );
		$breakpoint_index = array_search( $breakpoint, $breakpoints_indexes );

		if ( $breakpoint_index === 0 ) {
			return null;
		}

		return $breakpoints_indexes[ $breakpoint_index - 1 ];
	}

	/**
	 * Gap rules.
	 *
	 * @since 1.3.0
	 * @param string $breakpoint The breakpoint name.
	 * @param array $attributes The block attributes.
	 * @return array
	 */
	public static function gap_rules( $breakpoint, $attributes ) {
		$gap = array(
			'x' => '0px',
			'y' => '0px',
		);
		$type = 'gap';

		$dirs = array( 'x', 'y' );
		$parent_breakpoint = self::get_parent_breakpoint( $breakpoint );

		foreach ( $dirs as $dir ) {
			$key = $type . '_' . $dir . '_' . $breakpoint;
			$key_unit = $type . '_' . $dir . '_' . $breakpoint . '_unit';

			if ( isset( $attributes[ $key ] ) ) {
				$value = $attributes[ $key ];
				$unit = isset( $attributes[ $key_unit ] ) ? $attributes[ $key_unit ] : 'px';
				$new_value = $value . $unit;

				$gap[ $dir ] = $new_value;
			}
			else {
				if ( $parent_breakpoint ) {
					$parent_gap = self::gap_rules( $parent_breakpoint, $attributes );

					if ( ! empty( $parent_gap[ $dir ] ) ) {
						$gap[ $dir ] = $parent_gap[ $dir ];
					}
				}
			}
		}

		if ( ! isset( $gap[ $dir ] ) ) {
			$gap[ $dir ] = '0px';
		}

		return $gap;
	}

	/**
	 * Get the combined spacing value and unit.
	 *
	 * @since 1.2.6
	 * @param string $property The CSS spacing property.
	 * @param string $dir The property direction.
	 * @param array $attributes The block attributes.
	 * @param string $breakpoint The breakpoint key.
	 * @return string
	 */
	public static function get_spacing( $property, $dir, $attributes, $breakpoint ) {
		$value = isset( $attributes[ $property . '_' . $breakpoint . '_' . $dir ] ) && ! empty( $attributes[ $property . '_' . $breakpoint . '_' . $dir ] ) ? $attributes[ $property . '_' . $breakpoint . '_' . $dir ] : '0';
		$unit = isset( $attributes[ $property . '_' . $breakpoint . '_' . $dir . '_unit' ] ) && ! empty( $attributes[ $property . '_' . $breakpoint . '_' . $dir . '_unit' ] ) ? $attributes[ $property . '_' . $breakpoint . '_' . $dir . '_unit' ] : 'px';

		return $value . $unit;
	}

	/**
	 * Return display rules, depending on a given breakpoint.
	 *
	 * @since 1.0.0
	 * @param string $breakpoint The breakpoint key.
	 * @param array $attributes The block attributes.
	 * @param string $type The block type.
	 * @return string
	 */
	public static function display_rules( $breakpoint, $attributes, $type ) {
		$display = array();

		if ( isset( $attributes[ $breakpoint . '_display' ] ) ) {
			$display[] = 'none';
		}

		if ( empty( $display ) ) {
			$display[] = $type === 'section' ? 'block' : 'flex';
		}

		return $display;
	}

	/**
	 * Return z-index rules, depending on a given breakpoint.
	 *
	 * @since 1.3.0
	 * @param string $breakpoint The breakpoint key.
	 * @param array $attributes The block attributes.
	 * @return string
	 */
	public static function zindex_rules( $breakpoint, $attributes ) {
		$zindex = array();
		$parent_breakpoint = self::get_parent_breakpoint( $breakpoint );

		if ( isset( $attributes[ $breakpoint . '_zIndex' ] ) ) {
			$zindex[] = intval( $attributes[ $breakpoint . '_zIndex' ] );
		}
		else {
			if ( $parent_breakpoint ) {
				$parent_zindex = self::zindex_rules( $parent_breakpoint, $attributes );

				if ( ! empty( $parent_zindex ) ) {
					$zindex = $parent_zindex;
				}
			}
		}

		if ( empty( $zindex ) ) {
			$zindex[] = 'auto';
		}

		return $zindex;
	}

	/**
	 * Return spacing rules for margins or paddings, depending on a given breakpoint.
	 *
	 * @since 1.0.0
	 * @param string $type Either "margin" or "padding".
	 * @param string $breakpoint The breakpoint key.
	 * @param array $attributes The block attributes.
	 * @param string $output The output format.
	 * @return mixed
	 */
	public static function spacing_rules( $type, $breakpoint, $attributes, $dirs = array( 'top', 'right', 'bottom', 'left' ) ) {
		$values = array();
		$parent_breakpoint = self::get_parent_breakpoint( $breakpoint );

		foreach ( $dirs as $d => $dir ) {
			$key = $type . '_' . $breakpoint . '_' . $dir;
			$key_unit = $type . '_' . $breakpoint . '_' . $dir . '_unit';

			if ( isset( $attributes[ $key ] ) ) {
				$value = $attributes[ $key ];
				$unit = isset( $attributes[ $key_unit ] ) ? $attributes[ $key_unit ] : 'px';
				$new_value = $value . $unit;

				$values[] = $new_value;
			}
			else {
				if ( $parent_breakpoint ) {
					$parent_spacing = self::spacing_rules( $type, $parent_breakpoint, $attributes, [ $dir ] );

					if ( ! empty( $parent_spacing[0] ) ) {
						$values[] = $parent_spacing[0];
					}
				}
			}

			if ( ! isset( $values[ $d ] ) ) {
				$values[ $d ] = '0';
			}
		}

		return $values;
	}

	/**
	 * Return a Media Query selector based on a given breakpoint key.
	 *
	 * @since 1.0.0
	 * @param string $breakpoint The breakpoint key.
	 * @return string
	 */
	public static function media_query_selector( $breakpoint ) {
		$breakpoints_config = \Grids\Core::instance()->get_config( 'breakpoints' );
		$media = '';

		if ( isset( $breakpoints_config[ $breakpoint ] ) ) {
			if ( isset( $breakpoints_config[ $breakpoint ][ 'media' ] ) && ! empty( $breakpoints_config[ $breakpoint ][ 'media' ] ) ) {
				$media = $breakpoints_config[ $breakpoint ][ 'media' ];
			}
			else {
				$min = isset( $breakpoints_config[ $breakpoint ][ 'min' ] ) ? $breakpoints_config[ $breakpoint ][ 'min' ] : '';
				$max = isset( $breakpoints_config[ $breakpoint ][ 'max' ] ) ? $breakpoints_config[ $breakpoint ][ 'max' ] : '';

				$media = '@media screen';

				if ( ! empty( $min ) || ! empty( $max ) ) {
					if ( ! empty( $min ) ) {
						$media .= ' and ( min-width:' . $min . 'px )';
					}
					if ( ! empty( $max ) ) {
						$media .= ' and ( max-width:' . $max . 'px )';
					}
				}
			}
		}

		return $media;
	}

	/**
	 * Return the style to display a background color for given breakpoint key.
	 *
	 * @since 1.0.0
	 * @param string $breakpoint The breakpoint key.
	 * @param array $attributes The attributes array.
	 * @return mixed
	 */
	public static function background_color_rules( $breakpoint, $attributes ) {
		$background = array();

		if ( isset( $attributes[ 'background_' . $breakpoint . '_color' ] ) ) {
			$background[] = $attributes[ 'background_' . $breakpoint . '_color' ];
		}
		else {
			$parent_breakpoint = self::get_parent_breakpoint( $breakpoint );

			if ( $parent_breakpoint ) {
				$background = self::background_color_rules( $parent_breakpoint, $attributes );
			}
		}

		if ( empty( $background ) ) {
			$background[] = 'transparent';
		}

		return $background;
	}

	/**
	 * Return the style to display a background image for given breakpoint key.
	 *
	 * @since 1.0.0
	 * @param string $breakpoint The breakpoint key.
	 * @param array $attributes The attributes array.
	 * @return mixed
	 */
	public static function background_image_rules( $breakpoint, $attributes ) {
		$background = array();

		if ( isset( $attributes[ 'background_' . $breakpoint . '_image' ] ) ) {
			$image_id = $attributes[ 'background_' . $breakpoint . '_image' ];
			$image_size = isset( $attributes[ 'background_' . $breakpoint . '_image_size' ] ) ? $attributes[ 'background_' . $breakpoint . '_image_size' ] : 'full';

			$background[] = 'url(' . \Grids\Core\Media::get_image_by_id( $image_id, $image_size ) . ')';

			if ( isset( $attributes[ 'background_' . $breakpoint . '_repeat' ] ) ) {
				$background[] = $attributes[ 'background_' . $breakpoint . '_repeat' ];
			}
			else {
				$background[] = 'no-repeat';
			}

			if ( isset( $attributes[ 'background_' . $breakpoint . '_attachment' ] ) ) {
				$background[] = $attributes[ 'background_' . $breakpoint . '_attachment' ];
			}

			if ( isset( $attributes[ 'background_' . $breakpoint . '_position_x' ] ) ) {
				$background[] = $attributes[ 'background_' . $breakpoint . '_position_x' ] * 100 . '%';
			}
			else {
				$background[] = '50%';
			}

			if ( isset( $attributes[ 'background_' . $breakpoint . '_position_y' ] ) ) {
				$background[] = $attributes[ 'background_' . $breakpoint . '_position_y' ] * 100 . '%';
			}
			else {
				$background[] = '50%';
			}

			if ( isset( $attributes[ 'background_' . $breakpoint . '_size' ] ) ) {
				$background[] = ' / ' . $attributes[ 'background_' . $breakpoint . '_size' ];
			}
		}
		else {
			$parent_breakpoint = self::get_parent_breakpoint( $breakpoint );

			if ( $parent_breakpoint ) {
				$background = self::background_image_rules( $parent_breakpoint, $attributes );
			}
		}

		if ( empty( $background ) ) {
			$background[] = 'none';
		}

		return $background;
	}

	/**
	 * Return the expanded rules for the section background for given breakpoint key.
	 *
	 * @since 1.3.0
	 * @param string $breakpoint The breakpoint key.
	 * @param array $attributes The attributes array.
	 * @return string
	 */
	public static function background_expand_rules( $breakpoint, $attributes ) {
		$background_expand = '';
		$parent_breakpoint = self::get_parent_breakpoint( $breakpoint );

		if ( isset( $attributes[ 'align' ] ) && $attributes[ 'align' ] === 'wide' ) {
			if ( isset( $attributes[ 'background_' . $breakpoint . '_stretch' ] ) && $attributes[ 'background_' . $breakpoint . '_stretch' ] ) {
				$background_expand .= 'calc( 50% - 50vw )';
			}
			else {
				if ( $parent_breakpoint ) {
					$background_expand = self::background_expand_rules( $parent_breakpoint, $attributes );
				}
			}
		}

		if ( empty( $background_expand ) ) {
			$background_expand = '0px';
		}

		return $background_expand;
	}

}
