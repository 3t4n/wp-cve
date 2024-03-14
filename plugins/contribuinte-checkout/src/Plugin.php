<?php

namespace Checkout\Contribuinte;

use WC_Order;
use Checkout\Contribuinte\Vies\Vies;
use Checkout\Contribuinte\Menus\Admin;
use Checkout\Contribuinte\Helpers\Context;
use Automattic\WooCommerce\Utilities\FeaturesUtil;

class Plugin
{
    /**
     * Plugin name
     * @var string
     */
    private $pluginName = 'contribuinte-checkout';

    /**
     * Plugin version
     * @var string
     */
    private $version = '1.0.45';

    /**
     * Settings options name
     * @var string
     */
    private $settingsOptionsName = 'contribuinte-checkout-options';

    private $injectValidationInFooter = false;

    /**
     * Plugin constructor.
     */
    public function __construct()
    {
        $this->actions(); //Loads some needed classes
        $this->setHooks(); //Sets all needed hooks
    }

    /**
     * Starts this class
     * @return string
     */
    public static function init()
    {
        $class = __CLASS__;
        return new $class;
    }

    /**
     * Call the translations and admin classes
     */
    public function actions()
    {
        new Translations(); //Loads translations
        new Admin($this); //Add options page inside WordPress settings
    }

    /**
     * Sets all hooks
     */
    public function setHooks()
    {
        //filters needed
        add_filter('woocommerce_billing_fields', [$this, 'woocommerceBillingFields'], 10, 1); // GENERAL: Add field to billing address fields
        add_filter('woocommerce_admin_billing_fields', [$this, 'woocommerceAdminBillingFields']); // ADMIN: Add field to order page
        add_filter('woocommerce_customer_meta_fields', [$this, 'woocommerceCustomerMetaFields']); // ADMIN: Add field to user edit page
        add_filter('woocommerce_ajax_get_customer_details', [$this, 'woocommerceAjaxGetCustomerDetails'], 10, 2); // ADMIN:Add field to ajax billing get_customer_details
        add_filter('woocommerce_api_order_response', [$this, 'woocommerceApiOrderResponse'], 11, 2); // ADMIN: Add field to order when requested via API
        add_filter('woocommerce_api_customer_response', [$this, 'woocommerceApiCustomerResponse'], 10, 2); // ADMIN: Add field to customer when requested via API
        add_filter('woocommerce_order_get_formatted_billing_address', [$this, 'woocommerceOrderGetFormattedBillingAddress'], 10, 3); // Append vat field to billing address
        add_filter('plugin_action_links_' . plugin_basename(CONTRIBUINTE_CHECKOUT_PLUGIN_FILE), [$this, 'addActionLinks']); // Show settings link in plugins list

        //actions needed
        add_action('before_woocommerce_init', [$this, 'beforeWoocommerceInit']); // CORE: Confirm HPOS compatibility
        add_action('woocommerce_checkout_process', [$this, 'woocommerceCheckoutProcess']); // FRONT END: Verify VAT if set in settings
        add_action('woocommerce_after_save_address_validation', [$this, 'woocommerceAfterSaveAddressValidation'], 10, 3); // FRONT END: Verify VAT if set in settings
        add_action('woocommerce_admin_order_data_after_billing_address', [$this, 'woocommerceAdminOrderDataAfterBillingAddress']); // ADMIN: Show  vies information on admin order page under billing address.
        add_action('woocommerce_after_edit_account_address_form', [$this, 'woocommerceAfterEditAccountAddressForm']); // FRONT END: Show VIES information under addresses in my account page
        add_action('wp_footer', [$this, 'wpFooter']); // GENERAL: Draw in footer

        //deprecated stuff
        //add_filter('woocommerce_email_customer_details_fields', [$this, 'woocommerceEmailCustomerDetailsFields'], 10, 3); //FRONT END: Add vat field to the email template
        //add_action('woocommerce_order_details_after_customer_details', [$this, 'woocommerceOrderDetailsAfterCustomerDetails']); //FRONT END: Add VAT info to order report
    }

    /**
     * Renders the settings page
     * This method will be called when opening WooCommerce Contribuinte page under the tab Options
     */
    public function settingsPage()
    {
        $settings = new Settings();
        $settings->renderPage();
    }

    /**
     * Show settings link in plugins list
     * @param $links
     * @return array
     */
    public function addActionLinks($links)
    {
        $links[] = '<a href="' . admin_url('admin.php?page=contribuintecheckout') . '">' . __('Settings', 'contribuinte-checkout') . '</a>';

        return $links;
    }

