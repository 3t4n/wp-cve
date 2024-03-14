<?php

/**
 * Helper functions
 *
 * @link       https://prooffactor.com
 * @since      1.0.0
 *
 * @package    Proof_Factor_WP
 * @subpackage Proof_Factor_WP/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Proof_Factor_WP
 * @subpackage Proof_Factor_WP/includes
 * @author     Proof Factor LLC <enea@prooffactor.com>
 */
class Proof_Factor_WP_Helper
{

    public static function uninstall()
    {
        $plugin_name = 'proof-factor-wp';
        $options = get_option($plugin_name);

        if (isset($options) && empty($options) == false) {

            $proof_account_id = $options['account_id'];
            $payload = [
                'url' => home_url(),
                'email' => get_option('admin_email'),
                'blog_name' => get_option('blogname'),
                'account_id' => $proof_account_id
            ];

            Proof_Factor_WP_Helper::remote_json_post("https://api.prooffactor.com/v1/partners/wordpress/uninstall", $payload);
        }

        delete_option($plugin_name);
    }

    public static function remote_json_post($url, $params)
    {
        $args = array('headers' => array('Content-Type' => 'application/json'),
            'body' => json_encode($params),
            'timeout' => 30);
        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            if ($response->get_error_message() == 'cURL error 35: SSL connect error') {
                try {
                    return json_decode(file_get_contents($url), true);
                } catch (Exception $e) {
                    echo '<p class="warning">';
                    _e('Error accrued while contacting our server!');
                    echo '<br />';
                    _e('Error data: ');
                    echo $e->getMessage();
                    echo '</p>';
                }
            } else {
                echo '<p class="warning">';
                _e('Error accrued while contacting our server!');
                echo '<br />';
                _e('Error data: ');
                echo $response->get_error_data();
                echo '<br />';
                _e('Error message: ');
                echo $response->get_error_message();
                echo '<br />';
                _e('Error code: ');
                echo $response->get_error_code();
                echo "</p>";
            }
            // error, CURL not installed, firewall blocked or Fomo server down
        } else {
            if ($response != null && isset($response['body'])) {
                return json_decode($response['body'], true);
            }
        }
    }
}
