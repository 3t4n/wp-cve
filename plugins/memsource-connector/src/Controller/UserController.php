<?php

namespace Memsource\Controller;

use Memsource\Service\AuthService;
use Memsource\Service\LanguageService;
use Memsource\Service\OptionsService;
use WP_REST_Server;

class UserController
{
    /** @var OptionsService */
    private $optionsService;

    /** @var AuthService */
    private $authService;

    /** @var LanguageService */
    private $languageService;

    public function __construct(
        OptionsService $optionsService,
        AuthService $authService,
        LanguageService $languageService
    ) {
        $this->optionsService = $optionsService;
        $this->authService = $authService;
        $this->languageService = $languageService;
    }

    public function registerRestRoutes()
    {
        $namespace = $this->optionsService->getRestNamespace();
        register_rest_route($namespace, '/wpml', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'memsourceGetWpmlData'],
            'permission_callback' => '__return_true',
        ]);
        register_rest_route($namespace, '/user', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'memsourceGetAdminUser'],
            'permission_callback' => '__return_true',
        ]);
    }

    public function memsourceGetWpmlData()
    {
        $checkResponse = $this->authService->checkAuth();
        if (array_key_exists('error', $checkResponse)) {
            return $checkResponse;
        }
        return rest_ensure_response(
            ['languages' => $this->languageService->getMappedActiveLanguages()]
        );
    }

    public function memsourceGetAdminUser()
    {
        $checkResponse = $this->authService->checkAuth();
        if (array_key_exists('error', $checkResponse)) {
            return $checkResponse;
        }
        $admin = get_user_by('ID', get_option('memsource_admin_user'));
        return [
            'ID' => $admin->ID,
            'display_name' => $admin->display_name,
            'user_login' => $admin->user_login,
            'user_email' => $admin->user_email,
        ];
    }
}
