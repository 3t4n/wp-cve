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

use WPDeskFIVendor\Monolog\Logger;
use WPDeskFIVendor\Monolog\Formatter\NormalizerFormatter;
use WPDeskFIVendor\Monolog\Formatter\FormatterInterface;
use WPDeskFIVendor\Doctrine\CouchDB\CouchDBClient;
/**
 * CouchDB handler for Doctrine CouchDB ODM
 *
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class DoctrineCouchDBHandler extends \WPDeskFIVendor\Monolog\Handler\AbstractProcessingHandler
{
    /** @var CouchDBClient */
    private $client;
    public function __construct(\WPDeskFIVendor\Doctrine\CouchDB\CouchDBClient $client, $level = \WPDeskFIVendor\Monolog\Logger::DEBUG, bool $bubble = \true)
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
    protected function getDefaultFormatter() : \WPDeskFIVendor\Monolog\Formatter\FormatterInterface
    {
        return new \WPDeskFIVendor\Monolog\Formatter\NormalizerFormatter();
    }
}
