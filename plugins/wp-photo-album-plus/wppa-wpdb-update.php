<?php
/* wppa-wpdb-update.php
* Package: wp-photo-album-plus
*
* Contains low-level wpdb routines that update records
* Version: 8.5.02.001
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// Album
function wppa_update_album( $id, $args = false ) {
global $wpdb;

	// Init
	$modified 	= false;

	// Id must be given
	if ( ! wppa_is_int( $id ) ) {
		wppa_log( 'err', 'Missing id in wppa_update_album()' );
		return false;
	}

	// Id only given, just update modified
	if ( ! $args ) {
		$args = ['modified' => time()];
	}

	// Columns are
	$cols = ['id', 'name', 'description', 'a_order', 'main_photo', 'a_parent', 'p_order_by', 'cover_linktype', 'cover_linkpage', 'cover_link', 'owner',
					'timestamp', 'modified', 'upload_limit', 'alt_thumbsize', 'default_tags', 'cover_type', 'suba_order_by',
					'views', 'cats', 'scheduledtm', 'custom', 'crypt', 'treecounts', 'wmfile', 'wmpos', 'indexdtm', 'sname', 'zoomable', 'displayopts',
					'upload_limit_tree', 'scheduledel', 'status', 'max_children', 'rml_id', 'usedby'];

	// Fields to update are
	$fields = [];

	/* Check and optionally correct format and prepare for update */

	// Name
	if ( isset( $args['name'] ) ) {
		$n = sanitize_text_field( wppa_strip_tags( $args['name'], 'all' ) );
		if ( $n ) {
			$fields['name'] 	= $n;
			$fields['sname'] 	= wppa_name_slug( $n );
			$modified 			= true;
		}
	}

	// Description
	if ( isset( $args['description'] ) ) {
		$fields['description'] = $args['description'];
		$modified 				= true;
	}

	// Album order
	if ( isset( $args['a_order'] ) ) {
		if ( wppa_is_int( $args['a_order'] ) ) {
			$fields['a_order'] = $args['a_order'];
		}
	}

	// Main photo
	if ( isset( $args['main_photo'] ) ) {
		if ( wppa_is_int( $args['main_photo'] ) ) {
			$fields['main_photo'] = $args['main_photo'];
		}
	}

	// Parent album
	if ( isset( $args['a_parent'] ) ) {
		$p = strval( intval( $args['a_parent'] ) );
		if ( wppa_is_int( $p ) && $p != $id ) { // can not be self
			$fields['a_parent'] = $p;
			wppa_invalidate_treecounts( $id );	// Myself and my parents
			wppa_childlist_remove( $id );
			wppa_invalidate_treecounts( $p );	// My new parent
			wppa_childlist_remove( $p );
		}
	}

	// Photo order
	if ( isset( $args['p_order_by'] ) ) {
		$p = $args['p_order_by'];
		if ( wppa_is_int( $p ) && $p > '-8' && $p < '8' ) {
			$fields['p_order_by'] = $p;
		}
	}

	// Cover linktype
	if ( isset( $args['cover_linktype'] ) ) {
		$lt = $args['cover_linktype'];
		if ( in_array( $lt, array( 'content', 'albums', 'thumbs', 'slide', 'page', 'manual', 'none' ) ) ) {
			$fields['cover_linktype'] = $lt;
		}
	}

	// Cover link page
	if ( isset( $args['cover_linkpage'] ) ) {
		$lp = $args['cover_linkpage'];
		if ( $lp == 0 || get_post( $lp ) ) { // same or page exists
			$fields['cover_linkpage'] = $lp;
		}
	}

	// Cover link
	if ( isset( $args['cover_link'] ) ) {
		$cl = $args['cover_link'];
		if ( ! $cl ) {
			$fields['cover_link'] = '';
		}
		else {
			$fields['cover_link'] = sanitize_url( $cl );
		}
	}

	// Owner
	if ( isset( $args['owner'] ) ) {
		$o = $args['owner'];
		if ( $o == '--- public ---' || get_user_by( 'login', $o ) ) {
			$fields['owner'] = $o;
		}
	}

	// Timestamp
	if ( isset( $args['timestamp'] ) ) {
		$t = $args['timestamp'];
		if ( ! $t || wppa_is_posint( $t ) ) {
			$fields['timestamp'] = $t;
		}
	}

	// Modified
	if ( isset( $args['modified'] ) ) {
		$m = $args['modified'];
		if ( wppa_is_posint( $m ) ) {
			$fields['modified'] = $m;
		}
	}

	// Upload limit, Format checked in ajax
	if ( isset( $args['upload_limit'] ) ) {
		$fields['upload_limit'] = $args['upload_limit'];
	}

	// Alt thumbsize
	if ( isset( $args['alt_thumbsize'] ) ) {
		$a = $args['alt_thumbsize'];
		if ( $a == '0' || $a == 'yes' ) {
			$fields['alt_thumbsize'] = $args['alt_thumbsize'];
		}
	}

	// Default tags
	if ( isset( $args['default_tags'] ) ) {
		$d = $args['default_tags'];
		$fields['default_tags'] = wppa_sanitize_tags( $d, false, true );
	}

	// Cover type
	if ( isset( $args['cover_type'] ) ) {
		$ct = $args['cover_type'];
		if ( in_array( $ct, array( '', 'default', 'longdesc', 'imagefactory', 'default-mcr', 'longdesc-mcr', 'imagefactory-mcr' ) ) ) {
			$fields['cover_type'] = $ct;
			$modified = true;
		}
	}

	// Sub album order
	if ( isset( $args['suba_order_by'] ) ) {
		$s = $args['suba_order_by'];
		if ( in_array( $s, ['0', '3', '1', '-1', '2', '-2', '5', '-5'] ) ) {
			$fields['suba_order_by'] = $args['suba_order_by'];
		}
	}

	// Views
	if ( isset( $args['views'] ) ) {
		$fields['views'] = strval( intval( $args['views'] ) );
	}

	// Cats
	if ( isset( $args['cats'] ) ) {
		$fields['cats'] = wppa_sanitize_cats( $args['cats'] );
		wppa_clear_catlist();
		$modified = true;
	}

	// Scheduledtm
	if ( isset( $args['scheduledtm'] ) ) {
		$fields['scheduledtm'] = $args['scheduledtm'];
	}

	// Custom
	if ( isset( $args['custom'] ) ) {
		$fields['custom'] = $args['custom'];
		$modified = true;
	}

	// crypt
	if ( isset( $args['crypt'] ) ) {
		$fields['crypt'] = $args['crypt'];
	}

	// Treeecouts
	if ( isset( $args['treecounts'] ) ) {
		$fields['treecounts'] = $args['treecounts'];
	}

	// Watermark file
	if ( isset( $args['wmfile'] ) ) {
		$fields['wmfile'] = $args['wmfile'];
	}

	// Watermark pos
	if ( isset( $args['wmpos'] ) ) {
		$fields['wmpos'] = $args['wmpos'];
	}

	// Indexdtm
	if ( isset( $args['indexdtm'] ) ) {
		$i = $args['indexdtm'];
		if ( $i === '' || wppa_is_posint( $i ) ) {
			$fields['indexdtm'] = $i;
		}
	}

	// Sname
	if ( isset( $args['sname'] ) ) {
		$fields['sname'] = $args['sname'];
	}

	// Zoomable
	if ( isset( $args['zoomable'] ) ) {
		if ( in_array( $args['zoomable'], array( 'on', '', 'off' ) ) ) {
			$fields['zoomable'] = $args['zoomable'];
		}
	}

	// Displayopts. Format checked in ajax
	if ( isset( $args['displayopts'] ) ) {
		$fields['displayopts'] = $args['displayopts'];
	}

	// Upload limit tree
	if ( isset( $args['upload_limit_tree'] ) ) {
		$fields['upload_limit_tree'] = strval( intval ( $args['upload_limit_tree'] ) );
	}

	// Scheduledel
	if ( isset( $args['scheduledel'] ) ) {
		$fields['scheduledel'] = $args['scheduledel'];
	}

	// Status
	if ( isset( $args['status'] ) ) {
		$s = $args['status'];
		if ( in_array( $s, array( 'publish', 'private', 'hidden' ) ) ) {
			$fields['status'] = $s;
		}
	}

	// Max children
	if ( isset( $args['max_children'] ) ) {
		$fields['max_children'] = strval( intval( $args['max_children'] ) );
	}

	// RML id
	if ( isset( $args['rml_id'] ) ) {
		$fields['rml_id'] = strval( intval( $args['rml_id'] ) );
	}

	// Used by
	if ( isset( $args['usedby'] ) ) {
		$fields['usedby'] = $args['usedby'];
	}

	// If modified substantially, mark it
	if ( $modified ) {
		$fields['modified'] = time();
	}

	// Do the update
	try {
		$iret = $wpdb->update( WPPA_ALBUMS, $fields, ['id' => $id] );
		$f = var_export( $fields, true );
		wppa_log( 'db', "wppa_albums updatet item: $id fields: $f" );
		wppa_clear_cache( array( 'album' => $id ) );
		wppa_cache_album( 'invalidate', $id );
	}
	catch( Exception $e ) {
		wppa_log( 'err', 'wppa_update_album() caught exception: ' .  $e->getMessage() );
		$iret = false;
	}

	return $iret;
}

