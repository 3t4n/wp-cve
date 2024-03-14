<?php 
class directorypress_public {
	public $args = array();
	public $query;
	public $page_title;
	public $template;
	public $listings = array();
	public $search_form;
	public $map;
	public $paginator;
	public $breadcrumbs = array();
	public $base_url;
	public $messages = array();
	public $hash = null;
	public $packages_ids;
	public $do_initial_load = true;
	public $directorypress_client = 'directorypress_public_handler';
	public $scroll = 0;
	public $scroller_nav_style;
	
	public function __construct($args = array()) {
		apply_filters('directorypress_public_construct', $this);
	}
	
	public function init($attrs = array()) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		
		//$this->args['listing_post_style'] = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_post_style'];

		if (!$this->hash)
			if (isset($attrs['uid']) && $attrs['uid'])
				$this->hash = md5($attrs['uid']);
			else
				$this->hash = md5(get_class($this).serialize($attrs));
	}
	// Temporarily change global $post variable in head
	public function save_global_post() {
		global $post;
		$this->global_post = $post;
	}
	
	public function back_global_post() {
		global $post;
		$post = $this->global_post;
	}
	public function processQuery($load_map = true, $map_args = array()) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		
		$this->save_global_post();
		
		if (($this->getQueryVars('orderby') == 'meta_value_num' || $this->getQueryVars('orderby') == 'meta_value') && ($this->getQueryVars('meta_key') != '_order_date')) {
			$args = $this->getQueryVars();

			unset($args['taxonomy']);
			unset($args['term_id']);
			if (empty($args['s'])) {
				unset($args['s']);
			}
			
			$original_posts_per_page = $args['posts_per_page'];

			$ordered_posts_ids = get_posts(array_merge($args, array('fields' => 'ids', 'nopaging' => true)));
			
			$ordered_max_num_pages = ceil(count($ordered_posts_ids)/$original_posts_per_page) - (int) $ordered_posts_ids;

			$args['paged'] = $args['paged'] - $ordered_max_num_pages;
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = '_order_date';
			$args['order'] = 'DESC';
			$args['posts_per_page'] = $original_posts_per_page - $this->query->post_count;
			$all_posts_ids = get_posts(array_merge($args, array('fields' => 'ids', 'nopaging' => true)));
			$all_posts_count = count($all_posts_ids);
			

			if ($this->query->found_posts) {
				$args['post__not_in'] = array_map('intval', $ordered_posts_ids);
				if (!empty($args['post__in']) && is_array($args['post__in'])) {
					$args['post__in'] = array_diff($args['post__in'], $args['post__not_in']);
					if (!$args['post__in']) {
						$args['posts_per_page'] = 0;
					}
				}
			}

			$unordered_query = new WP_Query($args);
			

			if ($args['posts_per_page']) {
				$this->query->posts = array_merge($this->query->posts, $unordered_query->posts);
			}

			$this->query->post_count = count($this->query->posts);
			$this->query->found_posts = $all_posts_count;
			$this->query->max_num_pages = ceil($all_posts_count/$original_posts_per_page);
		}

		if ($load_map && directorypress_has_map()) {
			if (!isset($map_args['map_markers_is_limit']))
				$map_args['map_markers_is_limit'] = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_map_markers_is_limit'];
			$this->map = new directorypress_maps($map_args, $this->directorypress_client);
			$this->map->set_unique_id($this->hash);
			
			if (!$map_args['map_markers_is_limit'] && !$this->map->is_ajax_markers_management()) {
				$this->collectAllLocations();
			}
		}
		
		while ($this->query->have_posts()) {
			$this->query->the_post();

			$listing = new directorypress_listing;
			$listing->is_widget = (isset($this->args['is_widget']))? $this->args['is_widget']: 0;
			$listing->directorypress_init_lpost_listing(get_post());
			if(isset($this->args['listing_post_style']) && !empty($this->args['listing_post_style'])){
				$listing->listing_post_style = apply_filters('directorypress_listing_shortcode_grid_style', $this->args['listing_post_style'], $this->args['custom_settings'] );
			}else{
				$listing->listing_post_style = apply_filters('directorypress_archive_page_grid_style', $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_post_style']);
			}
			
			$listing->listing_image_width = (isset($this->args['listing_image_width'])) ? $this->args['listing_image_width']: $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_logo_width'];
			$listing->listing_image_height = (isset($this->args['listing_image_height'])) ? $this->args['listing_image_height']: $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_logo_height'];
			$listing->fchash = $this->hash;
			$listing->listings_view_type = (isset($this->args['listings_view_type'])) ? $this->args['listings_view_type']: $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_views_switcher_default'];
			$listing->listing_view = directorypress_listing_view_type($listing->listings_view_type, $listing->fchash);
			$listing->listing_has_featured_tag_style = $DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_style'];
			if (directorypress_has_map()){
				if ($load_map && $map_args['map_markers_is_limit'] && !$this->map->is_ajax_markers_management())
					$this->map->collect_locations($listing);
			
			}
			
			$this->listings[get_the_ID()] = $listing;
		}
		
		global $directorypress_address_locations, $directorypress_tax_terms_locations;
		
		$directorypress_address_locations = array();
		$directorypress_tax_terms_locations = array();

	
		wp_reset_postdata();
		
		$this->back_global_post();
		
		remove_filter('posts_join', 'join_packages');
		remove_filter('posts_orderby', 'orderby_packages', 1);
		remove_filter('get_meta_sql', 'add_null_values');
	}
	
	public function collectAllLocations() {
		$args = $this->getQueryVars();
			
		unset($args['orderby']);
		unset($args['order']);
		$args['nopaging'] = 1;
		$unlimited_query = new WP_Query($args);
		while ($unlimited_query->have_posts()) {
			$unlimited_query->the_post();
			
			$listing = new directorypress_listing;
			$listing->directorypress_init_lpost_listing(get_post());
			
			$this->map->collect_locations($listing);
		}
	}
		
	public function getQueryVars($var = null) {
		if (is_null($var)) {
			return $this->query->query_vars;
		} else {
			if (isset($this->query->query_vars[$var])) {
				return $this->query->query_vars[$var];
			}
		}
		return false;
	}
	
	public function getPageTitle() {
		return $this->page_title;
	}

	public function getBreadCrumbs($separator = ' » ') {
		return implode($separator, $this->breadcrumbs);
	}

	public function getBaseUrl() {
		return $this->base_url;
	}
	
	public function where_packages_ids($where = '') {
		if ($this->packages_ids)
			$where .= " AND (directorypress_packages.id IN (" . implode(',', $this->packages_ids) . "))";
		return $where;
	}
	
	public function directorypress_get_directoytype_of_listing() {
		global $directorypress_object;
		
		if (isset($this->args['directorytypes']) && !empty($this->args['directorytypes'])) {
			if (is_object($this->args['directorytypes'])) {
				return $this->args['directorytypes'];
			} elseif (is_string($this->args['directorytypes'])) {
				if ($directorytypes_ids = array_filter(explode(',', $this->args['directorytypes']), 'trim')) {
					if (count($directorytypes_ids) == 1 && ($directorytype = $directorypress_object->directorytypes->directory_by_id($directorytypes_ids[0]))) {
						return $directorytype;
					}
				}
			}
		}
		
		return $directorypress_object->current_directorytype;
	}
	public function get_listing_location_class() {
		$location_class = array();
		$listing = $this->listings[get_the_ID()];
		
		foreach ($listing->locations AS $location) {
			$location_class[] = 'marker-'.$location->id;
		}
		
		return implode(" ", $location_class);
	}
	public function display() {
		$output =  directorypress_display_template($this->template, array('public_handler' => $this), true);
		wp_reset_postdata();
	
		return $output;
	}
}


