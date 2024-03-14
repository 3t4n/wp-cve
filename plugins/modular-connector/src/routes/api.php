<?php

use Modular\Connector\Http\Controllers\HandleController;
use Modular\Connector\Services\Helpers\Utils;
use function Modular\ConnectorDependencies\request;

if (function_exists('add_action')) {
    add_action('init', function () {
        $request = request();

        if ($request->has('origin') && $request->get('origin') === 'mo') {
            $controller = new HandleController();

            if ($request->has('type') && $request->get('type') === 'request') {
                $response = $controller->getHandleRequest();
            }

            if ($request->has('type') && $request->get('type') === 'oauth') {
                $response = $controller->getOauthCallback();
            }

            if (isset($response)) {
                do_action('modular_shutdown');

                if (!empty($response)) {
                    Utils::forceResponse($response);
                }

                exit;
            }
        }
    });
}
