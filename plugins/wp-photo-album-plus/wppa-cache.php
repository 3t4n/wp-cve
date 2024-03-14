<?php
/* wppa-cache.php
/*
/* Contains all wppa smart cache functions
/*
/* Version 8.6.02.003
*/

// Test for caching
//
// Used for shortcodes.
// Returns array( 'caching' 	=> BOOL, indicating if current shortcode will be cached
//				  'cache_id' 	=> STRING, indicates the page and the occur, the login/out/admin, language  etc
//				  'cache_file'	=> STRING, pathname to the cache file
//				  'cache_data' 	=> STRING, content of the cachefile
//				 );
function wppa_test_for_caching( $yes_no_only = false ) {
global $wppa_lang;

	// Assume not
	$caching 	= false;
	$cache_id 	= false;
	$cache_file = false;
	$cache_data = false;

	if ( wppa_get( 'cache' ) ) {
		wppa( 'cache', wppa_get( 'cache' ) );
	}

	if ( wppa( 'cache' ) ) {

		// No querystring?
		if ( ! $_SERVER['REQUEST_URI'] ) {
			$caching = true;
		}

		// Querystring not for this occur?
		elseif ( ! wppa_in_widget() && wppa( 'mocc' ) != wppa_get( 'occur' ) ) {
			$caching = true;
		}

		// Ajax?
		elseif ( defined( 'DOING_AJAX' ) && wppa_get( 'cache' ) ) {
			$caching = true;
		}

		// Content for delayed sc?
		elseif ( wppa_get( 'wppa-action', '', 'text' ) == 'getshortcodedrenderedfenodelay' ) {
			$caching = true;
		}

		if ( $yes_no_only ) {
			return $caching;
		}

		if ( $caching ) {

			$cache_id = get_permalink() . '-' . wppa( 'mocc' );
			if ( wppa( 'ajax' ) ) {
				$cache_id .= $_SERVER['QUERY_STRING'];
			}

			$cache_id = md5($cache_id);
			$root = WPPA_CONTENT_PATH . '/' . wppa_opt( 'cache_root' ) .'/wppa-shortcode';
			if ( ! wppa_is_dir ( $root ) ) {
				wppa_mktree( $root );
			}

			$login = ( is_user_logged_in() ? 'log-' : '' );
			if ( $login && ( wppa_switch( 'user_upload_on' ) ) ) {
				$login .= get_current_user_id() . '-';
			}

			$roles = '';
			if ( wppa_user_is_admin() || ( is_user_logged_in() && ( wppa_switch( 'user_create_on' ) || wppa_opt( 'user_opload_roles' ) || wppa_opt( 'user_comment_roles' ) ) ) ) {
				$user = wp_get_current_user();
				$roles = implode( '-', $user->roles ) . '-';
			}

			$mob = wppa_is_mobile() ? 'M' : '';

			$cache_file =
				$root . '/' .
				$wppa_lang . '-' .
				$mob . '-' .
				$login .
				$roles .
				$cache_id;

			// Cachefile present?
			if ( wppa_is_file( $cache_file ) ) {
				$timer = wppa_occur_timer( 'show', $_SERVER['REQUEST_URI'] . ' oc ' . wppa( 'mocc' ), true );
				wppa_log( 'fso', 'Reading cachefile ' . str_replace( WPPA_CONTENT_PATH, '...', $cache_file ) );

				$cache_data = wppa_get_contents( $cache_file ) . $timer;
				wppa_update_option( 'wppa_cache_hits', wppa_get_option( 'wppa_cache_hits', 0 ) +1 );
			}
		}
	}

	$result = array( 'caching' 		=> $caching,
					 'cache_id' 	=> $cache_id,
					 'cache_file'	=> $cache_file,
					 'cache_data' 	=> $cache_data,
					 );

	return $result;
}

