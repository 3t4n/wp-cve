<?php
/**
 *  Parser that prepare data for Gutenberg
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Surfer\Content_Parsers;

use DOMDocument;

/**
 * Object that imports data from different sources into WordPress.
 */
class Gutenberg_Parser extends Content_Parser {

	/**
	 * Parse content from Surfer to Gutenberg Editor.
	 *
	 * @param string $content  - Content from Surfer.
	 * @return string
	 */
	public function parse_content( $content ) {

		parent::parse_content( $content );

		$content = wp_unslash( $content );

		$doc = new DOMDocument();

		$utf8_fix_prefix = '<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /></head><body>';
		$utf8_fix_suffix = '</body></html>';

		$doc->loadHTML( $utf8_fix_prefix . $content . $utf8_fix_suffix, LIBXML_HTML_NODEFDTD | LIBXML_SCHEMA_CREATE );

		$parsed_content = '';

		$this->parse_dom_node( $doc, $parsed_content );

		return $parsed_content;
	}

	/**
	 * Function interates by HTML tags in provided content.
	 *
	 * @param DOMNode|DOMDocument|DOMElement $parent_node - node to parse.
	 * @param string                         $content     - reference to content variable, to store Gutenberg output.
	 * @return void
	 */
	private function parse_dom_node( $parent_node, &$content ) {
		// @codingStandardsIgnoreLine
		foreach ( $parent_node->childNodes as $node ) {

			// @codingStandardsIgnoreLine
			$execute_for_child = $this->check_if_execute_recurrence( $node->nodeName );

			$content .= $this->parse_certain_node_type( $node );

			if ( $execute_for_child && $node->hasChildNodes() ) {
				$this->parse_dom_node( $node, $content );
			}
		}
	}

	/**
	 * Function checks if we want to dig deep into content scheme.
	 *
	 * @param string $node_type - name of the node, example: ul, p, h1.
	 * @return bool
	 */
	private function check_if_execute_recurrence( $node_type ) {

		$execute_for_child = true;

		if ( 'ul' === $node_type ) {
			$execute_for_child = false;
		}

		if ( 'ol' === $node_type ) {
			$execute_for_child = false;
		}

		if ( 'blockquote' === $node_type ) {
			$execute_for_child = false;
		}

		return $execute_for_child;
	}

	/**
	 * Function prepares attributes and run correct parser function for certain node type.
	 *
	 * @param DOMNode|DOMDocument|DOMElement $node - node to parse.
	 * @return string
	 */
	private function parse_certain_node_type( $node ) {

		$all_attributes = $this->parse_node_attributes( $node );
		$attributes     = $all_attributes['attributes'];

		// @codingStandardsIgnoreLine
		$node_name = $node->nodeName;

		$gutenberg_attribute = '';
		if ( isset( $all_attributes['gutenberg_block_specific'][ $node_name ] ) ) {
			$gutenberg_attribute = $all_attributes['gutenberg_block_specific'][ $node_name ];
		}

		if ( 'p' === $node_name ) {
			return $this->parse_node_p( $node, $attributes, $gutenberg_attribute );
		}

		if ( 0 === strpos( $node_name, 'h' ) && 'html' !== $node_name && 'hr' !== $node_name && 'head' !== $node_name ) {
			return $this->parse_node_h( $node, $attributes, $gutenberg_attribute );
		}

		if ( 'img' === $node_name ) {
			return $this->parse_node_img( $node, $attributes, $gutenberg_attribute );
		}

		if ( 'ul' === $node_name ) {
			return $this->parse_node_ul( $node, $attributes, $gutenberg_attribute );
		}

		if ( 'ol' === $node_name ) {
			return $this->parse_node_ol( $node, $attributes, $gutenberg_attribute );
		}

		if ( 'hr' === $node_name ) {
			return $this->parse_node_hr( $node, $attributes, $gutenberg_attribute );
		}

		if ( 'blockquote' === $node_name ) {
			return $this->parse_node_blockquote( $node, $attributes, $gutenberg_attribute );
		}

		if ( 'pre' === $node_name ) {
			return $this->parse_node_code( $node, $attributes, $gutenberg_attribute );
		}
	}

