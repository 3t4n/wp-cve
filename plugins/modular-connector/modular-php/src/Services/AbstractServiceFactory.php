<?php

namespace Modular\SDK\Services;

use Modular\SDK\ModularClient;

abstract class AbstractServiceFactory
{
    /**
     * @var ModularClient
     */
    private ModularClient $wordpressClient;

    /**
     * @var array<string, AbstractServiceFactory>
     */
    private array $services;

    /**
     * @param ModularClient $client
     */
    public function __construct(ModularClient $client)
    {
        $this->wordpressClient = $client;
        $this->services = [];
    }

    /**
     * @param string $name
     *
     * @return AbstractServiceFactory|null
     */
    public function __get(string $name)
    {
        $serviceClass = $this->getServiceClass($name);

        if ($serviceClass !== null) {
            if (!array_key_exists($name, $this->services)) {
                $this->services[$name] = new $serviceClass($this->wordpressClient);
            }

            return $this->services[$name];
        }

        return null;
    }

    /**
     * @param string $name
     *
     * @return null|string
     */
    abstract protected function getServiceClass(string $name): ?string;
}
