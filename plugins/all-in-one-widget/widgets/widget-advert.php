<?php
/**
 * Advert Widget Class
 */
if ( !defined('ABSPATH')) exit;

class Themeidol_Widget_Advert extends WP_Widget{
	
	function __construct(){
		$widget_ops = array('classname' => 'themeiedol-advert', 'description' => __('This widget lets you display an advertising banner of any size.', 'themeidol-all-widget'));
		parent::__construct('themeidol-advert', __('Themeidol - Adv Space', 'themeidol-all-widget'), $widget_ops);
		$this->alt_option_name = 'themeidol-advert';
		add_action('admin_enqueue_scripts', array(&$this,'widget_image_uploader'));

		// Refreshing the widget's cached output with each new post
    	add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
    	add_action( 'delete_attachment', array( $this, 'flush_group_cache' ) );
    	add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
    	add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

	}

	function widget($args, $instance){
		 // Check if there is a cached output
	    $cache = wp_cache_get( 'themeidol-advert', 'widget' );

	    if ( !is_array( $cache ) ) {
	      $cache = array();
	    }

	    if ( !isset( $args['widget_id'] ) ) {
	      $args['widget_id'] = $this->id;
	    }

	    if ( isset( $cache[ $args['widget_id'] ] ) ) {
	      return print $cache[ $args['widget_id'] ];
	    }
		extract($args);
		$title = apply_filters('widget_title', esc_attr($instance['title']));
		$image_url = esc_url($instance['image_url']);
		$link_url = esc_url($instance['link_url']);
		$ad_code = esc_attr($instance['ad_code']);
		  // Adding the custom class idol-widget for default widget class
        if (strpos($before_widget, 'widget ') !== false) {
            $before_widget = preg_replace('/widget /', "idol-widget ", $before_widget, 1);
        }
			
		ob_start();
		echo $before_widget;
		if($title != '') echo $before_title.$title.$after_title; ?>
		<div class="themeidol-advert" id="<?php echo $widget_id; ?>">
			<?php if($ad_code == ''): ?>
			<a href="<?php echo esc_url($link_url); ?>">
				<img src="<?php echo $image_url; ?>" title="<?php echo esc_attr($title); ?>" alt="<?php echo esc_attr($title); ?>"/>
			</a>
			<?php else: ?>
			<?php echo $ad_code; ?>
			<?php endif; ?>
		</div>
		<?php echo $after_widget;
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_add('themeidol-advert', $cache, 'widget');
	}
	function widget_image_uploader() {
	    wp_enqueue_media();
	    wp_enqueue_script('colormag-widget-image-upload', THEMEIDOL_WIDGET_JS_URL . 'image-uploader.js', false, '20150309', true);
	}

	//Flush Cache Functionality
	public function flush_widget_cache() {
    wp_cache_delete( 'themeidol-advert', 'widget' );
  	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['image_url'] = esc_url_raw($new_instance['image_url']);
		$instance['link_url'] = esc_url_raw($new_instance['link_url']);
		$instance['ad_code'] = esc_attr($new_instance['ad_code']);
		return $instance;
	}

	function form($instance){
		$instance = wp_parse_args((array)$instance, array('title' => '', 'image_url' => '', 'link_url' => '', 'ad_code' => ''));
		extract($instance, EXTR_SKIP);
		$title = esc_attr($instance['title']);
		$image_url = esc_url($instance['image_url']);
		$link_url = esc_url($instance['link_url']);
		$ad_code = esc_attr($instance['ad_code']); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'themeidol-all-widget'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		
		<p>
         <label for="<?php echo $this->get_field_id( 'image_url' ); ?>"> <?php _e( 'Advertisement Image ', 'themeidol-all-widget' ); ?></label>
         <div class="media-uploader" id="<?php echo $this->get_field_id( 'image_url' ); ?>">
            <div class="custom_media_preview">
               <?php if ( $image_url != '' ) : ?>
                  <img class="custom_media_preview_default" src="<?php echo esc_url( $instance[ 'image_url' ] ); ?>" style="max-width:100%;" />
               <?php endif; ?>
            </div>
            <label><?php _e( 'Image URL', 'themeidol-all-widget' ); ?></label>
            <input type="text" class="widefat custom_media_input" id="<?php echo $this->get_field_id( 'image_url' ); ?>" name="<?php echo $this->get_field_name( 'image_url' ); ?>" value="<?php echo esc_url( $instance['image_url'] ); ?>" style="margin-top:5px;" />
            <button class="custom_media_upload button button-secondary button-large" id="<?php echo $this->get_field_id( 'image_url' ); ?>" data-choose="<?php esc_attr_e( 'Choose an image', 'themeidol-all-widget' ); ?>" data-update="<?php esc_attr_e( 'Use image', 'themeidol-all-widget' ); ?>" style="width:100%;margin-top:6px;margin-right:30px;"><?php esc_html_e( 'Select an Image', 'themeidol-all-widget' ); ?></button>
         </div>
      </p>
		<p>
			<label for="<?php echo $this->get_field_id('link_url'); ?>"><?php _e('Link URL', 'themeidol-all-widget'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('link_url'); ?>" name="<?php echo $this->get_field_name('link_url'); ?>" type="text" value="<?php echo $link_url; ?>" />
		</p>
		<p><b>- <?php _e('or', 'themeidol-all-widget'); ?> -</b></p>
		<p>
			<label for="<?php echo $this->get_field_id('ad_code'); ?>"><?php _e('Advertising Code', 'themeidol-all-widget'); ?></label><br/>
			<textarea class="widefat" id="<?php echo $this->get_field_id('ad_code'); ?>" name="<?php echo $this->get_field_name('ad_code'); ?>"><?php echo $ad_code; ?></textarea>
		</p>
		<p><?php _e('You can add an image linked to a specific URL, or  paste your advertising code.', 'themeidol-all-widget'); ?></p>
	<?php }
}
add_action( 'widgets_init', create_function( '', 'return register_widget("Themeidol_Widget_Advert");' ) );
