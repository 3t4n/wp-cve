<?php
/**
 * Flickr Widget Class
 */
if ( !defined('ABSPATH')) exit;

class Themeidol_Widget_Flickr extends WP_Widget {
	
	function __construct() {
		$args = array('classname' => 'themeidol-flickr', 'description' => __('Displays a stream of photos from Flickr.', 'themeidol-all-widget'));
		parent::__construct('themeidol-flickr', __('Themeidol - Flickr Stream', 'themeidol-all-widget'), $args);
		// Register site styles and scripts
    	add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
    	// Refreshing the widget's cached output with each new post
	    add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
	    add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
	    add_action( 'delete_attachment', array( $this, 'flush_group_cache' ) );
	    add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) ); 
	}

	
	function widget($args, $instance){
		$cache    = (array) wp_cache_get( 'themeidol-flickr', 'widget' );

        if(!is_array($cache)) $cache = array();
      
        if(isset($cache[$args['widget_id']])){
            echo $cache[$args['widget_id']];
            return;
         }
      	ob_start();
		extract($args);
		$title = apply_filters('widget_title', esc_attr($instance['title']));
		$user_id = esc_attr($instance['user_id']);
		$number = esc_attr($instance['number']);
		if(!is_numeric($number)) $number = 5; elseif($number < 1) $number = 1; elseif($number > 20) $number = 20;
		$flickr_query = 'display=latest&size=s&layout=x&source=user&user='.$user_id.'&count='.$number;
		if (strpos($before_widget, 'widget ') !== false) {
            $before_widget = preg_replace('/widget /', "idol-widget ", $before_widget, 1);
        }
		
		echo $before_widget;
		if($title != '') echo $before_title.$title.$after_title; ?>
		<div class="widget-content">
			<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?<?php echo $flickr_query; ?>"></script>
		</div>
		<?php echo $after_widget;
		$widget_string = ob_get_flush();
		$cache[$args['widget_id']] = $widget_string;
		wp_cache_add('themeidol-flickr', $cache, 'widget');
	}
	public function flush_widget_cache() {
    		wp_cache_delete( 'themeidol-flickr', 'widget' );
  	}

	/**
   	* Registers and enqueues widget-specific styles.
   	*/
	  public function register_widget_styles() {
	    wp_enqueue_style( 'themeidol-flickr', THEMEIDOL_WIDGET_CSS_URL.'flckr-style.css');
	  } // end register_widget_styles

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['user_id'] = strip_tags($new_instance['user_id']);
		$instance['number'] = intval($new_instance['number']);
		return $instance;
	}


	function form($instance) {
		$instance = wp_parse_args((array) $instance, array('title' => '', 'user_id' => ''));
		if(!isset($instance['number']) || !$number = (int)$instance['number']) $number = 9;
		$title = esc_attr($instance['title']);
		$user_id = esc_attr($instance['user_id']); ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'themeidol-all-widget'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('user_id'); ?>"><?php _e('User ID', 'themeidol-all-widget'); ?></label>
			<input type="text" value="<?php echo $user_id; ?>" name="<?php echo $this->get_field_name('user_id'); ?>" id="<?php echo $this->get_field_id('user_id'); ?>" class="widefat" /><br />
			</small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of Photos', 'themeidol-all-widget'); ?></label><br/>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>		
	<?php }
} 
add_action( 'widgets_init', create_function( '', 'return register_widget("Themeidol_Widget_Flickr");' ) );