<?php

class WDIModelGalleryBox {

  public function get_theme_row_data($id) {
    global $wpdb;
    if ($id) {
      /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . esc_sql($wpdb->prefix . WDI_THEME_TABLE) . ' WHERE id="%d"', $id)); //db call ok
    }
    else {
      /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . esc_sql($wpdb->prefix . WDI_THEME_TABLE) . ' WHERE default_theme="%d"', 1)); //db call ok
    }

    return WDILibrary::objectToArray($row);
  }

  public function get_feed_row_data($id) {
     global $wpdb;
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
     $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . ' WHERE id="%d"', $id)); //db call ok
    return WDILibrary::objectToArray($row);
  }

  public function get_option_row_data() {
    global $wpdb;
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . esc_sql($wpdb->prefix . 'wdi_option') . ' WHERE id="%d"', 1)); //db call ok
    return $row;
  }

  public function get_comment_rows_data($image_id) {
    global $wpdb;
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
    $row = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . esc_sql($wpdb->prefix . 'wdi_image_comment') . ' WHERE image_id="%d" AND published=1 ORDER BY `id` DESC', $image_id)); //db call ok
    return $row;
  }

  public function get_image_rows_data($gallery_id, $sort_by, $order_by = 'asc') {
    global $wpdb;
    if ($sort_by == 'size' || $sort_by == 'resolution') {
      $sort_by = ' CAST(t1.' . $sort_by . ' AS SIGNED) ';
    }
    elseif (($sort_by != 'alt') && ($sort_by != 'date') && ($sort_by != 'filetype') && ($sort_by != 'filename')) {
      $sort_by = 't1.`order`';
    }
    if (preg_replace('/\s+/', '', $order_by) != 'asc') {
      $order_by = 'desc';
    }
    $ip = WDILibrary::get_user_ip();
    $query_order_by = sprintf(' ORDER BY %s %s', $sort_by, $order_by);
    // $wpdb->prepare() not needed (will throw a notice) as there are no parameters (all parts are already sanitised or cast to known-safe types if not sanitised here)
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared */
    $row = $wpdb->get_results($wpdb->prepare('SELECT t1.*,t2.rate FROM ' . esc_sql($wpdb->prefix . 'wdi_image') . ' AS t1 LEFT JOIN (SELECT rate, image_id FROM ' . esc_sql($wpdb->prefix . 'wdi_image_rate') . ' WHERE ip="%s") AS t2 ON t1.id=t2.image_id WHERE t1.published=1 AND t1.gallery_id="%d" ' . $query_order_by, $ip, $gallery_id)); //db call ok

    return $row;
  }

  public function get_image_rows_data_tag($tag_id, $sort_by, $order_by = 'asc') {
    global $wpdb;
    if ($sort_by == 'size' || $sort_by == 'resolution') {
      $sort_by = ' CAST(' . $sort_by . ' AS SIGNED) ';
    }
    elseif (($sort_by != 'alt') && ($sort_by != 'date') && ($sort_by != 'filetype')) {
      $sort_by = '`order`';
    }
    if (preg_replace('/\s+/', '', $order_by) != 'asc') {
      $order_by = 'desc';
    }
    $ip = WDILibrary::get_user_ip();
    $query_order_by = sprintf(' ORDER BY %s %s', $sort_by, $order_by);
    // $wpdb->prepare() not needed (will throw a notice) as there are no parameters (all parts are already sanitised or cast to known-safe types if not sanitised here)
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared */
    $row = $wpdb->get_results($wpdb->prepare('SELECT t1.*,t2.rate FROM (SELECT image.* FROM ' . esc_sql($wpdb->prefix . 'wdi_image') . ' AS image INNER JOIN ' . $wpdb->prefix . 'wdi_image_tag AS tag ON image.id=tag.image_id WHERE image.published=1 AND tag.tag_id="%d" ' . $query_order_by . ') AS t1 LEFT JOIN (SELECT rate, image_id FROM ' . esc_sql($wpdb->prefix . 'wdi_image_rate') . ' WHERE ip="%s") AS t2 ON t1.id=t2.image_id ', $tag_id, $ip)); //db call ok
    return $row;
  }
}