<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Endpoint;

use CKPL\Pay\Exception\EndpointException;

/**
 * Class MakeRefundEndpointException.
 *
 * @package CKPL\Pay\Exception\Endpoint
 */
class MakeRefundEndpointException extends EndpointException
{
    /**
     * @type string
     */
    const MISSING_REQUEST_PARAMETER = 'Missing parameter "%s" in refund.';

    /**
     * @type string
     */
    const MISSING_RESPONSE_PARAMETER = 'Missing parameter "%s" in response for refund.';
}
