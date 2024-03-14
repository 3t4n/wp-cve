<?php
/*************************************************************************
Plugin Name:  Unattach
Plugin URI:   http://outlandishideas.co.uk/blog/2011/03/unattach/
Description:  Allows detaching images and other media from posts, pages and other content types.
Version:      1.2.2
Author:       tamlyn
**************************************************************************/

//filter to add button to media library UI
function unattach_media_row_action( $actions, $post ) {
	// only add action if user can edit media and parent
	if ( current_user_can( 'edit_post', $post->ID ) && $post->post_parent && current_user_can( 'edit_post', $post->post_parent ) ) {
		$url = wp_nonce_url( admin_url('options.php?page=unattach&noheader=true&id=' . $post->ID ), 'unattach' );

		//add current page and searchterm as param
		if ( !empty( $_REQUEST['paged'] ) ) {
			$pagenum = (int)$_REQUEST['paged'];
			if ( $pagenum > 1 ) {
				$url = add_query_arg( 'paged', $pagenum, $url );
			}
		}
		if ( !empty( $_REQUEST['s'] ) ) {
			$url = add_query_arg( 's', $_REQUEST['s'], $url );
		}
        
		$actions['unattach'] = '<a href="' . esc_url( $url ) . '" title="' . __( 'Unattach this media item.', 'unattach' ) . '">' . __( 'Unattach', 'unattach' ) . '</a>';
	}

	return $actions;
}

//action to set post_parent to 0 on attachment
function unattach_do_it() {
	check_admin_referer( 'unattach' );

	global $wpdb;
	if ( !empty($_REQUEST['id'] ) ) {
		$id = (int)$_REQUEST['id'];
		if ( current_user_can( 'edit_post', $id ) ) {
			$wpdb->update($wpdb->posts, array('post_parent'=>0), array('id'=>$id, 'post_type'=>'attachment'));
		}
	}

    //construct return URL
	$url = 'upload.php';
	if ( !empty( $_REQUEST['paged'] ) ) {
		$pagenum = (int)$_REQUEST['paged'];
		if ( $pagenum > 1 ) {
			$url = add_query_arg( 'paged', $pagenum, $url );
		}
	}
	if ( !empty( $_REQUEST['s'] ) ) {
		$url = add_query_arg( 's', $_REQUEST['s'], $url );
	}

	wp_redirect( admin_url( $url ) );
	exit;
}

//set it up
add_action( 'admin_menu', 'unattach_init' );
function unattach_init() {
	$capability = apply_filters( 'unattach_capability', 'upload_files' );
	if ( current_user_can( $capability ) ) {
		add_filter('media_row_actions',  'unattach_media_row_action', 10, 2);
		// 1.0.3: use null instead tools.php
		add_submenu_page( null, 'Unattach', 'Unattach', $capability, 'unattach', 'unattach_do_it' );
	}
}

/*
 * Example to restrict access to administrators. Put this in functions.php:
 *
 * add_filter( 'unattach_capability', function( $capability ) {
 *    return 'administrator';
 * });
 */

// load translations
function unattach_load_textdomain() {
	load_plugin_textdomain( 'unattach', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' ); 
}
add_action( 'plugins_loaded', 'unattach_load_textdomain' );