<?php

defined('ABSPATH') or exit;

/**
 * This class holds logic for the Settings screen located at
 * Woocommerce -> Payments -> Montonio Payments
 *
 * The class handles building the settings form and input validation.
 */
class Montonio_Card_Payments_Settings
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
     * Montonio_Financing_Settings constructor.
     */
    public function __construct()
    {
        $this->lang       = $this::DEFAULT_LANG;
        $this->currency   = $this::DEFAULT_CURRENCY;
        $this->formFields = $this->getConfigurationFields();
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
        
        return $this->configurationFormFields;
    }

    protected function initConfigurationFields() {
        $this->configurationFormFields = array(
            'enabled' => array(
                'title'       => __('Enable Montonio Card Payments', 'montonio-for-woocommerce'),
                'label'       => __('Enabled', 'montonio-for-woocommerce'),
                'type'        => 'checkbox',
                'description' => 'Show Montonio Card Payments as a separate payment option in checkout',
                'default'     => 'no',
            ),
            'montonioCardPaymentsEnvironment'      => array(
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
            'montonioCardPaymentsAccessKey'        => array(
                'title'   => __('Access Key', 'montonio-for-woocommerce'),
                'type'    => 'text',
                'default' => '', // maybe translatable?
            ),
            'montonioCardPaymentsSecretKey'        => array(
                'title'       => __('Secret Key', 'montonio-for-woocommerce'),
                'type'        => 'password',
                'description' => __('Live and Sandbox API keys can be obtained at <a target="_blank" href="https://partner.montonio.com">Montonio Partner System</a>', 'montonio-for-woocommerce'),
                'default'     => '', // maybe translatable?
            ),
            'montonioCardPaymentsOrderPrefix'      => array(
                'title'       => __('Order Prefix', 'montonio-for-woocommerce'),
                'type'        => 'text',
                'description' => __('<strong>[MULTISTORE]</strong><br />If you are using Montonio in multiple shops with only one pair of API keys, <br />set the Order Prefix here to distinguish between orders in the Montonio Partner System', 'montonio-for-woocommerce'),
                'default'     => '', // maybe translatable?
            ),
            'montonioCardPaymentsMerchantName'     => array(
                'title'       => __('Merchant Name', 'montonio-for-woocommerce'),
                'type'        => 'text',
                'description' => __('<strong>[MULTISTORE]</strong><br />The name to be shown to customers in Montonio application. By default this is your company\'s Display Name (set in the Partner System), but you can also override it here', 'montonio-for-woocommerce'),
                'default'     => '', // maybe translatable?
            ),
            'title'            => array(
                'title'       => __('Title', 'montonio-for-woocommerce'),
                'type'        => 'text',
                'default'     => __('Card Payment', 'montonio-for-woocommerce'),
                'description' => __('Payment method title that the customer will see at checkout', 'montonio-for-woocommerce'),
            ),
            'description'      => array(
                'title'       => __('Description', 'montonio-for-woocommerce'),
                'type'        => 'text',
                'default'     => __('Pay with your credit or debit card via Montonio', 'montonio-for-woocommerce'),
            ),
            'montonioCardPaymentsDefaultCountry'   => array(
                'title'       => __('Default Country', 'montonio-for-woocommerce'),
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'default'     => 'EE',
                'description' => __('The country which banks the customer sees when switching to another payment method in Montonio\'s gateway.', 'montonio-for-woocommerce'),
                'options'     => array(
                    'EE' => 'Estonia',
                    'FI' => 'Finland',
                    'LV' => 'Latvia',
                    'LT' => 'Lithuania',
                ),
            ),
            'montonioCardPaymentsPaymentHandleCss' => array(
                'title'       => __('CSS for checkout', 'montonio-for-woocommerce'),
                'type'        => 'textarea',
                'css'         => 'width: 400px;',
                'description' => __(
                    'Here you can insert additional CSS rules for checkout.',
                    'montonio-for-woocommerce'
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
