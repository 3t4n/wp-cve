<?php

namespace ShopWP;

use ShopWP\Utils;
use ShopWP\Utils\Data;
use ShopWP\CPT;
use ShopWP\Transients;
use ShopWP\Options;

use function ShopWP\Vendor\DeepCopy\deep_copy;

if (!defined('ABSPATH')) {
    exit();
}

class DB
{
    public $table_name;
    public $version;
    public $primary_key;


    public function create_table()
    {
        return $this->create_table_if_doesnt_exist($this->get_table_name());
    }

    public function db_delta($table_class)
    {
        return \dbDelta(
            $table_class->create_table_query($table_class->table_name)
        );
    }

    public function create_table_if_doesnt_exist($table_name)
    {
        $result = false;

        $does_table_exist = $this->table_exists($table_name);

        // If table doesn't exist ...
        if (is_wp_error($does_table_exist) || empty($does_table_exist)) {
            require_once Utils::get_abs_admin_path('includes/upgrade.php');

            $result = \dbDelta($this->create_table_query($table_name));

            Options::update('shopwp_table_exists_' . $table_name, 1);
        }

        return $result;
    }

    public function get_all_rows_query($table_name) {
        return "SELECT * FROM " . $table_name;
    }

    public function get_rows_from_col_name_query($table_name, $col_name, $col_value) {
        
        global $wpdb;

        // return $wpdb->prepare("SELECT * FROM %s WHERE %s = %s", $table_name, $col_name, $col_value);
        // return "SELECT * FROM " . $table_name . " WHERE " . $col_name . " = " . $col_value;

        return $wpdb->prepare("SELECT * FROM " . $table_name . " WHERE " . $col_name . " = %s", $col_value);
    }    

    public function delete_from_primary_key_query($table_name, $primary_key, $column_value) {
        
        global $wpdb;

        // return $wpdb->prepare("DELETE FROM %s WHERE %s = %d", $table_name, $primary_key, $column_value);

        return $wpdb->prepare("DELETE FROM " . $table_name . " WHERE " . $primary_key . " = %d", $column_value);
    }
    
    public function get_all_rows_limit_one_query($table_name) {
        return "SELECT * FROM " . $table_name . " LIMIT 1;";
    }

    public function get_row_by_primary_key_query($table_name, $primary_key, $column_value) {
        
        global $wpdb;

        // return $wpdb->prepare("SELECT * FROM %s WHERE %s = %s LIMIT 1;", $table_name, $primary_key, $column_value);

        return $wpdb->prepare("SELECT * FROM " . $table_name . " WHERE " . $primary_key . " = %s LIMIT 1;", $column_value);
    }

    public function get_row_by_primary_key_and_column_name_query($column_name, $table_name, $primary_key, $column_value) {
        
        global $wpdb;

        // return $wpdb->prepare("SELECT %s FROM %s WHERE %s = %s LIMIT 1;", $column_name, $table_name, $primary_key, $column_value);

        return $wpdb->prepare("SELECT " . $column_name . " FROM " . $table_name . " WHERE " . $primary_key . " = %s LIMIT 1;", $column_value);
    }

    public function delete_where_col_int_query($table_name, $column_name, $column_value) {
        
        global $wpdb;

        // return $wpdb->prepare("DELETE FROM %s WHERE %s = %d", $table_name, $column_name, $column_value);

        return $wpdb->prepare("DELETE FROM " . $table_name . " WHERE " . $column_name . " = %d", $column_value);
    }

    public function delete_where_col_float_query($table_name, $column_name, $column_value) {
        
        global $wpdb;

        // return $wpdb->prepare("DELETE FROM %s WHERE %s = %f", $table_name, $column_name, $column_value);
        return $wpdb->prepare("DELETE FROM " . $table_name . " WHERE " . $column_name . " = %f", $column_value);
    }

    public function delete_where_col_string_query($table_name, $column_name, $column_value) {
        
        global $wpdb;

        // return $wpdb->prepare("DELETE FROM %s WHERE %s = %s", $table_name, $column_name, $column_value);
        return $wpdb->prepare("DELETE FROM " . $table_name . " WHERE " . $column_name . " = %s", $column_value);
    }

    public function delete_where_in_col_int_query($table_name, $column_name, $ids) {
        
        global $wpdb;

        // return $wpdb->prepare("DELETE FROM %s WHERE %s IN (%d)", $table_name, $column_name, $ids);
        return $wpdb->prepare("DELETE FROM " . $table_name . " WHERE " . $column_name . " IN (%d)", $ids);
    }

    public function delete_where_in_col_float_query($table_name, $column_name, $ids) {
        
        global $wpdb;

        // return $wpdb->prepare("DELETE FROM %s WHERE %s IN (%f)", $table_name, $column_name, $ids);
        return $wpdb->prepare("DELETE FROM " . $table_name . " WHERE " . $column_name . " IN (%f)", $ids);
    }

    public function delete_where_in_col_string_query($table_name, $column_name, $ids) {
        
        global $wpdb;

        // return $wpdb->prepare("DELETE FROM %s WHERE %s IN (%s)", $table_name, $column_name, $ids);
        return $wpdb->prepare("DELETE FROM " . $table_name . " WHERE " . $column_name . " IN (%s)", $ids);
    }

