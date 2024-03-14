<?php

namespace WPPayForm\App\Hooks\Handlers;

use WPPayForm\App\Modules\PaymentMethods\Stripe\Stripe;
use WPPayForm\App\Modules\PaymentMethods\Stripe\StripeInlineHandler;
use WPPayForm\App\Modules\PaymentMethods\Stripe\StripeHostedHandler;
use WPPayForm\Framework\Support\Arr;
use WPPayForm\App\Models\Submission;
use WPPayForm\App\Modules\Integrations\TinyMceBlock;
use WPPayForm\App\Modules\Integrations\DashboardWidget;

class DependencyHandler
{
    public function registerStripe()
    {
        // Stripe Payment Method Init Here
        $stripe = new Stripe();
        $stripe->registerHooks();

        // Stripe Inline Handler
        $stripeInlineHandler = new StripeInlineHandler();
        $stripeInlineHandler->registerHooks();

        // Stripe Hosted Checkout Handler
        $stripeHostedHandler = new StripeHostedHandler();
        $stripeHostedHandler->registerHooks();
    }

    public function tinyMceBlock()
    {
        (new TinyMceBlock())->register();
    }

    public function dashboardWidget()
    {
        (new DashboardWidget())->register();
    }

    public function registerShortCodes()
    {
        // Register the shortcode
        add_shortcode('wppayform', function ($args) {
            $args = shortcode_atts(
                array(
                    'id' => '',
                    'show_title' => false,
                    'show_description' => false,
                ),
                $args
            );

            if (!$args['id']) {
                return;
            }

            $builder = new \WPPayForm\App\Modules\Builder\Render();

            return $builder->render($args['id'], $args['show_title'], $args['show_description']);
        });

        add_shortcode('wppayform_reciept', function ($atts) {
            $args = shortcode_atts(
                array(
                    'hash' => '',
                ),
                $atts,
                'wppayform_reciept'
            );

            if (!$args['hash']) {
                $hash = Arr::get($_REQUEST, 'wpf_submission');
                if (!$hash) {
                    $hash = Arr::get($_REQUEST, 'wpf_hash');
                }
            } else {
                $hash = $args['hash'];
            }


            if ($hash) {
                $submission = Submission::where('submission_hash', '=', $hash)->first();
                if ($submission) {
                    $receiptHandler = new \WPPayForm\App\Modules\Builder\PaymentReceipt();
                    return $receiptHandler->render($submission->id);
                }
            }

            return '<p class="wpf_no_recipt_found">' . __('Sorry, no submission receipt found, Please check your receipt URL', 'wp-payment-form') . '</p>';
        });
    }
}
