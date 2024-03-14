<?php

$detect = new \WpSaioMobileDetect();

$position = esc_attr(get_option('wpsaio_widget_position', 'right'));

$bottom = esc_attr(get_option('wpsaio_bottom_distance', 30));

$show_on_desktop = esc_attr(get_option('wpsaio_show_on_desktop', 1));

$show_on_mobile = esc_attr(get_option('wpsaio_show_on_mobile', 1));

$displayCondition = esc_attr(get_option('wpsaio_display_condition', 'allPages'));

$button_image = esc_attr(get_option('wpsaio_button_image', 'contain'));

// $button_color = esc_attr(get_option('wpsaio_button_image', '#007cc4'));

$include_pages = get_option('wpsaio_includes_pages', []);

$exclude_pages = get_option('wpsaio_excludes_pages', []);

if ($detect->isMobile() && !$show_on_mobile || !$detect->isMobile() && !$show_on_desktop || (!$show_on_mobile && !$show_on_desktop)) {
    return;
}

wp_reset_postdata();

$current_ID = get_the_ID();

if (!is_front_page() && is_home()) {
    $current_ID = get_option('page_for_posts');
}

if(function_exists( 'WC' ) && is_woocommerce() && is_shop()) {
    $current_ID = get_option('woocommerce_shop_page_id');
}

if ($displayCondition === 'includePages') {
    if (empty($include_pages) || (empty($include_pages) && empty($include_pages[0]))) {
        if ( ! function_exists( 'get_current_screen' ) ){
            return;
        } else {
            $current_screen = get_current_screen();
        }
        if($current_screen->parent_base === ""){
        // No options chosen, don't display
            return;
        } 
    }

    if (!empty($include_pages) && !in_array($current_ID, $include_pages)) {
        if ( ! function_exists( 'get_current_screen' ) ){
            return;
        } else {
            $current_screen = get_current_screen();
        }
        if($current_screen->parent_base === ""){
        // No options chosen, don't display
            return;
        } 
    }
} elseif ($displayCondition === 'excludePages') {
    if (!empty($exclude_pages) && in_array($current_ID, $exclude_pages)) {
        return;
    }
}

?>

<div id="wp-nt-aio-wrapper" data-bottom="<?php echo $bottom ?>" data-position="<?php echo $position ?>">
    <div class="nt-aio-active js__nt_aio_active" data-icon="<?php echo esc_attr($btn_icon); ?>"></div>
    <!-- /.nt-aio-active js__nt_aio_active -->
    <div class="nt-aio-content">
        <?php echo $buttons; ?>
    </div><!-- /.nt-aio-content -->
    <?php echo $contents; ?>
</div>
<style>
#wp-nt-aio-wrapper {
    --backgroundColor: <?php echo $button_image === 'cover' ? 'transparent' : (esc_attr($icon_bg_color) ? esc_attr($icon_bg_color) : '#007cc4'); ?>;
    --backgroundColorAfter: <?php echo esc_attr($icon_bg_color) ? esc_attr($icon_bg_color) : '#007cc4' ?>
}

 /* #wp-nt-aio-wrapper .nt-aio-active {
    background-color: var(--backgroundColor);
} */

#wp-nt-aio-wrapper .nt-aio-active:before {
   background-color: var(--backgroundColor);
}

#wp-nt-aio-wrapper .nt-aio-active:after {
    background-color: var(--backgroundColorAfter);
}

</style>
<?php if(!empty($btn_icon)) { ?>
      <style>
      #wp-nt-aio-wrapper {
          --backgroundIcon: url(<?php echo esc_attr($btn_icon); ?>);
          --backgroundSize: <?php echo esc_attr($btn_image) === 'cover' ? 'cover' : '60%' ?>;
        }
      </style>
<?php }; ?>
<!-- /#wp-nt-aio-wrapper -->