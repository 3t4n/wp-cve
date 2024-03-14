<?php 

class directorypress_packages {
	public $packages_array = array();

	public function __construct() {
		$this->get_package_from_database();
	}
	
	public function saveOrder($order_input) {
		global $wpdb;

		if ($order_ids = explode(',', trim($order_input))) {
			$i = 1;
			foreach ($order_ids AS $id) {
				$wpdb->update($wpdb->directorypress_packages, array('order_num' => $i), array('id' => $id));
				$i++;
			}
		}
		$this->get_package_from_database();
		return true;
	}
	
	public function get_package_from_database() {
		global $wpdb;
		$this->packages_array = array();

		$array = $wpdb->get_results("SELECT * FROM {$wpdb->directorypress_packages} ORDER BY order_num", ARRAY_A);
		foreach ($array AS $row) {
			$package = new directorypress_package;
			$package->build_package_from_array($row);
			$package->convert_package_options();
			$this->packages_array[$row['id']] = $package;
		}
		
	}
	
	public function get_package_by_id($package_id) {
		if (isset($this->packages_array[$package_id]))
			return $this->packages_array[$package_id];
	}
	
	public function get_default_package() {
		$array_keys = array_keys($this->packages_array);
		$first_id = array_shift($array_keys);
		return $this->get_package_by_id($first_id);
	}

	public function create_package_from_array($array) {
		global $wpdb, $directorypress_object;
		
		$insert_update_args = array(
				'name' => directorypress_get_input_value($array, 'name'),
				'description' => directorypress_get_input_value($array, 'description'),
				'who_can_submit' => serialize(directorypress_get_input_value($array, 'who_can_submit', array())),
				'package_duration' => directorypress_get_input_value($array, 'package_duration', 1),
				'package_duration_unit' => directorypress_get_input_value($array, 'package_duration_unit', 'day'),
				'package_no_expiry' => directorypress_get_input_value($array, 'package_no_expiry', 0),
				'change_package_id' => directorypress_get_input_value($array, 'change_package_id', 1),
				'number_of_listings_in_package' => directorypress_get_input_value($array, 'number_of_listings_in_package', 1),
				'number_of_package_renew_allowed' => directorypress_get_input_value($array, 'number_of_package_renew_allowed'),
				'can_be_bumpup' => directorypress_get_input_value($array, 'can_be_bumpup'),
				'has_sticky' => directorypress_get_input_value($array, 'has_sticky'),
				'has_featured' => directorypress_get_input_value($array, 'has_featured'),
				'category_number_allowed' => directorypress_get_input_value($array, 'category_number_allowed', 1),
				'location_number_allowed' => directorypress_get_input_value($array, 'location_number_allowed', 1),
				'featured_package' => $array['featured_package'],
				'images_allowed' => directorypress_get_input_value($array, 'images_allowed'),
				'videos_allowed' => directorypress_get_input_value($array, 'videos_allowed'),
				'selected_categories' => serialize(directorypress_get_input_value($array, 'selected_categories', array())),
				'fields' => serialize(directorypress_get_input_value($array, 'fields', array())),
				'options' =>  serialize(directorypress_get_input_value($array, 'options', array())),
				'selected_locations' =>  serialize(directorypress_get_input_value($array, 'selected_locations', array())),
		);
		$insert_update_args = apply_filters('directorypress_package_create_edit_args', $insert_update_args, $array);

		if ($wpdb->insert($wpdb->directorypress_packages, $insert_update_args)) {
			$new_package_id = $wpdb->insert_id;
			
			do_action('directorypress_update_package', $new_package_id, $array);
			
			$this->get_package_from_database();
			$packages = $directorypress_object->packages;
			$results = array();
			foreach ($packages->packages_array AS $package) {
				$results[$package->id]['disabled'] = false;
				$results[$package->id]['raiseup'] = false;
			}
			$package = $this->get_package_by_id($new_package_id);
			$package->save_upgrade_meta($results);
			return true;
		}
	}
	
