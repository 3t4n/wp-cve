<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\TagMap;

class TagMapFactory
{
    /**
     * @param array $tag
     * @param int|null $tagId
     * @param string $languageIsoCode
     * @return TagMap
     */
    public function create(array $tag, int $tagId = null, string $languageIsoCode): TagMap
    {
        $tagMapModel = new TagMap();
        $tagMapModel->tagId = $tag['TagID'];
        $tagMapModel->shopTagId = $tagId;

        foreach ($tag['TagLangs'] as $tagLang) {
            if (strtolower($tagLang['IsoCode']) === strtolower($languageIsoCode)) {
                $tagMapModel->version = json_encode(
                    [
                        $tagLang['IsoCode'] => $tagLang['Version']
                    ]
                );

                return $tagMapModel;
            }
        }

        return $tagMapModel;
    }
}