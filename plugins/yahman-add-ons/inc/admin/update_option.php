<?php
defined( 'ABSPATH' ) || exit;


require_once YAHMAN_ADDONS_DIR . 'inc/admin/option_key.php';

function yahman_addons_update_options(){

	$old_version = get_option('yahman_addons_version');

	if (version_compare($old_version, '0.9.14', '<=')) {
		
		
		yahman_addons_convert_options_0914();
		$old_version = '0.9.15';
	}

	if (version_compare($old_version, '0.9.15', '<=')) {
		
		delete_user_meta( get_current_user_id(), 'yahman_addons_delete_function_notice_dismiss' );
	}

	if (version_compare($old_version, '0.9.23', '<=')) {
		
		yahman_addons_convert_options_0923();
	}


	$default_settings = yahman_addons_option_key();
	$load_setting = get_option('yahman_addons');

	if ( $load_setting ) {

		$load_setting = yahman_addons_merge_option($default_settings, $load_setting);

	}else{
		
		$load_setting = $default_settings;
	}

	update_option( 'yahman_addons', $load_setting );
	update_option( 'yahman_addons_version' , YAHMAN_ADDONS_VERSION );
}



function yahman_addons_merge_option($old_option, $new_option){

	if (is_array($old_option)) {
		if (is_array($new_option)) {
			foreach ($new_option as $key => $value) {
				if (isset($old_option[$key]) && is_array($value) && is_array($old_option[$key])) {
					$old_option[$key] = yahman_addons_merge_option($old_option[$key], $value);
				} else {
					$old_option[$key] = $value;
				}
			}
		}
	} elseif (! is_array($old_option) && ( strlen($old_option) == 0 || $old_option == 0 )) {
		$old_option = $new_option;
	}
	return $old_option;
}

function yahman_addons_convert_options_0914(){

	$load_setting = get_option('yahman_addons');

	if ( $load_setting ) {

		if( isset($load_setting['cta_social']['facebook_script']) ){
			$load_setting['sns_account']['facebook_script'] = $load_setting['cta_social']['facebook_script'];
			update_option( 'yahman_addons', $load_setting );
		}

	}

	return;

}

function yahman_addons_convert_options_0923(){
	$load_setting = get_option('yahman_addons');

	if ( $load_setting ) {
		if( isset($load_setting['amp']['logo']) ){
			$load_setting['json']['logo_image'] = $load_setting['amp']['logo'];
		}
		if( isset($load_setting['amp']['logo_id']) ){
			$load_setting['json']['logo_image_id'] = $load_setting['amp']['logo_id'];
		}
		if( isset($load_setting['amp']) ){
			unset($load_setting['amp']);
		}
		if( isset($load_setting['pwa']['amp_service_worker']) ){
			unset($load_setting['pwa']['amp_service_worker']);
		}
		if( isset($load_setting['pwa']['amp_install_html']) ){
			unset($load_setting['pwa']['amp_install_html']);
		}

		update_option( 'yahman_addons', $load_setting );
	}

	yahman_addons_remove_all_cache_amp();

	return;
}

function yahman_addons_remove_all_cache_amp() {
	$allposts = get_posts( 'numberposts=-1&post_type=any&post_status=any' );
	foreach( $allposts as $postinfo ) {
		yahman_addons_remove_cache_amp( $postinfo->ID );
	}
}

function yahman_addons_remove_cache_amp( $post_ID ) {

	delete_transient( 'ya_amp_cache_' . $post_ID );

	$post_data = get_post($post_ID);

	$count = substr_count( $post_data->post_content , '<!--nextpage-->' );

	if($count === 0) return;

	while($count >= 0){
		delete_transient( 'ya_amp_cache_' . $post_ID . '-' . ($count + 1) );
		--$count;
	}

}