<?php
if (!defined("CLUEVO_ACTIVE")) exit;

/**
 * Creates a metadata post for the given item
 *
 * Takes an optional parent post id to make the new post a child of
 *
 * @param CluevoItem $item
 * @param int $parentPostId (optional)
 */
function cluevo_create_metadata_page($item, $parentPostId = 0) {
  $meta = [ CLUEVO_METADATA_KEY => $item->item_id, CLUEVO_META_TREE_ITEM_ID => $item->item_id, CLUEVO_META_TREE_ITEM_KEY => $item ];
  foreach ($item as $key => $value) {
    $meta[CLUEVO_META_DATA_PREFIX . $key] = $value;
  }

  $id = wp_insert_post([
    'post_title' => sanitize_title($item->name),
    'post_type' => CLUEVO_METADATA_POST_TYPE,
    'post_status' => 'publish',
    'meta_input' => $meta,
    'post_parent' => (int)$parentPostId
  ]);
  $terms = get_terms([ 'taxonomy' => CLUEVO_TAXONOMY, 'hide_empty' => false ]);
  $types = cluevo_get_conf_const('CLUEVO_TYPES_TO_TERMS');
  if (is_array($terms)) {
    foreach($terms as $term) {
      if (array_key_exists($item->type, $types)) {
        if ($term->name == $types[$item->type]) {
          wp_set_post_terms($item->metadata_id, [$term->term_id], CLUEVO_TAXONOMY);
          break;
        }
      }
    }
  }
  return $id;
}

/**
 * Deletes a metadata post by metadata key id
 *
 * Queries posts for the given meta key and removes the post
 *
 * @param int $id
 */
function cluevo_remove_metadata_page($id) {
  $query = new WP_Query( [ 'post_type' => CLUEVO_METADATA_POST_TYPE, 'meta_key' => CLUEVO_META_TREE_ITEM_ID, 'meta_value' => $id ]);
  if ($query->have_posts()) {
    foreach ($query->posts as $post) {
      wp_delete_post($post->ID, true);
    }
  }
}

/**
 * Checks whether a metadata post for the given item id exists
 *
 * @param int $intTreeId
 * @param string $strKey (optional)
 *
 * @return bool
 */
function cluevo_metadata_page_exists($intTreeId, $strKey = CLUEVO_META_TREE_ITEM_ID, $strPostType = CLUEVO_METADATA_POST_TYPE) {
  $query = new WP_Query( [ 'post_type' => $strPostType , 'meta_key' => $strKey, 'meta_value' => $intTreeId ]);

  if ($query->have_posts()) {
    return $query->posts[0]->ID;
  }

  return false;
}

/**
 * Updates the metadata post of a given item
 *
 * Updates the meta fields, parent post and title of the given item
 *
 * @param mixed $item
 * @param mixed $parentPostId
 *
 * @return int|WP_Error
 */
function cluevo_update_metadata_page($item, $parentPostId = null) {
  if (empty($item->metadata_id)) return;
  $meta = [
    CLUEVO_METADATA_KEY => (int)$item->item_id,
    CLUEVO_META_TREE_ITEM_ID => (int)$item->item_id,
  ];

  $slug = sanitize_title($item->name);

  if (!empty($item->settings)) {
    $meta[CLUEVO_META_DATA_PREFIX . "settings"] = cluevo_sanitize_meta($item->settings);
  }

  $status = ($item->published) ? 'publish' : 'draft';

  remove_action("save_post_" . CLUEVO_METADATA_POST_TYPE, "cluevo_add_change_date_to_post", 10);

  $post = [
    "ID" => $item->metadata_id,
    "meta_input" => $meta,
    "post_parent" => $parentPostId,
    "post_name" => $slug,
    "post_title" => sanitize_text_field($item->name),
    "post_status" => $status
  ];

  $result = wp_update_post($post);

  $terms = get_terms(['taxonomy' => CLUEVO_TAXONOMY, 'hide_empty' => false]);
  $types = cluevo_get_conf_const('CLUEVO_TYPES_TO_TERMS');
  if (is_array($terms)) {
    foreach($terms as $term) {
      if (array_key_exists($item->type, $types)) {
        if ($term->name == $types[$item->type]) {
          wp_set_post_terms($item->metadata_id, [$term->term_id], CLUEVO_TAXONOMY);
          break;
        }
      }
    }
  }

  add_action("save_post_" . CLUEVO_METADATA_POST_TYPE, "cluevo_add_change_date_to_post", 10, 3);

  return $result;
}

