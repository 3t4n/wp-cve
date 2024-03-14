<?php 

class directorypress_field_summary extends directorypress_field {
	protected $can_be_ordered = false;
	protected $is_categories = false;
	protected $is_slug = false;
	
	public function is_field_not_empty($listing) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		if (post_type_supports(DIRECTORYPRESS_POST_TYPE, 'excerpt') && ($listing->post->post_excerpt || ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_cropped_content_as_excerpt'] && $listing->post->post_content !== '')))
			return true;
		else
			return false;
	}

	public function validate_field_values(&$errors, $data) {
		$listing = directorypress_pull_current_listing_admin();
		if (post_type_supports(DIRECTORYPRESS_POST_TYPE, 'excerpt') && $this->is_required && (!isset($data['post_excerpt']) || !$data['post_excerpt']))
			$errors[] = __('Listing excerpt is required', 'DIRECTORYPRESS');
		else
			return $listing->post->post_excerpt;
	}
	
	public function display_output($listing) {
		$field = $this;
		include('_html/output.php');
	}
	
	public function display_outputValue($listing) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		$field = $this;
		the_excerpt_max_charlength($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_excerpt_length']);
	}
	
	public function disaply_output_on_map($location, $listing) {
		return $listing->post->post_excerpt;
	}
}
?>