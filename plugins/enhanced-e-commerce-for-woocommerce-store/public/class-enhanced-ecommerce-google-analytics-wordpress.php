<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       conversios.io
 * @since      1.0.0
 *
 * @package    Enhanced_Ecommerce_Google_Analytics
 * @subpackage Enhanced_Ecommerce_Google_Analytics/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Enhanced_Ecommerce_Google_Analytics
 * @subpackage Enhanced_Ecommerce_Google_Analytics/public
 * @author     Conversios
 */
require_once(ENHANCAD_PLUGIN_DIR . 'public/class-con-settings.php');
class Enhanced_Ecommerce_Google_Analytics_Wordpress extends Con_Settings
{
  /**
   * Init and hook in the integration.
   *
   * @access public
   * @return void
   */
  //set plugin version
  protected $plugin_name;
  protected $version;
  protected $fb_page_view_event_id;

  /**
   * Enhanced_Ecommerce_Google_Analytics_Public constructor.
   * @param $plugin_name
   * @param $version
   */

  public function __construct($plugin_name, $version)
  {
    parent::__construct();
    $this->gtm = new Con_GTM_Tracking($plugin_name, $version);
    $this->TVC_Admin_Helper = new TVC_Admin_Helper();
    $this->plugin_name = sanitize_text_field($plugin_name);
    $this->version  = sanitize_text_field($version);
    $this->tvc_call_hooks();
    $this->fb_page_view_event_id = $this->get_fb_event_id();

    /*
     * start tvc_options
     */
    $current_user = wp_get_current_user();
    //$current_user ="";
    $user_id = "";
    $user_type = "guest_user";
    if (isset($current_user->ID) && $current_user->ID != 0) {
      $user_id = $current_user->ID;
      $current_user_type = 'register_user';
    }
    $this->tvc_options = array(
      "local_time" => esc_js(time()),
      "is_admin" => esc_attr(is_admin()),
      "tracking_option" => esc_js($this->tracking_option),
      "property_id" => esc_js($this->ga_id),
      "measurement_id" => esc_js($this->gm_id),
      "google_ads_id" => esc_js($this->google_ads_id),
      "google_merchant_center_id" => esc_js($this->google_merchant_id),
      "o_enhanced_e_commerce_tracking" => esc_js($this->ga_eeT),
      "o_log_step_gest_user" => esc_js($this->ga_gUser),
      "o_impression_thresold" => esc_js($this->ga_imTh),
      "o_ip_anonymization" => esc_js($this->ga_IPA),
      "ads_tracking_id" => esc_js($this->ads_tracking_id),
      "remarketing_tags" => esc_js($this->ads_ert),
      "dynamic_remarketing_tags" => esc_js($this->ads_edrt),
      "google_ads_conversion_tracking" => esc_js($this->google_ads_conversion_tracking),
      "conversio_send_to" => esc_js($this->conversio_send_to),
      "ga_EC" => esc_js($this->ga_EC),
      "user_id" => esc_js($user_id),
      "user_type" => esc_js($user_type),
      "day_type" => esc_js($this->add_day_type()),
      "remarketing_snippet_id" => esc_js($this->remarketing_snippet_id),
      "fb_pixel_id" => esc_js($this->fb_pixel_id),
      "fb_conversion_api_token" => esc_js($this->fb_conversion_api_token),
      "fb_event_id" => $this->get_fb_event_id(),
      "tvc_ajax_url" => esc_url(admin_url('admin-ajax.php'))
    );
    /*
     * end tvc_options
     */
  }

  public function tvc_call_hooks()
  {
    /**
     * add global site tag js or settings
     **/
    add_action("wp_head", array($this->gtm, "begin_datalayer"));
    add_action("wp_enqueue_scripts", array($this->gtm, "enqueue_scripts"));
    add_action("wp_head", array($this, "add_google_site_verification_tag"), 1);
    //add_action("wp_footer", array($this->gtm, "add_gtm_data_layer"));

    if ($this->tracking_method == "gtm") {
      add_action("woocommerce_after_cart", array($this->gtm, "product_cart_view"));
    } else {
      add_action("woocommerce_after_cart", array($this, "remove_cart_tracking"));
    }


    //Add Dev ID
    add_action("wp_head", array($this, "add_dev_id"));
    add_action("wp_footer", array($this, "tvc_store_meta_data"));
  }

