<?php

namespace Servebolt\Optimizer\Dependencies\GuzzleHttp;

use Servebolt\Optimizer\Dependencies\Psr\Http\Message\MessageInterface;

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
            ? \Servebolt\Optimizer\Dependencies\GuzzleHttp\Psr7\Message::bodySummary($message)
            : \Servebolt\Optimizer\Dependencies\GuzzleHttp\Psr7\Message::bodySummary($message, $this->truncateAt);
    }
}
