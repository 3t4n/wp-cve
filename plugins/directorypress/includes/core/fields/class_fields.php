<?php
include_once DIRECTORYPRESS_PATH . 'includes/core/fields/address/address.php';
include_once DIRECTORYPRESS_PATH . 'includes/core/fields/category/categories.php';
include_once DIRECTORYPRESS_PATH . 'includes/core/fields/content/content.php';
include_once DIRECTORYPRESS_PATH . 'includes/core/fields/email/email.php';
include_once DIRECTORYPRESS_PATH . 'includes/core/fields/link/link.php';
include_once DIRECTORYPRESS_PATH . 'includes/core/fields/price/price.php';
include_once DIRECTORYPRESS_PATH . 'includes/core/fields/select/select.php';
include_once DIRECTORYPRESS_PATH . 'includes/core/fields/summary/summary.php';
include_once DIRECTORYPRESS_PATH . 'includes/core/fields/tags/tags.php';
include_once DIRECTORYPRESS_PATH . 'includes/core/fields/text/text.php';
include_once DIRECTORYPRESS_PATH . 'includes/core/fields/textarea/textarea.php';

do_action( 'directorypress_before_fields_loaded' );


class directorypress_fields {
	public $fields_array = array();
	public $fields_groups_array = array();
	public $fields_types_names;
	private $map_fields = array();
	
	public function __construct() {
		$fields_types_names = array(
				'summary' => __('Summary', 'DIRECTORYPRESS'),
				'content' => __('Content', 'DIRECTORYPRESS'),
				'categories' => __('Categories', 'DIRECTORYPRESS'),
				'tags' => __('Tags', 'DIRECTORYPRESS'),
				'address' => __('Address', 'DIRECTORYPRESS'),
				'text' => __('Text', 'DIRECTORYPRESS'),
				'textarea' => __('Textarea', 'DIRECTORYPRESS'),
				'select' => __('Select', 'DIRECTORYPRESS'),
				'link' => __('Link', 'DIRECTORYPRESS'),
				'email' => __('Email', 'DIRECTORYPRESS'),
				'price' => __('Price', 'DIRECTORYPRESS'),
		);
		
		$this->fields_types_names = apply_filters('directorypress_fields_types_name', $fields_types_names);
		$this->get_fields_from_database();
	}
	
	public function saveOrder($order) {
		global $wpdb;

		if (isset($order) && $order && ($order_ids = explode(',', trim($order)))) {
			$i = 1;
			foreach ($order_ids AS $id) {
				$wpdb->update($wpdb->directorypress_fields, array('order_num' => $i), array('id' => $id));
				$i++;
			}
			$this->get_fields_from_database();

			return true;
		}
	}

	public function save_field_group_relations($id, $group_id) {
		global $wpdb;

		foreach ($this->fields_array AS $field) {
			//if ($id){
				//$group_id = $id;
				$wpdb->update($wpdb->directorypress_fields, array('group_id' => $group_id), array('id' => $id));
			//}
		}
		$this->get_fields_from_database();

		return true;
	}
	
	public function get_fields_from_database() {
		global $wpdb;

		$this->fields_array = array();
		$array = $wpdb->get_results("SELECT * FROM {$wpdb->directorypress_fields} ORDER BY order_num, is_core_field", ARRAY_A);
		foreach ($array AS $row) {
			$field_class_name = 'directorypress_field_' . $row['type'];
			if (class_exists($field_class_name)) {
				$field = new $field_class_name;
				$field->build_fields_from_array($row);
				$field->directorypress_process_categories();
				$field->convert_field_options();
				$this->fields_array[$row['id']] = $field;
			}
		}

		$this->fields_groups_array = array();
		$array = $wpdb->get_results("SELECT * FROM {$wpdb->directorypress_fields_groups}", ARRAY_A);
		foreach ($array AS $row) {
			$fields_group = new directorypress_fields_group($row);
			$this->fields_groups_array[$row['id']] = $fields_group;
		}
		
		return true;
	}
	
	public function get_field_by_id($field_id) {
		if (isset($this->fields_array[$field_id]))
			return $this->fields_array[$field_id];
	}

	public function get_fields_group_by_id($group_id) {
		if (isset($this->fields_groups_array[$group_id]))
			return $this->fields_groups_array[$group_id];
	}

	public function get_field_by_slug($slug) {
		foreach ($this->fields_array AS $field) {
			if ($field->slug == $slug)
				return $field;
		}
	}
	
