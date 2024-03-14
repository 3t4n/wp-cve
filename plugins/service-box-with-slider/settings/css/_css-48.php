<?php

$cssCode = "
    .sbs-6310-template-".esc_attr($templateId)."-parallax { 
      width: 100%;    
    }
    .sbs-6310-template-".esc_attr($templateId)." {
      text-align: center;
      padding: 15px;
      float: left;
      width: 100%;
      height: 100%;
      background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
      border-radius: ".esc_attr($cssData['sbs_6310_border_radius'])."px;
      box-shadow: 0 0 ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px 5px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
      box-sizing: border-box;
    }
    .sbs-6310-template-".esc_attr($templateId).":hover {
      background: ".esc_attr($cssData['sbs_6310_box_background_hover_color'])."; 
      box-shadow: 0 0 ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px 5px ".esc_attr($cssData['sbs_6310_box_hover_shadow_color']).";;
    }
    .sbs-6310-template-".esc_attr($templateId)."-icon-wrapper{
      height: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
    }

    .sbs-6310-template-".esc_attr($templateId)."-icon {
      color: ".esc_attr($cssData['sbs_6310_icon_color']).";
      background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";;
      font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      line-height: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
      height: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
      width: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
      margin: 0 auto 35px;
      border-radius: 15px;
      position: relative;
      z-index: 1;
      transition: all 0.3s ease 0s;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .sbs-6310-template-".esc_attr($templateId)."-icon:after {
      content: '';
      height: 115%;
      width: 115%;
      box-shadow: 0 0 10px ".esc_attr($cssData['sbs_6310_icon_box_shadow_color']).";
      border-radius: 10px;
      opacity: 0;
      transform: translateX(-50%) translateY(-50%) scale(0);
      position: absolute;
      left: 50%;
      top: 50%;
      z-index: -2;
      transition: all 0.3s;
    }

    .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
      color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
      transform: scale(0.9);
    }
    .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon:after {
      opacity: 1;
      transform: translateX(-50%) translateY(-50%) scale(1);
      box-shadow: 0 0 10px ".esc_attr($cssData['sbs_6310_icon_box_shadow_color']).", 0 0 10px rgba(0, 0, 0, 0.2) inset;
    }

    .sbs-6310-template-".esc_attr($templateId)."-icon img{
     width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      height: auto;
    }

    @media only screen and (max-width:990px) {
      .sbs-6310-template-".esc_attr($templateId)." {
        margin: 0 0 30px;
      }
    }
  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
