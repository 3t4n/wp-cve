<?php

class directorypress_fields_admin {
	public $menu_page_hook;
	
	public function __construct() {
		if (directorypress_is_listing_admin_edit_page()) {
			add_action('add_meta_boxes', array($this, 'add_fields_metabox'));
			add_action('post_edit_form_tag', array($this, 'add_fields_form_encryption'));
		}
		
		add_action('admin_menu', array($this, 'menu'));

		add_action('delete_term_taxonomy', array($this, 'renew_assigned_categories'));
		
		add_action('admin_enqueue_scripts', array( $this, 'scripts' ));
		
	}
	public function scripts() {
		wp_enqueue_media();
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');
	}
	public function menu() {
			$this->menu_page_hook = add_submenu_page('directorypress-admin-panel',
				__('Fields', 'DIRECTORYPRESS'),
				__('Fields', 'DIRECTORYPRESS'),
				'administrator',
				'directorypress_fields',
				array($this, 'directorypress_fields')
			);
	}
	
	public function directorypress_fields() {
		$this->fields_list();
	}
	
	public function fields_list() {
		global $directorypress_object;
		
		$items = $directorypress_object->fields;
		wp_enqueue_script('jquery-ui-sortable');
		
		$items_list = $this->table($items);
		$group_items_list = $this->group_table($items);
		
		include('_html/fields_backend.php');
	}
	
