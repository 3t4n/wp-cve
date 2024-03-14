<div style='display:flex;align-items: center;'>
    <div class="navhead is-<?php echo esc_attr($size);?> <?php echo esc_attr($has_image); ?>">
        <?php echo esc_attr($image_html); ?>
        <strong><?php echo esc_attr($name); ?></strong>
    </div>
    <?php
    $argsmain = array(
        'menu'              => $nav,
        'menu_class'    => 'header-nav header-nav-main nav nav-right nav-'.$style.' nav-'.$uppercase.' nav-size-'.$size.' nav-spacing-'.$spacing." ".$class,
        'container'      => false,
        'items_wrap'        => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        'walker'         => new $walker(),
        'add_li_class'  => '',
    );
    wp_nav_menu($argsmain);
    ?>
    <style type="text/css">#<?php echo esc_attr($id); ?> .header-nav{width: unset; margin-left: auto;}</style>
</div>