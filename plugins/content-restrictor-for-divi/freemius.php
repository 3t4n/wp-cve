<?php

if ( !function_exists( 'wpt_dcr' ) ) {
    // Create a helper function for easy SDK access.
    function wpt_dcr()
    {
        global  $wpt_dcr ;
        
        if ( !isset( $wpt_dcr ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $wpt_dcr = fs_dynamic_init( [
                'id'             => '7520',
                'slug'           => 'content-restrictor-for-divi',
                'type'           => 'plugin',
                'public_key'     => 'pk_094e71ce92cc1f993a2af0ca060f5',
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
        
        return $wpt_dcr;
    }
    
    // Init Freemius.
    wpt_dcr();
    // Signal that SDK was initiated.
    do_action( 'wpt_dcr_loaded' );
}
