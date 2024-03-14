<?php
defined('ABSPATH') || exit;

/**
 * External product add to cart
 *
 */


$product_url = $product->add_to_cart_url();
$button_text = $product->single_add_to_cart_text();

?>

<form class="cart" action="<?php echo esc_url($product_url); ?>" method="get">
    <button type="submit" class="single_add_to_cart_button button alt">
        <?php echo wp_kses_post($button_text); ?>
    </button>
    <?php wc_query_string_form_fields($product_url); ?>
</form>