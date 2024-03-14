<?php 

class directorypress_location {
	public $id;
	public $post_id;
	public $selected_location = 0;
	public $address_line_1;
	public $address_line_2;
	public $zip_or_postal_index;
	public $additional_info;
	public $manual_coords = false;
	public $map_coords_1;
	public $map_coords_2;
	public $map_icon_file;
	public $map_icon_color;
	public $map_icon_manually_selected;
	
	public function __construct($post_id = null) {
		$this->post_id = $post_id;
	}

	public function create_location_from_array($array) {
		$this->id = directorypress_get_input_value($array, 'id');
		$this->selected_location = directorypress_get_input_value($array, 'selected_location');
		$this->address_line_1 = directorypress_get_input_value($array, 'address_line_1');
		$this->address_line_2 = directorypress_get_input_value($array, 'address_line_2');
		$this->zip_or_postal_index = directorypress_get_input_value($array, 'zip_or_postal_index');
		$this->additional_info = directorypress_get_input_value($array, 'additional_info');
		$this->manual_coords = directorypress_get_input_value($array, 'manual_coords');
		$this->map_coords_1 = directorypress_get_input_value($array, 'map_coords_1');
		$this->map_coords_2 = directorypress_get_input_value($array, 'map_coords_2');
		$this->map_icon_file = directorypress_get_input_value($array, 'map_icon_file');
		$this->map_icon_color = directorypress_get_input_value($array, 'map_icon_color');
		$this->map_icon_manually_selected = directorypress_get_input_value($array, 'map_icon_manually_selected');
	}
	
	public function get_setected_location_string($glue = ', ', $reverse = false) {
		global $directorypress_object;

		if ($this->selected_location != 0) {
			$chain = array();
			$parent_id = $this->selected_location;
			while ($parent_id != 0) {
				if (!is_wp_error($term = get_term($parent_id, DIRECTORYPRESS_LOCATIONS_TAX)) && $term) {
					$chain[] = $term->name;
					$parent_id = $term->parent;
				} else 
					break;
			}

			$chain = array_reverse($chain);

			$locations_depths = $directorypress_object->locations_depths;
			$locations_depths_array = array_values($locations_depths->location_depths_array);
			$result_chain = array();
			foreach ($chain AS $location_key=>$location) {
				if ($locations_depths_array[$location_key]->in_address_line)
					$result_chain[] = $location;
			}

			if (!$reverse)
				$result_chain = array_reverse($result_chain);
			return implode($glue, $result_chain);
		}
	}
	public function get_location($microdata = true, $glue = ', ', $reverse = false) {
		$separator_previous = false;
		$out = array();
		if ($location_string = $this->get_setected_location_string($glue, $reverse)) {
						$out[] = (($microdata) ? '<span itemprop="addressLocality">' : '') . $location_string . (($microdata) ? '</span>' : '');
						$separator_previous = false;
					} elseif (in_array($separator_previous, array('space','comma','break')))
						array_pop($out);
		
		return trim(implode('', $out), ', ');
	}
	public function get_full_address($microdata = true, $glue = ', ', $reverse = false) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		$out = array();
		$separator_previous = false;
		$orders = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress-addresses-order']['enabled'];
		foreach ($orders AS $key=>$value) {
			switch ($key) {
				case 'location':
					if ($location_string = $this->get_setected_location_string($glue, $reverse)) {
						$out[] = (($microdata) ? '<span itemprop="addressLocality">' : '') . $location_string . (($microdata) ? '</span>' : '');
						$separator_previous = false;
					} elseif (in_array($separator_previous, array('space','comma','break')))
						array_pop($out);
					break;
				case 'line-1':
					if (trim($this->address_line_1) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_address_line_1']) {
						$out[] = (($microdata) ? '<span itemprop="streetAddress">' : '') . trim($this->address_line_1) . (($microdata) ? '</span>' : '');
						$separator_previous = false;
					} elseif (in_array($separator_previous, array('space','comma','break')))
						array_pop($out);
					break;
				case 'line-2':
					if (trim($this->address_line_2) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_address_line_2']) {
						$out[] = trim($this->address_line_2);
						$separator_previous = false;
					} elseif (in_array($separator_previous, array('space','comma','break')))
						array_pop($out);
					break;
				case 'zip':
					if (trim($this->zip_or_postal_index) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_postal_index']) {
						$out[] = (($microdata) ? '<span itemprop="postalCode">' : '') . trim($this->zip_or_postal_index) . (($microdata) ? '</span>' : '');
						$separator_previous = false;
					} elseif (in_array($separator_previous, array('space','comma','break')))
						array_pop($out);
					break;
				case 'space1':
				case 'space2':
				case 'space3':
					if ($separator_previous != 'break' && $out) {
						$out[] = ' ';
						$separator_previous = 'space';
					}
					break;
				case 'comma1':
				case 'comma2':
				case 'comma3':
					if ($separator_previous != 'break' && $out) {
						$out[] = ', ';
						$separator_previous = 'comma';
					}
					break;
				case 'break1':
				case 'break2':
					if ($separator_previous != 'break' && $out) {
						$out[] = '<br />';
						$separator_previous = 'break';
					}
					break;
			}
		}

		return trim(implode('', $out), ', ');
	}
	
	public function display_info_field_on_map() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_additional_info'])
			return $this->additional_info;
	}
}

?>