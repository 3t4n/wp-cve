<?php 

class directorypress_listings_handler extends directorypress_public {
	public $directorypress_client = 'directorypress_listings_handler';

	public function init($args = array()) {
		global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS;
		
		parent::init($args);
	
		$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
		
		$shortcode_atts = array_merge(array(
				'directorytypes' => '',
				'perpage' => 10,
				'onepage' => 0,
				'listing_post_style' => 'default',
				'listing_post_style_mobile' => '',
				'has_sticky_has_featured' => 0,
				'order_by' => 'post_date',
				'order' => 'ASC',
				'hide_order' => 0,
				'hide_count' => 0,
				'hide_paginator' => 0,
				'show_views_switcher' => 1,
				//'sorting_panel_style' => (isset($DIRECTORYPRESS_ADIMN_SETTINGS['view_switther_panel_style']))? $DIRECTORYPRESS_ADIMN_SETTINGS['view_switther_panel_style'] : 1,
				'listings_view_type' => 'list',
				'listings_view_grid_columns' =>  1,
				'grid_padding' => 15,
				'masonry_layout' => 0,
				'2col_responsive' => 0,
				'wrap_logo_list_view' => 0,
				'hide_content' => 0,
				'summary_on_logo_hover' => 0,
				'carousel' => 0,
				'carousel_show_slides' => 4,
				'carousel_slide_width' => 250,
				'carousel_slide_height' => 300,
				'carousel_full_width' => 0,
				'author' => 0,
				'paged' => $paged,
				'include_categories_children' => 0,
				'include_get_params' => 1,
				'categories' => '',
				'locations' => '',
				'packages' => '',
				'related_directory' => 0,
				'related_categories' => 0,
				'related_locations' => 0,
				'related_tags' => 0,
				'scrolling_paginator' => 0,
				'scroll' => 0,
				'desktop_items' => '3' ,
				'mobile_items' => '1' ,
				'tab_items' => '2' ,
				'autoplay' => 'false' ,
				'loop' => 'false' ,
				'owl_nav' => 'false' ,
				'delay' => '1000' ,
				'autoplay_speed' => '1000' ,
				'gutter' => '30' ,
				'slider_arrow_position' => 'absolute',
				'slider_arrow_icon_pre' => '',
				'slider_arrow_icon_next' => '',
				'listing_image_width' => 370,
				'listing_image_height' => 270,
				'listing_has_featured_tag_style' => '',
				'listing_order_by_txt' => '',
				'scroller_nav_style' => 1,
				'custom_category_link' => '',
				'custom_category_link_text' => '',
				'post__not_in' => '',
				'template' => 'partials/listing/wrapper.php',
				'is_widget' => 0,
				'custom_settings' => array(),
				'uid' => null,
		), $args);
		$shortcode_atts = apply_filters('directorypress_related_shortcode_args', $shortcode_atts, $args);
		
		$this->args = $shortcode_atts;
		
		if ($shortcode_atts['include_get_params']) {
			array_walk_recursive($_GET, 'sanitize_text_field');
			$this->args = array_merge($this->args, $_GET);
		}
		
		$this->args['listing_post_style'] = apply_filters('directorypress_listing_shortcode_grid_style', $this->args['listing_post_style'], $this->args['custom_settings']);
		$base_url_args = apply_filters('directorypress_base_url_args', array());
		if (isset($args['base_url'])){
			$this->base_url = add_query_arg($base_url_args, $args['base_url']);
		}else{
			$this->base_url = add_query_arg($base_url_args, get_permalink());
		}
		$this->template = $this->args['template'];
		
		$args = array(
				'post_type' => DIRECTORYPRESS_POST_TYPE,
				'post_status' => 'publish',
				//'meta_query' => array(array('key' => '_listing_status', 'value' => 'active')),
				'posts_per_page' => $shortcode_atts['perpage'],
				'paged' => $paged,
		);
		
		if ($shortcode_atts['author'])
			$args['author'] = $shortcode_atts['author'];
		
		// render just one page - all found listings on one page
		if ($shortcode_atts['onepage'])
			$args['posts_per_page'] = -1;

		$args = array_merge($args, apply_filters('directorypress_order_args', array(), $shortcode_atts, true));
		$args = apply_filters('directorypress_search_args', $args, $this->args, $this->args['include_get_params'], $this->hash);

		if (!empty($this->args['post__in'])) {
			if (is_string($this->args['post__in'])) {
				$args = array_merge($args, array('post__in' => explode(',', $this->args['post__in'])));
			} elseif (is_array($this->args['post__in'])) {
				$args['post__in'] = $this->args['post__in'];
			}
		}
		if (!empty($this->args['post__not_in'])) {
			$args = array_merge($args, array('post__not_in' => explode(',', $this->args['post__not_in'])));
		}

		
			if (!empty($this->args['directorytypes'])) {
				if ($directorytypes_ids = array_filter(explode(',', $this->args['directorytypes']), 'trim')) {
					$args = directorypress_set_directory_args($args, $directorytypes_ids);
				}
			}

			if (isset($this->args['packages']) && !is_array($this->args['packages'])) {
				if ($packages = array_filter(explode(',', $this->args['packages']), 'trim')) {
					$this->packages_ids = $packages;
					add_filter('posts_where', array($this, 'where_packages_ids'));
				}
			}
	
			if (isset($this->args['packages']) || $this->args['has_sticky_has_featured']) {
				add_filter('posts_join', 'join_packages');
				if ($this->args['has_sticky_has_featured'])
					add_filter('posts_where', 'where_has_sticky_has_featured');
			}
			$this->query = new WP_Query($args);
			
			// adapted for Relevanssi
			if (directorypress_is_relevanssi_search($shortcode_atts)) {
				$this->query->query_vars['s'] = directorypress_get_input_value($shortcode_atts, 'what_search');
				$this->query->query_vars['posts_per_page'] = $shortcode_atts['perpage'];
				relevanssi_do_query($this->query);
			}
				
			//var_dump($this->query->request);
			$this->processQuery(false);

			if ($this->args['has_sticky_has_featured']) {
				remove_filter('posts_join', 'join_packages');
				remove_filter('posts_where', 'where_has_sticky_has_featured');
			}
	
			if ($this->packages_ids){
				remove_filter('posts_where', array($this, 'where_packages_ids'));
			}
		
		$this->listings_view_type = $this->args['listings_view_type']; 
		$this->listing_view = directorypress_listing_view_type($this->args['listings_view_type'], $this->hash);
		$this->enqueue_listing_styles();
		add_action('wp_enqueue_scripts', array($this, 'enqueue_listing_styles'));
		
		apply_filters('directorypress_listings_handler_construct', $this);
	}
	
	public function enqueue_listing_styles() {
			global $DIRECTORYPRESS_ADIMN_SETTINGS;
			wp_enqueue_style('directorypress_listings');
			
			$style = $this->args['listing_post_style'];
			$style_handle = 'directorypress_listing_style_'. $style;
        	if(wp_style_is($style_handle, 'registered')){
        	    wp_enqueue_style($style_handle);
        	}
	}
}