<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Dotdigital;

use Dotdigital_WordPress_Vendor\Dotdigital\Resources\AbstractResource;
use Dotdigital_WordPress_Vendor\Dotdigital\Resources\ResourceFactory;
use Dotdigital_WordPress_Vendor\Http\Client\Common\Plugin\AuthenticationPlugin;
use Dotdigital_WordPress_Vendor\Http\Client\Common\Plugin\BaseUriPlugin;
use Dotdigital_WordPress_Vendor\Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Dotdigital_WordPress_Vendor\Http\Discovery\Psr17FactoryDiscovery;
use Dotdigital_WordPress_Vendor\Http\Message\Authentication\BasicAuth;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\UriFactoryInterface;
abstract class AbstractClient
{
    /**
     * @var string
     */
    public const RESOURCE_BASE = '';
    /**
     * @var ClientBuilder
     */
    protected $clientBuilder;
    /**
     * @var UriFactoryInterface
     */
    protected $uriFactory;
    /**
     * @var string
     */
    protected static $apiEndpoint;
    /**
     * @var string
     */
    protected static $apiUser;
    /**
     * @var string
     */
    protected static $apiPassword;
    public function __construct(ClientBuilder $clientBuilder = null, UriFactoryInterface $uriFactory = null)
    {
        $this->clientBuilder = $clientBuilder ?: new ClientBuilder();
        $this->uriFactory = $uriFactory ?: Psr17FactoryDiscovery::findUriFactory();
        // Set default headers
        $this->clientBuilder->addPlugin(new HeaderDefaultsPlugin(['User-Agent' => 'dotdigital-php', 'Content-Type' => 'application/json', 'Accept' => 'application/json']));
    }
    /**
     * @param string $email
     *
     * @return void
     */
    public static function setApiUser(string $email)
    {
        self::$apiUser = $email;
    }
    /**
     * @param string $password
     *
     * @return void
     */
    public static function setApiPassword(string $password)
    {
        self::$apiPassword = $password;
    }
    /**
     * @param string $endpoint
     *
     * @return void
     */
    public static function setApiEndpoint(string $endpoint)
    {
        self::$apiEndpoint = $endpoint;
    }
    /**
     * @return \Http\Client\Common\HttpMethodsClientInterface
     */
    public function getHttpClient()
    {
        // Set base uri
        $this->clientBuilder->addPlugin(new BaseUriPlugin($this->uriFactory->createUri($this->getApiEndpoint())));
        $this->clientBuilder->addPlugin(new AuthenticationPlugin(new BasicAuth(self::$apiUser, self::$apiPassword)));
        return $this->clientBuilder->getHttpClient();
    }
    /**
     * @return string
     */
    public function getApiEndpoint()
    {
        return self::$apiEndpoint;
    }
    public abstract function mediateResponse($response) : string;
    /**
     * @param string $name
     *
     * @return AbstractResource
     */
    public function __get($name)
    {
        $reflectionClass = new \ReflectionClass(\get_class($this));
        $nameSpace = $reflectionClass->getNamespaceName();
        $resource = '\\' . $nameSpace . '\\' . 'Resources' . '\\' . \ucfirst($name);
        $resourceFactory = new ResourceFactory($this);
        return $resourceFactory->__get($resource);
    }
}
