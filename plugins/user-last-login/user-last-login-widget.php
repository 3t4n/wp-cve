<?php
// Creating the widget 
class userLastLoginWidget extends WP_Widget {

	function __construct() {
		parent::__construct('user_last_login_widget', __('User Last Login', 'user_last_login'), array( 'description' => __( 'To Show the current user last login date and time', 'user_last_login' ), ) 
		);
	}

	/**
	 * Fronted view of widget.
	 * @author Raj K 
	 * @since 1.0 - 19-02-2017 
	 * @param array $args, $instance
	 * @return none
	 **/
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$format = !empty($instance['format'])?$instance['format']:get_option( 'date_format' ).' '.get_option( 'time_format' );
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		$last_login = (int) get_user_meta( get_current_user_id(), 'wp-last-login', true );
		echo  date_i18n($format, $last_login );
		echo $args['after_widget'];
	}
		
	/**
	 * Backend view of widget.
	 * @author Raj K 
	 * @since 1.0 - 19-02-2017 
	 * @param $instance
	 * @return none
	 **/
	public function form( $instance ) {
		$title = isset( $instance[ 'title' ] )?$instance[ 'title' ] :'User Last Login';
		$format = isset( $instance[ 'format' ] )?$instance[ 'format' ] :get_option( 'date_format' ).' '.get_option( 'time_format' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'format' ); ?>"><?php _e( 'DateTime Format:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'format' ); ?>" name="<?php echo $this->get_field_name( 'format' ); ?>" type="text" value="<?php echo esc_attr( $format ); ?>" />
		</p>
	<?php 
	}
	
	/**
	 * Update setting data of widget.
	 * @author Raj K 
	 * @since 1.0 - 19-02-2017 
	 * @param array $new_instance, $old_instance
	 * @return none
	 **/
	public function update( $new_instance, $old_instance ) {
		$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['format'] = ( ! empty( $new_instance['format'] ) ) ? strip_tags( $new_instance['format'] ) : '';
			return $instance;
		}
} 

// Register and load the widget
function user_last_login_load_widget() {
	register_widget( 'userLastLoginWidget' );
}

add_action( 'widgets_init', 'user_last_login_load_widget' );
