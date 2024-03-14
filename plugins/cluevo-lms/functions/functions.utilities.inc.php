<?php
if (!defined("CLUEVO_ACTIVE")) exit;

function cluevo_get_cluevo_lms()
{
  if (!empty($GLOBALS["cluevo"])) return $GLOBALS["cluevo"];
  global $post;
  //echo "<!-- init lms class -->\n";
  $userId = get_current_user_id();
  $itemId = null;
  if (!empty($post)) {
    $itemId = cluevo_get_item_id_from_metadata_id($post->ID);
  }
  $lms = new Cluevo($itemId, $userId);
  $GLOBALS['cluevo'] = $lms;
  return $lms;
  //echo "<!-- init time: $time -->\n";
}

function cluevo_get_the_lms_tree()
{
  global $cluevo_tree;
  return $cluevo_tree;
}

function cluevo_get_the_lms_user()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->user;
}

function cluevo_get_the_lms_user_level()
{
  $user = cluevo_get_the_lms_user();
  if (!empty($user->level["current"])) {
    return $user->level["current"];
  } else {
    return 0;
  }
}

function cluevo_get_the_lms_user_points()
{
  $user = cluevo_get_the_lms_user();

  if (!empty($user->total_points)) {
    return $user->total_points;
  } else {
    return 0;
  }
}

function cluevo_get_the_lms_user_exp()
{
  $user = cluevo_get_the_lms_user();

  if (!empty($user->level['exp'])) {
    return $user->level['exp'];
  } else {
    return 0;
  }
}

function cluevo_get_the_lms_user_exp_next()
{
  $user = cluevo_get_the_lms_user();

  if (!empty($user->level['next'])) {
    return $user->level['next'];
  } else {
    return 0;
  }
}

function cluevo_get_the_lms_user_exp_remaining()
{
  $user = cluevo_get_the_lms_user();

  if (!empty($user->level['remaining'])) {
    return $user->level['remaining'];
  } else {
    return 0;
  }
}

function cluevo_get_the_lms_user_exp_pct()
{
  $user = cluevo_get_the_lms_user();

  if (!empty($user->level['pct'])) {
    return $user->level['pct'];
  } else {
    return 0;
  }
}

function cluevo_get_the_lms_user_title()
{
  $user = cluevo_get_the_lms_user();

  if (!empty($user->level['title'])) {
    return $user->level['title'];
  } else {
    return __("Guest", "cluevo");
  }
}

function cluevo_the_lms_user_level()
{
  $user = cluevo_get_the_lms_user();

  if (!empty($user->level['current'])) {
    echo esc_html($user->level['current']);
  } else {
    return 0;
  }
}

function cluevo_the_lms_user_exp()
{
  $user = cluevo_get_the_lms_user();

  if (!empty($user->level['exp'])) {
    echo esc_html($user->level['exp']);
  } else {
    echo esc_html(0);
  }
}

function cluevo_the_lms_user_exp_next()
{
  $user = cluevo_get_the_lms_user();

  if (!empty($user->level['next'])) {
    echo esc_html($user->level['next']);
  } else {
    echo esc_html(0);
  }
}

function cluevo_the_lms_user_exp_remaining()
{
  $user = cluevo_get_the_lms_user();

  if (!empty($user->level['remaining'])) {
    echo esc_html($user->level['remaining']);
  } else {
    echo esc_html(0);
  }
}

function cluevo_the_lms_user_exp_pct()
{
  $user = cluevo_get_the_lms_user();

  if (!empty($user->level['pct'])) {
    echo esc_html($user->level['pct']);
  } else {
    echo esc_html(0);
  }
}

function cluevo_the_lms_user_title()
{
  $user = cluevo_get_the_lms_user();

  if (!empty($user->level['title'])) {
    echo esc_html($user->level['title']);
  } else {
    esc_html__("Guest", "cluevo");
  }
}

function cluevo_has_lms_user_title()
{
  $user = cluevo_get_the_lms_user();
  return !empty($user->level['title']);
}

function cluevo_have_lms_items()
{
  $cluevo = cluevo_get_cluevo_lms();
  if (!empty($cluevo)) {
    return $cluevo->have_items();
  } else {
    return false;
  }
}

function cluevo_have_visible_lms_items()
{
  $cluevo = cluevo_get_cluevo_lms();
  if (!empty($cluevo)) {
    return $cluevo->have_visible_items();
  } else {
    return false;
  }
}

function cluevo_the_lms_item_is_visible()
{
  $cluevo = cluevo_get_cluevo_lms();
  if (!empty($cluevo) && !empty($cluevo->item)) {
    return ($cluevo->item->access || $cluevo->item->access_status["access_level"] > 0);
  } else {
    return false;
  }
}

function cluevo_the_lms_item($intId = null)
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->the_item();
}

function cluevo_get_the_lms_item_title()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->item['metadata']->post_title;
}

function cluevo_the_lms_item_title()
{
  echo esc_html(cluevo_get_the_lms_item_title());
}

function cluevo_the_lms_item_metadata()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->the_item_metadata();
}

function cluevo_get_the_lms_item_type()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->the_item_type();
}

function cluevo_the_lms_item_type()
{
  $cluevo = cluevo_get_cluevo_lms();
  echo esc_attr($cluevo->the_item_type());
}

function cluevo_the_lms_tree()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->tree;
}

function cluevo_have_lms_modules()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->have_modules();
}

function cluevo_the_lms_module()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->the_module();
}

function cluevo_the_lms_module_metadata()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->the_module_metadata();
}

function cluevo_get_the_lms_module_progress()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->the_module_progress();
}

function cluevo_the_lms_module_progress()
{
  $cluevo = cluevo_get_cluevo_lms();
  echo esc_attr($cluevo->the_module_progress());
}

function cluevo_the_lms_module_progress_pct()
{
  $cluevo = cluevo_get_cluevo_lms();
  echo esc_html($cluevo->the_module_progress() * 100 . "%");
}

function cluevo_the_lms_item_string_path()
{
  $cluevo = cluevo_get_cluevo_lms();
  if (cluevo_in_the_lms_dependency_loop())
    return esc_html(implode(' / ', $cluevo->dependency['path']['string']));
  else
    return esc_html(implode(' / ', $cluevo->item['path']['string']));
}

function cluevo_the_lms_item_title_path()
{
  $cluevo = cluevo_get_cluevo_lms();
  $ids = [];
  if (cluevo_in_the_lms_dependency_loop())
    $ids = $cluevo->dependency['path']['id'];
  else
    $ids = $cluevo->item['path']['id'];

  $parts = [];
  foreach ($ids as $id) {
    $parts[] = esc_html($cluevo->tree[$id]['metadata']->post_title);
  }

  return implode(' / ', $parts);
}

function cluevo_in_the_lms_dependency_loop()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->in_the_dependency_loop;
}

function cluevo_get_the_lms_page()
{
  $cluevo = cluevo_get_cluevo_lms();
  if (!empty($cluevo)) {
    return $cluevo->current_page;
  }
}

function cluevo_get_the_parent_lms_page()
{
  $cluevo = cluevo_get_cluevo_lms();
  if (!empty($cluevo)) {
    if ($cluevo->shortcode)
      return false;

    if (!empty($cluevo->current_page->parent_id)) {
      $userId = (!empty($cluevo->user) && !empty($cluevo->user->ID)) ? $cluevo->user->ID : null;
      $metadataId = cluevo_get_metadata_id_from_item_id($cluevo->current_page->parent_id);
      if ($metadataId) {
        return get_post($metadataId);
      }
    } else {
      return cluevo_get_page_by_title('Index', OBJECT, CLUEVO_PAGE_POST_TYPE);
    }
  }

  return null;
}

function cluevo_get_the_lms_item()
{
  $cluevo = cluevo_get_cluevo_lms();
  if ($cluevo->in_the_dependency_loop)
    return $cluevo->dependency;
  else
    return $cluevo->item;
}

function cluevo_get_the_next_lms_item()
{
  global $wpdb, $cluevo;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $sql = "SELECT item_id FROM $table WHERE parent_id = %d AND sort_order > %d ORDER BY sort_order ASC LIMIT 1";
  $result = $wpdb->get_var(
    $wpdb->prepare($sql, [$cluevo->current_page->parent_id, $cluevo->current_page->sort_order])
  );

  if (!empty($result)) {
    return cluevo_get_learning_structure_item($result, get_current_user_id());
  }
}

