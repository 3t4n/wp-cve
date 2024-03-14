<?php
/*
	Plugin Name: MW Font Changer
	Plugin URI: https://www.zhaket.com/web/mw-font-changer-pro/?affid=AF-61332c0051cb8
	Description: Change your WordPress dashboard and theme font easy and fast :)
	Requires at least: 5.0
	Tested up to: 6.2
	Author: Ghaem
	Author URI: https://www.zhaket.com/web/mw-font-changer-pro/?affid=AF-61332c0051cb8
	Version: 5.3.1
	Tags: font, font changer, font editor, wp font editor, dashboard font editor, MW Font Changer, wp font changer, persian, parsi, persian font, parsi font, persian fonts, farsi, mw font changer pro
	Text Domain: mwfc
	Domain Path: /languages
*/

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

include_once('includes/plugin-site-options.php');
include_once('includes/plugin-dash-options.php');
include_once('includes/wp-head-codes.php');
include_once('includes/admin-head-codes.php');

if (!class_exists('MWFC')) {
  class MWFC
  {
    public function __construct()
    {
      add_action('admin_menu', array($this, 'mwfc_menu'));
      add_action('admin_init', array($this, 'register_mwfcsettings'));
      add_action('init', array($this, 'mwfc_translations'));
      add_action('wp_enqueue_scripts', array($this, 'mwfc_fonts'));
      add_action('admin_enqueue_scripts', array($this, 'mwfc_fonts'));
      add_action('admin_init', array($this, 'mwfc_script_init'));
      add_action('admin_notices', array($this, 'mwfc_pro_version_notice'));
    }

    public function mwfc_pro_version_notice()
    {
      if (get_current_screen()->id != 'settings_page_mwfc-dashboard-font'):
        $user_id = get_current_user_id();
        if (!get_user_meta($user_id, 'mwfc_pro_version_dismissed')):
          ?>
            <div class="mwfc-pro-version-notice notice" id="mwfc-pro-version-notice">
                <p><?php _e('MW Font Change Pro is here. You can now upload your custom fonts, use Google fonts, and have more features.', 'mwfc'); ?></p>
                <a href="https://www.zhaket.com/web/mw-font-changer-pro/?affid=AF-61332c0051cb8"
                   target="_blank"><?php _e('Get MW Font Changer Pro', 'mwfc'); ?></a>
                <a class="notice-dismiss"
                   href="<?php echo esc_url(add_query_arg('mwfc_pro_version_dismissed', '')) ?>"></a>
            </div>
        <?php
        elseif (!get_user_meta($user_id, 'mwfc_pro_version_2_dismissed')):
          ?>
            <div class="mwfc-pro-version-notice notice" id="mwfc-pro-version-notice">
                <p><?php _e('MW Font Changer Pro Big Update Has Been Released.', 'mwfc'); ?></p>
                <a href="https://www.zhaket.com/web/mw-font-changer-pro/?affid=AF-61332c0051cb8"
                   target="_blank"><?php _e('Get MW Font Changer Pro', 'mwfc'); ?></a>
                <a class="notice-dismiss"
                   href="<?php echo esc_url(add_query_arg('mwfc_pro_version_2_dismissed', '')) ?>"></a>
            </div>
        <?php
        endif;
      endif;
    }

    public function mwfc_translations()
    {
      load_plugin_textdomain('mwfc', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    public function mwfc_fonts()
    {
      wp_register_style('mwfcfonts', plugin_dir_url(__FILE__) . 'assets/css/fonts.css', array(), null, false);
      wp_enqueue_style('mwfcfonts');
    }

    public function mwfc_script_init()
    {
      wp_register_style('mwfc-style', plugin_dir_url(__FILE__) . 'assets/css/admin.css', array(), null, false);
      wp_register_style('mwfc-style-rtl', plugin_dir_url(__FILE__) . 'assets/css/admin-rtl.css', array(), null, false);
      $user_id = get_current_user_id();
      if (isset($_GET['mwfc_pro_version_dismissed']))
        add_user_meta($user_id, 'mwfc_pro_version_dismissed', 'true', true);
      if (isset($_GET['mwfc_pro_version_2_dismissed']))
        add_user_meta($user_id, 'mwfc_pro_version_2_dismissed', 'true', true);
    }

    public function mwfc_menu()
    {
      $page_mwfc_site = add_options_page(__("MW Font Changer - Theme", 'mwfc'), __("MW Font Changer - Theme", 'mwfc'), 'manage_options', 'mwfc-theme-font.php', 'mw_site_settings_page');
      $page_mwfc_dash = add_options_page(__("MW Font Changer - Dashboard", 'mwfc'), __("MW Font Changer - Dashboard", 'mwfc'), 'manage_options', 'mwfc-dashboard-font.php', 'mw_dash_settings_page');
      add_action('admin_print_scripts-' . $page_mwfc_site, array($this, 'mwfc_admin_scripts'));
      add_action('admin_print_scripts-' . $page_mwfc_dash, array($this, 'mwfc_admin_scripts'));
      add_action('admin_print_styles-' . $page_mwfc_site, array($this, 'mwfc_admin_scripts'));
      add_action('admin_print_styles-' . $page_mwfc_dash, array($this, 'mwfc_admin_scripts'));
    }

    public function mwfc_admin_scripts()
    {
      if (is_rtl()) {
        wp_enqueue_style('mwfc-style-rtl', plugin_dir_url(__FILE__) . 'assets/css/admin-rtl.css', array(), null, false);
      } else {
        wp_enqueue_style('mwfc-style', plugin_dir_url(__FILE__) . 'assets/css/admin.css', array(), null, false);
      }
    }

    public function register_mwfcsettings()
    {
      register_setting('site_font_settings', 'site_font_settings');
      register_setting('dash_font_settings', 'dash_font_settings');
    }

  }
}
$MWFC = new MWFC();