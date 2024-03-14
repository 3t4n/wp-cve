<?php

/*
	Price WIDGET
*/

class Directorypress_Widget_Price extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'directorypress_widget_price', 'description' => 'DirectoryPress Price Widget' );
		WP_Widget::__construct( 'directorypress_price', 'directorypress-price', $widget_ops );


	}

	function widget( $args, $instance ) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		extract( $args );
		
		
		$title = $instance['title'];
		$style = isset( $instance['style'] ) ? $instance['style'] : '';
		global $post,$authordata;
		if(has_shortcode($post->post_content, 'directorypress-listing')){ 
			$listing = $GLOBALS['listing_id'];
		}else{
			$listing = '';
		}
		global $wpdb;
		$price_field_id = '';
		$output = '';
		global $post;
		
		echo wp_kses_post($before_widget);
		if ( $title ){
			echo wp_kses_post($before_title . $title . $after_title);
		}
		if(class_exists('DirectoryPress') && has_shortcode($post->post_content, 'directorypress-listing')){
			$field_ids = $wpdb->get_results('SELECT id, type, slug FROM '.$wpdb->prefix.'directorypress_fields');
			foreach( $field_ids as $field_id ) {
				$singlefield_id = $field_id->id;
				if($field_id->type == 'price' && ($field_id->slug == 'price' || $field_id->slug == 'Price') ){			
					$price_field_id = $singlefield_id;
				}				
			}
			
			if(!empty($price_field_id)){
				 if($style == 1 && isset($listing->fields[$price_field_id])){
					echo  '<div class="directorypress-price-style1 clearfix">';
						 wp_kses_post($listing->display_content_field($price_field_id));
						
					echo '</div>';
				}elseif($style == 2 && isset($listing->fields[$price_field_id])){
					echo '<div class="directorypress-price-style2-range clearfix">';
						echo '<span class="price-range-string">'. wp_kses_post($listing->fields[$price_field_id]->range_options_out($listing)) .'</span>';
						echo '<span class="price-range-value">'. wp_kses_post($listing->fields[$price_field_id]->renderValueOutput($listing)) .'</span>';
					echo '</div>';
					
				}elseif($style == 3 && isset($listing->fields[$price_field_id])){
					echo  '<div class="directorypress-price-style3 clearfix">';
						wp_kses_post($listing->display_content_field($price_field_id));
						
					echo '</div>';
				}else{
					
				}
			}
		}
		echo wp_kses_post($after_widget);
	}


	function update( $new_instance, $old_instance ) {
		//$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['style'] = $new_instance['style'];
		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$style = isset( $instance['style'] ) ? $instance['style'] : '1';
?>
		<p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>">Title:</label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		
		 <p>
    			<label for="<?php echo esc_attr($this->get_field_id( 'style' )); ?>"><?php esc_html_e('Style:', 'DIRECTORYPRESS'); ?></label>
    			<select name="<?php echo esc_attr($this->get_field_name( 'style' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'style' )); ?>" class="widefat">
    				<option value="1"<?php selected( $style, '1');?>>One</option>
					<option value="2"<?php selected( $style, '2');?>>Two - Range</option>
					<option value="3"<?php selected( $style, '3');?>>Three</option>
    			</select>
  		  </p>
		
		
<?php

	}
}

/***************************************************/
