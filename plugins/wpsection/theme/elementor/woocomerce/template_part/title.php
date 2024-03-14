
 <?php if (isset($settings['show_product_title']) && $settings['show_product_title']) { ?>
    <?php if (!get_post_meta(get_the_id(), 'meta_show_title', true)) : ?>
        <div class="wps_order order-<?php echo $settings['position_order_one']; ?>">
            <div class="mr_product_title">
                <a href="<?php echo esc_url(get_the_permalink(get_the_ID())); ?>"><?php do_action('woocommerce_shop_loop_item_title'); ?></a>
            </div>
        </div>
    <?php endif; ?>
<?php } ?>
