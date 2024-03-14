<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$version_label = '';
$plugin_slug = '';
$version_label = __( 'Free', 'woo-hide-shipping-methods' );
$plugin_slug = 'basic_hide_shipping';
global  $whsm_fs ;
$plugin_name = 'Hide Shipping';
$plugin_version = 'v' . WOO_HIDE_SHIPPING_METHODS_VERSION;
$current_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$current_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$hide_shipping_option = get_option( 'hide_shipping_option' );
$whsm_free_dashboard = ( isset( $current_tab ) && 'upgrade_dashboard' === $current_tab ? 'active' : '' );
$whsm_settings_page = ( isset( $current_tab ) && 'general_setting' === $current_tab ? 'active' : '' );
$whsm_rules_list = ( isset( $current_tab ) && 'woo_hide_shipping' === $current_tab ? 'active' : '' );
$whsm_settings_menu = ( (isset( $current_tab ) || isset( $current_page )) && ('whsm_import_export' === $current_tab || 'get_started' === $current_tab || 'quick_info' === $current_tab || !(whsm_fs()->is__premium_only() && whsm_fs()->can_use_premium_code()) && 'whsm-start-page-account' === $current_page) ? 'active' : '' );
$whsm_get_started = ( isset( $current_tab ) && 'get_started' === $current_tab ? 'active' : '' );
$whsm_quick_info = ( isset( $current_tab ) && 'quick_info' === $current_tab ? 'active' : '' );
$whsm_import_export = ( isset( $current_tab ) && 'whsm_import_export' === $current_tab ? 'active' : '' );
$whsm_account_page = ( isset( $current_page ) && 'whsm-start-page-account' === $current_page ? 'active' : '' );
$whsm_display_submenu = ( !empty($whsm_settings_menu) && 'active' === $whsm_settings_menu ? 'display:inline-block' : 'display:none' );
$admin_object = new Woo_Hide_Shipping_Methods_Admin( '', '' );
?>
<div id="dotsstoremain">
    <div class="all-pad">
        <?php 
$admin_object->whsm_get_promotional_bar( $plugin_slug );
?>
        <header class="dots-header">
            <div class="dots-plugin-details">
                <div class="dots-header-left">
                    <div class="dots-logo-main">
                        <img src="<?php 
echo  esc_url( WHSM_PLUGIN_URL . 'admin/images/hide-shipping-method-logo.png' ) ;
?>">
                    </div>
                    <div class="plugin-name">
                        <div class="title"><?php 
esc_html_e( $plugin_name, 'woo-hide-shipping-methods' );
?></div>
                    </div>
                    <span class="version-label"><?php 
esc_html_e( $version_label, 'woo-hide-shipping-methods' );
?></span>
                    <span class="version-number"><?php 
echo  esc_html__( $plugin_version, 'woo-hide-shipping-methods' ) ;
?></span>
                </div>
                <div class="dots-header-right">
                    <div class="button-dots">
                        <a target="_blank" href="<?php 
echo  esc_url( 'http://www.thedotstore.com/support/' ) ;
?>"><?php 
esc_html_e( 'Support', 'woo-hide-shipping-methods' );
?></a>
                    </div>
                    <div class="button-dots">
                        <a target="_blank" href="<?php 
echo  esc_url( 'https://www.thedotstore.com/feature-requests/' ) ;
?>"><?php 
esc_html_e( 'Suggest', 'woo-hide-shipping-methods' );
?></a>
                    </div>
                	<div class="button-dots <?php 
echo  ( whsm_fs()->is__premium_only() && whsm_fs()->can_use_premium_code() ? '' : 'last-link-button' ) ;
?>">
                        <a target="_blank" href="<?php 
echo  esc_url( 'https://docs.thedotstore.com/category/180-premium-plugin-settings' ) ;
?>"><?php 
esc_html_e( 'Help', 'woo-hide-shipping-methods' );
?></a>
                    </div>
                    <div class="button-dots">
                        <?php 
?>
                            <a class="dots-upgrade-btn" target="_blank" href="<?php 
