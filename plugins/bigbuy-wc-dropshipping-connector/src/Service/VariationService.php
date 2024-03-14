<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCVariation;
use WcMipConnector\Enum\WooCommerceErrorCodes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Factory\VariationFactory;
use WcMipConnector\Factory\VariationMapFactory;
use WcMipConnector\Manager\ImportProcessVariationManager;
use WcMipConnector\Manager\ProductManager;
use WcMipConnector\Manager\VariationManager;
use WcMipConnector\Manager\VariationMapManager;
use WcMipConnector\Repository\ReferenceDataRepository;

class VariationService
{
    /** @var VariationManager */
    protected $variationManager;
    /** @var VariationMapManager */
    protected $variationMapManager;
    /** @var VariationFactory */
    protected $variationFactory;
    /** @var VariationMapFactory */
    protected $variationMapFactory;
    /** @var ImportProcessVariationService */
    protected $importProcessService;
    /** @var ImportProcessVariationManager */
    protected $importProcessManager;
    /** @var LoggerService */
    protected $logger;
    /** @var SystemService */
    protected $systemService;
    /** @var ProductManager */
    protected $productManager;
    /** @var ReferenceDataRepository */
    private $referenceDataRepository;

    public function __construct()
    {
        $this->variationManager = new VariationManager();
        $this->variationMapManager = new VariationMapManager();
        $this->variationFactory = new VariationFactory();
        $this->variationMapFactory = new VariationMapFactory();
        $this->importProcessService = new ImportProcessVariationService();
        $this->importProcessManager = new ImportProcessVariationManager();
        $this->logger = new LoggerService();
        $this->systemService = new SystemService();
        $this->productManager = new ProductManager();
        $this->referenceDataRepository = new ReferenceDataRepository();
    }

