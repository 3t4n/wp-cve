<?php

namespace RichardMuvirimi\WooCustomGateway\Vendor\GuzzleHttp;

use RichardMuvirimi\WooCustomGateway\Vendor\Psr\Http\Message\MessageInterface;

interface BodySummarizerInterface
{
    /**
     * Returns a summarized message body.
     */
    public function summarize(MessageInterface $message): ?string;
}
