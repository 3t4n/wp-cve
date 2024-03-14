<?php
/**
 * Stylist Popular Post Widget Class
 */
if ( !defined('ABSPATH')) exit;

class Themeidol_stylish_popular_posts extends WP_Widget {

	/**
	 * Setup the widget
	 */
	public function __construct() {
		add_action('wp_enqueue_scripts', array(&$this, 'register_scripts'));


		parent::__construct(
			'themeidol_stylish_popular_posts',
			__('Themeidol-Stylish popular posts', 'themeidol-all-widget'),
			array( 'description' => __( 'Displays most popular posts based on the number of comments', 'themeidol-all-widget' ), )
		);
		// Refreshing the widget's cached output with each new post
	    add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
	    add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
	    add_action( 'delete_attachment', array( $this, 'flush_group_cache' ) );
	    add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );     

	}
	function register_scripts()
	{
		wp_register_style('stylish_popular_posts_style', THEMEIDOL_WIDGET_CSS_URL.'stylist-post-style.css');
       
	}

	public function flush_widget_cache() {
    		wp_cache_delete( 'themeidol-stylistpost', 'widget' );
  	}


	/**
	 * Display the widget
	 */
	function widget( $args, $instance ) {
		 $cache    = (array) wp_cache_get( 'themeidol-stylistpost', 'widget' );

         if(!is_array($cache)) $cache = array();
      
         if(isset($cache[$args['widget_id']])){
            echo $cache[$args['widget_id']];
            return;
         }
      	ob_start();
		extract( $args );
		wp_enqueue_style('stylish_popular_posts_style');
		/* Variables from the widget settings. */
		$title = apply_filters('widget_title', esc_attr($instance['title']) );
		$number = esc_attr($instance['number']);
		$checkbox = esc_attr($instance['checkbox']);
		$popularity=esc_attr($instance['popularity']);
		if($popularity=='visited')
		{
		$top_popular_posts = new WP_Query( array('ignore_sticky_posts' => 1, 'posts_per_page' => $number, 'post_status' => 'publish', 'orderby' => 'meta_value_num', 'meta_key' => '_themeidol_view_count', 'order' => 'desc', 'paged' => 1));
		//$top_popular_posts = new WP_Query('showposts=' . $number . '&meta_key=_themeidol_view_count&orderby=meta_value_num&order=DESC');
		} else 
		{
			echo "not";
		$top_popular_posts = new WP_Query( array('ignore_sticky_posts' => 1, 'posts_per_page' => $number, 'post_status' => 'publish', 'orderby' => 'comment_count', 'order' => 'desc', 'paged' => 1));
		//$top_popular_posts = new WP_Query('showposts=' . $number . '&orderby=comment_count&order=DESC');
		}
		if ($top_popular_posts->have_posts()) :
		if (strpos($before_widget, 'widget ') !== false) {
            	$before_widget = preg_replace('/widget /', "idol-widget ", $before_widget, 1);
        }
		
		echo $before_widget;

		/* Display the widget title. */
		if ( $title )
			echo $before_title . $title . $after_title;
		?>
			<?php
				
			?>
			<div class="stylish-popular-item-wrapper">
			<?php  while ($top_popular_posts->have_posts()) : $top_popular_posts->the_post(); ?>
				
				
				<div class="stylish-popular-item dark  stylish-popular-item-has-excerpt">
					<a class="stylish-popular-item-image" href="<?php echo get_permalink() ?>" rel="bookmark">
						<div class="stylish-popular-item-overlay stylish-popular-primary-color-bg"></div>
					
					
						<h3 class="stylish-popular-item-title"><?php the_title(); ?></h3>
						<div class="stylish-popular-item-description">
							
							<span class="date"><?php _e('Posted on ', 'themeidol-all-widget'); ?><?php the_time( get_option('date_format') ); ?>
							</span>
						</div>
						
					<?php //the_post_thumbnail('popular_posts_img'); ?>
						<?php if(has_post_thumbnail()): ?>	
	    					<?php the_post_thumbnail('wp_review_'.'popular_posts_img', array('title' => '','class'=>'attachment-portfolio size-portfolio wp-post-image')); ?>		
	    				<?php else: ?>							
	    					<img src="<?php echo THEMEIDOL_WIDGET_IMAGES_URL.'largethumb.png'; ?>" alt="<?php the_title(); ?>"  class="attachment-portfolio size-portfolio wp-post-image" />					
	    				<?php endif; ?>
					</a>
					<?php if( $checkbox == '1' ) {?>
					<?php echo comments_number('<span class="popular-number">0</span>', '<span class="popular-number">1</span>', '<span class="popular-number">%</span> '.__('','themeidol-all-widget'));?>				
					<?php } ?>
				</div>
			<?php endwhile; ?>
			</div>
			<?php wp_reset_query(); ?>
			<?php endif; ?>
		<?php
		echo $after_widget;
		$widget_string = ob_get_flush();
		$cache[$args['widget_id']] = $widget_string;
		wp_cache_add('themeidol-stylistpost', $cache, 'widget');

	}
	/**
	 * Update the widget settings
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags( $new_instance['number'] );
		$instance['checkbox'] = strip_tags($new_instance['checkbox']);
		$instance['popularity']=strip_tags($new_instance['popularity']);
		return $instance;
	}
	
	function form( $instance ) {
		/* Set up default widget settings. */
		// Check values
		if( $instance) {
			$title = esc_attr($instance['title']);
			$number = esc_attr($instance['number']);
			$popularity=esc_attr($instance['popularity']);
			$checkbox = esc_attr($instance['checkbox']);
		} else {
			$title = 'Popular Posts';
			$number = '3';
			$popularity='visited';
			$checkbox = '1';
		}?>
		<!-- widget title -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'themeidol-all-widget'); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>"  />
		</p>
		<!-- number of posts to show -->
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Number of posts to show:', 'themeidol-all-widget'); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $number; ?>" size="3" />
		</p>
			<p class="stylist_popularity">
				<label for="<?php echo $this->get_field_id('popularity'); ?>"><?php _e('Popularity By:', 'themeidol-all-widget'); ?></label> 
				<select id="<?php echo $this->get_field_id('popularity'); ?>" name="<?php echo $this->get_field_name('popularity'); ?>" style="margin-left: 12px;">
					<option value="visited" <?php selected($popularity, 'visited', true); ?>><?php _e('Most Visited', 'themeidol-all-widget'); ?></option>  
					<option value="comment" <?php selected($popularity, 'comment', true); ?>><?php _e('Most Commented', 'themeidol-all-widget'); ?></option>
				</select>       
			</p>
		<p>
			<label for="<?php echo $this->get_field_id('checkbox'); ?>"><?php _e('Display Comments number', 'themeidol-all-widget'); ?></label>
			<input id="<?php echo $this->get_field_id('checkbox'); ?>" name="<?php echo $this->get_field_name('checkbox'); ?>" type="checkbox" value="1" <?php checked( '1', $checkbox ); ?> />
		</p>
	<?php
	}
}

add_action( 'widgets_init', create_function( '', 'return register_widget("Themeidol_stylish_popular_posts");' ) );