<?php

$cssCode = "
.sbs-6310-template-".esc_attr($templateId)."-parallax{
  width: 100%;
}
.sbs-6310-template-".esc_attr($templateId)."-wrapper{
  float: left;
  width: calc(100% - 40px);
  height: 100%;
  position: relative;
  box-sizing: border-box;
}

.sbs-6310-template-".esc_attr($templateId)."-icon1 {
  background-color: transparent;
  border-radius: 3px;
  color: #fff;
  width: 100%;
  height: 300px;
  transform-style: preserve-3d;
  perspective: 2000px;
  transition: 0.4s;
  text-align: center;
}
.sbs-6310-template-".esc_attr($templateId)."-icon1:before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: transparent;
  border-top: ".esc_attr($cssData['sbs_6310_box_border_width'])."px solid ".esc_attr($cssData['sbs_6310_box_border_color']).";
  border-left: ".esc_attr($cssData['sbs_6310_box_border_width'])."px solid ".esc_attr($cssData['sbs_6310_box_border_color']).";
  box-sizing: border-box;
}
.sbs-6310-template-".esc_attr($templateId)."-icon1:after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-bottom: ".esc_attr($cssData['sbs_6310_box_border_width'])."px solid ".esc_attr($cssData['sbs_6310_box_border_color']).";  border-right: ".esc_attr($cssData['sbs_6310_box_border_width'])."px solid ".esc_attr($cssData['sbs_6310_box_border_color']).";
  box-sizing: border-box;
}
.sbs-6310-template-".esc_attr($templateId)."-icon1 i, img {
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
  width: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
  line-height: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
  background-color: ".esc_attr($cssData['sbs_6310_icon_background_color']).";
  color: ".esc_attr($cssData['sbs_6310_icon_color']).";
  position: absolute;
  bottom: 0;
  right: 0;
  z-index: 1;
}

.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-title{
  color: ".esc_attr($cssData['sbs_6310_title_font_hover_color']).";
}

.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-description{
  color: ".esc_attr($cssData['sbs_6310_details_font_hover_color']).";
}

.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-icon1 i, img {
  color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
}

.sbs-6310-template-".esc_attr($templateId)."-icon1 .sbs-6310-template-".esc_attr($templateId)."-icon2 {
  position: absolute;
  top: 30px;
  left: 0;
  width: 110%;
  height: calc(100% - 60px);
  background-color: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  border-radius: 3px;
  transition: 0.4s;
}
.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-icon1 .sbs-6310-template-".esc_attr($templateId)."-icon2 {
 background-color: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";  
}
.sbs-6310-template-".esc_attr($templateId)."-icon2 i{
  position: absolute;
  top: 0;
  left: 0;
}
.sbs-6310-template-".esc_attr($templateId)."-icon2 img{
  position: absolute;
  top: 0;
  left: 0;
  z-index: 1;
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
  width: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
  line-height: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
  background-color: ".esc_attr($cssData['sbs_6310_icon_background_color']).";
}

 .sbs-6310-template-".esc_attr($templateId)."-content-wrapper {
  position: absolute;
  top: 50%;
  left: 0;
  transform: translateY(-50%);
  text-align: center;
  width: 100%;
  padding: 30px 60px;
  line-height: 1.5;
  box-sizing: border-box;
}

.sbs-6310-template-".esc_attr($templateId)."-icon1:hover {
  transform: rotateY(-20deg) skewY(3deg);
}
.sbs-6310-template-".esc_attr($templateId)."-icon1:hover .sbs-6310-template-".esc_attr($templateId)."-icon2 {
  transform: rotateY(20deg) skewY(-3deg);
}

  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
