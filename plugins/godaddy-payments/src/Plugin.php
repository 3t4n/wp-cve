<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt;

use Exception;
use GoDaddy\WooCommerce\Poynt\Emails\Emails as GdpEmails;
use GoDaddy\WooCommerce\Poynt\Frontend\Admin\Notices;
use GoDaddy\WooCommerce\Poynt\Frontend\MyAccount\PaymentMethods;
use GoDaddy\WooCommerce\Poynt\Gateways\CreditCardGateway;
use GoDaddy\WooCommerce\Poynt\Gateways\PayInPersonGateway;
use GoDaddy\WooCommerce\Poynt\Helpers\PoyntHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\WCHelper;
use GoDaddy\WooCommerce\Poynt\Pages\ViewOrderPage;
use GoDaddy\WooCommerce\Poynt\Payments\Captures;
use GoDaddy\WooCommerce\Poynt\Shipping\CoreShippingMethods;
use GoDaddy\WooCommerce\Poynt\Shipping\LocalPickup\LocalPickup;
use GoDaddy\WooCommerce\Poynt\Sync\Jobs\ActiveSmartTerminalDetector;
use GoDaddy\WooCommerce\Poynt\Sync\Jobs\PoyntTransactionSynchronizer;
use GoDaddy\WooCommerce\Poynt\Sync\PoyntOrderSynchronizer;
use GoDaddy\WooCommerce\Poynt\Webhooks\PoyntWebhooksHandler;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;

defined('ABSPATH') or exit;

/**
 * Plugin main class.
 *
 * @since 1.0.0
 *
 * @method Lifecycle get_lifecycle_handler
 */
class Plugin extends Framework\SV_WC_Payment_Gateway_Plugin
{
    /** the plugin's version number */
    const VERSION = '1.7.3';

    /** the plugin's gateway id */
    const PLUGIN_ID = 'poynt';

    /** the plugin's production environment identifier */
    const ENVIRONMENT_PRODUCTION = 'production';

    /** the plugin's staging production identifier */
    const ENVIRONMENT_STAGING = 'staging';

    /** @var string the credit card gateway identifier */
    const CREDIT_CARD_GATEWAY_ID = 'poynt_credit_card';

    /** @var string the pay in person gateway identifier */
    const PAYINPERSON_GATEWAY_ID = 'pay_in_person';

    /** @var array<string, array<int, string>> supported countries and currencies */
    public const SUPPORTED_COUNTRIES = [
        'US' => ['USD'],
        'CA' => ['CAD'],
    ];

    /** @var Plugin single instance of this plugin */
    protected static $instance;

    /** @var array active gateways */
    protected $activeGateways;

    /** @var Support instance */
    private $support;

    /**
     * Plugin constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->activeGateways = $this->getActiveGateways();

        parent::__construct(
            self::PLUGIN_ID,
            self::VERSION,
            [
                'text_domain' => 'godaddy-payments',
                'gateways'    => $this->activeGateways,
                // TODO: Replace currencies when limitations are clear (pitch still has a TODO instruction for this one). {AC 2021-02-01}
                'currencies'   => [],
                'dependencies' => [
                    'php_extensions' => [
                        'json',
                    ],
                ],
                'supported_features' => [
                    'hpos'   => true,
                    'blocks' => [
                        'cart'     => true,
                        'checkout' => true,
                    ],
                ],
                'supports' => [
                    self::FEATURE_CAPTURE_CHARGE,
                    self::FEATURE_MY_PAYMENT_METHODS,
                ],
                'require_ssl' => true,
            ]
        );

        $this->support = $this->getSupport();
        $this->add_hooks();
        $this->init();
    }

    /**
     * Setup hooks.
     *
     * @since 1.3.1
     */
    private function add_hooks()
    {
        add_action('admin_enqueue_scripts', [$this, 'registerScripts']);
        add_action('admin_enqueue_scripts', [$this, 'registerStyles']);
        add_action('wp_enqueue_scripts', [$this, 'registerPoyntCollect']);
    }

