<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       elementorplus.net
 * @since      1.0.0
 *
 * @package    Kitpack_Lite
 * @subpackage Kitpack_Lite/public/partials
 */
if (!defined('ABSPATH')) {die;}

if (class_exists('KPE')) {
    $prefix = 'kpe_option';

    /**
    * Create Options
    */
    KPE::createOptions( $prefix, array(
    
      // framework title
      'framework_title'         => 'کیت پک المنتور <small>lite</small>',
      'framework_class'         => 'kpe-header-options',
  
      // menu settings
      'menu_title'              => 'کیت پک المنتور',
      'menu_slug'               => 'kitpack',
      'menu_type'               => 'menu',
      'menu_capability'         => 'manage_options',
      'menu_icon'               => 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.44 511.44"><title>KP</title><g id="Layer_2" data-name="Layer 2"><g id="transparent-KP"><path id="KP" d="M255.72,0C114.49,0,0,114.49,0,255.72S114.49,511.44,255.72,511.44,511.44,397,511.44,255.72,397,0,255.72,0ZM154,366.44H88v-222h66Zm58.27,0-57.57-99h67.6l56.71,99Zm9.16-122H154.68q28.35-49.5,56.71-99H279ZM431.23,286Q406.5,311,372.65,311c-3.69,0-9.12-.39-16.28-1.14V242.82q7.49,6,15,6a21.76,21.76,0,0,0,16.2-7,23.09,23.09,0,0,0,6.75-16.6,21.9,21.9,0,0,0-7-16.36,23.28,23.28,0,0,0-16.76-6.75q-23.43,0-23.44,32.38V367.65H286.23V232.25q0-37.27,19.53-60.71a79.9,79.9,0,0,1,28.56-21.48,84.31,84.31,0,0,1,35.56-8q36.28,0,61.19,24.41T456,226.39Q456,260.89,431.23,286Z" style="fill:#fff"/></g></g></svg>'),
      'menu_position'           => '58',
      'menu_hidden'             => false,
      'menu_parent'             => '',
  
      // menu extras
      'show_bar_menu'           => true,
      'show_sub_menu'           => false,
      'show_in_network'         => true,
      'show_in_customizer'      => false,
  
      'show_search'             => true,
      'show_reset_all'          => true,
      'show_reset_section'      => true,
      'show_footer'             => true,
      'show_all_options'        => true,
      'show_form_warning'       => true,
      'sticky_header'           => true,
      'save_defaults'           => true,
      'ajax_save'               => true,
  
      // admin bar menu settings
      'admin_bar_menu_icon'     => '',
      'admin_bar_menu_priority' => 80,
  
      // footer
      'footer_text'             => 'طراحی و توسعه: علی رحمانی | پشتیبانی: المنتور پلاس',
      'footer_after'            => '',
      'footer_credit'           => 'قدرت گرفته از "کیت پک" توسط المنتور پلاس',
  
      // database model
      'database'                => '', // options, transient, theme_mod, network
      'transient_time'          => 0,
  
      // contextual help
      'contextual_help'         => array(),
      'contextual_help_sidebar' => '',
  
      // typography options
      'enqueue_webfont'         => true,
      'async_webfont'           => false,
  
      // others
      'output_css'              => true,
  
      // theme and wrapper classname
      'nav'                     => 'normal',
      'theme'                   => 'dark',
      'class'                   => '',
  
      // external default values
      'defaults'                => array(),
  
    ) );
    
    /**
     * Create General Section
     */
    require_once plugin_dir_path( __FILE__ ) .'kitpack-lite-option-general.php';
    
    /**
     * Create Kits Section
     */
    require_once plugin_dir_path( __FILE__ ) .'kitpack-lite-option-kits.php';

    /**
     * Create Translate Section
     */
    require_once plugin_dir_path( __FILE__ ) .'kitpack-lite-option-translate.php';

    /**
     * Create Icons Section
     */
    require_once plugin_dir_path( __FILE__ ) .'kitpack-lite-option-icons.php';

    /**
     * Create Fonts Section
     */
    require_once plugin_dir_path( __FILE__ ) .'kitpack-lite-option-fonts.php';

    /**
     * Create About Section
     */
    require_once plugin_dir_path( __FILE__ ) .'kitpack-lite-option-about.php';
}