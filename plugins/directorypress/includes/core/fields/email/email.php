<?php 

class directorypress_field_email extends directorypress_field {
	protected $can_be_ordered = false;
	
	public function is_field_not_empty($listing) {
		if ($this->value)
			return true;
		else
			return false;
	}

	public function renderInput() {
		$field = $this;
		include('_html/input.php');
	}
	
	public function validate_field_values(&$errors, $data) {
		$field_index = 'directorypress-field-input-' . $this->id;

		$validation = new directorypress_form_validation();
		$rules = 'valid_email';
		if ($this->is_this_field_requirable() && $this->is_required)
			$rules .= '|required';
		$validation->set_rules($field_index, $this->name, $rules);
		if (!$validation->run())
			$errors[] = implode("", $validation->error_array());
	
		return $validation->result_array($field_index);
	}
	
	public function save_field_value($post_id, $validation_results) {
		return update_post_meta($post_id, '_field_' . $this->id, $validation_results);
	}
	
	public function load_field_value($post_id) {
		$this->value = get_post_meta($post_id, '_field_' . $this->id, true);
		
		$this->value = apply_filters('directorypress_field_load', $this->value, $this, $post_id);
		return $this->value;
	}
	
	public function display_output($listing = null) {
		$field = $this;
		include('_html/output.php');
	}
	
	public function validate_csv_values($value, &$errors) {
		$validation = new directorypress_form_validation();
		if (!$validation->valid_email($value))
			$errors[] = __("Email field is invalid", "DIRECTORYPRESS");
		return $value;
	}
	
	public function disaply_output_on_map($location, $listing) {
		$email = antispambot($this->value);
		if (function_exists('iconv') && function_exists('mb_detect_encoding') && function_exists('mb_detect_order')) {
			$email = iconv(mb_detect_encoding($email, mb_detect_order(), true), "UTF-8", $email);
		}
		
		$field = $this;
		include('_html/map.php');
	}
}
?>