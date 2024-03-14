<?php 

function wsgf_load_font_family(){
	$font_family = array("wsgf_select_gfont_h1"=>get_option('wsgf_select_gfont_h1'), 
	"wsgf_select_gfont_h2"=>get_option('wsgf_select_gfont_h2'), 
	"wsgf_select_gfont_h3"=>get_option('wsgf_select_gfont_h3'), 
	"wsgf_select_gfont_h4"=>get_option('wsgf_select_gfont_h4'), 
	"wsgf_select_gfont_h5"=>get_option('wsgf_select_gfont_h5'),
	"wsgf_select_gfont_h6"=>get_option('wsgf_select_gfont_h6'),
	"wsgf_select_gfont_p"=>get_option('wsgf_select_gfont_p'),
	"wsgf_select_gfont_link"=>get_option('wsgf_select_gfont_link'),
	"wsgf_select_gfont_ol"=>get_option('wsgf_select_gfont_ol'),
	"wsgf_select_gfont_ul"=>get_option('wsgf_select_gfont_ul'),
	"wsgf_select_gfont_sp"=>get_option('wsgf_select_gfont_sp'),
	"wsgf_select_gfont_abbr"=>get_option('wsgf_select_gfont_abbr'),
	"wsgf_select_gfont_address"=>get_option('wsgf_select_gfont_address'),
	"wsgf_select_gfont_blockquote"=>get_option('wsgf_select_gfont_blockquote'),
	"wsgf_select_gfont_caption"=>get_option('wsgf_select_gfont_caption'),
	"wsgf_select_gfont_time"=>get_option('wsgf_select_gfont_time'),
	);
	$filtered_font_family = array_unique($font_family);
	
	$result = array_values($filtered_font_family);
	$arrayCount = count($result);
	$array="";
	for ($i=0; $i < $arrayCount ; $i++) { 
		$array[$i] = str_replace(" ","+",$result[$i]);
	}
	
	for ($i=0; $i < $arrayCount ; $i++) { 
		echo "<style>@import url(https://fonts.googleapis.com/css?family=" . $array[$i] . ")</style>";
	}
}

function wsgf_h1(){
	$font = str_replace("+", " ", get_option('wsgf_select_gfont_h1'));
	
	echo "<style>h1 { font-family : '" . $font . "' !important; color :  " . get_option('wsgf_select_color_h1') . " !important;}</style>";
	
}
function wsgf_h2(){
	$font = str_replace("+", " ", get_option('wsgf_select_gfont_h2'));
	
	echo "<style>h2 { font-family : '" . $font . "' !important;  color :  " . get_option('wsgf_select_color_h2') . " !important; }</style>";
	
}
function wsgf_h3(){
	$font = str_replace("+", " ", get_option('wsgf_select_gfont_h3'));
	
	echo "<style>h3 { font-family : '" . $font . "' !important;  color :  " . get_option('wsgf_select_color_h3') . " !important;}</style>";
	
}
function wsgf_h4(){
	$font = str_replace("+", " ", get_option('wsgf_select_gfont_h4'));
	
	echo "<style>h4 { font-family : '" .  $font. "' !important;  color :  " . get_option('wsgf_select_color_h4') . " !important;}</style>";
	
}
function wsgf_h5(){
	$font = str_replace("+", " ", get_option('wsgf_select_gfont_h5'));
	
	echo "<style>h5 { font-family : '" . $font . "' !important;  color :  " . get_option('wsgf_select_color_h5') . " !important;}</style>";
	
}
function wsgf_h6(){
	$font = str_replace("+", " ", get_option('wsgf_select_gfont_h6'));
	
	echo "<style>h6 { font-family : '" .  $font . "' !important;  color :  " . get_option('wsgf_select_color_h6') . " !important;}</style>";
	
}
function wsgf_p(){
	$font = str_replace("+", " ", get_option('wsgf_select_gfont_p'));
	
	echo "<style>p { font-family : '" . $font . "' !important;  color :  " . get_option('wsgf_select_color_p') . " !important;}</style>";
	
}
function wsgf_link(){
	$font = str_replace("+", " ", get_option('wsgf_select_gfont_link'));
	
	echo "<style>a { font-family : '" . $font. "'  !important; color :  " . get_option('wsgf_select_color_link') . " !important;}</style>";
	
}
function wsgf_ol(){
	$font = str_replace("+", " ", get_option('wsgf_select_gfont_ol'));
	
	echo "<style>ol li { font-family : '" . $font . "' !important; color :  " . get_option('wsgf_select_color_ol') . " !important;}</style>";
	
}
function wsgf_ul(){
	$font = str_replace("+", " ", get_option('wsgf_select_gfont_ul'));
	
	echo "<style>ul li { font-family : '" .  $font . "' !important; color :  " . get_option('wsgf_select_color_ul') . " !important;}</style>";
	
}
function wsgf_sp(){
	$font = str_replace("+", " ", get_option('wsgf_select_gfont_sp'));
	
	echo "<style>span { font-family : '" . $font. "' !important; color :  " . get_option('wsgf_select_color_sp') . " !important;}</style>";
	
}
function wsgf_abbr(){
	$font = str_replace("+", " ", get_option('wsgf_select_gfont_abbr'));
	
	echo "<style>abbr { font-family : '" .  $font . "' !important; color :  " . get_option('wsgf_select_color_abbr') . " !important;}</style>";
	
}
function wsgf_address(){
	$font = str_replace("+", " ", get_option('wsgf_select_gfont_address'));
	
	echo "<style>address { font-family : '" . $font . "' !important; color :  " . get_option('wsgf_select_color_address') . " !important;}</style>";
	
}
function wsgf_blockquote(){
	$font = str_replace("+", " ", get_option('wsgf_select_gfont_blockquote'));

	echo "<style>blockquote  { font-family : '" . $font . "' !important; color :  " . get_option('wsgf_select_color_blockquote') . " !important;}</style>";

}
function wsgf_caption(){
	$font = str_replace("+", " ", get_option('wsgf_select_gfont_caption'));
	
	echo "<style>caption { font-family : '" .  $font . "' !important; color :  " . get_option('wsgf_select_color_caption') . " !important;}</style>";
	
}
function wsgf_time(){
	$font = str_replace("+", " ", get_option('wsgf_select_gfont_time'));
	
	echo "<style>time { font-family : '" .  $font . "' !important; color :  " . get_option('wsgf_select_color_time') . " !important;}</style>";
	
}
function wsgf_figure(){
	$font = str_replace("+", " ", get_option('wsgf_select_gfont_figure'));
	
	echo "<style>figure { font-family : '" . get_option('wsgf_select_gfont_figure') . "' !important; color :  " . get_option('wsgf_select_color_figure') . " !important;}</style>";
	
}



add_action("wp_head","wsgf_load_font_family");
add_action("wp_head","wsgf_h1");
add_action("wp_head","wsgf_h2");
add_action("wp_head","wsgf_h3");
add_action("wp_head","wsgf_h4");
add_action("wp_head","wsgf_h5");
add_action("wp_head","wsgf_h6");
add_action("wp_head","wsgf_p");
add_action("wp_head","wsgf_link");
add_action("wp_head","wsgf_sp");
add_action("wp_head","wsgf_ol");
add_action("wp_head","wsgf_ul");
add_action("wp_head","wsgf_abbr");
add_action("wp_head","wsgf_address");
add_action("wp_head","wsgf_blockquote");
add_action("wp_head","wsgf_caption");
add_action("wp_head","wsgf_time");
add_action("wp_head","wsgf_figure");

