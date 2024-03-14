<?php 
function eapi_replace_eapi_tag( $atts ){
//
if(get_option('eapi_version') !=  EAPI_VERSION){
	eapi_install();
}
//error is fixed: now 'typ' or 'type' can be used.
if(isset($atts['type'])){
	$atts['typ'] = $atts['type'];
}

if(isset($atts['typ'])){
	$typ = $atts['typ'];
	if(! in_array($typ, array("standard", "sidebar", "negative", "price", "button", "picture", "link", "stars", "reviews", "prime"))){
		$typ = "standard";
	}
}
else{
	$typ = "standard";
}
//amazon is set as default shop, if no shop is passed
if(!isset($atts['shops'])){
	$atts['shops'] = "amazon";
}
if(isset($atts['keyword'])){
	$keyword = $atts['keyword'];
	$keyword = preg_replace("#,\s*#", "," , $keyword);
	$keyword = str_replace(" ", "+", $keyword);
	
	//If a list is passed, (comma separated) n is irrelevant.
	$matches = preg_match_all("#,#", $keyword, $matches_default);
	if($matches > 0){
		$atts['n'] = $matches+1;
	}
}
else{
	if($typ == "button"){//hook for no lookup later
		$keyword = "NOKEYWORD";
	}else{//default-keyword
		$keyword = "Nischenseite";
	}
}
//all other types can only display n=1
if(!($typ === "standard" OR $typ === "negative"OR $typ === "sidebar")){
	$atts['n'] = 1;
}

if(isset($atts['n'])){
	if(is_numeric($atts['n'])){
		$n = $atts['n'];
	}else{
		$n = 1;
	}
}else{
	$n = 1;
}
$passed_parameters = array(
'product_title' => '', 
'product_button' => '',
'product_link' => '',
'product_affiliate' => '',
'link_intern' => '',
'picture_size' => '',
'picture_resolution' => '',
'picture_float' => '',
'analytics_info' => '',
'button_style' => '',
'head_text' => '',
'product_review' => '',
'prime' => '',
'num_reviews' => '',
'star_float' => '',
'review_float' => '',
'custom_rating' => '',
'feature_1' => '', 'feature_2' => '', 'feature_3' => '', 'feature_4' => '', 'feature_5' => '', 'feature_6' => '', 'feature_7' => '', 'feature_8' => '', 'feature_9' => '',
'picture_1' => '', 'picture_2' => '', 'picture_3' => '', 'picture_4' => '', 'picture_5' => '', 'picture_6' => '', 'picture_7' => '', 'picture_8' => '', 'picture_9' => '',
'badge_1' => '', 'badge_2' => '', 'badge_3' => '', 'badge_4' => '', 'badge_5' => '', 'badge_6' => '', 'badge_7' => '', 'badge_8' => '', 'badge_9' => '',
'debug' => '',
'eiig' => '',
'eiig_exclude' => '',
'eiig_link' => '',
'eiig_height' => '',
'eiig_width' => '',
'eiig_text' => '',
'eiig_border' => '',
'eiig_shadow' => '',
'eiig_start' => '',
'ebay_keyword' => '',
'shops' => '',
'use_site_title' => ''
);
//exceptions are described in the statement below
foreach($passed_parameters as $key => $value){
	if(isset($atts[$key])){
		$passed_parameters[$key] = $atts[$key];
	}
}
if(HAS_EAPI_PLUS){
	if($passed_parameters['use_site_title']){
		$keyword = get_the_title();
	}
}
$passed_parameters['shops'] = explode(",", $passed_parameters['shops']);
$data_from_local_WP = eapi_get_data_from_WP_database($keyword, $n, $passed_parameters);
$ret = "";
if(!($keyword == "NOKEYWORD" AND $typ == 'button')){
	if(sizeof($data_from_local_WP) >= ($n + count($passed_parameters['shops']) - 1) AND check_if_local_data_is_actual($data_from_local_WP)){
		$data_from_local_WP = eapi_merge_shops($data_from_local_WP, $passed_parameters);
		$ret .= eapi_build_output($data_from_local_WP, $typ, $passed_parameters);
	}else{
		$server_arr = [];
		if(in_array("amazon", $passed_parameters['shops'])){
			$server_arr = json_decode(eapi_get_amazon_products(array('n' => $n, 'product' => $keyword, 'debug' => $passed_parameters['debug'])));
		}else if(in_array("ebay", $passed_parameters['shops'])){//case when no amazon and only ebay shop
			$server_arr = json_decode(eapi_get_ebay_products(array('n' => $n, 'product' => $keyword, 'debug' => $passed_parameters['debug'], 'shops' =>  $passed_parameters['debug'])));
		}
		if(count($server_arr) > 0){
			if(sizeof($data_from_local_WP) > 0){
				eapi_delete_with_this_keyword($keyword);
			}
			if(in_array("ebay", $passed_parameters['shops']) &&
				in_array("amazon", $passed_parameters['shops'])){
				$ebay_return = array();
				foreach($server_arr as $sa){
					if(isset($sa->ean)){
						$tmpp = json_decode(eapi_get_ebay_products(array('n' => $n, 'product' => $sa->ean, 'debug' => $passed_parameters['debug'], 'shops' =>  $passed_parameters['shops'])));
						if(count($tmpp) > 0){
							array_push($ebay_return, $tmpp[0]);
						}
					}
				}
				$server_arr = array_merge($server_arr, $ebay_return);
			}
			eapi_store_data_in_WP_database($keyword, $server_arr);
			$server_arr = eapi_merge_shops($server_arr, $passed_parameters);
			$ret .= eapi_build_output($server_arr, $typ, $passed_parameters);
		}else{
			if(sizeof($data_from_local_WP) > 0){
				
				$data_from_local_WP = eapi_merge_shops($data_from_local_WP, $passed_parameters);
				$ret .= eapi_build_output($data_from_local_WP, $typ, $passed_parameters);
			}
		}
	}
}else{
	//temporary an object, obviously some problems with certain PHP-versions?!
	$temp_obj = new stdClass();
	$temp_obj->title =  $passed_parameters['product_button'];
	//builds just the button, without looking in the database or in the amazon api, e.g. intern links
	$ret .=  eapi_build_output(array($temp_obj), $typ, $passed_parameters);
}
if(substr($keyword, -1) == "t") {
	eapi_get_number_of_entries_in_db();
} 
return $ret;
}
//merges the result of different shops according to ean number
function eapi_merge_shops($arr, $passed_parameters){
	$to_return = array();
	if(count($passed_parameters['shops']) > 1){
		for($i = 0; $i < count($arr); $i++){
			if($arr[$i]->shop == "amazon"){
				for($j = 0; $j < count($arr); $j++){
					if($arr[$j]->shop != "amazon" AND $arr[$j]->ean == $arr[$i]->ean ){
						$arr[$i]->ebay_price = $arr[$j]->new_price;
						$arr[$i]->ebay_id = $arr[$j]->asin;
					}
				}
				array_push($to_return, $arr[$i]);
			}
		}
	}else{
		$to_return = $arr;
	}
	return $to_return;
}

//if an parameter is not set, it will get "" --> no error occours.
function eapi_init_typ_json($typ_json){
	$arr_of_all_params = eapi_get_array_of_all_parameters(false);
	foreach($arr_of_all_params as $a_p){
		if(!isset($typ_json->$a_p)){
			$typ_json->$a_p = "";
		}
	}
	return $typ_json;
}
//checks, if ratings can be loaded. 
//if they can be loaded, they will be saved in db too
function eapi_handle_ratings($temp_obj, $passed_parameters){
	if($temp_obj->iframe_url != "" AND $temp_obj->has_reviews){
		$temp_iframe_return =  eapi_get_rating_from_iframeurl($temp_obj->iframe_url, $passed_parameters);
		$temp_obj->rating = $temp_iframe_return['rating'];
		$temp_obj->num_reviews = $temp_iframe_return['num_reviews'];
		
		global $wpdb;
		$table_name = $wpdb->prefix . 'easy_amazon_product_information_data';
		$wpdb->update($table_name , array('review_information' => json_encode(array('iframe_url' => $temp_obj->iframe_url,
			'has_reviews' => $temp_obj->has_reviews,
			'rating' => $temp_obj->rating,
			'num_reviews' => $temp_obj->num_reviews)
		)),
		array('asin' =>$temp_obj->asin));
		
	}
	return $temp_obj;
}

