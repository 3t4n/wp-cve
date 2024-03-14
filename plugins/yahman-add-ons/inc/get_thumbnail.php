<?php
defined( 'ABSPATH' ) || exit;
/**
 * Widget get thumbnail
 *
 * @package YAHMAN Add-ons
 */

function yahman_addons_get_thumbnail($post_id = false , $size = 'thumbnail' ) {

    /*
       * @param string $post_id Post ID.
       * @param string $size thumbnail, middle ,large ,full etc.
       * @param string $post_content Post_content.
      */
    $thumbnail = array();
    $thumbnail[1] = 640;
    $thumbnail[2] = 480;
    $thumbnail['has_image'] = false;



    if(has_post_thumbnail($post_id)) {

      $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($post_id) , $size );
      $thumbnail['has_image'] = true;

      return $thumbnail;

    }

    $post_content = get_post($post_id)->post_content;

    if(isset($post_content)){



      preg_match("/<img[^>]+src=[\"'](s?https?:\/\/[\-_\.!~\*'()a-z0-9;\/\?:@&=\+\$,%#]+\.(jpg|jpeg|png|gif))[\"'][^>]+>/i", $post_content , $thumurl);

      if(isset($thumurl[1])){
        $thumbnail[0] = $thumurl[1];
        $thumbnail['has_image'] = true;
        return $thumbnail;
      }



    }



    if ( has_header_image() ) {
        //$header_image = get_header_image_tag();

      $thumbnail[0] = get_header_image();

      return $thumbnail;

    }

    $option = get_option('yahman_addons') ;
    $ogp_logo = !empty($option['ogp']['image']) ? $option['ogp']['image'] : YAHMAN_ADDONS_URI . 'assets/images/ogp.jpg';

    $thumbnail[0] = $ogp_logo;

    return $thumbnail;


  }
