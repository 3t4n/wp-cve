<nav id="quadmenu" class="<?php echo esc_attr($args->navbar_class); ?> quadmenu-is-embed" data-template="embed" data-theme="<?php echo esc_attr($args->theme); ?>" data-unwrap="<?php echo esc_attr($args->unwrap); ?>" data-breakpoint="<?php echo esc_attr($args->layout_breakpoint); ?>">
  <div class="quadmenu-container">
    <div id="<?php echo esc_attr($args->target_id); ?>" class="quadmenu-navbar-collapse collapsed in">
      <?php echo $args->menu_items; ?>
    </div>
  </div>
</nav>