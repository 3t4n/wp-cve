<?php namespace Premmerce\WoocommerceMulticurrency;

use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\SDK\V2\Plugin\PluginInterface;
use Premmerce\WoocommerceMulticurrency\API\PremmerceMulticurrencyAPI;
use Premmerce\WoocommerceMulticurrency\Compatibility\WPMLCompatibility;
use Premmerce\WoocommerceMulticurrency\Frontend\UserCurrencyHandler;
use Premmerce\WoocommerceMulticurrency\Legacy\Legacy;
use Premmerce\WoocommerceMulticurrency\Model\Model;
use Premmerce\WoocommerceMulticurrency\Shipping\FreeShippingMinAmount;
use Premmerce\WoocommerceMulticurrency\Users\UserTotalSpentAmount;
use Premmerce\WoocommerceMulticurrency\Admin\Admin;
use Premmerce\WoocommerceMulticurrency\Admin\RatesUpdate\RatesUpdateController;
use Premmerce\WoocommerceMulticurrency\Frontend\Frontend;
use Premmerce\WoocommerceMulticurrency\Frontend\UserPricesHandler;
use Premmerce\WoocommerceMulticurrency\Orders\OrderPrices;
use Premmerce\SDK\V2\Notifications\AdminNotifier;

/**
 * Class WoocommerceMulticurrencyPlugin
 *
 *
 * @package Premmerce\WoocommerceMulticurrency
 */
class WoocommerceMulticurrencyPlugin implements PluginInterface
{
    const VERSION = '1.2';

    const VERSION_OPTION_NAME = 'premmerce_multicurrency_db_version';

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var Model
     */
    private $model;

    /**
     * @var UserPricesHandler
     */
    private $userPricesHandler;

    /**
     * @var RatesUpdateController
     */
    private $ratesUpdateController;

    /**
     * @var PremmerceMulticurrencyAPI
     */
    private $api;

    /**
     * @var bool
     */
    private $woocommerceActive;

    /**
     * PluginManager constructor.
     *
     * @param $mainFile
     */
    public function __construct($mainFile)
    {
        $this->woocommerceActive = in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')));

        $this->fileManager = new FileManager($mainFile, 'premmerce-woocommerce-multicurrency');
        $this->model = new Model();
    }

    /**
     * Run plugin part
     */
    public function run()
    {
        /**
         * Check if WooCommerce is active.
         *
         **/
        if ($this->woocommerceActive) {
            add_action('plugins_loaded', array($this, 'updateDB'));

            add_action('plugins_loaded', array($this, 'loadTranslations'));


            add_action('widgets_init', function () {
                register_widget(new Widget\CurrenciesWidget($this->fileManager, $this->model));
            });

            add_action('wc_ajax_premmerce_get_prices', array($this, 'handleAJAXrequest'));
            add_action('wc_ajax_nopriv_premmerce_get_prices', array($this, 'handleAJAXrequest'));
            $this->model->addCronSchedule();

            add_action('woocommerce_loaded', array($this, 'load'));//to be sure woocommerce is already loaded and we can use it's classes, functions etc.

            add_action('wpml_loaded', function () {
                new WPMLCompatibility();
            });
        } else {//Show warning if WooCommerce is disabled.
            if (is_admin()) {
                add_action('admin_init', function () {
                    if (current_user_can('activate_plugins')) {
                        $notifier = new AdminNotifier();
                        $notifier->push(__(
                            'Premmerce Woocommerce Multicurrency requires <a href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a> to be activated to work.',
                            'premmerce-woocommerce-multicurrency'
                        ), 'error', true);
                    }
                });
            }
        }
    }

    /**
     * Load plugin core classes
     */
    public function load()
    {
        $userCurrencyHandler        = new UserCurrencyHandler($this->model, $this->fileManager);
        $this->userPricesHandler    = new UserPricesHandler($this->fileManager, $this->model, $userCurrencyHandler);
        $this->api = new PremmerceMulticurrencyAPI($this->model, $this->userPricesHandler, $userCurrencyHandler);

        $this->ratesUpdateController = new RatesUpdateController($this->model);
        //auto update rates by cron hook
        add_action(Model::RATES_UPDATER_CRON_HOOK, array($this->ratesUpdateController, 'updateRates'));


        if (!is_admin() || (wp_doing_ajax() && isset($_GET['action']) && 'premmerce_get_prices' === $_GET['action'])) {
            $this->userPricesHandler->setFilters();
        }

        if (is_admin()) {
            new Admin($this->fileManager, $this->model, $this->ratesUpdateController);
            new Legacy();
        } else {
            new Frontend($this->fileManager, $this->model, $userCurrencyHandler);
        }

        new OrderPrices($this->model, $userCurrencyHandler, $this->userPricesHandler);
        new UserTotalSpentAmount();

        new FreeShippingMinAmount($this->userPricesHandler);
    }

    /**
     *
     */
    public function loadTranslations()
    {
        $name = $this->fileManager->getPluginName();
        load_plugin_textdomain('premmerce-woocommerce-multicurrency', false, $name . '/languages/');
    }

    /**
     * @return PremmerceMulticurrencyAPI
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * Create or update currencies table if plugin was updated
     */
    public function updateDB()
    {
        $oldVersion = get_option(self::VERSION_OPTION_NAME);
        if (version_compare($oldVersion, self::VERSION) === -1) {
            $this->model->updateDB();
            update_option(self::VERSION_OPTION_NAME, self::VERSION);
        }
    }

    /**
     * Redraw prices by AJAX if "I'm using cache plugin" option enabled
     */
    public function handleAJAXrequest()
    {
        $this->userPricesHandler->sendPricesForAjax();
    }

    /**
     * Fired when the plugin is activated
     */
    public function activate()
    {
        update_option(self::VERSION_OPTION_NAME, self::VERSION);
        $this->model->updateDB();

        //Flag to check if main Woocommerce currency code doesn't match to this plugins main currency.
        set_transient('premmerce_multicurrency_check_main_currency', true, DAY_IN_SECONDS);
    }

    public function deactivate()
    {
    }

    /**
     * Fired during plugin uninstall
     */
    public static function uninstall()
    {
        $model = new Model();
        $model->deleteProductsCurrenciesMetaFields();
        $model->dropCurrenciesTable();
        delete_option(self::VERSION_OPTION_NAME);
        delete_option(Model::MAIN_CURRENCY_OPTION_NAME);
        delete_option(Model::DEFAULT_COUNTRY_CURRENCY_OPTION_NAME);

        if (class_exists(Legacy::class)) {
            delete_option(Legacy::META_BOXES_FIXED_OPTION_NAME);
        }
    }
}
