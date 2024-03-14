<?php

$cssCode = "
.sbs-6310-template-".esc_attr($templateId)."-parallax{
  width: 100%;
}
.sbs-6310-template-".esc_attr($templateId)."-wrapper{
  float: left;
  width: 100%;
  height: 100%;
  position: relative;
  transition: .5s;
  overflow: hidden;
  border-radius:".esc_attr($cssData['sbs_6310_box_radius'])."px; 
}

.sbs-6310-template-".esc_attr($templateId)." {
  left: 0;
  top: 0;
  height: 300px;
  width: 100%;
  overflow: hidden;
  cursor: pointer;
  transition: .5s;  
 background-color: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
}

.sbs-6310-template-".esc_attr($templateId).":hover{
 background-color: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
}

.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-icon{
  opacity: 0;
  transition: .9s;
}
.sbs-6310-template-".esc_attr($templateId)."-icon {
  position: absolute;
  z-index: 1;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: 1s;
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  color: ".esc_attr($cssData['sbs_6310_icon_color']).";
}

.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
}
.sbs-6310-template-".esc_attr($templateId)."-content {
  position: absolute;
  top: 50%;
  transform: translatey(-50%);
  text-align: justify;
  color: black;
  padding: 40px;
  font-family: 'Merriweather', serif;
  transition: 1s;
  opacity: 0;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-content{
  z-index: 1;
  opacity: 1;
  transition: .9s;
}

.sbs-6310-template-".esc_attr($templateId)."-effect {
  width: 100%;
  height: 100%;
}

.sbs-6310-template-".esc_attr($templateId)."-effect::before {
  position: absolute;
  content: '';
  height: 100%;
  width: 60%;
  background:".esc_attr($cssData['sbs_6310_box_background_color']).";
  background-position: 100px;
  background-repeat: no-repeat;
  transition: 1s;
}

.sbs-6310-template-".esc_attr($templateId)."-effect::after {
  position: absolute;
  content: '';
  height: 100%;
  width: 60%;
  right: 0;
  background:".esc_attr($cssData['sbs_6310_box_background_color']).";
  background-position: -200px;
  background-repeat: no-repeat;
  transition: 1s;
}

.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-effect::after {
  transform: translatex(300px);
}

.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-effect::before{
  transform: translatex(-300px);
}
  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
