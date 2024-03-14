<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: code_editor
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'ADMINIFY_Field_code_editor' ) ) {
	class ADMINIFY_Field_code_editor extends ADMINIFY_Fields {

		public $version = '5.65.2';
		public $cdn_url = 'https://cdn.jsdelivr.net/npm/codemirror@';

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {
			$default_settings = [
				'tabSize'     => 2,
				'lineNumbers' => true,
				'theme'       => 'default',
				'mode'        => 'htmlmixed',
				'cdnURL'      => $this->cdn_url . $this->version,
			];

			$settings = ( ! empty( $this->field['settings'] ) ) ? $this->field['settings'] : [];
			$settings = wp_parse_args( $settings, $default_settings );

			echo wp_kses_post( $this->field_before() );
			echo '<textarea name="' . esc_attr( $this->field_name() ) . '"' . wp_kses_post( $this->field_attributes() ) . ' data-editor="' . esc_attr( json_encode( $settings ) ) . '">' . wp_kses_post( $this->value ) . '</textarea>';
			echo wp_kses_post( $this->field_after() );
		}

		public function enqueue() {
			$page = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';

			// Do not loads CodeMirror in revslider page.
			if ( in_array( $page, [ 'revslider' ] ) ) {
				return; }

			if ( ! wp_script_is( 'adminify-codemirror' ) ) {
				wp_enqueue_script( 'adminify-codemirror', esc_url( $this->cdn_url . $this->version . '/lib/codemirror.min.js' ), [ 'adminify' ], $this->version, true );
				wp_enqueue_script( 'adminify-codemirror-loadmode', esc_url( $this->cdn_url . $this->version . '/addon/mode/loadmode.min.js' ), [ 'adminify-codemirror' ], $this->version, true );
			}

			if ( ! wp_style_is( 'adminify-codemirror' ) ) {
				wp_enqueue_style( 'adminify-codemirror', esc_url( $this->cdn_url . $this->version . '/lib/codemirror.min.css' ), [], $this->version );
			}
		}

	}
}
