<?php
if (!defined('ABSPATH')) {
    exit();
}

/**
 * Main class to generate the QR BILl
 *
 * @since      1.0.0
 *
 * @package    WC_Swiss_Qr_Bill
 * @subpackage WC_Swiss_Qr_Bill/includes/gateway
 */


use Sprain\SwissQrBill as QrBill;

require_once plugin_dir_path(WC_SWISS_QR_BILL_FILE) . '/includes/gateway/vendor/autoload.php';
require_once plugin_dir_path(WC_SWISS_QR_BILL_FILE) . 'includes/tcpdf/class-custom-tcpdf.php';

class WC_Swiss_Qr_Bill_Generate {
    private $gateway;
    public $invoice_data = array();
    public $invoice_pdf;
    protected $qrBill;

    private $pdf_mode = 'F';

    public function __construct($gateway) {

        // Create upload dir if not exist
        if (!is_dir(WC_SWISS_QR_BILL_UPLOAD_DIR)) {
            mkdir(WC_SWISS_QR_BILL_UPLOAD_DIR, 0700);
        }

        $this->gateway = $gateway;
        $this->get_data();

        add_action('invoice_generate', array($this, 'init'), 99, 2);
        add_filter('woocommerce_email_attachments', array($this, 'attach_invoice'), 10, 3);
    }

    /**
     * Get the settings
     */
    protected function get_data() {
        $this->invoice_data['options'] = [
            'shop_logo' => $this->gateway->get_option('shop_logo'),
            'shop_name' => $this->gateway->get_option('shop_name'),
            'shop_street_address_1' => $this->gateway->get_option('shop_street_address_1'),
            'shop_address_2' => $this->gateway->get_option('shop_address_2'),
            'shop_zipcode' => $this->gateway->get_option('shop_zipcode'),
            'shop_city' => $this->gateway->get_option('shop_city'),
            'shop_telephone' => $this->gateway->get_option('shop_telephone'),
            'shop_email' => $this->gateway->get_option('shop_email'),
            'shop_vat_number' => $this->gateway->get_option('shop_vat_number'),
            'qr_iban' => $this->gateway->get_option('qr_iban'),
            'classic_iban' => $this->gateway->get_option('classic_iban'),
            'customer_identification_number' => $this->gateway->get_option('customer_identification_number'),
            'footer_text' => $this->gateway->get_option('footer_text'),
        ];;
    }

    /**
     * @param $gateway_id
     *
     * @return array
     */
    public function gateway_field_empty_validation($gateway_id) {
        $errors = array();
        // common required fields
        $required_fields = array(
            'shop_name' => __('Your shop name is empty. Please enter your shop name.', 'swiss-qr-bill'),
            'shop_street_address_1' => __('Your shop street address is empty. Please enter your street.', 'swiss-qr-bill'),
            'shop_zipcode' => __('Your shop zip code is empty. Please enter your zip code.', 'swiss-qr-bill'),
            'shop_city' => __('Your shop city is empty. Please enter your shop city.', 'swiss-qr-bill'),
        );

        // Payment gateway specific required fields
        if ($gateway_id == 'wc_swiss_qr_bill') {
            $required_fields['qr_iban'] = __('Your QR-IBAN is empty. Please enter your QR-IBAN.', 'swiss-qr-bill');
        } elseif ($gateway_id == 'wc_swiss_qr_bill_classic') {
            $required_fields['classic_iban'] = __('Your IBAN is empty. Please enter your IBAN.', 'swiss-qr-bill');

        }

        foreach ($required_fields as $key => $error_msg) {
            if ($this->invoice_data['options'][$key] == '') {
                $errors[] = $error_msg;
            }
        }

        return $errors;
    }

