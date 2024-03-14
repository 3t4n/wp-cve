<?php

/**
 * Handles plugin header
 * 
 * @package SCFW_Size_Chart_For_Woocommerce
 * @since   1.0.0
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    exit;
}
global  $scfw_fs ;
$version_label = '';
$plugin_slug = '';
$version_label = __( 'Free', 'size-chart-for-woocommerce' );
$plugin_slug = 'basic_size_chart';
$plugin_name = __( 'Product Size Charts', 'size-chart-for-woocommerce' );
$plugin_version = 'v' . SCFW_PLUGIN_VERSION;
$current_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$get_current_screen = get_current_screen();
$scfw_free_dashboard = ( isset( $current_page ) && 'scfw-upgrade-dashboard' === $current_page ? 'active' : '' );
$scfw_rules_list = ( (isset( $get_current_screen ) || isset( $current_page )) && ('size-chart' === $get_current_screen->post_type && 'size-chart-setting-page' !== $current_page) ? 'active' : '' );
$scfw_settings_menu = ( isset( $current_page ) && ('size-chart-import-export' === $current_page || 'size-chart-get-started' === $current_page || 'size-chart-information' === $current_page || 'size-chart-setting-page' === $current_page) || !(scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code()) && 'size-chart-get-started-account' === $current_page ? 'active' : '' );
$scfw_get_started = ( isset( $current_page ) && 'size-chart-get-started' === $current_page ? 'active' : '' );
$scfw_quick_info = ( isset( $current_page ) && 'size-chart-information' === $current_page ? 'active' : '' );
$scfw_import_export = ( isset( $current_page ) && 'size-chart-import-export' === $current_page ? 'active' : '' );
$scfw_global_settings = ( isset( $current_page ) && 'size-chart-setting-page' === $current_page ? 'active' : '' );
$scfw_account_page = ( isset( $current_page ) && 'size-chart-get-started-account' === $current_page ? 'active' : '' );
$scfw_free_dashboard = ( isset( $current_page ) && 'scfw-upgrade-dashboard' === $current_page ? 'active' : '' );
$scfw_display_submenu = ( !empty($scfw_settings_menu) && 'active' === $scfw_settings_menu ? 'display:inline-block' : 'display:none' );
$scfw_admin_object = new SCFW_Size_Chart_For_Woocommerce_Admin( '', '', '' );
?>
<div id="dotsstoremain">
    <div class="all-pad">
        <?php 
$scfw_admin_object->scfw_get_promotional_bar( $plugin_slug );
?>
        <div class="dotstore_plugin_page_loader"></div>
        <header class="dots-header">
            <div class="dots-plugin-details">
                <div class="dots-header-left">
                    <div class="dots-logo-main">
                        <img src="<?php 
echo  esc_url( plugin_dir_url( dirname( __FILE__, 2 ) ) . 'images/thedotstore-images/product-size-chart-logo-128x128.png' ) ;
?>" alt="<?php 
esc_attr_e( SCFW_PLUGIN_NAME, 'size-chart-for-woocommerce' );
?>">
                    </div>
                    <div class="plugin-name">
                        <div class="title"><?php 
esc_html_e( $plugin_name, 'size-chart-for-woocommerce' );
?></div>
                    </div>
                    <span class="version-label"><?php 
esc_html_e( $version_label, 'size-chart-for-woocommerce' );
?></span>
                    <span class="version-number"><?php 
echo  esc_html_e( $plugin_version, 'size-chart-for-woocommerce' ) ;
?></span>
                </div>
                <div class="dots-header-right">
                    <div class="button-dots">
                        <a target="_blank" href="<?php 
echo  esc_url( 'http://www.thedotstore.com/support/' ) ;
?>"><?php 
esc_html_e( 'Support', 'size-chart-for-woocommerce' );
?></a>
                    </div>
                    <div class="button-dots">
                        <a target="_blank" href="<?php 
echo  esc_url( 'https://www.thedotstore.com/feature-requests/' ) ;
?>"><?php 
esc_html_e( 'Suggest', 'size-chart-for-woocommerce' );
?></a>
                    </div>
                    <?php 
$plugin_help_url = 'https://docs.thedotstore.com/category/239-premium-plugin-settings';
?>
                	<div class="button-dots <?php 
echo  ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ? '' : 'last-link-button' ) ;
?>">
                        <a target="_blank" href="<?php 
echo  esc_url( $plugin_help_url ) ;
?>"><?php 
esc_html_e( 'Help', 'size-chart-for-woocommerce' );
?></a>
                    </div>
                    <div class="button-dots">
                        <?php 
?>
                            <a class="dots-upgrade-btn" target="_blank" href="<?php 
echo  esc_url( $scfw_fs->get_upgrade_url() ) ;
?>"><?php 
esc_html_e( 'Upgrade', 'size-chart-for-woocommerce' );
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

if ( !(scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code()) ) {
    ?>
                            <li>
                                <a class="dotstore_plugin <?php 
    echo  esc_attr( $scfw_free_dashboard ) ;
    ?>" href="<?php 
    echo  esc_url( add_query_arg( array(
        'page' => 'scfw-upgrade-dashboard',
    ), admin_url( 'admin.php' ) ) ) ;
    ?>"><?php 
    esc_html_e( 'Dashboard', 'size-chart-for-woocommerce' );
    ?></a>
                            </li>
                            <?php 
}

?>
                        <li>
                            <a class="dotstore_plugin <?php 
echo  esc_attr( $scfw_rules_list ) ;
?>" href="<?php 
echo  esc_url( add_query_arg( array(
    'post_type' => 'size-chart',
), admin_url( 'edit.php' ) ) ) ;
?>"><?php 
esc_html_e( 'Size Charts', 'size-chart-for-woocommerce' );
?></a>
                        </li>
                        <li>
                            <a class="dotstore_plugin <?php 
echo  esc_attr( $scfw_settings_menu ) ;
?>" href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'size-chart-setting-page',
), admin_url( 'edit.php?post_type=size-chart' ) ) ) ;
?>"><?php 
esc_html_e( 'Settings', 'size-chart-for-woocommerce' );
?></a>
                        </li>
                        <?php 

if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
    ?>
                            <li>
                                <a class="dotstore_plugin <?php 
    echo  esc_attr( $scfw_account_page ) ;
    ?>" href="<?php 
    echo  esc_url( $scfw_fs->get_account_url() ) ;
    ?>"><?php 
    esc_html_e( 'License', 'size-chart-for-woocommerce' );
    ?></a>
                            </li>
                            <?php 
}

?>
                    </ul>
                </nav>
            </div>
        </header>
        <div class="dots-settings-inner-main">
            <div class="dots-settings-left-side">
                <div class="dotstore-submenu-items" style="<?php 
echo  esc_attr( $scfw_display_submenu ) ;
?>">
                    <ul>
                        <li><a class="<?php 
echo  esc_attr( $scfw_global_settings ) ;
?>" href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'size-chart-setting-page',
), admin_url( 'edit.php?post_type=size-chart' ) ) ) ;
?>"><?php 
esc_html_e( 'Global Settings', 'size-chart-for-woocommerce' );
?></a></li>
                        <?php 
?>
                        <li><a class="<?php 
echo  esc_attr( $scfw_get_started ) ;
?>" href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'size-chart-get-started',
), admin_url( 'admin.php' ) ) ) ;
?>"><?php 
esc_html_e( 'About', 'size-chart-for-woocommerce' );
?></a></li>
                        <li><a class="<?php 
echo  esc_attr( $scfw_quick_info ) ;
?>" href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'size-chart-information',
), admin_url( 'admin.php' ) ) ) ;
?>"><?php 
esc_html_e( 'Quick info', 'size-chart-for-woocommerce' );
?></a></li>
                        <?php 

if ( !(scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code()) ) {
    $check_account_page_exist = menu_page_url( 'size-chart-get-started-account', false );
    
    if ( isset( $check_account_page_exist ) && !empty($check_account_page_exist) ) {
        ?>
                                <li>
                                    <a class="<?php 
        echo  esc_attr( $scfw_account_page ) ;
        ?>" href="<?php 
        echo  esc_url( $scfw_fs->get_account_url() ) ;
        ?>"><?php 
        esc_html_e( 'Account', 'size-chart-for-woocommerce' );
        ?></a>
                                </li>
                                <?php 
    }

}

?>
                        <li><a href="<?php 
echo  esc_url( 'https://www.thedotstore.com/plugins/' ) ;
?>" target="_blank"><?php 
esc_html_e( 'Shop Plugins', 'size-chart-for-woocommerce' );
?></a></li>
                    </ul>
                </div>
                <hr class="wp-header-end" />
                