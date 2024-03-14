<?php

/** Load Freemius */

if ( !function_exists( 'fab_freemius' ) ) {
    // Create a helper function for easy SDK access.
    function fab_freemius()
    {
        global  $fab_freemius ;
        
        if ( !isset( $fab_freemius ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/vendor/freemius/wordpress-sdk/start.php';
            $fab_freemius = fs_dynamic_init( array(
                'id'             => '9157',
                'slug'           => 'floating-awesome-button',
                'type'           => 'plugin',
                'public_key'     => 'pk_572cd99e98775de85c0a9aa4c28fb',
                'is_premium'     => false,
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'slug'    => 'floating-awesome-button-setting',
                'contact' => false,
                'support' => false,
                'parent'  => array(
                'slug' => 'options-general.php',
            ),
            ),
                'is_live'        => true,
            ) );
        }
        
        return $fab_freemius;
    }
    
    // Init Freemius.
    fab_freemius();
    // Signal that SDK was initiated.
    do_action( 'fab_freemius_loaded' );
}
