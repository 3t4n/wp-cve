<?php defined('ABSPATH') || exit;

class Nouvello_WeManage_Utm_Conversion
{

  const TYPE_UNKNOWN = 'unknown';
  const TYPE_LEAD = 'lead';
  const TYPE_ORDER = 'order';

  private static $events = array();
  private static $event_template = array(
    'event' => '',
    'type' => '',
    'label' => '',
    'css' => '',
    'cookie_expiry' => '',
    'data' => array()
  );

  public static function register_event($event)
  {

    $event = Nouvello_WeManage_Utm_Functions::merge_default($event, self::$event_template);
    self::$events[$event['event']] = $event;
  }

  public static function get_registered_events()
  {

    return self::$events;
  }

  public static function get_registered_event($event_name)
  {

    if (!empty(self::$events[$event_name])) {
      return self::$events[$event_name];
    } else {
      return array();
    }
  }
}
