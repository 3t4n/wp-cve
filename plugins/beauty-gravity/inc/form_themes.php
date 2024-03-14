<?php


$formThemes           = ["false"];
$add_tooltip          = false;
//empty generated style
$upload_dir           = wp_upload_dir();
$file                 = $upload_dir["basedir"].'/beauty_gravity';

// Get current form css
function sibg_GetThemesSettings($form_id){
	$css         = "";
    $compiledCSS = "";
	
	$instanse       = new sibg_frontend();
    $customSettings = $instanse->GetCustomizeSetting($form_id);
    $form_theme     = isset($customSettings["form_theme"])    ? $customSettings["form_theme"]    : "Default";
    $main_color     = isset($customSettings["main_color"])    ? $customSettings["main_color"]    : "#0389ff";
	$font_color     = isset($customSettings["font_color"])    ? $customSettings["font_color"]    : "#000";
    $themeType      = isset($customSettings["theme_type"])    ? $customSettings["theme_type"]    : "Light";
    $tooltipClass   = isset($customSettings["tooltip_class"]) ? $customSettings["tooltip_class"] : "None";
    $fontName       = isset($customSettings["font_name"])     ? $customSettings["font_name"]     : "Default";
    $fontName       = str_replace("+","_",$fontName);
    $fontType       = isset($customSettings["font_type"]) ? $customSettings["font_type"] : "sans-serif";
    include(SIBG_CSS_DIR.'tooltip.php');
	$tooltipCSS     = $SIBG_free_tooltip;
    $tooltipCSS    .= apply_filters("bg_tooltip_css",$tooltipCSS);

	if (is_rtl()){
		include(SIBG_CSS_DIR.'themes/BG_themes_color_rtl.php');
		$css = $SIBG_free_css;
	}else{
		include(SIBG_CSS_DIR.'themes/BG_themes_color.php');
		$css = $SIBG_free_css;
	}
    $css = apply_filters( 'bg_theme_css', $css );

    if ($fontName != "Default" && $fontName != "") {
        $compiledCSS .= ".BG_" . "{$fontName}" . "_font{font-family:'" . "{$fontName}" . "'," . $fontType . " !important}";
    }

    if ($form_theme != "Default"){
        $compiledCSS .= sibg_GetThemeCSS($form_theme,$form_id,$main_color,$themeType,$css,$font_color);
    }
    if ($tooltipClass != "None"){
        $compiledCSS .= sibg_GetTooltipCss($tooltipCSS,$tooltipClass,$main_color);
    }

    $compiledCSS  = sibg_minimizeCSS($compiledCSS);

	return $compiledCSS;
}

// get tooltip css
function sibg_GetTooltipCss($tooltipCSS,$tooltipClass,$main_color){
    global $add_tooltip;

    if ($add_tooltip !== true){
        $startGeneral  = strpos($tooltipCSS,"/*region general*/");
        $finishGeneral = strpos($tooltipCSS,"/*endregion*/") + strlen("/*endregion*/");
        $generalLength = $finishGeneral - $startGeneral;
        $compiledCSS   = substr($tooltipCSS, $startGeneral, $generalLength);
        $startTooltip  = strpos($tooltipCSS,"/*region ".$tooltipClass);
        $finishTooltip = strpos($tooltipCSS,"/*endregion*/", $startTooltip) + strlen("/*endregion*/");
        $tooltipLength = $finishTooltip - $startTooltip;
        $compiledCSS  .= substr($tooltipCSS, $startTooltip, $tooltipLength);
        $add_tooltip   = true;
    }else{
        $startTooltip  = strpos($tooltipCSS,"/*region ".$tooltipClass);
        $finishTooltip = strpos($tooltipCSS,"/*endregion*/", $startTooltip) + strlen("/*endregion*/");
        $tooltipLength = $finishTooltip - $startTooltip;
        $compiledCSS   = substr($tooltipCSS, $startTooltip, $tooltipLength);
    }
    if ($tooltipClass == "BG_tooltip_9"){
        $compiledCSS = str_replace("var(--tooltip-border-color)",$main_color,$compiledCSS);
    }
    return $compiledCSS;
}


