<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCCategory;
use WcMipConnector\Enum\WooCommerceApiMethodTypes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Model\WCBatchRequest;
use WcMipConnector\Service\WoocommerceApiAdapterService;
use WcMipConnector\Repository\CategoryRepository;
use WcMipConnector\Model\CategoryReport;

class CategoryManager
{
    /** @var WoocommerceApiAdapterService */
    protected $apiAdapterService;
    /** @var CategoryRepository */
    protected $repository;

    public function __construct()
    {
        $this->apiAdapterService = new WoocommerceApiAdapterService();
        $this->repository = new CategoryRepository();
    }

    /**
     * @param array $categoryMapIds
     *
     * @return array
     */
    public function findByCategoryShopIdsIndexedByCategoryId(array $categoryMapIds): array
    {
        return $this->repository->findByCategoryShopIdsIndexedByCategoryId($categoryMapIds);
    }

    /**
     * @param array $categoryShopIds
     *
     * @return array
     */
    public function findParentsShopIdsIndexedByShopId(array $categoryShopIds): array
    {
        return $this->repository->findParentsShopIdsIndexedByShopId($categoryShopIds);
    }

    /**
     * @param array $categories
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function deleteCollection(array $categories): array
    {
        if (!$categories) {
            return [];
        }

        $request = new WCBatchRequest();
        $request->delete = $categories;

        return $this->apiAdapterService->batchItems(WooCommerceApiMethodTypes::TYPE_PRODUCT_CATEGORIES, $request);
    }

    /**
     * @param WCCategory[] $categories
     *
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function updateCollection(array $categories): array
    {
        if (!$categories) {
            return [];
        }

        $request = new WCBatchRequest();
        $request->update = $categories;

        return $this->apiAdapterService->batchItems(WooCommerceApiMethodTypes::TYPE_PRODUCT_CATEGORIES, $request);
    }

    /**
     * @param WCCategory[] $categories
     *
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function createCollection(array $categories): array
    {
        if (!$categories) {
            return [];
        }

        $request = new WCBatchRequest();
        $request->create = $categories;

        return $this->apiAdapterService->batchItems(WooCommerceApiMethodTypes::TYPE_PRODUCT_CATEGORIES, $request);
    }

    /**
     * @return CategoryReport
     */
    public function getCategoryReport(): CategoryReport
    {
        $response = new CategoryReport();

        $totalCategories = $this->repository->getTotalCategories();
        $response->TotalCategories = (int)$totalCategories['total'];
        $activeCategories = $this->repository->getActiveCategories();
        $response->ActiveCategories = (int)$activeCategories['active'];
        $categories = $this->repository->getCategories();
        $response->Categories = [];

        foreach ($categories as $category) {
            $category['Active'] = $category['Active'] === '1';
            $response->Categories[] = $category;
        }

        return $response;
    }

    /**
     * @return array
     */
    public function findEmptyCategories(): array
    {
        return $this->repository->findEmptyCategories();
    }

    /**
     * @param string $slug
     * @return int
     */
    public function findCategoryShopIdBySlug(string $slug): ?int
    {
        return $this->repository->findCategoryShopIdBySlug($slug);
    }

    /**
     * @param array $categoryShopIds
     * @return array
     */
    public function findCategoryImagePostIdIndexedByCategoryShopId(array $categoryShopIds): array
    {
        return $this->repository->findCategoryImagePostIdIndexedByCategoryShopId($categoryShopIds);
    }

    /**
     * @param array $postIds
     * @return array
     */
    public function findImagePostByPostIds(array $postIds): array
    {
        return $this->repository->findImagePostByPostIds($postIds);
    }

    /**
     * @param int $postId
     * @param string $postTitle
     * @return array
     */
    public function findImagePostIdsIndexedByIds(int $postId, string $postTitle): array
    {
        return $this->repository->findImagePostIdsIndexedByIds($postId, $postTitle);
    }

    /**
     * @param array $postIds
     * @return array
     */
    public function findImagePostMetaByPostId(array $postIds): array
    {
        return $this->repository->findImagePostMetaByPostId($postIds);
    }

    /**
     * @param array $postIds
     */
    public function deleteImagePostById(array $postIds): void
    {
        $this->repository->deleteImagePostById($postIds);
    }

    /**
     * @param array $metaDataIds
     */
    public function deleteImageMetaDataByMetaDataId(array $metaDataIds): void
    {
        $this->repository->deleteImageMetaDataByMetaDataId($metaDataIds);
    }

    /**
     * @param array $postIds
     * @return array
     */
    public function findImagePostIndexedByPostId(array $postIds): array
    {
        return $this->repository->findImagePostIndexedByPostId($postIds);
    }

    /**
     * @param array $postIds
     * @return array
     */
    public function findUrlIndexedByPostId(array $postIds): array
    {
        return $this->repository->findUrlIndexedByPostId($postIds);
    }

    /**
     * @param array $categoriesSlug
     * @return array
     */
    public function findCategoriesShopIdIndexedBySlug(array $categoriesSlug): array
    {
        return $this->repository->findCategoriesShopIdIndexedBySlug($categoriesSlug);
    }

    /**
     * @param string $categorySlug
     * @return array
     */
    public function findCategoryIdsBySlug(string $categorySlug): array
    {
        return $this->repository->findCategoryIdsBySlug($categorySlug);
    }
}