    /**
     * Return the activated gateways based on the conditions for Pay In Person gateway.
     *
     * @since 1.3.1
     *
     * @return array
     */
    protected function getActiveGateways() : array
    {
        $gateways = [
            self::CREDIT_CARD_GATEWAY_ID => CreditCardGateway::class,
        ];

        if (WCHelper::shouldLoadPayInPersonGateway()) {
            $gateways[self::PAYINPERSON_GATEWAY_ID] = PayInPersonGateway::class;
        }

        return $gateways;
    }

    /**
     * Adds gateway setup page scripts.
     *
     * @internal
     *
     * @since 1.3.1
     */
    public function registerScripts()
    {
        // only load the scripts on the payment methods page
        if (! WCHelper::isAccessingPaymentSettings()) {
            return;
        }

        $paymentMethods = [
            [
                'gatewayId'   => self::CREDIT_CARD_GATEWAY_ID,
                'allowEnable' => true,
            ],
        ];

        if (array_key_exists(self::PAYINPERSON_GATEWAY_ID, $this->activeGateways)) {
            $paymentMethods[] = [
                'gatewayId'   => self::PAYINPERSON_GATEWAY_ID,
                'allowEnable' => PoyntHelper::hasPoyntSmartTerminalActivated(),
            ];
        }

        wp_enqueue_script(self::PLUGIN_ID.'_payment_methods', $this->get_plugin_url().'/assets/js/admin/payment-methods.js', ['jquery'], self::VERSION);
        wp_localize_script(self::PLUGIN_ID.'_payment_methods', self::PLUGIN_ID.'PaymentMethods', $paymentMethods);
    }

    /**
     * Adds gateway setup page stylesheet.
     *
     * @internal
     *
     * @since 1.3.1
     */
    public function registerStyles()
    {
        wp_enqueue_style('gdp-notices', $this->get_plugin_url().'/assets/css/notices.css', [], $this->get_version());
    }

    /**
     * Initial the core functionality.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function init()
    {
        $this->loadShippingMethods();
        $this->loadGdpEmails();
        $this->loadNotices();
        $this->loadScheduledActions();
        $this->loadWebhooksHandler();
        $this->loadPoyntOrderSynchronizer();
        $this->addCaptureSupport();
        $this->loadTransactionSynchronizer();
        $this->loadMisc();
    }

    /**
     * Gets the full path and filename of the plugin file.
     *
     * @since 1.0.0
     *
     * @return string
     */
    protected function get_file() : string
    {
        return __DIR__;
    }

    /**
     * Gets the plugin full name.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_plugin_name() : string
    {
        return __('GoDaddy Payments', 'godaddy-payments');
    }

    /**
     * Gets the main plugin instance.
     *
     * Ensures only one instance is/can be loaded.
     *
     * @since 1.0.0
     *
     * @return Plugin
     */
    public static function instance() : self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Gets the plugin documentation URL.
     *
     * @NOTE this will be used by {@see CreditCardGateway::get_not_configured_error_message()} {unfulvio 2021-07-02}
     * If we want to have a different URL than the documentation URL produced there, the method needs to be overridden separately.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_documentation_url() : string
    {
        return 'https://www.godaddy.com/help/40417';
    }

    /**
     * Gets the reviews URL.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_reviews_url()
    {
        return 'https://wordpress.org/support/plugin/godaddy-payments/reviews/';
    }

    /**
     * Gets the plugin sales page URL.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_sales_page_url()
    {
        return 'https://payments.godaddy.com/';
    }

    /**
     * Gets the plugin support URL.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_support_url()
    {
        return 'https://wordpress.org/support/plugin/godaddy-payments/';
    }

    /**
     * Gets the settings link used in the plugins page.
     *
     * @since 1.7.2
     * @internal
     *
     * @param $gateway_id
     * @return string
     */
    public function get_settings_link($gateway_id = '')
    {
        switch ($gateway_id) {
            case self::CREDIT_CARD_GATEWAY_ID:
                /** @var CreditCardGateway $gateway */
                $gateway = $this->get_gateway($gateway_id);

                return sprintf('<a href="%s">%s</a>', $this->get_settings_url($gateway_id), sprintf(esc_html_x('Configure %s', 'Payment gateway title', 'godaddy-payments'), $gateway->get_method_title()));
            case self::PAYINPERSON_GATEWAY_ID:
                try {
                    /** @var PayInPersonGateway $gateway */
                    $gateway = $this->get_gateway($gateway_id);

                    return $gateway && $gateway->is_configured()
                        ? sprintf('<a href="%s">%s</a>', $this->get_settings_url($gateway_id), sprintf(esc_html_x('Configure %s', 'Payment gateway title', 'godaddy-payments'), $gateway->get_method_title()))
                        : '';
                } catch (Exception $e) {
                    return '';
                }
            default:
                return parent::get_settings_link($gateway_id);
        }
    }

