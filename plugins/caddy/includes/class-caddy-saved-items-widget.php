<?php

/**
 * The file that used to register and load the saved items widget
 *
 * @since      1.0.0
 * @package    Caddy
 * @subpackage Caddy/includes
 */
class caddy_saved_items_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'caddy_saved_items_widget',
			__( 'Caddy Saved List', 'caddy' ),
			array( 'description' => __( 'Caddy saved list widget', 'caddy' ), )
		);
	}

	/**
	 * Creating front-end widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$si_widget_title = isset($instance['si_widget_title']) ? apply_filters('widget_title', $instance['si_widget_title']) : '';
	
		echo $args['before_widget'];
		if (!empty($si_widget_title)) {
			echo $args['before_title'] . $si_widget_title . $args['after_title'];
		}
	
		$si_text = !empty($instance['si_text']) ? $instance['si_text'] : '';
		$saved_items_link = sprintf(
			'<a href="%1$s" class="cc_saved_items_list" aria-label="%2$s">%3$s %4$s</a>',
			'javascript:void(0);',
			esc_html__('Saved Items', 'caddy'),
			('on' == $instance['cc_si_icon']) ? '' : '<i class="ccicon-heart-empty"></i>',
			esc_html($si_text)
		);
		echo $saved_items_link;
	
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form
	 *
	 * @param array $instance
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		$si_widget_title = isset( $instance['si_widget_title'] ) ? $instance['si_widget_title'] : __( 'New title', 'caddy' );
		$si_text         = isset( $instance['si_text'] ) ? $instance['si_text'] : __( 'Saved Items', 'caddy' );
		$si_icon_checked = ( isset( $instance['cc_si_icon'] ) && 'on' == $instance['cc_si_icon'] ) ? ' checked="checked"' : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'si_widget_title' ); ?>"><?php _e( 'Widget Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'si_widget_title' ); ?>" name="<?php echo $this->get_field_name( 'si_widget_title' ); ?>"
			       type="text" value="<?php echo esc_attr( $si_widget_title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'si_text' ); ?>"><?php _e( 'Save For Later Text:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'si_text' ); ?>" name="<?php echo $this->get_field_name( 'si_text' ); ?>" type="text"
			       value="<?php echo esc_attr( $si_text ); ?>" />
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php echo $si_icon_checked; ?> id="<?php echo $this->get_field_id( 'cc_si_icon' ); ?>"
			       name="<?php echo $this->get_field_name( 'cc_si_icon' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'cc_si_icon' ); ?>"><?php _e( 'Disable saved items icon' ); ?></label>
		</p>
		<?php
	}

	/**
	 * Updating widget replacing old instances with new
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                    = array();
		$instance['si_widget_title'] = ( ! empty( $new_instance['si_widget_title'] ) ) ? strip_tags( $new_instance['si_widget_title'] ) : '';
		$instance['si_text']         = ( ! empty( $new_instance['si_text'] ) ) ? strip_tags( $new_instance['si_text'] ) : '';
		$instance['cc_si_icon']      = $new_instance['cc_si_icon'];

		return $instance;
	}

}

/**
 * Register and load the saved items widget
 */
function caddy_saved_items_widget() {
	register_widget( 'caddy_saved_items_widget' );
}

// Add action to register and load the saved items widget
add_action( 'widgets_init', 'caddy_saved_items_widget' );
