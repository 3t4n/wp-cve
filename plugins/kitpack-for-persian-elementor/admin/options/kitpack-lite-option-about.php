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
  'title'  => 'درباره کیت پک',
  'icon'   => 'fas fa-award',
  'fields' => array(

    array(
      'type'    => 'content',
      'content' => '
      <h2>افزونه کیت پک المنتور</h2>
      <p> افزونه کیت پک المنتور با هدف آماده کردن تمپلیت های آماده برای سایت ساز المنتور پیاده سازی شده است. <br />
      علاوه بر این سعی کردیم چندین امکان را به صورت یکجا در این افزونه قرار دهیم تا کاربران با نصب یک افزونه اکثر نیاز هایشان در رابطه با استفاده از المنتور برطرف شود.<br />
      همچنین در این افزونه سعی کرده ایم به شما کاربران عزیز حق انتخاب دهیم و بتوانید امکانات را بر اساس نیاز خود فعال و غیر فعال نمایید.<br /><br />
      <strong> علی رحمانی <br />
       <a href="https://kitpack.ir" target="_blank">کیت پک برای المنتور</a>
      </p>
      ',
       ),
       array(
        'type'    => 'content',
        'content' => '<p>برای دریافت پشتیبانی می توانید به <a href="https://kitpack.ir">kitpack.ir</a> مراجعه کنید </p>
        <p>
        <img style="margin: 0 0 -7px 3px;" src='. KITPACK_URL . 'admin/img/logo-kp.png /><strong>کیت پک برای المنتور</strong>
        </p>
        ' ,
      ), 

  )
) );