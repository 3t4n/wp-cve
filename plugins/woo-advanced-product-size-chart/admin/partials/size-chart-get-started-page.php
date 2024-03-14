<?php
/**
 * Handles free plugin user dashboard
 * 
 * @package SCFW_Size_Chart_For_Woocommerce
 * @since   1.0.0
 */
 
// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	exit;
}

$file_dir_path = 'header/plugin-header.php';
if ( file_exists( plugin_dir_path( __FILE__ ) . $file_dir_path ) ) {
	require_once plugin_dir_path( __FILE__ ) . $file_dir_path;
}
?>
    <div class="thedotstore-main-table res-cl">
        <h2><?php esc_html_e( 'Getting Started', 'size-chart-for-woocommerce' ); ?></h2>
        <table class="table-outer">
            <tbody>
            <tr>
                <td class="fr-2">
                    <h4><strong><?php esc_html_e( 'Default Size Chart Template:', 'size-chart-for-woocommerce' ); ?></strong></h4>
                    <p class="block textgetting"><?php esc_html_e( 'Product Size Charts for WooCommerce plugin provides a pre-designed size chart template that you can easily apply to your products or categories, saving you time and effort.', 'size-chart-for-woocommerce' ); ?></p>
                    <h4><strong><?php esc_html_e( 'Create Your Own Size Guide:', 'size-chart-for-woocommerce' ); ?></strong></h4>
                    <p class="block textgetting"><?php esc_html_e( 'With this plugin, you have the flexibility to customize or clone the default size chart and create your own size guide tailored to your unique needs and products.', 'size-chart-for-woocommerce' ); ?></p>
                    <h4><strong><?php esc_html_e( 'Comprehensive Display:', 'size-chart-for-woocommerce' ); ?></strong></h4>
                    <p class="block textgetting"><?php esc_html_e( 'By utilizing this plugin, you can ensure that customers have a clear understanding of your products\' sizing information, reducing the need for unnecessary inquiries and improving the buying experience.', 'size-chart-for-woocommerce' ); ?></p>
                    <h4><strong><?php esc_html_e( 'Size Chart Customization and Management:', 'size-chart-for-woocommerce' ); ?></strong></h4>
                    <p class="block textgetting">
						<?php esc_html_e( 'This plugin offers the convenience of assigning ready-to-use default size chart templates to your WooCommerce products, as well as the ability to create custom size charts. You can clone existing templates or design your own size charts and assign them to specific products or categories, providing comprehensive size information to your customers.', 'size-chart-for-woocommerce' ); ?>
                    </p>
                    <p class="block textgetting">
						<?php esc_html_e( 'You can edit any of the size charts available in the plugin, preview or clone them.', 'size-chart-for-woocommerce' ); ?>
                        <span class="gettingstarted">
                            <img src="<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) . 'images/thedotstore-images/screenshots/Getting_Started_01.png' ); ?>" alt="<?php esc_attr_e( 'Getting_Started_01', 'size-chart-for-woocommerce' ); ?>">
                        </span>
                    </p>
                    <p class="block textgetting">
						<?php esc_html_e( 'For each size chart, you can add label, chart image for which you want the chart to appear, chart position (modal popup/additional tab on product page) and table style.', 'size-chart-for-woocommerce' ); ?>
                        <span class="gettingstarted">
                            <img src="<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) . 'images/thedotstore-images/screenshots/Getting_Started_02.png' ); ?>" alt="<?php esc_attr_e( 'Getting_Started_02', 'size-chart-for-woocommerce' ); ?>">
                        </span>
                        <span class="gettingstarted">
                            <img src="<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) . 'images/thedotstore-images/screenshots/Getting_Started_03.png' ); ?>" alt="<?php esc_attr_e( 'Getting_Started_03', 'size-chart-for-woocommerce' ); ?>">
                        </span>
                    </p>
                    <p class="block textgetting">
						<?php esc_html_e( 'For each size chart, you can create your custom chart table (with as many rows and columns you would like to include)', 'size-chart-for-woocommerce' ); ?>
                        <span class="gettingstarted">
                            <img src="<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) . 'images/thedotstore-images/screenshots/Getting_Started_04.png' ); ?>" alt="<?php esc_attr_e( 'Getting_Started_04', 'size-chart-for-woocommerce' ); ?>">
                        </span>
                    </p>
                    <p class="block textgetting">
						<?php esc_html_e( 'Plugin settings offers the option to change the label of size chart tab and modal popup, which is displayed in product page.)', 'size-chart-for-woocommerce' ); ?>
                        <span class="gettingstarted">
                            <img src="<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) . 'images/thedotstore-images/screenshots/Getting_Started_05.png' ); ?>" alt="<?php esc_attr_e( 'Getting_Started_05', 'size-chart-for-woocommerce' ); ?>">
                        </span>
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
<?php
