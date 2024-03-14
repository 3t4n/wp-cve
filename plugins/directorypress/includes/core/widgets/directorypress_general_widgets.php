<?php 
add_action('widgets_init', 'directorypress_register_bids_widget');
function directorypress_register_bids_widget() {
	register_widget('directorypress_bids_widget');
}

class directorypress_bids_widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
			'directorypress_bids_widget',
			__('DIRECTORYPRESS - Bids', 'DIRECTORYPRESS'),
			array('description' => __( 'DIRECTORYPRESS Bids', 'DIRECTORYPRESS'),)
		);
		
		add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'));
	}

	public function widget($args, $instance) {
		global $directorypress_object;

		if (!$instance['visibility'] || !empty($directorypress_object->public_handlers)) {
			$title = apply_filters('widget_title', $instance['title']);
			
			//if ($listing_id)
				directorypress_display_template('partials/widgets/bids/bids_widget.php', array('args' => $args, 'title' => $title,));
		}
	}
	
	public function form($instance) {
		$defaults = array('title' => __('Offers', 'DIRECTORYPRESS'), 'visibility' => 1);
		$instance = wp_parse_args((array) $instance, $defaults);
		
		directorypress_display_template('partials/widgets/bids/bids_widget_options.php', array('widget' => $this, 'instance' => $instance));
	}
	
	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		$instance['visibility'] = (!empty($new_instance['visibility'])) ? strip_tags($new_instance['visibility']) : '';
	
		return $instance;
	}
	
	public function wp_enqueue_scripts() {
		$widget_options_all = get_option($this->option_name);
		if (isset($widget_options_all[$this->number])) {
			$current_widget_options = $widget_options_all[$this->number];
			if (is_active_widget(false, false, $this->id_base, true) && !$current_widget_options['visibility']) {
				global $directorypress_object, $directorypress_fsubmit_instance, $directorypress_payments_instance, $directorypress_ratings_instance;
		
				//$directorypress_object->enqueue_scripts_styles(true);
				//if ($directorypress_fsubmit_instance)
					//$directorypress_fsubmit_instance->enqueue_scripts_styles(true);
				//if ($directorypress_payments_instance)
					//$directorypress_payments_instance->enqueue_scripts_styles(true);
				//if ($directorypress_ratings_instance)
					//$directorypress_ratings_instance->enqueue_scripts_styles(true);
			}
		}
	}

}
if(directorypress_has_map()){
	add_action('widgets_init', 'directorypress_register_map_widget');
}
function directorypress_register_map_widget() {
	register_widget('directorypress_map_widget');
}

class directorypress_map_widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
			'directorypress_map_widget',
			__('DIRECTORYPRESS - Map', 'DIRECTORYPRESS'),
			array('description' => __( 'DIRECTORYPRESS Advert Map', 'DIRECTORYPRESS'),)
		);
		
		add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'));
	}

	public function widget($args, $instance) {
		global $directorypress_object;

		if (!$instance['visibility'] || !empty($directorypress_object->public_handlers)) {
			$title = apply_filters('widget_title', $instance['title']);
			
			//if ($listing_id)
				directorypress_display_template('partials/widgets/map/map_widget.php', array('height' => 220, 'args' => $args, 'title' => $title,));
		}
	}
	
	public function form($instance) {
		$defaults = array('title' => __('Map View', 'DIRECTORYPRESS'), 'visibility' => 1);
		$instance = wp_parse_args((array) $instance, $defaults);
		
		directorypress_display_template('partials/widgets/map/map_widget_options.php', array('widget' => $this, 'instance' => $instance));
	}
	
	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		$instance['visibility'] = 1;
	
		return $instance;
	}
	
	public function wp_enqueue_scripts() {
		$widget_options_all = get_option($this->option_name);
		if (isset($widget_options_all[$this->number])) {
			$current_widget_options = $widget_options_all[$this->number];
			if (is_active_widget(false, false, $this->id_base, true) && !$current_widget_options['visibility']) {
				global $directorypress_object, $directorypress_fsubmit_instance, $directorypress_payments_instance, $directorypress_ratings_instance;
		
				$directorypress_object->enqueue_scripts_styles(true);
				if ($directorypress_fsubmit_instance)
					$directorypress_fsubmit_instance->enqueue_scripts_styles(true);
				if ($directorypress_payments_instance)
					$directorypress_payments_instance->enqueue_scripts_styles(true);
				if ($directorypress_ratings_instance)
					$directorypress_ratings_instance->enqueue_scripts_styles(true);
			}
		}
	}

}

add_action('widgets_init', 'directorypress_register_resurva_widget');
function directorypress_register_resurva_widget() {
	register_widget('directorypress_resurva_widget');
}

