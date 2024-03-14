<?php 

class directorypress_field_select_search extends directorypress_field_search {
	public $search_input_mode = 'checkboxes';
	public $checkboxes_operator = 'OR';
	public $items_count = 1;
	
	public function search_configure($id, $action = '') {
		global $wpdb, $directorypress_object;
	
		if ($action == 'search_config') {
			$validation = new directorypress_form_validation();
			$validation->set_rules('search_input_mode', __('Search input mode', 'DIRECTORYPRESS'), 'required');
			$validation->set_rules('checkboxes_operator', __('Operator for the search', 'DIRECTORYPRESS'), 'required');
			$validation->set_rules('items_count', __('Items counter', 'DIRECTORYPRESS'), 'is_checked');
			if ($validation->run()) {
				if ( current_user_can( 'manage_options' ) ) {
					$result = $validation->result_array();
					if ($wpdb->update($wpdb->directorypress_fields, array('search_options' => serialize(array('search_input_mode' => $result['search_input_mode'], 'checkboxes_operator' => $result['checkboxes_operator'], 'items_count' => $result['items_count']))), array('id' => $id), null, array('%d'))){
						directorypress_add_notification(__('Search field configuration was updated successfully!', 'DIRECTORYPRESS'));
					}
				}else{
					directorypress_add_notification(__('no permission!', 'DIRECTORYPRESS'), 'error');
				}
			} else {
				$this->search_input_mode = $validation->result_array('search_input_mode');
				$this->checkboxes_operator = $validation->result_array('checkboxes_operator');
				$this->items_count = $validation->result_array('items_count');
				directorypress_add_notification($validation->error_array(), 'error');
	
				$search_field = $this;
				include('_html/configuration.php');
			}
		} else{
			$search_field = $this;
			include('_html/configuration.php');
		}
	}
	
	public function build_search_options() {
		if (isset($this->field->search_options['search_input_mode'])) {
			$this->search_input_mode = $this->field->search_options['search_input_mode'];
		}
		if (isset($this->field->search_options['checkboxes_operator'])) {
			$this->checkboxes_operator = $this->field->search_options['checkboxes_operator'];
		}
		if (isset($this->field->search_options['items_count'])) {
			$this->items_count = $this->field->search_options['items_count'];
		}
	}

	public function display_search($search_form, $defaults = array()) {
		if ($this->search_input_mode =='radiobutton' && count($this->field->selection_items)) {
			$this->field->selection_items = array('' => __('All', 'DIRECTORYPRESS')) + $this->field->selection_items;
		}

		if (is_null($this->value)) {
			if (isset($defaults['field_' . $this->field->slug])) {
				$this->value = $defaults['field_' . $this->field->slug];
				if (!is_array($this->value)) {
					$this->value = array_filter(explode(',', $this->value), 'strlen');
				}
			}
		}
		
		if (!$this->value) {
			$this->value = array('');
		}
		
		$items_count_array = array();
		if ($this->items_count) {
			global $wpdb, $directorypress_object, $sitepress;
			
			$sql = "
					SELECT COUNT(DISTINCT(pm.post_id)) AS count, pm.meta_value FROM {$wpdb->posts} AS po
					LEFT JOIN {$wpdb->postmeta} AS pm ON po.ID = pm.post_id"

					. ($directorypress_object->directorytypes->isMultiDirectory() ? " LEFT JOIN {$wpdb->postmeta} AS pm1 ON po.ID = pm1.post_id " : " ")
					
					. ((function_exists('wpml_object_id_filter') && $sitepress) ? " LEFT JOIN {$wpdb->prefix}icl_translations ON po.ID = {$wpdb->prefix}icl_translations.element_id " : " ")
					
					. "WHERE 
						pm.meta_key = '_field_" . $this->field->id . "'
					AND
						po.post_status = 'publish'"
								
					. ($directorypress_object->directorytypes->isMultiDirectory() ? " AND
						pm1.meta_key = '_directory_id'
					AND
						pm1.meta_value = " . $directorypress_object->current_directorytype->id . " " : " ")
						
					. ((function_exists('wpml_object_id_filter') && $sitepress) ? " AND
						{$wpdb->prefix}icl_translations.language_code = '".ICL_LANGUAGE_CODE."' " : " ")
						
					. "GROUP BY pm.meta_value
			";
			
			$items_count_results = $wpdb->get_results($sql, ARRAY_A);
			
			foreach ($items_count_results AS $items_count) {
				$items_count_array[$items_count['meta_value']] = $items_count['count'];
			}
		}

		$search_field = $this;
		if ($this->search_input_mode == 'checkboxes'){
			include('_html/checkbox.php');
		}elseif($this->search_input_mode =='radiobutton'){
			include('_html/radio.php');
		}elseif($this->search_input_mode =='selectbox'){
			include('_html/selectbox.php');
		}
	}
	
	public function search_validation(&$args, $defaults = array(), $include_GET_params = true) {
		$field_index = 'field_' . $this->field->slug;
	
		if ($include_GET_params)
			$this->value = ((directorypress_get_input_value($_REQUEST, $field_index, false) !== false) ? directorypress_get_input_value($_REQUEST, $field_index) : directorypress_get_input_value($defaults, $field_index));
		else
			$this->value = directorypress_get_input_value($defaults, $field_index, false);

		if ($this->value) {
			if (!is_array($this->value)) {
				$this->value = array_filter(explode(',', $this->value), 'strlen');
			}

			$args['meta_query']['relation'] = 'AND';
			if ($this->checkboxes_operator == 'OR') {
				$args['meta_query'][] = array(
						'key' => '_field_' . $this->field->id,
						'value' => $this->value,
						'compare' => 'IN'
				);
			} elseif ($this->checkboxes_operator == 'AND') {
				foreach ($this->value AS $val) {
					$args['meta_query'][] = array(
							'key' => '_field_' . $this->field->id,
							'value' => $val
					);
				}
			}
		}
	}
	
	public function gat_vc_params() {
		return array(
				array(
						'type' => 'checkbox',
						'param_name' => 'field_' . $this->field->slug,
						'heading' => $this->field->name,
						'value' => array_flip($this->field->selection_items),
				),
		);
	}
	
	public function reset_field_value() {
		$this->value = array();
	}
}
?>