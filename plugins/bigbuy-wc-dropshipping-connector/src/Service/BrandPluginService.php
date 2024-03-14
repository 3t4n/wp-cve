<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCBrand;
use WcMipConnector\Enum\WooCommerceErrorCodes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Factory\BrandMapFactory;
use WcMipConnector\Factory\BrandPluginFactory;
use WcMipConnector\Manager\BrandPluginManager;
use WcMipConnector\Manager\BrandPluginMapManager;
use WcMipConnector\Manager\SystemManager;

class BrandPluginService
{
    /**@var BrandPluginFactory */
    protected $brandFactory;
    /**@var BrandMapFactory */
    protected $brandMapFactory;
    /**@var BrandPluginMapManager */
    protected $brandPluginMapManager;
    /**@var BrandPluginManager */
    protected $brandPluginManager;
    /**@var SystemManager */
    protected $systemManager;
    /**@var ImportProcessBrandPluginService */
    protected $importProcessService;
    /**@var LoggerService */
    protected $logger;
    /** @var SystemService */
    protected $systemService;

    public function __construct()
    {
        $this->brandFactory = new BrandPluginFactory();
        $this->brandMapFactory = new BrandMapFactory();
        $this->brandPluginMapManager = new BrandPluginMapManager();
        $this->brandPluginManager = new BrandPluginManager();
        $this->systemManager = new SystemManager();
        $this->importProcessService = new ImportProcessBrandPluginService();
        $this->logger = new LoggerService;
        $this->systemService = new SystemService();
    }

