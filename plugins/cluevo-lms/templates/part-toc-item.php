<?php if ($item) { ?>
<?php
$item->load_settings();
$blocked = ($item->access_level < 2 || !$item->access) ? "blocked" : "";
$blockAnyway = false;
if (!empty($item->children)) {
  $blockAnyway = true;
  foreach ($item->children as $c) {
    if (!empty($c->access) || (empty($c->access) && $c->access_status["access_level"] == 1)) {
      $blockAnyway = false;
      break;
    }
  }
}
$displayMode = cluevo_get_the_items_module_display_mode($item->item_id);
$progressMax = cluevo_get_item_progress_max($item->item_id);
$progressValue = cluevo_get_item_progress_value($item->item_id);
$progressWidth = cluevo_get_item_progress_width($item->item_id);
$link = $item->get_setting("item-is-link");
if (!empty($link) && is_string($link)) {
  $link = trim($link);
}
$isLink = (!empty($link)) ? true : false;
$newWin = ($item->get_setting("open-link-in-new-window") == 1) ? true : false;
if (empty($link)) $link = get_permalink($item->metadata_id);

$module = null;
if (!empty($item->module) && $item->module > 0) {
  if ($item->module) {
    $module = cluevo_get_module($item->module);
    do_action('cluevo_enqueue_module_scripts');
  }
}
?>
  <details
    class="cluevo-toc-item <?php if ($blocked) echo "disabled";?>"
    <?php echo cluevo_get_item_data_string($item); ?>
    <?php if (cluevo_toc_item_is_open($item->item_id, $item->level)) echo "open"; ?>
  >
  <summary
    class="cluevo-toc-item-summary"
    <?php if ($blocked || $blockAnyway) echo 'onclick="return false;"'; ?>
  >
    <div class="cluevo-toc-item-summary-content">
        <div class="cluevo-progress-container">
          <span
            class="cluevo-progress"
            style="width: <?php echo 100 - $progressWidth; ?>%;"
            data-value="<?php echo esc_attr($progressValue);?>"
            data-max="<?php echo esc_attr($progressMax); ?>"
          ></span>
        </div>
      <div class="cluevo-toc-item-title"><?php echo esc_html($item->name); ?></div>
      <div class="cluevo-toc-item-tools">
        <?php if (!empty($item->rating_avg)) { ?>
          <?php if (cluevo_toc_show_rating_stars()) { ?>
            <?php for ($i = 0; $i < 5; $i++) { ?>
              <?php if ($item->rating_avg["value"] <= $i) { ?>
              <img src="<?php echo cluevo_get_conf_const('CLUEVO_IMAGE_URL') . 'star-empty.png'; ?>" />
            <?php } elseif ($item->rating_avg["value"] - $i >  0.5) { ?>
              <img src="<?php echo cluevo_get_conf_const('CLUEVO_IMAGE_URL') . 'star-filled.png'; ?>" />
            <?php } else { ?>
              <img src="<?php echo cluevo_get_conf_const('CLUEVO_IMAGE_URL') . 'star-half.png'; ?>" />
            <?php } ?>
          <?php } ?>
        <?php } ?>
          <?php if (cluevo_toc_show_rating_value()) { ?>
            <?php echo number_format($item->rating_avg["value"], 2); ?>
          <?php } ?>
        <?php } ?>
        <?php if (!cluevo_toc_hide_meta()) do_action("cluevo_part_lms_item_meta", $item); ?>
          <?php if (!cluevo_toc_hide_count() && $item->type !== 'module') { ?>
          <div class="cluevo-module-status cluevo-meta-item">
            <?php if (count($item->children) > 0) echo count($item->completed_children) . " / " . count($item->children) ; ?>
          </div>
          <?php } ?>
      <?php if (!cluevo_toc_hide_icons()) { ?>
        <div class="cluevo-meta-item cluevo-access"><?php if ($item->access) { ?><i class="fas fa-unlock"></i><?php } else { ?><i class="fas fa-lock"></i> <?php } ?></div>
        <div class="cluevo-meta-item"><?php if (!empty($item->completed) && $item->completed) { ?><i class="fas fa-check"></i><?php } ?></div>
      <?php } ?>
      <?php if (!cluevo_toc_hide_icons() && !empty($item->module) && $item->module > 0) { ?>
        <a
          class="cluevo-content-item-link <?php if ($blockAnyway) echo "no-children-accessible"; ?> <?php echo ($module === null && $item->type == "module") ? "cluevo-empty-module" : ""; ?> <?php echo esc_attr($item->type); ?> <?php echo esc_attr($blocked); ?> <?php if (!empty($item->module) && $item->module > 0 && $module !== null) { echo "cluevo-module-link cluevo-module-mode-" . esc_attr($displayMode); } ?>"
          <?php if ($isLink && $newWin) echo 'target="_blank"'; ?>
          href="<?php echo esc_attr($link); ?>"
          data-item-id="<?php echo esc_attr($item->item_id); ?>"
          <?php echo cluevo_get_item_data_string($item); ?>
          data-module-id="<?php echo (!empty($item->module_id)) ? $item->module_id : 0; ?>"
          <?php if ($item->get_setting('hide-lightbox-close-button') == 1) echo "data-hide-lightbox-close-button=\"1\""; ?>
          data-module-type="<?php echo esc_attr(strtolower( ((!empty($module->type_name)) ? $module->type_name : "" ))); ?>"
        >
          <span class="dashicons dashicons-external"></span>
        </a>
      <?php } else { ?>
        <a
            href="<?php echo esc_attr($link); ?>"
          <?php if ($isLink && $newWin) echo 'target="_blank"'; ?>
        >
          <span class="dashicons dashicons-admin-links"></span>
        </a>
      <?php } ?>
        <?php if (!cluevo_toc_hide_icons() && !empty($item->module_id) && !empty($module)) { ?>
          <div class="cluevo-badge cluevo-module-type"><?php do_action("cluevo_module_icon_" . sanitize_key($module->type_name), $module); ?></div>
        <?php } ?>
      </div>
    </div>
    </summary>
    <div class="cluevo-toc-item-content">
      <?php $excerpt = has_excerpt($item->metadata_id) ? get_the_excerpt($item->metadata_id) : '' ?>
      <?php if (!empty($excerpt)) {
        echo "<div class=\"cluevo-toc-excerpt\">$excerpt";
        echo "<br /><a href=\"" . get_permalink($item->metadata_id) . "\" class=\"cluevo-more-link\">" . esc_html__("Open Element", "cluevo") . "</a>";
        echo "</div>";
      } ?>
      <?php if (!empty($item->module) && (int)$item->module > 0) { ?>
        <a
          class="cluevo-btn cluevo-content-item-link <?php if ($blockAnyway) echo "no-children-accessible"; ?> <?php echo ($module === null && $item->type == "module") ? "cluevo-empty-module" : ""; ?> <?php echo esc_attr($item->type); ?> <?php echo esc_attr($blocked); ?> <?php if (!empty($item->module) && $item->module > 0 && $module !== null) { echo "cluevo-module-link cluevo-module-mode-$displayMode"; } ?>"
          <?php if ($isLink && $newWin) echo 'target="_blank"'; ?>
          href="<?php echo esc_attr($link); ?>"
          data-item-id="<?php echo esc_attr($item->item_id); ?>"
          <?php echo cluevo_get_item_data_string($item); ?>
          data-module-id="<?php echo (!empty($item->module_id)) ? esc_attr($item->module_id) : 0; ?>"
          <?php if ($item->get_setting('hide-lightbox-close-button') == 1) echo "data-hide-lightbox-close-button=\"1\""; ?>
          data-module-type="<?php echo esc_attr(strtolower( ((!empty($module->type_name)) ? $module->type_name : "" ))); ?>"
        >
          <?php esc_html_e("Start Module", "cluevo"); ?>
        </a>
      <?php } elseif ($isLink) { ?>
        <a
          class="cluevo-btn cluevo-content-item-link"
            href="<?php echo esc_attr($link); ?>"
          <?php if ($isLink && $newWin) echo 'target="_blank"'; ?>
        >
          <?php esc_html_e("Open Link", "cluevo"); ?>
        </a>
      <?php } ?>
  <?php if (!empty($item->children)) { ?>
    <?php foreach ($item->children as $index => $item) { ?>
      <?php
      if (!empty($tpl)) {
        include($tpl);
      }
      ?>
    <?php } ?>
  <?php } ?>
  </div>
</details> <!-- /cluevo-toc-item -->
<?php } ?>
