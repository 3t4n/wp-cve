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

use DropshippingXmlFreeVendor\Monolog\Logger;
use DropshippingXmlFreeVendor\Monolog\Formatter\NormalizerFormatter;
use DropshippingXmlFreeVendor\Monolog\Formatter\FormatterInterface;
use DropshippingXmlFreeVendor\Doctrine\CouchDB\CouchDBClient;
/**
 * CouchDB handler for Doctrine CouchDB ODM
 *
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class DoctrineCouchDBHandler extends \DropshippingXmlFreeVendor\Monolog\Handler\AbstractProcessingHandler
{
    /** @var CouchDBClient */
    private $client;
    public function __construct(\DropshippingXmlFreeVendor\Doctrine\CouchDB\CouchDBClient $client, $level = \DropshippingXmlFreeVendor\Monolog\Logger::DEBUG, bool $bubble = \true)
    {
        $this->client = $client;
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritDoc}
     */
    protected function write(array $record) : void
    {
        $this->client->postDocument($record['formatted']);
    }
    protected function getDefaultFormatter() : \DropshippingXmlFreeVendor\Monolog\Formatter\FormatterInterface
    {
        return new \DropshippingXmlFreeVendor\Monolog\Formatter\NormalizerFormatter();
    }
}
