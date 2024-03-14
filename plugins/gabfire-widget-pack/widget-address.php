<?php
if ( !defined('ABSPATH')) exit;

class gabfire_contactus extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'gab_contact_widget', 'description' => 'Display an "about" widget' );
		$control_ops = array( 'width' => 400, 'height' => 350, 'id_base' => 'gab_contact_widget' );
		parent::__construct( 'gab_contact_widget', 'Gabfire: Contact', $widget_ops, $control_ops);	
	}
	
	public function widget($args, $instance) {
		extract( $args );
		$title	= $instance['title'];
		$d_logo	= $instance['d_logo'] ? '1' : '0';
		$logo_url	= $instance['logo_url'];
		$address	= $instance['address'];
		$phone	= $instance['phone'];
		$email	= $instance['email'];

		echo $before_widget;

			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			
			if($d_logo) {
				echo "<div class='gab_contact_logocont'><img src='$logo_url' class='aligncenter' alt='".get_bloginfo('name')."' /></div>";
			}	
			
			echo "<address>";
				echo wpautop($address);
			
				if($email) {
					echo "<a href='mailto:$email'>$email</a><br />";
				}
				
				if($phone) {
					echo $phone;
				}
			echo "</address>";
			
			
		echo "<div class='clear'></div>$after_widget"; 
	}
	
	function update($new_instance, $old_instance) {  
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['d_logo']	= $new_instance['d_logo'] ? '1' : '0';
		$instance['address'] = ( ! empty( $new_instance['address'] ) ) ? sanitize_text_field( $new_instance['address'] ) : '';
		$instance['email'] = ( ! empty( $new_instance['email'] ) ) ? sanitize_text_field( $new_instance['email'] ) : '';
		return $new_instance;
	}
 
	function form($instance) {
		$defaults	= array( 'title' => 'Address', 'd_logo' => '1', 'logo_url' => '', 'address' => '', 'phone' => '', 'email' => '');
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>

	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','gabfire-widget-pack'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
	</p>
		
	<p>
		<label for="<?php echo $this->get_field_id( 'd_logo' ); ?>"><?php _e('Show logo','gabfire-widget-pack'); ?></label> 
		<select id="<?php echo $this->get_field_id( 'd_logo' ); ?>" name="<?php echo $this->get_field_name( 'd_logo' ); ?>">
			<option value="1" <?php selected( $instance['d_logo'], '1' ); ?>><?php _e('Enable','gabfire-widget-pack'); ?></option>
			<option value="0" <?php selected( $instance['d_logo'], '0' ) ; ?>><?php _e('Disable','gabfire-widget-pack'); ?></option>	
		</select>
	</p>	
	
	<p>
		<label for="<?php echo $this->get_field_id('logo_url'); ?>"><?php _e('Enter logo URL','gabfire-widget-pack'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('logo_url'); ?>" name="<?php echo $this->get_field_name('logo_url'); ?>" type="text" value="<?php echo esc_attr($instance['logo_url']); ?>" />
	</p>	
		
	<p>
		<label for="<?php echo $this->get_field_id('address'); ?>"><?php _e('Address','gabfire-widget-pack'); ?></label>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>"><?php echo esc_attr($instance['address']); ?></textarea>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Email Address','gabfire-widget-pack'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php echo esc_attr($instance['email']); ?>" />
	</p>	
	
	<p>
		<label for="<?php echo $this->get_field_id('phone'); ?>"><?php _e('Phone Number','gabfire-widget-pack'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>" type="text" value="<?php echo esc_attr($instance['phone']); ?>" />
	</p>


<?php
	}
}

function register_gabfire_contactus() {
	register_widget('gabfire_contactus');
}

add_action('widgets_init', 'register_gabfire_contactus');