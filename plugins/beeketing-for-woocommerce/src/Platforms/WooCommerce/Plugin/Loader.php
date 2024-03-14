<?php
/**
 * User: Quan Truong
 * Date: 8/10/18
 * Time: 12:29 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Plugin;

use BeeketingConnect_beeketing_woocommerce\Common\Constants as CommonConstants;
use BeeketingConnect_beeketing_woocommerce\Common\BeeketingAPI;
use BeeketingConnect_beeketing_woocommerce\Common\Webhook;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\PluginConfig;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\DataManager\DataManager;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Helper;
use Raven_Client;

/**
 * Class Loader
 * @property PluginConfig config
 * @package BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Plugin
 *
 * Load main plugin
 */
class Loader
{

    /**
     * The single instance of the class
     */
    private static $_instance = null;


    /**
     * Check app beeketing number installed
     * @var int
     */
    private static $appsInstalledCount = 0;

    /**
     * Get instance
     *
     * @return static
     */
    public static function instance()
    {
        return static::$_instance;
    }

    /**
     * Make instance
     * @param PluginConfig $config
     */
    public static function makeInstance($config)
    {
        if (!is_null(static::$_instance)) {
            return;
        }

        static::$_instance = new static($config);
    }

    /**
     * @var Hooks
     */
    public $hooks;

    /**
     * @var Helper
     */
    public $helper;

    /**
     * Store Beeketing API Key
     * @var string
     */
    private $apiKey;

    /**
     * @var DataManager
     */
    public $dataManager;

    /**
     * @var Handler
     */
    public $handler;

    /**
     * @var BeeketingAPI
     */
    public $beeketingAPI;

    /**
     * @var Webhook
     */
    public $webhook;

    /**
     * @var WebhookHandler
     */
    public $webhookHandler;

    /**
     * @var Raven_Client
     */
    public $ravenClient;

    /**
     * Loader constructor.
     * @param PluginConfig $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->hooks = $this->createHooks($config);
        $this->helper = new Helper();
        $this->dataManager = new DataManager();
        $this->handler = new Handler($this);
        $this->beeketingAPI = new BeeketingAPI(BEEKETINGWOOCOMMERCE_GO_API, BEEKETINGWOOCOMMERCE_API);
        $this->webhook = new Webhook(BEEKETINGWOOCOMMERCE_API, BEEKETINGWOOCOMMERCE_GO_API);

        // Load variables
        if ($apiKey = $this->dataManager->settingManager->get(CommonConstants::SETTING_API_KEY)) {
            $this->setApiKey($apiKey);
        }
        // Init webhook handler
        $this->webhookHandler = new WebhookHandler($this->dataManager, $this->webhook,
            $this->dataManager->settingManager->get(CommonConstants::SETTING_SHOP_ID), $config);
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->beeketingAPI->setApiKey($apiKey);
        $this->webhook->setApiKey($apiKey);
        $this->apiKey = $apiKey;
    }

    /**
     * create Hooks
     * @param $config
     * @return Hooks
     */
    protected function createHooks($config)
    {
        return new Hooks($config, $this);
    }

    /**
     * Get apps installed count
     * @return int
     */
    public static function getAppsInstalledCount()
    {
        return self::$appsInstalledCount;
    }

    /**
     * Increase apps installed count
     * @return void
     */
    public static function increaseAppsInstalledCount()
    {
        self::$appsInstalledCount++;
    }

    public static function activateHook()
    {
        static::instance()
            ->handler
            ->activatePluginHook('Connect - Activate plugin', 'wordpress');
    }

    public static function deactivateHook()
    {
        static::instance()
            ->handler
            ->deactivatePluginHook('Connect - Deactivate plugin', 'wordpress');
    }

    /**
     * Init the plugin
     *
     */
    public function init()
    {
        static::increaseAppsInstalledCount();
        // Load for admin
        if (is_admin()) {
            // Redirect to connect Beeketing after activated
            add_action('activated_plugin', function ($plugin) {

                // exit if event not trigger by current plugin
                if ($plugin !== $this->config->getPluginBaseName()) {
                    return;
                }

                wp_redirect(admin_url('admin.php?page=' . $this->config->getPluginAdminUrl()));
                exit();
            });

            add_action('init', function () {
                static::instance()->hooks->registerAdminHooks();
            });
        }
        // exit if beeketing webhook registered
        if (static::getAppsInstalledCount() > 1) {
            return;
        }

        add_action('plugins_loaded', function () {
            add_action('admin_init', function () {
                // Setup to handle admin ajax requests
                static::instance()->handler->setupAjax();
            });

            // Handle all request
            static::instance()->handler->setupAllRequests();

            // Handle all request
            static::instance()->hooks->registerResourceWebhooks();

            // Script tag to site front
            static::instance()->hooks->addScriptTag();
        });
    }

}
