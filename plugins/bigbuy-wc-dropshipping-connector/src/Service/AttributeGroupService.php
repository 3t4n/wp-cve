<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCAttributeGroup;
use WcMipConnector\Enum\WooCommerceErrorCodes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Factory\AttributeGroupFactory;
use WcMipConnector\Manager\AttributeGroupMapManager;
use WcMipConnector\Manager\AttributeGroupManager;
use WcMipConnector\Factory\AttributeGroupMapFactory;

class AttributeGroupService
{
    /** @var AttributeGroupFactory */
    protected $attributeGroupFactory;
    /** @var AttributeGroupMapFactory */
    protected $attributeGroupMapFactory;
    /** @var AttributeGroupMapManager */
    protected $attributeGroupMapManager;
    /** @var AttributeGroupManager */
    protected $attributeGroupManager;
    /** @var ImportProcessAttributeGroupService */
    protected $importProcessService;
    /** @var LoggerService */
    protected $logger;
    /** @var SystemService */
    protected $systemService;

    public function __construct()
    {
        $this->attributeGroupFactory = new AttributeGroupFactory();
        $this->attributeGroupMapFactory = new AttributeGroupMapFactory();
        $this->attributeGroupMapManager = new AttributeGroupMapManager();
        $this->attributeGroupManager = new AttributeGroupManager();
        $this->importProcessService = new ImportProcessAttributeGroupService();
        $this->logger = new LoggerService();
        $this->systemService = new SystemService();
    }

