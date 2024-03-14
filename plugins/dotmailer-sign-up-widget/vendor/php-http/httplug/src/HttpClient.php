<?php

namespace Dotdigital_WordPress_Vendor\Http\Client;

use Dotdigital_WordPress_Vendor\Psr\Http\Client\ClientInterface;
/**
 * {@inheritdoc}
 *
 * Provide the Httplug HttpClient interface for BC.
 * You should typehint Psr\Http\Client\ClientInterface in new code
 *
 * @deprecated since version 2.4, use Psr\Http\Client\ClientInterface instead; see https://www.php-fig.org/psr/psr-18/
 */
interface HttpClient extends ClientInterface
{
}
