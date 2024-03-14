<?php
/**
 * Framework Typography field file.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/Framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WP_TABS_Field_typography' ) ) {
	/**
	 *
	 * Field: typography
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WP_TABS_Field_typography extends SP_WP_TABS_Fields {

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
		 * Typography field constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Render field
		 *
		 * @return void
		 */
		public function render() {

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->field_before();

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
					'hover_color'        => false,
					'active_color'       => false,
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
					'exclude'            => '',
					'unit'               => 'px',
					'line_height_unit'   => '',
					'preview_text'       => 'The quick brown fox jumps over the lazy dog',
					'margin_bottom'      => '',
				)
			);

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
				'hover_color'        => '',
				'active_color'       => '',
				'custom-style'       => '',
				'type'               => '',
				'subset'             => '',
				'extra-styles'       => array(),
				'margin-bottom'      => '',
			);

			$default_value    = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;
			$this->value      = wp_parse_args( $this->value, $default_value );
			$this->chosen     = $args['chosen'];
			$chosen_class     = ( $this->chosen ) ? ' wptabspro--chosen' : '';
			$line_height_unit = ( ! empty( $args['line_height_unit'] ) ) ? $args['line_height_unit'] : $args['unit'];

			echo '<div class="wptabspro--typography' . esc_attr( $chosen_class ) . '" data-unit="' . esc_attr( $args['unit'] ) . '" data-line-height-unit="' . esc_attr( $line_height_unit ) . '" data-exclude="' . esc_attr( $args['exclude'] ) . '">';

			echo '<div class="wptabspro--blocks wptabspro--blocks-selects">';

			//
			// Font Family.
			if ( ! empty( $args['font_family'] ) ) {
				echo '<div class="wptabspro--block">';
				echo '<div class="wptabspro--title">' . esc_html__( 'Font Family', 'wp-expand-tabs-free' ) . '</div>';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select( array( $this->value['font-family'] => $this->value['font-family'] ), 'font-family', esc_html__( 'Select a font', 'wp-expand-tabs-free' ) );
				echo '</div>';
			}

			//
			// Backup Font Family.
			if ( ! empty( $args['backup_font_family'] ) ) {
				echo '<div class="wptabspro--block wptabspro--block-backup-font-family hidden">';
				echo '<div class="wptabspro--title">' . esc_html__( 'Backup Font Family', 'wp-expand-tabs-free' ) . '</div>';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select(
					apply_filters(
						'wptabspro_field_typography_backup_font_family',
						array(
							'Arial, Helvetica, sans-serif',
							"'Arial Black', Gadget, sans-serif",
							"'Comic Sans MS', cursive, sans-serif",
							'Impact, Charcoal, sans-serif',
							"'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
							'Tahoma, Geneva, sans-serif',
							"'Trebuchet MS', Helvetica, sans-serif'",
							'Verdana, Geneva, sans-serif',
							"'Courier New', Courier, monospace",
							"'Lucida Console', Monaco, monospace",
							'Georgia, serif',
							'Palatino Linotype',
						)
					),
					'backup-font-family',
					esc_html__( 'Default', 'wp-expand-tabs-free' )
				);
				echo '</div>';
			}

			//
			// Font Style and Extra Style Select.
			if ( ! empty( $args['font_weight'] ) || ! empty( $args['font_style'] ) ) {

				//
				// Font Style Select.
				echo '<div class="wptabspro--block wptabspro--block-font-style hidden">';
				echo '<div class="wptabspro--title">' . esc_html__( 'Font Style', 'wp-expand-tabs-free' ) . '</div>';
				echo '<select class="wptabspro--font-style-select" data-placeholder="Default">';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<option value="">' . ( ! $this->chosen ? esc_html__( 'Default', 'wp-expand-tabs-free' ) : '' ) . '</option>';
				if ( ! empty( $this->value['font-weight'] ) || ! empty( $this->value['font-style'] ) ) {
					echo '<option value="' . esc_attr( strtolower( $this->value['font-weight'] . $this->value['font-style'] ) ) . '" selected></option>';
				}
				echo '</select>';
				echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[font-weight]' ) ) . '" class="wptabspro--font-weight" value="' . esc_attr( $this->value['font-weight'] ) . '" />';
				echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[font-style]' ) ) . '" class="wptabspro--font-style" value="' . esc_attr( $this->value['font-style'] ) . '" />';

				//
				// Extra Font Style Select.
				if ( ! empty( $args['extra_styles'] ) ) {
					echo '<div class="wptabspro--block-extra-styles hidden">';
					echo ( ! $this->chosen ) ? '<div class="wptabspro--title">' . esc_html__( 'Load Extra Styles', 'wp-expand-tabs-free' ) . '</div>' : '';
					$placeholder = ( $this->chosen ) ? esc_html__( 'Load Extra Styles', 'wp-expand-tabs-free' ) : esc_html__( 'Default', 'wp-expand-tabs-free' );
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $this->create_select( $this->value['extra-styles'], 'extra-styles', $placeholder, true );
					echo '</div>';
				}

				echo '</div>';

			}

			//
			// Subset.
			if ( ! empty( $args['subset'] ) ) {
				echo '<div class="wptabspro--block wptabspro--block-subset hidden">';
				echo '<div class="wptabspro--title">' . esc_html__( 'Subset', 'wp-expand-tabs-free' ) . '</div>';
				$subset = ( is_array( $this->value['subset'] ) ) ? $this->value['subset'] : array_filter( (array) $this->value['subset'] );
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select( $subset, 'subset', esc_html__( 'Default', 'wp-expand-tabs-free' ), $args['multi_subset'] );
				echo '</div>';
			}

			//
			// Text Align.
			if ( ! empty( $args['text_align'] ) ) {
				echo '<div class="wptabspro--block">';
				echo '<div class="wptabspro--title">' . esc_html__( 'Text Align', 'wp-expand-tabs-free' ) . '</div>';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select(
					array(
						'inherit' => esc_html__( 'Inherit', 'wp-expand-tabs-free' ),
						'left'    => esc_html__( 'Left', 'wp-expand-tabs-free' ),
						'center'  => esc_html__( 'Center', 'wp-expand-tabs-free' ),
						'right'   => esc_html__( 'Right', 'wp-expand-tabs-free' ),
						'justify' => esc_html__( 'Justify', 'wp-expand-tabs-free' ),
						'initial' => esc_html__( 'Initial', 'wp-expand-tabs-free' ),
					),
					'text-align',
					esc_html__( 'Default', 'wp-expand-tabs-free' )
				);
				echo '</div>';
			}

			//
			// Font Variant.
			if ( ! empty( $args['font_variant'] ) ) {
				echo '<div class="wptabspro--block">';
				echo '<div class="wptabspro--title">' . esc_html__( 'Font Variant', 'wp-expand-tabs-free' ) . '</div>';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select(
					array(
						'normal'         => esc_html__( 'Normal', 'wp-expand-tabs-free' ),
						'small-caps'     => esc_html__( 'Small Caps', 'wp-expand-tabs-free' ),
						'all-small-caps' => esc_html__( 'All Small Caps', 'wp-expand-tabs-free' ),
					),
					'font-variant',
					esc_html__( 'Default', 'wp-expand-tabs-free' )
				);
				echo '</div>';
			}

			//
			// Text Transform.
			if ( ! empty( $args['text_transform'] ) ) {
				echo '<div class="wptabspro--block">';
				echo '<div class="wptabspro--title">' . esc_html__( 'Text Transform', 'wp-expand-tabs-free' ) . '</div>';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select(
					array(
						'none'       => esc_html__( 'None', 'wp-expand-tabs-free' ),
						'capitalize' => esc_html__( 'Capitalize', 'wp-expand-tabs-free' ),
						'uppercase'  => esc_html__( 'Uppercase', 'wp-expand-tabs-free' ),
						'lowercase'  => esc_html__( 'Lowercase', 'wp-expand-tabs-free' ),
					),
					'text-transform',
					esc_html__( 'Default', 'wp-expand-tabs-free' )
				);
				echo '</div>';
			}

			//
			// Text Decoration.
			if ( ! empty( $args['text_decoration'] ) ) {
				echo '<div class="wptabspro--block">';
				echo '<div class="wptabspro--title">' . esc_html__( 'Text Decoration', 'wp-expand-tabs-free' ) . '</div>';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->create_select(
					array(
						'none'               => esc_html__( 'None', 'wp-expand-tabs-free' ),
						'underline'          => esc_html__( 'Solid', 'wp-expand-tabs-free' ),
						'underline double'   => esc_html__( 'Double', 'wp-expand-tabs-free' ),
						'underline dotted'   => esc_html__( 'Dotted', 'wp-expand-tabs-free' ),
						'underline dashed'   => esc_html__( 'Dashed', 'wp-expand-tabs-free' ),
						'underline wavy'     => esc_html__( 'Wavy', 'wp-expand-tabs-free' ),
						'underline overline' => esc_html__( 'Overline', 'wp-expand-tabs-free' ),
						'line-through'       => esc_html__( 'Line-through', 'wp-expand-tabs-free' ),
					),
					'text-decoration',
					esc_html__( 'Default', 'wp-expand-tabs-free' )
				);
				echo '</div>';
			}

			echo '</div>';

			echo '<div class="wptabspro--blocks wptabspro--blocks-inputs">';

			//
			// Font Size.
			if ( ! empty( $args['font_size'] ) ) {
				echo '<div class="wptabspro--block">';
				echo '<div class="wptabspro--title">' . esc_html__( 'Font Size', 'wp-expand-tabs-free' ) . '</div>';
				echo '<div class="wptabspro--input-wrap">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[font-size]' ) ) . '" class="wptabspro--font-size wptabspro--input wptabspro-input-number" value="' . esc_attr( $this->value['font-size'] ) . '" />';
				echo '<span class="wptabspro--unit">' . esc_attr( $args['unit'] ) . '</span>';
				echo '</div>';
				echo '</div>';
			}

			//
			// Line Height.
			if ( ! empty( $args['line_height'] ) ) {
				echo '<div class="wptabspro--block">';
				echo '<div class="wptabspro--title">' . esc_html__( 'Line Height', 'wp-expand-tabs-free' ) . '</div>';
				echo '<div class="wptabspro--input-wrap">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[line-height]' ) ) . '" class="wptabspro--line-height wptabspro--input wptabspro-input-number" value="' . esc_attr( $this->value['line-height'] ) . '" />';
				echo '<span class="wptabspro--unit">' . esc_attr( $line_height_unit ) . '</span>';
				echo '</div>';
				echo '</div>';
			}

			//
			// Letter Spacing.
			if ( ! empty( $args['letter_spacing'] ) ) {
				echo '<div class="wptabspro--block">';
				echo '<div class="wptabspro--title">' . esc_html__( 'Letter Spacing', 'wp-expand-tabs-free' ) . '</div>';
				echo '<div class="wptabspro--input-wrap">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[letter-spacing]' ) ) . '" class="wptabspro--letter-spacing wptabspro--input wptabspro-input-number" value="' . esc_attr( $this->value['letter-spacing'] ) . '" />';
				echo '<span class="wptabspro--unit">' . esc_attr( $args['unit'] ) . '</span>';
				echo '</div>';
				echo '</div>';
			}

			//
			// Word Spacing.
			if ( ! empty( $args['word_spacing'] ) ) {
				echo '<div class="wptabspro--block">';
				echo '<div class="wptabspro--title">' . esc_html__( 'Word Spacing', 'wp-expand-tabs-free' ) . '</div>';
				echo '<div class="wptabspro--input-wrap">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[word-spacing]' ) ) . '" class="wptabspro--word-spacing wptabspro--input wptabspro-input-number" value="' . esc_attr( $this->value['word-spacing'] ) . '" />';
				echo '<span class="wptabspro--unit">' . esc_attr( $args['unit'] ) . '</span>';
				echo '</div>';
				echo '</div>';
			}

			echo '</div>';

			//
			// Font Color.
			if ( ! empty( $args['color'] ) ) {
				echo '<div class="wptabspro--blocks wptabspro--blocks-color">';
				$default_color_attr = ( ! empty( $default_value['color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['color'] ) . '"' : '';
				echo '<div class="wptabspro--block wptabspro--block-font-color">';
				echo '<div class="wptabspro--title">' . esc_html__( 'Font Color', 'wp-expand-tabs-free' ) . '</div>';
				echo '<div class="wptabspro-field-color">';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" class="wptabspro-color wptabspro--color" value="' . esc_attr( $this->value['color'] ) . '"' . $default_color_attr . ' />';
				echo '</div>';
				echo '</div>';

				//
				// Font Hover Color.
				if ( ! empty( $args['hover_color'] ) ) {
					$default_hover_color_attr = ( ! empty( $default_value['hover_color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['hover_color'] ) . '"' : '';
					echo '<div class="wptabspro--block wptabspro--block-font-color">';
					echo '<div class="wptabspro--title">' . esc_html__( 'Font Hover Color', 'wp-expand-tabs-free' ) . '</div>';
					echo '<div class="wptabspro-field-color">';
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<input type="text" name="' . esc_attr( $this->field_name( '[hover_color]' ) ) . '" class="wptabspro-color wptabspro--color" value="' . esc_attr( $this->value['hover_color'] ) . '"' . $default_hover_color_attr . ' />';
					echo '</div>';
					echo '</div>';
				}
				//
				// Font active Color.
				if ( ! empty( $args['active_color'] ) ) {
					$default_active_color_attr = ( ! empty( $default_value['active_color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['active_color'] ) . '"' : '';
					echo '<div class="wptabspro--block wptabspro--block-font-color">';
					echo '<div class="wptabspro--title">' . esc_html__( 'Font Active Color', 'wp-expand-tabs-free' ) . '</div>';
					echo '<div class="wptabspro-field-color">';
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<input type="text" name="' . esc_attr( $this->field_name( '[active_color]' ) ) . '" class="wptabspro-color wptabspro--color" value="' . esc_attr( $this->value['active_color'] ) . '"' . $default_active_color_attr . ' />';
					echo '</div>';
					echo '</div>';
				}

				echo '</div>';
			}

			//
			// Custom style.
			if ( ! empty( $args['custom_style'] ) ) {
				echo '<div class="wptabspro--block wptabspro--block-custom-style">';
				echo '<div class="wptabspro--title">' . esc_html__( 'Custom Style', 'wp-expand-tabs-free' ) . '</div>';
				echo '<textarea name="' . esc_attr( $this->field_name( '[custom-style]' ) ) . '" class="wptabspro--custom-style">' . esc_attr( $this->value['custom-style'] ) . '</textarea>';
				echo '</div>';
			}

			// Margin Bottom.
			if ( ! empty( $args['margin_bottom'] ) ) {
				echo '<div class="wptabspro--block wptabspro--block-margin">';
				echo '<div class="wptabspro--title">' . esc_html__( 'Margin Bottom', 'wp-expand-tabs-free' ) . '</div>';
				echo '<div class="wptabspro--blocks">';
				echo '<div class="wptabspro--block wptabspro--unit"><i class="fa fa-long-arrow-down"></i></div>';
				echo '<div class="wptabspro--block"><input type="number" name="' . $this->field_name( '[margin-bottom]' ) . '" class="wptabspro--margin-bottom wptabspro--input wptabspro-number" value="' . $this->value['margin-bottom'] . '" /></div>';// phpcs:ignore
				echo '<div class="wptabspro--block wptabspro--unit">' . wp_kses_post( $args['unit'] ) . '</div>';
				echo '</div>';
				echo '</div>';
			}

			//
			// Preview.
			$always_preview = ( 'always' !== $args['preview'] ) ? ' hidden' : '';

			if ( ! empty( $args['preview'] ) ) {
				echo '<div class="wptabspro--block wptabspro--block-preview' . esc_attr( $always_preview ) . '">';
				echo '<div class="wptabspro--toggle fa fa-toggle-off"></div>';
				echo '<div class="wptabspro--preview">' . esc_attr( $args['preview_text'] ) . '</div>';
				echo '</div>';
			}

			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[type]' ) ) . '" class="wptabspro--type" value="' . esc_attr( $this->value['type'] ) . '" />';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[unit]' ) ) . '" class="wptabspro--unit-save" value="' . esc_attr( $args['unit'] ) . '" />';

			echo '</div>';

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->field_after();

		}

		/**
		 * Create select
		 *
		 * @param  array  $options options.
		 * @param  string $name name.
		 * @param  mixed  $placeholder placeholder.
		 * @param  bool   $is_multiple is_multiple.
		 * @return statement
		 */
		public function create_select( $options, $name, $placeholder = '', $is_multiple = false ) {

			$multiple_name = ( $is_multiple ) ? '[]' : '';
			$multiple_attr = ( $is_multiple ) ? ' multiple data-multiple="true"' : '';
			$chosen_rtl    = ( $this->chosen && is_rtl() ) ? ' chosen-rtl' : '';

			$output  = '<select name="' . esc_attr( $this->field_name( '[' . $name . ']' . $multiple_name ) ) . '" class="wptabspro--' . esc_attr( $name ) . esc_attr( $chosen_rtl ) . '" data-placeholder="' . esc_attr( $placeholder ) . '"' . $multiple_attr . '>';
			$output .= ( ! empty( $placeholder ) ) ? '<option value="">' . esc_attr( ( ! $this->chosen ) ? $placeholder : '' ) . '</option>' : '';

			if ( ! empty( $options ) ) {
				foreach ( $options as $option_key => $option_value ) {
					if ( $is_multiple ) {
						$selected = ( in_array( $option_value, $this->value[ $name ], true ) ) ? ' selected' : '';
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
		 * Field enqueue script.
		 *
		 * @return void
		 */
		public function enqueue() {

			if ( ! wp_script_is( 'wptabspro-webfontloader' ) ) {

				SP_WP_TABS::include_plugin_file( 'fields/typography/google-fonts.php' );

				wp_enqueue_script( 'wptabspro-webfontloader', 'https://cdn.jsdelivr.net/npm/webfontloader@1.6.28/webfontloader.min.js', array( 'wptabspro' ), '1.6.28', true );

				$webfonts = array();

				$customwebfonts = apply_filters( 'wptabspro_field_typography_customwebfonts', array() );

				if ( ! empty( $customwebfonts ) ) {
					$webfonts['custom'] = array(
						'label' => esc_html__( 'Custom Web Fonts', 'wp-expand-tabs-free' ),
						'fonts' => $customwebfonts,
					);
				}

				$webfonts['safe'] = array(
					'label' => esc_html__( 'Safe Web Fonts', 'wp-expand-tabs-free' ),
					'fonts' => apply_filters(
						'wptabspro_field_typography_safewebfonts',
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
					'label' => esc_html__( 'Google Web Fonts', 'wp-expand-tabs-free' ),
					'fonts' => apply_filters(
						'wptabspro_field_typography_googlewebfonts',
						wptabspro_get_google_fonts()
					),
				);

				$defaultstyles = apply_filters( 'wptabspro_field_typography_defaultstyles', array( 'normal', 'italic', '700', '700italic' ) );

				$googlestyles = apply_filters(
					'wptabspro_field_typography_googlestyles',
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

				$webfonts = apply_filters( 'wptabspro_field_typography_webfonts', $webfonts );

				wp_localize_script(
					'wptabspro',
					'wptabspro_typography_json',
					array(
						'webfonts'      => $webfonts,
						'defaultstyles' => $defaultstyles,
						'googlestyles'  => $googlestyles,
					)
				);

			}

		}

		/**
		 * Enqueue google fonts
		 *
		 * @return mixed
		 */
		public function enqueue_google_fonts() {

			$value     = $this->value;
			$families  = array();
			$is_google = false;

			if ( ! empty( $this->value['type'] ) ) {
				$is_google = ( 'google' === $this->value['type'] ) ? true : false;
			} else {
				SP_WP_TABS::include_plugin_file( 'fields/typography/google-fonts.php' );
				$is_google = ( array_key_exists( $this->value['font-family'], wptabspro_get_google_fonts() ) ) ? true : false;
			}

			if ( $is_google ) {

				// set style.
				$font_weight = ( ! empty( $value['font-weight'] ) ) ? $value['font-weight'] : '';
				$font_style  = ( ! empty( $value['font-style'] ) ) ? $value['font-style'] : '';

				if ( $font_weight || $font_style ) {
					$style                       = $font_weight . $font_style;
					$families['style'][ $style ] = $style;
				}

				// set extra styles.
				if ( ! empty( $value['extra-styles'] ) ) {
					foreach ( $value['extra-styles'] as $extra_style ) {
						$families['style'][ $extra_style ] = $extra_style;
					}
				}

				// set subsets.
				if ( ! empty( $value['subset'] ) ) {
					$value['subset'] = ( is_array( $value['subset'] ) ) ? $value['subset'] : array_filter( (array) $value['subset'] );
					foreach ( $value['subset'] as $subset ) {
						$families['subset'][ $subset ] = $subset;
					}
				}

				$all_styles  = ( ! empty( $families['style'] ) ) ? ':' . implode( ',', $families['style'] ) : '';
				$all_subsets = ( ! empty( $families['subset'] ) ) ? ':' . implode( ',', $families['subset'] ) : '';

				$families = $this->value['font-family'] . str_replace( array( 'normal', 'italic' ), array( 'n', 'i' ), $all_styles ) . $all_subsets;

				$this->parent->typographies[] = $families;

				return $families;

			}

			return false;

		}

		/**
		 * Field output
		 *
		 * @return statement
		 */
		public function output() {

			$output    = '';
			$bg_image  = array();
			$important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
			$element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

			$font_family   = ( ! empty( $this->value['font-family'] ) ) ? $this->value['font-family'] : '';
			$backup_family = ( ! empty( $this->value['backup-font-family'] ) ) ? ', ' . $this->value['backup-font-family'] : '';

			if ( $font_family ) {
				$output .= 'font-family:"' . $font_family . '"' . $backup_family . $important . ';';
			}

			// Common font properties.
			$properties = array(
				'color',
				'hover_color',
				'active_color',
				'font-weight',
				'font-style',
				'font-variant',
				'text-align',
				'text-transform',
				'text-decoration',
			);

			foreach ( $properties as $property ) {
				if ( isset( $this->value[ $property ] ) && '' !== $this->value[ $property ] ) {
					$output .= $property . ':' . $this->value[ $property ] . $important . ';';
				}
			}

			$properties = array(
				'font-size',
				'line-height',
				'letter-spacing',
				'word-spacing',
			);

			$unit             = ( ! empty( $this->value['unit'] ) ) ? $this->value['unit'] : '';
			$line_height_unit = ( ! empty( $this->value['line_height_unit'] ) ) ? $this->value['line_height_unit'] : $unit;

			foreach ( $properties as $property ) {
				if ( isset( $this->value[ $property ] ) && '' !== $this->value[ $property ] ) {
					$unit    = ( 'line-height' === $property ) ? $line_height_unit : $unit;
					$output .= $property . ':' . $this->value[ $property ] . $unit . $important . ';';
				}
			}

			$custom_style = ( ! empty( $this->value['custom-style'] ) ) ? $this->value['custom-style'] : '';

			if ( $output ) {
				$output = $element . '{' . $output . $custom_style . '}';
			}

			$this->parent->output_css .= $output;

			return $output;

		}

	}
}
