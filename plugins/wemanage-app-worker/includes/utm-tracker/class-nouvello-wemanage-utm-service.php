<?php defined('ABSPATH') || exit;

class Nouvello_WeManage_Utm_Service
{

  const COOKIE_PREFIX_NAME = 'nouvello_utm_';

  const COOKIE_EXPIRY_SHORT = 604800; //7 days
  const COOKIE_EXPIRY_MEDIUM = 2592000; //30 days
  const COOKIE_EXPIRY_LONG = 7776000; //90 days

  const DEFAULT_CONVERSION_TYPE = 'lead';

  private static $instance;
  private static $options = array();
  private static $domain_info = array();

  private static $meta_array = array();
  private static $cookie_whitelist = array();

  private static $utm_whitelist = array(
    'utm_source',
    'utm_medium',
    'utm_campaign',
    'utm_term',
    'utm_content'
  );

  private static $utm_valid_check = array(
    'utm_source'
  );

  private static $traffic_definitions = array();

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


  public static function init($options = array())
  {

    self::$options = Nouvello_WeManage_Utm_Functions::merge_default($options, array(
      'site_settings' => ''
    ));

    //setup domain info
    self::setup_domain_info(self::get_site_settings('cookie_domain'));

    self::$meta_array = include NSWMW_ROOT_PATH . '/includes/utm-tracker/includes/meta.php';

    //setup cookie whitelist
    $cookies = array();

    foreach (self::$meta_array as $meta_key => $meta) :
      if (!empty($meta['is_cookie'])) :
        $cookies[$meta_key] = $meta;
      endif;
    endforeach;

    self::$cookie_whitelist = $cookies;

    //setup search engines
    self::setup_traffic_definitions();
  }

  public static function get_meta_whitelist($scope = '')
  {

    if (!empty($scope)) :

      $scope_whitelist = array();

      foreach (self::$meta_array as $meta_key => $meta) :

        if (isset($meta['scope']) && in_array($scope, (array)$meta['scope'])) :
          $scope_whitelist[$meta_key] = $meta;
        endif;

      endforeach;

      return $scope_whitelist;

    else :

      return self::$meta_array;

    endif;
  }

  public static function get_cookie_whitelist()
  {

    return self::$cookie_whitelist;
  }

  public static function get_cookie_name($cookie_name = '')
  {

    return self::get_cookie_prefix() . $cookie_name;
  }

  public static function get_cookie_prefix()
  {

    if (self::get_site_settings('cookie_domain')) {
      return self::COOKIE_PREFIX_NAME;
    } elseif (is_multisite()) {
      return self::COOKIE_PREFIX_NAME . get_current_blog_id() . '_';
    } else {
      return self::COOKIE_PREFIX_NAME;
    }
  }

  public static function get_domain_info()
  {

    return self::$domain_info;
  }

  public static function is_verified_domain($parsed_url)
  {

    if (is_string($parsed_url)) :
      $parsed_url = Nouvello_WeManage_Utm_Functions::parse_url($parsed_url);
    endif;

    if (empty($parsed_url['host'])) :
      return false;
    endif;

    if (self::get_site_settings('cookie_domain')) :

      $parsed_cookie_domain_host = Nouvello_WeManage_Utm_Functions::parse_url('https://' . self::get_site_settings('cookie_domain'), PHP_URL_HOST);

      //PHP8
      if (empty($parsed_cookie_domain_host)) :
        return false;
      endif;

      $split_cookie_domain = explode('.', $parsed_cookie_domain_host);
      $split_cookie_domain = array_reverse($split_cookie_domain);

      $split_cookie_value = explode('.', $parsed_url['host']);
      $split_cookie_value = array_reverse($split_cookie_value);

      foreach ($split_cookie_domain as $split_index => $split_value) :
        if (isset($split_cookie_value[$split_index])) :
          if ($split_value != $split_cookie_value[$split_index]) :
            // not verified domain
            return false;
          endif;
        else :
          // not verified domain
          return false;
        endif;
      endforeach;

      //verified
      return true;

    elseif (is_multisite()) :

      $blog = get_blog_details(get_current_blog_id(), true);

      //PHP8
      if (!isset($blog->domain) || !isset($blog->path)) :
        return false;
      endif;

      $home_url = rtrim(rtrim((string) $blog->domain, '/') . (string) $blog->path, '/');

      //PHP8
      if (isset($parsed_url['path']) && $parsed_url['path'] !== null && $parsed_url['path'] !== false && $parsed_url['path'] !== '') :
        $check_url = $parsed_url['host'] . $parsed_url['path'];
      else :
        $check_url = $parsed_url['host'];
      endif;

      $check_url = rtrim((string) $check_url, '/');
      $check_url = substr($check_url, 0, strlen($home_url));

      return $check_url === $home_url ? true : false;

    else :

      $parsed_host = Nouvello_WeManage_Utm_Functions::parse_url(get_option('home', ''), PHP_URL_HOST);

      //PHP8
      if (empty($parsed_host) || !is_string($parsed_host)) :
        return false;
      endif;

      $check_url = substr($parsed_url['host'], 0, strlen($parsed_host));

      return $check_url === $parsed_host ? true : false;

    endif;

    return false;
  }

