<?php
/**
 * Framework Tabbed fields.
 *
 * @link https://shapedplugin.com
 * @since 3.0.0
 *
 * @package team-free
 * @subpackage team-free/framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

use ShapedPlugin\WPTeam\Admin\Framework\Classes\SPF_TEAM;

if ( ! class_exists( 'TEAMFW_Field_tabbed' ) ) {
	/**
	 *
	 * Field: tabbed
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class TEAMFW_Field_tabbed extends TEAMFW_Fields {

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

			$unallows = array( 'tabbed' );

			echo wp_kses_post( $this->field_before() );

			echo '<div class="spf-tabbed-nav">';
			foreach ( $this->field['tabs'] as $key => $tab ) {

				$tabbed_icon   = ( ! empty( $tab['icon'] ) ) ? $tab['icon'] : '';
				$tabbed_active = ( empty( $key ) ) ? ' class="spf-tabbed-active"' : '';

				echo '<a href="#"' . wp_kses_post( $tabbed_active ) . '>' . $tabbed_icon . wp_kses_post( $tab['title'] ) . '</a>';

			}
			echo '</div>';

			echo '<div class="spf-tabbed-sections">';
			foreach ( $this->field['tabs'] as $key => $tab ) {

				$tabbed_hidden = ( ! empty( $key ) ) ? ' hidden' : '';
				$tabbed_class  = ( ! empty( $tab['class'] ) ) ? ' ' . $tab['class'] : '';
				echo '<div class="spf-tabbed-section' . esc_attr( $tabbed_hidden . $tabbed_class ) . '">';

				foreach ( $tab['fields'] as $field ) {
					if ( in_array( $field['type'], $unallows ) ) {
						$field['_notice'] = true; }
					$field_id      = ( isset( $field['id'] ) ) ? $field['id'] : '';
					$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
					$field_value   = ( isset( $this->value[ $field_id ] ) ) ? $this->value[ $field_id ] : $field_default;
					$unique_id     = ( ! empty( $this->unique ) ) ? $this->unique : '';

					SPF_TEAM::field( $field, $field_value, $unique_id, 'field/tabbed' );

				}

				echo '</div>';

			}
			echo '</div>';

			echo wp_kses_post( $this->field_after() );

		}

	}
}
