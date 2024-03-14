<?php

$cssCode = "

.sbs-6310-template-".esc_attr($templateId)."-parallax{
  width: 100%;
}
.sbs-6310-template-".esc_attr($templateId)."{
  float: left;
  width: 100%;
  height: 100%;
  position: relative;
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
  border: ".esc_attr($cssData['sbs_6310_border_size'])."px solid ".esc_attr($cssData['sbs_6310_border_color'])."; 
}
.sbs-6310-template-".esc_attr($templateId).":hover{
  border: ".esc_attr($cssData['sbs_6310_border_size'])."px solid ".esc_attr($cssData['sbs_6310_border_hover_color']).";
  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
  transition: .5s;
}

.sbs-6310-template-".esc_attr($templateId)."-font-side{
  width: 100%;
  height: 200px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  padding:0px 10px;
}
.sbs-6310-template-".esc_attr($templateId)."-icon{
  float: left;
  width: 100%;
  text-align: center;
  color:".esc_attr($cssData['sbs_6310_icon_color']).";
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  margin-bottom: ".esc_attr($cssData['sbs_6310_icon_margin_bottom'])."px;
 margin-top: ".esc_attr($cssData['sbs_6310_icon_margin_top'])."px;
 display: flex;
 align-items: center;
 justify-content: center;
}
.sbs-6310-template-".esc_attr($templateId)."-icon{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
}

.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: 100%;
  height: auto;
}

.sbs-6310-template-".esc_attr($templateId)."-back-side{
  position: absolute;
  width: calc(100% - 20px);
  height: calc(100% - 20px);
  top: 10px;
  left: 10px;
  right: 10px;
  bottom: 10px;
  transition: all .5s ease;
  background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
  backface-visibility: hidden;
  transform-style: preserve-3d;
  transform: translateY(70px) rotateX( -90deg);
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding:0px 10px;

}
.sbs_6310_team_style_30_background {
  width: 100%;
  float: left;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-back-side{
  opacity: 1;
  transform: rotateX(0);
}


@media only screen and (max-width: 767px) {
  .sbs-6310-row {
    flex-direction: column;
  }
  .sbs-6310-col{
      width: calc(100% - 10px);
      margin: 5px;
  }
}


";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
