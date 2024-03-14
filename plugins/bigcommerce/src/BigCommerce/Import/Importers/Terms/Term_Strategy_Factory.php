<?php


namespace BigCommerce\Import\Importers\Terms;

use BigCommerce\Import\Import_Strategy;

class Term_Strategy_Factory {
	/** @var \ArrayAccess */
	private $bc_term;
	/** @var string */
	private $taxonomy;

	public function __construct( \ArrayAccess $bc_term, $taxonomy ) {
		$this->bc_term  = $bc_term;
		$this->taxonomy = $taxonomy;
	}

	/**
	 * Identify the strategy for handling the term import based on
	 * the existing data in WP
	 *
	 * @return Import_Strategy
	 */
	public function get_strategy() {
		$matching_term_id = $this->get_matching_term();
		if ( empty( $matching_term_id ) ) {
			return new Term_Creator( $this->bc_term, $this->taxonomy );
		}

		if ( ! $this->needs_refresh( $matching_term_id ) ) {
			return new Term_Ignorer( $this->bc_term, $this->taxonomy, $matching_term_id );
		}

		return new Term_Updater( $this->bc_term, $this->taxonomy, $matching_term_id );

	}

	/**
	 * Find an existing term based on the same BigCommerce product
	 *
	 * @return int The WordPress term ID, or 0 if not found
	 */
	private function get_matching_term() {
		$terms = get_terms( [
			'taxonomy'   => $this->taxonomy,
			'hide_empty' => false,
			'meta_query' => [
				[
					'key'     => 'bigcommerce_id',
					'value'   => $this->bc_term[ 'id' ] ?: $this->bc_term['category_id'],
					'compare' => '=',
				],
			],
		] );

		if ( ! empty( $terms ) ) {
			return (int) reset( $terms )->term_id;
		}

		return 0;
	}

	private function needs_refresh( $term_id ) {
		if ( get_term_meta( $term_id, Term_Saver::IMPORTER_VERSION_META_KEY, true ) != Import_Strategy::VERSION ) {
			$response = true;
		} else {
			$new_hash = Term_Saver::hash( $this->bc_term );
			$old_hash = get_term_meta( $term_id, Term_Saver::DATA_HASH_META_KEY, true );
			$response = $new_hash !== $old_hash;

		}

		/**
		 * Filter whether the term should be refreshed
		 *
		 * @param bool          $response Whether the term should be refreshed
		 * @param int           $term_id  The ID of the term
		 * @param array         $bc_term  The term data from the API
		 * @param string        $version  The version of the importer
		 */
		return apply_filters( 'bigcommerce/import/strategy/term/needs_refresh', $response, $term_id, $this->bc_term, Import_Strategy::VERSION );
	}
}
