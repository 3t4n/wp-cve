<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Webhooks;

/**
 * Webhooks handler interface.
 */
interface ResourceWebhookHandlerContract
{
    /**
     * Handles the event payload.
     *
     * @param array $payload payload data
     *
     * @since 1.3.0
     * @throws Exception
     */
    public function handlePayload($payload);
}
