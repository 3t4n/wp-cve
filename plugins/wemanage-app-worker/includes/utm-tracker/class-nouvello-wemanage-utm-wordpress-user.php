<?php defined('ABSPATH') || exit;

class Nouvello_WeManage_Utm_User
{

  const META_PREFIX = 'nouvello_utm_';
  const META_PREFIX_ACTIVE = 'nouvello_utm_active_';

  private static $instance;
  private static $options;

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
      'global_user_option' => false
    ));
  }

  public static function get_user_option($user_id, $option_name)
  {

    if (is_multisite()) :

      if (self::get_option('global_user_option')) :

        return get_user_option(self::META_PREFIX . $option_name, $user_id);

      else :

        global $wpdb;

        return get_user_meta($user_id, $wpdb->get_blog_prefix() . self::META_PREFIX . $option_name, true);

      endif;

    else :

      return get_user_option(self::META_PREFIX . $option_name, $user_id);

    endif;
  }

  public static function update_user_option($user_id, $option_name, $value)
  {
    update_user_option($user_id, self::META_PREFIX . $option_name, $value, self::get_option('global_user_option'));
  }

  public static function delete_user_option($user_id, $option_name)
  {
    delete_user_option($user_id, self::META_PREFIX . $option_name, self::get_option('global_user_option'));
  }



  public static function get_active_user_option($user_id, $option_name)
  {

    if (is_multisite()) :

      //check this first
      if (self::get_option('global_user_option')) :

        return get_user_option(self::META_PREFIX_ACTIVE . $option_name, $user_id);

      else :

        global $wpdb;

        return get_user_meta($user_id, $wpdb->get_blog_prefix() . self::META_PREFIX_ACTIVE . $option_name, true);

      endif;

    else :

      return get_user_option(self::META_PREFIX_ACTIVE . $option_name, $user_id);

    endif;
  }

  public static function update_active_user_option($user_id, $option_name, $value)
  {
    update_user_option($user_id, self::META_PREFIX_ACTIVE . $option_name, $value, self::get_option('global_user_option'));
  }

  public static function delete_active_user_option($user_id, $option_name)
  {
    delete_user_option($user_id, self::META_PREFIX_ACTIVE . $option_name, self::get_option('global_user_option'));
  }


  public static function get_active_session($user_id)
  {

    $meta_whitelist = Nouvello_WeManage_Utm_Service::get_meta_whitelist('active');

    $user_id = Nouvello_WeManage_Utm_Functions::sanitize_user_id($user_id);

    if (empty($user_id)) {
      return $meta_whitelist;
    }

    try {

      $attribution = self::get_active_user_option($user_id, 'attribution');

      if (!empty($attribution)) :

        $attribution = Nouvello_WeManage_Utm_Functions::json_decode($attribution);

        //populate value
        if (!empty($attribution) && is_array($attribution)) :
          foreach ($attribution as $meta_key => $meta_value) :
            if (isset($meta_whitelist[$meta_key]['value'])) :
              $meta_whitelist[$meta_key]['value'] = $attribution[$meta_key];
            endif;
          endforeach;
        endif;

      else :

        foreach ($meta_whitelist as $meta_key => &$meta) :
          $meta_value = self::get_active_user_option($user_id, $meta_key);
          $meta['value'] = $meta_value !== false ? $meta_value : '';
        endforeach;

      endif;
    } catch (\Exception $e) {
    }

    return $meta_whitelist;
  }

  public static function update_active_session($user_id, $meta_whitelist)
  {

    $user_id = Nouvello_WeManage_Utm_Functions::sanitize_user_id($user_id);

    if (empty($user_id)) :
      return false;
    endif;

    try {

      $attribution = Nouvello_WeManage_Utm_Service::prepare_attribution_data_for_saving($meta_whitelist, 'active');

      //save attribution
      if (Nouvello_WeManage_Utm_Settings::get('attribution_format') === 'json') :

        //save to meta
        self::update_active_user_option($user_id, 'attribution', Nouvello_WeManage_Utm_Functions::json_encode($attribution));

        //delete old
        if (self::get_active_user_option($user_id, 'cookie_consent') !== false || self::get_active_user_option($user_id, 'sess_visit') !== false) :

          foreach ($meta_whitelist as $meta_key => $meta) :
            self::delete_active_user_option($user_id, $meta_key);
          endforeach;

        endif;

      else :

        foreach ($meta_whitelist as $meta_key => $meta) :

          if (isset($attribution[$meta_key]) && $attribution[$meta_key] !== '' && $attribution[$meta_key] !== null && $attribution[$meta_key] !== false) :
            //update
            self::update_active_user_option($user_id, $meta_key, $attribution[$meta_key]);
          else :
            //delete
            self::delete_active_user_option($user_id, $meta_key);
          endif;

        endforeach;

        //delete old
        self::delete_active_user_option($user_id, 'attribution');

      endif;

      self::update_active_session_timestamp($user_id);

      //set version
      self::update_active_user_option($user_id, 'version', NSWMW_VER);
    } catch (\Exception $e) {
    }
  }

  public static function delete_active_session($user_id)
  {

    $user_id = Nouvello_WeManage_Utm_Functions::sanitize_user_id($user_id);

    if (empty($user_id)) {
      return false;
    }

    $version = self::get_active_user_option($user_id, 'version');

    if (empty($version)) :
      return false;
    endif;

    $meta_whitelist = Nouvello_WeManage_Utm_Service::get_meta_whitelist('active');

    if (self::get_active_user_option($user_id, 'cookie_consent') !== false || self::get_active_user_option($user_id, 'sess_visit') !== false) :

      foreach ($meta_whitelist as $meta_key => $meta) :
        self::delete_active_user_option($user_id, $meta_key);
      endforeach;

    endif;

    self::delete_active_user_option($user_id, 'attribution');
    self::delete_active_user_option($user_id, 'conversions');
    self::delete_active_user_option($user_id, 'updated_ts');
    self::delete_active_user_option($user_id, 'updated_date_utc');
    self::delete_active_user_option($user_id, 'updated_date_local');
    self::delete_active_user_option($user_id, 'has_lead');
    self::delete_active_user_option($user_id, 'has_order');
    self::delete_active_user_option($user_id, 'version');
  }

  public static function update_active_session_timestamp($user_id)
  {

    $user_id = Nouvello_WeManage_Utm_Functions::sanitize_user_id($user_id);

    //updated time
    $time = time();

    self::update_active_user_option($user_id, 'updated_ts', $time);
    self::update_active_user_option($user_id, 'updated_date_utc', Nouvello_WeManage_Utm_Functions::timestamp_to_utc_date_database($time));
    self::update_active_user_option($user_id, 'updated_date_local', Nouvello_WeManage_Utm_Functions::timestamp_to_local_date_database($time));
  }

  public static function reset_active_session_if_expired($user_id = 0)
  {

    $user_id = Nouvello_WeManage_Utm_Functions::sanitize_user_id($user_id);

    if (empty($user_id)) {
      return false;
    }

    $active_session = self::get_active_session($user_id);

    $cookie_expiry_in_days = !empty($active_session['cookie_expiry']['value']) ? intval($active_session['cookie_expiry']['value']) : 0;
    $updated_ts = intval(self::get_active_user_option($user_id, 'updated_ts'));

    if (empty($cookie_expiry_in_days) || empty($updated_ts)) {
      return false;
    }

    $ts_expiry = $updated_ts + ($cookie_expiry_in_days * 86400);
    $ts_now = time();

    //current time over the expiry time
    if ($ts_now < $ts_expiry) :
      return false;
    endif;

    //reset
    self::delete_active_session($user_id);

    return true;
  }

  public static function add_active_conversion($user_id, $conversion_event = array(), $user_synced_session = array())
  {

    $user_id = Nouvello_WeManage_Utm_Functions::sanitize_user_id($user_id);

    if (empty($user_id)) :
      return false;
    endif;

    $conversion_event = Nouvello_WeManage_Utm_Functions::merge_default($conversion_event, array(
      'event' => '',
      'type' => '',
      'data' => ''
    ));
    $conversion_event['data'] = isset($conversion_event['data']) ? $conversion_event['data'] : array();

    //save conversion list
    $conversions = self::get_active_user_option($user_id, 'conversions');

    if (empty($conversions)) :
      $conversions = array();
    endif;

    $conversions[] = $conversion_event;

    $offset_conversions = count($conversions);

    if ($offset_conversions > 5) :
      $offset_conversions = $offset_conversions - 5;
    else :
      $offset_conversions = 0;
    endif;

    $conversions = array_slice($conversions, $offset_conversions, 5);

    self::update_active_user_option($user_id, 'conversions', $conversions);

    switch ($conversion_event['type']):

      case 'lead':

        self::update_active_user_option($user_id, 'has_lead', 1);

        if (!empty($conversion_event['data']['conversion_ts'])) :
          self::update_user_option($user_id, 'last_lead_ts', $conversion_event['data']['conversion_ts']);
          self::update_user_option($user_id, 'last_lead_date_utc', Nouvello_WeManage_Utm_Functions::timestamp_to_utc_date_database($conversion_event['data']['conversion_ts']));
          self::update_user_option($user_id, 'last_lead_date_local', Nouvello_WeManage_Utm_Functions::timestamp_to_local_date_database($conversion_event['data']['conversion_ts']));
        endif;

        break;

      case 'order':

        self::update_active_user_option($user_id, 'has_order', 1);

        if (!empty($conversion_event['data']['conversion_ts'])) :
          self::update_user_option($user_id, 'last_order_ts', $conversion_event['data']['conversion_ts']);
          self::update_user_option($user_id, 'last_order_date_utc', Nouvello_WeManage_Utm_Functions::timestamp_to_utc_date_database($conversion_event['data']['conversion_ts']));
          self::update_user_option($user_id, 'last_order_date_local', Nouvello_WeManage_Utm_Functions::timestamp_to_local_date_database($conversion_event['data']['conversion_ts']));
        endif;

        break;

    endswitch;

    return true;
  }

  public static function get_option($key)
  {

    if (isset(self::$options[$key])) {
      return self::$options[$key];
    } else {
      throw new \Exception('Invalid option key');
    }
  }

  public static function save_conversion_attribution($attribution, $user_id)
  {

    //save attribution
    if (!empty($attribution)) :
      if (Nouvello_WeManage_Utm_Settings::get('attribution_format') === 'json') :
        //save to meta
        self::update_user_option($user_id, 'attribution', Nouvello_WeManage_Utm_Functions::json_encode($attribution));
      else :
        foreach ($attribution as $attribution_key => $attribution_value) :
          self::update_user_option($user_id, $attribution_key, $attribution_value);
        endforeach;
      endif;
    endif;
  }

  public static function get_conversion_attribution($user_id, $scope = 'converted')
  {

    try {

      $meta_whitelist = Nouvello_WeManage_Utm_Service::get_meta_whitelist($scope);

      $user = get_user_by('ID', $user_id);

      if (empty($user)) :
        return $meta_whitelist;
      endif;

      $attribution = self::get_user_option($user_id, 'attribution');

      if (!empty($attribution)) :

        $attribution = Nouvello_WeManage_Utm_Functions::json_decode($attribution);

        //populate value
        if (!empty($attribution) && is_array($attribution)) :
          foreach ($attribution as $meta_key => $meta_value) :
            if (isset($meta_whitelist[$meta_key]['value'])) :
              $meta_whitelist[$meta_key]['value'] = $attribution[$meta_key];
            endif;
          endforeach;
        endif;

      else :

        foreach ($meta_whitelist as $meta_key => &$meta) :
          $meta_value = self::get_user_option($user_id, $meta_key);
          $meta['value'] = $meta_value !== false ? $meta_value : '';
        endforeach;

      endif;
    } catch (\Exception $e) {
    }

    return apply_filters('nouvello_utm_wordpress_user_get_conversion_attribution', $meta_whitelist, $user_id, $scope);
  }

  public static function prepare_conversion_event($user_id, $meta_whitelist)
  {

    $event = Nouvello_WeManage_Utm_Conversion::get_registered_event('wordpress_account');
    $event['data'] = array(
      'conversion_ts' => !empty($meta_whitelist['conversion_ts']['value']) ? $meta_whitelist['conversion_ts']['value'] : time(),
      'sess_visit' => !empty($meta_whitelist['sess_visit']['value']) ? $meta_whitelist['sess_visit']['value'] : '',
      'user_id' => $user_id,
      'blog_id' => get_current_blog_id()
    );

    return $event;
  }

  public static function has_active_attribution($user_id)
  {

    $user_id = Nouvello_WeManage_Utm_Functions::sanitize_user_id($user_id);

    $attribution = self::get_active_user_option($user_id, 'attribution');

    if (!empty($attribution)) :

      $attribution = Nouvello_WeManage_Utm_Functions::json_decode($attribution);

      if (isset($attribution['sess_visit'])) :
        return true;
      endif;

    endif;

    $sess_visit = self::get_active_user_option($user_id, 'sess_visit');

    if ($sess_visit !== false) :
      return true;
    endif;

    return false;
  }
}
