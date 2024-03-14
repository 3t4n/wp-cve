<?php

namespace Memsource\Service\ExternalPlugin;

/**
 * Advanced Custom Fields (ACF) plugin support.
 * This class is responsible for getting (and storing) translatable text from (to) custom fields created in ACF.
 * Fields created by ACF can also be used for translation workflows.
 */
class AcfPlugin
{
    private const ACF_PATTERN_ENCODE = '<div data-type="memsource_acf_field" data-key="%s">%s</div>';
    private const ACF_PATTERN_DECODE = '/<div data-type="memsource_acf_field" data-key="([_a-z]+)">(|.+?)<\/div>/sm';

    private const ACF_RESULT_PATTERN_ENCODE = '<!--_acf_field_%s-->';
    private const ACF_RESULT_PATTERN_DECODE = '|.+</div><!--_acf_field_(.+)-->|sm';

    private const ACF_TRANSLATABLE_FIELD = 'title';

    public function isAcfCustomField($value): bool
    {
        if (strpos($value, '"' . self::ACF_TRANSLATABLE_FIELD . '"') === false) {
            return false;
        }

        $unserialized = maybe_unserialize($value);

        return is_array($unserialized) && ($unserialized !== $value);
    }

    public function commentAcfCustomField($metaValue): string
    {
        return sprintf(self::ACF_RESULT_PATTERN_ENCODE, $metaValue);
    }

    public function encodeAcfCustomField($metaValue): string
    {
        $html = '';
        $decodedMetaValue = unserialize($metaValue);

        foreach ($decodedMetaValue as $fieldName => $fieldValue) {
            if ($fieldName === self::ACF_TRANSLATABLE_FIELD) {
                $html .= sprintf(self::ACF_PATTERN_ENCODE, $fieldName, $fieldValue);
            }
        }

        return $html;
    }

    public function isAcfEncodedCustomField(string $value): bool
    {
        return strpos($value, '<!--_acf_field_') !== false;
    }

    public function decodeAcfCustomField($translations)
    {
        preg_match(self::ACF_RESULT_PATTERN_DECODE, $translations, $json);
        $elementorData = unserialize($json[1]);

        preg_match_all(self::ACF_PATTERN_DECODE, $translations, $matches, PREG_SET_ORDER);

        foreach ($matches ?: [] as $match) {
            $this->replaceTranslation($elementorData, $match[1], $match[2]);
        }

        return $elementorData;
    }

    private function replaceTranslation(&$elementorData, $translationKey, $translation)
    {
        foreach ($elementorData ?? [] as $itemKey => $item) {
            if ($itemKey === $translationKey) {
                $elementorData[$itemKey] = $translation;
            }
        }
    }

    /**
     * List all available custom fields created in ACF.
     *
     * @return string[]
     */
    public function getAllAcfFields(): array
    {
        $fields = [];

        global $wpdb;

        $query = "SELECT post_excerpt as 'field' FROM $wpdb->posts where post_type = 'acf-field'";
        $results = $wpdb->get_results($query, ARRAY_A);

        foreach ($results ?? [] as $item) {
            $fields[] = $item['field'];
        }

        return $fields;
    }
}
