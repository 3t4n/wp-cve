<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCProduct;
use WcMipConnector\Enum\WooCommerceErrorCodes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Factory\ProductFactory;
use WcMipConnector\Factory\ProductMapFactory;
use WcMipConnector\Factory\VariationFactory;
use WcMipConnector\Manager\BrandManager;
use WcMipConnector\Manager\CategoryMapManager;
use WcMipConnector\Manager\ImportProcessProductManager;
use WcMipConnector\Manager\ProductManager;
use WcMipConnector\Manager\ProductMapManager;
use WcMipConnector\Manager\TaxesManager;
use WcMipConnector\Repository\ReferenceDataRepository;

class ProductService
{
    /** @var ProductManager */
    protected $productManager;
    /** @var ProductMapManager */
    protected $productMapManager;
    /** @var ProductFactory */
    protected $productFactory;
    /** @var ProductMapFactory */
    protected $productMapFactory;
    /** @var VariationFactory */
    protected $variationFactory;
    /** @var VariationService */
    protected $variationService;
    /** @var ImportProcessProductService */
    protected $importProcessService;
    /** @var ImportProcessProductManager */
    protected $importProcessManager;
    /** @var ImportProcessVariationService */
    protected $importProcessVariationService;
    /** @var BrandManager */
    protected $brandManager;
    /** @var LoggerService */
    protected $logger;
    /** @var array */
    protected $languages;
    /** @var SystemService */
    protected $systemService;
    /** @var TagService */
    private $tagService;
    /** @var CategoryMapManager */
    private $categoryMapManager;
    /** @var TaxesManager */
    private $taxManager;

    /** @var ReferenceDataRepository */
    private $referenceDataRepository;

    public function __construct()
    {
        $this->productManager = new ProductManager();
        $this->productMapManager = new ProductMapManager();
        $this->productFactory = new ProductFactory();
        $this->productMapFactory = new ProductMapFactory();
        $this->variationFactory = new VariationFactory();
        $this->variationService = new VariationService();
        $this->tagService = new TagService();
        $this->importProcessService = new ImportProcessProductService();
        $this->importProcessManager = new ImportProcessProductManager();
        $this->importProcessVariationService = new ImportProcessVariationService();
        $this->brandManager = new BrandManager();
        $this->logger = new LoggerService;
        $this->systemService = new SystemService();
        $this->categoryMapManager = new CategoryMapManager();
        $this->taxManager = new TaxesManager();
        $this->referenceDataRepository = new ReferenceDataRepository();
    }