  public static function is_self_referer($referer_url)
  {

    $home_url = '';

    if (empty($referer_url)) :
      return false;
    endif;

    $parsed_referer = Nouvello_WeManage_Utm_Functions::parse_url($referer_url);

    //PHP8
    if (empty($parsed_referer['host'])) :
      return false;
    endif;

    if (is_multisite()) :

      $blog = get_blog_details(get_current_blog_id(), true);

      //PHP8
      if (!isset($blog->domain) || !isset($blog->path)) :
        return false;
      endif;

      $home_url = rtrim((string) $blog->domain . (string) $blog->path, '/');

    else :

      $parsed_host = Nouvello_WeManage_Utm_Functions::parse_url(get_option('home', ''), PHP_URL_HOST);

      //PHP8
      if (empty($parsed_host) && !is_string($parsed_host)) :
        return false;
      endif;

      $home_url = rtrim($parsed_host, '/');

    endif;

    //PHP8
    if (isset($parsed_referer['path']) && $parsed_referer['path'] !== null && $parsed_referer['path'] !== false && $parsed_referer['path'] !== '') :
      $check_url = $parsed_referer['host'] . $parsed_referer['path'];
    else :
      $check_url = $parsed_referer['host'];
    endif;

    $check_url = rtrim((string) $check_url, '/');
    $check_url = substr($check_url, 0, strlen($home_url));

    return $check_url === $home_url ? true : false;
  }

  public static function get_user_synced_session($user_id = 0)
  {

    $user_id = Nouvello_WeManage_Utm_Functions::sanitize_user_id($user_id);
    $cookie_consent = self::get_cookie_consent_value();
    $is_user_logged_in = is_user_logged_in();

    //important to be check same line
    if (
      ($is_user_logged_in && empty($user_id))
      || ($is_user_logged_in && !empty($user_id) && !Nouvello_WeManage_Utm_Functions::is_current_logged_in_user_id($user_id))
    ) :

      //security - dont populate browser cookies
      $active_session = self::get_meta_whitelist('active');
      $active_session = self::prepare_created_by($active_session, get_current_user_id());

    elseif ($cookie_consent === 'deny') :

      Nouvello_WeManage_Utm_User::delete_active_session($user_id);

      $active_session = self::get_meta_whitelist('active');

    else :

      Nouvello_WeManage_Utm_User::reset_active_session_if_expired($user_id);

      $active_session = Nouvello_WeManage_Utm_User::get_active_session($user_id);

      //get cookie values from browser
      $browser_user_cookies = self::get_user_browser_cookies($user_id);

      self::sync_cookie_expiry($active_session, $browser_user_cookies);
      self::sync_first_session($active_session, $browser_user_cookies);
      self::sync_utm_session($active_session, $browser_user_cookies);
      self::sync_click_identifier_session('gclid', $active_session, $browser_user_cookies);
      self::sync_click_identifier_session('fbclid', $active_session, $browser_user_cookies);
      self::sync_click_identifier_session('msclkid', $active_session, $browser_user_cookies);

    endif;

    if (isset($active_session['cookie_consent'])) :
      $active_session['cookie_consent']['value'] = $cookie_consent;
    endif;

    //sanitize
    foreach ($active_session as $key => $row) :

      if (isset($row['value'])) :

        if (isset($row['type'])) :
          $active_session[$key]['value'] = Nouvello_WeManage_Utm_Functions::sanitize_meta_value_by_type($active_session[$key]['value'], $active_session[$key]['type']);
        else :
          $active_session[$key]['value'] = Nouvello_WeManage_Utm_Functions::sanitize_meta_value_by_type($active_session[$key]['value']);
        endif;

      else :

        $active_session[$key]['value'] = '';

      endif;

    endforeach;

    return $active_session;
  }

  private static function sync_first_session(&$active_session, &$browser_user_cookies)
  {

    //error recovery
    if (empty($active_session['sess_visit']['value']) || empty($active_session['sess_landing']['value'])) :
      $active_session['sess_visit']['value'] = '';
      $active_session['sess_visit_date_local']['value'] = '';
      $active_session['sess_visit_date_utc']['value'] = '';
      $active_session['sess_landing']['value'] = '';
      $active_session['sess_landing_clean']['value'] = '';
      $active_session['sess_referer']['value'] = '';
      $active_session['sess_referer_clean']['value'] = '';
    endif;

    if (empty($browser_user_cookies['sess_visit']['value']) || empty($browser_user_cookies['sess_landing']['value'])) :
      $browser_user_cookies['sess_visit']['value'] = '';
      $browser_user_cookies['sess_landing']['value'] = '';
      $browser_user_cookies['sess_referer']['value'] = '';
    endif;

    //sync
    if (
      (empty($active_session['sess_visit']['value']) || empty($active_session['sess_landing']['value']))
      || ($active_session['sess_visit']['value'] > 0 && $browser_user_cookies['sess_visit']['value'] > 0 && $browser_user_cookies['sess_visit']['value'] < $active_session['sess_visit']['value'])
    ) :

      $active_session['sess_visit']['value'] = $browser_user_cookies['sess_visit']['value'];
      $active_session['sess_visit_date_local']['value'] = Nouvello_WeManage_Utm_Functions::timestamp_to_local_date_database($browser_user_cookies['sess_visit']['value']);
      $active_session['sess_visit_date_utc']['value'] = Nouvello_WeManage_Utm_Functions::timestamp_to_utc_date_database($browser_user_cookies['sess_visit']['value']);

      $active_session['sess_landing']['value'] = $browser_user_cookies['sess_landing']['value'];
      $active_session['sess_landing_clean']['value'] = Nouvello_WeManage_Utm_Functions::clean_url($browser_user_cookies['sess_landing']['value']);

      $active_session['sess_referer']['value'] = $browser_user_cookies['sess_referer']['value'];
      $active_session['sess_referer_clean']['value'] = Nouvello_WeManage_Utm_Functions::clean_url($browser_user_cookies['sess_referer']['value']);

    endif;

    if (isset($active_session['sess_ga'])) :
      $active_session['sess_ga']['value'] = $browser_user_cookies['sess_ga']['value'];
    endif;
  }

