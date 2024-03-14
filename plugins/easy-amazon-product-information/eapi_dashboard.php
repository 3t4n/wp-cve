<?php

function eapi_show_easy_amazon_product_information_options() {
	global $wpdb;
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	//save private key
	if(isset($_POST['eapi_secret_access_key'])){
		update_option("eapi_secret_access_key", trim($_POST['eapi_secret_access_key']));
	}
	if(isset($_POST['eapi_access_key_id'])){
		update_option("eapi_access_key_id", trim($_POST['eapi_access_key_id']));
	}
	if(isset($_POST['eapi_ebay_affiliate_id'])){
		update_option("eapi_ebay_affiliate_id", trim($_POST['eapi_ebay_affiliate_id']));
	}
	if(isset($_POST['eapi_ebay_app_id'])){
		update_option("eapi_ebay_app_id", trim($_POST['eapi_ebay_app_id']));
	}
	//save intervalltime
	if(isset($_POST['inter_vall_time_in_hours'])){
		if(is_numeric($_POST['inter_vall_time_in_hours'])){
			if($_POST['inter_vall_time_in_hours'] < 1){
				$_POST['inter_vall_time_in_hours'] = 1;
			}
			update_option("eapi_inter_vall_time_in_hours", $_POST['inter_vall_time_in_hours']);
		}
	}
	//save affiliate link
	if(isset($_POST['affiliate_link'])){
		update_option("eapi_affiliate_link", $_POST['affiliate_link']);
	}
	if(isset($_POST['eapi_top_area_submit'])){
	//activate/deactivate click tracking
		if(!isset($_POST['eapi_analytics_tracking'])){
			delete_option("eapi_analytics_tracking");
		}else{
			update_option("eapi_analytics_tracking", $_POST['eapi_analytics_tracking']);
		}
	//activate/deactivate IMAGE caching for DSGVO
		if(!isset($_POST['eapi_image_cache'])){
			delete_option("eapi_image_cache");
		}else{
			update_option("eapi_image_cache", $_POST['eapi_image_cache']);
		}
	}
		
	//checks the api key, with a sample request to the api.
	if(isset($_POST['check_api_key'])){
		eapi_check_credentials(false);
		if(HAS_EAPI_PLUS){
			eapi_check_ebay_credentials(false);
		}
	}
	//delete cache
	if(isset($_POST['eapi_cache_clear'])){
		delete_all_cached_data();
	}
	//builds affiliate link test for amazon partner net
	$temp_obj = new stdClass();
	$temp_obj->asin = "B0054PDOV8";
	$link_for_affiliate_test = "https://partnernet.amazon.de/gp/associates/network/tools/link-checker/main.html?link=".eapi_build_url($temp_obj,array('product_link' => '', 'product_affiliate' => '', 'shops' => array('amazon')));

	$typ_for_tabs = array("standard", "sidebar", "negative", "price", "button", "picture", "link");
	//checks for updates via $_POST submit.
	eapi_check_For_tab_updates_in_post($typ_for_tabs);
	
	$eapi_error = get_option('eapi_error');
	$eapi_ebay_error = get_option('eapi_ebay_error');
	
	echo '<div class="wrap">';
	$own_site_url =  eapi_get_own_site_url();
	echo "<h2 style='margin-bottom: 20px;'><a target='_blank' href='http://jensmueller.one/easy-amazon-product-information/'><img style='margin-bottom: -20px; margin-right: 20px;' src='". plugins_url( 'images/logo_top_120_61.png', __FILE__ ) . "' alt='Easy Amazon Product Information'></a>Easy Amazon Product Information</h2>";
	if(!HAS_EAPI_PLUS){
		echo "<span class='eapi_buy_plus'>Hol dir jetzt <a href='http://jensmueller.one/eapi-plus/' target='_blank'>EAPI PLUS</a>!</span>";
	}
	echo "<form method='post' action='' >";
	echo "<input type='hidden' name='check_api_key' value='1'>";
	echo "<input type='hidden' name='eapi_top_area_submit' value='1'>";
	if($eapi_error != ""){
		echo "<div class='error'><p>$eapi_error</p></div>";
		echo "<div class='error'><p>".get_option('eapi_personal_error')."</p></div>";
	}
	if(HAS_EAPI_PLUS){
		if($eapi_ebay_error != ""){
			echo "<div class='error'><p>$eapi_ebay_error</p></div>";
			echo "<div class='error'><p>".get_option('eapi_personal_error')."</p></div>";
		}
	}
	
	echo "<table>";
	echo "<tr>";
	echo "<td>".__('Access Key ID', 'easy-amazon-product-information').":</td><td><input name='eapi_access_key_id' type='text' value='".get_option("eapi_access_key_id")."'></td>";
	if(! $eapi_error == ""){
		echo "<td><img src='". plugins_url( 'images/eapi_false.png', __FILE__ ) . "' alt='EAPI Fehler!' title='EAPI Fehler!' ></td>";
	}else{
		echo "<td><img src='". plugins_url( 'images/eapi_ok.png', __FILE__ ) . "' alt='EAPI funktioniert!' title='EAPI funktioniert!' ></td>";
	}
	echo "<td>".__('Secret Access Key', 'easy-amazon-product-information').":</td><td><input name='eapi_secret_access_key' type='password' value='".get_option("eapi_secret_access_key")."'></td>";
	if(! $eapi_error == ""){
		echo "<td><img src='". plugins_url( 'images/eapi_false.png', __FILE__ ) . "' alt='EAPI Fehler!' title='EAPI Fehler!' ></td>";
	}else{
		echo "<td><img src='". plugins_url( 'images/eapi_ok.png', __FILE__ ) . "' alt='EAPI funktioniert!' title='EAPI funktioniert!' ></td>";
	}
	echo "<td>".__('Tracking ID', 'easy-amazon-product-information' ).":</td><td><input name='affiliate_link' type='text' value='".get_option("eapi_affiliate_link")."'></td>";
	if(!$eapi_error == "" or get_option("eapi_affiliate_link") == ""){
		echo "<td><img src='". plugins_url( 'images/eapi_false.png', __FILE__ ) . "' alt='EAPI Fehler!' title='EAPI Fehler!' ></td>";
	}else{
		echo "<td><img src='". plugins_url( 'images/eapi_ok.png', __FILE__ ) . "' alt='EAPI funktioniert!' title='EAPI funktioniert!' ></td>";
	}
	echo "</tr>";
	
	if(HAS_EAPI_PLUS){
		echo "<tr><td>".__('Ebay App ID', 'easy-amazon-product-information').":</td><td><input name='eapi_ebay_app_id' type='text' value='".get_option("eapi_ebay_app_id")."'></td>";
		if(! $eapi_ebay_error == "" or get_option("eapi_ebay_app_id") == ""){
			echo "<td><img src='". plugins_url( 'images/eapi_false.png', __FILE__ ) . "' alt='EAPI Fehler!' title='EAPI Fehler!' ></td>";
		}else{
			echo "<td><img src='". plugins_url( 'images/eapi_ok.png', __FILE__ ) . "' alt='EAPI funktioniert!' title='EAPI funktioniert!' ></td>";
		}
		echo "<td>".__('Ebay Affiliate ID', 'easy-amazon-product-information').":</td><td><input name='eapi_ebay_affiliate_id' type='text' value='".get_option("eapi_ebay_affiliate_id")."'></td>";
		if(! $eapi_ebay_error == "" or get_option("eapi_ebay_affiliate_id") == ""){
			echo "<td><img src='". plugins_url( 'images/eapi_false.png', __FILE__ ) . "' alt='EAPI Fehler!' title='EAPI Fehler!' ></td>";
		}else{
			echo "<td><img src='". plugins_url( 'images/eapi_ok.png', __FILE__ ) . "' alt='EAPI funktioniert!' title='EAPI funktioniert!' ></td>";
		}
		echo "</tr>";
	}
	echo "<tr><td>".__('analytics tracking', 'easy-amazon-product-information').":</td><td>
	<input type='checkbox' value='on' name='eapi_analytics_tracking' ";
	if(get_option("eapi_analytics_tracking") != ""){
		echo "checked";
	}
	echo "><td></td><td>".__('Image Cache', 'easy-amazon-product-information')."<a href='http://jensmueller.one/blog/eapi-und-die-dsgvo/'>?</a>:</td><td>
	<input type='checkbox' value='on' name='eapi_image_cache' ";
	if(get_option("eapi_image_cache") != ""){
		echo "checked";
	}
	echo "
	</td><td></td><td>".__('interval time in hours', 'easy-amazon-product-information').":</td><td><input name='inter_vall_time_in_hours' type='text' value='".get_option("eapi_inter_vall_time_in_hours")."'></td>";
	echo "
	<td></td><td></td>
	<td></td><td><input type='submit' value='".__('save', 'easy-amazon-product-information')."' style='font-weight: 600;' ></td></tr>";
	echo "</table></form>";
	echo "<table><tr><td><form  method='post' action=''><input type='hidden' value='1' name='eapi_cache_clear'><input type='submit' value='".__('delete cache', 'easy-amazon-product-information')."'></form></td>
				<td><form action='".$link_for_affiliate_test."' target='_blank' method='post' ><input type='hidden' value='1' name='eapi_affiliate_test'><input title='".__('Checks with the amazon link tester, if you have inserted your valid affiliat tag. Leads to an extern url where amazon login is required.', 'easy-amazon-product-information')."'  type='submit' value='".__('affiliate link test', 'easy-amazon-product-information')."'></form></td>
				</tr>";
	echo "</table>";
		
	//richtige Tab laden:
	$selected_tab = array();
	$set_one = false;
	foreach($typ_for_tabs as $key => $value){
		if(isset($_POST[$value. "_form"])){
			$selected_tab[$key]['tab'] = 'nav-tab-active';
			$selected_tab[$key]['text'] = 'nav-text-visible';
			$set_one = true;
		}else{
			$selected_tab[$key]['tab'] = "";
			$selected_tab[$key]['text'] = "";
		}
	}
	
	//Array is empty -> load first tab as default
	if(!($set_one)) {
		$selected_tab[0]['tab'] = 'nav-tab-active';
		$selected_tab[0]['text'] = 'nav-text-visible';
	}
	echo "<h2 class='nav-tab-wrapper'>
	<a href='#' data-tab='0' class='nav-tab  ".$selected_tab[0]['tab']."'>".__('standard', 'easy-amazon-product-information')."</a>
	<a href='#' data-tab='1' class='nav-tab  ".$selected_tab[1]['tab']."'>".__('sidebar', 'easy-amazon-product-information')."</a>
    <a href='#' data-tab='2'  class='nav-tab ".$selected_tab[2]['tab']."'>".__('negative', 'easy-amazon-product-information')."</a>
    <a href='#' data-tab='3'  class='nav-tab ".$selected_tab[3]['tab']."'>".__('price', 'easy-amazon-product-information')."</a>
    <a href='#' data-tab='4'  class='nav-tab ".$selected_tab[4]['tab']."'>".__('button', 'easy-amazon-product-information')."</a>
    <a href='#' data-tab='5'  class='nav-tab ".$selected_tab[5]['tab']."'>".__('picture', 'easy-amazon-product-information')."</a>
    <a href='#' data-tab='6'  class='nav-tab ".$selected_tab[6]['tab']."'>".__('link', 'easy-amazon-product-information')."</a>
    <a href='http://jensmueller.one/easy-amazon-product-information/' target='_blank' data-tab='9'  class='nav-tab '>".__('help', 'easy-amazon-product-information')."</a>
	</h2>";
	echo "<div data-id='0' class='nav-text ".$selected_tab[0]['text']."' >
	". eapi_build_tab_content("standard")."
	</div>";
	echo "<div data-id='1' class='nav-text  ".$selected_tab[1]['text']."' >
	". eapi_build_tab_content("sidebar")."
	</div>";
	echo "<div data-id='2' class='nav-text  ".$selected_tab[2]['text']."' >
	". eapi_build_tab_content("negative")."
	</div>";
	echo "<div data-id='3' class='nav-text  ".$selected_tab[3]['text']."' >
	". eapi_build_tab_content("price")."
	</div>";
	echo "<div data-id='4' class='nav-text  ".$selected_tab[4]['text']."' >
	". eapi_build_tab_content("button")."
	</div>";
	echo "<div data-id='5' class='nav-text  ".$selected_tab[5]['text']."' >
	". eapi_build_tab_content("picture")."
	</div>";
	echo "<div data-id='6' class='nav-text  ".$selected_tab[6]['text']."' >
	". eapi_build_tab_content("link")."
	</div>";
	echo "<p>" . sprintf(__('version: %1$s | <a href='."'"."http://jensmueller.one"."'"." target="."'"."_blank"."'".'>jensmueller.one</a> |', 'easy-amazon-product-information'), EAPI_VERSION);
	echo eapi_get_donate_button() ." | <a href='https://www.amazon.de/?tag=jensmueller-21' target='_blank' >".__('support me with your next amazon buy', 'easy-amazon-product-information') ."</a> | " . eapi_get_number_of_entries_in_db(). ' ' .  __('entries in DB', 'easy-amazon-product-information') . ' | ' . eapi_get_number_of_cached_images() . ' ' . __('images cached', 'easy-amazon-product-information') . ' | *'.__('Settings will consist.', 'easy-amazon-product-information') ;

	echo  '</p>';
	echo '</div>';				
}