//builds the output
function eapi_build_output($product_arr, $typ, $passed_parameters)
{
	//debug mode is activated
	if($passed_parameters['debug']){
		print_r("<a href='http://jensmueller.one/easy-amazon-product-information/'>DEBUG MODE ACTIVATED</a>:");
		print_r("<br>");
		print_r("<b>PHP_VERSION:</b> " . PHP_VERSION);
		print_r("<br>");
		print_r("<b>EAPI_VERSION:</b> " . EAPI_VERSION);
		print_r("<br>");
		print_r("<b>WEBSITE:</b> " . home_url());
		print_r("<br>");
		print_r("<b>Number of entries in DB:</b> " . eapi_get_number_of_entries_in_db());
		print_r("<br>" . "<b>First entry retrieved from DB:</b> ");
		print_r($product_arr[0]);
		print_r("<br>");
		print_r($passed_parameters);
	}
	$typ_json = json_decode(get_option('eapi_' . $typ));
	$typ_json = eapi_init_typ_json($typ_json);
	
	//json_decode the entry in db.
	foreach($product_arr as $key => $value){
		if(isset($product_arr[$key]->review_information)){
				if($review_info = json_decode($product_arr[$key]->review_information)){
				$product_arr[$key]->has_reviews = $review_info->has_reviews;
				$product_arr[$key]->iframe_url = $review_info->iframe_url;
				$product_arr[$key]->num_reviews = $review_info->num_reviews;
				$product_arr[$key]->rating = $review_info->rating;
			}else{
				$product_arr[$key]->has_reviews = false;
				$product_arr[$key]->iframe_url = "";
				$product_arr[$key]->num_reviews = 0;
				$product_arr[$key]->rating = "";
			}
		}
		
		//(rating == ""), if ratings aren't loaded yet
		//check if ratings should even be loaded
		if((eapi_dominating_setting($typ_json->product_review_display, $passed_parameters['product_review']) or
			eapi_dominating_setting($typ_json->num_reviews_display, $passed_parameters['num_reviews']))
			AND $product_arr[$key]->rating == ""
			AND $product_arr[$key]->has_reviews > 0
			){
			$product_arr[$key] = eapi_handle_ratings($product_arr[$key], $passed_parameters);
		}
	}
	$ret = "";
	$increasing_number = '1';
	//'display_savings' will get true, if the savings are shown in the corner right on the top.
	$display_savings = false;
	if($typ_json->display_intext_price != ""){
		$ret .= eapi_build_price_output(array_shift($product_arr), $typ, $typ_json);
	}else if($typ_json->display_intext_picture != ""){
		$prod = array_shift($product_arr);
		$url = eapi_build_url($prod, $passed_parameters);
		$ret .= eapi_build_picture_output($prod, $typ, $url, $passed_parameters, $typ_json, $increasing_number);
	}else if($typ_json->display_intext_button != ""){
		$prod = array_shift($product_arr);
		$url = eapi_build_url($prod, $passed_parameters);
		$ret .= eapi_build_button_output($prod, $typ, $url, $passed_parameters, $typ_json);
	}else if($typ_json->display_intext_link != ""){
		$prod = array_shift($product_arr);
		$url = eapi_build_url($prod, $passed_parameters);
		$ret .= eapi_build_link_output($prod, $typ, $url, $passed_parameters, $typ_json);
	}else if($typ_json->display_intext_stars != ""){
		$prod = array_shift($product_arr);
		$url = eapi_build_url($prod, $passed_parameters);
		$ret .= eapi_build_rating_output($prod, $typ, $url, $passed_parameters, $typ_json);
	}else if($typ_json->display_intext_reviews != ""){
		$prod = array_shift($product_arr);
		$url = eapi_build_url($prod, $passed_parameters);
		$ret .= eapi_build_number_ratings($prod, $typ, $url, $passed_parameters, $typ_json);
	}else if($typ_json->display_intext_prime != ""){
		$prod = array_shift($product_arr);
		$ret .= eapi_build_prime_output($prod, $typ, $passed_parameters, $typ_json);
	}
	else{
		if($passed_parameters['head_text'] == ""){
			$head_text = $typ_json->head_text;
		}else{
			$head_text = $passed_parameters['head_text'];
		}
		if($head_text != ""){
			$head_text_color = $typ_json->head_text_color;
			$ret .= "<h2 ";
			if($head_text_color != ""){
				$ret .= "style='color: $head_text_color;'";
			}
			$ret .= " >$head_text</h2>";
		}
		$ret .= "<div ";
		$entire_border_display = $typ_json->entire_border_display;
		if($entire_border_display != ""){
			if($entire_border_display != ""){
				$ret .= "class='eapi_entire_border '";
			}
		}
		if($typ_json->entire_border_display != ""){
			$ret .= "class='eapi_entire_border' ";
		}
		
		$ret .= " >";
		foreach($product_arr as $sA){
		
			if($passed_parameters['product_title'] != ""){
				$sA->title = $passed_parameters['product_title'];
			}
			$ret .= "<div data-eapi='v=" . EAPI_VERSION . ", b=" . BUILD_VERSION . "' class='eapi_around ";
			if($typ_json->shadow_display != ""){
				$ret .= "eapi_shadow";
			}
			$ret .= "'";
			if($typ_json->border_display !="" or $typ_json->background_color !=""){
				$ret .= "style='";
			}
			if($typ_json->border_display != ""){
				$ret .= "border: 1px solid ";
				if($typ_json->border_color != ""){
					$ret .= $typ_json->border_color;
				}
			}
			if($typ_json->background_color != ""){
				$ret .= " background-color: ". $typ_json->background_color . ";";
			}
			if($typ_json->border_display !="" or $typ_json->background_color !=""){
				$ret .= "'";
			}
			$ret .= " >";
			
			//display orange badge on top left of product
			if($increasing_number < 10){
				if($passed_parameters['badge_' . $increasing_number] != ""){
					$ret .= "<span class='eapi_badget'>" . $passed_parameters['badge_' . $increasing_number] . "</span><span class='eapi_badget_triangle'></span>";
				}
			}
			
			if(isset($sA->amount_saved) AND $sA->amount_saved != "" AND  $typ_json->saving_display != ""){
				$temp_new_price = floatval(str_replace(",", ".", $sA->new_price));
				$temp_save_price = floatval(str_replace(",", ".", $sA->amount_saved));
				$saving = $temp_save_price + $temp_new_price;
				$saving = round(($temp_save_price/$saving) * 100, 0);
				$ret .= "<div class='eapi_saving_procent' >-$saving%</div>";

				$display_savings = true;
			}
			$url = eapi_build_url($sA, $passed_parameters);
			if(isset($sA->ebay_price)){	
				$ebay_url = eapi_build_url($sA, $passed_parameters, "ebay");
			}
			
			if(isset($sA->title)){
				$char_quant = $typ_json->headline_quant_char;
				if( $char_quant  !== false){
					if($char_quant !== ""){
						if(strlen($sA->title) > $char_quant ){
							$sA->title = substr($sA->title, 0, $char_quant) . "...";
						}
					}
				}
				if($typ_json->display_as_p != ""){
						$ret .= "<p";
				}else{
					$ret .= "<h3";
				}
				$ret .= " class='";
				if($typ != "sidebar"){
					$ret .= "eapi_title_around_box";
				}else{
					$ret .= " eapi_title_around";
				}
				//move headline when badge is shown
				if($increasing_number < 10){
					if($passed_parameters['badge_' . $increasing_number] != ""){
						$ret .= " eapi_title_around_box_top";
					}else{
						$ret .= " eapi_title_around_box_correct";
					}
				}
				$ret .= "'";
				if($display_savings OR $typ == "sidebar"){
					$ret .= "style='";
					if($display_savings){
						$ret .= "padding-right: 40px; ";
					}
					if($typ == "sidebar"){
						$ret .= "text-align: center;";
					}
					$ret .= "'";
				}
				
				$ret .= " >";
				
				$ret .=  "<a class='eapi_product_title' ";
				$ret .= 'title="'. eapi_html_escape($sA->title) . '"';
				
				if(get_option('eapi_analytics_tracking') != ""){
					$ret .=eapi_build_analytics_information($typ, $sA, $passed_parameters);
				}
				$headline_text_color = $typ_json->headline_text_color;
				$headline_size = $typ_json->headline_size;
				if( $headline_text_color != "" OR $headline_size != "" ){
					$ret .= " style='";
					if($headline_text_color != ""){
						$ret .= "color: " . $headline_text_color . ";";
					}
					if($headline_size != ""){
						$ret .= "font-size: " . $headline_size . "%;";
					}
					$ret .= "' ";
				}
				$ret .= "	target='_blank' rel='nofollow' href='$url' >";
				if($typ_json->number_display != ""){
					$ret .= $increasing_number . ". ";
				}
				$ret .= "$sA->title</a>";
				
				if($typ_json->display_as_p != ""){
						$ret .= "</p>";
				}else{
					$ret .= "</h3>";
				}
			}
			$ret .= eapi_build_picture_output($sA, $typ, $url, $passed_parameters, $typ_json, $increasing_number);
			$ret .= eapi_build_feature_output($sA, $typ, $url, $passed_parameters, $typ_json, $increasing_number);
	
			//display of rating and prime logo
			if(eapi_dominating_setting($typ_json->prime_display, $passed_parameters['prime'])  or
			eapi_dominating_setting($typ_json->product_review_display, $passed_parameters['product_review']) or
			eapi_dominating_setting($typ_json->num_reviews_display, $passed_parameters['num_reviews'])){
				$ret .= eapi_information_output($sA, $typ, $url, $passed_parameters, $typ_json);
			}
			//display of button and price
			if($typ_json->price_display != "" or $typ_json->button_display != ""){
				$ret .= "<div class='";
				if($typ == "sidebar"){
					$ret.="eapi_custom_info_center";
				}else{
					$ret.="eapi_custom_info";
				}
				$ret .="'>";
				if($typ != "sidebar"){
					$ret .= "<div class='eapi_shop_box'>";
				}
				$ret .= eapi_build_button_output($sA, $typ, $url, $passed_parameters, $typ_json);
				$ret .= eapi_build_price_output($sA, $typ, $typ_json);
				if($typ != "sidebar"){
					$ret .= "</div>";
				}
				if(isset($sA->ebay_price)){
					if($typ != "sidebar"){
						$ret .= "<div class='eapi_shop_box'>";	
					}
					$ret .= eapi_build_button_output($sA, $typ, $ebay_url, $passed_parameters, $typ_json, "ebay");
					$ret .= eapi_build_price_output($sA, $typ, $typ_json, "ebay");
					if($typ != "sidebar"){
						$ret .= "</div>";
					}
				}
				$ret .= "</div>";
			}
			$ret .= "</div>";
			$increasing_number++;
		}
		if(!empty($product_arr)){
			$ret .= "<div class='eapi_foot_text' >";
			if($typ_json->foot_text != ""){
				$ret .= $typ_json->foot_text;
			}
			if($typ_json->last_update != ""){
				$last_item = array_pop($product_arr);
				if(isset($last_item->time)){
					$actual_date = strtotime($last_item->time);
					$ret .= sprintf(__(' last update on: %1$s at %2$s o\'clock. ', 'easy-amazon-product-information'), date('d.m.Y', $actual_date) , date('H:i', $actual_date ));
				}else{
					date_default_timezone_set("Europe/Berlin");
					$timestamp = time();
					mktime($timestamp);
					strtotime($timestamp);
					$actual_date = $timestamp;
					$ret .= sprintf(__(' last update on: %1$s at %2$s o\'clock. ', 'easy-amazon-product-information'), date('d.m.Y', $actual_date) , date('H:i', $actual_date ));
				}
			}
			$ret .= "</div>";
		}
		$ret .= "</div>";
	}
	return $ret;
}
//builds feature output
function eapi_build_feature_output($sA, $typ, $url, $passed_parameters, $typ_json, $increasing_number){
	
	$ret = "";
	if($increasing_number < 10){
		if($passed_parameters['feature_' . $increasing_number] != ""){
			$sA->features = array();
			$i = 1;
			$temp_arr = explode("|", $passed_parameters['feature_' . $increasing_number] );
			foreach($temp_arr as $ta){
				array_push($sA->features, $ta);
			}
		}
	}
	if(isset($sA->features)){
		if(!is_array($sA->features)){
			$sA->features = stripslashes($sA->features);//needed, to prevent coding  issues
			$sA->features = json_decode($sA->features);
		}
		if(is_array($sA->features) && sizeof($sA->features) > 0){
			if($typ_json->feature_display != ""){
				$ret .="<div class='eapi_feature_div' ";
				$max_feature_height = $typ_json->max_feature_height;
				if($max_feature_height != ""){
					$ret .= "style='max-height:{$max_feature_height}px!important;'";
				}
				$ret .= "><ul class='eapi_feature_list'>";
				$feature_quant = $typ_json->feature_quant_char;
				if($feature_quant == false or $feature_quant == ""){
					$feature_quant  = 100;
				}
				$n_feature = 0;
				$feature_text_color = $typ_json->feature_text_color;
				$feature_size = $typ_json->feature_size;
			
				if(is_array($sA->features)){
					foreach($sA->features as $f){
						$ret .= "<li ";
						if($feature_text_color != "" OR $feature_size != ""){
							$ret .= " style='";
							if($feature_size != ""){
								$ret .= "font-size:" . $feature_size . "%; ";
							}
							if($feature_text_color != ""){
								$ret .= "color:" . $feature_text_color . ";";
							}
							$ret .= "'";
						}
						$ret .= ">";
						$ret .= $f;
						$ret .= "</li>";
						
						$n_feature++;
						if($n_feature >= $feature_quant){
							break;
						}
					}	
				}
				$ret .= "</ul></div>";
			}
		}
	}
	return $ret;
}

