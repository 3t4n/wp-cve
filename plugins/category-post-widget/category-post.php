<?php
/*
Plugin Name: Category Post Widget
Description: Display specific category posts in the sidebar.
Version: 1.1
Author: Teja Amilineni
*/

// Start class category_posts_widget //

class category_posts_widget extends WP_Widget {

// Constructor //

	function category_posts_widget() {
		$widget_ops = array( 'classname' => 'category_posts_widget', 'description' => 'Display specific category posts in the sidebar' ); // Widget Settings
		$control_ops = array( 'id_base' => 'category_posts_widget' ); // Widget Control Settings
		$this->WP_Widget( 'category_posts_widget', 'Category Posts Widget', $widget_ops, $control_ops ); // Create the widget
	}

// Extract Args //

		function widget($args, $instance) {
			extract( $args );

			$title 		= apply_filters('widget_title', $instance['title']); // the widget title
			$cp_id		= $instance['cp_id']; // category id
			$postsnumber 		= $instance['postsnumber']; // number of posts to display
			// $rssid 		= $instance['rssid']; // rss feed link
			// $newsletterurl 	= $instance['newsletter_url'];  URL of newsletter signup
			// $authorcredit	= isset($instance['author_credit']) ? $instance['author_credit'] : false ; give plugin author credit


// Before widget //

			echo $before_widget;

	// Title of widget //

			if ( $title ) { echo $before_title . $title . $after_title; }

	// Widget output //

?>
		<?php $postquery = new WP_Query(array('posts_per_page' => $postsnumber, 'cat' => $cp_id, 'order' => 'ASC'));
			if ($postquery->have_posts()) {
			while ($postquery->have_posts()) : $postquery->the_post();
			$do_not_duplicate = $post->ID;
			?>
<ul>
	<li>
        					<a href="<?php echo get_permalink($post->ID);  ?>" > <?php the_title(); ?> </a> </li>
</ul>
			<?php endwhile; 			} ?>

<?php

	// After widget //

			echo $after_widget;
		} 


// Update Settings //

 		function update($new_instance, $old_instance) {
 			$instance['title'] = strip_tags($new_instance['title']);
 			$instance['cp_id'] = strip_tags($new_instance['cp_id']);
			$instance['postsnumber'] = strip_tags($new_instance['postsnumber']);
 			return $instance;
 		}



// Widget Control Panel //

 function form($instance) {

 $defaults = array( 'title' => 'Category Posts', 'cp_id' => 'Category ID' );
 		
	$instance = wp_parse_args( (array) $instance, $defaults ); ?>

 		<p>
 			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
 <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>'" type="text" value="<?php echo $instance['title']; ?>" />
 		</p>
 		<p>
 			<label for="<?php echo $this->get_field_id('cp_id'); ?>"><?php _e('Category ID'); ?></label>
 			<input class="widefat" id="<?php echo $this->get_field_id('cp_id'); ?>" name="<?php echo $this->get_field_name('cp_id'); ?>" type="text" value="<?php echo $instance['cp_id']; ?>" />
 		</p>
		<p>
 			<label for="<?php echo $this->get_field_id('postsnumber'); ?>"><?php _e('Number of posts to display'); ?></label>
 			<input class="widefat" id="<?php echo $this->get_field_id('postsnumber'); ?>" name="<?php echo $this->get_field_name('postsnumber'); ?>" type="text" value="<?php echo $instance['postsnumber']; ?>" />
 		</p>

        <?php }

}

// End class category_posts_widget

	add_action('widgets_init', create_function('', 'return register_widget("category_posts_widget");'));	

?>