<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WC_Checkout;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * WooCommerce Checkout.
 */
class Checkout implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @param Settings $settings
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings)
    {
        $this->settings = $settings;
    }
    /**
     * Fires hooks
     */
    public function hooks()
    {
        \add_action('woocommerce_after_order_notes', [$this, 'add_wpml_user_session_lang']);
        \add_action('woocommerce_checkout_update_order_meta', [$this, 'save_wpml_user_session_lang']);
        \add_action('woocommerce_checkout_update_user_meta', [$this, 'save_customer_vat_field'], 10, 2);
        if ('yes' === $this->settings->get('woocommerce_add_nip_field')) {
            \add_action('woocommerce_checkout_process', [$this, 'validate_vat_number']);
            \add_action('woocommerce_after_checkout_validation', [$this, 'should_validate_nip'], 10, 2);
        }
    }
    /**
     * @param array     $data   Post data.
     * @param \WP_Error $errors Checkout errors.
     *
     * @return mixed
     */
    public function should_validate_nip($data, $errors)
    {
        if (isset($data['billing_invoice_ask'])) {
            $is_nip_required = 'yes' === $this->settings->get('woocommerce_add_nip_field');
            $invoice_ask = (string) $data['billing_invoice_ask'];
            if ($invoice_ask !== '1' && $is_nip_required) {
                if ($errors instanceof \WP_Error && $errors->has_errors()) {
                    $errors->remove('billing_vat_number_required');
                }
            }
        }
        return $data;
    }
    /**
     * Update customer vat number.
     *
     * @param int   $user_id   User ID.
     * @param array $post_data Post data.
     *
     * @internal You should not use this directly from another application
     */
    public function save_customer_vat_field($user_id, $post_data)
    {
        if ($user_id && isset($post_data['billing_vat_number'])) {
            \update_user_meta($user_id, 'vat_number', \sanitize_text_field($post_data['billing_vat_number']));
        }
    }
    /**
     * @internal You should not use this directly from another application
     */
    public function validate_vat_number()
    {
        $vat_number = isset($_POST['billing_vat_number']) ? \trim(\sanitize_text_field(\wp_unslash($_POST['billing_vat_number']))) : '';
        // phpcs:ignore
        if ($vat_number && $this->settings->get('woocommerce_validate_nip') === 'yes' && !\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\ValidateVatNumber::is_valid($vat_number)) {
            $country = \WC()->customer->get_billing_country();
            $woocommerce_default_country = \get_option('woocommerce_default_country', 0);
            if ($woocommerce_default_country === $country && !\in_array($woocommerce_default_country, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\ValidateVatNumber::COUNTRY_ISO_SLUG, \true)) {
                // Translators: %s vat number label.
                \wc_add_notice(\sprintf(\esc_html__('Please enter a valid %s. Do not enter hyphens or spaces. Optionally add country prefix (EU VAT Number).', 'flexible-invoices'), $this->settings->get('woocommerce_nip_label')), 'error');
            } else {
                // Translators: %s vat number label.
                \wc_add_notice(\sprintf(\esc_html__('Please enter a valid %s without hyphens and spaces, with valid country prefix (EU VAT Number).', 'flexible-invoices'), $this->settings->get('woocommerce_nip_label')), 'error');
            }
        }
    }
    /**
     * @param WC_Checkout $checkout
     *
     * @internal You should not use this directly from another application
     */
    public function add_wpml_user_session_lang($checkout)
    {
        if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::is_wpml_active()) {
            global $sitepress;
            echo '<input type="hidden" class="input-hidden" name="wpml_user_lang" id="wpml_user_lang" value="' . \esc_html($sitepress->get_current_language()) . '">';
        }
    }
    /**
     * @param int $order_id
     *
     * @internal You should not use this directly from another application
     */
    public function save_wpml_user_session_lang($order_id)
    {
        $wpml_user_lang = isset($_POST['wpml_user_lang']) ? \trim(\sanitize_text_field(\wp_unslash($_POST['wpml_user_lang']))) : 'en';
        // phpcs:ignore
        if ($wpml_user_lang) {
            $order = \wc_get_order($order_id);
            $order->update_meta_data('wpml_user_lang', $wpml_user_lang);
            $order->save();
        }
    }
}