//compares the setting of the backend and the setting of the param and chooses the dominant one.
function eapi_dominating_setting($backend_state, $param_state){
	if($param_state){
		return 1;
	}else if($param_state == '' or !isset($param_state)){
		if($backend_state){
			return 1;
		}else{
			return 0;
		}
	}else{
		return 0;
	}
}
//escape strings for html
function eapi_html_escape($str){
	return htmlspecialchars($str);	
}

//build prime and rating output
function eapi_information_output($sA, $typ, $url, $passed_parameters, $typ_json){
	$ret = "";
	$ret .= "<div class='";
	if($typ == "sidebar"){
		$ret.="eapi_custom_info_center";
	}else{
		$ret.="eapi_custom_info";
	}
	$ret.="'>";
	$ret .= eapi_build_prime_output($sA, $typ, $passed_parameters, $typ_json);
	$ret .= eapi_build_rating_output($sA, $typ, $url, $passed_parameters, $typ_json);
	$ret .= eapi_build_number_ratings($sA, $typ, $url, $passed_parameters, $typ_json);
	$ret .= "</div>";
	return $ret;
}

function eapi_build_prime_output($sA, $typ, $passed_parameters, $typ_json){
	if(!in_array('amazon', $passed_parameters['shops']) && in_array('ebay', $passed_parameters['shops'])){
		$is_amazon = false;
	}else{
		$is_amazon = true;
	}
	$ret = "";
	if(eapi_dominating_setting($typ_json->prime_display, $passed_parameters['prime'])){
		if($sA->has_prime){
			$ret .= "<div class='";
		if($typ == "sidebar"){
			$ret .= "eapi_prime_wrapper_center";
		}else if($typ == "prime"){
			$ret .= "eapi_prime_intext";
		}
		else{
			if($is_amazon){
				$ret .= "eapi_prime_wrapper";
			}else{
				$ret .= "eapi_ebay_plus_wrapper";
			}
		}
		$ret .="' ><img src='". plugins_url( 'images/eapi_logos.png', __FILE__ ) . "' class='";
		if($is_amazon){
			$ret .= "eapi_prime_logo ";
		}else{
			$ret .= "eapi_ebay_plus_logo ";
		}
		if($is_amazon){
			$alt_or_title = __('prime logo', 'easy-amazon-product-information');
		}else{
			$alt_or_title = __('plus logo', 'easy-amazon-product-information');
		}
		$ret .= "'
		alt='".$alt_or_title."' title='".$alt_or_title."'></div>";
		}
	}
	return $ret;
}
function eapi_build_number_ratings($sA, $typ, $url, $passed_parameters,  $typ_json){
	$ret = "";
	if(eapi_dominating_setting($typ_json->num_reviews_display, $passed_parameters['num_reviews'])){
		if($sA->num_reviews > 0){
			$ret .= "<a  target='_blank' rel='nofollow' alt='".__('ratings', 'easy-amazon-product-information')."'  title='".__('ratings', 'easy-amazon-product-information')."' 
			";
			$ret .= " class='eapi_rating_link' ";
			if($typ_json->feature_text_color != ""){
			$ret .= " style='color:". $typ_json->feature_text_color ."'";
			}
			//set google analytics tag
			if(get_option('eapi_analytics_tracking') != ""){
				$ret .= eapi_build_analytics_information($typ, $sA, $passed_parameters);
			}
			$ret .=" href='$url"."#customerReviews"."'";
			$ret .="><span class='";
			if($typ != "sidebar" AND $typ != "reviews"){
				$ret .= "eapi_number_rating_box";
			}
			$ret .= "'";
			if($passed_parameters['review_float'] != ""){
				if($passed_parameters['review_float'] == "left"){
					$ret .=  " style='float:left!important' ";
				}
			}
			$ret .= ">";			
			$ret .=	$sA->num_reviews . " ";
			if($sA->num_reviews == 1){//manage plural
				$ret .=  __('rating', 'easy-amazon-product-information');
			}else{
				$ret .=  __('ratings', 'easy-amazon-product-information');
			}
			$ret .= "</span></a>";
		}
	}
	return $ret;
}
function eapi_build_rating_output($sA, $typ, $url, $passed_parameters, $typ_json){
	$ret = "";
	if($passed_parameters['custom_rating'] != ""){
		$sA->rating = $passed_parameters['custom_rating'];
	}
	if(eapi_dominating_setting($typ_json->product_review_display, $passed_parameters['product_review'])){
		if($sA->rating > 0 AND $sA->rating != ""){
			$ratings_round = $sA->rating;
			$star_shift = (5 - floor($ratings_round)) * 18;
			preg_match("#\.#", $ratings_round, $matches);
			$ret .= "<div class='";
			if($typ == "sidebar" OR $passed_parameters['star_float'] == 'center'){
				$ret .= "eapi_rating_wrapper_center";
			}else{
				$ret .= "eapi_rating_wrapper";
			}
			$ret .= "'";
			if($passed_parameters['star_float'] == 'left'){
				$ret .= " style='float: left;' ";
			}			
			$ret .="><a rel='nofollow' target='_blank' title='".__('amazon product ratings', 'easy-amazon-product-information')."' ";
			//set google analytics tag
			if(get_option('eapi_analytics_tracking') != ""){
				$ret .= eapi_build_analytics_information($typ, $sA, $passed_parameters);
			}
			$ret .=" href='$url".'#customerReviews'."'><img src='". plugins_url( 'images/eapi_logos.png', __FILE__ ) . "'";
			if(sizeof($matches) > 0){
				$star_shift += 180;
				$ret .= " style='left:-" . $star_shift . "px'";
			}else{
				$ret .=  " style='left:-" . $star_shift . "px'";
			}
			$ret .= " title='".__('amazon product ratings', 'easy-amazon-product-information')."' class='eapi_rating'></a></div>";
		}
	}
	return $ret;
}

