<?php 
class directorypress_ajax {

	public function __construct() {
		add_action('wp_ajax_directorypress_get_map_markers', array($this, 'get_map_markers'));
		add_action('wp_ajax_nopriv_directorypress_get_map_markers', array($this, 'get_map_markers'));

		add_action('wp_ajax_directorypress_get_map_marker_info', array($this, 'get_map_marker_info'));
		add_action('wp_ajax_nopriv_directorypress_get_map_marker_info', array($this, 'get_map_marker_info'));

		add_action('wp_ajax_directorypress_handler_request', array($this, 'directorypress_handler_demand'));
		add_action('wp_ajax_nopriv_directorypress_handler_request', array($this, 'directorypress_handler_demand'));

		add_action('wp_ajax_directorypress_search_by_poly', array($this, 'search_by_poly'));
		add_action('wp_ajax_nopriv_directorypress_search_by_poly', array($this, 'search_by_poly'));
		
		add_action('wp_ajax_directorypress_select_field_icon', array($this, 'select_field_icon'));
		add_action('wp_ajax_nopriv_directorypress_select_field_icon', array($this, 'select_field_icon'));
		
		add_action('wp_ajax_directorypress_contact_form', array($this, 'contact_form'));
		add_action('wp_ajax_nopriv_directorypress_contact_form', array($this, 'contact_form'));

		add_action('wp_ajax_directorypress_keywords_search', array($this, 'keywords_search'));
		add_action('wp_ajax_nopriv_directorypress_keywords_search', array($this, 'keywords_search'));
	}

	public function directorypress_handler_demand() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
		
		$directorypress_object->setup_current_page_directorytype();

		