	/**
	 * Functions prepare attributes for HTML and Gutendber tags.
	 *
	 * @param DOMNode|DOMDocument|DOMElement $node - node to parse.
	 * @return array
	 */
	private function parse_node_attributes( $node ) {

		$attributes_array          = array();
		$block_specific_attributes = array();

		if ( $node->hasAttributes() ) {

			// @codingStandardsIgnoreLine
			$node_name  = $node->nodeName;

			foreach ( $node->attributes as $attr ) {

				// @codingStandardsIgnoreLine
				$attr_name  = $attr->nodeName;
				// @codingStandardsIgnoreLine
				$attr_value = $attr->nodeValue;

				if ( 'contenteditable' === $attr_name ) {
					continue;
				}

				$attributes_array[ $attr_name ] = $attr_value;
			}

			if ( in_array( $node_name, array( 'h2', 'h3', 'h4', 'h5', 'h6', 'h7' ), true ) && 'style' === $attr_name ) {
				$this->parse_h_special_attributes( $attr_value, $attributes_array, $block_specific_attributes );
			}

			if ( 'p' === $node_name && 'style' === $attr_name ) {
				$this->parse_p_special_attributes( $attr_value, $attributes_array, $block_specific_attributes );
			}
		}

		return array(
			'attributes'               => $attributes_array,
			'gutenberg_block_specific' => $block_specific_attributes,
		);
	}

	/**
	 * Parse attributes that are specific for the <p> tag.
	 *
	 * @param string $styles_string             - string with all styles from style attribute.
	 * @param array  $attributes_array          - reference to final array of attributes.
	 * @param array  $block_specific_attributes - reference to final array of gutenberg attributes.
	 */
	private function parse_p_special_attributes( $styles_string, &$attributes_array, &$block_specific_attributes ) {

		$styles       = explode( ';', $styles_string );
		$styles_assoc = array();
		foreach ( $styles as $style ) {
			$s                     = explode( ':', $style );
			$styles_assoc[ $s[0] ] = trim( $s[1] );
		}

		if ( key_exists( 'text-align', $styles_assoc ) ) {
			$block_specific_attributes['p'] = '{"align":"' . $styles_assoc['text-align'] . '"} ';

			if ( ! isset( $attributes_array['class'] ) ) {
				$attributes_array['class'] = '';
			}

			$attributes_array['class'] .= ' has-text-align-' . $styles_assoc['text-align'];
		}
	}

	/**
	 * Parse attributes that are specific for the <h2-7> tag.
	 *
	 * @param string $styles_string             - string with all styles from style attribute.
	 * @param array  $attributes_array          - reference to final array of attributes.
	 * @param array  $block_specific_attributes - reference to final array of gutenberg attributes.
	 */
	private function parse_h_special_attributes( $styles_string, &$attributes_array, &$block_specific_attributes ) {

		$styles       = explode( ';', $styles_string );
		$styles_assoc = array();
		foreach ( $styles as $style ) {
			$s                     = explode( ':', $style );
			$styles_assoc[ $s[0] ] = trim( $s[1] );
		}

		if ( key_exists( 'text-align', $styles_assoc ) ) {

			$styles_assoc['text-align'] = str_replace( 'start', 'left', $styles_assoc['text-align'] );
			$styles_assoc['text-align'] = str_replace( 'end', 'right', $styles_assoc['text-align'] );

			$block_specific_attributes['h'] = '"textAlign":"' . $styles_assoc['text-align'] . '"';

			if ( ! isset( $attributes_array['class'] ) ) {
				$attributes_array['class'] = '';
			}

			$attributes_array['class'] .= ' has-text-align-' . $styles_assoc['text-align'];
			unset( $attributes_array['style'] );
		}
	}

