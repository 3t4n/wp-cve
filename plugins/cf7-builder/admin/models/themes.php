<?php
class ModelThemes_cf7b {

  public function save_theme( $id, $title, $options ) {
    global $wpdb;
    $data = array(
      'title' => $title,
      'options' => json_encode($options)
    );
    if ( !$id ) {
        $wpdb->insert($wpdb->prefix ."cf7b_themes", $data);
        $id = $wpdb->insert_id;
    } else {
        $wpdb->update($wpdb->prefix ."cf7b_themes", $data, array('id' => intval($id)));
    }
    return $id;
  }

  public function get_theme_data($id) {
    global $wpdb;
    $result = $wpdb->get_row($wpdb->prepare("SELECT title, options FROM ".$wpdb->prefix ."cf7b_themes WHERE id=%d",$id), ARRAY_A);
    if ( $result ) {
      return $result;
    }
    return array();
  }

  public function get_themes_list() {
    global $wpdb;
    $result = $wpdb->get_results("SELECT id, title, def FROM ".$wpdb->prefix ."cf7b_themes", ARRAY_A);
    return $result;
  }

  public function delete($id) {
    global $wpdb;
    $delete = $wpdb->delete($wpdb->prefix ."cf7b_themes", array('id' => $id), array('%d'));
    return $delete;
  }

  public function setdefault($id) {
    global $wpdb;
    $wpdb->update($wpdb->prefix ."cf7b_themes", array( 'def' => 0 ), array( 'def' => 1 ));
    $save = $wpdb->update($wpdb->prefix ."cf7b_themes", array( 'def' => 1 ), array( 'id' => $id ));
    return $save;
  }

  public function get_duplicated_row( $id ) {
    global $wpdb;
    return $wpdb->get_row($wpdb->prepare("SELECT title,options FROM " . $wpdb->prefix ."cf7b_themes WHERE id = %d", $id), ARRAY_A);
  }

  public function insert_theme_to_db( $row ) {
    global $wpdb;
    return $wpdb->insert($wpdb->prefix ."cf7b_themes", $row);
  }


}
