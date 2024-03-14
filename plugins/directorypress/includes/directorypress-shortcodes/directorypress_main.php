<?php
require_once DIRECTORYPRESS_PATH . 'includes/pages/index.php';
require_once DIRECTORYPRESS_PATH . 'includes/pages/category.php';
require_once DIRECTORYPRESS_PATH . 'includes/pages/location.php';
require_once DIRECTORYPRESS_PATH . 'includes/pages/tags.php';
require_once DIRECTORYPRESS_PATH . 'includes/pages/search.php';
require_once DIRECTORYPRESS_PATH . 'includes/pages/bookmark.php';
class directorypress_directory_handler extends directorypress_public {
	public $is_home = false;
	public $is_search = false;
	public $is_single = false;
	public $is_category = false;
	public $is_location = false;
	public $is_tag = false;
	public $is_favourites = false;
	public $breadcrumbs = array();
	public $custom_home = false;
	public $is_map_on_page = 1;
	public $directorypress_client = 'directorypress_directory_handler';
	public $form_layout = 'horizontal';

	public function init($shortcode_atts = array(), $shortcode = 'directorypress-main') {
		global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS;
		
		parent::init($shortcode_atts);
		
		if (isset($shortcode_atts['custom_home']) && $shortcode_atts['custom_home']) {
			$this->custom_home = true;
		}
		if(isset($shortcode_atts['archive_top_banner']) && !empty($shortcode_atts['archive_top_banner'])){
			if(directorypress_is_base64_encoded($shortcode_atts['archive_top_banner'])){
				$this->archive_top_banner = rawurldecode( base64_decode( $shortcode_atts['archive_top_banner'] ) );
			}else{
				$this->archive_top_banner = $shortcode_atts['archive_top_banner'];
			}
		}else{
			$this->archive_top_banner = '';
		}
		if(isset($shortcode_atts['archive_below_search_banner']) && !empty($shortcode_atts['archive_below_search_banner'])){
			if(directorypress_is_base64_encoded($shortcode_atts['archive_below_search_banner'])){
				$this->archive_below_search_banner = rawurldecode( base64_decode( $shortcode_atts['archive_below_search_banner'] ) );
			}else{
				$this->archive_below_search_banner = $shortcode_atts['archive_below_search_banner'];
			}
		}else{
			$this->archive_below_search_banner = '';
		}
		
		if(isset($shortcode_atts['archive_below_category_banner']) && !empty($shortcode_atts['archive_below_category_banner'])){
			if(directorypress_is_base64_encoded($shortcode_atts['archive_below_category_banner'])){
				$this->archive_below_category_banner = rawurldecode( base64_decode( $shortcode_atts['archive_below_category_banner'] ) );
			}else{
				$this->archive_below_category_banner = $shortcode_atts['archive_below_category_banner'];
			}
		}else{
			$this->archive_below_category_banner = '';
		}
		
		if(isset($shortcode_atts['archive_below_locations_banner']) && !empty($shortcode_atts['archive_below_locations_banner'])){
			if(directorypress_is_base64_encoded($shortcode_atts['archive_below_locations_banner'])){
				$this->archive_below_locations_banner = rawurldecode( base64_decode( $shortcode_atts['archive_below_locations_banner'] ) );
			}else{
				$this->archive_below_locations_banner = $shortcode_atts['archive_below_locations_banner'];
			}
		}else{
			$this->archive_below_locations_banner = '';
		}
		
		if(isset($shortcode_atts['archive_below_listings_banner']) && !empty($shortcode_atts['archive_below_listings_banner'])){
			if(directorypress_is_base64_encoded($shortcode_atts['archive_below_listings_banner'])){
				$this->archive_below_listings_banner = rawurldecode( base64_decode( $shortcode_atts['archive_below_listings_banner'] ) );
			}else{
				$this->archive_below_listings_banner = $shortcode_atts['archive_below_listings_banner'];
			}
		}else{
			$this->archive_below_listings_banner = '';
		}
		
		//$this->archive_below_search_banner =  (isset($shortcode_atts['archive_below_search_banner']) && !empty($shortcode_atts['archive_below_search_banner']))? rawurldecode( base64_decode( $shortcode_atts['archive_below_search_banner'] ) ): '';
		//$this->archive_below_category_banner = (isset($shortcode_atts['archive_below_category_banner']) && !empty($shortcode_atts['archive_below_category_banner']))? rawurldecode( base64_decode( $shortcode_atts['archive_below_category_banner'] ) ): '';
		//$this->archive_below_locations_banner = (isset($shortcode_atts['archive_below_locations_banner']) && !empty($shortcode_atts['archive_below_locations_banner']))? rawurldecode( base64_decode( $shortcode_atts['archive_below_locations_banner'] ) ): '';
		//$this->archive_below_listings_banner = (isset($shortcode_atts['archive_below_listings_banner']) && !empty($shortcode_atts['archive_below_listings_banner']))? rawurldecode( base64_decode( $shortcode_atts['archive_below_listings_banner'] ) ): '';
		
		if (get_query_var('page')) {
			$paged = get_query_var('page');
		} elseif (get_query_var('paged')) {
			$paged = get_query_var('paged');
		} else {
			$paged = 1;
		}
		$hide = 0;
		$common_search_args = array(
				'show_radius_search' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_radius_search'],
				'radius' =>  (int)$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_radius_search_default'],
				'show_keywords_category_combo' => $DIRECTORYPRESS_ADIMN_SETTINGS['show_keywords_category_combo'],
				'show_categories_search' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_categories_search'],
				'categories_search_depth' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_categories_search_depth'],
				'show_keywords_search' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_keywords_search'],
				'keywords_ajax_search' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['keywords_ajax_search'],
				'keywords_search_examples' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['keywords_search_examples'],
				'show_default_filed_label' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['show_default_filed_label'],
				'show_locations_search' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_locations_search'],
				'locations_search_depth' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['locations_search_depth'],
				'show_address_search' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_address_search'],
				'gap_in_fields' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['gap_in_fields'],
				'radius_field_width' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['radius_field_width'],
				'location_field_width' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['location_field_width'],
				'keyword_field_width' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['keyword_field_width'],
				'button_field_width' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['button_field_width'],
				'search_button_margin_top' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['button_field_margin_top'],
				'scroll_to' => 'listings',
				'search_fields' => (isset($shortcode_atts['search_fields']))? $shortcode_atts['search_fields'] : '',
				'search_fields_advanced' =>  (isset($shortcode_atts['search_fields_advanced']))? $shortcode_atts['search_fields_advanced'] : '',
				'hide_search_button' => (int)$DIRECTORYPRESS_ADIMN_SETTINGS['hide_search_button'],
		);
		
		if($DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] == 2){
			$this->form_layout = 'vertical';
		}else{
			$this->form_layout = apply_filters("directorypress_archive_form_layout" , $this->form_layout);
		}
		$search_args = array_merge(array(
				'custom_home' => 0,
				'directorytype' => $directorypress_object->current_directorytype->id,
				'form_layout' => $this->form_layout,
			), $common_search_args
		);
		if (directorypress_has_map()){
			$map_args = array_merge(array(
					'search_on_map_open' => 0,
					'start_zoom' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_start_map_zoom'],
					'start_address' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_start_address'],
					'start_latitude' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_start_latitude'],
					'start_longitude' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_start_longitude'],
					'geolocation' => 0,
				), $common_search_args
			);
		}else{
			$map_args = array();
		}

