<?php defined('ABSPATH') || exit;

class Nouvello_WeManage_Utm_WooCommerce
{
  const META_PREFIX = '_nouvello_utm_';
  const DEFAULT_CONVERSION_TYPE = 'order';

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

  public static function register_hooks()
  {

    //register conversion event
    self::action_register_conversion_events();


    switch (Nouvello_WeManage_Utm_Settings::get('woocommerce_conversion_on')):

      case 'thankyou':

        add_action('wp', array(__CLASS__, 'action_process_conversion_on_thankyou'), 10);

        break;

      case 'checkout_thankyou':

        add_action('woocommerce_checkout_update_order_meta', array(__CLASS__, 'action_woocommerce_checkout_update_order_meta'), 30, 2);
        add_action('wp', array(__CLASS__, 'action_process_conversion_on_thankyou'), 10);

        break;

      default:

        //Checkout
        add_action('woocommerce_checkout_update_order_meta', array(__CLASS__, 'action_woocommerce_checkout_update_order_meta'), 30, 2);

    endswitch;

    //merge tags for {nouvello_utm}
    add_filter('woocommerce_email_format_string', array(__CLASS__, 'filter_woocommerce_email_format_string'), 10, 2);

    //WooCommerce Subscription
    add_filter('wcs_renewal_order_meta_query', __CLASS__ . '::filter_wcs_renewal_order_meta_query', 10, 3);
  }

  public static function get_meta($order, $meta_key, $single = true)
  {

    if (!is_a($order, 'WC_Order')) :

      $order = wc_get_order($order);

    endif;

    return $order->get_meta(self::META_PREFIX . $meta_key, $single);
  }

  public static function update_meta($order, $meta_key, $meta_value)
  {

    if (!is_a($order, 'WC_Order')) :

      $order = wc_get_order($order);

    endif;

    $order->add_meta_data(self::META_PREFIX . $meta_key, $meta_value, true);
    $order->save_meta_data();
  }

  public static function action_woocommerce_checkout_update_order_meta($order_id, $data)
  {

    try {

      self::process_conversion($order_id);
    } catch (\Exception $e) {
    }
  }

  public static function action_register_conversion_events()
  {

    $cookie_conversion_order = Nouvello_WeManage_Utm_Settings::get('cookie_conversion_order');

    Nouvello_WeManage_Utm_Conversion::register_event(array(
      'event' => 'woocommerce',
      'label' => __('WooCommerce', 'ns-wmw'),
      'type' => self::get_conversion_type(),
      'cookie_expiry' => $cookie_conversion_order,
      'css' => 'tw-bg-purple-600 hover:tw-bg-opacity-75 tw-text-white hover:tw-text-white'
    ));
  }

  public static function filter_admin_reports_active_conversion($conversion)
  {

    if (!isset($conversion['event']) || $conversion['event'] !== 'woocommerce') {
      return $conversion;
    }

    if (!empty($conversion['data']['order_id'])) :

      $conversion['data']['url'] = Nouvello_WeManage_Utm_WooCommerce::get_admin_url_order(
        $conversion['data']['order_id'],
        !empty($conversion['data']['blog_id']) ? $conversion['data']['blog_id'] : null
      );

      $conversion['label'] .= ' #' . $conversion['data']['order_id'];
    endif;

    return $conversion;
  }

  public static function filter_woocommerce_email_format_string($text, $email)
  {

    if (
      empty($text)
      || strpos($text, '{nouvello_utm}') === false
      || empty($email->id)
      || empty($email->object)
    ) :
      return $text;
    endif;

    $attribution = '';

    if (is_a($email->object, 'WC_Order')) :

      $attribution = self::get_conversion_attribution($email->object->get_id(), 'email');

    elseif (is_a($email->object, 'WC_Customer')) :

      $attribution = Nouvello_WeManage_Utm_User::get_conversion_attribution($email->object->get_id(), 'email');

    elseif (is_a($email->object, 'WP_User')) :

      $attribution = Nouvello_WeManage_Utm_User::get_conversion_attribution($email->object->ID, 'email');

    endif;

    if (!empty($attribution)) :

      $html = Nouvello_WeManage_Utm_Html::get_conversion_report_table_for_email($attribution);
      $text = str_replace('{nouvello_utm}', $html, $text);

    endif;

    return $text;
  }

  public static function get_admin_url_order($order_id, $blog_id = null)
  {

    return add_query_arg(
      array(
        'post' => urlencode($order_id),
        'action' => 'edit'
      ),
      get_admin_url($blog_id, 'post.php')
    );
  }

