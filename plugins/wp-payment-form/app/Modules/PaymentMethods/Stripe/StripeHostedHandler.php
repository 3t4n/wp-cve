<?php

namespace WPPayForm\App\Modules\PaymentMethods\Stripe;

use WPPayForm\App\Models\Form;
use WPPayForm\App\Models\OrderItem;
use WPPayForm\App\Models\Submission;
use WPPayForm\App\Models\Subscription;
use WPPayForm\App\Models\Transaction;
use WPPayForm\App\Services\GeneralSettings;
use WPPayForm\App\Services\ConfirmationHelper;
use WPPayForm\Framework\Support\Arr;
use WPPayForm\App\Models\SubmissionActivity;
use WPPayFormPro\Classes\PaymentsHelper;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle Stripe Hosted Checkout Payments
 * @since 1.3.0
 */
class StripeHostedHandler extends StripeHandler
{
    public $paymentMethod = 'stripe_hosted';

    public function registerHooks()
    {
        add_filter('wppayform/form_submission_make_payment_' . $this->paymentMethod, array($this, 'redirectToStripe'), 10, 6);
        add_action('wppayform/frameless_pre_render_page_stripe_hosted_success', array($this, 'markPaymentSuccess'), 10, 1);
        add_action('wppayform/frameless_body_stripe_hosted_success', array($this, 'showSuccessMessage'), 10, 1);
    }

    /*
     * This payment method is bit easy than inline stripe
     * As Stripe handle all the things. We have to just feed the right data and
     * make the redirection. Then we will be done here.
     *
     */
    public function redirectToStripe($transactionId, $submissionId, $form_data, $form, $hasSubscriptions, $totalPayable = 0)
    {
        $submissionModel = new Submission();
        $submission = $submissionModel->getSubmission($submissionId);

        $formPaymentSettings = array(
            'transaction_type' => 'payment',
        );

        if (defined('WPPAYFORMHASPRO')) {
            $formPaymentSettings = (new PaymentsHelper())->getPaymentSettings($form->ID);
        }

        $cancelUrl = Arr::get($submission->form_data_raw, '__wpf_current_url');
        if (!wp_http_validate_url($cancelUrl)) {
            $cancelUrl = site_url('?wpf_page=frameless&wpf_action=stripe_hosted_cancel&wpf_hash=' . $submission->submission_hash);
        }

        $successUrl = site_url('?wpf_page=frameless&wpf_action=stripe_hosted_success&wpf_hash=' . $submission->submission_hash);

        $paymentMethodElements = Form::getPaymentMethodElements($form->ID);

        $requireBilling = Arr::get($paymentMethodElements, 'stripe_card_element.options.checkout_display_style.require_billing_info') == 'yes';

        $requireBillingForMultiplePayment = Arr::get($paymentMethodElements, 'choose_payment_method.options.method_settings.payment_settings.stripe.checkout_display_style.require_billing_info') == 'yes';

        $paymentMethods = Arr::get($formPaymentSettings, 'stripe_checkout_methods', false);

        $checkoutArgs = [
            'cancel_url' => $cancelUrl,
            'success_url' => $successUrl,
            'locale' => 'auto',
            'client_reference_id' => $submissionId,
            'billing_address_collection' => 'required',
            'metadata' => $this->getIntentMetaData($submission)
        ];

        if ($paymentMethods && is_array($paymentMethods)) {
            $checkoutArgs['payment_method_types'] = $paymentMethods;
        }

        if ($requireBilling || $requireBillingForMultiplePayment) {
            $checkoutArgs['billing_address_collection'] = 'required';
        } else {
            $checkoutArgs['billing_address_collection'] = 'auto';
        }

        if ($submission->customer_email) {
            $checkoutArgs['customer_email'] = $submission->customer_email;
        }
        if ($lineItems = $this->getLineItems($submission, $totalPayable)) {
            $checkoutArgs['line_items'] = $lineItems;
        }

        if ($hasSubscriptions) {
            $subscriptionArgs = $this->getSubscriptionArgs($submission, $totalPayable);
            if ($subscriptionArgs) {
                $checkoutArgs['subscription_data'] = $subscriptionArgs;
            }
        }

        if (empty($checkoutArgs['line_items']) && empty($checkoutArgs['subscription_data'])) {
            return;
        }



        if (empty($checkoutArgs['subscription_data'])) {
            if ($formPaymentSettings['transaction_type'] == 'donation') {
                $checkoutArgs['submit_type'] = 'donate';
            }
            $checkoutArgs['payment_intent_data'] = [
                'capture_method' => 'automatic',
                'description' => $form->post_title,
                'metadata' => $this->getIntentMetaData($submission)
            ];
        }


        $checkoutArgs = apply_filters('wppayform/stripe_checkout_session_args', $checkoutArgs, $submission);
        $checkoutSession = CheckoutSession::create($checkoutArgs, $submission->form_id);

        if (!empty($checkoutSession->error)) {
            wp_send_json_error([
                'message' => $checkoutSession->error->message,
                'payment_error' => true
            ], 423);
        }

        $paymentIntent = $checkoutSession->id;
        $transactionModel = new Transaction();
        $transactionModel->updateTransaction(
            $transactionId,
            array(
                'status' => 'intented',
                'payment_mode' => $this->getMode($form->ID)
            )
        );

        $submissionModel->updateMeta($submission->id, 'stripe_intended_session', $paymentIntent);
        // Redirect to
        wp_send_json_success([
            'message' => __('Please wait... You are redirecting to Secure Payment page powered by Stripe', 'wp-payment-form'),
            'call_next_method' => 'stripeRedirectToCheckout',
            'session_id' => $checkoutSession->id
        ], 200);
    }

