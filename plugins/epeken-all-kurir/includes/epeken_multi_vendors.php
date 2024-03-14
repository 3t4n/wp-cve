<?php
if ( ! defined( 'ABSPATH' ) ) exit; 

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

add_action ('show_user_profile', 'epeken_add_customer_meta_fields');
add_action ('edit_user_profile', 'epeken_add_customer_meta_fields');
add_action ('wcvendors_settings_after_shop_name', 'epeken_add_customer_meta_fields_front_end');
add_action( 'personal_options_update', 'epeken_save_customer_meta_fields' );
add_action( 'edit_user_profile_update','epeken_save_customer_meta_fields' );
add_action ('wcvendors_settings_before_paypal', 'epeken_save_customer_meta_fields_front_end');
add_action ('dokan_settings_form_bottom', 'epeken_add_customer_meta_fields_front_end');
add_action ('dokan_store_profile_saved', 'epeken_save_customer_meta_fields_front_end');
add_filter ('wcfm_is_allow_epeken', 'epeken_doesnt_allow_wcfm_todo_epeken_setting', 10,1);
add_action ('wcfm_marketplace_shipping', 'epeken_add_customer_meta_fields_wcfm_multivendor');
add_action ('wcfm_vendor_shipping_settings_update', 'epeken_save_customer_meta_fields_wcfm', 10,2);
add_action ('wcfm_vendor_settings_update', 'epeken_save_customer_meta_fields_wcfm', 150, 2 );

function epeken_save_customer_meta_fields_wcfm($user, $wcfm_settings_form) {
	parse_str($_POST['wcfm_settings_form'],$wcfm_settings_form);
	$vendor_data_asal_kota = (empty($wcfm_settings_form['vendor_data_asal_kota'] )) ? '' : $wcfm_settings_form['vendor_data_asal_kota']; // code kota asal
        $vendor_jne_reg = (empty($wcfm_settings_form['vendor_jne_reg'])) ? '' : $wcfm_settings_form['vendor_jne_reg'];
        $vendor_jne_oke = (empty($wcfm_settings_form['vendor_jne_oke'])) ? '' : $wcfm_settings_form['vendor_jne_oke'];
        $vendor_jne_yes = (empty($wcfm_settings_form['vendor_jne_yes'])) ? '' : $wcfm_settings_form['vendor_jne_yes'];
        $vendor_tiki_reg = (empty($wcfm_settings_form['vendor_tiki_reg'])) ? '' : $wcfm_settings_form['vendor_tiki_reg'];
        $vendor_tiki_eco = (empty($wcfm_settings_form['vendor_tiki_eco'])) ? '' : $wcfm_settings_form['vendor_tiki_eco'];
        $vendor_tiki_ons = (empty($wcfm_settings_form['vendor_tiki_ons'])) ? '' : $wcfm_settings_form['vendor_tiki_ons'];
	$vendor_pos_reguler = (empty($wcfm_settings_form['vendor_pos_reguler'])) ? '' : $wcfm_settings_form['vendor_pos_reguler'];
  	$vendor_pos_sameday = (empty($wcfm_settings_form['vendor_pos_sameday'])) ? '' : $wcfm_settings_form['vendor_pos_sameday'];
 	$vendor_pos_nextday = (empty($wcfm_settings_form['vendor_pos_nextday'])) ? '' : $wcfm_settings_form['vendor_pos_nextday'];
        $vendor_pos_biasa = (empty($wcfm_settings_form['vendor_pos_biasa'])) ? '' : $wcfm_settings_form['vendor_pos_biasa'];
        $vendor_pos_kilat_khusus = (empty($wcfm_settings_form['vendor_pos_kilat_khusus'])) ? '' : $wcfm_settings_form['vendor_pos_kilat_khusus'];
        $vendor_pos_express_next_day = (empty($wcfm_settings_form['vendor_pos_express_next_day'])) ? '' : $wcfm_settings_form['vendor_pos_express_next_day'];
        $vendor_pos_valuable_goods = (empty($wcfm_settings_form['vendor_pos_valuable_goods'])) ? '' : $wcfm_settings_form['vendor_pos_valuable_goods'];
	$vendor_pos_kprt = (empty($wcfm_settings_form['vendor_pos_kprt'])) ? '' : $wcfm_settings_form['vendor_pos_kprt'];
	$vendor_pos_kpru = (empty($wcfm_settings_form['vendor_pos_kpru'])) ? '' : $wcfm_settings_form['vendor_pos_kpru'];
        $vendor_jnt_ez = (empty($wcfm_settings_form['vendor_jnt_ez'])) ? '' : $wcfm_settings_form['vendor_jnt_ez'];
        $vendor_sicepat_reg = (empty($wcfm_settings_form['vendor_sicepat_reg'])) ? '' : $wcfm_settings_form['vendor_sicepat_reg'];
        $vendor_sicepat_best = (empty($wcfm_settings_form['vendor_sicepat_best'])) ? '' : $wcfm_settings_form['vendor_sicepat_best'];
        $vendor_sicepat_siunt = (empty($wcfm_settings_form['vendor_sicepat_siunt'])) ? '' : $wcfm_settings_form['vendor_sicepat_siunt'];
        $vendor_sicepat_gokil = (empty($wcfm_settings_form['vendor_sicepat_gokil'])) ? '' : $wcfm_settings_form['vendor_sicepat_gokil'];
 	$vendor_sicepat_sds = (empty($wcfm_settings_form['vendor_sicepat_sds'])) ? '' : $wcfm_settings_form['vendor_sicepat_sds'];
        $vendor_wahana = (empty($wcfm_settings_form['vendor_wahana'])) ? '' : $wcfm_settings_form['vendor_wahana'];
        $vendor_jmx_cos = (empty($wcfm_settings_form['vendor_jmx_cos'])) ? '' : $wcfm_settings_form['vendor_jmx_cos'];
        $vendor_jmx_sms = (empty($wcfm_settings_form['vendor_jmx_sms'])) ? '' : $wcfm_settings_form['vendor_jmx_sms'];
        $vendor_jmx_lts = (empty($wcfm_settings_form['vendor_jmx_lts'])) ? '' : $wcfm_settings_form['vendor_jmx_lts'];
        $vendor_jmx_sos = (empty($wcfm_settings_form['vendor_jmx_sos'])) ? '' : $wcfm_settings_form['vendor_jmx_sos'];
	$vendor_lion_regpack = (empty($wcfm_settings_form['vendor_lion_regpack'])) ? '' : $wcfm_settings_form['vendor_lion_regpack'];
	$vendor_lion_onepack = (empty($wcfm_settings_form['vendor_lion_onepack'])) ? '' : $wcfm_settings_form['vendor_lion_onepack'];
	$vendor_custom = (empty($wcfm_settings_form['vendor_custom'])) ? '' : $wcfm_settings_form['vendor_custom'];
	$vendor_flat =  (empty($wcfm_settings_form['vendor_flat'])) ? '' : $wcfm_settings_form['vendor_flat'];
	$vendor_flat_label =  (empty($wcfm_settings_form['vendor_flat_label'])) ? '' : $wcfm_settings_form['vendor_flat_label'];
	$epeken_kurir_toko_coverage = (empty($wcfm_settings_form['epeken_kurir_toko_coverage'])) ? '': $wcfm_settings_form['epeken_kurir_toko_coverage'];
	$vendor_sap_ods = (empty($wcfm_settings_form['vendor_sap_ods'])) ? '' : $wcfm_settings_form['vendor_sap_ods'];
	$vendor_sap_sds = (empty($wcfm_settings_form['vendor_sds'])) ? '' : $wcfm_settings_form['vendor_sap_sds'];
	$vendor_sap_reg = (empty($wcfm_settings_form['vendor_sap_reg'])) ? '' : $wcfm_settings_form['vendor_sap_reg'];
	$vendor_ninja_next_day = (empty($wcfm_settings_form['vendor_ninja_next_day'])) ? '' : $wcfm_settings_form['vendor_ninja_next_day'];
	$vendor_ninja_standard =  (empty($wcfm_settings_form['vendor_ninja_standard'])) ? '' : $wcfm_settings_form['vendor_ninja_standard'];
	$vendor_jtr =  (empty($wcfm_settings_form['vendor_jtr'])) ? '' : $wcfm_settings_form['vendor_jtr'];
	$vendor_dakota = (empty($wcfm_settings_form['vendor_dakota'])) ? '' : $wcfm_settings_form['vendor_dakota'];

	update_user_meta($user, 'vendor_data_kota_asal', $vendor_data_asal_kota);
        update_user_meta($user, 'vendor_jne_reg', $vendor_jne_reg);
        update_user_meta($user, 'vendor_jne_oke', $vendor_jne_oke);
        update_user_meta($user, 'vendor_jne_yes', $vendor_jne_yes);
        update_user_meta($user, 'vendor_tiki_reg', $vendor_tiki_reg);
        update_user_meta($user, 'vendor_tiki_eco', $vendor_tiki_eco);
        update_user_meta($user, 'vendor_tiki_ons', $vendor_tiki_ons);
        update_user_meta($user, 'vendor_pos_reguler', $vendor_pos_reguler);
	update_user_meta($user, 'vendor_pos_sameday', $vendor_pos_sameday);
	update_user_meta($user, 'vendor_pos_nextday', $vendor_pos_nextday);
	update_user_meta($user, 'vendor_pos_biasa', $vendor_pos_biasa);
        update_user_meta($user, 'vendor_pos_kilat_khusus', $vendor_pos_kilat_khusus);
        update_user_meta($user, 'vendor_pos_express_next_day', $vendor_pos_express_next_day);
        update_user_meta($user, 'vendor_pos_valuable_goods', $vendor_pos_valuable_goods);
	update_user_meta($user, 'vendor_pos_kprt', $vendor_pos_kprt);
	update_user_meta($user, 'vendor_pos_kpru', $vendor_pos_kpru);
        update_user_meta($user, 'vendor_jnt_ez', $vendor_jnt_ez);
        update_user_meta($user, 'vendor_sicepat_reg', $vendor_sicepat_reg);
        update_user_meta($user, 'vendor_sicepat_best', $vendor_sicepat_best);
        update_user_meta($user, 'vendor_sicepat_gokil', $vendor_sicepat_gokil);
	update_user_meta($user, 'vendor_sicepat_siunt', $vendor_sicepat_siunt);
        update_user_meta($user, 'vendor_sicepat_sds', $vendor_sicepat_sds);
        update_user_meta($user, 'vendor_wahana', $vendor_wahana);
        update_user_meta($user, 'vendor_jmx_cos', $vendor_jmx_cos);
        update_user_meta($user, 'vendor_jmx_sms', $vendor_jmx_sms);
        update_user_meta($user, 'vendor_jmx_lts', $vendor_jmx_lts);
        update_user_meta($user, 'vendor_jmx_sos', $vendor_jmx_sos);	
	update_user_meta($user, 'vendor_lion_regpack', $vendor_lion_regpack);	
	update_user_meta($user, 'vendor_lion_onepack', $vendor_lion_onepack);	
	update_user_meta($user, 'vendor_custom', $vendor_custom);
	update_user_meta($user, 'vendor_flat', $vendor_flat);
	update_user_meta($user, 'vendor_flat_label', $vendor_flat_label);
	update_user_meta($user, 'vendor_sap_ods', $vendor_sap_ods);
	update_user_meta($user, 'epeken_kurir_toko_coverage', $epeken_kurir_toko_coverage);
	update_user_meta($user, 'vendor_sap_sds', $vendor_sap_sds);
	update_user_meta($user, 'vendor_sap_reg', $vendor_sap_reg);
	update_user_meta($user, 'vendor_ninja_next_day', $vendor_ninja_next_day);
	update_user_meta($user, 'vendor_ninja_standard', $vendor_ninja_standard);
	update_user_meta($user, 'vendor_jtr',$vendor_jtr);
	update_user_meta($user, 'vendor_dakota',$vendor_dakota);
	$args = array("user" => $user, "wcfm_settings_form" => $wcfm_settings_form);
        do_action('epeken_save_vendor_item_wcfm',$args);
}

