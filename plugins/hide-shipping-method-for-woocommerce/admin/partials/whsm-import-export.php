<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once(plugin_dir_path( __FILE__ ).'header/plugin-header.php');
?>
<div class="whsm-section-left">
    <div class="whsm-import-export-section whsm-main-table table-outer res-cl">
        <h2><?php echo esc_html__( 'Import & Export Hide Shipping Method Rule', 'woo-hide-shipping-methods' ); ?></h2>
        <table class="form-table table-outer shipping-method-table">
            <tbody>
                <tr>
                    <th>
                        <label for="blogname"><?php echo esc_html__( 'Export Rule', 'woo-hide-shipping-methods' ); ?></label>
                    </th>
                    <td>
                        <div class="whsm_main_container export_settings_container">
                            <p class="whsm_button_container">
                                <input type="button" name="whsm_export_settings" id="whsm_export_settings" class="button button-primary" value="<?php esc_attr_e( 'Export', 'woo-hide-shipping-methods' ); ?>" />
                            </p>
                            <p class="whsm_content_container">
                                <?php wp_nonce_field( 'whsm_export_save_action_nonce', 'whsm_export_action_nonce' ); ?>
                                <input type="hidden" name="whsm_export_action" value="whsm_export_settings_action"/>
                                <span><?php esc_html_e( 'Export the hide shipping method rules settings for this site as a .json file. This allows you to easily import the configuration into another site. Please make sure simple product and variation products slugs must be unique.', 'woo-hide-shipping-methods' ); ?></span>
                            </p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="blogname"><?php echo esc_html__( 'Import Rule', 'woo-hide-shipping-methods' ); ?></label>
                    </th>
                    <td>
                        <div class="whsm_main_container import_settings_container">
                            <p class="whsm_file_container">
                                <input type="file" name="import_file" accept="application/json" />
                            </p>
                            <p class="whsm_button_container">
                                <input type="button" name="whsm_import_settings" id="whsm_import_settings" class="button button-primary" value="<?php esc_attr_e( 'Import', 'woo-hide-shipping-methods' ); ?>" />
                            </p>
                            <p class="whsm_content_container">
                                <input type="hidden" name="whsm_import_action" value="whsm_import_settings_action"/>
                                <?php wp_nonce_field( 'whsm_import_action_nonce', 'whsm_import_action_nonce' ); ?>
                                <span><?php esc_html_e( 'Import the shipping method settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'woo-hide-shipping-methods' ); ?></span>
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
</div>
</div>
<?php
