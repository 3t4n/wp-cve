<?php
/**
 * Expand Divi Single Post Pagination
 * adds a pagination to single posts
 *
 * @package  ExpandDivi/ExpandDiviSinglePostPagination
 */

// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ExpandDiviSinglePostPagination {

	/**
	 * constructor
	 */
	function __construct() {
		add_filter( 'the_content', array( $this, 'expand_divi_output_pagination' ) );	
	}

	/**
	 * append pagination html to the content
	 *
	 * @return string
	 */
	function expand_divi_output_pagination( $content ) {
		if ( is_singular('post') ) {
			$html = '<div class="nav-single clearfix">';
				$html .= '<span class="nav-previous">';
				$html .= get_previous_post_link( '%link', '<span class="meta-nav">' . et_get_safe_localization( _x( '&larr;', 'Previous post link', 'expand-divi' ) ) . 
					'</span> %title' );
				$html .= '</span>';
			$html .= '<span class="nav-next">';
				$html .= get_next_post_link( '%link', '%title <span class="meta-nav">' . et_get_safe_localization( _x( '&rarr;', 'Next post link', 'expand-divi' ) ) . '</span>' );
			$html .= '</span></div><!-- .nav-single -->';

			return $content . $html;
		} else {
			return $content;
		}
	}
}

new ExpandDiviSinglePostPagination();