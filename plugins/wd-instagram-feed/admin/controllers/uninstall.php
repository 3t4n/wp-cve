<?php

/**
 * Class Uninstall Controller.
 */
class Uninstall_controller_wdi {

  private $view;

  /**
   * Uninstall_controller_wdi constructor.
   */
  function __construct() {
    require_once(WDI_DIR . '/admin/views/uninstall.php');
    $this->view = new Uninstall_view_wdi();
  }

  /**
   * Execute.
   */
  public function execute() {
    $task = WDILibrary::get('task');
    if ( method_exists($this, $task) ) {
      check_admin_referer('wdi_nonce', 'wdi_nonce');
      $this->$task();
    }
    else {
      $this->view->display();
    }
  }

  /**
   * Display.
   */
  public function display() {
    $this->view->display();
  }

  /**
   * Uninstall.
   */
  private function uninstall() {
    $verify = WDILibrary::get('wdi_verify', 0, 'intval');
    if ( $verify == '1' ) {
      global $wpdb;
      // remove all tables.
      $tables = array( $wpdb->prefix . WDI_FEED_TABLE, $wpdb->prefix . WDI_THEME_TABLE );
      foreach ( $tables as $table ) {
        /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching */
        $checktable = $wpdb->query( $wpdb->prepare('SHOW TABLES LIKE "%s"', $table) ); //db call ok
        if ( $checktable > 0 ) {
          /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching */
          $wpdb->query( 'DROP TABLE IF EXISTS ' . esc_sql($table) ); //db call ok
        }
      }
      $sample_post_id = get_option('wdi_sample_feed_post_id');
      if ( $sample_post_id !== FALSE ) {
        wp_delete_post($sample_post_id, TRUE);
      }

      $rows = get_posts(array(
                          "post_type"   => "wdi_instagram",
                          "numberposts" =>  -1 )
      );

      foreach ( $rows as $row ) {
        wp_delete_post( $row->ID, true );
      }

      // remove all options.
      delete_option(WDI_OPT);
      delete_option('wdi_version');
      delete_option('wdi_sample_feed_id');
      delete_option('wdi_sample_feed_post_id');
      delete_option('wdi_sample_feed_post_url');
      delete_option('wdi_first_user_username');
      delete_option('wdi_theme_keys');
      delete_option('wdi_admin_notice');
      delete_option('wdi_subscribe_done');
      delete_option('wdi_redirect_to_settings');
      delete_option('wdi_token_error_flag');
      delete_option('widget_wdi_instagram_widget');
      delete_option('wdi_current_cache_time');

      // remove all cache.
      require_once (WDI_DIR .'/framework/WDICache.php');
      $CacheClass = new WDICache();
      $CacheClass->reset_cache();

      deactivate_plugins(WDI_MAIN_FILE);
      wp_redirect( admin_url('plugins.php') ); exit;
    }
    else {
      $this->display();
    }
  }
}