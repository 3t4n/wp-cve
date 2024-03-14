<?php

$cssCode = "  
  .sbs-6310-template-".esc_attr($templateId)." {
    text-align: center;
    background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
    float: left;
    width: 100%;
    height: 100%;
    padding: 15px;
    border-radius: ".esc_attr($cssData['sbs_6310_border_radius'])."px;
    box-shadow: 0 0 ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px 5px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";   
    
  }
  .sbs-6310-template-".esc_attr($templateId).":hover {
    background: ".esc_attr($cssData['sbs_6310_box_background_hover_color'])."; 
    box-shadow: 0 0 ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px 5px ".esc_attr($cssData['sbs_6310_box_hover_shadow_color'])."; 
 }

  .sbs-6310-template-".esc_attr($templateId)."-icon-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
    padding: 10px 0;
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon {
    display: flex;
    justify-content: center;
    align-items: center;
    width: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
    height: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
    line-height: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
    border-radius: 50%;
    background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";;
    margin-bottom: 25px;
    font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    color: ".esc_attr($cssData['sbs_6310_icon_color']).";
    position: relative;
    transition: all 0.5s ease 0s;
    z-index: 2;
  }
  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon{
    color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon img{
    width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    border-radius: 50%;
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";;
    top: 0;
    left: 0;
    border-radius: 50%;
    z-index: -1;
  }

  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon::after {
    animation: flashing 2s ease infinite; 
    
  }

  @keyframes flashing {
    0% {
      transform: scale(1, 1);
    }
    100% {
      transform: scale(1.3, 1.3);
      opacity: 0;
    }
  }

  @media only screen and (max-width:990px) {
    .sbs-6310-template-".esc_attr($templateId)." {
      margin-bottom: 30px;
    }
  }
  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