  /**
   * Google Analytics Day type
   *
   * @access public
   * @return void
   */
  function add_day_type()
  {
    $date = gmdate("Y-m-d");
    $day = strtolower(gmdate('l', strtotime($date)));
    if (($day == "saturday") || ($day == "sunday")) {
      $day_type = esc_html__("weekend", "enhanced-e-commerce-for-woocommerce-store");
    } else {
      $day_type = esc_html__("weekday", "enhanced-e-commerce-for-woocommerce-store");
    }
    return $day_type;
  }
  /*
   * Site verification using tag method
   */
  public function add_google_site_verification_tag()
  {
    $TVC_Admin_Helper = new TVC_Admin_Helper();
    $ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
    if (isset($ee_additional_data['add_site_varification_tag']) && isset($ee_additional_data['site_varification_tag_val']) && $ee_additional_data['add_site_varification_tag'] == 1 && $ee_additional_data['site_varification_tag_val'] != "") {
      echo esc_html(html_entity_decode(base64_decode($ee_additional_data['site_varification_tag_val'])));
    }
  }
  /**
   * Get store meta data for trouble shoot
   * @access public
   * @return void
   */
  function tvc_store_meta_data()
  {
    //only on home page
    global $woocommerce;
    $google_detail = $this->TVC_Admin_Helper->get_ee_options_data();
    $googleDetail = array();
    if (isset($google_detail['setting'])) {
      $googleDetail = $google_detail['setting'];
    }
    $tvc_sMetaData = array(
      'tvc_wpv' => esc_js(get_bloginfo('version')),
      'tvc_eev' => esc_js($this->tvc_eeVer),
      'tvc_cnf' => array(
        't_cg' => esc_js($this->ga_CG),
        't_ec' => esc_js($this->ga_EC),
        't_ee' => esc_js($this->ga_eeT),
        't_df' => esc_js($this->ga_DF),
        't_gUser' => esc_js($this->ga_gUser),
        't_UAen' => esc_js($this->ga_ST),
        't_thr' => esc_js($this->ga_imTh),
        't_IPA' => esc_js($this->ga_IPA),
        //'t_OptOut' => esc_js($this->ga_OPTOUT),
        't_PrivacyPolicy' => esc_js($this->ga_PrivacyPolicy)
      ),
      'tvc_sub_data' => array(
        'sub_id' => esc_js(isset($googleDetail->id) ? sanitize_text_field($googleDetail->id) : ""),
        'cu_id' => esc_js(isset($googleDetail->customer_id) ? sanitize_text_field($googleDetail->customer_id) : ""),
        'pl_id' => esc_js(isset($googleDetail->plan_id) ? sanitize_text_field($googleDetail->plan_id) : ""),
        'ga_tra_option' => esc_js(isset($googleDetail->tracking_option) ? sanitize_text_field($googleDetail->tracking_option) : ""),
        'ga_property_id' => esc_js(isset($googleDetail->property_id) ? sanitize_text_field($googleDetail->property_id) : ""),
        'ga_measurement_id' => esc_js(isset($googleDetail->measurement_id) ? sanitize_text_field($googleDetail->measurement_id) : ""),
        'ga_ads_id' => esc_js(isset($googleDetail->google_ads_id) ? sanitize_text_field($googleDetail->google_ads_id) : ""),
        'ga_gmc_id' => esc_js(isset($googleDetail->google_merchant_center_id) ? sanitize_text_field($googleDetail->google_merchant_center_id) : ""),
        'ga_gmc_id_p' => esc_js(isset($googleDetail->merchant_id) ? sanitize_text_field($googleDetail->merchant_id) : ""),
        'op_gtag_js' => esc_js(isset($googleDetail->add_gtag_snippet) ? sanitize_text_field($googleDetail->add_gtag_snippet) : ""),
        'op_en_e_t' => esc_js(isset($googleDetail->enhanced_e_commerce_tracking) ? sanitize_text_field($googleDetail->enhanced_e_commerce_tracking) : ""),
        'op_rm_t_t' => esc_js(isset($googleDetail->remarketing_tags) ? sanitize_text_field($googleDetail->remarketing_tags) : ""),
        'op_dy_rm_t_t' => esc_js(isset($googleDetail->dynamic_remarketing_tags) ? esc_attr($googleDetail->dynamic_remarketing_tags) : ""),
        'op_li_ga_wi_ads' => esc_js(isset($googleDetail->link_google_analytics_with_google_ads) ? sanitize_text_field($googleDetail->link_google_analytics_with_google_ads) : ""),
        'gmc_is_product_sync' => esc_js(isset($googleDetail->is_product_sync) ? sanitize_text_field($googleDetail->is_product_sync) : ""),
        'gmc_is_site_verified' => esc_js(isset($googleDetail->is_site_verified) ? sanitize_text_field($googleDetail->is_site_verified) : ""),
        'gmc_is_domain_claim' => esc_js(isset($googleDetail->is_domain_claim) ? sanitize_text_field($googleDetail->is_domain_claim) : ""),
        'gmc_product_count' => esc_js(isset($googleDetail->product_count) ? sanitize_text_field($googleDetail->product_count) : ""),
        'fb_pixel_id' => esc_js($this->fb_pixel_id),
        'tracking_method' => esc_js($this->tracking_method),
        'user_gtm_id' => ($this->tracking_method == 'gtm' && $this->want_to_use_your_gtm == 1) ? esc_js($this->use_your_gtm_id) : (($this->tracking_method == 'gtm') ? "conversios-gtm" : "")
      )
    );
    //wp_enqueue_js("tvc_smd=" . wp_json_encode($tvc_sMetaData) . ";");
    //$this->wc_version_compare("tvc_smd=" . wp_json_encode($tvc_sMetaData) . ";", );

    //Show badge in website
    if (!empty(esc_attr($this->conv_show_badge)) && esc_attr($this->conv_show_badge) == "yes") {
      $conv_img_src =  ENHANCAD_PLUGIN_URL . '/public/images/Conversios-logo.png';
      $conv_badge_position = empty($this->conv_badge_position) ? "center" : $this->conv_badge_position;
      $badge_img_link = add_query_arg(
        array(
          'utm_source'   => 'websiteBadge',
          'utm_medium'   => 'websiteBadge',
          'utm_campaign' => 'analyticsbyConversios',
        ),
        'https://www.conversios.io/'
      );
      ?>
      <div style="text-align: <?php echo esc_attr($conv_badge_position); ?>"><a href="<?php echo esc_url($badge_img_link) ?>" target="_blank" rel="nofollow"><img style="display: inline-block" src="<?php echo esc_url($conv_img_src); ?>"/></a></div>
      <?php
    }
  }

