<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/10/18
 * Time: 12:37 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Plugin;

use BeeketingConnect_beeketing_woocommerce\Common\Constants as CommonConstants;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\PluginConfig;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Constants;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Helper;

/**
 * Class Hooks
 * @property PluginConfig pluginConfig
 * @property Loader loader
 * @package BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Plugin
 *
 * Register hooks to Wordpress
 */
class Hooks
{
    private static $isWebhooksRegistered = false;

    /**
     * Hooks constructor.
     * @param PluginConfig pluginConfig
     * @param Loader $loader
     */
    public function __construct($pluginConfig, $loader)
    {
        $this->pluginConfig = $pluginConfig;
        $this->loader = $loader;
    }

    /**
     * Register hooks for admin pages, only run in admin
     */
    public function registerAdminHooks()
    {
        $config = $this->pluginConfig;
        // Menu and admin page
        add_action('admin_menu', function () use ($config) {
            // Add to admin_menu
            add_menu_page(
                __('Beeketing Menu Page'),
                __($config->getPluginName()),
                'edit_theme_options',
                $config->getPluginAdminUrl(),
                function () {
                    // Page content
                    include __DIR__ . '/../Views/admin_index.php';
                },
                plugins_url('images/icon/icon_menu.png', $config->getPluginDirName())
            );
        });

        // Add the plugin page Settings and Docs links
        add_filter('plugin_action_links_' . $config->getPluginBaseName(), function ($links) use ($config) {
            $moreLinks = array();
            $moreLinks['settings'] = '<a href="' . admin_url('admin.php?page=' . $config->getPluginAdminUrl()) . '">' . __('Settings', 'beeketing') . '</a>';

            return array_merge($moreLinks, $links);
        });

        // Setup admin homepage widget
        $this->setupWidget();

        // If this is home of beeketing admin settings page
        // Enqueue scripts
        if (isset($_GET['page']) && $_GET['page'] == $config->getPluginAdminUrl()) {
            $this->setupDashboardHome();
        } else if (isset($_SERVER['REQUEST_URI']) && (basename($_SERVER['REQUEST_URI']) == Constants::DASHBOARD_URI || basename($_SERVER['REQUEST_URI']) == Constants::DASHBOARD_URI_DEFAULT)) {
            // If not in admin of beeketing
            // Check and show notices if needed
            $this->setupNotices();
        }

        // Add plugins.js to /plugins.php
        add_action('current_screen', function () use ($config) {
            $screen = get_current_screen();
            if ($screen->id === 'plugins') {
                $migrationCookie = 'beeketing_migration';
                // Check update migration
                // If api key is not set
                if (!$this->loader->dataManager->settingManager->get(CommonConstants::SETTING_API_KEY)) {
                    // But setting key or old setting key `beeketing_api_key` is already existed in db
                    $oldSetting = false;
                    $oldSettingData = get_option(Constants::APP_SETTING_KEY);
                    if ($oldSettingData == false || !is_array($oldSettingData)) {
                        $oldSetting = true;
                    }

                    if (
                        ($oldSetting || get_option(CommonConstants::OLD_APP_SETTING_KEY ))
                        && (!isset($_COOKIE[$migrationCookie]) || !$_COOKIE[$migrationCookie])
                    ) {
                        // Mark that won't redirect here again in 1 month
                        $expiry = strtotime('+1 month');
                        setcookie($migrationCookie, 1, $expiry);

                        // Delete old app setting
                        delete_option(CommonConstants::OLD_APP_SETTING_KEY);

                        // Redirect to beeketing dashboard
                        wp_redirect(admin_url('admin.php?page=' . $config->getPluginAdminUrl()));
                        exit();
                    }
                }

                // Add plugins script
                $this->addPluginsScript();
            }
        });

        // Special action to disconnect plugin
        if (isset($_GET['action']) && $_GET['action'] === 'disconnect_beeketing') {
            delete_option(Constants::APP_SETTING_KEY);
            die('Disconnected');
        }
    }

