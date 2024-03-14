<?php
$curItem = cluevo_get_the_lms_page();
$parentPost = cluevo_get_the_parent_lms_page();
$displayMode = strtolower(get_option("cluevo-modules-display-mode", "lightbox"));
$module = null;
$cluevoListStyle = !empty($_COOKIE["cluevo-content-list-style"]) ? sanitize_key($_COOKIE["cluevo-content-list-style"]) : null;
$cluevoValidListStyles = ["row", "col"];
$cluevoListStyle = (in_array($cluevoListStyle, $cluevoValidListStyles)) ? $cluevoListStyle : "col";

if ($displayMode == "iframe" && $curItem->type == 'module' || (!empty($curItem->module_id) && $curItem->module_id > 0)) {
  do_action('cluevo_enqueue_module_scripts');
  $module = cluevo_get_the_items_module();
} ?>
<?php if ($parentPost) { ?>
  <div class="cluevo-back-link-container">
    <?php cluevo_display_template('cluevo-part-breadcrumbs'); ?>
  </div>
<?php } ?>

<?php
if (have_posts()) {
  the_post();
?>
  <div class="cluevo-item-metadata">
    <?php the_content(); ?>
  </div>
<?php } ?>
<div class="cluevo-content-grid">
  <?php
  ?>

  <div class="cluevo-back-link-container">
    <?php if (cluevo_have_lms_items() && (empty($curItem->module_id) || $curItem->module_id < 1)) { ?>
      <?php cluevo_display_the_content_list_style_switch(); ?>
    <?php } ?>
  </div>

  <?php
  if (cluevo_can_user_access_item()) {
    if (cluevo_have_lms_items() && (empty($curItem->module_id) || $curItem->module_id < 1)) {
  ?>

      <div class="cluevo-content-list <?php cluevo_the_content_list_style(); ?>">
        <?php if (cluevo_have_visible_lms_items()) { ?>
        <?php while (cluevo_have_lms_items()) {
            cluevo_the_lms_item();
            if (cluevo_the_lms_item_is_visible()) {
              cluevo_display_template('part-tree-item');
            }
          }
        } else {
          if (!cluevo_get_the_lms_items_hide_info_box_setting() && cluevo_get_the_display_empty_item_message_setting()) {
            cluevo_display_notice(__("Notice", "cluevo"), cluevo_get_the_lms_items_empty_text(), 'info');
          }
        }
        ?>
      </div>

    <?php } else { ?>
      <?php if (!empty($curItem->module_id) && $curItem->module_id > 0) { ?>
        <?php if (!empty($module)) { ?>
          <div class="cluevo-module-container">
            <?php do_action('cluevo_display_module', ["item" => $curItem, "module" => $module]); ?>
          </div>
        <?php } else {
          cluevo_display_notice(__("Notice", "cluevo"), __("This module does not seem to exist.", "cluevo"), 'error');
        } ?>
  <?php } else {
        if (!$curItem->has_content() && !cluevo_get_the_lms_items_hide_info_box_setting() && cluevo_get_the_display_empty_item_message_setting()) {
          cluevo_display_notice(__("Notice", "cluevo"), cluevo_get_the_lms_items_empty_text(), 'info');
        }
      }
    }

    // If comments are open or we have at least one comment, load up the comment template.
    if (comments_open() || get_comments_number()) {
      comments_template();
    }
  } else {
    cluevo_display_notice(__("Notice", "cluevo"), cluevo_get_the_lms_items_access_denied_text(), 'error');
  } ?>
</div>
