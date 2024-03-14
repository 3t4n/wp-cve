<?php
namespace NjtNotificationBar\NotificationBar;

defined('ABSPATH') || exit; 

use NjtNotificationBar\NotificationBar\WpCustomControlColorBg;
use NjtNotificationBar\NotificationBar\WpCustomControlColorText;
use NjtNotificationBar\NotificationBar\WpCustomControlColorLb;
use NjtNotificationBar\NotificationBar\WpCustomControlTextColorLb;
use NjtNotificationBar\NotificationBar\WpCustomControlColorPreset;
use NjtNotificationBar\NotificationBar\WpCustomControlPositionType;
use NjtNotificationBar\NotificationBar\WpCustomControlHandleButton;
use NjtNotificationBar\NotificationBar\WpCustomControlEnableBar;
use NjtNotificationBar\NotificationBar\WpCustomControlMultiselect;
use NjtNotificationBar\NotificationBar\WpCustomControlSelect2;
use NjtNotificationBar\NotificationBar\WpPosts;

class WpCustomNotification
{
  protected static $instance = null;

  public static function getInstance()
  {
    if (null == self::$instance) {
      self::$instance = new self;
    }

    return self::$instance;
  }
  private function __construct()
  {
    $this->valueDefault = apply_filters( 'njt_nofi_notification_bar_default_values', array(
      'align_content'     => 'center',
      'hide_close_button' => 'close_button',
      'content_width'     => '900',
      'position_type'     => 'fixed',
      'link_style'        => 'button',
      'text'              => esc_html('This is default text for notification bar'),
      'lb_text'           => esc_html('Learn more'),
      'lb_url'            => '',
      'new_windown'       => true,
      'text_mobile'       => esc_html('This is default text for notification bar'),
      'lb_text_mobile'    => esc_html('Learn more'),
      'lb_url_mobile'     => '',
      'new_windown_mobile'=> true,
      'preset_color'      => 1,
      'bg_color'          => '#9af4cf',
      'text_color'        => '#1919cf',
      'lb_color'          => '#1919cf',
      'lb_text_color'     => '#ffffff',
      'font_size'         => '15',
      'dp_homepage'       => true,
      'dp_pages'          => true,
      'dp_posts'          => true,
      'devices_display'   => 'all_devices',
      'dp_pp_id'          => '',
      'font_weight_display' => '400',
      'logic_display_page' => 'dis_all_page',
      'logic_display_post' => 'dis_all_post'
    )) ;

    //Set default value for each option text ues wpml translate
    update_option('njt_nofi_text_wpml_translate', get_theme_mod('njt_nofi_text', $this->valueDefault['text']));
    update_option('njt_nofi_text_mobile_wpml_translate', get_theme_mod('njt_nofi_text_mobile', $this->valueDefault['text_mobile']));
    update_option('njt_nofi_lb_text_wpml_translate', get_theme_mod('njt_nofi_lb_text', $this->valueDefault['lb_text']));
    update_option('njt_nofi_lb_text_mobile_wpml_translate', get_theme_mod('njt_nofi_lb_text_mobile', $this->valueDefault['lb_text_mobile']));
    update_option('njt_nofi_lb_url_wpml_translate', get_theme_mod( 'njt_nofi_lb_url', $this->valueDefault['lb_url']));
    update_option('njt_nofi_lb_url_mobile_wpml_translate', get_theme_mod( 'njt_nofi_lb_url_mobile', $this->valueDefault['lb_url_mobile']));

    add_action('customize_register', array( $this, 'njt_nofi_customizeNotification'), 10);
    add_action('admin_enqueue_scripts', array($this, 'addScriptsCustomizer'));
    add_action('wp_enqueue_scripts', array( $this, 'njt_nofi_enqueueCustomizeControls'));
    add_action('customize_save_after', array( $this, 'njt_nofi_customize_save_after'));

    add_action( 'wp_ajax_njt_nofi_text', array( $this, 'njt_nofi_text_shortcode' ) );
  }

