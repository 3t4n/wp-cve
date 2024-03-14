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
  'title'  => 'آیکن های حرفه ای',
  'icon'   => 'far fa-gem',
  'fields' => array(
    array(
      'type'    => 'content',
      'content' => 'در این قسمت می توانید آیکن های حرفه ای اضافه شده به افزونه المنتور را فعال یا غیر فعال نمایید.' ,
    ),  
    array(
      'type'    => 'notice',
      'style'   => 'warning',
      'content' => 'برای مشاهده لیست آیکن ها می توانید به وب سایت کیت پک مراجعه کنید. <a href="https://kitpack.ir/icons" target="_blank">لیست آیکن ها</a>',
       ),   
    array(
      'id'    => 'elementor-icon-iran',
      'type'  => 'switcher',
      'title' => 'آیکن پک ایرانی',
      'text_on'    => 'فعال',
      'text_off'   => 'غیرفعال',
      'subtitle'   => 'فعال/غیر فعال کردن آیکن پک ایرانی',
      'default'    => true ,
      'text_width' => 70
    ),

  )
) );