	/**
	 * Parses <p> node.
	 *
	 * @param DOMNode|DOMDocument|DOMElement $node                - node to parse.
	 * @param array                          $attributes          - HTML attributes for the node, example: class="" src="".
	 * @param string                         $gutenberg_attribute - Attributes for Gutenberg tag.
	 * @return string
	 */
	private function parse_node_p( $node, $attributes, $gutenberg_attribute ) {

		$attributes = $this->glue_attributes( $attributes );

		$node_content = $this->get_inner_html( $node );
		if ( '' === $node_content ) {
			return '';
		}

		$attributes = str_replace( 'has-text-align-start', '', $attributes );

		$content  = '<!-- wp:paragraph ' . $gutenberg_attribute . '-->' . PHP_EOL;
		$content .= '<p' . $attributes . '>' . $node_content . '</p>' . PHP_EOL;
		$content .= '<!-- /wp:paragraph -->' . PHP_EOL . PHP_EOL;

		return $content;
	}

	/**
	 * Parses <h1-6> nodes
	 *
	 * @param DOMNode|DOMDocument|DOMElement $node                - node to parse.
	 * @param array                          $attributes          - HTML attributes for the node, example: class="" src="".
	 * @param string                         $gutenberg_attribute - Attributes for Gutenberg tag.
	 * @return string
	 */
	private function parse_node_h( $node, $attributes, $gutenberg_attribute ) {

		$h1_text = $this->get_inner_html( $node );

		// @codingStandardsIgnoreLine
		$node_name = $node->nodeName;

		if ( 'h1' === $node_name && wp_strip_all_tags( $h1_text ) === $this->title ) {
			return '';
		}

		$attributes = $this->glue_attributes( $attributes );

		$header_size = str_replace( 'h', '', $node_name );

		$content  = '<!-- wp:heading {"level":' . $header_size . '} -->' . PHP_EOL;
		$content .= '<h' . $header_size . ' ' . $attributes . '>' . $h1_text . '</h' . $header_size . '>' . PHP_EOL;
		$content .= '<!-- /wp:heading -->' . PHP_EOL . PHP_EOL;

		return $content;
	}

	/**
	 * Parses <img> node.
	 *
	 * @param DOMNode|DOMDocument|DOMElement $node                - node to parse.
	 * @param array                          $attributes          - HTML attributes for the node, example: class="" src="".
	 * @param string                         $gutenberg_attribute - Attributes for Gutenberg tag.
	 * @return string
	 */
	private function parse_node_img( $node, $attributes, $gutenberg_attribute ) {

		$image_url = '';
		$image_alt = '';

		if ( isset( $attributes['src'] ) && ! empty( $attributes['src'] ) ) {
			$image_url = $attributes['src'];
		}

		if ( isset( $attributes['alt'] ) && ! empty( $attributes['alt'] ) ) {
			$image_alt = $attributes['alt'];
		}

		$image_url         = $this->download_img_to_media_library( $image_url, $image_alt );
		$attributes['src'] = $image_url;

		$attributes = $this->glue_attributes( $attributes );

		$content  = '<!-- wp:image -->' . PHP_EOL;
		$content .= '<figure class="wp-block-image">' . PHP_EOL;
		$content .= '<img' . $attributes . ' />' . PHP_EOL;
		$content .= '</figure>' . PHP_EOL;
		$content .= '<!-- /wp:image -->' . PHP_EOL . PHP_EOL;

		return $content;
	}

