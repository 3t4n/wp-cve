<?php

$cssCode = "
  .sbs-6310-template-".esc_attr($templateId)." {
    float: left;
    width: 100%;
    height: 100%;
    transition: all 0.5s ease 0s;
    border: ".esc_attr($cssData['sbs_6310_box_border_width'])."px solid ".esc_attr($cssData['sbs_6310_box_border_color']).";    box-shadow: 0 0 ".esc_attr($cssData['sbs_6310_box_shadow_hover_blur'])."px 5px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
    background-color: ".esc_attr($cssData['sbs_6310_box_background_color']).";
    border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
    padding: 0px 10px;
  }

  .sbs-6310-template-".esc_attr($templateId).":hover {
   background-color: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
    box-shadow: 0 0 ".esc_attr($cssData['sbs_6310_box_shadow_hover_blur'])."px 5px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
  }

  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
    transform: scale(1.1);
    color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
    background: ".esc_attr($cssData['sbs_6310_icon_hover_background_color']).";
    border: ".esc_attr($cssData['sbs_6310_icon_border_width'])."px solid ".esc_attr($cssData['sbs_6310_icon_border_color']).";
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon-wrapper {
    float: left;
    width: 100%;
    display: flex;
    justify-content: center;
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon{
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    color: ".esc_attr($cssData['sbs_6310_icon_color']).";
    border: ".esc_attr($cssData['sbs_6310_icon_border_width'])."px solid ".esc_attr($cssData['sbs_6310_icon_border_color']).";
    border-radius: ".esc_attr($cssData['sbs_6310_icon_border_radius'])."%;
    transition: all 0.9s ease 0s;
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon i {
    width: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
    height: ".esc_attr($cssData['sbs_6310_icon_width'])."px;    
    line-height: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
    text-align: center;
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon img{
    width: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
    height: ".esc_attr($cssData['sbs_6310_icon_width'])."px;    
    line-height: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
    text-align: center;
    display: block;
    border-radius: 50%;
  }
  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
