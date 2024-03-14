<?php

namespace MailOptin\CtctConnect;

use Authifly\Provider\ConstantContact;
use Authifly\Storage\OAuthCredentialStorage;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;

class AbstractCtctConnect extends AbstractConnect
{
    /** @var Connections */
    protected $api_key = '89kxrwcayg4ntpb2vm3ywzfp';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Is Constant Contact successfully connected to?
     *
     * @return bool
     */
    public static function is_connected()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return ! empty($db_options['ctct_access_token']);
    }

    /**
     * Return instance of ConstantContact class.
     *
     * @return ConstantContact
     * @throws \Exception
     *
     */
    public function ctctInstance()
    {
        $access_token = Connections::instance()->ctct_access_token();

        if (empty($access_token)) {
            throw new \Exception(__('ConstantContact (Legacy) access token not found.', 'mailoptin'));
        }

        $config = [
            // secret key and callback not needed but authifly requires they have a value hence the MAILOPTIN_OAUTH_URL constant and "__"
            'callback'     => MAILOPTIN_OAUTH_URL,
            'keys'         => ['key' => $this->api_key, 'secret' => '__'],
            'access_token' => $access_token
        ];

        // some website has session disabled. Since we do not require session to work, we are swapping it out for OAuthCredentialStorage.
        return new ConstantContact($config, null, new OAuthCredentialStorage());
    }
}