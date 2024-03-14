<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCTagLang;
use WcMipConnector\Entity\WCTag;

class TagFactory
{
    public const TAG_PREFIX = 'tag_';

    /**
     * @param array $tagData
     * @param string $languageIsoCode
     * @return WCTag
     */
    public function create(array $tagData, string $languageIsoCode): WCTag
    {
        $tagModel = new WCTag();
        $tagLangModel = $this->createLang($tagData['TagLangs'], $languageIsoCode);
        $tagModel->name = $tagLangModel->name;
        $tagModel->slug = strtolower(self::TAG_PREFIX.$tagData['TagID']);

        return $tagModel;
    }

    /**
     * @param array $tagData
     * @param string $languageIsoCode
     * @return WCTagLang
     */
    private function createLang(array $tagData, string $languageIsoCode): WCTagLang
    {
        $tagLangModel = new WCTagLang();

        if (empty($tagData) || empty($languageIsoCode)) {
            return $tagLangModel;
        }

        foreach ($tagData as $tagLang) {
            if (strtolower($tagLang['IsoCode']) === strtolower($languageIsoCode)) {
                $tagLangModel->name = $tagLang['TagName'];

                return $tagLangModel;
            }
        }
        return $tagLangModel;
    }
}