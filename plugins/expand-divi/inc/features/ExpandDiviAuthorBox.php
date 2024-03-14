<?php
/**
 * Expand Divi Author Box
 * adds the author box in single posts
 *
 * @package  ExpandDivi/ExpandDiviAuthorBox
 */

// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ExpandDiviAuthorBox {

	/**
	 * constructor
	 */
	function __construct() {
		add_filter( 'the_content', array( $this, 'expand_divi_output_author_box' ) );	
	}

	/**
	 * append author box html to the content
	 *
	 * @return string
	 */
	function expand_divi_output_author_box( $content ) {
		if ( is_singular('post') ) {
			$de_author_name = get_the_author();
			$de_author_id = get_the_author_meta('ID');
			$de_author_url = get_author_posts_url( $de_author_id );
			$de_author_avatar = get_avatar( $de_author_id );
			$de_author_description = get_the_author_meta('description');
			$de_social_fields = array( 'url', 'facebook', 'twitter', 'instagram', 'youtube', 'linkedin', 'pinterest', 'reddit' );

			$content .= '<div class="expand_divi_author_box">';
				$content .= '<div class="expand_divi_author_avatar">' . $de_author_avatar . '</div>';
				$content .= '<div class="expand_divi_author_bio_wrap">';
					$content .= '<h3 class="expand_divi_author_name"><a href="' . $de_author_url . '">' . $de_author_name . '</a></h3>';
					$content .= '<div class="expand_divi_author_bio">' . $de_author_description . '</div>';

					$content .= '<ul>';
					foreach ( $de_social_fields as $field ) {
						$field_url = get_the_author_meta( $field );
						if ( isset( $field_url ) && ! empty( $field_url ) ) {
							if ( $field == 'url' ) {
								$content .= '<li class="expand_divi_author_' . $field . '"><a target="_blank" href="'. $field_url .'"><i class="fas fa-link"></i></a></li>';
							} else {
								$content .= '<li class="expand_divi_author_' . $field . '"><a target="_blank" href="'. $field_url .'"><i class="fab fa-' . $field . '"></i></a></li>';
							}
						}
					}
					$content .= '</ul>';

				$content .= '</div>';
			$content .= '</div>';
		}

		return $content;
	}
}

new ExpandDiviAuthorBox();