<?php
	
global $DIRECTORYPRESS_ADIMN_SETTINGS, $heading_font_family;

$directorypress_primary_color = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_primary_color'];
$directorypress_secondary_color = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_secondary_color'];

DirectoryPress_Static_Files::addGlobalStyle("

.listing-widget-hover-overlay{
	background: {$directorypress_secondary_color};
}
.listing-post-style-11 figure .price .field-content{
	background-color:{$directorypress_primary_color};
}
.listing-post-style-13 figure .price .field-content{
	background-color:{$directorypress_primary_color};
}
");

if(is_rtl()){
DirectoryPress_Static_Files::addGlobalStyle("
		.listing-post-style-13 figure .price .field-content:after{
			border-bottom-color:{$directorypress_primary_color};
			border-right-color:{$directorypress_primary_color};
			border-top-color:{$directorypress_primary_color};
		}
	");
}else{
	DirectoryPress_Static_Files::addGlobalStyle("
		.listing-post-style-13 figure .price .field-content:after{
			border-bottom-color:{$directorypress_primary_color};
			border-left-color:{$directorypress_primary_color};
			border-top-color:{$directorypress_primary_color};
		}
");
}

DirectoryPress_Static_Files::addGlobalStyle("
.listing-post-style-13 .cat-wrapper .listing-cat{
	color:{$directorypress_primary_color} !important;
}
.location-style3.directorypress-locations-columns .directorypress-location-item .directorypress-parent-location a .location-icon,
.location-style8.directorypress-locations-columns .directorypress-location-item .directorypress-parent-location a .location-icon{
	background-color:{$directorypress_primary_color};
}
.location-style3.directorypress-locations-columns .directorypress-location-item .directorypress-parent-location a:hover,
.location-style8.directorypress-locations-columns .directorypress-location-item .directorypress-parent-location a:hover{
	color:{$directorypress_primary_color};
}
.location-style-default.directorypress-locations-columns .directorypress-location-item  .directorypress-parent-location a:hover{
	color:{$directorypress_primary_color};
	border-color:{$directorypress_primary_color};
}
.location-style-default.directorypress-locations-columns .directorypress-location-item  .directorypress-parent-location a:hover .location-icon{
	color:{$directorypress_primary_color};
}
.cat-style-6 .directorypress-categories-wrapper .directorypress-category-holder .subcategories ul li.view-all-btn-wrap a:hover{
	
	background-color:{$directorypress_primary_color};
}

.listing-main-content .directorypress-field-item .field-label .directorypress-field-title,
.directorypress-fields-group-caption,
.directorypress-video-field-name{
	
	
}
.single-listing  .directorypress-field-type-checkbox .field-content li:before{
	color: {$directorypress_primary_color} ;
}

.ui-widget-header, .ui-slider-horizontal {
    background:{$directorypress_secondary_color} ;
    
}
.ui-slider .ui-slider-handle{
	border-color: {$directorypress_primary_color} ;
	background:#fff;
}
");

###########################################
# SEARCH FORM
###########################################


/* main search box */

$main_searchbar_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['main_searchbar_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['main_searchbar_bg']['color'])) ? ('background-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['main_searchbar_bg']['rgba'].';') : '';

$search_box_padding_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_padding']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_padding']['padding-top'])) ? ('padding-top:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_padding']['padding-top'].';') : '';
$search_box_padding_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_padding']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_padding']['padding-bottom'])) ? ('padding-bottom:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_padding']['padding-bottom'].';') : '';
$search_box_padding_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_padding']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_padding']['padding-left'])) ? ('padding-left:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_padding']['padding-left'].';') : '';
$search_box_padding_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_padding']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_padding']['padding-right'])) ? ('padding-right:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_padding']['padding-right'].';') : '';

$search_box_border_radius_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_border_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_border_radius']['padding-top'])) ? ('border-top-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_border_radius']['padding-top'].';') : '';
$search_box_border_radius_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_border_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_border_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_border_radius']['padding-bottom'].';') : '';
$search_box_border_radius_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_border_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_border_radius']['padding-left'])) ? ('border-bottom-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_border_radius']['padding-left'].';') : '';
$search_box_border_radius_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_border_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_border_radius']['padding-right'])) ? ('border-top-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_box_border_radius']['padding-right'].';') : '';

/* veritical form fields */

$vertical_search_form_box_border = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_form_box_border']['border-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_form_box_border']['border-top'])) ? ('border-top-width:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_form_box_border']['border-top'].';') : '';
$vertical_search_form_box_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_form_box_border']['border-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_form_box_border']['border-bottom'])) ? ('border-bottom-width:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_form_box_border']['border-bottom'].';') : '';
$vertical_search_form_box_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_form_box_border']['border-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_form_box_border']['border-left'])) ? ('border-left-width:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_form_box_border']['border-left'].';') : '';
$vertical_search_form_box_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_form_box_border']['border-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_form_box_border']['border-right'])) ? ('border-right-width:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_form_box_border']['border-right'].';') : '';
$vertical_search_form_box_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_form_box_border']['border-style']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_form_box_border']['border-style'])) ? ('border-style:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_form_box_border']['border-style'].';') : '';
$vertical_search_form_box_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_form_box_border']['border-color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_form_box_border']['border-color'])) ? ('border-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_form_box_border']['border-color'].';') : '';

$vertical_form_field_margin_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_field_margin_bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_field_margin_bottom'])) ? ('margin-bottom:'. $DIRECTORYPRESS_ADIMN_SETTINGS['vertical_field_margin_bottom'].'px;') : '';

$vertical_default_field_padding = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_default_field_padding']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_default_field_padding']['padding-top'])) ? ('padding-top:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_default_field_padding']['padding-top'].';') : '';
$vertical_default_field_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_default_field_padding']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_default_field_padding']['padding-bottom'])) ? ('padding-bottom:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_default_field_padding']['padding-bottom'].';') : '';
$vertical_default_field_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_default_field_padding']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_default_field_padding']['padding-left'])) ? ('padding-left:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_default_field_padding']['padding-left'].';') : '';
$vertical_default_field_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_default_field_padding']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_default_field_padding']['padding-right'])) ? ('padding-right:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_default_field_padding']['padding-right'].';') : '';

$vertical_field_box_radius = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_field_box_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_field_box_radius']['padding-top'])) ? ('border-top-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_field_box_radius']['padding-top'].';') : '';
$vertical_field_box_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_field_box_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_field_box_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_field_box_radius']['padding-bottom'].';') : '';
$vertical_field_box_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_field_box_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_field_box_radius']['padding-left'])) ? ('border-bottom-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_field_box_radius']['padding-left'].';') : '';
$vertical_field_box_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_field_box_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_field_box_radius']['padding-right'])) ? ('border-top-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_field_box_radius']['padding-right'].';') : '';

$vertical_form_field_label_border = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_form_field_label_border']['border-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_form_field_label_border']['border-top'])) ? ('border-top-width:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_form_field_label_border']['border-top'].';') : '';
$vertical_form_field_label_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_form_field_label_border']['border-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_form_field_label_border']['border-bottom'])) ? ('border-bottom-width:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_form_field_label_border']['border-bottom'].';') : '';
$vertical_form_field_label_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_form_field_label_border']['border-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_form_field_label_border']['border-left'])) ? ('border-left-width:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_form_field_label_border']['border-left'].';') : '';
$vertical_form_field_label_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_form_field_label_border']['border-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_form_field_label_border']['border-right'])) ? ('border-right-width:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_form_field_label_border']['border-right'].';') : '';
$vertical_form_field_label_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_form_field_label_border']['border-style']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_form_field_label_border']['border-style'])) ? ('border-style:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_form_field_label_border']['border-style'].';') : '';
$vertical_form_field_label_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_form_field_label_border']['border-color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_form_field_label_border']['border-color'])) ? ('border-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_form_field_label_border']['border-color'].';') : '';

/*  fields */

$input_field_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_height'])) ? ('height:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_height'].'px;') : '';

$input_field_padding_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_padding']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_padding']['padding-top'])) ? ('padding-top:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_padding']['padding-top'].';') : '';
$input_field_padding_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_padding']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_padding']['padding-bottom'])) ? ('padding-bottom:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_padding']['padding-bottom'].';') : '';
$input_field_padding_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_padding']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_padding']['padding-left'])) ? ('padding-left:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_padding']['padding-left'].';') : '';
$input_field_padding_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_padding']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_padding']['padding-right'])) ? ('padding-right:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_padding']['padding-right'].';') : '';

$input_field_content_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_content_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_content_bg']['color'])) ? ('background-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_content_bg']['rgba'].';') : '';

$select_selector_line_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_height'])) ? ('line-height:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_height'].'px;') : '';
$input_field_height_min = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_height'])) ? ('min-height:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_height'].'px;') : '';

$input_field_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_box_input_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_box_input_bg']['color'])) ? ('background-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_box_input_bg']['rgba'].';') : '';

//$input_field_border_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_box_input_border']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_box_input_border']['color'])) ? ('border-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_box_input_border']['rgba'].';') : '';
$input_field_placeholder_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_box_input_placeholer_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_box_input_placeholer_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_box_input_placeholer_color'])) ? ('color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_box_input_placeholer_color'].';') : '';
$input_field_text_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_box_input_text_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_box_input_text_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_box_input_text_color'])) ? ('color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_box_input_text_color'].';') : '';
$input_field_label_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_box_input_label_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_box_input_label_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_box_input_label_color'])) ? ('color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_box_input_label_color'].';') : '';

//$input_field_border = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width'])) ? ('border-width:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width'].'px;') : '';
$input_field_border = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width']['border-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width']['border-top'])) ? ('border-top-width:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width']['border-top'].';') : '';
$input_field_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width']['border-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width']['border-bottom'])) ? ('border-bottom-width:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width']['border-bottom'].';') : '';
$input_field_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width']['border-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width']['border-left'])) ? ('border-left-width:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width']['border-left'].';') : '';
$input_field_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width']['border-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width']['border-right'])) ? ('border-right-width:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width']['border-right'].';') : '';
$input_field_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width']['border-style']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width']['border-style'])) ? ('border-style:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width']['border-style'].';') : '';
$input_field_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width']['border-color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width']['border-color'])) ? ('border-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_border_width']['border-color'].';') : '';

$input_field_border_radius = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_radius']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_radius'])) ? ('border-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_radius'].'px;') : '';

$input_field_label_padding_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label_padding']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label_padding']['padding-top'])) ? ('padding-top:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label_padding']['padding-top'].';') : '';
$input_field_label_padding_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label_padding']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label_padding']['padding-bottom'])) ? ('padding-bottom:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label_padding']['padding-bottom'].';') : '';
$input_field_label_padding_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label_padding']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label_padding']['padding-left'])) ? ('padding-left:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label_padding']['padding-left'].';') : '';
$input_field_label_padding_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label_padding']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label_padding']['padding-right'])) ? ('padding-right:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label_padding']['padding-right'].';') : '';

$search_field_font_family = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_text']['font-family']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_text']['font-family'])) ? ('font-family:' . $DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_text']['font-family'] . ';') : '';
$search_field_font_size = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_text']['font-size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_text']['font-size'])) ? ('font-size:' . $DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_text']['font-size'] . ';') : '';
$search_field_font_weight = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_text']['font-weight']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_text']['font-weight'])) ? ('font-weight:' . $DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_text']['font-weight'] . ';') : '';
$search_field_font_line_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_text']['line-height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_text']['line-height'])) ? ('line-height:' . $DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_text']['line-height'] . ';') : '';
$search_field_font_transform = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_field_text_transform']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_field_text_transform'])) ? ('text-transform: ' . $DIRECTORYPRESS_ADIMN_SETTINGS['search_field_text_transform'] . ';') : '';

$search_field_label_font_family = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label']['font-family']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label']['font-family'])) ? ('font-family:' . $DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label']['font-family'] . ';') : '';
$search_field_label_font_size = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label']['font-size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label']['font-size'])) ? ('font-size:' . $DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label']['font-size'] . ';') : '';
$search_field_label_font_weight = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label']['font-weight']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label']['font-weight'])) ? ('font-weight:' . $DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label']['font-weight'] . ';') : '';
$search_field_label_font_line_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label']['line-height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label']['line-height'])) ? ('line-height:' . $DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label']['line-height'] . ';') : '';
$search_field_label_font_transform = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_field_label_transform']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_field_label_transform'])) ? ('text-transform: ' . $DIRECTORYPRESS_ADIMN_SETTINGS['search_field_label_transform'] . ';') : '';

$search_field_label_background = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label_bg']['color'])) ? ('background-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_field_label_bg']['rgba'].';') : '';


/* search button */

$search_button_typo = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_text']['font-family']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_text']['font-family'])) ? ('font-family:' . $DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_text']['font-family'] . ';') : '';
$search_button_typo .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_text']['font-size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_text']['font-size'])) ? ('font-size:' . $DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_text']['font-size'] . ';') : '';
$search_button_typo .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_text']['font-weight']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_text']['font-weight'])) ? ('font-weight:' . $DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_text']['font-weight'] . ';') : '';
$search_button_typo .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_text']['line-height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_text']['line-height'])) ? ('line-height:' . $DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_text']['line-height'] . ';') : '';
$search_button_typo .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_button_text_transform']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_button_text_transform'])) ? ('text-transform: ' . $DIRECTORYPRESS_ADIMN_SETTINGS['search_button_text_transform'] . ';') : '';

$search_form_button_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_height'])) ? ('height:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_height'].'px;') : '';

$search_button_border_radius = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_border_radius']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_border_radius'])) ? ('border-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_border_radius'].'px;') : '';

$search_button_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_btn_color_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_btn_color_bg']['color'])) ? ('background-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_btn_color_bg']['rgba'].';') : '';
$search_button_bg_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_btn_color_bg_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_btn_color_bg_hover']['color'])) ? ('background-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_btn_color_bg_hover']['rgba'].';') : '';
$search_button_border_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_btn_border_color']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_btn_border_color']['color'])) ? ('border-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_btn_border_color']['rgba'].';') : '';
$search_button_border_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_btn_border_color_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_btn_border_color_hover']['color'])) ? ('border-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_btn_border_color_hover']['rgba'].';') : '';

$search_button_border_width = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_border_width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_border_width'])) ? ('border-width:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_border_width'].'px;') : '';

$search_button_text_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_btn_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_btn_color']['regular'])) ? ('color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_btn_color']['regular']. ';') : '';
$search_button_text_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_btn_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_btn_color']['hover'])) ? ('color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['search_form_btn_color']['hover']. ';') : '';
$search_button_icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_icon']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_icon'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['search_form_button_icon'] : '';

$vertical_search_button_wrapper_padding = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_button_wrapper_padding']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_button_wrapper_padding']['padding-top'])) ? ('padding-top:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_button_wrapper_padding']['padding-top'].';') : '';
$vertical_search_button_wrapper_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_button_wrapper_padding']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_button_wrapper_padding']['padding-bottom'])) ? ('padding-bottom:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_button_wrapper_padding']['padding-bottom'].';') : '';
$vertical_search_button_wrapper_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_button_wrapper_padding']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_button_wrapper_padding']['padding-left'])) ? ('padding-left:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_button_wrapper_padding']['padding-left'].';') : '';
$vertical_search_button_wrapper_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_button_wrapper_padding']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_button_wrapper_padding']['padding-right'])) ? ('padding-right:'.$DIRECTORYPRESS_ADIMN_SETTINGS['vertical_search_button_wrapper_padding']['padding-right'].';') : '';

$search_button_padding = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_button_padding']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_button_padding']['padding-top'])) ? ('padding-top:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_button_padding']['padding-top'].';') : '';
$search_button_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_button_padding']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_button_padding']['padding-bottom'])) ? ('padding-bottom:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_button_padding']['padding-bottom'].';') : '';
$search_button_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_button_padding']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_button_padding']['padding-left'])) ? ('padding-left:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_button_padding']['padding-left'].';') : '';
$search_button_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_button_padding']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_button_padding']['padding-right'])) ? ('padding-right:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_button_padding']['padding-right'].';') : '';


/* Radius Slider */

$search_radius_slider_border_radius_top_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_radius']['padding-top'])) ? ('border-top-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_radius']['padding-top'].';') : '';
$search_radius_slider_border_radius_top_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_radius']['padding-bottom'].';') : '';
$search_radius_slider_border_radius_bottom_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_radius']['padding-left'])) ? ('border-bottom-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_radius']['padding-left'].';') : '';
$search_radius_slider_border_radius_bottom_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_radius']['padding-right'])) ? ('border-top-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_radius']['padding-right'].';') : '';


$search_radius_slider_border_width = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_border_width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_border_width'])) ? ('border-width:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_border_width']. 'px;') : '';
$search_radius_slider_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_height'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_height'] : '10';


$search_radius_slider_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_bg']['color'])) ? ('background-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_bg']['rgba'].' !important;') : '';
$search_radius_slider_border_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_border_color']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_border_color']['color'])) ? ('border-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_border_color']['rgba'].' !important;') : '';

$search_radius_slider_range_border_radius_top_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_radius']['padding-top'])) ? ('border-top-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_radius']['padding-top'].';') : '';
$search_radius_slider_range_border_radius_top_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_radius']['padding-bottom'].';') : '';
$search_radius_slider_range_border_radius_bottom_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_radius']['padding-left'])) ? ('border-bottom-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_radius']['padding-left'].';') : '';
$search_radius_slider_range_border_radius_bottom_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_radius']['padding-right'])) ? ('border-top-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_radius']['padding-right'].';') : '';

