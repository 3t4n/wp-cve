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

use OctolizeShippingNoticesVendor\Monolog\Logger;
use OctolizeShippingNoticesVendor\Monolog\Formatter\NormalizerFormatter;
use OctolizeShippingNoticesVendor\Monolog\Formatter\FormatterInterface;
use OctolizeShippingNoticesVendor\Doctrine\CouchDB\CouchDBClient;
/**
 * CouchDB handler for Doctrine CouchDB ODM
 *
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class DoctrineCouchDBHandler extends \OctolizeShippingNoticesVendor\Monolog\Handler\AbstractProcessingHandler
{
    /** @var CouchDBClient */
    private $client;
    public function __construct(\OctolizeShippingNoticesVendor\Doctrine\CouchDB\CouchDBClient $client, $level = \OctolizeShippingNoticesVendor\Monolog\Logger::DEBUG, bool $bubble = \true)
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
    protected function getDefaultFormatter() : \OctolizeShippingNoticesVendor\Monolog\Formatter\FormatterInterface
    {
        return new \OctolizeShippingNoticesVendor\Monolog\Formatter\NormalizerFormatter();
    }
}
