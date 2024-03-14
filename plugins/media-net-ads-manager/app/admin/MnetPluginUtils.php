<?php

namespace Mnet\Admin;

use Mnet\Utils\DefaultOptions;
use Mnet\Admin\MnetAuthManager;
use Mnet\MnetDbManager;

class MnetPluginUtils
{
    static $MAIL_URL = 'send-support-mail';
    static $FORGOT_PASSWORD = 'forgot-password';

    public static function sendTroubleFeedbackMail()
    {
        $subject = $_POST['subject'];
        $body = $_POST['mail-content'];
        $email = $_POST['email'];

        $url = MNET_API_ENDPOINT . self::$MAIL_URL;

        $domain = self::getDomain();
        $httpHost = $_SERVER['HTTP_HOST'];
        if ($domain === $httpHost) {
            $httpHost = null;
        }

        $response = \wp_remote_post(
            $url,
            array_merge(
                DefaultOptions::$MNET_API_DEFAULT_ARGS,
                array(
                    'method' => 'POST',
                    'body' => array_merge(array(
                        'subject' => $subject,
                        'body' => $body,
                        'domain' => $domain,
                        'httpHost' => $httpHost,
                        'email' => $email,
                        'siteData' => self::getServerInfo()
                    ), self::getClientInfo())
                )
            )
        );
        self::sendAjaxResponse($response, 'Mail sent successfully', 'Failed to send mail');
    }

    public static function forgotPassword()
    {
        $email = $_POST['email'];
        $url = MNET_API_ENDPOINT . self::$FORGOT_PASSWORD;
        $response = \wp_remote_post(
            $url,
            array_merge(
                DefaultOptions::$MNET_API_DEFAULT_ARGS,
                array(
                    'method' => 'POST',
                    'body' => array(
                        'email' => $email
                    )
                )
            )
        );
        self::sendAjaxResponse($response, 'Reset password link has been sent to your email', 'Failed to send reset password link!');
    }

    public static function sendAjaxResponse($response, $successMessage, $errorMessage)
    {
        $body = !\is_wp_error($response) && isset($response['body']) ? json_decode($response['body'], true) : array();
        if (!$response || \is_wp_error($response) || (isset($body['sent']) && $body['sent'] == false)) {
            \wp_send_json_error(array(
                'status' => 'error',
                'message' => isset($body['message']) ? $body['message'] : $errorMessage
            ), 400);
        } else {
            \wp_send_json(array(
                'status' => 'success',
                'message' => isset($body['message']) ? $body['message'] : $successMessage
            ), 200);
        }
    }

    public static function getUserEmailId()
    {
        try {
            $userId = \get_current_user_id();
            $userData = \get_userdata($userId);
            return $userData->data->user_email;
        } catch (\Exception $e) {
        }
        return null;
    }

    public static function getDomain()
    {
        return preg_replace('#^https?://#', '', \get_home_url());
    }

    public static function getCurrentThemeName()
    {
        $wp_theme = \wp_get_theme();
        if (empty($wp_theme)) return 'Unknown';
        return $wp_theme->get('Name');
    }

    public static function getServerInfo()
    {
        $list = array();
        $list['php'] = phpversion();
        include(\ABSPATH . \WPINC . '/version.php');
        $list['wp'] = $wp_version;
        $list['db'] = MnetDbManager::getDbVersion();
        $list['plugin'] = MNET_PLUGIN_VERSION;
        $list['server'] = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'Unknown';
        return $list;
    }

    public static function getClientInfo()
    {
        return array(
            'userAgent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown',
        );
    }

    public static function sendErrorReport($data)
    {
        $homeUrl = \get_home_url();
        $url = MNET_API_ENDPOINT . 'report-error';
        try {
            \wp_remote_post(
                $url,
                array_merge(
                    DefaultOptions::$MNET_API_DEFAULT_ARGS,
                    array(
                        'method' => 'POST',
                        'body' => array_merge(
                            \mnet_user()->info(),
                            array(
                                'domain' => $homeUrl,
                                'siteData' => self::getServerInfo()
                            ),
                            self::getClientInfo(),
                            $data
                        )
                    )
                )
            );
        } catch (\Exception $e) {
        }
    }
}
