<?php

namespace Modular\ConnectorDependencies\GuzzleHttp;

use Modular\ConnectorDependencies\Psr\Http\Message\MessageInterface;
/** @internal */
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
        return $this->truncateAt === null ? \Modular\ConnectorDependencies\GuzzleHttp\Psr7\Message::bodySummary($message) : \Modular\ConnectorDependencies\GuzzleHttp\Psr7\Message::bodySummary($message, $this->truncateAt);
    }
}
