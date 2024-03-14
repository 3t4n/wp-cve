<?php
/**
 * Plugin Name: دومینوکیت
 * Description: ووکامرس خود را سفارشی سازی کنید
 * Plugin URI:  https://dominodev.com/sandbox/dominokit-landing/dominokit
 * Version:     1.1.0
 * Author:      dominodev
 * Author URI:  https://www.zhaket.com/store/web/dominodev
 * Text Domain: dominokit
 */

if (!defined('ABSPATH')) {
    exit;
}

define('DOMKIT_PLUGIN_BASE', plugin_basename(__FILE__));
define('DOMKIT_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('DOMKIT_INCLUDE', DOMKIT_DIR . 'includes');
define('DOMKIT_APP', DOMKIT_DIR . 'app');
define('DOMKIT_TEMPLATE', DOMKIT_DIR . 'templates');
define('DOMKIT_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('DOMKIT_ASSETS', DOMKIT_URL . 'assets');
define('DOMKIT_IMAGES', DOMKIT_URL . 'assets/images');
define('DOMKIT_DEBUG', true);
define('DOMKIT_PRO_DIR', ABSPATH . 'wp-content/plugins/dominokit-pro');


final class dominokit
{
    /**
     * Minimum PHP Version
     *
     * @since 1.2.0
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '7.4';

    /**
     * @var null
     * instance in class dominokit
     */
    private static $_instance = null;

    /**
     * dominokit constructor.
     */
    public function __construct()
    {
        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', array($this, 'admin_notice_minimum_php_version'));
            return;
        }

        require_once('vendor/autoload.php');

        // Load translation
        add_action('init', array($this, 'i18n'));

        add_filter('plugin_row_meta', array($this, 'domino_live_demo_meta_links_callback'), 10, 2);

        add_filter('plugin_action_links_' . DOMKIT_PLUGIN_BASE, array($this, 'domino_plugin_action_links_callback'));

        if (!function_exists('get_plugin_data')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        $get_plugin_data = get_plugin_data(__FILE__);
        $GLOBALS['version'] = $get_plugin_data["Version"];
        
        if (!class_exists('DominoKitController')) {
            require_once DOMKIT_APP . '/DominoKitController.php';
        }
    }

    /**
     * add language in plugin dominokit
     */
    public function i18n()
    {
        load_plugin_textdomain('dominokit', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * @param $meta_fields
     * @param $file
     * @return mixed
     * add live demo plugin row meta
     */
    public function domino_live_demo_meta_links_callback($meta_fields, $file)
    {
        if (plugin_basename(__FILE__) == $file) {
            $plugin_url = "https://dominodev.com/sandbox/dominokit-landing/dominokit/";
            $meta_fields[] = "<a href='" . esc_url($plugin_url) . "' target='_blank' title='" . esc_html__(__('Live Demo', 'dominokit')) . "'><i class='fa fa-desktop' aria-hidden='true'>" . "&nbsp;<span>" . esc_html__(__('Live Demo', 'dominokit')) . "</span>" . "</i></a>";
        }
        return $meta_fields;
    }

    /**
     * @param $links
     * @return mixed
     */
    public function domino_plugin_action_links_callback($links)
    {
        $settings_link = sprintf('<a href="%1$s">%2$s</a>', admin_url('admin.php?page=dominokit'), esc_html__('Settings', 'dominokit'));

        array_unshift($links, $settings_link);

        $links['go_dominokit_pro'] = sprintf('<a href="%1$s" target="_blank" class="dominokit-plugins-gopro">%2$s</a>', 'https://www.zhaket.com/web/dominokit-plugin', esc_html__('Get Dominokit Pro', 'dominokit'));

        ?>
        <style>
            .dominokit-plugins-gopro {
                font-weight: bold;
                color: #524DF1;
            }
        </style>
        <?php

        return $links;
    }

    /**
     * Notice minimum php version
     */
    public function admin_notice_minimum_php_version()
    {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'dominokit'),
            '<strong>' . esc_html__('woocommerce toolkit', 'dominokit') . '</strong>',
            '<strong>' . esc_html__('PHP', 'dominokit') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        $html_message = sprintf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);

        echo wp_kses_post($html_message);
    }

    /**
     * @return dominokit|null
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}

dominokit::instance();
