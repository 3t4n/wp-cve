<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<span class="cart__quantity-item">
    <?php printf (_n( '%d item in Cart', '%d items in Cart', WC()->cart->get_cart_contents_count(), 'pearl-header-builder' ), WC()->cart->get_cart_contents_count()); ?>
</span>