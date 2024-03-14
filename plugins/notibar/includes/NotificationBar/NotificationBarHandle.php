<?php
namespace NjtNotificationBar\NotificationBar;

defined('ABSPATH') || exit;

use NjtNotificationBar\NotificationBar\WpCustomNotification;
use NjtNotificationBar\NotificationBar\WpMobileDetect;
use NjtNotificationBar\NotificationBar\WpPosts;

class NotificationBarHandle
{
  protected static $instance = null;
  private $hook_suffix = array();
  private $valueDefault = null;

  public static function getInstance()
  {
    if (null == self::$instance) {
      self::$instance = new self;
    }

    return self::$instance;
  }

  private function __construct()
  {
    $WpCustomNotification = WpCustomNotification::getInstance();
    $this->valueDefault = $WpCustomNotification->valueDefault;

    add_action('admin_menu', array($this, 'njt_nofi_showMenu'));
 
    add_action('wp', array( $this, 'njt_nofi_showNotification'));

    $optionReview = get_option('njt_nofi_review');
    if (time() >= (int)$optionReview && $optionReview !== '0'){
      add_action('admin_notices', array($this, 'njt_nofi_give_review'));
    }
    
    add_action('wp_ajax_njt_nofi_save_review', array($this, 'njt_nofi_save_review'));
    
    //Register Enqueue
    add_action('wp_enqueue_scripts', array($this, 'njt_nofi_homeRegisterEnqueue'));
    add_filter('plugin_action_links_notibar/njt-notification-bar.php', array($this, 'addActionLinks'));
  }

  public function njt_nofi_showMenu()
  {
    global $submenu;

    $settings_suffix = add_submenu_page(
      'options-general.php',
      __('Notification Bar', NJT_NOFI_DOMAIN),
      __('Notibar', NJT_NOFI_DOMAIN),
      'manage_options',
      'njt_nofi_NotificationBar',
      array($this, 'njt_nofi_notificationSettings')
    );
    $urlEncode = urlencode('autofocus[panel]') ;
    $link = esc_html(admin_url('/customize.php?'. $urlEncode.'=njt_notification-bar'));
    if( isset($submenu['options-general.php'])) {
      foreach($submenu['options-general.php'] as $k=>$item){
        if ($item[2] == 'njt_nofi_NotificationBar') {
          $submenu['options-general.php'][$k][2] =  $link;
        }
      }
    }

    $this->hook_suffix = array($settings_suffix);
  }

  public function njt_nofi_homeRegisterEnqueue()
  {
    $isDisplayNotification = $this->njt_nofi_isDisplayNotification();
    $isEnableNotification = get_theme_mod('njt_nofi_enable_bar', 1) == 1 ? true : false;
    $isdevicesDisplay = $this->njt_nofi_devicesDisplay();

    if($this->njt_nofi_checkDisplayNotification() && $isdevicesDisplay) {
      wp_register_style('njt-nofi', NJT_NOFI_PLUGIN_URL . 'assets/frontend/css/notibar.css', array(), NJT_NOFI_VERSION);
      wp_enqueue_style('njt-nofi');

      wp_register_script('njt-nofi', NJT_NOFI_PLUGIN_URL . 'assets/frontend/js/notibar.js', array('jquery'),NJT_NOFI_VERSION, true );
      wp_enqueue_script('njt-nofi');

      wp_localize_script('njt-nofi', 'wpData', array(
        'admin_ajax' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce("njt-nofi-notification"),
        'isPositionFix' => get_theme_mod( 'njt_nofi_position_type', $this->valueDefault['position_type'] ) == 'fixed' ? true : false,
        'hideCloseButton' => get_theme_mod( 'njt_nofi_hide_close_button',$this->valueDefault['hide_close_button']),
        'isDisplayButton' => get_theme_mod( 'njt_nofi_handle_button', 1),
        'presetColor' => get_theme_mod( 'njt_nofi_preset_color', $this->valueDefault['preset_color']),
        'alignContent' => get_theme_mod( 'njt_nofi_alignment', $this->valueDefault['align_content']),
        'textColorNotification' => get_theme_mod('njt_nofi_text_color', $this->valueDefault['text_color']),
        'textButtonColor' => get_theme_mod('njt_nofi_lb_text_color',$this->valueDefault['lb_text_color']),
        'wp_is_mobile' => wp_is_mobile(),
        'is_customize_preview' => is_customize_preview(),
        'wp_get_theme' => wp_get_theme()->get( 'Name' ),
      ));
    }

   
  }