    /**
     * @param array $attributesGroupIndexedByAttributeGroupMapId
     * @param array $attributesGroup
     * @param int $fileId
     * @param string $languageIsoCode
     * @return array
     * @throws WooCommerceApiExceptionInterface
     * @throws \Exception
     */
    public function processAttributeGroupByBatch(array $attributesGroupIndexedByAttributeGroupMapId, array $attributesGroup, int $fileId, string $languageIsoCode): array
    {
        $attributeGroupShopIndexedByAttributeGroupId = [];
        $processedArray = [[]];
        $attributeGroupShopIndexedByMapIds = [];

        $batches = array_chunk($attributesGroupIndexedByAttributeGroupMapId, $this->systemService->getBatchValue(), true);

        foreach ($batches as $batchToProcess) {
            $attributeGroupToSaveIndexedByAttributeGroupMapId = [];
            $attributeGroupIdsToUpdateIndexedByAttributeGroupMapId = [];
            $attributeGroupToUpdateIndexedByAttributeGroupMapId = [];
            $attributeGroupFactorySave = [];
            $attributeGroupFactoryToUpdate = [];

            $attributeGroupMapsIndexedByAttributeGroupMapId = $this->attributeGroupMapManager->findByAttributeGroupMapIdsIndexedByAttributeGroupMapId(array_keys($batchToProcess));
            $attributeGroupVersionsIndexedByMapIds = $this->attributeGroupMapManager->findVersionsIndexedByAttributeGroupMapId(array_keys($attributeGroupMapsIndexedByAttributeGroupMapId));
            $attributeGroupProcessedArray = $this->importProcessService->getProcessedAttributesGroup(array_keys($attributeGroupMapsIndexedByAttributeGroupMapId), $fileId);
            $attributeGroupShopIdIndexedByName = $this->attributeGroupManager->findAttributeGroupShopIdIndexedBySlug(\array_values($this->getAttributeGroupSlug($batchToProcess)));

            if (!empty($attributeGroupMapsIndexedByAttributeGroupMapId)) {
                $attributeGroupShopIndexedByAttributeGroupId = $this->attributeGroupManager->findByAttributeGroupShopIdsIndexedByAttributeGroupId(\array_values($attributeGroupMapsIndexedByAttributeGroupMapId));
            }

            if ($attributeGroupShopIndexedByAttributeGroupId) {
                foreach ($attributeGroupMapsIndexedByAttributeGroupMapId as $attributeGroupParentMappedId => $attributeGroupMappedId) {
                    if (\array_key_exists($attributeGroupMappedId, $attributeGroupShopIndexedByAttributeGroupId)) {
                        $attributeGroupShopIndexedByMapIds[$attributeGroupParentMappedId] = $attributeGroupMappedId;
                    }
                }
            }

            foreach ($batchToProcess as $attributeGroupMapId => $attributeGroupMapData) {
                if (\array_key_exists($attributeGroupMapId, $attributeGroupProcessedArray) && array_key_exists($attributeGroupMapId, $attributeGroupShopIndexedByMapIds)) {
                    $processedArray[][] = ['shopId' => (int)$attributeGroupShopIndexedByMapIds[$attributeGroupMapId], 'id' => (int)$attributeGroupMapId];
                    $this->logger->getInstance()->info('AttributeGroupID: '.$attributeGroupMapId.' in the FileID: '.$fileId.' has been already processed');
                    continue;
                }

                if (\array_key_exists($attributeGroupMapId, $attributeGroupMapsIndexedByAttributeGroupMapId)) {
                    if (\array_key_exists($attributeGroupMapId, $attributeGroupShopIndexedByMapIds)) {
                        $version = \json_decode($attributeGroupVersionsIndexedByMapIds[$attributeGroupMapId], true);
                        $attributeGroupIndexedByIsoCode = \array_column($attributesGroup[$attributeGroupMapId]['AttributeGroupLangs'], 'Version', 'IsoCode');

                        foreach ($version as $isoCode => $codeVersion) {
                            if (
                                \array_key_exists($attributeGroupMapId, $attributesGroup)
                                && \array_key_exists($isoCode, $attributeGroupIndexedByIsoCode)
                                && filter_var($codeVersion, FILTER_SANITIZE_NUMBER_INT) >= $attributeGroupIndexedByIsoCode[$isoCode]
                            ) {
                                $processedArray[] = ['shopId' => (int)$attributeGroupShopIndexedByMapIds[$attributeGroupMapId], 'id' => (int)$attributeGroupMapId];
                                $this->importProcessService->setSuccess($attributeGroupMapId, $fileId);

                                continue;
                            }
                        }

                        $attributeGroupMapData->id = $attributeGroupShopIndexedByMapIds[$attributeGroupMapId];
                        $attributeGroupIdsToUpdateIndexedByAttributeGroupMapId[$attributeGroupMapId] = true;
                        $attributeGroupFactoryToUpdate[] = $attributeGroupMapData;
                        $attributeGroupToUpdateIndexedByAttributeGroupMapId[$attributeGroupMapId] = $attributeGroupMapData;
                        continue;
                    }

                    if (\array_key_exists($attributeGroupMapData->slug, $attributeGroupShopIdIndexedByName)) {
                        $attributeGroupMapData->id = $attributeGroupShopIdIndexedByName[$attributeGroupMapData->slug];
                        $attributeGroupIdsToUpdateIndexedByAttributeGroupMapId[$attributeGroupMapId] = true;
                        $attributeGroupFactoryToUpdate[] = $attributeGroupMapData;
                        $attributeGroupToUpdateIndexedByAttributeGroupMapId[$attributeGroupMapId] = $attributeGroupMapData;
                        continue;
                    }

                    $attributeGroupToSaveIndexedByAttributeGroupMapId[$attributeGroupMapId] = $attributeGroupMapData;
                    $attributeGroupIdsToUpdateIndexedByAttributeGroupMapId[$attributeGroupMapId] = true;
                    $attributeGroupFactorySave[] = $attributeGroupMapData;
                }

                if (\array_key_exists($attributeGroupMapData->slug, $attributeGroupShopIdIndexedByName)) {
                    $attributeGroupMapData->id = $attributeGroupShopIdIndexedByName[$attributeGroupMapData->slug];
                    $attributeGroupIdsToUpdateIndexedByAttributeGroupMapId[$attributeGroupMapId] = false;
                    $attributeGroupFactoryToUpdate[] = $attributeGroupMapData;
                    $attributeGroupToUpdateIndexedByAttributeGroupMapId[$attributeGroupMapId] = $attributeGroupMapData;
                    continue;
                }

                if (!array_key_exists($attributeGroupMapId, $attributeGroupMapsIndexedByAttributeGroupMapId)) {
                    $attributeGroupIdsToUpdateIndexedByAttributeGroupMapId[$attributeGroupMapId] = false;
                    $attributeGroupFactorySave[] = $attributeGroupMapData;
                    $attributeGroupToSaveIndexedByAttributeGroupMapId[$attributeGroupMapId] = $attributeGroupMapData;
                    continue;
                }
            }

            if (!$attributeGroupFactorySave && !$attributeGroupFactoryToUpdate) {
                continue;
            }

            try{
                $this->logger->debug('Started the API ATTRIBUTE GROUP UPDATE process');
                $attributeGroupBatchResponse = $this->attributeGroupManager->updateCollection($attributeGroupFactoryToUpdate);

                if (\array_key_exists('update', $attributeGroupBatchResponse) && $attributeGroupBatchResponse['update']) {
                    $processedArray[] = $this->mapAndImport($attributeGroupBatchResponse['update'],
                        $attributeGroupToUpdateIndexedByAttributeGroupMapId,
                        $attributeGroupIdsToUpdateIndexedByAttributeGroupMapId,
                        $attributesGroup,
                        $fileId,
                        $languageIsoCode
                    );
                }
            } catch (WooCommerceApiExceptionInterface $e) {
                $this->logger->error(__METHOD__.' Batch AttributeGroup Update Error - Exception Message: '.$e->getMessage());
            }

            $this->logger->debug('Finished the API ATTRIBUTE GROUP UPDATE process');

            try{
                $this->logger->debug('Started the API ATTRIBUTE GROUP CREATE process');
                $attributeGroupBatchResponse = $this->attributeGroupManager->createCollection($attributeGroupFactorySave);

                if (\array_key_exists('create', $attributeGroupBatchResponse) && $attributeGroupBatchResponse['create']) {
                    $processedArray[] =  $this->mapAndImport($attributeGroupBatchResponse['create'],
                        $attributeGroupToSaveIndexedByAttributeGroupMapId,
                        $attributeGroupIdsToUpdateIndexedByAttributeGroupMapId,
                        $attributesGroup,
                        $fileId,
                        $languageIsoCode
                    );
                }
            } catch (WooCommerceApiExceptionInterface $e) {
                $this->logger->error(__METHOD__.' Batch AttributeGroup Create Error - Exception Message: '.$e->getMessage());
            }

            $this->logger->debug('Finished the API ATTRIBUTE GROUP CREATE process');
        }

        $processed = array_merge(...$processedArray);

        return \array_column($processed, 'shopId', 'id');
    }

