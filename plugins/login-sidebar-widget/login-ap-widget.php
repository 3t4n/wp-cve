<?php

class Login_Widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
	 		'login_wid',
			'Login Widget',
			array( 'description' => __( 'This is a simple login form in the widget.', 'login-sidebar-widget' ), )
		);
	 }

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['wid_title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['wid_title'] ) . $args['after_title'];
		}
		$aplf = new AP_Login_Form;
		$aplf->login_form( $args['widget_id'] );
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['wid_title'] = strip_tags( $new_instance['wid_title'] );
		return $instance;
	}

	public function form( $instance ) {
		$wid_title = '';
		if(!empty($instance[ 'wid_title' ])){
			$wid_title = esc_html($instance[ 'wid_title' ]);
		}
		?>
		<p><label for="<?php echo $this->get_field_id('wid_title'); ?>"><?php _e('Title','login-sidebar-widget'); ?> </label>
        <?php Form_Class::form_input('text',$this->get_field_name('wid_title'),$this->get_field_id('wid_title'),$wid_title,'widefat');?>
		</p>
		<?php 
	}
	
} 