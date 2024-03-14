<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCCategory;
use WcMipConnector\Enum\WooCommerceErrorCodes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Factory\CategoryFactory;
use WcMipConnector\Factory\CategoryLangFactory;
use WcMipConnector\Factory\CategoryMapFactory;
use WcMipConnector\Helper\SlugHelper;
use WcMipConnector\Manager\CategoryManager;
use WcMipConnector\Manager\CategoryMapManager;
use WcMipConnector\Manager\ImportProcessCategoryManager;

class CategoryService
{
    /** @var CategoryFactory  */
    protected $categoryFactory;
    /** @var CategoryMapFactory  */
    protected $categoryMapFactory;
    /** @var CategoryMapManager  */
    protected $categoryMapManager;
    /** @var CategoryManager  */
    protected $categoryManager;
    /** @var ImportProcessCategoryService  */
    protected $importProcessService;
    /** @var LoggerService  */
    protected $logger;
    /** @var ImportProcessCategoryManager  */
    protected $importProcessManager;
    /** @var SystemService */
    protected $systemService;
    /** @var CategoryLangFactory */
    private $categoryLangFactory;

    public function __construct()
    {
        $this->categoryFactory = new CategoryFactory();
        $this->categoryLangFactory = new CategoryLangFactory();
        $this->categoryMapFactory = new CategoryMapFactory();
        $this->categoryMapManager = new CategoryMapManager();
        $this->categoryManager = new CategoryManager();
        $this->importProcessService = new ImportProcessCategoryService();
        $this->importProcessManager = new ImportProcessCategoryManager();
        $loggerService = new LoggerService();
        $this->logger = $loggerService->getInstance();
        $this->systemService = new SystemService();
    }