  private static function sync_utm_session(&$active_session, &$browser_user_cookies)
  {

    $sort = array();

    /*
    * Support Organic / Direct / Referral
    */
    if (self::get_site_settings('attr_first_non_utm')) :

      if ($active_session['sess_visit']['value'] > 0 && !empty($active_session['sess_landing']['value'])) :
        $sort[$active_session['sess_visit']['value']] = array('timestamp' => $active_session['sess_visit']['value'], 'url' => $active_session['sess_landing']['value']);
      endif;

      if ($browser_user_cookies['sess_visit']['value'] > 0 && !empty($browser_user_cookies['sess_landing']['value'])) :
        $sort[$browser_user_cookies['sess_visit']['value']] = array('timestamp' => $browser_user_cookies['sess_visit']['value'], 'url' => $browser_user_cookies['sess_landing']['value']);
      endif;

    endif;

    //active
    if ($active_session['utm_1st_visit']['value'] > 0 && self::is_valid_utm_url($active_session['utm_1st_url']['value'])) {
      $sort[$active_session['utm_1st_visit']['value']] = array('timestamp' => $active_session['utm_1st_visit']['value'], 'url' => $active_session['utm_1st_url']['value']);
    }

    if ($active_session['utm_visit']['value'] > 0 && self::is_valid_utm_url($active_session['utm_url']['value'])) {
      $sort[$active_session['utm_visit']['value']] = array('timestamp' => $active_session['utm_visit']['value'], 'url' => $active_session['utm_url']['value']);
    }

    if ($active_session['gclid_visit']['value'] > 0 && self::is_valid_utm_url($active_session['gclid_url']['value'])) {
      $sort[$active_session['gclid_visit']['value']] = array('timestamp' => $active_session['gclid_visit']['value'], 'url' => $active_session['gclid_url']['value']);
    }

    if ($active_session['fbclid_visit']['value'] > 0 && self::is_valid_utm_url($active_session['fbclid_url']['value'])) {
      $sort[$active_session['fbclid_visit']['value']] = array('timestamp' => $active_session['fbclid_visit']['value'], 'url' => $active_session['fbclid_url']['value']);
    }

    if ($active_session['msclkid_visit']['value'] > 0 && self::is_valid_utm_url($active_session['msclkid_url']['value'])) {
      $sort[$active_session['msclkid_visit']['value']] = array('timestamp' => $active_session['msclkid_visit']['value'], 'url' => $active_session['msclkid_url']['value']);
    }

    //browser
    if ($browser_user_cookies['utm_1st_visit']['value'] > 0 && self::is_valid_utm_url($browser_user_cookies['utm_1st_url']['value'])) {
      $sort[$browser_user_cookies['utm_1st_visit']['value']] = array('timestamp' => $browser_user_cookies['utm_1st_visit']['value'], 'url' => $browser_user_cookies['utm_1st_url']['value']);
    }

    if ($browser_user_cookies['utm_visit']['value'] > 0 && self::is_valid_utm_url($browser_user_cookies['utm_url']['value'])) {
      $sort[$browser_user_cookies['utm_visit']['value']] = array('timestamp' => $browser_user_cookies['utm_visit']['value'], 'url' => $browser_user_cookies['utm_url']['value']);
    }

    if ($browser_user_cookies['gclid_visit']['value'] > 0 && self::is_valid_utm_url($browser_user_cookies['gclid_url']['value'])) {
      $sort[$browser_user_cookies['gclid_visit']['value']] = array('timestamp' => $browser_user_cookies['gclid_visit']['value'], 'url' => $browser_user_cookies['gclid_url']['value']);
    }

    if ($browser_user_cookies['fbclid_visit']['value'] > 0 && self::is_valid_utm_url($browser_user_cookies['fbclid_url']['value'])) {
      $sort[$browser_user_cookies['fbclid_visit']['value']] = array('timestamp' => $browser_user_cookies['fbclid_visit']['value'], 'url' => $browser_user_cookies['fbclid_url']['value']);
    }

    if ($browser_user_cookies['msclkid_visit']['value'] > 0 && self::is_valid_utm_url($browser_user_cookies['msclkid_url']['value'])) {
      $sort[$browser_user_cookies['msclkid_visit']['value']] = array('timestamp' => $browser_user_cookies['msclkid_visit']['value'], 'url' => $browser_user_cookies['msclkid_url']['value']);
    }

    if (!empty($sort)) :
      ksort($sort, SORT_NUMERIC);
      $first = reset($sort);

      //first touch
      $active_session['utm_1st_visit']['value'] = $first['timestamp'];
      $active_session['utm_1st_visit_date_local']['value'] = Nouvello_WeManage_Utm_Functions::timestamp_to_local_date_database($first['timestamp']);
      $active_session['utm_1st_visit_date_utc']['value'] = Nouvello_WeManage_Utm_Functions::timestamp_to_utc_date_database($first['timestamp']);

      $active_session['utm_1st_url']['value'] = $first['url'];

      //last touch
      $active_session['utm_visit']['value'] = $active_session['utm_1st_visit']['value'];
      $active_session['utm_visit_date_local']['value'] = $active_session['utm_1st_visit_date_local']['value'];
      $active_session['utm_visit_date_utc']['value'] = $active_session['utm_1st_visit_date_utc']['value'];

      $active_session['utm_url']['value'] = $active_session['utm_1st_url']['value'];

      //last touch
      if (count($sort) > 1) :
        $end = end($sort);

        $cookie_last_touch_window_seconds = (int) (self::get_site_settings('cookie_last_touch_window') * 60);

        if (
          ((int) $end['timestamp'] - (int) $first['timestamp']) > $cookie_last_touch_window_seconds
          || $first['url'] != $end['url']
        ) :
          $active_session['utm_visit']['value'] = $end['timestamp'];
          $active_session['utm_visit_date_local']['value'] = Nouvello_WeManage_Utm_Functions::timestamp_to_local_date_database($end['timestamp']);
          $active_session['utm_visit_date_utc']['value'] = Nouvello_WeManage_Utm_Functions::timestamp_to_utc_date_database($end['timestamp']);

          $active_session['utm_url']['value'] = $end['url'];
        endif;

      endif;

    else :

      $active_session['utm_1st_visit']['value'] = '';
      $active_session['utm_1st_visit_date_local']['value'] = '';
      $active_session['utm_1st_visit_date_utc']['value'] = '';

      $active_session['utm_1st_url']['value'] = '';
      $active_session['utm_1st_url_clean']['value'] = '';

      $active_session['utm_visit']['value'] = '';
      $active_session['utm_visit_date_local']['value'] = '';
      $active_session['utm_visit_date_utc']['value'] = '';

      $active_session['utm_url']['value'] = '';
      $active_session['utm_url_clean']['value'] = '';

    endif;

    $active_session = self::extract_utm_from_session($active_session);

    //clean url
    $active_session['utm_1st_url_clean']['value'] = Nouvello_WeManage_Utm_Functions::clean_url($active_session['utm_1st_url']['value']);
    $active_session['utm_url_clean']['value'] = Nouvello_WeManage_Utm_Functions::clean_url($active_session['utm_url']['value']);
  }

