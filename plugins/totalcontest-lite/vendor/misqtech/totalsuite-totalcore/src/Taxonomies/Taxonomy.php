<?php

namespace TotalContestVendors\TotalCore\Taxonomies;

use TotalContestVendors\TotalCore\Contracts\Taxonomies\Taxonomy as TaxonomyContract;

/**
 * Class Taxonomy
 * @package TotalContestVendors\TotalCore\Taxonomies
 */
abstract class Taxonomy implements TaxonomyContract {
	/**
	 * Taxonomy constructor.
	 */
	public function __construct() {
		did_action( 'init' ) || doing_action( 'init' ) ? $this->register() : add_action( 'init', [ $this, 'register' ] );
	}

	/**
	 * Register taxonomy.
	 *
	 * @return \WP_Error|\WP_Taxonomy WP_Taxonomy on success, WP_Error otherwise.
	 * @since 1.0.0
	 */
	public function register() {
		/**
		 * @filter totalcore/filters/taxonomy/args Filter passed arguments to register_taxonomy
		 * @since  1.0.0
		 */
		return register_taxonomy( $this->getName(), $this->getPostTypes(), apply_filters( 'totalcore/filters/taxonomy/args', $this->getArguments() ) );
	}
}