<?php

$cssCode = "
    .sbs-6310-template-".esc_attr($ids)." {
      float: left;
      width: 100%;
      height: 100%;
      position: relative;
      overflow: hidden;
      border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
      background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
      box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
      border: ".esc_attr($cssData['sbs_6310_box_border_size'])."px solid ".esc_attr($cssData['sbs_6310_box_border_color']).";
      background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
      z-index: 1;
    }

    .sbs-6310-template-".esc_attr($ids)."::before {
      position: absolute;
      content: '';
      width: 0;
      height: 0;
      background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
      top: 50%;
      left: 50%;
      transition: .5s;
      z-index: -1;
    }
    .sbs-6310-template-".esc_attr($ids).":hover::before {
      width: 100% !important;
      height: 100% !important;
      top: 0 !important;
      left: 0 !important;
      transform: rotate(360deg) !important;
    }
    .sbs-6310-template-".esc_attr($ids).":hover{
      background: transparent !important;
      border-color: ".esc_attr($cssData['sbs_6310_box_border_hover_color']).";
      box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
    }
    .sbs-6310-template-".esc_attr($ids)."-icon{
      width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    }  
    .sbs-6310-template-".esc_attr($ids)."-icon img{
      width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;;
    }
    .sbs-6310-template-".esc_attr($ids)."-icon-wrapper {
      float: left;
      width: 100%;
      display: flex;
      justify-content: center;
      height: unset !important;
      align-items: center;
    }
    .sbs-6310-template-".esc_attr($ids)."-icon {
      width: ".esc_attr($cssData['sbs_6310_icon_box_size_number'])."px;
      height: ".esc_attr($cssData['sbs_6310_icon_box_size_number'])."px;
        line-height: ".esc_attr($cssData['sbs_6310_icon_box_size_number'])."px;

      text-align: center;
      background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";;
      color: ".esc_attr($cssData['sbs_6310_icon_color']).";
      font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .sbs-6310-template-".esc_attr($ids).":hover .sbs-6310-template-".esc_attr($ids)."-icon {
      animation: zoom-anim .5s ease-out;
      background: ".esc_attr($cssData['sbs_6310_icon_background_hover_color']).";
      color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
    }
    .sbs-6310-template-".esc_attr($ids)."-title{
      padding-left: 10px;
      padding-right: 10px;
    }
    .sbs-6310-template-".esc_attr($ids)."-description{
      padding-left: 10px;
      padding-right: 10px;
    }
    .sbs-6310-template-".esc_attr($ids)."-read-more{
      padding-left: 10px;
      padding-right: 10px;
    }
    @keyframes zoom-anim {
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

    @media only screen and (max-width: 767px) {
      .sbs-6310-col {
        width: 100%;
        margin: 0 auto;
      }
      .sbs-6310-template-".esc_attr($ids)." {
        margin-bottom: 5px;
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