	public function save_package_from_array($package_id, $array) {
		global $wpdb;
		$options = array();
		$options['selection_items'] = directorypress_get_input_value($array, 'selection_items[]', array());
		//$options['icon_selection_items'] = directorypress_get_input_value($array, 'icon_selection_items[]', array());
		$insert_update_args = array(
				'name' => directorypress_get_input_value($array, 'name'),
				'description' => directorypress_get_input_value($array, 'description'),
				'who_can_submit' => serialize(directorypress_get_input_value($array, 'who_can_submit', array())),
				'package_duration' => directorypress_get_input_value($array, 'package_duration'),
				'package_duration_unit' => directorypress_get_input_value($array, 'package_duration_unit'),
				'package_no_expiry' => directorypress_get_input_value($array, 'package_no_expiry'),
				'change_package_id' => directorypress_get_input_value($array, 'change_package_id'),
				'number_of_listings_in_package' => directorypress_get_input_value($array, 'number_of_listings_in_package'),
				'number_of_package_renew_allowed' => directorypress_get_input_value($array, 'number_of_package_renew_allowed'),
				'has_sticky' => directorypress_get_input_value($array, 'has_sticky'),
				'can_be_bumpup' => directorypress_get_input_value($array, 'can_be_bumpup'),
				'has_featured' => directorypress_get_input_value($array, 'has_featured'),
				'category_number_allowed' => directorypress_get_input_value($array, 'category_number_allowed'),
				'location_number_allowed' => directorypress_get_input_value($array, 'location_number_allowed'),
				'featured_package' => $array['featured_package'],
				'images_allowed' => directorypress_get_input_value($array, 'images_allowed'),
				'videos_allowed' => directorypress_get_input_value($array, 'videos_allowed'),
				'selected_categories' => serialize(directorypress_get_input_value($array, 'selected_categories', array())),
				'fields' => serialize(directorypress_get_input_value($array, 'fields', array())),
				'options' =>  serialize($options),
				'selected_locations' =>  serialize(directorypress_get_input_value($array, 'selected_locations', array())),
		);
		
		$insert_update_args = apply_filters('directorypress_package_create_edit_args', $insert_update_args, $array);
	
		if ($wpdb->update($wpdb->directorypress_packages, $insert_update_args, array('id' => $package_id), null, array('%d')) !== false) {
			do_action('directorypress_update_package', $package_id, $array);
			
			$old_package = $this->get_package_by_id($package_id);
			$this->get_package_from_database();
			$new_package = $this->get_package_by_id($package_id);
			
			// update listings from eternal active period to numeric
			if ($old_package->package_no_expiry && !$new_package->package_no_expiry) {
				$expiration_date = directorypress_expiry_date(current_time('timestamp'), $new_package);
				$postids = $this->getPostIdsByLevelId($package_id);
				foreach ($postids AS $post_id) {
					delete_post_meta($post_id, '_expiration_date');
					update_post_meta($post_id, '_expiration_date', $expiration_date);
				}
			} elseif (!$old_package->package_no_expiry && $new_package->package_no_expiry) {
				$postids = $this->getPostIdsByLevelId($package_id);
				foreach ($postids AS $post_id)
					delete_post_meta($post_id, '_expiration_date');
			}
			
			return true;
		}
	}
	
	public function delete_package($package_id) {
		global $wpdb;
		
		$postids = $this->getPostIdsByLevelId($package_id);
		foreach ($postids AS $post_id)
			wp_delete_post($post_id, true);
	
		$wpdb->delete($wpdb->directorypress_packages, array('id' => $package_id));
		
		$this->get_package_from_database();
		return true;
	}
	
	public function getPostIdsByLevelId($package_id) {
		global $wpdb;

		return $postids = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM {$wpdb->directorypress_packages_relation} WHERE package_id=%d", $package_id));
	}
}

class directorypress_package {
	public $id;
	public $order_num;
	public $name;
	public $description;
	public $who_can_submit = array();
	public $package_duration;
	public $package_duration_unit;
	public $package_no_expiry = 1;
	public $number_of_listings_in_package = 1;
	public $number_of_package_renew_allowed;
	public $change_package_id = 0;
	public $has_featured = 0;
	public $can_be_bumpup = 0;
	public $has_sticky = 0;
	public $category_number_allowed = 1;
	public $location_number_allowed = 1;
	public $featured_package;
	public $images_allowed = 1;
	public $videos_allowed = 1;
	public $selected_categories = array();
	public $selected_locations = array();
	public $fields = array();
	public $options = array();
	public $upgrade_meta = array();
	public $selection_items = array();
	//public $icon_selection_items = array();

