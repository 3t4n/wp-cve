<?php
$titleFontFamily =  str_replace("+", " ", esc_attr($cssData['sbs_6310_title_font_family'])); 
$cssCode = "
  .sbs-6310-template-".esc_attr($templateId)." {
    text-align: center;
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
    margin: 0 auto;
    float: left;
    z-index: 1;
    background-color: ".esc_attr($cssData['sbs_6310_box_background_color']).";
    border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
  }
  .sbs-6310-template-".esc_attr($templateId).":hover{
   background-color: ".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
  }



  .sbs-6310-template-".esc_attr($templateId)."-wrapper {
    display: block;
    float: left;
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
    border-collapse: collapse;
    text-decoration: none;
    color: #fff;
    padding: 0 10px;
    box-sizing: border-box;
    z-index: 1;
  }

  .sbs-6310-template-".esc_attr($templateId)."-wrapper .sbs-6310-template-".esc_attr($templateId)."-title {
    margin: 0;
    float: left;
    width: 100%;
    font-family: {$titleFontFamily};   
    color: ".esc_attr($cssData['sbs_6310_title_font_color']).";
    line-height: ".esc_attr($cssData['sbs_6310_title_line_height'])."px;
    font-size:".esc_attr($cssData['sbs_6310_title_font_size'])."px;
    font-weight: ".esc_attr($cssData['sbs_6310_title_font_weight']).";
    text-transform: ".esc_attr($cssData['sbs_6310_title_text_transform']).";
    text-align: ".esc_attr($cssData['sbs_6310_title_text_align']).";   
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon {
    float: left;
    width: 100%;
    margin-bottom: 10px;
    font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    color: ".esc_attr($cssData['sbs_6310_icon_color']).";
    display: flex !important;
    justify-content: center;
    align-items: center;
  }

  .sbs-6310-template-".esc_attr($templateId)."-wrapper:hover .sbs-6310-template-".esc_attr($templateId)."-icon {
    color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
  }

  .sbs-6310-template-".esc_attr($templateId)."-icon  {
    transition: 5s !important;
    display: block;
    font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    text-shadow: 8px 6px 4px #000000;
  }

  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-icon  {
    transform: scale(1.2) !important;
    transition: 5s;
  }
  .sbs-6310-template-".esc_attr($templateId)."-icon img{
    width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
    height: auto;
  }
  .sbs-6310-template-".esc_attr($templateId)."-wrapper:before {
    transition: all 0.4s;
    position: absolute;
    content: '';
    width: 100%;
    height: 50%;
    left: 50%;
    margin-left: -50%;
    top: 25%;
    border-color: rgba(200, 200, 200, 0);
    border-style: solid;
    border-width: 0 ".esc_attr($cssData['sbs_6310_box_border_width'])."px;
    z-index: -1;
    box-sizing: border-box;
  }

  .sbs-6310-template-".esc_attr($templateId)."-wrapper:after {
    transition: all 0.4s;
    position: absolute;
    content: '';
    width: 50%;
    height: 100%;
    left: 50%;
    margin-left: -25%;
    top: 0;
    border-color: rgba(200, 200, 200, 0);
    border-style: solid;
    border-width: ".esc_attr($cssData['sbs_6310_box_border_width'])."px 0;
    z-index: -1;
    box-sizing: border-box;
  }

  .sbs-6310-template-".esc_attr($templateId)."-wrapper:hover:after {
    width: 100%;
    margin-left: -50%;
    border-color: ".esc_attr($cssData['sbs_6310_box_border_color_1']).";
  }

  .sbs-6310-template-".esc_attr($templateId)."-wrapper:hover:before {
    height: 100%;
    top: 0%;
    border-color: ".esc_attr($cssData['sbs_6310_box_border_color_2'])."
  }


  ";

  wp_register_style( "sbs-6310-template-".esc_attr($templateId)."-css", '' );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($templateId)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($templateId)."-css", $cssCode );
?>