    /**
     * Gets the "My payment methods" handler instance.
     *
     * @since 1.0.0
     *
     * @return PaymentMethods
     */
    protected function get_my_payment_methods_instance() : PaymentMethods
    {
        return new PaymentMethods($this);
    }

    /**
     * Loads and initializes the plugin lifecycle handler.
     *
     * @since 1.0.0
     */
    protected function init_lifecycle_handler()
    {
        $this->lifecycle_handler = new Lifecycle($this);
    }

    /**
     * Loads and initializes the plugin REST API handler.
     *
     * @since 1.2.0
     */
    protected function init_rest_api_handler()
    {
        require_once $this->get_payment_gateway_framework_path().'/rest-api/class-sv-wc-payment-gateway-plugin-rest-api.php';

        $this->rest_api_handler = new REST($this);
    }

    /**
     * Gets the support handler instance.
     *
     * @since 1.2.0
     *
     * @return Support
     */
    public function getSupport() : Support
    {
        return $this->support instanceof Support ? $this->support : new Support();
    }

    /**
     * Adds any admin notices upon initialization.
     *
     * @internal
     *
     * @since 1.0.0
     */
    public function add_admin_notices()
    {
        parent::add_admin_notices();

        if ($this->get_lifecycle_handler()->hasMigratedFromLegacyPlugin()) {
            $this->get_admin_notice_handler()->add_admin_notice(
                sprintf(
                    /* translators: Placeholders: %1$s - opening <a> HTML link tag, %2$s - closing </a> HTML link tag */
                    __('Migration successful! WooCommerce Poynt Collect Payment Gateway was deactivated, and Poynt &mdash; a GoDaddy Brand for WooCommerce has been %1$sconfigured with your previous settings%2$s.', 'godaddy-payments'),
                    '<a href="'.esc_url($this->get_settings_url()).'">',
                    '</a>'
                ),
                Lifecycle::MIGRATED_FROM_LEGACY_PLUGIN_FLAG,
                [
                    'dismissible'             => true,
                    'always_show_on_settings' => false,
                    'notice_class'            => 'updated',
                ]
            );
        }
    }

    /**
     * Adds the delayed admin notices.
     *
     * @internal
     *
     * @since 1.0.0
     */
    public function add_delayed_admin_notices()
    {
        parent::add_delayed_admin_notices();

        $this->add_new_user_notices();
    }

    /**
     * Adds notices to prompt new user configuration.
     *
     * @since 1.0.0
     * @throws Exception
     */
    private function add_new_user_notices() : void
    {
        /** @var CreditCardGateway $settings_gateway */
        $settings_gateway = $this->get_gateway(self::CREDIT_CARD_GATEWAY_ID);

        // warn and bail if the shop's currency for the given country isn't supported
        if ($this->maybeAddCurrencyNotSupportedForCountryAdminNotice()) {
            return;
        }

        // warn and bail if the shop's country or currency isn't supported
        if (count(array_filter([$this->maybeAddCountryAdminNotice(), $this->maybeAddCurrencyAdminNotice()])) > 0) {
            return;
        }

        // show the following notices conditionally depending whether the admin is or isn't on the gateway settings page
        if (! $settings_gateway || ! $this->is_payment_gateway_configuration_page($settings_gateway->get_id())) {
            $this->maybeAddOnboardingNotice();

            return;
        }
    }

