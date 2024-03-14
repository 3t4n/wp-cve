<?php 

class directorypress_field_link extends directorypress_field {
	public $is_blank = false;
	public $is_nofollow = false;
	public $use_link_text = 1;
	public $default_link_text = '';
	public $use_default_link_text = 0;
	public $value = array('url' => '', 'text' => '');
	
	protected $can_be_ordered = false;
	protected $is_configuration_page = true;
	
	public function is_field_not_empty($listing) {
		if ($this->value['url'])
			return true;
		else
			return false;
	}

	public function configure($id, $action = '') {
		global $wpdb, $directorypress_object;

		if ($action == 'config') {
			$validation = new directorypress_form_validation();
			$validation->set_rules('is_blank', __('Open link in new window', 'DIRECTORYPRESS'), 'is_checked');
			$validation->set_rules('is_nofollow', __('Add nofollow attribute', 'DIRECTORYPRESS'), 'is_checked');
			$validation->set_rules('use_link_text', __('Default link text', 'DIRECTORYPRESS'), 'is_checked');
			$validation->set_rules('default_link_text', __('Default link text', 'DIRECTORYPRESS'));
			$validation->set_rules('use_default_link_text', __('Use default link text', 'DIRECTORYPRESS'), 'is_checked');
			if ($validation->run()) {
				if ( current_user_can( 'manage_options' ) ) {
					$result = $validation->result_array();
					if ($wpdb->update($wpdb->directorypress_fields, array('options' => serialize(array('is_blank' => $result['is_blank'], 'is_nofollow' => $result['is_nofollow'], 'use_link_text' => $result['use_link_text'], 'default_link_text' => $result['default_link_text'], 'use_default_link_text' => $result['use_default_link_text']))), array('id' => $id), null, array('%d'))){
						directorypress_add_notification(__('Field configuration was updated successfully!', 'DIRECTORYPRESS'));
					}
				}else{
					directorypress_add_notification(__('no permission!', 'DIRECTORYPRESS'), 'error');
				}
			} else {
				$this->is_blank = $validation->result_array('is_blank');
				$this->is_nofollow = $validation->result_array('is_nofollow');
				$this->use_link_text = $validation->result_array('use_link_text');
				$this->default_link_text = $validation->result_array('default_link_text');
				$this->use_default_link_text = $validation->result_array('use_default_link_text');
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
		if (isset($this->options['is_blank']))
			$this->is_blank = $this->options['is_blank'];

		if (isset($this->options['is_nofollow']))
			$this->is_nofollow = $this->options['is_nofollow'];

		if (isset($this->options['use_link_text']))
			$this->use_link_text = $this->options['use_link_text'];

		if (isset($this->options['default_link_text']))
			$this->default_link_text = $this->options['default_link_text'];

		if (isset($this->options['use_default_link_text']))
			$this->use_default_link_text = $this->options['use_default_link_text'];
	}
	
	public function renderInput() {
		// Default link text
		if ($this->value['text'] == '')
			$this->value['text'] = $this->default_link_text;

		$field = $this;
		include('_html/input.php');
	}
	
	public function validate_field_values(&$errors, $data) {
		$field_index_url = 'directorypress-field-input-url_' . $this->id;
		$field_index_text = 'directorypress-field-input-text_' . $this->id;

		$validation = new directorypress_form_validation();
		$rules = 'valid_url[1]'; // 1 - is the second parameter must be $prepare_url=true
		if ($this->is_this_field_requirable() && $this->is_required)
			$rules .= '|required';
		$validation->set_rules($field_index_url, $this->name, $rules);
		$validation->set_rules($field_index_text, $this->name);
		if (!$validation->run())
			$errors[] = implode("", $validation->error_array());

		return array('url' => $validation->result_array($field_index_url), 'text' => $validation->result_array($field_index_text));
	}
	
	public function save_field_value($post_id, $validation_results) {
		return update_post_meta($post_id, '_field_' . $this->id, $validation_results);
	}
	
	public function load_field_value($post_id) {
		if ($value = get_post_meta($post_id, '_field_' . $this->id, true)) {
			$this->value = maybe_unserialize($value);
		}
		
		// Default link text
		if (empty($this->value['text']) && $this->use_default_link_text) {
			$this->value['text'] = $this->default_link_text;
		}

		$this->value = apply_filters('directorypress_field_load', $this->value, $this, $post_id);
		return $this->value;
	}
	
	public function display_output($listing = null) {
		$field = $this;
		include('_html/output.php');
	}
	
	public function validate_csv_values($value, &$errors) {
		$value = explode('>', $value);
		$url = $value[0];
		$validation = new directorypress_form_validation();
		if (!$validation->valid_url($url))
			$errors[] = __("Website URL field is invalid", "DIRECTORYPRESS");

		$text = (isset($value[1]) ? $value[1] : '');
		return array('url' => $url, 'text' => $text);
	}
	
	public function export_field_to_csv() {
		if ($this->value['url']) {
			$output = $this->value['url'];
			if ($this->value['text'] && (!$this->use_default_link_text || $this->value['text'] != $this->default_link_text))
				$output .= ">" . $this->value['text'];
			return  $output;
		}
	}
	
	public function disaply_output_on_map($location, $listing) {
		$field = $this;
		include('_html/map.php');
	}
}
?>