function cluevo_get_the_previous_lms_item()
{
  global $wpdb, $cluevo;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $sql = "SELECT item_id FROM $table WHERE parent_id = %d AND sort_order < %d ORDER BY sort_order DESC LIMIT 1";
  $result = $wpdb->get_var(
    $wpdb->prepare($sql, [$cluevo->current_page->parent_id, $cluevo->current_page->sort_order])
  );

  if (!empty($result)) {
    return cluevo_get_learning_structure_item($result, get_current_user_id());
  }
}

function cluevo_get_the_lms_item_path()
{
  $cluevo = cluevo_get_cluevo_lms();
  $item = null;
  if ($cluevo->in_the_dependency_loop)
    $item = $cluevo->dependency;
  else
    $item = $cluevo->item;

  if (!empty($item->path->string)) {
    return $item->path->string;
  } else {
    return cluevo_get_string_path($item->path);
  }
}

function cluevo_have_lms_dependencies()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->have_dependencies();
}

function cluevo_the_lms_dependency()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->the_dependency();
}

function cluevo_get_the_lms_item_metadata()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->the_item_metadata();
}

function cluevo_load_lms_item($intId)
{
  $cluevo = cluevo_get_cluevo_lms();
  $cluevo->load_item($intId);
}

function cluevo_load_lms_module($strModule)
{
  $cluevo = cluevo_get_cluevo_lms();
  $cluevo->load_module($strModule);
}

function cluevo_create_lms_loop($items)
{
  $cluevo = cluevo_get_cluevo_lms();
  $cluevo->init_loop($items);
}

function cluevo_get_the_module_progress($strModule)
{
  $cluevo = cluevo_get_cluevo_lms();
  if (!empty($cluevo->user)) {
    $key = sanitize_key(SOURCENOVA_LMS_USER_SCORE_SCALED_META_KEY . $strModule);
    if (array_key_exists($key, $cluevo->user['meta']))
      return $cluevo->user['meta'][$key];
  }
  return 0;
}

function cluevo_get_the_lms_module()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->module;
}

function cluevo_get_the_lms_users_competence_scores()
{
  $cluevo = cluevo_get_cluevo_lms();
  if (!empty($cluevo->user)) {
    return $cluevo->user->competences;
  } else {
    return [];
  }
}

function cluevo_get_item_progress_value($intItemId = null)
{
  $progressValue = 0;
  $item = (empty($intItemId)) ? cluevo_get_the_lms_item() : cluevo_get_learning_structure_item($intItemId);
  if (empty($item->module) || $item->module < 0) {
    $progressValue = (!empty($item->completed_children)) ? count($item->completed_children) : 0;
  } else {
    $user = cluevo_get_the_lms_user();
    if (!empty($user)) {
      $progressValue = cluevo_get_users_best_module_attempt($user->ID, $item->module_id);
    }
  }

  return $progressValue;
}

function cluevo_get_item_progress_max($intItemId = null)
{
  $progressMax = 0;
  $item = (empty($intItemId)) ? cluevo_get_the_lms_item() : cluevo_get_learning_structure_item($intItemId);
  if (empty($item->module) || $item->module < 0) {
    $progressMax = count($item->children);
  } else {
    $user = cluevo_get_the_lms_user();
    if (!empty($user)) {
      $progressMax = 1;
    }
  }

  return $progressMax;
}

function cluevo_get_item_progress_width($intItemId = null)
{
  $progressMax = cluevo_get_item_progress_max($intItemId);
  $progressValue = cluevo_get_item_progress_value($intItemId);
  $progressWidth = 0;

  if ($progressMax > 0)
    $progressWidth = ($progressValue / $progressMax) * 100;

  return $progressWidth;
}

function cluevo_display_notice($strTitle, $strMessage, $strType = 'notice', $dismissible = false)
{
  if (!empty($dismissible) && !is_bool($dismissible)) {
    if (get_user_meta(get_current_user_id(), "cluevo-admin-notice-dismissed-$dismissible", true)) return;
  }
?>
  <div class="cluevo-notice cluevo-notice-<?php echo esc_attr($strType); ?><?php echo ($dismissible) ? " cluevo-is-dismissible" : ""; ?>">
    <?php if (!empty($dismissible)) { ?> <div class="cluevo-notice-dismiss" data-key="<?php echo esc_attr($dismissible); ?>"></div><?php } ?>
    <p class="cluevo-notice-title"><?php echo esc_html($strTitle); ?></p>
    <p><?php echo nl2br(esc_html($strMessage)); ?></p>
  </div>
<?php }

function cluevo_display_notice_html($strTitle, $strMessage, $strType = 'notice', $dismissible = false)
{
  if (!empty($dismissible) && !is_bool($dismissible)) {
    if (get_user_meta(get_current_user_id(), "cluevo-admin-notice-dismissed-" . sanitize_key($dismissible), true)) return;
  }
?>
  <div class="cluevo-notice cluevo-notice-<?php echo esc_attr($strType); ?><?php echo ($dismissible) ? " cluevo-is-dismissible" : ""; ?>">
    <?php if (!empty($dismissible)) { ?> <div class="cluevo-notice-dismiss" data-key="<?php echo esc_attr($dismissible); ?>"></div><?php } ?>
    <p class="cluevo-notice-title"><?php echo esc_html($strTitle); ?></p>
    <p><?php echo wp_kses($strMessage, wp_kses_allowed_html("post")); ?></p>
  </div>
  <?php }

function cluevo_item_has_parent_item()
{
  $parentPost = cluevo_get_the_parent_lms_page();
  return (!empty($parentPost));
}

function cluevo_user_has_item_access_level()
{
  $item = cluevo_get_the_lms_page();
  if (!empty($item)) {
    return ($item->access_status["access_level"] == true);
  }
}

function cluevo_can_user_access_item()
{
  $item = cluevo_get_the_lms_page();
  return (empty($item->access)) ? false : $item->access;
}

function cluevo_get_the_items_module()
{
  $item = cluevo_get_the_lms_page();
  if (empty($item->module_id)) return null;
  $module = cluevo_get_module($item->module_id);
  return $module;
}

function cluevo_get_the_items_module_display_mode($intItemId = null)
{
  $item = (empty($intItemId)) ? cluevo_get_the_lms_item() : cluevo_get_learning_structure_item($intItemId);
  if (!empty($item->display_mode)) {
    return $item->display_mode;
  } else {
    return strtolower(get_option("cluevo-modules-display-mode", "Lightbox"));
  }
}

function cluevo_get_the_content_list_style()
{
  $hidden = get_option("cluevo-hide-item-list-style-switch", "");
  $default = get_option("cluevo-default-item-list-style", "col");
  if (!in_array($default, ["row", "col"])) {
    $default = "col";
  }
  if (!empty($hidden)) {
    return "cluevo-content-list-style-{$default}";
  }
  $cluevoListStyle = !empty($_COOKIE["cluevo-content-list-style"]) ? sanitize_text_field($_COOKIE["cluevo-content-list-style"]) : null;
  $cluevoValidListStyles = ["cluevo-content-list-style-row", "cluevo-content-list-style-col"];
  $validStyle = array_search($cluevoListStyle, $cluevoValidListStyles);
  if (!empty($cluevoListStyle)) return $validStyle;
  return esc_attr("cluevo-content-list-style-{$default}");
}

function cluevo_the_content_list_style()
{
  echo cluevo_get_the_content_list_style();
}

function cluevo_display_the_content_list_style_switch()
{
  $hidden = get_option("cluevo-hide-item-list-style-switch", "");
  if (empty($hidden)) { ?>
    <div class="cluevo-content-list-style-switch">
      <div class="cluevo-btn cluevo-content-list-style-col <?php echo (cluevo_get_the_content_list_style() === 'cluevo-content-list-style-col') ? "active" : ""; ?>"><?php include(cluevo_get_conf_const('CLUEVO_IMAGE_DIR') . "icon-cols.svg"); ?></div>
      <div class="cluevo-btn cluevo-content-list-style-row <?php echo (cluevo_get_the_content_list_style() === 'cluevo-content-list-style-row') ? "active" : ""; ?>"><?php include(cluevo_get_conf_const('CLUEVO_IMAGE_DIR') . "icon-rows.svg"); ?></div>
    </div>
<?php }
}

