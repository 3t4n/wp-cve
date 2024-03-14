<?php

$cssCode = "
.sbs-6310-template-".esc_attr($templateId)."{
  text-align: center;
  padding: 0 15px 25px;
  border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px 0;
  box-shadow: 0 0 8px black inset;
  position: relative;
  transition: all 0.3s ease 0s;
  float: left;
  width: 100%;
  height: 100%;
  box-sizing: border-box;
  overflow: hidden;
  z-index: 1;
}
.sbs-6310-template-".esc_attr($templateId)."-wrapper{
  float: left;
  width: 100%;
  position: relative;
  height: 100%;
  background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
  left: 0px;
  border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px 0;
}
.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover{
  background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
}

.sbs-6310-template-".esc_attr($templateId).":before,
.sbs-6310-template-".esc_attr($templateId).":after{
  content: '';
  width: 25px;
  height: 25px;
  border-top: calc(".esc_attr($cssData['sbs_6310_box_border_width'])."px + 5px) solid ".esc_attr($cssData['sbs_6310_box_border_color1']).";
  border-right: calc(".esc_attr($cssData['sbs_6310_box_border_width'])."px + 5px) solid ".esc_attr($cssData['sbs_6310_box_border_color2']).";
  position: absolute;
  top: 0;
  right: 0;
  transition: .5s;
}
.sbs-6310-template-".esc_attr($templateId).":hover::after{
  width: 100%;
  height: 100%;
  z-index: -1;
}
.sbs-6310-template-".esc_attr($templateId).":hover::before{
  width: 100%;
  height: 100%;
  z-index: -1;
}
.sbs-6310-template-".esc_attr($templateId).":after{
  border-top: none;
  border-right: none;
  border-bottom: calc(".esc_attr($cssData['sbs_6310_box_border_width'])."px + 5px) solid ".esc_attr($cssData['sbs_6310_box_border_color1']).";
  border-left: calc(".esc_attr($cssData['sbs_6310_box_border_width'])."px + 5px) solid ".esc_attr($cssData['sbs_6310_box_border_color2']).";
  top: auto;
  right: auto;
  bottom: 0;
  left: 0;
  transition: .5s;
}
.sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-icon{
  width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 4);
height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);      line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
  background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";;
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  color:".esc_attr($cssData['sbs_6310_icon_color']).";
  margin-bottom: ".esc_attr($cssData['sbs_6310_icon_margin_bottom'])."px;
  border-radius: 0 0 15px 15px;
  box-shadow: 3px 3px 3px rgba(0,0,0,0.2);
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
 
}
.sbs-6310-template-".esc_attr($templateId)."-icon-wrapper {
  display: flex;
  justify-content: center;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon{
  background: ".esc_attr($cssData['sbs_6310_icon_background_hover_color']).";
  color:".esc_attr($cssData['sbs_6310_icon_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon i{
  transform: rotateX(360deg);
  transition: all 0.3s;
      line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
}

.sbs-6310-template-".esc_attr($templateId)."-icon-wrapper:after{
  left: auto;
  right: -15px;
}

.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: auto;
}

@media only screen and (max-width:990px){
  .sbs-6310-template-".esc_attr($templateId)."{ margin: 10px 10px 50px; }
}


";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
