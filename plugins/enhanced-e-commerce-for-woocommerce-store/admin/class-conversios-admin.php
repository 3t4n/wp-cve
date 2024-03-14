<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       tatvic.com
 * @since      1.0.0
 *
 * @package    Enhanced_Ecommerce_Google_Analytics
 * @subpackage Enhanced_Ecommerce_Google_Analytics/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Enhanced_Ecommerce_Google_Analytics
 * @subpackage Enhanced_Ecommerce_Google_Analytics/admin
 * @author     Tatvic
 */
if (class_exists('Conversios_Admin') === FALSE) {
  class Conversios_Admin extends TVC_Admin_Helper
  {

    //Google Detail variable
    protected $google_detail;

    //Url onboarding
    protected $url;

    //Version variable
    protected $version;
    public function __construct()
    {
      $this->version = PLUGIN_TVC_VERSION;
      $this->includes();
      $this->url = $this->get_onboarding_page_url(); // use in setting page
      $this->google_detail = $this->get_ee_options_data();
      add_action('admin_menu', array($this, 'add_admin_pages'));
      add_action('admin_init', array($this, 'init'));
      add_action("admin_print_styles", [$this, 'dequeue_css']);
    }

    /********Include Header and Footer **************************/
    public function includes()
    {
      if (class_exists('Conversios_Header') === FALSE) {
        require_once(ENHANCAD_PLUGIN_DIR . 'admin/partials/class-conversios-header.php');
      }

      if (class_exists('Conversios_Footer') === FALSE) {
        require_once(ENHANCAD_PLUGIN_DIR . 'admin/partials/class-conversios-footer.php');
      }
    }

    /*******Add scripts and styles to every page *******************/
    public function init()
    {
      add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
      add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    function dequeue_css()
    {
      $screen = get_current_screen();
      global $wp_styles;
      if (strpos($screen->id, CONV_SCREEN_ID) !== false || $screen->id === 'toplevel_page_conversios') {
        $not_allowed_css = array('porto_admin', 'flashy');
        foreach ($wp_styles->queue as $key => $value) {
          if (in_array($value, $not_allowed_css)) {
            wp_deregister_style($value);
            wp_dequeue_style($value);
          }
        }
      }
    }
    /**
     * Woo Full order refund.
     *
     * @since    1.0.0
     */
    public function action_woocommerce_order_refunded($order_id, $refund_id)
    {
      $data = unserialize(get_option('ee_options'));
      if (
        empty($data['ga_eeT']) ||
        get_post_meta($order_id, "tvc_tracked_refund", true) == 1
      ) {
        return;
      }
      $refund = wc_get_order($refund_id);
      $value = $refund->get_amount();
      $query = urlencode('/refundorders/');
      $currency = $this->get_woo_currency();
      $client_id = mt_rand(1000000000, 9999999999) . "." . time();
      $ga_id = $data['ga_id'];
      if ($ga_id) {
        $url = "https://www.google-analytics.com/collect?v=1&t=event&ni=1&cu=" . $currency . "&ec=Enhanced-Ecommerce&ea=click&el=full_refund&tid=" . $ga_id . "&cid=" . $client_id . "&ti=" . $order_id . "&pa=refund&tr=" . $value . "&dp=" . $query;
        $request = wp_remote_get(esc_url($url), array('timeout' => 120));
      }

      $gm_id = sanitize_text_field($data['gm_id']);
      $api_secret = sanitize_text_field($data['ga4_api_secret']);
      if ($gm_id && $api_secret) {
        $postData = array(
          "client_id" => $client_id,
          "non_personalized_ads" => true,
          "events" => [array(
            "name" => "refund",
            "params" => array(
              "currency" => $currency,
              "transaction_id" => $order_id,
              "value" => $value
            )
          )]
        );
        $args = array(
          'method' => 'POST',
          'body' => wp_json_encode($postData)
        );
        $url = "https://www.google-analytics.com/mp/collect?measurement_id=" . $gm_id . "&api_secret=" . $api_secret;
        $request = wp_remote_post(esc_url_raw($url), $args);
      }

      update_post_meta($order_id, "tvc_tracked_refund", 1);
    }

    /**
     * Woo Partial order refund.
     *
     * @since    1.0.0
     */
    public function woocommerce_partial_order_refunded($order_id, $refund_id)
    {
      $data = unserialize(get_option('ee_options'));
      if (empty($data['ga_eeT'])) {
        return;
      }
      $refund         = wc_get_order($refund_id);
      $value = $refund->get_amount();
      $currency = $this->get_woo_currency();
      $client_id = mt_rand(1000000000, 9999999999) . "." . time();
      $query_params = array();
      $i = 1;
      //GA3
      $ga_id = $data['ga_id'];
      if ($ga_id) {
        foreach ($refund->get_items('line_item') as $item_id => $item) {
          $query_params["pr{$i}id"] = $item['product_id'];
          $query_params["pr{$i}qt"] = abs($item['qty']);
          $query_params["pr{$i}pr"] = abs($item['total']);
          $i++;
        }
        $param_url = http_build_query($query_params, '', '&');
        $url = "https://www.google-analytics.com/collect?v=1&t=event&ni=1&cu=" . $currency . "&ec=Enhanced-Ecommerce&ea=Refund&el=partial_refunded&tid=" . sanitize_text_field($ga_id) . "&cid=" . $client_id . "&tr=" . $value . "&ti=" . $order_id . "&pa=refund&" . $param_url;
        $request = wp_remote_get(esc_url_raw($url), array('timeout' => 120));
      }
      //GA4
      $gm_id = sanitize_text_field($data['gm_id']);
      $api_secret = sanitize_text_field($data['ga4_api_secret']);
      if ($gm_id && $api_secret) {
        $items = array();
        foreach ($refund->get_items('line_item') as $item_id => $item) {
          $items[] = array("item_id" => $item['product_id'], "item_name" => $item['name'], "quantity" => abs($item['qty']), "price" => abs($item['total']), "currency" => $currency);
        }
        $postData = array(
          "client_id" => $client_id,
          "non_personalized_ads" => true,
          "events" => [array(
            "name" => "refund",
            "params" => array(
              "items" => $items,
              "currency" => $currency,
              "transaction_id" => $order_id,
              "value" => $value
            )
          )]
        );
        $args = array(
          'method' => 'POST',
          'body' => wp_json_encode($postData)
        );
        $url = "https://www.google-analytics.com/mp/collect?measurement_id=" . $gm_id . "&api_secret=" . $api_secret;
        $request = wp_remote_post(esc_url_raw($url), $args);
      }
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    4.1.4
     */
    public function enqueue_styles()
    {
      $screen = get_current_screen();
      if ($screen->id === 'toplevel_page_conversios'  || (isset($_GET['page']) === TRUE && strpos(sanitize_text_field($_GET['page']), 'conversios') !== false)) {
        //developres hook to custom css
        do_action('add_conversios_css_' . sanitize_text_field($_GET['page']));
        //conversios page css
        if (sanitize_text_field(wp_unslash(filter_input(INPUT_GET, 'page'))) == "conversios-analytics-reports" || sanitize_text_field(wp_unslash(filter_input(INPUT_GET, 'page'))) == "conversios") {
          wp_register_style('conversios-slick-css', esc_url(ENHANCAD_PLUGIN_URL . '/admin/css/slick.css'));
          wp_enqueue_style('conversios-slick-css');
          wp_register_style('conversios-daterangepicker-css', esc_url(ENHANCAD_PLUGIN_URL . '/admin/css/daterangepicker.css'));
          wp_enqueue_style('conversios-daterangepicker-css');
        } else if (sanitize_text_field($_GET['page']) === "conversios-pmax") {
          wp_register_style('jquery-ui', esc_url(ENHANCAD_PLUGIN_URL . '/includes/setup/plugins/datepicker/jquery-ui.css'));
          wp_enqueue_style('jquery-ui');
        }
        //if ($screen->id != "conversios_page_conversios-google-shopping-feed") {
        wp_enqueue_style('conversios-style-css', esc_url(ENHANCAD_PLUGIN_URL . '/admin/css/style.css'), array(), esc_attr($this->version), 'all');
        //}

        //pricingcss
        if ($screen->id == "conversios_page_conversios-pricings") {
          wp_enqueue_style('conversios-pricing-css', esc_url(ENHANCAD_PLUGIN_URL . '/admin/css/pricing/pricing.css'), array(), esc_attr($this->version), 'all');
        }
        //all conversios page css        
        wp_enqueue_style('conversios-responsive-css', esc_url(ENHANCAD_PLUGIN_URL . '/admin/css/responsive.css'), array(), esc_attr($this->version), 'all');
        if (sanitize_text_field(wp_unslash(filter_input(INPUT_GET, 'wizard'))) == "campaignManagement") {
          wp_register_style('tvc-dataTables-css', esc_url(ENHANCAD_PLUGIN_URL . '/admin/css/dataTables.bootstrap5.min.css'));
          wp_enqueue_style('tvc-dataTables-css');
        }        
      }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    4.1.4
     */
    public function enqueue_scripts()
    {
      $screen = get_current_screen();

      if (isset($_GET['page']) === TRUE && sanitize_text_field($_GET['page']) === "conversios-analytics-reports") {
        wp_enqueue_script('conversios-chart-js', esc_url(ENHANCAD_PLUGIN_URL . '/admin/js/chart.js'));
        wp_enqueue_script('conversios-chart-datalabels-js', esc_url(ENHANCAD_PLUGIN_URL . '/admin/js/chartjs-plugin-datalabels.js'));
        wp_enqueue_script('conversios-basictable-js', esc_url(ENHANCAD_PLUGIN_URL . '/admin/js/jquery.basictable.min.js'));
        if ( ! wp_script_is( 'moment', 'enqueued' ) ) {
          // Enqueue Moment.js only if it's not already enqueued
          wp_enqueue_script('conversios-moment-js', ENHANCAD_PLUGIN_URL . '/admin/js/moment.min.js', array(), '2.22.1', false);
        }
        wp_enqueue_script('conversios-daterangepicker-js', esc_url(ENHANCAD_PLUGIN_URL . '/admin/js/daterangepicker.js'));
        wp_enqueue_script('conversios-custom-js', esc_url(ENHANCAD_PLUGIN_URL . '/admin/js/tvc-ee-custom.js'), array('jquery'), esc_attr($this->version), false);
      } else if (sanitize_text_field(wp_unslash(filter_input(INPUT_GET, 'wizard'))) == "campaignManagement" || isset($_GET['page']) === TRUE && sanitize_text_field($_GET['page']) === "conversios-pmax") {
        wp_enqueue_script('conversios-pmax-js', esc_url(ENHANCAD_PLUGIN_URL . '/admin/js/pmax-custom.js'), array('jquery'), esc_attr($this->version), false);
        wp_register_script('tvc-bootstrap-datepicker-js', esc_url(ENHANCAD_PLUGIN_URL . '/includes/setup/plugins/datepicker/bootstrap-datepicker.min.js'));
        wp_enqueue_script('tvc-bootstrap-datepicker-js');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('tvc-ee-dataTables-js', esc_url(ENHANCAD_PLUGIN_URL . '/admin/js/jquery.dataTables.min.js'), array('jquery'), esc_attr($this->version), false);
        wp_enqueue_script('tvc-ee-dataTables-v5-js', esc_url(ENHANCAD_PLUGIN_URL . '/admin/js/dataTables.bootstrap5.min.js'), array('jquery'), esc_attr($this->version), false);
      } else if (isset($_GET['page']) === TRUE && sanitize_text_field($_GET['page']) === "conversios") {
        if ( ! wp_script_is( 'moment', 'enqueued' ) ) {
          // Enqueue Moment.js only if it's not already enqueued
          wp_enqueue_script('conversios-moment-js', ENHANCAD_PLUGIN_URL . '/admin/js/moment.min.js', array(), '2.22.1', false);
        }
      }      
    }

    /**
     * Display Admin Page.
     *
     * @since    4.1.4
     */
    public function add_admin_pages()
    {
      $google_detail = $this->google_detail;
      if (isset($google_detail['setting'])) {
        $googleDetail = $google_detail['setting'];
      }
      $icon = ENHANCAD_PLUGIN_URL . "/admin/images/offer.png";
      $freevspro = ENHANCAD_PLUGIN_URL . "/admin/images/freevspro.png";

      add_menu_page(
        esc_html(CONV_TOP_MENU, 'enhanced-e-commerce-for-woocommerce-store'),
        esc_html(CONV_TOP_MENU, 'enhanced-e-commerce-for-woocommerce-store') . '',
        'manage_options',
        CONV_MENU_SLUG,
        array($this, 'showPage'),
        esc_url(plugin_dir_url(__FILE__) . 'images/tatvic_logo.png'),
        26
      );
      if (!function_exists('is_plugin_active_for_network')) {
        require_once(ABSPATH . '/wp-admin/includes/woocommerce.php');
      }

      add_submenu_page(
        CONV_MENU_SLUG,
        esc_html__('Dashboard', 'enhanced-e-commerce-for-woocommerce-store'),
        esc_html__('Dashboard', 'enhanced-e-commerce-for-woocommerce-store'),
        'manage_options',
        'conversios',
        '',
        0
      );
      if (CONV_APP_ID == 1) {
        add_submenu_page(
          CONV_MENU_SLUG,
          esc_html__('Pixels & Analytics', 'enhanced-e-commerce-for-woocommerce-store'),
          esc_html__('Pixels & Analytics', 'enhanced-e-commerce-for-woocommerce-store'),
          'manage_options',
          'conversios-google-analytics',
          array($this, 'showPage'),
          2
        );

        add_submenu_page(
          CONV_MENU_SLUG,
          esc_html__('Account Summary', 'enhanced-e-commerce-for-woocommerce-store'),
          esc_html__('Account Summary', 'enhanced-e-commerce-for-woocommerce-store'),
          'manage_options',
          'conversios-account',
          array($this, 'showPage'),
          6
        );

        add_submenu_page(
          CONV_MENU_SLUG,
          esc_html__('Free Vs Pro', 'enhanced-e-commerce-for-woocommerce-store'),
          esc_html__('Free Vs Pro', 'enhanced-e-commerce-for-woocommerce-store') . '<img style="position: absolute; height: 30px;bottom: 5px; right: 10px;" src="' . esc_url($freevspro) . '">',
          'manage_options',
          'conversios-pricings',
          array($this, 'showPage'),
          5
        );
      }

      if (is_plugin_active_for_network('woocommerce/woocommerce.php') || in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        if (CONV_APP_ID == 1) {
          add_submenu_page(
            CONV_MENU_SLUG,
            esc_html__('Reports & Insights', 'enhanced-e-commerce-for-woocommerce-store'),
            esc_html__('Reports & Insights', 'enhanced-e-commerce-for-woocommerce-store'),
            'manage_options',
            'conversios-analytics-reports',
            array($this, 'showPage'),
            1
          );

          add_submenu_page(
            CONV_MENU_SLUG,
            esc_html__('Campaign Management', 'enhanced-e-commerce-for-woocommerce-store'),
            esc_html__('Campaign Management', 'enhanced-e-commerce-for-woocommerce-store'),
            'manage_options',
            'conversios-pmax',
            array($this, 'showPage'),
            4
          );
        }

        add_submenu_page(
          CONV_MENU_SLUG,
          esc_html__('Product Feed', 'enhanced-e-commerce-for-woocommerce-store'),
          '<span class="product_feed_menu"> Product Feed </span>',
          'manage_options',
          'conversios-google-shopping-feed',
          array($this, 'showPage'),
          3
        );
      }
    }

    /**
     * Display page.
     *
     * @since    4.1.4
     */
    public function showPage()
    {
      do_action('add_conversios_header');
      if (!empty(sanitize_text_field($_GET['page']))) {
        $get_action = str_replace("-", "_", sanitize_text_field($_GET['page']));
      } else {
        $get_action = "conversios";
      }
      if (method_exists($this, $get_action)) {
        $this->$get_action();
      }
      $html = $this->get_tvc_popup_message(); 
      echo wp_kses($html, array(
        "div" => array(
          'class' => array(),
          'style' => array(),
          'id' => array(),
          'title' => array(),
        ),
        "span" => array(
          'class' => array(),
          'style' => array(),
          'id' => array(),
          'title' => array(),
          'onclick' => array(),
        ),
        "h4" => array(
          'id' => array(),
        ),
        "p" => array(
          'id' => array(),
        ),
      ));
      do_action('add_conversios_footer');
    }

    // public function conversios()
    // {
    //   require_once(ENHANCAD_PLUGIN_DIR.'includes/setup/class-conversios-dashboard.php');
    // }

    public function conversios()
    {
      $is_inner_page = isset($_GET['tab']) ? sanitize_text_field(wp_unslash(filter_input(INPUT_GET, 'tab'))) : "";
      $is_inner_page = str_replace("-", "_", sanitize_text_field($is_inner_page));
      $is_wizard = isset($_GET['wizard']) ? sanitize_text_field(wp_unslash(filter_input(INPUT_GET, 'wizard'))) : "";
      if ($is_inner_page != "") {
        $this->$is_inner_page();
      } else {
        if ($is_wizard != "" && $is_wizard == "pixelandanalytics") {
          require_once('partials/wizard_pixelandanalytics.php');
        } elseif ($is_wizard != "" && $is_wizard == "productFeedOdd") {
          require_once('partials/wizard-productfeed-odd.php');
        } elseif ($is_wizard != "" && $is_wizard == "productFeedEven") {
          require_once('partials/wizard-productfeed-even.php');
        } elseif ($is_wizard != "" && $is_wizard == "campaignManagement") {
          require_once('partials/wizard-campaign-management.php');
        } else {
          require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/class-conversios-dashboard.php');
        }
      }

      $sub_page = (isset($_GET['subpage'])) ? sanitize_text_field(wp_unslash(filter_input(INPUT_GET, 'subpage'))) : "";
      if ($sub_page == "pixelandanalytics") {
        require_once('partials/wizard_pixelandanalytics.php');
      }
      if ($sub_page == "productfeed") {
        require_once('partials/wizard_productfeed.php');
      }
    }

    public function conversios_pricings()
    {
      require_once(ENHANCAD_PLUGIN_DIR . 'admin/partials/pricings.php');
      new TVC_Pricings();
    }
    public function conversios_account()
    {
      require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/help-html.php');
      require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/account.php');
      new TVC_Account();
    }
    public function conversios_google_analytics()
    {
      //require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/help-html.php');      
      $sub_page = (isset($_GET['subpage']) === TRUE) ? sanitize_text_field($_GET['subpage']) : "";
      if (!empty($sub_page)) {
        require_once('partials/single-pixel-settings.php');
      } else {
        require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/help-html.php');
        require_once('partials/general-fields.php');
      }
    }

    public function conversios_analytics_reports()
    {
      require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/class-conversios-analytics-reports.php');
    }
    public function conversios_pmax()
    {
      $action_tab = (isset($_GET['tab']) === TRUE) ? sanitize_text_field($_GET['tab']) : "";
      if ($action_tab != "") {
        $this->$action_tab();
      } else {
        require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/pmax.php');
        new TVC_PMax();
      }
    }
    public function pmax_add()
    {
      require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/pmax-add.php');
      new TVC_PMaxAdd();
    }
    public function pmax_edit()
    {
      require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/pmax-edit.php');
      new TVC_PMaxEdit();
    }
    public function conversios_google_shopping_feed()
    {
      $action_tab = (isset($_GET['tab'])) ? sanitize_text_field(wp_unslash(filter_input(INPUT_GET, 'tab'))) : "";
      $sub_page = (isset($_GET['subpage'])) ? sanitize_text_field(wp_unslash(filter_input(INPUT_GET, 'subpage'))) : "";
      if (!empty($sub_page)) {
        require_once('partials/single-pixel-settings.php');
      } else if ($action_tab != "") {
        $this->$action_tab();
      } else {
        //new GoogleShoppingFeed();
        require_once ENHANCAD_PLUGIN_DIR . 'includes/setup/class-tvc-product-sync-helper.php';
        require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/help-html.php');
        require_once 'partials/product-feed-list.php';
        //require_once('partials/general-fields-product-feed.php'); // deprecated!
      }
    }
    public function gaa_config_page()
    {
      require_once ENHANCAD_PLUGIN_DIR . 'includes/setup/class-tvc-product-sync-helper.php';
      require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/help-html.php');
      //require_once('partials/general-fields-product-feed.php'); // deprecated!
    }
    public function feed_list()
    {
      require_once ENHANCAD_PLUGIN_DIR . 'includes/setup/help-html.php';
      require_once ENHANCAD_PLUGIN_DIR . 'admin/class-tvc-admin-helper.php';
      require_once ENHANCAD_PLUGIN_DIR . 'includes/setup/CustomApi.php';
      require_once 'partials/product-feed-list.php';
    }
    public function product_list()
    {
      require_once ENHANCAD_PLUGIN_DIR . 'includes/setup/help-html.php';
      require_once ENHANCAD_PLUGIN_DIR . 'admin/class-tvc-admin-helper.php';
      require_once ENHANCAD_PLUGIN_DIR . 'includes/setup/CustomApi.php';
      require_once ENHANCAD_PLUGIN_DIR . 'admin/class-tvc-admin-db-helper.php';
      require_once ENHANCAD_PLUGIN_DIR . 'includes/setup/tatvic-category-wrapper.php';
      require_once ENHANCAD_PLUGIN_DIR . 'includes/setup/class-tvc-product-sync-helper.php';
      require_once 'partials/feedwise-product-list.php';
    }
  }
}
if (is_admin()) {
  new Conversios_Admin();
}
