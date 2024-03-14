<?php

$cssCode = "

.sbs-6310-template-".esc_attr($templateId)."{
  float: left;
  width: 100%;
  height: 100%;
  border: ".esc_attr($cssData['sbs_6310_border_size'])."px solid ".esc_attr($cssData['sbs_6310_border_color']).";
  background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
  border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
  padding:0px 10px;
}
.sbs-6310-template-".esc_attr($templateId).":hover { 
  border: ".esc_attr($cssData['sbs_6310_border_size'])."px solid ".esc_attr($cssData['sbs_6310_border_hover_color']).";
  background: ".esc_attr($cssData['box_background_hover_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId)."-icon-wrapper{
  float: left;
  width: 100%;
 margin-top: ".esc_attr($cssData['sbs_6310_icon_margin_top'])."px;
  margin-bottom: ".esc_attr($cssData['sbs_6310_icon_margin_bottom'])."px;
  display: flex;
  justify-content: center;
}
.sbs-6310-template-".esc_attr($templateId)."-icon{
float: left;
width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
    line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
color:".esc_attr($cssData['sbs_6310_icon_color']).";
display: flex;
justify-content: center;
align-items: center;
position: relative;
}
.sbs-6310-template-".esc_attr($templateId)."-icon i{
  z-index: 1;
}

.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  z-index: 1;
  border-radius: 50%;
}
.sbs-6310-template-".esc_attr($templateId)."-icon::after{
  content: '';
  border: ".esc_attr($cssData['sbs_6310_icon_border_width'])."px solid ".esc_attr($cssData['sbs_6310_icon_border_color']).";
  transform: rotate(45deg);
  position: absolute;
  width: 100%;
  height: 100%;
  transition: .5s;
}
  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon::after{
    border-color: ".esc_attr($cssData['sbs_6310_icon_background_hover_color']).";
    background:".esc_attr($cssData['sbs_6310_icon_background_hover_color'])." ;
    transition: .5s;
  }
.sbs-6310-template-".esc_attr($templateId)."-icon::before{
    content: '';
    width: 100%;
    height: 100%;
    box-shadow: 0 0 0 calc(".esc_attr($cssData['sbs_6310_icon_border_width'])."px + 1px) ".esc_attr($cssData['sbs_6310_icon_background_hover_color'])." !important;
    position: absolute;
    top: calc(-".esc_attr($cssData['sbs_6310_icon_border_width'])."px * 2);
    left: calc(-".esc_attr($cssData['sbs_6310_icon_border_width'])."px * 2);
    opacity: 0;
    padding: calc(".esc_attr($cssData['sbs_6310_icon_border_width'])."px * 2);
    transform: scale(1.2);
    box-sizing: content-box;
    transform: scale(1) rotate(45deg);
    transition: all 0.2s ease 0s;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon::before{
  opacity: 1;
  transform: scale(1.03) rotate(45deg);
  transition: .5s;
}

.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon{
 color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
 transition: .5s;
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