  /**
   * add dev id
   *
   * @access public
   * @return void
   */
  function add_dev_id()
  {
?>
    <script>
      (window.gaDevIds = window.gaDevIds || []).push('5CDcaG');
    </script>
  <?php
  }
}
/**
 * GTM Tracking Data Layer Push
 **/
class Con_GTM_Tracking extends Con_Settings
{
  protected $plugin_name;
  protected $version;
  protected $user_data;
  public function __construct($plugin_name, $version)
  {
    parent::__construct();
    $this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->TVC_Admin_Helper = new TVC_Admin_Helper();
    $this->tvc_options = array(
      "affiliation" => esc_js(get_bloginfo('name')),
      "is_admin" => esc_attr(is_admin()),
      "tracking_option" => esc_js($this->tracking_option),
      "property_id" => esc_js($this->ga_id),
      "measurement_id" => esc_js($this->gm_id),
      "google_ads_id" => esc_js($this->google_ads_id),
      "fb_pixel_id" => esc_js($this->fb_pixel_id),
      "fb_conversion_api_token" => esc_js($this->fb_conversion_api_token),
      "fb_event_id" => $this->get_fb_event_id(),
      "tvc_ajax_url" => esc_url(admin_url('admin-ajax.php'))
    );
  }
  public function get_user_data()
  {
    if (empty($this->user_data)) {
      $this->set_user_data();
    }
    return $this->user_data;
  }