function cluevo_get_the_breadcrumbs()
{
  $item = cluevo_get_the_lms_page();
  if (empty($item)) return;
  $cluevo = cluevo_get_cluevo_lms();
  $userId = $cluevo->user_id;
  $i = 0;
  $parent = cluevo_get_learning_structure_item($item->parent_id, $userId, true);
  $crumbs = [$parent];
  if (!empty($parent) && !empty($parent->parent_id)) {
    do {
      $i++;
      $parent = cluevo_get_learning_structure_item($parent->parent_id, $userId, true);
      if (!empty($parent)) {
        $crumbs[] = $parent;
      }
    } while (!empty($parent) && $parent->parent_id > 0 && $i < 10);
  }
  $crumbs = array_reverse($crumbs);
  return $crumbs;
}

function cluevo_get_the_index_page()
{
  if (($page = cluevo_get_page_by_title('Index', OBJECT, CLUEVO_PAGE_POST_TYPE)) != NULL) {
    return $page;
  }
  return false;
}

function cluevo_get_the_index_page_link()
{
  $id = (int)get_option("cluevo-tree-index-page", null);
  $indexPage = get_post($id);
  if (!empty($id) && !empty($indexPage)) {
    return get_permalink($id);
  }
  $page = cluevo_get_the_index_page();
  if (!empty($page)) {
    $link = get_permalink($page);
    return $link;
  }
  return false;
}

function cluevo_get_the_shortcode_content()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->shortcode_content;
}

function cluevo_user_has_competence_areas()
{
  $cluevo = cluevo_get_cluevo_lms();
  if (empty($cluevo)) return false;
  if (empty($cluevo->user)) return false;
  return $cluevo->user->has_competence_areas();
}

function cluevo_the_users_competence_area()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->user->the_competence_area();
}

function cluevo_get_the_users_competence_area()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->user->competence_area;
}

function cluevo_get_the_users_competence_area_id()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->user->competence_area->competence_area_id;
}

function cluevo_get_the_users_competence_area_name()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->user->competence_area->competence_area_name;
}

function cluevo_get_the_users_competence_area_score()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->user->competence_area->score;
}

function cluevo_get_the_users_competence_area_metadata_page()
{
  $cluevo = cluevo_get_cluevo_lms();
  return get_post($cluevo->user->competence_area->metadata_id);
}

function cluevo_user_has_competences()
{
  $cluevo = cluevo_get_cluevo_lms();
  if (empty($cluevo)) return false;
  if (empty($cluevo->user)) return false;
  return $cluevo->user->has_competences();
}

function cluevo_the_users_competence()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->user->the_competence();
}

function cluevo_get_the_users_competence()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->user->competence;
}

function cluevo_get_the_users_competence_name()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->user->competence->competence_name;
}

function cluevo_get_the_users_competence_id()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->user->competence->competence_id;
}

function cluevo_get_the_users_competence_score()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->user->competence->score;
}

function cluevo_get_the_users_competences_metadata_page()
{
  $cluevo = cluevo_get_cluevo_lms();
  return get_post($cluevo->user->competence->metadata_id);
}

function cluevo_users_competence_has_modules()
{
  $cluevo = cluevo_get_cluevo_lms();
  if (empty($cluevo)) return false;
  if (empty($cluevo->user)) return false;
  return $cluevo->user->competence_has_modules();
}

function cluevo_the_users_competence_module()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->user->the_competence_module();
}

function cluevo_get_the_users_competence_module()
{
  $cluevo = cluevo_get_cluevo_lms();
  $score = $cluevo->user->competence_module->score;
  $module = cluevo_get_module($cluevo->user->competence_module->id);
  $module->competence_score = $score;
  return $module;
}

function cluevo_get_the_users_competence_module_score()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->user->competence_module->score;
}

function cluevo_get_the_users_competence_module_coverage()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->user->competence_module->coverage;
}

function cluevo_get_the_users_competence_module_id()
{
  $cluevo = cluevo_get_cluevo_lms();
  return $cluevo->user->competence_module->id;
}

function cluevo_get_the_users_competence_module_metadata_page()
{
  $cluevo = cluevo_get_cluevo_lms();
  $module = cluevo_get_module($cluevo->user->competence_module->id);
  return get_post($module->metadata_id);
}

function cluevo_get_the_lms_items_hide_info_box_setting()
{
  $cluevo = cluevo_get_cluevo_lms();
  $item = (!empty($cluevo->item)) ? $cluevo->item : $cluevo->current_page;
  $item->load_settings();
  $value = $item->get_setting("hide-info-box");
  return ((int)$value === 1);
}

function cluevo_get_the_display_empty_item_message_setting()
{
  $opt = get_option('cluevo-display-empty-item-message', false);
  return !empty($opt) && $opt === 'on';
}

function cluevo_get_the_lms_items_empty_text()
{
  $cluevo = cluevo_get_cluevo_lms();
  $item = (!empty($cluevo->item)) ? $cluevo->item : $cluevo->current_page;
  $item->load_settings();
  $text = trim((string)$item->get_setting("element-is-empty-text"));
  return (!empty($text)) ? $text : __("This element is empty.", "cluevo");
}

function cluevo_the_lms_items_empty_text()
{
  $cluevo = cluevo_get_cluevo_lms();
  $item = (!empty($cluevo->item)) ? $cluevo->item : $cluevo->current_page;
  $item->load_settings();
  $text = $item->get_setting("element-is-empty-text");
  echo (!empty($text)) ? esc_html($text) : __("This element is empty.", "cluevo");
}

function cluevo_get_the_lms_items_hide_access_denied_box_setting()
{
  $cluevo = cluevo_get_cluevo_lms();
  $item = (!empty($cluevo->item)) ? $cluevo->item : $cluevo->current_page;
  $item->load_settings();
  $value = $item->get_setting("hide-access-denied-box");
  return ((int)$value === 1);
}

function cluevo_get_the_lms_items_access_denied_text()
{
  $cluevo = cluevo_get_cluevo_lms();
  $text = __("You do not have the required permissions to access this element", "cluevo");
  if (!empty($cluevo->item)) {
    $item = (!empty($cluevo->item)) ? $cluevo->item : $cluevo->current_page;
    $item->load_settings();
    $text = trim((string)$item->get_setting("access-denied-text"));
    $text = (!empty($text)) ? $text : __("You do not have the required permissions to access this element", "cluevo");
    if (!empty($item->settings["max-attempts"]) && (int)$item->settings["max-attempts"] > 0) {
      if (is_user_logged_in() && empty($item->access_status["attempts"])) {
        $text .= "\n";
        $text .= sprintf(__("You have already attempted this module the maximum amount of times: %d/%d", "cluevo"), $item->attempt_count, $item->settings["max-attempts"]);
      }
    }
  }
  return $text;
}

function cluevo_get_lms_item_access_denied_text($item)
{
  $text = __("You do not have the required permissions to access this element", "cluevo");
  if (!empty($item)) {
    $item->load_settings();
    $text = trim((string)$item->get_setting("access-denied-text"));
    $text = (!empty($text)) ? $text : __("You do not have the required permissions to access this element", "cluevo");
    if (!empty($item->settings["max-attempts"]) && (int)$item->settings["max-attempts"] > 0) {
      if (empty($item->access_status["attempts"])) {
        $text .= "\n";
        $text .= sprintf(__("You have already attempted this module the maximum amount of times: %d/%d", "cluevo"), $item->attempt_count, $item->settings["max-attempts"]);
      }
    }
  }
  return $text;
}

function cluevo_the_lms_items_access_denied_text()
{
  $cluevo = cluevo_get_cluevo_lms();
  $item = (!empty($item)) ? $cluevo->item : $cluevo->current_page;
  $item->load_settings();
  $text = $item->get_setting("access-denied-text");
  echo (!empty($text)) ? esc_html($text) : __("You do not have the required permissions to access this element", "cluevo");
}

function cluevo_the_item_is_a_link()
{
  $cluevo = cluevo_get_cluevo_lms();
  $item = (!empty($cluevo->item)) ? $cluevo->item : $cluevo->current_page;
  $item->load_settings();
  $link = trim((string)$item->get_setting("item-is-link"));
  return ($link !== "") ? true : false;
}

