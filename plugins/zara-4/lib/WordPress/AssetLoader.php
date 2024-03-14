<?php
if ( ! class_exists( 'Zara4_WordPress_AssetLoader' ) ) {


  /**
   * Class Zara4_WordPress_AssetLoader
   */
  class Zara4_WordPress_AssetLoader {


    /**
     * Enqueue assets (css and js)
     *
     * @param $hook
     */
    public static function enqueue_assets( $hook ) {

      $is_dashboard = self::is_dashboard( $hook );
      $is_media_page = self::is_media_page( $hook );
      $is_settings_page = self::is_settings_page( $hook );


      if ( $is_dashboard || $is_media_page || $is_settings_page ) {

        self::enqueue_common_assets();

        // Media Page Imports
        if ( $is_media_page ) {
          self::enqueue_media_assets();
        }

        // Settings Page Imports
        if ( $is_settings_page ) {
          self::enqueue_settings_assets();
        }
      }
    }


    private static function is_dashboard($hook) {
      return $hook == 'index.php';
    }


    private static function is_media_page($hook) {
      return $hook == 'upload.php';
    }


    private static function is_settings_page($hook) {
      return $hook == 'settings_page_zara-4';
    }


    /**
     * Enqueue assets common to all pages.
     */
    private static function enqueue_common_assets() {
      /** @noinspection PhpUndefinedFunctionInspection */
      wp_enqueue_script( 'jquery' );

      /** @noinspection PhpUndefinedFunctionInspection */
      wp_enqueue_style( 'zara-4-css', ZARA4_PLUGIN_BASE_URL.'/css/zara-4.min.css', array(), ZARA4_VERSION );
    }


    /**
     * Enqueue the assets for the media page.
     */
    private static function enqueue_media_assets() {

      $version = ZARA4_DEV ? ZARA4_VERSION . '-' . uniqid() : ZARA4_VERSION;

      $settings = new Zara4_WordPress_Settings();

      /** @noinspection PhpUndefinedFunctionInspection */
      wp_enqueue_script( 'media-page', ZARA4_PLUGIN_BASE_URL.'/js/media-page.min.js', array( 'jquery' ), $version );
      /** @noinspection PhpUndefinedFunctionInspection */
      wp_enqueue_script( 'media-page-loader', ZARA4_PLUGIN_BASE_URL.'/js/media-page.js', array( 'jquery' ), $version );
      /** @noinspection PhpUndefinedFunctionInspection */
      wp_localize_script( 'media-page', 'LOADING_URL', ZARA4_PLUGIN_BASE_URL.'/img/loading.gif' );
      /** @noinspection PhpUndefinedFunctionInspection */
      wp_localize_script( 'media-page', 'COMPRESS_ALL_FEATURE_ENABLED', ($settings->compress_all_feature()) ? 'true' : 'false' );

      //
      // Bootstrap
      //
      /** @noinspection PhpUndefinedFunctionInspection */
      wp_enqueue_script( 'bootstrap-js', ZARA4_PLUGIN_BASE_URL.'/packages/bootstrap/3.3.7/js/bootstrap.min.js', array( 'jquery' ), $version );

      //
      // Sortable tables
      //
      /** @noinspection PhpUndefinedFunctionInspection */
      wp_enqueue_script( 'tablesorter', ZARA4_PLUGIN_BASE_URL.'/packages/tablesorter/jquery.tablesorter.min.js', array( 'jquery' ), $version );

      //
      // Modal
      //
      /** @noinspection PhpUndefinedFunctionInspection */
      wp_enqueue_script( 'modal-js', ZARA4_PLUGIN_BASE_URL.'/packages/jquery-modal/v0.7.0/modal.min.js', array( 'jquery' ), $version );
      /** @noinspection PhpUndefinedFunctionInspection */
      wp_enqueue_style( 'modal-css', ZARA4_PLUGIN_BASE_URL.'/packages/jquery-modal/v0.7.0/modal.css', array(), $version );
    }


    /**
     * Enqueue the assets for the settings page.
     */
    private static function enqueue_settings_assets() {

      $version = ZARA4_DEV ? ZARA4_VERSION . '-' . uniqid() : ZARA4_VERSION;

      /** @noinspection PhpUndefinedFunctionInspection */
      wp_enqueue_script( 'settings-page', ZARA4_PLUGIN_BASE_URL.'/js/settings-page.min.js', array( 'jquery' ), $version );
      /** @noinspection PhpUndefinedFunctionInspection */
      wp_localize_script( 'settings-page', 'Z4_BASE_URL', ZARA4_DEV ? 'http://zara4.dev' : 'https://zara4.com' );

      /** @noinspection PhpUndefinedFunctionInspection */
      wp_enqueue_script( 'settings-page-settings', ZARA4_PLUGIN_BASE_URL.'/js/settings-page.js', array( 'jquery' ), $version );

      /** @noinspection PhpUndefinedFunctionInspection */
      wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), $version );

      //
      // Bootstrap
      //
      /** @noinspection PhpUndefinedFunctionInspection */
      wp_enqueue_script( 'bootstrap-js', ZARA4_PLUGIN_BASE_URL.'/packages/bootstrap/3.3.7/js/bootstrap.min.js', array( 'jquery' ), $version );

      //
      // Modal
      //
      /** @noinspection PhpUndefinedFunctionInspection */
      wp_enqueue_script( 'modal-js', ZARA4_PLUGIN_BASE_URL.'/packages/jquery-modal/v0.7.0/modal.min.js', array( 'jquery' ), $version );
      /** @noinspection PhpUndefinedFunctionInspection */
      wp_enqueue_style( 'modal-css', ZARA4_PLUGIN_BASE_URL.'/packages/jquery-modal/v0.7.0/modal.css', array(), $version );
    }

  }

}