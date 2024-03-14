<?php
defined( 'ABSPATH' ) || exit;
/**
 * Social share
 *
 * @package YAHMAN Add-ons
 */

function yahman_addons_social_share(){

  if(is_front_page())return;

  $defaults = array(
    'post'    => false,
    'page' => false,
    'title' => esc_html__('Share this', 'yahman-add-ons'),
    'icon_shape' => 'icon_square',
    'icon_align'   => 'circle',
    'icon_shape' => 'icon_square',
    'icon_size' => 'icon_medium',
    'icon_align'    => 'left',
    'icon_user_color' => '',
    'icon_user_hover_color' => '',
    'icon_tooltip' => false,
  );

  $option = get_option('yahman_addons');

  $share = wp_parse_args( $option['share'], $defaults );

  $sns_info['icon_shape'] = $share['icon_shape'];
  $sns_info['icon_size'] = $share['icon_size'];

  $sns_info['icon_user_color']  = $share['icon_user_color'];
  $sns_info['icon_user_hover_color']  = $share['icon_user_hover_color'];

  $sns_info['icon_tooltip']  = !empty($share['icon_tooltip']) ? ' sns_tooltip' : '';

  $sns_info['icon_align']  = $share['icon_align'];

  $title = apply_filters( 'yahman_addons_share_title', $share['title'] );

  $sns_info['no_image'] = !empty($option['other']['no_image']) ? $option['other']['no_image'] : YAHMAN_ADDONS_URI . 'assets/images/no_image.png';
  $sns_info['facebook_app_id'] = isset($option['sns_account']['facebook_app_id']) ? $option['sns_account']['facebook_app_id'] : '';


  switch ($sns_info['icon_align']){
    case 'left':
    $sns_info['icon_align'] = ' jc_fs';
    break;

    case 'right':
    $sns_info['icon_align'] = ' jc_fe';
    break;

    case 'center':
    $sns_info['icon_align'] = ' jc_c';
    break;

    case 'space_between':
    $sns_info['icon_align'] = ' jc_sb';
    break;

    case 'space_around':
    $sns_info['icon_align'] = ' jc_sa';
    break;

    default:
  }

  $i = 1;
  while($i <= 10){
    $sns_info['account'][$i] = '';
    $sns_info['icon'][$i] = $sns_info['share'][$i] = ! empty( $option['share']['icon_'.$i] ) ? $option['share']['icon_'.$i] : 'none';
    $sns_info['url'][$i] = get_the_permalink();
    ++$i;
  }
  $sns_info['loop'] = 10;
  $sns_info['class'] = ' sns_share_icon'.$sns_info['icon_align'];

  $sns_info['widget_id'] = 'social_share';

  ob_start();
  ?>

  <div id="social_share" class="social_share_list post_item mb_L">
    <div class="item_title fw8 mb_S"><?php echo esc_html( $title ); ?></div>



    <?php


    require_once YAHMAN_ADDONS_DIR . 'inc/widget/social-output.php';
    yahman_addons_social_output($sns_info);
    set_query_var( 'yahman_addons_social_share', true );

    ?>

  </div>
  <?php

  return ob_get_clean();
}

