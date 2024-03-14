<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2024 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a registered trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */

/*-------------------------------------------------------------
 Name:      adrotate_register_blocks
 Purpose:   Register and load blocks and their configuration
 Since:		5.8.24
-------------------------------------------------------------*/
function adrotate_register_blocks() {
    wp_register_script('adrotate-block', plugins_url('/library/block.js', __FILE__), array('wp-blocks', 'wp-element', 'wp-i18n'));

    register_block_type('adrotate/advert', array('editor_script' => 'adrotate-block', 'render_callback' => 'adrotate_advert_block'));
    register_block_type('adrotate/group', array('editor_script' => 'adrotate-block', 'render_callback' => 'adrotate_group_block'));
}
add_action('init', 'adrotate_register_blocks');

/*-------------------------------------------------------------
 Name:      adrotate_advert_block, adrotate_group_block
 Purpose:   Output advert or group block
 Since:		5.8.24
-------------------------------------------------------------*/
function adrotate_advert_block($attr) {
	global $adrotate_config;

    if(!isset($attr['advert_id']) OR !is_numeric($attr['advert_id'])) return;

	$output = '';
	if($adrotate_config['w3caching'] == "Y") {
		$output .= '<!-- mfunc '.W3TC_DYNAMIC_SECURITY.' -->';
		$output .= 'echo adrotate_ad('.$attr['advert_id'].');';
		$output .= '<!-- /mfunc '.W3TC_DYNAMIC_SECURITY.' -->';
	} else if($adrotate_config['borlabscache'] == "Y" AND function_exists('BorlabsCacheHelper')) {
		if(BorlabsCacheHelper()->willFragmentCachingPerform()) {
			$borlabsphrase = BorlabsCacheHelper()->getFragmentCachingPhrase();

			$output .= '<!--[borlabs cache start: '.$borlabsphrase.']--> ';
			$output .= 'echo adrotate_ad('.$attr['advert_id'].');';
			$output .= ' <!--[borlabs cache end: '.$borlabsphrase.']-->';

			unset($borlabsphrase);
		}
	} else {
		$output .= adrotate_ad($attr['advert_id']);
	}

	return $output;
}

function adrotate_group_block($attr) {
	global $adrotate_config;

    if(!isset($attr['group_id']) OR !is_numeric($attr['group_id'])) return;

	$output = '';
	if($adrotate_config['w3caching'] == "Y") {
		$output .= '<!-- mfunc '.W3TC_DYNAMIC_SECURITY.' -->';
		$output .= 'echo adrotate_group('.$attr['group_id'].');';
		$output .= '<!-- /mfunc '.W3TC_DYNAMIC_SECURITY.' -->';
	} else if($adrotate_config['borlabscache'] == "Y" AND function_exists('BorlabsCacheHelper')) {
		if(BorlabsCacheHelper()->willFragmentCachingPerform()) {
			$borlabsphrase = BorlabsCacheHelper()->getFragmentCachingPhrase();

			$output .= '<!--[borlabs cache start: '.$borlabsphrase.']--> ';
			$output .= 'echo adrotate_group('.$attr['group_id'].');';
			$output .= ' <!--[borlabs cache end: '.$borlabsphrase.']-->';

			unset($borlabsphrase);
		}
	} else {
		$output .= adrotate_group($attr['group_id']);
	}

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_add_block_category
 Purpose:   Add a category for AdRotate blocks in the Block Editor
 Since:		5.8.24
-------------------------------------------------------------*/
function adrotate_add_block_category($categories, $editor_context) {
	array_unshift($categories, array(
		'slug'	=> 'custom-adrotate',
		'title' => __('AdRotate - Advertisements', 'adrotate-pro'),
		'icon'  => null,
	));

	return $categories;
}
add_filter('block_categories_all', 'adrotate_add_block_category', 10, 2);