function join_packages($join = '') {
	global $wpdb;

	$join .= " LEFT JOIN {$wpdb->directorypress_packages_relation} AS directorypress_lr ON directorypress_lr.post_id = {$wpdb->posts}.ID ";
	$join .= " LEFT JOIN {$wpdb->directorypress_packages} AS directorypress_packages ON directorypress_packages.id = directorypress_lr.package_id ";

	return $join;
}


function orderby_packages($orderby = '') {
	$orderby_array[] = " directorypress_packages.has_sticky DESC";
	$orderby_array[] = "directorypress_packages.has_featured DESC";
	$orderby_array[] = $orderby;
	
	$orderby_array = apply_filters('directorypress_orderby_packages', $orderby_array, $orderby);
	
	return implode(', ', $orderby_array);
}

function where_has_sticky_has_featured($where = '') {
	$where .= " AND (directorypress_packages.has_sticky=1 OR directorypress_packages.has_featured=1)";
	return $where;
}


function add_null_values($clauses) {
	$clauses['where'] = preg_replace("/wp_postmeta\.meta_key = '_field_([0-9]+)'/", "(wp_postmeta.meta_key = '_field_$1' OR wp_postmeta.meta_value IS NULL)", $clauses['where']);
	return $clauses;
}


add_filter('directorypress_order_args', 'directorypress_order_listings', 10, 3);
function directorypress_order_listings($order_args = array(), $defaults = array(), $include_GET_params = true) {
	global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS;
	
	
	if (directorypress_is_relevanssi_search($defaults)) {
		return $order_args;
	}

	if ($include_GET_params && isset($_GET['order_by']) && $_GET['order_by']) {
		$order_by = sanitize_text_field($_GET['order_by']);
		
		$order = directorypress_get_input_value($_GET, 'order', 'ASC');
	} else {
		if (isset($defaults['order_by']) && $defaults['order_by']) {
			$order_by = $defaults['order_by'];
			$order = directorypress_get_input_value($defaults, 'order', 'ASC');
		} else {
			$order_by = 'post_date';
			$order = 'DESC';
		}
	}

	$order_args['orderby'] = $order_by;
	$order_args['order'] = $order;

	if ($order_by == 'rand' || $order_by == 'random') {
		if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_orderby_has_sticky_has_featured']) {
			add_filter('posts_join', 'join_packages');
			add_filter('posts_orderby', 'orderby_packages', 1);
		}
		$order_args['orderby'] = 'rand';
	}

	if ($order_by == 'title') {
		$order_args['orderby'] = array('title' => $order_args['order'], 'meta_value_num' => 'ASC');
		$order_args['meta_key'] = '_order_date';
		if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_orderby_has_sticky_has_featured']) {
			add_filter('posts_join', 'join_packages');
			add_filter('posts_orderby', 'orderby_packages', 1);
		}
	} elseif ($order_by == 'post_date') {
		// Do not affect packages weights when already ordering by posts IDs
		if (!isset($order_args['orderby']) || $order_args['orderby'] != 'post__in') {
			if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_orderby_has_sticky_has_featured']){
				add_filter('posts_join', 'join_packages');
				add_filter('posts_orderby', 'orderby_packages', 1);
			}
			add_filter('get_meta_sql', 'add_null_values');
		}

		if ($order_by == 'post_date') {
			$directorypress_object->order_by_date = true;
			// First of all order by _order_date parameter
			$order_args['orderby'] = 'meta_value_num';
			$order_args['meta_key'] = '_order_date';
		} else
			$order_args = array_merge($order_args, $directorypress_object->fields->get_order_params($defaults));
	} else {
		$order_args = array_merge($order_args, $directorypress_object->fields->get_order_params($defaults));
	}

	return $order_args;
}

