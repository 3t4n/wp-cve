<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCAttribute;
use WcMipConnector\Entity\WCAttributeGroup;
use WcMipConnector\Enum\WooCommerceErrorCodes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Factory\AttributeFactory;
use WcMipConnector\Factory\AttributeGroupFactory;
use WcMipConnector\Factory\AttributeMapFactory;
use WcMipConnector\Manager\AttributeGroupMapManager;
use WcMipConnector\Manager\AttributeManager;
use WcMipConnector\Manager\AttributeMapManager;

class AttributeService
{
    /** @var AttributeManager */
    protected $attributeManager;
    /** @var AttributeMapManager */
    protected $attributeMapManager;
    /** @var AttributeFactory */
    protected $attributeFactory;
    /** @var AttributeMapFactory */
    protected $attributeMapFactory;
    /** @var AttributeGroupFactory */
    protected $attributeGroupFactory;
    /** @var AttributeGroupService */
    protected $attributeGroupService;
    /** @var ImportProcessAttributeService */
    protected $importProcessService;
    /** @var LoggerService */
    protected $logger;
    /** @var array */
    protected $languages;
    /** @var SystemService */
    protected $systemService;
    /** @var AttributeGroupMapManager */
    protected $attributeGroupMapManager;

    public function __construct()
    {
        $this->attributeManager = new AttributeManager();
        $this->attributeMapManager = new AttributeMapManager();
        $this->attributeFactory = new AttributeFactory();
        $this->attributeMapFactory = new AttributeMapFactory();
        $this->attributeGroupFactory = new AttributeGroupFactory();
        $this->attributeGroupService = new AttributeGroupService();
        $this->importProcessService = new ImportProcessAttributeService();
        $this->logger = new LoggerService();
        $this->systemService = new SystemService();
        $this->attributeGroupMapManager = new AttributeGroupMapManager();
    }

    /**
     * @param array $attributes
     * @param int $fileId
     * @param string $languageIsoCode
     * @return array
     * @throws \Exception
     */
    public function process(array $attributes, int $fileId, string $languageIsoCode): array
    {
        if (empty($attributes) || empty($languageIsoCode)) {
            return [];
        }

        /** @var WCAttributeGroup[] $attributesGroupIndexedByAttributeGroupMapId */
        $attributesGroupIndexedByAttributeGroupMapId = [];
        $attributesToProcessIndexedByParent = [];
        $attributeResult = [];

        foreach ($attributes['AttributeGroup'] as $attributeGroupData) {
            $attributeGroupFactory = $this->attributeGroupFactory->create($attributeGroupData, $languageIsoCode);
            $attributesGroupIndexedByAttributeGroupMapId[$attributeGroupData['AttributeGroupID']] = $attributeGroupFactory;
        }

        foreach ($attributes['Attribute'] as $attributeParentId => $attributeData) {
            /** @var WCAttributeGroup[] $attributesIndexedByAttributeMapId */
            $attributesIndexedByAttributeMapId = [];
            foreach ($attributeData as $attributeChild) {
                $attributeFactory = $this->attributeFactory->create($attributeChild, $languageIsoCode);
                $attributesIndexedByAttributeMapId[$attributeChild['AttributeID']] = $attributeFactory;
                $attributesToProcessIndexedByParent[$attributeParentId] = $attributesIndexedByAttributeMapId;
            }
        }

        $attributeGroup = $this->attributeGroupService->processAttributeGroupByBatch($attributesGroupIndexedByAttributeGroupMapId, $attributes['AttributeGroup'], $fileId, $languageIsoCode);

        foreach ($attributesToProcessIndexedByParent as $attributeGroupParentId => $attributesToProcess) {
            $attributeResult[$attributeGroupParentId] = $this->processAttributeByBatch($attributesToProcess, $attributeGroup[$attributeGroupParentId], $attributes['Attribute'][$attributeGroupParentId], $fileId, $languageIsoCode);
        }

        return $attributeResult;
    }

