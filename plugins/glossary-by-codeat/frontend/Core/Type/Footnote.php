<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 2.0+
 * @link      https://codeat.co
 */

namespace Glossary\Frontend\Core\Type;

use Glossary\Engine;

/**
 * Get the HTML about Tooltips
 */
class Footnote extends Engine\Base {

	/**
	 * Initialize the class
	 *
	 * @return bool
	 */
	public function initialize() {
		parent::initialize();

		return true;
	}

	/**
	 * Generate a link or the tooltip
	 *
	 * @param array $atts Parameters.
	 * @global object $post The post object.
	 * @return array
	 */
	public function html( array $atts ) {
		return array( 'before' => '', 'value' => $atts[ 'replace' ] . '<a href="#glossary-footnote-' . $atts['term_ID'] . '" class="glossary-content-footnote"></a>', 'after' => '' );
	}

	/**
	 * Append a footnote on the text after Glossary injection
	 *
	 * @since 2.0
	 * @param array $terms_to_inject Terms to inject.
	 * @return string
	 */
	public function append_content( array $terms_to_inject ) { //phpcs:ignore SlevomatCodingStandard.Complexity.Cognitive.ComplexityTooHigh
		if ( !\is_type_inject_set_as( 'footnote' ) ) {
			return '';
		}

		$footnotes = \gl_get_settings_extra();
		$title     = \__( 'List of terms', GT_TEXTDOMAIN );

		if ( !empty( $footnotes['footnotes_title'] ) ) {
			$title = $footnotes['footnotes_title'];
		}

		$list = '<div class="glossary-footnote"><h4>' . $title . '</h4><ul>';
		$temp = array();

		foreach ( $terms_to_inject as $term ) {
			if ( isset( $temp[ $term[3] ] ) ) {
				continue;
			}

			$list .= '<li id="glossary-footnote-' . $term[3] . '" class="glossary-footnote-link glossary-footnote-' . $term[3] . '">';
			$text  = $term[2];

			if ( !empty( $footnotes['footnotes_list_links'] ) ) {
				$text = '<a href="' . \get_glossary_term_url( $term[3] ) . '" target="_blank">' . $term[2] . '</a>';
			}

			if ( !empty( $footnotes['footnotes_list_excerpt'] ) ) {
				$excerpt = \get_the_excerpt( (int) $term[3] );

				if ( !empty( $excerpt ) ) {
					$text .= ': ' . $excerpt;
				}
			}

			if ( !empty( $footnotes['footnotes_list_content'] ) ) {
				$text .= ': ' . \get_the_content( $term[3] );
			}

			$list          .= $text . '</li>';
			$temp[$term[3]] = true;
		}

		$list .= '</ul></div>';

		return $list;
	}

}
