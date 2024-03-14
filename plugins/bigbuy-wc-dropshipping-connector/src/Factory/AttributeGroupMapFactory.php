<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\AttributeGroupMap;

class AttributeGroupMapFactory
{
    /**
     * @param array $attributeGroup
     * @param int $attributeGroupShopId
     * @param string $languageIsoCode
     * @return AttributeGroupMap
     */
    public function create(array $attributeGroup, int $attributeGroupShopId, string $languageIsoCode): AttributeGroupMap
    {
        $attributeGroupMapModel = new AttributeGroupMap();
        $attributeGroupMapModel->attributeGroupId = $attributeGroup['AttributeGroupID'];
        $attributeGroupMapModel->shopAttributeGroupId = $attributeGroupShopId;

        foreach ($attributeGroup['AttributeGroupLangs'] as $attributeGroupLang) {
            if (strtolower($attributeGroupLang['IsoCode']) === strtolower($languageIsoCode)) {
                $attributeGroupMapModel->version = json_encode(
                    [
                        $attributeGroupLang['IsoCode'] => $attributeGroupLang['Version'],
                    ]
                );

                return $attributeGroupMapModel;
            }
        }

        return $attributeGroupMapModel;
    }
}