	public function build_package_from_array($array) {
		$this->id = directorypress_get_input_value($array, 'id');
		$this->order_num = directorypress_get_input_value($array, 'order_num');
		$this->name = directorypress_get_input_value($array, 'name');
		$this->description = directorypress_get_input_value($array, 'description');
		$this->who_can_submit = directorypress_get_input_value($array, 'who_can_submit');
		$this->package_duration = directorypress_get_input_value($array, 'package_duration');
		$this->package_duration_unit = directorypress_get_input_value($array, 'package_duration_unit');
		$this->package_no_expiry = directorypress_get_input_value($array, 'package_no_expiry');
		$this->number_of_listings_in_package = directorypress_get_input_value($array, 'number_of_listings_in_package');
		$this->number_of_package_renew_allowed = directorypress_get_input_value($array, 'number_of_package_renew_allowed');
		$this->change_package_id = directorypress_get_input_value($array, 'change_package_id');
		$this->has_featured = directorypress_get_input_value($array, 'has_featured');
		$this->has_sticky = directorypress_get_input_value($array, 'has_sticky');
		$this->can_be_bumpup = directorypress_get_input_value($array, 'can_be_bumpup');
		$this->category_number_allowed = directorypress_get_input_value($array, 'category_number_allowed');
		$this->location_number_allowed = directorypress_get_input_value($array, 'location_number_allowed');
		$this->featured_package = directorypress_get_input_value($array, 'featured_package');
		$this->images_allowed = directorypress_get_input_value($array, 'images_allowed');
		$this->videos_allowed = directorypress_get_input_value($array, 'videos_allowed');
		$this->selected_categories = directorypress_get_input_value($array, 'selected_categories');
		$this->selected_locations = directorypress_get_input_value($array, 'selected_locations');
		$this->fields = directorypress_get_input_value($array, 'fields');
		$this->options = directorypress_get_input_value($array, 'options');
		$this->upgrade_meta = (directorypress_get_input_value($array, 'upgrade_meta')) ? unserialize(directorypress_get_input_value($array, 'upgrade_meta')) : array();
		
		$this->directorypress_process_user_roles();
		$this->directorypress_process_categories();
		$this->directorypress_process_locations();
		$this->convert_fields();
		
		apply_filters('directorypress_packages_loading', $this, $array);
	}
	public function build_package_options() {
		if (isset($this->options['selection_items'])) {
			$this->selection_items = $this->options['selection_items'];
		}
		//if (isset($this->options['icon_selection_items'])) {
			//$this->icon_selection_items = $this->options['icon_selection_items'];
		//}
	}
	public function convert_package_options() {
		if ($this->options) {
			$unserialized_options = maybe_unserialize($this->options);
			if (count($unserialized_options) > 1 || $unserialized_options != array('')) {
				$this->options = $unserialized_options;
				if (method_exists($this, 'build_package_options'))
					$this->build_package_options();
				return $this->options;
			}
		}
		return array();
	}
	public function directorypress_process_user_roles() {
		
		if ($this->who_can_submit) {
			$unserialized_who_can_submit = maybe_unserialize($this->who_can_submit);
			if (count($unserialized_who_can_submit) > 1 || $unserialized_who_can_submit != array('')) {
				$this->who_can_submit = $unserialized_who_can_submit;
			} else {
				$this->who_can_submit = array();
			}
		} else {
			$this->who_can_submit = array();
		}
	}
	public function directorypress_process_categories() {
		if ($this->selected_categories) {
			$unserialized_categories = maybe_unserialize($this->selected_categories);
			if (!empty($unserialized_categories)){
				if (count($unserialized_categories) > 1 || $unserialized_categories != array('')){
					$this->selected_categories = $unserialized_categories;
				}else{
					$this->selected_categories = array();
				}
			}else{
				$this->selected_categories = array();
			}
			
		} else
			$this->selected_categories = array();
		return $this->selected_categories;
	}
	
	public function directorypress_process_locations() {
		if ($this->selected_locations) {
			$unserialized_locations = maybe_unserialize($this->selected_locations);
			if (!empty($unserialized_locations)){
				if (count($unserialized_locations) > 1 || $unserialized_locations != array('')){
					$this->selected_locations = $unserialized_locations;
				}else{
					$this->selected_locations = array();
				}
			}else{
				$this->selected_locations = array();
			}
		} else
			$this->selected_locations = array();
		return $this->selected_locations;
	}

	public function convert_fields() {
		if ($this->fields) {
			$unserialized_fields = maybe_unserialize($this->fields);
			if (!empty($unserialized_fields)){
				if (count($unserialized_fields) > 1 || $unserialized_fields != array('')){
					$this->fields = $unserialized_fields;
				}else{
					$this->fields = array();
				}
			}else{
				$this->fields = array();
			}
		} else
			$this->fields = array();
		return $this->fields;
	}
	
	public function get_active_duration_string() {
		if ($this->package_no_expiry)
			return __('Never expire', 'DIRECTORYPRESS');
		else {
			if ($this->package_duration_unit == 'day')
				return $this->package_duration . ' ' . _n('day', 'days', $this->package_duration, 'DIRECTORYPRESS');
			elseif ($this->package_duration_unit == 'week')
				return $this->package_duration . ' ' . _n('week', 'weeks', $this->package_duration, 'DIRECTORYPRESS');
			elseif ($this->package_duration_unit == 'month')
				return $this->package_duration . ' ' . _n('month', 'months', $this->package_duration, 'DIRECTORYPRESS');
			elseif ($this->package_duration_unit == 'year')
				return $this->package_duration . ' ' . _n('year', 'years', $this->package_duration, 'DIRECTORYPRESS');
		}
	}
	