$search_radius_slider_range_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_height'])) ? ('height:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_height'].'px;') : '';
$search_radius_slider_range_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_top'])) ? ('top:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_top']. 'px;') : '';
$search_radius_slider_range_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_top'])) ? ('left:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_range_top']. 'px;') : '';
$search_radius_slider_range_width = (round($search_radius_slider_height));
$search_radius_slider_rage_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_rage_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_rage_bg']['color'])) ? ('background-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_rage_bg']['rgba'].' !important;') : '';

$search_radius_slider_handle_border_radius_top_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_radius']['padding-top'])) ? ('border-top-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_radius']['padding-top'].';') : '';
$search_radius_slider_handle_border_radius_top_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_radius']['padding-bottom'].';') : '';
$search_radius_slider_handle_border_radius_bottom_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_radius']['padding-left'])) ? ('border-bottom-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_radius']['padding-left'].';') : '';
$search_radius_slider_handle_border_radius_bottom_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_radius']['padding-right'])) ? ('border-top-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_radius']['padding-right'].';') : '';



$search_radius_slider_handle_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_top'])) ? ('top:-'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_top'].'px;') : '';
$search_radius_slider_handle_width = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_width'])) ? ('width:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_width'].'px;') : '';
$search_radius_slider_handle_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_width'])) ? ('height:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_width'].'px;') : '';
$search_radius_slider_handle_border_width = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_border_width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_border_width'])) ? ('border-width:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_border_width'].'px;') : '';

$search_radius_slider_barwidth = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_width'])) ? ('width: calc(100% - '.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_width'].'px); width: -webkit-calc(100% - '.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_width'].'px); width: -moz-calc(100% - '.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_width'].'px);') : '';
if(is_rtl()){
	$search_radius_slider_barmargin = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_width'])) ? ('margin-right:'.round($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_width'] / 2).'px;') : '';
}else{
	$search_radius_slider_barmargin = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_width'])) ? ('margin-left:'.round($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_width'] / 2).'px;') : '';
}
$search_radius_slider_handle_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_bg']['color'])) ? ('background-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_bg']['rgba'].';') : '';
$search_radius_slider_handle_border_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_border_color']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_border_color']['color'])) ? ('border-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_handle_border_color']['rgba'].';') : '';

$search_radius_slider_tooltip_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_top'])) ? ('top:-'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_top'].'px;') : '';
$search_radius_slider_tooltip_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_left'])) ? ('left:-'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_left'].'px;') : '';
$search_radius_slider_tooltip_width = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_width'])) ? ('width:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_width'].'px;') : '';

$search_radius_slider_tooltip_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_bg']['color'])) ? ('background-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_bg']['rgba'].';') : '';
$search_radius_slider_tooltip_border_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_border_color']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_border_color']['color'])) ? ('border-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_border_color']['rgba'].';') : '';
$search_radius_slider_tooltip_text_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_text_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_text_color'])) ? ('color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_text_color']. ';') : '';

$search_selectbox_selector_icon_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_selectbox_selector_icon_bg']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_selectbox_selector_icon_bg'])) ? ('background:' .$DIRECTORYPRESS_ADIMN_SETTINGS['search_selectbox_selector_icon_bg']. ';') : '';
$search_selectbox_selector_icon_border = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_selectbox_selector_icon_border']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_selectbox_selector_icon_border'])) ? ('border-color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['search_selectbox_selector_icon_border']. ';') : '';
$search_selectbox_selector_icon_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_selectbox_selector_icon_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_selectbox_selector_icon_color'])) ? ('color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['search_selectbox_selector_icon_color']. ';') : '';
$search_selectbox_selector_icon_border_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_selectbox_selector_icon_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_selectbox_selector_icon_color'])) ? ('border-color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['search_selectbox_selector_icon_color']. ' transparent transparent transparent;') : '';
$search_selectbox_selector_icon_border_color_open = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_selectbox_selector_icon_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_selectbox_selector_icon_color'])) ? ('border-color: transparent transparent ' .$DIRECTORYPRESS_ADIMN_SETTINGS['search_selectbox_selector_icon_color']. ' transparent;') : '';

$search_radius_slider_tooltip_border_top_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_bg']['color'])) ? ('border-top-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['search_radius_slider_tooltip_bg']['rgba'].';') : '';
$has_featured_text = esc_html__('Featured', 'DIRECTORYPRESS');

$search_advanced_fiter_button_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_advanced_fiter_button_bg']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_advanced_fiter_button_bg']))? $DIRECTORYPRESS_ADIMN_SETTINGS['search_advanced_fiter_button_bg']: $directorypress_primary_color;
$search_advanced_fiter_button_bg_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_advanced_fiter_button_bg_hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_advanced_fiter_button_bg_hover']))? $DIRECTORYPRESS_ADIMN_SETTINGS['search_advanced_fiter_button_bg_hover']: $directorypress_secondary_color;
$search_advanced_fiter_button_color_regular = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_advanced_fiter_button_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_advanced_fiter_button_color']['regular']))? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['search_advanced_fiter_button_color']['regular'].';'): '';
$search_advanced_fiter_button_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_advanced_fiter_button_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['search_advanced_fiter_button_color']['hover']))? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['search_advanced_fiter_button_color']['hover'].';'): '';

if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['vertical_field_box_shadow'])){
		
	$vertical_field_box_shadow = $DIRECTORYPRESS_ADIMN_SETTINGS['vertical_field_box_shadow']['drop-shadow'];
	$vertical_field_box_shadow_color = $vertical_field_box_shadow['color'];
	$vertical_field_box_shadow_horizontal = ($vertical_field_box_shadow['horizontal'] != 0)? $vertical_field_box_shadow['horizontal'] .'px' : 0;
	$vertical_field_box_shadow_vertical = ($vertical_field_box_shadow['vertical'] != 0)? $vertical_field_box_shadow['vertical'] .'px' : 0;
	$vertical_field_box_shadow_blur = ($vertical_field_box_shadow['blur'] != 0)? $vertical_field_box_shadow['blur'] .'px' : 0;
	$vertical_field_box_shadow_spread = ($vertical_field_box_shadow['spread'] != 0)? $vertical_field_box_shadow['spread'] .'px' : 0;
	if(!empty($vertical_field_box_shadow_color)){
		$vertical_field_box_shadow_css = $vertical_field_box_shadow_horizontal .' '. $vertical_field_box_shadow_vertical .' '. $vertical_field_box_shadow_blur .' '. $vertical_field_box_shadow_spread .' '. $vertical_field_box_shadow_color;
		DirectoryPress_Static_Files::addGlobalStyle("
			.directorypress-search-layout-vertical .directorypress-search-holder .search-element-col:not(.directorypress-search-input-field-wrap):not(.cz-areaalider):not(.directorypress-search-submit-button-wrap),
			.directorypress-search-layout-vertical .directorypress-search-holder .default-search-fields-wrapper{
				box-shadow: {$vertical_field_box_shadow_css};
				-webkit-box-shadow: {$vertical_field_box_shadow_css};
				-moz-box-shadow: {$vertical_field_box_shadow_css};
				-o-box-shadow: {$vertical_field_box_shadow_css};
			}
		");
	}
}

if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_field_box_shadow'])){
		
	$search_field_box_shadow = $DIRECTORYPRESS_ADIMN_SETTINGS['search_field_box_shadow']['drop-shadow'];
	$search_field_box_shadow_color = $search_field_box_shadow['color'];
	$search_field_box_shadow_horizontal = ($search_field_box_shadow['horizontal'] != 0)? $search_field_box_shadow['horizontal'] .'px' : 0;
	$search_field_box_shadow_vertical = ($search_field_box_shadow['vertical'] != 0)? $search_field_box_shadow['vertical'] .'px' : 0;
	$search_field_box_shadow_blur = ($search_field_box_shadow['blur'] != 0)? $search_field_box_shadow['blur'] .'px' : 0;
	$search_field_box_shadow_spread = ($search_field_box_shadow['spread'] != 0)? $search_field_box_shadow['spread'] .'px' : 0;
	if(!empty($search_field_box_shadow_color)){
		$search_field_box_shadow_css = $search_field_box_shadow_horizontal .' '. $search_field_box_shadow_vertical .' '. $search_field_box_shadow_blur .' '. $search_field_box_shadow_spread .' '. $search_field_box_shadow_color;
		DirectoryPress_Static_Files::addGlobalStyle("
			.directorypress-search-holder .form-control,
			.directorypress-search-holder .select2-selection--single,
			.directorypress-search-holder .select2-container--default .select2-selection--single{
				box-shadow: {$search_field_box_shadow_css};
				-webkit-box-shadow: {$search_field_box_shadow_css};
				-moz-box-shadow: {$search_field_box_shadow_css};
				-o-box-shadow: {$search_field_box_shadow_css};
			}
		");
	}
}

if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_button_box_shadow'])){
		
	$search_button_box_shadow = $DIRECTORYPRESS_ADIMN_SETTINGS['search_button_box_shadow']['drop-shadow'];
	$search_button_box_shadow_color = $search_button_box_shadow['color'];
	$search_button_box_shadow_horizontal = ($search_button_box_shadow['horizontal'] != 0)? $search_button_box_shadow['horizontal'] .'px' : 0;
	$search_button_box_shadow_vertical = ($search_button_box_shadow['vertical'] != 0)? $search_button_box_shadow['vertical'] .'px' : 0;
	$search_button_box_shadow_blur = ($search_button_box_shadow['blur'] != 0)? $search_button_box_shadow['blur'] .'px' : 0;
	$search_button_box_shadow_spread = ($search_button_box_shadow['spread'] != 0)? $search_button_box_shadow['spread'] .'px' : 0;
	if(!empty($search_button_box_shadow_color)){
		$search_button_box_shadow_css = $search_button_box_shadow_horizontal .' '. $search_button_box_shadow_vertical .' '. $search_button_box_shadow_blur .' '. $search_button_box_shadow_spread .' '. $search_button_box_shadow_color;
		DirectoryPress_Static_Files::addGlobalStyle("
			.directorypress-search-holder .directorypress-search-form-button button{
				box-shadow: {$search_button_box_shadow_css};
				-webkit-box-shadow: {$search_button_box_shadow_css};
				-moz-box-shadow: {$search_button_box_shadow_css};
				-o-box-shadow: {$search_button_box_shadow_css};
			}
		");
	}
}
if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['search_button_box_shadow_hover'])){
		
	$search_button_box_shadow_hover = $DIRECTORYPRESS_ADIMN_SETTINGS['search_button_box_shadow_hover']['drop-shadow'];
	$search_button_box_shadow_hover_color = $search_button_box_shadow_hover['color'];
	$search_button_box_shadow_hover_horizontal = ($search_button_box_shadow_hover['horizontal'] != 0)? $search_button_box_shadow_hover['horizontal'] .'px' : 0;
	$search_button_box_shadow_hover_vertical = ($search_button_box_shadow_hover['vertical'] != 0)? $search_button_box_shadow_hover['vertical'] .'px' : 0;
	$search_button_box_shadow_hover_blur = ($search_button_box_shadow_hover['blur'] != 0)? $search_button_box_shadow_hover['blur'] .'px' : 0;
	$search_button_box_shadow_hover_spread = ($search_button_box_shadow_hover['spread'] != 0)? $search_button_box_shadow_hover['spread'] .'px' : 0;
	if(!empty($search_button_box_shadow_hover_color)){
		$search_button_box_shadow_hover_css = $search_button_box_shadow_hover_horizontal .' '. $search_button_box_shadow_hover_vertical .' '. $search_button_box_shadow_hover_blur .' '. $search_button_box_shadow_hover_spread .' '. $search_button_box_shadow_hover_color;
		DirectoryPress_Static_Files::addGlobalStyle("
			.directorypress-search-holder .directorypress-search-form-button button:hover{
				box-shadow: {$search_button_box_shadow_hover_css};
				-webkit-box-shadow: {$search_button_box_shadow_hover_css};
				-moz-box-shadow: {$search_button_box_shadow_hover_css};
				-o-box-shadow: {$search_button_box_shadow_hover_css};
			}
		");
	}
}

DirectoryPress_Static_Files::addGlobalStyle("
.directorypress-search-holder{
	{$search_box_padding_top}
	{$search_box_padding_bottom}
	{$search_box_padding_left}
	{$search_box_padding_right}
}
.directorypress-search-layout-vertical .directorypress-search-holder{
	{$vertical_search_form_box_border}
}

.directorypress-search-form,
.search-form-style1.directorypress-content-wrap.directorypress-search-form{
	{$main_searchbar_bg}
	{$search_box_border_radius_top}
	{$search_box_border_radius_right}
	{$search_box_border_radius_bottom}
	{$search_box_border_radius_left}
	
}
.directorypress-search-layout-vertical .directorypress-search-holder .search-element-col:not(.directorypress-search-input-field-wrap):not(.cz-areaalider):not(.directorypress-search-submit-button-wrap),
.directorypress-search-layout-vertical .directorypress-search-holder .default-search-fields-wrapper{
	
	{$vertical_form_field_margin_bottom}
	{$vertical_field_box_radius}
	overflow:hidden;
}

.directorypress-search-layout-vertical .directorypress-search-holder .search-element-col .directorypress-tax-dropdowns-wrap{
	{$vertical_default_field_padding}
}
.directorypress-search-layout-vertical .directorypress-search-holder .default-search-fields-content-box,
.directorypress-search-layout-vertical .directorypress-search-holder .search-element-col .field-input-wrapper,
.directorypress-search-layout-vertical .directorypress-search-holder .search-element-col .search-field-content-wrapper{
	{$input_field_content_bg}
	{$input_field_padding_top}
	{$input_field_padding_bottom}
	{$input_field_padding_right}
	{$input_field_padding_left}
}

.directorypress-search-holder .directorypress-search-form-button .btn.btn-primary,
.directorypress-search-holder .form-control,
.directorypress-search-holder .directorypress-autocomplete-dropmenubox-locations input,
.directorypress-search-holder .select2-container--default .select2-selection--single .select2-selection__arrow,
.directorypress-search-holder .select2-selection--single,
.search-form-style1 .select2-selection--single,
.directorypress-search-holder .select2-container--default .select2-selection--single .select2-selection__rendered {
    {$input_field_height}
	{$input_field_height_min}
	{$search_field_font_line_height}
	{$input_field_placeholder_color}
}
.directorypress-search-holder .directorypress-jquery-ui-slider{
	{$input_field_height}
	{$input_field_height_min}
}
.directorypress-search-holder .form-control,
.directorypress-search-holder .select2-selection--single,
.directorypress-search-holder .select2-container--default .select2-selection--single{
	{$input_field_bg}
	{$input_field_border}
	{$input_field_border_radius}
	{$input_field_text_color}
	{$search_field_font_size}
	{$search_field_font_weight}
	{$search_field_font_line_height}
	{$search_field_font_family}
	{$search_field_font_transform}
}

.directorypress-search-holder .form-control:focus{
	{$input_field_border}
	{$input_field_border_radius}
	{$input_field_text_color}
	{$search_field_font_size}
	{$search_field_font_weight}
	{$search_field_font_line_height}
	{$search_field_font_family}
	{$search_field_font_transform}
}
.default-search-fields-section-label label,
.directorypress-search-form .search-content-field-label label,
.directorypress-search-form .field-type-price label,
.directorypress-search-form .field-type-digit label,
.directorypress-search-form .directorypress-search-input-field-wrap label{
	{$input_field_label_color}
	{$search_field_label_font_size}
	{$search_field_label_font_weight}
	{$search_field_label_font_line_height}
	{$search_field_label_font_family}
	{$search_field_label_font_transform}
}
.default-search-fields-section-label label,
.directorypress-search-form.directorypress-search-layout-vertical .directorypress-search-radius-label,
.directorypress-search-form.directorypress-search-layout-vertical .search-content-field-label label,
.directorypress-search-form.directorypress-search-layout-vertical .field-type-price label,
.directorypress-search-form.directorypress-search-layout-vertical .field-type-digit label,
.directorypress-search-form.directorypress-search-layout-vertical .directorypress-search-input-field-wrap label{
	{$input_field_label_padding_top}
	{$input_field_label_padding_bottom}
	{$input_field_label_padding_right}
	{$input_field_label_padding_left}
	{$search_field_label_background}
	{$vertical_form_field_label_border}
}
.directorypress-search-holder .form-control::-moz-placeholder,
.directorypress-search-holder .form-control::placeholder{
	{$input_field_placeholder_color}
}
.directorypress-search-holder .directorypress-form-control-feedback,
.directorypress-search-holder .directorypress-tax-dropdowns-wrap select,
.directorypress-search-holder .select2-container--default .select2-selection--single .select2-selection__arrow {
    {$input_field_height}
	{$input_field_height_min}
	{$select_selector_line_height}
	{$search_selectbox_selector_icon_bg}
	{$search_selectbox_selector_icon_border}
	{$search_selectbox_selector_icon_color}

}
.directorypress-search-holder .select2-container--default .select2-selection--single .select2-selection__arrow {
	width:36px;
	text-align: center;
	right: 0;
	top: 0;
}
.rtl .directorypress-search-holder .select2-container--default .select2-selection--single .select2-selection__arrow {
	right: auto;
	left: 0;
}
.directorypress-search-holder .directorypress-form-control-feedback{
	width:36px;
	font-size:10px;
}
.directorypress-search-holder .select2-container--default .select2-selection--single .select2-selection__arrow b{
	margin-left:-3px;
	{$search_selectbox_selector_icon_border_color}
}
.directorypress-search-holder .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b{
	{$search_selectbox_selector_icon_border_color_open}
}
.directorypress-distance-slider .tooltip.top{
	opacity:1 !important;
	{$search_radius_slider_tooltip_top}
	{$search_radius_slider_tooltip_left}
}
.directorypress-distance-slider .tooltip.top .tooltip-arrow{
	{$search_radius_slider_tooltip_border_top_color}
}
.directorypress-distance-slider .tooltip.top .tooltip-inner{
	{$search_radius_slider_tooltip_bg}
	{$search_radius_slider_tooltip_text_color}
	{$search_radius_slider_tooltip_width}
	{$search_radius_slider_tooltip_border_color}
}
.ui-slider.ui-slider-horizontal{
	{$search_radius_slider_bg}
	{$search_radius_slider_border_color}
	{$search_radius_slider_border_radius_top_left}
	{$search_radius_slider_border_radius_top_right}
	{$search_radius_slider_border_radius_bottom_right}
	{$search_radius_slider_border_radius_bottom_left}
	{$search_radius_slider_border_width}
	{$search_radius_slider_barwidth}
	{$search_radius_slider_barmargin}
	height:{$search_radius_slider_height}px !important;
}
.ui-slider.ui-slider-horizontal .ui-slider-range{
	{$search_radius_slider_rage_bg}
	{$search_radius_slider_range_height}
	{$search_radius_slider_range_border_radius_top_left}
	{$search_radius_slider_range_border_radius_top_right}
	{$search_radius_slider_range_border_radius_bottom_right}
	{$search_radius_slider_range_border_radius_bottom_left}
	{$search_radius_slider_range_top};
	{$search_radius_slider_range_left}
}
.directorypress-search-holder .ui-slider .ui-slider-handle.ui-corner-all,
.directorypress-search-holder .ui-slider-handle.ui-corner-all.ui-state-focus {

    {$search_radius_slider_handle_height}
    {$search_radius_slider_handle_top}
    {$search_radius_slider_handle_width}
    {$search_radius_slider_handle_border_width}
	{$search_radius_slider_handle_bg}
	{$search_radius_slider_handle_border_color}
	{$search_radius_slider_handle_border_radius_top_left}
	{$search_radius_slider_handle_border_radius_top_right}
	{$search_radius_slider_handle_border_radius_bottom_right}
	{$search_radius_slider_handle_border_radius_bottom_left}

}
.directorypress-search-layout-vertical .directorypress-search-holder .directorypress-search-form-button{
	{$vertical_search_button_wrapper_padding}
}
.directorypress-search-holder .directorypress-search-form-button .btn.btn-primary{
	{$search_button_bg}
	{$search_form_button_height}
	{$search_button_border_color}
	{$search_button_border_width}
	{$search_button_border_radius}
	{$search_button_text_color}
	{$search_button_typo}
	{$search_button_padding}
	border-style: solid;
}
.directorypress-search-holder .btn.btn-primary:hover{
	{$search_button_bg_hover}
	{$search_button_border_color_hover}
	{$search_button_text_color_hover}
}
.search-checkbox input:checked ~ .search-checkbox-item{
	border-color: {$directorypress_primary_color} ;
	background-color: {$directorypress_primary_color} ;
	color:#fff;
}
.directorypress-advanced-search-label{
	background: {$search_advanced_fiter_button_bg};
	{$search_advanced_fiter_button_color_regular}
}
.directorypress-advanced-search-label:hover,
.directorypress-advanced-search-label.active{
	background: {$search_advanced_fiter_button_bg_hover};
	{$search_advanced_fiter_button_color_hover}
}
.directorypress-dropmenubox.ui-autocomplete{
	border-color:{$directorypress_primary_color};
}
.nicescroll-cursors {
	background-color:#ccc !important;
}
.nicescroll-rails {
		background-color:#eee !important;
}
");

###########################################
# Categories
###########################################

$cat_font_family = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['category_typo']['font-family']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['category_typo']['font-family'])) ? ('font-family:' . $DIRECTORYPRESS_ADIMN_SETTINGS['category_typo']['font-family'] . ';') : '';
$cat_font_size = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['category_typo']['font-size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['category_typo']['font-size'])) ? ('font-size:' . $DIRECTORYPRESS_ADIMN_SETTINGS['category_typo']['font-size'] . 'px;') : '';
$cat_font_weight = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['category_typo']['font-weight']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['category_typo']['font-weight'])) ? ('font-weight:' . $DIRECTORYPRESS_ADIMN_SETTINGS['category_typo']['font-weight'] . ';') : '';
$cat_font_line_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['category_typo']['line-heigh']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['category_typo']['line-heigh'])) ? ('line-heigh:' . $DIRECTORYPRESS_ADIMN_SETTINGS['category_typo']['line-heigh'] . 'px;') : '';
$cat_font_transform = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['category_typo_transform']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['category_typo_transform'])) ? ('text-transform: ' . $DIRECTORYPRESS_ADIMN_SETTINGS['category_typo_transform'] . ';') : '';

