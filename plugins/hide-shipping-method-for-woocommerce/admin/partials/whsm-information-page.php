<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$plugin_version = WOO_HIDE_SHIPPING_METHODS_VERSION;
$version_label = 'Free Version';
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-header.php';
?>

<div class="whsm-section-left">
    <div class="whsm-main-table table-outer res-cl">
        <h2><?php 
esc_html_e( 'Quick info', 'woo-hide-shipping-methods' );
?></h2>
        <table class="table-outer">
            <tbody>
                <tr>
                    <td class="fr-1"><?php 
esc_html_e( 'Product Type', 'woo-hide-shipping-methods' );
?></td>
                    <td class="fr-2"><?php 
esc_html_e( 'WooCommerce Plugin', 'woo-hide-shipping-methods' );
?></td>
                </tr>
                <tr>
                    <td class="fr-1"><?php 
esc_html_e( 'Product Name', 'woo-hide-shipping-methods' );
?></td>
                    <td class="fr-2"><?php 
esc_html_e( 'Hide Shipping Method For WooCommerce', 'woo-hide-shipping-methods' );
?></td>
                </tr>
                <tr>
                    <td class="fr-1"><?php 
esc_html_e( 'Installed Version', 'woo-hide-shipping-methods' );
?></td>
                    <td class="fr-2"><?php 
esc_html_e( $version_label, 'woo-hide-shipping-methods' );
?> <?php 
echo  esc_html_e( $plugin_version, 'woo-hide-shipping-methods' ) ;
?></td>
                </tr>
                <tr>
                    <td class="fr-1"><?php 
esc_html_e( 'License & Terms of use', 'woo-hide-shipping-methods' );
?></td>
                    <td class="fr-2"><a target="_blank"  href="<?php 
echo  esc_url( 'www.thedotstore.com/terms-and-conditions' ) ;
?>"><?php 
esc_html_e( 'Click here', 'woo-hide-shipping-methods' );
?></a><?php 
esc_html_e( ' to view license and terms of use.', 'woo-hide-shipping-methods' );
?></td>
                </tr>
                <tr>
                    <td class="fr-1"><?php 
esc_html_e( 'Help & Support', 'woo-hide-shipping-methods' );
?></td>
                    <td class="fr-2">
                        <ul>
                            <li><a href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'whsm-start-page&tab=general_setting',
), admin_url( 'admin.php' ) ) ) ;
?>"><?php 
esc_html_e( 'Quick Start', 'woo-hide-shipping-methods' );
?></a></li>
                            <li><a target="_blank" href="<?php 
echo  esc_url( 'https://docs.thedotstore.com/article/290-how-it-works' ) ;
?>"><?php 
esc_html_e( 'Guide Documentation', 'woo-hide-shipping-methods' );
?></a></li>
                            <li><a target="_blank" href="<?php 
echo  esc_url( 'www.thedotstore.com/support' ) ;
?>"><?php 
esc_html_e( 'Support Forum', 'woo-hide-shipping-methods' );
?></a></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td class="fr-1"><?php 
esc_html_e( 'Localization', 'woo-hide-shipping-methods' );
?></td>
                    <td class="fr-2"><?php 
esc_html_e( 'German, French, Polish, Spanish', 'woo-hide-shipping-methods' );
?></td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
</div>
</div>
</div>
</div>
