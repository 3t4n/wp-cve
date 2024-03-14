<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

require __DIR__.'/../../vendor/autoload.php';

use WcMipConnector\Enum\WooCommerceApiMethodTypes;
use WcMipConnector\Exception\WooCommerceApiAdapterException;
use WcMipConnector\Model\WCBatchRequest;

class WoocommerceApiAdapterService implements WoocommerceApiServiceInterface
{
    private const API_VERSION = 'wc/v3';

    /** @var LoggerService  */
    protected $logger;

    public function __construct()
    {
        $loggerService = new LoggerService();
        $this->logger = $loggerService->getInstance();
    }

    /**
     * @param string $methodType
     * @param array $filters
     * @param array $queryParams
     * @return array
     * @throws WooCommerceApiAdapterException
     */
    public function getItems(string $methodType, array $filters = [], array $queryParams = []): array
    {
        $controller = WooCommerceApiMethodTypes::getControllerClass($methodType, 'get_items');

        $request = new \WP_REST_Request('GET', self::API_VERSION.'/'.$methodType);

        foreach ($filters as $key => $filter) {
            $request[$key] = $filter;
        }
        $request->set_url_params($queryParams);

        return $controller->get_items($request)->data;
    }

    /**
     * @param string $methodType
     * @param string $id
     * @param array $queryParams
     * @return array
     * @throws WooCommerceApiAdapterException
     */
    public function getItem(string $methodType, string $id, array $queryParams = []): array
    {
        $controller = WooCommerceApiMethodTypes::getControllerClass($methodType, 'get_item');

        $request = new \WP_REST_Request('GET', self::API_VERSION.'/'.$methodType);
        $request['id'] = $id;
        $request->set_url_params($queryParams);

        $object = $controller->get_item($request);

        if (isset($object->errors)) {
            throw new WooCommerceApiAdapterException(\json_encode($object->errors));
        }

        return $object->data;
    }

    /**
     * @param string $methodType
     * @param array $objectRequest
     * @param array $queryParams
     * @return array
     * @throws WooCommerceApiAdapterException
     */
    public function deleteItem(string $methodType, array $objectRequest, array $queryParams = []): array
    {
        $controller = WooCommerceApiMethodTypes::getControllerClass($methodType, 'delete_item');

        $request = new \WP_REST_Request('DELETE', self::API_VERSION.'/'.$methodType);

        foreach ($objectRequest as $key => $objectProperty) {
            $request[$key] = $objectProperty;
        }
        $request->set_url_params($queryParams);

        $object = $controller->delete_item($request);

        if (isset($object->errors)) {
            throw new WooCommerceApiAdapterException(\json_encode($object->errors));
        }

        return $object->data;
    }

    /**
     * @param string $methodType
     * @param array $objectRequest
     * @param array $queryParams
     * @return array
     * @throws WooCommerceApiAdapterException
     */
    public function updateItem(string $methodType, array $objectRequest, array $queryParams = []): array
    {
        $controller = WooCommerceApiMethodTypes::getControllerClass($methodType, 'update_item');

        $request = new \WP_REST_Request('POST', self::API_VERSION.'/'.$methodType);

        foreach ($objectRequest as $key => $objectProperty) {
            $request[$key] = $objectProperty;
        }

        $request->set_url_params($queryParams);

        $object = $controller->update_item($request);

        if (isset($object->errors)) {
            throw new WooCommerceApiAdapterException(\json_encode($object->errors));
        }

        return $object->data;
    }

    /**
     * @param string $methodType
     * @param array $objectRequest
     * @param array $queryParams
     * @return array
     * @throws WooCommerceApiAdapterException
     */
    public function createItem(string $methodType, array $objectRequest, array $queryParams = []): array
    {
        $controller = WooCommerceApiMethodTypes::getControllerClass($methodType, 'create_item');

        $request = new \WP_REST_Request('POST', self::API_VERSION.'/'.$methodType);

        foreach ($objectRequest as $key => $objectProperty) {
            $request[$key] = $objectProperty;
        }
        $request->set_url_params($queryParams);

        $object = $controller->create_item($request);

        if (isset($object->errors)) {
            throw new WooCommerceApiAdapterException(\json_encode($object->errors));
        }

        return $object->data;
    }

    /**
     * @param string $methodType
     * @param WCBatchRequest $WCBatchRequest
     * @param array $queryParams
     * @return array
     * @throws WooCommerceApiAdapterException
     */
    public function batchItems(string $methodType, WCBatchRequest $WCBatchRequest, array $queryParams = []): array
    {
        $controller = WooCommerceApiMethodTypes::getControllerClass($methodType, 'batch_items');

        $request = new \WP_REST_Request('POST', self::API_VERSION.'/'.$methodType);

        $batchRequest = \json_decode(\json_encode($WCBatchRequest), true);

        foreach ($batchRequest as $key => $objectProperty) {
            $request[$key] = $objectProperty;
        }

        $request->set_url_params($queryParams);

        global $wp_rest_server;
        $wp_rest_server = new \WP_REST_Server();

        $batchResponse = $controller->batch_items($request);

        if (is_wp_error($batchResponse)) {
            $this->logger->error('Executing batch items - Message: '.\json_encode($batchResponse->get_error_messages()), [$batchResponse->get_error_codes(), $batchResponse->get_all_error_data()]);

            return [];
        }

        return $batchResponse;
    }
}