$child_cat_font_family = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['childcategory_typo']['font-family']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['childcategory_typo']['font-family'])) ? ('font-family:' . $DIRECTORYPRESS_ADIMN_SETTINGS['childcategory_typo']['font-family'] . ';') : '';
$child_cat_font_size = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['childcategory_typo']['font-size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['childcategory_typo']['font-size'])) ? ('font-size:' . $DIRECTORYPRESS_ADIMN_SETTINGS['childcategory_typo']['font-size'] . 'px;') : '';
$child_cat_font_weight = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['childcategory_typo']['font-weight']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['childcategory_typo']['font-weight'])) ? ('font-weight:' . $DIRECTORYPRESS_ADIMN_SETTINGS['childcategory_typo']['font-weight'] . ';') : '';
$child_cat_font_line_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['childcategory_typo']['line-heigh']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['childcategory_typo']['line-heigh'])) ? ('line-heigh:' . $DIRECTORYPRESS_ADIMN_SETTINGS['childcategory_typo']['line-heigh'] . 'px;') : '';
$child_cat_font_transform = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['category_typo_transform']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['category_typo_transform'])) ? ('text-transform: ' . $DIRECTORYPRESS_ADIMN_SETTINGS['category_typo_transform'] . ';') : '';

$parent_cat_title_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['regular'])) ? ('color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['regular']. ';') : '';
$parent_cat_title_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['hover'])) ?('color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['hover']. ';') : '';

$parent_cat_title_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['bg']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['bg'])) ? ('background:'.$DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['bg']. ';') : '';
$parent_cat_title_bg_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['bg-hover'])  && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['bg-hover'])) ? ('background:'.$DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['bg-hover']. ';') : '';

$subcategory_title_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['subcategory_title_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['subcategory_title_color']['regular'])) ? ('color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['subcategory_title_color']['regular']. ';') : '';
$subcategory_title_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['subcategory_title_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['subcategory_title_color']['hover'])) ? ('color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['subcategory_title_color']['hover']. ';') : '';

$cat_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_bg_color']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_bg_color']['color'])) ? ('background:'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_bg_color']['rgba'].';') : '';
$cat_bg_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_bg_color_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_bg_color_hover']['color'])) ? ('background:'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_bg_color_hover']['rgba'].';') : '';

$cat_border_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color']['rgba'])  && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color']['color'])) ? ('border-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color']['rgba']. ';') : '';
$cat_border_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color_hover']['rgba'])  && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color_hover']['color'])) ? ('border-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color_hover']['rgba']. ';') : '';

$cat_box_shadow = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color']['rgba'])  && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color']['color'])) ? ('box-shadow: 0 2px 0 0'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color']['rgba']. ';') : '';
$cat_box_shadow_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color_hover']['rgba'])  && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color_hover']['color'])) ? ('box-shadow: 0 2px 0 0'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color_hover']['rgba']. ';') : '';


$cat_border_radius_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-top'])) ? ('border-top-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-top'].';') : '';
$cat_border_radius_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-bottom'].';') : '';
$cat_border_radius_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-left'])) ? ('border-bottom-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-left'].';') : '';
$cat_border_radius_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-right'])) ? ('border-top-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-right'].';') : '';



DirectoryPress_Static_Files::addGlobalStyle("

	.directorypress-parent-category a{
		{$parent_cat_title_color}
		{$cat_font_size}
		{$cat_font_weight}
		{$cat_font_line_height}
		{$cat_font_family};
		{$cat_font_transform}
	}
	.directorypress-parent-category a:hover{
		{$parent_cat_title_color_hover}
	}
	.subcategories ul li a,
	.subcategories ul li a span{
		{$subcategory_title_color}
		{$child_cat_font_size}
		{$child_cat_font_weight};
		{$child_cat_font_line_height}
		{$child_cat_font_family}
		{$child_cat_font_transform}
	}
	.subcategories ul li a:hover,
	.subcategories ul li a:hover span{
		{$subcategory_title_color_hover}
	}

");

###########################################
# Locations
###########################################

$loc_font_family = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loccation_typo']['font-family']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loccation_typo']['font-family'])) ? ('font-family:' . $DIRECTORYPRESS_ADIMN_SETTINGS['loccation_typo']['font-family'] . ';') : '';
$loc_font_size = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loccation_typo']['font-size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loccation_typo']['font-size'])) ? ('font-size:' . $DIRECTORYPRESS_ADIMN_SETTINGS['loccation_typo']['font-size'] . ';') : '';
$loc_font_weight = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loccation_typo']['font-weight']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loccation_typo']['font-weight'])) ? ('font-weight:' . $DIRECTORYPRESS_ADIMN_SETTINGS['loccation_typo']['font-weight'] . ';') : '';
$loc_font_line_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loccation_typo']['line-height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loccation_typo']['line-height'])) ? ('line-height:' . $DIRECTORYPRESS_ADIMN_SETTINGS['loccation_typo']['line-height'] . ';') : '';

$loc_font_transform = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loccation_typo_transform']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loccation_typo_transform'])) ? ('text-transform: ' . $DIRECTORYPRESS_ADIMN_SETTINGS['loccation_typo_transform'] . ';') : '';

$child_loc_font_family = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['childlocation_typo']['font-family']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['childlocation_typo']['font-family'])) ? ('font-family:' . $DIRECTORYPRESS_ADIMN_SETTINGS['childlocation_typo']['font-family'] . ';') : '';
$child_loc_font_size = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['childlocation_typo']['font-size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['childlocation_typo']['font-size'])) ? ('font-size:' . $DIRECTORYPRESS_ADIMN_SETTINGS['childlocation_typo']['font-size'] . ';') : '';
$child_loc_font_weight = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['childlocation_typo']['font-weight']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['childlocation_typo']['font-weight'])) ? ('font-weight:' . $DIRECTORYPRESS_ADIMN_SETTINGS['childlocation_typo']['font-weight'] . ';') : '';
$child_loc_font_line_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['childlocation_typo']['line-height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['childlocation_typo']['line-heigh'])) ? ('line-height:' . $DIRECTORYPRESS_ADIMN_SETTINGS['childlocation_typo']['line-height'] . ';') : '';
$child_loc_font_transform = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['childlocation_typo_transform']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['childlocation_typo_transform'])) ? ('text-transform: ' . $DIRECTORYPRESS_ADIMN_SETTINGS['childlocation_typo_transform'] . ';') : '';

$parent_loc_title_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_title_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_title_color']['regular'])) ? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_title_color']['regular'] .';') : '';
$parent_loc_title_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_title_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_title_color']['hover'])) ? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_title_color']['hover'] .';') : '';

$parent_loc_title_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_title_color']['bg']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_title_color']['bg'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_title_color']['bg'] : '';
$parent_loc_title_bg_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_title_color']['bg-hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_title_color']['bg-hover'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_title_color']['bg-hover'] : '';

$sublocation_title_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['subloc_title_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['subloc_title_color']['regular'])) ? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['subloc_title_color']['regular'] .';') : '';
$sublocation_title_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['subloc_title_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['subloc_title_color']['hover'])) ? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['subloc_title_color']['hover'] .';') : '';


$loc_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loc_bg_color']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loc_bg_color']['color'])) ? ('background:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loc_bg_color']['rgba'] .';') : '';
$loc_bg_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loc_bg_color_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loc_bg_color_hover']['color'])) ? ('background:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loc_bg_color_hover']['rgba'] .';') : '';

$parent_loc_icon_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_icon_bg']['rgba'])  && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_icon_bg']['color'])) ? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_icon_bg']['rgba'] .';') : '';
$parent_loc_icon_bg_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_icon_bg_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_icon_bg_hover']['color'])) ? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_icon_bg_hover']['rgba'] .';') : '';

$parent_loc_icon_border_radius = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_icon_border_radius']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_icon_border_radius'])) ? ('border-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['parent_loc_icon_border_radius'] .'px;') : '';

$loc_border_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loc_border_color']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loc_border_color']['color'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['loc_border_color']['rgba'] : '';
$loc_border_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loc_border_color_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loc_border_color']['color'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['loc_border_color_hover']['rgba'] : '';

$loc_border_radius_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loc_border_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loc_border_radius']['padding-top'])) ? ('border-top-left-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loc_border_radius']['padding-top'] .';') : '';
$loc_border_radius_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loc_border_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loc_border_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loc_border_radius']['padding-bottom'] .';') : '';
$loc_border_radius_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loc_border_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loc_border_radius']['padding-bottom'])) ? ('border-bottom-left-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loc_border_radius']['padding-left'] .';') : '';
$loc_border_radius_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loc_border_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loc_border_radius']['padding-bottom'])) ? ('border-top-right-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loc_border_radius']['padding-right'] .';') : '';


DirectoryPress_Static_Files::addGlobalStyle("
	.directorypress-location-item .directorypress-parent-location a{
		{$loc_font_family}
		{$loc_font_size}
		{$loc_font_weight}
		{$loc_font_line_height}
		{$loc_font_transform}
		{$parent_loc_title_color}
	}
	.directorypress-location-item:hover .directorypress-parent-location a{
		{$parent_loc_title_color_hover}
	}
	
	.directorypress-location-item  .directorypress-parent-location{
		{$loc_bg}
		{$loc_border_radius_top}
		{$loc_border_radius_right}
		{$loc_border_radius_bottom}
		{$loc_border_radius_left}
	}
	.directorypress-location-item:hover  .directorypress-parent-location{
		{$loc_bg_hover}
	}

	.directorypress-locations-widget .directorypress-parent-location a .location-icon,
	.listings.location-archive .directorypress-locations-columns .directorypress-location-item .directorypress-parent-location a::before{
		{$parent_loc_icon_bg}
		{$parent_loc_icon_border_radius}
	}
	.directorypress-locations-widget .directorypress-parent-location:hover a .location-icon,
	.listings.location-archive .directorypress-locations-columns .directorypress-location-item:hover .directorypress-parent-location a::before{
		{$parent_loc_icon_bg_hover}
	}
	.directorypress-location-item .directorypress-parent-location .sublocations ul li a{
		{$child_loc_font_family}
		{$child_loc_font_size}
		{$child_loc_font_weight}
		{$child_loc_font_line_height}
		{$child_loc_font_transform}
		{$sublocation_title_color}
	}
	.directorypress-location-item .directorypress-parent-location .sublocations ul li a:hover{
		{$sublocation_title_color_hover}
	}

");


###########################################
# Pricing Plan
###########################################

/* font family */

$pp_title_font_family = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_typo']['font-family']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_typo']['font-family'])) ? ('font-family:' . $DIRECTORYPRESS_ADIMN_SETTINGS['pp_typo']['font-family'] . ' !important;') : '';
$pp_title_font_size = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_typo']['font-size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_typo']['font-size'])) ? ('font-size:' . $DIRECTORYPRESS_ADIMN_SETTINGS['pp_typo']['font-size'] . ' !important;') : '';
$pp_title_font_weight = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_typo']['font-weight']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_typo']['font-weight'])) ? ('font-weight:' . $DIRECTORYPRESS_ADIMN_SETTINGS['pp_typo']['font-weight'] . ' !important;') : '';
$pp_title_font_line_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_typo']['line-height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_typo']['line-height'])) ? ('line-height:' . $DIRECTORYPRESS_ADIMN_SETTINGS['pp_typo']['line-height'] . ' !important;') : '';
$pp_title_font_transform = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_title_typo_transform']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_title_typo_transform'])) ? ('text-transform: ' . $DIRECTORYPRESS_ADIMN_SETTINGS['pp_title_typo_transform'] . ' !important;') : ('text-transform: uppercase;');

