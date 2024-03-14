<?php 

class directorypress_field_tags extends directorypress_field {
	protected $can_be_required = false;
	protected $can_be_ordered = false;
	protected $is_categories = false;
	protected $is_slug = false;
	
	public function is_field_not_empty($listing) {
		if (has_term('', DIRECTORYPRESS_TAGS_TAX, $listing->post->ID))
			return true;
		else
			return false;
	}

	public function display_output($listing) {
		$field = $this;
		include('_html/output.php');
	}
	
	public function disaply_output_on_map($location, $listing) {
		$field = $this;
		include('_html/map.php');
	}
}
?>