<?php

namespace PodcastImporterSecondLine\Helper;

class Scheduler {

  public static function set_schedule( $timestamp, $interval_in_seconds, $hook, $args = array(), $group = '' ) {
    if( self::is_action_scheduled( $hook ) )
      as_unschedule_all_actions( $hook );

    as_schedule_recurring_action( $timestamp, $interval_in_seconds, $hook, $args, $group );
  }

  public static function is_action_scheduled( $hook, $args = null ) {
    if ( function_exists( 'as_has_scheduled_action' ) ) {
      if ( false === as_has_scheduled_action( $hook, $args ) )
        return false;
    } else {
      if ( false === as_next_scheduled_action( $hook, $args ) )
        return false;
    }

    return true;
  }

  public static function schedule_post_id( $post_id ) {
    $args = [
      'post_id'             => $post_id,
      'timestamp'           => strtotime( 'now' ),
      'interval_in_seconds' => ( 1 * HOUR_IN_SECONDS ),
      'hook'                => PODCAST_IMPORTER_SECONDLINE_SCHEDULER_FEED_PREFIX . $post_id,
      'args'                => [ $post_id ],
      'group'               => PODCAST_IMPORTER_SECONDLINE_SCHEDULER_FEED_GROUP
    ];

    $args = apply_filters( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_scheduler_feed_args', $args );

    self::set_schedule(
      $args[ 'timestamp' ],
      $args[ 'interval_in_seconds' ],
      $args[ 'hook' ],
      $args[ 'args' ],
      $args[ 'group' ]
    );
  }

  public static function schedule_posts() {
    $post_ids = get_posts( [
      'post_type'    	 => PODCAST_IMPORTER_SECONDLINE_POST_TYPE_IMPORT,
      'posts_per_page' => -1,
      'fields'         => 'ids'
    ] );

    foreach( $post_ids as $post_id )
      self::schedule_post_id( $post_id );
  }

  public static function is_everything_scheduled() {
    $post_ids = get_posts( [
      'post_type'    	 => PODCAST_IMPORTER_SECONDLINE_POST_TYPE_IMPORT,
      'posts_per_page' => -1,
      'fields'         => 'ids'
    ] );

    foreach( $post_ids as $post_id )
      if( !self::is_action_scheduled( PODCAST_IMPORTER_SECONDLINE_SCHEDULER_FEED_PREFIX . $post_id ) )
        return false;

    return true;
  }

}