//builds all parameters for analytics
function eapi_build_analytics_information($typ, $sA, $passed_parameters){
	$ret = "";
	$label = "default";
	$category = "";
	$current_page_id = get_the_ID();
	$analytic_information = $current_page_id . " | " . $typ . " | ";
	if($passed_parameters['product_link'] != ""){
		if(isset($passed_parameters['product_button'])){
			$analytic_information .= $passed_parameters['product_button'];
		}else{
			$analytic_information .= 'intern link';
		}
		$category = "eapi other";
	}else{
		$analytic_information .= $sA->asin;
		$category = "eapi amazon";
		$label =  $sA->asin;
	}
	//user can add aditional values, which are displayed in analytics later.
	if($passed_parameters['analytics_info'] != ""){
		$analytic_information .= ' | ' . $passed_parameters['analytics_info'];
	}
	
	$ret .= 'onclick="__gaTracker('."'".'send'."'".', '."'".'event'."'".', '."'".$category."'".', '."'".$analytic_information."'".', '."'".$label."'" . ')" ';
	return $ret;
}

//builds the url: if product_link == '', it's an amazon-link, else it can be an extern or intern link (not amazon).
function eapi_build_url($sA, $passed_parameters, $shop = "amazon"){
	//is an amazon-link
	$sA->shop = $shop;
	if(!(in_array("amazon", $passed_parameters['shops'])) AND (in_array("ebay", $passed_parameters['shops']))){
		$sA->shop = "ebay";
	}
	$url = "";
	if($passed_parameters['product_link'] == ''){
		if(isset($sA->asin)){
			if($sA->shop == "amazon"){
				$affili_link = get_option("eapi_affiliate_link");
				if($passed_parameters['product_affiliate'] == ""){
					if(!($affili_link) OR $affili_link === ""){
						$affili_link = "eapi-21";
					}
				}else{
					$affili_link = $passed_parameters['product_affiliate'];
				}
				$url = "https://www.amazon.de/dp/$sA->asin/?tag=" . $affili_link;
			}else if($sA->shop == "ebay"){
				if(!isset($sA->ebay_id)){
					$itm_id = $sA->asin;
				}else{
					$itm_id = $sA->ebay_id;
				}
				$campid = get_option('eapi_ebay_affiliate_id');
				$url = "https://rover.ebay.com/rover/1/707-53477-19255-0/1?toolid=10001&campid=" . $campid . "&customid=&mpre=";
				$url .= urldecode("https://www.ebay.de/itm/" . $itm_id  . "/");
			}
		}
	}else{//is not an amazon-link
		$url =  $passed_parameters['product_link'];
	}
	return $url;
}
function eapi_build_button_output($sA, $typ, $url, $passed_parameters,  $typ_json_pass, $shop = "amazon"){
	$typ_json = clone $typ_json_pass;
	$sA->shop = $shop;
	//when only ebay is displayed
	if(!in_array('amazon', $passed_parameters['shops']) && in_array('ebay', ($passed_parameters['shops']))){
		$sA->shop = "ebay";
	}
	
	if($sA->shop == "ebay"){//Just overwrite the atts temporary. Makes the following code easier.
		$typ_json->button_display = $typ_json->button_display_ebay;
		$typ_json->button_amazon_style = false;
		$typ_json->text_color = $typ_json->text_color_ebay;
		$typ_json->color = $typ_json->color_ebay;
		$typ_json->text = $typ_json->text_ebay;
		if(isset($sA->ebay_price)){//when only ebay is displayed
			$sA->new_price = $sA->ebay_price;
		}
	}
	
	$ret = "";
	if($typ_json->button_display != "" or $typ_json->button_amazon_style != "" or $passed_parameters['button_style'] != ""){
		$ret .= "<a ";
		$ret .= 'title="'. $sA->title . '"';
		if(($typ_json->button_amazon_style == "" AND $passed_parameters['button_style'] != "amazon" )
			or  $passed_parameters['button_style'] == "normal"){
			$ret .=" class='eapi_amazon_button_individual ";
		}else{
			$ret .=" class='eapi_amazon_button_style ";
		}
		if($typ == 'sidebar'){
			$ret .= " eapi_sidebar_button'";
		}else{
			if(count($passed_parameters['shops']) > 1){
				$ret .= " eapi_min_button_width";
			}
			$ret .= "'";
		}
		$color = $typ_json->color;
		$text_color = $typ_json->text_color;
		
		if((($text_color != "" or $color != "") AND ($typ_json->button_amazon_style == "" or  $passed_parameters['button_style'] == "normal"))
			or $typ == "sidebar" or $typ == "button" ){
			$ret .= " style='";
			//these properties can only be changed, if the button is in the individual style
			if(($typ_json->button_amazon_style == "" AND $passed_parameters['button_style'] != "amazon" )
			or  $passed_parameters['button_style'] == "normal"){
				if($color != "" ){
					$ret .= "background-color:". $color . "; ";
				}
				if($text_color != ""){
					$ret .= "color:" . $text_color . "; ";
				}
			}
			if($typ == "button"){//if typ is button, there should be no float
				$ret .= "float:none !important; ";
			}
			$ret .= "' ";
		}
		//set google analytics tag
		if(get_option('eapi_analytics_tracking') != ""){
			$ret .= eapi_build_analytics_information($typ, $sA, $passed_parameters);
		}
		//externe URL
		if($passed_parameters['link_intern'] == ""){
			$ret .= " target='_blank' rel='nofollow' ";
		}
		$ret .= " href='$url' >";
		
		if($typ_json->button_picture === "shipping_schwarz.png"){
			$ret.= "<img ";
		$ret .= 'title="'. $sA->title . '"';
		$ret .= " class='eapi_shipping_pic' src='". plugins_url( 'images/shipping_schwarz.png', __FILE__ ) . "' >";
		}else if($typ_json->button_picture === "shipping_weiss.png"){
			$ret.= "<img ";
		$ret .= 'title="'. $sA->title . '"';
		$ret .= " class='eapi_shipping_pic'  src='". plugins_url( 'images/shipping_weiss.png', __FILE__ ) . "' >";
		}else if($typ_json->button_picture === "shipping_amazon.png"){
			$ret.= "<img ";
		$ret .= 'title="'. $sA->title . '"';
		$ret .= " class='eapi_shipping_pic'  src='". plugins_url( 'images/shipping_amazon.png', __FILE__ ) . "' >";
		}
		
		//the inner statement is always same.
		if( $passed_parameters['product_button'] != ""){
			if(preg_match("#%1#", $passed_parameters['product_button'])){
				$ret .= preg_replace("#%1#", eapi_build_formatted_price_display($sA->new_price) ,  $passed_parameters['product_button']);
			}else{
				$ret .= $passed_parameters['product_button'];
			}
		}else{
			if(preg_match("#%1#", $typ_json->text)){
				$ret .= preg_replace("#%1#", eapi_build_formatted_price_display($sA->new_price),  $typ_json->text);
			}else{
				$ret .= $typ_json->text;
			}
		}
		$ret .= "</a>";
	}
	return $ret;
}
function eapi_build_link_output($sA, $typ, $url, $passed_parameters, $typ_json){
	
	$ret = "<a";
	$ret .= ' title="';
	if($passed_parameters['product_title'] != ""){
		$ret .= $passed_parameters['product_title'];
	}else{
		$ret .= $sA->title;
	}
	$ret .= '"';
	//externe URL
	if($passed_parameters['link_intern'] == ""){
		$ret .= " target='_blank' rel='nofollow' ";
	}
	//get google analytics code
	if(get_option('eapi_analytics_tracking') != ""){
		$ret .= eapi_build_analytics_information($typ, $sA, $passed_parameters);
	}
	$ret .= " href='$url' >";
	if($passed_parameters['product_title'] != ""){
		$ret .= $passed_parameters['product_title'];
	}else{
		$ret .= $sA->title;
	}
	$ret .= "</a>";
	return $ret;
}

