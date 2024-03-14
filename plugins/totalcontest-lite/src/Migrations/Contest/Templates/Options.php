<?php

namespace TotalContest\Migrations\Contest\Templates;

use TotalContest\Contracts\Migrations\Contest\Template\Options as OptionsContract;
use TotalContestVendors\TotalCore\Helpers\Arrays;

/**
 * Options Migration Template.
 * @package TotalContest\Migrations\Contest\Templates
 */
class Options extends Template implements OptionsContract {
	/**
	 * @param $section
	 * @param $value
	 *
	 * @return mixed
	 */
	public function addOption( $section, $value ) {
		$this->data['options'] = Arrays::setDotNotation( $this->data['options'], $section, $value );
	}
}
