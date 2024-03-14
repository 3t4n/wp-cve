<?php

namespace S2WPImporter;

abstract class AbstractDB
{

    /**
     * @var string $table_name The name of our database table
     */
    public $table_name;

    /**
     * @var string $primary_key The name of the primary column
     */
    public $primary_key;

    /**
     * @var \wpdb $db The access to WordPress DB.
     */
    public $db;

    /**
     * @var string $db_prefix The prefix used for DB tables.
     */
    public $db_prefix;

    /**
     * @var int $total_results Used to store the total results number when multiple rows are selected.
     */
    protected $total_results = 0;

    /**
     * AbstractDb constructor.
     */
    public function __construct()
    {
        $this->db = $this->connect();
        $this->db_prefix = $this->db->prefix . 'shopify2wp_';
    }

    /**
     * Whitelist of columns
     *
     * @return  array
     */
    abstract public function getColumns();

    /**
     * Default column values
     *
     * @return  array
     */
    abstract public function getColumnDefaults();

    /**
     * Connection to WP DB.
     *
     * @return \wpdb
     */
    public function connect()
    {
        global $wpdb;

        /** @var \wpdb $wpdb */
        return $wpdb;
    }

    /**
     * Retrieve a row by the primary key
     *
     * @param $row_id
     *
     * @return  object|array|null
     */
    public function get($row_id)
    {
        return $this->db->get_row(
            $this->db->prepare("SELECT * FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id)
        );
    }

    /**
     * Retrieve a row by a specific column / value
     *
     * @param $column_key
     * @param $column_value
     *
     * @return  object
     */
    public function getBy($column_key, $column_value)
    {
        $column_key = esc_sql($column_key);

        return $this->db->get_row(
            $this->db->prepare("SELECT * FROM $this->table_name WHERE $column_key = %s LIMIT 1;", $column_value)
        );
    }

    /**
     * Retrieve a specific column's value by the primary key
     *
     * @param $column_key
     * @param $row_id
     *
     * @return  string
     */
    public function getColumn($column_key, $row_id)
    {
        $column_key = esc_sql($column_key);

        return $this->db->get_var(
            $this->db->prepare("SELECT $column_key FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;",
                $row_id)
        );
    }

    /**
     * Retrieve a specific column's value by the the specified column / value
     *
     * @param $column_key
     * @param $column_where
     * @param $column_value
     *
     * @return  null|string
     */
    public function getColumnBy($column_key, $column_where, $column_value)
    {
        $column_where = esc_sql($column_where);
        $column_key = esc_sql($column_key);

        return $this->db->get_var(
            $this->db->prepare("SELECT $column_key FROM $this->table_name WHERE $column_where = %s LIMIT 1;",
                $column_value)
        );
    }

    /**
     * Retrieve a row by a specific column / value
     *
     * @param     $column_key
     * @param     $column_value
     * @param int $offset
     * @param int $limit
     *
     * @return  array
     */
    public function getRowsBy($column_key, $column_value, $offset = 0, $limit = 10)
    {
        $column_key = esc_sql($column_key);

        $result = $this->db->get_results(
            $this->db->prepare(
                "SELECT SQL_CALC_FOUND_ROWS * FROM $this->table_name WHERE $column_key = %s ORDER BY date DESC LIMIT %d, %d;",
                $column_value,
                $offset,
                $limit
            )
        );

        $this->total_results = absint($this->db->get_var(
            "SELECT FOUND_ROWS();"
        ));

        return $result;
    }

    /**
     * Return the total number of rows found, using the `getRowsBy`.
     *
     * @return int
     */
    public function totalResults()
    {
        return $this->total_results;
    }

    /**
     * Insert a new row
     *
     * @param        $data
     *
     * @return int
     */
    public function insert($data)
    {
        // Set default values
        $data = wp_parse_args($data, $this->getColumnDefaults());

        // Initialise column format array
        $column_formats = $this->getColumns();

        // Force fields to lower case
        $data = array_change_key_case($data);

        // White list columns
        $data = array_intersect_key($data, $column_formats);

        // Remove the id key from array so we don't rewrite it. This is usually the id.
        unset($data[$this->primary_key]);

        // Reorder $column_formats to match the order of columns given in $data
        $data_keys = array_keys($data);
        $column_formats = array_merge(array_flip($data_keys), $column_formats);

        $this->db->insert($this->table_name, $data, $column_formats);

        return $this->db->insert_id;
    }

    /**
     * Update a row
     *
     * @param        $row_id
     * @param array  $data
     * @param string $where
     *
     * @return  bool
     */
    public function update($row_id, $data = [], $where = '')
    {
        // Row ID must be positive integer
        $row_id = absint($row_id);

        if (empty($row_id)) {
            return false;
        }

        if (empty($where)) {
            $where = $this->primary_key;
        }

        // Initialise column format array
        $column_formats = $this->getColumns();

        // Force fields to lower case
        $data = array_change_key_case($data);

        // White list columns
        $data = array_intersect_key($data, $column_formats);

        // Remove the id key from array so we don't rewrite it. This is usually the id.
        unset($data[$this->primary_key]);

        // Reorder $column_formats to match the order of columns given in $data
        $data_keys = array_keys($data);
        $column_formats = array_merge(array_flip($data_keys), $column_formats);

        if (false === $this->db->update($this->table_name, $data, [$where => $row_id], $column_formats)) {
            return false;
        }

        return true;
    }

    /**
     * Delete a row identified by the primary key
     *
     * @param int $row_id
     *
     * @return  bool
     */
    public function delete($row_id = 0)
    {
        // Row ID must be positive integer
        $row_id = absint($row_id);

        if (empty($row_id)) {
            return false;
        }

        if (false === $this->db->query($this->db->prepare("DELETE FROM $this->table_name WHERE $this->primary_key = %d", $row_id))) {
            return false;
        }

        return true;
    }

    /**
     * Check if the given table exists
     *
     * @param string $table The table name
     *
     * @return bool If the table name exists
     */
    public function customTableExists($table)
    {
        $table = sanitize_text_field($table);

        return $this->db->get_var($this->db->prepare("SHOW TABLES LIKE '%s'", $table)) === $table;
    }

    /**
     * Check if the given table exists
     *
     * @return bool If the table name exists
     */
    public function tableExists()
    {
        return $this->customTableExists($this->table_name);
    }

}