// Get form theme css
function sibg_GetThemeCSS($form_theme,$form_id,$main_color,$themeType,$css,$font_color){

    $start = strpos($css,"/*region ".$form_theme);
	$startColor    = strpos($css,"/*region color*/",$start);
	$finishColor   = strpos($css,"/*endregion*/",$startColor) + strlen("/*endregion*/");
	$finish        = strpos($css,"/*endregion*/",$finishColor) + strlen("/*endregion*/");
	$lengthColor   = $finish - $startColor;
	$colorCSS      = substr($css,$startColor,$lengthColor);
	$compiledCSS   = str_replace("[form_id]",$form_id,$colorCSS);
	$compiledCSS   = sibg_GetThemeColor($form_theme,$compiledCSS,$main_color,$themeType,$font_color);
    return $compiledCSS;
}

// Get color style
function sibg_GetThemeColor($form_theme,$compiledCSS,$main_color,$themeType,$font_color){
    $compiledCSS = str_replace("var(--option-background-color-dark)", "#363636",$compiledCSS);
    $compiledCSS = str_replace("var(--input-background-color)", "#ffffff36",$compiledCSS);
    $compiledCSS = str_replace("var(--error-color)", "red",$compiledCSS);
	$compiledCSS = str_replace("var(--form-main-color)", $font_color,$compiledCSS);

    if ($form_theme == "BG_Microsoft") {
        $compiledCSS = str_replace("var(--microsoft-color-primary-dark)", sibg_adjustBrightness($main_color, "-0.2"),$compiledCSS);
        $compiledCSS = str_replace("var(--microsoft-color-primary)", $main_color,$compiledCSS);
        $compiledCSS = str_replace("var(--microsoft-border-color)", "rgb(138, 136, 134)",$compiledCSS);
        $compiledCSS = str_replace("var(--input-background-color)", "#ffffff36",$compiledCSS);
    }else{
        $compiledCSS = apply_filters("bg_theme_color", $form_theme, $compiledCSS, $main_color, $themeType);
    }
    return $compiledCSS;
}

/**
 * Increases or decreases the brightness of a color by a percentage of the current brightness.
 *
 * @param   string  $hexCode        Supported formats: `#FFF`, `#FFFFFF`, `FFF`, `FFFFFF`
 * @param   float   $adjustPercent  A number between -1 and 1. E.g. 0.3 = 30% lighter; -0.4 = 40% darker.
 *
 * @return  string
 */
function sibg_adjustBrightness($hexCode, $adjustPercent) {
    $hexCode = ltrim($hexCode, '#');

    if (strlen($hexCode) == 3) {
        $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
    }

    $hexCode = array_map('hexdec', str_split($hexCode, 2));

    foreach ($hexCode as & $color) {
        $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
        $adjustAmount    = ceil($adjustableLimit * $adjustPercent);

        $color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
    }

    return '#' . implode($hexCode);
}

function sibg_color_triadic($color){
    $color = str_replace('#', '', $color);
    $hexCode = str_split($color, 2);
    return '#'.$hexCode[2].$hexCode[0].$hexCode[1];
}

/* Convert hexdec color string to rgb(a) string */

function sibg_hex2rgba($color, $opacity = false) {

    $default = 'rgb(0,0,0)';

    //Return default if no color provided
    if(empty($color))
        return $default;

    //Sanitize $color if "#" is provided
    if ($color[0] == '#' ) {
        $color = substr( $color, 1 );
    }

    //Check if color has 6 or 3 characters and get values
    if (strlen($color) == 6) {
        $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    } elseif ( strlen( $color ) == 3 ) {
        $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
    } else {
        return $default;
    }

    //Convert hexadec to rgb
    $rgb =  array_map('hexdec', $hex);

    //Check if opacity is set(rgba or rgb)
    if($opacity){
        if(abs($opacity) > 1)
            $opacity = 1.0;
        $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
    } else {
        $output = 'rgb('.implode(",",$rgb).')';
    }

    //Return rgb(a) color string
    return $output;
}

// Minimize css
function sibg_minimizeCSS($css){
    $css = preg_replace('/\/\*((?!\*\/).)*\*\//','',$css); // negative look ahead
    $css = preg_replace('/\s{2,}/',' ',$css);
    $css = preg_replace('/\s*([:;{}])\s*/','$1',$css);
    $css = preg_replace('/;}/','}',$css);
    return $css;
}




