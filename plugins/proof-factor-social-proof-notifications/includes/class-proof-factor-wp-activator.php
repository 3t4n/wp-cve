<?php

/**
 * Fired during plugin activation
 *
 * @link       https://prooffactor.com
 * @since      1.0.0
 *
 * @package    Proof_Factor_WP
 * @subpackage Proof_Factor_WP/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Proof_Factor_WP
 * @subpackage Proof_Factor_WP/includes
 * @author     Proof Factor LLC <enea@prooffactor.com>
 */
class Proof_Factor_WP_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        $plugin_name = 'proof-factor-wp';
        $options = get_option($plugin_name);

        $redirect_key = $plugin_name . '_do_activation_redirect';
        add_option($redirect_key, true);

        if (isset($options) && empty($options) == false) {
            $proof_account_id = $options['account_id'];
            $payload = [
                'url' => home_url(),
                'email' => get_option('admin_email'),
                'blog_name' => get_option('blogname'),
                'account_id' => $proof_account_id
            ];
        } else {
            $payload = [
                'url' => home_url(),
                'email' => get_option('admin_email'),
                'blog_name' => get_option('blogname')
            ];
        }

        Proof_Factor_WP_Helper::remote_json_post("https://api.prooffactor.com/v1/partners/wordpress/activate", $payload);
    }
}
