<?php
/*
Plugin Name: Children Pages
Description: Displays children pages of the current top-parent in a sidebar widget.
Version: 1.0
Author: Johan Str&ouml;m
Author URI: http://www.swedishboy.se/wordpress

*/

if(!class_exists('children_pages')) {

class children_pages extends WP_Widget {

	function children_pages() {
		parent::WP_Widget(false, $name = 'Children Pages');		
	}


	function widget($args, $instance) {
		
		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);
				
		if(is_page()) {

			global $post;
			// top-parent or current page?
			$id = isset($post->ancestors[0]) ? $post->ancestors[0] : $post->ID;

			// get children pages
			$pages = wp_list_pages('depth=0&title_li=&child_of='.$id.'&echo=0');

			// did we get pages? ok then...
			if(!empty($pages)) {
				echo $before_widget;
				echo $before_title.get_the_title($id).$after_title;
				echo '<ul class="children_pages_list">'.$pages.'</ul>';
				echo $after_widget;
			}
		}
	}

	function form($instance) {
		// Get our options and see if we're handling a form submission.

		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.
		_e("This plugins has no options. It's automatic");
	}


}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', create_function('','return register_widget("children_pages");'));
}
?>