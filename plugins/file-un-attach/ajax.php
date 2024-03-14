<?php
/**
 * File un-attach - Ajax events
 *
 * @package Image Store
 * @author Hafid Trujillo
 * @copyright 20010-2011
 * @since 0.5.0
 */

//dont cache file
header("Robots: none");
header( 'X-Content-Type-Options: nosniff' );
header( 'Last-Modified:' . gmdate( 'D,d M Y H:i:s' ) . ' GMT' );
header( 'Cache-control:no-cache,no-store,must-revalidate,max-age=0' );

//define constants
define( 'WP_ADMIN', true );
define( 'DOING_AJAX', true );

$_SERVER['PHP_SELF'] = "/wp-admin/fun-ajax.php";

//load wp
require_once '../../../wp-load.php';

if ( empty( $_REQUEST['action'] ) )
	die( );

/**
 * Unattach file
 *
 * @return void
 * @since 0.5.0
 */
function file_unattach_unattach_this( ) {
	check_ajax_referer( "funajax" );

	$postid = isset( $_POST['postid'] ) ? ( int ) $_POST['postid'] : false;
	$imageid = isset( $_POST['imageid'] ) ?  ( int ) $_POST['imageid'] : false;

	if( !$imageid )
		return;
	
	if( $postid == $imageid )
		$postid = 0;
		
	delete_post_meta( $imageid, '_fun-parent', $postid );
	wp_update_post( array( 'ID' => $imageid, 'post_parent' => 0 ) );
}

/**
 * Attach file
 *
 * @return void
 * @since 0.5.0
 */
function file_unattach_attach_this( ) {
	check_ajax_referer( "funajax" );
	
	if( empty( $_POST['postid'] ) || empty( $_POST['imageid'] ) )
		return false;
	
	$postid = ( int ) $_POST['postid'];
	$imageid = ( int ) $_POST['imageid'];

	add_post_meta( $imageid, '_fun-parent', $postid );
}

/**
 * Find posts, function
 * Copied form admin-ajax.php
 *
 * @return void
 * @since 0.5.0
 */
function file_unattach_find_posts( ) {

	check_ajax_referer( "funajax" );
	
	if ( empty( $_POST['ps'] ) || empty( $_POST['type'] ) )
		exit;

	global $wpdb;
		
	$s = stripslashes( $_POST['ps'] );
	$searchand = $search = '';
	$args = array(
		'post_status' => 'any',
		'posts_per_page' => 50,
		'post_type' => $_POST['type'],
	);
	
	if ( '' !== $s )
		$args['s'] = $s;
		
	if( !empty( $_POST['exclude'] ))
		$args['exclude'] = explode( ',', trim($_POST['exclude'], ',') );

	$posts = get_posts( $args );

	if ( !$posts )
		wp_die( __( 'No items found.' ) );

	$html = '<table class="widefat fun-search-results" cellspacing="0"><thead>
	<tr><th class="found-radio">' . esc_attr__( 'Attached', 'fun' ) . '</th>
	<th>' . esc_attr__( 'Title', 'fun' ) . '</th>
	<th>' . esc_attr__( 'Date', 'fun' ) . '</th>
	<th>' . esc_attr__( 'Type', 'fun' ) . '</th>
	<th>' . esc_attr__( 'Status', 'fun' ) . '</th></tr></thead><tbody>';

	foreach ( $posts as $post ) {
		switch ( $post->post_status ) {
			case 'publish' :
			case 'private' :
				$stat = __( 'Published' );
				break;
			case 'future' :
				$stat = __( 'Scheduled' );
				break;
			case 'pending' :
				$stat = __( 'Pending Review' );
				break;
			case 'draft' :
			case 'auto-draft' :
				$stat = __( 'Draft' );
				break;
		}

		$time = ( '0000-00-00 00:00:00' == $post->post_date )  ?  '' : mysql2date( __( 'Y/m/d' ), $post->post_date );
		
		$html .= '<tr class="found-posts">';
		$html .= '<td class="found-radio"><input type="checkbox" id="fun-found-' . ( int ) $post->ID . '" name="found_post[' . ( int ) $post->ID . ']" value="' . esc_attr( $post->ID ) . '"></td>';
		$html .= '<td><label for="found-' . ( int ) $post->ID . '"><a href="' . esc_url( get_edit_post_link( $post->ID ) ). '">' . esc_html( $post->post_title ) . '</a></label></td>';
		
		$html .= '<td>' . esc_html( $time ) . '</td><td>' . esc_html( $post->post_type ) . '</td><td>' . esc_html( $stat ) . '</td>';
		$html .= '</tr>' . "\n\n";
	}
	
	$html .= '</tbody></table>';
	$html .= '<input name="fun-search" type="hidden" value="1" />';

	$x = new WP_Ajax_Response( );
	$x->add( array( 
		'data' => $html,
		'action' => NULL
	 ) );
	$x->send( );

	die( );
}

