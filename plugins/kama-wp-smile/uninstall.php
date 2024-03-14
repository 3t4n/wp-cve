<?php

if( ! defined('WP_UNINSTALL_PLUGIN') ) exit;

if( ! defined('KWS_PLUGIN_PATH') ){
	define('KWS_PLUGIN_PATH', plugin_dir_path(__FILE__) );

	require_once plugin_dir_path(__FILE__) .'class.Kama_WP_Smiles.php';
}

kws_unistall_init();

function kws_unistall_init(){
	global $wpdb;

	delete_option( 'kwsmile_version' );

	Kama_WP_Smiles::_set_sm_start_end( get_option( Kama_WP_Smiles::OPT_NAME ) );

	// delete options
	delete_option( Kama_WP_Smiles::OPT_NAME );

	// delete smiles in content
	if(1){

		// collect all names from all folders
		$names = array();
		foreach( glob(KWS_PLUGIN_PATH .'packs/*') as $dir ){
			if( is_dir($dir) )
				$names = array_merge( $names, Kama_WP_Smiles::get_dir_smile_names($dir) );
		}
		foreach( glob(untrailingslashit(KWS_PLUGIN_PATH) .'-packs/*') as $dir ){
			if( is_dir($dir) )
				$names = array_merge( $names, Kama_WP_Smiles::get_dir_smile_names($dir) );
		}

		$names = array_unique( $names );

		// split huge number of names into sets by 100
		$all_count = count( $names );
		$split_names = array();
		$start_from = 0; // start
		while(true){
			$split_names[] = array_slice( $names, $start_from, 100 );

			if( $start_from > $all_count )
				break; // stop

			$start_from += 100;
		}


		// delete sets
		$affected_rows = 0;
		foreach( $split_names as $names ){

			// multiple replace query - REPLACE( REPLACE( REPLACE( __FIELDNAME__, '(:acute:)', ''), '(:aggressive:)', ''), '(:air_kiss:)', '')
			$REPLACE_patt = '';
			foreach( $names as $val ){

				if( $val = preg_replace('/[^a-zA-Z0-9_-]/', '', $val ) ){
					$smile_code = Kama_WP_Smiles::$sm_start . $val . Kama_WP_Smiles::$sm_end;
					$REPLACE_patt = str_replace(
						array( '__FIELDNAME__',                                  '__SMILECODE__' ),
						array( ($REPLACE_patt ? $REPLACE_patt : '__FIELDNAME__'), $smile_code ),
						"REPLACE( __FIELDNAME__, '__SMILECODE__', '')"
					);
				}
			}

			if( $REPLACE_patt ){
				$affected_rows += $wpdb->query(
					"UPDATE $wpdb->posts SET post_content = ". str_replace( '__FIELDNAME__', 'post_content', $REPLACE_patt ) ." WHERE post_type NOT IN ('attachment','revision','nav_meny_item')"
				);

				$affected_rows += $wpdb->query(
					"UPDATE $wpdb->comments SET comment_content = ". str_replace( '__FIELDNAME__', 'comment_content', $REPLACE_patt ) .""
				);
			}

		}
	}

	// delete custom created packs - the folder `/wp-content/plugins/kama-wp-smile-packs`
	_kws_delete_folder( untrailingslashit(KWS_PLUGIN_PATH) .'-packs' );
}


/**
 * Удаляет текущую директорию и все файлы и папки в ней, включая скрытые файлы (.extension)...
 * @param string $folder_path Путь до папки которую нужно удалить
 */
function _kws_delete_folder( $folder_path, $delete_self = true ){
	$folder_path = untrailingslashit( $folder_path );

	$glod = glob( "$folder_path/{,.}[!.,!..]*", GLOB_BRACE );
	foreach( $glod as $file ){
		if( is_dir($file) )
			call_user_func( __FUNCTION__, $file );
		else
			unlink( $file );
	}

	if( $delete_self )
		rmdir( $folder_path );
}
