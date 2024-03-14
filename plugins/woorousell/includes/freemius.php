<?php

/**
 * Freemius Init Functions
 *
 * @author 		MojofyWP
 * @package 	includes
 *
 */
// Create a helper function for easy SDK access.
function woorousell_fs()
{
    global  $woorousell_fs ;
    
    if ( !isset( $woorousell_fs ) ) {
        // Activate multisite network integration.
        if ( !defined( 'WP_FS__PRODUCT_2655_MULTISITE' ) ) {
            define( 'WP_FS__PRODUCT_2655_MULTISITE', true );
        }
        // Include Freemius SDK.
        require_once WRSL_PATH . '/freemius/start.php';
        $woorousell_fs = fs_dynamic_init( array(
            'id'             => '2655',
            'slug'           => 'woorousell',
            'premium_slug'   => 'woorousell-pro',
            'type'           => 'plugin',
            'public_key'     => 'pk_5ef001f9bf16a072543e967d66773',
            'is_premium'     => false,
            'premium_suffix' => '(PRO)',
            'has_addons'     => false,
            'has_paid_plans' => true,
            'menu'           => array(
            'slug'    => 'wrsl-builder',
            'contact' => false,
            'support' => false,
        ),
            'is_live'        => true,
        ) );
    }
    
    return $woorousell_fs;
}

// Init Freemius.
woorousell_fs();
// Signal that SDK was initiated.
do_action( 'woorousell_fs_loaded' );