	public function fields_list_ajax() {
		global $directorypress_object;
		$items = $directorypress_object->fields;
		wp_enqueue_script('jquery-ui-sortable');
		$items_list = $this->table($items);
		
		echo wp_kses_post($items_list);
		die();
	}
	public function fields_group_list_ajax() {
		global $directorypress_object;
		$items = $directorypress_object->fields;
		wp_enqueue_script('jquery-ui-sortable');
		$group_items_list = $this->group_table($items);
		
		echo wp_kses_post($group_items_list);
		die();
	}
	public function fields_list_order($new_order, $action = '') {
		global $directorypress_object;
		$items = $directorypress_object->fields;
		
			if ($action == 'reorder') {
				if (current_user_can( 'manage_options' ) ) {
					if ($items->saveOrder($new_order)){
						directorypress_add_notification(__('fields order updated!', 'DIRECTORYPRESS'), 'updated');
					}
				}else{
					directorypress_add_notification(__('no permission!', 'DIRECTORYPRESS'), 'error');
				}
			}
		
	}
	public function assign_field_group($id, $group_id) {
		global $directorypress_object;
		$items = $directorypress_object->fields;
		if (current_user_can( 'manage_options' ) ) {
			if ($items->save_field_group_relations($id, $group_id)){
				directorypress_add_notification(__('fields relations updated!', 'DIRECTORYPRESS'), 'updated');
			}
		}else{
			directorypress_add_notification(__('no permission!', 'DIRECTORYPRESS'), 'error');
		}
	}
	public function field_ajax_vars($id) {
		global $directorypress_object;
		$items = $directorypress_object->fields;
		$item = $items->get_field_by_id($id);
		echo '<script>';
			echo 'var directorypress_fields_data = ' . json_encode(
				array(
					'setting_button' => ($item->has_setting_support())? '<button type="button" class="btn btn-primary field_config" data-id="'. esc_attr($id) .'">'.__('Field Settings', 'directorypress-payment-manager').'</button>' : '',
					'search_setting_button' => ($item->has_search_support())? '<button type="button" class="btn btn-primary field_search_config" data-id="'.esc_attr($id).'">'.__('Search Settings', 'directorypress-payment-manager').'</button>' : '',
				)
			).';
		';
		echo '</script>';
	}
	public function configure($id) {
		global $directorypress_object;
		$items = $directorypress_object->fields;
		$item = $items->get_field_by_id($id);
		$this->field_ajax_vars($id);
		$on_listing_page = ($item->on_listing_page)? __('yes', 'directorypress-payment-manager') : __('No', 'directorypress-payment-manager');
		$on_map = ($item->on_map)? __('yes', 'directorypress-payment-manager') : __('No', 'directorypress-payment-manager');
		$searchable = ($item->on_search_form)? __('yes', 'directorypress-payment-manager') : __('No', 'directorypress-payment-manager');
		$is_ordered = ($item->is_ordered)? __('yes', 'directorypress-payment-manager') : __('No', 'directorypress-payment-manager');
		$on_exerpt_page = ($item->on_exerpt_page)? __('yes', 'directorypress-payment-manager') : __('No', 'directorypress-payment-manager');
		$on_listing_page = ($item->on_listing_page)? __('yes', 'directorypress-payment-manager') : __('No', 'directorypress-payment-manager');
		$on_exerpt_page_list = ($item->on_exerpt_page_list)? __('yes', 'directorypress-payment-manager') : __('No', 'directorypress-payment-manager');
		$is_required = ($item->is_required)? __('yes', 'directorypress-payment-manager') : __('No', 'directorypress-payment-manager');
		
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Id', 'directorypress-payment-manager').'</label><span>'. esc_html($item->id) .'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Name', 'directorypress-payment-manager').'</label><span>'. esc_html($item->name) .'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Type', 'directorypress-payment-manager').'</label><span>'. esc_html($item->type).'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Required?', 'directorypress-payment-manager').'</label><span>'. esc_html($is_required).'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Sortable?', 'directorypress-payment-manager').'</label><span>'. esc_html($is_ordered) .'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Searchable?', 'directorypress-payment-manager').'</label><span>'. esc_html($searchable) .'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Show on Map?', 'directorypress-payment-manager').'</label><span>'. esc_html($on_map) .'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Show on Detail Page?', 'directorypress-payment-manager').'</label><span>'. esc_html($on_listing_page) .'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Show on Grid View?', 'directorypress-payment-manager').'</label><span>'. esc_html($on_exerpt_page) .'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Show on List View?', 'directorypress-payment-manager').'</label><span>'. esc_html($on_exerpt_page_list) .'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list assign_field_group">';
			echo '<label>'.__('Assign A Group', 'directorypress-payment-manager').'</label><span>';
			echo '<select id="assign_field_group" data-id="' . esc_attr($item->id) . '" name="group_id_' . esc_attr($item->id) . '">';
			echo '<option value=0>' . __('- No Group -', 'DIRECTORYPRESS') . '</option>';
			foreach ($items->fields_groups_array AS $group)
				echo '<option value=' . esc_attr($group->id) . ' ' . selected(esc_attr($item->group_id), esc_attr($group->id)) . '>' . esc_html($group->name) . '</option>';
			echo '</select></span>';
		echo '</div>';
		echo '<div class="assign_field_group_response"></div>';
		die();
	}
	public function group_configure($id) {
		global $directorypress_object;
		$items = $directorypress_object->fields;
		$item = $items->get_fields_group_by_id($id);
		
		$on_listing_page = ($item->on_listing_page)? __('yes', 'directorypress-payment-manager') : __('No', 'directorypress-payment-manager');
		$on_tab = ($item->on_tab)? __('yes', 'directorypress-payment-manager') : __('No', 'directorypress-payment-manager');
		
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Id', 'directorypress-payment-manager').'</label><span>'. esc_html($item->id) .'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Name', 'directorypress-payment-manager').'</label><span>'. esc_html($item->name) .'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Show in Tabs?', 'directorypress-payment-manager').'</label><span>'. esc_html($on_tab) .'</span>';
		echo '</div>';
		echo '<div class="directorypress-data-list">';
			echo '<label>'.__('Group Style', 'directorypress-payment-manager').'</label><span>'. __('Style', 'directorypress-payment-manager'). ' ' . esc_html($item->group_style) .'</span>';
		echo '</div>';
		
		die();
	}
	
