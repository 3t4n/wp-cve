<?php

namespace PDFEmbedder\Shortcodes;

use PDFEmbedder\Helpers\Links;
use PDFEmbedder\Helpers\Assets;

/**
 * Main class for the [pdf-embedder] shortcode.
 *
 * @since 4.7.0
 */
class PdfEmbedder extends Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @since 4.7.0
	 *
	 * @var string
	 */
	const TAG = 'pdf-embedder';

	/**
	 * Shortcode main render method.
	 *
	 * @since 4.7.0
	 *
	 * @param array  $atts    Shortcode attributes.
	 * @param string $content Shortcode content, that is inside the shortcode opening and closing tags.
	 */
	public function render( array $atts, string $content = '' ): string { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		$a = $this->get_processed_atts( $atts );

		$escaped_url = esc_url( set_url_scheme( $a['url'] ) );

		if ( empty( $a['url'] ) || empty( $escaped_url ) ) {
			return '<!-- PDF Embedder: Please provide an "URL" attribute in your shortcode. -->';
		}

		$this->enqueue_inline_assets();

		$title = ! empty( $a['title'] ) ? $a['title'] : Links::make_title_from_url( $a['url'] );

		$html_node   = '';
		$extra_style = '';

		/*
		 * Extra styles based on the PDF width and height settings.
		 */
		if ( is_numeric( $a['width'] ) ) {
			$extra_style .= 'width:' . (int) $a['width'] . 'px;';
		} elseif ( $a['width'] !== 'max' && $a['width'] !== 'auto' ) {
			$a['width'] = 'max';
		}

		if ( is_numeric( $a['height'] ) ) {
			$extra_style .= 'height:' . (int) $a['height'] . 'px;';
		} elseif ( $a['height'] !== 'max' && $a['height'] !== 'auto' ) {
			$a['height'] = 'max';
		}

		/**
		 * Filter the HTML attributes for the PDF Embedder shortcode.
		 *
		 * @since 4.7.0
		 *
		 * @param array $html_attr HTML attributes.
		 */
		$html_attr = apply_filters(
			'pdfemb_shortcode_html_attributes',
			[
				'class'              => 'pdfemb-viewer',
				'style'              => $extra_style,
				'data-width'         => $a['width'],
				'data-height'        => $a['height'],
				'data-toolbar'       => $a['toolbar'],
				'data-toolbar-fixed' => $a['toolbarfixed'],
			]
		);

		$html_node .= '<a href="' . esc_url( set_url_scheme( $a['url'] ) ) . '"';

		foreach ( $html_attr as $key => $value ) {
			if ( ! is_scalar( $key ) || ! is_scalar( $value ) ) {
				continue;
			}

			$html_node .= ' ' . sanitize_key( (string) $key ) . '="' . esc_attr( (string) $value ) . '"';
		}

		$html_node .= '>';

		$html_node .= esc_html( $title );

		$html_node .= '</a>';

		// Process content that might have been added inside the shortcode.
		if ( ! empty( $content ) ) {
			$html_node .= do_shortcode( $content );
		}

		return $html_node;
	}

	/**
	 * Get processed shortcode attributes, filtered and with defaults.
	 *
	 * @since 4.7.0
	 *
	 * @param array $atts Shortcode attributes.
	 */
	protected function get_processed_atts( array $atts ): array {

		$options = pdf_embedder()->options()->get();

		/**
		 * Filter shortcode and block attributes before rendering on the front-end.
		 *
		 * @since 1.0.0
		 *
		 * @param array $atts Shortcode/block attributes.
		 */
		$filtered = (array) apply_filters( 'pdfemb_filter_shortcode_attrs', $atts );

		// Insert all defaults for the shortcode.
		$defaults = [
			'url'          => '',
			'title'        => '',
			'width'        => $options['pdfemb_width'],
			'height'       => $options['pdfemb_height'],
			'toolbar'      => $options['pdfemb_toolbar'],
			'toolbarfixed' => $options['pdfemb_toolbarfixed'],
		];

		$processed = shortcode_atts( $defaults, $filtered, static::TAG );

		if ( ! in_array( $processed['toolbar'], [ 'top', 'bottom', 'both', 'none' ], true ) ) {
			$processed['toolbar'] = 'bottom';
		}

		if ( ! in_array( $processed['toolbarfixed'], [ 'on', 'off' ], true ) ) {
			$processed['toolbarfixed'] = 'off';
		}

		return $processed;
	}

	/**
	 * Inline scripts and styles for the shortcode.
	 *
	 * @since 4.7.0
	 */
	protected function enqueue_inline_assets() { // phpcs:ignore WPForms.PHP.HooksMethod.InvalidPlaceForAddingHooks

		// Assets should be enqueued only once on a page.
		// They are shared across all instances of the shortcode.
		static $is_enqueued = false;

		if ( $is_enqueued ) {
			return;
		}

		$is_enqueued = true;

		wp_enqueue_script( 'pdfemb_embed_pdf' );

		add_filter(
			'script_loader_tag',
			static function ( $tag, $handle, $src ) {
				if ( $handle === 'pdfemb_embed_pdf' ) {
					// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
					$tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
				}

				return $tag;
			},
			10,
			3
		);

		wp_enqueue_script( 'pdfemb_pdfjs' );

		wp_enqueue_style(
			'pdfemb_embed_pdf_css',
			Assets::url( 'css/pdfemb-embed-pdf.css', true ),
			[],
			Assets::ver()
		);
	}
}
