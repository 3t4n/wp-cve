<?php
/**
 * @link https://www.invoice123.com
 * @package Saskaita123Plugin
 *
 * Class Description: Custom inputs for checkout
 */

declare(strict_types=1);

namespace S123\Includes\Pages;

use S123\Includes\Base\S123_Options;

if (!defined('ABSPATH')) exit;

class S123_Checkout
{
    use S123_Options;

    public function s123_register(): void
    {
        if ($this->s123_get_option('use_custom_inputs') === true) {
            add_filter('woocommerce_checkout_fields', array($this, 'custom_woocommerce_billing_fields'), PHP_INT_MAX);
            add_action('woocommerce_checkout_create_order', array($this, 'custom_checkout_field_update_meta'), 10, 2);
            add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'show_company_info_data'), 10, 1);
            add_action('wp_footer', array($this, 'checkout_display_inputs'));
        }
    }

    /*
     * Add custom inputs for checkout
     */
    public function custom_woocommerce_billing_fields($fields): array
    {
        $fields['billing']['_invoice_for_company'] = array(
            'label' => __('Need an invoice for a company? Please add company information', 's123-invoices'),
            'required' => false,
            'type' => 'checkbox',
            'id' => '_invoice_for_company',
            'class' => array('form-row-wide'),
            'priority' => 30,
        );

        if (!isset($fields['billing']['billing_company'])) {
            $fields['billing']['_billing_company_name'] = array(
                'label' => __('Company name', 's123-invoices'),
                'required' => false,
                'type' => 'text',
                'priority' => 30,
            );
        }

        $fields['billing']['_billing_company_code'] = array(
            'label' => __('Company code', 's123-invoices'),
            'required' => false,
            'type' => 'text',
            'priority' => 30,
        );

        $fields['billing']['_billing_company_vat_code'] = array(
            'label' => __('Vat code', 's123-invoices'),
            'required' => false,
            'type' => 'text',
            'priority' => 30,
        );

        return $fields;
    }

    /*
     * Validate custom checkout input and save
     */
    public function custom_checkout_field_update_meta($order): void
    {
        if (isset($_POST['_invoice_for_company'])) {
            $order->update_meta_data('_invoice_for_company', sanitize_text_field(true));
        }

        if (isset($_POST['_billing_company_name']) && !empty($_POST['_billing_company_name'])) {
            $order->update_meta_data('_billing_company_name', sanitize_text_field($_POST['_billing_company_name']));
        }

        if (isset($_POST['_billing_company_code']) && !empty($_POST['_billing_company_code'])) {
            $order->update_meta_data('_billing_company_code', sanitize_text_field($_POST['_billing_company_code']));
        }

        if (isset($_POST['_billing_company_vat_code']) && !empty($_POST['_billing_company_vat_code'])) {
            $order->update_meta_data('_billing_company_vat_code', sanitize_text_field($_POST['_billing_company_vat_code']));
        }
    }

    /*
    * Display custom company inputs data in admin order info
    */
    public function show_company_info_data($order): void
    {
        $order_id = $order->get_id();

        if (get_post_meta($order_id, '_invoice_for_company', true)) {
            $generateInvoice = get_post_meta($order_id, '_invoice_for_company', true) === '1' ? __('Yes', 's123-invoices') : __('No', 's123-invoices');
            echo '<strong>' . __('Need an invoice for a company? Please add company information', 's123-invoices') . ':</strong> ' . $generateInvoice . '<br>';
        }

        if (get_post_meta($order_id, '_billing_company_name', true)) {
            echo '<strong>' . __('Company name', 's123-invoices') . ':</strong> ' . get_post_meta($order_id, '_billing_company_name', true) . '<br>';
        }

        if (get_post_meta($order_id, '_billing_company_code', true)) {
            echo '<strong>' . __('Company code', 's123-invoices') . ':</strong> ' . get_post_meta($order_id, '_billing_company_code', true) . '<br>';
        }

        if (get_post_meta($order_id, '_billing_company_vat_code', true)) {
            echo '<strong>' . __('Vat code', 's123-invoices') . ':</strong> ' . get_post_meta($order_id, '_billing_company_vat_code', true) . '<br>';
        }
    }

    /*
    * Show custom inputs after checkbox is ticked in checkout
    */
    public function checkout_display_inputs(): void
    {
        // Only on Checkout
        if (is_checkout() && !is_wc_endpoint_url()) :
            ?>
            <script type="text/javascript">
                jQuery(function ($) {
                    const companyNameInput = $("#_billing_company_name_field");
                    const companyCodeInput = $("#_billing_company_code_field");
                    const companyVatCodeInput = $("#_billing_company_vat_code_field");
                    companyNameInput.hide();
                    companyCodeInput.hide();
                    companyVatCodeInput.hide();

                    $('#_invoice_for_company').on('click', function () {
                        if (this.checked) {
                            companyNameInput.show();
                            companyCodeInput.show();
                            companyVatCodeInput.show();
                        } else {
                            companyNameInput.hide();
                            companyCodeInput.hide();
                            companyVatCodeInput.hide();
                        }
                    });
                });
            </script>
        <?php
        endif;
    }
}