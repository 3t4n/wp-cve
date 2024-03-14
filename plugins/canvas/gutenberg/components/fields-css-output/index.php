<?php
/**
 * Parse fields data and generate styles output.
 *
 * @package Canvas
 */

require_once CNVS_PATH . 'gutenberg/utils/is-field-visible/index.php';

if ( ! class_exists( 'CNVS_Gutenberg_Fields_CSS_Output' ) ) {

	/**
	 * Class Gutenberg Fields CSS Output.
	 */
	class CNVS_Gutenberg_Fields_CSS_Output {
		/**
		 * Prepare CSS for selected fields.
		 *
		 * @param string $selector CSS selector.
		 * @param array  $fields Fields data.
		 * @param array  $attributes Block attributes.
		 *
		 * @return string
		 */
		public static function get( $selector, $fields = array(), $attributes = array() ) {
			$schemes         = cnvs_gutenberg()->get_schemes_data();
			$breakpoints     = cnvs_gutenberg()->get_breakpoints_data();
			$all_breakpoints = cnvs_gutenberg()->get_all_breakpoints_data();

			$result = '';

			foreach ( $fields as $field ) {
				if ( ! isset( $field['type'] ) || ! isset( $field['output'] ) ) {
					continue;
				}

				// check if field visible.
				if ( ! CNVS_Gutenberg_Utils_Is_Field_Visible::check( $field, $attributes, $fields ) ) {
					continue;
				}

				foreach ( $field['output'] as $data ) {
					$result .= self::prepare_styles_from_params( $selector, self::get_field_value( $field, $attributes ), $data );

					if ( $schemes && ( 'color' === $field['type'] ) ) {

						foreach ( $schemes as $name => $scheme ) {
							$rule = sprintf( '[data-scheme="%s"] %s', $name, $selector );

							if ( get_theme_support( 'canvas-support-inverse-scheme' ) && 'dark' === $name ) {
								$rule = sprintf( '[data-scheme="inverse"] %s, [data-scheme="dark"] %s', $selector, $selector );
							}

							if ( $name && 'default' !== $name ) {
								$result .= self::prepare_styles_from_params(
									$rule,
									self::get_field_value( $field, $attributes, $name ),
									$data
								);
							}
						}
					}

					if ( isset( $field['responsive'] ) && $field['responsive'] ) {
						foreach ( $all_breakpoints as $name => $breakpoint ) {
							if ( $name && 'desktop' !== $name ) {

								$rule = $selector;

								// If exist scheme.
								if ( isset( $breakpoint['scheme'] ) ) {
									$rule = sprintf( '[data-scheme="%s"] %s', $breakpoint['scheme'], $selector );

									if ( get_theme_support( 'canvas-support-inverse-scheme' ) && 'dark' === $breakpoint['scheme'] ) {
										$rule = sprintf( '[data-scheme="inverse"] %s, [data-scheme="dark"] %s', $selector, $selector );
									}
								}

								$result .= self::prepare_styles_from_params(
									$rule,
									self::get_field_value( $field, $attributes, $name ),
									array_merge(
										$data,
										array(
											'media_query' => '@media (max-width: ' . apply_filters( 'canvas_blocks_dynamic_breakpoint_width', $breakpoint['width'], $field ) . 'px)',
										)
									)
								);
							}
						}
					}
				}
			}

			return $result;
		}

		/**
		 * Get current field value. If value doesn't exist, use default value.
		 *
		 * @param array  $field      Field data.
		 * @param array  $attributes Field attributes.
	 	 * @param string $breakpoint Breakpoint name.
		 *
		 * @return mixed Field value.
		 */
		public static function get_field_value( $field, $attributes, $breakpoint = '' ) {
			$suffix = '';

			if ( $breakpoint ) {
				$suffix = '_' . $breakpoint;
			}

			if ( isset( $attributes[ $field['key'] . $suffix ] ) ) {
				return $attributes[ $field['key'] . $suffix ];
			} elseif ( isset( $field[ 'default' . $suffix ] ) ) {
				return $field[ 'default' . $suffix ];
			}

			return null;
		}

		/**
		 * Prepare styles from params
		 * Params example:
			array(
				'element'       => '$',
				'property'      => 'height',
				'value_pattern' => 'linear-gradient(to bottom, $ 14%,#7db9e8 77%)',
				'media_query'   => '@media ( min-width: 760px )',
				'units'         => 'px',
				'prefix'        => 'calc(1px + ',
				'suffix'        => ') !important',
			)
		 *
		 * @param string $selector CSS selector.
		 * @param mixed  $value Property value.
		 * @param array  $params Output params.
		 *
		 * @return string
		 */
		public static function prepare_styles_from_params( $selector, $value, $params ) {
			$result = '';

			if ( ! $selector || ! isset( $value ) || '' === $value || null === $value || ! isset( $params['property'] ) ) {
				return $result;
			}

			// Check for context.
			if ( isset( $params['context'] ) && ! in_array( 'front', $params['context'], true ) ) {
				return $result;
			}

			// Custom selector pattern.
			if ( isset( $params['element'] ) ) {
				$selector = str_replace( '$', $selector, $params['element'] );
			}

			// Reverse smart selector.
			if ( isset( $params['reverse'] ) && isset( $params['reverse_max'] ) ) {
				$multi_selector = __return_empty_string();

				for ( $iteration = 1; $iteration <= $params['reverse_max']; $iteration++ ) {
					if ( $iteration <= $value ) {
						continue;
					}
					$multi_selector .= ( $multi_selector ? ', ' : '' ) . str_replace( '$numb', $iteration, $params['reverse'] );
				}

				$selector = str_replace( '$', $selector, $multi_selector );
			}

			// Value pattern.
			if ( isset( $params['value_pattern'] ) ) {
				$value = str_replace( '$', $value, $params['value_pattern'] );
			}

			// Prefix.
			if ( isset( $params['prefix'] ) ) {
				$value = $params['prefix'] . $value;
			}

			// Units.
			if ( isset( $params['units'] ) ) {
				$value = $value . $params['units'];
			}

			// Suffix.
			if ( isset( $params['suffix'] ) ) {
				$value = $value . $params['suffix'];
			}

			$property = $params['property'];

			// Prepare CSS.
			$result = "{$selector} { {$property}: {$value}; }";

			// Add media query.
			if ( isset( $params['media_query'] ) ) {
				$media_query = $params['media_query'];

				$result = "{$media_query} { {$result} }";
			}

			return $result;
		}
	}
}