    /**
     * Function to check for the validity of customer data for Qr code generation
     * Ultimately to be used for disabling the Payment Gateway
     *
     * @param $gateway
     * @param bool $notice
     *
     * @return bool|string|void
     */
    public function is_data_valid_for_qr_bill($gateway, $notice = false) {
        // Get payment gateway setting
        $swiss_qr_fields = $this->invoice_data['options'];
        $qrBillValidityCheck = QrBill\QrBill::create();

        try {
            $qrBillValidityCheck->setCreditor(
                QrBill\DataGroup\Element\CombinedAddress::create(
                    $swiss_qr_fields['shop_name'],
                    $swiss_qr_fields['shop_street_address_1'],
                    $swiss_qr_fields['shop_zipcode'] . ' ' . $swiss_qr_fields['shop_city'],
                    self::get_store_country()
                ));

            // set the creditor information based chosen payment gateway (classic or QR IBAN)
            $qrBillValidityCheck->setCreditorInformation(
                QrBill\DataGroup\Element\CreditorInformation::create(
                    $this->get_iban()
                ));

            // Add payment amount information
            // What amount is to be paid?
            $qrBillValidityCheck->setPaymentAmountInformation(
                QrBill\DataGroup\Element\PaymentAmountInformation::create(
                    get_woocommerce_currency(),
                    '1.23' //For Testing
                ));

            // Add payment reference
            // This is what you will need to identify incoming payments
            if ($gateway == 'wc_swiss_qr_bill') {
                $referenceNumber = QrBill\Reference\QrPaymentReferenceGenerator::generate(
                    $swiss_qr_fields['customer_identification_number'],  // you receive this number from your bank
                    '99' // For testing
                );
                $qrBillValidityCheck->setPaymentReference(
                    QrBill\DataGroup\Element\PaymentReference::create(
                        QrBill\DataGroup\Element\PaymentReference::TYPE_QR,
                        $referenceNumber
                    ));
            } elseif ($gateway == 'wc_swiss_qr_bill_classic') {
                // Add payment reference
                // Explicitly define that no reference number will be used by setting TYPE_NON.
                $qrBillValidityCheck->setPaymentReference(
                    QrBill\DataGroup\Element\PaymentReference::create(
                        QrBill\DataGroup\Element\PaymentReference::TYPE_NON
                    ));
            }

            $qrBillValidityCheck->getQrCode();

            return true;
        } catch (Exception $e) {

            $error = false;
            if ($notice) {

                foreach ($qrBillValidityCheck->getViolations() as $violation) {
                    $error_group = $violation->getConstraint()->groups['1'];

                    switch ($error_group) {
                        case 'CombinedAddress':
                            $error = __('Your address could not be validated. Please enter a correct shop address.', 'swiss-qr-bill');
                            break;
                        case 'CreditorInformation':
                        case 'QrBill':
                            $error = __('Your QR IBAN or Reference number could not be validated. Please enter a correct combination of QR IBAN and Reference number.', 'swiss-qr-bill');

                            if ($gateway == 'wc_swiss_qr_bill_classic') {
                                $error = __('Your IBAN number could not be validated. Please enter a correct IBAN.', 'swiss-qr-bill');

                            }
                            break;
                        default:
                            $error = false;
                            break;
                    }

                    break;
                }

                return $error;
            }

            return false;
        }
    }

    /**
     * Initialize the invoice Qr code generate
     * $pre_data is for invoice generation via admin metabox
     *
     * @param $order_id
     * @param $pre_data
     */
    public function init($order_id, $pre_data = null) {

        //Manual generation of PDF
        if (isset($_GET['_wpnonce'])) {
            if (wp_verify_nonce(sanitize_text_field($_GET['_wpnonce']), 'generate_invoice')) {
                if ($pre_data != null) {
                    $this->invoice_data['options'] = $pre_data;
                }
                $this->pdf_mode = 'I';
            }
        }


        $order = wc_get_order($order_id);
        if (!$order) {
            return false;
        }


        $payment_method = $order->get_payment_method();
        if (empty($this->gateway_field_empty_validation($payment_method)) && $this->is_data_valid_for_qr_bill($payment_method)) {
            $this->qr_bill_generate($order, $payment_method);
        }
    }

