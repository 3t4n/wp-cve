<?php

include_once DIRECTORYPRESS_PATH . 'includes/core/search/class_search_filter.php';
include_once DIRECTORYPRESS_PATH . 'includes/core/search/filters/select/class-select.php';
include_once DIRECTORYPRESS_PATH . 'includes/core/search/filters/text/class-text.php';
include_once DIRECTORYPRESS_PATH . 'includes/core/search/filters/textarea/class-textarea.php';

do_action( 'directorypress_before_search_fields_loaded' );

class directorypress_search_fields {
	public $search_fields_array = array();
	public $filter_fields_array = array();
	public function __construct() {
		$this->load_search_fields();
		
		add_filter('directorypress_search_args', array($this,  'collect_search_args'), 100, 4);
		add_filter('directorypress_base_url_args', array($this, 'base_url_args'));
		
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_styles'), 100);
	}
	
	public function search_field_settings($id, $action) {
		if (isset($this->search_fields_array[$id])) {
			$search_field = $this->search_fields_array[$id];
			if (method_exists($search_field, 'search_configure')){
				$search_field->search_configure($id, $action);
			}
		}
		
	}
	
	public function get_search_field_by_id($id) {
		if (isset($this->filter_fields_array[$id])) {
			return $this->filter_fields_array[$id];
		}
	}
	
	public function load_search_fields() {
		global $directorypress_object;
		if (!$this->search_fields_array && is_object($directorypress_object)) {
			$fields = $directorypress_object->fields->fields_array;
	
			foreach ($fields AS $field) {
				$field_search_class = get_class($field) . '_search';
				if (class_exists($field_search_class)) {
					$search_field = new $field_search_class;
					$search_field->assign_fields($field);
					$search_field->convert_search_options();
					if ($field->is_this_field_searchable() && (is_admin() || $field->on_search_form)) {
							$this->search_fields_array[$field->id] = $search_field;
							$this->filter_fields_array[$field->id] = $search_field;
					} else {
						$this->filter_fields_array[$field->id] = $search_field;
					}
				}
			}
		}
	}
	
	public function display_fields($search_form) {
		$search_fields = $search_form->search_fields_array;
		$search_fields_advanced = $search_form->search_fields_array_advanced;
		$search_fields_all = $search_form->search_fields_array_all;
		$is_advanced_search_panel = $search_form->is_advanced_search_panel;
		$advanced_open = $search_form->advanced_open;
		$defaults = $search_form->args;
		
		include('_html/filters.php');
	}
	public function display_advanced_fields($search_form) {
		$search_fields = $search_form->search_fields_array;
		$search_fields_advanced = $search_form->search_fields_array_advanced;
		$search_fields_all = $search_form->search_fields_array_all;
		$is_advanced_search_panel = $search_form->is_advanced_search_panel;
		$advanced_open = $search_form->advanced_open;
		$defaults = $search_form->args;
		include('_html/filters-advanced.php');
	}
	
	public function collect_search_args($args, $defaults = array(), $include_GET_params = true, $shortcode_hash = null) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;

		$include_tax_children = directorypress_get_input_value($defaults, 'include_categories_children');

		if ($include_GET_params) {
			$categories = (directorypress_get_input_value($_REQUEST, 'categories') ? directorypress_get_input_value($_REQUEST, 'categories') : directorypress_get_input_value($defaults, 'categories'));
		} else {
			$categories = directorypress_get_input_value($defaults, 'categories');
		}
		if (!$categories) {
			$categories = directorypress_get_input_value($defaults, 'exact_categories');
		}
		
		
		if (!empty($args["s"])) {
			$t_args = array(
					'taxonomy'      => array(DIRECTORYPRESS_CATEGORIES_TAX), // taxonomy name
					'orderby'       => 'id',
					'order'         => 'ASC',
					'hide_empty'    => true,
					'fields'        => 'tt_ids',
					'name__like'    => $args["s"]
			);
			$categories .= ',' . implode(',', get_terms($t_args));
			$args["_meta_or_title"] = $args["s"]; // needed for posts_clauses filter in frotnend_handler.php
		}

		if ($categories) {
			if ($categories = array_filter(explode(',', $categories), 'trim')) {
				$field = 'term_id';
				foreach ($categories AS $category) {
					if (!is_numeric($category)) {
						$field = 'slug';
					}
				}

				$args['tax_query'][] = array(
						'taxonomy' => DIRECTORYPRESS_CATEGORIES_TAX,
						'terms' => $categories,
						'field' => $field,
						'include_children' => $include_tax_children
				);
			}
		}

