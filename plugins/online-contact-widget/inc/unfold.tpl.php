<?php

/**
 * 浮标和移动端模式
 */
$unfold_mode_class = ' ' . $position;
$unfold_mode_class .= wp_is_mobile() ? ' ocw-mobile' : '';
$unfold_mode_class .= $dark_class;
?>
<div class="wb-ocw ocw-buoy<?php echo $unfold_mode_class; ?>">
  <div class="ocw-buoy-item">
    <div class="ocw-buoy-btn">
      <i class="ocw-point"></i>

      <?php
      $buoy_icon_custom = isset($opt['buoy_icon_custom']) ? $opt['buoy_icon_custom'] : '';
      if ($fold_icon == 6 && $buoy_icon_custom) : ?>
        <img class="ocw-wb-icon" src="<?php echo $buoy_icon_custom; ?>" alt="">
      <?php else : ?>
        <svg class="ocw-wb-icon ocw-buoy-<?php echo $fold_icon; ?>">
          <use xlink:href="#ocw-buoy-<?php echo $fold_icon; ?>"></use>
        </svg>
      <?php endif; ?>

      <?php if ($fold_label && $name_switch == 1) : ?>
        <span class="ocw-btn-text"><?php echo $fold_label; ?></span>
      <?php endif; ?>
    </div>

    <?php if ($is_fold == 1 && isset($opt['buoy_animation']) && $opt['buoy_animation'] > 0) : ?>
      <div class="ocw-animated-circles">
        <div class="ocw-circle c-1"></div>
        <div class="ocw-circle c-2"></div>
        <div class="ocw-circle c-3"></div>
      </div>
    <?php endif; ?>

    <?php
    /**
     * 展开面板
     */
    if (wp_is_mobile()) {
      echo '<div class="ocw-mask"></div>';
    }

    include_once ONLINE_CONTACT_WIDGET_PATH . '/inc/panel.tpl.php';
    ?>
  </div>
</div>