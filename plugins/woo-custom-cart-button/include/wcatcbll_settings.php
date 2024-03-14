<?php

// Save cart button setting in option.php
function wcatcbll_wccb_options_page()
{

	include(WCATCBLL_CART_INC . 'wcatcbll_btn_2dhvr.php');

	$catcbll_settings = get_option('_woo_catcbll_all_settings');
	extract($catcbll_settings);

	//button display setting
	if (isset($catcbll_both_btn)) {
		$both  = $catcbll_both_btn;
	} else {
		$both = '';
	}
	if (isset($catcbll_add2_cart)) {
		$add2cart = $catcbll_add2_cart;
	} else {
		$add2cart = '';
	}
	if (isset($catcbll_custom)) {
		$custom = $catcbll_custom;
	} else {
		$custom  = '';
	}
	//display button setting
	if (isset($catcbll_cart_global)) {
		$global = $catcbll_cart_global;
	} else {
		$global = '';
	}
	if (isset($catcbll_cart_shop)) {
		$shop = $catcbll_cart_shop;
	} else {
		$shop = '';
	}
	if (isset($catcbll_cart_single_product)) {
		$single  = $catcbll_cart_single_product;
	} else {
		$single = '';
	}
	if (isset($catcbll_btn_open_new_tab)) {
		$btn_opnt  = $catcbll_btn_open_new_tab;
	} else {
		$btn_opnt = '';
	}
	if (isset($catcbll_ready_to_use)) {
		$catcbll_ready_to_use  = $catcbll_ready_to_use;
	} else {
		$catcbll_ready_to_use = '';
	}

	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page'));
	}
   
    include(WCATCBLL_CART_INC . 'admin/wcatcbll_general_settings.php'); 
}

?>