		if ($include_GET_params) {
			$search_locations = (directorypress_get_input_value($_REQUEST, 'locations') ? directorypress_get_input_value($_REQUEST, 'locations') : directorypress_get_input_value($defaults, 'locations'));
		} else {
			$search_locations = directorypress_get_input_value($defaults, 'locations');
		}
		if (!$search_locations) {
			if ($include_GET_params) {
				$search_locations = (directorypress_get_input_value($_REQUEST, 'exact_locations') ? directorypress_get_input_value($_REQUEST, 'exact_locations') : directorypress_get_input_value($defaults, 'exact_locations'));
			} else {
				$search_locations = directorypress_get_input_value($defaults, 'exact_locations');
			}
		}

		if ($search_locations) {
			if ($locations = array_filter(explode(',', $search_locations), 'trim')) {
				$field = 'term_id';
				foreach ($locations AS $location) {
					if (!is_numeric($location)) {
						$field = 'slug';
					}
				}

				$args['tax_query'][] = array(
						'taxonomy' => DIRECTORYPRESS_LOCATIONS_TAX,
						'terms' => $locations,
						'field' => $field,
						'include_children' => $include_tax_children
				);
			}
		}
		
		if ($include_GET_params) {
			$tags = (directorypress_get_input_value($_REQUEST, 'tags') ? directorypress_get_input_value($_REQUEST, 'tags') : directorypress_get_input_value($defaults, 'tags'));
		} else {
			$tags = directorypress_get_input_value($defaults, 'tags');
		}
		
		if (!empty($args["s"])) {
			$t_args = array(
					'taxonomy'      => array(DIRECTORYPRESS_TAGS_TAX), // taxonomy name
					'orderby'       => 'id',
					'order'         => 'ASC',
					'hide_empty'    => true,
					'fields'        => 'tt_ids',
					'name__like'    => $args["s"]
			);
			$tags .= ',' . implode(',', get_terms($t_args));
			$args["_meta_or_title"] = $args["s"]; // needed for posts_clauses filter in frotnend_handler.php
		}

		if ($tags) {
			if ($tags = array_filter(explode(',', $tags), 'trim')) {
				$field = 'term_id';
				foreach ($tags AS $tag) {
					if (!is_numeric($tag)) {
						$field = 'slug';
					}
				}

				$args['tax_query'][] = array(
						'taxonomy' => DIRECTORYPRESS_TAGS_TAX,
						'terms' => $tags,
						'field' => 'term_id'
				);
			}
		}

		if ($include_GET_params) {
			$search_location = directorypress_get_input_value($_REQUEST, 'location_id', directorypress_get_input_value($defaults, 'location_id'));
			$address = trim(directorypress_get_input_value($_REQUEST, 'address', directorypress_get_input_value($defaults, 'address', null))?? '');
			$radius = directorypress_get_input_value($_REQUEST, 'radius', directorypress_get_input_value($defaults, 'radius'));
		} else {
			$search_location = directorypress_get_input_value($defaults, 'location_id');
			$address = trim(directorypress_get_input_value($defaults, 'address', null)?? '');
			$radius = directorypress_get_input_value($defaults, 'radius');
		}

		$search_location = apply_filters('directorypress_search_param_location_id', $search_location);
		$address = apply_filters('directorypress_search_param_address', $address);
		$radius = apply_filters('directorypress_search_param_radius', $radius);
		
		if (is_null($address) && (directorypress_get_input_value($defaults, 'start_address'))) {
			$address = directorypress_get_input_value($defaults, 'start_address');
		}

		$start_latitude = directorypress_get_input_value($defaults, 'start_latitude');
		$start_longitude = directorypress_get_input_value($defaults, 'start_longitude');
		
		$start_latitude = apply_filters('directorypress_search_param_start_latitude', $start_latitude);
		$start_longitude = apply_filters('directorypress_search_param_start_longitude', $start_longitude);

