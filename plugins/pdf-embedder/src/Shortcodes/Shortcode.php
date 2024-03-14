<?php

namespace PDFEmbedder\Shortcodes;

/**
 * Shortcode main class.
 *
 * @since 4.7.0
 */
abstract class Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @since 4.7.0
	 *
	 * @var string
	 */
	const TAG = '';

	/**
	 * Shortcode main render method.
	 *
	 * @since 4.7.0
	 *
	 * @param array  $atts    Shortcode attributes.
	 * @param string $content Shortcode content, that is inside the shortcode opening and closing tags.
	 */
	abstract public function render( array $atts, string $content = '' ): string;

	/**
	 * Inline scripts and styles for the shortcode.
	 *
	 * @since 4.7.0
	 */
	protected function enqueue_inline_assets() {

		return;
	}
}
