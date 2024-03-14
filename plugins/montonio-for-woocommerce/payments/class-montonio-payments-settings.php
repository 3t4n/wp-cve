<?php
defined('ABSPATH') or exit;

/**
 * This class holds logic for the Settings screen located at
 * Woocommerce -> Payments -> Montonio Payments
 *
 * The class handles building the settings form and input validation.
 */
class Montonio_Payments_Settings
{
    const DEFAULT_CURRENCY = 'EUR';
    const DEFAULT_LANG     = 'en';

    /**
     * @var integer
     */
    protected $settings;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var string
     */
    protected $lang;

    /**
     * @var array
     */
    protected $formFields;

    /**
     * @var array
     */
    protected $configurationFormFields;

    /**
     * @var array
     */
    protected $advancedFormFields;

    /**
     * @return self
     */
    static public function create()
    {
        return new self();
    }

    /**
     * Montonio_Payments_Settings constructor.
     */
    public function __construct()
    {
        $this->lang       = $this::DEFAULT_LANG;
        $this->currency   = $this::DEFAULT_CURRENCY;
        $this->formFields = $this->getConfigurationFields();

        /**
         * Setup syncing banks on settings save
         */
        add_filter('woocommerce_settings_api_sanitized_fields_montonio_payments', array($this, 'montonio_validate_settings'));
    }

    /**
     * Perform validation on settings after saving them
     *
     * @param Array $settings - New Admin Settings data
     * @return Array
     */
    public function montonio_validate_settings($settings)
    {
        $syncRequest = true;
        
        /**
         * Disable the payment gateway if keys are not provided
         */
        if (!$settings['montonioPaymentsAccessKey'] || !$settings['montonioPaymentsSecretKey']) {
            add_action('admin_notices', array($this, 'montonio_error_missing_apikeys'));
            $settings['enabled'] = 'no';
            $syncRequest         = false;
        }

        /**
         * sync banks list
         */
        $settings['montonioPaymentsBankList'] = $this->sync_banks($settings, $syncRequest);

        /**
         * Update Montonio Plugin version in db
         */
        $settings['montonioPluginVersion'] = WC_MONTONIO_PLUGIN_VERSION;

        /**
         * sanitize other fields such as CSS
         */
        return $settings;
    }

    /**
     * Fetch list of available payment methods for Checkout page
     * @version 2.1
     *
     * @param array $settings - New Admin Settings data
     * @param bool $sync - If false, then API keys were missing and show error without syncing
     * 
     * @return string - the JSON-encoded banklist array
     */
    public function sync_banks($settings, $sync = true)
    {
        if (!class_exists('MontonioPaymentsSDK')) {
            require_once dirname(dirname(__FILE__)) . '/libraries/MontonioPaymentsSDK.php';
        }

        try {
            /**
             * ApiKeys not provided
             */
            if (!$sync) {
                throw new Exception();
            }

            $montonioPayments = new MontonioPaymentsSDK(
                $settings['montonioPaymentsAccessKey'],
                $settings['montonioPaymentsSecretKey'],
                $settings['montonioPaymentsEnvironment']
            );

            $response = json_decode($montonioPayments->fetchPaymentMethods());
            $response = json_encode($response);
            
            return $response;
        } catch (Exception $e) {

            /**
             * If there has previously been a successful sync of banks, then keep the banklist
             * otherwise create an empty array for banklist to prevent errors elsewhere
             */
            add_action('admin_notices', array($this, 'montonio_error_failed_to_sync_banks'));
            return isset($this->settings['montonioPaymentsBankList']) ?
                $this->settings['montonioPaymentsBankList'] :
                json_encode([]);
        }
    }

    // =========================================================================
    // Admin notice messages
    // =========================================================================

    public function montonio_error_failed_to_sync_banks()
    {
        return $this->montonio_show_notice(
            __('Montonio Payments was unable to sync banks in the background.', 'montonio-for-woocommerce'),
            'error'
        );
    }

    public function montonio_error_missing_apikeys()
    {
        return $this->montonio_show_notice(
            __('Montonio Payments was disabled due to missing API keys.', 'montonio-for-woocommerce'),
            'error'
        );
    }

