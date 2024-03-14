<?php
	/*
	Widget Name: NX Heading For Page Builder
	Description: NX heading Widget For Page Builder.
	Author: templatesNext
	Author URI:Author URI: http://www.TemplatesNext.org
	*/	

	class nx_heading_widget extends WP_Widget {
		
		//function nx_heading_widget() {
		function __construct() {	
			$widget_ops = array( 
				'classname' => 'widget-nx-heading', 
				'description' => 'Heading widget for pagebuilder',
				'panels_icon' => 'dashicons dashicons-screenoptions',
				'panels_groups' => array('tx')				
			);
        	parent::__construct( 'widget-nx-heading', 'TX Heading ( for PB )', $widget_ops );				
		}
	
		function form($instance) {
		$defaults = array( 
			'style' => 'default', 
			'heading_text' => 'Heading Text', 
			'tag' => 'h2', 
			'size' => '24', 
			'margin' => '16', 
			'align' => 'left'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
	
	?>
	<div class="nx-widget-content">		
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php esc_html_e('Heading Style', 'tx');?>:</label>

            <select id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo esc_attr($this->get_field_name( 'style' )); ?>" value="<?php echo esc_attr($instance['style']); ?>" class="nx-widselect nx-pb-input">
              <option value="default"><?php esc_html_e('Default', 'tx');?></option>
            </select>            
		</p>
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php esc_html_e('Heading Text', 'tx');?>:</label>
            <input id="<?php echo esc_attr($this->get_field_id( 'heading_text' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'heading_text' )); ?>" value="<?php echo esc_attr($instance['heading_text']); ?>" class="nx-widenumber nx-pb-input" type="text" />
		</p> 
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php esc_html_e('Heading Tag', 'tx');?>:</label>
            <select id="<?php echo esc_attr($this->get_field_id( 'tag' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'tag' )); ?>" value="<?php echo esc_attr($instance['tag']); ?>" class="nx-widselect nx-pb-input">
              <option value="h2"><?php esc_html_e('H2', 'tx');?></option>
              <option value="h1"><?php esc_html_e('H1', 'tx');?></option>
              <option value="h3"><?php esc_html_e('H3', 'tx');?></option>
              <option value="h4"><?php esc_html_e('H4', 'tx');?></option>
              <option value="h5"><?php esc_html_e('H5', 'tx');?></option>
              <option value="h6"><?php esc_html_e('H6', 'tx');?></option>
              <option value="div"><?php esc_html_e('DIV', 'tx');?></option>                                                                                    
            </select>              
		</p>
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php esc_html_e('Heading Font Size (in px)', 'tx');?>:</label>
			<input id="<?php echo esc_attr($this->get_field_id( 'size' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'size' )); ?>" value="<?php echo esc_attr($instance['size']); ?>" class="nx-pb-input tx-range-prev txPrevi" type="text" />
            <input type="range" min="12" max="120" step="1" value="24" class="txRange tx-range-slider">
		</p> 
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php esc_html_e('Bottom Margin (in px)', 'tx');?>:</label>
			<input id="<?php echo esc_attr($this->get_field_id( 'margin' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'margin' )); ?>" value="<?php echo esc_attr($instance['margin']); ?>" class="nx-pb-input tx-range-prev txPrevi" type="text" />
            <input type="range" min="0" max="120" step="1" value="16" class="txRange tx-range-slider">
           
		</p>
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php esc_html_e('Heading Text Alignment', 'tx');?>:</label>

            <select id="<?php echo esc_attr($this->get_field_id( 'align' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'align' )); ?>" value="<?php echo esc_attr($instance['align']); ?>" class="nx-widselect nx-pb-input">
              <option value="left"><?php esc_html_e('Left', 'tx');?></option>
              <option value="right"><?php esc_html_e('Right', 'tx');?></option>
            </select>            
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
			$instance['style'] = strip_tags( $new_instance['style'] );
			$instance['heading_text'] = strip_tags( $new_instance['heading_text'] );
			$instance['tag'] = strip_tags( $new_instance['tag'] );
			$instance['size'] = strip_tags( $new_instance['size'] );
			$instance['margin'] = strip_tags( $new_instance['margin'] );
			$instance['align'] = strip_tags( $new_instance['align'] );	
			return $instance;
		}
		
		function widget($args, $instance) {
			
			extract( $args );
	
			$style = esc_attr($instance['style']);
			$heading_text = esc_attr($instance['heading_text']);
			$tag = esc_attr($instance['tag']);
			$size = esc_attr($instance['size']);
			$margin = esc_attr($instance['margin']);
			$align = esc_attr($instance['align']);	
	
			$output = '';
			
			$output .= '<div>[tx_heading style="'.$style.'" heading_text="'.$heading_text.'" tag="'.$tag.'" size="'.$size.'" margin="'.$margin.'" align="'.$align.'"]</div>';
			
			echo $output;
	
		}
			
	}
	
	add_action( 'widgets_init', 'nx_load_heading_widget' );
	
	function nx_load_heading_widget() {
		register_widget('nx_heading_widget');
	}
