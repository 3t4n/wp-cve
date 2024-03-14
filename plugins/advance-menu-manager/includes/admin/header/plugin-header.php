<?php

$plugin_name = DSAMM_PLUGIN_NAME;
$plugin_version = DSAMM_PLUGINPRO_VERSION;
$plugin_url = DSAMM_PRO_PLUGIN_URL;
$plugin_ver_type = DSAMM_PLUGIN_VERSION_TYPE;
?>
<div id="dotsstoremain">
    <div class="all-pad">
        <header class="dots-header">

        <div class="dots-plugin-details">
                <div class="dots-header-left">
                    <div class="dots-logo-main">
                        <div class="logo-image">
                            <img  src="<?php 
echo  esc_url( $plugin_url ) . 'images/amm-logo.png' ;
?>">
                        </div>
                        <div class="plugin-version">
                            <span><?php 
echo  esc_html( $plugin_ver_type ) ;
?> <?php 
echo  esc_html( $plugin_version ) ;
?></span>
                        </div>
                    </div>
                    <div class="plugin-name">
                        <div class="title"><?php 
echo  esc_html( $plugin_name ) ;
?></div>
                        <p class='desc'><?php 
esc_html_e( 'Effectively and easier way to manage the complex menu navigation of your WordPress website.', 'advance-menu-manager' );
?></p>
                    </div>
                </div>
                <div class="dots-header-right">
                    <div class="button-group">
                        <div class="button-dots">
                            <span class="support_dotstore_image">
                                <a target="_blank" href="<?php 
echo  esc_url( 'http://www.thedotstore.com/support/' ) ;
?>">
                                    <span class="dashicons dashicons-sos"></span>
                                    <strong><?php 
esc_html_e( 'Quick Support', 'advance-menu-manager' );
?></strong>
                                </a>
                            </span>
                        </div>
                        <div class="button-dots">
                            <span class="support_dotstore_image">
                                <a target="_blank" href="<?php 
echo  esc_url( 'https://docs.thedotstore.com/collection/626-advance-menu-manager-for-wordpress' ) ;
?>">
                                    <span class="dashicons dashicons-media-text"></span>
                                    <strong><?php 
esc_html_e( 'Documentation', 'advance-menu-manager' );
?></strong>
                                </a>
                            </span>
                        </div>
                        <?php 
?>
                            <div class="button-dots">
                                <span class="support_dotstore_image">
                                    <a target="_blank" href="<?php 
echo  esc_url( ammp_fs()->get_upgrade_url() ) ;
?>">
                                        <span class="dashicons dashicons-upload"></span>
                                        <strong><?php 
esc_html_e( 'Upgrade To Pro', 'advance-menu-manager' );
?></strong>
                                    </a>
                                </span>
                            </div>
                        <?php 
?>
                    </div>
                </div>
            </div>

            <?php 
$menu_advance_manager_get_started_method = '';
$wc_lite_extra_shipping_dotstore_contact_support_method = '';
$dotstore_introduction_menu_advance_manager = '';
$dotstore_setting_menu_enable = '';
$dotpremium_setting_menu_enable = '';
$add_new_menu_manager = '';
$select_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$section = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$menu_advance_manager_premium_method = '';
if ( !empty($select_tab) && 'menu_advance_manager_premium_method' === $select_tab ) {
    $menu_advance_manager_premium_method = "active";
}
if ( !empty($select_tab) && $select_tab !== '' && !empty($section) && 'menu-manager-add' === $select_tab ) {
    $add_new_menu_manager = "active";
}
if ( !empty($select_tab) && 'menu_advance_manager_get_started_method' === $select_tab ) {
    $menu_advance_manager_get_started_method = "active";
}
if ( !empty($select_tab) && 'wc_lite_extra_shipping_dotstore_contact_support_method' === $select_tab ) {
    $wc_lite_extra_shipping_dotstore_contact_support_method = "active";
}
if ( !empty($select_tab) && 'dotstore_introduction_menu_advance_manager' === $select_tab ) {
    $dotstore_introduction_menu_advance_manager = "active";
}
$site_url = "admin.php?page=advance-menu-manager-pro&tab=";
?>
            <div class="dots-menu-main">
                <nav>
                    <ul>
                        <li>
                            <a class="dotstore_plugin <?php 
echo  esc_attr( $add_new_menu_manager ) ;
?>"  href="<?php 
echo  esc_url( $site_url ) . '&tab=menu-manager-add&section=menu-add' ;
?>"><?php 
esc_html_e( 'Menus', 'advance-menu-manager' );
?></a>
                        </li>
                        <?php 
?>
                            <li>
                                <a class="dotstore_plugin <?php 
echo  esc_attr( $menu_advance_manager_premium_method ) ;
?>" href="<?php 
echo  esc_url( $site_url . '&tab=menu_advance_manager_premium_method' ) ;
?>"><?php 
esc_html_e( 'Premium Version', 'advance-menu-manager' );
?></a>
                            </li>
                        <?php 
?>
                        <li>
                            <a class="dotstore_plugin <?php 
echo  esc_attr( $menu_advance_manager_get_started_method ) ;
?> <?php 
echo  esc_attr( $dotstore_introduction_menu_advance_manager ) ;
?>"  href="<?php 
echo  esc_url( $site_url ) . 'menu_advance_manager_get_started_method' ;
?>"><?php 
esc_html_e( 'About Plugin', 'advance-menu-manager' );
?></a>
                            <ul class="sub-menu">
                                <li><a  class="dotstore_plugin <?php 
echo  esc_attr( $menu_advance_manager_get_started_method ) ;
?>" href="<?php 
echo  esc_url( $site_url ) . 'menu_advance_manager_get_started_method' ;
?>"><?php 
esc_html_e( 'Getting Started', 'advance-menu-manager' );
?></a></li>
                                <li><a class="dotstore_plugin <?php 
echo  esc_attr( $dotstore_introduction_menu_advance_manager ) ;
?>" href="<?php 
echo  esc_url( $site_url ) . 'dotstore_introduction_menu_advance_manager' ;
?>"><?php 
esc_html_e( 'Quick info', 'advance-menu-manager' );
?></a></li>
                                <li><a class="dotstore_plugin" href="<?php 
echo  esc_url( 'https://www.thedotstore.com/feature-requests/' ) ;
?>" target="_blank"><?php 
esc_html_e( 'Suggest A Feature', 'advance-menu-manager' );
?></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="dotstore_plugin <?php 
echo  esc_attr( $wc_lite_extra_shipping_dotstore_contact_support_method ) ;
?>"><?php 
esc_html_e( 'Dotstore', 'advance-menu-manager' );
?></a>
                            <ul class="sub-menu">
                                <li><a target="_blank" href="<?php 
echo  esc_url( 'https://www.thedotstore.com/woocommerce-plugins/' ) ;
?>"><?php 
esc_html_e( 'WooCommerce Plugins', 'advance-menu-manager' );
?></a></li>
                                <li><a target="_blank" href="<?php 
echo  esc_url( 'https://www.thedotstore.com/wordpress-plugins/' ) ;
?>"><?php 
esc_html_e( 'Wordpress Plugins', 'advance-menu-manager' );
?></a></li><br>
                                <li><a target="_blank" href="<?php 
echo  esc_url( 'https://www.thedotstore.com/support/' ) ;
?>"><?php 
esc_html_e( 'Contact Support', 'advance-menu-manager' );
?></a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>
        <div class="dots-settings-inner-main">
            <div class="dots-settings-left-side">