<?php

class ModelSubmissions_cf7b {

  public function save_submission( $data ) {
    global $wpdb;
    $format = array('%d', '%s', '%s', '%s', '%s');
    $wpdb->insert($wpdb->prefix ."cf7b_submissions", $data, $format );
  }

  public function get_list_data( $orderby, $order, $search ) {
    global $wpdb;

    $query = new WP_Query(array(
                            'post_type' => 'wpcf7_contact_form',
                            'post_status' => 'publish',
                            'posts_per_page' => -1,
                            'orderby'   => $orderby,
                            'order' => $order,
                            's' => $search,
                          ));


    $data = array();
    $ind = 0;
    while ($query->have_posts()) {
      $query->the_post();
      $data[$ind]['form_id'] = get_the_ID();
      $data[$ind]['title'] = get_the_title();

      $count = $wpdb->get_var("SELECT count(id) FROM ". $wpdb->prefix ."cf7b_submissions WHERE form_id = " . $data[$ind]['form_id']);
      $data[$ind]['count'] = $count;

      $ind++;
    }
    wp_reset_query();
    return $data;
  }

  public function getSubmissions( $id ) {
    global $wpdb;
    $data['title'] = get_the_title($id);
    $data['submissions'] = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix ."cf7b_submissions WHERE form_id = %d ORDER BY id desc", $id ), ARRAY_A );
    return $data;
  }

  public function remove_submission($id) {
    global $wpdb;
    $delete = $wpdb->query( $wpdb->prepare("DELETE FROM " . $wpdb->prefix ."cf7b_submissions WHERE id = %d",$id) );
  }

  public function remove_submissions($form_id) {
    global $wpdb;
    $delete = $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix ."cf7b_submissions WHERE form_id = %d",$form_id));
  }
}