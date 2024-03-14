<?php

defined( 'ABSPATH' ) || die( 'Bye.' );

function init_abp_plugin() {
	$abp_author_id = (int) get_option( 'abp_author_id', 0 );
	$abp_auth_key  = get_option( 'abp_auth_key', '' );

	if ( ! (int) $abp_author_id || empty( $abp_auth_key ) ) {
		return false;
	}

	$abp_cache_time = (int) get_option( 'abp_cache_time', 0 );
	if ( $abp_cache_time == 0 ) {
		update_option( "abp_cache_time", time() );
	}

	if ( time() - ABP_CACHE_TIME < $abp_cache_time ) {
		return false;
	} else {
		update_option( "abp_cache_time", time() );
	}

	if ( ! abp_exist_api() ) {
		return false;
	}

	if ( ! user_can( $abp_author_id, 'publish_posts' ) || ! user_can( $abp_author_id, 'edit_posts' ) ) {
		abp_error_report( $abp_auth_key, 'init_plugin', 'User can\'t publish post access' );

		return false;
	}

	$response     = Requests::post( ABP_MAGENET_API_URL . '/article/get_post', array(), array( 'auth_key' => $abp_auth_key ) );
	$response_log = $response;
	if ( ! isset( $response->body ) || empty( $response->body ) ) {
		return false;
	}

	$response = json_decode( $response->body );
	if ( json_last_error() !== JSON_ERROR_NONE ) {
		abp_error_report( $abp_auth_key, 'init_plugin', 'Json Error post response: ' . $response->body . ', error: ' . json_last_error() . ', log: ' . print_r( $response_log, true ) );

		return false;
	}

	if ( ! $response || ! isset( $response->status ) || (bool) $response->status !== true ) {
		return false;
	}

	if ( ! isset( $response->posts ) || empty( $response->posts ) ) {
		return false;
	}

	foreach ( $response->posts as $post_request ) {
		if ( isset( $post_request->post_status ) ) {
			switch ( $post_request->post_status ) {
				case ABP_STATUS_ADD:
					abp_add_post( $post_request, $abp_auth_key, $abp_author_id );
					break;
				case ABP_STATUS_UPDATE:
					abp_update_post( $post_request, $abp_auth_key, $abp_author_id );
					break;
				case ABP_STATUS_DELETE:
					abp_delete_post( $post_request, $abp_auth_key );
					break;
			}
		}
		abp_clear_cache();
	}
}

function abp_clear_cache() {
	if ( file_exists( WP_PLUGIN_DIR . '/w3-total-cache/w3-total-cache-api.php' ) ) {
		require_once( WP_PLUGIN_DIR . '/w3-total-cache/w3-total-cache-api.php' );
		if ( function_exists( 'w3tc_flush_all' ) ) {
			w3tc_flush_posts();
		}
	}
	if ( function_exists( 'wp_cache_clear_cache' ) ) {
		wp_cache_clear_cache();
	}
	if ( function_exists( 'w3tc_pgcache_flush' ) ) {
		w3tc_pgcache_flush();
	}

	if ( class_exists( "c_ws_plugin__qcache_clearing_routines" ) && method_exists( "c_ws_plugin__qcache_clearing_routines", "ajax_clear" ) ) {
		c_ws_plugin__qcache_clearing_routines::ajax_clear();
	}
}

function abp_get_post( $response ) {
	global $wpdb;

	return $wpdb->get_row( $wpdb->prepare(
		"SELECT post_id
                FROM " . $wpdb->prefix . ABP_TABLE_NAME . "
                WHERE identifier = %d 
                ORDER BY post_id DESC 
                LIMIT 1",
		(int) $response->id
	) );
}

