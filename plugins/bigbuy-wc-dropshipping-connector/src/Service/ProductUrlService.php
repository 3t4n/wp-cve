<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\AttributeMap;
use WcMipConnector\Manager\AttributeGroupMapManager;
use WcMipConnector\Manager\AttributeMapManager;
use WcMipConnector\Manager\BrandMapManager;
use WcMipConnector\Manager\CategoryMapManager;
use WcMipConnector\Manager\ImagesUrlManager;
use WcMipConnector\Manager\ProductImageUrlManager;
use WcMipConnector\Manager\ProductManager;
use WcMipConnector\Manager\ProductMapManager;
use WcMipConnector\Manager\ProductUrlManager;

class ProductUrlService
{
    /** @var ProductUrlManager */
    private $productUrlManager;

    /** @var ProductMapManager */
    private $productMapManager;

    /** @var VariationService */
    private $variationService;

    /** @var ProductManager */
    private $productManager;

    /** @var LoggerService */
    private $logger;

    /** @var ImagesUrlManager */
    private $imagesUrlManager;

    /** @var ProductImageUrlManager */
    private $productImageUrlManager;

    /** @var CategoryMapManager */
    private $categoryMapManager;

    /** @var AttributeMapManager */
    private $attributeMapManager;

    /** @var AttributeGroupMapManager */
    private $attributeGroupMapManager;

    /** @var BrandMapManager */
    private $brandMapManager;

    public function __construct()
    {
        $this->productUrlManager = new ProductUrlManager();
        $this->productMapManager = new ProductMapManager();
        $this->productManager = new ProductManager();
        $this->variationService = new VariationService();
        $this->logger = new LoggerService();
        $this->imagesUrlManager = new ImagesUrlManager();
        $this->productImageUrlManager = new ProductImageUrlManager();
        $this->categoryMapManager = new CategoryMapManager();
        $this->attributeMapManager = new AttributeMapManager();
        $this->attributeGroupMapManager = new AttributeGroupMapManager();
        $this->brandMapManager = new BrandMapManager();
    }

    /**
     * @return bool
     */
    public function executeGoogleShoppingProcess(): bool
    {
        $this->productMapManager->cleanTable();
        $this->imagesUrlManager->cleanTable();
        $this->productImageUrlManager->cleanTable();
        $this->productUrlManager->cleanTable();
        $this->categoryMapManager->cleanTable();
        $this->attributeMapManager->cleanTable();
        $this->attributeGroupMapManager->cleanTable();
        $this->brandMapManager->cleanTable();

        $mappedProductsIndexedByProductShopId = $this->productMapManager->getProductsShopIndexedByProductShopId();
        $mappedVariationsIndexedByVariationShopId = $this->variationService->getVariationsShopIndexedByVariationShopId();

        return $this->updateProductsImagesUrls($mappedProductsIndexedByProductShopId, $mappedVariationsIndexedByVariationShopId);
    }

    /**
     * @param array $mappedProductsIndexedByProductShopId
     * @param array $mappedVariationsIndexedByVariationShopId
     * @return bool
     */
    public function updateProductsImagesUrls(array $mappedProductsIndexedByProductShopId, array $mappedVariationsIndexedByVariationShopId): bool
    {
        $variationsUrlIndexedByProductShopId = [];
        $variationShopIdsIndexedByProductId = $this->productManager->getVariationShopIdsIndexedByProductId(\array_values($mappedVariationsIndexedByVariationShopId));
        $productsUrlIndexedByProductShopId = $this->productManager->getProductUrls($mappedProductsIndexedByProductShopId);
        $productImages = $this->productManager->getProductImages();
        $imageIds = '';

        foreach ($productImages as $productImage) {
            $imageIds .= $productImage['meta_value'].',';
        }

        $imageUrls = $this->productManager->getImagesUrl($imageIds);
        $productImageUrls = $this->productManager->getProductImagesUrl($productImages, $imageUrls);

        if (!empty($variationShopIdsIndexedByProductId)) {
            $variationsUrlIndexedByProductShopId = $this->variationService->getVariationsUrlIndexedByProductShopId($variationShopIdsIndexedByProductId);
        }

        try {
            $this->productUrlManager->updateProductsUrl($productsUrlIndexedByProductShopId, $variationsUrlIndexedByProductShopId);
            $this->imagesUrlManager->updateImagesUrl($productImageUrls);
            $this->productImageUrlManager->updateProductImageUrl($productImageUrls);
        } catch (\Exception $exception) {
            $this->logger->error('updateProductsImagesUrls - Exception Message: '.$exception->getMessage());
            return false;
        }

        return true;
    }

    /**
     * @param \DateTime $date
     * @return array|object|null
     */
    public function getProductUrls(\DateTime $date)
    {
        return $this->productUrlManager->getProductUrls($date);
    }

    /**
     * @param \DateTime $date
     * @return array|object|null
     */
    public function getVariationsUrls(\DateTime $date)
    {
        return $this->productUrlManager->getVariationsUrls($date);
    }
}