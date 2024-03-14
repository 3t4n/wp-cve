<?php

namespace WcMipConnector\Service;

use WcMipConnector\Enum\WooCommerceErrorCodes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Factory\ProductMapFactory;
use WcMipConnector\Factory\UnPublishFactory;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Manager\OrderLogManager;
use WcMipConnector\Manager\ProductManager;
use WcMipConnector\Manager\ProductMapManager;

defined('ABSPATH') || exit;

class UnPublishProductService
{
    /** @var ProductManager */
    protected $productManager;
    /** @var ProductMapManager */
    protected $productMapManager;
    /** @var UnPublishFactory */
    protected $disableFactory;
    /** @var ImportProcessProductService */
    protected $importProcessService;
    /** @var LoggerService */
    protected $logger;
    /** @var ProductMapFactory */
    protected $productMapFactory;
    /** @var SystemService */
    protected $systemService;
    /** @var ProductService */
    protected $productService;

    public function __construct()
    {
        $this->productManager = new ProductManager();
        $this->productMapManager = new ProductMapManager();
        $this->disableFactory = new UnPublishFactory;
        $this->importProcessService = new ImportProcessProductService();
        $this->logger = new LoggerService;
        $this->productMapFactory = new ProductMapFactory;
        $this->systemService = new SystemService();
        $this->productService = new ProductService();
    }

    /**
     * @param array $productsDisabled
     * @param int   $fileId
     *
     * @throws \Exception
     */
    public function disableByBatch(array $productsDisabled, int $fileId): void
    {
        if (empty($productsDisabled)) {
            return;
        }

        $productShopIds = [];
        $productShopIdsIndexedByProductMapId = [];
        $batches = \array_chunk($productsDisabled, $this->systemService->getBatchValue(), true);

        foreach ($batches as $batchToProcess) {
            $productFactoryToUpdate = [];
            $productToUpdate = [];
            $productToInsert = [];

            $productMapIdsIndexedByProductMapId = $this->productMapManager->findByProductMapIdsIndexedByProductMapId(\array_keys($batchToProcess));
            $productsNotMappedIndexedBySku = $this->productManager->findProductShopIdIndexedBySku(\array_keys(\array_column($batchToProcess, 'ProductID', 'SKU')));
            $productsAlreadyDisabled = $this->productManager->findDisabledByProductShopIdIndexedByProductShopId(\array_values($productMapIdsIndexedByProductMapId));

            if (!empty($productsAlreadyDisabled)) {
                if (ConfigurationOptionManager::getProductOption()) {
                    $this->productService->deleteMappedProductsDisableByDays(\array_values($productsAlreadyDisabled));
                }

                foreach ($productMapIdsIndexedByProductMapId as $productParentIdMapped => $productIdMapped) {
                    if (\array_key_exists($productIdMapped, $productsAlreadyDisabled)) {
                        unset($productMapIdsIndexedByProductMapId[$productParentIdMapped]);
                        $this->importProcessService->setSuccess($productParentIdMapped, $fileId);
                    }
                }
            }

            if (!empty($productMapIdsIndexedByProductMapId)) {
                $productShopIds = $this->productManager->findByProductShopIdsIndexedByProductId(\array_values($productMapIdsIndexedByProductMapId));
            }

            if ($productShopIds) {
                foreach ($productMapIdsIndexedByProductMapId as $productParentIdMapped => $productIdMapped) {
                    if (\array_key_exists($productIdMapped, $productShopIds)) {
                        $productShopIdsIndexedByProductMapId[$productParentIdMapped] = $productIdMapped;
                    }
                }
            }

            $processedProducts = $this->importProcessService->getProcessedProducts(array_keys($productMapIdsIndexedByProductMapId), $fileId);
            $productBatchIndex = 0;

            foreach ($batchToProcess as $productMapId => $productMapData) {
                if (\array_key_exists($productMapId, $processedProducts)) {
                    $this->logger->getInstance()->info('ProductID: '.$productMapId.' in the FileID: '.$fileId.' has been already processed');
                    continue;
                }

                if (
                    \array_key_exists($productMapId, $productShopIdsIndexedByProductMapId)
                    ||
                    (
                        \array_key_exists($productMapId, $productMapIdsIndexedByProductMapId)
                        && \array_key_exists($productMapData['SKU'], $productsNotMappedIndexedBySku)
                        && $productMapIdsIndexedByProductMapId[$productMapId] !== $productsNotMappedIndexedBySku[$productMapData['SKU']]
                    )
                ) {
                    $productShopId = \array_key_exists($productMapId, $productShopIdsIndexedByProductMapId)
                        ? $productShopIdsIndexedByProductMapId[$productMapId]
                        : $productsNotMappedIndexedBySku[$productMapData['SKU']];
                    $disableData = $this->disableFactory->create($productMapData, (int)$productShopId);
                    $productFactoryToUpdate[] = $disableData;
                    $productToUpdate[$productBatchIndex] = $productMapData;
                    $productBatchIndex++;

                    continue;
                }

                if (\array_key_exists($productMapData['SKU'], $productsNotMappedIndexedBySku) && !\array_key_exists($productsNotMappedIndexedBySku[$productMapData['SKU']], $productsAlreadyDisabled)) {
                    $disableData = $this->disableFactory->create($productMapData, $productsNotMappedIndexedBySku[$productMapData['SKU']]);
                    $productFactoryToUpdate[] = $disableData;
                    $productToInsert[$productBatchIndex] = $productMapData;
                    $productBatchIndex++;
                }
            }

            if (!$productFactoryToUpdate) {
                continue;
            }

            try {
                $productBatchResponse = $this->productManager->updateCollection($productFactoryToUpdate);
            } catch (WooCommerceApiExceptionInterface $e) {
                $this->logger->error('Batch Product Disable Error - Exception Message: '.$e->getMessage());
                continue;
            }

            if (\array_key_exists('update', $productBatchResponse) && $productBatchResponse['update']) {
                $this->update($productBatchResponse['update'], $productToUpdate, $productToInsert, $fileId);
            }
        }
    }

