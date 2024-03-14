<?php // Contact Form X Widget

if (!defined('ABSPATH')) die();

if (!class_exists('ContactFormX_Widget')) :

class ContactFormX_Widget extends WP_Widget {
	
	public function __construct() {
		
		$args = array('classname' => 'contactformx', 'description' => esc_html__('Display Contact Form X', 'contact-form-x'));
		
		parent::__construct('contactformx', esc_html__('Contact Form X', 'contact-form-x'), $args);
		
	}
	
	public function widget($args, $instance) {
		
		echo contactformx();
		
	}
	
	public function update($new_instance, $old_instance) {
		
		return $old_instance;
		
	}
	
	public function form($instance) {
		
		echo '<p>'. esc_html__('Visit the plugin settings to configure the contact form.', 'contact-form-x') .'</p>';
		
	}
	
}

function contactformx_register_widget() {
	
	register_widget('ContactFormX_Widget');
	
}

endif;