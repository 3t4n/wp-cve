<?php
/* wppa-boxes-html.php
* Package: wp-photo-album-plus
*
* Various wppa boxes
* Version 8.6.04.004
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// Open / close the box containing the thumbnails
function wppa_thumb_area( $action ) {

	$nice 		= wppa_is_nice();
	$maxh 		= wppa_opt( 'area_size' );
	$overflow 	= 'auto';
	$mocc 		= wppa( 'mocc' );
	if ( $nice ) $overflow = 'hidden';
	$modal = defined( 'DOING_WPPA_AJAX' ) && wppa_switch( 'ajax_render_modal' );
	$result = '';

	// Open thumbnail area box
	if ( $action == 'open' ) {
		if ( is_feed() ) {
			$result .= 	'
			<div
				id="wppa-thumb-area-' . $mocc . '"
				class="wppa-box wppa-thumb-area"
				>';
		}
		else {
			$result .= 	'
			<div
				id="wppa-thumb-area-' . $mocc . '"
				class="wppa-box wppa-thumb-area wppa-thumb-area-' . $mocc . ( $modal ? ' wppa-modal' : '' ) . '"
				style="' . ( $maxh > '1' ? 'max-height:' . $maxh . 'px;' : '' ) . '
						overflow:' . $overflow . ';"
				onscroll="wppaMakeLazyVisible();"
				>';

			if ( wppa_is_int( wppa( 'start_album' ) ) ) {
				wppa_bump_viewcount( 'album', wppa( 'start_album') );
			}
		}

		// Use nicescroller?
		if ( $nice ) {
			$result .= 	'<div class="wppa-nicewrap" >';
		}

		// Display create sub album and upload photo links conditionally
		if ( ! wppa_is_virtual() && wppa_opt( 'upload_link_thumbs' ) == 'top' ) {

			$alb = wppa( 'current_album' );
			$result .= wppa_get_user_create_html( $alb, wppa_get_container_width( 'netto' ), 'thumb' );
			$result .= wppa_get_user_upload_html( $alb, wppa_get_container_width( 'netto' ), 'thumb' );
		}
	}

	// Close thumbnail area box
	elseif ( $action == 'close' ) {

		// Display create sub album and upload photo links conditionally
		if ( ! wppa_is_virtual() && wppa_opt( 'upload_link_thumbs' ) == 'bottom' ) {

			$alb = wppa( 'current_album' );
			$result .= wppa_get_user_create_html( $alb, wppa_get_container_width( 'netto' ), 'thumb' );
			$result .= wppa_get_user_upload_html( $alb, wppa_get_container_width( 'netto' ), 'thumb' );
		}

		// Clear both
		$result .= '<div class="wppa-clear" ></div>';

		// Nicescroller
		if ( $nice ) {
			wppa_js( '
				jQuery(document).ready(function(){
					if ( jQuery().niceScroll )
					jQuery(".wppa-thumb-area").niceScroll(".wppa-nicewrap",{' . wppa_opt( 'nicescroll_opts' ) . '});
				});' );
			$result .= '</div>'; 	// close .wppa-nicewrap div
		}

		// Close the thumbnail box
		$result .= '</div>';
	}

	// Output result
	wppa_out( $result );
}

// Contest box
function wppa_contest_box() {

	// Init
	$result 	= '';
	$mocc 		= wppa( 'mocc' );
	$maxh 		= wppa_opt( 'area_size' );
	$nice 		= wppa_is_nice();
	$overflow 	= 'visible';
	if ( $maxh ) {
		if ( $nice ) $overflow = 'hidden';
		else $overflow = 'auto';
	}

	// Open contest box
	if ( is_feed() ) {
		$result .= 	'
		<div
			id="wppa-thumb-area-' . $mocc . '"
			class="wppa-box wppa-thumb-area"
			>';
	}
	else {
		$result .= 	'
		<div
			id="wppa-thumb-area-' . $mocc . '"
			class="wppa-box wppa-contest wppa-thumb-area wppa-thumb-area-' . $mocc . '"
			style="' . ( $maxh > '1' ? 'max-height:' . $maxh . 'px;' : '' ) . '
					overflow:' . $overflow . ';"
			onscroll="wppaMakeLazyVisible();"
			>';
	}

	// Use nicescroller?
	if ( $nice ) {
		$result .= 	'<div class="wppa-nicewrap" >';
	}

	$result .= wppa_get_contest_html( wppa( 'start_album' ) );

	// After content
	$result .= '<div class="wppa-clear" ></div>';

	// Nicescroller
	if ( $nice ) {
		wppa_js( '
			jQuery(document).ready(function(){
				if ( jQuery().niceScroll )
				jQuery(".wppa-thumb-area").niceScroll(".wppa-nicewrap",{' . wppa_opt( 'nicescroll_opts' ) . '});
			});' );
		$result .= '</div>'; 	// close .wppa-nicewrap div
	}	// Nicescroller

	// Close the box
	$result .= '</div>';

	// Output result
	wppa_out( $result );
}

// Get contest html
function wppa_get_contest_html( $xalb ) {
global $wpdb;

	$albs = wppa_expand_enum( $xalb );
	$albarr = explode( '.', $albs );
	$alblist = implode( ',', $albarr );

	// Get display type
	$type = wppa_opt( 'contest_sortby' );
	$numb = wppa_opt( 'contest_number' );
	$max  = wppa_opt( 'contest_max' );
	$anon = wppa_is_anon();

	// Sequenc is by mean rating
	if ( $type == 'average' ) {
		$photos = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
													   WHERE album IN ($alblist)
													   AND mean_rating <> ''
													   ORDER BY mean_rating DESC
													   LIMIT %d", $max ), ARRAY_A );
	}

	// Sequenc order is total score
	if ( $type == 'total' ) {
		$photos = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_photos
									   WHERE album IN ($alblist)
									   AND mean_rating <> ''", ARRAY_A );
		if ( is_array( $photos ) ) {
			foreach( array_keys( $photos ) as $idx ) {
				$photos[$idx]['total'] = wppa_get_rating_total_by_id( $photos[$idx]['id'] );
			}
			$photos = wppa_array_sort( $photos, 'total' );
			$photos = array_reverse( $photos );
			if ( count( $photos ) > $max ) {
				$photos = array_slice( $photos, 0, $max );
			}
		}
	}

	// Create html
	if ( count( $albarr ) == 1 ) {
		$result = '
		<h3>' .
			sprintf( __( 'Results of contest %s', 'wp-photo-album-plus' ), wppa_get_album_name( $xalb ) ) . '
		</h3>';
	}
	else {
		$result = '
		<h3>' .
			__( 'Results of contest', 'wp-photo-album-plus' ) . '
		</h3>';
	}

	if ( ! $photos ) {
		$result .= __( 'There are no rated photos to display', 'wp-photo-album-plus' );
		$result .= '<br>';
		return $result;
	}

	$result .= '
	<table class="wppa-contest-table" >
		<colgroup>
			<col style="width:30%">
		</colgroup>
		<thead>
			<tr>
				<th class="wppa-contest-table-photo" >' . __( 'Photo', 'wp-photo-album-plus' ) . '</th>
				<th class="wppa-contest-table-rater" >' . __( 'Rater', 'wp-photo-album-plus' ) . '</th>
				<th class="wppa-contest-table-points" >' . __( 'Points', 'wp-photo-album-plus' ) . '</th>
				<th class="wppa-contest-table-owner" >' . __( 'Owner', 'wp-photo-album-plus' ) . '</th>
				<th class="wppa-contest-table-ranking" >' . __( 'Ranking', 'wp-photo-album-plus' ) . '</th>
			</tr>
		</thead>
		<tbody>';

			// Misc inits
			$rank = '1';
			$prev_score = '0';
			$this_score = '0';
			$prev_rank 	= '0';
			$this_rank 	= '0';

			foreach( $photos as $photo ) {
				$id 		= $photo['id'];
				$alb 		= $photo['album'];
				$seqno 		= wppa_get_seqno( $alb, $id );
				$ratings 	= $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_rating WHERE photo = %d AND status = 'publish'", $id ), ARRAY_A );
				$is_video 	= wppa_is_video( $id );

				if ( $ratings ) {

					$result .= '
					<tr>
						<td class="wppa-contest-table-photo" >
							<a
								href="' . wppa_get_hires_url( $id ) . '"
								data-rel="wppa" data-wppa="yes"' .
								( $is_video ? ' data-videohtml="' . esc_attr( wppa_get_video_body( $id ) ) . '"' .
											  ' data-videonatwidth="'.wppa_get_videox( $id ) . '"' .
											  ' data-videonatheight="'.wppa_get_videoy( $id ) . '"' : '' ) . '

							>';

								// Video?
								if ( wppa_is_video( $id ) ) {
									$result .= wppa_get_video_html( ['id' => $id, 'style' => 'width:100%;', 'controls' => false] );

									/*
					'width'			=> '0',
					'widthp' 		=> '0',
					'height' 		=> '0',
					'controls' 		=> true,
					'margin_top' 	=> '0',
					'margin_bottom' => '0',
					'tagid' 		=> 'video-' . wppa( 'mocc' ),
					'cursor' 		=> '',
					'events' 		=> '',
					'title' 		=> '',
					'preload' 		=> 'metadata',
					'onclick' 		=> '',
					'lb' 			=> false,
					'class' 		=> '',
					'style' 		=> '',
					'use_thumb' 	=> false,
					'autoplay' 		=> false);
					*/
								}

								// Photo
								else {
									$result .= '
									<img src="' . wppa_get_photo_url( $id ) . '" alt="' . ( $anon ? '' : esc_attr( $photo['name'] ) ) . '" >';
								}

							$result .= '
							</a>
							<br> ';
							$result .= $anon ? '' : htmlspecialchars( $photo['name'] );
							if ( $numb == 'id' ) $result .= ' (' . $id . ')';
							if ( $numb == 'seqno' ) $result .= ' (' . wppa_get_seqno( $alb, $id ) . ')';
							$result .= '
						</td>
						<td class="wppa-contest-table-rater" >';
							foreach( $ratings as $rating ) {
								if ( wppa_contest_display_comment( $id, $rating['userid'] ) ) {
									$comment = $wpdb->get_var( $wpdb->prepare( "SELECT comment FROM $wpdb->wppa_comments WHERE photo = %d AND userid = %d", $id, $rating['userid'] ) );
								}
								else {
									$comment = '';
								}
								if ( $comment ) {
									$result .= '
									<a
										title="' . esc_attr( $comment ) . '"
										style="cursor:pointer"
										onclick="alert(\'' . esc_js( $comment ) . '\')"
										>' . wppa_get_user_by( 'id', $rating['userid'], false )->display_name . '
									</a>
									<br>';
								}
								else {
									$result .= wppa_get_user_by( 'id', $rating['userid'], false )->display_name . '<br>';
								}
							}
							$result .= '&nbsp;<br>';
							if ( $type == 'total' ) $result .= __( 'Total', 'wp-photo-album-plus' );
							if ( $type == 'average' ) $result .= __( 'Average', 'wp-photo-album-plus' );
							$result .= '
						</td>
						<td class="wppa-contest-table-points" >';
							foreach( $ratings as $rating ) {
								$t = $rating['value'];
								$result .= $t . '<br>';
							}
							$result .= '-----<br><b>';
							$prev_score = $this_score;
							if ( $type == 'total' ) {
								$this_score = $photo['total'];
								$result .= $this_score;
							}
							if ( $type == 'average' ) {
								$this_score = $photo['mean_rating'];
								$result .= sprintf( '%3.1f', $this_score );
							}
							$result .= '</b>
						</td>
						<td class="wppa-contest-table-owner" >' .
							wppa_get_user_by( 'login', $photo['owner'], true )->display_name . '
						</td>
						<td class="wppa-contest-table-ranking" >
							<b>';
								if ( $prev_score == $this_score ) {
									$this_rank = $prev_rank;
								}
								else {
									$this_rank = $rank;
									$prev_rank = $rank;
								}
								switch ( $this_rank ) {
									case '1':
										$result .= '
											<span style="font-size:2em">1</span>&nbsp;
											<img
												src="' . WPPA_URL . '/img/medal_gold_' . wppa_opt( 'medal_color' ) .'.png"
												alt="gold medal"
												title="' . esc_attr( __( 'Gold medal', 'wp-photo-album-plus' ) ) . '"
											/>';
										break;
									case '2':
										$result .= '
											<span style="font-size:1.75em">2</span>&nbsp;
											<img
												src="' . WPPA_URL . '/img/medal_silver_' . wppa_opt( 'medal_color' ) .'.png"
												alt="silver medal"
												title="' . esc_attr( __( 'Silver medal', 'wp-photo-album-plus' ) ) . '"
											/>';
										break;
									case '3':
										$result .= '
											<span style="font-size:1.5em">3</span>
											<img
												src="' . WPPA_URL . '/img/medal_bronze_' . wppa_opt( 'medal_color' ) .'.png"
												alt="bronze medal"
												title="' . esc_attr( __( 'Bronze medal', 'wp-photo-album-plus' ) ) . '"
											/>';
										break;
									default:
										$result .= $this_rank;
										break;
								}
								$result .= '
							</b>
						</td>
					</tr>';
				}
				$rank++;
			}
		$result .= '
		</tbody>
	</table>
	<br><div style="clear:both"></div>';

	return $result;
}

// Should comment be visible on contest display?
function wppa_contest_display_comment( $photoid, $userid ) {

	// Admin sees everything independant of policy
	if ( wppa_user_is_admin() ) {
		return true;
	}

	// Find the policy
	$policy = wppa_opt( 'contest_comment_policy' );

	switch ( $policy ) {

		// Nobody (admin has been covered above)?
		case 'none':
			return false;
			break;

		// My userid == comment/rating userid?
		case 'comowner':
			return ( wppa_get_user( 'id' ) == $userid );
			break;

		// My userid == comment/rating userid OR my login name == photo owner?
		case 'owners':
			return ( wppa_get_user( 'id' ) == $userid ) || ( wppa_get_user( 'login' ) == wppa_get_photo_item( $photoid, 'owner' ) );
			break;

		// case 'all':
		default:
			return true;
			break;
	}
}

// Search box
function wppa_search_box() {

	// Init
	$result = '';

	// No search box on feeds
	if ( is_feed() ) return;

	// Open container
	wppa_container( 'open' );

	// Open wrapper
	$result .= '
	<div
		id="wppa-search-'.wppa( 'mocc' ) . '"
		class="wppa-box wppa-search"
		>';

	// The search html
	$result .= wppa_get_search_html( '',
									 wppa( 'may_sub' ),
									 wppa( 'may_root' ),
									 wppa( 'forceroot' ),
									 wppa( 'landingpage' ),
									 wppa_switch( 'search_catbox' ),
									 wppa_opt( 'search_selboxes' ) );

	// Clear both
	$result .= '<div class="wppa-clear" ></div>';

	// Close wrapper
	$result .= '</div>';

	// Output
	wppa_out( $result );

	// Close container
	wppa_container( 'close' );
}

// Get search html
function wppa_get_search_html( $label = '', $sub = false, $rt = false, $force_root = '', $page = '', $catbox = false, $selboxes = 0 ) {
global $wppa_session;

	$wppa_session['has_searchbox'] = true;

	if ( ! $page ) {
		$page 		= wppa_get_the_landing_page( 	'search_linkpage',
													__( 'Photo search results', 'wp-photo-album-plus' )
												);
	}
	$pagelink 		= get_page_link( $page );
	$cansubsearch  	= $sub && $wppa_session['use_searchstring'];
	$value 			= $cansubsearch ? '' : wppa_test_for_search( true );
	$root 			= $wppa_session['search_root'];
	$rootboxset 	= $root ? '' : 'checked="checked" disabled';
	$fontsize 		= wppa_in_widget() ? 'font-size: 9px;' : '';
	$mocc 			= wppa( 'mocc' );
	$n_items 		= ( $catbox ? 1 : 0 ) + $selboxes + 1;
	$is_small 		= ( wppa_in_widget() ? true : false );
	$w 				= ( $is_small ? 100 : ( 100 / $n_items ) );

	// Find out if one or more items have a caption.
	// For layout purposes: If so, append '&nbsp;' to all captions to avoid empty captions
	if ( ! wppa_in_widget() ) {
		$label = wppa_opt( 'search_toptext' );
	}
	$any_caption 	= false;
	if ( $catbox || $label ) {
		$any_caption = true;
	}
	if ( $selboxes ) {
		for ( $sb = 0; $sb < $selboxes; $sb++ ) {
			if ( wppa_opt( 'search_caption_' . $sb ) ) {
				$any_caption = true;
			}
		}
	}

	// Open the form
	$result = '
	<form
		id="wppa_searchform_' . $mocc . '"
		action="' . $pagelink.'"
		method="' . wppa_opt( 'search_form_method' ) . '"
		class="widget_search search-form"
		role="search"
		>';

		// Catbox
		if ( $catbox ) {

			// Item wrapper
			$result .= '
			<div
				class="wppa-searchsel-item wppa-searchsel-item-' . $mocc . '"
				style="width:' . $w . '%;float:left"
				>';

				$cats = wppa_get_catlist();
				$result .=
				__( 'Category', 'wp-photo-album-plus' ) . '
				<select
					id="wppa-catbox-' . $mocc . '"
					name="wppa-catbox"
					class="wppa-searchselbox"
					style="width:100%;clear:both;"
					>';

					$current = '';
					if ( wppa_get( 'catbox' ) ) {
						$current = wppa_get( 'catbox' );
					}
					elseif ( wppa_get( 'catbox' ) ) {
						$current = wppa_get( 'catbox' );
					}
					if ( $current ) {
						$current = trim( wppa_sanitize_cats( $current ), ',' );
					}

					$result .= '<option value="" >' . __( '--- all ---', 'wp-photo-album-plus' ) . '</option>';
					if ( ! empty( $cats ) ) foreach( array_keys( $cats ) as $cat ) {
						$result .= '<option value="' . $cat . '" ' . ( $current == $cat ? 'selected' : '' ) . ' >' . $cat . '</option>';
					}
				$result .= '
				</select>';

			// Close item wrapper
			$result .= '
			</div>';
		}

		// Selection boxes
		if ( $selboxes ) {

			for ( $sb = 0; $sb < $selboxes; $sb++ ) {
				$opts[$sb] = array_merge( array( '' ), explode( "\n", wppa_opt( 'search_selbox_' . $sb ) ) );
				$vals[$sb] = $opts[$sb];
				$current = wppa_get( 'searchselbox-' . $sb, '', 'text' );

				// Item wrapper
				$result .= '
				<div
					class="wppa-searchsel-item wppa-searchsel-item-' . $mocc . '"
					style="width:' . $w . '%;float:left"
					>';

					// Caption
					$result .=
					wppa_opt( 'search_caption_' . $sb ) . ( $any_caption ? '&nbsp;' : '' );

					// Selbox
					$result .= '
					<select
						name="wppa-searchselbox-' . $sb . '"
						class="wppa-searchselbox"
						style="clear:both;width:100%;"
						>';
						foreach( array_keys( $opts[$sb] ) as $key ) {
							$sel = $current == $vals[$sb][$key] ? ' selected' : '';
							$result .= '<option value="' . $vals[$sb][$key] . '"' . $sel . ' >' . $opts[$sb][$key] . '</option>';
						}
					$result .= '
					</select>';

				// Close item wrapper
				$result .= '
				</div>';
			}
		}

		// The actual search input and submit
		// Item wrapper
		$result .= '
		<div
			class="wppa-searchsel-item wppa-searchsel-item-' . $mocc . '"
			style="width:' . $w . '%;float:left"
			>';

			// Toptext
			$result .=
			wppa_opt( 'search_toptext' ) . ( $any_caption ? '&nbsp;' : '' ) . '
			<div style="position:relative">';

				// form core
				$form_core = '';

				// Use own form as requested
				if ( wppa_switch( 'use_wppa_search_form' ) ) {

					if ( wppa_browser_can_html5() ) {
						$form_core = '<!-- wppa form html5 -->
						<label>
							<span class="screen-reader-text" >' . esc_html__( 'Search for:', 'wp-photo-album-plus' ) . '</span>
							<input
								type="search"
								class="search-field"
								placeholder="' . esc_attr( wppa_opt( 'search_placeholder' ) ) . '"
								value="' . esc_attr( wppa_get( 'searchstring' ) ) . '"
								name="wppa-searchstring"
							/>
						</label>
						<input
							type="submit"
							class="search-submit"
							value="'. esc_attr__( 'Search', 'wp-photo-album-plus' ) .'"
						/>';
					} else {
						$form_core = '<!-- wppa form html4 -->
						<div>
							<label
								class="screen-reader-text"
								for="wppa_s-'.$mocc.'"
								>' .
								esc_html__( 'Search for:', 'wp-photo-album-plus' ) . '
							</label>
							<input
								type="text"
								value="' . esc_attr( wppa_get( 'searchstring' ) ) . '"
								name="wppa-searchstring"
								id="wppa_s-' . $mocc . '"
							/>
							<input
								type="submit"
								id="searchsubmit"
								value="' . esc_attr__( 'Search', 'wp-photo-album-plus' ) . '"
							/>
						</div>';
					}
				}

				// Use theme or modified form
				else {
					$form_core = get_search_form( ['echo' => false] );

					// If still no luck, use wp default
					if ( ! $form_core ) {

						$format = current_theme_supports( 'html5', 'search-form' ) ? 'html5' : 'xhtml';
						$format = apply_filters( 'search_form_format', $format );

						if ( 'html5' == $format ) {
							$form_core = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
								<label>
									<span class="screen-reader-text">' . esc_html__( 'Search for:', 'wp-photo-album-plus' ) . '</span>
									<input type="search" class="search-field" placeholder="' . esc_attr( wppa_opt( 'search_placeholder' ) ) . '" value="' . get_search_query() . '" name="s" />
								</label>
								<input type="submit" class="search-submit" value="' . esc_attr__( 'Search', 'wp-photo-album-plus' ) .'" />
							</form>';
						} else {
							$form_core = '<form role="search" method="get" id="searchform" class="searchform" action="' . esc_url( home_url( '/' ) ) . '">
								<div>
									<label class="screen-reader-text" for="s">' . esc_html__( 'Search for:', 'wp-photo-album-plus' ) . '</label>
									<input type="text" value="' . get_search_query() . '" name="s" id="s" />
									<input type="submit" id="searchsubmit" value="'. esc_attr__( 'Search', 'wp-photo-album-plus' ) .'" />
								</div>
							</form>';
						}
					}

					// Remove form tag, we are already in a form
					$form_core = preg_replace( array( '/<form[^>]*>/siu', '/<\/form[^>]*>/siu' ), '', $form_core );

					// Fix id and name
					$form_core = str_replace( 'for="s"', 'for="wppa_s-'.$mocc.'"', $form_core );
					$form_core = str_replace( 'id="s"', 'id="wppa_s-'.$mocc.'"', $form_core );
					$form_core = str_replace( 'name="s"', 'name="wppa-searchstring"', $form_core );

					// If no placeholder in form_core, add it
					if ( strpos( $form_core, 'placeholder' ) === false ) {
						if ( strpos( $form_core, 'name="wppa-searchstring"' ) !== false ) {
							$form_core = str_replace( 'name="wppa-searchstring"', 'name="wppa-searchstring" placeholder="' . esc_attr( wppa_opt( 'search_placeholder' ) ) . '" ', $form_core );
						}
					}

					// Fix previous input
					$form_core = str_replace( 'value=""', 'value="' . esc_attr( wppa_get( 'searchstring' ) ) . '"', $form_core );

					// Fix placeholder
					$form_core = preg_replace( '/placeholder=\"[^\"]*/', 'placeholder="' . esc_attr( wppa_opt( 'search_placeholder' ) ), $form_core );
				}

				// Insert
				$result .= $form_core;

			$result .= '
			</div>';

		// Close item wrapper
		$result .= '
		</div>';

		$result .= '
		<div style="clear:both"></div>';

		// The hidden inputs and sub/root checkboxes
		if ( $force_root ) {
			$result .= '
			<input
				type="hidden"
				name="wppa-forceroot"
				value="' . $force_root . '"
				/>';
		}
		$result .= '
		<input
			type="hidden"
			name="wppa-searchroot"
			class="wppa-search-root-id"
			value="' . $root . '"
			/>';
			if ( $rt && ! $force_root ) {
				$result .= '
				<div style="clear:both" ></div>
				<small class="wppa-search-root" style="margin:0;padding:4px 0 0">' .
					wppa_display_root( $root ) . '
				</small>
				<div style="clear:both;' . $fontsize . '" >
					<input type="checkbox" name="wppa-rootsearch" class="wppa-rootbox" ' . $rootboxset . ' /> ' .
					wppa_opt( 'search_in_section' ) . '
				</div>';
			}
			if ( $sub ) {
				$result .= '
				<div style="clear:both" ></div>
				<small class="wppa-display-searchstring" style="margin:0;padding:4px 0 0">' .
					$wppa_session['display_searchstring'] . '
				</small>
				<div style="clear:both;' . $fontsize . '" >
					<input
						type="checkbox"
						name="wppa-subsearch"
						class="wppa-search-sub-box"' .
						( empty( $wppa_session['display_searchstring'] ) ? ' disabled' : '' ) . '
						onchange="wppaSubboxChange(this)"
					/> ' .
					wppa_opt( 'search_in_results' ) . '
				</div>';
			}
			$result .= '
	</form>';

	return $result;
}

