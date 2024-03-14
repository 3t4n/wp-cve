<?php


/**
 * Crul to fetch stats.
 *
 * @since 2.0
 */
function ssb_fetch_shares_via_curl_multi( $data, $options = array() ) {

	// array of curl handles
	$curly = array();
	// data to be returned
	$result = array();

	// multi handle
	$mh = curl_multi_init();

	// loop through $data and create curl handles
	// then add them to the multi-handle
	if ( is_array( $data ) ) :
		foreach ( $data as $id => $d ) :

			if ( $d !== 0 ) :

				$curly[ $id ] = curl_init();

				$url = ( is_array( $d ) && ! empty( $d['url'] ) ) ? $d['url'] : $d;
				curl_setopt( $curly[ $id ], CURLOPT_URL, $url );
				curl_setopt( $curly[ $id ], CURLOPT_HEADER, 0 );
				curl_setopt( $curly[ $id ], CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt( $curly[ $id ], CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] );
				curl_setopt( $curly[ $id ], CURLOPT_FAILONERROR, 0 );
				curl_setopt( $curly[ $id ], CURLOPT_FOLLOWLOCATION, 0 );
				curl_setopt( $curly[ $id ], CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt( $curly[ $id ], CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $curly[ $id ], CURLOPT_SSL_VERIFYHOST, false );
				curl_setopt( $curly[ $id ], CURLOPT_TIMEOUT, 5 );
				curl_setopt( $curly[ $id ], CURLOPT_CONNECTTIMEOUT, 5 );
				curl_setopt( $curly[ $id ], CURLOPT_NOSIGNAL, 1 );
				curl_setopt( $curly[ $id ], CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
				// curl_setopt($curly[$id], CURLOPT_SSLVERSION, CURL_SSLVERSION_SSLv3);

				// extra options?
				if ( ! empty( $options ) ) {
					curl_setopt_array( $curly[ $id ], $options );
				}

				curl_multi_add_handle( $mh, $curly[ $id ] );

			endif;
		endforeach;
	endif;

	// execute the handles
	$running = null;
	do {
		curl_multi_exec( $mh, $running );
	} while ( $running > 0 );

	// get content and remove handles
	foreach ( $curly as $id => $c ) {
		$result[ $id ] = curl_multi_getcontent( $c );
		curl_multi_remove_handle( $mh, $c );
	}

	// all done
	curl_multi_close( $mh );

	return $result;
}



	/**
	 * Return false if to fetch the new counts.
	 *
	 * @return bool
	 * @since 2.0
	 */
function ssb_is_cache_fresh( $post_id, $output = false, $ajax = false ) {
	// global $swp_user_options;
	// Bail early if it's a crawl bot. If so, ONLY SERVE CACHED RESULTS FOR MAXIMUM SPEED.
	if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && preg_match( '/bot|crawl|slurp|spider/i', wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) ) {
		return true;
	}

	// $options = $swp_user_options;
	$fresh_cache = false;

	if ( isset( $_POST['ssb_cache'] ) && 'rebuild' === $_POST['ssb_cache'] ) {
		return false;
	}
	// Always be TRUE if we're not on a single.php otherwise we could end up
	// Rebuilding multiple page caches which will cost a lot of time.
	// if ( ! is_singular() && ! $ajax ) {
	// return true;
	// }

	$post_age = floor( date( 'U' ) - get_post_time( 'U', false, $post_id ) );

	if ( $post_age < ( 21 * 86400 ) ) {
		$hours = 1;
	} elseif ( $post_age < ( 60 * 86400 ) ) {
		$hours = 4;
	} else {
		$hours = 12;
	}

	$time         = floor( ( ( date( 'U' ) / 60 ) / 60 ) );
	$last_checked = get_post_meta( $post_id, 'ssb_cache_timestamp', true );

	if ( $last_checked > ( $time - $hours ) && $last_checked > 390000 ) {
		$fresh_cache = true;
	} else {
		$fresh_cache = false;
	}

	return $fresh_cache;
}


	/**
	 * Fetch fresh counts and cached them.
	 *
	 * @param  Array  $stats
	 * @param  String $post_id
	 * @return Array Simple array with counts.
	 * @since 2.0
	 */
function ssb_fetch_fresh_counts( $stats, $post_id, $alt_share_link ) {

	$stats_result = array();
	$total        = 0;

	// special case if post id not exist for example short code run on widget out side the loop in archive page
	if ( 0 !== $post_id ) {
		$networks = get_post_meta( $post_id, 'ssb_old_counts', true );
	} else {
		$networks = get_option( 'ssb_not_exist_post_old_counts' );
	}

	if ( ! $networks ) {
		$_result = ssb_fetch_shares_via_curl_multi( array_filter( $alt_share_link ) );
		ssb_fetch_http_or_https_counts( $_result, $post_id );
		// special case if post id not exist for example short code run on widget out side the loop in archive page
		if ( 0 !== $post_id ) {
			$networks = get_post_meta( $post_id, 'ssb_old_counts', true );
		} else {
			$networks = get_option( 'ssb_not_exist_post_old_counts' );

		}
	}

	foreach ( $stats as $social_name => $counts ) {
		if ( ! ssb_is_network_has_counts( $social_name ) ) {
			continue;
		}
		$stats_counts = call_user_func( 'ssb_format_' . $social_name . '_response', $counts );
		$new_counts   = is_array( $networks ) && ! empty( $networks ) ? $stats_counts + $networks[ $social_name ] : '';

		$old_counts = get_post_meta( $post_id, 'ssb_' . $social_name . '_counts', true );

		// this will solve if new plugin install.
		$old_counts = $old_counts ? $old_counts : 0;
		// if old counts less than new. Return old.
		if ( $new_counts > $old_counts ) {
			$stats_result[ $social_name ] = $new_counts;
		} else {
			$stats_result[ $social_name ] = $old_counts;
		}

		// special case if post id not exist for example short code run on widget out side the loop in archive page
		if ( 0 !== $post_id ) {
			if ( $new_counts > $old_counts ) {
				update_post_meta( $post_id, 'ssb_' . $social_name . '_counts', $new_counts );
			} else {
				// set new counts = old counts for total calculation.
				$new_counts = $old_counts;
			}
		} else {
			update_option( 'ssb_not_exist_post_' . $social_name . '_counts', $new_counts );
		}

		$total += $new_counts;
	}

	$stats_result['total'] = $total;
	// special case if post id not exist for example short code run on widget out side the loop in archive page
	if ( 0 !== $post_id ) {
		update_post_meta( $post_id, 'ssb_total_counts', $total );
	} else {
		update_option( 'ssb_not_exist_post_total_counts', $total );
	}

	return $stats_result;
}
	/**
	 * Fetch counts + http or https resolve .
	 *
	 * @param  Array  $stats
	 * @param  String $post_id
	 * @return Array Simple array with counts.
	 * @since 2.0.12
	 */
function ssb_fetch_http_or_https_counts( $stats, $post_id ) {
	$stats_result = array();
	$networks     = array();
	foreach ( $stats as $social_name => $counts ) {
		if ( ! ssb_is_network_has_counts( $social_name ) ) {
			continue; }
		$stats_counts              = call_user_func( 'ssb_format_' . $social_name . '_response', $counts );
		 $networks[ $social_name ] = $stats_counts;
	}
	// special case if post id not exist for example short code run on widget out side the loop in archive page
	if ( 0 !== $post_id ) {
		update_post_meta( $post_id, 'ssb_old_counts', $networks );
	} else {
		update_option( 'ssb_not_exist_post_old_counts', $networks );
	}

}

	/**
	 * Get the cahced counts.
	 *
	 * @param  Array  $network_name
	 * @param  String $post_id
	 * @return Array Counts of each network.
	 * @since 2.0
	 */
function ssb_fetch_cached_counts( $network_name, $post_id ) {
	$network_name[] = 'total';
	$result         = array();
	foreach ( $network_name as $social_name ) {
		// special case if post id not exist for example short code run on widget out side the loop in archive page
		if ( 0 !== $post_id ) {
			$result[ $social_name ] = get_post_meta( $post_id, 'ssb_' . $social_name . '_counts', true );
		} else {
			$result[ $social_name ] = get_option( 'ssb_not_exist_post_' . $social_name . '_counts' );
		}
	}
	return $result;
}


	/**
	 * Detect if mobile.
	 *
	 * @since 2.0.13
	 */
function ssb_is_mobile() {

	$useragent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : 'none';

	if ( preg_match( '/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent ) || preg_match( '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr( $useragent, 0, 4 ) ) ) {
		return true;
	} else {
		return false;
	}
}

	/**
	 * Generate WhatsApp share link.
	 *
	 * @param String $url
	 * @return String Final url after detection is it mobile or desktop.
	 * @since 2.0.23
	 * @version 5.0.0
	 */
function ssb_whats_app_share_link( $url ) {
	$whats_share_link = '';
	$whats_share_link = 'https://api.whatsapp.com/send?text=' . $url;

	return $whats_share_link;
}
	/**
	 * Generate Viber share link.
	 *
	 * @param String $url
	 * @return String Final url after detection is it desktop.
	 * @since 3.2.0
	 */

	// Viber Sharing API calling
function ssb_viber_share_link( $url ) {
	$viber_share_link = 'viber://forward?text=' . $url;
	return $viber_share_link;
}
	/**
	 * Generate LinkdIn share link.
	 * @param String $url
	 * @return String Final url after detection is it desktop.
	 * @since 3.2.0
	 */

	// LinkdIn Sharing API calling
function ssb_linkdin_share_link( $url ) {
	$Linkdin_share_link = 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url;
	return $Linkdin_share_link;
}

	/**
	 * Check if SSB network has count/s.
	 *
	 * @since 2.1.4
	 * @param string $network network name.
	 * @return boolean
	 */
function ssb_is_network_has_counts( $network ) {
	$no_count_networks = array( 'totalshare', 'viber', 'fblike', 'whatsapp', 'print', 'email', 'messenger', 'linkedin' );
	if ( in_array( $network, $no_count_networks ) ) {
		return false;
	} else {
		return true;
	}
}

	/**
	 * Pretty counts format.
	 *
	 * @param integer $n .
	 * @param integer $precision .
	 * @return int|mixed
	 * @since 2.0.0
	 */
function ssb_count_format( $n, $precision = 1 ) {
	if ( $n >= 0 && $n < 1000 ) {
		// 1 - 999
		$n_format = floor( $n );
		$suffix   = '';
	} elseif ( $n >= 1000 && $n < 1000000 ) {
		// 1k-999k
		$n_format = number_format( $n / 1000, $precision );
		$suffix   = 'K+';
	} elseif ( $n >= 1000000 && $n < 1000000000 ) {
		// 1m-999m
		$n_format = number_format( $n / 1000000, $precision );
		$suffix   = 'M+';
	} elseif ( $n >= 1000000000 && $n < 1000000000000 ) {
		// 1b-999b
		$n_format = number_format( $n / 1000000000, $precision );
		$suffix   = 'B+';
	} elseif ( $n >= 1000000000000 ) {
		// 1t+
		$n_format = number_format( $n / 1000000000000, $precision );
		$suffix   = 'T+';
	}
	return ! empty( $n_format . $suffix ) ? floatval( $n_format ) . $suffix : $n;

}

