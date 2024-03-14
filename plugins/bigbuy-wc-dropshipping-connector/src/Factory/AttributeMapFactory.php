<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\AttributeMap;

class AttributeMapFactory
{
    /**
     * @param array $attributeData
     * @param int $attributeShopId
     * @param string $languageIsoCode
     * @return AttributeMap
     */
    public function create(array $attributeData, int $attributeShopId, string $languageIsoCode): AttributeMap
    {
        $attributeMapModel = new AttributeMap();
        $attributeMapModel->attributeId = $attributeData['AttributeID'];
        $attributeMapModel->shopAttributeId = $attributeShopId;

        foreach ($attributeData['AttributeLangs'] as $attributeLang) {
            if (strtolower($attributeLang['IsoCode']) === strtolower($languageIsoCode)) {
                $attributeMapModel->version = json_encode(
                    [
                        $attributeLang['IsoCode'] => $attributeLang['Version'],
                    ]
                );

                return $attributeMapModel;
            }
        }

        return $attributeMapModel;
    }
}