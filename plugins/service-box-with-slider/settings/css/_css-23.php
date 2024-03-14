<?php

$cssCode = "
.sbs-6310-template-".esc_attr($templateId)."{
  padding: 35px 20px 50px calc((".esc_attr($cssData['sbs_6310_icon_font_size'])."px) + ".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2 / 2 + 30px) ;
  position: relative;
  float: left;
  width: 100%;
  height: 100%;
  background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
  border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
  z-index: 1;
  overflow: hidden;
}

.sbs-6310-template-".esc_attr($templateId).":hover {
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
}

.sbs-6310-template-".esc_attr($templateId).":before{
  content: '';
  width: 100%;
  height: 100%;
  background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
  position: absolute;
  z-index: -1;
  top: 0;
  left: 0;
  opacity: 0;
  transform: scale3d(.08, .08, .08);
  transition: all 0.33s ease 0s;
}
.sbs-6310-template-".esc_attr($templateId).":hover:before{
  opacity: 1;
  transform: scale3d(1, 1, 1);
}
.sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-icon{
  width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);      line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  color: ".esc_attr($cssData['sbs_6310_icon_color']).";
  text-align: center;
  border: ".esc_attr($cssData['sbs_6310_icon_border_width'])."px solid ".esc_attr($cssData['sbs_6310_icon_border_color']).";
  position: absolute;
  top: 55px;
  left: 20px;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: all 0.3s ease 0s;
  background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon{
  border-color: ".esc_attr($cssData['sbs_6310_icon_border_hover_color']).";
  color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon{ transform: translateY(20px); }
.sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-icon:after{
  content: '';
  height: ".esc_attr($cssData['sbs_6310_icon_border_width'])."px;
  width: calc(".esc_attr($cssData['sbs_6310_icon_border_width'])."px + 20px);
  background: ".esc_attr($cssData['sbs_6310_icon_border_color']).";
  position: absolute;
  top: -20px;
  left:  calc((".esc_attr($cssData['sbs_6310_icon_border_width'])."px + 20px) - 20px - ".esc_attr($cssData['sbs_6310_icon_border_width']).");
  right:  calc((".esc_attr($cssData['sbs_6310_icon_border_width'])."px + 20px) - 20px - ".esc_attr($cssData['sbs_6310_icon_border_width']).");
  transition: all 0.3s ease 0s;
}
.sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-icon:before{
  content: '';
  height: ".esc_attr($cssData['sbs_6310_icon_border_width'])."px;
  width: calc(".esc_attr($cssData['sbs_6310_icon_border_width'])."px + 20px);
  background: ".esc_attr($cssData['sbs_6310_icon_border_color']).";
  position: absolute;
  bottom: -20px;
  left:  calc((".esc_attr($cssData['sbs_6310_icon_border_width'])."px + 20px) - 20px - ".esc_attr($cssData['sbs_6310_icon_border_width']).");
  right:  calc((".esc_attr($cssData['sbs_6310_icon_border_width'])."px + 20px) - 20px - ".esc_attr($cssData['sbs_6310_icon_border_width']).");
  transition: all 0.3s ease 0s;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon:before, 
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon:after{
  background: ".esc_attr($cssData['sbs_6310_icon_border_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId)."-icon:after{
  top: auto;
  bottom: -20px;
  left:  calc((".esc_attr($cssData['sbs_6310_icon_border_width'])."px + 20px) - 20px - ".esc_attr($cssData['sbs_6310_icon_border_width']).");
  right:  calc((".esc_attr($cssData['sbs_6310_icon_border_width'])."px + 20px) - 20px - ".esc_attr($cssData['sbs_6310_icon_border_width']).");
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon:before{
  bottom: -25px;
  transform: rotate(90deg);
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon:after{
  transform: rotate(-90deg);
  top: -25px;
}
.sbs-6310-template-".esc_attr($templateId)."-icon{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
}

.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: auto;
}

@media only screen and (max-width:990px){
  .sbs-6310-template-".esc_attr($templateId)."{ margin-bottom: 30px; }
}
@media only screen and (max-width:767px){
  .sbs-6310-template-".esc_attr($templateId).":before{ transform: scale3d(1, 1, 1); }
  .sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-icon{ top: 30px; }
}
@media only screen and (max-width:480px){
  .sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-icon{ top: 40px; }
}


";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