// Save cache file
//
// Args array( 'file' 		=> STRING, pathname to cachefile
// 	 	 	   'data' 		=> STRING, content of the cachefile
// 			   'albums' 	=> STRING, enumeration of album ids, OR '*' indicating all albums,
// 			   'photos'		=> STRING, enumeration of photo ids, OR '*' indicating all photos,
// 			   'other' 		=> CHAR, 'C' for comment, 'R' for rating, default ''
//			 );
// to indicate on what changes the cachefile should be purged. This info (cache metadata) is stored in $wpdb->wppa_caches
function wppa_save_cache_file( $xargs ) {
global $wppa_current_shortcode;
global $albums_used;
global $photos_used;
global $other_deps;
global $wpdb;

	$page = '';

	if ( ! wppa( 'in_widget' ) ) {
		if ( defined( 'DOING_AJAX' ) ) {
			$page = wppa_get( 'fromp', '' );
		}
		else {
			$page = get_the_ID();
		}
	}

	// Fill in defaults
	$defaults = array( 'file'	=> '',
					   'data' 	=> '',
					   'albums' => $albums_used,
					   'photos' => $photos_used,
					   'other' 	=> $other_deps,
					   'remark' => $wppa_current_shortcode,
					   'page' 	=> $page,
					   );
	$args = wp_parse_args( (array) $xargs, $defaults );

	// Extract vars
	$file 	= $args['file'];
	$data 	= $args['data'];
	$albums = $args['albums'];
	$photos = $args['photos'];
	$other 	= $args['other'];
	$remark = $args['remark'];
	$page 	= $args['page'];

	// Cleanup cache
	$maxfiles = wppa_opt( 'cache_maxfiles' ); 	// 0 is unlimited
	if ( $maxfiles ) {
		if ( wppa_is_dir( dirname( $file ) ) ) {
			$caches = wppa_glob( dirname( $file ) . '/*', WPPA_ONLYFILES );
			if ( ! empty( $caches ) ) {
				$count = count( $caches );
				if ( $count > $maxfiles ) {

					// delete all files older than 300 seconds
					foreach( $caches as $cache ) {
						if ( wppa_filetime( $cache ) < ( time() - 300 ) ) {
							wppa_unlink( $cache );
							$iret = $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->wppa_caches WHERE filename = %s", str_replace( WPPA_CONTENT_PATH, '...', $cache ) ) );
						}
					}
				}
			}
		}
	}

	// Check for missing refs
	if ( ! $albums && ! $photos && ! $other ) {

		// Last resort: assume generic or separate toplevel
		$albs = $wpdb->get_col( "SELECT id FROM $wpdb->wppa_albums WHERE a_parent < 1 ORDER BY id" );
		$albums = implode( '.', $albs );
	}
	if ( ! $page ) {
		$page = wppa_get( 'fromp', '' );
	}

	// Sanitize input
	if ( $albums != '*' ) {

		if ( ! $albums || substr( $albums, 0, 1 ) == '$' ) {
			$albums = wppa( 'start_album' );
		}

		// Remove dups, remove non-nueric album indicators
		$albums = explode( '.', $albums );
		foreach( array_keys( $albums ) as $key ) {
			if ( ! wppa_is_posint( $albums[$key] ) ) {
				unset( $albums[$key] );
			}
		}
		$albums = array_unique( $albums, SORT_NUMERIC );
		$albums = implode( '.', $albums );
		$albums = trim( $albums, '.' );
	}

	if ( $photos != '*' ) {

		if ( ! $photos ) {
			$photos = wppa( 'start_photo' ) . '.' . wppa_expand_enum( wppa( 'start_photos' ) );
		}

		// Remove dups
		$photos = explode( '.', $photos );
		$photos = array_unique( $photos, SORT_NUMERIC );
		$photos = implode( '.', $photos );
		$photos = trim( $photos, '.' );
	}

	if ( $other != 'C' && $other != 'R' ) {
		$other = '';
	}

	// Log making cachefile
	$extra = '';
	if ( $remark ) {
		$extra = ' for {b}' . $remark . '{/b}. ';
	}
	wppa_log( 'fso', 'Writring cachefile ' . str_replace( WPPA_CONTENT_PATH, '...', $file ) . $extra );

	// Save it
	wppa_put_contents( $file, $data );

	// Register cache miss
	wppa_update_option( 'wppa_cache_misses', wppa_get_option( 'wppa_cache_misses', 0 ) +1 );

	// Save used albums and photos
	wppa_create_cache_entry( array( 'filename' 	=> str_replace( WPPA_CONTENT_PATH, '...', $file ),
									'albums' 	=> $albums,
									'photos' 	=> $photos,
									'other' 	=> $other,
									'page' 		=> $page,
									) );
	// Cleanup
	$albums_used = '';
	$photos_used = '';
	$other_deps  = '';
}