    /**
     * @param array $categoriesIndexedByCategoryId
     * @param string $languageIsoCode
     * @param int|null $fileId
     * @return array
     * @throws \Exception
     */
    public function processByBatch(array $categoriesIndexedByCategoryId, string $languageIsoCode, ?int $fileId = null): array
    {
        if (empty($categoriesIndexedByCategoryId)) {
            return [];
        }

        $categoriesShopIndexedByCategoryId = [];
        $categoriesCreated = [[]];
        $categoriesUpdated = [[]];
        $categoriesIgnored = [[]];
        $mappedCategoriesShopIdsIndexedByCategoryId = [];
        $categoriesProcessedArray = [[]];
        $categoryOverrideTitleIndexedByCategoryId = [];
        $categoriesProcessed = [];

        $batches = \array_chunk($categoriesIndexedByCategoryId, $this->systemService->getBatchValue(), true);

        foreach ($batches as $categoriesToProcessIndexedByCategoryId) {
            $categoryIdsToUpdateIndexedByCategoryMapId = [];
            $categoriesToSaveIndexedByCategoryMapId = [];
            $categoriesToUpdateIndexedByCategoryMapId = [];
            $categoriesIdsIndex = [];
            $imagePostIndexedByCategoryShopId = [];
            $categoryImageIdsToDelete = [];
            $categorySlugShopIdsIndexedByCategoryMapId = [];

            foreach ($categoriesToProcessIndexedByCategoryId as $categoryToProcess) {
                if ($categoryToProcess['CategoryParentID'] !== null) {
                    $categoriesIdsIndex[$categoryToProcess['CategoryParentID']] = true;
                }

                $categoriesIdsIndex[$categoryToProcess['CategoryID']] = true;
            }

            $categoriesShopIdsIndexedByCategoryMapId = $this->categoryMapManager->findAllIndexedByCategoryMapId(\array_keys($categoriesIdsIndex));
            $categoriesVersionsIndexedById = $this->categoryMapManager->findVersionIndexedByCategoryMapId(\array_keys($categoriesShopIdsIndexedByCategoryMapId));
            $categoriesShopIdIndexedBySlug = $this->categoryManager->findCategoriesShopIdIndexedBySlug(\array_values($this->getCategoriesSlug($categoriesToProcessIndexedByCategoryId)));

            if (!empty($categoriesShopIdIndexedBySlug)) {
                $categorySlugShopIdsIndexedByCategoryMapId = $this->categoryMapManager->findCategoryIdIndexedByCategoryShop(\array_values($categoriesShopIdIndexedBySlug));
            }

            if ($fileId) {
                $categoriesProcessed = $this->importProcessService->getProcessedCategories(\array_keys($categoriesShopIdsIndexedByCategoryMapId), $fileId);
            }

            if ($categoriesShopIdsIndexedByCategoryMapId) {
                $categoriesShopIndexedByCategoryId = $this->categoryManager->findByCategoryShopIdsIndexedByCategoryId(\array_values($categoriesShopIdsIndexedByCategoryMapId));
            }

            if ($categoriesShopIndexedByCategoryId) {
                foreach ($categoriesShopIdsIndexedByCategoryMapId as $categoryParentMapped => $categoryMapped) {
                    if (\array_key_exists($categoryMapped, $categoriesShopIndexedByCategoryId)) {
                        $mappedCategoriesShopIdsIndexedByCategoryId[$categoryParentMapped] = $categoryMapped;
                    }
                }

                $categoryImageUrlIndexedByCategoryShopId = $this->categoryManager->findCategoryImagePostIdIndexedByCategoryShopId(\array_values($mappedCategoriesShopIdsIndexedByCategoryId));

                if (isset($categoryImageUrlIndexedByCategoryShopId)) {
                    $imagePostIndexedByCategoryShopId = $this->getCategoryImagePostIndexedByCategoryShopId($categoryImageUrlIndexedByCategoryShopId);
                }
            }

            $categoryFactorySave = [];
            $categoryFactoryToUpdate = [];
            $categoryFactoryDelete = [];
            $categoriesToDeleteIndexedByShopId = [];

            foreach ($categoriesToProcessIndexedByCategoryId as $categoryId => $categoryData) {
                if ($this->isProcessed($categoryId, $categoriesProcessed, $mappedCategoriesShopIdsIndexedByCategoryId)) {
                    $categoriesProcessedArray[][] = ['shopId' => (int)$mappedCategoriesShopIdsIndexedByCategoryId[$categoryId], 'id' => (int)$categoryId];
                    $this->logger->info('CategoryID: '.$categoryId.' in the FileID: '.$fileId.' has been already processed');

                    continue;
                }

                $categoryParentId = 0;
                $categoryMapId = $categoryData['CategoryID'];

                if ($this->categoryToDisableAndNotExists($categoryData, $categoryMapId, $mappedCategoriesShopIdsIndexedByCategoryId)) {
                    continue;
                }

                if ($this->categoryDisabledToDelete($categoryData, $categoryMapId, $mappedCategoriesShopIdsIndexedByCategoryId)) {
                    $categoryFactoryDelete[] = (int)$mappedCategoriesShopIdsIndexedByCategoryId[$categoryData['CategoryID']];
                    $categoriesToDeleteIndexedByShopId[(int)$mappedCategoriesShopIdsIndexedByCategoryId[$categoryData['CategoryID']]] = (int)$categoryData['CategoryID'];

                    continue;
                }

                if (\array_key_exists($categoryMapId, $categoriesShopIdsIndexedByCategoryMapId)) {
                    if (\array_key_exists($categoryMapId, $mappedCategoriesShopIdsIndexedByCategoryId)) {
                        $imageCategoryPostExists = \array_key_exists($mappedCategoriesShopIdsIndexedByCategoryId[$categoryMapId], $imagePostIndexedByCategoryShopId);

                        $version = \json_decode($categoriesVersionsIndexedById[$categoryData['CategoryID']], true);
                        $version = \is_array($version) ? $version : [];

                        if ($this->skipCategoryProcess($version, $categoryData)) {
                            $ignoredCategories[] = ['shopId' => (int)$mappedCategoriesShopIdsIndexedByCategoryId[$categoryData['CategoryID']], 'id' => (int)$categoryData['CategoryID']];
                            $categoriesProcessedArray[] = $ignoredCategories;
                            $categoriesIgnored[] = $ignoredCategories;

                            continue;
                        }

                        if ($this->isOverrideCategoryTree($categoryData)) {
                            $categoryParentId = $this->getCategoryParentId($categoryData, $mappedCategoriesShopIdsIndexedByCategoryId);
                        }

                        $categoryMapData = $this->categoryFactory->create($categoryData, $categoryData['OverrideCategoryTree'], $languageIsoCode, $categoryParentId, $imageCategoryPostExists);

                        foreach ($categoryData['CategoryLangs'] as $key => $categoryLang) {
                            if ($this->skipCategoryLangProcess($categoryLang, $version)) {
                                $categoryData['CategoryLangs'][$key]['Version'] = $version[$categoryLang['IsoCode']];
                                unset($categoryMapData->name, $categoryMapData->slug);

                                continue;
                            }

                            if (\array_key_exists($categoryLang['IsoCode'], $version)) {
                                $categoryOverrideTitleIndexedByCategoryId[$categoryMapId] = $categoryLang['OverrideTitle'];
                            }
                        }

                        if ($this->isCategoryOverrideTitle($categoryMapId, $categoryOverrideTitleIndexedByCategoryId)) {
                            unset($categoryMapData->name, $categoryMapData->slug);
                        }

                        $categoryMapData->id = $mappedCategoriesShopIdsIndexedByCategoryId[$categoryId];
                        $categoryIdsToUpdateIndexedByCategoryMapId[$categoryMapId] = true;
                        $categoryFactoryToUpdate[] = $categoryMapData;
                        $categoriesToUpdateIndexedByCategoryMapId[$categoryMapId] = $categoryMapData;

                        if ($this->isOverrideCategoryTree($categoryData)) {
                            $categoryImageIdsToDelete[$categoryMapId] = $categoryMapData->id;
                        }

                        continue;
                    }

                    if ($this->isOverrideCategoryTree($categoryData)) {
                        $categoryParentId = $this->getCategoryParentId($categoryData, $mappedCategoriesShopIdsIndexedByCategoryId);
                    }

                    $categoryMapData = $this->categoryFactory->create($categoryData, $categoryData['OverrideCategoryTree'], $languageIsoCode, $categoryParentId);


                    if (
                        \array_key_exists($categoryMapData->slug, $categoriesShopIdIndexedBySlug)
                        && !\array_key_exists($categoriesShopIdIndexedBySlug[$categoryMapData->slug], $categorySlugShopIdsIndexedByCategoryMapId)
                    ) {
                        $categoryMapData->id = $categoriesShopIdIndexedBySlug[$categoryMapData->slug];
                        $categoryImageIdsToDelete[$categoryMapId] = $categoryMapData->id;
                        $categoryIdsToUpdateIndexedByCategoryMapId[$categoryMapId] = true;
                        $categoryFactoryToUpdate[] = $categoryMapData;
                        $categoriesToUpdateIndexedByCategoryMapId[$categoryMapId] = $categoryMapData;

                        continue;
                    }

                    $categoryParentId = $this->getCategoryParentId($categoryData, $mappedCategoriesShopIdsIndexedByCategoryId);
                    $categoryMapData = $this->categoryFactory->create($categoryData, $categoryData['OverrideCategoryTree'], $languageIsoCode, $categoryParentId);
                    $categoryIdsToUpdateIndexedByCategoryMapId[$categoryMapId] = true;
                    $categoryFactorySave[] = $categoryMapData;
                    $categoriesToSaveIndexedByCategoryMapId[$categoryMapId] = $categoryMapData;

                    continue;
                }

                if ($this->isOverrideCategoryTree($categoryData)) {
                    $categoryParentId = $this->getCategoryParentId($categoryData, $mappedCategoriesShopIdsIndexedByCategoryId);
                }

                $categoryMapData = $this->categoryFactory->create($categoryData,true, $languageIsoCode, $categoryParentId);

                if (
                    \array_key_exists($categoryMapData->slug, $categoriesShopIdIndexedBySlug)
                    && !\array_key_exists($categoriesShopIdIndexedBySlug[$categoryMapData->slug], $categorySlugShopIdsIndexedByCategoryMapId)
                ) {
                    $categoryMapData->id = $categoriesShopIdIndexedBySlug[$categoryMapData->slug];
                    $categoryImageIdsToDelete[$categoryMapId] = $categoryMapData->id;
                    $categoryIdsToUpdateIndexedByCategoryMapId[$categoryMapId] = false;
                    $categoryFactoryToUpdate[] = $categoryMapData;
                    $categoriesToUpdateIndexedByCategoryMapId[$categoryMapId] = $categoryMapData;

                    continue;
                }

                $categoryParentId = $this->getCategoryParentId($categoryData, $mappedCategoriesShopIdsIndexedByCategoryId);
                $categoryMapData = $this->categoryFactory->create($categoryData,true, $languageIsoCode, $categoryParentId);
                $categoryIdsToUpdateIndexedByCategoryMapId[$categoryMapId] = false;
                $categoryFactorySave[] = $categoryMapData;
                $categoriesToSaveIndexedByCategoryMapId[$categoryMapId] = $categoryMapData;
            }

            if (!$categoryFactorySave && !$categoryFactoryToUpdate && !$categoryFactoryDelete) {
                continue;
            }

            if (!empty($categoryImageIdsToDelete)) {
                $this->deleteCategoryImageData($categoryImageIdsToDelete);
            }

            try {
                $this->logger->debug('Started the API CATEGORY UPDATE process');
                $categoriesBatchResponse = $this->categoryManager->updateCollection($categoryFactoryToUpdate);

                if ($this->mustBeUpdateByBatch($categoriesBatchResponse)) {
                    $updatedCategories = $this->update(
                        $categoriesBatchResponse['update'],
                        $categoriesToUpdateIndexedByCategoryMapId,
                        $categoryIdsToUpdateIndexedByCategoryMapId,
                        $categoriesToProcessIndexedByCategoryId,
                        $languageIsoCode,
                        $fileId
                    );
                    $categoriesProcessedArray[] = $updatedCategories;
                    $categoriesUpdated[] = $updatedCategories;
                }
            } catch (WooCommerceApiExceptionInterface $exception) {
                $this->logger->error(__METHOD__.' Category Batch Update - Exception Message: '.$exception->getMessage());
            }

            $this->logger->debug('Finished the API CATEGORY UPDATE process');

            try {
                $this->logger->debug('Started the API CATEGORY CREATE process');
                $categoriesBatchResponse = $this->categoryManager->createCollection($categoryFactorySave);

                if ($this->mustBeCreateByBatch($categoriesBatchResponse)) {
                    $createdCategories = $this->save(
                        $categoriesBatchResponse['create'],
                        $categoriesToSaveIndexedByCategoryMapId,
                        $categoryIdsToUpdateIndexedByCategoryMapId,
                        $categoriesToProcessIndexedByCategoryId,
                        $languageIsoCode,
                        $fileId
                    );
                    $categoriesProcessedArray[] = $createdCategories;
                    $categoriesCreated[] = $createdCategories;
                }
            } catch (WooCommerceApiExceptionInterface $exception) {
                $this->logger->error(__METHOD__.' Category Batch Create - Exception Message: '.$exception->getMessage());
            }

            $this->logger->debug('Finished the API CATEGORY CREATE process');

            try {
                $categoriesBatchResponse = $this->categoryManager->deleteCollection($categoryFactoryDelete);

                if ($this->mustBeDeleteByBatch($categoriesBatchResponse)) {
                    $this->delete($categoriesBatchResponse['delete'], $categoriesToDeleteIndexedByShopId);
                }
            } catch (WooCommerceApiExceptionInterface $exception) {
                $this->logger->error(__METHOD__.' Category Batch Delete - Exception Message: '.$exception->getMessage());
            }
        }

        if ($categoriesUpdated) {
            $categoriesUpdated = \array_merge(...$categoriesUpdated);
        }

        if ($categoriesIgnored) {
            $categoriesIgnored = \array_merge(...$categoriesIgnored);
        }

        if ($categoriesCreated) {
            $categoriesCreated = \array_merge(...$categoriesCreated);
            $this->setParents($categoriesIndexedByCategoryId, $categoriesCreated, $categoriesUpdated, $categoriesIgnored);
        }

        $categoriesProcessed = \array_merge(...$categoriesProcessedArray);

        return \array_column($categoriesProcessed, 'shopId', 'id');
    }