	public function create_field_from_array($array) {
		if (directorypress_get_input_value($array, 'type')) {
			$field_class_name = 'directorypress_field_' . directorypress_get_input_value($array, 'type');
			if (class_exists($field_class_name)) {
				$field = new $field_class_name;
				if ($field->create($array))
					return $this->get_fields_from_database();
			}
		}
		return false;
	}
	
	public function save_field_from_array($field_id, $array) {
		if ($field = $this->get_field_by_id($field_id))
			if ($field->save($array))
				return $this->get_fields_from_database();

		return false;
	}
	
	public function delete_field($field_id) {
		if ($field = $this->get_field_by_id($field_id))
			if ($field->delete())
				return $this->get_fields_from_database();
		
		return false;
	}

	public function delete_fields_group($group_id) {
		if ($fields_group = $this->get_fields_group_by_id($group_id))
			if ($fields_group->delete())
				return $this->get_fields_from_database();
		
		return false;
	}

	public function create_fields_group_from_array($array) {
		$fields_group = new directorypress_fields_group;
		if ($fields_group->create($array))
			return $this->get_fields_from_database();
		
		return false;
	}
	
	public function save_fields_group_from_array($group_id, $array) {
		if ($fields_group = $this->get_fields_group_by_id($group_id))
			if ($fields_group->save($array))
				return $this->get_fields_from_database();

		return false;
	}

	public function get_fields_order() {
		$fields = array();
		foreach ($this->fields_array AS $field) {
			if ($field->is_this_field_orderable() && $field->is_ordered)
				$fields[] = $field;
		}
		return $fields;
	}

	public function is_this_not_core_field() {
		foreach ($this->fields_array AS $field) {
			if (!$field->is_core_field)
				return true;
		}
	}
	
	public function is_this_field_slug($slug) {
		foreach ($this->fields_array AS $field) {
			if ($field->slug == $slug)
				return true;
		}
	}
	
	public function get_fields_categories_and_package_ids($categories_ids, $package_id = null) {
		if ($package_id) {
			global $directorypress_object;
			$package = $directorypress_object->packages->get_package_by_id($package_id);
		} else 
			$package = null;

		$result_fields = array();
		foreach ($this->fields_array AS &$field) {
			if (
				(!$field->is_categories() || $field->categories === array() || !is_array($field->categories) || array_intersect($field->categories, $categories_ids)) &&
				($field->is_core_field || !$package || !$package->fields || in_array($field->id, $package->fields))
			)
				$result_fields[$field->id] = $field;
		}
		return $result_fields;
	}

	public function save_values($post_id, $categories_ids, &$errors, $data, $package_id = null) {
		$fields = $this->get_fields_categories_and_package_ids($categories_ids, $package_id);
		foreach ($fields AS $field) {
			$local_errors = array();
			if (($validation_results = $field->validate_field_values($local_errors, $data)) !== false && !$local_errors) {
				$field->save_field_value($post_id, $validation_results);
			} else {
				$errors = array_merge($errors, $local_errors);
			}
		}
	}

	public function load_field_values($post_id, $categories_ids, $package_id = null) {
		$fields = $this->get_fields_categories_and_package_ids($categories_ids, $package_id);
		$result_fields = array();
		foreach ($fields AS $field) {
			$rfield = clone $field;
			$rfield->load_field_value($post_id);
			$result_fields[$field->id] = $rfield;
		}
		return $result_fields;
	}
	
	public function get_order_params($defaults = array()) {
		$order_by = directorypress_get_input_value($_GET, 'order_by', directorypress_get_input_value($defaults, 'order_by'));

		if ($order_by)
			foreach ($this->fields_array AS $field) {
				if ($field->is_this_field_orderable() && $field->is_ordered && $field->slug == $order_by) {
					return $field->order_params();
					break;
				}
			}
		return array();
	}
	
	public function get_map_fields() {
		if (!$this->map_fields) {
			foreach ($this->fields_array AS $field) {
				if ($field->on_map) {
					$this->map_fields[$field->slug] = clone $field;
				}
			}
			
			// address field always will be the first
			if (isset($this->map_fields['address'])) {
				$address_field = $this->map_fields['address'];
				unset($this->map_fields['address']);
				$this->map_fields = array('address' => $address_field) + $this->map_fields;
			}
			
			$this->map_fields = apply_filters('directorypress_map_info_window_fields', $this->map_fields);
		}
		
		return $this->map_fields;
	}

