<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Sanitize SVG during uploading */
class BORDERLESS_SvgSanitizer {

	private $document;
	private static $whitelist_elems = array();
	private static $whitelist_attrs = array();

	function __construct() {
		global $whitelist_elems;
		global $whitelist_attrs;
		
		$this->document = new DOMDocument();
		$this->document->preserveWhiteSpace = FALSE;

		require_once 'whitelist.php';

		self::$whitelist_elems = $whitelist_elems;
		self::$whitelist_attrs = $whitelist_attrs;
	}

	function load_svg( $file ) {
		$this->document->load( $file );
	}

	function borderless_sanitize_svg() {
		$elems = $this->document->getElementsByTagName( "*" );

		for( $i = 0; $i < $elems->length; $i++ ) {
			$node = $elems->item($i);

			$tag_name = $node->tagName;
			if( in_array( $tag_name, self::$whitelist_elems ) ) {
				for( $j = 0; $j < $node->attributes->length; $j++ ) {
					$attr_name = $node->attributes->item($j)->name;
					if( !in_array( $attr_name, self::$whitelist_attrs ) ) {
						$node->removeAttribute( $attr_name );
					}
				}
			} else {
				$node->parentNode->removeChild( $node );
			}
		}
	}

	function save_svg() {
		$this->document->formatOutput = TRUE;

		return $this->document->saveXML();
	}
}

?>