    /**
     * @param array $categoriesToProcess
     * @param array|null $categoriesCreated
     * @param array|null $categoriesUpdated
     * @param array|null $categoriesIgnored
     */
    private function setParents(array $categoriesToProcess, array $categoriesCreated = null, array $categoriesUpdated = null, array $categoriesIgnored = null): void
    {
        $categoryToSetParent = [];
        $categoriesToUpdateParent = \array_merge($categoriesUpdated, $categoriesIgnored);
        $categoriesToUpdateParentIndexedById = \array_column($categoriesToUpdateParent,'shopId','id');
        $mappedCategories = \array_merge($categoriesCreated, $categoriesUpdated, $categoriesIgnored);
        $mappedCategoriesShopIdsIndexedByCategoryId = \array_column($mappedCategories, 'shopId', 'id');
        $categoriesCreatedIndexedById = \array_column($categoriesCreated, 'shopId', 'id');
        $parentShopIdsIndexedByShopId = $this->categoryManager->findParentsShopIdsIndexedByShopId(\array_values($mappedCategoriesShopIdsIndexedByCategoryId));

        foreach ($mappedCategoriesShopIdsIndexedByCategoryId as $mappedCategoryId => $mappedCategoryShopIdIndexedByCategoryId) {
            $categoryData = $categoriesToProcess[$mappedCategoryId];

            if (
                $this->createdCategoriesWithWrongParents(
                $categoryData,
                $categoriesCreatedIndexedById,
                $mappedCategoriesShopIdsIndexedByCategoryId,
                $parentShopIdsIndexedByShopId,
                $mappedCategoryShopIdIndexedByCategoryId
                )
            ) {
                $categoryToSetParent[] = [
                    'id' => $mappedCategoryShopIdIndexedByCategoryId,
                    'parent' => $mappedCategoriesShopIdsIndexedByCategoryId[$categoryData['CategoryParentID']]
                ];
            }

            if ($this->updateCategoriesParentWithOverrideCategoryTree($categoryData, $categoriesToUpdateParentIndexedById, $mappedCategoriesShopIdsIndexedByCategoryId)) {
                $categoryToSetParent[] = [
                    'id' => $mappedCategoryShopIdIndexedByCategoryId,
                    'parent' => $mappedCategoriesShopIdsIndexedByCategoryId[$categoryData['CategoryParentID']]
                ];
            }
        }

        $batches = \array_chunk($categoryToSetParent, $this->systemService->getBatchValue(), true);

        foreach ($batches as $categorySetParent) {
            try {
                $this->categoryManager->updateCollection($categorySetParent);
            } catch (WooCommerceApiExceptionInterface $exception) {
                $this->logger->error('Category SetParents - Exception Message: '.$exception->getMessage());
            }
        }
    }

