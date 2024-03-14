<?php defined('ABSPATH') || exit;

class Nouvello_WeManage_Utm_Settings
{
  const META_NAME = 'nouvello_utm_settings';

  const COOKIE_EXPIRY_DAYS_SHORT = 7;
  const COOKIE_EXPIRY_DAYS_MEDIUM = 30;
  const COOKIE_EXPIRY_DAYS_LONG = 90;

  const COOKIE_LAST_TOUCH_WINDOW = 30; //minutes

  public static $default_settings = array(
    'cookie_attribution_window' => self::COOKIE_EXPIRY_DAYS_LONG,
    'cookie_last_touch_window' => self::COOKIE_LAST_TOUCH_WINDOW,
    'cookie_conversion_account' => self::COOKIE_EXPIRY_DAYS_MEDIUM,
    'cookie_conversion_order' => self::COOKIE_EXPIRY_DAYS_SHORT,
    'cookie_domain' => '',
    'cookie_consent_category' => 'statistics',
    'admin_column_mode' => 'multi',
    'admin_column_conversion_lag' => '1',
    'admin_column_utm_first' => '1',
    'admin_column_utm_last' => '1',
    'admin_column_clid' => '1',
    'admin_column_sess_referer' => '1',
    'export_blank' => '',
    'attribution_format' => 'json',
    'active_attribution' => '1',
    'cookie_renewal' => 'force',
    'woocommerce_conversion_on' => 'checkout',
    'attr_first_non_utm' => '0',
    'attr_first_non_utm_short_name' => '0',
    'attr_date_format' => 'M j, Y',
    'attr_time_format' => 'g:i a'
  );

  private static $instance;

  public static function get_instance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct()
  {
  }

  public static function install()
  {
    add_option(self::META_NAME, self::$default_settings);
  }

  public static function get($key = null)
  {

    $settings = get_option(self::META_NAME, array());
    $settings = Nouvello_WeManage_Utm_Functions::merge_default($settings, self::$default_settings);

    if (!empty($key)) :
      if (isset($settings[$key])) :
        return $settings[$key];
      else :
        return null;
      endif;
    else :
      return $settings;
    endif;
  }

  public static function get_attr_date_format()
  {

    $date_format = Nouvello_WeManage_Utm_Settings::get('attr_date_format');

    if ($date_format === 'WP') :
      $date_format = Nouvello_WeManage_Utm_Functions::get_wp_date_format();
    endif;

    return $date_format;
  }

  public static function get_attr_time_format()
  {

    $time_format = Nouvello_WeManage_Utm_Settings::get('attr_time_format');

    if ($time_format === 'WP') :
      $time_format = Nouvello_WeManage_Utm_Functions::get_wp_time_format();
    endif;

    return $time_format;
  }

  public static function get_enqueue_settings()
  {
    $settings = array(
      'ajax_url' => site_url() . '/wp-admin/admin-ajax.php',
      'action' => 'nouvello_utm_view',
      'nonce' => is_user_logged_in() ? wp_create_nonce('nouvello_utm_nonce') : '',
      'cookie_prefix' => Nouvello_WeManage_Utm_Service::get_cookie_prefix(),
      'cookie_expiry' => array(
        'days' => Nouvello_WeManage_Utm_Service::get_site_settings('cookie_attribution_window')
      ),
      'cookie_renewal' => Nouvello_WeManage_Utm_Service::get_site_settings('cookie_renewal'),
      'cookie_consent_category' => Nouvello_WeManage_Utm_Service::get_site_settings('cookie_consent_category'),
      'domain_info' => Nouvello_WeManage_Utm_Service::get_domain_info(),
      'last_touch_window' => intval(Nouvello_WeManage_Utm_Service::get_site_settings('cookie_last_touch_window')) * 60,
      'wp_consent_api_enabled' => Nouvello_WeManage_Utm_Service::is_wp_consent_api_installed(),
      'user_has_active_attribution' => Nouvello_WeManage_Utm_User::has_active_attribution(get_current_user_id()) ? 1 : 0,
      'attr_first_non_utm' => intval(Nouvello_WeManage_Utm_Service::get_site_settings('attr_first_non_utm'))
    );

    return $settings;
  }
}
