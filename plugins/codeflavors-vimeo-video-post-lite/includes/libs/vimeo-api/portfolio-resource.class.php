<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Portfolio_Resource
 * @package Vimeotheque
 * @link https://developer.vimeo.com/api/reference/portfolios
 * @ignore
 */
class Portfolio_Resource extends Resource_Abstract implements Resource_Interface {

	/**
	 * Portfolio_Resource constructor.
	 *
	 * @param $resource_id
	 * @param $user_id
	 * @param array $params
	 */
	public function __construct( $resource_id, $user_id = '', $params = [] ) {
		parent::__construct( $resource_id, $user_id, $params );

		parent::set_default_params([
			'filter' => '',
			'filter_embeddable' => false,
			'page' => 1,
			'per_page' => 20,
			'sort' => 'date',
			'direction' => 'desc'
		]);

		parent::set_sort_options(
			[
				'alphabetical',
				'comments',
				'date',
				'default', // the default sort set on the portfolio
				'likes',
				'manual',
				'plays'
			]
		);

		parent::set_filtering_options([
			'embeddable'
		]);

		parent::set_name( 'portfolio', __( 'Portfolio', 'codeflavors-vimeo-video-post-lite' ) );

	}

	/**
	 * Feed can use date limit
	 *
	 * @return bool
	 */
	public function has_date_limit(){
		return true;
	}

	/**
	 * Return resource relative API endpoint
	 *
	 * @return string
	 */
	public function get_api_endpoint() {
		return sprintf(
			'users/%s/portfolios/%s/videos',
			$this->user_id,
			$this->resource_id
		);
	}

	/**
	 * @see Resource_Interface::requires_user_id()
	 *
	 * @return bool
	 */
	public function requires_user_id() {
		return true;
	}

	/**
	 * @see Resource_Interface::label_user_id()
	 *
	 * @return bool|string|void
	 */
	public function label_user_id() {
		return __( 'Portfolio user ID', 'codeflavors-vimeo-video-post-lite' );
	}

	/**
	 * @see Resource_Interface::placeholder_user_id()
	 *
	 * @return bool|string|void
	 */
	public function placeholder_user_id() {
		return __( 'Portfolio owner user ID' );
	}
}