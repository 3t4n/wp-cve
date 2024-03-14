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
namespace OctolizeShippingNoticesVendor\Monolog\Handler;

use OctolizeShippingNoticesVendor\Monolog\ResettableInterface;
use OctolizeShippingNoticesVendor\Monolog\Formatter\FormatterInterface;
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
class HandlerWrapper implements \OctolizeShippingNoticesVendor\Monolog\Handler\HandlerInterface, \OctolizeShippingNoticesVendor\Monolog\Handler\ProcessableHandlerInterface, \OctolizeShippingNoticesVendor\Monolog\Handler\FormattableHandlerInterface, \OctolizeShippingNoticesVendor\Monolog\ResettableInterface
{
    /**
     * @var HandlerInterface
     */
    protected $handler;
    public function __construct(\OctolizeShippingNoticesVendor\Monolog\Handler\HandlerInterface $handler)
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
    public function pushProcessor(callable $callback) : \OctolizeShippingNoticesVendor\Monolog\Handler\HandlerInterface
    {
        if ($this->handler instanceof \OctolizeShippingNoticesVendor\Monolog\Handler\ProcessableHandlerInterface) {
            $this->handler->pushProcessor($callback);
            return $this;
        }
        throw new \LogicException('The wrapped handler does not implement ' . \OctolizeShippingNoticesVendor\Monolog\Handler\ProcessableHandlerInterface::class);
    }
    /**
     * {@inheritDoc}
     */
    public function popProcessor() : callable
    {
        if ($this->handler instanceof \OctolizeShippingNoticesVendor\Monolog\Handler\ProcessableHandlerInterface) {
            return $this->handler->popProcessor();
        }
        throw new \LogicException('The wrapped handler does not implement ' . \OctolizeShippingNoticesVendor\Monolog\Handler\ProcessableHandlerInterface::class);
    }
    /**
     * {@inheritDoc}
     */
    public function setFormatter(\OctolizeShippingNoticesVendor\Monolog\Formatter\FormatterInterface $formatter) : \OctolizeShippingNoticesVendor\Monolog\Handler\HandlerInterface
    {
        if ($this->handler instanceof \OctolizeShippingNoticesVendor\Monolog\Handler\FormattableHandlerInterface) {
            $this->handler->setFormatter($formatter);
            return $this;
        }
        throw new \LogicException('The wrapped handler does not implement ' . \OctolizeShippingNoticesVendor\Monolog\Handler\FormattableHandlerInterface::class);
    }
    /**
     * {@inheritDoc}
     */
    public function getFormatter() : \OctolizeShippingNoticesVendor\Monolog\Formatter\FormatterInterface
    {
        if ($this->handler instanceof \OctolizeShippingNoticesVendor\Monolog\Handler\FormattableHandlerInterface) {
            return $this->handler->getFormatter();
        }
        throw new \LogicException('The wrapped handler does not implement ' . \OctolizeShippingNoticesVendor\Monolog\Handler\FormattableHandlerInterface::class);
    }
    public function reset()
    {
        if ($this->handler instanceof \OctolizeShippingNoticesVendor\Monolog\ResettableInterface) {
            $this->handler->reset();
        }
    }
}
