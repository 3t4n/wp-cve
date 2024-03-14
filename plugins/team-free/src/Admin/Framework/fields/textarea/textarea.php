<?php
/**
 * Framework textarea field file.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package team-free
 * @subpackage team-free/framework
 */

use ShapedPlugin\WPTeam\Admin\Framework\Classes\SPF_TEAM;
if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'TEAMFW_Field_textarea' ) ) {
	/**
	 *
	 * Field: textarea
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class TEAMFW_Field_textarea extends TEAMFW_Fields {

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

			echo wp_kses_post( $this->field_before() );
			echo $this->shortcoder() ? wp_kses_post( $this->shortcoder() ) : '';
			echo '<textarea name="' . esc_attr( $this->field_name() ) . '"' . $this->field_attributes() . '>' . $this->value . '</textarea>'; // phpcs:ignore
			echo wp_kses_post( $this->field_after() );

		}

		/**
		 * Shortcoder
		 *
		 * @return void
		 */
		public function shortcoder() {

			if ( ! empty( $this->field['shortcoder'] ) ) {

				$instances = ( is_array( $this->field['shortcoder'] ) ) ? $this->field['shortcoder'] : array_filter( (array) $this->field['shortcoder'] );

				foreach ( $instances as $instance_key ) {

					if ( isset( SPF_TEAM::$shortcode_instances[ $instance_key ] ) ) {

						$button_title = SPF_TEAM::$shortcode_instances[ $instance_key ]['button_title'];

						echo '<a href="#" class="button button-primary spf-shortcode-button" data-modal-id="' . esc_attr( $instance_key ) . '">' . wp_kses_post( $button_title ) . '</a>';

					}
				}
			}

		}
	}
}
