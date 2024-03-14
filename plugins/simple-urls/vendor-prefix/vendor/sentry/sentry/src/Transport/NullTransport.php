<?php

declare (strict_types=1);
namespace LassoLiteVendor\Sentry\Transport;

use LassoLiteVendor\GuzzleHttp\Promise\FulfilledPromise;
use LassoLiteVendor\GuzzleHttp\Promise\PromiseInterface;
use LassoLiteVendor\Sentry\Event;
use LassoLiteVendor\Sentry\Response;
use LassoLiteVendor\Sentry\ResponseStatus;
/**
 * This transport fakes the sending of events by just ignoring them.
 *
 * @author Stefano Arlandini <sarlandini@alice.it>
 */
final class NullTransport implements TransportInterface
{
    /**
     * {@inheritdoc}
     */
    public function send(Event $event) : PromiseInterface
    {
        return new FulfilledPromise(new Response(ResponseStatus::skipped(), $event));
    }
    /**
     * {@inheritdoc}
     */
    public function close(?int $timeout = null) : PromiseInterface
    {
        return new FulfilledPromise(\true);
    }
}
