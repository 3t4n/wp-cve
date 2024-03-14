<?php

if ( !function_exists( 'wpt_img_carousel_fs' ) ) {
    // Create a helper function for easy SDK access.
    function wpt_img_carousel_fs()
    {
        global  $wpt_img_carousel_fs ;
        
        if ( !isset( $wpt_img_carousel_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $wpt_img_carousel_fs = fs_dynamic_init( [
                'id'             => '3743',
                'slug'           => 'image-carousel-for-divi',
                'type'           => 'plugin',
                'public_key'     => 'pk_8cb49bf09bc5d126478f8933a2eb4',
                'is_premium'     => false,
                'premium_suffix' => 'Premium',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => [
                'days'               => 7,
                'is_require_payment' => false,
            ],
                'menu'           => [
                'slug'    => 'image-carousel-for-divi',
                'support' => false,
                'parent'  => [
                'slug' => 'et_divi_options',
            ],
            ],
                'is_live'        => true,
            ] );
        }
        
        return $wpt_img_carousel_fs;
    }
    
    // Init Freemius.
    wpt_img_carousel_fs();
    // Signal that SDK was initiated.
    do_action( 'wpt_img_carousel_fs_loaded' );
}
