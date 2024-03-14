<?php

namespace TotalContest\Contracts\Migrations\Contest\Template;

/**
 * Interface Contest
 * @package TotalContest\Contracts\Migrations\Contest\Template
 */
interface Contest extends Template {
	/**
	 * Add field.
	 *
	 * @param $field
	 *
	 * @return mixed
	 */
	public function addField( $field );

	/**
	 * Add settings.
	 *
	 * @param $section
	 * @param $value
	 *
	 * @return mixed
	 */
	public function addSettings( $section, $value );

	/**
	 * Set contest title.
	 *
	 * @param $title
	 *
	 * @return void
	 */
	public function setTitle( $title );

	/**
	 * Get contest title.
	 *
	 * @return string
	 */
	public function getTitle();

}
