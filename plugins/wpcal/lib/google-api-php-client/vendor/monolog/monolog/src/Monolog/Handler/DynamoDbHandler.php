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
namespace WPCal\GoogleAPI\Monolog\Handler;

use WPCal\GoogleAPI\Aws\Sdk;
use WPCal\GoogleAPI\Aws\DynamoDb\DynamoDbClient;
use WPCal\GoogleAPI\Monolog\Formatter\FormatterInterface;
use WPCal\GoogleAPI\Aws\DynamoDb\Marshaler;
use WPCal\GoogleAPI\Monolog\Formatter\ScalarFormatter;
use WPCal\GoogleAPI\Monolog\Logger;
/**
 * Amazon DynamoDB handler (http://aws.amazon.com/dynamodb/)
 *
 * @link https://github.com/aws/aws-sdk-php/
 * @author Andrew Lawson <adlawson@gmail.com>
 */
class DynamoDbHandler extends \WPCal\GoogleAPI\Monolog\Handler\AbstractProcessingHandler
{
    public const DATE_FORMAT = 'Y-m-d\\TH:i:s.uO';
    /**
     * @var DynamoDbClient
     */
    protected $client;
    /**
     * @var string
     */
    protected $table;
    /**
     * @var int
     */
    protected $version;
    /**
     * @var Marshaler
     */
    protected $marshaler;
    /**
     * @param int|string $level
     */
    public function __construct(\WPCal\GoogleAPI\Aws\DynamoDb\DynamoDbClient $client, string $table, $level = \WPCal\GoogleAPI\Monolog\Logger::DEBUG, bool $bubble = \true)
    {
        if (\defined('Aws\\Sdk::VERSION') && \version_compare(\WPCal\GoogleAPI\Aws\Sdk::VERSION, '3.0', '>=')) {
            $this->version = 3;
            $this->marshaler = new \WPCal\GoogleAPI\Aws\DynamoDb\Marshaler();
        } else {
            $this->version = 2;
        }
        $this->client = $client;
        $this->table = $table;
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritdoc}
     */
    protected function write(array $record) : void
    {
        $filtered = $this->filterEmptyFields($record['formatted']);
        if ($this->version === 3) {
            $formatted = $this->marshaler->marshalItem($filtered);
        } else {
            $formatted = $this->client->formatAttributes($filtered);
        }
        $this->client->putItem(['TableName' => $this->table, 'Item' => $formatted]);
    }
    protected function filterEmptyFields(array $record) : array
    {
        return \array_filter($record, function ($value) {
            return !empty($value) || \false === $value || 0 === $value;
        });
    }
    /**
     * {@inheritdoc}
     */
    protected function getDefaultFormatter() : \WPCal\GoogleAPI\Monolog\Formatter\FormatterInterface
    {
        return new \WPCal\GoogleAPI\Monolog\Formatter\ScalarFormatter(self::DATE_FORMAT);
    }
}
