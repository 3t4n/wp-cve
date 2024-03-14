<?php
/**
 * Class for handling embedded media in gallery
 */

class WDILibraryEmbed {
  public function __construct() {
  }


  /**
   * client side analogue is function wdi_spider_display_embed in wdi_embed.js
   *
   * @param embed_type: string , one of predefined accepted types
   * @param embed_id: string, id of media in corresponding host, or url if no unique id system is defined for host
   * @param attrs: associative array with html attributes and values format e.g. array('width'=>"100px", 'style'=>"display:inline;")
   */
  public static function display_embed( $embed_type, $embed_id = '', $attrs = array(), $carousel_media = NULL, $url = '' ) {
    $html_to_insert = '';
    switch ( $embed_type ) {
      case 'EMBED_OEMBED_INSTAGRAM_VIDEO':
        $oembed_instagram_html = '<div ';
        foreach ( $attrs as $attr => $value ) {
          if ( preg_match('/src/i', $attr) === 0 ) {
            if ( $attr != '' && $value != '' ) {
              $oembed_instagram_html .= ' ' . esc_attr($attr) . '="' . esc_attr($value) . '"';
            }
          }
        }
        $oembed_instagram_html .= " >";
        if ( $url != '' ) {
          $oembed_instagram_html .= '<video onclick="wdi_play_pause(jQuery(this));" style="width:auto !important; height:auto !important; max-width:100% !important; max-height:100% !important; margin:0 !important;" controls>'.
            '<source src="'. esc_url($url) .
            '" type="video/mp4"> Your browser does not support the video tag. </video>';

        }
        $oembed_instagram_html .= "</div>";
        $html_to_insert .= $oembed_instagram_html;
        break;
      case 'EMBED_OEMBED_INSTAGRAM_IMAGE':
        $oembed_instagram_html = '<div ';
        foreach ( $attrs as $attr => $value ) {
          if ( preg_match('/src/i', $attr) === 0 ) {
            if ( $attr != '' && $value != '' ) {
              $oembed_instagram_html .= ' ' . esc_attr($attr) . '="' . esc_attr($value) . '"';
            }
          }
        }
        $oembed_instagram_html .= " >";
        if ( $url != '' ) {
          $oembed_instagram_html .= '<img src="' . esc_url($url) . '"' . ' style="' . 'max-width:' . '100%' . " !important" . '; max-height:' . '100%' . " !important" . '; width:' . 'auto !important' . '; height:' . 'auto !important' . ';">';
        }
        $oembed_instagram_html .= "</div>";
        $html_to_insert .= $oembed_instagram_html;
        break;
      case 'EMBED_OEMBED_INSTAGRAM_CAROUSEL':
        $oembed_instagram_html = '<div ';
        foreach ( $attrs as $attr => $value ) {
          if ( preg_match('/src/i', $attr) === 0 ) {
            if ( $attr != '' && $value != '' ) {
              $oembed_instagram_html .= ' ' . esc_attr($attr) . '="' . esc_attr($value) . '"';
            }
          }
        }
        $oembed_instagram_html .= " >";
        foreach ( $carousel_media as $key => $media ) {
          if ( $media["type"] == "image" ) {
            $oembed_instagram_html .= '<img src="' . esc_url($media["images"]["standard_resolution"]["url"]) . '"' . ' style="' . 'max-width:' . '100%' . " !important" . '; max-height:' . '100%' . " !important" . '; width:' . 'auto !important' . '; height:' . 'auto !important' . ';" data-id="' . esc_attr($key) . '" class="carousel_media ' . ($key == 0 ? "active" : "") . '">';
          }
          elseif ( $media["type"] == "video" ) {
            $oembed_instagram_html .= '<video onclick="wdi_play_pause(jQuery(this));" style="width:auto !important; height:auto !important; max-width:100% !important; max-height:100% !important; margin:0 !important;" controls data-id="' . esc_attr($key) . '" class="carousel_media ' . ($key == 0 ? "active" : "") . '">' . '<source src="' . esc_url($media["videos"]["standard_resolution"]["url"]) . '" type="video/mp4"> Your browser does not support the video tag. </video>';
          }
        }
        $oembed_instagram_html .= "</div>";
        $html_to_insert .= $oembed_instagram_html;
        break;
      default:
        // code...
        break;
    }
    echo wp_kses($html_to_insert, array(
      "div" => array("class" => true, "style" => true, "frameborder" => true, "allowfullscreen" => true),
      "img" => array("class" => true, "style" => true, "src" => true, "data-id" => true),
      "video" => array("class" => true, "style" => true, "onclick" => true, "controls" => true, "data-id" => true),
      "source" => array("src" => true, "type" => true),
    ));
  }
}