  private static function sync_click_identifier_session($click_identifier, &$active_session, &$browser_user_cookies)
  {

    //error recovery
    if (empty($active_session[$click_identifier . '_visit']['value']) || empty($active_session[$click_identifier . '_url']['value'])) :
      $active_session[$click_identifier . '_visit']['value'] = '';
      $active_session[$click_identifier . '_visit_date_local']['value'] = '';
      $active_session[$click_identifier . '_visit_date_utc']['value'] = '';
      $active_session[$click_identifier . '_url']['value'] = '';
      $active_session[$click_identifier . '_url_clean']['value'] = '';
      $active_session[$click_identifier . '_value']['value'] = '';
    endif;

    if (empty($browser_user_cookies[$click_identifier . '_visit']['value']) || !Nouvello_WeManage_Utm_Functions::has_url_query($browser_user_cookies[$click_identifier . '_url']['value'], $click_identifier)) :
      $browser_user_cookies[$click_identifier . '_visit']['value'] = '';
      $browser_user_cookies[$click_identifier . '_url']['value'] = '';
    endif;

    //sync
    if (
      (empty($active_session[$click_identifier . '_visit']['value']) || empty($active_session[$click_identifier . '_url']['value']))
      || ($active_session[$click_identifier . '_visit']['value'] > 0 && $browser_user_cookies[$click_identifier . '_visit']['value'] > 0 && $browser_user_cookies[$click_identifier . '_visit']['value'] > $active_session[$click_identifier . '_visit']['value'])
    ) :

      $active_session[$click_identifier . '_visit']['value'] = $browser_user_cookies[$click_identifier . '_visit']['value'];
      $active_session[$click_identifier . '_visit_date_local']['value'] = Nouvello_WeManage_Utm_Functions::timestamp_to_local_date_database($browser_user_cookies[$click_identifier . '_visit']['value']);
      $active_session[$click_identifier . '_visit_date_utc']['value'] = Nouvello_WeManage_Utm_Functions::timestamp_to_utc_date_database($browser_user_cookies[$click_identifier . '_visit']['value']);

      $active_session[$click_identifier . '_url']['value'] = $browser_user_cookies[$click_identifier . '_url']['value'];
      $active_session[$click_identifier . '_url_clean']['value'] = Nouvello_WeManage_Utm_Functions::clean_url($browser_user_cookies[$click_identifier . '_url']['value']);

      $active_session[$click_identifier . '_value']['value'] = Nouvello_WeManage_Utm_Functions::get_url_query_by_parameter($browser_user_cookies[$click_identifier . '_url']['value'], $click_identifier);
    endif;
  }

