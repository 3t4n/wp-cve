<?php

namespace TotalContest\Migrations\Contest\Templates;

use TotalContest\Contracts\Migrations\Contest\Template\Contest as ContestContract;
use TotalContestVendors\TotalCore\Helpers\Arrays;

/**
 * Contest Migration Template.
 * @package TotalContest\Migrations\Contest\Templates
 */
class Contest extends Template implements ContestContract {
	/**
	 * Contest's data.
	 *
	 * @var array $data
	 */
	protected $data = [];

	/**
	 * Contest constructor.
	 */
	public function __construct() {
		$this->data = TotalContest( 'contests.defaults' ) ?: [];
	}

	/**
	 * Set contest title.
	 *
	 * @param $title
	 */
	public function setTitle( $title ) {
		$this->data['title'] = $title;
	}

	/**
	 * Get contest title.
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->data['title'] ?: '';
	}

	/**
	 * Add field to contest.
	 *
	 * @param $field
	 */
	public function addField( $field ) {
		$this->data['contest']['form']['fields'] = Arrays::setDotNotation( $this->data['contest']['form']['fields'], count( $this->data['contest']['form']['fields'] ), $field );
	}

	/**
	 * Add settings section to contest.
	 *
	 * @param $section
	 * @param $value
	 */
	public function addSettings( $section, $value ) {
		$this->data = Arrays::setDotNotation( $this->data, $section, $value );
	}
}
