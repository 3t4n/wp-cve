<?php

class WDIModelWidget {
  
  public function __construct() {
  }
  
  public function get_feeds() {
    global $wpdb;
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
    $rows = $wpdb->get_results( $wpdb->prepare("SELECT id, feed_name, feed_type FROM " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " WHERE published='%d'", 1) ); //db call ok
    return $rows;
  }
}