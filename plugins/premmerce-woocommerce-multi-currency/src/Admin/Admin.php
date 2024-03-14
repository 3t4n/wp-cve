<?php namespace Premmerce\WoocommerceMulticurrency\Admin;

use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\SDK\V2\Notifications\AdminNotifier;
use Premmerce\WoocommerceMulticurrency\Admin\PluginPages\PluginPages;
use Premmerce\WoocommerceMulticurrency\Admin\RatesUpdate\RatesUpdateController;
use Premmerce\WoocommerceMulticurrency\Admin\Reports\ReportsDataQueryBuilder;
use Premmerce\WoocommerceMulticurrency\Admin\Reports\WoocommerceReportsAmountFixer;
use Premmerce\WoocommerceMulticurrency\Model\Model;

/**
 * Class Admin
 *
 *
 * @package Premmerce\WoocommerceMulticurrency\Admin
 */
class Admin
{
    const PAGE_SLUG = 'premmerce_multicurrency';

    const CHANGE_SHOP_CURRENCY_ACTION = 'premmerceChangeShopCurrency';

    const RECALCULATE_PRICES_ACTION = 'recalculateProductsPrices';

    const GET_UPDATERS_ACTION = 'premmerceGetUpdatersForCurrency';

    const GET_CURRENCY_RATE_ACTION = 'premmerceGetCurrencyRate';

    const CHECK_UPDATERS_STATUSES_ACTION = 'premmerceGetUpdatersStatuses';

    const CLEAN_PLUGIN_CACHE_ACTION = 'premmerceCleanMulticurrencyPluginCache';

    const DISMISS_UPDATER_MESSAGE_ACTION = 'premmerceDismissUpdaterMessage';

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var Model
     */
    private $model;

    /**
     * @var PluginPages
     */
    private $pluginPages;

    /**
     * @var AdminNotifier
     */
    private $notifier;
    
    /**
     * @var string
     */
    private $defaultRedirectPath = '';

    /**
     * @var RatesUpdateController
     */
    private $ratesUpdateController;

    /**
     * Admin constructor.
     *
     * @param FileManager $fileManager
     * @param Model $model
     * @param RatesUpdateController $ratesUpdateController
     */
    public function __construct(FileManager $fileManager, Model $model, RatesUpdateController $ratesUpdateController)
    {
        $this->fileManager = $fileManager;
        $this->model = $model;
        $this->ratesUpdateController = $ratesUpdateController;
        $this->defaultRedirectPath = admin_url('admin.php') . '?page=' . self::PAGE_SLUG;
        $this->pluginPages = new PluginPages($this->fileManager, $this->model, $this->ratesUpdateController);
        $this->notifier = new AdminNotifier();

        $this->requestedCurrencyExists() || wp_die(__('Sorry, you are not allowed to access this page'));


        if (!$this->model->pluginTableExists()) {
            return;
        }


        $mainCurrencyFromPluginOptions = $this->model->getMainCurrency();
        $woocommerceActivated = in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')));

        if (!$mainCurrencyFromPluginOptions['code']) {
            $this->model->fillMainCurrencyData();
        }

        if (!$woocommerceActivated) {
            return;
        }

        if (class_exists(InternalProductCurrencyHandler::class)) {
            new InternalProductCurrencyHandler($this->fileManager, $this->model);
        }

        $reportDataQueryBuilder = new ReportsDataQueryBuilder();
        new WoocommerceReportsAmountFixer($reportDataQueryBuilder);

        $this->subscribe();
    }

