<?php

namespace PodcastImporterSecondLine;

use PodcastImporterSecondLine\Helper\Scheduler as Helper_Scheduler;

class SiteHealth {

  /**
   * @var null|SiteHealth;
   */
  protected static $_instance;

  /**
   * @return SiteHealth
   */
  public static function instance(): SiteHealth {
    if( self::$_instance === null )
      self::$_instance = new self();

    return self::$_instance;
  }

  public function tests( $tests ) {
    $tests[ 'direct' ][ PODCAST_IMPORTER_SECONDLINE_ALIAS . '_test_scheduler' ] = [
      'label' => __( '%s - Scheduled Imports', 'podcast-importer-secondline' ),
      'test'  => [ $this, 'test_action_scheduler_jobs' ],
    ];

    return $tests;
  }

  public function test_action_scheduler_jobs(): array {
    $default = [
      'description' => '<p>' . sprintf( __( "%s runs the imports using the action scheduler.", 'podcast-importer-secondline' ), PODCAST_IMPORTER_SECONDLINE_NAME ) . '</p>',
      'test'        => PODCAST_IMPORTER_SECONDLINE_ALIAS . '_test_scheduler',
    ];

    if( Helper_Scheduler::is_everything_scheduled() )
      return [
          'label'   => sprintf( __( "%s - All Actions Scheduled.", 'podcast-importer-secondline' ), PODCAST_IMPORTER_SECONDLINE_NAME ),
          'status'  => 'good',
          'badge'       => [
            'label' => __( 'Performance', 'podcast-importer-secondline' ),
            'color' => 'green',
          ],
        ] + $default;

    return [
        'label'       => sprintf( __( "%s - Missing Actions in schedule.", 'podcast-importer-secondline' ), PODCAST_IMPORTER_SECONDLINE_NAME ),
        'status'      => 'performance',
        'badge'       => [
          'label' => __( 'Performance', 'podcast-importer-secondline' ),
          'color' => 'red',
        ],
        'actions'     => sprintf(
          '<p><a href="%s">%s</a></p>',
          esc_url( admin_url( '?' . PODCAST_IMPORTER_SECONDLINE_ALIAS . '-action=reset-scheduled-posts' ) ),
          __( "Reset Schedules", 'podcast-importer-secondline' )
        )
      ] + $default;
  }

}