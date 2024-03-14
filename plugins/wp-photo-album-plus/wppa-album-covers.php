<?php
/* wppa-album-covers.php
* Package: wp-photo-album-plus
*
* Functions for album covers
* Version: 8.5.03.011
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// Main entry for an album cover
// decide wich cover type and call the types function
function wppa_album_cover( $id ) {

	// Is this album visible for the current user?
	if ( ! wppa_is_album_visible( $id ) ) {
		return;
	}

	// Find the album specific cover type
	$cover_type = wppa_get_album_item( $id, 'cover_type' );

	// No type specified (0), use default
	if ( ! $cover_type ) {
		$cover_type = wppa_opt( 'cover_type' );
	}

	// Find the cover photo position
	wppa( 'coverphoto_pos', wppa_opt( 'coverphoto_pos' ) );

	// Dispatch on covertype
	switch ( $cover_type ) {
		case 'default':
			wppa_album_cover_default( $id, false );
			break;
		case 'default-mcr':
			wppa_album_cover_default( $id, true );
			break;
		case 'imagefactory':
			if ( wppa( 'coverphoto_pos' ) == 'left' ) {
				wppa( 'coverphoto_pos', 'top' );
			}
			if ( wppa( 'coverphoto_pos' ) == 'right' ) {
				wppa( 'coverphoto_pos', 'bottom' );
			}
			wppa_album_cover_imagefactory( $id, false );
			break;
		case 'imagefactory-mcr':
			if ( wppa( 'coverphoto_pos' ) == 'left' ) {
				wppa( 'coverphoto_pos', 'top' );
			}
			if ( wppa( 'coverphoto_pos' ) == 'right' ) {
				wppa( 'coverphoto_pos', 'bottom' );
			}
			wppa_album_cover_imagefactory( $id, true );
			break;
		case 'longdesc':
			if ( wppa( 'coverphoto_pos' ) == 'top' ) {
				wppa( 'coverphoto_pos', 'left' );
			}
			if ( wppa( 'coverphoto_pos' ) == 'bottom' ) {
				wppa( 'coverphoto_pos', 'right' );
			}
			wppa_album_cover_longdesc( $id, false );
			break;
		case 'longdesc-mcr':
			if ( wppa( 'coverphoto_pos' ) == 'top' ) {
				wppa( 'coverphoto_pos', 'left' );
			}
			if ( wppa( 'coverphoto_pos' ) == 'bottom' ) {
				wppa( 'coverphoto_pos', 'right' );
			}
			wppa_album_cover_longdesc( $id, true );
			break;
		case 'grid':
			wppa_album_cover_grid( $id );
			break;
		default:
			$err = 'Unimplemented covertype: ' . $cover_type;
			wppa_log( 'Err', $err );
	}
}

// The default cover type
function wppa_album_cover_default( $albumid, $multicolresp = false ) {
global $cover_count_key;

	// Init
	$album 	= wppa_cache_album( $albumid );

	// Multi column responsive?
	if ( $multicolresp ) $mcr = 'mcr-'; else $mcr = '';

	// Find album details
	$coverphoto = wppa_get_coverphoto_id( $albumid );
	$image 		= wppa_cache_photo( $coverphoto );
	$photocount = wppa_get_visible_photo_count( $albumid );
	$albumcount = wppa_get_visible_album_count( $albumid );

	// Init links
	$title 				= '';
	$linkpage 			= '';
	$href_title 		= '';
	$href_slideshow 	= '';
	$onclick_title 		= '';
	$onclick_slideshow 	= '';

	// See if there is substantial content to the album
	$has_content = $albumcount || $photocount;

	// What is the albums title linktype
	$linktype = $album['cover_linktype'];

	// If not specified, use default
	if ( ! $linktype ) {
		$linktype = 'content';
	}

	// What is the albums title linkpage
	$linkpage = $album['cover_linkpage'];

	// Fix backward compatibility issue
	if ( $linkpage == '-1' ) {
		$linktype = 'none';
	}

	// Find the cover title href, onclick and title
	$title_attr 	= wppa_get_album_title_attr_a( 	$albumid,
													$linktype,
													$linkpage,
													$has_content,
													$coverphoto,
													$photocount
												);
	$href_title 	= $title_attr['href'];
	$onclick_title 	= $title_attr['onclick'];
	$title 			= $title_attr['title'];

	// Find the slideshow link and onclick
	$href_slideshow = wppa_get_slideshow_url( array( 'album' => $albumid,
													 'page' => $linkpage ) );
	$ajax_slideshow = wppa_get_slideshow_url_ajax( array( 'album' => $albumid,
														  'page' => $linkpage ) );
	if ( ! $linkpage ) {
		$onclick_slideshow = "wppaDoAjaxRender( " . wppa( 'mocc' ) . ", '" . $ajax_slideshow . "', '" . $href_slideshow . "' )";
		$href_slideshow = "#";
	}

	// Find the coverphoto link
	if ( $coverphoto ) {
		$photolink = wppa_get_imglnk_a( 	'coverimg',
											$coverphoto,
											$href_title,
											$title,
											$onclick_title,
											'',
											$albumid
										);
	}
	else {
		$photolink = false;
	}

	// Find the coverphoto details
	if ( $coverphoto ) {
		$path 		= wppa_get_thumb_path( 	$coverphoto );
		$imgattr_a 	= wppa_get_imgstyle_a( 	$coverphoto,
											$path,
											wppa_opt( 'smallsize' ),
											'',
											'cover'
										);
		$src 		= wppa_get_thumb_url( 	$coverphoto,
											true,
											'',
											$imgattr_a['width'],
											$imgattr_a['height'],
											wppa_switch( 'cover_use_thumb' )
										);
	}

	// No coverphoto
	else {
		$path 		= '';
		$imgattr_a 	= false;
		$src 		= '';
	}

	// Feed?
	if ( is_feed() ) {
		$events  	= '';
	}
	else {
		$events 	= wppa_get_imgevents( 'cover' );
	}

	// Is cover a-symmetric ?
	$photo_pos = wppa( 'coverphoto_pos' );
	if ( $photo_pos == 'left' || $photo_pos == 'right' ) {
		$class_asym = 'wppa-asym-text-frame-' . $mcr . wppa( 'mocc' );
	}
	else {
		$class_asym = '';
	}

	// Set up album cover style
	$style =  '';
	if ( is_feed() ) {
		$style .= ' padding:7px;';
	}

	$style .= wppa_get_cover_width( 'cover' );

	if ( $cover_count_key == 'm' ) {
		$style .= 'margin-left: 8px;';
	}
	elseif ( $cover_count_key == 'r' ) {
		$style .= 'float:right;';
	}
	else {
		$style .= 'clear:both;';
	}

	// keep track of position
	wppa_step_covercount( 'cover' );

	// Open the album box
	wppa_out( '
	<div
		id="album-' . $albumid . '-' . wppa( 'mocc' ) . '"
		class="wppa-album-cover-standard album wppa-box wppa-cover-box wppa-cover-box-' . $mcr . wppa( 'mocc' ) . '"
		style="' . $style . '"
		>' );

	// First The Cover photo?
	if ( $photo_pos == 'left' || $photo_pos == 'top' ) {
		wppa_the_coverphoto( 	$albumid,
								$image,
								$src,
								$photo_pos,
								$photolink,
								$title,
								$imgattr_a,
								$events
							);
	}

	// Open the Cover text frame
	$textframestyle = wppa_get_text_frame_style( $photo_pos, 'cover' );
	wppa_out( '
	<div
		id="covertext_frame_' . $albumid . '_' . wppa( 'mocc' ) . '"
		class="wppa-text-frame-' . wppa( 'mocc' ) . ' wppa-text-frame wppa-cover-text-frame ' . $class_asym . '" ' .
		$textframestyle . '>' );

	// The Album title
	if ( $photolink ) {
		$target = '_self';
	}
	else {
		$target = '';
	}
	wppa_the_album_title( 	$albumid,
							$href_title,
							$onclick_title,
							$title,
							$target
						);

	// The Album description
	if ( wppa_switch( 'show_cover_text' ) ) {
		if ( wppa_opt( 'text_frame_height' ) > '0' ) {
			$textheight = 'min-height:' . wppa_opt( 'text_frame_height' ) . 'px;';
		}
		else {
			$textheight = '';
		}
		wppa_out( '
		<div
			class="wppa-box-text wppa-black wppa-box-text-desc"
			style="' . $textheight . '">' .
				wppa_get_album_desc( $albumid ) . '
		</div>' );
	}

	// Close the Cover text frame
	if ( $photo_pos == 'left' ) {
		wppa_out( '
		</div>
		<div style="clear:both"></div>' );
	}

	// The 'Slideshow'/'Browse' link
	wppa_the_slideshow_browse_link( $photocount,
									$href_slideshow,
									$onclick_slideshow,
									$target
								);

	// The 'View' link
	wppa_album_cover_view_link( $albumid );

	// Close the Cover text frame
	if ( $photo_pos != 'left' ) {
		wppa_out( '</div>' );
	}

	// The Cover photo last?
	if ( $photo_pos == 'right' || $photo_pos == 'bottom' ) {
		wppa_the_coverphoto( 	$albumid,
								$image,
								$src,
								$photo_pos,
								$photolink,
								$title,
								$imgattr_a,
								$events
							);
	}

	// The sublinks
	wppa_albumcover_sublinks( 	$albumid,
								wppa_get_cover_width( 'cover' ),
								$multicolresp
							);

	// Prepare for closing
	wppa_out( '<div style="clear:both;"></div>' );

	// Close the album box
	wppa_out( '</div>' );

}

// Type Image Factory
function wppa_album_cover_imagefactory( $albumid, $multicolresp = false ) {
global $cover_count_key;

	// Init
	$album = wppa_cache_album( $albumid );

	// Multi column responsive?
	if ( $multicolresp ) $mcr = 'mcr-'; else $mcr = '';

	$photo_pos 		= wppa( 'coverphoto_pos' );
	$cpcount 		= $album['main_photo'] > '0' ? '1' : wppa_opt( 'imgfact_count' );
	$coverphotos 	= wppa_get_coverphoto_ids( $albumid, $cpcount );

	$images 	= array();
	$srcs 		= array();
	$paths 		= array();
	$imgattrs_a = array();
	$photolinks = array();

	if ( is_feed() ) {
		$events = '';
	}
	else {
		$events = wppa_get_imgevents( 'cover' );
	}

	if ( ! empty( $coverphotos ) ) {
		$coverphoto = $coverphotos['0'];
	}
	else {
		$coverphoto = false;
	}

	$photocount = wppa_get_visible_photo_count( $albumid );
	$albumcount = wppa_get_visible_album_count( $albumid );
	$title 		= '';
	$linkpage 	= '';

	$href_title 		= '';
	$href_slideshow 	= '';
	$onclick_title 		= '';
	$onclick_slideshow 	= '';

	// See if there is substantial content to the album
	$has_content = $albumcount || $photocount;

	// If not specified, use default
	$linktype = $album['cover_linktype'];
	if ( ! $linktype ) {
		$linktype = 'content';
	}

	// Fix backward compatibility issue
	$linkpage = $album['cover_linkpage'];
	if ( $linkpage == '-1' ) {
		$linktype = 'none';
	}

	// Find the cover title href, onclick and title
	$title_attr 	= wppa_get_album_title_attr_a( 	$albumid,
													$linktype,
													$linkpage,
													$has_content,
													$coverphoto,
													$photocount
												);
	$href_title 	= $title_attr['href'];
	$onclick_title 	= $title_attr['onclick'];
	$title 			= $title_attr['title'];

	// Find the coverphotos details
	foreach ( $coverphotos as $coverphoto ) {

		$images[] 		= wppa_cache_photo( $coverphoto );
		$path 			= wppa_get_thumb_path( 	$coverphoto	 );
		$paths[] 		= $path;
		$cpsize 		= count( $coverphotos ) == '1' ?
							wppa_opt( 'smallsize' ) :
							wppa_opt( 'smallsize_multi' );
		$imgattr_a		= wppa_get_imgstyle_a( 	$coverphoto,
												$path,
												$cpsize,
												'',
												'cover'
											);
		$imgattrs_a[] 	= $imgattr_a;
		$srcs[] 		= wppa_get_thumb_url( 	$coverphoto,
												true,
												'',
												$imgattr_a['width'],
												$imgattr_a['height'],
												wppa_switch( 'cover_use_thumb' )
											);
		$photolinks[] 	= wppa_get_imglnk_a( 	'coverimg',
												$coverphoto,
												$href_title,
												$title,
												$onclick_title,
												'',
												$albumid
											);
	}

	// Find the slideshow link and onclick
	$href_slideshow = wppa_get_slideshow_url( array( 'album' => $albumid,
													 'page' => $linkpage ) );
	$ajax_slideshow = wppa_get_slideshow_url_ajax( array( 'album' => $albumid,
														  'page' => $linkpage ) );
	if ( ! $linkpage ) {
		$onclick_slideshow = "wppaDoAjaxRender( " . wppa( 'mocc' ) . ", '" . $ajax_slideshow . "', '" . $href_slideshow . "' )";
		$href_slideshow = "#";
	}

	$style =  '';
	if ( is_feed() ) $style .= ' padding:7px;';

	$style .= wppa_get_cover_width( 'cover' );
	if ( $cover_count_key == 'm' ) {
		$style .= 'margin-left: 8px;';
	}
	elseif ( $cover_count_key == 'r' ) {
		$style .= 'float:right;';
	}
	else {
		$style .= 'clear:both;';
	}
	wppa_step_covercount( 'cover' );

	$pl = isset( $photolinks['0']['target'] ) ? $photolinks['0']['target'] : '_self';
	$target = '_self';

	// Open the album box
	wppa_out( '
	<div
		id="album-' . $albumid . '-' . wppa( 'mocc' ) . '"
		class="wppa-album-cover-imagefactory album wppa-box wppa-cover-box wppa-cover-box-' . $mcr . wppa( 'mocc' ) . '"
		style="' . $style . '">' );

	// First The Cover photo?
	if ( $photo_pos == 'left' || $photo_pos == 'top' ) {
		wppa_the_coverphotos(
			$albumid, $images, $srcs, $photo_pos, $photolinks, $title, $imgattrs_a, $events );
	}

	// Open the Cover text frame
	$textframestyle = 'style="text-align:center;"';
	wppa_out( '
	<div
		id="covertext_frame_' . $albumid . '_' . wppa( 'mocc' ) . '"
		class="wppa-text-frame-' . wppa( 'mocc' ) . ' wppa-text-frame wppa-cover-text-frame" ' .
		$textframestyle . '>' );

	// The Album title
	wppa_the_album_title( $albumid, $href_title, $onclick_title, $title, $target );

	// The Album description
	if ( wppa_switch( 'show_cover_text' ) ) {
		$textheight = wppa_opt( 'text_frame_height' ) > '0' ?
		'min-height:' . wppa_opt( 'text_frame_height' ) . 'px; ' :
		'';
		wppa_out( '
		<div
			class="wppa-box-text wppa-black wppa-box-text-desc"
			style="' . $textheight . '">' .
			wppa_get_album_desc( $albumid ) . '
		</div>' );
	}

	// The 'Slideshow'/'Browse' link
	wppa_the_slideshow_browse_link( $photocount,
									$href_slideshow,
									$onclick_slideshow,
									$target
								);

	// The 'View' link
	wppa_album_cover_view_link( $albumid );

	// Close the Cover text frame
	wppa_out( '</div>' );

	// The Cover photo last?
	if ( $photo_pos == 'right' || $photo_pos == 'bottom' ) {
		wppa_the_coverphotos( 	$albumid,
								$images,
								$srcs,
								$photo_pos,
								$photolinks,
								$title,
								$imgattrs_a,
								$events
							);
	}

	// The sublinks
	wppa_albumcover_sublinks( 	$albumid,
								wppa_get_cover_width( 'cover' ),
								$multicolresp
							);

	// Prepare for closing
	wppa_out( '<div style="clear:both;"></div>' );

	// Close the album box
	wppa_out( '</div>' );
}

// Type Long Description
function wppa_album_cover_longdesc( $albumid, $multicolresp = false ) {
global $cover_count_key;

	$album = wppa_cache_album( $albumid );

	if ( $multicolresp ) $mcr = 'mcr-'; else $mcr = '';

	$coverphoto = wppa_get_coverphoto_id( $albumid );
	$image 		= wppa_cache_photo( $coverphoto );
	$photocount = wppa_get_visible_photo_count( $albumid );
	$albumcount = wppa_get_visible_album_count( $albumid );
	$title 		= '';
	$linkpage 	= '';

	$href_title 		= '';
	$href_slideshow 	= '';
	$onclick_title 		= '';
	$onclick_slideshow 	= '';

	// See if there is substantial content to the album
	$has_content = $albumcount || $photocount;

	// What is the albums title linktype
	$linktype = $album['cover_linktype'];
	if ( !$linktype ) $linktype = 'content'; // Default

	// What is the albums title linkpage
	$linkpage = $album['cover_linkpage'];
	if ( $linkpage == '-1' ) $linktype = 'none'; // for backward compatibility

	// Find the cover title href, onclick and title
	$title_attr 	= wppa_get_album_title_attr_a(
						$albumid, $linktype, $linkpage, $has_content, $coverphoto, $photocount );
	$href_title 	= $title_attr['href'];
	$onclick_title 	= $title_attr['onclick'];
	$title 			= $title_attr['title'];

	// Find the slideshow link and onclick
	$href_slideshow = wppa_get_slideshow_url( array( 'album' => $albumid,
													 'page' => $linkpage ) );
	$ajax_slideshow = wppa_get_slideshow_url_ajax( array( 'album' => $albumid,
														  'page' => $linkpage ) );
	if ( ! $linkpage ) {
		$onclick_slideshow = "wppaDoAjaxRender( " . wppa( 'mocc' ) . ", '" . $ajax_slideshow . "', '" . $href_slideshow . "' )";
		$href_slideshow = "#";
	}

	// Find the coverphoto link
	if ( $coverphoto ) {
		$photolink = wppa_get_imglnk_a(
			'coverimg', $coverphoto, $href_title, $title, $onclick_title, '', $albumid );
	}
	else $photolink = false;

	// Find the coverphoto details
	if ( $coverphoto ) {
		$path 		= wppa_get_thumb_path( $coverphoto );
		$imgattr_a 	= wppa_get_imgstyle_a(
							$coverphoto, $path, wppa_opt( 'smallsize' ), '', 'cover' );
		$src 		= wppa_get_thumb_url( 	$coverphoto,
											true,
											'',
											$imgattr_a['width'],
											$imgattr_a['height'],
											wppa_switch( 'cover_use_thumb' )
										);
	}
	else {
		$path 		= '';
		$imgattr_a 	= false;
		$src 		= '';
	}

	// Feed?
	if ( is_feed() ) {
		$events = '';
	}
	else {
		$events = wppa_get_imgevents( 'cover' );
	}

	$photo_pos = wppa( 'coverphoto_pos' );

	$style =  '';
	if ( is_feed() ) $style .= ' padding:7px;';

	$style .= wppa_get_cover_width( 'cover' );
	if ( $cover_count_key == 'm' ) {
		$style .= 'margin-left: 8px;';
	}
	elseif ( $cover_count_key == 'r' ) {
		$style .= 'float:right;';
	}
	else {
		$style .= 'clear:both;';
	}
	wppa_step_covercount( 'cover' );

	$target = '_self';

	// Open the album box
	wppa_out( '
	<div
		id="album-' . $albumid . '-' . wppa( 'mocc' ) . '"
		class="wppa-album-cover-longdesc album wppa-box wppa-cover-box wppa-cover-box-' . $mcr . wppa( 'mocc' ) . '"
		style="' . $style . '">' );

	// First The Cover photo?
	if ( $photo_pos == 'left' || $photo_pos == 'top' ) {
		wppa_the_coverphoto(
			$albumid, $image, $src, $photo_pos, $photolink, $title, $imgattr_a, $events );
	}

	// Open the Cover text frame
	$textframestyle = wppa_get_text_frame_style( $photo_pos, 'cover' );
	wppa_out( '
	<div
		id="covertext_frame_' . $albumid . '_' . wppa( 'mocc' ) . '"
		class="wppa-text-frame-' . wppa( 'mocc' ) . ' wppa-text-frame wppa-cover-text-frame wppa-asym-text-frame-' . $mcr . wppa( 'mocc' ) . '" ' .
		$textframestyle . '>' );

	// The Album title
	wppa_the_album_title( $albumid, $href_title, $onclick_title, $title, $target );

	// The 'Slideshow'/'Browse' link
	wppa_the_slideshow_browse_link( $photocount, $href_slideshow, $onclick_slideshow, $target );

	// The 'View' link
	wppa_album_cover_view_link( $albumid );

	// Close the Cover text frame
	wppa_out( '</div>' );

	// The Cover photo last?
	if ( $photo_pos == 'right' || $photo_pos == 'bottom' ) {
		wppa_the_coverphoto( $albumid, $image, $src, $photo_pos, $photolink, $title, $imgattr_a, $events );
	}

	// The Album description
	if ( wppa_switch( 'show_cover_text' ) ) {
		$textheight = wppa_opt( 'text_frame_height' ) > '0' ? 'min-height:' . wppa_opt( 'text_frame_height' ) . 'px; ' : '';
		wppa_out( '
		<div
			id="coverdesc_frame_' . $albumid . '_' . wppa( 'mocc' ) . '"
			style="clear:both">
			<div
				class="wppa-box-text wppa-black wppa-box-text-desc"
				style="' . $textheight . '">' .
				wppa_get_album_desc( $albumid ) . '
			</div>
		</div>' );
	}

	// The sublinks
	wppa_albumcover_sublinks( $albumid, wppa_get_cover_width( 'cover' ), $multicolresp );

	// Prepare for closing
	wppa_out( '<div style="clear:both;"></div>' );

	// Close the album box
	wppa_out( '</div>' );

}

// The cover type grid
function wppa_album_cover_grid( $id ) {
global $cover_count_key;

	// Init
	$album 	= wppa_cache_album( $id );

	// Find album details
	$coverphoto = wppa_get_coverphoto_id( $id );
	if ( ! $coverphoto ) return;
	$image 		= wppa_cache_photo( $coverphoto );
	$photocount = wppa_get_visible_photo_count( $id );
	$albumcount = wppa_get_visible_album_count( $id );

	// Init links
	$title 				= '';
	$linkpage 			= '';
	$href_title 		= '';
	$onclick_title 		= '';

	// See if there is substantial content to the album
	$has_content = $albumcount || $photocount;

	// What is the albums title linktype
	$linktype = $album['cover_linktype'];

	// If not specified, use default
	if ( ! $linktype ) {
		$linktype = 'content';
	}

	// What is the albums title linkpage
	$linkpage = $album['cover_linkpage'];

	// Fix backward compatibility issue
	if ( $linkpage == '-1' ) {
		$linktype = 'none';
	}

	// Find the cover title href, onclick and title
	$title_attr 	= wppa_get_album_title_attr_a( 	$id,
													$linktype,
													$linkpage,
													$has_content,
													$coverphoto,
													$photocount
												);
	$href_title 	= $title_attr['href'];
	$onclick_title 	= $title_attr['onclick'];
	$title 			= $title_attr['title'];

	// Find the coverphoto link
	if ( $coverphoto ) {
		$photolink = wppa_get_imglnk_a( 	'coverimg',
											$coverphoto,
											$href_title,
											$title,
											$onclick_title,
											'',
											$id
										);
	}
	else {
		$photolink = false;
	}

	// Find the coverphoto details
	$path 		= wppa_get_thumb_path( 	$coverphoto );
	$imgattr_a 	= wppa_get_imgstyle_a( 	$coverphoto,
										$path,
										wppa_opt( 'smallsize' ),
										'',
										'cover'
									);
	$src 		= wppa_get_thumb_url( 	$coverphoto,
										true,
										'',
										$imgattr_a['width'],
										$imgattr_a['height'],
										wppa_switch( 'cover_use_thumb' )
									);

	// Feed?
	if ( is_feed() ) {
		$events  	= '';
	}
	else {
		$events 	= wppa_get_imgevents( 'cover' );
	}

	// Set up album cover style
	$w = wppa_get_container_width();
	if ( $w < 1 ) {
		$w = $w * wppa_opt( 'initial_colwidth' );
	}
	$c = ceil( $w / wppa_opt( 'max_cover_width' ) );
	$style = 'float:left;padding:0;width:' . (100/$c) . '%;margin:0;';

	// Open the album box
	wppa_out( '
	<div
		id="album-' . $id . '-' . wppa( 'mocc' ) . '"
		class="wppa-album-cover-grid-' . wppa( 'mocc' ) . ' album wppa-box wppa-cover-box wppa-cover-box-' . wppa( 'mocc' ) . ' "
		style="' . $style . '">' );

	// The Cover photo
	wppa_the_coverphoto( 	$id,
							$image,
							$src,
							'left',
							$photolink,
							$title,
							$imgattr_a,
							$events,
							true 			// is grid
						);

	// Close the album box
	wppa_out( '</div>' );

}

// A single coverphoto
// Output goes directly to wppa_out()
function wppa_the_coverphoto( $albumid, $image, $src, $photo_pos, $photolink, $title, $imgattr_a = array(), $events = '', $is_grid = false ) {
global $wpdb;

	if ( ! $image ) {
		return;
	}

	$id = $image['id'];

	if ( wppa_has_audio( $id ) ) {
		$src = wppa_fix_poster_ext( $src, $id );
	}

	if ( $is_grid ) {
		$imgattr = 'width:100%;box-sizing:border-box;';
		$imgwidth = '';
		$imgheight = '';
		$frmwidth = '100%;';
	}
	else {
		$imgattr   = isset( $imgattr_a['style'] ) ? $imgattr_a['style'] : '';
		$imgwidth  = isset( $imgattr_a['width'] ) ? $imgattr_a['width'] : '';
		if ( ! $imgwidth ) $imgwidth = '0';
		$imgheight = isset( $imgattr_a['height'] ) ? $imgattr_a['height'] : '';
		if ( ! $imgheight ) $imgheight = '0';
		$frmwidth  = $imgwidth + '10';	// + 2 * 1 border + 2 * 4 padding
	}

	// Find the posterurl if mm and exists
	$thumburl  = wppa_get_thumb_url( $id );
	if ( wppa_is_file( wppa_get_thumb_path( $id ) ) && wppa_is_multi( $id ) ) {
		$posterurl = $thumburl;
	}
	else {
		$posterurl = '';
	}

	// Find the photo frame style
	if ( wppa_in_widget() ) {
		$photoframestyle = 'style="text-align:center; "';
	}
	else {
		if ( wppa_switch( 'coverphoto_responsive' ) ) {
			$framewidth = wppa_opt( 'smallsize_percentage' );
			switch ( $photo_pos ) {
				case 'left':
					$photoframestyle =
						'style="float:left;width:' . $framewidth . '%;height:auto;"';
					break;
				case 'right':
					$photoframestyle =
						'style="float:right;width:' . $framewidth . '%;height:auto;"';
					break;
				case 'top':
				case 'bottom':
					$photoframestyle = 'style="width:' . $framewidth . '%;height:auto;margin:0 auto;"';
					break;
				default:
					$photoframestyle = '';
			}
		}
		else {
			if ( $is_grid ) {
				$photoframestyle = 'style="width:100%;"';
			}
			else {
				switch ( $photo_pos ) {
					case 'left':
						$photoframestyle =
							'style="float:left; margin-right:5px;width:' . $frmwidth . 'px;"';
						break;
					case 'right':
						$photoframestyle =
							'style="float:right; margin-left:5px;width:' . $frmwidth . 'px;"';
						break;
					case 'top':
						$photoframestyle = 'style="text-align:center;"';
						break;
					case 'bottom':
						$photoframestyle = 'style="text-align:center;"';
						break;
					default:
						$photoframestyle = '';
				}
			}
		}
	}

	// Open the coverphoto frame
	wppa_out( '
	<div
		id="coverphoto_frame_' . $albumid . '_' . wppa( 'mocc' ) . '"
		class="coverphoto-frame" ' .
		$photoframestyle . '>' );

		// The indiv thumbnail wrapper
		wppa_out( '<div style="display:inline-block;margin:0 4px;">' );

			// The medal if at the top
			wppa_out( wppa_get_medal_html_a( array( 'id' => $id, 'size' => wppa_opt( 'icon_size_multimedia' ), 'where' => 'top', 'thumb' => true ) ) );


			// The link from the coverphoto
			if ( $photolink ) {

				// If lightbox, we need all the album photos to set up a lightbox set
				if ( $photolink['is_lightbox'] ) {
					$thumbs = $wpdb->get_results( $wpdb->prepare(
						"SELECT * FROM $wpdb->wppa_photos WHERE album = %s " .
						wppa_get_photo_order( $albumid ), $albumid
						), ARRAY_A );

					wppa_cache_photo( 'add', $thumbs );				// Save rsult in 2nd level cache

					if ( $thumbs ) foreach ( $thumbs as $thumb ) {
						$tid = $thumb['id'];
						$title = wppa_get_lbtitle( 'cover', $tid );
						if ( wppa_is_video( $tid ) ) {
							$siz['0'] = wppa_get_videox( $tid );
							$siz['1'] = wppa_get_videoy( $tid );
						}
						else {
							$siz['0'] = wppa_get_photox( $tid );
							$siz['1'] = wppa_get_photoy( $tid );
						}
						$link 		= wppa_get_photo_url( $tid, true, '', $siz['0'], $siz['1'] );
						$is_video 	= wppa_is_video( $tid );
						$has_audio 	= wppa_has_audio( $tid );
						$is_pdf 	= wppa_is_pdf( $tid );

						// Open the anchor tag for lightbox
						wppa_out( '
						<a
							data-id="' . wppa_encrypt_photo( $tid ) . '"
							href="' . $link . '"
							style="border:0;color:transparent;"' .
							( $is_video ? ' data-videohtml="' . esc_attr( wppa_get_video_body( $tid ) ) . '"
											data-videonatwidth="' . wppa_get_videox( $tid ) . '"
											data-videonatheight="' . wppa_get_videoy( $tid ) . '"' : '' ) .
							( $has_audio ? ' data-audiohtml="' . esc_attr( wppa_get_audio_body( $tid ) ) . '"' : '' ) .
							( $is_pdf ? ' data-pdfhtml="' . esc_attr( wppa_get_pdf_html( $tid ) ) . '"' : '' ) . '
							data-rel="wppa[alw-' . wppa( 'mocc' ) . '-' . $albumid . ']"
							data-lbtitle' . '="' . $title . '" ' .
							wppa_get_lb_panorama_full_html( $tid ) . '
							data-alt="' . esc_attr( wppa_get_imgalt( $tid, true ) ) . '"
							style="cursor:' . wppa_wait() . ';"
							onclick="return false;">' );

						// the cover image
						if ( $tid == $id ) {
							if ( wppa_is_video( $tid ) ) {
								wppa_out( '
								<video
									preload="metadata"
									class="image wppa-img" id="i-' . $tid . '-' . wppa( 'mocc' ) . '"
									title="' . wppa_zoom_in( $tid ) . '"
									style="' . $imgattr . '" ' .
									$events . '>' .
									wppa_get_video_body( $tid ) . '
								</video>' );
							}
							else {
								wppa_out( '
								<img
									class="image wppa-img" id="i-' . $tid . '-' . wppa( 'mocc' ) . '"
									title="' . wppa_zoom_in( $tid ) . '"
									src="' . $src . '"
									style="' . $imgattr . '" ' .
									$events . ' ' .
									wppa_get_imgalt( $tid ) . '
								>' );
							}
						}

						// Close the lightbox anchor tag
						wppa_out( '</a>' );
					}
				}

				// Link is NOT lightbox
				else {
					$url = $photolink['url'] == '#' ? '' : wppa_convert_to_pretty( $photolink['url'] );
					wppa_out( '
					<a
						style="border:0;color:transparent;cursor:pointer;"' .
						( $url ? ' href="' . $url . '"' : '' ) . '
						target="' . $photolink['target'] . '"
						title="' . $photolink['title'] . '"
						onclick="' . $photolink['onclick'] . '">' );

					// A video?
					if ( wppa_is_video( $id ) ) {
						wppa_out( '
						<video
							preload="metadata"
							title="' . $title . '"
							class="image wppa-img"
							style="' . $imgattr . '" ' .
							$events .
							( $posterurl ? ' poster="' . esc_url( $posterurl ) . '"' : '' ) . '
							>' .
							wppa_get_video_body( $id ) . '
						</video>' );
					}

					// A photo
					else {
						wppa_out( '
						<img
							src="' . $src . '" ' .
							wppa_get_imgalt( $id ) . '
							class="image wppa-img"
							style="' . $imgattr . '" ' .
							$events . '
						>' );
					}
					wppa_out( '</a>' );
				}
			}

			// No link on coverphoto
			else {

				// A video?
				if ( wppa_is_video( $id ) ) {
					wppa_out( '
					<video
						preload="metadata"
						class="image wppa-img"
						style="' . $imgattr . '" ' .
						$events .
						( $posterurl ? ' poster="' . esc_url( $posterurl ) . '"' : '' ) . '
						>' .
						wppa_get_video_body( $id ) . '
					</video>' );
				}

				// A photo
				else {
					wppa_out( '
					<img
						src="' . $src . '" ' .
						wppa_get_imgalt( $id ) . '
						class="image wppa-img"
						style="' . $imgattr . '" ' .
						$events . '
					>' );
				}
			}

			// The medals if near the bottom
			wppa_out( wppa_get_medal_html_a( array( 'id' => $id, 'size' => wppa_opt( 'icon_size_multimedia' ), 'where' => 'bot', 'thumb' => true ) ) );

		// Close the indiv wrapper
		wppa_out( '</div>' );

		// Viewcount on coverphoto?
		if ( wppa_opt( 'viewcount_on_cover' ) != '-none-' ) {
			$treecounts = wppa_get_treecounts_a( $albumid, true );
			if ( wppa_opt( 'viewcount_on_cover' ) == 'self' || $treecounts['selfphotoviews'] == $treecounts['treephotoviews'] ) {
				$count = $treecounts['selfphotoviews'];
				$title = __( 'Number of photo views in this album', 'wp-photo-album-plus' );
			}
			else {
				$count = $treecounts['treephotoviews'];
				$title = __( 'Number of photo views in this album and its sub albums', 'wp-photo-album-plus' );
			}
			wppa_out( '
			<div
				class="wppa-album-cover-viewcount"
				title="' . esc_attr( $title ) . '"
				style="cursor:pointer">' .
				__( 'Views:', 'wp-photo-album-plus' ) . ' ' . $count . '
			</div>' );
		}

	// Close the coverphoto frame
	wppa_out( '</div>' );
}

// Multiple coverphotos
// Output goes directly to wppa_out()
function wppa_the_coverphotos( $albumid, $images, $srcs, $photo_pos, $photolinks, $title, $imgattrs_a, $events ) {

	if ( ! $images ) {
		return;
	}

	// Open the coverphoto frame
	wppa_out( '
	<div
		id="coverphoto_frame_' . $albumid . '_' . wppa( 'mocc' ) . '"
		class="coverphoto-frame"
		style="text-align:center; "
		>' );

	// Process the images
	$n = count( $images );
	for ( $idx='0'; $idx < $n; $idx++ ) {

		$image 		= $images[$idx];
		$src 		= $srcs[$idx];
		$id 		= $image['id'];

		if ( wppa_has_audio( $id ) ) {
			$src = wppa_fix_poster_ext( $src, $id );
		}

		$imgattr   	= $imgattrs_a[$idx]['style'];
		$imgwidth  	= $imgattrs_a[$idx]['width'];
		$imgheight 	= $imgattrs_a[$idx]['height'];
		$frmwidth  	= $imgwidth + '10';	// + 2 * 1 border + 2 * 4 padding
		$imgattr_a	= $imgattrs_a[$idx];
		$photolink 	= $photolinks[$idx];

		if ( wppa_switch( 'coverphoto_responsive' ) ) {
			$width = ( $n == 1 ? wppa_opt( 'smallsize_percentage' ) : wppa_opt( 'smallsize_multi_percentage' ) );
			if ( wppa_switch( 'coversize_is_height' ) ) {
				$width = $width * ( $imgwidth / $imgheight );
			}
			elseif ( $imgwidth < $imgheight ) {
				$width = $width * ( $imgwidth / $imgheight );
			}
			$imgattr = 'width:' . $width . '%;height:auto;box-sizing:content-box;';
		}

		// The indiv thumbnail wrapper
		wppa_out( '<div style="display:inline-block;margin:0 4px;">' );

			// The medal if at the top
			wppa_out( wppa_get_medal_html_a( array( 'id' => $id, 'size' => wppa_opt( 'icon_size_multimedia' ), 'where' => 'top', 'thumb' => true ) ) );


			if ( $photolink ) {
				if ( $photolink['is_lightbox'] ) {
					$thumb = $image;
					$title = wppa_get_lbtitle( 'cover', $thumb['id'] );
					if ( wppa_is_video( $thumb['id'] ) ) {
						$siz['0'] = wppa_get_videox( $thumb['id'] );
						$siz['1'] = wppa_get_videoy( $thumb['id'] );
					}
					else {
						$siz['0'] = wppa_get_photox( $thumb['id'] );
						$siz['1'] = wppa_get_photoy( $thumb['id'] );
					}

					$link 		= wppa_get_photo_url( $thumb['id'], true, '', $siz['0'], $siz['1'] );
					$is_video 	= wppa_is_video( $thumb['id'] );
					$has_audio 	= wppa_has_audio( $thumb['id'] );
					$is_pdf 	= wppa_is_pdf( $thumb['id'] );

					wppa_out( '
					<a
						data-id="' . wppa_encrypt_photo( $thumb['id'] ) . '"
						href="' . $link . '"
						style="border:0;color:transparent;cursor:wait;"' .
						( $is_video ? ' data-videohtml="' . esc_attr( wppa_get_video_body( $thumb['id'] ) ) . '"
										data-videonatwidth="' . wppa_get_videox( $thumb['id'] ) . '"
										data-videonatheight="' . wppa_get_videoy( $thumb['id'] ) . '"' : '' ) .
						( $has_audio ? ' data-audiohtml="' . esc_attr( wppa_get_audio_body( $thumb['id'] ) ) . '"' : '' ) .
						( $is_pdf ? ' data-pdfhtml="' . esc_attr( wppa_get_pdf_html( $thumb['id'] ) ) .'"' : '' ) . '
						data-rel="wppa[alw-' . wppa( 'mocc' ) . '-' . $albumid . ']"' .
						( $title ? ' data-lbtitle' . '="' . $title . '" ' : ' ' ) .
						wppa_get_lb_panorama_full_html( $thumb['id'] ) . '
						data-alt="' . esc_attr( wppa_get_imgalt( $thumb['id'], true ) ) . '"
						>' );

					// the cover image
					if ( $thumb['id'] == $id ) {
						if ( wppa_is_video( $id ) ) {
							wppa_out( '
							<video
								preload="metadata"
								class="image wppa-img"
								id="i-' . $id . '-' . wppa( 'mocc' ) . '"
								title="' . wppa_zoom_in( $id ) . '"
								style="' . $imgattr . '" ' .
								$events . '
								>' .
								wppa_get_video_body( $id ) . '
							</video>' );
						}
						else {
							wppa_out( '
							<img
								class="image wppa-img"
								id="i-' . $id . '-' . wppa( 'mocc' ) . '"
								title="' . wppa_zoom_in( $id ) . '"
								src="' . $src . ' "' .
								wppa_get_imgalt( $id ) . '
								style="' . $imgattr . '" ' .
								$events . '
							>' );
						}
					}
					wppa_out( '</a> ' );
				}

				else {	// Link is NOT lightbox
					$url = $photolink['url'] == '#' ? '' : $photolink['url'];

					wppa_out( '
					<a
						style="border:0;color:transparent;"' .
						( $url ? ' href="' . $url . '"': '' ) . '
						target="' . $photolink['target'] . '"
						title="' . $photolink['title'] . '"
						onclick="' . $photolink['onclick'] . '"
						>' );

					if ( wppa_is_video( $id ) ) {

						wppa_out( '
						<video
							preload="metadata"
							class="image wppa-img"
							style="' . $imgattr . '" ' .
							$events . '
							>' .
							wppa_get_video_body( $id ) . '
						</video>' );
					}
					else {

						wppa_out( '
						<img
							src="' . $src . '" ' .
							wppa_get_imgalt( $id ) . '
							class="image wppa-img"
							style="' . $imgattr . '" ' .
							$events . '
						>' );
					}
					wppa_out( '</a> ' );
				}
			}

			// No link on coverphoto
			else {

				// A video?
				if ( wppa_is_video( $id ) ) {
					wppa_out( '
					<video
						preload="metadata"
						class="image wppa-img"
						style="' . $imgattr . '" ' .
						$events . '
						>' .
						wppa_get_video_body( $id ) . '
					</video>' );
				}

				// A photo
				else {
					wppa_out( '
					<img
						src="' . $src . '" ' .
						wppa_get_imgalt( $id ) . '
						class="image wppa-img"
						style="' . $imgattr . '" ' .
						$events . '
					>' );
				}
			}

			// The medals if near the bottom
			wppa_out( wppa_get_medal_html_a( array( 'id' => $id, 'size' => wppa_opt( 'icon_size_multimedia' ), 'where' => 'bot', 'thumb' => true ) ) );

		// Close the indiv wrapper
		wppa_out( '</div>' );

	}

	// Close the coverphoto frame
	wppa_out( '</div>' );
}

// get id of coverphoto. does all testing
function wppa_get_coverphoto_id( $xalb = '' ) {

	// See if current photo still exists, and is not deleted pending removal
	$current = wppa_get_album_item( $xalb, 'main_photo' );
	if ( $current > '0' ) {
		if ( ! wppa_photo_exists( $current ) ||
			 ! wppa_is_photo_visible( $current ) ||
			 wppa_is_photo_deleted( $current ) ) {
			wppa_update_album( $xalb, ['main_photo' => '0'] );
		}
		else {
			return $current;
		}
	}

	// See if random photo needs to be saved
	$save_it = false;
	if ( wppa_switch( 'main_photo_random_once' ) ) {
		$m_id = wppa_get_album_item( $xalb, 'main_photo' );
		if ( $m_id == '0' ) $m_id = wppa_opt( 'main_photo' );
		if ( $m_id == '-3' || $m_id == '-9' ) {
			$save_it = true;
		}
	}

	$result = wppa_get_coverphoto_ids( $xalb, '1' );

	if ( empty( $result ) ) {
		return false;
	}

	if ( $save_it ) {
		$status = wppa_get_photo_item( $result['0'], 'status' );
		if ( ! in_array( $status,['scheduled','private','pending'] ) ) {
			wppa_update_album( $xalb, ['main_photo' => $result['0']] );
			wppa_cache_album( 'invalidate', $xalb );
		}
	}
	return $result['0'];
}

// Get the cover photo id(s)
// The id in the album may be 0: random, -1: featured random; -2: last upload; > 0: one assigned specific.
// If one assigned but no longer exists or moved to other album: treat as random
function wppa_get_coverphoto_ids( $alb, $count ) {
global $wpdb;
static $cached_cover_photo_ids;

	// no album, no coverphoto
	if ( ! $alb ) return false;

	// Did we do this before? ( for non-imgfact only )
	if ( $count == '1' && isset( $cached_cover_photo_ids[$alb] ) ) {
		return $cached_cover_photo_ids[$alb];
	}

	// Find cover photo id
	$id = wppa_get_album_item( $alb, 'main_photo' );

	// main_photo is a positive integer ( photo id )?
	if ( $id > '0' ) {									// 1 coverphoto explicitly given
		$photo = wppa_cache_photo( $id );
		if ( ! $photo ) {								// Photo gone, set id to 0
			$id = '0';
		}
		elseif ( $photo['album'] != $alb ) {			// Photo moved to other album, set id to 0
			$id = '0';
		}
		else {
			$temp['0'] = $photo;						// Found!
		}
	}

	// Make the private clause
	if ( wppa_user_is_admin() ) {
		$non_private = '';
	}
	elseif ( is_user_logged_in() ) {
		$non_private = "AND status NOT IN ('pending','scheduled') ";
	}
	else {
		$non_private = "AND status NOT IN ('pending','scheduled','private') ";
	}

	// Other inits
	$user   = wppa_get_user();
	$rand   = wppa_get_randseed( 'page' );
	$allalb = str_replace( '.', ',', wppa_expand_enum( wppa_alb_to_enum_children( $alb ) ) );

	// main_photo is 0? Default
	if ( '0' == $id ) {
		$id = wppa_opt( 'main_photo' );
	}

	// Invalid value? -> -9
	if ( $id < '0' && ! in_array( $id, array( '-9', '-1', '-2', '-3', '-4', '-5' ) ) ) {
		$id = '-9';
	}

	// main_photo is -9: Random
	if ( '-9' == $id ) {
		$rs = wppa_get_randseed( 'page' );
		if ( current_user_can( 'wppa_moderate' ) ) {
			$temp = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														 WHERE album = %s
														 $non_private
														 ORDER BY RAND(%d)
														 LIMIT %d", $alb, $rs, $count ), ARRAY_A );
		}
		else {
			$temp = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														 WHERE album = %s
														 $non_private
														 AND ( ( status <> 'pending' AND status <> 'scheduled' ) OR owner = %s )
														 ORDER BY RAND(%d)
														 LIMIT %d", $alb, $rs, $user, $count ), ARRAY_A );
		}
	}

	// main_photo is -2? Last upload
	if ( '-2' == $id ) {
		if ( current_user_can( 'wppa_moderate' ) ) {
			$temp = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														 WHERE album = %s
														 $non_private
														 ORDER BY timestamp DESC
														 LIMIT %d", $alb, $count ), ARRAY_A );
		}
		else {
			$temp = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														 WHERE album = %s
														 $non_private
														 AND ( ( status <> 'pending' AND status <> 'scheduled' ) OR owner = %s )
														 ORDER BY timestamp DESC
														 LIMIT %d", $alb, $user, $count ), ARRAY_A );
		}
	}

	// main_phtot is -1? Random featured
	if ( '-1' == $id ) {
		$temp = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
													 WHERE album = %s AND status = 'featured'
													 ORDER BY RAND(%d) LIMIT %d",$alb, $rand, $count ), ARRAY_A );
	}

	// Random from children
	if ( '-3' == $id ) {
		$temp = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
													 WHERE album IN ( $allalb )
													 $non_private
													 AND ( ( status <> 'pending' AND status <> 'scheduled' ) OR owner = %s )
													 ORDER BY RAND( $rand )
													 LIMIT %d", $user, $count ), ARRAY_A );
	}

	// Most recent from children
	if ( '-4' == $id ) {

		$temp = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
													 WHERE album IN ( $allalb )
													 $non_private
													 AND ( ( status <> 'pending' AND status <> 'scheduled' ) OR owner = %s )
													 ORDER BY timestamp DESC LIMIT %d", $user, $count ), ARRAY_A );
	}

	// Imagefactory multiple like album photo order
	if ( '-5' == $id ) {
		$order = wppa_get_photo_order( $alb );
		if ( current_user_can( 'wppa_moderate' ) ) {
			$temp = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														 WHERE album = %s
														 $non_private
														 $order
														 LIMIT %d", $alb, $count ), ARRAY_A );
		}
		else {
			$temp = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														 WHERE album = %s
														 $non_private
														 AND ( ( status <> 'pending' AND status <> 'scheduled' ) OR owner = %s )
														 $order
														 LIMIT %d", $alb, $user, $count ), ARRAY_A );
		}
	}

	// Add to 2nd level cache
	wppa_cache_photo( 'add', $temp );

	// Extract the ids only and verify the existence
	$ids = array();
	if ( is_array( $temp ) ) foreach ( $temp as $item ) {
		$ids[] = $item['id'];
		if ( ! wppa_is_file( wppa_get_thumb_path( $item['id'] ) ) ) {
			wppa_create_thumbnail( $item['id'] );
		}
	}

	$cached_cover_photo_ids[$alb] = $ids;

	return $ids;
}

// Find the cover Title's href, onclick and title
function wppa_get_album_title_attr_a( $albumid, $linktype, $linkpage, $has_content, $coverphoto, $photocount ) {

	$album = wppa_cache_album( $albumid );
	if ( $album['cover_linkpage'] > '0' ) {
		$page_link = get_page_link( $album['cover_linkpage'] );
	}
	else {
		$page_link = '';
	}

	// Init
	$href_title 	= '';
	$onclick_title 	= '';
	$title_title 	= '';

	// Special case: manual enterd url
	if ( $linktype == 'manual' ) {
		$title_attr['href'] 	= $album['cover_link'];
		$title_attr['onclick'] 	= '';
		$title_attr['title'] 	= '';
		return $title_attr;
	}

	// Dispatch on linktype when page is not current
	if ( $linkpage > 0 ) {
		switch ( $linktype ) {
			case 'content':
			case 'thumbs':
			case 'albums':
				if ( $has_content ) {
					$href_title = wppa_get_album_url( array( 'album' => $albumid,
															 'page' => $linkpage,
															 'type' => $linktype ) );
				}
				else {
					$href_title = $page_link;
				}
				break;
			case 'slide':
				if ( $has_content ) {
					$href_title = wppa_get_slideshow_url( array( 'album' => $albumid,
																 'page' => $linkpage ) );
				}
				else {
					$href_title = $page_link;
				}
				break;
			case 'page':
				$href_title = $page_link;
				break;
			default:
				break;
		}
		$href_title = wppa_convert_to_pretty( $href_title );
		$title_title = __( 'Link to' , 'wp-photo-album-plus' );
		$title_title .= ' ' . __( get_the_title( $album['cover_linkpage'] ) );
	}

	// Dispatch on linktype when page is current
	elseif ( $has_content ) {
		switch ( $linktype ) {
			case 'content':
			case 'thumbs':
			case 'albums':
				$href_title = wppa_get_album_url( array( 'album' => $albumid,
														 'page' => $linkpage,
														 'type' => $linktype ) );
				$ajax_title = wppa_get_album_url_ajax( array( 'album' => $albumid,
															  'page' => $linkpage,
															  'type' => $linktype ) );

				$onclick_title = "wppaDoAjaxRender( " . wppa( 'mocc' ) . ", '" . $ajax_title . "', '" . $href_title . "' )";
				$href_title = "#";
				break;
			case 'slide':
				$href_title = wppa_get_slideshow_url( array( 'album' => $albumid,
															 'page' => $linkpage ) );
				$ajax_title = wppa_get_slideshow_url_ajax( array( 'album' => $albumid,
																  'page' => $linkpage,  ) );

				$onclick_title = "wppaDoAjaxRender( " . wppa( 'mocc' ) . ", '" . $ajax_title . "', '" . $href_title . "' )";
				$href_title = "#";
				break;
			default:
				break;
		}
		$title_title =
			__( 'View the album' , 'wp-photo-album-plus' ) . ' ' . esc_attr( __( stripslashes( $album['name'] ) ) );
	}
	else {	// No content on current page/post
		if ( $photocount > '0' ) {	// coverphotos only
			if ( $coverphoto ) {
				$href_title = wppa_convert_to_pretty( wppa_encrypt_url( wppa_get_image_page_url_by_id( $coverphoto ) ) );
			}
			else {
				$href_title = '#';
			}

				if ( $coverphoto ) {
					$onclick_title = "wppaDoAjaxRender( " . wppa( 'mocc' ) . ", '" .
						wppa_encrypt_url( wppa_get_image_url_ajax_by_id( $coverphoto ) ) . "', '" . $href_title . "' )";
				}
				else {
					$onclick_title = '';
				}
				$href_title = "#";

			$title_title = _n( 'View the cover photo', 'View the cover photos' , $photocount, 'wp-photo-album-plus' );
		}
	}
	$title_attr['href'] 	= wppa_encrypt_url( $href_title );
	$title_attr['onclick'] 	= wppa_encrypt_url( $onclick_title );
	$title_attr['title'] 	= $title_title;

	return $title_attr;
}

// The 'View' link
function wppa_album_cover_view_link( $id ) {

	// Anything to do?
	if ( ! wppa_switch( 'show_viewlink' ) ) return;

	// Find essential data
	$type 		= wppa_get_album_item( $id, 'cover_type' ) or wppa_opt( 'cover_type' );
	$album 		= wppa_cache_album( $id );
	$treecount 	= wppa_get_treecounts_a( $id );
	$class 		= 'wppa-box-text wppa-black wppa-info wppa-viewlink wppa-album-cover-link';
	if (  $type == 'imagefactory' || $type == 'imagefactory-mcr' ) {
		$class 	= 'wppa-box-text wppa-black wppa-info wppa-viewlink-sym wppa-album-cover-link';
	}

	$na  = strval( wppa_get_visible_album_count( $id ) );
	$np  = strval( wppa_get_visible_photo_count( $id ) );
	$nta = strval( wppa_get_visible_subtree_album_count( $id ) );
	$ntp = strval( wppa_get_visible_subtree_photo_count( $id ) );

	// Anything to show?
	if ( ! $na && ! $np && ! $nta && ! $ntp ) {
		return;
	}

	// Find the content 'View' link href and ajax url and onclick
	$page = max( $album['cover_linkpage'], '0' );
	$href = wppa_get_album_url( array( 'album' => $id, 'page' => $page ) );
	$ajax = wppa_get_album_url_ajax( array( 'album' => $id, 'page' => $page ) );
	$onclick = '';
	if ( ! $page ) {
		$onclick = "wppaDoAjaxRender( " . wppa( 'mocc' ) . ", '" . $ajax . "', '" . $href . "' )";
		$href = "#";
	}
	$target = '_self';

	// Open the div
	wppa_out( '<div class="' . $class . '">' );

		// Ajax	link
		if ( $href == '#' ) {
			wppa_out( '
			<a
				class="wppa-album-cover-link"
				onclick="' . $onclick . '"
				title="' . __( 'View the album' , 'wp-photo-album-plus' ) . ' ' . esc_attr( stripslashes( __( $album['name'] ) ) ) . '"
				style="cursor:pointer"
				>' );
		}

		// Non ajax
		else {
			wppa_out( '
			<a
				class="wppa-album-cover-link"
				href="' . $href . '"
				target="' . $target . '"
				onclick="' . $onclick . '"
				title="' . __( 'View the album' , 'wp-photo-album-plus' ) . ' ' . esc_attr( stripslashes( __( $album['name'] ) ) ) . '"
				style="cursor:pointer"
				>' );
		}

		// Make the link text
		$text = '';
		switch( wppa_opt( 'show_treecount' ) ) {

			case '-none-':
				if ( $na && $np ) {
					$text .= sprintf( _n( 'View %d album', 'View %d albums', $na, 'wp-photo-album-plus' ), $na ) . ' ';
					$text .= sprintf( _n( 'and %d photo', 'and %d photos', $np, 'wp-photo-album-plus' ), $np );
				}
				elseif ( $na ) {
					$text .= sprintf( _n( 'View %d album', 'View %d albums', $na, 'wp-photo-album-plus' ), $na );
				}
				elseif ( $np ) {
					$text .= sprintf( _n( 'View %d photo', 'View %d photos', $np, 'wp-photo-album-plus' ), $np );
				}
				break;

			case 'detail':
				if ( $na ) {
					if ( $na ) {
						$text .= sprintf( _n( 'View %d album', 'View %d albums', $na, 'wp-photo-album-plus' ), $na ) . ' ';
					}
					if ( $nta && $na != $nta ) {
						$text .= '(' . $nta . ') ';
					}
					if ( $np || $ntp ) {
						$text .= sprintf( _n( 'and %d photo', 'and %d photos', $np, 'wp-photo-album-plus' ), $np ) . ' ';
					}
					if ( $ntp && $np != $ntp ) {
						$text .= '(' . $ntp . ')';
					}
				}
				else {
					if ( $np || $ntp ) {
						$text .= sprintf( _n( 'View %d photo', 'View %d photos', $np, 'wp-photo-album-plus' ), $np ) . ' ';
					}
					if ( $ntp && $np != $ntp ) {
						$text .= '(' . $ntp . ')';
					}
				}
				break;

			case 'total':
				if ( $nta && $np ) {
					$text .= sprintf( _n( 'View %d album', 'View %d albums', $nta, 'wp-photo-album-plus' ), $nta ) . ' ';
					$text .= sprintf( _n( 'and %d photo', 'and %d photos', $np, 'wp-photo-album-plus' ), $np );
				}
				elseif ( $nta ) {
					$text .= sprintf( _n( 'View %d album', 'View %d albums', $nta, 'wp-photo-album-plus' ), $nta );
				}
				elseif ( $np ) {
					$text .= sprintf( _n( 'View %d photo', 'View %d photos', $np, 'wp-photo-album-plus' ), $np );
				}
				break;
		}

		wppa_out( str_replace( ' ', '&nbsp;', wppa_album_to_gallery( $text ) ) );

		wppa_out( '</a>' );

	wppa_out( '</div>' );
}

function wppa_the_album_title( $alb, $href_title, $onclick_title, $title, $target ) {

	$album = wppa_cache_album( $alb );

	$album_title = wppa_get_album_name( $alb );

	wppa_out( '<h2 class="wppa-title" style="clear:none;">' );

		if ( $href_title ) {
			if ( $href_title == '#' ) {
				wppa_out( '
				<a
					onclick="' . $onclick_title . '"
					title="' . $title . '"
					class="wppa-title"
					style="cursor:pointer">' .
					$album_title . '
				</a>' );
			}
			else {
				wppa_out( '
				<a
					href="' . $href_title . '"
					target="' . $target . '"
					onclick="' . $onclick_title . '"
					title="' . $title . '"
					class="wppa-title">' .
					$album_title . '
				</a>' );
			}
		}
		else {
			wppa_out( $album_title );
		}
	wppa_out( '</h2>' );

	// Photo count?
	if ( wppa_opt( 'count_on_title' ) != '-none-' ) {
		if ( wppa_opt( 'count_on_title' ) == 'self' ) {
			$cnt = wppa_get_visible_photo_count( $alb );
		}
		if ( wppa_opt( 'count_on_title' ) == 'total' ) {
			$temp = wppa_get_treecounts_a( $alb );
			$cnt = $temp['treephotos'];
			if ( current_user_can( 'wppa_moderate' ) ) {
				$cnt += $temp['pendtreephotos'];
			}
		}
		if ( $cnt ) {
			wppa_out( '
			<span
				class="wppa-cover-pcount"
				style="cursor:pointer"
				title="' . esc_attr( __( 'Number of items', 'wp-photo-album-plus' ) ) . '"
				>
				(' . $cnt . ')
			</span>' );
		}
	}

	$fsize = '12';
	if ( wppa_is_album_new( $alb ) ) {
		$type = 'new';
		$attr = __( 'New', 'wp-photo-album-plus' );
	}
	elseif ( wppa_is_album_modified( $alb ) ) {
		$type = 'mod';
		$attr = __( 'Modified', 'wp-photo-album-plus' );
	}
	else {
		$type = '';
	}

	$do_image =  ! wppa_switch( 'new_mod_label_is_text' );

	if ( $type ) {
		if ( $do_image ) {
			wppa_out( '
			<img
				src="' . wppa_opt($type.'_label_url') . '"
				title="' . esc_attr( $attr ) . '"
				alt="' . esc_attr( $attr ) . '"
				class="wppa-albumnew wppa-'.$type.'-image"
				style="border:none;margin:0;padding:0;box-shadow:none;"
			/>&nbsp;' );
		}
		else {
			wppa_out( '
			<div
				class="wppa-'.$type.'-text"
				style="
					display:inline;
					box-sizing:border-box;
					font-size:' . $fsize . 'px;
					line-height:' . $fsize . 'px;
					font-family:\'Arial Black\', Gadget, sans-serif;
					border-radius:4px;
					border-width:2px;
					border-style:solid;' .
					wppa_get_text_medal_color_style( $type, '2' ) . '"
			>&nbsp;' . __( wppa_opt( $type.'_label_text' ) ) . '&nbsp;</div>&nbsp;'	);
		}
	}

	// Album id?
	$show = wppa_opt( 'albumid_on_cover' );
	$edit = wppa_have_access( $alb ) && current_user_can( 'wppa_admin' );
	if ( $show == 'all' || ( $show == 'access' && $edit ) ) {
		if ( wppa_switch( 'fe_albid_edit' ) && $edit ) {
			$href = get_admin_url() . 'admin.php?page=wppa_admin_menu&wppa-nonce=' . wp_create_nonce( 'wppa-nonce' ) . '&tab=edit&edit-id=' . $alb;
			$album_id = '
			<a
				href="' . $href . '"
				target="_blank"
				class="wppa-cover-album-id"
				style="cursor:pointer"
				title="' . esc_attr( __( 'Edit Album', 'wp-photo-album-plus' ) ) . '"
				>
				(' . $alb . ')
			</a>';
		}
		else {
			$album_id = '
			<span
				class="wppa-cover-album-id"
				style="cursor:help"
				title="' . esc_attr( __( 'Album id', 'wp-photo-album-plus' ) ) . '"
				>
				(' . $alb . ')
			</span>';
		}
	}
	else {
		$album_id = '';
	}
	wppa_out( $album_id );
}

function wppa_albumcover_sublinks( $id, $width, $rsp ) {

	wppa_subalbumlinks_html( $id );
	wppa_user_destroy_html( $id, $width, 'cover', $rsp );
	wppa_user_create_html( $id, $width, 'cover', $rsp );
	wppa_user_upload_html( $id, $width, 'cover', $rsp );
	wppa_user_albumedit_html( $id, $width, 'cover', $rsp );
	wppa_album_download_link( $id );
	wppa_the_album_cats( $id );
}

function wppa_subalbumlinks_html( $id, $top = true ) {
global $wpdb;

	// Do they need us? Anything to display?
	if ( wppa_opt( 'cover_sublinks_display' ) == 'none' ) {
		return;
	}

	// Display type
	$display_type = wppa_opt( 'cover_sublinks_display' );

	// Link type
	$link_type = wppa_opt( 'cover_sublinks' );

	// Init
	$is_list = ( $display_type == 'list' || $display_type == 'recursivelist' );
	$is_recursive = $display_type == 'recursivelist';
	$first = true;

	// Get the albums sort order column
	$albumorder_col	= wppa_get_album_order_column( $id );

	// If random...
	if ( $albumorder_col == 'random' ) {

		$query  = "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %d ORDER BY RAND(%d)";

		$subs = $wpdb->get_results( $wpdb->prepare( $query, $id, wppa_get_randseed() ), ARRAY_A );
	}

	// Not random, Decending?
	else if ( wppa_is_album_order_desc( $id ) ) {

		switch ( $albumorder_col ) {

			case 'a_order':
				$query = "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %d ORDER BY a_order DESC";
				break;
			case 'name':
				$query = "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %d ORDER BY name DESC";
				break;
			case 'timestamp':
				$query = "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %d ORDER BY timestamp DESC";
				break;
			default:
				$query = "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %d ORDER BY id DESC";

		}

		$subs = $wpdb->get_results( $wpdb->prepare( $query, $id ), ARRAY_A );
	}

	// Not descending
	else {

		switch ( $albumorder_col ) {

			case 'a_order':
				$query = "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %d ORDER BY a_order";
				break;
			case 'name':
				$query = "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %d ORDER BY name";
				break;
			case 'timestamp':
				$query = "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %d ORDER BY timestamp";
				break;
			default:
				$query = "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %d ORDER BY id";

		}

		$subs = $wpdb->get_results( $wpdb->prepare( $query, $id ), ARRAY_A );
	}

	// Only if there are sub albums
	if ( ! empty( $subs ) ) {

		wppa_out( '<div class="wppa-cover-sublist-container" >' );

		// Start list if required
		if ( $is_list ) {
			wppa_out( '
			<ul
				class="wppa-cover-sublink-list"
				style="clear:both;margin:0;list-style-type:disc;list-style-position:inside;padding:0 0 0 24px;"
				>' );
		}
		else {
			wppa_out( '<div style="clear:both"></div>' );
		}

		// Process the sub albums
		foreach( $subs as $album ) {

			// The album id
			$albumid = $album['id'];

			// This sub album visible for this user?
			if ( wppa_is_album_visible( $albumid ) ) {

				// What is in there visible?
				$pc = wppa_get_visible_photo_count( $albumid );
				$ac = wppa_get_visible_album_count( $albumid );

				// What is the albums title linktype
				$linktype = $album['cover_linktype'];
				if ( ! $linktype ) $linktype = 'content'; // Default

				// What is the albums title linkpage
				$linkpage = $album['cover_linkpage'];
				if ( $linkpage == '-1' ) $linktype = 'none'; // for backward compatibility

				// Find the content 'View' link
				$albumid 			= $album['id'];
				$photocount 		= $pc;

				// Thumbnails and covers, show sub album covers
				// in case slideshow is requested on an empty album
				if ( wppa_opt( 'cover_sublinks' ) == 'content' || ! $photocount ) {
					$href_content 		= wppa_get_album_url( array( 'album' => $albumid,
																	 'page' => $linkpage ) );
					$ajax_content 		= wppa_get_album_url_ajax( array( 'album' => $albumid,
																		  'page' => $linkpage ) );

					$onclick_content 	= "wppaDoAjaxRender( " . wppa( 'mocc' ) . ", '" . $ajax_content . "', '" . $href_content . "' )";

					$title = esc_attr( __( 'View the album', 'wp-photo-album-plus' ) . ': ' . wppa_get_album_name( $albumid ) );
				}

				// Slideshow
				elseif ( wppa_opt( 'cover_sublinks' ) == 'slide' ) {
					$href_content 		= wppa_get_slideshow_url( array( 'album' => $albumid,
																		 'page' => $linkpage ) );
					$ajax_content		= wppa_get_slideshow_url_ajax( array( 'album' => $albumid,
																			  'page' => $linkpage ) );

					$onclick_content 	= "wppaDoAjaxRender( " . wppa( 'mocc' ) . ", '" . $alax_content . "', '" . $href_content . "' )";

					$title = esc_attr( __( 'View the album', 'wp-photo-album-plus' ) . ': ' . wppa_get_album_name( $albumid ) );
				}

				// Sub album title link
				elseif ( wppa_opt( 'cover_sublinks' ) == 'title' ) {
					$sub_attr 	= wppa_get_album_title_attr_a( 	$albumid,
																$album['cover_linktype'],
																$album['cover_linkpage'],
																( $pc + $ac > 0 ),
																false,
																$pc
																);
					$href_content 		= $sub_attr['href'];
					$onclick_content 	= $sub_attr['onclick'];
					$title 				= $sub_attr['title'];
				}

				// None
				else {
					$href_content 		= '';
					$onclick_content 	= '';
					$title 				= '';
				}

				// Physical empty? Even no link for admin, except when empty thumblist requested and upload link on thumblist
				if ( ! $pc && ! $ac ) {
					if ( ! wppa_switch( 'show_empty_thumblist' ) || wppa_opt( 'upload_link_thumbs' ) == 'none' ) {
						$href_content 		= '';
						$onclick_content 	= '';
						$title 				= __( 'This album is empty', 'wp-photo-album-plus' );
					}
				}

				// Do the output
				switch( $display_type ) {
					case 'list':
					case 'recursivelist':
						if ( $link_type == 'none' ) {
							wppa_out( '
							<li style="margin:0;cursor:pointer">' .
								wppa_get_album_name( $album['id'] ) . '
							</li>' );
						}
						else {
							wppa_out( '
							<li style="margin:0;cursor:pointer">
								<a' .
									( $href_content && ! $onclick_content ? ' href="' . $href_content . '"' : '' ) .
									( $onclick_content ? ' onclick="' . $onclick_content . '"' : '' ) . '
									title="' . $title . '"
									style="cursor:pointer"
									>' .
									wppa_get_album_name( $album['id'] ) . '
								</a>
							</li>' );
						}
						break;
					case 'enum':
						if ( ! $first ) {
							wppa_out( ', ' );
						}
						if ( $link_type == 'none' ) {
							wppa_out( wppa_get_album_name( $album['id'] ) );
						}
						else {
							wppa_out( '
							<a' .
								( $href_content && ! $onclick_content ? ' href="' . $href_content . '"' : '' ) .
								( $onclick_content ? ' onclick="' . $onclick_content . '"' : '' ) . '
								title="' . $title . '"
								style="cursor:pointer;"
								>' .
								wppa_get_album_name( $album['id'] ) . '
							</a>' );
						}
						$first = false;
						break;
					case 'microthumbs':
						$coverphoto_id = wppa_get_coverphoto_id( $album['id'] );
						$x = max( '1', wppa_get_thumbx( $coverphoto_id ) );
						$y = max( '1', wppa_get_thumby( $coverphoto_id ) );
						if ( $x > ( $y * 2 ) ) { // x limits
							$f = $x / 100;
							$x = 100;
							$y = floor( $y / $f );
						}
						else { // y limits
							$f = $y / 50;
							$y = 50;
							$x = floor( $x / $f );
						}
						$src = wppa_get_thumb_url( $coverphoto_id, true, '', $x, $y, wppa_switch( 'cover_use_thumb' ) );
						wppa_out( '<div style="width:' . $x . 'px;height:' . $y . 'px;overflow:hidden;float:left;" >' );
						if ( $link_type == 'none' ) {
							wppa_out( '
							<img
								class="wppa-cover-sublink-img"
								src="' . $src . '"
								alt="' . wppa_get_album_name( $album['id'] ) . '"
								style="width:' . $x . 'px;height:' . $y . 'px;padding:1px;margin:1px;background-color:' . wppa_opt( 'bgcolor_img' ) . ';float:left;"
							>' );
						}
						else {
							wppa_out( '
							<a' .
								( $href_content && ! $onclick_content ? ' href="' . $href_content . '"' : '' ) .
								( $onclick_content ? ' onclick="' . $onclick_content . '"' : '' ) . '
								title="' . $title . '"
								>
								<img
									class="wppa-cover-sublink-img"
									src="' . $src . '"
									alt="' . wppa_get_album_name( $album['id'] ) . '"
									style="width:' . $x . 'px;height:' . $y . 'px;padding:1px;margin:1px;background-color:' . wppa_opt( 'bgcolor_img' ) . ';float:left;cursor:pointer;"
								>
							</a>' );
						}
						wppa_out( '</div>' );
						break;
					default:
						break;
				}

				// Go deeper for grandchildren
				if ( $is_recursive ) {
					wppa_subalbumlinks_html( $album['id'], false );
				}
			}
		}

		// End list
		if ( $is_list ) {
			wppa_out( '</ul>' );
		}

		wppa_out( '</div>' );
	}
}

function wppa_the_slideshow_browse_link( $photocount, $href_slideshow, $onclick_slideshow, $target ) {

	if ( wppa_switch( 'show_slideshowbrowselink' ) ) {
		wppa_out(
			'<div class="wppa-box-text wppa-black wppa-info wppa-slideshow-browse-link wppa-album-cover-link">'
			);
		if ( $photocount ) {
			$label = wppa_switch( 'enable_slideshow' ) ?
				__( 'Slideshow', 'wp-photo-album-plus' ) :
				__( 'Browse photos', 'wp-photo-album-plus' );
			if ( $href_slideshow == '#' ) {
				wppa_out( '
				<a
					class="wppa-album-cover-link"
					onclick="' . $onclick_slideshow . '"
					title="' . $label . '"
					style="cursor:pointer"
					>' .
					$label . '
				</a>' );
			}
			else {
				wppa_out( '
				<a
					class="wppa-album-cover-link"
					href="' . $href_slideshow . '"
					target="' . $target . '"
					onclick="' . $onclick_slideshow . '"
					title="' . $label . '"
					style="cursor:pointer"
					>' .
					$label . '
				</a>' );
			}
		}
		else {
			wppa_out( '&nbsp;' );
		}
		wppa_out( '</div>' );
	}
}

function wppa_the_album_cats( $alb ) {

	if ( ! wppa_switch( 'show_cats' ) ) {
		return;
	}

	$cats = wppa_get_album_item( $alb, 'cats' );
	$cats = trim( $cats, ',' );
	$cats = str_replace( ',', ',&nbsp;', $cats );

	if ( $cats ) {
		$temp 	= explode( ',', $cats );
		$ncats 	= count( $temp );
		wppa_out( '
		<div
			id="wppa-cats-' . $alb . '-' . wppa( 'mocc' ) . '"
			class="wppa-album-cover-cats"
			style="float:right"
			>' .
				_n( 'Category:', 'Categories:', $ncats, 'wp-photo-album-plus' ) . '&nbsp;<b>' . $cats . '</b>' . '
		</div>' );
	}
}