function abp_add_post( $response, $abp_auth_key, $abp_author_id ) {
	global $wpdb;
	$abp_post = abp_get_post( $response );

	$post_category = get_option( 'abp_categories' );

	$post_content         = abp_upload_base64_image( $response->post_content );
	$image_featured_image = $post_content['image_featured_image'];
	$post_content         = $post_content['post_content'];

	$post_data = wp_slash( array(
		'post_title'   => esc_html( wp_strip_all_tags( $response->post_title ) ),
		'post_content' => ! empty( $post_content ) ? $post_content : $response->post_content,
		'post_status'  => 'publish',
		'post_author'  => (int) $abp_author_id,
		'post_excerpt' => wp_trim_words( strip_tags( $response->post_content ) ),
	) );
	if ( $abp_post ) {
		$post_data['ID'] = $abp_post->post_id;
	}
	if ( ! empty( $post_category ) && is_array( $post_category ) ) {
		$post_data['post_category'] = $post_category;
	}

	$post_id = @wp_insert_post( $post_data );
	if ( $post_id ) {
		if ( ! $abp_post ) {
			$result = $wpdb->insert(
				$wpdb->prefix . ABP_TABLE_NAME,
				array(
					'post_id'    => $post_id,
					'identifier' => (int) $response->id,
					'user_id'    => (int) $abp_author_id,
					'created_at' => time()
				),
				array( '%d', '%d', '%d' )
			);
			if ( ! $result ) {
				abp_error_report( $abp_auth_key, 'add_post_internal_table', json_encode( $post_data ) );
			}
		}

		abp_set_featured_image( $post_id, $image_featured_image );

		$post_url = @get_permalink( $post_id );
		Requests::post( ABP_MAGENET_API_URL . '/article/publish_post', array(), array(
				'auth_key'          => $abp_auth_key,
				'id'                => (int) $response->id,
				'post_id'           => $post_id,
				'post_url'          => $post_url,
				'version_plugin'    => ABP_VERSION_PLUGIN,
				'version_wordpress' => ABP_VERSION_WORDPRESS
			)
		);
	} else {
		abp_error_report( $abp_auth_key, 'add_post', json_encode( $post_data ) );
	}
}

function abp_update_post( $response, $abp_auth_key, $abp_author_id ) {
	global $wpdb;
	$abp_post = abp_get_post( $response );

	$post_content         = abp_upload_base64_image( $response->post_content );
	$image_featured_image = $post_content['image_featured_image'];
	$post_content         = $post_content['post_content'];

	$post_data = wp_slash( array(
		'post_title'   => esc_html( wp_strip_all_tags( $response->post_title ) ),
		'post_content' => ! empty( $post_content ) ? $post_content : $response->post_content,
		'post_status'  => 'publish',
		'post_author'  => (int) $abp_author_id,
		'post_excerpt' => wp_trim_words( strip_tags( $response->post_content ) ),
	) );

	if ( (int) $response->post_id > 0 ) {
		$post_data['ID'] = (int) $response->post_id;
	}

	$post_id = @wp_insert_post( $post_data );
	if ( $post_id ) {
		if ( ! $abp_post ) {
			$result = $wpdb->insert(
				$wpdb->prefix . ABP_TABLE_NAME,
				array(
					'post_id'    => (int) $post_id,
					'identifier' => (int) $response->id,
					'user_id'    => (int) $abp_author_id,
					'created_at' => time()
				),
				array( '%d', '%d', '%d' )
			);
			if ( ! $result ) {
				abp_error_report( $abp_auth_key, 'update_post_internal_table', json_encode( $post_data ) );
			}
		} else {
			abp_error_report( $abp_auth_key, 'get_post_internal_table', json_encode( $post_data ) );
		}
		abp_set_featured_image( $post_id, $image_featured_image );

		$post_url = @get_permalink( $post_id );
		$data     = array(
			'auth_key'          => $abp_auth_key,
			'id'                => (int) $response->id,
			'post_id'           => (int) $post_id,
			'post_url'          => $post_url,
			'version_plugin'    => ABP_VERSION_PLUGIN,
			'version_wordpress' => ABP_VERSION_WORDPRESS
		);

		if ( (int) $response->post_id > 0 ) {
			Requests::post( ABP_MAGENET_API_URL . '/article/update_post', array(), $data );
		} else {
			Requests::post( ABP_MAGENET_API_URL . '/article/publish_post', array(), $data );
		}
	} else {
		abp_error_report( $abp_auth_key, 'update_post', json_encode( $post_data ) );
	}
}

function abp_delete_post( $response, $abp_auth_key ) {
	global $wpdb;
	$post_id = @wp_delete_post( (int) $response->post_id );
	if ( $post_id ) {
		$result = $wpdb->delete( $wpdb->prefix . ABP_TABLE_NAME, array( 'post_id' => (int) $response->post_id ), array( '%d' ) );
		if ( $result ) {
			Requests::post( ABP_MAGENET_API_URL . '/article/delete_post', array(), array(
					'auth_key'          => $abp_auth_key,
					'id'                => (int) $response->id,
					'post_id'           => (int) $response->post_id,
					'version_plugin'    => ABP_VERSION_PLUGIN,
					'version_wordpress' => ABP_VERSION_WORDPRESS
				)
			);
		} else {
			abp_error_report( $abp_auth_key, 'delete_post_internal_table', 'Post id: ' . (int) $response->post_id );
		}
	} else {
		abp_error_report( $abp_auth_key, 'delete_post', 'Post id: ' . (int) $response->post_id );
	}
}

function abp_exist_api() {
	$oHttp  = new WP_Http;
	$output = $oHttp->request( ABP_MAGENET_API_URL . '/article' );

	return ! ( $output instanceof WP_Error );
}

