<?php

namespace Avecdo\Woocommerce\Classes;

use Avecdo\SDK\Classes\Helpers;
use Avecdo\SDK\Exceptions\AuthException;
use Avecdo\SDK\POPO\KeySet;
use Avecdo\SDK\POPO\Shop;
use Avecdo\SDK\POPO\Shop\ShopSystem;
use Avecdo\SDK\POPO\Shop\ShopExtras;
use Avecdo\Woocommerce\Models\Model;
use Avecdo\SDK\Classes\WebService;
use Exception;
use WP;

if (!defined('ABSPATH')) {
    exit;
}

class Plugin
{
    const WP_NONCE_NOT_SET           = 'WP nonce not set.';
    const INVALID_WP_NONCE           = 'WP nonce not valid! Please go back and refresh the page to try again.';
    const WOOCOMMERCE_NOT_ACTIVE     = 'WooCommerce is not activated.';
    const NOT_SUFFICIENT_PERMISSIONS = 'You do not have sufficient permissions to access this page.';
    const ERROR_CODE_INTERFACE       = 6872;
    const WPML_ACTIVE_BUT_NOT_WCML   = 'Please install "WooCommerce Multilingual" and go to WooCommerce->WooCommerce Multilingual->Multi-currency and "enable multi currency mode" for multi language/multi currency support.';

    /** @var Model */
    public $model;
    public $apiPath;

    /**
     * holds the currently loaded keyset.
     * @var KeySet
     */
    private $keySet;

    /**
     * Holds a reference to the current Plugin instance
     * @var Plugin
     */
    private static $instance;
    private $messages = array();

    /**
     * @return Plugin
     */
    public static function make()
    {
        if (is_null(static::$instance)) {
            new static();
        }

        return static::$instance;
    }

    public function __construct()
    {
        $this->model   = new Model();
        $this->apiPath = rtrim(site_url(), '/').'/?avecdo-api';
        $this->updateKeySet();


        // do update check..
        $updateSatatus = get_transient('__avecdo_update_check');
        if (!$updateSatatus) {
            if (!function_exists('plugins_api')) {
                require_once( ABSPATH.'wp-admin/includes/plugin-install.php' );
            }
            $args          = array(
                'slug'   => 'avecdo-for-woocommerce',
                'fields' => array('version' => true)
            );
            $data          = plugins_api('plugin_information', $args);
            $newVersion    = version_compare(AVECDO_WOOCOMMERCE_PLUGIN_VERSION, $data->version, '<');
            $updateSatatus = array(
                'update_available' => $newVersion,
                'latest'           => $data->version
            );
            // store update status for 12 hours
            set_transient('__avecdo_update_check', $updateSatatus, ((60 * 60) * 12));
        }


        static::$instance = $this;
    }

    /**
     * Returns whether WPML is available.
     */
    private function isWPMLAvailable()
    {
        global $sitepress;
        return isset($sitepress);
    }

    /**
     * Returns true when Woocommerce WPML is installed and multi currency
     * function is enabled.
     */
    public function isMultiCurrencyEnabled()
    {
        global $woocommerce_wpml;
        return isset($woocommerce_wpml) && wcml_is_multi_currency_on();
    }

