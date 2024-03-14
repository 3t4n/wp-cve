<?php
function coinmotion_get_widget_data($widget = COINMOTION_OPTION_NAME_WIDGET_0): array
{
	$options = coinmotion_get_option($widget);
	$db_data = coinmotion_get_default_data($widget);
	

    if ( ! empty( $options[ 'title' ] ) ) {
        $db_data['title'] = $options[ 'title' ];
    }
    if ( ! empty( $options[ 'refcode' ] ) ) {
        $db_data['refcode'] = $options[ 'refcode' ];
    }
    if ( ! empty( $options[ 'register_text' ] ) ) {
        $db_data['register_text'] = __($options['register_text'], 'coinmotion');
    }
    if ( ! empty( $options[ 'lang' ] ) ) {
        $db_data['lang'] = $options[ 'lang' ];
    }
    if ( ! empty( $options[ 'register_button_color' ] ) ) {
        $db_data['register_button_color'] = $options[ 'register_button_color' ];
    }
    if ( ! empty( $options[ 'register_text_color' ] ) ) {
        $db_data['register_text_color'] = $options[ 'register_text_color' ];
    }
    if ( ! empty( $options[ 'default_currency' ] ) ) {
        $db_data['default_currency'] = $options[ 'default_currency' ];
    }
    if ( ! empty( $options[ 'show_button' ] ) ) {
        $db_data['show_button'] = $options[ 'show_button' ];
    }

    if ( ! empty( $options[ 'register_button_hover_color' ] ) ) {
        $db_data['register_button_hover_color'] = $options[ 'register_button_hover_color' ];
    }

	$db_data['register_text'] = __($db_data['register_text'], 'coinmotion');
	
	return $db_data;
}

function coinmotion_get_widget_rate_period_data($widget = COINMOTION_OPTION_NAME_WIDGET_RATE_PERIOD): array
{
	$options = coinmotion_get_option($widget);
	$db_data = coinmotion_get_default_data($widget);

    if ( ! empty( $options[ 'title' ] ) ) {
        $db_data['title'] = $options[ 'title' ];
    }
    if ( ! empty( $options[ 'line_color' ] ) ) {
        $db_data['line_color'] = $options[ 'line_color' ];
    }
    if ( ! empty( $options[ 'period' ] ) ) {
        $db_data['period'] = $options[ 'period' ];
    }
    if ( ! empty( $options[ 'currency' ] ) ) {
        $db_data['currency'] = $options[ 'currency' ];
    }
    if ( ! empty( $options[ 'type' ] ) ) {
        $db_data['type'] = $options[ 'type' ];
    }
    if ( ! empty( $options[ 'points' ] ) ) {
        $db_data['points'] = $options[ 'points' ];
    }
    if ( ! empty( $options[ 'width' ] ) ) {
        $db_data['width'] = $options[ 'width' ];
    }
    if ( ! empty( $options[ 'height' ] ) ) {
        $db_data['height'] = $options[ 'height' ];
    }
    if ( ! empty( $options[ 'background' ] ) ) {
        $db_data['background'] = $options[ 'background' ];
    }
    if ( ! empty( $options[ 'graph' ] ) ) {
        $db_data['graph'] = $options[ 'graph' ];
    }
    if ( ! empty( $options[ 'show_button' ] ) ) {
        $db_data['show_button'] = $options[ 'show_button' ];
    }

	return $db_data;
}

function coinmotion_get_widget_currency_details_data($widget = COINMOTION_OPTION_NAME_WIDGET_CURRENCY_DETAILS): array
{
	$options = coinmotion_get_option($widget);
	$db_data = coinmotion_get_default_data($widget);
    if ( ! empty( $options[ 'title' ] ) ) {
        $db_data['title'] = $options[ 'title' ];
    }
    if ( ! empty( $options[ 'currency' ] ) ) {
        $db_data['currency'] = $options[ 'currency' ];
    }
    if ( ! empty( $options[ 'type' ] ) ) {
        $db_data['type'] = $options[ 'type' ];
    }
    if ( ! empty( $options[ 'background_color' ] ) ) {
        $db_data['background_color'] = $options[ 'background_color' ];
    }
    if ( ! empty( $options[ 'text_color' ] ) ) {
        $db_data['text_color'] = $options[ 'text_color' ];
    }
    if ( ! empty( $options[ 'show_button' ] ) ) {
        $db_data['show_button'] = $options[ 'show_button' ];
    }	
	return $db_data;
}

function coinmotion_get_widget_currency_conversor_data($widget = COINMOTION_OPTION_NAME_WIDGET_CURRENCY_CONVERSOR): array
{
	$options = coinmotion_get_option($widget);
	$db_data = coinmotion_get_default_data($widget);
    if ( ! empty( $options[ 'title' ] ) ) {
        $db_data['title'] = $options[ 'title' ];
    }
    if ( ! empty( $options[ 'background_color' ] ) ) {
        $db_data['background_color'] = $options[ 'background_color' ];
    }	
    if ( ! empty( $options[ 'text_color' ] ) ) {
        $db_data['text_color'] = $options[ 'text_color' ];
    }
    if ( ! empty( $options[ 'show_button' ] ) ) {
        $db_data['show_button'] = $options[ 'show_button' ];
    }	
	return $db_data;
}

