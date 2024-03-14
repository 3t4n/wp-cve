<?php

class PGIModelImport_pgi {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
 
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////

  function message($message, $type) {
	return '<div style="width:99%"><div class="' . $type . '"><p><strong>' . $message . '</strong></p></div></div>';
  }
  
  public function get_results($table_name, $col_name = "", $id = "") {
    global $wpdb;
    if (!$col_name && !$id) {
      $row = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . $table_name);
    }
    else {
      $row = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . $table_name . ' WHERE ' . $col_name . ' ="' .$id .'"');
    }
    return $row;
  }

  public function pgi_get_unique_slug($table_name, $slug, $id) {
    global $wpdb;
    $slug = sanitize_title($slug);
    if ($id != 0) {
      $query = $wpdb->prepare("SELECT slug FROM " . $wpdb->prefix . $table_name. " WHERE slug = %s AND id != %d", $slug, $id);
    }
    if ($wpdb->get_var($query)) {
      $num = 2;
      do {
        $alt_slug = $slug . "-$num";
        $num++;
        $slug_check = $wpdb->get_var($wpdb->prepare("SELECT slug FROM " . $wpdb->prefix . $table_name. " WHERE slug = %s", $alt_slug));
      } while ($slug_check);
      $slug = $alt_slug;
    }
    return $slug;
  }

  public function pgi_get_unique_name($table_name, $name, $id) {
    global $wpdb;
    if ($id != 0) {
      $query = $wpdb->prepare("SELECT name FROM " . $wpdb->prefix . $table_name. " WHERE name = %s AND id != %d", $name, $id);
    }
    if ($wpdb->get_var($query)) {
      $num = 2;
      do {
        $alt_name = $name . "-$num";
        $num++;
        $slug_check = $wpdb->get_var($wpdb->prepare("SELECT name FROM " . $wpdb->prefix . $table_name. " WHERE name = %s", $alt_name));
      } while ($slug_check);
      $name = $alt_name;
    }
    return $name;
  }

  public function album_id($id) {
    global $nggdb;
    global $wpdb;
    $id = substr($id,1);
    $albums = $nggdb->find_album($id);
    $album = $albums->gallery_ids;
    foreach($album as $new_album_id) {
      if (ctype_digit($new_album_id)) {
        return $new_album_id;
      }
      else {
	      return $this->album_id($new_album_id);
	    }
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}