    /**
     * Register an admin menu item to access the plugin page.
     */
    public function registerAdminMenuItem()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        add_menu_page('Avecdo Connect', 'Avecdo Connect', 'manage_options', 'avecdo', 'avecdo_connect', 'dashicons-rss');
    }

    /**
     * Register a custom CSS file inside the administration on
     * the Avecdo plugin page.
     */
    public function registerAdminCss()
    {
        wp_register_style('avecdo_admin_style', plugins_url('../../assets/css/styles.css', __FILE__), array(), AVECDO_WOOCOMMERCE_PLUGIN_VERSION, 'all');
        wp_enqueue_style('avecdo_admin_style');
    }

    /**
     * Runs a security check when form data is being posted.
     * The request verification is WP's own system called "nonce".
     */
    public function checkNonce()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_REQUEST['_wpnonce'])) {
                $this->messages['warning'][] = __(self::WP_NONCE_NOT_SET, 'avecdo-for-woocommerce');
            }
            if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'avecdo_activation_form')) {
                $this->messages['warning'][] = __(self::INVALID_WP_NONCE, 'avecdo-for-woocommerce');
            }
        }
        return true;
    }

    /**
     * Calls wp_die() with an error message.
     */
    public function error($message)
    {
        wp_die('<div class="wrap"><div class="error settings-error notice"><p>'.$message.'</p></div></div>');
    }

    public function prepareMultiCurrency($multiCurrencyEnabled, &$languages, &$currencies, &$avecdo_language, $multiLangShopData = null, &$usedLanguages = array())
    {
        // WooCommerce Multilingual with Multi-currency.
        if ($multiCurrencyEnabled) {
            global $woocommerce_wpml;
            $languages = apply_filters( 'wpml_active_languages', array(), array( 'skip_missing' => 0, 'orderby' => 'code' ));

            //NARROWS SELECT OPTIONS
            if($multiLangShopData !== null){
                foreach ($multiLangShopData as $code => $shop){
                    if(isset($languages[$code])){
                        $usedLanguages[] = $code;
                    }
                }
            }


            if (empty($avecdo_language)) {
                // If language is not set, set language to first key in associative array.
                reset($languages);
                $usedLanguages[] = key($languages);
                $avecdo_language = key($languages);
            } else {
                $usedLanguages[] = $avecdo_language;
            }

            // Invert the currency list, so that we go from language to available currencies,
            // instead of available currencies to language.
            foreach ($woocommerce_wpml->multi_currency->currencies as $currency_id => $currency_langs) {
                foreach ($currency_langs['languages'] as $lang_code => $lang_enabled) {
                    if ($lang_enabled) {
                        $currencies[$lang_code][] = $currency_id;
                    }
                }
            }
        }
    }

    public function render()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->catchFormRequest();
        }

        if ($this->isWPMLAvailable() && !$this->isMultiCurrencyEnabled()) {
            $this->error(__(Plugin::WPML_ACTIVE_BUT_NOT_WCML, 'avecdo-for-woocommerce'));
        }

        ob_start();
        $currencies = array();
        $languages = array();

        $multiCurrencyEnabled = $this->isMultiCurrencyEnabled();
        $multiLangShopData = $this->getWPMLShopOptions();
        $usedLanguages = array();

        $useDescription = Option::get('use_description');
        $avecdo_language = Option::get('language');
        $avecdo_currency = Option::get('currency');

        $this->prepareMultiCurrency($multiCurrencyEnabled, $languages, $currencies, $avecdo_language, $multiLangShopData, $usedLanguages);

        $activationKey = $this->keySet->asString();
        $mesages       = $this->messages;
        $activation    = false;

        if ((isset($_GET['activation']) && !isset($_POST['avecdo_submit_reset'])) || isset($_POST['version'])) {
            $activation = true;
        }

        if (!$this->isActivated()) {
            include(dirname(__FILE__) . '/../../views/index.php');
        } else {
            include(dirname(__FILE__) . '/../../views/activated.php');
        }

        $content = ob_get_clean();
        echo $content;
    }

    public function getWPMLShopOptions() {
        if(Option::get('multi_lang_props')){
            return json_decode(Option::get('multi_lang_props'), true);
        }

        return null;
    }

    public function setWPMLShopOptions($options) {
        Option::update('multi_lang_props', json_encode($options));
    }

    public function catchFormRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isMultiLang = false;
            if(isset($_POST['avecdo_multi_lang']) && $_POST['avecdo_multi_lang'] === '1'){ $isMultiLang = true; }

            if (isset($_POST['avecdo_submit_activation'])) {

                $activated = $this->activationSubmitted($isMultiLang, $_POST);
                if(!$activated){ return; }

            } else if (isset($_POST['avecdo_submit_reset']) && $_POST['avecdo_submit_reset'] == '1') {
                $this->resetSubmitted($isMultiLang, $_POST);
            } else if (isset($_POST['version']) && in_array($_POST['version'], ['1', '2'])) {
                update_option('avecdo_version', intval($_POST['version']));
                $this->updateKeySet();
            }

            if (!isset($_POST['use_description'])) {
                return;
            }

            // Update configuration options
            Option::update('use_description', $_POST['use_description']);

            if(!$isMultiLang){
                if (isset($_POST['AVECDO_CURRENCY_ID'])) {
                    // If the shop has multi-currency plugin installed:
                    Option::update('currency', $_POST['AVECDO_CURRENCY_ID']);
                    Option::update('language', $_POST['AVECDO_LANGUAGE_ID']);
                } else {
                    // If there is no multi-currency enabled (or it has been disabled),
                    // use the default woocommerce currency.
                    Option::update('currency', get_woocommerce_currency());
                }
            } else {

                $multiLangShopData = $this->getWPMLShopOptions();
                $langCode = isset($_POST['AVECDO_PREVIOUS_LANG_ID']) ? $_POST['AVECDO_PREVIOUS_LANG_ID'] : $_POST['AVECDO_LANGUAGE_ID'];

                $data = isset($multiLangShopData[$langCode]) ? $multiLangShopData[$langCode] : [];
                $newData = [
                    'lang_code' => $_POST['AVECDO_LANGUAGE_ID'],
                    'currency_id' => $_POST['AVECDO_CURRENCY_ID'],
                    'description' => $_POST['use_description']
                ];

                $multiLangShopData[$_POST['AVECDO_LANGUAGE_ID']] = array_merge($data,$newData);
                if(isset($_POST['AVECDO_PREVIOUS_LANG_ID'])){
                    unset($multiLangShopData[$_POST['AVECDO_PREVIOUS_LANG_ID']]);
                }

                $this->setWPMLShopOptions($multiLangShopData);
            }

        }
    }

    public function getProducts($page, $limit, $lastRun)
    {
        return $this->model->getProducts($page, $limit, $lastRun, $this->getWPMLShopOptions(), $this->getKeySet());
    }

    public function getCategories()
    {
        return $this->model->getCategories($this->getWPMLShopOptions(), $this->getKeySet());
    }

    public function catchApiRequest()
    {
        WooAPI::make()->bindContext($this)->routeRequest($this->getKeySet());
    }

    private function activationSubmitted($isMultiLang = false, $formData = array())
    {
        $_aak          = @$_POST['avecdo_activation_key'];
        $activationKey = isset($_aak) ? sanitize_text_field($_aak) : '';
        $keySet        = KeySet::fromActivationKey($activationKey);

        if ($keySet === null) {
            $this->messages['error'][] = __('Invalid activation key', 'avecdo-for-woocommerce');
            return false;
        }

        if($isMultiLang && $this->checkIfKeyIsUsed($keySet,$isMultiLang,$formData['AVECDO_LANGUAGE_ID']) === true){
            $this->messages['error'][] = __('Activation key already used!', 'avecdo-for-woocommerce');
            return false;
        }

        $this->storeKeys($keySet, $isMultiLang);
        $webService = new WebService();

        try {
            $webService->authenticate($keySet, $this->apiPath);
        } catch (AuthException $e) {

            $this->resetSubmitted($isMultiLang ,$_POST);

            $errorMessage = $e->getMessage();
            $payload      = $e->getPayload();

            if (isset($payload->message)) {
                $errorMessage .= ': '.$payload->message;
            }
            $this->messages['error'][] = $errorMessage;
            return false;
        } catch (Exception $e) {
            $this->messages['error'][] = $e->getMessage();
            return false;
        }

        return $this->activateAvecdo($isMultiLang, $formData);
    }

    /**
     * Set avecdo feed as active
     */
    public function activateAvecdo($isMultiLang = false, $formData = array())
    {
        if ($isMultiLang === false) // Set default options:
        {
            // Set default options:
            Option::update('plugin_activated', 1);
            Option::update('use_description', 'any');
            Option::update('currency', get_woocommerce_currency());

            global $sitepress;
            if ($sitepress) {
                Option::update('language', $sitepress->get_current_language());
            }

        } else {
            $multiLangShopData = $this->getWPMLShopOptions();
            $multiLangShopData[$_POST['AVECDO_LANGUAGE_ID']]['shop_activated'] = 1;
            $this->setWPMLShopOptions($multiLangShopData);
        }

        $this->messages['success'][] = __('Your avecdo connection is now active.', 'avecdo-for-woocommerce');
        return true;
    }

    /**
     * Runs the activation process when the plugin gets activated inside WP.
     *
     * CHANGED BY: Christian M. Jensen <christian@modified.dk>
     * @return void
     * @version 1.1.2
     *  - added wp_rewrite->flush_rules() to support clean avecdo api uri. (load new url rewrites)
     */
    public function activate()
    {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    /**
     * Runs the deactivation process when the plugin gets deactivated inside WP.
     *
     * CHANGED BY: Christian M. Jensen <christian@modified.dk>
     * @return void
     * @version 1.1.2
     *  - added wp_rewrite->flush_rules() to support clean avecdo api uri. (remove old url rewrites)
     */
    public function deactivate()
    {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    /**
     * Get shop data
     * @return array
     */
    public function getShop()
    {
        $shop = new Shop();
        $shop
            ->setUrl(get_site_url())
            ->setPrimaryCurrency(get_woocommerce_currency())
            ->setName(get_bloginfo('name'))
            ->setImage(get_header_image())
            ->setEmail(get_option('admin_email'))
            ->setCountry(get_option('woocommerce_default_country'))
            ->setShopSystem(ShopSystem::WOOCOMMERCE)
            ->setPluginVersion(AVECDO_WOOCOMMERCE_PLUGIN_VERSION)
            ->setAddress(/* address */null)
            ->setOwner(/* owner */null)
            ->setPhone(/* phone */null)
            /* ->setPrimaryLanguage(get_bloginfo('language')) */
            ->setPrimaryLanguage(get_locale())
            ->addToExtras(ShopExtras::WORDPRESS_VERSION, get_bloginfo('version'));

        if (class_exists('WooCommerce')) {
            $wooCommerce = $this->model->getWooCommerceInstance();
            $shop->setShopSystemVersion($wooCommerce->version);
        }
        $shop->addToExtras('description', get_bloginfo('description'));

        /* just because */
        $shop->addToExtras('MAX_EXECUTION_TIME', @ini_get('max_execution_time'));
        $shop->addToExtras('MEMORY_LIMIT', @ini_get('memory_limit'));
        $shop->addToExtras('IS_MULTISITE', is_multisite() ? 1 : 0);
        $shop->addToExtras('ACTIVE_PLUGINS', json_encode($this->getActivePlugins()));

        $data = $shop->getAll();
        return $data;
    }

    /**
     * Get the currently loaded keyset, if not loaded
     * loaded the current one from database
     * @return KeySet
     */
    protected function getKeySet()
    {
        if (is_null($this->keySet)) {
            $this->updateKeySet();
        }

        return $this->keySet;
    }

    /**
     * Reset the plugin and delete private/public keys from the database.
     */
    private function resetSubmitted($isMultiLang = false, $formData = array())
    {
        $skipRest = false;
        if($isMultiLang === false){
            Option::update('plugin_activated', 0);
            Option::update('public_key', '');
            Option::update('private_key', '');
        } else {
            $multiLangShopData = $this->getWPMLShopOptions();
            //IF WE'RE RESETTING THE MAIN SHOP, MOVE FIRST MULTI SHOP INTO MAIN SHOP IF WE HAVE A MULTISHOP ELSE RESET COMPLETELY
            $mainShopKey = Option::get('public_key') . ';' . Option::get('private_key');

            if(
                $mainShopKey === $formData['avecdo_activation_key'] &&
                $multiLangShopData !== null &&
                count($multiLangShopData) > 0)
            {
                $shop = reset($multiLangShopData);
                $shop_code = key($multiLangShopData);
                Option::update('public_key', $shop['public_key']);
                Option::update('private_key', $shop['private_key']);
                Option::update('language', $shop['lang_code']);
                Option::update('currency', $shop['currency_id']);
                Option::update('use_description', $shop['description']);


                unset($multiLangShopData[$shop_code]);
                $this->setWPMLShopOptions($multiLangShopData);
                $skipRest = true;
            }

            if($multiLangShopData !== null && $skipRest === false) {
                foreach ($multiLangShopData as $code => $shop){
                    $key = $shop['public_key'] . ';' . $shop['private_key'];
                    if($key === $formData['avecdo_activation_key']){
                        unset($multiLangShopData[$code]);
                        break;
                    }
                }
                $this->setWPMLShopOptions($multiLangShopData);
            }

            //NO MORE KEYS!
            if($mainShopKey === $formData['avecdo_activation_key'] && count($multiLangShopData) <= 0){
                Option::update('plugin_activated', 0);
                Option::update('public_key', '');
                Option::update('private_key', '');
                Option::update('language', '');
                Option::update('currency', '');
                Option::update('use_description', '');
                Option::update('multi_lang_props', '');
            }
        }

        $this->messages['success'][] = __('Your avecdo keys have been removed from your shop.', 'avecdo-for-woocommerce');
    }

    /**
     * Save the keyset for this shop in database.
     * @param KeySet $keySet
     */
    private function storeKeys(KeySet $keySet, $isMultiLang)
    {
        if (!$isMultiLang) {
            Option::update('public_key', $keySet->getPublicKey());
            Option::update('private_key', $keySet->getPrivateKey());
        } else {
            $multiLangShopData = $this->getWPMLShopOptions();

            // Check if the option ket exists and act according to that
            if($multiLangShopData) {
                $multiLangShopData[$_POST['AVECDO_LANGUAGE_ID']] = [
                    'public_key' => $keySet->getPublicKey(),
                    'private_key' => $keySet->getPrivateKey(),
                ];

                $this->setWPMLShopOptions($multiLangShopData);
            } else {
                $multiLangShopData = [
                    $_POST['AVECDO_LANGUAGE_ID'] => [
                        'public_key' => $keySet->getPublicKey(),
                        'private_key' => $keySet->getPrivateKey(),
                    ]
                ];
                $this->setWPMLShopOptions($multiLangShopData);
            }
        }
        // load keyset.
        $this->keySet = null;
        $this->getKeySet();
    }

    /**
     * Update the loaded provate and public key keys
     * @return void
     */
    public function updateKeySet()
    {
        $headers = Helpers::getAllHeaders();

        if(isset($headers['x-apikey'])){
            if(Option::get('public_key') === $headers['x-apikey']){
                $this->keySet = new KeySet(Option::get('public_key'), Option::get('private_key'));
            } else {
                $multiLangShopData = $this->getWPMLShopOptions();
                if(!empty($multiLangShopData)) {
                    foreach ($multiLangShopData as $code => $shop) {
                        if ($shop['public_key'] === $headers['x-apikey']) {
                            $this->keySet = new KeySet($shop['public_key'], $shop['private_key']);
                            break;
                        }
                    }
                }

                if($this->keySet === null){
                    $this->keySet = new KeySet(Option::get('public_key'), Option::get('private_key'));
                }
            }
        } else {
            $this->keySet = new KeySet(Option::get('public_key'), Option::get('private_key'));
        }
    }

    public function checkIfKeyIsUsed(KeySet $keySet, $isMultiLang = false, $language_code = null){
        if($isMultiLang === true){
            $used = false;
            $mainShopKey = Option::get('public_key') . ';' . Option::get('private_key');
            $formKey = $keySet->getPublicKey().';'.$keySet->getPrivateKey();
            if($mainShopKey === $formKey){
                $used = true;
            }

            $multiLangShopData = $this->getWPMLShopOptions();
            if ($multiLangShopData !== null && $used === false) {
                foreach ($multiLangShopData as $code => $shop){
                    if(
                        $code !== $language_code &&
                        $shop['public_key'] === $keySet->getPublicKey() &&
                        $shop['private_key'] === $keySet->getPrivateKey()
                    ){
                        $used = true;
                    }
                }
            }
        } else {
            $used = false;
        }

        return $used;
    }


    /**
     * gets a boolean value indicating if the plug in is activated.
     * @return boolean
     */
    private function isActivated()
    {
        return (bool) Option::get('plugin_activated', false);
    }

    /**
     * Load localization files.
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    public function loadTextdomain()
    {
        load_plugin_textdomain('avecdo-for-woocommerce', false, dirname(plugin_basename(__FILE__)).'/languages/');
    }

    /**
     * Gets a boolean value indicating if WooCommerce is active
     * @return boolean
     */
    public function isWoocommerceActive()
    {
        if( is_plugin_active_for_network( 'woocommerce/woocommerce.php') || is_plugin_active( 'woocommerce/woocommerce.php')){
            return true;
        }

        $active_plugins = ( is_multisite() ) ?
            array_keys(get_network_option(null, 'active_sitewide_plugins', array())) : apply_filters('active_plugins', get_option('active_plugins', array()));

        foreach ($active_plugins as $active_plugin) {
            $active_plugin = explode('/', $active_plugin);
            if (isset($active_plugin[1]) && 'woocommerce.php' === $active_plugin[1]) {
                return true;
            }
        }

        return false;
    }

    /**
     * Show row meta on the plugin screen.
     *
     * @param	mixed $links Plugin Row Meta
     * @param	mixed $file  Plugin Base file
     * @return	array
     */
    public function pluginRowMeta($links, $file)
    {
        if (AVECDO_WOOCOMMERCE_PLUGIN_BASENAME == $file) {
            $row_meta = array(
                'channels' => '<a href="http://avecdo.com/channels/" title="'.__('View supported channels', 'avecdo-for-woocommerce').'">'.__('Channels', 'avecdo-for-woocommerce').'</a>',
                'features' => '<a href="http://avecdo.com/features/" title="'.__('View supported features', 'avecdo-for-woocommerce').'">'.__('Features', 'avecdo-for-woocommerce').'</a>',
                'support'  => '<a href="mailto:support@avecdo.com?subject=Help with WooCommerce" title="'.__('Send a mail to customer support', 'avecdo-for-woocommerce').'">'.__('Mail support', 'avecdo-for-woocommerce').'</a>',
            );
            if ($this->isActivated()) {
                $row_meta['login'] = '<a href="https://v2.avecdo.com/login" target="_blank">'.__('Go to avecdo', 'avecdo-for-woocommerce').'</a>';
            } else {
                $row_meta['register'] = '<a href="https://v2.avecdo.com/register" target="_blank">'.__('Register at avecdo', 'avecdo-for-woocommerce').'</a>';
            }
            return array_merge($links, $row_meta);
        }
        return (array) $links;
    }

    /**
     * Register plugin action links
     * @param array $links
     * @return array
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    public function registerActionLinks($links)
    {
        $links[] = '<a href="'.admin_url('admin.php?page=avecdo').'">'.__('Settings', 'avecdo-for-woocommerce').'</a>';
        return $links;
    }

    /**
     * Get all active plugins.
     * @return array
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    private function getActivePlugins()
    {
        require_once ABSPATH.'wp-admin/includes/plugin.php';
        // Get both site plugins and network plugins
        $active_plugins = (array) get_option('active_plugins', array());
        if (is_multisite()) {
            $network_activated_plugins = array_keys(get_site_option('active_sitewide_plugins', array()));
            $active_plugins            = array_merge($active_plugins, $network_activated_plugins);
        }
        $active_plugins_data = array();
        foreach ($active_plugins as $plugin) {
            $data                  = get_plugin_data(WP_PLUGIN_DIR.'/'.$plugin);
            // convert plugin data to json response format.
            $active_plugins_data[] = array(
                'plugin'            => $plugin,
                'name'              => $data['Name'],
                'version'           => $data['Version'],
                'url'               => $data['PluginURI'],
                'author_name'       => $data['AuthorName'],
                'author_url'        => esc_url_raw($data['AuthorURI']),
                'network_activated' => $data['Network'],
            );
        }
        return $active_plugins_data;
    }

    /**
     * parse web request and check for avecdo api calls.
     * @param WP $wp
     * @return void
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    public function parseRequest($wp)
    {
        if (!isset($wp->query_vars['avecdo-api'])) {
            return;
        }
        $valid_actions = array('product', 'category', 'shop');
        $action        = $wp->query_vars['action'];
        if (!empty($action) && in_array($action, $valid_actions)) {

            // SDK hack,  uses $_GET
            $_GET = array_merge($_GET, $wp->query_vars);

            WooAPI::make()->bindContext($this)->routeRequest($this->getKeySet());
        }
    }

    /**
     * call WP function 'add_rewrite_rule' and register rewrite routes
     * @return void
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    public function registerRewriteRules()
    {
        // avecdo-api/ACTION/-page-
        add_rewrite_rule('^avecdo-api/(product|category|shop)/([0-9]+)?', 'index.php?avecdo-api=true&action=$matches[1]&page=$matches[2]', 'top');

        // avecdo-api/(product | category | shop)
        add_rewrite_rule('^avecdo-api/(product|category|shop)?', 'index.php?avecdo-api=true&action=$matches[1]', 'top');
    }

    /**
     * append routed vars for use in avecdo api routes
     * @param array $vars
     * @return array
     * @author Christian M. Jensen <christian@modified.dk>
     * @since 1.1.2
     */
    public function routeQueryVars($vars)
    {
        $vars[] = 'avecdo-api';
        $vars[] = 'action';
        $vars[] = 'page';
        return $vars;
    }

    public function registerPluginActions()
    {
        // register plguin rewrite rules
        add_action('init', $this->getCallBackToMe('registerRewriteRules'));
        // append supported query vars
        add_filter('query_vars', $this->getCallBackToMe('routeQueryVars'));
        // parse page request if query == avecdo-api
        add_action('parse_request', $this->getCallBackToMe('parseRequest'), 0);
        // show update notice etc..
        add_action( 'network_admin_notices', 'avecdoShowNotice' );
        add_action( 'admin_notices', 'avecdoShowNotice' );

        $this->registerProductHooks();

        // activate / deactivate hhoks
        register_activation_hook(__FILE__, $this->getCallBackToMe('activate'));
        register_deactivation_hook(__FILE__, $this->getCallBackToMe('deactivate'));

        // admin page hooks (plugin page)
        add_action('admin_enqueue_scripts', $this->getCallBackToMe('registerAdminCss'));
        if (is_admin()) {
            add_action('admin_menu', $this->getCallBackToMe('registerAdminMenuItem'));
            add_filter('plugin_row_meta', $this->getCallBackToMe('pluginRowMeta'), 10, 2);
            add_filter('plugin_action_links_'.AVECDO_WOOCOMMERCE_PLUGIN_BASENAME, $this->getCallBackToMe('registerActionLinks'));
        }
    }

    /**
     * Register hooks for product input fields
     */
    private function registerProductHooks()
    {
        //Simple Product Hooks
        add_action('woocommerce_product_options_general_product_data', $this->getCallBackToMe('customProductFieldsGeneral'));
        // Save Fields
        add_action('woocommerce_process_product_meta', $this->getCallBackToMe('saveSustomProductFieldsGeneral'));

        //Variable Product Hooks
        add_action( 'woocommerce_product_after_variable_attributes', $this->getCallBackToMe('customProductFieldsVariable'), 10, 3 );
        //Save variation fields
        add_action( 'woocommerce_save_product_variation',  $this->getCallBackToMe('saveCustomProductFieldsVariable'), 10, 1 );

    }

    /**
     * Save custom fields to product vaiations form for Brand Name and UPC, MPN, EAN and ISBN Numbers
     * @param type $post_id
     */
    public function saveCustomProductFieldsVariable($post_id)
    {
        if ( isset( $_POST['variable_post_id'] ) ) {

            $variablePostId = $_POST['variable_post_id'];

            $max_loop = max(array_keys($_POST['variable_post_id']));

            for ($i = 0; $i <= $max_loop; $i++) {
                if (!isset($variablePostId[$i])) {
                    continue;
                }
                $variationId = (int) $variablePostId[$i];

                // Brand Field
                $brand = isset($_POST['_avecdo_variable_brand'][$i]) ? $_POST['_avecdo_variable_brand'][$i]: null;
                if (!empty($brand)) {
                    update_post_meta($variationId, '_avecdo_brand', stripslashes($brand));
                }

                // MPN Field
                $mpn = isset($_POST['_avecdo_variable_mpn'][$i]) ? $_POST['_avecdo_variable_mpn'][$i]: null;
                if (!empty($mpn)) {
                    update_post_meta($variationId, '_avecdo_mpn', stripslashes($mpn));
                }

                // UPC Field
                $upc = isset($_POST['_avecdo_variable_upc'][$i]) ? $_POST['_avecdo_variable_upc'][$i]: null;
                if (!empty($upc)) {
                    update_post_meta($variationId, '_avecdo_upc', stripslashes($upc));
                }

                // EAN Field
                $ean = isset($_POST['_avecdo_variable_ean'][$i]) ? $_POST['_avecdo_variable_ean'][$i]: null;
                if (!empty($ean)) {
                    update_post_meta($variationId, '_avecdo_ean', stripslashes($ean));
                }

                // ISBN Field
                $isbn = isset($_POST['_avecdo_variable_isbn'][$i]) ? $_POST['_avecdo_variable_isbn'][$i]: null;
                if (!empty($isbn)) {
                    update_post_meta($variationId, '_avecdo_isbn', stripslashes($isbn));
                }

                // JAN Field
                $jan = isset($_POST['_avecdo_variable_jan'][$i]) ? $_POST['_avecdo_variable_jan'][$i]: null;
                if (!empty($jan)) {
                    update_post_meta($variationId, '_avecdo_jan', stripslashes($jan));
                }
            }
        }
    }

    /**
     * Add new custom fields to product vaiations form for Brand Name and UPC, MPN, EAN and ISBN Numbers
     * @param type $loop
     * @param type $variation_id
     * @param type $variation
     */
    public function customProductFieldsVariable($loop, $variation_id, $variation)
    {
        echo "<div>";
        //Brand field
        if (!avecdoHasBrandsPluginInstalled()) {
            woocommerce_wp_text_input(
                array(
                    'id'            => '_avecdo_variable_brand['.$loop.']',
                    'label'         => __('Brand', 'woocommerce'),
                    'desc_tip'      => 'true',
                    'description'   => __('Enter the product Brand here.', 'woocommerce'),
                    'value'         => get_post_meta($variation->ID, '_avecdo_brand', true),
                    'wrapper_class' => 'form-row',
                )
            );
        }
        //MPN Field
        woocommerce_wp_text_input(
            array(
                'id'            => '_avecdo_variable_mpn['.$loop.']',
                'label'         => __('MPN', 'woocommerce'),
                'desc_tip'      => 'true',
                'placeholder'   => 'Manufacturer Product Number',
                'value'         => get_post_meta($variation->ID, '_avecdo_mpn', true),
                'description'   => __('Enter the manufacturer product number', 'woocommerce'),
                'wrapper_class' => 'form-row',
            )
        );
        echo "</div>";

        echo "<div>";
        //UPC Field
        woocommerce_wp_text_input(
            array(
                'id'            => '_avecdo_variable_upc['.$loop.']',
                'label'         => __('UPC', 'woocommerce'),
                'placeholder'   => 'UPC',
                'desc_tip'      => 'true',
                'value'         => get_post_meta($variation->ID, '_avecdo_upc', true),
                'description'   => __('Enter the product UPC here.', 'woocommerce'),
                'wrapper_class' => 'form-row',
            )
        );

        if (!avecdoHasEANPluginInstalled()) {
        //EAN Field
        woocommerce_wp_text_input(
            array(
                'id'            => '_avecdo_variable_ean['.$loop.']',
                'label'         => __('EAN', 'woocommerce'),
                'desc_tip'      => 'true',
                'placeholder'   => 'EAN',
                'value'         => get_post_meta($variation->ID, '_avecdo_ean', true),
                'description'   => __('Enter the product EAN here.', 'woocommerce'),
                'wrapper_class' => 'form-row',
            )
        );
        }
        echo "</div>";

        echo "<div>";
        //ISBN Field
        woocommerce_wp_text_input(
            array(
                'id'            => '_avecdo_variable_isbn['.$loop.']',
                'label'         => __('ISBN', 'woocommerce'),
                'desc_tip'      => 'true',
                'placeholder'   => 'ISBN',
                'value'         => get_post_meta($variation->ID, '_avecdo_isbn', true),
                'description'   => __('Enter the product ISBN here.', 'woocommerce'),
                'wrapper_class' => 'form-row',
            )
        );
        //JAN Field
        woocommerce_wp_text_input(
            array(
                'id'            => '_avecdo_variable_jan['.$loop.']',
                'label'         => __('JAN', 'woocommerce'),
                'desc_tip'      => 'true',
                'placeholder'   => 'JAN',
                'value'         => get_post_meta($variation->ID, '_avecdo_jan', true),
                'description'   => __('Enter the product ISBN here.', 'woocommerce'),
                'wrapper_class' => 'form-row',
            )
        );
        echo "</div>";
    }

    /**
     * Save custom fields to product form for Brand Name and UPC, MPN, EAN and ISBN Numbers
     * @param type $post_id
     */
    public function saveSustomProductFieldsGeneral($post_id)
    {
        if (isset($_POST['_avecdo_brand'])) {
            update_post_meta($post_id, '_avecdo_brand', esc_attr($_POST['_avecdo_brand']));
        }

        if (isset($_POST['_avecdo_mpn'])) {
            update_post_meta($post_id, '_avecdo_mpn', esc_attr($_POST['_avecdo_mpn']));
        }

        if (isset($_POST['_avecdo_upc'])) {
            update_post_meta($post_id, '_avecdo_upc', esc_attr($_POST['_avecdo_upc']));
        }

        if (isset($_POST['_avecdo_ean'])) {
            update_post_meta($post_id, '_avecdo_ean', esc_attr($_POST['_avecdo_ean']));
        }

        if (isset($_POST['_avecdo_isbn'])) {
            update_post_meta($post_id, '_avecdo_isbn', esc_attr($_POST['_avecdo_isbn']));
        }

        if (isset($_POST['_avecdo_jan'])) {
            update_post_meta($post_id, '_avecdo_isbn', esc_attr($_POST['_avecdo_jan']));
        }
    }

    /**
     * Add new custom fields to product form for Brand Name and UPC, MPN, EAN and ISBN Numbers
     * @global type $woocommerce
     * @global type $post
     */
    public function customProductFieldsGeneral()
    {
        global $woocommerce, $post;

        //Brand field
        if (!avecdoHasBrandsPluginInstalled()) {
            echo '<div class="options_group">';
            woocommerce_wp_text_input(
                array(
                    'id'          => '_avecdo_brand',
                    'label'       => __('Brand', 'woocommerce'),
                    'desc_tip'    => 'true',
                    //'type'        => 'text',
                    'value'       => get_post_meta($post->ID, '_avecdo_brand', true),
                    'description' => __('Enter the product Brand here.', 'woocommerce')
                )
            );
            echo '</div>';
        }
        echo '<div class="options_group show_if_simple show_if_external">';

        //MPN Field
        woocommerce_wp_text_input(
            array(
                'id'          => '_avecdo_mpn',
                'label'       => __('MPN', 'woocommerce'),
                'desc_tip'    => 'true',
                'placeholder'   => 'Manufacturer Product Number',
                'value'       => get_post_meta($post->ID, '_avecdo_mpn', true),
                'description' => __('Enter the manufacturer product number', 'woocommerce'),
            )
        );

        //UPC Field
        woocommerce_wp_text_input(
            array(
                'id'            => '_avecdo_upc',
                'label'         => __('UPC', 'woocommerce'),
                'desc_tip'      => 'true',
                'placeholder'   => 'UPC',
                'value'         => get_post_meta($post->ID, '_avecdo_upc', true),
                'description'   => __('Enter the product UPC here.', 'woocommerce'),
            )
        );

        //EAN Field
        woocommerce_wp_text_input(
            array(
                'id'            => '_avecdo_ean',
                'label'         => __('EAN', 'woocommerce'),
                'desc_tip'      => 'true',
                'placeholder'   => 'EAN',
                'value'         => get_post_meta($post->ID, '_avecdo_ean', true),
                'description'   => __('Enter the product EAN here.', 'woocommerce'),
            )
        );

        //ISBN Field
        woocommerce_wp_text_input(
            array(
                'id'          => '_avecdo_isbn',
                'label'       => __('ISBN', 'woocommerce'),
                'desc_tip'    => 'true',
                'value'       => get_post_meta($post->ID, '_avecdo_isbn', true),
                'description' => __('Enter the product ISBN here.', 'woocommerce'),
            )
        );

        //JAN Field
        woocommerce_wp_text_input(
            array(
                'id'          => '_avecdo_jan',
                'label'       => __('JAN', 'woocommerce'),
                'desc_tip'    => 'true',
                'value'       => get_post_meta($post->ID, '_avecdo_jan', true),
                'description' => __('Enter the product JAN here.', 'woocommerce'),
            )
        );
        echo '</div>';
    }

    private function getCallBackToMe($method)
    {
        return array($this, $method);
    }
}