	public function save_upgrade_meta($meta) {
		global $wpdb;
		
		$this->upgrade_meta = $meta;
		
		$this->upgrade_meta = apply_filters('directorypress_package_upgrade_meta', $this->upgrade_meta, $this);

		return $wpdb->update($wpdb->directorypress_packages, array('upgrade_meta' => serialize($this->upgrade_meta)), array('id' => $this->id));
	}
	
	public function is_upgradable() {
		global $directorypress_object;

		if (count($directorypress_object->packages->packages_array) > 1) {
			foreach ($this->upgrade_meta AS $id=>$meta) {
				if (($id != $this->id) && (!isset($meta['disabled']) || !$meta['disabled'] || (current_user_can('editor') || current_user_can('manage_options'))))
					return true;
			}
		}
		return false;
	}
}

// adapted for WPML
add_action('init', 'directorypress_packages_names_into_strings');
function directorypress_packages_names_into_strings() {
	global $directorypress_object, $sitepress;

	if (function_exists('wpml_object_id_filter') && $sitepress) {
		foreach ($directorypress_object->packages->packages_array AS &$package) {
			$package->name = apply_filters('wpml_translate_single_string', $package->name, 'DirectoryPress', 'package name #' . $package->id);
			$package->description = apply_filters('wpml_translate_single_string', $package->description, 'DirectoryPress', 'package description #' . $package->id);
		}
	}
}

add_filter('directorypress_package_create_edit_args', 'directorypress_filter_package_settings', 10, 2);
function directorypress_filter_package_settings($insert_update_args, $array) {
	global $sitepress;

	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($sitepress->get_default_language() != ICL_LANGUAGE_CODE) {
			if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['package_id'])) {
				$package_id = sanitize_text_field($_GET['package_id']);
				if ($name_string_id = icl_st_is_registered_string('DirectoryPress', 'package name #' . $package_id))
					icl_add_string_translation($name_string_id, ICL_LANGUAGE_CODE, $insert_update_args['name'], ICL_TM_COMPLETE);
				if ($description_string_id = icl_st_is_registered_string('DirectoryPress', 'package description #' . $package_id))
					icl_add_string_translation($description_string_id, ICL_LANGUAGE_CODE, $insert_update_args['description'], ICL_TM_COMPLETE);
				unset($insert_update_args['name']);
				unset($insert_update_args['description']);
				
				unset($insert_update_args['selected_categories']);
				unset($insert_update_args['selected_locations']);
			} else { 
				$insert_update_args['selected_categories'] = '';
				$insert_update_args['selected_locations'] = '';
			}
		}
	}
	return $insert_update_args;
}

add_action('directorypress_update_package', 'directorypress_save_package_categories_locations', 10, 2);
function directorypress_save_package_categories_locations($package_id, $array) {
	global $sitepress;

	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($sitepress->get_default_language() != ICL_LANGUAGE_CODE) {
			update_option('directorypress_wpml_package_categories_'.$package_id.'_'.ICL_LANGUAGE_CODE, directorypress_get_input_value($array, 'selected_categories'));
			update_option('directorypress_wpml_package_locations_'.$package_id.'_'.ICL_LANGUAGE_CODE, directorypress_get_input_value($array, 'selected_locations'));
		}
		
		if ($sitepress->get_default_language() == ICL_LANGUAGE_CODE) {
			do_action('wpml_register_single_string', 'DirectoryPress', 'The name of package #' . $package_id, directorypress_get_input_value($array, 'name'));
			do_action('wpml_register_single_string', 'DirectoryPress', 'The description of package #' . $package_id, directorypress_get_input_value($array, 'description'));
		}
	}
}
	
add_action('init', 'directorypress_load_packages_categories_locations');
function directorypress_load_packages_categories_locations() {
	global $directorypress_object, $sitepress;

	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($sitepress->get_default_language() != ICL_LANGUAGE_CODE) {
			foreach ($directorypress_object->packages->packages_array AS &$package) {
				$_categories = get_option('directorypress_wpml_package_categories_'.$package->id.'_'.ICL_LANGUAGE_CODE);
				if ($_categories && (count($_categories) > 1 || $_categories != array('')))
					$package->selected_categories = $_categories;
				else
					$package->selected_categories = array();
				$_locations = get_option('directorypress_wpml_package_locations_'.$package->id.'_'.ICL_LANGUAGE_CODE);
				if ($_locations && (count($_locations) > 1 || $_locations != array('')))
					$package->selected_locations = $_locations;
				else
					$package->selected_locations = array();
			}
		}
	}
}