// Clear cache
//
// Args array( 'album' 		=> INT album id, will delete all caches that contain indicated album
//			   'photo' 		=> INT photo id, will delete all caches that contain indicated photo
//			   'force' 		=> BOOL if true, will delete all caches
//			   'shortcodes' => BOOL if true, will delete all shortcode caches
//			   'widgets' 	=> BOOL if true, will delete all widget caches
//			   'albums' 	=> BOOL if true, will delete all caches that contain any album
//			   'photos' 	=> BOOL if true, will delete all caches that contain any photo
//			   'qr' 		=> BOOL if true, will delete all qr code caches
// 			   'other' 		=> CHAR either '', 'C', or 'R', will delete all caches that contain Comments or Ratings
//			 );
//
function wppa_clear_cache( $args = array() ) {
global $cache_path;
global $wpdb;

	// Fill in default args
	$defaults = array( 	'album' 		=> '',
						'photo' 		=> '',
						'force' 		=> false,
						'shortcodes' 	=> false,
						'widgets' 		=> false,
						'albums' 		=> false,
						'photos' 		=> false,
						'qr' 			=> false,
						'page' 			=> '',
						'other' 		=> '',
						);
	$args = wp_parse_args( (array) $args, $defaults );

	$album 		= $args['album'];
	$photo 		= $args['photo'];
	$force 		= $args['force'];
	$shortcodes = $args['shortcodes'] || $force;
	$widgets 	= $args['widgets'] || $force;
	$albums 	= $args['albums'] || $force;
	$photos 	= $args['photos'] || $force;
	$qr 		= $args['qr'] || $force;
	$page 		= $args['page'];
	$other 		= $args['other'];

	// Album based
	if ( $album != '' ) {

		$query = "SELECT filename FROM $wpdb->wppa_caches WHERE albums LIKE '%.$album.%' OR albums = '.*.'";
		$files = $wpdb->get_col( $query );
		foreach( $files as $file ) {
			$path = str_replace( '...', WPPA_CONTENT_PATH, $file );
			if ( wppa_is_file( $path ) ) {
				wppa_unlink( $path, true );
			}
		}
		$wpdb->query( "DELETE FROM $wpdb->wppa_caches WHERE albums LIKE '%.$album.%' OR albums = '.*.'" );
	}

	// Photo based
	if ( $photo != '' ) {

		$query = "SELECT filename FROM $wpdb->wppa_caches WHERE photos LIKE '%.$photo.%' OR photos = '.*.'";
		$files = $wpdb->get_col( $query );
		foreach( $files as $file ) {
			$path = str_replace( '...', WPPA_CONTENT_PATH, $file );
			if ( wppa_is_file( $path ) ) {
				wppa_unlink( $path, true );
			}
		}
		$wpdb->query( "DELETE FROM $wpdb->wppa_caches WHERE photos LIKE '%.$photo.%' OR photos = '.*.'" );
	}

	// All albums
	if ( $albums ) {

		$query = "SELECT filename FROM $wpdb->wppa_caches WHERE albums <> '..'";
		$files = $wpdb->get_col( $query );
		foreach( $files as $file ) {
			$path = str_replace( '...', WPPA_CONTENT_PATH, $file );
			if ( wppa_is_file( $path ) ) {
				wppa_unlink( $path, true );
			}
		}
		$wpdb->query( "DELETE FROM $wpdb->wppa_caches WHERE albums <> '..'" );
	}

	// All photos
	if ( $photos ) {

		$query = "SELECT filename FROM $wpdb->wppa_caches WHERE photos <> '..'";
		$files = $wpdb->get_col( $query );
		foreach( $files as $file ) {
			$path = str_replace( '...', WPPA_CONTENT_PATH, $file );
			if ( wppa_is_file( $path ) ) {
				wppa_unlink( $path, true );
			}
		}
		$wpdb->query( "DELETE FROM $wpdb->wppa_caches WHERE photos <> '..'" );
	}

	// Page based
	if ( $page ) {

		$p = strval( intval( $page ) );
		$query = "SELECT filename FROM $wpdb->wppa_caches WHERE page = $p";
		$files = $wpdb->get_col( $query );
		foreach( $files as $file ) {
			$path = str_replace( '...', WPPA_CONTENT_PATH, $file );
			if ( wppa_is_file( $path ) ) {
				wppa_unlink( $path, true );
			}
		}
		$wpdb->query( "DELETE FROM $wpdb->wppa_caches WHERE page = $p" );
	}

	// Clear all shortcode caches
	if ( $shortcodes ) {

		$root = WPPA_CONTENT_PATH . '/' . wppa_opt( 'cache_root' ) . '/wppa-shortcode';
		if ( wppa_is_dir( $root ) ) {
			wppa_tree_empty( $root );
		}
		$wpdb->query( "DELETE FROM $wpdb->wppa_caches WHERE filename LIKE '%/wppa-shortcode/%'" );
		wppa_log( 'fso', 'All wppa shortcode caches cleared' );
	}

	// Clear all widget caches
	if ( $widgets ) {

		$root = WPPA_CONTENT_PATH . '/' . wppa_opt( 'cache_root' ) . '/wppa-widget';
		if ( wppa_is_dir( $root ) ) {
			wppa_tree_empty( $root );
		}
		$wpdb->query( "DELETE FROM $wpdb->wppa_caches WHERE filename LIKE '%/wppa-widget/%'" );
		wppa_log( 'fso', 'All wppa widget caches cleared' );
	}

	// Clear Other caches
	if ( $other ) {

		$query = "SELECT filename FROM $wpdb->wppa_caches WHERE other = '$other'";
		$files = $wpdb->get_col( $query );
		foreach( $files as $file ) {
			$path = str_replace( '...', WPPA_CONTENT_PATH, $file );
			if ( wppa_is_file( $path ) ) {
				wppa_unlink( $path, true );
			}
		}
		$wpdb->query( "DELETE FROM $wpdb->wppa_caches WHERE other = '$other'" );

	}

	// Always clear non-wppa cache
	$force = true;

	// Cleanup tempfiles and clear non wppa caches
	if ( $force ) {

		// Remove tempfiles
		wppa_delete_obsolete_tempfiles();

		// If wp-super-cache is on board, clear cache
		if ( function_exists( 'prune_super_cache' ) ) {
			prune_super_cache( $cache_path . 'supercache/', true );
			prune_super_cache( $cache_path, true );
		}

		// W3 Total cache
		if ( function_exists( 'w3tc_pgcache_flush' ) ) {
			w3tc_pgcache_flush();
		}

		// SG_CachePress
		if ( class_exists( 'SG_CachePress_Supercacher' ) ) {
			$c = new SG_CachePress_Supercacher();
			if ( $c->purge_cache ) {
				$c->purge_cache();
			}
		}

		// Quick cache
		if ( isset($GLOBALS['quick_cache']) ) {
			$GLOBALS['quick_cache']->clear_cache();
		}

		// Comet cache
		if ( class_exists( 'comet_cache' ) ) {
			comet_cache::clear();
		}

		// WP Optimize cache
		if ( class_exists( 'WP_Optimize' ) ) {
			WP_Optimize()->get_page_cache()->purge();
		}
	}
}

