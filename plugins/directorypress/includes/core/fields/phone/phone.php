<?php 

class directorypress_field_text extends directorypress_field {
	public $max_length = 255;
	public $regex;
	public $is_phone;
	
	protected $can_be_searched = true;
	protected $is_configuration_page = true;
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
			$validation->set_rules('regex', __('PHP RegEx template', 'DIRECTORYPRESS'));
			$validation->set_rules('is_phone', __('Is phone field', 'DIRECTORYPRESS'), 'is_checked');
			if ($validation->run()) {
				$result = $validation->result_array();
				if ($wpdb->update($wpdb->directorypress_fields, array('options' => serialize(array('max_length' => $result['max_length'], 'regex' => $result['regex'], 'is_phone' => $result['is_phone']))), array('id' => $id), null, array('%d'))){
					directorypress_add_notification(__('Field configuration was updated successfully!', 'DIRECTORYPRESS'));
				}
			} else {
				$this->max_length = $validation->result_array('max_length');
				$this->regex = $validation->result_array('regex');
				$this->is_phone = $validation->result_array('is_phone');
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

		if (isset($this->options['regex']))
			$this->regex = $this->options['regex'];

		if (isset($this->options['is_phone']))
			$this->is_phone = $this->options['is_phone'];
		
	}
	
	public function renderInput() {
		$field = $this;
		include('_html/input.php');
	}
	
	public function validate_field_values(&$errors, $data) {
		$field_index = 'directorypress-field-input-' . $this->id;
		
		if (isset($_POST[$field_index]) && $_POST[$field_index] && $this->regex)
			if (@!preg_match('/^' . $this->regex . '$/', $_POST[$field_index]))
				$errors[] = sprintf(__("Field %s doesn't match template!", 'DIRECTORYPRESS'), $this->name);

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
		return $this->value;
	}
	
	public function display_output($listing = null) {
		$field = $this;
		include('_html/output.php');
	}
	
	public function order_params() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		$order_params = array('orderby' => 'meta_value', 'meta_key' => '_field_' . $this->id);
		if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_orderby_exclude_null'])
			$order_params['meta_query'] = array(
				array(
					'key' => '_field_' . $this->id,
					'value'   => array(''),
					'compare' => 'NOT IN'
				)
			);
		return $order_params;
	}

	public function validate_csv_values($value, &$errors) {
		if (!is_string($value))
			$errors[] = sprintf(__('Field %s must be a string!', 'DIRECTORYPRESS'), $this->name);
		elseif ($this->regex && @!preg_match('/^' . $this->regex . '$/', $value))
			$errors[] = sprintf(__("Field %s doesn't match template!", 'DIRECTORYPRESS'), $this->name);
		elseif (strlen($value) > $this->max_length)
			$errors[] = sprintf(__('The %s field can not exceed %s characters in length.', 'DIRECTORYPRESS'), $this->name, $this->max_length);
		else
			return $value;
	}
	
	public function disaply_output_on_map($location, $listing) {
		if ($this->is_phone) {
			$phone = antispambot($this->value);
			if (function_exists('iconv') && function_exists('mb_detect_encoding') && function_exists('mb_detect_order')) {
				$phone = iconv(mb_detect_encoding($phone, mb_detect_order(), true), "UTF-8", $phone);
			}
			return $phone;
		} else {
			return $this->value;
		}
	}
}
?>