function epeken_save_customer_meta_fields($user) {
   $is_wcpv = false;
   if (epeken_is_wcpv_active() && term_exists($user -> term_id, 'wcpv_product_vendors')) 
    $is_wcpv = true;   

   if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$vendor_data_asal_kota = (empty($_POST['vendor_data_asal_kota'] )) ? '' : $_POST['vendor_data_asal_kota']; // code kota asal
	$vendor_jne_reg = (empty($_POST['vendor_jne_reg'])) ? '' : $_POST['vendor_jne_reg'];
	$vendor_jne_oke = (empty($_POST['vendor_jne_oke'])) ? '' : $_POST['vendor_jne_oke'];
	$vendor_jne_yes = (empty($_POST['vendor_jne_yes'])) ? '' : $_POST['vendor_jne_yes'];
	$vendor_tiki_reg = (empty($_POST['vendor_tiki_reg'])) ? '' : $_POST['vendor_tiki_reg'];
	$vendor_tiki_eco = (empty($_POST['vendor_tiki_eco'])) ? '' : $_POST['vendor_tiki_eco'];
	$vendor_tiki_ons = (empty($_POST['vendor_tiki_ons'])) ? '' : $_POST['vendor_tiki_ons'];
	$vendor_pos_reguler = (empty($_POST['vendor_pos_reguler'])) ? '' : $_POST['vendor_pos_reguler'];
        $vendor_pos_sameday = (empty($_POST['vendor_pos_sameday'])) ? '' : $_POST['vendor_pos_sameday'];
        $vendor_pos_nextday = (empty($_POST['vendor_pos_nextday'])) ? '' : $_POST['vendor_pos_nextday'];
	$vendor_pos_biasa = (empty($_POST['vendor_pos_biasa'])) ? '' : $_POST['vendor_pos_biasa'];
	$vendor_pos_kilat_khusus = (empty($_POST['vendor_pos_kilat_khusus'])) ? '' : $_POST['vendor_pos_kilat_khusus'];
	$vendor_pos_express_next_day = (empty($_POST['vendor_pos_express_next_day'])) ? '' : $_POST['vendor_pos_express_next_day'];
	$vendor_pos_valuable_goods = (empty($_POST['vendor_pos_valuable_goods'])) ? '' : $_POST['vendor_pos_valuable_goods'];
	$vendor_pos_kprt = (empty($_POST['vendor_pos_kprt'])) ? '' : $_POST['vendor_pos_kprt'];
	$vendor_pos_kpru = (empty($_POST['vendor_pos_kpru'])) ? '' : $_POST['vendor_pos_kpru'];
	$vendor_rpx_sdp = (empty($_POST['vendor_rpx_sdp'])) ? '' : $_POST['vendor_rpx_sdp'];
        $vendor_rpx_mdp = (empty($_POST['vendor_rpx_mdp'])) ? '' : $_POST['vendor_rpx_mdp'];
	$vendor_rpx_ndp = (empty($_POST['vendor_rpx_ndp'])) ? '' : $_POST['vendor_rpx_ndp'];
        $vendor_rpx_rgp = (empty($_POST['vendor_rpx_rgp'])) ? '' : $_POST['vendor_rpx_rgp'];
	$vendor_jnt_ez = (empty($_POST['vendor_jnt_ez'])) ? '' : $_POST['vendor_jnt_ez'];
	$vendor_sicepat_reg = (empty($_POST['vendor_sicepat_reg'])) ? '' : $_POST['vendor_sicepat_reg'];
	$vendor_sicepat_siunt = (empty($_POST['vendor_sicepat_siunt'])) ? '' : $_POST['vendor_sicepat_siunt'];
	$vendor_sicepat_best = (empty($_POST['vendor_sicepat_best'])) ? '' : $_POST['vendor_sicepat_best'];
	$vendor_sicepat_gokil = (empty($_POST['vendor_sicepat_gokil'])) ? '' : $_POST['vendor_sicepat_gokil'];
	$vendor_sicepat_sds = (empty($_POST['vendor_sicepat_sds'])) ? '' : $_POST['vendor_sicepat_sds'];
	$vendor_wahana = (empty($_POST['vendor_wahana'])) ? '' : $_POST['vendor_wahana'];
	$vendor_jmx_cos = (empty($_POST['vendor_jmx_cos'])) ? '' : $_POST['vendor_jmx_cos'];
	$vendor_jmx_sms = (empty($_POST['vendor_jmx_sms'])) ? '' : $_POST['vendor_jmx_sms'];
	$vendor_jmx_lts = (empty($_POST['vendor_jmx_lts'])) ? '' : $_POST['vendor_jmx_lts'];
	$vendor_jmx_sos = (empty($_POST['vendor_jmx_sos'])) ? '' : $_POST['vendor_jmx_sos'];
	$vendor_lion_regpack = (empty($_POST['vendor_lion_regpack'])) ? '' : $_POST['vendor_lion_regpack'];
	$vendor_lion_onepack = (empty($_POST['vendor_lion_onepack'])) ? '' : $_POST['vendor_lion_onepack'];
	$vendor_flat = (empty($_POST['vendor_flat'])) ? '' : $_POST['vendor_flat'];
	$vendor_flat_label = (empty($_POST['vendor_flat_label'])) ? '' : $_POST['vendor_flat_label'];
	$epeken_kurir_toko_coverage = (empty($_POST['epeken_kurir_toko_coverage'])) ? '' : $_POST['epeken_kurir_toko_coverage'];
	$vendor_sap_ods = (empty($_POST['vendor_sap_ods'])) ? '' : $_POST['vendor_sap_ods'];
	$vendor_sap_sds = (empty($_POST['vendor_sap_sds'])) ? '' : $_POST['vendor_sap_sds'];
	$vendor_sap_reg = (empty($_POST['vendor_sap_reg'])) ? '' : $_POST['vendor_sap_reg'];
	$vendor_custom = (empty($_POST['vendor_custom'])) ? '' : $_POST['vendor_custom'];
	$vendor_ninja_next_day = (empty($_POST['vendor_ninja_next_day'])) ? '' : $_POST['vendor_ninja_next_day'];
	$vendor_ninja_standard = (empty($_POST['vendor_ninja_standard'])) ? '' : $_POST['vendor_ninja_standard'];
	$vendor_jtr = (empty($_POST['vendor_jtr'])) ? '' : $_POST['vendor_jtr'];	
	$vendor_dakota = (empty($_POST['vendor_dakota'])) ? '' : $_POST['vendor_dakota'];

	if ($is_wcpv) {	
	     $args = array('vendor_data_asal_kota' => $vendor_data_asal_kota, 'vendor_jne_reg' => $vendor_jne_reg, 
			'vendor_jne_oke' => $vendor_jne_oke, 'vendor_jne_yes' => $vendor_jne_yes, 
			'vendor_tiki_reg' => $vendor_tiki_reg, 'vendor_tiki_eco' => $vendor_tiki_eco, 
			'vendor_tiki_ons' => $vendor_tiki_ons, 'vendor_pos_reguler' => $vendor_pos_reguler, 
			'vendor_pos_sameday' => $vendor_pos_sameday, 'vendor_pos_nextday' => $vendor_pos_nextday,
			'vendor_rpx_sdp' => $vendor_rpx_sdp, 'vendor_rpx_mdp' => $vendor_rpx_mdp, 'vendor_rpx_ndp' => $vendor_rpx_ndp,
			'vendor_rpx_rgp' => $vendor_rpx_rgp, 'vendor_jnt_ez' => $vendor_jnt_ez,
			'vendor_sicepat_reg' => $vendor_sicepat_reg, 'vendor_sicepat_siunt' => $vendor_sicepat_siunt, 'vendor_sicepat_best' => $vendor_sicepat_best,
			'vendor_sicepat_gokil' => $vendor_sicepat_gokil, 'vendor_sicepat_sds' => $vendor_sicepat_sds,
			'vendor_wahana' => $vendor_wahana, 'vendor_jmx_cos' => $vendor_jmx_cos, 'vendor_jmx_sms' => $vendor_jmx_sms,
			'vendor_jmx_lts' => $vendor_jmx_lts, 'vendor_jmx_sos' => $vendor_jmx_sos, 'vendor_lion_regpack' => $vendor_lion_regpack,
			'vendor_lion_onepack' => $vendor_lion_onepack, 'vendor_flat' => $vendor_flat, 'vendor_flat_label' => $vendor_flat_label,
			'epeken_kurir_toko_coverage' => $epeken_kurir_toko_coverage, 'vendor_sap_ods' => $vendor_sap_ods, 'vendor_sap_sds' => $vendor_sap_sds,
			'vendor_sap_reg' => $vendor_sap_reg, 'vendor_custom' => $vendor_custom, 'vendor_ninja_next_day' => $vendor_ninja_next_day,
			'vendor_ninja_standard' => $vendor_ninja_standard, 'vendor_jtr' => $vendor_jtr, 'vendor_dakota' => $vendor_dakota); //please continue here.
	     epeken_wcpv_save_vendor_data($user -> term_id, $args);
	     return;
        }
	
	update_user_meta($user, 'vendor_data_kota_asal', $vendor_data_asal_kota);
	update_user_meta($user, 'vendor_jne_reg', $vendor_jne_reg);
	update_user_meta($user, 'vendor_jne_oke', $vendor_jne_oke);
	update_user_meta($user, 'vendor_jne_yes', $vendor_jne_yes);
	update_user_meta($user, 'vendor_tiki_reg', $vendor_tiki_reg);
	update_user_meta($user, 'vendor_tiki_eco', $vendor_tiki_eco);
	update_user_meta($user, 'vendor_tiki_ons', $vendor_tiki_ons);
	update_user_meta($user, 'vendor_pos_reguler', $vendor_pos_reguler);
	update_user_meta($user, 'vendor_pos_sameday', $vendor_pos_sameday);
	update_user_meta($user, 'vendor_pos_nextday', $vendor_pos_nextday);
	update_user_meta($user, 'vendor_pos_biasa', $vendor_pos_biasa);
	update_user_meta($user, 'vendor_pos_kilat_khusus', $vendor_pos_kilat_khusus);
	update_user_meta($user, 'vendor_pos_express_next_day', $vendor_pos_express_next_day);
	update_user_meta($user, 'vendor_pos_valuable_goods', $vendor_pos_valuable_goods);
	update_user_meta($user, 'vendor_pos_kprt', $vendor_pos_kprt);
	update_user_meta($user, 'vendor_pos_kpru', $vendor_pos_kpru);
	update_user_meta($user, 'vendor_rpx_sdp', $vendor_rpx_sdp);
	update_user_meta($user, 'vendor_rpx_mdp', $vendor_rpx_mdp);	
	update_user_meta($user, 'vendor_rpx_ndp', $vendor_rpx_ndp);
	update_user_meta($user, 'vendor_rpx_rgp', $vendor_rpx_rgp);	
	update_user_meta($user, 'vendor_jnt_ez', $vendor_jnt_ez);
	update_user_meta($user, 'vendor_sicepat_reg', $vendor_sicepat_reg);
	update_user_meta($user, 'vendor_sicepat_best', $vendor_sicepat_best);
	update_user_meta($user, 'vendor_sicepat_gokil', $vendor_sicepat_gokil);
	update_user_meta($user, 'vendor_sicepat_sds', $vendor_sicepat_sds);
	update_user_meta($user, 'vendor_sicepat_siunt', $vendor_sicepat_siunt);
	update_user_meta($user, 'vendor_wahana', $vendor_wahana);
	update_user_meta($user, 'vendor_jmx_cos', $vendor_jmx_cos);
	update_user_meta($user, 'vendor_jmx_sms', $vendor_jmx_sms);
	update_user_meta($user, 'vendor_jmx_lts', $vendor_jmx_lts);
	update_user_meta($user, 'vendor_jmx_sos', $vendor_jmx_sos);
	update_user_meta($user, 'vendor_lion_regpack', $vendor_lion_regpack);	
	update_user_meta($user, 'vendor_lion_onepack', $vendor_lion_onepack);
	update_user_meta($user, 'vendor_custom', $vendor_custom);
	update_user_meta($user, 'vendor_flat', $vendor_flat);
	update_user_meta($user, 'vendor_flat_label', $vendor_flat_label);
	update_user_meta($user, 'epeken_kurir_toko_coverage', $epeken_kurir_toko_coverage);
	update_user_meta($user, 'vendor_sap_ods', $vendor_sap_ods);
	update_user_meta($user, 'vendor_sap_sds', $vendor_sap_sds);
	update_user_meta($user, 'vendor_sap_reg', $vendor_sap_reg);
	update_user_meta($user, 'vendor_ninja_next_day', $vendor_ninja_next_day);
	update_user_meta($user, 'vendor_ninja_standard', $vendor_ninja_standard);	
	update_user_meta($user, 'vendor_jtr', $vendor_jtr);
	update_user_meta($user, 'vendor_dakota', $vendor_dakota);
	do_action('epeken_save_vendor_shipping_item', $user);
   }
}

