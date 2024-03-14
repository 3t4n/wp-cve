<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Endpoints;

use Holded\Woocommerce\Adapters\PaymentMethodAdapter;

class PaymentMethodsEndpoint extends AbstractEndpoint
{
    public function init(): void
    {
        add_action('rest_api_init', [$this, 'registerEndpoint']);
    }

    public function registerEndpoint(): void
    {
        register_rest_route($this->apiNamespace, '/paymentmethods', [
            'methods'             => \WP_REST_Server::EDITABLE,
            'callback'            => [$this, 'listPaymentMethods'],
            'permission_callback' => '__return_true',
        ]);
    }

    /**
     * @return \WP_Error|\WP_REST_Response
     */
    public function listPaymentMethods(\WP_REST_Request $request)
    {
        $authentication = $this->authentication($request);
        if (is_wp_error($authentication) && is_object($authentication)) {
            return $authentication;
        }
        $methods = [];
        foreach (WC()->payment_gateways()->get_available_payment_gateways() as $gateway) {
            $methods[] = PaymentMethodAdapter::fromWoocommerceToDTO($gateway);
        }

        return new \WP_REST_Response([
            'data' => [
                'methods' => $methods,
            ],
        ], 200);
    }
}
