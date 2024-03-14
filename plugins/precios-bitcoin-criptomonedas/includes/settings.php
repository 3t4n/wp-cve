<?php
/**
* WIDGET 0 - DEFAULTS
*/

define ('COINMOTION_WIDGET_O_TITLE', __( 'Real time prices', 'coinmotion' ));
define ('COINMOTION_WIDGET_O_REFCODE', __( 'WIDGET_REFERRAL', 'coinmotion' ));
define ('COINMOTION_WIDGET_O_REGISTER_TEXT', __( 'Buy now', 'coinmotion' ));
define ('COINMOTION_WIDGET_O_LANG', 'en');
define ('COINMOTION_WIDGET_O_REGISTER_BUTTON_COLOR', '#00b0db');
define ('COINMOTION_WIDGET_O_REGISTER_TEXT_COLOR', '#000000');
define ('COINMOTION_WIDGET_O_REGISTER_BUTTON_HOVER_COLOR', '#39bfe2');
define ('COINMOTION_WIDGET_O_DEFAULT_CURRENCY', 'EUR');
define ('COINMOTION_WIDGET_O_SHOW_BUTTON', 'true');

/**
* WIDGET PRICE EVOLUTION - DEFAULTS
*/
define ('COINMOTION_WIDGET_RATE_PERIOD_TITLE', __( 'Price Evolution', 'coinmotion' ));
define ('COINMOTION_WIDGET_RATE_PERIOD_LINE_COLOR', '#009ac0');
define ('COINMOTION_WIDGET_RATE_PERIOD_PERIOD', 'day');
define ('COINMOTION_WIDGET_RATE_PERIOD_CURRENCY', 'BTC');
define ('COINMOTION_WIDGET_RATE_PERIOD_TYPE', 'price');
define ('COINMOTION_WIDGET_RATE_PERIOD_BACKGROUND', '#ffffff');
define ('COINMOTION_WIDGET_RATE_PERIOD_WIDTH', '100%');
define ('COINMOTION_WIDGET_RATE_PERIOD_HEIGHT', '200px');
define ('COINMOTION_WIDGET_RATE_PERIOD_POINTS', '10');
define ('COINMOTION_WIDGET_RATE_PERIOD_GRAPH', 'line');
define ('COINMOTION_WIDGET_RATE_PERIOD_SHOW_BUTTON', 'true');

/**
* WIDGET HISTORICAL DATA - DEFAULTS
*/
define ('COINMOTION_WIDGET_CURRENCY_DETAILS_TITLE', __( 'Historical Data', 'coinmotion' ));
define ('COINMOTION_WIDGET_CURRENCY_DETAILS_CURRENCY', 'BTC');
define ('COINMOTION_WIDGET_CURRENCY_DETAILS_TYPE', 'price');
define ('COINMOTION_WIDGET_CURRENCY_DETAILS_TEXT_COLOR', '#a7a7a7');
define ('COINMOTION_WIDGET_CURRENCY_DETAILS_BACKGROUND_COLOR', '#ffffff');
define ('COINMOTION_WIDGET_CURRENCY_DETAILS_SHOW_BUTTON', 'true');

/**
* WIDGET CURRENCY/CRYPTO CONVERSOR - DEFAULTS
*/
define ('COINMOTION_WIDGET_CURRENCY_CONVERSOR_TITLE', __( 'Currency/Crypto Conversor', 'coinmotion' ));
define ('COINMOTION_WIDGET_CURRENCY_CONVERSOR_BACKGROUND_COLOR', '#ffffff');
define ('COINMOTION_WIDGET_CURRENCY_CONVERSOR_TEXT_COLOR', '#a7a7a7');
define ('COINMOTION_WIDGET_CURRENCY_CONVERSOR_SHOW_BUTTON', 'true');

/**
* OTHER FUNCTIONS
*/
function hex2rgba_coinmotion($color, $opacity = false) {
 
	$default = 'rgb(0,0,0)';
 
	if(empty($color))
          return $default; 

    if (!strpos($color, '#'))
        return $color;

    if ($color[0] == '#' ) {
        $color = substr( $color, 1 );
    }

    if (strlen($color) == 6) {
            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    } elseif ( strlen( $color ) == 3 ) {
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
    } else {
            return $default;
    }

    $rgb =  array_map('hexdec', $hex);

    if($opacity){
        if(abs($opacity) > 1)
            $opacity = 1.0;
        $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
    } else {
        $output = 'rgb('.implode(",",$rgb).')';
    }

    return $output;
}
?>