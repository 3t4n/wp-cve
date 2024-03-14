<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

require __DIR__.'/../../vendor/autoload.php';

use Automattic\WooCommerce\Client;
use WcMipConnector\Enum\WooCommerceApiMethodTypes;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Exception\WooCommerceApiConnectionException;
use WcMipConnector\Model\WCBatchRequest;
use WcMipConnector\Repository\WoocommerceApiRepository;

class WoocommerceApiConnectionService implements WoocommerceApiServiceInterface
{
    private const API_VERSION = 'wc/v3';
    private const TIMEOUT = 100000;
    private const ENDPOINT_BATCH = 'batch';

    /** @var Client */
    protected $apiConnection;
    /** @var LoggerService */
    protected $logger;
    /** @var WoocommerceApiRepository  */
    protected $repository;

    public function __construct()
    {
        $loggerService = new LoggerService();
        $this->logger = $loggerService->getInstance();
        $this->repository = new WoocommerceApiRepository();
    }

    /**
     * @return Client
     */
    public function getWoocommerceApiConnection(): Client
    {
        if (!$this->repository->existsApiAccess()) {
            $this->repository->createApiCredentials();
        }

        return new Client(
            ConfigurationOptionManager::getOptionBySiteUrl(),
            ConfigurationOptionManager::getAccessToken(),
            ConfigurationOptionManager::getSecretKey(),
            [
                'wp_api' => true,
                'version' => self::API_VERSION,
                'query_string_auth' => true,
                'timeout' => self::TIMEOUT
            ]
        );
    }

    /**
     * @param string $methodType
     * @param WCBatchRequest $WCBatchRequest
     * @param array $queryParams
     * @return array
     * @throws WooCommerceApiConnectionException
     */
    public function batchItems(string $methodType, WCBatchRequest $WCBatchRequest, array $queryParams = []): array
    {
        $endPoint = WooCommerceApiMethodTypes::getEndpoint($methodType, $queryParams);

        try {
            $this->apiConnection = $this->getWoocommerceApiConnection();
            $request = \json_decode(\json_encode($WCBatchRequest), true);

            return \json_decode(\json_encode($this->apiConnection->post($endPoint.'/'.self::ENDPOINT_BATCH, $request)), true);
        } catch (\Exception $exception) {
            $this->logger->error('Post WooCommerceApiConnection - EndPoint: '.$endPoint.' - Error message: '.$exception->getMessage());
            throw new WooCommerceApiConnectionException('Post WooCommerceApiConnection - EndPoint: '.$endPoint.'- Error message: '.$exception->getMessage());
        }
    }

    /**
     * @param string $methodType
     * @param array $objectRequest
     * @param array $queryParams
     * @return array
     * @throws WooCommerceApiConnectionException
     */
    public function deleteItem(string $methodType, array $objectRequest, array $queryParams = []): array
    {
        $endPoint = WooCommerceApiMethodTypes::getEndpoint($methodType, $queryParams);

        try {
            $this->apiConnection = $this->getWoocommerceApiConnection();

            return \json_decode(\json_encode($this->apiConnection->delete($endPoint, $objectRequest)), true);
        } catch (\Exception $exception) {
            $this->logger->error('Delete WooCommerceApiConnection - EndPoint: '.$endPoint.' - Error message: '.$exception->getMessage());
            throw new WooCommerceApiConnectionException('Delete WooCommerceApiConnection - EndPoint: '.$endPoint.'- Error message: '.$exception->getMessage());
        }
    }

    /**
     * @param string $methodType
     * @param array $objectRequest
     * @param array $queryParams
     * @return array
     * @throws WooCommerceApiConnectionException
     */
    public function updateItem(string $methodType, array $objectRequest, array $queryParams = []): array
    {
        $endPoint = WooCommerceApiMethodTypes::getEndpoint($methodType, $queryParams);

        try {
            $this->apiConnection = $this->getWoocommerceApiConnection();

            return \json_decode(\json_encode($this->apiConnection->put($endPoint, $objectRequest)), true);
        } catch (\Exception $exception) {
            $this->logger->error('Put WooCommerceApiConnection - EndPoint: '.$endPoint.' - Error message: '.$exception->getMessage());
            throw new WooCommerceApiConnectionException('Put WooCommerceApiConnection - EndPoint: '.$endPoint.'- Error message: '.$exception->getMessage());
        }

    }

    /**
     * @param string $methodType
     * @param array $objectRequest
     * @param array $queryParams
     * @return array
     * @throws WooCommerceApiConnectionException
     */
    public function createItem(string $methodType, array $objectRequest, array $queryParams = []): array
    {
        $endPoint = WooCommerceApiMethodTypes::getEndpoint($methodType, $queryParams);

        try {
            $this->apiConnection = $this->getWoocommerceApiConnection();

            return \json_decode(\json_encode($this->apiConnection->post($endPoint, $objectRequest)), true);
        } catch (\Exception $exception) {
            $this->logger->error('Post WooCommerceApiConnection - EndPoint: '.$endPoint.' - Error message: '.$exception->getMessage());
            throw new WooCommerceApiConnectionException('Post WooCommerceApiConnection - EndPoint: '.$endPoint.'- Error message: '.$exception->getMessage());
        }
    }

    /**
     * @param string $methodType
     * @param array $filters
     * @param array $queryParams
     * @return array
     * @throws WooCommerceApiConnectionException
     */
    public function getItems(string $methodType, array $filters = [], array $queryParams = []): array
    {
        $endPoint = WooCommerceApiMethodTypes::getEndpoint($methodType, $queryParams);

        try {
            $this->apiConnection = $this->getWoocommerceApiConnection();

            return \json_decode(\json_encode($this->apiConnection->get($endPoint, $filters)), true);
        } catch (\Exception $exception) {
            $this->logger->error('Get WooCommerceApiConnection - EndPoint: '.$endPoint.' - Parameters: '.\json_encode($filters).' - Error message: '.$exception->getMessage());
            throw new WooCommerceApiConnectionException('Get WooCommerceApiConnection - EndPoint: '.$endPoint.' - Parameters: '.\json_encode($filters).' - Error message: '.$exception->getMessage());
        }
    }

    /**
     * @param string $methodType
     * @param string $id
     * @param array $queryParams
     * @return array
     * @throws WooCommerceApiConnectionException
     */
    public function getItem(string $methodType, string $id, array $queryParams = []): array
    {
        return $this->getItems($methodType, ['id' => $id], $queryParams);
    }
}