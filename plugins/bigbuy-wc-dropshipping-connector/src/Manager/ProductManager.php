<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCProduct;
use WcMipConnector\Enum\WooCommerceApiMethodTypes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Model\ProductReport;
use WcMipConnector\Model\WCBatchRequest;
use WcMipConnector\Repository\ProductRepository;
use WcMipConnector\Service\WoocommerceApiAdapterService;

class ProductManager
{
    /** @var ProductRepository */
    protected $productRepository;
    /** @var WoocommerceApiAdapterService */
    protected $apiAdapterService;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->apiAdapterService = new WoocommerceApiAdapterService();
    }

    /**
     * @param array $productMapIds
     *
     * @return array
     */
    public function findByProductShopIdsIndexedByProductId(array $productMapIds): array
    {
        return $this->productRepository->findByProductShopIdsIndexedByProductId($productMapIds);
    }

    /**
     * @param array $productShopIds
     *
     * @return array
     */
    public function findDisabledByProductShopIdIndexedByProductShopId(array $productShopIds): array
    {
        return $this->productRepository->findDisabledByProductShopIdIndexedByProductShopId($productShopIds);
    }

    /**
     * @param array $productMapIds
     *
     * @return array
     */
    public function findProductShopIdIndexedBySku(array $productMapIds): array
    {
        return $this->productRepository->findProductShopIdIndexedBySku($productMapIds);
    }

    /**
     * @param string $productSku
     *
     * @return array
     */
    public function findProductShopIdWithSameSku(string $productSku): array
    {
        return $this->productRepository->findProductShopIdWithSameSku($productSku);
    }

    /**
     * @param array $productMapIds
     *
     * @return array
     */
    public function findProductIdAndSkuByProductIds(array $productMapIds): array
    {
        return $this->productRepository->findProductIdAndSkuByProductIds($productMapIds);
    }

    /**
     * @param array $productIds
     *
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function deleteCollection(array $productIds): array
    {
        if (!$productIds) {
            return [];
        }

        $request = new WCBatchRequest();
        $request->delete = $productIds;

        return $this->apiAdapterService->batchItems(WooCommerceApiMethodTypes::TYPE_PRODUCTS, $request);
    }

    /**
     * @param WCProduct[] $products
     *
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function updateCollection(array $products): array
    {
        if (!$products) {
            return [];
        }

        $request = new WCBatchRequest();
        $request->update = $products;

        return $this->apiAdapterService->batchItems(WooCommerceApiMethodTypes::TYPE_PRODUCTS, $request);
    }

    /**
     * @param WCProduct[] $products
     *
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function createCollection(array $products): array
    {
        if (!$products) {
            return [];
        }

        $request = new WCBatchRequest();
        $request->create = $products;

        return $this->apiAdapterService->batchItems(WooCommerceApiMethodTypes::TYPE_PRODUCTS, $request);
    }

    /**
     * @return ProductReport
     */
    public function getProductReport(): ProductReport
    {
        $response = new ProductReport();

        $response->TotalProducts = $this->countTotalMapped();
        $response->ActiveProducts = $this->countTotalMappedAndActive();
        $products = $this->productRepository->getProducts();
        $response->Products = [];

        foreach ($products as $product) {
            $product['Active'] = $product['Active'] === 'publish';
            $product['Stock'] = (int)$product['Stock'];
            $response->Products[] = $product;
        }

        return $response;
    }

    /**
     * @return int
     */
    public function countTotalMappedAndActive(): int
    {
        return $this->productRepository->countTotalMappedAndActive();
    }

    /**
     * @return int
     */
    public function countTotalMappedAndDisabled(): int
    {
        return $this->productRepository->countTotalMappedAndDisabled();
    }

    /**
     * @return int
     */
    public function countTotalMapped(): int
    {
        return $this->productRepository->countTotalMapped();
    }

    /**
     * @return int
     */
    public function countTotalProductShop(): int
    {
        return $this->productRepository->countTotalProductShop();
    }

    /**
     * @return int
     */
    public function countTotalProductShopDisabled(): int
    {
        return $this->productRepository->countTotalProductShopDisabled();
    }

    /**
     * @return int
     */
    public function countTotalProductShopActive(): int
    {
        return $this->productRepository->countTotalProductShopActive();
    }

    /**
     * @param array $mappedProductsIndexedByShopId
     * @return array
     */
    public function getProductUrls(array $mappedProductsIndexedByShopId): array
    {
        if (empty($mappedProductsIndexedByShopId)) {
            return [];
        }

        return $this->productRepository->getProductUrls($mappedProductsIndexedByShopId);
    }

    /**
     * @return array
     */
    public function getProductImages(): array
    {
        return $this->productRepository->getProductImages();
    }

    /**
     * @param array $productShopIds
     * @param int $days
     * @return array
     */
    public function findMappedProductsIdsDisableByDays(array $productShopIds, int $days): array
    {
        return $this->productRepository->findMappedProductsIdsDisableByDays($productShopIds, $days);
    }

    /**
     * @return array
     */
    public function findIdsDisabledToPurge(): array
    {
        return $this->productRepository->findIdsDisabledToPurge();
    }

    /**
     * @param string $productSku
     * @return array
     */
    public function findImagePostsIndexedByProductShop(string $productSku): array
    {
        return $this->productRepository->findImagePostsIndexedByProductShop($productSku);
    }

    /**
     * @param int $productShopId
     * @param string $description
     * @return bool
     */
    public function updateIframeDescription(int $productShopId, string $description): bool
    {
        $data = [
            'post_content' => $description,
        ];

        $where = [
            'ID' => $productShopId,
        ];

        return $this->productRepository->update($data, $where);
    }

    /**
     * @param int $productShopId
     * @return array
     */
    public function getProductImagesTitlesByProductId(int $productShopId): array
    {
        return $this->productRepository->getProductImagesTitlesByProductId($productShopId);
    }

    /**
     * @param array|string[] $imageTitles
     * @return array
     */
    public function getImagesNotAssociateToProductByImageTitles(array $imageTitles): array
    {
        return $this->productRepository->getImagesNotAssociateToProductByImageTitles($imageTitles);
    }

    /**
     * @param array $imageIds
     * @return array
     */
    public function getImageIdsWithProductMetaByImageIds(array $imageIds): array
    {
        return $this->productRepository->getImageIdsWithProductMetaByImageIds($imageIds);
    }

    /**
     * @param array $variationIds
     * @return array
     */
    public function getVariationShopIdsIndexedByProductId(array $variationIds): array
    {
        if (empty($variationIds)) {
            return [];
        }

        return $this->productRepository->getVariationShopIdsIndexedByProductId($variationIds);
    }

    /**
     * @param array $productSkuIndexedByProductIds
     * @return array
     */
    public function getProductShopIdByReferencesIndexedByReference(array $productSkuIndexedByProductIds): array
    {
        $skusIndexedByProductId = $this->productRepository->getSkusIndexedByProductId($productSkuIndexedByProductIds);
        $postParentsIndexedByProductIds = $this->productRepository->getPostParentsIndexedByProductIds($skusIndexedByProductId);

        if (empty($postParentsIndexedByProductIds)) {
            return [];
        }

        $productShopIdByReferencesIndexedByReference = [];

        foreach ($skusIndexedByProductId as $productId => $sku) {
            if (!\array_key_exists($productId, $postParentsIndexedByProductIds)) {
                continue;
            }

            $productShopIdByReferencesIndexedByReference[$sku] = $postParentsIndexedByProductIds[$productId];
        }

        return $productShopIdByReferencesIndexedByReference;
    }

    /**
     * @param string $productImageIds
     * @return array|null
     */
    public function getImagesUrl(string $productImageIds): ?array
    {
        return $this->productRepository->getImagesUrl($productImageIds);
    }

    /**
     * @param array $productImages
     * @param array $imageUrls
     * @return array
     */
    public function getProductImagesUrl(array $productImages, array $imageUrls): array
    {
        return $this->productRepository->getProductImagesUrl($productImages, $imageUrls);
    }

    /**
     * @param string $productSku
     * @return bool
     */
    public function deleteBySku(string $productSku): bool
    {
        $where = [
            'sku' => $productSku,
        ];

        return $this->productRepository->delete($where);
    }

    /**
     * @param int[] $productIds
     */
    public function updateMetaLookUpStockByProductIds(array $productIds): void
    {
        $this->productRepository->updateMetaLookUpStockByProductIds($productIds);
    }

    /**
     * @param int[] $productIds
     */
    public function updatePostMetaStockByProductIds(array $productIds): void
    {
        $this->productRepository->updatePostMetaStockByProductIds($productIds);
    }

    /**
     * @param int[] $productIds
     */
    public function updatePostMetaStockStatusByProductIds(array $productIds): void
    {
        $this->productRepository->updatePostMetaStockStatusByProductIds($productIds);
    }
}