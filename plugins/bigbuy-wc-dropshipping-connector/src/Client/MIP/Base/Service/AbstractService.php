<?php
/**
 * @author    BigBuy
 * @copyright 2010-2020 BigBuy
 * @license   license
 */

namespace WcMipConnector\Client\MIP\Base\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Client\Base\Client;
use WcMipConnector\Client\Base\Exception\ClientErrorException;

abstract class AbstractService
{
    const API_URL = 'https://platform.bigbuy.eu';
    const JSON_FORMAT = 'json';

    /** @var Client */
    protected $client;

    /**
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->client = new Client(
            [
                'base_uri' => self::API_URL,
                'headers' =>
                    [
                        'Authorization' => 'Bearer '.$apiKey,
                        'Content-type' => 'application/'.self::JSON_FORMAT,
                        'Accept' => 'application/'.self::JSON_FORMAT,
                    ],
            ]
        );
    }

    /**
     * @param Client $client
     * @return $this
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @param string $url
     * @return array|mixed
     * @throws ClientErrorException
     */
    protected function get($url)
    {
        return $this->client->get($url);
    }

    /**
     * @param string $url
     * @param array $request
     * @return array
     * @throws ClientErrorException
     */
    protected function post($url, $request)
    {
        return $this->client->post($url, $request);
    }
}
