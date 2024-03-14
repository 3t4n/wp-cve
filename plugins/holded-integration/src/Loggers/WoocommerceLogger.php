<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Loggers;

use Psr\Log\LoggerInterface;

final class WoocommerceLogger implements LoggerInterface
{
    public function emergency($message, array $context = [])
    {
        $context['severity'] = 'EMERGENCY';

        $this->log(3, $message, $context);
    }

    public function alert($message, array $context = [])
    {
        $context['severity'] = 'ALERT';

        $this->log(3, $message, $context);
    }

    public function critical($message, array $context = [])
    {
        $context['severity'] = 'CRITICAL';

        $this->log(3, $message, $context);
    }

    public function error($message, array $context = [])
    {
        $context['severity'] = 'ERROR';

        $this->log(3, $message, $context);
    }

    public function warning($message, array $context = [])
    {
        $context['severity'] = 'WARNING';

        $this->log(2, $message, $context);
    }

    public function notice($message, array $context = [])
    {
        $context['severity'] = 'NOTICE';

        $this->log(1, $message, $context);
    }

    public function info($message, array $context = [])
    {
        $context['severity'] = 'INFO';

        $this->log(1, $message, $context);
    }

    public function debug($message, array $context = [])
    {
        $context['severity'] = 'DEBUG';

        $this->log(1, $message, $context);
    }

    public function log($level, $message, array $context = [])
    {
        $message = $this->addSeverityToMessage($context['severity'] ?? 'LOG', $message);
        $message = $this->addContextToMessage($message, $context);

        $this->saveLog($message);
    }

    private function addSeverityToMessage(string $severity, string $message): string
    {
        return sprintf('[HOLDED %s] %s', $severity, $message);
    }

    /**
     * @param mixed[] $context
     */
    private function addContextToMessage(string $message, array $context = []): string
    {
        return sprintf('%s | [CONTEXT]: %s', $message, json_encode($context));
    }

    private function saveLog(string $message): void
    {
        /** @var string[] $logs */
        $logs = get_transient('holded_log') ?: [];
        $logs[] = $message;

        // Save only ten last logs for avoid overflow of transient
        if (count($logs) > 10) {
            array_shift($logs);
        }

        set_transient('holded_log', $logs, 24 * 60 * 60);
    }
}
