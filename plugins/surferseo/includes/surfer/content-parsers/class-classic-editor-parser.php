<?php
/**
 *  Parser that prepare data for Classic Editor.
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Surfer\Content_Parsers;

use DOMDocument;

/**
 * Object that imports data from different sources into WordPress.
 */
class Classic_Editor_Parser extends Content_Parser {


	/**
	 * Parse content from Surfer to Classic Editor.
	 *
	 * @param string $content - Content from Surfer.
	 * @return string
	 */
	public function parse_content( $content ) {

		parent::parse_content( $content );

		$content = $this->parse_img_for_classic_editor( $content );
		return $content;
	}

	/**
	 * Parse images for classic editor.
	 *
	 * @param string $content - whole content.
	 * @return string content where <img> URLs are corrected to media library.
	 */
	private function parse_img_for_classic_editor( $content ) {

		$doc = new DOMDocument();
		$doc->loadHTML( $content );

		$h1s = $doc->getElementsByTagName( 'h1' );

		foreach ( $h1s as $h1 ) {
			$h1_text = $this->get_inner_html( $h1 );
			if ( wp_strip_all_tags( $h1_text ) === $this->title ) {
				// @codingStandardsIgnoreLine
				$h1_string = $h1->ownerDocument->saveXML( $h1 );
				$content   = str_replace( $h1_string, '', $content );
			}
		}

		$tags = $doc->getElementsByTagName( 'img' );

		foreach ( $tags as $tag ) {
			$image_url = $tag->getAttribute( 'src' );
			$image_alt = $tag->getAttribute( 'alt' );

			$media_library_image_url = $this->download_img_to_media_library( $image_url, $image_alt );

			$content = str_replace( $image_url, $media_library_image_url, $content );
		}

		return $content;
	}
}