	public function table($items) {
		global $directorypress_object;
		$items_array = array();
		$items = $directorypress_object->fields;
		$output = '';
		$output .= '<div class="dp-list-section">';
			foreach ($items->fields_array as $id=>$item) {
				$output .= '<div class="row dp-list-row">';
					$output .= '<input type="hidden" class="field_weight_id" value="'. esc_attr($item->id) .'" />';
					$output .= '<div class="col-md-10 txt-left dp-list-item"><i class="fas fa-arrows-alt"></i><span class="badge badge-default">' . esc_attr($item->id) .'</span> ' . esc_html($item->name) . ' <span class="label label-default">' . esc_html($item->type) .'</span></div>';
					$output .= '<div class="col-md-2 text-right dp-list-item"><a class="fields_configure" href="#" data-toggle="modal" data-target="#fields_configure" data-id="'. esc_attr($item->id) .'" data-type="'. esc_attr($item->type) .'"><i class="fas fa-cog"></i>'. esc_html__('Configure', 'directorypress-payment-manager').'</a></div>';
				$output .= '</div>';
			}
		$output .= '</div>';
			
		return $output;
	}
	public function group_table($items) {
		global $directorypress_object;
		$items_array = array();
		$items = $directorypress_object->fields;
		$output = '';
		$output .= '<div class="dp-list-section">';
			foreach ($items->fields_groups_array as $id=>$item) {
				$output .= '<div class="row dp-list-row">';
					$output .= '<input type="hidden" class="field_group_weight_id" value="'. esc_attr($item->id) .'" />';
					$output .= '<div class="col-md-10 txt-left dp-list-item"><i class="fas fa-arrows-alt"></i>'. esc_html($item->id) .' - ' . esc_html($item->name) . '</div>';
					$output .= '<div class="col-md-2 text-right dp-list-item"><a class="groups_configure" href="#" data-toggle="modal" data-target="#groups_configure" data-id="'. esc_attr($item->id) .'"><i class="fas fa-cog"></i>'. esc_html__('Configure', 'directorypress-payment-manager').'</a></div>';
				$output .= '</div>';
			}
		$output .= '</div>';
			
		return $output;
	}
	public function add_or_edit_fields($id = null, $action = '') {
		global $directorypress_object;
		
		$fields = $directorypress_object->fields;
	
		if (!$field = $fields->get_field_by_id($id)) {
			if (isset($_POST['type']) && $_POST['type']) {
				$field_class_name = 'directorypress_field_' . sanitize_text_field($_POST['type']);
				if (class_exists($field_class_name)) {
					$field = new $field_class_name;
				}
			} else {
				$field = new directorypress_field();
			}
		}
		
		

		if ($action == 'submit') {
			if (current_user_can( 'manage_options' ) ) {
				
			
				$validation = $field->validation();
			
				if ($validation->run()) {
					if ($field->id) {
						if ($fields->save_field_from_array($id, $validation->result_array())) {
							directorypress_add_notification(__('field updated successfully!', 'DIRECTORYPRESS'));
							//include('_html/add_edit_field.php');
						}
					} else {
						if ($fields->create_field_from_array($validation->result_array())) {
							directorypress_add_notification(__('field created successfully!', 'DIRECTORYPRESS'));
						}
						$this->validation_status = 'success';
					}
				} else {
					$field->build_fields_from_array($validation->result_array());
					directorypress_add_notification($validation->error_array(), 'error');
					include('_html/add_edit_field.php');
					$this->validation_status = 'error';
				}
			}else{
				directorypress_add_notification(__('no permission!', 'DIRECTORYPRESS'), 'error');
			}
		} else {
			include('_html/add_edit_field.php');
		}
	}

	public function add_or_edit_field_groups($id = null, $action = '') {
		global $directorypress_object;
	
		$fields = $directorypress_object->fields;
	
		if (!$fields_group = $fields->get_fields_group_by_id($id)) {
			// this will be new fields group
			$fields_group = new directorypress_fields_group();
		}

		if ($action == 'submit') {
			if (current_user_can( 'manage_options' ) ) {
				$validation = $fields_group->validation();

				if ($validation->run()) {
					if ($fields_group->id) {
						if ($fields->save_fields_group_from_array($id, $validation->result_array())) {
							directorypress_add_notification(__('updated successfully!', 'DIRECTORYPRESS'));
						}
					} else {
						if ($fields->create_fields_group_from_array($validation->result_array())) {
							directorypress_add_notification(__('created successfully!', 'DIRECTORYPRESS'));
						}
					}
				} else {
					directorypress_add_notification($validation->error_array(), 'error');
					include('_html/add_edit_group.php');
				}
			}else{
				directorypress_add_notification(__('no permission!', 'DIRECTORYPRESS'), 'error');
			}	
		} else {
			include('_html/add_edit_group.php');
		}
	}

