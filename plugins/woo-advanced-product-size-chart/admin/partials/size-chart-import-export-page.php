<?php
/**
 * Handles free plugin user dashboard
 * 
 * @package SCFW_Size_Chart_For_Woocommerce
 * @since   2.4.0
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
    <div class="thedotstore-main-table res-cl scfw-import-export-table">
        <h2><?php esc_html_e( 'Import & Export Settings', 'size-chart-for-woocommerce' ); ?></h2>
        <table class="table-outer">
            <tbody>
                <tr>
                    <th scope="row" class="titledesc">
                        <label for="blogname"><strong><?php echo esc_html__( 'Export Size Chart Rule', 'size-chart-for-woocommerce' ); ?></strong></label>
                    </th>
                    <td>
                        <div class="whsm_main_container export_settings_container">
                            <p class="scfw_button_container">
                                <input type="button" name="scfw_export_settings" id="scfw_export_settings" class="button button-primary" value="<?php esc_attr_e( 'Export', 'size-chart-for-woocommerce' ); ?>" />
                            </p>
                            <p class="scfw_content_container">
                                <?php wp_nonce_field( 'scfw_export_save_action_nonce', 'scfw_export_action_nonce' ); ?>
                                <input type="hidden" name="scfw_export_action" value="scfw_export_settings_action"/>
                                <span><?php esc_html_e( 'Export the size chart rules settings for this site as a .json file. This allows you to easily import the configuration into another site. Please make sure simple product and variation products slugs must be unique.', 'size-chart-for-woocommerce' ); ?></span>
                            </p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="titledesc"><label
                            for="blogname"><strong><?php echo esc_html__( 'Import Size Chart Rule', 'size-chart-for-woocommerce' ); ?></strong></label>
                    </th>
                    <td>
                        <div class="scfw_main_container import_settings_container">
                            <p class="scfw_file_container">
                                <input type="file" name="import_file" accept="application/json" />
                            </p>
                            <p class="scfw_button_container">
                                <input type="button" name="scfw_import_settings" id="scfw_import_settings" class="button button-primary" value="<?php esc_attr_e( 'Import', 'size-chart-for-woocommerce' ); ?>" />
                            </p>
                            <p class="scfw_content_container">
                                <input type="hidden" name="scfw_import_action" value="scfw_import_settings_action"/>
                                <?php wp_nonce_field( 'scfw_import_action_nonce', 'scfw_import_action_nonce' ); ?>
                                <span><?php esc_html_e( 'Import the size chart rules settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'size-chart-for-woocommerce' ); ?></span>
                            </p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
<?php