	/**
	 * Parses <ul> node.
	 *
	 * @param DOMNode|DOMDocument|DOMElement $node                - node to parse.
	 * @param array                          $attributes          - HTML attributes for the node, example: class="" src="".
	 * @param string                         $gutenberg_attribute - Attributes for Gutenberg tag.
	 * @return string
	 */
	private function parse_node_ul( $node, $attributes, $gutenberg_attribute ) {

		$node_content = $this->get_inner_html( $node );
		if ( '' === $node_content ) {
			return '';
		}

		$content  = '<!-- wp:list -->' . PHP_EOL;
		$content .= '<ul>' . PHP_EOL;
		$content .= $node_content . PHP_EOL;
		$content .= '</ul>' . PHP_EOL;
		$content .= '<!-- /wp:list -->' . PHP_EOL . PHP_EOL;

		return $content;
	}

	/**
	 * Parses <ol> node.
	 *
	 * @param DOMNode|DOMDocument|DOMElement $node                - node to parse.
	 * @param array                          $attributes          - HTML attributes for the node, example: class="" src="".
	 * @param string                         $gutenberg_attribute - Attributes for Gutenberg tag.
	 * @return string
	 */
	private function parse_node_ol( $node, $attributes, $gutenberg_attribute ) {

		$node_content = $this->get_inner_html( $node );
		if ( '' === $node_content ) {
			return '';
		}

		$content  = '<!-- wp:list {"ordered":true} -->' . PHP_EOL;
		$content .= '<ol>' . PHP_EOL;
		$content .= $node_content . PHP_EOL;
		$content .= '</ol>' . PHP_EOL;
		$content .= '<!-- /wp:list -->' . PHP_EOL . PHP_EOL;

		return $content;
	}

	/**
	 * Parses <hr> node.
	 *
	 * @param DOMNode|DOMDocument|DOMElement $node                - node to parse.
	 * @param array                          $attributes          - HTML attributes for the node, example: class="" src="".
	 * @param string                         $gutenberg_attribute - Attributes for Gutenberg tag.
	 * @return string
	 */
	private function parse_node_hr( $node, $attributes, $gutenberg_attribute ) {

		$attributes = $this->glue_attributes( $attributes );

		$content  = '<!-- wp:separator -->' . PHP_EOL;
		$content .= '<hr class="wp-block-separator"' . $attributes . ' />' . PHP_EOL;
		$content .= '<!-- /wp:separator -->' . PHP_EOL . PHP_EOL;

		return $content;
	}

	/**
	 * Parses <blockquote> node.
	 *
	 * @param DOMNode|DOMDocument|DOMElement $node                - node to parse.
	 * @param array                          $attributes          - HTML attributes for the node, example: class="" src="".
	 * @param string                         $gutenberg_attribute - Attributes for Gutenberg tag.
	 * @return string
	 */
	private function parse_node_blockquote( $node, $attributes, $gutenberg_attribute ) {

		$node_content = $this->get_inner_html( $node );
		if ( '' === $node_content ) {
			return '';
		}

		$content  = '<!-- wp:quote -->' . PHP_EOL;
		$content .= '<blockquote class="wp-block-quote">' . $node_content . '</blockquote>' . PHP_EOL;
		$content .= '<!-- /wp:quote -->' . PHP_EOL . PHP_EOL;

		return $content;
	}

	/**
	 * Parses <pre> node.
	 *
	 * @param DOMNode|DOMDocument|DOMElement $node                - node to parse.
	 * @param array                          $attributes          - HTML attributes for the node, example: class="" src="".
	 * @param string                         $gutenberg_attribute - Attributes for Gutenberg tag.
	 * @return string
	 */
	private function parse_node_code( $node, $attributes, $gutenberg_attribute ) {

		$node_content = $this->get_inner_html( $node );
		if ( '' === $node_content ) {
			return '';
		}

		$content  = '<!-- wp:code -->' . PHP_EOL;
		$content .= '<pre class="wp-block-code">' . PHP_EOL;
		$content .= $node_content . PHP_EOL;
		$content .= '</pre>' . PHP_EOL;
		$content .= '<!-- /wp:code -->' . PHP_EOL . PHP_EOL;

		return $content;
	}
}
