<?php

// Create a helper function for easy SDK access.

if ( !function_exists( 'dapof_fs' ) ) {
    function dapof_fs()
    {
        global  $dapof_fs ;
        
        if ( !isset( $dapof_fs ) ) {
            // Activate multisite network integration.
            if ( !defined( 'WP_FS__PRODUCT_1877_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_1877_MULTISITE', true );
            }
            $dapof_fs = fs_dynamic_init( array(
                'id'              => '1877',
                'slug'            => 'display-admin-page-on-frontend',
                'type'            => 'plugin',
                'public_key'      => 'pk_64475c4417669fbcc17c076e31b38',
                'is_premium'      => false,
                'has_addons'      => false,
                'has_paid_plans'  => true,
                'trial'           => array(
                'days'               => 7,
                'is_require_payment' => true,
            ),
                'has_affiliation' => 'selected',
                'menu'            => array(
                'slug'        => 'wpatof_welcome_page',
                'first-path'  => 'admin.php?page=wpatof_welcome_page',
                'support'     => false,
                'affiliation' => false,
                'network'     => true,
            ),
                'is_live'         => true,
            ) );
        }
        
        return $dapof_fs;
    }
    
    // Init Freemius.
    dapof_fs();
    // Signal that SDK was initiated.
    do_action( 'dapof_fs_loaded' );
}
