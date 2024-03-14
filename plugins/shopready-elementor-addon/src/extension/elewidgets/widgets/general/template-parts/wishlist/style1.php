<?php
if (!defined('ABSPATH')) {
    exit;
}
// Wishlist Layout One

?>

<div class="woo-ready-wishlist-table-area">
    <?php if ($settings['show_heading'] == 'yes') { ?>
    <h3 class="woo-ready-wishlist-title"><?php echo esc_html($settings['wready_wishlist_heading']); ?></h3>
    <?php } ?>

    <table class="woo-ready-wishlist-table">
        <thead>
            <tr>
                <th class="product-name"><?php echo esc_html($settings['product_name_heading']); ?> </th>
                <th class="product-price"><?php echo esc_html($settings['product_price_heading']); ?> </th>
                <th class="stock-status"><?php echo esc_html($settings['product_stock_heading']); ?> </th>
                <th class="wishlist-cart"><?php echo esc_html($settings['product_cart_heading']); ?> </th>
                <th class="product-remove"><?php echo esc_html($settings['product_remove_heading']); ?> </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) :  

                $product_id        = $product->get_id();
                $product_permalink = $product->get_permalink();
                $product_image     = $product->get_image();
                $product_price     = wc_price( $product->get_price() );
                $product_name      = wp_trim_words( $product->get_name(), $settings['product_title_crop'] , '' );
                $addToCartUrl      = $product->add_to_cart_url();
                $stockStatus       = $product->get_stock_status();
            
            ?>

            <tr>

                <td class="product-name">
                    <a class="product-image"
                        href="<?php echo esc_url($product_permalink); ?>"><?php echo wp_kses_post($product_image); ?>
                    </a>
                    <a class="product-title"
                        href="<?php echo esc_url( $product_permalink); ?>"><?php echo esc_html($product_name); ?> </a>
                </td>

                <td class="product-price">
                    <?php echo wp_kses_post($product_price); ?>
                </td>

                <td class="stock-status">
                    <?php if ($product->is_in_stock()) {?>
                    <a href="<?php echo esc_url($product_permalink); ?>">
                        <?php echo wp_kses_post($stockStatus); ?></a>
                    <?php } else { echo wp_kses_post($stockStatus); };?>
                </td>

                <td class="wishlist-cart">
                    <a href="<?php echo esc_url($addToCartUrl); ?>"
                        class="add_to_cart_btn"><?php echo wp_kses_post($settings['addtocart_text']); ?></a>
                </td>

                <td class="product-remove">
                    <a class="wready-product-wishlist-remove cursor\:pointer"
                        data-product_id="<?php echo esc_attr($product_id); ?>"
                        href="#"><?php echo wp_kses_post($product_renove_icon); ?></a>
                </td>

            </tr>

            <?php endforeach; ?>

        </tbody>
    </table>
</div>