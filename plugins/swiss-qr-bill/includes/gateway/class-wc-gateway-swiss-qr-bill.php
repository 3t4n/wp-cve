<?php
if ( !defined('ABSPATH') ) {
    exit; // Exit if accessed directly.
}

/**
 * The Payment gateway class
 *
 * @since      1.0.0
 *
 * @package    WC_Swiss_Qr_Bill
 * @subpackage WC_Swiss_Qr_Bill/includes/gateway
 */
class WC_Gateway_Swiss_Qr extends WC_Gateway_Swiss_Qr_Base {

    public $title;
    public $descriptions;
    public $instructions;
    public $enable_for_methods;

    protected $invoiceGenerate;

    /**
     * Constructor for the gateway.
     */
    public function __construct() {
        parent::__construct();
        $this->validation_success_msg = __('QR IBAN successfully validated.', 'swiss-qr-bill');
    }

    /**
     * Setup general properties for the gateway.
     */
    protected function setup_properties() {
        $this->id = 'wc_swiss_qr_bill';
        $this->method_title = __('Swiss QR Bill for WooCommerce WITH QR-IBAN', 'swiss-qr-bill');
        $this->method_description = __('Allow your clients to pay conveniently with Swiss QR bills.', 'swiss-qr-bill');
        $this->has_fields = false;
    }

    /**
     * Initialise Gateway Settings Form Fields.
     */
    public function init_form_fields() {
        $this->form_fields = include 'settings-wc-swiss-qr-bill.php';
    }

}
