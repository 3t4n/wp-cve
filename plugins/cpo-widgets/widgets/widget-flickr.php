<?php

class Ctwg_Widget_Flickr extends WP_Widget {
	
	function __construct() {
		$args = array('classname' => 'ctwg-flickr', 'description' => __('Displays a stream of photos from Flickr.', 'ctwg'));
		parent::__construct('ctwg-flickr', __('CPO - Flickr Stream', 'ctwg'), $args);
	}

	
	function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$user_id = $instance['user_id'];
		$number = $instance['number'];
		if(!is_numeric($number)) $number = 5; elseif($number < 1) $number = 1; elseif($number > 20) $number = 20;
		$flickr_query = 'display=latest&size=s&layout=x&source=user&user='.$user_id.'&count='.$number;
		
		echo $before_widget;
		if($title != '') echo $before_title.$title.$after_title; ?>
		<div class="widget-content">
			<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?<?php echo $flickr_query; ?>"></script>
		</div>
		<?php echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['user_id'] = strip_tags($new_instance['user_id']);
		$instance['number'] = (int)$new_instance['number'];
		return $instance;
	}


	function form($instance) {
		$instance = wp_parse_args((array) $instance, array('title' => '', 'user_id' => ''));
		if(!isset($instance['number']) || !$number = (int)$instance['number']) $number = 9;
		$title = esc_attr($instance['title']);
		$user_id = esc_attr($instance['user_id']); ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'ctwg'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('user_id'); ?>"><?php _e('User ID', 'ctwg'); ?></label>
			<input type="text" value="<?php echo $user_id; ?>" name="<?php echo $this->get_field_name('user_id'); ?>" id="<?php echo $this->get_field_id('user_id'); ?>" class="widefat" /><br />
			</small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of Photos', 'ctwg'); ?></label><br/>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>		
	<?php }
} 

add_action('widgets_init', 'ctwg_widget_flickr');
function ctwg_widget_flickr() {
	register_widget('Ctwg_Widget_Flickr');
}