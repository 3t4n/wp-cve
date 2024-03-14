<?php

namespace Memsource\Service;

use Memsource\Service\TranslationPlugin\ITranslationPlugin;

class LanguageService
{
    /** @var DatabaseService */
    private $databaseService;

    /** @var ITranslationPlugin */
    private $translationPlugin;

    public function __construct(DatabaseService $databaseService, ITranslationPlugin $translationPlugin)
    {
        $this->databaseService = $databaseService;
        $this->translationPlugin = $translationPlugin;
    }

    public function getMappedActiveLanguages(): array
    {
        $mapping = $this->databaseService->findAllLanguageMapping();
        $sourceLanguageCode = $this->translationPlugin->getSourceLanguage();
        if (isset($mapping[$sourceLanguageCode])) {
            $sourceLanguageCode = $mapping[$sourceLanguageCode]['memsource_code'];
        }
        $activeLanguages = $this->translationPlugin->getActiveLanguagesDetails();
        $result = [
            'source' => null,
            'target' => [],
        ];

        foreach ($activeLanguages as $activeLanguage) {
            if (isset($mapping[$activeLanguage['code']])) {
                $activeLanguage['code'] = $mapping[$activeLanguage['code']]['memsource_code'];
            }

            if ($activeLanguage['code'] === $sourceLanguageCode) {
                $result['source'] = $activeLanguage;
            } else {
                $result['target'][] = $activeLanguage;
            }
        }

        return $result;
    }

    public function isValidTargetLanguage($language): bool
    {
        return in_array($language, $this->translationPlugin->getTargetLanguages(), true);
    }
}
