<?php
namespace OACS\SolidPostLikes\Controllers;

if ( ! defined( 'WPINC' ) ) { die; }

/** Creates the like and unlike icon for output. */

class SolidPostLikesIcon
{

  public function oacs_spl_get_liked_icon()
  {
    $oacs_like_icon_color_settings        = carbon_get_theme_option('oacs_spl_like_icon_color') ?? '';
    $oacs_like_icon_setting               = carbon_get_theme_option('oacs_spl_like_icon')['class'] ?? '';
    $oacs_spl_like_icon_style_setting     = carbon_get_theme_option('oacs_spl_like_icon_style') ?? '';
    $oacs_spl_like_icon_size_setting      = carbon_get_theme_option('oacs_spl_like_icon_size') ?? '';
    $oacs_spl_like_icon_padding_setting   = carbon_get_theme_option('oacs_spl_like_icon_padding') ?? '';


    if (!empty($oacs_like_icon_color_settings) OR !empty($oacs_spl_like_icon_size_setting) OR !empty($oacs_spl_like_icon_padding_setting)) { $oacs_spl_style_tag = '" style="'; } else { $oacs_spl_style_tag = '' ;}

    /** NOTE the space here after the icon is important. Leave it. */
    $icon = '
    <i class="oacs-spl-icon ' .
    (!empty($oacs_like_icon_setting) ? ($oacs_like_icon_setting) : '') .
    $oacs_spl_style_tag .
    (!empty($oacs_like_icon_color_settings) ? 'color: ' . esc_attr($oacs_like_icon_color_settings) . '; ': '') .
    (!empty($oacs_spl_like_icon_size_setting) ? 'font-size: ' . esc_attr($oacs_spl_like_icon_size_setting) . '; ': '') .
    (!empty($oacs_spl_like_icon_padding_setting) ? 'padding: ' . esc_attr($oacs_spl_like_icon_padding_setting)  . ';': '') .
    '"></i>';

    return apply_filters( 'oacs_spl_liked_icon', $icon);
  }


  public function oacs_spl_get_unliked_icon()
  {
    $oacs_unlike_icon_color_settings        = carbon_get_theme_option('oacs_spl_unlike_icon_color') ?? '';
    $oacs_unlike_icon_setting               = carbon_get_theme_option('oacs_spl_unlike_icon')['class'] ?? '';
    $oacs_spl_unlike_icon_style_setting     = carbon_get_theme_option('oacs_spl_unlike_icon_style') ?? '';
    $oacs_spl_unlike_icon_size_setting      = carbon_get_theme_option('oacs_spl_unlike_icon_size') ?? '';
    $oacs_spl_unlike_icon_padding_setting   = carbon_get_theme_option('oacs_spl_unlike_icon_padding') ?? '';

    if (!empty($oacs_unlike_icon_color_settings) OR !empty($oacs_spl_unlike_icon_size_setting) OR !empty($oacs_spl_unlike_icon_padding_setting)) { $oacs_spl_style_tag = '" style="'; } else { $oacs_spl_style_tag = '' ;}

    /** NOTE the space here after the icon is important. Leave it. */
    $icon = '
    <i class="oacs-spl-icon ' .
    (!empty($oacs_unlike_icon_setting) ? ($oacs_unlike_icon_setting) : '') .
    $oacs_spl_style_tag .
    (!empty($oacs_unlike_icon_color_settings) ? 'color: ' . esc_attr($oacs_unlike_icon_color_settings) . '; ' : '') .
    (!empty($oacs_spl_unlike_icon_size_setting) ? 'font-size: ' . esc_attr($oacs_spl_unlike_icon_size_setting) . '; ' : '') .
    (!empty($oacs_spl_unlike_icon_padding_setting) ? 'padding: ' . esc_attr( $oacs_spl_unlike_icon_padding_setting) . '; ' : '') .
    '"></i>';

    return apply_filters( 'oacs_spl_unliked_icon', $icon);
  }

}