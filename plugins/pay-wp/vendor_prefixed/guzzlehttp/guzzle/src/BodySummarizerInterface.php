<?php

namespace WPPayVendor\GuzzleHttp;

use WPPayVendor\Psr\Http\Message\MessageInterface;
interface BodySummarizerInterface
{
    /**
     * Returns a summarized message body.
     */
    public function summarize(\WPPayVendor\Psr\Http\Message\MessageInterface $message) : ?string;
}