function cluevo_the_items_link_opens_in_new_window()
{
  $cluevo = cluevo_get_cluevo_lms();
  $item = (!empty($cluevo->item)) ? $cluevo->item : $cluevo->current_page;
  $item->load_settings();
  return ($item->get_setting("open-link-in-new-window") == 1) ? true : false;
}

function cluevo_get_the_items_link()
{
  $meta = cluevo_the_lms_item_metadata();
  $cluevo = cluevo_get_cluevo_lms();
  $item = (!empty($cluevo->item)) ? $cluevo->item : $cluevo->current_page;
  $item->load_settings();
  $link = trim((string)$item->get_setting("item-is-link"));
  if ($link !== "") return $link;
  if ($meta) return get_permalink($meta->ID);
}

function cluevo_turbo_get_users_completed_modules($intUserId = null)
{
  $uid = (!empty($intUserId)) ? $intUserId : get_current_user_id();
  if (isset($GLOBALS["cluevo-users-completed-modules"][$uid])) {
    return $GLOBALS["cluevo-users-completed-modules"][$uid];
  } else {
    $GLOBALS["cluevo-users-completed-modules"][$uid] = cluevo_get_users_completed_modules($uid, false);
    return $GLOBALS["cluevo-users-completed-modules"][$uid];
  }
  return [];
}

function cluevo_build_module_dependencies()
{
  global $wpdb;
  $depTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULE_DEPENDENCIES;
  $sql = "SELECT * FROM $depTable ORDER BY module_id";
  $rows = $wpdb->get_results($sql);
  $deps = [];
  $arrCompleted = (empty($arrCompleted)) ? cluevo_turbo_get_users_completed_modules() : [];
  if (!empty($rows)) {
    foreach ($rows as $row) {
      $id = $row->dep_id;
      $results = ["normal" => [], "inherited" => [], "blocked" => [], "all" => []];
      if (array_key_exists($id, $deps)) {
        $results = $deps[$id];
      } else {
        $results = ["normal" => [], "inherited" => [], "blocked" => [], "all" => []];
      }
      if (!array_key_exists($row->dep_type, $results))
        $results[$row->dep_type] = [];

      $results[$row->dep_type][$row->module_id] = false;
      if (!empty($row->module_id) && in_array($row->module_id, $arrCompleted)) $results[$row->dep_type][$row->module_id] = true;

      if (!empty($row->module_id) && ($row->dep_type == "normal" || $row->dep_type == "inherited")) {
        $results['all'][$row->module_id] = false;
        if (in_array($row->module_id, $arrCompleted)) $results["all"][$row->module_id] = true;
      }
      $deps[$id] = $results;
    }
  }
  $GLOBALS["cluevo-module-dependencies"] = $deps;
}

function cluevo_turbo_get_module_dependencies($intItemId = null, $arrCompleted = [])
{
  if (isset($GLOBALS["cluevo-module-dependencies"]) && empty($intItemId)) {
    if (array_key_exists($intItemId, $GLOBALS["cluevo-module-dependencies"])) return $GLOBALS["cluevo-module-dependencies"][$intItemId];
    return ["normal" => [], "inherited" => [], "blocked" => [], "all" => []];
    return $GLOBALS["cluevo-module-dependencies"];
  } else {
    if (!array_key_exists("cluevo-module-dependencies", $GLOBALS)) {
      cluevo_build_module_dependencies();
    }
    if (!empty($intItemId)) {
      if (array_key_exists($intItemId, $GLOBALS["cluevo-module-dependencies"])) return $GLOBALS["cluevo-module-dependencies"][$intItemId];
      return ["normal" => [], "inherited" => [], "blocked" => [], "all" => []];
    } else {
      return $GLOBALS["cluevo-module-dependencies"];
    }
  }
  return [];
}

function cluevo_build_item_dependencies()
{
  global $wpdb;
  $depTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_DEPENDENCIES;
  $sql = "SELECT * FROM $depTable ORDER BY item_id";
  $rows = $wpdb->get_results($sql);
  $deps = [];
  if (!empty($rows)) {
    foreach ($rows as $row) {
      $id = $row->item_id;
      $results = ["normal" => [], "inherited" => [], "blocked" => [], "all" => []];
      if (array_key_exists($id, $deps)) {
        $results = $deps[$id];
      } else {
        $results = ["normal" => [], "inherited" => [], "blocked" => [], "all" => []];
      }
      if (!array_key_exists($row->dep_type, $results))
        $results[$row->dep_type] = [];

      $completed = false;
      if (!empty($row->dep_id)) {
        $dep = cluevo_turbo_get_tree_item($row->dep_id);
        if (!empty($dep) && $dep->completed) {
          $completed = true;
        }
      }
      $results[$row->dep_type][$row->dep_id] = $completed;

      if (!empty($row->dep_id) && ($row->dep_type == "normal" || $row->dep_type == "inherited")) {
        $results['all'][$row->dep_id] = $completed;
      }
      $deps[$id] = $results;
    }
  }
  $GLOBALS["cluevo-item-dependencies"] = $deps;
}

function cluevo_turbo_get_item_dependencies($intItemId = null)
{
  if (isset($GLOBALS["cluevo-item-dependencies"]) && empty($intItemId)) {
    if (array_key_exists($intItemId, $GLOBALS["cluevo-item-dependencies"])) return $GLOBALS["cluevo-item-dependencies"][$intItemId];
    return ["normal" => [], "inherited" => [], "blocked" => [], "all" => []];
  } else {
    if (isset($GLOBALS["cluevo-item-dependencies"]) && !empty($intItemId) && isset($GLOBALS["cluevo-item-dependencies"][$intItemId])) {
      return $GLOBALS["cluevo-item-dependencies"][$intItemId];
    } else {
      if (!array_key_exists("cluevo-item-dependencies", $GLOBALS)) {
        cluevo_build_item_dependencies();
      }
      return ["normal" => [], "inherited" => [], "blocked" => [], "all" => []];
    }
    if (!empty($intItemId)) {
      if (array_key_exists($intItemId, $GLOBALS["cluevo-item-dependencies"])) return $GLOBALS["cluevo-item-dependencies"][$intItemId];
      return ["normal" => [], "inherited" => [], "blocked" => [], "all" => []];
    } else {
      return $GLOBALS["cluevo-item-dependencies"];
    }
  }
  return [];
}

function cluevo_turbo_get_module($mixedId, $strLangCode = "", $boolRefresh = false)
{
  $modules = cluevo_turbo_get_modules($strLangCode, $boolRefresh);
  $results = array_filter($modules, function ($m) use ($mixedId) {
    return ((is_numeric($mixedId) && $m->module_id == $mixedId) || $m->module_name == $mixedId);
  });
  if (!empty($results)) {
    return array_pop($results);
  }
  return null;
}

function cluevo_turbo_get_modules($strLangCode = "", $boolRefresh = false)
{
  $last = (isset($GLOBALS["cluevo-last-module-lang"])) ? $GLOBALS["cluevo-last-module-lang"] : null;
  if (isset($GLOBALS["cluevo-modules"]) && $boolRefresh == false && $last == $strLangCode) {
    return $GLOBALS["cluevo-modules"];
  } else {
    $GLOBALS["cluevo-last-module-lang"] = $strLangCode;
    $ratings = cluevo_turbo_get_ratings();
    global $wpdb;
    $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
    $moduleTypeTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_TYPES;
    $progressTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
    $curUser = get_current_user_id();
    if (version_compare(get_option(CLUEVO_DB_VERSION_OPT_KEY), CLUEVO_PLUGIN_DB_VERSION) === -1) {
      $sql = "SELECT m.*, LOWER(t.type_name) AS type_name, t.type_description, a.count AS attempts ";
    } else {
      $sql = "SELECT m.*, LOWER(t.type_name) AS type_name, t.type_description, a.count AS attempts, tags ";
    }
    $sql .= "FROM $moduleTable m
      LEFT JOIN $moduleTypeTable t ON m.type_id = t.type_id
      LEFT JOIN (
        SELECT module_id, COUNT(*) AS count FROM {$progressTable} WHERE user_id = %d AND credit = 1 GROUP BY module_id
      ) a ON m.module_id = a.module_id";
    if (!empty($strLangCode)) {
      $sql .= " WHERE lang_code = %s";
      $results = $wpdb->get_results($wpdb->prepare($sql, [$curUser, $strLangCode]), OBJECT);
    } else {
      $results = $wpdb->get_results($wpdb->prepare($sql, [$curUser]), OBJECT);
    }
    if (!empty($results)) {
      if (!empty($ratings)) {
        $results = array_map(
          function ($module) use ($ratings) {
            if (!empty($module->tags)) {
              $tagsRaw = explode(",", $module->tags);
              $tags = [];
              if (!empty($tagsRaw) && is_array($tagsRaw)) {
                foreach ($tagsRaw as $tag) {
                  $t = trim((string)$tag);
                  if (!empty($t)) $tags[] = $t;
                }
              }
              $module->tags = $tags;
            }
            if (!empty($ratings["post"]["cluevo-module-rating-avg-" . $module->module_id])) {
              $module->rating_avg = $ratings["post"]["cluevo-module-rating-avg-" . $module->module_id];
            }
            if (!empty($ratings["user"]["cluevo-module-rating-" . $module->module_id])) {
              $module->rating_user = $ratings["user"]["cluevo-module-rating-" . $module->module_id];
            }
            return $module;
          },
          $results
        );
      }
      $GLOBALS["cluevo-modules"] = $results;
      return $GLOBALS["cluevo-modules"];
    } else {
      return [];
    }
  }
}

