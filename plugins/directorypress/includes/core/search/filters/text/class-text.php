<?php 

class directorypress_field_text_search extends directorypress_field_search {
	public $search_input_mode = 'keywords';
	
	public function search_configure($id, $action = '') {
		global $wpdb, $directorypress_object;
	
		if ($action == 'search_config') {
			$validation = new directorypress_form_validation();
			$validation->set_rules('search_input_mode', __('Search input mode', 'DIRECTORYPRESS'), 'required');
			if ($validation->run()) {
				if ( current_user_can( 'manage_options' ) ) {
					$result = $validation->result_array();
					if ($wpdb->update($wpdb->directorypress_fields, array('search_options' => serialize(array('search_input_mode' => $result['search_input_mode']))), array('id' => $id), null, array('%d'))){
						directorypress_add_notification(__('Search field configuration was updated successfully!', 'DIRECTORYPRESS'));
					}
				}else{
					directorypress_add_notification(__('no permission!', 'DIRECTORYPRESS'), 'error');
				}
			} else {
				$this->search_input_mode = $validation->result_array('search_input_mode');
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
		if (isset($this->field->search_options['search_input_mode']))
			$this->search_input_mode = $this->field->search_options['search_input_mode'];
	}

	public function display_search($search_form, $defaults = array()) {
		if ($this->search_input_mode == 'input') {
			if (is_null($this->value) && isset($defaults['field_' . $this->field->slug])) {
				$this->value = $defaults['field_' . $this->field->slug];
			}
			
			$search_field = $this;
			include('_html/input.php');
		}
	}
	
	public function search_validation(&$args, $defaults = array(), $include_GET_params = true) {
		if ($this->search_input_mode == 'input') {
			$field_index = 'field_' . $this->field->slug;
	
			if ($include_GET_params)
				$this->value = ((directorypress_get_input_value($_REQUEST, $field_index, false) !== false) ? directorypress_get_input_value($_REQUEST, $field_index) : directorypress_get_input_value($defaults, $field_index));
			else
				$this->value = directorypress_get_input_value($defaults, $field_index, false);
	
			if ($this->value !== false && $this->value !== "") {
				$args['meta_query']['relation'] = 'AND';
				$args['meta_query'][] = array(
						'key' => '_field_' . $this->field->id,
						'value' => stripslashes($this->value),
						'compare' => 'LIKE'
				);
			}
		} elseif ($this->search_input_mode == 'keywords' && $this->field->on_search_form) {
			if (!empty($args['s'])) {
				$this->value = $args['s'];
	
				//var_dump($include_GET_params);
				//if (!has_filter('posts_clauses', array($this, 'posts_clauses'))) {
					//var_dump(11);
					add_filter('posts_clauses', array($this, 'posts_clauses'), 11, 2);
				//}
			}
		}
	}
	
	public function posts_clauses($clauses, $q) {
		global $wpdb;

		$postmeta_table = 'directorypress_postmeta_' . $this->field->id;
		
		if ($this->value && strpos($clauses['join'], $postmeta_table) === false) { 
			$clauses['join'] .=' LEFT JOIN '.$wpdb->postmeta. ' AS ' . $postmeta_table . ' ON '. $wpdb->posts . '.ID = ' . $postmeta_table . '.post_id ';
			
			$postmeta_where = ' AND ' . $postmeta_table . '.meta_key = "_field_' . $this->field->id . '" ';
			
			$clauses['where'] = preg_replace(
					"/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
					"(".$wpdb->posts.".post_title LIKE $1) OR (".$postmeta_table.".meta_value LIKE $1 ".$postmeta_where.")", $clauses['where']);
			
			// Add GROUP BY posts.ID (for some occasions it becomes missing in the result query)
			$clauses['groupby'] = "{$wpdb->posts}.ID";
		}
		
		return $clauses;
	}
	
	public function gat_vc_params() {
		return array(
				array(
						'type' => 'textfield',
						'param_name' => 'field_' . $this->field->slug,
						'heading' => $this->field->name,
				),
		);
	}
}
?>