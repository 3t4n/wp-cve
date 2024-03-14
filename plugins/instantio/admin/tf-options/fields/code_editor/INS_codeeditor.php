<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'INS_codeeditor' ) ) {
	class INS_codeeditor extends INS_Fields {

		public $version = '5.65.15';
		public $cdn_url = 'https://cdn.jsdelivr.net/npm/codemirror@';

		public function __construct( $field, $value = '', $settings_id = '', $parent_field = '' ) {
			parent::__construct( $field, $value, $settings_id, $parent_field );
			$this->enqueue();

		}

		public function render() {
			$default_settings = array(
				'tabSize' => 4,
				'lineNumbers' => true,
				'theme' => 'monokai',
				'mode' => 'htmlmixed',
				'cdnURL' => $this->cdn_url . $this->version,
			);

			$settings = ( ! empty( $this->field['settings'] ) ) ? $this->field['settings'] : array();
			$settings = wp_parse_args( $settings, $default_settings );

			?>
			<div class="tf-field-textarea tf-field-codearea">
				<?php
				echo '<textarea name="' . esc_attr( $this->field_name() ) . '"' . $this->field_attributes() . ' data-editor="' . esc_attr( json_encode( $settings ) ) . '">' . $this->value . '</textarea>';
				?>

			</div>
			<?php
		}

		public function enqueue() {

			$page = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';

			// Do not loads CodeMirror in revslider page.
			if ( in_array( $page, array( 'revslider' ) ) ) {
				return;
			}

			if ( ! wp_script_is( 'ins-codemirror' ) ) {
				wp_enqueue_script( 'ins-codemirror', esc_url( $this->cdn_url . $this->version . '/lib/codemirror.min.js' ), array(), $this->version, true );
				wp_enqueue_script( 'ins-codemirror-loadmode', esc_url( $this->cdn_url . $this->version . '/addon/mode/loadmode.min.js' ), array( 'ins-codemirror' ), $this->version, true );
			}

			if ( ! wp_style_is( 'ins-codemirror' ) ) {
				wp_enqueue_style( 'ins-codemirror', esc_url( $this->cdn_url . $this->version . '/lib/codemirror.min.css' ), array(), $this->version );
			}

		}

		public function sanitize() {
			return wp_kses_post( $this->value );
		}

	}
}