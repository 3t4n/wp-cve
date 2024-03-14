<?php
namespace CTXFeed\V5\Query;

class Query {
	/**
	 * @var QueryInterface
	 */
	private $query;

	public function __construct( QueryInterface $query ) {

		$this->query = $query;
	}

	public function get_ids() {
		return $this->query->product_ids();
	}
}
