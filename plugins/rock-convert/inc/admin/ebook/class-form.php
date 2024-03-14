<?php
/**
 * The Ebook CPT Form Class.
 *
 * @package    Rock_Convert\Inc\Admin
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\inc\admin\ebook;

use Rock_Convert\Inc\Admin\Subscriber;

/**
 * Class Form
 *
 * @package Rock_Convert\inc\admin\ebook
 */
class Form {
	/**
	 * Callback from download form;
	 *
	 * Here if the email and post_id are valid it will:
	 *  * Store email in database
	 *  * Send to RD_Station if is integrated
	 *  * Send to Hubspot if is integrated
	 *  * Redirect to the PDF generated when post is saved
	 *
	 * @since 2.0.0
	 */
	public function download_form_callback() {
		if ( isset( $_POST['convert_add_meta_nonce'] )
			&& wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST['convert_add_meta_nonce'] ) ),
				'convert_add_subscriber_form_nonce'
			)
		) {
			$post      = isset( $_POST['convert_post_id'] ) ? get_post( sanitize_key( $_POST['convert_post_id'] ) ) : null;
			$email     = isset( $_POST['convert_email'] ) ? sanitize_email( wp_unslash( $_POST['convert_email'] ) ) : null;
			$permalink = get_the_permalink( $post->ID );

			$subscriber = new Subscriber( $email, $post->ID, $permalink );

			if ( $subscriber->subscribe( 'rock-convert-pdf' ) ) {

				$attatchment_path = $this->get_attatchment_path( $post->ID );

				if ( $attatchment_path ) {
					$this->redirect( $attatchment_path );
				}
			} else {
				// Email invalid.
				$error      = 'error=email-invalid';
				$permalink .= strpos( $permalink, '?' ) ? '&' : '?' . $error;
			}

			$this->redirect( $permalink );
		}
	}

	/**
	 * Get post attatchment with PDF
	 *
	 * @param int $post_id ID from post.
	 *
	 * @since 2.0.0
	 * @return false|string
	 */
	public function get_attatchment_path( $post_id ) {
		$attatchment_id = get_post_meta(
			$post_id,
			'_rock_convert_ebook_attatchment_id',
			true
		);

		return wp_get_attachment_url( $attatchment_id );
	}

	/**
	 * Redirect
	 *
	 * @param string $path URL.
	 *
	 * @since    2.0.0
	 */
	public function redirect( $path ) {
		wp_safe_redirect( $path );
		exit();
	}
}
