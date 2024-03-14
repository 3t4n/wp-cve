<?php
if ( !defined('ABSPATH')) exit;

class gabfire_simplead extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'gabfire_simplead_widget', 'description' => __('Gabfire Widget: Simple Banner', 'gabfire'));
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'gabfire_simplead_widget' );
		parent::__construct( 'gabfire_simplead_widget', __('Gabfire Widget: Simple Banner', 'gabfire'), $widget_ops, $control_ops);
	}	
	
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$link	= $instance['link'];
		$banner	= $instance['banner'];
		$textcode	= $instance['textcode'];
		echo $before_widget;

			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			
			if ($textcode == '' ) {
				
				if ($link !== '') { echo '<a href="'. esc_url( $link ) .'">'; }
					echo '<img src="'. esc_attr( $banner ) . '" alt="'. esc_attr($title) .'" />';
				if ($link !== '') { echo '</a>'; }
				
			} else {
				
				echo $textcode;
				
			}
			
		echo "<div class='clear'></div>$after_widget"; 
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['link'] = ( ! empty( $new_instance['link'] ) ) ? sanitize_text_field( $new_instance['link'] ) : '';
		$instance['banner'] = ( ! empty( $new_instance['banner'] ) ) ? sanitize_text_field( $new_instance['banner'] ) : '';
		$instance['textcode'] = ( ! empty( $new_instance['textcode'] ) ) ? $new_instance['textcode'] : '';
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' => '',
			'link' => '',
			'banner' => '',
			'textcode' => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		?>
				
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'gabfire'); ?></label>
			<input class="widefat"  id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
				
		<p><?php _e('You may use a banner by defining URL to image file below or paste a code directly into adcode field.','gabfire-widget-pack'); ?></p>				
			
		<div class="gabfire_gray">
			<p>
				<label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link', 'gabfire'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo esc_attr($instance['link']); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('banner'); ?>"><?php _e('Banner Image', 'gabfire'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('banner'); ?>" name="<?php echo $this->get_field_name('banner'); ?>" type="text" value="<?php echo esc_attr($instance['banner']); ?>" />
			</p>
		</div>
		
		<div class="gabfire_gray">
			<p>
				<label for="<?php echo $this->get_field_id('textcode'); ?>"><?php _e('Ad Code','gabfire-widget-pack'); ?></label>
				<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('textcode'); ?>" name="<?php echo $this->get_field_name('textcode'); ?>"><?php echo esc_attr($instance['textcode']); ?></textarea>
			</p>		
		</div>

	<?php
	}
}
function register_simplead() {
	register_widget('gabfire_simplead');
}

add_action('widgets_init', 'register_simplead'); 