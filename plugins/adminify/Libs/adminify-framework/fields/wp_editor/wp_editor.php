<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: wp_editor
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'ADMINIFY_Field_wp_editor' ) ) {
	class ADMINIFY_Field_wp_editor extends ADMINIFY_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {
			$args = wp_parse_args(
				$this->field,
				[
					'tinymce'       => true,
					'quicktags'     => true,
					'media_buttons' => true,
					'wpautop'       => false,
					'height'        => '',
				]
			);

			$attributes = [
				'rows'         => 10,
				'class'        => 'wp-editor-area',
				'autocomplete' => 'off',
			];

			$editor_height = ( ! empty( $args['height'] ) ) ? ' style="height:' . esc_attr( $args['height'] ) . ';"' : '';

			$editor_settings = [
				'tinymce'       => $args['tinymce'],
				'quicktags'     => $args['quicktags'],
				'media_buttons' => $args['media_buttons'],
				'wpautop'       => $args['wpautop'],
			];

			echo wp_kses_post( $this->field_before() );

			echo ( adminify_wp_editor_api() ) ? '<div class="adminify-wp-editor" data-editor-settings="' . esc_attr( json_encode( $editor_settings ) ) . '">' : '';

			echo '<textarea name="' . esc_attr( $this->field_name() ) . '"' . wp_kses_post( $this->field_attributes( $attributes ))  . esc_attr( $editor_height ) . '>' . wp_kses_post( $this->value ) . '</textarea>';

			echo ( adminify_wp_editor_api() ) ? '</div>' : '';

			echo wp_kses_post( $this->field_after() );
		}

		public function enqueue() {
			if ( adminify_wp_editor_api() && function_exists( 'wp_enqueue_editor' ) ) {
				wp_enqueue_editor();

				$this->setup_wp_editor_settings();

				add_action( 'print_default_editor_scripts', [ $this, 'setup_wp_editor_media_buttons' ] );
			}
		}

		// Setup wp editor media buttons
		public function setup_wp_editor_media_buttons() {
			if ( ! function_exists( 'media_buttons' ) ) {
				return;
			}

			ob_start();
			echo '<div class="wp-media-buttons">';
			do_action( 'media_buttons' );
			echo '</div>';
			$media_buttons = ob_get_clean();

			echo '<script type="text/javascript">';
			echo 'var adminify_media_buttons = ' . json_encode( $media_buttons ) . ';';
			echo '</script>';
		}

		// Setup wp editor settings
		public function setup_wp_editor_settings() {
			if ( adminify_wp_editor_api() && class_exists( '_WP_Editors' ) ) {
				$defaults = apply_filters(
					'adminify_wp_editor',
					[
						'tinymce' => [
							'wp_skip_init' => true,
						],
					]
				);

				$setup = _WP_Editors::parse_settings( 'adminify_wp_editor', $defaults );

				_WP_Editors::editor_settings( 'adminify_wp_editor', $setup );
			}
		}

	}
}
