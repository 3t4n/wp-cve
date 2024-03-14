<?php
/* wppa_theme.php
* Package: wp-photo-album-plus
*
* display the albums/photos/slideshow in a page or post
* Version 8.4.06.007
*/
function wppa_theme() {

global $wppa;
global $wppa_show_statistics;						// Can be set to true by a custom page template
global $wppa_empty_content;

	$curpage = wppa_get_curpage();					// Get the page # we are on when pagination is on, or 1
	$didsome = false;								// Required initializations for pagination
	$n_album_pages = '0';							// "
	$n_thumb_pages = '0';							// "
	$mocc = wppa( 'mocc' );

	// Open container
	wppa_container( 'open' );

	// Show statistics if set so by the page template
	if ( $wppa_show_statistics ) wppa_show_statistics();

	// Display breadcrumb navigation only if it is set in the settings page
	wppa_breadcrumb( 'optional' );


	if ( wppa_page( 'albums' ) ) {													// Page 'Albums' requested

		// Init for possible alt thumbsize on masonry
		$counter_thumbs = '0';
		$is_alt_thumbsize = false;
		$alb_id = wppa( 'start_album' );
		if ( wppa_is_int( $alb_id ) && $alb_id > '0' ) {
			$is_alt_thumbsize = wppa_get_album_item( $alb_id, 'alt_thumbsize' ) == 'yes';
		}

		// Get the albums and the thumbs and the number of pages for each set
		$albums = wppa_get_albums();												// Get the albums
		$n_album_pages = wppa_get_npages( 'albums', $albums );						// Get the number of album pages

		if ( wppa_opt( 'thumbtype' ) != 'none' ) {
			$thumbs = wppa_get_photos();											// Get the Thumbs
		} else $thumbs = false;

		$wanted_empty = wppa_is_wanted_empty( $thumbs );							// See if we need to display an empty thumbnail area

		$n_thumb_pages = wppa_get_npages( 'thumbs', $thumbs );						// Get the number of thumb pages
		if ( $n_thumb_pages == '0' && ! $wanted_empty ) $thumbs = false;			// No pages: no thumbs. Maybe want covers only
		if ( $wanted_empty ) $n_thumb_pages = '1';

		// Get total number of pages
		if ( ! wppa_is_pagination() ) $totpag = '1';								// If both pagination is off, there is only one page
		else $totpag = $n_album_pages + $n_thumb_pages;

		// Make pagelinkbar if requested on top
		if ( wppa_opt( 'pagelink_pos' ) == 'top' || wppa_opt( 'pagelink_pos' ) == 'both' ) {
			wppa_page_links( $totpag, $curpage );
		}

		// Process the albums
		if ( ! wppa_switch( 'thumbs_first' ) ) {
			if ( $albums ) {
				$counter_albums = '0';
				wppa_album_list( 'open' );												// Open Albums sub-container
					foreach ( $albums as $album ) { 									// Loop the albums
						$counter_albums++;
						if ( wppa_onpage( 'albums', $counter_albums, $curpage ) ) {
							wppa_album_cover( $album['id'] );							// Show the cover
							$didsome = true;
						} // End if on page
					}
				wppa_album_list( 'close' );												// Close Albums sub-container
			}	// If albums
		}

		if ( $didsome && wppa_is_pagination() ) $thumbs = false;						// Pag on and didsome: force a pagebreak by faking no thumbs
		if ( is_array( $thumbs ) && ! count( $thumbs ) && ! $wanted_empty ) $thumbs = false;			// Less than treshold value
		if ( wppa_switch( 'thumbs_first' ) && $curpage > $n_thumb_pages ) $thumbs = false; 	// If thumbs done, do not display an empty thumbarea

		// Process the thumbs
		if ( $thumbs || $wanted_empty )
		if ( ! $wanted_empty || ! wppa_switch( 'thumbs_first' ) || wppa_get_curpage() == '1' )
		if ( ! $wanted_empty || wppa_switch( 'thumbs_first' ) || wppa_get_curpage() == $totpag ) {

			// As covers
			if ( wppa_opt( 'thumbtype' ) == 'ascovers' ||
				 wppa_opt( 'thumbtype' ) == 'ascovers-mcr' ) {					// Do the thumbs As covers
				wppa_thumb_list( 'open' );											// Open Thumblist sub-container
				$relpage = wppa_switch( 'thumbs_first' ) ? $curpage : $curpage - $n_album_pages;
				foreach ( $thumbs as $tt ) :  global $thumb; $thumb = $tt; 			// Loop the Thumbs
					$counter_thumbs++;
					if ( wppa_onpage( 'thumbs', $counter_thumbs, $relpage ) ) {
						$didsome = true;
						wppa_thumb_ascover( $thumb['id'] );							// Show Thumb as cover
					} // End if on page
				endforeach;
				wppa_thumb_list( 'close' );											// Close Thumblist sub-container
			}	// As covers

			// Masonry vertical
			elseif ( wppa_opt( 'thumbtype' ) == 'masonry-v' ) {						// Masonry

				// The header
				wppa_thumb_area( 'open' );											// Open Thumbarea sub-container
				wppa_popup();														// Prepare Popup box
				wppa_album_name( 'top' );											// Optionally display album name
				wppa_album_desc( 'top' );											// Optionally display album description

				// Init
				$relpage 	= wppa_switch( 'thumbs_first' ) ? $curpage : $curpage - $n_album_pages;
				$cont_width = wppa_get_container_width();
				$margin 	= wppa_opt( 'tn_margin' );
				if ( wppa_is_mobile() ) $margin = ceil( $margin / 2 );
				$count_cols = ceil( $cont_width / ( wppa_opt( $is_alt_thumbsize ? 'thumbsize_alt' : 'thumbsize' ) + $margin ) );
				if ( wppa_is_mobile() ) {
					$count_cols = ceil( $count_cols / 2 );
				}
				$correction = $margin * ( $cont_width / $count_cols ) / 100;

				// Init the table
				wppa_out( '<table class="wppa-masonry" style="margin-top:3px;" ><tbody class="wppa-masonry" ><tr class="wppa-masonry" >' );

				// Init the columns
				$col_headers 	= array();
				$col_contents 	= array();
				$col_heights 	= array();
				$col_widths 	= array();

				for ( $col = 0; $col < $count_cols; $col++ ) {
					$col_headers[$col] 	= '';
					$col_contents[$col] = '';
					$col_heights[$col] 	= 0;
					$col_widths[$col] 	= 100;
				}

				// Process the thumbnails
				$col = '0';
				if ( $thumbs ) foreach ( $thumbs as $tt ) {
					$id = $tt['id'];
					$counter_thumbs++;
					if ( wppa_onpage( 'thumbs', $counter_thumbs, $relpage ) ) {
						$col_contents[$col] .= wppa_get_thumb_masonry( $id );
						$col_heights[$col] 	+= ( $correction + wppa_get_thumby( $id ) ) / ( $correction + wppa_get_thumbx( $id ) ) * $col_widths[$col];
						$col += '1';
						if ( $col == $count_cols ) {
							$col = '0';
						}
						$didsome = true;
					}
				}

				// Find longest column
				$long = 0;
				for ( $col = 0; $col < $count_cols; $col++ ) {
					if ( $col_heights[$col] > $long ) $long = $col_heights[$col];
				}

				// Adjust column widths to resize lengths to equal lengths
				for ( $col = 0; $col < $count_cols; $col++ ) {
					if ( $col_heights[$col] ) {
						$col_widths[$col] = $long / $col_heights[$col] * $col_widths[$col];
					}
				}

				// Adjust column widths to total 100
				$wide = 0;
				for ( $col = 0; $col < $count_cols; $col++ ) {
					$wide += $col_widths[$col];
				}
				for ( $col = 0; $col < $count_cols; $col++ ) {
					$col_widths[$col] = $col_widths[$col] * 100 / $wide;
				}

				// Make column headers
				for ( $col = 0; $col < $count_cols; $col++ ) {
					$col_headers[$col] = '<td style="width: '.$col_widths[$col].'%; vertical-align:top;" class="wppa-masonry" >';
				}

				// Add the columns to the output stream
				for ( $col = 0; $col < $count_cols; $col++ ) {
					wppa_out( $col_headers[$col] );
					wppa_out( $col_contents[$col] );
					wppa_out( '</td>' );
				}

				// Close the table
				wppa_out( '</tr></tbody></table>' );

				// The footer
				wppa_album_name( 'bottom' );										// Optionally display album name
				wppa_album_desc( 'bottom' );										// Optionally display album description
				wppa_thumb_area( 'close' );											// Close Thumbarea sub-container
			}	// Masonry-v

			// Masonry horizontal
			elseif ( wppa_opt( 'thumbtype' ) == 'masonry-h' ) {						// Masonry

				// The header
				wppa_thumb_area( 'open' );											// Open Thumbarea sub-container
				wppa_popup();														// Prepare Popup box
				wppa_album_name( 'top' );											// Optionally display album name
				wppa_album_desc( 'top' );											// Optionally display album description

				// Init
				$relpage 	= wppa_switch( 'thumbs_first' ) ? $curpage : $curpage - $n_album_pages;
				$cont_width = wppa_get_container_width( 'netto' );
				$correction = wppa_opt( 'tn_margin' );

				// Init the table
				wppa_out( '<table class="wppa-masonry wppa-masonry-h" style="margin-top:3px;" ><tbody class="wppa-masonry" >' );

				// Process the thumbnails
				$row_content 		= '';
				$row_width 			= 0;
				$target_row_height 	= wppa_opt( $is_alt_thumbsize ? 'thumbsize_alt' : 'thumbsize' ) * 0.75 + $correction;
				$rw_count 			= 0;
				$tr_count 			= '1';
				$done_count 		= 0;
				$last 				= false;
				$max_row_height 	= $target_row_height * 0.8; 	// Init keep track for last
				if ( $thumbs ) foreach ( $thumbs as $tt ) {
					$id = $tt['id'];
					$counter_thumbs++;
					if ( wppa_onpage( 'thumbs', $counter_thumbs, $relpage ) ) {
						$row_content 	.= wppa_get_thumb_masonry( $tt['id'] );
						$rw_count 		+= 1;
						$row_width 		+= wppa_get_thumbratioxy( $id ) * ( $target_row_height - $correction );
						$didsome 		= true;
					}
					$done_count 	+= 1;
					$last 			= $done_count == count( $thumbs );
					if ( $row_width > $cont_width || $last ) {
						$tot_marg 		= $rw_count * $correction;
						$row_height 	= $row_width ? ( ( $target_row_height - $correction ) * ( $cont_width - $tot_marg ) / ( $row_width ) + $correction )  : '0';
						if ( ! $last ) {
							$max_row_height = max( $max_row_height, $row_height );
						}
						if ( $last && $row_height > wppa_get_thumby( $id ) ) {
							$row_height = $max_row_height;
						}
						$row_height_p 	= $row_height / $cont_width * 100;
						wppa_out( 	'<tr class="wppa-masonry" >' .
										'<td style="border:none;padding:0;margin:0" >' .
											'<div' .
												' id="wppa-mas-h-'.$tr_count.'-'.wppa( 'mocc' ).'"' .
												' style="height:'.$row_height.'px;"' .
												' class="wppa-masonry"' .
												' data-height-perc="'.$row_height_p.'"' .
												' >');
						wppa_out( $row_content );
						wppa_out( '</div></td></tr>' );
						$row_content 	= '';
						$row_width 		= 0;
						$row_height 	= wppa_opt( 'thumbsize' );
						$rw_count 		= 0;
						$tr_count 		+= '1';
					}
				}
				wppa_out( '<tr class="wppa-masonry" ><td class="wppa-dummy" style="padding:0;border:none;" ></td></tr>' );
				wppa_out( '</tbody></table>' );
				wppa_js( 'jQuery(document).ready(function(){wppaSetMasHorFrameWidthsForIeAndChrome(' . wppa( 'mocc' ) . ');});' );

				// The footer
				wppa_album_name( 'bottom' );										// Optionally display album name
				wppa_album_desc( 'bottom' );										// Optionally display album description
				wppa_thumb_area( 'close' );											// Close Thumbarea sub-container

			}	// Masonry-h

			// Masonry plus
			elseif ( wppa_opt( 'thumbtype' ) == 'masonry-plus' ) {

				// The header
				wppa_thumb_area( 'open' );											// Open Thumbarea sub-container
				wppa_popup();														// Prepare Popup box
				wppa_album_name( 'top' );											// Optionally display album name
				wppa_album_desc( 'top' );											// Optionally display album description

				// Init
				$relpage = wppa_switch( 'thumbs_first' ) ? $curpage : $curpage - $n_album_pages;

				// Process the thumbnails
				if ( $thumbs ) {

					// Open masonry contatiner
					$html = '
					<div
						id="grid-' . $mocc . '"
						class="grid-' . $mocc . ' grid-masonryplus"
						style="padding-top:6px;padding-bottom:6px;padding-right:6px;margin:0 auto;"
						>';

					// Add css
					$html .= '
					<style type="text/css" >
						.grid-item-' . $mocc . ' {
							line-height: 0;
							visibility: hidden;
							text-align: center;
						}
						.grid-item-' . $mocc . ' img {
							width: 100%;
						}
					</style>';

					// The thumbs
					foreach ( $thumbs as $tt ) {
						$counter_thumbs++;

						if ( wppa_onpage( 'thumbs', $counter_thumbs, $relpage ) ) {

							$didsome = true;
							$html .= '
							<div
								style=""
								id="grid-item-' . $mocc . '-' . wppa_encrypt_photo( $tt['id'] ) . '"
								class="grid-item grid-item-' . $mocc . '" >' .
								wppa_get_thumb_masonry( $tt['id'] ) . '
							</div>';
						}
					}

					// Close masonry container
					$html .= '</div><div style="clear:both" ></div>';

					wppa_out( wppa_compress_html( $html ) );
				}

				// The footer
				wppa_album_name( 'bottom' );										// Optionally display album name
				wppa_album_desc( 'bottom' );										// Optionally display album description
				wppa_thumb_area( 'close' );											// Close Thumbarea sub-container
			}	// Masonry plus

			// Default
			elseif ( wppa_opt( 'thumbtype' ) == 'default' ) {					// Do the thumbs As default

				// The header
				wppa_thumb_area( 'open' );											// Open Thumbarea sub-container
				wppa_popup();														// Prepare Popup box
				wppa_album_name( 'top' );											// Optionally display album name
				wppa_album_desc( 'top' );											// Optionally display album description

				// Init
				$relpage = wppa_switch( 'thumbs_first' ) ? $curpage : $curpage - $n_album_pages;

				// Process the thumbnails
				if ( $thumbs ) foreach ( $thumbs as $tt ) {
					$counter_thumbs++;
					if ( wppa_onpage( 'thumbs', $counter_thumbs, $relpage ) ) {
						$didsome = true;
						wppa_thumb_default( $tt['id'] );							// Show Thumb as default
					}	// End if on page
				}

				// The footer
				wppa_album_name( 'bottom' );										// Optionally display album name
				wppa_album_desc( 'bottom' );										// Optionally display album description
				wppa_thumb_area( 'close' );											// Close Thumbarea sub-container
			}	// As default

			// Unimplemented thumbnail type
			else {
				wppa_out( 'Unimplemented thumbnail type: ' . wppa_opt( 'thumbtype' ) );
			}
		}	// If thumbs

		if ( $didsome && wppa_is_pagination() ) $albums = false;					// Pag on and didsome: force a pagebreak by faking no albums
		if ( ! wppa_is_pagination() ) $n_thumb_pages = '0';							// Still on page one

		// Process the albums
		if ( wppa_switch( 'thumbs_first' ) ) {
			if ( $albums ) {
				$counter_albums = '0';
				wppa_album_list( 'open' );												// Open Albums sub-container
					foreach ( $albums as $album ) { 									// Loop the albums
						$counter_albums++;
						if ( wppa_onpage( 'albums', $counter_albums, $curpage - $n_thumb_pages ) ) {
							wppa_album_cover( $album['id'] );							// Show the cover
							$didsome = true;
						} // End if on page
					}
				wppa_album_list( 'close' );												// Close Albums sub-container
			}	// If albums
		}

		// Make pagelinkbar if requested on bottom
		if ( wppa_opt( 'pagelink_pos' ) == 'bottom' || wppa_opt( 'pagelink_pos' ) == 'both' ) {
			wppa_page_links( $totpag, $curpage );
		}

		// Empty results?
		if ( ! $didsome && ! $wanted_empty ) {
			if ( wppa( 'photos_only' ) ) {
				wppa_out( wppa_errorbox( __( 'No photos found matching your search criteria.', 'wp-photo-album-plus' ) ) );
				wppa_report_nothing(1);
			}
			elseif ( wppa( 'albums_only' ) ) {
				wppa_out( wppa_errorbox( __( 'No albums found matching your search criteria.', 'wp-photo-album-plus' ) ) );
				wppa_report_nothing(2);
			}
			else {
				wppa_out( wppa_errorbox( __( 'No albums or photos found matching your search criteria.', 'wp-photo-album-plus' ) ) );
				wppa_report_nothing(3);
			}
			$wppa_empty_content = true;
		}
	} // wppa_page( 'albums' )

	elseif ( wppa_page( 'slide' ) || wppa_page( 'single' ) ) {						// Page 'Slideshow' or 'Single' in browsemode requested

		// Get all the photos
		$photos = wppa_get_photos();

		if ( $photos ) {
			wppa_slide_list( 'open' );		// Wrapper for nicescroller
			wppa_the_slideshow( $photos ); 	// Produces all the html required for the slideshow
			wppa_slide_list( 'close' );
		}
		else {
			wppa_out( wppa_errorbox( __( 'No photos found matching your search criteria.', 'wp-photo-album-plus') ) );
			wppa_report_nothing(4);
		}
	} // wppa_page( 'slide' )

	// Close container
	wppa_container( 'close' );

}