function cluevo_resolve_item_dependencies($item, $completedItems)
{
  $dependencies = json_decode(json_encode($item->dependencies));
  if (!empty($dependencies->other)) {
    foreach ($dependencies->other as $ok => $deps) {
      if (empty($dependencies->other->all)) $dependencies->other->all = new stdClass();
      foreach ($dependencies->other->{$ok} as $d => $state) {
        if ($ok == "all" || $ok == "blocked") continue;
        if (property_exists($dependencies->other->all, $d)) {
          $dependencies->other->all->{$d} = in_array($d, $completedItems);
        }
      }
    }
  }

  $itemsGranted = true;
  if (!empty($dependencies->other->all)) {
    foreach ($dependencies->other->all as $dep => $value) {
      if ($value == false || empty($value)) {
        $itemsGranted = false;
        break;
      }
    }
  }

  $completedModules = cluevo_get_users_completed_modules();
  if (!empty($dependencies->modules)) {
    foreach ($dependencies->modules as $ok => $deps) {
      if (empty($dependencies->modules->all)) $dependencies->modules->all = new stdClass();
      foreach ($dependencies->modules->{$ok} as $d => $state) {
        if ($ok == "all" || $ok == "blocked") continue;
        if (property_exists($dependencies->modules->all, $d)) {
          $dependencies->modules->all->{$d} = in_array($d, $completedModules);
        }
      }
    }
  }

  $modulesGranted = true;
  if (!empty($dependencies->modules->all)) {
    foreach ($dependencies->modules->all as $dep => $value) {
      if ($value == false || empty($value)) {
        $modulesGranted = false;
        break;
      }
    }
  }

  $item->access_status["dependencies"] = $itemsGranted && $modulesGranted;
  $item->dependencies = $dependencies;

  return $item;
}

function cluevo_turbo_get_user($intUserId = null)
{
  $userId = (!empty($intUserId)) ? $intUserId : get_current_user_id();
  if (!empty($GLOBALS["cluevo-users"][$userId])) {
    return $GLOBALS["cluevo-users"][$userId];
  } else {
    $GLOBALS["cluevo-users"][$userId] = cluevo_get_user($intUserId);
    return $GLOBALS["cluevo-users"][$userId];
  }
  return null;
}

function cluevo_resolve_access($item, $intUserId = null)
{
  $userId = (!empty($intUserId)) ? $intUserId : get_current_user_id();
  $completedModules = cluevo_turbo_get_users_completed_modules($userId);
  $perms = cluevo_turbo_get_users_permissions();
  $item->access_status = ["dependencies" => true, "points" => true, "level" => true, "access_level" => false];
  $item->parent_id = (empty($item->parent_id)) ? $item->tree_id : $item->parent_id;
  $user = cluevo_turbo_get_user($userId);

  if (!empty($item->dependencies->other)) {
    foreach ($item->dependencies->other as $ok => $deps) {
      if (empty($item->dependencies->other->all)) $item->dependencies->other->all = new stdClass();
      foreach ($item->dependencies->other->{$ok} as $d => $state) {
        if ($ok == "all" || $ok == "blocked") continue;
        if (!empty($item->dependencies->other->all->{$d})) {
          $item->dependencies->other->all->{$d} = 0;
        }
      }
    }
  }

  if (!empty($item->dependencies->modules)) {
    foreach ($item->dependencies->modules as $mk => $deps) {
      if (empty($item->dependencies->modules->all)) $item->dependencies->modules->all = new stdClass();
      foreach ($item->dependencies->modules->{$mk} as $d => $state) {
        if ($mk == "all" || $mk == "blocked") continue;
        $fulfilled = 0;
        if (in_array($d, $completedModules)) {
          $fulfilled = 1;
        }
        if (
          (is_array($item->dependencies->modules->all) && !array_key_exists($d, $item->dependencies->modules->all)) ||
          (is_object($item->dependencies->modules->all) && !isset($item->dependencies->modules->all->{$d}))
        ) {
          $item->dependencies->modules->all->{$d} = $fulfilled;
        }
        $item->dependencies->modules->{$mk}->{$d} = $fulfilled;
      }
    }
  }

  $granted = true;
  $access = true;
  if (!empty($item->dependencies->other->all)) {
    foreach ($item->dependencies->other->all as $dep => $value) {
      if ($value == false) {
        $granted = false;
        $access = false;
        break;
      }
    }
  }

  if ($granted && !empty($item->dependencies->modules->all)) {
    foreach ($item->dependencies->modules->all as $dep => $value) {
      if (!in_array($dep, $completedModules)) {
        $granted = false;
        $access = false;
        break;
      }
    }
  }
  $item->dependencies = json_decode(json_encode($item->dependencies), true);
  $item->access_status["dependencies"] = $granted;

  $access_level = 0;
  $is_trainer = 0;
  $expired = (!empty($perms[$item->item_id]["expired"])) ? $perms[$item->item_id]["expired"] : null;
  if (current_user_can('administrator')) {
    $access_level = 999;
  } else {
    if (array_key_exists($item->item_id, $perms)) {
      if (!empty($perms[$item->item_id])) {
        if (empty($perms[$item->item_id]["expired"]) || (!empty($perms[$item->item_id]["expired"]) && $perms[$item->item_id]["expired"] > time())) {
          $access_level = $perms[$item->item_id]['level'];
        } else {
          $access_level = 0;
        }
      } else {
        $access_level = $perms[$item->item_id]['level'];
      }
    }
  }
  if (array_key_exists($item->item_id, $perms)) {
    $is_trainer = $perms[$item->item_id]['trainer'];
  }

  $item->expires = $expired;
  $item->access_level = $access_level;
  $item->is_trainer = $is_trainer;
  $item->access_status["access_level"] = $access_level;
  if (!empty((int)$item->level_required)) {
    $item->access_status["level"] = !empty($user->total_points) && (int)$item->level_required <= (int)$user->current_level;
  } else {
    $item->access_status["level"] = true;
  }
  if (!empty((int)$item->points_required)) {
    $item->access_status["points"] = !empty($user->total_points) && (int)$item->points_required <= (int)$user->total_points;
  } else {
    $item->access_status["points"] = true;
  }

  $access = true;
  foreach ($item->access_status as $type => $value) {
    if ($value == false || ($type == "access_level" && $value < 2)) {
      $access = false;
    }
  }

  $item->access = ($access || current_user_can("administrator"));
  return $item;
}

function cluevo_turbo_get_prepared_trees($intUserId = null)
{
  return CluevoTree::load_all($intUserId);
}

function cluevo_turbo_get_trees()
{
  if (isset($GLOBALS["cluevo-trees"])) {
    return $GLOBALS["cluevo-trees"];
  } else {
    $trees = cluevo_turbo_get_prepared_trees();
    $GLOBALS["cluevo-trees"] = $trees;
    return $GLOBALS["cluevo-trees"];
  }
  return [];
}

function cluevo_turbo_get_trees_index()
{
  $trees = CluevoTree::load_all();
  return array_column($trees, "item");
}