    /**
     * @param array $products
     * @param array $tags
     * @param array $categories
     * @param array $brands
     * @param array $brandsPluginIds
     * @param array $attributes
     * @param int $fileId
     * @param string $languageIsoCode
     * @throws \Exception
     */
    public function process(array $products, array $tags, array $categories, array $brands, array $brandsPluginIds, array $attributes, int $fileId, string $languageIsoCode): void
    {
        if (empty($products)) {
            return;
        }

        /** @var WCProduct[] $productIndexedByProductMapId */
        $productIndexedByProductMapId = [];
        $variationsToProcessIndexedByParent = [];
        $productToProcess = [];
        $variationToProcess = [];

        $taxes = $this->taxManager->getTaxes();

        foreach ($products as $product) {
            $overridePrice = true;

            foreach ($product['Product'] as $productId => $productData) {
                $productAttributes = [];
                $tagIds = $this->getTagIdsByProductTags($productData['Tags'], $tags);
                $categoryIds = $this->getCategoryIdsByProductCategories($productData['Categories'], $categories);
                $brandIds = $this->getBrandIdsByProductBrands($productData['Brand'], $brands);
                $brandPluginIds = $this->getBrandIdsByProductBrands($productData['Brand'], $brandsPluginIds);

                if (\array_key_exists('OverridePrice', $productData)) {
                    $overridePrice = $productData['OverridePrice'];
                }

                if ($productData['Variations']) {
                    $productAttributes = $this->getAttributesByVariations($productData['Variations'], $attributes);
                }

                if (!$productAttributes && $productData['Variations']) {
                    $this->logger->error('Product '.$productId.' ignored - Wrong attributes');
                    $this->importProcessService->setFailure($productId, $fileId);
                    continue;
                }

                $productToProcess[$productId] = $productData;
                $productFactory = $this->productFactory->create($productData, $tagIds, $categoryIds, $brandIds, $brandPluginIds, $productAttributes, $languageIsoCode, $taxes);

                $productIndexedByProductMapId[$productData['ProductID']] = $productFactory;
            }

            if (!$product['Variation']) {
                continue;
            }

            foreach ($product['Variation'] as $productParentId => $variationData) {
                $variationsIndexedByVariationMapId = [];

                foreach ($variationData as $variationId => $variationChild) {
                    $attributesIds = $this->getAttributesIdsByVariations($variationChild['VariationAttributes'], $attributes);

                    if (!$attributesIds) {
                        $this->logger->error('Variation '.$variationId.' ignored - Wrong attributes');
                        $this->importProcessVariationService->setFailure($variationId, $fileId);

                        continue;
                    }

                    $variationFactory = $this->variationFactory->create($variationChild, $attributesIds);

                    $variationToProcess[$productParentId][$variationChild['VariationID']] = $variationChild;
                    $variationToProcess[$productParentId][$variationChild['VariationID']]['OverridePrice'] = $overridePrice;
                    $variationsIndexedByVariationMapId[$variationChild['VariationID']] = $variationFactory;
                    $variationsToProcessIndexedByParent[$productParentId] = $variationsIndexedByVariationMapId;
                }
            }
        }

        $this->logger->debug('Started the product process');
        $productParentProcessed = $this->processProductByBatch($productIndexedByProductMapId, $productToProcess, $fileId, $languageIsoCode);
        $this->logger->debug('Finished the product process');

        foreach ($variationsToProcessIndexedByParent as $variationProductParentId => $variationsToProcess) {
            $productParentProcessedId = null;

            if (\array_key_exists($variationProductParentId, $productParentProcessed)) {
                $productParentProcessedId = $productParentProcessed[$variationProductParentId];
            }

            $this->logger->debug('Started the product variation process');
            $this->variationService->processVariationByBatch($variationsToProcess, $productParentProcessedId, $variationToProcess[$variationProductParentId], $fileId, $variationProductParentId);
            $this->logger->debug('Finished the product variation process');
        }
    }

    /**
     * @param array $productTags
     * @param array $tags
     *
     * @return array
     */
    private function getTagIdsByProductTags(array $productTags, array $tags): array
    {
        $tagIds = [];

        foreach ($productTags as $productTag) {
            if (\array_key_exists($productTag['TagID'], $tags)) {
                $tagIds[] = ['id' => $tags[$productTag['TagID']]];
            }
        }

        return $tagIds;
    }

    /**
     * @param array $productCategories
     * @param array $categories
     *
     * @return array
     */
    private function getCategoryIdsByProductCategories(array $productCategories, array $categories): array
    {
        $categoryIds = [];

        foreach ($productCategories as $productCategory) {
            if (\array_key_exists($productCategory['CategoryID'], $categories)) {
                $categoryIds[] = ['id' => $categories[$productCategory['CategoryID']]];
            }
        }

        return $categoryIds;
    }

    /**
     * @param array $productBrand
     * @param array $brandsData
     * @return array
     */
    private function getBrandIdsByProductBrands(array $productBrand, array $brandsData): array
    {
        if (\array_key_exists($productBrand['BrandID'], $brandsData)) {
            return $brandsData[$productBrand['BrandID']];
        }

        return [];
    }