    /**
     * @param array $categoriesBatchResponse
     * @param array $categoriesToDeleteIndexedByShopId
     */
    private function delete(array $categoriesBatchResponse, array $categoriesToDeleteIndexedByShopId): void
    {
        $categoryIds = [];
        foreach ($categoriesBatchResponse as $categoryDelete) {
            if (\array_key_exists($categoryDelete['id'], $categoriesToDeleteIndexedByShopId)) {
                $categoryIds[] = $categoriesToDeleteIndexedByShopId[$categoryDelete['id']];
            }
        }

        $this->categoryMapManager->deleteByCategoryIds($categoryIds);
        $this->importProcessManager->deleteByCategoryIds($categoryIds);
    }

    /**
     * @param array $categoriesBatchResponse
     * @param array $categoriesIndexedByCategoryMapId
     * @param array $categoryIdsToUpdateIndexedByCategoryMapId
     * @param array $categoriesIndexedByCategoryId
     * @param string $languageIsoCode
     * @param int|null $fileId
     * @return array
     * @throws \Exception
     */
    private function save(
        array $categoriesBatchResponse,
        array $categoriesIndexedByCategoryMapId,
        array $categoryIdsToUpdateIndexedByCategoryMapId,
        array $categoriesIndexedByCategoryId,
        string $languageIsoCode,
        ?int $fileId = null
    ): array {
        $categoryIds = [];
        $categoryMapIndex = 0;

        foreach ($categoriesIndexedByCategoryMapId as $categoryId => $categoryMapData) {
            $categoryBatchResponse = $categoriesBatchResponse[$categoryMapIndex];
            $categoryMapIndex++;
            $categorySlug = null;

            if (!$categoryBatchResponse) {
                continue;
            }

            if (
                \array_key_exists($categoryId, $categoriesIndexedByCategoryId)
                && \array_key_exists('CategoryLangs', $categoriesIndexedByCategoryId[$categoryId])
            ) {
                $categorySlug = $this->findCategorySlugByLanguageIsoCodes($categoriesIndexedByCategoryId[$categoryId]['CategoryLangs'], $languageIsoCode);
            }

            if ($categorySlug && $this->batchErrorTypeTermExists($categoryBatchResponse)) {
                $foundCategoryShopId = $this->categoryManager->findCategoryShopIdBySlug($categorySlug);

                if (!$foundCategoryShopId) {
                    $categorySlug = SlugHelper::sanitize($categorySlug);
                    $foundCategoryShopId = $this->categoryManager->findCategoryShopIdBySlug($categorySlug);
                }

                $categoryBatchResponse['id'] = $foundCategoryShopId;
                $this->logger->debug('Remapped CategoryID: '.$categoryId.' with batch response category ID '.$categoryBatchResponse['id'].' and slug '.$categorySlug);

                unset($categoryBatchResponse['error']);
            }

            if ($this->hasCategoryBatchError($categoryBatchResponse, $categoryId, $categoryMapData, $fileId)) {
                continue;
            }

            /**
             * Product category import process from submission with file ID
             */
            if (!$categoryBatchResponse['id'] && $fileId) {
                $this->importProcessService->setFailure($categoryId, $fileId);

                continue;
            }

            /**
             * Update categories process is processed from request without a submission file ID
             */
            if (!$categoryBatchResponse['id']) {
                $this->logger->error('CategoryID: '.$categoryId.' could not be remapped as there is an existing category and it could not be determined from be SLUG '.$categorySlug);

                continue;
            }

            $categoryToMap = $this->categoryMapFactory->create($categoriesIndexedByCategoryId[$categoryId], $categoryBatchResponse['id'], $languageIsoCode);

            if (
                \array_key_exists($categoryId, $categoryIdsToUpdateIndexedByCategoryMapId)
                && $categoryIdsToUpdateIndexedByCategoryMapId[$categoryId]
            ) {
                $this->categoryMapManager->update($categoryBatchResponse['id'], $categoryToMap);
            } else {
                $this->categoryMapManager->save($categoryToMap);
            }

            if ($fileId) {
                $this->importProcessService->setSuccess($categoryId, $fileId);
            }

            $categoryIds[] = ['shopId' => $categoryBatchResponse['id'], 'id' => $categoryId];
        }

        return $categoryIds;
    }