    /**
     * Setup to show notices
     */
    private function setupNotices()
    {
        // If woocommerce is not active
        if (!Helper::isWooCommerceActive()) {
            // Show require woocommerce
            add_action('admin_notices', function () {
                $wooCommercePluginUrl = Helper::getWooCommercePluginUrl();
                include __DIR__ . '/../Views/require_woocommerce.php';
            });
        }

        // If API Key is not existed
        $loader = $this->loader;
        if (!$loader->getApiKey()) {
            // Add dashboard script
            $this->addDashboardScript();

            // Show notice to setup Beeketing plugin
            add_action('admin_notices', function () {
                include __DIR__ . '/../Views/setup_beeketing.php';
            });
        }
    }

    /**
     * Setup wordpress home widget
     */
    private function setupWidget()
    {
        add_action('wp_dashboard_setup', function () {
            $name = 'beeketing_dashboard_widget_banner';
            wp_add_dashboard_widget($name, 'Beeketing for WooCommerce', function () {
                include __DIR__ . '/../Views/widget.php';
            });

            Helper::highPriorityWidget($name);
            $this->addDashboardScript();
        });
    }

    /**
     * Setup home page of plugin dashboard
     */
    private function setupDashboardHome()
    {
        // Enqueue script
        $config = $this->pluginConfig;
        $scriptHandle = 'beeketing_app_script';

        wp_register_script($scriptHandle, Helper::getConnectDashboardUrl($config->getAppJs()), array(), true, true);
        wp_enqueue_script($scriptHandle);
        // add script plugin thinkbox
        add_thickbox();

        $this->injectAppParams($scriptHandle);
    }

    /**
     * Inject app params to use with Beeketing Connect Dashboard
     * @param $scriptHandle
     * @param bool $generateAccessToken
     */
    public function injectAppParams($scriptHandle, $generateAccessToken = true)
    {
        $config = $this->pluginConfig;
        // Flag to prevent inject twice
        global $injectedBeeketingAppParams;

        // Only inject once
        if ($injectedBeeketingAppParams === true) {
            return;
        }
        $injectedBeeketingAppParams = true;

        $currentUser = wp_get_current_user();
        $loader = $this->loader;

        wp_localize_script($scriptHandle, 'beeketing_app_vars', array(
            'plugin_url' => plugins_url('/', $config->getPluginDirName()),
            'api_key' => $loader->getApiKey(),
            'access_token' => $loader->dataManager->settingManager->getAccessToken($generateAccessToken),
            'auto_login_token' => $loader->dataManager->settingManager->get(CommonConstants::SETTING_AUTO_LOGIN_TOKEN),
            'shop_id' => $loader->dataManager->settingManager->get(CommonConstants::SETTING_SHOP_ID),
            'beeketing_user_id' => $loader->dataManager->settingManager->get(CommonConstants::SETTING_USER_ID),
            'user_display_name' => $currentUser->display_name,
            'user_email' => $currentUser->user_email,
            'site_url' => site_url(),
            'domain' => Helper::getShopDomain(),
            'env' => BEEKETINGWOOCOMMERCE_ENVIRONMENT,
            'is_woocommerce_active' => Helper::isWooCommerceActive(),
            'woocommerce_plugin_url' => Helper::getWooCommercePluginUrl(),
            'connect_admin_url' => admin_url('admin.php?page=' . $config->getPluginAdminUrl()),
            'platform' => Constants::PLATFORM_CODE,
            'admin_url' => admin_url(),
            'plugin' => $this->getPlugin(),
            'plugin_name' => $config->getPluginName()
        ));
    }

    /**
     * @return string
     */
    private function getPlugin()
    {
        $path = plugin_basename(__FILE__);

        if (strpos($path, "salespop") !== false || strpos($path, "sales_pop") !== false) {
            return "sale_notification";
        }

        return Constants::PLATFORM_CODE;
    }

