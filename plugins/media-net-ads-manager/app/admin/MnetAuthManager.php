<?php

namespace Mnet\Admin;

use Arr;
use Mnet\Admin\MnetPluginUtils;
use Mnet\Admin\MnetLogManager;
use Mnet\Admin\MnetAdTag;
use Mnet\Admin\MnetModuleManager;
use Mnet\Utils\DefaultOptions;
use Mnet\Utils\MnetAdSlot;
use Mnet\Utils\Response;

class MnetAuthManager
{
    public static function authenticateUser()
    {
        if (!isset($_POST['email']) || empty($_POST['email'])) {
            \wp_send_json_error('Email is required', 422);
        }

        if (!isset($_POST['password']) || empty($_POST['password'])) {
            \wp_send_json_error('Password is required', 422);
        }
        try {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $query = $_POST['query'];

            $response = self::callAuthApi($email, $password, $query);

            if ($response['status'] != 'success') {
                \wp_send_json_error(
                    array(
                        'message' => isset($response['message']) ? $response['message'] : 'Error occurred. Please try again.',
                        'info' =>  isset($response['info']) ? $response['info'] : null
                    ),
                    400
                );
            }
            MnetOptions::setMnetOptions($email, $response);

            self::updateAdtags($response);
            self::generateLoginResponse($response);
        } catch (\Exception $e) {
            self::logError($email, $e->getMessage());
            \wp_send_json_error(
                $e->getMessage(),
                500
            );
        }
    }

    private static function logError($email, $message)
    {
        $details = array_merge(
            array(
                'email' => $email,
                'domain' => MnetPluginUtils::getDomain(),
                'error' => $message
            ),
            MnetPluginUtils::getServerInfo(),
            MnetPluginUtils::getClientInfo()
        );
        MnetLogManager::logEvent('Login Error', json_encode($details));
    }

    public static function generateLoginResponse($apiResponse)
    {
        setcookie('mnet_authenticated', 1, strtotime('+7 days'), '/');
        \wp_send_json(array(
            'status' => 'SUCCESS',
            'message' => 'AUTHENTICATED',
            'slotCount' => MnetAdSlot::count(),
            'adtagCount' => MnetAdTag::count(),
            'siteRejected' => \Arr::get($apiResponse, 'account_info.eapRejected'),
            'isEap' => \Arr::get($apiResponse, 'account_info.isEap'),
            'siteStatus' => \mnet_site()->status,
            'availableAdSizes' => MnetAdTag::getAvailableSizes(),
            'modules' => MnetModuleManager::getModules(),
            'adstxtParserPath' => \plugin_dir_url(__DIR__) . mnet_normalize_chunks('/../../dist/js/mnetAdstxtParseWorker.js'),
        ));
    }

    public static function callAuthApi($email, $password, $query)
    {
        $url = MNET_API_ENDPOINT . "login";
        $payload = array_merge(array(
            'email' => $email,
            'password' => $password,
            'query' => $query,
            'domain' => MnetPluginUtils::getDomain(),
            'httpHost' => Arr::get($_SERVER, 'HTTP_HOST'),
            'options' => json_encode(Arr::get($_POST, 'options'))
        ), MnetPluginUtils::getClientInfo());

        $response = \wp_remote_post(
            $url,
            array_merge(
                DefaultOptions::$MNET_API_DEFAULT_ARGS,
                array(
                    'method' => 'POST',
                    'body' => $payload,
                )
            )
        );
        if (\is_wp_error($response)) {
            self::logError($email, $response->get_error_message());
            if (substr($response->get_error_message(), 0, 13) === "cURL error 28") { // timeout
                return array('status' => 'fail', 'message' => 'Server timeout. Please try again.');
            }
            return array('status' => 'fail');
        }

        return json_decode($response['body'], true);
    }