function cluevo_turbo_build_hierarchy($source, $intParentId)
{
  $children = [];
  $perms = cluevo_turbo_get_users_permissions();
  foreach ($source as $item) {
    if (!current_user_can("administrator") && !array_key_exists($item->item_id, $perms)) continue;
    if (!current_user_can("administrator") && $perms[$item->item_id] < 1) continue;


    if ($item->parent_id == $intParentId) {
      $itemChildren = cluevo_turbo_build_hierarchy($source, $item->item_id);
      $item->children = [];
      $item->completed_children = [];
      foreach ($itemChildren as $child) {
        if (array_key_exists($child->item_id, $perms) && $perms[$child->item_id]['level'] > 0 || current_user_can("administrator")) {
          $item->children[] = $child;
          if ($child->completed)
            $item->completed_children[] = $child->item_id;
        }
      }

      $item->completed = false;
      if (empty($item->module) || $item->module < 1) {
        if (!empty($item->children)) {
          $item->completed = (count($item->children) == count($item->completed_children));
        }
      }
      $children[] = $item;
    }
  }
  usort($children, function ($a, $b) {
    if (isset($a->sort_order) && isset($b->sort_order)) {
      if ($a->sort_order == $b->sort_order) return 0;
      return ($a->sort_order < $b->sort_order) ? -1 : 1;
    }
    return 1;
  });
  return $children;
}

function cluevo_turbo_get_users_permissions()
{
  $uid = get_current_user_id();
  $wpUser = wp_get_current_user();
  $email = null;
  if ($wpUser) {
    $email = substr($wpUser->user_email, strpos($wpUser->user_email, '@'));
  }

  if (isset($GLOBALS["cluevo-users-permissions"]) && is_array($GLOBALS["cluevo-users-permissions"])) {
    return $GLOBALS["cluevo-users-permissions"];
  } else {
    global $wpdb;
    $permTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_PERMS;
    $userGroupTable = $wpdb->prefix . CLUEVO_DB_TABLE_USERS_TO_GROUPS;
    $groupTable = $wpdb->prefix . CLUEVO_DB_TABLE_USER_GROUPS;

    $args = [];
    if (!empty($uid)) {
      if (version_compare(get_option(CLUEVO_DB_VERSION_OPT_KEY), CLUEVO_PLUGIN_DB_VERSION) === -1) {
        $sql = "SELECT item_id, MAX(access_level) AS access_level, MAX(COALESCE(utg.is_trainer, 0)) AS is_trainer FROM
        {$permTable} tp
        LEFT JOIN {$userGroupTable} utg ON tp.perm = CONCAT('g:', utg.group_id) AND utg.is_trainer = 1
        WHERE perm = CONCAT('u:', %d) OR CONCAT(tp.item_id, ':', perm) IN (
          SELECT CONCAT(tp.item_id, ':', perm)
          FROM {$permTable} tp
          INNER JOIN {$userGroupTable} utg ON utg.user_id = %d AND CONCAT('g:', utg.group_id) = tp.perm
        ) OR CONCAT(tp.item_id, ':', perm) IN (
          SELECT CONCAT(tp.item_id, ':', perm)
          FROM {$permTable} tp
          INNER JOIN $groupTable g ON tp.perm = CONCAT('g:', g.group_id)
          WHERE g.group_name = %s
        )
        GROUP BY item_id";
        $args = [$uid, $uid, $email];
      } else {
        $sql = "SELECT item_id, MAX(access_level) AS access_level, MAX(COALESCE(utg.is_trainer, 0)) AS is_trainer, MAX(tp.date_expired) AS date_expired FROM
        {$permTable} tp
        LEFT JOIN {$userGroupTable} utg ON tp.perm = CONCAT('g:', utg.group_id) AND utg.is_trainer = 1
        WHERE perm = CONCAT('u:', %d) OR CONCAT(tp.item_id, ':', perm) IN (
          SELECT CONCAT(tp.item_id, ':', perm)
          FROM {$permTable} tp
          INNER JOIN {$userGroupTable} utg ON utg.user_id = %d AND CONCAT('g:', utg.group_id) = tp.perm
          WHERE tp.date_expired IS NULL OR tp.date_expired >= NOW()
        ) OR CONCAT(tp.item_id, ':', perm) IN (
          SELECT CONCAT(tp.item_id, ':', perm)
          FROM {$permTable} tp
          INNER JOIN $groupTable g ON tp.perm = CONCAT('g:', g.group_id)
          WHERE g.group_name = %s AND (tp.date_expired IS NULL OR tp.date_expired >= NOW())
        )
        GROUP BY item_id";
        $args = [$uid, $uid, $email];
      }
    } else {
      if (version_compare(get_option(CLUEVO_DB_VERSION_OPT_KEY), CLUEVO_PLUGIN_DB_VERSION) === -1) {
        $sql = "SELECT item_id, MAX(access_level) AS access_level, MAX(tp.date_expired) AS date_expired FROM
        {$permTable} tp WHERE perm = CONCAT('g:', %d)
        GROUP BY item_id";
      } else {
        $sql = "SELECT item_id, MAX(access_level) AS access_level, MAX(tp.date_expired) AS date_expired FROM
        {$permTable} tp WHERE perm = CONCAT('g:', %d)
        GROUP BY item_id";
      }
      $args = [CLUEVO_DEFAULT_GROUP_GUEST];
    }

    $results = $wpdb->get_results(
      $wpdb->prepare($sql, $args)
    );

    $perms = [];
    if (!empty($results)) {
      foreach ($results as $row) {
        $perms[$row->item_id] = [
          'level' => (int)$row->access_level,
          'trainer' => (!empty($row->is_trainer)) ? (int)$row->is_trainer : false,
          'expired' => !empty($row->date_expired) ? strtotime($row->date_expired) : null,
        ];
      }
    }
    $GLOBALS["cluevo-users-permissions"] = $perms;
    return $GLOBALS["cluevo-users-permissions"];
  }
  return [];
}

function cluevo_turbo_get_item_children($intItemId)
{
  $item = CluevoTree::find_item($intItemId);
  if (empty($item)) return null;
  return $item->children;
}

function cluevo_turbo_get_tree_structs()
{
  $perms = cluevo_turbo_get_users_permissions();
  if (isset($GLOBALS["cluevo-tree-structs"]) && is_array($GLOBALS["cluevo-tree-structs"])) {
    return $GLOBALS["cluevo-tree-structs"];
  } else {
    //$trees = cluevo_get_learning_structures(null, true);
    global $wpdb;
    $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
    $opts = $wpdb->get_results(
      "SELECT option_name, option_value, t.* FROM {$wpdb->prefix}options o
      INNER JOIN $treeTable t ON t.item_id = replace(o.option_name, 'cluevo-lms-tree-', '')
      WHERE option_name LIKE 'cluevo-lms-tree-%'"
    );
    if (empty($opts)) return [];
    $trees = [];
    foreach ($opts as $opt) {
      $id = str_replace("cluevo-lms-tree-", "", $opt->option_name);
      $t = CluevoItem::from_std_class($opt);
      $t->metadata_id = $opt->metadata_id;
      $t->item_id = $id;
      $children = json_decode($opt->option_value);
      if (!empty($children)) {
        foreach ($children as $c) {
          $t->children[] = $c;
        }
      }
      $t->completed_children = [];
      $modules = [];
      if (!empty($children)) {
        foreach ($children as $c) {
          if (!empty($c->modules)) {
            foreach ($c->modules as $m) {
              if (!empty($m) && $m > 0 && !in_array($m, $modules)) $modules[] = $m;
            }
          }
        }
      }
      $level = 0;
      $trainer = 0;
      if (array_key_exists($t->item_id, $perms)) {
        $level = $perms[$t->item_id]['level'];
        $trainer = $perms[$t->item_id]['trainer'];
      }
      if (current_user_can("administrator")) {
        $level = 999;
        $trainer = 1;
      }
      $t->access_status["access_level"] = $level;
      $t->access_level = $level;
      $t->is_trainer = $trainer;
      $t->modules = $modules;
      $t->completed = false;
      $trees[] = $t;
    }
    $GLOBALS["cluevo-tree-structs"] = $trees;
    return $GLOBALS["cluevo-tree-structs"];
  }
}