  public static function prepare_conversion_event($order_id, $meta_whitelist)
  {

    $event = Nouvello_WeManage_Utm_Conversion::get_registered_event('woocommerce');
    $event['data'] = array(
      'conversion_ts' => !empty($meta_whitelist['conversion_ts']['value']) ? $meta_whitelist['conversion_ts']['value'] : time(),
      'sess_visit' => !empty($meta_whitelist['sess_visit']['value']) ? $meta_whitelist['sess_visit']['value'] : '',
      'order_id' => $order_id,
      'blog_id' => get_current_blog_id()
    );

    return $event;
  }

  public static function save_conversion_attribution($attribution, $order)
  {

    $meta = array();

    //save attribution
    if (!empty($attribution)) :
      if (Nouvello_WeManage_Utm_Settings::get('attribution_format') === 'json') :
        $meta['attribution'] = Nouvello_WeManage_Utm_Functions::json_encode($attribution);
      else :
        $meta = $attribution;
      endif;
    endif;

    $meta['version'] = NSWMW_VER;

    //save meta
    self::update_meta_bulk($order, $meta);
  }

  public static function get_conversion_attribution($order_id, $scope = 'converted')
  {

    try {
      $meta_whitelist = Nouvello_WeManage_Utm_Service::get_meta_whitelist($scope);

      $order = wc_get_order($order_id);

      if (empty($order)) :
        throw new \Exception('Invalid Order ID');
      endif;

      $attribution = self::get_meta($order, 'attribution');

      if (!empty($attribution)) :

        // echo $attribution;


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

        $meta_data = self::get_meta_bulk($order, array_keys($meta_whitelist));

        foreach ($meta_whitelist as $meta_key => &$meta) :
          if (isset($meta_data[$meta_key]) && $meta_data[$meta_key] !== false && $meta_data[$meta_key] !== null) :
            $meta['value'] = $meta_data[$meta_key];
          else :
            $meta['value'] = '';
          endif;
        endforeach;

      endif;
    } catch (\Exception $e) {
    }

    return apply_filters('nouvello_utm_woocommerce_order_get_conversion_attribution', $meta_whitelist, $order_id, $scope);
  }

  public static function get_order_date_created_timestamp($order)
  {

    try {

      $dt_date_created = $order->get_date_created();

      if (!($dt_date_created instanceof WC_DateTime)) :
        return time();
      endif;

      return $dt_date_created->getTimestamp();
    } catch (\Exception $e) {
    }

    return time();
  }

  public static function filter_wcs_renewal_order_meta_query($meta_query, $to_order, $from_order)
  {
    global $wpdb;

    if (!empty(self::META_PREFIX)) :
      $meta_query .= $wpdb->prepare(" AND `meta_key` NOT LIKE %s", $wpdb->esc_like(self::META_PREFIX) . '_%%');
    endif;

    return $meta_query;
  }

  public static function get_converted_session($order_id)
  {

    _deprecated_function('Nouvello_WeManage_Utm_WooCommerce::get_converted_session', '2.4.0', 'Nouvello_WeManage_Utm_WooCommerce::get_conversion_attribution');

    $meta_whitelist = Nouvello_WeManage_Utm_Service::get_meta_whitelist();

    if (!empty($order_id)) :
      foreach ($meta_whitelist as $meta_key => &$meta) :
        $meta['value'] = self::get_meta($order_id, $meta_key, true);
      endforeach;
    endif;

    return apply_filters('nouvello_utm_woocommerce_order_get_converted_session', $meta_whitelist, $order_id);
  }

  public static function calculate_conversion_lag($order)
  {

    _deprecated_function('Nouvello_WeManage_Utm_WooCommerce::calculate_conversion_lag', '2.4.0', 'Nouvello_WeManage_Utm_WooCommerce::prepare_conversion_lag');

    try {

      if (!($order instanceof WC_Order)) {
        return false;
      }

      $order_id = $order->get_id();

      $dt_date_created = $order->get_date_created();

      if (!($dt_date_created instanceof WC_DateTime)) {
        return false;
      }

      $date_created_timestamp = $dt_date_created->getTimestamp();
      $sess_visit_timestamp = Nouvello_WeManage_Utm_WooCommerce::get_meta($order_id, 'sess_visit');

      if ($date_created_timestamp <= 0 || $sess_visit_timestamp <= 0) :
        return false;
      endif;

      Nouvello_WeManage_Utm_WooCommerce::update_meta($order_id, 'conversion_ts', $date_created_timestamp);
      Nouvello_WeManage_Utm_WooCommerce::update_meta($order_id, 'conversion_date_local', Nouvello_WeManage_Utm_Functions::timestamp_to_local_date_database($date_created_timestamp, 'Y-m-d H:i:s'));
      Nouvello_WeManage_Utm_WooCommerce::update_meta($order_id, 'conversion_date_utc', Nouvello_WeManage_Utm_Functions::timestamp_to_utc_date_database($date_created_timestamp, 'Y-m-d H:i:s'));

      $conversion_lag = $date_created_timestamp - $sess_visit_timestamp;
      Nouvello_WeManage_Utm_WooCommerce::update_meta($order_id, 'conversion_lag', $conversion_lag);
      Nouvello_WeManage_Utm_WooCommerce::update_meta($order_id, 'conversion_lag_human', Nouvello_WeManage_Utm_Functions::seconds_to_duration($conversion_lag));
    } catch (\Exception $e) {
      return false;
    }

    return true;
  }

