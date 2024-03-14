<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 * Field: background
 *
 * @since   1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'KIANFR_Field_background' ) ) {
	class KIANFR_Field_background extends KIANFR_Fields
	{

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' )
		{
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render()
		{
			$args = wp_parse_args( $this->field, [
				'background_color'              => true,
				'background_image'              => true,
				'background_position'           => true,
				'background_repeat'             => true,
				'background_attachment'         => true,
				'background_size'               => true,
				'background_origin'             => false,
				'background_clip'               => false,
				'background_blend_mode'         => false,
				'background_gradient'           => false,
				'background_gradient_color'     => true,
				'background_gradient_direction' => true,
				'background_image_preview'      => true,
				'background_auto_attributes'    => false,
				'compact'                       => false,
				'background_image_library'      => 'image',
				'background_image_placeholder'  => esc_html__( 'Not selected', 'kianfr' ),
			] );

			if ( $args['compact'] ) {
				$args['background_color']           = false;
				$args['background_auto_attributes'] = true;
			}

			$default_value = [
				'background-color'              => '',
				'background-image'              => '',
				'background-position'           => '',
				'background-repeat'             => '',
				'background-attachment'         => '',
				'background-size'               => '',
				'background-origin'             => '',
				'background-clip'               => '',
				'background-blend-mode'         => '',
				'background-gradient-color'     => '',
				'background-gradient-direction' => '',
			];

			$default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

			$this->value = wp_parse_args( $this->value, $default_value );

			echo $this->field_before();

			echo '<div class="kianfr--background-colors">';

			//
			// Background Color
			if ( ! empty( $args['background_color'] ) ) {
				echo '<div class="kianfr--color">';

				echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="kianfr--title">' . esc_html__( 'From', 'kianfr' ) . '</div>' : '';

				KIANFR::field( [
					'id'      => 'background-color',
					'type'    => 'color',
					'default' => $default_value['background-color'],
				], $this->value['background-color'], $this->field_name(), 'field/background' );

				echo '</div>';
			}

			//
			// Background Gradient Color
			if ( ! empty( $args['background_gradient_color'] ) && ! empty( $args['background_gradient'] ) ) {
				echo '<div class="kianfr--color">';

				echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="kianfr--title">' . esc_html__( 'To', 'kianfr' ) . '</div>' : '';

				KIANFR::field( [
					'id'      => 'background-gradient-color',
					'type'    => 'color',
					'default' => $default_value['background-gradient-color'],
				], $this->value['background-gradient-color'], $this->field_name(), 'field/background' );

				echo '</div>';
			}

			//
			// Background Gradient Direction
			if ( ! empty( $args['background_gradient_direction'] ) && ! empty( $args['background_gradient'] ) ) {
				echo '<div class="kianfr--color">';

				echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="kianfr---title">' . esc_html__( 'Direction', 'kianfr' ) . '</div>' : '';

				KIANFR::field( [
					'id'      => 'background-gradient-direction',
					'type'    => 'select',
					'options' => [
						''          => esc_html__( 'Gradient Direction', 'kianfr' ),
						'to bottom' => esc_html__( '&#8659; top to bottom', 'kianfr' ),
						'to right'  => esc_html__( '&#8658; left to right', 'kianfr' ),
						'135deg'    => esc_html__( '&#8664; corner top to right', 'kianfr' ),
						'-135deg'   => esc_html__( '&#8665; corner top to left', 'kianfr' ),
					],
				], $this->value['background-gradient-direction'], $this->field_name(), 'field/background' );

				echo '</div>';
			}

			echo '</div>';

			//
			// Background Image
			if ( ! empty( $args['background_image'] ) ) {
				echo '<div class="kianfr--background-image">';

				KIANFR::field( [
					'id'          => 'background-image',
					'type'        => 'media',
					'class'       => 'kianfr-assign-field-background',
					'library'     => $args['background_image_library'],
					'preview'     => $args['background_image_preview'],
					'placeholder' => $args['background_image_placeholder'],
					'attributes'  => [ 'data-depend-id' => $this->field['id'] ],
				], $this->value['background-image'], $this->field_name(), 'field/background' );

				echo '</div>';
			}

			$auto_class   = ( ! empty( $args['background_auto_attributes'] ) ) ? ' kianfr--auto-attributes' : '';
			$hidden_class = ( ! empty( $args['background_auto_attributes'] ) && empty( $this->value['background-image']['url'] ) ) ? ' kianfr--attributes-hidden' : '';

			echo '<div class="kianfr--background-attributes' . esc_attr( $auto_class . $hidden_class ) . '">';

			//
			// Background Position
			if ( ! empty( $args['background_position'] ) ) {
				KIANFR::field( [
					'id'      => 'background-position',
					'type'    => 'select',
					'options' => [
						''              => esc_html__( 'Background Position', 'kianfr' ),
						'left top'      => esc_html__( 'Left Top', 'kianfr' ),
						'left center'   => esc_html__( 'Left Center', 'kianfr' ),
						'left bottom'   => esc_html__( 'Left Bottom', 'kianfr' ),
						'center top'    => esc_html__( 'Center Top', 'kianfr' ),
						'center center' => esc_html__( 'Center Center', 'kianfr' ),
						'center bottom' => esc_html__( 'Center Bottom', 'kianfr' ),
						'right top'     => esc_html__( 'Right Top', 'kianfr' ),
						'right center'  => esc_html__( 'Right Center', 'kianfr' ),
						'right bottom'  => esc_html__( 'Right Bottom', 'kianfr' ),
					],
				], $this->value['background-position'], $this->field_name(), 'field/background' );
			}

			//
			// Background Repeat
			if ( ! empty( $args['background_repeat'] ) ) {
				KIANFR::field( [
					'id'      => 'background-repeat',
					'type'    => 'select',
					'options' => [
						''          => esc_html__( 'Background Repeat', 'kianfr' ),
						'repeat'    => esc_html__( 'Repeat', 'kianfr' ),
						'no-repeat' => esc_html__( 'No Repeat', 'kianfr' ),
						'repeat-x'  => esc_html__( 'Repeat Horizontally', 'kianfr' ),
						'repeat-y'  => esc_html__( 'Repeat Vertically', 'kianfr' ),
					],
				], $this->value['background-repeat'], $this->field_name(), 'field/background' );
			}

			//
			// Background Attachment
			if ( ! empty( $args['background_attachment'] ) ) {
				KIANFR::field( [
					'id'      => 'background-attachment',
					'type'    => 'select',
					'options' => [
						''       => esc_html__( 'Background Attachment', 'kianfr' ),
						'scroll' => esc_html__( 'Scroll', 'kianfr' ),
						'fixed'  => esc_html__( 'Fixed', 'kianfr' ),
					],
				], $this->value['background-attachment'], $this->field_name(), 'field/background' );
			}

			//
			// Background Size
			if ( ! empty( $args['background_size'] ) ) {
				KIANFR::field( [
					'id'      => 'background-size',
					'type'    => 'select',
					'options' => [
						''        => esc_html__( 'Background Size', 'kianfr' ),
						'cover'   => esc_html__( 'Cover', 'kianfr' ),
						'contain' => esc_html__( 'Contain', 'kianfr' ),
						'auto'    => esc_html__( 'Auto', 'kianfr' ),
					],
				], $this->value['background-size'], $this->field_name(), 'field/background' );
			}

			//
			// Background Origin
			if ( ! empty( $args['background_origin'] ) ) {
				KIANFR::field( [
					'id'      => 'background-origin',
					'type'    => 'select',
					'options' => [
						''            => esc_html__( 'Background Origin', 'kianfr' ),
						'padding-box' => esc_html__( 'Padding Box', 'kianfr' ),
						'border-box'  => esc_html__( 'Border Box', 'kianfr' ),
						'content-box' => esc_html__( 'Content Box', 'kianfr' ),
					],
				], $this->value['background-origin'], $this->field_name(), 'field/background' );
			}

			//
			// Background Clip
			if ( ! empty( $args['background_clip'] ) ) {
				KIANFR::field( [
					'id'      => 'background-clip',
					'type'    => 'select',
					'options' => [
						''            => esc_html__( 'Background Clip', 'kianfr' ),
						'border-box'  => esc_html__( 'Border Box', 'kianfr' ),
						'padding-box' => esc_html__( 'Padding Box', 'kianfr' ),
						'content-box' => esc_html__( 'Content Box', 'kianfr' ),
					],
				], $this->value['background-clip'], $this->field_name(), 'field/background' );
			}

			//
			// Background Blend Mode
			if ( ! empty( $args['background_blend_mode'] ) ) {
				KIANFR::field( [
					'id'      => 'background-blend-mode',
					'type'    => 'select',
					'options' => [
						''            => esc_html__( 'Background Blend Mode', 'kianfr' ),
						'normal'      => esc_html__( 'Normal', 'kianfr' ),
						'multiply'    => esc_html__( 'Multiply', 'kianfr' ),
						'screen'      => esc_html__( 'Screen', 'kianfr' ),
						'overlay'     => esc_html__( 'Overlay', 'kianfr' ),
						'darken'      => esc_html__( 'Darken', 'kianfr' ),
						'lighten'     => esc_html__( 'Lighten', 'kianfr' ),
						'color-dodge' => esc_html__( 'Color Dodge', 'kianfr' ),
						'saturation'  => esc_html__( 'Saturation', 'kianfr' ),
						'color'       => esc_html__( 'Color', 'kianfr' ),
						'luminosity'  => esc_html__( 'Luminosity', 'kianfr' ),
					],
				], $this->value['background-blend-mode'], $this->field_name(), 'field/background' );
			}

			echo '</div>';

			echo $this->field_after();
		}

		public function output()
		{
			$output    = '';
			$bg_image  = [];
			$important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
			$element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

			// Background image and gradient
			$background_color        = ( ! empty( $this->value['background-color'] ) ) ? $this->value['background-color'] : '';
			$background_gd_color     = ( ! empty( $this->value['background-gradient-color'] ) ) ? $this->value['background-gradient-color'] : '';
			$background_gd_direction = ( ! empty( $this->value['background-gradient-direction'] ) ) ? $this->value['background-gradient-direction'] : '';
			$background_image        = ( ! empty( $this->value['background-image']['url'] ) ) ? $this->value['background-image']['url'] : '';

			if ( $background_color && $background_gd_color ) {
				$gd_direction = ( $background_gd_direction ) ? $background_gd_direction . ',' : '';
				$bg_image[]   = 'linear-gradient(' . $gd_direction . $background_color . ',' . $background_gd_color . ')';
				unset( $this->value['background-color'] );
			}

			if ( $background_image ) {
				$bg_image[] = 'url(' . $background_image . ')';
			}

			if ( ! empty( $bg_image ) ) {
				$output .= 'background-image:' . implode( ',', $bg_image ) . $important . ';';
			}

			// Common background properties
			$properties = [ 'color', 'position', 'repeat', 'attachment', 'size', 'origin', 'clip', 'blend-mode' ];

			foreach ( $properties as $property ) {
				$property = 'background-' . $property;
				if ( ! empty( $this->value[ $property ] ) ) {
					$output .= $property . ':' . $this->value[ $property ] . $important . ';';
				}
			}

			if ( $output ) {
				$output = $element . '{' . $output . '}';
			}

			$this->parent->output_css .= $output;

			return $output;
		}

	}
}
