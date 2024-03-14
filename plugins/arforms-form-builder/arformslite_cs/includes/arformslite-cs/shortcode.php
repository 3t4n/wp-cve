<?php
/**
 * File to execute the ARFormslite Cornerstone Shortcode
 *
 * @package ARFormslite
 */

$arforms_final_shortcode = '';
$form_id                 = isset( $atts['arf_forms'] ) ? $atts['arf_forms'] : '';
if ( '' != $form_id ) {
	$arforms_final_shortcode .= '[ARForms id=' . $form_id . ']';
}

echo do_shortcode( $arforms_final_shortcode );
