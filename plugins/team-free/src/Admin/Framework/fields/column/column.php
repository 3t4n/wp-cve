<?php
/**
 * Framework column field file.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package team-free
 * @subpackage team-free/framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'TEAMFW_Field_column' ) ) {
	/**
	 *
	 * Field: column
	 *
	 * @since 2.0
	 * @version 2.0
	 */
	class TEAMFW_Field_column extends TEAMFW_Fields {

		/**
		 * Field constructor.
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

			$args = wp_parse_args(
				$this->field,
				array(
					'desktop_icon'        => '<i class="fa fa-desktop"></i>',
					'laptop_icon'         => '<i class="fa fa-laptop"></i>',
					'tablet_icon'         => '<i class="fa fa-tablet"></i>',
					'mobile_icon'         => '<i class="fa fa-mobile"></i>',
					'all_text'            => '<i class="fa fa-arrows"></i>',
					'desktop_placeholder' => esc_html__( 'Desktop', 'team-free' ),
					'laptop_placeholder'  => esc_html__( 'Small Desktop', 'team-free' ),
					'tablet_placeholder'  => esc_html__( 'Tablet', 'team-free' ),
					'mobile_placeholder'  => esc_html__( 'Mobile', 'team-free' ),
					'all_placeholder'     => esc_html__( 'all', 'team-free' ),
					'desktop'             => true,
					'laptop'              => true,
					'tablet'              => true,
					'mobile'              => true,
					'unit'                => false,
					'all'                 => false,
					'units'               => array( 'px', '%', 'em' ),
				)
			);

			$default_values = array(
				'desktop' => '4',
				'laptop'  => '3',
				'tablet'  => '2',
				'mobile'  => '1',
				'all'     => '',
				'unit'    => 'px',
			);

			$value = wp_parse_args( $this->value, $default_values );

			echo wp_kses_post( $this->field_before() );
			echo '<div class="spf--inputs">';

			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . $args['all_placeholder'] . '"' : '';

				echo '<div class="spf--input">';
				echo ( ! empty( $args['all_text'] ) ) ? '<span class="spf--label spf--icon">' . esc_html( $args['all_text'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '" class="spf-number"  min="1"/>';
				echo ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? '<span class="spf--label spf--label-unit">' . esc_html( $args['units'][0] ) . '</span>' : '';
				echo '</div>';

			} else {

				$properties = array();

				foreach ( array( 'desktop', 'laptop', 'tablet', 'mobile' ) as $prop ) {
					if ( ! empty( $args[ $prop ] ) ) {
						$properties[] = $prop;
					}
				}

				$properties = ( array( 'laptop', 'mobile' ) === $properties ) ? array_reverse( $properties ) : $properties;

				foreach ( $properties as $property ) {

					$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? ' placeholder="' . $args[ $property . '_placeholder' ] . '"' : '';

					echo '<div class="spf--border">';
					echo '<div class="spf--title">' . esc_html( $property ) . '</div>';
					echo '<div class="spf--input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="spf--label spf--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '" class="spf-input-number spf--is-unit" step="any" min="1" />';
					echo ( ! empty( $args['unit'] ) ) ? '<span class="spf--label spf--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
					echo '</div>';
					echo '</div>';
				}
			}

			echo '</div>';
			echo wp_kses_post( $this->field_after() );

		}

	}
}