  public function njt_nofi_give_review()
  {
    if (function_exists('get_current_screen')) {
      if (get_current_screen()->id == 'plugins') {
        $this->enqueue_scripts();
        ?>
        <div class="notice notice-success is-dismissible" id="njt-nofi-review">
          <h3><?php _e('Give Notibar a review', NJT_NOFI_DOMAIN)?></h3>
          <p>
            <?php _e('Thank you for choosing Notibar. We hope you love it. Could you take a couple of seconds posting a nice review to share your happy experience?', NJT_NOFI_DOMAIN)?>
          </p>
          <p>
            <?php _e('We will be forever grateful. Thank you in advance ;)', NJT_NOFI_DOMAIN)?>
          </p>
          <p>
            <a href="javascript:;" data="rateNow" class="button button-primary" style="margin-right: 5px"><?php _e('Rate now', NJT_NOFI_DOMAIN)?></a>
            <a href="javascript:;" data="later" class="button" style="margin-right: 5px"><?php _e('Later', NJT_NOFI_DOMAIN)?></a>
            <a href="javascript:;" data="alreadyDid" class="button"><?php _e('Already did', NJT_NOFI_DOMAIN)?></a>
          </p>
        </div>
        <?php
      }
    }
  }

  public function njt_nofi_save_review()
  {
    if ( isset( $_POST ) ) {
      $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : null;
      $field = isset( $_POST['field'] ) ? sanitize_text_field( $_POST['field'] ) : null;

      if ( ! wp_verify_nonce( $nonce, 'njt-nofi-review' ) ) {
        wp_send_json_error( array( 'status' => 'Wrong nonce validate!' ) );
        exit();
      }
      
      if ($field == 'later'){
        update_option('njt_nofi_review', time() + 3*60*60*24); //After 3 days show
      } else if ($field == 'alreadyDid'){
        update_option('njt_nofi_review', 0);
      }
      wp_send_json_success();
    }
    wp_send_json_error( array( 'message' => 'Update fail!' ) );
  }

  public function enqueue_scripts(){
      wp_enqueue_script('njt-nofi-review', NJT_NOFI_PLUGIN_URL . 'assets/admin/js/review.js', array('jquery'), NJT_NOFI_VERSION, false);
      wp_localize_script('njt-nofi-review', 'wpDataNofi', array(
          'admin_ajax' => admin_url('admin-ajax.php'),
          'nonce' => wp_create_nonce("njt-nofi-review"),
      ));
  }

  public function addActionLinks($links) {
    $urlEncode = urlencode('autofocus[panel]') ;
    $linkUrl= esc_html(admin_url('/customize.php?'. $urlEncode.'=njt_notification-bar'));
    $settingsLinks = array(
      '<a href="'.$linkUrl.'">Settings</a>',
    );
    return array_merge($settingsLinks, $links);
  }

 
  public function njt_nofi_notificationSettings()
  {
    exit;
  }

  public function njt_nofi_checkDisplayNotification() {
    $isDisplayNotification = $this->njt_nofi_isDisplayNotification();
    $isEnableNotification = get_theme_mod('njt_nofi_enable_bar', 1) == 1 ? true : false;
    if($isDisplayNotification && is_customize_preview() ) {
      return true;
     }
    if($isDisplayNotification && $isEnableNotification && !is_customize_preview()) {
      return true;
    }
    return false;
  }

  public function njt_nofi_is_page() {
    if ( is_page() 
    || is_home()
    || is_front_page()
    || (function_exists("is_shop") && is_shop()) 
    || (function_exists("is_search") && is_search()) 
    || (function_exists("is_preview") && is_preview()) 
    || (function_exists("is_archive") && is_archive()) 
    || (function_exists("is_date") && is_date()) 
    || (function_exists("is_year") && is_year()) 
    || (function_exists("is_month") && is_month()) 
    || (function_exists("is_day") && is_day()) 
    || (function_exists("is_time") && is_time()) 
    || (function_exists("is_author") && is_author()) 
    || (function_exists("is_category") && is_category()) 
    || (function_exists("is_tag") && is_tag()) 
    || (function_exists("is_tax") && is_tax()) 
    || (function_exists("is_feed") && is_feed()) 
    || (function_exists("is_comment_feed") && is_comment_feed()) 
    || (function_exists("is_trackback") && is_trackback()) 
    || (function_exists("is_404") && is_404()) 
    || (function_exists("is_paged") && is_paged()) 
    || (function_exists("is_attachment") && is_attachment()) 
    || (function_exists("is_robots") && is_robots()) 
    || (function_exists("is_posts_page") && is_posts_page()) 
    || (function_exists("is_post_type_archive") && is_post_type_archive()) ) {
      return true;
    }
    return false;
  }

