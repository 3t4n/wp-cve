<?php

class PMCS_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'pmcs-widget',
			'description' => __( 'Display currency switcher on front-end.', 'pmcs' ),
		);
		parent::__construct( 'pmcs_widget', __( 'Currency Swicther', 'pmcs' ), $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		echo pmcs()->shortcode->init( $instance, $args ); // WPCS: XSS ok.
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options.
	 */
	public function form( $instance ) {
		$title        = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Currency Swicther', 'pmcs' );
		$display_type = ! empty( $instance['display_type'] ) ? $instance['display_type'] : '';
		$show_flag    = ! isset( $instance['show_flag'] ) ? 1 : $instance['show_flag'];
		$show_name    = ! empty( $instance['show_name'] ) ? $instance['show_name'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'pmcs' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'display_type' ) ); ?>"><?php esc_attr_e( 'Display type:', 'pmcs' ); ?></label> 
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'display_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_type' ) ); ?>">
				<option <?php selected( $show_name, 'list' ); ?> value="list"><?php _e( 'List', 'pmcs' ); ?></option>
				<option <?php selected( $show_name, 'dropdown' ); ?> value="dropdown"><?php _e( 'Dropdown', 'pmcs' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_flag' ) ); ?>"><input id="<?php echo esc_attr( $this->get_field_id( 'show_flag' ) ); ?>" <?php checked( $show_flag, 1 ); ?> name="<?php echo esc_attr( $this->get_field_name( 'show_flag' ) ); ?>" type="checkbox" value="1"> <?php esc_attr_e( 'Show currency flags', 'pmcs' ); ?></label> 
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_name' ) ); ?>"><?php esc_attr_e( 'Display name:', 'pmcs' ); ?></label> 
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_name' ) ); ?>">
				<option <?php selected( $show_name, 'name' ); ?> value="name"><?php _e( 'Currency name', 'pmcs' ); ?></option>
				<option <?php selected( $show_name, 'code' ); ?> value="code"><?php _e( 'Currency code', 'pmcs' ); ?></option>
			</select>
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options.
	 * @param array $old_instance The previous options.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                 = array();
		$instance['title']        = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['display_type'] = ( ! empty( $new_instance['display_type'] ) ) ? sanitize_text_field( $new_instance['display_type'] ) : '';
		$instance['show_flag']    = isset( $new_instance['show_flag'] ) ? sanitize_text_field( $new_instance['show_flag'] ) : '';
		$instance['show_name']    = ( ! empty( $new_instance['show_name'] ) ) ? sanitize_text_field( $new_instance['show_name'] ) : '';
		return $instance;
	}
}