    public function getLineItems($submission, $totalPayable)
    {
        $orderItemsModel = new OrderItem();
        $items = $orderItemsModel->getOrderItems($submission->id);
        $formattedItems = [];
        $priceSubtotal = 0;
        $taxTotal = 0;
        $payItems = [];
        $taxItems = [];
        foreach ($items as $item) {
            $price = $item->item_price;

            if (!$price) {
                continue;
            }
            if (GeneralSettings::isZeroDecimal($submission->currency)) {
                $price = intval($price / 100);
            }

            $quantity = ($item->quantity) ? $item->quantity : 1;

            if ($item->type == 'tax_line') {
                $taxTotal += intval($item->line_total);
                $taxItems[] = [
                    'amount' => $price,
                    'currency' => $submission->currency,
                    'name' => $item->item_name,
                    'quantity' => $quantity,
                ];
            } else {
                $payItems[] = [
                    'amount' => $price,
                    'currency' => $submission->currency,
                    'name' => $item->item_name,
                    'quantity' => $quantity,
                ];
            }
            $priceSubtotal += $price * intval($quantity);
        }
        $discountItems = $orderItemsModel->getDiscountItems($submission->id);
        if ($discountItems) {
            $discountTotal = 0;
            $totalPayable -= $taxTotal;
            foreach ($discountItems as $discountItem) {
                $discountTotal += $discountItem->line_total;
            }

            if (GeneralSettings::isZeroDecimal($submission->currency)) {
                $discountTotal = intval($discountTotal / 100);
                $totalPayable = intval($totalPayable / 100);
            }

            $newDiscountItems = [];

            foreach ($payItems as $item) {
                $baseAmount = $item['amount'];
                $item['amount'] = intval($baseAmount - ($discountTotal / $totalPayable) * $baseAmount);
                $newDiscountItems[] = $item;
            }
            $payItems = $newDiscountItems;
        }
        $formattedItems = array_merge($payItems, $taxItems);
        return $formattedItems;
    }

