<?php
/**
 * LinkView Links Class
 *
 * @package link-view
 */

// declare( strict_types=1 ); Remove for now due to warnings in php <7.0!

namespace WordPress\Plugins\mibuthu\LinkView;

if ( ! defined( 'WPINC' ) ) {
	exit();
}

require_once PLUGIN_PATH . 'shortcode/config.php';


/**
 * LinkView Links Class
 *
 * This class handles the shortcode view for link lists.
 */
class Links {


	/**
	 * Get Links
	 *
	 * @param Shortcode\Config $shortcode_config Shortcode Config object.
	 * @param \WP_Term|null    $category Category object.
	 * @return object[] Links object array.
	 */
	public static function get( $shortcode_config, $category = null ) {
		$args = [
			'orderby' => $shortcode_config->link_orderby,
			'order'   => $shortcode_config->link_order,
			'limit'   => $shortcode_config->num_links,
		];
		if ( $category instanceof \WP_Term ) {
			$args['category_name'] = $category->name;
		}
		return get_bookmarks( $args );
	}


	/**
	 * Get link categories
	 *
	 * @param Shortcode\Config $shortcode_config Shortcode Config object.
	 * @return \WP_Term[] Link category object array.
	 */
	public static function categories( $shortcode_config ) {
		$catarray = [];
		// TODO: The cat_filter value "all" is depricated and can be removed in 0.9.
		if ( '' !== $shortcode_config->cat_filter && 'all' !== $shortcode_config->cat_filter ) {
			str_replace( ',', '|', $shortcode_config->cat_filter );
			$catslugs = array_map( 'trim', array_map( 'strval', (array) explode( '|', $shortcode_config->cat_filter ) ) );
			foreach ( $catslugs as $catslug ) {
				$term = get_term_by( 'slug', $catslug, 'link_category' );
				if ( $term instanceof \WP_Term ) {
					$catarray[] = $term;
				}
			}
		} else {
			// There seems to be a problem to recognize the get_terms function correctly.
			// @phan-suppress-next-line PhanAccessMethodInternal.
			$terms = get_terms(
				[
					'taxonomy' => 'link_category',
					'orderby'  => 'name',
				]
			);
			if ( is_array( $terms ) ) {
				$catarray = $terms;
			}
			if ( '' !== $shortcode_config->exclude_cat ) {
				$excludecat = array_map( 'trim', array_map( 'strval', (array) explode( ',', $shortcode_config->exclude_cat ) ) );
				$diff       = [];
				foreach ( $catarray as $cat ) {
					if ( false === array_search( $cat->name, $excludecat, true ) ) {
						array_push( $diff, $cat );
					}
				}
				$catarray = $diff;
				unset( $diff );
			}
		}
		return $catarray;
	}

}