    /**
     * Gets the signup URL.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function getSignupUrl() : string
    {
        return 'https://signup.payments.godaddy.com/r/woo-plugin';
    }

    /**
     * Gets the Poynt Collect URL.
     *
     * @since 1.7.0
     *
     * @param string $environment
     * @return string|null
     */
    public function getPoyntCollectUrl(string $environment) : ?string
    {
        switch ($environment) {
            case Plugin::ENVIRONMENT_PRODUCTION:
                return 'https://cdn.poynt.net/collect.js';
            case Plugin::ENVIRONMENT_STAGING:
                return 'https://cdn.poynt.net/ote/collect-ote.js';
            default:
                return null;
        }
    }

    /**
     * Registers the Poynt Collect script.
     *
     * @since 1.7.0
     *
     * @param string $environment
     * @param bool $enqueue whether to also enqueue the script
     * @return void
     */
    public function registerPoyntCollect(string $environment, bool $enqueue = false) : void
    {
        $url = $this->getPoyntCollectUrl($environment);

        if (! $url) {
            return;
        }

        wp_register_script('poynt-collect', $url, [], $this->get_version());

        if ($enqueue) {
            wp_enqueue_script('poynt-collect');
        }
    }

    /**
     * Gets the list of supported countries.
     *
     * @since 1.6.0
     *
     * @return string[] array of ISO 3166-1 alpha-2 country codes
     */
    public static function getSupportedCountries() : array
    {
        return array_keys(self::SUPPORTED_COUNTRIES);
    }

    /**
     * Gets the list of supported currencies.
     *
     * When a country is provided, only the currencies supported by that country are returned.
     *
     * @since 1.6.0
     *
     * @param string|null $country
     * @return string[] array of ISO 3166-1 alpha-2 country codes
     */
    public static function getSupportedCurrencies(?string $country = null) : array
    {
        $currenciesByCountry = self::SUPPORTED_COUNTRIES;

        return $country ? (array) ($currenciesByCountry[$country] ?? []) : array_unique(array_merge([], ...array_values($currenciesByCountry)));
    }

    /**
     * Determines whether the given country is supported or not.
     *
     *
     * @since 1.1.0
     *
     * @param string|null $country will default to current store country if not provided
     * @return bool
     */
    public static function isCountrySupported(?string $country = null) : bool
    {
        $country = $country ?? (function_exists('wc_get_base_location') ? wc_get_base_location()['country'] : null);

        return in_array($country, self::getSupportedCountries(), true);
    }

    /**
     * Determines whether the current store currency is supported or not.
     *
     * When a country is provided, only the currencies supported by that country are checked.
     *
     * @since 1.1.0
     *
     * @param string|null $currency will default to current store currency if not provided
     * @param string|null $country
     * @return bool
     */
    public static function isCurrencySupported(?string $currency = null, ?string $country = null) : bool
    {
        return in_array($currency ?? get_woocommerce_currency(), self::getSupportedCurrencies($country), true);
    }

    /**
     * Returns the configured country.
     *
     * @return string|null
     */
    public function getCountry() : ?string
    {
        $baseLocation = (function_exists('wc_get_base_location') ? wc_get_base_location() : null);

        return $baseLocation['country'] ?? null;
    }

    /**
     * Gets the current locale used in the Poynt Collect payment form fields.
     *
     * @since 1.7.0
     *
     * @return string one of en-US | en-CA | fr-CA (supported locales)
     */
    public function getLocale() : string
    {
        $locale = get_locale();
        $default_locale = 'en_US';
        $supported_locales = ['en_US', 'fr_CA', 'en_CA'];

        if (! in_array($locale ?: 'en_US', $supported_locales, true)) {
            $locale = $default_locale;
        }

        return str_replace('_', '-', $locale);
    }

