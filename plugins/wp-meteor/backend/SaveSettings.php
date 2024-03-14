<?php

/**
 * WP_Meteor
 *
 * @package   WP_Meteor
 * @author    Aleksandr Guidrevitch <alex@excitingstartup.com>
 * @copyright 2020 wp-meteor.com
 * @license   GPL 2.0+
 * @link      https://wp-meteor.com
 */

namespace WP_Meteor\Backend;

use WP_Meteor\Engine\Base;

/**
 * Provide Import and Export of the settings of the plugin
 */
class SaveSettings extends Base
{

    /**
     * Initialize the class.
     *
     * @return void
     */
    public function initialize()
    {
        // Add the save settings method
        \add_action('admin_init', array($this, 'save_settings'));
    }

    /**
     * Process a settings export from config
     *
     * @since 1.0.0
     * @return void
     */
    public function save_settings()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (defined('DOING_AJAX') && DOING_AJAX) {
            return;
        }

        if (empty($_POST['wpmeteor_action'])) {
            return;
        }

        $sanitized = \wpmeteor_sanitive_recursively($_POST);

        $nonce = $sanitized['wpmeteor_save_settings_nonce'];
        unset($sanitized['wpmeteor_save_settings_nonce']);

        if (empty($sanitized['wpmeteor_action']) || 'save_settings' !== $sanitized['wpmeteor_action']) {
            return [];
        }

        //a:8:{s:10:"wpmeteor_action";s:13:"save_settings";s:16:"_wp_http_referer";s:55:"/wp-admin/options-general.php?page=wp-meteor";s:8:"defaults";a:3:{s:7:"enabled";s:2:"on";s:5:"after";s:12:"WINDOWLOADED";s:5:"delay";s:1:"3";}s:7:"addthis";a:4:{s:7:"enabled";s:2:"on";s:8:"override";s:2:"on";s:5:"after";s:3:"LCP";s:5:"delay";s:1:"3";}s:5:"drift";a:4:{s:7:"enabled";s:2:"on";s:8:"override";s:2:"on";s:5:"after";s:12:"WINDOWLOADED";s:5:"delay";s:1:"2";}s:12:"optinmonster";a:4:{s:7:"enabled";s:2:"on";s:8:"override";s:2:"on";s:5:"after";s:3:"LCP";s:5:"delay";s:1:"3";}s:13:"marketo-forms";a:1:{s:9:"selectors";a:1:{i:0;s:17:"form[data-formid]";}}s:6:"submit";s:12:"Save Changes";}

        if (!\wp_verify_nonce($nonce, 'wpmeteor_save_settings_nonce')) {
            return [];
        }

        if (!\current_user_can('manage_options')) {
            return [];
        }

        unset($sanitized['wpmeteor_action']);
        unset($sanitized['submit']);
        unset($sanitized['_wp_http_referer']);


        // var_dump($sanitized); exit;
        $settings = \apply_filters(WPMETEOR_TEXTDOMAIN . '-backend-save-settings', $sanitized, \wpmeteor_get_settings());

        unset($settings['wpmeteor_action']);
        unset($settings['submit']);
        unset($settings['_wp_http_referer']);
        $settings['v'] = WPMETEOR_VERSION;

        \wpmeteor_set_settings($settings);
        \wpdesk_wp_notice('Successfully saved!', 'success', true);
    }
}