function cluevo_turbo_get_tree_struct($intTreeId)
{
  global $wpdb;
  $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $opt = $wpdb->get_row(
    $wpdb->prepare(
      "SELECT option_name, option_value, t.* FROM {$wpdb->prefix}options o
      INNER JOIN $treeTable t ON t.item_id = replace(o.option_name, 'cluevo-lms-tree-', '')
      WHERE option_name = %s",
      ["cluevo-lms-tree-$intTreeId"]
    )
  );
  $t = CluevoItem::from_std_class($opt);
  $t->metadata_id = $opt->metadata_id;
  $t->item_id = $intTreeId;
  $children = json_decode($opt->option_value);
  if (!empty($children)) {
    foreach ($children as $c) {
      $t->children[] = $c;
    }
  }
  $t->completed_children = [];
  $modules = [];
  if (!empty($children)) {
    foreach ($children as $c) {
      if (!empty($c->modules)) {
        foreach ($c->modules as $m) {
          if (!empty($m) && $m > 0 && !in_array($m, $modules)) $modules[] = $m;
        }
      }
    }
  }
  $t->access_status["access_level"] = null;
  $t->access_level = null;
  $t->modules = $modules;
  $t->completed = false;
  return $t;
}

function cluevo_get_tree_id_from_item_id($intItemId)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $result = $wpdb->get_var(
    $wpdb->prepare("SELECT path FROM $table WHERE item_id = %s", [$intItemId])
  );

  if (!empty($result)) {
    if ($result === '/') return $intItemId;
    $parts = explode('/', $result);
    if (!empty($parts) && is_array($parts) && !empty($parts[1])) {
      return $parts[1];
    }
  }
  return null;
}

function cluevo_turbo_get_tree_item($intItemId, $boolChildren = false)
{
  if (empty($intItemId)) {
    do_action("qm/debug", "load empty itemid");
    $trees = CluevoTree::load_all();
    if (!empty($trees)) {
      return array_column($trees, "item");
    }
  } else {
    return CluevoTree::find_item($intItemId);
  }
  if (!$boolChildren) {
    return  CluevoTree::find_item($intItemId);
  } else {
    $item = CluevoTree::find_item($intItemId);
    if (!empty($item) && !empty($item->children)) {
      return $item->children;
    } else {
      return [];
    }
  }
}

function cluevo_turbo_find_item($items, $intItemId)
{
  return CluevoTree::find_item($intItemId);
}

function cluevo_turbo_get_tree($intItemId)
{
  return CluevoTree::find_item($intItemId);
  $trees = cluevo_turbo_get_trees($intItemId);
  if (array_key_exists($intItemId, $trees)) {
    $tree = $trees[$intItemId];
    $tree->children = array_filter($tree->children, function ($c) use ($intItemId) {
      return $c->parent_id == $intItemId;
    });
    $ratings = cluevo_turbo_get_ratings();
    if (!empty($ratings)) {
      $tree->children = array_map(
        function ($child) use ($ratings) {
          if (!empty($ratings["post"]["cluevo-module-rating-avg-" . $child->module_id])) {
            $child->rating_avg = $ratings["post"]["cluevo-module-rating-avg-" . $child->module_id];
          }
          if (!empty($ratings["user"]["cluevo-module-rating-" . $child->module_id])) {
            $child->rating_user = $ratings["user"]["cluevo-module-rating-" . $child->module_id];
          }
          return $child;
        },
        $tree->children
      );
    }
    return $tree;
  }
  return null;
}

function cluevo_turbo_get_ratings($intUserId = null)
{
  if (!$intUserId) $intUserId = get_current_user_id();

  if (isset($GLOBALS["cluevo-module-ratings"])) {
    return $GLOBALS["cluevo-module-ratings"];
  } else {
    global $wpdb;
    $usermeta = $wpdb->prefix . "usermeta";
    $postmeta = $wpdb->prefix . "postmeta";
    $modules = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;

    $postAverages = $wpdb->get_results("SELECT CONCAT(meta_key, '-', module_id) AS meta_key, meta_value FROM {$postmeta} p INNER JOIN {$modules} m ON p.post_id = m.metadata_id WHERE meta_key LIKE 'cluevo-module-rating-avg'");
    $userRatings = $wpdb->get_results(
      $wpdb->prepare("SELECT meta_key, meta_value FROM {$usermeta} WHERE meta_key LIKE 'cluevo-module-rating-%' AND meta_key != 'cluevo-module-rating-avg' AND user_id = %d", [$intUserId])
    );

    $ratings = ["post" => [], "user" => []];
    if (!empty($postAverages)) {
      foreach ($postAverages as $avg) {
        $ratings["post"][$avg->meta_key] = maybe_unserialize($avg->meta_value);
      }
    }

    if (!empty($userRatings)) {
      foreach ($userRatings as $avg) {
        $ratings["user"][$avg->meta_key] = maybe_unserialize($avg->meta_value);
      }
    }

    $GLOBALS["cluevo-module-ratings"] = $ratings;
    return $GLOBALS["cluevo-module-ratings"];
  }
}

function cluevo_turbo_get_item_id_by_meta_id($intMetaId)
{
  if (isset($GLOBALS["cluevo-meta-ids"])) {
    if (!empty($GLOBALS["cluevo-meta-ids"]) && array_key_exists($intMetaId, $GLOBALS["cluevo-meta-ids"])) {
      return $GLOBALS["cluevo-meta-ids"][$intMetaId];
    }
    return false;
  } else {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
    $result = $wpdb->get_results("SELECT item_id, metadata_id FROM $table");
    if (!empty($result)) {
      $items = [];
      foreach ($result as $row) {
        $items[$row->metadata_id] = $row->item_id;
      }
      $GLOBALS["cluevo-meta-ids"] = $items;
      if (!empty($GLOBALS["cluevo-meta-ids"]) && array_key_exists($intMetaId, $GLOBALS["cluevo-meta-ids"])) {
        return $GLOBALS["cluevo-meta-ids"][$intMetaId];
      }
      return false;
    }
  }
}

function cluevo_turbo_get_meta_id_from_item_id($intItemId)
{
  $item = cluevo_turbo_get_tree_item($intItemId);
  if (!empty($item)) return $item->metadata_id;
  return null;
}

function cluevo_clear_module_cache()
{
  if (isset($GLOBALS["cluevo-modules"])) {
    unset($GLOBALS["cluevo-modules"]);
  }
}

function cluevo_clear_turbo_cache()
{
  if (isset($GLOBALS["cluevo-users-completed-modules"])) {
    unset($GLOBALS["cluevo-users-completed-modules"]);
  }
  if (isset($GLOBALS["cluevo-modules"])) {
    unset($GLOBALS["cluevo-modules"]);
  }
  if (isset($GLOBALS["cluevo-trees"])) {
    unset($GLOBALS["cluevo-trees"]);
  }
  if (isset($GLOBALS["cluevo-tree-structs"])) {
    unset($GLOBALS["cluevo-tree-structs"]);
  }
}

function cluevo_clear_groups_cache()
{
  if (isset($GLOBALS["cluevo_groups"])) {
    unset($GLOBALS["cluevo_groups"]);
  }
}

function cluevo_turbo_get_groups($mixedGroups = null)
{
  $last = (isset($GLOBALS["cluevo-last-groups"])) ? $GLOBALS["cluevo-last-groups"] : -1;
  if (isset($GLOBALS["cluevo_groups"]) && is_array($GLOBALS["cluevo_groups"]) && $last == $mixedGroups) {
    return $GLOBALS["cluevo_groups"];
  } else {
    $GLOBALS["cluevo_groups"] = cluevo_get_user_groups($mixedGroups);
    $GLOBALS["cluevo-last-groups"] = $mixedGroups;
    return $GLOBALS["cluevo_groups"];
  }
}

function cluevo_toc_item_is_open($intItemId, $intLevel = -1)
{
  $cluevo = cluevo_get_cluevo_lms();
  if (is_array($cluevo->shortcode_atts) && in_array("open-all", $cluevo->shortcode_atts)) return true;
  if (isset($cluevo->shortcode_atts["level"])) {
    if ((int)$intLevel <= (int)$cluevo->shortcode_atts["level"]) return true;
  }
  if (!empty($cluevo->shortcode_atts) && !empty($cluevo->shortcode_atts["open"])) {
    $opened = explode(",", $cluevo->shortcode_atts["open"]);
    $opened = array_map(function ($el) {
      return trim((string)$el);
    }, $opened);
    if (in_array($intItemId, $opened)) return true;
  }

  return false;
}

