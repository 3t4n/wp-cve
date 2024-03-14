<?php
namespace Photonic_Plugin\Components;

class Pagination {
	/**
	 * @var int $start Index of the first element of the current dataset, relative to the total dataset; >= 1
	 */
	public $start;

	/**
	 * @var int $end Index of the last element of the current dataset, relative to the total dataset; <= $total
	 */
	public $end;

	/**
	 * @var int $total Total number of elements in the current dataset
	 */
	public $total;

	/**
	 * @var int $per_page Items attempted to fetch for the current dataset; $per_page >= $end - $start + 1
	 */
	public $per_page;

	/**
	 * @var string $next_token Token to fetch next data set; some platforms use this instead of defining hard counts
	 */
	public $next_token;
}
