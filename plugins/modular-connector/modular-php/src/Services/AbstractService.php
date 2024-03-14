<?php

namespace Modular\SDK\Services;

use Modular\SDK\ModularClient;
use Modular\SDK\ModularClientInterface;
use Modular\SDK\Objects\BaseObject;
use Modular\SDK\Objects\BaseObjectFactory;

abstract class AbstractService extends BaseObjectFactory
{
    /**
     * @var ModularClientInterface
     */
    protected ModularClientInterface $sdk;

    /**
     * @var string
     */
    protected string $basePath = '/api/';

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 15;

    /**
     * @param ModularClient $client
     */
    public function __construct(ModularClient $client)
    {
        $this->sdk = $client;
    }

    /**
     * Get the number of models to return per page.
     *
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * Set the number of models to return per page.
     *
     * @param int $perPage
     * @return AbstractService
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Gets the client used by this service to send requests.
     *
     * @return \Modular\sdk\ModularClientInterface
     */
    public function getClient(): ModularClient
    {
        return $this->sdk;
    }

    /**
     * @param string $method the HTTP method
     * @param string $path the path of the request
     * @param array $data the data of the request
     * @param array $opts the special modifiers of the request
     *
     * @return BaseObject
     * @throws \Exception
     */
    public function request(string $method, string $path, array $data = [], array $opts = []): BaseObject
    {
        return $this->getClient()->request($method, $path, $data, $opts);
    }

    /**
     * @param string $method the HTTP method
     * @param string $path the path of the request
     * @param array $data the data of the request
     * @param array $opts the special modifiers of the request
     *
     * @return mixed
     * @throws \Exception
     */
    public function raw(string $method, string $path, array $data = [], array $opts = [])
    {
        return $this->getClient()->raw($method, $path, $data, $opts);
    }

    /**
     * @param string $basePath
     * @param ...$ids
     *
     * @return string
     * @throws \ErrorException
     */
    protected function buildPath(string $basePath, ...$ids): string
    {
        foreach ($ids as $id) {
            if (null === $id || '' === \trim($id)) {
                $msg = 'The resource ID cannot be null or whitespace.';

                // TODO Custom Exceptions
                throw new \ErrorException($msg);
            }
        }

        $this->basePath = rtrim($this->basePath, '/');
        $path = ltrim($basePath, '/');

        $path = $this->basePath . '/' . $path;

        return \sprintf($path, ...array_map('urlencode', $ids));
    }
}