class directorypress_query_search extends WP_Query {
	function __parse_search($q) {
		$x = $this->parse_search($q);
		return $x;
	}
}

add_filter('posts_clauses', 'posts_clauses', 10, 2);
function posts_clauses($clauses, $q) {
	if ($title = $q->get('_meta_or_title')) {
		$tax_query_vars = array();
		if (!empty($q->query_vars['tax_query'])) {
			$tax_query_vars = $q->query_vars['tax_query'];
		}
		if (isset($tax_query_vars[0]['taxonomy']) && in_array($tax_query_vars[0]['taxonomy'], array(DIRECTORYPRESS_CATEGORIES_TAX, DIRECTORYPRESS_TAGS_TAX))) {
			$tq = new WP_Tax_Query($tax_query_vars);

			$qu['s'] = $title;
			$directorypress_query_search = new directorypress_query_search;
	
			global $wpdb;
			$tc = $tq->get_sql($wpdb->posts, 'ID');

			if ($tc['where'] && ($search_sql = $directorypress_query_search->__parse_search($qu))) {
				$clauses['where'] = str_ireplace( 
					$search_sql, 
					' ', 
					$clauses['where'] 
				);
				$clauses['where'] = str_ireplace( 
					$tc['where'], 
					' ', 
					$clauses['where'] 
				);
				$clauses['where'] .= sprintf( 
					" AND ( ( 1=1 %s ) OR ( 1=1 %s ) ) ", 
					$tc['where'],
					$search_sql
				);
			}
		}
    }
    return $clauses;
}
function directorypress_what_search($args, $defaults = array(), $include_GET_params = true) {
	if ($include_GET_params) {
		$args['s'] = directorypress_get_input_value($_GET, 'what_search', directorypress_get_input_value($defaults, 'what_search'));
	} else {
		$args['s'] =  directorypress_get_input_value($defaults, 'what_search');
	}
	
	$args['s'] = stripslashes($args['s']);
	
	$args['s'] = apply_filters('directorypress_search_param_what_search', $args['s']);

	if (empty($args['s'])) {
		unset($args['s']);
	}

	return $args;
}
add_filter('directorypress_search_args', 'directorypress_what_search', 10, 3);

