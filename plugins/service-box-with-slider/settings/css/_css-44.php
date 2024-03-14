<?php

$cssCode = "
  .sbs-6310-template-".esc_attr($templateId)." {
    float: left;
    width: 100%;
    height: 100%;
  }

  .sbs-6310-template-".esc_attr($templateId)."-container {
    box-shadow: 0 0 ".esc_attr($cssData['sbs_6310_box_shadow_hover_blur'])."px 5px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";
    background-color: ".esc_attr($cssData['sbs_6310_box_background_color']).";   
    color: #ddd;
    padding: 20px 20px 20px 20px;
    float: right;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    transition: .9s;
    height: 100%;
    width: 100%;
  }
  .sbs-6310-template-".esc_attr($templateId)."-container:hover {
    box-shadow: 0 0 ".esc_attr($cssData['sbs_6310_box_shadow_hover_blur'])."px 5px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
  }

  .sbs-6310-template-".esc_attr($templateId)."-container:before {
    content: '';
    position: absolute;
    top: 0%;
    left: 0%;
    width: 0px;
    height: 0px;
    border-bottom: 15px solid #191919;
    border-left: 15px solid #ffffff;
    -webkit-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.2);
    -moz-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.2);
    box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.2);
    display: block;
    width: 0;
  }

  .sbs-6310-template-".esc_attr($templateId)."-container.rounded, .box-b.rounded {
    -moz-border-radius: 5px 0 5px 5px;
    border-radius: 5px 0 5px 5px;
  }

  .sbs-6310-template-".esc_attr($templateId)."-container.rounded:before {
    border-width: 8px;
    border-color: #323232 #323232 transparent transparent;
    -moz-border-radius: 0 0 0 5px;
    border-radius: 0 0 0 5px;
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon {
    float: left;
    position: relative;
    width: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
    height: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
    line-height: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
    text-align: center;
    z-index: 1;
    transition: .9s;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    color: ".esc_attr($cssData['sbs_6310_icon_color']).";
  }

  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon{
    color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
  }

  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon i{
    line-height: ".esc_attr($cssData['sbs_6310_icon_width'])."px;
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon img{
    width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    height: auto;
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
   background-color: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
    left: 0;
    top: 0;
    border-radius: 50%;
    transition: 1s;
    z-index: -1;
  }

  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon::after {
    transform: scale(15, 15) translateX(20px);
    transition: .9s;
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon i {
    z-index: 1;
  }

  .sbs-6310-template-".esc_attr($templateId)."-wrapper {
    float: right;
    width: calc(100% - 100px);
    z-index: 1;
  }

  @media only screen and (max-width: 767px) {
    .sbs-6310-row {
      flex-direction: column;
    }
    .sbs-6310-col {
      width: calc(100% - 10px);
      margin: 5px;
    }
    .sbs-6310-template-".esc_attr($templateId)."-container {
      display: flex;
      flex-direction: column;
      width: 100%;
    }
    .sbs-6310-template-".esc_attr($templateId)."-icon {
      text-align: center;
    }
    .sbs-6310-template-".esc_attr($templateId)."-title {
      text-align: center;
    }
  }
  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