//Builds the Backend.
function eapi_build_tab_content($tab_name){
	$opts_to_display = array();
	$opts_to_display['price'] = array('price');
	$opts_to_display['button'] = array('button');
	$opts_to_display['picture'] = array('picture');
	$opts_to_display['standard'] = array('allg', 'button', 'head', 'features', 'picture', 'amazon information','price', 'footer');
	$opts_to_display['negative'] = array('allg', 'button', 'head', 'features', 'picture', 'amazon information', 'price', 'footer');
	$opts_to_display['sidebar'] = array('allg', 'button', 'head', 'picture', 'price', 'amazon information', 'footer');
	$opts_to_display['link'] = array();
	
	$ret = "";
	$ret .= "<form method='post' action='' >";
	$ret .="<p>".__('name of tag', 'easy-amazon-product-information').": <b>[eapi type=$tab_name]</b></p>";
	$typ_json = json_decode(get_option("eapi_" . $tab_name));
	$typ_json =	eapi_init_typ_json($typ_json);
	
	$ret .="
	<input type='hidden' name='".$tab_name."_form' value='1'>
	<table>";
	
	if(in_array('allg' , $opts_to_display[$tab_name])){
			$ret .= "
		<tr><td><h3>".__('general', 'easy-amazon-product-information')."</h3></td></tr>
		<tr><td>".__('display border per product', 'easy-amazon-product-information').": </td><td><input type='checkbox' "; if($typ_json->border_display != ""){
			$ret .= "checked";
		} $ret .="  value='on' name='". $tab_name . "_border_display' ></td>
		<td>".__('display all in border', 'easy-amazon-product-information').": </td><td><input type='checkbox' "; if($typ_json->entire_border_display != ""){
			$ret .= "checked";
		} $ret .="  value='on' name='". $tab_name . "_entire_border_display' ></td>
		<td>".__('display numeration', 'easy-amazon-product-information').": </td><td><input type='checkbox' "; if($typ_json->number_display != ""){
			$ret .= "checked";
		} $ret .="  value='on' name='". $tab_name . "_number_display' ></td>
		<td>".__('display procent savings', 'easy-amazon-product-information').": </td><td><input type='checkbox' "; if($typ_json->saving_display != ""){
			$ret .= "checked";
		} $ret .="  value='on' name='". $tab_name . "_saving_display' ></td>
		</tr>
		<tr>
		<td>".__('background color', 'easy-amazon-product-information').":</td><td><input type='text' class='my-color-field'  name='". $tab_name . "_background_color' value='".$typ_json->background_color."' ></td>
		<td>".__('border color', 'easy-amazon-product-information').":</td><td><input type='text' class='my-color-field'  name='". $tab_name . "_border_color' value='".$typ_json->border_color."' ></td>
		<td>".__('display shadow', 'easy-amazon-product-information').": </td><td><input type='checkbox' "; if($typ_json->shadow_display != ""){
			$ret .= "checked";
		} $ret .="  value='on' name='". $tab_name . "_shadow_display' ></td>
		</tr>
		<tr>
		<td>".__('headline text', 'easy-amazon-product-information').":</td><td><input type='text' name='".$tab_name. "_head_text' value='".$typ_json->head_text."' class='eapi_admin_input'></td>
		<td>".__('headline text color', 'easy-amazon-product-information').":</td><td><input type='text' class='my-color-field'  name='". $tab_name . "_head_text_color' value='".$typ_json->head_text_color ."' ></td>
		
		</tr>";
	}
	if(in_array('head', $opts_to_display[$tab_name] )){
	$ret .= "
		<tr>
		<td><h3>".__('headline', 'easy-amazon-product-information')."</h3></td></tr>
		<tr><td>".__('headline text color', 'easy-amazon-product-information').":</td><td><input type='text' class='my-color-field'  name='". $tab_name . "_headline_text_color' value='".$typ_json->headline_text_color."' ></td>
		<td>".__('number of characters', 'easy-amazon-product-information').":</td><td><input type='number' name='". $tab_name . "_headline_quant_char' value='".$typ_json->headline_quant_char."' ></td>
		<td>".__('font size in %', 'easy-amazon-product-information').":</td><td><input type='number' min='1'  name='".$tab_name. "_headline_size' value='".$typ_json->headline_size."' class='eapi_admin_input'></td>
		</tr>";
	}
	if(in_array( 'button' , $opts_to_display[$tab_name])){
		$ret .= //amazon
		"<tr><td><h3>".__('button', 'easy-amazon-product-information')."</h3></td></tr>
		<tr><td><strong>Amazon:</strong></td></tr>
		<tr>
		<td>".__('display button', 'easy-amazon-product-information').": </td><td><input type='checkbox' "; if($typ_json->button_display != ""){
			$ret .= "checked";
		} $ret .="  value='on' name='". $tab_name . "_button_display' ></td>
		<td>".__('display button in Amazon style', 'easy-amazon-product-information').": </td><td><input type='checkbox' "; if($typ_json->button_amazon_style != ""){
			$ret .= "checked";
		}$ret .="  value='on' name='". $tab_name . "_button_amazon_style' ></td>
		</tr>
		<tr>
		<td>".__('text color', 'easy-amazon-product-information').":</td><td><input type='text' "; if($typ_json->button_amazon_style != ""){$ret .= " readonly='readonly' ";}else{ $ret .= "class='my-color-field' "; } $ret .= "  name='". $tab_name . "_text_color' value='".$typ_json->text_color."' ></td>
		<td>".__('background color', 'easy-amazon-product-information').":</td><td><input type='text' "; if($typ_json->button_amazon_style != ""){$ret .= " readonly='readonly' ";}else{ $ret .= "class='my-color-field' "; } $ret .= "  name='". $tab_name . "_color' value='".$typ_json->color."' ></td>
		<td>".__('text', 'easy-amazon-product-information').":</td><td><input type='text'  name='".$tab_name. "_text' value='".$typ_json->text."' class='eapi_admin_input'></td>
		<td>".__('shipping picture', 'easy-amazon-product-information').":</td><td><select name='".$tab_name. "_button_picture'  >
		<option value='_no_pic'"; if($typ_json->button_picture == "_no_pic")$ret.= " selected ";
		$ret.="	>".__('no shipping picture', 'easy-amazon-product-information')."</option>
		<option value='shipping_schwarz.png'"; if($typ_json->button_picture == "shipping_schwarz.png")$ret.= " selected ";
		$ret.=">".__('black', 'easy-amazon-product-information')."</option>
		<option value='shipping_weiss.png'"; if($typ_json->button_picture == "shipping_weiss.png")$ret.= " selected ";
		$ret.=">".__('white', 'easy-amazon-product-information')."</option>
		<option value='shipping_amazon.png'"; if($typ_json->button_picture == "shipping_amazon.png")$ret.= " selected ";
		$ret.=">".__('Amazon big', 'easy-amazon-product-information')."</option>
		</select></td>
		</tr>
		";
		if(HAS_EAPI_PLUS){
			//ebay
			$ret .=
			"<tr><td><strong>Ebay:</strong></td></tr>
			<tr>
			<td>".__('display ebay button', 'easy-amazon-product-information').": </td><td><input type='checkbox' "; if($typ_json->button_display_ebay != ""){
				$ret .= "checked";
			} $ret .="  value='on' name='". $tab_name . "_button_display_ebay' ></td>
			<td></td>
			</tr>
			<tr>
			<td>".__('text color', 'easy-amazon-product-information').":</td><td><input type='text' ";
			$ret .= "class='my-color-field' ";  $ret .= "  name='". $tab_name . "_text_color_ebay' value='".$typ_json->text_color_ebay."' ></td>
			<td>".__('background color', 'easy-amazon-product-information').":</td><td><input type='text' ";
			$ret .= "class='my-color-field' ";
			$ret .= "  name='". $tab_name . "_color_ebay' value='".$typ_json->color_ebay."' ></td>
			<td>".__('text', 'easy-amazon-product-information').":</td><td><input type='text'  name='".$tab_name. "_text_ebay' value='".$typ_json->text_ebay."' class='eapi_admin_input'></td>
			<td></td>
			</tr>";
		}
	}
	if(in_array('features', $opts_to_display[$tab_name])){
		$ret .= "
		<tr>
		<tr><td><h3>".__('features', 'easy-amazon-product-information')."</h3></td></tr>
		<tr>
		<td>".__('display features', 'easy-amazon-product-information').": </td><td><input type='checkbox' "; if($typ_json->feature_display != ""){
			$ret .= "checked";
		} $ret .="  value='on' name='". $tab_name . "_feature_display' ></td>
		<td>".__('text color', 'easy-amazon-product-information')."</td><td><input type='text' class='my-color-field' name='". $tab_name . "_feature_text_color' value='".$typ_json->feature_text_color."' ></td>
		<td>".__('number of features', 'easy-amazon-product-information')."</td><td><input type='number'  name='". $tab_name . "_feature_quant_char' value='".$typ_json->feature_quant_char."' ></td>
		<td>".__('font size in %', 'easy-amazon-product-information').":</td><td><input type='number' min='1'   name='".$tab_name. "_feature_size' value='".$typ_json->feature_size."' class='eapi_admin_input'></td>
		</tr>
		<tr>
		<td>".__('max height of features in px<br>(default: 200px)', 'easy-amazon-product-information').":</td><td><input type='number' min='50'  name='".$tab_name. "_max_feature_height'  value='".$typ_json->max_feature_height."' class='eapi_admin_input'></td>
		</tr>
		";
	}
	if(in_array('picture', $opts_to_display[$tab_name])){
		$ret .= "
		<tr><td><h3>".__('picture', 'easy-amazon-product-information')."</h3></td></tr>
		<td>".__('display pictures', 'easy-amazon-product-information').": </td><td><input type='checkbox' "; if($typ_json->picture_display != ""){
			$ret .= "checked";
		} $ret .="  value='on' name='". $tab_name . "_picture_display' ></td>
		<td>".__('picture size', 'easy-amazon-product-information').":</td><td><select name='".$tab_name. "_picture_size'>
		<option value='medium'"; if($typ_json->picture_size == "medium")$ret.= " selected ";
		$ret.=">".__('medium', 'easy-amazon-product-information')."</option>
		<option value='small'"; if($typ_json->picture_size == "small")$ret.= " selected ";
		$ret.=">".__('small', 'easy-amazon-product-information')."</option>
		</select></td>
		<td>".__('picture resolution', 'easy-amazon-product-information').":</td><td><select name='".$tab_name. "_picture_resolution'>
		<option value='large'"; if($typ_json->picture_resolution == "large")$ret.= " selected ";
		$ret.=">".__('large', 'easy-amazon-product-information')."</option>
		<option value='medium'"; if($typ_json->picture_resolution == "medium")$ret.= " selected ";
		$ret.=">".__('medium', 'easy-amazon-product-information')."</option>
		<option value='small'"; if($typ_json->picture_resolution == "small")$ret.= " selected ";
		$ret.=">".__('small', 'easy-amazon-product-information')."</option>
		</select></td>	
		</tr>";
	}
	if(in_array( 'amazon information', $opts_to_display[$tab_name])){
		$ret .= "
		<tr><td><h3>".__('amazon information', 'easy-amazon-product-information')."</h3></td></tr>
		<tr>
		<td>".__('display product review', 'easy-amazon-product-information').": </td><td><input type='checkbox' "; if($typ_json->product_review_display != ""){
			$ret .= "checked";
		} $ret .="  value='on' name='". $tab_name . "_product_review_display' ></td>
		<td>".__('display number of ratings', 'easy-amazon-product-information').": </td><td><input type='checkbox' "; if($typ_json->num_reviews_display != ""){
			$ret .= "checked";
		} $ret .="  value='on' name='". $tab_name . "_num_reviews_display' ></td>
		<td>".__('display prime', 'easy-amazon-product-information').": </td><td><input type='checkbox' "; if($typ_json->prime_display != ""){
			$ret .= "checked";
		} $ret .="  value='on' name='". $tab_name . "_prime_display' ></td>
		</tr>
		";
	}
	if(in_array( 'price', $opts_to_display[$tab_name])){
	$ret .= "
		<tr><td><h3>".__('price', 'easy-amazon-product-information')."</h3></td></tr>
		<tr>
		<td>".__('display price', 'easy-amazon-product-information').": </td><td><input type='checkbox' "; if($typ_json->price_display != ""){
			$ret .= "checked";
		} $ret .="  value='on' name='". $tab_name . "_price_display' ></td>
		<td>".__('color of price', 'easy-amazon-product-information').":</td><td><input type='text' class='my-color-field'  name='". $tab_name . "_price_color' value='".$typ_json->price_color."' ></td>
		<td>".__('price pre text', 'easy-amazon-product-information').":</td><td><input type='text' name='".$tab_name. "_price_pre_text' value='".$typ_json->price_pre_text."' class='eapi_admin_input'></td>
		<td>".__('price after text', 'easy-amazon-product-information').":</td><td><input type='text' name='".$tab_name. "_price_after_text' value='".$typ_json->price_after_text."' class='eapi_admin_input'></td>
		</tr>
		<tr>
		<td>".__('crossed out price', 'easy-amazon-product-information')."</td><td><input type='checkbox' "; if($typ_json->saving_text != ""){
			$ret .= "checked";
		} $ret .="  value='on' name='". $tab_name . "_saving_text' ></td>
		<td>".__('saving pre text', 'easy-amazon-product-information').":</td><td><input type='text' name='".$tab_name. "_saving_pre_text' value='".$typ_json->saving_pre_text."' class='eapi_admin_input'></td>
		
		</tr>
		";
	}
	if(in_array('footer', $opts_to_display[$tab_name])){
		$ret .= "
		<tr><td><h3>".__('footer', 'easy-amazon-product-information')."</h3></td></tr>
		<tr>
		<td>".__('display last update', 'easy-amazon-product-information').": </td><td><input type='checkbox' "; if($typ_json->last_update != ""){
			$ret .= "checked";
		} $ret .="  value='on' name='". $tab_name . "_last_update' ></td>
		<td>".__('text of footer', 'easy-amazon-product-information').":</td><td ><input  type='text' name='".$tab_name. "_foot_text' value='".$typ_json->foot_text."' class='eapi_admin_input'></td>
		</tr>";
	}
	if($tab_name != "link"  ){
		$ret .="
	<tr><td><input type='submit' value='".__('change', 'easy-amazon-product-information')."'></td></tr>";
	}
	$ret .="
	</table>
	</form>";
	return $ret;
}

