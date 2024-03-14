<?php

$cssCode = "

  .sbs-6310-template-".esc_attr($templateId)." {
    background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
    border: ".esc_attr($cssData['sbs_6310_border_size'])."px solid ".esc_attr($cssData['sbs_6310_border_color']).";
    text-align: center;
    position: relative;
    float: left;
    width: 100%;
    height: 100%;
    padding: 15px;
    box-sizing: border-box;
    transition: all 0.5s ease 0s;
    box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
    border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."%;
  }
  .sbs-6310-template-".esc_attr($templateId).":hover { 
    border: ".esc_attr($cssData['sbs_6310_border_size'])."px solid ".esc_attr($cssData['sbs_6310_border_hover_color']).";
    background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
    box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";

  }
  .sbs-6310-template-".esc_attr($templateId).":before,
  .sbs-6310-template-".esc_attr($templateId).":after {
    content: '';
    width: 70%;
    height: 5px;
    background: ".esc_attr($cssData['sbs_6310_hover_effect_color']).";
    opacity: 0;
    position: absolute;
    top: -3px;
    left: 35%;
    transform: translateX(-50%);
    transition: all 0.5s ease 0s;
  }
  .sbs-6310-template-".esc_attr($templateId).":hover:before,
  .sbs-6310-template-".esc_attr($templateId).":hover:after {
    opacity: 1;
    left: 50%;
  }
  .sbs-6310-template-".esc_attr($templateId).":after {
    top: auto;
    bottom: -3px;
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon-wrapper2{
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    float: left;
  } 

  .sbs-6310-template-".esc_attr($templateId)."-icon img{
    width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
  height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);        line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
    border-radius: 50%;
  }
  .sbs-6310-template-icon-wrapper2 {
    width: 100%;
    display: flex;
    justify-content: center;
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon {
    width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
    height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);        
    line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
    border-radius: 50%;
    background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";;
    text-align: center;
    font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    color:".esc_attr($cssData['sbs_6310_icon_color']).";
   margin-top: ".esc_attr($cssData['sbs_6310_icon_margin_top'])."px;
    margin-bottom: ".esc_attr($cssData['sbs_6310_icon_margin_bottom'])."px;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon i {
    line-height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px * 2);
  }
  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon{
    color:".esc_attr($cssData['sbs_6310_icon_hover_color']).";
    background:".esc_attr($cssData['sbs_6310_icon_background_hover_color']).";
  }
  .sbs-6310-template-".esc_attr($templateId)."-title {
    margin: 0 0 10px 0;
    transition: all 0.5s ease 0s;
  }
  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-title { 
    opacity: 0; 
  }
  .sbs-6310-template-".esc_attr($templateId)."-description {
    transition: all 0.5s ease 0s;
  }
  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-description {
    margin-top: -20px;
    padding-bottom: 20px;
    color: #00bcd4;
  }
  @media only screen and (max-width:990px) {
    .sbs-6310-template-".esc_attr($templateId)."{ 
      margin-bottom: 30px; 
    }
  }

";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
