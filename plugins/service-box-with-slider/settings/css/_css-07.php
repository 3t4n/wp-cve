<?php

$cssCode = "   

.sbs-6310-template-".esc_attr($templateId)." {
  float: left;
  width: 100%;
  height: 100%;
  overflow: hidden;
  position: relative;
  padding: 15px;
  transition: .5s;
  border: ".esc_attr($cssData['sbs_6310_box_border_width'])."px solid ".esc_attr($cssData['sbs_6310_box_border_color']).";  border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
  background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
}

.sbs-6310-template-".esc_attr($templateId)."::before {
  position: absolute;
  content: '';
  width: 0;
  height: ".esc_attr($cssData['sbs_6310_border_top_width_number'])."px;
  background: ".esc_attr($cssData['sbs_6310_border_top_color']).";  transition: .5s;
  left: 0;
  top: 0;
}

.sbs-6310-template-".esc_attr($templateId).":hover::before {
  width: 100%;
  box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
}

.sbs-6310-template-".esc_attr($templateId).":hover {
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
  transform: scale(1.02);
  background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
  border-color: ".esc_attr($cssData['sbs_6310_border_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId)."-icon-wrapper {
  float: left;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: ".esc_attr($cssData['sbs_6310_icon_margin_top'])."px;
  margin-bottom: ".esc_attr($cssData['sbs_6310_icon_margin_bottom'])."px;
}
.sbs-6310-template-".esc_attr($templateId)."-icon {
  float: left;
  box-sizing: border-box;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  color: ".esc_attr($cssData['sbs_6310_icon_color']).";
  
}
.sbs-6310-template-".esc_attr($templateId)."-icon{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
}  
.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
}

.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon {
  color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
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

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", "" );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
