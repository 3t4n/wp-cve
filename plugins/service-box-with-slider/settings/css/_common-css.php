<?php
  $titleFontFamily =  str_replace("+", " ", esc_attr($cssData['sbs_6310_title_font_family'])); 
  $detailFontFamily = str_replace("+", " ", esc_attr($cssData['sbs_6310_details_font_family'])); 
  $readMoreFontFamily =  str_replace("+", " ", esc_attr($cssData['sbs_6310_read_more_font_family'])); 
  $bgCSS = '';
  if($bgType == 1) {
    $bgCSS = "background: transparent;";
  } else if($bgType == 2) {
    $bgCSS = "background-color: " . (isset($cssData['template_background_color']) ? esc_attr($cssData['template_background_color']) : 'rgba(255, 255, 255, 0)') . ";";
  } else if($bgType == 3) {
    $bgCSS = "background-image: url('" . (isset($cssData['box_background_image']) ? esc_attr($cssData['box_background_image']) : 'rgba(255, 255, 255, 0)') . "');";
  }

  $bgColor = '';
  if($bgType == 3){
    $bgColor = "background-color: ".esc_attr($cssData['image_opacity']).";";
  } else if($bgType == 4) {
    $bgColor = "background-color: ".esc_attr($cssData['video_opacity_color']).";";
  }

  $cssCode = "
  .sbs-6310-template-".esc_attr($templateId)."-parallax { 
    {$bgCSS}
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    position: relative;
    overflow: hidden;
    flex: 1;
  }
  .sbs-6310-template-".esc_attr($templateId)."-common-overlay {
    float: left;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 1;
    $bgColor
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: stretch;
  }
  .sbs-6310-template-".esc_attr($templateId)."-common-overlay iframe {
    position: absolute !important;
    top: -100%;
    left: 0;
    width: 100%;
    height: 300%;
    pointer-events: none;
    opacity: ".esc_attr($cssData['video_opacity']).";
    position: relative;
  }

  .sbs-6310-template-".esc_attr($templateId)."-title {  
    float: left;
    width: 100%; 
    font-family: {$titleFontFamily};   
    color: ".esc_attr($cssData['sbs_6310_title_font_color']).";
    line-height: ".esc_attr($cssData['sbs_6310_title_line_height'])."px;
    font-size:".esc_attr($cssData['sbs_6310_title_font_size'])."px;
    font-weight: ".esc_attr($cssData['sbs_6310_title_font_weight']).";
    text-transform: ".esc_attr($cssData['sbs_6310_title_text_transform']).";
    text-align: ".esc_attr($cssData['sbs_6310_title_text_align']).";  
    padding-top: ".esc_attr($cssData['sbs_6310_title_padding_top'])."px;
    padding-bottom: ".esc_attr($cssData['sbs_6310_title_padding_bottom'])."px; 
  }
  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-title {
    color: ". (isset($cssData['sbs_6310_title_font_hover_color']) ? $cssData['sbs_6310_title_font_hover_color'] : $cssData['sbs_6310_title_font_color']) .";
   
  }
  .sbs-6310-template-".esc_attr($templateId)."-description {
    float: left;
    width:100%;
    font-size: ".esc_attr($cssData['sbs_6310_details_font_size'])."px;
    font-weight: ".esc_attr($cssData['sbs_6310_details_font_weight']).";
    line-height: ".esc_attr($cssData['sbs_6310_details_line_height'])."px;
    color:".esc_attr($cssData['sbs_6310_details_font_color']).";  
    font-family: {$detailFontFamily};
    text-align: ".esc_attr($cssData['sbs_6310_details_text_align']).";
    text-transform: ".esc_attr($cssData['sbs_6310_details_text_transform']).";
    margin-top: ".esc_attr($cssData['sbs_6310_details_margin_top'])."px;
    margin-bottom: ".esc_attr($cssData['sbs_6310_details_margin_bottom'])."px;      
  }

  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-template-".esc_attr($templateId)."-description {
    color: ".esc_attr($cssData['sbs_6310_details_font_hover_color']).";
  }

  .sbs-6310-template-".esc_attr($templateId)."-read-more {
    float: left;
    width: 100%;
    text-align: ".esc_attr($cssData['sbs_6310_read_more_text_align']).";   
    margin-top: ".esc_attr($cssData['sbs_6310_read_more_margin_top'])."px ;
    margin-bottom:".esc_attr($cssData['sbs_6310_read_more_margin_bottom'])."px;
  }
  .sbs-6310-template-".esc_attr($templateId)."-read-more a {
    display: inline-block;
    text-decoration: none;
    transition: .5s;
    text-align:center;
    box-sizing: content-box !important;
    font-family: $readMoreFontFamily;
    width: ".esc_attr($cssData['sbs_6310_read_more_width'])."px;
    line-height: ".esc_attr($cssData['sbs_6310_read_more_height'])."px;
    height: ".esc_attr($cssData['sbs_6310_read_more_height'])."px;
    font-size: ".esc_attr($cssData['sbs_6310_read_more_font_size'])."px;   
    background: ".esc_attr($cssData['sbs_6310_read_more_background_color'])."; 
    color: ".esc_attr($cssData['sbs_6310_read_more_font_color']).";   
    border: ".esc_attr($cssData['sbs_6310_read_more_border_width'])." solid ".esc_attr($cssData['sbs_6310_read_more_box_border_color']).";
    border-radius: ".esc_attr($cssData['sbs_6310_read_more_border_radius'])."px;
    font-weight: ".esc_attr($cssData['sbs_6310_read_more_font_weight']).";
    text-transform: ".esc_attr($cssData['sbs_6310_read_more_text_transform']).";
  }
  .sbs-6310-template-".esc_attr($templateId)."-read-more a:hover{
    color: ".esc_attr($cssData['sbs_6310_read_more_font_hover_color']).";
    background: ".esc_attr($cssData['sbs_6310_read_more_background_hover_color'])."; 
    border: ".esc_attr($cssData['sbs_6310_read_more_border_width'])." solid ".esc_attr($cssData['sbs_6310_read_more_border_hover_color']).";
  }
  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-icon, 
  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-image {
    display: none !important; 
  }
  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-hover-icon, 
  .sbs-6310-template-".esc_attr($templateId).":hover .sbs-6310-hover-image {
    display: block !important; 
  }

  /*########################### Slider Start ###########################*/  
  .sbs-6310-carousel-".esc_attr($templateId)." .sbs-6310-owl-nav div {
    position: absolute;
    top: calc(50% - 35px);
    background: ".esc_attr($cssData['slider_prev_next_bgcolor']).";
    color: ".esc_attr($cssData['slider_prev_next_color']).";
    margin: 0;
    transition: all 0.3s ease-in-out;
    font-size: ".esc_attr($cssData['slider_prev_next_icon_size'])."px;
    line-height: ". ((int) esc_attr($cssData['slider_prev_next_icon_size'])  + 15) ."px;
    height: ". ((int) esc_attr($cssData['slider_prev_next_icon_size']) + 15) ."px;
    width: ". ((int) esc_attr($cssData['slider_prev_next_icon_size']) + 15) ."px;
    text-align: center;
    padding: 0;
  }

  .sbs-6310-carousel-".esc_attr($templateId)." .sbs-6310-owl-nav div:hover {
      background: ".esc_attr($cssData['slider_prev_next_hover_bgcolor']).";
      color: ".esc_attr($cssData['slider_prev_next_hover_color']).";
  }

  .sbs-6310-carousel-".esc_attr($templateId)." .sbs-6310-owl-nav div.sbs-6310-owl-prev {
      left: ".esc_attr($cssData['item_margin'])."px;
      border-radius: 0 ".esc_attr($cssData['slider_prev_next_icon_border_radius'])."% ".esc_attr($cssData['slider_prev_next_icon_border_radius'])."% 0;
  }

  .sbs-6310-carousel-".esc_attr($templateId)." .sbs-6310-owl-nav div.sbs-6310-owl-next {
      right: ".esc_attr($cssData['item_margin'])."px;
      border-radius: ".esc_attr($cssData['slider_prev_next_icon_border_radius'])."% 0 0 ".esc_attr($cssData['slider_prev_next_icon_border_radius'])."%;
  }

  .sbs-6310-carousel-".esc_attr($templateId)." .sbs-6310-owl-dots {
      text-align: center;
      padding-top: 15px;
  }

  .sbs-6310-carousel-".esc_attr($templateId)." .sbs-6310-owl-dots div {
      width: ".esc_attr($cssData['slider_indicator_width'])."px;
      height: ".esc_attr($cssData['slider_indicator_height'])."px;
      border-radius: ".esc_attr($cssData['slider_indicator_border_radius'])."%;
      display: inline-block;
      background-color: ".esc_attr($cssData['slider_indicator_color']).";
      margin: 0 ".esc_attr($cssData['slider_indicator_margin'])."px;
  }

  .sbs-6310-carousel-".esc_attr($templateId)." .sbs-6310-owl-dots div.active {
      background-color: ".esc_attr($cssData['slider_indicator_active_color']).";
  }
  .sbs-6310-owl-stage-outer { 
    overflow: visible !important;
  }
  .sbs-6310-owl-item {
      opacity: 0 !important;
      transition: opacity 500ms !important;
  }
  .sbs-6310-owl-item.active {
    opacity: 1 !important;
  }
  /*########################### Slider End ###########################*/  
  /*########################### Search Start ###########################*/  
  .sbs-6310-search-".esc_attr($templateId)." {
    display: flex;
    justify-content: ".((isset($cssData['search_align']) && $cssData['search_align'] !== '') ? $cssData['search_align'] : 'flex-start').";
    margin: 0;
    width: 100% !important;
  }

  .sbs-6310-search-template-".esc_attr($templateId)." {
    display: ". ((isset($cssData['search_activation']) && $cssData['search_activation']) ? 'flex' : 'none') .";
    position: relative;
    width: calc(33% - 15px);
    margin-bottom: ". ((isset($cssData['sbs_6310_search_margin_bottom']) && $cssData['sbs_6310_search_margin_bottom'] !== '') ? $cssData['sbs_6310_search_margin_bottom'] : 10) ."px;
  }
  .sbs-6310-search-template-".esc_attr($templateId)." input {
    width: 100% !important;
    border: ". ((isset($cssData['search_border_width']) && $cssData['search_border_width'] !== '') ? esc_attr($cssData['search_border_width']) : '2') ."px solid ". ((isset($cssData['search_border_color']) && $cssData['search_border_color'] !== '') ? esc_attr($cssData['search_border_color']) : '#000') .";
    border-radius: ". ((isset($cssData['search_border_radius']) && $cssData['search_border_radius'] !== '') ? esc_attr($cssData['search_border_radius']) : '50') ."px;
    padding: 5px 40px 5px 12px;
    color: ". ((isset($cssData['search_font_color']) && $cssData['search_font_color'] !== '') ? esc_attr($cssData['search_font_color']) : '#000') .";
    height: ". ((isset($cssData['search_height']) && $cssData['search_height'] !== '') ? esc_attr($cssData['search_height']) : '40') ."px;
    line-height: ". ((isset($cssData['search_height']) && $cssData['search_height'] !== '') ? esc_attr($cssData['search_height']) : '40') ."px;
    font-size: 15px;
    transition: all 0.3s;
  }
  .sbs-6310-search-template-".esc_attr($templateId)." input::placeholder {
    color: ". ((isset($cssData['search_placeholder_font_color']) && $cssData['search_placeholder_font_color'] !== '') ? esc_attr($cssData['search_placeholder_font_color']) : 'rgb(128, 128, 128)') .";
  }
  .sbs-6310-search-template-".esc_attr($templateId)." input:focus {
    outline: none !important;
    box-shadow: none !important;
    border-color: ". ((isset($cssData['search_border_color']) && $cssData['search_border_color'] !== '') ? esc_attr($cssData['search_border_color']) : '#000') .";
  }
  .sbs-6310-search-template-".esc_attr($templateId)." i.search-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 14px;
    color: ". ((isset($cssData['search_border_color']) && $cssData['search_border_color'] !== '') ? esc_attr($cssData['search_border_color']) : '#000') .";
  }
  @media screen and (max-width: 767px){
    .sbs-6310-search-template-".esc_attr($templateId)." {
      width: 100%;
    }
  }
  /*########################### Search End ###########################*/  

  .sbs-6310-col-2, .sbs-6310-col-3, .sbs-6310-col-4, .sbs-6310-col-5, .sbs-6310-col-6 {
    margin-left: ". (isset($cssData['item_margin'])?esc_attr($cssData['item_margin']):15) ."px !important;
    margin-right: ". (isset($cssData['item_margin'])?esc_attr($cssData['item_margin']):15) ."px !important;
  }
    .sbs-6310-col-2 {
     width: calc(50% - ". ((isset($cssData['item_margin'])?esc_attr($cssData['item_margin']):15) * 2) ."px) !important;
  }
  .sbs-6310-col-3 {
     width: calc(33.33% - ". ((isset($cssData['item_margin'])?esc_attr($cssData['item_margin']):15) * 2) ."px) !important;
  }
  .sbs-6310-col-4 {
     width: calc(25% - ". ((isset($cssData['item_margin'])?esc_attr($cssData['item_margin']):15) * 2) ."px) !important;
  }
  .sbs-6310-col-5 {
     width: calc(20% - ". ((isset($cssData['item_margin'])?esc_attr($cssData['item_margin']):15) * 2) ."px) !important;
  }
  .sbs-6310-col-6 {
     width: calc(16.6667% - ". ((isset($cssData['item_margin'])?esc_attr($cssData['item_margin']):15) * 2) ."px) !important;
  }
  @media screen and (max-width: 1023px) {
    .sbs-6310-noslider-".esc_attr($templateId)." .sbs-6310-col-2, 
    .sbs-6310-noslider-".esc_attr($templateId)." .sbs-6310-col-3, 
    .sbs-6310-noslider-".esc_attr($templateId)." .sbs-6310-col-4, 
    .sbs-6310-noslider-".esc_attr($templateId)." .sbs-6310-col-5, 
    .sbs-6310-noslider-".esc_attr($templateId)." .sbs-6310-col-6{
      width: ". (($tablet_row > 1) ? "calc(" . (100/$tablet_row) . "% - " . ($cssData['item_margin'] * 2) . "px)" : "100%") ." !important;
    }
  }
  @media screen and (max-width: 767px) {
    .sbs-6310-noslider-".esc_attr($templateId)." .sbs-6310-col-2, 
    .sbs-6310-noslider-".esc_attr($templateId)." .sbs-6310-col-3, 
    .sbs-6310-noslider-".esc_attr($templateId)." .sbs-6310-col-4, 
    .sbs-6310-noslider-".esc_attr($templateId)." .sbs-6310-col-5, 
    .sbs-6310-noslider-".esc_attr($templateId)." .sbs-6310-col-6{
      width: ". (($mobile_row > 1) ? "calc(" . (100/$mobile_row) . "% - " . ($cssData['item_margin'] * 2) . "px)" : "100%") ." !important;
    
    }
  }
  ";


  $cssCode .= "
    .sbs-6310-template-".esc_attr($templateId)."-read-more{
      display: " . (isset($cssData['sbs_6310_fun_template_button']) ? 'block' : 'none') . ";
    }
  ";
  $cssCode .= "
    .sbs-6310-template-".esc_attr($templateId)."-description{
      display: " . (isset($cssData['template_details_show_hide']) ? 'block' : 'none') . ";
    }
  ";



  $num = rand(1000, 9999);
  wp_register_style( "sbs-6310-template-{$num}-css", "" );
  wp_enqueue_style( "sbs-6310-template-{$num}-css" );
  wp_add_inline_style( "sbs-6310-template-{$num}-css", $cssCode );
?>