    /**
     * @param array $brands
     * @param int $fileId
     * @return array
     * @throws \Exception
     */
    public function processByBatch(array $brands, int $fileId): array
    {
        if (empty($brands)) {
            return [];
        }

        $brandIdsToUpdateIndexedByBrandMapId = [];
        $shopMappedBrandsIndexedByBrandId = [];
        $processedArrays = [];
        $arrayResult = [];
        $brandsShopIds = [];

        $batches = \array_chunk($brands, $this->systemService->getBatchValue(), true);

        foreach ($batches as $brandsToProcess) {
            $brandFactorySave = [];
            $brandFactoryToUpdate = [];
            $brandsToSaveIndexedByBrandMapId = [];
            $brandsToUpdateIndexedByBrandMapId = [];
            /** @var WCBrand[] $brandsIndexedByBrandMapId */
            $brandsIndexedByBrandMapId = [];
            $brandMapIdsIndexedByBrandMapId = $this->brandPluginMapManager->findByBrandMapIdsIndexedByBrandMapId(array_keys($brandsToProcess));

            foreach ($brandsToProcess as $brandData) {
                $brandMapId = $brandData['BrandID'];
                $isBrandMapped = \array_key_exists($brandMapId, $brandMapIdsIndexedByBrandMapId);
                $brandFactory = $this->brandFactory->create($brandData, $isBrandMapped);
                $brandsIndexedByBrandMapId[$brandData['BrandID']] = $brandFactory;
            }

            $brandVersionsIndexedByBrandMapId = $this->brandPluginMapManager->findVersionsIndexedByBrandMapId(array_keys($brandMapIdsIndexedByBrandMapId));
            $brandsProcessed = $this->importProcessService->getProcessedBrands(array_keys($brandMapIdsIndexedByBrandMapId), $fileId);
            $brandShopIdsIndexedBySlug = $this->brandPluginManager->findBrandShopIdIndexedBySlug(array_values($this->getBrandsSlug($brandsToProcess)));

            if (!empty($brandMapIdsIndexedByBrandMapId)) {
                $brandsShopIds = $this->brandPluginManager->findByBrandShopIdsIndexedByBrandId(array_values($brandMapIdsIndexedByBrandMapId));
            }

            if ($brandsShopIds) {
                $shopMappedBrandsIndexedByBrandId = \array_intersect($brandMapIdsIndexedByBrandMapId, $brandsShopIds);
            }

            /** @var WCBrand $brandMapData */
            foreach ($brandsIndexedByBrandMapId as $brandMapId => $brandMapData) {
                if (\array_key_exists($brandMapId, $brandsProcessed)
                || (\array_key_exists($brandMapId, $shopMappedBrandsIndexedByBrandId) && (int)$brandsToProcess[$brandMapId]['Version'] <= (int)$brandVersionsIndexedByBrandMapId[$brandMapId])
                ) {
                    $processedArrays[] = $this->setProcessBrands($brandMapId,  $shopMappedBrandsIndexedByBrandId, $fileId);

                    continue;
                }

                if (\array_key_exists($brandMapId, $brandMapIdsIndexedByBrandMapId)) {
                    if (\array_key_exists($brandMapId, $shopMappedBrandsIndexedByBrandId)) {
                        $brandMapData->id = $shopMappedBrandsIndexedByBrandId[$brandMapId];
                        $brandIdsToUpdateIndexedByBrandMapId[$brandMapId] = true;
                        $brandFactoryToUpdate[] = $brandMapData;
                        $brandsToUpdateIndexedByBrandMapId[$brandMapId] = $brandMapData;

                        continue;
                    }

                    if (\array_key_exists($brandMapData->slug, $brandShopIdsIndexedBySlug)) {
                        $brandMapData->id = $brandShopIdsIndexedBySlug[$brandMapData->slug];
                        $brandIdsToUpdateIndexedByBrandMapId[$brandMapId] = true;
                        $brandFactoryToUpdate[] = $brandMapData;
                        $brandsToUpdateIndexedByBrandMapId[$brandMapId] = $brandMapData;

                        continue;
                    }

                    $brandsToSaveIndexedByBrandMapId[$brandMapId] = $brandMapData;
                    $brandIdsToUpdateIndexedByBrandMapId[$brandMapId] = true;
                    $brandFactorySave[] = $brandMapData;

                    continue;
                }

                if (\array_key_exists($brandMapData->slug, $brandShopIdsIndexedBySlug)) {
                    $brandMapData->id = $brandShopIdsIndexedBySlug[$brandMapData->slug];
                    $brandIdsToUpdateIndexedByBrandMapId[$brandMapId] = false;
                    $brandFactoryToUpdate[] = $brandMapData;
                    $brandsToUpdateIndexedByBrandMapId[$brandMapId] = $brandMapData;

                    continue;
                }

                if (!\array_key_exists($brandMapId, $brandMapIdsIndexedByBrandMapId)) {
                    $brandIdsToUpdateIndexedByBrandMapId[$brandMapId] = false;
                    $brandFactorySave[] = $brandMapData;
                    $brandsToSaveIndexedByBrandMapId[$brandMapId] = $brandMapData;
                }
            }

            if (!$brandFactorySave && !$brandFactoryToUpdate) {
                continue;
            }

            try{
                $brandsBatchResponse = $this->brandPluginManager->save($brandFactorySave, $brandFactoryToUpdate);
            } catch (WooCommerceApiExceptionInterface $e) {
                $this->logger->error('Plugin Brands Batch Error - Exception Message: '.$e->getMessage());
                continue;
            }

            if (\array_key_exists('create', $brandsBatchResponse) && $brandsBatchResponse['create']) {
                $processedArrays[] =  $this->mapAndImport($brandsBatchResponse['create'], $brandsToSaveIndexedByBrandMapId, $brandIdsToUpdateIndexedByBrandMapId, $brandsToProcess, $fileId);
            }

            if (\array_key_exists('update', $brandsBatchResponse) && $brandsBatchResponse['update']) {
                $processedArrays[] = $this->mapAndImport($brandsBatchResponse['update'], $brandsToUpdateIndexedByBrandMapId, $brandIdsToUpdateIndexedByBrandMapId, $brandsToProcess, $fileId);
            }
        }

        foreach ($processedArrays as $processedArray) {
            foreach ($processedArray as $processedArrayParent => $processed) {
                $arrayResult[$processedArrayParent] = $processed;
            }
        }

        return $arrayResult;
    }

    /**
     * @param string $brandMapId
     * @param array $shopMappedBrandsIndexedByBrandId
     * @param int $fileId
     * @return array
     * @throws \Exception
     */
    public function setProcessBrands(string $brandMapId, array $shopMappedBrandsIndexedByBrandId, int $fileId): array
    {
        $process = [];

        $this->importProcessService->setSuccess($brandMapId, $fileId);

        if (!\array_key_exists($brandMapId, $shopMappedBrandsIndexedByBrandId)) {
            return [];
        }

        $process[$brandMapId] = [$shopMappedBrandsIndexedByBrandId[$brandMapId]];

        return $process;
    }

