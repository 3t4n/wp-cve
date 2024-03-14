<?php


function a13fe_counter_render( $atts ) {
	$el_class     = $from = $to = $speed = $refresh_interval = $finish_text = $text_font_size = $digits_font_size = $text_color = $text_bold = '';
	$digits_color = $digits_bold = $uppercase = $style = $text_style = $number_style = $align = '';

	//check for script in theme version < 1.8.0
	if(wp_script_is( 'jquery.countTo', 'registered' ) || wp_script_is( 'jquery.countTo', 'enqueued' )){
		wp_enqueue_script( 'jquery.countTo' );
	}
	//check for script in theme version >= 1.8.0
	elseif(wp_script_is( 'jquery-countto', 'registered' ) || wp_script_is( 'jquery-countto', 'enqueued' )){
		wp_enqueue_script( 'jquery-countto' );
	}


	extract( shortcode_atts( array(
		'from'             => '',
		'to'               => '',
		'speed'            => '',
		'refresh_interval' => '',
		'finish_text'      => '',
		'text_font_size'   => '',
		'digits_font_size' => '',
		'text_color'       => false,
		'digits_color'     => false,
		'text_bold'        => false,
		'digits_bold'      => false,
		'uppercase'        => false,
		'align'            => '',
		'el_class'         => ''
	), $atts ) );
	$uppercase = (bool) $uppercase;
	$digits_bold      = (bool) $digits_bold;
	$text_bold      = (bool) $text_bold;

	$css_classes = a13fe_get_extra_class( $el_class );

	//bold font
	if ( $digits_bold === true ) {
		$number_style .= 'font-weight:bold;';
	} else {
		$number_style .= 'font-weight:normal;';
	}
	if ( $text_bold === true ) {
		$text_style .= 'font-weight:bold;';
	} else {
		$text_style .= 'font-weight:normal;';
	}

	//text transform
	if ( $uppercase === true ) {
		$style .= 'text-transform:uppercase;';
	} else {
		$style .= 'text-transform:none;';
	}
	//alignment
	if ( $align !== false && strlen( $align ) ) {
		$style .= 'text-align:' . esc_attr( $align ) . ';';
	}

	//color
	if ( $text_color !== false && strlen( $text_color ) ) {
		$text_style .= 'color:' . $text_color . ';';
	}
	if ( $digits_color !== false && strlen( $digits_color ) ) {
		$number_style .= 'color:' . $digits_color . ';';
	}

	//font size of number
	if ( strlen( $text_font_size ) ) {
		$text_style .= 'font-size:' . ( (int) $text_font_size ) . 'px;';
	}
	if ( strlen( $digits_font_size ) ) {
		$number_style .= 'font-size:' . ( (int) $digits_font_size ) . 'px;';
	}

	//attributes for counter
	$data_attr = '';
	$data_arr  = array( 'from', 'to', 'speed' );
	foreach ( $data_arr as $attr ) {
		if ( strlen( ${$attr} ) ) {
			$data_attr .= ' data-' . $attr . '="' . esc_attr( ${$attr} ) . '"';
		}
	}
	if ( strlen( $refresh_interval ) ) {
		$data_attr .= ' data-refresh-interval="' . esc_attr( $refresh_interval ) . '"';
	}

	$output = '<div class="a13_counter' . esc_attr( $css_classes ) . '" style="' . esc_attr( $style ) . '">';
	$output .= '<span class="number" style="' . esc_attr( $number_style ) . '" ' . $data_attr . '>&nbsp;</span>';
	$output .= strlen( $finish_text ) ? ( '<span class="finish-text" style="' . esc_attr( $text_style ) . '">' . esc_html( $finish_text ) . '</span>' ) : '';
	$output .= '</div>' . "\n";

	return $output;

}
//@deprecated
add_shortcode( 'a13_counter', 'a13fe_counter_render' );
//@since 1.0.8
add_shortcode( 'a13fe_counter', 'a13fe_counter_render' );