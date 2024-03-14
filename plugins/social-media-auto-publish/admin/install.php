<?php
if( !defined('ABSPATH') ){ exit();}
function smap_free_network_install($networkwide) {
	global $wpdb;

	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if ($networkwide) {
			$old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				smap_install_free();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	smap_install_free();
}

function smap_install_free()
{
	
	$pluginName = 'xyz-wp-smap/xyz-wp-smap.php';
	if (is_plugin_active($pluginName)) {
		wp_die( "The plugin Social Media Auto Publish cannot be activated unless the premium version of this plugin is deactivated. Back to <a href='".admin_url()."plugins.php'>Plugin Installation</a>." );
	}
	if (version_compare(PHP_VERSION, '5.4.0', '<')) {	
		wp_die( "The plugin Social Media Auto Publish requires PHP version 5.4 or higher. Back to <a href='".admin_url()."plugins.php'>Plugin Installation</a>." );
	}
	$pluginName = 'xyz-wp-smap-plus/xyz-wp-smap-plus.php';
	if (is_plugin_active($pluginName)) {
		wp_die( "The plugin Social Media Auto Publish cannot be activated unless the premium version of this plugin is deactivated. Back to <a href='".admin_url()."plugins.php'>Plugin Installation</a>." );
	}
	global $current_user;
	wp_get_current_user();
	if(get_option('xyz_credit_link')=="")
	{
		add_option("xyz_credit_link", '0');
	}

	$linkedin_siwl=1;//sign in with linkedin 
	$smap_installed_date = get_option('smap_installed_date');
	if ($smap_installed_date=="") {
		$linkedin_siwl=0;//siwl using openID
		$smap_installed_date = time();
		update_option('smap_installed_date', $smap_installed_date);
	}
	add_option('xyz_smap_application_name','');
	add_option('xyz_smap_application_id','');
	add_option('xyz_smap_application_secret', '');
	//add_option('xyz_smap_fb_id', '');
	add_option('xyz_smap_message', 'New post added at {BLOG_TITLE} - {POST_TITLE}');
 	add_option('xyz_smap_po_method', '2');
	add_option('xyz_smap_post_permission', '1');
	add_option('xyz_smap_current_appln_token', '');
	add_option('xyz_smap_af', '1'); //authorization flag
	add_option('xyz_smap_ig_af', '1');
	add_option('xyz_smap_ig_token', '');
	add_option('xyz_smap_pages_ids','-1');
	
	add_option('xyz_smap_twconsumer_secret', '');
	add_option('xyz_smap_twconsumer_id','');
	add_option('xyz_smap_tw_id', '');
	/*$xyz_smap_current_twappln_token= get_option('xyz_smap_current_twappln_token');
	if ($xyz_smap_current_twappln_token!="")
	    add_option('xyz_smap_tw_app_sel_mode','0');//if already publishing using own app
	else*/
	    add_option('xyz_smap_tw_app_sel_mode','0');
	add_option('xyz_smap_current_twappln_token', '');
	add_option('xyz_smap_twpost_permission', '1');
	add_option('xyz_smap_twpost_image_permission', '1');
	add_option('xyz_smap_twaccestok_secret', '');
	add_option('xyz_smap_twmessage', '{POST_TITLE} - {PERMALINK}');
	add_option('xyz_smap_twtr_char_limit',280);
	
	add_option('xyz_smap_tbconsumer_secret', '');
	add_option('xyz_smap_tbconsumer_id','');
	add_option('xyz_smap_tb_id', '');
	add_option('xyz_smap_current_tbappln_token', '');
	add_option('xyz_smap_tbpost_permission', '1');
	add_option('xyz_smap_tbpost_media_permission', '1');///
	add_option('xyz_smap_tbaccestok_secret', '');
	add_option('xyz_smap_tbmessage', '{POST_TITLE} - {PERMALINK}');
	add_option('xyz_smap_tb_future_to_publish', '1');
	add_option('xyz_smap_tbap_post_logs', '');
	add_option('xyz_smap_tb_af', '1');
	add_option('xyz_smap_application_lnarray', '');
	add_option('xyz_smap_ln_shareprivate', '0');
// 	add_option('xyz_smap_ln_sharingmethod', '0');
	add_option('xyz_smap_lnapikey', '');
	add_option('xyz_smap_lnapisecret', '');
// 	add_option('xyz_smap_lnoauth_verifier', '');
// 	add_option('xyz_smap_lnoauth_token', '');
// 	add_option('xyz_smap_lnoauth_secret', '');
	add_option('xyz_smap_lnpost_permission', '1');
	add_option('xyz_smap_lnaf', '1');
	add_option('xyz_smap_lnmessage', '{POST_TITLE} - {PERMALINK}');
	add_option('xyz_smap_std_future_to_publish', '1');
	add_option('xyz_smap_std_apply_filters', '');
	$version=get_option('xyz_smap_free_version');
	$currentversion=xyz_smap_plugin_get_version();
	update_option('xyz_smap_free_version', $currentversion);
	
	add_option('xyz_smap_include_pages', '0');
	add_option('xyz_smap_include_posts', '1');
	add_option('xyz_smap_include_categories', 'All');
	add_option('xyz_smap_include_customposttypes', '');
	
	add_option('xyz_smap_peer_verification', '1');
	add_option('xyz_smap_fbap_post_logs', '');
	add_option('xyz_smap_lnap_post_logs', '');
	add_option('xyz_smap_twap_post_logs', '');
	add_option('xyz_smap_igap_post_logs', '');
	add_option('xyz_smap_premium_version_ads', '1');
	add_option('xyz_smap_default_selection_edit', '0');
	add_option('xyz_smap_default_selection_create', '1');
// 	add_option('xyz_smap_utf_decode_enable', '0');
	add_option('xyz_smap_dnt_shw_notice','0');
	if(get_option('xyz_smap_credit_dismiss') == ""){
		add_option("xyz_smap_credit_dismiss",0);
	}
	add_option('xyz_smap_page_names','');
	add_option('xyz_smap_ig_page_names','');
	add_option('xyz_smap_app_sel_mode','1');
	add_option('xyz_smap_ig_app_sel_mode','1');
	add_option('xyz_smap_ln_company_ids', '');
	$xyz_smap_ln_company_ids_arr=array();
	$xyz_smap_ln_company_ids=get_option('xyz_smap_ln_company_ids');
	if ($xyz_smap_ln_company_ids!='')
		$xyz_smap_ln_company_ids_arr=explode(',', $xyz_smap_ln_company_ids);
	if (in_array('-1', $xyz_smap_ln_company_ids_arr))
		add_option('xyz_smap_lnshare_to_profile', '1');
	else 
		add_option('xyz_smap_lnshare_to_profile', '0');
	add_option('xyz_smap_xyzscripts_user_id','');
	add_option('xyz_smap_xyzscripts_hash_val','');
	add_option('xyz_smap_secret_key','');
	add_option('xyz_smap_smapsoln_userid','0');
	add_option('xyz_smap_lnpost_method',2);
	add_option('xyz_smap_lnappscoped_userid','');
	add_option('xyz_smap_ln_api_permission',2);
	add_option('xyz_smap_smapsoln_userid_ln','0');
	add_option('xyz_smap_smapsoln_userid_tw','0');
	add_option('xyz_smap_smapsoln_userid_ig','0');
	add_option('xyz_smap_secret_key_tw','');
	add_option('xyz_smap_secret_key_ig','');
	add_option('xyz_smap_secret_key_ln','');//54
	add_option('xyz_smap_ln_page_names','');
	add_option('xyz_smap_igapplication_name','');
	add_option('xyz_smap_igapplication_id','');
	add_option('xyz_smap_igapplication_secret','');
	add_option('xyz_smap_igpost_permission','1');
	add_option('xyz_smap_igmessage','{POST_TITLE} - {PERMALINK}');
	add_option('xyz_smap_ig_pages_ids','');
	add_option('xyz_smap_free_enforce_og_tags',1);
	add_option('xyz_smap_clear_fb_cache',0);
	add_option('xyz_smap_ln_signin_method',$linkedin_siwl);
}

register_activation_hook(XYZ_SMAP_PLUGIN_FILE,'smap_free_network_install');
