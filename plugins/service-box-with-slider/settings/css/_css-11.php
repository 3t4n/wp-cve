<?php

$cssCode = "  

.sbs-6310-template-".esc_attr($templateId)."{
  float: left;
  width: 100%;
  height: 100%;
  box-sizing: border-box;
  text-align: center;
  padding: 35px 15px;
  overflow: hidden;
  position: relative;
  transition: all 0.3s ease 0s;
  background:".esc_attr($cssData['sbs_6310_box_background_color'])."
}
.sbs-6310-template-".esc_attr($templateId).":hover{
  background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId).":before{
  content: '';
  width: 40px;
  height: 40px;
  border-width: 20px;
  border-style: solid;
  border-color: #fff #fff rgba(1, 1, 1, 0.2) rgba(0, 0, 0, 0.2);
  position: absolute;
  top: -100px;
  right: -100px;
  box-sizing: border-box;
  transition: all 0.3s ease 0s;
}
.sbs-6310-template-".esc_attr($templateId).":hover:before{
  top: 0;
  right: 0;
}
.sbs-6310-template-".esc_attr($templateId)."-title{   
  transition: all 0.3s ease 0s;
}
.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
}
.sbs-6310-template-".esc_attr($templateId)."-icon {
  width: 100%;
  display: flex;
  justify-content: center;
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  color: ".esc_attr($cssData['sbs_6310_icon_color']).";
  margin-bottom: 14px;
  transition: all 0.3s ease 0s;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
  color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
} 
@media only screen and (max-width: 990px){
  .sbs-6310-template-".esc_attr($templateId)."{ margin-bottom: 20px; }
}

 ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", "" );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
