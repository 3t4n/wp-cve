<div class="cluevo-content-list">
<?php
while (cluevo_have_lms_items()) {
  cluevo_the_lms_item();
  cluevo_display_template('part-tree-item');
?>
<?php } ?>
</div>
