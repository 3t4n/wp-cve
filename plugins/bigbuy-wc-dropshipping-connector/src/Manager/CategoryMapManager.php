<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\CategoryMap;
use WcMipConnector\Repository\CategoryMapRepository;

class CategoryMapManager
{
    /** @var CategoryMapRepository */
    protected $repository;

    public function __construct()
    {
        $this->repository = new CategoryMapRepository();
    }

    /**
     * @return array
     */
    public function findCategoryShopIndexedByCategoryId(): array
    {
        return $this->repository->findCategoryShopIndexedByCategoryId();
    }

    /**
     * @param array $categoriesIdToProcess
     *
     * @return array
     */
    public function findAllIndexedByCategoryMapId(array $categoriesIdToProcess): array
    {
        return $this->repository->findAllIndexedByCategoryMapId($categoriesIdToProcess);
    }

    public function findCategoryIdIndexedByCategoryShop(array $categoryShopIds): array
    {
        return $this->repository->findCategoryIdIndexedByCategoryShop($categoryShopIds);
    }

    /**
     * @param array $categoriesIdToProcess
     *
     * @return array
     */
    public function findAllIndexedByCategoryShopId(array $categoriesIdToProcess): array
    {
        return $this->repository->findAllIndexedByCategoryShopId($categoriesIdToProcess);
    }

    /**
     * @param array $categoriesIdToProcess
     *
     * @return array
     */
    public function findVersionIndexedByCategoryMapId(array $categoriesIdToProcess): array
    {
        return $this->repository->findVersionIndexedByCategoryMapId($categoriesIdToProcess);
    }

    /**
     * @param CategoryMap $categoryMap
     *
     * @return bool
     */
    public function save(CategoryMap $categoryMap): bool
    {
        $data = [
            'category_id' => $categoryMap->categoryId,
            'category_shop_id' => $categoryMap->shopCategoryId,
            'version' => $categoryMap->version,
            'date_add' => date('Y-m-d H:i:s'),
            'date_update' => null
        ];

        return $this->repository->save($data);
    }

    /**
     * @param int    $categoryId
     * @param CategoryMap $categoryMap
     *
     * @return bool
     */
    public function update(int $categoryId, CategoryMap $categoryMap): bool
    {
        $data = [
            'category_shop_id' => $categoryId,
            'version' => $categoryMap->version,
            'date_update' => date('Y-m-d H:i:s')
        ];

        $where = [
            'category_id' => $categoryMap->categoryId
        ];

        return $this->repository->update($data, $where);
    }

    /**
     * @param array $categoryIds
     * @return int
     */
    public function deleteByCategoryIds(array $categoryIds): int
    {
        $categoryIdList = implode(',', $categoryIds);

        return $this->repository->deleteByCategoryIds($categoryIdList);
    }

    public function cleanTable(): void
    {
        $this->repository->cleanTable();
    }

    /**
     * @param array $categoryShopIds
     * @return int
     */
    public function deleteByCategoryShopIds(array $categoryShopIds): int
    {
        return $this->repository->deleteByCategoryShopIds($categoryShopIds);
    }
}