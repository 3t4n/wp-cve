<?php

namespace Rublon_WordPress\Libs\RublonImplemented;

use Rublon\Core\Api\RublonAPIClient;
use Rublon\Core\RublonAuthParams;
use Rublon\Core\RublonConsumer;


/**
 * API request: Check Rublon protection.
 *
 */
class RublonAPICheckProtection extends RublonAPIClient
{


    /**
     * Name of the field with users data.
     */
    const FIELD_USERS = 'users';

    /**
     * Users emails to check
     */
    const FIELD_EMAILS = 'emails';

    /**
     * Logged in user email
     */
    const FIELD_EMAIL = 'email';

    /**
     * Logged in user email
     */
    const FIELD_PROTECTION = 'protection';

    /**
     * Email address also taken into account when checking protection status
     */
    const FIELD_INCLUDING_EMAIL = 'includingEmail';

    /**
     * Dummy email for ping request to check
     * the Rublon server availability when user's email
     * is not accessible.
     */
    const EMAIL_PING = 'ping@rublon.com';

    /**
     * URL path of the request.
     *
     * @var string
     */
    protected $urlPath = '/api/sdk/checkProtection';

    /**
     * Constructor.
     *
     * @param Rublon $rublon
     */
    public function __construct(RublonConsumer $rublon, $userId, $userEmail, $usersEmails = array())
    {

        parent::__construct($rublon);

        if (!$rublon->isConfigured()) {
            trigger_error(RublonConsumer::TEMPLATE_CONFIG_ERROR, E_USER_ERROR);
        }

        if (empty($userEmail)) {
            $userEmail = self::EMAIL_PING;
        }

        // Set request URL and parameters
        $url = $rublon->getAPIDomain() . $this->urlPath;
        $params = array(
            RublonAuthParams::FIELD_SYSTEM_TOKEN => $rublon->getSystemToken(),
            self::FIELD_EMAILS => !empty($usersEmails) ? $usersEmails : array($userEmail),
            self::FIELD_EMAIL => $userEmail,
        );

        $this->setRequestURL($url)->setRequestParams($params);

    }

    /**
     * Append user to check.
     *
     * @param string $userId
     * @param string $userEmail
     * @return RublonAPICheckProtection
     */
    public function appendUser($userId, $userEmail)
    {
        if (empty($userEmail)) {
            $userEmail = self::EMAIL_PING;
        }
        $this->params[self::FIELD_EMAILS][] = $userEmail;
        return $this;
    }


    /**
     * Check protection status.
     *
     * @return boolean
     */
    public function isProtectionEnabled($userId)
    {
        return (
            !empty($this->response[self::FIELD_RESULT])
            && !empty($this->response[self::FIELD_RESULT][$userId])
        );
    }

    public function isUserProtected($userEmail)
    {
        if (!empty($this->response[self::FIELD_RESULT])) {
            foreach ($this->response[self::FIELD_RESULT] as $user) {
                if ($user[self::FIELD_EMAIL] == $userEmail)
                    return $user[self::FIELD_PROTECTION];
            }
        }
    }


}