	public function order_content_fields_by_groups($fields_array = null) {
		if (!$fields_array)
			$fields_array = $this->fields_array;

		$result = array();
		foreach ($fields_array AS $field)
			if ($field->group_id && isset($this->fields_groups_array[$field->group_id])) {
				$fields_group = $this->fields_groups_array[$field->group_id];
				$group_in_array = false;
				foreach ($result AS $item)
					if (is_a($item, 'directorypress_fields_group') && $item->id == $field->group_id)
						$group_in_array = true;
				if (!$group_in_array) {
					$fields_group->set_directorypress_fields($fields_array);
					$result[] = $fields_group;
				}
			} else 
				$result[] = $field;
		return $result;
	}
}

class directorypress_fields_group {
	public $id;
	public $name;
	public $on_tab;
	public $group_style;
	public $hide_anonymous;
	public $fields_array = array();

	public function __construct($row = null) {
		if ($row) {
			$this->id = $row['id'];
			$this->name = $row['name'];
			$this->on_tab = $row['on_tab'];
			$this->group_style = (isset($row['group_style']))? $row['group_style'] :'';
			$this->hide_anonymous = $row['hide_anonymous'];
		}
	}
	
	public function validation() {
		$validation = new directorypress_form_validation();
		$validation->set_rules('name', __('Group name', 'DIRECTORYPRESS'), 'required');
		$validation->set_rules('on_tab', __('Show on Tab', 'DIRECTORYPRESS'), 'is_checked');
		$validation->set_rules('group_style', __('Group style', 'DIRECTORYPRESS'), 'required');
		$validation->set_rules('hide_anonymous', __('Hide from anonymous', 'DIRECTORYPRESS'), 'is_checked');
		return $validation;
	}
	
	public function create($array) {
		global $wpdb;
	
		$insert_update_args = array(
				'name' => directorypress_get_input_value($array, 'name'),
				'on_tab' => directorypress_get_input_value($array, 'on_tab'),
				'group_style' => $array['group_style'],
				'hide_anonymous' => directorypress_get_input_value($array, 'hide_anonymous'),
		);
		
		$insert_update_args = apply_filters('directorypress_field_group_create_edit_args', $insert_update_args, $this, $array);

		if ($wpdb->insert($wpdb->directorypress_fields_groups, $insert_update_args)) {
			$new_field_group_id = $wpdb->insert_id;
				
			do_action('directorypress_update_field_group', $new_field_group_id, $this, $insert_update_args);
			
			return true;
		}
	}
	
	public function save($array) {
		global $wpdb, $directorypress_object;

		$insert_update_args = array(
				'name' => directorypress_get_input_value($array, 'name'),
				'on_tab' => directorypress_get_input_value($array, 'on_tab'),
				'group_style' => $array['group_style'],
				'hide_anonymous' => directorypress_get_input_value($array, 'hide_anonymous'),
		);
		
		$insert_update_args = apply_filters('directorypress_field_group_create_edit_args', $insert_update_args, $this, $array);

		if ($wpdb->update($wpdb->directorypress_fields_groups, $insert_update_args, array('id' => $this->id), null, array('%d')) !== false) {
			do_action('directorypress_update_field_group', $this->id, $this, $insert_update_args);
				
			return true;
		}
	}
	
	public function delete() {
		global $wpdb;

		$wpdb->delete($wpdb->directorypress_fields_groups, array('id' => $this->id));
		$wpdb->update($wpdb->directorypress_fields, array('group_id' => 0), array('group_id' => $this->id));
		return true;
	}
	
	public function set_directorypress_fields($fields_array) {
		foreach ($fields_array AS $field) {
			if ($this->id == $field->group_id)
				$this->fields_array[$field->id] = $field;
		}
	}
	
	public function display_output($listing, $is_single = true) {
		if ($this->fields_array) {
			$fields_group = $this;
			include('_html/group_output.php');
		}
	}
}

class directorypress_field {
	public $id;
	public $is_core_field = 0;
	public $order_num;
	public $name;
	public $field_search_label;
	public $slug;
	public $description;
	public $fieldwidth;
	public $fieldwidth_archive;
	public $type;
	public $icon_image;
	public $is_required = 0;
	public $is_ordered;
	public $is_hide_name;
	public $is_field_in_line;
	public $is_hide_name_on_grid = 'hide';
	public $is_hide_name_on_list = 'hide';
	public $is_hide_name_on_search;
	public $on_exerpt_page = 1;
	public $on_exerpt_page_list = 1;
	public $on_listing_page = 1;
	public $on_map;
	public $categories = array();
	public $options;
	public $search_options;
	public $group_id;
	public $value;
	
