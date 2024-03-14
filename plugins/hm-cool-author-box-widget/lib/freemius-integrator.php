<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Create a helper function for easy SDK access.

if ( !function_exists( 'hcabw_fs' ) ) {
    // Create a helper function for easy SDK access.
    function hcabw_fs()
    {
        global  $hcabw_fs ;
        
        if ( !isset( $hcabw_fs ) ) {
            // Include Freemius SDK.
            require_once HMCABW_PATH . '/freemius/start.php';
            $hcabw_fs = fs_dynamic_init( array(
                'id'             => '9664',
                'slug'           => 'hm-cool-author-box-widget',
                'type'           => 'plugin',
                'public_key'     => 'pk_e34baa44d4ff84ad4dff05c26b4b3',
                'is_premium'     => false,
                'premium_suffix' => 'Professional',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => array(
                'days'               => 10,
                'is_require_payment' => true,
            ),
                'menu'           => array(
                'slug'       => 'hm-cool-author-box',
                'first-path' => 'admin.php?page=hmcab-help-usage',
            ),
                'is_live'        => true,
            ) );
        }
        
        return $hcabw_fs;
    }
    
    // Init Freemius.
    hcabw_fs();
    // Signal that SDK was initiated.
    do_action( 'hcabw_fs_loaded' );
    function hcabw_fs_support_forum_url( $wp_support_url )
    {
        return 'https://wordpress.org/support/plugin/hm-cool-author-box-widget/';
    }
    
    hcabw_fs()->add_filter( 'support_forum_url', 'hcabw_fs_support_forum_url' );
    function hcabw_fs_custom_connect_message_on_update(
        $message,
        $user_first_name,
        $plugin_title,
        $user_login,
        $site_link,
        $freemius_link
    )
    {
        return sprintf(
            __( 'Hey %1$s' ) . ',<br>' . __( 'Please help us improve %2$s! If you opt-in, some data about your usage of %2$s will be sent to %5$s. If you skip this, that\'s okay! %2$s will still work just fine.', HMCABW_TXT_DOMAIN ),
            $user_first_name,
            '<b>' . $plugin_title . '</b>',
            '<b>' . $user_login . '</b>',
            $site_link,
            $freemius_link
        );
    }
    
    hcabw_fs()->add_filter(
        'connect_message_on_update',
        'hcabw_fs_custom_connect_message_on_update',
        10,
        6
    );
}
