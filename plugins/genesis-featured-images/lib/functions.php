<?php

/**
 * Obtains the attachment id from url
 * 
 */ 
function get_attachment_id_from_url ( $image_url ) {

	global $wpdb;
	
	$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='%s'";
	$id = $wpdb->get_var( $wpdb->prepare( $query, $image_url ) );
	
	return $id;

}

function gfi_get_image_data( $args ) {
	
	$url = genesis_get_option( 'featimg_url' );
	$id = get_attachment_id_from_url( $url );
	$size = get_post_meta( get_the_ID(), GFI_PREFIX . 'custom_feat_img', true );
	$size = $size ? $size : $args['size'];
	
	$html = wp_get_attachment_image( $id, $size, false, $args['attr'] );
	list( $url ) = wp_get_attachment_image_src( $id, $size, false, $args['attr'] ); 
	
	//* Source path, relative to the root
	$src = str_replace( home_url(), '', $url );
	
	return array(
		'url'  => $url,
		'id'   => $id,
		'size' => $size,
		'src'  => $src,
		'html' => $html,
	);
}

function pr( $args ) {
	echo '<pre>' . htmlspecialchars( print_r( $args, 1 ) ) . '</pre>';
}

/**
 * Filters genesis_get_image() returning the default image if html or url are empty.
 * 
 */
//add_filter( 'genesis_get_image_default_args', 'gfi_get_image_default_args', 10, 2 );
function gfi_get_image_default_args( $defaults, $args ) {
	if ( 'first-attached' !== $defaults['fallback'] || !genesis_get_option( 'featimg_default_enable' ) ) {
		return $defaults;
	}

	// Ok, now let's check to see if an image exists
	$data = gfi_get_image_data( $args );

	return array(
		'id'   => $data['id'],
		'html' => $data['html'],
		'url'  => $data['url'],
	);
	
}

add_filter( 'genesis_pre_get_image' , 'genesis_get_image_default' , 10 , 6 );
function genesis_get_image_default( $pre, $args, $post ) {

	if ( $pre !== false || !genesis_get_option( 'featimg_default_enable' ) || has_post_thumbnail( $post->ID ) ) {
		return $pre;
	}

	// Get first-attached
	if ( 'first-attached' === $args['fallback'] && genesis_get_image_id( $args['num'], $args['post_id'] ) ) {
		return $pre;
	}

	// Now let's do our featured Image
	$data = gfi_get_image_data( $args );
	
	// determine output
	if ( 'html' === strtolower( $args['format'] ) ) {
		$output = $data['html'];
	} elseif ( 'url' === strtolower( $args['format'] ) ) {
		$output = $data['url'];
	} else {
		$output = $data['src'];
	}
		
	// return FALSE if $url is blank
	if ( empty( $data['url'] ) ) {
		$output = false;
	}
	
	return $output;
	
}