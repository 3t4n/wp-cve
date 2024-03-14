<?php
if (cluevo_can_user_access_item()) {
  $curItem = cluevo_get_the_lms_page();
  $curItem->load_settings();
  $iframePos = $curItem->get_setting('iframe-position');
  $module = null;
  $displayMode = $curItem->display_mode;
  if (empty($iframePos)) {
    $iframePos = strtolower(get_option("cluevo-modules-display-position", "end"));
  }
  if (empty($displayMode)) {
    $displayMode = strtolower(get_option("cluevo-modules-display-mode", "lightbox"));
  }
  do_action('cluevo_enqueue_module_scripts');
  $module = null;
  if ($curItem->module_id >= 0) {
    $module = cluevo_get_module($curItem->module_id);
  }
  $next = cluevo_get_the_next_lms_item();
  $prev = cluevo_get_the_previous_lms_item();
  $parentPost = cluevo_get_the_parent_lms_page();
  if ($parentPost) { ?>
    <div class="cluevo-back-link-container">
      <?php cluevo_display_template('cluevo-part-breadcrumbs'); ?>
    </div>
    <?php if ($displayMode === 'iframe' && $iframePos === 'start') { ?>
      <?php if (!empty($module)) { ?>
        <div class="cluevo-module-container">
          <?php if (is_object($module)) do_action('cluevo_display_module', ["item" => $curItem, "module" => $module]); ?>
        </div>
    <?php } else {
        if (empty(get_the_content())) {
          cluevo_display_notice(__("Notice", "cluevo"), __("This module does not seem to exist.", "cluevo"), 'error');
        }
      }
    } ?>
  <?php }
  if (have_posts()) {
    the_post();
  ?>
    <div class="cluevo-item-metadata">
      <?php the_content(); ?>
    </div>
  <?php } ?>
  <?php if (!empty($module) && $displayMode !== 'iframe') { ?>
    <div class="cluevo-module-start-button cluevo-detailed">
      <?php do_action("cluevo_display_detailed_module_start_link", $curItem, __("Start Module", "cluevo")); ?>
    </div>
  <?php } ?>
  <?php if (!empty($module) && $displayMode === 'iframe' && ($iframePos === 'end' || empty($iframePos))) { ?>
    <div class="cluevo-module-container">
      <?php if (is_object($module)) do_action('cluevo_display_module', ["item" => $curItem, "module" => $module]); ?>
    </div>
    <?php if (!empty($next) || !empty($prev)) { ?>
      <div class="cluevo-module-nav">
        <?php if (!empty($prev)) { ?>
          <a class="cluevo-module-link cluevo-module-link-prev" href="<?php echo get_permalink($prev->metadata_id); ?>">◄ <?php echo esc_html($prev->name); ?></a>
        <?php } ?>
        <?php if (!empty($next)) { ?>
          <a class="cluevo-module-link cluevo-module-link-next" href="<?php echo get_permalink($next->metadata_id); ?>"><?php echo esc_html($next->name); ?> ►</a>
        <?php } ?>
      </div>
    <?php } ?>
  <?php } ?>
<?php
} else {
  cluevo_display_notice(__("Notice", "cluevo"), __("You do not have the required permissions to access this page.", "cluevo"), 'error');
} ?>
