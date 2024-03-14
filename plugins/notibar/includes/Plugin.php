<?php
namespace NjtNotificationBar;

defined('ABSPATH') || exit;
/**
 * Plugin activate/deactivate logic
 */
class Plugin {
  protected static $instance = null;

  public static function getInstance() {
    if (null == self::$instance) {
      self::$instance = new self;
    }

    return self::$instance;
  }

  private function __construct() {
  }

  /** Plugin activated hook */
  public static function activate() {
    $first_time_active = get_option('njt_nofi_first_time_active');
    $njt_nofi_review = get_option('njt_nofi_review');

    if ($first_time_active === false) {
      update_option('njt_nofi_first_time_active', 1);
      if ($njt_nofi_review !== false) return;
        update_option('njt_nofi_review', time() + 3*60*60*24); //After 3 days show
    }

    $current_version = get_option('njt_nofi_version');
    if ( version_compare(NJT_NOFI_VERSION, $current_version, '>') ) {
      $filebirdCross = \FileBirdCross::get_instance('filebird', 'filebird+ninjateam', NJT_NOFI_PLUGIN_URL, array('filebird/filebird.php', 'filebird-pro/filebird.php'));
      $filebirdCross->need_update_option();
      update_option('njt_nofi_version', NJT_NOFI_VERSION);
      if ($njt_nofi_review !== false) return;
        update_option('njt_nofi_review', time() + 3*60*60*24); //After 3 days show
    }
  }

  /** Plugin deactivate hook */
  public static function deactivate() {
  }
}