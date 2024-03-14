<?php

namespace Memsource\Service\TranslationPlugin;

use Memsource\Service\OptionsService;

class TranslationPluginProvider
{
    private const DEFAULT_TRANSLATION_PLUGIN = '';

    /** @var OptionsService */
    private $optionsService;

    /** @var ITranslationPlugin[] */
    private $translationPlugins = [];

    public function __construct(OptionsService $optionsService)
    {
        $this->optionsService = $optionsService;
    }

    public function addTranslationPlugin(string $key, ITranslationPlugin $plugin)
    {
        $this->translationPlugins[$key] = $plugin;
    }

    public function addDefaultTranslationPlugin(ITranslationPlugin $plugin)
    {
        $this->translationPlugins[self::DEFAULT_TRANSLATION_PLUGIN] = $plugin;
    }

    public function getTranslationPlugin(): ITranslationPlugin
    {
        $key = $this->optionsService->getActiveTranslationPluginKey();

        if (isset($this->translationPlugins[$key])) {
            return $this->translationPlugins[$key];
        }

        error_log("Memsource Connector: Multilingual plugin '$key' not found.");

        return $this->translationPlugins[self::DEFAULT_TRANSLATION_PLUGIN];
    }
}
