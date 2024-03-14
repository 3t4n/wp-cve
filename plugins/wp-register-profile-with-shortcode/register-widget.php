<?php

class Register_Wid extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
	 		'register_wid',
			'Registration Widget',
			array( 'description' => __( 'This is a simple user registration form in the widget.', 'wp-register-profile-with-shortcode' ), )
		);
	 }

	public function widget( $args, $instance ) {
		extract( $args );
		if( is_user_logged_in() ){
			return;
		}
		$wid_title = apply_filters( 'widget_title', $instance['wid_title'] );
		echo $args['before_widget'];
		if ( ! empty( $wid_title ) )
		echo $args['before_title'] . $wid_title . $args['after_title'];
		$rf = new Register_Form;
		$rf->registration_form();
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['wid_title'] = sanitize_text_field( $new_instance['wid_title'] );
		return $instance;
	}

	public function form( $instance ) {
		$wid_title = '';
		if(!empty($instance[ 'wid_title' ])){
			$wid_title = $instance[ 'wid_title' ];
		}
		?>
		<p><label for="<?php echo $this->get_field_id('wid_title'); ?>"><?php _e('Title','wp-register-profile-with-shortcode'); ?> </label>
		<input class="widefat" id="<?php echo $this->get_field_id('wid_title'); ?>" name="<?php echo $this->get_field_name('wid_title'); ?>" type="text" value="<?php echo $wid_title; ?>" />
		</p>
        <p><?php _e( 'Registration form will not be displayed if user is Logged In', 'wp-register-profile-with-shortcode' );?></p>
		<?php 
	}
	
} 

