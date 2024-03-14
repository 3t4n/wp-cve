<?php

namespace FluentConnect\App\Http\Controllers;

use FluentConnect\Framework\Request\Request;

class IntegrationProviders extends Controller
{
    public function getAll(Request $request)
    {
        return [
            'providers' => [
                'thrivecart' => [
                    'title' => 'ThriveCart',
                    'logo' => '',
                    'description' => __('Configure ThriveCart and run tasks on order success, refund etc')
                ]
            ]
        ];
    }

    public function find(Request $request, $providerSlug)
    {
        return [
            'info' => [
                'title' => 'ThriveCart',
                'logo' => '',
                'description' => __('Configure ThriveCart and run tasks on order success, refund etc')
            ],
            'settings' => [
                'config' => [
                    'api_key' => ''
                ],
                'status' => false,
                'require_next' => true,
                'btn_text_first' => 'Continue [Setup Webhook]',
                'complete_btn_text' => 'Complete Setup'
            ],
            'config_fields' => [
                'api_key' => [
                    'type' => 'input-text',
                    'label' => 'ThriveCart API Key',
                    'inline_help' => 'You can find the ThriveCart API Key from Account > Settings > API & Webhooks',
                    'data_type' => 'password'
                ]
            ]
        ];
    }
}