// The supersearch box
function wppa_supersearch_box() {

	if ( is_feed() ) return;

	wppa_container( 'open' );

	wppa_out( '
		<div
			id="wppa-search-' . wppa( 'mocc' ) . '"
			class="wppa-box wppa-search"
			>' .
			wppa_get_supersearch_html() . '
			<div class="wppa-clear" ></div>
		</div>' );

	wppa_container( 'close' );
}

// Get supersearch html
function wppa_get_supersearch_html() {
global $wpdb;
global $wppa_session;
global $wppa_supported_camara_brands;
global $albums_used;
global $photos_used;

	// Init
	$albums_used = '*';
	$photos_used = '*';
	$mocc 		= wppa( 'mocc' );
	$page 		= wppa_get_the_landing_page( 	'supersearch_linkpage',
												__( 'Photo search results' ,'wp-photo-album-plus' )
											);
	$pagelink 	= get_page_link( $page );
	$fontsize 	= wppa_in_widget() ? 'font-size: 9px;' : '';
	$query 		= "SELECT id, name, owner FROM $wpdb->wppa_albums
				   ORDER BY name";
	$albums 	= $wpdb->get_results( $query, ARRAY_A );
	$query 		= "SELECT DISTINCT name FROM $wpdb->wppa_photos
				   WHERE status <> 'pending'
				   AND status <> 'scheduled'
				   AND album > 0
				   ORDER BY name LIMIT 1000";
	$photonames	= $wpdb->get_results( $query, ARRAY_A );
	$query 		= "SELECT owner FROM $wpdb->wppa_photos
				   WHERE status <> 'pending'
				   AND status <> 'scheduled'
				   AND album > 0
				   ORDER BY owner";
	$ownerlist 	= $wpdb->get_results( $query, ARRAY_A );
	$catlist 	= wppa_get_catlist();
	$taglist 	= wppa_get_taglist();
	$ss_data 	= explode( ',', $wppa_session['supersearch'] );
	if ( count( $ss_data ) < '4' ) {
		$ss_data = array( '', '', '', '' );
	}
	$ss_cats 	= ( $ss_data['0'] == 'a' && $ss_data['1'] == 'c' ) ? explode( '.', $ss_data['3'] ) : array();
	$ss_tags 	= ( $ss_data['0'] == 'p' && $ss_data['1'] == 'g' ) ? explode( '.', $ss_data['3'] ) : array();
	$ss_data['3'] = str_replace( '...', '***', $ss_data['3'] );
	$ss_atxt 	= ( $ss_data['0'] == 'a' && $ss_data['1'] == 't' ) ? explode( '.', $ss_data['3'] ) : array();
	foreach( array_keys( $ss_atxt ) as $key ) {
		$ss_atxt[$key] = str_replace( '***', '...', $ss_atxt[$key] );
	}
	$ss_ptxt 	= ( $ss_data['0'] == 'p' && $ss_data['1'] == 't' ) ? explode( '.', $ss_data['3'] ) : array();
	foreach( array_keys( $ss_ptxt ) as $key ) {
		$ss_ptxt[$key] = str_replace( '***', '...', $ss_ptxt[$key] );
	}
	$ss_data['3'] = str_replace( '***', '...', $ss_data['3'] );

	$query 		= "SELECT slug FROM $wpdb->wppa_index
				   WHERE albums <> ''
				   ORDER BY slug";
	$albumtxt 	= $wpdb->get_results( $query, ARRAY_A );
	$query 		= "SELECT slug FROM $wpdb->wppa_index
				   WHERE photos <> ''
				   ORDER BY slug";
	$phototxt 	= $wpdb->get_results( $query, ARRAY_A );

	// IPTC
	$iptclist 	= wppa_switch( 'save_iptc' ) ?
					$wpdb->get_results( "SELECT tag, description FROM $wpdb->wppa_iptc
										 WHERE photo = '0'
										 AND status <> 'hide'", ARRAY_A ) : array();

	// Translate (for multilanguage qTranslate-able labels )
	if ( ! empty( $iptclist ) ) {
		foreach( array_keys( $iptclist ) as $idx ) {
			$iptclist[$idx]['description'] = __( $iptclist[$idx]['description'] );
		}
	}

	// Sort alphabetically
	$iptclist = wppa_array_sort( $iptclist, 'description' );

	// EXIF
	$exiflist 	= wppa_switch( 'save_exif' ) ?
					$wpdb->get_results( "SELECT tag, description, status FROM $wpdb->wppa_exif
										 WHERE photo = '0'
										 AND status <> 'hide'", ARRAY_A ) : array();

	// Translate (for multilanguage qTranslate-able labels), // or remove if no non-empty items
	if ( ! empty( $exiflist ) ) {
		foreach( array_keys( $exiflist ) as $idx ) {
			$exiflist[$idx]['description'] = __( $exiflist[$idx]['description'] );
		}
	}

	// Sort alphabetically
	$exiflist = wppa_array_sort( $exiflist, 'description' );

	// Check for empty albums
	if ( wppa_switch( 'skip_empty_albums' ) ) {

		$user = wppa_get_user();
		if ( is_array( $albums ) ) foreach ( array_keys( $albums ) as $albumkey ) {
			$albumid 	= $albums[$albumkey]['id'];
			$albumowner = $albums[$albumkey]['owner'];
			$treecount 	= wppa_get_treecounts_a( $albums[$albumkey]['id'] );
			$photocount = $treecount['treephotos'];
			$icanupload = wppa_switch( 'user_upload_on' ) && $albumowner == '--- public ---';
			if ( ! $photocount && ! wppa_user_is_admin() && $user != $albumowner && ! $icanupload ) {
				unset( $albums[$albumkey] );
			}
		}
	}
	if ( empty( $albums ) ) $albums = array();

	// Compress photonames if partial length search
	if ( wppa_opt( 'ss_name_max' ) ) {
		$maxl = wppa_opt( 'ss_name_max' );
		$last = '';
		foreach ( array_keys( $photonames ) as $key ) {
			if ( strlen( $photonames[$key]['name'] ) > $maxl ) {
				$photonames[$key]['name'] = substr( $photonames[$key]['name'], 0, $maxl ) . '...';
			}
			if ( $photonames[$key]['name'] == $last ) {
				unset( $photonames[$key] );
			}
			else {
				$last = $photonames[$key]['name'];
			}
		}
	}

	// Compress phototxt if partial length search
	if ( wppa_opt( 'ss_text_max' ) ) {
		$maxl = wppa_opt( 'ss_text_max' );
		$last = '';
		foreach ( array_keys( $phototxt ) as $key ) {
			if ( strlen( $phototxt[$key]['slug'] ) > $maxl ) {
				$phototxt[$key]['slug'] = substr( $phototxt[$key]['slug'], 0, $maxl ) . '...';
			}
			if ( $phototxt[$key]['slug'] == $last ) {
				unset( $phototxt[$key] );
			}
			else {
				$last = $phototxt[$key]['slug'];
			}
		}
	}

	// Remove dup photo owners
	$last = '';
	foreach( array_keys( $ownerlist ) as $key ) {
		if ( $ownerlist[$key]['owner'] == $last ) {
			unset( $ownerlist[$key] );
		}
		else {
			$last = $ownerlist[$key]['owner'];
		}
	}

	// Make the html
	$id = 'wppa_searchform_' . $mocc;
	$result = '
	<form
		id="' . $id . '"
		action="'.$pagelink.'"
		method="post"
		class="widget_search"
		>
		<input
			type="hidden"
			id="wppa-ss-pageurl-' . $mocc . '"
			name="wppa-ss-pageurl"' .
			' value="'.$pagelink.'"' .
		' />';

		// album or photo
		$id = 'wppa-ss-pa-' . $mocc;
		$result .= '
		<select
			id="' . $id . '"
			class="wppa-supersearch-2"
			name="wppa-ss-pa"
			style="margin:2px;padding:0;vertical-align:top;float:left"
			onchange="wppaSuperSearchSelect(' . $mocc . ');"
			onwheel="event.stopPropagation();"
			size="2"
			>
			<option
				value="a"' .
				( $ss_data['0'] == 'a' ? ' selected' : '' ) . '
				>' .
				__('Albums', 'wp-photo-album-plus' ) . '
			</option>
			<option
				value="p"' .
				( $ss_data['0'] == 'p' ? ' selected' : '' ) . '
				>' .
				__('Photos', 'wp-photo-album-plus' ) . '
			</option>
		</select>';

		// album
		$id = 'wppa-ss-albumopt-' . $mocc;
		$result .= '
		<select
			id="' . $id . '"
			class="wppa-supersearch-' . ( ! empty( $catlist ) ? '3' : '2' ) . '"
			name="wppa-ss-albumopt"
			style="display:none;margin:2px;padding:0;vertical-align:top;float:left"
			onchange="wppaSuperSearchSelect(' . $mocc . ');"
			onwheel="event.stopPropagation();"
			size="' . ( ! empty( $catlist ) ? '3' : '2' ) . '"
			>';
			if ( ! empty( $catlist ) ) {
				$result .= '
				<option
					value="c"' .
					( $ss_data['0'] == 'a' && $ss_data['1'] == 'c' ? ' selected' : '' ) . '
					>' .
					__( 'Category', 'wp-photo-album-plus' ) . '
				</option>';
			}
			$result .= '
			<option
				value="n"' .
				( $ss_data['0'] == 'a' && $ss_data['1'] == 'n' ? ' selected' : '' ) . '
				>' .
				__( 'Name', 'wp-photo-album-plus' ) . '
			</option>
			<option
				value="t"' .
				( $ss_data['0'] == 'a' && $ss_data['1'] == 't' ? ' selected' : '' ) . '
				>' .
				__( 'Text', 'wp-photo-album-plus' ) . '
			</option>
		</select>';

		// album category
		if ( ! empty( $catlist ) ) {
			$id = 'wppa-ss-albumcat-' . $mocc;
			$result .= '
			<select
				id="' . $id . '"
				class="wppa-supersearch-' . ( min( count( $catlist ), '6' ) ) . '"
				name="wppa-ss-albumcat"
				style="display:none;margin:2px;padding:0;vertical-align:top;float:left"
				onchange="wppaSuperSearchSelect(' . $mocc . ');"
				onwheel="event.stopPropagation();"
				size="' . ( min( count( $catlist ), '6' ) ) . '"
				multiple
				title="' .
					esc_attr( __( 'CTRL+Click to add/remove option.', 'wp-photo-album-plus' ) ) .
					esc_attr( __( 'Items must meet all selected options.', 'wp-photo-album-plus' ) ) . '"
				>';
				foreach ( array_keys( $catlist ) as $cat ) {
					$sel = in_array ( $cat, $ss_cats );
					$result .= '
					<option
						value="' . $cat . '"
						class="' . $id . '"' .
						( $sel ? ' selected' : '' ) . '
						>' .
						$cat . '
					</option>';
				}
			$result .= '
			</select>';
		}

		// album name
		$id = 'wppa-ss-albumname-' . $mocc;
		$result .= '
		<select
			id="' . $id . '"
			class="wppa-supersearch-' . ( min( count( $albums ), '6' ) ) . '"
			name="wppa-ss-albumname"
			style="display:none;margin:2px;padding:0;vertical-align:top;float:left"
			onchange="wppaSuperSearchSelect(' . $mocc . ');"
			onwheel="event.stopPropagation();"
			size="' . ( min( count( $albums ), '6' ) ) . '"
			>';
			foreach ( $albums as $album ) {
				$name = stripslashes( $album['name'] );
				$sel = ( $ss_data['3'] == $name && $ss_data['0'] == 'a' && $ss_data['1'] == 'n' );
				$result .= '
				<option
					value="' . esc_attr( $name ) . '"' .
					( $sel ? ' selected' : '' ) . '
					>' .
					__( $name ) . '
				</option>';
			}
		$result .=
		'</select>';

		// album text
		$id = 'wppa-ss-albumtext-' . $mocc;
		$result .= '
		<select
			id="' . $id . '"
			class="wppa-supersearch-' . ( min( count( $albumtxt ), '6' ) ) . '"
			name="wppa-ss-albumtext"
			style="display:none;margin:2px;padding:0;vertical-align:top;float:left"
			onchange="wppaSuperSearchSelect(' . $mocc . ');"
			onwheel="event.stopPropagation();"
			size="' . ( min( count( $albumtxt ), '6' ) ) . '"
			multiple
			title="' .
				esc_attr( __( 'CTRL+Click to add/remove option.', 'wp-photo-album-plus' ) ) .
				esc_attr( __( 'Items must meet all selected options.', 'wp-photo-album-plus' ) ) . '"
			>';
			foreach ( $albumtxt as $txt ) {
				$text = $txt['slug'];
				$sel = in_array ( $text, $ss_atxt );
				$result .= '
				<option
					value="' . $text . '"
					class="' . $id . '"' .
					( $sel ? ' selected' : '' ) . '
					>' .
					$text . '
				</option>';
			}
		$result .= '
		</select>';

		// photo
		$n = '1' +
			( count( $ownerlist ) > '1' ) +
			( ! empty( $taglist ) ) +
			'1' +
			( wppa_switch( 'save_iptc' ) ) +
			( wppa_switch( 'save_exif' ) );
		$result .= '
		<select
			id="wppa-ss-photoopt-' . $mocc . '"
			class="wppa-supersearch-' . $n . '"
			name="wppa-ss-photoopt"
			style="display:none;margin:2px;padding:0;vertical-align:top;float:left"
			onchange="wppaSuperSearchSelect(' . $mocc . ');"
			onwheel="event.stopPropagation();"
			size="' . $n . '"
			>
			<option
				value="n"' .
				( $ss_data['0'] == 'p' && $ss_data['1'] == 'n' ? 'selected ' : '' ) . '
				>' .
				__( 'Name', 'wp-photo-album-plus' ) . '
			</option>';
			if ( count( $ownerlist ) > '1' ) {
				$result .= '
				<option
					value="o"' .
					( $ss_data['0'] == 'p' && $ss_data['1'] == 'o' ? 'selected ' : '' ) . '
					>' .
						__( 'Owner', 'wp-photo-album-plus' ) . '
				</option>';
			}
			if ( ! empty( $taglist ) ) {
				$result .= '
				<option
					value="g"' .
					( $ss_data['0'] == 'p' && $ss_data['1'] == 'g' ? 'selected ' : '' ) . '
					>' .
					__( 'Tag', 'wp-photo-album-plus' ) . '
				</option>';
			}
			$result .= '
			<option' . '
				value="t"' .
				( $ss_data['0'] == 'p' && $ss_data['1'] == 't' ? 'selected ' : '' ) . '
				>' .
				__( 'Text', 'wp-photo-album-plus' ) . '
			</option>';
			if ( wppa_switch( 'save_iptc' ) ) {
				$result .= '
				<option
					value="i"' .
					( $ss_data['0'] == 'p' && $ss_data['1'] == 'i' ? 'selected ' : '' ) . '
					>' .
					__( 'Iptc', 'wp-photo-album-plus' ) . '
				</option>';
			}
			if ( wppa_switch( 'save_exif' ) ) {
				$result .= '
				<option
					value="e"' .
					( $ss_data['0'] == 'p' && $ss_data['1'] == 'e' ? 'selected ' : '' ) . '
					>' .
					__( 'Exif', 'wp-photo-album-plus' ) . '
				</option>';
			}
		$result .= '
		</select>';

		// photo name
		$id = 'wppa-ss-photoname-' . $mocc;
		$result .= '
		<select
			id="' . $id . '"
			class="wppa-supersearch-' . min( count( $photonames ), '6' ) . '"
			name="wppa-ss-photoname"
			style="display:none;margin:2px;padding:0;vertical-align:top;float:left"
			onchange="wppaSuperSearchSelect(' . $mocc . ');"
			onwheel="event.stopPropagation();"
			size="' . min( count( $photonames ), '6' ) . '"
			>';
			foreach ( $photonames as $photo ) {
				$name = stripslashes( $photo['name'] );
				$sel = ( $ss_data['3'] == $name && $ss_data['0'] == 'p' && $ss_data['1'] == 'n' );
				$result .= '
				<option
					value="' . esc_attr( $photo['name'] ) . '"' .
					( $sel ? ' selected' : '' ) . '
					>' .
					__( $name ) . '
				</option>';
			}
		$result .= '
		</select>';

		// photo owner
		$id = 'wppa-ss-photoowner-' . $mocc;
		$result .= '
		<select
			id="' . $id . '"
			class="wppa-supersearch-' . min( count( $ownerlist ), '6' ) . '"
			name="wppa-ss-photoowner"
			style="display:none;margin:2px;padding:0;vertical-align:top;float:left"
			onchange="wppaSuperSearchSelect(' . $mocc . ');"
			onwheel="event.stopPropagation();"
			size="' . ( min( count( $ownerlist ), '6' ) ) . '"
			>';
			foreach ( $ownerlist as $photo ) {
				$owner = $photo['owner'];
				$sel = ( $ss_data['3'] == $owner && $ss_data['0'] == 'p' && $ss_data['1'] == 'o' );
				$result .= '
				<option
					value="' . $owner . '"' .
					( $sel ? ' selected' : '' ) . '
					>' .
					$owner . '
				</option>';
			}
		$result .= '
		</select>';

		// photo tag
		if ( ! empty( $taglist ) ) {
			$id = 'wppa-ss-phototag-' . $mocc;
			$result .= '
			<select
				id="' . $id . '"
				class="wppa-supersearch-' . min( count( $taglist ), '6' ) . '"
				name="wppa-ss-phototag"
				style="display:none;margin:2px;padding:0;vertical-align:top;float:left"
				onchange="wppaSuperSearchSelect(' . $mocc . ');"
				onwheel="event.stopPropagation();"
				size="' . ( min( count( $taglist ), '6' ) ) . '"
				multiple
				title="' .
					esc_attr( __( 'CTRL+Click to add/remove option.', 'wp-photo-album-plus' ) ) .
					esc_attr( __( 'Items must meet all selected options.', 'wp-photo-album-plus' ) ) . '"
				>';
				foreach ( array_keys( $taglist ) as $tag ) {
					$sel = in_array ( $tag, $ss_tags );
					$result .= '
					<option
						value="'.$tag.'"
						class="' . $id . '"' .
						( $sel ? ' selected' : '' ) . '
						>' .
						$tag . '
					</option>';
				}
			$result .= '
			</select>';
		}

		// photo text
		$id = 'wppa-ss-phototext-' . $mocc;
		$result .= '
		<select
			id="' . $id . '"
			class="wppa-supersearch-' . min( count( $phototxt ), '6' ) . '"
			name="wppa-ss-phototext"
			style="display:none;margin:2px;padding:0;vertical-align:top;float:left"
			onchange="wppaSuperSearchSelect(' . $mocc . ');"
			onwheel="event.stopPropagation();"
			size="' . ( min( count( $phototxt ), '6' ) ) . '"
			multiple
			title="' .
				esc_attr( __( 'CTRL+Click to add/remove option.', 'wp-photo-album-plus' ) ) .
				esc_attr( __( 'Items must meet all selected options.', 'wp-photo-album-plus' ) ) . '"
			>';
			foreach ( $phototxt as $txt ) {
				$text 	= $txt['slug'];
				$sel 	= in_array ( $text, $ss_ptxt );
				$result .= '
				<option
					value="' . $text . '"
					class="' . $id . '"' .
					( $sel ? ' selected' : '' ) . '
					>' .
					$text . '
				</option>';
			}
		$result .= '
		</select>';

		// photo iptc
		$result .= '
		<select
			id="wppa-ss-photoiptc-' . $mocc . '"
			class="wppa-supersearch-' . min( count( $iptclist ), '6' ) . '"
			name="wppa-ss-photoiptc"
			style="display:none;margin:2px;padding:0;vertical-align:top;float:left"
			onchange="wppaSuperSearchSelect(' . $mocc . ');"
			onwheel="event.stopPropagation();"
			size="' . min( count( $iptclist ), '6' ) . '"
			>';
			$reftag = str_replace( 'H', '#', $ss_data['2'] );
			foreach ( $iptclist as $item ) {
				$tag = $item['tag'];
				$sel = ( $reftag == $tag && $ss_data['0'] = 'p' && $ss_data['1'] == 'i' );
				$result .= '
				<option
					value="' . $tag . '"' .
					( $sel ? ' selected' : '' ) . '
					>' .
					rtrim( __( $item['description'], 'wp-photo-album-plus' ), " \n\r\t\v\0:" ) . '
				</option>';
			}
		$result .= '
		</select>';

		// Iptc items
		$result .= '
		<select
			id="wppa-ss-iptcopts-' . $mocc . '"
			class="wppa-supersearch-6"
			name="wppa-ss-iptcopts"
			style="display:none;margin:2px;padding:0;vertical-align:top;float:left"
			size="6"
			onchange="wppaSuperSearchSelect(' . $mocc . ')"
			onwheel="event.stopPropagation();"
			>
		</select>';

		// photo exif
		$result .= '
		<select
			id="wppa-ss-photoexif-' . $mocc . '"
			class="wppa-supersearch-6"
			name="wppa-ss-photoexif"
			style="display:none;margin:2px;padding:0;vertical-align:top;float:left"
			onchange="wppaSuperSearchSelect(' . $mocc . ');"
			onwheel="event.stopPropagation();"
			size="' . min( count( $exiflist ), '6' ) . '"
			>';
			$reftag = str_replace( 'H', '#', $ss_data['2'] );

			// Process all tags
			$options_array = array();
			foreach ( $exiflist as $item ) {
				$tag = $item['tag'];

				// Add brand specific tagname(s)
				$brandfound = false;
				foreach( $wppa_supported_camara_brands as $brand ) {
					$brtagnam = trim( wppa_exif_tagname( $tag, $brand, 'brandonly' ), ': ' );
					if ( $brtagnam ) {
						$options_array[] = array( 'tag' => $tag . $brand, 'desc' => $brtagnam . ' (' . ucfirst( strtolower( $brand ) ) . ')' );
						$brandfound = true;
					}
				}

				// Add generic only if not undefined
				$desc = __( $item['description'], 'wp-photo-album-plus' );
				if ( substr( $desc, 0, 12 ) != 'UndefinedTag' ) {
					$options_array[] = array( 'tag' => $tag, 'desc' => trim( __( $item['description'], 'wp-photo-album-plus' ), ': ' ) );
				}
			}

			// Sort options
			$options_array = wppa_array_sort( $options_array, 'desc' );

			// Make the options html
			foreach ( $options_array as $item ) {
				$tag = $item['tag'];
				$desc = $item['desc'];
				$sel = ( $reftag == $tag && $ss_data['0'] == 'p' && $ss_data['1'] == 'e' );

				$result .= '
				<option
					value="' . $tag . '"' .
					( $sel ? ' selected' : '' ) . '
					>' .
					$desc . '
				</option>';
			}
		$result .= '
		</select>';

		// Exif items
		$result .= '
		<select
			id="wppa-ss-exifopts-' . $mocc . '"
			class="wppa-supersearch-6"
			name="wppa-ss-exifopts"
			style="display:none;margin:2px;padding:0;vertical-align:top;float:left"
			size="6"
			onchange="wppaSuperSearchSelect(' . $mocc . ')"
			onwheel="event.stopPropagation();"
			>
		</select>';

		// The spinner
		$result .= '
		<img
			id="wppa-ss-spinner-' . $mocc . '"
			src="' . wppa_get_imgdir() . '/spinner.gif' . '"
			style="margin:0 4px;display:none;"
		/>';

		// The button
		$result .= '
		<input
			type="button"
			id="wppa-ss-button-' . $mocc . '"
			data-mocc="' . $mocc . '"
			class="wppa-ss-button"
			value="' . __( 'Submit', 'wp-photo-album-plus' ) . '"
			style="vertical-align:top;margin:2px;display:none;"
			onclick="wppaSuperSearchSelect(' . $mocc . ' , true)"
		/>';

	$result .= '
	</form>';

	return $result;
}

// Superview box
function wppa_superview_box( $album_root = '0', $sort = true ) {

	if ( is_feed() ) return;

	wppa_container( 'open' );

	wppa_out( '
		<div
			id="wppa-superview-' . wppa( 'mocc' ) . '"
			class="wppa-box wppa-superview"
			>' .
			wppa_get_superview_html( $album_root, $sort ) . '
			<div class="wppa-clear" >
			</div>
		</div>' );

	wppa_container( 'close' );
}

// Get superview html
function wppa_get_superview_html( $album_root = '0', $sort = true ) {
global $wppa_session;

	$page = wppa_get_the_landing_page( 	'super_view_linkpage',
										__( 'Super View Photos' ,'wp-photo-album-plus' )
									);
	$url = get_permalink( $page );

	$onsubmit = 'if (!jQuery(\'#super-album\').val()) { alert(\''.__('Please select an album', 'wp-photo-album-plus').'\'); return false }';

	$result = '
	<div>
		<form action="' . $url . '" method="get" onsubmit="' . $onsubmit . '">
			<label>' . __( 'Album:', 'wp-photo-album-plus' ) . '</label>
			<select id="super-album" name="wppa-album" style="clear:left">' .
				wppa_album_select_a( array( 'selected' 			=> $wppa_session['superalbum'],
											'addpleaseselect' 	=> true,
											'root' 				=> $album_root,
											'content' 			=> true,
											'sort'				=> $sort,
											'path' 				=> ( ! wppa_in_widget() ),
											'crypt' 			=> true,
											 ) ) . '
			</select><br>
			<input
				type="radio"
				name="wppa-slide"
				value="0" ' .
				( $wppa_session['superview'] == 'thumbs' ? 'checked' : '' ) . '
			/>' .
			__( 'Thumbnails', 'wp-photo-album-plus' ) . '
			<br>
			<input
				type="radio"
				name="wppa-slide"
				value="1" ' .
				( $wppa_session['superview'] == 'slide' ? 'checked' : '' ) . '
			/>' .
			__( 'Slideshow', 'wp-photo-album-plus' ) . '
			<br>
			<input type="hidden" name="wppa-occur" value="1">
			<input type="hidden" name="wppa-superview" value="1">
			<input type="submit" value="' . __( 'Submit', 'wp-photo-album-plus' ) . '">
		</form>
	</div>';

	return $result;
}

// The admins choice box
function wppa_admins_choice_box( $admins ) {

	if ( is_feed() ) return;

	wppa_container( 'open' );

	wppa_out( '
		<div
			id="wppa-adminschoice-' . wppa( 'mocc' ) . '"
			class="wppa-box wppa-adminschoice"
			>' .
			wppa_get_admins_choice_html( $admins ) . '
			<div class="wppa-clear" ></div>
		</div>' );

	wppa_container( 'close' );
}

// The admins choice html
function wppa_get_admins_choice_html( $admins ) {

	// Find zip dir
	$zipsdir = WPPA_UPLOAD_PATH.'/zips/';

	// Find all zipfiles
	if ( wppa_switch( 'admins_choice_meonly' ) ) {
		$zipfiles = wppa_glob($zipsdir.wppa_get_user().'.zip');
	}
	else {
		$zipfiles = wppa_glob($zipsdir.'*.zip');
	}

	// admins specified?
	if ( $admins ) {
		$admin_arr = explode( ',', $admins );
	}
	else {
		$admin_arr = false;
	}

	if ( $zipfiles ) {

		$result = '
		<ul' . ( ! wppa( 'in_widget' ) ? ' style="list-style-position:inside;margin:0;padding:0;"' : '' ) . ' >';

		// Compose the current users zip filename
		$myzip = $zipsdir.wppa_get_user().'.zip';

		foreach( $zipfiles as $zipfile ) {

			// Find zipfiles user
			$user = wppa_strip_ext( basename( $zipfile ) );
			$full_user = wppa_get_user_by( 'login', $user );
			$user = $full_user->display_name;
			$login = $full_user->user_login;

			// Do we need this one?
			if ( ! $admin_arr || in_array( $user, $admin_arr ) || in_array( $login, $admin_arr ) ) {

				// Check file existance
				if ( is_file( $zipfile ) ) {

					// Open zip
					$wppa_zip = new ZipArchive;
					$wppa_zip->open( $zipfile );
					if ( $wppa_zip ) {

						// Look photos up in zip
						$title = '';
						for( $i = 0; $i < $wppa_zip->numFiles; $i++ ) {
							$stat = $wppa_zip->statIndex( $i );
							$title .= esc_attr($stat['name']) . "\n";
						}
						$result .= 	'
						<li title="' . $title . '" >
							<a href="'. WPPA_UPLOAD_URL.'/zips/'.basename($zipfile).'" >' .
								$user . '
							</a>';
							if ( $zipfile == $myzip ) {
								$result .= '
								<a
									onclick="wppaAjaxDeleteMyZip();"
									style="float:right;cursor:pointer"
								>' .
									__('Delete', 'wp-photo-album-plus' ) . '
								</a>';
							}
						$result .=	'
						</li>';
					}
				}
			}
		}
		$result .= 	'</ul>';
	}
	else {
		$result = __( 'No zipfiles available', 'wp-photo-album-plus' );
	}

	return $result;
}

// The tagcloud box
function wppa_tagcloud_box( $seltags = '', $minsize = '8', $maxsize = '24' ) {

	if ( is_feed() ) return;

	wppa_container( 'open' );

	wppa_out( '
		<div
			id="wppa-tagcloud-' . wppa( 'mocc' ) . '"
			class="wppa-box wppa-tagcloud"
			>' .
			wppa_get_tagcloud_html( $seltags, $minsize, $maxsize ) . '
			<div class="wppa-clear" ></div>
		</div>' );

	wppa_container( 'close' );
}

// Get html for tagcloud
function wppa_get_tagcloud_html( $seltags = '', $minsize = '8', $maxsize = '24' ) {

	$page 	= wppa_get_the_landing_page( 	'tagcloud_linkpage',
										__( 'Tagged photos' ,'wp-photo-album-plus' )
									);
	$oc 	= wppa_opt( 'tagcloud_linkpage_oc' );
	$result = '';
	if ( $page ) {
		if ( $page == '-1' ) {
			$hr = wppa_get_permalink();
		}
		else {
			$hr = wppa_get_permalink( $page );
		}
		if ( wppa_opt( 'tagcloud_linktype' ) == 'album' ) {
			$hr .= 'wppa-album=0&amp;wppa-cover=0&amp;wppa-occur='.$oc;
		}
		if ( wppa_opt( 'tagcloud_linktype' ) == 'slide' ) {
			$hr .= 'wppa-album=0&amp;wppa-cover=0&amp;wppa-occur='.$oc.'&amp;slide';
		}
	}
	else {
		$hr = '';
	}
	$tags = wppa_get_taglist( true );
	if ( $tags ) {
		$top = '0';
		foreach ( $tags as $tag ) {	// Find largest percentage
			if ( $tag['fraction'] > $top ) $top = $tag['fraction'];
		}
		if ( $top ) {
			$factor = ( $maxsize - $minsize ) / $top;
		}
		else $factor = '1.0';
		$selarr = $seltags ? explode( ',', $seltags ) : array();
		foreach ( $tags as $tag ) {
			if ( ! $seltags || in_array( $tag['tag'], $selarr ) ) {
				$href 		= $hr . '&amp;wppa-tag=' . urlencode( $tag['tag'] );
				$href 		= wppa_encrypt_url( $href );
				$title 		= sprintf( '%d photos - %s%%', $tag['count'], $tag['fraction'] * '100' );
				$name 		= $tag['tag'];
				if ( wppa_opt( 'tagcloud_formula' ) == 'quadratic' ) {
					$x 		= $minsize + $tag['fraction'] * $factor;
					$s 		= $minsize;
					$l 		= $maxsize;
					$size 	= round(sqrt(($x-$s)/($l-$s))*($l-$s)+$s);
				}
				elseif ( wppa_opt( 'tagcloud_formula' ) == 'cubic' ) {
					$x 		= $minsize + $tag['fraction'] * $factor;
					$s 		= $minsize;
					$l 		= $maxsize;
					$size 	= round(pow(($x-$s)/($l-$s),1/3)*($l-$s)+$s);
				}
				else {
					$size 	= round( $minsize + $tag['fraction'] * $factor );
				}
				$result    .= 	'
				<a
					href="' . $href . '"
					title="' . $title . '"
					style="font-size:' . $size . 'px;"
					>' .
					ucfirst( __( $name ) ) . '
				</a> ';
			}
		}
	}

	return $result;
}

// The multitag box
function wppa_multitag_box( $nperline = '2', $seltags = '' ) {

	if ( is_feed() ) return;

	wppa_container( 'open' );

	wppa_out( '
		<div
			id="wppa-multitag-' . wppa( 'mocc' ) . '"
			class="wppa-box wppa-multitag"
			>' .
			wppa_get_multitag_html( $nperline, $seltags ) . '
			<div class="wppa-clear" ></div>
		</div>' );

	wppa_container( 'close' );
}

// The html for multitag widget
function wppa_get_multitag_html( $nperline = '2', $seltags = '' ) {

	$or_only 	= wppa_switch( 'tags_or_only' );
	$not_on 	= wppa_switch( 'tags_not_on' );
	$page 		= wppa_get_the_landing_page( 	'multitag_linkpage',
										__( 'Multi Tagged photos' ,'wp-photo-album-plus' )
									);
	$oc 		= wppa_opt( 'multitag_linkpage_oc' );
	$mocc 		= wppa( 'mocc' );
	$result 	= '';
	if ( $page ) {
		if ( $page == '-1' ) {
			$hr = wppa_get_permalink();
		}
		else {
			$hr = wppa_get_permalink( $page );
		}
		$hr = str_replace( '&amp;', '&', $hr );
		if ( wppa_opt( 'multitag_linktype' ) == 'album' ) {
			$hr .= 'wppa-album=0&wppa-cover=0&wppa-occur='.$oc;
		}
		if ( wppa_opt( 'multitag_linktype' ) == 'slide' ) {
			$hr .= 'wppa-album=0&wppa-cover=0&wppa-occur='.$oc.'&slide';
		}
	}
	else {
		$hr = '';
	}
	$tags = wppa_get_taglist( true );

	$the_js = '
		function wppaProcessMultiTagRequest'.wppa('mocc').'() {
			var any = false;
			var url = "' . wppa_encrypt_url( $hr ) . '";
			var andor;
			var sep;

			if ( jQuery( "#inverse-' . $mocc . '" ).prop( "checked" ) ) {
				url += "&wppa-inv=1";
			}
			url += "&wppa-tag=";
			';

			if ( $or_only ) {
				$the_js .= '
				andor 	= "or";
				sep 	= ";";
				';
			}
			else {
				$the_js .= '
				andor 	= "and";
				sep 	= ",";
				if ( jQuery( "#andoror-' . $mocc . '" ).prop( "checked" ) ) {
					andor 	= "or";
					sep 	= ";";
				}';
			}

			$selarr = $seltags ? explode( ',', $seltags ) : array();
			if ( $tags ) foreach ( $tags as $tag ) {
				if ( ! $seltags || in_array( $tag['tag'], $selarr ) ) {
					$the_js .= '
					if ( document.getElementById( "wppa-' . $mocc . '-' . str_replace( ' ', '_', $tag['tag'] ) . '" ).checked ) {
						url+="' . urlencode( $tag['tag'] ) . '"+sep;
						any = true;
					}';
				}
			}

			$the_js .= '
			if ( any ) {
				document.location = url;
			}
			else {
				alert ( "' . __( 'Please check the tag(s) that the photos must have', 'wp-photo-album-plus' ) . '" );
			}
		}
	';
	wppa_add_inline_script( 'wppa', $the_js, true );

	$qtag = wppa_get( 'tag', '', 'text' );
	$andor = $or_only ? 'or' : 'and'; // default
	if ( strpos( $qtag, ',' ) ) {
		$querystringtags = explode( ',',wppa_get( 'tag' ) );
	}
	elseif ( strpos( $qtag, ';' ) ) {
		$querystringtags = explode( ';', wppa_get( 'tag' ) );
		$andor = 'or';
	}
	else $querystringtags = wppa_get( 'tag' );

	if ( $tags ) {

		if ( ! $or_only || $not_on ) {
			$result .= 	'
			<table class="wppa-multitag-table">';
				if ( ! $or_only ) {
					$result .= '
					<tr>
						<td>
							<input
								class="radio"
								name="andor-' . $mocc . '"
								value="and"
								id="andorand-' . $mocc . '"
								type="radio"' .
								( $andor == 'and' ? ' checked' : '' ) . '
							/>&nbsp;' .
							__( 'And', 'wp-photo-album-plus' ) . '
						</td>
						<td>
							<input
								class="radio"
								name="andor-' . $mocc . '"
								value="or"
								id="andoror-' . $mocc . '"
								type="radio"' .
								( $andor == 'or' ? ' checked' : '' ) . '
							/>&nbsp;' .
							__( 'Or', 'wp-photo-album-plus' ) . '
						</td>
					</tr>';
				}
				if ( $not_on ) {
					$result .= '
					<tr>
						<td>
							<input
								type="checkbox"
								class="checkbox"
								name="inverse-' . $mocc . '"
								id="inverse-' . $mocc . '"' .
								( wppa_get( 'inv' ) ? ' checked' : '' ) . '
							/>&nbsp;' .
							__( 'Inverse selection', 'wp-photo-album-plus' ) . '
						</td>
						<td>
						</td>
					</tr>';
				}
			$result .= 	'</table>';
		}

		$count 		= '0';
		$checked 	= '';
		$tropen 	= false;

		$result 	.= '<table class="wppa-multitag-table" >';

		foreach ( $tags as $tag ) {
			if ( ! $seltags || in_array( $tag['tag'], $selarr ) ) {
				if ( $count % $nperline == '0' ) {
					$result .= '<tr>';
					$tropen = true;
				}
				if ( is_array( $querystringtags ) ) {
					$checked = in_array( $tag['tag'], $querystringtags ) ? ' checked' : ' ';
				}
				$result .= 	'
				<td
					style="padding-right:4px;"
					>
					<input
						type="checkbox"
						id="wppa-' . $mocc . '-' . str_replace( ' ', '_', $tag['tag'] ) . '"' .
						$checked . '
					/>&nbsp;' .
					str_replace( ' ', '&nbsp;', ucfirst( __( $tag['tag'] ) ) ) . '
				</td>';
				$count++;
				if ( $count % $nperline == '0' ) {
					$result .= '</tr>';
					$tropen = false;
				}
			}
		}

		if ( $tropen ) {
			while ( $count % $nperline != '0' ) {
				$result .= '<td></td>';
				$count++;
			}
			$result .= '</tr>';
		}
		$result .= '</table>';
		$result .= 	'
		<input
			type="button"
			onclick="wppaProcessMultiTagRequest' . $mocc . '()"
			value="' . __( 'Find!', 'wp-photo-album-plus' ) . '"
		/>';
	}

	return $result;
}

// Make html for sharebox
function wppa_get_share_html( $id, $key = '', $js = true, $single = false ) {
global $wppa_locale;

	$p = wppa_get_the_id();
	$p_void = explode( ',', wppa_opt( 'sm_void_pages' ) );
	if ( ! empty( $p_void ) && in_array( $p, $p_void ) ) return '';

	$do_it = false;
	if ( ! wppa( 'is_slideonly' ) || $key == 'lightbox' ) {
		if ( wppa_switch( 'share_on' ) && ! wppa_in_widget() ) $do_it = true;
		if ( wppa_switch( 'share_on_widget' ) && wppa_in_widget() ) $do_it = true;
		if ( wppa_switch( 'share_on_lightbox' ) ) $do_it = true;
	}
	if ( ! $do_it ) return '';

	// The share url
	if ( wppa_in_widget() ) {
		if ( wppa_opt( 'widget_sm_linktype' ) == 'home' ) {
			$share_url = home_url();
		}
		else {
			$share_url = 	get_permalink(
								wppa_get_the_landing_page( 'widget_sm_linkpage',
									__( 'Social media landing page' ,'wp-photo-album-plus' )
								)
							);
			$alb = wppa_get_photo_item( $id, 'album' );
			$oc = wppa_opt( 'widget_sm_linkpage_oc' );
			$share_url .= '?wppa-album='.$alb.'&wppa-photo='.$id.'&wppa-cover=0&wppa-occur='.$oc;
			if ( wppa_switch( 'share_single_image' ) || $single ) {
				$share_url .= '&wppa-single=1';
			}
		}
	}
	else {
		$share_url = wppa_get_image_page_url_by_id( $id, wppa_switch( 'share_single_image' ) );
		$share_url = str_replace( '&amp;', '&', $share_url );
	}

	$share_url = wppa_convert_to_pretty( wppa_encrypt_url( $share_url ), 'nonames' );

	// Protect url against making relative
	$share_url = wppa_protect_relative( $share_url );

	// The share title
	$photo_name = wppa_get_photo_name( $id );

	// The share description
	$photo_desc = wppa_html( wppa_get_photo_desc( $id ) );
	$photo_desc = strip_shortcodes( wppa_strip_tags( $photo_desc, 'all' ) );

	// The default description
	$site = str_replace( '&amp;', __( 'and', 'wp-photo-album-plus' ), get_bloginfo( 'name' ) );
	$see_on_site = sprintf( __( 'See this image on %s' ,'wp-photo-album-plus' ), $site );

	// The share image. Must be the fullsize image for facebook.
	// If you take the thumbnail, facebook takes a different image at random.
	$share_img = wppa_get_photo_url( $id );

	// The icon size
	if ( ( wppa_in_widget() && $key != 'lightbox' ) || $key == 'thumb' ) {
		$s = '16';
		$br = '2';
	}
	else {
		$s = wppa_opt( 'share_size' );
		$br = ceil( $s/8 );
	}

	// qr code
	if ( wppa_switch( 'share_qr' ) && $key != 'thumb' ) {
		$src 	= 	wppa_create_qrcode_cache( $share_url, '80' );
		$qr 	= 	'
		<div style="float:left; padding:2px">
			<img
				src="' . $src . '"
				title="' . esc_attr( $share_url ) . '"
				alt="' . __( 'QR code', 'wp-photo-album-plus' ) . '"
			/>
		</div>';
	}
	else {
		$qr = '';
	}

	// twitter share button
	if ( wppa_switch( 'share_twitter' ) ) {
		$tweet = urlencode( $see_on_site ) . ': ';
		$tweet_len = strlen( $tweet ) + '1';

		$tweet .= urlencode( $share_url );

		// find first '/' after 'http( s )://' rest doesnt count for twitter chars
		$url_len = strpos( $share_url, '/', 8 ) + 1;
		$tweet_len += ( $url_len > 1 ) ? $url_len : strlen( $share_url );

		$rest_len = 140 - $tweet_len;

		if ( wppa_switch( 'show_full_name' ) ) {
			if ( $rest_len > strlen( $photo_name ) ) {
				$tweet .= ' ' . urlencode( $photo_name );
				$rest_len -= strlen( $photo_name );
				$rest_len -= '2';
			}
			else {
				$tweet .= ' '. urlencode( substr( $photo_name, 0, $rest_len ) ) . '...';
				$rest_len -= strlen( substr( $photo_name, 0, $rest_len ) );
				$rest_len -= '5';
			}
		}

		if ( $photo_desc ) {
			if ( $rest_len > strlen( $photo_desc ) ) {
				$tweet .= ': ' . urlencode( $photo_desc );
			}
			elseif ( $rest_len > 8 ) {
				$tweet .= ': '. urlencode( substr( $photo_desc, 0, $rest_len ) ) . '...';
			}
		}

		$tweet = urlencode( $share_url );

		$tw = '
		<div
			class="wppa-share-icon"
			style="float:left; padding:0 2px;"
			>
			<a
				title="' . sprintf( __( 'Tweet %s on Twitter', 'wp-photo-album-plus' ), esc_attr( $photo_name ) ) . '"
				href="https://twitter.com/intent/tweet?text=' . $tweet . '"
				target="_blank"
				>';
				if ( wppa_switch( 'twitter_black' ) ) {
					$tw .= '
					<img
						src="' . wppa_get_imgdir() . 'twitter.svg' . '"
						style="height:' . $s . 'px;vertical-align:top;border-radius:50%;background-color:black"
						alt="' . esc_attr( __( 'Share on Twitter', 'wp-photo-album-plus' ) ) . '"
					/>';
				}
				else {
					$tw .= '
					<img
						src="' . wppa_get_imgdir() . 'twitter.png"
						style="height:' . $s . 'px;vertical-align:top;"
						alt="' . esc_attr( __( 'Share on Twitter', 'wp-photo-album-plus' ) ) . '"
					/>';
				}
				$tw .= '
			</a>
		</div>';
	}
	else {
		$tw = '';
	}

	// Pinterest
	$desc = urlencode( $see_on_site ).': '.urlencode( $photo_desc );
	if ( strlen( $desc ) > 500 ) $desc = substr( $desc, 0, 495 ).'...';
	if ( wppa_switch( 'share_pinterest' ) ) {
		$pi = '
		<div class="wppa-share-icon" style="float:left; padding:0 2px">
			<a
				title="' . sprintf( __( 'Share %s on Pinterest' ,'wp-photo-album-plus' ), esc_attr( $photo_name ) ) . '"
				href="http://pinterest.com/pin/create/button/?url=' . urlencode( $share_url ) .
							'&media=' . urlencode( str_replace( '/thumbs/', '/', $share_img ) ) .
							'&description=' . $desc .
							'"
				target="_blank"
				>';
				if ( wppa_switch( 'pinterest_black' ) ) {
					$pi .=
					'<img
						src="' . wppa_get_imgdir() . 'pinterest.svg' . '"
						style="height:' . $s . 'px;vertical-align:top;border-radius:50%;background-color:black"
						alt="' . esc_attr( __( 'Share on Pinterest', 'wp-photo-album-plus' ) ) . '"
					/>';
				}
				else {
					$pi .=
					'<img
						src="' . wppa_get_imgdir() . 'pinterest.png"
						style="height:' . $s . 'px;vertical-align:top;border-radius:' . $br . 'px"
						alt="' . esc_attr( __( 'Share on Pinterest', 'wp-photo-album-plus' ) ) . '"
					/>';
				}
			$pi .= '
			</a>
		</div>';
	}
	else {
		$pi = '';
	}

	// LinkedIn
	if ( wppa_switch( 'share_linkedin' ) && $key != 'thumb' && $key != 'lightbox' ) {
		$li = '
		<div class="wppa-share-icon" style="float:left; padding:0 2px">
			<a
				title="' . sprintf( __( 'Share %s on LinkedIn' ,'wp-photo-album-plus' ), esc_attr( $photo_name ) ) . '"
				href="https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode( $share_url ) . '"
				target="_blank"
				>';
				if ( wppa_switch( 'linkedin_black' ) ) {
					$li .= '
					<img
						src="' . wppa_get_imgdir() . 'linkedin.svg' . '"
						style="height:' . $s . 'px;vertical-align:top;border-radius:50%;background-color:black"
					/>';				}
				else {
					$li .= '
					<img
						src="' . wppa_get_imgdir() . 'linkedin.png' . '"
						style="height:' . $s . 'px;vertical-align:top;"
					/>';
				}
				$li .= '
			</a>
		</div>';
	}
	else {
		$li = '';
	}

	// Facebook
	$fb = '';
	$need_fb_init = false;
	$small = ( 'thumb' == $key );
	if ( 'lightbox' == $key ) {
		if ( wppa_switch( 'facebook_like' ) && wppa_switch( 'share_facebook' ) ) {
			$lbs = 'max-width:62px; max-height:96px; overflow:show;';
		}
		else {
			$lbs = 'max-width:62px; max-height:64px; overflow:show;';
		}
	}
	else {
		$lbs = '';
	}

	// Share
	if ( wppa_switch( 'share_facebook' ) && ! wppa_switch( 'facebook_like' ) ) {
		if ( $small ) {
			$fb .= '
			<div
				class="fb-share-button"
				style="float:left; padding:0 2px;"
				data-href="' . $share_url . '"
				data-type="icon"
				>
			</div>';
		}
		else {
			$disp = wppa_opt( 'fb_display' );
			if ( 'standard' == $disp ) {
				$disp = 'button';
			}
			$fb .= '
			<div
				class="fb-share-button"
				style="float:left; padding:0 2px; ' . $lbs . '"
				data-width="200"
				data-href="' . $share_url . '"
				data-type="' . $disp . '"
				>
			</div>';
		}
		$need_fb_init = true;
	}

	// Like
	if ( wppa_switch( 'facebook_like' ) && ! wppa_switch( 'share_facebook' ) ) {
		if ( $small ) {
			$fb .= '
			<div
				class="fb-like"
				style="float:left; padding:0 2px; "
				data-href="' . $share_url . '"
				data-layout="button"
				>
			</div>';
		}
		else {
			$fb .= '
			<div
				class="fb-like"
				style="float:left; padding:0 2px; ' . $lbs . '"
				data-width="200"
				data-href="' . $share_url . '"
				data-layout="' . wppa_opt( 'fb_display' ) . '"
				>
			</div>';
		}
		$need_fb_init = true;
	}

	// Like and share
	if ( wppa_switch( 'facebook_like' ) && wppa_switch( 'share_facebook' ) ) {
		if ( $small ) {
			$fb .= '
			<div
				class="fb-like"
				style="float:left; padding:0 2px; "
				data-href="' . $share_url . '"
				data-layout="button"
				data-action="like"
				data-show-faces="false"
				data-share="true"
				>
			</div>';
		}
		else {
			$fb .= '
			<div
				class="fb-like"
				style="float:left; padding:0 2px; ' . $lbs . '"
				data-width="200"
				data-href="' . $share_url . '"
				data-layout="' . wppa_opt( 'fb_display' ) . '"
				data-action="like"
				data-show-faces="false"
				data-share="true"
				>
			</div>';
		}
		$need_fb_init = true;
	}

	// Comments
	if ( wppa_switch( 'facebook_comments' ) && ! wppa_in_widget() && $key != 'thumb' && $key != 'lightbox' ) {
		$width = wppa( 'auto_colwidth' ) ? '100%' : wppa_get_container_width( true );
		if ( wppa_switch( 'facebook_comments' ) ) {
			$fb .= '
			<div style="clear:both"></div>
			<div class="wppa-fb-comments-title" style="color:blue">' .
				__( 'Comment on Facebook:', 'wp-photo-album-plus' ) . '
			</div>
			<div class="fb-comments" data-href="' . $share_url . '" data-width="' . $width . '"></div>';
			$need_fb_init = true;
		}
	}

	// Need init?
	if ( $need_fb_init ) {

		wppa_js( 'jQuery(document).ready(function(){wppaFbInit();});' );

		$need_fb_init = false;
	}

	return '<div class="wppa-share-' . $key . '" >' . $qr . $tw . $pi . $li . $fb . '<div style="clear:both"></div></div>';
}

// Make html for share a page/post
function wppa_get_share_page_html() {
global $wppa_locale;
global $wpdb;

	// The page/post id
	$p = wppa_get_the_ID();

	// The share url
	$share_url = wppa_convert_to_pretty( get_permalink( $p ) );

	// The share title
	$share_name = $wpdb->get_var( $wpdb->prepare( "SELECT post_title FROM $wpdb->posts
												   WHERE ID = %d", $p ) );

	// The share description
	$share_desc = $wpdb->get_var( $wpdb->prepare( "SELECT post_content FROM $wpdb->posts
												   WHERE ID = %d", $p ) );
	$share_desc = strip_tags( strip_shortcodes( $share_desc ) );
	if ( strlen( $share_desc ) > 150 ) {
		$share_desc = substr( $share_desc, 0, 120 ) . '...';
	}

	// The default description
	$site = str_replace( '&amp;', __( 'and', 'wp-photo-album-plus' ), get_bloginfo( 'name' ) );
	$see_on_site = sprintf( __( 'See this article on %s' ,'wp-photo-album-plus' ), $site );

	// The icon size
	$s = wppa_opt( 'share_size' );
	$br = ceil( $s/8 );

	// qr code
	if ( wppa_switch( 'share_qr' ) ) {
		$src 	= 	wppa_create_qrcode_cache( $share_url, '80' );
		$qr 	= 	'
		<div style="float:left; padding:2px">
			<img
				src="' . $src . '"
				title="' . esc_attr( $share_url ) . '"
				alt="' . __( 'QR code', 'wp-photo-album-plus' ) . '"
			/>
		</div>';
	}
	else {
		$qr = '';
	}

	// twitter share button
	if ( wppa_switch( 'share_twitter' ) ) {
		$tweet = urlencode( $see_on_site ) . ': ';
		$tweet_len = strlen( $tweet ) + '1';

		$tweet .= urlencode( $share_url );

		// find first '/' after 'http( s )://' rest doesnt count for twitter chars
		$url_len = strpos( $share_url, '/', 8 ) + 1;
		$tweet_len += ( $url_len > 1 ) ? $url_len : strlen( $share_url );

		$rest_len = 140 - $tweet_len;

		if ( $share_desc ) {
			if ( $rest_len > strlen( $share_desc ) ) {
				$tweet .= ': ' . urlencode( $share_desc );
			}
			elseif ( $rest_len > 8 ) {
				$tweet .= ': '. urlencode( substr( $share_desc, 0, $rest_len ) ) . '...';
			}
		}

		$tw = '
		<div class="wppa-share-icon" style="float:left; padding:0 2px">
			<a
				title="' . sprintf( __( 'Tweet %s on Twitter', 'wp-photo-album-plus' ), esc_attr( $share_name ) ) . '"
				href="https://twitter.com/intent/tweet?text=' . $tweet . '"
				target="_blank"
				>
				<img
					src="' . wppa_get_imgdir() . 'twitter.png"
					style="height:' . $s . 'px;vertical-align:top;"
					alt="' . esc_attr( __( 'Share on Twitter', 'wp-photo-album-plus' ) ) . '"
				/>
			</a>
		</div>';
	}
	else {
		$tw = '';
	}

	// Pinterest
	$pi = '';

	// LinkedIn
	$li = '';

	// Facebook
	$fb = '';
	$need_fb_init = false;

	// Share
	if ( wppa_switch( 'share_facebook' ) && ! wppa_switch( 'facebook_like' ) ) {

		$disp = wppa_opt( 'fb_display' );
		if ( 'standard' == $disp ) {
			$disp = 'button';
		}
		$fb .= '
		<div
			class="fb-share-button"
			style="float:left; padding:0 2px;"
			data-width="200"
			data-href="' . $share_url . '"
			data-type="' . $disp . '"
			>
		</div>';

		$need_fb_init = true;
	}

	// Like
	if ( wppa_switch( 'facebook_like' ) && ! wppa_switch( 'share_facebook' ) ) {

		$fb .= '
		<div
			class="fb-like"
			style="float:left; padding:0 2px;"
			data-width="200"
			data-href="' . $share_url . '"
			data-layout="' . wppa_opt( 'fb_display' ) . '"
			>
		</div>';

		$need_fb_init = true;
	}

	// Like and share
	if ( wppa_switch( 'facebook_like' ) && wppa_switch( 'share_facebook' ) ) {

		$fb .= '
		<div
			class="fb-like"
			style="float:left; padding:0 2px;"
			data-width="200"
			data-href="' . $share_url . '"
			data-layout="' . wppa_opt( 'fb_display' ) . '"
			data-action="like"
			data-show-faces="false"
			data-share="true"
			>
		</div>';

		$need_fb_init = true;
	}

	// Comments
	if ( wppa_switch( 'facebook_comments' ) ) {
		if ( wppa_switch( 'facebook_comments' ) ) {
			$fb .= '
			<div style="clear:both"></div>
			<div class="wppa-fb-comments-title" style="color:blue">' .
				__( 'Comment on Facebook:', 'wp-photo-album-plus' ) . '
			</div>
			<div class="fb-comments" data-href="' . $share_url . '" data-width="100%" ></div>';

			$need_fb_init = true;
		}
	}

	// Need init?
	if ( $need_fb_init ) {
		wppa_js( 'jQuery(document).ready(function(){wppaFbInit();});' );
		$need_fb_init = false;
	}

	$result = 	'<div style="clear:both"></div>' .
				$qr . $tw . $pi . $li . $fb .
				'<div style="clear:both"></div>';

	return $result;

}

// The upload box
function wppa_upload_box() {

	// Init
	$alb = wppa( 'start_album' );

	// Feature enabled?
	if ( ! wppa_switch( 'user_upload_on' ) ) {
		return;
	}

	// Must login
	if ( ! is_user_logged_in() ) return;

	// Are roles specified and do i have one?
	if ( ! wppa_check_user_upload_role() ) {
		return;
	}

	// Have i access?
	if ( $alb && ! wppa_is_enum( $alb ) ) {

		// Access to this album ?
		if ( ! wppa_have_access( $alb ) ) return;
	}

	// Do the dirty work
	$create = wppa_get_user_create_html( $alb, wppa_get_container_width( 'netto' ), 'uploadbox' );
	$upload = wppa_get_user_upload_html( $alb, wppa_get_container_width( 'netto' ), 'uploadbox' );

	if ( ! $create && ! $upload ) return; 	// Nothing to do

	// In widget no container
	if ( wppa_in_widget() ) {
		wppa_out( $create . $upload );
	}

	// Open container
	else {
		wppa_container( 'open' );

		// Open div
		wppa_out( '
		<div
			id="wppa-upload-box-' . wppa( 'mocc' ) . '"
			class="wppa-box wppa-upload"
			>' .
			$create . $upload . '
			<div style="clear:both;"></div>
		</div>' );

		// Close container
		wppa_container( 'close' );
	}
}

// Frontend delete album, for use in the album box
function wppa_user_destroy_html( $alb, $width, $where, $rsp ) {

	// Feature enabled ?
	if ( ! wppa_switch( 'user_destroy_on' ) ) {
		return;
	}

	// Must login ?
	if ( ! is_user_logged_in() ) {
		return;
	}

	// Album access ?
	if ( ! wppa_have_access( $alb ) ) {
		return;
	}

	// Been naughty ?
	if ( wppa_is_user_blacklisted() ) {
		return;
	}

	// Make the html
	wppa_out( '
		<div
			class="wppa-album-cover-link"
			style="clear:both;"
			>
			<a
				style="float:left; cursor:pointer"
				onclick="
					jQuery(this).html(\'' . __( 'Working...', 'wp-photo-album-plus' ) . '\');
					wppaAjaxDestroyAlbum(' . $alb . ',\'' . wp_create_nonce( 'wppa-nonce_' . $alb ) . '\');
					jQuery(this).html(\'' . __( 'Delete Album', 'wp-photo-album-plus' ) . '\');
					"
				>' .
				__( 'Delete Album', 'wp-photo-album-plus' ) . '
			</a>
		</div>' );
}

// Frontend create album, for use in the upload box, the widget or in the album and thumbnail box
function wppa_user_create_html( $alb, $width, $where = '', $mcr = false ) {

	wppa_out( wppa_get_user_create_html( $alb, $width, $where, $mcr ) );
}

function wppa_get_user_create_html( $alb, $width, $where = '', $mcr = false ) {

	// Logged out can never create album-
	if ( ! is_user_logged_in() ) {
		return '';
	}

	// Basic users are not allowed to create sub albums
	if ( wppa_user_is_basic() ) {
		return '';
	}

	// Test for max nesting level
	if ( wppa_get_nesting_level( $alb ) >= wppa_opt( 'user_create_max_level' ) ) {
		return '';
	}

	// Init
	$result = '';
	$mocc 	= wppa( 'mocc' );
	$occur 	= wppa( 'mocc' );
	if ( $alb < '0' ) {
		$alb = '0';
	}

	$parent = $alb;
	if ( ! wppa_is_int( $parent ) && wppa_is_enum( $parent ) ) {
		$parent = '0';
	}

	// Feature enabled ?
	if ( ! wppa_switch( 'user_create_on' ) ) {
		return '';
	}

	// If roles specified and i am not an admin, see if i have one
	if ( wppa_opt( 'user_create_roles' ) && ! wppa_user_is_admin() ) {

		// Allowed roles
		$allowed_roles = explode( ',', wppa_opt( 'user_create_roles' ) );

		// Current user roles
		$user = wp_get_current_user();
		if ( ! array_intersect( $allowed_roles, $user->roles ) ) {
		   return '';
		}
	}

	// Have access?
	if ( $parent && ! wppa_have_access( $parent ) ) {
		return '';
	}

	// Can create album?
	if ( $parent && ! wppa_can_create_album() ) {
		return '';
	}

	// Test for max children of parent
	if ( $parent > '0' ) {

		$max = wppa_get_album_item( $parent, 'max_children' );
		if ( $max == '-1' ) return ''; // None alowed
		if ( $max > '0' ) { // See if max reached ( 0 = unlimited )
			$tc = wppa_get_treecounts_a( $parent );
			$nchild = $tc['selfalbums'];
			if ( $nchild >= $max ) return '';
		}
	}

	// In a widget or multi column responsive?
	$small = ( wppa_in_widget() == 'upload' || $mcr );

	// Create the return url
	$returnurl = wppa_get_permalink();
	if ( $where == 'cover' ) {
		$returnurl .= 'wppa-album=' . $parent . '&amp;wppa-cover=0&amp;wppa-occur=' . $occur;
	}
	elseif ( $where == 'thumb' ) {
		$returnurl .= 'wppa-album=' . $parent . '&amp;wppa-cover=0&amp;wppa-occur=' . $occur;
	}
	if ( wppa( 'page' ) ) $returnurl .= '&amp;wppa-paged=' . wppa( 'page' );
	$returnurl = trim( $returnurl, '?' );
	$returnurl = wppa_trim_wppa_( $returnurl );
	$t = $mcr ? 'mcr-' : '';

	// The links
	$a = str_replace('.','-',$alb);
	$result .= '
		<div style="clear:both"></div>
		<a id="wppa-cr-' . $a . '-' . $mocc . '" class="wppa-create-' . $where . ' wppa-album-cover-link" onclick="
				jQuery( \'#wppa-create-'.$t.$a.'-'.$mocc.'\' ).css( \'display\',\'block\' );
				jQuery( \'#wppa-cr-'.$a.'-'.$mocc.'\' ).css( \'display\',\'none\' );
				jQuery( \'#wppa-up-'.$a.'-'.$mocc.'\' ).css( \'display\',\'none\' );
				jQuery( \'#wppa-ea-'.$a.'-'.$mocc.'\' ).css( \'display\',\'none\' );
				jQuery( \'#wppa-cats-' . $a . '-' . $mocc . '\' ).css( \'display\',\'none\' );
				jQuery( \'#_wppa-cr-'.$a.'-'.$mocc.'\' ).css( \'display\',\'block\' );
				window.dispatchEvent(new Event(\'resize\'))"
			style="float:left;cursor:pointer"> ' .
			( $alb ? __( 'Create sub album', 'wp-photo-album-plus' ) : __( 'Create album', 'wp-photo-album-plus' ) ) . '
		</a>
		<a id="_wppa-cr-' . $a . '-' . $mocc . '" class="wppa-create-' . $where . ' wppa-album-cover-link" onclick="
				jQuery( \'#wppa-create-'.$t.$a.'-'.$mocc.'\' ).css( \'display\',\'none\' );
				jQuery( \'#wppa-cr-'.$a.'-'.$mocc.'\' ).css( \'display\',\'block\' );
				jQuery( \'#wppa-up-'.$a.'-'.$mocc.'\' ).css( \'display\',\'block\' );
				jQuery( \'#wppa-ea-'.$a.'-'.$mocc.'\' ).css( \'display\',\'block\' );
				jQuery( \'#wppa-cats-' . $a . '-' . $mocc . '\' ).css( \'display\',\'block\' );
				jQuery( \'#_wppa-cr-'.$a.'-'.$mocc.'\' ).css( \'display\',\'none\' );
				window.dispatchEvent(new Event(\'resize\'))"
			style="float:right;cursor:pointer;display:none;padding-right:6px;">' .
			__( wppa_opt( 'close_text' ), 'wp-photo-album-plus' ) . '
		</a>';

	// The create form
	$result .= '
		<div
			id="wppa-create-'.$t.$a.'-'.$mocc.'"
			style="width:100%;text-align:center;display:none;"
			>
			<form
				id="wppa-creform-'.$a.'-'.$mocc.'"
				action="#"
				method="post"
				>' .
				wppa_nonce_field( 'wppa-album-check', 'wppa-nonce', false, $alb ) . '
				<input type="hidden" name="wppa-fe-create" value="yes" />';

				// Parent
				if ( ( $where == 'widget' || wppa( 'is_upload' ) ) && ! wppa_switch( 'default_parent_always' ) ) {

					$head = __( 'Parent album', 'wp-photo-album-plus' );
					$body = '';
					$result .= wppa_get_dlg_item( $head, $body, false );

					$result .= '
					<select
						id="wppa-create-parent-' . $mocc . '"
						name="wppa-album-parent"
						class="wppa-upload-album-' . $mocc . '"
						style="max-width: 100%;"
						>' .
						wppa_album_select_a( array ( 	'exclude' 			=> '',
														'selected' 			=> '',
														'disabled' 			=> '',
														'addpleaseselect' 	=> false,
														'addnone' 			=> wppa_can_create_top_album(),
														'addall' 			=> false,
														'addgeneric'		=> false,
														'addblank' 			=> false,
														'addselected'		=> false,
														'addseparate' 		=> wppa_can_create_top_album(),
														'addselbox'			=> false,
														'addowner' 			=> false,
														'disableancestors' 	=> false,
														'checkaccess' 		=> true,
														'checkowner' 		=> false,
														'checkupload' 		=> false,
														'addmultiple' 		=> false,
														'addnumbers' 		=> false,
														'path' 				=> true,
														'root' 				=> false,
														'content'			=> false,
														'sort'				=> true,
														'checkarray' 		=> false,
														'array' 			=> array(),
														'optionclass' 		=> '',
														'tagopen' 			=> '',
														'tagname' 			=> '',
														'tagid' 			=> '',
														'tagonchange' 		=> '',
														'multiple' 			=> false,
														'tagstyle' 			=> '',
														'checkcreate' 		=> true,
											) ) . '
					</select>
					<div style="clear:both"></div>';
				}
				else {
					$result .= '
					<input
						type="hidden"
						name="wppa-album-parent"
						value="' . $parent . '"
					/>';
				}

				// Name
				$result .= '
				<div
					class="wppa-box-text wppa-td"
					style="width:100%;clear:both;float:left;text-align:left;"
					>' .
					__( 'Enter album name.', 'wp-photo-album-plus' ) . '
				</div>
				<input
					type="text"
					class="wppa-box-text"
					style="padding:0; width:100%;"
					name="wppa-album-name"
					placeholder="' . esc_attr( 'New Album', 'wp-photo-album-plus' ) . '"
				/>';

				// Description
				$result .= '
				<div
					class="wppa-box-text wppa-td"
					style="width:100%;clear:both;float:left;text-align:left;"
					>' .
					__( 'Enter album description', 'wp-photo-album-plus' ) . '
				</div>
				<textarea
					class="wppa-user-textarea wppa-box-text"
					style="padding:0;height:120px; width:100%;"
					name="wppa-album-desc"
				>
				</textarea>';

				if ( wppa_switch( 'user_create_captcha' ) ) {
					$result .= '
					<div style="float:left; margin: 6px 0">
						<div style="float:left">' .
							wppa_make_captcha( wppa_get_randseed( 'session' ) ) . '
						</div>
						<input
							type="text"
							id="wppa-captcha-' . $mocc . '"
							name="wppa-captcha"
							style="margin-left: 6px; width:50px;"
						/>
					</div>';
				}

				$result .= '
				<input
					type="submit"
					class="wppa-user-submit"
					style="margin: 6px 0; float:right;"
					value="' . __( 'Create album', 'wp-photo-album-plus' ) . '"
				/>
			</form>
		</div>';

	return $result;
}

// Frontend upload html, for use in the upload box, the widget or in the album and thumbnail box
function wppa_user_upload_html( $alb, $width, $where = '', $mcr = false ) {

	wppa_out( wppa_get_user_upload_html( $alb, $width, $where, $mcr ) );
}

function wppa_get_user_upload_html( $xalb, $width, $where = '', $mcr = false ) {
global $wpdb;
global $wppa_supported_photo_extensions;
global $wppa_supported_video_extensions;
global $wppa_supported_audio_extensions;
static $seqno;
static $albums_granted;

	// Basic users are not allowed to upload
	if ( wppa_user_is_basic() ) {
		return '';
	}

	$albums_created = array();

	// Create granted albums only if not done yet in a previous occurance,
	// and an album id is given not being '0'
	if ( wppa_is_int( $xalb ) && $xalb > '0' ) {
		if ( ! in_array( $xalb, (array) $albums_granted, true ) ) {

			// This function will check if $xalb is a grant parent,
			// and make my sub album if it does not already exist.
			$ta = wppa_grant_albums( $xalb );
			if ( ! empty( $ta ) ) {
				$albums_created = array_merge( $albums_created, $ta );
			}

			// Remember we processed this possible grant parent
			$albums_granted[] = $xalb;
		}
	}

	// Check all albums in an enumeration,
	// like above
	elseif( wppa_is_enum( $xalb ) ) {
		$temp = explode( '.', wppa_expand_enum( $xalb ) );
		foreach( $temp as $t ) {
			if ( ! in_array( $t, (array) $albums_granted, true ) ) {

				$ta = wppa_grant_albums( $t );
				if ( ! empty( $ta ) ) {
					$albums_created = array_merge( $albums_created, $ta );
				}

				$albums_granted[] = $t;
			}
		}
	}

	// If albums created, add them to the list, so they appear immediately
	$alb = $xalb;
	if ( ! empty( $albums_created ) ) {
		foreach( $albums_created as $a ) {
			$alb .= '.' . $a;
		}
	}

	// Init
	$mocc 	= wppa( 'mocc');
	$occur 	= wppa( 'mocc' );
	$yalb 	= str_replace( '.', '', $xalb );

	// Open wrapper
	$result = '<div style="clear:both"></div>';//<div id="fe-upl-wrap-' . $mocc . '" style="background-color:#FFC">';

	// Using seqno to distinguish from different places within one occurrence because
	// the album no is not known when there is a selection box.
	if ( $seqno ) $seqno++;
	else $seqno = '1';

	// Feature enabled?
	if ( ! wppa_switch( 'user_upload_on' ) ) {
		return '';
	}

	// Login required
	if ( ! is_user_logged_in() ) {
		return '';
	}

	// Are roles specified and do i have one?
	if ( ! wppa_check_user_upload_role() ) {
		return;
	}

	// Basically there are 3 possibilities for supplied album id(s)
	// 1. A single album
	// 2. '' or '0', meaning 'any'
	// 3. An album enumerations
	//
	// Now we are going to test if the visitor has access
	$albarr = array(); // Init

	// Case 1. A single album. I should have access to this album ( $alb > 0 ).
	if ( wppa_is_int( $alb ) && $alb > '0' ) {
		if ( ! wppa_have_access( $alb ) ) {
			return '';
		}
		$albarr = array( $alb );
	}

	// Case 2. No alb given, treat as all albums. Make array
	elseif ( ! $alb ) {
		$alb = trim( wppa_alb_to_enum_children( '0' ) . '.' . wppa_alb_to_enum_children( '-1' ), '.' );
		$albarr = explode( '.', $alb );
	}

	// Case 3. An enumeration. Make it an array.
	if ( wppa_is_enum( $alb ) ) {
		$albarr = explode( '.', wppa_expand_enum( $alb ) );
	}

	// Test for all albums in the array, and remove the albums that he has no access to.
	// In this event, if a single album remains, there will not be a selectionbox, but its treated as if a single album was supplied.
	foreach( array_keys( $albarr ) as $key ) {
		if ( ! wppa_have_access( $albarr[$key] ) ) {
			unset( $albarr[$key] );
		}
	}
	if ( empty( $albarr ) ) {
		$alb = '';
	}
	if ( count( $albarr ) == 1 ) {
		$alb = reset( $albarr );
	}
	else {
		$alb = $albarr;
	}

	// If no more albums left, no access, quit this proc.
	if ( ! $alb ) {
		return '';
	}

	// The result is: $alb is either an album id, or an array of album ids. Always with upload access.

	// Find max files for the user
	if ( wppa_is_int( $xalb ) ) {
		$a = $xalb;
	}
	else {
		$a = '';
	}
	$allow_me = wppa_allow_user_uploads( $a );
	if ( ! $allow_me ) {
		$result .=
					'<h6 style="color:red">' .
						__( 'Max uploads reached', 'wp-photo-album-plus' ) .
						wppa_time_to_wait_html( '0', true ) .
					'</h6>';
		return $result;
	}

	// Find max files for the album
	if ( wppa_is_int( $alb ) ) {
		$allow_alb = wppa_allow_uploads( $alb );
		if ( ! $allow_alb ) {
			$result .=
						'<h6 style="color:red">' .
							__( 'Max uploads reached', 'wp-photo-album-plus' ) .
							wppa_time_to_wait_html( $alb ) .
						'</h6>';
			return $result;
		}
	}
	else {
		$allow_alb = '-1';
	}

	if ( wppa_is_user_blacklisted() ) return '';

	// Find max files for the system
	$allow_sys = ini_get( 'max_file_uploads' );

	// THE max
	if ( $allow_me == '-1' ) $allow_me = $allow_sys;
	if ( $allow_alb == '-1' ) $allow_alb = $allow_sys;
	$max = min( $allow_me, $allow_alb, $allow_sys );

	// In a widget or multi column responsive?
	$small = ( wppa_in_widget() || $mcr );
	$big = ! $small;

	// Create the return url
	$returnurl = wppa_get_ajaxlink( 'plain' ) . '&amp;wppa-action=do-fe-upload';

	// Make the HTML
	$t = $mcr ? 'mcr-' : '';
	$a = str_replace( '.', '-', $yalb );

	$result .= '
		<a id="wppa-up-' . $a . '-' . $mocc . '" class="wppa-upload-'.$where.' wppa-album-cover-link" onclick="
				jQuery( \'#wppa-file-'.$t.$a.'-'.$mocc.'\' ).css( \'display\',\'block\' );
				jQuery( \'#wppa-up-'.$a.'-'.$mocc.'\' ).css( \'display\',\'none\' );
				jQuery( \'#wppa-cr-'.$a.'-'.$mocc.'\' ).css( \'display\',\'none\' );
				jQuery( \'#wppa-ea-'.$a.'-'.$mocc.'\' ).css( \'display\',\'none\' );
				jQuery( \'#wppa-cats-' . $a . '-' . $mocc . '\' ).css( \'display\',\'none\' );
				jQuery( \'#_wppa-up-'.$a.'-'.$mocc.'\' ).css( \'display\',\'block\' );
				window.dispatchEvent(new Event(\'resize\'));
				" style="float:left; cursor:pointer"
			>' .
			__( 'Upload photo', 'wp-photo-album-plus' ) . '
		</a>
		<a id="_wppa-up-' . $a . '-' . $mocc . '" class="wppa-upload-'.$where.' wppa-album-cover-link" onclick="
				jQuery( \'#wppa-file-'.$t.$a.'-'.$mocc.'\' ).css( \'display\',\'none\' );
				jQuery( \'#wppa-cr-'.$a.'-'.$mocc.'\' ).css( \'display\',\'block\' );
				jQuery( \'#wppa-up-'.$a.'-'.$mocc.'\' ).css( \'display\',\'block\' );
				jQuery( \'#wppa-ea-'.$a.'-'.$mocc.'\' ).css( \'display\',\'block\' );
				jQuery( \'#wppa-cats-' . $a . '-' . $mocc . '\' ).css( \'display\',\'block\' );
				jQuery( \'#_wppa-up-'.$a.'-'.$mocc.'\' ).css( \'display\',\'none\' );
				window.dispatchEvent(new Event(\'resize\'));
				" style="float:right; cursor:pointer;display:none;padding-right:6px">' .
			__( wppa_opt( 'close_text' ), 'wp-photo-album-plus' ) . '
		</a>
		<div id="wppa-file-' . $t . $a . '-' . $mocc . '" style="width:100%;text-align:center;display:none; clear:both" >
			<form id="wppa-uplform-' . $yalb . '-' . $mocc . '" action="' . $returnurl . '" method="post" enctype="multipart/form-data" >' .
				wppa_nonce_field( 'wppa-check', 'wppa-nonce', false, $yalb );

	// Single Album given
	if ( wppa_is_int( $alb ) ) {
		$result .= '
			<input type="hidden" id="wppa-upload-album-' . $mocc . '-' . $seqno . '" name="wppa-upload-album" value="' . $alb . '" />';

		$head = '';
		$body = '';
	}

	// Array given
	else {

		$head = __( 'Upload to album', 'wp-photo-album-plus' );
		$body = '';

		if ( ! is_array( $alb ) ) {
			$alb = explode( '.', wppa_expand_enum( $alb ) );
		}

		$alb = wppa_strip_void_albums( $alb );

		// Can an selection box be displayed?
		if ( ! wppa_opt( 'fe_upload_max_albums' ) ||												// No limit on number of albums
				wppa_opt( 'fe_upload_max_albums' ) > wppa_get_uploadable_album_count( $alb ) ) {	// Below max
			$body .= '
				<select
					id="wppa-upload-album-' . $mocc . '-' . $seqno . '"
					name="wppa-upload-album"
					class="wppa-upload-album-' . $mocc . '"
					style="max-width: 100%;"
					onchange="jQuery( \'#wppa-sel-'.$yalb.'-'.$mocc.'\' ).trigger( \'onchange\' )"
					>' .
					wppa_album_select_a( array ( 	'addpleaseselect' 	=> true,
													'checkowner' 		=> true,
													'checkupload' 		=> true,
													'path' 				=> true,
													'checkarray' 		=> count( $alb ) > 1,
													'array' 			=> $alb,
													'sort' 				=> true,
										) ) . '
				</select>
				<div style="clear:both"></div>';
		}

		// No, there are too many albums
		else {
			$body .= '
				<input
					id="wppa-upload-album-' . $mocc . '-' . $seqno . '"
					type="number"
					placeholder="' . esc_attr( __( 'Enter album id', 'wp-photo-album-plus' ) ) . '"
					name="wppa-upload-album"
					style="max-width: 100%;"
					onchange="jQuery( \'#wppa-sel-'.$yalb.'-'.$mocc.'\' ).trigger( \'onchange\' )"
				/>';
		}
	}

	// If big, init table
	if ( $big ) {
		$result .= '<table class="wppa-upload-table" style="max-width:100%">';
	}

	// Album select
	$result .= wppa_get_dlg_item( $head, $body, $big );

	$one_only 	= wppa_switch( 'upload_one_only' ) && ! wppa_user_is_admin();
	$multiple 	= ! $one_only;
	$on_camera 	= wppa_switch( 'camera_connect' );
	$may_video 	= wppa_switch( 'user_upload_video_on' );
	$may_audio 	= wppa_switch( 'user_upload_audio_on' );

	// Restrictions for logged out
	if ( ! is_user_logged_in() ) {
		$one_only = false;
		$multiple = true;
		$may_video = false;
		$may_audio = false;
	}

	if ( $one_only ) $max = '1';

	$accept 	= '.jpg,.gif,.png,.webp';
	if ( $may_video ) {
		$accept .= ',.' . implode( ',.', $wppa_supported_video_extensions );
	}
	if ( $may_audio ) {
		$accept .= ',.' . implode( ',.', $wppa_supported_audio_extensions );
	}
	if ( wppa_switch( 'enable_pdf' ) ) {
		$accept .= ',.pdf';
	}

	if ( $one_only ) {
		if ( $on_camera ) {
			$head = esc_attr( __( 'Select File or Camera', 'wp-photo-album-plus' ) );
		}
		else {
			$head = esc_attr( __( 'Select File', 'wp-photo-album-plus' ) );
		}
	}
	else {
		if ( $on_camera ) {
			$head = esc_attr( __( 'Select File(s) or Camera', 'wp-photo-album-plus' ) );
		}
		else {
			$head = esc_attr( __( 'Select File(s)', 'wp-photo-album-plus' ) );
		}
	}

	$value = __( 'Browse...', 'wp-photo-album-plus' );

	// Save the button text
	$body = '';
	wppa_js( 'wppaUploadButtonText="' . esc_js( $value ) . '";' );

	// The (hidden) functional button
	$body .= '
	<input
		type="file"
		accept="' . $accept . '"' .
		( $multiple ? ' multiple' : '' ) . '
		style="display:none;"
		id="wppa-user-upload-' . $yalb . '-' . $mocc . '"
		name="wppa-user-upload-' . $yalb . '-' . $mocc . '[]"
		onchange="
			jQuery( \'#wppa-user-submit-' . $yalb . '-' . $mocc.'\' ).css( \'display\', \'block\' );
			jQuery( window ).trigger(\'resize\');
			wppaDisplaySelectedFiles(\'wppa-user-upload-' . $yalb . '-' . $mocc . '\');
			"
	/>';

	// The displayed button
	$body .= '
	<input
		type="button"
		style="max-width:100%;width:auto;margin-top:8px;margin-bottom:8px;padding-left:6px;padding-right:6px;"
		id="wppa-user-upload-' . $yalb . '-' . $mocc . '-display"
		class="wppa-upload-button"
		value="' . $value . '"
		onclick="
			wppaSetMaxWidthToParentWidth(this);
			jQuery(\'#wppa-user-upload-' . $yalb . '-' . $mocc . '\').removeAttr(\'capture\');
			jQuery(\'#wppa-user-upload-' . $yalb . '-' . $mocc . '\').click();
			"
	/>';

	// The camera button for iphone and ipad, if Advanced settings -> Users -> I -> item 27(Camera connect) is ticked.
	if ( wppa_is_iphoneoripad() && $on_camera ) {
		$body .= '
		<input
			type="button"
			style="
				width:32px;margin:8px 12px;padding:0 6px;
				background-image:url(\'' . wppa_get_imgdir() . 'camera16.png\');
				background-repeat:no-repeat;
				background-position:center;
				"
			id="wppa-user-upload-' . $yalb . '-' . $mocc . '-idisplay"
			class="wppa-upload-button"
			value="&nbsp;"
			onclick="
				jQuery(\'#wppa-user-upload-' . $yalb . '-' . $mocc . '\').attr(\'capture\',\'environment\');
				jQuery(\'#wppa-user-upload-' . $yalb . '-' . $mocc . '\').click();
				"
		/>';
	}

	// Explanation
	$body .= '
	<div style="font-size:10px">' .
		sprintf( _n( 'You may upload %d photo', 'You may upload up to %d photos at once if your browser supports HTML-5 multiple file upload',
					$max, 'wp-photo-album-plus' ), $max ) . '
	</div>';

	if ( wppa_opt( 'upload_frontend_minsize' ) ) {
		$minsize = wppa_opt( 'upload_frontend_minsize' );
		$body .=
			'<div style="font-size:10px">' .
				sprintf( __( 'Min photo size: %d pixels', 'wp-photo-album-plus' ), $minsize ) .
			'</div>';
	}
	if ( wppa_opt( 'upload_frontend_maxsize' ) ) {
		$maxsize = wppa_opt( 'upload_frontend_maxsize' );
		$body .=
			'<div style="font-size:10px">' .
				sprintf( __( 'Max photo size: %d pixels', 'wp-photo-album-plus' ), $maxsize ) .
			'</div>';
	}

	$supp = $wppa_supported_photo_extensions;
	$body .=
	'<div style="font-size:10px">' .
		__( 'You may upload files of type', 'wp-photo-album-plus' ) . ': .' .
		implode( ', .', $wppa_supported_photo_extensions );
		if ( $may_video ) {
			$body .= ', ' . implode( ', .', $wppa_supported_video_extensions );
		}
		if ( $may_audio ) {
			$body .= ', ' . implode( ', .', $wppa_supported_audio_extensions );
		}
		if ( wppa_switch( 'enable_pdf' ) && wppa_can_magick() ) {
			$body .= ', .pdf';
		}
	$body .=
	'</div>';

	// Copyright notice
	if ( wppa_switch( 'copyright_on' ) ) {
		$body .=
			'<div style="width:100%;clear:both">' .
				__( wppa_opt( 'copyright_notice' ) ) .
			'</div>';
	}

	$result .= wppa_get_dlg_item( $head, $body, $big );

	// Watermark
	if ( wppa_switch( 'watermark_on' ) && wppa_switch( 'watermark_user' ) ) {

		$head = __( 'Watermark', 'wp-photo-album-plus' );
		$body = '
			<table
				class="wppa-watermark wppa-box-text"
				style="margin:0; border:0;"
				>
				<tbody>
					<tr style="border:0 none;vertical-align:top" >
						<td
							class="wppa-box-text wppa-td"
							>' .
							__( 'Apply watermark file:', 'wp-photo-album-plus' ) . '
						</td>
					</tr>
					<tr>
						<td
							class="wppa-box-text wppa-td"
							style="width:' . $width . ';"
							>
							<select
								style="margin:0; padding:0; text-align:left; width:auto; "
								name="wppa-watermark-file"
								id="wppa-watermark-file"
								>' .
								wppa_watermark_file_select( 'user' ) . '
							</select>
						</td>
					</tr>
					<tr style="border:0 none;vertical-align:top" >
						<td
							class="wppa-box-text wppa-td"
							style="width:' . $width . ';"
							>' .
							__( 'Position:', 'wp-photo-album-plus' ) . '
						</td>
					</tr>
					<tr>
						<td
							class="wppa-box-text wppa-td"
							style="width: ' . $width . ';"
							>
							<select
								style="margin:0; padding:0; text-align:left; width:auto; "
								name="wppa-watermark-pos"
								id="wppa-watermark-pos"
								>' .
								wppa_watermark_pos_select( 'user' ) . '
							</select>
						</td>
					</tr>
				</tbody>
			</table>';

		$result .= wppa_get_dlg_item( $head, $body, $big );
	}

	// Name
	$head = __( 'Image name', 'wp-photo-album-plus' );
	if ( wppa_switch( 'name_user_mandatory' ) ) {
		$head .= '<sup style="color:red">*</sup>';
	}
	$body = '';
	if ( wppa_switch( 'name_user' ) ) {
		if ( wppa_switch( 'name_user_mandatory' ) ) {
			$expl = '';
		}
		else {
			switch ( wppa_opt( 'newphoto_name_method' ) ) {
				case 'none':
					$expl = '';
					break;
				case '2#005':
					$expl =
					__( 'If you leave this blank, iptc tag 005 (Graphic name) will be used as photoname if available, else the original filename will be used as photo name.',
						'wp-photo-album-plus' );
					break;
				case '2#120':
					$expl =
					__( 'If you leave this blank, iptc tag 120 (Caption) will be used as photoname if available, else the original filename will be used as photo name.',
					'wp-photo-album-plus' );
					break;
				case 'Photo w#id':
					$expl =
					__( 'If you leave this blank, "Photo photoid" will be used as photo name.',
					'wp-photo-album-plus' );
					break;

				default:
					$expl =
					__( 'If you leave this blank, the original filename will be used as photo name.',
					'wp-photo-album-plus' );
			}
		}
		$body .= '
			<input
				id="wppa-name-user-' . $mocc . '-' . $seqno . '"
				type="text"
				class="wppa-box-text"
				style="border:1px solid ' . wppa_opt( 'bcolor' ) . ';clear:left; padding:0; width:100%;"
				name="wppa-user-name"
			/>
			<div style="clear:left;font-size:10px">' .
				$expl . '
			</div>';
	}
	$result .= wppa_get_dlg_item( $head, $body, $big );

	// Description user fillable ?
	if ( wppa_switch( 'desc_user' ) ) {

		$head = __( 'Image description', 'wp-photo-album-plus' );
		if ( wppa_switch( 'desc_user_mandatory' ) ) {
			$head .= '<sup style="color:red">*</sup>';
		}
		$desc = wppa_switch( 'apply_newphoto_desc_user' ) ? stripslashes( wppa_opt( 'newphoto_description' ) ) : '';

		// Do NOT show newphoto des if it contains html
		if ( $desc != strip_tags( $desc ) ) {
			$desc = '';
		}

		$body = '
			<textarea
				id="wppa-desc-user-' . $mocc . '-' . $seqno . '"
				class="wppa-user-textarea wppa-box-text"
				style="border:1px solid '.wppa_opt( 'bcolor' ).';clear:left; padding:0; height:120px; width:100%;"
				name="wppa-user-desc"
				>' .
				$desc . '
			</textarea>';

		$result .= wppa_get_dlg_item( $head, $body, $big );
	}

	// Predefined desc ?
	elseif ( wppa_switch( 'apply_newphoto_desc_user' ) ) {

		$result .= wppa_get_dlg_item( '', '
			<input
				id="wppa-desc-user-' . $mocc . '-' . $seqno . '"
				type="hidden"
				value="' . esc_attr( wppa_opt( 'newphoto_description' ) ) . '"
				name="wppa-user-desc"
			/>', $big );
	}

	// Custom fields
	if ( wppa_switch( 'fe_custom_fields' ) ) {
		for ( $i = '0'; $i < '10' ; $i++ ) {
			if ( wppa_opt( 'custom_caption_' . $i ) ) {

				$head = __( wppa_opt( 'custom_caption_' . $i ) ) .
						( wppa_switch( 'custom_visible_' . $i ) ? '' : '&nbsp;<small><i>(&nbsp;'.__( 'hidden', 'wp-photo-album-plus' ).'&nbsp;)</i></small>' );
				$body = '
				<input
					type="text"
					class="wppa-box-text"
					style="border:1px solid '.wppa_opt( 'bcolor' ).';clear:left; padding:0; width:100%;"
					name="wppa-user-custom-' . $i . '"
				/>';

				$result .= wppa_get_dlg_item( $head, $body, $big );
			}
		}
	}

	// Tags
	if ( wppa_switch( 'fe_upload_tags' ) ) {

		// Prepare onclick action
		$onc = 'wppaPrevTags(\'wppa-sel-'.$yalb.'-'.$mocc.'\', \'wppa-inp-'.$yalb.'-'.$mocc.'\', \'wppa-upload-album-'.$mocc.'-'.$seqno.'\', \'wppa-prev-'.$yalb.'-'.$mocc.'\')';

		// Selection boxes 1..3
		for ( $i = '1'; $i < '4'; $i++ ) {
			if ( wppa_switch( 'up_tagselbox_on_'.$i ) ) {

				$head = __( wppa_opt( 'up_tagselbox_title_'.$i ) );
				$head = trim( $head, ': ');
				$body = '
				<select
					id="wppa-sel-' . $yalb . '-' . $mocc . '-' . $i . '"
					name="wppa-user-tags-' . $i . '[]"' .
					( wppa_switch( 'up_tagselbox_multi_'.$i ) ? ' multiple' : '' ) . '
					onchange="' . $onc . '"
					>';

				if ( wppa_opt( 'up_tagselbox_content_'.$i ) ) {	// List of tags supplied
					$tags = explode( ',', trim( wppa_opt( 'up_tagselbox_content_'.$i ), ',' ) );
					$body .= '<option value="" >&nbsp;</option>';
					if ( is_array( $tags ) ) foreach ( $tags as $tag ) {
						$body .= '<option class="wppa-sel-'.$yalb.'-'.$mocc.'" value="'.urlencode($tag).'">'.$tag.'</option>';
					}
				}
				else {											// All existing tags
					$tags = wppa_get_taglist();
					$body .= '<option value="" >&nbsp;</option>';
					if ( is_array( $tags ) ) foreach ( $tags as $tag ) {
						$body .= '<option class="wppa-sel-'.$yalb.'-'.$mocc.'" value="'.urlencode($tag['tag']).'">'.$tag['tag'].'</option>';
					}
				}
				$body .= '
				</select>
				<div style="clear:both"></div>';

				$result .= wppa_get_dlg_item( $head, $body, $big );
			}
		}

		// New tags
		if ( wppa_switch( 'up_tag_input_on' ) ) {

			$head = __( wppa_opt( 'up_tag_input_title' ) );
			$head = trim( $head, ': ');
			$body = '
			<input
				id="wppa-inp-' . $yalb . '-' . $mocc . '"
				type="text"
				class="wppa-box-text"
				style="padding:0; width:100%;"
				name="wppa-new-tags"
				onchange="' . $onc . '"
				value="' . trim( wppa_opt( 'up_tagbox_new' ), ',' ) . '"
			/>';

			$result .= wppa_get_dlg_item( $head, $body, $big );
		}

		// Preview area
		if ( wppa_switch( 'up_tag_preview' ) ) {
			$head = __( 'Preview tags', 'wp-photo-album-plus' );
			$body = '<span id="wppa-prev-'.$yalb.'-'.$mocc.'">' .

					( $yalb ? htmlspecialchars( trim( wppa_sanitize_tags( wppa_get_album_item( $yalb, 'default_tags' ), false, true ), ',' ) ) : '' ) .

					'</span>' .

					( $yalb ? '' : wppa_js( 'jQuery(document).ready(function() {'.$onc.'});' ) );

			$result .= wppa_get_dlg_item( $head, $body, $big );
		}
	}

	if ( current_user_can( 'wppa_moderate' ) ) {
		$default = wppa_opt( 'status_new' );
		if ( wppa_switch( 'fe_upload_private' ) ) {
			$default = 'private';
		}
		$head = __( 'Status', 'wp-photo-album-plus' ) . ':';
		$body = '
		<select
			id="wppa-user-status-' . $mocc . '"
			name="wppa-user-status"
			>
			<option value="publish"' . ( 'publish' == $default ? ' selected' : '' ) . '>' . __( 'Publish', 'wp-photo-album-plus' ) . '</option>
			<option value="pending"' . ( 'pending' == $default ? ' selected' : '' ) . '>' . __( 'Pending', 'wp-photo-album-plus' ) . '</option>
			<option value="featured"' . ( 'featured' == $default ? ' selected' : '' ) . '>' . __( 'Featured', 'wp-photo-album-plus' ) . '</option>
			<option value="private"' . ( 'private' == $default ? ' selected' : '' ) . '>' . __( 'Private', 'wp-photo-album-plus' ) . '</option>
		</select>';

		$result .= wppa_get_dlg_item( $head, $body, $big );
	}

/* The Blogit section */

	if ( ( $where == 'widget' || $where == 'uploadbox' ) && current_user_can( 'edit_posts' ) && wppa_opt( 'blog_it' ) != '-none-' ) {

		// User can choose to blog it
		if ( wppa_opt( 'blog_it' ) == 'optional' ) {

			$head =
			'<input' .
				' type="button"' .
				' value="' . esc_attr( __( 'Blog it?', 'wp-photo-album-plus' ) ) . '"' .
				' onclick="jQuery(\'#wppa-blogit-'.$yalb.'-'.$mocc.'\').trigger(\'click\')"' .
			' />';

			$head .=
			' <input' .
				' type="checkbox"' .
				' id="wppa-blogit-'.$yalb.'-'.$mocc.'"' .
				' name="wppa-blogit"' .
				' style="display:none;"' .
				' onchange="if ( jQuery(this).prop(\'checked\') ) { ' .
								'jQuery(\'#blog-div-'.$yalb.'-'.$mocc.'\').css(\'display\',\'block\'); ' .
								'jQuery(\'#wppa-user-submit-' . $yalb . '-' . $mocc . '\').prop(\'value\', \'' . esc_js(__( 'Upload and blog', 'wp-photo-album-plus' )) . '\'); ' .
							'} ' .
							'else { ' .
								'jQuery(\'#blog-div-'.$yalb.'-'.$mocc.'\').css(\'display\',\'none\'); ' .
								'jQuery(\'#wppa-user-submit-' . $yalb . '-' . $mocc . '\').prop(\'value\', \'' . esc_js(__( 'Upload photo', 'wp-photo-album-plus' )) . '\'); ' .
							'} "' .
			' />' ;
		}

		// Always blog
		else {

			$head =
			'<input' .
				' type="checkbox"' .
				' id="wppa-blogit-'.$yalb.'-'.$mocc.'"' .
				' name="wppa-blogit"' .
				' style="display:none;"' .
				' checked="checked"' .
			' />';
		}

		$body =
		'<div' .
			' id="blog-div-'.$yalb.'-'.$mocc.'"' .
			( wppa_opt( 'blog_it' ) == 'optional' ? ' style="display:none;"' : '' ) .
			' >' .


			'<h6>' .
				__( 'Post title', 'wp-photo-album-plus' ) .
			'</h6>' .
			'<input' .
				' id="wppa-blogit-title-'.$yalb.'-'.$mocc.'"' .
				' type="text"' .
				' class="wppa-box-text "' .
				' style="padding:0; width:100%;"' .
				' name="wppa-post-title"' .
			' />' .
			'<h6>' .
				__( 'Text BEFORE the image', 'wp-photo-album-plus' ) .
			'</h6>' .
			'<textarea' .
				' id="wppa-blogit-pretext-'.$yalb.'-'.$mocc.'"' .
				' name="wppa-blogit-pretext"' .
				' class="wppa-user-textarea wppa-box-text"' .
				' style="border:1px solid '.wppa_opt( 'bcolor' ).';clear:left; padding:0; height:120px; width:100%;"' .
				' >' .
			'</textarea>' .
			'<h6>' .
				__( 'Text AFTER the image', 'wp-photo-album-plus' ) .
			'</h6>' .
			'<textarea' .
				' id="wppa-blogit-posttext-'.$yalb.'-'.$mocc.'"' .
				' name="wppa-blogit-posttext"' .
				' class="wppa-user-textarea wppa-box-text"' .
				' style="border:1px solid '.wppa_opt( 'bcolor' ).';clear:left; padding:0; height:120px; width:100%;"' .
				'>' .
			'</textarea>' .
		'</div>';

		$result .= wppa_get_dlg_item( $head, $body, $big );
	}

/* start submit section */

	// Onclick submit verify required data is present
	$vfy_album 	= 'if (jquery( \'#wppa-upload-album-' . $mocc . '-' . $seqno . '\' ).value == 0 ){alert( \''.esc_js( __( 'Please select an album and try again', 'wp-photo-album-plus' ) ).'\' );return false;}';
	$vfy_name 	= 'if (jQuery( \'#wppa-name-user-' . $mocc . '-' . $seqno . '\' ).val() == \'\' ){alert( \''.esc_js(__( 'Please enter the name of the photo and try again', 'wp-photo-album-plus' )).'\');return false;}';
	$vfy_desc 	= 'if (jQuery( \'#wppa-desc-user-' . $mocc . '-' . $seqno . '\' ).val() == \'\' ){alert( \''.esc_js(__( 'Please enter the description of the photo and try again', 'wp-photo-album-plus' )).'\');return false;}';
	$vfy_postit = 'if (jQuery( \'#wppa-blogit-'. $yalb . '-' . $mocc . '\' ).prop( \'checked\' ) && jQuery( \'#wppa-blogit-title-' . $yalb . '-' . $mocc . '\' ).val() == \'\' ){alert( \''.esc_js(__( 'Please enter the title of the blogpost and try again', 'wp-photo-album-plus' )).'\');return false;}';
	$go 		= 'jQuery(this).css(\'display\', \'none\');';

	$onclick 	= ' onclick="';
	if ( ! $alb ) {
		$onclick .= $vfy_album;
	}
	if ( wppa_switch( 'name_user_mandatory' ) ) $onclick .= $vfy_name;
	if ( wppa_switch( 'desc_user_mandatory' ) ) $onclick .= $vfy_desc;
	if ( wppa_opt( 'blog_it' ) != '-none-' ) $onclick .= $vfy_postit;

	$onclick .= $go . '"';

	// The submit button
	$value = wppa_opt( 'blog_it' ) == 'always' ? esc_attr( __( 'Upload and blog', 'wp-photo-album-plus' ) ) : esc_attr( __( 'Upload photo', 'wp-photo-album-plus' ) );

	$head = '';
	$body =
		'<div style="height:6px;clear:both"></div>' .
		'<input' .
			' type="submit"' .
			' id="wppa-user-submit-' . $yalb . '-' . $mocc . '"' .
			$onclick .
			' style="display:none; margin: 6px 0; float:right;"' .
			' class="wppa-user-submit"' .
			' name="wppa-user-submit-'.$yalb.'-'.$mocc.'" value="' . $value . '"' .
		' />' .
		'<div style="height:6px;clear:both;"></div>
		<div' .
			' id="progress-'.$yalb.'-'.$mocc.'"' .
			' class="wppa-progress "' .
			' style="width:100%;border-color:'.wppa_opt( 'bcolor' ).'"' .
			' >' .
			'<div id="bar-'.$yalb.'-'.$mocc.'" class="wppa-bar" ></div>' .
			'<div id="percent-'.$yalb.'-'.$mocc.'" class="wppa-percent">0%</div>' .
		'</div>' .
		'<div id="message-'.$yalb.'-'.$mocc.'" class="wppa-message" ></div>';

	$result .= wppa_get_dlg_item( $head, $body, $big );


/* End submit section */

	// End table on wide dieplays
	if ( $big ) {
		$result .= '</table>';
	}

	// Done
	$result .= '</form></div>';



	// If ajax upload and from cover or thumbnail area, go display the thumbnails after upload
	if ( $where == 'cover' || $where == 'thumb' ) {

		if ( is_array( $alb ) ) {
			foreach( array_keys( $alb ) as $key ) {
				$alb[$key] = wppa_encrypt_album( $alb[$key] );
			}
		}
		else {
			$alb = wppa_encrypt_album( $alb );
		}

		$url_after_ajax_upload = wppa_get_permalink() . 'wppa-occur=' . wppa( 'mocc' ) . '&wppa-cover=0&wppa-album=' . ( is_array( $alb ) ? implode( '.', $alb ) : $alb );
		$ajax_url_after_upload = str_replace( '&amp;', '&', wppa_get_ajaxlink() ) . 'wppa-occur=' . wppa( 'mocc' ) . '&wppa-cover=0&wppa-album=' . ( is_array( $alb ) ? implode( '.', $alb ) : $alb );
		$on_complete = 'wppaDoAjaxRender( ' . $occur . ', \'' . $ajax_url_after_upload . '\', \'' . $url_after_ajax_upload . '\' )';
	}
	else {
		$url_after_ajax_upload = '';
		$ajax_url_after_upload = '';
		$on_complete = 'document.location.href=\'' . home_url() . '\'';
	}

	// Ajax upload script
	wppa_js( 'jQuery(document).ready(function() {jQuery("#wppa-uplform-'.$yalb.'-'.$mocc.'").ajaxForm(wppaGetUploadOptions( "'.$yalb.'", '.$mocc.', "'.$where.'", "'.$on_complete.'" ));});' );

	return $result;
}


// Dialog item for fe upload dialog. Makes difference betwen slall layut (widget) and 'box'(shortcode) layout.
function wppa_get_dlg_item( $head, $body, $big ) {

	if ( ! $head && ! $body ) {
		return '';
	}

	if ( $big ) {
		$result = 	'<tr class="wppa-upload-tr" >
						<th class="wppa-upload-th" >' .
							$head .
						'</th>
						<td class="wppa-upload-td" >' .
							$body .
						'</td>
					</tr>';
	}
	else {
		$result = 	'<h6>' . $head . '</h6>' .
					'<div>' . $body . '</div>';
	}

	return $result;
}

// Frontend edit album info
function wppa_user_albumedit_html( $alb, $width, $where = '', $mcr = false ) {

	$album = wppa_cache_album( $alb );

	if ( ! wppa_switch( 'user_album_edit_on' ) ) return; 	// Feature not enabled
	if ( ! $alb ) return;									// No album given
	if ( ! wppa_have_access( $alb ) ) return;				// No rights
	if ( ! is_user_logged_in() ) return;					// Must login
	if ( $album['owner'] == '--- public ---' && ! current_user_can( 'wppa_admin' ) ) return;	// Public albums are not publicly editable

	$t = $mcr ? 'mcr-' : '';

	// Create the return url
	$returnurl = wppa_get_permalink();
	if ( $where == 'cover' ) {
		$returnurl .= 'wppa-album=' . $alb . '&amp;wppa-cover=1&amp;wppa-occur=' . wppa( 'mocc' );
	}
	elseif ( $where == 'thumb' ) {
		$returnurl .= 'wppa-album=' . $alb . '&amp;wppa-cover=0&amp;wppa-occur=' . wppa( 'mocc' );
	}
//	elseif ( $where == 'widget' || $where == 'uploadbox' ) {
//	}
	if ( wppa( 'page' ) ) $returnurl .= '&amp;wppa-paged=' . wppa( 'page' );
	$returnurl = trim( $returnurl, '?' );

	$returnurl = wppa_encrypt_url( $returnurl );
	$a = str_replace( '.', '-', $alb );

	$result = '
	<div style="clear:both;"></div>
	<a id="wppa-ea-'.$a.'-'.wppa( 'mocc' ).'" class="wppa-aedit-'.$where.' wppa-album-'.$where.'-link" onclick="'.
									'jQuery( \'#wppa-fe-div-'.$a.'-'.wppa( 'mocc' ).'\' ).css( \'display\',\'block\' );'.		// Open the Edit form
									'jQuery( \'#wppa-ea-'.$a.'-'.wppa( 'mocc' ).'\' ).css( \'display\',\'none\' );'.			// Hide the Edit link
									'jQuery( \'#wppa-cr-'.$a.'-'.wppa( 'mocc' ).'\' ).css( \'display\',\'none\' );'.			// Hide the Create libk
									'jQuery( \'#wppa-up-'.$a.'-'.wppa( 'mocc' ).'\' ).css( \'display\',\'none\' );'.			// Hide the upload link
									'jQuery( \'#wppa-cats-' . $a . '-' . wppa( 'mocc' ) . '\' ).css( \'display\',\'none\' );'.	// Hide catogory
									'jQuery( \'#_wppa-ea-'.$a.'-'.wppa( 'mocc' ).'\' ).css( \'display\',\'block\' );'. 		// Show backlink
									'_wppaDoAutocol( ' . wppa( 'mocc' ) . ' )' .													// Trigger autocol
									'" style="float:left; cursor:pointer">
		'.__( 'Edit album info', 'wp-photo-album-plus' ).'
	</a>
	<a id="_wppa-ea-'.$a.'-'.wppa( 'mocc' ).'" class="wppa-aedit-'.$where.' wppa-album-'.$where.'-link" onclick="'.
									'jQuery( \'#wppa-fe-div-'.$a.'-'.wppa( 'mocc' ).'\' ).css( \'display\',\'none\' );'.		// Hide the Edit form
									'jQuery( \'#wppa-cr-'.$a.'-'.wppa( 'mocc' ).'\' ).css( \'display\',\'block\' );'.			// Show the Create link
									'jQuery( \'#wppa-up-'.$a.'-'.wppa( 'mocc' ).'\' ).css( \'display\',\'block\' );'.			// Show the Upload link
									'jQuery( \'#wppa-ea-'.$a.'-'.wppa( 'mocc' ).'\' ).css( \'display\',\'block\' );'.			// Show the Edit link
									'jQuery( \'#wppa-cats-' . $a . '-' . wppa( 'mocc' ) . '\' ).css( \'display\',\'block\' );'.	// Show catogory
									'jQuery( \'#_wppa-ea-'.$a.'-'.wppa( 'mocc' ).'\' ).css( \'display\',\'none\' );'. 			// Hide backlink
									'_wppaDoAutocol( ' . wppa( 'mocc' ) . ' )'.													// Trigger autocol
									'" style="float:right; cursor:pointer;display:none;padding-right:6px;">
		' . __( wppa_opt( 'close_text' ), 'wp-photo-album-plus' ) .
	'</a>';


	// Get name and description, if possible multilanguage editable. ( if qTranslate-x content filter not active )
	$name = stripslashes( $album['name'] );
	$desc = stripslashes( $album['description'] );

	// qTranslate(-x) not active or not properly closed tag?
	if ( substr( $name, -3 ) != '[:]' ) {
		$name = __( $name );
	}

	// qTranslate(-x) not active or not properly closed tag?
	if ( substr( $desc, -3 ) != '[:]' ) {
		$desc = __( $desc );
	}

	// Escape
	$name = esc_attr( $name );
	$desc = esc_textarea( $desc );

	$result .=
	'<div id="wppa-fe-div-'.$a.'-'.wppa( 'mocc' ).'" style="display:none">' .
		'<form action="#" method="post" >' .
			'<input' .
				' type="hidden"' .
				' name="wppa-albumeditnonce"' .
				' id="album-nonce-'.wppa( 'mocc' ).'-'.$alb.'"' .
				' value="'.wp_create_nonce( 'wppa-nonce_'.$alb ).'"' .
				' />
			<input' .
				' type="hidden"' .
				' name="wppa-albumeditid"' .
				' id="wppaalbum-id-'.wppa( 'mocc' ).'-'.$alb.'"' .
				' value="'.$alb.'"' .
				' />
			<div' .
				' class="wppa-box-text wppa-td"' .
				' style="' .
					'clear:both;' .
					'float:left;' .
					'text-align:left;' .
					'"' .
				' >'.
				__( 'Enter album name', 'wp-photo-album-plus' ) . '&nbsp;' .
				'<span style="font-size:10px">' .
					__( 'Don\'t leave this blank!', 'wp-photo-album-plus' ) .
				'</span>
			</div>
			<input' .
				' name="wppa-albumeditname"' .
				' id="wppaalbum-name-'.wppa( 'mocc' ).'-'.$alb.'"' .
				' class="wppa-box-text wppa-file-'.$t.wppa( 'mocc' ).'"' .
				' value="' . $name . '"' .
				' style="padding:0; width:100%;"' .
				' />
			<div' .
				' class="wppa-box-text wppa-td"' .
				' style="' .
					'clear:both;' .
					'float:left;' .
					'text-align:left;' .
					'"' .
				' >'.
				__( 'Album description:', 'wp-photo-album-plus' ).'
			</div>
			<textarea' .
				' name="wppa-albumeditdesc"' .
				' id="wppaalbum-desc-'.wppa( 'mocc' ).'-'.$alb.'"' .
				' class="wppa-user-textarea wppa-box-text wppa-file-'.$t.wppa( 'mocc' ).'"' .
				' style="' .
					'padding:0;' .
					'height:120px;' .
					'width:100%;' .
					'"' .
				' >' . $desc .
			'</textarea>';

			// Custom data
			$custom_data = wppa_unserialize( wppa_get_album_item( $alb, 'custom' ) );
			if ( ! is_array( $custom_data ) ) {
				$custom_data = array( '', '', '', '', '', '', '', '', '', '' );
			}
			$idx = '0';
			while ( $idx < '10' ) {
				if ( wppa_switch( 'album_custom_edit_' . $idx ) ) {
					$result .= 	'<div' .
									' class="wppa-box-text wppa-td"' .
									' style="' .
										'clear:both;' .
										'float:left;' .
										'text-align:left;' .
										'"' .
									' >'.
									apply_filters( 'translate_text', wppa_opt( 'album_custom_caption_' . $idx ) ) .
								'</div>' .
								'<input' .
									' name="custom_' . $idx . '"' .
									' id="wppaalbum-custom-' . $idx . '-' . wppa( 'mocc' ) . '-' . $alb . '"' .
									' class="wppa-box-text wppa-file-' . $t . wppa( 'mocc' ) . '"' .
									' value="' . esc_attr( stripslashes( $custom_data[$idx] ) ) . '"' .
									' style="padding:0;width:100%"' .
								' />';

				}
				$idx++;
			}

			$result .= 	'
			<input
				type="submit"
				name="wppa-albumeditsubmit"
				class="wppa-user-submit"
				style="margin:6px 0;float:right"
				value="' . esc_attr( __( 'Update album', 'wp-photo-album-plus' ) ) . '"
			/>
		</form>
	</div>';
	wppa_out( $result );
}

// Build the html for the comment box
function wppa_comment_html( $id, $comment_allowed ) {
global $wpdb;

	$result = '';
	if ( wppa_in_widget() ) return $result;		// NOT in a widget

	// Find out who we are either logged in or not
	$vis = is_user_logged_in() ? 'display:none; ' : '';

	// Mobile?
	$mob = wppa_is_mobile();

	// Occurrance
	$mocc = wppa( 'mocc' );

	// Find user
	if ( wppa_get( 'comname' ) ) wppa( 'comment_user', wppa_get( 'comname' ) );
	if ( wppa_get( 'comemail' ) ) wppa( 'comment_email', wppa_get( 'comemail' ) );
	elseif ( is_user_logged_in() ) {
		$current_user = wp_get_current_user();
		wppa( 'comment_user', $current_user->display_name ); //user_login;
		wppa( 'comment_email', $current_user->user_email );
	}

	// Loop the comments already there
	$n_comments = 0;
	if ( wppa_switch( 'comments_desc' ) ) {
		$comments = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_comments
														 WHERE photo = %d
														 ORDER BY id DESC", $id ), ARRAY_A );
	}
	else {
		$comments = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_comments
														 WHERE photo = %d
														 ORDER BY id", $id ), ARRAY_A );
	}
	$com_count = count( $comments );
	$color = 'darkgrey';
	if ( wppa_opt( 'fontcolor_box' ) ) $color = wppa_opt( 'fontcolor_box' );
	if ( $comments && ( is_user_logged_in() || ! wppa_switch( 'comment_view_login' ) ) ) {

		// Open the existing comments wrapper / table / tbody
		$result .= '
		<div
		id="wppa-comtable-wrap-'.$mocc.'"
		style="display:none;"
		>
		<table
			id="wppacommentstable-' . $mocc . '"
			class="wppa-comment-form"
			style="margin:0; "
			>
			<tbody>';

				// Process the exising comments
				foreach( $comments as $comment ) {

					// Show a comment either when it is approved, or it is pending and mine or i am a moderator
					if ( $comment['status'] == 'approved' ||
						current_user_can( 'wppa_moderate' ) ||
						current_user_can( 'wppa_comments' ) ||
							( ( $comment['status'] == 'pending' || $comment['status'] == 'spam' ) &&
								stripslashes( $comment['user'] ) == wppa( 'comment_user' )
							)
						) {

						// Inc counter
						$n_comments++;

						// Prmium user?
						$premium = wppa_get_premium( $comment['userid'] );

						// Prepare html
						$originatorblock = '
						<td
							class="wppa-box-text wppa-td"
							style="vertical-align:top; width:30%; border-width: 0 0 0 0;"
							>' .
							( wppa_switch( 'domain_link_buddypress' ) ?
								wppa_bp_userlink( $comment['email'], false, true ) :
								esc_js( $comment['user'] )
							) .
							wppa_get_premium_html( $comment['userid'] ) .
							' ' .
							__( 'wrote', 'wp-photo-album-plus' ) . '
							<span style="font-size:9px; ">' .
								wppa_get_time_since( $comment['timestamp'] ) . '
							</span>';

							// Avatar ?
							if ( wppa_opt( 'comment_gravatar' ) != 'none' ) {

								// Find the default
								if ( wppa_opt( 'comment_gravatar' ) != 'url' ) {
									$default = wppa_opt( 'comment_gravatar' );
								}
								else {
									$default = wppa_opt( 'comment_gravatar_url' );
								}

								// Find the avatar, init
								$avt = false;
								$usr = false;

								// First try to find the user by email address ( works only if email required on comments )
								if ( $comment['email'] ) {
									$usr = wppa_get_user_by( 'email', $comment['email'] );
								}

								// If not found, try to find the user by login name ( works only if login name is equal to display name )
								if ( ! $usr ) {
									$usr = wppa_get_user_by( 'login', stripslashes( $comment['user'] ) );
								}

								// Still no user, try to find him by display name
								if ( ! $usr ) {
									$usr = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->users
																				WHERE display_name = %s", stripslashes( $comment['user'] ) ) );

									// Accept this user if he is the only one with this display name
									if ( count( $usr ) != 1 ) {
										$usr = false;
									}
								}

								// If a user is found, see for local Avatar ?
								if ( $usr ) {
									if ( is_array( $usr ) ) {
										$avt = str_replace( "'", "\"", get_avatar( $usr[0]->ID, wppa_opt( 'gravatar_size' ), $default ) );
									}
									else {
										$avt = str_replace( "'", "\"", get_avatar( $usr->ID, wppa_opt( 'gravatar_size' ), $default ) );
									}
								}

								// Global avatars off ? try myself
								if ( ! $avt ) {
									$avt = 	'
										<img' .
											' class="avatar wppa-box-text wppa-td"' .
											' src="http' . ( is_ssl() ? 's' : '' ) . '://www.gravatar.com/avatar/' .
													wppa_get_unique_crypt() .
												//	md5( strtolower( trim( $comment['email'] ) ) ) .
													'.jpg?d='.urlencode( $default ) . '&s=' . wppa_opt( 'gravatar_size' ) . '"' .
											' alt="' . __( 'Avatar', 'wp-photo-album-plus' ) . '"' .
										' />';
								}

								// Compose the html
								$originatorblock .= '
									<div
										class="com_avatar"
										style="float:left;margin-right:5px">' .
										$avt .
									'</div>';
							}
							$originatorblock .= '
						</td>';

						$commentblock = '
						<td
							class="wppa-box-text wppa-td"
							style="width:70%; word-wrap:break-word; border-width: 0 0 0 0;"
							>';

							$c = $comment['comment'];
							$c = wppa_convert_smilies( $c );
							$c = nl2br( $c );
							$c = str_replace( "\n", '', $c );
					//		$c = stripslashes( $c );
							$c = esc_js( $c );
							$c = html_entity_decode( $c );
							if ( wppa_switch( 'comment_clickable' ) ) {
								$c = make_clickable( $c );
							}
							$commentblock .= '
							<blockquote
								class="wppa-comment-bquote"
								style="padding:5px 0;margin:5px 0 0;width:98%;"
								>' .
								$c . '
							</blockquote>';

							// Status approved
							if ( $comment['status'] != 'approved' && ( current_user_can( 'wppa_moderate' ) || current_user_can( 'wppa_comments' ) ) ) {
								if ( wppa( 'no_esc' ) ) {
									$commentblock .= wppa_moderate_links( 'comment', $id, $comment['id'] );
								}
								else {
									$commentblock .= wppa_html( esc_js( wppa_moderate_links( 'comment', $id, $comment['id'] ) ) );
								}
							}

							// Status pending
							if ( $comment['status'] == 'pending' ) {

								// Com needs vote message pending (from wppa_do_comment() in wppa_functions.php)
								if ( wppa( 'comneedsvote' ) ) {
									wppa_alert( __( "Please also give the photo a rating to get your comment published." , 'wp-photo-album-plus' ) );
								}

								// If awaiting ratinmg
								if ( wppa_switch( 'comment_need_vote' ) ) {

									// If its is the current users comment, say Awaiting YOUR rating
									if ( wppa_get_user( 'display' ) == $comment['user'] ) {
										$commentblock .=
										'<span style="color:red">' .
											__( 'Awaiting your rating', 'wp-photo-album-plus' ) .
										'</span>';
									}

									// Other users comment (only seen by moderators)
									else {
										$commentblock .=
										'<span style="color:red">' .
											__( 'Awaiting a rating', 'wp-photo-album-plus' ) .
										'</span>';
									}
								}

								// Not awaiting rating, just pending
								else {
									$commentblock .=
									'<span style="color:red">' .
										__( 'Awaiting moderation', 'wp-photo-album-plus' ) .
									'</span>';
								}
							}

							// Spam?
							elseif ( $comment['status'] == 'spam' && stripslashes( $comment['user'] ) == wppa( 'comment_user' ) ) {
								$commentblock .= '<br><span style="color:red; font-size:9px">'.__( 'Marked as spam', 'wp-photo-album-plus' ).'</span>';
							}

						$commentblock .=
						'</td>';

						// The actual addition to the html
						if ( $mob ) {
							$result .= 	'
							<tr
								class="wppa-comment-'.$comment['id'].'"
								style="border:0 none;vertical-align:top"
								>' .
								$originatorblock .
							'</tr>
							<tr class="wppa-comment-'.$comment['id'].'"
								style="border:0 none;vertical-align:top"
								>' .
								$commentblock .
							'</tr>
							<tr class="wppa-comment-' . $comment['id'] . '" >
								<td style="padding:0" >
									<hr style="background-color:' . $color . '; margin:0;" />
								</td>
							</tr>';
						}
						else {
							$result .= 	'
							<tr
								class="wppa-comment-'.$comment['id'].'"
								style="border:0 none;vertical-align:top"
								>' .
								$originatorblock .
								$commentblock .
							'</tr>
							<tr class="wppa-comment-' . $comment['id'] . '">
								<td colspan="2" style="padding:0">
									<hr style="background-color:' . $color . '; margin:0;" />
								</td>
							</tr>';
						}
					}
				}
				$result .= '
				</tbody>
			</table>
		</div>';
	}

	// See if we are currently in the process of adding/editing this comment
	$is_current = ( $id == wppa( 'comment_photo' ) && wppa( 'comment_id' ) );
	if ( $is_current ) {
		$txt = wppa( 'comment_text' );
// wppa_log('obs', 'is current = '.$txt);
		$btn = __( 'Update!', 'wp-photo-album-plus' );
	}
	else {
		$txt = '';
		$btn = __( 'Send!', 'wp-photo-album-plus' );
	}

	// Prepare the callback url
	$returnurl = wppa_get_permalink();

	$album = wppa_get( 'album' );
	if ( $album !== false ) $returnurl .= 'wppa-album='.$album.'&';
	$cover = wppa_get( 'cover' );
	if ( $cover ) $returnurl .= 'wppa-cover='.$cover.'&';
	$slide = wppa_get( 'slide' );
	if ( $slide !== false ) $returnurl .= 'wppa-slide&';
	$occur = wppa_get( 'occur' );
	if ( $occur ) $returnurl .= 'wppa-occur='.$occur.'&';
	$lasten = wppa_get( 'lasten' );
	if ( $lasten ) $returnurl .= 'wppa-lasten='.$lasten.'&';
	$topten = wppa_get( 'topten' );
	if ( $topten ) $returnurl .= 'wppa-topten='.$topten.'&';
	$comten = wppa_get( 'comten' );
	if ( $comten ) $returnurl .= 'wppa-comten='.$comten.'&';
	$tag = wppa_get( 'tag' );
	if ( $tag ) $returnurl .= 'wppa-tag='.$tag.'&';

	$returnurl .= 'wppa-photo='.$id;

	// Open the actual comment form
	if ( $comment_allowed ) {

		$result .=
			'<div' .
				' id="wppa-comform-wrap-' . $mocc . '"' .
				' style="display:none;"' .
				' >' .

				// The form
				'<form' .
					' id="wppa-commentform-' . $mocc . '"' .
					' class="wppa-comment-form"' .
					' action="' . $returnurl . '"' .
					' method="post"' .
					' onsubmit="return wppaValidateComment( ' . $mocc . ' )"' .
					' >' .

					// The hidden fields
//					wp_nonce_field( 'wppa-nonce-' . $mocc , 'wppa-nonce-' . $mocc, false, false ) .
					( $album ? '<input type="hidden" name="wppa-album" value="' . $album . '" />' : '' ) .
					( $cover ? '<input type="hidden" name="wppa-cover" value="' . $cover . '" />' : '' ) .
					( $slide ? '<input type="hidden" name="wppa-slide" value="' . $slide . '" />' : '' ) .
					'<input' .
						' type="hidden"' .
						' name="wppa-returnurl"' .
						' id="wppa-returnurl-' . $mocc . '"' .
						' value="' . $returnurl . '"' .
					' />' .
					( $is_current ? '<input' .
										' type="hidden"' .
										' id="wppa-comment-edit-' . $mocc . '"' .
										' name="wppa-comment-edit"' .
										' value="' . wppa( 'comment_id' ) . '"' .
									' />' : '' ) .
					'<input type="hidden" name="wppa-occur" value="'.wppa( 'mocc' ).'" />' .

					// Table start
					'<table id="wppacommenttable-'.$mocc.'" style="margin:0;">' .
						'<tbody>';

							// The commenters name label td
							$label_html = '
							<td
								class="wppa-box-text wppa-td"
								style="' . ( $mob ? '' : 'width:30%;' ) . 'background-color:transparent;"
								>' .
								__( 'Your name:', 'wp-photo-album-plus' ) .
							'</td>';

							// The commenters name input td
							$value_html = '
							<td
								class="wppa-box-text wppa-td"
								style="' . ( $mob ? '' : 'width:70%;' ) . 'background-color:transparent;"
								>
								<input
									type="text"
									name="wppa-comname"
									id="wppa-comname-' . $mocc . '"
									style="width:98%;
									" value="' . esc_js( wppa( 'comment_user' ) ) . '"
									/>
							</td>';

							// Name
							if ( $mob ) {
								$result .= '
								<tr style="vertical-align:top;' . $vis . '">' .
									$label_html . '
								</tr >
								<tr style="vertical-align:top;' . $vis . '">' .
									$value_html . '
								</tr >';
							}
							else {
								$result .= '
								<tr style="vertical-align:top;' . $vis . '">' .
									$label_html .
									$value_html . '
								</tr >';
							}

							// The commenters email label td
							$label_html = '
							<td
								class="wppa-box-text wppa-td"
								style="width:30%;background-color:transparent;"
								>' .
								__( 'Your email:', 'wp-photo-album-plus' ) .
							'</td>';

							// The commenters email input td
							$value_html = '
							<td
								class="wppa-box-text wppa-td"
								style="width:70%;background-color:transparent;"
								>
								<input
									type="text"
									name="wppa-comemail"
									id="wppa-comemail-' . $mocc . '"
									style="width:98%;"
									value="' . wppa( 'comment_email' ) . '"
								/>
							</td>';

							// Email
							if ( $mob ) {
								$result .= '
								<tr style="vertical-align:top;' . $vis . '">' .
									$label_html . '
								</tr >
								<tr style="vertical-align:top;' . $vis . '">' .
									$value_html . '
								</tr >';
							}
							else {
								$result .= '
								<tr style="vertical-align:top;' . $vis . '">' .
									$label_html .
									$value_html . '
								</tr >';
							}

							// The comment label
							$comment_label = __( 'Your comment:', 'wp-photo-album-plus' );

							// The captch label
							$captcha_label = __( 'Calculate:', 'wp-photo-album-plus' );

							// The captcha input
							$captkey = ( $is_current ?
											$wpdb->get_var( $wpdb->prepare( "SELECT timestamp FROM $wpdb->wppa_comments
																			 WHERE id = %d", wppa( 'comment_id' ) ) ) :
											$id );

							$captcha_input =
							wppa_make_captcha( $captkey ) . '
							<input
								type="text"
								id="wppa-captcha-' . $mocc . '"
								name="wppa-captcha"
								style="width:30px;"
							/>';

							// The Smilypicker
							$smily_html = wppa_switch( 'comment_smiley_picker' ) ? wppa_get_smiley_picker_html( 'wppa-comment-'.$mocc ) : '';

							// The current comment text
						//	$txt = wppa( 'comment_text' );
						//	$txt = stripslashes( $txt );
						//	$txt = esc_js( $txt );
						//	$txt = html_entity_decode( $txt );
						$txt = wppa_decode( wppa_get( 'comment', '', 'textarea' ) );

							// The comment input
							$comment_input = '
							<textarea
								name="wppa-comment"
								id="wppa-comment-' . $mocc . '"
								style="height:60px; width:98%; "
								>' .
								/*esc_textarea( stripslashes( $txt ) ) . */
								$txt .
							'</textarea>';

							// DB checkbox and message
							$dbconfirm_html = '
							<input
								type="checkbox"' .
								( $is_current ? ' checked="checked"' : '' ) . '
								id="db-agree-' . $mocc . '"
								name="db-agree"
								style="float:left"
								/>
							<label
								for="db-agree-' . $mocc . '"
								style="float:left"
								>
								&nbsp;' .
								sprintf( __( 'I agree that the information above will be stored in a database along with my %s', 'wp-photo-album-plus' ),
												is_user_logged_in() ? __( 'login name', 'wp-photo-album-plus' ) : __( 'ip address', 'wp-photo-album-plus' ) ) . '
							</label>';

							// Go button
							$cid = "\'".wppa_get_photo_item( $id, 'crypt' )."\'";
							$gobutton_html = '
							<input
								type="button"
								name="commentbtn"
								onclick="wppaAjaxComment(' . $mocc . ', ' . $cid . ')"
								value="' . esc_attr( $btn ) . '"
								style="margin:0 4px 0 0;"
							/>
							<img
								id="wppa-comment-spin-' . $mocc . '"
								src="' . wppa_get_imgdir() . 'spinner.gif"
								style="display:none;"
							/>';

							$need_captcha = ( is_user_logged_in() && wppa_opt( 'comment_captcha' ) == 'all' ) ||
											( ! is_user_logged_in() && wppa_opt( 'comment_captcha' ) != 'none' );
							// Comment
							if ( $mob ) {
								$result .= '
								<tr>
									<td
										class="wppa-box-text wppa-td"
										style="background-color:transparent;"
										>' .
										$comment_label . '
									</td>
								</tr>
								<tr>
									<td
										class="wppa-box-text wppa-td"
										style="background-color:transparent;"
										>' .
										$smily_html .
										$comment_input . '
									</td>
								<tr>
								<tr>
									<td
										class="wppa-box-text wppa-td"
										style="background-color:transparent;"
										>';
										if ( $need_captcha ) {

											$result .=
											$captcha_label . ' ' .
											$captcha_input . ' ';
										}

										$result .=
										$gobutton_html . '
									</td>
								</tr>
								';
							}
							else {
								$result .= '
								<tr
									style="vertical-align:top;"
									>
									<td
										class="wppa-box-text wppa-td"
										style="vertical-align:top; width:30%;background-color:transparent;"
										>' .
										$comment_label;

										if ( $need_captcha ) {

											$result .= '
											<br><br>' .
											$captcha_label . '
											<br>' .
											$captcha_input;
										}

										$result .=
										$gobutton_html . '
									</td>
									<td>' .
										$smily_html .
										$comment_input . '
									</td>
								</tr>';
							}

							$result .= '
						</tbody>
					</table>
				</form>
			</div>';
	}
	elseif ( wppa_user_is_basic() ) {
		$result .= __( 'You must upgrade your membership to enter a comment', 'wp-photo-album-plus' );
	}
	else {
		if ( wppa_switch( 'login_links' ) ) {
			$result .= sprintf( __( 'You must <a href="%s">login</a> to enter a comment', 'wp-photo-album-plus' ), wppa_opt( 'login_url' ) );
		}
		else {
			$result .= __( 'You must login to enter a comment', 'wp-photo-album-plus' );
		}
	}

	$result .=
			'<div id="wppa-comfooter-wrap-'.wppa( 'mocc' ).'" style="display:block">' .
				'<table id="wppacommentfooter-'.wppa( 'mocc' ).'" class="wppa-comment-form" style="margin:0;">' .
					'<tbody>' .
						'<tr style="text-align:center;">' .
							'<td style="text-align:center; cursor:pointer">' .
								'<a onclick="wppaOpenComments( '.wppa( 'mocc' ).', -1 ); return false">';
			if ( $n_comments ) {
				$result .= sprintf( _n( '%d comment', '%d comments', $n_comments, 'wp-photo-album-plus' ), $n_comments );
			}
			else {
				if ( $comment_allowed ) {
					$result .= __( 'Leave a comment', 'wp-photo-album-plus' );
				}
			}
		$result .=
								'</a>' .
							'</td>' .
						'</tr>' .
					'</tbody>' .
				'</table>' .
			'</div>' .
			'<div style="clear:both"></div>';

	return $result;
}

// The smiley picker for the comment box
function wppa_get_smiley_picker_html( $elm_id ) {
static $wppa_smilies;
global $wpsmiliestrans;

	// Fill inverted smilies array if needed
	if ( ! is_array( $wppa_smilies ) ) {
		if ( is_array( $wpsmiliestrans ) ) {
			foreach( array_keys( $wpsmiliestrans ) as $idx ) {
				if ( ! isset ( $wppa_smilies[$wpsmiliestrans[$idx]] ) ) {
					$wppa_smilies[$wpsmiliestrans[$idx]] = $idx;
				}
			}
		}
	}

	// Make the html
	$result = '';
	if ( is_array( $wppa_smilies ) ) {
		foreach ( array_keys( $wppa_smilies ) as $key ) {
			$onclick 	= esc_attr( 'wppaInsertAtCursor( document.getElementById( "' . $elm_id . '" ), " ' . $wppa_smilies[$key] . ' " )' );
			$title 		= trim( $wppa_smilies[$key], ':' );
			$result 	.= 	'<a onclick="'.$onclick.'" title="'.$title.'" >';
			$result 	.= 		wppa_convert_smilies( $wppa_smilies[$key] );
			$result 	.= 	'</a>';
		}
	}

	return $result;
}

// IPTC box
function wppa_iptc_html( $photo ) {
global $wpdb;
global $wppa_iptc_labels;
global $wppa_iptc_cache;

	// Get tha labels if not yet present
	if ( ! is_array( $wppa_iptc_labels ) ) {
		$wppa_iptc_labels = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_iptc
												 WHERE photo = '0'
												 ORDER BY tag", ARRAY_A );
	}

	$count = 0;

	// If in cache, use it
	$iptcdata = false;
	if ( is_array( $wppa_iptc_cache ) ) {
		if ( isset( $wppa_iptc_cache[$photo] ) ) {
			$iptcdata = $wppa_iptc_cache[$photo];
		}
	}

	// Get the photo data
	if ( $iptcdata === false ) {
		$iptcdata = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_iptc
														 WHERE photo = %s
														 ORDER BY tag", $photo ), ARRAY_A );

		// Save in cache, even when empty
		$wppa_iptc_cache[$photo] = $iptcdata;
	}

	if ( $iptcdata ) {

		// Open the container content
		$result = '<div id="iptccontent-'.wppa( 'mocc' ).'" >';

		// Open or closed?
		$d1 = wppa_switch( 'show_iptc_open' ) ? 'display:none;' : 'display:inline;';
		$d2 = wppa_switch( 'show_iptc_open' ) ? 'display:inline;' : 'display:none;';

		// Process data
		$onclick = 	'wppaStopShow( ' . wppa( 'mocc' ) . ' );' .
					'jQuery( \'.wppa-iptc-table-' . wppa( 'mocc' ) . '\' ).css( \'display\', \'\' );' .
					'jQuery( \'.-wppa-iptc-table-' . wppa( 'mocc' ). '\' ).css( \'display\', \'none\' );';

		$result .= 	'<a' .
						' class="-wppa-iptc-table-' . wppa( 'mocc' ) . '"' .
						' onclick="' . esc_attr( $onclick ) . '"' .
						' style="cursor:pointer;' . $d1 . '"' .
						' >' .
						__( 'Show IPTC data', 'wp-photo-album-plus' ) .
					'</a>';

		$onclick = 	'jQuery( \'.wppa-iptc-table-' . wppa( 'mocc' ) . '\' ).css( \'display\', \'none\' );' .
					'jQuery( \'.-wppa-iptc-table-' . wppa( 'mocc' ) . '\' ).css( \'display\', \'\' );';

		$result .= 	'<a' .
						' class="wppa-iptc-table-switch wppa-iptc-table-' . wppa( 'mocc' ) . '"' .
						' onclick="'.esc_attr($onclick).'"' .
						' style="cursor:pointer;'.$d2.'"' .
						' >' .
						__( 'Hide IPTC data', 'wp-photo-album-plus' ) .
					'</a>';

		$result .=
				'<div style="clear:both"></div>' .
					'<table class="wppa-iptc-table-'.wppa( 'mocc' ).' wppa-detail" style="border:0 none; margin:0;'.$d2.'" >' .
						'<tbody>';
		$oldtag = '';
		foreach ( $iptcdata as $iptcline ) {

			$default = 'default';
			$label = '';
			foreach ( $wppa_iptc_labels as $iptc_label ) {
				if ( $iptc_label['tag'] == $iptcline['tag'] ) {
					$default = $iptc_label['status'];
					$label   = rtrim( $iptc_label['description'], " \n\r\t\v\0:" );
				}
			}

			// Photo status is hide ?
			if ( $iptcline['status'] == 'hide' ) continue;

			// P s is default and default is hide?
			if ( $iptcline['status'] == 'default' && $default == 'hide' ) continue;

			// P s is default and default is optional and field is empty ?
			if ( $iptcline['status'] == 'default' && $default == 'option' && ! trim( $iptcline['description'], "\x00..\x1F " ) ) continue;

			$count++;
			$newtag = $iptcline['tag'];
			if ( $newtag != $oldtag && $oldtag != '' ) $result .= '</td></tr>';	// Close previous line
			if ( $newtag == $oldtag ) {
				$result .= '; ';							// next item with same tag
			}
			else {
				$result .= 	'
				<tr style="border-bottom:0 none; border-top:0 none; border-left: 0 none; border-right: 0 none">
					<td class="wppa-iptc-label wppa-box-text wppa-td" style="width:50%;text-align:right">' .
						esc_js( __( $label ) ) . '
					</td>
					<td class="wppa-iptc-value wppa-box-text wppa-td" style="width:50%;text-align:left">';
			}
			$result .= esc_js( wppa_sanitize_text( __( $iptcline['description'], 'wp-photo-album-plus' ) ) );
			$oldtag = $newtag;
		}
		if ( $oldtag != '' ) $result .= '</td></tr>';	// Close last line
		$result .= '</tbody></table></div>';
	}
	if ( ! $count ) {
		$result = '<div id="iptccontent-'.wppa( 'mocc' ).'" >'.__( 'No IPTC data', 'wp-photo-album-plus' ).'</div>';
	}

	return ( $result );
}

// EXIF box
function wppa_exif_html( $photo ) {
global $wpdb;
global $wppa_exif_labels;
global $wppa_exif_cache;

	// Get tha labels if not yet present
	if ( ! is_array( $wppa_exif_labels ) ) {
		$wppa_exif_labels = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_exif
												 WHERE photo = '0'
												 ORDER BY tag", ARRAY_A );
	}

	$count = 0;

	$brand = wppa_get_camera_brand( $photo );

	// If in cache, use it
	$exifdata = false;
	if ( is_array( $wppa_exif_cache ) ) {
		if ( isset( $wppa_exif_cache[$photo] ) ) {
			$exifdata = $wppa_exif_cache[$photo];
		}
	}

	// Get the photo data
	if ( $exifdata === false ) {
		$exifdata = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_exif
														 WHERE photo = %s
														 ORDER BY tag", $photo ), ARRAY_A );

		// Save in cache, even when empty
		$wppa_exif_cache[$photo] = $exifdata;
	}

	// Create the output
	if ( ! empty( $exifdata ) ) {

		// Open the container content
		$result = '<div id="exifcontent-'.wppa( 'mocc' ).'" >';

		// Open or closed?
		$d1 = wppa_switch( 'show_exif_open' ) ? 'display:none;' : 'display:inline;';
		$d2 = wppa_switch( 'show_exif_open' ) ? 'display:inline;' : 'display:none;';

		// Process data
		$onclick = 	'wppaStopShow( ' . wppa( 'mocc' ) . ' );' .
					'jQuery( \'.wppa-exif-table-' . wppa( 'mocc' ) . '\' ).css( \'display\', \'\' );' .
					'jQuery( \'.-wppa-exif-table-' . wppa( 'mocc' ) . '\' ).css( \'display\', \'none\' );';

		$result .= 	'<a' .
						' class="-wppa-exif-table-' . wppa( 'mocc' ) . '"' .
						' onclick="' . esc_attr( $onclick ) . '"' .
						' style="cursor:pointer;' . $d1 . '"' .
						' >' .
						__( 'Show EXIF data', 'wp-photo-album-plus' ) .
					'</a>';

		$onclick = 	'jQuery( \'.wppa-exif-table-' . wppa( 'mocc' ) . '\' ).css( \'display\', \'none\' );' .
					'jQuery( \'.-wppa-exif-table-' . wppa( 'mocc' ) . '\' ).css( \'display\', \'\' )';

		$result .= 	'<a' .
						' class="wppa-exif-table-switch wppa-exif-table-' . wppa( 'mocc' ) . '"' .
						' onclick="' . esc_attr( $onclick ) . '"' .
						' style="cursor:pointer;' . $d2 . '"' .
						' >' .
						__( 'Hide EXIF data', 'wp-photo-album-plus' ) .
					'</a>';

		$result .= 	'<div style="clear:both"></div>' .
					'<table' .
						' class="wppa-exif-table-'.wppa( 'mocc' ).' wppa-detail"' .
						' style="'.$d2.' border:0 none; margin:0;"' .
						' >' .
						'<tbody>';
		$oldtag = '';
		foreach ( $exifdata as $exifline ) {

			$default = 'default';
			$label = '';
			foreach ( $wppa_exif_labels as $exif_label ) {
				if ( $exif_label['tag'] == $exifline['tag'] ) {
					$default = $exif_label['status'];
					$label   = $exif_label['description'];
				}
			}

			// Photo status is hide ?
			if ( $exifline['status'] == 'hide' ) continue;

			// P s is default and default is hide
			if ( $exifline['status'] == 'default' && $default == 'hide' ) continue;

			// P s is default and default is optional and field is empty
			if ( $exifline['status'] == 'default' && $default == 'option' && ! $exifline['f_description'] ) continue;

			$count++;
			$newtag = $exifline['tag'];
			if ( $newtag != $oldtag && $oldtag != '' ) $result .= '</td></tr>';	// Close previous line
			if ( $newtag == $oldtag ) {
				$result .= '; ';							// next item with same tag
			}
			else {
				$result .= 	'<tr style="border-bottom:0 none; border-top:0 none; border-left: 0 none; border-right: 0 none">' .
							'<td class="wppa-exif-label wppa-box-text wppa-td" style="width:50%;text-align:right">';

				$label = rtrim( wppa_exif_tagname( $exifline['tag'], $brand ), " \n\r\t\v\0:" );

				$result .= esc_js( __( $label ) );

				$result .= '</td><td class="wppa-exif-value wppa-box-text wppa-td" style="width:50%;text-align:left">';
			}
			$result .= esc_js( $exifline['f_description'] );
			$oldtag = $newtag;
		}
		if ( $oldtag != '' ) $result .= '</td></tr>';	// Close last line
		$result .= '</tbody></table></div>';
	}
	if ( ! $count ) {
		$result = '<div id="exifcontent-'.wppa( 'mocc' ).'" >'.__( 'No EXIF data', 'wp-photo-album-plus' ).'</div>';
	}

	return ( $result );
}

// Display the album name ( on a thumbnail display ) either on top or at the bottom of the thumbnail area
function wppa_album_name( $key ) {

	// Virtual albums have no name
	if ( wppa_is_virtual() ) return;

	// Album enumerations have no name
	if ( strlen( wppa( 'start_album' ) ) > '0' && ! wppa_is_int( wppa( 'start_album' ) ) ) return;

	$result = '';
	if ( wppa_opt( 'albname_on_thumbarea' ) == $key && wppa( 'start_album' ) ) {
		$name = wppa_get_album_name( wppa( 'start_album' ) );
		if ( $key == 'top' ) {
			$result .= 	'<h3' .
							' id="wppa-albname-' . wppa( 'mocc' ) . '"' .
							' class="wppa-box-text wppa-black"' .
							' style="padding-right:6px; margin:0;"' .
							' >' .
							$name .
						'</h3>' .
						'<div style="clear:both" ></div>';
		}
		if ( $key == 'bottom' ) {
			$result .= 	'<h3' .
							' id="wppa-albname-b-' . wppa( 'mocc' ) . '"' .
							' class="wppa-box-text wppa-black"' .
							' style="clear:both; padding-right:6px; margin:0;"' .
							' >' .
							$name .
						'</h3>';
		}
	}

	wppa_out( $result );
}

// Display the album description ( on a thumbnail display ) either on top or at the bottom of the thumbnail area
function wppa_album_desc( $key ) {

	// Virtual albums have no name
	if ( wppa_is_virtual() ) return;

	// Album enumerations have no name
	if ( strlen( wppa( 'start_album' ) ) > '0' && ! wppa_is_int( wppa( 'start_album' ) ) ) return;

	$result = '';
	if ( wppa_opt( 'albdesc_on_thumbarea' ) == $key && wppa( 'start_album' ) ) {
		$desc = wppa_get_album_desc( wppa( 'start_album' ) );
		if ( $key == 'top' ) {
			$result .= 	'<div' .
							' id="wppa-albdesc-'.wppa( 'mocc' ).'"' .
							' class="wppa-box-text wppa-black wppa-thumbarea-albdesc"' .
							' style="padding-right:6px;"' .
							' >' .
							$desc .
						'</div>' .
						'<div style="clear:both" ></div>';
		}
		if ( $key == 'bottom' ) {
			$result .= 	'<div' .
							' id="wppa-albdesc-b-'.wppa( 'mocc' ).'"' .
							' class="wppa-box-text wppa-black wppa-thumbarea-albdesc"' .
							' style="clear:both; padding-right:6px;"' .
							' >' .
							$desc .
						'</div>';
		}
	}

	wppa_out( $result );
}

// The auto page links
function wppa_auto_page_links( $where ) {
global $wpdb;

	$m = $where == 'bottom' ? 'margin-top:8px;' : '';
	$mustwhere = wppa_opt( 'auto_page_links' );
	if ( ( $mustwhere == 'top' || $mustwhere == 'both' ) && ( $where == 'top' ) || ( ( $mustwhere == 'bottom' || $mustwhere == 'both' ) && ( $where == 'bottom' ) ) ) {
		wppa_out( '
			<div' .
				' id="prevnext1-'.wppa( 'mocc' ).'"' .
				' class="wppa-box wppa-nav wppa-nav-text"' .
				' style="text-align: center; ' . $m . '"' .
				' >' );

		$photo = wppa( 'single_photo' );
		$thumb = wppa_cache_photo( $photo );
		$album = $thumb['album'];
		$photos = $wpdb->get_results( $wpdb->prepare( "SELECT id, page_id FROM $wpdb->wppa_photos
													   WHERE album = %s
													   ORDER BY " . wppa_get_poc( $album ), $album ), ARRAY_A );
		$prevpag = '0';
		$nextpag = '0';
		$curpag  = wppa_get_the_ID();
		$count = count( $photos );
		$count_ = $count - 1;
		$current = '0';
		if ( $photos ) {
			foreach ( array_keys( $photos ) as $idx ) {
				if ( $photos[$idx]['page_id'] == $curpag ) {
					if ( $idx != '0' ) $prevpag = wppa_get_the_auto_page( $photos[$idx-1]['id'] ); // ['page_id'];
					if ( $idx != $count_ ) $nextpag = wppa_get_the_auto_page( $photos[$idx+1]['id'] ); // ['page_id'];
					$current = $idx;
				}
			}
		}

		if ( $prevpag ) {
			wppa_out(	'<a href="'.get_permalink( $prevpag ).'" style="float:left" >' .
							__( '< Previous', 'wp-photo-album-plus' ) .
						'</a>' );
		}
		else {
			wppa_out( 	'<span style="visibility:hidden" >' .
							__( '< Previous', 'wp-photo-album-plus' ) .
						'</span>' );
		}
		wppa_out( ++$current.'/'.$count );
		if ( $nextpag ) {
			wppa_out( 	'<a href="'.get_permalink( $nextpag ).'" style="float:right" >' .
							__( 'Next >', 'wp-photo-album-plus' ) .
						'</a>' );
		}
		else {
			wppa_out( 	'<span style="visibility:hidden" >' .
							__( 'Next >', 'wp-photo-album-plus' ) .
						'</span>' );
		}

		wppa_out( '</div><div style="clear:both"></div>' );
	}
}

// The bestof box
function wppa_bestof_box( $args ) {

	wppa_container( 'open' );
	wppa_out( 	'<div' .
					' id="wppa-bestof-' . wppa( 'mocc' ) . '"' .
					' class="wppa-box wppa-bestof"' .
					'>' .
					wppa_bestof_html( $args, false ) .
					'<div style="clear:both; height:4px;">' .
					'</div>' .
				'</div>'
			);
	wppa_container( 'close' );
}

// The Bestof html
function wppa_bestof_html( $args, $widget = true ) {
global $photos_used;
global $other_deps;

	$photos_used = '*';
	$other_deps = 'R';

	// Copletify args
	$args = wp_parse_args( ( array ) $args, array( 	'page' 			=> '0',
													'count' 		=> '1',
													'sortby' 		=> 'maxratingcount',
													'display' 		=> 'photo',
													'period' 		=> 'thisweek',
													'maxratings'	=> 'yes',
													'meanrat' 		=> 'yes',
													'ratcount' 		=> 'yes',
													'linktype' 		=> 'none',
													'size' 			=> wppa_opt( 'widget_width' ),
													'fontsize' 		=> wppa_opt( 'fontsize_widget_thumb' ),
													'lineheight' 	=> wppa_opt( 'fontsize_widget_thumb' ) * 1.5,
													'height' 		=> '200',
													'totvalue' 		=> '',
											 ) );

	// Make args into seperate vars
	extract ( $args );

	if ( ! $widget ) {
		$size = $height;
	}

	$result = '';

	$data = wppa_get_the_bestof( $count, $period, $sortby, $display );

	if ( $display == 'photo' ) {

		if ( is_array( $data ) ) {

			foreach ( array_keys( $data ) as $id ) {

				$thumb = wppa_cache_photo( $id );
				if ( $thumb ) {

					if ( wppa_is_video( $id ) ) {
						$imgsize 	= array( wppa_get_videox( $id ), wppa_get_videoy( $id ) );
					}
					else {
						$imgsize	= array( wppa_get_photox( $id ), wppa_get_photoy( $id ) );
					}
					if ( $widget ) {
						$maxw 		= $size;
						$maxh 		= round ( $maxw * $imgsize['1'] / $imgsize['0'] );
					}
					else {
						$maxh 		= $size;
						$maxw 		= $size; // round ( $maxh * $imgsize['0'] / $imgsize['1'] );
					}
					$totalh 		= $maxh + $lineheight;
					if ( $maxratings == 'yes' ) $totalh += $lineheight;
					if ( $meanrat == 'yes' ) 	$totalh += $lineheight;
					if ( $ratcount == 'yes' ) 	$totalh += $lineheight;
					if ( $totvalue == 'yes' ) 	$totalh += $lineheight;

					if ( $widget ) $clear = 'clear:both; '; else $clear = '';
					$result .= "\n" .
								'<div' .
									' class="' . ( $widget ? 'wppa-widget' : 'thumbnail-frame-' . wppa( 'mocc' ) ) . '"' .
									' style="' .
										$clear .
										'width:' . $maxw . 'px;height:' . $totalh . 'px;' .
										( $widget ? 'margin:4px;display:inline;' : 'margin-top:3px;margin-bottom:3px;margin-left:' . wppa_opt( 'tn_margin' ) . 'px;' ) .
										'text-align:center;float:left;' .
										'"'.
									' >';

						// The medal if at the top
						$result .= wppa_get_medal_html_a( array( 'id' => $id, 'size' => 'M', 'where' => 'top' ) );

						// The link if any
						if ( $linktype == 'lightboxsingle' ) {
							$lbtitle 	= wppa_get_lbtitle( 'sphoto', $id );
							$videobody 	= esc_attr( wppa_get_video_body( $id ) );
							$audiobody 	= esc_attr( wppa_get_audio_body( $id ) );
							$videox 	= wppa_get_videox( $id );
							$videoy 	= wppa_get_videoy( $id );
							$result .=
							'<a' .
								' data-id="' . wppa_encrypt_photo( $id ) . '"' .
								' href="' . wppa_get_photo_url( $id ) . '"' .
								( $lbtitle ? ' ' . 'data-lbtitle' . '="'.$lbtitle.'"' : '' ) .
								( $videobody ? ' data-videohtml="' . $videobody . '"' : '' ) .
								( $audiobody ? ' data-audiohtml="' . $audiobody . '"' : '' ) .
								( $is_pdf ? ' data-pdfhtml="' . esc_attr( wppa_get_pdf_html( $id ) ) .'"' : '' ) .
								( $videox ? ' data-videonatwidth="' . $videox . '"' : '' ) .
								( $videoy ? ' data-videonatheight="' . $videoy . '"' : '' ) .
								' data-rel="wppa"' .
					//			( $link['target'] ? ' target="' . $link['target'] . '"' : '' ) .
								' class="thumb-img"' .
								' id="a-' . $id . '-' . wppa( 'mocc' ) . '"' .
								' data-alt="' . esc_attr( wppa_get_imgalt( $id, true ) ) . '"' .
								' style="cursor:' . wppa_wait() . ';"' . // url( ' . wppa_get_imgdir() . wppa_opt( 'magnifier' ) . ' ),pointer"' .
								' title="' . wppa_zoom_in( $id ) . '"' .
								wppa_get_lb_panorama_full_html( $id ) .
								' onclick="return false;"' .
								' >';
						}
						elseif ( $linktype != 'none' ) {
							switch ( $linktype ) {
								case 'owneralbums':
									$href = wppa_get_permalink( $page ).'wppa-cover=1&amp;wppa-owner='.$thumb['owner'].'&amp;wppa-occur=1';
									$title = __( 'See the authors albums', 'wp-photo-album-plus' );
									break;
								case 'ownerphotos':
									$href = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-owner='.$thumb['owner'].'&photos-only&amp;wppa-occur=1';
									$title = __( 'See the authors photos', 'wp-photo-album-plus' );
									break;
								case 'upldrphotos':
									$href = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-upldr='.$thumb['owner'].'&amp;wppa-occur=1';
									$title = __( 'See all the authors photos', 'wp-photo-album-plus' );
									break;
								case 'ownerphotosslide':
									$href = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-owner='.$thumb['owner'].'&photos-only&amp;wppa-occur=1&slide';
									$title = __( 'See the authors photos', 'wp-photo-album-plus' );
									break;
								case 'upldrphotosslide':
									$href = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-upldr='.$thumb['owner'].'&amp;wppa-occur=1&slide';
									$title = __( 'See all the authors photos', 'wp-photo-album-plus' );
									break;
								default:
									$href = '';
									$title = '';
							}
							$result .= '<a href="'.wppa_convert_to_pretty( $href ).'" title="'.$title.'" >';
						}

						// Compute image top margin for box version
						$tx = wppa_get_thumbx( $id );
						$ty = wppa_get_thumby( $id );
						$tm = '0';
						if ( $tx > $ty ) {
							$totm = ( $tx - $ty ) * ( $maxh / $tx );
							switch( wppa_opt( 'valign' ) ) {
								case 'center':
									$tm = round( $totm / 2 );
									break;
								case 'bottom':
									$tm = $totm;
									break;
								default:
									$tm = 0;
							}
						}

						// The image
						$result .=
						'<div style="height:' . $maxh . 'px;width:' . $maxw . 'px">' .
							'<img' .
								( $widget ? ' style="height:' . $maxh . 'px; width:' . $maxw . 'px;"' :
											' style="max-height:' . $maxh . 'px; max-width:' . $maxw . 'px;margin-top:' . $tm . 'px"' ) .
								' src="' . wppa_get_photo_url( $id, true, '', $maxw, $maxh ) . '"' .
								' ' . wppa_get_imgalt( $id ) .
							' />' .
						'</div>';

						// The /link
						if ( $linktype != 'none' ) {
							$result .= '</a>';
						}

						// The medal if near the bottom
						$result .= wppa_get_medal_html_a( array( 'id' => $id, 'size' => 'M', 'where' => 'bot' ) );

						// The subtitles
						$result .= "\n\t".'<div style="font-size:'.$fontsize.'px; line-height:'.$lineheight.'px; position:absolute; width:'.$maxw.'px">';
							$result .= sprintf( __( 'Photo by: %s', 'wp-photo-album-plus' ), $data[$id]['user'] ).'<br>';
							if ( $maxratings 	== 'yes' ) {
								$n = $data[$id]['maxratingcount'];
								$result .= sprintf( _n( '%d max rating', '%d max ratings', $n, 'wp-photo-album-plus' ), $n ) . '<br>';
							}
							if ( $ratcount 		== 'yes' ) {
								$n = $data[$id]['ratingcount'];
								$result .= sprintf( _n( '%d vote', '%d votes', 'wp-photo-album-plus' ), $n ) . '<br>';
							}
							if ( $meanrat  		== 'yes' ) {
								$m = $data[$id]['meanrating'];
								$result .= sprintf( __( 'Rating: %4.2f.', 'wp-photo-album-plus' ), $m ) . '<br>';
							}
							if ( $totvalue 		== 'yes' ) {
								$t = $data[$id]['totvalue'];
								$result .= sprintf( __( 'Total rating: %d', 'wp-photo-album-plus' ), $t ) . '<br>';
							}

						$result .= '</div>';
						$result .= '<div style="clear:both"></div>';

					$result .= "\n".'</div>';
				}
				else {	// No image
					$result .= '<div>'.sprintf( __( 'Photo %s not found.', 'wp-photo-album-plus' ), $id ).'</div>';
				}
			}
		}
		else {
			$result .= $data;	// No array, print message
		}
	}
	else {	// Display = owner
		if ( is_array( $data ) ) {
			$result .= '<ul>';
			foreach ( array_keys( $data ) as $author ) {
				$result .= '<li>';
				// The link if any
				if ( $linktype != 'none' ) {
					switch ( $linktype ) {
						case 'owneralbums':
							$href = wppa_get_permalink( $page ).'wppa-cover=1&amp;wppa-owner='.$data[$author]['owner'].'&amp;wppa-occur=1';
							$title = __( 'See the authors albums', 'wp-photo-album-plus' );
							break;
						case 'ownerphotos':
							$href = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-owner='.$data[$author]['owner'].'&amp;photos-only=1&amp;wppa-occur=1';
							$title = __( 'See the authors photos', 'wp-photo-album-plus' );
							break;
						case 'ownerphotosslide':
							$href = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-owner='.$data[$author]['owner'].'&amp;slide=1&amp;wppa-occur=1';
							$title = __( 'See the authors photos in a slideshow', 'wp-photo-album-plus' );
							break;
						case 'upldrphotos':
							$href = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-upldr='.$data[$author]['owner'].'&amp;wppa-occur=1';
							$title = __( 'See all the authors photos', 'wp-photo-album-plus' );
							break;
						case 'upldrphotosslide':
							$href = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-upldr='.$data[$author]['owner'].'&amp;wppa-occur=1&amp;slide=1';
							$title = __( 'See all the authors photos', 'wp-photo-album-plus' );
							break;
						default:
							$href = '';
							$title = '';
							wppa_log( 'err', 'Unimplemented linktype: ' . $linktype . ' in wppa_bestof_html()' );
							break;
					}
					$result .= '<a href="'.$href.'" title="'.$title.'" >';
				}

				// The name
				$result .= $author;

				// The /link
				if ( $linktype != 'none' ) {
					$result .= '</a>';
				}

				$result .= '<br/>';

				// The subtitles
				$result .= "\n" .
							'<div style="font-size:'.wppa_opt( 'fontsize_widget_thumb' ).'px; line-height:'.$lineheight.'px">';
							if ( $maxratings 	== 'yes' ) {
								$n = $data[$author]['maxratingcount'];
								$result .= sprintf( _n( '%d max rating', '%d max ratings', $n, 'wp-photo-album-plus' ), $n ).'<br>';
							}
							if ( $ratcount 		== 'yes' ) {
								$n = $data[$author]['ratingcount'];
								$result .= sprintf( _n( '%d vote', '%d votes', 'wp-photo-album-plus' ), $n ).'<br>';
							}
							if ( $meanrat  		== 'yes' ) {
								$m = $data[$author]['meanrating'];
								$result .= sprintf( __( 'Mean value: %4.2f.', 'wp-photo-album-plus' ), $m ).'<br>';
							}
							if ( $totvalue 		== 'yes' ) {
								$t = $data[$author]['totvalue'];
								$result .= sprintf( __( 'Total rating: %d', 'wp-photo-album-plus' ), $t ) . '<br>';
							}

				$result .= 	'</div>';
				$result .= 	'</li>';
			}
			$result .= '</ul>';
		}
		else {
			$result .= $data;	// No array, print message
		}
	}

	return $result;
}

// The calendar box
function wppa_calendar_box() {

	if ( is_feed() ) return;

	// The calendar container
	wppa_container( 'open' );

	// Get the selected fontsize-lineheight. small = 10-12, medium = 14-17, large = 18-22, xlarge = 22-24
	$fontsize = wppa_opt('font_calendar_by');
	$bold = wppa_switch( 'font_calendar_by_bold' );

	switch ( $fontsize ) {
		case 'xlarge':
			$fs = 22;
			$lh = 26;
			$cw = 60;
			break;
		case 'large':
			$fs = 18;
			$lh = 22;
			$cw = 50;
			break;
		case 'medium':
			$fs = 14;
			$lh = 17;
			$cw = 40;
			break;
		default: // small
			$fs = 10;
			$lh = 12;
			$cw = 30;
		break;
	}

	wppa_out( 	'<div' .
					' id="wppa-calendar-' . wppa( 'mocc' ) . '"' .
					' class="wppa-box wppa-calendar"' .
					' style="' .
						'font-size:'.$fs.'px;' .
						( $bold ? 'font-weight:bold;' : '' ) .
						'line-height:'.$lh.'px' .
						'"' .
					' >' .
					'<div style="overflow:auto">' .
						wppa_get_calendar_html() .
					'</div>' .
					'<div class="wppa-clear" >' .
					'</div>' .
				'</div>'
			);

	wppa_container( 'close' );
}

// The calendar html
function wppa_get_calendar_html() {
global $wpdb;
global $photos_used;

	// Init
	$result 		= '';
	$secinday 		= 24*60*60;
	$calendar_type 	= wppa( 'calendar' );
	$albums 		= wppa( 'start_album' ) ? wppa_expand_enum( wppa( 'start_album' ) ) : '';
	$alb_clause 	= $albums ? ' AND album IN ( ' . str_replace( '.', ',' , $albums ) . ' ) ' : ' AND album > 0 ';
	$alb_arg 		= wppa( 'start_album' ) ? 'wppa-album=' . wppa_alb_to_enum_children( wppa( 'start_album' ) ) . '&' : '';
	$desc 			= wppa( 'reverse' ) ? ' DESC' : '';
	$from 			= 0;
	$to 			= 0;
	$mocc 			= wppa( 'mocc' );
	$mocc1 			= $mocc + 1;
	$photos_used 	= '*';

	// Get the selected fontsize-lineheight. small = 10-12, medium = 14-17, large = 18-22, xlarge = 22-24
	$fontsize = wppa_opt( 'font_calendar_by' );
	switch ( $fontsize ) {
		case 'xlarge':
			$fs = 22;
			$lh = 26;
			$cw = 60;
			break;
		case 'large':
			$fs = 18;
			$lh = 22;
			$cw = 50;
			break;
		case 'medium':
			$fs = 14;
			$lh = 17;
			$cw = 40;
			break;
		default: // small
			$fs = 10;
			$lh = 12;
			$cw = 30;
		break;
	}

	// Get todays daynumber and range
	$today 	= floor( time() / $secinday );

	switch ( $calendar_type ) {
		case 'exifdtm':
			$photos = $wpdb->get_results( "SELECT id, exifdtm FROM $wpdb->wppa_photos
										   WHERE exifdtm <> ''
										   AND status <> 'pending'
										   AND status <> 'scheduled' " .
										   $alb_clause . "
										   ORDER BY exifdtm " . $desc, ARRAY_A );

			$dates = array();
			foreach ( $photos as $photo ) {
				$date = substr( $photo['exifdtm'], 0, 10 );
				if ( wppa_is_exif_date( $date ) ) {
					if ( isset( $dates[$date] ) ) {
						$dates[$date]++;
					}
					else {
						$dates[$date] = '1';
					}
				}
			}

			$from 	= 0;
			$to 	= count( $dates );
			break;

		case 'timestamp':
		case 'modified':
			$photos = $wpdb->get_results( "SELECT id, " . $calendar_type . "
										   FROM $wpdb->wppa_photos
										   WHERE " . $calendar_type . " > 0
										   AND status <> 'pending'
										   AND status <> 'scheduled' " .
										   $alb_clause . "
										   ORDER BY " . $calendar_type . $desc, ARRAY_A );

			$dates = array();
			foreach ( $photos as $photo ) {
				$date = floor( $photo[$calendar_type] / $secinday );
				if ( isset( $dates[$date] ) ) {
					$dates[$date]++;
				}
				else {
					$dates[$date] = '1';
				}
			}
			$from 	= 0;
			$to 	= count( $dates );
			break;

		default:
			if ( $calendar_type ) {
				wppa_log( 'err', 'Unexpected calendar type: ' . $calendar_type . ' found in wppa_get_calendar_html()' );
			}
	}

	// Display minicovers
	$result .= '
	<div
		style="width:' . ( ( $cw + 2 ) * ( $to - $from ) ) . 'px;position:relative"
		>';

	switch( $calendar_type ) {
		case 'exifdtm':

			$keys = array_keys( $dates );

			for ( $day = $from; $day < $to; $day++ ) {
				$date = date_create_from_format( 'Y:m:d', $keys[$day] );

				if ( is_object( $date ) ) {

					$ajaxurl = wppa_get_ajaxlink('', '1') .
								'wppa-calendar=exifdtm&wppa-caldate=' . $keys[$day] . '&' . $alb_arg . 'wppa-occur=' . $mocc1;

					$onclick = 'jQuery(\'.wppa-minicover-' . $mocc . '\').removeClass(\'wppa-minicover-current\');
								jQuery(this).addClass(\'wppa-minicover-current\');
								wppaDoAjaxRender(' . $mocc1 . ', \'' . $ajaxurl . '\', \'\');';

					$result .= '
					<a
						class="wppa-minicover-' . $mocc . '"
						onclick="' . $onclick . '"
						>
						<div
							id="wppa-minicover-' . $day . '"
							class="wppa-minicover"
							style="border:1px solid gray;
								   float:left;
								   text-align:center;
								   cursor:pointer;
								   width:' . $cw . 'px"
							>' .
							__( $date->format( 'M' ) ) . '<br>' .
							__( $date->format( 'd' ) ) . '<br>' .
							__( $date->format( 'D' ) ) . '<br>' .
							__( $date->format( 'Y' ) ) . '<br>' .
							'(' . $dates[$keys[$day]] . ')' . '
						</div>
					</a>';
				}
			}
			break;

		case 'timestamp':
		case 'modified':
			$keys = array_keys( $dates );

			for ( $day = $from; $day < $to; $day++ ) {

				$date 		= $keys[$day];

				$ajaxurl =  wppa_get_ajaxlink('', '1') .
							 'wppa-calendar=' . $calendar_type . '&wppa-caldate=' . $keys[$day] . '&' . $alb_arg . 'wppa-occur=' . $mocc1;

				$onclick = 'jQuery( \'.wppa-minicover-' . $mocc . '\' ).removeClass( \'wppa-minicover-current\' );
							jQuery(this).addClass(\'wppa-minicover-current\');
							wppaDoAjaxRender(' . $mocc1 . ', \'' . $ajaxurl . '\', \'\');';

				$result .= 	'
				<a
					class="wppa-minicover-' . $mocc . '"
					onclick="' . $onclick . '"
					>
					<div
						id="wppa-minicover-' . $day . '"
						class="wppa-minicover"
						style="border:1px solid gray;
							   float:left;
							   text-align:center;
							   cursor:pointer;
							   width:' . $cw . 'px"
						>' .
						__( date( 'M', $date * $secinday ) ) . '<br>' .
						__( date( 'd', $date * $secinday ) ) . '<br>' .
						__( date( 'D', $date * $secinday ) ) . '<br>' .
						__( date( 'Y', $date * $secinday ) ) . '<br>' .
						'(' . $dates[$keys[$day]] . ')' . '
					</div>
				</a>';
			}
			break;

		default:
			break;
	}

	$result .= 	'</div>';

	return $result;
}

// The real calendar box
function wppa_real_calendar_box() {

	if ( is_feed() ) return;

	// The calendar container
	wppa_container( 'open' );

	$year = wppa_get( 'calendar-year' );
	$month = wppa_get( 'calendar-month' );
	if ( ! $year && ! $month ) {
		$year = wppa( 'year' );
		$month = wppa( 'month' );
	}

	wppa_out( '<div' .
					' id="wppa-calendar-' . wppa( 'mocc' ) . '"' .
					' class="wppa-box wppa-calendar"' .
					' >' .
					'<div style="overflow:visible;margin-bottom:3px">' .
						wppa_get_real_calendar_html( $year, $month ) .
					'</div>' .
					'<div class="wppa-clear" >' .
					'</div>' .
				'</div>' );

	wppa_container( 'close' );
}

// The real calendar html
function wppa_get_real_calendar_html( $year = 0, $month = 0 ) {
global $wpdb;
global $photos_used;

	$photos_used = '*';

	$is_this_month = ( ! $year && ! $month ) || ( $year == wppa_local_date( 'Y', time() ) && $month == wppa_local_date( 'm', time() ) );

	// If no year given, default to current local year
	if ( ! $year ) {
		$year = wppa_local_date( 'Y', time() );
	}

	// If no month given, default to current local month
	if ( ! $month ) {
		$month = wppa_local_date( 'm', time() );
	}

	// Is it the current month?
	$is_this_month = wppa_local_date( 'Y', time() ) == $year && wppa_local_date( 'm', time() ) == $month;

	// Get other init data
	$mocc = wppa( 'mocc' );
	$days_in_month = wppa_local_date( 't', wppa_local_strtotime( $year . '-' . $month . '-01-12' ) );
	$num_of_weeks = ( $days_in_month % 7== 0 ? 0 : 1 ) + intval( $days_in_month / 7 );
    $month_ending_day = wppa_local_date( 'N', wppa_local_strtotime( $year . '-' . $month . '-' . $days_in_month . '-12' ) );
    $month_start_day = wppa_local_date( 'N', wppa_local_strtotime( $year . '-' . $month . '-01-12' ) );
    if ( $month_ending_day < $month_start_day ) {
		$num_of_weeks++;
	}
	$first_day_of_the_week = wppa_local_date( 'N', wppa_local_strtotime( $year . '-' . $month . '-01-12' ) );
	$day_labels = array(__("Mon"),__("Tue"),__("Wed"),__("Thu"),__("Fri"),__("Sat"),__("Sun"));
	$month_labels = array(__("January"),__("February"),__("March"),__("April"),__("May"),__("June"),__("July"),__("August"),__("September"),__("October"),__("November"),__("December"));
	$month_lbls = array(__("Jan"),__("Feb"),__("Mar"),__("Apr"),__("May"),__("Jun"),__("Jul"),__("Aug"),__("Sep"),__("Oct"),__("Nov"),__("Dec"));
	$current_day = 0;
	$pm = $month - 1;
	if ( ! $pm ) {
		$pm = 12;
		$py = $year - 1;
	}
	else {
		$py = $year;
	}
	$nm = $month + 1;
	if ( $nm == 13 ) {
		$nm = 1;
		$ny = $year + 1;
	}
	else {
		$ny = $year;
	}

	// Caching?
	$cache = wppa( 'cache' );

	// The html:
	$w = wppa_get_container_width( 'netto' );	// Width of the week
	$h = $w * wppa_get_thumb_aspect() / 7;		// Height of a day
	$f = $w / 50 + 2;							// Fontsize
	$m = $f / 4;								// Header margin
	$b = $h / 2;								// bottom offset for text

	// Album spec?
	$albums = str_replace( '.', ',', wppa_expand_enum( wppa( 'start_album' ) ) );

	// Buid the html
	$result = '
	<div
		id="wppa-real-calendar-' . $mocc . '"
		class="wppa-real-calendar"
		style="font-size:' . $f . 'px"
		>
		<table
			class="wppa-real-calendar-table"
			style="width:100%;border-bottom:1px solid gray;margin:0">
			<thead class="wppa-real-calendar-head" >
				<tr class="wppa-real-calendar-navi" >';

					// The previous year link
					if ( wppa_is_prehistoric( $year-1, 0 ) ) {
						$result .= '
						<td
							class="wppa-real-calendar-small wppa-real-calendar-inactive wppa-real-calendar-head-td-'.$mocc.'"
							style="margin-top:' . $m . 'px;margin-bottom:' . $m . 'px">' .
							( $year - 1 ) . '
						</td>';
					}
					else {
					$result .= '
						<td class="wppa-real-calendar-small wppa-real-calendar-head-td-'.$mocc.'"
							onclick="wppaDoAjaxRender(' . $mocc . ', \'' . wppa_get_real_calendar_link( $year-1, $month ) . '\');"
							>' .
							( $year - 1 ) . '
						</td>';
					}

					// The month links
					$m = 1;
					while ( $m < 13 ) {
						$f = wppa_is_future( $year, $m );
						$p = wppa_is_prehistoric( $year, $m );
						if ( $f || $p ) {
							$result .= '
							<td class="wppa-real-calendar-small wppa-real-calendar-inactive wppa-real-calendar-head-td-'.$mocc.'"
							>' .
							$month_lbls[$m - 1] . '
							</td>';
						}
						else {
							$result .= '
							<td class="wppa-real-calendar-small wppa-real-calendar-head-td-'.$mocc.'"
							onclick="wppaDoAjaxRender(' . $mocc . ', \'' . wppa_get_real_calendar_link( $year, $m ) . '\');"
							>' .
							$month_lbls[$m - 1] . '
							</td>';
						}
						$m++;
					}

					// The next year link
					if ( wppa_is_future( $year+1, $month ) ) {
						$result .= '
						<td class="wppa-real-calendar-small wppa-real-calendar-inactive wppa-real-calendar-head-td-'.$mocc.'"
							>' .
							( $year + 1 ) . '
						</td>';
					}
					else {
						$result .= '
						<td class="wppa-real-calendar-small wppa-real-calendar-head-td-'.$mocc.'"
							onclick="wppaDoAjaxRender(' . $mocc . ', \'' . wppa_get_real_calendar_link( $year+1, $month ) . '\');"
							>' .
							( $year + 1 ) . '
						</td>';
					}
					$result .= '
				</tr>';

				// The caption
				$result .= '
				<tr class="wppa-real-calendar-caption" >';

					// The previous month link
					if ( wppa_is_prehistoric( $py, $pm ) ) {
						$result .= '
						<td colspan="1"
							class="wppa-real-calendar-navi wppa-real-calendar-inactive wppa-real-calendar-head-td-'.$mocc.'"
							>' .
							ucfirst( $month_lbls[$pm-1] ) . '
						</td>';

					}
					else {
						$result .= '
						<td colspan="1"
							class="wppa-real-calendar-navi wppa-real-calendar-head-td-'.$mocc.'"
							title="' . ucfirst( $month_labels[$pm-1] ) . ' ' . $py . '"
							onclick="wppaDoAjaxRender(' . $mocc . ', \'' . wppa_get_real_calendar_link( $py, $pm ) . '\');"
							>' .
							ucfirst( $month_lbls[$pm-1] ) . '
						</td>';
					}

					// Filler
					$result .= '
					<td></td>';

					// The current month caption
					$result .= '
					<td colspan="9"
						class="wppa-real-calendar-caption wppa-real-calendar-head-td-'.$mocc.'" >' .
						ucfirst( $month_labels[$month - 1] ) . ' - ' . $year . '
					</td>';

					// The back to current month: 'Today' link
					if ( $is_this_month ) {
						$result .= '
						<td colspan="2"
							class="wppa-real-calendar-today wppa-real-calendar-navi wppa-real-calendar-inactive wppa-real-calendar-head-td-'.$mocc.'"
							>' .
							__( "Now", 'wp-photo-album-plus' ) . '
						</td>';
					}
					else {
						$result .= '
						<td colspan="2"
							class="wppa-real-calendar-today wppa-real-calendar-navi wppa-real-calendar-head-td-'.$mocc.'"
							title="' . ucfirst( $month_labels[wppa_local_date( 'm', time() )-1] ) . ' - ' . wppa_local_date( 'Y', time() ) . '"
							onclick="wppaDoAjaxRender(' . $mocc . ', \'' . wppa_get_real_calendar_link( 0, 0 ) . '\');"
							>' .
							__( "Now", 'wp-photo-album-plus' ) . '
						</td>';
					}

					// The next month link
					if ( wppa_is_future( ( $nm == 1 ? $year + 1 : $year ), $nm ) ) {
						$result .= '
						<td colspan="1"
							class="wppa-real-calendar-navi wppa-real-calendar-inactive wppa-real-calendar-head-td-'.$mocc.'"
							>' .
							ucfirst( $month_lbls[$nm-1] ) . '
						</td>';
					}
					else {
						$result .= '
						<td colspan="1"
							class="wppa-real-calendar-navi wppa-real-calendar-head-td-'.$mocc.'"
							title="' . ucfirst( $month_labels[$nm-1] ) . ' ' . $ny . '"
							onclick="wppaDoAjaxRender(' . $mocc . ', \'' . wppa_get_real_calendar_link( $ny, $nm ) . '\');"
							>' .
							ucfirst( $month_lbls[$nm-1] ) . '
						</td>';
					}

					// Close the caption
					$result .= '
				</tr>';

				// The 7 day labels
				$result .= '
				<tr class="wppa-real-calendar-days" >';
					foreach( $day_labels as $day_label ) {
						$result .= '
						<td colspan="2"
							class="wppa-real-calendar-day-label wppa-real-calendar-head-td-'.$mocc.'" >
							' . ucfirst( $day_label ) . '
						</td>';
					}
					$result .= '
				</tr>
			</thead>
			<tbody class="wppa-real-calendar-body" >';

				// Create weeks in a month
				for( $i = 0; $i < $num_of_weeks; $i++ ) {

					$result .= '
					<tr class="wppa-real-calendar-week" >';

					// Create days in a week
					for ( $j = 1; $j <= 7; $j++ ) {
						$cell_number = $i * 7 + $j;

						// First day?
						if ( $cell_number == $first_day_of_the_week ) {
							$current_day = 1;
						}

						// Existing day?
						if ( $current_day == 0 || $current_day > $days_in_month ) {

							// Dummy day
							$result .= '
							<td colspan="2"
								class="wppa-real-calendar-dummy"
								style="width:14%">
							</td>';
						}
						else {

							// Create the days html
							// See if tere are uploads this day
							if ( wppa( 'calendar' ) == 'realexifdtm' ) {
								$like 	= sprintf( '%d:%02d:%02d', $year, $month, $current_day );
								$query  = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														   WHERE exifdtm LIKE %s", $wpdb->esc_like( $like ) . '%' );
							}
							else {
								$from 	= wppa_local_strtotime( $year . '-' . $month . '-' . $current_day );
								$to 	= $from + 24 * 60 * 60;
								if ( wppa( 'calendar' ) == 'realmodified' ) {
									$query 	= $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
															   WHERE modified >= %d
															   AND modified < %d", $from, $to );
								}
								else {
									$query 	= $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
															   WHERE timestamp >= %d
															   AND timestamp < %d", $from, $to );
								}
							}
							if ( $albums ) {
								$query .= " AND album IN ($albums)";
							}
							else {
								$query .= " AND album > 0";
							}
							if ( ! current_user_can( 'wppa_moderate' ) ) {
								if ( is_user_logged_in() ) {
									$query .= " AND status <> 'pending'";
								}
								else {
									$query .= " AND status NOT IN ('pending','private')";
								}
							}
							$order = wppa_is_int( wppa( 'start_album' ) ) ? wppa_get_photo_order( wppa( 'start_album' ) ) : wppa_get_photo_order( '0' );
							$query .= " " . $order;
							$thumbs = $wpdb->get_results( $query, ARRAY_A );

							if ( wppa_switch( 'extended_duplicate_remove' ) ) {
								wppa_extended_duplicate_remove( $thumbs );
							}

							// There are count($thumbs) items this day
							if ( count( $thumbs ) ) {

								$thisday 	= wppa_local_date( wppa_get_option( 'date_format' ), wppa_local_strtotime( $year . '-' . $month . '-' . $current_day . '-12' ) );
								$id 		= $thumbs['0']['id'];
								wppa_get_thumb_url( $id ); // Force creation of thumb in case its not there to find the size

								$thumbratio = wppa_get_photo_item( $id, 'thumbx' ) ? wppa_get_photo_item( $id, 'thumby' ) / wppa_get_photo_item( $id, 'thumbx' ) : 1;
								$cellratio 	= wppa_get_thumb_aspect();
								$tmp 		= $thumbratio / $cellratio;
								$fill 		= 0.95 < $tmp && $tmp < 1.05;

								$thisb 		= $b;
								if ( ! $fill && $thumbratio < $cellratio ) { // Thumb more landscape than cell
									$thumbh = ( $w / 7 ) * $thumbratio;
									$thisb = $b - ( $h - $thumbh ) / 2;
								}

								switch( wppa_opt( 'real_calendar_linktype' ) ) {

									case 'slide':

										// Start slideshow case
										$imgtitle = sprintf( __( 'Click for slideshow to see %d items of %s', 'wp-photo-album-plus' ), count( $thumbs ), $thisday );
										$secsinday = 24 * 60 * 60;
										if ( wppa( 'calendar' ) == 'realexifdtm' ) {
											$day = sprintf( '%4d:%02d:%02d', $year, $month, $current_day );
										}
										else {
											$day = floor( wppa_local_strtotime( $year . '-' . $month . '-' . $current_day . '-12' ) / $secsinday );
										}
										$ajaxurl = wppa_encrypt_url(
												wppa_get_ajaxlink( '', '1' ) .
												'wppa-calendar='.substr( wppa( 'calendar' ), '4' ) . '&' .
												'wppa-caldate=' . $day . '&' .
												( $albums ? 'wppa-albums=' . $albums . '&' : '' ) .
												'wppa-vt=1&' .
												'wppa-slide=1&' .
												'wppa-occur=' . ( $mocc + '1' )
												);
										$id = $thumbs['0']['id'];

										// The link
										$the_a_tag = '
										<a
											data-id="' . wppa_encrypt_photo( $id ) . '"
											style="color:white;cursor:pointer"
											onclick="wppaDoAjaxRender(' . ( $mocc + 1 ) . ', \'' . $ajaxurl . '\' );"
											>';

										// The cell content
										$cell_content = $the_a_tag;

											// the display image
											$imgattr = $fill ? 'width:100%;height:100%;cursor:pointer;' : 'max-width:100%;max-height:100%;cursor:pointer;';
											$id = $thumbs['0']['id'];
											if ( wppa_is_video( $id ) ) {
												$cell_content .=
													'<video preload="metadata"
														class="thumb wppa-img" id="i-' . $id . '-' . $mocc . ' wppa-realcalimg wppa-realcalimg-' . $mocc . '"
														data-day="' . $current_day . '"
														title="' . esc_attr( $imgtitle ) . '"
														style="' . $imgattr . '"
														>' .
														wppa_get_video_body( $id ) .
													'</video>'
												;
											}
											else {
												$cell_content .=
													'<img
														class="thumb wppa-img wppa-realcalimg wppa-realcalimg-' . $mocc . '"
														data-day="' . $current_day . '"
														id="i-' . $id . '-' . $mocc . '"
														title="' . esc_attr( $imgtitle ) . '"
														src="' . wppa_get_thumb_url( $id ) . '"
														style="' . $imgattr . '" ' .
														wppa_get_imgalt( $id ) . '
													/>';
											}

										$cell_content .= '
										</a>';

										// End slideshow case
										break;

									// case 'lightbox':
									default:

										// Start lightbox case; this is the default
										$imgtitle = sprintf( __( 'Zoom in to see %d items of %s', 'wp-photo-album-plus' ), count( $thumbs ), $thisday );
										$cell_content = '';
										foreach ( $thumbs as $thumb ) {
											$id = $thumb['id'];
											$title = wppa_get_lbtitle( 'cover', $id );
											if ( wppa_is_video( $id ) ) {
												$siz['0'] = wppa_get_videox( $id );
												$siz['1'] = wppa_get_videoy( $id );
											}
											else {
												$siz['0'] = wppa_get_photox( $id );
												$siz['1'] = wppa_get_photoy( $id );
											}
											$link 		= wppa_get_photo_url( $id, true, '', $siz['0'], $siz['1'] );
											$is_video 	= wppa_is_video( $id );
											$has_audio 	= wppa_has_audio( $id );
											$is_pdf 	= wppa_is_pdf( $id );

											// Open the anchor tag for lightbox
											$cell_content .= '
											<a
												data-id="' . wppa_encrypt_photo( $id ) . '"
												href="' . $link . '"
												style="border:0;color:transparent;"' .
												( $is_video ? ' data-videohtml="' . esc_attr( wppa_get_video_body( $id ) ) . '"
												data-videonatwidth="' . wppa_get_videox( $id ) . '"
												data-videonatheight="' . wppa_get_videoy( $id ) . '"' : '' ) .
												( $has_audio ? ' data-audiohtml="' . esc_attr( wppa_get_audio_body( $id ) ) . '"' : '' ) .
												( $is_pdf ? ' data-pdfhtml="' . esc_attr( wppa_get_pdf_html( $id ) ) .'"' : '' ) .
												' data-rel="wppa[alw-' . wppa( 'mocc' ) . '-' . $year . '-' . $month . '-'. $cell_number . ']"' .
												' ' . 'data-lbtitle' . '="' . $title . '"' .
												wppa_get_lb_panorama_full_html( $id ) . '
												data-alt="' . esc_attr( wppa_get_imgalt( $id, true ) ) . '"
												style="cursor:' . wppa_wait() . ';"
												onclick="return false;"
												>';

												// the display image
												$imgattr = $fill ? 'width:100%;height:100%;' : 'max-width:100%;max-height:100%;';
												if ( $id == $thumbs['0']['id'] ) {

													$the_a_tag = '
													<a
														style="cursor:url(\'' . wppa_get_imgdir( wppa_opt( 'magnifier' ) ) . '\'),auto;color:white;text-decoration:none;"
														onclick="jQuery(\'#i-' . $thumb['id'] . '-' . $mocc . '\').trigger(\'click\');"
														>';


													if ( wppa_is_video( $thumb['id'] ) ) {
														$cell_content .=
															'<video preload="metadata"
																class="thumb wppa-img" id="i-' . $thumb['id'] . '-' . $mocc . ' wppa-realcalimg wppa-realcalimg-' . $mocc . '"
																data-day="' . $current_day . '"
																id="i-' . $thumb['id'] . '-' . $mocc . '"
																title="' . esc_attr( $imgtitle ) . '"
																style="' . $imgattr . '"
																>' .
																wppa_get_video_body( $thumb['id'] ) .
															'</video>'
														;
													}
													else {
														$cell_content .=
															'<img
																class="thumb wppa-img wppa-realcalimg wppa-img wppa-realcalimg-' . $mocc . '"
																data-day="' . $current_day . '"
																id="i-' . $thumb['id'] . '-' . $mocc . '"
																title="' . esc_attr( $imgtitle ) . '"
																src="' . wppa_get_thumb_url( $id ) . '"
																style="' . $imgattr . '" ' .
																wppa_get_imgalt( $thumb['id'] ) . '
															/>';
													}
												}

												// Close the lightbox anchor tag
												$cell_content .=
											'</a>';
										}
										// End lightbox case
										break;
								}

								$cell_content .=
								'<div
									class="wppa-real-calendar-day-content-' . $current_day . '-' . $mocc . '"
									style="position:relative;color:white;width:100%;bottom:' . round( $thisb ) . 'px;"
									title="' . esc_attr( $imgtitle ) . '"
									>' .
									$the_a_tag .
										$current_day . '
									</a>
								</div>';

							}
							else {
								$cell_content = '<span style="cursor:default">' . $current_day . '</span>';
							}
							$result .= '
							<td colspan="2"
								id="li-' . $current_day . '"
								class="wppa-real-calendar-day' .
									( wppa_is_today( $year, $month, $current_day ) ? ' wppa-current-day' : '' ) .
									' wppa-real-calendar-day-' . $mocc . '"
									style="height:' . $h . 'px;width:14%;"
								>
								' . $cell_content . '
							</td>';
							$current_day++;
						}
					}
					$result .= '
					</tr>';
				}
			$result .= '
			</tbody>
		</table>
    </div>';

	return $result;
}

// Make an Ajax link for the real calendar, calling for a certain year and month.
// Type: 'realexifdtm', 'realtimestamp' or 'realmodified'.
// Month: 1..12, 0 will be 12 previous year, 13 will be 1 next year, etc.
function wppa_get_real_calendar_link( $year, $month ) {

	if ( $year && $month ) {
		while ( $month > 12 ) {
			$year++;
			$month -= 12;
		}
		while ( $month < 1 ) {
			$year--;
			$month += 12;
		}
	}
	$album = str_replace( ',', '.', wppa_expand_enum( wppa( 'start_album' ) ) );
	$result = wppa_encrypt_url( wppa_get_ajaxlink() .
								'wppa-calendar=' . wppa( 'calendar' ) . '&amp;' .
								'wppa-calendar-year=' . $year . '&amp;' .
								'wppa-calendar-month=' . $month . '&amp;' .
								( $album ? 'wppa-album=' . $album . '&amp;' : '' ) .
								( wppa( 'cache' ) ? 'wppa-cache=1&amp;' : '' ) .
								'wppa-occur=' . wppa( 'mocc' )
								);

	return $result;
}

// Is year / month in the future?
function wppa_is_future( $year, $month ) {

	if ( $year > wppa_local_date( 'Y', time() ) ) {
		return true;
	}
	if ( $year < wppa_local_date( 'Y', time() ) ) {
		return false;
	}
	if ( $month > wppa_local_date( 'm', time() ) ) {
		return true;
	}
	return false;
}

// Is year / month current?
function wppa_is_current( $year, $month ) {

	if ( $year == wppa_local_date( 'Y', time() ) &&
		 $month == wppa_local_date( 'm', time() ) ) {
			 return true;
		 }

	return false;
}

//
function wppa_is_today( $year, $month, $day ) {
	return wppa_is_current( $year, $month ) && $day == wppa_local_date( 'd', time() );
}

// Is Year / Month prehistoric?
function wppa_is_prehistoric( $year, $month ) {
global $wpdb;
static $cache;

	$albums = str_replace( '.', ',', wppa_expand_enum( wppa( 'start_album' ) ) );
	$y = 0;
	$m = 0;

	// Find year and month of the first item
	switch ( wppa( 'calendar' ) ) {
		case 'realexifdtm':

			if ( isset( $cache[wppa('mocc')][wppa('calendar')] ) ) {
				$first = $cache[wppa('mocc')][wppa('calendar')];
			}
			else {
				if ( $albums ) {
					$first = $wpdb->get_var( "SELECT exifdtm FROM $wpdb->wppa_photos
											  WHERE exifdtm <> ''
											  AND album IN ($albums)
											  ORDER BY exifdtm LIMIT 1" );
				}
				else {
					$first = $wpdb->get_var( "SELECT exifdtm FROM $wpdb->wppa_photos
											  WHERE exifdtm <> ''
											  AND album > 0
											  ORDER BY exifdtm LIMIT 1" );
				}
				$cache[wppa('mocc')][wppa('calendar')] = $first;
			}

			if ( $first ) {
				$t = explode( ':', $first );
				$y = $t[0];
				$m = strval( intval( $t[1] ) );

			}
			break;

		case 'realtimestamp':

			if ( isset( $cache[wppa('mocc')][wppa('calendar')] ) ) {
				$first = $cache[wppa('mocc')][wppa('calendar')];
			}
			else {
				if ( $albums ) {
					$first = $wpdb->get_var( "SELECT timestamp FROM $wpdb->wppa_photos
											  WHERE album IN ($albums)
											  ORDER BY timestamp LIMIT 1" );
				}
				else {
					$first = $wpdb->get_var( "SELECT timestamp FROM $wpdb->wppa_photos
											  WHERE album > 0
											  ORDER BY timestamp LIMIT 1" );
				}
				$cache[wppa('mocc')][wppa('calendar')] = $first;
			}
			if ( $first ) {
				$y = wppa_local_date( 'Y', $first );
				$m = wppa_local_date( 'n', $first );
			}
			break;

		case 'realmodified';

			if ( isset( $cache[wppa('mocc')][wppa('calendar')] ) ) {
				$first = $cache[wppa('mocc')][wppa('calendar')];
			}
			else {
				if ( $albums ) {
					$first = $wpdb->get_var( "SELECT modified FROM $wpdb->wppa_photos
											  WHERE album IN ($albums)
											  ORDER BY modified LIMIT 1" );
				}
				else {
					$first = $wpdb->get_var( "SELECT modified FROM $wpdb->wppa_photos
											  WHERE album > 0
											  ORDER BY modified LIMIT 1" );
				}
				$cache[wppa('mocc')][wppa('calendar')] = $first;
			}
			if ( $first ) {
				$y = wppa_local_date( 'Y', $first );
				$m = wppa_local_date( 'n', $first );
			}
			break;

		default:
			return false;
			break;
	}

	// Do the actual compare
	if ( $y > $year ) {
		$result = true;
	}
	elseif ( $y < $year ) {
		$result = false;
	}
	elseif ( ! $month ) {
		$result = false;
	}
	elseif ( $m > $month ) {
		$result = true;
	}
	else {
		$result = false;
	}

	return $result;
}

// Stereo settings box
function wppa_stereo_box() {

	// Init
	$result = '';

	// No search box on feeds
	if ( is_feed() ) return;

	// Open container
	wppa_container( 'open' );

	// Open wrapper
	$result .= "\n";
	$result .= '<div' .
					' id="wppa-stereo-' . wppa( 'mocc' ) . '"' .
					' class="wppa-box wppa-stereo"' .
					' >';

	// The search html
	$result .= wppa_get_stereo_html();

	// Clear both
	$result .= '<div class="wppa-clear" ></div>';

	// Close wrapper
	$result .= '</div>';

	// Output
	wppa_out( $result );

	// Close container
	wppa_container( 'close' );
}

// Stereo settings html
function wppa_get_stereo_html() {
global $wppa_supported_stereo_types;
global $wppa_supported_stereo_glasses;
global $wppa_supported_stereo_type_names;
global $wppa_supported_stereo_glass_names;

	$result = 	'<form' .
					' id="wppa-stereo-form-' . wppa( 'mocc' ) . '"' .
					' >' .
					'<select' .
						' id="wppa-stereo-type-' . wppa( 'mocc' ) . '"' .
						' name="wppa-stereo-type"' .
						' onchange="wppaStereoTypeChange( this.value );"' .
						' >';
						foreach( array_keys( $wppa_supported_stereo_types ) as $key ) {
							$result .=
							'<option' .
								' value="' . $wppa_supported_stereo_types[$key] . '"' .
								( wppa_get_cookie( "stereotype" ) == $wppa_supported_stereo_types[$key] ? ' selected' : '' ) .
								' >' .
								$wppa_supported_stereo_type_names[$key] .
							'</option>';
						}
	$result .=		'</select>';

	$result .=		'<select' .
						' id="wppa-stereo-glass-' . wppa( 'mocc' ) . '"' .
						' name="wppa-stereo-glass"' .
						' onchange="wppaStereoGlassChange( this.value );"' .
						' >';
						foreach( array_keys( $wppa_supported_stereo_glasses ) as $key ) {
							$result .=
							'<option' .
								' value="' . $wppa_supported_stereo_glasses[$key] . '"' .
								( wppa_get_cookie( "stereoglass" ) == $wppa_supported_stereo_glasses[$key] ? ' selected' : '' ) .
								' >' .
								$wppa_supported_stereo_glass_names[$key] .
							'</option>';
						}
	$result .=		'</select>';

	$result .= 		'<input' .
						' type="button"' .
						' onclick="document.location.reload(true)"' .
						' value="' . __( 'Refresh', 'wp-photo-album-plus' ) . '"' .
						' />';

	$result .=	'</form>';

	return $result;
}

function wppa_is_exif_date( $date ) {

	if ( strlen( $date ) != '10' ) return false;

	for ( $i=0; $i<10; $i++ ) {
		$d = substr( $date, $i, '1' );
		switch ( $i ) {
			case 4:
			case 7:
				if ( $d != ':' ) return false;
				break;
			default:
				if ( ! in_array( $d, array( '0','1','2','3','4','5','6','7','8','9' ) ) ) return false;
		}
	}

	$t = explode( ':', $date );
	if ( $t['0'] < '1970' ) return false;
	if ( $t['0'] > date( 'Y' ) ) return false;
	if ( $t['1'] < '1' ) return false;
	if ( $t['1'] > '12' ) return false;
	if ( $t['2'] < '1' ) return false;
	if ( $t['2'] > '31' ) return false;

	return true;
}

// The shortcode is hidden behind an Ajax activating button
// Currently implemented for:
// type="slide"
function wppa_button_box() {
global $wppa_lang;

	// No button box on feeds
	if ( is_feed() ) return;

	// Open container
	wppa_container( 'open' );

	// Init
	$mocc = wppa( 'mocc' );
	$result = '';

	// The standard Ajax link
	$al = ( wppa_switch( 'ajax_home' ) ? home_url() : site_url() ) . '/wppaajax/?action=wppa&wppa-action=render';
	$al .= '&wppa-size=' . wppa_get_container_width();
	$al .= '&wppa-occur=' . $mocc;
	if ( wppa_get( 'p' ) ) {
		$al .= '&p=' . wppa_get( 'p' );
	}
	if ( wppa_get( 'page_id' ) ) {
		$al .= '&page_id=' . wppa_get( 'page_id' );
	}
	$al .= '&wppa-fromp=' . wppa_get_the_ID();

	if ( $wppa_lang ) {	// If lang in querystring: keep it
		if ( strpos( $al, 'lang=' ) === false ) { 	// Not yet
			$al .= '&lang=' . $wppa_lang;
		}
	}

	// The shortcode type specific args
	if ( wppa( 'is_slide' ) ) {
		$al .= '&wppa-slide&wppa-album=' . wppa( 'start_album' );
		if ( wppa( 'start_photo' ) ) {
			$al .= '&wppa-photo=' . wppa( 'start_photo' );
		}
	}


	// The container content
	$result .=
		'<input' .
			' id="wppa-button-initial-' . $mocc . '"' .
			' type="button"' .
			' value="' . wppa( 'is_button' ) . '"' .
			' onclick="wppaDoAjaxRender( ' . $mocc . ', \'' . $al . '\' )"' .
		' />';

	// Output
	wppa_out( $result );

	// Close container
	wppa_container( 'close' );

	// The Hide and show buttons
	$result =
		'<input' .
			' id="wppa-button-show-' . $mocc . '"' .
			' type="button"' .
			' value="' . wppa( 'is_button' ) . '"' .
			' onclick="jQuery( \'#wppa-container-' . $mocc . '\' ).show();' .
					  'jQuery( \'#wppa-button-hide-' . $mocc . '\' ).show();' .
					  'jQuery( this ).hide();' .
					  '"' .
			' style="display:none;"' .
		' />' .
		'<input' .
			' id="wppa-button-hide-' . $mocc . '"' .
			' type="button"' .
			' value="' . esc_attr( __( 'Hide', 'wp-photo-album-plus' ) ) . '"' .
			' onclick="jQuery( \'#wppa-container-' . $mocc . '\' ).hide();' .
					  'jQuery( \'#wppa-button-show-' . $mocc . '\' ).show();' .
					  'jQuery( this ).hide();' .
					  '"' .
			' style="display:none;"' .
		' />';
	wppa_out( $result );
}

// Grid of photos display
function wppa_grid_box() {

	// Open container
	wppa_container( 'open' );

	// Init
	$result 	= '';
	$mocc 		= wppa( 'mocc' );
	$maxh 		= wppa_opt( 'area_size' );
	$nice 		= wppa_is_nice();
	$overflow 	= 'visible';
	if ( $maxh ) {
		if ( $nice ) $overflow = 'hidden';
		else $overflow = 'auto';
	}

	// Open grid box
	if ( is_feed() ) {
		$result .= 	'
		<div
			id="wppa-thumb-area-' . $mocc . '"
			class="wppa-box wppa-thumb-area"
			>';
	}
	else {
		$result .= 	'
		<div
			id="wppa-thumb-area-' . $mocc . '"
			class="wppa-box wppa-contest wppa-thumb-area wppa-thumb-area-' . $mocc . '"
			style="' . ( $maxh > '1' ? 'max-height:' . $maxh . 'px;' : '' ) . '
					overflow:' . $overflow . ';padding-left:0;"
			onscroll="wppaMakeLazyVisible();"
			>';
	}

	// Use nicescroller?
	if ( $nice ) {
		$result .= 	'<div class="wppa-nicewrap" >';
	}

	// Find the photos to display
	$photos = wppa_get_photos();

	// Get the html
	if ( is_array( $photos ) && count( $photos ) ) {
		$result .= wppa_get_grid_box_html( $photos );
	}

	// After grid
	$result .= '<div class="wppa-clear" ></div>';

	// Nicescroller
	if ( $nice ) {
		wppa_js( '
			jQuery(document).ready(function(){
				if ( jQuery().niceScroll )
				jQuery(".wppa-thumb-area").niceScroll(".wppa-nicewrap",{' . wppa_opt( 'nicescroll_opts' ) . '});
			});' );
		$result .= '</div>'; 	// close .wppa-nicewrap div
	}

	// Close the box
	$result .= '</div>';

	// Output result
	wppa_out( $result );

	// Close container
	wppa_container( 'close' );
}
function wppa_get_grid_box_html( $photos ) {

	// Init
	$cols = wppa( 'gridcols' );
	$size = 100 / $cols . '%';
	$mocc = wppa( 'mocc' );
	$result = '';

	// Open table
	$result .= '
	<table class="wppa-grid wppa-grid-' . $mocc . '" >';

	// Define equally spaced columns
	$result .= '
	<colgroup>';
		$i = 0;
		while( $i < $cols ) {
			$result .= '
				<col style="width:' . $size . '" > ';
			$i++;
		}
	$result .= '
	</colgroup>';

	$style = '';
	$i = 0;
	foreach( $photos as $item ) {

		// The id
		$id = $item['id'];

		// Open row
		if ( $i == 0 ) {
			$result .= '<tr>';
		}

		// The item
		$result .= '
		<td>' .
			wppa_get_grid_image_html( $id ) . '
		</td>';

		// Test for end of row
		$i++;
		if ( $i == $cols ) {
			$result .= '</tr>';
			$i = 0;
		}
	}

	// Fill up last row optionally
	if ( $i != 0 ) {
		while( $i != $cols ) {
			$result .= '<td></td>';
			$i++;
		}
		$result .= '
		</tr>';
	}

	// Close table
	$result .= '
	</table>';

	return $result;
}

function wppa_get_grid_image_html( $id ) {

	// Get photo data
	$photo = wppa_cache_photo( $id );

	// Get link
	$link = wppa_get_imglnk_a( 'grid', $id );

	// Encrypted photo id
	$xid = wppa_encrypt_photo( $id );

	// Get occurrance
	$mocc = wppa( 'mocc' );

	// Find image attributes
	$imgsrc 	= wppa_get_photo_path( $id );
	$cursor 	= 'pointer';
	$is_video 	= wppa_is_video( $id );
	$has_audio 	= wppa_has_audio( $id );
	$is_pdf 	= wppa_is_pdf( $id );
	$imgurl 	= wppa_get_photo_url( $id );
	$imgalt 	= wppa_get_imgalt( $id );
	$imgstyle 	= 'max-width:100%;';
	$title 		= '';
	$target 	= '';

	$result = '';

	// Link?
	if ( $link ) {

		$title 		= $link['title']; //wppa_get_photo_name( $id );
		$target 	= $link['target'];

		// Is link an url?
		if ( $link['is_url'] ) {

			// The a img
			if ( $link['ajax_url'] ) {
				$result .= '<a style="position:static;" onclick="wppaDoAjaxRender('.$mocc.', \''.$link['ajax_url'].'\', \''.$link['url'].'\');" class="grid-img" id="x-'.$xid.'-'.$mocc.'" >';
			}
			else {
				$result .= '<a style="position:static;" href="'.$link['url'].'" target="'.$link['target'].'" class="grid-img" id="x-'.$xid.'-'.$mocc.'" >';
			}
			if ( $is_video ) {
				$result .= wppa_get_video_html( array(
						'id'			=> $id,
						'controls' 		=> wppa_switch( 'grid_video' ),
						'margin_top' 	=> '0',
						'margin_bottom' => '0',
						'tagid' 		=> 'i-'.$xid.'-'.$mocc,
						'cursor' 		=> 'cursor:pointer;',
						'title' 		=> $title,
						'preload' 		=> 'metadata',
						'onclick' 		=> '',
						'lb' 			=> false,
						'class' 		=> '',
						'style' 		=> $imgstyle
						));
			}
			else {
				$result .= 	'
				<img
					id="i-' . $xid . '-' . $mocc . '"' .
					( wppa_lazy() ? ' data-' : ' ' ) . 'src="' . $imgurl . '" ' .
					$imgalt .
					( $title ? ' title="' . $title . '"' : '' ) . '
					style="' . $imgstyle . ' cursor:pointer"
				/>';
			}

			// Close the img non ajax
			$result .= '</a>';
		}

		// Link is not an url. link is lightbox ?
		elseif ( $link['is_lightbox'] ) {

			$title 		= wppa_get_lbtitle( 'grid', $id );

			// The a img
			$result .= '<a href="'.$link['url'].'" target="'.$link['target'] . '"' .
						' data-id="' . wppa_encrypt_photo( $id ) . '"' .
						( $is_video ? ' data-videohtml="' . esc_attr( wppa_get_video_body( $id ) ) . '"' .
						' data-videonatwidth="'.wppa_get_videox( $id ) . '"' .
						' data-videonatheight="'.wppa_get_videoy( $id ) . '"' : '' ) .
						( $has_audio ? ' data-audiohtml="' . esc_attr( wppa_get_audio_body( $id ) ) . '"' : '' ) .
						( $is_pdf ? ' data-pdfhtml="' . esc_attr( wppa_get_pdf_html( $id ) ) .'"' : '' ) .
						' data-rel="wppa[occ'.$mocc.']"' .
						' ' . 'data-lbtitle' . '="'.$title.'" ' .
						wppa_get_lb_panorama_full_html( $id ) .
						' class="grid-img" id="x-'.$xid.'-'.$mocc.'"' .
						' data-alt="' . esc_attr( wppa_get_imgalt( $id, true ) ) . '"' .
						' onclick="return false;"' .
						' style="cursor:' . wppa_wait() . ';"' .
						' >';
			if ( $is_video ) {
				$result .= wppa_get_video_html( array(
						'id'			=> $id,
						'controls' 		=> false,
						'margin_top' 	=> '0',
						'margin_bottom' => '0',
						'tagid' 		=> 'i-'.$xid.'-'.$mocc,
						'cursor' 		=> $cursor,
						'title' 		=> wppa_zoom_in( $id ),
						'preload' 		=> 'metadata',
						'onclick' 		=> '',
						'lb' 			=> false,
						'class' 		=> '',
						'style' 		=> $imgstyle
						));
			}
			else {
				$title = wppa_zoom_in( $id );
				$result .= 	'
				<img
					id="i-' . $xid . '-' . $mocc . '"' .
					( wppa_lazy() ? ' data-' : ' ' ) . 'src="' . $imgurl . '" ' .
					$imgalt . '
					title="' . esc_attr( $title ) . '"
					style="' . $imgstyle . $cursor . '"
				/>';
			}

			// Close the a img
			$result .= '</a>';
		}

		// Unsupported
		else {
			wppa_log( 'err', 'Unsupported linktype in wppa_get_grid_image_html() ' . var_export( $link, true ) );
		}
	}

	// No link
	else {	// no link

		if ( $is_video ) {
			$result .= wppa_get_video_html( array(
					'id'			=> $id,
					'controls' 		=> wppa_switch( 'grid_video' ),
					'margin_top' 	=> '0',
					'margin_bottom' => '0',
					'tagid' 		=> 'i-'.$id.'-'.$mocc,
					'cursor' 		=> '',
					'title' 		=> $title,
					'preload' 		=> 'metadata',
					'onclick' 		=> '',
					'lb' 			=> false,
					'class' 		=> '',
					'style' 		=> $imgstyle
					));
		}
		else {
			$result .= 	'
			<img
				id="i-' . $xid . '-' . $mocc . '"' .
				( wppa_lazy() ? ' data-' : ' ' ) . 'src="' . $imgurl . '" ' .
				$imgalt . '
				style="' . $imgstyle . '"
				title="' . esc_attr( $title ) . '"
			/>';
		}
	}

	return $result;

}

// Audo only display
function wppa_audio_only_box() {
global $wppa;

	$mocc 	= wppa( 'mocc' );
	$item 	= wppa( 'audio_item' );
	$album 	= wppa( 'audio_album' );
	$poster = wppa( 'audio_poster' );

	wppa_virt_album_to_runparms( $album );
	wppa_virt_photo_to_runparms( $item );
	if ( substr( $poster, 0, 1 ) == '$' ) {
		$poster = wppa_get_photo_id_by_name( substr( $poster, 1 ) );
		wppa( 'audio_poster', $poster );
	}

	// An item?
	if ( ! $album ) {
		wppa_container( 'open' );
			wppa_audio_only_container( 'open' );
				wppa_out( '<div id="audioonly-' . wppa( 'mocc' ) . '" style="margin-top:4px;">' );
					_wppa_audio_only( $item );
				wppa_out( '</div>' );
			wppa_audio_only_container( 'close' );
		wppa_container( 'close' );
		return;
	}

	// An album?
	else {
		wppa_container( 'open' );
			wppa_audio_only_container( 'open' );
				wppa_out( '<div id="audioonly-' . wppa( 'mocc' ) . '" style="margin-top:4px;">' );
					$items = wppa_get_photos();
					if ( is_array( $items ) ) foreach( $items as $item ) {
						if ( wppa_has_audio( $item['id'] ) ) {
							_wppa_audio_only( $item['id'] );
						}
					}
					wppa_out( '<br>' );
				wppa_out( '</div>' );
			wppa_audio_only_container( 'close' );
		wppa_container( 'close' );
		return;
	}
}

// Do a single item
function _wppa_audio_only( $id ) {
static $seqno;

	if ( ! $seqno ) $seqno = 1;
	else $seqno++;

	// Does item exist?
	$item = wppa_cache_photo( $id );
	if ( ! $item ) {
		wppa_out( '<div>' . __( 'Item does not exist', 'wp-photo-album-plus' ) . '</div>' );
	}

	// Has this item audio?
	$avail_exts = wppa_has_audio( $id );
	if ( ! $avail_exts ) {
		wppa_out( '<div>' . __( 'No audio available', 'wp-photo-album-plus' ) . '</div>' );
	}

	// Yes it has audio
	else {

		wppa( 'no_ver', true ); // prevent adding version to urls

		$mocc 	= wppa( 'mocc' );

		$url 	= wppa_strip_ext( wppa_get_photo_url( $id, false ) );
		$urls 	= '';
		$exts 	= ['mp3', 'wav', 'ogg'];
		foreach( $avail_exts as $ext ) {
			$urls .= '\'' . $url . '.' . $ext.'\',';
		}
		$urls = trim( $urls, ',' );
		$result = '
		<div style="width:100%;overflow:hidden;white-space: nowrap;">
			<a
				id="audiolabel-'.$mocc.'-'.$seqno.'"
				class="wppa-audiolabel wppa-audiolabel-'.$mocc.'"
				style="cursor:pointer;"
				onclick="wppaDoAudioOnly(\'wppa-audioonly-'.$mocc.'\',['.$urls.'],'.$seqno.','.$mocc.');"
				onmouseover="wppaShowAudioDesc('.$seqno.','.$mocc.');"
				onmouseout="wppaHideAudioDesc('.$seqno.','.$mocc.');"
				>' .
				wppa_get_photo_name( $id ) . '
			</a>';
			if ( wppa_switch( 'audioonly_duration' ) ) {
				$result .= '
				<span style="float:right;">';
					$duration = wppa_get_photo_item( $id, 'duration' );
					if ( $duration ) {
						$m = floor( $duration / 60 );
						$s = $duration % 60;
						if ( ! $s ) $s = '00';
						elseif ( $s < '10' ) $s = '0'.$s;
						$result .= $m."' ".$s.'"';
					}

				$result .= '
				</span>';
			}
			$desc = wppa_get_photo_desc( $id );
			if ( wppa_switch( 'audioonly_itemdesc' ) && $desc ) { 	// show description
				$result .= '
				<div
					id="audiodesc-'.$mocc.'-'.$seqno.'"
					class="wppa-audiodesc wppa-audiodesc-'.$mocc.'"
					style="font-size:0.8em;font-style:italic;display:none;"
					>' .
					$desc . '
				</div>';
			}
			$result .= '
		</div>';

		wppa_out( $result );

	}
}

// Open / close the box containing the audio only items
function wppa_audio_only_container( $action ) {

	$nice 		= wppa_is_nice();
	$maxh 		= wppa_opt( 'area_size_audio' );
	$overflow 	= 'auto';
	$mocc 		= wppa( 'mocc' );
	if ( $nice ) $overflow = 'hidden';
	$modal 		= defined( 'DOING_WPPA_AJAX' ) && wppa_switch( 'ajax_render_modal' );
	$result 	= '';
	$album 		= wppa( 'start_album' );
	$left 		= 'left';
	$right 		= 'right';
	$padding 	= '0;';
	if ( wppa_opt( 'audioonly_posterpos' ) == 'left' ) {
		$left 		= 'right';
		$right 		= 'left';
		$padding 	= '0 8px;';
	}

	// Open tha audio only box
	if ( $action == 'open' ) {
		if ( is_feed() ) {
			$result .= 	'
			<div
				id="wppa-audio-only-' . $mocc . '"
				class="wppa-box wppa-audio-only"
				>';
		}
		else {
			$br = wppa_opt( 'bradius' ) . 'px';
			$result .= 	'
			<div id="wppa-audioonly-div-'.$mocc.'" class="wppa-box" style="margin-bottom:0; border-bottom:none; border-radius:'.$br.' '.$br.' 0px 0px;">';
				if ( wppa_is_int( $album ) ) {
					$poster = wppa( 'audio_poster' );
					if ( $poster ) {
						$result .= '<div style="float:'.$right.';max-width:20%;">
										<img src="' . esc_attr( wppa_get_photo_url( $poster ) ) . '" style=""></div>';
						$result .= '<div style="float:left;padding:'.$padding.'max-width:80%;">';
					}
					if ( wppa_switch( 'audioonly_name' ) ) {
						$result .= '<h3 style="margin-top:6px;">' . wppa_get_album_name( $album ) . '</h3>';
					}
					if ( wppa_switch( 'audioonly_desc' ) ) {
						$result .= '<span style="margin-top:6px;">' . wppa_get_album_desc( $album ) . '</span>';
					}
					if ( $poster ) {
						$result .= '</div>';
					}
				}
				$result .= '
				<audio
					id="wppa-audioonly-'.$mocc.'"
					style="height:20px;width:100%;"
					controls
					preload="metadata"
					onended="wppaAudioOnlyNext('.$mocc.')">
					<source id="wppa-audio-source-'.$mocc.'" src="" type="audio/mpeg">
				</audio>
			</div>
			<div
				id="wppa-audio-only-' . $mocc . '"
				class="wppa-box audiolist wppa-audio-only wppa-audio-only-' . $mocc . ( $modal ? ' wppa-modal' : '' ) . '"
				style="' . ( $maxh > '1' ? 'max-height:' . $maxh . 'px;' : '' ) . '
						overflow:' . $overflow . ';
						border-radius: 0px 0px '.$br.' '.$br.';
						border-top:none;
						margin-top:-6px;
						"
				>';

			if ( wppa_is_posint( wppa( 'start_album' ) ) ) {
				wppa_bump_viewcount( 'album', wppa( 'start_album') );
			}
		}

		// Use nicescroller?
		if ( $nice ) {
			$result .= 	'<div class="wppa-nicewrap" >';
		}
	}

	// Close audio only box
	elseif ( $action == 'close' ) {

		// Clear both
		$result .= '<div class="wppa-clear" ></div>';

		// Nicescroller
		if ( $nice ) {
			wppa_js( '
				jQuery(document).ready(function(){
					if ( jQuery().niceScroll )
					jQuery(".wppa-audio-only").niceScroll(".wppa-nicewrap",{' . wppa_opt( 'nicescroll_opts' ) . '});
				});' );
			$result .= '</div>'; 	// close .wppa-nicewrap div
		}

		// Close the thumbnail box
		$result .= '</div>';
	}

	// Output result
	wppa_out( $result );
}

function wppa_notify_box() {

	if ( is_feed() ) return;
	if ( ! is_user_logged_in() ) return;

	wppa_container( 'open' );

	wppa_out( '
		<div
			id="wppa-notify-' . wppa( 'mocc' ) . '"
			class="wppa-box wppa-notify"
			>
			<h3>' .
				__( 'Notify me when', 'wp-photo-album-plus' ) . '
			</h3>' .
			wppa_get_email_subscription_body() . '
			<input type="hidden" id="wppa-ntfy-nonce" value="' . wp_create_nonce( 'wppa-ntfy-nonce' ) . '" />
			<div class="wppa-clear" ></div>
		</div>' );

	wppa_container( 'close' );
}