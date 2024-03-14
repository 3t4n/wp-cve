<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

$html = '';

$html .= '<div class="cbcurrencyconverter_cal_wrapper cbcurrencyconverter_cal_wrapper' . esc_attr( $reference ) . '">';
$html .= '<h4 class="cbcurrencyconverter_heading">' . esc_html( $calc_title ) . '</h4>';
$html .= '<div class="cbcurrencyconverter_result cbcurrencyconverter_result_' . esc_attr( $reference ) . '"></div>';

$html .= '<input  type = "hidden" class = "cbcurrencyconverter_cal_amount cbcurrencyconverter_cal_amount_' . esc_attr( $reference ) . '" value ="' . floatval( $calc_default_amount ) . '"/> ';

$html .= '<div class="cbcurrencyconverter_form_field" style="display: none;">';
$html .= '<span  class="cbcurrencyconverter_label">' . esc_html__( 'From', 'cbcurrencyconverter' ) . ' <i>' . $calc_from_currency . '</i></span>';
$html .= '<div class="cbcurrencyconverter_form_field_input">';
$html .= '<select class="cbcurrencyconverter_select2 cbcurrencyconverter_cal_from cbcurrencyconverter_cal_from_' . esc_attr( $reference ) . '">';
$html .= '<option value="' . $calc_from_currency . '">' . esc_html__( 'Select a currency', 'cbcurrencyconverter' ) . '</option>';
$html .= '</select>';
$html .= '</div>';
$html .= '</div>';

$html .= '<div class="cbcurrencyconverter_form_field">';
$html .= '<span class="cbcurrencyconverter_label">' . esc_html__( 'To', 'cbcurrencyconverter' ) . ' <i>' . $calc_to_currency . '</i></span>';

$html .= '<div class="cbcurrencyconverter_form_field_input">';
$html .= '<select class="cbcurrencyconverter_select2 cbcurrencyconverter_cal_to cbcurrencyconverter_cal_to_' . esc_attr( $reference ) . '">';
$html .= '<option value="">' . esc_html__( 'Select a currency', 'cbcurrencyconverter' ) . '</option>';
foreach ( $all_currencies as $key => $title ) {
	if ( ! in_array( $key, $calc_to_currencies ) ) {
		continue;
	}

	$html .= '<option ' . selected( $calc_to_currency, $key, false ) . '  value="' . esc_attr( $key ) . '">' . esc_html( $title ) . '</option>';
}
$html .= '</select>';
$html .= '</div>';
$html .= '</div>';

$html .= '<div class="cbconverter_result_wrapper_shortcode"><button  class="button btn btn-primary cbcurrencyconverter_calculate cbcurrencyconverter_calculate_' . esc_attr( $reference ) . '" data-busy = "0" data-ref = "' . esc_attr( $reference ) . '" >' . esc_html__( 'Convert', 'cbcurrencyconverter' ) . '</button></div>';
$html .= '</div>';// end of wrapper div

echo $html;