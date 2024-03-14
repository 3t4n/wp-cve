<?php

namespace TotalContest\Migrations\Contest\Templates;

use TotalContest\Contracts\Migrations\Contest\Template\Submission as SubmissionContract;

/**
 * Submission Migration Template.
 *
 * @package TotalContest\Migrations\Contest\Templates
 */
class Submission extends Template implements SubmissionContract {
	/**
	 * Submission entry data.
	 *
	 * @var array $data
	 */
	protected $data = [
		'token'    => '',
		'fields'   => [],
		'contents' => [],
	];


	/**
	 * Set contest submission title.
	 *
	 * @param $title
	 */
	public function setTitle( $title ) {
		$this->data['title'] = $title;
	}

	/**
	 * Get contest submission title.
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->data['title'] ?: '';
	}

}