	protected $can_be_required = true;
	protected $can_be_ordered = true;
	protected $is_categories = true;
	protected $is_slug = true;
	
	protected $is_configuration_page = false;

	protected $can_be_searched = false;
	protected $is_search_configuration_page = false;
	public $on_search_form = false;
	public $on_search_form_archive = false;
	public $on_search_form_widget = false;
	public $advanced_search_form = false;


	public function validation() {
		global $directorypress_object;

		if (!$this->is_core_field) {
			if (isset($_POST['type']) && $_POST['type']) {
				$field_class_name = 'directorypress_field_' . sanitize_text_field($_POST['type']);
				if (class_exists($field_class_name)) {
					$process_field = new $field_class_name;
				} else {
					directorypress_add_notification('This type of content field does not exist!', 'error');
					$process_field = $this;
				}
			} else {
				$process_field = $this;
			}
		} else
			$process_field = $this;
		
		$validation = new directorypress_form_validation();
		$validation->set_rules('name', __('Content field name', 'DIRECTORYPRESS'), 'required');
		$validation->set_rules('field_search_label', __('Content field search label', 'DIRECTORYPRESS'));
		if ($process_field->is_slug()){
			$validation->set_rules('slug', __('Content field slug', 'DIRECTORYPRESS'), 'required|alpha_dash');
		}
		$validation->set_rules('description', __('Content field description', 'DIRECTORYPRESS'));
		$validation->set_rules('fieldwidth', __('Content field fieldwidth on custom search form', 'DIRECTORYPRESS'));
		$validation->set_rules('fieldwidth_archive', __('Content field fieldwidth on Archive search form', 'DIRECTORYPRESS'));
		$validation->set_rules('icon_image', __('Icon image', 'DIRECTORYPRESS'));
		if ($process_field->is_this_field_requirable()){
			$validation->set_rules('is_required', __('Content field required', 'DIRECTORYPRESS'), 'is_checked');
		}
		if ($process_field->is_this_field_orderable()){
			$validation->set_rules('is_ordered', __('Order by field', 'DIRECTORYPRESS'), 'is_checked');
		}
		$validation->set_rules('is_hide_name', __('Hide name on single listing', 'DIRECTORYPRESS'), 'is_checked');
		$validation->set_rules('is_hide_name_on_grid', __('Hide name grid style', 'DIRECTORYPRESS'), 'required');
		$validation->set_rules('is_hide_name_on_list', __('Hide name on list style', 'DIRECTORYPRESS'), 'required');
		$validation->set_rules('is_hide_name_on_search', __('Hide name in search forms', 'DIRECTORYPRESS'), 'is_checked');
		$validation->set_rules('is_field_in_line', __('Display field in line', 'DIRECTORYPRESS'), 'is_checked');
		$validation->set_rules('on_exerpt_page', __('On grid view', 'DIRECTORYPRESS'), 'is_checked');
		$validation->set_rules('on_exerpt_page_list', __('On list view', 'DIRECTORYPRESS'), 'is_checked');
		$validation->set_rules('on_listing_page', __('On listing page', 'DIRECTORYPRESS'), 'is_checked');
		$validation->set_rules('on_map', __('In map marker InfoWindow', 'DIRECTORYPRESS'), 'is_checked');
		// core fields can't change type
		if (!$this->is_core_field){
			$validation->set_rules('type', __('Content field type', 'DIRECTORYPRESS'), 'required');
		}
		if ($process_field->is_categories()){
			$validation->set_rules('categories', __('Assigned categories', 'DIRECTORYPRESS'));
		}
		if ($process_field->is_this_field_searchable()) {
			$validation->set_rules('on_search_form', __('On search form', 'DIRECTORYPRESS'), 'is_checked');
			$validation->set_rules('advanced_search_form', __('On advanced search panel', 'DIRECTORYPRESS'), 'is_checked');
		}

		$validation = apply_filters('directorypress_field_validation', $validation, $process_field);

		if ($process_field->is_slug()) {
			global $wpdb;

			if ($wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->directorypress_fields} WHERE slug=%s AND id!=%d", esc_attr($_POST['slug']), $this->id), ARRAY_A)
				|| $_POST['slug'] == 'post_title'
				|| $_POST['slug'] == 'post_name'
				|| $_POST['slug'] == 'post_date'
				|| $_POST['slug'] == 'title'
				|| $_POST['slug'] == 'categories_list'
				|| $_POST['slug'] == 'address'
				|| $_POST['slug'] == 'address_line_1'
				|| $_POST['slug'] == 'address_line_2'
				|| $_POST['slug'] == 'map_coords_1'
				|| $_POST['slug'] == 'map_coords_2'
				|| $_POST['slug'] == 'map_icon_file'
				|| $_POST['slug'] == 'content'
				|| $_POST['slug'] == 'excerpt'
				|| $_POST['slug'] == 'listing_tags'
				|| $_POST['slug'] == 'distance'
				|| $_POST['slug'] == 'user'
				|| $_POST['slug'] == 'zip_or_postal_index'
			)
				$validation->setError('slug', esc_attr__("Can't use this slug", 'DIRECTORYPRESS'));
		}

