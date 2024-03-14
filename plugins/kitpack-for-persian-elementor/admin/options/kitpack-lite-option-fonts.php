<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       elementorplus.net
 * @since      1.0.0
 * 
 */

if (!defined('ABSPATH')) {die;}

KPE::createSection( $prefix, array(
  'title'  => 'فونت های فارسی',
  'icon'   => 'fas fa-language',
  'fields' => array(

    array(
      'type'    => 'notice',
      'style'   => 'warning',
      'content' => 'در این قسمت می توانید فونت های فارسی اضافه شده به افزونه المنتور را فعال یا غیر فعال نمایید.</a>',
       ), 
    array(
        'id'    => 'anjoman-font',
        'type'  => 'switcher',
        'title' => 'فونت انجمن',
        'text_on'    => 'فعال',
        'text_off'   => 'غیرفعال',
        'subtitle'   => 'فعال/غیر فعال کردن فونت فارسی انجمن',
        'default'    => true ,
        'text_width' => 70
    ),
    array(
      'id'    => 'vazir-font',
      'type'  => 'switcher',
      'title' => 'فونت وزیر',
      'text_on'    => 'فعال',
      'text_off'   => 'غیرفعال',
      'subtitle'   => 'فعال/غیر فعال کردن فونت فارسی وزیر',
      'default'    => true ,
      'text_width' => 70
    ),
    array(
      'id'    => 'samim-font',
      'type'  => 'switcher',
      'title' => 'فونت صمیم',
      'text_on'    => 'فعال',
      'text_off'   => 'غیرفعال',
      'subtitle'   => 'فعال/غیر فعال کردن فونت فارسی صمیم',
      'default'    => true ,
      'text_width' => 70
    ),
    array(
      'id'    => 'shabnam-font',
      'type'  => 'switcher',
      'title' => 'فونت شبنم',
      'text_on'    => 'فعال',
      'text_off'   => 'غیرفعال',
      'subtitle'   => 'فعال/غیر فعال کردن فونت فارسی شبنم',
      'default'    => true ,
      'text_width' => 70
    ),
    array(
      'id'    => 'mikhak-font',
      'type'  => 'switcher',
      'title' => 'فونت میخک',
      'text_on'    => 'فعال',
      'text_off'   => 'غیرفعال',
      'subtitle'   => 'فعال/غیر فعال کردن فونت فارسی میخک',
      'default'    => true ,
      'text_width' => 70
    ),
    array(
      'id'    => 'kara-font',
      'type'  => 'switcher',
      'title' => 'فونت کارا',
      'text_on'    => 'فعال',
      'text_off'   => 'غیرفعال',
      'subtitle'   => 'فعال/غیر فعال کردن فونت فارسی کارا',
      'default'    => true ,
      'text_width' => 70
    ),


  )
) );