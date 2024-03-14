<?php
	
	/*
	*
	*	NX Spacer For Page Builder 
	*	------------------------------------------------
	*	TemplatesNext
	* 	Copyright TemplatesNext 2014 - http://www.TemplatesNext.org
	*/
	
	/*
	*	Plugin Name: NX Spacer For Page Builder Widget
	*	Plugin URI: http://www.TemplatesNext.org
	*	Description: NX spacer Widget For Page Builder
	*	Author: templatesNext
	*	Version: 1.0
	*	Author URI: http://www.TemplatesNext.org
	*/		

	class nx_spacer_widget extends WP_Widget {
		
		//function nx_spacer_widget() {
		function __construct() {	
			$widget_ops = array( 
			'classname' => 'widget-nx-spacer', 
			'description' => 'Spacer widget for pagebuilder',
			'panels_icon' => 'dashicons dashicons-screenoptions',
			'panels_groups' => array('tx')			
		);
        	parent::__construct( 'widget-nx-spacer', 'TX Spacer ( for PB )', $widget_ops );				
		}
	
		function form($instance) {
		$defaults = array( 
			'spacer_size' => '16'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
	
	?>
	<div class="nx-widget-content">		
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Spacer Size', 'tx');?>:</label>
            <input id="<?php echo $this->get_field_id( 'spacer_size' ); ?>" name="<?php echo $this->get_field_name( 'spacer_size' ); ?>" value="<?php echo $instance['spacer_size']; ?>" class="nx-pb-input tx-range-prev txPrevi" type="text" />
            <input type="range" min="1" max="240" step="1" value="16" class="txRange tx-range-slider">
            <small><?php _e('Vertical space between 2 elements in px', 'tx');?></small>
		</p>         
	</div>
		<script>
        	
			jQuery(document).ready(function($) {
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
			$instance['spacer_size'] = strip_tags( $new_instance['spacer_size'] );
			return $instance;
		}
		
		function widget($args, $instance) {
			
			extract( $args );
	
			$spacer_size = esc_attr($instance['spacer_size']);
	
			$output = '';
			
			$output .= '<div>[tx_spacer size="'.$spacer_size.'"]</div>';
			
			echo $output;
	
		}
			
	}
	
	add_action( 'widgets_init', 'nx_load_spacer_widget' );
	
	function nx_load_spacer_widget() {
		register_widget('nx_spacer_widget');
	}