    public function show_vars_performance_query() {
        return "SHOW VARIABLES LIKE 'performance_schema'";
    }

    public function show_tables_link_query($table_sanitized) {
        return "SHOW TABLES LIKE '" . $table_sanitized . "'";
    }

    public function select_in_query($options)
    {

        global $wpdb;

        $vals_prepared = $this->prepare_in_statement($options['vals_to_check']);

        return $wpdb->prepare("SELECT items." . $options['col_to_return'] . " FROM " . $table_name . " as items WHERE items." . $options['col_to_check'] . " IN(%s)", $vals_prepared);

    }

    public function truncate_table_query($table_name) {
        return "TRUNCATE TABLE " . $table_name;
    }

    public function get_row_by_query($table_name, $column_name, $column_value) {
        
        global $wpdb;
        
        return $wpdb->prepare("SELECT * FROM " . $table_name . " WHERE " . $column_name . " = %s LIMIT 1;", $column_value);
    }

    public function select_like_query($options, $vals)
    {
        global $wpdb;
        
        return $wpdb->prepare('SELECT items.' . $options['col_to_return'] . ' FROM ' . $options['table_name'] . ' as items WHERE ' . $options['col_to_check'] . ' LIKE %s;', $vals);
    }

    public function get_column_single_query($column, $table_name)
    {
        return "SELECT " . $column . " FROM " . $table_name . ";";
    }

    public function get_column_by_query($column, $table_name, $column_where, $column_value)
    {
        global $wpdb;
        
        return $wpdb->prepare("SELECT " . $column . " FROM " . $table_name . " WHERE " . $column_where . " = %s LIMIT 1;", $column_value);
    }

    public function drop_table_if_exists_query($table_name) {
        return 'DROP TABLE IF EXISTS ' . $table_name;
    }    

    public function show_full_cols_query($table_name) {
        return 'SHOW FULL COLUMNS FROM ' . $table_name;
    }

    public function get_columns_desc_query($table_name) {
        return 'DESC ' . $table_name;
    }

    public function collate()
    {
        global $wpdb;

        $collate = '';

        if ($wpdb->has_cap('collation')) {
            $collate = $wpdb->get_charset_collate();
        }

        return $collate;
    }

    public function get_columns_current($table_name)
    {
        global $wpdb;

        $query = $this->get_columns_desc_query($table_name);

        $get_columns_current = $wpdb->get_col($query, 0);

        return $get_columns_current;
    }

    public function switch_shopify_ids(
        $data,
        $old_primary_key,
        $new_primary_key
    ) {
        if (!Utils::has($data, $old_primary_key)) {
            return $data;
        }

        $data->$new_primary_key = $data->$old_primary_key;
        unset($data->$old_primary_key);

        return $data;
    }

    public function tables_skip_rename_primary_key()
    {
        return [
            'settings_connection',
            'settings_general',
            'settings_syncing',
            'settings_license',
            'shop',
            'tag',
        ];
    }

    /*

	Maybe renames primary key of data before update / insert

	*/
    public function maybe_rename_to_lookup_key($item)
    {
        if (in_array($this->type, $this->tables_skip_rename_primary_key())) {
            return $item;
        }

        // If item doesnt have the shopify primary key
        // Only proceeds if 'id' is present on data
        if (!Utils::has($item, SHOPWP_SHOPIFY_PAYLOAD_KEY)) {
            return $item;
        }

        return $this->rename_to_lookup_key(
            $item,
            SHOPWP_SHOPIFY_PAYLOAD_KEY,
            $this->lookup_key
        );
    }

    /*

	Rename primary key

	$product

	*/
    public function rename_to_lookup_key(
        $data,
        $old_primary_key,
        $new_primary_key
    ) {
        $data = Utils::convert_array_to_object($data);

        // If keys have already been changed just return it
        if (
            Utils::has($data, $new_primary_key) &&
            !Utils::has($data, $old_primary_key)
        ) {
            return $data;
        }

        return $this->switch_shopify_ids(
            $data,
            $old_primary_key,
            $new_primary_key
        );
    }

    /*

	Renames primary keys for an array of items

	array_map("show_hindi", $counting, $hindi);

	*/
    public function rename_to_lookup_keys(
        $items,
        $old_primary_key,
        $new_primary_key
    ) {
        if (Utils::object_is_empty($items)) {
            return [];
        }

        $items = Utils::convert_object_to_array($items);

        return array_map(function ($item) use (
            $old_primary_key,
            $new_primary_key
        ) {
            return $this->rename_to_lookup_key(
                $item,
                $old_primary_key,
                $new_primary_key
            );
        },
        $items);
    }

    /*

	Copy objects

	*/
    public function copy($maybe_object)
    {
        return deep_copy($maybe_object);
    }

    /*

	Returns corrosponding table name. Contains prefix of single blog in multisite.

	*/
    public function get_table_name()
    {
        global $wpdb;

        return $wpdb->prefix . $this->table_name_suffix;
    }

    /*

	Gets the max packet size

	*/
    public function get_max_packet_size()
    {
        global $wpdb;

        $results = $wpdb->get_results(
            "SHOW VARIABLES LIKE 'max_allowed_packet'"
        );

        if (!empty($results)) {
            return (int) $results[0]->Value;
        } else {
            return 0;
        }
    }



