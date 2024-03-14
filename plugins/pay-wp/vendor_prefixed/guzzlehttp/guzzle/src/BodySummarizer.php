<?php

namespace WPPayVendor\GuzzleHttp;

use WPPayVendor\Psr\Http\Message\MessageInterface;
final class BodySummarizer implements \WPPayVendor\GuzzleHttp\BodySummarizerInterface
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
    public function summarize(\WPPayVendor\Psr\Http\Message\MessageInterface $message) : ?string
    {
        return $this->truncateAt === null ? \WPPayVendor\GuzzleHttp\Psr7\Message::bodySummary($message) : \WPPayVendor\GuzzleHttp\Psr7\Message::bodySummary($message, $this->truncateAt);
    }
}