		switch ($_POST['handler']) {
			case "directorypress_directory_handler":
			case "directorypress_listings_handler":
				if ($_POST['handler'] == "directorypress_directory_handler"){
					$shortcode_atts = array_merge(array(
							'perpage' => (isset($_POST['is_home']) && $_POST['is_home']) ? $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_number_index'] : $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_number_excerpt'],
							'onepage' => 0,
							'map_markers_is_limit' => (directorypress_has_map())? $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_map_markers_is_limit']: 1,
							'has_sticky_has_featured' => 0,
							'order_by' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_orderby'],
							'order' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_order'],
							'hide_order' => (int)(!($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_orderby_links'])),
							'hide_count' => 0,
							'hide_paginator' => 0,
							'scrolling_paginator' => 0,
							'show_views_switcher' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_views_switcher'],
							'listings_view_type' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_views_switcher_default'],
							'listings_view_grid_columns' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_views_switcher_grid_columns'],
							'grid_padding' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_grid_padding'],
							'summary_on_logo_hover' => 0,
							'listing_post_style' => apply_filters('directorypress_archive_page_grid_style', $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_post_style']),
							'author' => 0,
							'paged' => 1,
							'include_categories_children' => 1,
							'include_get_params' => 1,
							'scroll' => 0, 
							'desktop_items' => '3' ,
							'tab_landscape_items' => '3' ,
							'tab_items' => '2' ,
							'autoplay' => 'false' ,
							'loop' => 'false' , 
							'owl_nav' => 'false' , 
							'delay' => '1000' , 
							'autoplay_speed' => '1000' ,
							'gutter' => '30' , 
							'scroller_nav_style' => 2,
							'2col_responsive' => 0,
							'template' => 'partials/listing/wrapper.php',
					), $_POST);
				}else{
					$shortcode_atts = array_merge(array(
							'perpage' => 10,
							'onepage' => 0,
							'has_sticky_has_featured' => 0,
							'order_by' => 'post_date',
							'order' => 'DESC',
							'hide_order' => 0,
							'hide_count' => 0,
							'hide_paginator' => 0,
							'scrolling_paginator' => 0,
							'show_views_switcher' => 1,
							'listings_view_type' => '',
							'listings_view_grid_columns' => 2,
							'grid_padding' => 15,
							'listing_post_style' => apply_filters('directorypress_archive_page_grid_style', $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_post_style']),
							'hide_content' => 0,
							'summary_on_logo_hover' => 0,
							'author' => 0,
							'paged' => 1,
							'include_categories_children' => 0,
							'include_get_params' => 1,
							'scroll' => 0, //cz custom
							'desktop_items' => '3' , //cz custom
							'tab_landscape_items' => '3' , //cz custom
							'tab_items' => '2' , //cz custom
							'autoplay' => 'false' , //cz custom
							'loop' => 'false' , //cz custom
							'owl_nav' => 'false' , //cz custom
							'delay' => '1000' , //cz custom
							'autoplay_speed' => '1000' , //cz custom
							'gutter' => '30' , //cz custom
							'scroller_nav_style' => 2,
							'2col_responsive' => 0,
							'template' => array('partials/listing/wrapper.php'),
					), $_POST);
				}
				$address = false;
				$radius = false;
				if (isset($_POST['address'])) {
					$address = apply_filters('directorypress_search_param_address', $_POST['address']);
				}
				if (isset($_POST['radius'])) {
					$radius = apply_filters('directorypress_search_param_radius', $_POST['radius']);
				}
				
				if (isset($_POST['order_by'])) {
					$_REQUEST['order_by'] = directorypress_get_input_value($_POST, 'order_by', $shortcode_atts['order_by']);
					$_REQUEST['order'] = directorypress_get_input_value($_POST, 'order', $shortcode_atts['order']);
				} elseif ($address && $radius) {
					$shortcode_atts['order_by'] = 'distance';
					$shortcode_atts['order'] = 'ASC';
				}

				
				set_query_var('page', $shortcode_atts['paged']);

				$directorypress_handler = new directorypress_public();
				$directorypress_handler->init($_POST);
				$directorypress_handler->hash = $_POST['hash'];
				$directorypress_handler->args = $shortcode_atts;
				$directorypress_handler->directorypress_client = 'directorypress_listings_handler';
				$directorypress_handler->custom_home = (isset($shortcode_atts['custom_home']) && $shortcode_atts['custom_home']);

				
				$order_args = apply_filters('directorypress_order_args', array(), $shortcode_atts, false);
				
				
				if (isset($shortcode_atts['existing_listings']) && $order_args['orderby'] == 'rand') {
					$perpage = -1;
				} else {
					$perpage = $shortcode_atts['perpage'];
				}
				$default_post_status = 'publish';
				$post_status = apply_filters('directorypress_search_post_status', $default_post_status);
				$args = array(
						'post_type' => DIRECTORYPRESS_POST_TYPE,
						'post_status' => $post_status,
						'posts_per_page' => $perpage,
						'paged' => $shortcode_atts['paged'],
				);
				if ($shortcode_atts['author'])
					$args['author'] = $shortcode_atts['author'];

				if ($shortcode_atts['onepage'])
					$args['posts_per_page'] = -1;

				$args = array_merge($args, $order_args);
				$args = apply_filters('directorypress_search_args', $args, $shortcode_atts, $shortcode_atts['include_get_params'], $directorypress_handler->hash);
				if (!empty($shortcode_atts['post__in'])) {
					if (is_string($shortcode_atts['post__in'])) {
						$args = array_merge($args, array('post__in' => explode(',', $shortcode_atts['post__in'])));
					} elseif (is_array($shortcode_atts['post__in'])) {
						$args['post__in'] = $shortcode_atts['post__in'];
					}
				}
				if (!empty($shortcode_atts['post__not_in'])) {
					$args = array_merge($args, array('post__not_in' => explode(',', $shortcode_atts['post__not_in'])));
				}
				
				if (!empty($shortcode_atts['packages']) && !is_array($shortcode_atts['packages'])) {
					if ($packages = array_filter(explode(',', $shortcode_atts['packages']), 'trim')) {
						$directorypress_handler->packages_ids = $packages;
						add_filter('posts_where', array($directorypress_handler, 'where_packages_ids'));
					}
				}
				
				if (!empty($shortcode_atts['packages']) || $shortcode_atts['has_sticky_has_featured']) {
					add_filter('posts_join', 'join_packages');
					if ($shortcode_atts['has_sticky_has_featured'])
						add_filter('posts_where', 'where_has_sticky_has_featured');
				}
				
				if (!empty($shortcode_atts['directorytypes']) && !empty($shortcode_atts['directorytypes'])) {
					if ($directorytypes_ids = array_filter(explode(',', $shortcode_atts['directorytypes']), 'trim')) {
						$args = directorypress_set_directory_args($args, $directorytypes_ids);
					}
				} elseif (!empty($shortcode_atts['directorytypes']) && $_POST['handler'] == 'directorypress_directory_handler') {
					$args = directorypress_set_directory_args($args, array($directorypress_object->current_directorytype->id));
				}
				
				$args = apply_filters('directorypress_directory_query_args', $args);
					
				
				global $wp_filter;
				if (isset($wp_filter['pre_get_posts'])) {
					$pre_get_posts = $wp_filter['pre_get_posts'];
					unset($wp_filter['pre_get_posts']);
				}
				$directorypress_handler->query = new WP_Query($args);
				
				if (directorypress_is_relevanssi_search($shortcode_atts)) {
					$directorypress_handler->query->query_vars['s'] = directorypress_get_input_value($shortcode_atts, 'what_search');
					$directorypress_handler->query->query_vars['posts_per_page'] = $perpage;
					relevanssi_do_query($directorypress_handler->query);
				}
				
				if (isset($shortcode_atts['existing_listings']) && $order_args['orderby'] == 'rand') {
					$all_posts_count = count($directorypress_handler->query->posts);
					$existing_listings = array_filter(explode(',', $shortcode_atts['existing_listings']));
					foreach ($directorypress_handler->query->posts AS $key=>$post) {
						if (in_array($post->ID, $existing_listings)) {
							unset($directorypress_handler->query->posts[$key]);
						}
					}
					$directorypress_handler->query->posts = array_values($directorypress_handler->query->posts);
					$directorypress_handler->query->posts = array_slice($directorypress_handler->query->posts, 0, $shortcode_atts['perpage']);

					$directorypress_handler->query->post_count = count($directorypress_handler->query->posts);
					$directorypress_handler->query->found_posts = $all_posts_count;
					$directorypress_handler->query->max_num_pages = ceil($all_posts_count/$shortcode_atts['perpage']);
				}
				
				if (!empty($_POST['with_map']) || !empty($_POST['map_listings']))
					$load_map_markers = true;
				else
					$load_map_markers = false;

				$map_args = array();
				if (!empty($_POST['map_markers_is_limit']))
					$map_args['map_markers_is_limit'] = true;
				else
					$map_args['map_markers_is_limit'] = false;

				$directorypress_handler->processQuery($load_map_markers, $map_args);
				if (isset($pre_get_posts))
					$wp_filter['pre_get_posts'] = $pre_get_posts;

				$base_url_args = apply_filters('directorypress_base_url_args', array());
				if (!empty($_POST['base_url']))
					$directorypress_handler->base_url = add_query_arg($base_url_args, $_POST['base_url']);
				else 
					$directorypress_handler->base_url = directorypress_directorytype_url($base_url_args);
				
				global $directorypress_global_base_url;
				$directorypress_global_base_url = $directorypress_handler->base_url;
				add_filter('get_pagenum_link', array($this, 'get_pagenum_link'));
				
				$listing_post_styles = $shortcode_atts['listing_post_style'];
				
				
				$directorypress_handler->listing_view = directorypress_listing_view_type($directorypress_handler->args['listings_view_type'], $directorypress_handler->hash);
				$listing_style_to_show = $directorypress_handler->listing_view;
				 if($listing_style_to_show == 'show_grid_style'){
					if(isset($listing_post_styles) && !empty($listing_post_styles)) {
						$listing_style = $listing_post_styles. ' ' . 'listing-grid-item';
					}else{
						$listing_style = '';
					}
					
					$grid_padding = $directorypress_handler->args['grid_padding'];
				 }else{
					 $listing_style = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_listview_post_style'];
					 $grid_padding = 0;
				 }
				  if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_responsive_grid']){
					$directorypress_responsive_col = 'responsive-2col';
				 }else{
					$directorypress_responsive_col = '';
				 }
				 if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_grid_masonry_display'] && $listing_style_to_show == 'show_grid_style'){
					$masonry = 'masonry';
					$isotope_el_class = 'isotop-enabled directorypress-theme-loop ';
				}else{
					$masonry = '';
					$isotope_el_class = '';
				}
				if($listing_style_to_show == 'show_grid_style'){
					$directorypress_grid_margin_bottom = 'margin-bottom:'. $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_grid_margin_bottom']. 'px;';
				}else{
					$directorypress_grid_margin_bottom = '';
				}
				$listings_html = '';
				if (!isset($_POST['without_listings']) || !$_POST['without_listings']) {
					if (isset($_POST['do_append']) && $_POST['do_append']) {
						if ($directorypress_handler->listings)
							while ($directorypress_handler->query->have_posts()) {
								$directorypress_handler->query->the_post(); 
								
								$listings_html .= '<article id="post-' . get_the_ID() . '" class="row directorypress-listing '. esc_attr($directorypress_responsive_col) .' listing-post-style-'. esc_attr($listing_style) .'   clearfix m-grid-item ' . (($directorypress_handler->listings[get_the_ID()]->package->has_featured) ? 'directorypress-has_featured' : '') . ' ' . (($directorypress_handler->listings[get_the_ID()]->package->has_sticky) ? 'directorypress-has_sticky' : '') . '" style="padding-left:'. esc_attr($grid_padding) .'px; padding-right:'. esc_attr($grid_padding) .'px; '. $directorypress_grid_margin_bottom .'" >';
								$listings_html .= '<div class="directorypress-listing-item-holder clearfix">';
								
								$listings_html .= $directorypress_handler->listings[get_the_ID()]->display($directorypress_handler, false, true);
								$listings_html .= '</div>';
								$listings_html .= '</article>';
							}
						unset($directorypress_handler->args['do_append']);
					} else
						$listings_html = directorypress_display_template('partials/listing/wrapper.php', array('public_handler' => $directorypress_handler), true);
				}
				wp_reset_postdata();
				
				$out = array(
						'html' => $listings_html,
						'hash' => $directorypress_handler->hash,
						'map_markers' => ((!empty($_POST['with_map']) && $directorypress_handler->map) ? $directorypress_handler->map->locations_option_array : ''),
						'map_listings' => ((!empty($_POST['map_listings']) && $directorypress_handler->map) ? $directorypress_handler->map->buildListingsContent() : ''),
						'hide_show_more_listings_button' => ($shortcode_atts['paged'] >= $directorypress_handler->query->max_num_pages) ? 1 : 0,
				);
				
				if (isset($directorypress_object->radius_values_array[$directorypress_handler->hash]) && isset($directorypress_object->radius_values_array[$directorypress_handler->hash]['x_coord']) && isset($directorypress_object->radius_values_array[$directorypress_handler->hash]['y_coord'])) {
					$out['radius_params'] = array(
							'radius_value' => $directorypress_object->radius_values_array[$directorypress_handler->hash]['radius'],
							'map_coords_1' => $directorypress_object->radius_values_array[$directorypress_handler->hash]['x_coord'],
							'map_coords_2' => $directorypress_object->radius_values_array[$directorypress_handler->hash]['y_coord'],
							'dimension' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_miles_kilometers_in_search']
					);
				}
				
				echo json_encode($out);

				break;
		}
		
		die();
	}

