<?php
if (!defined('ABSPATH')) {
    exit;
}
use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;
use Shop_Ready\helpers\classes\WooCommerce_Product as Wready_Utils;

/*
 * Product Slider Default Layout
 */

foreach ($products as $product):

    $product_image = $product->get_image($image_size, ['loading' => 'eager']);
    $product_price = wc_price($product->get_price());
    $product_name = wp_trim_words($product->get_name(), $settings['post_title_crop'], '');
    $product_permalink = $product->get_permalink();
    $addToCartUrl = $product->add_to_cart_url();
    $rating_count = $product->get_rating_count();
    $review_count = $product->get_review_count();
    $average = $product->get_average_rating();
    $is_featured = $product->get_featured();
    $total_sales = $product->get_total_sales();
    $percentage_sale = $sale_text;

    if ((is_numeric($product->get_sale_price()) && !empty($product->get_sale_price())) && (is_numeric($product->get_regular_price()) && !empty($product->get_regular_price()))) {
        $percentage_sale = number_format((float) ($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price() * 100, 1, '.', '') . '%';
    }

    ?>

<div class="wooready-slider-product-layout srt---iop">
    <div class="product-thumb">
        <?php echo wp_kses_post($product_image);
            if ($product->is_on_sale()) { ?>
        <div class="discount">
            <?php echo wp_kses_post($percentage_sale); ?>
        </div>
        <?php } ?>
    </div>
    <div class="product-details">
        <?php if ($product->get_rating_count()): ?>
        <div class="sr-review-rating">
            <?php echo wp_kses_post(wc_get_rating_html($product->get_average_rating()) . ' ' . $product->get_rating_count()); ?>
        </div>
        <?php endif; ?>
        <h5>
            <a class="product-title"
                href="<?php echo esc_url($product_permalink); ?>"><?php echo wp_kses_post($product_name); ?></a>
        </h5>
        <?php if ($settings['wooready_products_vendor'] == 'yes'): ?>
        <?php
                $vendor_id = get_the_author_meta('ID');
                $vendor_name = get_the_author_meta('display_name');
                $user_name = get_the_author_meta('user_login');
                $vendor_link = add_query_arg(['author' => $user_name], get_permalink(wc_get_page_id('shop')));
                ?>
        <?php if ($vendor_name != ''): ?>

        <div class="product-vendor-name">
            <p>
                <?php if ($settings['wooready_products_vendor_text'] != ''): ?>
                <span>
                    <?php echo esc_html($settings['wooready_products_vendor_text']) ?>
                </span>
                <?php endif; ?>
                <a href="<?php echo esc_url($vendor_link); ?>"> <?php echo esc_html($vendor_name); ?> </a>
            </p>
        </div>

        <?php endif; ?>

        <?php endif; ?>
        <div class="product-price clearfix">
            <span class="price">
                <?php echo wp_kses_post($product_price); ?>
            </span>
        </div>
        <?php

            if ($product->is_type('variable')) {

                echo wp_kses_post('<div class="wooready_product_color order:4 display:flex">');

                $attributes = $product->get_variation_attributes();
                $selected_attributes = $product->get_default_attributes();

                foreach ($attributes as $attribute_name => $options) {

                    $attributes_id_arr = wp_list_pluck(wc_get_attribute_taxonomies(), 'attribute_id', 'attribute_name');
                    $remove_suffix = preg_replace('/^pa_/', '', $attribute_name);
                    $woo_ready_color_id = isset($attributes_id_arr[$remove_suffix]) ? $attributes_id_arr[$remove_suffix] : null;
                    $attribute_wrea = get_option('woo_ready_product_attributes') ? get_option('woo_ready_product_attributes') : array();
                    $woo_ready_display_type = sanitize_text_field(isset($_POST['woo_ready_display_type']) ? sanitize_text_field($_POST['woo_ready_display_type']) : (isset($attribute_wrea[$woo_ready_color_id]) ? $attribute_wrea[$woo_ready_color_id] : ''));
                    $name = 'attribute_' . sanitize_title($attribute_name);

                    if ($woo_ready_display_type == 'variation_color') {
                        echo wp_kses_post(sprintf('<a href="%s" class="wready-product-loop-color-wrapper display:flex gap:10 align-items:center %s">', esc_url(get_permalink($product->get_id())), esc_attr($product->get_type())));
                        if (!empty($options)) {

                            if ($product && taxonomy_exists($attribute_name)) {

                                $terms = wc_get_product_terms($product->get_id(), $attribute_name, array(
                                    'fields' => 'all',
                                )
                                );

                                foreach ($terms as $term) {

                                    $cls = $woo_ready_display_type == 'variation_color' ? 'border-radius:100%' : '';
                                    $color = "background-color:" . get_term_meta($term->term_id, $attribute_name . '_' . $this->meta_key . '_color', true);

                                    if (in_array($term->slug, $options)) {

                                        $id = $name . '-' . $term->slug;
                                        echo wp_kses_post('<label class="' . esc_attr($cls) . '" style="' . $color . '" for="' . esc_attr($id) . '">' . esc_html(apply_filters('woocommerce_variation_option_name', $term->name, $woo_ready_display_type)) . '</label>');
                                    }
                                } // end forach
        
                            }
                        }
                        echo wp_kses_post('</a>');
                    }
                }
            }


            echo wp_kses_post('</div>');

            ?>
        <a class="add-to-cart"
            href="<?php echo esc_url($addToCartUrl); ?>"><?php echo wp_kses_post($add_to_cart_icon); ?></a>
    </div>
    <div class="product-meta">
        <?php
            $cmp_icon = WReady_Helper::get_global_setting('woo_ready_product_compare_icon');

            $compare_text = WReady_Helper::get_global_setting('woo_ready_product_compare_text');

            if ($product->is_purchasable() && $product->is_in_stock()) {

                echo wp_kses_post(sprintf("<a data-product-type='%s' data-product_id='%s' title='%s' class='wready-product-compare href='#'>", esc_attr($product->get_type()), esc_attr($product->get_id()), esc_html($compare_text)));
                echo wp_kses_post(shop_ready_render_icons($cmp_icon, 'wready-icons'));
                echo esc_html($compare_text);
                echo wp_kses_post('</a>');
            }

            $_icon = wp_kses_post(WReady_Helper::get_global_setting('woo_ready_product_quickview_icon'));
            $_text = esc_html(WReady_Helper::get_global_setting('woo_ready_product_quickview_text'));

            echo wp_kses_post(sprintf("<a data-product-type='%s' data-product_id='%s' title='%s' class='wready-product-quickview view' href='#'>", esc_attr($product->get_type()), esc_attr($product->get_id()), esc_html($_text)));
            echo wp_kses_post(shop_ready_render_icons($_icon, 'wready-icons'));
            echo esc_html($_text);
            echo wp_kses_post('</a>');

            $_icon = WReady_Helper::get_global_setting('woo_ready_product_wishlist_icon');
            $_text = WReady_Helper::get_global_setting('woo_ready_product_wishlist_text');

            echo wp_kses_post(sprintf("<a data-product-type='%s' data-product_id='%s' title='%s' class='whishlist wready-product-wishlist' href='#'> ", esc_attr($product->get_type()), esc_attr($product->get_id()), esc_html($_text)));
            echo wp_kses_post(shop_ready_render_icons($_icon, 'wready-icons'));
            echo esc_html($_text);
            echo wp_kses_post('</a>');
            ?>
    </div>
</div>

<?php endforeach; ?>