  private static function get_user_browser_cookies($user_id = 0)
  {

    $user_id = Nouvello_WeManage_Utm_Functions::sanitize_user_id($user_id);
    $time = time();
    $cookie_whitelist = Nouvello_WeManage_Utm_Service::get_cookie_whitelist();

    if ($user_id > 0 && is_user_logged_in() && $user_id !== get_current_user_id()) :
      return $cookie_whitelist;
    endif;

    foreach ($cookie_whitelist as $cookie_key => &$cookie_row) :

      $cookie_name = !empty($cookie_row['cookie_name']) ? $cookie_row['cookie_name'] : Nouvello_WeManage_Utm_Service::get_cookie_name($cookie_key);

      $meta_value = '';

      if (isset($_COOKIE[$cookie_name])) :
        $meta_value = wp_unslash($_COOKIE[$cookie_name]);
      endif;

      if ($cookie_row['type'] === 'url') :

        $meta_value = Nouvello_WeManage_Utm_Functions::sanitize_url($meta_value);

        if (!empty($meta_value)) :
          //check verified domain
          if (isset($cookie_row['is_own_url']) && $cookie_row['is_own_url'] === true) :

            if (Nouvello_WeManage_Utm_Service::is_verified_domain($meta_value)) :
              $cookie_row['value'] = $meta_value;
            endif;

          else :

            //check if not self referer, then set
            if ($cookie_key === 'sess_referer' && !Nouvello_WeManage_Utm_Service::is_self_referer($meta_value)) :
              $cookie_row['value'] = $meta_value;
            endif;

          endif;
        endif;

      elseif ($cookie_row['type'] === 'timestamp') :

        if (is_numeric($meta_value)) :
          $cookie_row['value'] = intval($meta_value);

          if ($cookie_row['value'] <= 0) :
            $cookie_row['value'] = $time;
          endif;
        else :
          $cookie_row['value'] = $time;
        endif;

      elseif ($cookie_row['type'] === 'integer') :

        if (is_numeric($meta_value)) :
          $cookie_row['value'] = intval($meta_value);
        else :
          $cookie_row['value'] = '';
        endif;

      else :

        $cookie_row['value'] = sanitize_text_field($meta_value);

      endif;

    endforeach;

    return $cookie_whitelist;
  }


  public static function get_site_settings($key)
  {

    self::$options['site_settings'] = Nouvello_WeManage_Utm_Settings::$default_settings;

    if (isset(self::$options['site_settings'][$key])) {
      return self::$options['site_settings'][$key];
    } else {
      return null;
    }
  }

  public static function sync_cookie_expiry(&$active_session, &$browser_user_cookies)
  {

    $default_cookie_expiry = absint(self::get_site_settings('cookie_attribution_window'));

    if (isset($browser_user_cookies['cookie_expiry']['value']) && !empty($browser_user_cookies['cookie_expiry']['value'])) :

      if (!is_numeric($browser_user_cookies['cookie_expiry']['value'])) :

        //version 1
        switch ($browser_user_cookies['cookie_expiry']['value']):
          case 'short':

            $browser_user_cookies['cookie_expiry']['value'] = Nouvello_WeManage_Utm_Settings::COOKIE_EXPIRY_DAYS_SHORT;
            break;

          case 'medium':

            $browser_user_cookies['cookie_expiry']['value'] = Nouvello_WeManage_Utm_Settings::COOKIE_EXPIRY_DAYS_MEDIUM;
            break;

          default:

            $browser_user_cookies['cookie_expiry']['value'] = Nouvello_WeManage_Utm_Settings::COOKIE_EXPIRY_DAYS_LONG;
            break;
        endswitch;

      endif;

      $browser_user_cookies['cookie_expiry']['value'] = absint($browser_user_cookies['cookie_expiry']['value']);

      if ($browser_user_cookies['cookie_expiry']['value'] <= 0 || $browser_user_cookies['cookie_expiry']['value'] > $default_cookie_expiry) :

        $browser_user_cookies['cookie_expiry']['value'] = $default_cookie_expiry;

      endif;

    endif;

    if (isset($active_session['cookie_expiry']['value'])) :

      if (empty($active_session['cookie_expiry']['value'])) :
        $active_session['cookie_expiry']['value'] = $default_cookie_expiry;
      endif;

      if (
        isset($browser_user_cookies['cookie_expiry']['value'])
        && $browser_user_cookies['cookie_expiry']['value'] > 0
        && $browser_user_cookies['cookie_expiry']['value'] < $active_session['cookie_expiry']['value']
      ) :

        //if browser lower value, use browser value
        $active_session['cookie_expiry']['value'] = $browser_user_cookies['cookie_expiry']['value'];

      endif;

      $active_session['cookie_expiry']['value'] = absint($active_session['cookie_expiry']['value']);

      if (empty($active_session['cookie_expiry']['value']) || $active_session['cookie_expiry']['value'] > $default_cookie_expiry) :

        $active_session['cookie_expiry']['value'] = $default_cookie_expiry;

      endif;

    endif;
  }