function cluevo_toc_show_rating_stars()
{
  $cluevo = cluevo_get_cluevo_lms();
  return is_array($cluevo->shortcode_atts) && in_array("stars", $cluevo->shortcode_atts);
}

function cluevo_toc_show_rating_value()
{
  $cluevo = cluevo_get_cluevo_lms();
  return is_array($cluevo->shortcode_atts) && in_array("ratings", $cluevo->shortcode_atts);
}

function cluevo_toc_hide_meta()
{
  $cluevo = cluevo_get_cluevo_lms();
  return is_array($cluevo->shortcode_atts) && in_array("hide-meta", $cluevo->shortcode_atts);
}

function cluevo_toc_hide_icons()
{
  $cluevo = cluevo_get_cluevo_lms();
  return is_array($cluevo->shortcode_atts) && in_array("hide-icons", $cluevo->shortcode_atts);
}

function cluevo_toc_hide_count()
{
  $cluevo = cluevo_get_cluevo_lms();
  return is_array($cluevo->shortcode_atts) && in_array("hide-count", $cluevo->shortcode_atts);
}

function cluevo_has_user_completed_item($itemId)
{
  $item = cluevo_turbo_get_tree_item($itemId);
  return $item->completed;
}

function cluevo_can_user_access_module($intModuleId, $intUserId = null)
{
  $userId = (!empty($intUserId)) ? $intUserId : get_current_user_id();
  if (isset($GLOBALS["cluevo-can-user-access-module"][$intModuleId][$intUserId])) {
    return $GLOBALS["cluevo-can-user-access-module"][$intModuleId][$intUserId];
  } else {
    $result = false;
    $moduleItems = cluevo_get_modules_items($intModuleId, true);
    if (empty($moduleItems)) $result = false;
    foreach ($moduleItems as $id) {
      $item = cluevo_get_learning_structure_item($id, $userId);
      if (empty($item)) continue;
      if (!empty($item->access)) $result = true;
    }
    $GLOBALS["cluevo-can-user-access-module"][$intModuleId][$intUserId] = $result;
    return $result;
  }
  return false;
}

function cluevo_can_user_see_module($intModuleId, $intUserId = null)
{
  $userId = (!empty($intUserId)) ? $intUserId : get_current_user_id();
  if (isset($GLOBALS["cluevo-can-user-see-module"][$intModuleId][$intUserId])) {
    return $GLOBALS["cluevo-can-user-see-module"][$intModuleId][$intUserId];
  } else {
    $result = false;
    $moduleItems = cluevo_get_modules_items($intModuleId, true);
    if (empty($moduleItems)) $result = false;
    foreach ($moduleItems as $id) {
      $item = cluevo_get_learning_structure_item($id, $userId);
      if (empty($item)) continue;
      if ($item->access_level > 0) $result = true;
    }
    $GLOBALS["cluevo-can-user-see-module"][$intModuleId][$intUserId] = $result;
    return $result;
  }
  return false;
}

function cluevo_get_the_competence()
{
  global $post, $wpdb;
  if ($post->post_type !== CLUEVO_METADATA_POST_TYPE_COMPETENCE) return null;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
  $sql = "SELECT competence_id FROM {$table} WHERE metadata_id = %d";
  $id = $wpdb->get_var($wpdb->prepare($sql, [$post->ID]));
  if (empty($id)) return null;
  $comp = cluevo_get_competence($id);
  if (empty($comp)) return null;
  $comp->load_modules();
  $comp->load_areas();
  $comp->load_score();
  return $comp;
}

function cluevo_get_the_competence_area()
{
  global $post, $wpdb;
  if ($post->post_type !== CLUEVO_METADATA_POST_TYPE_COMPETENCE_AREA) return null;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCE_AREAS;
  $sql = "SELECT competence_area_id FROM {$table} WHERE metadata_id = %d";
  $id = $wpdb->get_var($wpdb->prepare($sql, [$post->ID]));
  if (empty($id)) return null;
  $area = cluevo_get_competence_area($id);
  if (empty($area)) return null;
  $area->load_competences();
  $area->load_modules();
  $area->load_score();
  return $area;
}

function cluevo_get_progress_bar($value, $max)
{
  $width = 0;
  if ($max > 0) {
    $width = ($value / $max) * 100;
  }
  $out = '<div class="cluevo-progress-container">';
  $out .= '<span
        class="cluevo-progress"
        style="width: ' . esc_attr(abs(100 - $width)) . '%;"
        data-value=' . esc_attr($value) . '"
        data-max="' . esc_attr($max) . '"
      ></span>';
  $out .= '</div>';
  return $out;
}

function cluevo_display_progress_bar($value, $max)
{
  echo cluevo_get_progress_bar($value, $max);
}

function cluevo_format_date($mixedTime)
{
  if (!is_numeric($mixedTime)) {
    $time = strtotime($mixedTime);
  } else {
    $time = (int)$mixedTime;
  }
  $dateFormat = get_option('date_format');
  $timeOffset = 3600 * get_option('gmt_offset');
  return esc_html(date_i18n($dateFormat, $time + $timeOffset));
}

function cluevo_format_time($mixedTime)
{
  if (!is_numeric($mixedTime)) {
    $time = strtotime($mixedTime);
  } else {
    $time = (int)$mixedTime;
  }
  $timeFormat = get_option('time_format');
  $timeOffset = 3600 * get_option('gmt_offset');
  return esc_html(date_i18n($timeFormat, $time + $timeOffset));
}

function cluevo_format_datetime($mixedTime)
{
  if (!is_numeric($mixedTime)) {
    $time = strtotime($mixedTime);
  } else {
    $time = (int)$mixedTime;
  }
  return cluevo_format_date($time) . " " . cluevo_format_time($time);
}

function cluevo_print_r($data, $keysToPrint = null, $detailed = false, $indent = 0)
{
  if (empty($indent)) {
    echo "<pre>";
  }
  if (is_array($data)) {
    cluevo_print_array($data, $keysToPrint, $detailed, $indent);
  } elseif (is_object($data)) {
    cluevo_print_object($data, $keysToPrint, $detailed, $indent);
  } else {
    if ($detailed) {
      ob_start();
      var_dump($data);
      $out = ob_get_clean();
      echo trim($out);
    } else {
      echo $data;
    }
  }
  if (empty($indent)) {
    echo "</pre>";
  }
}

function cluevo_print_array($array, $keysToPrint, $detailed, $indent)
{
  $spaces = str_repeat(' ', $indent * 4);
  $size = count($array) === 0 ? 'EMPTY' : count($array) . " items";
  echo "Array ({$size})";

  if (!empty($array)) {
    echo "\n{$spaces}(<span class=\"wrap\">\n";
    foreach ($array as $key => $value) {
      if ($keysToPrint === null || in_array($key, $keysToPrint) || is_int($key)) {
        $id = 'cluevo-var-' . uniqid();
        echo "{$spaces}    <span onclick=\"document.querySelector('#$id .wrap').style.display = document.querySelector('#$id .wrap').style.display != 'none' ? 'none' : 'inline';\">[$key]</span> => <span id=\"$id\">";
        cluevo_print_r($value, $keysToPrint, $detailed, $indent + 1);
        echo "</span>\n";
      }
    }
    echo "{$spaces}</span>)";
  }
}

function cluevo_print_object($object, $keysToPrint, $detailed, $indent)
{
  $spaces = str_repeat(' ', $indent * 4);
  echo get_class($object) . " Object\n{$spaces}(<span class=\"wrap\">\n";

  foreach ($object as $key => $value) {
    if ($keysToPrint === null || in_array($key, $keysToPrint)) {
      $id = 'cluevo-var-' . uniqid();
      echo "{$spaces}    <span onclick=\"document.querySelector('#$id .wrap').style.display = document.querySelector('#$id .wrap').style.display != 'none' ? 'none' : 'inline';\">[$key]</span> => <span id=\"$id\">";
      cluevo_print_r($value, $keysToPrint, $detailed, $indent + 1);
      echo "</span>\n";
    }
  }

  echo "{$spaces}</span>)";
}
?>