// Photo
function wppa_update_photo( $id, $args = false ) {
global $wpdb;

	// Init
	$modified 	= false;

	// Id must be given
	if ( ! wppa_is_int( $id ) ) {
		wppa_log( 'err', 'Missing id in wppa_update_photo()' );
		return false;
	}

	// Id only given, just update modified
	if ( ! $args ) {
		$args = ['modified' => time()];
	}

	// Columns are
	$cols = ['id',  'album', 'ext', 'name', 'description', 'p_order', 'mean_rating', 'linkurl', 'linktitle', 'linktarget', 'owner', 'timestamp',
					'status', 'rating_count', 'tags', 'alt', 'filename', 'modified', 'location', 'views', 'clicks', 'page_id', 'exifdtm',
					'videox', 'videoy', 'thumbx', 'thumby', 'photox', 'photoy', 'scheduledtm', 'scheduledel', 'custom', 'stereo', 'crypt',
					'magickstack', 'indexdtm', 'panorama', 'angle', 'sname', 'dlcount', 'thumblock', 'duration', 'rml_id', 'usedby', 'misc'];

	// Fields to update are
	$fields = [];

	/* Check and optionally correct format and prepare for update */

	// Album
	if ( isset( $args['album'] ) ) {
		$fields['album'] = strval( intval( $args['album'] ) );
	}

	// Ext
	if ( isset( $args['ext'] ) ) {
		$fields['ext'] = strtolower( $args['ext'] );
	}

	// Name
	if ( isset( $args['name'] ) ) {
		$n = sanitize_text_field( wppa_strip_tags( $args['name'], 'all' ) );
		if ( $n ) {
			$fields['name'] 	= $n;
			$fields['sname'] 	= wppa_name_slug( $n );
			$modified 			= true;
		}
	}

	// Description
	if ( isset( $args['description'] ) ) {
		$fields['description'] = $args['description'];
		$modified = true;
	}

	// P_order
	if ( isset( $args['p_order'] ) ) {
		$fields['p_order'] = strval( intval ( $args['p_order'] ) );
	}

	// Mean rating
	if ( isset( $args['mean_rating'] ) ) {
		$fields['mean_rating'] = is_numeric( $args['mean_rating'] ) ? $args['mean_rating'] : NULL;
	}

	// Link url
	if ( isset( $args['linkurl'] ) ) {
		$fields['linkurl'] = sanitize_url( $args['linkurl'], array( 'http', 'https' ) );
	}

	// Link title
	if ( isset( $args['linktitle'] ) ) {
		$fields['linktitle'] = sanitize_text_field( wppa_strip_tags( $args['linktitle'], 'all' ) );
	}

	// Link targe
	if ( isset( $args['linktarget'] ) ) {
		$t = $args['linktarget'];
		if ( in_array( $t, ['', '_blank', '_self'] ) ) {
			$fields['linktarget'] = $t;
		}
	}

	// Owner
	if ( isset( $args['owner'] ) ) {
		$o = $args['owner'];
		if ( $o == '--- public ---' || get_user_by( 'login', $o ) || filter_var( $o, FILTER_VALIDATE_IP ) ) {
			$fields['owner'] = $o;
		}
	}

	// Timestamp
	if ( isset( $args['timestamp'] ) ) {
		$t = $args['timestamp'];
		if ( ! $t || wppa_is_posint( $t ) ) {
			$fields['timestamp'] = $t;
		}
	}

	// Status
	if ( isset( $args['status'] ) ) {
		$s = $args['status'];
		if ( in_array( $s, array( 'pending', 'publish', 'featured', 'gold', 'silver', 'bronze', 'scheduled', 'private' ) ) ) {
			$fields['status'] = $s;
			wppa_clear_taglist();
			wppa_flush_upldr_cache( 'photoid', $id );
			if ( $s != 'scheduled' ) {
				$fields['scheduledtm'] = '';
			}
			$modified = true;
		}
	}

	// Rating count
	if ( isset( $args['rating_count'] ) ) {
		$fields['rating_count'] = strval( intval ( $args['rating_count'] ) );
	}

	// Tags
	if ( isset( $args['tags'] ) ) {
		$t = $args['tags'];
		$t = wppa_filter_iptc( $t, $id );
		$t = wppa_filter_exif( $t, $id );
		$fields['tags'] = wppa_sanitize_tags( $t );
		wppa_clear_taglist();
		$modified = true;
	}

	// Alt
	if ( isset( $args['alt'] ) ) {
		$fields['alt'] = strip_tags( stripslashes( $args['alt'] ) );
	}

	// Filename
	if ( isset( $args['filename'] ) ) {
		$fields['filename'] = wppa_sanitize_file_name( basename( $args['filename'] ) );
	}

	// Modified
	if ( isset( $args['modified'] ) ) {
		$fields['modified'] = time();
	}

	// Location
	if ( isset( $args['location'] ) ) {
		$fields['location'] = $args['location'];
	}

	// Views
	if ( isset( $args['views'] ) ) {
		$n = $args['views'];
		if ( wppa_is_notnegint( $n ) ) {
			$fields['views'] = $n;
		}
	}

	// Clicks
	if ( isset( $args['clicks'] ) ) {
		$n = $args['clicks'];
		if ( wppa_is_notnegint( $n ) ) {
			$fields['clicks'] = $n;
		}
	}

	// Page id
	if ( isset( $args['page_id'] ) ) {
		$n = $args['page_id'];
		if ( wppa_is_notnegint( $n ) ) {
			$fields['page_id'] = $n;
		}
	}

	// Exif dtm
	if ( isset( $args['exifdtm'] ) ) {
		$fields['exifdtm'] = $args['exifdtm'];
	}

	// Videox
	if ( isset( $args['videox'] ) ) {
		$n = $args['videox'];
		if ( wppa_is_notnegint( $n ) ) {
			$fields['videox'] = $n;
		}
	}

	// Videoy
	if ( isset( $args['videoy'] ) ) {
		$n = $args['videoy'];
		if ( wppa_is_notnegint( $n ) ) {
			$fields['videoy'] = $n;
		}
	}

	// Thumbx
	if ( isset( $args['thumbx'] ) ) {
		$n = $args['thumbx'];
		if ( wppa_is_notnegint( $n ) ) {
			$fields['thumbx'] = $n;
		}
	}

	// Thumby
	if ( isset( $args['thumby'] ) ) {
		$n = $args['thumby'];
		if ( wppa_is_notnegint( $n ) ) {
			$fields['thumby'] = $n;
		}
	}

	// Photox
	if ( isset( $args['photox'] ) ) {
		$n = $args['photox'];
		if ( wppa_is_notnegint( $n ) ) {
			$fields['photox'] = $n;
		}
	}

	// Photoy
	if ( isset( $args['photoy'] ) ) {
		$n = $args['photoy'];
		if ( wppa_is_notnegint( $n ) ) {
			$fields['photoy'] = $n;
		}
	}

	// Schedule date to be published
	if ( isset( $args['scheduledtm'] ) ) {
		$fields['scheduledtm'] = $args['scheduledtm'];
	}

	// Scheduled delete
	if ( isset( $args['scheduledel'] ) ) {
		$fields['scheduledel'] = $args['scheduledel'];
	}

	// Custom
	if ( isset( $args['custom'] ) ) {
		$fields['custom'] = $args['custom'];
		$modified = true;
	}

	// Stereo
	if ( isset( $args['stereo'] ) ) {
		$n = $args['stereo'];
		if ( in_array( $n, ['-1', '0', '1']  ) ) {
			$fields['stereo'] = $n;
		}
	}

	// Crypt
	if ( isset( $args['crypt'] ) ) {
		$fields['crypt'] = $args['crypt'];
	}

	// Magick stack
	if ( isset( $args['magickstack'] ) ) {
		$fields['magickstack'] = $args['magickstack'];
		$modified = true;
	}

	// Index date
	if ( isset( $args['indexdtm'] ) ) {
		$i = $args['indexdtm'];
		if ( wppa_is_posint( $i ) || $i == '' ) {
			$fields['indexdtm'] = $args['indexdtm'];
		}
	}

	// Panorama
	if ( isset( $args['panorama'] ) ) {
		$n = $args['panorama'];
		if ( in_array( $n, ['0', '1', '2']  ) ) {
			$fields['panorama'] = $n;
		}
	}

	// Angle
	if ( isset( $args['angle'] ) ) {
		$n = $args['angle'];
		if ( wppa_is_notnegint( $n ) ) {
			$fields['angle'] = $n;
		}
	}

	// Name slug
	if ( isset( $args['sname'] ) ) {
		$fields['sname'] = $args['sname'];
	}

	// Download count
	if ( isset( $args['dlcount'] ) ) {
		$n = $args['dlcount'];
		if ( wppa_is_notnegint( $n ) ) {
			$fields['dlcount'] = $n;
		}
	}

	// Thumbnail lock
	if ( isset( $args['thumblock'] ) ) {
		$n = $args['thumblock'];
		if ( in_array( $n, ['0', '1'] ) ) {
			$fields['thumblock'] = $n;
		}
	}

	// Duration
	if ( isset( $args['duration'] ) ) {
		$fields['duration'] = sprintf( '%4.2f', $args['duration'] );
	}

	// RML id
	if ( isset( $args['rml_id'] ) ) {
		$fields['rml_id'] = $args['rml_id'];
	}

	// Used by
	if ( isset( $args['usedby'] ) ) {
		$fields['usedby'] = $args['usedby'];
	}

	// misc
	if ( isset( $args['misc'] ) ) {
		$fields['misc'] = $args['misc'];
	}

	// If modified substantially, mark it
	if ( $modified ) {
		$fields['modified'] = time();
	}

	// Do the update
	try {
		$iret = $wpdb->update( WPPA_PHOTOS, $fields, ['id' => $id] );
		$f = var_export( $fields, true );
		wppa_log( 'db', "wppa_photos updatet item: $id fields: $f" );
	}
	catch( Exception $e ) {
		wppa_log( 'err', 'wppa_update_photo() caught exception: ' .  $e->getMessage() );
		$iret = false;
	}

	// Housekeeping if successfull
	if ( $iret ) {

		// Update index
		if ( $modified && ! isset( $args['indexdtm'] ) ) { // No recursive indexing please
			wppa_index_update( 'photo', $id );
		}

		// Clear associated caches
		wppa_clear_cache( array( 'photo' => $id ) );
		wppa_cache_photo( 'invalidate', $id );
	}

	return $iret;
}

