<?php

$cssCode = "
    .sbs-6310-template-".esc_attr($ids)." {
      float: left;
      width: 100%;
      height: 100%;
      transition: .5s;
      padding: 20px;
      box-sizing: border-box;
      box-shadow: ".esc_attr($cssData['sbs_6310_box_shadow_color'])." 0px 3px 8px;   
      background-color: ".esc_attr($cssData['sbs_6310_box_background_color']).";
      border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
    }

    .sbs-6310-template-".esc_attr($ids).":hover {
      box-shadow: 0 0 ".esc_attr($cssData['sbs_6310_box_shadow_hover_blur'])."px 0 ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
      transform: scale(1.02);
     background-color: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
    }
    .sbs-6310-template-".esc_attr($ids)."-icon{
      width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    }  
    .sbs-6310-template-".esc_attr($ids)."-icon img{
      width: 100%;
      height: auto;
    }
    .sbs-6310-template-".esc_attr($ids)."-icon-wrapper {
      float: left;
      width: 100%;
      display: flex;
      justify-content: center;
     margin-top: ".esc_attr($cssData['sbs_6310_icon_margin_top'])."px;
      margin-bottom: ".esc_attr($cssData['sbs_6310_icon_margin_bottom'])."px;
    }
    .sbs-6310-template-".esc_attr($ids)."-icon {
      float: left;
      font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px !important;
      color:".esc_attr($cssData['sbs_6310_icon_color']).";
      text-align: center;
      display: block;
      transition: .5s;
    }
    .sbs-6310-template-".esc_attr($ids).":hover .sbs-6310-template-".esc_attr($ids)."-icon{
      color:".esc_attr($cssData['sbs_6310_icon_hover_color']).";
    }
    .sbs-6310-template-".esc_attr($ids).":hover .sbs-6310-template-".esc_attr($ids)."-icon {
      transform: rotate(360deg);
    }
    .sbs-6310-template-".esc_attr($ids)."-title {
      float: left;
      width: 100%;
      transition: .5s;    
    }  

    @media only screen and (max-width: 767px) {
      .sbs-6310-col {
        width: 100%;
        margin: 0 auto;
      }
      .sbs-6310-template-".esc_attr($ids)." {
        margin: 5px;
        width: calc(100% - 10px);
      }
      .sbs-6310-row {
        display: inline-block;
        width: 100%;
      }
    }
    
    .sbs-6310-template-".esc_attr($ids)."-description {    
      float: left;
      width: 100%;    
      margin-top: ".esc_attr($cssData['sbs_6310_details_margin_top'])."px; 
      margin-bottom: ".esc_attr($cssData['sbs_6310_details_margin_bottom'])."px;    
    }  
  ";

  wp_register_style( "sbs-6310-template-".esc_attr($ids)."-css", "" );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($ids)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($ids)."-css", $cssCode );
?>
