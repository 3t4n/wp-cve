<?php

$cssCode = "
.sbs-6310-template-".esc_attr($templateId)."-parallax{
  width: 100%;
}
.sbs-6310-template-".esc_attr($templateId)."-f-box {
  width: 100%;
  height: 200px;
}

.sbs-6310-template-".esc_attr($templateId)."-f-box:hover .sbs-6310-template-".esc_attr($templateId)."-f-box-back{ 
  background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
}
.sbs-6310-template-".esc_attr($templateId)."-f-box-inner {
  position: relative;
  width: 100%;
  height: 100%;
  text-align: center;
  transition: transform 0.8s;
  transform-style: preserve-3d;
}

.sbs-6310-template-".esc_attr($templateId)."-f-box:hover .sbs-6310-template-".esc_attr($templateId)."-f-box-inner {
  transform: rotateX(180deg);
}

.sbs-6310-template-".esc_attr($templateId)."-f-box-front, .sbs-6310-template-".esc_attr($templateId)."-f-box-back {
  position: absolute;
  width: 100%;
  height: 100%;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
}

.sbs-6310-template-".esc_attr($templateId)."-f-box-front {
  background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
}
.sbs-6310-template-".esc_attr($templateId)."-icon-wrapper {
  width: 100%;
  display: flex;
  justify-content: center;
}
.sbs-6310-template-".esc_attr($templateId)."-f-box-back {
  transform: rotateX(180deg);
  background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
}

.sbs-6310-template-".esc_attr($templateId)."-back {
  float: left;
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 100%;
  padding:0px 10px;
}

.sbs-6310-template-".esc_attr($templateId)."-icon {
  float: left;
  width: 100%;
  text-align: center;
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  color: ".esc_attr($cssData['sbs_6310_icon_color']).";
  margin-top: ".esc_attr($cssData['sbs_6310_icon_margin_top'])."px;
  margin-bottom: ".esc_attr($cssData['sbs_6310_icon_margin_bottom'])."px;
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
  height: 100%;
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