		if (directorypress_get_input_value($_REQUEST, 'directorypress_action') == 'search') {
			if (!$address) {
				$radius = 0;
			}
		}
		if (directorypress_has_map() && $radius && is_numeric($radius) && ($address || ($start_latitude && $start_longitude))) {
			$directorypress_object->radius_values_array[$shortcode_hash]['radius'] = $radius;

			if (($search_location && is_numeric($search_location)) || $address || ($start_latitude && $start_longitude)) {
				$coords = null;
				if ($start_latitude && $start_longitude) {
					$coords[1] = $start_latitude;
					$coords[0] = $start_longitude;
				} elseif ($address || $search_location) {
					$chain = array();
					$parent_id = $search_location;
					while ($parent_id != 0) {
						if ($term = get_term($parent_id, DIRECTORYPRESS_LOCATIONS_TAX)) {
							$chain[] = $term->name;
							$parent_id = $term->parent;
						} else
							$parent_id = 0;
					}
					$location_string = implode(', ', $chain);
					
					if ($address)
						$location_string = $address . ' ' . $location_string;
					if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_geocoding_location'])
						$location_string = $location_string . ' ' . $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_geocoding_location'];
					
					$directorypress_geo_name = new directorypress_geo_name();
					$coords = $directorypress_geo_name->geocodeRequest($location_string, 'coordinates');
				}

				if ($coords) {
					add_filter('directorypress_ordering_options', array($this, 'order_by_distance_html'), 10, 4);

					$directorypress_object->radius_values_array[$shortcode_hash]['x_coord'] = $coords[1]; // latitude
					$directorypress_object->radius_values_array[$shortcode_hash]['y_coord'] = $coords[0]; // longitude

					wp_localize_script(
							'directorypress-public',
							'radius_params_'.$shortcode_hash,
							array(
								'radius_value' => $radius,
								'map_coords_1' => $coords[1],
								'map_coords_2' => $coords[0],
								'dimension' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_miles_kilometers_in_search']
							)
					);
					

					if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_miles_kilometers_in_search'] == 'miles')
						$R = 3956; 
					else
						$R = 6367;

					$dLat = '((map_coords_1-'.$coords[1].')*PI()/180)';
					$dLong = '((map_coords_2-'.$coords[0].')*PI()/180)';
					$a = '(sin('.$dLat.'/2) * sin('.$dLat.'/2) + cos('.$coords[1].'*pi()/180) * cos(map_coords_1*pi()/180) * sin('.$dLong.'/2) * sin('.$dLong.'/2))';
					$c = '2*atan2(sqrt('.$a.'), sqrt(1-'.$a.'))';
					$sql = $R.'*'.$c; 

					global $wpdb, $directorypress_address_locations;
					$results = $wpdb->get_results($wpdb->prepare(
						"SELECT DISTINCT
							id, post_id, " . $sql . " AS distance FROM {$wpdb->directorypress_locations_relation}
						HAVING
							distance <= %d
						ORDER BY
							distance
						", $radius), ARRAY_A);

					$post_ids = array();
					$directorypress_address_locations = array();
					foreach ($results AS $row) {
						$post_ids[] = $row['post_id'];
						$directorypress_address_locations[] = $row['id'];
					}
					$post_ids = array_unique($post_ids);

					if ($post_ids) {
						$args['post__in'] = $post_ids;
					} else
						
						$args['post__in'] = array(0);

					$args = $this->order_by_distance_args($args, $defaults, $include_GET_params, $shortcode_hash);
				}
			}
		}

		foreach ($this->filter_fields_array AS $field_id=>$filter_field) {
			$filter_field->search_validation($args, $defaults, $include_GET_params);
		}

		return $args;
	}
	
	public function order_by_distance_args($args, $defaults, $include_GET_params, $shortcode_hash) {
		global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS;

		if (isset($directorypress_object->radius_values_array[$shortcode_hash]) && $directorypress_object->radius_values_array[$shortcode_hash]['radius']) {
			if (!isset($defaults['order_by']) || !$defaults['order_by']) {
				$defaults['order_by'] = 'distance';
				$directorypress_object->order_by_date = false;
				
			}
			
			if ($include_GET_params) {
				$order_by = directorypress_get_input_value($_REQUEST, 'order_by', directorypress_get_input_value($defaults, 'order_by'));
				$order = directorypress_get_input_value($_REQUEST, 'order', directorypress_get_input_value($defaults, 'order'));
			} else {
				$order_by = directorypress_get_input_value($defaults, 'order_by');
				$order = directorypress_get_input_value($defaults, 'order');
			}

			
			if ($order_by == 'distance' && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_orderby_distance']) {
				$args['orderby'] = 'post__in';
				unset($args['meta_key']);
				if ($order == 'DESC') {
					if (!empty($args['post__in']) && is_array($args['post__in'])) {
						$args['post__in'] = array_reverse($args['post__in']);
					}
				}
	
				if (!$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_orderby_has_sticky_has_featured']) {
					
					remove_filter('posts_join', 'join_packages');
					remove_filter('posts_orderby', 'orderby_packages', 1);
					remove_filter('get_meta_sql', 'add_null_values');
				}
			}
		}

		return $args;
	}
	
	public function order_by_distance_html($ordering, $base_url, $defaults = array(), $shortcode_hash = null) {
		global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS;

		if (isset($directorypress_object->radius_values_array[$shortcode_hash]) && $directorypress_object->radius_values_array[$shortcode_hash]['radius']) {
			if (!isset($_REQUEST['order_by']) || !$_REQUEST['order_by'] || $_REQUEST['order_by'] == 'distance') {
				
				foreach ($ordering['array'] AS $field_slug=>$field_name) {
					$ordering['struct'][$field_slug]['class'] = '';
				}
				
				if (isset($defaults['order_by']) && $defaults['order_by'] && isset($ordering['array'][$defaults['order_by']])) {
					$url = esc_url(add_query_arg('order_by', $defaults['order_by'], $base_url));
					$ordering['links'][$defaults['order_by']] = '<a href="' . $url . '">' . $ordering['array'][$defaults['order_by']] . '</a>';
					$ordering['struct'][$defaults['order_by']]['url'] = $url;
					$ordering['struct'][$defaults['order_by']]['class'] = '';
				}
	
				$order_by = 'distance';
				$order = directorypress_get_input_value($_REQUEST, 'order', 'ASC');
			} else {
				$order_by = directorypress_get_input_value($defaults, 'order_by');
				$order = directorypress_get_input_value($defaults, 'order');
			}
	
			$class = '';
			$next_order = 'ASC';
			
			if ($order_by == 'distance' && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_orderby_distance']) {
				if (!$order || $order == 'DESC') {
					$class = 'descending';
					$next_order = 'ASC';
					$url = esc_url(add_query_arg('order_by', 'distance', $base_url));
				} elseif ($order == 'ASC') {
					$class = 'ascending';
					$next_order = 'DESC';
					$url = esc_url(add_query_arg('order', $next_order, add_query_arg('order_by', 'distance', $base_url)));
				}
			} else
				$url = esc_url(add_query_arg('order_by', 'distance', $base_url));
	
			$ordering['links']['distance'] = '<a class="' . $class . '" href="' . $url . '">' . __('Distance', 'DIRECTORYPRESS') . '</a>';
			$ordering['array']['distance'] = __('Distance', 'DIRECTORYPRESS');
			$ordering['struct']['distance'] = array('class' => $class, 'url' => $url, 'field_name' => __('Distance', 'DIRECTORYPRESS'), 'order' => $next_order);
		}

		return $ordering;
	}
	
	public function base_url_args($args) {
		if (isset($_REQUEST['directorypress_action']) && $_REQUEST['directorypress_action'] == 'search') {
			if (isset($_REQUEST['categories']) && $_REQUEST['categories'] && is_numeric($_REQUEST['categories']))
				$args['categories'] = sanitize_text_field($_REQUEST['categories']);
			if (isset($_REQUEST['radius']) && $_REQUEST['radius'] && is_numeric($_REQUEST['radius']))
				$args['radius'] = sanitize_text_field($_REQUEST['radius']);

			foreach ($this->search_fields_array AS $search_field)
				$search_field->gat_base_url_args($args);
		}
		
		return $args;
	}
	
	public function enqueue_scripts_styles() {
		global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS;
	
		if (function_exists('is_rtl') && is_rtl()) {
			wp_deregister_script('jquery-ui-slider');
			wp_register_script('jquery-ui-slider', DIRECTORYPRESS_RESOURCES_URL . 'lib/jquery-ui/js/jquery-ui-slider-rtl.min.js', array('jquery-ui-core') , false, true);
		}
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('jquery-touch-punch');
	
		wp_localize_script(
			'jquery-ui-slider',
			'slider_params',
			array(
				'min' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_radius_search_min'],
				'max' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_radius_search_max']
			)
		);

		
		foreach ($directorypress_object->radius_values_array AS $shortcode_hash=>$value) {
			if (($public_handler = $directorypress_object->directorypress_get_unique_shortcode_object($shortcode_hash)) && isset($value['x_coord']) && isset($value['y_coord'])) {
				wp_localize_script(
					'directorypress-public',
					'radius_params_'.$public_handler->hash,
					array(
						'radius_value' => $value['radius'],
						'map_coords_1' => $value['x_coord'],
						'map_coords_2' => $value['y_coord'],
						'dimension' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_miles_kilometers_in_search']
					)
				);
			}
		}
	}
}

?>