    public function max_packet_size_reached($query)
    {
        global $wpdb;

        $results = $wpdb->get_results($this->show_vars_performance_query());

        // Default to false, if not set
        if (empty($results)) {
            return false;
        }

        if (Data::size_in_bytes($query) > $this->get_max_packet_size()) {
            return true;

        } else {
            return false;
        }
    }



    /*

	Retrieve a row by the primary key

	*/
    public function get($row_id = 0)
    {
        global $wpdb;

        $table_name = $this->get_table_name();

        if (empty($row_id)) {
            $query = $this->get_all_rows_limit_one_query($table_name);
            $results = $wpdb->get_row($query);

        } else {
            $query = $this->get_row_by_primary_key_query($table_name, $this->primary_key, $row_id);
            $results = $wpdb->get_row($query);
        }

        return $this->sanitize_db_response(
            $results,
            'ShopWP Error: Failed to get database row while calling query: ' . $query,
            'get_row'
        );

    }

    /*

	Retrieve a row by a specific column / value

	*/
    public function get_row_by($column_name, $column_value)
    {
        global $wpdb;

        $table_name = $this->get_table_name();

        $column_name = esc_sql($column_name);
        
        $query = $this->get_row_by_query($table_name, $column_name, $column_value);

        $results = $wpdb->get_row($query);

        return $this->sanitize_db_response(
            $results,
            'ShopWP Error: Unable to get database row by column ' . $column_name . ' for table ' . $table_name,
            'get_row'
        );
    }



    /*

	Retrieve rows by a specific column / value

	*/
    public function get_rows($col_name, $col_value)
    {
        global $wpdb;

        $table_name = $this->get_table_name();

        $col_name = esc_sql($col_name);
        $query = $this->get_rows_from_col_name_query($table_name, $col_name, $col_value);

        return $wpdb->get_results($query);
    }

    public function prepare_in_statement($values_to_prep)
    {
        if (!is_array($values_to_prep)) {
            $values_to_prep = [$values_to_prep];
        }

        // how many entries will we select?
        $how_many = count($values_to_prep);

        // prepare the right amount of placeholders
        // if you're looing for strings, use '%s' instead
        $placeholders = array_fill(0, $how_many, '%s');

        // glue together all the placeholders...
        // $format = '%d, %d, %d, %d, %d, [...]'
        $format = implode(', ', $placeholders);

        return $format;
    }


    public function select_in_refine_results($results, $options)
    {
        return array_map(function ($result) use ($options) {
            return $result->{$options['col_to_return']};
        }, $results);
    }

    public function select_in($options)
    {
        global $wpdb;

        $hash = Utils::hash($options, true);

        $cache = Transients::get('shopwp_select_in_query_' . $hash);

        if ($cache) {
            return $cache;
        }

        $query = $this->select_in_query($options);

        $results = $wpdb->get_results($query);

        $final_results = array_unique(
            $this->select_in_refine_results($results, $options)
        );

        Transients::update('shopwp_select_in_query_' . $hash, $final_results);

        return $final_results;
    }

    public function select_like($options)
    {
        global $wpdb;

        $vals = '%' . $wpdb->esc_like($options['vals_to_check']) . '%';

        $prepared_query = $this->select_like_query($options, $vals);

        $results = $wpdb->get_results($prepared_query);

        return array_unique(
            $this->select_in_refine_results($results, $options)
        );
    }

    /*

   Responsible for returning an array of product ids from an array of slug strings

   */
    public function select_in_col($col_to_return, $col_to_check, $vals)
    {
        if (empty($vals)) {
            return [];
        }

        return $this->select_in([
            'col_to_return' => $col_to_return,
            'col_to_check' => $col_to_check,
            'vals_to_check' => $vals,
        ]);
    }

    public function select_like_col(
        $col_to_return,
        $col_to_check,
        $vals,
        $table_name
    ) {
        if (empty($vals)) {
            return [];
        }

        return $this->select_like([
            'col_to_return' => $col_to_return,
            'col_to_check' => $col_to_check,
            'vals_to_check' => $vals,
            'table_name' => $table_name,
        ]);
    }

    /*

	Retrieves all rows

	*/
    public function get_all_rows()
    {
        global $wpdb;

        $table_name = $this->get_table_name();
        $query = $this->get_all_rows_query($table_name);
        
        $results = $wpdb->get_results($query);

        return $this->sanitize_db_response(
            $results,
            'ShopWP Error: Failed to get all rows from table "' . $table_name . '"',
            'get_results'
        );
    }

    /*

	Retrieve a specific column's value by the primary key

	*/
    public function get_column($column_name, $column_value, $table_name = false)
    {
        global $wpdb;

        if (!$table_name) {
            $table_name = $this->get_table_name();
        }

        $column_name = esc_sql($column_name);
        
        $query = $this->get_row_by_primary_key_and_column_name_query($column_name, $table_name, $this->primary_key, $column_value);

        $results = $wpdb->get_var($query);

        return $this->sanitize_db_response(
            $results,
            'ShopWP Error: Failed to get value from column "' . $column_name . '" on table "' . $table_name . '" while using primary key "' . $this->primary_key . '".',
            'get_var'
        );
    }