		if (get_query_var('listing-directorypress') || (($shortcode == DIRECTORYPRESS_LISTING_SHORTCODE || $shortcode == 'directorypress-listing') && isset($shortcode_atts['listing_id']) && is_numeric($shortcode_atts['listing_id']))) {
			
			if (get_query_var('listing-directorypress')) {
				$args = array(
						'post_type' => DIRECTORYPRESS_POST_TYPE,
						//'post_status' => 'publish',
						'name' => get_query_var('listing-directorypress'),
						'posts_per_page' => 1,
				);
			} else {
				$args = array(
						'post_type' => DIRECTORYPRESS_POST_TYPE,
						//'post_status' => 'publish',
						'p' => $shortcode_atts['listing_id'],
						'posts_per_page' => 1,
				);
			}
			$this->query = new WP_Query($args);
			$this->processQuery(true);
			// Map uID must be absolutely unique on single listing page
			$this->hash = md5(time());
			
			if (count($this->listings)) {
				$listings_array = $this->listings;
				$listing = array_shift($listings_array);
				$this->listing = $listing;
				if ($this->listing->post->post_status != 'publish' && !current_user_can('edit_others_posts'))
					wp_redirect(directorypress_directorytype_url());

				global $wp_rewrite;
				if ($shortcode != 'directorypress-listing' && $wp_rewrite->using_permalinks() && (($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_permalinks_structure'] == 'category_slug' || $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_permalinks_structure'] == 'location_slug' || $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_permalinks_structure'] == 'tag_slug'))) {
					switch ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_permalinks_structure']) {
						case 'category_slug':
							if ($terms = get_the_terms($this->listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX)) {
								$term_number = 0;
								if (count($terms) > 1) {
									foreach ($terms AS $term) {
										$term_number++;
										if ($parents = directorypress_get_term_parents_slugs($term->term_id, DIRECTORYPRESS_CATEGORIES_TAX)) {
											$uri = implode('/', $parents);
											if ($uri == get_query_var('tax-slugs-directorypress')) {
												break;
											}
										}
									}
								}
								
								$term = array_shift($terms);
								$uri = '';
								if ($parents = directorypress_get_term_parents_slugs($term->term_id, DIRECTORYPRESS_CATEGORIES_TAX))
									$uri = implode('/', $parents);
								if ($uri != get_query_var('tax-slugs-directorypress')) {
									$permalink = get_the_permalink($this->listing->post->ID);
									if ($term_number > 1) {
										$permalink = add_query_arg('term_number', $term_number, $permalink);
									}
									wp_redirect($permalink, 301);
									die();
								}
							}
							break;
						case 'location_slug':
							if ($terms = get_the_terms($this->listing->post->ID, DIRECTORYPRESS_LOCATIONS_TAX)) {
								$term_number = 0;
								if (count($terms) > 1) {
									foreach ($terms AS $term) {
										$term_number++;
										if ($parents = directorypress_get_term_parents_slugs($term->term_id, DIRECTORYPRESS_LOCATIONS_TAX)) {
											$uri = implode('/', $parents);
											if ($uri == get_query_var('tax-slugs-directorypress')) {
												break;
											}
										}
									}
								}
								
								$term = array_shift($terms);
								$uri = '';
								if ($parents = directorypress_get_term_parents_slugs($term->term_id, DIRECTORYPRESS_LOCATIONS_TAX))
									$uri = implode('/', $parents);
								if ($uri != get_query_var('tax-slugs-directorypress')) {
									$permalink = get_the_permalink($this->listing->post->ID);
									if ($term_number > 1) {
										$permalink = add_query_arg('term_number', $term_number, $permalink);
									}
									wp_redirect($permalink, 301);
									die();
								}
							}
							break;
						case 'tag_slug':
							if (($terms = get_the_terms($post->ID, DIRECTORYPRESS_TAGS_TAX)) && ($term = array_shift($terms))) {
								if ($term->slug != get_query_var('tax-slugs-directorypress')) {
									wp_redirect(get_the_permalink($this->listing->post->ID), 301);
									die();
								}
							}
							break;
					}
				}
				
				if (!wp_doing_ajax()) {
					$this->listing->increase_click_count();
				}
				
				$this->is_single = true;
				
				
				$this->template = 'partials/single-listing/single-wrapper.php';
				
				
				// here directorytype ID we will take from post meta
				$directorypress_object->setup_current_page_directorytype();

				$this->page_title = $listing->title();

				if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_breadcrumbs']) {
					if (!$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_hide_home_link_breadcrumb'])
						$this->breadcrumbs[] = '<li><a href="' . directorypress_directorytype_url() . '">' . __('Home', 'DIRECTORYPRESS') . '</a></li>';
					switch ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_breadcrumbs_mode']) {
						case 'category':
							if ($terms = get_the_terms($this->listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX)) {
								if (!empty($_GET['term_number']) && isset($terms[$_GET['term_number']-1]) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_permalinks_structure'] == 'category_slug') {
									$term = $terms[esc_attr($_GET['term_number'])-1];
								} else {
									$term = array_shift($terms);
								}
								$this->breadcrumbs = array_merge($this->breadcrumbs, directorypress_get_term_parents($term, DIRECTORYPRESS_CATEGORIES_TAX, true, true));
							}
							break;
						case 'location':
							if ($terms = get_the_terms($this->listing->post->ID, DIRECTORYPRESS_LOCATIONS_TAX)) {
								if (!empty($_GET['term_number']) && isset($terms[$_GET['term_number']-1]) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_permalinks_structure'] == 'location_slug') {
									$term = $terms[esc_attr($_GET['term_number'])-1];
								} else {
									$term = array_shift($terms);
								}
								$this->breadcrumbs = array_merge($this->breadcrumbs, directorypress_get_term_parents($term, DIRECTORYPRESS_LOCATIONS_TAX, true, true));
							}
							break;
					}
					$this->breadcrumbs[] = $listing->title();
				}

				if ($DIRECTORYPRESS_ADIMN_SETTINGS['message_system'] == 'email_messages' && defined('WPCF7_VERSION') && directorypress_wpml_supported_settings('directorypress_listing_contact_form_7')) {
					add_filter('wpcf7_form_action_url', array($this, 'directorypress_add_listing_id_to_wpcf7'));
					add_filter('wpcf7_form_hidden_fields', array($this, 'directorypress_add_listing_id_to_wpcf7_field'));
				}
				
				add_filter('language_attributes', array($this, 'add_opengraph_doctype'));
				// Disable OpenGraph in Jetpack
				if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_share_buttons']) {
					add_filter('jetpack_enable_open_graph', '__return_false', 99);
				}
				add_action('wp_head', array($this, 'save_global_post'), -1001);
				add_action('wp_head', array($this, 'change_global_post'), -1000);
				add_action('wp_head', array($this, 'back_global_post'), 1000);
				add_action('wp_head', array($this, 'insert_opengraph_metadat'), -10);
				if (function_exists('rel_canonical')) {
					remove_action('wp_head', 'rel_canonical');
				}
				// replace the default WordPress canonical URL function with your own
				add_action('wp_head', array($this, 'rel_canonical_with_custom_tag_override'));
			} else {
				$this->set404();
			}
		} elseif ($directorypress_object->action == 'search') {
			
			do_action('archive_search_page', $this, $search_args, $shortcode_atts, $map_args);
		
		} elseif (get_query_var('category-directorypress')) {
			
			do_action('archive_category_page', $this, $search_args, $shortcode_atts, $map_args);
		
		} elseif (get_query_var('location-directorypress')) {
			
			do_action('archive_location_page', $this, $search_args, $shortcode_atts, $map_args);
		
		} elseif (get_query_var('tag-directorypress')) {
			
			do_action('archive_tags_page', $this, $search_args, $shortcode_atts, $map_args);
		
		} elseif ($directorypress_object->action == 'myfavourites') {
			
			do_action('archive_bookmark_page', $this, $search_args, $shortcode_atts, $map_args);
		
		} elseif (!$directorypress_object->action && $shortcode == DIRECTORYPRESS_MAIN_SHORTCODE) {
			do_action('archive_index_page', $this, $search_args, $shortcode_atts, $map_args, $shortcode);
		}
		
		$this->args['directorytypes'] = $directorypress_object->current_directorytype->id;
		$this->args['is_home'] = $this->is_home;
		$this->args['paged'] = $paged;
		$this->args['custom_home'] = (int)$this->custom_home;

		$this->args['onepage'] = 0;
		$this->args['hide_paginator'] = 0;
		$this->args['hide_count'] = 0;
		$this->args['scroll'] = 0;
		$this->args['hide_content'] = directorypress_get_input_value($shortcode_atts, 'hide_content', 0);
		if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_orderby_links']) {
			$this->args['hide_order'] = 0;
		}else{
			$this->args['hide_order'] = 1;
		}
		if($this->is_favourites){
			$this->args['show_views_switcher'] = 0;
		}else{
			$this->args['show_views_switcher'] = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_views_switcher'];
		}
	
		$this->args['listings_view_type'] = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_views_switcher_default'];
		//$this->listings_view_type = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_views_switcher_default'];
		$listing_post_style = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_post_style'];
		$this->args['listing_post_style'] = apply_filters('directorypress_archive_page_grid_style', $listing_post_style);
			
		$this->args['listings_view_grid_columns'] = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_views_switcher_grid_columns'];
		$this->args['masonry_layout'] = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_grid_masonry_display'];
		$this->args['2col_responsive'] = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_responsive_grid'];
		$this->args['scroller_nav_style'] = 2;
		$this->args['scroll'] = 0;
		$this->args['owl_nav'] = false;
		$this->args['loop'] = false;
		$this->args['autoplay'] = false;
		$this->args['autoplay_speed'] = 1000;
		$this->args['desktop_items'] = 1;
		$this->args['tab_items'] = 1;
		$this->args['mobile_items'] = 1;
		$this->args['scrolling_paginator'] = 0;
		
		$this->listing_view = directorypress_listing_view_type($this->args['listings_view_type'], $this->hash);
		

		add_action('get_header', array($this, 'configure_seo_filters'), 2);
		
		if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_overwrite_page_title']) {
			add_filter('the_title', array($this, 'setThemePageTitle'), 10, 2);
		}
	
		// adapted for WPML
		add_filter('icl_ls_languages', array($this, 'adapt_wpml_urls'));
		//add_filter('WPML_alternate_hreflang', array($this, 'alternate_hreflang'), 10, 2);

		// this is possible to build custom home page instead of static set of blocks
		if (!$this->is_single && $this->custom_home)
			$this->template = 'partials/listing/wrapper.php';
		
		$this->template = apply_filters('directorypress_public_template', $this->template, $this);

		apply_filters('directorypress_directory_handler_construct', $this);
	}
	
	public function set404() {
		global $wp_query;
		$wp_query->set_404();
		status_header(404);
	}

	public function setThemePageTitle($title, $id = null) {
		global $directorypress_object;

		if (!is_admin() && !in_the_loop() && is_page() && ($directorypress_object->directorypress_archive_page_id == $id || in_array($id, $directorypress_object->directorypress_all_listing_pages))) {
			return $this->getPageTitle();
		} else {
			return $title;
		}
	}

	public function tempLangToWPML () {
		return $this->temp_lang;
	}
	
	// adapted for WPML
	public function adapt_wpml_urls($w_active_languages) {
		global $sitepress, $directorypress_object;

		// WPML will not switch language using $sitepress->switch_lang() function when there is 'lang=' parameter in the URL, so we have to use such hack
		if ($sitepress->get_option('language_negotiation_type') == 3)
			remove_all_filters('icl_current_language');

		foreach ($w_active_languages AS $key=>&$language) {
			$sitepress->switch_lang($language['language_code']);
			$this->temp_lang = $language['language_code'];
			add_filter('icl_current_language', array($this, 'tempLangToWPML'));
			$directorypress_object->directorypress_get_system_pages();
			$directorypress_object->directorypress_get_archive_page();

			$is_directorypress_page = false;
			$directorypress_page_url = false;
			if ($this->is_single || $this->is_category || $this->is_location || $this->is_tag || $this->is_favourites) {
				$is_directorypress_page = true;
			}

			if ($this->is_single && ($tlisting_post_id = apply_filters('wpml_object_id', $this->listing->post->ID, DIRECTORYPRESS_POST_TYPE, false, $language['language_code']))) {
				$directorypress_page_url = get_permalink($tlisting_post_id);
			}
			if ($this->is_category && ($tterm_id = apply_filters('wpml_object_id', $this->category->term_id, DIRECTORYPRESS_CATEGORIES_TAX, false, $language['language_code']))) {
				$tterm = get_term($tterm_id, DIRECTORYPRESS_CATEGORIES_TAX);
				$directorypress_page_url = get_term_link($tterm);
			}
			
			if ($this->is_location && ($tterm_id = apply_filters('wpml_object_id', $this->location->term_id, DIRECTORYPRESS_LOCATIONS_TAX, false, $language['language_code']))) {
				$tterm = get_term($tterm_id, DIRECTORYPRESS_LOCATIONS_TAX);
				$directorypress_page_url = get_term_link($tterm, DIRECTORYPRESS_LOCATIONS_TAX);
			}
			if ($this->is_tag && ($tterm_id = apply_filters('wpml_object_id', $this->tag->term_id, DIRECTORYPRESS_TAGS_TAX, false, $language['language_code']))) {
				$tterm = get_term($tterm_id, DIRECTORYPRESS_TAGS_TAX);
				$directorypress_page_url = get_term_link($tterm, DIRECTORYPRESS_TAGS_TAX);
			}
			if ($this->is_favourites) {
				$directorypress_page_url = directorypress_directorytype_url(array('directorypress_action' => 'myfavourites'));
			}

			// show links only to pages, which have translations
			if ($is_directorypress_page) {
				if ($directorypress_page_url)
					$language['url'] = $directorypress_page_url;
				else
					unset($w_active_languages[$key]);
			}

			remove_filter('icl_current_language', array($this, 'tempLangToWPML'));
		}
		$sitepress->switch_lang(ICL_LANGUAGE_CODE);
		$directorypress_object->directorypress_get_system_pages();
		$directorypress_object->directorypress_get_archive_page();
		return $w_active_languages;
	}
	
	// Add listing ID to query string while rendering Contact Form 7
	public function directorypress_add_listing_id_to_wpcf7($url) {
		if ($this->is_single) {
			$url = esc_url(add_query_arg('listing_id', $this->listing->post->ID, $url));
		}
		
		return $url;
	}
	// Add listing ID to hidden fields while rendering Contact Form 7
	public function directorypress_add_listing_id_to_wpcf7_field($fields) {
		if ($this->is_single) {
			$fields["listing_id"] = $this->listing->post->ID;
		}
		
		return $fields;
	}

	public function configure_seo_filters() {
		if ($this->is_home || $this->is_single || $this->is_search || $this->is_category || $this->is_location || $this->is_tag || $this->is_favourites) {
			// When using WP 4.4, just use the new hook.
			add_filter('pre_get_document_title', array($this, 'page_title'), 16);
			add_filter('wp_title', array($this, 'page_title'), 10, 2);
		}
	}
	
	public function page_title($title, $separator = '|') {
		
		if ($this->getPageTitle()){
			$title = $this->getPageTitle() . ' ' . $separator . ' ';
		}
		if (directorypress_wpml_supported_settings('directorypress_directory_title')){ 
			$title .= directorypress_wpml_supported_settings('directorypress_directory_title');
		}else{
			$title .= get_option('blogname');
		}
		
		return $title;
	}

	// rewrite canonical URL
	public function rel_canonical_with_custom_tag_override() {
		echo '<link rel="canonical" href="' . get_permalink($this->listing->post->ID) . '" />
';
	}
	
	// Adding the Open Graph in the Language Attributes
	public function add_opengraph_doctype($output) {
		return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
	}
	
	// Temporarily change global $post variable in head
	public function change_global_post() {
		global $post;
		$post = $this->listing->post;
	}
	
	// Lets add Open Graph Meta Info
	public function insert_opengraph_metadat() {
		echo '<meta property="og:type" content="article" data-w2dc-og-meta="true" />';
		echo '<meta property="og:title" content="' . esc_attr($this->opengraph_title()) . '" />';
	
		echo '<meta property="og:description" content="' . esc_attr($this->opengraph_description()) . '" />';
		echo '<meta property="og:url" content="' . esc_url($this->opengraph_url()) . '" />';
		echo '<meta property="og:site_name" content="' . esc_attr($this->opengraph_site_name()) . '" />';
		if ($thumbnail_src = $this->opengraph_image()) {
			echo '<meta property="og:image" content="' . esc_url($thumbnail_src) . '" />';
		}
	
		add_filter('wpseo_opengraph_title', array($this, 'opengraph_title'), 10, 2);
		add_filter('wpseo_opengraph_desc', array($this, 'opengraph_description'), 10, 2);
		add_filter('wpseo_opengraph_url', array($this, 'opengraph_url'), 10, 2);
		add_filter('wpseo_opengraph_image', array($this, 'opengraph_image'), 10, 2);
		add_filter('wpseo_opengraph_site_name', array($this, 'opengraph_site_name'), 10, 2);
	}
	
	public function opengraph_title() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		return esc_attr($this->listing->title()) . ' - ' . $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_directory_title'];
	}
	
	public function opengraph_description() {
		if ($this->listing->post->post_excerpt) {
			$excerpt = $this->listing->post->post_excerpt;
		} else {
			$excerpt = $this->listing->get_excerpt_from_content();
		}
	
		return esc_attr($excerpt);
	}
	
	public function opengraph_url() {
		return get_permalink($this->listing->post->ID);
	}
	
	public function opengraph_site_name() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		return $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_directory_title'];
	}
	
	public function opengraph_image() {
		return $this->listing->get_logo_url();
	}
	public function display() {
		$output =  directorypress_display_template($this->template, array('public_handler' => $this), true);
		wp_reset_postdata();

		return $output;
	}
}