$pp_price_font_family = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_typo']['font-family']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_typo']['font-family'])) ? ('font-family:' . $DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_typo']['font-family'] . ';') : '';
$pp_price_font_size = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_typo']['font-size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_typo']['font-size'])) ? ('font-size:' . $DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_typo']['font-size'] . ';') : '';
$pp_price_font_weight = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_typo']['font-weight']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_typo']['font-weight'])) ? ('font-weight:' . $DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_typo']['font-weight'] . ';') : '';
$pp_price_font_line_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_typo']['line-height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_typo']['line-heigh'])) ? ('line-height:' . $DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_typo']['line-height'] . ';') : '';
$pp_price_font_transform = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_typo_transform']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_typo_transform'])) ? ('text-transform: ' . $DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_typo_transform'] . ';') : ('text-transform: uppercase;');

$pp_list_font_family = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_typo']['font-family']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_typo']['font-family'])) ? ('font-family:' . $DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_typo']['font-family'] . ';') : '';
$pp_list_font_size = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_typo']['font-size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_typo']['font-size'])) ? ('font-size:' . $DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_typo']['font-size'] . ';') : '';
$pp_list_font_weight = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_typo']['font-weight']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_typo']['font-weight'])) ? ('font-weight:' . $DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_typo']['font-weight'] . ';') : '';
$pp_list_font_line_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_typo']['line-height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_typo']['line-heigh'])) ? ('line-height:' . $DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_typo']['line-height'] . ';') : '';
$pp_list_font_transform = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_typo_transform']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_typo_transform'])) ? ('text-transform: ' . $DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_typo_transform'] . ' !important;') : '';

/* text color */

$pp_title_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_title_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_title_color']['regular'])) ? ('color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_title_color']['regular']. ' !important;') : '';
$pp_title_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_title_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_title_color']['hover'])) ? ('color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_title_color']['hover']. ' !important;') : '';

$pp_list_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_color']['regular'])) ? ('color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_color']['regular']. ';') : '';
$pp_list_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_color']['hover'])) ? ('color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_color']['hover']. ';') : '';

$pp_price_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_color']['regular'])) ? ('color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_color']['regular']. ' !important;') : '';
$pp_price_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_color']['hover'])) ? ('color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_price_color']['hover']. '!important;') : '';

$pp_button_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_color']['regular'])) ? ('color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_color']['regular']. ' !important;') : '';
$pp_button_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_color']['hover'])) ? ('color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_color']['hover']. ' !important;') : '';

$pp_icon_check_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_check_icon_color']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_check_icon_color']['color'])) ? ('color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_check_icon_color']['rgba']. ';') : '';
$pp_icon_remove_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_remove_icon_color']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_remove_icon_color']['color'])) ? ('color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_remove_icon_color']['rgba']. ';') : '';

/* background color */

$pp_wrapper_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_bg']['color'])) ? ('background-color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_bg']['rgba']. ';') : '';
$pp_wrapper_bg_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_bg_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_bg_hover']['color'])) ? ('background-color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_bg_hover']['rgba']. ';') : '';

$pp_list_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_bg']['color'])) ? ('background-color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_bg']['rgba']. ' !important;') : '';
$pp_list_bg_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_bg_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_bg_hover']['color'])) ? ('background-color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_bg_hover']['rgba']. ' !important;') : '';

$pp_button_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_bg']['color'])) ? ('background-color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_bg']['rgba']. ' !important;') : '';
$pp_button_bg_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_bg_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_bg_hover']['color'])) ? ('background-color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_bg_hover']['rgba']. ' !important;') : '';

/* border color */

$pp_wrapper_border_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_border_color']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_border_color']['color'])) ? ('border-color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_border_color']['rgba']. ';') : '';
$pp_wrapper_border_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_border_color_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_border_color_hover']['color'])) ? ('border-color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_border_color_hover']['rgba']. ';') : '';

$pp_list_border_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_border_color']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_border_color']['color'])) ? ('border-color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_border_color']['rgba']. ' !important;') : '';
$pp_list_border_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_border_color_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_border_color_hover']['color'])) ? ('border-color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_border_color_hover']['rgba']. ' !important;') : '';

$pp_button_border_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_border_color']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_border_color']['color'])) ? ('border-color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_border_color']['rgba']. ' !important;') : '';
$pp_button_border_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_border_color_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_border_color_hover']['color'])) ? ('border-color:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_border_color_hover']['rgba']. ';') : '';

/* border radius */

$pp_wrapper_border_radius_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_radius']['padding-top'])) ? ('border-top-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_radius']['padding-top'].';') : '';
$pp_wrapper_border_radius_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_radius']['padding-bottom'].';') : '';
$pp_wrapper_border_radius_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_radius']['padding-left'])) ? ('border-bottom-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_radius']['padding-left'].';') : '';
$pp_wrapper_border_radius_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_radius']['padding-right'])) ? ('border-top-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_radius']['padding-right'].';') : '';

$pp_button_border_radius_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_radius']['padding-top'])) ? ('border-top-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_radius']['padding-top'].' !important;') : '';
$pp_button_border_radius_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_radius']['padding-bottom'].' !important;') : '';
$pp_button_border_radius_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_radius']['padding-left'])) ? ('border-bottom-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_radius']['padding-left'].' !important;') : '';
$pp_button_border_radius_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_radius']['padding-right'])) ? ('border-top-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_radius']['padding-right'].' !important;') : '';

/* box shadow */

$pp_wrapper_shadow = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_shadow']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_shadow'])) ? ('box-shadow:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_shadow']. ' !important;') : '';

/* border width */

$pp_wrapper_border_width = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_border_width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_border_width'])) ? ('border-width:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_wrapper_border_width']. 'px;') : '';
$pp_list_border_width = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_border_width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_border_width'])) ? ('border-width:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_list_border_width']. 'px !important;') : '';
$pp_button_border_width = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_border_width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_border_width'])) ? ('border-width:' .$DIRECTORYPRESS_ADIMN_SETTINGS['pp_button_border_width']. 'px;') : '';

/* css */

DirectoryPress_Static_Files::addGlobalStyle("
	.directorypress-choose-plan ul li .directorypress-price del,
	.directorypress-price del .woocommerce-Price-amount,
	.directorypress-price del .woocommerce-Price-amount .woocommerce-Price-currencySymbol{
			
	}
	.directorypress-choose-plan{
		{$pp_wrapper_bg}
		{$pp_wrapper_border_color}
		{$pp_wrapper_border_width}
		{$pp_wrapper_shadow}
		{$pp_wrapper_border_radius_top}
		{$pp_wrapper_border_radius_bottom}
		{$pp_wrapper_border_radius_left}
		{$pp_wrapper_border_radius_right}
		
	}
	.directorypress-choose-plan:hover{
		{$pp_wrapper_bg_hover}
		{$pp_wrapper_border_color_hover}
	}
	.directorypress-choose-plan .directorypress-panel-heading h3{
		{$pp_title_font_family}
		{$pp_title_font_size}
		{$pp_title_font_weight}
		{$pp_title_font_line_height}
		{$pp_title_font_transform}
		{$pp_title_color}
	}
	.directorypress-choose-plan:hover .directorypress-panel-heading h3{
		{$pp_title_color_hover}
	}
	.directorypress-choose-plan .directorypress-list-group .directorypress-list-group-item.pp-price .directorypress-price{
		{$pp_price_font_family}
		{$pp_price_font_size}
		{$pp_price_font_weight}
		{$pp_price_font_line_height}
		{$pp_price_font_transform}
		{$pp_price_color}
	}
	.directorypress-choose-plan:hover .directorypress-list-group .directorypress-list-group-item.pp-price .directorypress-price{
		{$pp_price_color_hover}
	}
	.directorypress-choose-plan .directorypress-list-group .directorypress-list-group-item{
		{$pp_list_font_family}
		{$pp_list_font_size}
		{$pp_list_font_weight}
		{$pp_list_font_line_height}
		{$pp_list_font_transform}
		{$pp_list_color}
		{$pp_list_border_width}
		{$pp_list_border_color}
		{$pp_list_bg}
	}
	.directorypress-choose-plan:hover .directorypress-list-group .directorypress-list-group-item{
		{$pp_list_color_hover}
		{$pp_list_border_color_hover}
		{$pp_list_bg_hover}
	}
	.directorypress-choose-plan .directorypress-list-group .directorypress-list-group-item .directorypress-icon-check{
		{$pp_icon_check_color}
	}
	.directorypress-choose-plan .directorypress-list-group .directorypress-list-group-item .directorypress-icon-remove{
		{$pp_icon_remove_color}
	}
	.directorypress-choose-plan .directorypress-list-group .directorypress-list-group-item.pp-button a.pricing-button{
		{$pp_button_bg}
		{$pp_button_border_width}
		{$pp_button_border_color}
		{$pp_button_border_radius_top}
		{$pp_button_border_radius_bottom}
		{$pp_button_border_radius_left}
		{$pp_button_border_radius_right}
		{$pp_button_color}
	}
	.directorypress-choose-plan .directorypress-list-group .directorypress-list-group-item.pp-button a.pricing-button:hover{
		{$pp_button_bg_hover}
		{$pp_button_border_color_hover}
		{$pp_button_color_hover}
	}
");


###########################################
# Listing 
###########################################

/* listing title */
$listing_font_family = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_typo']['font-family']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_typo']['font-family'])) ? ('font-family:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_typo']['font-family'] . ';') : '';
$listing_font_size = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_typo']['font-size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_typo']['font-size'])) ? ('font-size:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_typo']['font-size'] . ';') : '';
$listing_font_weight = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_typo']['font-weight']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_typo']['font-weight'])) ? ('font-weight:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_typo']['font-weight'] . ';') : '';
$listing_font_line_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_typo']['line-height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_typo']['line-height'])) ? ('line-height:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_typo']['line-height'] . ';') : '';
$listing_font_transform = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_title_typo_transform']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_title_typo_transform'])) ? ('text-transform: ' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_title_typo_transform'] . ';') : '';

$listing_title_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_title_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_title_color']['regular'])) ? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['listing_title_color']['regular'] .' !important;'): '';
$listing_title_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_title_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_title_color']['hover'])) ? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['listing_title_color']['hover'] .' !important;') : '';


/* listing category */

$listing_cat_font_family = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_typo']['font-family']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_typo']['font-family'])) ? ('font-family:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_typo']['font-family'] . ';') : '';
$listing_cat_font_size = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_typo']['font-size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_typo']['font-size'])) ? ('font-size:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_typo']['font-size'] . ';') : '';
$listing_cat_font_weight = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_typo']['font-weight']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_typo']['font-weight'])) ? ('font-weight:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_typo']['font-weight'] . ';') : '';
$listing_cat_font_line_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_typo']['line-height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_typo']['line-height'])) ? ('line-height:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_typo']['line-height'] . ';') : '';
$listing_cat_font_transform = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_typo_transform']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_typo_transform'])) ? ('text-transform: ' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_typo_transform'] . ';') : '';

$listing_category_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_color']['regular'])  && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_color']['regular'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_color']['regular'] : '';
$listing_category_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_color']['hover'])  && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_color']['regular'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['listing_cat_color']['hover'] : '';


/* listing meta */

$listing_meta_font_family = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_typo']['font-family']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_typo']['font-family'])) ? ('font-family:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_typo']['font-family'] . ';') : '';
$listing_meta_font_size = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_typo']['font-size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_typo']['font-size'])) ? ('font-size:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_typo']['font-size'] . ';') : '';
$listing_meta_font_weight = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_typo']['font-weight']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_typo']['font-weight'])) ? ('font-weight:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_typo']['font-weight'] . ';') : '';
$listing_meta_font_line_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_typo']['line-height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_typo']['line-height'])) ? ('line-height:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_typo']['line-height'] . ';') : '';
$listing_meta_font_transform = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_typo_transform']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_typo_transform'])) ? ('text-transform: ' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_typo_transform'] . ';') : ('text-transform: uppercase;');
	
$listing_meta_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_color']['regular'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_color']['regular'] : '';
$listing_meta_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_color']['hover'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['listing_meta_color']['hover'] : '';

/* listing wrapper */

$listing_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_bg']['color'])) ? ('background:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_bg']['rgba'].';') : '';
$listing_bg_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_bg_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_bg_hover']['color'])) ? ('background:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_bg_hover']['rgba'].';') : '';
$listing_content_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_content_wrapper_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_content_wrapper_bg']['color'])) ? ('background:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_content_wrapper_bg']['rgba'].';') : '';
$listing_content_bg_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_content_wrapper_bg_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_content_wrapper_bg_hover']['color'])) ? ('background:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_content_wrapper_bg_hover']['rgba'].';') : '';
$listing_border_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_border_color']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_border_color']['color'])) ? ('border-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_border_color']['rgba'].';') : '';
$listing_border_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_border_color_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_border_color_hover']['color'])) ? ('border-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_border_color_hover']['rgba'].';') : '';


$listing_border_radius_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_radius']['padding-top'])) ? ('border-top-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_radius']['padding-top'].';') : '';
$listing_border_radius_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_radius']['padding-bottom'].';') : '';
$listing_border_radius_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_radius']['padding-left'])) ? ('border-bottom-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_radius']['padding-left'].';') : '';
$listing_border_radius_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_radius']['padding-right'])) ? ('border-top-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_radius']['padding-right'].';') : '';

$listing_content_border_radius_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_content_wrapper_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_content_wrapper_radius']['padding-top'])) ? ('border-top-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_content_wrapper_radius']['padding-top'].';') : '';
$listing_content_border_radius_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_content_wrapper_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_content_wrapper_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_content_wrapper_radius']['padding-bottom'].';') : '';
$listing_content_border_radius_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_content_wrapper_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_content_wrapper_radius']['padding-left'])) ? ('border-bottom-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_content_wrapper_radius']['padding-left'].';') : '';
$listing_content_border_radius_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_content_wrapper_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_content_wrapper_radius']['padding-right'])) ? ('border-top-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_content_wrapper_radius']['padding-right'].';') : '';

$listing_border_width = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_border_width']) && $DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_border_width'] != 0) ? ('min-width:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_border_width'] . 'px;') : ''; 
$listing_box_shadow = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_shadow']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_shadow'])) ? ('box-shadow:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_shadow'].';') : '';
$listing_box_shadow_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_shadow_hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_shadow_hover'])) ? ('box-shadow:'. $DIRECTORYPRESS_ADIMN_SETTINGS['listing_wrapper_shadow_hover'] .';') : '';

/* Featured tag */

$has_featured_tag_font_family = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_typo']['font-family']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_typo']['font-family'])) ? ('font-family:' . $DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_typo']['font-family'] . ';') : '';
$has_featured_tag_font_size = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_typo']['font-size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_typo']['font-size'])) ? ('font-size:' . $DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_typo']['font-size'] . ';') : '';
$has_featured_tag_font_weight = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_typo']['font-weight']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_typo']['font-weight'])) ? ('font-weight:' . $DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_typo']['font-weight'] . ';') : '';
$has_featured_tag_font_line_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_typo']['line-height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_typo']['line-height'])) ? ('line-height:' . $DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_typo']['line-height'] . ';') : '';
$has_featured_tag_font_transform = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_typo_transform']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_typo_transform'])) ? ('text-transform: ' . $DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_typo_transform'] . ';') : '';

$has_featured_tag_border_radius_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_radius']['padding-top'])) ? ('border-top-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_radius']['padding-top'].';') : '';
$has_featured_tag_border_radius_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_radius']['padding-bottom'].';') : '';
$has_featured_tag_border_radius_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_radius']['padding-left'])) ? ('border-bottom-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_radius']['padding-left'].';') : '';
$has_featured_tag_border_radius_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_radius']['padding-right'])) ? ('border-top-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_radius']['padding-right'].';') : '';

$has_featured_tag_position_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_position_top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_position_top'])) ? ('top:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_position_top'].' !important;') : '';
$has_featured_tag_position_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_position_bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_position_bottom'])) ? ('bottom:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_position_bottom'].' !important;') : '';
$has_featured_tag_position_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_position_left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_position_left'])) ? ('left:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_position_left'].' !important;') : '';
$has_featured_tag_position_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_position_right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_position_right'])) ? ('right:'.$DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_position_right'].' !important;') : '';


$has_featured_tag_width = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_width']) && $DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_width'] != 0) ? ('min-width:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_width'] . 'px;') : ''; 
$has_featured_tag_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_height']) && $DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_height'] != 0) ? ('min-height:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_has_featured_tag_height'] . 'px;') : '';
$has_featured_tag_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_bg']['rgba']) && (!empty($DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_bg']['color']))) ? ('background:'.$DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_bg']['rgba'].';') : '';
$has_featured_tag_bg_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_bg_hover']['rgba']) && (!empty($DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_bg_hover']['color']))) ? ('background:'.$DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_bg_hover']['rgba'].';') : '';
$has_featured_tag_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_color']['regular'])) ? ('color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_color']['regular'].';') : '';
$has_featured_tag_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_color']['hover'])) ? ('color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['has_featured_tag_color']['hover'].';') : '';

/* listing price */

$listing_price_font_family = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_typo']['font-family']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_typo']['font-family'])) ? ('font-family:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_typo']['font-family'] . ';') : '';
$listing_price_font_size = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_typo']['font-size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_typo']['font-size'])) ? ('font-size:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_typo']['font-size'] . ';') : '';
$listing_price_font_weight = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_typo']['font-weight']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_typo']['font-weight'])) ? ('font-weight:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_typo']['font-weight'] . ';') : '';
$listing_price_font_line_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_typo']['line-height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_typo']['line-height'])) ? ('line-height:' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_typo']['line-height'] . ';') : '';
$listing_price_font_transform = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_typo_transform']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_typo_transform'])) ? ('text-transform: ' . $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_typo_transform'] . ';') : '';

$listing_price_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_color']['regular'])) ? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_color']['regular'] .' !important;') : '';
$listing_price_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_color']['hover'])) ? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_color']['hover'] .' !important;') : '';