echo  esc_url( $whsm_fs->get_upgrade_url() ) ;
?>"><?php 
esc_html_e( 'Upgrade', 'woo-hide-shipping-methods' );
?></a>
                            <?php 
?>
                    </div>
                </div>
            </div>
            <div class="dots-menu-main">
                <nav>
                    <ul>
                        <?php 

if ( !(whsm_fs()->is__premium_only() && whsm_fs()->can_use_premium_code()) ) {
    ?>
                            <li>
                                <a class="dotstore_plugin <?php 
    echo  esc_attr( $whsm_free_dashboard ) ;
    ?>" href="<?php 
    echo  esc_url( add_query_arg( array(
        'page' => 'whsm-start-page&tab=upgrade_dashboard',
    ), admin_url( 'admin.php' ) ) ) ;
    ?>"><?php 
    esc_html_e( 'Dashboard', 'woo-hide-shipping-methods' );
    ?></a>
                            </li>
                            <?php 
}

?>
                        <?php 

if ( isset( $current_page ) && 'whsm-start-page' === $current_page && empty($current_tab) ) {
    ?>
                            <li>
                                <a class="dotstore_plugin active" href="<?php 
    echo  esc_url( add_query_arg( array(
        'page' => 'whsm-start-page&tab=general_setting',
    ), admin_url( 'admin.php' ) ) ) ;
    ?>"><?php 
    esc_html_e( 'General Settings', 'woo-hide-shipping-methods' );
    ?></a>
                            </li>
                            <?php 
} else {
    ?>
                            <li>
                                <a class="dotstore_plugin <?php 
    echo  esc_attr( $whsm_settings_page ) ;
    ?>" href="<?php 
    echo  esc_url( add_query_arg( array(
        'page' => 'whsm-start-page&tab=general_setting',
    ), admin_url( 'admin.php' ) ) ) ;
    ?>"><?php 
    esc_html_e( 'General Settings', 'woo-hide-shipping-methods' );
    ?></a>
                            </li>   
                            <?php 
}


if ( isset( $hide_shipping_option ) && 'advance_hide_shipping' === $hide_shipping_option ) {
    ?>
                            <li>
                                <a class="dotstore_plugin <?php 
    echo  esc_attr( $whsm_rules_list ) ;
    ?>" href="<?php 
    echo  esc_url( add_query_arg( array(
        'page' => 'whsm-start-page&tab=woo_hide_shipping',
    ), admin_url( 'admin.php' ) ) ) ;
    ?>"><?php 
    esc_html_e( 'Manage Rules', 'woo-hide-shipping-methods' );
    ?></a>
                            </li>
                            <?php 
}

$whsm_settings_page_url = '';
$whsm_settings_page_url = add_query_arg( array(
    'page' => 'whsm-start-page&tab=get_started',
), admin_url( 'admin.php' ) );
?>
                        <li>
                            <a class="dotstore_plugin <?php 
echo  esc_attr( $whsm_settings_menu ) ;
?>" href="<?php 
echo  esc_url( $whsm_settings_page_url ) ;
?>"><?php 
esc_html_e( 'Settings', 'woo-hide-shipping-methods' );
?></a>
                        </li>
                        <?php 

if ( whsm_fs()->is__premium_only() && whsm_fs()->can_use_premium_code() ) {
    ?>
                            <li>
                                <a class="dotstore_plugin <?php 
    echo  esc_attr( $whsm_account_page ) ;
    ?>" href="<?php 
    echo  esc_url( $whsm_fs->get_account_url() ) ;
    ?>"><?php 
    esc_html_e( 'License', 'woo-hide-shipping-methods' );
    ?></a>
                            </li>
                            <?php 
}

?>
                    </ul>
                </nav>
            </div>
        </header>
        <!-- Upgrade to pro popup -->
        <?php 