    /**
     * @param array $categoriesBatchResponse
     * @param array $categoriesIndexedByCategoryMapId
     * @param array $categoryIdsToUpdateIndexedByCategoryMapId
     * @param array $categoriesIndexedByCategoryId
     * @param string $languageIsoCode
     * @param int|null $fileId
     * @return array
     * @throws \Exception
     */
    private function update(
        array $categoriesBatchResponse,
        array $categoriesIndexedByCategoryMapId,
        array $categoryIdsToUpdateIndexedByCategoryMapId,
        array $categoriesIndexedByCategoryId,
        string $languageIsoCode,
        ?int $fileId = null
    ): array {
        $categoryIds = [];
        $categoryMapIndex = 0;

        foreach ($categoriesIndexedByCategoryMapId as $categoryId => $categoryMapData) {
            $categoryBatchResponse = $categoriesBatchResponse[$categoryMapIndex];
            $categoryMapIndex++;
            $categorySlug = null;
            $categoriesIds = [];

            if (!$categoryBatchResponse) {
                continue;
            }

            if (\array_key_exists($categoryId, $categoriesIndexedByCategoryId) && \array_key_exists('CategoryLangs', $categoriesIndexedByCategoryId[$categoryId])) {
                $categorySlug = $this->findCategorySlugByLanguageIsoCodes($categoriesIndexedByCategoryId[$categoryId]['CategoryLangs'], $languageIsoCode);
            }

            if ($categorySlug && $this->batchErrorTypeTermExists($categoryBatchResponse)) {
                $foundCategoryShopId = $this->categoryManager->findCategoryShopIdBySlug($categorySlug);

                if (!$foundCategoryShopId) {
                    $categorySlug = SlugHelper::sanitize($categorySlug);
                    $foundCategoryShopId = $this->categoryManager->findCategoryShopIdBySlug($categorySlug);
                }

                $categoryBatchResponse['id'] = $foundCategoryShopId;
                $this->logger->info('Remapped CategoryID: '.$categoryBatchResponse['id']);

                unset($categoryBatchResponse['error']);
            }

            if ($this->hasCategoryBatchError($categoryBatchResponse, $categoryId, $categoryMapData, $fileId)) {
                continue;
            }

            /**
             * Product category import process from submission with file ID
             */
            if (!$categoryBatchResponse['id'] && $fileId) {
                $this->importProcessService->setFailure($categoryId, $fileId);

                continue;
            }

            /**
             * Update categories process is processed from request without a submission file ID
             */
            if (!$categoryBatchResponse['id']) {
                $this->logger->error('CategoryID: '.$categoryId.' could not be remapped as there is an existing category and it could not be determined from be SLUG '.$categorySlug);

                continue;
            }

            if ($categorySlug) {
                $categoriesIds = $this->categoryManager->findCategoryIdsBySlug($categorySlug);
            }

            $this->deleteDuplicateCategories($categoriesIds, $categoryBatchResponse['id']);
            $categoryToMap = $this->categoryMapFactory->create($categoriesIndexedByCategoryId[$categoryId], $categoryBatchResponse['id'], $languageIsoCode);

            if (\array_key_exists($categoryId, $categoryIdsToUpdateIndexedByCategoryMapId) && $categoryIdsToUpdateIndexedByCategoryMapId[$categoryId]) {
                $this->categoryMapManager->update($categoryBatchResponse['id'], $categoryToMap);
            } else {
                $this->categoryMapManager->save($categoryToMap);
            }

            if ($fileId) {
                $this->importProcessService->setSuccess($categoryId, $fileId);
            }

            $categoryIds[] = ['shopId' => $categoryBatchResponse['id'], 'id' => $categoryId];
        }

        return $categoryIds;
    }

