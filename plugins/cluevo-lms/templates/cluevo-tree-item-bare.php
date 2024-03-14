<div class="cluevo-content-list">
<?php
if(cluevo_have_lms_items()) {
  while (cluevo_have_lms_items()) {
    cluevo_the_lms_item();
    cluevo_display_template('part-tree-item');
  }
} else { ?>
  <div class="cluevo-notice cluevo-notice-info">
    <p class="cluevo-notice-title"><?php esc_html_e("Notice", "cluevo"); ?></p>
    <p>
      <?php esc_html_e("The course index is empty.", "cluevo"); ?>
    </p>
  </div>
<?php } ?>
</div>
