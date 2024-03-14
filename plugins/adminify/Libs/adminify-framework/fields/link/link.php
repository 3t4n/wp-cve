<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: link
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'ADMINIFY_Field_link' ) ) {
	class ADMINIFY_Field_link extends ADMINIFY_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {
			$args = wp_parse_args(
				$this->field,
				[
					'add_title'    => esc_html__( 'Add Link', 'adminify' ),
					'edit_title'   => esc_html__( 'Edit Link', 'adminify' ),
					'remove_title' => esc_html__( 'Remove Link', 'adminify' ),
				]
			);

			$default_values = [
				'url'    => '',
				'text'   => '',
				'target' => '',
			];

			$value = wp_parse_args( $this->value, $default_values );

			$hidden = ( ! empty( $value['url'] ) || ! empty( $value['url'] ) || ! empty( $value['url'] ) ) ? ' hidden' : '';

			$maybe_hidden = ( empty( $hidden ) ) ? ' hidden' : '';

			echo wp_kses_post( $this->field_before() );

			echo '<textarea readonly="readonly" class="adminify--link hidden"></textarea>';

			echo '<div class="' . esc_attr( $maybe_hidden ) . '"><div class="adminify--result">' . sprintf( '{url:"%s", text:"%s", target:"%s"}', esc_url( $value['url'] ), wp_kses_post( $value['text'] ), esc_attr( $value['target'] ) ) . '</div></div>';

			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[url]' ) ) . '" value="' . esc_attr( $value['url'] ) . '"' . wp_kses_post( $this->field_attributes( [ 'class' => 'adminify--url' ] ) ) . ' />';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[text]' ) ) . '" value="' . esc_attr( $value['text'] ) . '" class="adminify--text" />';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[target]' ) ) . '" value="' . esc_attr( $value['target'] ) . '" class="adminify--target" />';

			echo '<a href="#" class="button button-primary adminify--add' . esc_attr( $hidden ) . '">' . wp_kses_post( $args['add_title'] ) . '</a> ';
			echo '<a href="#" class="button adminify--edit' . esc_attr( $maybe_hidden ) . '">' . wp_kses_post( $args['edit_title'] ) . '</a> ';
			echo '<a href="#" class="button adminify-warning-primary adminify--remove' . esc_attr( $maybe_hidden ) . '">' . wp_kses_post( $args['remove_title'] ) . '</a>';

			echo wp_kses_post( $this->field_after() );
		}

		public function enqueue() {
			if ( ! wp_script_is( 'wplink' ) ) {
				wp_enqueue_script( 'wplink' );
			}

			if ( ! wp_script_is( 'jquery-ui-autocomplete' ) ) {
				wp_enqueue_script( 'jquery-ui-autocomplete' );
			}

			add_action( 'admin_print_footer_scripts', [ $this, 'add_wp_link_dialog' ] );
		}

		public function add_wp_link_dialog() {
			if ( ! class_exists( '_WP_Editors' ) ) {
				require_once ABSPATH . WPINC . '/class-wp-editor.php';
			}

			wp_print_styles( 'editor-buttons' );

			_WP_Editors::wp_link_dialog();
		}

	}
}
