<?php
/**
 * Walker_PageDropdown_Multiple
 */
if ( ! class_exists( 'Walker_PageDropdown_Multiple' ) ) {
	/**
	 * Create HTML dropdown list of pages.
	 *
	 * @package WordPress
	 * @since 2.1.0
	 * @uses Walker
	 */
	class Walker_PageDropdown_Multiple extends Walker_PageDropdown {
		/**
		 * @see Walker::start_el()
		 * @since 2.1.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $page Page data object.
		 * @param int    $depth Depth of page in reference to parent pages. Used for padding.
		 * @param array  $args Uses 'selected' argument for selected page to set selected HTML attribute for option element.
		 * @param int    $id
		 */
		function start_el( &$output, $page, $depth = 0, $args = array(), $id = 0 ) {
			$pad = str_repeat( isset( $args['pad'] ) ? $args['pad'] : '--', $depth );

			$output .= "\t<option class=\"level-$depth\" value=\"$page->ID\"";
			if ( in_array( $page->ID, (array) $args['selected'] ) ) {
				$output .= ' selected="selected"';
			}

			$output .= '>';
			$title   = apply_filters( 'list_pages', $page->post_title, $page );
			$title   = apply_filters( 'pagedropdown_multiple_title', $title, $page, $args );
			$output .= $pad . ' ' . esc_html( $title );
			$output .= "</option>\n";
		}
	}
}

/**
 * Walker_CategoryDropdown_Multiple
 */
if ( ! class_exists( 'Walker_CategoryDropdown_Multiple' ) ) {
	/**
	 * Create HTML dropdown list of pages.
	 *
	 * @package WordPress
	 * @since 2.1.0
	 * @uses Walker
	 */
	class Walker_CategoryDropdown_Multiple extends Walker_CategoryDropdown {
		/**
		 * @see Walker::start_el()
		 * @since 2.1.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $page Page data object.
		 * @param int    $depth Depth of page in reference to parent pages. Used for padding.
		 * @param array  $args Uses 'selected' argument for selected page to set selected HTML attribute for option element.
		 * @param int    $id
		 */
		function start_el( &$output, $page, $depth = 0, $args = array(), $id = 0 ) {
			$pad = str_repeat( isset( $args['pad'] ) ? $args['pad'] : '--', $depth );

			$output .= "\t<option class=\"level-$depth\" value=\"$page->term_id\"";
			if ( in_array( $page->term_id, (array) $args['selected'] ) ) {
				$output .= ' selected="selected"';
			}

			$output .= '>';
			$title   = apply_filters( 'list_pages', $page->name, $page );
			$title   = apply_filters( 'pagedropdown_multiple_title', $title, $page, $args );
			$output .= $pad . ' ' . esc_html( $title );
			$output .= "</option>\n";
		}
	}
}

