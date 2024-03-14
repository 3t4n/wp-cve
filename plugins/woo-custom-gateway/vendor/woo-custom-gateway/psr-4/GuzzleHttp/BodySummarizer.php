<?php

namespace RichardMuvirimi\WooCustomGateway\Vendor\GuzzleHttp;

use RichardMuvirimi\WooCustomGateway\Vendor\Psr\Http\Message\MessageInterface;

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
            ? \RichardMuvirimi\WooCustomGateway\Vendor\GuzzleHttp\Psr7\Message::bodySummary($message)
            : \RichardMuvirimi\WooCustomGateway\Vendor\GuzzleHttp\Psr7\Message::bodySummary($message, $this->truncateAt);
    }
}