    /**
     * @param array $categoriesIds
     * @param int $categoryId
     */
    private function deleteDuplicateCategories(array $categoriesIds, int $categoryId): void
    {
        $batches = \array_chunk($categoriesIds, $this->systemService->getBatchValue(), true);

        foreach ($batches as $categoriesIdsByBatch) {
            $categoryFactoryDelete = [];

            foreach ($categoriesIdsByBatch as $categoryShopId => $categorySlug) {
                if ((int)$categoryShopId === $categoryId || !preg_match('~[0-9]+~', $categorySlug)) {
                    continue;
                }

                $categoryFactoryDelete[] = $categoryShopId;
            }

            if (!$categoryFactoryDelete) {
                continue;
            }

            try {
                $this->categoryManager->deleteCollection($categoryFactoryDelete);
                $this->logger->info('Deleting duplicated categories with Id: '.$categoryId);
            } catch (\Exception $exception) {
                $this->logger->error('Error deleting duplicated categories: '.$exception->getMessage());
            }
        }
    }

    /**
     * @param array $categories
     * @param array $languages
     * @throws \Exception
     */
    public function updateCategories(array $categories, array $languages): void
    {
        if (empty($languages)) {
            return;
        }

        $categoriesIndexedByCategoryId = [];

        if (empty($categories)) {
            return;
        }

        foreach ($categories['Categories'] as $category) {
            $categoriesIndexedByCategoryId[$category['CategoryID']] = $category;
        }

        $this->processByBatch($categoriesIndexedByCategoryId, \current($languages));
    }

    public function deleteEmptyCategories(): void
    {
        $emptyCategoryIndexedByCategoryShopId = $this->categoryManager->findEmptyCategories();

        if (!$emptyCategoryIndexedByCategoryShopId) {
            return;
        }

        $emptyCategoriesExistInShop = $this->categoryManager->findByCategoryShopIdsIndexedByCategoryId($emptyCategoryIndexedByCategoryShopId);

        $batches = \array_chunk($emptyCategoryIndexedByCategoryShopId, $this->systemService->getBatchValue(), true);

        foreach ($batches as $batchToProcess) {
            $categoryShopIdsToDelete = [];
            $categoryShopIdsToDeleteMap = [];

            foreach ($batchToProcess as $categoryShopId) {
                if (\array_key_exists($categoryShopId, $emptyCategoriesExistInShop)) {
                    $categoryShopIdsToDelete[$categoryShopId] = $categoryShopId;
                }

                $categoryShopIdsToDeleteMap[$categoryShopId] = $categoryShopId;
            }

            try {
                $this->categoryManager->deleteCollection($categoryShopIdsToDelete);
                $this->categoryMapManager->deleteByCategoryShopIds($categoryShopIdsToDeleteMap);
            } catch (WooCommerceApiExceptionInterface $exception) {
                $this->logger->error('Empty Categories Delete - Exception Message: '.$exception->getMessage());
            }
        }
    }

    /**
     * @param array $categoryImageIds
     */
    public function deleteCategoryImageData(array $categoryImageIds): void
    {
        if (empty($categoryImageIds)) {
            return;
        }

        $categoryImagePostIDIndexedByCategoryShopId = $this->categoryManager->findCategoryImagePostIdIndexedByCategoryShopId($categoryImageIds);

        if (empty($categoryImagePostIDIndexedByCategoryShopId)) {
            return;
        }

        $imageGuidUrlIndexedByPostId = $this->categoryManager->findUrlIndexedByPostId($categoryImagePostIDIndexedByCategoryShopId);

        if (empty($imageGuidUrlIndexedByPostId)) {
            return;
        }

        $this->deleteCategoryImageAttachment($imageGuidUrlIndexedByPostId);
    }

    /**
     * @param array $imageGuidUrlIndexedByPostId
     */
    public function deleteCategoryImageAttachment(array $imageGuidUrlIndexedByPostId): void
    {
        if (!$imageGuidUrlIndexedByPostId) {
            return;
        }

        foreach ($imageGuidUrlIndexedByPostId as $postId => $imageGuidUrl) {
            \wp_delete_post($postId, true);
        }
    }

    /**
     * @param string $categoryImageMonthDir
     * @param string $categoryImageFileName
     * @return bool
     */
    private function deleteImageByGlob(string $categoryImageMonthDir, string $categoryImageFileName): bool
    {
        if (!\is_callable('glob') || false !== \stripos(\ini_get('disable_functions'), 'glob')) {
            return false;
        }

        $fileDirName = $categoryImageMonthDir.'/*'.$categoryImageFileName.'*';
        $imagesToDelete = @glob($fileDirName);

        if (empty($imagesToDelete)) {
            return true;
        }

        $this->deleteFiles($imagesToDelete);

        return true;
    }

    /**
     * @param string $categoryImageMonthDir
     * @param string $categoryImageFileName
     */
    private function deleteImageByDirectoryIterator(string $categoryImageMonthDir, string $categoryImageFileName): void
    {
        $monthDirFiles =  new \DirectoryIterator($categoryImageMonthDir);
        $imagesToDelete = [];

        foreach ($monthDirFiles->current() as $fileDir) {
            if (\stripos($fileDir->getFilename(), $categoryImageFileName) === false) {
                continue;
            }

            $imagesToDelete[] = $fileDir->getPathname();
        }

        if (empty($imagesToDelete)) {
            return;
        }

        $this->deleteFiles($imagesToDelete);
    }