	public function field_settings($id, $action) {
		global $directorypress_object;
		
			if (($field = $directorypress_object->fields->get_field_by_id($id))){
				
					$field->configure($id, $action);
			}else {
				directorypress_add_notification(esc_attr__("This content field can't be configured", 'DIRECTORYPRESS'), 'error');
				
			}
	}

	public function delete_field($id, $action = '') {
		global $directorypress_object;
	
		$fields = $directorypress_object->fields;
		
			if (($field = $fields->get_field_by_id($id)) && !$field->is_core_field) {
				
				if ($action == 'delete') {
					if (current_user_can( 'manage_options' ) ) {
						if ($fields->delete_field($id)){
							directorypress_add_notification(__('Content field was deleted successfully!', 'DIRECTORYPRESS'));
						}
					}else{
						directorypress_add_notification(__('no permission!', 'DIRECTORYPRESS'), 'error');
					}
				} else{
					echo '<div class="directorypress-delete">';
						echo '<p class="alert alert-warning">'. sprintf(esc_html__('Are you sure you want delete "%s" Field?', 'DIRECTORYPRESS'), $field->name).'</p>';
					echo '</div>';
				}
			}
		
	}

	public function delete_field_group($id, $action = '') {
		global $directorypress_object;
	
		$fields = $directorypress_object->fields;
		if ($fields_group = $fields->get_fields_group_by_id($id)) {
			if ($action == 'delete') {
				if (current_user_can( 'manage_options' ) ) {
					if ($fields->delete_fields_group($id)){
						directorypress_add_notification(__('Fields group deleted successfully!', 'DIRECTORYPRESS'));
					}
				}else{
					directorypress_add_notification(__('no permission!', 'DIRECTORYPRESS'), 'error');
				}	
			} else{
				echo '<div class="directorypress-delete">';
					echo '<p class="alert alert-warning">'. sprintf(esc_html__('Are you sure you want delete "%s" Group?', 'DIRECTORYPRESS'), $fields_group->name).'</p>';
				echo '</div>';
			}
		}
	}
	
	public function add_fields_form_encryption($post) {
		if ($post->post_type == DIRECTORYPRESS_POST_TYPE) {
			echo ' enctype="multipart/form-data" ';
		}
	}
	
	public function add_fields_metabox($post_type) {
		if ($post_type == DIRECTORYPRESS_POST_TYPE) {
			global $directorypress_object;
			
			if ($directorypress_object->fields->is_this_not_core_field()){
				add_meta_box(
					'directorypress_fields',
					__('Content fields', 'DIRECTORYPRESS'),
					array($this, 'directorypress_fields_metabox'),
					DIRECTORYPRESS_POST_TYPE,
					'normal',
					'high'
				);
			}
		}
	}
	
	public function directorypress_fields_metabox($post) {
		global $directorypress_object;

		if ($listing = directorypress_pull_current_listing_admin()) {
			$fields = $listing->fields + $directorypress_object->fields->fields_array;

			$order_keys = array_keys($directorypress_object->fields->fields_array);
			$ordered_fields = array();
			foreach($order_keys as $key) {
				if(array_key_exists($key, $fields)) {
					$ordered_fields[$key] = $fields[$key];
					unset($fields[$key]);
				}
			}
			$fields = array();
			foreach ($ordered_fields AS &$field)
				if ($field->is_core_field || !$listing->package->fields || in_array($field->id, $listing->package->fields))
					$fields[] = $field;
		} else
			$fields = $directorypress_object->fields->fields_array;
		
		$fields = apply_filters('directorypress_fields_metabox', $fields, $post);
		
		include('_html/metabox.php');
	}
	public function directorypress_fields_metabox_by_slug_type($type, $slug, $post) {
		global $directorypress_object;

		if ($listing = directorypress_pull_current_listing_admin()) {
			$fields = $listing->fields + $directorypress_object->fields->fields_array;

			$order_keys = array_keys($directorypress_object->fields->fields_array);
			$ordered_fields = array();
			foreach($order_keys as $key) {
				if(array_key_exists($key, $fields)) {
					$ordered_fields[$key] = $fields[$key];
					unset($fields[$key]);
				}
			}
			$fields = array();
			foreach ($ordered_fields AS &$field){
				if ($field->is_core_field || !$listing->package->fields || in_array($field->id, $listing->package->fields))
					$fields[] = $field;
			}
		} else{
			$fields = $directorypress_object->fields->fields_array;
		}
		
		$fields = apply_filters('directorypress_fields_metabox', $fields, $post);
		
		foreach ($fields AS $field) {
			if ($field->type == $type || $field->slug == $slug){
				$field->renderInput();
			}
		}
	}
	public function renew_assigned_categories($tt_id) {
		if ($term = get_term_by('term_taxonomy_id', $tt_id, DIRECTORYPRESS_CATEGORIES_TAX)) {
			global $wpdb;
			$fields = $wpdb->get_results("SELECT * FROM {$wpdb->directorypress_fields}", ARRAY_A);
			foreach ($fields AS $field) {
				if ($field['categories']) {
					$unserialized_categories = unserialize($field['categories']);
					if (count($unserialized_categories) > 1 || $unserialized_categories != array(''))
						if (($key = array_search($term->term_id, $unserialized_categories)) !== FALSE) {
							unset($unserialized_categories[$key]);
							$wpdb->update($wpdb->directorypress_fields, array('categories' => serialize($unserialized_categories)), array('id' => $field['id']));
						}
				}
			}
		}
	}
}

