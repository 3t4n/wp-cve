<?php
/**
 * Plugin Name: Omnivore
 * Description: Connect your WooCommerce to your several marketplaces via Omnivore.
 * Version: 3.12
 * Author: City Beach Software
 * Author URI: https://omnivore.com.au//
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ((get_option('active_plugins') && in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) ||
    (get_site_option('active_sitewide_plugins') && in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', array_keys(get_site_option('active_sitewide_plugins')))))) {

  if (!class_exists('OmnivoreAdmin')) {

    class OmnivoreAdmin {
      const baseUrl = 'https://m1.omnivore.com.au/wooCommerce/connect';
      const flavour = 'omnivore';
      const flavourLabel = 'Omnivore';
      const keyName = 'CityBeach Omnivore';


      public function initAdminMenu() {
        add_menu_page(self::flavourLabel, self::flavourLabel, 'manage_options', self::flavour, array($this, 'dashboard'), null, '59');
        add_submenu_page( null, null, null, 'manage_options', self::flavour . '_connect', array($this, 'connect'));
        add_action( 'admin_init', array($this, 'settings') );
      }


      public function settings() {
        register_setting( self::flavour . '-connection', self::flavour . '_connection_key', array($this, 'settings_key_validate') );
        register_setting( self::flavour . '-connection', self::flavour . '_connection_endpoint', array($this, 'settings_endpoint_validate') );
        register_setting( self::flavour . '-connection', self::flavour . '_connection_email', array($this, 'settings_email_validate') );
        register_setting( self::flavour . '-connection', self::flavour . '_connection_name', array($this, 'settings_name_validate') );
        register_setting( self::flavour . '-google-ads', self::flavour . '_google_ads_enable', array($this, 'settings_trim_validate') );
        register_setting( self::flavour . '-google-ads', self::flavour . '_google_ads_account_id', array($this, 'settings_trim_validate') );
        register_setting( self::flavour . '-google-ads', self::flavour . '_google_ads_conversion_id', array($this, 'settings_trim_validate') );
      }


      private function create_update_api_key() {
        global $wpdb;

        // Remove any existing key.
        $wpdb->delete( $wpdb->prefix . 'woocommerce_api_keys', array( 'description' => self::keyName ), array( '%s' ) );

        $user = wp_get_current_user();

        // Created API keys.
        $permissions     = 'read_write';
        $consumer_key    = 'ck_' . wc_rand_hash();
        $consumer_secret = 'cs_' . wc_rand_hash();

        $detail = array(
          'user_id'         => $user->ID,
          'description'     => self::keyName,
          'permissions'     => $permissions,
          'consumer_key'    => wc_api_hash( $consumer_key ),
          'consumer_secret' => $consumer_secret,
          'truncated_key'   => substr( $consumer_key, -7 ),
        );

        $wpdb->insert($wpdb->prefix . 'woocommerce_api_keys', $detail, array('%d', '%s', '%s', '%s', '%s', '%s',));

        $detail['consumer_key'] = $consumer_key;

        return $detail;
      }


      private static function woocommerce_version() {
        if ( class_exists( 'WooCommerce' ) ) {
          global $woocommerce;
          return $woocommerce->version;
        }
        else {
          return false;
        }
      }


      public static function generate_key() {
        $length = 128;
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyz';
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
          $str .= $keyspace[mt_rand(0, $max)];
        }
        return $str;
      }


      public static function connection_key () {
        global $wpdb;

        $row = $wpdb->get_row(
          $wpdb->prepare(
            "SELECT consumer_key, consumer_secret FROM {$wpdb->prefix}woocommerce_api_keys WHERE description = %s",
            self::keyName
          )
        );

        return $row;
      }

      public function settings_key_validate($input) {
        $existing = get_option(self::flavour . '_connection_key');
        if (!empty($existing)) {
          return $existing;
        }
        else {
          $newinput = trim($input);

          if(!preg_match('/^[a-zA-Z0-9]{128}$/', $newinput)) {

            $newinput = '';
          }
        }
        return $newinput;
      }


      public function settings_endpoint_validate($input) {
        $existing = get_option(self::flavour . '_connection_endpoint');
        if (!empty($existing)) {
          return $existing;
        }
        else {
          $newinput = trim($input);

          if(!filter_var($newinput, FILTER_VALIDATE_URL)) {
            $newinput = '';
          }
        }
        return $newinput;
      }


      public function settings_email_validate($input) {
        $existing = get_option(self::flavour . '_connection_email');
        if (!empty($existing)) {
          return $existing;
        }
        else {
          $newinput = trim($input);

          if(!is_email($newinput)) {
            $newinput = '';
          }
        }
        return $newinput;
      }


      public function settings_name_validate($input) {
        $existing = get_option(self::flavour . '_connection_name');
        if (!empty($existing)) {
          return $existing;
        }
        else {
          $newinput = trim($input);
        }
        return $newinput;
      }

      public function settings_checkbox_validate($input) {
        return $input ? '1' : '0';
      }

      public function settings_trim_validate($input) {
        return trim($input);
      }

      public function dashboard() {
        if (!current_user_can( 'manage_options'))  {
          wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        require_once(__DIR__ . '/connect.php');
      }


      public function connect() {
        if (!current_user_can( 'manage_options'))  {
          wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $key_details = $this->create_update_api_key();
        $connection_key = get_option(self::flavour . '_connection_key');
        $connection_endpoint = get_option(self::flavour . '_connection_endpoint');
        $connection_email = get_option(self::flavour . '_connection_email');
        $connection_name = get_option(self::flavour . '_connection_name');

        $url = self::baseUrl
          . '?' . 'flavour=' . urlencode(self::flavour)
          . '&' . 'storeName=' . urlencode($connection_name)
          . '&' . 'email=' . urlencode($connection_email)
          . '&' . 'endpoint=' . urlencode($connection_endpoint)
          . '&' . 'key=' . urlencode($connection_key)
          . '&' . 'wordpress_version=' . urlencode(get_bloginfo('version'))
          . '&' . 'woocommerce_version=' . urlencode(self::woocommerce_version())
          . '&' . 'consumer_key=' . urlencode($key_details['consumer_key'])
          . '&' . 'truncated_key=' . urlencode($key_details['truncated_key'])
          . '&' . 'consumer_secret=' . urlencode($key_details['consumer_secret']);

        wp_redirect( $url );
      }
    }
  }

  if (!class_exists('Omnivore')) {

    class Omnivore {
      protected $tag = 'omnivore';
      protected $name = ' Omnivore';
      protected $version = '3.0';
      protected $notice = '';

      public function getVersion() {
        return $this->version;
      }

      public function lateLoad() {
        global $pagenow;

        include_once(ABSPATH . 'wp-includes/pluggable.php');

        if ($pagenow === 'admin.php' || $pagenow === "admin-ajax.php") {
          @ob_start();
        }

        $admin = new OmnivoreAdmin();

        add_action('admin_menu', array($admin, 'initAdminMenu'));
      }

      public function __construct() {
        add_action('plugins_loaded', array($this, 'lateLoad'));
        add_action( 'wp_head', array( $this, 'globalSiteHeader' ), 10 );
        add_action( 'wp_head', array( $this, 'conversionEventHeader' ), 20 );
      }

      function globalSiteHeader() {
        $google_ads_enable = get_option($this->tag . '_google_ads_enable');

        if ( boolval( $google_ads_enable ) ) {
          $google_ads_account_id = get_option($this->tag . '_google_ads_account_id');
          if ( $google_ads_account_id ) {
            ?>
              <!-- Global site tag (gtag.js) - Google Ads: <?php echo esc_html($google_ads_account_id); ?> -->
              <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($google_ads_account_id); ?>"></script>
              <script>
                  window.dataLayer = window.dataLayer || [];
                  function gtag(){dataLayer.push(arguments);}
                  gtag('js', new Date());
                  gtag('config', '<?php echo esc_js($google_ads_account_id); ?>');
              </script>
            <?php
          }
        }
      }

      function conversionEventHeader() {
        $google_ads_enable = get_option($this->tag . '_google_ads_enable');

        if ( boolval( $google_ads_enable ) ) {
          $google_ads_account_id = get_option($this->tag . '_google_ads_account_id');
          $google_ads_conversion_id = get_option($this->tag . '_google_ads_conversion_id');

          if ( $google_ads_account_id && $google_ads_conversion_id ) {
            $google_ads_conversion_id_value = '';
            $google_ads_conversion_id_currency = '';
            $google_ads_conversion_id_transaction_id = '';

            if ( function_exists( 'is_order_received_page' ) && is_order_received_page() && isset($GLOBALS['order-received']) ) {
              $order_id = apply_filters('woocommerce_thankyou_order_id', absint($GLOBALS['order-received']));
              $order    = wc_get_order( $order_id );
              if ( $order instanceof WC_Order ) {
                $google_ads_conversion_id_transaction_id = $order->get_id();
                $google_ads_conversion_id_value = $order->get_total();
                $google_ads_conversion_id_currency = $order->get_currency();
              }
              ?>
                <!-- Event snippet for Conversion Action for Account <?php echo esc_html($google_ads_account_id); ?> conversion page -->
                <script>
                    gtag('event', 'conversion', {
                        'send_to': '<?php echo esc_js($google_ads_conversion_id); ?>',
                        'value': <?php echo esc_js($google_ads_conversion_id_value); ?>,
                        'currency': '<?php echo esc_js($google_ads_conversion_id_currency); ?>',
                        'transaction_id': '<?php echo esc_js($google_ads_conversion_id_transaction_id); ?>'
                    });
                </script>
              <?php
            }
          }
        }
      }
    }

    global $omnivore;
    $omnivore = new Omnivore();
  }
}
