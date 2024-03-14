<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCBrand;
use WcMipConnector\Enum\WooCommerceApiMethodTypes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Model\WCBatchRequest;
use WcMipConnector\Repository\BrandRepository;
use WcMipConnector\Service\WoocommerceApiAdapterService;

class BrandManager
{
    /** @var BrandRepository */
    protected $repository;
    /** @var WoocommerceApiAdapterService */
    protected $apiAdapterService;
    /** @var AttributeGroupManager */
    protected $attributeGroupManager;

    /** BrandManager constructor. */
    public function __construct()
    {
        $this->apiAdapterService = new WoocommerceApiAdapterService();
        $this->repository = new BrandRepository();
        $this->attributeGroupManager = new AttributeGroupManager();
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
     * @param int $brandId
     * @param WCBrand[] $brandsFactorySave
     * @param WCBrand[] $brandsFactoryToUpdate
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function save(int $brandId, array $brandsFactorySave, array $brandsFactoryToUpdate): array
    {
        foreach ($brandsFactorySave as $attribute) {
            $attribute->attribute_id = $brandId;
        }

        foreach ($brandsFactoryToUpdate as $attribute) {
            $attribute->attribute_id = $brandId;
        }

        $request = new WCBatchRequest();
        $request->create = $brandsFactorySave;
        $request->update = $brandsFactoryToUpdate;
        $queryParams = ['attribute_id' => $brandId];

        return $this->apiAdapterService->batchItems(
            WooCommerceApiMethodTypes::TYPE_PRODUCT_ATTRIBUTE_TERMS,
            $request,
            $queryParams
        );
    }

    public function deleteCollection($brandParentId, array $brandIds)
    {
        $request = new WCBatchRequest();
        $request->delete = $brandIds;
        $queryParams = ['attribute_id' => $brandParentId];

        return $this->apiAdapterService->batchItems(
            WooCommerceApiMethodTypes::TYPE_PRODUCT_ATTRIBUTE_TERMS,
            $request,
            $queryParams
        );
    }

    /**
     * @param string $brandName
     *
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function create(string $brandName): array
    {
        return $this->attributeGroupManager->create($brandName, BrandRepository::ATTRIBUTE_NAME);
    }

    /**
     * @param int $brandShopId
     * @param string $attributeLabel
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function update(int $brandShopId, string $attributeLabel): array
    {
        return $this->attributeGroupManager->update($brandShopId, $attributeLabel, BrandRepository::ATTRIBUTE_NAME);
    }

    /**
     * @param int $brandId
     *
     * @return bool
     */
    public function exists(int $brandId): bool
    {
        return wc_get_attribute($brandId) !== null;
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
     * @param array $brandsId
     * @return array
     */
    public function findBrandShopIdIndexedBySlug(array $brandsId): array
    {
        return $this->repository->findBrandShopIdIndexedBySlug($brandsId);
    }

    /**
     * @return array
     */
    public function findEmptyAttributeBrands(): array
    {
        return $this->repository->findEmptyAttributeBrands();
    }

    /**
     * @param string $attributeLabel
     * @return int|null
     */
    public function findIdByLabel(string $attributeLabel): ?int
    {
        return $this->repository->findIdByLabel($attributeLabel);
    }

    /**
     * @return int|null
     */
    public function findIdByName(): ?int
    {
        return $this->repository->findIdByName();
    }
}