function directorypress_address($args, $defaults = array(), $include_GET_params = true) {
	global $wpdb, $directorypress_address_locations;

	if ($include_GET_params) {
		$address = directorypress_get_input_value($_GET, 'address', directorypress_get_input_value($defaults, 'address'));
		$search_location = directorypress_get_input_value($_GET, 'location_id', directorypress_get_input_value($defaults, 'location_id'));
	} else {
		$search_location = directorypress_get_input_value($defaults, 'location_id');
		$address = directorypress_get_input_value($defaults, 'address');
	}
	
	$search_location = apply_filters('directorypress_search_param_location_id', $search_location);
	$address = apply_filters('directorypress_search_param_address', $address);
	
	$where_sql_array = array();
	if ($search_location && is_numeric($search_location)) {
		$term_ids = get_terms(DIRECTORYPRESS_LOCATIONS_TAX, array('child_of' => $search_location, 'fields' => 'ids', 'hide_empty' => false));
		$term_ids[] = $search_location;
		$where_sql_array[] = "(location_id IN (" . implode(', ', $term_ids) . "))";
	}
	
	if ($address) {
		$where_sql_array[] = $wpdb->prepare("(address_line_1 LIKE '%%%s%%' OR address_line_2 LIKE '%%%s%%' OR zip_or_postal_index LIKE '%%%s%%')", $address, $address, $address);
		
		// Search keyword in locations terms
		$t_args = array(
				'taxonomy'      => array(DIRECTORYPRESS_LOCATIONS_TAX),
				'orderby'       => 'id',
				'order'         => 'ASC',
				'hide_empty'    => true,
				'fields'        => 'tt_ids',
				'name__like'    => $address
		);
		$address_locations = get_terms($t_args);

		foreach ($address_locations AS $address_location) {
			$term_ids = get_terms(DIRECTORYPRESS_LOCATIONS_TAX, array('child_of' => $address_location, 'fields' => 'ids', 'hide_empty' => false));
			$term_ids[] = $address_location;
			$where_sql_array[] = "(location_id IN (" . implode(', ', $term_ids) . "))";
		}
	}

	if ($where_sql_array) {
		$results = $wpdb->get_results("SELECT id, post_id FROM {$wpdb->directorypress_locations_relation} WHERE " . implode(' OR ', $where_sql_array), ARRAY_A);
		$post_ids = array();
		foreach ($results AS $row) {
			$post_ids[] = $row['post_id'];
			$directorypress_address_locations[] = $row['id'];
		}
		if ($post_ids) {
			$args['post__in'] = $post_ids;
		} else {
			// Do not show any listings
			$args['post__in'] = array(0);
		}	
	}
	return $args;
}
add_filter('directorypress_search_args', 'directorypress_address', 10, 3);

function directorypress_keywordInCategorySearch($keyword) {
	if (directorypress_get_input_value($_REQUEST, 'directorypress_action') == 'search' && ($categories = array_filter(explode(',', directorypress_get_input_value($_REQUEST, 'categories')), 'trim')) && count($categories) == 1) {
		if (!is_wp_error($category = get_term(array_pop($categories), DIRECTORYPRESS_CATEGORIES_TAX))) {
			$keyword = trim(str_ireplace(htmlspecialchars_decode($category->name), '', $keyword));
		}
	}
	return $keyword;
}
add_filter('directorypress_search_param_what_search', 'directorypress_keywordInCategorySearch');

