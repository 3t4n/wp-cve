<?php

namespace platy\etsy\GuzzleHttp;

use platy\etsy\Psr\Http\Message\MessageInterface;

final class BodySummarizer implements BodySummarizerInterface
{
    /**
     * @var int|null
     */
    private $truncateAt;

    public function __construct(int $truncateAt = null)
    {
        $this->truncateAt = $truncateAt;
    }

    /**
     * Returns a summarized message body.
     */
    public function summarize(MessageInterface $message): ?string
    {
        return $this->truncateAt === null
            ? \platy\etsy\GuzzleHttp\Psr7\Message::bodySummary($message)
            : \platy\etsy\GuzzleHttp\Psr7\Message::bodySummary($message, $this->truncateAt);
    }
}
