<?php
	/*
	Widget Name: NX Services For Page Builder
	Description: NX Services Widget For Page Builder.
	Author: templatesNext
	Author URI:Author URI: http://www.TemplatesNext.org
	*/	

	class nx_services_widget extends WP_Widget {
		
		//function nx_services_widget() {
		function __construct() {	
			$widget_ops = array( 
				'classname' => 'widget-nx-services', 
				'description' => 'Services widget for Page Builder', 
				'panels_icon' => 'dashicons dashicons-screenoptions',
				'panels_groups' => array('tx')
			);
        	parent::__construct( 'widget-nx-services', 'TX Services ( for PB )', $widget_ops );				
		}
	
		function form($instance) {
		$defaults = array( 
			'style' => 'default', 
			'title' => 'Service Title', 
			'icon' => 'fa-star', 
			'content' => 'Services content...', 
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		//[tx_services style="curved" title="Services Title" icon="fa-star"]Services content[/tx_services]
	
	?>

	<div class="nx-widget-content">
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Service Style', 'tx');?>:</label>

            <select id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>" value="<?php echo $instance['style']; ?>" class="nx-widselect nx-pb-input">
                <option value="default"><?php _e('Default (Circle)', 'tx');?></option>					
                <option value="curved"><?php _e('Curved Corner', 'tx');?></option>					
                <option value="square"><?php _e('Square', 'tx');?></option>	
            </select>            
		</p>    
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Service Title', 'tx');?>:</label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="nx-widenumber nx-pb-input" type="text" />
		</p> 
		<div class="nx-icon-para">
			<label class="nx-pb-lebel"><?php _e('Service Icon', 'tx');?>:</label>
            <div class="awedrop" id="awedrop_<?php echo $this->get_field_name( 'icon' ); ?>">
			</div>            
            <input id="<?php echo $this->get_field_id( 'icon' ); ?>" name="<?php echo $this->get_field_name( 'icon' ); ?>" value="<?php echo $instance['icon']; ?>" class="nx-widerange nx-pb-input nx-service-icon" type="text" />
		</div>
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Service Content', 'tx');?>:</label>

            <textarea id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>" value="<?php echo $instance['content']; ?>" class="nx-widselect nx-pb-input"><?php echo $instance['content']; ?></textarea>
		</p> 
	</div>
		<script>
       		document.getElementById("awedrop_<?php echo $this->get_field_name( 'icon' ); ?>").innerHTML = tx_font_awesome_include('tx-fa-icons');
			
			jQuery(document).ready(function ($) {
				//$( ".nx-widget-content" ).each(function( index ) {
				//});
				$('.nx-widget-content').on('click', '.tx-fa-icons .fa', function() {
					$('.tx-fa-icons .active').removeClass('active');
					$(this).addClass('active');
					var tx_icon = jQuery(this).data('value');
					$('.nx-service-icon').val(tx_icon);
				});
			});					
        </script>
    
	<?php	
		}
	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['style'] = strip_tags( $new_instance['style'] );
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['icon'] = strip_tags( $new_instance['icon'] );
			$instance['content'] = strip_tags( $new_instance['content'] );
																		
			return $instance;
		}
		
		function widget($args, $instance) {
			
			extract( $args );
	
			$style = esc_attr($instance['style']);
			$title = esc_attr($instance['title']);
			$icon = esc_attr($instance['icon']);
			$content = esc_html($instance['content']);
	
			$output = '';
			
			$output .= '<div>[tx_services style="'.$style.'" title="'.$title.'" icon="'.$icon.'"]'.$content.'[/tx_services]</div>';
			
			echo $output;
	
		}
			
	}
	
	add_action( 'widgets_init', 'nx_load_services_widget' );
	
	function nx_load_services_widget() {
		register_widget('nx_services_widget');
	}