  public static function set_cookies($meta_array = array())
  {

    //cookie consent deny
    if (isset($meta_array['cookie_consent']['value']) && $meta_array['cookie_consent']['value'] === 'deny') :
      self::delete_cookies();
      return false;
    endif;

    $cookie_expiry_in_seconds = 0;

    //calculate cookie expiry in seconds
    if (!empty($meta_array['cookie_expiry']['value']) && $meta_array['cookie_expiry']['value'] > 0) :
      $cookie_expiry_in_seconds = time() + (absint($meta_array['cookie_expiry']['value']) * 86400);
    else :
      $cookie_expiry_in_days = absint(self::get_site_settings('cookie_attribution_window'));
      $cookie_expiry_in_seconds = time() + ($cookie_expiry_in_days * 86400);
    endif;

    $domain_info = self::get_domain_info();

    //set cookies
    foreach (self::get_cookie_whitelist() as $cookie_key => $cookie) :

      if (!empty($cookie['is_cookie']) && !empty($cookie['rewrite_cookie']) && isset($meta_array[$cookie_key]['value'])) :

        if (
          $meta_array[$cookie_key]['value'] !== ''
          || ($meta_array[$cookie_key]['value'] === '' && isset($_COOKIE[self::get_cookie_name($cookie_key)]))
        ) :

          setcookie(
            self::get_cookie_name($cookie_key),
            $meta_array[$cookie_key]['value'],
            $cookie_expiry_in_seconds,
            $domain_info['path'],
            $domain_info['domain'],
            true
          );

        endif;

      endif;

    endforeach;

    try {

      $main_cookie = array(
        'updated_ts' => time()
      );
      $main_cookie_json = json_encode($main_cookie);

      setcookie(
        self::get_cookie_name('main'),
        $main_cookie_json,
        $cookie_expiry_in_seconds,
        $domain_info['path'],
        $domain_info['domain'],
        true
      );
    } catch (\Exception $e) {
    }
  }

  public static function delete_cookies()
  {

    $domain_info = self::get_domain_info();

    foreach (self::get_cookie_whitelist() as $cookie_key => $cookie) :

      if (
        isset($_COOKIE[self::get_cookie_name($cookie_key)])
        && !empty($cookie['is_cookie'])
        && !empty($cookie['rewrite_cookie'])
      ) :
        setcookie(
          self::get_cookie_name($cookie_key),
          '',
          1,
          $domain_info['path'],
          $domain_info['domain'],
          true
        );
      endif;

    endforeach;
  }


  public static function prepare_conversion_lag($user_synced_session, $date_created_timestamp)
  {

    //calculate conversion lag
    $date_created_timestamp = (int) (!empty($date_created_timestamp) ? $date_created_timestamp : time());
    $sess_visit_timestamp = (int) (!empty($user_synced_session['sess_visit']['value']) ? $user_synced_session['sess_visit']['value'] : 0);

    if ($date_created_timestamp <= 0 || $sess_visit_timestamp <= 0) :
      return $user_synced_session;
    endif;

    //calculate conversion lag
    $conversion_lag = $date_created_timestamp - $sess_visit_timestamp;

    $user_synced_session['conversion_ts']['value'] = $date_created_timestamp;
    $user_synced_session['conversion_date_local']['value'] = Nouvello_WeManage_Utm_Functions::timestamp_to_local_date_database($date_created_timestamp, 'Y-m-d H:i:s');
    $user_synced_session['conversion_date_utc']['value'] = Nouvello_WeManage_Utm_Functions::timestamp_to_utc_date_database($date_created_timestamp, 'Y-m-d H:i:s');

    $user_synced_session['conversion_lag']['value'] = $conversion_lag;
    $user_synced_session['conversion_lag_human']['value'] = Nouvello_WeManage_Utm_Functions::translate_seconds_to_duration($conversion_lag);

    return $user_synced_session;
  }

  public static function prepare_attribution_data_for_saving($user_synced_session, $scope = 'converted')
  {

    $attribution = array();

    if (!is_array($user_synced_session)) :
      return $attribution;
    endif;

    //cookie consent deny so clear attribution
    if (isset($user_synced_session['cookie_consent']['value']) && $user_synced_session['cookie_consent']['value'] === 'deny') :

      $attribution['cookie_consent'] = sanitize_text_field($user_synced_session['cookie_consent']['value']);
      return $attribution;

    endif;

    foreach ($user_synced_session as $meta_key => $meta) :

      //check scope
      if (!isset($meta['scope']) || !in_array($scope, (array)$meta['scope'])) :
        continue;
      endif;

      if (isset($meta['value']) && $meta['value'] !== '' && $meta['value'] !== null && $meta['value'] !== false) :
        //save meta
        $attribution[$meta_key] = $meta['value'];
      endif;

    endforeach;

    return $attribution;
  }

