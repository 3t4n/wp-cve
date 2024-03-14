<?php
/**
 * Framework code editor fields.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Woo_Product_Slider.
 * @subpackage Woo_Product_Slider/Admin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPF_WPSP_Field_code_editor' ) ) {
	/**
	 *
	 * Field: code_editor
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_WPSP_Field_code_editor extends SPF_WPSP_Fields {

		/**
		 * Version
		 *
		 * @var string
		 */
		public $version = '5.62.2';
		/**
		 * Cdn_url
		 *
		 * @var string
		 */
		public $cdn_url = 'https://cdn.jsdelivr.net/npm/codemirror@';
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

			$default_settings = array(
				'tabSize'     => 2,
				'lineNumbers' => true,
				'theme'       => 'default',
				'mode'        => 'htmlmixed',
				'cdnURL'      => $this->cdn_url . $this->version,
			);

			$settings = ( ! empty( $this->field['settings'] ) ) ? $this->field['settings'] : array();
			$settings = wp_parse_args( $settings, $default_settings );
			echo wp_kses_post( $this->field_before() );
			// Ignore phpcs errors because already $this->field_attributes escaped.
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<textarea name="' . esc_attr( $this->field_name() ) . '"' . $this->field_attributes() . ' data-editor="' . esc_attr( wp_json_encode( $settings ) ) . '">' . $this->value . '</textarea>';
			echo wp_kses_post( $this->field_after() );

		}
		/**
		 * Enqueue
		 *
		 * @return void
		 */
		public function enqueue() {
			/* Ignore phpcs errors because there is no input from user, just a check function */
			$page = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore

			// Do not loads CodeMirror in revslider page.
			if ( in_array( $page, array( 'revslider' ) ) ) {
				return; }

			if ( ! wp_script_is( 'spwps-codemirror' ) ) {
				wp_enqueue_script( 'spwps-codemirror', esc_url( $this->cdn_url . $this->version . '/lib/codemirror.min.js' ), array( 'spwps' ), $this->version, true );
				wp_enqueue_script( 'spwps-codemirror-loadmode', esc_url( $this->cdn_url . $this->version . '/addon/mode/loadmode.min.js' ), array( 'spwps-codemirror' ), $this->version, true );
			}

			if ( ! wp_style_is( 'spwps-codemirror' ) ) {
				wp_enqueue_style( 'spwps-codemirror', esc_url( $this->cdn_url . $this->version . '/lib/codemirror.min.css' ), array(), $this->version );
			}

		}

	}
}
