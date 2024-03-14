<?php

if ( !function_exists( 'wptools_product_carousel_fs' ) ) {
    // Create a helper function for easy SDK access.
    function wptools_product_carousel_fs()
    {
        global  $wptools_product_carousel_fs ;
        
        if ( !isset( $wptools_product_carousel_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $wptools_product_carousel_fs = fs_dynamic_init( [
                'id'             => '4206',
                'slug'           => 'wp-tools-divi-product-carousel',
                'type'           => 'plugin',
                'public_key'     => 'pk_1a723c947c6885022596c2e2f55be',
                'is_premium'     => false,
                'premium_suffix' => 'Premium',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => [
                'days'               => 7,
                'is_require_payment' => false,
            ],
                'menu'           => [
                'slug'    => 'wp-tools-divi-product-carousel',
                'support' => false,
                'parent'  => [
                'slug' => 'et_divi_options',
            ],
            ],
                'is_live'        => true,
            ] );
        }
        
        return $wptools_product_carousel_fs;
    }
    
    // Init Freemius.
    wptools_product_carousel_fs();
    // Signal that SDK was initiated.
    do_action( 'wptools_product_carousel_fs_loaded' );
}
