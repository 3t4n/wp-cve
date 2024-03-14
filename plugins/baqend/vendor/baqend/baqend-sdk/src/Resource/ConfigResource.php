<?php

namespace Baqend\SDK\Resource;

use Baqend\SDK\Exception\ResponseException;
use Baqend\SDK\Model\Config\Config;

/**
 * Class ConfigResource created on 20.01.2018.
 *
 * @author  Florian Bücklers
 * @package Baqend\SDK\Resource
 */
class ConfigResource extends AbstractRestResource
{
    /**
     * Retrieves the contents of the configuration resource
     *
     * @return Config The app runtime configuration.
     * @throws ResponseException
     */
    public function getConfig() {
        $request = $this->sendJson('GET', '/config');
        $response = $this->execute($request);

        switch ($response->getStatusCode()) {
            case 200:
                return $this->receiveJson($response, Config::class);
            default:
                throw new ResponseException($response);
        }
    }

    /**
     * Updates the app runtime configuration
     * @param Config $config The app runtime configuration
     * @return Config The updated app runtime configuration.
     * @throws ResponseException
     */
    public function updateConfig(Config $config) {
        $request = $this->client->createRequest()
            ->asPut()
            ->withPath('/config')
            ->withJsonBody($config)
            ->withIfMatch($config->getRevision()->getVersion())
            ->build();

        $response = $this->execute($request);

        switch ($response->getStatusCode()) {
            case 200:
                return $this->receiveJson($response, Config::class);
            default:
                throw new ResponseException($response);
        }
    }
}
