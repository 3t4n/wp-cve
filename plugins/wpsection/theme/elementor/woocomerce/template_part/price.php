
<?php if (isset($settings['show_product_price']) && $settings['show_product_price']) { ?>
    <?php if (!get_post_meta(get_the_id(), 'meta_show_price', true)) : ?>
        <div class="wps_order order-<?php echo $settings['position_order_three']; ?> ">
            <div class="mr_shop_price price">
                <?php echo $price_html; ?>
            </div>
        </div>
    <?php endif; ?>
<?php } ?>

