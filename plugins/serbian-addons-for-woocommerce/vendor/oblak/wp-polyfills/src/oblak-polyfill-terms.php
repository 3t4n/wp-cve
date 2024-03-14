<?php
/**
 * Array polyfills
 *
 * ! This file intentionally left without namespace
 *
 * @package WP Polyfills
 * @subpackage Term functions
 */

if ( ! function_exists( 'wp_format_term_name' ) ) :
	/**
	 * Formats the term name with its ancestors
	 *
	 * @param  WP_Term $term       Term object.
	 * @param  string  $tax        Taxonomy slug.
	 * @param  bool    $with_count Whether to include the term count.
	 * @param  bool    $with_link  Whether to include the term link.
	 * @param  string  $link_base  Base URL for the term link.
	 * @param  string  $sep        Separator between terms.
	 * @return string              Formatted term name.
	 */
	function wp_format_term_name(
        WP_Term $term,
        string $tax = 'category',
        bool $with_count = false,
        bool $with_link = false,
        string $link_base = '',
        string $sep = ' > ',
	) {
		$formatted_name = '';
		$formatter      = $with_link
        ? static fn( $term ) => sprintf(
            '<a href="%s">%s</a>',
            '' === $link_base
                ? get_term_link( $term->term_id, $tax )
                : add_query_arg( $tax, $term->slug, $link_base ),
            $term->name,
        )
        : static fn( $term ) => $term->name;

		if ( $term->parent ) {
			$ancestors = array_reverse( get_ancestors( $term->term_id, $tax ) );
			foreach ( $ancestors as $ancestor ) {
				$ancestor_term = get_term( $ancestor, $tax );

				if ( ! $ancestor_term ) {
                    continue;
                }

                $formatted_name .= $formatter( $ancestor_term ) . $sep;
			}
		}

		$formatted_name .= $formatter( $term );

		return $with_count ? sprintf( '%s (%d)', $formatted_name, $term->count ) : $formatted_name;
	}
endif;
