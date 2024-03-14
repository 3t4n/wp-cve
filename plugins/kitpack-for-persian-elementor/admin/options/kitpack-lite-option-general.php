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
  'title'  => 'کیت پک',
  'icon'   => 'far fa-smile-wink',
  'fields' => array(

    array(
        'type'    => 'content',
        'content' => '<p style="color:red;font-size:14px"> نسخه افزونه: '.  KITPACK_LITE_VERSION . '<p> برای عملکرد بهتر افزونه، همیشه آن را به آخرین نسخه موجود بروزرسانی کنید.</p>' ,
      ),

      array(
        'type'    => 'submessage',
        'style'   => 'info',
        'content' => 'به صورت پیش فرض افزونه کیت پک فونت بخش های مدیریت و المنتور را به "وزیر" تغییر می دهد، در این قسمت می توانید این مورد را غیر فعال کنید.',
      ),
      array(
        'id'    => 'admin-farsi-font',
        'type'  => 'switcher',
        'title' => 'اصلاح فونت مدیریت',
        'text_on'    => 'فعال',
        'text_off'   => 'غیرفعال',
        'subtitle'   => 'تغییر فونت پیشخوان وردپرس به فونت "وزیر" ',
        'default'    => true ,
        'text_width' => 70
      ),
      array(
        'type'    => 'notice',
        'style'   => 'success',
        'content' => 'در حال حاضر پیشخوان وردپرس با فونت پیشفرض بارگذاری می شود (پیشنهاد نشده)',
        'dependency' => array( 'admin-farsi-font', '==', 'false' )
      ),
      array(
        'id'    => 'elementor-farsi-font',
        'type'  => 'switcher',
        'title' => 'اصلاح فونت المنتور',
        'text_on'    => 'فعال',
        'text_off'   => 'غیرفعال',
        'subtitle'   => 'تغییر فونت افزونه المنتور به فونت "وزیر" جهت سازگاری بهتر با زبان فارسی ',
        'default'    => true ,
        'text_width' => 70
      ),
      array(
        'type'    => 'notice',
        'style'   => 'success',
        'content' => 'در حال حاضر المنتور با فونت پیشفرض بارگذاری می شود (پیشنهاد نشده)',
        'dependency' => array( 'elementor-farsi-font', '==', 'false' )
      ),


  )
) );