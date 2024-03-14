<?php

namespace MailOptin\NewsmanConnect;

use Authifly\Provider\Newsman;
use Authifly\Storage\OAuthCredentialStorage;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;

class AbstractNewsmanConnect extends AbstractConnect
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Is Newsman successfully connected to?
     *
     * @return bool
     */
    public static function is_connected()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return ! empty($db_options['newsman_access_token']);
    }

    /**
     * @return Newsman
     * @throws \Exception
     *
     */
    public function newsmanInstance()
    {
        $access_token = Connections::instance()->newsman_access_token();
        $user_id      = Connections::instance()->newsman_user_id();

        if (empty($access_token)) {
            throw new \Exception(__('Newsman access token not found.', 'mailoptin'));
        }

        if (empty($user_id)) {
            throw new \Exception(__('Newsman user ID not found.', 'mailoptin'));
        }

        $config = [
            // secret key and callback not needed but authifly requires they have a value hence the MAILOPTIN_OAUTH_URL constant and "__"
            'callback'     => MAILOPTIN_OAUTH_URL,
            'keys'         => ['key' => 'nzmplugin', 'secret' => '__'],
            'user_id'      => $user_id,
            'access_token' => $access_token
        ];

        return new Newsman($config, null, new OAuthCredentialStorage());
    }
}