<?php

$cssCode = "
  .sbs-6310-template-".esc_attr($templateId)."-parallax { 
    width: 100%;
  }
  .sbs-6310-template-".esc_attr($templateId)." {
    text-align: center;
    padding: 30px;
    overflow: hidden;
    position: relative;
    float: left;
    width: 100%;
    height:100%;
    box-sizing: border-box;
    background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
    border-radius: ".esc_attr($cssData['sbs_6310_border_radius'])."px;
    box-shadow: 0 0 ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px 5px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";    
  }
  .sbs-6310-template-".esc_attr($templateId).":hover {
    background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
    box-shadow: 0 0 ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px 5px ".esc_attr($cssData['sbs_6310_box_hover_shadow_color']).";
  }
  .sbs-6310-template-".esc_attr($templateId)."-content {
    border: ".esc_attr($cssData['sbs_6310_border_width'])."px double ".esc_attr($cssData['sbs_6310_box_border_color']).";
    padding: 40px 30px 20px;
    width: 100%;
    height: 100%;
    position: relative;
    float: left;
    transition: all 0.3s ease-in-out 0s;
    width: 100%;
    height: 100%;
  }
  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-content {
    border: ".esc_attr($cssData['sbs_6310_border_width'])."px double ".esc_attr($cssData['sbs_6310_box_border_color']).";
  }
  
  .sbs-6310-template-".esc_attr($templateId)."-icon i {
    font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    color: ".esc_attr($cssData['sbs_6310_icon_color']).";
    margin-bottom: 10px;
    transition: all 0.3s ease-in-out 0s;
  }
  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon i {
    color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
    transform: rotate(360deg);
  }
  
  .sbs-6310-template-".esc_attr($templateId)."-icon-bg i  {
    font-size: 100px ;
    color: rgba(255, 255, 255, 0.3) !important;
    line-height: 100px ;
    position: absolute ;
    bottom: 50px ;
    right: 0 ;
    transition: all 0.3s ease-in-out 0s ;
    display: block ;
  }
  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon-bg i{
    transform: rotate(360deg);
    color: rgba(255, 255, 255, 0.3) !important;    
  }  

  .sbs-6310-template-".esc_attr($templateId)."-icon img{
    width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    height: auto;
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon-bg img {
    width: 100px;
    opacity: .4;
    bottom: 50px;
    position: absolute;
    right: 0;
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  @media only screen and (max-width: 990px){
    .sbs-6310-template-".esc_attr($templateId)." { 
      margin-bottom: 30px; 
    }
  }
  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