$listing_price_tag_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_color']['regular'])) ? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_color']['regular'] .' !important;') : '';
$listing_price_tag_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_color']['hover'])) ? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_color']['hover'] .' !important;') : '';

$price_tag_border_radius_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_radius']['padding-top'])) ? ('border-top-left-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_radius']['padding-top'] .';') : '';
$price_tag_border_radius_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_radius']['padding-bottom'] .';') : '';
$price_tag_border_radius_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_radius']['padding-left'])) ? ('border-bottom-left-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_radius']['padding-left'] .';') : '';
$price_tag_border_radius_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_radius']['padding-right'])) ? ('border-top-right-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_radius']['padding-right'] .';') : '';

$price_tag_position_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['price_tag_position']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['price_tag_position']['padding-top'])) ? ('top:'. $DIRECTORYPRESS_ADIMN_SETTINGS['price_tag_position']['padding-top'] .';'): '';
$price_tag_position_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['price_tag_position']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['price_tag_position']['padding-bottom'])) ? ('bottom:'. $DIRECTORYPRESS_ADIMN_SETTINGS['price_tag_position']['padding-bottom'] .';'): '';
$price_tag_position_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['price_tag_position']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['price_tag_position']['padding-left'])) ? ('left:'. $DIRECTORYPRESS_ADIMN_SETTINGS['price_tag_position']['padding-left'] .';'): '';
$price_tag_position_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['price_tag_position']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['price_tag_position']['padding-right'])) ? ('right:'. $DIRECTORYPRESS_ADIMN_SETTINGS['price_tag_position']['padding-right'] .';'): '';

$price_tag_width = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_width'])) ? ('min-width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_width'] .'px;') : '';
$price_tag_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_height'])) ? ('min-height:'. $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_height'] .'px;') : '';

$price_tag_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_bg']['color'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_bg']['rgba'] : $directorypress_primary_color;
$price_tag_bg_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_bg_hover']['rgba'])  && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_bg_hover']['color'])) ? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_bg_hover']['rgba'] .';') : '';

$listview_width = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_logo_width_listview'];
$listview_height = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_logo_height_listview'];


/* listing loadmore */

$loadmore_btn_font_family = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_typo']['font-family']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_typo']['font-family'])) ? ('font-family:' . $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_typo']['font-family'] . ';') : '';
$loadmore_btn_font_size = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_typo']['font-size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_typo']['font-size'])) ? ('font-size:' . $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_typo']['font-size'] . ';') : '';
$loadmore_btn_font_weight = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_typo']['font-weight']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_typo']['font-weight'])) ? ('font-weight:' . $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_typo']['font-weight'] . ';') : '';
$loadmore_btn_font_line_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_typo']['line-height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_typo']['line-height'])) ? ('line-height:' . $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_typo']['line-height'] . ';') : '';
$loadmore_btn_font_transform = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_text_transform']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_text_transform'])) ? ('text-transform: ' . $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_text_transform'] . ';') : '';

$loadmore_btn_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_color']['regular'])) ? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_color']['regular'] .';') : '';
$loadmore_btn_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_color']['hover'])) ? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_color']['hover'] .';') : '';
$loadmore_btn_bg_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_color']['bg']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_color']['bg'])) ? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_color']['bg'] .';') : '';
$loadmore_btn_bg_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_color']['bg-hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_color']['bg-hover'])) ? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_color']['bg-hover'] .';') : '';

$loadmore_btn_border_radius_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_radius']['padding-top'])) ? ('border-top-left-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_radius']['padding-top'] .' !important;') : '';
$loadmore_btn_border_radius_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_radius']['padding-bottom'] .' !important;') : '';
$loadmore_btn_border_radius_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_radius']['padding-left'])) ? ('border-bottom-left-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_radius']['padding-left'] .' !important;') : '';
$loadmore_btn_border_radius_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_radius']['padding-right'])) ? ('border-top-right-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_radius']['padding-right'] .' !important;') : '';

$loadmore_btn_padding_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_padding']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_padding']['padding-top'])) ? ('padding-top:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_padding']['padding-top'] .';') : '';
$loadmore_btn_padding_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_padding']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_padding']['padding-bottom'])) ? ('padding-bottom:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_padding']['padding-bottom'] .';') : '';
$loadmore_btn_padding_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_padding']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_padding']['padding-left'])) ? ('padding-left:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_padding']['padding-left'] .';') : '';
$loadmore_btn_padding_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_padding']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_padding']['padding-right'])) ? ('padding-right:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_padding']['padding-right'] .';') : '';

$loadmore_btn_border_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_border']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_border']['padding-top'])) ? ('border-top:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_border']['padding-top'] .';') : '';
$loadmore_btn_border_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_border']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_border']['padding-bottom'])) ? ('border-bottom:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_border']['padding-bottom'] .';') : '';
$loadmore_btn_border_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_border']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_border']['padding-left'])) ? ('border-left:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_border']['padding-left'] .';') : '';
$loadmore_btn_border_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_border']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_border']['padding-right'])) ? ('border-right:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_btn_border']['padding-right'] .';') : '';

$loadmore_btn_border_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_border_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_border_color']['regular'])) ? ('border-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_border_color']['regular'] .';') : '';
$loadmore_btn_border_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_border_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_border_color']['hover'])) ? ('border-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_border_color']['hover'] .';') : '';

$loadmore_button_width = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_button_width']['width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_button_width']['width'])) ? ('width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_button_width']['width'] .';') : '';
if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_button_box_shadow'])){
		
	$loadmore_button_box_shadow = $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_button_box_shadow']['drop-shadow'];
	$loadmore_button_box_shadow_color = $loadmore_button_box_shadow['color'];
	$loadmore_button_box_shadow_horizontal = ($loadmore_button_box_shadow['horizontal'] != 0)? $loadmore_button_box_shadow['horizontal'] .'px' : 0;
	$loadmore_button_box_shadow_vertical = ($loadmore_button_box_shadow['vertical'] != 0)? $loadmore_button_box_shadow['vertical'] .'px' : 0;
	$loadmore_button_box_shadow_blur = ($loadmore_button_box_shadow['blur'] != 0)? $loadmore_button_box_shadow['blur'] .'px' : 0;
	$loadmore_button_box_shadow_spread = ($loadmore_button_box_shadow['spread'] != 0)? $loadmore_button_box_shadow['spread'] .'px' : 0;
	if(!empty($loadmore_button_box_shadow_color)){
		$loadmore_button_box_shadow_css = $loadmore_button_box_shadow_horizontal .' '. $loadmore_button_box_shadow_vertical .' '. $loadmore_button_box_shadow_blur .' '. $loadmore_button_box_shadow_spread .' '. $loadmore_button_box_shadow_color;
		DirectoryPress_Static_Files::addGlobalStyle("
			.directorypress-show-more-button{
				box-shadow: {$loadmore_button_box_shadow_css};
				-webkit-box-shadow: {$loadmore_button_box_shadow_css};
				-moz-box-shadow: {$loadmore_button_box_shadow_css};
				-o-box-shadow: {$loadmore_button_box_shadow_css};
			}
		");
	}
}
if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_button_box_shadow_hover'])){
		
	$loadmore_button_box_shadow_hover = $DIRECTORYPRESS_ADIMN_SETTINGS['loadmore_button_box_shadow_hover']['drop-shadow'];
	$loadmore_button_box_shadow_hover_color = $loadmore_button_box_shadow_hover['color'];
	$loadmore_button_box_shadow_hover_horizontal = ($loadmore_button_box_shadow_hover['horizontal'] != 0)? $loadmore_button_box_shadow_hover['horizontal'] .'px' : 0;
	$loadmore_button_box_shadow_hover_vertical = ($loadmore_button_box_shadow_hover['vertical'] != 0)? $loadmore_button_box_shadow_hover['vertical'] .'px' : 0;
	$loadmore_button_box_shadow_hover_blur = ($loadmore_button_box_shadow_hover['blur'] != 0)? $loadmore_button_box_shadow_hover['blur'] .'px' : 0;
	$loadmore_button_box_shadow_hover_spread = ($loadmore_button_box_shadow_hover['spread'] != 0)? $loadmore_button_box_shadow_hover['spread'] .'px' : 0;
	if(!empty($loadmore_button_box_shadow_hover_color)){
		$loadmore_button_box_shadow_hover_css = $loadmore_button_box_shadow_hover_horizontal .' '. $loadmore_button_box_shadow_hover_vertical .' '. $loadmore_button_box_shadow_hover_blur .' '. $loadmore_button_box_shadow_hover_spread .' '. $loadmore_button_box_shadow_hover_color;
		DirectoryPress_Static_Files::addGlobalStyle("
			.directorypress-show-more-button:hover{
				box-shadow: {$loadmore_button_box_shadow_hover_css};
				-webkit-box-shadow: {$loadmore_button_box_shadow_hover_css};
				-moz-box-shadow: {$loadmore_button_box_shadow_hover_css};
				-o-box-shadow: {$loadmore_button_box_shadow_hover_css};
			}
		");
	}
}

