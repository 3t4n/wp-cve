<?php

$cssCode = "
.sbs-6310-template-".esc_attr($templateId)."{
  border: ".esc_attr($cssData['sbs_6310_box_border_width'])."px solid ".esc_attr($cssData['sbs_6310_box_border_color']).";  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
  text-align: center;
  overflow: hidden;
  position: relative;
  z-index: 1;
  transition: all 0.5s ease 0s;
  float: left;
  width: 100%;
  height: 100%;
  box-sizing: border-box;
  border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
  padding:0px 10px;
}
.sbs-6310-template-".esc_attr($templateId).":hover { 
  border: ".esc_attr($cssData['sbs_6310_box_border_width'])."px solid ".esc_attr($cssData['sbs_6310_border_hover_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId).":before,
.sbs-6310-template-".esc_attr($templateId).":after{
  content: '';
  width: 200%;
  height: 200%;
  background: ".esc_attr($cssData['sbs_6310_box_background1_color']).";
  position: absolute;
  top: 100px;
  left: -20px;
  z-index: -1;
  transform: rotate(-18deg);
  transition: all 0.5s ease 0s;
}
.sbs-6310-template-".esc_attr($templateId).":before{
  background: ".esc_attr($cssData['sbs_6310_box_background2_color']).";
  left: -115%;
  transform: rotate(24deg);
}
.sbs-6310-template-".esc_attr($templateId).":hover:before{
  transform: rotate(16deg);
  background: ".esc_attr($cssData['sbs_6310_box_background2_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId).":hover:after{
  background: ".esc_attr($cssData['sbs_6310_box_background1_hover_color']).";
  transform: rotate(-10deg);
}
.sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-icon{
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  color: ".esc_attr($cssData['sbs_6310_icon_color']).";
 margin-top: ".esc_attr($cssData['sbs_6310_icon_margin_top'])."px;
  margin-bottom: ".esc_attr($cssData['sbs_6310_icon_margin_bottom'])."px;
  display: flex;
  justify-content: center;
  align-items: center;
}
.sbs-6310-template-25-icon-wrapper {
  width: 100%;
  display: flex;
  justify-content: center;
}
.sbs-6310-template-".esc_attr($templateId)."-content{
 float: left; 
 width: 100%;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon{
  color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId)."-icon i{
  transition: 1s;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon i{
  transform: scale(1.3)
}
.sbs-6310-template-".esc_attr($templateId)."-icon{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
}

.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: auto;
}

@media only screen and (max-width: 990px){
  .sbs-6310-template-".esc_attr($templateId)."{ margin-bottom: 30px; }
}
@media only screen and (max-width: 767px){
  .sbs-6310-template-".esc_attr($templateId).":before,
  .sbs-6310-template-".esc_attr($templateId).":after{
      top: 80px;
  }
}
@media only screen and (max-width: 480px){
  .sbs-6310-template-".esc_attr($templateId).":before,
  .sbs-6310-template-".esc_attr($templateId).":after{
      top: 140px;
  }
}


";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