function epeken_save_customer_meta_fields_front_end() {
	$user = wp_get_current_user();
	if(epeken_is_vendor($user) === false)
		return;
	epeken_save_customer_meta_fields($user->id);
}

function epeken_is_vendor($user) {

	if (epeken_is_wcpv_active() && term_exists($user -> term_id, 'wcpv_product_vendors'))
		return true;
	
	$user_info = get_userdata($user->id);
	$user_roles = $user_info -> roles;
	
	if(empty($user_roles))
		return false;
	
	 foreach($user_roles as $role){
		if (strtolower($role) === 'vendor' //vendor is role for seller in wc-vendors
		|| strtolower($role) === 'seller'  //seller is role for seller in dokan
		|| strtolower($role) === 'shop_vendor' //dokan
		|| strtolower($role) === 'shop_manager' //dokan lite
		|| strtolower($role) === 'dc_vendor' //dc-vendor is role for seller in wc-marketplace or multivendorx
		|| strtolower($role) === 'yith_vendor' //yith_vendor is role for seller in yith multivendor
		|| strtolower($role) === 'dropshipper' //woocommerce dropshipper
		|| strtolower($role) === 'wcfm_vendor' //wcfm multivendor marketplace
		) { 
			return true;
		}
	 }
	//mercado
 	$value = false;
	$value = apply_filters('epeken_is_vendor_user_filter', $user_roles);
	return $value;
}

function epeken_is_vendor_id($user_id) {

	if (epeken_is_wcpv_active() && term_exists($user_id, 'wcpv_product_vendors'))
		return true;

	$user_info = get_userdata($user_id);
    	$user_roles = $user_info -> roles;
    
	if(empty($user_roles))
		return false;
	
        foreach($user_roles as $role){
                if (strtolower($role) === 'vendor' //wc-vendors
				|| strtolower($role) === 'seller' //dokan
				|| strtolower($role) === 'shop_vendor' //dokan
				|| strtolower($role) === 'dc_vendor' //wc-vendor
				|| strtolower($role) === 'shop_manager' //dokan lite
				|| strtolower($role) === 'vendor' //wc-vendor 2.0.9
				|| strtolower($role) === 'yith_vendor' //yith multivendor
				|| strtolower($role) === 'dropshipper' //woocommerce dropshipper
				|| strtolower($role) === 'wcfm_vendor' //wcfm multivendor marketplace
			){
                        return true;
                }   
        }   
	//mercado
	$value = false;
 	$value = apply_filters('epeken_is_vendor_id_filter', $user_roles);
        return $value;	
}