  public function njt_nofi_text_shortcode()
  {
    if ( isset( $_POST ) ) {
			$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : null;

			if ( ! wp_verify_nonce( $nonce, 'njt-nofi-notification' ) ) {
				wp_send_json_error( array( 'status' => 'Wrong nonce validate!' ) );
				exit();
			}
    
      $njt_nofi_text = isset( $_POST['text'] ) ? $_POST['text'] : null;
      
      $a = do_shortcode($njt_nofi_text);
      
      wp_send_json_success(wp_unslash($a));
		}
		wp_send_json_error( array( 'message' => 'Update fail!' ) );
  }

   /**
     * Enqueue script for customizer control
     */
  public function njt_nofi_enqueueCustomizeControls()
  {
    if(is_customize_preview()){
      wp_register_script('njt-nofi-admin-customizebar', NJT_NOFI_PLUGIN_URL . 'assets/admin/js/admin-customizebar.js', array('jquery'),NJT_NOFI_VERSION,true);
      wp_enqueue_script('njt-nofi-admin-customizebar');
    }

  }
  public function addScriptsCustomizer(){
    if(is_customize_preview()){
      wp_register_script('njt-nofi-cus-control-select2', NJT_NOFI_PLUGIN_URL . 'assets/admin/js/select2.min.js', array('jquery'), NJT_NOFI_VERSION, true);
      wp_enqueue_script('njt-nofi-cus-control-select2');
      wp_register_style('njt-nofi-cus-control-select2', NJT_NOFI_PLUGIN_URL . 'assets/admin/css/select2.min.css', array(), NJT_NOFI_VERSION);
      wp_enqueue_style('njt-nofi-cus-control-select2');

      wp_register_script('njt-nofi-cus-control', NJT_NOFI_PLUGIN_URL . 'assets/admin/js/admin-customizer-control.js', array('jquery'), NJT_NOFI_VERSION, true);
      wp_enqueue_script('njt-nofi-cus-control');
      wp_register_style('njt-nofi-cus-control', NJT_NOFI_PLUGIN_URL . 'assets/admin/css/admin-customizer-control.css', array(), NJT_NOFI_VERSION);
      wp_enqueue_style('njt-nofi-cus-control');

      wp_localize_script('njt-nofi-cus-control-select2', 'wpNoFi', array(
        'admin_ajax' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce("njt-nofi-cus-control-select2"),
        'list_posts_selected' => WpPosts::get_list_pages_posts_selected(get_theme_mod('njt_nofi_list_display_post')),
        'list_pages_selected' => WpPosts::get_list_pages_posts_selected(get_theme_mod('njt_nofi_list_display_page')),
      ));
    }
  }

  public function njt_nofi_customize_save_after()
  {
    update_option('njt_nofi_text_wpml_translate', get_theme_mod('njt_nofi_text', $this->valueDefault['text']));
    update_option('njt_nofi_text_mobile_wpml_translate', get_theme_mod('njt_nofi_text_mobile', $this->valueDefault['text_mobile']));
    update_option('njt_nofi_lb_text_wpml_translate', get_theme_mod('njt_nofi_lb_text', $this->valueDefault['lb_text']));
    update_option('njt_nofi_lb_text_mobile_wpml_translate', get_theme_mod('njt_nofi_lb_text_mobile', $this->valueDefault['lb_text_mobile']));
	update_option('njt_nofi_lb_url_wpml_translate', get_theme_mod( 'njt_nofi_lb_url', $this->valueDefault['lb_url']));
	update_option('njt_nofi_lb_url_mobile_wpml_translate', get_theme_mod( 'njt_nofi_lb_url_mobile', $this->valueDefault['lb_url_mobile']));
}
  
