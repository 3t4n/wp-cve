<?php
$item = cluevo_get_the_lms_item();
if ($item->access_level == 0) return;
$item->load_settings();
$path = cluevo_get_the_lms_item_path();
$path = implode(" / ", $path);
$meta = cluevo_the_lms_item_metadata();
$progressMax = cluevo_get_item_progress_max();
$progressValue = cluevo_get_item_progress_value();
$progressWidth = cluevo_get_item_progress_width();
$completedModules = 0;
$moduleCount = $item->module_count;
$user = cluevo_get_the_lms_user();
$showIndicator = get_option('cluevo-show-tile-indicator');
$showItemType = get_option('cluevo-display-item-type-text');
$showRatings = get_option('cluevo-display-module-ratings');
$showRatingsThreshold = get_option('cluevo-display-module-ratings-threshold', 0);
$date_format = get_option('date_format');
$time_format = get_option('time_format');
$format = "$date_format $time_format";

$img = null;
if (!empty($meta)) {
  if (has_post_thumbnail($meta->ID))
    $img = get_the_post_thumbnail($meta->ID);
}

if (empty($img)) {
  $imgDir = cluevo_get_conf_const('CLUEVO_IMAGE_URL');
  $img = '<img src="' . "$imgDir/lms-content-placeholder.jpg" . '" alt="" />';
}

$displayMode = cluevo_get_the_items_module_display_mode();
$tileMode = strtolower(get_option("cluevo-display-diagonal-tiles", "off"));
$diagonal = ($tileMode === "on") ? "diagonal" : "";
$blocked = ($item->access_level < 2 || !$item->access) ? "blocked" : "";
$blockAnyway = false;
if (!empty($item->children)) {
  $blockAnyway = true;
  foreach ($item->children as $c) {
    if (!empty($c->access)) {
      $blockAnyway = false;
      break;
    }
  }
}

$module = null;
if (!empty($item->module) && $item->module > 0) {
  if ($item->module) {
    $module = cluevo_get_module($item->module);
    do_action('cluevo_enqueue_module_scripts');
  }
}

$data = [];
foreach ($item->settings as $key => $value) {
  if (is_array($value) && count($value) == 1) {
    $value = maybe_unserialize($value[0]);
  } else {
    $value = maybe_unserialize($value);
  }
  if (!empty($value)) {
    if (!is_string($value)) {
      $value = json_encode($value);
    }
    $key = str_replace(CLUEVO_META_DATA_PREFIX, '', $key);
    $key = str_replace('_', '-', $key);
    $data[] = "data-" . sanitize_key($key) . "='" . esc_attr($value) . "'";
  }
}

do_action("cluevo_missing_dependencies_json", $item);

