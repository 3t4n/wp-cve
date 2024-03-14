<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\AttributeGroupMap;
use WcMipConnector\Repository\AttributeGroupMapRepository;

class AttributeGroupMapManager
{
    /** @var AttributeGroupMapRepository */
    protected $attributeGroupMapRepository;

    public function __construct()
    {
        $this->attributeGroupMapRepository = new AttributeGroupMapRepository();
    }

    /**
     * @param array $attributeGroupMapIds
     *
     * @return array
     */
    public function findByAttributeGroupMapIdsIndexedByAttributeGroupMapId(array $attributeGroupMapIds): array
    {
        return $this->attributeGroupMapRepository->findByAttributeGroupMapIdsIndexedByAttributeGroupMapId($attributeGroupMapIds);
    }

    /**
     * @param array $attributeGroupMapIds
     *
     * @return array
     */
    public function findVersionsIndexedByAttributeGroupMapId(array $attributeGroupMapIds): array
    {
        return $this->attributeGroupMapRepository->findVersionsIndexedByAttributeGroupMapId($attributeGroupMapIds);
    }

    /**
     * @param AttributeGroupMap $attributeGroupMap
     *
     * @return bool
     */
    public function save(AttributeGroupMap $attributeGroupMap): bool
    {
        $data = [
            'attribute_group_id' => $attributeGroupMap->attributeGroupId,
            'attribute_group_shop_id' => $attributeGroupMap->shopAttributeGroupId,
            'version' => $attributeGroupMap->version,
            'date_add' => date('Y-m-d H:i:s'),
            'date_update' => null,
        ];

        return $this->attributeGroupMapRepository->save($data);
    }

    /**
     * @param int               $attributeGroupShopId
     * @param AttributeGroupMap $attributeGroupMap
     *
     * @return bool
     */
    public function update(int $attributeGroupShopId, AttributeGroupMap $attributeGroupMap): bool
    {
        $data = [
            'attribute_group_shop_id' => $attributeGroupShopId,
            'version' => $attributeGroupMap->version,
            'date_update' => date('Y-m-d H:i:s'),
        ];

        $where = [
            'attribute_group_id' => $attributeGroupMap->attributeGroupId,
        ];

        return $this->attributeGroupMapRepository->update($data, $where);
    }

    /**
     * @return array
     */
    public function getAttributeGroupIdsIndexedByAttributeMapId(): array
    {
        return $this->attributeGroupMapRepository->getAttributeGroupIdsIndexedByAttributeMapId();
    }

    public function cleanTable(): void
    {
        $this->attributeGroupMapRepository->cleanTable();
    }

    /**
     * @param array $attributeGroupShopIds
     */
    public function deleteByAttributeGroupShopIds(array $attributeGroupShopIds): void
    {
        $this->attributeGroupMapRepository->deleteByAttributeGroupShopIds($attributeGroupShopIds);
    }
}