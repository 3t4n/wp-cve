<?php

$cssCode = "
.sbs-6310-template-".esc_attr($templateId)."{
  margin:20px 0;
  padding:40px 20px;
  text-align: center;
  border-radius:".esc_attr($cssData['sbs_6310_box_radius'])."px;
  background:".esc_attr($cssData['sbs_6310_box_background_color']).";
  float: left;
  height: 100%;
  width: 100%;
  transition: all 0.30s ease 0s;
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
}
.sbs-6310-template-".esc_attr($templateId).":hover{
  background:".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId)."-icon{
  width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);  margin: 0 auto;
  font-size:".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";;
      line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
  border-radius:50%;
  transform: translateY(0);
  display: flex;
  justify-content: center;
  align-items: center;
  transition: all 0.30s ease 0s;
}
.sbs-6310-template-".esc_attr($templateId)."-icon-".esc_attr($templateId)." {
  float: left;
  width: 100%;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon{
  transform: translateY(-25%);
  background: ".esc_attr($cssData['sbs_6310_icon_background_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId)."-icon i{
  color:".esc_attr($cssData['sbs_6310_icon_color']).";
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon i{
  color:".esc_attr($cssData['sbs_6310_icon_hover_color']).";
  line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
}


.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
  height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
  border-radius: 50%;
}
.sbs-6310-template-".esc_attr($templateId)."-title{
  position: relative;
  top:40px;
  text-transform:uppercase;
  transform: translateY(0%);
  transition: all 600ms cubic-bezier(0.68, -0.55, 0.265, 1.55) 0s;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-title{
  top:-20px;
}
.sbs-6310-template-".esc_attr($templateId)."-description{

  opacity:0;
  transition: all 0.30s linear 0s;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-description{
  opacity:1;
}

.sbs-6310-template-".esc_attr($templateId)."-read-more{
  opacity: 0;
  transform: translateY(-25px);
  transition: .5s;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-read-more{
  opacity: 1;
  transform: translateY(0);
}
.sbs-6310-template-".esc_attr($templateId)."-icon-wrapper{
  height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2) !important;
  margin-top: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
}

";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
