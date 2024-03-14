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
namespace DhlVendor\Monolog\Handler;

use DhlVendor\Rollbar\RollbarLogger;
use Throwable;
use DhlVendor\Monolog\Logger;
/**
 * Sends errors to Rollbar
 *
 * If the context data contains a `payload` key, that is used as an array
 * of payload options to RollbarLogger's log method.
 *
 * Rollbar's context info will contain the context + extra keys from the log record
 * merged, and then on top of that a few keys:
 *
 *  - level (rollbar level name)
 *  - monolog_level (monolog level name, raw level, as rollbar only has 5 but monolog 8)
 *  - channel
 *  - datetime (unix timestamp)
 *
 * @author Paul Statezny <paulstatezny@gmail.com>
 */
class RollbarHandler extends \DhlVendor\Monolog\Handler\AbstractProcessingHandler
{
    /**
     * @var RollbarLogger
     */
    protected $rollbarLogger;
    /** @var string[] */
    protected $levelMap = [\DhlVendor\Monolog\Logger::DEBUG => 'debug', \DhlVendor\Monolog\Logger::INFO => 'info', \DhlVendor\Monolog\Logger::NOTICE => 'info', \DhlVendor\Monolog\Logger::WARNING => 'warning', \DhlVendor\Monolog\Logger::ERROR => 'error', \DhlVendor\Monolog\Logger::CRITICAL => 'critical', \DhlVendor\Monolog\Logger::ALERT => 'critical', \DhlVendor\Monolog\Logger::EMERGENCY => 'critical'];
    /**
     * Records whether any log records have been added since the last flush of the rollbar notifier
     *
     * @var bool
     */
    private $hasRecords = \false;
    /** @var bool */
    protected $initialized = \false;
    /**
     * @param RollbarLogger $rollbarLogger RollbarLogger object constructed with valid token
     */
    public function __construct(\DhlVendor\Rollbar\RollbarLogger $rollbarLogger, $level = \DhlVendor\Monolog\Logger::ERROR, bool $bubble = \true)
    {
        $this->rollbarLogger = $rollbarLogger;
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritDoc}
     */
    protected function write(array $record) : void
    {
        if (!$this->initialized) {
            // __destructor() doesn't get called on Fatal errors
            \register_shutdown_function(array($this, 'close'));
            $this->initialized = \true;
        }
        $context = $record['context'];
        $context = \array_merge($context, $record['extra'], ['level' => $this->levelMap[$record['level']], 'monolog_level' => $record['level_name'], 'channel' => $record['channel'], 'datetime' => $record['datetime']->format('U')]);
        if (isset($context['exception']) && $context['exception'] instanceof \Throwable) {
            $exception = $context['exception'];
            unset($context['exception']);
            $toLog = $exception;
        } else {
            $toLog = $record['message'];
        }
        // @phpstan-ignore-next-line
        $this->rollbarLogger->log($context['level'], $toLog, $context);
        $this->hasRecords = \true;
    }
    public function flush() : void
    {
        if ($this->hasRecords) {
            $this->rollbarLogger->flush();
            $this->hasRecords = \false;
        }
    }
    /**
     * {@inheritDoc}
     */
    public function close() : void
    {
        $this->flush();
    }
    /**
     * {@inheritDoc}
     */
    public function reset()
    {
        $this->flush();
        parent::reset();
    }
}
