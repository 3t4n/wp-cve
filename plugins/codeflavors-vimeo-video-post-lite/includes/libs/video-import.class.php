<?php

namespace Vimeotheque;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Vimeo_Api\Vimeo_Api_Query;
use WP_Error;

/**
 * Class Video_Import
 * @package Vimeotheque
 */
class Video_Import{
	/**
	 * The results array containing all videos
	 *
	 * @var array
	 */
	private $results;

	/**
	 * Total number of entries returned by API query
	 *
	 * @var int
	 */
	private $total_items;

	/**
	 * Current page in API query
	 *
	 * @var int
	 */
	private $page;

	/**
	 * Reached the end of the feed
	 *
	 * @var bool
	 */
	private $end = false;

	/**
	 * Errors
	 *
	 * @var array|string|WP_Error
	 */
	private $errors;

	/**
	 * @var Vimeo_Api_Query
	 */
	private $api;

	/**
	 * Video_Import constructor.
	 *
	 * @param string        $resource_type  The type of resource being queried (album, channel, search, etc.).
	 * @param bool|string   $resource_id    The resource ID that should be retrieved from Vimeo API.
	 * @param bool|string   $user_id        The user ID (if required) that owns the resource or false in case the parameter is not needed.
	 * @param array $args   {
	 *      Additional request parameters.
	 *
	 *      @type   int     $page                   The page number to retrieve from Vimeo API.
	 *      @type   int     $per_page               Number of results per page.
	 *      @type   string  $query                  A search query to search within the set of results for further filtering.
	 *      @type   string  $filter                 Results filtering; has specific value based on the required feed type (ie. playable,
	 *                                              embeddable, featured, live, etc.).
	 *                                              See Vimeo API docs for the spcific resource imported to get the available
	 *                                              filtering options.
	 *      @type   bool    $filter_embeddable      Filter results by embeddable videos (true) or non-embeddable videos (false). Requires
	 *                                              parameter "filter" to be set to "embeddable".
	 *      @type   bool    $filter_playable        Whether to filter the results by playable videos (true) or non-playable videos (false).
	 *      @type   string  $links                  The page containing the video URI.
	 *      @type   string  $password               Password for password restricted resources (ie. showcases).
	 * }
	 */
	public function __construct( $resource_type, $resource_id = false, $user_id = false, $args = [] ){

		$this->api = new Vimeo_Api_Query( $resource_type, $resource_id, $user_id, $args );
		$request = $this->api->request_feed();
		// stop on error
		if( is_wp_error( $request ) ){
			$this->errors = $request;
			return;
		}
		
		$result = json_decode( $request['body'], true );
		
		/* single video entry */
		if( $this->api->get_api_resource()->is_single_entry() ){
			$this->results = $this->api->get_api_resource()->get_formatted_entry( $result );
			return;
		}

		$raw_entries = isset( $result['data'] ) ? $result['data'] : [];
		$entries =	[];
		foreach ( $raw_entries as $entry ){
			$_entry = $this->api->get_api_resource()
			                    ->get_formatted_entry( $entry );

			if( !is_null( $_entry ) ) {
				$entries[] = $_entry;
			}
		}		
		
		$this->results = $entries;
		$this->end = ( !isset( $result['paging']['next'] ) || empty( $result['paging']['next'] ) );
		$this->total_items = isset( $result['total'] ) ? $result['total'] : 0;
		$this->page = isset( $result['page'] ) ? $result['page'] : 0;
	}

	/**
	 * @return array
	 */
	public function get_feed(){
		return $this->results;
	}

	/**
	 * @return int
	 */
	public function get_total_items(){
		return $this->total_items;
	}

	/**
	 * @return int
	 */
	public function get_page(){
		return $this->page;
	}

	/**
	 * @return bool
	 */
	public function has_ended(){
		return $this->end;
	}

	/**
	 * @return array|string|WP_Error
	 */
	public function get_errors(){
		return $this->errors;
	}
}