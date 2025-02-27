<?php
/**
 * Lord of the Files - SVG DOMDocument
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm\svg;

use DOMDocument;
use DOMXPath;
use Throwable;



abstract class svg_dom extends svg_base {

	/**
	 * Sanitize an SVG
	 *
	 * @param string $svg SVG code or path.
	 * @param bool $header Append header.
	 * @return string|bool Sanitized SVG code. False on failure.
	 */
	public static function sanitize(string $svg='', bool $header=true) {
		// Importing itself will ensure the XML is at least
		// vaguely valid. It will also adjust tag casing,
		// quote styles, etc.
		if (false === ($dom = static::import($svg))) {
			return false;
		}

		// Whitelist.
		$allowed_tags = static::get_allowed_tags();
		$allowed_attributes = static::get_allowed_attributes();
		$iri_attributes = static::get_iri_attributes();
		$xpath = new DOMXPath($dom);

		// Validate tags.
		$tags = $dom->getElementsByTagName('*');

		for ($x = $tags->length - 1; $x >= 0; $x--) {
			$tag = $tags->item($x);

			$tag_name = \mb_strtolower($tag->tagName, 'UTF-8');

			// The tag might be namespaced (ns:tag). We'll allow it if
			// the tag is allowed.
			if (
				false !== \strpos($tag_name, ':') &&
				! \in_array($tag_name, $allowed_tags, true)
			) {
				$tag_name = \explode(':', $tag_name);
				$tag_name = $tag_name[1];
			}

			// Bad tag: not whitelisted.
			if (! \in_array($tag_name, $allowed_tags, true)) {
				static::remove_node($tag);
				continue;
			}

			// If this is a <style> tag, we want to decode entities.
			// Thanks XML!
			if ('style' === $tag_name) {
				$style = \strip_tags(static::sanitize_attribute_value($tag->textContent));
				$tag->textContent = $style;
			}

			// Use XPath for attributes, as $tag->attributes will skip
			// anything namespaced. Note: We aren't focusing on
			// actual Namespaces here, that comes later.
			$attributes = $xpath->query('.//@*', $tag);
			for ($y = $attributes->length - 1; $y >= 0; $y--) {
				$attribute = $attributes->item($y);

				$attribute_name = \mb_strtolower($attribute->nodeName, 'UTF-8');

				// Could be namespaced.
				if (
					! \in_array($attribute_name, $allowed_attributes, true) &&
					false !== ($start = \mb_strpos($attribute_name, ':', 0, 'UTF-8'))
				) {
					$attribute_name = \mb_substr(
						$attribute_name,
						$start + 1,
						null,
						'UTF-8'
					);
				}

				// Bad attribute: not whitelisted.
				// data-* is implicitly whitelisted.
				if (
					! \preg_match('/^data\-/', $attribute_name) &&
					! \in_array($attribute_name, $allowed_attributes, true)
				) {
					$tag->removeAttribute($attribute->nodeName);
					continue;
				}

				// Validate values.
				$attribute_value = static::sanitize_attribute_value($attribute->value);

				// Validate protocols.
				// IRI attributes get the full KSES treatment.
				$iri = false;
				if (\in_array($attribute_name, $iri_attributes, true)) {
					$iri = true;
					$attribute_value = static::sanitize_iri_value($attribute_value);
				} // For others, we are specifically interested in removing scripty bits.
				elseif (\preg_match('/(?:\w+script):/xi', $attribute_value)) {
					$attribute_value = '';
				}

				// Update it.
				if ($attribute_value !== $attribute->value) {
					if ($iri) {
						$tag->removeAttribute($attribute->nodeName);
					} else {
						$tag->setAttribute($attribute->nodeName, $attribute_value);
					}
				}
			}
		} // Each tag.

		// Once more through the tags to find namespaces.
		$tags = $dom->getElementsByTagName('*');
		for ($x = 0; $x < $tags->length; $x++) {
			$tag = $tags->item($x);
			$nodes = $xpath->query('namespace::*', $tag);
			for ($y = 0; $y < $nodes->length; $y++) {
				$node = $nodes->item($y);

				$node_name = \mb_strtolower($node->nodeName, 'UTF-8');

				// Not xmlns?
				if (! \preg_match('/^xmlns:/', $node_name)) {
					static::remove_namespace($dom, $node->localName);
					continue;
				}

				// Validate values.
				$node_value = static::sanitize_attribute_value($node->nodeValue);
				$node_value = static::sanitize_iri_value($node_value);

				// Remove invalid.
				if (! \strlen($node_value)) {
					static::remove_namespace($dom, $node->localName);
				}
			}
		}

		// Back to a string!
		if (false === ($svg = static::export($dom))) {
			return false;
		}

		// Sanitize CSS values (e.g. foo="url(...)").
		$svg = \preg_replace_callback(
			'/url\s*\((.*)\s*\)/Ui',
			array(static::class, 'callback_sanitize_css_iri'),
			$svg
		);

		// Make sure if xmlns="" exists, it is correct. Can't alter
		// that with DOMDocument, and there is only one proper value.
		$svg = \preg_replace(
			'/xmlns\s*=\s*"[^"]*"/',
			'xmlns="' . static::XMLNS_NAMESPACE . '"',
			$svg
		);

		// Let's crunch some whitespace!
		$svg = \preg_replace('/\s+/u', ' ', $svg);
		$svg = \str_replace('> <', '><', $svg);

		// Add our headers, and we're done!
		if ($header) {
			$svg = static::XMLTAG . "\n" . static::get_doctype() . "\n$svg";
		}

		// Done!
		return $svg;
	}

	/**
	 * Import to DOMDocument object.
	 *
	 * @param string $svg SVG code or path.
	 * @return DOMDocument|bool XML object. False on failure.
	 */
	protected static function import(string $svg = '') {
		// Early bail.
		if (
			false === ($svg = static::load_svg($svg)) ||
			! \class_exists('DOMDocument')
		) {
			return false;
		}

		try {
			\libxml_use_internal_errors(true);
			if (\PHP_VERSION_ID < 80000) {
				\libxml_disable_entity_loader(true);
			}
			$dom = new DOMDocument('1.0', 'UTF-8');
			$dom->formatOutput = false;
			$dom->preserveWhiteSpace = false;
			$dom->loadXML(static::get_doctype() . "\n{$svg}");

			// Make sure there are still SVG tags.
			$svgs = $dom->getElementsByTagName('svg');
			if (! $svgs->length) {
				return false;
			}

			return $dom;
		} catch (Throwable $e) {
			\error_log($e->getMessage());
			return false;
		}

		return false;
	}

	/**
	 * Export from a DOMDocument object.
	 *
	 * @param DOMDocument $dom XML object.
	 * @return string|bool Sanitized SVG code. False on failure.
	 */
	protected static function export(DOMDocument $dom) {
		try {
			$svgs = $dom->getElementsByTagName('svg');
			if (! $svgs->length) {
				return false;
			}
			$svg = $svgs->item(0)->ownerDocument->saveXML(
				$svgs->item(0),
				\LIBXML_NOBLANKS
			);

			// Remove comments.
			$svg = static::strip_comments($svg);

			return $svg;
		} catch (Throwable $e) {
			\error_log($e->getMessage());
			return false;
		}

		return false;
	}

	/**
	 * Get SVG Dimensions
	 *
	 * @param string $svg SVG code or path.
	 * @return array|bool Dimensions. False on failure.
	 */
	public static function get_dimensions(string $svg = '') {
		if (false === ($dom = static::import($svg))) {
			return false;
		}

		try {
			$svgs = $dom->getElementsByTagName('svg');
			if (! $svgs->length) {
				return false;
			}

			$svg = $svgs->item(0);

			$width = $svg->hasAttribute('width') ? $svg->getAttribute('width') : null;
			$height = $svg->hasAttribute('height') ? $svg->getAttribute('height') : null;
			$viewbox = $svg->hasAttribute('viewBox') ? $svg->getAttribute('viewBox') : null;

			// Prefer the viewbox as it is more likely to be numeric,
			// and also more likely to reflect the real dimensions.
			if (null !== $viewbox) {
				$viewbox = \trim(\preg_replace('/\s+/', ' ', $viewbox));
				$viewbox = \explode(' ', $viewbox);
				if (\count($viewbox) === 4) {
					$viewbox[2] = (float) $viewbox[2];
					$viewbox[3] = (float) $viewbox[3];

					if ($viewbox[2] > 0 && $viewbox[3] > 0) {
						return array(
							'width'=>$viewbox[2],
							'height'=>$viewbox[3],
						);
					}
				}
			}

			// Otherwise maybe the width and height are good?
			if (\is_numeric($width) && \is_numeric($height)) {
				$width = (float) $width;
				$height = (float) $height;

				if ($width > 0 && $height > 0) {
					return array(
						'width'=>$width,
						'height'=>$height,
					);
				}
			}
		} catch (Throwable $e) {
			\error_log($e->getMessage());
			return false;
		}

		return false;
	}


	/**
	 * Remove nodes from a DOMNodeList
	 *
	 * @param DOMNodeList $nodes Nodes.
	 * @return bool True/False.
	 */
	protected static function remove_nodes(DOMNodeList $nodes) : bool {
		try {
			while ($nodes->length) {
				static::remove_node($nodes->item(0));
			}
			return true;
		} catch (Throwable $e) {
			\error_log($e->getMessage());
			return false;
		}

		return false;
	}

	/**
	 * Remove node from a DOMDocument
	 *
	 * @param DOMElement|DOMNode $node Node.
	 * @return bool True/False.
	 */
	protected static function remove_node($node) : bool {
		if (
			! \is_a($node, 'DOMElement') &&
			! \is_a($node, 'DOMNode')
		) {
			return false;
		}

		try {
			$node->parentNode->removeChild($node);
			return true;
		} catch (Throwable $e) {
			\error_log($e->getMessage());
			return false;
		}

		return false;
	}

	/**
	 * Remove namespace (and attached nodes) from a DOMDocument
	 *
	 * @param DOMDocument $dom Object.
	 * @param string $namespace Namespace.
	 * @return bool True/False.
	 */
	protected static function remove_namespace(
		DOMDocument $dom,
		string $namespace
	) : bool {
		if (! $namespace) {
			return false;
		}

		try {
			$xpath = new DOMXPath($dom);
			$nodes = $xpath->query("//*[namespace::{$namespace} and not(../namespace::{$namespace})]");
			for ($x = 0; $x < $nodes->length; $x++) {
				$node = $nodes->item($x);
				$node->removeAttributeNS(
					$node->lookupNamespaceURI($namespace),
					$namespace
				);
			}

			return true;
		} catch (Throwable $e) {
			\error_log($e->getMessage());
			return false;
		}

		return false;
	}
}

