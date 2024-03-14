<?php
namespace TenWebPluginIO;

use Tenweb_Authorization\Helper;
use Tenweb_Authorization\Login;

class Connect
{
    const FROM_PLUGIN = '10webimageoptimizer';

    public static function getConnectionLink($endpoint = 'sign-up', $args = [])
    {
        // copied from manager.py
        $return_url = get_admin_url() . 'admin.php';
        if (is_multisite()) {
            $return_url = network_admin_url() . 'admin.php';
        }

        $token = wp_create_nonce('io_10web_connection');
        update_site_option(TENWEBIO_PREFIX . '_saved_nonce', $token);
        $return_url_args = array('page' => TENWEBIO_PREFIX . '_dashboard');
        $register_url_args = array(
            'site_url'            => urlencode(get_site_url()),
            'utm_source'          => self::FROM_PLUGIN,
            'from_plugin'         => self::FROM_PLUGIN,
            'utm_medium'          => 'freeplugin',
            'nonce'               => $token,
            'version'             => get_site_option(TENWEBIO_VERSION),
            'plugin_id'           => 69,
            'subscr_id'           => TENWEB_SO_FREE_SUBSCRIPTION_ID,
            'new_connection_flow' => 1,
        );
        if (!empty($args['old_connection_flow'])) {
            unset($register_url_args['new_connection_flow']);
        }

        if (!empty($args)) {
            $register_url_args = $register_url_args + $args;
            $return_url_args = $return_url_args + $args;
        }

        $register_url_args['return_url'] = urlencode(add_query_arg($return_url_args, $return_url));

        $plugin_from = get_site_option("tenweb_manager_installed");
        if ($plugin_from !== false) {
            $plugin_from = json_decode($plugin_from, true);
            if (is_array($plugin_from) && reset($plugin_from) !== false) {
                $register_url_args['plugin_id'] = reset($plugin_from);
                if (isset($plugin_from["type"])) {
                    $register_url_args['utm_source'] = $plugin_from["type"];
                }
            }
        }

        $url = add_query_arg($register_url_args, TENWEB_DASHBOARD . '/' . $endpoint . '/');

        return $url;
    }

    public static function connectToTenweb($parameters = null)
    {

        if (empty($parameters)) {
            $parameters = array();
            $parameters['email'] = !empty($_GET['email']) ? sanitize_email($_GET['email']) : null;
            $parameters['token'] = !empty($_GET['token']) ? sanitize_text_field($_GET['token']) : null;
            $parameters['new_connection_flow'] = !empty($_GET['new_connection_flow']) ? rest_sanitize_boolean($_GET['new_connection_flow']) : null;
            $parameters['sign_up_from_free_plugin'] = !empty($_GET['sign_up_from_free_plugin']) ? rest_sanitize_boolean($_GET['sign_up_from_free_plugin']) : null;
        }
        $email = !empty($parameters['email']) ? sanitize_email($parameters['email']) : null;
        $token = !empty($parameters['token']) ? sanitize_text_field($parameters['token']) : null;
        $new_connection_flow = !empty($parameters['new_connection_flow']);
        $sign_up_from_free_plugin = !empty($parameters['sign_up_from_free_plugin']);

        if (!empty($email) && !empty($token)) {
            $pwd = md5($token);
            $class_login = Login::get_instance();
            $args = ['connected_from' => 'image_optimizer'];
            if ($class_login->login($email, $pwd, $token, $args) && $class_login->check_logged_in()) {
                Helper::remove_error_logs();
                $domain_id = get_site_option(TENWEB_PREFIX . '_domain_id');
                update_site_option(TENWEB_PREFIX . '_from_image_optimizer', true);
                if ( empty(get_option('tenwebio_was_connected') ) ) {
                    //save this option to know that IO was connected even once
                    update_option('tenwebio_was_connected', 'io_was_connected', false);
                }
                Helper::check_site_state(true);
                while (ob_get_level() !== 0) {
                    ob_end_clean();
                }
                if ($new_connection_flow) {
                    // Clear all unexpected output. We don't want to see a warning in rest response.
                    die(json_encode(array("connected_domain_id" => $domain_id)));
                }
                /*$url = TENWEB_DASHBOARD . '/websites?optimizing_website=' . $domain_id .'&from_plugin='.self::FROM_PLUGIN
                wp_redirect($url);*/
            } else {
                $errors = $class_login->get_errors();
                $err_msg = (!empty($errors)) ? $errors['message'] : 'Something went wrong. ';
                set_site_transient('image_optimizer_auth_error_logs', $err_msg, MINUTE_IN_SECONDS);
            }

        }
    }

    public static function disconnectFromTenweb()
    {
        //TODO check options that needs to be deleted
        $class_login = Login::get_instance();
        Helper::remove_error_logs();
        delete_site_option(TENWEB_PREFIX . '_from_image_optimizer');

        //check if booster is active and disconnect Booster too
        if ( class_exists('\TenWebOptimizer\OptimizerAdmin')) {
            self::disconnectFromBooster();
        }
        $class_login->logout(false);
        if (is_multisite()) {
            wp_redirect(network_admin_url() . 'admin.php?page=iowd_dashboard');
        }
        wp_redirect(get_admin_url() . 'admin.php?page=iowd_dashboard');
    }

    public static function ImageOptimizerConnected()
    {
        return (
            (defined('TENWEB_SO_HOSTED_ON_10WEB') && TENWEB_SO_HOSTED_ON_10WEB)
            || (defined('TENWEB_CONNECTED_SPEED') &&
                Login::get_instance()->check_logged_in() &&
                Login::get_instance()->get_connection_type() == TENWEB_CONNECTED_SPEED)
        );
    }

    public static function disconnectFromBooster() {
        global $TwoSettings;
        $TwoSettings->update_setting("two_connected", "0");
        $TwoSettings->sync_configs_with_plugin_state('inactive');
        delete_site_option("first_critical_generation_flag");
        delete_option("two_flow_status");
        delete_option("two_triggerPostOptimizationTasks");
        delete_option("incompatible_plugins_active_send");
        delete_option("flow_score_check_init");
        delete_option("two_flow_score_log");
        delete_option('two_clear_cache_from');
        //deleting option which is showing IO connection
        delete_site_option(TENWEB_PREFIX . '_from_image_optimizer');
        delete_site_option(TENWEB_PREFIX . '_client_referral_hash');
        delete_site_option(TW_OPTIMIZE_PREFIX . '_saved_nonce');
        \TenWebOptimizer\OptimizerAdmin::clear_cache(false, true);
        \TenWebOptimizer\OptimizerAdmin::two_uninstall();
    }
}