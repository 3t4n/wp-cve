<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception;

/**
 * Class EndpointException.
 *
 * @package CKPL\Pay\Exception
 */
class EndpointException extends ClientException
{
    /**
     * @type string
     */
    const MISSING_RESPONSE_PARAMETER = 'Parameter %s is missing in response.';

    /**
     * @type string
     */
    const MISSING_REQUEST_PARAMETERS = 'Parameter "%s" is missing in request.';
}
