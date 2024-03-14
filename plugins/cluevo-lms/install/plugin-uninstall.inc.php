<?php
function cluevo_plugin_uninstall() {
  $curOpt = get_option("cluevo-delete-data-on-uninstall", "");
  if ($curOpt === "on") {
    global $wpdb;

    $optTable = $wpdb->prefix . "options";
    $optionsResults = $wpdb->get_results("SELECT option_name FROM $optTable WHERE option_name LIKE 'cluevo%'", ARRAY_N);

    if (!empty($optionsResults)) {
      foreach ($optionsResults as $result) {
        $option = $result[0];
        delete_option($option);
      }
    }

    $cluevo_tmp_upload_base_dir = wp_upload_dir();
    $dir = $cluevo_tmp_upload_base_dir["basedir"] . "/cluevo/";
    if (file_exists($dir)) {
      cluevo_delete_directory($dir);
    }

    $table = $wpdb->prefix . 'posts';
    $sqlDeletePosts = "SELECT id FROM wp_posts WHERE id IN (SELECT id FROM $table WHERE post_type LIKE 'cluevo-%') OR post_parent IN (SELECT id FROM $table WHERE post_type LIKE 'cluevo-%')";
    $delResult = $wpdb->get_results($sqlDeletePosts, ARRAY_N);
    if (!empty($delResult)) {
      foreach ($delResult as $post) {
        wp_delete_post($post[0], true);
      }
    }

    $sqlDeleteTerms = "SELECT t.name, t.term_id, tt.taxonomy
      FROM $wpdb->terms AS t
      INNER JOIN $wpdb->term_taxonomy AS tt
      ON t.term_id = tt.term_id
      WHERE tt.taxonomy = '" . CLUEVO_TAXONOMY . "'";

    $terms = $wpdb->get_results($sqlDeleteTerms);

    if (!empty($terms)) {
      foreach ($terms as $t) {
        $term = get_term($t->term_id, $t->taxonomy);
        if (!is_wp_error($term) && !empty($term)) {
          wp_delete_term( $t->term_id, $t->taxonomy);
        }
      }
    }

    $tableResults = $wpdb->get_results("SHOW TABLES LIKE '" . $wpdb->prefix . "cluevo_%'", ARRAY_N);

    if (!empty($tableResults)) {
      foreach ($tableResults as $result) {
        $table = $result[0];
        $wpdb->query("DROP TABLE IF EXISTS $table");
      }
    }
  }
}
?>