    /**
     * Add dashboard script to page
     */
    private function addDashboardScript()
    {
        // Flag to prevent inject twice
        global $injectedBeeketingDashboardScript;

        // Only inject once
        if ($injectedBeeketingDashboardScript === true) {
            return;
        }
        $injectedBeeketingDashboardScript = true;

        $scriptHandle = 'beeketing_dashboard_script';
        wp_register_script($scriptHandle, Helper::getConnectDashboardUrl('woocommerce/dashboard.js'), array(), true, true);
        wp_enqueue_script($scriptHandle);
        $this->injectAppParams($scriptHandle, false);
    }

    /**
     * Add plugins script to page
     */
    private function addPluginsScript()
    {
        // Flag to prevent inject twice
        global $injectedBeeketingPluginScript;

        // Only inject once
        if ($injectedBeeketingPluginScript === true) {
            return;
        }
        $injectedBeeketingPluginScript = true;

        $scriptHandle = 'beeketing_plugins_script';
        wp_register_script($scriptHandle, Helper::getConnectDashboardUrl('plugins.js'), array(), true, true);
        wp_enqueue_script($scriptHandle);
        $this->injectAppParams($scriptHandle);
    }

    /**
     * Register webhooks
     */
    public function registerResourceWebhooks()
    {
        // Don't register webhook if plugin is not connected
        if (!$this->loader->dataManager->settingManager->get(CommonConstants::SETTING_API_KEY)) {
            return;
        }

        // Don't re-register resourceWebHook if already registered
        if(self::$isWebhooksRegistered) {
            return null;
        }

        self::$isWebhooksRegistered = true;

        $webhookHandler = $this->loader->webhookHandler;
        add_filter('woocommerce_update_options_general', array($webhookHandler, 'updateGeneralSettingsWC'), 10, 3);
        add_action('updated_option', array($webhookHandler, 'updateWPSetting'), 10, 3);

        // Create, update, delete collection webhook
        add_action('created_term', array($webhookHandler, 'createCollectionWebhook'), 10, 3);
        add_action('edited_term', array($webhookHandler, 'updateCollectionWebhook'), 10, 3);
        add_action('delete_term', array($webhookHandler, 'deleteCollectionWebhook'), 10, 3);

        // Create, update, delete customer webhook
        add_action('user_register', array($webhookHandler, 'createCustomerWebhook'), 10, 2);
        add_action('profile_update', array($webhookHandler, 'updateCustomerWebhook'), 10, 2);
        add_action('delete_user', array($webhookHandler, 'deleteCustomerWebhook'), 10, 2);

        // Order webhook
        if (Helper::isWc3()) {
            add_action('woocommerce_thankyou', array($webhookHandler, 'updateOrderWebhook'), 10);
        } else {
            add_action('woocommerce_checkout_update_order_meta', array($webhookHandler, 'updateOrderWebhook'), 10);
        }

        // Order updated
        add_action('woocommerce_process_shop_order_meta', array($webhookHandler, 'updateOrderWebhook'), 10);

        // Create, update, delete product webhook
        add_action('save_post', array($webhookHandler, 'updateProductWebhook'), 20);
        add_action('woocommerce_ajax_save_product_variations', array($webhookHandler, 'updateProductWebhook'), 30);
        add_action('trash_product', array($webhookHandler, 'deleteProductWebhook'), 10);
        add_action('untrashed_post', array($webhookHandler, 'updateProductWebhook'), 10);

        // Special listener for delete variation
        add_action('woocommerce_init', function () {
            if (isset($_POST['action']) && $_POST['action'] === 'woocommerce_remove_variations' && isset($_POST['variation_ids']) && is_array($_POST['variation_ids'])) {
                global $toUpdateProductIds;

                $toUpdateProductIds = array();
                foreach ($_POST['variation_ids'] as $variationId) {
                    $variationId = (int)$variationId;
                    $variant = wc_get_product($variationId);
                    if (!$variant) {
                        continue;
                    }
                    $toUpdateProductIds[] = Helper::isWc3() ? $variant->get_parent_id() : $variant->parent_id;
                }

                // Send webhook after process and before die
                if (count($toUpdateProductIds) > 0) {
                    add_filter('wp_die_ajax_handler', function () {
                        // Flag to run once
                        global $addVariationWebhookFlag;
                        if (isset($addVariationWebhookFlag) && $addVariationWebhookFlag === true) {
                            return;
                        }
                        $addVariationWebhookFlag = true;

                        global $toUpdateProductIds;
                        if (!isset($toUpdateProductIds) || !is_array($toUpdateProductIds)) {
                            return;
                        }

                        foreach ($toUpdateProductIds as $toUpdateProductId) {
                            $this->loader->webhookHandler->updateProductWebhook($toUpdateProductId);
                        }
                    });
                }
            }
        });
    }

