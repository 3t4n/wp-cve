<?php

namespace Qodax\CheckoutManager\Http\Controllers;

use Qodax\CheckoutManager\Contracts\HttpResponseInterface;
use Qodax\CheckoutManager\Http\Controller;
use Qodax\CheckoutManager\Http\Request;

if ( ! defined('ABSPATH')) {
    exit;
}

class SettingsController extends Controller
{
    private const OPTION_CHECKOUT_LAYOUT = 'qxcm_column_layout';

    public function getSettings(Request $request): HttpResponseInterface
    {
        return $this->json([
            'success' => true,
            'data' => [
                'column_layout' => get_option(self::OPTION_CHECKOUT_LAYOUT, '2-columns')
            ]
        ]);
    }

    public function save(Request $request): HttpResponseInterface
    {
        $settings = $request->get('settings', []);

        // todo: use validation middleware
        update_option(self::OPTION_CHECKOUT_LAYOUT, $settings['column_layout'] ?? '2-columns');

        return $this->json([
            'success' => true
        ]);
    }
}