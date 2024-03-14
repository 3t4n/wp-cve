<?php

$cssCode = "
.sbs-6310-template-39-parallax{
  width: 100%;
}

  .sbs-6310-template-".esc_attr($templateId)." {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    width: 100%;
    height: 100%;
  }

  .sbs-6310-template-".esc_attr($templateId)."-container {
    position: relative;
    width: 100%;
    height: 300px;
    box-shadow: inset 5px 5px 5px rgb(0 0 0 / 20%), inset -5px -5px 15px rgb(255 255 255 / 10%), 5px 5px 15px rgb(0 0 0 / 30%), -5px -5px 15px rgb(255 255 255 / 10%);
    border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
    transition: 0.5s;
  }

  .sbs-6310-template-".esc_attr($templateId)."-box {
    position: absolute;
    top: 20px;
    left: 20px;
    right: 20px;
    bottom: 20px;
    background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
    border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    transition: 0.5s;
  }

  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-box {    
    background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
    transform: translateY(-50px);
  }

  .sbs-6310-template-".esc_attr($templateId)."-box:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 50%;
    height: 100%;
    background: rgba(255, 255, 255, 0.03);
  }

  .sbs-6310-template-".esc_attr($templateId)."-content {
    padding: 20px;
    text-align: center;
    z-index: 1;
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon {
    position: absolute;
    top: 25%;
    right: 30px;
    font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    color: ".esc_attr($cssData['sbs_6310_icon_color']).";
    opacity: 0.3;
    z-index: -1;
  }

  .sbs-6310-template-".esc_attr($templateId)."-box:hover .sbs-6310-template-".esc_attr($templateId)."-icon {
    color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon{
    width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  }
  
  .sbs-6310-template-".esc_attr($templateId)."-icon img{
    width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  }

  .sbs-6310-template-".esc_attr($templateId)."-title {
    transition: 0.5s; 
    z-index: 999; 
  }

  .sbs-6310-template-".esc_attr($templateId)."-description {
    transition: 0.5s;
    z-index: 999;
  }

  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
