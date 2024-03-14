<?php

namespace PodcastImporterSecondLine;

use PodcastImporterSecondLine\Helper\Scheduler as PIS_Helper_Scheduler;

class Controller {

  /**
   * @var Controller;
   */
  protected static $_instance;

  /**
   * @return Controller
   */
  public static function instance(): Controller {
    if( self::$_instance === null )
      self::$_instance = new self();

    return self::$_instance;
  }

  public function setup() {
    load_plugin_textdomain( 'secondline-podcast-importer', false, PODCAST_IMPORTER_SECONDLINE_LANGUAGE_DIRECTORY );

    if( isset( $_GET[ PODCAST_IMPORTER_SECONDLINE_ALIAS . '-action' ] )
        && $_GET[ PODCAST_IMPORTER_SECONDLINE_ALIAS . '-action' ] === 'reset-scheduled-posts' ) {
      add_action( "init", function() {
        if( !current_user_can( PODCAST_IMPORTER_SECONDLINE_SETTINGS_PERMISSION_CAP ) )
          return;

        PIS_Helper_Scheduler::schedule_posts();

        podcast_importer_secondline_redirect( get_admin_url( null, 'site-health.php'), 302 );
        exit;
      });
    }
  }

}