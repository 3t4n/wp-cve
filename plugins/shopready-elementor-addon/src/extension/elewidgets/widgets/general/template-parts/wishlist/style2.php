<?php
if (!defined('ABSPATH')) {
    exit;
}
/*
 * WishList Layout: Layout Two
 */

if ($settings['show_heading'] == 'yes') {
    echo wp_kses_post(sprintf("<h3 class='woo-ready-wishlist-title'>%s</h3>", esc_html($settings['wready_wishlist_heading'])));
}
echo wp_kses_post(sprintf("<div class='wishlist-product-grid display:grid grid-template-columns-%s'>", esc_attr($settings['wooready_wishlist_grid_layout_column'])));



foreach ($products as $product):

    $product_image = $product->get_image();
    $product_price = wc_price($product->get_price());
    $product_name = wp_trim_words($product->get_name(), $settings['product_title_crop'], '');
    $product_permalink = $product->get_permalink();
    $addToCartUrl = $product->add_to_cart_url();
    $rating_count = $product->get_rating_count();
    $review_count = $product->get_review_count();
    $average = $product->get_average_rating();
    $category_list = wc_get_product_category_list($product->get_id());
    $regular_price = $product->get_regular_price() ? wc_price($product->get_regular_price()) : null;
    $sale_price = $product->get_sale_price() ? wc_price($product->get_sale_price()) : null;

    $percentage_sale = esc_html__('Sale', 'shopready-elementor-addon');
    if (is_numeric($product->get_sale_price()) && is_numeric($product->get_regular_price())) {
        $percentage_sale = number_format((float) ($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price() * 100, 1, '.', '') . '% OFF';
    }


    ?>

    <div class="wooready_product_components">
        <div class="wooready_product_layout_two wooready_product_layout_wrapper">
            <div class="wooready_product_thumb text-align:center position:relative  overflow:hidden overflow:hidden">
                <?php echo wp_kses_post($product_image);

                if ($product->is_on_sale()) { ?>
                    <span class="wooready_sell_discount position:absolute top:15 left:0"><?php echo wp_kses_post($percentage_sale); ?></span>
                <?php } ?>

                <?php if (apply_filters('shop_ready_product_quick_view_enable', false)): ?>
                    <div
                        class="wooready_product_cart_box display:flex justify-content:center align-items:center position:absolute right:10 bottom:10">
                        <div class="wooready_product_popup se-quickview">
                            <a href="#" data-product-type='<?php echo esc_attr($product->get_type()); ?>'
                                data-product_id='<?php echo esc_attr($product->get_id()); ?>' class='wready-product-quickview'>
                                <?php echo wp_kses_post($quick_view_icon); ?></i></a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="wooready_product_content_box  text-align:left position:relative">
                <div class="wooready_product_category">
                    <?php echo wp_kses_post($category_list); ?>
                </div>
                <div class="wooready_title  order:2">
                    <h3 class="title"><a href="<?php echo esc_url($product_permalink); ?>"><?php echo wp_kses_post($product_name); ?></a>
                    </h3>
                </div>
                <div class="wooready_review display:flex justify-content:space-between text-align:center flex-basis:100 ">
                    <?php if (!is_null($regular_price) || !is_null($sale_price)) { ?>
                        <div class="wooready_price_box">
                            <?php if (!is_null($regular_price)) { ?>
                                <span class="wooready_price_normal">
                                    <?php echo wp_kses_post($regular_price); ?>
                                </span>
                            <?php }
                            if (!is_null($sale_price)) { ?>
                                <span class="wooready_price_discount">
                                    <?php echo wp_kses_post($sale_price); ?>
                                </span>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php echo sprintf('<ul class="wooready_review_box display:inline-block">');
                    foreach (range(0, 4) as $number) {
                        if ($number < $average) {
                            echo sprintf(' <li><i class="fa fa-star"></i></li>');
                        } else {
                            echo sprintf(' <li><i class="wrinactive fa fa-star"></i></li>');
                        }
                    }
                    echo wp_kses_post('</ul>'); ?>
                </div>
                <div class="wooready_product_cart_box">
                    <div class="wooready_product_cart">
                        <a href="<?php echo esc_url($addToCartUrl); ?>">
                            <?php echo wp_kses_post($settings['addtocart_text']); ?>
                            <?php echo wp_kses_post($add_to_cart_icon); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>

</div>