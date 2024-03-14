<div class="cluevo-content-list">
<?php
$cluevo = cluevo_get_cluevo_lms();
while (cluevo_have_lms_dependencies()) {
  $item = cluevo_the_lms_dependency();
  cluevo_display_template('part-tree-item');
?>
<?php } ?>
</div>
