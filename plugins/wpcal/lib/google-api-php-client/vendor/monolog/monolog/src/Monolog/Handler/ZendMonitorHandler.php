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
namespace WPCal\GoogleAPI\Monolog\Handler;

use WPCal\GoogleAPI\Monolog\Formatter\FormatterInterface;
use WPCal\GoogleAPI\Monolog\Formatter\NormalizerFormatter;
use WPCal\GoogleAPI\Monolog\Logger;
/**
 * Handler sending logs to Zend Monitor
 *
 * @author  Christian Bergau <cbergau86@gmail.com>
 * @author  Jason Davis <happydude@jasondavis.net>
 */
class ZendMonitorHandler extends \WPCal\GoogleAPI\Monolog\Handler\AbstractProcessingHandler
{
    /**
     * Monolog level / ZendMonitor Custom Event priority map
     *
     * @var array
     */
    protected $levelMap = [];
    /**
     * @param  string|int                $level  The minimum logging level at which this handler will be triggered.
     * @param  bool                      $bubble Whether the messages that are handled can bubble up the stack or not.
     * @throws MissingExtensionException
     */
    public function __construct($level = \WPCal\GoogleAPI\Monolog\Logger::DEBUG, bool $bubble = \true)
    {
        if (!\function_exists('WPCal\\GoogleAPI\\zend_monitor_custom_event')) {
            throw new \WPCal\GoogleAPI\Monolog\Handler\MissingExtensionException('You must have Zend Server installed with Zend Monitor enabled in order to use this handler');
        }
        //zend monitor constants are not defined if zend monitor is not enabled.
        $this->levelMap = [\WPCal\GoogleAPI\Monolog\Logger::DEBUG => \ZEND_MONITOR_EVENT_SEVERITY_INFO, \WPCal\GoogleAPI\Monolog\Logger::INFO => \ZEND_MONITOR_EVENT_SEVERITY_INFO, \WPCal\GoogleAPI\Monolog\Logger::NOTICE => \ZEND_MONITOR_EVENT_SEVERITY_INFO, \WPCal\GoogleAPI\Monolog\Logger::WARNING => \ZEND_MONITOR_EVENT_SEVERITY_WARNING, \WPCal\GoogleAPI\Monolog\Logger::ERROR => \ZEND_MONITOR_EVENT_SEVERITY_ERROR, \WPCal\GoogleAPI\Monolog\Logger::CRITICAL => \ZEND_MONITOR_EVENT_SEVERITY_ERROR, \WPCal\GoogleAPI\Monolog\Logger::ALERT => \ZEND_MONITOR_EVENT_SEVERITY_ERROR, \WPCal\GoogleAPI\Monolog\Logger::EMERGENCY => \ZEND_MONITOR_EVENT_SEVERITY_ERROR];
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritdoc}
     */
    protected function write(array $record) : void
    {
        $this->writeZendMonitorCustomEvent(\WPCal\GoogleAPI\Monolog\Logger::getLevelName($record['level']), $record['message'], $record['formatted'], $this->levelMap[$record['level']]);
    }
    /**
     * Write to Zend Monitor Events
     * @param string $type Text displayed in "Class Name (custom)" field
     * @param string $message Text displayed in "Error String"
     * @param mixed $formatted Displayed in Custom Variables tab
     * @param int $severity Set the event severity level (-1,0,1)
     */
    protected function writeZendMonitorCustomEvent(string $type, string $message, array $formatted, int $severity) : void
    {
        zend_monitor_custom_event($type, $message, $formatted, $severity);
    }
    /**
     * {@inheritdoc}
     */
    public function getDefaultFormatter() : \WPCal\GoogleAPI\Monolog\Formatter\FormatterInterface
    {
        return new \WPCal\GoogleAPI\Monolog\Formatter\NormalizerFormatter();
    }
    public function getLevelMap() : array
    {
        return $this->levelMap;
    }
}