function wppa_is_wanted_empty( $thumbs ) {

	if ( ! wppa_switch( 'show_empty_thumblist' ) ) return false;							// Feature not enabled
	if ( is_array( $thumbs ) && count( $thumbs ) ) return false;							// Album is not empty
	if ( wppa_is_virtual() ) return false; 													// wanted empty only on real albums
	if ( ! wppa_is_int( wppa( 'start_album' ) ) ) return false;								// Only seingle albums, no enumerations
	if ( wppa( 'albums_only' ) ) return false;												// Explicitly no thumbs

//	if ( wppa_switch( 'thumbs_first' ) && wppa_get_curpage() != '1' ) return false;			// Only on page 1 if thumbs first

	wppa( 'current_album', wppa( 'start_album' ) );											// Make sure upload knows the album

	return true;
}

function wppa_get_extra_url() {

	$extra_url = '';

	// occur
	$occur = wppa_get( 'occur', wppa( 'mocc' ) );
	$extra_url .= '&amp;wppa-occur=' . $occur;

	// cover
	$cover = wppa_get( 'cover', wppa( 'is_cover' ) );
	if ( ! $cover ) $cover = '0';
	$extra_url .= '&amp;wppa-cover='.$cover;

	// album
	$album = wppa_get( 'album', wppa( 'start_album' ) );
	if ( $album ) $extra_url .= '&amp;wppa-album='.$album;

	// slide or photo
	$slide = wppa_get( 'slide', wppa( 'is_slide' ) );
	if ( $slide ) $extra_url .= '&amp;wppa-slide=1';

	// Photo
	$photo = wppa_get( 'photo', wppa( 'start_photo' ) );
	if ( $photo ) $extra_url .= '&amp;wppa-photo=' . wppa_get( 'photo' );

	// Topten?
	if ( wppa( 'is_topten' ) ) $extra_url .= '&amp;wppa-topten='.wppa( 'topten_count' );

	// Lasten?
	if ( wppa( 'is_lasten' ) ) $extra_url .= '&amp;wppa-lasten='.wppa( 'lasten_count' );

	// Comten?
	if ( wppa( 'is_comten' ) ) $extra_url .= '&amp;wppa-comten='.wppa( 'comten_count' );

	// Featen?
	if ( wppa( 'is_featen' ) ) $extra_url .= '&amp;wppa-featen='.wppa( 'featen_count' );

	// Tag?
	if ( wppa( 'is_tag' ) && ! wppa( 'is_related' ) ) $extra_url .= '&amp;wppa-tag='.wppa( 'is_tag' );

	// Search?
	if ( wppa( 'src' ) && ! wppa( 'is_related' ) ) $extra_url .= '&amp;wppa-searchstring='.urlencode( wppa( 'searchstring' ) );

	// Supersearch?
	if ( wppa( 'supersearch' ) ) $extra_url .= '&amp;wppa-supersearch=' . str_replace( '/', '%2F', urlencode( wppa( 'supersearch' ) ) );

	// Related
	if ( wppa( 'is_related' ) ) $extra_url .= '&amp;wppa-rel='.wppa( 'is_related' ).'&amp;wppa-relcount='.wppa( 'related_count' );

	// Uploader?
	if ( wppa( 'is_upldr' ) ) $extra_url .= '&amp;wppa-upldr='.wppa( 'is_upldr' );

	// Calendar ?
	if ( wppa( 'calendar' ) ) $extra_url .= '&amp;wppa-calendar='.wppa( 'calendar' ).'&amp;wppa-caldate='.wppa( 'caldate' );

	// Photos only?
	if ( wppa( 'photos_only' ) ) $extra_url .= '&amp;wppa-photos-only=1';

	// Albums only?
	if ( wppa( 'albums_only' ) ) $extra_url .= '&amp;wppa-albums-only=1';

	// Inverse?
	if ( wppa( 'is_inverse' ) ) $extra_url .= '&amp;wppa-inv=1';

	return $extra_url;
}

function wppa_report_nothing( $where ) {

	$result = "Nothing found. Location: $where";
	wppa_log( 'dbg', $result );
}

