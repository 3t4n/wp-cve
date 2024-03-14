<?php
/**
 * Expand Divi Archive Blog Styles
 * adds styles to the archive pages
 *
 * @package  ExpandDivi/ExpandDiviArchiveBlogStyles
 */

// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ExpandDiviArchiveBlogStyles {
	public $options;

	/**
	 * constructor
	 */
	function __construct() {
		$this->options = get_option('expand_divi');
		add_filter( 'body_class', array( $this, 'expand_divi_add_style_class' ) );	
	}

	/**
	 * adds to the body_class if option is enabled
	 *
	 * @return array
	 */
	function expand_divi_add_style_class( $classes ) {
		if ( is_category() || is_tag() || is_author() || is_search() || ( ! is_front_page() && is_home() ) ) {
			if ( $this->options['enable_archive_blog_styles'] == 1 ) {
				$classes[] = 'expand-divi-blog-grid';
			} elseif ( $this->options['enable_archive_blog_styles'] == 2 ) {
				$classes[] = 'expand-divi-blog-list';
			}
		}
    	return $classes;
	}
}

new ExpandDiviArchiveBlogStyles();