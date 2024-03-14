<?php 

class directorypress_field_address extends directorypress_field {
	protected $can_be_required = true;
	protected $can_be_ordered = false;
	protected $is_categories = false;
	protected $is_slug = false;
	
	public function is_field_not_empty($listing) {
		foreach ($listing->locations AS $location)
			if ($location->get_full_address())
				return true;

		return false;
	}

	public function display_output($listing) {
		if ($listing->package->location_number_allowed) {
			$field = $this;
			include('_html/output.php');
		}
	}
	
	public function disaply_output_on_map($location, $listing) {
		if ($listing->package->location_number_allowed >= 0)
			return $location->get_full_address();
	}
}
?>