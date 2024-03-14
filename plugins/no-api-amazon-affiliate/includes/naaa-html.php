<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function naaa_get_html_review_h($opiniones) {
	if (!get_option('naaa_comentarios_show')){
		return '';
	}

	$numOpiniones = naaa_get_numeric($opiniones);
	if ($numOpiniones > 99999 ){
		$opiniones = '+100k';
	}

	$naaa_html_review = '<span class="naaa-product-review">';
		$naaa_html_review .= '<span class="naaa-product-review-value">('.esc_html($opiniones).')</span>';
		$naaa_html_review .= '<span class="naaa-product-review-text-h">'.esc_html(get_option('naaa_comentarios_text')).'</span>';
	$naaa_html_review .= '</span>';

	return $naaa_html_review;
}

function naaa_get_html_review($opiniones) {
	if (!get_option('naaa_comentarios_show')){
		return '';
	}

	$numOpiniones = naaa_get_numeric($opiniones);
	if ($numOpiniones > 99999 ){
		$opiniones = '+100k';
	}

	$naaa_html_review = '<div class="naaa-product-review">';
		$naaa_html_review .= '<div class="naaa-product-review-value">('.esc_html($opiniones).')</div>';
		$naaa_html_review .= '<div class="naaa-product-review-text">'.esc_html(get_option('naaa_comentarios_text')).'</div>';
	$naaa_html_review .= '</div>';

	return $naaa_html_review;
}

function naaa_get_html_rating_h($valoracion){
	if (!get_option('naaa_valoracion_show', 1)){
		return '<span class="naaa-product-no-rating"></span>';
	}

	$starSegment = round(($valoracion / 0.5));
	if ($valoracion > 0){
		$title = $valoracion.' '.__('de', 'no-api-amazon-affiliate').' 5';
		$title = esc_html($title);
	}else{
		$title = esc_html(__('Sin valorar', 'no-api-amazon-affiliate'));
	}

	$nameGroup = uniqid();
	$naaa_html_rating = '<span class="naaa-product-rating">';
		$naaa_html_rating .= '<fieldset class="naaa-rating" id="'.$nameGroup.'">';
		for ($i=10; $i > 1 ; $i--) { 
			if($i%2==0){
				$naaa_html_rating .= '<input type="radio" class="naaa-input-star" name="'.$nameGroup.'" value="'.$i.'" '.checked($i, $starSegment, false).'/><label class="naaa-full naaa-label-star" title="'.$title.'"></label>';
			}else{
				$naaa_html_rating .= '<input type="radio" class="naaa-input-star" name="'.$nameGroup.'" value="'.$i.'" '.checked($i, $starSegment, false).'/><label class="naaa-half naaa-label-star" title="'.$title.'"></label>';
			}
		}
		$naaa_html_rating .= '</fieldset>';
		if (get_option('naaa_valoracion_desc_show')){
			$naaa_html_rating .= '<span class="naaa-product-rating-value-h" title="'.$title.'">'.$title.'</span>';
		}else{
			$naaa_html_rating .= '<span class="naaa-product-rating-value-h" title="'.$title.'">&nbsp;</span>';
		}
	$naaa_html_rating .= '</span>';
	
	return $naaa_html_rating;
}

function naaa_get_html_rating($valoracion){
	if (!get_option('naaa_valoracion_show', 1)){
		return '<div class="naaa-product-no-rating"></div>';
	}

	$starSegment = round(($valoracion / 0.5));
	if ($valoracion > 0){
		$title = $valoracion.' '.__('de', 'no-api-amazon-affiliate').' 5';
		$title = esc_html($title);
	}else{
		$title = esc_html(__('Sin valorar', 'no-api-amazon-affiliate'));
	}

	$nameGroup = uniqid();
	$naaa_html_rating = '<div class="naaa-product-rating">';
		$naaa_html_rating .= '<fieldset class="naaa-rating" id="'.$nameGroup.'">';
		for ($i=10; $i > 1 ; $i--) { 
			if($i%2==0){
				$naaa_html_rating .= '<input type="radio" class="naaa-input-star" name="'.$nameGroup.'" value="'.$i.'" '.checked($i, $starSegment, false).'/><label class="naaa-full naaa-label-star" title="'.$title.'"></label>';
			}else{
				$naaa_html_rating .= '<input type="radio" class="naaa-input-star" name="'.$nameGroup.'" value="'.$i.'" '.checked($i, $starSegment, false).'/><label class="naaa-half naaa-label-star" title="'.$title.'"></label>';
			}
		}
		$naaa_html_rating .= '</fieldset>';
		if (get_option('naaa_valoracion_desc_show')){
			$naaa_html_rating .= '<div class="naaa-product-rating-value" title="'.$title.'">'.$title.'</div>';
		}
	$naaa_html_rating .= '</div>';
	
	return $naaa_html_rating;
}

