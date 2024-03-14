<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$plugin_name = MPPC_PLUGIN_NAME;
global  $mppcp_fs ;
$version_label = __( 'Free Version', 'mass-pages-posts-creator' );
?>
<div id="dotsstoremain">
    <div class="all-pad">
        <header class="dots-header">
            <div class="dots-plugin-details">
                <div class="dots-header-left">
                    <div class="dots-logo-main">
                        <div class="logo-image">
                            <img src="<?php 
echo  esc_url( MPPC_PLUGIN_URL . 'admin/images/mass_pages_posts_creator-1.png' ) ;
?>">
                        </div>
                        <div class="plugin-version">
                            <span><?php 
esc_html_e( $version_label, 'mass-pages-posts-creator' );
?> <?php 
echo  esc_html( MPPC_PLUGIN_VERSION ) ;
?></span>
                        </div>
                    </div>
                    <div class="plugin-name">
                        <div class="title"><?php 
esc_html_e( $plugin_name, 'mass-pages-posts-creator' );
?></div>
                        <div class="desc"><?php 
esc_html_e( 'Create unique thousands of WordPress page or post by a single click. Allows you to enter prefix and postfix keywords for the name of pages or posts. Bulk posts with range or comma-separated values.', 'mass-pages-posts-creator' );
?></div>
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
esc_html_e( 'Quick Support', 'mass-pages-posts-creator' );
?></strong>
                                </a>
                            </span>
                        </div>

                        <div class="button-dots">
                            <span class="support_dotstore_image">
                                <a target="_blank" href="<?php 
echo  esc_url( 'https://docs.thedotstore.com/category/271-premium-plugin-settings' ) ;
?>">
                                    <span class="dashicons dashicons-media-text"></span>
                                    <strong><?php 
esc_html_e( 'Documentation', 'mass-pages-posts-creator' );
?></strong>
                                </a>
                            </span>
                        </div>
                        <?php 
?>
                            <div class="button-dots">
                                <span class="support_dotstore_image">
                                    <a target="_blank" href="<?php 
echo  esc_url( $mppcp_fs->get_upgrade_url() ) ;
?>">
                                        <span class="dashicons dashicons-upload"></span>
                                        <strong><?php 
esc_html_e( 'Upgrade To Pro', 'mass-pages-posts-creator' );
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
$current_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$mppc_settings_page = ( isset( $current_page ) && 'mass-pages-posts-creator' === $current_page ? 'active' : '' );
$mppc_getting_started = ( isset( $current_page ) && 'mppc-get-started' === $current_page ? 'active' : '' );
$mppc_information = ( isset( $current_page ) && 'mppc-information' === $current_page ? 'active' : '' );

if ( isset( $current_page ) && 'mppc-information' === $current_page || isset( $current_page ) && 'mppc-get-started' === $current_page ) {
    $fee_about = 'active';
} else {
    $fee_about = '';
}

?>
            <div class="dots-menu-main">
                <nav>
                    <ul>
                        <li>
                            <a class="dotstore_plugin <?php 
echo  esc_attr( $mppc_settings_page ) ;
?>" href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'mass-pages-posts-creator',
), admin_url( 'admin.php' ) ) ) ;
?>"><?php 
esc_html_e( 'Add New Mass Pages/Posts', 'mass-pages-posts-creator' );
?></a>
                        </li>
                        <li>
                            <a class="dotstore_plugin <?php 
echo  esc_attr( $fee_about ) ;
?>" href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'mppc-get-started',
), admin_url( 'admin.php' ) ) ) ;
?>"><?php 
esc_html_e( 'About Plugin', 'mass-pages-posts-creator' );
?></a>
                            <ul class="sub-menu">
                                <li><a class="dotstore_plugin <?php 
echo  esc_attr( $mppc_getting_started ) ;
?>" href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'mppc-get-started',
), admin_url( 'admin.php' ) ) ) ;
?>"><?php 
esc_html_e( 'Getting Started', 'mass-pages-posts-creator' );
?></a></li>
                                <li><a class="dotstore_plugin <?php 
echo  esc_attr( $mppc_information ) ;
?>" href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'mppc-information',
), admin_url( 'admin.php' ) ) ) ;
?>"><?php 
esc_html_e( 'Quick info', 'mass-pages-posts-creator' );
?></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="dotstore_plugin"><?php 
esc_html_e( 'Dotstore', 'mass-pages-posts-creator' );
?></a>
                            <ul class="sub-menu">
                                <li><a target="_blank" href="<?php 
echo  esc_url( 'www.thedotstore.com/woocommerce-plugins' ) ;
?>"><?php 
esc_html_e( 'WooCommerce Plugins', 'mass-pages-posts-creator' );
?></a></li>
                                <li><a target="_blank" href="<?php 
echo  esc_url( 'www.thedotstore.com/wordpress-plugins' ) ;
?>"><?php 
esc_html_e( 'Wordpress Plugins', 'mass-pages-posts-creator' );
?></a></li><br>
                                <li><a target="_blank" href="<?php 
echo  esc_url( 'www.thedotstore.com/support' ) ;
?>"><?php 
esc_html_e( 'Contact Support', 'mass-pages-posts-creator' );
?></a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>
        <div class="dots-settings-inner-main">