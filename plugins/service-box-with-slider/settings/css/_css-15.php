<?php

$cssCode = "
.sbs-6310-template-".esc_attr($templateId)."{
  float: left;
  width: 100%;
  height: 90%;
  border: ".esc_attr($cssData['sbs_6310_border_size'])."px solid ".esc_attr($cssData['sbs_6310_border_color']).";
  background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
  border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
  margin-top: 10%;
  padding: 0px 10px;
}
.sbs-6310-template-".esc_attr($templateId).":hover { 
  border: ".esc_attr($cssData['sbs_6310_border_size'])."px solid ".esc_attr($cssData['sbs_6310_border_hover_color']).";
  background: ".esc_attr($cssData['box_background_hover_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId)."-icon-wrapper{
  float: left;
  width: 100%;
  text-align: center;
  margin-bottom: ".esc_attr($cssData['sbs_6310_icon_margin_bottom'])."px;
  display: flex;
  justify-content: center;
  position: relative;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-hover-icon i {
  display: flex !important;
  width: 100%;
  height: 100%;
  justify-content: center;
  align-items: center;
}
.sbs-6310-template-".esc_attr($templateId)."-icon{
  width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2) !important;
  height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2) !important;
  line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2) !important;
  background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";;
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  color:".esc_attr($cssData['sbs_6310_icon_color']).";
  border-radius: 5px;
  border: 1px solid #dddd;
  border-radius: ".esc_attr($cssData['sbs_6310_icon_border_radius'])."px;
  animation: mymove-reverse 1s;
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
  top: -".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  overflow: hidden;
}
@keyframes mymove {
  0%   { background: ".esc_attr($cssData['sbs_6310_icon_background_hover_color']).";}
  30%   { background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";}
  50%  { background: rgb(255, 255, 255); color: #000;}
    100% { background: ".esc_attr($cssData['sbs_6310_icon_background_hover_color']).";}
}
@keyframes mymove-reverse {
  0%   { background: ".esc_attr($cssData['sbs_6310_icon_background_hover_color']).";}
  30%  { background: ".esc_attr($cssData['sbs_6310_icon_background_hover_color']).";}
  50%   { background:  rgb(255, 255, 255); color: #000;}
  100% { background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";}
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
  animation: mymove;
  color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
  animation-duration: .9s;
  transition: all 0.5s ease-in-out;
  background: ".esc_attr($cssData['sbs_6310_icon_background_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon i {
  width: 100%;
  height: 100%;
  line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
}
.sbs-6310-template-".esc_attr($templateId)."-icon{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
}

.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
}
.sbs-6310-template-".esc_attr($templateId)."-title{
  position: relative;
}

.sbs-6310-template-".esc_attr($templateId)."-title::after{
  content: '';
  background: ".esc_attr($cssData['sbs_6310_border_effect_hover_color']).";
  width: 0;
  height: ".esc_attr($cssData['sbs_6310_hover_border_effect_width'])."px;
  position: absolute;
  bottom: 0;
  left: 50%;
  transition: all 0.5s ease-in-out;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-title::after{
  width: 40%;
}
.sbs-6310-template-".esc_attr($templateId)."-title::before{
  content: '';
  background: ".esc_attr($cssData['sbs_6310_border_effect_hover_color']).";
  width: 0;
  height: ".esc_attr($cssData['sbs_6310_hover_border_effect_width'])."px;
  position: absolute;
  bottom: 0;
  right: 50%;
  transition: all 0.5s ease-in-out;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-title::before{
  width: 40%;
}

.sbs-6310-template-15-parallax{
  width:100%;
}

@media only screen and (max-width: 767px) {
  .sbs-6310-col {
    width: 100%;
    margin: 0 auto;
  }
  .sbs-6310-template-".esc_attr($templateId)." {
    margin-bottom: 5px;
  }
  .sbs-6310-row {
    display: inline-block;
    width: 100%;
  }
}

";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
