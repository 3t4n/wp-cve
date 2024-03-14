<?php

/**
 * Handles free plugin user dashboard
 * 
 * @package SCFW_Size_Chart_For_Woocommerce
 * @since   2.4.3
 */
// Exit if accessed directly
if ( !defined( 'WPINC' ) ) {
    exit;
}
$file_dir_path = 'header/plugin-header.php';
if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
    require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
}
$plugin_mode = __( 'Free Version ', 'size-chart-for-woocommerce' );
?>

    <div class="thedotstore-main-table res-cl quick-info">
        <h2><?php 
esc_html_e( 'Quick info', 'size-chart-for-woocommerce' );
?></h2>
        <table class="table-outer">
            <tbody>
            <tr>
                <td class="fr-1"><?php 
esc_html_e( 'Product Type', 'size-chart-for-woocommerce' );
?></td>
                <td class="fr-2"><?php 
esc_html_e( 'WooCommerce Plugin', 'size-chart-for-woocommerce' );
?></td>
            </tr>
            <tr>
                <td class="fr-1"><?php 
esc_html_e( 'Product Name', 'size-chart-for-woocommerce' );
?></td>
                <td class="fr-2"><?php 
esc_html_e( SCFW_PLUGIN_NAME, 'size-chart-for-woocommerce' );
?></td>
            </tr>
            <tr>
                <td class="fr-1"><?php 
esc_html_e( 'Installed Version', 'size-chart-for-woocommerce' );
?></td>
                <td class="fr-2"><?php 
echo  esc_html( $plugin_mode ) ;
echo  esc_html( SCFW_PLUGIN_VERSION ) ;
?></td>
            </tr>
            <tr>
                <td class="fr-1">
					<?php 
esc_html_e( 'License & Terms of use', 'size-chart-for-woocommerce' );
?>
                </td>
                <td class="fr-2">
                    <a href="<?php 
echo  esc_url( 'https://www.thedotstore.com/terms-and-conditions/' ) ;
?>" target="_blank">
						<?php 
esc_html_e( 'Click here', 'size-chart-for-woocommerce' );
?>
                    </a>
					<?php 
esc_html_e( 'to view license and terms of use.', 'size-chart-for-woocommerce' );
?>
                </td>
            </tr>
            <tr>
                <td class="fr-1">
					<?php 
esc_html_e( 'Help & Support', 'size-chart-for-woocommerce' );
?>
                </td>
                <td class="fr-2 wschart-information">
                    <ul>
                        <li>
                            <a href="<?php 
echo  esc_url( add_query_arg( array(
    'page' => 'size-chart-get-started',
), admin_url( 'admin.php' ) ) ) ;
?>" target="_blank">
								<?php 
esc_html_e( 'Quick Start', 'size-chart-for-woocommerce' );
?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php 
echo  esc_url( 'https://docs.thedotstore.com/category/239-premium-plugin-settings' ) ;
?>" target="_blank">
								<?php 
esc_html_e( 'Guide Documentation', 'size-chart-for-woocommerce' );
?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php 
echo  esc_url( 'https://www.thedotstore.com/support/' ) ;
?>" target="_blank">
								<?php 
esc_html_e( 'Support Forum', 'size-chart-for-woocommerce' );
?>
                            </a>
                        </li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td class="fr-1">
					<?php 
esc_html_e( 'Localization', 'size-chart-for-woocommerce' );
?>
                </td>
                <td class="fr-2">
					<?php 
esc_html_e( 'German', 'size-chart-for-woocommerce' );
?> , <?php 
esc_html_e( 'Spanish', 'size-chart-for-woocommerce' );
?> , <?php 
esc_html_e( 'French', 'size-chart-for-woocommerce' );
?> , <?php 
esc_html_e( 'Polish', 'size-chart-for-woocommerce' );
?>
                </td>
            </tr>
            <tr>
                <td class="fr-1">
                    <?php 
esc_html_e( 'Shortcode', 'size-chart-for-woocommerce' );
?>
                </td>
                <td class="fr-2">
                    <?php 
esc_html_e( '[scfw_product_size_chart]', 'size-chart-for-woocommerce' );
?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
