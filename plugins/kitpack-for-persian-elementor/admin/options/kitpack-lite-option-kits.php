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
  'title'  => 'کیت های آماده المنتور',
  'icon'   => 'fas fa-columns',
  'fields' => array(
    array(
      'id'    => 'elementor-ready-kits',
      'type'  => 'switcher',
      'title' => 'کیت های آماده و فارسی',
      'text_on'    => 'فعال',
      'text_off'   => 'غیرفعال',
      'subtitle'   => 'فعال/غیر فعال کردن کیت های آماده و فارسی ',
      'default'    => true ,
      'text_width' => 70
    ),
    array(
      'type'    => 'notice',
      'style'   => 'success',
      'content' => 'در حال حاضر کیت های آماده و فارسی بارگذاری نمی شود.',
      'dependency' => array( 'elementor-ready-kits', '==', 'false' )
    ),

  )
) );