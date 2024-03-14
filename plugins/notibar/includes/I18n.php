<?php
namespace NjtNotificationBar;

defined('ABSPATH') || exit;
/**
 * I18n Logic
 */
class I18n {
  protected static $instance = null;

  public static function getInstance() {
    if (null == self::$instance) {
      self::$instance = new self;
    }

    return self::$instance;
  }

  private function __construct() {
    add_action('plugins_loaded', array($this, 'loadPluginTextdomain'));
  }

  public static function loadPluginTextdomain() {
    if ( function_exists( 'determine_locale' ) ) {
			$locale = determine_locale();
		} else {
			$locale = is_admin() ? get_user_locale() : get_locale();
		}
		unload_textdomain( NJT_NOFI_DOMAIN );
		load_textdomain( NJT_NOFI_DOMAIN, NJT_NOFI_PLUGIN_PATH . '/i18n/languages/notibar-' . $locale . '.mo' );

    load_plugin_textdomain(
      NJT_NOFI_DOMAIN,
      false,
      NJT_NOFI_PLUGIN_PATH . 'i18n/languages/'
    );
  }
}