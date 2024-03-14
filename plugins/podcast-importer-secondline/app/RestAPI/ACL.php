<?php

namespace PodcastImporterSecondLine\RestAPI;

use WP_Error;

class ACL {

  public static function admin_dismiss_notice() {
    return self::_default();
  }

  public static function get_feed_summary() {
    return self::_default();
  }

  public static function save_feed() {
    return self::_default();
  }

  public static function import_feed() {
    return self::_default();
  }

  public static function sync_feed() {
    return self::_default();
  }

  private static function _default() {
    if ( !current_user_can( PODCAST_IMPORTER_SECONDLINE_SETTINGS_PERMISSION_CAP ) ) {
      return new WP_Error(
        'rest_forbidden',
        sprintf( __( 'You are not allowed to %s.', 'podcast-importer-secondline' ), PODCAST_IMPORTER_SECONDLINE_SETTINGS_PERMISSION_CAP ),
        [
          'status' => 401
        ]
      );
    }

    return true;
  }

}