function coinmotion_get_default_data($widget = COINMOTION_OPTION_NAME_WIDGET_0): array
{
	$params = [];
	switch ($widget){
		case COINMOTION_OPTION_NAME_WIDGET_0:
			$params['title'] = COINMOTION_WIDGET_O_TITLE;
			$params['refcode'] = COINMOTION_WIDGET_O_REFCODE;
			$params['register_text'] = __(COINMOTION_WIDGET_O_REGISTER_TEXT, 'coinmotion');
			$params['lang'] = COINMOTION_WIDGET_O_LANG;
			$params['register_button_color'] = COINMOTION_WIDGET_O_REGISTER_BUTTON_COLOR;
			$params['register_text_color'] = COINMOTION_WIDGET_O_REGISTER_TEXT_COLOR;
			$params['register_button_hover_color'] = COINMOTION_WIDGET_O_REGISTER_BUTTON_HOVER_COLOR;
			$params['default_currency'] = COINMOTION_WIDGET_O_DEFAULT_CURRENCY;
			$params['show_button'] = COINMOTION_WIDGET_O_SHOW_BUTTON;
			break;
		case COINMOTION_OPTION_NAME_WIDGET_RATE_PERIOD:
			$params['title'] =  __( 'Price Evolution', 'coinmotion' );
			$params['line_color'] = COINMOTION_WIDGET_RATE_PERIOD_LINE_COLOR;
			$params['period'] = COINMOTION_WIDGET_RATE_PERIOD_PERIOD;
			$params['currency'] = COINMOTION_WIDGET_RATE_PERIOD_CURRENCY;
			$params['type'] = COINMOTION_WIDGET_RATE_PERIOD_TYPE;
			$params['points'] = COINMOTION_WIDGET_RATE_PERIOD_POINTS;
			$params['width'] = COINMOTION_WIDGET_RATE_PERIOD_WIDTH;
			$params['height'] = COINMOTION_WIDGET_RATE_PERIOD_HEIGHT;
			$params['background'] = COINMOTION_WIDGET_RATE_PERIOD_BACKGROUND;
			$params['graph'] = COINMOTION_WIDGET_RATE_PERIOD_GRAPH;
			$params['show_button'] = COINMOTION_WIDGET_RATE_PERIOD_SHOW_BUTTON;
			break;
		case COINMOTION_OPTION_NAME_WIDGET_CURRENCY_DETAILS:
			$params['title'] = __( 'Historical Data', 'coinmotion' );
			$params['currency'] = COINMOTION_WIDGET_CURRENCY_DETAILS_CURRENCY;
			$params['type'] = COINMOTION_WIDGET_CURRENCY_DETAILS_TYPE;
			$params['background_color'] = COINMOTION_WIDGET_CURRENCY_DETAILS_BACKGROUND_COLOR;
			$params['text_color'] = COINMOTION_WIDGET_CURRENCY_DETAILS_TEXT_COLOR;
			$params['show_button'] = COINMOTION_WIDGET_CURRENCY_DETAILS_SHOW_BUTTON;
			break;
		case COINMOTION_OPTION_NAME_WIDGET_CURRENCY_CONVERSOR:
			$params['title'] = __( 'Currency/Crypto Conversor', 'coinmotion' );
			$params['background_color'] = COINMOTION_WIDGET_CURRENCY_CONVERSOR_BACKGROUND_COLOR;
			$params['text_color'] = COINMOTION_WIDGET_CURRENCY_CONVERSOR_TEXT_COLOR;
			$params['show_button'] = COINMOTION_WIDGET_CURRENCY_CONVERSOR_SHOW_BUTTON;
			break;
	}
	return $params;
}
/*
function coinmotion_get_lastkey($widget = COINMOTION_OPTION_NAME_WIDGET_0){
	$options = coinmotion_get_option($widget);
	$lastKey = "";
	if ( $options ){
		$keys = array_keys( $options );
		if( isset($keys[ count( $keys ) -1 ] )){
			$lastKey = $keys[ count( $keys ) -1 ];
			if( $lastKey == "_multiwidget"  && isset($keys[ count( $keys ) -2 ])){
				$lastKey = $keys[ count( $keys ) -2 ];
			}
		}
	}
	return $lastKey;
}
*/
function coinmotion_get_option($widget = COINMOTION_OPTION_NAME_WIDGET_0){
    return get_option( $widget );
}

function getFormattedData($value, $with_symbol = true): string
{
    $symbol = '+';
    if (!$with_symbol) {
        $symbol = '';
    }

    if ((float)$value > 100){
		return $symbol . number_format($value, 0, ',', '.');
	}
    elseif (((float)$value < 100) && ((float)$value >= 1)){
		return $symbol . number_format($value, 2, ',', '.');
	}
	elseif (((float)$value < 1) && ((float)$value > 0)){
		return $symbol . number_format($value, 4, ',', '.');
	}
    elseif ((float)$value < 0){
		return number_format($value, 4, ',', '.');
	}
	else{
		return number_format($value, 2, ',', '.');
	}
}
