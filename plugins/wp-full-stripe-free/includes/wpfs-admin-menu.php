<?php

class MM_WPFS_Admin_Menu
{
    use MM_WPFS_Logger_AddOn;
    use MM_WPFS_StaticContext_AddOn;

    /** @var MM_WPFS_Database */
    private $db = null;
    /** @var MM_WPFS_Stripe */
    private $stripe = null;
    /** @var MM_WPFS_HelpService */
    private $helpService;
    /** @var MM_WPFS_Options */
    private $options = null;

    private $capability = 'manage_options';

    const UPDATE_INTERVAL_4_HOURS = 14400;
    const UPDATE_INTERVAL_30_MINUTES = 1800;

    const SLUG_FORMS = 'wpfs-forms';
    const SLUG_TRANSACTIONS = 'wpfs-transactions';
    const SLUG_CREATE_FORM = 'wpfs-create-form';
    const SLUG_EDIT_FORM = 'wpfs-edit-form';
    const SLUG_SETTINGS = 'wpfs-settings';
    const SLUG_SETTINGS_STRIPE = 'wpfs-settings-stripe';
    const SLUG_SETTINGS_FORMS = 'wpfs-settings-forms';
    const SLUG_SETTINGS_CUSTOMER_PORTAL = 'wpfs-settings-customer-portal';
    const SLUG_SETTINGS_SECURITY = 'wpfs-settings-security';
    const SLUG_SETTINGS_EMAIL_NOTIFICATIONS = 'wpfs-settings-email';
    const SLUG_SETTINGS_WORDPRESS_DASHBOARD = 'wpfs-settings-wp-dashboard';
    const SLUG_SETTINGS_ADDONS = 'wpfs-settings-addons';
    const SLUG_SETTINGS_LOGS = 'wpfs-settings-logs';
    const SLUG_ADDONS = 'wpfs-transactions-addons';

    const PARAM_NAME_FORM_TEXT_FILTER = 'wpfs-form-text-filter';
    const PARAM_NAME_FORM_MODE_FILTER = 'wpfs-form-mode-filter';
    const PARAM_NAME_PAYMENTS_TEXT_FILTER = 'wpfs-payments-text-filter';
    const PARAM_NAME_PAYMENTS_STATUS_FILTER = 'wpfs-payments-status-filter';
    const PARAM_NAME_PAYMENTS_MODE_FILTER = 'wpfs-payments-mode-filter';
    const PARAM_NAME_SUBSCRIPTIONS_TEXT_FILTER = 'wpfs-subscriptions-text-filter';
    const PARAM_NAME_SUBSCRIPTIONS_STATUS_FILTER = 'wpfs-subscriptions-status-filter';
    const PARAM_NAME_SUBSCRIPTIONS_MODE_FILTER = 'wpfs-subscriptions-mode-filter';
    const PARAM_NAME_DONATIONS_TEXT_FILTER = 'wpfs-donation-text-filter';
    const PARAM_NAME_DONATIONS_MODE_FILTER = 'wpfs-donation-mode-filter';
    const PARAM_NAME_SAVED_CARDS_TEXT_FILTER = 'wpfs-saved-cards-text-filter';
    const PARAM_NAME_SAVED_CARDS_MODE_FILTER = 'wpfs-saved-cards-mode-filter';
    const PARAM_NAME_TAB = 'tab';
    const PARAM_NAME_TYPE = 'type';
    const PARAM_NAME_ORDER_BY = 'orderby';
    const PARAM_NAME_ORDER = 'order';
    const PARAM_NAME_FORM = 'form';

    const PARAM_VALUE_API_MODE_ALL = 'all';
    const PARAM_VALUE_API_MODE_TEST = 'test';
    const PARAM_VALUE_API_MODE_LIVE = 'live';
    const PARAM_VALUE_PAYMENT_STATUS_ALL = 'all';
    const PARAM_VALUE_SUBSCRIPTION_STATUS_ALL = 'all';
    const PARAM_VALUE_TAB_PAYMENTS = 'payments';
    const PARAM_VALUE_TAB_SUBSCRIPTIONS = 'subscriptions';
    const PARAM_VALUE_TAB_DONATIONS = 'donations';
    const PARAM_VALUE_TAB_SAVED_CARDS = 'saved-cards';
    const PARAM_VALUE_TAB_OPTIONS = 'options';
    const PARAM_VALUE_TAB_TEMPLATES = 'templates';
    const PARAM_VALUE_TAB_APPEARANCE = 'appearance';
    const PARAM_VALUE_TAB_GENERAL = 'general';
    const PARAM_VALUE_TAB_PAYMENT = 'payment';
    const PARAM_VALUE_TAB_TAX = 'tax';
    const PARAM_VALUE_TAB_FORM_LAYOUT = 'form-layout';
    const PARAM_VALUE_TAB_EMAIL_NOTIFICATIONS = 'email-notifications';

    const COOKIE_NAME_TAB_ID = 'wpfsTabId';

    const ENVATO_ADDONS_URL = 'https://codecanyon.net/search/wp%20full%20stripe';

    public function __construct($loggerService)
    {
        $this->initLogger($loggerService, MM_WPFS_LoggerService::MODULE_ADMIN);

        $this->db = new MM_WPFS_Database();
        $this->helpService = new MM_WPFS_HelpService($this->loggerService);
        $this->options = new MM_WPFS_Options();
        $this->initStaticContext();

        $this->stripe = new MM_WPFS_Stripe(MM_WPFS_Stripe::getStripeAuthenticationToken($this->staticContext), $this->loggerService);

        $this->initStaticContext();
        $this->initActionHooks();
        $this->initDemoMode();
    }

    private function initActionHooks()
    {
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_menu', array($this, 'menu_pages'));
    }

    private function initDemoMode()
    {
        if (MM_WPFS_Utils::isDemoMode()) {
            $this->capability = 'read';
        }
    }

    /**
     * @param $form \StdClass
     * @return array
     */
    protected function prepareOnetimeProducts($form)
    {
        $savedProducts = MM_WPFS_Utils::decodeJsonArray($form->decoratedProducts);
        $priceIds = MM_WPFS_Pricing::extractPriceIdsFromProductsStatic($savedProducts);

        $products = array();
        $productsLookup = array();

        $stripePrices = array();
        try {
            $stripePrices = $this->stripe->getOnetimePrices();
        } catch (Exception $ex) {
            $this->logger->error(__FUNCTION__, 'Cannot retrieve Stripe one-time prices', $ex);
        }
        foreach ($stripePrices as $stripePrice) {
            if (array_search($stripePrice->id, $priceIds) !== false) {
                $productsLookup[$stripePrice->id] = array(
                    'name' => $stripePrice->product->name,
                    'price' => $stripePrice->unit_amount,
                    'currency' => $stripePrice->currency,
                    'stripePriceId' => $stripePrice->id
                );
            }
        }

        foreach ($priceIds as $priceId) {
            if (array_key_exists($priceId, $productsLookup)) {
                array_push($products, $productsLookup[$priceId]);
            }
        }

        return $products;
    }

    /**
     * @param $form \StdClass
     * @return array
     */
    protected function prepareTaxRates($form)
    {
        $savedTaxRates = array();
        if (!is_null($form->vatRates)) {
            $savedTaxRates = json_decode($form->vatRates);
        }
        $taxRateIds = MM_WPFS_Pricing::extractTaxRateIdsStatic($savedTaxRates);

        $taxRates = array();
        $taxRatesLookup = array();

        $stripeTaxRates = array();
        try {
            $stripeTaxRates = $this->stripe->getTaxRates();
        } catch (Exception $ex) {
            $this->logger->error(__FUNCTION__, 'Cannot retrieve Stripe tax rates', $ex);
        }
        foreach ($stripeTaxRates as $stripeTaxRate) {
            if (array_search($stripeTaxRate->id, $taxRateIds) !== false) {
                $taxRatesLookup[$stripeTaxRate->id] = array(
                    'displayName' => $stripeTaxRate->display_name,
                    'inclusive' => $stripeTaxRate->inclusive,
                    'country' => $stripeTaxRate->country,
                    'countryLabel' => MM_WPFS_Countries::getCountryNameFor($stripeTaxRate->country),
                    'state' => $stripeTaxRate->state,
                    'stateLabel' => MM_WPFS_States::getStateNameFor($stripeTaxRate->state),
                    'percentage' => $stripeTaxRate->percentage,
                    'jurisdiction' => $stripeTaxRate->jurisdiction,
                    'taxRateId' => $stripeTaxRate->id
                );
            }
        }

        foreach ($taxRateIds as $taxRateId) {
            if (array_key_exists($taxRateId, $taxRatesLookup)) {
                array_push($taxRates, $taxRatesLookup[$taxRateId]);
            }
        }

        return $taxRates;
    }

    /**
     * @param $classes
     *
     * @return string
     */
    function wpfs_admin_body_class($classes)
    {
        return "$classes wpfs-body";
    }

    function admin_init()
    {
        wp_register_style('fullstripe-admin-revamped-css', MM_WPFS_Assets::css('wpfs-admin.css'), null, MM_WPFS::VERSION);
        wp_register_style('fullstripe-admin-revamped-css-extra', MM_WPFS_Assets::css('wpfs-admin-extra.css'), null, MM_WPFS::VERSION);
        wp_register_style('fullstripe-slick-css', MM_WPFS_Assets::css('slick.css'), null, MM_WPFS::VERSION);

        add_filter('admin_body_class', array($this, 'wpfs_admin_body_class'));
    }

    function menu_pages()
    {

        $page_title =
            /* translators: Browser page title of the "Transactions" page in WordPress admin */
            __('Full Pay - Transactions', 'wp-full-stripe-admin');
        $menu_title =
            /* translators: Menu title of the plugin in WordPress admin */
            __('Full Pay', 'wp-full-stripe-admin');
        $menu_slug = self::SLUG_TRANSACTIONS;
        $capability = $this->capability;
        $function = array($this, 'fullstripe_transactions');
        $menu_icon = MM_WPFS_Assets::images('wpfs-admin-icon.svg');
        add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $menu_icon);

        // Add submenu page with same slug as parent to ensure no duplicates
        $sub_menu_title =
            /* translators: Submenu title of the "Transactions" page in WordPress admin */
            __('Transactions', 'wp-full-stripe-admin');
        $menu_hook = add_submenu_page($menu_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);
        add_action('admin_print_scripts-' . $menu_hook, array(
            $this,
            'fullstripe_admin_scripts'
        )); //this ensures script/styles only loaded for this plugin admin pages

        $submenu_page_title =
            /* translators: Browser page title of the "Manage forms" page in WordPress admin */
            __('Full Pay - Manage forms', 'wp-full-stripe-admin');
        $submenu_title =
            /* translators: Submenu title of the "Manage forms" page in WordPress admin */
            __('Forms', 'wp-full-stripe-admin');
        $submenu_slug = self::SLUG_FORMS;
        $submenu_function = array($this, 'fullstripe_forms');
        $menu_hook = add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
        add_action('admin_print_scripts-' . $menu_hook, array($this, 'fullstripe_admin_scripts'));

