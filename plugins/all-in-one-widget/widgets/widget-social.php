<?php
/**
 * Social Icons Widget Class
 */
if ( !defined('ABSPATH')) exit;

class Themeidol_Widget_Social extends WP_Widget{
	
	function __construct(){
		$widget_ops = array('classname' => 'themeidol-social', 'description' => __('This widget lets you display an advertising banner of any size.', 'themeidol-all-widget'));
		parent::__construct('themeidol-social', __('Themeidol - Social Links', 'themeidol-all-widget'), $widget_ops);
		$this->alt_option_name = 'themeidol-social';
		// Register site styles and scripts
    	add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
		// Refreshing the widget's cached output with each new post
	    add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
	    add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
	    add_action( 'delete_attachment', array( $this, 'flush_group_cache' ) );
	    add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );  
	}

	function widget($args, $instance){
		$cache    = (array) wp_cache_get( 'themeidol-featuredvertialpost', 'widget' );

         if(!is_array($cache)) $cache = array();
      
         if(isset($cache[$args['widget_id']])){
            echo $cache[$args['widget_id']];
            return;
         }
      	ob_start();
		extract($args);
		$title = apply_filters('widget_title', esc_attr($instance['title']));
		$page_rss = esc_url($instance['page_rss']);
		$page_facebook = esc_url($instance['page_facebook']);
		$page_twitter = esc_url($instance['page_twitter']);
		$page_gplus = esc_url($instance['page_gplus']);
		$page_linkedin = esc_url($instance['page_linkedin']);
		$page_youtube = esc_url($instance['page_youtube']);
		$page_tumblr = esc_url($instance['page_tumblr']);
		$page_skype = esc_attr($instance['page_skype']);
		$page_pinterest = esc_url($instance['page_pinterest']);
		$page_instagram = esc_url($instance['page_instagram']);
		$page_dribbble = esc_url($instance['page_dribbble']);
		if (strpos($before_widget, 'widget ') !== false) {
            $before_widget = preg_replace('/widget /', "idol-widget ", $before_widget, 1);
        }		
		echo $before_widget;
		if($title != '') echo $before_title.$title.$after_title; ?>
		<div class="themeidol-social" id="<?php echo $widget_id; ?>">
			<?php if($page_rss != ''): ?>
			<a class="themeidol-social-link themeidol-social-rss" href="<?php echo esc_url($page_rss); ?>" title="RSS">
				<span class="themeidol-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_facebook != ''): ?>
			<a class="themeidol-social-link themeidol-social-facebook" href="<?php echo esc_url($page_facebook); ?>" title="Facebook">
				<span class="themeidol-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_twitter != ''): ?>
			<a class="themeidol-social-link themeidol-social-twitter" href="<?php echo esc_url($page_twitter); ?>" title="Twitter">
				<span class="themeidol-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_gplus != ''): ?>
			<a class="themeidol-social-link themeidol-social-gplus" href="<?php echo esc_url($page_gplus); ?>" title="Google+">
				<span class="themeidol-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_linkedin != ''): ?>
			<a class="themeidol-social-link themeidol-social-linkedin" href="<?php echo esc_url($page_linkedin); ?>" title="LinkedIn">
				<span class="themeidol-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_youtube != ''): ?>
			<a class="themeidol-social-link themeidol-social-youtube" href="<?php echo esc_url($page_youtube); ?>" title="YouTube">
				<span class="themeidol-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_tumblr != ''): ?>
			<a class="themeidol-social-link themeidol-social-tumblr" href="<?php echo esc_url($page_tumblr); ?>" title="Tumblr">
				<span class="themeidol-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_skype != ''): ?>
			<a class="themeidol-social-link themeidol-social-skype" href="<?php echo esc_url($page_skype); ?>" title="Skype">
				<span class="themeidol-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_pinterest != ''): ?>
			<a class="themeidol-social-link themeidol-social-pinterest" href="<?php echo esc_url($page_pinterest); ?>" title="Pinterest">
				<span class="themeidol-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_instagram != ''): ?>
			<a class="themeidol-social-link themeidol-social-instagram" href="<?php echo esc_url($page_instagram); ?>" title="Instagram">
				<span class="themeidol-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_dribbble != ''): ?>
			<a class="themeidol-social-link themeidol-social-dribbble" href="<?php echo esc_url($page_dribbble); ?>" title="Dribbble">
				<span class="themeidol-social-icon"></span>
			</a>
			<?php endif; ?>
		</div>
		<?php echo $after_widget;
			$widget_string = ob_get_flush();
			$cache[$args['widget_id']] = $widget_string;
			wp_cache_add('themeidol-social', $cache, 'widget');
	}
	public function flush_widget_cache() {
    		wp_cache_delete( 'themeidol-social', 'widget' );
  	}
	/**
   	* Registers and enqueues widget-specific styles.
   	*/
	  public function register_widget_styles() {
	    wp_enqueue_style( 'themeidol-social', THEMEIDOL_WIDGET_CSS_URL.'social-links-style.css');
	  } // end register_widget_styles

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['page_rss'] = esc_url($new_instance['page_rss']);
		$instance['page_facebook'] = esc_url($new_instance['page_facebook']);
		$instance['page_twitter'] = esc_url($new_instance['page_twitter']);
		$instance['page_gplus'] = esc_url($new_instance['page_gplus']);
		$instance['page_linkedin'] = esc_url($new_instance['page_linkedin']);
		$instance['page_youtube'] = esc_url($new_instance['page_youtube']);
		$instance['page_tumblr'] = esc_url($new_instance['page_tumblr']);
		$instance['page_skype'] = esc_attr($new_instance['page_skype']);
		$instance['page_instagram'] = esc_url($new_instance['page_instagram']);
		$instance['page_dribbble'] = esc_url($new_instance['page_dribbble']);
		$instance['page_pinterest'] = esc_url($new_instance['page_pinterest']);
		return $instance;
	}

	function form($instance){
		$instance = wp_parse_args((array)$instance, 
		array('title' => '', 
		'page_rss' => '', 
		'page_facebook' => '', 
		'page_twitter' => '', 
		'page_gplus' => '', 
		'page_linkedin' => '', 
		'page_youtube' => '', 
		'page_tumblr' => '', 
		'page_skype' => '', 
		'page_pinterest' => '', 
		'page_instagram' => '', 
		'page_dribbble' => '', 
		'ad_code' => ''));
		
		extract($instance, EXTR_SKIP);
		$title = esc_attr($instance['title']);
		$page_rss = esc_url($instance['page_rss']);
		$page_facebook = esc_url($instance['page_facebook']);
		$page_twitter = esc_url($instance['page_twitter']);
		$page_gplus = esc_url($instance['page_gplus']);
		$page_linkedin = esc_url($instance['page_linkedin']);
		$page_youtube = esc_url($instance['page_youtube']);
		$page_tumblr = esc_url($instance['page_tumblr']);
		$page_skype = esc_attr($instance['page_skype']);
		$page_pinterest = esc_url($instance['page_pinterest']);
		$page_instagram = esc_url($instance['page_instagram']);
		$page_dribbble = esc_url($instance['page_dribbble']); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'themeidol-all-widget'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_rss'); ?>"><?php _e('RSS URL', 'themeidol-all-widget'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_rss'); ?>" name="<?php echo $this->get_field_name('page_rss'); ?>" type="text" value="<?php echo $page_rss; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_facebook'); ?>"><?php _e('Facebook Page', 'ctthemeidol-all-widgetwg'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_facebook'); ?>" name="<?php echo $this->get_field_name('page_facebook'); ?>" type="text" value="<?php echo $page_facebook; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_twitter'); ?>"><?php _e('Twitter Page', 'themeidol-all-widget'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_twitter'); ?>" name="<?php echo $this->get_field_name('page_twitter'); ?>" type="text" value="<?php echo $page_twitter; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_gplus'); ?>"><?php _e('Google Plus Page', 'themeidol-all-widget'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_gplus'); ?>" name="<?php echo $this->get_field_name('page_gplus'); ?>" type="text" value="<?php echo $page_gplus; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_linkedin'); ?>"><?php _e('LinkedIn Page', 'themeidol-all-widget'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_linkedin'); ?>" name="<?php echo $this->get_field_name('page_linkedin'); ?>" type="text" value="<?php echo $page_linkedin; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_youtube'); ?>"><?php _e('YouTube Page', 'themeidol-all-widget'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_youtube'); ?>" name="<?php echo $this->get_field_name('page_youtube'); ?>" type="text" value="<?php echo $page_youtube; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_tumblr'); ?>"><?php _e('Tumblr Page', 'themeidol-all-widget'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_tumblr'); ?>" name="<?php echo $this->get_field_name('page_tumblr'); ?>" type="text" value="<?php echo $page_tumblr; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_skype'); ?>"><?php _e('Skype Page', 'themeidol-all-widget'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_skype'); ?>" name="<?php echo $this->get_field_name('page_skype'); ?>" type="text" value="<?php echo $page_skype; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_pinterest'); ?>"><?php _e('Pinterest Page', 'themeidol-all-widget'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_pinterest'); ?>" name="<?php echo $this->get_field_name('page_pinterest'); ?>" type="text" value="<?php echo $page_pinterest; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_instagram'); ?>"><?php _e('Instagram Page', 'themeidol-all-widget'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_instagram'); ?>" name="<?php echo $this->get_field_name('page_instagram'); ?>" type="text" value="<?php echo $page_instagram; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_dribbble'); ?>"><?php _e('Dribbble Page', 'themeidol-all-widget'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_dribbble'); ?>" name="<?php echo $this->get_field_name('page_dribbble'); ?>" type="text" value="<?php echo $page_dribbble; ?>" />
		</p>
	<?php }
}
add_action( 'widgets_init', create_function( '', 'return register_widget("Themeidol_Widget_Social");' ) );