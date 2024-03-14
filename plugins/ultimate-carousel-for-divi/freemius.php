<?php

if ( !function_exists( 'ucfd_fs' ) ) {
    // Create a helper function for easy SDK access.
    function ucfd_fs()
    {
        global  $ucfd_fs ;
        
        if ( !isset( $ucfd_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $ucfd_fs = fs_dynamic_init( [
                'id'             => '8139',
                'slug'           => 'ultimate-carousel-for-divi',
                'type'           => 'plugin',
                'public_key'     => 'pk_0ec0c34c8c805d0486a6328347e7b',
                'is_premium'     => false,
                'premium_suffix' => 'Premium',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => [
                'days'               => 7,
                'is_require_payment' => false,
            ],
                'menu'           => [
                'first-path' => 'plugins.php',
                'support'    => false,
            ],
                'is_live'        => true,
            ] );
        }
        
        return $ucfd_fs;
    }
    
    // Init Freemius.
    ucfd_fs();
    // Signal that SDK was initiated.
    do_action( 'ucfd_fs_loaded' );
}
