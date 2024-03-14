<?php

$cssCode = "
    .sbs-6310-template-".esc_attr($ids)." {
      float: left;
      width: 100%;
      height: 100%;
      box-sizing: border-box;
      position: relative;
      overflow: hidden;
      border-bottom-right-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."%;
      border: ".esc_attr($cssData['sbs_6310_box_border_width'])."px solid ".esc_attr($cssData['sbs_6310_box_border_color']).";      transition: .5s;
      background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
      box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
      z-index: 1;
      padding: 15px;
    }

    .sbs-6310-template-".esc_attr($ids).":hover {
      border-color: ".esc_attr($cssData['sbs_6310_box_border_hover_color']).";
      box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
      background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
    }

    .sbs-6310-template-".esc_attr($ids)."-icon img{
      width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      height: auto;
    }
    .sbs-6310-template-".esc_attr($ids)."-icon {
      display: inline-block;
      position: relative;
      font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0 auto;
      color: ".esc_attr($cssData['sbs_6310_icon_color']).";
      background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";;
      transition: all 500ms;
      margin-bottom: 20px;
      width: ".esc_attr($cssData['sbs_6310_icon_box_size_number'])."px;
      height: ".esc_attr($cssData['sbs_6310_icon_box_size_number'])."px;
      padding: 10px;
      transition: .5s;
      border: ".esc_attr($cssData['sbs_6310_icon_border_width'])."px solid ".esc_attr($cssData['sbs_6310_icon_border_color']).";
      box-sizing: border-box;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .sbs-6310-template-".esc_attr($ids)."-icon-wrapper{
      height: unset !important;
    }
    .sbs-6310-template-".esc_attr($ids).":hover .sbs-6310-template-".esc_attr($ids)."-icon::after {
      animation: zoom-in-zoom-out .5s ease-out;
      background: ".esc_attr($cssData['sbs_6310_icon_hover_effect_color']).";
      z-index: -1;
    }

    @keyframes zoom-in-zoom-out {
      0% {
        transform: scale(1, 1);
      }
      50% {
        transform: scale(1.2, 1.2);
      }
      100% {
        transform: scale(1, 1);
      }
    }

    .sbs-6310-template-".esc_attr($ids)."-icon-wrapper {
      float: left;
      width: 100%;
      text-align: center;
      padding: 10px;
      box-sizing: border-box;
    }

    .sbs-6310-template-".esc_attr($ids)."-icon::after {
      position: absolute;
      width: calc(".esc_attr($cssData['sbs_6310_icon_box_size_number'])."px + 20px);        height: calc(".esc_attr($cssData['sbs_6310_icon_box_size_number'])."px + 20px);

      border-radius: 50%;
      content: '';
      z-index: -1;
      width: calc(".esc_attr($cssData['sbs_6310_icon_box_size_number'])."px + 20px);
    }

    .sbs-6310-template-".esc_attr($ids).":hover .sbs-6310-template-".esc_attr($ids)."-icon i {
      transform: scale(1.1);
      animation: zoom-in-zoom-out .5s ease-out;
    }
    .sbs-6310-template-".esc_attr($ids).":hover .sbs-6310-template-".esc_attr($ids)."-icon  {
      color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
    }


    @media only screen and (max-width: 767px) {
      .sbs-6310-col {
        width: 100%;
        margin: 0 auto;
      }
      .sbs-6310-template-".esc_attr($ids)." {
        margin: 5px;
        width: calc(100% - 10px);
      }
      .sbs-6310-row {
        display: inline-block;
        width: 100%;
      }
    }
  ";

  wp_register_style( "sbs-6310-template-".esc_attr($ids)."-css", "" );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($ids)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($ids)."-css", $cssCode );
?>