function cluevo_sanitize_meta($meta) {
  if (empty($meta)) return;
  if (is_array($meta)) {
    $clean = [];
    foreach ($meta as $key => $value) {
      $cKey = sanitize_key($key);
      if (!empty($value)) {
        $cValue = null;
        if (is_array($value)) {
          $cValue = cluevo_sanitize_meta($value);
        } else {
          $cValue = sanitize_textarea_field($value);
        }
        $clean[$cKey] = $cValue;
      }
    }
    return $clean;
  } else {
    return sanitize_textarea_field($meta);
  }
}

/**
 * Returns the metadata page id for the given item id
 *
 * @param int $intItemId
 *
 * @return WP_Post|null
 */
function cluevo_get_metadata_page($intItemId) {
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $sql = "SELECT metadata_id FROM $table WHERE item_id = %d";

  $id = $wpdb->get_var($wpdb->prepare($sql, [ $intItemId ]));

  return get_post($id);
}

/**
 * Returns the metadata post for a given module id
 *
 * @param int $intModuleId
 *
 * @return WP_Post|null
 */
function cluevo_get_module_metadata_page($intModuleId) {
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
  $sql = "SELECT metadata_id FROM $table WHERE module_id = %d";

  $id = $wpdb->get_var($wpdb->prepare($sql, [ $intModuleId ]));

  return get_post($id);
}

/**
 * Returns the 'edit page' link for a metadata post
 *
 * @param mixed $strKey
 * @param string $type (optional) Can be either item or module, defaults to "item"
 *
 * @return string|null
 */
function cluevo_get_metadata_page_link($strKey, $type = "item") {
  if ($type == "module")
    $post = cluevo_get_module_metadata_page($strKey);
  else
    $post = cluevo_get_metadata_page($strKey);

  if (!empty($post)) {
    return get_edit_post_link($post->ID);
  }

  return null;
}

/**
 * Returns the permalink for a metadata post
 *
 * @param mixed $strKey
 * @param string $type (optional) Can be either item or module, defaults to "item"
 *
 * @return string|null
 */
function cluevo_get_metadata_permalink($strKey, $type = "item") {
  if ($type == "module")
    $post = cluevo_get_module_metadata_page($strKey);
  else
    $post = cluevo_get_metadata_id_from_item_id($strKey);

  if (!empty($post)) {
    if (is_object($post)) {
      return get_permalink($post->ID);
    } else {
      return get_permalink($post);
    }
  }

  return null;
}

/**
 * Cleans up orphaned metadata posts
 *
 * Searches for metadata posts that are not referenced in any tree item
 * and deletes them
 *
 * @return void
 */
function cluevo_cleanup_metadata_posts() {
  global $wpdb;
  $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
  $sql = "SELECT p.id, t.metadata_id FROM " . $wpdb->prefix . "posts p
    LEFT JOIN $treeTable t ON t.metadata_id = p.ID
    LEFT JOIN $moduleTable m ON m.metadata_id = p.ID
    WHERE p.post_type = %s AND COALESCE(t.metadata_id, m.metadata_id) IS NULL";
  $rows = $wpdb->get_results(
    $wpdb->prepare($sql, [ CLUEVO_METADATA_POST_TYPE ]),
    ARRAY_A);
  if (!empty($rows)) {
    foreach ($rows as $row) {
      $itemId = cluevo_get_item_id_from_metadata_id($row["id"]);
      if (empty($itemId)) {
        wp_delete_post($row["id"], true);
      }
    }
  }
}
?>
