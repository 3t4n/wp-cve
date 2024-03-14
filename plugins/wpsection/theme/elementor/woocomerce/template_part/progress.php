
<?php if ($stock_quantity) : ?>
    <?php
    $sale_percentage = ($sale_stock_quantity / $stock_quantity) * 100;
    if ($sale_percentage < $settings['level_one']) {
        $bar_class = 'border-green';
    } elseif ($sale_percentage >= $settings['level_two']) {
        $bar_class = 'border-red';
    } elseif ($sale_percentage >= $settings['level_one']) {
        $bar_class = 'border-yellow';
    }
    ?>

    <?php if (isset($settings['show_product_progress']) && $settings['show_product_progress']) : ?>
        <?php if (!get_post_meta(get_the_id(), 'meta_show_progress', true)) : ?>
            <div class="wps_order order-<?php echo $settings['position_order_four']; ?> ">
                <div class="mr_product_progress ">
                    <div class="product-single-item-bar">
                        <span class="<?php echo esc_attr($bar_class); ?>" style="max-width:100%!important; width: <?php echo esc_attr($sale_percentage); ?>%"></span>
                    </div>

                    <div class="product-single-item-sold">
                        <p><?php echo $settings['sold_text']; ?><span><?php echo esc_html($sale_stock_quantity); ?> / <?php echo esc_html($stock_quantity); ?></span></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>

