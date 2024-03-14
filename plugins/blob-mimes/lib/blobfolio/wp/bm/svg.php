<?php
/**
 * Lord of the Files: SVG Wrapper
 *
 * This plugin encourages the use of DOMDocument to parse SVG (XML)
 * content, but for environments that do not have that PHP extension
 * installed, there is a decent fallback.
 *
 * This file picks the best and calls it "svg".
 *
 * Public methods:
 * - ::get_dimensions()
 * - ::sanitize()
 *
 * phpcs:disable
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm;

// DomDocument is better, but not everyone can do it.
if (\class_exists('DOMDocument') && \class_exists('DOMXPath')) {
	class svg extends svg\svg_dom {}
}
// Otherwise there's a decent fallback.
else {
	class svg extends svg\svg_fallback {}
}
