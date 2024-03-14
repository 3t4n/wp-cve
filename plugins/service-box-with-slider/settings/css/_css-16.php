<?php

$cssCode = "

.sbs-6310-template-".esc_attr($templateId)."{
  text-align: center;
  margin-top: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
  position: relative;
  z-index: 1;
  float: left;
  width: 100%;
  height: calc(100% - calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2));
}
.sbs-6310-template-".esc_attr($templateId)."-content:hover {
  background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
  transition: .5s;
}
.sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-icon1 {
  width: calc((".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2) + 10px);
  height: calc((".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2) + 10px);
  border-radius:3px;
  background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  margin: 0 auto;
  position: absolute;
  top: -34px;
  left: 0;
  right: 0;
  z-index: 1;
  transition: all 0.3s ease-out 0s;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon1{
  transform: rotate(45deg);
  color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
  background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon1 i{
  color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon1-i{
  background: ".esc_attr($cssData['sbs_6310_icon_background_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId)."-icon1-i{
  display: inline-block;
  width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);      line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
  border-radius:3px;
  background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";;
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  color: ".esc_attr($cssData['sbs_6310_icon_color']).";
  margin: auto;
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: all 0.3s ease-out 0s;
}
 .sbs-6310-template-".esc_attr($templateId)."-icon1-i i{
  transition: all 0.3s ease-out 0s;
}

.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon1 .sbs-6310-template-".esc_attr($templateId)."-icon1-i i, .sbs-6310-template-16-icon img{
  transform: rotate(-45deg);
}
.sbs-6310-template-".esc_attr($templateId)."-icon img{
  transform: rotate(0);
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-16-icon img{
  transform: rotate(-45deg);
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon i{
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}
.sbs-6310-template-".esc_attr($templateId)."-content{
  border: ".esc_attr($cssData['sbs_6310_border_size'])."px solid ".esc_attr($cssData['sbs_6310_border_color']).";
  border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
  background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
  position: relative;
  float: left;
  width: 100%;
  height: 100%;
  padding: 55px 15px;
}
.sbs-6310-template-".esc_attr($templateId)."-icon {
  display: flex;
  justify-content: center;
  align-items: center;
}
.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  display: flex;
  justify-content: center;
  align-items: center;
}
.sbs-6310-template-".esc_attr($templateId)."-content:before{
  content: '';
  display: block;
  width: 80px;
  height: 80px;
  border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
  margin: 0 auto;
  position: absolute;
  top: -37px;
  left: 0;
  right: 0;
  z-index: -1;
  transition: all 0.3s ease-out 0s;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-content:before .sbs-6310-template-".esc_attr($templateId)."-content{
  transform: rotate(180deg);
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-content{
   border: ".esc_attr($cssData['sbs_6310_border_size'])."px solid ".esc_attr($cssData['sbs_6310_border_hover_color']).";
}
.sbs-6310-image {
  transform: rotate(0);
}

@media only screen and (max-width: 990px){
  .sbs-6310-template-".esc_attr($templateId)."{ margin-bottom: 30px; }
}
@media only screen and (max-width: 767px){
  .sbs-6310-template-".esc_attr($templateId)."{ margin-bottom: 80px; }
}
";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