    /**
     * @param array $attributeGroupBatchResponse
     * @param array $attributeGroupIndexedByAttributeGroupMapId
     * @param array $attributeGroupIdsToUpdateIndexedByAttributeGroupMapId
     * @param array $attributeGroups
     * @param int $fileId
     * @param string $languageIsoCode
     * @return array
     * @throws \Exception
     */
    private function mapAndImport(array $attributeGroupBatchResponse, array $attributeGroupIndexedByAttributeGroupMapId, array $attributeGroupIdsToUpdateIndexedByAttributeGroupMapId, array $attributeGroups, int $fileId, string $languageIsoCode): array
    {
        $attributeGroupIds = [];
        $attributeGroupMapIndex = 0;

        foreach ($attributeGroupIndexedByAttributeGroupMapId as $attributeGroupId => $attributeGroupMapData) {
            $attributeGroup = $attributeGroupBatchResponse[$attributeGroupMapIndex];
            $attributeGroupMapIndex++;

            if (!$attributeGroup) {
                continue;
            }

            if (\array_key_exists('error', $attributeGroup) && $attributeGroup['error']['code'] === WooCommerceErrorCodes::CANNOT_CREATE) {
                $attributeGroup['id'] = $this->attributeGroupManager->findAttributeGroupShopIdBySlug($attributeGroupMapData->slug);
                if ($attributeGroup['id'] > 0) {
                    $this->logger->info('Remapped AttributeGroup Id: '.$attributeGroup['id']);
                    unset($attributeGroup['error']);
                }
            }

            if (\array_key_exists('error', $attributeGroup) && $attributeGroup['error']) {
                $this->importProcessService->setFailure($attributeGroupId, $fileId);
                $this->logger->getInstance()->error('AttributeGroupID: '.$attributeGroupId.' in the FileID: '.$fileId.' has been not processed. Batch response: '
                    .$attributeGroup['error']['code'].'. AttributeGroup object: '.\json_encode($attributeGroupMapData, true));
                continue;
            }

            $attributeGroupToMap = $this->attributeGroupMapFactory->create($attributeGroups[$attributeGroupId], $attributeGroup['id'], $languageIsoCode);

            if (\array_key_exists($attributeGroupId, $attributeGroupIdsToUpdateIndexedByAttributeGroupMapId) && $attributeGroupIdsToUpdateIndexedByAttributeGroupMapId[$attributeGroupId]) {
                $this->attributeGroupMapManager->update($attributeGroup['id'], $attributeGroupToMap);
            }else{
                $this->attributeGroupMapManager->save($attributeGroupToMap);
            }

            $this->importProcessService->setSuccess($attributeGroupId, $fileId);

            $attributeGroupIds[] = ['shopId' => $attributeGroup['id'], 'id' => $attributeGroupId];
        }

        return $attributeGroupIds;
    }

