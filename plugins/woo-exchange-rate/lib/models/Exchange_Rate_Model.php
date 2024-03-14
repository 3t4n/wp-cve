<?php

namespace WOOER;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Singleton
 * 
 * Wrapper for SQL queries
 */
class Exchange_Rate_Model {

    private static $instance = null;
    private $table_name;
    private $db;

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . "woocommerce_exchange_rate";
        $this->db = $wpdb;
    }

    private function __clone() {
        //clear
    }

    public function get_table_name() {
        return $this->table_name;
    }

    public function get_exchange_rate_by_code($code = '') {
        if (!$code) {
            throw new Exception('Currency code is not present!');
        }

       return $this->db->get_var($this->db->prepare("
			SELECT currency_exchange_rate FROM {$this->table_name} WHERE currency_code LIKE %s
		", $code));
    }
    
    public function get_currency_pos_by_code($code = '') {
        if (!$code) {
            throw new Exception('Currency code is not present!');
        }

       return $this->db->get_var($this->db->prepare("
			SELECT currency_pos FROM {$this->table_name} WHERE currency_code LIKE %s
		", $code));
    }

    public function get_count() {
        return $this->db->get_var("SELECT COUNT(id) FROM {$this->table_name};");
    }

    /**
     * For CRUD
     * @param int $id
     * @return array
     */
    public function get_data_by_id($id = 0) {
        $empty = array(
            'id' => 0,
            'currency_code' => '',
            'currency_pos' => '',
            'currency_exchange_rate' => '',
        );

        if (!$id) {
            return $empty;
        }

        $row = $this->db->get_row($this->db->prepare("
			SELECT * FROM {$this->table_name} WHERE id = %d
		", $id), ARRAY_A);

        if (is_null($row)) {
            return $empty;
        }

        return $row;
    }

    /**
     * Custom select query
     * @param array $fields
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @return array | bool
     */
    public function select($fields, $order = 'ASC', $limit = null, $offset = null) {
        if (!$fields) {
            $fields = array('*');
        }
        $sql = "SELECT " . implode(',', $fields).  " FROM {$this->table_name} ORDER BY id {$order}";
        if (isset($limit, $offset)) {
            $sql .= " LIMIT %d OFFSET %d";
            $sql = $this->db->prepare($sql, $limit, $offset);
        }
        return $this->db->get_results($sql, ARRAY_A);
    }

    /**
     * Store data in database
     * Returns TRUE on success, FALSE on fail and 0 if no rows were updated
     * @param type $data
     * @return int | bool
     */
    public function save($data) {
        $id = $data['id'];
        //id - is auto inrement primary key field does not update/insert
        unset($data['id']);
        return isset($id) 
            ? $this->db->update($this->table_name, $data, array('id' => $id), array('%s', '%s', '%f'), array('%d'))
            : $this->db->insert($this->table_name, $data, array('%s', '%s', '%f'));
    }

    public function delete($id) {
        return $this->db->delete($this->table_name, array('id' => $id), array('%d'));
    }

}
//@todo: looks bad
