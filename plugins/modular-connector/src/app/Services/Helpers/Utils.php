<?php

namespace Modular\Connector\Services\Helpers;

use Modular\ConnectorDependencies\Illuminate\Support\Str;
use Modular\ConnectorDependencies\Symfony\Component\HttpFoundation\Response;
use function Modular\ConnectorDependencies\request;

class Utils
{
    /**
     * @return void
     */
    public static function configMaxLimit()
    {
        if (function_exists('set_time_limit')) {
            @set_time_limit(900);
        }

        if (function_exists('ignore_user_abort')) {
            @ignore_user_abort(true);
        }
    }

    public static function isModularRequest()
    {
        global $wp;

        $request = request();

        // When is WP JSON request
        if (!empty($wp->query_vars) && isset($wp->query_vars['rest_route']) && $request->segment(2) === 'modular') {
            return true;
        }

        // When is wp-load.php request
        if ($request->has('origin') && $request->get('origin') === 'mo') {
            return true;
        }

        return defined('DOING_AJAX') && DOING_AJAX &&
            Str::startsWith($request->get('action', ''), 'modular_');
    }

    /**
     * Force response to browser
     *
     * @param mixed $response
     * @return void
     */
    public static function forceResponse($response = '')
    {
        $response = json_encode($response);

        $response = new Response($response, 200, [
            'Content-Type' => 'application/json',
            'Connection' => 'close',
        ]);

        $request = request();
        $response->prepare($request);

        $response->send();
    }
}
