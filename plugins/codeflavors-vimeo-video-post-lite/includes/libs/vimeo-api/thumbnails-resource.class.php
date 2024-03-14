<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Thumbnails_Resource
 * @package Vimeotheque
 * @ignore
 */
class Thumbnails_Resource extends Resource_Abstract implements Resource_Interface {

	/**
	 * Thumbnails_Resource constructor.
	 *
	 * @param $resource_id
	 */
	public function __construct( $resource_id ) {
		parent::__construct( $resource_id, false, false );

		parent::set_name( 'thumbnails', __( 'Thumbnail', 'codeflavors-vimeo-video-post-lite' ) );
	}

	/**
	 * Format the API response
	 *
	 * @param $raw_entry
	 *
	 * @return array
	 */
	public function get_formatted_entry( $raw_entry ) {
		$result = [];

		$thumbnails = [];
		foreach( $raw_entry['data'] as $image ){
			if( $image['active'] ){

				foreach ( $image['sizes'] as $_image ) {
					$thumbnails[ $_image['width'] ] = $_image['link'];
				}

				ksort( $thumbnails, SORT_NUMERIC );
				$thumbnails = array_values( $thumbnails );

				$result = [
					'uri' => $image['uri'],
					'images' => $thumbnails
				];

				break;
			}
		}

		return $result;
	}

	/**
	 * Set specific fields to be retrieved from Vimeo API according to this endpoint
	 *
	 * @return array
	 */
	protected function get_fields() {
		return ['active', 'uri', 'sizes'];
	}

	/**
	 * @return bool
	 */
	public function is_single_entry(){
		return true;
	}

	/**
	 * @return bool
	 */
	public function has_automatic_import() {
		return false;
	}

	/**
	 * Return resource relative API endpoint
	 *
	 * @return string
	 */
	public function get_api_endpoint() {
		return sprintf(
			'videos/%s/pictures',
			$this->resource_id
		);
	}

	/**
	 * Searching within the returned results isn't allowed by API
	 *
	 * @return bool
	 */
	public function can_search_results() {
		return false;
	}

}