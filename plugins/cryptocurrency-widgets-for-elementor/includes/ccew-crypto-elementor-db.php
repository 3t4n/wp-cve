<?php
/**
 * This file is responsible for all database realted functionality.
 */
class ccew_database
{

    public $table_name;
    public $primary_key;
    public $version;
    /**
     * Get things started
     *
     * @access  public
     * @since   1.0
     */
    public function __construct()
    {
        global $wpdb;

        $this->table_name = $wpdb->base_prefix . 'ccew_coins';
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
            'percent_change_1h' => '%f',
            'percent_change_24h' => '%f',
            'percent_change_7d' => '%f',
            'percent_change_30d' => '%f',
            'low_24h' => '%f',
            'high_24h' => '%f',
            'market_cap' => '%f',
            'total_volume' => '%f',
            'total_supply' => '%f',
            'circulating_supply' => '%d',
            'logo' => '%s',
            '7d_chart' => '%s',
            'coin_last_updated' => '%s',
            'extra_data' => '%s',

        );
    }

    public function ccew_insert($coins_data)
    {

        if (is_array($coins_data)) {

            return $this->wp_insert_rows($coins_data, esc_sql($this->table_name), true, 'coin_id');
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
            'percent_change_1h' => '',
            'percent_change_24h' => '',
            'percent_change_7d' => '',
            'percent_change_30d' => '',
            'low_24h' => '',
            'high_24h' => '',
            'market_cap' => '',
            'total_volume' => '',
            'total_supply' => '',
            'circulating_supply' => '',
            'logo' => '',
            '7d_chart' => '',
            'coin_last_update' => '',
            'extra_data' => '',
            'last_updated' => gmdate('Y-m-d H:i:s'),
        );
    }

    /**
     * Check if a coin exists by its ID
     *
     * This function checks if a coin exists in the database based on its ID.
     *
     * @param   string $coin_id The ID of the coin
     * @return  bool True if the coin exists, false otherwise
     */
    public function coin_exists_by_id($coin_id)
    {
        global $wpdb;

        // Sanitize the coin ID to prevent SQL injection
        $sanitized_coin_id = esc_sql($coin_id);
        $table_name = esc_sql($this->table_name);
        // Prepare the SQL query with sanitized input
        $query = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE coin_id = %s", $sanitized_coin_id);

        // Retrieve the count of matching records
        $count = $wpdb->get_var($query);

        // Check if a single matching record exists
        if ($count == 1) {
            // Return true if the coin exists
            return true;
        } else {
            // Return false if the coin does not exist
            return false;
        }
    }

    /**
     * Check the latest update time of a specific coin
     *
     * This function retrieves the last update time of a specific coin from the database and compares it with the current time.
     * It then calculates the time difference and returns true if the difference is less than 10 minutes, indicating that the coin data is up to date.
     * Otherwise, it returns false, indicating that the coin data needs to be updated.
     *
     * @param   string $coin_id The ID of the coin
     * @return  bool True if the coin data is up to date, false otherwise
     */
    public function check_coin_latest_update($coin_id)
    {
        global $wpdb;
        $table_name = esc_sql($this->table_name);

        // Retrieve the last update time of the coin from the database with proper escaping
        $coin_update_value = $wpdb->get_var($wpdb->prepare("SELECT coin_last_update FROM $table_name  WHERE coin_id = %s", esc_sql($coin_id)));

        // Trim the time zone information from the retrieved value
        $coin_latest_update = trim($coin_update_value, 'TZ');

        // Get the current date and time in GMT
        $currentdate = gmdate('Y-m-d H:i:s');

        // Create DateTime objects for the current time and the last update time
        $datetime1 = new DateTime($currentdate);
        $datetime2 = new DateTime($coin_update_value);

        // Calculate the time difference
        $interval = $datetime1->diff($datetime2);

        // Check if the time difference is less than 10 minutes
        if ($interval->y == 0 && $interval->m == 0 && $interval->h == 0 && $interval->i < 10) {
            // Return true if the coin data is up to date
            return true;
        } else {
            // Return false if the coin data needs to be updated
            return false;
        }
    }
    /**
     * Update coins table after every 24 hours
     *
     * This function checks the last update time of the coins table and deletes records older than 24 hours.
     * It applies WordPress-standard escaping and sanitization techniques to enhance security.
     *
     * @access public
     * @return void
     */
    public function ccew_check_coin_list()
    {
        global $wpdb;
        $table_name = esc_sql($this->table_name);
        // Get the table name with proper escaping
        $table = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));

        // Calculate the date 2 days ago with proper escaping
        $date = date('Y-m-d h:m:s', strtotime("-2 days"));

        if ($table === $table_name) {
            // Delete records older than 24 hours with proper escaping
            $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE last_updated <= %s ", $date));
        }
    } //end ccew_check_coin_list

    /**
     * Retrieve the logo of a specific coin from the database
     *
     * @access  public
     * @since   1.0
     * @param   string|null $coin_id The ID of the coin
     * @return  string|null The logo URL of the coin, or null if not found
     * @throws  Exception If $coin_id is not provided
     */
    public function get_coin_logo($coin_id = null)
    {
        if (!empty($coin_id)) {
            global $wpdb;
            // Sanitize the input to prevent SQL injection
            $coin_id = esc_sql($coin_id);
            $logo = $wpdb->get_var($wpdb->prepare('SELECT logo FROM ' . esc_sql($this->table_name) . ' WHERE coin_id=%s', $coin_id));
            // Escape the output to prevent XSS
            $logo = esc_url($logo);
            return $logo;
        } else {
            // Throw an exception if $coin_id is not provided
            throw new Exception("One argument 'coin_id' expected. Null given.");
        }
    }

    /**
     * Retrieve orders from the database
     *
     * @access  public
     * @since   1.0
     * @param   array $args
     * @param   bool  $count  Return only the total number of results found (optional)
     * @return  array|int      An array of coin data or the count of results based on the $count parameter
     */
    public function get_coins($args = array(), $count = false)
    {
        global $wpdb;
        $table_name = esc_sql($this->table_name);
        // Set default arguments
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

        // Merge the provided arguments with the default arguments
        $args = wp_parse_args($args, $defaults);

        // Ensure the 'number' argument is at least 1
        if ($args['number'] < 1) {
            $args['number'] = 1000;
        }

        $where = '';

        // Specific referrals
        if (!empty($args['id'])) {
            // Sanitize the input to prevent SQL injection
            $order_ids = implode(',', array_map('absint', (array) $args['id']));
            $where .= "WHERE `id` IN( {$order_ids} ) ";
        }

        if (!empty($args['coin_id'])) {
            // Sanitize the input to prevent SQL injection
            $coin_ids = array_map('esc_sql', (array) $args['coin_id']);
            $coin_ids = "'" . implode("','", $coin_ids) . "'";

            if (empty($where)) {
                $where .= ' WHERE';
            } else {
                $where .= ' AND';
            }

            $where .= " `coin_id` IN($coin_ids) ";
        }

        // Validate and sanitize the 'orderby' argument
        $args['orderby'] = !array_key_exists($args['orderby'], $this->get_columns()) ? esc_sql($this->primary_key) : esc_sql($args['orderby']);

        // Sanitize the 'orderby' argument to prevent SQL injection
        if ('total' === $args['orderby'] || 'subtotal' === $args['orderby']) {
            $args['orderby'] = esc_sql($args['orderby']) . '+0';
        }

        // Generate a unique cache key based on the arguments
        $cache_key = (true === $count) ? md5('ccew_coins_count' . wp_json_encode($args)) : md5('ccew_coins_' . wp_json_encode($args));

        // Retrieve the results from the cache
        $results = wp_cache_get($cache_key, 'coins');

        if (false === $results) {
            if (true === $count) {
                // Retrieve the count of results from the database
                $results = absint($wpdb->get_var($wpdb->prepare("SELECT COUNT(%s) FROM $table_name  {$where};", $this->primary_key)));
            } else {
                // Retrieve the results from the database
                $results = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT * FROM $table_name  {$where} ORDER BY {$args['orderby']} {$args['order']} LIMIT %d, %d;",
                        absint($args['offset']),
                        absint($args['number'])
                    )
                );
            }
            // Store the results in the cache for 1 hour
            wp_cache_set($cache_key, $results, 'coins', 3600);
        }

        // Return the results
        return $results;
    }

    /**
     * Insert or update rows in the WordPress database table with proper escaping and sanitization.
     *
     * @param array   $row_arrays   Array of rows to be inserted or updated.
     * @param string  $wp_table_name Name of the WordPress table.
     * @param boolean $update       Optional. Whether to update the row if it already exists. Default false.
     * @param string  $primary_key  Optional. The primary key of the table. Default null.
     * @return false|int             False on failure, the number of rows affected on success.
     */
    public function wp_insert_rows($row_arrays, $wp_table_name, $update = false, $primary_key = null)
    {
        global $wpdb;

        // Sanitize the table name to prevent SQL injection
        $wp_table_name = esc_sql($wp_table_name);

        // Initialize arrays for values and placeholders
        $values = array();
        $place_holders = array();
        $query = '';
        $query_columns = '';

        // Define an array of columns with float data type
        $floatCols = array('price', 'low_24h', 'high_24h', 'percent_change_1h', 'percent_change_24h', 'percent_change_7d', 'percent_change_30d', 'market_cap', 'total_volume', 'total_supply', 'circulating_supply');

        // Construct the INSERT query
        $query .= "INSERT INTO `{$wp_table_name}` (";
        foreach ($row_arrays as $count => $row_array) {
            foreach ($row_array as $key => $value) {
                if ($count == 0) {
                    if ($query_columns) {
                        $query_columns .= ', `' . $key . '`';
                    } else {
                        $query_columns .= '`' . $key . '`';
                    }
                }

                // Add the value to the array
                $values[] = $value;

                // Determine the placeholder symbol based on the value type
                $symbol = '%s';
                if (is_numeric($value)) {
                    $symbol = '%d';
                }
                if (in_array($key, $floatCols)) {
                    $symbol = '%f';
                }

                // Prepare the placeholder for the value
                if (isset($place_holders[$count])) {
                    $place_holders[$count] .= ", '$symbol'";
                } else {
                    $place_holders[$count] = "( '$symbol'";
                }
            }
            // Close the placeholder
            $place_holders[$count] .= ')';
        }

        // Complete the INSERT query
        $query .= " $query_columns ) VALUES ";
        $query .= implode(', ', $place_holders);

        // Add ON DUPLICATE KEY UPDATE clause if update is true
        if ($update) {
            $update = " ON DUPLICATE KEY UPDATE `$primary_key`=VALUES( `$primary_key` ),";
            $cnt = 0;
            foreach ($row_arrays[0] as $key => $value) {
                if ($cnt == 0) {
                    $update .= "`$key`=VALUES(`$key`)";
                    $cnt = 1;
                } else {
                    $update .= ", `$key`=VALUES(`$key`)";
                }
            }
            $query .= $update;
        }

        // Prepare and execute the SQL query with proper escaping
        $sql = $wpdb->prepare($query, $values);

        // Execute the query and return the result
        if ($wpdb->query($sql)) {
            return true; // Return true on successful query execution
        } else {
            return false; // Return false on query execution failure
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

        // Include the necessary file for dbDelta function
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        // Define the SQL query for creating the table with proper escaping and sanitization
        $sql = "CREATE TABLE " . esc_sql($this->table_name) . " (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `coin_id` varchar(200) NOT NULL UNIQUE,
            `rank` int(9),
            `name` varchar(250) NOT NULL,
            `symbol` varchar(100) NOT NULL,
            `price` decimal(20,8),
            `percent_change_1h` decimal(7,4) ,
            `percent_change_24h` decimal(7,4) ,
            `percent_change_7d` decimal(7,4) ,
            `percent_change_30d` decimal(7,4) ,
            `low_24h` decimal(20,8),
            `high_24h` decimal(20,8),
            `market_cap` decimal(24,2),
            `total_volume` decimal(24,2) ,
            `total_supply` decimal(24,2) ,
            `circulating_supply` varchar(250),
            `logo` varchar(250),
            `7d_chart` longtext,
            `coin_last_update` varchar(250),
            `extra_data` varchar(250),
            `last_updated` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
            PRIMARY KEY (id)
        ) CHARACTER SET utf8 COLLATE utf8_general_ci;";

        // Execute the dbDelta function to create or update the table
        dbDelta($sql);

        // Update the database version option
        update_option(esc_sql($this->table_name) . '_db_version', esc_sql($this->version));
    }

    /**
     * Drop database table
     *
     * @access  public
     * @since   1.0
     */
    public function drop_table()
    {
        global $wpdb;

        // Drop the table with proper escaping and sanitization
        $wpdb->query('DROP TABLE IF EXISTS ' . esc_sql($this->table_name));

    }

    /**
     * Truncate database table
     *
     * @access  public
     * @since   1.0
     */
    public function truncate_table()
    {
        global $wpdb;

        // Truncate the table with proper escaping and sanitization
        $wpdb->query('TRUNCATE TABLE ' . esc_sql($this->table_name));

    }
}