function naaa_get_html_discount($precio, $precio_old){
	if (empty($precio) || empty($precio_old) || !get_option('naaa_discount_show', 1) || $precio == 0 || $precio_old == 0){
		return '';
	}

	$discount = intval(100-( (100*$precio)/($precio_old) ));
	
	$naaa_html_discount = '<div class="naaa-discount">'.esc_html($discount).'%</div>';
	return $naaa_html_discount;
}


function naaa_get_html_price($precio, $market, $precio_text, $precio_old, $template){

	if (!get_option('naaa_precio_new_show', 1) && !get_option('naaa_precio_old_show', 1) ){
		return '';
	}
	
	$naaa_show_precio_new  = !empty($precio) && ($precio>0) && get_option('naaa_precio_new_show', 1);
	$naaa_show_precio_old  = !empty($precio_old) && ($precio_old>0) && get_option('naaa_precio_old_show', 1);

	$naaa_html_price = '<div class="naaa-product-price">';
		if(!empty($precio_text) && ($naaa_show_precio_new || $naaa_show_precio_old) ){
			$naaa_html_price .= '<div class="naaa-product-price-text">'.esc_html($precio_text).'</div>&nbsp;';
		}

		if($template == 'horizontal'){
			$naaa_html_price .= '<div class="naaa-product-price-h">';
		}else{
			$naaa_html_price .= '<div>';
		}
			if($naaa_show_precio_new){
				$naaa_html_price .= '<span class="naaa-product-price-new">'.esc_html(naaa_get_price_with_currency($precio, $market)).'</span>&nbsp;';
			}

			if($naaa_show_precio_old){
				$naaa_html_price .= '<span class="naaa-product-price-old">'.esc_html(naaa_get_price_with_currency($precio_old, $market)).'</span>';
			}
		$naaa_html_price .= '</div>';

	$naaa_html_price .= '</div>';

	return $naaa_html_price;
}

function naaa_get_html_prime($prime){
	if (empty($prime) || !get_option('naaa_prime_show', 1)){
		return '';
	}
	$naaa_html_prime = '<span class="naaa-prime"></span>';
	return $naaa_html_prime;
}

function naaa_get_html_button($button_text){
	$naaa_html_button = '<div class="naaa-product-action">';
		if (get_option('naaa_button_border_show', 1)){
			$naaa_html_button .= '<div class="naaa-product-button naaa-product-button-border">'.esc_html($button_text).'</div>';
		}else{
			$naaa_html_button .= '<div class="naaa-product-button">'.esc_html($button_text).'</div>';
		}
	$naaa_html_button .= '</div>';
	return $naaa_html_button;
}

function naaa_get_html_class_gridbox(){
	if (!get_option('naaa_responsive', 1)){
		return 'naaa-gridbox';
	}else{
		return 'naaa-gridbox naaa-responsive';
	}
}

function naaa_get_html_gridbox($asin, $button_text, $urlImage, $precio, $titulo, $alt_text, $precio_text, $precio_old, 
								$valoracion, $opiniones, $prime, $market, $template, $heading){
	if($template == 'horizontal'){
		return naaa_get_html_gridbox_hori($asin, $button_text, $urlImage, $precio, $titulo, $alt_text, $precio_text, $precio_old,
								$valoracion, $opiniones, $prime, $market, $heading);
	}else{
		return naaa_get_html_gridbox_card($asin, $button_text, $urlImage, $precio, $titulo, $alt_text, $precio_text, $precio_old,
								$valoracion, $opiniones, $prime, $market, $heading);
	}
}

function naaa_get_html_heading($titulo, $heading){
	if (is_numeric($heading) && $heading > 0 && $heading < 7){
		return '<h'.$heading.' style="all: unset;">'.$titulo.'</h'.$heading.'>';
		
	}else{
		return $titulo;
	}
	return $titulo;
}

function naaa_get_html_gridbox_hori($asin, $button_text, $urlImage, $precio, $titulo, $alt_text, $precio_text, $precio_old,
									$valoracion, $opiniones, $prime, $market, $heading){
	$naaa_html_gridbox = '<div class="naaa-gridbox-h">
						<a rel="sponsored,nofollow" target="_blank" href="'.esc_url(naaa_get_amazon_url_product($asin, $market)).'" class="naaa-link-gridbox">
						<div class="naaa-product naaa-product-h">
							'.naaa_get_html_discount($precio, $precio_old).'
							<div class="naaa-product-thumb">
								<img class="naaa-product-img-h" src="'.esc_url($urlImage.'_AC_AC_SR250,250_.jpg').'" alt="'.esc_attr($alt_text).'">
							</div>
							'.naaa_get_html_prime($prime).'
							<div class="naaa-product-title naaa-product-title-h">
								'.naaa_get_html_heading($titulo, $heading).'
							</div>
							'.naaa_get_html_price($precio, $market, $precio_text, $precio_old, 'horizontal').'
							<div>
								'.naaa_get_html_button($button_text).'
							</div>
							<div class="naaa-rating-and-review-h">
								'.naaa_get_html_rating_h($valoracion).'
								'.naaa_get_html_review_h($opiniones).'
							</div>
						</div>
						</a>
					</div>';
	return $naaa_html_gridbox;					
}

