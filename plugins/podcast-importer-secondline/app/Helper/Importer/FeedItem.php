<?php

namespace PodcastImporterSecondLine\Helper\Importer;

use PodcastImporterSecondLine\Helper\Importer as PIS_Helper_Importer;
use PodcastImporterSecondLine\Helper\Embed as PIS_Helper_Embed;
use SimpleXMLElement;

class FeedItem {

  /**
   * @var PIS_Helper_Importer
   */
  public $importer;
  /**
   * @var SimpleXMLElement
   */
  public $feed_item;
  public $feed_item_itunes;
  private $_guid;

  public $episode_number;
  public $season_number;
  public $filesize;
  public $duration;

  public $current_post_id = 0;
  public $current_post_information = [
    'post_author'  => '',
    'post_content' => '',
    'post_date'    => '',
    'post_excerpt' => '',
    'post_status'  => '',
    'post_type'    => '',
    'post_title'   => '',
  ];

  public $audio_url;
  public $audio_feed_url  = '';
  public $audio_feed_host = '';
  public $audio_embed_html = ''; // Can be shortcode only.
  public $audio_is_whitelisted = false;

  /**
   * @param PIS_Helper_Importer $importer
   * @param $feed_item
   */
  public function __construct( PIS_Helper_Importer $importer, $feed_item ) {
    $this->importer = $importer;
    $this->feed_item = $feed_item;
    $this->feed_item_itunes = $this->feed_item->children( 'http://www.itunes.com/dtds/podcast-1.0.dtd' );
    $this->_guid   = podcast_importer_secondline_sanitize_feed_value($this->feed_item->guid );

    // Create the post
    global $wpdb;

    $this->current_post_id = intval($wpdb->get_var('SELECT post_id FROM ' . $wpdb->postmeta . ' WHERE ( meta_key = "secondline_imported_guid" AND meta_value LIKE "%' . esc_sql($wpdb->esc_like( $this->_guid )) . '%")' ) );

    $this->episode_number = podcast_importer_secondline_sanitize_feed_value($this->feed_item_itunes->episode );
    $this->season_number  = podcast_importer_secondline_sanitize_feed_value( $this->feed_item_itunes->season );
    $this->filesize       = '' . number_format($this->feed_item->enclosure['length'] / 1048576, 2) . 'M';
    $this->duration       = podcast_importer_secondline_sanitize_feed_value($this->feed_item_itunes->duration );

    if( !empty( $this->duration) ) {
      if( strpos($this->duration, ':' ) === false )
        $this->duration = gmdate("H:i:s", $this->duration );
    } else {
      $this->duration = '';
    }

    $this->current_post_information[ 'post_author' ] = $this->importer->post_author;
    $this->current_post_information[ 'post_date' ]   = date( 'Y-m-d H:i:s', ( strtotime( (string) $this->feed_item->pubDate ) < current_time('timestamp') ? strtotime( (string) $this->feed_item->pubDate ) : current_time('timestamp') ) );
    $this->current_post_information[ 'post_type' ]   = $this->importer->post_type;
    $this->current_post_information[ 'post_status' ] = $this->importer->post_status;
    $this->current_post_information[ 'post_title' ]  = podcast_importer_secondline_sanitize_feed_value($feed_item->title);

    if( $this->importer->import_episode_number && $this->episode_number !== '' )
      $this->current_post_information[ 'post_title' ] = $this->episode_number . ': ' . $this->current_post_information[ 'post_title' ];

    if( $this->importer->import_prepend_title !== '' )
      $this->current_post_information[ 'post_title' ] = str_replace(
        '[podcast_title]',
        podcast_importer_secondline_sanitize_feed_value($this->importer->feedXML->channel->title ),
          $this->importer->import_prepend_title
        ) . ' ' . $this->current_post_information[ 'post_title' ];


    if( $this->current_post_id === 0 )
      $this->current_post_id = \post_exists( $this->current_post_information[ 'post_title' ], "", "", $this->importer->post_type );

    $this->current_post_information[ 'post_excerpt' ] = podcast_importer_secondline_sanitize_feed_value($this->feed_item_itunes->subtitle );

    $this->_set_audio_information();
  }

