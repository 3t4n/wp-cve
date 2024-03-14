<?php

namespace Baqend\SDK\Client;

use Baqend\SDK\Exception\RequestException;
use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;

/**
 * Class GuzzleClient created on 25.07.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Client
 */
class GuzzleClient implements ClientInterface
{

    /**
     * @var Client
     */
    private $guzzle;

    /**
     * GuzzleClient constructor.
     *
     * @param Client|null $guzzle
     */
    public function __construct(Client $guzzle = null) {
        if ($guzzle === null) {
            $guzzle = new Client([
                'http_errors' => false,
                'timeout' => (float) self::TIMEOUT,
                'allow_redirects' => [
                    'max' => self::MAX_REDIRECTS,
                ],
            ]);
        }

        $this->guzzle = $guzzle;
    }

    /**
     * Returns the transport this client represents.
     *
     * @return string The transport this client represents.
     */
    public function getTransport() {
        return self::GUZZLE_TRANSPORT;
    }

    /**
     * @inheritdoc
     */
    public function sendSyncRequest(RequestInterface $request) {
        try {
            return $this->guzzle->send($request);
        } catch (\Exception $e) {
            throw new RequestException($request, get_class($e).' thrown', $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function sendAsyncRequest(RequestInterface $request) {
        $this->guzzle->sendAsync($request);
    }
}
