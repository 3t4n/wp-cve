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
namespace WpifyWooDeps\Monolog\Handler;

use WpifyWooDeps\Monolog\Logger;
use WpifyWooDeps\Monolog\Formatter\NormalizerFormatter;
use WpifyWooDeps\Monolog\Formatter\FormatterInterface;
use WpifyWooDeps\Doctrine\CouchDB\CouchDBClient;
/**
 * CouchDB handler for Doctrine CouchDB ODM
 *
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class DoctrineCouchDBHandler extends AbstractProcessingHandler
{
    /** @var CouchDBClient */
    private $client;
    public function __construct(CouchDBClient $client, $level = Logger::DEBUG, bool $bubble = \true)
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
    protected function getDefaultFormatter() : FormatterInterface
    {
        return new NormalizerFormatter();
    }
}
