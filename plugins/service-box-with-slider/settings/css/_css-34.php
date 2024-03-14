<?php

$cssCode = "
.sbs-6310-template-".esc_attr($templateId)."{
  padding: 20px 25px;
  border-radius: 5px;
  text-align: right;
  overflow: hidden;
  z-index: 1;
  position: relative;
  float: left;
  width: 100%;
  height: 100%;
  box-sizing: border-box;
  background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  height: 100%;
}
.sbs-6310-template-".esc_attr($templateId).":before{
  content: '';
  width: 100%;
  height: 100%;
  border-radius: 5px;
  background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
  position: absolute;
  top: 0;
  left: -100%;
  z-index: -1;
  transition: all 0.5s ease 0s;
}
.sbs-6310-template-".esc_attr($templateId).":hover:before{ left: 0; }
.sbs-6310-template-".esc_attr($templateId).":after{
  content: '';
  width: ".esc_attr($cssData['sbs_6310_left_border_width'])."px;
  height: 0;
  border-radius: 5px;
  background: ".esc_attr($cssData['sbs_6310_left_border_color']).";
  position: absolute;
  top: 0;
  left: 0;
  transition: all 0.5s ease 0s;
}
.sbs-6310-template-".esc_attr($templateId).":hover:after{ 
  height: 100%; 
}
 .sbs-6310-template-".esc_attr($templateId)."-icon{
  color: ".esc_attr($cssData['sbs_6310_icon_color']).";
  display: flex;
  flex-direction: row;
}

.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: auto;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
  color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId)."-icon i{ 
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  margin-bottom: 10px;
  transition: all 0.5s ease 0s;
}

 .sbs-6310-template-".esc_attr($templateId)."-description{
  transition: all 0.5s ease 0s;
}


  @media only screen and (max-width:990px){
    .sbs-6310-template-".esc_attr($templateId)."{ margin-bottom: 30px; }
  }
  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
