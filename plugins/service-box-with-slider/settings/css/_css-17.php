<?php

$cssCode = "
.sbs-6310-template-".esc_attr($templateId)."{
    float: left;
    width: 100%;
    border: ".esc_attr($cssData['sbs_6310_border_size'])."px solid ".esc_attr($cssData['sbs_6310_border_color']).";
    height: 100%;
    padding: 15px;
    border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
    background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
    box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
  }
  .sbs-6310-template-".esc_attr($templateId).":hover{
    border: ".esc_attr($cssData['sbs_6310_border_size'])."px solid ".esc_attr($cssData['sbs_6310_border_hover_color']).";
    background: ".esc_attr($cssData['box_background_hover_color']).";
    box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon1{
      width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2 + 15px);
      height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2 + 15px);
      line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2 + 15px);
      display: block;
      overflow: hidden;
      position: relative;
      margin: 0 auto;
     margin-top: ".esc_attr($cssData['sbs_6310_icon_margin_top'])."px;
      margin-bottom: ".esc_attr($cssData['sbs_6310_icon_margin_bottom'])."px;
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon1 .sbs-6310-template-".esc_attr($templateId)."-hover-1{
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border-width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px / 4);
      border-style: solid;
      border-radius: 400px;
      transform: rotate(-45deg);
      transition: all 0.5s ease 0s;
      z-index: 1;
      box-sizing: border-box;
  }
  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-hover-1{
      transform: rotate(315deg);
      transition: all 0.5s ease 0s;
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon1 .sbs-6310-template-".esc_attr($templateId)."-hover-2{
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border-width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px / 4);
      border-style: solid;
      border-radius: 400px;
      z-index: 0;
      box-sizing: border-box;
  }
  .sbs-6310-template-".esc_attr($templateId)."-icons{
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
  }
  .sbs-6310-template-".esc_attr($templateId)."-icons i{
      font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      color: ".esc_attr($cssData['sbs_6310_icon_color']).";
      transform: rotateY(0deg);
      transition: all 0.5s ease 0s;
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      height: 100%;
  }
  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icons i{
    color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon1:hover .sbs-6310-template-".esc_attr($templateId)."-icons i{
    transform: rotateY(360deg);
    line-height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    transition: all 0.5s ease 0s;
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon{
    width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    line-height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  }
  
  .sbs-6310-template-".esc_attr($templateId)."-icon img{
    width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  }  
  .sbs-6310-template-".esc_attr($templateId)."-icon1 .sbs-6310-template-".esc_attr($templateId)."-hover-2{
      border-color: ".esc_attr($cssData['sbs_6310_icon_color1']).";
  }
  
  .sbs-6310-template-".esc_attr($templateId)."-icon1 .sbs-6310-template-".esc_attr($templateId)."-hover-1{
      border-color: hsla(0, 0%, 0%, 0) ".esc_attr($cssData['sbs_6310_icon_color2'])." ".esc_attr($cssData['sbs_6310_icon_color2'])." hsla(0, 0%, 0%, 0);
  }
  .sbs-6310-template-".esc_attr($templateId)."-read-more-wrapper{
    float: left;
    width: 100%;
    text-align: center;
  }
  @media screen and (max-width: 990px){
      .sbs-6310-template-".esc_attr($templateId)."{
          margin-bottom: 35px;
      }
  }

";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