    /**
     * @param array $variations
     * @param array $attributes
     *
     * @return array
     */
    private function getAttributesIdsByVariations(array $variations, array $attributes): array
    {
        $attributeIds = [];

        foreach ($variations as $variation) {
            $attributeGroupId = $variation['AttributeGroup']['AttributeGroupID'];
            $attributeId = $variation['Attribute']['AttributeID'];

            if (\array_key_exists($attributeGroupId, $attributes) && array_key_exists($attributeId, $attributes[$attributeGroupId])) {
                $attributeIds[] = $attributes[$attributeGroupId][$attributeId];
                continue;
            }

            return [];
        }

        return $attributeIds;
    }

    /**
     * @param array $variations
     * @param array $attributesIndexedByAttributeGroupId
     *
     * @return array
     */
    private function getAttributesByVariations(array $variations, array $attributesIndexedByAttributeGroupId): array
    {
        $attributes  = [];
        $attributeGroupOptions = [];
        $attributeIdIndexedByAttributeGroupId = [];

        foreach ($variations as $variation) {
            foreach ($variation['VariationAttributes'] as $variationAttributes) {
                $attributeGroupId = $variationAttributes['AttributeGroup']['AttributeGroupID'];
                $attributeId = $variationAttributes['Attribute']['AttributeID'];

                if (\array_key_exists($attributeGroupId, $attributesIndexedByAttributeGroupId) && array_key_exists($attributeId, $attributesIndexedByAttributeGroupId[$attributeGroupId])) {
                    $attributeGroupOptions[$attributeGroupId][] = $attributesIndexedByAttributeGroupId[$attributeGroupId][$attributeId]['name'];
                    $attributeIdIndexedByAttributeGroupId[$attributeGroupId] = $attributeId;
                    continue;
                }

                return [];
            }
        }

        foreach ($attributeGroupOptions as $attributeGroupId => $optionNames) {
            $attributes[] = [
                'id' => $attributesIndexedByAttributeGroupId[$attributeGroupId][$attributeIdIndexedByAttributeGroupId[$attributeGroupId]]['id'],
                'visible' => true,
                'variation' => true,
                'options' => $optionNames,
            ];
        }

        return $attributes;
    }

