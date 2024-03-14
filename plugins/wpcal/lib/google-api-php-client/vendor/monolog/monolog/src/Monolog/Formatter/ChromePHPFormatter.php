<?php

declare (strict_types=1);
/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPCal\GoogleAPI\Monolog\Formatter;

use WPCal\GoogleAPI\Monolog\Logger;
/**
 * Formats a log message according to the ChromePHP array format
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ChromePHPFormatter implements \WPCal\GoogleAPI\Monolog\Formatter\FormatterInterface
{
    /**
     * Translates Monolog log levels to Wildfire levels.
     */
    private $logLevels = [\WPCal\GoogleAPI\Monolog\Logger::DEBUG => 'log', \WPCal\GoogleAPI\Monolog\Logger::INFO => 'info', \WPCal\GoogleAPI\Monolog\Logger::NOTICE => 'info', \WPCal\GoogleAPI\Monolog\Logger::WARNING => 'warn', \WPCal\GoogleAPI\Monolog\Logger::ERROR => 'error', \WPCal\GoogleAPI\Monolog\Logger::CRITICAL => 'error', \WPCal\GoogleAPI\Monolog\Logger::ALERT => 'error', \WPCal\GoogleAPI\Monolog\Logger::EMERGENCY => 'error'];
    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        // Retrieve the line and file if set and remove them from the formatted extra
        $backtrace = 'unknown';
        if (isset($record['extra']['file'], $record['extra']['line'])) {
            $backtrace = $record['extra']['file'] . ' : ' . $record['extra']['line'];
            unset($record['extra']['file'], $record['extra']['line']);
        }
        $message = ['message' => $record['message']];
        if ($record['context']) {
            $message['context'] = $record['context'];
        }
        if ($record['extra']) {
            $message['extra'] = $record['extra'];
        }
        if (\count($message) === 1) {
            $message = \reset($message);
        }
        return [$record['channel'], $message, $backtrace, $this->logLevels[$record['level']]];
    }
    /**
     * {@inheritdoc}
     */
    public function formatBatch(array $records)
    {
        $formatted = [];
        foreach ($records as $record) {
            $formatted[] = $this->format($record);
        }
        return $formatted;
    }
}
