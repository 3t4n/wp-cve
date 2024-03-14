<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\VariationMap;
use WcMipConnector\Repository\VariationMapRepository;

class VariationMapManager
{
    /** @var VariationMapRepository */
    protected $variationMapRepository;

    public function __construct()
    {
        $this->variationMapRepository = new VariationMapRepository();
    }

    /**
     * @param array $variationMapIds
     *
     * @return array
     */
    public function findByVariationMapIdsIndexedByVariationMapId(array $variationMapIds): array
    {
        return $this->variationMapRepository->findByVariationMapIdsIndexedByVariationMapId($variationMapIds);
    }

    /**
     * @param string[] $variationIds
     * @return array<string, string>
     */
    public function findVariationIdsIndexedByVariationShopId(array $variationIds): array
    {
        return $this->variationMapRepository->findVariationIdsIndexedByVariationShopId($variationIds);
    }

    /**
     * @param VariationMap $variationMap
     *
     * @return bool
     */
    public function save(VariationMap $variationMap): bool
    {
        $data = [
            'variation_id' => $variationMap->variationId,
            'variation_shop_id' => $variationMap->shopVariationId,
            'date_add' => date('Y-m-d H:i:s'),
            'date_update' => null,
        ];

        return $this->variationMapRepository->save($data);
    }

    /**
     * @param int $variationShopId
     * @param VariationMap $variationMap
     *
     * @return bool
     */
    public function update(int $variationShopId, VariationMap $variationMap): bool
    {
        $data = [
            'variation_shop_id' => $variationShopId,
            'date_update' => date('Y-m-d H:i:s'),
        ];

        $where = [
            'variation_id' => $variationMap->variationId,
        ];

        return $this->variationMapRepository->update($data, $where);
    }

    /**
     * @return array
     */
    public function getVariationsShopIndexedByVariationShopId(): array
    {
        return $this->variationMapRepository->getVariationsShopIndexedByVariationShopId();
    }
}