DirectoryPress_Static_Files::addGlobalStyle("
	.directorypress-listing .directorypress-listing-item-holder{
		{$listing_bg}
		{$listing_border_color}
		{$listing_border_radius_top}
		{$listing_border_radius_right}
		{$listing_border_radius_bottom}
		{$listing_border_radius_left}
		{$listing_box_shadow}
		{$listing_border_width}
	}
	.directorypress-listing .directorypress-listing-item-holder:hover{
		{$listing_bg_hover}
		{$listing_border_color_hover}
		{$listing_box_shadow_hover}
	}
	.directorypress-listing .directorypress-listing-item-holder .directorypress-listing-text-content-wrap{
		{$listing_content_bg}
		{$listing_content_border_radius_top}
		{$listing_content_border_radius_right}
		{$listing_content_border_radius_bottom}
		{$listing_content_border_radius_left}
		overflow:hidden;
	}
	.directorypress-listing .directorypress-listing-item-holder:hover .directorypress-listing-text-content-wrap{
		{$listing_content_bg_hover}
	}
	.directorypress-listing .directorypress-listing-item-holder .directorypress-listing-text-content-wrap .directorypress-listing-title h2 a{
		{$listing_font_family}
		{$listing_font_size}
		{$listing_font_weight}
		{$listing_font_line_height}
		{$listing_font_transform}
		{$listing_title_color}
	}
	.directorypress-listing .directorypress-listing-item-holder:hover .directorypress-listing-text-content-wrap .directorypress-listing-title h2 a{
		{$listing_title_color_hover}
	}
	
	.directorypress-listing [class^='has_featured-tag-']{
		{$has_featured_tag_font_family}
		{$has_featured_tag_font_size}
		{$has_featured_tag_font_weight}
		{$has_featured_tag_font_line_height}
		{$has_featured_tag_font_transform}
		{$has_featured_tag_width}
		{$has_featured_tag_height}
		{$has_featured_tag_bg}
		{$has_featured_tag_color}
		{$has_featured_tag_border_radius_top}
		{$has_featured_tag_border_radius_right}
		{$has_featured_tag_border_radius_bottom}
		{$has_featured_tag_border_radius_left}
		{$has_featured_tag_position_top}
		{$has_featured_tag_position_bottom}
		{$has_featured_tag_position_left}
		{$has_featured_tag_position_right}

	}
	.directorypress-listing .directorypress-listing-item-holder:hover [class^='has_featured-tag-']{
		{$has_featured_tag_color_hover}
		{$has_featured_tag_bg_hover}
	}
	.directorypress-listing .directorypress-listing-item-holder figure .price{
		{$price_tag_position_top}
		{$price_tag_position_bottom}
		{$price_tag_position_left}
		{$price_tag_position_right}
	}
	.directorypress-listing .directorypress-listing-item-holder .price .field-content {
		{$listing_price_font_family}
		{$listing_price_font_size}
		{$listing_price_font_weight}
		{$listing_price_font_line_height}
		{$listing_price_font_transform}
		{$listing_price_color}
	}
	.directorypress-listing .directorypress-listing-item-holder:hover .price .field-content {
		{$listing_price_color_hover}
	}
	.directorypress-listing .directorypress-listing-item-holder figure .price .field-content {
		background:{$price_tag_bg};
		{$price_tag_width}
		{$price_tag_height}
		{$price_tag_border_radius_top}
		{$price_tag_border_radius_right}
		{$price_tag_border_radius_bottom}
		{$price_tag_border_radius_left}
		{$price_tag_position_top}
		{$price_tag_position_bottom}
		{$price_tag_position_left}
		{$price_tag_position_right}
		{$listing_price_tag_color}
	}
	.directorypress-listing .directorypress-listing-item-holder:hover figure .price .field-content {
		{$price_tag_bg_hover}
		{$listing_price_tag_color_hover}
	}
	.listing-pre,
	.listing-next{
		color:{$directorypress_primary_color};
		border-color:{$directorypress_primary_color};
	}
	.listing-pre:hover,
	.listing-next:hover{
		background-color:{$directorypress_primary_color};
		color:#fff;
	}
	
	.listing-post-style-listview_default .directorypress-listing-text-content-wrap,
	.listing-post-style-listview_ultra .directorypress-listing-text-content-wrap {
		width:calc(100% - {$listview_width}px);
		width: -webkit-calc(100% - {$listview_width}px);
		width: -moz-calc(100% - {$listview_width}px);
		float:left;
	}
	.listing-post-style-listview_default .directorypress-listing-text-content-wrap .mod-inner-content {
		min-height:{$listview_height}px;
	}
	.listing-post-style-listview_default figure,
	.listing-post-style-listview_ultra figure,
	.listing-post-style-listview_mod figure{
		width:{$listview_width}px;
		float:left;
	}
	.btn-block.directorypress-show-more-button{
		{$loadmore_button_width}
		{$loadmore_btn_font_family}
		{$loadmore_btn_font_size}
		{$loadmore_btn_font_weight}
		{$loadmore_btn_font_line_height}
		{$loadmore_btn_font_transform}
		{$loadmore_btn_color}
		{$loadmore_btn_bg_color}
		{$loadmore_btn_border_color}
		{$loadmore_btn_border_radius_top}
		{$loadmore_btn_border_radius_bottom}
		{$loadmore_btn_border_radius_left}
		{$loadmore_btn_border_radius_right}
		{$loadmore_btn_padding_top}
		{$loadmore_btn_padding_bottom}
		{$loadmore_btn_padding_left}
		{$loadmore_btn_padding_right}
		{$loadmore_btn_border_top}
		{$loadmore_btn_border_bottom}
		{$loadmore_btn_border_left}
		{$loadmore_btn_border_right}
		border-style:solid;
	}
	.btn-block.directorypress-show-more-button:hover{
		{$loadmore_btn_color_hover}
		{$loadmore_btn_bg_color_hover}
		{$loadmore_btn_border_color_hover}
	}
");

###########################################
# Archive
###########################################
$archive_content_area_width = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['archive_content_area_width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['archive_content_area_width'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['archive_content_area_width'] : 67;
$archive_side_area_width = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['archive_side_area_width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['archive_side_area_width'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['archive_side_area_width'] : 33;
$archive_side_area_padding = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['archive_side_area_padding']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['archive_side_area_padding'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['archive_side_area_padding'] : 15;
$archive_side_area_position = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['archive_side_area_position']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['archive_side_area_position'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['archive_side_area_position'] : 'right';
$archive_top_map_width_box = ($DIRECTORYPRESS_ADIMN_SETTINGS['archive_top_map_width']) ? ('width:100%; left:auto;') : '';
$archive_content_area_location_margin_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['archive_content_area_location_margin_top'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['archive_content_area_location_margin_top'] : 70;
$archive_content_area_category_margin_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['archive_content_area_category_margin_top'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['archive_content_area_category_margin_top'] : 70;
$archive_content_area_listings_margin_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['archive_content_area_listings_margin_top'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['archive_content_area_listings_margin_top'] : 70;

DirectoryPress_Static_Files::addGlobalStyle("
	.archive-content-wrapper{
		margin-left:-{$archive_side_area_padding}px;
		margin-right:-{$archive_side_area_padding}px;
	}
	.listing-archive-sidearea{
		width:{$archive_side_area_width}%;
		float:{$archive_side_area_position};
		padding-left:{$archive_side_area_padding}px;
		padding-right:{$archive_side_area_padding}px;
	}
	.listing-archive-content{
		width:{$archive_content_area_width}%;
		float:{$archive_side_area_position};
		padding-left:{$archive_side_area_padding}px;
		padding-right:{$archive_side_area_padding}px;
	}
	.listing-archive .map-listings{
		{$archive_top_map_width_box}
	}
	.listing-archive-content .map-listings{
		width:100%;
		left:auto;
	}
	.archive-style-nosidebar .archive-locations-wrapper .directorypress-locations-columns {
		
	}
	.listing-archive-content .map-listings .directorypress-maps-canvas{
		border-radius:0;
	}
	.archive-style-nosidebar .archive-locations-wrapper{
		margin-top:{$archive_content_area_location_margin_top}px;
	}
	.archive-style-nosidebar .archive-categories-wrapper{
		margin-top:{$archive_content_area_category_margin_top}px;
	}
	.archive-style-nosidebar .archive-listings-wrapper{
		margin-top:{$archive_content_area_listings_margin_top}px;
	}
	
");

###########################################
# Misc
###########################################

$new_reset_link = $directorypress_primary_color;

DirectoryPress_Static_Files::addGlobalStyle("

	.directorypress-drop-attached-item .directorypress-ajax-iloader > div{
		background-color:{$directorypress_primary_color} !important;
	}
	.directorypress-remove-from-favourites-list{
		background-color:{$directorypress_primary_color};
	}
	.directorypress-close-info-window{
		background-color:{$directorypress_primary_color};
	}
	.directorypress-listings-grid .directorypress-listing-text-content-wrap .directorypress-field-type-categories .field-content .directorypress-label{
		background-color:{$directorypress_primary_color} !important;
		border-color:{$directorypress_primary_color} !important;
		color:#fff;
		border-radius:0;
	}
	.directorypress-orderby-links a.btn.btn-default.btn-primary{
		background:none !important;
		padding:6px 12px !important;
	}
	.directorypress-content-wrap .btn-default:hover,
	.directorypress-orderby-links .btn-default.btn-primary,
	.directorypress-orderby-links .btn-default.btn-primary:hover{
		
	}
	
	.btn-primary:hover{
		background-color:{$directorypress_primary_color};
		border-color:{$directorypress_primary_color};
	}
	.single-listing.directorypress-content-wrap .nav-tabs > li a,
	.single-listing.directorypress-content-wrap .nav-tabs > li a:hover,
	.access-press-social .apsl-login-new-text{
		
	}
	.single-listing.directorypress-content-wrap .nav-tabs > li a i{
		color:{$directorypress_primary_color} !important;
	}
	.cat-scroll-header,
	.search-form-style2 .directorypress-search-holder h5,
	.directorypress-single-listing-text-content-wrap .directorypress-fields-group .directorypress-fields-group-caption,
	.directorypress-single-listing-text-content-wrap .directorypress-field-item .field-label{
		
	}
	.handpick-locations .directorypress-location-item a{}
	.directorypress-dashboard-tabs-content .directorypress-table ul li.td_listings_options .btn-group a{
		background-color:#fff !important;
		border-color: #fff !important;
	}
	.directorypress-dashboard-tabs-content .directorypress-table ul li.td_listings_options .btn-group a span{
		color:{$directorypress_primary_color} !important;
	}
	.directorypress-dashboard-tabs-content .directorypress-table ul:first-child li,
	.directorypress-dashboard-tabs-content .directorypress-table ul:first-child li a,
	.directorypress-dashboard-tabs-content .directorypress-table ul:first-child li a span,
	.directorypress-content-wrap .directorypress-submit-section-adv .directorypress-panel-default > .directorypress-panel-heading h3{
		
	}
	.directorypress-user-avatar-delete a,
	.single-listing .field-content a,
	.author-avatar-btn a{
		
	}
	.directorypress-user-avatar-delete a:hover,
	.author-avatar-btn a:hover{
		background-color:{$directorypress_primary_color};
		border-color:{$directorypress_primary_color};
		color:#fff;
	}
	.save-avatar-btn .profile-avatar-btn,
	.listing-author-box .author-info .author-btn a,
	.directorypress-social-widget ul.directorypress-social li a{
		background-color:{$directorypress_primary_color} !important;
		border-color:{$directorypress_primary_color} !important;
		color:#fff !important;
	}
	.save-avatar-btn .profile-avatar-btn:hover,
	.listing-author-box .author-info .author-btn a:hover,
	.directorypress-social-widget ul.directorypress-social li a:hover{
		background-color:{$directorypress_primary_color};
		border-color:{$directorypress_primary_color};
		color:#fff !important;
	}
	.search-form-style2 .directorypress-search-holder h5:before,
	.listing-author-box .author-info .author-info-list ul li i,
	.directorypress-listing-title .rating-numbers{
		background-color:{$directorypress_primary_color};
	}

	.directorypress-listing.directorypress-has_featured .directorypress-listing-figure a.directorypress-listing-figure-img-wrap::after{
		background-color:#ff5656;
	}

	.cz-datetime .datetime-reset-btn .btn.btn-primary{
		background-color:{$directorypress_primary_color};
	}
	.cz-datetime .datetime-reset-btn .btn.btn-primary:hover{
		background:{$directorypress_secondary_color};
	}
	:not(.listing-archive) .search-form-style2.directorypress-content-wrap.directorypress-search-form .bs-caret,
	:not(.location-archive) .search-form-style2.directorypress-content-wrap.directorypress-search-form .bs-caret,
	:not(.cat-archive) .search-form-style2.directorypress-content-wrap.directorypress-search-form .bs-caret,
	:not(.search-result) .search-form-style2.directorypress-content-wrap.directorypress-search-form .bs-caret,
	:not(.listing-archive) .search-form-style2.directorypress-content-wrap.directorypress-search-form .directorypress-mylocation.glyphicon-screenshot::before,
	:not(.location-archive) .search-form-style2.directorypress-content-wrap.directorypress-search-form .directorypress-mylocation.glyphicon-screenshot::before,
	:not(.cat-archive) .search-form-style2.directorypress-content-wrap.directorypress-search-form .directorypress-mylocation.glyphicon-screenshot::before,
	:not(.search-result) .search-form-style2.directorypress-content-wrap.directorypress-search-form .directorypress-mylocation.glyphicon-screenshot::before{
		background-color:{$directorypress_primary_color} !important;
		color:#fff;
	}
	.directorypress_search_widget .bs-caret,
	.directorypress-locations-widget .directorypress-parent-location a .location-icon,
	.listings.location-archive .directorypress-locations-columns .directorypress-location-item  .directorypress-parent-location a:before{
		background-color:{$directorypress_primary_color};
	}
	.directorypress_search_widget .has-feedback:hover .glyphicon-screenshot,
	.directorypress-locations-widget .directorypress-parent-location a:hover .location-icon,
	.listings.location-archive .directorypress-locations-columns .directorypress-location-item  .directorypress-parent-location a:hover:before{
		background-color:{$directorypress_secondary_color};
	}
	.directorypress-listings-block.cz-listview article .directorypress-field-type-categories .field-content .label.label-primary{
		background-color:{$directorypress_primary_color} !important;
	}
	.directorypress-listings-block.cz-listview article .directorypress-field-item .field-label .directorypress-field-icon{
		color:{$directorypress_primary_color} !important;
	}
	.directorypress-single-listing-logo-wrap header.directorypress-listing-title .statVal span.ui-rater-rating {
		background-color:{$directorypress_primary_color} !important;
	}
	.cz-checkboxes .checkbox .radio-check-item:before,
	.directorypress-price.directorypress-payments-free,
	.directorypress-content-wrap .directorypress-list-group-item i.directorypress-icon-check,
	.checkbox-wrap .checkbox label:before,
	label span.radio-check-item:before{
		color:{$directorypress_primary_color} !important;
	}
	.checkbox label input[type=radio]:not(old):checked + span.radio-check-item:before,
	.difp-column-difp-cb .directorypress-checkbox label input[type=checkbox]:not(old):checked + span.radio-check-item:before,
	label input[type=radio]:not(old):checked + span.radio-check-item:before{
		background-color:{$directorypress_primary_color};
		border-color:{$directorypress_primary_color};
		color:#fff !important;
	}
	.directorypress-categories-widget .directorypress-parent-category a:hover,
	.directorypress-categories-widget .directorypress-parent-category a:hover .categories-name,
	.cat-style-default .directorypress-categories-wrapper .directorypress-category-holder .directorypress-parent-category a:hover .categories-name{
		border-color:{$directorypress_primary_color};
		color:{$directorypress_primary_color};
	}
	.directorypress-categories-widget .directorypress-parent-category a:hover,
	a.directorypress-hint-icon:after{
		color:{$directorypress_primary_color};
	}
	.cat-style-default .directorypress-categories-wrapper .directorypress-category-holder .directorypress-parent-category a:hover .categories-count{
		background:{$directorypress_primary_color};
		border-color:{$directorypress_primary_color};
		color:#fff;
	}
	.single-listing .directorypress-label-primary {background:none;}

	.single-listing-btns ul li a{
		
	}

	.directorypress-listing .directorypress-listing-text-content-wrap .listing-metas em.directorypress-listing-date i,
	.directorypress-listing .directorypress-listing-text-content-wrap .listing-views i,
	.directorypress-listing .directorypress-listing-text-content-wrap .listing-id i,
	.directorypress-listing .directorypress-listing-item-holder .directorypress-listing-text-content-wrap .listing-location i,
	.single-listing .directorypress-listing-date i,
	.single-listing .listing-views i,
	.single-location-address i,
	.dashbeard-btn-panel .cz-btn-wrap a.favourites-link:hover{
		color:{$directorypress_secondary_color};
	}
	.dashbeard-btn-panel .cz-btn-wrap a.favourites-link{
		background-color:{$directorypress_primary_color};
	}
	.cz-listview .directorypress-listing-text-content-wrap .price span.field-content{
		background:{$directorypress_secondary_color};
	}
	.author_type,
	.author_verifed{
		border-color:{$directorypress_primary_color};
		color:{$directorypress_primary_color};
	}
	.author_unverifed{
		border-color:#E37B33;
		color:#E37B33;
	}

	.user-panel .author-thumbnail{
		border:3px solid {$directorypress_primary_color};
	}

	.skin-blue .user-panel-main .sidebar-menu > li.active > a,
	.skin-blue .user-panel-main .sidebar-menu>li>.treeview-menu{
		border-left-color:{$directorypress_primary_color};
	}
	.single-listing .owl-nav .owl-prev:hover, .single-listing .owl-nav .owl-next:hover {
		color: {$directorypress_secondary_color};
	}

	.td_listings_id span.directorypress-fic4-bookmark-white,
	.td_listings_options .dropdown .dropdown-menu a span,
	.comments_numbers
	{
		color: {$directorypress_primary_color};
	}
	.new_reset_link{
		color:{$new_reset_link};
	}
");


	
	$listing_title_font = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_title_font'];
	$directorypress_search_form_margin_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_search_form_margin_top'])) ? ('margin-top:'.$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_search_form_margin_top'].'px;') : '';
	DirectoryPress_Static_Files::addGlobalStyle("
	
		header.directorypress-listing-title h2 {
		font-size: {$listing_title_font}px;
		}
		.directorypress-search-form,
		.search-form-style1.directorypress-content-wrap.directorypress-search-form{
		{$directorypress_search_form_margin_top}
		}
	");
	if (directorypress_has_map()){
		DirectoryPress_Static_Files::addGlobalStyle("
			.listings.cat-archive .main-search-bar .directorypress-content-wrap.directorypress-search-form {margin: 0 !important;}
			.listings.location-archive .main-search-bar .directorypress-content-wrap.directorypress-search-form {margin: 0 !important;}
		");
	}
	



/* single listing*/

$single_listing__button_bg_color = (isset($directorypress_primary_color))? ('background-color:' . $directorypress_primary_color):'';
DirectoryPress_Static_Files::addGlobalStyle("
	
	.single-listing .listing-top-content .slick-carousel .listing-pre:hover,
	.single-listing .listing-top-content .slick-carousel .listing-next:hover{
		color:{$directorypress_primary_color} !important;
		background:#fff !important;
	}
	.directorypress-listing-figure-wrap .slick-prev:hover:before,
	.directorypress-listing-figure-wrap .slick-next:hover:before,
	.directorypress-listing-figure-wrap .slide-link i:hover{
		color:{$directorypress_primary_color} !important;
	}
	.single-listing .listing-header-wrap header .price .directorypress-field-item span.field-content,
	.single-listing .directorypress-single-listing-logo-wrap .price .directorypress-field-item span.field-content{
		background-color:{$directorypress_primary_color};
	}
		
	.directorypress-directory-head-section-content-top .single-listing-directory-btns .directorypress-booking-link.button-style-2{
		{$single_listing__button_bg_color}
	}
	.single-listing-btns .button-style-2:hover {
		border-color: {$directorypress_primary_color};
		color: {$directorypress_primary_color};
	}
	.directorypress-single-directory-style .business-hours-header i{
		color: {$directorypress_primary_color};
	}
	.directorypress-directory-head-section-content-top .single-listing-rating .rating-numbers{
		background-color: {$directorypress_secondary_color};
	}
	.single-listing .single-listing-contact .single-filed-phone .directorypress-field-item .directorypress-field-icon{
		color: {$directorypress_secondary_color};
	}
	.directorypress_widget_author .author-btns a:hover{
		background-color: {$directorypress_primary_color};
	}
	.author-phone.style2 a,
	.author-phone a{
		background-color: {$directorypress_primary_color};
	}
	.author-phone.style2 a:hover,
	.author-phone a:hover{
		background-color: {$directorypress_secondary_color};
	}
	.author-phone.whatsapp.style2 a,
	.author-phone.whatsapp a{
		background-color: #199473;
	}
	.author-phone.whatsapp.style2 a:hover,
	.author-phone.whatsapp a:hover{
		background-color: #199473;
	}
");

// submit listing page
DirectoryPress_Static_Files::addGlobalStyle("
	.directorypress-upload-item .directorypress-drop-zone .btn{
		background-color: {$directorypress_primary_color};
	}
	.submit-listing-button,
	input.submit-listing-button[type='submit']{
		border-color: {$directorypress_primary_color};
		
	}
	.submit-listing-button:hover,
	input.submit-listing-button[type='submit']:hover{
		background-color: {$directorypress_primary_color};
		color:#fff;
	}
	.input-checkbox .input-checkbox-item:after{
		color: {$directorypress_primary_color};
	}
	.add-address-btn .add_address{
		border-color: {$directorypress_primary_color};
	}
	.add-address-btn .add_address:hover{
		border-color: {$directorypress_primary_color};
		background: {$directorypress_primary_color};
	}
	.widget #directorypress_contact_form .directorypress-send-message-button{
		background: {$directorypress_primary_color};
	}
	.widget #directorypress_contact_form .directorypress-send-message-button:hover{
		background: {$directorypress_secondary_color};
	}
	.directorypress_widget_author .directorypress-author.style3 .author-social-follow .author-social-follow-ul li a:hover,
	.directorypress_map_widget .directorypress-listing-social-links ul li a:hover{
		color: {$directorypress_primary_color} !important;
	}
");

// Login Form
DirectoryPress_Static_Files::addGlobalStyle("
	.directorypress-default-login-form form input.form-control:hover,
	.woocommerce-ResetPassword .woocommerce-form-row input:hover{
		border-color: {$directorypress_primary_color};
	}
	.directorypress-default-login-form form .directorypress-login-button:hover,
	.woocommerce-ResetPassword .woocommerce-form-row .woocommerce-Button:hover{
		background-color: {$directorypress_primary_color};
	}
	.directorypress-default-login-form form p.form-group i{
		color: {$directorypress_primary_color};
	}
	#resetpassform .resetpass-submit input:hover{
		background-color: {$directorypress_primary_color};
	}
");
if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_custom_skin']) && $DIRECTORYPRESS_ADIMN_SETTINGS['fup_custom_skin']){
	// Frontend User Panel
	$fup_panel_width = ((isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_is_panel_width']) && $DIRECTORYPRESS_ADIMN_SETTINGS['fup_is_panel_width'] == 'custom') && (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_panel_width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_panel_width'])))? ('width: '. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_panel_width'] .'px;') : '';
	$fup_content_area_background = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_content_area_background']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_content_area_background']))? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_content_area_background'] .';'): '';
	$fup_content_area_border_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_content_area_border_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_content_area_border_color']))? ('border-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_content_area_border_color'] .';'): '';
	$fup_sidebar_area_border_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_border_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_border_color']))? ('border-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_border_color'] .';'): '';
	
	// sidebar author area
	$fup_sidebar_author_section_background = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_section_background']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_section_background']))? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_section_background'] .';'): '';
	$fup_sidebar_author_name_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_name_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_name_color']))? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_name_color'] .';'): '';
	$fup_sidebar_author_name_status_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_name_status_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_name_status_color']))? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_name_status_color'] .';'): '';
	
	$fup_sidebar_author_section_padding = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_section_padding']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_section_padding']['padding-top'])) ? ('padding-top:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_section_padding']['padding-top'] .';') : '';
	$fup_sidebar_author_section_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_section_padding']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_section_padding']['padding-bottom'])) ? ('padding-bottom:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_section_padding']['padding-bottom'] .';') : '';
	$fup_sidebar_author_section_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_section_padding']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_section_padding']['padding-left'])) ? ('padding-left:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_section_padding']['padding-left'] .';') : '';
	$fup_sidebar_author_section_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_section_padding']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_section_padding']['padding-right'])) ? ('padding-right:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_author_section_padding']['padding-right'] .';') : '';
	

	// sidebar menu
	$fup_sidebar_area_menu_wrapper_padding = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_wrapper_padding']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_wrapper_padding']['padding-top'])) ? ('padding-top:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_wrapper_padding']['padding-top'] .';') : '';
	$fup_sidebar_area_menu_wrapper_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_wrapper_padding']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_wrapper_padding']['padding-bottom'])) ? ('padding-bottom:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_wrapper_padding']['padding-bottom'] .';') : '';
	$fup_sidebar_area_menu_wrapper_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_wrapper_padding']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_wrapper_padding']['padding-left'])) ? ('padding-left:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_wrapper_padding']['padding-left'] .';') : '';
	$fup_sidebar_area_menu_wrapper_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_wrapper_padding']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_wrapper_padding']['padding-right'])) ? ('padding-right:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_wrapper_padding']['padding-right'] .';') : '';
	
	// parent menu
	$fup_sidebar_area_menu_parent_padding = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_padding']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_padding']['padding-top'])) ? ('padding-top:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_padding']['padding-top'] .';') : '';
	$fup_sidebar_area_menu_parent_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_padding']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_padding']['padding-bottom'])) ? ('padding-bottom:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_padding']['padding-bottom'] .';') : '';
	$fup_sidebar_area_menu_parent_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_padding']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_padding']['padding-left'])) ? ('padding-left:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_padding']['padding-left'] .';') : '';
	$fup_sidebar_area_menu_parent_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_padding']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_padding']['padding-right'])) ? ('padding-right:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_padding']['padding-right'] .';') : '';
	
	$fup_sidebar_area_menu_parent_radius = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_radius']['padding-top'])) ? ('border-top-left-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_radius']['padding-top'] .';') : '';
	$fup_sidebar_area_menu_parent_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_radius']['padding-bottom'])) ? ('border-bottom-left-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_radius']['padding-bottom'] .';') : '';
	$fup_sidebar_area_menu_parent_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_radius']['padding-left'])) ? ('border-bottom-right-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_radius']['padding-left'] .';') : '';
	$fup_sidebar_area_menu_parent_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_radius']['padding-right'])) ? ('border-top-right-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_parent_radius']['padding-right'] .';') : '';
	
	
	$fup_sidebar_menu_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_color']['regular']))? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_color']['regular'] .';'): '';
	$fup_sidebar_menu_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_color']['hover']))? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_color']['hover'] .';'): '';
	$fup_sidebar_menu_background_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_color']['bg']))? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_color']['bg'] .';'): '';
	$fup_sidebar_menu_background_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_color']['bg-hover']))? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_color']['bg-hover'] .';'): '';
	$fup_sidebar_menu_background_color_active = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_color']['bg-active']))? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_color']['bg-active'] .';'): '';
	
	$fup_sidebar_menu_border_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_border_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_border_color']['regular']))? ('border-bottom-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_border_color']['regular'] .';'): '';
	$fup_sidebar_menu_border_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_border_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_border_color']['hover']))? ('border-bottom-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_menu_border_color']['hover'] .';'): '';

	// submenu
	$fup_sidebar_area_menu_submenu_padding = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_padding']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_padding']['padding-top'])) ? ('padding-top:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_padding']['padding-top'] .';') : '';
	$fup_sidebar_area_menu_submenu_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_padding']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_padding']['padding-bottom'])) ? ('padding-bottom:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_padding']['padding-bottom'] .';') : '';
	$fup_sidebar_area_menu_submenu_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_padding']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_padding']['padding-left'])) ? ('padding-left:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_padding']['padding-left'] .';') : '';
	$fup_sidebar_area_menu_submenu_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_padding']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_padding']['padding-right'])) ? ('padding-right:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_padding']['padding-right'] .';') : '';
	
	$fup_sidebar_area_menu_submenu_radius = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_radius']['padding-top'])) ? ('border-top-left-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_radius']['padding-top'] .';') : '';
	$fup_sidebar_area_menu_submenu_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_radius']['padding-bottom'])) ? ('border-bottom-left-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_radius']['padding-bottom'] .';') : '';
	$fup_sidebar_area_menu_submenu_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_radius']['padding-left'])) ? ('border-bottom-right-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_radius']['padding-left'] .';') : '';
	$fup_sidebar_area_menu_submenu_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_radius']['padding-right'])) ? ('border-top-right-radius:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_area_menu_submenu_radius']['padding-right'] .';') : '';
	
	
	$fup_sidebar_submenu_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']['regular']))? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']['regular'] .';'): '';
	$fup_sidebar_submenu_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']['hover']))? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']['hover'] .';'): '';
	
	$fup_sidebar_submenu_dot_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']['regular']))? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']['regular'] .';'): '';
	
	$fup_sidebar_submenu_dot_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']['hover']))? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']['hover'] .';'): '';
	
	$fup_sidebar_submenu_background_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']['bg']))? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']['bg'] .';'): '';
	$fup_sidebar_submenu_background_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']['bg-hover']))? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']['bg-hover'] .';'): '';
	$fup_sidebar_submenu_background_color_active = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']['bg-active']))? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_color']['bg-active'] .';'): '';

	$fup_sidebar_submenu_border_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_border_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_border_color']['regular']))? ('border-bottom-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_border_color']['regular'] .';'): '';
	$fup_sidebar_submenu_border_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_border_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_border_color']['hover']))? ('border-bottom-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['fup_sidebar_submenu_border_color']['hover'] .';'): '';

	if((isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_is_panel_width']) && $DIRECTORYPRESS_ADIMN_SETTINGS['fup_is_panel_width'] == 'custom') && (isset($DIRECTORYPRESS_ADIMN_SETTINGS['fup_panel_width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['fup_panel_width']))){
		$breakpoint = $DIRECTORYPRESS_ADIMN_SETTINGS['fup_panel_width'] - 1;
		DirectoryPress_Static_Files::addGlobalStyle("
		
			.dashboard-page-section .container{
				{$fup_panel_width}
			}
			@media handheld, only screen and (max-width: {$breakpoint}px) {
				.dashboard-page-section .container{
					width:100%;
				}
				
			}
		");
	}
	DirectoryPress_Static_Files::addGlobalStyle("
		#panel-content-wrapper{
			{$fup_content_area_background}
			{$fup_content_area_border_color }
		}
		#directorypress-panel-sidebar-wrapper{
			{$fup_content_area_background}
			{$fup_content_area_border_color }
		}
		#panel-content-wrapper,
		.profile-img-inner,
		.dpfl-dashboad-profile-card,
		#panel-content-wrapper .form-control,
		#panel-content-wrapper input,
		#panel-content-wrapper .select2-container--default .select2-selection--single,
		.dpfl-dashboad-profile-card-header,
		.difp-table-row,
		.difp-message-head,
		.directorypress-frontend-dashboard .shop_table tbody tr{
			{$fup_content_area_border_color }
		}
		#panel-content-wrapper ::-webkit-input-placeholder,
		#panel-content-wrapper :-ms-input-placeholder,	
		#panel-content-wrapper ::placeholder,
		#panel-content-wrapper select2-selection__rendered{
		  color: #d2d7de;
		}

		.dashboard-wrapper .jquery-accordion-menu{
			{$fup_sidebar_area_border_color}
		}
		
		.dashboard-wrapper .author-section{
			{$fup_sidebar_author_section_background}
			{$fup_sidebar_author_section_padding}
		}
		.dashboard-wrapper .author-section .author-name-info h6{
			{$fup_sidebar_author_name_color}
		}
		.dashboard-wrapper .author-section .author-name-info author-status{
			{$fup_sidebar_author_name_status_color}
		}
		
		#directorypress-panel-sidebar-wrapper .panel-menu-wrapper{
			{$fup_sidebar_area_menu_wrapper_padding}
		}
		
		.dashboard-wrapper .jquery-accordion-menu > ul > li > a.parent-menu-link{
			{$fup_sidebar_menu_color}
			{$fup_sidebar_menu_background_color}
			{$fup_sidebar_menu_border_color}
			{$fup_sidebar_area_menu_parent_padding}
			{$fup_sidebar_area_menu_parent_radius}
		}
		.dashboard-wrapper .jquery-accordion-menu > ul > li > a.parent-menu-link:hover,
		.dashboard-wrapper .jquery-accordion-menu > ul > li > a.parent-menu-link:active{
			{$fup_sidebar_menu_color_hover}
			{$fup_sidebar_menu_background_color_hover}
			{$fup_sidebar_menu_border_color_hover}
		}
		.dashboard-wrapper .jquery-accordion-menu > ul > li > a.parent-menu-link.active{
			{$fup_sidebar_menu_color_hover}
			{$fup_sidebar_menu_background_color_active}
			{$fup_sidebar_menu_border_color_hover}
		}
		
		.jquery-accordion-menu ul ul.submenu li a{
			{$fup_sidebar_submenu_color}
			{$fup_sidebar_submenu_background_color}
			{$fup_sidebar_submenu_border_color}
			{$fup_sidebar_area_menu_submenu_padding}
			{$fup_sidebar_area_menu_submenu_radius}
		}
		
		.jquery-accordion-menu ul ul.submenu li a:hover{
			{$fup_sidebar_submenu_color_hover}
			{$fup_sidebar_submenu_background_color_hover}
			{$fup_sidebar_submenu_border_color_hover}
		}
		.jquery-accordion-menu ul ul.submenu li a:hover:before{
			{$fup_sidebar_submenu_dot_color_hover}
		}
		.jquery-accordion-menu ul ul.submenu li.active a{
			{$fup_sidebar_submenu_color_hover}
			{$fup_sidebar_submenu_background_color_active}
			{$fup_sidebar_submenu_border_color_hover}
		}
		.jquery-accordion-menu ul ul.submenu li.active a:before{
			{$fup_sidebar_submenu_dot_color_hover}
		}
		
	");
}
// Sorting Panel Styling
$sorting_panel_background = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_background']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_background']['color']))? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_background']['color'] .';'): '';

