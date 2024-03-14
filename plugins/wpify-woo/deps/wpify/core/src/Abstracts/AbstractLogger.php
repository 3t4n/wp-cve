<?php

namespace WpifyWooDeps\Wpify\Core\Abstracts;

use WpifyWooDeps\Monolog\Logger;
use WpifyWooDeps\Wpify\Core\WordpressMonologHandler;
abstract class AbstractLogger extends AbstractComponent
{
    /**
     * Detailed debug information
     */
    public const DEBUG = 100;
    /**
     * Interesting events
     * Examples: User logs in, SQL logs.
     */
    public const INFO = 200;
    /**
     * Uncommon events
     */
    public const NOTICE = 250;
    /**
     * Exceptional occurrences that are not errors
     * Examples: Use of deprecated APIs, poor use of an API,
     * undesirable things that are not necessarily wrong.
     */
    public const WARNING = 300;
    /**
     * Runtime errors
     */
    public const ERROR = 400;
    /**
     * Critical conditions
     * Example: Application component unavailable, unexpected exception.
     */
    public const CRITICAL = 500;
    /**
     * Action must be taken immediately
     * Example: Entire website down, database unavailable, etc.
     * This should trigger the SMS alerts and wake you up.
     */
    public const ALERT = 550;
    /**
     * Urgent alert.
     */
    public const EMERGENCY = 600;
    public abstract function channel() : string;
    /**
     * Return the Monolog Handler
     *
     * @return mixed
     */
    public abstract function handler();
    /**
     * @var Logger $logger
     */
    private $logger;
    public function init()
    {
        $this->logger = new Logger($this->channel());
        $this->logger->pushHandler($this->handler());
        parent::init();
    }
    /**
     * @param $message
     * @param $data
     *
     * @return mixed
     */
    public function debug($message, $data)
    {
        return $this->logger->debug($message, $data);
    }
    /**
     * @param $message
     * @param $data
     *
     * @return mixed
     */
    public function info($message, $data)
    {
        return $this->logger->info($message, $data);
    }
    /**
     * @param $message
     * @param $data
     *
     * @return mixed
     */
    public function notice($message, $data)
    {
        return $this->logger->notice($message, $data);
    }
    /**
     * @param $message
     * @param array   $data
     *
     * @return mixed
     */
    public function warning($message, $data = array())
    {
        return $this->logger->warning($message, $data);
    }
    /**
     * @param $message
     * @param array   $data
     *
     * @return mixed
     */
    public function error($message, $data = array())
    {
        return $this->logger->error($message, $data);
    }
    /**
     * @param $message
     * @param $data
     *
     * @return mixed
     */
    public function critical($message, $data)
    {
        return $this->logger->critical($message, $data);
    }
    /**
     * @param $message
     * @param $data
     *
     * @return mixed
     */
    public function alert($message, $data)
    {
        return $this->logger->alert($message, $data);
    }
    /**
     * @param $message
     * @param $data
     *
     * @return mixed
     */
    public function emergency($message, $data)
    {
        return $this->logger->emergency($message, $data);
    }
}
