<?php

/**
 * Add Woocommerce Settings Tab
 */
function youzify_woocommerce_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'WooCommerce Integration', 'youzify' ),
            'desc'  => __( 'Enable WooCommerce integration', 'youzify' ),
            'id'    => 'youzify_enable_woocommerce',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}