<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

$show_symbol = $settings->get_option( 'show_symbol', 'cbcurrencyconverter_global', 'on' );
$show_flag   = $settings->get_option( 'show_flag', 'cbcurrencyconverter_global', 'on' );

$select2_class_flag_class            = '';
$select_form_field_select_flag_class = '';
if ( $show_flag == 'on' ) {
	$select2_class_flag_class            = 'cbcurrencyconverter_select2_flag';
	$select_form_field_select_flag_class = 'cbcurrencyconverter_form_field_select_flag';
}

//$symbols = cbcurrencyconverter_get_symbols();

$html = '';
$html .= '<div  class="cbcurrencyconverter_cal_wrapper cbcurrencyconverter_cal_wrapper_' . esc_attr( $reference ) . '">';

$html .= '<h3 class="cbcurrencyconverter_heading">' . esc_html( $calc_title ) . '</h3>';


/*$html .= '<div class="cbcurrencyconverter_form_field">';
$html .= '<span  class="cbcurrencyconverter_label">' . esc_html__( 'Amount', 'cbcurrencyconverter' ) . '</span>';
$html .= '<div class="cbcurrencyconverter_form_field_input">';
$html .= '<input type="number" step="any" class="cbcurrencyconverter_cal_amount cbcurrencyconverter_cal_amount_' . $reference . '" value ="' . floatval( $calc_default_amount ) . '" />';
$html .= '</div>';
$html .= '</div>';*/


$html .= '<div class="cbcurrencyconverter_form_field cbcurrencyconverter_form_field_from cbcurrencyconverter_form_field_select ' . esc_attr( $select_form_field_select_flag_class ) . '">';
$html .= '<span  class="cbcurrencyconverter_label">' . esc_html__( 'From', 'cbcurrencyconverter' ) . ' <i>' . $calc_from_currency . '</i></span>';

$from_currencies   = 0;
$html_from_options = '<option value="">' . esc_html__( 'Select From Currency', 'cbcurrencyconverter' ) . '</option>';
foreach ( $all_currencies as $key => $title ) {
	if ( ! in_array( $key, $calc_from_currencies ) ) {
		continue;
	}

	$from_currencies ++;

	$title = ( $show_symbol == 'on' ) ? $key . ' - ' . cbcurrencyconverter_get_symbol( $key ) : $title;

	$html_from_options .= '<option ' . selected( $calc_from_currency, $key, false ) . ' value="' . esc_attr( $key ) . '">' . esc_attr( $title ) . '</option>';
}

$disable_arrow_class = ( $from_currencies < 2 ) ? 'cbcurrencyconverter_arrow_disable' : '';
$html                .= '<div class="cbcurrencyconverter_form_field_input ' . esc_attr( $disable_arrow_class ) . '">';
$html                .= '<select data-placeholder="' . esc_attr__( 'Select From Currency', 'cbcurrencyconverter' ) . '" class="cbcurrencyconverter_select2 ' . esc_attr( $select2_class_flag_class ) . ' cbcurrencyconverter_cal_from cbcurrencyconverter_cal_from_' . esc_attr( $reference ) . '">';
$html                .= $html_from_options;
$html                .= '</select>';
$html                .= '</div>';
$html                .= '</div>';


$html .= '<div class="cbcurrencyconverter_form_field cbcurrencyconverter_form_field_to cbcurrencyconverter_form_field_select ' . esc_attr( $select_form_field_select_flag_class ) . '">';
$html .= '<span class="cbcurrencyconverter_label">' . esc_html__( 'To', 'cbcurrencyconverter' ) . ' <i>' . $calc_to_currency . '</i></span>';

$to_currencies   = 0;
$html_to_options = '<option value="">' . esc_html__( 'Select To Currency', 'cbcurrencyconverter' ) . '</option>';
foreach ( $all_currencies as $key => $title ) {
	if ( ! in_array( $key, $calc_to_currencies ) ) {
		continue;
	}
	$to_currencies ++;
	$title           = ( $show_symbol == 'on' ) ? $key . ' - ' . cbcurrencyconverter_get_symbol( $key ) : $title;
	$html_to_options .= '<option ' . selected( $calc_to_currency, $key, false ) . '  value="' . esc_attr( $key ) . '">' . esc_html( $title ) . '</option>';
}

$disable_arrow_class = ( $to_currencies < 2 ) ? 'cbcurrencyconverter_arrow_disable' : '';
$html                .= '<div class="cbcurrencyconverter_form_field_input ' . esc_attr( $disable_arrow_class ) . '">';
$html                .= '<select data-placeholder="' . esc_attr__( 'Select To Currency', 'cbcurrencyconverter' ) . '" class="cbcurrencyconverter_select2 ' . esc_attr( $select2_class_flag_class ) . ' cbcurrencyconverter_cal_to cbcurrencyconverter_cal_to_' . esc_attr( $reference ) . '">';
$html                .= $html_to_options;
$html                .= '</select>';


$html .= '</div>';
$html .= '</div>';


$currency_nonce = wp_create_nonce( "cbcurrencyconverter" );

$html .= '<div class="cbcurrencyconverter_amont_result_combo">';

$html .= '<div class="cbcurrencyconverter_form_field cbcurrencyconverter_form_field_amount">';
$html .= '<span  class="cbcurrencyconverter_label sr-only">' . esc_html__( 'Amount', 'cbcurrencyconverter' ) . '</span>';
$html .= '<div class="cbcurrencyconverter_form_field_input">';
$html .= '<input type="number" placeholder="' . esc_attr( 'Amount', 'cbcurrencyconverter' ) . '" step="any" min="1" class="cbcurrencyconverter_cal_amount cbcurrencyconverter_cal_amount_' . $reference . '" value ="' . floatval( $calc_default_amount ) . '" />';
$html .= '</div>';
$html .= '</div>';


$html .= '<div class="cbconverter_result_wrapper cbconverter_result_wrapper_' . esc_attr( $reference ) . '">';
$html .= '<button class="button btn btn-primary cbcurrencyconverter_calculate" data-decimal-point="' . intval( $decimal_point ) . '" data-busy = "0" data-ref = "' . esc_attr( $reference ) . '" data-nonce="' . $currency_nonce . '"><span>' . esc_html__( 'Convert', 'cbcurrencyconverter' ) . '</span></button>';
$html .= '</div>'; //.cbconverter_result_wrapper

$html .= '</div>';//.cbcurrencyconverter_amont_result_combo

$html .= '<div  class="cbcurrencyconverter_result cbcurrencyconverter_result_' . esc_attr( $reference ) . '"></div>';

$html .= '</div>';// end of wrapper div

echo $html;