		return $validation;
	}
	
	public function create($array) {
		global $wpdb;

		$insert_update_args = array(
				'name' => directorypress_get_input_value($array, 'name'),
				'field_search_label' => directorypress_get_input_value($array, 'field_search_label'),
				'description' => directorypress_get_input_value($array, 'description'),
				'fieldwidth' => directorypress_get_input_value($array, 'fieldwidth'),
				'fieldwidth_archive' => directorypress_get_input_value($array, 'fieldwidth_archive'),
				'type' => directorypress_get_input_value($array, 'type'),
				'icon_image' => directorypress_get_input_value($array, 'icon_image'),
				'is_configuration_page' => $this->is_configuration_page,
				'is_search_configuration_page' => $this->is_search_configuration_page,
				'is_hide_name' => directorypress_get_input_value($array, 'is_hide_name'),
				'is_hide_name_on_grid' => directorypress_get_input_value($array, 'is_hide_name_on_grid'),
				'is_hide_name_on_list' => directorypress_get_input_value($array, 'is_hide_name_on_list'),
				'is_hide_name_on_search' => directorypress_get_input_value($array, 'is_hide_name_on_search'),
				'is_field_in_line' => directorypress_get_input_value($array, 'is_field_in_line'),
				'on_exerpt_page' => directorypress_get_input_value($array, 'on_exerpt_page'),
				'on_exerpt_page_list' => directorypress_get_input_value($array, 'on_exerpt_page_list'),
				'on_listing_page' => directorypress_get_input_value($array, 'on_listing_page'),
				'on_map' => directorypress_get_input_value($array, 'on_map'),
		);
		if ($this->is_slug())
			$insert_update_args['slug'] = directorypress_get_input_value($array, 'slug');
		if ($this->is_this_field_requirable())
			$insert_update_args['is_required'] = directorypress_get_input_value($array, 'is_required');
		if ($this->is_this_field_orderable())
			$insert_update_args['is_ordered'] = directorypress_get_input_value($array, 'is_ordered');
		if ($this->is_categories())
			$insert_update_args['categories'] = serialize(directorypress_get_input_value($array, 'categories', array()));
		if ($this->is_this_field_searchable()) {
			$insert_update_args['on_search_form'] = directorypress_get_input_value($array, 'on_search_form');
			$insert_update_args['advanced_search_form'] = directorypress_get_input_value($array, 'advanced_search_form');
		} else {
			$insert_update_args['on_search_form'] = 0;
			$insert_update_args['advanced_search_form'] = 0;
			$insert_update_args['search_options'] = '';
		}

		$insert_update_args = apply_filters('directorypress_field_create_edit_args', $insert_update_args, $this, $array);
		
		if ($wpdb->insert($wpdb->directorypress_fields, $insert_update_args)) {
			$new_field_id = $wpdb->insert_id;
				
			do_action('directorypress_update_field', $new_field_id, $this, $array);
			
			return true;
		}
	}
	
	public function save($array) {
		global $wpdb, $directorypress_object;
		
		if (!$this->is_core_field) {
			if (isset($_POST['type']) && $_POST['type']) {
				$field_class_name = 'directorypress_field_' . sanitize_text_field($_POST['type']);
				if (class_exists($field_class_name)) {
					$process_field = new $field_class_name;
				} else {
					directorypress_add_notification('This type of content field does not exist!', 'error');
					$process_field = $this;
				}
			} else {
				$process_field = $this;
			}
		} else
			$process_field = $this;
		
		$insert_update_args = array(
				'name' => directorypress_get_input_value($array, 'name'),
				'field_search_label' => directorypress_get_input_value($array, 'field_search_label'),
				'description' => directorypress_get_input_value($array, 'description'),
				'fieldwidth' => directorypress_get_input_value($array, 'fieldwidth'),
				'fieldwidth_archive' => directorypress_get_input_value($array, 'fieldwidth_archive'),
				'icon_image' => directorypress_get_input_value($array, 'icon_image'),
				'is_hide_name' => directorypress_get_input_value($array, 'is_hide_name'),
				'is_hide_name_on_grid' => directorypress_get_input_value($array, 'is_hide_name_on_grid'),
				'is_hide_name_on_list' => directorypress_get_input_value($array, 'is_hide_name_on_list'),
				'is_hide_name_on_search' => directorypress_get_input_value($array, 'is_hide_name_on_search'),
				'is_field_in_line' => directorypress_get_input_value($array, 'is_field_in_line'),
				'on_exerpt_page' => directorypress_get_input_value($array, 'on_exerpt_page'),
				'on_exerpt_page_list' => directorypress_get_input_value($array, 'on_exerpt_page_list'),
				'on_listing_page' => directorypress_get_input_value($array, 'on_listing_page'),
				'on_map' => directorypress_get_input_value($array, 'on_map'),
		);
		// core fields can't change type
		if (!$this->is_core_field)
			$insert_update_args['type'] = directorypress_get_input_value($array, 'type');
		if ($process_field->is_slug())
			$insert_update_args['slug'] = directorypress_get_input_value($array, 'slug');

		if ($process_field->is_this_field_requirable())
			$insert_update_args['is_required'] = directorypress_get_input_value($array, 'is_required');
		else
			$insert_update_args['is_required'] = 0;

		if ($process_field->is_this_field_orderable())
			$insert_update_args['is_ordered'] = directorypress_get_input_value($array, 'is_ordered');
		else
			$insert_update_args['is_ordered'] = 0;

		if ($process_field->is_categories())
			$insert_update_args['categories'] = serialize(directorypress_get_input_value($array, 'categories', array()));
		else
			$insert_update_args['categories'] = '';
		
		if ($process_field->has_setting_support())
			$insert_update_args['is_configuration_page'] = 1;
		else
			$insert_update_args['is_configuration_page'] = 0;

		if ($process_field->has_search_support())
			$insert_update_args['is_search_configuration_page'] = 1;
		else
			$insert_update_args['is_search_configuration_page'] = 0;

		if ($this->is_this_field_searchable()) {
			$insert_update_args['on_search_form'] = directorypress_get_input_value($array, 'on_search_form');
			$insert_update_args['advanced_search_form'] = directorypress_get_input_value($array, 'advanced_search_form');
		} else {
			$insert_update_args['on_search_form'] = 0;
			$insert_update_args['advanced_search_form'] = 0;
			$insert_update_args['search_options'] = '';
		}

		$insert_update_args = apply_filters('directorypress_field_create_edit_args', $insert_update_args, $process_field, $array);

		if ($wpdb->update($wpdb->directorypress_fields, $insert_update_args, array('id' => $this->id), null, array('%d')) !== false) {
			do_action('directorypress_update_field', $this->id, $process_field, $array);
			return true;
		}
	}
	
	public function delete() {
		global $wpdb;

		$wpdb->delete($wpdb->postmeta, array('meta_key' => '_field_' . $this->id));

		$wpdb->delete($wpdb->directorypress_fields, array('id' => $this->id));
		return true;
	}

	public function build_fields_from_array($array) {
		$this->id = directorypress_get_input_value($array, 'id');
		$this->is_core_field = directorypress_get_input_value($array, 'is_core_field');
		$this->order_num = directorypress_get_input_value($array, 'order_num');
		$this->name = directorypress_get_input_value($array, 'name');
		$this->field_search_label = directorypress_get_input_value($array, 'field_search_label');
		$this->slug = directorypress_get_input_value($array, 'slug');
		$this->description = directorypress_get_input_value($array, 'description');
		$this->fieldwidth = directorypress_get_input_value($array, 'fieldwidth');
		$this->fieldwidth_archive = directorypress_get_input_value($array, 'fieldwidth_archive');
		$this->type = directorypress_get_input_value($array, 'type');
		$this->icon_image = directorypress_get_input_value($array, 'icon_image');
		$this->is_required = directorypress_get_input_value($array, 'is_required');
		$this->is_configuration_page = directorypress_get_input_value($array, 'is_configuration_page');
		$this->is_search_configuration_page = directorypress_get_input_value($array, 'is_search_configuration_page');
		$this->on_search_form = directorypress_get_input_value($array, 'on_search_form');
		$this->advanced_search_form = directorypress_get_input_value($array, 'advanced_search_form');
		$this->is_ordered = directorypress_get_input_value($array, 'is_ordered');
		$this->is_hide_name = directorypress_get_input_value($array, 'is_hide_name');
		$this->is_hide_name_on_grid = directorypress_get_input_value($array, 'is_hide_name_on_grid');
		$this->is_hide_name_on_list = directorypress_get_input_value($array, 'is_hide_name_on_list');
		$this->is_hide_name_on_search = directorypress_get_input_value($array, 'is_hide_name_on_search');
		$this->is_field_in_line = directorypress_get_input_value($array, 'is_field_in_line');
		$this->on_exerpt_page = directorypress_get_input_value($array, 'on_exerpt_page');
		$this->on_exerpt_page_list = directorypress_get_input_value($array, 'on_exerpt_page_list');
		$this->on_listing_page = directorypress_get_input_value($array, 'on_listing_page');
		$this->on_map = directorypress_get_input_value($array, 'on_map');
		$this->categories = directorypress_get_input_value($array, 'categories');
		$this->options = directorypress_get_input_value($array, 'options');
		$this->search_options = directorypress_get_input_value($array, 'search_options');
		$this->group_id = directorypress_get_input_value($array, 'group_id');
	}
	
	public function directorypress_process_categories() {
		if ($this->categories) {
			$unserialized_categories = maybe_unserialize($this->categories);
			if (is_countable($unserialized_categories) && (count($unserialized_categories) > 1 || $unserialized_categories != array(''))){
				$this->categories = $unserialized_categories;
			}else{
				$this->categories = array();
			}
		} else {
			$this->categories = array();
		}
		
		return $this->categories;
	}
	public function convert_field_options() {
		if ($this->options) {
			$unserialized_options = maybe_unserialize($this->options);
			if (count($unserialized_options) > 1 || $unserialized_options != array('')) {
				$this->options = $unserialized_options;
				if (method_exists($this, 'build_field_options'))
					$this->build_field_options();
				return $this->options;
			}
		}
		return array();
	}
	
	public function is_this_field_requirable() {
		return $this->can_be_required;
	}

	public function is_this_field_orderable() {
		return $this->can_be_ordered;
	}

	public function is_slug() {
		return $this->is_slug;
	}

	public function is_categories() {
		return $this->is_categories;
	}

	public function has_setting_support() {
		return $this->is_configuration_page;
	}

	public function has_search_support() {
		return $this->is_search_configuration_page;
	}

	public function is_this_field_searchable() {
		return $this->can_be_searched;
	}
	
	public function validate_field_values(&$errors, $data) {
		return true;
	}

	public function validate_csv_values($value, &$errors) {
		return true;
	}
	
	public function export_field_to_csv() {
		if ($this->value) {
			return $this->value;
			//return addslashes($this->value);
		}
	}

	public function save_field_value($post_id, $validation_results) {
		return true;
	}

	public function load_field_value($post_id) {
		return true;
	}
	
	public function display_output($listing) {
		return true;
	}

	public function disaply_output_on_map($location, $string) {
		return true;
	}

	public function isEmpty($listing) {
		if ($this->value)
			return false;
		else 
			return true;
	}
}

