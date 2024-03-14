<?php
// options filters

add_filter("directorypress_map_type_option" , "directorypress_map_type_setting");
add_filter("directorypress_mapbox_api_option" , "directorypress_mapbox_api");
add_filter("directorypress_mapbox_styles_option" , "directorypress_mapbox_styles_settings");

// listing styles
add_filter("directorypress_listing_grid_styles" , "directorypress_listing_grid_styles_fuction");
add_filter('directorypress_after_listing_post_style_settings', 'directorypress_after_listing_post_style_function', 10);
add_filter("directorypress_listing_grid_styles_featured_tags" , "directorypress_listing_grid_styles_featured_tags_function");

add_filter("directorypress_listing_list_styles" , "directorypress_listing_list_styles_fuction");

add_filter("directorypress_listing_grid_styles_vc" , "directorypress_listing_grid_styles_vc_function");
add_filter("directorypress_listing_grid_styles_featured_tags_vc" , "directorypress_listing_grid_styles_featured_tags_vc_function");
add_filter("directorypress_listing_widget_grid_styles", "directorypress_listing_widget_grid_styles_function");


// sorting styles
add_filter("directorypress_listing_sorting_style_option" , "directorypress_listing_sorting_styles");

// single listing styles
add_filter("directorypress_listing_single_style_option" , "directorypress_listing_single_styles");

// achive layout styles
add_filter("directorypress_archive_page_style_option" , "directorypress_archive_page_styles");

// pricing plan styles
add_filter("directorypress_pricing_plan_style_option" , "directorypress_pricing_plan_styles_function");

// categories styles

add_filter("directorypress_categories_styles" , "directorypress_categories_styles_function");
add_filter("directorypress_categories_depth_conditions" , "directorypress_categories_depth_conditions_function");
add_filter("directorypress_categories_styles_vc" , "directorypress_categories_styles_vc_function");
add_filter("directorypress_locations_styles" , "directorypress_locations_styles_function");
add_filter("directorypress_locations_styles_vc" , "directorypress_locations_styles_vc_function");

// option filter callback functions

function directorypress_map_type_setting(){
	$value = array(
		'type' => 'select',
		'id' => 'directorypress_map_type',
		'title' => __('Map Type', 'DIRECTORYPRESS'),
		'options' => array(
			'google' => __('Google Map', 'DIRECTORYPRESS'),
			'mapbox' => __('MapBox Map', 'DIRECTORYPRESS'),
		),
		'default' => 'google',
	);
	return $value;
}

function directorypress_mapbox_api(){
	$value = array(
		'type' => 'text',
		'id' => 'directorypress_mapbox_api_key',
		'title' => __('MapBox API key', 'DIRECTORYPRESS'),
		'default' => '',
	);
	return $value;
}

function directorypress_mapbox_styles_settings(){
	$mapbox_map_style = array();
	foreach (directorypress_map_styles() AS $name=>$style) {
		$mapbox_map_style[] = array('value' => $style, 'label' => $name);
	}
	
	$mapbox_map_styles = array();
	foreach($mapbox_map_style as $listItem) {
		$mapbox_map_styles[$listItem['value']] = $listItem['label'];
	}
	
	$value = array(
		'type' => 'select',
		'id' => 'directorypress_mapbox_map_style',
		'title' => __('MapBox Maps style', 'DIRECTORYPRESS'),
		'options' => $mapbox_map_styles,
		'default' => 'default',
	);
	return $value;
}

function directorypress_listing_grid_styles_fuction(){
	$styles = array('default' => 'Defualt');
	$styles = apply_filters('directorypress_listing_gridview_styles', $styles);
	return $styles;
}
function directorypress_after_listing_post_style_function(){
	
}

function directorypress_listing_list_styles_fuction(){
	$styles = array('listview_default' => 'Defualt');
	$styles = apply_filters('directorypress_listing_listview_styles', $styles);
	return $styles;
}

function directorypress_listing_grid_styles_featured_tags_function(){
	$styles = array('1' => 'Defualt');
	$styles = apply_filters('directorypress_listing_gridview_styles_featured_tags', $styles);
	return $styles;
}

function directorypress_listing_sorting_styles(){
	
	$styles = array(
		'1' => __('style 1 Default', 'DIRECTORYPRESS'),
	);
	$styles = apply_filters('directorypress_sorting_panel_styles', $styles);
	return $styles;
}

function directorypress_listing_single_styles(){
	$styles = array(
		'default' => __('Default', 'DIRECTORYPRESS'),
	);
	$styles = apply_filters('directorypress_single_listing_styles', $styles);
	return $styles;
}

function directorypress_archive_page_styles(){
	$styles = array(
		'1' => __('No Sidebar', 'DIRECTORYPRESS'),
		'2' => __('Sidebar', 'DIRECTORYPRESS'),
	);
	$styles = apply_filters('directorypress_archive_page_styles', $styles);
	return $styles;
}

function directorypress_pricing_plan_styles_function(){
	$styles = array(
		'pplan-style-1' => __('style 1 Default', 'DIRECTORYPRESS'),
	);
	$styles = apply_filters('directorypress_pricing_plan_styles', $styles);
	return $styles;
}

// categories

function directorypress_categories_styles_function(){
	$styles = array('default' => 'Defualt');
	$styles = apply_filters('directorypress_category_styles', $styles);
	return $styles;
}
function directorypress_categories_styles_vc_function(){
	return array_flip(directorypress_categories_styles_function());
}
function directorypress_categories_depth_conditions_function(){
	$styles = array('default');
	$styles = apply_filters('directorypress_category_styles_has_depth', $styles);
	//$new_styles = array();
	//foreach($styles AS $key=>$value){
		//$new_styles[] = $key;
	//}
	//var_dump($new_styles);
	return $styles;
}

// Locations

function directorypress_locations_styles_function(){
	$styles = array('default' => 'Defualt', 'custom' => 'Custom');
	$styles = apply_filters('directorypress_location_styles', $styles);
	return $styles;
}
function directorypress_locations_styles_vc_function(){
	return array_flip(directorypress_locations_styles_function());
}

// vc args

function directorypress_listing_grid_styles_vc_function(){
	
	return array_flip(directorypress_listing_grid_styles_fuction());
}
function directorypress_listing_grid_styles_featured_tags_vc_function(){
	
	return array_flip(directorypress_listing_grid_styles_featured_tags_function());
}

// widget

function directorypress_listing_widget_grid_styles_function(){
	
	return array_flip(directorypress_listing_grid_styles_fuction());
}


