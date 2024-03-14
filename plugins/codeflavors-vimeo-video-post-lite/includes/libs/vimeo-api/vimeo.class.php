<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use WP_Error;

/**
 * Class Vimeo. Shared between Vimeo_Oauth and Vimeo_Query classes
 *
 * @package Vimeotheque
 * @ignore
 */
abstract class Vimeo{

	/**
	 * Vimeo API endpoint
	 */
	const API_ENDPOINT = 'https://api.vimeo.com/';

	/**
	 * API version to be requested
	 * @see https://developer.vimeo.com/api/changelog
	 */
	const VERSION_STRING 	= 'application/vnd.vimeo.*+json; version=3.4';

	/**
	 * Generates a WP_Error
	 *
	 * @param $code
	 * @param $message
	 * @param bool $data
	 *
	 * @return WP_Error
	 */
	protected function error( $code, $message, $data = false ){
		return new WP_Error( $code, $message, $data );
	}

	/**
	 * Process error responses from video into a WP_Error object
	 *
	 * @param $data
	 *
	 * @return WP_Error
	 */
	protected function api_error( $data ){
		if( isset( $data['developer_message'] ) ){
			$message = sprintf(
				__( '%s: %s (error code: %s)', 'codeflavors-vimeo-video-post-lite' ),
				__( 'Vimeo API error encountered', 'codeflavors-vimeo-video-post-lite' ) ,
				$data['developer_message'],
				$data['error_code']
			);
		}elseif ( isset( $data['error'] ) ){
			$message = sprintf(
				'%s: %s',
				__( 'Vimeo API error encountered', 'codeflavors-vimeo-video-post-lite' ),
				$data['error']
			);
		}else{
			$message = __( 'An unknown Vimeo API error has happened. Please try again.', 'codeflavors-vimeo-video-post-lite' );
		}
		// the error is Vimeo specific, flag it as such
		$data['vimeo_api_error'] = true;
		return $this->error( 'vimeo_api_error', $message, $data );
	}
}