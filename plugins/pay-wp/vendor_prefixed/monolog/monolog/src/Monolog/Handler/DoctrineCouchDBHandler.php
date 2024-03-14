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

use WPPayVendor\Monolog\Logger;
use WPPayVendor\Monolog\Formatter\NormalizerFormatter;
use WPPayVendor\Monolog\Formatter\FormatterInterface;
use WPPayVendor\Doctrine\CouchDB\CouchDBClient;
/**
 * CouchDB handler for Doctrine CouchDB ODM
 *
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class DoctrineCouchDBHandler extends \WPPayVendor\Monolog\Handler\AbstractProcessingHandler
{
    /** @var CouchDBClient */
    private $client;
    public function __construct(\WPPayVendor\Doctrine\CouchDB\CouchDBClient $client, $level = \WPPayVendor\Monolog\Logger::DEBUG, bool $bubble = \true)
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
    protected function getDefaultFormatter() : \WPPayVendor\Monolog\Formatter\FormatterInterface
    {
        return new \WPPayVendor\Monolog\Formatter\NormalizerFormatter();
    }
}
