<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="mini-cart">
    <div class="mini-cart__products">
        <?php
        if (sizeof(WC()->cart->get_cart()) > 0) :
            $cart = array_reverse(WC()->cart->get_cart());
            foreach ($cart as $cart_item_key => $cart_item) :
                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) :

                    $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key);
                    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                    $product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                    ?>
                    <div class="mini-cart__product clearfix">
                        <div class="mini-cart__product-left">
                            <?php if (!$_product->is_visible()) : ?>
                                <?php echo str_replace(array('http:', 'https:'), '', $thumbnail); ?>
                            <?php else : ?>
                                <a class="mini-cart__product-link"
                                   href="<?php echo esc_url($_product->get_permalink($cart_item)); ?>">
                                    <?php echo str_replace(array('http:', 'https:'), '', $thumbnail); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="mini-cart__product-body">
                            <?php if (!$_product->is_visible()) : ?>
                                <span class="mini-cart__product-title"><?php echo esc_html($product_name); ?></span>
                            <?php else : ?>
                                <a class="mini-cart__product-title"
                                   href="<?php echo esc_url($_product->get_permalink($cart_item)); ?>"><?php echo esc_html($product_name); ?></a>
                            <?php endif; ?>
                            <?php echo apply_filters('woocommerce_widget_cart_item_quantity', '<span class="mini-cart__product-quantity">' . sprintf('%s &times; %s', $cart_item['quantity'], $product_price) . '</span>', $cart_item, $cart_item_key); ?>
                        </div>
                        <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="mini-cart__empty"><?php _e('No products in the cart.', 'pearl-header-builder'); ?></div>
        <?php endif; ?>
    </div>
    <?php if (sizeof(WC()->cart->get_cart()) > 0) : ?>
        <div class="mini-cart__price-total"><?php _e('Subtotal', 'pearl-header-builder'); ?>
            : <?php echo WC()->cart->get_cart_subtotal(); ?></div>
        <div class="mini-cart__actions">
            <a href="<?php echo esc_url(wc_get_checkout_url()); ?>"
               class=" btn btn_primary btn_solid"><?php _e('Checkout', 'pearl-header-builder'); ?></a>
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>"
               class="mini-cart__action-link"><?php _e('View cart', 'pearl-header-builder'); ?></a>
        </div>
    <?php endif; ?>
</div>