// Rating
function wppa_update_rating( $id, $args = false ) {
global $wpdb;

	// Id must be given
	if ( ! wppa_is_int( $id ) ) {
		wppa_log( 'err', 'Missing id in wppa_update_rating()' );
		return false;
	}

	// Columns are
	$cols = ['id', 'timestamp', 'photo', 'value', 'user', 'userid', 'ip', 'status'];

	// Fields to update are
	$fields = [];

	/* Check and optionally correct format and prepare for update */

	if ( isset( $args['timestamp'] ) ) {
		$t = $args['timestamp'];
		if ( ! $t || wppa_is_posint( $t ) ) {
			$fields['timestamp'] = $t;
		}
	}

	if ( isset( $args['photo'] ) ) {
		$p = $args['photo'];
		if ( wppa_is_posint( $p ) ) {
			$fields['photo'] = $p;
		}
	}

	if ( isset( $args['value'] ) ) {
		$v = $args['value'];
		if ( wppa_is_int( $v ) ) {
			$fields['value'] = $v;
		}
	}

	if ( isset( $args['user'] ) ) {
		$fields['user'] = sanitize_text_field( $args['user'] );
	}

	if ( isset( $args['userid'] ) ) {
		$u = $args['userid'];
		if ( wppa_is_posint( $u ) ) {
			$fields['userid'] = $u;
		}
	}

	if ( isset( $args['ip'] ) ) {
		$fields['ip'] = $args['ip'];
	}

	if ( isset( $args['status'] ) ) {
		$fields['status'] = sanitize_text_field( $args['status'] );
	}

	// Do the update
	try {
		$iret = $wpdb->update( WPPA_RATING, $fields, ['id' => $id] );
		$f = implode( ', ', array_keys( $fields ) );
		wppa_log( 'db', "wppa_rating updatet item: $id fields: $f" );
	}
	catch( Exception $e ) {
		wppa_log( 'err', 'wppa_update_rating() caught exception: ' .  $e->getMessage() );
		$iret = false;
	}

	return $iret;
}

