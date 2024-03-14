<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */

namespace Glossary\Frontend\Core;

use Glossary\Engine;

/**
 * Combine the Core to gather, search and inject
 */
class Alphabetical_Index_Bar extends Engine\Base {

	/**
	 * Settings
	 *
	 * @var array
	 */
	private $atts = array();

	/**
	 * Term index
	 *
	 * @var array
	 */
	private $alpha_terms = array();

	/**
	 * Term index alphabetic
	 *
	 * @var array
	 */
	private $alpha_index = array();

	/**
	 * Generate a list A B C D etc.
	 *
	 * @param array $atts The various parameters.
	 * @return array
	 */
	public function generate_index( array $atts ) {
		$this->atts = $atts;

		if ( !in_array( $this->atts[ 'theme' ], array( 'grid', 'summary' ), true ) ) {
			$this->atts[ 'theme' ] = '';
		}

		$this->alpha_index = $this->alpha_terms = array();

		$this->loop_terms();
		$this->parse_terms();
		$this->order_letters();

		return array( $this->alpha_index, $this->alpha_terms );
	}

	/**
	 * Generate WP_Query arguments
	 *
	 * @return array
	 */
	public function generate_args() {
		$is   = new Engine\Is_Methods;
		$args = array(
			'post_type'              => 'glossary',
			'posts_per_page'         => -1,
			'order'                  => 'ASC',
			'orderby'                => 'title',
			'post_status'            => 'publish',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
		);

		if ( $is->request( 'ajax' ) || $is->request( 'backend' ) ) {
			$args['posts_per_page'] = 30;
		}

		if ( !empty( $this->atts[ 'taxonomy' ] ) ) {
			$args[ 'tax_query' ] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'glossary-cat',
					'field'    => 'slug',
					'terms'    => $this->atts[ 'taxonomy' ],
				),
			);
		}

		if ( !empty( $this->atts[ 'show-letter' ] ) ) {
			$args[ 'post__in' ] = \gl_get_a2z_ids( $this->atts );
		}

		return $args;
	}

	/**
	 * Loop terms
	 *
	 * @return void
	 */
	public function loop_terms() {
		$terms = new \WP_Query( $this->generate_args() );

		foreach ( $terms->posts as $post ) {
			$post_id = $post;
			$title   = \get_the_title( $post_id );

			if ( !\is_int( $post ) ) {
				$post_id = $post->ID;
				$title   = $post->post_title;
			}

			$this->initial_index( $title, $post_id );
		}
	}

	/**
	 * Generate index
	 *
	 * @return void
	 */
	public function parse_terms() {
		if ( isset( $this->atts[ 'empty-letters' ] ) && 'true' !== $this->atts[ 'empty-letters' ] ) {
			return;
		}

		$letters = \range( 'A', 'Z' );

		foreach ( $letters as $letter ) {
			$letter = \strtolower( (string) $letter );

			if ( isset( $this->alpha_index[ $letter ] ) ) {
				continue;
			}

			$this->alpha_index[ $letter ] = '<span class="glossary-no-link-initial-item">' . $letter . '</span>';
		}
	}

	/**
	 * Fill the alpha_index.
	 *
	 * @param string       $title The term.
	 * @param int|\WP_Post $post_id ID.
	 * @return void
	 */
	public function initial_index( $title, $post_id ) { // phpcs:ignore
		$initial_index = \mb_strtolower( $title );
		$initial_index = \mb_substr( $initial_index, 0, 1 );

		if ( !isset( $this->alpha_index[ $initial_index ] ) ) {
			$link = '#glossary-' . $initial_index;

			if (
				isset( $this->atts[ 'letter-anchor' ] )
				&& 'false' === $this->atts[ 'letter-anchor' ]
				|| !isset( $this->atts[ 'letter-anchor' ] )
			) {
				$link = \add_query_arg( 'az', $initial_index, \gl_get_base_url() );

				if ( !isset( $this->settings[ 'archive' ] ) || empty( $this->settings[ 'archive' ] ) ) {
					$posttype = \get_post_type_object( 'glossary' );

					if ( is_object( $posttype ) && is_array( $posttype->rewrite ) ) {
						$link = \add_query_arg( 'az', $initial_index, \get_bloginfo( 'url' ) . '/' . $posttype->rewrite['slug'] );
					}
				}
			}

			$this->alpha_index[ $initial_index ] = '<span class="glossary-link-initial-item"><a href="' . $link . '">' . $initial_index . '</a></span>';
		}

		$this->alpha_terms[ $initial_index ][ $post_id ] = $title;
	}

	/**
	 * Generate list of terms in a list.
	 *
	 * @param array $terms List of terms.
	 * @return string
	 */
	public function generate_li( array $terms ) {
		$html = '';

		foreach ( $terms as $id => $title ) {
			$html .= '<li>';
			$html .= '<span class="glossary-link-item">';
			$html .= $this->get_anchor( $id, $title );
			$html .= $this->get_featured_image( $id );
			$html .= $this->get_text( $id );
			$html .= '</span>';
			$html .= '</li>';
		}

		return $html;
	}

	/**
	 * Generate content based on the list.
	 *
	 * @return string
	 */
	public function generate_html_content() {
		$accordion = '';

		if ( 'false' !== $this->atts[ 'accordion' ] ) {
			$accordion = ' glossary-accordion';
		}

		$html       = '<div class="glossary-term-list ' . $this->atts[ 'theme' ] . $accordion . '">';
		$letter_tag = \apply_filters( $this->default_parameters[ 'filter_prefix' ] . '_a2z_letter_tag', 'span' );

		foreach ( $this->alpha_terms as $letter => $terms ) {
			$html .= '<div class="glossary-block glossary-block-' . $letter . '">';
			$html .= '<' . $letter_tag . ' class="glossary-letter" id="glossary-' . $letter . '">' . $letter . '</' . $letter_tag . '>';
			$html .= '<ul>';
			$html .= $this->generate_li( $terms );
			$html .= '</ul></div>';
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * Get anchor from a term.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $title Term title.
	 * @return string
	 */
	public function get_anchor( int $post_id, string $title ) {
		$anchor = $title;
		$target = '';

		if ( 'true' === $this->atts[ 'term-anchor-target' ] ) {
			$target = ' target="_blank"';
		}

		if ( 'true' === $this->atts[ 'term-anchor' ] ) {
			$anchor    = '<a href="' . \get_permalink( (int) $post_id ) . '"' . $target . '>' . $title . '</a>';
			$customurl = \get_glossary_term_url( $post_id );

			if ( 'true' === $this->atts[ 'custom-url' ] && !empty( $customurl ) ) {
				$anchor = '<a href="' . $customurl . '"' . $target . '>' . $title . '</a>';
			}
		}

		return $anchor;
	}

	/**
	 * Generate atts content
	 *
	 * @param int $post_id Post ID.
	 * @return string
	 */
	public function get_atts_content( int $post_id ) {
		$text = '';

		if ( 'true' === $this->atts[ 'excerpt' ] ) {
			$text = '<span class="glossary-list-term-excerpt">' . \get_the_excerpt( $post_id ) . '</span>';
		}

		if ( 'true' === $this->atts[ 'content' ] ) {
			$text = '<span class="glossary-list-term-content">' . \get_post_field( 'post_content', $post_id ) . '</span>';
		}

		if ( 'true' === $this->atts[ 'custom-fields' ] ) {
			$term_content = new \Glossary\Frontend\Term_Content( true );
			$text        .= '<span class="glossary-list-term-content">' . $term_content->custom_fields( $post_id, '' ) . '</span>';
		}

		return $text;
	}

	/**
	 * Get Term's featured image
	 *
	 * @param int $post_id Term's ID.
	 * @return string
	 */
	public function get_featured_image( int $post_id ) {
		$html = '';

		if ( $this->atts['featured-image'] !== 'false' ) {
			$thumb = \get_the_post_thumbnail( $post_id, $this->atts['featured-image'] );

			if ( !empty( $thumb ) ) {
				$html = '<div class="glossary-list-image">' . $thumb . '</div>';
			}
		}

		return $html;
	}

	/**
	 * Generate HTML index
	 *
	 * @param int $post_id Post ID.
	 * @return string
	 */
	public function get_text( int $post_id ) {
		$separator = \apply_filters( 'glossary_list_excerpt_separator', ' - ' );

		$text = $this->get_atts_content( $post_id );

		if ( !empty( $text ) ) {
			return $separator . $text;
		}

		return '';
	}

	/**
	 * Generate HTML index
	 *
	 * @return string
	 */
	public function generate_html_index() {
		$this->remap_search_attribute();
		$accordion = '';

		if ( isset( $this->atts[ 'accordion' ] ) && 'false' !== $this->atts[ 'accordion' ] ) {
			$accordion = ' glossary-accordion';
		}

		$bar  = '<div class="glossary-term-bar' . $accordion . '">';
		$bar .= \implode( '', $this->alpha_index );
		$bar .= '</div>';

		if ( 'disabled' !== $this->atts[ 'search' ] ) {
			$bar .= '<div class="gt-search-bar" data-scroll="' . $this->atts[ 'search' ] . '">';
			$bar .= '<input type="text" class="gt-search" name="gt-search" aria-label="' . \__( 'Search', GT_TEXTDOMAIN ) . '" placeholder="' . \__( 'Search', GT_TEXTDOMAIN ) . '" value="" autocomplete="off">'; // phpcs:ignore

			if ( 'scroll' === $this->atts[ 'search' ] || 'scroll-bottom' === $this->atts[ 'search' ] ) {
				$bar .= '<button class="gt-next">&darr;</button><button class="gt-prev">&uarr;</button>';
			}

			$bar .= '</div>';
		}

		return $bar;
	}

	/**
	 * Remap old parameters from Glossary 1.0 to 2.0
	 *
	 * @return array
	 */
	public function remap_search_attribute() {
		switch ( $this->atts[ 'search' ] ) {
			case 'true':
				$this->atts[ 'search' ] = 'scroll';

				break;

			case 'false':
				$this->atts[ 'search' ] = 'disabled';

				break;

			case 'no-fixed':
				$this->atts[ 'search' ] = 'no-scroll';

				break;
		}

		return $this->atts;
	}

	/**
	 * Order the two arrays based on php modules avalaible
	 *
	 * @return bool
	 */
	private function order_letters() {
		if ( \extension_loaded( 'intl' ) ) {
			$keys   = \array_keys( $this->alpha_index );
			$values = \array_values( $this->alpha_index );
			$locale = \collator_create( \get_locale() );

			if ( !\is_null( $locale ) ) {
				\collator_asort( $locale, $keys );
			}

			$this->alpha_index = array();

			foreach ( $keys as $index => $key ) {
				$this->alpha_index[$key] = $values[$index];
			}

			return false;
		}

		\ksort( $this->alpha_index );
		\ksort( $this->alpha_terms );

		return true;
	}

}