function directorypress_addressInLocationSearch($address) {
	if (directorypress_get_input_value($_REQUEST, 'directorypress_action') == 'search' && ($location_id = array_filter(explode(',', directorypress_get_input_value($_REQUEST, 'location_id')), 'trim')) && count($location_id) == 1) {
		if (!is_wp_error($location = get_term(array_pop($location_id), DIRECTORYPRESS_LOCATIONS_TAX))) {
			$address = trim(str_ireplace(htmlspecialchars_decode($location->name), '', $address));
		}
	}
	return $address;
}
add_filter('directorypress_search_param_address', 'directorypress_addressInLocationSearch');

function directorypress_base_url_args($args) {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if (isset($_REQUEST['directorypress_action']) && $_REQUEST['directorypress_action'] == 'search') {
			$args['directorypress_action'] = 'search';
		if (isset($_REQUEST['what_search']) && $_REQUEST['what_search'])
			$args['what_search'] = urlencode(sanitize_text_field($_REQUEST['what_search']));
		if (isset($_REQUEST['address']) && $_REQUEST['address'])
			$args['address'] = urlencode(sanitize_text_field($_REQUEST['address']));
		if (isset($_REQUEST['location_id']) && $_REQUEST['location_id'] && is_numeric($_REQUEST['location_id']))
			$args['location_id'] = sanitize_text_field($_REQUEST['location_id']);
	}

	return $args;
}
add_filter('directorypress_base_url_args', 'directorypress_base_url_args');

function directorypress_related_shortcode_args($shortcode_atts) {
	global $directorypress_object;
	
	if ((isset($shortcode_atts['directorytypes']) && $shortcode_atts['directorytypes'] == 'related') || (isset($shortcode_atts['related_directory']) && $shortcode_atts['related_directory'])) {
		if (($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode(DIRECTORYPRESS_MAIN_SHORTCODE)) || ($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode(DIRECTORYPRESS_LISTING_SHORTCODE)) || ($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode('directorypress-listing'))) {
			if ($directorypress_directory_handler->is_home || $directorypress_directory_handler->is_search || $directorypress_directory_handler->is_category || $directorypress_directory_handler->is_location || $directorypress_directory_handler->is_tag) {
				$shortcode_atts['directorytypes'] = $directorypress_object->current_directorytype->id;
			} elseif ($directorypress_directory_handler->is_single) {
				$shortcode_atts['directorytypes'] = $directorypress_directory_handler->listing->directorytype->id;
				$shortcode_atts['post__not_in'] = $directorypress_directory_handler->listing->post->ID;
			}
		}
	}

	if ((isset($shortcode_atts['categories']) && $shortcode_atts['categories'] == 'related') || (isset($shortcode_atts['related_categories']) && $shortcode_atts['related_categories'])) {
		if (($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode(DIRECTORYPRESS_MAIN_SHORTCODE)) || ($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode(DIRECTORYPRESS_LISTING_SHORTCODE)) || ($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode('directorypress-listing'))) {
			if ($directorypress_directory_handler->is_category) {
				$shortcode_atts['categories'] = $directorypress_directory_handler->category->term_id;
			} elseif ($directorypress_directory_handler->is_single) {
				if ($terms = get_the_terms($directorypress_directory_handler->listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX)) {
					$terms_ids = array();
					foreach ($terms AS $term)
						$terms_ids[] = $term->term_id;
					$shortcode_atts['categories'] = implode(',', $terms_ids);
				}
				$shortcode_atts['post__not_in'] = $directorypress_directory_handler->listing->post->ID;
			}
		}
	}

	if ((isset($shortcode_atts['locations']) && $shortcode_atts['locations'] == 'related') || (isset($shortcode_atts['related_locations']) && $shortcode_atts['related_locations'])) {
		if (($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode(DIRECTORYPRESS_MAIN_SHORTCODE)) || ($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode(DIRECTORYPRESS_LISTING_SHORTCODE)) || ($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode('directorypress-listing'))) {
			if ($directorypress_directory_handler->is_location) {
				$shortcode_atts['locations'] = $directorypress_directory_handler->location->term_id;
			} elseif ($directorypress_directory_handler->is_single) {
				if ($terms = get_the_terms($directorypress_directory_handler->listing->post->ID, DIRECTORYPRESS_LOCATIONS_TAX)) {
					$terms_ids = array();
					foreach ($terms AS $term)
						$terms_ids[] = $term->term_id;
					$shortcode_atts['locations'] = implode(',', $terms_ids);
				}
				$shortcode_atts['post__not_in'] = $directorypress_directory_handler->listing->post->ID;
			}
		}
	}

	if (isset($shortcode_atts['related_tags']) && $shortcode_atts['related_tags']) {
		if (($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode(DIRECTORYPRESS_MAIN_SHORTCODE)) || ($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode(DIRECTORYPRESS_LISTING_SHORTCODE)) || ($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode('directorypress-listing'))) {
			if ($directorypress_directory_handler->is_tag) {
				$shortcode_atts['tags'] = $directorypress_directory_handler->tag->term_id;
			} elseif ($directorypress_directory_handler->is_single) {
				if ($terms = get_the_terms($directorypress_directory_handler->listing->post->ID, DIRECTORYPRESS_TAGS_TAX)) {
					$terms_ids = array();
					foreach ($terms AS $term)
						$terms_ids[] = $term->term_id;
					$shortcode_atts['tags'] = implode(',', $terms_ids);
				}
				$shortcode_atts['post__not_in'] = $directorypress_directory_handler->listing->post->ID;
			}
		}
	}

	if (isset($shortcode_atts['author']) && $shortcode_atts['author'] === 'related') {
		if (($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode(DIRECTORYPRESS_MAIN_SHORTCODE)) || ($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode(DIRECTORYPRESS_LISTING_SHORTCODE)) || ($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode('directorypress-listing'))) {
			if ($directorypress_directory_handler->is_single) {
				$shortcode_atts['author'] = $directorypress_directory_handler->listing->post->post_author;
				$shortcode_atts['post__not_in'] = $directorypress_directory_handler->listing->post->ID;
			}
		} elseif ($user_id = get_the_author_meta('ID')) {
			$shortcode_atts['author'] = $user_id;
		}
	}

	return $shortcode_atts;
}
add_filter('directorypress_related_shortcode_args', 'directorypress_related_shortcode_args');

