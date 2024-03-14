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
  'title'  => 'فارسی ساز المنتور',
  'icon'   => 'fas fa-language',
  'fields' => array(

    array(
      'type'    => 'content',
      'content' => 'ما سعی کرده ایم ترجمه های تخصصی برای افزونه های المنتور و المنتور پرو آماده کنیم، به صورت پیش فرض این ترجمه ها اعمال می شوند اما می توانید از طریق گزینه های زیر این موارد را غیر فعال نمایید.' ,
    ),
    array(
      'id'    => 'elementor-translate-farsi',
      'type'  => 'switcher',
      'title' => 'فارسی ساز افزونه المنتور',
      'text_on'    => 'فعال',
      'text_off'   => 'غیرفعال',
      'subtitle'   => 'فعال/غیر فعال کردن ترجمه فارسی افزونه المنتور',
      'default'    => true ,
      'text_width' => 70
    ),
    array(
      'id'    => 'elementor-pro-translate-farsi',
      'type'  => 'switcher',
      'title' => 'فارسی ساز افزونه المنتور پرو',
      'text_on'    => 'فعال',
      'text_off'   => 'غیرفعال',
      'subtitle'   => 'فعال/غیر فعال کردن ترجمه فارسی افزونه المنتور پرو',
      'default'    => true ,
      'text_width' => 70
    ),

  )
) );

KPE::createSection( $prefix, array(
  'title'  => 'فارسی سازی های دیگر',
  'icon'   => 'fas fa-language',
  'fields' => array(

    array(
      'type'    => 'content',
      'content' => 'ما سعی کرده ایم ترجمه های تخصصی برای برخی افزودنی های المنتور آماده کنیم، به صورت پیش فرض این ترجمه ها اعمال می شوند اما می توانید از طریق گزینه های زیر این موارد را غیر فعال نمایید.' ,
    ),  
    array(
      'id'    => 'elementskit-lite-translate-farsi',
      'type'  => 'switcher',
      'title' => 'فارسی ساز افزونه المنت کیت لایت',
      'text_on'    => 'فعال',
      'text_off'   => 'غیرفعال',
      'subtitle'   => 'فعال/غیر فعال کردن ترجمه فارسی افزونه المنت کیت لایت',
      'default'    => false ,
      'text_width' => 70
    ), 

  )
) );