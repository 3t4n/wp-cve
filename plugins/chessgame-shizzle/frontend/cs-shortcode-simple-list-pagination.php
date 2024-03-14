<?php

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Pagination of the chessgames in simple list shortcode
 *
 * @param int $pagenum the number of the requested page.
 * @param int $pages_total the total number of pages.
 * @return string $pagination the html of the pagination.
 *
 * @since 1.1.2
 */
function chessgame_shizzle_simple_list_pagination( $pagenum, $pages_total ) {

	$highdotsmade = false;
	$pages_done = array();

	$permalink = get_permalink(get_the_ID());

	$pagination = '
				<div class="page-navigation">
					<span class="screen-reader-text">' . esc_html__('Chessgame list navigation', 'chessgame-shizzle') . '</span>';

	if ($pagenum > 1) {
		$pagination .= '<a href="' . add_query_arg( 'pagenum', round($pagenum - 1), esc_attr( $permalink ) ) . '" title="' . esc_attr__('Previous page', 'chessgame-shizzle') . '" rel="prev">&larr;</a>';
	}

	if ($pagenum < 5) {
		$showrange = 5;
		if ($pages_total < 6) {
			$showrange = $pages_total;
			$highdotsmade = true; // no need for highdots.
		}
		for ( $i = 1; $i < ( $showrange + 1 ); $i++ ) {
			if ($i === $pagenum) {
				if ( in_array( $i, $pages_done ) ) {
					continue;
				}
				$pagination .= '<span class="page-numbers current">' . $i . '</span>';
				$pages_done[] = $i;
			} else {
				if ( in_array( $i, $pages_done ) ) {
					continue;
				}
				$pagination .= '<a href="' . add_query_arg( 'pagenum', $i, esc_attr( $permalink ) ) . '" title="' . esc_attr__('Page', 'chessgame-shizzle') . ' ' . (int) $i . '">' . (int) $i . '</a>';
				$pages_done[] = $i;
				if ( $i === $pages_total ) {
					break;
				}
			}
		}

		if ( ( $pagenum + 4 < $pages_total ) && ( ! $highdotsmade ) ) {
			$pagination .= '<span class="page-numbers dots">...</span>';
			$highdotsmade = true;
		}
	} else if ( $pagenum > 4 ) {
		$pagination .= '<a href="' . add_query_arg( 'pagenum', 1, esc_attr( $permalink ) ) . '" title="' . esc_attr__('Page', 'chessgame-shizzle') . ' 1">1</a>';
		if ($pages_total > 4) {
			$pagination .= '<span class="page-numbers dots">...</span>';
		}
		if ( ( $pagenum + 2 ) < $pages_total ) {
			$minrange = ( $pagenum - 2 );
			$showrange = ( $pagenum + 2 );
		} else {
			$minrange = ( $pagenum - 3 );
			$showrange = ( $pages_total - 1 );
		}
		for ($i = $minrange; $i <= $showrange; $i++) {
			if ($i === $pagenum) {
				$pagination .= '<span class="page-numbers current">' . $i . '</span>';
			} else {
				$pagination .= '<a href="' . add_query_arg( 'pagenum', $i, esc_attr( $permalink ) ) . '" title="' . esc_attr__('Page', 'chessgame-shizzle') . ' ' . (int) $i . '">' . (int) $i . '</a>';
			}
		}
		if ($pagenum === $pages_total) {
			$pagination .= '<span class="page-numbers current">' . $pagenum . '</span>';
		}
	}

	if ( $pagenum < $pages_total ) {
		if ( ( $pagenum + 3 < $pages_total ) && ( ! $highdotsmade ) ) {
			$pagination .= '<span class="page-numbers dots">...</span>';
			$highdotsmade = true;
		}
		if ( ! in_array( $pages_total, $pages_done ) ) {
			$pagination .= '<a href="' . add_query_arg( 'pagenum', $pages_total, esc_attr( $permalink ) ) . '" title="' . esc_attr__('Page', 'chessgame-shizzle') . ' ' . (int) $pages_total . '">' . (int) $pages_total . '</a>';
		}
		$pagination .= '<a href="' . add_query_arg( 'pagenum', round($pagenum + 1), esc_attr( $permalink ) ) . '" title="' . esc_attr__('Next page', 'chessgame-shizzle') . '" rel="next">&rarr;</a>';
	}

	$pagination .= '</div>
		';

	if ( $pages_total > 1 ) {
		return $pagination;
	}

}
