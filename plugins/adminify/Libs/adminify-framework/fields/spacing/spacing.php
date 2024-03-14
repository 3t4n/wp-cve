<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: spacing
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'ADMINIFY_Field_spacing' ) ) {
	class ADMINIFY_Field_spacing extends ADMINIFY_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {
			$args = wp_parse_args(
				$this->field,
				[
					'top_icon'           => '<i class="fas fa-long-arrow-alt-up"></i>',
					'right_icon'         => '<i class="fas fa-long-arrow-alt-right"></i>',
					'bottom_icon'        => '<i class="fas fa-long-arrow-alt-down"></i>',
					'left_icon'          => '<i class="fas fa-long-arrow-alt-left"></i>',
					'all_icon'           => '<i class="fas fa-arrows-alt"></i>',
					'top_placeholder'    => esc_html__( 'top', 'adminify' ),
					'right_placeholder'  => esc_html__( 'right', 'adminify' ),
					'bottom_placeholder' => esc_html__( 'bottom', 'adminify' ),
					'left_placeholder'   => esc_html__( 'left', 'adminify' ),
					'all_placeholder'    => esc_html__( 'all', 'adminify' ),
					'top'                => true,
					'left'               => true,
					'bottom'             => true,
					'right'              => true,
					'unit'               => true,
					'show_units'         => true,
					'all'                => false,
					'units'              => [ 'px', '%', 'em' ],
				]
			);

			$default_values = [
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
				'all'    => '',
				'unit'   => 'px',
			];

			$value   = wp_parse_args( $this->value, $default_values );
			$unit    = ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? $args['units'][0] : '';
			$is_unit = ( ! empty( $unit ) ) ? ' adminify--is-unit' : '';

			echo wp_kses_post( $this->field_before() );

			echo '<div class="adminify--inputs" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';

			if ( ! empty( $args['all'] ) ) {
				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . esc_attr( $args['all_placeholder'] ) . '"' : '';

				echo '<div class="adminify--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="adminify--label adminify--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '"' . esc_attr( $placeholder ) . ' class="adminify-input-number' . esc_attr( $is_unit ) . '" step="any" />';
				echo ( $unit ) ? '<span class="adminify--label adminify--unit">' . esc_attr( $args['units'][0] ) . '</span>' : '';
				echo '</div>';
			} else {
				$properties = [];

				foreach ( [ 'top', 'right', 'bottom', 'left' ] as $prop ) {
					if ( ! empty( $args[ $prop ] ) ) {
						$properties[] = $prop;
					}
				}

				$properties = ( $properties === [ 'right', 'left' ] ) ? array_reverse( $properties ) : $properties;

				foreach ( $properties as $property ) {
					$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? ' placeholder="' . esc_attr( $args[ $property . '_placeholder' ] ) . '"' : '';

					echo '<div class="adminify--input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="adminify--label adminify--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '"' . esc_attr( $placeholder ) . ' class="adminify-input-number' . esc_attr( $is_unit ) . '" step="any" />';
					echo ( $unit ) ? '<span class="adminify--label adminify--unit">' . esc_attr( $args['units'][0] ) . '</span>' : '';
					echo '</div>';
				}
			}

			if ( ! empty( $args['unit'] ) && ! empty( $args['show_units'] ) && count( $args['units'] ) > 1 ) {
				echo '<div class="adminify--input">';
				echo '<select name="' . esc_attr( $this->field_name( '[unit]' ) ) . '">';
				foreach ( $args['units'] as $unit ) {
					$selected = ( $value['unit'] === $unit ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $unit ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $unit ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
			}

			echo '</div>';

			echo wp_kses_post( $this->field_after() );
		}

		public function output() {
			$output    = '';
			$element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];
			$important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
			$unit      = ( ! empty( $this->value['unit'] ) ) ? $this->value['unit'] : 'px';

			$mode = ( ! empty( $this->field['output_mode'] ) ) ? $this->field['output_mode'] : 'padding';

			if ( $mode === 'border-radius' || $mode === 'radius' ) {
				$top    = 'border-top-left-radius';
				$right  = 'border-top-right-radius';
				$bottom = 'border-bottom-right-radius';
				$left   = 'border-bottom-left-radius';
			} elseif ( $mode === 'relative' || $mode === 'absolute' || $mode === 'none' ) {
				$top    = 'top';
				$right  = 'right';
				$bottom = 'bottom';
				$left   = 'left';
			} else {
				$top    = $mode . '-top';
				$right  = $mode . '-right';
				$bottom = $mode . '-bottom';
				$left   = $mode . '-left';
			}

			if ( ! empty( $this->field['all'] ) && isset( $this->value['all'] ) && $this->value['all'] !== '' ) {
				$output  = $element . '{';
				$output .= $top . ':' . $this->value['all'] . $unit . $important . ';';
				$output .= $right . ':' . $this->value['all'] . $unit . $important . ';';
				$output .= $bottom . ':' . $this->value['all'] . $unit . $important . ';';
				$output .= $left . ':' . $this->value['all'] . $unit . $important . ';';
				$output .= '}';
			} else {
				$top    = ( isset( $this->value['top'] ) && $this->value['top'] !== '' ) ? $top . ':' . $this->value['top'] . $unit . $important . ';' : '';
				$right  = ( isset( $this->value['right'] ) && $this->value['right'] !== '' ) ? $right . ':' . $this->value['right'] . $unit . $important . ';' : '';
				$bottom = ( isset( $this->value['bottom'] ) && $this->value['bottom'] !== '' ) ? $bottom . ':' . $this->value['bottom'] . $unit . $important . ';' : '';
				$left   = ( isset( $this->value['left'] ) && $this->value['left'] !== '' ) ? $left . ':' . $this->value['left'] . $unit . $important . ';' : '';

				if ( $top !== '' || $right !== '' || $bottom !== '' || $left !== '' ) {
					$output = $element . '{' . $top . $right . $bottom . $left . '}';
				}
			}

			$this->parent->output_css .= $output;

			return $output;
		}

	}
}