    public function montonio_show_notice($message, $severity = 'success')
    {
        if (!$message) {
            return;
        }

        echo '<div class="notice notice-' . $severity . ' is-dismissible"><p>';
        echo $message;
        echo '</p></div>';
    }

    /**
     * The form fields at Montonio Payments configuration page
     *
     * @return array
     */
    private function getConfigurationFields()
    {
        $this->initConfigurationFields();
        $this->initAdvancedFields();

        return array_merge(
            $this->configurationFormFields,
            $this->advancedFormFields
        );
    }

    protected function initConfigurationFields() {
        $this->configurationFormFields = array(
            'enabled'                          => array(
                'title'       => __('Enable Montonio Payments', 'montonio-for-woocommerce'),
                'label'       => __('Enabled', 'montonio-for-woocommerce'),
                'type'        => 'checkbox',
                'description' => '',
                'default'     => 'no',
            ),
            'montonioPaymentsEnvironment'      => array(
                'type'        => 'select',
                'title'       => __('Environment', 'montonio-for-woocommerce'),
                'class'       => 'wc-enhanced-select',
                'default'     => 'production',
                'description' => __('Use the Sandbox environment for testing only', 'montonio-for-woocommerce'),
                'options'     => array(
                    'production' => __('Production', 'montonio-for-woocommerce'),
                    'sandbox'    => __('Sandbox', 'montonio-for-woocommerce'),
                ),
            ),
            'montonioPaymentsAccessKey'        => array(
                'title'   => __('Access Key', 'montonio-for-woocommerce'),
                'type'    => 'text',
                'default' => '', // maybe translatable?
            ),
            'montonioPaymentsSecretKey'        => array(
                'title'       => __('Secret Key', 'montonio-for-woocommerce'),
                'type'        => 'password',
                'description' => __('Live and Sandbox API keys can be obtained at <a target="_blank" href="https://partner.montonio.com">Montonio Partner System</a>', 'montonio-for-woocommerce'),
                'default'     => '', // maybe translatable?
            ),
            'montonioPaymentsOrderPrefix'      => array(
                'title'       => __('Order Prefix', 'montonio-for-woocommerce'),
                'type'        => 'text',
                'description' => __('<strong>[MULTISTORE]</strong><br />If you are using Montonio in multiple shops with only one pair of API keys, <br />set the Order Prefix here to distinguish between orders in the Montonio Partner System', 'montonio-for-woocommerce'),
                'default'     => '', // maybe translatable?
            ),
            'montonioPaymentsMerchantName'     => array(
                'title'       => __('Merchant Name', 'montonio-for-woocommerce'),
                'type'        => 'text',
                'description' => __('<strong>[MULTISTORE]</strong><br />The name to be shown to customers in Montonio application. By default this is your company\'s Display Name (set in the Partner System), but you can also override it here', 'montonio-for-woocommerce'),
                'default'     => '', // maybe translatable?
            ),
            'title'            => array(
                'title'       => __('Title', 'montonio-for-woocommerce'),
                'type'        => 'text',
                'default'     => __('Pay with your bank', 'montonio-for-woocommerce'),
                'description' => __('Payment method title that the customer will see at checkout', 'montonio-for-woocommerce'),
            ),
            'description'      => array(
                'title'       => __('Description', 'montonio-for-woocommerce'),
                'type'        => 'text',
                'default'     => __('Complete the purchase with a direct payment from your bank account.', 'montonio-for-woocommerce'),
                'description' => __('Text to use when Payment handle style is "Show description".', 'montonio-for-woocommerce'),
            ),
            'alwaysShowDescription' => array(
                'title'       => __('Always show description?', 'montonio-for-woocommerce'),
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'default'     => 'no',
                'description' => __('If enabled, the description will always be shown on top of bank selection regardless of chosen "Payment handle style"', 'montonio-for-woocommerce'),
                'options'     => array(
                    'yes' => 'Yes',
                    'no' => 'No',
                ),
            ),
            'paymentHandleStyle'               => array(
                'title'       => __('Payment handle style', 'montonio-for-woocommerce'),
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'default'     => 'grid_logos',
                'description' => __('This controls how to display Montonio Payments at checkout', 'montonio-for-woocommerce'),
                'options'     => array(
                    'description' => 'Show description',
                    'list_logos'  => 'Show logos in list',
                    'grid_logos'  => 'Show logos in grid',
                ),
            ),
            'showPlaceOrderInstructions'       => array(
                'title'       => __('Show instructions to customer?', 'montonio-for-woocommerce'),
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'default'     => 'no',
                'description' => __('If enabled, then after selecting the bank, a small text advising the customer to click "Place order" will be shown below the banks after selecting a payment method.', 'montonio-for-woocommerce'),
                'options'     => array(
                    'yes' => 'Yes',
                    'no' => 'No',
                ),
            ),
            'instructions'       => array(
                'title'       => __('The instructions text', 'montonio-for-woocommerce'),
                'type'        => 'textarea',
                'css'         => 'width: 400px;',
                'default'     => __('Click on the submit button to continue to the payment page.', 'montonio-for-woocommerce'),
                'description' => __('The text shown to the customer if "Show instructions to customer?" is set to Yes', 'montonio-for-woocommerce'),
            ),
            'montonioPaymentsPaymentHandleCss' => array(
                'title'       => __('CSS for checkout', 'montonio-for-woocommerce'),
                'type'        => 'textarea',
                'css'         => 'width: 400px;',
                'description' => __(
                    'Here you can insert additional CSS rules for checkout.',
                    'montonio-for-woocommerce'
                ),
            ),
            'montonioPaymentsDefaultCountry'   => array(
                'title'       => __('Default Eurozone Country', 'montonio-for-woocommerce'),
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'default'     => 'EE',
                'description' => __('The country whose banks to show first at checkout when using Euro (€) as currency.<br/>When using a different currency, e.g Polish Zloty (PLN), this option will be disregarded and the correct currency will be shown.', 'montonio-for-woocommerce'),
                'options'     => array(
                    'EE' => 'Estonia',
                    'FI' => 'Finland',
                    'LV' => 'Latvia',
                    'LT' => 'Lithuania',
                ),
            ),
        );
    }

