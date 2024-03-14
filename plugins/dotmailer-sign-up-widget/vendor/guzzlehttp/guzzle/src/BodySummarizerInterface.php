<?php

namespace Dotdigital_WordPress_Vendor\GuzzleHttp;

use Dotdigital_WordPress_Vendor\Psr\Http\Message\MessageInterface;
interface BodySummarizerInterface
{
    /**
     * Returns a summarized message body.
     */
    public function summarize(MessageInterface $message) : ?string;
}
