<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\CategoryMap;

class CategoryMapFactory
{
    /**
     * @param array $category
     * @param int $categoryId
     * @param string $languageIsoCode
     * @return CategoryMap
     */
    public function create(array $category, int $categoryId, string $languageIsoCode): CategoryMap
    {
        $categoryMapModel = new CategoryMap();
        $categoryMapModel->categoryId = $category['CategoryID'];
        $categoryMapModel->shopCategoryId = $categoryId;

        foreach ($category['CategoryLangs'] as $categoryLang) {
            if (strtolower($categoryLang['IsoCode']) === strtolower($languageIsoCode)) {
                $categoryMapModel->version = json_encode(
                    [
                        'version' => $category['Version'],
                        $categoryLang['IsoCode'] => $categoryLang['Version'],
                    ]
                );
            }
        }

        return $categoryMapModel;
    }
}