<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\WooCommerceErrorCodes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Factory\TagMapFactory;
use WcMipConnector\Factory\TagFactory;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Manager\TagManager;
use WcMipConnector\Manager\TagMapManager;

class TagService
{
    /** @var TagFactory  */
    protected $tagFactory;
    /** @var TagMapFactory  */
    protected $tagMapFactory;
    /** @var TagMapManager  */
    protected $tagMapManager;
    /** @var TagManager  */
    protected $tagManager;
    /** @var ImportProcessTagService  */
    protected $importProcessService;
    /** @var LoggerService  */
    protected $loggerService;
    /** @var LoggerService  */
    protected $logger;
    /** @var SystemService */
    protected $systemService;

    public function __construct()
    {
        $this->tagFactory = new TagFactory();
        $this->tagMapFactory = new TagMapFactory();
        $this->tagMapManager = new TagMapManager();
        $this->tagManager = new TagManager();
        $this->importProcessService = new ImportProcessTagService();
        $this->loggerService = new LoggerService();
        $this->logger = $this->loggerService->getInstance();
        $this->systemService = new SystemService();
    }

    /**
     * @param array $tags
     * @param int $fileId
     * @param string $languageIsoCode
     * @return array
     * @throws \Exception
     */
    public function processByBatch(array $tags, int $fileId, string $languageIsoCode): array
    {
        if (empty($tags)) {
            return [];
        }

        $tagsShopIndexedByTagId = [];
        $tagShopIndexedByMapIds = [];
        $batches = \array_chunk($tags, $this->systemService->getBatchValue(), true);
        $tagsProcessedArray = [[]];

        foreach ($batches as $tagsToProcess) {
            $tagFactorySave = [];
            $tagFactoryToUpdate = [];
            $tagsToSaveIndexedByTagMapId = [];
            $tagsToUpdateIndexedByTagMapId = [];
            $tagIdsToUpdateIndexedByTagMapId = [];
            $tagsIndexedByTagMapId = [];

            foreach ($tagsToProcess as $tagData) {
                $tagFactory = $this->tagFactory->create($tagData, $languageIsoCode);
                $tagsIndexedByTagMapId[$tagData['TagID']] = $tagFactory;
            }

            $tagMapsIndexedByTagMapId = $this->tagMapManager->findByTagMapIdsIndexedByTagMapId(\array_keys($tagsToProcess));
            $tagVersionsIndexedByTagMapId = $this->tagMapManager->findVersionsIndexedByTagMapId(\array_keys($tagMapsIndexedByTagMapId));
            $tagsProcessed = $this->importProcessService->getProcessedTags(\array_keys($tagMapsIndexedByTagMapId), $fileId);
            $tagsShopIdIndexedBySlug = $this->tagManager->findTagsShopIdIndexedBySlug(\array_values($this->getTagsSlug($tagsToProcess)));

            if (!empty($tagMapsIndexedByTagMapId)) {
                $tagsShopIndexedByTagId = $this->tagManager->findByTagShopIdsIndexedByTagId(\array_values($tagMapsIndexedByTagMapId));
            }

            if ($tagsShopIndexedByTagId) {
                $tagShopIndexedByMapIds = \array_intersect($tagMapsIndexedByTagMapId, $tagsShopIndexedByTagId);
            }

            foreach ($tagsIndexedByTagMapId as $tagMapId => $tagMapData) {
                if (\array_key_exists($tagMapId, $tagsProcessed) && \array_key_exists($tagMapId, $tagShopIndexedByMapIds)) {
                    $tagsProcessedArray[][] = ['shopId' => (int)$tagShopIndexedByMapIds[$tagMapId], 'id' => (int)$tagMapId];
                    $this->logger->getInstance()->info('TagID: '.$tagMapId.' in the FileID: '.$fileId.' has been already processed');

                    continue;
                }

                if (\array_key_exists($tagMapId, $tagMapsIndexedByTagMapId)) {
                    if (\array_key_exists($tagMapId, $tagShopIndexedByMapIds)) {
                        $versionToNotProcess = false;
                        $version = \json_decode($tagVersionsIndexedByTagMapId[$tagMapId], true);
                        $tagsIndexedByIsoCode = \array_column($tagsToProcess[$tagMapId]['TagLangs'], 'Version', 'IsoCode');

                        foreach ($tagsIndexedByIsoCode as $isoCode => $versionLang) {
                            if (\array_key_exists($isoCode, $version)
                                && $version[$isoCode] >= $tagsIndexedByIsoCode[$isoCode]
                            ) {
                                $tagsProcessedArray[][] = ['shopId' => (int)$tagShopIndexedByMapIds[$tagMapId], 'id' => (int)$tagMapId];
                                $versionToNotProcess = true;
                                break;
                            }
                        }

                        if ($versionToNotProcess) {
                            $this->importProcessService->setSuccess($tagMapId, $fileId);

                            continue;
                        }

                        $tagMapData->id = $tagShopIndexedByMapIds[$tagMapId];
                        $tagIdsToUpdateIndexedByTagMapId[$tagMapId] = true;
                        $tagFactoryToUpdate[] = $tagMapData;
                        $tagsToUpdateIndexedByTagMapId[$tagMapId] = $tagMapData;

                        continue;
                    }

                    if (\array_key_exists($tagMapData->slug, $tagsShopIdIndexedBySlug)) {
                        $tagMapData->id = $tagsShopIdIndexedBySlug[$tagMapData->slug];
                        $tagIdsToUpdateIndexedByTagMapId[$tagMapId] = true;
                        $tagFactoryToUpdate[] = $tagMapData;
                        $tagsToUpdateIndexedByTagMapId[$tagMapId] = $tagMapData;

                        continue;
                    }

                    $tagIdsToUpdateIndexedByTagMapId[$tagMapId] = true;
                    $tagsToSaveIndexedByTagMapId[$tagMapId] = $tagMapData;
                    $tagFactorySave[] = $tagMapData;

                    continue;
                }

                if (\array_key_exists($tagMapData->slug, $tagsShopIdIndexedBySlug)) {
                    $tagMapData->id = $tagsShopIdIndexedBySlug[$tagMapData->slug];
                    $tagIdsToUpdateIndexedByTagMapId[$tagMapId] = false;
                    $tagFactoryToUpdate[] = $tagMapData;
                    $tagsToUpdateIndexedByTagMapId[$tagMapId] = $tagMapData;

                    continue;
                }

                $tagIdsToUpdateIndexedByTagMapId[$tagMapId] = false;
                $tagFactorySave[] = $tagMapData;
                $tagsToSaveIndexedByTagMapId[$tagMapId] = $tagMapData;
            }

            if (!$tagFactorySave && !$tagFactoryToUpdate) {
                continue;
            }

            try {
                $this->logger->debug('Started the API TAG UPDATE process');

                $tagsBatchResponse = $this->tagManager->updateCollection($tagFactoryToUpdate);

                if (\array_key_exists('update', $tagsBatchResponse) && $tagsBatchResponse['update']) {
                    $tagsProcessedArray[] = $this->mapAndImport(
                        $tagsBatchResponse['update'],
                        $tagsToUpdateIndexedByTagMapId,
                        $tagIdsToUpdateIndexedByTagMapId,
                        $tagsToProcess,
                        $fileId,
                        $languageIsoCode
                    );
                }
            } catch (WooCommerceApiExceptionInterface $e) {
                $this->logger->error(__METHOD__.' Tag Batch Update - Exception Message: '.$e->getMessage());
            }

            $this->logger->debug('Finished the API TAG UPDATE process');

            try {
                $this->logger->debug('Started the API TAG CREATE process');
                $tagsBatchResponse = $this->tagManager->createCollection($tagFactorySave);

                if (\array_key_exists('create', $tagsBatchResponse) && $tagsBatchResponse['create']) {
                    $tagsProcessedArray[] = $this->mapAndImport(
                        $tagsBatchResponse['create'],
                        $tagsToSaveIndexedByTagMapId,
                        $tagIdsToUpdateIndexedByTagMapId,
                        $tagsToProcess,
                        $fileId,
                        $languageIsoCode
                    );
                }
            } catch (WooCommerceApiExceptionInterface $e) {
                $this->logger->error(__METHOD__.' Tag Batch Create - Exception Message: '.$e->getMessage());
            }

            $this->logger->debug('Finished the API TAG CREATE process');
        }

        $tagsProcessed = \array_merge(...$tagsProcessedArray);

        return \array_column($tagsProcessed, 'shopId', 'id');
    }

