<?php
	
	/*
	*
	*	NX Testimonials For Page Builder 
	*	------------------------------------------------
	*	TemplatesNext
	* 	Copyright TemplatesNext 2014 - http://www.TemplatesNext.org
	*/
	
	/*
	*	Plugin Name: NX Testimonials For Page Builder Widget
	*	Plugin URI: http://www.TemplatesNext.org
	*	Description: NX testimonials Widget For Page Builder
	*	Author: templatesNext
	*	Version: 1.0
	*	Author URI: http://www.TemplatesNext.org
	*/		

	class nx_testimonials_widget extends WP_Widget {
		
		//function nx_testimonials_widget() {
		function __construct() {	
			$widget_ops = array( 
			'classname' => 'widget-nx-testimonials', 
			'description' => 'Testimonials widget for pagebuilder',
			'panels_icon' => 'dashicons dashicons-screenoptions',
			'panels_groups' => array('tx')			
		);
        	parent::__construct( 'widget-nx-testimonials', 'TX Testimonials ( for PB )', $widget_ops );				
		}
	
		function form($instance) {
		$defaults = array( 
			'testimonials_style' => 'default'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
	
	?>
	<div class="nx-widget-content">		
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Testimonials Style', 'tx');?>:</label>

            <select id="<?php echo $this->get_field_id( 'testimonials_style' ); ?>" name="<?php echo $this->get_field_name( 'testimonials_style' ); ?>" value="<?php echo $instance['testimonials_style']; ?>" class="nx-widselect nx-pb-input">
              <option value="default"><?php _e('Default', 'tx');?></option>
            </select>            
		</p>
	</div>		
	<?php	
		}
	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['testimonials_style'] = strip_tags( $new_instance['testimonials_style'] );
			return $instance;
		}
		
		function widget($args, $instance) {
			
			extract( $args );
	
			$testimonials_style = esc_attr($instance['testimonials_style']);
	
			$output = '';
			
			$output .= '<div>[tx_testimonial style="'.$testimonials_style.'"]</div>';
			
			echo $output;
	
		}
			
	}
	
	add_action( 'widgets_init', 'nx_load_testimonials_widget' );
	
	function nx_load_testimonials_widget() {
		register_widget('nx_testimonials_widget');
	}