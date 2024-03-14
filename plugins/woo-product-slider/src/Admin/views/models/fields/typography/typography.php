<?php
/**
 * Field: Typography.
 *
 * @package    woo-product-slider
 * @subpackage woo-product-slider/Admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPF_WPSP_Field_typography' ) ) {
	/**
	 *
	 * Field: typography
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_WPSP_Field_typography extends SPF_WPSP_Fields {

		/**
		 * Chosen
		 *
		 * @var bool
		 */
		public $chosen = false;

		/**
		 * Value
		 *
		 * @var array
		 */
		public $value = array();

		/**
		 * Constructor function.
		 *
		 * @param array  $field field.
		 * @param string $value field value.
		 * @param string $unique field unique.
		 * @param string $where field where.
		 * @param string $parent field parent.
		 * @since 2.0
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Render
		 *
		 * @return void
		 */
		public function render() {

			echo wp_kses_post( $this->field_before() );

			$args = wp_parse_args(
				$this->field,
				array(
					'font_family'        => true,
					'font_weight'        => true,
					'font_style'         => true,
					'font_size'          => true,
					'line_height'        => true,
					'letter_spacing'     => true,
					'text_align'         => true,
					'text_transform'     => true,
					'color'              => true,
					'chosen'             => true,
					'preview'            => true,
					'subset'             => true,
					'multi_subset'       => false,
					'extra_styles'       => false,
					'backup_font_family' => false,
					'font_variant'       => false,
					'word_spacing'       => false,
					'text_decoration'    => false,
					'custom_style'       => false,
					'compact'            => false,
					'exclude'            => '',
					'unit'               => 'px',
					'margin_top'         => false,
					'margin_right'       => false,
					'margin_bottom'      => false,
					'margin-left'        => false,
					'line_height_unit'   => '',
					'preview_text'       => 'The quick brown fox jumps over the lazy dog',
				)
			);

			if ( $args['compact'] ) {
				$args['text_transform'] = false;
				$args['text_align']     = false;
				$args['font_size']      = false;
				$args['line_height']    = false;
				$args['letter_spacing'] = false;
				$args['preview']        = false;
				$args['color']          = false;
			}

			$default_value = array(
				'font-family'        => '',
				'font-weight'        => '',
				'font-style'         => '',
				'font-variant'       => '',
				'font-size'          => '',
				'line-height'        => '',
				'letter-spacing'     => '',
				'word-spacing'       => '',
				'text-align'         => '',
				'text-transform'     => '',
				'text-decoration'    => '',
				'backup-font-family' => '',
				'color'              => '',
				'custom-style'       => '',
				'type'               => '',
				'subset'             => '',
				'extra-styles'       => array(),
				'margin-top'         => '',
				'margin-right'       => '',
				'margin-bottom'      => '',
				'margin-left'        => '',
			);

			$default_value    = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;
			$this->value      = wp_parse_args( $this->value, $default_value );
			$this->chosen     = $args['chosen'];
			$chosen_class     = ( $this->chosen ) ? ' spwps--chosen' : '';
			$line_height_unit = ( ! empty( $args['line_height_unit'] ) ) ? $args['line_height_unit'] : $args['unit'];

			echo '<div class="spwps--typography' . esc_attr( $chosen_class ) . '" data-depend-id="' . esc_attr( $this->field['id'] ) . '" data-unit="' . esc_attr( $args['unit'] ) . '" data-line-height-unit="' . esc_attr( $line_height_unit ) . '" data-exclude="' . esc_attr( $args['exclude'] ) . '">';

			echo '<div class="spwps--blocks spwps--blocks-selects">';

			//
			// Font Family.
			if ( ! empty( $args['font_family'] ) ) {
				echo '<div class="spwps--block">';
				echo '<div class="spwps--title">' . esc_html__( 'Font Family', 'woo-product-slider' ) . '</div>';
				// Ignore phpcs errors because already $this->create_select escaped.
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select( array( $this->value['font-family'] => $this->value['font-family'] ), 'font-family', esc_html__( 'Select a font', 'woo-product-slider' ) );
				echo '</div>';
			}

			//
			// Font Style and Extra Style Select.
			if ( ! empty( $args['font_weight'] ) || ! empty( $args['font_style'] ) ) {
				//
				// Font Style Select.
				echo '<div class="spwps--block spwps--block-font-style hidden">';
				echo '<div class="spwps--title">' . esc_html__( 'Font Style', 'woo-product-slider' ) . '</div>';
				echo '<select class="spwps--font-style-select" data-placeholder="Default">';
				echo '<option value="">' . ( ( ! $this->chosen ) ? esc_html__( 'Default', 'woo-product-slider' ) : '' ) . '</option>';
				if ( ! empty( $this->value['font-weight'] ) || ! empty( $this->value['font-style'] ) ) {
					echo '<option value="' . esc_attr( strtolower( $this->value['font-weight'] . $this->value['font-style'] ) ) . '" selected></option>';
				}
				echo '</select>';
				echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[font-weight]' ) ) . '" class="spwps--font-weight" value="' . esc_attr( $this->value['font-weight'] ) . '" />';
				echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[font-style]' ) ) . '" class="spwps--font-style" value="' . esc_attr( $this->value['font-style'] ) . '" />';

				//
				// Extra Font Style Select.
				if ( ! empty( $args['extra_styles'] ) ) {
					echo '<div class="spwps--block-extra-styles hidden">';
					echo ( ! $this->chosen ) ? '<div class="spwps--title">' . esc_html__( 'Load Extra Styles', 'woo-product-slider' ) . '</div>' : '';
					$placeholder = ( $this->chosen ) ? esc_html__( 'Load Extra Styles', 'woo-product-slider' ) : esc_html__( 'Default', 'woo-product-slider' );
					// Ignore phpcs errors because already $this->create_select escaped.
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $this->create_select( $this->value['extra-styles'], 'extra-styles', $placeholder, true );
					echo '</div>';
				}
				echo '</div>';
			}

			//
			// Subset.
			if ( ! empty( $args['subset'] ) ) {
				echo '<div class="spwps--block spwps--block-subset hidden">';
				echo '<div class="spwps--title">' . esc_html__( 'Subset', 'woo-product-slider' ) . '</div>';
				$subset = ( is_array( $this->value['subset'] ) ) ? $this->value['subset'] : array_filter( (array) $this->value['subset'] );
				// Ignore phpcs errors because already $this->create_select escaped.
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select( $subset, 'subset', esc_html__( 'Default', 'woo-product-slider' ), $args['multi_subset'] );
				echo '</div>';
			}

			//
			// Text Align.
			if ( ! empty( $args['text_align'] ) ) {
				echo '<div class="spwps--block">';
				echo '<div class="spwps--title">' . esc_html__( 'Text Align', 'woo-product-slider' ) . '</div>';
				// Ignore phpcs errors because already $this->create_select escaped.
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select(
					array(
						'inherit' => esc_html__( 'Inherit', 'woo-product-slider' ),
						'left'    => esc_html__( 'Left', 'woo-product-slider' ),
						'center'  => esc_html__( 'Center', 'woo-product-slider' ),
						'right'   => esc_html__( 'Right', 'woo-product-slider' ),
						'justify' => esc_html__( 'Justify', 'woo-product-slider' ),
						'initial' => esc_html__( 'Initial', 'woo-product-slider' ),
					),
					'text-align',
					esc_html__( 'Default', 'woo-product-slider' )
				);
				echo '</div>';
			}

			//
			// Font Variant.
			if ( ! empty( $args['font_variant'] ) ) {
				echo '<div class="spwps--block">';
				echo '<div class="spwps--title">' . esc_html__( 'Font Variant', 'woo-product-slider' ) . '</div>';
				// Ignore phpcs errors because already $this->create_select escaped.
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select(
					array(
						'normal'         => esc_html__( 'Normal', 'woo-product-slider' ),
						'small-caps'     => esc_html__( 'Small Caps', 'woo-product-slider' ),
						'all-small-caps' => esc_html__( 'All Small Caps', 'woo-product-slider' ),
					),
					'font-variant',
					esc_html__( 'Default', 'woo-product-slider' )
				);
				echo '</div>';
			}

			//
			// Text Transform.
			if ( ! empty( $args['text_transform'] ) ) {
				echo '<div class="spwps--block">';
				echo '<div class="spwps--title">' . esc_html__( 'Text Transform', 'woo-product-slider' ) . '</div>';
				// Ignore phpcs errors because already $this->create_select escaped.
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select(
					array(
						'none'       => esc_html__( 'None', 'woo-product-slider' ),
						'capitalize' => esc_html__( 'Capitalize', 'woo-product-slider' ),
						'uppercase'  => esc_html__( 'Uppercase', 'woo-product-slider' ),
						'lowercase'  => esc_html__( 'Lowercase', 'woo-product-slider' ),
					),
					'text-transform',
					esc_html__( 'Default', 'woo-product-slider' )
				);
				echo '</div>';
			}

			//
			// Text Decoration.
			if ( ! empty( $args['text_decoration'] ) ) {
				echo '<div class="spwps--block">';
				echo '<div class="spwps--title">' . esc_html__( 'Text Decoration', 'woo-product-slider' ) . '</div>';
				// Ignore phpcs errors because already $this->create_select escaped.
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select(
					array(
						'none'               => esc_html__( 'None', 'woo-product-slider' ),
						'underline'          => esc_html__( 'Solid', 'woo-product-slider' ),
						'underline double'   => esc_html__( 'Double', 'woo-product-slider' ),
						'underline dotted'   => esc_html__( 'Dotted', 'woo-product-slider' ),
						'underline dashed'   => esc_html__( 'Dashed', 'woo-product-slider' ),
						'underline wavy'     => esc_html__( 'Wavy', 'woo-product-slider' ),
						'underline overline' => esc_html__( 'Overline', 'woo-product-slider' ),
						'line-through'       => esc_html__( 'Line-through', 'woo-product-slider' ),
					),
					'text-decoration',
					esc_html__( 'Default', 'woo-product-slider' )
				);
				echo '</div>';
			}

			echo '</div>';

			echo '<div class="spwps--blocks spwps--blocks-inputs">';

			//
			// Font Size.
			if ( ! empty( $args['font_size'] ) ) {
				echo '<div class="spwps--block">';
				echo '<div class="spwps--title">' . esc_html__( 'Font Size', 'woo-product-slider' ) . '</div>';
				echo '<div class="spwps--input-wrap">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[font-size]' ) ) . '" class="spwps--font-size spwps--input spwps-input-number" value="' . esc_attr( $this->value['font-size'] ) . '" step="any" />';
				echo '<span class="spwps--unit">' . esc_attr( $args['unit'] ) . '</span>';
				echo '</div>';
				echo '</div>';
			}

			//
			// Line Height.
			if ( ! empty( $args['line_height'] ) ) {
				echo '<div class="spwps--block">';
				echo '<div class="spwps--title">' . esc_html__( 'Line Height', 'woo-product-slider' ) . '</div>';
				echo '<div class="spwps--input-wrap">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[line-height]' ) ) . '" class="spwps--line-height spwps--input spwps-input-number" value="' . esc_attr( $this->value['line-height'] ) . '" step="any" />';
				echo '<span class="spwps--unit">' . esc_attr( $line_height_unit ) . '</span>';
				echo '</div>';
				echo '</div>';
			}

			//
			// Letter Spacing.
			if ( ! empty( $args['letter_spacing'] ) ) {
				echo '<div class="spwps--block">';
				echo '<div class="spwps--title">' . esc_html__( 'Letter Spacing', 'woo-product-slider' ) . '</div>';
				echo '<div class="spwps--input-wrap">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[letter-spacing]' ) ) . '" class="spwps--letter-spacing spwps--input spwps-input-number" value="' . esc_attr( $this->value['letter-spacing'] ) . '" step="any" />';
				echo '<span class="spwps--unit">' . esc_attr( $args['unit'] ) . '</span>';
				echo '</div>';
				echo '</div>';
			}

			//
			// Margins Top.
			if ( ! empty( $args['margin_top'] ) ) {
				echo '<div class="spwps--block">';
				echo '<div class="spwps--title">' . esc_html__( 'Margin Top', 'woo-product-slider' ) . '</div>';
				echo '<div class="spwps--input-wrap">';
				echo '<div class="spwps--block"><input type="number" name="' . esc_attr( $this->field_name( '[margin-top]' ) ) . '" class="spwps--margin-top spwps--input spwps-input-number" value="' . esc_attr( $this->value['margin-top'] ) . '" /></div>';
				echo '<div class="spwps--unit">' . esc_attr( $args['unit'] ) . '</div>';
				echo '</div>';
				echo '</div>';
			}

			//
			// Margin Right.
			if ( ! empty( $args['margin_right'] ) ) {
				echo '<div class="spwps--block">';
				echo '<div class="spwps--title">' . esc_html__( 'Margin Right', 'woo-product-slider' ) . '</div>';
				echo '<div class="spwps--input-wrap">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[margin-right]' ) ) . '" class="spwps--margin-right spwps--input spwps-input-number" value="' . esc_attr( $this->value['margin-right'] ) . '" />';
				echo '<div class="spwps--unit">' . esc_attr( $args['unit'] ) . '</div>';
				echo '</div>';
				echo '</div>';
			}

			//
			// Margin Bottom.
			if ( ! empty( $args['margin_bottom'] ) ) {
				echo '<div class="spwps--block">';
				echo '<div class="spwps--title">' . esc_html__( 'Margin Bottom', 'woo-product-slider' ) . '</div>';
				echo '<div class="spwps--input-wrap">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[margin-bottom]' ) ) . '" class="spwps--margin-bottom spwps--input spwps-input-number" value="' . esc_attr( $this->value['margin-bottom'] ) . '" />';
				echo '<div class="spwps--unit">' . esc_attr( $args['unit'] ) . '</div>';
				echo '</div>';
				echo '</div>';
			}

			//
			// Margin Left.
			if ( ! empty( $args['margin_left'] ) ) {
				echo '<div class="spwps--block">';
				echo '<div class="spwps--title">' . esc_html__( 'Margin Left', 'woo-product-slider' ) . '</div>';
				echo '<div class="spwps--input-wrap">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[margin-left]' ) ) . '" class="spwps--margin-left spwps--input spwps-input-number" value="' . esc_attr( $this->value['margin-left'] ) . '" />';
				echo '<div class="spwps--unit">' . esc_attr( $args['unit'] ) . '</div>';
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';
			//
			// Font Color.
			if ( ! empty( $args['color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['color'] ) ) ? $default_value['color'] : '';
				echo '<div class="spwps--block spwps--block-font-color">';
				echo '<div class="spwps--title">' . esc_html__( 'Font Color', 'woo-product-slider' ) . '</div>';
				echo '<div class="spwps-field-color">';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" class="spwps-color spwps--color" value="' . esc_attr( $this->value['color'] ) . '" data-default-color="' . esc_attr( $default_color_attr ) . '" />';
				echo '</div>';
				echo '</div>';
			}
			//
			// Font Hover Color.
			if ( ! empty( $args['hover_color'] ) ) {
				$default_hover_color_attr = ( ! empty( $default_value['hover_color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['hover_color'] ) . '"' : '';
				echo '<div class="spwps--block spwps--block-font-color">';
				echo '<div class="spwps--title">' . esc_html__( 'Hover Font Color', 'woo-product-slider' ) . '</div>';
				echo '<div class="spwps-field-color">';
				// Ignore phpcs errors because already $default_hover_color_attr escaped.
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[hover_color]' ) ) . '" class="spwps-color spwps--color" value="' . esc_attr( $this->value['hover_color'] ) . '"' . $default_hover_color_attr . ' />';
				echo '</div>';
				echo '</div>';
			}
			//
			// Custom style.
			if ( ! empty( $args['custom_style'] ) ) {
				echo '<div class="spwps--block spwps--block-custom-style">';
				echo '<div class="spwps--title">' . esc_html__( 'Custom Style', 'woo-product-slider' ) . '</div>';
				echo '<textarea name="' . esc_attr( $this->field_name( '[custom-style]' ) ) . '" class="spwps--custom-style">' . esc_attr( $this->value['custom-style'] ) . '</textarea>';
				echo '</div>';
			}

			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[type]' ) ) . '" class="spwps--type" value="' . esc_attr( $this->value['type'] ) . '" />';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[unit]' ) ) . '" class="spwps--unit-save" value="' . esc_attr( $args['unit'] ) . '" />';

			echo '</div>';

			echo wp_kses_post( $this->field_after() );

		}

		/**
		 * Create select
		 *
		 * @param  mixed $options options.
		 * @param  mixed $name name.
		 * @param  mixed $placeholder placeholder.
		 * @param  mixed $is_multiple multiple or not.
		 * @return statement
		 */
		public function create_select( $options, $name, $placeholder = '', $is_multiple = false ) {

			$multiple_name = ( $is_multiple ) ? '[]' : '';
			$multiple_attr = ( $is_multiple ) ? ' multiple data-multiple="true"' : '';
			$chosen_rtl    = ( $this->chosen && is_rtl() ) ? ' chosen-rtl' : '';

			$output  = '<select name="' . esc_attr( $this->field_name( '[' . $name . ']' . $multiple_name ) ) . '" class="spwps--' . esc_attr( $name ) . esc_attr( $chosen_rtl ) . '" data-placeholder="' . esc_attr( $placeholder ) . '"' . $multiple_attr . '>';
			$output .= ( ! empty( $placeholder ) ) ? '<option value="">' . esc_attr( ( ! $this->chosen ) ? $placeholder : '' ) . '</option>' : '';

			if ( ! empty( $options ) ) {
				foreach ( $options as $option_key => $option_value ) {
					if ( $is_multiple ) {
						$selected = ( in_array( $option_value, $this->value[ $name ] ) ) ? ' selected' : '';
						$output  .= '<option value="' . esc_attr( $option_value ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $option_value ) . '</option>';
					} else {
						$option_key = ( is_numeric( $option_key ) ) ? $option_value : $option_key;
						$selected   = ( $option_key === $this->value[ $name ] ) ? ' selected' : '';
						$output    .= '<option value="' . esc_attr( $option_key ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $option_value ) . '</option>';
					}
				}
			}

			$output .= '</select>';

			return $output;

		}

		/**
		 * Enqueue fonts scripts.
		 *
		 * @return void
		 */
		public function enqueue() {

			if ( ! wp_script_is( 'spwps-webfontloader' ) ) {

				$webfonts = array();

				$customwebfonts = apply_filters( 'spwps_field_typography_customwebfonts', array() );

				if ( ! empty( $customwebfonts ) ) {
					$webfonts['custom'] = array(
						'label' => esc_html__( 'Custom Web Fonts', 'woo-product-slider' ),
						'fonts' => $customwebfonts,
					);
				}

				$webfonts['safe'] = array(
					'label' => esc_html__( 'Safe Web Fonts', 'woo-product-slider' ),
					'fonts' => apply_filters(
						'spwps_field_typography_safewebfonts',
						array(
							'Arial',
							'Arial Black',
							'Helvetica',
							'Times New Roman',
							'Courier New',
							'Tahoma',
							'Verdana',
							'Impact',
							'Trebuchet MS',
							'Comic Sans MS',
							'Lucida Console',
							'Lucida Sans Unicode',
							'Georgia, serif',
							'Palatino Linotype',
						)
					),
				);

				$webfonts['google'] = array(
					'label' => esc_html__( 'Google Web Fonts', 'woo-product-slider' ),
					'fonts' => array(
						'Open Sans' => array( array( '300', '300italic', 'normal', 'italic', '600', '600italic', '700', '700italic', '800', '800italic' ), array( 'cyrillic-ext', 'cyrillic', 'greek-ext', 'latin-ext', 'greek', 'latin', 'vietnamese' ) ),
					),
				);

				$defaultstyles = apply_filters( 'spwps_field_typography_defaultstyles', array( 'normal', 'italic', '700', '700italic' ) );

				$googlestyles = apply_filters(
					'spwps_field_typography_googlestyles',
					array(
						'100'       => 'Thin 100',
						'100italic' => 'Thin 100 Italic',
						'200'       => 'Extra-Light 200',
						'200italic' => 'Extra-Light 200 Italic',
						'300'       => 'Light 300',
						'300italic' => 'Light 300 Italic',
						'normal'    => 'Normal 400',
						'italic'    => 'Normal 400 Italic',
						'500'       => 'Medium 500',
						'500italic' => 'Medium 500 Italic',
						'600'       => 'Semi-Bold 600',
						'600italic' => 'Semi-Bold 600 Italic',
						'700'       => 'Bold 700',
						'700italic' => 'Bold 700 Italic',
						'800'       => 'Extra-Bold 800',
						'800italic' => 'Extra-Bold 800 Italic',
						'900'       => 'Black 900',
						'900italic' => 'Black 900 Italic',
					)
				);

				$webfonts = apply_filters( 'spwps_field_typography_webfonts', $webfonts );

				wp_localize_script(
					'spwps',
					'spwps_typography_json',
					array(
						'webfonts'      => $webfonts,
						'defaultstyles' => $defaultstyles,
						'googlestyles'  => $googlestyles,
					)
				);

			}

		}
	}
}
