<?php

namespace WcMipConnector\Factory;

use WcMipConnector\Entity\WCCategoryLang;

defined('ABSPATH') || exit;

class CategoryLangFactory
{
    /**
     * @param array $categoryLangData
     * @param string $languageIsoCode
     * @return WCCategoryLang
     */
    public function create(array $categoryLangData, string $languageIsoCode): WCCategoryLang
    {
        $categoryLangModel = new WCCategoryLang();

        foreach ($categoryLangData as $categoryLang) {
            if (strtolower($categoryLang['IsoCode']) === strtolower($languageIsoCode)) {
                $categoryLangModel->name = $categoryLang['CategoryName'];
                $categoryLangModel->slug = $categoryLang['CategoryURL'];

                return $categoryLangModel;
            }
        }

        return $categoryLangModel;
    }
}