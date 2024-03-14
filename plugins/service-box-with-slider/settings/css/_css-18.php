<?php

$cssCode = "

.sbs-6310-template-".esc_attr($templateId)."{
  padding:0px 10px 20px 10px;
  text-align: center;
  position: relative;
  border: ".esc_attr($cssData['sbs_6310_border_size'])."px solid ".esc_attr($cssData['sbs_6310_border_color']).";
  background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
  border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
  float: left;
  width: 100%;
  height: 100%;
  overflow: hidden;
}
.sbs-6310-template-".esc_attr($templateId).":hover { 
  border-color: ".esc_attr($cssData['sbs_6310_border_hover_color']).";
  background: ".esc_attr($cssData['box_background_hover_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId).":before{
  content: '';
  width: 100%;
  height: calc(".esc_attr($cssData['sbs_6310_border_size'])."px + 2px);
  position: absolute;
  bottom: 0;
  left: 0;
  transform: scale(0);
  transition: all 0.5s ease 0s;
}
.sbs-6310-template-".esc_attr($templateId)."-icon-wrapper-".esc_attr($templateId)." {
  display: flex;
  justify-content: center;
}
.sbs-6310-template-".esc_attr($templateId)."-icon{
  display: inline-block;
  width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 4);
  height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 4);
  line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 4);
  border-radius: 50%;
  border: 3px solid ".esc_attr($cssData['sbs_6310_icon_border_color']).";
  border-left: 3px solid transparent;
  border-right: 3px solid transparent;
  margin: 50px 0;
  position: relative;
   transition: .9s;
   display: flex;
   justify-content: center;
   align-items: center;
  transform: rotateY(0deg); 
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
  transform: rotateY(180deg); 
}

.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: 90%;
  height: 90%;
  border-radius: 50%;
}

.sbs-6310-template-".esc_attr($templateId)."-icon:after{
  content: '';
  width: 3px;
  height: 40px;
  background: ".esc_attr($cssData['sbs_6310_icon_border_color']).";
  margin: 0 auto;
  position: absolute;
  top: -40px;
  left: 0;
  right: 0;
}
.sbs-6310-template-".esc_attr($templateId)."-icon:before{
  content: '';
  width: 3px;
  height: 40px;
  background: ".esc_attr($cssData['sbs_6310_icon_border_color']).";
  margin: 0 auto;
  position: absolute;
  bottom: -40px;
  left: 0;
  right: 0;
}

.sbs-6310-template-".esc_attr($templateId)."-icon i{
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  color:".esc_attr($cssData['sbs_6310_icon_color']).";
  background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";;
  border-radius: 50%;
  position: absolute;
  top: 10px;
  left: 10px;
  bottom: 10px;
  right: 10px;
  z-index: 1 ;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: .5s;
}

.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon i{
  color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
  background: ".esc_attr($cssData['sbs_6310_icon_background_hover_color']).";
  display: flex !important;

}

.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon i:before { 
  transform: rotateY(180deg); 
}

.sbs-6310-hover-icon{
  visibility: hidden !important;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-hover-icon{
  visibility: visible !important;
  transition: .9s;
  display: block;
}

@media only screen and (max-width:990px){
  .sbs-6310-template-".esc_attr($templateId)."{ margin-bottom: 30px; }
}

";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