/**
 * Check if image is attached
 *
 * @return void
 * @since 0.5.0
 */
function file_unattach_is_attached( ){
	
	check_ajax_referer( "funajax" );
	
	if ( empty( $_POST['img'] ) || empty( $_POST['postid'] ))
		exit;

	global $wpdb;

	$postid = ( int ) $_POST['postid'];
	$imageid = ( int ) $_POST['img'];
	
	$posts = $wpdb->get_results( 
		$wpdb->prepare( 
			" SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.ID IN (
				SELECT post_parent FROM $wpdb->posts 
				WHERE $wpdb->posts.post_type = 'attachment' 
				AND $wpdb->posts.ID = %d
			) OR $wpdb->posts.ID IN ( 
				SELECT meta_value FROM $wpdb->postmeta 
				WHERE $wpdb->postmeta.meta_key = '_fun-parent' 
				AND $wpdb->postmeta.post_id = $imageid
				AND $wpdb->postmeta.meta_value = %d
			) ORDER BY post_date_gmt DESC "
		, $postid, $postid  )
	);
	 	 
	if( !empty( $posts ) ){
		echo '<a class="funattach" id="unattach-' . $imageid . '" href="#" style="display:block">' . esc_html( __( 'Detach', 'fun' ) ). '</a>';
		echo '<span class="fun-message hidden fun-mess-' . $imageid . '">' . esc_html( __( " Detach this file?", 'fun' ) ) . '&nbsp;';
		echo '<a class="fun-yes" href="#" id="file-unattch-'. $imageid . '">' . esc_html( __( 'Yes', 'fun' ) ) . '</a> &nbsp;';
		echo '<a class="fun-no" href="#" >' . esc_html( __( 'No', 'fun' ) ) . '</a></span>';
	}else{
		echo '<a class="fileattach" id="attach-' . $imageid . '" href="#" style="display:block">' . __( 'Attach', 'fun' ) . '</a>';
		echo '<span class="fun-message hidden fun-mess-' . $imageid . '">' . esc_html( __( "File has been attached", 'fun' ) ) . '</span>';
	}
}

/**
 * Find posts to which the 
 * image is attached,
 *
 * @return void
 * @since 0.5.0
 */
