<?php

class WDIModelEditorShortcode {

  public function __construct() {
  }

  public function get_row_data() {
    global $wpdb;
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
    $row = $wpdb->get_results( $wpdb->prepare("SELECT id, feed_name, feed_thumb FROM " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " WHERE published = '%d' ORDER BY feed_name ASC", 1) ); //db call ok
    return $row;
  }

  public function get_first_feed_id(){
    global $wpdb;
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
    $min_id = $wpdb->get_var( $wpdb->prepare("SELECT MIN(id) FROM " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) ." WHERE published = '%d'", 1) ); //db call ok
    return $min_id;
  }
}