    public function purge(): void
    {
        if (!ConfigurationOptionManager::getProductOption()) {
            return;
        }

        $productIdsDisabledToPurge = $this->productManager->findIdsDisabledToPurge();
        $this->productService->deleteByIds($productIdsDisabledToPurge, true);
    }

    /**
     * @param array $productBatchResponses
     * @param array $productToUpdateMap
     * @param array $productToInsertMap
     * @param int $fileId
     * @return void
     * @throws \Exception
     */
    private function update(array $productBatchResponses,  array $productToUpdateMap, array $productToInsertMap, int $fileId): void
    {
        foreach ($productBatchResponses as $productBatchIndex => $productBatchResponse) {
            if (!$productBatchResponse) {
                continue;
            }

            if (\array_key_exists($productBatchIndex, $productToUpdateMap) && \array_key_exists('error', $productBatchResponse)) {
                $this->handleBatchResponseError($productToUpdateMap[$productBatchIndex], $productBatchResponse, $fileId);

                continue;
            }

            if (\array_key_exists($productBatchIndex, $productToInsertMap) && \array_key_exists('error', $productBatchResponse)) {
                $this->handleBatchResponseError($productToInsertMap[$productBatchIndex], $productBatchResponse, $fileId);

                continue;
            }

            if (\array_key_exists($productBatchIndex, $productToUpdateMap)) {
                $productMapId = $productToUpdateMap[$productBatchIndex]['ProductID'];
                $productToMap = $this->productMapFactory->createUnPublish($productMapId, $productBatchResponse['id']);
                $this->productMapManager->update($productBatchResponse['id'], $productToMap);
                $this->importProcessService->setSuccess($productMapId, $fileId);

                continue;
            }

            if (\array_key_exists($productBatchIndex, $productToInsertMap)) {
                $productMapId = $productToInsertMap[$productBatchIndex]['ProductID'];
                $productToMap = $this->productMapFactory->createUnPublish($productMapId, $productBatchResponse['id']);
                $this->productMapManager->save($productToMap);
                $this->importProcessService->setSuccess($productMapId, $fileId);
            }
        }
    }

    /**
     * @param string $errorCode
     * @return bool
     */
    private function isInvalidProductIdErrorCode(string $errorCode): bool
    {
        return $errorCode === WooCommerceErrorCodes::PRODUCT_INVALID_ID || $errorCode === WooCommerceErrorCodes::INVALID_PRODUCT_ID;
    }

    /**
     * @param array $productData
     * @param array $productBatchResponse
     * @param int $fileId
     * @return void
     * @throws \Exception
     */
    private function handleBatchResponseError(array $productData, array $productBatchResponse, int $fileId): void
    {
        if (!\array_key_exists('error', $productBatchResponse)) {
            return;
        }

        $productBatchResponseErrorData = $productBatchResponse['error'];

        if (empty($productBatchResponseErrorData)) {
            return;
        }

        $productSku = $productData['SKU'] ?? '';
        $productMapId = $productData['ProductID'];
        $errorMessage = '';
        $productBatchResponseErrorCode = $productBatchResponseErrorData['code'] ?? '';

        if (!empty($productSku) && $this->isInvalidProductIdErrorCode($productBatchResponseErrorCode)) {
            $this->productManager->deleteBySku($productSku);
            $errorMessage = ' - Invalid SKU deleted, this product will be processed in another file';
        }

        $this->importProcessService->setFailure($productMapId, $fileId);
        $this->logger->getInstance()->error(
            __METHOD__.' Product '.$productMapId.' of fileID '.$fileId.' has not been disabled.',
            [$productBatchResponseErrorData, $errorMessage]
        );
    }
}