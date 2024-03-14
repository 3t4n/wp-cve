<?php
/**
 * This file is responsible for all database realted functionality.
 */
class ccpw_database
{

    /**
     * Get things started
     *
     * @access  public
     * @since   1.0
     */
    public $table_name;
    public $primary_key;
    public $version;
    public function __construct()
    {
        global $wpdb;

        $this->table_name = $wpdb->base_prefix . 'ccpw_coins';
        $this->primary_key = 'id';
        $this->version = '1.0';

    }

    /**
     * Get columns and formats
     *
     * @access  public
     * @since   1.0
     */
    public function get_columns()
    {
        return array(
            'id' => '%d',
            'coin_id' => '%s',
            'rank' => '%d',
            'name' => '%s',
            'symbol' => '%s',
            'price' => '%f',
            'percent_change_24h' => '%f',
            'market_cap' => '%f',
            'total_volume' => '%f',
            'circulating_supply' => '%d',
            'logo' => '%s',
        );
    }

    public function ccpw_insert($coins_data)
    {
        if (is_array($coins_data) && count($coins_data) > 1) {

            return $this->wp_insert_rows($coins_data, $this->table_name, true, 'coin_id');
        }
    }
    /**
     * Get default column values
     *
     * @access  public
     * @since   1.0
     */
    public function get_column_defaults()
    {
        return array(
            'coin_id' => '',
            'rank' => '',
            'name' => '',
            'symbol' => '',
            'price' => '',
            'percent_change_24h' => '',
            'market_cap' => '',
            'total_volume' => '',
            'circulating_supply' => '',
            'logo' => '',
            'last_updated' => gmdate('Y-m-d H:i:s'),
        );
    }

