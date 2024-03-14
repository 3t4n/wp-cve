<?php
/**
 * Recent Post Widget Class
 */
if ( !defined('ABSPATH')) exit;

class Themeidol_Widget_RecentPosts extends WP_Widget{
	
	function __construct(){
		$args = array('classname' => 'themeidol-recent', 'description' => __('Displays the most recent posts with date and thumbnail.', 'themeidol-all-widget'));
		parent::__construct('themeidol-recent-posts', __('Themeidol - Recent Posts', 'themeidol-all-widget'), $args);
		$this->alt_option_name = 'themeidol-recent-posts';
		// Register site styles and scripts
    	add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
		add_action('save_post', array(&$this, 'flush_widget_cache'));
		add_action('deleted_post', array(&$this, 'flush_widget_cache'));
		add_action('switch_theme', array(&$this, 'flush_widget_cache'));
	}

	function widget($args, $instance){
		$cache = wp_cache_get('themeidol-recent-posts', 'widget');
		if(!is_array($cache)) $cache = array();
		
		if(isset($cache[$args['widget_id']])){
			echo $cache[$args['widget_id']];
			return;
		}
		ob_start();
		extract($args);
		$title = apply_filters('widget_title', esc_attr($instance['title']));
		$type = esc_attr($instance['type']);
		if($type == '') $type = 'post';
		$number = esc_attr($instance['number']);
		if(!is_numeric($number)) $number = 5; elseif($number < 1) $number = 1; elseif($number > 99) $number = 99;
		
		$recent_posts = new WP_Query(array('post_type' => $type, 'posts_per_page' => $number, 'ignore_sticky_posts' => 1));
		if($recent_posts->have_posts()):
		$before_widget = str_replace('widget ', 'idol-widget ',  $before_widget);
		echo $before_widget;
		if($title != '') echo $before_title.$title.$after_title; ?>
		
		<div class="themeidol-recent" id="<?php echo $widget_id; ?>">
			<?php while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
			<div class="themeidol-recent-item<?php if(has_post_thumbnail()) echo ' themeidol-has-thumbnail'; ?>">
				<?php if(has_post_thumbnail()): ?>
				<a class="themeidol-recent-image" href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail('thumbnail', array('title' => '')); ?>
				</a>
				<?php endif; ?>
				<div class="themeidol-recent-body">
					<div class="themeidol-recent-title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</div>
					<div class="themeidol-recent-meta"><?php the_time(get_option('date_format')); ?></div>
				</div>
			</div>
			<?php endwhile; ?>
		</div>
		<?php echo $after_widget;
		wp_reset_postdata();
		endif;
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_add('themeidol-recent-posts', $cache, 'widget');
	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['type'] = strip_tags($new_instance['type']);
		$instance['number'] = intval($new_instance['number']);
		$this->flush_widget_cache();
		$alloptions = wp_cache_get('alloptions', 'options');
		if(isset($alloptions['themeidol-recent-posts']))
		delete_option('themeidol-recent-posts');
		return $instance;
	}

	function flush_widget_cache(){
		wp_cache_delete('cpotheme-recent-posts', 'widget');
	}
	/**
   	* Registers and enqueues widget-specific styles.
   	*/
	  public function register_widget_styles() {
	    wp_enqueue_style( 'themeidol-recentpost', THEMEIDOL_WIDGET_CSS_URL.'recentpost-style.css');
	  } // end register_widget_styles

	function form($instance){
		$instance = wp_parse_args((array) $instance, array('title' => ''));
		$title = esc_attr($instance['title']);
		$type = isset($instance['type']) ? esc_attr($instance['type']) : 'post';
		if(!isset($instance['number']) || !$number = intval($instance['number'])) $number = 5; ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'themeidol-all-widget'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Post Type', 'themeidol-all-widget'); ?></label><br/>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('type'); ?>" type="text" value="<?php echo $type; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of Posts', 'themeidol-all-widget'); ?></label><br/>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>
	<?php }
}
add_action( 'widgets_init', create_function( '', 'return register_widget("Themeidol_Widget_RecentPosts");' ) );