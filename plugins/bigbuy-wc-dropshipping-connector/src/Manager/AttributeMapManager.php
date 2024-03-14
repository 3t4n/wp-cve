<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\AttributeMap;
use WcMipConnector\Repository\AttributeMapRepository;

class AttributeMapManager
{
    /** @var AttributeMapRepository */
    protected $attributeMapRepository;

    public function __construct()
    {
        $this->attributeMapRepository = new AttributeMapRepository();
    }

    /**
     * @param array $attributesMapIds
     *
     * @return array
     */
    public function findByAttributeMapIdsIndexedByAttributeMapId(array $attributesMapIds): array
    {
        return $this->attributeMapRepository->findByAttributeMapIdsIndexedByAttributeMapId($attributesMapIds);
    }

    /**
     * @param array $attributesMapIds
     *
     * @return array
     */
    public function findVersionsIndexedByAttributeMapId(array $attributesMapIds): array
    {
        return $this->attributeMapRepository->findVersionsIndexedByAttributeMapId($attributesMapIds);
    }

    /**
     * @param AttributeMap $attributeMap
     *
     * @return bool
     */
    public function save(AttributeMap $attributeMap): bool
    {
        $data = [
            'attribute_id' => $attributeMap->attributeId,
            'attribute_shop_id' => $attributeMap->shopAttributeId,
            'version' => $attributeMap->version,
            'date_add' => date('Y-m-d H:i:s'),
            'date_update' => null,
        ];

        return $this->attributeMapRepository->save($data);
    }

    /**
     * @param int          $attributeShopId
     * @param AttributeMap $attributeMap
     *
     * @return bool
     */
    public function update(int $attributeShopId, AttributeMap $attributeMap): bool
    {
        $data = [
            'attribute_shop_id' => $attributeShopId,
            'version' => $attributeMap->version,
            'date_update' => date('Y-m-d H:i:s'),
        ];

        $where = [
            'attribute_id' => $attributeMap->attributeId,
        ];

        return $this->attributeMapRepository->update($data, $where);
    }

    public function cleanTable(): void
    {
        $this->attributeMapRepository->cleanTable();
    }

    /**
     * @param array $attributeShopIds
     */
    public function deleteByAttributeShopIds(array $attributeShopIds): void
    {
        $this->attributeMapRepository->deleteByAttributeShopIds($attributeShopIds);
    }
}