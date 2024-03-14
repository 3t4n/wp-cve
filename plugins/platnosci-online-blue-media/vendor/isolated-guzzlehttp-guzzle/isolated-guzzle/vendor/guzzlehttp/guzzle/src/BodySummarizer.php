<?php

namespace Isolated\Blue_Media\Isolated_Guzzlehttp\GuzzleHttp;

use Isolated\Blue_Media\Isolated_Guzzlehttp\Psr\Http\Message\MessageInterface;
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
    public function summarize(MessageInterface $message) : ?string
    {
        return $this->truncateAt === null ? \Isolated\Blue_Media\Isolated_Guzzlehttp\GuzzleHttp\Psr7\Message::bodySummary($message) : \Isolated\Blue_Media\Isolated_Guzzlehttp\GuzzleHttp\Psr7\Message::bodySummary($message, $this->truncateAt);
    }
}