    /**
     * QR bill code generate
     *
     * @param $order
     * @param $payment_method
     *
     * @return bool
     */
    public function qr_bill_generate($order, $payment_method) {
        $order_id = $order->get_id();

        if (!$this->should_attach_invoice_pdf($order)) {
            return false;
        }
        // Get payment gateway setting
        $swiss_qr_fields = $this->invoice_data['options'];

        // Time to output something!
        try {
            $qrBill = QrBill\QrBill::create();

            // Add creditor information
            // Who will receive the payment and to which bank account?
            $qrBill->setCreditor(
                QrBill\DataGroup\Element\CombinedAddress::create(
                    $swiss_qr_fields['shop_name'],
                    $swiss_qr_fields['shop_street_address_1'],
                    $swiss_qr_fields['shop_zipcode'] . ' ' . $swiss_qr_fields['shop_city'],
                    self::get_store_country()
                ));

            $qrBill->setCreditorInformation(
                QrBill\DataGroup\Element\CreditorInformation::create(
                    $this->get_iban()
                ));

            // Add debtor information
            // Who has to pay the invoice? This part is optional

            $qrBill->setUltimateDebtor(
                QrBill\DataGroup\Element\StructuredAddress::createWithStreet(
                    $order->get_billing_company() ? $order->get_formatted_billing_full_name() . ', ' . $order->get_billing_company() : $order->get_formatted_billing_full_name(),
                    $order->get_billing_address_1(),
                    '',
                    $order->get_billing_postcode(),
                    substr($order->get_billing_city(), 0, 32),
                    $order->get_billing_country()
                ));

            // Add payment amount information
            // What amount is to be paid?
            $qrBill->setPaymentAmountInformation(
                QrBill\DataGroup\Element\PaymentAmountInformation::create(
                    get_woocommerce_currency(),
                    $order->get_total()
                ));

            // Add payment reference
            // This is what you will need to identify incoming payments.
            if ($payment_method == 'wc_swiss_qr_bill') {
                $referenceNumber = QrBill\Reference\QrPaymentReferenceGenerator::generate(
                    $swiss_qr_fields['customer_identification_number'],  // you receive this number from your bank
                    $order_id // a number to match the payment with your other data, e.g. an invoice number
                );
                $qrBill->setPaymentReference(
                    QrBill\DataGroup\Element\PaymentReference::create(
                        QrBill\DataGroup\Element\PaymentReference::TYPE_QR,
                        $referenceNumber
                    ));
            } elseif ($payment_method == 'wc_swiss_qr_bill_classic') {
                // Add payment reference
                // Explicitly define that no reference number will be used by setting TYPE_NON.
                $referenceNumber = $order->get_order_number();
                $qrBill->setPaymentReference(
                    QrBill\DataGroup\Element\PaymentReference::create(
                        QrBill\DataGroup\Element\PaymentReference::TYPE_NON
                    ));
            }

            $this->invoice_data['options']['referenceNumber'] = $referenceNumber;

            $invoice_date = WC_Swiss_Qr_Bill_Generate::get_formatted_date($order->get_date_created());

            $qrBill->setAdditionalInformation(
                QrBill\DataGroup\Element\AdditionalInformation::create(
                    __('Invoice', 'swiss-qr-bill') . ' #' . $order->get_order_number() . ', ' . $invoice_date
                )
            );

            $filename = $this->get_qr_image_filename($order);
            $qrBill->getQrCode()->writeFile(WC_SWISS_QR_BILL_UPLOAD_DIR . $filename . '.png');
            $qrBill->getQrCode()->writeFile(WC_SWISS_QR_BILL_UPLOAD_DIR . $filename . '.svg');
            $this->invoice_data['options']['qr_image_name'] = $filename . '.png';

            // send upload dir as variable as well for consistency of path
            $this->invoice_generate($order, $qrBill);

        } catch (Exception $e) {
            return false;
//            foreach ( $qrBill->getViolations() as $violation ) {
//                print $violation->getMessage() . "\n";
//            }
//            exit;
        }
    }