    /**
     * Add actions and filters
     */
    public function subscribe()
    {
        //Settings, admin assets, views etc.
        add_action('admin_init', array($this->pluginPages, 'registerSettings'));
        add_action('admin_menu', array($this->pluginPages, 'addMenuItem'));
        add_filter('woocommerce_get_settings_general', array($this->pluginPages, 'moveWoocommerceOptions'));
        add_action('admin_enqueue_scripts', array($this->pluginPages, 'enqueueAdminAssets'));

        //Show warning if main currency was changed without this plugin
        add_action('admin_init', array($this, 'checkCurrencySettings'));

        //AJAX
        add_action('wp_ajax_' . self::GET_UPDATERS_ACTION, function () {
            check_ajax_referer(self::GET_UPDATERS_ACTION, 'premmerceNonce');
            $currency = sanitize_text_field(wp_unslash($_GET['currencyCode']));
            $updaters = $this->ratesUpdateController->getUpdatersForCurrency($currency);
            wp_send_json($updaters);
        });
        add_action('wp_ajax_' . self::GET_CURRENCY_RATE_ACTION, function () {
            check_ajax_referer(self::GET_CURRENCY_RATE_ACTION, 'premmerceNonce');
            $currencyCode = sanitize_text_field(wp_unslash($_GET['currencyCode']));
            $updaterId = sanitize_text_field(wp_unslash($_GET['updaterId']));

            wp_send_json(
                array(
                    'rate' => $this->ratesUpdateController->getCurrencyRate($currencyCode, $updaterId),
                    'message' => '' //todo: put error messages here
                )
            );
        });

        add_action('wp_ajax_' . self::CHECK_UPDATERS_STATUSES_ACTION, array($this, 'checkUpdatersStatuses'));


        //Recalculate, update, change, delete
        add_action('admin_post_update-currencies', array($this, 'insertCurrencyData'));
        add_action('admin_post_add_currency', array($this, 'insertCurrencyData'));
        add_action('admin_post_delete-currency', array($this, 'deleteCurrency'));
        add_action('admin_post_premmerce_multicurrency_update_rates', array($this, 'updateRatesButtonHandler'));
        add_action('admin_post_' . self::CLEAN_PLUGIN_CACHE_ACTION, array($this, 'cleanPluginCache'));
        add_action('wp_ajax_' . self::DISMISS_UPDATER_MESSAGE_ACTION, array($this, 'dismissUpdaterNoApiKeyMessage'));
        add_action('wp_ajax_premmerceChangeShopCurrency', array($this, 'changeShopCurrency'));
        add_action('wp_ajax_premmerceRecalculatePrices', array($this, 'updateProductsDataAJAX'));


        //Save rates updaters settings
        add_action('admin_post_save_multicurrency_updater_settings', array($this, 'saveRatesUpdaterOptions'));
    }

    /**
     * Handle ajax request to check what update rates services are available
     */
    public function checkUpdatersStatuses()
    {
        check_ajax_referer(self::CHECK_UPDATERS_STATUSES_ACTION, 'premmerceNonce');
        wp_send_json($this->ratesUpdateController->checkUpdatersStatuses());
    }

    /**
     * Handle update rates from admin
     */
    public function updateRatesButtonHandler()
    {
        $message = $this->ratesUpdateController->updateRates();
        $this->redirectBack($message);
    }

    /**
     * Change main shop currency
     */
    public function changeShopCurrency()
    {
        check_ajax_referer(self::CHANGE_SHOP_CURRENCY_ACTION, 'premmerceNonce');
        $newMainCurrencyId = intval($_POST['currency_id']);
        $oldCurrencyId = get_option(Model::MAIN_CURRENCY_OPTION_NAME);

        do_action('premmerce_multicurrency_before_change_main_currency', $newMainCurrencyId, $oldCurrencyId);

        $this->model->recalculateRates($newMainCurrencyId);


        do_action('premmerce_multicurrency_after_change_main_currency', $newMainCurrencyId, $oldCurrencyId);


        wp_send_json(array(
            'status' => 'changedOk',
            'redirectPath' => $this->defaultRedirectPath,
            'nextChangeNonce' => wp_create_nonce(self::CHANGE_SHOP_CURRENCY_ACTION)
        ));
    }

    /**
     * Update product data using AJAX
     *
     * @param string $onlyForCurrency
     *
     */
    public function updateProductsDataAJAX($onlyForCurrency = '')
    {
        check_ajax_referer(self::RECALCULATE_PRICES_ACTION, 'premmerceNonce');

        $offset = 0;
        $productsLeft = -1;


        $productsToUpdateData = get_transient('premmerce_products_recalculate') ?: array();
        extract($productsToUpdateData, EXTR_OVERWRITE);

        //Number of products which will be recalculated on every AJAX request
        $productsNumber = apply_filters('premmerce_multicurrency_ajax_product_number', 10);
        $productsToUpdate = $this->model->getProductsIDs($onlyForCurrency, $productsNumber, $offset);


        foreach ($productsToUpdate as $productToUpdateID) {
            $this->model->updateProductData($productToUpdateID);
        }

        $nextOffset = $offset + count($productsToUpdate);

        if (-1 === $productsLeft) {
            $productsLeft = count($this->model->getProductsIDs($onlyForCurrency));
        }

        $productsLeft = $productsLeft - count($productsToUpdate);

        $data = array(
            'offset' => $nextOffset,
            'productsLeft' => $productsLeft
        );


        if ($productsLeft > 0) {
            set_transient('premmerce_products_recalculate', $data, 60);
        } else {
            $this->endCurrencyChanging();
        }

        wp_send_json(array(
            'productsLeft' => $productsLeft,
            'nextRecalculateNonce' => wp_create_nonce(self::RECALCULATE_PRICES_ACTION),
            'productsToUpdate' => $productsToUpdate
        ));
    }

