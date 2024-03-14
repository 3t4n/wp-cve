<?php
/**
 * SecurePay.
 *
 * @author  SecurePay Sdn Bhd
 * @license GPL-2.0+
 *
 * @see    https://securepay.net
 */
\defined('ABSPATH') || exit;

final class SecurePay
{
    private static function register_locale()
    {
        add_action(
            'plugins_loaded',
            function () {
                load_plugin_textdomain(
                    'securepay',
                    false,
                    SECUREPAY_PATH.'languages/'
                );
            },
            0
        );
    }

    public static function register_admin_hooks()
    {
        add_filter(
            'plugin_action_links_'.SECUREPAY_HOOK,
            function ($links) {
                array_unshift(
                    $links,
                    sprintf(
                        '<a href="%s">%s</a>',
                        admin_url('admin.php?page=wc-settings&tab=checkout&section='.SECUREPAY_SLUG),
                        __('Settings', 'securepay')
                    )
                );

                return $links;
            }
        );

        add_filter(
            'woocommerce_payment_gateways',
            function ($gateways) {
                $gateways[] = 'WC_Gateway_SecurePay';

                return $gateways;
            }
        );

        add_action(
            'plugins_loaded',
            function () {
                if (self::is_woocommerce_activated()) {
                    require_once __DIR__.'/WC_Gateway_SecurePay.php';
                    add_filter(
                        'woocommerce_get_sections_checkout',
                        function ($sections) {
                            return $sections;
                        },
                        \PHP_INT_MAX
                    );
                }

                if (current_user_can(apply_filters('capability', 'manage_options'))) {
                    add_action('all_admin_notices', [__CLASS__, 'callback_compatibility'], \PHP_INT_MAX);
                }
            }
        );

        add_action(
            'init',
            function () {
                if (!empty($_GET['wc-api'])) {
                    // capture
                    WC()->payment_gateways();

                    if ('wc_gateway_securepay_capture_response' === $_GET['wc-api']) {
                        do_action('woocommerce_wc_gateway_securepay_capture_response');
                    }
                }

                if (isset($_POST['securepaybuyerbank']) && 'securepay' === WC()->session->get('chosen_payment_method')) {
                    $bank = sanitize_text_field($_POST['securepaybuyerbank']);
                    WC()->session->set('securepay_buyerbankcode', $bank);
                    exit($bank);
                }
            },
            \PHP_INT_MAX
        );
    }

    private static function is_woocommerce_activated()
    {
        return class_exists('WooCommerce');
    }

    private static function register_autoupdates()
    {
        $options = get_option('woocommerce_securepay_settings', false);
        if (!empty($options) && \is_array($options) && !empty($options['securepay_autoupdate']) && 'yes' === $options['securepay_autoupdate']) {
            add_filter(
                'auto_update_plugin',
                function ($update, $item) {
                    if (SECUREPAY_SLUG === $item->slug) {
                        return true;
                    }

                    return $update;
                },
                \PHP_INT_MAX,
                2
            );
        }
    }

    public static function callback_compatibility()
    {
        if (!self::is_woocommerce_activated()) {
            $html = '<div id="securepay-notice" class="notice notice-error is-dismissible">';
            $html .= '<p>'.esc_html__('SecurePay require WooCommerce plugin. Please install and activate.', 'securepay').'</p>';
            $html .= '</div>';
            echo $html;
        }
    }

    public static function activate()
    {
        return true;
    }

    public static function deactivate()
    {
        return true;
    }

    public static function uninstall()
    {
        return true;
    }

    public static function register_plugin_hooks()
    {
        register_activation_hook(SECUREPAY_HOOK, [__CLASS__, 'activate']);
        register_deactivation_hook(SECUREPAY_HOOK, [__CLASS__, 'deactivate']);
        register_uninstall_hook(SECUREPAY_HOOK, [__CLASS__, 'uninstall']);
    }

    public static function attach()
    {
        self::register_locale();
        self::register_plugin_hooks();
        self::register_admin_hooks();
        self::register_autoupdates();
    }
}