?>
<div class="cluevo-content <?php echo esc_attr($item->type); ?> <?php echo !$item->published ? "draft" : ''; ?>">
  <?php if (!$blocked) { ?>
    <?php if (cluevo_the_item_is_a_link()) { ?>
      <a class="cluevo-content-item-link <?php echo esc_attr($item->type); ?>" <?php if (cluevo_the_items_link_opens_in_new_window()) echo 'target="_blank"'; ?> href="<?php echo esc_attr(cluevo_get_the_items_link()); ?>">
      <?php } else { ?>

        <a class="cluevo-content-item-link <?php if ($blockAnyway) echo "no-children-accessible"; ?> <?php echo ($module === null && $item->type == "module") ? "cluevo-empty-module" : ""; ?> <?php echo esc_attr($item->type); ?> <?php echo esc_attr($blocked); ?> <?php if (empty($item->has_content()) && !empty($item->module) && $item->module > 0 && $module !== null) {
                                                                                                                                                                                                                                                                        echo "cluevo-module-link cluevo-module-mode-$displayMode";
                                                                                                                                                                                                                                                                      } ?>" <?php if (cluevo_the_item_is_a_link() && cluevo_the_items_link_opens_in_new_window()) echo 'target="_blank"'; ?> href="<?php echo esc_attr(cluevo_get_the_items_link()); ?>" data-item-id="<?php echo esc_attr($item->item_id); ?>" <?php echo cluevo_get_item_data_string($item); ?> data-module-id="<?php echo (!empty($module->module_id)) ? $module->module_id : 0; ?>" <?php if ($item->get_setting('hide-lightbox-close-button') == 1) echo "data-hide-lightbox-close-button=\"1\""; ?> data-module-type="<?php echo esc_attr(strtolower(((!empty($module->type_name)) ? $module->type_name : ""))); ?>">
        <?php } ?>
      <?php } else {  ?>
        <div class="cluevo-content-item-link
    <?php if (!$item->access) echo "access-denied"; ?>
    <?php if (empty($item->access_status["dependencies"]) || empty($item->access_status["points"]) || empty($item->access_status["level"])) echo "missing-reqs"; ?>" data-points-required="<?php echo esc_attr($item->points_required); ?>" data-level-required="<?php echo esc_attr($item->level_required); ?>" data-user-level="<?php echo esc_attr(cluevo_get_the_lms_user_level()); ?>" data-user-points="<?php echo esc_attr(cluevo_get_the_lms_user_points()); ?>" data-access-denied-text="<?php echo esc_attr(cluevo_get_the_lms_items_access_denied_text()); ?>" data-item-id="<?php echo (int)$item->item_id; ?>">
        <?php } ?>
        <div class="cluevo-post-thumb">
          <?php if ($showIndicator) { ?><div class="cluevo-item-type-corner <?php echo !empty($item->module) ? esc_attr('module') : esc_attr($item->type); ?>"></div> <?php } ?>

          <?php if (!empty($img)) {
            echo wp_kses($img, ["img" => ["src" => 1, "alt" => 1, "class" => 1]]);
          } ?>
          <div class="cluevo-meta-bg">
            <div class="meta-bg-corner"></div>
          </div>
          <div class="cluevo-meta-container">
            <?php if (!empty($item->expires) && $item->expires > time()) { ?>
              <div class="cluevo-meta-item cluevo-access-expires"><i class="fas fa-clock" title="<?php echo esc_attr(sprintf(__("Access until: %s", "cluevo"), wp_date($format, $item->expires))); ?>"></i></div>
            <?php } ?>
            <div class="cluevo-meta-item cluevo-access"><?php if ($item->access) { ?><i class="fas fa-unlock"></i><?php } else { ?><i class="fas fa-lock"></i> <?php } ?></div>
            <?php do_action("cluevo_part_lms_item_meta", $item); ?>
            <div class="cluevo-meta-item"><?php if (!empty($item->completed) && $item->completed) { ?><i class="fas fa-check"></i><?php } ?></div>
            <?php if (!empty($module)) { ?>
              <div class="cluevo-badge cluevo-module-type"><?php do_action("cluevo_module_icon_" . sanitize_key($module->type_name), $module); ?></div>
            <?php } ?>
            <?php if ($item->type !== 'module') { ?>
              <div class="cluevo-module-status cluevo-meta-item">
                <?php if (count($item->children) > 0) echo count($item->completed_children) . " / " . count($item->children); ?>
              </div>
            <?php } ?>
            <?php if ($showRatings && !empty($item->rating_avg["value"]) && $item->rating_avg["value"] >= $showRatingsThreshold) { ?>
              <div class="cluevo-meta-item cluevo-module-rating"><i class="fas fa-star" title="<?php echo esc_attr(number_format_i18n($item->rating_avg["value"], 2)); ?>"></i><span class="cluevo-rating-value"><?php echo esc_html(number_format_i18n($item->rating_avg["value"], 2)); ?></span></div>
            <?php } ?>
            <?php if ($showItemType) { ?>
              <div class="cluevo-item-type">
                <?php _e($item->type, "cluevo"); ?> <?php if (!$item->published) echo "[" . esc_html__('Draft', "cluevo") . "]"; ?>
              </div>
            <?php } ?>
          </div>
        </div>
        <div class="cluevo-content-container">
          <div class="cluevo-description"><?php echo (!empty($meta->post_title)) ? esc_html($meta->post_title) : "&nbsp;"; ?></div>
          <div class="cluevo-excerpt"><?php echo (!empty($meta->post_excerpt)) ? esc_html($meta->post_excerpt) : "&nbsp;"; ?></div>
          <?php if (get_option("cluevo-display-item-status-row", "")) { ?>
            <div class="cluevo-content-status">
              <div class="cluevo-course-completion-status">
                <?php echo esc_html_e("Status: ", "cluevo"); ?>
                <?php if ($item->access) { ?>
                  <span class="cluevo-content-status-value <?php echo ($item->completed) ? esc_attr('cluevo-content-status-completed') : esc_attr('cluevo-content-status-incomplete'); ?>"><?php ($item->completed) ? esc_html_e("Completed", "cluevo") : esc_html_e("Not Completed", "cluevo"); ?></span>
                <?php } else { ?>
                  <span class="cluevo-content-status-value cluevo-content-status-access-denied"> <?php esc_html_e(" Access Denied", "cluevo"); ?>
                  <?php } ?>
              </div>
              <?php if (!empty($item->expires) && $item->expires > time()) { ?>
                <div class="cluevo-status-expire-time"><?php echo esc_html(sprintf(__("Access until: %s", "cluevo"), wp_date($format, $item->expires))); ?></div>
              <?php } ?>
            </div>
          <?php } ?>
        </div>
        <div class="cluevo-progress-container">
          <span class="cluevo-progress" style="width: <?php echo esc_attr(100 - $progressWidth); ?>%;" data-value="<?php echo esc_attr($progressValue); ?>" data-max="<?php echo esc_attr($progressMax); ?>"></span>
        </div>
        <?php if (!$blocked) { ?>
        </a>
      <?php } else { ?>
</div>
<?php } ?>


</div>
