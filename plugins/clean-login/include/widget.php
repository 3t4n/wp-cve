<?php

class CleanLogin_Widget{
    public function load(){
		add_action( 'widgets_init', array( $this, 'register' ) );
    }

    function register(){
		register_widget( 'CleanLogin_WPWidget' );
    }
    
}

class CleanLogin_WPWidget extends WP_Widget {
	function __construct() {
		parent::__construct(
			'clean_login_widget', 
			'Clean Login status and links', 
			array( 'description' => __( 'Use this widget to show the user login status and Clean Login links.', 'clean-login' ), ) 
		);
	}

	public function widget( $args, $instance ) {
		$title = ( isset( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : __( 'User login status', 'clean-login' );

		$title = apply_filters( 'widget_title', $title );
		
		echo $args['before_widget'];
		
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		$login_url = get_option( 'cl_login_url', '');
		$edit_url = get_option( 'cl_edit_url', '');
		$register_url = get_option( 'cl_register_url', '');
		$restore_url = get_option( 'cl_restore_url', '');

		if ( is_user_logged_in() ) {
			$current_user = wp_get_current_user();
			
			echo get_avatar( $current_user->ID, 96 );
			if ( $current_user->user_firstname == '')
				echo "<h1 class='widget-title'>$current_user->user_login</h1>";
			else
				echo "<h1 class='widget-title'>$current_user->user_firstname $current_user->user_lastname</h1>";
			
			if ( $edit_url != '' || $login_url != '' ) echo "<ul>";
			
			if ( $edit_url != '' )
				echo "<li><a href='$edit_url'>". __( 'Edit my profile', 'clean-login') ."</a></li>";

			if ( $login_url != '' )
				echo "<li><a href='$login_url?action=logout'>". __( 'Logout', 'clean-login') ."</a></li>";
			
			if ( $edit_url != '' || $login_url != '' ) echo "</ul>";

		} else {
			echo "<ul>";
			if ( $login_url != '' ) echo "<li><a href='$login_url'>". __( 'Log in', 'clean-login') ."</a></li>";
			if ( $register_url != '' ) echo "<li><a href='$register_url'>". __( 'Register', 'clean-login') ."</a></li>";
			if ( $restore_url != '' )echo "<li><a href='$restore_url'>". __( 'Lost password?', 'clean-login') ."</a></li>";
			echo "</ul>";
		}

		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title = ( isset( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : __( 'User login status', 'clean-login' );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php __( 'Title:', 'clean-login' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}