    /**
     * @param array $productsIndexedByProductMapId
     * @param array $products
     * @param int $fileId
     * @param string $languageIsoCode
     * @return array
     * @throws \Exception
     */
    public function processProductByBatch(array $productsIndexedByProductMapId, array $products, int $fileId, string $languageIsoCode): array
    {
        if (empty($productsIndexedByProductMapId)) {
            return [];
        }

        $processedArray = [[]];

        if (!$productsIndexedByProductMapId) {
            $productsIndexedByProductMapId = [];
        }

        $batches = array_chunk($productsIndexedByProductMapId, $this->systemService->getBatchValue(), true);

        foreach ($batches as $batchToProcess) {
            $productFactorySave = [];
            $productFactoryToUpdate = [];
            $productFactoryToDelete = [];
            $productIdsToUpdateIndexedByProductMapId = [];
            /** @var WCProduct[] $productToSaveIndexedByProductMapId */
            $productToSaveIndexedByProductMapId = [];
            $productToUpdateIndexedByProductMapId = [];
            $productsTopImageVersion = [];
            $productShopIds = [];

            $productMapIdsIndexedByProductMapId = $this->productMapManager->findByProductMapIdsIndexedByProductMapId(\array_keys($batchToProcess));
            $productTagsIndexedByProductId = $this->tagService->getProductTagsIndexedByProductIds(\array_values($productMapIdsIndexedByProductMapId));
            $productTagIdsIndexedByMapIds = $this->tagService->findByTagShopIdsIndexedByTagMapIds($productTagsIndexedByProductId);
            $productVersionsIndexedById = $this->productMapManager->findVersionsIndexedByProductMapId(\array_keys($productMapIdsIndexedByProductMapId));
            $processedProducts = $this->importProcessManager->getProcessedProducts(\array_keys($productMapIdsIndexedByProductMapId), $fileId);

            $productSkuIndexedByProductIds = \array_column($products, 'ProductID', 'SKU');
            $productsNotMappedIndexedBySku = $this->productManager->findProductShopIdIndexedBySku(\array_keys($productSkuIndexedByProductIds));
            $productParentIdsIndexedSku = $this->productManager->getProductShopIdByReferencesIndexedByReference(\array_keys($productSkuIndexedByProductIds));

            if (!empty($productMapIdsIndexedByProductMapId)) {
                $productShopIds = $this->productManager->findByProductShopIdsIndexedByProductId(\array_values($productMapIdsIndexedByProductMapId));
            }

            if ($productShopIds) {
                $productsTopImageVersion = $this->productMapManager->findImageVersionIndexedByProductMapId(\array_values($productShopIds));
            }

            /** @var WCProduct $productMapData */
            foreach ($batchToProcess as $productMapId => $productMapData) {
                if (\array_key_exists($productMapId, $processedProducts) && array_key_exists($productMapId, $productMapIdsIndexedByProductMapId)) {
                    $processedArray[][] = ['shopId' => (int)$productMapIdsIndexedByProductMapId[$productMapId], 'id' => (int)$productMapId];
                    $this->logger->getInstance()->info('ProductID: '.$productMapId.' in the FileID: '.$fileId.' has been already processed');

                    continue;
                }

                $overridePrice = \array_key_exists('OverridePrice', $products[$productMapId]) ? $products[$productMapId]['OverridePrice'] : true;
                $overrideImages = \array_key_exists('OverrideImages', $products[$productMapId]) ? $products[$productMapId]['OverrideImages'] : true;

                $categories = $products[$productMapId]['Categories'];
                $overrideCategoryTree = $this->getOverrideCategoryTree($categories);

                if (\array_key_exists($productMapId, $productMapIdsIndexedByProductMapId)) {
                    if (\array_key_exists($productMapData->sku, $productsNotMappedIndexedBySku)) {
                        $version = \json_decode($productVersionsIndexedById[$productMapId], true);

                        if (\array_key_exists('version', $version) && $products[$productMapId]['Version'] < $version['version']) {
                            $processedArray[][] = ['shopId' => (int)$productsNotMappedIndexedBySku[$productMapData->sku], 'id' => (int)$productMapId];
                            continue;
                        }

                        $productMapData->id = (int)$productsNotMappedIndexedBySku[$productMapData->sku];

                        if (!$overrideCategoryTree) {
                            $productMapData->categories = $this->addUnmappedCategoriesAssociatedToProduct($productMapData->id, $productMapData->categories);
                        }

                        foreach ($products[$productMapId]['ProductLangs'] as $key => $productLang) {
                            $skipUpdateByProductLangVersion = \array_key_exists($productLang['IsoCode'], $version)
                                && $version[$productLang['IsoCode']] > $productLang['Version'];


                            if (!$productLang['OverrideTitle'] || $skipUpdateByProductLangVersion) {
                                unset($productMapData->name);
                            }

                            if (!$productLang['OverrideDescription'] || $skipUpdateByProductLangVersion) {
                                unset($productMapData->description);
                            }

                            if ($skipUpdateByProductLangVersion) {
                                $products[$productMapId]['ProductLangs'][$key]['Version'] = $version[$productLang['IsoCode']];
                            }
                        }

                        if (\array_key_exists($productMapData->id, $productTagsIndexedByProductId) && $productTagsIndexedByProductId[$productMapData->id]
                            && array_key_exists($productMapData->id, $productTagIdsIndexedByMapIds) && $productTagIdsIndexedByMapIds[$productMapData->id]) {
                            $customerTags = array_diff($productTagsIndexedByProductId[$productMapData->id], $productTagIdsIndexedByMapIds[$productMapData->id]);
                            $this->productFactory->setTags($customerTags, $productMapData);
                        }

                        $productMapData = $this->deleteProductImageData(
                            $productMapData,
                            $productMapId,
                            $productsTopImageVersion,
                            $productsNotMappedIndexedBySku,
                            $products[$productMapId]['ImagesVersion'],
                            $overrideImages
                        );

                        if (isset($productMapData->images)) {
                            $this->deleteProductImageAttachment($productMapData->sku);
                        }

                        if (!$overridePrice) {
                            unset($productMapData->price, $productMapData->regular_price, $productMapData->sale_price);
                        }

                        if (\array_key_exists($productMapData->sku, $productParentIdsIndexedSku)) {
                            $productFactoryToDelete[$productParentIdsIndexedSku[$productMapData->sku]] = $productParentIdsIndexedSku[$productMapData->sku];
                            $productIdsToUpdateIndexedByProductMapId[$productMapId] = true;
                            $productFactorySave[$productMapId] = $productMapData;
                            $productToSaveIndexedByProductMapId[$productMapId] = $productMapData;

                            continue;
                        }

                        $productIdsToUpdateIndexedByProductMapId[$productMapId] = true;
                        $productFactoryToUpdate[$productMapId] = $productMapData;
                        $productToUpdateIndexedByProductMapId[$productMapId] = $productMapData;

                        continue;
                    }

                    $productIdsToUpdateIndexedByProductMapId[$productMapId] = true;
                    $productFactorySave[$productMapId] = $productMapData;
                    $productToSaveIndexedByProductMapId[$productMapId] = $productMapData;
                }

                if (!array_key_exists($productMapId, $productMapIdsIndexedByProductMapId)) {
                    if (\array_key_exists($productMapData->sku, $productsNotMappedIndexedBySku)) {
                        $productMapData->id = (int)$productsNotMappedIndexedBySku[$productMapData->sku];

                        if (!$overrideCategoryTree) {
                            $productMapData->categories = $this->addUnmappedCategoriesAssociatedToProduct($productMapData->id, $productMapData->categories);
                        }

                        $productMapData = $this->deleteProductImageData(
                            $productMapData,
                            $productMapId,
                            $productsTopImageVersion,
                            $productsNotMappedIndexedBySku,
                            $products[$productMapId]['ImagesVersion'],
                            $overrideImages
                        );

                        if (isset($productMapData->images)) {
                            $this->deleteProductImageAttachment($productMapData->sku);
                        }

                        if (!$overridePrice) {
                            unset($productMapData->price, $productMapData->regular_price, $productMapData->sale_price);
                        }

                        if (\array_key_exists($productMapData->sku, $productParentIdsIndexedSku)) {
                            $productFactoryToDelete[$productParentIdsIndexedSku[$productMapData->sku]] = $productParentIdsIndexedSku[$productMapData->sku];
                            $productIdsToUpdateIndexedByProductMapId[$productMapId] = false;
                            $productFactorySave[$productMapId] = $productMapData;
                            $productToSaveIndexedByProductMapId[$productMapId] = $productMapData;

                            continue;
                        }

                        $productIdsToUpdateIndexedByProductMapId[$productMapId] = false;
                        $productFactoryToUpdate[$productMapId] = $productMapData;
                        $productToUpdateIndexedByProductMapId[$productMapId] = $productMapData;

                        continue;
                    }

                    $productIdsToUpdateIndexedByProductMapId[$productMapId] = false;
                    $productFactorySave[$productMapId] = $productMapData;
                    $productToSaveIndexedByProductMapId[$productMapId] = $productMapData;
                }
            }

            if (!empty($productFactoryToDelete)) {
                $this->productManager->deleteCollection($productFactoryToDelete);
            }

            if (!$productFactorySave && !$productFactoryToUpdate) {
                continue;
            }

            try {
                $this->logger->debug('Started the API PRODUCT UPDATE process');
                $productBatchResponse = $this->productManager->updateCollection($productFactoryToUpdate);

                if (\array_key_exists('update', $productBatchResponse) && $productBatchResponse['update']) {
                    $processedArray[] = $this->mapAndImport(
                        $productBatchResponse['update'],
                        $productToUpdateIndexedByProductMapId,
                        $productIdsToUpdateIndexedByProductMapId,
                        $products,
                        $fileId,
                        $languageIsoCode
                    );
                }
            } catch (WooCommerceApiExceptionInterface $exception) {
                $this->logger->error(__METHOD__.' Product Update Batch Error - Exception Message: '.$exception->getMessage());
            }

            $this->logger->debug('Finished the API PRODUCT UPDATE process');


            try {
                $this->logger->debug('Started the API PRODUCT CREATE process');
                $productBatchResponse = $this->productManager->createCollection($productFactorySave);

                if (\array_key_exists('create', $productBatchResponse) && $productBatchResponse['create']) {
                    $processedArray[] = $this->mapAndImport(
                        $productBatchResponse['create'],
                        $productToSaveIndexedByProductMapId,
                        $productIdsToUpdateIndexedByProductMapId,
                        $products,
                        $fileId,
                        $languageIsoCode
                    );
                }
            } catch (WooCommerceApiExceptionInterface $exception) {
                $this->logger->error(__METHOD__.' Product Create Batch Error - Exception Message: '.$exception->getMessage());
            }

            $this->logger->debug('Finished the API PRODUCT CREATE process');
        }

        $processed = array_merge(...$processedArray);

        return \array_column($processed, 'shopId', 'id');
    }

