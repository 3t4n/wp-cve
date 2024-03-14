<?php

/**
 * The WC_OfficeGuy Class
 *
 * @since 1.0
 */
function officeguybit_woocommerce_gateway()
{
    if (!class_exists("WC_Payment_Gateway"))
        return;

    class WC_OfficeGuyBit extends WC_Payment_Gateway
    {
        function __construct()
        {
            $this->id = 'officeguybit';
            $this->init_settings();
            $this->method_title = 'SUMIT bit';
            $this->method_description = __('Receive bit payments using SUMIT.', 'officeguy');
            $this->icon = PLUGIN_DIR . 'includes/images/bit.png';
            $this->has_fields = true;
            if (!empty($this->settings['title']))
                $this->title = $this->settings['title'];
            OfficeGuySettings::InitBitFormFields($this);

            $this->supports = array('products');

            add_action('woocommerce_receipt_officeguy', array($this, 'ReceiptPage'));
            add_action('woocommerce_update_options_payment_gateways', array($this, 'process_admin_options'));
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            //add_action('woocommerce_payment_complete', 'OfficeGuyPayment::CreateDocumentOnPaymentComplete', 10, 1);
            add_action('woocommerce_scheduled_subscription_payment_' . $this->id, array($this, 'ProcessSubscriptionPayment'), 10, 2);

            if (!OfficeGuyPayment::IsCurrencySupported())
                $this->enabled = 'no';
            OfficeGuyStock::CreateSchedules($this);
        }
   
        function admin_options()
        { ?>
            <h3><?php echo __('SUMIT Payments - bit', 'officeguy') ?></h3>
            <p>
                <?php echo __('The SUMIT BitPayments Gateway is a simple and powerful checkout solution.<br />The plugin adds an option to pay using bit on the checkout page for Upay customers, and processes the transaction on SUMIT.<br />Please follow the <a target="_blank" href="https://help.sumit.co.il/he/articles/5830000">installation instructions</a> to complete the plugin setup.', 'officeguy') ?>
            </p>
            <table class="form-table">
                <?php $this->generate_settings_html(); ?>
            </table>
            <?php
        }

        function payment_fields()
        {
            if ($this->settings['description'])
            { ?>
                <p><?php echo $this->settings['description']; ?></p>
<?php
            }
        }

        function process_payment($OrderID)
        {
            $Order = wc_get_order($OrderID);
            return OfficeGuyPayment::ProcessBitOrder($this, $Order);
        }

        function ReceiptPage($Order)
        {
            echo '<p>' . __('Thank you for your order.', 'officeguy') . '</p>';
        }

        public static function AddPaymentGateway($Methods)
        {
            $Methods[] = 'WC_OfficeGuyBit';
            return $Methods;
        }

        public static function ProcessIPN()
        {
            $OrderID = OfficeGuyRequestHelpers::Get("orderid");
            $OrderKey = OfficeGuyRequestHelpers::Get("orderkey");
            $DocumentID = OfficeGuyRequestHelpers::Post("documentid");
            $CustomerID = OfficeGuyRequestHelpers::Post("customerid");

            $Order = wc_get_order($OrderID);
            if ($Order->get_order_key() != $OrderKey)
            {
                OfficeGuyAPI::WriteToLog("Received IPN with incorrect key " . $OrderID, "debug");
                return;
            }
            if ($Order->get_status() != "pending")
            {
                OfficeGuyAPI::WriteToLog("Received IPN for non-pending order " . $OrderID, "debug");
                return;
            }

            OfficeGuyAPI::WriteToLog("Processing IPN for order " . $OrderID, "debug");
            $Remark = __('SUMIT order completed. Document ID: %s.', 'officeguy');
            $Remark = sprintf($Remark, $DocumentID);

            $Order->add_order_note($Remark);
            $Order->add_meta_data('OfficeGuyDocumentID', $DocumentID);
            $Order->add_meta_data('OfficeGuyCustomerID', $CustomerID);
            $Order->payment_complete();
            $Order->save_meta_data();
            $Order->save();
        }
    }
}
add_action('plugins_loaded', 'officeguybit_woocommerce_gateway', 0);
add_filter('woocommerce_payment_gateways', 'WC_OfficeGuyBit::AddPaymentGateway');
add_action('woocommerce_api_officeguybit_woocommerce_gateway', 'WC_OfficeGuyBit::ProcessIPN');
