<?php
/**
 * Author Widget Class
 */
if ( !defined('ABSPATH')) exit;

class Themeidol_Widget_Author extends WP_Widget{
	
	function __construct(){
		$args = array('classname' => 'themeidol-author', 'description' => __('Displays an author badge for a specific user.', 'themeidol-all-widget'));
		parent::__construct('themeidol-author', __('Themeidol - Author Badge', 'themeidol-all-widget'), $args);
		// Register site styles and scripts
    	add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
		// Refreshing the widget's cached output with each new post
	    add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
	    add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
	    add_action( 'delete_attachment', array( $this, 'flush_group_cache' ) );
	    add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
	}

	function widget($args, $instance){
		// Check if there is a cached output
		$cache    = (array) wp_cache_get( 'themeidol-authorbadge', 'widget' );

		if(!is_array($cache)) $cache = array();
		
		if(isset($cache[$args['widget_id']])){
				echo $cache[$args['widget_id']];
				return;
		}
		
		extract($args);
		$widget_id = str_replace('-', '_', $widget_id);
		$title = apply_filters('widget_title', esc_attr($instance['title']));
		$userid = intval(esc_attr($instance['user']));
		$description = esc_attr(esc_attr($instance['description']));
		$userdata = get_userdata($userid);			
		$size = intval($instance['size']);
		if($size == 0) $size = 100; 
		$outputprev='';
		$output = '';
		$output .= '<div class="themeidol-author">';
		$output .= '<div class="themeidol-author-image">'.get_avatar($userdata->user_email, $size).'</div>';
		$output .= '<div class="themeidol-author-body">';
		$output .= '<h4 class="themeidol-author-name"><a href="'.get_author_posts_url($userid).'">'.get_the_author_meta('nicename',$userid).'</a></h4>';
		if($description != ''){
			$output .= '<div class="themeidol-author-description">'.$description.'</div>';
		}
		$output .= '<div class="themeidol-author-content">'.get_the_author_meta('description', $userid).'</div>';
		$output .= '</div>';
		$output .= '</div>';
		         // Adding the custom class idol-widget for default widget class
        if (strpos($before_widget, 'widget ') !== false) {
            $before_widget = preg_replace('/widget /', "idol-widget ", $before_widget, 1);
        }
		echo $before_widget;
		if($title != '') {
		$outputprev =$before_title.$title.$after_title;}
		echo $outputprev.$output;
		echo $after_widget;
		$cache[ $args['widget_id'] ] = $before_widget.$outputprev.$output.$after_widget;
		wp_cache_set( 'themeidol-authorbadge', $cache, 'widget' );
	}
	public function flush_widget_cache() {
    	wp_cache_delete( 'themeidol-authorbadge', 'widget' );
  	}
  	/**
   	* Registers and enqueues widget-specific styles.
   	*/
	  public function register_widget_styles() {
	    wp_enqueue_style( 'themeidol-authotstyle', THEMEIDOL_WIDGET_CSS_URL.'author-style.css');
	  } // end register_widget_styles


	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags(esc_attr($new_instance['title']));
		$instance['user'] = intval($new_instance['user']);
		$instance['size'] = intval($new_instance['size']);
		$instance['description'] = strip_tags(esc_attr($new_instance['description']));
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
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'themeidol-all-widget'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('user'); ?>"><?php _e('User', 'ctthemeidol-all-widgetwg'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>">
				<?php foreach($user_list as $current_user): ?>
				<option value="<?php echo esc_attr($current_user->ID); ?>" <?php if($user == $current_user->ID) echo 'selected'; ?>><?php echo esc_attr($current_user->user_nicename); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description', 'themeidol-all-widget'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" type="text" value="<?php echo $description; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Avatar Size (px)', 'themeidol-all-widget'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>" type="text" value="<?php echo $size; ?>" />
		</p>
	<?php 
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget("Themeidol_Widget_Author");' ) );