    private function getSubscriptionArgs($submission, $totalPayable)
    {
        $subscriptionModel = new Subscription();
        $subscriptions = $subscriptionModel->getSubscriptions($submission->id);

        if (!$subscriptions) {
            return [];
        }

        $subscriptionItems = [];
        $maxTrialDays = 0;

        $orderItemsModel = new OrderItem();
        $discountItems = $orderItemsModel->getDiscountItems($submission->id);
        $items = $orderItemsModel->getOrderItems($submission->id);
        $taxTotal = 0;
        foreach ($items as $item) {
            $price = $item->item_price;
            if ($item->type == 'tax_line') {
                $taxTotal += intval($item->line_total);
            }
        }
        if ($taxTotal) {
            $totalPayable -= $taxTotal;
        }

        if ($discountItems) {
            $discountTotal = 0;
            // $priceSubtotal -= $taxTotal;
            foreach ($discountItems as $discountItem) {
                $discountTotal += $discountItem->line_total;
            }

            if (GeneralSettings::isZeroDecimal($submission->currency)) {
                $discountTotal = intval($discountTotal / 100);
            }

            $newDiscountItems = [];

            foreach ($subscriptions as $subscription) {
                $baseAmount = intval($subscription->recurring_amount);
                $subscription->recurring_amount = intval($baseAmount - ($discountTotal / $totalPayable) * $baseAmount);
                $newDiscountItems[] = $subscription;
            }

            $subscriptions = $newDiscountItems;
        }

        foreach ($subscriptions as $subscriptionItem) {
            if (!$subscriptionItem->recurring_amount) {
                continue;
            }

            if ($subscriptionItem->trial_days && $maxTrialDays < $subscriptionItem->trial_days) {
                $maxTrialDays = $subscriptionItem->trial_days;
                $subscriptionItem->trial_days = 0;
            }
            $subscription = Plan::getOrCreatePlan($subscriptionItem, $submission);

            $subscriptionItems[] = [
                'plan' => $subscription->id,
                'quantity' => ($subscriptionItem->quantity) ? $subscriptionItem->quantity : 1
            ];
            $subscriptionModel->updateSubscription($subscriptionItem->id, [
                'status' => 'intented',
                'vendor_plan_id' => $subscription->id,
                'vendor_response' => maybe_serialize($subscription),
            ]);
        }


        $args = [];
        if ($subscriptionItems) {
            $args = [
                'items' => $subscriptionItems
            ];
            if ($maxTrialDays) {
                $subscriptionModel->updateBySubmissionId($submission->id, [
                    'trial_days' => $maxTrialDays,
                    'updated_at' => current_time('mysql')
                ]);
                $args['trial_period_days'] = $maxTrialDays;
            }
        }

        $metaData = [
            'submission_id' => $submission->id,
            'wpf_subscription_id' => $subscription->id,
            'form_id' => $submission->form_id,
            'description' => $submission->post_title
        ];

        $metaData = apply_filters('wppayform/stripe_onetime_payment_metadata', $metaData, $submission);

        $args['metadata'] = $metaData;

        return $args;
    }

    /*
     * This function will be called after stripe hosted payment success
     * It's basically called by the fameless page action
     */
    public function markPaymentSuccess($action = '')
    {
        $submissionHash = sanitize_text_field($_REQUEST['wpf_hash']);
        $submissionModel = new Submission();
        $submission = $submissionModel->getSubmissionByHash($submissionHash);

        if (!$submission) {
            return;
        }

        // Payment Status pending so let's try to make the payment now
        //   print_r($submission);
        $sessionId = $submissionModel->getMeta($submission->id, 'stripe_intended_session');

        $session = CheckoutSession::retrive($sessionId, [
            'expand' => [
                'subscription.latest_invoice.payment_intent',
                'payment_intent'
            ]
        ], $submission->form_id);

        if (!$session || !$session->customer) {
            // For failed payment customer will not exist
            return;
        }

        $this->handleCheckoutSessionSuccess($submission, $session);
    }


