<?php 

class directorypress_field_search {
	public $value;

	public $field;
	
	public function assign_fields($field) {
		$this->field = $field;
	}
	
	public function convert_search_options() {
		if ($this->field->search_options) {
			if (is_string($this->field->search_options)) {
				$unserialized_options = unserialize($this->field->search_options);
			} elseif (is_array($this->field->search_options)) {
				$unserialized_options = $this->field->search_options;
			}
			if (count($unserialized_options) > 1 || $unserialized_options != array('')) {
				$this->field->search_options = $unserialized_options;
				if (method_exists($this, 'build_search_options')) {
					$this->build_search_options();
				}
				return $this->field->search_options;
			}
		}
		return array();
	}
	
	public function gat_base_url_args(&$args) {
		$field_index = 'field_' . $this->field->slug;
		
		if (isset($_REQUEST[$field_index]) && $_REQUEST[$field_index])
			$args[$field_index] = sanitize_text_field($_REQUEST[$field_index]);
	}
	
	public function gat_vc_params() {
		return array();
	}
	
	public function is_this_field_param($param) {
		if ($param == 'field_' . $this->field->slug) {
			return true;
		}
	}
	
	public function reset_field_value() {
		$this->value = null;
	}
	
	public function field_label($search_form) {
		$label = (isset($this->field->field_search_label) && !empty($this->field->field_search_label))? $this->field->field_search_label: $this->field->name;
		if($this->field->is_hide_name_on_search && $search_form->form_layout == 'vertical'){
			echo '<div class="search-content-field-label">';
				echo '<label>'. esc_html($label) .'</label>';
			echo '</div>';
		}elseif(!$this->field->is_hide_name_on_search){
			echo '<div class="search-content-field-label">';
				echo '<label>'. esc_html($label) .'</label>';
			echo '</div>';
		}
	}
	public function field_width($search_form) {
		if($search_form->form_layout == 'horizontal' && directorypress_is_archive_page()){
			$width = $this->field->fieldwidth_archive;
		}elseif($search_form->form_layout == 'horizontal' && !directorypress_is_archive_page()){
			$width = $this->field->fieldwidth;
		}else{
			$width = '100';
		}
		return $width;
	}
}
?>