    /**
     * May add an admin notice if the store's currency for the current country is not supported.
     *
     * @since 1.6.0
     *
     * @return bool true if the notice was added
     * @throws Exception
     */
    private function maybeAddCurrencyNotSupportedForCountryAdminNotice() : bool
    {
        $country = $this->getCountry();
        $currency = get_woocommerce_currency();

        if (! self::isCountrySupported($country) || self::isCurrencySupported($currency, $country)) {
            return false;
        }

        $countries = WC()->countries->get_countries() ?? [];
        $currencies = get_woocommerce_currencies();

        /** @var array<string> $supportedCurrencies */
        $supportedCurrencies = array_map(
            fn ($currencyCode) => $currencies[$currencyCode],
            self::getSupportedCurrencies($country),
        );

        /** @var array<string> $supportedCurrencyCodes */
        $supportedCurrencyCodes = array_map(
            static fn ($currencyCode) => $currencyCode,
            self::getSupportedCurrencies($country),
        );

        $this->get_admin_notice_handler()->add_admin_notice(
            sprintf(
                /* translators: Placeholders: %1$s - list of supported currencies, %2$s - store country, %3$s - opening <a> HTML link tag, %4$s - closing </a> HTML link tag, %5$s - currency code */
                __('GoDaddy Payments requires %1$s transactions for %2$s businesses. Please %3$schange your Currency%4$s to %5$s in order to use the payment method.', 'godaddy-payments'),
                Framework\SV_WC_Helper::array_join_natural($supportedCurrencies, 'or'),
                $countries[$country] ?? $country,
                '<a href="'.esc_url($this->get_general_configuration_url()).'">',
                '</a>',
                Framework\SV_WC_Helper::array_join_natural($supportedCurrencyCodes, 'or')
            ),
            'invalid-currency-for-current-country-prompt',
            [
                'notice_class' => 'notice-error',
                'dismissible'  => false,
            ]
        );

        return true;
    }

    /**
     * May add an admin notice if the store's currency is not supported.
     *
     * @since 1.1.0
     *
     * @return bool true if the notice was added
     * @throws Exception
     */
    private function maybeAddCurrencyAdminNotice() : bool
    {
        if (self::isCurrencySupported()) {
            return false;
        }

        $currencies = get_woocommerce_currencies();

        /** @var array<string> $supportedCurrencies */
        $supportedCurrencies = array_map(
            static fn ($currencyCode) => $currencies[$currencyCode],
            self::getSupportedCurrencies(),
        );

        $this->get_admin_notice_handler()->add_admin_notice(
            sprintf(
                /* translators: Placeholders: %1$s - list of supported currencies, %2$s - opening <a> HTML link tag, %3$s - closing </a> HTML link tag */
                __('GoDaddy Payments requires %1$s transactions. Please %2$schange your Currency%3$s in order to use the payment method.', 'godaddy-payments'),
                Framework\SV_WC_Helper::array_join_natural($supportedCurrencies, 'or'),
                '<a href="'.esc_url($this->get_general_configuration_url()).'">',
                '</a>'
            ),
            'invalid-currency-prompt',
            [
                'notice_class' => 'notice-error',
                'dismissible'  => false,
            ]
        );

        return true;
    }

    /**
     * May add an admin notice if the store's country is not supported.
     *
     * @since 1.1.0
     *
     * @return bool true if the notice was added
     * @throws Exception
     */
    private function maybeAddCountryAdminNotice() : bool
    {
        if (self::isCountrySupported()) {
            return false;
        }

        $countries = WC()->countries->get_countries() ?? [];

        /** @var array<string> $supportedCountries */
        $supportedCountries = array_map(
            static fn ($countryCode) => $countries[$countryCode],
            self::getSupportedCountries()
        );

        $availableCountriesFriendlyMessage = Framework\SV_WC_Helper::array_join_natural($supportedCountries, 'or');

        $this->get_admin_notice_handler()->add_admin_notice(
            sprintf(
                /* translators: Placeholders: %1$s - list of supported countries, %2$s - opening <a> HTML link tag, %3$s - closing </a> HTML link tag, %4$s - list of supported countries % */
                __('GoDaddy Payments is available for %1$s-based businesses. Please %2$supdate your Store Address%3$s if you are in the %4$s.', 'godaddy-payments'),
                $availableCountriesFriendlyMessage,
                '<a href="'.esc_url($this->get_general_configuration_url()).'">',
                '</a>',
                $availableCountriesFriendlyMessage,
            ),
            'unsupported-country-prompt',
            [
                'notice_class' => 'notice-error',
                'dismissible'  => false,
            ]
        );

        return true;
    }

