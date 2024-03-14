<?php

$cssCode = "
.sbs-6310-template-".esc_attr($templateId)."{
  border:  ".esc_attr($cssData['sbs_6310_box_border_width'])."px solid ".esc_attr($cssData['sbs_6310_box_border_color']).";
  border-radius: 0px ".esc_attr($cssData['sbs_6310_box_radius'])."px;
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
  text-align: center;
  transition: all 0.5s ease 0s;
  float: left;
  width: 100%;
  height: 100%;
  background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 15px;
}
.sbs-6310-template-".esc_attr($templateId).":hover{
 background-color: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
  border: ".esc_attr($cssData['sbs_6310_box_border_width'])."px solid ".esc_attr($cssData['sbs_6310_border_hover_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-icon{
  width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);  line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2px);
  background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";;
 margin-top: ".esc_attr($cssData['sbs_6310_icon_margin_top'])."px;
  margin-bottom: ".esc_attr($cssData['sbs_6310_icon_margin_bottom'])."px;
  border-radius: 0 20px;
  transition: all 0.5s ease 0s;
  overflow: hidden;
  display: flex;
  justify-content: center;
  align-items: center;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon{
  background:".esc_attr($cssData['sbs_6310_icon_background_hover_color']).";
}
 .sbs-6310-template-".esc_attr($templateId)."-icon i{
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  color:".esc_attr($cssData['sbs_6310_icon_color']).";
      line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
  transition: all 0.5s ease 0s;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon i{
  color:".esc_attr($cssData['sbs_6310_icon_hover_color']).";
}

.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: auto;
}

.sbs-6310-template-".esc_attr($templateId)."-title:after{
  content: '';
  width: 25%;
  border-top: ".esc_attr($cssData['sbs_6310_bottom_border_width'])."px solid ".esc_attr($cssData['sbs_6310_border_bottom_color']).";
  display: block;
  margin: 15px auto;
  transition: all 0.8s ease 0s;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-title:after{
  width: 80%;
  border-color: ".esc_attr($cssData['sbs_6310_border_bottom_hover_color']).";
}

@media only screen and (max-width: 990px){
  .serviceBox{ margin-bottom: 30px; }
}


";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
