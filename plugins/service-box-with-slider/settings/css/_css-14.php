<?php

$cssCode = "

  .sbs-6310-template-".esc_attr($templateId)."-wrapper {
  float: left;
  width: 100%;
  height: 100%;
  background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  border: ".esc_attr($cssData['sbs_6310_border_size'])."px solid ".esc_attr($cssData['sbs_6310_border_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
  border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
  padding: 0px 10px;
}
.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover { 
  border: ".esc_attr($cssData['sbs_6310_border_size'])."px solid ".esc_attr($cssData['sbs_6310_border_hover_color']).";
  background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId)." {
  float: left;
  width: 100%;
  height: 100%;
}

.sbs-6310-template-".esc_attr($templateId)."-icon-wrapper {
  width: 100%;
  margin-top: 10px;
  float: left;
  display: flex;
  justify-content: center;
  align-items: center;
}
.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  border-radius: 50%;
}
.sbs-6310-template-".esc_attr($templateId)."-icon {
  width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
  height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);  line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2 - ".esc_attr($cssData['sbs_6310_icon_border_width'])."px);
  border-radius: 50%;
  background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";;
  text-align: center;
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  color:".esc_attr($cssData['sbs_6310_icon_color']).";
 margin-top: ".esc_attr($cssData['sbs_6310_icon_margin_top'])."px;
  margin-bottom: ".esc_attr($cssData['sbs_6310_icon_margin_bottom'])."px;
  position: relative;
  border: ".esc_attr($cssData['sbs_6310_icon_border_width'])."px solid ".esc_attr($cssData['sbs_6310_icon_border_color']).";
  display: flex;
  justify-content: center;
  align-items: center;
  transition: .5s;
}

.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-icon {
  color:".esc_attr($cssData['sbs_6310_icon_hover_color']).";
  background:".esc_attr($cssData['sbs_6310_icon_background_hover_color']).";
}

.sbs-6310-template-".esc_attr($templateId)."-icon::after {
  background: none;
  border: calc(".esc_attr($cssData['sbs_6310_icon_border_width'])."px / 2 ) solid ".esc_attr($cssData['sbs_6310_icon_border_color']).";
  border-radius: 100%;
  content: '';
  display: inline-block;
  height: 115%;
  left: 50%;
  opacity: 0;
  position: absolute;
  top: 50%;
  transform: translateY(-50%) translateX(-50%) scale(0.5);
  transition: all 0.3s cubic-bezier(0.69, -0.32, 0.27, 1.39) 0s;
  visibility: hidden;
  width: 115%;
  z-index: 0;
}

.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-icon::after {
  opacity: 1;
  transform: translateY(-50%) translateX(-50%) scale(1.1);
  visibility: visible;
}
.sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-icon i {
  float: left;
  width: 100%;
  height: 100%;
  line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2 - ".esc_attr($cssData['sbs_6310_icon_border_width'])."px); 
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