    /**
     * Manage currency deletion from DB
     */
    public function deleteCurrency()
    {
        check_admin_referer('premmerce-currency-delete') || die;

        $currencyDeleteNotAllowedMessage = array(
            'message' => __(
                'Currency wasn\'t deleted. Looks like you are not permitted to do this.',
                'premmerce-woocommerce-multicurrency'
            ),
            'type' => 'error'
        );

        $currencyDeleteFailMessage = array(
            'message' => __('Something goes wrong. Currency wasn\'t deleted.'),
            'type' => 'error'
        );

        $currencyDeleteSuccessMessage = array(
            'message' => __('Currency was deleted.', 'premmerce-woocommerce-multicurrency'),
            'type' => 'success'
        );

        current_user_can('manage_woocommerce') || $this->redirectBack($currencyDeleteNotAllowedMessage);


        $currencyId = (int)$_GET['currency_id'];

        do_action('premmerce_multicurrency_before_currency_delete', $currencyId);

        $result = ($this->model->deleteCurrency($currencyId));


        do_action('premmerce_multicurrency_after_currency_delete', $currencyId, $result);

        $this->redirectBack($result ? $currencyDeleteSuccessMessage : $currencyDeleteFailMessage);
    }

    /**
     * Save user added currency or update if exists
     *
     */
    public function insertCurrencyData()
    {
        $redirectPath   = $this->defaultRedirectPath;
        $action         = '';
        $adminNotice    = array();

        $currencyData = $this->getPostedCurrencyData();


        if ('update-currencies' === $_POST['action']) {
            $action = 'update';

            $queryParams = array(
                'page' => self::PAGE_SLUG,
                'action' => 'edit-currency',
                'currency_id' => $currencyData['id']
            );
            $redirectPath = admin_url('admin.php') . '?' . build_query($queryParams);
            $adminNotice['message'] = __('Currency was updated.', 'premmerce-woocommerce-multicurrency');
            $adminNotice['message'] .= '<br><a href="' . $this->defaultRedirectPath . '">' . __(
                'Back to the currencies list',
                'premmerce-woocommerce-multicurrency'
            ) . '</a>';
        } elseif ('add_currency' === $_POST['action']) {
            $action = 'add';
            $adminNotice['message'] = __('Currency was added.', 'premmerce-woocommerce-multicurrency');
        }


        if (check_admin_referer('premmerce-currency-' . $action)) {
            $result = $this->model->insertCurrencyData($currencyData);


            if (! $result->getSuccess()) {//Show error messages if something goes wrong
                foreach ($result->getMessages() as $messageArray) {
                    $this->notifier->flash($messageArray['message'], $messageArray['type']);
                }
                $adminNotice = array();
            }
        }

        $this->redirectBack($adminNotice, $redirectPath);
    }

    /**
     * Handle rates updater options saving
     */
    public function saveRatesUpdaterOptions()
    {
        check_admin_referer('save_multicurrency_updater_settings', 'premmerceNonce') || die;

        $notPermittedMessage = array(
            'message' => __('You are not permitted to change this settings', 'premmerce-woocommerce-multicurrency'),
            'type' => 'error'
        );

        current_user_can('manage_woocommerce') || $this->redirectBack(array($notPermittedMessage));

        $result = $this->ratesUpdateController->saveUpdaterOptions();


        //Prepare result message and redirect back after settings saving
        $queryParams = array(
            'page' => self::PAGE_SLUG,
            'tab' => 'rates'
        );
        $url = esc_url(admin_url('admin.php') . '?' . build_query($queryParams));
        $linkText = __('Back to the rates updaters list', 'premmerce-woocommerce-multicurrency');
        $result['message'] .= '<br><a href="' . $url . '">' . esc_attr($linkText) . '</a>';

        $updaterId = $_POST['updater_id'];
        $queryArgs = array(
            'page' => self::PAGE_SLUG,
            'tab' => 'rates',
            'action' => 'edit-updater',
            'updater' => $this->ratesUpdateController->getUpdaterById($updaterId) ? $updaterId : ''
        );
        $path = admin_url('admin.php') . '?' . build_query($queryArgs);
        $this->redirectBack($result, $path);
    }