    /**
     * @param int $productId
     * @param array $productCategories
     * @return array
     */
    private function addUnmappedCategoriesAssociatedToProduct(int $productId, array $productCategories): array
    {
        $productCategoriesData = $productCategories;
        $product = \wc_get_product($productId);

        if (!$product) {
            return $productCategoriesData;
        }

        $shopCategoryIds = $product->get_category_ids();

        $categoriesDifferenceFromShop = \array_diff($shopCategoryIds, \array_column($productCategoriesData, 'id'));

        $mappedCategoryShopIds = $this->categoryMapManager->findAllIndexedByCategoryShopId($categoriesDifferenceFromShop);

        $unmappedCategories = \array_diff($categoriesDifferenceFromShop, $mappedCategoryShopIds);

        foreach ($unmappedCategories as $unmappedCategory) {
            $productCategoriesData[] = ['id' => $unmappedCategory];
        }

        return $productCategoriesData;
    }

    /**
     * @param array $productBatchResponse
     * @param array $productIndexedByProductMapId
     * @param array $productNotMappedIndexedById
     * @param array $products
     * @param int $fileId
     * @param string $languageIsoCode
     * @return array
     * @throws \Exception
     */
    private function mapAndImport(array $productBatchResponse, array $productIndexedByProductMapId, array $productNotMappedIndexedById, array $products, int $fileId, string $languageIsoCode): array
    {
        $productIds = [];
        $productMapIndex = 0;

        foreach ($productIndexedByProductMapId as $productId => $productMapData) {
            $product = $productBatchResponse[$productMapIndex];
            $productMapIndex++;

            if ($this->checkIfHasBatchErrorAndIsNotByDuplicatedSku($product)) {
                $setProductError = '';

                if ($this->isInvalidProductIdErrorCode($product['error']['code'])) {
                    $this->productManager->deleteBySku($productMapData->sku);
                    $setProductError = ' - Invalid SKU deleted, this product will be processed in another file';
                }

                $this->importProcessService->setFailure($productId, $fileId);

                $errorCode = $product['error']['code'];
                $message = 'Product '.$productId.' of fileID '.$fileId.' has been not processed. Batch response: '
                    . $errorCode.' - Product SKU: '.$productMapData->sku . $setProductError;

                if (isset($product['error']['message']) && $product['error']['message']) {
                    $internalCode = ResponseService::getInternalErrorCodeFromResponseError($product['error']['message']);
                    $message .= ' - InternalCode: '. $internalCode. ' - ErrorMessage: '.$product['error']['message'];
                }

                $this->logger->error($message);

                continue;
            }

            $product = $this->deleteDuplicatedProducts($productMapData->sku);

            if (!$product) {
                continue;
            }

            $productToMap = $this->productMapFactory->create($products[$productId], $product['id'], $languageIsoCode);

            if (\array_key_exists($productId, $productNotMappedIndexedById) && $productNotMappedIndexedById[$productId]) {
                $this->productMapManager->update($product['id'], $productToMap);
            } else {
                $this->productMapManager->save($productToMap);
            }

            $this->referenceDataRepository->upsert($productToMap->productId, $productMapData->sku, $products[$productId]['EAN']);
            $this->importProcessService->setSuccess($productId, $fileId);

            if (isset($productMapData->description) && stripos($productMapData->description, 'iframe') !== false) {
                $this->productManager->updateIframeDescription($product['id'], $productMapData->description);
            }

            $productIds[] = ['shopId' => $product['id'], 'id' => $productId];
        }

        return $productIds;
    }

