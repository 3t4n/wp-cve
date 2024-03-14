<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCBrand;
use WcMipConnector\Enum\WooCommerceApiMethodTypes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Model\WCBatchRequest;
use WcMipConnector\Repository\BrandPluginRepository;
use WcMipConnector\Service\WoocommerceApiAdapterService;

class BrandPluginManager
{
    /** @var BrandPluginRepository */
    protected $repository;
    /** @var WoocommerceApiAdapterService */
    protected $apiAdapterService;

    /** BrandManager constructor. */
    public function __construct()
    {
        $this->apiAdapterService = new WoocommerceApiAdapterService();
        $this->repository = new BrandPluginRepository();
    }

    /**
     * @param WCBrand[] $brandsFactorySave
     * @param WCBrand[] $brandsFactoryToUpdate
     * @param bool $brandPlugin
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function save(array $brandsFactorySave, array $brandsFactoryToUpdate): array
    {
        $request = new WCBatchRequest();
        $request->create = $brandsFactorySave;
        $request->update = $brandsFactoryToUpdate;

        return $this->apiAdapterService->batchItems(WooCommerceApiMethodTypes::TYPE_PRODUCT_BRANDS, $request);
    }

    public function deleteCollection(array $brandIds)
    {
        $request = new WCBatchRequest();
        $request->delete = $brandIds;

        return $this->apiAdapterService->batchItems(WooCommerceApiMethodTypes::TYPE_PRODUCT_BRANDS, $request);
    }

    /**
     * @return array
     */
    public function findEmptyBrands(): array
    {
        return $this->repository->findEmptyBrands();
    }

    /**
     * @param string $slug
     * @return int|null
     */
    public function findBrandShopIdBySlug(string $slug): ?int
    {
        return $this->repository->findBrandShopIdBySlug($slug);
    }

    /**
     * @param array $brandMapIds
     * @return array
     */
    public function findByBrandShopIdsIndexedByBrandId(array $brandMapIds): array
    {
        return $this->repository->findByBrandShopIdsIndexedByBrandId($brandMapIds);
    }

    /**
     * @param array $brandsId
     * @return array
     */
    public function findBrandShopIdIndexedBySlug(array $brandsId): array
    {
        return $this->repository->findBrandShopIdIndexedBySlug($brandsId);
    }
}