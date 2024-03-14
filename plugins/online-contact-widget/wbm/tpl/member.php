<?php

/**
 * 
 */

if (!empty($cnf['load_theme_header'])) {
  do_action('wbm_head_' . $this->module);
  get_header();
  echo '<div class="wbm-page">';
} else {
  include __DIR__ . '/header.php';
}
?>
<div class="wbm-container wbm-pw">
  <div class="wbm-side">
    <ul class="wbm-side-nav">
      <?php do_action('wbm_get_menu'); ?>
    </ul>
    <?php do_action('wbm_left'); ?>
  </div>

  <div class="wbm-main">
    <?php do_action('wbm_content'); ?>
  </div>
</div>

<?php
if (!empty($cnf['load_theme_footer'])) {
  echo  '</div>'; // div.wbc-page
  do_action('wbm_footer_' . $this->module);
  get_footer();
} else {
  include __DIR__ . '/footer.php';
}
