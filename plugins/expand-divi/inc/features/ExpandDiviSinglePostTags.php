<?php
/**
 * Expand Divi Single Post Tags
 * adds tags to single posts
 *
 * @package  ExpandDivi/ExpandDiviSinglePostTags
 */

// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ExpandDiviSinglePostTags {
	public $options;

	/**
	 * constructor
	 */
	function __construct() {
		$this->options = get_option( 'expand_divi' );
		add_filter( 'the_content', array( $this, 'expand_divi_output_single_post_tags' ) );
	}

	/**
	 * adds tags html to the content
	 *
	 * @return string
	 */
	public function expand_divi_output_single_post_tags( $content ) {
		$the_tags = get_the_tags();

		if ( is_singular('post') && ! empty( $the_tags ) ) {

			if ( ( $this->options['enable_post_tags'] == 1 ) ) {
				$tags_above = '<div class="expand-divi-above-tags">| ';
				
				foreach ( $the_tags as $tag ) {
					$tags_above .=  ' <a href="';
					$tags_above .= get_tag_link($tag->term_id);
					$tags_above .= '">';
					$tags_above .= $tag->name;
					$tags_above .= '</a>';
				}

				$tags_above .= '</div>';
				$output = $tags_above . $content;
			} else if ( ( $this->options['enable_post_tags'] == 2 ) ) {
				$tags_below = '<div class="expand-divi-below-tags">' . esc_html__( 'Tags:', 'expand-divi' );

				foreach ( $the_tags as $tag ) {
					$tags_below .=  ' <a href="';
					$tags_below .= get_tag_link($tag->term_id);
					$tags_below .= '">';
					$tags_below .= $tag->name;
					$tags_below .= '</a>';
				}

				$tags_below .= '</div>';

				$output = $content . $tags_below;
			}
			return $output;
		} else {
			return $content;
		}
	}
}

new ExpandDiviSinglePostTags();