    /*

	Retrieve a specific column's value by the primary key
	TODO: Return the actual value instead of array('col_name' => 'value')

	From Codex: "If no matching rows are found, or if there is a database error, the return value will be an empty array. If your $query string is empty, or you pass an invalid $output_type, NULL will be returned.""

	Can return the following values:

	WP_Error
	False if nothing found or error
	Array of objects


	*/
    public function get_column_single($column)
    {
        global $wpdb;

        $table_name = $this->get_table_name();

        // Dont get the column value if it doesnt exist ...
        if (!$column) {
            return new \WP_Error('error', 'ShopWP Error: Database error on table ' . $table_name . '. Column name was empty while running get_column_single().');
        }

        $does_table_exist = $this->table_exists($table_name);

        // If table doesnt exist ...
        if (is_wp_error($does_table_exist)) {
            return $this->sanitize_db_response(
                false,
                'ShopWP Error: Failed to get single database column: ' . $column . '. Table "' . $table_name . '" doesn\'t exist',
                'get_var'
            );
        }

        if (!is_string($column)) {
            return new \WP_Error('error', 'ShopWP Error: Database error on table ' . $table_name . '. Column name: ' . $column . ' is not a string');
        }

        // If argument not apart of schema ...
        if (!\array_key_exists($column, $this->get_columns())) {
            return new \WP_Error('error', 'ShopWP Error: Database column name: ' . $column . ' is not apart of schema. Please try deactivating and reactivating the plugin.');
        }

        // Construct query and get result ...
        $query = $this->get_column_single_query($column, $table_name);

        $results = $wpdb->get_results($query);

        return $this->sanitize_db_response(
            $results,
            'ShopWP Error: Failed to get database results while calling query: ' . $query,
            'get_results'
        );
    }

    public function get_column_by($column, $column_where, $column_value)
    {
        global $wpdb;

        $table_name = $this->get_table_name();

        $column_where = esc_sql($column_where);
        $column = esc_sql($column);
        $query = $this->get_column_by_query($column, $table_name, $column_where, $column_value);

        $results = $wpdb->get_var($query);

        return $this->sanitize_db_response(
            $results,
            'ShopWP Error: Failed to get row from column "' . $column . '" on table "' . $table_name . '".',
            'get_var'
        );
    }

    public function has_existing_record($data)
    {
        global $wpdb;

        if (!isset($data[$this->lookup_key])) {
            return false;
        }

        $results = $this->get_row_by(
            $this->lookup_key,
            $data[$this->lookup_key]
        );

        if (empty($results) || is_wp_error($results)) {
            return false;
        }

        return true;
    }

    /*

	Checks if there was a MySQL error

	*/
    public function has_mysql_error()
    {
        global $wpdb;

        if ($wpdb->last_error !== '') {
            return true;
        } else {
            return false;
        }
    }

    /*

	Default mod before change just returns

	*/
    public function mod_before_change($item)
    {
        return $item;
    }

    /*

	Helper method for returning MYSQL errors

	Used only for MySQL operations

	$result: The result of a wpdb operation
	$fallback_message: The message to use if an error occurs
	$type: Name of the wpdb function. Possible values:
		- get_row
		- get_results
		- query
		- update
		- insert


	Possible return values:
	    - WP_Error if any errors
	    - false if no results are found
	    - true If the $data matches what is already in the database, no rows will be updated, so 0 will be returned. No errors occured and nothing was updated.
        - Non-empty array with results of successful db operation 

	*/
    public function sanitize_db_response(
        $result = false,
        $fallback_message = 'Uncaught error. Please clear the plugin cache and try again.',
        $type = false
    ) {

        global $wpdb;

        /*

		If $wpdb->last_error doesnt contain an empty string, then we know the query
		failed in some capacity. We can safely return this wrapped inside a WP_Error.

		*/
        if ($this->has_mysql_error()) {
            return new \WP_Error('error', $wpdb->last_error);
        }

        /*

		Returns false if errors:

		$wpdb->update
        (int|false) The number of rows updated, or false on error.

		$wpdb->delete
        (int|false) The number of rows updated, or false on error.

		$wpdb->insert
        (int|false) The number of rows inserted, or false on error.

		$wpdb->replace
        (int|false) The number of rows affected, or false on error.

        $wpdb->query
        (int|bool) Boolean false on error.

		*/
        if ($type === 'query' || $type === 'update' || $type === 'delete' || $type === 'insert' || $type === 'replace') {
            if ($result === false) {
                return new \WP_Error('error', $fallback_message);
            }
        }

        /*

		$wpdb->get_col			    -- (array) Database query result. Array indexed from 0 by SQL result row number.
		$wpdb->get_results			-- (array|object|null) Database query results.
		$wpdb->get_var 				-- (string|null) Database query result (as string), or null on failure.
		$wpdb->get_row 				-- (array|object|null|void) Database query result in format specified by $output or null on failure.

		*/

        if ($type === 'get_var' && is_null($result)) {
            return new \WP_Error('error', $fallback_message);
        }

        if ($type === 'get_row' && is_null($result)) {
            return new \WP_Error('error', $fallback_message);
        }

        if ($type === 'get_results' && is_null($result)) {
            return new \WP_Error('error', $fallback_message);
        }

        if ($type === 'get_col') {
            if (is_array($result) && empty($result)) {
                return false;
            }
        }

        /*

		If the $data matches what is already in the database, no rows will be updated, so 0 will be returned.

		No errors occured and nothing was updated.

		$wpdb->update: If nothing was updated
		$wpdb->delete: If nothing was deleted

		*/
        if ($result === 0) {
            if ($type === 'query' || $type === 'update') {
                return true;
            }
        }

        // If execution gets to hear, then we have actual data to work with in the form of a non-empty array
        return $result;
    }

