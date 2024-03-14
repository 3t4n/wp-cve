<?php

namespace WcMipConnector\Client\Base;

defined('ABSPATH') || exit;

use WcMipConnector\Client\Base\Exception\ClientErrorException;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Service\LoggerService;

class Client
{
    const SUCCESS = 200;
    const TIME_OUT = '60';
    const HTTP_VERSION = '1.1';

    /** @var LoggerService */
    protected $logger;

    private $args;
    private $baseUri;

    /**
     * Client constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        try {
            $this->logger = new LoggerService();
            $this->baseUri = $config['base_uri'];
            $headers = $config['headers'];
            $userAgentInfo = [
                'Wordpress/'.get_bloginfo( 'version' ),
                'MIPConnector/'.ConfigurationOptionManager::getPluginDatabaseVersion(),
                'PHP/'.PHP_VERSION,
                get_site_url()
            ];
            $headers['User-Agent'] = \implode(';', $userAgentInfo);
            $this->args = [
                'timeout' => self::TIME_OUT,
                'httpversion' => self::HTTP_VERSION,
                'headers' => $headers,
            ];
        } catch (\Throwable $throwable) {
            if ($this->logger !== null) {
                $this->logger->error(__METHOD__.' - Error: '.$throwable->getMessage().' - Class: '.\get_class($throwable));
            }
        }
    }

    /**
     * @param string $url
     * @return array
     * @throws ClientErrorException
     */
    public function get($url)
    {
        try {
            $response = wp_remote_get($this->baseUri.$url, $this->args);
            $statusCode = (int)wp_remote_retrieve_response_code($response);

            if ($statusCode !== self::SUCCESS) {
                throw new ClientErrorException($statusCode, json_encode($response));
            }

            return \json_decode(wp_remote_retrieve_body($response), true);
        } catch (ClientErrorException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('GET - Call: '.$url.' - Exception message: '.$exception->getMessage());
        }

        return [];
    }

    /**
     * @param string $url
     * @param array $request
     * @return array
     * @throws ClientErrorException
     */
    public function post($url, $request)
    {
        try {
            $args = $this->args;
            $args['body'] = \json_encode($request);

            $response = \wp_remote_post($this->baseUri.$url, $args);
            $statusCode = (int)\wp_remote_retrieve_response_code($response);
            $body = \wp_remote_retrieve_body($response);

            if ($statusCode !== self::SUCCESS) {
                throw new ClientErrorException($statusCode, $body);
            }

            return \json_decode($body, true);
        } catch (ClientErrorException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('GET - Call: '.$url.' - Exception message: '.$exception->getMessage());
        }

        return [];
    }
}