<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCBrand;
use WcMipConnector\Enum\BrandTranslations;
use WcMipConnector\Enum\WooCommerceErrorCodes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Factory\BrandFactory;
use WcMipConnector\Factory\BrandMapFactory;
use WcMipConnector\Manager\BrandManager;
use WcMipConnector\Manager\BrandMapManager;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Manager\SystemManager;

class BrandService
{
    /**@var BrandFactory */
    protected $brandFactory;
    /**@var BrandMapFactory */
    protected $brandMapFactory;
    /**@var BrandMapManager */
    protected $brandMapManager;
    /**@var BrandManager */
    protected $brandManager;
    /**@var SystemManager */
    protected $systemManager;
    /**@var ImportProcessBrandService */
    protected $importProcessService;
    /**@var LoggerService */
    protected $logger;
    /** @var SystemService */
    protected $systemService;

    public function __construct()
    {
        $this->brandFactory = new BrandFactory();
        $this->brandMapFactory = new BrandMapFactory();
        $this->brandMapManager = new BrandMapManager();
        $this->brandManager = new BrandManager();
        $this->systemManager = new SystemManager();
        $this->importProcessService = new ImportProcessBrandService();
        $this->logger = new LoggerService;
        $this->systemService = new SystemService();
    }

    /**
     * @param array $brands
     * @param int $fileId
     * @param string $languageIsoCode
     * @return array
     * @throws \Exception
     */
    public function processByBatch(array $brands, int $fileId, string $languageIsoCode): array
    {
        if (empty($brands)) {
            return [];
        }

        $brandIdsToUpdateIndexedByBrandMapId = [];
        $shopMappedBrandsIndexedByBrandId = [];
        $processedArrays = [];
        $arrayResult = [];
        $brandsShopIds = [];

        try {
            $brandId = $this->createBrandGroupIfNotExists($languageIsoCode);
        } catch (WooCommerceApiExceptionInterface $exception) {
            $this->logger->getInstance()->error('Create Brand Group - Exception message: '.$exception->getMessage());

            foreach ($brands as $brandId => $brandData) {
                $this->importProcessService->setFailure($brandId, $fileId);
                $this->logger->getInstance()->error(
                    'BrandID: '.$brandId.' of FileID: '.$fileId.' does not have parent'
                );
            }

            return [];
        }

        if (!ConfigurationOptionManager::getBrandAttribute()) {
            foreach ($brands as $brandId => $brandData) {
                $this->importProcessService->setFailure($brandId, $fileId);
                $this->logger->getInstance()->error('BrandID: '.$brandId.' of FileID: '.$fileId.' does not have parent');
            }

            return [];
        }

        $batches = \array_chunk($brands, $this->systemService->getBatchValue(), true);

        foreach ($batches as $brandsToProcess) {
            $brandFactorySave = [];
            $brandFactoryToUpdate = [];
            $brandsToSaveIndexedByBrandMapId = [];
            $brandsToUpdateIndexedByBrandMapId = [];
            /** @var WCBrand[] $brandsIndexedByBrandMapId */
            $brandsIndexedByBrandMapId = [];

            foreach ($brandsToProcess as $brandData) {
                $brandFactory = $this->brandFactory->create($brandData);
                $brandsIndexedByBrandMapId[$brandData['BrandID']] = $brandFactory;
            }

            $brandMapIdsIndexedByBrandMapId = $this->brandMapManager->findByBrandMapIdsIndexedByBrandMapId(array_keys($brandsToProcess));
            $brandVersionsIndexedByBrandMapId = $this->brandMapManager->findVersionsIndexedByBrandMapId(array_keys($brandMapIdsIndexedByBrandMapId));
            $brandsProcessed = $this->importProcessService->getProcessedBrands(array_keys($brandMapIdsIndexedByBrandMapId), $fileId);
            $brandShopIdsIndexedBySlug = $this->brandManager->findBrandShopIdIndexedBySlug(array_values($this->getBrandsSlug($brandsToProcess)));

            if (!empty($brandMapIdsIndexedByBrandMapId)) {
                $brandsShopIds = $this->brandManager->findByBrandShopIdsIndexedByBrandId(array_values($brandMapIdsIndexedByBrandMapId));
            }

            if ($brandsShopIds) {
                $shopMappedBrandsIndexedByBrandId = \array_intersect($brandMapIdsIndexedByBrandMapId, $brandsShopIds);
            }

            /** @var WCBrand $brandMapData */
            foreach ($brandsIndexedByBrandMapId as $brandMapId => $brandMapData) {
                if (\array_key_exists($brandMapId, $brandsProcessed)
                || (\array_key_exists($brandMapId, $shopMappedBrandsIndexedByBrandId) && (int)$brandsToProcess[$brandMapId]['Version'] <= (int)$brandVersionsIndexedByBrandMapId[$brandMapId])
                ) {
                    $processedArrays[] = $this->setProcessBrands($brandMapId, $brandId, $brandsIndexedByBrandMapId, $fileId);

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
                $brandsBatchResponse = $this->brandManager->save($brandId, $brandFactorySave, $brandFactoryToUpdate);
            } catch (WooCommerceApiExceptionInterface $e) {
                $this->logger->error('Brands Batch Error - Exception Message: '.$e->getMessage());
                continue;
            }

            if (\array_key_exists('create', $brandsBatchResponse) && $brandsBatchResponse['create']) {
                $processedArrays[] =  $this->mapAndImport($brandsBatchResponse['create'], $brandsToSaveIndexedByBrandMapId, $brandIdsToUpdateIndexedByBrandMapId, $brandsToProcess, $brandId, $fileId);
            }

            if (\array_key_exists('update', $brandsBatchResponse) && $brandsBatchResponse['update']) {
                $processedArrays[] = $this->mapAndImport($brandsBatchResponse['update'], $brandsToUpdateIndexedByBrandMapId, $brandIdsToUpdateIndexedByBrandMapId, $brandsToProcess, $brandId, $fileId);
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
     * @param int $brandId
     * @param array $brandsIndexedByBrandMapId
     * @param int $fileId
     * @return array
     * @throws \Exception
     */
    public function setProcessBrands(string $brandMapId, int $brandId, array $brandsIndexedByBrandMapId, int $fileId): array
    {
        $process = [];

        $this->importProcessService->setSuccess($brandMapId, $fileId);

        if (!\array_key_exists($brandMapId, $brandsIndexedByBrandMapId)) {
            return [];
        }

        $process[$brandMapId] = [
            'id'      => $brandId,
            'visible' => true,
            'options' => [$brandsIndexedByBrandMapId[$brandMapId]->name],
        ];

        return $process;
    }

    /**
     * @param array $brandsBatchResponse
     * @param array $brandsIndexedByBrandMapId
     * @param array $brandIdsToUpdateIndexedByBrandMapId
     * @param array $brandsIndexedById
     * @param int $brandParentId
     * @param int $fileId
     * @return array
     * @throws \Exception
     */
    private function mapAndImport(array $brandsBatchResponse, array $brandsIndexedByBrandMapId, array $brandIdsToUpdateIndexedByBrandMapId, array $brandsIndexedById, int $brandParentId, int $fileId): array
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
                $brand['id'] = $this->brandManager->findBrandShopIdBySlug($brandMapData->slug);

                if ($brand['id'] > 0) {
                    $this->logger->info('Remapped Brand Id: '.$brand['id']);
                    unset($brand['error']);
                }
            }

            if (\array_key_exists('error', $brand) && $brand['error']) {
                $this->importProcessService->setFailure((int)$brandId, $fileId);
                $errorCode = $brand['error']['code'];
                $message = 'BrandID: '.$brandId.' in the FileID: '.$fileId.' has been not processed right.';

                if (isset($brand['error']['message']) && $brand['error']['message']) {
                    $internalErrorCode = ResponseService::getInternalErrorCodeFromResponseError($brand['error']['message']);
                    $message .= ' - InternalCode: '.$internalErrorCode.' - ErrorMessage: '.$brand['error']['message'];
                }

                if ($errorCode === WooCommerceErrorCodes::INVALID_REMOTE_URL) {
                    $message .= ' '.$brandMapData->image['src'];
                }

                $this->logger->getInstance()->error($message);

                continue;
            }

            $brandToMap = $this->brandMapFactory->create($brandsIndexedById[$brandId], $brand['id']);

            if (\array_key_exists($brandId, $brandIdsToUpdateIndexedByBrandMapId) && $brandIdsToUpdateIndexedByBrandMapId[$brandId]) {
                $this->brandMapManager->update($brand['id'], $brandToMap);
            } else {
                $this->brandMapManager->save($brandToMap);
            }

            $this->importProcessService->setSuccess((int)$brandId, $fileId);

            $brandModel[$brandId] =  [
                'id' => $brandParentId,
                'visible' => true,
                'options' => [$brandsIndexedById[$brandId]['BrandName']],
            ];
        }

        if ($brandsToDelete) {
            try {
                $this->brandManager->deleteCollection($brandParentId, \array_values($brandsToDelete));
            } catch (WooCommerceApiExceptionInterface $e) {
                $this->logger->error('Brand Delete of File Id:'.$fileId.' - Brand Group Id: '.$brandParentId.' Exception Message: '.$e->getMessage());
            }
        }

        return $brandModel;
    }

    /**
     * @param string $languageIsoCode
     * @return int|null
     * @throws WooCommerceApiExceptionInterface
     */
    private function createBrandGroupIfNotExists(string $languageIsoCode): ?int
    {
        $brandId = ConfigurationOptionManager::getBrandId();
        $brandShopIdByName = $this->brandManager->findIdByName();
        $brandLabel = BrandTranslations::getBrandTranslation(strtoupper($languageIsoCode));
        $brandShopIdByLabel = $this->brandManager->findIdByLabel($brandLabel);

        if ($brandId && $brandId === $brandShopIdByName && $brandId === $brandShopIdByLabel) {
            return $brandId;
        }

        if ($brandShopIdByName !== null) {
            ConfigurationOptionManager::setBrandId($brandShopIdByName);
            $this->brandManager->update($brandShopIdByName, $brandLabel);

            return $brandShopIdByName;
        }

        if ($brandShopIdByLabel !== null){
            ConfigurationOptionManager::setBrandId($brandShopIdByLabel);
            $this->brandManager->update($brandShopIdByLabel, $brandLabel);

            return $brandShopIdByLabel;
        }

        $brand = $this->brandManager->create($brandLabel);
        ConfigurationOptionManager::setBrandId($brand['id']);

        return $brand['id'];
    }

    public function deleteEmptyBrands(): void
    {
        $brandParentId = ConfigurationOptionManager::getBrandId();

        if (!$brandParentId) {
            return;
        }

        if (!$this->brandManager->exists($brandParentId)) {
            return;
        }

        $emptyBrandsIndexedByBrandShopId = $this->brandManager->findEmptyAttributeBrands();

        if (!$emptyBrandsIndexedByBrandShopId) {
            return;
        }

        $emptyBrandsExistInShop = $this->brandManager->findByBrandShopIdsIndexedByBrandId($emptyBrandsIndexedByBrandShopId);

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

            $this->deleteBrands($brandShopIdsToDelete, $brandShopIdsToDeleteMap, $brandParentId);
        }
    }

    /**
     * @param array $brandsToDelete
     * @param array $brandsMapToDelete
     * @param int $brandParentId
     */
    public function deleteBrands(array $brandsToDelete, array $brandsMapToDelete, int $brandParentId): void
    {
        try {
            $this->brandManager->deleteCollection($brandParentId, $brandsToDelete);
            $this->brandMapManager->deleteByBrandShopIds($brandsMapToDelete);
        } catch (WooCommerceApiExceptionInterface $exception) {
            $this->logger->error('Empty Brands Delete - Exception Message: '.$exception->getMessage());
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
            $brandsSlug[] = BrandFactory::BRAND_PREFIX . $brand['BrandID'];
        }

        return $brandsSlug;
    }
}