    /*

	Insert a new row

	Returns false if the row could not be inserted. Otherwise, it returns the number of affected rows (which will always be 1).
	https://codex.wordpress.org/Class_Reference/wpdb

	*/
    public function insert($data)
    {
        global $wpdb;

        $table_name = $this->get_table_name();

        // Convert data to array if not one already
        $data = Utils::convert_object_to_array($data);

        // Return immediately, if $data does not exist or if it equals false
        if (empty($data)) {
            return false;
        }

        // Gets the table's column names
        $column_formats = $this->get_columns();

        // Performs any needed data structure changes before insert (primary key, adding values, etc)
        $data = $this->mod_before_change($data);

        // Set default values. Requires $data to be array
        $data = wp_parse_args($data, $this->get_column_defaults());

        // Sanitizing nested arrays (serializes nested arrays and objects)
        $data = Utils::serialize_data_for_db($data);

        // Force fields to lower case
        $data = array_change_key_case($data);

        // White list columns
        $data = array_intersect_key($data, $column_formats);

        // Reorder $column_formats to match the order of columns given in $data
        $data_keys = array_keys($data);

        $max_col_size_limit_reached = Utils::data_values_size_limit_reached(
            $data,
            $table_name
        );

        /*

		If data to insert is too big, preemptively throw error. If we don't, $wpdb fails silently

		*/
        if ($max_col_size_limit_reached !== false) {

            $message_aux =
                'insert within "' .
                $table_name .
                '" on column "' .
                $max_col_size_limit_reached['column_name'] .
                '". <br><br><b>Attempted to insert value:</b> ' .
                $max_col_size_limit_reached['value_attempted'] .
                '<br><b>Max column size:</b> ' .
                $max_col_size_limit_reached['max_size'];

            return new \WP_Error('error', $message_aux);
        }

        $column_formats = array_merge(array_flip($data_keys), $column_formats);

        /*

        Checks whether the item we're inserting into the DB
        already exists to avoid errors. We can do this by first running $wpdb->get_results
        and then cheking the num rows like below:

        */
        if ($this->has_existing_record($data)) {
            
            $result = $wpdb->update($table_name, $data, $column_formats);

            return $this->sanitize_db_response(
                $result,
                'ShopWP Error: Failed to update database record in table ' . $table_name .'. Please clear the plugin cache and try again.',
                'update'
            );

        } else {

            $result = $wpdb->insert($table_name, $data, $column_formats);

            return $this->sanitize_db_response(
                $result,
                'ShopWP Error: Failed to insert database record in table ' . $table_name .'. Please clear the plugin cache and try again.',
                'insert'
            );
        }
    }

    /*

	Update a new row


	By default, $row_id refers to the primary key of the table. However if $where is passed in, then
	$row_id will refer to this column instead


	*/
    public function update($column_name = false, $column_value = false, $data = [])
    {
        global $wpdb;

        $table_name = $this->get_table_name();

        // Row ID must be positive integer
        $column_value = absint($column_value);

        // Record must already exist already to update
        if (empty($column_value)) {
            return new \WP_Error('error', 'ShopWP Error: No database column value found to check against table: ' . $table_name);
        }

        // Sets the column for lookup to the primary key of the table by default
        if ($column_name === false) {
            $column_name = $this->primary_key;
        }

        // Performs any needed data structure changes before update (primary key, adding values, etc)
        $data = $this->mod_before_change($data);

        // Forces data to array
        $data = Utils::convert_object_to_array($data);

        $data = Utils::serialize_data_for_db($data);

        // Initialize column format array
        $column_formats = $this->get_columns();

        // Force fields to lower case
        $data = array_change_key_case($data);

        // White list columns
        $data = array_intersect_key($data, $column_formats);

        // Reorder $column_formats to match the order of columns given in $data
        $data_keys = array_keys($data);

        $column_formats = array_merge(array_flip($data_keys), $column_formats);

        $results = $wpdb->update(
            $table_name,
            $data,
            [$column_name => $column_value],
            $column_formats
        );

        return $this->sanitize_db_response(
            $results,
            'ShopWP Error: Failed to update database table "' . $table_name . '" on column name "' . $column_name . '" with the new value of "' . $column_value . '". Please clear the plugin cache and try again.',
            'update'
        );
    }

    /*

	Update a new row
	Returns boolean

	*/
    public function update_col(
        $col_name = false,
        $col_val = fale,
        $where = ['id' => 1]
    ) {
        global $wpdb;

        $data = [];
        $data[$col_name] = $col_val;

        $table_name = $this->get_table_name();

        if (empty($where)) {
            return new \WP_Error('error', 'ShopWP Error: Failed to update database column in table "' . $table_name . '". Where clause does not exist.');
        }

        $results = $wpdb->update($table_name, $data, $where);

        return $this->sanitize_db_response(
            $results,
            'ShopWP Error: Failed to update database column "' . $col_name . '" to value "' . $col_val . '" for table "' . $table_name . '". Please clear the plugin cache and try again.',
            'update'
        );
    }

