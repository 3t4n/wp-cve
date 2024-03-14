<?php
/**
 * Widget API: ExS_Widget_Theme_Spacer class
 *
 * @package ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'ExS_Widget_Theme_Spacer' ) ) :

	class ExS_Widget_Theme_Spacer extends WP_Widget {

		/**
		 * Sets up a new Recent Posts widget instance.
		 *
		 * @since 0.0.1
		 */
		public function __construct() {
			$exs_widget_ops = array(
				'classname'                   => 'widget_theme_spacer',
				'description'                 => esc_html__( 'Add spacer between your widgets.', 'exs' ),
				'customize_selective_refresh' => true,
			);
			parent::__construct( 'theme-spacer', esc_html__( 'ExS Spacer', 'exs' ), $exs_widget_ops );
			$this->alt_option_name = 'widget_theme_spacer';
		}

		/**
		 * Outputs the content for the current Custom Posts widget instance.
		 *
		 * @param array $exs_args Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $exs_instance Settings for the current Custom Posts widget instance.
		 */
		public function widget( $exs_args, $exs_instance ) {
			if ( ! isset( $exs_args['widget_id'] ) ) {
				$exs_args['widget_id'] = $this->id;
			}

			$exs_title = ( ! empty( $exs_instance['title'] ) ) ? $exs_instance['title'] : '';

			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$exs_title = apply_filters( 'widget_title', $exs_title, $exs_instance, $this->id_base );

			$exs_sub_title   = ( ! empty( $exs_instance['sub_title'] ) ) ? $exs_instance['sub_title'] : '';
			$exs_text_center = isset( $exs_instance['text_center'] ) ? $exs_instance['text_center'] : false;
			$exs_layout      = ( ! empty( $exs_instance['layout'] ) ) ? esc_attr( $exs_instance['layout'] ) : 'default';
			$exs_pt          = ( ! empty( $exs_instance['pt'] ) ) ? esc_attr( $exs_instance['pt'] ) : '';
			$exs_pb          = ( ! empty( $exs_instance['pb'] ) ) ? esc_attr( $exs_instance['pb'] ) : '';
			$exs_css_class   = ( ! empty( $exs_instance['css_class'] ) ) ? sanitize_text_field( $exs_instance['css_class'] ) : '';

			$exs_filepath = EXS_WIDGETS_PLUGIN_PATH . 'widgets/spacer/views/default.php';

			if ( file_exists( $exs_filepath ) ) {
				include $exs_filepath;
			} else {
				esc_html_e( 'View not found: ', 'exs' );
				echo '<strong>' . esc_html( $exs_filepath ) . '</strong>';
			}

		}

		/**
		 * Handles updating the settings for the current Recent Posts widget instance.
		 *
		 * @param array $exs_new_instance New settings for this instance as input by the user via
		 *                            WP_Widget::form().
		 * @param array $exs_old_instance Old settings for this instance.
		 *
		 * @return array Updated settings to save.
		 */
		public function update( $exs_new_instance, $exs_old_instance ) {
			$exs_instance                = $exs_old_instance;
			$exs_instance['title']       = sanitize_text_field( $exs_new_instance['title'] );
			$exs_instance['sub_title']   = sanitize_text_field( $exs_new_instance['sub_title'] );
			$exs_instance['layout']      = esc_attr( $exs_new_instance['layout'] );
			$exs_instance['pt']          = esc_attr( $exs_new_instance['pt'] );
			$exs_instance['pb']          = esc_attr( $exs_new_instance['pb'] );
			$exs_instance['text_center'] = isset( $exs_new_instance['text_center'] ) ? (bool) $exs_new_instance['text_center'] : false;
			$exs_instance['css_class']   = sanitize_text_field( $exs_new_instance['css_class'] );

			return $exs_instance;
		}

		/**
		 * Outputs the settings form for the Recent Posts widget.
		 *
		 * @param array $exs_instance Current settings.
		 */
		public function form( $exs_instance ) {
			$exs_title       = isset( $exs_instance['title'] ) ? esc_attr( $exs_instance['title'] ) : '';
			$exs_sub_title   = isset( $exs_instance['sub_title'] ) ? esc_attr( $exs_instance['sub_title'] ) : '';
			$exs_layout      = isset( $exs_instance['layout'] ) ? esc_attr( $exs_instance['layout'] ) : 'default';
			$exs_pt          = isset( $exs_instance['pt'] ) ? esc_attr( $exs_instance['pt'] ) : '';
			$exs_pb          = isset( $exs_instance['pb'] ) ? esc_attr( $exs_instance['pb'] ) : '';
			$exs_text_center = isset( $exs_instance['text_center'] ) ? (bool) $exs_instance['text_center'] : false;
			$exs_css_class   = isset( $exs_instance['css_class'] ) ? sanitize_text_field( $exs_instance['css_class'] ) : '';
			?>
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'exs' ); ?></label>
				<input
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
					type="text"
					value="<?php echo esc_attr( $exs_title ); ?>"/>
			</p>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'sub_title' ) ); ?>"><?php esc_html_e( 'Sub Title:', 'exs' ); ?></label>
				<textarea
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'sub_title' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'sub_title' ) ); ?>"
				><?php echo esc_html( $exs_sub_title ); ?></textarea>
			</p>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php esc_html_e( 'Widgets layout:', 'exs' ); ?></label>
				<select
					name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>"
					id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"
					class="widefat"
				>
					<option
						value="default"<?php selected( $exs_layout, 'default' ); ?>><?php esc_html_e( 'Spacer', 'exs' ); ?></option>
					<option
						value="hr"<?php selected( $exs_layout, 'hr' ); ?>><?php esc_html_e( 'Horizontal Rule (hr)', 'exs' ); ?></option>
				</select>
			</p>


			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'pt' ) ); ?>"><?php esc_html_e( 'Top space:', 'exs' ); ?></label>
				<select
					name="<?php echo esc_attr( $this->get_field_name( 'pt' ) ); ?>"
					id="<?php echo esc_attr( $this->get_field_id( 'pt' ) ); ?>"
					class="widefat"
				>
					<option value=""<?php selected( $exs_pt, '' ); ?>><?php esc_html_e( 'None', 'exs' ); ?></option>
					<option
						value="05"<?php selected( $exs_pt, '05' ); ?>><?php esc_html_e( '0.5em', 'exs' ); ?></option>
					<option
						value="1"<?php selected( $exs_pt, '1' ); ?>><?php esc_html_e( '1em', 'exs' ); ?></option>
					<option
						value="2"<?php selected( $exs_pt, '2' ); ?>><?php esc_html_e( '2em', 'exs' ); ?></option>
					<option
						value="3"<?php selected( $exs_pt, '3' ); ?>><?php esc_html_e( '3em', 'exs' ); ?></option>
					<option
						value="4"<?php selected( $exs_pt, '4' ); ?>><?php esc_html_e( '4em', 'exs' ); ?></option>
					<option
						value="5"<?php selected( $exs_pt, '5' ); ?>><?php esc_html_e( '5em', 'exs' ); ?></option>
				</select>
			</p>


			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'pb' ) ); ?>"><?php esc_html_e( 'Bottom space:', 'exs' ); ?></label>
				<select
					name="<?php echo esc_attr( $this->get_field_name( 'pb' ) ); ?>"
					id="<?php echo esc_attr( $this->get_field_id( 'pb' ) ); ?>"
					class="widefat"
				>
					<option value=""<?php selected( $exs_pb, '' ); ?>><?php esc_html_e( 'None', 'exs' ); ?></option>
					<option
						value="05"<?php selected( $exs_pb, '05' ); ?>><?php esc_html_e( '0.5em', 'exs' ); ?></option>
					<option
						value="1"<?php selected( $exs_pb, '1' ); ?>><?php esc_html_e( '1em', 'exs' ); ?></option>
					<option
						value="2"<?php selected( $exs_pb, '2' ); ?>><?php esc_html_e( '2em', 'exs' ); ?></option>
					<option
						value="3"<?php selected( $exs_pb, '3' ); ?>><?php esc_html_e( '3em', 'exs' ); ?></option>
					<option
						value="4"<?php selected( $exs_pb, '4' ); ?>><?php esc_html_e( '4em', 'exs' ); ?></option>
					<option
						value="5"<?php selected( $exs_pb, '5' ); ?>><?php esc_html_e( '5em', 'exs' ); ?></option>
				</select>
			</p>

			<p>
				<input
					class="checkbox"
					type="checkbox"<?php checked( $exs_text_center ); ?>
					id="<?php echo esc_attr( $this->get_field_id( 'text_center' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'text_center' ) ); ?>"/>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'text_center' ) ); ?>"><?php esc_html_e( 'Center alignment', 'exs' ); ?></label>
			</p>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'css_class' ) ); ?>"><?php esc_html_e( 'Custom CSS class:', 'exs' ); ?></label>
				<input
					class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'css_class' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'css_class' ) ); ?>"
					type="text"
					value="<?php echo esc_attr( $exs_css_class ); ?>"/>
			</p>
			<?php
		}
	}

endif; //class_exists

if ( ! function_exists( 'exs_theme_register_widget_theme_spacer' ) ) :
	function exs_theme_register_widget_theme_spacer() {
		register_widget( 'ExS_Widget_Theme_Spacer' );
	}
endif;
add_action( 'widgets_init', 'exs_theme_register_widget_theme_spacer' );
