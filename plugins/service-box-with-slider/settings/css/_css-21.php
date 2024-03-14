<?php

$cssCode = "
.sbs-6310-template-".esc_attr($templateId)."-main-wrapper {
  float: left;
  width: 100%;
  height: 100%;
}
.sbs-6310-template-".esc_attr($templateId)."{
  float: left;
  width: 100%;
  height: 200px;
  position: relative;
  overflow: hidden;
  border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
  
}
.sbs-6310-template-".esc_attr($templateId)."-font-side{
  float: left;
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  transform: perspective(1000px) rotateY(0);
  position: absolute;
  opacity: 1;
  transition: .9s;
  overflow: hidden;
  perspective: 1000px;
  font-family: Vollkorn;
  position: absolute;
  background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  padding: 0px 10px;

}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-font-side{
  opacity: 0;
  transform: translateX(-110px) rotateY(90deg);
}
.sbs-6310-template-".esc_attr($templateId)."-backside {
    top: 0;
    left: 0;
    z-index: 1;
    opacity: 0;
    width: 100%;
    height: 100%;
    padding: 20px;
    box-sizing: border-box;
    text-align: center;
    transition: all .6s ease;   
    backface-visibility: hidden;
    transform-style: preserve-3d;
    transform: translateX(110px) rotateY(-90deg);   
    background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-backside{
  transform: perspective(1000px) rotateY(0);
  opacity: 1;
}
.sbs-6310-template-".esc_attr($templateId)."-icon-wrapper{
 margin-top: ".esc_attr($cssData['sbs_6310_icon_margin_top'])."px;
  margin-bottom: ".esc_attr($cssData['sbs_6310_icon_margin_bottom'])."px;
}
.sbs-6310-template-".esc_attr($templateId)."-icon{
  float: left;
  width: 100%;
  font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
  color: ".esc_attr($cssData['sbs_6310_icon_color']).";
 
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
