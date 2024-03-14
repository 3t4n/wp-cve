<?php

namespace PDFEmbedder\Admin;

use WP_Post;
use WP_User;
use PDFEmbedder\Helpers\Links;

/**
 * Extend default WordPress Media library.
 *
 * @since 4.7.0
 */
class MediaLibrary {

	/**
	 * Assign all hooks to proper places.
	 *
	 * @since 4.7.0
	 */
	public function hooks() {

		add_filter( 'attachment_fields_to_edit', [ $this, 'attachment_fields_to_edit' ], 10, 2 );

		add_filter( 'upload_mimes', [ $this, 'add_pdf_to_upload_mimes' ], 10, 2 );

		add_filter( 'post_mime_types', [ $this, 'pdfemb_post_mime_types' ] );
	}

	/**
	 * Add additional fields to the "Attachment details" media popup/screen.
	 *
	 * @since 4.7.0
	 *
	 * @param array   $form_fields An array of attachment form fields.
	 * @param WP_Post $post        The WP_Post attachment object.
	 */
	public function attachment_fields_to_edit( array $form_fields, WP_Post $post ): array {

		if ( $post->post_mime_type !== 'application/pdf' ) {
			return $form_fields;
		}

		$form_fields['pdfemb-upgrade'] = [
			'input' => 'html',
			'html'  => sprintf(
				wp_kses( /* translators: %s - URL to wp-pdf.com page. */
					__( 'Track downloads and views with <a href="%s" target="_blank">PDF Embedder Premium</a>.', 'pdf-embedder' ),
					[
						'a' => [
							'href'   => [],
							'target' => [],
						],
					]
				),
				Links::get_upgrade_link( 'Media Library', 'Downloads / Views' )
			),
			'label' => __( 'Downloads / Views', 'pdf-embedder' ),
		];

		return $form_fields;
	}

	/**
	 * Add PDF mime type to the list of allowed mime types.
	 *
	 * @since 4.7.0
	 *
	 * @param array            $mimes Mime types keyed by the file extension regex corresponding to those types.
	 * @param int|WP_User|null $user  User ID, User object or null if not provided (indicates current user).
	 */
	public function add_pdf_to_upload_mimes( array $mimes, $user ): array { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed

		$mimes['pdf'] = 'application/pdf';

		return $mimes;
	}

	/**
	 * Filter for PDFs in Media Gallery.
	 *
	 * @since 4.7.0
	 *
	 * @param array $post_mime_types Default list of post mime types.
	 */
	public function pdfemb_post_mime_types( array $post_mime_types ): array {

		$post_mime_types['application/pdf'] = [
			__( 'PDFs', 'pdf-embedder' ),
			__( 'Manage PDFs', 'pdf-embedder' ),
			/* translators: %s - number of PDF files. */
			_n_noop(
				'PDF <span class="count">(%s)</span>',
				'PDFs <span class="count">(%s)</span>',
				'pdf-embedder'
			),
		];

		return $post_mime_types;
	}
}
