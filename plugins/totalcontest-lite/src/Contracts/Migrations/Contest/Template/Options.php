<?php

namespace TotalContest\Contracts\Migrations\Contest\Template;

/**
 * Interface Options
 * @package TotalContest\Contracts\Migrations\Contest\Template
 */
interface Options extends Template {
	/**
	 * @param $section
	 * @param $value
	 *
	 * @return mixed
	 */
	public function addOption( $section, $value );
}