    /**
     * @param array|null $attributesIndexedByAttributeMapId
     * @param int|null $attributeParentId
     * @param array|null $attributes
     * @param int|null $fileId
     * @param string $languageIsoCode
     * @return array
     * @throws \Exception
     */
    public function processAttributeByBatch(array $attributesIndexedByAttributeMapId = null, int $attributeParentId = null, array $attributes = null, int $fileId = null, string $languageIsoCode): array
    {
        $attributeShopIndexedByAttributeId = [];
        $attributeIdsToUpdateIndexedByAttributeMapId = [];
        $processedArrays = [];
        $arrayResult = [];
        $process = [];
        $attributeShopIndexedByMapIds = [];

        if (!$attributeParentId || !$attributeParent = wc_get_attribute($attributeParentId)) {
            $this->importProcessService->setBatchFailure(\array_keys($attributesIndexedByAttributeMapId), $fileId);

            return [];
        }

        $batches = array_chunk($attributesIndexedByAttributeMapId, $this->systemService->getBatchValue(), true);

        foreach ($batches as $attributesToProcess) {
            $attributeFactorySave = [];
            $attributeFactoryToUpdate = [];
            $attributeToSaveIndexedByAttributeMapId = [];
            $attributeToUpdateIndexedByAttributeMapId = [];
            $tagsToNotProcess = [];

            $attributeMapsIndexedByAttributeMapId = $this->attributeMapManager->findByAttributeMapIdsIndexedByAttributeMapId(array_keys($attributesToProcess));
            $attributeVersionsIndexedByMapIds = $this->attributeMapManager->findVersionsIndexedByAttributeMapId(array_keys($attributeMapsIndexedByAttributeMapId));
            $attributeProcessedArray = $this->importProcessService->getProcessedAttributes(array_keys($attributeMapsIndexedByAttributeMapId), $fileId);
            $attributeShopIdIndexedByName = $this->attributeManager->findAttributeShopIdIndexedBySlug(array_values($this->getAttributeSlug($attributesToProcess)), $attributeParent->slug);

            if (!empty($attributeMapsIndexedByAttributeMapId)) {
                $attributeShopIndexedByAttributeId = $this->attributeManager->findByAttributeShopIdsIndexedByAttributeId(\array_values($attributeMapsIndexedByAttributeMapId), $attributeParent->slug);
            }

            if ($attributeShopIndexedByAttributeId) {
                foreach ($attributeMapsIndexedByAttributeMapId as $attributeParentMappedId => $attributeShopIndexedByMapId) {
                    if (\array_key_exists($attributeShopIndexedByMapId, $attributeShopIndexedByAttributeId)) {
                        $attributeShopIndexedByMapIds[$attributeParentMappedId] = $attributeShopIndexedByMapId;
                    }
                }
            }

            foreach ($attributesToProcess as $attributeMapId => $attributeMapData) {
                if (\array_key_exists($attributeMapId, $attributeProcessedArray)) {
                    $processedArrays[][$attributeMapId] = [
                        'id' => $attributeParentId,
                        'name' => $attributeMapData->name,
                    ];

                    $this->logger->getInstance()->info('AttributeID: '.$attributeMapId.' in the FileID: '.$fileId.' has been already processed');
                    continue;
                }

                if (\array_key_exists($attributeMapId, $attributeMapsIndexedByAttributeMapId)) {
                    if (\array_key_exists($attributeMapId, $attributeShopIndexedByMapIds)) {
                        $version = json_decode($attributeVersionsIndexedByMapIds[$attributeMapId], true);
                        $attributeIndexedByIsoCode = \array_column(
                            $attributes[$attributeMapId]['AttributeLangs'],
                            'Version',
                            'IsoCode'
                        );

                        foreach ($version as $isoCode => $codeVersion) {
                            if (
                                \array_key_exists($attributeMapId, $attributes)
                                && \array_key_exists($isoCode, $attributeIndexedByIsoCode)
                                && filter_var($codeVersion, FILTER_SANITIZE_NUMBER_INT) >= $attributeIndexedByIsoCode[$isoCode]
                            ) {
                                $tagsToNotProcess[$attributeMapId] = true;
                                $process[$attributeMapId] = [
                                    'id' => $attributeParentId,
                                    'name' => $attributesToProcess[$attributeMapId]->name,
                                ];

                                $this->importProcessService->setSuccess($attributeMapId, $fileId);

                                continue;
                            }
                        }

                        if (\array_key_exists($attributeMapId, $tagsToNotProcess)) {
                            $processedArrays[] = $process;
                            continue;
                        }

                        $attributeMapData->id = $attributeShopIndexedByMapIds[$attributeMapId];
                        $attributeIdsToUpdateIndexedByAttributeMapId[$attributeMapId] = true;
                        $attributeFactoryToUpdate[] = $attributeMapData;
                        $attributeToUpdateIndexedByAttributeMapId[$attributeMapId] = $attributeMapData;
                        continue;
                    }

                    if (\array_key_exists($attributeMapData->slug, $attributeShopIdIndexedByName)) {
                        $attributeMapData->id = $attributeShopIdIndexedByName[$attributeMapData->slug];
                        $attributeIdsToUpdateIndexedByAttributeMapId[$attributeMapId] = true;
                        $attributeFactoryToUpdate[] = $attributeMapData;
                        $attributeToUpdateIndexedByAttributeMapId[$attributeMapId] = $attributeMapData;
                        continue;
                    }

                    $attributeToSaveIndexedByAttributeMapId[$attributeMapId] = $attributeMapData;
                    $attributeIdsToUpdateIndexedByAttributeMapId[$attributeMapId] = true;
                    $attributeFactorySave[] = $attributeMapData;
                }

                if (\array_key_exists($attributeMapData->slug, $attributeShopIdIndexedByName)) {
                    $attributeMapData->id = $attributeShopIdIndexedByName[$attributeMapData->slug];
                    $attributeIdsToUpdateIndexedByAttributeMapId[$attributeMapId] = false;
                    $attributeFactoryToUpdate[] = $attributeMapData;
                    $attributeToUpdateIndexedByAttributeMapId[$attributeMapId] = $attributeMapData;
                    continue;
                }

                if (!array_key_exists($attributeMapId, $attributeMapsIndexedByAttributeMapId)) {
                    $attributeIdsToUpdateIndexedByAttributeMapId[$attributeMapId] = false;
                    $attributeFactorySave[] = $attributeMapData;
                    $attributeToSaveIndexedByAttributeMapId[$attributeMapId] = $attributeMapData;
                    continue;
                }
            }

            if (!$attributeFactorySave && !$attributeFactoryToUpdate) {
                continue;
            }

            try{
                $this->logger->debug('Started the API ATTRIBUTE UPDATE process');
                $attributeBatchResponse = $this->attributeManager->updateCollection($attributeFactoryToUpdate, $attributeParentId);

                if (\array_key_exists('update', $attributeBatchResponse) && $attributeBatchResponse['update']) {
                    $processedArrays[] = $this->mapAndImport(
                        $attributeBatchResponse['update'],
                        $attributeToUpdateIndexedByAttributeMapId,
                        $attributeIdsToUpdateIndexedByAttributeMapId,
                        $attributes,
                        $attributeParentId,
                        $fileId,
                        $languageIsoCode
                    );
                }
            } catch (WooCommerceApiExceptionInterface $e) {
                $this->logger->error(__METHOD__.' Batch Attribute Update Error - Exception Message: '.$e->getMessage());
            }

            $this->logger->debug('Finished the API ATTRIBUTE UPDATE process');

            try{
                $this->logger->debug('Started the API ATTRIBUTE CREATE process');
                $attributeBatchResponse = $this->attributeManager->createCollection($attributeFactorySave, $attributeParentId);

                if (\array_key_exists('create', $attributeBatchResponse) && $attributeBatchResponse['create']) {
                    $processedArrays[] = $this->mapAndImport(
                        $attributeBatchResponse['create'],
                        $attributeToSaveIndexedByAttributeMapId,
                        $attributeIdsToUpdateIndexedByAttributeMapId,
                        $attributes,
                        $attributeParentId,
                        $fileId,
                        $languageIsoCode
                    );
                }
            } catch (WooCommerceApiExceptionInterface $e) {
                $this->logger->error(__METHOD__.' Batch Attribute Create Batch Attribute Error - Exception Message: '.$e->getMessage());
            }

            $this->logger->debug('Finished the API ATTRIBUTE CREATE process');
        }

        foreach ($processedArrays as $processedArray) {
            foreach ($processedArray as $processedArrayParent => $processed) {
                $arrayResult[$processedArrayParent] = $processed;
            }
        }

        return $arrayResult;
    }