    /**
     * May add an admin notice if the payment gateway is not enabled.
     *
     * Checks if the credentials are empty too, assuming this is when the plugin is first activated and never configured before.
     *
     * @since 1.1.3
     *
     * @return void
     */
    private function maybeAddOnboardingNotice() : void
    {
        $settings_gateway = $this->get_gateway(self::CREDIT_CARD_GATEWAY_ID);

        if ($settings_gateway && ! $settings_gateway->is_configured() && ! $settings_gateway->is_enabled()) {
            $this->get_admin_notice_handler()->add_admin_notice(
                sprintf(
                    /* translators: Placeholders: %1$s - Plugin name, %2$s - country based pricing, %3$s - opening <a> HTML link tag, %4$s - closing </a> HTML link tag */
                    __('Set up %1$s for WooCommerce to securely accept credit and debit card payments in your checkout, and keep more of your money with the industry\'s lowest fees - just %2$s per online transaction. %3$sSet up now%4$s', 'godaddy-payments'),
                    $this->get_plugin_name(),
                    $this->getPricing(),
                    '<strong><a href="'.esc_url($this->get_settings_url($settings_gateway->get_id())).'">',
                    '</a></strong>'
                ),
                'poynt-disabled-no-credentials',
                [
                    'dismissible'             => true,
                    'always_show_on_settings' => false,
                    'notice_class'            => 'notice-info',
                ]
            );
        }
    }

    /**
     * Gets the pricing based on the configured country.
     *
     * @return string
     */
    protected function getPricing() : string
    {
        switch ($this->getCountry()) {
            case 'CA':
                /* translators: Context: pricing (e.g.: 2.7% + 0¢) formatted using HTML entities - only adjust formatting if necessary for the given locale */
                $pricing = __('2.7&#37; + 0&#162;', 'godaddy-payments');
                break;
            default:
                /* translators: Context: pricing (e.g.: 2.3% + 30¢) formatted using HTML entities - only adjust formatting if necessary for the given locale */
                $pricing = __('2.3&#37; + 30&#162;', 'godaddy-payments');
        }

        return $pricing;
    }

    /**
     * Load the scheduled action methods.
     *
     * @since 1.3.0
     */
    protected function loadScheduledActions()
    {
        new ActiveSmartTerminalDetector();
    }

    /**
     * Load the shipping methods.
     *
     * @since 1.3.0
     */
    protected function loadShippingMethods()
    {
        new CoreShippingMethods();
        new LocalPickup();
    }

    /**
     * load woo emails.
     *
     * @since 1.3.0
     */
    protected function loadGdpEmails()
    {
        new GdpEmails();
    }

    /**
     * load Notices.
     *
     * 1.3.0
     */
    protected function loadNotices()
    {
        new Notices();
    }

    /**
     * load webhooks handlers.
     *
     * @since 1.3.0
     */
    protected function loadWebhooksHandler()
    {
        new PoyntWebhooksHandler();
    }

    /**
     * load Sync Poynt Order class.
     *
     * @since 1.3.0
     */
    protected function loadPoyntOrderSynchronizer()
    {
        new PoyntOrderSynchronizer();
    }

    protected function addCaptureSupport()
    {
        new Captures();
    }

    /**
     * load Sync Transactions class.
     *
     * @since 1.3.0
     */
    protected function loadTransactionSynchronizer()
    {
        new PoyntTransactionSynchronizer();
    }

    /**
     * load Miscellaneous things.
     *
     * @since 1.3.0
     */
    protected function loadMisc()
    {
        new ViewOrderPage();
    }
}
