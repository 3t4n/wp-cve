<?php

namespace Servebolt\Optimizer\Dependencies\GuzzleHttp;

use Servebolt\Optimizer\Dependencies\Psr\Http\Message\MessageInterface;

interface BodySummarizerInterface
{
    /**
     * Returns a summarized message body.
     */
    public function summarize(MessageInterface $message): ?string;
}
