<?php

$cssCode = "
    .sbs-6310-template-".esc_attr($ids)." {
      float: left;
      width: calc(100% - 40px);
      height: 100%;
      margin-left: 40px;
      border: ".esc_attr($cssData['sbs_6310_box_border_size'])."px solid ".esc_attr($cssData['sbs_6310_box_border_color']).";
      position: relative;
      box-sizing: border-box;
      border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
      background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
      box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
      transition: .5s;
      z-index: 1;
    }
    .sbs-6310-template-05:hover {
      background: transparent !important;
    }

    .sbs-6310-template-".esc_attr($ids)."::before {
      position: absolute;
      content: '';
      width: 0;
      height: 0;
      background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
      border-radius: calc(".esc_attr($cssData['sbs_6310_box_radius'])."px - ".esc_attr($cssData['sbs_6310_box_border_size'])."px);
      top: 50%;
      left: 50%;
      transition: .5s;
      transform-origin: center center;
      z-index: -1;
    }

    .sbs-6310-template-".esc_attr($ids).":hover::before {
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
    }

    .sbs-6310-template-".esc_attr($ids).":hover {
      border-color: ".esc_attr($cssData['sbs_6310_box_border_hover_color']).";
      box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
    }

    .sbs-6310-template-".esc_attr($ids)."-content-box {
      float: left;
      margin-left: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px + 15px);
      padding: 0px 10px;
    }
    .sbs-6310-template-".esc_attr($ids)."-icon{
        width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
        height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      }  
      .sbs-6310-template-".esc_attr($ids)."-icon img{
        width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
        height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
        border-radius: 50%;
      }

    .sbs-6310-template-".esc_attr($ids)."-icon {
      display: inline-block;
      position: absolute;
      left: -".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      top: 10%;
      width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
    height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);      line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2 - calc(".esc_attr($cssData['sbs_6310_icon_border_size'])."px * 2));

      border-radius: 50%;
      background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";;
      border: ".esc_attr($cssData['sbs_6310_icon_border_size'])."px solid ".esc_attr($cssData['sbs_6310_icon_box_border_color']).";
      font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      text-align: center;
      color: ".esc_attr($cssData['sbs_6310_icon_color'])."; 
      transition: all 0.3s ease-in;
      box-sizing: border-box;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .sbs-6310-template-".esc_attr($ids)."-icon i {
      display:flex;
      justify-content: center;
      align-items: center;
      height: auto;      
    }

    .sbs-6310-template-".esc_attr($ids).":hover .sbs-6310-template-".esc_attr($ids)."-icon {
      background: ".esc_attr($cssData['sbs_6310_icon_background_hover_color']).";
      color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
    }

    .sbs-6310-template-".esc_attr($ids).":hover .sbs-6310-template-".esc_attr($ids)."-icon i{
          line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
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
