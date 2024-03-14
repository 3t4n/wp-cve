<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API\Transactions;

use GoDaddy\WooCommerce\Poynt\API\Requests\AbstractBusinessRequest;

defined('ABSPATH') or exit;

/**
 * Void payment request.
 *
 * @since 1.0.0
 */
class VoidRequest extends AbstractBusinessRequest
{
    /**
     * Void request constructor.
     *
     * @since 1.0.0
     *
     * @param string $businessId the business ID
     * @param string $transactionId the transaction ID
     */
    public function __construct(string $businessId, string $transactionId)
    {
        parent::__construct($businessId);

        $this->path = "{$this->path}/transactions/{$transactionId}/void";

        $this->data = [
            'context' => [
                'sourceApp' => $this->getContextSourceApp(),
            ],
        ];
    }
}