// Comment
function wppa_update_comment( $id, $args = false ) {
global $wpdb;

	// Id must be given
	if ( ! wppa_is_int( $id ) ) {
		wppa_log( 'err', 'Missing id in wppa_update_comment()' );
		return false;
	}

	// Columns are
	$cols = ['id', 'timestamp', 'photo', 'user', 'userid', 'ip', 'email', 'comment', 'status'];

	// Fields to update are
	$fields = [];

	/* Check and optionally correct format and prepare for update */

	if ( isset( $args['timestamp'] ) ) {
		$t = $args['timestamp'];
		if ( ! $t || wppa_is_posint( $t ) ) {
			$fields['timestamp'] = $t;
		}
	}

	if ( isset( $args['photo'] ) ) {
		$p = $args['photo'];
		if ( wppa_is_posint( $p ) ) {
			$fields['photo'] = $p;
		}
	}

	if ( isset( $args['user'] ) ) {
		$fields['user'] = sanitize_text_field( $args['user'] );
	}

	if ( isset( $args['userid'] ) ) {
		$u = $args['userid'];
		if ( wppa_is_posint( $u ) ) {
			$fields['userid'] = $u;
		}
	}

	if ( isset( $args['ip'] ) ) {
		$fields['ip'] = $args['ip'];
	}

	if ( isset( $args['email'] ) ) {
		$fields['email'] = $args['email'];
	}

	if ( isset( $args['comment'] ) ) {
		$fields['comment'] = $args['comment'];
	}

	if ( isset( $args['status'] ) ) {
		$fields['status'] = sanitize_text_field( $args['status'] );
	}

	// Do the update
	try {
		$iret = $wpdb->update( WPPA_COMMENTS, $fields, ['id' => $id] );
		$f = implode( ', ', array_keys( $fields ) );
		wppa_log( 'db', "wppa_comments updatet item: $id fields: $f" );
	}
	catch( Exception $e ) {
		wppa_log( 'err', 'wppa_update_comment() caught exception: ' .  $e->getMessage() );
		$iret = false;
	}

	return $iret;
}