    /**
     * Show warning if Woocommerce currency not same as this plugin main currency
     */
    public function checkCurrencySettings()
    {
        $woocommerceMainCurrency = get_option('woocommerce_currency');

        //Check and display warning only once
        if (!get_transient('premmerce_multicurrency_check_main_currency')) {
            return;
        }

        $pluginMainCurrency = $this->model->getMainCurrency();

        if ($pluginMainCurrency['code'] && $woocommerceMainCurrency && $woocommerceMainCurrency !== $pluginMainCurrency['code']) {
            $this->notifier->push(__(
                'Looks like main store currency was changed since Premmerce Multi-currency was active last time. Please, check your prices and currencies rates and update it, if needed.',
                'premmerce-woocommerce-multicurrency'
            ), 'warning');
        }

        delete_transient('premmerce_multicurrency_check_main_currency');
    }

    /**
     * Clean all caches related to this plugin and products prices
     */
    public function cleanPluginCache()
    {
        if (check_admin_referer(self::CLEAN_PLUGIN_CACHE_ACTION, 'premmerceNonce')) {
            $this->model->invalidateWcProductPricesCache();
            $this->model->cleanCache();
            $this->ratesUpdateController->cleanAllCurrenciesCaches();

            $message = array(
                'message' => __('Cache was cleaned.', 'premmerce-woocommerce-multicurrency'),
                'type' => AdminNotifier::SUCCESS
            );

            $args = array(
                'page'   => self::PAGE_SLUG,
                'tab' => 'advanced_settings',
            );
            $path = admin_url('admin.php') . '?' . build_query($args);

            $this->redirectBack($message, $path);
        }
    }

    /**
     * Save option to not display updater message anymore.
     */
    public function dismissUpdaterNoApiKeyMessage()
    {
        check_ajax_referer(self::DISMISS_UPDATER_MESSAGE_ACTION, 'premmerceNonce');

        $updaterId = filter_input(INPUT_POST, 'updaterId', FILTER_SANITIZE_STRING);
        $updater = $this->ratesUpdateController->getUpdaterById($updaterId);


        if ($updater) {
            $updater->update_option('no_api_key_message_dismissed', true);
        }
    }

    /**
     * Delete temporary recalculation data and set success message
     */
    private function endCurrencyChanging()
    {
        delete_transient('premmerce_products_recalculate');
        $message = __('Data was successfully updated.', 'premmerce-woocommerce-multicurrency');
        $this->notifier->flash($message, 'success');
    }

    /**
     * Get currency data sent from add new currency or update currency form
     *
     * @return array $newCurrency
     */
    private function getPostedCurrencyData()
    {
        if (isset($_POST['currency-code'])) {
            $newCurrency['code'] = sanitize_text_field($_POST['currency-code']);
        }

        $newCurrency['currency_name'] = sanitize_text_field($_POST['currency-name']);
        $newCurrency['symbol'] = sanitize_text_field($_POST['currency-symbol']);
        $newCurrency['position'] = sanitize_text_field($_POST['currency-position']);
        $newCurrency['rate'] = (float)isset($_POST['currency-rate']) ? $_POST['currency-rate'] : 1;
        $newCurrency['decimal_separator'] = sanitize_text_field($_POST['currency-decimal-separator']);
        $newCurrency['thousand_separator'] = esc_sql(wp_unslash($_POST['currency-thousand-separator']));
        $newCurrency['decimals_num'] = intval($_POST['currency-decimals-num']);
        $newCurrency['display_on_front'] = isset($_POST['currency-display-on-front']);
        $newCurrency['updater'] = sanitize_text_field($_POST['currency-updater']);
        $newCurrency['id'] = isset($_POST['currency_id']) ? (int)$_POST['currency_id'] : '';

        return $newCurrency;
    }

    /**
     * Return user to admin page
     *
     * @param string $path
     * @param array $notice - Notice to show after redirect
     *
     */
    private function redirectBack($notice = array(), $path = '')
    {
        if ($notice) {
            $type = isset($notice['type']) ? $notice['type'] : 'success';

            $this->notifier->flash($notice['message'], $type);
        }

        wp_safe_redirect($path ?: $this->defaultRedirectPath);

        exit;
    }

    /**
     * Check if requested currency exists.
     *
     * @return bool
     *
     */
    private function requestedCurrencyExists()
    {
        if (isset($_GET['currency_id'])) {
            $id = (int)$_GET['currency_id'];
            return $this->model->currencyExists($id);
        }
        return true;
    }
}