function eapi_build_picture_output($sA, $typ, $url, $passed_parameters, $typ_json, $increasing_number){
	$ret = "";
	//integration with EIIG
	if($passed_parameters['eiig'] != ""){
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if(is_plugin_active('easy-integrated-image-gallery/easy-integrated-image-gallery.php')){
			$pics = json_decode($sA->large_image);
			if($passed_parameters['eiig_exclude'] != ""){
				$temp_excl = explode(",", $passed_parameters['eiig_exclude']);
				
				foreach($temp_excl as $te){
					array_splice($pics, $te - 1, $te - 1);
				}
			}
			$pics = array_slice($pics, 0,9);
			$pics_string = implode(',', $pics);
			$title = eapi_html_escape($sA->title);
			$temp_arr = array("width" => "50%", "height" => "230px", "pics" => $pics_string,
			"eapi_integration" => '1');
			if($passed_parameters['eiig_link'] != ""){
				$temp_arr['link'] = $url;
			}
			if($passed_parameters['eiig_height'] != ""){
				$temp_arr['height'] = $passed_parameters['eiig_height'];
			}
			if($passed_parameters['eiig_width'] != ""){
				$temp_arr['width'] = $passed_parameters['eiig_width'];
			}
			if($passed_parameters['eiig_text'] != ""){
				$temp_arr['text'] = $passed_parameters['eiig_text'];
			}
			if($passed_parameters['eiig_shadow'] != ""){
				$temp_arr['shadow'] = $passed_parameters['eiig_shadow'];
			}
			if($passed_parameters['eiig_border'] != ""){
				$temp_arr['border'] = $passed_parameters['eiig_border'];
			}
			if($passed_parameters['eiig_start'] != ""){
				$temp_arr['start'] = $passed_parameters['eiig_start'];
			}
			if($typ == "picture"){
				$temp_arr['eapi_picture'] = 1;
			}
			for($i = 0; $i < 10; $i++){
				$temp_arr["title_" . $i] = $title;
				$temp_arr["alt_" . $i] = $title;
			}
			$ret .= eiig_replace_eiig_tag($temp_arr);
		}	
	}else if($typ_json->picture_display != ""){
		$fallback = array('large' => 'medium', 'medium'=> 'small', 'small' => 'medium');
		$picture_url = "";
		$picture_size = "";
		$picture_resolution = "";
		$picture_variant_to_display = array();
		
		//if the user doesn't want to use the default (first) image, he can choose another picture with a parameter 
		foreach(array('small_image', 'medium_image', 'large_image') as $image){
			$temp_arr = json_decode($sA->$image);
			if($increasing_number < 10 AND 
			$passed_parameters['picture_' . $increasing_number] != "" AND 
				isset($temp_arr[$passed_parameters['picture_'. $increasing_number] - 1])){
				$picture_variant_to_display[$image] = $temp_arr[$passed_parameters['picture_'. $increasing_number] -1];
			}else{
				if(isset($temp_arr[0])){
					$picture_variant_to_display[$image] = $temp_arr[0];
				}
			}
		}
		
		//check if picture_size is passed as an parameter, overwrites the setting of backend.
		if($passed_parameters['picture_size'] != ""){
			//avoid wrong passed values
			if(array_key_exists($passed_parameters['picture_size'], $fallback)){
				$picture_size = $passed_parameters['picture_size'];
			}
		}
		if($picture_size == ""){
			$picture_size = $typ_json->picture_size;	
		}
		//set medium as default.
		if($picture_size == ""){
			$picture_size = "medium";
		}
		if($passed_parameters['picture_resolution'] != ""){
			//avoid wrong passed values
			if(array_key_exists($passed_parameters['picture_resolution'], $fallback)){
				$picture_resolution = $passed_parameters['picture_resolution'];
			}
		}if($picture_resolution == ""){
			$picture_resolution = $typ_json->picture_resolution;	
		}
		//if no picture_resolution is selected, take value of picture_size
		if($picture_resolution == ""){
			$picture_resolution = $picture_size;
		}
		$selected_size = $picture_resolution . "_image";
		$fall_attribute = $fallback[$picture_size] . "_image";
		if(isset($picture_variant_to_display[$selected_size] )){
			if($picture_variant_to_display[$selected_size]  != ""){
				$picture_url = $picture_variant_to_display[$selected_size] ;
			}else{
				$picture_size = $fallback[$picture_size];
				$picture_url = $picture_variant_to_display[$fall_attribute];
			}
		}else if(isset( $picture_variant_to_display[$fall_attribute])){
			$picture_size = $fallback[$picture_size];
			$picture_url = $picture_variant_to_display[$fall_attribute];
		}
		if(get_option('eapi_image_cache') AND $picture_url){//DSGVO cache images
			if(!(picture_is_cached($sA->asin))){
				cache_picture($picture_url, $sA->asin);
			}
			$picture_url = get_option('siteurl') . '/wp-content/plugins/easy-amazon-product-information/cache/' . $sA->asin  . '.jpg';
		}else if($picture_url){
			$picture_url = preg_replace('/http(?!s)/', 'https', $picture_url);
			$picture_url = str_replace('http://ecx.', 'https://images-na.ssl-', $picture_url);	
		}else {
			$picture_url = plugins_url( 'images/default.png', __FILE__ );
		}
		if($picture_url != ""){
			$ret .= "<a";
			$ret .= ' title="'. eapi_html_escape($sA->title) . '"';
			$ret .= " target='_blank' rel='nofollow' href='$url'><img";
			$ret .= ' title="'. eapi_html_escape($sA->title) . '"';
			$ret .= ' alt="'. eapi_html_escape($sA->title) . '" ';
			if(get_option('eapi_analytics_tracking') != ""){
				$ret .=eapi_build_analytics_information($typ, $sA, $passed_parameters);
			}
			$ret .= " class='";
			if($typ == "sidebar"){
				$ret .="eapi_product_image";
			}else{
				$ret .="eapi_product_image_box";
			}
			$ret .= "' ";
			$ret .= "style='";
			if($passed_parameters['picture_float'] == "right"){
				$ret .= "float:right!important;";
			}else if($passed_parameters['picture_float'] == "center"){
				$ret .= "margin:auto!important;float:none!important;";
			}		
			if($typ == 'picture'){
				$ret .= "margin: 15px;";
			}
			if($typ != "sidebar"){
				switch($picture_size){
					case "small":
						$ret .= "max-width:15%;";
					break;
					case "medium":
						$ret .= "max-width:30%;";
					break;
				}
			}
			$ret .= "'";
			$ret .= " src='$picture_url'></a>";
		}
	}
	return $ret;
}
function picture_is_cached($name){
	return file_exists(EAPI_PLUGIN_DIR . 'cache/' . $name . '.jpg');
}
function cache_picture($url, $name){
	file_put_contents(EAPI_PLUGIN_DIR . '/cache/' . $name . '.jpg', file_get_contents($url));
}
function eapi_build_price_output($sA, $typ, $typ_json, $shop = "amazon"){
	$sA->shop = $shop;
	if($sA->shop == "ebay"){
		$sA->new_price = $sA->ebay_price;
		$sA->amount_saved = 0;
	}
	$ret = "";
	if($typ_json->price_display != ""){
		$price_color = $typ_json->price_color;
		if(isset($sA->new_price)){
			$ret .= "<span ";
			if($typ != "price"){
				$ret .= " class='eapi_price_field ";
				if($typ != "sidebar"){
					$ret .= "eapi_price_field_box";
				}else{
					$ret .= "eapi_price_field_center";
				}
				$ret .= "' ";
			}
			//change of custom colour
			if($price_color != ""){
				$ret .= "style='";
					$ret .= "color: ".$price_color."; ";
				$ret .= "'";
			}
			$ret .= ">";
			$price_pre_text = $typ_json->price_pre_text;
			$price_after_text = $typ_json->price_after_text;
			if($price_pre_text != ""){
				$ret .= "<span>$price_pre_text</span>";
			}
			$ret .= eapi_build_formatted_price_display($sA->new_price);
			if($price_after_text != ""){
				$ret .= "<span>$price_after_text</span>";
			}
			if($typ != "price"){
				$ret .= "</span>";
			}
		}
		if(isset($sA->amount_saved) AND $typ_json->saving_text != ""){
			if($sA->amount_saved != ""){
				if($typ != "price"){
					$ret .= "<span class='eapi_price_amount_saved ";
					if($typ != "sidebar"){
						$ret .= "eapi_price_amount_saved_box";
					}
					$ret .= "' ";
					if($price_color != ""){
						$ret .= "style='color: ".$price_color.";'";
					}
					$ret .= ">";
				}
				$saving_pre_text = $typ_json->saving_pre_text;
				if($saving_pre_text != ""){
					$ret .= "<span>$saving_pre_text</span>";
				}
				$temp_saved = eapi_build_formatted_price_display($sA->new_price, $sA->amount_saved);
				
				$ret .= "<s> ". $temp_saved. "</s>";
				if($typ != "price"){
					$ret .= "</span>";
				}
			}
		}
	}
	return $ret;
}
//returns the price in a formatted way for output
//$opt_price2 ist optional and gets added to the first value
function eapi_build_formatted_price_display($price1, $opt_price2 = null){
	if($price1 == "" OR $price1 == 0){
		return "(Nicht verfügbar)";
	}
	if(isset($opt_price2)){
	return  number_format(str_replace(",", ".", trim(str_replace(array("EUR", "."), "", $opt_price2))) +
				str_replace(",", ".", trim(str_replace(array("EUR", "."), "", $price1))), 2, ",", ".") . " €";
	}else{
	 return	number_format(str_replace(",", ".", trim(str_replace(array("EUR", "."), "", $price1))), 2, ",", ".") . " €";
	}
}