    public static function updateAdtags($response)
    {
        if (\Arr::get($response, 'adtags') !== null) {
            MnetAdTag::populateAdtags(\Arr::get($response, 'adtags'), \Arr::get($response, 'adHeadCodes'));
        } elseif (\mnet_user()->isNewPub()) {
            MnetAdTag::fetchAdTags($response['access_token'], false);
        }
    }

    public static function refreshStatus()
    {
        $url = MNET_API_ENDPOINT . "status" . '?access_token=' . mnet_user()->token;
        $response = \wp_remote_get($url, DefaultOptions::$MNET_API_DEFAULT_ARGS);
        MnetAuthManager::clearExpiredToken($response);
        if (!\is_wp_error($response) && !empty($response['body']) && (!empty($response['response']) && !empty($response['response']['code']) && $response['response']['code'] === 200)) {
            $body = json_decode($response['body'], true);

            $updatedUserData = [];
            $updatedUserData['isEap'] = !isset($body['eap']) ? 1 : intval($body['eap']);
            $updatedUserData['inactive'] = !isset($body['inactive']) ? 0 : intval($body['inactive']);
            MnetOptions::updateUserOptions($updatedUserData);
            \mnet_user()->refresh($updatedUserData);

            $updatedSiteData = [];
            $updatedSiteData['rejected'] = !isset($body['rejected']) ? 0 : intval($body['rejected']);
            $updatedSiteData['mapped'] = !isset($body['siteMapped']) ? 0 : intval($body['siteMapped']);
            $updatedSiteData['status'] = !isset($body['siteStatus']) ? '' : $body['siteStatus'];
            MnetOptions::updateSiteOptions($updatedSiteData);
            \mnet_site()->refresh($updatedSiteData);

            if (\Arr::get($body, 'adtags') !== null) {
                MnetAdTag::populateAdtags(\Arr::get($body, 'adtags'), \Arr::get($body, 'adHeadCodes'));
            }
        }
    }


    public static function isLoggedIn()
    {
        return !!(\mnet_user()->token);
    }

    public static function returnIfSessionExpired()
    {
        if (!self::isLoggedIn()) {
            MnetOptions::clearLoggedInOptions();
            \wp_send_json(array('status' => MNET_SESSION_STATUS_EXPIRED), 401);
        }
    }

    public static function handleAccessTokenExpired($response)
    {
        if (isset($response['response']) && isset($response['response']['code']) && $response['response']['code'] === 401) {
            MnetOptions::clearLoggedInOptions();
            \wp_send_json(array('status' => MNET_SESSION_STATUS_EXPIRED), 401);
        }
    }

    public static function clearExpiredToken($response)
    {
        if (!\is_wp_error($response) && isset($response['response']) && isset($response['response']['code']) && $response['response']['code'] === 401) {
            MnetOptions::clearLoggedInOptions();
        }
    }

    public static function removeAuthCookie()
    {
        unset($_COOKIE['mnet_authenticated']);
        setcookie('mnet_authenticated', null, -1, '/');
    }

    public static function logout()
    {
        $token = \mnet_user()->token;
        MnetOptions::clearLoggedInOptions();
        self::apiLogout($token);
    }

    public static function apiLogout($token)
    {
        $url = MNET_API_ENDPOINT . "logout";
        $payload = array_merge(array(
            'token' => $token,
            'domain' => MnetPluginUtils::getDomain(),
        ), MnetPluginUtils::getClientInfo());
        \wp_remote_post(
            $url,
            array_merge(
                DefaultOptions::$MNET_API_DEFAULT_ARGS,
                array(
                    'method' => 'POST',
                    'body' => $payload,
                )
            )
        );
    }

    public static function getEncryptionKey()
    {
        $url = MNET_API_ENDPOINT . "encryption-key";
        $key = \wp_remote_get(
            $url,
            array_merge(
                DefaultOptions::$MNET_API_DEFAULT_ARGS
            )
        );
        if (Arr::get($key, 'response.code') === 200) {
            Response::success(Arr::get($key, 'body'));
        }
        Response::fail('Something went wrong.');
    }
}