  public static function prepare_cookie_expiry_after_conversion($user_synced_session, $conversion_event = array())
  {

    if (empty($user_synced_session['cookie_expiry']['value'])) :
      return $user_synced_session;
    endif;

    //set cookie expiry by event
    if (!empty($conversion_event['cookie_expiry']) && $conversion_event['cookie_expiry'] > 0) :

      if ($conversion_event['cookie_expiry'] < $user_synced_session['cookie_expiry']['value']) :
        $user_synced_session['cookie_expiry']['value'] = absint($conversion_event['cookie_expiry']);
      endif;

    endif;

    if ($user_synced_session['cookie_expiry']['value'] <= 0) :
      $user_synced_session['cookie_expiry']['value'] = absint(self::get_site_settings('cookie_attribution_window'));
    endif;

    return $user_synced_session;
  }


  public static function trigger_conversion($conversion_event, $user_synced_session, $user_id = 0)
  {

    $user_id = Nouvello_WeManage_Utm_Functions::sanitize_user_id($user_id);
    $user_synced_session = self::prepare_cookie_expiry_after_conversion($user_synced_session, $conversion_event);

    Nouvello_WeManage_Utm_User::add_active_conversion($user_id, $conversion_event, $user_synced_session);

    if (is_user_logged_in()) :

      //security - important to be inner
      if (Nouvello_WeManage_Utm_Functions::is_current_logged_in_user_id($user_id)) :

        Nouvello_WeManage_Utm_User::update_active_session($user_id, $user_synced_session);
        self::set_cookies($user_synced_session);

      endif;

    else :

      Nouvello_WeManage_Utm_User::update_active_session($user_id, $user_synced_session);
      self::set_cookies($user_synced_session);

    endif;
  }

  public static function get_cookie_consent_value()
  {

    if (self::is_wp_consent_api_installed()) :
      return self::has_cookie_consent() ? 'allow' : 'deny';
    else :
      return 'n/a';
    endif;
  }

  public static function format_cookie_consent_value($has_consent)
  {

    if (self::is_wp_consent_api_installed()) :
      return $has_consent ? 'allow' : 'deny';
    else :
      return 'n/a';
    endif;
  }

  public static function is_wp_consent_api_installed()
  {

    if (function_exists('wp_has_consent')) :
      return true;
    else :
      return false;
    endif;
  }

  public static function prepare_conversion_type($user_synced_session, $conversion_type)
  {

    if (isset($user_synced_session['conversion_type'])) :
      $user_synced_session['conversion_type']['value'] = !empty($conversion_type) ? $conversion_type : self::DEFAULT_CONVERSION_TYPE;
    endif;

    return $user_synced_session;
  }

  public static function prepare_created_by($user_synced_session, $current_logged_in_user_id = '')
  {

    if (isset($user_synced_session['created_by'])) :
      $user_synced_session['created_by']['value'] = abs((int)$current_logged_in_user_id);
    endif;

    return $user_synced_session;
  }

  public static function setup_domain_info($cookie_domain = '')
  {

    if ($cookie_domain && is_string($cookie_domain)) :

      $cookie_domain = rtrim($cookie_domain, '/');
      $cookie_domain = ltrim($cookie_domain, '.');

      $parsed_host = Nouvello_WeManage_Utm_Functions::parse_url('http://' . $cookie_domain, PHP_URL_HOST);

      self::$domain_info = array(
        'domain' => !empty($parsed_host) ? $parsed_host : '',
        'path' => '/'
      );

    elseif (is_multisite()) :

      $blog = get_blog_details(get_current_blog_id(), true);

      self::$domain_info = array(
        'domain' => isset($blog->domain) ? $blog->domain : '',
        'path' => (isset($blog->path) ? rtrim((string) $blog->path, '/') : '') . '/'
      );

    else :

      $parsed_host = Nouvello_WeManage_Utm_Functions::parse_url(get_option('home', ''), PHP_URL_HOST);

      self::$domain_info = array(
        'domain' => !empty($parsed_host) ? $parsed_host : '',
        'path' => '/',
      );

    endif;
  }

