<?php

namespace Memsource\Service\Content;

use Memsource\Service\CustomFields\CustomFieldsDecodeService;
use Memsource\Service\LanguageService;
use Memsource\Service\TransformService;
use Memsource\Service\TranslationPlugin\ITranslationPlugin;
use Memsource\Utils\ArrayUtils;

abstract class AbstractContentService
{
    /** @var LanguageService */
    protected $languageService;

    /** @var ITranslationPlugin */
    protected $translationPlugin;

    /** @var TransformService */
    protected $transformService;

    /** @var CustomFieldsDecodeService */
    protected $customFieldsDecodeService;

    public function __construct(
        LanguageService $languageService,
        ITranslationPlugin $translationPlugin,
        TransformService $transformService,
        CustomFieldsDecodeService $customFieldsDecodeService
    ) {
        $this->languageService = $languageService;
        $this->translationPlugin = $translationPlugin;
        $this->transformService = $transformService;
        $this->customFieldsDecodeService = $customFieldsDecodeService;
    }

    /**
     * Check arguments before saving a translation.
     *
     * @param $args array
     * @throws \InvalidArgumentException
     */
    protected function checkArgsBeforeSaveTranslation(array $args)
    {
        ArrayUtils::checkKeyExists($args, ['lang']);

        if (!$this->languageService->isValidTargetLanguage($args['lang'])) {
            throw new \InvalidArgumentException(sprintf('Language \'%s\' is not active.', $args['lang']));
        }
    }
}
