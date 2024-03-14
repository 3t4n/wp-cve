<?php
global $cluevo_module;
$module = cluevo_get_the_lms_module();
$meta = cluevo_the_lms_module_metadata();
$user = cluevo_get_the_lms_user();
$displayMode = strtolower(get_option("cluevo-modules-display-mode", "Iframe"));
$tileMode = strtolower(get_option("cluevo-display-diagonal-tiles", "off"));
$diagonal = ($tileMode === "on") ? "diagonal" : "";
if (!empty($user)) {
  $progressValue = cluevo_get_users_best_module_attempt($user->ID, $module->module_id);
  $progressMax = 1;
}
?>
<div class="cluevo-content">

<a class="cluevo-content-title <?php echo esc_attr($diagonal); ?> cluevo-module-link cluevo-module-mode-<?php echo esc_attr($displayMode); ?>" href="<?php if( !empty($meta)) { echo get_permalink($meta->ID); } ?>"><?php echo esc_html(((!empty($meta->post_title)) ? $meta->post_title : $module->module_name)); ?></a>
<a class="cluevo-content-item-link cluevo-module-link cluevo-module-mode-<?php echo esc_attr($displayMode); ?>" href="<?php if (!empty($meta)) { echo get_permalink($meta->ID); } ?>">
    <div class="cluevo-post-thumb">
        <div class="cluevo-meta-bg"></div>
        <div class="cluevo-meta-container"></div>
      <?php if (!empty($img)) { echo wp_kses($img, ["img" => ["src" => 1, "alt" => 1, "class" => 1]]); } else { echo '<img src="' . esc_url(plugins_url('images/lms-content-placeholder.jpg', dirname(__FILE__, 1)), ['http', 'https']) . '" alt="" />'; } ?>
    </div>
    <?php //if (!empty($meta->post_excerpt)) { ?>
    <div class="cluevo-content-container">
      <div class="cluevo-description"><?php echo (!empty($meta->post_excerpt)) ? esc_html($meta->post_excerpt) : "&nbsp;"; ?></div>
    </div>
    <?php //} ?>
    <div class="cluevo-progress-container">
      <span class="cluevo-progress" style="width: <?php echo esc_attr(100 - $progressWidth); ?>%;" data-value="<?php echo esc_attr($progressValue); ?>" data-max="<?php echo esc_attr($progressMax); ?>"></span>
    </div>
  </a>
</div>
