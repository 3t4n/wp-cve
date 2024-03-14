<nav id="quadmenu" class="<?php echo esc_attr($args->navbar_class); ?>" data-template="collapse" data-theme="<?php echo esc_attr($args->theme); ?>" data-unwrap="<?php echo esc_attr($args->unwrap); ?>" data-width="<?php echo esc_attr($args->layout_width); ?>" data-selector="<?php echo esc_attr($args->layout_width_inner_selector); ?>" data-breakpoint="<?php echo esc_attr($args->layout_breakpoint); ?>" data-sticky="<?php echo esc_attr($args->layout_sticky); ?>" data-sticky-offset="<?php echo esc_attr($args->layout_sticky_offset); ?>">
  <div class="quadmenu-container">
    <div class="quadmenu-navbar-header">
      <?php quadmenu_get_template('button/toggle.php', array('target' => '#' . $args->target_id)); ?>
      <?php quadmenu_get_template('logo.php', array('url' => isset($args->navbar_logo['url']) ? $args->navbar_logo['url'] : false, 'link' => $args->navbar_logo_link)); ?>
    </div>
    <div id="<?php echo esc_attr($args->target_id); ?>" class="quadmenu-navbar-collapse collapse">
      <?php echo $args->menu_items; ?>
    </div>
  </div>
</nav>