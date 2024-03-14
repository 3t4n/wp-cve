<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\BrandMap;
use WcMipConnector\Repository\BrandMapRepository;

class BrandMapManager
{
    /** @var BrandMapRepository */
    protected $repository;

    public function __construct()
    {
        $this->repository = new BrandMapRepository();
    }

    /**
     * @param array $brandMapIds
     * @return array
     */
    public function findByBrandMapIdsIndexedByBrandMapId(array $brandMapIds): array
    {
        return $this->repository->findByBrandMapIdsIndexedByBrandMapId($brandMapIds);
    }

    /**
     * @param array $brandMapIds
     * @return array
     */
    public function findVersionsIndexedByBrandMapId(array $brandMapIds): array
    {
        return $this->repository->findVersionsIndexedByBrandMapId($brandMapIds);
    }

    /**
     * @param BrandMap $brandMap
     * @return bool
     */
    public function save(BrandMap $brandMap): bool
    {
        $data = [
            'brand_id' => $brandMap->brandId,
            'brand_shop_id' => $brandMap->shopBrandId,
            'version' => $brandMap->version,
            'date_add' => date('Y-m-d H:i:s'),
            'date_update' => null
        ];

        return $this->repository->save($data);
    }

    /**
     * @param int $shopBrandId
     * @param BrandMap $brandMap
     * @return bool
     */
    public function update(int $shopBrandId, BrandMap $brandMap): bool
    {
        $data = [
            'brand_shop_id' => $shopBrandId,
            'version' => $brandMap->version,
            'date_update' => date('Y-m-d H:i:s')
        ];

        $where = [
            'brand_id' => $brandMap->brandId
        ];

        return $this->repository->update($data, $where);
    }

    public function cleanTable(): void
    {
        $this->repository->cleanTable();
    }

    /**
     * @param array $brandShopIds
     */
    public function deleteByBrandShopIds(array $brandShopIds): void
    {
        $this->repository->deleteByBrandShopIds($brandShopIds);
    }
}