    /**
     * @param array $attributeBatchResponse
     * @param array $attributeIndexedByAttributeMapId
     * @param array $attributeIdsToUpdateIndexedByAttributeMapId
     * @param array $attributes
     * @param int $attributeParentId
     * @param int $fileId
     * @param string $languageIsoCode
     * @return array
     * @throws \Exception
     */
    private function mapAndImport(array $attributeBatchResponse, array $attributeIndexedByAttributeMapId, array $attributeIdsToUpdateIndexedByAttributeMapId, array $attributes, int $attributeParentId, int $fileId, string $languageIsoCode): array
    {
        $attributeModel = [];
        $attributeMapIndex = 0;

        foreach ($attributeIndexedByAttributeMapId as $attributeId => $attributeMapData) {
            $attribute = $attributeBatchResponse[$attributeMapIndex];
            $attributeMapIndex++;

            if (!$attribute) {
                continue;
            }

            if (\array_key_exists('error', $attribute) && $attribute['error']['code'] === WooCommerceErrorCodes::TERM_EXISTS) {
                $attribute['id'] = $this->attributeManager->findAttributeShopIdBySlug($attributeMapData->slug);
                if ($attribute['id'] > 0) {
                    $attributeBatchResponse[$attributeMapIndex]['name'] = $attributeMapData->name;
                    $this->logger->info('Remapped Attribute Id: '.$attribute['id']);
                    unset($attribute['error']);
                }
            }

            if (\array_key_exists('error', $attribute) && $attribute['error']) {
                $this->importProcessService->setFailure($attributeId, $fileId);
                $this->logger->getInstance()->error('AttributeID: '.$attributeId.' in the FileID: '.$fileId.' has been not processed - Attribute Group ID: '.$attributeParentId.' - Batch response: '
                    .$attribute['error']['code']);
                continue;
            }

            $attributeToMap = $this->attributeMapFactory->create($attributes[$attributeId], $attribute['id'], $languageIsoCode);

            if (\array_key_exists($attributeId, $attributeIdsToUpdateIndexedByAttributeMapId) && $attributeIdsToUpdateIndexedByAttributeMapId[$attributeId]) {
                $this->attributeMapManager->update($attribute['id'], $attributeToMap);
            }else{
                $this->attributeMapManager->save($attributeToMap);
            }

            $this->importProcessService->setSuccess($attributeId, $fileId);

            $attributeModel[$attributeId] =  [
                'id' => $attributeParentId,
                'name' => $attribute['name'],
            ];
        }

        return $attributeModel;
    }

