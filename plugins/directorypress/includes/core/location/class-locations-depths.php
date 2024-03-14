<?php 

class directorypress_locations_depths {
	public $location_depths_array = array();

	public function __construct() {
		$this->get_depth_levels_from_database();
	}

	public function get_depth_levels_from_database() {
		global $wpdb;
		$this->location_depths_array = array();

		$array = $wpdb->get_results("SELECT * FROM {$wpdb->directorypress_locations_depths}", ARRAY_A);
		foreach ($array AS $row) {
			$location_depth = new directorypress_locations_depth;
			$location_depth->build_depth_level_from_array($row);
			$this->location_depths_array[$row['id']] = $location_depth;
		}
	}
	
	public function get_names_array() {
		$names = array();
		foreach ($this->location_depths_array AS $location_depth)
			$names[] = $location_depth->name;
		
		return $names;
	}

	public function get_selections_array() {
		$selections = array();
		foreach ($this->location_depths_array AS $location_depth)
			$selections[] = $location_depth->name;
		
		return $selections;
	}
	
	public function get_depth_level_by_id($location_depth_id) {
		if (isset($this->location_depths_array[$location_depth_id]))
			return $this->location_depths_array[$location_depth_id];
	}
	
	public function create_depth_level_from_array($array) {
		global $wpdb;
		
		$insert_update_args = array(
				'name' => directorypress_get_input_value($array, 'name'),
				'in_address_line' => directorypress_get_input_value($array, 'in_address_line'),
		);
		
		$insert_update_args = apply_filters('directorypress_locations_depth_create_edit_args', $insert_update_args, $array);
	
		if ($wpdb->insert($wpdb->directorypress_locations_depths, $insert_update_args)) {
			$new_location_depth_id = $wpdb->insert_id;

			do_action('directorypress_update_locations_depth', $new_location_depth_id, $insert_update_args);
			
			$this->get_depth_levels_from_database();
			
			return true;
		}
	}
	
	public function save_depth_level_from_array($location_depth_id, $array) {
		global $wpdb;
		
		$insert_update_args = array(
				'name' => directorypress_get_input_value($array, 'name'),
				'in_address_line' => directorypress_get_input_value($array, 'in_address_line'),
		);
		
		$insert_update_args = apply_filters('directorypress_locations_depth_create_edit_args', $insert_update_args, $array);
	
		if ($wpdb->update($wpdb->directorypress_locations_depths, $insert_update_args,	array('id' => $location_depth_id), null, array('%d')) !== false) {
			do_action('directorypress_update_locations_depth', $location_depth_id, $insert_update_args);
			
			$this->get_depth_levels_from_database();
				
			return true;
		}
	}
	
	public function delete_depth_level($location_depth_id) {
		global $wpdb;
	
		if ($wpdb->delete($wpdb->directorypress_locations_depths, array('id' => $location_depth_id))) {
			$this->get_depth_levels_from_database();

			return true;
		}
	}
}

class directorypress_locations_depth {
	public $id;
	public $name;
	public $in_address_line = 1;

	public function build_depth_level_from_array($array) {
		$this->id = directorypress_get_input_value($array, 'id');
		$this->name = directorypress_get_input_value($array, 'name');
		$this->in_address_line = directorypress_get_input_value($array, 'in_address_line');
	}
}


add_action('init', 'directorypress_locations_depths_names_into_strings');
function directorypress_locations_depths_names_into_strings() {
	global $directorypress_object, $sitepress;

	if (function_exists('wpml_object_id_filter') && $sitepress) {
		foreach ($directorypress_object->locations_depths->location_depths_array AS &$locations_depth) {
			$locations_depth->name = apply_filters('wpml_translate_single_string', $locations_depth->name, 'DirectoryPress', 'locations depth name #' . $locations_depth->id);
		}
	}
}

add_filter('directorypress_locations_depth_create_edit_args', 'directorypress_filter_locations_depth_fields', 10, 2);
function directorypress_filter_locations_depth_fields($insert_update_args, $array) {
	global $sitepress, $wpdb;

	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($sitepress->get_default_language() != ICL_LANGUAGE_CODE) {
			if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['location_depth_id'])) {
				$location_depth_id = sanitize_text_field($_GET['location_depth_id']);
				if ($name_string_id = icl_st_is_registered_string('DirectoryPress', 'locations depth name #' . $package_id))
					icl_add_string_translation($name_string_id, ICL_LANGUAGE_CODE, $insert_update_args['name'], ICL_TM_COMPLETE);
				unset($insert_update_args['name']);
			}
		}
	}
	return $insert_update_args;
}

add_action('directorypress_update_locations_depth', 'directorypress_update_locations_depth', 10, 2);
function directorypress_update_locations_depth($location_depth_id, $array) {
	global $sitepress;
	
	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($sitepress->get_default_language() == ICL_LANGUAGE_CODE) {
			do_action('wpml_register_single_string', 'DirectoryPress', 'locations depth name #' . $location_depth_id, directorypress_get_input_value($array, 'name'));
		}
	}
}

?>