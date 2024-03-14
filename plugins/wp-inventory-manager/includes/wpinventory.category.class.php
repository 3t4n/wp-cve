<?php

// No direct access allowed.
if ( ! defined('ABSPATH')) {
	exit;
}

class WPIMCategory extends WPIMDB {
	
	private static $instance;
	
	/**
	* Constructor magic method.
	*/
	public function __construct() {
		parent::__construct();
	}
	
	public static function getInstance() {
		if ( ! self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	/**
	 * This is here purely to prevent someone from cloning the class
	 */
	private function __clone() {}
		
	/**
	 * Get a listing of categories.
	 * @param array $args
	 * valid arguments:
	 * order - set the sort order
	 * per_page - set the number per page
	 * paged - set the starting page
	 */
	public function get_all($args = NULL) {
		
		
		$where = '';
		$limit = '';
		$order = '';
		
		$defaults = [
			'order'		=> 'category_name',
			'per_page'	=> 0,  // Unlimited
			'paged'		=> 0,  // Beginning
			'name'		=> '', // Filter by name.  Will use %LIKE%
		];
		
		// Process the arguments
		$args = wp_parse_args((array)$args, $defaults);
		
		$args = apply_filters('wpim_get_categories_args', $args);
		
		extract($args);
		
		if ($order == 'sort_order') {
			$order = 'category_sort_order';
		}
		
//		$order = $this->parse_sort($order, $this->get_fields());
		
		if ($name) {
			$where = $this->wpdb->prepare(' WHERE c.category_name LIKE "%%s%"', $name);
		}
		
		if ((int)$per_page) {
			$limit = ' LIMIT ';
			$limit.= ((int)$paged) ? (int)$limit . ',' : '';
			$limit.= (int)$paged;
		}
		
		$order = ' ORDER BY ' . $order;
		
		$categories = $this->wpdb->get_results('SELECT * FROM ' . $this->category_table . ' AS c ' . $where . $limit . $order);
		
		return apply_filters('wpim_get_categories', $categories, $args);
	} 
	
	public function get_fields() {
		$fields = [
			"category_id",
			"category_name",
			"category_description",
			"category_slug",
			"category_sort_order"	
		];
		
		return apply_filters('wpim_get_category_fields', $fields);
	}
	
	/**
	 * Get specific category
	 * @param int $category_id
	 *
	 * @return object
	 */
	public function get($category_id) {
		$category = $this->wpdb->get_row($this->wpdb->prepare('SELECT * FROM ' . $this->category_table . ' WHERE category_id = %d', $category_id));
		return apply_filters('wpim_get_category', $category);
	}
	
	/**
	 * Get the category id from the name
	 * @param string $category_name
	 *
	 * @return int
	 */
	public function get_id_from_name($category_name) {
		return (int)$this->wpdb->get_var($this->wpdb->prepare('SELECT category_id FROM ' . $this->category_table . ' WHERE category_name = %s', $category_name));
	}
	
	public function save($data) {
		if ( ! isset($data['category_id'])) {
			$data['category_id'] = NULL;
		}
		$data['category_slug'] = $this->validate_slug('category', $data['category_slug'], $data['category_name'], $data['category_id']);
		
		$data = apply_filters('wpim_category_data_pre_save', $data);
		do_action('wpinventory_category_pre_save', $data);
		
		extract($data);
		
		$query = $this->wpdb->prepare(" " . $this->category_table . " SET 
			category_name = %s, 
			category_slug = %s,
			category_description = %s,
			category_sort_order = %d", 
			$category_name, $category_slug, $category_description, $category_sort_order );
		
		if ($category_id) {
			$query = $this->wpdb->prepare('UPDATE' . $query . ' WHERE category_id=%d', $category_id);
		} else {
			$query = 'INSERT INTO' . $query;
		}
		
		$this->wpdb->query($query);
		
		if ( ! $category_id) {
			$category_id = $this->wpdb->insert_id;
		}
		
		do_action('wpinventory_category_post_save', $category_id, $data);
		
		return ( ! $this->wpdb->last_error) ? $category_id : FALSE;
	}

	/**
	 * Updates a category, permits partial data (does not require all item fields)
	 *
	 * @param int $category_id
	 * @param array $data
	 *
	 * @return false|int
	 */
	public function update( $category_id, $data) {
		return $this->wpdb->update($this->category_table, $data, ['category_id' => $category_id]);
	}

	public function delete($category_id) {
		$items = $this->wpdb->get_var($this->wpdb->prepare('SELECT count(*) FROM ' . $this->inventory_table . ' WHERE category_id = %d', $category_id));
		if ($items) {
			self::$error = $this->__('Category has ' . $items . ' inventory items in it, and may not be deleted.');
			return FALSE;
		}
		
		return $this->wpdb->query($this->wpdb->prepare('DELETE FROM ' . $this->category_table . ' WHERE category_id= %d', $category_id));
	}
	
	public function dropdown($name, $selected, $class = '') {
		$categories = $this->get_all();
		$select = '<select name="' . esc_attr($name) . '"';
		$select.= ($class) ? ' class="' . esc_attr($class) . '"' : '';
		$select.= '>' . PHP_EOL;
		$select.= '<option value="">' . $this->__('Select Category') . '</option>' . PHP_EOL;
		foreach($categories AS $category) {
			$select.= '<option value="' . $category->category_id . '"';
			$select.= ($category->category_id == $selected) ? ' selected' : '';
			$select.= '>' . esc_attr($category->category_name) . '</option>' . PHP_EOL;
		}
		$select.= '</select>' . PHP_EOL;
		return $select;
	}
}
