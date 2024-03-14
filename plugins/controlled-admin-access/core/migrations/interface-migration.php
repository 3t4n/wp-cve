<?php
namespace WPRuby_CAA\Core\Migrations;

interface Interface_Migration {

	/**
	 * @return bool
	 */
	public function migrate();

	/**
	 * @return string
	 */
	public function version();

}
