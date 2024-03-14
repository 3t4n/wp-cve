<?php
/*** wctofb_woocommerce_feeds.php ***/
if ( ! defined( 'ABSPATH' ) ) exit;
	global $wctofb_site_url, $wpdb ,$post; $productids=array();
	define('DONOTCACHEPAGE', TRUE);
	set_time_limit (0);
	if(isset($_GET['productid'])){$singleproductid = (int)$_GET['productid'];}
	if(isset($_GET['productlimit'])){$productlimit = (int)$_GET['productlimit'];}else{$productlimit=1;}
	header("Content-Type: application/json; charset=UTF-8");
	$wctofb_api = esc_attr(get_option('wctofb_api'));
	$wctofb_apikey_success = esc_attr(get_option('wctofb_apikey_success'));
		if($wctofb_apikey_success=="4" || $wctofb_apikey_success==""){
		$store_url =  get_bloginfo('url');	
		$jsondata = array("action"=>"sync","message"=>"API key is not verified",'store_url'=>$store_url);
		echo $sendjson = json_encode($jsondata, JSON_NUMERIC_CHECK);
		exit();
	}
	$table_name = $wpdb->prefix . 'wctofb';
	$content="";
	$cartpage_url =  wc_get_cart_url();
	$store_url =  get_bloginfo('url');
	$sendstoreproducts = array(); $store_currency = array();
	$store_currency['currency_symbol'] = get_woocommerce_currency_symbol();
	$store_currency['price_format'] = get_woocommerce_price_format();
	$store_currency['decimal_separator']  = wc_get_price_decimal_separator();
    $store_currency['thousand_separator'] = wc_get_price_thousand_separator();
	$store_currency['decimals'] = wc_get_price_decimals();
	$store_language =  get_locale();
	global $post;
	$gettotalproduct = $wpdb->get_results( "SELECT product_id FROM $table_name", ARRAY_A );
	if(empty($gettotalproduct)){
	$store_url =  get_bloginfo('url');	
	$jsondata = array("action"=>"Issue with database table","apikey"=>"verified",'store_url'=>$store_url);
	echo $sendjson = json_encode($jsondata, JSON_NUMERIC_CHECK);
	exit();	
	}
	$productid_results = $wpdb->get_results( "SELECT product_id FROM $table_name WHERE sync_status='0' AND (product_status='sync' OR product_status='unsync') LIMIT $productlimit", ARRAY_A );
	if(!empty($productid_results)){
	if(!empty($singleproductid)){
		$productids[] = $singleproductid;
	}else{
		foreach($productid_results as $productid){
			$productids[] = $productid['product_id'];
		}
	}
		$my_query = query_posts(array('post__in' => $productids ,'post_type'=> 'product', 'post_status' => 'publish','order'=>'ASC','posts_per_page' => -1 ));
		if ( have_posts() ) :
		while (have_posts()): the_post();
			global $product; $attributes=$allattributes=$allvariations=$product_stock_status=$productrawvariations=$producttype=$childproductsids=$cat_name=$allimages=$product_sku=$product_stock_quantity=$product_stock_manage=$product_stock_backorders=$product_stock_sold_individually=$product_weight=$product_length=$product_height=$product_width=$regularprice=$saleprice=$product_long_description=$product_short_description=$product_detail_link=$product_title=$product_id='';
			//get detail for simple / external products
		if( ($product->is_type( 'simple' ) || $product->is_type('external')) && $product->get_regular_price()!=''){
			if($product->is_type( 'simple' )){$producttype = 'simple';}
			if($product->is_type( 'external' )){$producttype = 'external';}
				if ( method_exists( $product, 'get_stock_status' ) ) {
				$product_stock_status = $product->get_stock_status(); // For version 3.0+
				} else {
				$product_stock_status = $product->stock_status; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_stock_quantity' ) ) {
				$product_stock_quantity= $product->get_stock_quantity();// For version 3.0+
				} else {
				$product_stock_quantity= $product->stock_quantity; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_manage_stock' ) ) {
				$product_stock_manage= $product->get_manage_stock(); // For version 3.0+
				} else {
				$product_stock_manage= $product->manage_stock; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_backorders' ) ) {
				$product_stock_backorders=$product->get_backorders(); // For version 3.0+
				} else {
				$product_stock_backorders = $product->backorders; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_sold_individually' ) ) {
				$product_stock_sold_individually= $product->get_sold_individually(); // For version 3.0+
				} else {
				$product_stock_sold_individually= $product->sold_individually; // Older than version 3.0
				}
				$product_weight= $product->get_weight();
				$product_length = $product->get_length();
				$product_height = $product->get_height();
				$product_width = $product->get_width();
				$product_title = get_the_title();
				$product_sku = $product->get_sku();
				$product_id = get_the_ID();
				$args = array( 'taxonomy' => 'product_cat',);
				$terms = wp_get_post_terms($post->ID,'product_cat', $args);
				$count = count($terms); 
				if ($count > 0) {
				$cat_id=$image_gallery_link='';
				$cat_name=$allimages=array();
				foreach ($terms as $term) {
				$cat_name[] =  $term->name;
				}
				}
				
				$product_detail_link = get_post_permalink( $product_id );
				$product_short_description = preg_replace("/\[(.*?)\]/i",'',apply_filters( 'the_excerpt', get_the_excerpt()));
				$product_long_description = preg_replace("/\[(.*?)\]/i", '', apply_filters( 'the_content', get_the_content()));
				$featured_image_small = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'medium', false, '' );	
				$featured_image_large = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'full', false, '' );
				if(!empty($featured_image_small[0])){
				$allimages[] = array("srcurl"=>$featured_image_small[0],"isthumb"=>"1","islarge"=>"0","isgallery"=>"0","isvariation"=>"0",'variation_id'=>"0");				}
				if(!empty($featured_image_large[0])){
				$allimages[] = array("srcurl"=>$featured_image_large[0],"isthumb"=>"0","islarge"=>"1","isgallery"=>"0","isvariation"=>"0",'variation_id'=>"0");}
				if ( method_exists( $product, 'get_gallery_image_ids' ) ) {
				$attachment_ids = $product->get_gallery_image_ids(); // For version 3.0+
				} elseif ( method_exists( $product, 'get_gallery_attachment_ids' ) ) {
				$attachment_ids = $product->get_gallery_attachment_ids(); // For version 2.6 to 3.0
				}else {
				$attachment_ids = $product->get_gallery_attachment_ids; // Older than version 2.6
				}
				if(!empty($attachment_ids)){
				foreach( $attachment_ids as $attachment_id ) 
				{
				$allimages[] = array("srcurl"=>wp_get_attachment_url( $attachment_id ),"isthumb"=>"0","islarge"=>"0","isgallery"=>"1","isvariation"=>"0",'variation_id'=>"0");
				}
				}
				
				$raw_regularprice=$raw_saleprice=$regularprice=$saleprice='';
				if ( method_exists( $product, 'get_regular_price' ) ) {
				$raw_regularprice = $product->get_regular_price(); // For version 3.0+
				} else {
				$raw_regularprice = $product->regular_price; // Older than version 3.0
				}
				if($raw_regularprice!=''){
				$regularprice = $raw_regularprice;
				} 
				if($product->is_on_sale()){
				if ( method_exists( $product, 'get_sale_price' ) ) {
				$raw_saleprice = $product->get_sale_price(); // For version 3.0+
				} else {
				$raw_saleprice = $product->sale_price; // Older than version 3.0
				}	
				if($raw_saleprice!=''){
				$saleprice = $raw_saleprice;
				}	
				}
				if(empty($singleproductid) && !isset($_SERVER['HTTP_COOKIE'])){
				$wpdb->update( $table_name, array( 'product_status' => 'sync','sync_status'=>'1'),array( 'product_id' => esc_sql($product_id)) );
				}
			}
			//get detail for grouped products
			elseif( $product->is_type( 'grouped' )){
				$product_single_inventory= array();
				if($product->is_type( 'grouped' )){$producttype = 'grouped';}
				if ( method_exists( $product, 'get_stock_status' ) ) {
				$product_stock_status = $product->get_stock_status(); // For version 3.0+
				} else {
				$product_stock_status = $product->stock_status; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_stock_quantity' ) ) {
				$product_stock_quantity= $product->get_stock_quantity();// For version 3.0+
				} else {
				$product_stock_quantity= $product->stock_quantity; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_manage_stock' ) ) {
				$product_stock_manage= $product->get_manage_stock(); // For version 3.0+
				} else {
				$product_stock_manage= $product->manage_stock; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_backorders' ) ) {
				$product_stock_backorders=$product->get_backorders(); // For version 3.0+
				} else {
				$product_stock_backorders = $product->backorders; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_sold_individually' ) ) {
				$product_stock_sold_individually= $product->get_sold_individually(); // For version 3.0+
				} else {
				$product_stock_sold_individually= $product->sold_individually; // Older than version 3.0
				}
				$product_title = get_the_title();
				$product_sku = $product->get_sku();
				$product_id = get_the_ID();
				$args = array( 'taxonomy' => 'product_cat',);
				$terms = wp_get_post_terms($post->ID,'product_cat', $args);
				$count = count($terms); 
				if ($count > 0) {
				$cat_id=$image_gallery_link='';
				$cat_name=array();
				foreach ($terms as $term) {
				$cat_name[] =  $term->name;
				}
				}
				$allimages=array();
				$product_detail_link = get_post_permalink($product_id);
				$product_short_description = preg_replace("/\[(.*?)\]/i",'',apply_filters( 'the_excerpt', get_the_excerpt()));
				$product_long_description = preg_replace("/\[(.*?)\]/i", '', apply_filters( 'the_content', get_the_content()));
				$featured_image_small = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'medium', false, '' );	
				$featured_image_large = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'full', false, '' );
				if(!empty($featured_image_small[0])){
				$allimages[] = array("srcurl"=>$featured_image_small[0],"isthumb"=>"1","islarge"=>"0","isgallery"=>"0","isvariation"=>"0",'variation_id'=>"0");				}
				if(!empty($featured_image_large[0])){
				$allimages[] = array("srcurl"=>$featured_image_large[0],"isthumb"=>"0","islarge"=>"1","isgallery"=>"0","isvariation"=>"0",'variation_id'=>"0");}
				if ( method_exists( $product, 'get_gallery_image_ids' ) ) {
				$attachment_ids = $product->get_gallery_image_ids(); // For version 3.0+
				} elseif ( method_exists( $product, 'get_gallery_attachment_ids' ) ) {
				$attachment_ids = $product->get_gallery_attachment_ids(); // For version 2.6 to 3.0
				}else {
				$attachment_ids = $product->get_gallery_attachment_ids; // Older than version 2.6
				}
				if(!empty($attachment_ids)){
				foreach( $attachment_ids as $attachment_id ) 
				{
				$allimages[] = array("srcurl"=>wp_get_attachment_url( $attachment_id ),"isthumb"=>"0","islarge"=>"0","isgallery"=>"1","isvariation"=>"0",'variation_id'=>"0");
				}
				}
				$raw_regularprice=$raw_saleprice=$regularprice=$saleprice='';
				$child_prices=$childproductsids= array();
			    foreach ( $product->get_children() as $child_id ) {
			   $child_prices[] = get_post_meta( $child_id, '_price', true ); 
			   $childproductsids[]= $child_id;
			   }
			    $regularprice = min(array_filter($child_prices));
				if(empty($singleproductid) && !isset($_SERVER['HTTP_COOKIE'])){
				$wpdb->update( $table_name, array( 'product_status' => 'sync','sync_status'=>'1'),array( 'product_id' => esc_sql($product_id)) );
				}
			}
			//get detail for variable products
			elseif( $product->is_type( 'variable' ) && $product->get_variation_regular_price('min',true)!=''){
				if($product->is_type( 'variable' )){$producttype = 'variable';}
				$product_title = get_the_title();
				$product_sku = $product->get_sku();
				$product_id = get_the_ID();
				$args = array( 'taxonomy' => 'product_cat',);
				$terms = wp_get_post_terms($post->ID,'product_cat', $args);
				$count = count($terms);
				if ($count > 0) {
				$cat_id=$image_gallery_link=$saleprice=$regularprice="";
				$cat_name=array();
				foreach ($terms as $term) {
				$cat_name[] =  $term->name;
				}
				}
				
				$allimages=$product_single_shipping=array();
				if ( method_exists( $product, 'get_stock_status' ) ) {
				$product_stock_status = $product->get_stock_status(); // For version 3.0+
				} else {
				$product_stock_status = $product->stock_status; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_stock_quantity' ) ) {
				$product_stock_quantity= $product->get_stock_quantity();// For version 3.0+
				} else {
				$product_stock_quantity= $product->stock_quantity; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_manage_stock' ) ) {
				$product_stock_manage= $product->get_manage_stock(); // For version 3.0+
				} else {
				$product_stock_manage= $product->manage_stock; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_backorders' ) ) {
				$product_stock_backorders=$product->get_backorders(); // For version 3.0+
				} else {
				$product_stock_backorders = $product->backorders; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_sold_individually' ) ) {
				$product_stock_sold_individually= $product->get_sold_individually(); // For version 3.0+
				} else {
				$product_stock_sold_individually= $product->sold_individually; // Older than version 3.0
				}
				$product_weight= $product->get_weight();
				$product_length = $product->get_length();
				$product_height = $product->get_height();
				$product_width = $product->get_width();
				$product_detail_link = get_post_permalink( $product_id );
				$product_short_description = preg_replace("/\[(.*?)\]/i",'',apply_filters( 'the_excerpt', get_the_excerpt()));
				$product_long_description = preg_replace("/\[(.*?)\]/i", '', apply_filters( 'the_content', get_the_content()));
				$featured_image_small = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'medium', false, '' );	
				$featured_image_large = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'full', false, '' );
				if(!empty($featured_image_small[0])){
				$allimages[] = array("srcurl"=>$featured_image_small[0],"isthumb"=>"1","islarge"=>"0","isgallery"=>"0","isvariation"=>"0",'variation_id'=>"0");				}
				if(!empty($featured_image_large[0])){
				$allimages[] = array("srcurl"=>$featured_image_large[0],"isthumb"=>"0","islarge"=>"1","isgallery"=>"0","isvariation"=>"0",'variation_id'=>"0");}
				if ( method_exists( $product, 'get_gallery_image_ids' ) ) {
				$attachment_ids = $product->get_gallery_image_ids(); // For version 3.0+
				} elseif ( method_exists( $product, 'get_gallery_attachment_ids' ) ) {
				$attachment_ids = $product->get_gallery_attachment_ids(); // For version 2.6 to 3.0
				}else {
				$attachment_ids = $product->get_gallery_attachment_ids; // Older than version 2.6
				}
				foreach( $attachment_ids as $attachment_id ) 
				{
				$allimages[] = array("srcurl"=>wp_get_attachment_url( $attachment_id ),"isthumb"=>"0","islarge"=>"0","isgallery"=>"1","isvariation"=>"0",'variation_id'=>"0");
				}
				$regularprice = $product->get_variation_regular_price( 'min', true );
				$product_variations = $product->get_available_variations();
				if(!empty($product_variations)){
				$variation_product_id = $product_variations [0]['variation_id'];
				$variation_product = new WC_Product_Variation( $variation_product_id );
				if($product->is_on_sale()){
					$saleprice= $product->get_variation_sale_price( 'min', true );
				 } 
				$get_available_variations = $product->get_available_variations();
				$productrawvariations='';
				$productrawvariations = array();$productrawvariations['variations']= array(); $custom_single_variation =array();
				foreach($get_available_variations as $get_available_variation){
				$allimages[] = array("srcurl"=>$get_available_variation['image']['url'],"isthumb"=>"0","islarge"=>"0","isgallery"=>"0","isvariation"=>"1",'variation_id'=>$get_available_variation['variation_id']);
				$custom_single_variation['display_price']= $get_available_variation['display_price'];
				$custom_single_variation['display_regular_price']= $get_available_variation['display_regular_price'];
				$custom_single_variation['attributes']= $get_available_variation['attributes'];
				$custom_single_variation['variation_id'] = $get_available_variation['variation_id'];
            	$custom_single_variation['variation_is_active'] = $get_available_variation['variation_is_active'];
            	$custom_single_variation['variation_is_visible'] = $get_available_variation['variation_is_visible'];
				$custom_single_variation['availability_html'] = $get_available_variation['availability_html'];
				$custom_single_variation['backorders_allowed'] = $get_available_variation['backorders_allowed'];
				$custom_single_variation['is_in_stock'] = $get_available_variation['is_in_stock'];
				$custom_single_variation['max_qty'] = $get_available_variation['max_qty'];
            	$custom_single_variation['min_qty'] = $get_available_variation['min_qty'];
				$custom_single_variation['weight'] = $get_available_variation['weight'];
				$custom_single_variation['length'] = $get_available_variation['dimensions']['length'];
				$custom_single_variation['height'] = $get_available_variation['dimensions']['height'];
				$custom_single_variation['width'] = $get_available_variation['dimensions']['width'];
				$custom_single_variation['sku'] = $get_available_variation['sku'];
				$custom_single_variation['stock_quantity'] =  $get_available_variation['max_qty'];
				array_push($productrawvariations['variations'],$custom_single_variation);
				}
				}
				$attributes = $product->get_variation_attributes();
				$productrawvariations['variation_dropdown']= array();
				array_push($productrawvariations['variation_dropdown'],$attributes);
				if(empty($singleproductid) && !isset($_SERVER['HTTP_COOKIE'])){
			$wpdb->update( $table_name, array( 'product_status' => 'sync','sync_status'=>'1'),array( 'product_id' => esc_sql($product_id)) );	
				}
			}
			if(!empty($product_id)){
			$sendstoreproducts[] = array('product_id'=> $product_id,'product_sku'=>htmlspecialchars($product_sku),'product_title'=> htmlspecialchars($product_title),'product_short_description'=> htmlspecialchars($product_short_description),'product_long_description'=> htmlspecialchars($product_long_description),'regularprice'=> $regularprice,'saleprice'=> $saleprice,'product_detail_link'=> $product_detail_link,'allimages'=> $allimages,'productrawvariations'=>$productrawvariations, 'cat_name'=> $cat_name, 'product_stock_status'=>$product_stock_status,'product_stock_manage'=>$product_stock_manage,'product_stock_quantity'=>$product_stock_quantity,'product_stock_backorders'=>$product_stock_backorders,'product_stock_sold_individually'=>$product_stock_sold_individually,'product_weight'=>$product_weight,'product_length'=>$product_length,'product_height'=>$product_height,'product_width'=>$product_width,'producttype'=>$producttype,'groupedproduct_ids'=>$childproductsids);}
	endwhile;
	else:
	foreach($productids as $removedproid){
		$wpdb->delete( $table_name, array( 'product_id' => $removedproid ) );
	}
	endif;
	$jsondata = array("action"=>"Store Feed","products"=>$sendstoreproducts,'store_url'=>$store_url,'cartpage_url'=>$cartpage_url,"store_currency"=>$store_currency,"store_weight_unit"=>esc_attr( get_option('woocommerce_weight_unit' ) ),"store_dimension_unit"=>esc_attr( get_option('woocommerce_dimension_unit')),"store_language"=>$store_language,"apikey"=>"verified");
		
echo $sendjson = json_encode($jsondata, JSON_NUMERIC_CHECK);
	}else{
		$store_url =  get_bloginfo('url');
		$jsondata = array("action"=>"Store Feed sync complete","apikey"=>"verified",'store_url'=>$store_url);
		echo $sendjson = json_encode($jsondata, JSON_NUMERIC_CHECK);
		$wpdb->query( $wpdb->prepare("UPDATE $table_name SET sync_status = %s WHERE product_status = %s OR product_status= %s",'0', 'sync','unsync'));
	}
exit();
?>