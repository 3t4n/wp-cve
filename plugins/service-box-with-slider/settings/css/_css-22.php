<?php

$cssCode = "
.sbs-6310-template-".esc_attr($templateId)."{
  float: left;
  width: 100%;
  height: 100%;
  background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
  padding:0px 10px;
}
.sbs-6310-template-".esc_attr($templateId).":hover{
  background: ".esc_attr($cssData['box_background_hover_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
}

.sbs-6310-template-".esc_attr($templateId)."-icon-wrapper{
  float: left;
  width: 100%;
  display: flex;
  justify-content: center;
}
.sbs-6310-template-".esc_attr($templateId)."-icon{
  display: inline-block;
  width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);      line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
 margin-top: ".esc_attr($cssData['sbs_6310_icon_margin_top'])."px;
  margin-bottom: ".esc_attr($cssData['sbs_6310_icon_margin_bottom'])."px;
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  color: ".esc_attr($cssData['sbs_6310_icon_color'])."; 
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
  color: ".esc_attr($cssData['sbs_6310_icon_hover_color'])."; 
}
.sbs-6310-template-".esc_attr($templateId)."-icon:before{
  content: '';
  width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);      line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
  border: ".esc_attr($cssData['sbs_6310_icon_border_size'])."px solid ".esc_attr($cssData['sbs_6310_icon_border_color']).";
  border-radius: 3px;
  position: absolute;
  transition: all 0.25s ease 0s;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon:before{
  border: calc(".esc_attr($cssData['sbs_6310_icon_border_size'])."px + 3px) solid ".esc_attr($cssData['sbs_6310_icon_border_hover_color']).";
  transform: rotate(45deg);
}

.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: auto;
}


@media only screen and (max-width: 990px){
  .sbs-6310-template-".esc_attr($templateId)."{ margin-bottom: 30px; }
}


";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
