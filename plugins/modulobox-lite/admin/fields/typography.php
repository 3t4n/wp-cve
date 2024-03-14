<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ModuloBox_Typography_Field' ) ) {

	class ModuloBox_Typography_Field extends ModuloBox_Settings_field {

		/**
		 * Standard font families
		 *
		 * @since 1.0.0
		 * @access private
		 */
		private static $std_fonts = array(
			'Arial, Helvetica, sans-serif'                     => 'Arial, Helvetica',
			'Arial Black, Gadget, sans-serif'                  => 'Arial Black',
			'Comic Sans MS, cursive, sans-serif'               => 'Comic Sans MS',
			'Courier New, Courier, monospace'                  => 'Courier New',
			'Georgia, serif'                                   => 'Georgia, serif',
			'Impact, Charcoal, sans-serif'                     => 'Impact',
			'Lucida Console, Monaco, monospace'                => 'Lucida Console',
			'Lucida Sans Unicode, Lucida Grande, sans-serif'   => 'Lucida Sans Unicode',
			'Palatino Linotype, Book Antiqua, Palatino, serif' => 'Palatino Linotype',
			'Tahoma, Geneva, sans-serif'                       => 'Tahoma',
			'Times New Roman, Times, serif'                    => 'Times New Roman',
			'Trebuchet MS, Helvetica, sans-serif'              => 'Trebuchet MS',
			'Verdana, Geneva, sans-serif'                      => 'Verdana',
		);

		/**
		 * Standard font weights
		 *
		 * @since 1.0.0
		 * @access private
		 */
		private static $std_weights = array(
			100,
			200,
			300,
			400,
			500,
			600,
			700,
			800,
			900,
		);

		/**
		 * Render HTML field
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param array $args Contains all field parameters
		 */
		static function render( $args ) {

			echo $args['desc'];
			echo $args['premium'];

			echo '<div class="mobx-typography">';

				echo '<ul class="mobx-typography-tabs">';

					$count = 0;
					foreach ( $args['default'] as $arg ) {

						$active = ! $count ? ' mobx-tab-active' : '';
						echo '<li class="mobx-typography-tab' . $active . '">' . esc_html( $arg['device'] ) . '</li>';
						$count++;

					}

				echo '</ul>';

				$count = 0;
				foreach ( $args['default'] as $size => $name ) {

					$active = ! $count ? ' mobx-fields-active' : '';

					echo '<fieldset class="mobx-typography-fields' . $active . '">';

						self::font_families( $size, $args );
						self::font_subsets( $size, $args );
						self::font_variants( $size, $args );

						echo '<br>';

						self::font_color( $size, $args );
						self::font_styles( $size, $args );
						self::text_align( $size, $args );

						echo '<br>';

						self::font_size( $size, $args );

						echo '<br>';

						self::line_height( $size, $args );

					echo '</fieldset>';

					$count++;

				}

			echo '</div>';

		}

		/**
		 * Font families select field
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param string $size
		 * @param array $args
		 */
		static function font_families( $size, $args ) {

			echo '<select class="mobx-select mobx-font-families" title="' . esc_attr__( 'Font Family' , 'modulobox' ) . '" name="' . esc_attr( $args['name'] )  . '[' . esc_attr( $size )  . '][font-family]" data-value="' . esc_attr( $args['value'][ esc_attr( $size ) ]['font-family'] ) . '">';

				echo '<option value="">' . esc_html__( 'Inherit Font Family' , 'modulobox' ) . '</option>';

				foreach ( self::$std_fonts as $font => $name ) {
					echo '<option value="' . esc_attr( $font ) . '">' . esc_html( $name ) . '</option>';
				}

			echo '</select>';

		}

		/**
		 * Font subsets select field
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param string $size
		 * @param array $args
		 */
		static function font_subsets( $size, $args ) {

			echo '<select class="mobx-select mobx-font-subsets" title="' . esc_attr__( 'Font Subset' , 'modulobox' ) . '" name="' . esc_attr( $args['name'] )  . '[' . esc_attr( $size )  . '][font-subset]" data-value="' . esc_attr( $args['value'][ esc_attr( $size ) ]['font-subset'] ) . '"></select>';

		}

		/**
		 * Font variants select field
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param string $size
		 * @param array $args
		 */
		static function font_variants( $size, $args ) {

			echo '<select class="mobx-select mobx-font-variants" title="' . esc_attr__( 'Font Weight' , 'modulobox' ) . '" name="' . esc_attr( $args['name'] )  . '[' . esc_attr( $size )  . '][font-weight]" data-value="' . esc_attr( $args['value'][ esc_attr( $size ) ]['font-weight'] ) . '">';

				foreach ( self::$std_weights as $weight ) {
					echo '<option value="' . esc_attr( $weight ) . '" ' . selected( esc_attr( $args['value'][ esc_attr( $size ) ]['font-weight'] ), esc_attr( $weight ), 0 ) . '>' . esc_html( $weight ) . '</option>';
				}

			echo '</select>';

		}

		/**
		 * Font style checkbox fields
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param string $size
		 * @param array $args
		 */
		static function font_styles( $size, $args ) {

			echo '<div class="mobx-typography-button">';
				echo '<input type="checkbox" title="' . esc_attr__( 'Uppercase' , 'modulobox' ) . '" class="mobx-radio" name="' . esc_attr( $args['name'] )  . '[' . esc_attr( $size )  . '][text-transform]" value="uppercase" ' . checked( esc_attr( $args['value'][ esc_attr( $size ) ]['text-transform'] ), 'uppercase', 0 ) . '>';
				echo '<label class="dashicons dashicons-editor-textcolor"></label>';
			echo '</div>';

			echo '<div class="mobx-typography-button">';
				echo '<input type="checkbox" title="' . esc_attr__( 'Italic' , 'modulobox' ) . '" class="mobx-radio" name="' . esc_attr( $args['name'] )  . '[' . esc_attr( $size )  . '][font-style]" value="italic" ' . checked( esc_attr( $args['value'][ esc_attr( $size ) ]['font-style'] ), 'italic', 0 ) . '>';
				echo '<label class="dashicons dashicons-editor-italic"></label>';
			echo '</div>';

			echo '<div class="mobx-typography-button">';
				echo '<input type="checkbox" title="' . esc_attr__( 'Underline' , 'modulobox' ) . '" class="mobx-radio" name="' . esc_attr( $args['name'] )  . '[' . esc_attr( $size )  . '][text-decoration]" value="underline" ' . checked( esc_attr( $args['value'][ esc_attr( $size ) ]['text-decoration'] ), 'underline', 0 ) . '>';
				echo '<label class="dashicons dashicons-editor-underline"></label>';
			echo '</div>';

		}

		/**
		 * Text alignment radio fields
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param string $size
		 * @param array $args
		 */
		static function text_align( $size, $args ) {

			echo '<div class="mobx-typography-button">';
				echo '<input type="radio" title="' . esc_attr__( 'Text Align Left' , 'modulobox' ) . '" class="mobx-radio" name="' . esc_attr( $args['name'] )  . '[' . esc_attr( $size )  . '][text-align]" value="left" ' . checked( esc_attr( $args['value'][ esc_attr( $size ) ]['text-align'] ), 'left', 0 ) . '>';
				echo '<label class="dashicons dashicons-editor-alignleft"></label>';
			echo '</div>';

			echo '<div class="mobx-typography-button">';
				echo '<input type="radio" title="' . esc_attr__( 'Text Align Center' , 'modulobox' ) . '" class="mobx-radio" name="' . esc_attr( $args['name'] )  . '[' . esc_attr( $size )  . '][text-align]" value="center" ' . checked( esc_attr( $args['value'][ esc_attr( $size ) ]['text-align'] ), 'center', 0 ) . '>';
				echo '<label class="dashicons dashicons-editor-aligncenter"></label>';
			echo '</div>';

			echo '<div class="mobx-typography-button">';
				echo '<input type="radio" title="' . esc_attr__( 'Text Align Right' , 'modulobox' ) . '" class="mobx-radio" name="' . esc_attr( $args['name'] )  . '[' . esc_attr( $size )  . '][text-align]" value="right" ' . checked( esc_attr( $args['value'][ esc_attr( $size ) ]['text-align'] ), 'right', 0 ) . '>';
				echo '<label class="dashicons dashicons-editor-alignright"></label>';
			echo '</div>';

		}

		/**
		 * Font color field
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param string $size
		 * @param array $args
		 */
		static function font_color( $size, $args ) {

			echo '<input type="text" class="mobx-color-picker" name="' . esc_attr( $args['name'] )  . '[' . esc_attr( $size )  . '][color]" value="' . esc_attr( $args['value'][ esc_attr( $size ) ]['color'] ) . '" data-alpha="false">';

		}

		/**
		 * Font size field
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param string $size
		 * @param array $args
		 */
		static function font_size( $size, $args ) {

			echo '<label>' . esc_html__( 'Font Size' , 'modulobox' ) . '</label>';
			echo '<div class="mobx-ui-slider" data-value="' . esc_attr( $args['value'][ esc_attr( $size ) ]['font-size'] ) . '" data-min="1" data-max="60" data-step="1" data-unit="px"></div>';
			echo '<input type="text" class="mobx-ui-slider-value" name="' . esc_attr( $args['name'] ) . '[' . esc_attr( $size )  . '][font-size]" value="' . esc_attr( $args['value'][ esc_attr( $size ) ]['font-size'] ) . 'px" autocomplete="off">';

		}

		/**
		 * Line height field
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param string $size
		 * @param array $args
		 */
		static function line_height( $size, $args ) {

			echo '<label>' . esc_html__( 'Line Height' , 'modulobox' ) . '</label>';
			echo '<div class="mobx-ui-slider" data-value="' . esc_attr( $args['value'][ esc_attr( $size ) ]['line-height'] ) . '" data-min="1" data-max="60" data-step="1" data-unit="px"></div>';
			echo '<input type="text" class="mobx-ui-slider-value" name="' . esc_attr( $args['name'] ) . '[' . esc_attr( $size )  . '][line-height]" value="' . esc_attr( $args['value'][ esc_attr( $size ) ]['line-height'] ) . 'px" autocomplete="off">';

		}

		/**
		 * Enqueue scripts and styles
		 *
		 * @since 1.0.0
		 * @access static
		 */
		static function scripts() {}

		/**
		 * Normalize field parameters
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param array $field
		 * @return array
		 */
		static function normalize( $field ) {

			$std_props = array(
				'device'          => __( 'Desktop', 'modulobox' ),
				'line-height'     => '',
				'font-family'     => '',
				'font-size'       => '',
				'font-style'      => '',
				'font-subset'     => '',
				'font-weight'     => '',
				'text-align'      => '',
				'text-transform'  => '',
				'text-decoration' => '',
				'color'           => '',
			);

			$default = array(
				'-1' => $std_props,
			);

			// Normalize each size
			if ( isset( $field['default'] ) && ! empty( $field['default'] ) ) {

				foreach ( $field['default'] as $size => $prop ) {

					$field['default'][ $size ] = isset( $field['default'][ $size ] ) ? wp_parse_args( $field['default'][ $size ], $std_props ) : $std_props;
					$field['value'][ $size ]   = isset( $field['value'][ $size ] ) ? wp_parse_args( $field['value'][ $size ], $std_props ) : $std_props;

				}
			} else {

				$field['default'] =
				$field['value']   = $default;

			}

			return wp_parse_args( $field, $default );

		}

		/**
		 * Sanitize field value
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param mixed $val
		 * @param array $args
		 * @return string
		 */
		static function sanitize( $val, $args ) {

			return $args['default'];

		}
	}

}