  /**
   * begin datalayer like settings
   **/
  public function begin_datalayer()
  {
    if ($this->disable_tracking($this->ga_eeT)) {
      return;
    }
    /*start uset tracking*/
    $enhanced_conversion = array();

    /*end user tracking*/
    $conversio_send_to = array();
    if ($this->conversio_send_to != "") {
      $conversio_send_to = explode("/", $this->conversio_send_to);
    }
    $dataLayer = array("event" => "begin_datalayer");
    if ($this->ga_id != "") {
      $dataLayer["cov_ga3_propety_id"] = esc_js($this->ga_id);
    }
    if ($this->gm_id != "") {
      $dataLayer["cov_ga4_measurment_id"] = esc_js($this->gm_id);
    }
    if ($this->remarketing_snippet_id != "") {
      $dataLayer["cov_remarketing_conversion_id"] = esc_js($this->remarketing_snippet_id);
    }
    $dataLayer["cov_remarketing"] = $this->ads_ert;
    $dataLayer["cov_dynamic_remarketing"] = $this->ads_edrt;
    if ($this->fb_pixel_id != "") {
      $dataLayer["cov_fb_pixel_id"] = esc_js($this->fb_pixel_id);
    }
    if ($this->microsoft_ads_pixel_id != "") {
      $dataLayer["cov_microsoft_uetq_id"] = esc_js($this->microsoft_ads_pixel_id);
    }
    if ($this->twitter_ads_pixel_id != "") {
      $dataLayer["cov_twitter_pixel_id"] = esc_js($this->twitter_ads_pixel_id);
    }
    if ($this->pinterest_ads_pixel_id != "") {
      $dataLayer["cov_pintrest_pixel_id"] = esc_js($this->pinterest_ads_pixel_id);
    }
    if ($this->snapchat_ads_pixel_id != "") {
      $dataLayer["cov_snapchat_pixel_id"] = esc_js($this->snapchat_ads_pixel_id);
    }
    if ($this->tiKtok_ads_pixel_id != "") {
      $dataLayer["cov_tiktok_sdkid"] = esc_js($this->tiKtok_ads_pixel_id);
    }
    if (!empty($enhanced_conversion) &&  $this->ga_EC == 1) {
      $dataLayer =  array_merge($dataLayer, $enhanced_conversion);
    }
    if (!empty($conversio_send_to) && $this->conversio_send_to && $this->google_ads_conversion_tracking == 1) {
      $dataLayer["cov_gads_conversion_id"] = $conversio_send_to[0];
      $dataLayer["cov_gads_conversion_label"] = $conversio_send_to[1];
    }
    if ($this->fb_conversion_api_token != "") {
      $dataLayer["fb_event_id"] = $this->get_fb_event_id();
    }

    if ($this->hotjar_pixel_id != "") {
      $dataLayer["cov_hotjar_pixel_id"] = esc_js($this->hotjar_pixel_id);
    }

    if ($this->crazyegg_pixel_id != "") {
      $dataLayer["cov_crazyegg_pixel_id"] = esc_js($this->crazyegg_pixel_id);
    }

    if ($this->msclarity_pixel_id != "") {
      $dataLayer["cov_msclarity_pixel_id"] = esc_js($this->msclarity_pixel_id);
    }

    $this->add_gtm_begin_datalayer_js($dataLayer);
  }

  /** 
   * dataLayer for setting and GTM global tag
   **/
  public function add_gtm_begin_datalayer_js($data_layer)
  {
    $gtm_id = "GTM-K7X94DG";
    $has_html5_support    = current_theme_supports('html5');
    echo '<script data-cfasync="false" data-pagespeed-no-defer' . ($has_html5_support ? ' type="text/javascript"' : '') . '>
      window.dataLayer = window.dataLayer || [];
      dataLayer.push(' . wp_json_encode($data_layer) . ');
    </script>';
  ?>
    <!-- Google Tag Manager by Conversios-->
    <script>
      (function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
          'gtm.start': new Date().getTime(),
          event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
          j = d.createElement(s),
          dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src =
          'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
      })(window, document, 'script', 'dataLayer', '<?php echo esc_js($gtm_id); ?>');
    </script>
    <!-- End Google Tag Manager -->
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_js($gtm_id); ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
<?php
  }
  /** 
   * DataLayer to JS
   **/
  public function add_gtm_data_layer_js($data_layer)
  {
    $has_html5_support    = current_theme_supports('html5');
    echo '<script data-cfasync="false" data-pagespeed-no-defer' . ($has_html5_support ? ' type="text/javascript"' : '') . '>
      window.dataLayer = window.dataLayer || [];
      dataLayer.push(' . wp_json_encode($data_layer) . ');
    </script>
    ';
  }

  public function enqueue_scripts()
  {
    wp_enqueue_script(esc_js($this->plugin_name), esc_url(ENHANCAD_PLUGIN_URL . '/public/js/con-gtm-google-analytics.js'), array('jquery'), esc_js($this->version), false);
  }
}
