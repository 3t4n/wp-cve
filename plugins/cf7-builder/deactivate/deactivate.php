<?php
if ( !defined('ABSPATH') ) {
  exit;
}
define('WC_NEW_LIB_DEACTIVATION_URL', 'http://statistics.webconstruct.tech/');

class WCLibDeactivate {
  public $deactivate_reasons = array();
  public $config;
  public $plugin_dir;
  public $plugin_url;
  // Reason IDs
  const REASON_PLUGIN_IS_HARD_TO_USE_TECHNICAL_PROBLEMS = "reason_plugin_is_hard_to_use_technical_problems";
  const REASON_FREE_VERSION_IS_LIMITED = "reason_free_version_limited";
  const REASON_PRO_EXPENSIVE = "reason_premium_expensive";
  const REASON_UPGRADING_TO_PAID_VERSION = "reason_upgrading_to_paid_version";
  const REASON_TEMPORARY_DEACTIVATION = "reason_temporary_deactivation";

  public function __construct( $config = array() ) {

    $this->config = $config;
    $this->plugin_dir = WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__));
    $this->plugin_url = plugins_url(plugin_basename(dirname(__FILE__)));
    $wd_options = $this->config;
    $this->deactivate_reasons = array(
      1 => array(
        'id' => self::REASON_PLUGIN_IS_HARD_TO_USE_TECHNICAL_PROBLEMS,
        'text' => __('Technical problems / hard to use', 'cf7b'),
      ),
      2 => array(
        'id' => self::REASON_FREE_VERSION_IS_LIMITED,
        'text' => __('Free version is limited', 'cf7b'),
      ),
/*      3 => array(
        'id' => self::REASON_UPGRADING_TO_PAID_VERSION,
        'text' => __('Upgrading to paid version', 'cf7b'),
      ),*/
      4 => array(
        'id' => self::REASON_TEMPORARY_DEACTIVATION,
        'text' => __('Temporary deactivation', 'cf7b'),
      ),
    );
    add_action('admin_footer', array( $this, 'add_deactivation_feedback_box' ));
    add_action('admin_init', array( $this, 'submit_and_deactivate' ));
    // Just enqueue styles/scripts and they will be in the footer.
    $this->scripts();
  }

  public function add_deactivation_feedback_box() {
    $deactivate_reasons = $this->deactivate_reasons;
    ?>
    <script>
      jQuery(function () {
        cf7bReady();
      });
    </script>
    <?php
    $plugin_main_file = CF7B_BUILDER_INT_DIR.'/cf7-builder.php';

    $deactivate_url = add_query_arg(array(
                                      'action' => 'deactivate',
                                      'plugin' => plugin_basename($plugin_main_file),
                                      '_wpnonce' => wp_create_nonce('deactivate-plugin_' . plugin_basename($plugin_main_file)),
                                    ), admin_url('plugins.php'));
    require( $this->plugin_dir . '/deactivation_popup.php' );
  }

  public function scripts() {
    wp_enqueue_style('cf7b-deactivate-popup', $this->plugin_url . '/assets/cf7b_deactivate_popup.css', array(), '1.0.0');
    wp_enqueue_script('cf7b-deactivate-popup',$this->plugin_url . '/assets/cf7b_deactivate_popup.js', array(), '1.0.0');
    $admin_data = wp_get_current_user();
    if( (isset($admin_data->ID) && $admin_data->ID == 0) || !isset($admin_data->ID) ) {
        return;
    }
    wp_localize_script('cf7b-deactivate-popup', 'cf7b_WDDeactivateVars', array(
      "prefix" =>'cf7b',
      "deactivate_class" => 'deactivate-cf7-builder',
      "email" => $admin_data->data->user_email,
      "plugin_wd_url" => '',
    ));
  }

  public function submit_and_deactivate() {
    if ( isset($_POST["cf7b_submit_and_deactivate"]) ) {

      if ( intval($_POST["cf7b_submit_and_deactivate"]) == 2 || intval($_POST["cf7b_submit_and_deactivate"]) == 3 ) {
        $data = array();
        $data["deactivate"] = 'true';
        $data["plugin"] = 'cf7b';
        $data["reason"] = isset($_POST["cf7b_reasons"]) ? sanitize_text_field($_POST["cf7b_reasons"]) : "";
        $data["site_url"] = site_url();
        $data["additional_details"] = isset($_POST["cf7b_additional_details"]) ? sanitize_text_field($_POST["cf7b_additional_details"]) : "";
        $admin_data = wp_get_current_user();
        $data["user_email"] = isset($_POST["cf7b_email"]) ? sanitize_email($_POST["cf7b_email"]) : $admin_data->data->user_email;
        $user_first_name = get_user_meta($admin_data->ID, "first_name", TRUE);
        $user_last_name = get_user_meta($admin_data->ID, "last_name", TRUE);
        $data["user_name"] = $user_first_name || $user_last_name ? $user_first_name . " " . $user_last_name : $admin_data->data->user_login;
        wp_remote_post(WC_NEW_LIB_DEACTIVATION_URL, array(
                                                         'method' => 'POST',
                                                         'timeout' => 45,
                                                         'redirection' => 5,
                                                         'httpversion' => '1.0',
                                                         'body' => $data,
                                                       ));
      }


      if ( intval($_POST["cf7b_submit_and_deactivate"]) == 2 || intval($_POST["cf7b_submit_and_deactivate"]) == 1 ) {
        $plugin_main_file = CF7B_BUILDER_INT_DIR.'/cf7-builder.php';
        $deactivate_url = add_query_arg(array(
                                          'action' => 'deactivate',
                                          'plugin' => plugin_basename($plugin_main_file),
                                          '_wpnonce' => wp_create_nonce('deactivate-plugin_' . plugin_basename($plugin_main_file)),
                                        ), admin_url('plugins.php'));
        echo '<script>window.location.href="' . esc_url($deactivate_url) . '";</script>';
      }
    }
  }
}
