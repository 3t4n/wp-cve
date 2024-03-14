<?php

$cssCode = "
  .sbs-6310-template-".esc_attr($templateId)." {
    float: left;
    width: 100%;
    height: 100%;
    display: flex;
    overflow: hidden;
  }

  .sbs-6310-template-".esc_attr($templateId)."-left-section {
    float: left;
    width: 40%;
    background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
    display: flex;
    align-items: center;
    justify-content: center;    
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon {
    float: left;
    width: 100%;
    font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    color: ".esc_attr($cssData['sbs_6310_icon_color']).";
    text-align: center;
    z-index: 999;
  }

  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
    color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
  }
  .sbs-6310-template-".esc_attr($templateId)."-title { 
    z-index: 999;
  }
  .sbs-6310-template-".esc_attr($templateId)."-description{
    z-index: 999;
  }

  .sbs-6310-template-".esc_attr($templateId)."-right-section {
    float: left;
    height: 100%;
    width: 60%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 10px;
    box-sizing: border-box;
    position: relative;    
  }

  .sbs-6310-template-".esc_attr($templateId)."-right-section::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
    z-index: 1;
    transform: translateX(-100%);
    transition: .9s;    
  }

  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-right-section {
    background: transparent;
    z-index: 1;
  }

  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-right-section::after {
    transform: translateX(0);
  }
  .sbs-6310-template-".esc_attr($templateId)."-read-more {
    z-index: 999;
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon{
    width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  }
  
  .sbs-6310-template-".esc_attr($templateId)."-icon img{
    width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  }
  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
