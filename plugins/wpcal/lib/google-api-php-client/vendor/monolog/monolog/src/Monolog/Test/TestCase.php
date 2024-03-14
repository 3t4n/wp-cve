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
namespace WPCal\GoogleAPI\Monolog\Test;

use WPCal\GoogleAPI\Monolog\Logger;
use WPCal\GoogleAPI\Monolog\DateTimeImmutable;
use WPCal\GoogleAPI\Monolog\Formatter\FormatterInterface;
/**
 * Lets you easily generate log records and a dummy formatter for testing purposes
 * *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class TestCase extends \WPCal\GoogleAPI\PHPUnit\Framework\TestCase
{
    /**
     * @return array Record
     */
    protected function getRecord($level = \WPCal\GoogleAPI\Monolog\Logger::WARNING, $message = 'test', array $context = []) : array
    {
        return ['message' => (string) $message, 'context' => $context, 'level' => $level, 'level_name' => \WPCal\GoogleAPI\Monolog\Logger::getLevelName($level), 'channel' => 'test', 'datetime' => new \WPCal\GoogleAPI\Monolog\DateTimeImmutable(\true), 'extra' => []];
    }
    protected function getMultipleRecords() : array
    {
        return [$this->getRecord(\WPCal\GoogleAPI\Monolog\Logger::DEBUG, 'debug message 1'), $this->getRecord(\WPCal\GoogleAPI\Monolog\Logger::DEBUG, 'debug message 2'), $this->getRecord(\WPCal\GoogleAPI\Monolog\Logger::INFO, 'information'), $this->getRecord(\WPCal\GoogleAPI\Monolog\Logger::WARNING, 'warning'), $this->getRecord(\WPCal\GoogleAPI\Monolog\Logger::ERROR, 'error')];
    }
    /**
     * @suppress PhanTypeMismatchReturn
     */
    protected function getIdentityFormatter() : \WPCal\GoogleAPI\Monolog\Formatter\FormatterInterface
    {
        $formatter = $this->createMock(\WPCal\GoogleAPI\Monolog\Formatter\FormatterInterface::class);
        $formatter->expects($this->any())->method('format')->will($this->returnCallback(function ($record) {
            return $record['message'];
        }));
        return $formatter;
    }
}
