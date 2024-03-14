<?php
	/*
	Widget Name: NX Products For Page Builder
	Description: NX WooCommerce Products Scroll Widget For Page Builder.
	Author: templatesNext
	Author URI:Author URI: http://www.TemplatesNext.org
	*/	

	class nx_prodscroll_widget extends WP_Widget {
		
		//function nx_prodscroll_widget() {
		function __construct() {	
			$widget_ops = array( 
				'classname' => 'widget-nx-prodscroll', 
				'description' => 'Product Carousel widget for pagebuilder',
				'panels_icon' => 'dashicons dashicons-screenoptions',
				'panels_groups' => array('tx')				
			);
        	parent::__construct( 'widget-nx-prodscroll', 'TX WooCommerce Products Carousel ( for PB )', $widget_ops );				
		}
	
		function form($instance) {
		$defaults = array( 
			'type' => 'recent_products', 
			'items' => '8', 
			'columns' => '4', 
			'ids' => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		//[tx_prodscroll type="product_categories" ids="" columns="4" items="8"]
	?>
	<div class="nx-widget-content">		
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Portfolio Style', 'tx');?>:</label>

            <select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" value="<?php echo $instance['type']; ?>" class="nx-widselect nx-pb-input">
            
                <option value="recent_products"><?php _e('Recent Products', 'tx');?></option>	
                <option value="product_categories"><?php _e('Product Categories', 'tx');?></option>					
                <option value="featured_products"><?php _e('Featured Products', 'tx');?></option>					
                <option value="sale_products"><?php _e('Products On Sale', 'tx');?></option>					
                <option value="best_selling_products"><?php _e('Best Selling Products', 'tx');?></option>					
                <option value="top_rated_products"><?php _e('Top Rated Products', 'tx');?></option>					
                <option value="products"><?php _e('Products By Ids', 'tx');?></option>
                  
            </select>            
		</p>
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
			<label class="nx-pb-lebel"><?php _e('Category/Product Ids (optional)', 'tx');?>:</label>
            <input id="<?php echo $this->get_field_id( 'ids' ); ?>" name="<?php echo $this->get_field_name( 'ids' ); ?>" value="<?php echo $instance['ids']; ?>" class="nx-widselect nx-pb-input" type="text" />
            <br /><span class="small"><?php _e('Comma separeted category or product ids (works with "Product Categories" and "Products By Ids")', 'tx');?></span>
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
			$instance['type'] = strip_tags( $new_instance['type'] );
			$instance['items'] = strip_tags( $new_instance['items'] );
			$instance['columns'] = strip_tags( $new_instance['columns'] );
			$instance['ids'] = strip_tags( $new_instance['ids'] );
			return $instance;
		}
		
		function widget($args, $instance) {
			
			extract( $args );
	
			$type = esc_attr($instance['type']);
			$items = esc_attr($instance['items']);
			$columns = esc_attr($instance['columns']);
			$ids = esc_html($instance['ids']);
	
			$output = '';
			
			$output .= '<div>[tx_prodscroll type="'.$type.'" items="'.$items.'" columns="'.$columns.'" ids="'.$ids.'"]</div>';
			
			echo $output;
	
		}
			
	}
	
	add_action( 'widgets_init', 'nx_load_prodscroll_widget' );
	
	function nx_load_prodscroll_widget() {
		register_widget('nx_prodscroll_widget');
	}
