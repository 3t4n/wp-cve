<?php
/**
 * Expand Divi Remove Sidebar
 * removes the sidebar from posts, archive pages or globally
 *
 * @package  ExpandDivi/ExpandDiviRemoveSidebar
 */

// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ExpandDiviRemoveSidebar {
	public $options;

	/**
	 * constructor
	 */
	function __construct() {
		$this->options = get_option( 'expand_divi' );
		add_filter( 'body_class', array( $this, 'expand_divi_remove_sidebar' ) );	
	}

	/**
	 * adds to the body_class if option is enabled
	 *
	 * @return array
	 */
	function expand_divi_remove_sidebar( $classes ) {
		if ( $this->options['remove_sidebar'] == 1 ) {
			if ( is_category() || is_tag() || is_author() || is_search() || ( ! is_front_page() && is_home() ) || is_single() ) {
				$classes[] = 'expand-divi-sidebar-global-remove';
			}
		} elseif ( $this->options['remove_sidebar'] == 2 ) {
			if ( is_single() ) {
				$classes[] = 'expand-divi-sidebar-posts-remove';
			}
		} elseif ( $this->options['remove_sidebar'] == 3 ) {
			if ( is_category() || is_tag() || is_author() || is_search() || ( ! is_front_page() && is_home() ) ) {
				$classes[] = 'expand-divi-sidebar-archive-remove';
			}
		}

    	return $classes;
	}
}

new ExpandDiviRemoveSidebar();