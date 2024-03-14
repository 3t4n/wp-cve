<?php

namespace WPPayForm\App\Modules\PaymentMethods\Stripe;

use WPPayForm\App\Models\Submission;
use WPPayForm\App\Models\OrderItem;
use WPPayForm\App\Models\SubmissionActivity;
use WPPayForm\App\Models\Subscription;
use WPPayForm\App\Models\SubscriptionTransaction;
use WPPayForm\App\Models\Transaction;
use WPPayForm\App\Services\GeneralSettings;
use WPPayForm\Framework\Support\Arr;
use WPPayFormPro\Classes\PaymentsHelper;

if (!defined('ABSPATH')) {
    exit;
}

/**
 *  Stripe Base Class Handler where stripe payment methods
 * will extend this class
 * @since 1.3.0
 */
class StripeHandler
{
    public $parnentPamentMethod = 'stripe';

    public function getMode($formId = false)
    {
        if ($formId && defined('WPPAYFORMHASPRO')) {
            $formPaymentSettings = (new PaymentsHelper())->getPaymentSettings($formId, 'admin');
            if (Arr::get($formPaymentSettings, 'stripe_account_type') == 'custom') {
                return Arr::get($formPaymentSettings, 'stripe_custom_config.payment_mode') == 'live' ? 'live' : 'test';
            }
        }

        $paymentSettings = $this->getStripeSettings();
        return ($paymentSettings['payment_mode'] == 'live') ? 'live' : 'test';
    }

    // wpfGetStripePaymentSettings
    private function getStripeSettings()
    {
        $settings = get_option('wppayform_stripe_payment_settings', array());
        $defaults = array(
            'payment_mode' => 'test',
            'live_pub_key' => '',
            'live_secret_key' => '',
            'test_pub_key' => '',
            'test_secret_key' => '',
            'company_name' => get_bloginfo('name'),
            'checkout_logo' => '',
            'send_meta_data' => 'no'
        );
        return wp_parse_args($settings, $defaults);
    }

    public function handleInlineSubscriptions($customer, $submission, $form)
    {
        $subscriptionModel = new Subscription();
        $subscriptionTransactionModel = new SubscriptionTransaction();
        $subscriptions = $subscriptionModel->getSubscriptions($submission->id);

        if (!$subscriptions) {
            return false;
        }

        $isOneSucceed = false;
        $subscriptionStatus = 'active';
        foreach ($subscriptions as $subscriptionItem) {
            $subscription = PlanSubscription::create($subscriptionItem, $customer, $submission);

            if (!$subscription || is_wp_error($subscription)) {
                $subscriptionModel->update($subscriptionItem->id, [
                    'status' => 'failed',
                ]);
                if ($isOneSucceed) {
                    $message = __('Stripe error when creating subscription plan for you. Your card might be charged for atleast one subscription. Please contact site admin to resolve the issue', 'wp-payment-form');
                } else {
                    $message = __('Stripe error when creating subscription plan for you. Please contact site admin', 'wp-payment-form');
                }
                $errorCode = 400;
                if (is_wp_error($subscription)) {
                    $errorCode = $subscription->get_error_code();
                    $message = $subscription->get_error_message($errorCode);
                }
                return new \WP_Error($errorCode, $message, $subscription);
            }

            $isOneSucceed = true;
            if ($subscriptionItem->trial_days) {
                $subscriptionStatus = 'trialling';
            }

            $subscriptionModel->update($subscriptionItem->id, [
                'status' => $subscriptionStatus,
                'vendor_customer_id' => $subscription->customer,
                'vendor_subscriptipn_id' => $subscription->id,
                'vendor_plan_id' => $subscription->plan->id,
                'vendor_response' => maybe_serialize($subscription),
            ]);

            if (!$subscriptionItem->trial_days) {
                // Let's create the Subscription Transaction
                $latestInvoice = $subscription->latest_invoice;
                if ($latestInvoice->total) {
                    $totalAmount = $latestInvoice->total;
                    if (GeneralSettings::isZeroDecimal($submission->currency)) {
                        $totalAmount = intval($latestInvoice->total * 100);
                    }
                    $transactionItem = [
                        'form_id' => $submission->form_id,
                        'user_id' => $submission->user_id,
                        'submission_id' => $submission->id,
                        'subscription_id' => $subscriptionItem->id,
                        'transaction_type' => 'subscription',
                        'payment_method' => 'stripe',
                        'charge_id' => $latestInvoice->charge,
                        'payment_total' => $totalAmount,
                        'status' => $latestInvoice->status,
                        'currency' => $latestInvoice->currency,
                        'payment_mode' => ($latestInvoice->livemode) ? 'live' : 'test',
                        'payment_note' => maybe_serialize($latestInvoice),
                        'created_at' => gmdate('Y-m-d H:i:s', $latestInvoice->created),
                        'updated_at' => gmdate('Y-m-d H:i:s', $latestInvoice->created)
                    ];
                    $subscriptionTransactionModel->maybeInsertCharge($transactionItem);
                }
            }
        }

        SubmissionActivity::createActivity(array(
            'form_id' => $form->ID,
            'submission_id' => $submission->id,
            'type' => 'activity',
            'created_by' => 'Paymattic BOT',
            'content' => __('Stripe recurring subscription successfully initiated', 'wp-payment-form')
        ));

        $submissionModel = new Submission();

        $submissionModel->updateSubmission($submission->id, [
            'payment_status' => 'paid',
            'status' => $subscriptionStatus
        ]);

        SubmissionActivity::createActivity(array(
            'form_id' => $form->ID,
            'submission_id' => $submission->id,
            'type' => 'activity',
            'created_by' => 'Paymattic BOT',
            'content' => __('Subscription status changed to : ', 'wp-payment-form') . $subscriptionStatus
        ));

        return $subscriptionModel->getSubscriptions($submission->id);
    }

