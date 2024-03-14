<?php
/**
 * @package Velocity Velocity_Meta
 * Get meta data for videos
 *
 * @since 1.2
 */
class Velocity_Metadata {
	
	
	public static function getYoutubeData($id){
		      
      $html = '';      
		$video_url = 'https://www.youtube.com/watch?v='. $id;
      $json = file_get_contents('https://www.youtube.com/oembed?format=json&url='. $video_url);
      
      if($json){
	      $obj = json_decode($json);
	      
	      // SEO
	      $html .= '<meta itemprop="contentLocation" content="'. $video_url .'" />';
	      $html .= '<meta itemprop="embedUrl" content="//youtube.com/embed/'. $id .'" />';
	      $html .= '<meta itemprop="thumbnail" content="'. $obj->thumbnail_url .'" />';
	      //$html .= '<meta itemprop="datePublished" content="'. $obj->upload_date .'" />';
	      //$html .= '<meta itemprop="duration" content="'. $obj->duration .'" />';
	      //print_r(alm_pretty_print($obj));
	
			return $html;
		}
      
   }
	
	
   
   public static function getVimeoData($id){
      
      $html = '';
      $json = file_get_contents('https://vimeo.com/api/v2/video/'. $id .'.json');
      
      if($json){
	      $obj = json_decode($json);
	      $obj = $obj[0];
	      
	      // SEO
	      $html .= '<meta itemprop="contentLocation" content="'. $obj->url .'" />';
	      $html .= '<meta itemprop="embedUrl" content="//player.vimeo.com/video/'. $id .'" />';
	      $html .= '<meta itemprop="thumbnail" content="'. $obj->thumbnail_large .'" />';
	      $html .= '<meta itemprop="datePublished" content="'. $obj->upload_date .'" />';
	      $html .= '<meta itemprop="duration" content="'. $obj->duration .'" />';
	      
	      return $html;
      }
      
   }
	
	
	public static function getTwitchData($id){
		      
      $html = '';      
		$video_url = 'https://www.twitch.tv/'. $id;
	      
      // SEO
      $html .= '<meta itemprop="contentLocation" content="'. $video_url .'" />';
      $html .= '<meta itemprop="embedUrl" content="//player.twitch.tv/?channel='. $id .'" />';

		return $html;
   }
   
   
}