function abp_error_report( $abp_auth_key, $category, $error ) {
	Requests::post( ABP_MAGENET_API_URL . '/article/error_report', array(), array(
			'auth_key'          => $abp_auth_key,
			'category'          => $category,
			'version_plugin'    => ABP_VERSION_PLUGIN,
			'version_php'       => ABP_VERSION_PHP,
			'version_wordpress' => ABP_VERSION_WORDPRESS,
			'errors'            => $error
		)
	);
}

function abp_check_image_mime_type( $mime_type ) {
	switch ( $mime_type ) {
		case 'image/jpg':
		case 'image/jpeg':
		case 'image/gif':
		case 'image/png':
		case 'image/bmp':
			return true;
			break;
	}

	return false;
}

function abp_upload_base64_image( $post_content ) {
	preg_match_all( '/<img[^>]+>/i', $post_content, $images );
	$featured_image = array();
	if ( isset( $images[0] ) ) {
		$wordpress_upload_dir = wp_upload_dir();
		foreach ( $images[0] as $image ) {

			$image_info = explode( ';base64,', $image );
			if ( ! isset( $image_info[0], $image_info[1] ) ) {
				continue;
			}

			$img_ext = explode( 'data:image/', $image_info[0] );
			if ( ! isset( $img_ext[1] ) ) {
				continue;
			}

			$img_ext  = $img_ext[1];
			$img_data = base64_decode( $image_info[1] );
			$img_size = strlen( $img_data );

			if ( $img_size > wp_max_upload_size() ) {
				continue;
			}

			if ( ! abp_check_image_mime_type( 'image/' . $img_ext ) ) {
				continue;
			}

			$file_name = time() . '.' . $img_ext;
			$file_name = wp_unique_filename( $wordpress_upload_dir['path'] . '/', $file_name );

			$new_file_image_path = $wordpress_upload_dir['path'] . '/' . $file_name;
			$new_file_image_url  = $wordpress_upload_dir['url'] . '/' . $file_name;

			$res = @file_put_contents( $new_file_image_path, $img_data );
			if ( ! $res ) {
				continue;
			}
			$post_content = str_replace( $image, "<img src='" . $new_file_image_url . "' />", $post_content );

			if ( empty( $featured_image ) ) {
				$featured_image = array(
					'file_name' => $file_name,
					'file_path' => $wordpress_upload_dir['path'],
					'file_url'  => $new_file_image_url
				);
			}
		}
	}

	return array( 'post_content' => $post_content, 'image_featured_image' => $featured_image );
}

function abp_set_featured_image( $post_id, $featured_image ) {
	if ( ! empty( $featured_image ) && isset( $featured_image['file_name'], $featured_image['file_path'] ) ) {
		$wp_file_type = wp_check_filetype( $featured_image['file_path'] . '/' . $featured_image['file_name'], null );
		$attachment   = array(
			'guid'           => $featured_image['file_url'],
			'post_mime_type' => $wp_file_type['type'],
			'post_parent'    => $post_id,
			'post_title'     => preg_replace( '/\.[^.]+$/', '', $featured_image['file_name'] ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		$attachment_id = wp_insert_attachment( $attachment, $featured_image['file_path'] . '/' . $featured_image['file_name'], $post_id );

		if ( ! is_wp_error( $attachment_id ) ) {
			require_once( ABSPATH . 'wp-admin' . '/includes/image.php' );

			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $featured_image['file_path'] . '/' . $featured_image['file_name'] );

			wp_update_attachment_metadata( $attachment_id, $attachment_data );
			set_post_thumbnail( $post_id, $attachment_id );
		}
	}
}

add_action( 'wp_loaded', 'init_abp_plugin' );

function abp_action_callback()
{
    echo (!get_option("abp_auth_key") ? 1 : 2);
    wp_die();
}
add_action('wp_ajax_abp_action', 'abp_action_callback');

function abp_admin_load_scripts()
{
    wp_register_script('magenetAbpAdminJs', trailingslashit(WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__))) . 'js/admin-scripts.js', array('jquery-ui-dialog'));
    wp_enqueue_script('magenetAbpAdminJs');
}

add_action('admin_enqueue_scripts', 'abp_admin_load_scripts');

function admin_load_styles()
{
    wp_register_style('magenetAbpAdminCss', trailingslashit(WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__))) . 'css/admin-style.css?v1');
    wp_enqueue_style('magenetAbpAdminCss');
    wp_enqueue_style('wp-jquery-ui-dialog');
}

add_action('admin_enqueue_scripts', 'admin_load_styles');

function magenet_abp_notices() {
    if (strstr($_SERVER['SCRIPT_NAME'], 'plugins.php')) {
        include 'admin/popup.php';
    }
}

add_action('admin_notices', 'magenet_abp_notices');