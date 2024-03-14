<?php

namespace FluentConnect\App\Hooks\Handlers;

use FluentConnect\App\App;

class WebhookReceiverHandler
{
    public function route()
    {
        if(!isset($_GET['fcon_webhook'])) {
            return false;
        }

        $request = App::make('request');
        $provider = $request->get('provider');

        if(!$provider) {
            return false;
        }



        do_action('fluent_connector_handle_webhook_'.$provider, $request);

        exit(200);
    }
}