    public function handleCheckoutSessionSuccess($submission, $session)
    {
        do_action('wppayform/form_submission_activity_start', $submission->form_id);

        $submissionModel = new Submission();

        // Check If the hooks already fired and data updated
        if ($submissionModel->getMeta($submission->id, 'stripe_checkout_hooked_fired') == 'yes') {
            return;
        }

        $paymentSuccessHandler = new PaymentSuccessHandler();

        // Collect the Onetime not-paid transaction and intented transactions
        $transactionModel = new Transaction();
        $intentedOneTimeTransaction = $transactionModel->getLatestIntentedTransaction($submission->id);

        // Handle One time payment success
        if ($intentedOneTimeTransaction) {
            if (empty($session->subscription->latest_invoice)) {
                $response = $session;
            } else {
                $response = $session->subscription->latest_invoice;
            }
            $paymentSuccessHandler->processOnetimeSuccess($intentedOneTimeTransaction, $response, $submission);
            $submission = $submissionModel->getSubmission($submission->id); // We are just getting the latest data
        }
        /*
         * Handle Subscription Transaction Entry on Success
         * First of all we have to check if this submission has any subscription payment
         */
        // Lets fetch the subscription for this submission

        $subscriptionModel = new Subscription();
        $intentedSubscriptions = $subscriptionModel->getIntentedSubscriptions($submission->id);

        if (!$intentedSubscriptions->isEmpty()) {
            $paymentSuccessHandler->processSubscriptionsSuccess($intentedSubscriptions, $session->subscription->latest_invoice, $submission);
            $subscriptions = $subscriptionModel->getSubscriptions($submission->id);
            $vendorSubscription = $session->subscription;

            if ($vendorSubscription) {
                $this->maybeSetCancelAt($subscriptions[0], $vendorSubscription, $submission->form_id);
            }
        }

        // Fire Action Hooks to make the payment
        $submissionModel->updateSubmission($submission->id, [
            'payment_status' => 'paid',
            'payment_method' => 'stripe',
            'payment_mode' => $this->getMode($submission->form_id)
        ]);

        $submission = $submissionModel->getSubmission($submission->id);

        $submissionModel->updateMeta($submission->id, 'stripe_checkout_hooked_fired', 'yes');

        $paymentSuccessFired = false;
        if ($intentedOneTimeTransaction) {
            $transaction = $transactionModel->getTransaction($intentedOneTimeTransaction->id);
            do_action('wppayform/form_payment_success_stripe', $submission, $transaction, $submission->form_id, $session);
            do_action('wppayform/form_payment_success', $submission, $transaction, $submission->form_id, $session);
            $paymentSuccessFired = true;
        }

        if (!$intentedSubscriptions->isEmpty()) {
            $subscriptions = $subscriptionModel->getSubscriptions($submission->id);
            do_action('wppayform/form_recurring_subscribed_stripe', $submission, $subscriptions, $submission->form_id);
            do_action('wppayform/form_recurring_subscribed', $submission, $subscriptions, $submission->form_id);

            if (!$paymentSuccessFired) {
                do_action('wppayform/form_payment_success_stripe', $submission, false, $submission->form_id, $session);
                do_action('wppayform/form_payment_success', $submission, false, $submission->form_id, $session);
            }
        }
    }

    public function maybeSetCancelAt($subscription, $stripeSub, $formId = false)
    {
        if ($stripeSub->cancel_at) {
            return;
        }

        if (!$subscription->bill_times) {
            return;
        }

        $trialOffset = 0;

        $dateStr = '+' . $subscription->bill_times . ' ' . ($subscription->billing_interval !== 'daily' ? $subscription->billing_interval : 'day');

        if ($subscription->trial_days) {
            $trialOffset = $subscription->trial_days * 86400;
        }

        $startingTimestamp = $stripeSub->created;

        $cancelAt = $startingTimestamp + $trialOffset + (strtotime($dateStr) - time()) + 2160;

        $stripe = new Stripe();
        ApiRequest::set_secret_key($stripe->getSecretKey($formId));

        return ApiRequest::request([
            'cancel_at' => $cancelAt
        ], 'subscriptions/' . $stripeSub->id, 'POST');
    }


