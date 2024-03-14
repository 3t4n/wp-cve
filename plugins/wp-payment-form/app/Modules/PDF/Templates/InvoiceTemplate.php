<?php

namespace WPPayForm\App\Modules\PDF\Templates;

use WPPayForm\App\Services\PlaceholderParser;
use WPPayForm\App\Modules\PDF\Templates\TemplateManager;
use WPPayForm\App\Models\Submission;
use WPPayForm\Framework\Support\Arr;

class InvoiceTemplate extends TemplateManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getDefaultSettings($form)
    {
        return [
            'logo' => '',
            'business_email',
            'business_address',
            'invoice_upper_text' => '',
            'invoice_thanks' => 'Thank you for your order',
            'invoice_prefix' => '',
            'customer_name' => '',
            'customer_email' => '',
            'customer_address' => ''
        ];
    }

    public function getSettingsFields()
    {
        return array(
            [
                'key' => 'logo',
                'label' => 'Business Logo',
                'tips' => 'Your Business Logo which will be shown in the invoice header',
                'type' => 'image_widget'
            ],
            [
                'key' => 'business_name',
                'label' => 'Business Name',
                'tips' => 'Your Business Name which will be shown in the invoice header',
                'type' => 'value_text'
            ],
            [
                'key' => 'business_email',
                'label' => 'Business Email',
                'tips' => 'Your Business Email which will be shown in the invoice header',
                'type' => 'value_text'
            ],
            [
                'key' => 'business_address',
                'label' => 'Business Address',
                'tips' => 'Your Business Address which will be shown in the invoice header',
                'type' => 'value_text'
            ],
            [
                'key' => 'customer_name',
                'label' => 'Customer Name',
                'tips' => 'Please select the customer name field from the smartcode dropdown',
                'type' => 'value_text'
            ],
            [
                'key' => 'customer_email',
                'label' => 'Customer Email',
                'tips' => 'Please select the customer email field from the smartcode dropdown',
                'type' => 'value_text'
            ],
            [
                'key' => 'customer_address',
                'label' => 'Customer Address',
                'tips' => 'Please select the customer address field from the smartcode dropdown',
                'type' => 'value_text'
            ],
            [
                'key' => 'invoice_prefix',
                'label' => 'Invoice Prefix',
                'tips' => 'Add your invoice prefix which will be prepended with the invoice number',
                'type' => 'value_text'
            ],
            [
                'key' => 'invoice_upper_text',
                'label' => 'Invoice Body Text',
                'tips' => 'Write Invoice body text. This will show before the invoice items',
                'type' => 'wp-editor'
            ],
            [
                'key' => 'invoice_thanks',
                'label' => 'Invoice Footer Text',
                'tips' => 'Write Invoice Footer Text. This will show at the end of the invoice',
                'type' => 'value_textarea'
            ]
        );
    }

    public function generatePdf($submissionId, $feed, $outPut, $fileName = '')
    {
        $settings = $feed['settings'];
        $submissionModel = new Submission();
        $submission = $submissionModel->getSubmission($submissionId);
        $formData = maybe_unserialize($submission->form_data_formatted, true);

        $settings['invoice_lines'] = '{submission.product_items_table_html}';
        if (false !== strpos(Arr::get($settings, 'invoice_upper_text'), '{submission.payment_receipt}')) {
            $settings['invoice_lines'] = '';
        }
        $settings['payment_summary'] = '{submission.payment_receipt}';
        $settings = PlaceholderParser::parse($settings, $submission);


        $htmlBody = $this->generateInvoiceHTML($submission, $settings, $feed);

        $htmlBody = str_replace('{page_break}', '<page_break />', $htmlBody);

        $htmlBody = apply_filters('wppayform_pdf_body_parse', $htmlBody, $submissionId, $formData, $submission->form_id);

        if(!$fileName) {
            $fileName = PlaceholderParser::parse( $feed['name'], $submission, $formData);
            $fileName = sanitize_title($fileName, 'pdf-file', 'display');
        }

        return $this->pdfBuilder($fileName, $feed, $htmlBody, '', $outPut);
    }

    private function generateInvoiceHTML($submission, $settings, $feed)
    {  
        $paymentSettings = false;
        $logo = Arr::get($settings, 'logo');
        if(!class_exists('\WPPayForm\App\Http\Controllers\GlobalSettingsController', false)) {
            $paymentSettings = (new \WPPayForm\App\Http\Controllers\GlobalSettingsController())->currencies();
            if(!$logo) {
                $logo = Arr::get($paymentSettings, 'business_logo');
            }
        } else {
            $paymentSettings = (new \WPPayForm\App\Http\Controllers\GlobalSettingsController())->currencies();
            if(!$logo) {
                $logo = Arr::get($paymentSettings, 'business_logo');
            }
        }

        ob_start();
        ?>
        <table style="width: 100%; border: 0px solid transparent;">
            <tr>
                <td style="width: 40%" class="business_details">
                    <?php if($logo): ?>
                    <div class="business_logo">
                        <img src="<?php echo $logo; ?>" alt="Business-logo" class='buisness-logo' style="margin: 0; padding-bottom: 10px;"/>
                    </div>
                    <div>
                        <h4><?php echo Arr::get($settings, 'business_name'); ?></h4>
                        <div class="business_email"><?php echo Arr::get($settings, 'business_email'); ?></div>
                        <div class="business_address"><?php echo Arr::get($settings, 'business_address'); ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if($paymentSettings): ?>
                    <div class="business_address">
                        <div class="business_name"><?php echo Arr::get($paymentSettings, 'business_name'); ?></div>
                        <div class="business_address"><?php echo Arr::get($paymentSettings, 'business_address'); ?></div>
                    </div>
                    <?php endif; ?>
                </td>
                <td style="width: 20%"></td>
                <td style="width: 40%" class="customer_row">
                    <?php if(Arr::get($settings, 'invoice_prefix')): ?>
                        <h2 style="padding-bottom: 30px" class="invoice_title"><?php _e('RECEIPT:', 'wp-payment-form');?> <?php echo Arr::get($settings, 'invoice_prefix').'-'.$submission->serial_number; ?></h2>
                        <br/>
                    <?php endif; ?>

                    <div  class="heading_items">
                        <div class="order_number"><b><?php _e('Order Number:', 'wp-payment-form'); ?></b> <?php echo $submission->id; ?></div>
                        <div class="payment_date"><b><?php _e('Payment Date:', 'wp-payment-form'); ?></b> <?php echo date(get_option( 'date_format' ), strtotime($submission->created_at)); ?></div>
                        <br />
                        <div class="customer_details">
                            <?php if(Arr::get($settings, 'customer_name') || Arr::get($settings, 'customer_address') || Arr::get($settings, 'customer_email')): ?>
                                <p style="font-weight: bold; margin-bottom:10px;" class="customer_heading"><?php _e('Customer Details', 'wp-payment-form'); ?></p>
                                <p class="customer_name"><?php echo Arr::get($settings, 'customer_name'); ?></p>
                                <p class="customer_email"><?php echo Arr::get($settings, 'customer_email'); ?></p>
                                <p class="customer_address"><?php echo Arr::get($settings, 'customer_address'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <hr />
        <div class="receipt_upper_text"><?php echo Arr::get($settings, 'invoice_upper_text'); ?></div>

        <div class="invoice_lines"><?php echo Arr::get($settings, 'invoice_lines'); ?></div>

        <?php if (strpos(Arr::get($settings, 'payment_summary'), 'class="ffp_payment_info_table"') !== false): ?>
            <div class="invoice_summary">
                <h3><?php _e('Payment Details', 'wp-payment-form');?></h3>
                <?php echo Arr::get($settings, 'payment_summary'); ?>
            </div>
        <?php endif;?>

        <div class="invoice_thanks">
            <?php echo Arr::get($settings, 'invoice_thanks'); ?>
        </div>
        <style>
            .business_logo {
                max-width: 200px;
                margin-bottom: 20px;
            }
            .business_logo img {
                margin-bottom: 20px;
                max-width: 200px;
                max-height: 100px;
            }
            .business_name {
                font-weight: bold;
                margin-bottom: 10px;
            }
            td.customer_row {
                text-align: right;
            }
            td.customer_row ul, td.customer_row ul li {
                list-style: none;
            }
            td.customer_row ul li {
                padding-bottom: 7px;
            }
        </style>
        <?php

        return ob_get_clean();
    }
}