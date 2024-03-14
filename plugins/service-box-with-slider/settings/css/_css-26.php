<?php

$cssCode = "
  .sbs-6310-template-".esc_attr($templateId)."-parallax { 
    width: 100%;
  }
  
  .sbs-6310-template-".esc_attr($templateId)." {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    float: left;
    width: 100%;
    height: 100%;
   
  }

  .sbs-6310-template-".esc_attr($templateId)."-box {
    position: relative;
    width: 100%;
    border: ".esc_attr($cssData['sbs_6310_box_border_width'])."px solid ".esc_attr($cssData['sbs_6310_box_border_color']).";    box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
    border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
    overflow: hidden;
    text-align: center; 
    padding: 26px;
  }
  .sbs-6310-template-".esc_attr($templateId)."-box:hover { 
    border-color: ".esc_attr($cssData['sbs_6310_border_hover_color']).";
    box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";   
  }

  .sbs-6310-template-".esc_attr($templateId)."-box:before {
    content: '';
    width: 50%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    background: rgba(255,255,255,.2);
    z-index: 2;
    pointer-events: none;
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon {
    position: relative;
    width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
    height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);        
    line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);  
    color:".esc_attr($cssData['sbs_6310_icon_color']).";
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px; 
    transition: 1s;
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon img{
    width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    height: auto;
    border-radius: 50%;
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon-box {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: ".esc_attr($cssData['sbs_6310_icon_margin_bottom'])."px;
   margin-top: ".esc_attr($cssData['sbs_6310_icon_margin_top'])."px;
}

  .sbs-6310-template-".esc_attr($templateId)."-box
  .sbs-6310-template-".esc_attr($templateId)."-icon {
    box-shadow: 0 0 0 0 ".esc_attr($cssData['sbs_6310_box_background_color']).";
    background-color: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  }

  .sbs-6310-template-".esc_attr($templateId)."-box:hover
  .sbs-6310-template-".esc_attr($templateId)."-icon {
    box-shadow: 0 0 0 400px ".esc_attr($cssData['sbs_6310_box_background_color']).";
    background-color: ".esc_attr($cssData['sbs_6310_box_background_color']).";
    color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
  }


  .sbs-6310-template-".esc_attr($templateId)."
  .sbs-6310-template-".esc_attr($templateId)."-box
  .sbs-6310-template-".esc_attr($templateId)."-content {
    position: relative;
    z-index: 1;
    transition: 0.5s;
  } 

  .sbs-6310-template-".esc_attr($templateId)."
  .sbs-6310-template-".esc_attr($templateId)."-box:hover
  .sbs-6310-template-".esc_attr($templateId)."-content {
    color: #fff;
  }

";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