if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class directorypress_manage_fields_table extends WP_List_Table {

	public function __construct() {
		parent::__construct(array(
				'singular' => __('content field', 'DIRECTORYPRESS'),
				'plural' => __('content fields', 'DIRECTORYPRESS'),
				'ajax' => false
		));
	}

	public function get_columns() {
		$columns = array(
				'field_name' => __('Name', 'DIRECTORYPRESS'),
				'field_type' => __('Field type', 'DIRECTORYPRESS'),
				'required' => __('Required', 'DIRECTORYPRESS'),
				'group_id' => __('Group', 'DIRECTORYPRESS'),
				'id' => __('ID', 'DIRECTORYPRESS'),
		);
		$columns = apply_filters('directorypress_field_table_header', $columns);

		return $columns;
	}

	public function getItems($fields_object) {
		$items_array = array();
		foreach ($fields_object->fields_array as $id=>$field) {
			$items_array[$id] = array(
					'id' => $field->id,
					'is_core_field' => $field->is_core_field,
					'field_name' => $field->name,
					'field_type' => $field->type,
					'required' => $field->is_required,
					'can_be_required' => $field->is_this_field_requirable(),
					'is_configuration_page' => $field->has_setting_support(),
					'is_search_configuration_page' => $field->has_search_support(),
					'group_id' => $field->group_id,
			);
			$items_array[$id] = apply_filters('directorypress_field_table_row', $items_array[$id], $field);
		}
		return $items_array;
	}

	public function prepareItems($fields_object) {
		$this->_column_headers = array($this->get_columns(), array(), array());

		$this->items = $this->getItems($fields_object);
	}

	public function column_field_name($item) {
		$actions['edit'] = sprintf('<a href="?page=%s&action=%s&field_id=%d">' . __('Edit', 'DIRECTORYPRESS') . '</a>', $_GET['page'], 'edit', $item['id']);
		if ($item['is_configuration_page'])
			$actions['configure'] = sprintf('<a href="?page=%s&action=%s&field_id=%d">' . __('Configure', 'DIRECTORYPRESS') . '</a>', $_GET['page'], 'configure', $item['id']);
		if ($item['is_search_configuration_page'])
			$actions['search_configure'] = sprintf('<a href="?page=%s&action=%s&field_id=%d">' . __('Configure search', 'DIRECTORYPRESS') . '</a>', $_GET['page'], 'configure_search', $item['id']);

		$actions = apply_filters('directorypress_fields_column_options', $actions, $item);

		if (!$item['is_core_field'])
			$actions['delete'] = sprintf('<a href="?page=%s&action=%s&field_id=%d">' . __('Delete', 'DIRECTORYPRESS') . '</a>', esc_attr($_GET['page']), 'delete', esc_attr($item['id']));
		return sprintf('%1$s %2$s', sprintf('<a href="?page=%s&action=%s&field_id=%d">' . esc_html($item['field_name']) . '</a><input type="hidden" class="field_weight_id" value="%d" />', esc_attr($_GET['page']), 'edit', esc_attr($item['id']), esc_attr($item['id'])), $this->row_actions($actions));
	}

	public function column_field_type($item) {
		global $directorypress_object;

		return $directorypress_object->fields->fields_types_names[$item['field_type']];
	}

	public function column_required($item) {
		if ($item['can_be_required'])
			if ($item['required'])
				return '<span class="field_check directorypress-icon-check"></span>';
			else
				return '<span class="field_remove directorypress-icon-close"></span>';
		else
			return ' ';
	}

	public function column_icon_image($item) {
		if ($item['icon_image'])
			return '<span class="directorypress-icon-tag directorypress-fa ' . esc_attr($item['icon_image']) . '"></span>';
		else
			return ' ';
	}

	public function column_in_pages($item) {
		$html = array();
		if ($item['on_exerpt_page'])
			$html[] = __('On Grid', 'DIRECTORYPRESS');
		if ($item['on_exerpt_page_list'])
			$html[] = __('On List', 'DIRECTORYPRESS');
		if ($item['on_listing_page'])
			$html[] = __('On listing', 'DIRECTORYPRESS');
		if ($item['on_map'])
			$html[] = __('In map marker InfoWindow', 'DIRECTORYPRESS');
		if ($item['on_search_form'])
			$html_array[] = __('On search form', 'DIRECTORYPRESS');
		
		$html = apply_filters('directorypress_fields_in_pages_options', $html, $item);
		
		if ($html)
			return implode('<br />', $html);
		else
			return ' ';
	}
	
	public function column_group_id($item) {
		global $directorypress_object;

		echo '<select name="group_id_' . esc_attr($item['id']) . '">';
		echo '<option value=0>' . __('- Without group -', 'DIRECTORYPRESS') . '</option>';
		foreach ($directorypress_object->fields->fields_groups_array AS $group)
			echo '<option value=' . esc_attr($group->id) . ' ' . selected(esc_attr($item['group_id']), esc_attr($group->id)) . '>' . esc_html($group->name) . '</option>';
		echo '</select>';
	}

	public function column_default($item, $column_name) {
		switch($column_name) {
			default:
				return $item[$column_name];
		}
	}

	public function no_items() {
		__('No content fields found.', 'DIRECTORYPRESS');
	}
}

