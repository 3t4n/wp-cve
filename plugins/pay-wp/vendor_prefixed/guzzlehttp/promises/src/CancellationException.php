<?php

namespace WPPayVendor\GuzzleHttp\Promise;

/**
 * Exception that is set as the reason for a promise that has been cancelled.
 */
class CancellationException extends \WPPayVendor\GuzzleHttp\Promise\RejectionException
{
}
