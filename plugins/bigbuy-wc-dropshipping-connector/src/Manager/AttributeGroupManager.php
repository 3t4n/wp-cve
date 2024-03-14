<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCAttribute;
use WcMipConnector\Enum\WooCommerceApiMethodTypes;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Model\WCBatchRequest;
use WcMipConnector\Service\WoocommerceApiAdapterService;
use WcMipConnector\Repository\AttributeGroupRepository;

class AttributeGroupManager
{
    /** @var WoocommerceApiAdapterService */
    protected $apiAdapterService;
    /** @var AttributeGroupRepository */
    protected $repository;

    public function __construct()
    {
        $this->apiAdapterService = new WoocommerceApiAdapterService();
        $this->repository = new AttributeGroupRepository();
    }

    /**
     * @param array $attributeGroupMapIds
     *
     * @return array
     */
    public function findByAttributeGroupShopIdsIndexedByAttributeGroupId(array $attributeGroupMapIds): array
    {
        return $this->repository->findByAttributeGroupShopIdsIndexedByAttributeGroupId($attributeGroupMapIds);
    }

    /**
     * @param array $attributeIds
     *
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function deleteCollection(array $attributeIds): array
    {
        if (empty($attributeIds)) {
            return [];
        }

        $request = new WCBatchRequest();
        $request->delete = $attributeIds;

        return $this->apiAdapterService->batchItems(WooCommerceApiMethodTypes::TYPE_PRODUCT_ATTRIBUTES, $request);
    }

    /**
     * @param array $attributes
     *
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function updateCollection(array $attributes): array
    {
        if (empty($attributes)) {
            return [];
        }

        $request = new WCBatchRequest();
        $request->update = $attributes;

        return $this->apiAdapterService->batchItems(WooCommerceApiMethodTypes::TYPE_PRODUCT_ATTRIBUTES, $request);
    }

    /**
     * @param WCAttribute[] $attributes
     *
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    public function createCollection(array $attributes): array
    {
        if (empty($attributes)) {
            return [];
        }

        $request = new WCBatchRequest();
        $request->create = $attributes;

        $result = $this->apiAdapterService->batchItems(WooCommerceApiMethodTypes::TYPE_PRODUCT_ATTRIBUTES, $request);

        foreach($attributes as $attribute) {
            $this->registerProductAttribute($attribute->name, $attribute->slug);
        }

        return $result;
    }

    /**
     * @param string $attributeName
     * @param string $attributeSlug
     * @return mixed
     * @throws WooCommerceApiExceptionInterface
     */
    public function create(string $attributeName, string $attributeSlug): array
    {
        $attribute = [
            'name' => $attributeName,
            'order_by' => 'name',
            'slug' => $attributeSlug
        ];

        $result = $this->apiAdapterService->createItem(WooCommerceApiMethodTypes::TYPE_PRODUCT_ATTRIBUTES, $attribute);

        $this->registerProductAttribute($attributeName, $attributeSlug);

        return $result;
    }

    /**
     * @param int $attributeId
     * @param string $attributeName
     * @param string $attributeSlug
     * @return mixed
     * @throws WooCommerceApiExceptionInterface
     */
    public function update(int $attributeId, string $attributeName, string $attributeSlug): array
    {
        $attribute = [
            'id' => $attributeId,
            'name' => $attributeName,
            'order_by' => 'name',
            'slug' => $attributeSlug
        ];

        $result = $this->apiAdapterService->updateItem(WooCommerceApiMethodTypes::TYPE_PRODUCT_ATTRIBUTES, $attribute);

        $this->registerProductAttribute($attributeName, $attributeSlug);

        return $result;
    }

    private function registerProductAttribute(string $attributeName, string $attributeSlug)
    {
        $taxonomy_name = wc_attribute_taxonomy_name($attributeSlug);
        register_taxonomy(
            $taxonomy_name,
            apply_filters('woocommerce_taxonomy_objects_'.$taxonomy_name, ['product']),
            apply_filters(
                'woocommerce_taxonomy_args_'.$taxonomy_name,
                [
                    'labels' => [
                        'name' => $attributeName,
                    ],
                    'hierarchical' => true,
                    'show_ui' => false,
                    'query_var' => true,
                    'rewrite' => false,
                ]
            )
        );

        global $wc_product_attributes;
        // Set product attributes global.
        $wc_product_attributes = [];

        foreach ( wc_get_attribute_taxonomies() as $taxonomy ) {
            $wc_product_attributes[ wc_attribute_taxonomy_name( $taxonomy->attribute_name ) ] = $taxonomy;
        }
    }

    /**
     * @param string $slug
     * @return int
     */
    public function findAttributeGroupShopIdBySlug(string $slug): ?int
    {
        return $this->repository->findAttributeGroupShopIdBySlug($slug);
    }

    /**
     * @param array $attributesGroupName
     * @return array
     */
    public function findAttributeGroupShopIdIndexedBySlug(array $attributesGroupName): array
    {
        return $this->repository->findAttributeGroupShopIdIndexedBySlug($attributesGroupName);
    }

    /**
     * @param array $attributesGroupIndexedByName
     * @return array
     */
    public function findAttributesGroup(array $attributesGroupIndexedByName): array
    {
        return $this->repository->findAttributesGroup($attributesGroupIndexedByName);
    }
}