    /**
     * Add script tag to site footer
     */
    public function addScriptTag()
    {
        // Don't add script tag if plugin is not connected
        if (!$this->loader->dataManager->settingManager->get(CommonConstants::SETTING_API_KEY)) {
            return;
        }

        // Add scripts
        add_action('wp_footer', function () {
            $loader = $this->loader;

            // vars used for template
            $scriptPath = BEEKETINGWOOCOMMERCE_SDK_PATH;
            $shopApiKey = $loader->dataManager->settingManager->get(CommonConstants::SETTING_API_KEY);

            // make sure api key is valid, then print the snippet
            if (trim($shopApiKey) !== '') {
                // Print shop snippet
                echo $this->getPageSnippet();
                include_once __DIR__ . '/../Views/sdk_snippet.php';
            }
        });
    }

    /**
     * Get page snippet
     *
     * @return string
     */
    private function getPageSnippet()
    {
        $loader = $this->loader;
        $data = array();

        if (Helper::isWooCommerceActive()) {
            global $woocommerce;
            // Wc, wp version
            $data['wc_version'] = $woocommerce->version;
            $data['wp_version'] = get_bloginfo('version');
            $data['plugin_version'] = $loader->dataManager->getPluginVersion();
            $data['php_version'] = phpversion();

            $data['cart'] = $loader->dataManager->resourceManager->cartManager->get(false);

            // Page url
            $data['page_url'] = array(
                'home' => get_permalink(Helper::isWc3() ? wc_get_page_id('shop') : woocommerce_get_page_id('shop')),
                'cart' => Helper::isWc3() ? wc_get_cart_url() : $woocommerce->cart->get_cart_url(),
                'checkout' => Helper::isWc3() ? wc_get_checkout_url() : $woocommerce->cart->get_checkout_url(),
            );

            // Customer
            $currentUserId = get_current_user_id();
            if ($currentUserId) {
                $data['customer'] = array(
                    'id' => $currentUserId,
                );
            }

            // Page
            $data['page'] = array();
            if (is_shop() || is_front_page()) {
                $data['page']['type'] = 'home';
            } elseif (is_product_category()) {
                $collection = get_queried_object();
                $collection_id = $collection ? $collection->term_id : null;
                $data['page']['type'] = 'collection';
                $data['page']['id'] = (int)$collection_id;
            } elseif (is_product()) {
                wp_reset_postdata();
                $product = WC()->product_factory->get_product();
                if ($product) {
                    $productId = Helper::isWc3() ? $product->get_id() : $product->id;
                } else {
                    $productId = get_the_ID();
                }
                $data['page']['type'] = 'product';
                $data['page']['id'] = (int)$productId;
            } elseif (is_cart()) {
                $data['page']['type'] = 'cart';
            } elseif (is_wc_endpoint_url('order-received')) {
                $data['page']['type'] = 'post_checkout';
            } elseif (is_checkout()) {
                $data['page']['type'] = 'checkout';
            }
        }

        // Convert to js snippet
        $data = addslashes(json_encode($data));
        $snippet = '<script>var _beeketing = JSON.parse(\'' . $data . '\');</script>';

        return $snippet;
    }

}