  public static function get_conversion_type()
  {
    return self::DEFAULT_CONVERSION_TYPE;
  }

  public static function process_conversion($order_id)
  {

    if ($order_id instanceof WC_Order) :
      $order = $order_id;
    else :
      $order = wc_get_order($order_id);
    endif;

    if (empty($order)) :
      return;
    endif;

    $order_id = $order->get_id();

    $check_if_run = self::get_meta($order, 'version');

    if ($check_if_run) :
      return;
    endif;

    $user_id = $order->get_customer_id();
    $user_id = Nouvello_WeManage_Utm_Functions::sanitize_user_id($user_id);
    $date_created_timestamp = self::get_order_date_created_timestamp($order);

    //prepare session
    $instance_session = Nouvello_WeManage_Utm_Session::instance();
    $instance_session->setup($user_id);

    $user_synced_session = $instance_session->get('user_synced_session');
    $user_synced_session = Nouvello_WeManage_Utm_Service::prepare_conversion_lag($user_synced_session, $date_created_timestamp);
    $user_synced_session = Nouvello_WeManage_Utm_Service::prepare_conversion_type($user_synced_session, self::get_conversion_type());

    //get conversion event
    $conversion_event = self::prepare_conversion_event($order_id, $user_synced_session);

    //prepare attribution for saving
    $attribution = Nouvello_WeManage_Utm_Service::prepare_attribution_data_for_saving($user_synced_session, 'converted');

    //save conversion attribution
    self::save_conversion_attribution($attribution, $order_id);

    Nouvello_WeManage_Utm_Service::trigger_conversion($conversion_event, $user_synced_session, $user_id);
  }

  public static function action_process_conversion_on_thankyou()
  {

    global $wp;

    try {

      if (!is_order_received_page()) :
        return;
      endif;

      if (isset($_GET['key']) && isset($wp->query_vars['order-received'])) :

        //Default Thank you
        $browser_order_id = absint($wp->query_vars['order-received']);
        $browser_key = wc_clean(wp_unslash($_GET['key']));

      elseif (isset($_GET['wcf-key']) && isset($_GET['wcf-order'])) :

        //Cartflow
        $browser_order_id = absint($_GET['wcf-order']);
        $browser_key = wc_clean(wp_unslash($_GET['wcf-key']));

      endif;

      if (empty($browser_order_id) || empty($browser_key) || !function_exists('wc_get_order')) :
        return;
      endif;

      $order = wc_get_order($browser_order_id);

      if (empty($order)) :
        return;
      endif;

      if ($browser_order_id === $order->get_id() && hash_equals($order->get_order_key(), $browser_key)) :
        self::process_conversion($order->get_id());
      endif;
    } catch (\Exception $e) {
    }
  }

  public static function update_meta_bulk($order, $meta_data)
  {

    if (!is_a($order, 'WC_Order')) :

      $order = wc_get_order($order);

      if ($order === false) :
        return false;
      endif;

    endif;

    if (!is_array($meta_data)) :
      return false;
    endif;

    foreach ($meta_data as $meta_key => $meta_value) :

      $order->add_meta_data(self::META_PREFIX . $meta_key, $meta_value, true);

    endforeach;

    $order->save_meta_data();

    return true;
  }

  public static function get_meta_bulk($order, $meta_key_list)
  {

    $output = array();

    if (!is_a($order, 'WC_Order')) :

      $order = wc_get_order($order);

      if ($order === false) :
        return false;
      endif;

    endif;

    if (!is_array($meta_key_list)) :
      return false;
    endif;

    foreach ($meta_key_list as $meta_key) :

      $output[$meta_key] = $order->get_meta(self::META_PREFIX . $meta_key, true);

    endforeach;

    return $output;
  }
}
