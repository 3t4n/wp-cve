<?php
/* Handle Access to User files */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

if(!is_user_logged_in()){
	status_header(403);
	die('403 &#8212; Not allowed.');
}

list($basedir) = array_values(array_intersect_key(wp_upload_dir(), array('basedir' => 1)))+array(NULL);
$basedir .= '/upf-docs';
$file =  rtrim($basedir,'/').'/'.str_replace('..', '', isset($_GET[ 'file' ])?sanitize_text_field($_GET[ 'file' ]):'');
if (!$basedir || !is_file($file)) {
	status_header(404);
	die('404 &#8212; File not found.');
}

if(isset($_GET[ 'file' ])){
	$private_file = 'upf-docs/' . sanitize_text_field($_GET[ 'file' ]);
	$file_raw_name = sanitize_text_field($_GET[ 'file' ]);
}
$allowed = $doc_id = 0;
$curr_user_id = get_current_user_id();

// allow access to admin users
if(user_can( $curr_user_id, 'administrator' )){
	$allowed = 1;
} else{
	// if file-author or allowed-user is viewing the file
	$the_query = new WP_Query( array( 'post_type' => 'attachment', 'post_status' => array('inherit', 'trash'), 'meta_key' => '_wp_attached_file', 'meta_value' => $private_file ) );
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$doc_id = get_the_ID();
			$doc_author = get_the_author_meta("ID");
			if($curr_user_id == $doc_author){
				$allowed = 1;
			}
			else{
				$upf_allowed_users = get_post_meta($doc_id, 'upf_allowed', true);
				if($upf_allowed_users){
					if(in_array($curr_user_id, $upf_allowed_users)){
						$allowed = 1;
					}
				}
			}
			
		}
	}
	wp_reset_query();

	// If doc is image and not original but different size
	if(!$doc_id){
		$args = array(
			'post_type' => 'attachment',
			'post_status' => array('inherit', 'trash'),
			'meta_query' => array(
				array(
					'key' => '_wp_attachment_metadata',
					'value' => $file_raw_name,
					'compare' => 'LIKE'
				)
			)
		);
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$doc_id = get_the_ID();
				$doc_author = get_the_author_meta("ID");
				if($curr_user_id == $doc_author){
					$allowed = 1;
				}
				else{
					$upf_allowed_users = get_post_meta($doc_id, 'upf_allowed', true);
					if($upf_allowed_users){
						if(in_array($curr_user_id, $upf_allowed_users)){
							$allowed = 1;
						}
					}
				}
			}
		}
		wp_reset_query();
	}
}

if(!$allowed){
	status_header(403);
	die('403 &#8212; You do not have Permission to view this file.');
}
	
$mime = wp_check_filetype($file);
if( false === $mime[ 'type' ] && function_exists( 'mime_content_type' ) )
	$mime[ 'type' ] = mime_content_type( $file );

if( $mime[ 'type' ] )
	$mimetype = $mime[ 'type' ];
else
	$mimetype = 'image/' . substr( $file, strrpos( $file, '.' ) + 1 );

header( 'Content-Type: ' . $mimetype ); // always send this
if ( false === strpos( $_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS' ) )
	header( 'Content-Length: ' . filesize( $file ) );

$last_modified = gmdate( 'D, d M Y H:i:s', filemtime( $file ) );
$etag = '"' . md5( $last_modified ) . '"';
header( "Last-Modified: $last_modified GMT" );
header( 'ETag: ' . $etag );
header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 100000000 ) . ' GMT' );

// Support for Conditional GET
$client_etag = isset( $_SERVER['HTTP_IF_NONE_MATCH'] ) ? stripslashes( sanitize_text_field( $_SERVER['HTTP_IF_NONE_MATCH'] ) ) : false;

if( ! isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) )
	$_SERVER['HTTP_IF_MODIFIED_SINCE'] = false;

$client_last_modified = trim( rest_sanitize_boolean( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) );

// If string is empty, return 0. If not, attempt to parse into a timestamp
$client_modified_timestamp = $client_last_modified ? strtotime( $client_last_modified ) : 0;

// Make a timestamp for our most recent modification...
$modified_timestamp = strtotime($last_modified);

if ( ( $client_last_modified && $client_etag )
	? ( ( $client_modified_timestamp >= $modified_timestamp) && ( $client_etag == $etag ) )
	: ( ( $client_modified_timestamp >= $modified_timestamp) || ( $client_etag == $etag ) )
	) {
	status_header( 304 );
	exit;
}

// If we made it this far, just serve the file
readfile( $file );
exit;