<?php

namespace PodcastImporterSecondLine\Helper;

use PodcastImporterSecondLine\Helper\Embed as PIS_Helper_Embed;
use PodcastImporterSecondLine\Helper\Importer\FeedItem as PIS_Helper_Importer_FeedItem;
use SimpleXMLElement;

class Importer {

  /**
   * @param array $meta_map
   * @return Importer
   */
  public static function from_meta_map( array $meta_map ): Importer {
    $settings = [];

    if( isset( $meta_map[ 'secondline_import_post_type' ] ) )
      $settings[ 'post_type' ] = $meta_map[ 'secondline_import_post_type' ];

    if( isset( $meta_map[ 'secondline_import_publish' ] ) )
      $settings[ 'post_status' ] = $meta_map[ 'secondline_import_publish' ];

    if( isset( $meta_map[ 'secondline_import_author' ] ) )
      $settings[ 'post_author' ] = $meta_map[ 'secondline_import_author' ];

    if( isset( $meta_map[ 'secondline_import_category' ] ) && is_array( $meta_map[ 'secondline_import_category' ] ) )
      $settings[ 'post_categories' ] = $meta_map[ 'secondline_import_category' ];

    if( isset( $meta_map[ 'secondline_import_images' ] ) )
      $settings[ 'import_images' ] = self::_meta_setting_to_bool( $meta_map[ 'secondline_import_images' ] );

    if( isset( $meta_map[ 'secondline_import_episode_number' ] ) )
      $settings[ 'import_episode_number' ] = self::_meta_setting_to_bool( $meta_map[ 'secondline_import_episode_number' ] );

    if( isset( $meta_map[ 'secondline_truncate_post' ] ) )
      $settings[ 'import_content_truncate' ] = ( $meta_map[ 'secondline_truncate_post' ] === '' ? false : intval( $meta_map[ 'secondline_truncate_post' ] ) );

    if( isset( $meta_map[ 'secondline_import_embed_player' ] ) )
      $settings[ 'import_embed_player' ] = self::_meta_setting_to_bool( $meta_map[ 'secondline_import_embed_player' ] );

    if( isset( $meta_map[ 'secondline_prepend_title' ] ) )
      $settings[ 'import_prepend_title' ] = $meta_map[ 'secondline_prepend_title' ];

    if( isset( $meta_map[ 'secondline_import_allow_sync' ] ) )
      $settings[ 'import_allow_sync' ] = self::_meta_setting_to_bool( $meta_map[ 'secondline_import_allow_sync' ] );

    if( isset( $meta_map[ 'secondline_import_date_from' ] ) )
      $settings[ 'import_date_from' ] = ( $meta_map[ 'secondline_import_date_from' ] !== '' ? $meta_map[ 'secondline_import_date_from' ] : false );

    if( isset( $meta_map[ 'secondline_content_tag' ] ) )
      $settings[ 'import_content_tag' ] = $meta_map[ 'secondline_content_tag' ];

    if( isset( $meta_map[ 'secondline_parent_show' ] ) )
      $settings[ 'import_parent_show' ] = $meta_map[ 'secondline_parent_show' ];

    if( isset( $meta_map[ 'secondline_import_continuous' ] ) )
      $settings[ 'import_continuous' ] = self::_meta_setting_to_bool( $meta_map[ 'secondline_import_continuous' ] );      

    $settings = apply_filters( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_importer_settings_from_meta_map', $settings, $meta_map );

    return new self( $meta_map[ 'secondline_rss_feed' ], $settings );
  }

  public static function _meta_setting_to_bool( $val ): bool {
    return ( $val === 'off' ? false : ( $val === 'on' ? true : boolval( $val ) ) );
  }

  /**
   * @var SimpleXMLElement
   */
  public $feedXML;

  public $feed_link = '';

  public $post_type       = 'post';
  public $post_status     = 'publish';
  public $post_author     = 'admin';
  public $post_categories = [];

  public $import_allow_sync = false;
  public $import_continuous = false;
  public $import_images = false;
  public $import_episode_number = false;
  public $import_content_truncate = false;
  public $import_prepend_title    = '';
  public $import_parent_show      = '';
  public $import_embed_player     = false;
  public $import_append_audio_html_to_content = false;
  public $import_date_from = false;
  public $import_content_tag = 'content:encoded';

  public $import_segmentation_limit = false;
  public $import_segmentation_offset = false;

  public $additional_settings = [];

  private $_current_feed_episode_count = 0;
  private $_current_feed_url_to_post_id_map = [];
  private $_current_imported_count = 0;
  private $_post_categories_import_map = [];

  public function __construct( $feed_link, $settings = array() ) {
    $this->import_append_audio_html_to_content = podcast_importer_secondline_append_player_to_content();

    $this->feed_link = esc_url( $feed_link, [ 'http', 'https' ] );

    foreach( $settings as $k => $v ) {
      if( !isset( $this->$k ) ) {
        $this->additional_settings[ $k ] = $v;
        continue;
      }

      $this->$k = $v;
    }

    if( $this->import_date_from !== false )
      $this->import_date_from = !is_numeric( $this->import_date_from ) ? strtotime( $this->import_date_from ) : intval( $this->import_date_from );

    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    if( !function_exists( 'post_exists' ) )
      require_once(ABSPATH . 'wp-admin/includes/post.php' );
  }

  private function _loadXML() {
    $this->feedXML = @simplexml_load_file($this->feed_link );

    if( empty( $this->feedXML ) ) {
      $ch = curl_init($this->feed_link);

      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

      $result = curl_exec($ch);

      if(substr($result, 0, 5) == "<?xml") {
        $this->feedXML = simplexml_load_string($result);
      }

      curl_close($ch);
    }

    if ($this->feedXML->channel->item) {
	    $this->_current_feed_episode_count = count( $this->feedXML->channel->item );
	  }

    do_action( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_importer_after_load_xml', $this );
  }

  public function get_current_feed_episode_count() {
    if( empty( $this->feed_link ) )
      return false;

    if( empty( $this->feedXML ) ) {
      $this->_loadXML();

      if( empty( $this->feedXML ) )
        return false;
    }

    return intval( $this->_current_feed_episode_count );
  }

  public function import_current_feed() {
    do_action( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_importer_before_import_current_feed', $this );

    if( empty( $this->feed_link ) )
      return false;

    if( empty( $this->feedXML ) ) {
      $this->_loadXML();

      if( empty( $this->feedXML ) )
        return false;
    }

    if( $this->_current_feed_episode_count === 0 )
      return false;

    if( empty( $this->_post_categories_import_map ) ) {
      foreach( $this->post_categories as $post_category ) {
        $term = get_term( $post_category );

        if( !isset( $term->taxonomy ) )
          continue;

        if( !isset( $this->_post_categories_import_map[ $term->taxonomy ] ) )
          $this->_post_categories_import_map[ $term->taxonomy ] = [];

        $this->_post_categories_import_map[ $term->taxonomy ][] = $post_category;
      }
    }

    $this->_current_imported_count = 0;

    set_time_limit(360);

    $synced_count  = 0;
    $skipped_count = 0;
    $additional_errors = [];

    $offset = ( empty( $this->import_segmentation_offset ) ? 0 : $this->import_segmentation_offset );
    $limit = (
      empty( $this->import_segmentation_limit )
        ? $this->_current_feed_episode_count
        : (
          $this->_current_feed_episode_count - $this->import_segmentation_offset < $this->import_segmentation_limit
            ? $this->_current_feed_episode_count - $this->import_segmentation_offset
            : $this->import_segmentation_limit
      )
    );

    for ( $i = $offset;
          $i < $offset + $limit;
          $i++
    ) {
      if( $this->import_date_from !== false ) {
        if( strtotime( (string) $this->feedXML->channel->item[ $i ]->pubDate ) < $this->import_date_from ) {
          $skipped_count++;
          continue;
        }
      }

      $feedItemInstance = new PIS_Helper_Importer_FeedItem( $this, $this->feedXML->channel->item[ $i ] );

      if( $feedItemInstance->current_post_id !== 0 ) {
        if( $this->import_allow_sync ) {
          $sync_response = $feedItemInstance->sync();

          if( is_wp_error( $sync_response ) ) {
            $additional_errors[] = $sync_response->get_error_message();

            unset( $feedItemInstance );

            continue;
          }

          $synced_count++;
        }

        $this->_current_feed_url_to_post_id_map[ $feedItemInstance->audio_feed_url ] = $feedItemInstance->current_post_id;

        unset( $feedItemInstance );

        continue;
      }

      $import_response = $feedItemInstance->import();

      if( is_wp_error( $import_response ) ) {
        $additional_errors[] = $import_response->get_error_message();

        unset( $feedItemInstance );

        continue;
      }

      $this->_current_feed_url_to_post_id_map[ $feedItemInstance->audio_feed_url ] = $feedItemInstance->current_post_id;
      $this->_current_imported_count++;

      unset( $feedItemInstance );
    }

    do_action( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_importer_after_import_current_feed', $this );

    return [
      'episode_count'     => $this->_current_feed_episode_count,
      'skipped_count'     => $skipped_count,
      'synced_count'      => $synced_count,
      'current_import'    => $this->_current_imported_count,
      'additional_errors' => $additional_errors
    ];
  }

  public function get_post_categories_import_map() :array {
    return $this->_post_categories_import_map;
  }

  public function get_feed_title() {
    if( empty( $this->feed_link ) )
      return false;

    if( empty( $this->feedXML ) ) {
      $this->_loadXML();

      if( empty( $this->feedXML ) )
        return false;
    }

    return (
      isset( $this->feedXML->channel ) && isset( $this->feedXML->channel->title )
        ? podcast_importer_secondline_sanitize_feed_value($this->feedXML->channel->title )
        : md5( $this->feed_link )
    );
  }

}