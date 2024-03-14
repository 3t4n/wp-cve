<?php

namespace Memsource\Service\CustomFields;

use Memsource\Dao\ContentSettingsDao;
use Memsource\Dto\ContentSettingsDto;

class CustomFieldsSettingsService
{
    /** @var ContentSettingsDao */
    private $contentSettingsDao;

    public function __construct(ContentSettingsDao $contentSettingsDao)
    {
        $this->contentSettingsDao = $contentSettingsDao;
    }

    /**
     * Find all content settings for custom fields.
     *
     * @return ContentSettingsDto[]
     */
    public function findContentSettings(): array
    {
        $allSettings = $this->contentSettingsDao->findAllContentSettings();

        $result = [];

        foreach ($allSettings as $settings) {
            $result[$settings->getHash()] = $settings;
        }

        return $result;
    }

    /**
     * Save content settings (insert a new settings or update an existing one).
     */
    public function saveContentSettings(string $customFieldName, string $customFieldType, bool $exportForTranslation)
    {
        $hash = CustomFieldsService::calculateHash($customFieldName, $customFieldType);
        $settings = $this->contentSettingsDao->findOneByHash($hash);

        if ($settings === null) {
            $this->contentSettingsDao->insertContentSettings($hash, $customFieldName, $customFieldType, $exportForTranslation);
        } else {
            $this->contentSettingsDao->updateContentSettings($settings->getId(), $customFieldType, $exportForTranslation);
        }
    }
}
