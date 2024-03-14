<?php

namespace WPCal\GoogleAPI\GuzzleHttp\Promise;

/**
 * Exception that is set as the reason for a promise that has been cancelled.
 */
class CancellationException extends \WPCal\GoogleAPI\GuzzleHttp\Promise\RejectionException
{
}
