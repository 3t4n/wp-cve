<?php

$cssCode = "
.sbs-6310-template-28-parallax{
  width: 100%;
}
.sbs-6310-template-".esc_attr($templateId)."-wrapper{
  width: 100%;
  float: left;
  height: 100%;
}

.sbs-6310-template-".esc_attr($templateId)."-container{
  -webkit-transform-style: preserve-3d;
          transform-style: preserve-3d;
  -webkit-perspective: 1000px;
          perspective: 1000px;
}

.sbs-6310-template-".esc_attr($templateId)."-front,
.sbs-6310-template-".esc_attr($templateId)."-back{
  background-size: cover;
  background-position: center;
  -webkit-transition: -webkit-transform .7s cubic-bezier(0.4, 0.2, 0.2, 1);
  transition: -webkit-transform .7s cubic-bezier(0.4, 0.2, 0.2, 1);
  -o-transition: transform .7s cubic-bezier(0.4, 0.2, 0.2, 1);
  transition: transform .7s cubic-bezier(0.4, 0.2, 0.2, 1);
  transition: transform .7s cubic-bezier(0.4, 0.2, 0.2, 1);
  backface-visibility: hidden;
  min-height: 280px;
  border: ".esc_attr($cssData['sbs_6310_box_border_width'])."px solid ".esc_attr($cssData['sbs_6310_box_border_color']).";  box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
  border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
  background: ".esc_attr($cssData['sbs_6310_front_background_color']).";
}

.sbs-6310-template-".esc_attr($templateId)."-back{
  background: ".esc_attr($cssData['sbs_6310_backside_background_color'])."; 
  border: ".esc_attr($cssData['sbs_6310_box_border_width'])."px solid ".esc_attr($cssData['sbs_6310_border_hover_color']).";
   box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
}

.sbs-6310-template-".esc_attr($templateId)."-container:hover .sbs-6310-template-".esc_attr($templateId)."-front,
.sbs-6310-template-".esc_attr($templateId)."-container:hover .sbs-6310-template-".esc_attr($templateId)."-back{
    -webkit-transition: -webkit-transform .7s cubic-bezier(0.4, 0.2, 0.2, 1);
    transition: -webkit-transform .7s cubic-bezier(0.4, 0.2, 0.2, 1);
    -o-transition: transform .7s cubic-bezier(0.4, 0.2, 0.2, 1);
    transition: transform .7s cubic-bezier(0.4, 0.2, 0.2, 1);
    transition: transform .7s cubic-bezier(0.4, 0.2, 0.2, 1), -webkit-transform .7s cubic-bezier(0.4, 0.2, 0.2, 1);
}

.sbs-6310-template-".esc_attr($templateId)."-back{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
}

.sbs-6310-template-".esc_attr($templateId)."-inner{
    -webkit-transform: translateY(-50%) translateZ(60px) scale(0.94);
    transform: translateY(-50%) translateZ(60px) scale(0.94);
    top: 50%;
    position: absolute;
    left: 0;
    width: 100%;
    padding: 2rem;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    outline: 1px solid transparent;
    -webkit-perspective: inherit;
     perspective: inherit;
    z-index: 2;
}

.sbs-6310-template-".esc_attr($templateId)."-container .sbs-6310-template-".esc_attr($templateId)."-back{
    -webkit-transform: rotateY(180deg);
            transform: rotateY(180deg);
    -webkit-transform-style: preserve-3d;
            transform-style: preserve-3d;
}

.sbs-6310-template-".esc_attr($templateId)."-container .sbs-6310-template-".esc_attr($templateId)."-front{
    -webkit-transform: rotateY(0deg);
            transform: rotateY(0deg);
    -webkit-transform-style: preserve-3d;
            transform-style: preserve-3d;
}

.sbs-6310-template-".esc_attr($templateId)."-container:hover .sbs-6310-template-".esc_attr($templateId)."-back{
  -webkit-transform: rotateY(0deg);
          transform: rotateY(0deg);
  -webkit-transform-style: preserve-3d;
          transform-style: preserve-3d;
}

.sbs-6310-template-".esc_attr($templateId)."-container:hover .sbs-6310-template-".esc_attr($templateId)."-front{
  -webkit-transform: rotateY(-180deg);
          transform: rotateY(-180deg);
  -webkit-transform-style: preserve-3d;
          transform-style: preserve-3d;
}

.sbs-6310-template-".esc_attr($templateId)."-front .sbs-6310-template-".esc_attr($templateId)."-title{
  position: relative;
}

.sbs-6310-template-".esc_attr($templateId)."-title:after{
  content: '';
  width: ".esc_attr($cssData['sbs_6310_border_bottom_width'])."%;
  height: 2px;
  position: absolute;
  background: ".esc_attr($cssData['sbs_6310_border_bottom_color']).";
  display: block;
  left: 0;
  right: 0;
  margin: 0 auto;
  bottom: 0;
}

.sbs-6310-template-".esc_attr($templateId)."-icon{
  color: ".esc_attr($cssData['sbs_6310_icon_color']).";
  font-family: 'Montserrat';
  font-weight: 300;
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  width: 100%;
  text-align: center;
  padding-top: ".esc_attr($cssData['sbs_6310_icon_margin_top'])."px;
  padding-bottom: ".esc_attr($cssData['sbs_6310_icon_margin_bottom'])."px;
  display: flex;
  justify-content: center;
  align-items: center;
}


.sbs-6310-template-".esc_attr($templateId)."-icon img{
  width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  height: auto;
}

.sbs_6310_team_style_{$templateId}_background {
  width: 100%;
  float: left;
}

";

wp_register_style("sbs-6310-template-".esc_attr($templateId)."-css", '');
wp_enqueue_style("sbs-6310-template-".esc_attr($templateId)."-css");
wp_add_inline_style("sbs-6310-template-".esc_attr($templateId)."-css", $cssCode);