        $submenu_page_title =
            /* translators: Browser page title of the "Create form" page in WordPress admin */
            __('Full Pay - Create form', 'wp-full-stripe-admin');
        $submenu_title =
            /* translators: Submenu title of the "Create form" page in WordPress admin */
            __('Create form', 'wp-full-stripe-admin');
        $submenu_slug = self::SLUG_CREATE_FORM;
        $submenu_function = array($this, 'fullstripe_create_form');
        $menu_hook = add_submenu_page(self::SLUG_FORMS, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
        add_action('admin_print_scripts-' . $menu_hook, array($this, 'fullstripe_admin_scripts'));

        $submenu_page_title =
            /* translators: Browser page title of "Settings" page in WordPress admin */
            __('Full Pay - Settings', 'wp-full-stripe-admin');
        $submenu_title =
            /* translators: Submenu title of the "Settings" page in WordPress admin */
            __('Settings', 'wp-full-stripe-admin');
        $submenu_slug = self::SLUG_SETTINGS;
        $submenu_function = array($this, 'fullstripe_settings');
        $menu_hook = add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
        add_action('admin_print_scripts-' . $menu_hook, array($this, 'fullstripe_admin_scripts'));

        $submenu_page_title =
            /* translators: Browser page title of the "Configure Stripe account" page in WordPress admin */
            __('Full Pay - Configure Stripe account', 'wp-full-stripe-admin');
        $submenu_title =
            /* translators: Submenu title of the "Configure Stripe account" page in WordPress admin */
            __('Stripe account', 'wp-full-stripe-admin');
        $submenu_slug = self::SLUG_SETTINGS_STRIPE;
        $submenu_function = array($this, 'fullstripe_settings_stripe');
        $menu_hook = add_submenu_page(self::SLUG_SETTINGS, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
        add_action('admin_print_scripts-' . $menu_hook, array($this, 'fullstripe_admin_scripts'));

        $submenu_page_title =
            /* translators: Browser page title of the "Customize forms" page in WordPress admin */
            __('Full Pay - Customize forms', 'wp-full-stripe-admin');
        $submenu_title =
            /* translators: Submenu title of the "Customize forms" page in WordPress admin */
            __('Forms', 'wp-full-stripe-admin');
        $submenu_slug = self::SLUG_SETTINGS_FORMS;
        $submenu_function = array($this, 'fullstripe_settings_forms');
        $menu_hook = add_submenu_page(self::SLUG_SETTINGS, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
        add_action('admin_print_scripts-' . $menu_hook, array($this, 'fullstripe_admin_scripts'));

        $submenu_page_title =
            /* translators: Browser page title of the "Email notifications" page in WordPress admin */
            __('Full Pay - Email notifications', 'wp-full-stripe-admin');
        $submenu_title =
            /* translators: Submenu title of the "Email notifications" page in WordPress admin */
            __('Email notifications', 'wp-full-stripe-admin');
        $submenu_slug = self::SLUG_SETTINGS_EMAIL_NOTIFICATIONS;
        $submenu_function = array($this, 'fullstripe_settings_email');
        $menu_hook = add_submenu_page(self::SLUG_SETTINGS, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
        add_action('admin_print_scripts-' . $menu_hook, array($this, 'fullstripe_admin_scripts'));

        $submenu_page_title =
            /* translators: Browser page title of the "Security settings" page in WordPress admin */
            __('Full Pay - Security settings', 'wp-full-stripe-admin');
        $submenu_title =
            /* translators: Submenu title of the "Security settings" page in WordPress admin */
            __('Security', 'wp-full-stripe-admin');
        $submenu_slug = self::SLUG_SETTINGS_SECURITY;
        $submenu_function = array($this, 'fullstripe_settings_security');
        $menu_hook = add_submenu_page(self::SLUG_SETTINGS, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
        add_action('admin_print_scripts-' . $menu_hook, array($this, 'fullstripe_admin_scripts'));

        $submenu_page_title =
            /* translators: Browser page title of the "Customer portal" page in WordPress admin */
            __('Full Pay - Customer portal', 'wp-full-stripe-admin');
        $submenu_title =
            /* translators: Submenu title of the "Customer portal" page in WordPress admin */
            __('Customer portal', 'wp-full-stripe-admin');
        $submenu_slug = self::SLUG_SETTINGS_CUSTOMER_PORTAL;
        $submenu_function = array($this, 'fullstripe_settings_my_account');
        $menu_hook = add_submenu_page(self::SLUG_SETTINGS, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
        add_action('admin_print_scripts-' . $menu_hook, array($this, 'fullstripe_admin_scripts'));

        $submenu_page_title =
            /* translators: Browser page title of the "WordPress dashboard settings" page in WordPress admin */
            __('Full Pay - WordPress dashboard settings', 'wp-full-stripe-admin');
        $submenu_title =
            /* translators: Submenu title of the "WordPress dashboard settings" page in WordPress admin */
            __('WordPress dashboard', 'wp-full-stripe-admin');
        $submenu_slug = self::SLUG_SETTINGS_WORDPRESS_DASHBOARD;
        $submenu_function = array($this, 'fullstripe_settings_wp_dashboard');
        $menu_hook = add_submenu_page(self::SLUG_SETTINGS, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
        add_action('admin_print_scripts-' . $menu_hook, array($this, 'fullstripe_admin_scripts'));

        $submenu_page_title =
            /* translators: Browser page title of the "Add-ons" settings page in WordPress admin */
            __('Full Pay - Add-ons', 'wp-full-stripe-admin');
        $submenu_title =
            /* translators: Submenu title of the "Add-ons" settings page in WordPress admin */
            __('Add-ons', 'wp-full-stripe-admin');
        $submenu_slug = self::SLUG_SETTINGS_ADDONS;
        $submenu_function = array($this, 'fullstripe_settings_addons');
        $menu_hook = add_submenu_page(self::SLUG_SETTINGS, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
        add_action('admin_print_scripts-' . $menu_hook, array($this, 'fullstripe_admin_scripts'));

        $submenu_page_title =
            /* translators: Browser page title of the "Add-ons" settings page in WordPress admin */
            __('Full Pay - Error logging', 'wp-full-stripe-admin');
        $submenu_title =
            /* translators: Submenu title of the "Error logging" settings page in WordPress admin */
            __('Error logging', 'wp-full-stripe-admin');
        $submenu_slug = self::SLUG_SETTINGS_LOGS;
        $submenu_function = array($this, 'fullstripe_settings_logs');
        $menu_hook = add_submenu_page(self::SLUG_SETTINGS, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
        add_action('admin_print_scripts-' . $menu_hook, array($this, 'fullstripe_admin_scripts'));

        $submenu_page_title =
            /* translators: Browser page title of the "Edit form" page in WordPress admin */
            __('Full Pay - Edit form', 'wp-full-stripe-admin');
        $submenu_title =
            /* translators: Submenu title of the "Edit form" page in WordPress admin */
            __('Edit form', 'wp-full-stripe-admin');
        $submenu_slug = self::SLUG_EDIT_FORM;
        $submenu_function = array($this, 'fullstripe_edit_form');
        $menu_hook = add_submenu_page(self::SLUG_FORMS, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
        add_action('admin_print_scripts-' . $menu_hook, array($this, 'fullstripe_admin_scripts'));

        do_action('fullstripe_admin_menus', $menu_slug);
    }

    function compileFrontendAdminOptions()
    {
        $options = $this->options->getSeveral([
            MM_WPFS_Options::OPTION_DECIMAL_SEPARATOR_SYMBOL,
            MM_WPFS_Options::OPTION_SHOW_CURRENCY_SYMBOL_INSTEAD_OF_CODE,
            MM_WPFS_Options::OPTION_SHOW_CURRENCY_SIGN_AT_FIRST_POSITION,
            MM_WPFS_Options::OPTION_PUT_WHITESPACE_BETWEEN_CURRENCY_AND_AMOUNT
        ]);

        // todo: refactor magic strings
        $result = array(
            'customInputFieldCount' => MM_WPFS::getCustomFieldMaxCount($this->staticContext),
            'currencyDecimalSeparatorSymbol' => $options[MM_WPFS_Options::OPTION_DECIMAL_SEPARATOR_SYMBOL],
            'currencyShowSymbolInsteadOfCode' => $options[MM_WPFS_Options::OPTION_SHOW_CURRENCY_SYMBOL_INSTEAD_OF_CODE],
            'currencyShowIdentifierOnLeft' => $options[MM_WPFS_Options::OPTION_SHOW_CURRENCY_SIGN_AT_FIRST_POSITION],
            'currencyPutSpaceBetweenCurrencyAndAmount' => $options[MM_WPFS_Options::OPTION_PUT_WHITESPACE_BETWEEN_CURRENCY_AND_AMOUNT]
        );

        return $result;
    }

    /**
     * @return StdClass
     */
    public static function createMacroData()
    {
        $data = new \StdClass;

        $data->templateIds = apply_filters('fullstripe_email_template_ids', array(
            MM_WPFS::EMAIL_TEMPLATE_ID_PAYMENT_RECEIPT,
            MM_WPFS::EMAIL_TEMPLATE_ID_CARD_SAVED,
            MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_RECEIPT,
            MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_ENDED,
            MM_WPFS::EMAIL_TEMPLATE_ID_DONATION_RECEIPT,
            MM_WPFS::EMAIL_TEMPLATE_ID_CUSTOMER_PORTAL_SECURITY_CODE
        ));
        $data->macroLists = apply_filters('fullstripe_email_template_macros', array(
            MM_WPFS::EMAIL_TEMPLATE_ID_PAYMENT_RECEIPT => MM_WPFS_OneTimePaymentMacroReplacer::getMacroKeys(),
            MM_WPFS::EMAIL_TEMPLATE_ID_CARD_SAVED => MM_WPFS_SaveCardMacroReplacer::getMacroKeys(),
            MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_RECEIPT => MM_WPFS_SubscriptionMacroReplacer::getMacroKeys(),
            MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_ENDED => MM_WPFS_SubscriptionMacroReplacer::getMacroKeys(),
            MM_WPFS::EMAIL_TEMPLATE_ID_DONATION_RECEIPT => MM_WPFS_DonationMacroReplacer::getMacroKeys(),
            MM_WPFS::EMAIL_TEMPLATE_ID_CUSTOMER_PORTAL_SECURITY_CODE => MM_WPFS_MyAccountLoginMacroReplacer::getMacroKeys()
        ));
        $data->descriptions = apply_filters(
            'fullstripe_macro_descriptions',
            MacroHelperTools::getMacroDescriptions()
        );

        return $data;
    }

    function enqueueGlobalScripts()
    {
        wp_enqueue_script('sprintf-js', MM_WPFS_Assets::scripts('sprintf.min.js'), null, MM_WPFS::VERSION);
        wp_enqueue_script('slick-js', MM_WPFS_Assets::scripts('slick.min.js'), null, MM_WPFS::VERSION);
        wp_enqueue_script('wp-full-stripe-utils-js', MM_WPFS_Assets::scripts('wpfs-utils.js'), null, MM_WPFS::VERSION);
    }

    function enqueueGlobalCss()
    {
        wp_enqueue_style('fullstripe-admin-revamped-css');
        wp_enqueue_style('fullstripe-admin-revamped-css-extra');
        wp_enqueue_style('fullstripe-slick-css');
    }

    function fullstripe_admin_scripts()
    {
        $page = $_GET['page'];
        $tab = array_key_exists('tab', $_GET) ? $_GET['tab'] : null;

        wp_enqueue_media();
        $this->enqueueGlobalScripts();

        $localizer = MM_WPFS_AdminScriptLocalizerFactory::createLocalizer($this->options, $page, $tab);
        $localizer->enqueueScripts();

        $dependencies = array(
            'sprintf-js',
            'wp-full-stripe-utils-js',
            'underscore',
            'backbone',
            'jquery',
            'jquery-ui-selectmenu',
            'jquery-ui-dialog',
            'jquery-ui-tabs',
            'jquery-ui-core',
            'jquery-ui-widget',
            'jquery-ui-autocomplete',
            'jquery-ui-button',
            'jquery-ui-tooltip',
            'jquery-ui-sortable'
        );
        wp_enqueue_script('wp-full-stripe-admin-library-js', MM_WPFS_Assets::scripts('wpfs-admin-library.js'), $dependencies, MM_WPFS::VERSION);

        $dependencies = array(
            'wp-full-stripe-admin-library-js',
        );
        $dependencies = array_merge($localizer->getScriptDependencies(), $dependencies);
        wp_enqueue_script('wp-full-stripe-admin-js', MM_WPFS_Assets::scripts('wpfs-admin.js'), $dependencies, MM_WPFS::VERSION);

        $localizer->localizeScripts();

        $wpfsAdminSettings = array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'preferences' => $this->compileFrontendAdminOptions()
        );
        $wpfsAdminSettings = array_merge($wpfsAdminSettings, $localizer->getSettingsOptions());
        wp_localize_script('wp-full-stripe-admin-js', 'wpfsAdminSettings', $wpfsAdminSettings);

        $this->enqueueGlobalCss();

        do_action('fullstripe_admin_scripts');
    }

    /**
     * @param $currentTime int
     * @param $time int
     *
     * @return string
     */
    protected function getFormattedTimeDescription($currentTime, $time)
    {
        $timeDescription = '';

        if ($currentTime - $time < MINUTE_IN_SECONDS) {
            $seconds = $currentTime - $time;
            $timeDescription = sprintf(
                /* translators: Singular and plural message for time number of seconds passed since the last webhook event
                   p1: number of seconds
                */
                _n('Last event one second ago', 'Last event %d seconds ago', $seconds, 'wp-full-stripe-admin'),
                $seconds
            );
        } elseif ($currentTime - $time < HOUR_IN_SECONDS) {
            $minutes = (int) (($currentTime - $time) / MINUTE_IN_SECONDS);
            $timeDescription = sprintf(
                /* translators: Singular and plural message for time number of minutes passed since the last webhook event
                   p1: number of minutes
                */
                _n('Last event one minute ago', 'Last event %d minutes ago', $minutes, 'wp-full-stripe-admin'),
                $minutes
            );
        } elseif ($currentTime - $time < DAY_IN_SECONDS) {
            $hours = (int) (($currentTime - $time) / HOUR_IN_SECONDS);
            $timeDescription = sprintf(
                /* translators: Singular and plural message for time number of hours passed since the last webhook event
                   p1: number of hours
                */
                _n('Last event one hour ago', 'Last event %d hours ago', $hours, 'wp-full-stripe-admin'),
                $hours
            );
        } elseif ($currentTime - $time >= DAY_IN_SECONDS) {
            $days = (int) (($currentTime - $time) / DAY_IN_SECONDS);
            $timeDescription = sprintf(
                /* translators: Singular and plural message for time number of days passed since the last webhook event
                   p1: number of days
                */
                _n('Last event one day ago', 'Last event %d days ago', $days, 'wp-full-stripe-admin'),
                $days
            );
        }

        return $timeDescription;
    }

    /**
     * @param $currentTime int
     * @param $time int
     * @param $isLiveMode boolean
     *
     * @return array
     */
    protected function getLastWebHookEventDescription($currentTime, $time, $isLiveMode)
    {
        $cssStyle = '';
        $title = '';
        $description = '';

        if (is_null($time)) {
            $cssStyle = 'wpfs-status-bullet--grey';
            $description =
                /* translators: Message stating that this webhook URL haven't received any event yet */
                __('Never received event', 'wp-full-stripe-admin');
            if ($isLiveMode) {
                $title =
                    /* translators: Message stating that live webhooks notifications is not set up in Stripe */
                    __('Live webhook is not set up', 'wp-full-stripe-admin');
            } else {
                $title =
                    /* translators: Message stating that test webhooks notifications is not set up in Stripe */
                    __('Test webhook is not set up', 'wp-full-stripe-admin');
            }
        } elseif ($currentTime - $time < WEEK_IN_SECONDS) {
            $cssStyle = 'wpfs-status-bullet--green';
            $description = $this->getFormattedTimeDescription($currentTime, $time);
            if ($isLiveMode) {
                $title =
                    /* translators: Message stating that the live webhooks URL works properly, it's receiving events from Stripe */
                    __('Live webhook works properly', 'wp-full-stripe-admin');
            } else {
                $title =
                    /* translators: Message stating that the test webhooks URL works properly, it's receiving events from Stripe */
                    __('Test webhook works properly', 'wp-full-stripe-admin');
            }
        } elseif ($currentTime - $time >= WEEK_IN_SECONDS) {
            $cssStyle = 'wpfs-status-bullet--red';
            $description = $this->getFormattedTimeDescription($currentTime, $time);
            if ($isLiveMode) {
                /* translators: Message stating that the live webhooks URL may not work properly */
                $title = __('Live webhook may not work properly', 'wp-full-stripe-admin');
            } else {
                /* translators: Message stating that the test webhooks URL may not work properly */
                $title = __('Test webhook may not work properly', 'wp-full-stripe-admin');
            }
        }

        return array($cssStyle, $title, $description);
    }

    protected function getStripeAccountData()
    {
        $stripeData = new \stdClass;
        $currentTime = time();

        if (MM_WPFS_Utils::isDemoMode()) {
            $stripeData->testSecretKey =
                /* translators: Placeholder text for an API test secret key which is not entered yet */
                __('Test secret key here', 'wp-full-stripe-admin');
            $stripeData->testPublishableKey =
                /* translators: Placeholder text for an API test publishable key which is not entered yet */
                __('Test publishable key here', 'wp-full-stripe-admin');
            $stripeData->liveSecretKey =
                /* translators: Placeholder text for an API live secret key which is not entered yet */
                __('Live secret key here', 'wp-full-stripe-admin');
            $stripeData->livePublishableKey =
                /* translators: Placeholder text for an API live publishable key which is not entered yet */
                __('Live publishable key here', 'wp-full-stripe-admin');
            $stripeData->apiMode = MM_WPFS::STRIPE_API_MODE_TEST;
            $stripeData->testAccountId = MM_WPFS::TEST_ACCOUNT_ID;
            $stripeData->liveAccountId = MM_WPFS::LIVE_ACCOUNT_ID;
            $stripeData->useWpTestPlatform = MM_WPFS::USE_WP_TEST_PLATFORM;
            $stripeData->useWpLivePlatform = MM_WPFS::USE_WP_LIVE_PLATFORM;
            $stripeData->webHookUrl = esc_attr(MM_WPFS_EventHandler::getDemoWebhookURL());
            $stripeData->webHookUrlLegacy = esc_attr(MM_WPFS_EventHandler::getDemoWebhookURL());

            list($liveStyle, $liveTitle, $liveDescription) = $this->getLastWebHookEventDescription($currentTime, null, true);
            $stripeData->liveEventStyle = $liveStyle;
            $stripeData->liveEventTitle = $liveTitle;
            $stripeData->liveEventDescription = $liveDescription;

            list($testStyle, $testTitle, $testDescription) = $this->getLastWebHookEventDescription($currentTime, $currentTime - MINUTE_IN_SECONDS * 5, false);
            $stripeData->testEventStyle = $testStyle;
            $stripeData->testEventTitle = $testTitle;
            $stripeData->testEventDescription = $testDescription;
        } else {
            $options = $this->options->getSeveral([
                MM_WPFS_Options::OPTION_API_TEST_SECRET_KEY,
                MM_WPFS_Options::OPTION_API_TEST_PUBLISHABLE_KEY,
                MM_WPFS_Options::OPTION_API_LIVE_SECRET_KEY,
                MM_WPFS_Options::OPTION_API_LIVE_PUBLISHABLE_KEY,
                MM_WPFS_Options::OPTION_API_MODE,
                MM_WPFS_Options::OPTION_TEST_ACCOUNT_ID,
                MM_WPFS_Options::OPTION_LIVE_ACCOUNT_ID,
                MM_WPFS_Options::OPTION_TEST_ACCOUNT_STATUS,
                MM_WPFS_Options::OPTION_LIVE_ACCOUNT_STATUS,
                MM_WPFS_Options::OPTION_USE_WP_TEST_PLATFORM,
                MM_WPFS_Options::OPTION_USE_WP_LIVE_PLATFORM,
                MM_WPFS_Options::OPTION_LAST_WEBHOOK_EVENT_LIVE,
                MM_WPFS_Options::OPTION_LAST_WEBHOOK_EVENT_TEST
            ]);

            $stripeData->testSecretKey = $options[MM_WPFS_Options::OPTION_API_TEST_SECRET_KEY];
            $stripeData->testPublishableKey = $options[MM_WPFS_Options::OPTION_API_TEST_PUBLISHABLE_KEY];
            $stripeData->liveSecretKey = $options[MM_WPFS_Options::OPTION_API_LIVE_SECRET_KEY];
            $stripeData->livePublishableKey = $options[MM_WPFS_Options::OPTION_API_LIVE_PUBLISHABLE_KEY];
            $stripeData->apiMode = $options[MM_WPFS_Options::OPTION_API_MODE];
            $stripeData->testAccountId = $options[MM_WPFS_Options::OPTION_TEST_ACCOUNT_ID];
            $stripeData->liveAccountId = $options[MM_WPFS_Options::OPTION_LIVE_ACCOUNT_ID];
            $stripeData->testAccountStatus = $options[MM_WPFS_Options::OPTION_TEST_ACCOUNT_STATUS];
            $stripeData->liveAccountStatus = $options[MM_WPFS_Options::OPTION_LIVE_ACCOUNT_STATUS];
            $stripeData->useWpTestPlatform = $options[MM_WPFS_Options::OPTION_USE_WP_TEST_PLATFORM];
            $stripeData->useWpLivePlatform = $options[MM_WPFS_Options::OPTION_USE_WP_LIVE_PLATFORM];
            if (isset($stripeData->testAccountId)) {
                $stripeData->testAccountLink = $this->stripe->getTestAccountLink($options[MM_WPFS_Options::OPTION_TEST_ACCOUNT_ID], home_url(add_query_arg(null, null)), home_url(add_query_arg(null, null)));
            }
            if (isset($stripeData->liveAccountId)) {
                $stripeData->liveAccountLink = $this->stripe->getLiveAccountLink($options[MM_WPFS_Options::OPTION_LIVE_ACCOUNT_ID], home_url(add_query_arg(null, null)), home_url(add_query_arg(null, null)));
            }

            $stripeData->webHookUrl = esc_attr(MM_WPFS_EventHandler::getWebhookEndpointURL($this->staticContext));
            $stripeData->webHookUrlLegacy = esc_attr(MM_WPFS_EventHandler::getLegacyWebhookEndpointURL($this->staticContext));

            list($liveStyle, $liveTitle, $liveDescription) = $this->getLastWebHookEventDescription($currentTime, $options[MM_WPFS_Options::OPTION_LAST_WEBHOOK_EVENT_LIVE], true);
            $stripeData->liveEventStyle = $liveStyle;
            $stripeData->liveEventTitle = $liveTitle;
            $stripeData->liveEventDescription = $liveDescription;

            list($testStyle, $testTitle, $testDescription) = $this->getLastWebHookEventDescription($currentTime, $options[MM_WPFS_Options::OPTION_LAST_WEBHOOK_EVENT_TEST], false);
            $stripeData->testEventStyle = $testStyle;
            $stripeData->testEventTitle = $testTitle;
            $stripeData->testEventDescription = $testDescription;
        }

        return $stripeData;
    }

    /**
     * @return stdClass
     */
    protected function getStripeStatus()
    {
        $stripeStatus = new \stdClass;

        if (MM_WPFS_Utils::isDemoMode()) {
            $stripeStatus->apiModeLabel = MM_WPFS_Admin::getApiModeLabelFromString(MM_WPFS::STRIPE_API_MODE_TEST);
        } else {
            $stripeStatus->apiModeLabel = MM_WPFS_Admin::getApiModeLabelFromString($this->options->get(MM_WPFS_Options::OPTION_API_MODE));
        }
        $stripeStatus->manageStripeAccountsUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_SETTINGS_STRIPE);

        return $stripeStatus;
    }

    function fullstripe_settings_stripe()
    {
        if (!current_user_can($this->capability)) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        $pageTitle =
            /* translators: Page title of the "Configure Stripe account" page in WordPress admin  */
            __('Configure Stripe account', 'wp-full-stripe-admin');
        $stripeStatus = $this->getStripeStatus();
        $backLinkUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(self::SLUG_SETTINGS);
        $backLinkLabel =
            /* translators: Link label which takes back to the "Settings" page  */
            __('Back to Settings', 'wp-full-stripe-admin');
        $view = new MM_WPFS_Admin_ConfigureStripeAccountView();
        $stripeData = $this->getStripeAccountData();
        $help = $this->helpService->getContextSensitiveHelp($_REQUEST);

        /** @noinspection PhpIncludeInspection */
        include MM_WPFS_Assets::templates('admin/wpfs-settings-stripe.php');
    }

    protected function getSecurityData()
    {
        $securityData = new \stdClass;
        $options = $this->options->getSeveral([
            MM_WPFS_Options::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA,
            MM_WPFS_Options::OPTION_SECURE_CHECKOUT_FORMS_WITH_GOOGLE_RE_CAPTCHA,
            MM_WPFS_Options::OPTION_SECURE_CUSTOMER_PORTAL_WITH_GOOGLE_RE_CAPTCHA,
            MM_WPFS_Options::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY,
            MM_WPFS_Options::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY
        ]);

        $securityData->secureInlineForms = $options[MM_WPFS_Options::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA];
        $securityData->secureCheckoutForms = $options[MM_WPFS_Options::OPTION_SECURE_CHECKOUT_FORMS_WITH_GOOGLE_RE_CAPTCHA];
        $securityData->secureCustomerPortal = $options[MM_WPFS_Options::OPTION_SECURE_CUSTOMER_PORTAL_WITH_GOOGLE_RE_CAPTCHA];

        if (MM_WPFS_Utils::isDemoMode()) {
            // todo: refactor magic strings
            $securityData->recaptchaSecretKey = 'Google_reCaptcha_secret_key_here';
            $securityData->recaptchaSiteKey = 'Google_reCaptcha_site_key_here';
        } else {
            $securityData->recaptchaSecretKey = $options[MM_WPFS_Options::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY];
            $securityData->recaptchaSiteKey = $options[MM_WPFS_Options::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY];
        }

        return $securityData;
    }

    function fullstripe_settings_security()
    {
        if (!current_user_can($this->capability)) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        $pageTitle =
            /* translators: Page title of the "Security settings" page in WordPress admin  */
            __('Security settings', 'wp-full-stripe-admin');
        $stripeStatus = $this->getStripeStatus();
        $backLinkUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(self::SLUG_SETTINGS);
        $backLinkLabel =
            /* translators: Link label which takes back to the "Settings" page  */
            __('Back to Settings', 'wp-full-stripe-admin');
        $view = new MM_WPFS_Admin_SecurityView();
        $securityData = $this->getSecurityData();
        $help = $this->helpService->getContextSensitiveHelp($_REQUEST);

        /** @noinspection PhpIncludeInspection */
        include MM_WPFS_Assets::templates('admin/wpfs-settings-security.php');
    }

    /**
     * @return stdClass
     */
    protected function getCustomerPortalData()
    {
        $result = new \stdClass;
        $options = $this->options->getSeveral([
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_SHOW_SUBSCRIPTIONS_TO_CUSTOMERS,
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_CANCEL_SUBSCRIPTIONS,
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_WHEN_CANCEL_SUBSCRIPTIONS,
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_UPDOWNGRADE_SUBSCRIPTIONS,
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_SHOW_INVOICES_SECTION,
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_SCROLLING_PANE_INTO_VIEW
        ]);

        $result->showSubscriptions = $options[MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_SHOW_SUBSCRIPTIONS_TO_CUSTOMERS];
        $result->cancelSubscriptions = $options[MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_CANCEL_SUBSCRIPTIONS];
        $result->whenCancelSubscriptions = $options[MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_WHEN_CANCEL_SUBSCRIPTIONS];
        $result->updowngradeSubscriptions = $options[MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_UPDOWNGRADE_SUBSCRIPTIONS];
        $result->showInvoices = $options[MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_SHOW_INVOICES_SECTION];
        $result->scrollingPaneIntoView = $options[MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_SCROLLING_PANE_INTO_VIEW];

        return $result;
    }

    function fullstripe_settings_my_account()
    {
        if (!current_user_can($this->capability)) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        $pageTitle =
            /* translators: Page title of the "Customer portal settings" page in WordPress admin  */
            __('Customer portal settings', 'wp-full-stripe-admin');
        $stripeStatus = $this->getStripeStatus();
        $backLinkUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(self::SLUG_SETTINGS);
        $backLinkLabel =
            /* translators: Link label which takes back to the "Settings" page  */
            __('Back to Settings', 'wp-full-stripe-admin');
        $view = new MM_WPFS_Admin_CustomerPortalView();
        $myAccountData = $this->getCustomerPortalData();
        $help = $this->helpService->getContextSensitiveHelp($_REQUEST);

        /** @noinspection PhpIncludeInspection */
        include MM_WPFS_Assets::templates('admin/wpfs-settings-my-account.php');
    }

    /**
     * @return stdClass
     */
    protected function getWordpressDasboardData()
    {
        $result = new \stdClass;
        $options = $this->options->getSeveral([
            MM_WPFS_Options::OPTION_DECIMAL_SEPARATOR_SYMBOL,
            MM_WPFS_Options::OPTION_SHOW_CURRENCY_SYMBOL_INSTEAD_OF_CODE,
            MM_WPFS_Options::OPTION_SHOW_CURRENCY_SIGN_AT_FIRST_POSITION,
            MM_WPFS_Options::OPTION_PUT_WHITESPACE_BETWEEN_CURRENCY_AND_AMOUNT
        ]);

        $result->decimalSeparator = $options[MM_WPFS_Options::OPTION_DECIMAL_SEPARATOR_SYMBOL];
        $result->useSymbolNotCode = $options[MM_WPFS_Options::OPTION_SHOW_CURRENCY_SYMBOL_INSTEAD_OF_CODE];
        $result->currencySymbolAtFirstPosition = $options[MM_WPFS_Options::OPTION_SHOW_CURRENCY_SIGN_AT_FIRST_POSITION];
        $result->putSpaceBetweenSymbolAndAmount = $options[MM_WPFS_Options::OPTION_PUT_WHITESPACE_BETWEEN_CURRENCY_AND_AMOUNT];

        return $result;
    }

    function fullstripe_settings_wp_dashboard()
    {
        if (!current_user_can($this->capability)) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        $pageTitle =
            /* translators: Page title of the "WordPress dashboard settings" page in WordPress admin  */
            __('WordPress dashboard settings', 'wp-full-stripe-admin');
        $stripeStatus = $this->getStripeStatus();
        $backLinkUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(self::SLUG_SETTINGS);
        $backLinkLabel =
            /* translators: Link label which takes back to the "Settings" page  */
            __('Back to Settings', 'wp-full-stripe-admin');
        $view = new MM_WPFS_Admin_WordpressDashboardView();
        $wpDashboardData = $this->getWordpressDasboardData();
        $help = $this->helpService->getContextSensitiveHelp($_REQUEST);

        /** @noinspection PhpIncludeInspection */
        include MM_WPFS_Assets::templates('admin/wpfs-settings-wp-dashboard.php');
    }

    function fullstripe_settings_addons()
    {
        if (!current_user_can($this->capability)) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        $pageTitle =
            /* translators: Page title of the "Add-ons" settings page in WordPress admin  */
            __('Add-ons', 'wp-full-stripe-admin');
        $stripeStatus = $this->getStripeStatus();
        $backLinkUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(self::SLUG_SETTINGS);
        $pluginsUrl = MM_WPFS_Admin_Menu::getPluginsUrl();
        $addOnsExploreUrl = MM_WPFS_Utils::isFreemiusLicensing() ?
            MM_WPFS_Admin_Menu::getAdminUrlBySlug(self::SLUG_ADDONS) :
            MM_WPFS_Admin_Menu::ENVATO_ADDONS_URL;
        $backLinkLabel =
            /* translators: Link label which takes back to the "Settings" page  */
            __('Back to Settings', 'wp-full-stripe-admin');
        $addOnData = $this->getActivatedAddOns();
        $help = $this->helpService->getContextSensitiveHelp($_REQUEST);

        /** @noinspection PhpIncludeInspection */
        include MM_WPFS_Assets::templates('admin/wpfs-settings-addons.php');
    }

    protected function getLogsData()
    {
        $result = new \StdClass;
        $options = $this->options->getSeveral([
            MM_WPFS_Options::OPTION_LOG_LEVEL,
            MM_WPFS_Options::OPTION_LOG_TO_WEB_SERVER,
            MM_WPFS_Options::OPTION_CATCH_UNCAUGHT_ERRORS
        ]);
        $logentries = $this->db->getNumberOfLogEntries();
        $result->logEntryCount = $logentries->logCount;
        $result->logLevel = $options[MM_WPFS_Options::OPTION_LOG_LEVEL];
        $result->logToWebServer = $options[MM_WPFS_Options::OPTION_LOG_TO_WEB_SERVER];
        $result->catchUncaughtErrors = $options[MM_WPFS_Options::OPTION_CATCH_UNCAUGHT_ERRORS];

        return $result;
    }

    function fullstripe_settings_logs()
    {
        if (!current_user_can($this->capability)) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        $pageTitle =
            /* translators: Page title of the "Add-ons" settings page in WordPress admin  */
            __('Error logging', 'wp-full-stripe-admin');
        $stripeStatus = $this->getStripeStatus();
        $backLinkUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(self::SLUG_SETTINGS);
        $pluginsUrl = MM_WPFS_Admin_Menu::getPluginsUrl();
        $logDownloadUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlugAndParams(self::SLUG_SETTINGS, ['wpfp-download-log' => 1]);

        $backLinkLabel =
            /* translators: Link label which takes back to the "Settings" page  */
            __('Back to Settings', 'wp-full-stripe-admin');
        $help = $this->helpService->getContextSensitiveHelp($_REQUEST);

        $levelView = new MM_WPFS_Admin_LogLevel_View();
        $emptyView = new MM_WPFS_Admin_LogEmpty_View();

        $logData = $this->getLogsData();

        /** @noinspection PhpIncludeInspection */
        include MM_WPFS_Assets::templates('admin/wpfs-settings-logs.php');
    }

    /**
     * @return array
     */
    protected function getActivatedAddOns()
    {
        $result = [];

        try {
            $result = apply_filters(MM_WPFS::FILTER_NAME_GET_ADDONS, $result);
        } catch (Exception $ex) {
            $this->logger->error(__FUNCTION__, 'ERror while getting activated add-ons', $ex);
        }

        return $result;
    }

    /**
     * @return StdClass
     */
    protected function prepareSettingsData()
    {
        $result = new \StdClass;

        $result->isAddOnsAvailable = count($this->getActivatedAddOns()) > 0;

        return $result;
    }

    function fullstripe_settings()
    {
        if (!current_user_can($this->capability)) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        $pageTitle =
            /* translators: Page title of the "Settings" page in WordPress admin  */
            __('Settings', 'wp-full-stripe-admin');
        $stripeStatus = $this->getStripeStatus();
        $help = $this->helpService->getContextSensitiveHelp($_REQUEST);
        $data = $this->prepareSettingsData();

        /** @noinspection PhpIncludeInspection */
        include MM_WPFS_Assets::templates('admin/wpfs-settings.php');
    }

    /**
     * @return string
     */
    private function getStripeApiMode()
    {
        return $this->options->get(MM_WPFS_Options::OPTION_API_MODE);
    }

    /**
     * @param $timestamp
     *
     * @return string
     */
    private function formatTimestamp($timestamp)
    {
        return is_null($timestamp) ?
            /* translators: When was the last time this form was used? This is the label for 'never'  */
            __('Never', 'wp-full-stripe-admin') :
            MM_WPFS_Utils::formatTimestampWithWordpressDateTimeFormat(strtotime($timestamp));
    }

    /**
     * @return array
     */
    private function getForms()
    {
        $forms = $this->db->getAllForms();
        $stripeApiMode = $this->getStripeApiMode();

        for ($idx = 0; $idx < count($forms); $idx++) {
            $form = $forms[$idx];

            $form->editUrl = MM_WPFS_Utils::getFormEditUrl($form->id, $form->type, $form->layout);
            $form->stripeApiMode = $stripeApiMode;
            $form->shortCode = MM_WPFS_Shortcode::createShortCodeByForm($form);
            $form->lastUsedAt = $this->formatTimestamp($form->created);

            $forms[$idx] = $form;
        }

        return $forms;
    }

    /**
     * @param string $slug
     * @param array $params
     *
     * @return string
     */
    public static function getAdminUrlBySlugAndParams($slug, $params = array()): string
    {
        $allParams = array_merge(
            array(
                'page' => $slug
            ),
            $params
        );

        $url = add_query_arg(
            $allParams,
            admin_url("admin.php")
        );

        return $url;
    }

    /**
     * @param $slug
     *
     * @return string
     */
    public static function getAdminUrlBySlug($slug): string
    {
        return MM_WPFS_Admin_Menu::getAdminUrlBySlugAndParams($slug);
    }

    /**
     * @return string
     */
    public function getLiveAccountStatus()
    {
        $accountId = $this->options->get(MM_WPFS_Options::OPTION_LIVE_ACCOUNT_ID);
        if (isset($accountId)) {
            $account = $this->stripe->getLiveAccount($accountId);
            return $this->determineAccountStatus($account, 'live');
        } else {
            return null;
        }
    }

    /**
     * @return string
     */
    public function getTestAccountStatus()
    {
        $accountId = $this->options->get(MM_WPFS_Options::OPTION_TEST_ACCOUNT_ID);
        if (isset($accountId)) {
            $account = $this->stripe->getTestAccount($accountId);
            return $this->determineAccountStatus($account, 'test');
        } else {
            return null;
        }
    }

    public function determineAccountStatus($account, $accountMode)
    {
        $state = null;
        if (isset($account)) {
            if ($this->isRejected($account)) {
                $state = MM_WPFS_Options::OPTION_ACCOUNT_STATUS_REJECTED;
            } else if ($this->isRestricted($account)) {
                $state = MM_WPFS_Options::OPTION_ACCOUNT_STATUS_RESTRICTED;
            } else if ($this->isRestrictedSoon($account)) {
                $state = MM_WPFS_Options::OPTION_ACCOUNT_STATUS_RESTRICTED_SOON;
            } else if ($this->isPendingEnabled($account)) {
                $state = MM_WPFS_Options::OPTION_ACCOUNT_STATUS_PENDING_ENABLED;
            } else if ($this->isPendingDisabled($account)) {
                $state = MM_WPFS_Options::OPTION_ACCOUNT_STATUS_PENDING_DISABLED;
            } else if ($this->isEnabled($account)) {
                $state = MM_WPFS_Options::OPTION_ACCOUNT_STATUS_ENABLED;
            } else if ($this->isComplete($account)) {
                $state = MM_WPFS_Options::OPTION_ACCOUNT_STATUS_COMPLETE;
            } else {
                $state = MM_WPFS_Options::OPTION_ACCOUNT_STATUS_NULL;
            }
        }
        if ($accountMode === 'live') {
            $this->options->set(MM_WPFS_Options::OPTION_LIVE_ACCOUNT_STATUS, $state);
        } else {
            $this->options->set(MM_WPFS_Options::OPTION_TEST_ACCOUNT_STATUS, $state);
        }
        return $state;
    }

    /**
     * @return string
     */
    public static function getPluginsUrl(): string
    {
        return add_query_arg(
            [],
            admin_url("plugins.php")
        );
    }

    /**
     * @return bool
     */
    private function isRestricted($account): bool
    {

        return $account->payouts_enabled === false;
    }

    /**
     * @return bool
     */
    private function isRejectedReason($reason): bool
    {
        return $reason && str_starts_with($reason, 'rejected');
    }

    /**
     * @return bool
     */
    private function isRestrictedSoon($account): bool
    {
        if (isset($account->requirements))
            return $account->requirements->current_deadline !== null;
        else
            return false;
    }

    /**
     * @return bool
     */
    private function isRejected($account): bool
    {
        if (isset($account->requirements))
            return $this->isRejectedReason($account->requirements->disabled_reason);
        else
            return false;
    }

    /**
     * @return bool
     */
    private function isComplete($account): bool
    {
        $eventuallyDue = [];
        if (isset($account->requirements) && isset($account->requirements->eventually_due)) {
            $eventuallyDue = $account->requirements->eventually_due;
        }
        return count($eventuallyDue) === 0;
    }

    /**
     * @return bool
     */
    private function isEnabled($account): bool
    {
        $eventuallyDue = [];
        if (isset($account->requirements) && isset($account->requirements->eventually_due)) {
            $eventuallyDue = $account->requirements->eventually_due;
        }
        $current_deadline = null;
        if (isset($account->requirements)) {
            $current_deadline = $account->requirements->current_deadline;
        }

        return (
            $account->payouts_enabled === true &&
            count($eventuallyDue) > 0 &&
            $current_deadline === null
        );
    }

    /**
     * @return bool
     */
    private function isPendingEnabled($account): bool
    {

        $pendingVerification = [];
        if (isset($account->requirements) && isset($account->requirements->pending_verification)) {
            $pendingVerification = $account->requirements->pending_verification;
        }
        $disabledReason = null;
        if (isset($account->requirements)) {
            $disabledReason = $account->requirements->disabled_reason;
        }

        return (
            count($pendingVerification) > 0 &&
            $disabledReason === null
        );
    }

    /**
     * @return bool
     */
    private function isPendingDisabled($account): bool
    {
        $pendingVerification = [];
        if (isset($account->requirements) && isset($account->requirements->pending_verification)) {
            $pendingVerification = $account->requirements->pending_verification;
        }

        $disabledReason = null;
        if (isset($account->requirements)) {
            $disabledReason = $account->requirements->disabled_reason;
        }
        return (
            count($pendingVerification) > 0 &&
            $disabledReason !== null
        );

    }

    /**
     * @param $allForms
     * @param $textFilter
     * @param $modeFilter
     *
     * @return array
     */
    private function filterFormList($allForms, $textFilter, $modeFilter)
    {
        $forms = array();

        if (empty($textFilter) && $modeFilter === self::PARAM_VALUE_API_MODE_ALL) {
            $forms = $allForms;
        } else {
            $forms = array_filter($allForms, function ($form, $key) use ($textFilter, $modeFilter) {
                $match = true;

                if (!empty($textFilter)) {
                    $lowerCaseTextFilter = strtolower($textFilter);

                    if (
                        strpos(strtolower($form->displayName), $lowerCaseTextFilter) === false &&
                        strpos(strtolower($form->name), $lowerCaseTextFilter) === false
                    ) {
                        $match = false;
                    }
                }

                if ($modeFilter !== self::PARAM_VALUE_API_MODE_ALL) {
                    if ($form->stripeApiMode != $modeFilter) {
                        $match = false;
                    }
                }

                return $match;
            }, ARRAY_FILTER_USE_BOTH);
        }

        return $forms;
    }

    /**
     * @param $tab
     *
     * @return string
     */
    private function validateTabId($tabs, $tab, $defaultTab): string
    {
        return key_exists($tab, $tabs) !== false ? $tab : $defaultTab;
    }


    /**
     * @return string|null
     */
    protected function getTabIdFromCookie()
    {
        return array_key_exists(self::COOKIE_NAME_TAB_ID, $_COOKIE) ? $_COOKIE[self::COOKIE_NAME_TAB_ID] : null;
    }

    /**
     * @param $lookupTabs array
     * @return string
     */
    private function getTransactionTabIdFromRequest(&$lookupTabs): string
    {
        $tabId = array_key_exists(MM_WPFS_Admin_Menu::PARAM_NAME_TAB, $_REQUEST) ? $_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_TAB] : null;
        if (is_null($tabId)) {
            $tabId = $this->getTabIdFromCookie();
        }
        $tabId = $this->validateTabId($lookupTabs, $tabId, MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_PAYMENTS);

        return $tabId;
    }

    /**
     * @return array[]
     */
    private function prepareTransactionLookupTabIds()
    {
        $lookupTabs = array(
            MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_PAYMENTS => array(
                'title' =>
                    /* translators: "One-time payments" tab of the "Transactions" page  */
                    __('One-time payments', 'wp-full-stripe-admin'),
                'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_PAYMENTS
            ),
            MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_SUBSCRIPTIONS => array(
                'title' =>
                    /* translators: "Subscriptions" tab of the "Transactions" page  */
                    __('Subscriptions', 'wp-full-stripe-admin'),
                'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_SUBSCRIPTIONS
            ),
            MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_DONATIONS => array(
                'title' =>
                    /* translators: "Donations" tab of the "Transactions" page  */
                    __('Donations', 'wp-full-stripe-admin'),
                'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_DONATIONS
            ),
            MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_SAVED_CARDS => array(
                'title' =>
                    /* translators: "Saved cards" tab of the "Transactions" page  */
                    __('Saved cards', 'wp-full-stripe-admin'),
                'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_SAVED_CARDS
            )
        );

        return $lookupTabs;
    }

    /**
     * @param $tabId
     *
     * @return array
     */
    private function prepareTransactionData($tabId): array
    {
        $transactionCount = 0;
        $formCount = 0;
        $transactionTable = null;

        switch ($tabId) {
            case MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_PAYMENTS: {
                    $transactionCount = ($this->db->getNumberOfOneTimePayments())->paymentCount;
                    $formCount = ($this->db->getNumberOfOneTimePaymentForms())->formCount;

                    if ($transactionCount > 0) {
                        $transactionTable = new WPFS_OneTimePayments_Table($this->loggerService);
                        $transactionTable->prepare_items();
                    }

                    break;
                }

            case MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_SUBSCRIPTIONS: {
                    $transactionCount = ($this->db->getNumberOfSubscriptions())->subscriptionCount;
                    $formCount = ($this->db->getNumberOfSubscriptionForms())->formCount;

                    if ($transactionCount > 0) {
                        $transactionTable = new WPFS_Subscriptions_Table($this->loggerService);
                        $transactionTable->prepare_items();
                    }

                    break;
                }

            case MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_DONATIONS: {
                    $transactionCount = ($this->db->getNumberOfDonations())->donationCount;
                    $formCount = ($this->db->getNumberOfDonationForms())->formCount;

                    if ($transactionCount > 0) {
                        $transactionTable = new WPFS_Donations_Table($this->loggerService);
                        $transactionTable->prepare_items();
                    }

                    break;
                }

            case MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_SAVED_CARDS: {
                    $transactionCount = ($this->db->getNumberOfSavedCards())->savedCardCount;
                    $formCount = ($this->db->getNumberOfSaveCardForms())->formCount;

                    if ($transactionCount > 0) {
                        $transactionTable = new WPFS_SavedCards_Table($this->loggerService);
                        $transactionTable->prepare_items();
                    }

                    break;
                }
        }

        return array($transactionCount, $formCount, $transactionTable);
    }

    function fullstripe_transactions()
    {
        if (!current_user_can($this->capability)) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        $lookupTabs = $this->prepareTransactionLookupTabIds();
        $tabId = $this->getTransactionTabIdFromRequest($lookupTabs);

        $pageTitle =
            /* translators: Page title of the "Transactions" page  */
            __('Transactions', 'wp-full-stripe-admin');
        $stripeStatus = $this->getStripeStatus();
        $pageSlug = MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS;
        $createButtonLabel =
            /* translators: Button label of the "Add form" button  */
            __('Add form', 'wp-full-stripe-admin');
        $help = $this->helpService->getContextSensitiveHelp($_REQUEST);

        $tabs = array();
        array_push($tabs, $lookupTabs[MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_PAYMENTS]);
        array_push($tabs, $lookupTabs[MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_SUBSCRIPTIONS]);
        array_push($tabs, $lookupTabs[MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_DONATIONS]);
        array_push($tabs, $lookupTabs[MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_SAVED_CARDS]);

        list($transactionCount, $formCount, $transactionTable) = $this->prepareTransactionData($tabId);

        include MM_WPFS_Assets::templates('admin/wpfs-transactions.php');
    }

    /**
     * @return array[]
     */
    private function prepareEmailSettingsLookupTabIds()
    {
        $lookupTabs = array(
            MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS => array(
                'title' =>
                    /* translators: The "Options" tab of the "Email settings" page  */
                    __('Options', 'wp-full-stripe-admin'),
                'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS
            ),
            MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_TEMPLATES => array(
                'title' =>
                    /* translators: The "Templates" tab of the "Email settings" page  */
                    __('Templates', 'wp-full-stripe-admin'),
                'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_TEMPLATES
            )
        );

        return $lookupTabs;
    }

    /**
     * @param $lookupTabs array
     * @return string
     */
    private function getEmailSettingsTabIdFromRequest(&$lookupTabs): string
    {
        $tabId = array_key_exists(MM_WPFS_Admin_Menu::PARAM_NAME_TAB, $_REQUEST) ? $_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_TAB] : null;
        $tabId = $this->validateTabId($lookupTabs, $tabId, MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS);

        return $tabId;
    }

    protected function getEmailTemplatesData()
    {
        $emailTemplatesData = new \stdClass;
        $emailTemplates = array();

        $template = new \stdClass;
        $template->id = MM_WPFS::EMAIL_TEMPLATE_ID_PAYMENT_RECEIPT;
        $template->caption =
            /* translators: Name of the one-time payment receipt email  */
            __('Payment receipt', 'wp-full-stripe-admin');
        array_push($emailTemplates, $template);

        $template = new \stdClass;
        $template->id = MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_RECEIPT;
        $template->caption =
            /* translators: Name of the subscription receipt email */
            __('Subscription receipt', 'wp-full-stripe-admin');
        array_push($emailTemplates, $template);

        $template = new \stdClass;
        $template->id = MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_ENDED;
        $template->caption =
            /* translators: Name of the email notification sent when a payment-in-installments subscription is ended  */
            __('Subscription ended', 'wp-full-stripe-admin');
        array_push($emailTemplates, $template);

        $template = new \stdClass;
        $template->id = MM_WPFS::EMAIL_TEMPLATE_ID_DONATION_RECEIPT;
        $template->caption =
            /* translators: Name of the donation receipt email */
            __('Donation receipt', 'wp-full-stripe-admin');
        array_push($emailTemplates, $template);

        $template = new \stdClass;
        $template->id = MM_WPFS::EMAIL_TEMPLATE_ID_CARD_SAVED;
        $template->caption =
            /* translators: Name of the email which is sent when customer information together with payment details are saved in Stripe */
            __('Card saved', 'wp-full-stripe-admin');
        array_push($emailTemplates, $template);

        $template = new \stdClass;
        $template->id = MM_WPFS::EMAIL_TEMPLATE_ID_CUSTOMER_PORTAL_SECURITY_CODE;
        $template->caption =
            /* translators: Name of the email which is sent when customer initiates a login process to the Customer portal */
            __('Customer portal login code', 'wp-full-stripe-admin');
        array_push($emailTemplates, $template);

        $emailTemplatesData->templates = apply_filters('fullstripe_email_template_descriptions', $emailTemplates);

        return $emailTemplatesData;
    }

    /**
     * @return stdClass
     */
    protected function getEmailOptionsData()
    {
        $emailOptionsData = new \stdClass;
        $options = $this->options->getSeveral([
            MM_WPFS_Options::OPTION_EMAIL_NOTIFICATION_SENDER_ADDRESS,
            MM_WPFS_Options::OPTION_EMAIL_NOTIFICATION_BCC_ADDRESSES
        ]);

        $emailOptionsData->adminName = get_bloginfo('name');
        $emailOptionsData->adminEmail = get_bloginfo('admin_email');

        $emailOptionsData->senderEmail = $options[MM_WPFS_Options::OPTION_EMAIL_NOTIFICATION_SENDER_ADDRESS];
        $emailOptionsData->bccEmails = json_decode($options[MM_WPFS_Options::OPTION_EMAIL_NOTIFICATION_BCC_ADDRESSES]);

        return $emailOptionsData;
    }

    function fullstripe_settings_email()
    {
        if (!current_user_can($this->capability)) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        $lookupTabs = $this->prepareEmailSettingsLookupTabIds();
        $tabId = $this->getEmailSettingsTabIdFromRequest($lookupTabs);

        $pageTitle =
            /* translators: Page title of the "Email notifications" page  */
            __('Email notifications', 'wp-full-stripe-admin');
        $pageSlug = MM_WPFS_Admin_Menu::SLUG_SETTINGS_EMAIL_NOTIFICATIONS;
        $backLinkUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(self::SLUG_SETTINGS);
        $backLinkLabel =
            /* translators: Link label which takes back to the "Settings" page  */
            __('Back to Settings', 'wp-full-stripe-admin');
        $stripeStatus = $this->getStripeStatus();
        $help = $this->helpService->getContextSensitiveHelp($_REQUEST);

        $tabs = array();
        array_push($tabs, $lookupTabs[MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS]);
        array_push($tabs, $lookupTabs[MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_TEMPLATES]);

        include MM_WPFS_Assets::templates('admin/wpfs-settings-email-notifications.php');
    }

    /**
     * @return array[]
     */
    private function prepareFormsSettingsLookupTabIds()
    {
        $lookupTabs = array(
            MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS => array(
                'title' =>
                    /* translators: "Options" tab of the "Form settings" page  */
                    __('Options', 'wp-full-stripe-admin'),
                'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS
            ),
            MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_APPEARANCE => array(
                'title' =>
                    /* translators: "Appearance" tab of the "Form settings" page  */
                    __('Appearance', 'wp-full-stripe-admin'),
                'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_APPEARANCE
            )
        );

        return $lookupTabs;
    }

    /**
     * @param $lookupTabs array
     * @return string
     */
    private function getFormsSettingsTabIdFromRequest(&$lookupTabs): string
    {
        $tabId = array_key_exists(MM_WPFS_Admin_Menu::PARAM_NAME_TAB, $_REQUEST) ? $_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_TAB] : null;
        $tabId = $this->validateTabId($lookupTabs, $tabId, MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS);

        return $tabId;
    }

    /**
     * @return stdClass
     */
    protected function getFormsOptionsData()
    {
        $result = new \stdClass;
        $options = $this->options->getSeveral([
            MM_WPFS_Options::OPTION_FILL_IN_EMAIL_FOR_LOGGED_IN_USERS,
            MM_WPFS_Options::OPTION_SET_FORM_FIELDS_VIA_URL_PARAMETERS
        ]);

        $result->fillInEmailForUsers = $options[MM_WPFS_Options::OPTION_FILL_IN_EMAIL_FOR_LOGGED_IN_USERS];
        $result->setFormFieldsViaUrlParameters = $options[MM_WPFS_Options::OPTION_SET_FORM_FIELDS_VIA_URL_PARAMETERS];

        return $result;
    }

    /**
     * @return stdClass
     */
    protected function getFormsAppearanceData()
    {
        $result = new \stdClass;
        $options = $this->options->getSeveral([
            MM_WPFS_Options::OPTION_FORM_CUSTOM_CSS
        ]);

        $result->customCss = $options[MM_WPFS_Options::OPTION_FORM_CUSTOM_CSS];

        return $result;
    }

    function fullstripe_settings_forms()
    {
        if (!current_user_can($this->capability)) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        $lookupTabs = $this->prepareFormsSettingsLookupTabIds();
        $tabId = $this->getFormsSettingsTabIdFromRequest($lookupTabs);

        $pageTitle =
            /* translators: Page title of the "Form settings" page */
            __('Forms', 'wp-full-stripe-admin');
        $pageSlug = MM_WPFS_Admin_Menu::SLUG_SETTINGS_FORMS;
        $backLinkUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(self::SLUG_SETTINGS);
        $backLinkLabel =
            /* translators: Link label which takes back to the "Settings" page  */
            __('Back to Settings', 'wp-full-stripe-admin');
        $stripeStatus = $this->getStripeStatus();
        $help = $this->helpService->getContextSensitiveHelp($_REQUEST);

        $tabs = array();
        array_push($tabs, $lookupTabs[MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS]);
        array_push($tabs, $lookupTabs[MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_APPEARANCE]);

        include MM_WPFS_Assets::templates('admin/wpfs-settings-forms.php');
    }

    function fullstripe_forms()
    {
        if (!current_user_can($this->capability)) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        $textFilter = array_key_exists(self::PARAM_NAME_FORM_TEXT_FILTER, $_POST) ? $_POST[self::PARAM_NAME_FORM_TEXT_FILTER] : null;
        $modeFilter = array_key_exists(self::PARAM_NAME_FORM_MODE_FILTER, $_POST) ? $_POST[self::PARAM_NAME_FORM_MODE_FILTER] : self::PARAM_VALUE_API_MODE_ALL;

        $pageTitle =
            /* translators: Page title of the "Manage forms" page  */
            __('Manage forms', 'wp-full-stripe-admin');
        $stripeStatus = $this->getStripeStatus();
        $createButtonLabel =
            /* translators: Button label of the "Add form" button  */
            __('Add form', 'wp-full-stripe-admin');
        $createButtonUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(self::SLUG_CREATE_FORM);
        $pageUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(self::SLUG_FORMS);
        $allForms = $this->getForms();
        $help = $this->helpService->getContextSensitiveHelp($_REQUEST);

        if (count($allForms) > 0) {
            $forms = $this->filterFormList($allForms, $textFilter, $modeFilter);

            /** @noinspection PhpIncludeInspection */
            include MM_WPFS_Assets::templates('admin/wpfs-forms.php');
        } else {
            /** @noinspection PhpIncludeInspection */
            include MM_WPFS_Assets::templates('admin/wpfs-forms-empty.php');
        }
    }

    function fullstripe_create_form()
    {
        if (!current_user_can($this->capability)) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        $pageTitle =
            /* translators: Page title of the "Add new form" page  */
            __('Add new form', 'wp-full-stripe-admin');
        $stripeStatus = $this->getStripeStatus();
        $backLinkUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(self::SLUG_FORMS);
        $backLinkLabel =
            /* translators: Link label which takes back to the "Manage forms" page  */
            __('Back to Manage forms', 'wp-full-stripe-admin');
        $view = new MM_WPFS_Admin_CreateFormView($_GET);
        $help = $this->helpService->getContextSensitiveHelp($_REQUEST);

        /** @noinspection PhpIncludeInspection */
        include MM_WPFS_Assets::templates('admin/wpfs-create-form.php');
    }


    /**
     * @param $formType string
     * @return string
     */
    protected function getEditFormTitle($formType)
    {
        $title = '';

        if ($formType === MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD) {
            $title =
                /* translators: Page title of the "Edit inline save card form" page  */
                __('Inline save card form', 'wp-full-stripe-admin');
        } elseif ($formType === MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD) {
            $title =
                /* translators: Page title of the "Edit checkout save card form" page  */
                __('Checkout save card form', 'wp-full-stripe-admin');
        } elseif ($formType === MM_WPFS::FORM_TYPE_INLINE_DONATION) {
            $title =
                /* translators: Page title of the "Edit inline donation form" page  */
                __('Inline donation form', 'wp-full-stripe-admin');
        } elseif ($formType === MM_WPFS::FORM_TYPE_CHECKOUT_DONATION) {
            $title =
                /* translators: Page title of the "Edit checkout donation form" page  */
                __('Checkout donation form', 'wp-full-stripe-admin');
        } elseif ($formType === MM_WPFS::FORM_TYPE_INLINE_PAYMENT) {
            $title =
                /* translators: Page title of the "Edit inline one-time payment form" page  */
                __('Inline one-time payment form', 'wp-full-stripe-admin');
        } elseif ($formType === MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT) {
            $title =
                /* translators: Page title of the "Edit checkout one-time payment form" page  */
                __('Checkout one-time payment form', 'wp-full-stripe-admin');
        } elseif ($formType === MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION) {
            $title =
                /* translators: Page title of the "Edit inline subscription form" page  */
                __('Inline subscription form', 'wp-full-stripe-admin');
        } elseif ($formType === MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION) {
            $title =
                /* translators: Page title of the "Edit checkout subscription form" page  */
                __('Checkout subscription form', 'wp-full-stripe-admin');
        }

        return $title;
    }

    /**
     * @param $formType
     *
     * @return array[]
     */
    protected function prepareEmailTemplates($formType, $form)
    {
        $templates = MM_WPFS_Mailer::extractEmailTemplates($this->staticContext, $formType, $form->emailTemplates);
        $templateDescriptors = MM_WPFS_Mailer::getEmailTemplateDescriptors($formType);
        $templateResult = array();

        foreach ($templateDescriptors as $descriptor) {
            $result = $descriptor;

            $type = $descriptor->type;
            if (property_exists($templates, $type)) {
                $result->enabled = $templates->{$type}->enabled;
            } else {
                $result->enabled = false;
            }

            array_push($templateResult, $result);
        }

        return $templateResult;
    }

    /**
     * @param $form
     */
    protected function prepareInlineSaveCardFormData($form): \StdClass
    {
        $data = new \StdClass;

        $data->thankYouPages = MM_WPFS_ThankYou::getPagesAndPosts($form);
        $data->cssSelector = MM_WPFS_Utils::generateFormHash(MM_WPFS_Utils::getFormType($form), MM_WPFS_Utils::getFormId($form), $form->name);
        $data->cardFieldLanguages = MM_WPFS_Languages::getStripeElementsLanguages();
        $data->customFieldMaxCount = MM_WPFS::getCustomFieldMaxCount($this->staticContext);
        $data->customFieldLabels = MM_WPFS_Utils::decodeCustomFieldLabels($form->customInputs);
        $data->emailTemplates = $this->prepareEmailTemplates(MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD, $form);

        return $data;
    }

    /**
     * @param $form
     */
    protected function prepareCheckoutSaveCardFormData($form): \StdClass
    {
        $data = new \StdClass;

        $data->thankYouPages = MM_WPFS_ThankYou::getPagesAndPosts($form);
        $data->cssSelector = MM_WPFS_Utils::generateFormHash(MM_WPFS_Utils::getFormType($form), MM_WPFS_Utils::getFormId($form), $form->name);
        $data->checkoutFormLanguages = MM_WPFS_Languages::getCheckoutLanguages();
        $data->customFieldMaxCount = MM_WPFS::getCustomFieldMaxCount($this->staticContext);
        $data->customFieldLabels = MM_WPFS_Utils::decodeCustomFieldLabels($form->customInputs);
        $data->emailTemplates = $this->prepareEmailTemplates(MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD, $form);

        return $data;
    }

    /**
     * @param $form
     */
    protected function prepareInlineDonationFormData($form): \StdClass
    {
        $data = new \StdClass;

        $data->thankYouPages = MM_WPFS_ThankYou::getPagesAndPosts($form);
        $data->cssSelector = MM_WPFS_Utils::generateFormHash(MM_WPFS_Utils::getFormType($form), MM_WPFS_Utils::getFormId($form), $form->name);
        $data->cardFieldLanguages = MM_WPFS_Languages::getStripeElementsLanguages();
        $data->customFieldMaxCount = MM_WPFS::getCustomFieldMaxCount($this->staticContext);
        $data->customFieldLabels = MM_WPFS_Utils::decodeCustomFieldLabels($form->customInputs);
        $data->emailTemplates = $this->prepareEmailTemplates(MM_WPFS::FORM_TYPE_INLINE_DONATION, $form);
        $data->currencies = MM_WPFS_Currencies::getAvailableCurrencies();

        return $data;
    }

    protected function prepareCheckoutDonationFormData($form): \StdClass
    {
        $data = new \StdClass;

        $data->thankYouPages = MM_WPFS_ThankYou::getPagesAndPosts($form);
        $data->cssSelector = MM_WPFS_Utils::generateFormHash(MM_WPFS_Utils::getFormType($form), MM_WPFS_Utils::getFormId($form), $form->name);
        $data->checkoutFormLanguages = MM_WPFS_Languages::getCheckoutLanguages();
        $data->customFieldMaxCount = MM_WPFS::getCustomFieldMaxCount($this->staticContext);
        $data->customFieldLabels = MM_WPFS_Utils::decodeCustomFieldLabels($form->customInputs);
        $data->emailTemplates = $this->prepareEmailTemplates(MM_WPFS::FORM_TYPE_INLINE_DONATION, $form);
        $data->currencies = MM_WPFS_Currencies::getAvailableCurrencies();

        return $data;
    }

    /**
     * @param $form
     */
    protected function prepareInlinePaymentFormData($form): \StdClass
    {
        $data = new \StdClass;

        $data->thankYouPages = MM_WPFS_ThankYou::getPagesAndPosts($form);
        $data->cssSelector = MM_WPFS_Utils::generateFormHash(MM_WPFS_Utils::getFormType($form), MM_WPFS_Utils::getFormId($form), $form->name);
        $data->cardFieldLanguages = MM_WPFS_Languages::getStripeElementsLanguages();
        $data->customFieldMaxCount = MM_WPFS::getCustomFieldMaxCount($this->staticContext);
        $data->customFieldLabels = MM_WPFS_Utils::decodeCustomFieldLabels($form->customInputs);
        $data->emailTemplates = $this->prepareEmailTemplates(MM_WPFS::FORM_TYPE_INLINE_PAYMENT, $form);
        $data->currencies = MM_WPFS_Currencies::getAvailableCurrencies();
        $data->products = $this->prepareOnetimeProducts($form);
        $data->stripeApiModeInteger = MM_WPFS_Admin::getApiModeIntegerFromString($this->getStripeApiMode());
        $data->taxRates = $this->prepareTaxRates($form);

        return $data;
    }

    /**
     * @param $form
     */
    protected function prepareCheckoutPaymentFormData($form): \StdClass
    {
        $data = new \StdClass;

        $data->thankYouPages = MM_WPFS_ThankYou::getPagesAndPosts($form);
        $data->cssSelector = MM_WPFS_Utils::generateFormHash(MM_WPFS_Utils::getFormType($form), MM_WPFS_Utils::getFormId($form), $form->name);
        $data->checkoutFormLanguages = MM_WPFS_Languages::getCheckoutLanguages();
        $data->customFieldMaxCount = MM_WPFS::getCustomFieldMaxCount($this->staticContext);
        $data->customFieldLabels = MM_WPFS_Utils::decodeCustomFieldLabels($form->customInputs);
        $data->emailTemplates = $this->prepareEmailTemplates(MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT, $form);
        $data->currencies = MM_WPFS_Currencies::getAvailableCurrencies();
        $data->products = $this->prepareOnetimeProducts($form);
        $data->stripeApiModeInteger = MM_WPFS_Admin::getApiModeIntegerFromString($this->getStripeApiMode());
        $data->taxRates = $this->prepareTaxRates($form);

        return $data;
    }

    /**
     * @param $products array
     *
     * @return array
     */
    protected function createProductsLookup($products)
    {
        $productsLookup = array();

        foreach ($products as $product) {
            $productsLookup[$product->stripePriceId] = $product;
        }

        return $productsLookup;
    }

    /**
     * @param $form \StdClass
     * @return array
     */
    protected function prepareRecurringProducts($form)
    {
        $savedProducts = MM_WPFS_Utils::decodeJsonArray($form->decoratedPlans);
        $priceIds = MM_WPFS_Pricing::extractPriceIdsFromProductsStatic($savedProducts);
        $savedProductsLookup = $this->createProductsLookup($savedProducts);

        $products = array();
        $productsLookup = array();

        $stripePrices = array();
        try {
            $stripePrices = $this->stripe->getRecurringPrices();
        } catch (Exception $ex) {
            $this->logger->error(__FUNCTION__, 'Cannot retrieve Stripe recurring prices', $ex);
        }
        foreach ($stripePrices as $stripePrice) {
            if (array_search($stripePrice->id, $priceIds) !== false) {
                $mergedProduct = $savedProductsLookup[$stripePrice->id];

                $mergedProduct->name = $stripePrice->product->name;
                $mergedProduct->price = $stripePrice->unit_amount;
                $mergedProduct->currency = $stripePrice->currency;
                $mergedProduct->interval = $stripePrice->recurring->interval;
                $mergedProduct->intervalCount = $stripePrice->recurring->interval_count;

                $productsLookup[$stripePrice->id] = $mergedProduct;
            }
        }

        foreach ($priceIds as $priceId) {
            if (array_key_exists($priceId, $productsLookup)) {
                array_push($products, $productsLookup[$priceId]);
            }
        }

        return $products;
    }

    /**
     * @param $form
     */
    protected function prepareInlineSubscriptionFormData($form): \StdClass
    {
        $data = new \StdClass;

        $data->thankYouPages = MM_WPFS_ThankYou::getPagesAndPosts($form);
        $data->cssSelector = MM_WPFS_Utils::generateFormHash(MM_WPFS_Utils::getFormType($form), MM_WPFS_Utils::getFormId($form), $form->name);
        $data->cardFieldLanguages = MM_WPFS_Languages::getStripeElementsLanguages();
        $data->customFieldMaxCount = MM_WPFS::getCustomFieldMaxCount($this->staticContext);
        $data->customFieldLabels = MM_WPFS_Utils::decodeCustomFieldLabels($form->customInputs);
        $data->emailTemplates = $this->prepareEmailTemplates(MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION, $form);
        $data->plans = $this->prepareRecurringProducts($form);
        $data->stripeApiModeInteger = MM_WPFS_Admin::getApiModeIntegerFromString($this->getStripeApiMode());
        $data->taxRates = $this->prepareTaxRates($form);

        return $data;
    }

    /**
     * @param $form
     */
    protected function prepareCheckoutSubscriptionFormData($form): \StdClass
    {
        $data = new \StdClass;

        $data->thankYouPages = MM_WPFS_ThankYou::getPagesAndPosts($form);
        $data->cssSelector = MM_WPFS_Utils::generateFormHash(MM_WPFS_Utils::getFormType($form), MM_WPFS_Utils::getFormId($form), $form->name);
        $data->checkoutFormLanguages = MM_WPFS_Languages::getCheckoutLanguages();
        $data->customFieldMaxCount = MM_WPFS::getCustomFieldMaxCount($this->staticContext);
        $data->customFieldLabels = MM_WPFS_Utils::decodeCustomFieldLabels($form->customInputs);
        $data->emailTemplates = $this->prepareEmailTemplates(MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION, $form);
        $data->plans = $this->prepareRecurringProducts($form);
        $data->stripeApiModeInteger = MM_WPFS_Admin::getApiModeIntegerFromString($this->getStripeApiMode());
        $data->taxRates = $this->prepareTaxRates($form);

        return $data;
    }

    function fullstripe_edit_form()
    {
        if (!current_user_can($this->capability)) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        $formType = $_GET[MM_WPFS_Admin_Menu::PARAM_NAME_TYPE];
        $formId = $_GET[MM_WPFS_Admin_Menu::PARAM_NAME_FORM];

        $pageTitle = $this->getEditFormTitle($formType);
        $tabs = MM_WPFS_FormTabCreatorFactory::createFormTabCreator($formType)->getTabs();
        $form = MM_WPFS_FormLoaderFactory::createFormLoader($formType, $formId)->getForm();
        $pageSlug = MM_WPFS_Admin_Menu::SLUG_EDIT_FORM;
        $tabId = $tabs[0]['tab'];

        $stripeStatus = $this->getStripeStatus();
        $help = $this->helpService->getContextSensitiveHelp($_REQUEST);
        $backLinkUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(self::SLUG_FORMS);
        $backLinkLabel =
            /* translators: Link label which takes back to the "Manage forms" page  */
            __('Back to Manage forms', 'wp-full-stripe-admin');
        if ($formType === MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD) {
            $view = new MM_WPFS_Admin_InlineSaveCardFormView();
            $data = $this->prepareInlineSaveCardFormData($form);
        } elseif ($formType === MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD) {
            $view = new MM_WPFS_Admin_CheckoutSaveCardFormView();
            $data = $this->prepareCheckoutSaveCardFormData($form);
        }
        if ($formType === MM_WPFS::FORM_TYPE_INLINE_DONATION) {
            $view = new MM_WPFS_Admin_InlineDonationFormView();
            $data = $this->prepareInlineDonationFormData($form);
            $submitButtonDescription =
                /* translators: Helper label describing how the {{amount}} placeholder token can be used for donation button labels */
                __('Insert {{amount}} to display the donation amount on the button', 'wp-full-stripe-admin');
        }
        if ($formType === MM_WPFS::FORM_TYPE_CHECKOUT_DONATION) {
            $view = new MM_WPFS_Admin_CheckoutDonationFormView();
            $data = $this->prepareCheckoutDonationFormData($form);
            $openButtonDescription =
                /* translators: Helper label describing how the {{amount}} placeholder token can be used for donation button labels */
                __('Insert {{amount}} to display the donation amount on the button', 'wp-full-stripe-admin');
        }
        if ($formType === MM_WPFS::FORM_TYPE_INLINE_PAYMENT) {
            $view = new MM_WPFS_Admin_InlinePaymentFormView();
            $data = $this->prepareInlinePaymentFormData($form);
            $submitButtonDescription =
                /* translators: Helper label describing how the {{amount}} placeholder token can be used for payment button labels */
                __('Insert {{amount}} to display the payment amount on the button', 'wp-full-stripe-admin');
        }
        if ($formType === MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT) {
            $view = new MM_WPFS_Admin_CheckoutPaymentFormView();
            $data = $this->prepareCheckoutPaymentFormData($form);
            $openButtonDescription =
                /* translators: Helper label describing how the {{amount}} placeholder token can be used for donation button labels */
                __('Insert {{amount}} to display the payment amount on the button', 'wp-full-stripe-admin');
        }
        if ($formType === MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION) {
            $view = new MM_WPFS_Admin_InlineSubscriptionFormView();
            $data = $this->prepareInlineSubscriptionFormData($form);
        }
        if ($formType === MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION) {
            $view = new MM_WPFS_Admin_CheckoutSubscriptionFormView();
            $data = $this->prepareCheckoutSubscriptionFormData($form);
        }

        /** @noinspection PhpIncludeInspection */
        include MM_WPFS_Assets::templates('admin/wpfs-edit-form.php');
    }

    private function get_news_feed($force_reload = false)
    {

        $news_feed = get_transient('wpfs_news_feed');
        $news_feed_last_update = get_transient('wpfs_news_feed_last_update');

        $load_feed = false;
        if ($news_feed === false) {
            $load_feed = true;
        } elseif (is_array($news_feed) && count($news_feed) == 0) {
            $load_feed = true;
        }
        if ($news_feed_last_update === false) {
            $load_feed = true;
        }
        if (isset($news_feed_last_update)) {
            $current_time = time();
            $update_interval = self::UPDATE_INTERVAL_4_HOURS;
            if (isset($news_feed) && count($news_feed) == 0) {
                $update_interval = self::UPDATE_INTERVAL_30_MINUTES;
            }
            if ($current_time - $news_feed_last_update > $update_interval) {
                $load_feed = true;
            }
        }
        if ($load_feed || $force_reload) {
            $news_feed = $this->load_news_feed(MM_WPFS_NewsFeed::URL);
            set_transient('wpfs_news_feed', $news_feed);
            set_transient('wpfs_news_feed_last_update', time());
        }

        return $news_feed;
    }

    private function load_news_feed($news_feed_url, $max_feed_length = 10)
    {
        $news_feed = array();
        try {

            $response = wp_remote_get($news_feed_url);
            if (!is_wp_error($response)) {
                $response_body = wp_remote_retrieve_body($response);

                $parser = xml_parser_create_ns('UTF-8');
                xml_parse_into_struct($parser, $response_body, $values, $index);
                xml_parser_free($parser);

                $feed_entry = null;

                for ($i = 0; $i < count($values) && count($news_feed) < $max_feed_length; $i++) {
                    $value = $values[$i];
                    if ($value['tag'] == 'ITEM') {
                        if ($value['type'] == 'open') {
                            $feed_entry = array();
                        }
                        if ($value['type'] == 'close') {
                            array_push($news_feed, $feed_entry);
                            $feed_entry = null;
                        }
                    }
                    if ($value['tag'] == 'TITLE' && $value['type'] == 'complete') {
                        if (isset($feed_entry)) {
                            $feed_entry['title'] = $value['value'];
                        }
                    }
                    if ($value['tag'] == 'DESCRIPTION' && $value['type'] == 'complete') {
                        $feed_entry['description'] = $value['value'];
                    }
                    if ($value['tag'] == 'HTTP://PURL.ORG/RSS/1.0/MODULES/CONTENT/:ENCODED' && $value['type'] == 'complete') {
                        $feed_entry['content'] = $value['value'];
                    }
                    if ($value['tag'] == 'PUBDATE' && $value['type'] == 'complete') {
                        $feed_entry['published'] = $value['value'];
                    }
                    if ($value['tag'] == 'CATEGORY' && $value['type'] == 'complete') {
                        $feed_entry['category'] = $value['value'];
                    }
                    if ($value['tag'] == 'LINK' && $value['type'] == 'complete') {
                        $feed_entry['link'] = $value['value'];
                    }
                    if ($value['tag'] == 'COMMENTS' && $value['type'] == 'complete') {
                        $feed_entry['comments'] = $value['value'];
                    }
                }

            }
        } catch (Exception $e) {
            MM_WPFS_Utils::log(sprintf('Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString()));
        }

        return $news_feed;

    }

}

class MM_WPFS_FormLoaderFactory
{
    public static function createFormLoader($formType, $id)
    {
        switch ($formType) {
            case MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD:
                return new MM_WPFS_InlineSaveCardFormLoader($id);

            case MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD:
                return new MM_WPFS_CheckoutSaveCardFormLoader($id);

            case MM_WPFS::FORM_TYPE_INLINE_DONATION:
                return new MM_WPFS_InlineDonationFormLoader($id);

            case MM_WPFS::FORM_TYPE_CHECKOUT_DONATION:
                return new MM_WPFS_CheckoutDonationFormLoader($id);

            case MM_WPFS::FORM_TYPE_INLINE_PAYMENT:
                return new MM_WPFS_InlinePaymentFormLoader($id);

            case MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT:
                return new MM_WPFS_CheckoutPaymentFormLoader($id);

            case MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION:
                return new MM_WPFS_InlineSubscriptionFormLoader($id);

            case MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION:
                return new MM_WPFS_CheckoutSubscriptionFormLoader($id);

            default:
                throw new Exception(__CLASS__ . '.' . __FUNCTION__ . "(): unsupported form type: " . $formType);
        }
    }
}

abstract class MM_WPFS_FormLoader
{
    protected $id;
    protected $db;

    public function __construct($id)
    {
        $this->id = $id;
        $this->db = new MM_WPFS_Database();
    }

    abstract public function getForm();
}

class MM_WPFS_InlineSaveCardFormLoader extends MM_WPFS_FormLoader
{
    public function getForm()
    {
        return $this->db->getInlinePaymentFormById($this->id);
    }
}

class MM_WPFS_CheckoutSaveCardFormLoader extends MM_WPFS_FormLoader
{
    public function getForm()
    {
        return $this->db->getCheckoutPaymentFormById($this->id);
    }
}

class MM_WPFS_InlineDonationFormLoader extends MM_WPFS_FormLoader
{
    public function getForm()
    {
        return $this->db->getInlineDonationFormById($this->id);
    }
}

class MM_WPFS_CheckoutDonationFormLoader extends MM_WPFS_FormLoader
{
    public function getForm()
    {
        return $this->db->getCheckoutDonationFormById($this->id);
    }
}

class MM_WPFS_InlinePaymentFormLoader extends MM_WPFS_FormLoader
{
    public function getForm()
    {
        return $this->db->getInlinePaymentFormById($this->id);
    }
}

class MM_WPFS_CheckoutPaymentFormLoader extends MM_WPFS_FormLoader
{
    public function getForm()
    {
        return $this->db->getCheckoutPaymentFormById($this->id);
    }
}

class MM_WPFS_InlineSubscriptionFormLoader extends MM_WPFS_FormLoader
{
    public function getForm()
    {
        return $this->db->getInlineSubscriptionFormById($this->id);
    }
}

class MM_WPFS_CheckoutSubscriptionFormLoader extends MM_WPFS_FormLoader
{
    public function getForm()
    {
        return $this->db->getCheckoutSubscriptionFormById($this->id);
    }
}

class MM_WPFS_FormTabCreatorFactory
{
    public static function createFormTabCreator($formType)
    {
        switch ($formType) {
            case MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD:
                return new MM_WPFS_InlineSaveCardFormTabCreator();

            case MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD:
                return new MM_WPFS_CheckoutSaveCardFormTabCreator();

            case MM_WPFS::FORM_TYPE_INLINE_DONATION:
                return new MM_WPFS_InlineDonationFormTabCreator();

            case MM_WPFS::FORM_TYPE_CHECKOUT_DONATION:
                return new MM_WPFS_CheckoutDonationFormTabCreator();

            case MM_WPFS::FORM_TYPE_INLINE_PAYMENT:
                return new MM_WPFS_InlinePaymentFormTabCreator();

            case MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT:
                return new MM_WPFS_CheckoutPaymentFormTabCreator();

            case MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION:
                return new MM_WPFS_InlineSubscriptionFormTabCreator();

            case MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION:
                return new MM_WPFS_CheckoutSubscriptionFormTabCreator();

            default:
                throw new Exception(__CLASS__ . '.' . __FUNCTION__ . "(): unsupported form type: " . $formType);
        }
    }
}

abstract class MM_WPFS_FormTabCreator
{
    abstract public function getTabs(): array;

    protected function generalTab(&$tabs)
    {
        array_push($tabs, array(
            'title' =>
                /* translators: Label of the "General" tab on "Edit form" pages */
                __('General', 'wp-full-stripe-admin'),
            'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_GENERAL
        )
        );
    }

    protected function paymentTab(&$tabs)
    {
        array_push($tabs, array(
            'title' =>
                /* translators: Label of the "Payment" tab on "Edit form" pages */
                __('Payment', 'wp-full-stripe-admin'),
            'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_PAYMENT
        )
        );
    }

    protected function appearanceTab(&$tabs)
    {
        array_push($tabs, array(
            'title' =>
                /* translators: Label of the "Appearance" tab on "Edit form" pages */
                __('Appearance', 'wp-full-stripe-admin'),
            'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_APPEARANCE
        )
        );
    }

    protected function formFieldsTab(&$tabs)
    {
        array_push($tabs, array(
            'title' =>
                /* translators: Label of the "Form fields" tab on "Edit form" pages */
                __('Form fields', 'wp-full-stripe-admin'),
            'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_FORM_LAYOUT
        )
        );
    }

    protected function emailNotificationsTab(&$tabs)
    {
        array_push($tabs, array(
            'title' =>
                /* translators: Label of the "Email notifications" tab on "Edit form" pages */
                __('Email notifications', 'wp-full-stripe-admin'),
            'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_EMAIL_NOTIFICATIONS
        )
        );
    }
}

class MM_WPFS_InlineSaveCardFormTabCreator extends MM_WPFS_FormTabCreator
{
    public function getTabs(): array
    {
        $tabs = array();

        $this->generalTab($tabs);
        $this->paymentTab($tabs);
        $this->appearanceTab($tabs);
        $this->formFieldsTab($tabs);
        $this->emailNotificationsTab($tabs);

        return $tabs;
    }
}

class MM_WPFS_CheckoutSaveCardFormTabCreator extends MM_WPFS_FormTabCreator
{
    public function getTabs(): array
    {
        $tabs = array();

        $this->generalTab($tabs);
        $this->paymentTab($tabs);
        $this->appearanceTab($tabs);
        $this->formFieldsTab($tabs);
        $this->emailNotificationsTab($tabs);

        return $tabs;
    }
}

class MM_WPFS_InlineDonationFormTabCreator extends MM_WPFS_FormTabCreator
{
    public function getTabs(): array
    {
        $tabs = array();

        $this->generalTab($tabs);
        $this->paymentTab($tabs);
        $this->appearanceTab($tabs);
        $this->formFieldsTab($tabs);
        $this->emailNotificationsTab($tabs);

        return $tabs;
    }
}

class MM_WPFS_CheckoutDonationFormTabCreator extends MM_WPFS_FormTabCreator
{
    public function getTabs(): array
    {
        $tabs = array();

        $this->generalTab($tabs);
        $this->paymentTab($tabs);
        $this->appearanceTab($tabs);
        $this->formFieldsTab($tabs);
        $this->emailNotificationsTab($tabs);

        return $tabs;
    }
}

trait MM_WPFS_TaxAwareFormTabCreator
{
    protected function taxTab(&$tabs)
    {
        array_push($tabs, array(
            'title' =>
                /* translators: Label of the "Tax" tab on "Edit form" pages */
                __('Tax', 'wp-full-stripe-admin'),
            'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_TAX
        )
        );
    }
}

class MM_WPFS_InlinePaymentFormTabCreator extends MM_WPFS_FormTabCreator
{
    use MM_WPFS_TaxAwareFormTabCreator;

    public function getTabs(): array
    {
        $tabs = array();

        $this->generalTab($tabs);
        $this->paymentTab($tabs);
        $this->taxTab($tabs);
        $this->appearanceTab($tabs);
        $this->formFieldsTab($tabs);
        $this->emailNotificationsTab($tabs);

        return $tabs;
    }
}

class MM_WPFS_CheckoutPaymentFormTabCreator extends MM_WPFS_FormTabCreator
{
    use MM_WPFS_TaxAwareFormTabCreator;

    public function getTabs(): array
    {
        $tabs = array();

        $this->generalTab($tabs);
        $this->paymentTab($tabs);
        $this->taxTab($tabs);
        $this->appearanceTab($tabs);
        $this->formFieldsTab($tabs);
        $this->emailNotificationsTab($tabs);

        return $tabs;
    }
}

class MM_WPFS_InlineSubscriptionFormTabCreator extends MM_WPFS_FormTabCreator
{
    use MM_WPFS_TaxAwareFormTabCreator;

    public function getTabs(): array
    {
        $tabs = array();

        $this->generalTab($tabs);
        $this->paymentTab($tabs);
        $this->taxTab($tabs);
        $this->appearanceTab($tabs);
        $this->formFieldsTab($tabs);
        $this->emailNotificationsTab($tabs);

        return $tabs;
    }
}

class MM_WPFS_CheckoutSubscriptionFormTabCreator extends MM_WPFS_FormTabCreator
{
    use MM_WPFS_TaxAwareFormTabCreator;

    public function getTabs(): array
    {
        $tabs = array();

        $this->generalTab($tabs);
        $this->paymentTab($tabs);
        $this->taxTab($tabs);
        $this->appearanceTab($tabs);
        $this->formFieldsTab($tabs);
        $this->emailNotificationsTab($tabs);

        return $tabs;
    }
}


class MM_WPFS_AdminScriptLocalizerFactory
{

    /**
     * @param $options
     * @param $page
     * @param $tab
     * @return MM_WPFS_AdminScriptLocalizer
     * @throws WPFS_UserFriendlyException
     */
    public static function createLocalizer($options, $page, $tab): MM_WPFS_AdminScriptLocalizer
    {
        switch ($page) {
            case MM_WPFS_Admin_Menu::SLUG_SETTINGS_STRIPE:
                return new MM_WPFS_SettingsStripeLocalizer($options, $tab);

            case MM_WPFS_Admin_Menu::SLUG_SETTINGS_FORMS:
                return new MM_WPFS_SettingsFormsLocalizer($options, $tab);

            case MM_WPFS_Admin_Menu::SLUG_SETTINGS_CUSTOMER_PORTAL:
                return new MM_WPFS_SettingsCustomerPortalLocalizer($options, $tab);

            case MM_WPFS_Admin_Menu::SLUG_SETTINGS_SECURITY:
                return new MM_WPFS_SettingsSecurityLocalizer($options, $tab);

            case MM_WPFS_Admin_Menu::SLUG_SETTINGS_EMAIL_NOTIFICATIONS:
                return new MM_WPFS_SettingsEmailNotificationsLocalizer($options, $tab);

            case MM_WPFS_Admin_Menu::SLUG_SETTINGS_LOGS:
                return new MM_WPFS_SettingsLogsLocalizer($options, $tab);

            case MM_WPFS_Admin_Menu::SLUG_CREATE_FORM:
                return new MM_WPFS_CreateFormLocalizer($options, $tab);

            case MM_WPFS_Admin_Menu::SLUG_EDIT_FORM:
                return new MM_WPFS_EditFormLocalizer($options, $tab, $_GET);

            case MM_WPFS_Admin_Menu::SLUG_FORMS:
                return new MM_WPFS_FormsLocalizer($options, $tab);

            case MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS:
                return new MM_WPFS_TransactionsLocalizer($options, $tab);

            default:
                return new MM_WPFS_DefaultLocalizer($options, $tab);
        }
    }
}

abstract class MM_WPFS_AdminScriptLocalizer
{
    /** @var MM_WPFS_Options */
    protected $options;
    protected $tab;
    protected $getParameters;

    public function __construct($options, $tab = null, $getParameters = null)
    {
        $this->options = $options;
        $this->tab = $tab;
        $this->getParameters = $getParameters;
    }

    public function getSettingsOptions()
    {
        return array();
    }

    public function getScriptDependencies()
    {
        return array();
    }

    public function enqueueScripts()
    {
    }

    abstract public function localizeScripts();
}

class MM_WPFS_DefaultLocalizer extends MM_WPFS_AdminScriptLocalizer
{
    public function localizeScripts()
    {
        // This localizer is for admin pages where we don't want to localize any js script
    }
}


class MM_WPFS_SettingsStripeLocalizer extends MM_WPFS_AdminScriptLocalizer
{
    public function localizeScripts()
    {
        wp_localize_script('wp-full-stripe-admin-js', 'wpfsFormFields', array(
            MM_WPFS::FORM_TYPE_ADMIN_CONFIGURE_STRIPE_ACCOUNT => MM_WPFS_Admin_ConfigureStripeAccountView::getFields()
        )
        );
        wp_localize_script('wp-full-stripe-admin-js', 'wpfsAdminL10n', array(
            'webhookUrlCopiedMessage' =>
                /* translators: Banner message displayed when a webhook URL is copied to the clipboard */
                __('Webhook URL copied to clipboard.', 'wp-full-stripe-admin')
        )
        );
    }
}

class MM_WPFS_SettingsFormsLocalizer extends MM_WPFS_AdminScriptLocalizer
{
    public function localizeScripts()
    {
        if (
            $this->tab === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS ||
            $this->tab === null
        ) {
            wp_localize_script('wp-full-stripe-admin-js', 'wpfsFormFields', array(
                MM_WPFS::FORM_TYPE_ADMIN_CONFIGURE_FORMS_OPTIONS => MM_WPFS_Admin_FormsOptionsView::getFields()
            )
            );
        } elseif ($this->tab === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_APPEARANCE) {
            wp_localize_script('wp-full-stripe-admin-js', 'wpfsFormFields', array(
                MM_WPFS::FORM_TYPE_ADMIN_CONFIGURE_FORMS_APPEARANCE => MM_WPFS_Admin_FormsAppearanceView::getFields()
            )
            );
        }
    }

    public function getSettingsOptions()
    {
        $options = array();

        if ($this->tab === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_APPEARANCE) {
            $options['aceEditorPath'] = MM_WPFS_Assets::scripts('ace/');
        }

        return $options;
    }

    public function enqueueScripts()
    {
        if ($this->tab === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_APPEARANCE) {
            wp_enqueue_script('beautify-js-js', MM_WPFS_Assets::scripts('beautify/beautify.js'), null, '1.3.13');
            wp_enqueue_script('beautify-css-js', MM_WPFS_Assets::scripts('beautify/beautify-css.js'), null, '1.3.13');
            wp_enqueue_script('ace-js', MM_WPFS_Assets::scripts('ace/ace.js'), array(
                'beautify-js-js',
                'beautify-css-js',
            ), '1.4.12');
            wp_enqueue_script('ace-theme-js', MM_WPFS_Assets::scripts('ace/theme-solarized_dark.js'), array(
                'ace-js'
            ), '1.4.12');
        }
    }

    public function getScriptDependencies()
    {
        $dependencies = array();

        if ($this->tab === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_APPEARANCE) {
            $dependencies = array_merge($dependencies, array(
                'ace-js',
                'ace-theme-js'
            )
            );
        }

        return $dependencies;
    }
}

class MM_WPFS_SettingsCustomerPortalLocalizer extends MM_WPFS_AdminScriptLocalizer
{
    public function localizeScripts()
    {
        wp_localize_script('wp-full-stripe-admin-js', 'wpfsFormFields', array(
            MM_WPFS::FORM_TYPE_ADMIN_CONFIGURE_CUSTOMER_PORTAL => MM_WPFS_Admin_CustomerPortalView::getFields()
        )
        );
    }
}


class MM_WPFS_SettingsSecurityLocalizer extends MM_WPFS_AdminScriptLocalizer
{
    public function localizeScripts()
    {
        wp_localize_script('wp-full-stripe-admin-js', 'wpfsFormFields', array(
            MM_WPFS::FORM_TYPE_ADMIN_CONFIGURE_SECURITY => MM_WPFS_Admin_SecurityView::getFields()
        )
        );
    }
}

class MM_WPFS_SettingsEmailNotificationsLocalizer extends MM_WPFS_AdminScriptLocalizer
{
    public function localizeScripts()
    {
        if (
            $this->tab === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS ||
            $this->tab === null
        ) {
            wp_localize_script('wp-full-stripe-admin-js', 'wpfsFormFields', array(
                MM_WPFS::FORM_TYPE_ADMIN_CONFIGURE_EMAIL_OPTIONS => MM_WPFS_Admin_EmailOptionsView::getFields()
            )
            );
        } elseif ($this->tab === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_TEMPLATES) {
            wp_localize_script('wp-full-stripe-admin-js', 'wpfsFormFields', array(
                MM_WPFS::FORM_TYPE_ADMIN_CONFIGURE_EMAIL_TEMPLATES => MM_WPFS_Admin_EmailTemplatesView::getFields()
            )
            );
            wp_localize_script('wp-full-stripe-admin-js', 'wpfsAdminL10n', array(
                'resetTemplateTitle' =>
                    /* translators: Title of the 'Reset email template' dialog */
                    __('Reset email template', 'wp-full-stripe-admin'),
                'resetTemplateConfirmationMessage' =>
                    /* translators: Confirmation message displayed in the 'Reset email template' dialog */
                    __("Are you sure you'd like to reset the email template?", 'wp-full-stripe-admin'),
                'sendTestEmailTitle' =>
                    /* translators: Title of the 'Send test email' dialog */
                    __('Send test email', 'wp-full-stripe-admin'),
                'enterAtLeastOneEmailAddressMessage' =>
                    /* translators: Error message displayed when no email is entered in the 'Send test email' dialog */
                    __("Please enter at least one email address", 'wp-full-stripe-admin'),
                'emailAddressInvalidMessage' =>
                    /* translators: Error message displayed when an invalid email address is entered in the 'Send test email' dialog */
                    __("Email address %s is invalid", 'wp-full-stripe-admin'),
            )
            );
        }
    }

    public function getSettingsOptions()
    {
        $result = array();

        if ($this->tab === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_TEMPLATES) {
            $options = $this->options->getSeveral([
                MM_WPFS_Options::OPTION_EMAIL_TEMPLATES
            ]);

            // todo: refactor magic strings
            $result['emailTemplates'] = json_decode($options[MM_WPFS_Options::OPTION_EMAIL_TEMPLATES]);
            $result['defaultEmailTemplates'] = MM_WPFS_Mailer::getDefaultEmailTemplates();
            $result['macros'] = MM_WPFS_Admin_Menu::createMacroData();
        }

        return $result;
    }
}

class MM_WPFS_SettingsLogsLocalizer extends MM_WPFS_AdminScriptLocalizer
{
    public function localizeScripts()
    {
        wp_localize_script('wp-full-stripe-admin-js', 'wpfsFormFields', array(
            MM_WPFS::FORM_TYPE_ADMIN_CONFIGURE_LOGGING => MM_WPFS_Admin_LogLevel_View::getFields(),
            MM_WPFS::FORM_TYPE_ADMIN_EMPTY_LOG => MM_WPFS_Admin_LogEmpty_View::getFields()
        )
        );
        wp_localize_script('wp-full-stripe-admin-js', 'wpfsAdminL10n', array(
            'emptyLogTitle' =>
                /* translators: Dialog title of the 'Empty log' dialog */
                __('Empty log', 'wp-full-stripe-admin'),
            'emptyLogConfirmationMessage' =>
                /* translators: Confirmation message of the 'Empty log' dialog */
                __("Are you use you'd like to delete all log entries?", 'wp-full-stripe-admin'),
        )
        );
    }
}

class MM_WPFS_CreateFormLocalizer extends MM_WPFS_AdminScriptLocalizer
{
    public function localizeScripts()
    {
        wp_localize_script('wp-full-stripe-admin-js', 'wpfsFormFields', array(
            MM_WPFS::FORM_TYPE_ADMIN_CREATE_FORM => MM_WPFS_Admin_CreateFormView::getFields()
        )
        );
    }
}

class MM_WPFS_EditFormLocalizer extends MM_WPFS_AdminScriptLocalizer
{
    protected $formType;

    public function __construct($options, $tab = null, $getParameters = null)
    {
        parent::__construct($options, $tab, $getParameters);

        $this->getFormType($getParameters);
    }

    protected function getFormType($getParameters)
    {
        if (array_key_exists(MM_WPFS_Admin_Menu::PARAM_NAME_TYPE, $getParameters)) {
            $this->formType = $getParameters[MM_WPFS_Admin_Menu::PARAM_NAME_TYPE];
        } else {
            throw new Exception(__CLASS__ . '.' . __FUNCTION__ . "(): URL parameter 'type' not found.");
        }
    }

    public function localizeScripts()
    {
        $l10n = array(
            'formCssIdCopiedMessage' =>
                /* translators: Banner message displayed when a CSS selector is copied to the clipboard */
                __('Form CSS selector ID copied to clipboard', 'wp-full-stripe-admin'),
            'textFieldTypeLabel' =>
                /* translators: File type label of the text custom field type */
                __('Text field', 'wp-full-stripe-admin'),
            'fieldNameRequiredMessage' =>
                /* translators: Validation message displayed when the name of the custom field is not filled in */
                __('Please enter field name', 'wp-full-stripe-admin'),
            'fieldNameTooLongMessage' =>
                /* translators: Validation message displayed when the length of the custom field name is more than 40 characters */
                __('Maximum custom field length is 40 characters', 'wp-full-stripe-admin'),
            'deleteCustomFieldTitle' =>
                /* translators: Title of the 'Delete custom field' dialog */
                __('Delete custom field', 'wp-full-stripe-admin'),
            'deleteCustomFieldConfirmationMessage' =>
                /* translators: Confirmation message displayed in the  'Delete custom field' dialog
                 * p1: Name of the custom field
                 */
                __("Are you sure you'd like to delete custom field '%s' ?", 'wp-full-stripe-admin'),
            'addCustomFieldTitle' =>
                /* translators: Title of the 'Add custom field' dialog */
                __('Add custom field', 'wp-full-stripe-admin'),
            'recurringPriceWithSingularDayFormatter' =>
                /* translators: Daily recurring price label
                 * p1: Price
                 */
                __('%1$s / day', 'wp-full-stripe-admin'),
            'recurringPriceWithPluralDayFormatter' =>
                /* translators: Recurring price label for prices charged every x days
                 * p1: Price
                 * p2: Frequency in days
                 */
                __('%1$s / %2$d days', 'wp-full-stripe-admin'),
            'recurringPriceWithSingularWeekFormatter' =>
                /* translators: Weekly recurring price label
                 * p1: Price
                 */
                __('%1$s / week', 'wp-full-stripe-admin'),
            'recurringPriceWithPluralWeekFormatter' =>
                /* translators: Recurring price label for prices charged every x weeks
                 * p1: Price
                 * p2: Frequency in weeks
                 */
                __('%1$s / %2$d weeks', 'wp-full-stripe-admin'),
            'recurringPriceWithSingularMonthFormatter' =>
                /* translators: Monthly recurring price label
                 * p1: Price
                 */
                __('%1$s / month', 'wp-full-stripe-admin'),
            'recurringPriceWithPluralMonthFormatter' =>
                /* translators: Recurring price label for prices charged every x months
                 * p1: Price
                 * p2: Frequency in months
                 */
                __('%1$s / %2$d months', 'wp-full-stripe-admin'),
            'recurringPriceWithSingularYearFormatter' =>
                /* translators: Annual recurring price label
                 * p1: Price
                 */
                __('%1$s / year', 'wp-full-stripe-admin'),
            'recurringPriceWithPluralYearFormatter' =>
                /* translators: Recurring price label for prices charged every x years
                 * p1: Price
                 * p2: Frequency in years
                 */
                __('%1$s / %2$d years', 'wp-full-stripe-admin'),
            'standardPricingLabel' =>
                /* translators: Label for the pricing type 'Standard pricing' */
                __('Standard pricing', 'wp-full-stripe-admin'),
            'volumePricingLabel' =>
                /* translators: Label for the pricing type 'Volume pricing' */
                __('Volume pricing', 'wp-full-stripe-admin'),
            'graduatedPricingLabel' =>
                /* translators: Label for the pricing type 'Graduated pricing' */
                __('Graduated pricing', 'wp-full-stripe-admin'),
            'meteredBillingLabel' =>
                /* translators: Label for the pricing mode 'Metered billing' */
                __('Metered billing', 'wp-full-stripe-admin'),
            'setAdditionalPlanPropertiesLabel' =>
                /* translators: Title of the 'Add subscription plan properties' dialog box
                 * p1: Name of the subscription plan
                 */
                __('Set additional properties for <b>%s</b>', 'wp-full-stripe-admin'),
            'requiredFieldMessage' =>
                /* translators: Validation error message for required fields which are not filled in */
                __('This is a required field', 'wp-full-stripe-admin'),
            'invalidAmountMessage' =>
                /* translators: Validation error message for invalid payment amounts entered */
                __('Please enter a valid amount', 'wp-full-stripe-admin'),
            'enterWholeNumberMessage' =>
                /* translators: Validation error message displayed when the entered number is not a whole number */
                __('Please enter a whole number', 'wp-full-stripe-admin'),
            'enterPositiveNumberMessage' =>
                /* translators: Validation error message displayed when the entered number is not a positive number */
                __('Please enter a positive number', 'wp-full-stripe-admin'),
            'billingAnchorDayIntervalMessage' =>
                /* translators: Validation error message displayed when the entered number is not between 1 and 28 */
                __('Please enter number between 1 and 28', 'wp-full-stripe-admin'),
            'planAndSetupFeeLabel' =>
                /* translators: Pricing label of a subscription plan
                 * p1: Recurring amount
                 * p2: One-time setup fee
                 */
                __('%1$s + %2$s setup fee', 'wp-full-stripe-admin'),
            'trialDaysLabel' =>
                /* translators: Trial days label of a subscription plan
                 * p1: Number of trial days
                 */
                __('%s-day trial', 'wp-full-stripe-admin'),
            'runningUntilCanceledLabel' =>
                /* translators: Label describing subscriptions which are running till canceled */
                __('Running until canceled', 'wp-full-stripe-admin'),
            'canceledAfterXOccurrences' =>
                /* translators: Label describing subscriptions which are canceled after x charges
                 * p1: Number of recurring charges after which the subscription is canceled
                 */
                __('Canceled after %s occurences', 'wp-full-stripe-admin'),
            'internalError' =>
                /* translators: Error banner title for internal, unexpected errors/problems */
                __('Internal error', 'wp-full-stripe-admin'),
            'taxRateInclusiveDescription' =>
                /* translators: Description of an inclusive tax rate
                 * p1: Tax percentage
                 * p2: Tax label (VAT, GST, Sales tax, etc)
                 */
                __('%1$s%% %2$s (inclusive)', 'wp-full-stripe-admin'),
            'taxRateExclusiveDescription' =>
                /* translators: Description of an exclusive tax rate
                 * p1: Tax percentage
                 * p2: Tax label (VAT, GST, Sales tax, etc)
                 */
                __('%1$s%% %2$s (exclusive)', 'wp-full-stripe-admin'),
        );

        if ($this->formType === MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD) {
            wp_localize_script('wp-full-stripe-admin-js', 'wpfsFormFields', array(
                MM_WPFS::FORM_TYPE_ADMIN_INLINE_SAVE_CARD_FORM => MM_WPFS_Admin_InlineSaveCardFormView::getFields()
            )
            );
        } elseif ($this->formType === MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD) {
            wp_localize_script('wp-full-stripe-admin-js', 'wpfsFormFields', array(
                MM_WPFS::FORM_TYPE_ADMIN_CHECKOUT_SAVE_CARD_FORM => MM_WPFS_Admin_CheckoutSaveCardFormView::getFields()
            )
            );
        } elseif ($this->formType === MM_WPFS::FORM_TYPE_INLINE_DONATION) {
            wp_localize_script('wp-full-stripe-admin-js', 'wpfsFormFields', array(
                MM_WPFS::FORM_TYPE_ADMIN_INLINE_DONATION_FORM => MM_WPFS_Admin_InlineDonationFormView::getFields()
            )
            );

            $l10n['addSuggestedDonationAmountTitle'] =
                /* translators: Title of the 'Add suggested donation amount' dialog */
                __('Add suggested donation amount', 'wp-full-stripe-admin');
            $l10n['suggestedDonationAmountRequiredMessage'] =
                /* translators: Validation error message for an empty donation amount */
                __('Please enter a donation amount', 'wp-full-stripe-admin');
            $l10n['suggestedDonationAmountInvalidMessage'] =
                /* translators: Validation error message for an invalid donation amount entered */
                __('Please enter a valid amount', 'wp-full-stripe-admin');
            $l10n['suggestedDonationAmountNotWholeNumberMessage'] =
                /* translators: Validation error message for a donation amount which is not a whole number */
                __('Please enter a number without decimal separator', 'wp-full-stripe-admin');
            $l10n['deleteSuggestedDonationTitle'] =
                /* translators: Title of the 'Delete suggested donation amount' dialog */
                __('Delete suggested donation amount', 'wp-full-stripe-admin');
            $l10n['deleteSuggestedDonationAmountConfirmationMessage'] =
                /* translators: Confirmation message displayed before deleting a suggested donation amount
                 * p1: The donation amount to be deleted
                 */
                __("Are you sure you'd like to delete donation amount '%s' ?", 'wp-full-stripe-admin');
        } elseif ($this->formType === MM_WPFS::FORM_TYPE_CHECKOUT_DONATION) {
            wp_localize_script('wp-full-stripe-admin-js', 'wpfsFormFields', array(
                MM_WPFS::FORM_TYPE_ADMIN_CHECKOUT_DONATION_FORM => MM_WPFS_Admin_CheckoutDonationFormView::getFields()
            )
            );

            $l10n['addSuggestedDonationAmountTitle'] =
                /* translators: Title of the 'Add suggested donation amount' dialog */
                __('Add suggested donation amount', 'wp-full-stripe-admin');
            $l10n['suggestedDonationAmountRequiredMessage'] =
                /* translators: Validation error message for an empty donation amount */
                __('Please enter a donation amount', 'wp-full-stripe-admin');
            $l10n['suggestedDonationAmountInvalidMessage'] =
                /* translators: Validation error message for an invalid donation amount entered */
                __('Please enter a valid amount', 'wp-full-stripe-admin');
            $l10n['suggestedDonationAmountNotWholeNumberMessage'] =
                /* translators: Validation error message for a donation amount which is not a whole number */
                __('Please enter a number without decimal separator', 'wp-full-stripe-admin');
            $l10n['deleteSuggestedDonationTitle'] =
                /* translators: Title of the 'Delete suggested donation amount' dialog */
                __('Delete suggested donation amount', 'wp-full-stripe-admin');
            $l10n['deleteSuggestedDonationAmountConfirmationMessage'] =
                /* translators: Confirmation message displayed before deleting a suggested donation amount
                 * p1: The donation amount to be deleted
                 */
                __("Are you sure you'd like to delete donation amount '%s' ?", 'wp-full-stripe-admin');
        } elseif ($this->formType === MM_WPFS::FORM_TYPE_INLINE_PAYMENT) {
            wp_localize_script('wp-full-stripe-admin-js', 'wpfsFormFields', array(
                MM_WPFS::FORM_TYPE_ADMIN_INLINE_PAYMENT_FORM => MM_WPFS_Admin_InlinePaymentFormView::getFields()
            )
            );
        } elseif ($this->formType === MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT) {
            wp_localize_script('wp-full-stripe-admin-js', 'wpfsFormFields', array(
                MM_WPFS::FORM_TYPE_ADMIN_CHECKOUT_PAYMENT_FORM => MM_WPFS_Admin_CheckoutPaymentFormView::getFields()
            )
            );
        } elseif ($this->formType === MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION) {
            wp_localize_script('wp-full-stripe-admin-js', 'wpfsFormFields', array(
                MM_WPFS::FORM_TYPE_ADMIN_INLINE_SUBSCRIPTION_FORM => MM_WPFS_Admin_InlineSubscriptionFormView::getFields()
            )
            );
        } elseif ($this->formType === MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION) {
            wp_localize_script('wp-full-stripe-admin-js', 'wpfsFormFields', array(
                MM_WPFS::FORM_TYPE_ADMIN_CHECKOUT_SUBSCRIPTION_FORM => MM_WPFS_Admin_CheckoutSubscriptionFormView::getFields()
            )
            );
        }

        wp_localize_script('wp-full-stripe-admin-js', 'wpfsAdminL10n', $l10n);
    }

    public function getSettingsOptions()
    {
        $options = array();

        $options['macroDescriptions'] = MacroHelperTools::getMacroDescriptions();

        if (
            $this->formType === MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD ||
            $this->formType === MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD
        ) {
            $options['macroKeys'] = MM_WPFS_SaveCardMacroReplacer::getMacroKeys();
        } elseif (
            $this->formType === MM_WPFS::FORM_TYPE_INLINE_DONATION ||
            $this->formType === MM_WPFS::FORM_TYPE_CHECKOUT_DONATION
        ) {
            $options['macroKeys'] = MM_WPFS_DonationMacroReplacer::getMacroKeys();
        } elseif (
            $this->formType === MM_WPFS::FORM_TYPE_INLINE_PAYMENT ||
            $this->formType === MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT
        ) {
            $options['macroKeys'] = MM_WPFS_OneTimePaymentMacroReplacer::getMacroKeys();
        }

        return $options;
    }
}

class MM_WPFS_FormsLocalizer extends MM_WPFS_AdminScriptLocalizer
{
    public function localizeScripts()
    {
        wp_localize_script('wp-full-stripe-admin-js', 'wpfsAdminL10n', array(
            'shortcodeCopiedMessage' =>
                /* translators: Success banner message when a form shortcode is copied to the clipboard  */
                __('Shortcode copied to clipboard.', 'wp-full-stripe-admin'),
            'deleteFormConfirmationMessage' =>
                /* translators: Confirmation message displayed before deleting a form
                 * p1: Name of the form
                 */
                __("Are you sure you'd like to delete form '%s' ?", 'wp-full-stripe-admin'),
            'cloneFormTitle' =>
                /* translators: Title of the 'Clone form' dialog  */
                __('Clone form', 'wp-full-stripe-admin'),
            'deleteFormTitle' =>
                /* translators: Title of the 'Delete form' dialog  */
                __('Delete form', 'wp-full-stripe-admin'),
            'copyPostfix' =>
                /* translators: This is the postfix add to name of a cloned form. TestForm -> TestFormCopy  */
                __('Copy', 'wp-full-stripe-admin'),
        )
        );
    }
}

class MM_WPFS_TransactionsLocalizer extends MM_WPFS_AdminScriptLocalizer
{
    public function localizeScripts()
    {
        wp_localize_script('wp-full-stripe-admin-js', 'wpfsAdminL10n', array(
            'refundPaymentTitle' =>
                /* translators: Title of the 'Refund payment' dialog  */
                __('Refund payment', 'wp-full-stripe-admin'),
            'refundPaymentConfirmationMessage' =>
                /* translators: Confirmation message displayed in the 'Refund payment' dialog
                 * p1: Identifier of the payment to be refunded
                 */
                __("Are you sure you'd like to refund payment '%s' ?", 'wp-full-stripe-admin'),
            'deletePaymentTitle' =>
                /* translators: Title of the 'Delete payment' dialog  */
                __('Delete payment', 'wp-full-stripe-admin'),
            'deletePaymentConfirmationMessage' =>
                /* translators: Confirmation message displayed in the 'Delete payment' dialog
                 * p1: Identifier of the payment to be deleted
                 */
                __("Are you sure you'd like to delete payment '%s' from the WordPress database?", 'wp-full-stripe-admin'),
            'capturePaymentTitle' =>
                /* translators: Title of the 'Capture payment' dialog  */
                __('Capture payment', 'wp-full-stripe-admin'),
            'capturePaymentConfirmationMessage' =>
                /* translators: Confirmation message displayed in the 'Capture payment' dialog
                 * p1: Identifier of the payment to be captured
                 */
                __("Are you sure you'd like to capture payment '%s' ?", 'wp-full-stripe-admin'),
            'cancelSubscriptionTitle' =>
                /* translators: Title of the 'Cancel subscription' dialog  */
                __('Cancel subscription', 'wp-full-stripe-admin'),
            'cancelSubscriptionConfirmationMessage' =>
                /* translators: Confirmation message displayed in the 'Cancel subscription' dialog
                 * p1: Identifier of the subscription to be canceled
                 */
                __("Are you sure you'd like to cancel subscription '%s' ?", 'wp-full-stripe-admin'),
            'deleteSubscriptionTitle' =>
                /* translators: Title of the 'Delete subscription' dialog  */
                __('Delete subscription', 'wp-full-stripe-admin'),
            'deleteSubscriptionConfirmationMessage' =>
                /* translators: Confirmation message displayed in the 'Delete subscription' dialog
                 * p1: Identifier of the subscription to be deleted
                 */
                __("Are you sure you'd like to delete subscription '%s' from the WordPress database?", 'wp-full-stripe-admin'),
            'refundDonationTitle' =>
                /* translators: Title of the 'Refund donation' dialog  */
                __('Refund donation', 'wp-full-stripe-admin'),
            'refundDonationConfirmationMessage' =>
                /* translators: Confirmation message displayed in the 'Refund donation' dialog
                 * p1: Identifier of the donation to be refunded
                 */
                __("Are you sure you'd like to refund donation '%s' ?", 'wp-full-stripe-admin'),
            'cancelDonationTitle' =>
                /* translators: Title of the 'Cancel donation' dialog  */
                __('Cancel donation', 'wp-full-stripe-admin'),
            'cancelDonationConfirmationMessage' =>
                /* translators: Confirmation message displayed in the 'Cancel donation' dialog
                 * p1: Identifier of the donation to be canceled
                 */
                __("Are you sure you'd like to cancel donation '%s' ?", 'wp-full-stripe-admin'),
            'deleteDonationTitle' =>
                /* translators: Title of the 'Delete donation' dialog  */
                __('Delete donation', 'wp-full-stripe-admin'),
            'deleteDonationConfirmationMessage' =>
                /* translators: Confirmation message displayed in the 'Delete donation' dialog
                 * p1: Identifier of the donation to be deleted
                 */
                __("Are you sure you'd like to delete donation '%s' from the WordPress database?", 'wp-full-stripe-admin'),
            'deleteSavedCardTitle' =>
                /* translators: Title of the 'Delete saved card' dialog  */
                __('Delete saved card', 'wp-full-stripe-admin'),
            'deleteSavedCardConfirmationMessage' =>
                /* translators: Confirmation message displayed in the 'Delete saved card' dialog
                 * p1: Identifier of the saved card to be deleted
                 */
                __("Are you sure you'd like to delete saved card '%s' from the WordPress database?", 'wp-full-stripe-admin'),
        )
        );
    }
}
