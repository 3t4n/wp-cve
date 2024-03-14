<?php

namespace TotalContest\Shortcode;

/**
 * File shortcode class
 * @package TotalContest\Shortcode
 * @since   1.0.0
 */
class File extends Base {

	/**
	 * Handle shortcode.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function handle() {
		$attachmentUrl  = $this->getAttribute( 'src' );
		$attachmentId   = $this->getAttribute( 'id' );
		$attachmentName = esc_html__( 'Attachment', 'totalcontest' );

		if ( $attachmentId ):
			wp_get_attachment_url( $attachmentId );
			$attachmentName = get_the_title( $attachmentId );
		endif;

		return sprintf( '<a href="%s" target="_blank" rel="nofollow">%s</a>', esc_attr( $attachmentUrl ), esc_html( $attachmentName ) );
	}

}
