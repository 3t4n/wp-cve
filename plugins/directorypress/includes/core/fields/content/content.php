<?php 

class directorypress_field_content extends directorypress_field {
	protected $can_be_ordered = false;
	protected $is_categories = false;
	protected $is_slug = false;
	
	public function is_field_not_empty($listing) {
		if (post_type_supports(DIRECTORYPRESS_POST_TYPE, 'editor') && !empty($listing->post->post_content))
			return true;
		else
			return false;
	}

	public function validate_field_values(&$errors, $data) {
		$listing = directorypress_pull_current_listing_admin();
		if (post_type_supports(DIRECTORYPRESS_POST_TYPE, 'editor') && $this->is_required && (!isset($data['post_content']) || !$data['post_content']))
			$errors[] = sprintf(__('"%s" field is required', 'DIRECTORYPRESS'), $this->name);
		else
			return $listing->post->post_content;
	}
	
	public function display_output($listing) {
		$field = $this;
		include('_html/output.php');
	}
	
	public function disaply_output_on_map($location, $listing) {
		return wpautop($listing->post->post_content);
	}
}
?>