	public function get_pagenum_link($result) {
		global $directorypress_global_base_url;

		if ($directorypress_global_base_url) {
			preg_match('/paged=(.?)/', $result, $matches);
			if (isset($matches[1])) {
				global $wp_rewrite;
				if ($wp_rewrite->using_permalinks()) {
					$parsed_url = parse_url($directorypress_global_base_url);
					$query_args = (isset($parsed_url['query'])) ? wp_parse_args($parsed_url['query']) : array();
					$query_args = array_map('urlencode', $query_args);
					$url_without_get = ($pos_get = strpos($directorypress_global_base_url, '?')) ? substr($directorypress_global_base_url, 0, $pos_get) : $directorypress_global_base_url;
					return esc_url(add_query_arg($query_args, trailingslashit(trailingslashit($url_without_get) . 'page/' . $matches[1])));
				} else
					return add_query_arg('page', $matches[1], $directorypress_global_base_url);
			} else 
				return $directorypress_global_base_url;
		}
		return $result;
	}

	public function get_map_markers() {
		global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS;
		
		$directorypress_object->setup_current_page_directorytype();

		
		$hash = sanitize_text_field($_POST['hash']);

		$map_markers = array();
		$map_listings = '';
		if (isset($_POST['neLat']) && isset($_POST['neLng']) && isset($_POST['swLat']) && isset($_POST['swLng'])) {
			
			if (isset($_POST['ajax_loading'])) {
				unset($_POST['ajax_loading']);
			}
			
			$address = false;
			$radius = false;
			if (isset($_POST['address'])) {
				$address = apply_filters('directorypress_search_param_address', $_POST['address']);
			}
			if (isset($_POST['radius'])) {
				$radius = apply_filters('directorypress_search_param_radius', $_POST['radius']);
			}
			
			if ($radius && $address) {
				
				$_POST['order_by'] = 'distance';
				$_POST['order'] = 'ASC';
			}
			
			if (!isset($_POST['directorytypes'])) {
				$_POST['directorytypes'] = $directorypress_object->current_directorytype->id;
			}
			
			$_POST['custom_home'] = 0;

			$map_handler = new directorypress_map_handler();
			$map_handler->hash = $hash;
			$map_handler->init($_POST);
			wp_reset_postdata();
			
			$map_markers = $map_handler->map->locations_option_array;
			if (!empty($_POST['map_listings'])) {
				$map_listings = $map_handler->map->buildListingsContent();
			}
		}
			
		$listings_html = '';
		if ((!isset($_POST['without_listings']) || !$_POST['without_listings'])) {
			$shortcode_atts = array_merge(array(
					'perpage' => 10,
					'onepage' => 0,
					'has_sticky_has_featured' => 0,
					'order_by' => 'post_date',
					'order' => 'DESC',
					'hide_order' => 0,
					'hide_count' => 0,
					'hide_paginator' => 0,
					'scrolling_paginator' => 0,
					'show_views_switcher' => 1,
					'listings_view_type' => 'list',
					'listings_view_grid_columns' => 2,
					'listing_post_style' => 13,
					'author' => 0,
					'paged' => 1,
					'scroll' => 0,
					'scroller_nav_style' => 2,
					'template' => 'partials/listing/wrapper.php',
			), $_POST);

			$post_ids = array();
			if (isset($map_handler->map->locations_array) && $map_handler->map->locations_array) {
				foreach ($map_handler->map->locations_array AS $location)
					$post_ids[] = $location->post_id;
				$shortcode_atts['post__in'] = $post_ids;
			} else {
				$shortcode_atts['post__in'] = array(0);
			}
			
			if (!isset($_POST['directorytypes'])) {
				$shortcode_atts['directorytypes'] = $directorypress_object->current_directorytype->id;
			}

			$directorypress_listings_handler = new directorypress_listings_handler();
			$directorypress_listings_handler->init($shortcode_atts);
			$directorypress_listings_handler->hash = $hash;
			
			$base_url_args = apply_filters('directorypress_base_url_args', array());
			if (isset($_POST['base_url']) && $_POST['base_url'])
				$directorypress_listings_handler->base_url = add_query_arg($base_url_args, $_POST['base_url']);
			else
				$directorypress_listings_handler->base_url = directorypress_directorytype_url($base_url_args);

			$listings_html = directorypress_display_template('partials/listing/wrapper.php', array('public_handler' => $directorypress_listings_handler), true);
			wp_reset_postdata();
		}

		$out = array(
				'html' => $listings_html,
				'hash' => $hash,
				'map_markers' => $map_markers,
				'map_listings' => $map_listings,
		);

		if (isset($directorypress_object->radius_values_array[$hash]) && isset($directorypress_object->radius_values_array[$hash]['x_coord']) && isset($directorypress_object->radius_values_array[$hash]['y_coord'])) {
			$out['radius_params'] = array(
					'radius_value' => $directorypress_object->radius_values_array[$hash]['radius'],
					'map_coords_1' => $directorypress_object->radius_values_array[$hash]['x_coord'],
					'map_coords_2' => $directorypress_object->radius_values_array[$hash]['y_coord'],
					'dimension' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_miles_kilometers_in_search']
			);
		}
			
		echo json_encode($out);

		die();
	}
	
