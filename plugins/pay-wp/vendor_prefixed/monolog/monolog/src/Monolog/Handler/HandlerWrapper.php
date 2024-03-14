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
namespace WPPayVendor\Monolog\Handler;

use WPPayVendor\Monolog\ResettableInterface;
use WPPayVendor\Monolog\Formatter\FormatterInterface;
/**
 * This simple wrapper class can be used to extend handlers functionality.
 *
 * Example: A custom filtering that can be applied to any handler.
 *
 * Inherit from this class and override handle() like this:
 *
 *   public function handle(array $record)
 *   {
 *        if ($record meets certain conditions) {
 *            return false;
 *        }
 *        return $this->handler->handle($record);
 *   }
 *
 * @author Alexey Karapetov <alexey@karapetov.com>
 */
class HandlerWrapper implements \WPPayVendor\Monolog\Handler\HandlerInterface, \WPPayVendor\Monolog\Handler\ProcessableHandlerInterface, \WPPayVendor\Monolog\Handler\FormattableHandlerInterface, \WPPayVendor\Monolog\ResettableInterface
{
    /**
     * @var HandlerInterface
     */
    protected $handler;
    public function __construct(\WPPayVendor\Monolog\Handler\HandlerInterface $handler)
    {
        $this->handler = $handler;
    }
    /**
     * {@inheritDoc}
     */
    public function isHandling(array $record) : bool
    {
        return $this->handler->isHandling($record);
    }
    /**
     * {@inheritDoc}
     */
    public function handle(array $record) : bool
    {
        return $this->handler->handle($record);
    }
    /**
     * {@inheritDoc}
     */
    public function handleBatch(array $records) : void
    {
        $this->handler->handleBatch($records);
    }
    /**
     * {@inheritDoc}
     */
    public function close() : void
    {
        $this->handler->close();
    }
    /**
     * {@inheritDoc}
     */
    public function pushProcessor(callable $callback) : \WPPayVendor\Monolog\Handler\HandlerInterface
    {
        if ($this->handler instanceof \WPPayVendor\Monolog\Handler\ProcessableHandlerInterface) {
            $this->handler->pushProcessor($callback);
            return $this;
        }
        throw new \LogicException('The wrapped handler does not implement ' . \WPPayVendor\Monolog\Handler\ProcessableHandlerInterface::class);
    }
    /**
     * {@inheritDoc}
     */
    public function popProcessor() : callable
    {
        if ($this->handler instanceof \WPPayVendor\Monolog\Handler\ProcessableHandlerInterface) {
            return $this->handler->popProcessor();
        }
        throw new \LogicException('The wrapped handler does not implement ' . \WPPayVendor\Monolog\Handler\ProcessableHandlerInterface::class);
    }
    /**
     * {@inheritDoc}
     */
    public function setFormatter(\WPPayVendor\Monolog\Formatter\FormatterInterface $formatter) : \WPPayVendor\Monolog\Handler\HandlerInterface
    {
        if ($this->handler instanceof \WPPayVendor\Monolog\Handler\FormattableHandlerInterface) {
            $this->handler->setFormatter($formatter);
            return $this;
        }
        throw new \LogicException('The wrapped handler does not implement ' . \WPPayVendor\Monolog\Handler\FormattableHandlerInterface::class);
    }
    /**
     * {@inheritDoc}
     */
    public function getFormatter() : \WPPayVendor\Monolog\Formatter\FormatterInterface
    {
        if ($this->handler instanceof \WPPayVendor\Monolog\Handler\FormattableHandlerInterface) {
            return $this->handler->getFormatter();
        }
        throw new \LogicException('The wrapped handler does not implement ' . \WPPayVendor\Monolog\Handler\FormattableHandlerInterface::class);
    }
    public function reset()
    {
        if ($this->handler instanceof \WPPayVendor\Monolog\ResettableInterface) {
            $this->handler->reset();
        }
    }
}
