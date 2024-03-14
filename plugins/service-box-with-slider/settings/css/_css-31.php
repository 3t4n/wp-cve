<?php

$cssCode = "
.sbs-6310-template-".esc_attr($templateId)."-parallax{
  float: left;
  width: 100%;
}
.sbs-6310-template-".esc_attr($templateId)."-wrapper {
  display: inline-block;
  perspective: 1000px;
  float: left;
  width: 100%;
  overflow: hidden;
}
.sbs-6310-template-".esc_attr($templateId)." {
  position: relative;
  cursor: pointer;
  transition-duration: 0.6s;
  transition-timing-function: ease-in-out;
  transform-style: preserve-3d;
  float: left;
  width: 100%;
  height: 200px;
}

.sbs-6310-template-".esc_attr($templateId)."-icon {
  float: right;
  width: calc(50% - 20px);
  text-align: right;
  margin: 0 10px;
  color: ".esc_attr($cssData['sbs_6310_icon_color']).";
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
}

.sbs-6310-template-".esc_attr($templateId)."-title{
  width: 50% !important;
}

.sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-front, .sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-back {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
 backface-visibility: hidden;
  transform: rotateX(0deg);
}

.sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-front {
  padding: 10px;
  z-index: 2;
}

.sbs-6310-template-".esc_attr($templateId)."-flip-right .sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-back {
  transform: rotate3d(-1, 1, 0, 180deg);
}

.sbs-6310-template-".esc_attr($templateId)."-flip-right:hover .sbs-6310-template-".esc_attr($templateId)." {
  transform: rotate3d(-1, 1, 0, 180deg);
}

.sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-front, .sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-back {
  display: flex;
  align-items: center;
  justify-content: center;
  border: ".esc_attr($cssData['sbs_6310_box_border_width'])."px solid ".esc_attr($cssData['sbs_6310_box_border_color']).";  border-radius: ".esc_attr($cssData['sbs_6310_box_border_radius'])."px;
}

.sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-front {
  background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
}

.sbs-6310-template-".esc_attr($templateId)." .sbs-6310-template-".esc_attr($templateId)."-back {
  background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
  padding:0px 10px;
}
.sbs-6310-template-".esc_attr($templateId)."-icon{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
}

.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: 100%;
  height: auto;
}

@media only screen and (max-width: 767px) {
  .sbs-6310-row {
    flex-direction: column;
  }
  .sbs-6310-col {
    width: calc(100% - 10px);
    margin: 5px;
  }
}
  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
