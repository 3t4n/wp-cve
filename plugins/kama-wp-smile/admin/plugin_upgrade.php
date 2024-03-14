<?php

function ksw_version_upgrade(){
	global $wpdb;

	$kws_ver = get_option( 'kwsmile_version' );

	_ksw_version_fast_upgrade(); // обновление невзирая на версию - быстрая проверка

	if( $kws_ver === KWS_VER || ! $kws_ver ){
		return;
	}

	update_option( 'kwsmile_version', KWS_VER );

	$KWS = kwsmile();

	// 1.8.0
	if( version_compare( $kws_ver, '1.8.0', '<' ) ){
		// Обновим все смайлики
		foreach( $KWS->get_dir_smile_names() as $val ){
			$val = $wpdb->esc_sql( addslashes( $val ) );
			if( $val ){
				$old_sm_code = "*$val*";
				$new_sm_code = $KWS::$sm_start . $val . $KWS::$sm_end;
				$wpdb->query( "UPDATE $wpdb->posts SET post_content = REPLACE(post_content, '$old_sm_code', '$new_sm_code') WHERE post_type NOT IN ('attachment','revision')" );
				$wpdb->query( "UPDATE $wpdb->comments SET comment_content = REPLACE(comment_content, '$old_sm_code', '$new_sm_code')" );
			}
		}
	}
}

function _ksw_version_fast_upgrade(){
	$KWS = kwsmile();

	// rename option name
	if( $KWS::OPT_NAME !== 'wp_sm_opt' && false !== get_option( 'wp_sm_opt' ) ){
		$opt = get_option( 'wp_sm_opt' );
		delete_option( 'wp_sm_opt' );
		$KWS->opt = $opt;
		update_option( $KWS::OPT_NAME, $opt );
	}

	if( ! $KWS->get_opt( 'all_sm' ) && $KWS->get_opt( 'exist' ) ){
		// изменим название опции 'exist' на 'all_sm'
		$KWS->opt['all_sm'] = $KWS->opt['exist'];
		unset( $KWS->opt['exist'] );
		update_option( $KWS::OPT_NAME, $KWS->opt );
	}
}
