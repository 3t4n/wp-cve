<?php

$cssCode = "
  .sbs-6310-template-".esc_attr($templateId)." {
    width: 100%;
    float: left;
    height: 100%;
  }

  .sbs-6310-template-".esc_attr($templateId)."-wrapper {
    display: block;
    width: 100%;
    height:100%;
    position: relative;
    overflow: hidden;
    box-sizing: border-box;
    border: ".esc_attr($cssData['sbs_6310_box_border_width'])."px solid ".esc_attr($cssData['sbs_6310_box_border_color']).";    
    text-align: center;
    text-decoration: none;
    margin-right: 5px;
    background-color: ".esc_attr($cssData['sbs_6310_box_background_color']).";
    border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
  }

  .sbs-6310-template-".esc_attr($templateId)."-title-wrapper {
    width: 100%;
    display: flex;
    float: left;
    justify-content: center;
    align-items: center;
  }
  .sbs-6310-template-".esc_attr($templateId)."-title {
    border-bottom: ".esc_attr($cssData['sbs_6310_title_bottom_border'])."px solid ".esc_attr($cssData['sbs_6310_title_border_bottom_color']).";
    width: 70% !important;
    display: flex;
    float: left;
    justify-content: center;
    align-items: center;
    flex-direction: row-reverse;
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon{
    width: 30% !important;
  }
  .sbs-6310-template-".esc_attr($templateId)."-inner {
    margin: 15px;
    display: block;
  }

  .sbs-6310-template-".esc_attr($templateId)."-wrapper .sbs-6310-template-".esc_attr($templateId)."-title, .sbs-6310-template-".esc_attr($templateId)."-description {
    transition: transform 1s;
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon {
    float: left;
    width: 30%;
    text-align: left;
    font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    color: ".esc_attr($cssData['sbs_6310_icon_color']).";
    margin-bottom: ".esc_attr($cssData['sbs_6310_icon_margin_bottom'])."px;
  }

  .sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-icon {
    color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
  }
  
  .sbs-6310-template-".esc_attr($templateId)."-icon img{
    width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  }
  .sbs-6310-template-".esc_attr($templateId)."-wrapper:hover {
   background-color: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
    transition: background 0.5s;
  }

  .sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-title, .sbs-6310-template-".esc_attr($templateId)."-wrapper:active .sbs-6310-template-".esc_attr($templateId)."-title {
    color: ".esc_attr($cssData['sbs_6310_title_font_hover_color']).";
    border-bottom-color: ".esc_attr($cssData['sbs_6310_title_border_bottom_hover_color']).";
    transform: scale(1.1);
  }

  .sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-description {
    color: ".esc_attr($cssData['sbs_6310_details_font_hover_color']).";
    transition: transform 1s;
  }


  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
