<?php

namespace Mnet\Admin;

use Mnet\Admin\MnetPluginUtils;
use Mnet\Utils\DefaultOptions;
use Mnet\Admin\MnetAuthManager;
use Mnet\MnetDbManager;


class MnetNotices
{
  static $DISMISSED_NOTICES_OPTION = 'DismissedNotices';

  public static function fetchAdminNotices()
  {
    // removed customer id since version 2.9.2
    if (MnetAuthManager::isLoggedIn()) {
      $response = \wp_remote_get(MNET_API_ENDPOINT . "notices?access_token=" . \mnet_user()->token . "&domain=" . MnetPluginUtils::getDomain(), DefaultOptions::$MNET_API_DEFAULT_ARGS);
      if (\is_wp_error($response) || $response['response']['code'] !== 200) {
        return "[]";
      }
      $notices = $response['body'];
      return $notices;
    }
    return "[]";
  }

  public static function getAdminNotices()
  {
    try {
      $notices = json_decode(self::fetchAdminNotices(), true);
    } catch (\Exception $e) {
      return array();
    }
    $dismissedNotices = self::getDismissedNotices();
    $noticesToShow = array();
    foreach ($notices as $page => $list) {
      $noticesToShow[$page] = array_map(array('self', 'getParsedNotice'), array_values(array_filter($list, function ($notice) use ($dismissedNotices) {
        return (!intval($notice['dismissible']) || !in_array($notice['id'], $dismissedNotices)) && self::applyFilters($notice);
      })));
    }
    return $noticesToShow;
  }

  public static function getParsedNotice($notice)
  {
    $note = $notice['note'];
    $finalText = $note;
    $replacements = array(
      'publisherName' => \mnet_user()->name,
      'publisherEmail' => \mnet_user()->email,
      'domain' => MnetPluginUtils::getDomain()
    );

    foreach ($replacements as $key => $value) {
      $finalText = preg_replace('/\$\{' . $key . '\}/', $value, $finalText);
    }
    $notice['note'] = $finalText;
    return $notice;
  }

  public static function applyFilters($notice)
  {
    if (empty($notice['meta'])) return true;

    $meta = $notice['meta'];
    if (empty($meta['filters'])) return true;
    $filters = $meta['filters'];
    if (!empty($filters['versions']) && MnetDbManager::minimumVersionCheckFailed($filters['versions'])) {
      return true;
    }
    if (!empty($filters['constants']) && self::compareValues($filters['constants'], 'constant')) {
      return true;
    }
    if (!empty($filters['options']) && self::compareValues($filters['options'], 'get_option')) {
      return true;
    }
    return false;
  }

  public static function compareValues($list, $func)
  {
    foreach ($list as $key => $item) {
      if (call_user_func($func, $key) === $item['value'] || (isset($item['diff']))) return true;
    }
    return false;
  }

  public static function getLoginPageNotices()
  {
    $notices = self::getAdminNotices();
    return !empty($notices['login']) ? $notices['login'] : array();
  }

  public static function getDismissedNotices()
  {
    return json_decode(MnetOptions::getOption(self::$DISMISSED_NOTICES_OPTION, "[]"), true);
  }

  public static function dismissNotice($id)
  {
    $newDismissedList = self::getDismissedNotices();
    $newDismissedList[] = $id;
    MnetOptions::saveOption(self::$DISMISSED_NOTICES_OPTION, $newDismissedList);
  }
}