    /**
     * @param array $productShopIds
     * @param int $days
     */
    public function deleteMappedProductsDisableByDays(array $productShopIds, int $days = 90): void
    {
        $productIdsDisabledToDelete = $this->productManager->findMappedProductsIdsDisableByDays($productShopIds, $days);
        $this->deleteByIds($productIdsDisabledToDelete);
    }

    /**
     * @param array $productIdsDisabledToDelete
     * @param bool|null $purge
     * @return void
     */
    public function deleteByIds(array $productIdsDisabledToDelete, ?bool $purge = false): void
    {
        $message = 'Products older than 90 days';

        if ($purge) {
            $message = 'Products older than 90 days and not included in orders';
        }

        try {
            $this->productManager->deleteCollection($productIdsDisabledToDelete);

            $this->logger->getInstance()->info($message.' deleted, affected ids: '.\json_encode($productIdsDisabledToDelete, true));
        } catch (WooCommerceApiExceptionInterface $exception) {
            $this->logger->error($message.' could not be deleted, error: '.$exception->getMessage());
        }
    }

    /**
     * @param WCProduct $productMapData
     * @param int $productMapId
     * @param array $productsTopImageVersion
     * @param array $productsNotMappedIndexedBySku
     * @param int $imageVersion
     * @param bool $overrideImages
     * @return WCProduct
     */
    public function deleteProductImageData(
        WCProduct $productMapData,
        int $productMapId,
        array $productsTopImageVersion,
        array $productsNotMappedIndexedBySku,
        int $imageVersion,
        bool $overrideImages
    ): WCProduct {
        if (\array_key_exists($productMapId, $productsTopImageVersion)) {
            if ((isset($productsTopImageVersion[$productMapId]) && (int)$productsTopImageVersion[$productMapId] >= $imageVersion) || !$overrideImages) {
                unset ($productMapData->images);
            }
        } elseif (\array_key_exists($productMapData->sku, $productsNotMappedIndexedBySku)) {
            unset ($productMapData->images);
        }

        return $productMapData;
    }

