<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API\Cards;

use GoDaddy\WooCommerce\Poynt\API\Requests\AbstractBusinessRequest;

defined('ABSPATH') or exit;

/**
 * Tokenize request.
 *
 * @since 1.0.0
 */
class TokenizeRequest extends AbstractBusinessRequest
{
    /**
     * Tokenize request constructor.
     *
     * @since 1.0.0
     *
     * @param string $businessId the business ID
     * @param string $nonce the request nonce
     */
    public function __construct(string $businessId, string $nonce)
    {
        parent::__construct($businessId);

        $this->path = "{$this->path}/cards/tokenize";

        $this->data = [
            'nonce' => $nonce,
        ];
    }
}
