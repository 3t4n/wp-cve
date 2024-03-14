<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Dotdigital;

use Dotdigital_WordPress_Vendor\Http\Client\Common\HttpMethodsClient;
use Dotdigital_WordPress_Vendor\Http\Client\Common\HttpMethodsClientInterface;
use Dotdigital_WordPress_Vendor\Http\Client\Common\Plugin;
use Dotdigital_WordPress_Vendor\Http\Client\Common\PluginClientFactory;
use Dotdigital_WordPress_Vendor\Http\Discovery\Psr17FactoryDiscovery;
use Dotdigital_WordPress_Vendor\Http\Discovery\Psr18ClientDiscovery;
use Dotdigital_WordPress_Vendor\Psr\Http\Client\ClientInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\RequestFactoryInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\StreamFactoryInterface;
final class ClientBuilder
{
    /**
     * @var \Http\Client\HttpClient|ClientInterface|null
     */
    private $httpClient;
    /**
     * @var RequestFactoryInterface|null
     */
    private $requestFactoryInterface;
    /**
     * @var StreamFactoryInterface|null
     */
    private $streamFactoryInterface;
    /**
     * @var array<mixed>
     */
    private $plugins = [];
    public function __construct(ClientInterface $httpClient = null, RequestFactoryInterface $requestFactoryInterface = null, StreamFactoryInterface $streamFactoryInterface = null)
    {
        $this->httpClient = $httpClient ?: Psr18ClientDiscovery::find();
        $this->requestFactoryInterface = $requestFactoryInterface ?: Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactoryInterface = $streamFactoryInterface ?: Psr17FactoryDiscovery::findStreamFactory();
    }
    /**
     * @param Plugin $plugin
     */
    public function addPlugin(Plugin $plugin) : void
    {
        $this->plugins[] = $plugin;
    }
    public function getHttpClient() : HttpMethodsClientInterface
    {
        $pluginClient = (new PluginClientFactory())->createClient($this->httpClient, $this->plugins);
        return new HttpMethodsClient($pluginClient, $this->requestFactoryInterface, $this->streamFactoryInterface);
    }
}