    /**
     * @param array $tagsBatchResponse
     * @param array $tagsIndexedByTagMapId
     * @param array $tagIdsToUpdateIndexedByTagMapId
     * @param array $tags
     * @param int $fileId
     * @param string $languageIsoCode
     * @return array
     * @throws \Exception
     */
    private function mapAndImport(array $tagsBatchResponse, array $tagsIndexedByTagMapId, array $tagIdsToUpdateIndexedByTagMapId, array $tags, int $fileId, string $languageIsoCode): array
    {
        $tagIds = [];
        $tagMapIndex = 0;

        foreach ($tagsIndexedByTagMapId as $tagId => $tagMapData) {
            $tag = $tagsBatchResponse[$tagMapIndex];
            $tagMapIndex++;

            if (!$tag) {
                continue;
            }

            if (\array_key_exists('error', $tag) && $tag['error']['code'] === WooCommerceErrorCodes::TERM_EXISTS) {
                $tag['id'] = (int)$this->tagManager->findTagShopIdBySlug($tagMapData->slug);
                if ($tag['id'] > 0) {
                    $this->logger->info('Remapped Tag Id: '.$tag['id']);
                    unset($tag['error']);
                }
            }

            if (\array_key_exists('error', $tag) && $tag['error']) {
                $this->importProcessService->setFailure($tagId, $fileId);
                $this->logger->error('Processing Tag Id: '.$tagId.' in the File Id: '.$fileId.' - Batch Error: '.$tag['error']['code'].' - Tag object: '.\json_encode($tagMapData, true));
                continue;
            }

            $tagToMap = $this->tagMapFactory->create($tags[$tagId], $tag['id'], $languageIsoCode);

            if (\array_key_exists($tagId, $tagIdsToUpdateIndexedByTagMapId) && $tagIdsToUpdateIndexedByTagMapId[$tagId]) {
                $this->tagMapManager->update($tag['id'], $tagToMap);
            } else {
                $this->tagMapManager->save($tagToMap);
            }

            $this->importProcessService->setSuccess($tagId, $fileId);

            $tagIds[] = ['shopId' => $tag['id'], 'id' => $tagId];
        }

        return $tagIds;
    }

