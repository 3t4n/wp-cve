<div class="cluevo-back-link-container">
<?php cluevo_display_the_content_list_style_switch(); ?>
</div>
<div class="cluevo-content-list <?php cluevo_the_content_list_style(); ?>">
<?php
if (cluevo_have_lms_items() && cluevo_have_visible_lms_items()) {
  while (cluevo_have_lms_items()) {
    cluevo_the_lms_item();
    cluevo_display_template('part-tree-item');
  }
} else {
  if (current_user_can('administrator')) {
    cluevo_display_notice(
      __("Notice", "cluevo"),
      __("The course index is empty. You can add courses through the admin area.", "cluevo")
    );
  } else {
    cluevo_display_notice(
      __("Notice", "cluevo"),
      __("The course index is empty or you do not have the required permissions to access this page", "cluevo")
    );
  }
}
?>
</div>
