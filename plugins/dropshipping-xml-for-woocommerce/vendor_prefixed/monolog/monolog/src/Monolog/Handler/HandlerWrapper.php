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
namespace DropshippingXmlFreeVendor\Monolog\Handler;

use DropshippingXmlFreeVendor\Monolog\ResettableInterface;
use DropshippingXmlFreeVendor\Monolog\Formatter\FormatterInterface;
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
class HandlerWrapper implements \DropshippingXmlFreeVendor\Monolog\Handler\HandlerInterface, \DropshippingXmlFreeVendor\Monolog\Handler\ProcessableHandlerInterface, \DropshippingXmlFreeVendor\Monolog\Handler\FormattableHandlerInterface, \DropshippingXmlFreeVendor\Monolog\ResettableInterface
{
    /**
     * @var HandlerInterface
     */
    protected $handler;
    public function __construct(\DropshippingXmlFreeVendor\Monolog\Handler\HandlerInterface $handler)
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
    public function pushProcessor(callable $callback) : \DropshippingXmlFreeVendor\Monolog\Handler\HandlerInterface
    {
        if ($this->handler instanceof \DropshippingXmlFreeVendor\Monolog\Handler\ProcessableHandlerInterface) {
            $this->handler->pushProcessor($callback);
            return $this;
        }
        throw new \LogicException('The wrapped handler does not implement ' . \DropshippingXmlFreeVendor\Monolog\Handler\ProcessableHandlerInterface::class);
    }
    /**
     * {@inheritDoc}
     */
    public function popProcessor() : callable
    {
        if ($this->handler instanceof \DropshippingXmlFreeVendor\Monolog\Handler\ProcessableHandlerInterface) {
            return $this->handler->popProcessor();
        }
        throw new \LogicException('The wrapped handler does not implement ' . \DropshippingXmlFreeVendor\Monolog\Handler\ProcessableHandlerInterface::class);
    }
    /**
     * {@inheritDoc}
     */
    public function setFormatter(\DropshippingXmlFreeVendor\Monolog\Formatter\FormatterInterface $formatter) : \DropshippingXmlFreeVendor\Monolog\Handler\HandlerInterface
    {
        if ($this->handler instanceof \DropshippingXmlFreeVendor\Monolog\Handler\FormattableHandlerInterface) {
            $this->handler->setFormatter($formatter);
            return $this;
        }
        throw new \LogicException('The wrapped handler does not implement ' . \DropshippingXmlFreeVendor\Monolog\Handler\FormattableHandlerInterface::class);
    }
    /**
     * {@inheritDoc}
     */
    public function getFormatter() : \DropshippingXmlFreeVendor\Monolog\Formatter\FormatterInterface
    {
        if ($this->handler instanceof \DropshippingXmlFreeVendor\Monolog\Handler\FormattableHandlerInterface) {
            return $this->handler->getFormatter();
        }
        throw new \LogicException('The wrapped handler does not implement ' . \DropshippingXmlFreeVendor\Monolog\Handler\FormattableHandlerInterface::class);
    }
    public function reset()
    {
        if ($this->handler instanceof \DropshippingXmlFreeVendor\Monolog\ResettableInterface) {
            $this->handler->reset();
        }
    }
}