    /**
     * @param array $attributes
     * @return array
     */
    public function getAttributeSlug(array $attributes): array
    {
        $attributesSlug = [];

        /** @var WCAttribute $attribute */
        foreach ($attributes as $attribute) {
            $attributesSlug[] = $attribute->slug;
        }

        return $attributesSlug;
    }

    public function deleteEmptyAttributes(): void
    {
        $attributesIndexedByAttributesGroupId = [];
        $attributesGroupIndexedByName = [];

        $emptyAttributesIndexedByAttributeShopId = $this->attributeManager->findEmptyAttributes();

        if (!$emptyAttributesIndexedByAttributeShopId) {
            return;
        }

        $emptyAttributesIndexedByAttributeGroupShopId = $this->attributeManager->findAttributesIndexedByAttributeGroupShopId(array_keys($emptyAttributesIndexedByAttributeShopId));

        if (!$emptyAttributesIndexedByAttributeGroupShopId) {
            return;
        }

        $attributeGroupIdsIndexedByAttributeMapId = $this->attributeGroupMapManager->getAttributeGroupIdsIndexedByAttributeMapId();

        foreach ($emptyAttributesIndexedByAttributeGroupShopId as $attribute) {
            $attributeGroupParentId = filter_var($attribute['taxonomy'], FILTER_SANITIZE_NUMBER_INT);

            if (\array_key_exists($attributeGroupParentId, $attributeGroupIdsIndexedByAttributeMapId)) {
                $attributesIndexedByAttributesGroupId[$attributeGroupIdsIndexedByAttributeMapId[$attributeGroupParentId]][] = $attribute['term_id'];
                $attributesGroupIndexedByName[$attribute['taxonomy']] = $attributeGroupIdsIndexedByAttributeMapId[$attributeGroupParentId];
            }
        }

        foreach ($attributesIndexedByAttributesGroupId as $attributeGroupParentId => $attributesIndexedByAttributeGroupId) {
            $batches = array_chunk($attributesIndexedByAttributeGroupId, $this->systemService->getBatchValue(), true);

            foreach ($batches as $batchToProcess) {
                $attributeShopIdsToDelete = [];
                $attributeShopIdsToDeleteMap = [];

                foreach ($batchToProcess as $attributeShopId) {
                    if (\array_key_exists($attributeShopId, $emptyAttributesIndexedByAttributeGroupShopId)) {
                        $attributeShopIdsToDelete[$attributeShopId] = $attributeShopId;
                    }

                    $attributeShopIdsToDeleteMap[$attributeShopId] = $attributeShopId;
                }

                try {
                    $this->attributeManager->deleteCollection($attributeShopIdsToDelete, $attributeGroupParentId);
                    $this->attributeMapManager->deleteByAttributeShopIds($attributeShopIdsToDeleteMap);
                } catch (WooCommerceApiExceptionInterface $exception) {
                    $this->logger->info('Empty Attributes Delete - Exception Message: '.$exception->getMessage());
                }
            }
        }

        $this->attributeGroupService->deleteEmptyAttributeGroup($attributesGroupIndexedByName);
    }
}