  public function njt_nofi_isDisplayNotification() {
    global $wp_query;
    $logicDisplayPage = get_theme_mod('njt_nofi_logic_display_page', $this->valueDefault['logic_display_page']);
    $listDisplayPage = explode(',',get_theme_mod('njt_nofi_list_display_page'));
    $logicDisplayPost = get_theme_mod('njt_nofi_logic_display_post', $this->valueDefault['logic_display_post']);
    $listDisplayPost = explode(',',get_theme_mod('njt_nofi_list_display_post'));
    $currentPageOrPostID = $wp_query->get_queried_object_id();

    if ($logicDisplayPage == 'dis_selected_page' ) {
      if(in_array('home_page', $listDisplayPost) && is_home() || in_array('home_page', $listDisplayPost) && is_front_page()) return true;
    }

    if ($logicDisplayPage == 'hide_selected_page' ) {
      if(in_array('home_page', $listDisplayPost) && is_home() || in_array('home_page', $listDisplayPost) && is_front_page()) return false;
    }

    if ( $this->njt_nofi_is_page()) {
      if( $logicDisplayPage == 'dis_all_page' ) return true;
      if( $logicDisplayPage == 'hide_all_page' ) return false;
      if ($logicDisplayPage == 'dis_selected_page' ) {
        if(!empty($listDisplayPage) && in_array($currentPageOrPostID, $listDisplayPage)) return true;
        return false;
      }
      if ($logicDisplayPage == 'hide_selected_page' ) {
        if( !empty($listDisplayPage) && in_array($currentPageOrPostID, $listDisplayPage)) return false;
        return true;
      }
    }

    if (is_single()) {
      if( $logicDisplayPost == 'dis_all_post' ) return true;
      if( $logicDisplayPost == 'hide_all_post' ) return false;
      if ($logicDisplayPost == 'dis_selected_post' ) {
        if(!empty($listDisplayPost) && in_array($currentPageOrPostID, $listDisplayPost)) return true;
        return false;
      }
      if ($logicDisplayPost == 'hide_selected_post' ) {
        if( !empty($listDisplayPost) && in_array($currentPageOrPostID, $listDisplayPost)) return false;
        return true;
      }
    }

    return false;
  }

  public function njt_nofi_devicesDisplay() {
    $isdevicesDisplay = get_theme_mod('njt_nofi_devices_display', $this->valueDefault['devices_display']);
    if($isdevicesDisplay == 'all_devices') {
      return true;
    }
    if ($isdevicesDisplay == 'desktop' && !wp_is_mobile() ) {
      return true;
    }
    if ($isdevicesDisplay == 'mobile' && wp_is_mobile() ) {
      return true;
    }
    return false;
  }


  public function njt_nofi_showNotification()
  {
    // Display Notification Bar.
    $isDisplayNotification = $this->njt_nofi_isDisplayNotification();
    $isEnableNotification = get_theme_mod('njt_nofi_enable_bar', 1) == 1 ? true : false;
    $isdevicesDisplay = $this->njt_nofi_devicesDisplay();
  
    if($isDisplayNotification && $isdevicesDisplay && is_customize_preview()) {
     add_action( 'wp_footer', array( $this, 'display_notification' ),10);
    }

    if($isDisplayNotification && $isEnableNotification && $isdevicesDisplay && !is_customize_preview()) {
      add_action( 'wp_footer', array( $this, 'display_notification' ),10);
     }
    add_action( 'wp_footer', array( $this, 'njt_nofi_rederInput' ),10);
  }

  public function display_notification()
  {
    
    if(wp_get_theme()->get( 'Name' ) == 'Nayma') {
      $widthStyle = 'auto';
    } else {
      $widthStyle = '100%';
    }

    if (wp_is_mobile()) {
      $contentWidth = $widthStyle;
    } else {
      $contentWidth = get_theme_mod('njt_nofi_content_width') != null ? get_theme_mod('njt_nofi_content_width').'px' : $widthStyle;
    }
  
    $isPositionFix = get_theme_mod('njt_nofi_position_type', $this->valueDefault['position_type']) == 'fixed' ? true : false;
    $bgColorNotification = get_theme_mod('njt_nofi_bg_color', $this->valueDefault['bg_color']);
    $textColorNotification = get_theme_mod('njt_nofi_text_color', $this->valueDefault['text_color']);
    $lbColorNotification = get_theme_mod('njt_nofi_lb_color', $this->valueDefault['lb_color']);
    $notificationFontSize = get_theme_mod('njt_nofi_font_size', $this->valueDefault['font_size']);



    if(wp_get_theme()->get( 'Name' ) == 'Nayma') {
      ?>
        <style>
            .njt-nofi-notification-bar .njt-nofi-hide .njt-nofi-close-icon,
            .njt-nofi-display-toggle .njt-nofi-display-toggle-icon {
              width: 10px !important;
              height: 10px !important;
            }
        </style>
      <?php
    }

    ?>
      <style>
        .njt-nofi-notification-bar .njt-nofi-hide-button {
          display: none;
        }
        .njt-nofi-notification-bar .njt-nofi-content {
          font-size : <?php echo esc_html($notificationFontSize.'px') ?>;
        }
        /* body{
          padding-top: 49px;
        } */
      </style>
    <?php

    $viewPath = NJT_NOFI_PLUGIN_PATH . 'views/pages/home/home-notification-bar.php';
    include_once $viewPath;
  }

  public function njt_nofi_rederInput() {
    global $wp_query;
    $dataDisplay = array(
      'is_home' => is_home(),
      'is_page' => is_page(),
      'is_single' => is_single(),
      'id_page' => $wp_query->get_queried_object_id()
    );

    ?>
      <input type="hidden" id="njt_nofi_checkDisplayReview" name="njt_nofi_checkDisplayReview" value='<?php echo (json_encode( $dataDisplay ))?>'>
    <?php
  }
}