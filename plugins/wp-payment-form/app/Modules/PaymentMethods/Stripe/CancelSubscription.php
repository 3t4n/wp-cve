<?php

namespace WPPayForm\App\Modules\PaymentMethods\Stripe;

use WPPayForm\Framework\Support\Arr;
use WPPayForm\App\Models\Submission;
use WPPayForm\App\Models\Subscription;

if (!defined('ABSPATH')) {
    exit;
}

class cancelSubscription
{
    public static function Cancel($formId, $subscription, $submission)
    {
        if (!$subscription) {
            return new \WP_Error('not_found', 'Sorry, Subscription is not available/already cancelled');
        }

        $validStatuses = [
            'active',
            'trialling',
            'failing'
        ];

        $subscriptionId = Arr::get($subscription, 'vendor_subscriptipn_id');
        $subscriptionStatus = Arr::get($subscription, 'status');

        if (!in_array($subscriptionStatus, $validStatuses)) {
            return new \WP_Error('wrong_status', 'Sorry, You can not cancel this subscription');
        }

        $oldStatus = $subscriptionStatus;
        $newStatus = 'cancelled';

        $stripe = new Stripe();
        ApiRequest::set_secret_key($stripe->getSecretKey($formId));
        $response = [];
        if (current_user_can('manage_options') || current_user_can('delete_dashboard') || current_user_can('cancel_subscription')) {
            $response = ApiRequest::request([], 'subscriptions/' . $subscriptionId, 'DELETE');
        } else {
            return new \WP_Error('wrong_status', 'Sorry, You can not cancel subscriptions. Only administrators can cancel subscription');
        }

        if (is_wp_error($response)) {
            return $response;
        }

        $data['status'] = $newStatus;

        $subscriptionModel = new Subscription();

        $subscriptionModel->updateSubscription($subscription['id'], $data);

        $vendor_data = Arr::get($subscription, 'vendor_response');

        do_action('wppayform/subscription_payment_canceled', $submission, $subscription, $formId, $vendor_data);
        do_action('wppayform/subscription_payment_canceled_stripe', $submission, $subscription, $formId, $vendor_data);

        $message = 'Subscription has been cancelled successfully!';

        return $message;
    }
}