// Get path to widget cache file
function wppa_get_widget_cache_path( $widget_id ) {
global $wppa_lang;

	$root = WPPA_CONTENT_PATH . '/' . wppa_opt( 'cache_root' ) . '/wppa-widget';

	if ( ! wppa_is_dir( $root ) ) {
		wppa_mktree( $root );
	}

	$login = ( is_user_logged_in() ? 'log-' : '' );
	if ( $login && ( wppa_switch( 'user_upload_on' ) ) ) {
		$login .= get_current_user_id() . '-';
	}

	$roles = '';
	if ( wppa_user_is_admin() || ( is_user_logged_in() && wppa_switch( 'user_create_on' ) ) ) {
		$user = wp_get_current_user();
		$roles = implode( '-', $user->roles ) . '-';
	}

	$result =
		$root . '/' .
		$wppa_lang . '-' .
		$login .
		$roles .
		$widget_id;

	return $result;
}

// Remove cache files for a certrain widget
function wppa_remove_widget_cache( $widget_id ) {
global $wpdb;

	if ( ! $widget_id ) return;

	$files = $wpdb->get_col( "SELECT filename FROM $wpdb->wppa_caches WHERE filename LIKE '%$widget_id%'" );
	foreach ( $files as $file ) {
		$path = str_replace( '...', WPPA_CONTENT_PATH, $file );
		if ( wppa_is_file( $path ) ) {
			wppa_unlink( $path, true );
		}
	}
	$wpdb->query( "DELETE FROM $wpdb->wppa_caches WHERE filename LIKE '%$widget_id%'" );
}

