<?php

$cssCode = "
  .sbs-6310-template-".esc_attr($templateId)."-container {
    width: 100%;
    height: 100%;
    position: relative;
    display: flex;
    justify-content: space-between;
  }

  .sbs-6310-template-".esc_attr($templateId)." {
    position: relative;
    cursor: pointer;
    float: left;
    width: 100%;
    height: 100%;
  }

  .sbs-6310-template-".esc_attr($templateId)."-face {
    width: 100%;
    height: 200px;
    transition: 0.5s;
  }

  .sbs-6310-template-".esc_attr($templateId)."-face1 {
    position: relative;
    background-color: ".esc_attr($cssData['sbs_6310_box_background_color']).";
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1;
    transform: translateY(100px);
  }

  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-face.sbs-6310-template-".esc_attr($templateId)."-face1 {
   background-color: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
    transform: translateY(0);
  }

  .sbs-6310-template-".esc_attr($templateId)."-content {
    transition: 0.5s;
    float: left;
    width: 100%;
    text-align: center;
    font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  }
  .sbs-6310-template-".esc_attr($templateId)."-content i{
    color: ".esc_attr($cssData['sbs_6310_icon_color']).";
  }

  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-content {
    opacity: 1;
    
  }
  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-content i{
    color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon img {
    width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon-wrapper{
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
  }

  .sbs-6310-template-".esc_attr($templateId)."-face.sbs-6310-template-".esc_attr($templateId)."-face2 {
    position: relative;
    background: ".esc_attr($cssData['sbs_6310_box_background_bottom_color']).";
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    box-sizing: border-box;
    transform: translateY(-100px);
    opacity: 0;
  }

  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-face.sbs-6310-template-".esc_attr($templateId)."-face2 {
    transform: translateY(0);  
    opacity: 1;
  }

  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