function file_unattach_find_attached( ) {

	check_ajax_referer( "funajax" );
	
	if ( empty( $_POST['img'] ) )
		exit;

	global $wpdb;

	$postid = ( int ) $_POST['img'];
	$post_types = get_post_types( array( 'public' => true ), 'objects' );
	
	unset( $post_types['ims_image'] );
	unset( $post_types['attachment'] );
	
	$posts = $wpdb->get_results(
		$wpdb->prepare( 
			"SELECT ID, post_title, post_type, post_status, post_date
			 FROM $wpdb->posts WHERE $wpdb->posts.ID IN ( 
			 	SELECT post_parent FROM $wpdb->posts 
				WHERE $wpdb->posts.post_type = 'attachment' 
				AND $wpdb->posts.ID = %d
			 ) OR $wpdb->posts.ID IN (
			 	SELECT meta_value FROM $wpdb->postmeta 
				WHERE $wpdb->postmeta.meta_key = '_fun-parent' 
				AND $wpdb->postmeta.post_id = %d
			) ORDER BY post_date_gmt DESC LIMIT 50 "
		, $postid, $postid ) 
	 );

	$html = '<table class="widefat fun-search-results" cellspacing="0"><thead>
	<tr><th class="found-radio">' . esc_attr__( 'Attached', 'fun' ) . '</th>
	<th>' . esc_attr__( 'Title', 'fun' ) . '</th>
	<th>' . esc_attr__( 'Date', 'fun' ) . '</th>
	<th>' . esc_attr__( 'Type', 'fun' ) . '</th>
	<th>' . esc_attr__( 'Status', 'fun' ) . '</th></tr>
	</thead><tbody>';

	foreach ( $posts as $post ) {
		switch ( $post->post_status ) {
			case 'publish' :
			case 'private' :
				$stat = __( 'Published' );
				break;
			case 'future' :
				$stat = __( 'Scheduled' );
				break;
			case 'pending' :
				$stat = __( 'Pending Review' );
				break;
			case 'draft' :
			case 'auto-draft' :
				$stat = __( 'Draft' );
				break;
		}
		if ( '0000-00-00 00:00:00' == $post->post_date ) {
			$time = '';
		} else {
			/* translators: date format in table columns, see http://php.net/date */
			$time = mysql2date( __( 'Y/m/d' ), $post->post_date );
		}

		$html .= '<tr class="found-posts">';
		$html .= '<td class="found-radio"><input type="checkbox" checked="checked" id="fun-found-' . ( int ) $post->ID . '" name="found_post[' . ( int ) $post->ID . ']" value="' . esc_attr( $post->ID ) . '"></td>';
		$html .= '<td><label for="found-' . ( int ) $post->ID . '"><a href="' . esc_url( get_edit_post_link( $post->ID ) ). '">' . esc_html( $post->post_title ) . '</a></label></td>';
		
		$html .= '<td>' . esc_html( $time ) . '</td><td>' . esc_html( $post->post_type ) . '</td><td>' . esc_html( $stat ) . '</td>';
		$html .= '</tr>' . "\n\n";
	}
	$html .= '</tbody></table>';
	$html .= '<input name="fun-current-attached" type="hidden" value="' . esc_attr( implode( ',', get_post_meta( $postid, "_fun-parent" ) ) ) . '" />';

	$x = new WP_Ajax_Response( );
	$x->add( array( 
		'data' => $html,
		'action' => NULL
	 ) );
	$x->send( );

	die( );
}


function file_unattach_attach_multiple(){
	
	check_ajax_referer( "funajax" );
	
	if ( empty( $_POST['images'] ) ||  empty( $_POST['postid'] ) || empty( $_POST['directive'] ) )
		exit;
	
	$postid = ( int ) $_POST['postid'];
	$images = ( array ) $_POST['images'];
	
	foreach( $images as $imageid ){
		if( $_POST['directive']  == 'attach' )
			add_post_meta( $imageid, '_fun-parent', $postid );
		else if( $_POST['directive']  == 'detach' )
			delete_post_meta( $imageid, '_fun-parent', $postid );
	}
}


switch ( $_POST['action'] ) {
	case 'attach':
		file_unattach_attach_this( );
		break;
	case 'is_attached':
		file_unattach_is_attached( );
		break;
	case 'find_posts':
		file_unattach_find_posts();
		break;
	case 'find_attached':
		file_unattach_find_attached( );
		break;
	case 'attach_multiple':
		file_unattach_attach_multiple( );
		break;
	case 'unattach':
		file_unattach_unattach_this( );
		break;
	default: die( );
}
?>