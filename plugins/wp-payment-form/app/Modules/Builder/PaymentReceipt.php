<?php

namespace WPPayForm\App\Modules\Builder;

use WPPayForm\App\Models\Form;
use WPPayForm\App\Models\Submission;
use WPPayForm\App\Models\SubscriptionTransaction;
use WPPayForm\App\Modules\Entry\Entry;
use WPPayForm\App\Services\PlaceholderParser;
use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Receipt Shortcode Handler
 * @since 1.0.0
 */
class PaymentReceipt
{
    public function render($submissionId)
    {
        $submissionModel = new Submission();
        $submission = $submissionModel->getSubmission($submissionId, array('transactions', 'order_items', 'tax_items', 'subscriptions', 'discount'));
        $submission = $this->getSubmissionTotal($submission);
        $receiptSettings = Form::getReceiptSettings($submission->form_id);

        $receiptSettings['receipt_header'] = PlaceholderParser::parse($receiptSettings['receipt_header'], $submission);
        $receiptSettings['receipt_footer'] = PlaceholderParser::parse($receiptSettings['receipt_footer'], $submission);

        $submission->parsedData = $submissionModel->getParsedSubmission($submission);
        $html = $this->beforePaymentReceipt($submission, $receiptSettings);
        $html .= $this->paymentReceptHeader($submission, $receiptSettings);
        $html .= $this->paymentInfo($submission, $receiptSettings);
        $html .= $this->recurringPaymentInfo($submission, $receiptSettings);

        $html .= $this->itemDetails($submission, $receiptSettings);
        $html .= $this->submissionDetails($submission, $receiptSettings);
        $html .= $this->paymentReceptFooter($submission, $receiptSettings);
        $html .= $this->afterPaymentReceipt($submission, $receiptSettings);
        $html .= $this->loadCss($submission);
        return $html;
    }

    public function getSubmissionTotal($submission)
    {
        if (!$submission) {
            return '<p class="wpf_invalid_receipt">' . __('Invalid submission. No receipt found', 'wp-payment-form') . '</p>';
        }
        // get Total subscription amount for make payment_total
        $totalSubscriptionsAmount = 0;
        foreach ($submission->subscriptions as $subscription) {
            $totalSubscriptionsAmount += $subscription->recurring_amount + $subscription->initial_amount;
        }
        // get Total discount amount for make payment_total
        $totalDiscountAmount = Arr::get($submission, 'discounts.total', 0);
    //    Submission payment_total alraedy have tax amount so we need to remove
    //    discount amount from payment_total and add subscription item amount
        $submission->payment_total = $submission->payment_total + $totalSubscriptionsAmount - $totalDiscountAmount;

        return $submission;
    }

    private function beforePaymentReceipt($submission, $receiptSettings)
    {
        ob_start();
        echo '<div class="wpf_payment_receipt">';
        do_action('wppayform/payment_receipt/before_content', $submission, $receiptSettings);
        return ob_get_clean();
    }

    private function afterPaymentReceipt($submission, $receiptSettings)
    {
        ob_start();
        do_action('wppayform/payment_receipt/after_content', $submission, $receiptSettings);
        echo '</div>';
        return ob_get_clean();
    }

    private function paymentReceptHeader($submission, $receiptSettings)
    {
        $preRender = apply_filters('wppayform/payment_receipt/pre_render_header', '', $submission, $receiptSettings);
        if ($preRender) {
            // We are returning the header if someone want to render the recept. peace!!!
            return $preRender;
        }
        return $this->loadView('receipt/header', array(
            'submission' => $submission,
            'header_content' => $receiptSettings['receipt_header']
        ));
    }

    private function paymentReceptFooter($submission, $receiptSettings)
    {
        $preRender = apply_filters('wppayform/payment_receipt/pre_render_footer', '', $submission, $receiptSettings);
        if ($preRender) {
            // We are returning the header if someone want to render the recept. peace!!!
            return $preRender;
        }

        if (!$receiptSettings['receipt_footer']) {
            return '';
        }

        return '<div class="wpf_receipt_footer">' . $receiptSettings['receipt_footer'] . '</div>';
    }

    private function paymentInfo($submission, $receiptSettings)
    {
        $preRender = apply_filters('wppayform/payment_receipt/pre_render_payment_info', '', $submission);
        if ($preRender) {
            return $preRender;
        }

        if (Arr::get($receiptSettings, 'info_modules.payment_info') != 'yes') {
            return;
        }

        if ($submission->subscriptions) {
            foreach ($submission->subscriptions as $subscription) {
                $submission->order_items[] = (object) [
                    'item_name' => $subscription->item_name . ' (' . $subscription->plan_name . ')',
                    'quantity' => $subscription->quantity,
                    'item_price' => $subscription->recurring_amount,
                    'line_total' => $subscription->recurring_amount * $subscription->quantity
                ];
            }
        }


        if (!$submission->order_items) {
            return;
        }

        return $this->loadView('receipt/payment_info', array(
            'submission' => $submission
        ));

        return '';
    }

    private function itemDetails($submission, $receiptSettings)
    {
        $preRender = apply_filters('wppayform/payment_receipt/pre_render_item_details', '', $submission, $receiptSettings);

        // Check for subscription
        $trasubscriptionTransactionModel = new SubscriptionTransaction();
        $hasSubscription = $trasubscriptionTransactionModel->hasSubscription($submission->id);

        if ($preRender) {
            return $preRender;
        }

        if (Arr::get($receiptSettings, 'info_modules.payment_info') != 'yes') {
            return;
        }

        $header = '<div>';
        $header .= '<h4>' . __('Items Details', 'wp-payment-form') . '</h4>';
        $html = $this->loadView('elements/order_items_table', array(
            'submission' => $submission,
            'hasSubscription' => $hasSubscription
        ));

        if (!$html) {
            return '</div>';
        }
        return $header . $html . '</div>';
    }

    private function submissionDetails($submission, $receiptSettings)
    {
        $preRender = apply_filters('wppayform/payment_receipt/pre_render_submission_details', '', $submission, $receiptSettings);
        if ($preRender) {
            return $preRender;
        }

        if (Arr::get($receiptSettings, 'info_modules.input_details') != 'yes') {
            return;
        }

        $entry = new Entry($submission);

        return $this->loadView('receipt/customer_details', array(
            'submission' => $submission,
            'submission_details' => $entry->getInputFieldsHtmlTable()
        ));
    }

    private function recurringPaymentInfo($submission, $receiptSettings)
    {
        if (Arr::get($receiptSettings, 'info_modules.payment_info') != 'yes') {
            return;
        }

        if (property_exists($submission, 'subscriptions') && $submission->subscriptions) {
            $preRender = apply_filters('wppayform/payment_receipt/pre_render_subscription_details', '', $submission);
            if ($preRender) {
                return $preRender;
            }
            $header = '<h4>' . __('Subscription Details', 'wp-payment-form') . '</h4>';
            $html = $this->loadView('elements/subscriptions_info', array(
                'submission' => $submission,
                'load_table_css' => false
            ));
            return $header . $html;
        }
    }

    private function loadCss($submission)
    {
        return $this->loadView('receipt/custom_css', array('submission' => $submission));
    }

    public function loadView($fileName, $data)
    {
        // normalize the filename
        $fileName = str_replace(array('../', './'), '', $fileName);
        $basePath = apply_filters('wppayform/receipt_template_base_path', WPPAYFORM_DIR . 'app/Views/', $fileName, $data);
        $filePath = $basePath . $fileName . '.php';
        extract($data);
        ob_start();
        include $filePath;
        return ob_get_clean();
    }
}