add_action('init', 'directorypress_handle_wpcf7');
function directorypress_handle_wpcf7() {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if (defined('WPCF7_VERSION')) {
		if ($DIRECTORYPRESS_ADIMN_SETTINGS['message_system'] == 'email_messages' && defined('WPCF7_VERSION') && directorypress_wpml_supported_settings('directorypress_listing_contact_form_7')) {
			add_filter('wpcf7_mail_components', 'directorypress_wpcf7_handle_email', 10, 2);
		}
			
		function directorypress_wpcf7_handle_email($WPCF7_components, $WPCF7_currentform) {
			if (isset($_REQUEST['listing_id'])) {
				$post = get_post($_REQUEST['listing_id']);
	
				$mail = $WPCF7_currentform->prop('mail');
				// DO not touch mail_2
				if ($mail['recipient'] == $WPCF7_components['recipient']) {
					if ($post && isset($_POST['_wpcf7']) && preg_match_all('/'.get_shortcode_regex().'/s', directorypress_wpml_supported_settings('directorypress_listing_contact_form_7'), $matches)) {
						foreach ($matches[2] AS $key=>$shortcode) {
							if ($shortcode == 'contact-form-7') {
								if ($attrs = shortcode_parse_atts($matches[3][$key])) {
									if (isset($attrs['id']) && $attrs['id'] == $_POST['_wpcf7']) {
										$contact_email = null;
										if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_custom_contact_email'] && ($listing = directorypress_get_listing($post)) && $listing->contact_email) {
											$contact_email = $listing->contact_email;
										} elseif (($listing_owner = get_userdata($post->post_author)) && $listing_owner->user_email) {
											$contact_email = $listing_owner->user_email;
										}
										if ($contact_email)
											$WPCF7_components['recipient'] = $contact_email;
									}
								}
							}
						}
					}
				}
			}
			return $WPCF7_components;
		}
	}
}

?>