$sorting_panel_padding = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_padding']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_padding']['padding-top'])) ? ('padding-top:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_padding']['padding-top'].';') : 'padding-top:0;';
$sorting_panel_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_padding']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_padding']['padding-bottom'])) ? ('padding-bottom:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_padding']['padding-bottom'].';') : 'padding-bottom:0;';
$sorting_panel_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_padding']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_padding']['padding-left'])) ? ('padding-left:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_padding']['padding-left'].';') : 'padding-left:0;';
$sorting_panel_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_padding']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_padding']['padding-right'])) ? ('padding-right:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_padding']['padding-right'].';') : 'padding-right:0;';

$sorting_panel_border = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_border']['border-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_border']['border-top'])) ? ('border-top-width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_border']['border-top'].';') : '';
$sorting_panel_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_border']['border-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_border']['border-right'])) ? ('border-right-width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_border']['border-right'] .';') : '';
$sorting_panel_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_border']['border-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_border']['border-bottom'])) ? ('border-bottom-width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_border']['border-bottom'] .';') : '';
$sorting_panel_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_border']['border-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_border']['border-left'])) ? ('border-left-width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_border']['border-left'] .';') : '';
$sorting_panel_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_border']['border-color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_border']['border-color'])) ? ('border-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_border']['border-color'] .';') : '';
$sorting_panel_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_border']['border-style']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_border']['border-style'])) ? ('border-style:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_border']['border-style'] .';') : '';

$sorting_panel_radius = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_radius']['padding-top'])) ? ('border-top-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_radius']['padding-top'].';') : '';
$sorting_panel_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_radius']['padding-bottom'].';') : '';
$sorting_panel_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_radius']['padding-left'])) ? ('border-bottom-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_radius']['padding-left'].';') : '';
$sorting_panel_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_radius']['padding-right'])) ? ('border-top-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_radius']['padding-right'].';') : '';

$result_count_typo = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['result_count_typo']['font-family']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['result_count_typo']['font-family'])) ? ('font-family:' . $DIRECTORYPRESS_ADIMN_SETTINGS['result_count_typo']['font-family'] . ';') : '';
$result_count_typo .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['result_count_typo']['font-size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['result_count_typo']['font-size'])) ? ('font-size:' . $DIRECTORYPRESS_ADIMN_SETTINGS['result_count_typo']['font-size'] . ';') : '';
$result_count_typo .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['result_count_typo']['font-weight']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['result_count_typo']['font-weight'])) ? ('font-weight:' . $DIRECTORYPRESS_ADIMN_SETTINGS['result_count_typo']['font-weight'] . ';') : '';
$result_count_typo .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['result_count_typo']['line-height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['result_count_typo']['line-height'])) ? ('line-height:' . $DIRECTORYPRESS_ADIMN_SETTINGS['result_count_typo']['line-height'] . ';') : '';
$result_count_typo .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['result_count_typo']['color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['result_count_typo']['color'])) ? ('color:' . $DIRECTORYPRESS_ADIMN_SETTINGS['result_count_typo']['color'] . ';') : '';

DirectoryPress_Static_Files::addGlobalStyle("
	.directorypress-listings-block-header{
		{$sorting_panel_background}
		{$sorting_panel_padding}
		{$sorting_panel_border}
		{$sorting_panel_radius}
	}
	.directorypress-listings-block-header .directorypress-found-listings{
		{$result_count_typo}
	}
");

if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_box_shadow'])){
		
	$sorting_panel_box_shadow = $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_box_shadow']['drop-shadow'];
	$sorting_panel_box_shadow_color = $sorting_panel_box_shadow['color'];
	$sorting_panel_box_shadow_horizontal = ($sorting_panel_box_shadow['horizontal'] != 0)? $sorting_panel_box_shadow['horizontal'] .'px' : 0;
	$sorting_panel_box_shadow_vertical = ($sorting_panel_box_shadow['vertical'] != 0)? $sorting_panel_box_shadow['vertical'] .'px' : 0;
	$sorting_panel_box_shadow_blur = ($sorting_panel_box_shadow['blur'] != 0)? $sorting_panel_box_shadow['blur'] .'px' : 0;
	$sorting_panel_box_shadow_spread = ($sorting_panel_box_shadow['spread'] != 0)? $sorting_panel_box_shadow['spread'] .'px' : 0;
	if(!empty($sorting_panel_box_shadow_color)){
		$sorting_panel_box_shadow_css = $sorting_panel_box_shadow_horizontal .' '. $sorting_panel_box_shadow_vertical .' '. $sorting_panel_box_shadow_blur .' '. $sorting_panel_box_shadow_spread .' '. $sorting_panel_box_shadow_color;
		DirectoryPress_Static_Files::addGlobalStyle("
			.directorypress-listings-block-header{
				box-shadow: {$sorting_panel_box_shadow_css};
				-webkit-box-shadow: {$sorting_panel_box_shadow_css};
				-moz-box-shadow: {$sorting_panel_box_shadow_css};
				-o-box-shadow: {$sorting_panel_box_shadow_css};
			}
		");
	}
}

// stwitcher button
$sorting_panel_switch_button_wrapper_padding = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_wrapper_padding']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_wrapper_padding']['padding-top'])) ? ('padding-top:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_wrapper_padding']['padding-top'].';') : '';
$sorting_panel_switch_button_wrapper_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_wrapper_padding']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_wrapper_padding']['padding-top'])) ? ('padding-bottom:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_wrapper_padding']['padding-bottom'].';') : '';
$sorting_panel_switch_button_wrapper_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_wrapper_padding']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_wrapper_padding']['padding-top'])) ? ('padding-left:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_wrapper_padding']['padding-left'].';') : '';
$sorting_panel_switch_button_wrapper_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_wrapper_padding']['padding-right'])) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_wrapper_padding']['padding-top']) ? ('padding-right:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_wrapper_padding']['padding-right'].';') : '';
$sorting_panel_switch_button_wrapper_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_wrapper_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_wrapper_bg']['color'])) ? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_wrapper_bg']['rgba'] .';') : '';


$sorting_panel_switcher_button_dimensions = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_dimensions']['width'])) ? ('width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_dimensions']['width'] .';') : '';
$sorting_panel_switcher_button_dimensions .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_dimensions']['height'])) ? ('height:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_dimensions']['height'] .';') : '';

$sorting_panel_switcher_button_icon_size = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_icon_size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_icon_size'])) ? ('font-size:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_icon_size'] .'px;') : '';

$sorting_panel_switcher_button_spacing = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_spacing'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_spacing'] : 5;

$sorting_panel_switcher_button_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_color']['regular'])) ? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_color']['regular'] .';') : '';
$sorting_panel_switcher_button_color .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_bg']['color'])) ? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_bg']['rgba'] .';') : '';

$sorting_panel_switcher_button_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_color']['hover'])) ? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_color']['hover'] .';') : '';
$sorting_panel_switcher_button_color_hover .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_bg_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_bg_hover']['color'])) ? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_bg_hover']['rgba'] .';') : '';

$sorting_panel_switch_button_radius = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_radius']['padding-top'])) ? ('border-top-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_radius']['padding-top'].';') : '';
$sorting_panel_switch_button_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_radius']['padding-bottom'].';') : '';
$sorting_panel_switch_button_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_radius']['padding-left'])) ? ('border-bottom-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_radius']['padding-left'].';') : '';
$sorting_panel_switch_button_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_radius']['padding-right'])) ? ('border-top-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_radius']['padding-right'].';') : '';

$sorting_panel_switcher_button_border = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border']['border-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border']['border-top'])) ? ('border-top-width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border']['border-top'].';') : '';
$sorting_panel_switcher_button_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border']['border-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border']['border-right'])) ? ('border-right-width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border']['border-right'] .';') : '';
$sorting_panel_switcher_button_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border']['border-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border']['border-bottom'])) ? ('border-bottom-width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border']['border-bottom'] .';') : '';
$sorting_panel_switcher_button_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border']['border-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border']['border-left'])) ? ('border-left-width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border']['border-left'] .';') : '';
$sorting_panel_switcher_button_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border_color']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border_color']['color'])) ? ('border-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border_color']['rgba'] .';') : '';
$sorting_panel_switcher_button_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border']['border-style']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border']['border-style'])) ? ('border-style:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border']['border-style'] .';') : '';

