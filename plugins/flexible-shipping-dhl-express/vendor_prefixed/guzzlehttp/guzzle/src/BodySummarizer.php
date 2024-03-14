<?php

namespace DhlVendor\GuzzleHttp;

use DhlVendor\Psr\Http\Message\MessageInterface;
final class BodySummarizer implements \DhlVendor\GuzzleHttp\BodySummarizerInterface
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
    public function summarize(\DhlVendor\Psr\Http\Message\MessageInterface $message) : ?string
    {
        return $this->truncateAt === null ? \DhlVendor\GuzzleHttp\Psr7\Message::bodySummary($message) : \DhlVendor\GuzzleHttp\Psr7\Message::bodySummary($message, $this->truncateAt);
    }
}
