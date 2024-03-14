<?php

namespace FluentConnect\App\Services\ThriveCart;

use FluentConnect\App\Models\Integration;
use FluentConnect\App\Services\ConnectStores;
use FluentConnect\App\Services\Triggers\WP\ProfileUpdated;
use FluentConnect\Framework\Support\Arr;

class ThriveCart
{
    private $slug = 'thrivecart';

    public function init()
    {
        add_action('fluent_connector_handle_webhook_' . $this->slug, array($this, 'handleWebhookEvent'));

        ConnectStores::addTriggerProvider('thrivecart', [
            'title'                        => 'ThriveCart',
            'logo'                         => FLUENT_CONNECT_PLUGIN_URL . 'assets/images/thrivecart.png',
            'description'                  => 'Do actions based on ThriveCart events like order succes, order refunds etc',
            'require_integration_selector' => true,
            'enabled'                      => true
        ]);

        ConnectStores::addTrigger('thrivecart', 'fluent_connector_thrivecart_event_order_success', OrderSuccessTrigger::class);
        ConnectStores::addTrigger('thrivecart', 'fluent_connector_thrivecart_event_order_refund', OrderRefundTrigger::class);
        ConnectStores::addTrigger('thrivecart', 'fluent_connector_thrivecart_event_order_subscription_cancelled', SubscriptionCancelledTrigger::class);
    }

    /**
     * @param $apiKey string
     * @return array|mixed|\WP_Error
     */
    public function getAccountInfo($apiKey)
    {
        return (new Api($apiKey))->get('ping');
    }

    /**
     * @param $request \FluentConnect\Framework\Request\Request
     */
    public function handleWebhookEvent($request)
    {
        $hookId = absint($request->get('hook_id'));
        $integration = Integration::where('provider', $this->slug)->find($hookId);


        if (!$integration) {
            wp_send_json([
                'message' => 'No Integration found'
            ], 404);
        }

        $sign = $request->get('sign');

        if ($sign != Arr::get($integration->settings, 'webhook_signature')) {
            wp_send_json([
                'message' => 'Signature mismatch'
            ], 404);
        }

        if ($request->method() === 'HEAD') {
            if ($integration->status == 'draft') {
                $integration->status = 'published';
            }

            $settings = $integration->settings;
            $settings['webhook_verified'] = true;
            $integration->settings = $settings;
            $integration->save();

            wp_send_json([
                'message'   => 'Webhook successfully verified',
                'timestamp' => time()
            ], 200);
        }

        $hookData = $request->getJson();

        if (!$hookData || !is_array($hookData) || empty($hookData['event'])) {
            return false;
        }

        $event = sanitize_text_field($hookData['event']);

        $event = str_replace('.', '_', $event);

        do_action('fluent_connector_thrivecart_event_' . $event, $hookData);

    }

}
