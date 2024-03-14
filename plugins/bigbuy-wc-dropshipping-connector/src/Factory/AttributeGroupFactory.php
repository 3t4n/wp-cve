<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCAttributeGroup;

class AttributeGroupFactory
{
    private const ATTRIBUTE_TYPE = 'select';
    private const ATTRIBUTE_SLUG = 'AttributeGroup_';

    /**
     * @param array $attributeGroup
     * @param string $languageIsoCode
     * @return WCAttributeGroup
     */
    public function create(array $attributeGroup, string $languageIsoCode): WCAttributeGroup
    {
        $attributeGroupModel = new WCAttributeGroup();
        $attributeGroupModel->variation = true;
        $attributeGroupModel->visible = true;
        $attributeGroupModel->slug = strtolower(self::ATTRIBUTE_SLUG . $attributeGroup['AttributeGroupID']);
        $attributeGroupModel->type = self::ATTRIBUTE_TYPE;
        foreach ($attributeGroup['AttributeGroupLangs'] as $attribute) {
            if (strtolower($attribute['IsoCode']) === strtolower($languageIsoCode)) {
                $attributeGroupModel->name = $attribute['AttributeGroupName'];

                return $attributeGroupModel;
            }
        }

        return $attributeGroupModel;
    }
}