  public function njt_nofi_sanitizeSelect( $input, $setting ){
          
    $input = sanitize_key($input);

    $choices = $setting->manager->get_control( $setting->id.'_control' )->choices;
                      
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );                
  }

  function njt_nofi_sanitizeCheckbox( $input ){
    //returns true if checkbox is checked
    if(isset($input)) {
      return ( $input ? true : false );
    }
     return false;
  }

  public function njt_nofi_customizeNotification($customNoti)
  {
    $customNoti->add_panel( 'njt_notification-bar', array(
      'title'       => __('Notibar',NJT_NOFI_DOMAIN),
      'description' => __('This is panel WordPress Notification Bar',NJT_NOFI_DOMAIN),
      'priority'    => 10,
    ) );

    /* Option General */
    $customNoti->add_section( 'njt_nofi_general', array(
      'title'    => __( 'General Options',NJT_NOFI_DOMAIN),
      'priority' => 10,
      'panel'    => 'njt_notification-bar',
    ) );

    /*Enable/Disable Notibar*/
    $customNoti->add_setting('njt_nofi_enable_bar', array(
      'default'           => 1,
      'transport'         => 'postMessage',
    ));

    $customNoti->add_control(
      new WpCustomControlEnableBar( $customNoti, 'njt_nofi_enable_bar',
      array(
        'label'    => __('Enable/Disable Notibar', NJT_NOFI_DOMAIN ),
        'section'  => 'njt_nofi_general',
        'settings' => 'njt_nofi_enable_bar'
      )
    ));

    // Option Alignment
    $customNoti->add_setting('njt_nofi_alignment', array(
      'default'           => $this->valueDefault['align_content'],
      'sanitize_callback' => array($this,'njt_nofi_sanitizeSelect'),
      'transport'         => 'postMessage',
    ));
    
    $customNoti->add_control( 'njt_nofi_alignment_control', array(
      'label'           => __( 'Alignment Option', NJT_NOFI_DOMAIN ),
      'section'         => 'njt_nofi_general',
      'settings'        => 'njt_nofi_alignment',
      'type'            => 'select',
      'choices'         => array(
        'center'        => esc_html__( 'Center', NJT_NOFI_DOMAIN ),
        'left'          => esc_html__( 'Left', NJT_NOFI_DOMAIN ),
        'right'         => esc_html__( 'Right', NJT_NOFI_DOMAIN ),
        'space_around' => esc_html__( 'Space around', NJT_NOFI_DOMAIN ),
      ),
    ));

    // Hide/Close Button (No button, Toggle button, Close button)
    $customNoti->add_setting('njt_nofi_hide_close_button', array(
      'default'           => $this->valueDefault['hide_close_button'],
      'sanitize_callback' => array($this,'njt_nofi_sanitizeSelect'),
       'transport'         => 'postMessage',
    ));
    
    $customNoti->add_control( 'njt_nofi_hide_close_button_control', array(
      'label'           => __( 'Hide/Close Button', NJT_NOFI_DOMAIN ),
      'section'         => 'njt_nofi_general',
      'settings'        => 'njt_nofi_hide_close_button',
      'type'            => 'select',
      'choices'         => array(
        'no_button'     => esc_html__( 'No button', NJT_NOFI_DOMAIN ),
        'toggle_button' => esc_html__( 'Toggle button', NJT_NOFI_DOMAIN ),
        'close_button'  => esc_html__( 'Close button', NJT_NOFI_DOMAIN ),
      ),
    ));

    // Content Width (px)
    $customNoti->add_setting('njt_nofi_content_width', array(
      'default'           => $this->valueDefault['content_width'],
      'sanitize_callback' => 'absint', //converts value to a non-negative integer
      'transport'         => 'postMessage'
    ));

    $customNoti->add_control( 'njt_nofi_content_width_control', array(
      'label'    => __( 'Content Width (px)', NJT_NOFI_DOMAIN ),
      'section'  => 'njt_nofi_general',
      'settings' => 'njt_nofi_content_width',
      'type'     => 'number',
    ));

    //Position Type
    $customNoti->add_setting('njt_nofi_position_type', array(
      'default'           => $this->valueDefault['position_type'],
      'sanitize_callback' => 'wp_filter_nohtml_kses',
      'transport'         => 'postMessage'
    ));

    $customNoti->add_control(
      new WpCustomControlPositionType( $customNoti, 'njt_nofi_position_type',
      array(
        'label'    => __( 'Position Type', NJT_NOFI_DOMAIN ),
        'section'  => 'njt_nofi_general',
        'settings' => 'njt_nofi_position_type'
      )
    ));

    /*Content*/
    $customNoti->add_section( 'njt_nofi_content', array(
      'title'    => __( 'Content Options',NJT_NOFI_DOMAIN),
      'priority' => 10,
      'panel'    => 'njt_notification-bar',
    ));

    
    //Text
    $customNoti->add_setting('njt_nofi_text', array(
      'default'           => $this->valueDefault['text'],
      'sanitize_callback' => 'wp_kses_post', //keeps only HTML tags that are allowed in post content
      'transport'         => 'postMessage',
    ));

    $customNoti->selective_refresh->add_partial( 'njt_nofi_text', array(
      'selector'            => '.njt-display-deskop',
      'primarySetting'      => 'njt_nofi_text',
      'container_inclusive' => true,
      'fallback_refresh'    => false,
    ) );

    $customNoti->add_control( 'njt_nofi_text_control', array(
      'label'    => __('Text', NJT_NOFI_DOMAIN ),
      'section'  => 'njt_nofi_content',
      'settings' => 'njt_nofi_text',
      'type'     => 'textarea',
    ));

    //Switch on/off button
    $customNoti->add_setting('njt_nofi_handle_button', array(
      'default'           => 1,
      'transport'         => 'postMessage',
    ));

    $customNoti->add_control(
      new WpCustomControlHandleButton( $customNoti, 'njt_nofi_handle_button',
      array(
        'label'    => __( 'On/Off Button', NJT_NOFI_DOMAIN ),
        'section'  => 'njt_nofi_content',
        'settings' => 'njt_nofi_handle_button'
      )
    ));

    //Link/Button Text
    $customNoti->add_setting('njt_nofi_lb_text', array(
      'default'           => $this->valueDefault['lb_text'],
      'sanitize_callback' => 'wp_filter_nohtml_kses', //removes all HTML from content
      'transport'         => 'postMessage',
    ));

    $customNoti->add_control('njt_nofi_lb_text_control', array(
      'label'    => __('Button Text', NJT_NOFI_DOMAIN ),
      'section'  => 'njt_nofi_content',
      'settings' => 'njt_nofi_lb_text',
      'type'     => 'text',
    ));

    //Link/Button URL
    $customNoti->add_setting('njt_nofi_lb_url', array(
      'default'           => $this->valueDefault['lb_url'],
      'sanitize_callback' => 'esc_url_raw', //cleans URL from all invalid characters
      'transport'         => 'postMessage',
    ));

    $customNoti->add_control('njt_nofi_lb_url_control', array(
      'label'    => __('Button URL', NJT_NOFI_DOMAIN ),
      'section'  => 'njt_nofi_content',
      'settings' => 'njt_nofi_lb_url',
      'type'     => 'text',
    ));

    //Link/Button Font Weight
    $customNoti->add_setting('njt_nofi_lb_font_weight', array(
      'default'           => $this->valueDefault['font_weight_display'],
      'sanitize_callback' => array($this,'njt_nofi_sanitizeSelect'),
       'transport'         => 'postMessage',
    ));
    
    $customNoti->add_control( 'njt_nofi_lb_font_weight_control', array(
      'label'           => __( 'Font Weight:', NJT_NOFI_DOMAIN ),
      'section'         => 'njt_nofi_content',
      'settings'        => 'njt_nofi_lb_font_weight',
      'type'            => 'select',
      'choices'         => array(
        '400'     => esc_html__( 'Normal', NJT_NOFI_DOMAIN ),
        '500'     => esc_html__( 'Medium', NJT_NOFI_DOMAIN ),
        '600'     => esc_html__( 'Semi Bold', NJT_NOFI_DOMAIN ),
        '700'     => esc_html__( 'Bold', NJT_NOFI_DOMAIN ),
      ),
    ));

    //Open in new window
    $customNoti->add_setting('njt_nofi_open_new_windown', array(
      'default'           => $this->valueDefault['new_windown'],
      'sanitize_callback' => array($this, 'njt_nofi_sanitizeCheckbox'),
      'transport'         => 'postMessage',
    ));

    $customNoti->add_control( 'njt_nofi_open_new_windown_control', array(
      'label'    => __( 'Open in new window', NJT_NOFI_DOMAIN ),
      'section'  => 'njt_nofi_content',
      'settings' => 'njt_nofi_open_new_windown',
      'type'     => 'checkbox',
    ));

    //You want different content for mobile
    $customNoti->add_setting('njt_nofi_content_mobile', array(
      'default'           => 0,
      'transport'         => 'postMessage',
    ));

    $customNoti->add_control(
      new WpCustomControlContentMobile( $customNoti, 'njt_nofi_content_mobile',
      array(
        'label'    => __( 'You want different content for mobile?', NJT_NOFI_DOMAIN ),
        'section'  => 'njt_nofi_content',
        'settings' => 'njt_nofi_content_mobile'
      )
    ));
    

    //text mobile
    $customNoti->add_setting('njt_nofi_text_mobile', array(
      'default'           => $this->valueDefault['text_mobile'],
      'sanitize_callback' => 'wp_kses_post', //keeps only HTML tags that are allowed in post content
      'transport'         => 'postMessage',
    ));

    $customNoti->selective_refresh->add_partial( 'njt_nofi_text_mobile', array(
      'selector'            => '.njt-display-mobile',
      'primarySetting'      => 'njt_nofi_text_mobile',
      'container_inclusive' => true,
      'fallback_refresh'    => false,
    ) );

    $customNoti->add_control( 'njt_nofi_text_mobile_control', array(
      'label'    => __('Text', NJT_NOFI_DOMAIN ),
      'section'  => 'njt_nofi_content',
      'settings' => 'njt_nofi_text_mobile',
      'type'     => 'textarea',
    ));

    //Switch on/off button mobiile
    $customNoti->add_setting('njt_nofi_handle_button_mobile', array(
      'default'           => 0,
      'transport'         => 'postMessage',
    ));

    $customNoti->add_control(
      new WpCustomControlHandleButtonMobile( $customNoti, 'njt_nofi_handle_button_mobile',
      array(
        'label'    => __( 'On/Off Button', NJT_NOFI_DOMAIN ),
        'section'  => 'njt_nofi_content',
        'settings' => 'njt_nofi_handle_button_mobile'
      )
    ));

    //Link/Button Text Mobile
    $customNoti->add_setting('njt_nofi_lb_text_mobile', array(
      'default'           => $this->valueDefault['lb_text_mobile'],
      'sanitize_callback' => 'wp_filter_nohtml_kses', //removes all HTML from content
      'transport'         => 'postMessage',
    ));

    $customNoti->add_control('njt_nofi_lb_text_mobile_control', array(
      'label'    => __('Button Text', NJT_NOFI_DOMAIN ),
      'section'  => 'njt_nofi_content',
      'settings' => 'njt_nofi_lb_text_mobile',
      'type'     => 'text',
    ));

    //Link/Button URL Mobile
    $customNoti->add_setting('njt_nofi_lb_url_mobile', array(
      'default'           => $this->valueDefault['lb_url_mobile'],
      'sanitize_callback' => 'esc_url_raw', //cleans URL from all invalid characters
      'transport'         => 'postMessage',
    ));

    $customNoti->add_control('njt_nofi_lb_url_mobile_control', array(
      'label'    => __('Button URL', NJT_NOFI_DOMAIN ),
      'section'  => 'njt_nofi_content',
      'settings' => 'njt_nofi_lb_url_mobile',
      'type'     => 'text',
    ));

    //Link/Button Font Weight Mobild
    $customNoti->add_setting('njt_nofi_lb_font_weight_mobile', array(
      'default'           => $this->valueDefault['font_weight_display'],
      'sanitize_callback' => array($this,'njt_nofi_sanitizeSelect'),
       'transport'         => 'postMessage',
    ));
    
    $customNoti->add_control( 'njt_nofi_lb_font_weight_mobile_control', array(
      'label'           => __( 'Font Weight:', NJT_NOFI_DOMAIN ),
      'section'         => 'njt_nofi_content',
      'settings'        => 'njt_nofi_lb_font_weight_mobile',
      'type'            => 'select',
      'choices'         => array(
        '400'     => esc_html__( 'Normal', NJT_NOFI_DOMAIN ),
        '500'     => esc_html__( 'Medium', NJT_NOFI_DOMAIN ),
        '600'     => esc_html__( 'Semi Bold', NJT_NOFI_DOMAIN ),
        '700'     => esc_html__( 'Bold', NJT_NOFI_DOMAIN ),
      ),
    ));

    //Open in new window mobile
    $customNoti->add_setting('njt_nofi_open_new_windown_mobile', array(
      'default'           => $this->valueDefault['new_windown_mobile'],
      'sanitize_callback' => array($this, 'njt_nofi_sanitizeCheckbox'),
      'transport'         => 'postMessage',
    ));

    $customNoti->add_control( 'njt_nofi_open_new_windown_mobile_control', array(
      'label'    => __( 'Open in new window', NJT_NOFI_DOMAIN ),
      'section'  => 'njt_nofi_content',
      'settings' => 'njt_nofi_open_new_windown_mobile',
      'type'     => 'checkbox',
    ));

    /*Style*/
    $customNoti->add_section( 'njt_nofi_style', array(
      'title'    => __( 'Style Options',NJT_NOFI_DOMAIN),
      'priority' => 10,
      'panel'    => 'njt_notification-bar',
    ));

    //Preset Color
    $customNoti->add_setting( 'njt_nofi_preset_color', array(
        'default' => $this->valueDefault['preset_color'],
        'transport'         => 'postMessage',
      )
    );
    
    $customNoti->add_control(
      new WpCustomControlColorPreset( $customNoti, 'njt_nofi_preset_color',
      array(
        'label'    => __('Preset Color', NJT_NOFI_DOMAIN ),
        'section'  => 'njt_nofi_style',
        'settings' => 'njt_nofi_preset_color'
      )
    ));

    //Background Color
    $customNoti->add_setting( 'njt_nofi_bg_color',
      array(
          'default'           => $this->valueDefault['bg_color'],
          'sanitize_callback' => 'sanitize_hex_color',
          'transport'         => 'postMessage',
      )
    );
    
    $customNoti->add_control(
      new WpCustomControlColorBg( $customNoti, 'njt_nofi_bg_color',
      array(
        'label'    => __('Background Color', NJT_NOFI_DOMAIN ),
        'section'  => 'njt_nofi_style',
        'settings' => 'njt_nofi_bg_color'
      )
    ));

    //Text Color
    $customNoti->add_setting( 'njt_nofi_text_color',
      array(
          'default'           => $this->valueDefault['text_color'],
          'sanitize_callback' => 'sanitize_hex_color',
          'transport'         => 'postMessage',
      )
    );
  
    $customNoti->add_control(
      new WpCustomControlColorText( $customNoti, 'njt_nofi_text_color',
      array(
        'label'    => __('Text Color', NJT_NOFI_DOMAIN ),
        'section'  => 'njt_nofi_style',
        'settings' => 'njt_nofi_text_color',
      )
    ));

    //Button Color 
    $customNoti->add_setting('njt_nofi_lb_color', array(
      'default'           =>$this->valueDefault['lb_color'],
      'sanitize_callback' => 'sanitize_hex_color',
      'transport'         => 'postMessage',
    ));

    $customNoti->add_control(
      new WpCustomControlColorLb( $customNoti, 'njt_nofi_lb_color',
      array(
        'label'    => __('Button Color', NJT_NOFI_DOMAIN ),
        'section'  => 'njt_nofi_style',
        'settings' => 'njt_nofi_lb_color'
      )
    ));

    //Button Text Color
    $customNoti->add_setting('njt_nofi_lb_text_color', array(
      'default'           =>$this->valueDefault['lb_text_color'],
      'sanitize_callback' => 'sanitize_hex_color',
      'transport'         => 'postMessage',
    ));

    $customNoti->add_control(
      new WpCustomControlTextColorLb( $customNoti, 'njt_nofi_lb_text_color',
      array(
        'label'    => __('Button Text Color', NJT_NOFI_DOMAIN ),
        'section'  => 'njt_nofi_style',
        'settings' => 'njt_nofi_lb_text_color'
      )
    ));

    //Font Size (px)
    $customNoti->add_setting('njt_nofi_font_size', array(
      'default'           => $this->valueDefault['font_size'],
      'sanitize_callback' => 'absint', //converts value to a non-negative integer
      'transport'         => 'postMessage'
    ));

    $customNoti->add_control('njt_nofi_font_size_control', array(
      'label'    => __('Font Size (px)', NJT_NOFI_DOMAIN ),
      'section'  => 'njt_nofi_style',
      'settings' => 'njt_nofi_font_size',
      'type'     => 'number',
    ));

    /* Display */
    $customNoti->add_section( 'njt_nofi_display', array(
      'title'    => __( 'Display Options',NJT_NOFI_DOMAIN),
      'priority' => 10,
      'panel'    => 'njt_notification-bar',
    ));

    //Select devices want to display
    $customNoti->add_setting('njt_nofi_devices_display', array(
      'default'           => $this->valueDefault['devices_display'],
      'sanitize_callback' => 'wp_filter_nohtml_kses',
       'transport'         => 'postMessage',
    ));
    
    $customNoti->add_control( 'njt_nofi_devices_display_control', array(
        'label'           => __( 'Select devices want to display', NJT_NOFI_DOMAIN ),
        'section'         => 'njt_nofi_display',
        'settings'        => 'njt_nofi_devices_display',
        'type'            => 'select',
        'choices'         => array(
          'all_devices'   => esc_html__( 'All devices', NJT_NOFI_DOMAIN ),
          'desktop'       => esc_html__( 'Only desktop', NJT_NOFI_DOMAIN ),
          'mobile'        => esc_html__( 'Only mobile', NJT_NOFI_DOMAIN ),
        )
    ));

    //Logic display Pages
    $customNoti->add_setting('njt_nofi_logic_display_page', array(
      'default'           => $this->valueDefault['logic_display_page'],
      'sanitize_callback' => 'wp_filter_nohtml_kses', //removes all HTML from content
      'transport'         => 'postMessage'
    ));

    $customNoti->add_control( 'njt_nofi_logic_display_page',
      array(
        'label'       => __( 'Option display pages', NJT_NOFI_DOMAIN ),
        'section'     => 'njt_nofi_display',
        'settings'    => 'njt_nofi_logic_display_page',
        'type'        => 'select',
        'choices'         => array(
          'dis_all_page'     => esc_html__( 'Display on all page', NJT_NOFI_DOMAIN ),
          'dis_selected_page'     => esc_html__( 'Display on selected page', NJT_NOFI_DOMAIN ),
          'hide_all_page'     => esc_html__( 'Hide on all page', NJT_NOFI_DOMAIN ),
          'hide_selected_page'     => esc_html__( 'Hide on selected page', NJT_NOFI_DOMAIN ),
        ),
    ));
    //List display Pages
    $customNoti->add_setting('njt_nofi_list_display_page', array(
      'default'           => $this->valueDefault['logic_display_page'],
      'sanitize_callback' => 'wp_filter_nohtml_kses', //removes all HTML from content
      'transport'         => 'postMessage'
    ));

    $customNoti->add_control(
      new WpCustomControlMultiselect( $customNoti, 'njt_nofi_list_display_page',
      array(
        'label'       => __( 'Option display pages', NJT_NOFI_DOMAIN ),
        'section'     => 'njt_nofi_display',
        'settings'    => 'njt_nofi_list_display_page',
        'type'        => 'multiple-select',
      )
    ));

    //Logic display Post
    $customNoti->add_setting('njt_nofi_logic_display_post', array(
      'default'           => $this->valueDefault['logic_display_post'],
      'sanitize_callback' => 'wp_filter_nohtml_kses', //removes all HTML from content
      'transport'         => 'postMessage'
    ));

    $customNoti->add_control( 'njt_nofi_logic_display_post', array(
        'label'       => __( 'Option display posts', NJT_NOFI_DOMAIN ),
        'section'     => 'njt_nofi_display',
        'settings'    => 'njt_nofi_logic_display_post',
        'type'        => 'select',
        'choices'               => array(
          'dis_all_post'        => esc_html__( 'Display on all post', NJT_NOFI_DOMAIN ),
          'dis_selected_post'   => esc_html__( 'Display on selected post', NJT_NOFI_DOMAIN ),
          'hide_all_post'       => esc_html__( 'Hide on all post', NJT_NOFI_DOMAIN ),
          'hide_selected_post'  => esc_html__( 'Hide on selected post', NJT_NOFI_DOMAIN ),
        ),
    ));
    //List display Post
    $customNoti->add_setting('njt_nofi_list_display_post', array(
      'default'           => $this->valueDefault['dp_pp_id'],
      'sanitize_callback' => 'wp_filter_nohtml_kses', //removes all HTML from content
      'transport'         => 'postMessage'
    ));

    $customNoti->add_control(
      new WpCustomControlMultiselect( $customNoti, 'njt_nofi_list_display_post',
      array(
        'label'       => __( 'Option display Post', NJT_NOFI_DOMAIN ),
        'section'     => 'njt_nofi_display',
        'settings'    => 'njt_nofi_list_display_post',
        'type'        => 'multiple-select',
      )
    ));

  }
}