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
namespace WPDeskFIVendor\Monolog\Handler;

use WPDeskFIVendor\Monolog\Formatter\FormatterInterface;
use WPDeskFIVendor\Monolog\Formatter\LineFormatter;
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
    public function setFormatter(\WPDeskFIVendor\Monolog\Formatter\FormatterInterface $formatter) : \WPDeskFIVendor\Monolog\Handler\HandlerInterface
    {
        $this->formatter = $formatter;
        return $this;
    }
    /**
     * {@inheritDoc}
     */
    public function getFormatter() : \WPDeskFIVendor\Monolog\Formatter\FormatterInterface
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
    protected function getDefaultFormatter() : \WPDeskFIVendor\Monolog\Formatter\FormatterInterface
    {
        return new \WPDeskFIVendor\Monolog\Formatter\LineFormatter();
    }
}
