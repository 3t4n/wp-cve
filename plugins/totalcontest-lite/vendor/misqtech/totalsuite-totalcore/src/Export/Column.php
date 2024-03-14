<?php

namespace TotalContestVendors\TotalCore\Export;

/**
 * Class Column
 * @package TotalContestVendors\TotalCore\Export
 */
abstract class Column {
	/**
	 * @var string $title
	 */
	public $title;
	/**
	 * @var string $width
	 */
	public $width;

	/**
	 * Column constructor.
	 *
	 * @param      $title
	 * @param null $width
	 */
	public function __construct( $title, $width = null ) {
		$this->title = $title;
		$this->width = $width;
	}
}