    /**
     * @param array $categoryData
     * @param int $categoryMapId
     * @param array $mappedCategoriesShopIdsIndexedByCategoryId
     * @return bool
     */
    public function categoryToDisableAndNotExists(array $categoryData, int $categoryMapId, array $mappedCategoriesShopIdsIndexedByCategoryId): bool
    {
        return \array_key_exists('Active', $categoryData) &&
            !$categoryData['Active'] && !\array_key_exists($categoryMapId, $mappedCategoriesShopIdsIndexedByCategoryId);
    }

    /**
     * @param array $categoryData
     * @param array $mappedCategoriesShopIdsIndexedByCategoryId
     * @return int
     */
    public function getCategoryParentId(array $categoryData, array $mappedCategoriesShopIdsIndexedByCategoryId): int
    {
        return $mappedCategoriesShopIdsIndexedByCategoryId[$categoryData['CategoryParentID']] ?? 0;
    }

    /**
     * @param array $categories
     * @return array
     */
    public function getCategoriesSlug(array $categories): array
    {
        $categorySlug = [];

        foreach ($categories as $category) {
            if (!\is_array($category['CategoryLangs'])) {
                $this->logger->error(__METHOD__.' Invalid category lang', $category);

                continue;
            }

            foreach ($category['CategoryLangs'] as $categoryLang) {
                $categorySlug[] = $categoryLang['CategoryURL'];
            }
        }

        return $categorySlug;
    }

    /**
     * @param array $categoryData
     * @param int $categoryMapId
     * @param array $mappedCategoriesShopIdsIndexedByCategoryId
     * @return bool
     */
    private function categoryDisabledToDelete(array $categoryData, int $categoryMapId, array $mappedCategoriesShopIdsIndexedByCategoryId): bool
    {
        return \array_key_exists('Active', $categoryData) &&
            !$categoryData['Active'] && \array_key_exists($categoryMapId, $mappedCategoriesShopIdsIndexedByCategoryId);
    }

    /**
     * @param array $version
     * @param array $categoryData
     * @return bool
     */
    private function skipCategoryProcess(array $version, array $categoryData): bool
    {
        return \array_key_exists('version', $version) && $categoryData['Version'] < $version['version'];
    }

    /**
     * @param int $categoryId
     * @param array $categoriesProcessed
     * @param array $mappedCategoriesShopIdsIndexedByCategoryId
     * @return bool
     */
    private function isProcessed(int $categoryId, array $categoriesProcessed, array $mappedCategoriesShopIdsIndexedByCategoryId): bool
    {
        return \array_key_exists($categoryId, $categoriesProcessed) && \array_key_exists($categoryId, $mappedCategoriesShopIdsIndexedByCategoryId);
    }

    /**
     * @param array $categoryData
     * @return bool
     */
    private function isOverrideCategoryTree(array $categoryData): bool
    {
        return \array_key_exists('OverrideCategoryTree', $categoryData) && $categoryData['OverrideCategoryTree'];
    }

    /**
     * @param array $categoryLang
     * @param array $version
     * @return bool
     */
    private function skipCategoryLangProcess(array $categoryLang, array $version): bool
    {
        $languageIsoCode = $categoryLang['IsoCode'];

        return \array_key_exists($languageIsoCode, $version) && $categoryLang['Version'] < $version[$languageIsoCode];
    }

    /**
     * @param int $categoryMapId
     * @param array $categoryOverrideTitleIndexedByCategoryId
     * @return bool
     */
    private function isCategoryOverrideTitle(int $categoryMapId, array $categoryOverrideTitleIndexedByCategoryId): bool
    {
        return \array_key_exists($categoryMapId, $categoryOverrideTitleIndexedByCategoryId) && !$categoryOverrideTitleIndexedByCategoryId[$categoryMapId];
    }

    /**
     * @param array $categoriesBatchResponse
     * @return bool
     */
    private function mustBeCreateByBatch(array $categoriesBatchResponse): bool
    {
        return \array_key_exists('create', $categoriesBatchResponse) && $categoriesBatchResponse['create'];
    }

    /**
     * @param array $categoriesBatchResponse
     * @return bool
     */
    private function mustBeUpdateByBatch(array $categoriesBatchResponse): bool
    {
        return \array_key_exists('update', $categoriesBatchResponse) && $categoriesBatchResponse['update'];
    }

    /**
     * @param array $categoriesBatchResponse
     * @return bool
     */
    private function mustBeDeleteByBatch(array $categoriesBatchResponse): bool
    {
        return \array_key_exists('delete', $categoriesBatchResponse) && $categoriesBatchResponse['delete'];
    }

    /**
     * @param array $categoryBatchResponse
     * @return bool
     */
    private function batchErrorTypeTermExists(array $categoryBatchResponse): bool
    {
        return \array_key_exists('error', $categoryBatchResponse) && ($categoryBatchResponse['error']['code'] === WooCommerceErrorCodes::TERM_EXISTS || $categoryBatchResponse['error']['code'] === WooCommerceErrorCodes::DUPLICATE_TERM_SLUG);
    }

