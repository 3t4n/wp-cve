<?php

namespace Isolated\Blue_Media\Isolated_Php_ga4_mp\GuzzleHttp;

use Isolated\Blue_Media\Isolated_Php_ga4_mp\Psr\Http\Message\MessageInterface;
interface BodySummarizerInterface
{
    /**
     * Returns a summarized message body.
     */
    public function summarize(MessageInterface $message) : ?string;
}