if ( !(whsm_fs()->is__premium_only() && whsm_fs()->can_use_premium_code()) ) {
    ?>
            <div class="upgrade-to-pro-modal-main">
                <div class="upgrade-to-pro-modal-outer">
                    <div class="pro-modal-inner">
                        <div class="pro-modal-wrapper">
                            <div class="pro-modal-header">
                                <span class="dashicons dashicons-no-alt modal-close-btn"></span>
                                <p><span class="whsm-pro-label"></span><?php 
    esc_html_e( 'Unlock Premium Features!', 'woo-hide-shipping-methods' );
    ?></p>
                            </div>
                            <div class="pro-modal-body">
                                <h3 class="pro-feature-title"><?php 
    esc_html_e( 'Upgrade to Hide Shipping Premium', 'woo-hide-shipping-methods' );
    ?></h3>
                                <ul class="pro-feature-list">
                                    <li><?php 
    esc_html_e( 'Hide non-compatible shipping methods.', 'woo-hide-shipping-methods' );
    ?></li>
                                    <li><?php 
    esc_html_e( 'Hide shipping methods based on specific dates, days, or times.', 'woo-hide-shipping-methods' );
    ?></li>
                                    <li><?php 
    esc_html_e( 'Hide shipping methods based on cities, states, postcodes, or zones.', 'woo-hide-shipping-methods' );
    ?></li>
                                    <li><?php 
    esc_html_e( 'Hide shipping methods based on product attributes.', 'woo-hide-shipping-methods' );
    ?></li>
                                    <li><?php 
    esc_html_e( 'Hide shipping methods based on specific user roles.', 'woo-hide-shipping-methods' );
    ?></li>
                                    <li><?php 
    esc_html_e( 'Hide shipping methods based on specific payment gateways.', 'woo-hide-shipping-methods' );
    ?></li>
                                    <li><?php 
    esc_html_e( 'Import and export hide shipping rules.', 'woo-hide-shipping-methods' );
    ?></li>
                                </ul>
                            </div>
                            <div class="pro-modal-footer">
                                <a class="pro-feature-trial-btn" target="_blank" href="<?php 
    echo  esc_url( 'https://www.thedotstore.com/hide-shipping-method-for-woocommerce/' ) ;
    ?>"><?php 
    esc_html_e( 'Buy Now', 'woo-hide-shipping-methods' );
    ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
}

?>
        <div class="dots-settings-inner-main">
            <div class="dots-settings-left-side">
                <div class="dotstore-submenu-items" style="<?php 
echo  esc_attr( $whsm_display_submenu ) ;
?>">
                    <ul>
                        <?php 
?>
                        <li><a class="<?php 
echo  esc_attr( $whsm_get_started ) ;
?>" href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'whsm-start-page&tab=get_started',
), admin_url( 'admin.php' ) ) ) ;
?>"><?php 
esc_html_e( 'About', 'woo-hide-shipping-methods' );
?></a></li>
                        <li><a class="<?php 
echo  esc_attr( $whsm_quick_info ) ;
?>" href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'whsm-start-page&tab=quick_info',
), admin_url( 'admin.php' ) ) ) ;
?>"><?php 
esc_html_e( 'Quick info', 'woo-hide-shipping-methods' );
?></a></li>
                        <?php 

if ( !(whsm_fs()->is__premium_only() && whsm_fs()->can_use_premium_code()) ) {
    $check_account_page_exist = menu_page_url( 'whsm-start-page-account', false );
    
    if ( isset( $check_account_page_exist ) && !empty($check_account_page_exist) ) {
        ?>
                                <li>
                                    <a class="<?php 
        echo  esc_attr( $whsm_account_page ) ;
        ?>" href="<?php 
        echo  esc_url( $whsm_fs->get_account_url() ) ;
        ?>"><?php 
        esc_html_e( 'Account', 'woo-hide-shipping-methods' );
        ?></a>
                                </li>
                                <?php 
    }

}

?>
                        <li><a href="<?php 
echo  esc_url( 'https://www.thedotstore.com/plugins/' ) ;
?>" target="_blank"><?php 
esc_html_e( 'Shop Plugins', 'woo-hide-shipping-methods' );
?></a></li>
                    </ul>
                </div>
                <hr class="wp-header-end" />