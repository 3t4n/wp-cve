<?php

class Ctwg_Widget_RecentPosts extends WP_Widget{
	
	function __construct(){
		$args = array('classname' => 'ctwg-recent', 'description' => __('Displays the most recent posts with date and thumbnail.', 'ctwg'));
		parent::__construct('ctwg-recent-posts', __('CPO - Recent Posts', 'ctwg'), $args);
		$this->alt_option_name = 'ctwg-recent-posts';
		add_action('save_post', array(&$this, 'flush_widget_cache'));
		add_action('deleted_post', array(&$this, 'flush_widget_cache'));
		add_action('switch_theme', array(&$this, 'flush_widget_cache'));
	}

	function widget($args, $instance){
		$cache = wp_cache_get('ctwg-recent-posts', 'widget');
		if(!is_array($cache)) $cache = array();
		
		if(isset($cache[$args['widget_id']])){
			echo $cache[$args['widget_id']];
			return;
		}
		ob_start();
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$type = $instance['type'];
		if($type == '') $type = 'post';
		$number = $instance['number'];
		if(!is_numeric($number)) $number = 5; elseif($number < 1) $number = 1; elseif($number > 99) $number = 99;
		
		$recent_posts = new WP_Query(array('post_type' => $type, 'posts_per_page' => $number, 'ignore_sticky_posts' => 1));
		if($recent_posts->have_posts()):
		echo $before_widget;
		if($title != '') echo $before_title.$title.$after_title; ?>
		
		<div class="ctwg-recent" id="<?php echo $widget_id; ?>">
			<?php while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
			<div class="ctwg-recent-item<?php if(has_post_thumbnail()) echo ' ctwg-has-thumbnail'; ?>">
				<?php if(has_post_thumbnail()): ?>
				<a class="ctwg-recent-image" href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail('thumbnail', array('title' => '')); ?>
				</a>
				<?php endif; ?>
				<div class="ctwg-recent-body">
					<div class="ctwg-recent-title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</div>
					<div class="ctwg-recent-meta"><?php the_time(get_option('date_format')); ?></div>
				</div>
			</div>
			<?php endwhile; ?>
		</div>
		<?php echo $after_widget;
		wp_reset_postdata();
		endif;
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_add('ctwg-recent-posts', $cache, 'widget');
	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['type'] = strip_tags($new_instance['type']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();
		$alloptions = wp_cache_get('alloptions', 'options');
		if(isset($alloptions['ctwg-recent-posts']))
		delete_option('ctwg-recent-posts');
		return $instance;
	}

	function flush_widget_cache(){
		wp_cache_delete('cpotheme-recent-posts', 'widget');
	}

	function form($instance){
		$instance = wp_parse_args((array) $instance, array('title' => ''));
		$title = esc_attr($instance['title']);
		$type = isset($instance['type']) ? esc_attr($instance['type']) : 'post';
		if(!isset($instance['number']) || !$number = (int)$instance['number']) $number = 5; ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'ctwg'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Post Type', 'ctwg'); ?></label><br/>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('type'); ?>" type="text" value="<?php echo $type; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of Posts', 'ctwg'); ?></label><br/>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>
	<?php }
}

add_action('widgets_init', 'ctwg_widget_recent');
function ctwg_widget_recent() {
	register_widget('Ctwg_Widget_RecentPosts');
}