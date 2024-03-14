<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Interface Resource_Interface
 * @package Vimeotheque
 * @ignore
 */
interface Resource_Interface{
	/**
	 * Must return the endpoint URI with all neccessary parameters
	 *
	 * @return string
	 */
	public function get_endpoint();

	/**
	 * The method used to retrieve data from Vimeo
	 *
	 * @return string The method to be used when making the request (ie. GET, POST, PATCH, etc)
	 */
	public function get_request_method();

	/**
	 * Returns any additional optional fields that should be set from the
	 * raw video entry returned by Vimeo API
	 *
	 * @return array
	 */
	public function get_optional_fields();

	/**
	 * @param $raw_entry
	 *
	 * @return array
	 */
	public function get_formatted_entry( $raw_entry );

	/**
	 * When used for a single video or other type of single entry, should return true
	 *
	 * @return bool
	 */
	public function is_single_entry();

	/**
	 * If the resource is not a single entry resource and musn't be displayed in importers,
	 * return false.
	 *
	 * @return bool
	 */
	public function enabled_for_importers();

	/**
	 * Resource can be used in automatic import
	 *
	 * @return bool
	 */
	public function has_automatic_import();

	/**
	 * Resource can skip reiteration of feed and import only newly added videos
	 *
	 * @return bool
	 */
	public function can_import_new_videos();

	/**
	 * Feed can have a date limit set for automatic import that it can use
	 * to stop importing if videos are older than a given date
	 *
	 * @return boolean
	 */
	public function has_date_limit();

	/**
	 * Returns the resource output name for the page output
	 *
	 * @return string
	 */
	public function get_output_name();

	/**
	 * Returns the resource ID name
	 *
	 * @return string
	 */
	public function get_name();

	/**
	 * Return resource relative API endpoint
	 *
	 * @return string
	 */
	public function get_api_endpoint();

	/**
	 * Used to retrieve whether feed needs Vimeo user ID to make queries
	 *
	 * @return bool
	 */
	public function requires_user_id();

	/**
	 * Get field label for Vimeo user ID
	 *
	 * @return bool|string
	 */
	public function label_user_id();

	/**
	 * Get placeholder for field Vimeo user ID
	 *
	 * @return bool|string
	 */
	public function placeholder_user_id();

	/**
	 * Some resources allow search within the returned results.
	 * If it's the case, method implementation should return true
	 *
	 * @return bool
	 */
	public function can_search_results();

	/**
	 * Returns the resource default params as specified into the implementation
	 *
	 * @return mixed
	 */
	public function get_default_params();
}