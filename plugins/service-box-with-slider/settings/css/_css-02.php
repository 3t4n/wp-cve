<?php

$cssCode = "   

    .sbs-6310-template-".esc_attr($templateId)." {
      float: left;
      width: 100%;
      height: 100%;
      padding: 10px;  
      position: relative;
      overflow: hidden;
      transition: 0.5s;
      box-shadow: ".esc_attr($cssData['sbs_6310_box_shadow_color'])." 0px 3px 8px;     
      background-color: ".esc_attr($cssData['sbs_6310_box_background_color']).";
      border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
      z-index:1;
    }
    .sbs-6310-template-".esc_attr($templateId).":hover{
      box-shadow: 0 0 ".esc_attr($cssData['sbs_6310_box_shadow_hover_blur'])."px 0 ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
    }

    .sbs-6310-template-".esc_attr($templateId)."::after {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";     
      top: 0;
      left: 0;
      z-index: -1;
      border-radius: 0px;
      transform: translateX(100%) translateY(100%);
      visibility: hidden;
      opacity: 0;
      transition: .5s;
    }

    .sbs-6310-template-".esc_attr($templateId).":hover::after {
      transform: translateX(0) translateY(0);
      visibility: visible;
      opacity: 1;
    }

    .sbs-6310-template-".esc_attr($templateId)."::before {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
      top: 0;
      left: 0;
      z-index: -1;
      border-radius: 0px;
      visibility: hidden;
      opacity: 0;
      transform: translateX(-100%) translateY(-100%);
      transition: .5s;
    }

    .sbs-6310-template-".esc_attr($templateId).":hover::before {
      transform: translateX(0) translateY(0);
      visibility: visible;
      opacity: 1;
    }
    .sbs-6310-template-".esc_attr($templateId)."-icon-wrapper {
      float: left;
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: ".esc_attr($cssData['social_margin_top'])."px;     
      margin-bottom: ".esc_attr($cssData['social_margin_bottom'])."px;   
    }
    .sbs-6310-template-".esc_attr($templateId)."-icon {
      float: left;
      width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;    
      height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      color:".esc_attr($cssData['sbs_6310_icon_color']).";     
      transition: .5s;
    }
    .sbs-6310-template-".esc_attr($templateId)."-icon img{
      width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;    
      height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    }
    
    .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon{
      color:".esc_attr($cssData['sbs_6310_icon_hover_color']).";
    }

    .sbs-6310-template-".esc_attr($templateId)."-title { 
      transition: .5s;
    } 

    @media only screen and (max-width: 767px) {
      .sbs-6310-col {
        width: 100%;
        margin: 0 auto;
      }
      .sbs-6310-template-".esc_attr($templateId)." {
        margin: 5px;
        width: calc(100% - 10px);
      }
      .sbs-6310-row {
        display: inline-block;
        width: 100%;
      }
    } 
      ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", "" );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