    public function initAdvancedFields()
    {
        $this->advancedFormFields = array(
            
            'montonioPaymentsAutomaticallyChangeCountry'   => array(
                'title'       => __('Country by Checkout Data', 'montonio-for-woocommerce'),
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'default'     => 'EE',
                'description' => __('<strong>[ADVANCED]</strong> Should we attempt to change the selected country by the customer\'s info? If unsuccessful, we revert to your configured Default Country.', 'montonio-for-woocommerce'),
                'options'     => array(
                    'manual' => 'No, show the configured Default Country',
                    'wpml' => 'Select country by WPML locale',
                ),
            ),
            'montonioPaymentsTranslateCountryDropdown'   => array(
                'title'       => __('Country Dropdown Language', 'montonio-for-woocommerce'),
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'default'     => 'EE',
                'description' => __('<strong>[ADVANCED]</strong> How should the countries in the checkout dropdown be shown?', 'montonio-for-woocommerce'),
                'options'     => array(
                    'english' => 'in English',
                    'translated' => 'Translated',
                ),
            ),
            'montonioPaymentsEnqueueMode'   => array(
                'title'       => __('Enqueue Mode', 'montonio-for-woocommerce'),
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'default'     => 'enqueue',
                'description' => __('<strong>[ADVANCED]</strong> Select how to enqueue CSS and JavaScript files to your store', 'montonio-for-woocommerce'),
                'options'     => array(
                    'enqueue' => 'Enqueue (recommended)',
                    'echo' => 'Echo',
                ),
            ),
        );
    }

    /**
     * @return integer
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param integer $settings
     *
     * @return self
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return self
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     *
     * @return self
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * @return array
     */
    public function getFormFields()
    {
        return $this->formFields;
    }

    /**
     * @param array $formFields
     *
     * @return self
     */
    public function setFormFields($formFields)
    {
        $this->formFields = $formFields;

        return $this;
    }
}