class directorypress_resurva_widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
			'directorypress_resurva_widget',
			__('DIRECTORYPRESS - Resurva Booking', 'DIRECTORYPRESS'),
			array('description' => __( 'DIRECTORYPRESS Resurva Booking', 'DIRECTORYPRESS'),)
		);
		
		add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'));
	}

	public function widget($args, $instance) {
		global $directorypress_object;

		if (!$instance['visibility'] || !empty($directorypress_object->public_handlers)) {
			$title = apply_filters('widget_title', $instance['title']);
			
			//if ($listing_id)
				directorypress_display_template('partials/widgets/resurva/resurva_widget.php', array('args' => $args, 'title' => $title,));
		}
	}
	
	public function form($instance) {
		$defaults = array('title' => __('Resurva Booking', 'DIRECTORYPRESS'), 'visibility' => 1);
		$instance = wp_parse_args((array) $instance, $defaults);
		
		directorypress_display_template('partials/widgets/resurva/resurva_widget_options.php', array('widget' => $this, 'instance' => $instance));
	}
	
	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		$instance['visibility'] = 1;
	
		return $instance;
	}
	
	public function wp_enqueue_scripts() {
		$widget_options_all = get_option($this->option_name);
		if (isset($widget_options_all[$this->number])) {
			$current_widget_options = $widget_options_all[$this->number];
			if (is_active_widget(false, false, $this->id_base, true) && !$current_widget_options['visibility']) {
				global $directorypress_object, $directorypress_fsubmit_instance, $directorypress_payments_instance, $directorypress_ratings_instance;
		
				$directorypress_object->enqueue_scripts_styles(true);
				if ($directorypress_fsubmit_instance)
					$directorypress_fsubmit_instance->enqueue_scripts_styles(true);
				if ($directorypress_payments_instance)
					$directorypress_payments_instance->enqueue_scripts_styles(true);
				if ($directorypress_ratings_instance)
					$directorypress_ratings_instance->enqueue_scripts_styles(true);
			}
		}
	}

}

add_action('widgets_init', 'directorypress_register_social_widget');
function directorypress_register_social_widget() {
	register_widget('directorypress_social_widget');
}

class directorypress_social_widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
			'directorypress_social_widget',
			__('DIRECTORYPRESS - Social', 'DIRECTORYPRESS'),
			array('description' => __( 'Social services', 'DIRECTORYPRESS'))
		);
	}

	public function widget($args, $instance) {
		global $directorypress_object;
		
		if (!$instance['visibility'] || !empty($directorypress_object->public_handlers)) {
			$title = apply_filters('widget_title', $instance['title']);
	
			directorypress_display_template('partials/widgets/social/social_widget.php', array('args' => $args, 'title' => $title, 'instance' => $instance));
		}
	}
	
	public function form($instance) {
		$defaults = array(
				'title' => __('Social accounts', 'DIRECTORYPRESS'),
				'facebook' => 'http://www.facebook.com/',
				'is_facebook' => 1,
				'twitter' => 'http://twitter.com/',
				'is_twitter' => 1,
				'linkedin' => 'http://www.linkedin.com/',
				'is_linkedin' => 1,
				'youtube' => 'http://www.youtube.com/',
				'is_youtube' => 1,
				'rss' => esc_url(add_query_arg('post_type', DIRECTORYPRESS_POST_TYPE, site_url('feed'))),
				'is_rss' => 1,
				'visibility' => 1,
		);
		$instance = wp_parse_args((array) $instance, $defaults);
		
		directorypress_display_template('partials/widgets/social/social_widget_options.php', array('widget' => $this, 'instance' => $instance));
	}
	
	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		$instance['facebook'] = (!empty($new_instance['facebook'])) ? strip_tags($new_instance['facebook']) : '';
		$instance['is_facebook'] = (!empty($new_instance['is_facebook'])) ? strip_tags($new_instance['is_facebook']) : '';
		$instance['twitter'] = (!empty($new_instance['twitter'])) ? strip_tags($new_instance['twitter']) : '';
		$instance['is_twitter'] = (!empty($new_instance['is_twitter'])) ? strip_tags($new_instance['is_twitter']) : '';
		$instance['linkedin'] = (!empty($new_instance['linkedin'])) ? strip_tags($new_instance['linkedin']) : '';
		$instance['is_linkedin'] = (!empty($new_instance['is_linkedin'])) ? strip_tags($new_instance['is_linkedin']) : '';
		$instance['youtube'] = (!empty($new_instance['youtube'])) ? strip_tags($new_instance['youtube']) : '';
		$instance['is_youtube'] = (!empty($new_instance['is_youtube'])) ? strip_tags($new_instance['is_youtube']) : '';
		$instance['rss'] = (!empty($new_instance['rss'])) ? strip_tags($new_instance['rss']) : '';
		$instance['is_rss'] = (!empty($new_instance['is_rss'])) ? strip_tags($new_instance['is_rss']) : '';
		$instance['visibility'] = (!empty($new_instance['visibility'])) ? strip_tags($new_instance['visibility']) : '';
	
		return $instance;
	}
}

?>