// adapted for WPML
add_action('init', 'directorypress_fields_names_into_strings');
function directorypress_fields_names_into_strings() {
	global $directorypress_object, $sitepress;

	if (function_exists('wpml_object_id_filter') && $sitepress) {
		foreach ($directorypress_object->fields->fields_array AS &$field) {
			$field->name = apply_filters('wpml_translate_single_string', $field->name, 'DirectoryPress', 'field name #' . $field->id);
			$field->field_search_label = apply_filters('wpml_translate_single_string', $field->field_search_label, 'DirectoryPress', 'field search label #' . $field->id);
			$field->description = apply_filters('wpml_translate_single_string', $field->description, 'DirectoryPress', 'field description #' . $field->id);
		}
		foreach ($directorypress_object->fields->fields_groups_array AS &$fields_group) {
			$fields_group->name = apply_filters('wpml_translate_single_string', $fields_group->name, 'DirectoryPress', 'group name #' . $fields_group->id);
		}
	}
}

add_filter('directorypress_field_create_edit_args', 'directorypress_filter_field_fields', 10, 3);
function directorypress_filter_field_fields($insert_update_args, $field, $array) {
	global $sitepress, $wpdb;

	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($sitepress->get_default_language() != ICL_LANGUAGE_CODE) {
			if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['field_id'])) {
				$field_id = sanitize_text_field($_GET['field_id']);
				if ($name_string_id = icl_st_is_registered_string('DirectoryPress', 'field name #' . $field_id))
					icl_add_string_translation($name_string_id, ICL_LANGUAGE_CODE, $insert_update_args['name'], ICL_TM_COMPLETE);
				if ($search_label_string_id = icl_st_is_registered_string('DirectoryPress', 'field search label #' . $field_id))
					icl_add_string_translation($name_string_id, ICL_LANGUAGE_CODE, $insert_update_args['field_search_label'], ICL_TM_COMPLETE);
				if ($description_string_id = icl_st_is_registered_string('DirectoryPress', 'field description #' . $field_id))
					icl_add_string_translation($description_string_id, ICL_LANGUAGE_CODE, $insert_update_args['description'], ICL_TM_COMPLETE);
				unset($insert_update_args['name']);
				unset($insert_update_args['field_search_label']);
				unset($insert_update_args['description']);
				unset($insert_update_args['slug']);

				unset($insert_update_args['categories']);
			} else {
				$insert_update_args['categories'] = '';
			}
		}
	}
	return $insert_update_args;
}

