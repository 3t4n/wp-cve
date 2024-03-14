<?php

namespace PodcastImporterSecondLine;

use WP_REST_Server;

class RestAPI {

  /**
   * @var RestAPI;
   */
  protected static $_instance;

  /**
   * @return RestAPI
   */
  public static function instance(): RestAPI {
    if( self::$_instance === null )
      self::$_instance = new self();

    return self::$_instance;
  }

  public function setup() {
    register_rest_route(
      PODCAST_IMPORTER_SECONDLINE_REST_API_PREFIX . '/v1',
      '/admin-dismiss-notice',
      [
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => 'PodcastImporterSecondLine\RestAPI\Response::admin_dismiss_notice',
        'permission_callback' => 'PodcastImporterSecondLine\RestAPI\ACL::admin_dismiss_notice',
      ]
    );

    register_rest_route(
      PODCAST_IMPORTER_SECONDLINE_REST_API_PREFIX . '/v1',
      '/get-feed-summary',
      [
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => 'PodcastImporterSecondLine\RestAPI\Response::get_feed_summary',
        'permission_callback' => 'PodcastImporterSecondLine\RestAPI\ACL::get_feed_summary',
      ]
    );

    register_rest_route(
      PODCAST_IMPORTER_SECONDLINE_REST_API_PREFIX . '/v1',
      '/save-feed',
      [
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => 'PodcastImporterSecondLine\RestAPI\Response::save_feed',
        'permission_callback' => 'PodcastImporterSecondLine\RestAPI\ACL::save_feed',
      ]
    );

    register_rest_route(
      PODCAST_IMPORTER_SECONDLINE_REST_API_PREFIX . '/v1',
      '/import-feed',
      [
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => 'PodcastImporterSecondLine\RestAPI\Response::import_feed',
        'permission_callback' => 'PodcastImporterSecondLine\RestAPI\ACL::import_feed',
      ]
    );

    register_rest_route(
      PODCAST_IMPORTER_SECONDLINE_REST_API_PREFIX . '/v1',
      '/sync-feed/(?P<id>\d+)',
      [
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => 'PodcastImporterSecondLine\RestAPI\Response::sync_feed',
        'permission_callback' => 'PodcastImporterSecondLine\RestAPI\ACL::sync_feed',
      ]
    );
  }

}