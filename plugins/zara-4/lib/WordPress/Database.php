<?php
if ( ! class_exists( 'Zara4_WordPress_Database' ) ) {


  /**
   * Class Zara4_WordPress_Database
   */
  class Zara4_WordPress_Database {

    private $prefix;
    private $show_errors;

    /**
     * @param null $prefix
     */
    public function __construct( $prefix = null ) {
      $this->prefix = $prefix;
    }


    /**
     * Disable database errors
     */
    public function hide_errors() {
      global $wpdb;
      $this->show_errors = $wpdb->show_errors;
      $wpdb->show_errors = false;
    }


    /**
     *
     */
    public function restore_errors() {
      global $wpdb;
      $wpdb->show_errors = $this->show_errors;
    }


    /**
     * @return string
     */
    public function get_charset_collate() {
      global $wpdb;
      /** @noinspection PhpUndefinedMethodInspection */
      return $wpdb->get_charset_collate();
    }


    /**
     * @return string
     */
    public function get_prefix() {
      global $wpdb;
      return $this->prefix ? $this->prefix : $wpdb->prefix;
    }


    /**
     * @return mixed
     */
    public function get_dbname() {
      global $wpdb;
      return $wpdb->dbname;
    }


    /**
     * @param $sql
     * @param array|bool $params
     * @return mixed
     */
    public function query( $sql, $params = false ) {
      global $wpdb;
      if($params) {
        /** @noinspection PhpUndefinedMethodInspection */
        $sql = $wpdb->prepare( $sql, $params );
      }
      /** @noinspection PhpUndefinedMethodInspection */
      return $wpdb->get_results( $sql );
    }


    /**
     * @param $table
     * @param $params
     * @param null $format
     * @return mixed
     */
    public function insert( $table, $params, $format = null ) {
      global $wpdb;
      /** @noinspection PhpUndefinedMethodInspection */
      $wpdb->insert( $table, $params, $format );
      return $wpdb->insert_id;
    }


    /**
     * @param $query
     * @param $args
     * @return mixed
     */
    public function prepare($query, $args) {
      global $wpdb;
      /** @noinspection PhpUndefinedMethodInspection */
      return $wpdb->prepare( $query, $args );
    }


    /**
     * @param $table_name
     * @return bool
     */
    public function table_exists( $table_name ) {
      global $wpdb;

      $prefix = $this->get_prefix();

      /** @noinspection PhpUndefinedMethodInspection */
      $result = $wpdb->get_results("SELECT COUNT(1) hasTable FROM information_schema.tables WHERE table_schema='{$wpdb->dbname}' AND table_name='{$prefix}{$table_name}'");
      return ( isset( $result[0]->hasTable ) && $result[0]->hasTable > 0 );
    }


    /**
     * @param $query
     * @return mixed
     */
    public function get_row( $query ) {
      global $wpdb;

      /** @noinspection PhpUndefinedMethodInspection */
      return $wpdb->get_row( $query );
    }


  }

}