    /**
     * @param WCVariation[]|null $variationsIndexedByVariationMapId
     * @param int|null $variationParentId
     * @param array|null $variation
     * @param int|null $fileId
     * @param int|null $variationProductParentId
     * @return array
     */
    public function processVariationByBatch(array $variationsIndexedByVariationMapId = null, int $variationParentId = null, array $variation = null, int $fileId = null, int $variationProductParentId = null): array
    {
        $processedArray = [[]];

        if (!$variationParentId) {
            foreach ($variationsIndexedByVariationMapId as $variationId => $variationData) {
                $this->importProcessService->setFailure($variationId, $fileId);
                $this->logger->getInstance()->error('VariationID: '.$variationId.' of FileID: '.$fileId.' does not have parent');
            }

            return [];
        }

        $batches = array_chunk($variationsIndexedByVariationMapId, $this->systemService->getBatchValue(), true);

        /** @var WCVariation[] $batchToProcess */
        foreach ($batches as $batchToProcess) {
            /** @var WCVariation[] $variationFactorySave */
            $variationFactorySave = [];
            /** @var WCVariation[] $variationFactoryToUpdate */
            $variationFactoryToUpdate = [];
            $variationIdsToUpdateIndexedByVariationMapId = [];
            $variationToSaveIndexedByVariationMapId = [];
            $variationToUpdateIndexedByVariationMapId = [];

            $variationMapsIndexedByVariationMapId = $this->variationMapManager->findByVariationMapIdsIndexedByVariationMapId(array_keys($batchToProcess));
            $productsShopIdIndexedBySku = $this->productManager->findProductShopIdIndexedBySku(array_keys(array_column($variation, 'VariationID', 'SKU')));
            $processedVariations = $this->importProcessManager->getProcessedVariations(array_keys($variationMapsIndexedByVariationMapId), $fileId);

            foreach ($batchToProcess as $variationMapId => $variationMapData) {
                if (\array_key_exists($variationMapId, $processedVariations)) {
                    $this->logger->getInstance()->info('VariationID: '.$variationMapId.' in the FileID: '.$fileId.' has been already processed');
                    continue;
                }

                $overridePrice = \array_key_exists($variationMapId, $variation) ? $variation[$variationMapId]['OverridePrice'] : true;

                if (!array_key_exists($variationMapId, $variationMapsIndexedByVariationMapId)) {
                    if (\array_key_exists($variationMapData->sku, $productsShopIdIndexedBySku)) {
                        $variationMapData->id = $productsShopIdIndexedBySku[$variationMapData->sku];
                        $variationIdsToUpdateIndexedByVariationMapId[$variationMapId] = false;

                        if (!$overridePrice) {
                            unset($variationMapData->price, $variationMapData->regular_price, $variationMapData->sale_price);
                        }

                        $variationFactoryToUpdate[] = $variationMapData;
                        $variationToUpdateIndexedByVariationMapId[$variationMapId] = $variationMapData;
                        continue;
                    }

                    $variationIdsToUpdateIndexedByVariationMapId[$variationMapId] = false;
                    $variationFactorySave[] = $variationMapData;
                    $variationToSaveIndexedByVariationMapId[$variationMapId] = $variationMapData;
                    continue;
                }

                if (\array_key_exists($variationMapId, $variationMapsIndexedByVariationMapId)) {
                    if (\array_key_exists($variationMapData->sku, $productsShopIdIndexedBySku)) {
                        $variationMapData->id = $productsShopIdIndexedBySku[$variationMapData->sku];
                        $variationIdsToUpdateIndexedByVariationMapId[$variationMapId] = true;

                        if (!$overridePrice) {
                            unset($variationMapData->price, $variationMapData->regular_price, $variationMapData->sale_price);
                        }

                        $variationFactoryToUpdate[] = $variationMapData;
                        $variationToUpdateIndexedByVariationMapId[$variationMapId] = $variationMapData;
                        continue;
                    }

                    $variationToSaveIndexedByVariationMapId[$variationMapId] = $variationMapData;
                    $variationIdsToUpdateIndexedByVariationMapId[$variationMapId] = true;
                    $variationFactorySave[] = $variationMapData;
                }
            }

            if (!$variationFactorySave && !$variationFactoryToUpdate) {
                continue;
            }

            try{
                $this->logger->debug('Started the API PRODUCT VARIATION UPDATE process');
                $variationBatchResponse = $this->variationManager->updateCollection($variationFactoryToUpdate, $variationParentId);

                if (\array_key_exists('update', $variationBatchResponse) && $variationBatchResponse['update']) {
                    $processedArray[] = $this->mapAndImport(
                        $variationBatchResponse['update'],
                        $variationToUpdateIndexedByVariationMapId,
                        $variationIdsToUpdateIndexedByVariationMapId,
                        $variation,
                        $fileId,
                        $variationProductParentId
                    );
                }
            } catch (WooCommerceApiExceptionInterface $e) {
                $this->logger->error(__METHOD__.' Batch Variation Update Error - Exception Message: '.$e->getMessage());
            }

            $this->logger->debug('Finished the API PRODUCT VARIATION UPDATE process');

            try{
                $this->logger->debug('Started the API PRODUCT VARIATION CREATE process');
                $variationBatchResponse = $this->variationManager->createCollection($variationFactorySave, $variationParentId);

                if (\array_key_exists('create', $variationBatchResponse) && $variationBatchResponse['create']) {
                    $processedArray[] = $this->mapAndImport(
                        $variationBatchResponse['create'],
                        $variationToSaveIndexedByVariationMapId,
                        $variationIdsToUpdateIndexedByVariationMapId,
                        $variation,
                        $fileId,
                        $variationProductParentId
                    );
                }
            } catch (WooCommerceApiExceptionInterface $e) {
                $this->logger->error(__METHOD__.' Batch Variation Create - Exception Message: '.$e->getMessage());
            }

            $this->logger->debug('Finished the API PRODUCT VARIATION CREATE process');
        }

        $processed = array_merge(...$processedArray);

        return \array_column($processed, 'shopId', 'id');
    }

