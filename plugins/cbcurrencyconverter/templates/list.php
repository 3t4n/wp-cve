<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

$show_symbol = $settings->get_option( 'show_symbol', 'cbcurrencyconverter_global', 'on' );
$show_flag   = $settings->get_option( 'show_flag', 'cbcurrencyconverter_global', 'on' );

//$symbols = cbcurrencyconverter_get_symbols();

$html = '<div  class="cbcurrencyconverter_list_wrapper cbcurrencyconverter_list_wrapper_' . esc_attr( $reference ) . '">';
$html .= '<h3 class = "cbcurrencyconverter_heading">' . esc_html( $list_title ) . '</h3>';

if ( ! empty( $list_to_currencies ) ) {
	$html .= '<ul class="cbcurrencyconverter_list_to cbcurrencyconverter_list_to_' . esc_attr( $reference ) . '">';

	//$html .= '<li class="cbcurrencyconverter_list_from cbcurrencyconverter_list_from_' . esc_attr( $reference ) . '">' . $list_default_amount . ' ' . $list_from_currency . '<span class ="cbcur_list_custom_text">' . esc_html__( 'equals =', 'cbcurrencyconverter' ) . '</span></li>';

	$currency_lower = strtolower( $list_from_currency );
	$symbol_txt     = '';
	if ( $show_symbol == 'on' ) {
		$symbol     = cbcurrencyconverter_get_symbol( $list_from_currency );
		$symbol_txt = ( $symbol != '' ) ? ' ' . $symbol : '';
	}

	$flag_html = '';
	if ( $show_flag == 'on' ) {
		$flag_html = '<i class="currency-flag currency-flag-' . esc_attr( $currency_lower ) . '"></i>';
	}

	$html .= '<li class="cbcurrencyconverter_list_to_from">';
	$html .= '<span class="cbcur_list_to_country">' . $flag_html . esc_html( $list_from_currency ) . '</span>';
	$html .= '<span class="cbcur_list_to_cur">' . number_format_i18n( $list_default_amount, $decimal_point ) . $symbol_txt . '</span>';
	$html .= '</li>';

	if ( is_array( $list_to_currencies ) && sizeof( $list_to_currencies ) > 0 ) {
		foreach ( $list_to_currencies as $currency ) {
			$symbol_txt = '';
			if ( $show_symbol == 'on' ) {
				$symbol     = cbcurrencyconverter_get_symbol( $currency );
				$symbol_txt = ( $symbol != '' ) ? ' ' . $symbol : '';
			}


			$currency_price = CBCurrencyConverterHelper::getCurrencyRate( $list_default_amount, $list_from_currency, $currency, $decimal_point );
			if ( $currency_price != '' ) {
				$currency_lower = strtolower( $currency );

				$flag_html = '';
				if ( $show_flag == 'on' ) {
					$flag_html = '<i class="currency-flag currency-flag-' . esc_attr( $currency_lower ) . '"></i>';
				}


				$html .= '<li class="cbcurrencyconverter_list_to_to cbcurrencyconverter_list_to_to_' . esc_attr( $currency_lower ) . '">';
				$html .= '<span class="cbcur_list_to_country">' . $flag_html . esc_html( $currency ) . '</span>';
				$html .= '<span class="cbcur_list_to_cur">' . number_format_i18n( $currency_price, $decimal_point ) . $symbol_txt . '</span>';
				$html .= '</li>';
			}//end of not null

		}//end of foreach
	}
	$html .= '</ul>';//.cbcurrencyconverter_list_to
}
$html .= '</div>'; //.cbcurrencyconverter_list_wrapper
echo $html;