<?php
	
	/*
	*
	*	NX Skill Bar/Progress Bar For Page Builder 
	*	------------------------------------------------
	*	TemplatesNext
	* 	Copyright TemplatesNext 2014 - http://www.TemplatesNext.org
	*/
	
	/*
	*	Plugin Name: NX Skill Bar/Progress Bar For Page Builder Widget
	*	Plugin URI: http://www.TemplatesNext.org
	*	Description: NX progressbar Widget For Page Builder
	*	Author: templatesNext
	*	Version: 1.0
	*	Author URI: http://www.TemplatesNext.org
	*/		

	class nx_progressbar_widget extends WP_Widget {
		
		//function nx_progressbar_widget() {
		function __construct() {	
			$widget_ops = array( 
			'classname' => 'widget-nx-progressbar', 
			'description' => 'Skill Bar/Progress Bar widget for pagebuilder',
			'panels_icon' => 'dashicons dashicons-screenoptions',
			'panels_groups' => array('tx')			
		);
        	parent::__construct( 'widget-nx-progressbar', 'TX Skill Bar/Progress Bar ( for PB )', $widget_ops );				
		}
	
		function form($instance) {
		$defaults = array( 
			'skill_name' => 'Skill Title', 
			'percent' => 72,
			'barcolor' => '#dd9933', 
			'trackcolor' => '#f6dab0', 			
			'barheight' => 32,
			'candystrip' => 'no',
			'class' => '',	
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
	
	?>
	<div class="nx-widget-content">		
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Skill Name', 'tx');?>:</label>
			<input id="<?php echo $this->get_field_id( 'skill_name' ); ?>" name="<?php echo $this->get_field_name( 'skill_name' ); ?>" value="<?php echo $instance['skill_name']; ?>" class="nx-widenumber nx-pb-input" type="text" />          
		</p>
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Percent', 'tx');?>:</label>
			<input id="<?php echo $this->get_field_id( 'percent' ); ?>" name="<?php echo $this->get_field_name( 'percent' ); ?>" value="<?php echo $instance['percent']; ?>" class="nx-pb-input tx-range-prev txPrevi"  type="text" />
			<input type="range"  min="1" max="100" step="1" value="72" class="txRange tx-range-slider">            
		</p>           
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Bar Color', 'tx');?>:</label>
            <input id="<?php echo $this->get_field_id( 'barcolor' ); ?>" name="<?php echo $this->get_field_name( 'barcolor' ); ?>" value="<?php echo $instance['barcolor']; ?>" class="nx-widenumber nx-pb-input tx-color" type="text" />
		</p> 
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Track Color', 'tx');?>:</label>
            <input id="<?php echo $this->get_field_id( 'trackcolor' ); ?>" name="<?php echo $this->get_field_name( 'trackcolor' ); ?>" value="<?php echo $instance['trackcolor']; ?>" class="nx-widenumber nx-pb-input tx-color" type="text" />
		</p>         
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Bar Height', 'tx');?>:</label>
			<input id="<?php echo $this->get_field_id( 'barheight' ); ?>" name="<?php echo $this->get_field_name( 'barheight' ); ?>" value="<?php echo $instance['barheight']; ?>" class="nx-pb-input tx-range-prev txPrevi"  type="text" />
			<input type="range"  min="24" max="48" step="1" value="32" class="txRange tx-range-slider"> 
            
		</p>   
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Candystrip Animation', 'tx');?>:</label>
            <select id="<?php echo $this->get_field_id( 'candystrip' ); ?>" name="<?php echo $this->get_field_name( 'candystrip' ); ?>" value="<?php echo $instance['candystrip']; ?>" class="nx-widselect nx-pb-input">
                <option value="no"><?php _e('Yes', 'tx');?></option>					
                <option value="yes"><?php _e('No', 'tx');?></option>					
            </select>                    
		</p>
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Class', 'tx');?>:</label>
            <input id="<?php echo $this->get_field_id( 'class' ); ?>" name="<?php echo $this->get_field_name( 'class' ); ?>" value="<?php echo $instance['class']; ?>" class="nx-widenumber nx-pb-input" type="text" />
		</p> 
	</div>
		<script>
        	
			jQuery(document).ready(function($) {
				$('.tx-color').wpColorPicker();
				
				$( "input.txRange" ).each(function( index ) {
					
					var txRange = $(this);
					var txPrevi = $(this).prev( ".txPrevi" );
					
					txRange.bind("input", function() {
						var newRange = txRange.val(); 
						txPrevi.val(newRange);
					});				
				});					
			});

        </script>    	
	<?php	
		}
	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['skill_name'] = strip_tags( $new_instance['skill_name'] );
			$instance['percent'] = strip_tags( $new_instance['percent'] );			
			$instance['barcolor'] = strip_tags( $new_instance['barcolor'] );
			$instance['trackcolor'] = strip_tags( $new_instance['trackcolor'] );			
			$instance['barheight'] = strip_tags( $new_instance['barheight'] );
			$instance['candystrip'] = strip_tags( $new_instance['candystrip'] );
																		
			return $instance;
		}
		
		function widget($args, $instance) {
			
			extract( $args );
	
			$skill_name = esc_attr($instance['skill_name']);
			$percent = esc_attr($instance['percent']);
			$barcolor = esc_attr($instance['barcolor']);
			$trackcolor = esc_attr($instance['trackcolor']);			
			$barheight = esc_attr($instance['barheight']);
			$candystrip = esc_attr($instance['candystrip']);
	
			$output = '';
			
			$output .= '<div>[tx_progressbar skill_name="'.$skill_name.'" percent="'.$percent.'" barcolor="'.$barcolor.'" trackcolor="'.$trackcolor.'" barheight="'.$barheight.'" candystrip="'.$candystrip.'"]</div>';
			
			echo $output;
	
		}
			
	}
	
	add_action( 'widgets_init', 'nx_load_progressbar_widget' );
	
	function nx_load_progressbar_widget() {
		register_widget('nx_progressbar_widget');
	}