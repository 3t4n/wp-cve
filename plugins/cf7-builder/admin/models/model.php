<?php
class Model_cf7b {

  /**
   * Get form content from meta
   *
   * @param int $post_id
   *
   * @return string
   */
  public function cf7b_get_form_template( $post_id ) {
    $content = get_post_meta( $post_id, '_form', true );
    return $content;
  }

  /**
   * Update created preview post content
   *
   * @param int $post_id
   * @param string $content
   *
   * @return bool
   */
  public function cf7b_update_preview_post( $post_id, $content ) {

    global $wpdb;
    $content = wp_specialchars_decode($content, ENT_QUOTES);
    $saved = $wpdb->update( $wpdb->prefix . 'posts', array('post_content' => $content), array( 'id' => $post_id ), array('%s'), array('%d') );
    update_post_meta( $post_id, '_form', $content);
/*
    $cf7b_form_settings = get_option('cf7b_form_settings');
    if ( empty($cf7b_form_settings) ) {
      $data = array('form_'.$post_id => array('theme'=>$cf7b_active_theme));
      update_option('cf7b_form_settings', $data, 1);
    } else {
      var_dump($cf7b_active_theme); die();
      $cf7b_form_settings['form_'.$post_id] = $cf7b_active_theme;
      update_option('cf7b_form_settings', $cf7b_form_settings);
    }*/
    return $saved;
  }

  /**
   * Get all revisions data by post_id
   *
   * @param int $post_id
   *
   * @return array
   */
  public function cf7b_get_revisions( $post_id ) {
    global $wpdb;
    $row = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'cf7b_builder WHERE post_id="%d" ORDER BY id desc', $post_id ), ARRAY_A );
    return $row;
  }

  /**
   * Add revision to DB
   *
   * @param array $params ('post_id','template'))
   *
   * @return array
   */
    public function cf7b_set_revision( $params ) {
    global $wpdb;
    $content = $this->cf7b_get_form_data( $params['post_id'] );
    $insert_data = array(
      'post_id' => $params['post_id'],
      'content' => $content,
      'template' => $params['template'],
      'modified_date' => time(),
      'active' => 1
    );
    $format = array(
      '%d',
      '%s',
      '%s',
      '%d',
      '%d'
    );

    $saved = $wpdb->insert( $wpdb->prefix . 'cf7b_builder', $insert_data, $format );
    if ( $saved ) {
      $id = $wpdb->insert_id;
      $post_id = $params['post_id'];
      $wpdb->query( $wpdb->prepare('UPDATE '.$wpdb->prefix . 'cf7b_builder SET active=0 WHERE post_id="%d" && id <> "%d"', $post_id, $id) );
    }
  }

  /**
   * Get revision data by id
   *
   * @param int $id revision id in cfb db
   *
   * @return array
   */
  public function cf7b_get_revision_by_id( $id ) {
    global $wpdb;
    $row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'cf7b_builder WHERE id="%d"', $id ), ARRAY_A );
    return $row;
  }

  /**
   * Check if form has revision
   *
   * @param int $post_id cf7 form id in posts
   *
   * @return bool
   */
  public function cf7b_check_revision( $post_id ) {
    global $wpdb;
    $rowcount = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "cf7b_builder WHERE post_id=".$post_id);
    if ( $rowcount ) {
      return true;
    }
    return false;
  }

  /**
   * Get post content
   *
   * @param int $post_id
   *
   * @return string
  */
  public function cf7b_get_form_data( $post_id ) {
    global $wpdb;
    $post_content = get_post($post_id);
    $content = $post_content->post_content;
    return $content;
  }

  public function get_preview_permalink() {
    return get_option('cf7b_preview_permalink');
  }

}

