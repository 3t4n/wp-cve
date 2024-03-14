<?php

use Rublon\Core\Exceptions\Api\APIException;
use Rublon\Core\Exceptions\RublonException;
use Rublon\Rublon;
use Rublon_WordPress\Libs\RublonImplemented\RublonAPICheckProtection;
use Rublon_WordPress\Libs\RublonImplemented\RublonAPINewsletterSignup;

/**
 * Class for performing various requests to Rublon servers
 *
 * @author     Rublon Developers http://www.rublon.com
 * @copyright  Rublon Developers http://www.rublon.com
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class RublonRequests
{

    const ERROR_NL_API = 'NEWSLETTER_API_ERROR';
    const ERROR_NL_RUBLON_API = 'NEWSLETTER_RUBLON_API_ERROR';
    const ERROR_ALREADY_SUBSCRIBED = 'NEWSLETTER_ALREADY_SUBSCRIBED_ERROR';
    const ERROR_INVALID_NONCE = 'NEWSLETTER_INVALID_NONCE_ERROR';
    const ERROR_RUBLON_NOT_CONFIGURED = 'RUBLON_NOT_CONFIGURED';

    const SUCCESS_NL_SUBSCRIBED_SUCCESSFULLY = 'NEWSLETTER_SUBSCRIBE_OK';


    /**
     * Rublon instance
     *
     * @var Rublon
     */
    protected $rublon;


    /**
     * Constructor
     */
    public function __construct()
    {

        $this->rublon = RublonHelper::getRublon();

    }


    /**
     * Check mobile app status of a single WP user
     *
     * @param WP_User $user
     * @return string RublonHelper constant
     */
    public function checkMobileStatus($user)
    {
        return RublonHelper::NO;
    }

    public function subscribeToNewsletter($email)
    {

        if (RublonHelper::isSiteRegistered()) {
            $signup = new RublonAPINewsletterSignup($this->rublon, $email);
            try {
                $signup->perform();
                $result = $signup->subscribedSuccessfully();
            } catch (RublonException $e) {
                if ($e instanceof APIException) {
                    $response = $e->getClient()->getResponse();
                    if (!empty($response[RublonAPINewsletterSignup::FIELD_RESULT])
                        && !empty($response[RublonAPINewsletterSignup::FIELD_RESULT]['exception'])
                        && $response[RublonAPINewsletterSignup::FIELD_RESULT]['exception'] == 'AlreadySubscribed_NewsletterException') {
                        $result = self::ERROR_ALREADY_SUBSCRIBED;
                    } else {
                        $result = self::ERROR_NL_API;
                    }
                } else {
                    $result = self::ERROR_NL_RUBLON_API;
                }
            }
            return ($result !== false) ? $result : self::ERROR_NL_RUBLON_API;
        } else {
            return self::ERROR_RUBLON_NOT_CONFIGURED;
        }

    }


}
