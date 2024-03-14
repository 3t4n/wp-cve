<?php

$cssCode = "
.sbs-6310-template-".esc_attr($templateId)."-parallax{
  width: 100%;
}
.sbs-6310-template-".esc_attr($templateId)." {
  float: left;
  width: 100%;
  height: 350px;
  background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."%;
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
  border: ".esc_attr($cssData['sbs_6310_border_size'])."px solid ".esc_attr($cssData['sbs_6310_border_color']).";
  padding: 0px 10px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.sbs-6310-template-".esc_attr($templateId).":hover { 
  border: ".esc_attr($cssData['sbs_6310_border_size'])."px solid ".esc_attr($cssData['sbs_6310_border_hover_color']).";
  background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId)."-icon{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px !important;
  height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px !important;
}  
.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: 100%;
  height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px !important;
  line-height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px !important;
}
.sbs-6310-template-".esc_attr($templateId)."-icon-".esc_attr($templateId)." {
  width: 100%;
  display: flex;
  justify-content: center;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
  transform: translateY(-15px);
}

.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-title {
  transform: translateY(-20px);
  transition: all 0.5s;
}

.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-title::after {
  bottom: -50px;
  transition: all 0.5s;
}

.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-description {
  opacity: 1;
  transform: translateY(10px);
}
.sbs-6310-template-".esc_attr($templateId)."-icon-wrapper {
height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);}
.sbs-6310-template-".esc_attr($templateId)."-icon {
  float: left;
  width: 100%;
  text-align: center;
  margin-top: 10%;
  margin-bottom: 15px;
  box-sizing: border-box;
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  line-height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  color: ".esc_attr($cssData['sbs_6310_icon_color']).";
  transition: .5s;
  display: flex;
  justify-content: center;
  align-items: center;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
  color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
}

.sbs-6310-template-".esc_attr($templateId)."-title {
  float: left;
  transition: .5s;
  position: relative;
  text-align: center !important;
}

.sbs-6310-template-".esc_attr($templateId)."-title::after {
  content: '';
  position: absolute;
  border-top: ".esc_attr($cssData['sbs_6310_icon_border_width'])."px solid ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
  width: 50%;
  height: 100%;
  left: 25%;
  bottom: 10px;
  transition: .5s;
}

.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-title {
  color: ".esc_attr($cssData['sbs_6310_title_font_hover_color']).";
}

.sbs-6310-template-".esc_attr($templateId)."-description {
  float: left;
  width: 100%;
  box-sizing: border-box;
  padding: 10px;
  transition: .5s;
  opacity: 0;
  text-align: center !important;
}
.sbs-6310-template-".esc_attr($templateId)."-read-more{
  transform: translateY(-20px);
  opacity: 0;
  transition: .5s
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-read-more{
  transform: translateY(0);
  opacity: 1;
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
