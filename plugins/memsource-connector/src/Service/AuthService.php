<?php

namespace Memsource\Service;

use Memsource\Service\TranslationPlugin\ITranslationPlugin;

class AuthService
{
    /** @var OptionsService */
    private $optionsService;

    /** @var ITranslationPlugin */
    private $translationPlugin;

    public function __construct(OptionsService $optionsService, ITranslationPlugin $translationPlugin)
    {
        $this->optionsService = $optionsService;
        $this->translationPlugin = $translationPlugin;
    }

    public function checkAuth($token = null): array
    {
        if (!$token && isset($_GET['token'])) {
            $token = $_GET['token'];
        }

        if ($token !== $this->optionsService->getToken()) {
            return ['error' => 'Invalid token: ' . $token];
        }

        if (!$this->translationPlugin->isPluginActive()) {
            return ['error' => $this->translationPlugin->getName() . ' plugin not found.'];
        }

        return ['token' => $token];
    }
}
