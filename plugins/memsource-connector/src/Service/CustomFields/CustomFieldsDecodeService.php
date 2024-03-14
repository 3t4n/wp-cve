<?php

namespace Memsource\Service\CustomFields;

use Memsource\Service\ExternalPlugin\AcfPlugin;
use Memsource\Service\ExternalPlugin\ElementorPlugin;
use Memsource\Service\ExternalPlugin\SeoPlugin;

class CustomFieldsDecodeService
{
    /** @var AcfPlugin */
    private $acfPlugin;

    /** @var ElementorPlugin */
    private $elementorPlugin;

    /** @var SeoPlugin */
    private $seoPlugin;

    public function __construct(
        AcfPlugin $acfPlugin,
        ElementorPlugin $elementorPlugin,
        SeoPlugin $seoPlugin
    ) {
        $this->acfPlugin = $acfPlugin;
        $this->elementorPlugin = $elementorPlugin;
        $this->seoPlugin = $seoPlugin;
    }

    /**
     * Insert or update custom fields for a post.
     */
    public function savePostCustomFields($postId, array $fields)
    {
        foreach ($fields as $field) {
            $value = $this->decodeFieldIfSerialized($field['value']);
            delete_post_meta($postId, $field['key']);
            add_post_meta($postId, $field['key'], $value);
        }

        $this->elementorPlugin->refreshPost($postId);
    }

    /**
     * Insert or update custom fields for a term.
     */
    public function saveTermCustomFields($termId, array $fields)
    {
        foreach ($fields as $field) {
            $value = $this->decodeFieldIfSerialized($field['value']);
            delete_term_meta($termId, $field['key']);
            add_term_meta($termId, $field['key'], $value);
        }
    }

    /**
     * Decode custom filed if its value is serialized.
     *
     * @param string $value
     * @return string|mixed
     */
    private function decodeFieldIfSerialized($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        $pattern = "|<span class=\"memsource-encoded-field\" data-content=\"([^\s]+)\"></span>|sm";
        preg_match_all($pattern, $value, $matches, PREG_SET_ORDER);

        if (isset($matches[0][1])) {
            return @unserialize(base64_decode(trim($matches[0][1])));
        }

        return $value;
    }

    /**
     * Find custom fields in the string.
     *
     * @param string $string
     * @return array
     */
    public function getCustomFieldsFromString($string): array
    {
        $result = [];

        foreach ($this->getCustomFieldRegexPatterns() as $pattern) {
            preg_match_all($pattern, $string, $matches, PREG_SET_ORDER);

            foreach ($matches ?: [] as $match) {
                $value = $match[3];
                $value = $this->seoPlugin->removeSeoPluginTags($value);

                if ($this->elementorPlugin->isElementorDataField($match[2])) {
                    $value = $this->elementorPlugin->decodeElementorDataField($value);
                } elseif ($this->acfPlugin->isAcfEncodedCustomField($value)) {
                    $value = $this->acfPlugin->decodeAcfCustomField($value);
                }

                $result[] = [
                    'sourceId' => $match[1],
                    'key' => $match[2],
                    'value' => $value,
                ];
            }
        }

        return $result;
    }

    /**
     * @param string $string
     * @return string
     */
    public function cleanStringFromCustomFields($string)
    {
        foreach ($this->getCustomFieldRegexPatterns() as $pattern) {
            $string = preg_replace($pattern, '', $string);
        }

        return $string;
    }

    /**
     * Return an array of regex patterns, which can be used to find the custom field in a text.
     *
     * @return array
     */
    private function getCustomFieldRegexPatterns(): array
    {
        return [
            '/<div data-type="custom_field" data-source-id="(\d+)"><div id="key">(|.+?)<\/div><div id="value">(|.+?)<\/div><\/div>/s', // <- BW compatibility, new translations use custom_field_v2
            '/<div data-type="custom_field_v2" data-source-id="(\d+)" data-key="(|.+?)"><div id="value">(|.+?)<\/div><\/div>/s',
        ];
    }
}