    /*

	Truncates table

	*/
    public function truncate()
    {
        global $wpdb;

        $table_name = $this->get_table_name();

        if (!$this->table_exists($table_name)) {
            return new \WP_Error('error', 'ShopWP Error: Tried to truncate table ' . $table_name . ' but table doesn\'t exist.');
        }

        $query_results = $wpdb->query($this->truncate_table_query($table_name));

        return $this->sanitize_db_response(
            $query_results,
            'ShopWP Error: Failed to truncate table ' . $table_name . '. Please clear the plugin cache and try again.',
            'query'
        );
    }



    /*

	Delete a row identified by the primary key

	Used only to delete single rows from a table specified by primary key.

	*/
    public function delete($row_id = 1)
    {
        global $wpdb;

        $table_name = $this->get_table_name();

        // Row ID must be positive integer
        $row_id = absint($row_id);

        $query = $this->delete_from_primary_key_query($table_name, $this->primary_key, $row_id);
        $query_results = $wpdb->query($query);

        return $this->sanitize_db_response(
            $query_results,
            'ShopWP Error: Failed to delete record(s) in table ' . $table_name . ' on row "' . $row_id .'". Please clear the plugin cache and try again.',
            'query'
        );
    }

    public function query($query)
    {
        global $wpdb;

        $results = $wpdb->query($query);

        return $this->sanitize_db_response(
            $results,
            'ShopWP Error: Database query failed when executing: ' .$query,
            'query'
        );
    }

    /*

	Deletes a normal ShopWP table

	TODO: Will only delete table if it exists. We should probably alert the system
	somehow if the table was _expected to exist_ but didn't for some reason.

	*/
    public function delete_table()
    {
        global $wpdb;

        $table_name = $this->get_table_name();
        $query = $this->drop_table_if_exists_query($table_name);

        $results = $wpdb->query($query);

        return $this->sanitize_db_response(
            $results,
            'ShopWP Error: Failed to delete table "' . $table_name . '". Please try uninstalling the plugin instead.',
            'query'
        );
    }

    /*

	Delete a row(s) identified by column value

	*/
    public function delete_rows($column_name, $column_value)
    {
        global $wpdb;

        $table_name = $this->get_table_name();
        $column_name = esc_sql($column_name);

        if (gettype($column_value) === 'integer') {
            $query = $this->delete_where_col_int_query($table_name, $column_name, $column_value);

        } elseif (gettype($column_value) === 'double') {
            $query = $this->delete_where_col_float_query($table_name, $column_name, $column_value);

        } else {
            $query = $this->delete_where_col_string_query($table_name, $column_name, $column_value);
        }

        $results = $wpdb->query($query);

        return $this->sanitize_db_response(
            $results,
            'ShopWP Error: Failed to delete database rows on table "' . $table_name . '" matching column "' . $column_name . '". Please clear the plugin cache and try again.',
            'query'
        );
    }

    /*

	Delete a row(s) identified by column value

	$values comes in as an array. We must turn it into a comma
	seperated list.

	*/
    public function delete_rows_in($column, $ids)
    {
        global $wpdb;

        // $table_name = $this->get_table_name();
        // $column = esc_sql($column);

        if (gettype($ids) === 'integer') {
            $query = $this->delete_where_in_col_int_query();

        } elseif (gettype($ids) === 'double') {
            $query = $this->delete_where_in_col_float_query();

        } else {
            $query = $this->delete_where_in_col_string_query($table_name, $column_name, $ids);
        }

        $result = $wpdb->get_results($query);

        return $this->sanitize_db_response(
            $result,
            'ShopWP Error: Failed to delete database rows by column "' . $column . '" on in table "' . $table_name . '". Please clear the plugin cache and try again.',
            'get_results'
        );
    }

    public function search_for_table($table)
    {
        global $wpdb;

        $table_sanitized = sanitize_text_field($table);

        $query = $this->show_tables_link_query($table_sanitized);

        $result = $wpdb->get_var($query);

        return $this->sanitize_db_response(
            $result,
            'ShopWP Error: Unable to search for table: ' .  $table_sanitized,
            'get_var'
        );
    }

    /*

	Check if the given table exists

	*/
    public function table_exists($table_name)
    {
        if (Options::get('shopwp_table_exists_' . $table_name)) {
            return true;
        }

        $db_result = $this->search_for_table($table_name);

        if (is_wp_error($db_result)) {
            return $db_result;
        }

        // Tables exists
        if ($db_result === $table_name) {
            Options::update('shopwp_table_exists_' . $table_name, 1);

            return true;
        }

        return false;
    }

    /*

	Checks if the tables has been initialized or not

	*/
    public function table_has_been_initialized($primary_key = 'id')
    {

        $row = $this->get_rows($primary_key, 1);

        if (count($row) <= 0) {
            return false;
            
        } else {
            return true;
        }
    }

    /*

	Table charset: Get column info

	*/
    public function get_col_info($table)
    {
        global $wpdb;

        $table_name = $this->get_table_name();
        $query = $this->show_full_cols_query($table_name);

        $results = $wpdb->get_results($query);

        return $this->sanitize_db_response(
            $results,
            'ShopWP Error: Failed to get database column info on table "' . $table_name . '".',
            'get_var'
        );

    }

