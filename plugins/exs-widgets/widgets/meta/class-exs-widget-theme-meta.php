<?php
/**
 * Widget API: ExS_Widget_Theme_Meta class
 *
 * @package ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//this widget make sense only for ExS theme - it shows content from theme customizer settings
//TODO process layout
if ( 'exs' !== get_template() && 'exs-pro' !== get_template() ) :
	return;
endif;


if ( ! class_exists( 'ExS_Widget_Theme_Meta' ) ) :

	class ExS_Widget_Theme_Meta extends WP_Widget {

		/**
		 * Sets up a new Recent Posts widget instance.
		 *
		 * @since 0.0.1
		 */
		public function __construct() {
			$exs_widget_ops = array(
				'classname'                   => 'widget_theme_meta',
				'description'                 => esc_html__( 'Theme meta from customizer settings.', 'exs' ),
				'customize_selective_refresh' => true,
			);

			add_action( 'admin_print_scripts-widgets.php', array( $this, 'enqueue_admin_scripts' ) );

			parent::__construct( 'theme-meta', esc_html__( 'ExS Theme Meta', 'exs' ), $exs_widget_ops );
			$this->alt_option_name = 'widget_theme_meta';
		}

		/**
		 * Loads the required scripts and styles for the widget control.
		 */
		public function enqueue_admin_scripts() {
			wp_enqueue_media();

			wp_enqueue_script(
				'exs-widgets-admin-script',
				EXS_WIDGETS_PLUGIN_ASSETS_URL . 'js/media.js',
				array( 'jquery' ),
				'0.0.1',
				true
			);
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

			$exs_image_uri = ( ! empty( $exs_instance['image_uri'] ) ) ? $exs_instance['image_uri'] : '';
			$exs_sub_title = ( ! empty( $exs_instance['sub_title'] ) ) ? $exs_instance['sub_title'] : '';

			$exs_show_email         = isset( $exs_instance['show_email'] ) ? $exs_instance['show_email'] : false;
			$exs_show_phone         = isset( $exs_instance['show_phone'] ) ? $exs_instance['show_phone'] : false;
			$exs_show_address       = isset( $exs_instance['show_address'] ) ? $exs_instance['show_address'] : false;
			$exs_show_opening_hours = isset( $exs_instance['show_opening_hours'] ) ? $exs_instance['show_opening_hours'] : false;
			$exs_show_social_links  = isset( $exs_instance['show_social_links'] ) ? $exs_instance['show_social_links'] : false;
			$exs_text_center        = isset( $exs_instance['text_center'] ) ? $exs_instance['text_center'] : false;
			$exs_layout             = ( ! empty( $exs_instance['layout'] ) ) ? esc_attr( $exs_instance['layout'] ) : 'default';
			$exs_css_class          = ( ! empty( $exs_instance['css_class'] ) ) ? sanitize_text_field( $exs_instance['css_class'] ) : '';

			$exs_filepath = EXS_WIDGETS_PLUGIN_PATH . 'widgets/meta/views/' . $exs_layout . '.php';

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
			$exs_instance                       = $exs_old_instance;
			$exs_instance['title']              = sanitize_text_field( $exs_new_instance['title'] );
			$exs_instance['image_uri']          = esc_url( $exs_new_instance['image_uri'] );
			$exs_instance['sub_title']          = sanitize_text_field( $exs_new_instance['sub_title'] );
			$exs_instance['show_email']         = isset( $exs_new_instance['show_email'] ) ? (bool) $exs_new_instance['show_email'] : false;
			$exs_instance['show_phone']         = isset( $exs_new_instance['show_phone'] ) ? (bool) $exs_new_instance['show_phone'] : false;
			$exs_instance['show_address']       = isset( $exs_new_instance['show_address'] ) ? (bool) $exs_new_instance['show_address'] : false;
			$exs_instance['show_opening_hours'] = isset( $exs_new_instance['show_opening_hours'] ) ? (bool) $exs_new_instance['show_opening_hours'] : false;
			$exs_instance['show_social_links']  = isset( $exs_new_instance['show_social_links'] ) ? (bool) $exs_new_instance['show_social_links'] : false;
			$exs_instance['text_center']        = isset( $exs_new_instance['text_center'] ) ? (bool) $exs_new_instance['text_center'] : false;
			$exs_instance['layout']             = esc_attr( $exs_new_instance['layout'] );
			$exs_instance['css_class']          = sanitize_text_field( $exs_new_instance['css_class'] );

			return $exs_instance;
		}

		/**
		 * Outputs the settings form for the Recent Posts widget.
		 *
		 * @param array $exs_instance Current settings.
		 */
		public function form( $exs_instance ) {
			$exs_title              = isset( $exs_instance['title'] ) ? esc_attr( $exs_instance['title'] ) : '';
			$exs_image_uri          = isset( $exs_instance['image_uri'] ) ? esc_url( $exs_instance['image_uri'] ) : '';
			$exs_sub_title          = isset( $exs_instance['sub_title'] ) ? esc_attr( $exs_instance['sub_title'] ) : '';
			$exs_show_email         = isset( $exs_instance['show_email'] ) ? (bool) $exs_instance['show_email'] : false;
			$exs_show_phone         = isset( $exs_instance['show_phone'] ) ? (bool) $exs_instance['show_phone'] : false;
			$exs_show_address       = isset( $exs_instance['show_address'] ) ? (bool) $exs_instance['show_address'] : false;
			$exs_show_opening_hours = isset( $exs_instance['show_opening_hours'] ) ? (bool) $exs_instance['show_opening_hours'] : false;
			$exs_show_social_links  = isset( $exs_instance['show_social_links'] ) ? (bool) $exs_instance['show_social_links'] : false;
			$exs_text_center        = isset( $exs_instance['text_center'] ) ? (bool) $exs_instance['text_center'] : false;
			$exs_layout             = isset( $exs_instance['layout'] ) ? esc_attr( $exs_instance['layout'] ) : 'default';
			$exs_css_class          = isset( $exs_instance['css_class'] ) ? sanitize_text_field( $exs_instance['css_class'] ) : '';
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

			<div class="exs-meta-widget-media">
				<div>
					<label for="<?php echo esc_attr( $this->get_field_id( 'image_uri' ) ); ?>_img"><?php esc_html_e( 'Image:', 'exs' ); ?></label>
				</div>
				<div>
					<img class="<?php echo esc_attr( $this->get_field_id( 'image_uri' ) ); ?>_img" src="<?php echo ( ! empty( $exs_image_uri ) ) ? esc_url( $exs_image_uri ) : ''; ?>" style="max-width:100%;"/>
				</div>
				<input type="text" class="widefat <?php echo esc_attr( $this->get_field_id( 'image_uri' ) ); ?>_url" name="<?php echo esc_attr( $this->get_field_name( 'image_uri' ) ); ?>" value="<?php echo esc_attr( $exs_image_uri ); ?>" hidden />
				<div>
					<button type="button" id="<?php echo esc_attr( $this->get_field_id( 'image_uri' ) ); ?>" class="dashicons-before dashicons-upload button button-primary exs_meta_widget_upload_media"></button>
					<button type="button" id="<?php echo esc_attr( $this->get_field_id( 'image_uri' ) ); ?>_remove" class="dashicons-before dashicons-dismiss button exs_meta_widget_remove_media"></button>
				</div>
			</div>

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
						value="default"<?php selected( $exs_layout, 'default' ); ?>><?php esc_html_e( 'Default list', 'exs' ); ?></option>
				</select>
			</p>

			<p>
				<input
					class="checkbox"
					type="checkbox"<?php checked( $exs_show_email ); ?>
					id="<?php echo esc_attr( $this->get_field_id( 'show_email' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'show_email' ) ); ?>"/>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'show_email' ) ); ?>"><?php esc_html_e( 'Display email if set?', 'exs' ); ?></label>
			</p>

			<p>
				<input
					class="checkbox"
					type="checkbox"<?php checked( $exs_show_phone ); ?>
					id="<?php echo esc_attr( $this->get_field_id( 'show_phone' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'show_phone' ) ); ?>"/>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'show_phone' ) ); ?>"><?php esc_html_e( 'Display phone if set?', 'exs' ); ?></label>
			</p>


			<p>
				<input
					class="checkbox"
					type="checkbox"<?php checked( $exs_show_address ); ?>
					id="<?php echo esc_attr( $this->get_field_id( 'show_address' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'show_address' ) ); ?>"/>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'show_address' ) ); ?>"><?php esc_html_e( 'Display address if set?', 'exs' ); ?></label>
			</p>

			<p>
				<input
					class="checkbox"
					type="checkbox"<?php checked( $exs_show_opening_hours ); ?>
					id="<?php echo esc_attr( $this->get_field_id( 'show_opening_hours' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'show_opening_hours' ) ); ?>"/>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'show_opening_hours' ) ); ?>"><?php esc_html_e( 'Display opening hours if set?', 'exs' ); ?></label>
			</p>

			<p>
				<input
					class="checkbox"
					type="checkbox"<?php checked( $exs_show_social_links ); ?>
					id="<?php echo esc_attr( $this->get_field_id( 'show_social_links' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'show_social_links' ) ); ?>"/>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'show_social_links' ) ); ?>"><?php esc_html_e( 'Display social links if set?', 'exs' ); ?></label>
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


if ( ! function_exists( 'exs_theme_register_widget_theme_meta' ) ) :
	function exs_theme_register_widget_theme_meta() {
		register_widget( 'ExS_Widget_Theme_Meta' );
	}
endif;
add_action( 'widgets_init', 'exs_theme_register_widget_theme_meta' );
