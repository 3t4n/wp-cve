<?php

namespace PodcastImporterSecondLine;

use PodcastImporterSecondLine\Settings;

class Hooks {

  /**
   * @var Hooks;
   */
  protected static $_instance;

  /**
   * @return Hooks
   */
  public static function instance(): Hooks {
    if( self::$_instance === null )
      self::$_instance = new self();

    return self::$_instance;
  }

  public function setup() {
    add_filter( 'wp_kses_allowed_html', [ $this, '_wp_kses_allowed_html' ], 10, 2 );
    add_filter( 'oembed_providers', [ $this, '_oembed_providers' ] );
    add_action( 'admin_notices', [ $this, '_admin_notice' ] );
  }

  public function _wp_kses_allowed_html( $tags, $context ) {
    if( !in_array( $context, podcast_importer_secondline_supported_post_types() ) )
      return $tags;

    $tags['iframe'] = array(
      'src'             => true,
      'height'          => true,
      'width'           => true,
      'style'			  		=> true,
      'frameborder'     => true,
      'allowfullscreen' => true,
      'scrolling'		  	=> true,
      'seamless'		  	=> true,
    );

    return $tags;
  }

  public function _oembed_providers( $providers ) {
    $providers['#https?://(.+).podbean.com/e/.+#i'] = [ 'https://api.podbean.com/v1/oembed', true ];

    return $providers;
  }

  public function _admin_notice() {
    if( !current_user_can( PODCAST_IMPORTER_SECONDLINE_SETTINGS_PERMISSION_CAP ) )
      return;

    if( podcast_importer_secondline_has_premium_theme() || ( defined( 'PODCAST_IMPORTER_PRO_SECONDLINE' ) ) )
      return;

    if( isset( Settings::instance()->get( '_admin_notice_dismissed_map', [] )[ get_current_user_id() ] ) )
      return;

    echo '<div id="podtcast-importer-secondline-dismissible" class="notice notice-info is-dismissible">';
    echo    '<p>' . esc_html__( 'Power up your Podcast Website with', 'podcast-importer-secondline' );
    echo      ' <a href="https://secondlinethemes.com/themes/?utm_source=import-plugin-notice" target="_blank">' . esc_html__( 'SecondLineThemes.', 'podcast-importer-secondline' ) . '</a> ';
    echo       esc_html__( 'Brought to you by the creators of the Podcast Importer plugin!', 'podcast-importer-secondline' );
    echo    '</p>';
    echo '</div>';
  }

}