    /**
     * @param array $categoryBatchResponse
     * @param int $categoryId
     * @param int|null $fileId
     * @param WCCategory $categoryMapData
     * @return bool
     * @throws \Exception
     */
    private function hasCategoryBatchError(array $categoryBatchResponse, int $categoryId, WCCategory $categoryMapData, ?int $fileId = null): bool
    {
        if (!((\array_key_exists('error', $categoryBatchResponse) && $categoryBatchResponse['error']) || $categoryBatchResponse['id'] === 0)) {
            return false;
        }

        if ($fileId) {
            $this->importProcessService->setFailure($categoryId, $fileId);
        }
        $errorCode = $categoryBatchResponse['error']['code'];
        $message = 'CategoryID: '.$categoryId.' in the FileID: '.$fileId.' has been not processed right. Batch response: '. $errorCode;

        if (isset($categoryBatchResponse['error']['message']) && $categoryBatchResponse['error']['message']) {
            $internalErrorCode = ResponseService::getInternalErrorCodeFromResponseError($categoryBatchResponse['error']['message']);
            $message .= ' - InternalCode: '.$internalErrorCode.' - ErrorMessage: '.$categoryBatchResponse['error']['message'];
        }

        if ($errorCode === WooCommerceErrorCodes::INVALID_REMOTE_URL) {
            $message .= ' '.$categoryMapData->image['src'];
        }

        $this->logger->error($message);

        return true;
    }

    /**
     * @param array $categoryData
     * @param array $categoriesCreatedIndexedById
     * @param array $mappedCategoriesShopIdsIndexedByCategoryId
     * @param array $parentShopIdsIndexedByShopId
     * @param int $mappedCategoryShopIdIndexedByCategoryId
     * @return bool
     */
    private function createdCategoriesWithWrongParents(array $categoryData, array $categoriesCreatedIndexedById, array $mappedCategoriesShopIdsIndexedByCategoryId, array $parentShopIdsIndexedByShopId, int $mappedCategoryShopIdIndexedByCategoryId): bool
    {
        return \array_key_exists('CategoryID', $categoryData) &&
            \array_key_exists('CategoryParentID', $categoryData) &&
            \array_key_exists($categoryData['CategoryID'], $categoriesCreatedIndexedById) &&
            \array_key_exists($categoryData['CategoryParentID'], $mappedCategoriesShopIdsIndexedByCategoryId) &&
            $this->hasWrongParent($parentShopIdsIndexedByShopId, $mappedCategoriesShopIdsIndexedByCategoryId, $mappedCategoryShopIdIndexedByCategoryId, $categoryData['CategoryParentID']);
    }

    /**
     * @param array $parentShopIdsIndexedByShopId
     * @param array $mappedCategoriesShopIdsIndexedByCategoryId
     * @param int $mappedCategoryShopIdIndexedByCategoryId
     * @param int $categoryId
     * @return bool
     */
    private function hasWrongParent(array $parentShopIdsIndexedByShopId, array $mappedCategoriesShopIdsIndexedByCategoryId, int $mappedCategoryShopIdIndexedByCategoryId, int $categoryId): bool
    {
        return (int)$parentShopIdsIndexedByShopId[$mappedCategoryShopIdIndexedByCategoryId] !==
            $mappedCategoriesShopIdsIndexedByCategoryId[$categoryId];
    }

    /**
     * @param $categoryData
     * @param $categoriesToUpdateParentIndexedById
     * @param $mappedCategoriesShopIdsIndexedByCategoryId
     * @return bool
     */
    private function updateCategoriesParentWithOverrideCategoryTree($categoryData, $categoriesToUpdateParentIndexedById, $mappedCategoriesShopIdsIndexedByCategoryId): bool
    {
        return $this->isOverrideCategoryTree($categoryData) &&
            \array_key_exists($categoryData['CategoryID'], $categoriesToUpdateParentIndexedById) &&
            \array_key_exists('CategoryParentID', $categoryData) &&
            \array_key_exists($categoryData['CategoryParentID'], $mappedCategoriesShopIdsIndexedByCategoryId);
    }

    /**
     * @param array $categoryLangs
     * @param string $languageIsoCode
     * @return string|null
     */
    private function findCategorySlugByLanguageIsoCodes(array $categoryLangs, string $languageIsoCode): ?string
    {
        return $this->categoryLangFactory->create($categoryLangs, $languageIsoCode)->slug;
    }

    /**
     * @param array $categoryImageUrlIndexedByCategoryShopId
     * @return array
     */
    private function getCategoryImagePostIndexedByCategoryShopId(array $categoryImageUrlIndexedByCategoryShopId): array
    {
        if (empty($categoryImageUrlIndexedByCategoryShopId)) {
            return [];
        }

        $imagePostIndexedByCategoryShopId = [];

        $imagePostIndexedByPostId = $this->categoryManager->findImagePostIndexedByPostId($categoryImageUrlIndexedByCategoryShopId);

        if (empty($imagePostIndexedByPostId)) {
            return [];
        }

        foreach ($categoryImageUrlIndexedByCategoryShopId as $categoryShopId => $postMetaId) {
            if (!\array_key_exists($postMetaId, $imagePostIndexedByPostId)) {
                continue;
            }

            $imagePostIndexedByCategoryShopId[$categoryShopId] = $imagePostIndexedByPostId[$postMetaId];
        }

        return $imagePostIndexedByCategoryShopId;
    }

    /**
     * @param array $imagesToDelete
     * @return void
     */
    public function deleteFiles(array $imagesToDelete): void
    {
        if (empty($imagesToDelete)) {
            return;
        }

        foreach ($imagesToDelete as $imageToDelete) {
            @unlink($imageToDelete);
        }
    }
}