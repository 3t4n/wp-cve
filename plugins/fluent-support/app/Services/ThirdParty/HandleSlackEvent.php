<?php

namespace FluentSupport\App\Services\ThirdParty;

use Exception;
use FluentSupport\App\App;
use FluentSupportPro\App\Services\Integrations\Slack\SlackNotification;

class HandleSlackEvent
{

    /**
     * handleEvent method is responsible for handling slack events
     * @param string $token
     * @return bool
     * @throws Exception
     */

    public function handleEvent ( $token )
    {
        $this->verifyProVersion();
        $this->validateToken($token);

        $request = App::getInstance('request');

        if ($request->get('type') == 'url_verification') {
            echo wp_kses_post($request->get('challenge'));
        }

        return (new SlackNotification())->processSlackEvent($request->get('event'));
    }



    /**
     * verifyProVersion method will check if the pro version is installed or not
     * @throws Exception
     * @return boolean | Exception
     */
    private function verifyProVersion ()
    {
        if (!defined('FLUENTSUPPORTPRO')) {
            throw new \Exception('Slack Integration requires pro version of Fluent Support', 400);
        }

        return true;
    }

    /**
     * validateToken method will check if the token is valid or not
     * @param string $token
     * @throws Exception
     * @return boolean | Exception
     */
    private function validateToken ($token)
    {
        if (\FluentSupportPro\App\Services\Integrations\Slack\SlackHelper::getWebhookToken() != $token) {
            throw new \Exception('Bot Token could not be verified', 404);
        }
        return true;
    }
}