function epeken_add_customer_meta_fields_front_end () {
	$user = wp_get_current_user();
	
	if(epeken_is_vendor($user) === false)
		return;
	
	epeken_add_customer_meta_fields($user, true);
}

function epeken_add_customer_meta_fields_wcfm_multivendor($vendor_id) {
	$wp_user = new WP_User($vendor_id);
	epeken_add_customer_meta_fields($wp_user, true);
	?>
	<script type="text/javascript">
	  jQuery(document).ready(function($){
             $('#wcfmmp_settings_form_shipping_expander').hide();
	     $('input[type="checkbox"]').css('-webkit-appearance','checkbox');
          });
	</script>
	<?php
}

function epeken_get_vendor_data_user_based($user) {
	$data = array(
	 'vendor_data_asal_kota' => get_user_meta(intval($user->ID), 'vendor_data_kota_asal', true),
	 'vendor_jne_reg' => get_user_meta(intval($user->ID), 'vendor_jne_reg', true),
	 'vendor_jne_oke' => get_user_meta(intval($user->ID), 'vendor_jne_oke', true),
	 'vendor_jne_yes' => get_user_meta(intval($user->ID), 'vendor_jne_yes', true),
	 'vendor_tiki_reg' => get_user_meta(intval($user->ID), 'vendor_tiki_reg', true),
	 'vendor_tiki_eco' => get_user_meta(intval($user->ID), 'vendor_tiki_eco', true),
	 'vendor_tiki_ons' => get_user_meta(intval($user->ID), 'vendor_tiki_ons', true),
	 'vendor_rpx_sdp' => get_user_meta(intval($user->ID), 'vendor_rpx_sdp', true),
	 'vendor_rpx_mdp' => get_user_meta(intval($user->ID), 'vendor_rpx_mdp', true),	
	 'vendor_rpx_ndp' => get_user_meta(intval($user->ID), 'vendor_rpx_ndp', true),
	 'vendor_rpx_rgp' => get_user_meta(intval($user->ID), 'vendor_rpx_rgp', true),	
	 'vendor_pos_reguler' => get_user_meta(intval($user->ID), 'vendor_pos_reguler', true),
	 'vendor_pos_sameday' => get_user_meta(intval($user->ID), 'vendor_pos_sameday', true),
	 'vendor_pos_nextday' => get_user_meta(intval($user->ID), 'vendor_pos_nextday', true),
 	 'vendor_pos_biasa' => get_user_meta(intval($user->ID), 'vendor_pos_biasa', true),
	 'vendor_pos_kilat_khusus' => get_user_meta(intval($user->ID), 'vendor_pos_kilat_khusus', true),
	 'vendor_pos_express_next_day' => get_user_meta(intval($user->ID), 'vendor_pos_express_next_day', true),
	 'vendor_pos_valuable_goods' => get_user_meta(intval($user->ID), 'vendor_pos_valuable_goods', true),
	 'vendor_pos_kprt' => get_user_meta(intval($user->ID), 'vendor_pos_kprt', true),
	 'vendor_pos_kpru' => get_user_meta(intval($user->ID), 'vendor_pos_kpru', true),
	 'vendor_jnt_ez' => get_user_meta(intval($user->ID), 'vendor_jnt_ez', true),
	 'vendor_sicepat_reg' => get_user_meta(intval($user->ID), 'vendor_sicepat_reg', true),
	 'vendor_sicepat_best' => get_user_meta(intval($user->ID), 'vendor_sicepat_best', true),
	 'vendor_sicepat_gokil' => get_user_meta(intval($user->ID), 'vendor_sicepat_gokil', true),
	 'vendor_sicepat_siunt' => get_user_meta(intval($user->ID), 'vendor_sicepat_siunt', true),
	 'vendor_sicepat_sds' => get_user_meta(intval($user->ID), 'vendor_sicepat_sds', true),
	 'vendor_wahana' => get_user_meta(intval($user->ID), 'vendor_wahana', true),
	 'vendor_jmx_cos' => get_user_meta(intval($user->ID), 'vendor_jmx_cos', true),
	 'vendor_jmx_sms' => get_user_meta(intval($user->ID), 'vendor_jmx_sms', true),
	 'vendor_jmx_lts' => get_user_meta(intval($user->ID), 'vendor_jmx_lts', true),
	 'vendor_jmx_sos' => get_user_meta(intval($user->ID), 'vendor_jmx_sos', true),
	 'vendor_lion_regpack' => get_user_meta(intval($user->ID), 'vendor_lion_regpack', true),
	 'vendor_lion_onepack' => get_user_meta(intval($user->ID), 'vendor_lion_onepack', true),
	 'vendor_custom' => get_user_meta(intval($user->ID), 'vendor_custom', true),
	 'vendor_flat' => get_user_meta(intval($user->ID), 'vendor_flat', true),
	 'vendor_flat_label' => get_user_meta(intval($user->ID), 'vendor_flat_label', true),
	 'epeken_kurir_toko_coverage' => get_user_meta(intval($user->ID), 'epeken_kurir_toko_coverage', false),
	 'vendor_sap_ods' => get_user_meta(intval($user->ID), 'vendor_sap_ods', true),
         'vendor_sap_sds' => get_user_meta(intval($user->ID), 'vendor_sap_sds', true),
         'vendor_sap_reg' => get_user_meta(intval($user->ID), 'vendor_sap_reg', true),
	 'vendor_ninja_next_day' => get_user_meta(intval($user->ID), 'vendor_ninja_next_day', true),
	 'vendor_ninja_standard' => get_user_meta(intval($user->ID), 'vendor_ninja_standard', true),
	 'vendor_jtr' => get_user_meta(intval($user->ID), 'vendor_jtr', true),
	 'vendor_dakota' => get_user_meta(intval($user->ID), 'vendor_dakota', true),
	);
	return $data;
}