// Index
function wppa_update_index( $id, $args ) {
global $wpdb;

	// Id must be given
	if ( ! wppa_is_int( $id ) ) {
		wppa_log( 'err', 'Missing id in wppa_update_index()' );
		return false;
	}

	// Columns are
	$cols = ['id', 'slug', 'albums', 'photos'];

	// Fields to update are
	$fields = [];

	/* Check and optionally correct format and prepare for update */

	// Slug
	if ( isset( $args['slug'] ) ) {
		$fields['slug'] = $args['slug'];
	}

	// Albums
	if ( isset( $args['albums'] ) ) {
		$fields['albums'] = $args['albums'];
	}

	// Photos
	if ( isset( $args['photos'] ) ) {
		$fields['photos'] = $args['photos'];
	}

	// Do the update
	try {
		$iret = $wpdb->update( WPPA_INDEX, $fields, ['id' => $id] );
		$f = implode( ', ', array_keys( $fields ) );
		wppa_log( 'db', "wppa_index updatet item: $id fields: $f" );
	}
	catch( Exception $e ) {
		wppa_log( 'err', 'wppa_update_index() caught exception: ' .  $e->getMessage() );
		$iret = false;
	}

	return $iret;
}

// Cleart a column by setting it to '' or '0'
function wppa_clear_col( $table, $col, $val = '' ) {
global $wpdb;

	if ( $val !== '' ) {
		$val = '0';
	}
	$query = $wpdb->prepare( "UPDATE $table SET $col = %s", $val );
	try {
		$wpdb->query( $query );
		wppa_log( 'db', $query );
	}
	catch( Exception $e ) {
		wppa_log( 'err', 'wppa_clear_col() caught exception: ' .  $e->getMessage() );
		return false;
	}
	return true;
}

