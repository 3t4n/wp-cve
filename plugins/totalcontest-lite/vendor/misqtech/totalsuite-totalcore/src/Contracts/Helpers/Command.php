<?php

namespace TotalContestVendors\TotalCore\Contracts\Helpers;

interface Command {

	public static function share( $key, $value );

	public static function getShared( $key, $default );

	public function execute();
}