    public function showSuccessMessage($action)
    {
        $submissionHash = sanitize_text_field($_REQUEST['wpf_hash']);

        $submissionModel = new Submission();
        $submission = $submissionModel->getSubmissionByHash($submissionHash);

        if (!$submission) {
            echo __('Sorry! no associate submission found', 'wp-payment-form');
            return;
        }
        $confirmation = ConfirmationHelper::getFormConfirmation($submission->form_id, $submission);

        if ($confirmation['redirectTo'] == 'customUrl' && $confirmation['customUrl']) {
            wp_redirect($confirmation['customUrl']);
            exit();
        }
        $title = __('Payment has been successfully completed', 'wp-payment-form');

        $paymentHeader = apply_filters('wppayform/payment_success_title', $title, $submission);
        echo '<div class="frameless_body_header">' . $paymentHeader . '</div>';
        echo do_shortcode($confirmation['messageToShow']);
        return;
    }

    public function getIntentMetaData($submission)
    {
        $metadata = [
            'Submission ID' => $submission->id,
            'Form ID' => $submission->form_id
        ];

        if ($submission->customer_email) {
            $metadata['customer_email'] = $submission->customer_email;
        }

        if ($submission->customer_name) {
            $metadata['customer_name'] = $submission->customer_name;
        }

        return apply_filters('wppayform/stripe_onetime_payment_metadata', $metadata, $submission);
    }

    public function syncSubscription($formId, $submissionId)
    {
        if (!$submissionId) {
            return;
        }
        $submissionModel = new Submission();
        $submission = $submissionModel->getSubmission($submissionId);
        $sessionId = $submissionModel->getMeta($submissionId, 'stripe_intended_session');
        $subscriptionModel = new Subscription();
        $subscriptions = $subscriptionModel->getSubscriptions($submissionId);
        if (isset($subscriptions[0])) {
            $subscriptionId = Arr::get($subscriptions, '0.vendor_subscriptipn_id');
        } else {
            return 'No subscription Id found!';
        }
        ;

        $session = CheckoutSession::invoices($sessionId, [
            'subscription' => $subscriptionId
        ], $submission->form_id);

        if (empty($session->data)) {
            return;
        }

        SubmissionActivity::createActivity(
            array(
                'form_id' => $submission->form_id,
                'submission_id' => $submission->id,
                'type' => 'activity',
                'created_by' => 'Paymattic BOT',
                'content' => __('Stripe recurring payments synced from upstream', 'wp-payment-form')
            )
        );

        foreach (array_reverse($session->data) as $invoice) {
            $subsSuccess = new PaymentSuccessHandler();
            $subsSuccess->processSubscriptionsSuccess($subscriptions, $invoice, $submission, true);
        }

        wp_send_json_success(
            array(
                'message' => 'Successfully synced!'
            ),
            200
        );
    }

    public function cancelSubscription($formId, $submission, $subscription)
    {
        $subscriptionId = Arr::get($subscription, 'vendor_subscriptipn_id');
        if (!$subscriptionId) {
            wp_send_json_error(
                array(
                    'message' => 'No subscription ID found!',
                ),
                423
            );
        }

        $response = CancelSubscription::Cancel($formId, $subscription, $submission);

        if (is_wp_error(($response))) {
            wp_send_json_error(array('message' => $response->get_error_message()), 423);
        }

        // add logs
        SubmissionActivity::createActivity(
            array(
                'form_id' => $submission->form_id,
                'submission_id' => $submission->id,
                'type' => 'activity',
                'created_by' => 'Paymattic BOT',
                'content' => __('Stripe recurring subscription Cancelled', 'wp-payment-form')
            )
        );

        wp_send_json_success(
            array(
                'message' => $response
            ),
            200
        );
    }

}
