<?php

namespace Baqend\SDK;

use Baqend\SDK\Client\ClientInterface;
use Baqend\SDK\Client\GuzzleClient;
use Baqend\SDK\Client\RestClient;
use Baqend\SDK\Client\RestClientInterface;
use Baqend\SDK\Client\WordPressClient;
use Baqend\SDK\Client\ZendFramework1Client;
use Baqend\SDK\Exception\NotConnectedException;
use Baqend\SDK\Resource\AssetResource;
use Baqend\SDK\Resource\CodeResource;
use Baqend\SDK\Resource\ConfigResource;
use Baqend\SDK\Resource\CrudResource;
use Baqend\SDK\Resource\FileResource;
use Baqend\SDK\Resource\ResourceInterface;
use Baqend\SDK\Resource\UserResource;
use Baqend\SDK\Serializer\AclNormalizer;
use Baqend\SDK\Serializer\BucketAclNormalizer;
use Baqend\SDK\Serializer\ConfigNormalizer;
use Baqend\SDK\Serializer\DateTimeNormalizer;
use Baqend\SDK\Serializer\EntityNormalizer;
use Baqend\SDK\Serializer\FileNormalizer;
use Baqend\SDK\Serializer\MediaTypeNormalizer;
use Baqend\SDK\Serializer\PermissionNormalizer;
use Baqend\SDK\Serializer\QueryStringEncoder;
use Baqend\SDK\Service\IOService;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class Baqend created on 25.07.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK
 */
class Baqend
{

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var RestClientInterface|null
     */
    private $restClient;

    /**
     * @var ResourceInterface[]
     */
    private $resources;

    /**
     * @var IOService
     */
    private $ioService;

    /**
     * @var SpeedKit
     */
    private $speedKit;

    /**
     * @var WordPressPlugin
     */
    private $wordPressPlugin;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Baqend constructor.
     *
     * @param string $transport
     * @param array $resources
     * @param IOService|null $ioService
     * @param SerializerInterface|null $serializer
     */
    public function __construct(
        $transport = ClientInterface::GUZZLE_TRANSPORT,
        array $resources = [],
        IOService $ioService = null,
        SerializerInterface $serializer = null
    ) {
        if ($ioService === null) {
            $ioService = new IOService();
        }

        // Initialize serializer
        if ($serializer === null) {
            $serializer = $this->createSerializer();
        }

        // Create the client
        $this->client = $this->createClient($transport);
        $this->restClient = null;
        $this->resources = $resources;
        $this->ioService = $ioService;
        $this->serializer = $serializer;
        $this->speedKit = new SpeedKit($this->client, $this->ioService, $this->serializer);
        $this->wordPressPlugin = new WordPressPlugin($this->client, $this->serializer);
    }

    /**
     * Creates a serializer instance.
     *
     * @return Serializer
     */
    public static function createSerializer() {
        // a full list of extractors is shown further below
        $phpDocExtractor = new PhpDocExtractor();
        $reflectionExtractor = new ReflectionExtractor();

        // array of PropertyListExtractorInterface
        $listExtractors = [$reflectionExtractor];

        // array of PropertyTypeExtractorInterface
        $typeExtractors = [$phpDocExtractor, $reflectionExtractor];

        // array of PropertyDescriptionExtractorInterface
        $descriptionExtractors = [$phpDocExtractor];

        // array of PropertyAccessExtractorInterface
        $accessExtractors = [$reflectionExtractor];

        $propertyInfo = new PropertyInfoExtractor(
            $listExtractors,
            $typeExtractors,
            $descriptionExtractors,
            $accessExtractors
        );

        $configNormalizer = new ConfigNormalizer();
        $jsonSerializableNormalizer = new JsonSerializableNormalizer();
        $aclNormalizer = new AclNormalizer();
        $bucketAclNormalizer = new BucketAclNormalizer();
        $permissionNormalizer = new PermissionNormalizer();
        $dateTimeNormalizer = new DateTimeNormalizer();
        $mediaTypeNormalizer = new MediaTypeNormalizer();
        $fileNormalizer = new FileNormalizer();
        $entityNormalizer = new EntityNormalizer(null, null, null, $propertyInfo);
        $arrayDenormalizer = new ArrayDenormalizer();

        $normalizers = [$configNormalizer, $jsonSerializableNormalizer, $aclNormalizer, $bucketAclNormalizer,
            $permissionNormalizer, $dateTimeNormalizer, $mediaTypeNormalizer, $fileNormalizer, $arrayDenormalizer,
            $entityNormalizer];
        $encoders = [new JsonEncoder(new JsonEncode(['json_encode_options' => JSON_UNESCAPED_SLASHES])), new QueryStringEncoder()];

        return new Serializer($normalizers, $encoders);
    }

    /**
     * Returns the HTTP client used by the SDK.
     *
     * @return ClientInterface
     */
    public function getClient() {
        return $this->client;
    }

    /**
     * Sets the HTTP client used by the SDK.
     *
     * @param ClientInterface $httpClient The HTTP client to use.
     * @return static
     */
    public function setClient(ClientInterface $httpClient) {
        $this->client = $httpClient;
        if ($this->restClient !== null) {
            $this->restClient->setClient($httpClient);
        }

        return $this;
    }

    /**
     * @return SerializerInterface
     */
    public function getSerializer() {
        return $this->serializer;
    }