    /*

	Table charset: Get column data

	*/
    public function construct_column_data($column_info, $columns)
    {
        foreach ($column_info as $column) {
            $columns[strtolower($column->Field)] = $column;
        }

        return $columns;
    }

    /*

	Table charset: Get charset from count

	*/
    public function construct_charset_from_count($charsets)
    {
        // Check if we have more than one charset in play.
        $count = count($charsets);

        if (1 === $count) {
            $charset = key($charsets);
        } elseif (0 === $count) {
            // No charsets, assume this table can store whatever.
            $charset = false;
        } else {
            // More than one charset. Remove latin1 if present and recalculate.
            unset($charsets['latin1']);
            $count = count($charsets);

            if (1 === $count) {
                // Only one charset (besides latin1).
                $charset = key($charsets);
            } elseif (
                2 === $count &&
                isset($charsets['utf8'], $charsets['utf8mb4'])
            ) {
                // Two charsets, but they're utf8 and utf8mb4, use utf8.
                $charset = 'utf8';
            } else {
                // Two mixed character sets. ascii.
                $charset = 'ascii';
            }
        }

        return $charset;
    }

    /*

	Table charset: Check for binary type

	*/
    public function check_for_binary_type($type)
    {
        // A binary/blob means the whole query gets treated like this.
        if (
            in_array(strtoupper($type), [
                'BINARY',
                'VARBINARY',
                'TINYBLOB',
                'MEDIUMBLOB',
                'BLOB',
                'LONGBLOB',
            ])
        ) {
            return true;
        } else {
            return false;
        }
    }

    /*

	Table charset: Get charsets from collation

	*/
    public function construct_charsets_from_collation($column, $charsets)
    {
        global $wpdb;

        if (!empty($column->Collation)) {
            list($charset) = explode('_', $column->Collation);

            // If the current connection can't support utf8mb4 characters, let's only send 3-byte utf8 characters.
            if ('utf8mb4' === $charset && !$wpdb->has_cap('utf8mb4')) {
                $charset = 'utf8';
            }

            $charsets[strtolower($charset)] = true;
        }

        return $charsets;
    }

    /*

	Table charset: Check for utf8md3

	*/
    public function check_for_utf8md3($charsets)
    {
        // utf8mb3 is an alias for utf8.
        if (isset($charsets['utf8mb3'])) {
            $charsets['utf8'] = true;
            unset($charsets['utf8mb3']);
        }

        return $charsets;
    }

    /*

	Table charset: Get table charset

	*/
    public function get_table_charset($table)
    {
        global $wpdb;

        $table = strtolower($table);

        if (get_transient('shopwp_table_charset_' . $table)) {
            return get_transient('shopwp_table_charset_' . $table);
        }

        $charsets = [];
        $columns = [];

        $column_info = $this->get_col_info($table);

        if (is_wp_error($column_info)) {
            return $column_info;
        }

        $columns = $this->construct_column_data($column_info, $columns);

        foreach ($columns as $column) {
            $charsets = $this->construct_charsets_from_collation(
                $column,
                $charsets
            );

            list($type) = explode('(', $column->Type);

            if ($this->check_for_binary_type($type)) {
                return 'binary';
            }
        }

        $charsets = $this->check_for_utf8md3($charsets);

        $charset = $this->construct_charset_from_count($charsets);

        set_transient('shopwp_table_charset_' . $table, $charset);

        return $charset;
    }

    /*

	Wrapper function for encoding utf8 charset content into utf8mb4

	*/
    public function maybe_encode_emoji_content($content)
    {
        if (
            function_exists('wp_encode_emoji') &&
            function_exists('mb_convert_encoding')
        ) {
            $content = wp_encode_emoji($content);
        }

        return $content;
    }

    public function encode_data($items)
    {
        if (empty($items)) {
            return $items;
        }

        // If one big string is passed in, just encode and return it
        if (is_string($items)) {
            return $this->maybe_encode_emoji_content($items);
        }

        foreach ($items as $key => $value) {
            if (empty($value)) {
                return;
            }

            if (is_array($value) || is_object($value)) {
                $this->encode_data($value);
            } else {
                if (is_string($value)) {
                    if (is_array($items)) {
                        $items[$key] = $this->maybe_encode_emoji_content(
                            $value
                        );
                    }

                    if (is_object($items)) {
                        $items->{$key} = $this->maybe_encode_emoji_content(
                            $value
                        );
                    }
                }
            }
        }

        return $items;
    }

    public function insert_default_values($blog_id = false)
    {
        return $this->insert($this->get_column_defaults($blog_id));
    }

    public function get_lookup_value($item)
    {
        // product_id, variant_id, image_id, etc
        if (Utils::has($item, $this->lookup_key)) {
            return $item->{$this->lookup_key};
        }

        // id
        if (Utils::has($item, SHOPWP_SHOPIFY_PAYLOAD_KEY)) {
            return $item->{SHOPWP_SHOPIFY_PAYLOAD_KEY};
        }
    }

    public function get_current_items($method_name, $options)
    {
        return $this->$method_name(
            $options['item']->{SHOPWP_SHOPIFY_PAYLOAD_KEY}
        );
    }

