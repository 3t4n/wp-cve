<?php if ( ! defined( 'ABSPATH' ) ) die; // Cannot access pages directly.


/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 */

$salert_settings = get_option( 'salert_save_settings');
//Image Position
$image_position = $salert_settings['image-position'];
$image_style = $salert_settings['image-style'];

$sep = isset($salert_settings['text-separator']) ? $salert_settings['text-separator'] : ',';
//Random Names
$rand_name = $salert_settings['popup-names'];
$text_array = $arr_history = explode($sep,$rand_name);
$key = array_rand($text_array,1);
$name = $text_array[$key];

//Random Countries
$rand_country = $salert_settings['popup-countries'];
$country_array = explode($sep,$rand_country);
$key = array_rand($country_array,1);
$country =  $country_array[$key];

//Random Times
$time = mt_rand(1,15);
$time_periods = $salert_settings['popup-timeperiod'];
$min_hr = explode($sep,$time_periods);
$timespend = array_rand($min_hr,1);
$ago = $salert_settings['popup-timeago'];
$time = '<small class="time">'.esc_attr($time).' '.esc_html($min_hr[$timespend]).' '.esc_html($ago).'</small>';

//Random products
$popup_product = $salert_settings['popup-products'];
$product_array = ( isset( $popup_product ) ) ? $popup_product : '';  

if( !empty($product_array)){
	$rand_product = array_rand($product_array['title'],1);
	$image_url = $product_array['url'][$rand_product];
	$product_name = $product_array['title'][$rand_product];
    $product_url =  isset($product_array['link'][$rand_product]) ? $product_array['link'][$rand_product] : '';
}else{
	$rand_product = '';
	$image_url = '';
	$product_name = '';
    $product_url = '';
}

$popup_contents = $salert_settings['popup-contents'];
$final_content  = strtr($popup_contents, array("[name]"=>$name, "[country]"=>$country, "[product]"=>$product_name, "[time]"=>$time));

?>

<div class="popup-item <?php echo esc_attr($image_position);?> <?php echo ($image_url == '') ? 'textOnly' : ''; ?> clearfix">
    <?php 
    if($salert_settings['close-btn']==1){
        echo '<span class="btn-close"><img src="'.SALERT_DIR.'/assets/close-icon.png" alt="close"/></span>';
    }
    if($product_url!=''){?>
    <a href="<?php echo esc_url($product_url);?>">
    <?php }
    if($image_url != ''){ 
    if($image_position !== 'textOnly'){ 
    ?>
    <figure class="salert-img <?php echo esc_attr($image_style);?>"><img src="<?php echo esc_url($image_url)?>"></figure>
    <?php }}?>
    <div class="salert-content-wrap">
        <?php echo do_shortcode($final_content);?>
    </div>
    <?php if($product_url!=''){?>
    </a>
    <?php }?>
</div>







