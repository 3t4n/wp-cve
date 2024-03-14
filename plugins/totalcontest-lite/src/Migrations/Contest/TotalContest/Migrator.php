<?php

namespace TotalContest\Migrations\Contest\TotalContest;

use TotalContest\Migrations\Contest\Load;

/**
 * TotalContest 3 Migrator.
 * @package TotalContest\Migrations\Contest\TotalContest
 */
class Migrator extends \TotalContest\Migrations\Contest\Migrator {
	/**
	 * Migrator constructor.
	 *
	 * @param array $env
	 */
	public function __construct( $env ) {
		parent::__construct( $env, new Extract(), new Transform(), new Load() );
	}

	/**
	 * @return array
	 */
	#[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return [
			'name'  => 'TotalContest 1.0',
			'image' => $this->env['url'] . 'assets/dist/images/migration/totalcontest-1.png',
			'done'  => $this->getMigratedCount(),
			'total' => $this->getCount(),
		];
	}
}