    /**
     * @param array $attributesGroup
     * @return array
     */
    public function getAttributeGroupSlug(array $attributesGroup): array
    {
        $attributesGroupSlug = [];

        /** @var WCAttributeGroup $attributeGroup */
        foreach ($attributesGroup as $attributeGroup) {
            $attributesGroupSlug[] = $attributeGroup->slug;
        }

        return $attributesGroupSlug;
    }

    /**
     * @param array $attributesGroup
     */
    public function deleteEmptyAttributeGroup(array $attributesGroup): void
    {
        $attributeGroupIds = [];
        $attributesGroupShopIdsToDelete = [];
        $attributesGroupShopIdsToDeleteMap = [];

        $attributesGroupIndexedByName = $this->attributeGroupManager->findAttributesGroup(\array_keys($attributesGroup));

        foreach ($attributesGroup as $attributesGroupName => $attributeGroupShopId) {
            if (!\array_key_exists($attributesGroupName, $attributesGroupIndexedByName)) {
                $attributeGroupIds[$attributeGroupShopId] = $attributeGroupShopId;
            }
        }

        $emptyAttributesGroupIndexedByAttributeGroupShopId = $this->attributeGroupManager->findByAttributeGroupShopIdsIndexedByAttributeGroupId(\array_values($attributeGroupIds));

        foreach ($attributeGroupIds as $attributeGroupShopId) {
            if (\array_key_exists($attributeGroupShopId, $emptyAttributesGroupIndexedByAttributeGroupShopId)) {
                $attributesGroupShopIdsToDelete[$attributeGroupShopId] = $attributeGroupShopId;
            }

            $attributesGroupShopIdsToDeleteMap[$attributeGroupShopId] = $attributeGroupShopId;
        }

        try {
            $this->attributeGroupManager->deleteCollection($attributesGroupShopIdsToDelete);
            $this->attributeGroupMapManager->deleteByAttributeGroupShopIds($attributesGroupShopIdsToDeleteMap);
        } catch (WooCommerceApiExceptionInterface $exception) {
            $this->logger->error('Empty Attributes Group Delete - Exception Message: '.$exception->getMessage());
        }
    }
}