    /**
     * Validates the VAT number
     * Only validates Portuguese vat number
     * @param string $vat vat number
     * @return bool
     */
    public function validateVat($vat)
    {
        if (preg_match('/^[123456789]\d{8}$/', $vat)) {
            $sum = 0;

            for ($i = 0; $i < 9; $i++) {
                $sum += $vat[$i] * (10 - ($i + 1));
            }

            if ((int)$vat[8] === 0) {
                if (($sum % 11) !== 0) {
                    $sum += 10;
                }
            }

            if (($sum % 11) !== 0) {
                return false;
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     * Add field to billing address fields
     *
     * @param array $fields
     * @return array
     */
    public function woocommerceBillingFields($fields)
    {
        $settings = get_option($this->settingsOptionsName);

        // We should only apply settings if settings are already defined
        if (is_array($settings)) {
            $label = empty($settings['text_box_vat_field_label']) ? __('VAT', 'contribuinte-checkout') : $settings['text_box_vat_field_label'];
            $placeholder = empty($settings['text_box_vat_field_description']) ? __('VAT Number', 'contribuinte-checkout') : $settings['text_box_vat_field_description'];
            $isRequired = (int)$settings['drop_down_is_required'];
            $isRequiredOverLimit = (int)$settings['drop_down_required_over_limit_price'];
            $validateVat = (bool)$settings['drop_down_validate_vat'];
        } else {
            $label = __('VAT', 'contribuinte-checkout');
            $placeholder = __('VAT Number', 'contribuinte-checkout');
            $isRequired = 0;
            $isRequiredOverLimit = 0;
            $validateVat = false;
        }

        // If hook is called during checkout and is required over limit
        if ($isRequiredOverLimit > 0 && is_checkout()) {
            $orderValue = WC()->cart->get_total('hook');

            if ($orderValue > 1000) {
                $isRequired = 1;
            }
        }

        if ($validateVat) {
            $this->injectValidationInFooter = true;
        }

        $fields['billing_vat'] = [
            'type' => 'text',
            'label' => $label,
            'placeholder' => $placeholder,
            'required' => $isRequired,
            'autocomplete' => 'on',
            'priority' => 120,
            'maxlength' => 20,
            'validate' => false,
            'class' => []
        ];

        return $fields;
    }

    /**
     * Add field to order page
     *
     * @param $billingFields
     *
     * @return array
     */
    public function woocommerceAdminBillingFields($billingFields)
    {
        global $post;

        $isLegacyOrderType = !empty($post) && ($post->post_type === 'shop_order' || $post->post_type === 'shop_subscription');

        if (Context::isNewOrdersSystemEnabled() || $isLegacyOrderType) {
            $settings = get_option($this->settingsOptionsName);

            if (!empty($settings) && isset($settings['text_box_vat_field_label']) && !empty($settings['text_box_vat_field_label'])) {
                $settingsLabel = $settings['text_box_vat_field_label'];
            } else {
                $settingsLabel = __('VAT', 'contribuinte-checkout');
            }

            if (empty($billingFields)) {
                $billingFields = [];
            }

            $billingFields['vat'] = [
                'label' => $settingsLabel
            ];
        }

        return $billingFields;
    }

    /**
     * Add field to user edit page
     * @param $profileFields
     * @return array
     */
    public function woocommerceCustomerMetaFields($profileFields)
    {
        $settings = get_option($this->settingsOptionsName);

        if (isset($profileFields['billing']) && is_array($profileFields['billing']['fields'])) {
            $profileFields['billing']['fields']['billing_vat'] = [
                'label' => empty($settings['text_box_vat_field_label']) ? __('VAT', 'contribuinte-checkout') : $settings['text_box_vat_field_label'],
                'description' => empty($settings['text_box_vat_field_description']) ? __('VAT Number', 'contribuinte-checkout') : $settings['text_box_vat_field_description']
            ];
        }
        return $profileFields;
    }

    /**
     * Add field to ajax billing get_customer_details
     * @param $data
     * @param $customer
     * @return array
     */
    public function woocommerceAjaxGetCustomerDetails($data, $customer)
    {
        if ((isset($data['billing']['country']))) {
            $data['billing']['vat'] = $customer->get_meta('billing_vat');
        }

        return $data;
    }

    /**
     * Add field to order when requested via API
     * @param $orderData
     * @param $order
     * @return array
     */
    public function woocommerceApiOrderResponse($orderData, $order)
    {
        if (isset($orderData['billing_address'])) {
            $billingVat = $order->get_meta('_billing_vat');
            $orderData['billing_address']['vat'] = $billingVat;
        }

        return $orderData;
    }

    /**
     * Add field to customer when requested via API
     * @param $customerData
     * @param $customer
     * @return array
     */
    public function woocommerceApiCustomerResponse($customerData, $customer)
    {
        if (isset($customerData['billing_address'])) {
            $billingVat = $customer->get_meta('_billing_vat');
            $customerData['billing_address']['vat'] = $billingVat;
        }

        return $customerData;
    }

    /**
     * Append vat field to billing address
     *
     * @param string $address Formatted billing address
     * @param array $rawAddress Billing address data
     * @param WC_Order $order Woocommerce order class
     *
     * @return string
     */
    public function woocommerceOrderGetFormattedBillingAddress($address, $rawAddress, $order)
    {
        $vat = $order->get_meta('_billing_vat');
        $settings = get_option($this->settingsOptionsName);

        if (!empty($settings) && isset($settings['text_box_vat_field_label']) && !empty($settings['text_box_vat_field_label'])) {
            $settingsLabel = $settings['text_box_vat_field_label'];
        } else {
            $settingsLabel = __('VAT', 'contribuinte-checkout');
        }

        if (!empty($vat)) {
            if (empty($address)) {
                $address = '';
            }

            $address .= '<br>';
            $address .= $settingsLabel;
            $address .= ': ';
            $address .= $vat;
        }

        return $address;
    }

    /**
     * Set plugin as HPOS compatible
     *
     * @see https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book#declaring-extension-incompatibility
     *
     * @return void
     */
    public function beforeWoocommerceInit()
    {
        if (class_exists(FeaturesUtil::class)) {
            FeaturesUtil::declare_compatibility('custom_order_tables', CONTRIBUINTE_CHECKOUT_PLUGIN_FILE, true);
        }
    }

    /**
     * Verify VAT if set in settings when order is in checkout
     */
    public function woocommerceCheckoutProcess()
    {
        $settings = get_option($this->settingsOptionsName);

        $validateVat = (bool)$settings['drop_down_validate_vat'];
        $isRequired = (bool)$settings['drop_down_is_required'];
        $validationFail = (bool)$settings['drop_down_on_validation_fail'];

        if ($validateVat) {
            $billingVAT = sanitize_text_field(isset($_POST['billing_vat']) ? $_POST['billing_vat'] : '');
            $billingCountry = sanitize_text_field(WC()->customer->get_billing_country());

            if ($billingCountry === 'PT') {
                if (($billingVAT === '' && $isRequired === false) || $this->validateVat($billingVAT)) {
                    //Validation passed
                } else {
                    $identifier = [
                        'id' => 'billing_vat'
                    ];

                    if ((int)$validationFail === 0) {
                        //add error
                        wc_add_notice(__('You have entered an invalid VAT.', 'contribuinte-checkout'), 'error', $identifier);
                    } else {
                        //ads notice
                        wc_add_notice(__('You have entered an invalid VAT.', 'contribuinte-checkout'), 'notice', $identifier);
                    }
                }
            }
        }
    }

    /**
     * Verify VAT if set in settings adter saving the billing address
     * @param $userId
     * @param $loadAddress
     * @param $address
     */
    public function woocommerceAfterSaveAddressValidation($userId, $loadAddress, $address)
    {
        $settings = get_option($this->settingsOptionsName);

        $validateVat = (bool)$settings['drop_down_validate_vat'];
        $isRequired = (bool)$settings['drop_down_is_required'];
        $validationFail = (bool)$settings['drop_down_on_validation_fail'];

        if (($loadAddress === 'billing') && $validateVat) {

            $billingVAT = sanitize_text_field(isset($_POST['billing_vat']) ? $_POST['billing_vat'] : '');
            $billingCountry = sanitize_text_field(isset($_POST['billing_country']) ? $_POST['billing_country'] : '');

            if ($billingCountry === 'PT') {
                if ($this->validateVat($billingVAT) || ($billingVAT === '' && $isRequired === false)) {
                    //Validation passed
                } else {
                    $identifier = [
                        'id' => 'billing_vat'
                    ];

                    if ((int)$validationFail === 0) {
                        //adds error
                        wc_add_notice(__('You have entered an invalid VAT.', 'contribuinte-checkout'), 'error', $identifier);
                    } else {
                        //adds notice
                        wc_add_notice(__('You have entered an invalid VAT.', 'contribuinte-checkout'), 'notice', $identifier);
                    }
                }
            }
        }
    }

    /**
     * Show VIES information under billing address in admin order page
     *
     * @param $order
     */
    public function woocommerceAdminOrderDataAfterBillingAddress($order)
    {
        $settings = get_option($this->settingsOptionsName);

        if (!empty($settings) && isset($settings['drop_down_show_vies'])) {
            $showVies = (bool)$settings['drop_down_show_vies'];
        } else {
            $showVies = false;
        }

        $vat = $order->get_meta('_billing_vat');
        $country = $order->get_billing_country();

        if ($showVies === false || empty($vat)) {
            return;
        }

        $vies = new Vies($country, $vat);
        $result = $vies->checkVat();

        $vies->getViesForAdminOrderDataAfterBillingAddress($result);
    }

    /**
     * Show VIES information under addresses in my account page
     */
    public function woocommerceAfterEditAccountAddressForm()
    {
        $vat = WC()->customer->get_meta('billing_vat');
        $country = WC()->customer->get_billing_country();
        $showVies = (bool)get_option($this->settingsOptionsName)['drop_down_show_vies'];

        if ($showVies === false || empty($vat)) {
            return;
        }

        $vies = new Vies($country, $vat);
        $result = $vies->checkVat();

        $vies->getViesForAfterEditAccountAddressForm($result);
    }

    /**
     * Draw in WordPress footer
     *
     * @return void
     */
    public function wpFooter()
    {
        if (!$this->injectValidationInFooter) {
            return;
        }

        if (!is_checkout()) {
            return;
        }

        ?>
        <script>
            if (jQuery) {
                jQuery(function ($) {
                    var contryCode = jQuery('select#billing_country');
                    var vatInput = jQuery('input#billing_vat');

                    if (!contryCode.length || !vatInput.length) {
                        return;
                    }

                    var wrapper = vatInput.closest('.form-row');

                    if (!wrapper.length) {
                        return;
                    }

                    function validateVatPT(number) {
                        if (number.length !== 9) {
                            return false;
                        }

                        if (!/^\d+$/.test(number)) {
                            return false;
                        }

                        var digits = number.split('').map(Number);
                        var sum = 0;

                        for (var i = 0; i < 8; i++) {
                            sum += digits[i] * (9 - i);
                        }

                        var rest = sum % 11;
                        var controlDigit = rest === 0 ? 0 : 11 - rest;

                        return controlDigit === digits[8];
                    }

                    function onInputChange() {
                        var value = vatInput.val().toString().trim();

                        if (contryCode.val() !== 'PT' || value === '') {
                            wrapper.removeClass('woocommerce-validated');

                            if (!wrapper.hasClass('validate-required')) {
                                wrapper.removeClass('woocommerce-invalid');
                            }

                            return;
                        }

                        if (validateVatPT(value)) {
                            wrapper
                                .removeClass('woocommerce-invalid')
                                .addClass('woocommerce-validated');
                        } else {
                            wrapper
                                .removeClass('woocommerce-validated')
                                .addClass('woocommerce-invalid');
                        }
                    }

                    vatInput.on('change validate', function () {
                        setTimeout(onInputChange, 100);
                    });
                });
            }
        </script>
        <?php
    }

    //      deprecated      //

    /**
     * Add vat field to the email template
     *
     * @param $array
     * @param $sendToAdmin
     * @param $order
     *
     * @return array
     */
    public function woocommerceEmailCustomerDetailsFields($array, $sendToAdmin, $order)
    {
        $vat = $order->get_meta('_billing_vat');
        $settingsLabel = get_option($this->settingsOptionsName)['text_box_vat_field_label'];

        if (!empty($vat)) {
            $array['billing']['billing_vat'] = [
                'label' => empty($settingsLabel) ? __('VAT', 'contribuinte-checkout') : $settingsLabel,
                'value' => $vat
            ];
        }

        return $array;
    }

    /**
     * Add VAT info to order report (thank you page)
     *
     * @param $order
     *
     * @return void
     */
    public function woocommerceOrderDetailsAfterCustomerDetails($order)
    {
        $settings = get_option($this->settingsOptionsName);

        $vat = $order->get_meta('_billing_vat');
        $settingsLabel = $settings['text_box_vat_field_label'];
        $showVies = (bool)$settings['drop_down_show_vies'];

        if (!empty($vat)) {
            echo empty($settingsLabel) ? __('VAT', 'contribuinte-checkout') : esc_html($settingsLabel);
            echo ': ' . esc_html($vat);
        }

        if ($showVies === false || empty($vat)) {
            return;
        }

        $country = $order->get_billing_country();
        $vies = new Vies($country, $vat);
        $result = $vies->checkVat();

        $vies->getViesForOrderDetailsAfterCustomerDetails($result);
    }
}