    /**
     * @param array $variationBatchResponse
     * @param WCVariation[] $variationIndexedByVariationMapId
     * @param array $variationIdsToUpdateIndexedByVariationMapId
     * @param array $variations
     * @param int $fileId
     * @param int $variationProductParentId
     * @return array
     */
    private function mapAndImport(array $variationBatchResponse, array $variationIndexedByVariationMapId, array $variationIdsToUpdateIndexedByVariationMapId, array $variations, int $fileId, int $variationProductParentId): array
    {
        $variationIds = [];
        $variationMapIndex = 0;

        foreach ($variationIndexedByVariationMapId as $variationId => $variationMapData) {
            $variation = $variationBatchResponse[$variationMapIndex];
            $variationMapIndex++;

            if (!$variation) {
                continue;
            }

            if (\array_key_exists('error', $variation) && $variation['error'] && $variation['error']['code']) {
                $setProductError = '';

                if ($this->isInvalidVariationIdErrorCode($variation['error']['code'])) {
                    $this->productManager->deleteBySku($variationMapData->sku);
                    $setProductError = ' - Invalid SKU deleted, this variation will be processed in another file';
                }

                $this->importProcessService->setFailure($variationId, $fileId);
                $this->logger->getInstance()->error('VariationID: '.$variationId.' of FileID: '.$fileId.' has been not processed. Batch response: '
                    .$variation['error']['code']. $setProductError .' - Variation SKU: '.$variationMapData->sku, $variation['error']);
            }

            $variation = $this->deleteDuplicatedVariations($variationMapData->sku);
            if (!$variation) {
                continue;
            }

            $variationToMap = $this->variationMapFactory->create($variations[$variationId], $variation['id']);

            if (\array_key_exists($variationId, $variationIdsToUpdateIndexedByVariationMapId) && $variationIdsToUpdateIndexedByVariationMapId[$variationId]) {
                $this->variationMapManager->update($variation['id'], $variationToMap);
            }else{
                $this->variationMapManager->save($variationToMap);
            }

            $this->referenceDataRepository->upsert($variationProductParentId, $variationMapData->sku, $variations[$variationId]['EAN'], $variationToMap->variationId);
            $this->importProcessService->setSuccess($variationId, $fileId);

            $variationIds[] = ['shopId' => $variation['id'], 'id' => $variationId];
        }

        return $variationIds;
    }

    /**
     * @param string $errorCode
     * @return bool
     */
    public function isInvalidVariationIdErrorCode(string $errorCode): bool
    {
        return $errorCode === WooCommerceErrorCodes::INVALID_VARIATION_ID;
    }

    /**
     * @param string $variationSku
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function deleteDuplicatedVariations(string $variationSku): array
    {
        $i = 0;
        $variationToMap = [];
        $variationsToDelete = [];

        $variationIndexedBySku = $this->productManager->findProductShopIdWithSameSku($variationSku);
        if (!$variationIndexedBySku) {
            return [];
        }

        foreach ($variationIndexedBySku as $variation) {
            if ($i === 0) {
                $i++;
                $variationToMap = $variation;
                continue;
            }

            $variationsToDelete[] = $variation['product_id'];
        }

        if (!$variationsToDelete) {
            return ['id' => $variationToMap['product_id']];
        }

        $this->productManager->deleteCollection($variationsToDelete);
        $this->logger->getInstance()->error('Variations with duplicated SKU '.$variationSku.' with IDS: '
            .\json_encode(\array_values($variationsToDelete), true).' have been deleted');

        return ['id' => $variationToMap['product_id']];
    }

    /**
     * @return array
     */
    public function getVariationsShopIndexedByVariationShopId(): array
    {
        return $this->variationMapManager->getVariationsShopIndexedByVariationShopId();
    }

    /**
     * @param array $variationShopIdsIndexedByProductId
     * @return array
     */
    public function getVariationsUrlIndexedByProductShopId(array $variationShopIdsIndexedByProductId): array
    {
        return $this->variationManager->getVariationsUrlIndexedByProductShopId($variationShopIdsIndexedByProductId);
    }
}