// Cache admin page
function wppa_page_cache() {
global $wpdb;

	if ( ! current_user_can( 'administrator' ) && ! current_user_can( 'edit_posts' ) ) {
		wp_die( 'You have no rights to do this' );
	}

	$sc_files = array();
	$root = WPPA_CONTENT_PATH . '/' . wppa_opt( 'cache_root' ) . '/wppa-shortcode';
	if ( wppa_is_dir( $root ) ) {
		$sc_files = wppa_glob( $root . '/*' );
	}

	$wg_files = array();
	$root = WPPA_CONTENT_PATH . '/' . wppa_opt( 'cache_root' ) . '/wppa-widget';
	if ( wppa_is_dir( $root ) ) {
		$wg_files = wppa_glob( $root . '/*' );
	}

	$files = array_merge( $sc_files, $wg_files );
	$count = count( $files );
	if ( $count ) {
		$result = '
		<div class="wrap" >
			<h1>' . get_admin_page_title() . '</h1>
			<table class="wppa-table widefat wppa-setting-table striped" style="margin-top:12px">
				<thead style="font-weight:bold">
					<tr>
						<td colspan="3" ><h3 style="margin:0.1em">' . __( 'File data', 'wp-photo-album-plus' ) . '</h3></td>
						<td colspan="4" style="border-left:1px solid #c3c4c7"><h3 style="margin:0.1em">' . __( 'Dependencies', 'wp-photo-album-plus' ) . '</h3></td>
					</tr>
					<tr>
						<td><b>' . __( 'Name', 'wp-photo-album-plus' ) . '</b></td>
						<td><b>' . __( 'Size', 'wp-photo-album-plus' ) . '</b></td>
						<td><b>' . __( 'Age', 'wp-photo-album-plus' ) . '</b></td>
						<td style="border-left:1px solid #c3c4c7"><b>' . __( 'Albums', 'wp-photo-album-plus' ) . '</b></td>
						<td><b>' . __( 'Photos', 'wp-photo-album-plus' ) . '</b></td>
						<td><b>' . __( 'Page', 'wp-photo-album-plus' ) . '</b></td>
						<td><b>' . __( 'Other', 'wp-photo-album-plus' ) . '</b></td>
					</tr>
				</thead>
				<tbody>';
				foreach( $files as $file ) {
					$pfile 	= '...' . str_replace( dirname( dirname( dirname( dirname( $file ) ) ) ), '', $file );

					$size 	= wppa_filesize( $file );
					if ( $size > 1024*1024 ) {
						$size = sprintf( '%5.1fMb', $size / ( 1024 * 1024 ) );
					}
					elseif ( $size > 1024 ) {
						$size = sprintf( '%5.1fkb', $size / 1024 );
					}
					else {
						$size = $size . 'bytes';
					}

					$a = time() - wppa_filetime( $file );
					$d = floor( $a / ( 24 * 3600 ) ) ;
					$a -= $d * 24 * 3600;
					$h = floor( $a / 3600 );
					$a -= $h * 3600;
					$m = floor( $a / 60 );
					$s = $a - $m * 60;
					$age 	= sprintf( '%2dd %2dh %2dm %2ds', $d, $h, $m, $s );
					if ( wppa_get( 'delete' ) ) {
						wppa_unlink( $file, false );
					}
					$meta = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_caches
															 where filename = %s",
															 str_replace( WPPA_CONTENT_PATH, '...', $file ) ), ARRAY_A );
					if ( ! $meta ) {
						$meta['albums'] = '';
						$meta['photos'] = '';
						$meta['other'] = '';
						$meta['page'] = '';
					}
					else {
						$meta['albums'] = wppa_cache_display_format( $meta['albums'] );
						if ( $meta['albums'] == '*' ) $meta['albums'] = __( 'All', 'wp-photo-album-plus' );
						$meta['photos'] = wppa_cache_display_format( $meta['photos'] );
						if ( $meta['photos'] == '*' ) $meta['photos'] = __( 'All', 'wp-photo-album-plus' );
						if ( $meta['other'] == 'R' ) $meta['other'] = __( 'Any rating', 'wp-photo-album-plus' );
						if ( $meta['other'] == 'C' ) $meta['other'] = __( 'Any comment', 'wp-photo-album-plus' );
						if ( $meta['page'] == '0' ) $meta['page'] = '';
					}
					$result .= '
					<tr>
						<td style="width:600px">' . $pfile . '</td>
						<td style="width:75px">' . $size . '</td>
						<td style="width:100px">' . $age . '</td>
						<td style="border-left:1px solid #c3c4c7">' . $meta['albums'] . '</td>
						<td>' . $meta['photos'] . '</td>
						<td>' . $meta['page'] . '</td>
						<td>' . $meta['other'] . '</td>
					</tr>';
				}
				$result .= '
				</tbody>
			</table>';

			if ( wppa_get( 'delete' ) ) {
				wppa_clear_cache( ['force' => true] );
				$result .= '<br><b>' . sprintf( __( '%d cachefiles deleted', 'wp-photo-album-plus' ), $count ) . '</br>';
			}
			else {
				$hits = wppa_get_option( 'wppa_cache_hits', '0' );
				$miss = wppa_get_option( 'wppa_cache_misses', '1' );
				$perc = sprintf( '%5.2f', 100 * $hits / ( $hits + $miss ) );
				$result .= '
					<p>' .
					__( 'Caching is \'smart\'. This means that cache files are cleared when the display of a wppa widget or shortcode will change due to adding albums, photos comments or ratings.', 'wp-photo-album-plus' ) . '
					<br>' .
					__( 'You will need to clear the cachefiles only when you change the layout outside the WPPA settings, i.e. change theme or custom CSS.', 'wp-photo-album-plus' ) . '
					</p>

					<input
						type="button"
						class="button-primary"
						onclick="document.location.href=\'' . admin_url( 'admin.php?page=wppa_cache&delete=1' ) . '\'"
						value="' . __( 'Clear cache', 'wp-photo-album-plus' ) . '"
					/><br>

					<p>' .
					sprintf(
					__( 'Since last install / update of the plugin, there were %d cache hits, %d cache misses, i.e. a hitrate of %5.2f%%', 'wp-photo-album-plus' ),
					$hits, $miss, $perc ) .
					'</p>';
			}
		$result .= '
		</div>';
	}
	else {
		$result = '
		<div class="wrap" >' .
			__( 'No cachefiles to remove.', 'wp-photo-album-plus' ) . '
		</div>';
	}
	wppa_echo( $result );
}

function wppa_cache_display_format( $text ) {

	$text = trim( $text, '.' );
	if ( ! wppa_is_enum( $text ) ) {
		return $text;
	}

	$text = wppa_compress_enum( $text );
	$text = str_replace( '..', '-', $text );
	$text = str_replace( '.', ' ', $text );
	$text = str_replace( '-', '..', $text );
	return $text;
}