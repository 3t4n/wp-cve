<nav id="quadmenu" class="<?php echo esc_attr($args->navbar_class); ?>" data-template="vertical" data-theme="<?php echo esc_attr($args->theme); ?>" data-unwrap="<?php echo esc_attr($args->unwrap); ?>" data-breakpoint="<?php echo esc_attr($args->layout_breakpoint); ?>">
  <?php quadmenu_get_template('button/toggle.php', array('target' => '#' . $args->target_id)); ?>
  <div id="<?php echo esc_attr($args->target_id); ?>" class="navbar-offcanvas">
    <?php quadmenu_get_template('logo.php', array('url' => isset($args->navbar_logo['url']) ? $args->navbar_logo['url'] : false, 'link' => $args->navbar_logo_link)); ?>
    <?php echo $args->menu_items; ?>
  </div>
</nav>