function epeken_add_customer_meta_fields($user, $is_front_end = false) {
	$is_wcpv = false;
	if (epeken_is_wcpv_active() && term_exists($user -> term_id, 'wcpv_product_vendors'))
    	  $is_wcpv = true;
  	
	$title = '<h1>Kota Asal Pengiriman Barang Seller/Vendor ini</h1>';
	if ($is_front_end === true){
		$title = '<div style="float: left; margin: 20px 0 20px 0;width: 100%;text-align: left;"><h1>Vendor Shipping Profile</h1></div>';
	}
	if (!$is_wcpv && epeken_is_vendor($user) === false)
		return; // do nothing, return.
	
	$vendor_data = array();
	if($is_wcpv)
		$vendor_data = wcpv_get_epeken_vendor_data($user -> term_id)[0];
 	else
		$vendor_data = epeken_get_vendor_data_user_based($user);

	?>
		<?php if(!epeken_is_wcpv_active()) echo $title; ?>
		<table class="form-table epeken-vendor-shipping-profile">
		 <tr>
		 <th>
			Kota Asal Pengiriman
		 </th>
		 <td>
			<select name="vendor_data_asal_kota" id="vendor_data_asal_kota" style="width: 50%">
			<?php
			$license = get_option('epeken_wcjne_license_key');     
                	$origins = epeken_get_valid_origin($license);
        	        $origins = json_decode($origins,true);
	                $origins = $origins["validorigin"];
			?>		<option value="0">None</option>
			<?php
                	 foreach($origins as $origin){
							$idx=$origin['origin_code'];
							?><option value=<?php echo '"'.$idx.'"'; if($vendor_data['vendor_data_asal_kota'] === $idx){echo ' selected';}?>><?php echo $origin["kota_kabupaten"]; ?></option>
							<?php
	                 }
			?>
			</select>
			<script type='text/javascript'>
					jQuery(document).ready(function($){
							$('#vendor_data_asal_kota').select2();
					});
            </script>
		 </td>
		 </tr>
		 <tr>
		 <th>
		 Expedisi/Kurir Yang Diaktifkan
		 </th>
		 <td>
			<table>
			<tr>
			<td class="td-pilih-kurir">
			 <?php 
   			  $en_jne_reg = get_option('epeken_enabled_jne_reg'); 
			  if($en_jne_reg === 'on') {
			  ?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_jne_reg" <?php if($vendor_data['vendor_jne_reg'] === 'on') echo " checked"; ?>> JNE REG</input><br>
			 <?php } else { echo 'JNE REG (disabled) <br>'; } 
			  $en_jne_oke = get_option('epeken_enabled_jne_oke');
			  if($en_jne_oke === 'on') {
			  ?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_jne_oke" <?php if($vendor_data['vendor_jne_oke'] === 'on') echo " checked"; ?>> JNE OKE</input><br>
			  <?php } else { echo 'JNE OKE (disabled) <br>';}
			  $en_jne_yes = get_option('epeken_enabled_jne_yes');
			  if($en_jne_yes === 'on') {
			  ?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_jne_yes" <?php if($vendor_data['$vendor_jne_yes'] === 'on') echo " checked"; ?>> JNE YES</input><br>
			 <?php } else { echo 'JNE YES (disabled) <br>';}
			 ?> &nbsp;
			</td>
			<?php 
                        $en_tiki_ons = get_option('epeken_enabled_tiki_ons');
                        $en_tiki_reg = get_option('epeken_enabled_tiki_reg');
                        $en_tiki_eco = get_option('epeken_enabled_tiki_eco');
			?>
			<td class="td-pilih-kurir">
			 <?php if($en_tiki_reg === 'on') {?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_tiki_reg" <?php if($vendor_data['vendor_tiki_reg'] === 'on') echo " checked"; ?>> TIKI REG</input><br>
			 <?php } else { echo 'TIKI REG (disabled) <BR>'; } 
			   if($en_tiki_eco === 'on') {
			 ?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_tiki_eco" <?php if($vendor_data['vendor_tiki_eco'] === 'on') echo " checked"; ?>> TIKI ECO</input><br>
			 <?php } else { echo 'TIKI ECO (disabled) <br>'; } 
			 if($en_tiki_ons === 'on') { ?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_tiki_ons" <?php if($vendor_data['vendor_tiki_ons'] === 'on') echo " checked"; ?>> TIKI ONS</input><br>
			 <?php } else { echo 'TIKI ONS (disabled) <br>'; } ?>
			</td>
			</tr>
			<tr>
			<td  class="td-pilih-kurir">
			 <?php 
		 	$en_pos_reg = get_option('epeken_enabled_pos_reguler');
			$en_pos_sd = get_option('epeken_enabled_pos_sameday');
			$en_pos_nd = get_option ('epeken_enabled_pos_nextday');
 			$en_pos_bi = get_option('epeken_enabled_pos_biasa');
                        $en_pos_kk = get_option('epeken_enabled_pos_kilat_khusus');
                        $en_pos_end = get_option('epeken_enabled_pos_express_nextday');
                        $en_pos_vg = get_option('epeken_enabled_pos_val_good');
			$en_pos_kprt = get_option('epeken_enabled_pos_kprt');
			$en_pos_kpru = get_option('epeken_enabled_pos_kpru');
			  if($en_pos_reg === 'on') {
			?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_pos_reguler" <?php if($vendor_data['vendor_pos_reguler'] === 'on') echo " checked"; ?>>POS REGULER</input><br>
			 <?php } else { echo 'POS REGULER (disabled) <br>';} 
			 if($en_pos_sd === 'on') {
			?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_pos_sameday" <?php if($vendor_data['vendor_pos_sameday'] === 'on') echo " checked"; ?>>POS SAMEDAY</input><br>
			 <?php } else { echo 'POS SAMEDAY (disabled) <br>';} 
			 if($en_pos_nd === 'on') {
			?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_pos_nextday" <?php if($vendor_data['vendor_pos_nextday'] === 'on') echo " checked"; ?>>POS NEXTDAY</input><br> 
			 <?php } else { echo 'POS NEXTDAY (disabled) <br>';} 
echo "<!-- ";
		 	if($en_pos_bi === 'on') {
			?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_pos_biasa" <?php if($vendor_data['vendor_pos_biasa'] === 'on') echo " checked"; ?>> PAKETPOS BIASA</input><br>
			 <?php } else { echo 'PAKETPOS BIASA (disabled) <br>';} 
			  if($en_pos_kk === 'on') {
			 ?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_pos_kilat_khusus" <?php if($vendor_data['vendor_pos_kilat_khusus'] === 'on') echo " checked"; ?>> POS KILAT KHUSUS</input><br>
			 <?php } else { echo 'POS KILAT KHUSUS (disabled) <br>';}
			 	if($en_pos_end === 'on') {
			  ?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_pos_express_next_day" <?php if($vendor_data['vendor_pos_express_next_day'] === 'on') echo " checked"; ?>> POS Express Next Day</input><br> <?php }
			if($en_pos_vg === 'on') { ?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_pos_valuable_goods" <?php if($vendor_data['vendor_pos_valuable_goods'] === 'on') echo " checked"; ?>> POS Valuable Goods</input><br>
			<?php }  else {echo 'POS Valuable Goods (disabled) <br>'; }
			if($en_pos_kprt === 'on') {
			?>
			<input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_pos_kprt" <?php if($vendor_data['vendor_pos_kprt'] === 'on') echo " checked"; ?>>Kargo Pos Retail Train</input><br>
			<?php } else { echo 'Kargo Pos Retail Train (disabled) <br>';} 
			 if($en_pos_kpru === 'on') {
			?>
			<input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_pos_kpru" <?php if($vendor_data['vendor_pos_kpru'] === 'on') echo " checked"; ?>>Kargo Pos Retail Udara</input><br>
			<?php } else {echo 'Kargo Pos Retail Udara (disabled) <br>';} 
echo " -->";
?>
			 
			</td>
			<td class="td-pilih-kurir">
			<?php 
			$en_rpx_sdp = get_option('epeken_enabled_rpx_sdp');
                        $en_rpx_mdp = get_option('epeken_enabled_rpx_mdp');
                        $en_rpx_ndp = get_option('epeken_enabled_rpx_ndp');
                        $en_rpx_rgp = get_option('epeken_enabled_rpx_rgp');
			if($en_rpx_sdp === 'on') {
			?>
 			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_rpx_sdp" <?php if($vendor_data['vendor_rpx_sdp'] === 'on') echo " checked"; ?>> RPX SDP (Sameday)</input><br> 
			<?php } else { echo 'RPX SDP (disabled) <br>';}
			if($en_rpx_mdp === 'on') {
			?>
 			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_rpx_mdp" <?php if($vendor_data['vendor_rpx_mdp'] === 'on') echo " checked"; ?>> RPX MDP (Midday)</input><br> 
			<?php } else { echo 'RPX MDP (disabled) <br>';}
			if($en_rpx_ndp === 'on') {
			?>
			<input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_rpx_ndp" <?php if($vendor_data['vendor_rpx_ndp'] === 'on') echo " checked"; ?>> RPX NDP (Nextday)</input><br> 
			<?php } else { echo 'RPX NDP (disabled) <br>';} 
			if($en_rpx_rgp === 'on') {
			?>
			<input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_rpx_rgp" <?php if($vendor_data['vendor_rpx_rgp'] === 'on') echo " checked"; ?>> RPX RGP (Regular)</input><br> 
			<?php } else { echo 'RPX RGP (disabled) <br>';} ?>
			</td>
			</tr>
			<tr>
			<td class="td-pilih-kurir">
			 <?php $en_jetez = get_option('epeken_enabled_jetez'); 
			 if($en_jetez === 'on') { ?>
			 <input class="wcfm-checkbox wcfm_ele"  type="checkbox" name="vendor_jnt_ez" <?php if($vendor_data['vendor_jnt_ez'] === 'on') echo " checked"; ?>> J&T EZ</input><br>
			 <?php }  else {echo 'J&T EZ (disabled) <br>';}?>
			</td>
			<td class="td-pilih-kurir">
			 <?php 
				$en_sicepat_reg = get_option('epeken_enabled_sicepat_reg');
                        	$en_sicepat_best = get_option('epeken_enabled_sicepat_best');
				$en_sicepat_gokil = get_option('epeken_enabled_sicepat_gokil');
				$en_sicepat_siunt = get_option('epeken_enabled_sicepat_siunt');
				$en_sicepat_sds = get_option('epeken_enabled_sicepat_sds');
			 if($en_sicepat_reg === 'on') {
			 ?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_sicepat_reg" <?php if($vendor_data['vendor_sicepat_reg'] === 'on') echo " checked"; ?>> SICEPAT REG</input><br>
			 <?php } else {echo 'SICEPAT REG (disabled) <br>'; } 
			 if($en_sicepat_best === 'on') { ?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_sicepat_best" <?php if($vendor_data['vendor_sicepat_best'] === 'on') echo " checked"; ?>> SICEPAT BEST</input><br>
			 <?php } else {echo 'SICEPAT BEST (disabled) <br>'; } 
			 ?> &nbsp;
			<?php if($en_sicepat_gokil === 'on') { ?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_sicepat_gokil" <?php if($vendor_data['vendor_sicepat_gokil'] === 'on') echo " checked"; ?>> SICEPAT GOKIL</input><br>
			 <?php } else {echo 'SICEPAT GOKIL (disabled) <br>'; } 
			 ?> &nbsp;
			 <?php if($en_sicepat_siunt === 'on') { ?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_sicepat_siunt" <?php if($vendor_data['vendor_sicepat_siunt'] === 'on') echo " checked"; ?>> SICEPAT SIUNT</input><br>
			 <?php } else {echo 'SICEPAT SIUNT (disabled) <br>'; } 
			 ?> &nbsp;
			 <?php if($en_sicepat_sds === 'on') { ?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_sicepat_sds" <?php if($vendor_data['vendor_sicepat_sds'] === 'on') echo " checked"; ?>> SICEPAT SDS</input><br>
			 <?php } else {echo 'SICEPAT SDS (disabled) <br>'; } 
			 ?> &nbsp;
			</td>
			<td class="td-pilih-kurir">
			 <?php $en_wahana = get_option('epeken_enabled_wahana'); if($en_wahana === 'on') { ?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_wahana" <?php if($vendor_data['vendor_wahana'] === 'on') echo " checked"; ?>> Wahana </input><br>
			 <?php } else {'Wahana (disabled) <br>';}?> &nbsp;
			</td>
			</tr>
			<tr>
			<td>
			 <?php 
				$en_jmx_cos = get_option('epeken_enabled_jmx_cos');
                $en_jmx_lts = get_option('epeken_enabled_jmx_lts');
                $en_jmx_sms = get_option('epeken_enabled_jmx_sms');
                $en_jmx_sos = get_option('epeken_enabled_jmx_sos');
			 	if($en_jmx_cos === 'on') {
			 ?>
		          <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_jmx_cos" <?php if($vendor_data['vendor_jmx_cos'] === 'on') echo " checked"; ?>> JMX COS</input><br>
			<?php } else { echo 'JMX COS (disabled) <br>';} if ($en_jmx_sms === 'on') { ?>
			  <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_jmx_sms" <?php if($vendor_data['vendor_jmx_sms'] === 'on') echo " checked"; ?>> JMX SMS</input><br>
			<?php } else { echo 'JMX SMS (disabled) <br>';} if ($en_jmx_lts === 'on') { ?>
			  <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_jmx_lts" <?php if($vendor_data['vendor_jmx_lts'] === 'on') echo " checked"; ?>> JMX LTS</input><br>
			<?php } else { echo 'JMX LTS (disabled) <br>';} if($en_jmx_sos === 'on') { ?>
			  <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_jmx_sos" <?php if($vendor_data['vendor_jmx_sos'] === 'on') echo " checked"; ?>> JMX SOS</input><br>
			<?php } else { echo 'JMX SOS (disabled) <br>';} ?>
			</td>
			<td class="td-pilih-kurir">
			 <?php
				$en_lion_onepack = get_option('epeken_enabled_lion_onepack');
				$en_lion_regpack = get_option('epeken_enabled_lion_regpack');
				if($en_lion_regpack === 'on') {
					?>
					<input type="checkbox" class="wcfm-checkbox wcfm_ele" name="vendor_lion_regpack" <?php if($vendor_data['vendor_lion_regpack'] === 'on') echo " checked"; ?>>Lion Parcel REGPACK</input><br>
					<?php
				} else { echo 'LION REGPACK (disabled) <br>';} if ($en_lion_onepack === 'on'){
					?>
					<input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_lion_onepack" <?php if($vendor_data['vendor_lion_onepack'] === 'on') echo " checked"; ?>>Lion Parcel ONEPACK</input><br>
					<?php
				}
			 ?>
			</td>
			<td>
			<?php
			$sapsds = get_option('epeken_enabled_sap_sds');
			$sapods = get_option('epeken_enabled_sap_ods');
			$sapreg = get_option('epeken_enabled_sap_reg');
			?>
			<?php if ($sapods === 'on') {?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_sap_ods" <?php if($vendor_data['vendor_sap_ods'] === 'on') echo "checked"; ?>/> SAP ODS <br>	
			<?php } ?>
			<?php if ($sapsds === 'on') {?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_sap_sds" <?php if($vendor_data['vendor_sap_sds'] === 'on') echo "checked"; ?>/> SAP SDS <br>
			<?php } ?>
			<?php if ($sapreg === 'on') {?>
			 <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_sap_reg" <?php if($vendor_data['vendor_sap_reg'] === 'on') echo "checked"; ?>/> SAP REG <br>
			<?php } ?>
			</td>
			</tr>
			<tr>
			<td class="td-pilih-kurir">
			<?php $ninja_next_day = get_option('epeken_enabled_ninja_next_day'); $ninja_standard = get_option('epeken_enabled_ninja_standard'); ?>
			<?php if($ninja_next_day ==='on') { ?>
			<input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_ninja_next_day" <?php if($vendor_data['vendor_ninja_next_day'] === 'on') echo "checked"; ?>/> Ninja Express Next Day <br>
			<?php } ?>
			<?php if($ninja_standard ==='on') { ?>
                        <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_ninja_standard" <?php if($vendor_data['vendor_ninja_standard'] === 'on') echo "checked"; ?>/> Ninja Express Standard <br>
                        <?php } ?>
			</td>
			<td class="td-pilih-kurir">
			<?php 
			$jtr_flag = get_option('epeken_enabled_jne_trucking_tarif');
			if($jtr_flag === 'on') {
			 ?>
			<input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_jtr" <?php if($vendor_data['vendor_jtr'] === 'on') echo "checked"; ?>/>JNE Trucking<br>
			<?php
			}else{
				echo 'JNE Trucking(disabled)<br>';
			}
			?>
			</td>
			<td class="td-pilih-kurir">
                          <?php $en_enabled_custom = get_option('epeken_enabled_custom_tarif');
                           if($en_enabled_custom === 'on') {
                          ?>
                         <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_custom" 
<?php if($vendor_custom === 'on') echo " checked"; ?>> Other Shipping (JExpress)  </input><br>
                         <?php } ?> &nbsp;
                        </td>
			</tr>
			<tr style="border: 1px solid;">
			  <td class="td-pilih-kurir">
			 <?php $en_enabled_flat = get_option('epeken_enabled_flat');
                           if($en_enabled_flat === 'on') {
                         ?>
				<table style="padding: 10px;">
				<tr><td colspan=2><strong>Tarif Kurir Toko (Vendor) Flat</strong></td></tr>
				<tr>
				<td>
				Label Kurir Flat </td> 
				<td><input type="text" name="vendor_flat_label" <?php if(!empty($vendor_data['vendor_flat_label'])) echo "value='".$vendor_data['vendor_flat_label']."'";?> /></td> 
				</tr><tr>
				<td>
				Ongkos Kirim Flat Tarif RP </td>
				<td><input type="number" name="vendor_flat" <?php if(!empty($vendor_data['vendor_flat'])) echo "value='".$vendor_data['vendor_flat']."'";?> />
				</td>
				</tr>
				</table>
                         <?php
                           }
			 ?>
			  </td>
			  <td>
			   <p>Wilayah coverage kurir toko</p>
			   <p>
				<select multiple="multiple" class="multiselect chosen_select ajax_chosen_select_city" name="epeken_kurir_toko_coverage[]"
				id="epeken_kurir_toko_coverage" data-placeholder="Pilih kota" style="width: 90%;">
				<?php
				 $listkota = epeken_get_list_of_kota_kabupaten();
				 if(!empty($listkota)){
					foreach($listkota as $kota){
					  $selected = '';
                                          $existing_config = $vendor_data['epeken_kurir_toko_coverage'];
                                          if (!empty($existing_config) && is_array($existing_config)) {
					   $existing_config = $existing_config[0];
 					   if(is_array($existing_config)) {
                                                for($x=0;$x<sizeof($existing_config);$x++){
                                                  if($kota === $existing_config[$x]){
                                                     $selected = 'selected';
                                                     break;
                                                  }
						}
                                           }
                                          }
					?>
					 <option value="<?php echo $kota;?>" <?php echo $selected; ?>><?php echo $kota;?></option>
					<?php
					}
				 }
				?>
				</select>
			   </p></td>
			</tr>
			<tr>
			<td class="td-pilih-kurir">
			<?php
			$dakota_flag = get_option('epeken_enabled_dakota_tarif');
			if($dakota_flag === 'on') {
			  ?>
			   <input class="wcfm-checkbox wcfm_ele" type="checkbox" name="vendor_dakota" <?php if($vendor_data['vendor_dakota'] === 'on') echo "checked"; ?>/>Dakota<br>
			  <?php
			} else {
				echo "Dakota (disabled)<br>";
			}
?>
			</td>
			<td style="padding-top: 5px;">
			<em>Pengaturan Pengiriman Internasional 
			hanya dapat diaktifkan oleh Administrator.</em></td>
			</tr></table>
		 </td>
		 </tr>
		 <?php do_action('epeken_add_vendor_shipping_item', $user); ?>
		</table>
	<?php
}

add_action('epeken_hook_calculate_shipping', 'epeken_set_shipping_cost');

function epeken_set_shipping_cost ($inputs) {
	$epeken_all_kurir = $inputs ["shipping_class"];
	$package = $inputs["package"];
	$epeken_all_kurir -> reset_shipping();
	$epeken_all_kurir -> refresh_usd_rate();
	$epeken_all_kurir -> set_shipping_cost_intl($package);
	$epeken_all_kurir -> set_shipping_cost($package);
	$epeken_all_kurir -> post_nonms_calculate_shipping();
}

function epeken_get_item_vendor_origin ($product_id) {
	$produk = get_post($product_id);
	$vendor_kota_asal = '';
	if(epeken_is_wcpv_active()){
	 $vendor_id = wp_get_post_terms($product_id,'wcpv_product_vendors')[0] -> term_id; 
	 $vendor_kota_asal = get_term_meta($vendor_id,'epeken_vendor_data')[0]['vendor_data_asal_kota'];
	}else{
	 $produk_vendor = get_userdata($produk -> post_author);	
	 $vendor_id = $produk_vendor -> ID;
	 $vendor_kota_asal = get_user_meta($vendor_id, 'vendor_data_kota_asal', true);
	}
	return $vendor_kota_asal;
}

function epeken_get_item_dropshipper_origin($product_id) {
	$vendor_user_login = get_post_meta($product_id, 'woo_dropshipper', true);
	if(empty($vendor_user_login))
	{ return 'N/A'; }
	$wpuser = get_user_by('login', $vendor_user_login);
	$vendor_id = $wpuser -> ID;
	$vendor_kota_asal = get_user_meta($vendor_id, 'vendor_data_kota_asal', true);
	return $vendor_kota_asal;	
}

function epeken_yith_get_user_id_from_vendor($vendor) {
	global $wpdb;
	$vendor_id = $vendor -> id;
	$query = "select user_id from ".$wpdb->prefix."usermeta where meta_key = 'yith_product_vendor' and meta_value = ".$vendor_id;
	$meta_key = $wpdb -> get_row($query);
	return $meta_key -> user_id;
}

function epeken_get_item_vendor($product_id) {
	$produk = get_post($product_id);
	if($produk -> post_parent !== 0)
		$produk = get_post($produk -> post_parent);
	$produk_vendor = get_userdata($produk -> post_author);
	$vendor_id = $produk_vendor -> ID;

	if(epeken_is_wcpv_active()) {
		$vendor_id = wp_get_post_terms($produk_vendor -> ID, 'wcpv_product_vendors')[0] -> term_id;
	}
	
	return $vendor_id;
}

function epeken_get_item_vendor_woo_dropshippers($product_id) {
	$product = get_post($product_id);
	$parent_product_id = $product -> post_parent;
	$the_product_id = $product_id;
	if($parent_product_id !== 0)
		$the_product_id = $parent_product_id;

	$dropshipper_username = get_post_meta($the_product_id, 'woo_dropshipper', true);
	$dropshipper_wp_user = new WP_User($dropshipper_username);
	$user_id = $dropshipper_wp_user -> ID;
	return $user_id;
}

function epeken_is_multi_vendor_mode($checksetting=false) {
 if(epeken_is_yith_multivendor_active() || epeken_is_dokan_active() 
   || epeken_is_wc_vendor_active() || epeken_is_wc_marketplace_active()
	|| epeken_is_woo_dropshippers_active() || epeken_is_wcfm_multivendor_active()
		|| epeken_is_wcpv_active()
   ) {

	if($checksetting)
		return true;
		
	$epeken_ongkir_per_vendor = get_option('epeken_ongkir_per_vendor');

	if($epeken_ongkir_per_vendor === 'off') 
		return false;

	return true;
  }
	//mercado
	$value = false;
	$value = apply_filters('epeken_is_multi_vendor_mode_filters', $value);
	return $value;
}

function epeken_is_wcpv_active() {
   return is_plugin_active('woocommerce-product-vendors/woocommerce-product-vendors.php');
}

function epeken_is_multi_inventory() {
   $third_party_plugins = is_plugin_active('woocommerce-multi-inventory/woocommerce-multi-inventory.php');
   if (!$third_party_plugins)
        $third_party_plugins = defined('WCMLIM_VERSION');
   $epeken_bridge_plugin = is_plugin_active('epeken-multi-inventory-bridge/epeken-multi-inventory-bridge.php');
   return $third_party_plugins && $epeken_bridge_plugin;
}

if (!function_exists('epeken_is_wcfm_multivendor_active')){
 function epeken_is_wcfm_multivendor_active() {
        return is_plugin_active('wc-multivendor-marketplace/wc-multivendor-marketplace.php');
 }
}

function epeken_doesnt_allow_wcfm_todo_epeken_setting($bool) {
         return false;
}

function epeken_is_woo_dropshippers_active() {
	return is_plugin_active('woocommerce-dropshippers/woocommerce-dropshippers.php');
}

function epeken_is_yith_multivendor_active() {
	return (defined('YITH_WPV_PREMIUM') || defined('YITH_WPV_FREE_INIT'));
}

function epeken_is_dokan_lite_active() {
	return is_plugin_active('dokan-lite/dokan.php');
}

if(!function_exists('epeken_is_dokan_pro_active')){
 function epeken_is_dokan_pro_active() {
	return is_plugin_active('dokan-pro/dokan-pro.php');
 }
}

function epeken_is_wc_vendor_active() {
	return is_plugin_active('wc-vendors/class-wc-vendors.php');
}

function epeken_is_dokan_active() {
	return (is_plugin_active('dokan-lite/dokan.php') || is_plugin_active('dokan-pro/dokan-pro.php'));
}

function epeken_is_wc_marketplace_active() {
	/* wc marketplace has been rebranded to MultiVendorX */
	//return (defined('WCMp_PLUGIN_VERSION'));
	return(defined('MVX_PLUGIN_VERSION'));
}	

add_filter( 'woocommerce_cart_shipping_packages', 'epeken_generate_package', 1000);

function epeken_generate_package($packages) {
	if(!epeken_is_multi_vendor_mode()) 
		return $packages; //do nothing if webstore is not a multi vendor or built on top of marketplace concept

	$packages = array(); //reset package
	$vendors = epeken_list_vendor_in_cart();	
	$n = 0;
	foreach($vendors as $vendor_id) {
		$vendor_shop_name = '';
		if(epeken_is_wc_vendor_active()){
		 $vendor_shop_name = WCV_Vendors::get_vendor_shop_name( stripslashes( $vendor_id ) );	
		}else if(epeken_is_dokan_active()){
		 $store_info = dokan_get_store_info( $vendor_id );
		 $vendor_shop_name = $store_info['store_name'];
		}else if(epeken_is_wc_marketplace_active()){
		 $vendor = get_mvx_vendor($vendor_id);
		 $vendor_shop_name = $vendor -> user_data -> data -> display_name;
		}else if(epeken_is_yith_multivendor_active()) {
		  $vendor = yith_get_vendor($vendor_id,'user');
		  $vendor_shop_name = $vendor -> term -> name; 
		}else if(epeken_is_woo_dropshippers_active()){
		 $wp_user = new WP_User($vendor_id);
		 $vendor_shop_name = $wp_user -> first_name.' '.$wp_user -> last_name;	
		 $vendor_shop_name = trim($vendor_shop_name);
	 	 if(empty($vendor_shop_name))
			$vendor_shop_name = home_url();
		}else if(epeken_is_wcfm_multivendor_active()){
		 $wp_user = new WP_User($vendor_id);
		 $display_name = $wp_user -> display_name;
		 $vendor_data = get_user_meta( $vendor_id, 'wcfmmp_profile_settings', true );
		 $vendor_shop_name = isset( $vendor_data['store_name'] ) ? esc_attr( $vendor_data['store_name'] ) : $display_name;
		}else if(epeken_is_wcpv_active()) {
		 $vendor_info = get_term_by('id', $vendor_id, 'wcpv_product_vendors');
		 $vendor_shop_name = $vendor_info -> name; 
		}
		//mercado
		//add_filter('epeken_vendor_shop_name_filter', 'callback', 10, 2); //implement callback with this add filter to customize vendor_shop_name
		$vendor_shop_name = apply_filters('epeken_vendor_shop_name_filter', $vendor_shop_name, $vendor_id); 
		$items_of_vendor = epeken_list_product_of_vendor_in_cart($vendor_id);	
		$packages[$n] = array(
			'package_name' => $vendor_shop_name,
			'vendor_id' => $vendor_id,
			'seller_id' => $vendor_id,
			'contents' => $items_of_vendor,
			'weight' => epeken_calculate_vendor_package_weight($items_of_vendor),
			'contents_cost' => array_sum( wp_list_pluck( $items_of_vendor, 'line_total' ) ),
			'applied_coupons' => WC()->cart->applied_coupons,
			'destination' => array(
			'country' => WC()->customer->get_shipping_country(),
			'state' => WC()->customer->get_shipping_state(),
			'postcode' => WC()->customer->get_shipping_postcode(),
			'city' => WC()->customer->get_shipping_city(),
			'address' => WC()->customer->get_shipping_address(),
			'address_2' => WC()->customer->get_shipping_address_2()
			),
		);
		$n++;		
	}
	return $packages;	
}
if(epeken_is_wc_marketplace_active()){
        add_action('woocommerce_checkout_create_order_shipping_item', 'epeken_modify_vendor_id_in_shipping_item', 100,3);
}
function epeken_modify_vendor_id_in_shipping_item($item, $package_key, $package) {
	$item -> update_meta_data('vendor_id', $package['vendor_id']);
	$item -> save_meta_data();
}
if(epeken_is_dokan_active()){
        add_action('woocommerce_checkout_create_order_shipping_item', 'epeken_add_seller_id_in_shipping_item', 100,3);
}
function epeken_add_seller_id_in_shipping_item($item, $package_key, $package){
        $item -> update_meta_data('seller_id', $package['seller_id']);
        $item -> save_meta_data();
}
if(epeken_is_wcfm_multivendor_active()) {
        add_action('woocommerce_checkout_create_order_shipping_item', 'epeken_add_wcfm_vendor_id_in_shipping_item', 100,3);
}

function epeken_add_wcfm_vendor_id_in_shipping_item($item, $package_key, $package) {
        $quantity = 0;
		foreach($package["contents"] as $content) {
			$quantity = $quantity + $content["quantity"];
		}
        $item -> update_meta_data('vendor_id', $package['vendor_id']);
        $item -> update_meta_data('package_qty', $quantity);
		$item -> save_meta_data();
}
function epeken_list_vendor_in_cart() {
	$vendors = array();
	foreach ( WC()->cart->get_cart() as $item ) {
		$vendor_id = 0;	
		if(epeken_is_yith_multivendor_active()) {
		 $base_product_id = $item['product_id'];//epeken_get_item_vendor($item['product_id']);
                 $vendor = yith_get_vendor( $base_product_id, 'product' );
		 $vendor_id = $vendor -> id;
		 $vendor_id = get_term_meta($vendor_id,'owner',true);
        	}else if(epeken_is_woo_dropshippers_active()) {
		 $vendor_id = epeken_get_item_vendor_woo_dropshippers($item['data'] -> get_id()); 
		}else if(epeken_is_dokan_pro_active()) {
		  $vendor_id = epeken_get_item_vendor($item['product_id']);
		}else if(epeken_is_wcfm_multivendor_active()){
		  $vendor_id = epeken_get_item_vendor($item['product_id']);
		}else if(epeken_is_wcpv_active()){
		  $vendor = wp_get_post_terms($item['product_id'], 'wcpv_product_vendors')[0];
		  $vendor_id = $vendor -> term_id;
		}else{
		  $vendor_id = epeken_get_item_vendor($item['data'] -> get_id());
		}

		$logger = new WC_Logger();
		$logger -> add('epeken-all-kurir', 'vendor id: '.$vendor_id);
		
		if(!in_array($vendor_id, $vendors)){
	 	  array_push($vendors, $vendor_id);
		}
	}
	return $vendors;
}
function epeken_calculate_vendor_package_weight($items) {
	$shipping = WC_Shipping::instance();
	$methods = $shipping -> get_shipping_methods();
	$epeken_tikijne = $methods['epeken_courier'];
	$weight = 0;
	foreach($items as $item) {
	 	$dimensi_barang = 0; $berat_barang = 0; $panjang_barang = 0; $lebar_barang = 0; $tinggi_barang = 0;	
		if(is_numeric($item['data'] -> get_weight()))
		 $berat_barang = (floatval($item['quantity']) * floatval($item['data'] -> get_weight()));

		if(get_option('woocommerce_weight_unit') === "g") {
                    $berat_barang = $berat_barang / 1000;
                }

		$tinggi_barang = $item['data'] -> get_height();
		$panjang_barang =  $item['data'] -> get_length(); 
	 	$lebar_barang = $item['data'] -> get_width();

		if(!is_numeric($tinggi_barang))
			$tinggi_barang = 1;
		if(!is_numeric($panjang_barang))
			$panjang_barang = 1;
		if(!is_numeric($lebar_barang))
			$lebar_barang = 1;

		$panjang_barang = $panjang_barang * floatval($item['quantity']);

		$dimensi_barang = ($length * $width * $height) / 6000;
		
		if($dimensi_barang > $weight)
			$berat_barang = $dimensi_barang;

		$weight = $weight + $berat_barang;
	}
	return $weight;
}
function epeken_list_product_of_vendor_in_cart($vendor_id) {
	$items = array();
	foreach ( WC()->cart->get_cart() as $item ) {

		if(!$item['data']->needs_shipping())
		  continue;

		$item_vendor_id = 0; //epeken_get_item_vendor($item['data'] -> get_id());
		if(epeken_is_yith_multivendor_active()) {
		 //$base_product_id = yit_get_base_product_id( $item['data'] );
		 $base_product_id = $item['product_id'];
                 $vendor = yith_get_vendor( $base_product_id, 'product' );
                 //$item_vendor_id = epeken_yith_get_user_id_from_vendor($vendor);
		 $item_vendor_id = $vendor -> id;
		 $item_vendor_id = get_term_meta($item_vendor_id,'owner',true);
                }else if(epeken_is_woo_dropshippers_active()) {
                 $item_vendor_id = epeken_get_item_vendor_woo_dropshippers($item['data'] -> get_id());
                }else if(epeken_is_dokan_pro_active() || epeken_is_wcfm_multivendor_active()) {
                  $item_vendor_id = epeken_get_item_vendor($item['product_id']);
                }else if(epeken_is_wcpv_active()) {
		  $item_vendor = wp_get_post_terms($item['product_id'], 'wcpv_product_vendors')[0];
		  $item_vendor_id = $item_vendor -> term_id;
		}else{
                  $item_vendor_id = epeken_get_item_vendor($item['data'] -> get_id());
                }
		if($vendor_id === $item_vendor_id)
			array_push($items, $item);
	}
	return $items;
}
//add_filter( 'woocommerce_shipping_package_name', 'epeken_change_shipping_pack_name', 10, 3 );
/*function epeken_change_shipping_pack_name($title, $i, $package ) {
	$package_name = '';
	if(isset($package['package_name']))
		$package_name = $package['package_name'];

	$package_weight = '';
	if(isset($package['weight']))
	 	$package_weight = $package['weight'];

	$package_vendor_id = '';
	if(isset($package['vendor_id']))
		$package_vendor_id = $package['vendor_id'];


	$package_destination_city = '';
	if(isset($package['destination']['city']))
		$package_destination_city = $package['destination']['city'];	

	if(epeken_is_multi_vendor_mode() && is_checkout()) {
	 return __('Pengiriman Dari', 'epeken-all-kurir').'<br><span style="font-size: 12px;">'.$package_name.
			'<br>Berat: '.$package_weight.' '.get_option('woocommerce_weight_unit').
			'<br>Asal: '.epeken_code_to_city(get_user_meta($package_vendor_id, 'vendor_data_kota_asal', true)).
			'<br>Tujuan: '.$package_destination_city.'</span>';	
	}
	return __('Shipping', 'epeken-all-kurir');	
}*/
add_action('begin_wcfm_dokan_settings_form', 'epeken_wcfm_dokan_settings');
function epeken_wcfm_dokan_settings () {
	$user_id = get_current_user_id();
	$user = new WP_User($user_id);
?>
	<div style="max-height: 800px !important">
		<!-- collapsible -->
			<div class="page-collapsible" style="margin: 0 10px;">
                                        <label class="wcfmfa fa-truck"></label>
                                        <?php _e('Epeken Shipping', 'wc-frontend-manager'); ?><span></span>
				</div>
		<div class="">
		<div style="margin: 0 10px;">
<?php
	epeken_add_customer_meta_fields($user, false);
?>
		</div></div>
	 </div>
	<?php	 
}

add_filter( 'wcfm_order_details_shipping_line_item', 'epeken_show_wcfm_shipping_in_vendor_order', 100 );
function epeken_show_wcfm_shipping_in_vendor_order($bool) {
  return true;
}

add_filter('wcfm_order_details_shipping_total', 'epeken_show_wcfm_order_details_shipping_total', 100);
function epeken_show_wcfm_order_details_shipping_total ($bool)
{
  return false;
}

add_filter( 'dokan_checkout_update_order_meta', 'epeken_dokan_sync_order_address', 10, 2 );
function epeken_dokan_sync_order_address($order_id, $seller_id) {
	$child_post = get_post($order_id);
	$parent_id = $child_post -> post_parent;
	$parent_post = get_post($parrent_id);
	update_post_meta($order_id, 'shipping_kelurahan', get_post_meta($parent_id, 'shipping_kelurahan', true));
	update_post_meta($order_id, 'billing_kelurahan', get_post_meta($parent_id, 'billing_kelurahan', true));
	update_post_meta($order_id, 'shipping_kecamatan', get_post_meta($parent_id, 'shipping_kecamatan', true));
	update_post_meta($order_id, 'billing_kecamatan', get_post_meta($parent_id, 'billing_kecamatan', true));
	return $child_order;
}

function epeken_get_parent_order($child_order){
  $child_post = get_post($child_order->ID);
  $parent_id = $child_post -> post_parent;
  if (empty($parent_id))
	  return false;
  $parent_order = new WC_Order($parent_id); 
  return $parent_order;
}


#add_filter('wcfmmp_sold_by_label','epeken_change_sold_by_label');
#function epeken_change_sold_by_label ($sold_by_label, $vendor_id) {
#        return 'Pelapak';
#}

?>
