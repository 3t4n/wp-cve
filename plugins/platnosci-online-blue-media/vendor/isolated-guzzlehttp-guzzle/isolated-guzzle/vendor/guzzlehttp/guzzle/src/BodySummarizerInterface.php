<?php

namespace Isolated\Blue_Media\Isolated_Guzzlehttp\GuzzleHttp;

use Isolated\Blue_Media\Isolated_Guzzlehttp\Psr\Http\Message\MessageInterface;
interface BodySummarizerInterface
{
    /**
     * Returns a summarized message body.
     */
    public function summarize(MessageInterface $message) : ?string;
}