function naaa_get_html_gridbox_card($asin, $button_text, $urlImage, $precio, $titulo, $alt_text, $precio_text, $precio_old,
									$valoracion, $opiniones, $prime, $market, $heading){
	$naaa_html_gridbox = '<div class="'.naaa_get_html_class_gridbox().'">
						<a rel="sponsored,nofollow" target="_blank" href="'.esc_url(naaa_get_amazon_url_product($asin, $market)).'" class="naaa-link-gridbox">
						<div class="naaa-product">
							'.naaa_get_html_discount($precio, $precio_old).'
							<div class="naaa-product-thumb">
								<img class="naaa-product-img" src="'.esc_url($urlImage.'_AC_AC_SR250,250_.jpg').'" alt="'.esc_attr($alt_text).'">
							</div>
							'.naaa_get_html_prime($prime).'
							<div class="naaa-product-title">
								'.naaa_get_html_heading($titulo, $heading).'
							</div>
							'.naaa_get_html_price($precio, $market, $precio_text, $precio_old, 'card').'
							'.naaa_get_html_button($button_text).'
							<div class="naaa-rating-and-review">
								'.naaa_get_html_rating($valoracion).'
								'.naaa_get_html_review($opiniones).'
							</div>
						</div>
						</a>
					</div>';
	return $naaa_html_gridbox;					
}

function naaa_get_tag_autor($market){
	if ($market == 'ca'){
		return 'pwpnaaa0f-20';
	}else if ($market == 'de'){
		return 'pwpnaaa01-21';
	}else if ($market == 'es' || empty($market)){
		return 'pwpnaaa07-21';
	}else if ($market == 'fr'){
		return 'pwpnaaa0f-22';
	}else if ($market == 'gb'){
		return 'pwpnaaa0c-21';
	}else if ($market == 'it'){
		return 'pwpnaaa-07';
	}else if ($market == 'jp'){
		return 'pwpnaaa-07';
	}else if ($market == 'us'){
		return 'pwpnaaa-22';
	}else if ($market == 'mx'){
		return 'pwpnaaa03-20';
	}else if ($market == 'br'){
		return 'pwpnaaa03-20';
	}
	return 'pwpnaaa07-21';
}

function naaa_get_html_grid($asin, $button_text, $precio_text, $market, $template, $heading, $bestseller, $max) {
	if (naaa_is_valid_market($market)){
		$market = strtolower($market);
	}else{
		$market = strtolower(get_option('naaa_amazon_country','es'));
	}

	if(!empty($bestseller)){
		$asin = naaa_get_asin_list_bestseller($bestseller, $market);
	}
	$asinList = explode(",", $asin);
	
	$button_text = trim($button_text);
	if($button_text == ''){
		$button_text = __('Ver más', 'no-api-amazon-affiliate');
	}
	$precio_text = trim($precio_text);

	$max = naaa_get_numeric($max);

	$naaa_container = '<div class="container">
							<div class="naaa-grid">';
	foreach ($asinList as $asinUnit) {
		if($max <= 0) break;
		$asinUnitArray = explode("-", trim($asinUnit));
		$finalAsinUnit = $asinUnitArray[0];
		if (count($asinUnitArray)>1) {
			$finalMarket = $asinUnitArray[1];
		}else{
			$finalMarket = $market;
		}
		$finalMarket = strtolower($finalMarket);
		$item_data = naaa_get_item_data($finalAsinUnit, $finalMarket);
		//Definimos el título a usar
		$title='';
		if(empty($item_data['titulo_manual'])){
			$title = $item_data['titulo'];
		}else{
			$title = $item_data['titulo_manual'];
		}

		//Definimos el alt a usar
		if(empty($item_data['alt_manual'])){
			$alt_text = $item_data['titulo'];
		}else{
			$alt_text = $item_data['alt_manual'];
		}


		if (!empty($item_data)){
			$naaa_container .=  naaa_get_html_gridbox($finalAsinUnit,
														$button_text,
														$item_data['imagen_url'],
														$item_data['precio'],
														$title,
														$alt_text,
														$precio_text,
														$item_data['precio_anterior'],
														$item_data['valoracion'],
														$item_data['opiniones'],
														$item_data['prime'],
														$finalMarket,
														$template,
														$heading);
			$max--;
		}
	}
	
	$naaa_container .=  	'</div>
						</div>';
	return $naaa_container;
}

function naaa_get_html_title_list($title, $title_manual){
	$title_html = '';
	if(empty($title_manual)){
		$title_html = $title;
	}else{
		$title_html = '<del>'.$title.'</del><br>'.$title_manual;
	}
	return $title_html;
}

?>