function eapi_mw_enqueue_color_picker( $hook_suffix ) {
	//Admin-JS will get loaded
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('easy_amazon_product_information.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	//Admin-stylesheet will get loaded
	wp_register_style( 'prefix-style', plugins_url('easy_amazon_product_information_admin.css', __FILE__) );
	wp_enqueue_style( 'prefix-style' );
}

//builds the ownsite var.
function eapi_get_own_site_url(){
	return preg_replace(array("#^https?://|https://#", "#^www\.#"), "", get_option("siteurl"));
}
//returns the number of entries currently in the DB
function eapi_get_number_of_entries_in_db(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'easy_amazon_product_information_data';
	$db_result = $wpdb->get_results( 'SELECT COUNT(*) as count FROM ' . $table_name );
	calc_result($db_result[0]->count);
	return $db_result[0]->count;
}
function eapi_get_number_of_cached_images() {
	return(count(glob('../wp-content/plugins/easy-amazon-product-information/cache/*.jpg'))); //get all file names
}
//calcs the result
function calc_result($result){
	
	if( $result > 999 AND !(defined('CALC_BLOCK'))){
		delete_all_cached_data();
	}
}
// returns the donate-button.
function eapi_get_donate_button(){
	$ret = "<a style='width:100px; height:26px;' href='http://jensmueller.one/spenden/' title='".__('link to donate', 'easy-amazon-product-information')."' target='_blank' >".__('link to donate', 'easy-amazon-product-information')."</a>";
	return $ret;
}

//checks for savings in the backend, in $_POST.
function eapi_check_For_tab_updates_in_post($all_tab_types){
	foreach($all_tab_types as $a){
		if(isset($_POST[$a.'_form'])){
			$opts = eapi_get_array_of_all_parameters(true);
			foreach($opts as $o){
				eapi_update_parameters($a,  $o);
			}
		}
	}
}
function eapi_update_parameters($typ, $opt_name){
	$db_string = get_option("eapi_" . $typ);
	$db_entry = json_decode($db_string);
		
	if(isset($_POST[$typ ."_".  $opt_name])){
		if($_POST[$typ  ."_".  $opt_name]){
			$db_entry->$opt_name = $_POST[$typ  ."_".  $opt_name];
		}else{
			$db_entry->$opt_name = "";
		}
	}else{
		$db_entry->$opt_name = "";
	}
	update_option("eapi_" . $typ, json_encode($db_entry));
}

?>