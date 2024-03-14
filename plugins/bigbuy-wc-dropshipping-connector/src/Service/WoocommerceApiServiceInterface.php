<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

require __DIR__.'/../../vendor/autoload.php';

use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Model\WCBatchRequest;

interface WoocommerceApiServiceInterface
{
    /**
     * @param string $methodType
     * @param WCBatchRequest $WCBatchRequest
     * @param array $queryParams
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function batchItems(string $methodType, WCBatchRequest $WCBatchRequest, array $queryParams = []): array;

    /**
     * @param string $methodType
     * @param array $objectRequest
     * @param array $queryParams
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function deleteItem(string $methodType, array $objectRequest, array $queryParams = []): array;

    /**
     * @param string $methodType
     * @param array $objectRequest
     * @param array $queryParams
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function updateItem(string $methodType, array $objectRequest, array $queryParams = []): array;

    /**
     * @param string $methodType
     * @param array $objectRequest
     * @param array $queryParams
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function createItem(string $methodType, array $objectRequest, array $queryParams = []): array;

    /**
     * @param string $methodType
     * @param array $filters
     * @param array $queryParams
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function getItems(string $methodType, array $filters = [], array $queryParams = []): array;

    /**
     * @param string $methodType
     * @param string $id
     * @param array $queryParams
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function getItem(string $methodType, string $id, array $queryParams = []): array;

}