  public static function extract_utm_from_session($active_session)
  {

    $remember_utm_1st_url = '';

    //reset
    foreach (Nouvello_WeManage_Utm_Service::$utm_whitelist as $utm_parameter) :
      if (isset($active_session[$utm_parameter . '_1st']['value'])) :
        $active_session[$utm_parameter . '_1st']['value'] = '';
      endif;

      if (isset($active_session[$utm_parameter]['value'])) :
        $active_session[$utm_parameter]['value'] = '';
      endif;
    endforeach;

    //first touch
    if (isset($active_session['utm_1st_url']['value']) && filter_var((string) $active_session['utm_1st_url']['value'], FILTER_VALIDATE_URL)) :

      $remember_utm_1st_url = $active_session['utm_1st_url']['value'];

      $utm_first_queries = Nouvello_WeManage_Utm_Functions::get_url_query($active_session['utm_1st_url']['value'], false);

      //actual UTM parameters
      foreach (Nouvello_WeManage_Utm_Service::$utm_whitelist as $utm_parameter) :
        if (isset($active_session[$utm_parameter . '_1st']['value'])) :
          $active_session[$utm_parameter . '_1st']['value'] = isset($utm_first_queries[$utm_parameter]) ? $utm_first_queries[$utm_parameter] : '';
        endif;
      endforeach;

      //Support "Organic / Direct / Referral"
      if (self::get_site_settings('attr_first_non_utm')) :

        if (
          isset($active_session['sess_referer']['value'])
          && (!isset($utm_first_queries['utm_source']) || $utm_first_queries['utm_source'] === '')
        ) :

          $source_medium = self::which_traffic_source_medium($active_session['sess_referer']['value']);

          //set utm_source
          $active_session['utm_source_1st']['value'] = $source_medium['source'];
          $active_session['utm_1st_url']['value'] = add_query_arg(
            array('utm_source' => urlencode($source_medium['source'])),
            $active_session['utm_1st_url']['value']
          );

          //set utm_medium
          if (!isset($utm_first_queries['utm_medium']) || $utm_first_queries['utm_medium'] === '') :
            $active_session['utm_medium_1st']['value'] = $source_medium['medium'];
            $active_session['utm_1st_url']['value'] = add_query_arg(
              array('utm_medium' => urlencode($source_medium['medium'])),
              $active_session['utm_1st_url']['value']
            );
          endif;

        endif;

      endif;

    endif;

    //last touch
    if (isset($active_session['utm_url']['value']) && filter_var((string) $active_session['utm_url']['value'], FILTER_VALIDATE_URL)) :

      //UTM 1st URL may have been modified above
      if (
        $remember_utm_1st_url == $active_session['utm_url']['value']
        && $active_session['utm_1st_visit']['value'] == $active_session['utm_visit']['value']
      ) :
        $active_session['utm_url']['value'] = $active_session['utm_1st_url']['value'];
      endif;

      $utm_last_queries = Nouvello_WeManage_Utm_Functions::get_url_query($active_session['utm_url']['value'], false);

      foreach (Nouvello_WeManage_Utm_Service::$utm_whitelist as $utm_parameter) :
        if (isset($active_session[$utm_parameter]['value'])) :
          $active_session[$utm_parameter]['value'] = isset($utm_last_queries[$utm_parameter]) ? $utm_last_queries[$utm_parameter] : '';
        endif;
      endforeach;

    endif;

    return $active_session;
  }

  public static function setup_traffic_definitions()
  {

    self::$traffic_definitions = include NSWMW_ROOT_PATH . '/includes/utm-tracker/includes/traffic-definitions.php';
    self::$traffic_definitions = apply_filters('nouvello_utm_traffic_definitions', self::$traffic_definitions);
  }

  public static function get_traffic_definitions()
  {

    return self::$traffic_definitions;
  }

  public static function which_traffic_source_medium($referrer_url)
  {

    $output = array(
      'source' => '',
      'medium' => ''
    );

    if (empty($referrer_url) || self::is_self_referer($referrer_url)) :

      $output['source'] = 'direct';
      $output['medium'] = 'none';

    else :

      $referrer_hostname = Nouvello_WeManage_Utm_Functions::get_url_hostname($referrer_url);

      //default
      $output['source'] = $referrer_hostname != '' ? strtolower((string) $referrer_hostname) : 'unknown';
      $output['medium'] = 'referral';

      $traffic_definitions = self::get_traffic_definitions();

      if (is_array($traffic_definitions)) :

        $use_short_domain = self::get_site_settings('attr_first_non_utm_short_name');

        foreach ($traffic_definitions as $tmp_regex => $tmp_domain) :

          if (preg_match($tmp_regex, $referrer_hostname) === 1) :

            if ($use_short_domain && !empty($tmp_domain['short_name'])) :
              $output['source'] = $tmp_domain['short_name'];
            endif;

            if (isset($tmp_domain['utm_medium'])) :
              $output['medium'] = $tmp_domain['utm_medium'];
            endif;

            break;

          endif;

        endforeach;

      endif;

    endif;

    return $output;
  }

  public static function is_valid_utm_url($url)
  {

    $url = filter_var((string) $url, FILTER_VALIDATE_URL);

    if (empty($url)) :
      //exit
      return false;
    endif;

    $parsed_query = Nouvello_WeManage_Utm_Functions::parse_url($url, PHP_URL_QUERY);

    if (empty($parsed_query)) :
      //exit
      return false;
    endif;

    $url_query = array();
    parse_str((string) $parsed_query, $url_query);

    if (empty($url_query)) :
      //exit
      return false;
    endif;

    foreach ($url_query as $key => $value) :
      if (in_array($key, self::$utm_valid_check)) :
        //exit
        return true;
      endif;
    endforeach;

    return false;
  }
}
