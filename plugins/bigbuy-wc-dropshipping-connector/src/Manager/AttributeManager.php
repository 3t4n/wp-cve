<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCAttribute;
use WcMipConnector\Enum\WooCommerceApiMethodTypes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Model\WCBatchRequest;
use WcMipConnector\Repository\AttributeRepository;
use WcMipConnector\Service\WoocommerceApiAdapterService;

class AttributeManager
{
    /** @var WoocommerceApiAdapterService */
    protected $apiAdapterService;
    /** @var AttributeRepository */
    protected $repository;

    public function __construct()
    {
        $this->apiAdapterService = new WoocommerceApiAdapterService();
        $this->repository = new AttributeRepository();
    }

    /**
     * @param array $attributeMapIds
     * @param string $attributeParentName
     * @return array
     */
    public function findByAttributeShopIdsIndexedByAttributeId(array $attributeMapIds, string $attributeParentName): array
    {
        return $this->repository->findByAttributeShopIdsIndexedByAttributeId($attributeMapIds, $attributeParentName);
    }

    /**
     * @param array $attributes
     * @param int $attributeParentId
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function deleteCollection(array $attributes, int $attributeParentId): array
    {
        if (!$attributes) {
            return [];
        }

        $request = new WCBatchRequest();
        $request->delete = $attributes;
        $queryParams = ['attribute_id' => $attributeParentId];

        return $this->apiAdapterService->batchItems(
            WooCommerceApiMethodTypes::TYPE_PRODUCT_ATTRIBUTE_TERMS,
            $request,
            $queryParams
        );
    }

    /**
     * @param WCAttribute[] $attributes
     * @param int $attributeParentId
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function updateCollection(array $attributes, int $attributeParentId): array
    {
        if (!$attributes) {
            return [];
        }

        foreach ($attributes as $attribute) {
            $attribute->attribute_id = $attributeParentId;
        }

        $request = new WCBatchRequest();
        $request->update = $attributes;
        $queryParams = ['attribute_id' => $attributeParentId];

        return $this->apiAdapterService->batchItems(
            WooCommerceApiMethodTypes::TYPE_PRODUCT_ATTRIBUTE_TERMS,
            $request,
            $queryParams
        );
    }

    /**
     * @param WCAttribute[] $attributes
     * @param int $attributeParentId
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function createCollection(array $attributes, int $attributeParentId): array
    {
        if (!$attributes) {
            return [];
        }

        foreach ($attributes as $attribute) {
            $attribute->attribute_id = $attributeParentId;
        }

        $request = new WCBatchRequest();
        $request->create = $attributes;
        $queryParams = ['attribute_id' => $attributeParentId];

        return $this->apiAdapterService->batchItems(
            WooCommerceApiMethodTypes::TYPE_PRODUCT_ATTRIBUTE_TERMS,
            $request,
            $queryParams
        );
    }

    /**
     * @param string $slug
     * @return int
     */
    public function findAttributeShopIdBySlug(string $slug): ?int
    {
        return $this->repository->findAttributeShopIdBySlug($slug);
    }

    /**
     * @param array $attributesNames
     * @param string $attributeParentName
     * @return array
     */
    public function findAttributeShopIdIndexedBySlug(array $attributesNames, string $attributeParentName): array
    {
        return $this->repository->findAttributeShopIdIndexedBySlug($attributesNames, $attributeParentName);
    }

    /**
     * @return array
     */
    public function findEmptyAttributes(): array
    {
        return $this->repository->findEmptyAttributes();
    }

    /**
     * @param array $attributeShopIds
     * @return array
     */
    public function findAttributesIndexedByAttributeGroupShopId(array $attributeShopIds): array
    {
        return $this->repository->findAttributesIndexedByAttributeGroupShopId($attributeShopIds);
    }
}