<?php

class ModelTabSettings_cf7b {

  /**
  * Get default theme id
   */
  public function get_default_theme_id() {
    global $wpdb;
    $def_id = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix ."cf7b_themes WHERE def = 1");
    return $def_id;
  }

  public function get_all_themes() {
    global $wpdb;
    $themes = $wpdb->get_results("SELECT id, title, def FROM ".$wpdb->prefix ."cf7b_themes", ARRAY_A);
    return $themes;
  }
}