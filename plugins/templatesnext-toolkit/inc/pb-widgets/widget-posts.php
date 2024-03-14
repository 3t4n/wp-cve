<?php
	/*
	Widget Name: NX Posts/Blog For Page Builder
	Description: NX Posts/Blog Widget For Page Builder.
	Author: templatesNext
	Author URI:Author URI: http://www.TemplatesNext.org
	*/	

	class nx_posts_widget extends WP_Widget {
		
		//function nx_posts_widget() {
		function __construct() {	
			$widget_ops = array( 
				'classname' => 'widget-nx-posts', 
				'description' => 'Posts/Blog widget for Page Builder', 
				'panels_icon' => 'dashicons dashicons-screenoptions',
				'panels_groups' => array('tx')
			);
        	parent::__construct( 'widget-nx-posts', 'TX Posts/Blog ( for PB )', $widget_ops );				
		}
	
		function form($instance) {
		$defaults = array( 
			'style' => 'default', 
			'items' => '8', 
			'columns' => '4', 
			'showcat' => 'show', 
			'hide_excerpt' => 'no', 
			'show_pagination' => 'no', 
			'carousel' => 'no',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
	
	?>

	<div class="nx-widget-content">
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Number Of Items', 'tx');?>:</label>
            <input id="<?php echo $this->get_field_id( 'items' ); ?>" name="<?php echo $this->get_field_name( 'items' ); ?>" value="<?php echo $instance['items']; ?>" class="nx-pb-input tx-range-prev txPrevi" type="text" />
            <input type="range" min="1" max="16" step="1" value="8" class="txRange tx-range-slider">            
		</p> 
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Number Of Columns', 'tx');?>:</label>
            <input id="<?php echo $this->get_field_id( 'columns' ); ?>" name="<?php echo $this->get_field_name( 'columns' ); ?>" value="<?php echo $instance['columns']; ?>" class="nx-pb-input tx-range-prev txPrevi" type="text" />
            <input type="range" min="1" max="4" step="1" value="4" class="txRange tx-range-slider">            
		</p>
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Show/Hide Category', 'tx');?>:</label>

            <select id="<?php echo $this->get_field_id( 'showcat' ); ?>" name="<?php echo $this->get_field_name( 'showcat' ); ?>" value="<?php echo $instance['showcat']; ?>" class="nx-widselect nx-pb-input">
              <option value="show"><?php _e('Show', 'tx');?></option>
              <option value="hide"><?php _e('Hide', 'tx');?></option>
            </select>            
		</p> 
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Show Pagination', 'tx');?>:</label>

            <select id="<?php echo $this->get_field_id( 'show_pagination' ); ?>" name="<?php echo $this->get_field_name( 'show_pagination' ); ?>" value="<?php echo $instance['show_pagination']; ?>" class="nx-widselect nx-pb-input">
              <option value="no"><?php _e('No', 'tx');?></option>
              <option value="yes"><?php _e('Yes', 'tx');?></option>
            </select>            
		</p>
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Show As Carousel', 'tx');?>:</label>

            <select id="<?php echo $this->get_field_id( 'carousel' ); ?>" name="<?php echo $this->get_field_name( 'carousel' ); ?>" value="<?php echo $instance['carousel']; ?>" class="nx-widselect nx-pb-input">
              <option value="no"><?php _e('No', 'tx');?></option>
              <option value="yes"><?php _e('Yes', 'tx');?></option>
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
			//$instance['style'] = strip_tags( $new_instance['style'] );
			$instance['items'] = strip_tags( $new_instance['items'] );
			$instance['columns'] = strip_tags( $new_instance['columns'] );
			$instance['showcat'] = strip_tags( $new_instance['showcat'] );
			//$instance['hide_excerpt'] = strip_tags( $new_instance['hide_excerpt'] );
			$instance['show_pagination'] = strip_tags( $new_instance['show_pagination'] );	
			$instance['carousel'] = strip_tags( $new_instance['carousel'] );																		
			return $instance;
		}
		
		function widget($args, $instance) {
			
			extract( $args );
	
			//$style = $instance['style'];
			$items = esc_attr($instance['items']);
			$columns = esc_attr($instance['columns']);
			$showcat = esc_attr($instance['showcat']);
			//$hide_excerpt = $instance['hide_excerpt'];
			$show_pagination = esc_attr($instance['show_pagination']);	
			$carousel = esc_attr($instance['carousel']);
	
			$output = '';
			
			$output .= '<div>[tx_blog items="'.$items.'" columns="'.$columns.'" showcat="'.$showcat.'" show_pagination="'.$show_pagination.'" carousel="'.$carousel.'"]</div>';
			
			echo $output;
	
		}
			
	}
	
	add_action( 'widgets_init', 'nx_load_posts_widget' );
	
	function nx_load_posts_widget() {
		register_widget('nx_posts_widget');
	}

