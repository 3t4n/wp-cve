<?php
/**
 * Watchful WP version test.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful\Audit\Tests;

use Watchful\Audit\Audit;

/**
 * Watchful WP version test class.
 */
class HasWpVersion extends Audit {

	/**
	 * Run the test.
	 *
	 * @return mixed
	 */
	public function run() {
		$body = $this->get_body( get_bloginfo( 'wpurl' ) );

		if ( ! $body ) {
			return $this->response->send_ko();
		}

		$meta_tags     = $this->get_meta_tags( $body );
		$matching_tags = array();

		foreach ( $meta_tags as $meta_tag ) {
			if ( stripos( $meta_tag, 'generator' ) !== false &&
				stripos( $meta_tag, get_bloginfo( 'version' ) ) !== false ) {
				$matching_tags[] = array(
					'value'   => base64_encode( $meta_tag ),
					'encoder' => 'base64',
				);
			}
		}

		if ( ! empty( $matching_tags ) ) {
			return $this->response->send_ko( $matching_tags );
		}

		return $this->response->send_ok();
	}

	/**
	 * Get the html body from the given url.
	 *
	 * @param string $url The page URL.
	 *
	 * @return mixed
	 */
	private function get_body( $url ) {
		if ( ! class_exists( 'WP_Http' ) ) {
			require ABSPATH . WPINC . '/class-http.php';
		}

		$http     = new \WP_Http();
		$response = (array) $http->request( $url );
		return wp_remote_retrieve_body( $response );
	}

	/**
	 * Get the meta tags from the given HTML.
	 *
	 * @param string $html The HTML to check.
	 *
	 * @return mixed
	 */
	private function get_meta_tags( $html ) {
		// Extract content in <head> tags.
		$start = strpos( $html, '<head' );
		$len   = strpos( $html, 'head>', $start + strlen( '<head' ) );
		$html  = substr( $html, $start, $len - $start + strlen( 'head>' ) );

		// Find all Meta Tags.
		preg_match_all( '#<meta([^>]*)>#si', $html, $matches );
		return $matches[0];
	}
}