	public function search_by_poly() {
		global $directorypress_object;
		
		
		$hash = sanitize_text_field($_POST['hash']);
		
		$out = array(
				'hash' => $hash
		);

		$map_markers = array();
		$map_listings = '';
		if (isset($_POST['geo_poly']) && $_POST['geo_poly']) {
			$map_handler = new directorypress_map_handler();
			$map_handler->hash = $hash;

			$_POST['ajax_loading'] = 0; 
			$_POST['ajax_markers_loading'] = 0; 
			$_POST['radius'] = 0; 
			$_POST['address'] = ''; 
			$_POST['location_id'] = 0; 
			$_POST['locations'] = ''; 
			$_POST['custom_home'] = 0;
			
			if (!isset($_POST['directorytypes'])) {
				$_POST['directorytypes'] = $directorypress_object->current_directorytype->id;
			}
			$map_handler->init($_POST);
			wp_reset_postdata();

			$map_markers = $map_handler->map->locations_option_array;
			if (!empty($_POST['map_listings'])) {
				$map_listings = $map_handler->map->buildListingsContent();
			}
		}
		
		$listings_html = '';
		if ((!isset($_POST['without_listings']) || !$_POST['without_listings'])) {
			$shortcode_atts = array_merge(array(
					'perpage' => 10,
					'onepage' => 0,
					'has_sticky_has_featured' => 0,
					'order_by' => 'post_date',
					'order' => 'DESC',
					'hide_order' => 0,
					'hide_count' => 0,
					'hide_paginator' => 0,
					'scrolling_paginator' => 0,
					'show_views_switcher' => 1,
					'listings_view_type' => 'list',
					'listings_view_grid_columns' => 2,
					'author' => 0,
					'paged' => 1,
					'scroll' => 0,
					'scroller_nav_style' => 2,
					'template' => 'partials/listing/wrapper.php',
			), $_POST);

			if (isset($map_handler->map->locations_array) && $map_handler->map->locations_array) {
				$post_ids = array();
				foreach ($map_handler->map->locations_array AS $location)
					$post_ids[] = $location->post_id;
				$shortcode_atts['post__in'] = $post_ids;
			} else {
				$shortcode_atts['post__in'] = 0;
			}
			
			if (!isset($_POST['directorytypes'])) {
				$shortcode_atts['directorytypes'] = $directorypress_object->current_directorytype->id;
			}

			$directorypress_listings_handler = new directorypress_listings_handler();
			$directorypress_listings_handler->init($shortcode_atts);
			$directorypress_listings_handler->hash = $hash;
		
			$listings_html = directorypress_display_template('partials/listing/wrapper.php', array('public_handler' => $directorypress_listings_handler), true);
			wp_reset_postdata();
		}
		
		$out['html'] = $listings_html;
		$out['map_markers'] = $map_markers;
		$out['map_listings'] = $map_listings;

		echo json_encode($out);
	
		die();
	}
	
