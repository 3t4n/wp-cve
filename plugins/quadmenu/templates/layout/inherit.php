<nav id="quadmenu" class="<?php echo esc_attr($args->navbar_class); ?>" data-template="inherit" data-theme="<?php echo esc_attr($args->theme); ?>" data-unwrap="<?php echo esc_attr($args->unwrap); ?>" >
  <div class="quadmenu-container">
    <div id="<?php echo esc_attr($args->target_id); ?>">
      <?php quadmenu_get_template('logo.php', array('url' => isset($args->navbar_logo['url']) ? $args->navbar_logo['url'] : false, 'link' => $args->navbar_logo_link)); ?>
      <?php echo $args->menu_items; ?>
    </div>
  </div>
</nav>