    /**
     * Check if coin exists by ID
     *
     * @access  public
     * @param   string $coin_id
     * @return  bool
     */
    public function coin_exists_by_id($coin_id)
    {
        global $wpdb;
        $coin_id = esc_sql($coin_id);
        $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $this->table_name WHERE coin_id = %s", $coin_id));
        return $count == 1;
    }
    /**
     * Retrieve coins from the database.
     *
     * @access  public
     * @since   1.0
     * @param   array $args   Arguments for filtering data.
     * @param   bool  $count  Whether to return count only (optional).
     */
    public function get_coins($args = array(), $count = false)
    {
        global $wpdb;

        // Default arguments
        $defaults = array(
            'number' => 20,
            'offset' => 0,
            'id' => '',
            'coin_id' => '',
            'name' => '',
            'status' => '',
            'orderby' => 'id',
            'order' => 'ASC',
        );

        // Merge provided arguments with defaults
        $args = wp_parse_args($args, $defaults);

        // Set minimum number of results
        $args['number'] = max(1, $args['number']); // Ensure number is at least 1

        $where = '';

        // Add conditions based on arguments
        if (!empty($args['id'])) {
            // Specific IDs
            $order_ids = is_array($args['id']) ? implode(',', array_map('absint', $args['id'])) : absint($args['id']);
            $where .= "WHERE `id` IN ({$order_ids}) ";
        }

        if (!empty($args['coin_id'])) {
            // Coin IDs
            $where .= empty($where) ? ' WHERE' : ' AND';
            if (is_array($args['coin_id'])) {
                $coin_ids_escaped = array_map('esc_sql', $args['coin_id']);
                $where .= " `coin_id` IN ('" . implode("','", $coin_ids_escaped) . "') ";
            } else {
                $where .= " `coin_id` = '" . esc_sql($args['coin_id']) . "' ";
            }
        }

        // Handle orderby parameter
        $allowed_columns = array_keys($this->get_columns());
        $args['orderby'] = in_array($args['orderby'], $allowed_columns) ? $args['orderby'] : $this->primary_key;
        if ($args['orderby'] === 'total' || $args['orderby'] === 'subtotal') {
            $args['orderby'] .= '+0';
        }

        // Generate cache key
        $cache_key = (true === $count) ? sanitize_text_field(md5('ccpw_coins_count' . serialize($args))) : sanitize_text_field(md5('ccpw_coins_' . serialize($args)));

        // Retrieve data from cache if available
        $results = wp_cache_get($cache_key, 'coins');

        if (false === $results) {
            if (true === $count) {
                // Count query
                $results = absint($wpdb->get_var($wpdb->prepare("SELECT COUNT({$this->primary_key}) FROM {$this->table_name} {$where};")));
            } else {
                // Data query
                $results = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT * FROM {$this->table_name} {$where} ORDER BY {$args['orderby']} {$args['order']} LIMIT %d, %d;",
                        absint($args['offset']),
                        absint($args['number'])
                    )
                );
            }
            // Cache the results
            wp_cache_set($cache_key, $results, 'coins', 3600);
        }

        return $results;
    }

    /**
     * Get coins list data.
     *
     * @param array   $args   Arguments for filtering data.
     * @param boolean $count  Whether to return count only.
     * @return mixed
     */
    public function get_coins_listdata($args = array(), $count = false)
    {
        global $wpdb;

        // Default arguments
        $defaults = array(
            'number' => 20,
            'offset' => 0,
            'coin_id' => '',
            'name' => '',
            'status' => '',
            'email' => '',
            'orderby' => 'id',
            'order' => 'ASC',
        );

        // Merge provided arguments with defaults
        $args = wp_parse_args($args, $defaults);

        // Set minimum number of results
        if ($args['number'] < 1) {
            $args['number'] = 999999999999;
        }

        $where = '';

        // Add conditions based on arguments
        if (!empty($args['id'])) {
            // Specific IDs
            $order_ids = is_array($args['id']) ? implode(',', $args['id']) : intval($args['id']);
            $where .= " WHERE `id` IN( {$order_ids} ) ";
        }

        if (!empty($args['coin_id'])) {
            // Coin IDs
            $where .= empty($where) ? ' WHERE' : ' AND';
            if (is_array($args['coin_id'])) {
                $coin_ids = array_map('esc_sql', $args['coin_id']);
                $coin_ids = implode("','", $coin_ids);
                $where .= " `coin_id` IN('{$coin_ids}') ";
            } else {
                $coin_id = esc_sql($args['coin_id']);
                $where .= " `coin_id` = '{$coin_id}' ";
            }
        }

        // Handle orderby parameter
        $allowed_columns = array_keys($this->get_columns());
        $args['orderby'] = in_array($args['orderby'], $allowed_columns) ? $args['orderby'] : $this->primary_key;
        if ($args['orderby'] === 'total' || $args['orderby'] === 'subtotal') {
            $args['orderby'] .= '+0';
        }

        // Generate cache key
        $cache_key = (true === $count) ? sanitize_text_field(md5('ccpw_coins_list_count' . serialize($args))) : sanitize_text_field(md5('ccpw_coins_list_' . serialize($args)));

        // Retrieve data from cache if available
        $results = wp_cache_get($cache_key, 'coins');

        if (false === $results) {
            if (true === $count) {
                // Count query
                $results = absint($wpdb->get_var($wpdb->prepare("SELECT COUNT({$this->primary_key}) FROM {$this->table_name} {$where};")));
            } else {
                // Data query
                $results = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT name, price, symbol, coin_id FROM {$this->table_name} {$where} ORDER BY {$args['orderby']} {$args['order']} LIMIT %d, %d;",
                        absint($args['offset']),
                        absint($args['number'])
                    )
                );
            }
            // Cache the results
            wp_cache_set($cache_key, $results, 'coins', 3600);
        }

        return $results;
    }

    /**
     * checks the list after 24 hours and update coins
     */
    public function ccpw_check_coin_list()
    {
        global $wpdb;
        $table = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", esc_sql($this->table_name)));
        $date = date('Y-m-d h:m:s', strtotime("-2 days"));

        if ($table == esc_sql($this->table_name)) {
            $wpdb->query($wpdb->prepare("DELETE FROM " . esc_sql($this->table_name) . " WHERE last_updated <= %s ", $date));
        }
    } //end ccpw_check_coin_list

    /**
     * Insert or update multiple rows into the specified table.
     *
     * @param array   $row_arrays      Array of arrays containing data to be inserted or updated.
     * @param string  $wp_table_name   Name of the WordPress table.
     * @param boolean $update          Whether to update existing rows (optional).
     * @param string  $primary_key     Primary key column name for updating (optional).
     * @return false|int               False on failure, number of rows affected on success.
     */
    public function wp_insert_rows($row_arrays, $wp_table_name, $update = false, $primary_key = null)
    {
        global $wpdb;

        // Escape table name
        $wp_table_name = esc_sql($wp_table_name);

        // Initialize variables
        $values = array();
        $place_holders = array();
        $query = '';
        $query_columns = '';

        // Build INSERT INTO query
        $query .= "INSERT INTO `{$wp_table_name}` (";

        // Get column names from the first row
        $first_row = reset($row_arrays);
        $query_columns .= '`' . implode('`, `', array_map('esc_sql', array_keys($first_row))) . '`';

        // Sanitize and prepare values and placeholders
        foreach ($row_arrays as $row_array) {
            $placeholders = array();
            foreach ($row_array as $value) {
                $values[] = $value;
                $placeholders[] = '%s'; // Default placeholder for string values
            }
            $place_holders[] = '(' . implode(', ', $placeholders) . ')';
        }

        // Complete the INSERT INTO query
        $query .= "$query_columns) VALUES ";
        $query .= implode(', ', $place_holders);

        // Add ON DUPLICATE KEY UPDATE clause if update is enabled
        if ($update) {
            $update_columns = array_map(function ($column) {
                return "`$column`=VALUES(`$column`)";
            }, array_keys($first_row));
            $updateClause = " ON DUPLICATE KEY UPDATE " . implode(', ', $update_columns);
            $query .= $updateClause;
        }

        // Prepare and execute the SQL query
        $sql = $wpdb->prepare($query, $values);

        // Execute the query and return result
        if ($wpdb->query($sql)) {
            return true; // Success
        } else {
            return false; // Failure
        }
    }

    /**
     * Return the number of results found for a given query
     *
     * @param  array $args
     * @return int
     */
    public function count($args = array())
    {
        return $this->get_coins($args, true);
    }

    /**
     * Create the table
     *
     * @access  public
     * @since   1.0
     */
    public function create_table()
    {
        global $wpdb;

        // Include WordPress upgrade file for dbDelta function
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        // Define SQL query for creating the table
        $sql = 'CREATE TABLE ' . esc_sql($this->table_name) . ' (
        `id` bigint(20) NOT NULL AUTO_INCREMENT,
        `coin_id` varchar(200) NOT NULL UNIQUE,
        `rank` int(9),
        `name` varchar(250) NOT NULL,
        `symbol` varchar(100) NOT NULL,
        `price` decimal(20,6),
        `percent_change_24h` decimal(7,4),
        `market_cap` decimal(24,2),
        `total_volume` decimal(24,2),
        `circulating_supply` varchar(250),
        `logo` varchar(250),
        `last_updated` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
        PRIMARY KEY (id)
    ) CHARACTER SET utf8 COLLATE utf8_general_ci;';

        // Execute the SQL query using dbDelta
        dbDelta($sql);

        // Update the database version option
        update_option($this->table_name . '_db_version', $this->version);
    }

    /**
     * Drop database table
     */
    public function drop_table()
    {
        global $wpdb;

        // Drop the table if it exists
        $wpdb->query('DROP TABLE IF EXISTS ' . esc_sql($this->table_name));
    }

    /**
     * Truncate database table
     */
    public function truncate_table()
    {
        global $wpdb;

        // Truncate the table (remove all rows)
        $wpdb->query('TRUNCATE TABLE ' . esc_sql($this->table_name));
    }

}
