<?php

$cssCode = "
    .sbs-6310-template-".esc_attr($ids)." {
      float: left;
      width: 100%;
      height: 100%;
      box-sizing: border-box;
      padding: 25px 30px;
      text-align: center;
      background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
      border-top: ".esc_attr($cssData['sbs_6310_box_border_width'])."px solid ".esc_attr($cssData['sbs_6310_box_border_color']).";
      border-bottom: ".esc_attr($cssData['sbs_6310_box_border_width'])."px solid ".esc_attr($cssData['sbs_6310_box_border_color']).";      position: relative; 
      box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
      transition: .5s;
    }

    .sbs-6310-template-".esc_attr($ids).":before {
      content: '';
      border-top: 0 solid ".esc_attr($cssData['sbs_6310_border_hover_color']).";
      border-right: 0 solid transparent;
      position: absolute;
      left: 0;
      top: 0;
      z-index: 1;
      transition: all 0.3s ease 0s;
    }

    .sbs-6310-template-".esc_attr($ids).":hover:before {
      border-top-width: 78px !important;
      border-right-width: 78px;
    }
    .sbs-6310-template-".esc_attr($ids).":hover {
      background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
      border-color:".esc_attr($cssData['sbs_6310_border_hover_color']).";
      box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
    }
    .sbs-6310-template-".esc_attr($ids).":after {
      content: '';
      border-bottom: 0 solid ".esc_attr($cssData['sbs_6310_border_hover_color']).";
      border-left: 0 solid transparent;
      position: absolute;
      bottom: 0;
      right: 0;
      z-index: 1;
      transition: all 0.3s ease 0s;
    }

    .sbs-6310-template-".esc_attr($ids).":hover:after {
      border-bottom-width: 78px !important;
      border-left-width: 78px;
    }
    .sbs-6310-template-".esc_attr($ids)."-icon img{
      width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    }
    .sbs-6310-template-".esc_attr($ids)."-icon-".esc_attr($ids)." {
      display: flex;
      justify-content: center;
  }
    .sbs-6310-template-".esc_attr($ids)."-icon-wrapper{
      height: ".esc_attr($cssData['sbs_6310_icon_box_size_number'])."px !important;
    }
    .sbs-6310-template-".esc_attr($ids)."-icon {
      display: inline-block;
      width: ".esc_attr($cssData['sbs_6310_icon_box_size_number'])."px;
      height: ".esc_attr($cssData['sbs_6310_icon_box_size_number'])."px;
        line-height: ".esc_attr($cssData['sbs_6310_icon_box_size_number'])."px;

      border-radius: 20px;
      border: ".esc_attr($cssData['sbs_6310_icon_border_width'])."px solid ".esc_attr($cssData['sbs_6310_icon_border_color']).";
      background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";;
      font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      color: ".esc_attr($cssData['sbs_6310_icon_color']).";
      margin-bottom: 20px;
      position: relative;
      overflow: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
  
    }
    .sbs-6310-template-".esc_attr($ids)."-icon i{
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      height: 100%;
    }
    .sbs-6310-template-".esc_attr($ids).":hover .sbs-6310-template-".esc_attr($ids)."-icon i{
      line-height: calc(".esc_attr($cssData['sbs_6310_icon_box_size_number'])."px - ".esc_attr($cssData['sbs_6310_icon_border_width'])."px * 2);
    }

    .sbs-6310-template-".esc_attr($ids).":hover .sbs-6310-template-".esc_attr($ids)."-icon {
      background:  ".esc_attr($cssData['sbs_6310_icon_background_hover_color']).";
      color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
      border-color: ".esc_attr($cssData['sbs_6310_icon_border_hover_color']).";
    }

    @media only screen and (max-width:990px) {
      .sbs-6310-template-".esc_attr($ids)." {
        margin-bottom: 30px;
      }
    }
  ";

  wp_register_style( "sbs-6310-template-".esc_attr($ids)."-css", "" );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($ids)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($ids)."-css", $cssCode );
?>
