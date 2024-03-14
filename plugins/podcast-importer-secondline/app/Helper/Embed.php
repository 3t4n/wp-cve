<?php

namespace PodcastImporterSecondLine\Helper;

class Embed {

  /**
   * @param $feed_host_url
   * @param $embed_url
   * @param $audio_url
   * @param $plugin_feed_url
   * @param $guid
   * @return false|string
   */
  public static function get_embed_content( $feed_host_url, $embed_url, $audio_url, $plugin_feed_url, $guid ) {
    if (strpos($feed_host_url, 'transistor.fm') !== false) {
      if (strpos($embed_url, 'share.transistor.fm') !== false) {
        $fixed_share_url = str_replace('/s/', '/e/', $embed_url);
      } else {
        $fixed_share_url =  'https://share.transistor.fm/e/' . explode('/', $audio_url)[3];
      }
      $response = '<iframe src="' . esc_url($fixed_share_url) . '" width="100%" height="180" frameborder="0" scrolling="no" seamless="true" style="width:100%; height:180px;"></iframe>';

    } elseif (strpos($feed_host_url, 'anchor.fm') !== false) {

      $fixed_share_url = str_replace('/episodes/', '/embed/episodes/', $embed_url);
      $response = '<iframe src="' . esc_url($fixed_share_url) . '" height="180px" width="100%" frameborder="0" scrolling="no" style="width:100%; height:180px;"></iframe>';

    } elseif (strpos($plugin_feed_url, 'simplecast.com') !== false) {
      $extract_sc_url = explode('/audio/', $audio_url);
      $fixed_share_url = explode('/', $extract_sc_url[1]);
      $response = '<iframe src="https://player.simplecast.com/' . $fixed_share_url[2] . '" height="200px" width="100%" frameborder="no" scrolling="no" style="width:100%; height:200px;"></iframe>';


    } elseif (strpos($feed_host_url, 'podcastpage.io') !== false) {

      $fixed_share_url = str_replace('/episode/', '/?pp_mode=preview/', $embed_url);
      $response = '<iframe src="' . esc_url($fixed_share_url) . '" height="200px" width="100%" frameborder="0" scrolling="no" style="width:100%; height:200px;"></iframe>';

    } elseif (strpos($feed_host_url, 'whooshkaa.com') !== false) {

      $whooshkaa_audio_id = substr($embed_url, strpos($embed_url, "?id=") + 4);
      $fixed_share_url = 'https://webplayer.whooshkaa.com/player/episode/id/' . $whooshkaa_audio_id . '?theme=light';
      $response = '<iframe src="' . esc_url($fixed_share_url) . '" width="100%" height="200" frameborder="0" scrolling="no" style="width: 100%; height: 200px"></iframe>';

    } elseif ((strpos($plugin_feed_url, 'omny.fm') !== false) || (strpos($plugin_feed_url, 'omnycontent.com') !== false)) {

      $response = '<iframe src="' . esc_url($embed_url) . '" width="100%" height="180px" scrolling="no"  frameborder="0" style="width:100%; height:180px;"></iframe>';

    } elseif (strpos($feed_host_url, 'podbean.com') !== false) {

      $response = wp_oembed_get(esc_url($embed_url)); // oEmbed

    } elseif (strpos($plugin_feed_url, 'megaphone.fm') !== false) {
     
      $megaphone_audio_link = explode('megaphone.fm/', $audio_url);
      $megaphone_audio_id = explode('.', $megaphone_audio_link[1]);
      $fixed_share_url = 'https://playlist.megaphone.fm/?e=' . $megaphone_audio_id[0];
      $response = '<iframe src="' . esc_url($fixed_share_url) . '" width="100%" height="210" scrolling="no"  frameborder="0" style="width: 100%; height: 210px"></iframe>';

    } elseif (strpos($plugin_feed_url, 'captivate.fm') !== false) {
      
      $captivate_audio_link = explode('media/', $audio_url);
      $captivate_audio_id = explode('/', $captivate_audio_link[1]);
      $fixed_share_url = 'https://player.captivate.fm/episode/' . $captivate_audio_id[0];
      $response = '<iframe src="' . esc_url($fixed_share_url) . '" width="100%" height="170" scrolling="no"  frameborder="0" style="width: 100%; height: 170px"></iframe>';

    } elseif (strpos($audio_url, 'buzzsprout.com') !== false) {
      
      $buzzsprout_audio_url = explode('.mp3', $audio_url);
      $fixed_share_url = $buzzsprout_audio_url[0] . '?iframe=true';
      $response = '<iframe src="' . esc_url($fixed_share_url) . '" scrolling="no" width="100%" scrolling="no"  height="200" frameborder="0" style="width: 100%; height: 200px"></iframe>';

    } elseif (strpos($audio_url, 'pinecast.com') !== false) {
      
      $pinecast_audio_url = explode('.mp3', $audio_url);
      $pinecast_episode_url = str_replace('/listen/', '/player/', $pinecast_audio_url[0]);
      $fixed_share_url = $pinecast_episode_url . '?theme=flat';
      $response = '<iframe src="' . esc_url($fixed_share_url) . '" scrolling="no" width="100%" scrolling="no"  height="200" frameborder="0" style="width: 100%; height: 200px"></iframe>';

    } elseif (strpos($plugin_feed_url, 'feed.ausha.co') !== false) {
      
      $ausha_audio_link = explode('audio.ausha.co/', $audio_url);
      $ausha_audio_id = explode('.mp3', $ausha_audio_link[1]);
      $podcastId = $ausha_audio_id[0];
      $response = '<iframe frameborder="0" height="200px" scrolling="no"  width="100%" src="https://widget.ausha.co/index.html?podcastId=' . $podcastId . '&amp;display=horizontal&amp;v=2"></iframe>';

    } elseif (strpos($plugin_feed_url, 'sounder.fm') !== false) {
    
      $sounder_audio_link = explode('audio--', $embed_url);
      $sounder_audio_id = explode('--', $sounder_audio_link[1]);
      $podcastId = $sounder_audio_id[0];
      $response = '<iframe frameborder="0" height="200px" scrolling="no"  width="100%" src="https://embed.sounder.fm/play/' . $podcastId . '"></iframe>';

    } elseif (strpos($plugin_feed_url, 'spreaker.com') !== false) {
      
      $fixed_share_url = explode('/episode/', $guid);
      if(isset($fixed_share_url[1])) {
        $response = '<iframe frameborder="0" height="200" scrolling="no" width="100%" src="https://widget.spreaker.com/player?episode_id=' . $fixed_share_url[1] . '"></iframe>';
      } else {
        $response = '[audio src="' . esc_url($audio_url) . '"][/audio]';
      }
      
    } elseif (strpos($plugin_feed_url, 'fireside.fm') !== false) {
      
      $response = $embed_url . '</iframe>';
      
    } elseif (strpos($plugin_feed_url, 'libsyn.com') !== false) {
      
      $response = '<iframe frameborder="0" height="128" scrolling="no" width="100%" src="https://play.libsyn.com/embed/episode/id/' . $embed_url . '" ></iframe>';
    
    } elseif (strpos($plugin_feed_url, 'audioboom.com') !== false) {
      
      $fixed_share_url = str_replace('/posts/', '/boos/', $embed_url);
      $response = '<iframe frameborder="0" height="220" scrolling="no" width="100%" src="' . $fixed_share_url . '/embed/v4"></iframe>';
    
    } else {
    
      $response = '[audio src="' . esc_url($audio_url) . '"][/audio]';
    
    }

    return $response;
  }

}