function eapi_eapi_plugin_menu() {
	if(get_option('eapi_version') !=  EAPI_VERSION){
		eapi_install();
	}
	add_options_page( 'EAPI Adminportal', 'Easy Amazon Product Information', 'manage_options', 'easy_amazon_product_information', 'eapi_show_easy_amazon_product_information_options' );
}

//checks the credentials by making a sample request to the database
function eapi_check_credentials($debug){
	update_option('eapi_personal_error', 'no error');
	if(! (count(json_decode(eapi_get_amazon_products(array('n' => 1, 'product' => 'Nischenseite', 'debug' => $debug))))> 0)){
		update_option('eapi_error', __('WARNING: Please check if you access Key ID, Secret Access Key and/or your associate ID is correct. Click on \'save \' to check it.', 'easy-amazon-product-information')
		.' <a target=\'_blank\' 	href=\'http://jensmueller.one/eapi-fehlersuche/\'>' .  __('More information to find the mistake.', 'easy-amazon-product-information') . '</a>');
	}else{
		delete_option('eapi_error');
	}
}

function eapi_load_css(){
	wp_register_style( 'prefix-style', plugins_url('easy_amazon_product_information.css', __FILE__) );
	wp_enqueue_style( 'prefix-style' );
	
	if(HAS_EAPI_PLUS){
		wp_enqueue_script( 'eapi-nice-js', plugins_url('eapi_plus.js', __FILE__ ), array('jquery'), null, true);
		wp_enqueue_script( 'eapi-nice-js' );
		wp_register_style( 'eapi-nice-css', plugins_url('eapi_plus.css', __FILE__ ));
		wp_enqueue_style( 'eapi-nice-css' );
	}
}
//checks the timestamp of the entries, with the intervall-time.
function check_if_local_data_is_actual($data_from_local_WP){
date_default_timezone_set("Europe/Berlin");
$timestamp = time();
mktime($timestamp);
$eapi_inter_vall_time_in_hours = get_option("eapi_inter_vall_time_in_hours");
if($eapi_inter_vall_time_in_hours){
	$inter_vall_time = $eapi_inter_vall_time_in_hours;
}
else{
	$inter_vall_time = 12;
}

$inter_vall_time =  $inter_vall_time  *60*60;

foreach($data_from_local_WP as $d){
	if($d){
		if(abs(strtotime($d->time) - $timestamp) > $inter_vall_time ){
			return false;
		}
	}
}
return true;
}