    /**
     * @param array $productIds
     * @return array
     */
    public function getProductTagsIndexedByProductIds(array $productIds): array
    {
        return $this->tagManager->getProductTagsIndexedByProductIds($productIds);
    }

    /**
     * @param array $productTagIdsIndexedByProductId
     * @return array
     */
    public function findByTagShopIdsIndexedByTagMapIds(array $productTagIdsIndexedByProductId): array
    {
        $productTagIdsIndexedByMapIds = [];

        foreach ($productTagIdsIndexedByProductId as $productId => $tagIds) {
            $productTagIdsIndexedByMapIds[$productId] = $this->tagMapManager->findByTagShopIdsIndexedByTagMapIds($tagIds);
        }

        return $productTagIdsIndexedByMapIds;
    }

    /**
     * @param array $tags
     * @return array
     */
    public function getTagsSlug(array $tags): array
    {
        $tagsSlug = [];

        foreach ($tags as $tag) {
            $tagsSlug[] = TagFactory::TAG_PREFIX . $tag['TagID'];
        }

        return $tagsSlug;
    }

    public function deleteEmptyTags(): void
    {
        $tagShopIdsIndexedByTagShopId = $this->tagManager->findUnusedTagShopIds();
        
        if (!$tagShopIdsIndexedByTagShopId) {
            return;
        }

        $emptyTagsExistInShop = $this->tagManager->findByTagShopIdsIndexedByTagId(\array_values($tagShopIdsIndexedByTagShopId));

        $batches = \array_chunk(\array_keys($tagShopIdsIndexedByTagShopId), $this->systemService->getBatchValue(), true);

        foreach ($batches as $batchToProcess) {
            $tagShopIdToDelete = [];
            $tagShopIdToDeleteMap = [];

            foreach ($batchToProcess as $tagShopId) {
                if (\array_key_exists($tagShopId, $emptyTagsExistInShop)) {
                    $tagShopIdToDelete[$tagShopId] = $tagShopId;
                }

                $tagShopIdToDeleteMap[$tagShopId] = $tagShopId;
            }

            try {
                $this->tagManager->deleteCollection($tagShopIdToDelete);
                $this->tagMapManager->deleteByIds($tagShopIdToDeleteMap);
            } catch (WooCommerceApiExceptionInterface $exception) {
                $this->logger->error('Empty Tags Delete - Exception Message: '.$exception->getMessage());
            }
        }
    }
}