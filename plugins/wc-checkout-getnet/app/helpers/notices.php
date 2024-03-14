<?php
/**
 * Add admin notices
 *
 * @since 1.0.2
 * @return string
 */
function add_admin_notices() {
    if ( (float) phpversion() < 7.4 ) {
        add_action( 'admin_notices', 'add_php_notices' );
    }

    if ( ! class_exists( 'Extra_Checkout_Fields_For_Brazil' ) ) {
        add_action( 'admin_notices', 'add_extra_notices' );
    }

    if ( ! class_exists( 'WooCommerce' ) ) {
        add_action( 'admin_notices', 'add_woocommerce_notices' );
    }
}

function add_extra_notices() {
    printf(
        '<div class="error"><p><strong>%s</strong> %s</p></div>',
        __( 'Coffee Code - Getnet for WooCommerce' ),
        __( 'necessário do plugin Brazilian Market on WooCommerce!' )
    );
}

function add_woocommerce_notices() {
    printf(
        '<div class="error"><p><strong>%s</strong> %s</p></div>',
        __( 'Coffee Code - Getnet for WooCommerce' ),
        __( 'necessário do plugin WooCommerce!' )
    );
}

function add_php_notices() {
    printf(
        '<div class="error"><p><strong>%s</strong> %s</p></div>',
        __( 'Coffee Code - Getnet for WooCommerce' ),
        __( 'plugin não é mais compatível com versões inferiores do PHP 7.4!' )
    );
}