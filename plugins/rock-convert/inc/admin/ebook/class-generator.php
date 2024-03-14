<?php
/**
 * The Ebook CPT Generator Class.
 *
 * @package    Rock_Convert\Inc\Admin
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\Inc\Admin\Ebook;

use Rock_Convert\Inc\Admin\Utils;
use Rock_Convert\Inc\Libraries\PDF\Ebook;

/**
 * Class Generator
 *
 * This class add a function to save_post hook.
 *
 * It will create a PDF file based on post title, content and featured image.
 *
 * Only posts with the option "_rock_convert_enable_ebook" with value true will
 * trigger this action
 *
 * @package    Rock_Convert\Inc\Ebook
 * @link       https://rockcontent.com
 * @since      2.0.0
 *
 * @author     Rock Content
 */
class Generator {

	/**
	 * Post constructor.
	 */
	public function __construct() {
		add_action( 'save_post', array( $this, 'create_post_pdf' ), 20 );
	}

	/**
	 * Create a PDF file based on post title, content and featured image
	 *
	 * The PDF file is uploaded to wp-uploads folder and saved as an attachment
	 * to the post.
	 *
	 * @param int $post_id ID from post.
	 */
	public function create_post_pdf( $post_id ) {
		// If this is just a revision, don't generate a PDF.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Check if user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check if not an autosave.
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		$this->remove_old_ebooks( $post_id );

		// If ebook generation is disabled for this post, don't generate a PDF.
		if ( ! $this->check_ebook_enabled( $post_id ) ) {
			return;
		}

		$post = get_post( $post_id );

		$title   = $post->post_title;
		$image   = get_the_post_thumbnail_url( $post_id, 'large' );
		$content = $this->filter_content( $post->post_content );

		$pdf = new Ebook( $post_id, $title, $content, $image );

		try {
			$pdf->generate();
		} catch ( \Exception $e ) {

			Utils::logError( '[class-generator.php]: ' . $e );
			/**
			 * Woops. PDF not generated :(
			 *
			 * Removing attatchment ID
			 */
			delete_post_meta( $post_id, '_rock_convert_ebook_attatchment_id' );

			/**
			 * Remove option to generate PDF
			 */
			delete_post_meta( $post_id, '_rock_convert_enable_ebook' );
		}
	}

	/**
	 * Delete ebook attatchment associated with the post
	 *
	 * @param int $post_id ID from post.
	 */
	private function remove_old_ebooks( $post_id ) {
		$ebook_attatchment_id = get_post_meta(
			$post_id,
			'_rock_convert_ebook_attatchment_id',
			true
		);

		if ( ! empty( $ebook_attatchment_id ) ) {
			wp_delete_attachment( $ebook_attatchment_id );
		}
	}

	/**
	 * Check if post ebook generation option is enabled
	 *
	 * @param int $post_id ID from post.
	 *
	 * @return mixed
	 */
	public function check_ebook_enabled( $post_id ) {
		$enable_ebook = get_post_meta(
			$post_id,
			'_rock_convert_enable_ebook',
			true
		);

		return filter_var( $enable_ebook, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Prepare content to be rendered in PDF
	 *
	 * Add paragraphs when there is only space (WP native function).
	 * Remove shortcodes.
	 * Whitelist tags.
	 *
	 * @param string $raw_content Raw content.
	 *
	 * @return string
	 */
	public function filter_content( $raw_content ) {
		$tags = array(
			'p',
			'a',
			'img',
			'span',
			'del',
			'b',
			'u',
			'i',
			'em',
			'hr',
			'strong',
			'ul',
			'ol',
			'li',
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
		);

		$lambda = function ( $value ) {
			return '<' . $value . '>';
		};

		$allowed_tags = implode( '', array_map( $lambda, $tags ) );

		/**
		 * Replaces double line-breaks with paragraph elements.
		 */
		$content = wpautop( $raw_content );

		/**
		 * Remove shortcodes
		 */
		$content = strip_shortcodes( $content );

		$dom = new \DOMDocument( '1.0', 'UTF-8' );
		$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );

		$images = $dom->getElementsByTagName( 'img' );
		foreach ( $images as $img ) {
			$img->setAttribute( 'width', '750' );

			if ( $img->hasAttribute( 'height' ) ) {
				$img->removeAttribute( 'height' );
			}
		}

		$content = $dom->saveHTML();

		/**
		 * Whitelist HTML tags
		 */
		return strip_tags( $content, $allowed_tags );
	}

	/**
	 * Display an error
	 *
	 * @return void
	 */
	public function error_generating_pdf() {
		$class   = 'notice notice-error';
		$message = __( 'Irks! An error has occurred.', 'rock-convert' );

		printf(
			'<div class="%1$s"><p>%2$s</p></div>',
			esc_attr( $class ),
			esc_html( $message )
		);
	}
}