class directorypress_manage_fields_groups_table extends WP_List_Table {

	public function __construct() {
		parent::__construct(array(
				'singular' => __('content fields group', 'DIRECTORYPRESS'),
				'plural' => __('content fields groups', 'DIRECTORYPRESS'),
				'ajax' => false
		));
	}

	public function get_columns() {
		$columns = array(
				'group_name' => __('Name', 'DIRECTORYPRESS'),
		);
		$columns = apply_filters('directorypress_field_table_header', $columns);

		return $columns;
	}
	
	public function getItems($fields_object) {
		$items_array = array();
		foreach ($fields_object->fields_groups_array as $id=>$fields_group) {
			$items_array[$id] = array(
					'id' => $fields_group->id,
					'group_name' => $fields_group->name,
			);
		}
		return $items_array;
	}

	public function prepareItems($fields_object) {
		$this->_column_headers = array($this->get_columns(), array(), array());

		$this->items = $this->getItems($fields_object);
	}

	public function column_group_name($item) {
		$actions['edit'] = sprintf('<a href="?page=%s&action=%s&group_id=%d">' . __('Edit', 'DIRECTORYPRESS') . '</a>', $_GET['page'], 'edit_group', $item['id']);
		$actions['delete'] = sprintf('<a href="?page=%s&action=%s&group_id=%d">' . __('Delete', 'DIRECTORYPRESS') . '</a>', $_GET['page'], 'delete_group', $item['id']);
		return sprintf('%1$s %2$s', sprintf('<a href="?page=%s&action=%s&group_id=%d">' . $item['group_name'] . '</a>', $_GET['page'], 'edit_group', $item['id']), $this->row_actions($actions));
	}

	public function column_default($item, $column_name) {
		switch($column_name) {
			default:
				return $item[$column_name];
		}
	}

	public function no_items() {
		__('No content fields groups found.', 'DIRECTORYPRESS');
	}
}
?>