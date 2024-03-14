<?php
$item = cluevo_get_the_lms_page();
?>
<div class="cluevo-scorm-error access">
  <h1><?php esc_html_e("Error", "cluevo"); ?></h1>
  <p><?php esc_html_e("You do not meet the requirements needed to access this content", "cluevo"); ?></p>
  <?php if (!$item->access_status['dependencies']) { ?>
    <p><?php esc_html_e("Complete the following items to gain access.", "cluevo"); ?></p>
    <?php cluevo_display_template('part-dependency-list'); ?>
    <ul class="cluevo-module-dependencies">
      <?php $deps = cluevo_get_multiple_learning_structure_items(array_keys($item_ > dependencies["all"]), get_current_user_id()); ?>
      <?php foreach ($deps as $dep) { ?>
        <li><a href="<?php echo get_permalink($dep->metadata_id); ?>"><?php echo esc_html($dep->name); ?></a></li>
      <?php } // foreach 
      ?>
    </ul>
  <?php } // if (dep) 
  ?>
  <?php if (!$item->access_status['points']) { ?>
    <p><?php esc_html_e("You do not have the required points to access this content.", "cluevo"); ?></p>
  <?php } ?>
  <?php if (!$item->access_status['level']) { ?>
    <p><?php esc_html_e("You have not reached the required level to access this content.", "cluevo"); ?></p>
  <?php } ?>
</div>
