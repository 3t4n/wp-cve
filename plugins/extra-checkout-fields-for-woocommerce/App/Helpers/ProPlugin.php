<?php

namespace ECFFW\App\Helpers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class ProPlugin
{
    /**
     * Check the Pro plugin is active or not.
     * @return bool
     */
    public static function isActive()
    {
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins', array()));
        if (is_multisite()) {
            $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
        }
        return in_array('extra-checkout-fields-for-woocommerce-pro/extra-checkout-fields-for-woocommerce-pro.php', $active_plugins, false) || array_key_exists('extra-checkout-fields-for-woocommerce-pro/extra-checkout-fields-for-woocommerce-pro.php', $active_plugins);
    }

    /**
     * Display Pro Features in Settings.
     */
    public static function displaySettingsFeatures()
    {
        add_action('ecffw_settings_page_after_rows', function () {
            ?>
                <tr valign="top">
                    <th scope="row">
                        <h2 style="margin: 0; margin-bottom: -10px; color: gray;"><?php _e("File Upload", 'extra-checkout-fields-for-woocommerce'); ?></h2>
                    </th>
                    <td></td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label style="color: gray;"><?php _e("Enable", 'extra-checkout-fields-for-woocommerce'); ?></label>
                    </th>
                    <td>
                        <input type="checkbox" id="ecffw-file-upload-enable" name="ecffw-file-upload-enable" disabled="disabled"/>
                        <label style="color: gray;" for="ecffw-file-upload-enable">
                            <?php _e("Upload Files", 'extra-checkout-fields-for-woocommerce'); ?> - 
                            <a href="<?php echo esc_url(ECFFW_PRO_PLUGIN_URL); ?>" target="_blank" style="color: #00b559;">
                                <?php _e("Upgrade to PRO", 'extra-checkout-fields-for-woocommerce'); ?>
                            </a>
                        </label>
                    </td>
                </tr>
            <?php
        });
    }

    /**
     * Display List of Pro Features.
     */
    public static function displayListFeatures()
    {
        ?>
            <ul>
                <li><span class="dashicons dashicons-saved"></span> Date Picker</li>
                <li><span class="dashicons dashicons-saved"></span> Time Picker</li>
                <li><span class="dashicons dashicons-saved"></span> DateTime Picker</li>
                <li><span class="dashicons dashicons-saved"></span> Color Picker</li>
                <li><span class="dashicons dashicons-saved"></span> Checkbox</li>
                <li><span class="dashicons dashicons-saved"></span> Radio Buttons</li>
                <li><span class="dashicons dashicons-saved"></span> File Upload</li>
                <li><span class="dashicons dashicons-saved"></span> Description / Help Text</li>
            </ul>
            <a href="<?php echo esc_url(ECFFW_PRO_PLUGIN_URL); ?>" target="_blank" class="button-primary" style="background: #00b559; border-color: #00a650;">
                <?php _e("Upgrade Now", 'extra-checkout-fields-for-woocommerce'); ?>
            </a>
        <?php
    }
}
