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

use DropshippingXmlFreeVendor\Monolog\Formatter\FormatterInterface;
use DropshippingXmlFreeVendor\Monolog\Formatter\LineFormatter;
/**
 * Helper trait for implementing FormattableInterface
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
trait FormattableHandlerTrait
{
    /**
     * @var ?FormatterInterface
     */
    protected $formatter;
    /**
     * {@inheritDoc}
     */
    public function setFormatter(\DropshippingXmlFreeVendor\Monolog\Formatter\FormatterInterface $formatter) : \DropshippingXmlFreeVendor\Monolog\Handler\HandlerInterface
    {
        $this->formatter = $formatter;
        return $this;
    }
    /**
     * {@inheritDoc}
     */
    public function getFormatter() : \DropshippingXmlFreeVendor\Monolog\Formatter\FormatterInterface
    {
        if (!$this->formatter) {
            $this->formatter = $this->getDefaultFormatter();
        }
        return $this->formatter;
    }
    /**
     * Gets the default formatter.
     *
     * Overwrite this if the LineFormatter is not a good default for your handler.
     */
    protected function getDefaultFormatter() : \DropshippingXmlFreeVendor\Monolog\Formatter\FormatterInterface
    {
        return new \DropshippingXmlFreeVendor\Monolog\Formatter\LineFormatter();
    }
}
