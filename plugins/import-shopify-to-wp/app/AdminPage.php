<?php

namespace S2WPImporter;

use S2WPImporter\Plugins\Plugin;
use S2WPImporter\Plugins\PluginCheckCallbacks;

class AdminPage
{
    private $parent_slug = 'tools.php';

    private $capability = 'manage_options';

    private $menu_slug = 'import-shopify-to-wp';

    private $position = null;

    private $recommendedPlugins = [
            'all-in-one-seo-pack',
            'coming-soon',
            'wpforms-lite',
            'optinmonster',
            'google-analytics-for-wordpress',
            'wp-mail-smtp',
            'trustpulse-api',
    ];

    public function init()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
        add_action('admin_menu', [$this, 'register']);
        add_action('init', [$this, 'initSettings']);
    }

    public function settings(Settings $s)
    {
//        $s->addInteger('file_id');
    }

    public function enqueue($hook_suffix)
    {
        if ("tools_page_{$this->menu_slug}" === $hook_suffix) {
            $asset = include S2WP_IMPORTER_DIR . 'js/main.min.asset.php';
            wp_enqueue_script(
                    's2wp-importer-app-script',
                    S2WP_IMPORTER_URI . 'js/main.min.js',
                    array_merge($asset['dependencies'], ['wp-api']),
                    $asset['version'],
                    true
            );

            wp_localize_script('s2wp-importer-app-script', 'shopify2wp', [
                    'woocommerce_status' => class_exists('WooCommerce') ? 'active' : 'inactive',
                    'products' => (new Files('products'))->getLastFileData(),
                    'products_total_pages' => (new Files('products'))->getTotalFiles(),
                    'products_current_page' => (new Files('products'))->getLastFileNumber(),
                    'products_import_complete' => (new Files('products'))->getLastFileNumber() > (new Files('products'))->getTotalFiles(),

                    'customers' => (new Files('customers'))->getLastFileData(),
                    'customers_total_pages' => (new Files('customers'))->getTotalFiles(),
                    'customers_current_page' => (new Files('customers'))->getLastFileNumber(),
                    'customers_import_complete' => (new Files('customers'))->getLastFileNumber() > (new Files('customers'))->getTotalFiles(),

                    'orders' => (new Files('orders'))->getLastFileData(),
                    'orders_total_pages' => (new Files('orders'))->getTotalFiles(),
                    'orders_current_page' => (new Files('orders'))->getLastFileNumber(),
                    'orders_import_complete' => (new Files('orders'))->getLastFileNumber() > (new Files('orders'))->getTotalFiles(),

                    'current_step' => get_option('shopify2wp_current_step', 'upload'),
                    'ajaxNonce' => wp_create_nonce('s2wp'),
                    'adminUrl' => admin_url(),

                    'ignoredPlugins' => $this->installedPlugins(),

                    'shopUrl' => function_exists('wc_get_page_id') ? get_permalink( wc_get_page_id( 'shop' ) ) : 0,
                    'shopAdminUrl' => admin_url( 'admin.php?page=wc-admin' )
            ]);

            wp_enqueue_style(
                    's2wp-importer-app-style',
                    S2WP_IMPORTER_URI . 'css/main.min.css',
                    ['wp-components'],
                    S2WP_IMPORTER_VERSION
            );
        }
    }

    public function register()
    {
        add_submenu_page(
                $this->parent_slug,
                __('Shopify Importer', 'import-shopify-to-wp'),
                __('Shopify Importer', 'import-shopify-to-wp'),
                $this->capability,
                $this->menu_slug,
                [$this, 'page'],
                $this->position
        );
    }

    public function page()
    {
        ?>
        <div class="import-shopify-to-wp-wrap">
            <div id="import-shopify-to-wp-app"></div>
        </div>
        <?php
    }

    public function initSettings()
    {
        $this->settings(new Settings());
    }

    public function installedPlugins()
    {
        return array_filter($this->recommendedPlugins, function ($slug) {
            $plugin = new Plugin($slug);

            $plugin->setIsActiveCallback(PluginCheckCallbacks::getCallback($slug));

            return $plugin->isInstalled();
        });
    }

}
