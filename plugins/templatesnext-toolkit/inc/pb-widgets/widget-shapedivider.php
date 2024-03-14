<?php
	/*
	Widget Name: NX Shape Divider For Page Builder
	Description: NX Shape Divider Widget For Page Builder.
	Author: templatesNext
	Author URI:Author URI: http://www.TemplatesNext.org
	*/	

	class nx_shapedivider_widget extends WP_Widget {
		
		//function nx_prodscroll_widget() {
		function __construct() {	
			$widget_ops = array( 
				'classname' => 'widget-nx-shapedivider', 
				'description' => 'Shape divider widget for pagebuilder',
				'panels_icon' => 'dashicons dashicons-screenoptions',
				'panels_groups' => array('tx')				
			);
        	parent::__construct( 'widget-nx-shapedivider', 'TX Shape Divider ( for PB )', $widget_ops );				
		}
	
		function form($instance) {
		$defaults = array( 
			'divider_type' => 'slanted', 
			'bg_color_1' => '#FFFFFF', 
			'bg_color_2' => '#BBBBBB', 
			'height' => 100,
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		
	?>
	<div class="nx-widget-content">		
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Divider Type', 'tx');?>:</label>

            <select id="<?php echo $this->get_field_id( 'divider_type' ); ?>" name="<?php echo $this->get_field_name( 'divider_type' ); ?>" value="<?php echo $instance['divider_type']; ?>" class="nx-widselect nx-pb-input">
            
                <option value="triangle"><?php _e('Triangle', 'tx');?></option>	
                <option value="slanted"><?php _e('Slanted', 'tx');?></option>					
                <option value="big-triangle-up"><?php _e('Big Triangle Upward', 'tx');?></option>					
                <option value="big-triangle-dn"><?php _e('Big Triangle Downward', 'tx');?></option>					
                <option value="curve-up"><?php _e('Curve Up', 'tx');?></option>					
                <option value="curve-dn"><?php _e('Curve Down', 'tx');?></option>					
                <option value="big-triangle-shadow"><?php _e('Big Triangle With Shadow', 'tx');?></option>
                  
            </select>            
		</p>
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Color 1', 'tx');?>:</label>
            <input id="<?php echo $this->get_field_id( 'bg_color_1' ); ?>" name="<?php echo $this->get_field_name( 'bg_color_1' ); ?>" value="<?php echo $instance['bg_color_1']; ?>" class="nx-widenumber nx-pb-input tx-color" type="text" />
		</p> 
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Color 2', 'tx');?>:</label>
            <input id="<?php echo $this->get_field_id( 'bg_color_2' ); ?>" name="<?php echo $this->get_field_name( 'bg_color_2' ); ?>" value="<?php echo $instance['bg_color_2']; ?>" class="nx-widenumber nx-pb-input tx-color" type="text" />
            <br /><span class="small"><?php _e('2nd color only used with Slanted and Big Triangle with shadow.', 'tx');?></span>            
		</p>
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Height', 'tx');?>:</label>
            <input id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $instance['height']; ?>" class="nx-widselect nx-pb-input" type="text" />
            <br /><span class="small"><?php _e('Height of the shape divider in PX..', 'tx');?></span>
		</p> 
	</div>
	<script>
        	
		jQuery(document).ready(function($) {			
			$('.tx-color').wpColorPicker();

			$( "input.txRange" ).each(function( index ) {
					
				var txRange = $(this);
				var nxPrevi = $(this).prev( ".nxPrevi" );
					
				txRange.bind("input", function() {
					var newRange = txRange.val(); 
					nxPrevi.val(newRange);
				});				
			});					
		});

    </script>     	
	<?php	
		}
	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['divider_type'] = strip_tags( $new_instance['divider_type'] );
			$instance['bg_color_1'] = strip_tags( $new_instance['bg_color_1'] );
			$instance['bg_color_2'] = strip_tags( $new_instance['bg_color_2'] );
			$instance['height'] = strip_tags( $new_instance['height'] );
			return $instance;
		}
		
		function widget($args, $instance) {
			
			extract( $args );
	
			$divider_type = esc_html($instance['divider_type']);
			$height = esc_attr($instance['height']);
			$bg_color_1 = esc_attr($instance['bg_color_1']);
			$bg_color_2 = esc_attr($instance['bg_color_2']);
	
			$output = '';
			//nx_shapedivider divider_type="big-triangle-shadow" bg_color_1="#dd3333" bg_color_2="#dd3399" height="150"
			$output .= '<div>[tx_shapedivider divider_type="'.$divider_type.'" bg_color_1="'.$bg_color_1.'" bg_color_2="'.$bg_color_2.'" height="'.$height.'"]</div>';
			
			echo $output;
	
		}
			
	}
	
	add_action( 'widgets_init', 'nx_load_shapedivider_widget' );
	
	function nx_load_shapedivider_widget() {
		register_widget('nx_shapedivider_widget');
	}
