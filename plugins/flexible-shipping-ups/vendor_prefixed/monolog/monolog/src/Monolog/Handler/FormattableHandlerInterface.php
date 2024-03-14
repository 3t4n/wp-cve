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
namespace UpsFreeVendor\Monolog\Handler;

use UpsFreeVendor\Monolog\Formatter\FormatterInterface;
/**
 * Interface to describe loggers that have a formatter
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
interface FormattableHandlerInterface
{
    /**
     * Sets the formatter.
     *
     * @param  FormatterInterface $formatter
     * @return HandlerInterface   self
     */
    public function setFormatter(\UpsFreeVendor\Monolog\Formatter\FormatterInterface $formatter) : \UpsFreeVendor\Monolog\Handler\HandlerInterface;
    /**
     * Gets the formatter.
     *
     * @return FormatterInterface
     */
    public function getFormatter() : \UpsFreeVendor\Monolog\Formatter\FormatterInterface;
}
