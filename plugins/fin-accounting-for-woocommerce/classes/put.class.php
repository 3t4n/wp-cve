<?php
/**
 * Class to handle database INSERT, UPDATE, DELETE operations
 * Determines data formats before executing the query
 *
 * @link              https://finpose.com
 * @since             1.0.0
 * @package           Finpose
 * @author            info@finpose.com
 */
if ( !class_exists( 'fin_put' ) ) {
  class fin_put {

    public $db;
    public $errmsg;

    public $floats = array('amount', 'tr');
    public $integers = array('timecr', 'datepaid', 'datetransfer');
    /**
	 * Constructor for PUT operations
	 */
    function __construct($db) {
    global $wpdb;
      $this->db = $wpdb;
    }

    /**
	 * Insert into DB, with type formatting
	 */
    function insert($t, $p) {
      $formats = array();
      foreach ($p as $k=>$v) {
        $f = '%s';
        if(in_array($k, $this->floats)) {
          $f = '%f';
        }
        if(in_array($k, $this->integers)) {
          $f = '%d';
        }
        $formats[]=$f;
      }
      $ins = $this->db->insert($t, $p, $formats);
      $this->errmsg = $this->db->last_error;
    return $ins;
    }

    /**
	 * Update DB with type formatting
	 */
    function update($t, $u, $w) {
      $formats = array();
      foreach ($u as $k=>$v) {
        $f = '%s';
        if(in_array($k, $this->floats)) {
          $f = '%f';
        }
        if(in_array($k, $this->integers)) {
          $f = '%d';
        }
        $formats[]=$f;
      }
      $upd = $this->db->update($t, $u, $w, $formats);
      $this->errmsg = $this->db->last_error;
      return $upd;
    }

    /**
	 * Delete From DB, with type formatting
	 */
    function delete($t, $w) {
      $where_format = array();
      foreach ($w as $k=>$v) {
        $f = '%s';
        if(in_array($k, $this->floats)) {
          $f = '%f';
        }
        if(in_array($k, $this->integers)) {
          $f = '%d';
        }
        $where_format[]=$f;
      }
    return $this->db->delete($t, $w, $where_format);
    }

  }
}