// Clear table
function wppa_clear_table( $table ) {
global $wpdb;

	$query = "TRUNCATE TABLE $table";
	try {
		$wpdb->query( $query );
		wppa_log( 'db', $query );
	}
	catch( Exception $e ) {
		wppa_log( 'err', 'wppa_clear_table() caught exception: ' .  $e->getMessage() );
		return false;
	}
	return true;
}

// Delete a row
function wppa_del_row( $table, $col, $value ) {
global $wpdb;

	$query = $wpdb->prepare( "DELETE FROM $table WHERE $col = %s", $value );
	try {
		$iret = $wpdb->query( $query );
		wppa_log( 'db', $query );
	}
	catch( Exception $e ) {
		wppa_log( 'err', 'wppa_del_row() caught exception: ' .  $e->getMessage() );
		return false;
	}
	return $iret;
}

// Get db table count conditionally
// Example: wppa_get_count( WPPA_PHOTOS, ['album' => 6, 'status' => 'private'], ['=', '!='], 'and' );
// to get the count of non private photos in album 6
function wppa_get_count( $table, $conditions = array(), $operators = array(), $andor = 'and' ) {
global $wpdb;

	$query = "SELECT COUNT(*) FROM $table ";
	if ( count( $conditions ) ) {
		$query .= "WHERE ";
		$first = true;
		$index = 0;
		foreach ( array_keys( $conditions ) as $key ) {
			$field = $key;
			$value = $conditions[$key];
			$op    = isset( $operators[$index] ) ? $operators[$index] : "=";
			$ao    = ( $andor == 'and' ? "AND " : "OR " );
			if ( $first ) {
				$first = false;
			}
			else {
				$query .= $ao;
			}
			$query .= $wpdb->prepare( "$key $op %s ", $value );
			$index++;
		}
	}
	try {
		$iret = $wpdb->get_var( $query );
		wppa_log( 'db', $query . ':{b}' . $iret . '{/b}' );
	}
	catch( Exception $e ) {
		wppa_log( 'err', 'wppa_get_count() caught exception: ' .  $e->getMessage() );
		return false;
	}
	return $iret;
}