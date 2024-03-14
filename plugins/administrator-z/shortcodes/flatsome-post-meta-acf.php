<?php 
return;
//  COMING SOON










































if(!function_exists('get_field')) return;

use Adminz\Helper\ADMINZ_Helper_Flatsome_Shortcodes;
$a = new \Adminz\Helper\ADMINZ_Helper_Flatsome_Shortcodes;
$a->shortcode_name = 'adminz_post_meta_acf';
$a->shortcode_title = 'Post ACF (beta)';
$a->shortcode_type = 'container';
$a->shortcode_allow = ['adminz_post_meta_acf_item'];
$a->shortcode_icon = 'text';
$options = [
	'field'=> [
		'type' =>'textfield',
		'heading' => 'ACF field',
		'description' => 'Test Meta Url: '.get_site_url()."?testfield=post_id",
		'default' => '',
	],
	'is_repeater' => array(
        'type' => 'checkbox',
        'heading' => "Is Repeater",
        'default' => 'false',        
    ),
];
$options = array_merge(
	$options,
	require ADMINZ_DIR."/shortcodes/inc/flatsome-element-advanced.php",
);




$a->options = $options;



$a->shortcode_callback = function($atts, $content = null){	
	$GLOBALS['adminz_post_meta_acf_items'] = array();
	$GLOBALS['adminz_post_meta_acf_item_count'] = 0;


	extract(shortcode_atts(array(
		"field" => "",
		"is_repeater" => "false"
    ), $atts));

    if(isset($_POST['ux_builder_action'])){
        return '<span style="background: #71cedf; border: 2px dashed #000; display: flex; color: white; justify-content: center; align-items: center;">Demo Post Meta result for '.$field.'</span>'; 
    }

    $content = do_shortcode( $content );

	if(!$field) return;
    ob_start();	

    if(!empty($GLOBALS['adminz_post_meta_acf_items'])){
    	echo "<pre>";print_r($GLOBALS['adminz_post_meta_acf_items']);echo "</pre>";
    	foreach( $GLOBALS['adminz_post_meta_acf_items'] as $key => $item ){
    		echo "<pre>";print_r($item);echo "</pre>";
    	}
    }else{
    	print_r(get_field($field));
    }
	

	return apply_filters('adminz_apply_content_change',ob_get_clean(), $atts);
};

$a->general_element();




// ===================== Item ===================================

$a12345 = new \Adminz\Helper\ADMINZ_Helper_Flatsome_Shortcodes;
$a12345->shortcode_name = 'adminz_post_meta_acf_item';
$a12345->shortcode_title = 'Post ACF Item';
$a12345->shortcode_icon = 'text';
$options1 = [
	'field'=> [
		'type' =>'textfield',
		'heading' => 'ACF field',
		'default' => '',
	],
];
$options1 = array_merge(
	$options1,
	require ADMINZ_DIR."/shortcodes/inc/flatsome-element-advanced.php",
);
$a12345->options = $options1;
$a12345->shortcode_callback = function($params, $content = null){
	extract(shortcode_atts(array(
			'field' => '',
	), $params));

	$x = $GLOBALS['adminz_post_meta_acf_item_count'];
	$GLOBALS['adminz_post_meta_acf_items'][ $x ] = array( 'field' => $field, 'content' => $content );
	$GLOBALS['adminz_post_meta_acf_item_count']++;
};
$a12345->general_element();
