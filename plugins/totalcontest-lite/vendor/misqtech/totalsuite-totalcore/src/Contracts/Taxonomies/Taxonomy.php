<?php

namespace TotalContestVendors\TotalCore\Contracts\Taxonomies;


/**
 * Class Taxonomy
 * @package TotalContestVendors\TotalCore\Taxonomies
 */
interface Taxonomy {
	/**
	 * Register taxonomy.
	 *
	 * @return \WP_Error|\WP_Taxonomy WP_Taxonomy on success, WP_Error otherwise.
	 * @since 1.0.0
	 */
	public function register();

	/**
	 * Get taxonomy name.
	 *
	 * @return mixed
	 */
	public function getName();

	/**
	 * Get post types.
	 *
	 * @return mixed
	 */
	public function getPostTypes();

	/**
	 * Get arguments.
	 *
	 * @return mixed
	 */
	public function getArguments();
}