<?php

namespace WcMipConnector\Service;

use WcMipConnector\Client\Base\Exception\ClientErrorException;
use WcMipConnector\Client\MIP\Customer\Service\StockService as StockMipService;
use WcMipConnector\Client\MIP\Model\Stock;
use WcMipConnector\Entity\WCProduct;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Factory\StockFactory;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Manager\ProductManager;
use WcMipConnector\Manager\ProductMapManager;
use WcMipConnector\Manager\VariationManager;
use WcMipConnector\Manager\VariationMapManager;

defined('ABSPATH') || exit;

class StockService
{
    private const UPDATE_BATCH_VALUE = 1000;
    private const UPDATE_PRODUCT_STOCKS_BATCH_VALUE = 5000;

    /** @var ProductMapManager */
    protected $productMapManager;

    /** @var VariationMapManager */
    protected $variationMapManager;

    /** @var StockFactory */
    protected $stockFactory;

    /** @var ProductManager */
    protected $productManager;

    /** @var SystemService */
    protected $systemService;

    /** @var VariationManager */
    protected $variationManager;

    /** @var LoggerService */
    protected $logger;

    /** @var VariationService */
    protected $variationService;

    public function __construct()
    {
        $this->productMapManager = new ProductMapManager();
        $this->variationMapManager = new VariationMapManager();
        $this->stockFactory = new StockFactory();
        $this->productManager = new ProductManager();
        $this->systemService = new SystemService();
        $this->variationManager = new VariationManager();
        $this->logger = new LoggerService();
        $this->variationService = new VariationService();
    }

    /**
     * @return bool
     */
    public function updateStock(): bool
    {
        $lastStockUpdateDate = ConfigurationOptionManager::getLastStockUpdate();
        $currentTime = date('Y-m-d H:i:s');

        if (!$lastStockUpdateDate) {
            ConfigurationOptionManager::setLastStockUpdate($currentTime);

            return false;
        }

        mt_srand(strtotime($lastStockUpdateDate));
        $hoursRandom = mt_rand(22, 24);
        $minutesRandom = mt_rand(0, 59);
        $secondsRandom = mt_rand(1, 15);

        sleep($secondsRandom);

        $nextCatalogStockUpdate = strtotime(
            ' +'.$hoursRandom.' hours +'. $minutesRandom . ' minutes',
            strtotime($lastStockUpdateDate)
        );

        if ($nextCatalogStockUpdate > strtotime($currentTime)) {
            return true;
        }

        ConfigurationOptionManager::setLastStockUpdate($currentTime);
        $accessToken = ConfigurationOptionManager::getAccessToken();

        try {
            $this->logger->debug($currentTime.' Retrieving product stocks information to update');
            $productStockResponse = StockMipService::getInstance($accessToken)->getStocks();
        } catch(ClientErrorException $exception) {
            $this->logger->critical($exception->getMessage());

            return false;
        }

        if (empty($productStockResponse)) {
            return false;
        }

        $this->logger->debug(' Start product stocks update process');

        foreach (\array_chunk($productStockResponse, self::UPDATE_PRODUCT_STOCKS_BATCH_VALUE) as $chunkedProductStockResponse) {
            $this->updateProductStocksFromStockResponse($chunkedProductStockResponse);
        }

        $this->logger->debug(' Finished product stocks update process');

        return true;
    }

    /**
     * @param Stock[] $productStockResponse
     * @return void
     */
    private function updateProductStocksFromStockResponse(array $productStockResponse): void
    {
        $productIdsIndex = [];
        $variationIdsIndex = [];

        foreach ($productStockResponse as $productStock) {
            if (!$productStock->productId) {
                continue;
            }

            if ($productStock->productVariationId === "0") {
                $productIdsIndex[$productStock->productId] = true;

                continue;
            }

            $variationIdsIndex[$productStock->productVariationId] = true;
        }

        $productIdsIndexedByProductShopIds = $this->productMapManager->findProductIdsIndexedByProductShopId(\array_keys($productIdsIndex));
        $this->updateProductStocks($productIdsIndexedByProductShopIds);


        $variationIdsIndexedByVariationShopId = $this->variationMapManager->findVariationIdsIndexedByVariationShopId(\array_keys($variationIdsIndex));
        $this->updateProductStocks($variationIdsIndexedByVariationShopId);

    }

    /**
     * @param array $productStockToUpdate
     * @throws WooCommerceApiExceptionInterface
     */
    public function processProductsByBatch(array $productStockToUpdate): void
    {
        $batches = array_chunk($productStockToUpdate, $this->systemService->getBatchValue(), true);

        foreach ($batches as $batchToProcess) {
            $this->productManager->updateCollection($batchToProcess);
        }
    }

    /**
     * @param array<int, bool> $productIdsIndex
     * @throws WooCommerceApiExceptionInterface
     */
    public function updateProductStocks(array $productIdsIndex): void
    {
        if (empty($productIdsIndex)) {
            return;
        }

        $productIds = \array_keys($productIdsIndex);

        foreach (\array_chunk($productIds, self::UPDATE_BATCH_VALUE) as $productIdsChunked) {
            try {
                $this->productManager->updateMetaLookUpStockByProductIds($productIdsChunked);
            } catch (\Throwable $exception) {
                $this->logger->critical(__METHOD__.' Update product_meta_look_up product stock error: '.$exception->getMessage());
            }

            try {
                $this->productManager->updatePostMetaStockByProductIds($productIdsChunked);
            } catch (\Throwable $exception) {
                $this->logger->critical(__METHOD__.' Update postmeta product stock error: '.$exception->getMessage());
            }

            try {
                $this->productManager->updatePostMetaStockStatusByProductIds($productIdsChunked);
            } catch (\Throwable $exception) {
                $this->logger->critical(__METHOD__.' Update postmeta product stock status error: '.$exception->getMessage());
            }
        }
    }

    /**
     * @param array $variationStockToUpdate
     * @param int $productParentId
     */
    public function processVariationsByBatch(array $variationStockToUpdate, int $productParentId): void
    {
        $variationsBatchResponse = $this->variationManager->updateCollection($variationStockToUpdate, $productParentId);
        $this->checkVariationBatchErrors($variationStockToUpdate, $variationsBatchResponse);
    }

    /**
     * @param array $variationIndexedByVariationId
     * @param array $variationsBatchResponse
     */
    private function checkVariationBatchErrors(array $variationIndexedByVariationId, array $variationsBatchResponse): void
    {
        if (!$variationsBatchResponse) {
            return;
        }

        $variationMapIndex = 0;

        /** @var WCProduct $variationMap */
        foreach ($variationIndexedByVariationId as $variationId => $variationMap) {
            if (!\array_key_exists($variationMapIndex, $variationsBatchResponse)) {
                break;
            }

            $variation = $variationsBatchResponse[$variationMapIndex];
            $variationMapIndex++;

            if (!$variation) {
                continue;
            }

            if (\array_key_exists('error', $variation) && $variation['error'] && $variation['error']['code']) {
                $setProductError = '';

                if ($this->variationService->isInvalidVariationIdErrorCode($variation['error']['code'])) {
                    $this->productManager->deleteBySku($variationMap->sku);
                    $setProductError = ' - Invalid SKU deleted, this variation will be processed in another file';
                }

                $this->logger->getInstance()->error('VariationShopId: '.$variationId.' has been not processed in stock request. Batch response: '
                    .$variation['error']['code']. $setProductError, $variation['error']);
            }
        }
    }
}