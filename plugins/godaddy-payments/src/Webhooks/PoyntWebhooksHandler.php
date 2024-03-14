<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Webhooks;

use Exception;
use GoDaddy\WooCommerce\Poynt\API\GatewayAPI;
use GoDaddy\WooCommerce\Poynt\Helpers\ArrayHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\StringHelper;
use WC_Order;

/**
 * Poynt webhooks subscriber.
 *
 * @since 1.3.0
 */
class PoyntWebhooksHandler extends WebhooksHandler
{
    /** @var string resource value for orders webhooks */
    const EVENT_RESOURCE_ORDERS = '/orders';

    /** @var string resource value for transactions webhooks */
    const EVENT_RESOURCE_TRANSACTIONS = '/transactions';

    /**
     * PoyntWebhooksHandler constructor.
     *
     * @since 1.3.0
     */
    public function __construct()
    {
        $this->addHooks();
    }

    /**
     * Adds the hooks to handle webhooks.
     *
     * @since 1.3.0
     *
     * @return void
     */
    protected function addHooks()
    {
        add_action('woocommerce_api_poynt', [$this, 'webhookCallback']);
    }

    /**
     * Webhook callback.
     *
     * @internal
     *
     * @since 1.3.0
     *
     * @return void
     * @throws Exception
     */
    public function webhookCallback()
    {
        $this->setHeaders($this->getRequestHeaders());
        $this->setPayload($this->getRequestPayload());

        if ($this->validateWebhook()) {
            $this->loadPayloadHandler();
        }
    }

    /**
     * Loads the payload handler class depending on the resource type.
     *
     * @since 1.3.0
     *
     * @throws Exception
     */
    public function loadPayloadHandler()
    {
        if (! poynt_for_woocommerce()->is_plugin_active('woocommerce.php')) {
            return;
        }

        $payload = $this->getPayloadDecoded();
        $resource = ArrayHelper::get($payload, 'resource');

        $webhookHandler = null;

        switch ($resource) {
            case static::EVENT_RESOURCE_ORDERS:
                $webhookHandler = new PoyntOrderWebhookHandler();
                break;
            case static::EVENT_RESOURCE_TRANSACTIONS:
                $webhookHandler = new PoyntTransactionWebhookHandler();
                break;
            default:
                return;
        }

        $webhookHandler->handlePayload($payload);
    }

    /**
     * Gets the request headers.
     *
     * @since 1.3.0
     *
     * @return array
     */
    protected function getRequestHeaders() : array
    {
        return ArrayHelper::where(ArrayHelper::wrap($_SERVER), function ($value, $key) {
            return StringHelper::startsWith($key, 'HTTP_') || StringHelper::startsWith($key, 'CONTENT_');
        });
    }

    /**
     * Gets the request payload.
     *
     * @since 1.3.0
     *
     * @return string
     */
    protected function getRequestPayload() : string
    {
        return file_get_contents('php://input') ?: '';
    }

    /**
     * Validates a webhook.
     *
     * @since 1.3.0
     *
     * @return bool
     * @throws Exception
     */
    public function validateWebhook() : bool
    {
        $signature = base64_encode(hash_hmac('sha1', $this->getPayload(), GatewayAPI::getWebhookSecret(), true));

        if (! hash_equals($signature, ArrayHelper::get($this->getHeaders(), 'HTTP_POYNT_WEBHOOK_SIGNATURE'))) {
            poynt_for_woocommerce()->log("Failed to validate Poynt webhook signature for incoming webhook:\n".$this->getPayload());

            return false;
        }

        return StringHelper::isJson($this->getPayload());
    }

    /**
     * Find the corresponding WC Order based on the poynt remote order id.
     *
     * @since 1.3.0
     *
     * @param string $orderId the remote poynt order id to search for
     * @return WC_Order|null
     * @throws Exception
     */
    protected function findOrderByPoyntOrderId(string $orderId)
    {
        return $this->findOrderByPoyntId($orderId, '_wc_poynt_order_remoteId');
    }

    /**
     * Find the corresponding WC Order based on the poynt remote order id.
     *
     * @since 1.3.0
     *
     * @param string $transactionId the remote poynt transaction id to search for
     * @return WC_Order|null
     * @throws Exception
     */
    protected function findOrderByPoyntTransactionId(string $transactionId)
    {
        return $this->findOrderByPoyntId($transactionId, '_wc_poynt_credit_card_trans_id');
    }

    /**
     * Find the corresponding WC Order based on the poynt order or transaction id.
     *
     * @since 1.3.0
     *
     * @param string $value meta value to search
     * @param string $metaKey meta key to search against
     * @param string $post_type the post type to search for. defaults to 'shop_order'
     * @return WC_Order|null
     * @throws Exception
     */
    protected function findOrderByPoyntId(string $value, string $metaKey, string $post_type = 'shop_order')
    {
        if (! $value) {
            return null;
        }

        $args = [
            'post_type'   => $post_type,
            'fields'      => 'ids',
            'post_status' => 'any',
            'meta_key'    => $metaKey,
            'meta_value'  => $value,
        ];

        if ($results = wc_get_orders($args)) {
            return wc_get_order(ArrayHelper::get($results, 0));
        }

        return null;
    }
}
