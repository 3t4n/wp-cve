<?php

class Ctwg_Widget_Author extends WP_Widget{
	
	function __construct(){
		$args = array('classname' => 'ctwg-author', 'description' => __('Displays an author badge for a specific user.', 'ctwg'));
		parent::__construct('ctwg-author', __('CPO - Author Badge', 'ctwg'), $args);
	}

	function widget($args, $instance){
		extract($args);
		$widget_id = str_replace('-', '_', $widget_id);
		$title = apply_filters('widget_title', $instance['title']);
		$userid = intval($instance['user']);
		$description = esc_attr($instance['description']);
		$userdata = get_userdata($userid);			
		$size = intval($instance['size']);
		if($size == 0) $size = 100; 
		
		$output = '';
		$output .= '<div class="ctwg-author">';
		$output .= '<div class="ctwg-author-image">'.get_avatar($userdata->user_email, $size).'</div>';
		$output .= '<div class="ctwg-author-body">';
		$output .= '<h4 class="ctwg-author-name"><a href="'.get_author_posts_url($userid).'">'.get_the_author().'</a></h4>';
		if($description != ''){
			$output .= '<div class="ctwg-author-description">'.$description.'</div>';
		}
		$output .= '<div class="ctwg-author-content">'.get_the_author_meta('description', $userid).'</div>';
		$output .= '</div>';
		$output .= '</div>';
		
		echo $before_widget;
		if($title != '') 
			echo $before_title.$title.$after_title;
		echo $output;
		echo $after_widget;
	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['user'] = (int)$new_instance['user'];
		$instance['size'] = (int)$new_instance['size'];
		$instance['description'] = strip_tags($new_instance['description']);
		return $instance;
	}

	function form($instance){
		$instance = wp_parse_args((array) $instance, array('title' => '', 'user' => '', 'description' => '', 'size' => 100));
		$title = esc_attr($instance['title']);
		$user = esc_attr($instance['user']);
		$description = esc_attr($instance['description']);
		$size = intval($instance['size']);
		$user_list = get_users('orderby=nicename'); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'ctwg'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('user'); ?>"><?php _e('User', 'ctwg'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>">
				<?php foreach($user_list as $current_user): ?>
				<option value="<?php echo esc_attr($current_user->ID); ?>" <?php if($user == $current_user->ID) echo 'selected'; ?>><?php echo esc_attr($current_user->user_nicename); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description', 'ctwg'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" type="text" value="<?php echo $description; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Avatar Size (px)', 'ctwg'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>" type="text" value="<?php echo $size; ?>" />
		</p>
	<?php 
	}
}

add_action('widgets_init', 'ctwg_widget_author');
function ctwg_widget_author() {
	register_widget('Ctwg_Widget_Author');
}