    /**
     * @param array $brandsBatchResponse
     * @param array $brandsIndexedByBrandMapId
     * @param array $brandIdsToUpdateIndexedByBrandMapId
     * @param array $brandsIndexedById
     * @param int $fileId
     * @return array
     * @throws \Exception
     */
    private function mapAndImport(array $brandsBatchResponse, array $brandsIndexedByBrandMapId, array $brandIdsToUpdateIndexedByBrandMapId, array $brandsIndexedById, int $fileId): array
    {
        $brandModel = [];
        $brandMapIndex = 0;
        $brandsToDelete = null;

        foreach ($brandsIndexedByBrandMapId as $brandId => $brandMapData) {
            $brand = $brandsBatchResponse[$brandMapIndex];
            $brandMapIndex++;

            if (!$brand) {
                continue;
            }

            if (\array_key_exists('error', $brand) && $brand['error']['code'] === WooCommerceErrorCodes::TERM_EXISTS) {
                $brand['id'] = $this->brandPluginManager->findBrandShopIdBySlug($brandMapData->slug);
                if ($brand['id'] > 0) {
                    $this->logger->info('Remapped Plugin Brand Id: '.$brand['id']);
                    unset($brand['error']);
                }
            }

            if (\array_key_exists('error', $brand) && $brand['error']) {
                $this->importProcessService->setFailure((int)$brandId, $fileId);
                $errorCode = $brand['error']['code'];
                $message = 'Plugin BrandID: '.$brandId.' in the FileID: '.$fileId.' has been not processed right.';

                if ($errorCode === WooCommerceErrorCodes::INVALID_REMOTE_URL) {
                    $message .= ' '.$brandMapData->image['src'];
                }

                if (isset($brand['error']['message']) && $brand['error']['message']) {
                    $internalErrorCode = ResponseService::getInternalErrorCodeFromResponseError($brand['error']['message']);
                    $message .= ' - InternalCode: '.$internalErrorCode.' - ErrorMessage: '.$brand['error']['message'];
                }

                $this->logger->getInstance()->error($message);

                continue;
            }

            $brandToMap = $this->brandMapFactory->create($brandsIndexedById[$brandId], $brand['id']);

            if (\array_key_exists($brandId, $brandIdsToUpdateIndexedByBrandMapId) && $brandIdsToUpdateIndexedByBrandMapId[$brandId]) {
                $this->brandPluginMapManager->update($brand['id'], $brandToMap);
            } else {
                $this->brandPluginMapManager->save($brandToMap);
            }

            $this->importProcessService->setSuccess((int)$brandId, $fileId);

            $brandModel[$brandId] = [$brandToMap->shopBrandId];
        }

        if ($brandsToDelete) {
            try {
                $this->brandPluginManager->deleteCollection(\array_values($brandsToDelete));
            } catch (WooCommerceApiExceptionInterface $e) {
                $this->logger->error('Plugin Brand Delete of File Id:'.$fileId.' - Exception Message: '.$e->getMessage());
            }
        }

        return $brandModel;
    }

    public function deleteEmptyBrands(): void
    {
        $emptyBrandsIndexedByBrandShopId = $this->brandPluginManager->findEmptyBrands();

        if (!$emptyBrandsIndexedByBrandShopId) {
            return;
        }

        $emptyBrandsExistInShop = $this->brandPluginManager->findByBrandShopIdsIndexedByBrandId($emptyBrandsIndexedByBrandShopId);

        $batches = array_chunk($emptyBrandsIndexedByBrandShopId, $this->systemService->getBatchValue(), true);

        foreach ($batches as $batchToProcess) {
            $brandShopIdsToDelete = [];
            $brandShopIdsToDeleteMap = [];

            foreach ($batchToProcess as $brandShopId) {
                if (\array_key_exists($brandShopId, $emptyBrandsExistInShop)) {
                    $brandShopIdsToDelete[$brandShopId] = $brandShopId;
                }

                $brandShopIdsToDeleteMap[$brandShopId] = $brandShopId;
            }

            $this->deleteBrands($brandShopIdsToDelete, $brandShopIdsToDeleteMap);
        }
    }

    /**
     * @param array $brandsToDelete
     * @param array $brandsMapToDelete
     */
    public function deleteBrands(array $brandsToDelete, array $brandsMapToDelete): void
    {
        try {
            $this->brandPluginManager->deleteCollection($brandsToDelete);
            $this->brandPluginMapManager->deleteByBrandShopIds($brandsMapToDelete);
        } catch (WooCommerceApiExceptionInterface $exception) {
            $this->logger->error('Empty Plugin Brands Delete - Exception Message: '.$exception->getMessage());
        }
    }

    /**
     * @param array $brands
     * @return array
     */
    public function getBrandsSlug(array $brands): array
    {
        $brandsSlug = [];

        foreach ($brands as $brand) {
            $brandsSlug[] = BrandPluginFactory::BRAND_PLUGIN_PREFIX . $brand['BrandID'];
        }

        return $brandsSlug;
    }
}