<?php
defined( 'ABSPATH' ) || exit;


if ( get_option( 'yektanet_app_id', true ) != '-' ) {
    require_once('classes/YektanetAddScript.php');
    new YektanetAddScript();


    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    
        require_once('classes/YektanetMainClass.php');
        require_once('classes/YektanetCartHandler.php');
        require_once('classes/YektanetOrderHandler.php');
        require_once('classes/YektanetProductHandler.php');

        new YektanetMainClass();
        new YektanetCartHandler();
        new YektanetOrderHandler();
        new YektanetProductHandler();
    }
}
require_once('classes/YektanetAddStyle.php');
require_once('classes/admin_classes/YektanetAdminMainClass.php');
require_once('classes/admin_classes/YektanetAdminMenu.php');

new YektanetAddStyle();
new YektanetAdminMainClass();
new YektanetAdminMenu();