	public function get_map_marker_info() {
		global $directorypress_object, $wpdb;

		if (isset($_POST['location_id']) && is_numeric($_POST['location_id'])) {
			$location_id = sanitize_text_field($_POST['location_id']);

			$row = $wpdb->get_row("SELECT * FROM {$wpdb->directorypress_locations_relation} WHERE id=".$location_id, ARRAY_A);

			if ($row && $row['location_id'] || $row['map_coords_1'] != '0.000000' || $row['map_coords_2'] != '0.000000' || $row['address_line_1'] || $row['zip_or_postal_index']) {
				$listing = new directorypress_listing;
				if ($listing->directorypress_init_lpost_listing($row['post_id'])) {
					$location = new directorypress_location($row['post_id']);
					$location_settings['id'] = directorypress_get_input_value($row, 'id');
					$location_settings['selected_location'] = directorypress_get_input_value($row, 'location_id');
					$location_settings['address_line_1'] = directorypress_get_input_value($row, 'address_line_1');
					$location_settings['address_line_2'] = directorypress_get_input_value($row, 'address_line_2');
					$location_settings['zip_or_postal_index'] = directorypress_get_input_value($row, 'zip_or_postal_index');
					$location_settings['additional_info'] = directorypress_get_input_value($row, 'additional_info');
					if(directorypress_has_map()){
						$location_settings['manual_coords'] = directorypress_get_input_value($row, 'manual_coords');
						$location_settings['map_coords_1'] = directorypress_get_input_value($row, 'map_coords_1');
						$location_settings['map_coords_2'] = directorypress_get_input_value($row, 'map_coords_2');
						$location_settings['map_icon_file'] = directorypress_get_input_value($row, 'map_icon_file');
					}
					
					$location->create_location_from_array($location_settings);
					global $DIRECTORYPRESS_ADIMN_SETTINGS;
					$logo_image = '';
					if ($listing->logo_image) {
							
							$width= 250;
							$height= 150;
							$image_src_array = wp_get_attachment_image_src($listing->logo_image, 'full');
							$image_src = $image_src_array[0];
							$param = array(
								'width' => $width,
								'height' => $height,
								'crop' => true
							);
							
							$logo_image = '<img alt="'.$listing->title().'" src="'. bfi_thumb($image_src, $param).'" width="'.$width.'" height="'.$height.'" />';
							
						} elseif ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_nologo'] && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']) {
							$image_src = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url'];
							$param = array(
								'width' => $width,
								'height' => $height,
								'crop' => true
							);
							$logo_image = '<img alt="'.$listing->title().'" src="'. bfi_thumb($image_src, $param).'" width="'.$width.'" height="'.$height.'" />';
							
						}
						
					$listing_link = get_permalink($listing->post->ID);
						
					$fields_output = $listing->set_directorypress_fields_for_map($directorypress_object->fields->get_map_fields(), $location);

					$locations_option_array = array(
							$location->id,
							$location->map_coords_1,
							$location->map_coords_2,
							$location->map_icon_file,
							$location->map_icon_color,
							$listing->map_zoom,
							$listing->title(),
							$logo_image,
							$listing_link,
							$fields_output,
							'post-' . $listing->post->ID,
					);
						
					echo json_encode($locations_option_array);
				}
			}
		}
		die();
	}
	
	public function select_field_icon() {
		directorypress_display_template('views/directorypress_fontawesome.php', array('icons' => directorypress_get_fa_icons_names()));
		die();
	}
	
	public function contact_form() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		$success = '';
		$error = '';
		if (!($type = $_REQUEST['type'])) {
			$error = __('The type of message required!', 'DIRECTORYPRESS');
		} else {
			check_ajax_referer('directorypress_' . $type . '_nonce', 'security');

			$validation = new directorypress_form_validation;
			if (!is_user_logged_in()) {
				$validation->set_rules('name', __('Contact name', 'DIRECTORYPRESS'), 'required');
				$validation->set_rules('email', __('Contact email', 'DIRECTORYPRESS'), 'required|valid_email');
			}
			$validation->set_rules('listing_id', __('Listing ID', 'DIRECTORYPRESS'), 'required');
			$validation->set_rules('message', __('Message', 'DIRECTORYPRESS'), 'required|max_length[1500]');
			if ($validation->run()) {
				$listing = new directorypress_listing();
				if ($listing->directorypress_init_lpost_listing($validation->result_array('listing_id'))) {
					if (!is_user_logged_in()) {
						$name = $validation->result_array('name');
						$email = $validation->result_array('email');
					} else {
						$current_user = wp_get_current_user();
						$name = $current_user->display_name;
						$email = $current_user->user_email;
					}
					$message = $validation->result_array('message');
	
					if (directorypress_recaptcha_validated()) {
						if ($type == 'contact') {
							if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_custom_contact_email'] && $listing->contact_email){
								$send_to_email = $listing->contact_email;
							}else {
								$listing_owner = get_userdata($listing->post->post_author);
								$send_to_email = $listing_owner->user_email;
							}
						}elseif ($type == 'report') {
							$send_to_email = get_option('admin_email');
						}
	
						$headers[] = "From: $name <$email>";
						$headers[] = "Reply-To: $email";
						$headers[] = "Content-Type: text/html";

						$subject = sprintf(__('%s contacted you about your listing "%s"', 'DIRECTORYPRESS'), $name, $listing->title());
	
						$body = directorypress_display_template('emails/' . $type . '_form.php',
						array(
							'name' => $name,
							'email' => $email,
							'message' => $message,
							'listing_title' => $listing->title(),
							'listing_url' => get_permalink($listing->post->ID)
						), true);

						do_action('directorypress_send_' . $type . '_email', $listing, $send_to_email, $subject, $body, $headers);
					
						if (directorypress_mail($send_to_email, $subject, $body, $headers)) {
							unset($_POST['name']);
							unset($_POST['email']);
							unset($_POST['message']);
							$success = __('You message was sent successfully!', 'DIRECTORYPRESS');
						} else {
							$error = esc_attr__("An error occurred and your message wasn't sent!", 'DIRECTORYPRESS');
						}
						$listing_owner = get_userdata($listing->post->post_author);
						$to = $listing_owner->user_phone;
						if(directorypress_is_directorypress_twilio_active() && !empty($to)){
							directorypress_send_sms($to, $body);
						}
					} else {
						$error = esc_html__("Anti-bot test wasn't passed!", 'DIRECTORYPRESS');
					}
				}
			} else {
				$error = $validation->error_array();
			}
			echo json_encode(array('error' => $error, 'success' => $success));
	
			die();
		}
	}
	
	public function widget_contact_form() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		$success = '';
		$error = '';
		
		check_ajax_referer('directorypress_widget_contact_form_nonce', 'security');

			$validation = new directorypress_form_validation;
			$validation->set_rules('name', __('Contact name', 'DIRECTORYPRESS'), 'required');
			$validation->set_rules('email', __('Contact email', 'DIRECTORYPRESS'), 'required|valid_email');
			
			$validation->set_rules('listing_id', __('Listing ID', 'DIRECTORYPRESS'), 'required');
			$validation->set_rules('message', __('Message', 'DIRECTORYPRESS'), 'required|max_length[1500]');
			if ($validation->run()) {
				$listing = new directorypress_listing();
				if ($listing->directorypress_init_lpost_listing($validation->result_array('listing_id'))) {
					
					$name = $validation->result_array('name');
					$email = $validation->result_array('email');
					$message = $validation->result_array('message');
	
					if (directorypress_recaptcha_validated()) {
						
						if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_custom_contact_email'] && $listing->contact_email){
							$send_to_email = $listing->contact_email;
						}else {
							$listing_owner = get_userdata($listing->post->post_author);
							$send_to_email = $listing_owner->user_email;
						}
						
	
						$headers[] = "From: $name <$email>";
						$headers[] = "Reply-To: $email";
						$headers[] = "Content-Type: text/html";

						$subject = sprintf(__('%s contacted you about "%s"', 'DIRECTORYPRESS'), $name, $listing->title());
	
						$body = directorypress_display_template('emails/contact_form_widget.php',
						array(
							'name' => $name,
							'email' => $email,
							'message' => $message,
							'listing_title' => $listing->title(),
							'listing_url' => get_permalink($listing->post->ID)
						), true);

						do_action('directorypress_send_contact_email', $listing, $send_to_email, $subject, $body, $headers);
					
						if (directorypress_mail($send_to_email, $subject, $body, $headers)) {
							unset($_POST['name']);
							unset($_POST['email']);
							unset($_POST['message']);
							$success = __('You message was sent successfully!', 'DIRECTORYPRESS');
						} else {
							$error = esc_attr__("An error occurred and your message wasn't sent!", 'DIRECTORYPRESS');
						}
						$listing_owner = get_userdata($listing->post->post_author);
						$to = $listing_owner->user_phone;
						if(directorypress_is_directorypress_twilio_active() && !empty($to)){
							directorypress_send_sms($to, $body);
						}
					} else {
						$error = esc_html__("Anti-bot test wasn't passed!", 'DIRECTORYPRESS');
					}
				}
			} else {
				$error = $validation->error_array();
			}
			echo json_encode(array('error' => $error, 'success' => $success));
	
			die();
		
	}
	
	public function keywords_search() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		$validation = new directorypress_form_validation;
		$validation->set_rules('term', __('Search term', 'DIRECTORYPRESS'));
		$validation->set_rules('directorytypes', __('Directorytypes IDs', 'DIRECTORYPRESS'));
		if ($validation->run()) {
			$term = $validation->result_array('term');
			$directorytypes = $validation->result_array('directorytypes'); 
			
			$default_orderby_args = array('order_by' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_orderby'], 'order' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_order']);
			$order_args = apply_filters('directorypress_order_args', array(), $default_orderby_args);
		
			$args = array(
					'post_type' => DIRECTORYPRESS_POST_TYPE,
					'post_status' => 'publish',
					'posts_per_page' => apply_filters('directorypress_ajax_search_listings_number', 10),
					's' => $term
			);
			$args = array_merge($args, $order_args);
			
			if ($directorytypes) {
				$directorytypes = explode(',', $directorytypes);
				$args = directorypress_set_directory_args($args, $directorytypes);
			}

			$query = new WP_Query($args);
			
			
			if (directorypress_is_relevanssi_search()) {
				$query->query_vars['s'] = $term;
				$query->query_vars['posts_per_page'] = apply_filters('directorypress_ajax_search_listings_number', 10);
				relevanssi_do_query($query);
			}
			
			$listings_json = array();
			while ($query->have_posts()) {
				$query->the_post();
			
				$listing = new directorypress_listing;
				$listing->directorypress_init_lpost_listing(get_post());
				
				$target = apply_filters('directorypress_listing_title_search_target', 'target="_blank"');
				$title = '<strong><a href="' . get_the_permalink() . '" ' . $target . ' title="' . esc_attr__("open listing", "DIRECTORYPRESS") . '" rel="nofollow">' . $listing->title() . '</a></strong>';
				

				$listing_json_field = array();
				$listing_json_field['title'] = apply_filters('directorypress_listing_title_search_html', $title, $listing);
				$listing_json_field['name'] = $listing->title();
				$listing_json_field['url'] = get_the_permalink();
				$listing_json_field['icon'] = $listing->get_logo_url(array(40, 40));
				$listing_json_field['sublabel'] = directorypress_trim_content(10, 0, false, false, '...');
				$listings_json[] = $listing_json_field;
			}
			
			echo json_encode(array('listings' => $listings_json));
		}
		
		die();
	}
}
?>