    /**
     * @param string $productSku
     */
    public function deleteProductImageAttachment(string $productSku): void
    {
        $imageProductPosts = $this->productManager->findImagePostsIndexedByProductShop($productSku);

        if ($imageProductPosts) {
            foreach ($imageProductPosts as $imagePost) {
                wp_delete_post( $imagePost['ID'], true);

                if (file_exists($imagePost['guid'])) {
                    unlink($imagePost['guid']);
                }
            }
        }
    }

    /**
     * @param string $productSku
     * @return array
     */
    public function deleteDuplicatedProducts(string $productSku): array
    {
        $i = 0;
        $productToMap = [];
        $productsToDelete = [];

        $productIndexedBySku = $this->productManager->findProductShopIdWithSameSku($productSku);

        if (!$productIndexedBySku) {
            return [];
        }

        foreach ($productIndexedBySku as $product) {
            if ($i === 0) {
                $productToMap = $product;
                $i++;

                continue;
            }

            $productsToDelete[] = $product['product_id'];
        }

        if (!$productsToDelete) {
            return ['id' => $productToMap['product_id']];
        }

        try {
            $this->productManager->deleteCollection($productsToDelete);
        } catch (WooCommerceApiExceptionInterface $e) {
            $this->logger->getInstance()->error('Exception found in function deleteDuplicatedProducts()->save: '.$e->getMessage());

            return [];
        }

        $this->deleteImagesNotAssociateToProducts($productToMap['product_id']);

        $this->logger->getInstance()->warning('Products with duplicated SKU '.$productSku.' with IDS: '
            .\json_encode(\array_values($productsToDelete), true).' have been deleted');

        return ['id' => $productToMap['product_id']];
    }

