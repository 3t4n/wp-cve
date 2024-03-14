<?php
/**
 * Shortcodes
 *
 *
 */
/*-----------------------------------------------------------------------------------*/
/*	Magic Buttons Shortcode
/*-----------------------------------------------------------------------------------*/
function pwr_magic_buttons_shortcode($atts, $content = null) {
	extract(shortcode_atts(array(
		//"ids" => ''
		"style" => '',
		"text" => '',
		"link" => '',
		"target" => '',
		"nofollow" => '',
		"icon" => '',
		"icon_position" => '',
		"size" => '',
	), $atts));

	//Enqueue Scripts
	wp_enqueue_style( 'magic-buttons-css', plugin_dir_url( __FILE__ ) . 'css/buttons.css' );	
	//wp_enqueue_script( 'owl-carousel-js', plugin_dir_url( __FILE__ ) . 'js/vendor/owl.carousel/owl.carousel.min.js', array('jquery'), '20151215', true );
	

	if( $target == true ) {
		$targetcontent = 'target="_blank"';
	} else {
		$targetcontent = '';
	}

	if( $nofollow == true ) {
		$nofollowcontent = 'rel="nofollow"';
	} else {
		$nofollowcontent = '';
	}
	$iconcontent = '';
	$iconcontent_after = '';
	if (! empty($icon)) {
		$iconcontent = ' <i class="magic-button__icon '.$icon.'"></i> ';
		if ( $icon_position == 'after') {
			$iconcontent_after = ' <i class="magic-button__icon '.$icon.'"></i> ';
			$iconcontent = '';
		} 		
	}

	$datatext = $text;
	$text2 = '<span>'.$text.'</span>';
	if ($style == 'nina' || $style == 'nanuk') {
		$text2 = array();
		$text = explode(" ", $text);
		$isFirst = true;
		foreach ($text as $string) {			
			$string = str_split($string);
			if ($isFirst == false) {
				array_push($text2, '&nbsp;');
			}
			foreach ($string as $key => $value) {
				$value = '<span>'.$value.'</span>';
				array_push($text2, $value);
			}			
			$isFirst = false;	
		}		

		$text2 = implode("",$text2);
	} 

	

	$output = '';
	
	//$output .='<button class="button button--'.$style.' button--border-thin button--round-s" data-text="'.$text.'"><span>'.$text.'</span></button>' ;
	$output .='<a href="'.$link.'" '.$targetcontent.' '.$nofollowcontent.' class="magic-button magic-button--'.$style.' magic-button--'.$size.'" data-text="'.$datatext.'">'.$iconcontent.$text2.$iconcontent_after.'</a>' ;

	return $output;
}

add_shortcode("magic-button", "pwr_magic_buttons_shortcode");