add_action('directorypress_update_field', 'directorypress_save_field', 10, 3);
function directorypress_save_field($field_id, $field, $array) {
	global $sitepress;

	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($sitepress->get_default_language() != ICL_LANGUAGE_CODE && $field->is_categories()) {
			update_option('directorypress_wpml_field_categories_'.$field_id.'_'.ICL_LANGUAGE_CODE, directorypress_get_input_value($array, 'categories'));
		}
		
		if ($sitepress->get_default_language() == ICL_LANGUAGE_CODE) {
			do_action('wpml_register_single_string', 'DirectoryPress', 'field name #' . $field_id, directorypress_get_input_value($array, 'name'));
			do_action('wpml_register_single_string', 'DirectoryPress', 'field search label #' . $field_id, directorypress_get_input_value($array, 'field_search_label'));
			do_action('wpml_register_single_string', 'DirectoryPress', 'field description #' . $field_id, directorypress_get_input_value($array, 'description'));
		}
	}
}

add_filter('directorypress_field_group_create_edit_args', 'directorypress_filter_field_group_fields', 10, 3);
function directorypress_filter_field_group_fields($insert_update_args, $field_group, $array) {
	global $sitepress, $wpdb;

	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($sitepress->get_default_language() != ICL_LANGUAGE_CODE) {
			if (isset($_GET['action']) && $_GET['action'] == 'edit_group' && isset($_GET['group_id'])) {
				$field_group_id = sanitize_text_field($_GET['group_id']);
				if ($name_string_id = icl_st_is_registered_string('DirectoryPress', 'group name #' . $field_group_id))
					icl_add_string_translation($name_string_id, ICL_LANGUAGE_CODE, $insert_update_args['name'], ICL_TM_COMPLETE);
				unset($insert_update_args['name']);
			}
		}
	}
	return $insert_update_args;
}

add_action('directorypress_update_field_group', 'directorypress_save_field_group', 10, 3);
function directorypress_save_field_group($field_group_id, $field_group, $array) {
	global $sitepress;
	
	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($sitepress->get_default_language() == ICL_LANGUAGE_CODE) {
			do_action('wpml_register_single_string', 'DirectoryPress', 'group name #' . $field_group_id, directorypress_get_input_value($array, 'name'));
		}
	}
}

add_action('init', 'directorypress_load_fields_categories');
function directorypress_load_fields_categories() {
	global $directorypress_object, $sitepress;

	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($sitepress->get_default_language() != ICL_LANGUAGE_CODE) {
			foreach ($directorypress_object->fields->fields_array AS &$field) {
				if ($field->is_categories()) {
					$_categories = get_option('directorypress_wpml_field_categories_'.$field->id.'_'.ICL_LANGUAGE_CODE);
					if ($_categories && (count($_categories) > 1 || $_categories != array('')))
						$field->categories = $_categories;
					else
						$field->categories = array();
				}
			}
		}
	}
}

?>