<?php

namespace Mnet\Admin;

use Mnet\Admin\MnetAuthManager;
use Mnet\Utils\DefaultOptions;

class MnetFaqs
{
  static $API_ENDPOINT = MNET_API_ENDPOINT . 'faq';

  public static function index()
  {
    MnetAuthManager::returnIfSessionExpired();
    $url = static::$API_ENDPOINT . '?access_token=' . \mnet_user()->token;
    $response = \wp_remote_get($url, DefaultOptions::$MNET_API_DEFAULT_ARGS);
    MnetAuthManager::handleAccessTokenExpired($response);
    if (\is_wp_error($response) || !isset($response['body']) || $response['body'] == "") {
      \wp_send_json_error(
        array("status" => "error", "message" => "Something went wrong. Please retry after sometime."),
        400
      );
    }
    \wp_send_json(json_decode($response['body']));
  }

  public static function getTroubleshootFaqs()
  {
    $userAgent = \Arr::get($_SERVER, 'HTTP_USER_AGENT');
    $userAgent = urlencode($userAgent);
    $url = static::$API_ENDPOINT . "/troubleshoot?ua=${userAgent}";
    $response = \wp_remote_get($url, DefaultOptions::$MNET_API_DEFAULT_ARGS);
    if (\is_wp_error($response) || !isset($response['body']) || $response['body'] == "") {
      \wp_send_json_error(
        array("status" => "error"),
        400
      );
    }
    \wp_send_json(json_decode($response['body'], true));
  }
}
