<?php 

class directorypress_field_textarea extends directorypress_field {
	public $max_length = 500;
	public $html_editor = false;
	public $do_shortcodes = false;

	protected $can_be_ordered = false;
	protected $is_configuration_page = true;
	protected $can_be_searched = true;
	protected $is_search_configuration_page = true;
	
	public function is_field_not_empty($listing) {
		if ($this->value)
			return true;
		else
			return false;
	}

	public function configure($id, $action = '') {
		global $wpdb, $directorypress_object;

		if ($action == 'config') {
			$validation = new directorypress_form_validation();
			$validation->set_rules('max_length', __('Max length', 'DIRECTORYPRESS'), 'required|is_natural_no_zero');
			$validation->set_rules('html_editor', __('HTML editor enabled', 'DIRECTORYPRESS'), 'is_checked');
			$validation->set_rules('do_shortcodes', __('Shortcodes processing', 'DIRECTORYPRESS'), 'is_checked');
			if ($validation->run()) {
				$result = $validation->result_array();
				if ( current_user_can( 'manage_options' ) ) {
					if ($wpdb->update($wpdb->directorypress_fields, array('options' => serialize(array('max_length' => $result['max_length'], 'html_editor' => $result['html_editor'], 'do_shortcodes' => $result['do_shortcodes']))), array('id' => $id), null, array('%d'))){
						directorypress_add_notification(__('Field configuration was updated successfully!', 'DIRECTORYPRESS'));
					}
				}else{
					directorypress_add_notification(__('no permission!', 'DIRECTORYPRESS'), 'error');
				}
			} else {
				$this->max_length = $validation->result_array('max_length');
				$this->html_editor = $validation->result_array('html_editor');
				$this->do_shortcodes = $validation->result_array('do_shortcodes');
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
		if (isset($this->options['max_length']))
			$this->max_length = $this->options['max_length'];
		if (isset($this->options['html_editor']))
			$this->html_editor = $this->options['html_editor'];
		if (isset($this->options['do_shortcodes']))
			$this->do_shortcodes = $this->options['do_shortcodes'];
	}
	
	public function renderInput() {
		$field = $this;
		include('_html/input.php');
	}
	
	public function validate_field_values(&$errors, $data) {
		$field_index = 'directorypress-field-input-' . $this->id;
	
		$validation = new directorypress_form_validation();
		$rules = 'max_length[' . $this->max_length . ']';
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
		
		if (!$this->html_editor) {
			$this->value = strip_tags($this->value);
		}

		return $this->value;
	}
	
	public function display_output($listing = null) {
		add_filter('the_content', 'wpautop');
		if (!$this->do_shortcodes)
			remove_filter('the_content', 'do_shortcode', 11);

		$field = $this;
		include('_html/output.php');

		if (!$this->do_shortcodes)
			add_filter('the_content', 'do_shortcode', 11);
		remove_filter('the_content', 'wpautop');
	}
	
	public function validate_csv_values($value, &$errors) {
		if (strlen($value) > $this->max_length)
			$errors[] = sprintf(__('The %s field can not exceed %s characters in length.', 'DIRECTORYPRESS'), $this->name, $this->max_length);
		else
			return $value;
	}
	
	public function disaply_output_on_map($location, $listing) {
		return $this->value;
	}
}
?>