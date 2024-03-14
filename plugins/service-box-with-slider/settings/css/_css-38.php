<?php

$cssCode = "
.sbs-6310-template-".esc_attr($templateId)." {
  float: left;
  width: 100%;
  height: 100%;
}

.sbs-6310-template-".esc_attr($templateId)." {
  float: left;
  width: 100%;
  height: 400px;
}
.sbs-6310-template-".esc_attr($templateId)."-container{
  width:100%;
}
.sbs-6310-template-".esc_attr($templateId)."-card {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #000000;
  height: 400px;
  width: 100%;
}

.sbs-6310-template-".esc_attr($templateId)."-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 50%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.1);
}

.sbs-6310-template-".esc_attr($templateId)."-card {
  background-image: linear-gradient(45deg, ".esc_attr($cssData['sbs_6310_box_gradient_color_1']).", ".esc_attr($cssData['sbs_6310_box_gradient_color_2']).");
}

.sbs-6310-template-".esc_attr($templateId)."-frame {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
}

.sbs-6310-template-".esc_attr($templateId)."-pic {
  margin-bottom: 12px;
  // filter: invert(1);
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  color: ".esc_attr($cssData['sbs_6310_icon_color']).";
}

.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-pic {
  color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
}

.sbs-6310-template-".esc_attr($templateId)."-pic img {
  display: block;
  max-width: 100%;
  height: auto;
}
.sbs-6310-template-".esc_attr($templateId)."-overlay {
  position: absolute;
  bottom: 20px;
  right: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 52px;
  height: 52px;
  background-color: #ffffff;
  border-radius: 50%;
  transition: 0.5s;
  cursor: pointer;
}

.sbs-6310-template-".esc_attr($templateId)."-overlay::before {
  content: 'Read';
  text-transform: uppercase;
  font-size: 12px;
  font-weight: 500;
  letter-spacing: 0.02em;
  color: ".esc_attr($cssData['sbs_6310_title_font_color']).";
}

.sbs-6310-template-".esc_attr($templateId)."-overlay:hover, .sbs-6310-template-".esc_attr($templateId)."-overlay:focus {
  bottom: 0;
  right: 0;
  width: 100%;
  height: 100%;
  box-shadow: none;
  border-radius: 0;
  opacity: 1;
}

.sbs-6310-template-".esc_attr($templateId)."-overlay:hover::before, .sbs-6310-template-".esc_attr($templateId)."-overlay:focus::before {
  content: none;
}

.sbs-6310-template-".esc_attr($templateId)."-overlay {
  background-image: linear-gradient(45deg, ".esc_attr($cssData['sbs_6310_box_gradient_hover_color_1']).", ".esc_attr($cssData['sbs_6310_box_gradient_hover_color_2']).");
}

.sbs-6310-template-".esc_attr($templateId)."-content {
  z-index: 1;
  padding: 20px;
  line-height: 1.4;
  opacity: 0;
  visibility: hidden;
  box-sizing: border-box;
  pointer-events: none;
  transition: 0s;
}

.sbs-6310-template-".esc_attr($templateId)."-overlay:hover~.sbs-6310-template-".esc_attr($templateId)."-content {
  opacity: 1;
  visibility: visible;
  transition: 0.2s 0.3s;
}
.sbs-6310-template-".esc_attr($templateId)."-icon{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  float: left;
  display: flex;
  justify-content: center;
  align-items: center;
}

.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  float: left;
  display: flex;
  justify-content: center;
  align-items: center;
}

  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