    /**
     * @param SerializerInterface $serializer
     * @return static
     */
    public function setSerializer(SerializerInterface $serializer) {
        $this->serializer = $serializer;
        if ($this->restClient !== null) {
            $this->restClient->setSerializer($serializer);
        }

        return $this;
    }

    /**
     * @return RestClientInterface|null
     */
    public function getRestClient() {
        return $this->restClient;
    }

    /**
     * @return SpeedKit
     */
    public function getSpeedKit() {
        return $this->speedKit;
    }

    /**
     * @return WordPressPlugin
     */
    public function getWordPressPlugin() {
        return $this->wordPressPlugin;
    }

    /**
     * Returns whether this Baqend is connected.
     *
     * @return boolean True, if is connected to Baqend.
     */
    public function isConnected() {
        return $this->restClient !== null;
    }

    /**
     * Connects Baqend against a given app.
     *
     * @param string $app The app to connect to.
     * @return static
     */
    public function connect($app) {
        if ($this->isConnected()) {
            throw new \LogicException('Baqend SDK is already connected');
        }

        $this->restClient = new RestClient($app, $this->serializer, $this->client);

        return $this;
    }

    /**
     * Returns a new EntityManager for a different app.
     *
     * @param string|null $app
     * @param string|null $token
     * @return Baqend
     */
    public function createEntityManager($app = null, $token = null) {
        if ($app === null && $this->restClient === null) {
            throw new \LogicException('Baqend SDK must be connected to create another entity manager for it');
        }

        $app = $app ?: $this->restClient->getApp();
        $entityManager = new Baqend($this->client->getTransport(), [], $this->ioService, $this->serializer);
        $entityManager->connect($app);
        if ($token !== null) {
            $entityManager->restClient->setAuthorizationToken($token);
        }

        return $entityManager;
    }

    /**
     * Disconnects Baqend.
     *
     * @return static
     */
    public function disconnect() {
        if (!$this->isConnected()) {
            throw new NotConnectedException();
        }

        $this->restClient = null;
        $this->resources = [];

        return $this;
    }

    /**
     * @return AssetResource
     */
    public function asset() {
        return $this->getResource(ResourceInterface::ASSET_RESOURCE);
    }

    /**
     * @return CodeResource
     */
    public function code() {
        return $this->getResource(ResourceInterface::CODE_RESOURCE);
    }

    /**
     * @return ConfigResource
     */
    public function config() {
        return $this->getResource(ResourceInterface::CONFIG_RESOURCE);
    }

    /**
     * @return CrudResource
     */
    public function crud() {
        return $this->getResource(ResourceInterface::CRUD_RESOURCE);
    }

    /**
     * @return FileResource
     */
    public function file() {
        return $this->getResource(ResourceInterface::FILE_RESOURCE);
    }

    /**
     * @return UserResource
     */
    public function user() {
        return $this->getResource(ResourceInterface::USER_RESOURCE);
    }

    /**
     * Finds an object by its ID.
     *
     * @param string $id The ID of an object to find. Looks like "/db/MyClass/uuid".
     * @return array An array containing the object's data.
     * @throws Exception\InvalidAuthorizationException When not having access permissions.
     * @throws Exception\RequestException When request could not be sent.
     */
    public function find($id) {
        $request = $this->restClient->createRequest()
            ->asGet()
            ->withPath($id)
            ->build();
        $response = $this->client->sendSyncRequest($request);

        return $this->serializer->decode($response->getBody()->getContents(), 'json');
    }

    /**
     * Returns a resource by its name.
     *
     * @param string $resourceName The resource's name.
     * @return ResourceInterface|mixed The resource demanded.
     */
    public function getResource($resourceName) {
        if (!$this->isConnected()) {
            throw new NotConnectedException();
        }

        if (!isset($this->resources[$resourceName])) {
            $this->resources[$resourceName] = $this->createResource($resourceName);
        }

        return $this->resources[$resourceName];
    }

    /**
     * @param string $transport The transport type to use.
     * @return ClientInterface
     * @internal param string $app The app to connect to.
     */
    private function createClient($transport) {
        switch ($transport) {
            case ClientInterface::WORD_PRESS_TRANSPORT:
                return new WordPressClient();
            case ClientInterface::GUZZLE_TRANSPORT:
                return new GuzzleClient();
            case ClientInterface::ZEND_FRAMEWORK1_TRANSPORT:
                return new ZendFramework1Client();
            default:
                throw new \InvalidArgumentException("Invalid transport given: $transport");
        }
    }

    /**
     * @param string $resourceName The resource's name.
     * @return ResourceInterface
     */
    private function createResource($resourceName) {
        switch ($resourceName) {
            case ResourceInterface::ASSET_RESOURCE:
                return new AssetResource($this->restClient, $this->serializer);
            case ResourceInterface::CODE_RESOURCE:
                return new CodeResource($this->restClient, $this->serializer);
            case ResourceInterface::CONFIG_RESOURCE:
                return new ConfigResource($this->restClient, $this->serializer);
            case ResourceInterface::CRUD_RESOURCE:
                return new CrudResource($this->restClient, $this->serializer);
            case ResourceInterface::FILE_RESOURCE:
                return new FileResource($this->restClient, $this->serializer, $this->ioService);
            case ResourceInterface::USER_RESOURCE:
                return new UserResource($this->restClient, $this->serializer);
            default:
                throw new \InvalidArgumentException("Invalid resource name given: $resourceName");
        }
    }
}
