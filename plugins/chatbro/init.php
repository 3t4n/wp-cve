<?php

defined('ABSPATH') or die('No script kiddies please!');

require_once(ABSPATH . '/wp-admin/includes/user.php');

require_once('common/settings/settings.php');
require_once('common/chats/chats.php');
require_once('common/api/api.php');
require_once('common/chats/display_chat.php');
require_once('common/permissions/permissions.php');
require_once('common/user/user.php');
require_once('common/utils/utils.php');
require_once('common/admin/admin.php');
require_once('backends/permissions.php');
require_once('backends/settings.php');
require_once('backends/chats.php');
require_once('backends/api.php');
require_once('backends/user.php');
require_once('backends/utils.php');
require_once('backends/admin.php');
require_once('wp_common.php');
require_once('shortcode.php');
require_once('widget.php');


class CBroInit
{
  const caps_initialized = 'chatbro_caps_initialized';

  public static function load_textdomain()
  {
    // Локализации
    load_plugin_textdomain(
      'chatbro',
      false,
      dirname(plugin_basename(__FILE__)) . '/common/languagess'
    );
  }

  public static function my_plugin_load_my_own_textdomain( $mofile, $domain ) {
    if ( 'chatbro' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
      $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
      $mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/common/languages/' . $domain . '-' . $locale . '.mo';
    }
    return $mofile;
  }

  public static function do_init()
  {
    self::init_permissions();
    self::init_backends();
    self::init_chat_list();
  }

  private static function init_backends()
  {
    CBroUtils::init(new CBroWPUtilsBackend());
    CBroAdmin::init(new CBroWPAdminBackend());
    CBroSettings::init(new CBroWPSettingsBackend());
    CBroChats::init(new CBroWPChatsBackend());
    CBroApi::init(new CBroWPApiBackend());
    CBroUser::init(new CBroWPUserBackend());
    CBroPermissions::init(new CBroWPPermissionsBackend());
    CBroShortCode::get_instance();
  }

  private static function init_chat_list()
  {
    CBroChats::init_chats();
  }

  private static function init_permissions()
  {
    if (!CBroWPCommon::get_option(self::caps_initialized)) {
      // Initializing capabilities with default values
      $adm = get_role('administrator');
      $adm->add_cap(CBroPermissions::cap_delete);
      $adm->add_cap(CBroPermissions::cap_ban);

      foreach (get_editable_roles() as $name => $info) {
        $role = get_role($name);
        $role->add_cap(CBroPermissions::cap_view);
      }

      CBroWPCommon::add_option(self::caps_initialized, true);
    }
  }

  public static function add_menu_option()
  {
    add_menu_page(
      "ChatBro",
      "ChatBro",
      "manage_options",
      "chatbro_settings",
      array('CBroAdmin', 'display'),
      plugins_url() .
      "/chatbro/favicon_small.png"
    );
  }

  public static function chat()
  {
    // Идем по всем чатам и рисуем их, если нужно
    foreach (CBroChats::iterator() as $name => $chat) {
      echo ((new CBroDisplayChat($chat))->get_sitewide_popup_chat_code());
    }
  }

  public static function init()
  {
    add_filter( 'load_textdomain_mofile', array('CBroInit', 'my_plugin_load_my_own_textdomain'), 10, 2 );

    add_action('init', array('CBroInit', 'do_init'));
    add_action('admin_menu', array('CBroInit', 'add_menu_option'));
    add_action('wp_footer', array('CBroInit', 'chat'));

    add_action('wp_ajax_chatbro_save_settings', array('CBroWPCommon', 'ajax_save_settings'));
    add_action('wp_ajax_chatbro_create_chat', array('CBroWPCommon', 'ajax_create_chat'));
    add_action('wp_ajax_chatbro_update_chat', array('CBroWPCommon', 'ajax_update_chat'));
    add_action('wp_ajax_chatbro_delete_chat', array('CBroWPCommon', 'ajax_delete_chat'));
    add_action('wp_ajax_chatbro_get_chats', array('CBroWPCommon', 'ajax_get_chats'));

    add_action('widgets_init', array('CBroWidget', 'register'));
  }
}

?>