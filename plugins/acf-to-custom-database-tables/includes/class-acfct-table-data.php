<?php


class Acfct_table_data
{
	use Acfct_formatters;

	protected $post_id = null;
	protected $table_name = null;
	protected $table_values = array();

	public function __construct($postId, $tableName)
	{
		$this->post_id = $postId;
		$this->table_name = Acfct_utils::maybe_prefix_table_name($tableName);
	}

	public function get($key, $name)
	{
		/**
		 * First it will search by key, if value not found then it will search by name
		 * To avoid duplicate key issue in FC, FC fields are mapped by name and other fields are mapped by key
		 */
		if (array_key_exists($key, $this->table_values)) {
			return $this->table_values[$key];
		} else if (array_key_exists($name, $this->table_values)) {
			return $this->table_values[$name];
		}
		return null;
	}

	public function getColumns()
	{
		global $wpdb;
		$table = $this->table_name;
		$query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='{$table}';";
		$result = $wpdb->get_results($query, ARRAY_N);
		$columns = array();
		if (is_array($result)) {
			foreach ($result as $col) {
				array_push($columns, $col[0]);
			}
		}
		return $columns;
	}

	public function saveData($data)
	{
		global $wpdb;
		$table = $this->table_name;

		$result = $this->fetch();
		if (count($result) !== 0) {
			$wpdb->update($table, $data, array(ACF_CUSTOM_TABLE_POST_ID_COLUMN => $this->post_id));
		} else {
			$data[ACF_CUSTOM_TABLE_POST_ID_COLUMN] = $this->post_id;
			$wpdb->insert($table, $data);
		}

	}

	private function fetch()
	{
		global $wpdb;
		$table = $this->table_name;
		$result = [];
		$result = apply_filters('acf_ct/fetch_table_data', $result, $this->post_id, $table);
		$result = apply_filters('acf_ct/fetch_table_data/name=' . $table, $result, $this->post_id);

		if (empty($result)) {
			$sql = 'SELECT * FROM ' . $table . ' WHERE ' . ACF_CUSTOM_TABLE_POST_ID_COLUMN . ' = ' . $this->post_id;
			$result = $wpdb->get_row($sql, ARRAY_A);
		}

		if ($result) {
			return $result;
		}
		return array();
	}

	public function fetchValues($acf_id)
	{
		$acf_fields = Acfct_utils::get_acf_keys($acf_id, true);
		$table_values = $this->fetch();
		$map = array();

		foreach ($acf_fields as $key => $field) {
			if (array_key_exists($field['name'], $table_values)) {
				$field_type = $field['type'];

				$formattedValue = $this->get_acf_formatted_value($acf_fields, $key, $field, $table_values[$field['name']]);

				if ($field_type === 'flexible_content' || $field_type === 'repeater') {
					$map = array_merge($map, $formattedValue);
				} else {
					$map[$key] = $formattedValue;
				}
			}
		}
		$this->table_values = $map;
		return $map;
	}

}
