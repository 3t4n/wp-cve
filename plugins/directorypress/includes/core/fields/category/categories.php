<?php 

class directorypress_field_categories extends directorypress_field {
	protected $can_be_required = true;
	protected $can_be_ordered = false;
	protected $is_categories = false;
	protected $is_slug = false;
	protected $is_configuration_page = true;
	public $is_multiselect = 1;
	public $allow_listing_in_parent = 1;
	
	public function is_field_not_empty($listing) {
		if (has_term('', DIRECTORYPRESS_CATEGORIES_TAX, $listing->post->ID))
			return true;
		else
			return false;
	}
	
	public function configure($id, $action = '') {
		global $wpdb, $directorypress_object;

		if ($action == 'config') {
			$validation = new directorypress_form_validation();
			$validation->set_rules('is_multiselect', __('Is Multi Select Field?', 'DIRECTORYPRESS'), 'is_checked');
			$validation->set_rules('allow_listing_in_parent', __('Allow listing submission in parent category?', 'DIRECTORYPRESS'), 'is_checked');
			if ($validation->run()) {
				if ( current_user_can( 'manage_options' ) ) {
					$result = $validation->result_array();
					if ($wpdb->update($wpdb->directorypress_fields, array('options' => serialize(array('is_multiselect' => $result['is_multiselect'], 'allow_listing_in_parent' => $result['allow_listing_in_parent']))), array('id' => $id), null, array('%d'))){
						directorypress_add_notification(__('Field configuration was updated successfully!', 'DIRECTORYPRESS'));
					}
				}else{
					directorypress_add_notification(__('no permission!', 'DIRECTORYPRESS'), 'error');
				}
			} else {
				$this->is_multiselect = $validation->result_array('is_multiselect');
				$this->allow_listing_in_parent = $validation->result_array('allow_listing_in_parent');
				directorypress_add_notification($validation->error_array(), 'error');

				$field = $this;
				include('_html/configuration.php');
			}
		} else{
			$field = $this;
			include('_html/configuration.php');
		}
	}
	
	public function build_field_options() {
		if (isset($this->options['is_multiselect'])){
			$this->is_multiselect = $this->options['is_multiselect'];
		}
		if (isset($this->options['allow_listing_in_parent'])){
			$this->allow_listing_in_parent = $this->options['allow_listing_in_parent'];
		}
		
	}

	public function display_output($listing) {
		$field = $this;
		include('_html/output.php');
	}
	
	public function disaply_output_on_map($location, $listing) {
		if (has_term('', DIRECTORYPRESS_CATEGORIES_TAX, $listing->post->ID)):
			return get_the_term_list($listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX, '', ', ', '');
		endif;
	}
}
?>