    /**
     * PDF invoice generate
     *
     * @param $order
     * @param $qrBill
     */
    public function invoice_generate($order, $qrBill) {
        $tcPdf = new Custom_TCPDF($order, PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false);

        $tcPdf->SetTitle('Invoice ' . $order->get_order_number());

        // set default header data
        $tcPdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        //set header and footer fonts
        $tcPdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $tcPdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $tcPdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $tcPdf->setPrintHeader(true);
        $tcPdf->setPrintFooter(false);
        $tcPdf->AddPage();

        $gateway_options = $this->invoice_data['options']; // This variable to be used in include template

        // shop header
        ob_start();
        include plugin_dir_path(WC_SWISS_QR_BILL_FILE) . 'includes/tcpdf/templates/parts/shop-header.php';
        $shop_header = ob_get_clean();
        $tcPdf->writeHTML($shop_header, true, false, true, false, '');

        // customer address
        ob_start();
        include plugin_dir_path(WC_SWISS_QR_BILL_FILE) . 'includes/tcpdf/templates/parts/customer-address.php';
        $customer_address = ob_get_clean();
        $tcPdf->writeHTML($customer_address, true, false, true, false, '');

        // Order items header
        ob_start();
        include plugin_dir_path(WC_SWISS_QR_BILL_FILE) . 'includes/tcpdf/templates/parts/order-items-header.php';
        $order_items_header = ob_get_clean();
        $tcPdf->writeHTML($order_items_header, true, false, true, false, '');
        $tcPdf->Ln(1);

        // Calculate max allowed number of order items row
        $threshold = 170;
        $max = floor(($threshold - $tcPdf->GetY()) / 5.64);
        $order_items_chunk = $this->get_order_items_table_chunk($order, $max);
        $table_data = $order_items_chunk['paged1'];

        ob_start();
        include plugin_dir_path(WC_SWISS_QR_BILL_FILE) . 'includes/tcpdf/templates/parts/order-items.php';
        $order_items = ob_get_clean();
        $tcPdf->writeHTML($order_items, true, false, true, false, '');


        $output = new QrBill\PaymentPart\Output\TcPdfOutput\TcPdfOutput($qrBill, $this->get_langauge_code(), $tcPdf);
        $output
            ->setPrintable(false)
            ->getPaymentPart();

        // If there is chunk of order items ; display in next page
        if (isset($order_items_chunk['paged2'])) {
            $tcPdf->AddPage();

            $table_data = $order_items_chunk['paged2'];

            ob_start();
            include plugin_dir_path(WC_SWISS_QR_BILL_FILE) . 'includes/tcpdf/templates/parts/order-items.php';
            $order_items = ob_get_clean();
            $tcPdf->writeHTML($order_items, true, false, true, false, '');

        }

        //Close and output PDF document
        $pdf_name = $this->get_invoice_pdf_name($order->get_id());
        try {
            $invoice_pdf = WC_SWISS_QR_BILL_UPLOAD_DIR . $pdf_name;
            $this->clean_qr_images($order);

            $tcPdf->Output($this->pdf_mode == 'F' ? $invoice_pdf : $pdf_name, $this->pdf_mode);
            $this->invoice_pdf = $invoice_pdf;

            update_post_meta($order->get_id(), '_wsqb_invoice_pdf_path', $pdf_name);
            update_post_meta($order->get_id(), '_wsqb_gateway_data', serialize($this->invoice_data['options']));
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }

    /**
     * Attach generated PDF to order confirmation email
     *
     * @param $attachments
     * @param $id
     * @param $order
     *
     * @return mixed
     */
    public function attach_invoice($attachments, $id, $order) {

        if (!is_a($order, 'WC_Order')) {
            return $attachments;
        }

        if (!$this->should_attach_invoice_pdf($order)) {
            return $attachments;
        }

        $allowed_statuses = array('customer_processing_order', 'customer_on_hold_order', 'customer_note', 'customer_invoice');

        if (isset($id) && in_array($id, $allowed_statuses)) {
            $file_name = get_post_meta($order->get_id(), '_wsqb_invoice_pdf_path', 'true');
            if (file_exists(WC_SWISS_QR_BILL_UPLOAD_DIR . $file_name)) {
                $attachments[] = WC_SWISS_QR_BILL_UPLOAD_DIR . $file_name;
            }
        }

        return $attachments;
    }

    /**
     * Helper function to generate the German / French formatted date
     *
     * @param $date
     *
     * @return String
     */
    public static function get_formatted_date($date) {
        if (in_array(get_locale(), array('fr_FR', 'fr_BE', 'fr_CA'))) {
            $fmt = datefmt_create("fr_BE", IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'Europe/Paris', IntlDateFormatter::GREGORIAN);
        } else {
            $fmt = datefmt_create("de_DE", IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'Europe/Berlin', IntlDateFormatter::GREGORIAN);
        }

        return datefmt_format($fmt, $date);
    }

    /** Function to get the chunks of order items table data
     *
     * @param $order
     * @param $max
     *
     * @return array
     */
    private function get_order_items_table_chunk($order, $max) {
        $item_types = [
            'line_item',
            'subtotal',
            'fee',
            'coupon',
            'shipping',
            'tax',
            'total'
        ];
        $wc_currency_args = array('currency' => $order->get_currency());

        $breakpoint = $max - 3;
        $order_items_chunk = array();
        $order_items_chunk['paged1'][] =
            array(
                'type' => 't_head',
                'td1' => '<b>' . __('Item', 'swiss-qr-bill') . '</b>',
                'td2' => '<b>' . __('Quantity', 'swiss-qr-bill') . '</b>',
                'td3' => '<b>' . __('Item Price', 'swiss-qr-bill') . '</b>',
                'td4' => '<b>' . __('Amount', 'swiss-qr-bill') . '</b>'
            );

        $i = 0;
        foreach ($item_types as $item_type) {
            switch ($item_type) {
                case 'subtotal':
                    $items = array(
                        array(
                            'key' => __('Subtotal', 'swiss-qr-bill'),
                            'value' => $order->get_subtotal()
                        )
                    );
                    break;

                case 'total':
                    $items = array(
                        array(
                            'key' => __('Total', 'swiss-qr-bill'),
                            'value' => $order->get_total()
                        )
                    );

                    break;
                case 'tax':
                    $items = $order->get_tax_totals();
                    break;
                default:
                    $items = $order->get_items($item_type);
                    break;
            }

            $j = 0;
            foreach ($items as $index => $item) {
                $i++;

                switch ($item_type) {
                    case 'line_item':
                        $type = $item_type;

                        if (count($items) - 1 == $j) {
                            $type = 't_head';
                        }
                        $td1 = $item->get_name();
                        $td2 = $item->get_quantity();
                        $td3 = wp_strip_all_tags(wc_price($item->get_data()['subtotal'] / $td2), $wc_currency_args);
                        $td4 = wp_strip_all_tags(wc_price($item->get_data()['subtotal'], $wc_currency_args));
                        $j++;
                        break;
                    case 'subtotal':
                    case 'total':
                        $type = $item_type;
                        $td1 = '';
                        $td2 = '';
                        $td3 = '<b>' . $item['key'] . '</b>';
                        $td4 = '<b>' . wc_price($item['value'], $wc_currency_args) . '</b>';
                        break;
                    case 'fee':
                        $type = $item_type;
                        $td1 = '';
                        $td2 = '';
                        $td3 = $item->get_name();
                        $td4 = wc_price($item->get_total(), $wc_currency_args);
                        break;
                    case 'coupon':
                        $type = $item_type;
                        $td1 = $td2 = '';
                        $td3 = __('Discount', 'swiss-qr-bill') . ' [ ' . $item->get_name() . ' ]';
                        $td4 = '-' . wc_price($item->get_discount(), $wc_currency_args);
                        break;
                    case 'shipping':
                        $type = $item_type;
                        $td1 = $td2 = '';
                        $td3 = __('Shipping', 'swiss-qr-bill') . '(' . $item->get_name() . ')';
                        $td4 = wc_price($item->get_data()['total'], $wc_currency_args);
                        break;
                    case 'tax':
                        $type = $item_type;
                        $td1 = $td2 = '';
                        $td3 = $item->label . ' ' . WC_Tax::get_rate_percent($item->rate_id);
                        $td4 = wc_price($item->amount, $wc_currency_args);
                        break;
                    default:

                }

                if ($i <= $breakpoint) {
                    $order_items_chunk['paged1'][] = array(
                        'type' => $type,
                        'td1' => $td1,
                        'td2' => $td2,
                        'td3' => $td3,
                        'td4' => $td4
                    );
                } else {
                    $order_items_chunk['paged2'][] = array(
                        'type' => $type,
                        'td1' => $td1,
                        'td2' => $td2,
                        'td3' => $td3,
                        'td4' => $td4
                    );
                }
            }


        }

        return $order_items_chunk;
    }

    /**
     * Function to get the shop store country
     *
     * @return mixed|string
     */
    public static function get_store_country() {
        $store_raw_country = get_option('woocommerce_default_country');
        $split_country = explode(":", $store_raw_country);

        return $split_country[0];
    }

    /**
     * Get the IBAN number
     *
     * @return |null
     */
    private function get_iban() {
        if ($this->gateway->id == 'wc_swiss_qr_bill') {
            return $this->gateway->get_option('qr_iban');
        } elseif ($this->gateway->id == 'wc_swiss_qr_bill_classic') {
            return $this->gateway->get_option('classic_iban');
        }

        return null;
    }

    /**
     * Return the qr image filename
     *
     * @param $order
     *
     * @return mixed
     */
    private function get_qr_image_filename($order) {
        return $order->get_id();
    }

    /**
     * Clean the QR images
     *
     * @param $order
     */
    private function clean_qr_images($order) {
        $qr_png = WC_SWISS_QR_BILL_UPLOAD_DIR . $this->get_qr_image_filename($order) . '.png';
        $qr_svg = WC_SWISS_QR_BILL_UPLOAD_DIR . $this->get_qr_image_filename($order) . '.svg';
        if (file_exists($qr_png)) {
            wp_delete_file($qr_png);
        }
        if (file_exists($qr_svg)) {
            wp_delete_file($qr_svg);
        }
    }

    /**
     * Clean the generated invoice PDF
     *
     * @param $order_id
     */
    public function clean_pdf_invoice($order_id) {
        if (!$order_id) {
            return;
        }

        $file_name = get_post_meta($order_id, '_wsqb_invoice_pdf_path', 'true');
        if (file_exists(WC_SWISS_QR_BILL_UPLOAD_DIR . $file_name)) {
            wp_delete_file(WC_SWISS_QR_BILL_UPLOAD_DIR . $file_name);
        }
    }

    /**
     * @param $order
     *
     * @return bool
     */
    public function should_attach_invoice_pdf($order) {
        return in_array($order->get_payment_method(), array('wc_swiss_qr_bill', 'wc_swiss_qr_bill_classic'));
    }

    /**
     * @param $order_id
     *
     * @return string
     */
    public function get_invoice_pdf_name($order_id) {
        $locale = get_locale();

        switch ($locale) {
            case "en_ZA":
            case "en_GB":
            case "en_NZ":
            case "en_CA":
            case "en_AU":
            case "en_US":
                $file_name = 'invoice';
                break;
            case "fr_CA":
            case "fr_BE":
            case "fr_FR":

                $file_name = 'facture';
                break;
            default:
                $file_name = 'rechnung';
        }

        return $file_name . '-' . $order_id . '.pdf';
    }

    /**
     * @return string
     */
    public function get_langauge_code() {
        $locale = get_locale();

        switch ($locale) {
            case "fr_CA":
            case "fr_BE":
            case "fr_FR":
                $language = 'fr';
                break;
            case "de_CH":
            case "de_DE":
            case "de_CH_informal":
            case "de_DE_formal":
            case "de_AT":
                $language = 'de';
                break;
            default:
                $language = 'en';
        }

        return $language;
    }

}