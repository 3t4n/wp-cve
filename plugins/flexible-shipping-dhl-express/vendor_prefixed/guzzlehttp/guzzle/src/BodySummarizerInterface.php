<?php

namespace DhlVendor\GuzzleHttp;

use DhlVendor\Psr\Http\Message\MessageInterface;
interface BodySummarizerInterface
{
    /**
     * Returns a summarized message body.
     */
    public function summarize(\DhlVendor\Psr\Http\Message\MessageInterface $message) : ?string;
}
