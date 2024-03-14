<?php 
class directorypress_field_select extends directorypress_field {
	public $selection_items = array();
	protected $can_be_ordered = false;
	protected $is_configuration_page = true;
	protected $is_search_configuration_page = true;
	protected $can_be_searched = true;
	
	public function is_field_not_empty($listing) {
		if ($this->value)
			return true;
		else
			return false;
	}
	
	public function __construct() {
		// adapted for WPML
		add_action('init', array($this, 'fields_options_into_strings'));
	}

	public function configure($id, $action = '') {
		global $wpdb, $directorypress_object;

		wp_enqueue_script('jquery-ui-sortable');

		if ($action == 'config') {
			$validation = new directorypress_form_validation();
			$validation->set_rules('selection_items[]', __('Options', 'DIRECTORYPRESS'), 'required');
			if ($validation->run()) {
				$result = $validation->result_array();
				
				$insert_update_args['selection_items'] = $result['selection_items[]'];

				$insert_update_args = apply_filters('directorypress_selection_items_update_args', $insert_update_args, $this, $result);
				if ( current_user_can( 'manage_options' ) ) {
					if ($insert_update_args) {
						$wpdb->update($wpdb->directorypress_fields, array('options' => serialize($insert_update_args)), array('id' => $id), null, array('%d'));
					}
				}else{
					directorypress_add_notification(__('no permission!', 'DIRECTORYPRESS'), 'error');
				}

				directorypress_add_notification(__('updated successfully!', 'DIRECTORYPRESS'));
				
				do_action('directorypress_update_selection_items', $result['selection_items[]'], $this);
			} else {
				$this->selection_items = $validation->result_array('selection_items[]');
				directorypress_add_notification($validation->error_array(), 'error');

				$field = $this;
				include('_html/configuration.php');
			}
		} else {
			$field = $this;
			include('_html/configuration.php');
		}
	}
	
	public function build_field_options() {
		if (isset($this->options['selection_items'])) {
			$this->selection_items = $this->options['selection_items'];
		}
	}
	
	public function renderInput() {
		$field = $this;
		include('_html/input.php');
	}
	
	public function validate_field_values(&$errors, $data) {
		$field_index = 'directorypress-field-input-' . $this->id;

		$validation = new directorypress_form_validation();
		$rules = '';
		//if ($this->is_this_field_requirable() && $this->is_required)
			//$rules .= '|required';
		$validation->set_rules($field_index, $this->name);
		if (!$validation->run())
			$errors[] = implode("", $validation->error_array());
		elseif ($selected_item = $validation->result_array($field_index)) {
			if (!in_array($selected_item, array_keys($this->selection_items)))
				$errors[] = sprintf(__("This selection option index \"%d\" doesn't exist", 'DIRECTORYPRESS'), $selected_item);

			return $selected_item;
		}elseif ($this->is_this_field_requirable() && $this->is_required){
			$errors[] = sprintf(__('"%s" field is required', 'DIRECTORYPRESS'), $this->name);
		}
	}
	
	public function save_field_value($post_id, $validation_results) {
		return update_post_meta($post_id, '_field_' . $this->id, $validation_results);
		return true;
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
		if ($value) {
			if (array_key_exists($value, $this->selection_items))
				return $value;
				
			if (!in_array($value, $this->selection_items))
				$errors[] = sprintf(__("This selection option \"%s\" doesn't exist", 'DIRECTORYPRESS'), $value);
			else
				return array_search($value, $this->selection_items);
		} else 
			return '';
	}
	
	public function disaply_output_on_map($location, $listing) {
		if ($this->value && isset($this->selection_items[$this->value]))
			return $this->selection_items[$this->value];
	}

	// adapted for WPML
	public function fields_options_into_strings() {
		global $sitepress;

		if (function_exists('wpml_object_id_filter') && $sitepress) {
			foreach ($this->selection_items AS $key=>&$item) {
				$item = apply_filters('wpml_translate_single_string', $item, 'DirectoryPress', 'The option #' . $key . ' of field #' . $this->id);
			}
		}
	}
}

add_filter('directorypress_selection_items_update_args', 'directorypress_filter_selection_items', 10, 3);
function directorypress_filter_selection_items($insert_update_args, $field, $array) {
	global $sitepress, $wpdb;

	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($sitepress->get_default_language() != ICL_LANGUAGE_CODE) {
			if (isset($_GET['action']) && $_GET['action'] == 'configure' && isset($_GET['field_id'])) {
				foreach ($insert_update_args['selection_items'] AS $key=>$item) {
					$field_id = sanitize_text_field($_GET['field_id']);
					if ($option_string_id = icl_st_is_registered_string('DirectoryPress', 'The option #' . $key . ' of field #' . $field_id))
						icl_add_string_translation($option_string_id, ICL_LANGUAGE_CODE, $item, ICL_TM_COMPLETE);
					unset($insert_update_args['selection_items']);
				}
			}
		}
	}
	return $insert_update_args;
}

add_action('directorypress_update_selection_items', 'directorypress_update_selection_items', 10, 2);
function directorypress_update_selection_items($selection_items, $field) {
	global $sitepress;

	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($sitepress->get_default_language() == ICL_LANGUAGE_CODE) {
			foreach ($selection_items AS $key=>&$item) {
				do_action('wpml_register_single_string', 'DirectoryPress',  'The option #' . $key . ' of field #' . $field->id, $item);
			}
		}
	}
}
?>