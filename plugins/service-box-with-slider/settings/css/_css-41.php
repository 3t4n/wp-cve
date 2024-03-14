<?php

$cssCode = "  
  .sbs-6310-template-".esc_attr($templateId)." {
    float: left;
    width: 100%;
    height: 100%;
    transition: .5s;
    overflow: hidden;
    padding: 15px;
    box-sizing: border-box;
    position: relative;
    box-shadow: 0 0 ".esc_attr($cssData['sbs_6310_box_shadow_hover_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
    background-color: ".esc_attr($cssData['sbs_6310_box_background_color']).";
    border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
  }
  
  .sbs-6310-template-".esc_attr($templateId).":hover {
    animation: animate 1s;
    animation-timing-function: ease-in-out;
    animation-iteration-count: 1;
   background-color: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
    box-shadow: 0 0 ".esc_attr($cssData['sbs_6310_box_shadow_hover_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
  }

  @keyframes animate {
    16.65% {
      -webkit-transform: translateY(6px);
      transform: translateY(6px);
    }
    33.3% {
      -webkit-transform: translateY(-4px);
      transform: translateY(-4px);
    }
    49.95% {
      -webkit-transform: translateY(2px);
      transform: translateY(2px);
    }
    66.6% {
      -webkit-transform: translateY(-2px);
      transform: translateY(-2px);
    }
    83.25% {
      -webkit-transform: translateY(1px);
      transform: translateY(1px);
    }
    100% {
      -webkit-transform: translateY(0);
      transform: translateY(0);
    }
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon {
    width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
    height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);        
    line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);     
    text-align: center;
    border-radius: 45px 45px 0px 0px;
    border: 1px solid;
    font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    color: ".esc_attr($cssData['sbs_6310_icon_color']).";
    margin-bottom: 30px;
    -webkit-box-shadow: 2px 2px 20px rgb(0 0 0 / 10%);
    box-shadow: 2px 2px 20px rgb(0 0 0 / 10%);
    transition: all 500ms;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
    color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
    
  }
  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon i {
    float: left;
    width: 100%;
    text-align: center;
    width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
  height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);        line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
    height: 100%;
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon img{
    width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  }

  .sbs-6310-template-".esc_attr($templateId).":hover {
    border-color: #000000;
  }
  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