function directorypress_set_directory_args($args, $directorytypes_ids = array()) {
	global $directorypress_object;
	
	if ($directorypress_object->directorytypes->isMultiDirectory()) {
		if (!isset($args['meta_query']))
			$args['meta_query'] = array();
	
		$args['meta_query'] = array_merge($args['meta_query'], array(
				array(
						'key' => '_directory_id',
						'value' => $directorytypes_ids,
						'compare' => 'IN',
				)
		));
	}

	return $args;
}
if (!function_exists('directorypress_get_similar_listings')) {
      function directorypress_get_similar_listings($post_id, $count = 4, $cat = true)
      {
		  global $directorypress_object;
		  $listing = $directorypress_object->directorypress_get_property_of_shortcode('directorypress-listing');
            $query = new WP_Query();
            $args = '';
            $post_id = $listing->listing->post->ID;
            $item_cats  = get_the_terms($post_id, DIRECTORYPRESS_CATEGORIES_TAX);
            $item_array = array();
            if ($item_cats):
                  foreach ($item_cats as $item_cat) {
                        $item_array[] = $item_cat->term_id;
                  }
            else :
               $item_array[] = array('');
            endif;
			
            $args = wp_parse_args($args, array(
                  'showposts' => $count,
                  'post__not_in' => array(
                        $post_id
                  ),
                  'ignore_has_sticky_posts' => 0,
                  'post_type' => DIRECTORYPRESS_POST_TYPE,
                  'tax_query' => array(
                        array(
                              'taxonomy' => DIRECTORYPRESS_CATEGORIES_TAX,
                              'field' => 'id',
                              'terms' => $item_array
                        )
                  )
            ));
             $query = new WP_Query($args);
            return $query;
      }
}

add_action('directorypress_related_listings', 'directorypress_similar_listings');
if ( !function_exists( 'directorypress_similar_listings' ) ) {
	function directorypress_similar_listings( $layout ) {
	}
}
?>