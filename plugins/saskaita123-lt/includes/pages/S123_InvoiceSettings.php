<?php
/**
 * @link https://www.invoice123.com
 * @package Saskaita123Plugin
 */

namespace S123\Includes\Pages;

use S123\Includes\Base\S123_BaseController;
use S123\Includes\Helpers\S123_ResponseHelpers;

if (!defined('ABSPATH')) exit;

class S123_InvoiceSettings extends S123_BaseController
{
    public function s123_register()
    {
        add_action('wp_ajax_s123_submit_invoice_settings', array($this, 's123_submit_invoice_settings'));
    }

    /*
     * Validate and save api settings
     */
    public function s123_submit_invoice_settings()
    {
        if (isset($_POST['s123_security']) && wp_verify_nonce($_POST['s123_security'], 's123_security')) {
            $keys = ['use_custom_inputs', 'use_order_status'];
            $data = [];
            if (isset($_POST['api_vats'])) {
                // map woocommerce vat with app.invoice123.com vat
                $this->mapVats($_POST['api_vats']);
            }

            foreach ($keys as $key) {
                $data[$key] = isset($_POST[$key]) ? sanitize_text_field(trim($_POST[$key])) : null;
            }

            $data['use_custom_inputs'] = isset($_POST['use_custom_inputs']);

            $this->saveInvoiceSettings($data);

            S123_ResponseHelpers::s123_sendSuccessResponse(__('API Settings saved successfully!', 's123-invoices'));
        } else {
            S123_ResponseHelpers::s123_sendErrorResponse(__('Invalid secret key specified.', 's123-invoices'));
        }
    }

    public function mapVats($vats)
    {
        foreach ($vats as $vat) {
            $vat = sanitize_text_field(trim($vat));
            // [0] api vat id, [1] woo tax vat id
            $vat = explode('-', $vat);

            global $wpdb;
            $tableName = $wpdb->prefix . "woocommerce_tax_rates";
            $wpdb->query($wpdb->prepare("UPDATE {$tableName} SET s123_tax_id='$vat[0]' WHERE tax_rate_id='$vat[1]'"));
        }
    }

    public function saveInvoiceSettings($data)
    {
        $options = array_merge($this->s123_get_options(), $data);
        $this->s123_update_options($options);
    }
}