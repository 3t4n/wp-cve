<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCAttribute;

class AttributeFactory
{
    private const ATTRIBUTE_SLUG = 'Attribute_';

    public function create(array $attribute, string $languageIsoCode): WCAttribute
    {
        $attributeModel = new WCAttribute();
        $attributeModel->slug = strtolower(self::ATTRIBUTE_SLUG.$attribute['AttributeID']);
        foreach ($attribute['AttributeLangs'] as $attributeLang) {
            if (strtolower($attributeLang['IsoCode']) === strtolower($languageIsoCode)) {
                $attributeModel->name = $attributeLang['AttributeName'];

                return $attributeModel;
            }
        }

        return $attributeModel;
    }
}