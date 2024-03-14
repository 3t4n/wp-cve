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

use OctolizeShippingNoticesVendor\Elastica\Document;
use OctolizeShippingNoticesVendor\Monolog\Formatter\FormatterInterface;
use OctolizeShippingNoticesVendor\Monolog\Formatter\ElasticaFormatter;
use OctolizeShippingNoticesVendor\Monolog\Logger;
use OctolizeShippingNoticesVendor\Elastica\Client;
use OctolizeShippingNoticesVendor\Elastica\Exception\ExceptionInterface;
/**
 * Elastic Search handler
 *
 * Usage example:
 *
 *    $client = new \Elastica\Client();
 *    $options = array(
 *        'index' => 'elastic_index_name',
 *        'type' => 'elastic_doc_type', Types have been removed in Elastica 7
 *    );
 *    $handler = new ElasticaHandler($client, $options);
 *    $log = new Logger('application');
 *    $log->pushHandler($handler);
 *
 * @author Jelle Vink <jelle.vink@gmail.com>
 */
class ElasticaHandler extends \OctolizeShippingNoticesVendor\Monolog\Handler\AbstractProcessingHandler
{
    /**
     * @var Client
     */
    protected $client;
    /**
     * @var mixed[] Handler config options
     */
    protected $options = [];
    /**
     * @param Client  $client  Elastica Client object
     * @param mixed[] $options Handler configuration
     */
    public function __construct(\OctolizeShippingNoticesVendor\Elastica\Client $client, array $options = [], $level = \OctolizeShippingNoticesVendor\Monolog\Logger::DEBUG, bool $bubble = \true)
    {
        parent::__construct($level, $bubble);
        $this->client = $client;
        $this->options = \array_merge([
            'index' => 'monolog',
            // Elastic index name
            'type' => 'record',
            // Elastic document type
            'ignore_error' => \false,
        ], $options);
    }
    /**
     * {@inheritDoc}
     */
    protected function write(array $record) : void
    {
        $this->bulkSend([$record['formatted']]);
    }
    /**
     * {@inheritDoc}
     */
    public function setFormatter(\OctolizeShippingNoticesVendor\Monolog\Formatter\FormatterInterface $formatter) : \OctolizeShippingNoticesVendor\Monolog\Handler\HandlerInterface
    {
        if ($formatter instanceof \OctolizeShippingNoticesVendor\Monolog\Formatter\ElasticaFormatter) {
            return parent::setFormatter($formatter);
        }
        throw new \InvalidArgumentException('ElasticaHandler is only compatible with ElasticaFormatter');
    }
    /**
     * @return mixed[]
     */
    public function getOptions() : array
    {
        return $this->options;
    }
    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter() : \OctolizeShippingNoticesVendor\Monolog\Formatter\FormatterInterface
    {
        return new \OctolizeShippingNoticesVendor\Monolog\Formatter\ElasticaFormatter($this->options['index'], $this->options['type']);
    }
    /**
     * {@inheritDoc}
     */
    public function handleBatch(array $records) : void
    {
        $documents = $this->getFormatter()->formatBatch($records);
        $this->bulkSend($documents);
    }
    /**
     * Use Elasticsearch bulk API to send list of documents
     *
     * @param Document[] $documents
     *
     * @throws \RuntimeException
     */
    protected function bulkSend(array $documents) : void
    {
        try {
            $this->client->addDocuments($documents);
        } catch (\OctolizeShippingNoticesVendor\Elastica\Exception\ExceptionInterface $e) {
            if (!$this->options['ignore_error']) {
                throw new \RuntimeException("Error sending messages to Elasticsearch", 0, $e);
            }
        }
    }
}
