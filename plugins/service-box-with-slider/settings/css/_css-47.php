<?php

$cssCode = "
.sbs-6310-template-".esc_attr($templateId)."-parallax {
  width: 100%;
}
.sbs-6310-template-".esc_attr($templateId)." {
  float: left;
  width: 100%;
  height: 100%;
  box-sizing: border-box;
  background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  position: relative;
  overflow: hidden;
  border-radius: ".esc_attr($cssData['sbs_6310_border_radius'])."px;
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
  transition: .9s;
}
.sbs-6310-template-".esc_attr($templateId).":hover {
  background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
  transition: .9s;
}

.sbs-6310-template-".esc_attr($templateId)."-font-container {
  float: left;
  width: 100%;
  height: 350px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 15px;
}

.sbs-6310-template-".esc_attr($templateId)."-content {
  position: absolute;
  width: 100%;
  height: 100% !important;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  transition: .9s;
  top: -100%;
  padding: 15px;
}

.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-content {
  top: 0;
  background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
}

.sbs-6310-template-".esc_attr($templateId)."-icon {
  float: left;
  width: 100%;
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  color: ".esc_attr($cssData['sbs_6310_icon_color']).";
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
  color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
}

.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: auto;
}

  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