  private function _set_audio_information() {
    $this->audio_url = (string) $this->feed_item->enclosure['url'];

    if( $this->importer->import_embed_player ) {
      if( strpos( $this->audio_url, 'dts.podtrac.com/redirect.mp3/' ) !== false )
        $this->audio_url = str_replace( 'dts.podtrac.com/redirect.mp3/', '', $this->audio_url );
    }

    $this->audio_url = preg_replace( '/(?s:.*)(https?:\/\/(?:[\w\-\.]+[^#?\s]+)(?:\.mp3))(?s:.*)/', '$1', $this->audio_url );
    $this->audio_url = preg_replace( '/(?s:.*)(https?:\/\/(?:[\w\-\.]+[^#?\s]+)(?:\.m4a))(?s:.*)/', '$1', $this->audio_url );

    $this->audio_feed_url = (string) $this->importer->feed_link;
    $this->item_link_url = (string) $this->feed_item->link;

    if( !empty( $this->audio_feed_url ) )
      $this->audio_feed_host = parse_url($this->audio_feed_url )['host'];
    else
      $this->audio_feed_host = parse_url($this->audio_url )['host'];

    if( $this->importer->import_embed_player && !empty( $this->audio_feed_host ) ) {
      if (preg_match('/fireside.fm/i', $this->importer->feed_link ) ) {
        $this->item_link_url = (string) $this->feed_item->children('fireside', true)->playerEmbedCode;
      } else if (preg_match('/omny.fm/i', $this->importer->feed_link ) || preg_match('/omnycontent.com/i', $this->importer->feed_link ) ) {
        $this->item_link_url = (string) $this->feed_item->children('media', true)->content->children('media', true)->player->attributes()->url;
      } else if (preg_match('/libsyn.com/i', $this->importer->feed_link ) || preg_match('/omnycontent.com/i', $this->importer->feed_link ) ) {
        $this->item_link_url = (string) $this->feed_item->children('libsyn', true)->itemId;
      } else if (preg_match('/sounder.fm/i', $this->audio_feed_url )) {
        $this->item_link_url = (string) $this->feed_item->enclosure['url'];
      }

      $this->audio_embed_html = PIS_Helper_Embed::get_embed_content( $this->audio_feed_host, $this->item_link_url, $this->audio_url, $this->importer->feed_link, $this->_guid );
      $this->audio_is_whitelisted = podcast_importer_is_whitelisted_host($this->audio_feed_host, $this->audio_feed_url);
    } else {
      $this->audio_embed_html = '[audio src="' . esc_url($this->audio_url ) . '"][/audio]';
    }

    $this->current_post_information[ 'post_content' ] = '';

    if( $this->importer->import_content_truncate === false || $this->importer->import_content_truncate >= 1 ) {
      if ( !empty( $this->feed_item->children( 'itunes', true )->summary) && $this->importer->import_content_tag === 'itunes:summary' ) {
        $this->current_post_information[ 'post_content' ] = wpautop(make_clickable(podcast_importer_secondline_sanitize_feed_value($this->feed_item->children('itunes', true )->summary)));
      } elseif( !empty( $this->feed_item->children('itunes', true)->encoded ) && $this->importer->import_content_tag === 'content:encoded') {
        $this->current_post_information[ 'post_content' ] = wpautop(make_clickable(podcast_importer_secondline_sanitize_feed_value($this->feed_item->children('content', true )->encoded)));
      } elseif( !empty( $this->feed_item->description ) && $this->importer->import_content_tag === 'description' ) {
        $this->current_post_information[ 'post_content' ] = wpautop(make_clickable(podcast_importer_secondline_sanitize_feed_value($this->feed_item->description)));
      } elseif( !empty( $this->feed_item->children( 'content', true )->encoded ) ) {
        $this->current_post_information[ 'post_content' ] = wpautop(make_clickable(podcast_importer_secondline_sanitize_feed_value($this->feed_item->children('content', true )->encoded)));
      } elseif( !empty( $this->feed_item->description ) ) {
        $this->current_post_information[ 'post_content' ] = wpautop(make_clickable(podcast_importer_secondline_sanitize_feed_value($this->feed_item->description)));
      } else {
        $this->current_post_information[ 'post_content' ] = wpautop(make_clickable(podcast_importer_secondline_sanitize_feed_value($this->feed_item_itunes->summary )));
      }

      if ( $this->importer->import_content_truncate !== false )
        $this->current_post_information[ 'post_content' ] = substr($this->current_post_information[ 'post_content' ], 0, $this->importer->import_content_truncate );
    }

    if( apply_filters( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_feed_item_has_player_in_content', podcast_importer_secondline_append_player_to_content(), $this ) )
      $this->current_post_information[ 'post_content' ] = $this->audio_embed_html . $this->current_post_information[ 'post_content' ];
  }

  public function import() {
    $this->current_post_id = wp_insert_post( $this->current_post_information );

    if ( is_wp_error( $this->current_post_id ) )
      return $this->current_post_id;

    $this->_set_post_information();

    return true;
  }

  public function sync() {
    if( $this->current_post_id === 0 )
      return false;

    $response = wp_update_post( [
      'ID' => $this->current_post_id
    ] + $this->current_post_information, true );

    if( is_wp_error( $response ) )
      return $response;

    $this->_set_post_information();

    return true;
  }

  private function _set_post_information() {
    update_post_meta( $this->current_post_id, 'secondline_imported_guid', $this->_guid );

    if ( podcast_importer_secondline_has_premium_theme() ) {
      if ( $this->episode_number !== '' )
        update_post_meta( $this->current_post_id, 'secondline_themes_episode_number', $this->episode_number );
      if ( $this->season_number !== '')
        update_post_meta( $this->current_post_id, 'secondline_themes_season_number', $this->season_number );
    }

    if ( $this->importer->import_embed_player && $this->audio_is_whitelisted && podcast_importer_secondline_has_premium_theme() ) {
      update_post_meta( $this->current_post_id, 'secondline_themes_external_embed', $this->audio_embed_html );
    } else {
      // Custom Field - Seriously Simple Podcasting
      if (function_exists('ssp_episodes')) {
        update_post_meta( $this->current_post_id, 'audio_file', $this->audio_url );
        update_post_meta( $this->current_post_id, 'duration', $this->duration );
        update_post_meta( $this->current_post_id, 'filesize', $this->filesize );
      }
      // Custom Field - PowerPress
      if ( function_exists('powerpress_get_enclosure_data')) {
        update_post_meta($this->current_post_id, 'enclosure', $this->audio_url );
      }
      // Custom Field - Simple Podcast Press
      if ( function_exists('spp_sl_sppress_plugin_updater') ) {
        update_post_meta( $this->current_post_id, '_audiourl', $this->audio_url );
      }
      // Custom Field - SecondLineThemes
      if ( function_exists('secondline_themes_theme_updater') && (!function_exists('powerpress_get_enclosure_data') && !function_exists('ssp_episodes') && !function_exists('spp_sl_sppress_plugin_updater' ) ) ) {
        update_post_meta( $this->current_post_id, 'secondline_themes_external_embed', $this->audio_embed_html );
      }
    }

    $categories_map = apply_filters( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_feed_item_import_category_map', $this->importer->get_post_categories_import_map(), $this );

    foreach( $categories_map as $taxonomy => $taxonomy_tags )
      wp_set_post_terms( $this->current_post_id, $taxonomy_tags, $taxonomy, false );

    if( !empty( $this->importer->import_parent_show ) )
      update_post_meta( $this->current_post_id, 'secondline_themes_parent_show_post', $this->importer->import_parent_show );

    if( apply_filters( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_feed_item_import_images', ( isset( $this->importer->import_images ) && $this->importer->import_images ), $this ) )
      $this->_handle_image_import();

    do_action( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_feed_item_imported', $this );

    // Old Actions, leaving for backward compatibility.
    do_action( 'secondline_after_post_import', $this->current_post_id, $this->importer->feed_link, $this->feed_item );

  }

  private function _handle_image_import() {
    $image_url = false;

    if( isset( $this->importer->import_images ) && $this->feed_item_itunes && isset( $this->feed_item_itunes->image ) && $this->feed_item_itunes->image->attributes() && isset( $this->feed_item_itunes->image->attributes()->href ) ) {
      $image_url = (string) $this->feed_item_itunes->image->attributes()->href;

    }

    if( $image_url === false )
      return;

    as_enqueue_async_action( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_scheduler_image_sync', [ $this->current_post_id, $image_url ], PODCAST_IMPORTER_SECONDLINE_ALIAS );
  }

}