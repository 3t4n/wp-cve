<?php

/**
 * Class FFWDControllerUninstall
 */
class FFWDControllerUninstall_ffwd {

  private $view;

  public function __construct() {
    require_once WD_FFWD_DIR . "/admin/views/uninstall.php";
    $this->view = new Uninstall_ffwd();
  }

  /**
   * Execute.
   */
  public function execute() {
    $task = WDW_FFWD_Library::get('task');
    if ( !empty($task) ) {
      if ( !WDW_FFWD_Library::verify_nonce('uninstall_ffwd') ) {
        die('Sorry, your nonce did not verify.');
      }
    }
    if ( method_exists($this, $task) ) {
      $this->$task();
    }
    else {
      $this->display();
    }
  }

  /**
   * Display.
   */
  public function display() {
    $this->view->display();
  }

  /**
   *  Uninstall.
   */
  public function uninstall() {
    global $wpdb;
    $wpdb->query('DROP TABLE ' . $wpdb->prefix . 'wd_fb_info');
    $wpdb->query('DROP TABLE ' . $wpdb->prefix . 'wd_fb_data');
    $wpdb->query('DROP TABLE ' . $wpdb->prefix . 'wd_fb_option');
    $wpdb->query('DROP TABLE ' . $wpdb->prefix . 'wd_fb_theme');
    $wpdb->query('DROP TABLE ' . $wpdb->prefix . 'wd_fb_shortcode');

    delete_option('ffwd_admin_notice');
    delete_option("ffwd_version");
    delete_option('wds_bk_notice_status');
    delete_option('ffwd_old_version');
    delete_option('ffwd_pages_list');

    deactivate_plugins(WD_FFWD_MAIN_FILE);
    wp_redirect(admin_url('plugins.php'));
    exit;
  }
}