    /*

	Gathers items for modification.

	* Important * SHOPWP_SHOPIFY_PAYLOAD_KEY is used to build the get_{items}_from_product_id

	$options

		- $item 									-- $product, $variant, $image, etc
		- $prop_to_access 				-- prop to fetch on latest items
		- $payload_key 		-- old primary key from payload (id)
		- $lookup_key 				-- primary key to we assign (product_id, variant_id, etc)

	*/
    public function gather_items_for_modification($options)
    {
        $method_name_get_type_from =
            'get_' .
            $options['prop_to_access'] .
            '_from_' .
            $options['item_lookup_key'];

        $current_items = $this->get_current_items(
            $method_name_get_type_from,
            $options
        );

        $latest_items = $this->get_latest_items_from_payload(
            $options['item'],
            $options['prop_to_access']
        );
        $latest_items = $this->rename_to_lookup_keys(
            $latest_items,
            SHOPWP_SHOPIFY_PAYLOAD_KEY,
            $this->lookup_key
        );

        return [
            'current' => Utils::convert_object_to_array($current_items),
            'latest' => Utils::convert_object_to_array($latest_items),
        ];
    }

    public function no_items($options)
    {
        return empty($options['item']);
    }

    public function get_latest_items_from_payload($payload, $prop)
    {
        if (!Utils::has($payload, $prop)) {
            return [];
        }

        return $payload->{$prop};
    }

    public function gather_items_for_deletion($options)
    {
        $items = $this->gather_items_for_modification($options);

        return Utils::find_items_to_delete(
            $items['current'],
            $items['latest'],
            true,
            $this->lookup_key
        );
    }

    public function gather_items_for_insertion($options)
    {
        if ($this->no_items($options)) {
            return [];
        }

        $items = $this->gather_items_for_modification($options);

        return Utils::find_items_to_add(
            $items['current'],
            $items['latest'],
            true,
            $this->lookup_key
        );
    }

    public function gather_items_for_updating($options)
    {
        if ($this->no_items($options)) {
            return [];
        }

        return $options['item']->{$options['prop_to_access']};
    }

    public function maybe_delete($items_to_delete, $type)
    {
        return $this->delete_items_of_type($items_to_delete, $type);
    }

    public function maybe_insert($items_to_add, $type)
    {
        if (empty($items_to_add)) {
            return [];
        }

        return $this->insert_items_of_type($items_to_add, $type);
    }

    public function maybe_update($items_to_update, $type)
    {
        if (empty($items_to_update)) {
            return [];
        }

        return $this->update_items_of_type($items_to_update, $type);
    }

    public function change_item($item, $method_name)
    {
        if (method_exists($this, $method_name)) {
            return $this->$method_name($item);
        }
    }

    public function change_items_of_type($items, $method_name)
    {
        $results = [];

        if (!is_array($items)) {
            return $this->change_item($items, $method_name);
        }

        foreach ($items as $item) {
            $result = $this->change_item($item, $method_name);

            if (is_wp_error($result)) {
                return $result;
            }

            $results[] = $result;
        }

        return $results;
    }

    public function insert_items_of_type($items)
    {
        return $this->change_items_of_type($items, 'insert_' . $this->type);
    }

    public function delete_items_of_type($items)
    {
        return $this->change_items_of_type($items, 'delete_' . $this->type);
    }

    public function update_items_of_type($items)
    {
        return $this->change_items_of_type($items, 'update_' . $this->type);
    }

    /*

	Main entry point for webhooks

	Returns WP_Error object on error or the number of rows affected on success

	In order to handle an update being initated by _new_ data (e.g., when a new variant is added),
	we need to compare what's currently in the database with what gets sent back via the
	product/update webhook.

	*/
    public function modify_from_shopify($options)
    {
        $results = [];
        $insert_options = $this->copy($options);
        $update_options = $this->copy($options);
        $delete_options = $this->copy($options);

        $items_to_insert = $this->gather_items_for_insertion($insert_options);
        $items_to_delete = $this->gather_items_for_deletion($update_options);
        $items_to_update = $this->gather_items_for_updating($delete_options);

        // Insertions
        $insert_result = $this->maybe_insert(
            $items_to_insert,
            $options['change_type']
        );

        if (is_wp_error($insert_result)) {
            return $insert_result;
        } else {
            $results['created'][] = $insert_result;
        }

        // Deletions
        $delete_result = $this->maybe_delete(
            $items_to_delete,
            $options['change_type']
        );

        if (is_wp_error($delete_result)) {
            return $delete_result;
        } else {
            $results['deleted'][] = $delete_result;
        }

        // Updates
        $update_result = $this->maybe_update(
            $items_to_update,
            $options['change_type']
        );

        if (is_wp_error($update_result)) {
            return $update_result;
        } else {
            $results['updated'][] = $update_result;
        }

        return $results;
    }

    public function get_col_val($col_name, $return_type = false)
    {
        $col_value = $this->get_column_single($col_name);

        if (is_wp_error($col_value)) {
            return $col_value;
        }

        if (
            Utils::array_not_empty($col_value) &&
            isset($col_value[0]->{$col_name})
        ) {
            if ($return_type) {
                $col_value_to_return = $col_value[0]->{$col_name};

                return Data::coerce($col_value_to_return, $return_type);
            } else {
                return $col_value[0]->{$col_name};
            }
        } else {
            return $col_value;
        }
    }
}
