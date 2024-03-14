<?php

$cssCode = "
    .sbs-6310-template-".esc_attr($templateId)."-parallax {
      width: 100%;
    }

    .sbs-6310-template-".esc_attr($templateId)." {
      padding:60px 10px 10px 10px;
      margin-top:".esc_attr($cssData['sbs_6310_icon_width'])."px;
      position: relative;
      transition: all 0.5s ease 0s;
      float: left;
      width: 100%;
      height: calc(100% - ".esc_attr($cssData['sbs_6310_icon_width'])."px);
      box-sizing: border-box;
      background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
      border-radius: ".esc_attr($cssData['sbs_6310_border_radius'])."px;
      box-shadow: 0 0 ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px 5px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
    }
    .sbs-6310-template-".esc_attr($templateId).":hover {
      background: ".esc_attr($cssData['sbs_6310_box_background_hover_color'])."; 
      box-shadow: 0 0 ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px 5px ".esc_attr($cssData['sbs_6310_box_hover_shadow_color']).";
    }
    .sbs-6310-template-".esc_attr($templateId)."-icon {
      width: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
      height: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
      line-height: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
      border-radius: 10px;
      background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";;
      font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      text-align: center;
      position: absolute;
      top: calc(-".esc_attr($cssData['sbs_6310_icon_width'])."px / 2);
      left: 0;
      transform: rotateX(90deg);
      perspective-origin: top;
      perspective: 1000px;
      transition: all 0.5s ease 0s;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
      background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";;
      color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
      left: 30px;
      top: calc(-".esc_attr($cssData['sbs_6310_icon_width'])."px / 2);
      transform: rotateX(0);
    }

    .sbs-6310-template-".esc_attr($templateId)."-icon img{
      width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    }

    @media only screen and (max-width:990px){
      .sbs-6310-template-".esc_attr($templateId)." { margin-bottom: 30px; }
    }
    @media only screen and (max-width:767px){
      .sbs-6310-template-".esc_attr($templateId)." { margin-bottom: 70px; }
    }
  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