//deletes all in the data table. Function of the Caching-Button
function delete_all_cached_data(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'easy_amazon_product_information_data';
	$wpdb->query( "DELETE FROM $table_name  WHERE 1 ");
	$files = glob('../wp-content/plugins/easy-amazon-product-information/cache/*.jpg'); //get all file names
	foreach($files as $file){
		if(is_file($file))
		unlink($file); //delete file
	}
}

//deletes all entries in db, with this keyword
function eapi_delete_with_this_keyword($keyword){
	global $wpdb;
	$table_name = $wpdb->prefix . 'easy_amazon_product_information_data';

	$wpdb->query( 
		$wpdb->prepare( 
			"DELETE FROM $table_name
			 WHERE keyword = '%s' 
			",$keyword
			)
	);
}
//return the entries in db, matching $keyword, limited to the given $count
function eapi_get_data_from_WP_database($keyword, $count, $passed_parameters)
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'easy_amazon_product_information_data';
	$sql_arr = array();
	foreach($passed_parameters['shops'] as $shop){
		$sql = $wpdb->prepare(
		"(SELECT * FROM $table_name
		WHERE keyword = %s
		AND shop = %s
		limit $count)", $keyword, $shop);
		array_push($sql_arr, $sql);
	}
	$db_result = $wpdb->get_results(implode(" UNION ", $sql_arr));
	return $db_result;
}
function eapi_store_data_in_WP_database($keyword, $product_array)
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'easy_amazon_product_information_data';
	$sql = "INSERT into $table_name (id, time, asin, keyword,
	title, small_image, medium_image, large_image, description,
	features, amount_saved, new_price, review_information,
	has_prime, availability, shop, ean) values";
	$arr = array();
	foreach($product_array as $pA){
		if(!isset($pA->features)){
			$pA->features = "";
		}else{
			$json_test = true;
			if(!is_array($pA->features)){
				json_decode($pA->features);
				$json_test = false;
			}//it must be prevented that the 'preg_replace' function is executed twice on a json object
			if(!(json_last_error() == JSON_ERROR_NONE) || ($json_test)){
				$pA->features = preg_replace("#\"#", "[-]" ,$pA->features);
				$pA->features = json_encode( $pA->features, JSON_UNESCAPED_UNICODE);
			}
		}
		if(!isset($pA->title)){
			$pA->title = "";
		}
		if(!isset($pA->description)){
			$pA->description = "";
		}
		if(!isset($pA->amount_saved)){
			$pA->amount_saved = "";
		}
		if(!isset($pA->small_image)){
			$pA->small_image = "";
		}
		if(!isset($pA->medium_image)){
			$pA->medium_image = "";
		}
		if(!isset($pA->large_image)){
			$pA->large_image = "";
		}
		if(!isset($pA->has_reviews)){
			$pA->has_reviews = 0;
		}
		if(!isset($pA->iframe_url)){
			$pA->iframe_url = "";
		}
		if(!isset($pA->num_reviews)){
			$pA->num_reviews = 0;
		}
		if(!isset($pA->rating)){
			$pA->rating = "";
		}
		if(!isset($pA->has_prime)){
			$pA->has_prime = 0;
		}
		if(!isset($pA->availability)){
			$pA->availability = "";
		}
		if(!isset($pA->shop)){
			$pA->shop = "";
		}
		if(!isset($pA->ean)){
			$pA->ean = "";
		}
		if(!isset($pA->ean)){
			$pA->ean = "";
		}
		if(!isset($pA->review_information)){
			$pA->review_information = json_encode(array());
		}
		//remove some characters
		$pA->title = esc_sql( $pA->title);
		$pA->description = esc_sql( $pA->description);
		$pA->features = esc_sql($pA->features);
			array_push($arr, "('', '" .current_time( 'mysql'). "', '$pA->asin', '$keyword',
			'$pA->title', '$pA->small_image', '$pA->medium_image', '$pA->large_image',
			'$pA->description','$pA->features', '$pA->amount_saved', '$pA->new_price',
			'$pA->review_information', '$pA->has_prime', '$pA->availability', '$pA->shop',
			'$pA->ean')");
	}
	$sql .= implode($arr, ", ");
	$wpdb->query($sql);
}
function eapi_get_array_of_all_parameters($restriction){
	$arr = array("text_color", "color", "text", "headline_text_color", "headline_quant_char", "feature_text_color",
			"feature_quant_char", "feature_display", "picture_display", "headline_size", "feature_size", "border_display",
			"number_display", "button_picture", "last_update", "foot_text", "saving_display", "saving_text", "head_text",
			"background_color", "price_color", "display_as_p",  "button_display", "head_text_color",
			"entire_border_display", "price_pre_text", "price_after_text", "saving_pre_text", "picture_size",
			"max_feature_height", "price_display", "picture_resolution", "display_intext_link", "button_amazon_style",
			"prime_display", "product_review_display", "num_reviews_display", "border_color","shadow_display",
			"text_color_ebay", "button_display_ebay", "color_ebay", "text_ebay"
			);
	//sometimes not all paramets should be returned: These paremetes can't be set by the user.
	if(! ($restriction)){
		$arr = array_merge($arr, array("display_intext_price", "display_intext_picture", "display_intext_button",
		"display_intext_stars", "display_intext_reviews", "display_intext_prime"));	
	}
	return $arr;
}
//function is called, when installing the plugin.
// AND when a different version is detected
function eapi_install() {
	$arr_of_all_params = eapi_get_array_of_all_parameters(false);
	$all_start_options_in_one_array  = array('standard' => array(), 'sidebar'=> array(), 'negative'=> array(), 'button'=> array(), 'picture'=> array(), 'price'=> array(), 'link' => array());
	
	foreach($all_start_options_in_one_array as $key => $value){
		$arr = array();
		//$pre_values are the values from before.
		$pre_values = json_decode(get_option("eapi_" . $key));
		foreach($arr_of_all_params as $a_param){
			if($pre_values == null){
				$arr[$a_param] = "";
			}else if(!(isset($pre_values->$a_param))){
				$arr[$a_param] = "";
			}else{
				$arr[$a_param] = $pre_values->$a_param;
			}
		}
		$all_start_options_in_one_array[$key] = $arr;	
	}
	//default-values are saved in db,
	//if the plugin is installed the first time (version-number not bigger than 0.0)
	//else, no default-values
	if( get_option('eapi_version') == null){
		//Default-Values in Database.
		foreach(array("standard", "sidebar", "negative") as $a){
			$all_start_options_in_one_array[$a]['feature_display'] = 'on';
			$all_start_options_in_one_array[$a]['picture_display'] = 'on';
			$all_start_options_in_one_array[$a]['text'] = 'Bei Amazon kaufen!';
			$all_start_options_in_one_array[$a]['text_color'] = '#ffffff';
			$all_start_options_in_one_array[$a]['color'] = '#3d94f6';
			$all_start_options_in_one_array[$a]['feature_quant_char'] = '3';
			$all_start_options_in_one_array[$a]['button_display'] = 'on';
			$all_start_options_in_one_array[$a]['price_display'] = 'on';
			$all_start_options_in_one_array[$a]['picture_size'] = 'medium';
			$all_start_options_in_one_array[$a]['picture_resolution'] = 'large';
			$all_start_options_in_one_array[$a]['prime_display'] = 'on';
		}
		
		$all_start_options_in_one_array['negative']['background_color'] = '#666666';
		$all_start_options_in_one_array['negative']['feature_text_color'] = '#ffffff';
		$all_start_options_in_one_array['negative']['headline_text_color'] = '#ffffff';
		$all_start_options_in_one_array['negative']['price_color'] = '#ffffff';
		$all_start_options_in_one_array['negative']['button_picture'] = 'shipping_schwarz.png';
		$all_start_options_in_one_array['negative']['text_color'] = '#000000';
		$all_start_options_in_one_array['negative']['color'] = '#FFFFFF';
		
		$all_start_options_in_one_array['price']['display_intext_price'] = 'on';
		$all_start_options_in_one_array['price']['price_display'] = 'on';
		
		$all_start_options_in_one_array['sidebar']['text'] = 'Prüfen!';
		$all_start_options_in_one_array['sidebar']['feature_display'] = '';
		
		$all_start_options_in_one_array['button']['display_intext_button'] = 'on';
		$all_start_options_in_one_array['button']['button_display'] = 'on';
		$all_start_options_in_one_array['button']['text'] = __('Buy on Amazon!', 'easy-amazon-product-information');
		$all_start_options_in_one_array['button']['text_color'] = '#ffffff';
		$all_start_options_in_one_array['button']['color'] = '#3d94f6';
		
		$all_start_options_in_one_array['picture']['display_intext_picture'] = 'on';
		$all_start_options_in_one_array['picture']['picture_display'] = 'on';
		$all_start_options_in_one_array['picture']['picture_size'] = 'medium';
		$all_start_options_in_one_array['picture']['picture_resolution'] = 'large';
		
		update_option("eapi_inter_vall_time_in_hours", '48');
	}
	
	//options for new functions are going to be set here, according to the required version
	//new version is set later
	//...
	//default values for versions (<) VERSION.NUMBER
	if( version_compare(get_option('eapi_version'), "1.0.8")< 0){
		//property display_intext_link is set true for link
		$all_start_options_in_one_array['link']['display_intext_link'] = '1';
		$all_start_options_in_one_array['sidebar']['price_display'] = 'on';
		$all_start_options_in_one_array['standard']['price_display'] = 'on';
		$all_start_options_in_one_array['negative']['price_display'] = 'on';
		$all_start_options_in_one_array['price']['price_display'] = 'on';
	}
	if( version_compare(get_option('eapi_version'), "1.2.0")< 0){
		//button_amazon_style is set off for all types
		$all_start_options_in_one_array['sidebar']['button_amazon_style'] = '';
		$all_start_options_in_one_array['negative']['button_amazon_style'] = '';
		$all_start_options_in_one_array['standard']['button_amazon_style'] = '';
		$all_start_options_in_one_array['standard']['button_amazon_style'] = '';
		$all_start_options_in_one_array['button']['button_amazon_style'] = '';
	}
	if( version_compare(get_option('eapi_version'), "2.0.0")< 0){
	/*	preg_match( "#[a-zA-Z0-9\-äöüÄÖÜß]*\.[a-z0-9]*$#", get_bloginfo('url'), $matches);
		if(isset($matches[0])){
			$domain_name = $matches[0];
			$domain_name = 'eapi@'.$domain_name;
		}else{
			$domain_name = '';
		}
		update_option("eapi_mail_sender", $domain_name);
		update_option("eapi_mail_receiver", get_option('admin_email'));
		*///not needed any longer, old function for notification mail
	}
	if( version_compare(get_option('eapi_version'), "2.1.0")< 0){
		$all_start_options_in_one_array['standard']['border_color'] = '#c1baba';
		$all_start_options_in_one_array['negative']['border_color'] = '#c1baba';
		$all_start_options_in_one_array['sidebar']['border_color'] = '#c1baba';
	}
	if( version_compare(get_option('eapi_version'), "2.3.0") < 0){
		$all_start_options_in_one_array['stars']['product_review_display'] = '1';
		$all_start_options_in_one_array['stars']['display_intext_stars'] = '1';
		$all_start_options_in_one_array['reviews']['num_reviews_display'] = '1';
		$all_start_options_in_one_array['reviews']['display_intext_reviews'] = '1';
	}
	if( version_compare(get_option('eapi_version'), "2.6.0") < 0){
		$all_start_options_in_one_array['prime']['display_intext_prime'] = '1';
		$all_start_options_in_one_array['prime']['prime_display'] = '1';
		delete_option('eapi_notification_log');
		delete_option("eapi_email_notification");
		delete_option('eapi_mail_sender');
		delete_option("eapi_mail_receiver");
	}
	if( version_compare(get_option('eapi_version'), "3.1.0") < 0){
		$all_start_options_in_one_array['standard']['button_display_ebay'] = '1';
		$all_start_options_in_one_array['negative']['button_display_ebay'] = '1';
		$all_start_options_in_one_array['sidebar']['button_display_ebay'] = '1';
		$all_start_options_in_one_array['button']['button_display_ebay'] = '1';
		$all_start_options_in_one_array['standard']['text_ebay'] = 'Bei Ebay kaufen!';
		$all_start_options_in_one_array['negative']['text_ebay'] = 'Bei Ebay kaufen!';
		$all_start_options_in_one_array['sidebar']['text_ebay'] = 'Bei Ebay kaufen!';
		$all_start_options_in_one_array['standard']['color_ebay'] = '#3d94f6';
		$all_start_options_in_one_array['negative']['color_ebay'] = '#000';
		$all_start_options_in_one_array['sidebar']['color_ebay'] = '#3d94f6';
		$all_start_options_in_one_array['standard']['text_color_ebay'] = '#fff';
		$all_start_options_in_one_array['sidebar']['text_color_ebay'] = '#fff';
		$all_start_options_in_one_array['button']['text_color_ebay'] = '#fff';
		$all_start_options_in_one_array['negative']['text_color_ebay'] = '#fff';
		delete_option('eapi_small');
	}
	//saving all these options in db
	foreach($all_start_options_in_one_array as $key => $value){
		update_option("eapi_" . $key, json_encode($value));
	}
		
	//checks credentials
	eapi_check_credentials(false);
	
	global $wpdb;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	$charset_collate = $wpdb->get_charset_collate();
	
	//delete first the old table
	eapi_delete_eapi_database();
	
	//create new table
	$table_name_data = $wpdb->prefix . 'easy_amazon_product_information_data';
	$sql = "CREATE TABLE $table_name_data (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		asin varchar(100) NOT NULL,
		keyword varchar(100) NOT NULL,
		title text NOT NULL,
		small_image varchar(2000),
		medium_image varchar(2000),
		large_image varchar(2000),
		description text,
		features text,
		amount_saved varchar(50),
		new_price varchar(50) NOT NULL,
		review_information varchar(300),
		has_prime boolean,
		availability varchar(100),
		shop varchar(50),
		ean varchar(50),
		UNIQUE KEY id (id)
	) $charset_collate;";
	
	update_option('eapi_version', EAPI_VERSION);
	dbDelta( $sql );
}
function eapi_delete_eapi_database(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'easy_amazon_product_information_data';
	$wpdb->query("DROP TABLE IF EXISTS $table_name; ");
}
?>