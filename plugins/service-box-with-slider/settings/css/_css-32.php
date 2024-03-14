<?php
$titleFontFamily =  str_replace("+", " ", esc_attr($cssData['sbs_6310_title_font_family']));

$cssCode = "
  .sbs-6310-template-".esc_attr($templateId)."-parallax {
  width: 100%;
  }
  .sbs-6310-template-".esc_attr($templateId)." {
    width: 100%;
    display: grid;
    grid-auto-rows: 300px;
    grid-gap: 35px;
    margin: auto 0;
    position: relative;
    height: 100%;
    box-sizing: border-box;
    overflow: hidden;
    box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_color']).";   
    border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
    background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
  }
  .sbs-6310-template-".esc_attr($templateId).":hover {
    background: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
    box-shadow: 0px 0px ".esc_attr($cssData['sbs_6310_box_shadow_blur'])."px ".esc_attr($cssData['sbs_6310_box_shadow_width'])."px ".esc_attr($cssData['sbs_6310_box_shadow_hover_color']).";
  }
  

 .sbs-6310-template-".esc_attr($templateId)."-icon {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    transition: 0.5s;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    color: ".esc_attr($cssData['sbs_6310_icon_color']).";
  }

  .sbs-6310-template-".esc_attr($templateId)."-box:hover .sbs-6310-template-".esc_attr($templateId)."-icon {
    top: 20px;
    left: calc(50% - (".esc_attr($cssData['sbs_6310_icon_font_size'])."px + 20px) / 2 );
    width: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px + 20px);
    height: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px + 20px);
    border-radius: 50%;
    color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
    background: ".esc_attr($cssData['sbs_6310_box_background_color']).";
    font-size: calc(".esc_attr($cssData['sbs_6310_icon_font_size'])."px / 2);
  }
  
  .sbs-6310-template-".esc_attr($templateId)."-icon img{
    width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    height: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    border-radius: 50%;
  }

  .sbs-6310-template-".esc_attr($templateId)."-box .sbs-6310-template-".esc_attr($templateId)."-content {
    position: absolute;
    top: 100%;
    height: calc(100% - 100px);
    text-align: center;
    padding: 20px;
    box-sizing: border-box;
    transition: 0.5s;
  }

  .sbs-6310-template-".esc_attr($templateId)."-box:hover .sbs-6310-template-".esc_attr($templateId)."-content {
    top: 100px;
  }

.sbs-6310-template-".esc_attr($templateId)."-title-extra {
    float: left;
    width: 100%;
    text-align: center;
    position: absolute;
    top: 70%;
    font-size:".esc_attr($cssData['sbs_6310_title_font_size'])."px;
    line-height:".esc_attr($cssData['sbs_6310_title_line_height'])."px;
    font-weight: ".esc_attr($cssData['sbs_6310_title_font_weight']).";
    text-transform: ".esc_attr($cssData['sbs_6310_title_text_transform']).";
    font-family: {$titleFontFamily}; 
    color: ".esc_attr($cssData['sbs_6310_title_font_color']).";

  }
  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-title-extra{
    opacity: 0;
  }
 
  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
