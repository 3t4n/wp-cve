<?php

// Create a helper function for easy SDK access.
function premmerce_pr_fs() {
    global $premmerce_pr_fs;

    if ( ! isset( $premmerce_pr_fs ) ) {
        // Include Freemius SDK.
        require_once dirname(__FILE__) . '/freemius/start.php';

        $premmerce_pr_fs = fs_dynamic_init( array(
            'id'                  => '1584',
            'slug'                => 'premmerce-redirect-manager',
            'type'                => 'plugin',
            'public_key'          => 'pk_6dad30b57fc6397cbd2d2f03e9dde',
            'is_premium'          => false,
            'has_addons'          => false,
            'has_paid_plans'      => false,
            'menu'                => array(
                'account'        => false,
                'contact'        => false,
                'support'        => false,
            ),
        ) );
    }

    return $premmerce_pr_fs;
}

// Init Freemius.
premmerce_pr_fs();
// Signal that SDK was initiated.
do_action( 'premmerce_pr_fs_loaded' );