    /**
     * @param array $productsProcessedFromFile
     * @return array
     */
    public function getProductSkuIndexedByProductId(array $productsProcessedFromFile): array
    {
        $productSkuIndexedByProductMapId = [];
        $productSkuIndexedByProductShopId = [];

        $productShopIdIndexedByProductMapId = $this->productMapManager->findByProductMapIdsIndexedByProductMapId(\array_values($productsProcessedFromFile));

        if ($productShopIdIndexedByProductMapId) {
            $productSkuIndexedByProductShopId = $this->productManager->findProductIdAndSkuByProductIds(\array_values($productShopIdIndexedByProductMapId));
        }

        if (!$productSkuIndexedByProductShopId) {
            return $productSkuIndexedByProductMapId;
        }

        foreach ($productShopIdIndexedByProductMapId as $productMapId => $productShopId) {
            if (\array_key_exists($productShopId, $productSkuIndexedByProductShopId)) {
                $productSkuIndexedByProductMapId[$productMapId] = $productSkuIndexedByProductShopId[$productShopId];
            }
        }

        return $productSkuIndexedByProductMapId;
    }

    public function deleteImagesNotAssociateToProducts(int $productShopId): void
    {
        $productImagesTitles = $this->productManager->getProductImagesTitlesByProductId($productShopId);

        if (empty($productImagesTitles)) {
            return;
        }

        $imageTitles = [];
        foreach ($productImagesTitles as $productImageTitle) {
            $imageTitles[] = preg_replace(
                "/-[0-9]{1}/",
                '',
                str_replace(['.jpg', '.png', '.jpeg', '.gif'], '', $productImageTitle)
            );
        }

        $imagesIdsNotAssociateToProduct = $this->productManager->getImagesNotAssociateToProductByImageTitles($imageTitles);

        if (empty($imagesIdsNotAssociateToProduct)) {
            return;
        }

        $imageIdsWithProductMeta = $this->productManager->getImageIdsWithProductMetaByImageIds($imagesIdsNotAssociateToProduct);
        $imageIdsToDelete = array_diff($imagesIdsNotAssociateToProduct, $imageIdsWithProductMeta);

        foreach ($imageIdsToDelete as $imageIdToDelete) {
            wp_delete_attachment((int)$imageIdToDelete, true);
        }
    }

    /**
     * @param array $product
     * @return bool
     */
    private function checkIfHasBatchErrorAndIsNotByDuplicatedSku(array $product): bool
    {
        return \array_key_exists('error', $product) && $product['error'] && $product['error']['code'] !== WooCommerceErrorCodes::PRODUCT_INVALID_SKU;
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
     * @param array $categories
     * @return bool
     */
    private function getOverrideCategoryTree(array $categories): bool
    {
        $overrideCategoryTree = true;

        foreach ($categories as $category) {
            $overrideCategoryTree = $category['OverrideCategoryTree'];
            break;
        }

        return $overrideCategoryTree;
    }

    public function addGtinToStructuredData($markup, ?\WC_Product $product)
    {
        try {
            if (!$product) {
                return $markup;
            }

            $gtin = \trim((string)$this->referenceDataRepository->findEanByReference($product->get_sku()));

            if (\strlen($gtin) !== 13) {
                return $markup;
            }

            $markup['gtin'] = $gtin;
        } catch (\Throwable $exception) {
            $this->logger->critical('Error defining gtin in structured data: '.$exception->getMessage());
        }

        return $markup;
    }
}