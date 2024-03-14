<?php

$cssCode = "
  .sbs-6310-template-".esc_attr($templateId)."-parallax{
    width: 100%;
  }
  .sbs-6310-template-".esc_attr($templateId)." {
    width: 100%;
    float: left;
    height: 100%;
  }
  .sbs-6310-template-".esc_attr($templateId)."-wrapper {
    position: relative;
    width: 100%;
    height: 400px;
    margin: 0 auto; 
    border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;   
  }
  .sbs-6310-template-".esc_attr($templateId)."-wrapper:hover {
    background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
  }
  .sbs-6310-template-".esc_attr($templateId)."-font {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
    background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  }
  .sbs-6310-template-".esc_attr($templateId)."-font1 {
    box-sizing: border-box;
    background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
    padding: 20px;
    transition: 1s;
  }
  .sbs-6310-template-".esc_attr($templateId)."-font1 .sbs-6310-template-".esc_attr($templateId)."-number {
    margin: 0;
    padding: 0;    
  }

  .sbs-6310-template-".esc_attr($templateId)."-font2 {
    transition: 0.5s;
  } 
  .sbs-6310-template-".esc_attr($templateId)."-font2 .sbs-6310-template-".esc_attr($templateId)."-number {
    margin: 0;
    padding: 0;
    color: ".esc_attr($cssData['sbs_6310_icon_color']).";
    font-size: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
    transition: 0.5s;
    text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    z-index: 10;
  } 
  .sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-font2 {
    height: 60px;
  }
  .sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-number {
    font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon{
    width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    float: left;
    display: flex;
    justify-content: center;
    align-items: center;
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
