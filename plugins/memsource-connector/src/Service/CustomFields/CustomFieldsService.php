<?php

namespace Memsource\Service\CustomFields;

use Memsource\Dao\MetaDao;
use Memsource\Dto\MetaKeyDto;
use Memsource\Service\ExternalPlugin\SeoPlugin;
use Memsource\Service\TranslationWorkflowService;
use Memsource\Utils\StringUtils;

class CustomFieldsService
{
    /** @var SeoPlugin */
    private $seoPlugin;

    /** @var TranslationWorkflowService */
    private $translationWorkflowService;

    /** @var CustomFieldsSettingsService */
    private $customFieldsSettingsService;

    /** @var MetaDao */
    private $metaDao;

    public function __construct(
        SeoPlugin $seoPlugin,
        TranslationWorkflowService $translationWorkflowService,
        CustomFieldsSettingsService $customFieldsSettingsService,
        MetaDao $metaDao
    ) {
        $this->seoPlugin = $seoPlugin;
        $this->translationWorkflowService = $translationWorkflowService;
        $this->customFieldsSettingsService = $customFieldsSettingsService;
        $this->metaDao = $metaDao;
    }

    /**
     * Calculate custom fields size.
     */
    public function calculateCustomFieldsSize($postId): int
    {
        $postMeta = get_post_meta($postId);
        $size = 0;

        foreach (is_array($postMeta) ? $postMeta : [] as $metaName => $metaValues) {
            if (isset($metaName[0]) && !$this->isSystemCustomField($metaName[0])) {
                foreach ($metaValues as $metaValue) {
                    if ($metaValue != '') {
                        $size += StringUtils::size($metaValue);
                    }
                }
            }
        }

        return $size;
    }

    /**
     * Check whether a custom field starts with '_' and can be exported for translation.
     */
    public function isSystemCustomField(string $customFieldName): bool
    {
        $firstChar = substr($customFieldName, 0, 1);

        $translationWorkflowFields = [
            $this->translationWorkflowService->getValue(TranslationWorkflowService::FIELD_NAME),
            $this->translationWorkflowService->getValue(TranslationWorkflowService::FIELD_NAME_TARGET_LANGUAGES),
        ];

        return
            ($firstChar === '_' && !$this->seoPlugin->isSeoPluginCustomField($customFieldName))
            || in_array($customFieldName, $translationWorkflowFields, true);
    }

    /**
     * Return custom field config for debug purposes.
     */
    public function getCustomFieldsDump(): array
    {
        $settings = [];

        foreach ($this->customFieldsSettingsService->findContentSettings() as $field) {
            $settings[$field->getHash()] = $field->exportForTranslation() ? 'on' : 'off';
        }

        $result = [];

        foreach ($this->metaDao->findAllMetaKeys() ?: [] as $field) {
            $result[$field->getType()][$field->getName()] = ($settings[$field->getHash()] ?? 'on');
        }

        return $result;
    }

    /**
     * @param string $customFieldName
     * @param string $customFieldType MetaKeyDto::TYPE_POST or MetaKeyDto::TYPE_TERM
     *
     * @return string
     */
    public static function calculateHash(string $customFieldName, string $customFieldType): string
    {
        return StringUtils::stringToHex(
            $customFieldName . ($customFieldType === MetaKeyDto::TYPE_POST ? '' : $customFieldType)
        );
    }
}
