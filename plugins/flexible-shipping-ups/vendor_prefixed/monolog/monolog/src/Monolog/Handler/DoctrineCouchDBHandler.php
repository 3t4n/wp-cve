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

use UpsFreeVendor\Monolog\Logger;
use UpsFreeVendor\Monolog\Formatter\NormalizerFormatter;
use UpsFreeVendor\Monolog\Formatter\FormatterInterface;
use UpsFreeVendor\Doctrine\CouchDB\CouchDBClient;
/**
 * CouchDB handler for Doctrine CouchDB ODM
 *
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class DoctrineCouchDBHandler extends \UpsFreeVendor\Monolog\Handler\AbstractProcessingHandler
{
    /** @var CouchDBClient */
    private $client;
    public function __construct(\UpsFreeVendor\Doctrine\CouchDB\CouchDBClient $client, $level = \UpsFreeVendor\Monolog\Logger::DEBUG, bool $bubble = \true)
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
    protected function getDefaultFormatter() : \UpsFreeVendor\Monolog\Formatter\FormatterInterface
    {
        return new \UpsFreeVendor\Monolog\Formatter\NormalizerFormatter();
    }
}