    public function handlePaymentChargeError($message, $submission, $transaction, $form, $charge = false, $type = 'general')
    {
        $paymentMode = $this->getMode($form->ID);
        do_action('wppayform/form_payment_stripe_failed', $submission, $transaction, $form, $charge, $type);
        do_action('wppayform/form_payment_failed', $submission, $transaction, $form, $charge, $type);

        $submissionModel = new Submission();

        if ($transaction) {
            $transactionModel = new Transaction();
            $transactionModel->updateTransaction($transaction->id, array(
                'status' => 'failed',
                'payment_method' => 'stripe',
                'payment_mode' => $paymentMode,
            ));
        }


        $submissionModel->updateSubmission($submission->id, array(
            'payment_status' => 'failed',
            'payment_method' => 'stripe',
            'payment_mode' => $paymentMode,
        ));

        SubmissionActivity::createActivity(array(
            'form_id' => $form->ID,
            'submission_id' => $submission->id,
            'type' => 'activity',
            'created_by' => 'Paymattic BOT',
            'content' => __('Payment Failed via stripe. Status changed from Pending to Failed.', 'wp-payment-form')
        ));

        if ($message) {
            SubmissionActivity::createActivity(array(
                'form_id' => $form->ID,
                'submission_id' => $submission->id,
                'type' => 'error',
                'created_by' => 'Paymattic BOT',
                'content' => $message
            ));
        }

        wp_send_json_error(array(
            'message' => $message,
            'payment_error' => true,
            'type' => $type,
            'form_events' => [
                'payment_failed'
            ]
        ), 423);
    }

    public function getSubmissionPlans($submission, $totalPayable)
    {
        $subscriptionModel = new Subscription();
        $subscriptions = $subscriptionModel->getSubscriptions($submission->id);

        if (!$subscriptions) {
            return [];
        }

        $orderItemsModel = new OrderItem();
        $discountItems = $orderItemsModel->getDiscountItems($submission->id);

        $plans = [];

        foreach ($subscriptions as $subscription) {
            if ($discountItems) {
                $discountTotal = 0;
                foreach ($discountItems as $discountItem) {
                    $discountTotal += $discountItem->line_total;
                }

                if (GeneralSettings::isZeroDecimal($submission->currency)) {
                    $discountTotal = intval($discountTotal / 100);
                }

                $baseAmount = intval($subscription->recurring_amount);
                $subscription->recurring_amount = intval($baseAmount - ($discountTotal / $totalPayable) * $baseAmount);
            }

            $plan = Plan::getOrCreatePlan($subscription, $submission);
            if ($plan && is_wp_error($plan)) {
                wp_send_json_error([
                    'message' => __('Sorry! there has an error when creating the subscription plan. Please try again', 'wp-payment-form'),
                    'plan' => $plan
                ], 423);
            } elseif ($plan) {
                $data = [
                    'plan_id' => $plan->id,
                    'description' => $subscription->item_name . ' (' . $subscription->plan_name . ')',
                    'quantity' => $subscription->quantity,
                    'trial_expiration_at' => false,
                    'subscription_cancel_at' => $this->getCancelAtTimeStamp($subscription)
                ];

                if ($subscription->expiration_at) {
                    $data['trial_expiration_at'] = strtotime($subscription->expiration_at);
                }

                $plans[] = $data;
                $subscriptionModel->updateSubscription($subscription->id, [
                    'vendor_plan_id' => $plan->id
                ]);
            }
        }

        return $plans;
    }

    private function getCancelAtTimeStamp($subscription)
    {
        if (!$subscription->bill_times) {
            return false;
        }
        $dateTime = current_datetime();
        $localtime = $dateTime->getTimestamp() + $dateTime->getOffset();

        $billingStartDate = $localtime;
        if ($subscription->expiration_at) {
            $billingStartDate = strtotime($subscription->expiration_at);
        }

        $billTimes = $subscription->bill_times;

        $interval = $subscription->billing_interval;

        if ($interval == 'daily') {
            $interval = 'day';
        }

        $interValMaps = [
            'day' => 'days',
            'week' => 'weeks',
            'month' => 'months',
            'year' => 'years'
        ];

        if (isset($interValMaps[$interval]) && $billTimes > 1) {
            $interval = $interValMaps[$interval];
        }

        return strtotime('+ ' . $billTimes . ' ' . $interval, $billingStartDate);
    }
}