$sorting_panel_switcher_button_border_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border_color_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border_color_hover']['color'])) ? ('border-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_border_color_hover']['rgba'].';') : '';

if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_shadow'])){
		
	$sorting_panel_switcher_button_box_shadow = $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switch_button_shadow']['drop-shadow'];
	$sorting_panel_switcher_button_box_shadow_color = $sorting_panel_switcher_button_box_shadow['color'];
	$sorting_panel_switcher_button_box_shadow_horizontal = ($sorting_panel_switcher_button_box_shadow['horizontal'] != 0)? $sorting_panel_switcher_button_box_shadow['horizontal'] .'px' : 0;
	$sorting_panel_switcher_button_box_shadow_vertical = ($sorting_panel_switcher_button_box_shadow['vertical'] != 0)? $sorting_panel_switcher_button_box_shadow['vertical'] .'px' : 0;
	$sorting_panel_switcher_button_box_shadow_blur = ($sorting_panel_switcher_button_box_shadow['blur'] != 0)? $sorting_panel_switcher_button_box_shadow['blur'] .'px' : 0;
	$sorting_panel_switcher_button_box_shadow_spread = ($sorting_panel_switcher_button_box_shadow['spread'] != 0)? $sorting_panel_switcher_button_box_shadow['spread'] .'px' : 0;
	if(!empty($sorting_panel_switcher_button_box_shadow_color)){
		$sorting_panel_switcher_button_box_shadow_css = $sorting_panel_switcher_button_box_shadow_horizontal .' '. $sorting_panel_switcher_button_box_shadow_vertical .' '. $sorting_panel_switcher_button_box_shadow_blur .' '. $sorting_panel_switcher_button_box_shadow_spread .' '. $sorting_panel_switcher_button_box_shadow_color;
		DirectoryPress_Static_Files::addGlobalStyle("
			.directorypress-listings-block-header .directorypress-grid-view-btn,
			.directorypress-listings-block-header .directorypress-list-view-btn{
				box-shadow: {$sorting_panel_switcher_button_box_shadow_css};
				-webkit-box-shadow: {$sorting_panel_switcher_button_box_shadow_css};
				-moz-box-shadow: {$sorting_panel_switcher_button_box_shadow_css};
				-o-box-shadow: {$sorting_panel_switcher_button_box_shadow_css};
			}
		");
	}
}
DirectoryPress_Static_Files::addGlobalStyle("
	.directorypress-listings-block-header .directorypress-views-links .btn-group{
		{$sorting_panel_switch_button_wrapper_padding}
		{$sorting_panel_switch_button_wrapper_bg}
	}
	.directorypress-listings-block-header .directorypress-grid-view-btn,
	.directorypress-listings-block-header .directorypress-list-view-btn{
		{$sorting_panel_switcher_button_dimensions}
		{$sorting_panel_switcher_button_color}
		{$sorting_panel_switch_button_radius}
		{$sorting_panel_switcher_button_border}
		{$sorting_panel_switcher_button_icon_size}
	}
	.directorypress-listings-block-header .directorypress-list-view-btn{
		margin-right:{$sorting_panel_switcher_button_spacing}px;
	}
	.rtl .directorypress-listings-block-header .directorypress-list-view-btn{
		margin-left:{$sorting_panel_switcher_button_spacing}px;
		margin-right:0;
	}
	.directorypress-listings-block-header .in-active.directorypress-grid-view-btn:hover,
	.directorypress-listings-block-header .in-active.directorypress-list-view-btn:hover,
	.directorypress-listings-block-header .active.directorypress-grid-view-btn,
	.directorypress-listings-block-header .active.directorypress-list-view-btn{
		{$sorting_panel_switcher_button_color_hover}
		{$sorting_panel_switcher_button_border_color_hover}
	}	
");


// sorting dropbox

$sorting_selectbox_wrapper_padding = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_wrapper_padding']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_wrapper_padding']['padding-top'])) ? ('padding-top:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_wrapper_padding']['padding-top'].';') : '';
$sorting_selectbox_wrapper_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_wrapper_padding']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_wrapper_padding']['padding-bottom'])) ? ('padding-bottom:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_wrapper_padding']['padding-bottom'].';') : '';
$sorting_selectbox_wrapper_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_wrapper_padding']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_wrapper_padding']['padding-left'])) ? ('padding-left:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_wrapper_padding']['padding-left'].';') : '';
$sorting_selectbox_wrapper_padding .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_wrapper_padding']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_wrapper_padding']['padding-right'])) ? ('padding-right:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_wrapper_padding']['padding-right'].';') : '';
$sorting_selectbox_wrapper_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_wrapper_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_wrapper_bg']['color'])) ? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_wrapper_bg']['rgba'] .';') : '';

$sorting_selectbox_width = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_dimensions']['width']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_dimensions']['width'])) ? ('width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_dimensions']['width'] .';') : '';
$sorting_selectbox_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_dimensions']['height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_dimensions']['height'])) ? ('height:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_dimensions']['height'] .';') : '';

$sorting_selectbox_line_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_dimensions']['height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_dimensions']['height'])) ? ('line-height:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_dimensions']['height'] .';') : '';

$sorting_dropbox_border = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border']['border-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border']['border-top'])) ? ('border-top-width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border']['border-top'].';') : '';
$sorting_dropbox_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border']['border-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border']['border-right'])) ? ('border-right-width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border']['border-right'] .';') : '';
$sorting_dropbox_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border']['border-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border']['border-bottom'])) ? ('border-bottom-width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border']['border-bottom'] .';') : '';
$sorting_dropbox_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border']['border-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border']['border-left'])) ? ('border-left-width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border']['border-left'] .';') : '';
$sorting_dropbox_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border_color']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border_color']['color'])) ? ('border-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border_color']['rgba'] .';') : '';
$sorting_dropbox_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border']['border-style']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border']['border-style'])) ? ('border-style:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border']['border-style'] .';') : '';

$sorting_dropbox_border_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border_hover']['color'])) ? ('border-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border_hover']['rgba'] .';') : '';

$sorting_selectbox_border_radius = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border_radius']['padding-top'])) ? ('border-top-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border_radius']['padding-top'].';') : '';
$sorting_selectbox_border_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border_radius']['padding-bottom'].';') : '';
$sorting_selectbox_border_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border_radius']['padding-left'])) ? ('border-bottom-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border_radius']['padding-left'].';') : '';
$sorting_selectbox_border_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border_radius']['padding-right'])) ? ('border-top-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_border_radius']['padding-right'].';') : '';

$sorting_selectbox_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_bg']['color'])) ? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_bg']['rgba'] .';') : '';
$sorting_selectbox_bg_focus = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_bg_focus']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_bg_focus']['color'])) ? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_bg_focus']['rgba'] .';') : '';

$sorting_selectbox_icon_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_color']['regular']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_color']['regular'])) ? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_color']['regular'] .';') : '';
$sorting_selectbox_icon_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_bg']['color'])) ? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_bg']['rgba'] .';') : '';

$sorting_selectbox_icon_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_color']['hover']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_color']['hover'])) ? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_color']['hover'] .';') : '';
$sorting_selectbox_icon_bg_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_bg_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_bg_hover']['color'])) ? ('background-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_bg_hover']['rgba'] .';') : '';

$sorting_selectbox_icon_border = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border']['border-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border']['border-top'])) ? ('border-top-width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border']['border-top'].';') : '';
$sorting_selectbox_icon_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border']['border-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border']['border-right'])) ? ('border-right-width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border']['border-right'] .';') : '';
$sorting_selectbox_icon_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border']['border-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border']['border-bottom'])) ? ('border-bottom-width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border']['border-bottom'] .';') : '';
$sorting_selectbox_icon_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border']['border-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border']['border-left'])) ? ('border-left-width:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border']['border-left'] .';') : '';
$sorting_selectbox_icon_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border_color']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border_color']['color'])) ? ('border-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border_color']['rgba'] .';') : '';
$sorting_selectbox_icon_border .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border']['border-style']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border']['border-style'])) ? ('border-style:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border']['border-style'] .';') : '';

$sorting_selectbox_icon_border_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border_color_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border_color_hover']['color'])) ? ('border-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border_color_hover']['rgba'] .';') : '';

$sorting_selectbox_icon_border_radius = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border_radius']['padding-top'])) ? ('border-top-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border_radius']['padding-top'].';') : '';
$sorting_selectbox_icon_border_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border_radius']['padding-bottom'].';') : '';
$sorting_selectbox_icon_border_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border_radius']['padding-left'])) ? ('border-bottom-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border_radius']['padding-left'].';') : '';
$sorting_selectbox_icon_border_radius .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border_radius']['padding-right'])) ? ('border-top-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_icon_border_radius']['padding-right'].';') : '';

if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_box_shadow'])){
		
	$sorting_dropbox_box_shadow = $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_selectbox_box_shadow']['drop-shadow'];
	$sorting_dropbox_box_shadow_color = $sorting_dropbox_box_shadow['color'];
	$sorting_dropbox_box_shadow_horizontal = ($sorting_dropbox_box_shadow['horizontal'] != 0)? $sorting_dropbox_box_shadow['horizontal'] .'px' : 0;
	$sorting_dropbox_box_shadow_vertical = ($sorting_dropbox_box_shadow['vertical'] != 0)? $sorting_dropbox_box_shadow['vertical'] .'px' : 0;
	$sorting_dropbox_box_shadow_blur = ($sorting_dropbox_box_shadow['blur'] != 0)? $sorting_dropbox_box_shadow['blur'] .'px' : 0;
	$sorting_dropbox_box_shadow_spread = ($sorting_dropbox_box_shadow['spread'] != 0)? $sorting_dropbox_box_shadow['spread'] .'px' : 0;
	if(!empty($sorting_dropbox_box_shadow_color)){
		$sorting_dropbox_box_shadow_css = $sorting_dropbox_box_shadow_horizontal .' '. $sorting_dropbox_box_shadow_vertical .' '. $sorting_dropbox_box_shadow_blur .' '. $sorting_dropbox_box_shadow_spread .' '. $sorting_dropbox_box_shadow_color;
		DirectoryPress_Static_Files::addGlobalStyle("
			.directorypress-orderby-links .select2-container--default .select2-selection--single{
				box-shadow: {$sorting_dropbox_box_shadow_css};
				-webkit-box-shadow: {$sorting_dropbox_box_shadow_css};
				-moz-box-shadow: {$sorting_dropbox_box_shadow_css};
				-o-box-shadow: {$sorting_dropbox_box_shadow_css};
			}
		");
	}
}
DirectoryPress_Static_Files::addGlobalStyle("
	.directorypress-listings-block-header .directorypress-orderby-links{
		{$sorting_selectbox_wrapper_padding}
		{$sorting_selectbox_wrapper_bg}
	}
	.directorypress-orderby-links .select2-container--default .select2-selection--single{
		{$sorting_selectbox_width}
		{$sorting_selectbox_height}
		{$sorting_selectbox_bg}
		{$sorting_dropbox_border}
		{$sorting_selectbox_border_radius}
	}
	.directorypress-orderby-links .select2-container--default.select2-container--open .select2-selection--single{
		{$sorting_selectbox_bg_focus}
	}
	.directorypress-orderby-links .select2-container--default .select2-selection--single .select2-selection__rendered{
		
		{$sorting_selectbox_line_height}
	}
	.directorypress-orderby-links .select2-container--default.select2-container--open .select2-selection--single{
		{$sorting_dropbox_border_color_hover}
	}
	.directorypress-orderby-links .select2-container--default .select2-selection--single .select2-selection__arrow{
		{$sorting_selectbox_height}
		{$sorting_selectbox_icon_color}
		{$sorting_selectbox_icon_bg}
		{$sorting_selectbox_icon_border}
		{$sorting_selectbox_icon_border_radius}
	}
	.directorypress-orderby-links .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow{
		{$sorting_selectbox_icon_color_hover}
		{$sorting_selectbox_icon_bg_hover}
		{$sorting_selectbox_icon_border_color_hover}
	}
");

// custom icons
$single_listing_meta_id_icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_id_icon_size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_id_icon_size']))? ('font-size:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_id_icon_size'] .'px;'):'';
$single_listing_meta_id_icon .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_id_icon_line_height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_id_icon_line_height']))? ('line-height:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_id_icon_line_height'] .'px;'):'';
$single_listing_meta_id_icon .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_id_icon_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_id_icon_color']))? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_id_icon_color'] .';'):'';

$single_listing_meta_date_icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_date_icon_size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_date_icon_size']))? ('font-size:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_date_icon_size'] .'px;'):'';
$single_listing_meta_date_icon .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_date_icon_line_height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_date_icon_line_height']))? ('line-height:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_date_icon_line_height'] .'px;'):'';
$single_listing_meta_date_icon .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_date_icon_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_date_icon_color']))? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_date_icon_color'] .';'):'';

$single_listing_meta_views_icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_views_icon_size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_views_icon_size']))? ('font-size:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_views_icon_size'] .'px;'):'';
$single_listing_meta_views_icon .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_views_icon_line_height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_views_icon_line_height']))? ('line-height:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_views_icon_line_height'] .'px;'):'';
$single_listing_meta_views_icon .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_views_icon_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_views_icon_color']))? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_meta_views_icon_color'] .';'):'';

$single_listing_meta_report_button_icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_report_icon_size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_report_icon_size']))? ('font-size:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_report_icon_size'] .'px;'):'';
$single_listing_meta_report_button_icon .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_report_icon_line_height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_button_report_views_icon_line_height']))? ('line-height:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_report_icon_line_height'] .'px;'):'';
$single_listing_meta_report_button_icon .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_report_icon_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_report_icon_color']))? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_report_icon_color'] .';'):'';

$single_listing_meta_download_button_icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_download_icon_size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_download_icon_size']))? ('font-size:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_download_icon_size'] .'px;'):'';
$single_listing_meta_download_button_icon .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_download_icon_line_height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_download_icon_line_height']))? ('line-height:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_download_icon_line_height'] .'px;'):'';
$single_listing_meta_download_button_icon .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_download_icon_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_download_icon_color']))? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_download_icon_color'] .';'):'';

$single_listing_meta_print_button_icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_print_icon_size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_print_icon_size']))? ('font-size:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_print_icon_size'] .'px;'):'';
$single_listing_meta_print_button_icon .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_print_icon_line_height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_print_icon_line_height']))? ('line-height:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_print_icon_line_height'] .'px;'):'';
$single_listing_meta_print_button_icon .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_print_icon_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_print_icon_color']))? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_print_icon_color'] .';'):'';

$single_listing_meta_share_button_icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_share_icon_size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_share_icon_size']))? ('font-size:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_share_icon_size'] .'px;'):'';
$single_listing_meta_share_button_icon .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_share_icon_line_height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_share_icon_line_height']))? ('line-height:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_share_icon_line_height'] .'px;'):'';
$single_listing_meta_share_button_icon .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_share_icon_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_share_icon_color']))? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_share_icon_color'] .';'):'';

$single_listing_meta_edit_button_icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_edit_icon_size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_edit_icon_size']))? ('font-size:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_edit_icon_size'] .'px;'):'';
$single_listing_meta_edit_button_icon .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_edit_icon_line_height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_edit_icon_line_height']))? ('line-height:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_edit_icon_line_height'] .'px;'):'';
$single_listing_meta_edit_button_icon .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_edit_icon_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_edit_icon_color']))? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_edit_icon_color'] .';'):'';

$single_listing_meta_bookmark_button_icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon_size']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon_size']))? ('font-size:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon_size'] .'px;'):'';
$single_listing_meta_bookmark_button_icon .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon_line_height']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon_line_height']))? ('line-height:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon_line_height'] .'px;'):'';
$single_listing_meta_bookmark_button_icon .= (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon_color']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon_color']))? ('color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_bookmark_icon_color'] .';'):'';


DirectoryPress_Static_Files::addGlobalStyle("
	.listing-metas-single .listing-id i{
		{$single_listing_meta_id_icon}
	}
	.listing-metas-single .directorypress-listing-date i{
		{$single_listing_meta_date_icon}
	}
	.listing-metas-single .listing-views i{
		{$single_listing_meta_views_icon}
	}
	
	.single-listing-btns a.report-button i{
		{$single_listing_meta_report_button_icon}
	}
	.single-listing-btns a.download-button i{
		{$single_listing_meta_download_button_icon}
	}
	.single-listing-btns a.print-button i{
		{$single_listing_meta_print_button_icon}
	}
	.single-listing-btns a.share-button i{
		{$single_listing_meta_share_button_icon}
	}
	.single-listing-btns a.edit-button i{
		{$single_listing_meta_edit_button_icon}
	}
	.single-listing-btns a.bookmark-button i{
		{$single_listing_meta_bookmark_button_icon}
	}

");

// embed custom styling

// User dashboard custom styling
DirectoryPress_Static_Files::addGlobalStyle("
	.dashboard-wrapper .jquery-accordion-menu > ul > li > a.parent-menu-link .badge {
	  background: {$directorypress_primary_color};
	}
	.dpfl-dashboad-button:hover {
	  background: {$directorypress_primary_color};
	}
	.profile-img-inner .dpfl-user-profile-photo .choose-author-image i {
	  color: {$directorypress_primary_color};
	}
	.woocommerce-MyAccount-content .woocommerce-pagination a.woocommerce-button:hover{
		background: {$directorypress_primary_color};
	}

");
	

do_action('directorypress_after_dynamic_style');