<?php

namespace Memsource\Service\CustomFields;

use Memsource\Dao\MetaDao;
use Memsource\Parser\ParserResult;
use Memsource\Service\ExternalPlugin\AcfPlugin;
use Memsource\Service\ExternalPlugin\ElementorPlugin;
use Memsource\Service\ExternalPlugin\SeoPlugin;
use Memsource\Service\PlaceholderService;
use Memsource\Utils\AuthUtils;

class CustomFieldsEncodeService
{
    private const RESULT_CUSTOM_FIELDS = 'customFields';
    private const RESULT_PLACEHOLDERS = 'placeholders';

    /** @var AcfPlugin */
    private $acfPlugin;

    /** @var ElementorPlugin */
    private $elementorPlugin;

    /** @var SeoPlugin */
    private $seoPlugin;

    /** @var AuthUtils */
    private $authUtils;

    /** @var PlaceholderService */
    private $placeholderService;

    /** @var CustomFieldsSettingsService */
    private $customFieldsSettingsService;

    /** @var CustomFieldsService */
    private $customFieldsService;

    /** @var MetaDao */
    private $metaDao;

    public function __construct(
        AcfPlugin $acfPlugin,
        ElementorPlugin $elementorPlugin,
        SeoPlugin $seoPlugin,
        AuthUtils $authUtils,
        PlaceholderService $placeholderService,
        CustomFieldsSettingsService $customFieldsSettingsService,
        CustomFieldsService $customFieldsCheckerService,
        MetaDao $metaDao
    ) {
        $this->acfPlugin = $acfPlugin;
        $this->elementorPlugin = $elementorPlugin;
        $this->seoPlugin = $seoPlugin;
        $this->authUtils = $authUtils;
        $this->placeholderService = $placeholderService;
        $this->customFieldsSettingsService = $customFieldsSettingsService;
        $this->customFieldsService = $customFieldsCheckerService;
        $this->metaDao = $metaDao;
    }

    /**
     * Encode custom fields attached to the post.
     *
     * @param int $postId
     *
     * @return ParserResult
     */
    public function encodeCustomFields($type, $id): ParserResult
    {
        $customFieldsResultMap = $this->findCustomFields($type, $id);

        if (!empty($customFieldsResultMap[self::RESULT_CUSTOM_FIELDS])) {
            return new ParserResult(
                $this->customFieldsToHTML($customFieldsResultMap[self::RESULT_CUSTOM_FIELDS]),
                $customFieldsResultMap[self::RESULT_PLACEHOLDERS]
            );
        }

        return new ParserResult;
    }

    private function findCustomFields($type, $id): array
    {
        $response = [];
        $result = $this->findCustomFieldsInternal($type, $id);
        $fieldSettings = $this->customFieldsSettingsService->findContentSettings();

        foreach ($result[self::RESULT_CUSTOM_FIELDS] ?: [] as $field) {
            $key = $field['meta_key'];
            $hash = CustomFieldsService::calculateHash($key, $type);
            if (!isset($fieldSettings[$hash]) || $fieldSettings[$hash]->exportForTranslation()) {
                $response[$hash] = [
                    'sourceId' => $field['meta_id'],
                    'key' => $key,
                    'value' => $field['meta_value'],
                ];
            }
        }

        return [
            self::RESULT_CUSTOM_FIELDS => $response,
            self::RESULT_PLACEHOLDERS => $result[self::RESULT_PLACEHOLDERS],
        ];
    }

    private function findCustomFieldsInternal($type, $id): array
    {
        $customFields = $this->metaDao->findByIdAsArray($type, $id);
        $placeholders = [];

        foreach ($customFields as $key => $row) {
            $metaValue = $row['meta_value'];
            $customFields[$key]['meta_value'] = (string) $metaValue;

            if ($this->elementorPlugin->isElementorCustomField($type, $id, $row['meta_key'])) {
                $token = $this->authUtils->generateRandomToken();
                $placeholders[$token] = $this->elementorPlugin->commentElementorDataField($metaValue);
                $customFields[$key]['meta_value'] = $this->elementorPlugin->encodeElementorDataField($metaValue);
                $customFields[$key]['meta_value'] .= $this->placeholderService->createPlaceholderTag($token);
            } elseif ($this->acfPlugin->isAcfCustomField($metaValue)) {
                $token = $this->authUtils->generateRandomToken();
                $placeholders[$token] = $this->acfPlugin->commentAcfCustomField($metaValue);
                $customFields[$key]['meta_value'] = $this->acfPlugin->encodeAcfCustomField($metaValue);
                $customFields[$key]['meta_value'] .= $this->placeholderService->createPlaceholderTag($token);
            } elseif ($this->customFieldsService->isSystemCustomField($row['meta_key'])) {
                unset($customFields[$key]);
            }
        }

        return [
            self::RESULT_CUSTOM_FIELDS => $customFields,
            self::RESULT_PLACEHOLDERS => $placeholders,
        ];
    }

    /**
     * Convert array of custom fields to the html string.
     *
     * @param array $customFields
     * @return string
     */
    private function customFieldsToHTML(array $customFields): string
    {
        $html = '';

        foreach ($customFields ?: [] as $field) {
            $html .= $this->customFieldToHTML($field['sourceId'], $field['key'], $field['value']);
        }

        return $html;
    }

    /**
     * Convert custom filed to the html string.
     *
     * @param int $sourceId
     * @param string $key
     * @param string $value
     * @return string
     */
    private function customFieldToHTML($sourceId, $key, $value): string
    {
        $value = (string) $value;
        $key = (string) $key;

        $value = $this->encodeFieldIfSerialized($value);
        $value = $this->seoPlugin->encodeSeoPluginVariables($key, $value);

        return sprintf('<div data-type="custom_field_v2" data-source-id="%d" data-key="%s"><div id="value">%s</div></div>', $sourceId, $key, $value);
    }

    /**
     * Encode custom filed if its value is serialized.
     *
     * @param string $value
     * @return string
     */
    private function encodeFieldIfSerialized(string $value)
    {
        if (maybe_unserialize($value) !== $value) {
            return '<span class="memsource-encoded-field" data-content="' . base64_encode($value) . '"></span>';
        }

        return $value;
    }
}
